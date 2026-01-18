<?php
// Include database connection
require_once 'includes/db_connect.php';

// Get search query and category from request
$search_query = isset($_GET['query']) ? trim($_GET['query']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';

// Initialize suggestions array
$suggestions = [];

// Only process if search query is not empty
if (!empty($search_query)) {
    try {
        // Base query
        $sql = "SELECT p.name, p.description, c.name as category_name 
                FROM products p 
                JOIN categories c ON p.category_id = c.id 
                WHERE (p.name LIKE :query OR p.description LIKE :query)";
        
        // Add category filter if specified
        if (!empty($category)) {
            $sql .= " AND c.name = :category";
        }
        
        // Add ordering and limit
        $sql .= " ORDER BY 
                    CASE 
                        WHEN p.name LIKE :exact_match THEN 1
                        WHEN p.name LIKE :starts_with THEN 2
                        ELSE 3
                    END,
                    p.name ASC
                LIMIT 10";
        
        // Prepare and execute query
        $stmt = $conn->prepare($sql);
        $search_param = "%{$search_query}%";
        $exact_match = $search_query;
        $starts_with = "{$search_query}%";
        
        $stmt->bindParam(':query', $search_param, PDO::PARAM_STR);
        $stmt->bindParam(':exact_match', $exact_match, PDO::PARAM_STR);
        $stmt->bindParam(':starts_with', $starts_with, PDO::PARAM_STR);
        
        if (!empty($category)) {
            $stmt->bindParam(':category', $category, PDO::PARAM_STR);
        }
        
        $stmt->execute();
        $results = $stmt->fetchAll();
        
        // Process results and highlight matching text
        foreach ($results as $row) {
            $highlighted_name = preg_replace(
                '/(' . preg_quote($search_query, '/') . ')/i',
                '<strong>$1</strong>',
                htmlspecialchars($row['name'])
            );
            
            $suggestions[] = [
                'name' => $highlighted_name,
                'category' => htmlspecialchars($row['category_name']),
                'description' => htmlspecialchars($row['description'])
            ];
        }
    } catch (PDOException $e) {
        error_log("Search query failed: " . $e->getMessage());
        // Return empty suggestions array on error
    }
}

// Set JSON response headers
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

// Output suggestions as JSON
echo json_encode(['suggestions' => $suggestions]);
?> 
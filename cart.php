<?php
include 'navbar-top.php';
include 'database/config.php';

if (!isset($_SESSION['user_id'])) {
    exit();
}

$userId = $_SESSION['user_id'];

// 🛒 Fetch cart data
$sql = "SELECT c.*, p.name, p.selling_price, p.image 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$totalAmount = 0;
$cartItems = [];

while ($item = $result->fetch_assoc()) {
    $subtotal = $item['selling_price'] * $item['quantity'];
    $totalAmount += $subtotal;
    $cartItems[] = $item;
}

// Fetch user's saved address if exists
$addressSql = "SELECT * FROM user_addresses WHERE user_id = ?";
$addressStmt = $con->prepare($addressSql);
$addressStmt->bind_param("i", $userId);
$addressStmt->execute();
$addressResult = $addressStmt->get_result();
$savedAddress = $addressResult->fetch_assoc();

// Process address form submission
$addressError = '';
$addressSuccess = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_address'])) {
    $fullName = trim($_POST['full_name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $state = trim($_POST['state']);
    $pincode = trim($_POST['pincode']);
    
    // Validate inputs
    if (empty($fullName) || empty($phone) || empty($address) || empty($city) || empty($state) || empty($pincode)) {
        $addressError = "All fields are required";
    } elseif (!preg_match("/^[0-9]{10}$/", $phone)) {
        $addressError = "Please enter a valid 10-digit phone number";
    } elseif (!preg_match("/^[0-9]{6}$/", $pincode)) {
        $addressError = "Please enter a valid 6-digit pincode";
    } else {
        // Check if address already exists
        if ($savedAddress) {
            // Update existing address
            $updateSql = "UPDATE user_addresses SET 
                          full_name = ?, phone = ?, address = ?, city = ?, state = ?, pincode = ? 
                          WHERE user_id = ?";
            $updateStmt = $con->prepare($updateSql);
            $updateStmt->bind_param("ssssssi", $fullName, $phone, $address, $city, $state, $pincode, $userId);
            
            if ($updateStmt->execute()) {
                $addressSuccess = "Address updated successfully!";
                $savedAddress = [
                    'full_name' => $fullName,
                    'phone' => $phone,
                    'address' => $address,
                    'city' => $city,
                    'state' => $state,
                    'pincode' => $pincode
                ];
            } else {
                $addressError = "Error updating address. Please try again.";
            }
        } else {
            // Insert new address
            $insertSql = "INSERT INTO user_addresses (user_id, full_name, phone, address, city, state, pincode) 
                          VALUES (?, ?, ?, ?, ?, ?, ?)";
            $insertStmt = $con->prepare($insertSql);
            $insertStmt->bind_param("issssss", $userId, $fullName, $phone, $address, $city, $state, $pincode);
            
            if ($insertStmt->execute()) {
                $addressSuccess = "Address saved successfully!";
                $savedAddress = [
                    'full_name' => $fullName,
                    'phone' => $phone,
                    'address' => $address,
                    'city' => $city,
                    'state' => $state,
                    'pincode' => $pincode
                ];
            } else {
                $addressError = "Error saving address. Please try again.";
            }
        }
    }
}
?>

<!-- ✅ STYLE SECTION -->
<style>
    body {
        background-color: #f8f9fa;
        padding-bottom: 100px;
    }
    .cart-box, .address-box {
        background-color: #fff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0px 0px 12px rgba(0, 0, 0, 0.05);
        margin-bottom: 20px;
    }
    .cart-title, .address-title {
        color: #05a39c;
        font-weight: bold;
    }
    .btn-primary {
        background-color: #05a39c;
        border-color: #05a39c;
    }
    .btn-primary:hover {
        background-color: #048a83;
        border-color: #048a83;
    }
    .table td, .table th {
        vertical-align: middle;
    }
    .address-form .form-control:focus {
        border-color: #05a39c;
        box-shadow: 0 0 0 0.25rem rgba(5, 163, 156, 0.25);
    }
    .address-summary {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    .address-summary p {
        margin-bottom: 5px;
    }
    .address-summary .edit-address {
        color: #05a39c;
        cursor: pointer;
        text-decoration: underline;
    }
</style>

<!-- ✅ MAIN CART DISPLAY -->
<div class="container mt-5 mb-5">
    <div class="cart-box">
        <h4 class="cart-title mb-4">🛒 Shopping Cart</h4>

        <?php if (count($cartItems) > 0): ?>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>Image</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($cartItems as $item): 
                        $subtotal = $item['selling_price'] * $item['quantity'];
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td><img src="uploads/<?= $item['image'] ?>" width="60" class="rounded shadow-sm"></td>
                            <td>₹<?= number_format($item['selling_price'], 2) ?></td>
                            <td>
                                <form method="POST" action="database/update-cart-quantity.php" class="d-flex align-items-center">
                                    <input type="hidden" name="cart_id" value="<?= $item['id'] ?>">
                                    <button type="button" class="btn btn-sm btn-outline-secondary quantity-btn" data-action="decrease">-</button>
                                    <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" class="form-control form-control-sm mx-2 text-center" style="width: 60px;">
                                    <button type="button" class="btn btn-sm btn-outline-secondary quantity-btn" data-action="increase">+</button>
                                </form>
                            </td>
                            <td>₹<?= number_format($subtotal, 2) ?></td>
                            <td>
                                <form method="POST" action="database/remove-from-cart.php" class="d-inline">
                                    <input type="hidden" name="cart_id" value="<?= $item['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- 🔷 PRICE SUMMARY -->
            <div class="mt-4 p-3 bg-light rounded">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Total Items: <?= count($cartItems) ?></h5>
                    <h4 class="mb-0">Total Amount: ₹<?= number_format($totalAmount, 2) ?></h4>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">Your shopping cart is currently empty. <a href="index.php" class="text-decoration-underline">Shop now</a></div>
        <?php endif; ?>
    </div>

    <?php if (count($cartItems) > 0): ?>
    <!-- 🔷 ADDRESS FORM -->
    <div class="address-box">
        <h4 class="address-title mb-4">📍 Delivery Address</h4>
        
        <?php if ($addressSuccess): ?>
            <div class="alert alert-success"><?= $addressSuccess ?></div>
        <?php endif; ?>
        
        <?php if ($addressError): ?>
            <div class="alert alert-danger"><?= $addressError ?></div>
        <?php endif; ?>
        
        <?php if ($savedAddress && !isset($_POST['edit_address'])): ?>
            <!-- Display saved address -->
            <div class="address-summary">
                <h5>Saved Address</h5>
                <p><strong><?= htmlspecialchars($savedAddress['full_name']) ?></strong> | <?= htmlspecialchars($savedAddress['phone']) ?></p>
                <p><?= htmlspecialchars($savedAddress['address']) ?></p>
                <p><?= htmlspecialchars($savedAddress['city']) ?>, <?= htmlspecialchars($savedAddress['state']) ?> - <?= htmlspecialchars($savedAddress['pincode']) ?></p>
                <p class="edit-address" onclick="showAddressForm()">Edit Address</p>
            </div>
            
            <div id="address-form-container" style="display: none;">
                <form method="POST" class="address-form">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="full_name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" value="<?= htmlspecialchars($savedAddress['full_name']) ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($savedAddress['phone']) ?>" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Street Address</label>
                        <textarea class="form-control" id="address" name="address" rows="2" required><?= htmlspecialchars($savedAddress['address']) ?></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="form-control" id="city" name="city" value="<?= htmlspecialchars($savedAddress['city']) ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="state" class="form-label">State</label>
                            <input type="text" class="form-control" id="state" name="state" value="<?= htmlspecialchars($savedAddress['state']) ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="pincode" class="form-label">Pincode</label>
                            <input type="text" class="form-control" id="pincode" name="pincode" value="<?= htmlspecialchars($savedAddress['pincode']) ?>" required>
                        </div>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="button" class="btn btn-secondary" onclick="hideAddressForm()">Cancel</button>
                        <button type="submit" name="save_address" class="btn btn-primary">Save Address</button>
                    </div>
                </form>
            </div>
            
            <div class="d-grid gap-2 mt-4">
                <a href="checkout.php" class="btn btn-primary">🚀 Proceed to Checkout</a>
            </div>
        <?php else: ?>
            <!-- Address form -->
            <form method="POST" class="address-form">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="full_name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="full_name" name="full_name" value="<?= isset($savedAddress) ? htmlspecialchars($savedAddress['full_name']) : '' ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="phone" name="phone" value="<?= isset($savedAddress) ? htmlspecialchars($savedAddress['phone']) : '' ?>" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Street Address</label>
                    <textarea class="form-control" id="address" name="address" rows="2" required><?= isset($savedAddress) ? htmlspecialchars($savedAddress['address']) : '' ?></textarea>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="city" class="form-label">City</label>
                        <input type="text" class="form-control" id="city" name="city" value="<?= isset($savedAddress) ? htmlspecialchars($savedAddress['city']) : '' ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="state" class="form-label">State</label>
                        <input type="text" class="form-control" id="state" name="state" value="<?= isset($savedAddress) ? htmlspecialchars($savedAddress['state']) : '' ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="pincode" class="form-label">Pincode</label>
                        <input type="text" class="form-control" id="pincode" name="pincode" value="<?= isset($savedAddress) ? htmlspecialchars($savedAddress['pincode']) : '' ?>" required>
                    </div>
                </div>
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" name="save_address" class="btn btn-primary">Save Address</button>
                </div>
            </form>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<script>
function showAddressForm() {
    document.getElementById('address-form-container').style.display = 'block';
}

function hideAddressForm() {
    document.getElementById('address-form-container').style.display = 'none';
}

// Form validation
document.addEventListener('DOMContentLoaded', function() {
    const addressForm = document.querySelector('.address-form');
    if (addressForm) {
        addressForm.addEventListener('submit', function(e) {
            const phone = document.getElementById('phone').value;
            const pincode = document.getElementById('pincode').value;
            
            if (!/^[0-9]{10}$/.test(phone)) {
                e.preventDefault();
                alert('Please enter a valid 10-digit phone number');
            }
            else if (!/^[0-9]{6}$/.test(pincode)) {
                e.preventDefault();
                alert('Please enter a valid 6-digit pincode');
            }
        });
    }
});

// Add this script at the end of the file, before the closing body tag
document.addEventListener('DOMContentLoaded', function() {
    // Handle quantity buttons
    document.querySelectorAll('.quantity-btn').forEach(button => {
        button.addEventListener('click', function() {
            const form = this.closest('form');
            const input = form.querySelector('input[name="quantity"]');
            const currentValue = parseInt(input.value);
            
            if (this.dataset.action === 'increase') {
                input.value = currentValue + 1;
            } else if (this.dataset.action === 'decrease' && currentValue > 1) {
                input.value = currentValue - 1;
            }
            
            // Submit the form when quantity changes
            form.submit();
        });
    });
});
</script>

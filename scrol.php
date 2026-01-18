<?php
// Include database connection
include 'database/config.php';

// Fetch active coupons
$today = date('Y-m-d');
$couponQuery = "SELECT code, discount, expiry_date FROM coupons WHERE expiry_date >= '$today' ORDER BY expiry_date ASC LIMIT 3";
$couponResult = mysqli_query($con, $couponQuery);
$coupons = [];
while ($row = mysqli_fetch_assoc($couponResult)) {
    $coupons[] = $row;
}

// Fetch active offers
$offerQuery = "SELECT title, discount FROM offers ORDER BY id DESC LIMIT 2";
$offerResult = mysqli_query($con, $offerQuery);
$offers = [];
while ($row = mysqli_fetch_assoc($offerResult)) {
    $offers[] = $row;
}
?>

<div class="bg-light py-2 marquee-container border-bottom">
    <div class="marquee" id="marquee">
        < WE MAKE YOUR CONSTRUCTION EASY > LOCAL LEVEL SERVICE
        
        <?php if (!empty($coupons)): ?>
            > <span class="text-success">SPECIAL OFFERS: 
            <?php foreach ($coupons as $coupon): ?>
                <span class="coupon-item" data-code="<?php echo htmlspecialchars($coupon['code']); ?>" data-expiry="<?php echo htmlspecialchars($coupon['expiry_date']); ?>">
                    Use code <strong><?php echo htmlspecialchars($coupon['code']); ?></strong> for <?php echo $coupon['discount']; ?>% OFF 
                    <span class="expiry-date">(Expires: <?php echo date('d M Y', strtotime($coupon['expiry_date'])); ?>)</span> | 
                </span>
            <?php endforeach; ?>
            </span>
        <?php endif; ?>
        
        <?php if (!empty($offers)): ?>
            > <span class="text-danger">LIMITED TIME: 
            <?php foreach ($offers as $offer): ?>
                <?php echo htmlspecialchars($offer['title']); ?> - <?php echo $offer['discount']; ?>% OFF | 
            <?php endforeach; ?>
            </span>
        <?php endif; ?>
        
        > FREE SHIPPING ON ORDERS ABOVE ₹2000 > CASH ON DELIVERY AVAILABLE
    </div>
</div>

<style>
    .marquee-container {
        width: 100%;
        background: #f8f9fa;
        padding: 5px 0;
        z-index: 1080;
    }

    .marquee {
        display: inline-block;
        white-space: nowrap;
        animation: marqueeScroll 30s linear infinite;
        font-weight: bold;
    }

    @keyframes marqueeScroll {
        from { transform: translateX(100%); }
        to { transform: translateX(-100%); }
    }
    
    .marquee strong {
        color: #05a39c;
        font-weight: 800;
    }
    
    .text-success {
        color: #28a745 !important;
    }
    
    .text-danger {
        color: #dc3545 !important;
    }
    
    /* Interactive Elements */
    .coupon-item {
        cursor: pointer;
        transition: all 0.3s ease;
        padding: 2px 5px;
        border-radius: 4px;
    }
    
    .coupon-item:hover {
        background-color: rgba(5, 163, 156, 0.1);
        transform: scale(1.05);
    }
    
    .expiry-date {
        font-size: 0.8em;
        opacity: 0.8;
        font-style: italic;
    }
    
    /* Pause animation on hover */
    .marquee-container:hover .marquee {
        animation-play-state: paused;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Coupon click functionality
        const couponItems = document.querySelectorAll('.coupon-item');
        couponItems.forEach(item => {
            item.addEventListener('click', function() {
                const code = this.getAttribute('data-code');
                const expiry = this.getAttribute('data-expiry');
                
                // Show coupon details in an alert
                alert(`Coupon Code: ${code}\nExpires: ${new Date(expiry).toLocaleDateString()}`);
                
                // Copy to clipboard
                navigator.clipboard.writeText(code).then(() => {
                    // Show a small notification
                    const notification = document.createElement('div');
                    notification.textContent = 'Coupon code copied!';
                    notification.style.position = 'fixed';
                    notification.style.bottom = '20px';
                    notification.style.right = '20px';
                    notification.style.backgroundColor = '#05a39c';
                    notification.style.color = 'white';
                    notification.style.padding = '10px 20px';
                    notification.style.borderRadius = '5px';
                    notification.style.zIndex = '9999';
                    document.body.appendChild(notification);
                    
                    // Remove notification after 2 seconds
                    setTimeout(() => {
                        notification.remove();
                    }, 2000);
                });
            });
        });
    });
</script>

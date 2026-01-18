<?php
include 'navbar-top.php';
include 'database/config.php';
include 'includes/razorpay_config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// Fetch cart items
$sql = "SELECT c.*, p.name, p.selling_price, p.image FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = $userId";
$result = mysqli_query($con, $sql);

$cartItems = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $cartItems[] = $row;
    }
}

$total = 0;
foreach ($cartItems as $item) {
    $total += $item['selling_price'] * $item['quantity'];
}

// Initialize coupon variables
$discountAmount = 0;
$finalAmount = $total;
$couponMsg = '';
$discountPercent = 0;
$appliedCouponCode = '';

// Process coupon code if submitted
if (isset($_POST['apply_coupon'])) {
    $code = strtoupper(trim($_POST['coupon_code']));
    $today = date('Y-m-d');

    $couponStmt = $con->prepare("SELECT * FROM coupons WHERE code = ? AND expiry_date >= ?");
    $couponStmt->bind_param("ss", $code, $today);
    $couponStmt->execute();
    $couponResult = $couponStmt->get_result();

    if ($couponResult->num_rows > 0) {
        $coupon = $couponResult->fetch_assoc();
        $discountPercent = $coupon['discount'];

        $discountAmount = $total * ($discountPercent / 100);
        $finalAmount = $total - $discountAmount;
        $appliedCouponCode = $code;

        $couponMsg = "✅ Coupon applied! You saved ₹" . number_format($discountAmount, 2) . " ({$discountPercent}%).";
    } else {
        $couponMsg = "❌ Invalid or expired coupon.";
    }
}

// Create Razorpay Order with final amount (after discount)
$orderData = [
    'amount' => $finalAmount * 100, // Amount in paise
    'currency' => 'INR',
    'receipt' => 'order_' . time() . '_' . $userId,
    'payment_capture' => 1 // Auto capture payment
];

try {
    $razorpayOrder = $api->order->create($orderData);
    $razorpayOrderId = $razorpayOrder['id'];
    $_SESSION['razorpay_order_id'] = $razorpayOrderId;
} catch (Exception $e) {
    error_log("Razorpay Error: " . $e->getMessage());
    $razorpayError = "Unable to create order. Please try again.";
}

// Fetch user details for pre-filling
$userSql = "SELECT name, email FROM users WHERE id = $userId";
$userResult = mysqli_query($con, $userSql);
$userDetails = mysqli_fetch_assoc($userResult);
?>

<!-- Include Razorpay JavaScript SDK -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<!-- Custom Styles -->
<style>
    body {
        background-color: #f8f9fa;
    }
    .checkout-box {
        background: #fff;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }
    .checkout-title {
        color: #05a39c;
        font-weight: bold;
    }
    .list-group-item {
        border: none;
        border-bottom: 1px solid #ddd;
        background-color: #fefefe;
    }
    .list-group-item:last-child {
        border-bottom: none;
    }
    .btn-checkout {
        background-color: #05a39c;
        color: white;
        font-weight: 600;
    }
    .btn-checkout:hover {
        background-color: #048a83;
    }
    .payment-option {
        border: 2px solid #ddd;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .payment-option:hover {
        border-color: #05a39c;
    }
    .payment-option.selected {
        border-color: #05a39c;
        background-color: #f8fffd;
    }
    .payment-icon {
        font-size: 24px;
        margin-right: 10px;
    }
    .coupon-section {
        margin-top: 20px;
        margin-bottom: 20px;
    }
    .coupon-section input {
        max-width: 250px;
    }
</style>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="checkout-box">
                <h4 class="checkout-title mb-4">🛒 Checkout</h4>

                <?php if (isset($razorpayError)): ?>
                    <div class="alert alert-danger"><?php echo $razorpayError; ?></div>
                <?php endif; ?>

                <?php if (isset($_SESSION['payment_error'])): ?>
                    <div class="alert alert-danger">
                        <?php 
                        echo $_SESSION['payment_error']; 
                        unset($_SESSION['payment_error']); // Clear the error after displaying
                        ?>
                    </div>
                <?php endif; ?>

                <?php if (count($cartItems) > 0): ?>
                    <h5 class="mb-3">Order Summary</h5>
                    <ul class="list-group mb-4">
                        <?php foreach ($cartItems as $item): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong><?= htmlspecialchars($item['name']) ?></strong>
                                    <br>
                                    <small>Quantity: x<?= $item['quantity'] ?></small>
                                </div>
                                <span>₹<?= number_format($item['selling_price'] * $item['quantity'], 2) ?></span>
                            </li>
                        <?php endforeach; ?>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Subtotal</strong>
                            <strong>₹<?= number_format($total, 2) ?></strong>
                        </li>
                        
                        <?php if ($discountAmount > 0): ?>
                        <li class="list-group-item d-flex justify-content-between text-success">
                            <strong>Discount (<?= $discountPercent ?>%)</strong>
                            <strong>-₹<?= number_format($discountAmount, 2) ?></strong>
                        </li>
                        <?php endif; ?>
                        
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Total</strong>
                            <strong>₹<?= number_format($finalAmount, 2) ?></strong>
                        </li>
                    </ul>

                    <!-- Coupon Form -->
                    <form method="POST" class="coupon-section d-flex flex-wrap gap-2">
                        <input type="text" name="coupon_code" class="form-control" placeholder="Enter Coupon Code" style="border-color: #05a39c;" value="<?= htmlspecialchars($appliedCouponCode) ?>">
                        <button type="submit" name="apply_coupon" class="btn" style="border-color: #05a39c;color: #05a39c">Apply</button>
                    </form>

                    <?php if (!empty($couponMsg)): ?>
                        <div class="alert alert-info"><?= $couponMsg ?></div>
                    <?php endif; ?>

                    <h5 class="mb-3">Select Payment Method</h5>
                    <div class="payment-options mb-4">
                        <div class="payment-option selected" data-payment="razorpay">
                            <i class="bi bi-credit-card payment-icon"></i>
                            Pay Online (Credit/Debit Card, UPI, Netbanking)
                        </div>
                        <div class="payment-option" data-payment="cod">
                            <i class="bi bi-cash payment-icon"></i>
                            Cash on Delivery
                        </div>
                    </div>

                    <div id="razorpay-button">
                        <button type="button" id="pay-btn" class="btn btn-checkout w-100">
                            <i class="bi bi-lock"></i> Pay Securely ₹<?= number_format($finalAmount, 2) ?>
                        </button>
                    </div>

                    <form action="database/process_order.php" method="POST" id="cod-form" style="display: none;">
                        <input type="hidden" name="payment_method" value="COD">
                        <input type="hidden" name="total_price" value="<?= $finalAmount ?>">
                        <input type="hidden" name="coupon_code" value="<?= htmlspecialchars($appliedCouponCode) ?>">
                        <input type="hidden" name="discount_amount" value="<?= $discountAmount ?>">
                        <button type="submit" name="place_order" class="btn btn-checkout w-100">
                            🚚 Place Order (Cash on Delivery)
                        </button>
                    </form>
                <?php else: ?>
                    <div class="alert alert-warning">Your cart is empty. <a href="index.php" class="text-decoration-underline">Shop now</a></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentOptions = document.querySelectorAll('.payment-option');
    const razorpayButton = document.getElementById('razorpay-button');
    const codForm = document.getElementById('cod-form');

    // Payment method selection
    paymentOptions.forEach(option => {
        option.addEventListener('click', function() {
            paymentOptions.forEach(opt => opt.classList.remove('selected'));
            this.classList.add('selected');
            
            const paymentMethod = this.dataset.payment;
            if (paymentMethod === 'razorpay') {
                razorpayButton.style.display = 'block';
                codForm.style.display = 'none';
            } else {
                razorpayButton.style.display = 'none';
                codForm.style.display = 'block';
            }
        });
    });

    // Initialize Razorpay
    const options = {
        key: '<?php echo RAZORPAY_KEY_ID; ?>', // Enter the Key ID generated from the Dashboard
        amount: '<?php echo $finalAmount * 100; ?>', // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
        currency: 'INR',
        name: 'BuildBond',
        description: 'Purchase Payment',
        image: 'assets/logo.png',
        order_id: '<?php echo $razorpayOrderId; ?>', // This is a sample Order ID. Pass the `id` obtained in the response of createOrder().
        handler: function (response) {
            // On payment success
            console.log("Payment successful, redirecting to process_payment.php");
            document.location.href = 'database/process_payment.php?razorpay_payment_id=' + response.razorpay_payment_id + 
                '&razorpay_order_id=' + response.razorpay_order_id + 
                '&razorpay_signature=' + response.razorpay_signature + 
                '&coupon_code=<?php echo urlencode($appliedCouponCode); ?>' + 
                '&discount_amount=<?php echo $discountAmount; ?>';
        },
        prefill: {
            name: '<?php echo htmlspecialchars($userDetails['name']); ?>',
            email: '<?php echo htmlspecialchars($userDetails['email']); ?>'
        },
        theme: {
            color: '#05a39c'
        }
    };
    const rzp = new Razorpay(options);

    document.getElementById('pay-btn').onclick = function(e) {
        rzp.open();
        e.preventDefault();
    }
});
</script>

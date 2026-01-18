-- Add Razorpay-specific columns to orders table
ALTER TABLE orders
ADD COLUMN razorpay_order_id VARCHAR(255) NULL AFTER payment_method,
ADD COLUMN razorpay_payment_id VARCHAR(255) NULL AFTER razorpay_order_id; 
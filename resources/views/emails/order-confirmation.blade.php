<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
</head>
<body>
    <h1>Order Confirmation</h1>
    
    <p>Dear {{ $order->firstname }} {{ $order->lastname }},</p>
    
    <p>Thank you for placing an order with us. Your order has been successfully confirmed.</p>
    
    <h2>Order Details:</h2>
    
    <p>Order ID: {{ $order->id }}</p>
    <p>Order Date: {{ $order->created_at->format('Y-m-d H:i:s') }}</p>
    <!-- Include any other relevant order details -->
    
    <p>If you have any questions or need further assistance, please don't hesitate to contact us.</p>
    
    <p>Best regards,</p>
    <p>Your Company Name</p>
</body>
</html>

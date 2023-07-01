<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
</head>
<body>
    <h1>Order Confirmation</h1>
    
    <p>Dear {{ $order->firstname }} {{ $order->lastname }},</p>
    
    <p>Thank you for placing an order with us. Your order has been successfully confirmed.</p>

    <h5>Payment Methods: Pay after receive</h5>
    
    <h2>Order Details:</h2>
    
    <p>Order Date: {{ $order->created_at->format('d-m-Y H:i:s') }}</p>
    <!-- Include any other relevant order details -->
    <p>Link: http://localhost:3000/order-list</p>
    
    <p>If you have any questions or need further assistance, please don't hesitate to contact us.</p>
    
    <p>Best regards,</p>
    <p>H Store customer service.</p>
</body>
</html>

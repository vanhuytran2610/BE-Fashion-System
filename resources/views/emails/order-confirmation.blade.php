<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
</head>
<body>
    <h1>Order Confirmation</h1>
    
    <p style="font-weight: 600;">Dear {{ $order->firstname }} {{ $order->lastname }},</p>
    
    <p style="font-weight: 600;">Thank you for placing an order with us. Your order has been successfully confirmed.</p>

    <h5>Payment Methods: Payment on delivery</h5>
    
    <h2>Order Details:</h2>
    
    <p style="font-weight: 600;">Order Date: {{ $order->created_at->format('d-m-Y H:i:s') }}</p>
    <!-- Include any other relevant order details -->
    <p style="font-weight: 600;">Link: http://localhost:3000/order-list</p>
    
    <p style="font-weight: 600;">If you have any questions or need further assistance, please don't hesitate to contact us.</p>
    
    <p style="font-weight: 600;">Best regards,</p>
    <p style="font-weight: 600;">H Store customer service.</p>
</body>
</html>

@php
    $user = $order->user ?? null;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->id }}</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f9f9fb; color: #222; margin: 0; padding: 0; }
        .invoice-box { max-width: 700px; margin: 40px auto; background: #fff; border-radius: 14px; box-shadow: 0 8px 32px rgba(44, 62, 80, 0.12); padding: 40px 32px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; }
        .logo { font-size: 2rem; font-weight: 700; color: #5a67d8; }
        .invoice-title { font-size: 1.4rem; font-weight: 600; color: #4a5568; }
        .section-title { font-size: 1.1rem; font-weight: 600; color: #6b46c1; margin-top: 32px; margin-bottom: 12px; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        .info-table td { padding: 6px 0; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 32px; }
        .items-table th, .items-table td { border-bottom: 1px solid #e2e8f0; padding: 10px 8px; text-align: left; }
        .items-table th { background: #f3f0ff; color: #6b46c1; font-weight: 700; }
        .totals { margin-top: 16px; float: right; }
        .totals td { padding: 6px 16px; font-size: 1rem; }
        .totals .label { color: #4a5568; font-weight: 500; }
        .totals .value { color: #2d3748; font-weight: 700; }
        .footer { margin-top: 60px; color: #718096; font-size: 0.95rem; text-align: center; }
    </style>
</head>
<body>
<div class="invoice-box">
    <div class="header">
        <div class="logo">MediMart</div>
        <div class="invoice-title">Invoice #{{ $order->id }}</div>
    </div>
    <table class="info-table">
        <tr>
            <td><strong>Billed To:</strong></td>
            <td>
                {{ $user ? $user->name : ($order->customer_name ?? '-') }}<br>
                {{ $user ? $user->address : ($order->delivery_address ?? '-') }}<br>
                {{ $user ? $user->phone : ($order->contact_phone ?? '-') }}
            </td>
            <td><strong>Order Date:</strong></td>
            <td>{{ $order->created_at ? $order->created_at->format('F j, Y h:i A') : '-' }}</td>
        </tr>
        <tr>
            <td><strong>Email:</strong></td>
            <td>{{ $user ? $user->email : '-' }}</td>
            <td><strong>Status:</strong></td>
            <td>{{ $order->status }}</td>
        </tr>
    </table>
    <div class="section-title">Order Items</div>
    <table class="items-table">
        <thead>
        <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Unit Price</th>
            <th>Total</th>
        </tr>
        </thead>
        <tbody>
        @foreach($order->items as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->price, 2) }}</td>
                <td>{{ number_format($item->price * $item->quantity, 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <table class="totals">
        <tr>
            <td class="label">Subtotal:</td>
            <td class="value">{{ number_format($order->subtotal, 2) }}</td>
        </tr>
        <tr>
            <td class="label">Delivery Fee:</td>
            <td class="value">{{ number_format($order->delivery_charge, 2) }}</td>
        </tr>
        @if($order->discount && $order->discount > 0)
            <tr>
                <td class="label">Discount:</td>
                <td class="value">-{{ number_format($order->discount, 2) }}</td>
            </tr>
        @endif
        <tr>
            <td class="label"><strong>Total:</strong></td>
            <td class="value"><strong>{{ number_format($order->total, 2) }}</strong></td>
        </tr>
    </table>
    <div style="clear: both;"></div>
    <div class="footer">
        Thank you for shopping with MediMart!<br>
        Contact Us: support@medimart.com | +88-01711008229
    </div>
</div>
</body>
</html>

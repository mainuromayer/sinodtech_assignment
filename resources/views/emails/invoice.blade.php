<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        .header { text-align: center; border-bottom: 2px solid #3b82f6; padding-bottom: 10px; margin-bottom: 20px; }
        .details { margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background-color: #f3f4f6; }
        .total { text-align: right; font-weight: bold; font-size: 1.2em; }
        .footer { text-align: center; font-size: 0.8em; color: #777; margin-top: 20px; border-top: 1px solid #ddd; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Invoice</h2>
            <p>Sale #{{ $sale->id }}</p>
        </div>
        <div class="details">
            <p><strong>Date:</strong> {{ $sale->created_at->format('M d, Y h:i A') }}</p>
            <p><strong>Branch:</strong> {{ $sale->branch->name }}</p>
            @if($sale->customer)
                <p><strong>Customer:</strong> {{ $sale->customer->name }} ({{ $sale->customer->email }})</p>
            @endif
            @if($sale->employee)
                <p><strong>Assisted By:</strong> {{ $sale->employee->name }}</p>
            @endif
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->items as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>৳{{ number_format($item->price, 2) }}</td>
                        <td>৳{{ number_format($item->quantity * $item->price, 2) }}</td>
                    </tr>
                @end@foreach
            </tbody>
        </table>
        <div class="total">
            Total Amount: ৳{{ number_format($sale->total_amount, 2) }}
        </div>
        <div class="footer">
            Thank you for shopping with us!
        </div>
    </div>
</body>
</html>

@extends('layouts.admin')

@section('content')
<h1>Order Detail</h1>

<p><strong>Order ID:</strong> {{ $order->id }}</p>
<p><strong>Customer Name:</strong> {{ $order->customer_name }}</p>
<p><strong>Table:</strong> {{ $order->table_number }}</p>
<p><strong>Total:</strong> Rp{{ number_format($order->total_price, 0, ',', '.') }}</p>

<h3>Order Items</h3>
<table class="table">
    <thead>
        <tr>
            <th>No</th>
            <th>Menu Item</th>
            <th>Category</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($order->orderItems as $index => $item)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $item->menuItem->name }}</td>
            <td>{{ $item->menuItem->category->name }}</td>
            <td>{{ $item->quantity }}</td>
            <td>Rp{{ number_format($item->menuItem->price, 0, ',', '.') }}</td>
            <td>Rp{{ number_format($item->quantity * $item->menuItem->price, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<a href="{{ route('orders.print', $order->id) }}" class="btn btn-success">Print Struk</a>
@endsection

@extends('admin.layout')

@section('content')
<h1>Orders</h1>

<table class="table">
    <thead>
        <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Table</th>
            <th>Total</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($orders as $order)
        <tr>
            <td>{{ $order->id }}</td>
            <td>{{ $order->customer_name }}</td>
            <td>{{ $order->table_number }}</td>
            <td>Rp{{ number_format($order->total_price, 0, ',', '.') }}</td>
            <td>{{ $order->status }}</td>
            <td>
                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-prima">View</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection

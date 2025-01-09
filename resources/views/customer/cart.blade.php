@extends('customer.layout')

@section('content')
<h2>Your Cart</h2>

@foreach ($carts as $cart)
    <div>
        <h4>{{ $cart->menuItem->name }}</h4>
        <p>Harga: {{ $cart->menuItem->price }}</p>
        <p>Jumlah: {{ $cart->quantity }}</p>
    </div>
@endforeach

<button onclick="checkout()">Checkout</button>
@endsection


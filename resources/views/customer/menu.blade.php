@extends('customer.layout')

@section('content')
<h2>Menu</h2>

@foreach ($categories as $category)
    <h3>{{ $category->name }}</h3>
    <div class="menu-grid">
        @foreach ($category->menuItems as $item)
            <div class="menu-item">
                <img src="{{ $item->image }}" alt="{{ $item->name }}">
                <h4>{{ $item->name }}</h4>
                <p>{{ $item->description }}</p>
                <p>Rp {{ $item->price }}</p>
                <button onclick="addToCart({
                    id: {{ $item->id }},
                    name: '{{ $item->name }}',
                    price: {{ $item->price }}
                })">Add to Cart</button>
            </div>
        @endforeach
    </div>
@endforeach
@endsection

@section('scripts')
<script src="{{ asset('js/cart.js') }}"></script>
<script>
    function addToCart(item) {
        fetch('/cart', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Tambahkan token CSRF
            },
            body: JSON.stringify(item)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(`${item.name} has been added to the cart.`);
            } else {
                alert('Failed to add item to the cart.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
</script>
@endsection

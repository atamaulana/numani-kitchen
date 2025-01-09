@extends('layouts.app')

@section('content')
<h1>Welcome to the Customer Panel</h1>
<p>Hello, {{ auth()->user()->name }}!</p>
<a href="{{ route('customer.profile') }}">View Profile</a>
<a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
@endsection

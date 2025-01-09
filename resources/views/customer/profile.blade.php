@extends('layouts.app')

@section('content')
<h1>Your Profile</h1>
@if(session('success'))
    <div>{{ session('success') }}</div>
@endif

<form action="{{ route('customer.profile.update') }}" method="POST">
    @csrf
    <div>
        <label for="name">Name:</label>
        <input type="text" name="name" value="{{ $user->name }}" required>
    </div>
    <div>
        <label for="email">Email:</label>
        <input type="email" name="email" value="{{ $user->email }}" required>
    </div>
    <button type="submit">Update Profile</button>
</form>

<a href="{{ route('customer.home') }}">Back to Home</a>
@endsection

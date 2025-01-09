@extends('admin.layout')

@section('content')
<div style="max-width: 600px; margin: 30px auto; background: #f8f9fa; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); padding: 20px;">
    <h2 style="text-align: center; color: #343a40; font-family: 'Arial', sans-serif; margin-bottom: 20px;">Edit Menu Item</h2>
    <form action="{{ route('admin.menus.update', $menuItem) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div style="margin-bottom: 15px;">
            <label for="name" style="display: block; font-weight: bold; color: #495057; margin-bottom: 5px;">Name</label>
            <input type="text" name="name" id="name" value="{{ $menuItem->name }}" style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 5px; box-sizing: border-box;" required>
        </div>
        <div style="margin-bottom: 15px;">
            <label for="description" style="display: block; font-weight: bold; color: #495057; margin-bottom: 5px;">Description</label>
            <textarea name="description" id="description" style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 5px; box-sizing: border-box;" rows="3" required>{{ $menuItem->description }}</textarea>
        </div>
        <div style="margin-bottom: 15px;">
            <label for="price" style="display: block; font-weight: bold; color: #495057; margin-bottom: 5px;">Price</label>
            <input type="number" name="price" id="price" value="{{ $menuItem->price }}" style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 5px; box-sizing: border-box;" required>
        </div>
        <div style="margin-bottom: 15px;">
            <label for="stock" style="display: block; font-weight: bold; color: #495057; margin-bottom: 5px;">Stock</label>
            <input type="number" name="stock" id="stock" value="{{ $menuItem->stock }}" style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 5px; box-sizing: border-box;" required>
        </div>
        <div style="margin-bottom: 15px;">
            <label for="category_id" style="display: block; font-weight: bold; color: #495057; margin-bottom: 5px;">Category</label>
            <select name="category_id" id="category_id" style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 5px; box-sizing: border-box;" required>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ $menuItem->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div style="margin-bottom: 15px;">
            <label for="image" style="display: block; font-weight: bold; color: #495057; margin-bottom: 5px;">Image</label>
            <input type="file" name="image" id="image" style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 5px; box-sizing: border-box;">
            @if ($menuItem->image)
                <div style="margin-top: 10px;">
                    <img src="{{ asset('storage/' . $menuItem->image) }}" width="100" style="border-radius: 5px; border: 1px solid #ced4da;">
                </div>
            @endif
        </div>
        <div style="text-align: center;">
            <button type="submit" style="background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">Update Menu</button>
        </div>
    </form>
</div>
@endsection

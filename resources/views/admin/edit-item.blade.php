@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Item</h1>

    <form action="{{ route('admin.items.update', $item->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Type</label>
            <select name="type" class="form-control">
                <option value="lost" {{ $item->type === 'lost' ? 'selected' : '' }}>Lost</option>
                <option value="found" {{ $item->type === 'found' ? 'selected' : '' }}>Found</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Item Name</label>
            <input type="text" name="item_name" value="{{ $item->item_name }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Category</label>
            <input type="text" name="category" value="{{ $item->category }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control">{{ $item->description }}</textarea>
        </div>

        <div class="mb-3">
            <label>Location</label>
            <input type="text" name="location" value="{{ $item->location }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Date</label>
            <input type="date" name="date" value="{{ $item->date }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Image (optional)</label>
            <input type="file" name="image" class="form-control">
            @if($item->image)
            <img src="{{ asset('storage/' . $item->image) }}" width="100" class="mt-2">
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Update Item</button>
    </form>
</div>
@endsection

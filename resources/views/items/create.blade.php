@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Post Lost / Found Item</h2>

    <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label>Type</label>
            <select name="type" class="form-control" required>
                <option value="">Select</option>
                <option value="lost">Lost</option>
                <option value="found">Found</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Item Name</label>
            <input type="text" name="item_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Category</label>
            <input type="text" name="category" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="3" required></textarea>
        </div>

        <div class="mb-3">
            <label>Location</label>
            <input type="text" name="location" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Date</label>
            <input type="date" name="date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Image (optional)</label>
            <input type="file" name="image" class="form-control">
        </div>

        <button class="btn btn-primary">Post Item</button>
    </form>
</div>
@endsection

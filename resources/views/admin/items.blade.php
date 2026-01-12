@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">All Items</h1>
    <a href="{{ route('items.create') }}" class="btn btn-warning mb-3">Add Item</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Type</th>
                <th>Name</th>
                <th>Category</th>
                <th>Status</th>
                <th>Posted By</th>
            </tr>
        </thead>
        <tbody>
           @foreach($items as $item)
<tr>
    <td>{{ $item->id }}</td>
    <td>{{ ucfirst($item->type) }}</td>
    <td>{{ $item->item_name }}</td>
    <td>{{ $item->category }}</td>
    <td>{{ ucfirst($item->status) }}</td>
    <td>{{ $item->user->name }}</td>
    <td>
        <a href="{{ route('admin.items.edit', $item->id) }}" class="btn btn-primary btn-sm">Update</a>
        <form action="{{ route('admin.items.delete', $item->id) }}" method="POST" onsubmit="return confirm('Delete this item?')" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
        </form>
    </td>
</tr>
@endforeach
        </tbody>
    </table>
</div>
@endsection

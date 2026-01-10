@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">All Items</h1>

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
        
        <form action="{{ route('admin.items.delete', $item->id) }}" method="POST" onsubmit="return confirm('Delete this item?')">
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

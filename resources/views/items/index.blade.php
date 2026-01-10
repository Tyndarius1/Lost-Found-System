@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-3">
        <h2>Lost & Found Items</h2>
        <a href="{{ route('items.create') }}" class="btn btn-success">Post Item</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        @forelse($items as $item)
            <div class="col-md-4">
                <div class="card mb-3">
                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" class="card-img-top">
                    @endif
                    <div class="card-body">
                        <span class="badge bg-{{ $item->type == 'lost' ? 'danger' : 'success' }}">
                            {{ ucfirst($item->type) }}
                        </span>

                       <h5 class="mt-2">
    <a href="{{ route('items.show', $item->id) }}">
        {{ $item->item_name }}
    </a>
</h5>
                        <p>{{ $item->location }}</p>
                        <small>Status: {{ ucfirst($item->status) }}</small>
                    </div>
                </div>
            </div>
        @empty
            <p>No items found.</p>
        @endforelse
    </div>
</div>
@endsection

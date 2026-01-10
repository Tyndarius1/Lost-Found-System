@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{ route('items.index') }}" class="btn btn-secondary mb-3">← Back</a>

    <div class="card">
        @if($item->image)
            <img src="{{ asset('storage/' . $item->image) }}" class="card-img-top">
        @endif

        <div class="card-body">
            <span class="badge bg-{{ $item->type == 'lost' ? 'danger' : 'success' }}">
                {{ strtoupper($item->type) }}
            </span>

            <h3 class="mt-3">{{ $item->item_name }}</h3>

            <p><strong>Category:</strong> {{ $item->category }}</p>
            <p><strong>Description:</strong> {{ $item->description }}</p>
            <p><strong>Location:</strong> {{ $item->location }}</p>
            <p><strong>Date:</strong> {{ $item->date }}</p>
            <p><strong>Status:</strong> {{ ucfirst($item->status) }}</p>

            <hr>

            <p class="text-muted">
                Posted by {{ $item->user->name }}
            </p>

            {{-- Success & Error Messages --}}
@if(session('success'))
    <div class="alert alert-success mt-3">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger mt-3">
        {{ session('error') }}
    </div>
@endif

{{-- Claim Form --}}
@if($item->status === 'open' && auth()->id() !== $item->user_id)
    <form action="{{ route('items.claim', $item->id) }}" method="POST" class="mt-3">
        @csrf

        <div class="mb-3">
            <label class="form-label">Why is this item yours?</label>
            <textarea 
                name="message" 
                class="form-control" 
                rows="3" 
                required
            ></textarea>
        </div>

        <button class="btn btn-primary">
            Submit Claim
        </button>
    </form>
@endif

{{-- Message for owner --}}
@if(auth()->id() === $item->user_id)
    <div class="alert alert-info mt-3">
        You posted this item.
    </div>
@endif

{{-- Message if already claimed --}}
@if($item->status !== 'open')
    <div class="alert alert-warning mt-3">
        This item is already {{ $item->status }}.
    </div>
@endif

        </div>
    </div>
</div>
@endsection

@extends('layouts.user')

@section('content')
<div class="container">
    <h2>Claims on Your Items</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @forelse($claims as $claim)
        <div class="card mb-3">
            <div class="card-body">
                <h5>{{ $claim->item->item_name }} ({{ ucfirst($claim->item->type) }})</h5>
                <p><strong>Claimed by:</strong> {{ $claim->user->name }}</p>
                <p><strong>Message:</strong> {{ $claim->message }}</p>
                <p><strong>Status:</strong> {{ ucfirst($claim->status) }}</p>

                @if($claim->status === 'pending')
                    <form action="{{ route('owner.claims.update', $claim->id) }}" method="POST" class="mt-2">
                        @csrf
                        <button type="submit" name="status" value="approved" class="btn btn-success">Approve</button>
                        <button type="submit" name="status" value="rejected" class="btn btn-danger">Reject</button>
                    </form>
                @endif
            </div>
        </div>
    @empty
        <p>No claims on your items yet.</p>
    @endforelse
</div>
@endsection

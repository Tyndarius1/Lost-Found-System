@extends('layouts.user')

@section('content')
<div class="container pb-5">
    <div class="row mb-5">
        <div class="col-md-12">
            <h2 class="fw-bold text-dark">Lost & Found Items</h2>
            <p class="text-muted">Browse items reported by the community. To post your own, go to "My Items".</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
            <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="row g-4">
        @forelse($items as $item)
            <div class="col-sm-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 item-card" style="border-radius: 20px; transition: transform 0.2s;">
                    <div class="position-relative overflow-hidden" style="border-radius: 20px 20px 0 0;">
                        @if($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}" class="card-img-top" style="height: 220px; object-fit: cover;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 220px;">
                                <i class="bi bi-image text-muted opacity-25" style="font-size: 3rem;"></i>
                            </div>
                        @endif
                        
                        <div class="position-absolute top-0 start-0 m-3">
                            <span class="badge rounded-pill px-3 py-2 fw-bold shadow-sm {{ $item->type == 'lost' ? 'bg-danger' : 'bg-success' }}">
                                {{ strtoupper($item->type) }}
                            </span>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="text-primary small fw-bold text-uppercase" style="letter-spacing: 0.05em;">{{ $item->category }}</span>
                            <span class="text-muted small"><i class="bi bi-clock me-1"></i> {{ $item->created_at->diffForHumans() }}</span>
                        </div>

                        <h5 class="fw-bold mb-3">
                            <a href="{{ route('items.show', $item->id) }}" class="text-dark text-decoration-none stretched-link">
                                {{ $item->item_name }}
                            </a>
                        </h5>

                        <div class="d-flex align-items-center text-muted small mb-3">
                            <i class="bi bi-geo-alt-fill text-primary me-2"></i>
                            {{ $item->location }}
                        </div>

                        <div class="pt-3 border-top d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center" style="position: relative; z-index: 2;">
                                <div class="bg-light text-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 24px; height: 24px; font-size: 0.7rem; font-weight: 700;">
                                    {{ substr($item->user->name, 0, 1) }}
                                </div>
                                <span class="small text-muted">{{ $item->user->name }}</span>
                            </div>
                            <span class="badge border rounded-pill text-dark px-3 py-1" style="font-size: 0.7rem;">
                                {{ ucfirst($item->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-search text-muted opacity-25" style="font-size: 4rem;"></i>
                <h4 class="fw-bold text-dark mt-3">No items found</h4>
                <p class="text-muted">No one has reported anything yet.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-5 d-flex justify-content-center">
        @if(method_exists($items, 'links'))
            {{ $items->links() }}
        @endif
    </div>
</div>

<style>
    .item-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
    }
</style>
@endsection
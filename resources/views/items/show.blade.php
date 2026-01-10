@extends('layouts.user')

@section('content')
<style>
    /* 1. SKELETON SHIMMER ANIMATION */
    .skeleton {
        background: #e2e8f0;
        /* This creates the moving light effect */
        background: linear-gradient(110deg, #ececec 8%, #f5f5f5 18%, #ececec 33%);
        border-radius: 10px;
        background-size: 200% 100%;
        animation: shine 1.5s linear infinite;
    }

    @keyframes shine {
        to { background-position-x: -200%; }
    }

    /* 2. SKELETON ELEMENTS */
    .skeleton-title { height: 35px; width: 60%; border-radius: 8px; }
    .skeleton-text { height: 18px; width: 100%; border-radius: 4px; margin-bottom: 10px; }
    .skeleton-image { height: 400px; width: 100%; border-radius: 24px; }
    .skeleton-avatar { width: 40px; height: 40px; border-radius: 50%; }

    /* 3. TRANSITION LOGIC */
    #actual-content { display: none; }
</style>

<div class="container pb-5">
    <div id="skeleton-loader">
        <div class="d-flex align-items-center mb-4">
            <div class="skeleton" style="width: 45px; height: 45px; border-radius: 50%;"></div>
            <div class="ms-3 flex-grow-1">
                <div class="skeleton skeleton-text" style="width: 150px; height: 25px;"></div>
                <div class="skeleton skeleton-text" style="width: 250px;"></div>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="skeleton skeleton-image shadow-sm"></div>
            </div>
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm p-4 p-md-5 h-100" style="border-radius: 24px;">
                    <div class="skeleton skeleton-text" style="width: 80px; height: 15px;"></div>
                    <div class="skeleton skeleton-title mb-4"></div>
                    <div class="row mb-4">
                        <div class="col-6"><div class="skeleton skeleton-text"></div><div class="skeleton skeleton-text" style="width: 70%;"></div></div>
                        <div class="col-6"><div class="skeleton skeleton-text"></div><div class="skeleton skeleton-text" style="width: 70%;"></div></div>
                    </div>
                    <div class="skeleton skeleton-text"></div>
                    <div class="skeleton skeleton-text"></div>
                    <div class="skeleton skeleton-text" style="width: 60%;"></div>
                    <div class="d-flex align-items-center mt-4 pt-3 border-top border-light">
                        <div class="skeleton skeleton-avatar me-3"></div>
                        <div class="flex-grow-1"><div class="skeleton skeleton-text" style="width: 100px;"></div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="actual-content">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('items.index') }}" class="btn btn-white border shadow-sm rounded-circle me-3" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-arrow-left text-dark"></i>
            </a>
            <div>
                <h2 class="fw-bold mb-0">Item Details</h2>
                <p class="text-muted mb-0 text-truncate" style="max-width: 250px;">Viewing information for {{ $item->item_name }}</p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm overflow-hidden h-100 position-relative" style="border-radius: 24px;">
                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" class="img-fluid h-100 w-100" style="object-fit: cover; min-height: 400px;">
                    @else
                        <div class="bg-light d-flex flex-column align-items-center justify-content-center h-100 py-5" style="min-height: 400px;">
                            <i class="bi bi-image text-muted opacity-25" style="font-size: 5rem;"></i>
                            <p class="text-muted mt-2">No image provided</p>
                        </div>
                    @endif
                    
                    <div class="position-absolute top-0 start-0 m-4">
                        <span class="badge rounded-pill px-3 py-2 fw-bold shadow-sm {{ $item->type == 'lost' ? 'bg-danger' : 'bg-success' }}">
                            <i class="bi {{ $item->type == 'lost' ? 'bi-search' : 'bi-check-circle' }} me-1"></i>
                            {{ strtoupper($item->type) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow-sm p-4 p-md-5 h-100" style="border-radius: 24px;">
                    <div class="mb-4">
                        <span class="text-primary small fw-bold text-uppercase ls-wide">{{ $item->category }}</span>
                        <h1 class="fw-bold text-dark mt-1">{{ $item->item_name }}</h1>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <label class="small text-muted text-uppercase fw-bold ls-wide d-block">Location</label>
                            <span class="fw-semibold text-dark"><i class="bi bi-geo-alt me-1"></i> {{ $item->location }}</span>
                        </div>
                        <div class="col-6">
                            <label class="small text-muted text-uppercase fw-bold ls-wide d-block">Date Reported</label>
                            <span class="fw-semibold text-dark"><i class="bi bi-calendar3 me-1"></i> {{ \Carbon\Carbon::parse($item->date)->format('M d, Y') }}</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="small text-muted text-uppercase fw-bold ls-wide d-block mb-2">Description</label>
                        <p class="text-secondary lh-lg">{{ $item->description }}</p>
                    </div>

                    <div class="d-flex align-items-center p-3 bg-light rounded-4 mb-4">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; font-weight: 700;">
                            {{ substr($item->user->name, 0, 1) }}
                        </div>
                        <div>
                            <span class="small text-muted d-block">Posted by</span>
                            <span class="fw-bold text-dark">{{ $item->user->name }}</span>
                        </div>
                        <div class="ms-auto text-end">
                             <span class="badge {{ $item->status === 'open' ? 'bg-primary-subtle text-primary' : 'bg-secondary-subtle text-secondary' }} border px-3">
                                {{ ucfirst($item->status) }}
                             </span>
                        </div>
                    </div>

                    <hr class="my-4 opacity-10">

                    @if(session('success'))
                        <div class="alert alert-success rounded-4 border-0 shadow-sm d-flex align-items-center mb-4">
                            <i class="bi bi-check-circle-fill me-2 fs-4"></i>
                            <div>{{ session('success') }}</div>
                        </div>
                    @endif

                    @if($item->status === 'open' && auth()->id() !== $item->user_id)
                        <form action="{{ route('items.claim', $item->id) }}" method="POST">
                            @csrf
                            <div class="input-group-modern mb-3">
                                <textarea name="message" rows="3" placeholder=" " minlength="10" required></textarea>
                                <label>Prove ownership (e.g. Serial number, specific marks...)</label>
                            </div>
                            <button class="btn btn-primary w-100 py-3 fw-bold rounded-pill shadow-sm">
                                <i class="bi bi-send-fill me-2"></i> Submit Claim
                            </button>
                        </form>
                    @endif

                    @if(auth()->id() === $item->user_id)
                        <div class="bg-info bg-opacity-10 text-info p-3 rounded-4 border border-info border-opacity-25 text-center">
                            <i class="bi bi-info-circle-fill me-2"></i> You are the owner of this post.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // We delay the content reveal slightly to show off the shimmer effect
        setTimeout(() => {
            document.getElementById('skeleton-loader').style.display = 'none';
            document.getElementById('actual-content').style.display = 'block';
        }, 1200); 
    });
</script>
@endsection
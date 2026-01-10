@extends('layouts.user')

@section('content')
<div class="container pb-5">
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
            <div class="card border-0 shadow-sm overflow-hidden h-100" style="border-radius: 24px;">
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

                {{-- Status Messages & Form --}}
                @if(session('success'))
                    <div class="alert alert-success rounded-4 border-0 shadow-sm d-flex align-items-center" role="alert">
                        <i class="bi bi-check-circle-fill me-2 fs-4"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger rounded-4 border-0 shadow-sm" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Claim Form --}}
                @if($item->status === 'open' && auth()->id() !== $item->user_id)
                    <form action="{{ route('items.claim', $item->id) }}" method="POST">
                        @csrf
                        <div class="input-group-modern mb-3">
                            <textarea name="message" rows="3" placeholder=" " required></textarea>
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

                @if($item->status !== 'open')
                    <div class="bg-secondary bg-opacity-10 text-secondary p-3 rounded-4 border border-secondary border-opacity-25 text-center fw-bold">
                        <i class="bi bi-lock-fill me-2"></i> This item is already {{ strtoupper($item->status) }}.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .ls-wide { letter-spacing: 0.05em; }
    .btn-white { background-color: white; transition: all 0.2s; }
    .btn-white:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important; }

    /* Modern Inputs Style (Consistent with Create page) */
    .input-group-modern {
        position: relative;
        width: 100%;
    }
    .input-group-modern textarea {
        width: 100%;
        padding: 14px 16px;
        border: 2px solid #f1f5f9;
        background: #f8fafc;
        border-radius: 14px;
        outline: none;
        transition: all 0.2s;
        font-size: 1rem;
    }
    .input-group-modern label {
        position: absolute;
        left: 16px;
        top: 14px;
        color: #94a3b8;
        pointer-events: none;
        transition: all 0.2s;
    }
    .input-group-modern textarea:focus {
        border-color: #6366f1;
        background: #fff;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }
    .input-group-modern textarea:focus + label, 
    .input-group-modern textarea:not(:placeholder-shown) + label {
        top: -10px;
        left: 12px;
        font-size: 0.75rem;
        font-weight: 800;
        background: #fff;
        padding: 0 8px;
        color: #6366f1;
    }
</style>
@endsection
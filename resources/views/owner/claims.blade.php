@extends('layouts.user')

@section('content')
<div class="container pb-5">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-bold mb-1 text-dark">Claims on Your Items</h2>
            <p class="text-muted mb-0">Review and manage requests from students claiming your reported items.</p>
        </div>
        <div class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
            <i class="bi bi-person-check me-1"></i> {{ $claims->count() }} Total Claims
        </div>
    </div>

    <div class="row g-3">
        @forelse($claims as $claim)
            <div class="col-12">
                <div class="card border-0 shadow-sm item-list-card" style="border-radius: 20px; position: relative;">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="bg-light rounded-4 overflow-hidden" style="width: 70px; height: 70px; border: 1px solid #f1f5f9;">
                                    @if($claim->item->image)
                                        <img src="{{ asset('storage/' . $claim->item->image) }}" class="w-100 h-100" style="object-fit: cover;">
                                    @else
                                        <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                                            <i class="bi bi-box-seam text-muted fs-3"></i>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col px-md-4">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <h5 class="fw-bold mb-0 text-dark">{{ $claim->item->item_name }}</h5>
                                    <span class="badge rounded-pill {{ $claim->item->type == 'lost' ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success' }}" style="font-size: 0.65rem; font-weight: 800;">
                                        {{ strtoupper($claim->item->type) }}
                                    </span>
                                </div>
                                <div class="mb-2">
                                    <span class="text-dark small fw-bold"><i class="bi bi-person me-1 text-primary"></i> Claimed by:</span>
                                    <span class="text-muted small">{{ $claim->user->name }}</span>
                                </div>
                                <div class="bg-light p-3 rounded-3 mt-2 border-start border-primary border-4">
                                    <p class="mb-0 small text-dark italic">"{{ $claim->message }}"</p>
                                </div>
                            </div>

                            <div class="col-auto text-end">
                                <div class="mb-3">
                                    <span class="badge rounded-pill px-3 py-2 border {{ $claim->status === 'pending' ? 'bg-warning-subtle text-warning border-warning' : ($claim->status === 'approved' ? 'bg-success-subtle text-success border-success' : 'bg-danger-subtle text-danger border-danger') }}" style="font-size: 0.75rem;">
                                        <i class="bi {{ $claim->status === 'pending' ? 'bi-clock' : ($claim->status === 'approved' ? 'bi-check-circle' : 'bi-x-circle') }} me-1"></i>
                                        {{ ucfirst($claim->status) }}
                                    </span>
                                </div>

                                @if($claim->status === 'pending')
                                    <div class="d-flex gap-2">
                                        <form action="{{ route('owner.claims.update', $claim->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" name="status" value="approved" class="btn btn-success btn-sm rounded-pill px-3 fw-bold shadow-sm">
                                                Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('owner.claims.update', $claim->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" name="status" value="rejected" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-bold">
                                                Reject
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="bg-light d-inline-block rounded-circle p-4 mb-3">
                    <i class="bi bi-chat-dots text-muted fs-1 opacity-25"></i>
                </div>
                <h4 class="fw-bold text-dark">No claims yet</h4>
                <p class="text-muted">Requests for your items will appear here.</p>
            </div>
        @endforelse
    </div>
</div>

<style>
    .item-list-card {
        transition: transform 0.2s, box-shadow 0.2s;
        border: 1px solid rgba(0,0,0,0.05);
    }
    .item-list-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important;
    }
    .italic { font-style: italic; }
</style>

<script>
    // Consistent Toast configuration
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        },
        customClass: {
            popup: 'rounded-4 border-0 shadow-sm mt-3 me-3',
        }
    });

    @if(session('success'))
        Toast.fire({
            icon: 'success',
            title: "{{ session('success') }}"
        });
    @endif
</script>
@endsection
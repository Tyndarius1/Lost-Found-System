@extends('layouts.user')

@section('content')
<div class="container pb-5">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-bold mb-1">My Reported Items</h2>
            <p class="text-muted mb-0">Manage and track the status of items you have posted.</p>
        </div>
        <a href="{{ route('items.create') }}" class="btn btn-primary px-4 py-2 fw-bold rounded-pill shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Post New Item
        </a>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3" style="border-radius: 16px;">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3 me-3">
                        <i class="bi bi-file-post fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-muted small fw-bold text-uppercase mb-0">Total Posts</h6>
                        <h4 class="fw-bold mb-0">{{ $items->total() }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3" style="border-radius: 16px;">
                <div class="d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 text-warning rounded-circle p-3 me-3">
                        <i class="bi bi-clock-history fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-muted small fw-bold text-uppercase mb-0">Open Items</h6>
                        <h4 class="fw-bold mb-0">{{ $items->where('status', 'open')->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        @forelse($items as $item)
            <div class="col-12">
                <div class="card border-0 shadow-sm item-list-card" style="border-radius: 20px; position: relative;">
                    <div class="card-body p-3" style="overflow: visible;">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="bg-light rounded-4 overflow-hidden" style="width: 85px; height: 85px; border: 1px solid #f1f5f9;">
                                    @if($item->image)
                                        <img src="{{ asset('storage/' . $item->image) }}" class="w-100 h-100" style="object-fit: cover;">
                                    @else
                                        <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                                            <i class="bi bi-box-seam text-muted fs-2"></i>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col px-md-4">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="badge rounded-pill {{ $item->type == 'lost' ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success' }}" style="font-size: 0.7rem; font-weight: 800; letter-spacing: 0.5px;">
                                        {{ strtoupper($item->type) }}
                                    </span>
                                    <h5 class="fw-bold mb-0 text-dark">{{ $item->item_name }}</h5>
                                </div>
                                <div class="text-muted small d-flex flex-wrap gap-3 mt-1">
                                    <span><i class="bi bi-tag me-1 text-primary"></i> {{ $item->category }}</span>
                                    <span><i class="bi bi-geo-alt me-1 text-primary"></i> {{ $item->location }}</span>
                                    <span><i class="bi bi-calendar3 me-1 text-primary"></i> {{ \Carbon\Carbon::parse($item->date)->format('M d, Y') }}</span>
                                </div>
                            </div>

                            <div class="col-auto d-flex align-items-center gap-3">
                                <span class="badge rounded-pill px-3 py-2 {{ $item->status == 'open' ? 'bg-primary-subtle text-primary border border-primary' : 'bg-light text-muted border' }}" style="font-size: 0.75rem;">
                                    {{ ucfirst($item->status) }}
                                </span>

                                <div class="dropdown">
                                    <button class="btn btn-light rounded-circle shadow-sm action-dot-btn" 
                                            type="button" 
                                            data-bs-toggle="dropdown" 
                                            data-bs-boundary="viewport"
                                            aria-expanded="false" 
                                            style="width: 42px; height: 42px;">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-2 mt-2" style="border-radius: 15px; min-width: 180px;">
                                        <li>
                                            <a class="dropdown-item rounded-3 py-2" href="{{ route('items.show', $item->id) }}">
                                                <i class="bi bi-eye me-2 text-primary"></i> View Details
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item rounded-3 py-2" href="{{ route('items.edit', $item->id) }}">
                                                <i class="bi bi-pencil-square me-2 text-warning"></i> Edit Post
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider opacity-50"></li>
                                        <li>
                                            <form action="{{ route('items.destroy', $item->id) }}" method="POST" id="delete-form-{{ $item->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="dropdown-item rounded-3 py-2 text-danger" onclick="confirmDelete({{ $item->id }})">
                                                    <i class="bi bi-trash3 me-2"></i> Delete Post
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="bg-light d-inline-block rounded-circle p-4 mb-3">
                    <i class="bi bi-search text-muted fs-1 opacity-25"></i>
                </div>
                <h4 class="fw-bold text-dark">No reports yet</h4>
                <p class="text-muted">You haven't posted any items in the system.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-5 d-flex justify-content-center">
        {{ $items->links() }}
    </div>
</div>

<style>
    /* Prevent clipping and handle stacking */
    .item-list-card {
        transition: transform 0.2s, box-shadow 0.2s;
        border: 1px solid rgba(0,0,0,0.05);
        overflow: visible !important;
    }
    .item-list-card:focus-within { 
        z-index: 10; 
    }
    .item-list-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important;
    }
    .action-dot-btn:hover { 
        background-color: #6366f1 !important; 
        color: white !important; 
    }
    .dropdown-item {
        font-weight: 500;
        transition: all 0.2s;
    }
    .dropdown-item:hover {
        background-color: #f8fafc;
    }
</style>

<script>
// Small Toast configuration for Success Messages
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

// Trigger Toast if session exists
@if(session('success'))
    Toast.fire({
        icon: 'success',
        title: "{{ session('success') }}"
    });
@endif

// Full SweetAlert for Delete Confirmation
function confirmDelete(id) {
    Swal.fire({
        title: 'Delete this post?',
        text: "This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#6366f1',
        cancelButtonColor: '#f1f5f9',
        confirmButtonText: 'Yes, delete it',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        customClass: {
            popup: 'rounded-4 shadow-lg border-0',
            confirmButton: 'btn btn-primary px-4 py-2 rounded-pill fw-bold ms-2',
            cancelButton: 'btn btn-light px-4 py-2 rounded-pill fw-bold text-dark'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    })
}
</script>
@endsection
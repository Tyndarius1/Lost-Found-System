@extends('layouts.user')

@section('content')
<div class="container">
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="fw-bold text-dark">Welcome, {{ Auth::user()->name }}</h2>
            <p class="text-muted">What would you like to do today?</p>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100 p-3" style="border-radius: 20px;">
                <div class="card-body">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center mb-4" style="width: 50px; height: 50px;">
                        <i class="bi bi-search fs-4"></i>
                    </div>
                    <h5 class="fw-bold">Browse Items</h5>
                    <p class="text-muted small">Search through all reported items to see if your lost belonging has been found.</p>
                    <a href="{{ route('items.index') }}" class="btn btn-primary w-100 rounded-pill mt-2">View All Items</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100 p-3" style="border-radius: 20px;">
                <div class="card-body">
                    <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center mb-4" style="width: 50px; height: 50px;">
                        <i class="bi bi-plus-circle fs-4"></i>
                    </div>
                    <h5 class="fw-bold">Report an Item</h5>
                    <p class="text-muted small">Did you find something? Upload details and a photo to help the owner find it.</p>
                    <a href="{{ route('items.create') }}" class="btn btn-success w-100 rounded-pill mt-2">Report Found Item</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100 p-3" style="border-radius: 20px;">
                <div class="card-body">
                    <div class="bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center mb-4" style="width: 50px; height: 50px;">
                        <i class="bi bi-file-earmark-check fs-4"></i>
                    </div>
                    <h5 class="fw-bold">Track My Claims</h5>
                    <p class="text-muted small">Check the status of items you have claimed or manage responses from owners.</p>
                    <a href="{{ route('owner.claims') }}" class="btn btn-info w-100 rounded-pill text-white mt-2">View My Claims</a>
                </div>
            </div>
        </div>
    </div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 20px;">
            <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">My Recent Reports</h5>
                <a href="{{ route('items.index') }}" class="small text-decoration-none">View All</a>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle border-0">
                        <tbody>
                            @forelse($recentActivity as $activity)
                                <tr>
                                    <td style="width: 50px;">
                                        <div class="rounded-3 bg-light d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                            <i class="bi {{ $activity->type == 'lost' ? 'bi-search text-danger' : 'bi-check-circle text-success' }}"></i>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $activity->item_name }}</div>
                                        <div class="text-muted small">{{ $activity->location }}</div>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill {{ $activity->status == 'open' ? 'bg-primary-subtle text-primary' : 'bg-light text-muted' }} border">
                                            {{ ucfirst($activity->status) }}
                                        </span>
                                    </td>
                                    <td class="text-muted small text-end">
                                        {{ $activity->created_at->diffForHumans() }}
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('items.show', $activity->id) }}" class="btn btn-sm btn-light rounded-circle">
                                            <i class="bi bi-chevron-right"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <div class="text-center py-4">
                                    <i class="bi bi-clock-history text-muted fs-1 opacity-25"></i>
                                    <p class="text-muted mt-2">You haven't reported any items yet.</p>
                                    <a href="{{ route('items.create') }}" class="btn btn-primary btn-sm rounded-pill px-4">Post an Item</a>
                                </div>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
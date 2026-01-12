@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-bold mb-1">Admin Overview</h2>
            <p class="text-muted mb-0">System-wide analytics and management.</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-white border shadow-sm px-3 fw-semibold">
                <i class="bi bi-download me-1"></i> Export Report
            </button>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 16px;">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3">
                        <i class="bi bi-people-fill fs-4"></i>
                    </div>
                    <span class="badge bg-success-subtle text-success">+12%</span>
                </div>
                <h6 class="text-muted small fw-bold text-uppercase">Total Users</h6>
                <div class="d-flex align-items-end gap-2">
                    <h2 class="fw-bold mb-0">{{ $usersCount }}</h2>
                </div>
                <a href="{{ route('admin.users') }}" class="stretched-link mt-3 text-decoration-none small fw-bold text-primary">
                    Manage users <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 16px;">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="bg-info bg-opacity-10 text-info rounded-3 p-3">
                        <i class="bi bi-box-seam-fill fs-4"></i>
                    </div>
                </div>
                <h6 class="text-muted small fw-bold text-uppercase">Inventory Items</h6>
                <h2 class="fw-bold mb-0">{{ $itemsCount }}</h2>
                <a href="{{ route('admin.items') }}" class="stretched-link mt-3 text-decoration-none small fw-bold text-info">
                    View inventory <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 16px;">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-3">
                        <i class="bi bi-file-earmark-text-fill fs-4"></i>
                    </div>
                    <span class="badge bg-danger-subtle text-danger">Action Required</span>
                </div>
                <h6 class="text-muted small fw-bold text-uppercase">Pending Claims</h6>
                <h2 class="fw-bold mb-0">{{ $claimsCount }}</h2>
                <a href="{{ route('admin.claims') }}" class="stretched-link mt-3 text-decoration-none small fw-bold text-warning">
                    Review claims <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm p-4 mb-5" style="border-radius: 16px;">
    <div class="card-header bg-white border-0 p-3">
        <h5 class="fw-bold mb-0">Recent Activity Log</h5>
    </div>
    <div class="table-responsive p-3">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Type</th>
                    <th>User</th>
                    <th>Action</th>
                    <th></th>
                    <th class="text-end">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentActivity as $activity)
                    <tr>
                        <td>
                            @if($activity['type'] === 'Claim')
                                <span class="badge bg-primary-subtle text-primary">Claim</span>
                            @else
                                <span class="badge bg-success-subtle text-success">Item</span>
                            @endif
                        </td>
                        <td class="fw-semibold">{{ $activity['user'] }}</td>
                        <td>{{ $activity['action'] }}</td>
                        <td class="text-muted small">{{ $activity['date'] }}</td>
                        <td class="text-end">
                            @php
                                $status = strtolower($activity['status']);
                                $badgeClass = match($status) {
                                    'pending' => 'bg-warning text-dark',
                                    'open' => 'bg-primary text-white',
                                    'resolved', 'approved' => 'bg-success text-white',
                                    'rejected' => 'bg-danger text-white',
                                    default => 'bg-secondary text-white'
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $activity['status'] }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">No recent activity found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

</div>

<style>
    /* Subtle button styling */
    .btn-white {
        background-color: white;
        transition: all 0.2s;
    }
    .btn-white:hover {
        background-color: #f8f9fa;
        transform: translateY(-1px);
    }
    .ls-wide { letter-spacing: 0.05em; }
</style>
@endsection
@extends('layouts.app')


@section('content')
<div class="container">
    <h1 class="mb-4">Admin Dashboard</h1>

    <div class="row">
        <div class="col-md-4">
            <div class="card p-3 mb-3">
                <h5>Users</h5>
                <p>{{ $usersCount }}</p>
                <a href="{{ route('admin.users') }}" class="btn btn-sm btn-primary">View Users</a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 mb-3">
                <h5>Items</h5>
                <p>{{ $itemsCount }}</p>
                <a href="{{ route('admin.items') }}" class="btn btn-sm btn-primary">View Items</a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 mb-3">
                <h5>Claims</h5>
                <p>{{ $claimsCount }}</p>
                <a href="{{ route('admin.claims') }}" class="btn btn-sm btn-primary">View Claims</a>
            </div>
        </div>
    </div>
</div>
@endsection

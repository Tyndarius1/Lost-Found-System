@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">All Claims</h1>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Item</th>
                <th>Claimed By</th>
                <th>Message</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($claims as $claim)
            <tr>
                <td>{{ $claim->id }}</td>
                <td>{{ $claim->item->item_name }}</td>
                <td>{{ $claim->user->name }}</td>
                <td>{{ $claim->message }}</td>
                <td>{{ ucfirst($claim->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

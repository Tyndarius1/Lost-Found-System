@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-4" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-bold mb-1 text-dark">System Users</h2>
            <p class="text-muted mb-0">Manage student and administrator accounts.</p>
        </div>
        <button class="btn btn-primary px-4 py-2 fw-bold rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#createUserModal">
            <i class="bi bi-person-plus-fill me-2"></i> Add New User
        </button>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 20px; overflow: hidden;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-muted small fw-bold text-uppercase">User</th>
                        <th class="py-3 text-muted small fw-bold text-uppercase">Contact Info</th>
                        <th class="py-3 text-muted small fw-bold text-uppercase text-center">Age</th>
                        <th class="py-3 text-muted small fw-bold text-uppercase text-center">Role</th>
                        <th class="pe-4 py-3 text-muted small fw-bold text-uppercase text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                @if($user->profile_picture)
                                    <img src="{{ asset('storage/' . $user->profile_picture) }}" class="rounded-circle me-3 shadow-sm" style="width: 40px; height: 40px; object-fit: cover;">
                                @else
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm" style="width: 40px; height: 40px; font-weight: 700;">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-bold text-dark">{{ $user->name }}</div>
                                    <div class="text-muted small">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="small fw-semibold text-dark">
                                <i class="bi bi-telephone me-1 text-primary"></i> {{ $user->phone ?? 'No Phone' }}
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="text-muted small fw-bold">{{ $user->age ?? 'N/A' }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge rounded-pill px-3 py-2 {{ $user->role === 'admin' ? 'bg-dark' : 'bg-primary-subtle text-primary border border-primary-subtle' }}" style="font-size: 0.7rem;">
                                {{ strtoupper($user->role) }}
                            </span>
                        </td>
                        <td class="pe-4 text-end">
                            <button class="btn btn-light btn-sm rounded-circle me-1 shadow-sm edit-user-btn" 
                                    data-user-id="{{ $user->id }}"
                                    data-name="{{ $user->name }}"
                                    data-email="{{ $user->email }}"
                                    data-phone="{{ $user->phone }}"
                                    data-age="{{ $user->age }}"
                                    data-role="{{ $user->role }}"
                                    data-url="{{ route('admin.users.update', $user->id) }}">
                                <i class="bi bi-pencil-square text-warning"></i>
                            </button>
                            
                            @if(auth()->id() !== $user->id)
                            <button class="btn btn-light btn-sm rounded-circle shadow-sm" 
                                    onclick="confirmUserDelete({{ $user->id }})">
                                <i class="bi bi-trash3 text-danger"></i>
                            </button>
                            <form id="delete-user-{{ $user->id }}" action="{{ route('admin.users.delete', $user->id) }}" method="POST" class="d-none">
                                @csrf 
                                @method('DELETE')
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="createUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 p-4 pb-0">
                <h5 class="fw-bold">Create New Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.users.store') }}" method="POST" class="p-4">
                @csrf
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="small fw-bold text-muted mb-1">Full Name</label>
                        <input type="text" name="name" class="form-control rounded-3" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="small fw-bold text-muted mb-1">Age</label>
                        <input type="number" name="age" class="form-control rounded-3">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="small fw-bold text-muted mb-1">Email Address</label>
                    <input type="email" name="email" class="form-control rounded-3" required>
                </div>
                <div class="mb-3">
                    <label class="small fw-bold text-muted mb-1">Phone Number</label>
                    <input type="text" name="phone" class="form-control rounded-3" placeholder="09xxxxxxxxx">
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="small fw-bold text-muted mb-1">Password</label>
                        <input type="password" name="password" class="form-control rounded-3" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="small fw-bold text-muted mb-1">Confirm</label>
                        <input type="password" name="password_confirmation" class="form-control rounded-3" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="small fw-bold text-muted mb-1">Role</label>
                    <select name="role" class="form-select rounded-3">
                        <option value="user">User (Student)</option>
                        <option value="admin">Administrator</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold rounded-pill shadow-sm">Create User</button>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 p-4 pb-0">
                <h5 class="fw-bold">Update Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editUserForm" method="POST" class="p-4">
                @csrf 
                @method('PUT')
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="small fw-bold text-muted mb-1">Full Name</label>
                        <input type="text" name="name" id="edit_name" class="form-control rounded-3" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="small fw-bold text-muted mb-1">Age</label>
                        <input type="number" name="age" id="edit_age" class="form-control rounded-3">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="small fw-bold text-muted mb-1">Email Address</label>
                    <input type="email" name="email" id="edit_email" class="form-control rounded-3" required>
                </div>
                <div class="mb-3">
                    <label class="small fw-bold text-muted mb-1">Phone Number</label>
                    <input type="text" name="phone" id="edit_phone" class="form-control rounded-3">
                </div>
                <div class="p-3 bg-light rounded-3 mb-3 border">
                    <label class="small fw-bold text-dark mb-1">Change Password (Optional)</label>
                    <input type="password" name="password" class="form-control rounded-3 bg-white mb-2" placeholder="New password">
                    <input type="password" name="password_confirmation" class="form-control rounded-3 bg-white" placeholder="Confirm new password">
                </div>
                <div class="mb-4">
                    <label class="small fw-bold text-muted mb-1">Role</label>
                    <select name="role" id="edit_role" class="form-select rounded-3">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-warning w-100 py-2 fw-bold rounded-pill shadow-sm">Update User</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Edit Button Logic
        const editButtons = document.querySelectorAll('.edit-user-btn');
        const editForm = document.getElementById('editUserForm');
        const editModal = new bootstrap.Modal(document.getElementById('editUserModal'));

        editButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                // Set form action URL
                editForm.action = this.getAttribute('data-url');
                
                // Fill form fields
                document.getElementById('edit_name').value = this.getAttribute('data-name');
                document.getElementById('edit_email').value = this.getAttribute('data-email');
                document.getElementById('edit_phone').value = this.getAttribute('data-phone') || '';
                document.getElementById('edit_age').value = this.getAttribute('data-age') || '';
                document.getElementById('edit_role').value = this.getAttribute('data-role');

                // Show modal
                editModal.show();
            });
        });
    });

    // 2. Delete Logic
    function confirmUserDelete(userId) {
        Swal.fire({
            title: 'Delete this user?',
            text: "This action will permanently remove the account.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Yes, delete it',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            customClass: { popup: 'rounded-4' }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-user-' + userId).submit();
            }
        });
    }
</script>
@endsection
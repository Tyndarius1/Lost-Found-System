@extends('layouts.user')

@section('content')
<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('home') }}" class="btn btn-white border shadow-sm rounded-circle me-3" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; background: white;">
                    <i class="bi bi-arrow-left text-dark"></i>
                </a>
                <div>
                    <h2 class="fw-bold mb-0 text-dark">Profile Settings</h2>
                    <p class="text-muted mb-0">Update your personal information and profile picture.</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm p-4 p-md-5" style="border-radius: 24px;">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="text-center mb-5">
                        <div class="position-relative d-inline-block">
                            <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=6366f1&color=fff' }}" 
                                 id="avatar-preview" 
                                 class="rounded-circle shadow-sm border border-4 border-white" 
                                 style="width: 130px; height: 130px; object-fit: cover;">
                            
                            <label for="avatar-input" class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="cursor: pointer; width: 40px; height: 40px; border: 3px solid #fff;">
                                <i class="bi bi-camera-fill"></i>
                                <input type="file" id="avatar-input" name="profile_picture" class="d-none" onchange="previewAvatar(event)">
                            </label>
                        </div>
                        <h4 class="fw-bold mt-3 mb-1 text-dark">{{ $user->name }}</h4>
                        <p class="text-muted small">Member since {{ $user->created_at->format('M Y') }}</p>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="input-group-modern">
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" placeholder=" " required />
                                <label>Full Name</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="input-group-modern">
                                <input type="number" name="age" value="{{ old('age', $user->age) }}" placeholder=" " />
                                <label>Age (Optional)</label>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="input-group-modern">
                                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder=" " />
                                <label>Phone Number</label>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="input-group-modern">
                                <textarea name="bio" rows="4" placeholder=" ">{{ old('bio', $user->bio) }}</textarea>
                                <label>Bio (Tell us about yourself...)</label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 text-end">
                        <button type="submit" class="btn btn-primary px-5 py-3 fw-bold rounded-pill shadow-sm">
                            Update Profile <i class="bi bi-check2-circle ms-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Consistent Modern Inputs Style */
    .input-group-modern {
        position: relative;
        width: 100%;
    }
    .input-group-modern input, 
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
    .input-group-modern input:focus, 
    .input-group-modern textarea:focus {
        border-color: #6366f1;
        background: #fff;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }
    .input-group-modern input:focus + label, 
    .input-group-modern input:not(:placeholder-shown) + label,
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

<script>
    // Avatar Preview Logic
    function previewAvatar(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('avatar-preview');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    // Success Toast Notification
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
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
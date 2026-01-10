@extends('layouts.user')

@section('content')
<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('items.user') }}" class="btn btn-white border shadow-sm rounded-circle me-3" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-arrow-left text-dark"></i>
                </a>
                <div>
                    <h2 class="fw-bold mb-0">Edit Report</h2>
                    <p class="text-muted mb-0">Updating: <span class="text-primary fw-semibold">{{ $item->item_name }}</span></p>
                </div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
                    <ul class="mb-0 small fw-bold">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card border-0 shadow-sm p-4 p-md-5" style="border-radius: 24px;">
                <form action="{{ route('items.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-5">
                        <label class="fw-bold text-dark mb-3">Item Photo</label>
                        <div class="row align-items-center g-3">
                            <div class="col-auto">
                                <div class="position-relative">
                                    @if($item->image)
                                        <img src="{{ asset('storage/' . $item->image) }}" id="current-img" class="rounded-4 shadow-sm border" style="width: 120px; height: 120px; object-fit: cover;">
                                    @else
                                        <div class="rounded-4 bg-light d-flex align-items-center justify-content-center border" style="width: 120px; height: 120px;">
                                            <i class="bi bi-image text-muted opacity-50 fs-2"></i>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col">
                                <div class="upload-container py-3" onclick="document.getElementById('fileInput').click()">
                                    <input type="file" name="image" id="fileInput" class="d-none" accept="image/*" onchange="previewUpdateImage(event)">
                                    <div id="upload-text">
                                        <i class="bi bi-camera me-2"></i> <span class="fw-bold">Replace Photo</span>
                                        <p class="text-muted small mb-0">Click to upload new image</p>
                                    </div>
                                    <img id="update-preview" src="#" alt="Preview" class="d-none img-fluid rounded-3 mx-auto" style="max-height: 80px;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-12">
                            <label class="form-label fw-bold small text-uppercase text-muted ls-wide">What happened?</label>
                            <div class="d-flex gap-3">
                                <input type="radio" class="btn-check" name="type" id="typeLost" value="lost" {{ $item->type == 'lost' ? 'checked' : '' }} required>
                                <label class="btn btn-outline-danger w-100 py-2 fw-bold rounded-3" for="typeLost">I LOST THIS</label>

                                <input type="radio" class="btn-check" name="type" id="typeFound" value="found" {{ $item->type == 'found' ? 'checked' : '' }}>
                                <label class="btn btn-outline-success w-100 py-2 fw-bold rounded-3" for="typeFound">I FOUND THIS</label>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold small text-uppercase text-muted ls-wide">Date Occurred</label>
                            <input type="date" name="date" class="form-control custom-select-input" value="{{ $item->date }}" required>
                        </div>

                        <div class="col-12">
                            <div class="input-group-modern">
                                <input type="text" name="item_name" value="{{ $item->item_name }}" placeholder=" " required />
                                <label>Item Name (e.g., Mechanical Pencil)</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase text-muted ls-wide">Category</label>
                            <select name="category" class="form-select custom-select-input" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}" {{ $item->category == $cat ? 'selected' : '' }}>
                                        {{ $cat }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase text-muted ls-wide">School Location</label>
                            <select name="location" class="form-select custom-select-input" required>
                                @foreach($locations as $group => $rooms)
                                    <optgroup label="{{ $group }}">
                                        @foreach($rooms as $room)
                                            <option value="{{ $room }}" {{ $item->location == $room ? 'selected' : '' }}>
                                                {{ $room }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <div class="input-group-modern">
                                <textarea name="description" rows="4" placeholder=" " required>{{ $item->description }}</textarea>
                                <label>Detailed Description</label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 text-end">
                        <button type="submit" class="btn btn-primary px-5 py-3 fw-bold rounded-pill shadow-sm">
                            Update Post <i class="bi bi-check-lg ms-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Premium Input Styling */
    .input-group-modern { position: relative; width: 100%; }
    .input-group-modern input, .input-group-modern textarea {
        width: 100%; padding: 14px 16px; border: 2px solid #f1f5f9; background: #f8fafc; border-radius: 14px; outline: none; transition: all 0.2s; font-size: 1rem;
    }
    .input-group-modern label { position: absolute; left: 16px; top: 14px; color: #94a3b8; pointer-events: none; transition: all 0.2s; }
    .input-group-modern input:focus + label, .input-group-modern input:not(:placeholder-shown) + label,
    .input-group-modern textarea:focus + label, .input-group-modern textarea:not(:placeholder-shown) + label {
        top: -10px; left: 12px; font-size: 0.75rem; font-weight: 800; background: #fff; padding: 0 8px; color: #6366f1;
    }

    /* Custom Select & Date Styling */
    .custom-select-input {
        border: 2px solid #f1f5f9; background-color: #f8fafc; border-radius: 14px; padding: 12px 16px; font-size: 1rem; transition: all 0.2s;
    }
    .custom-select-input:focus { border-color: #6366f1; background-color: #fff; box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1); }

    .upload-container { border: 2px dashed #cbd5e1; background: #f8fafc; border-radius: 14px; text-align: center; cursor: pointer; transition: all 0.2s; }
    .upload-container:hover { border-color: #6366f1; background: #f1f5f9; }
    
    .ls-wide { letter-spacing: 0.05em; }
    .btn-white { background-color: white; border: 1px solid #e2e8f0; }

    optgroup { font-weight: 700; color: #6366f1; text-transform: uppercase; font-size: 0.8rem; background: #fff; }
</style>

<script>
    function previewUpdateImage(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('update-preview');
            const text = document.getElementById('upload-text');
            output.src = reader.result;
            output.classList.remove('d-none');
            text.classList.add('d-none');
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endsection
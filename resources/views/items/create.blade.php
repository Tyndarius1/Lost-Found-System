@extends('layouts.user')

@section('content')
<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('items.index') }}" class="btn btn-white border shadow-sm rounded-circle me-3" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-arrow-left text-dark"></i>
                </a>
                <div>
                    <h2 class="fw-bold mb-0">Report an Item</h2>
                    <p class="text-muted mb-0">Provide details to help return the item to its owner.</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm p-4 p-md-5" style="border-radius: 24px;">
                <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data" id="postItemForm">
                    @csrf

                    <div class="mb-5">
                        <label class="fw-bold text-dark mb-3">Item Photo</label>
                        <div class="upload-container" id="dropzone" onclick="document.getElementById('fileInput').click()">
                            <input type="file" name="image" id="fileInput" class="d-none" accept="image/*" onchange="previewImage(event)">
                            <div id="upload-placeholder">
                                <i class="bi bi-cloud-arrow-up fs-1 text-primary"></i>
                                <h6 class="mt-3 fw-bold">Drag & Drop or Click to Upload</h6>
                                <p class="text-muted small">Supports JPG, PNG (Max 2MB)</p>
                            </div>
                            <img id="image-preview" src="#" alt="Preview" class="d-none img-fluid rounded-3" style="max-height: 300px;">
                            <div id="remove-preview" class="d-none" onclick="clearImage(event)">
                                <i class="bi bi-x-circle-fill"></i> Remove
                            </div>
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase text-muted ls-wide">What happened?</label>
                            <div class="d-flex gap-3">
                                <input type="radio" class="btn-check" name="type" id="typeLost" value="lost" required>
                                <label class="btn btn-outline-danger w-100 py-2 fw-bold rounded-3" for="typeLost">I Lost This</label>

                                <input type="radio" class="btn-check" name="type" id="typeFound" value="found">
                                <label class="btn btn-outline-success w-100 py-2 fw-bold rounded-3" for="typeFound">I Found This</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase text-muted ls-wide">When?</label>
                            <input type="date" name="date" class="form-control custom-input" required>
                        </div>

                        <div class="col-12">
                            <div class="input-group-modern">
                                <input type="text" name="item_name" placeholder=" " required />
                                <label>What is the item? (e.g. Blue iPhone 13)</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase text-muted ls-wide">Category</label>
                            <select name="category" class="form-select custom-input" required>
                                <option value="" selected disabled>Select Category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}">{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase text-muted ls-wide">School Location</label>
                            <select name="location" class="form-select custom-input" required>
                                <option value="" selected disabled>Select Location</option>
                                @foreach($locations as $group => $rooms)
                                    <optgroup label="{{ $group }}">
                                        @foreach($rooms as $room)
                                            <option value="{{ $room }}">{{ $room }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <div class="input-group-modern">
                                <textarea name="description" rows="4" placeholder=" " required></textarea>
                                <label>Provide a detailed description...</label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 text-end">
                        <button type="submit" class="btn btn-primary px-5 py-3 fw-bold rounded-pill shadow-sm">
                            Submit Report <i class="bi bi-send ms-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Modern Inputs Style */
    .input-group-modern {
        position: relative;
        width: 100%;
    }
    .input-group-modern input, .input-group-modern textarea {
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
    .input-group-modern input:focus, .input-group-modern textarea:focus {
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

    /* Select specific styling to match modern inputs */
    .form-select.custom-input {
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2394a3b8' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 16px 12px;
    }

    /* Custom Uploader Style */
    .upload-container {
        border: 2px dashed #cbd5e1;
        background: #f8fafc;
        border-radius: 20px;
        padding: 40px;
        text-align: center;
        cursor: pointer;
        position: relative;
        transition: all 0.2s;
    }
    .upload-container:hover {
        border-color: #6366f1;
        background: #f1f5f9;
    }
    #remove-preview {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(239, 68, 68, 0.9);
        color: white;
        padding: 5px 12px;
        border-radius: 50px;
        font-size: 0.8rem;
        cursor: pointer;
    }
    .custom-input {
        border-radius: 14px;
        padding: 12px;
        border: 2px solid #f1f5f9;
        background: #f8fafc;
    }
    .ls-wide { letter-spacing: 0.05em; }
    
    /* Optgroup styling */
    optgroup {
        font-weight: 700;
        color: #6366f1;
    }
</style>

<script>
    function previewImage(event) {
        const input = event.target;
        const reader = new FileReader();
        reader.onload = function(){
            const dataURL = reader.result;
            const output = document.getElementById('image-preview');
            const placeholder = document.getElementById('upload-placeholder');
            const removeBtn = document.getElementById('remove-preview');
            
            output.src = dataURL;
            output.classList.remove('d-none');
            placeholder.classList.add('d-none');
            removeBtn.classList.remove('d-none');
        };
        reader.readAsDataURL(input.files[0]);
    }

    function clearImage(event) {
        event.stopPropagation();
        const input = document.getElementById('fileInput');
        const output = document.getElementById('image-preview');
        const placeholder = document.getElementById('upload-placeholder');
        const removeBtn = document.getElementById('remove-preview');
        
        input.value = '';
        output.classList.add('d-none');
        placeholder.classList.remove('d-none');
        removeBtn.classList.add('d-none');
    }
</script>
@endsection
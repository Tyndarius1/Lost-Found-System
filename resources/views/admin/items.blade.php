@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<div class="container-fluid">
    {{-- Header Section --}}
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-bold mb-1 text-dark">Manage Reported Items</h2>
            <p class="text-muted mb-0">Monitor and moderate all lost and found posts across the system.</p>
        </div>
        <div class="badge bg-primary bg-opacity-10 text-primary px-4 py-2 rounded-pill fw-bold" style="font-size: 0.85rem;">
            <i class="bi bi-box-seam me-2"></i> Total Items: {{ $items->count() }}
        </div>
    </div>

    {{-- Items Table Card --}}
    <div class="card border-0 shadow-sm" style="border-radius: 24px; overflow: hidden;">
        <div class="table-responsive" style="overflow: visible;">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-muted small fw-bold text-uppercase">Item Details</th>
                        <th class="py-3 text-muted small fw-bold text-uppercase">Location & Date</th>
                        <th class="py-3 text-muted small fw-bold text-uppercase">Posted By</th>
                        <th class="py-3 text-muted small fw-bold text-uppercase text-center">Status</th>
                        <th class="pe-4 py-3 text-muted small fw-bold text-uppercase text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="rounded-3 overflow-hidden bg-light shadow-sm me-3" style="width: 55px; height: 55px; border: 1px solid #f1f5f9; flex-shrink: 0;">
                                    @if($item->image)
                                        <img src="{{ asset('storage/' . $item->image) }}" class="w-100 h-100" style="object-fit: cover;">
                                    @else
                                        <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted">
                                            <i class="bi bi-image fs-4"></i>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <div class="fw-bold text-dark mb-0">{{ $item->item_name }}</div>
                                    <span class="badge rounded-pill {{ $item->type == 'lost' ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success' }}" style="font-size: 0.65rem; font-weight: 800; text-transform: uppercase;">
                                        {{ $item->type }}
                                    </span>
                                    <span class="text-muted small ms-1">in {{ $item->category }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="small fw-semibold text-dark"><i class="bi bi-geo-alt text-primary me-1"></i> {{ $item->location }}</div>
                            <div class="text-muted small"><i class="bi bi-calendar3 me-1"></i> {{ \Carbon\Carbon::parse($item->date)->format('M d, Y') }}</div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2 shadow-sm" style="width: 32px; height: 32px; font-size: 0.75rem; font-weight: 700;">
                                    {{ substr($item->user->name ?? 'U', 0, 1) }}
                                </div>
                                <span class="small fw-medium text-dark text-truncate" style="max-width: 120px;">{{ $item->user->name ?? 'Unknown' }}</span>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="badge rounded-pill px-3 py-2 border {{ $item->status == 'returned' ? 'bg-success-subtle text-success border-success' : 'bg-info-subtle text-info border-info' }}" style="font-size: 0.75rem;">
                                <i class="bi {{ $item->status == 'returned' ? 'bi-check-circle' : 'bi-clock' }} me-1"></i>
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                        <td class="pe-4 text-end">
                            <div class="d-flex justify-content-end gap-2">
                                {{-- FIXED EDIT BUTTON TRIGGER --}}
                                <button type="button" class="btn btn-light btn-sm rounded-circle shadow-sm border action-btn" 
                                        onclick="openEditItemModal({{ $item->id }}, '{{ addslashes($item->item_name) }}', '{{ $item->type }}', '{{ addslashes($item->category) }}', '{{ addslashes($item->location) }}', '{{ $item->date }}', '{{ addslashes($item->description) }}', '{{ $item->status }}', '{{ $item->image }}')">
                                    <i class="bi bi-pencil-square text-warning"></i>
                                </button>

                                {{-- FIXED DELETE BUTTON TRIGGER --}}
                                <button type="button" class="btn btn-light btn-sm rounded-circle shadow-sm border action-btn" 
                                        onclick="confirmItemDelete({{ $item->id }})">
                                    <i class="bi bi-trash3 text-danger"></i>
                                </button>

                                <form id="delete-item-{{ $item->id }}" action="{{ route('admin.items.delete', $item->id) }}" method="POST" class="d-none">
                                    @csrf 
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL: EDIT ITEM --}}
<div class="modal fade" id="editItemModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 24px;">
            <div class="modal-header border-0 p-4 pb-0">
                <h5 class="fw-bold"><i class="bi bi-pencil-square me-2 text-primary"></i>Edit Item Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editItemForm" method="POST" enctype="multipart/form-data" class="p-4">
                @csrf @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="small fw-bold text-muted mb-1">Report Type</label>
                        <select name="type" id="edit_item_type" class="form-select rounded-3 border-light-subtle">
                            <option value="lost">Lost</option>
                            <option value="found">Found</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="small fw-bold text-muted mb-1">Status</label>
                        <select name="status" id="edit_item_status" class="form-select rounded-3 border-light-subtle">
                            <option value="open">Open (Active)</option>
                            <option value="returned">Returned / Resolved</option>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <label class="small fw-bold text-muted mb-1">Item Name</label>
                        <input type="text" name="item_name" id="edit_item_name" class="form-control rounded-3 border-light-subtle" required>
                    </div>
                    <div class="col-md-4">
                        <label class="small fw-bold text-muted mb-1">Category</label>
                        <input type="text" name="category" id="edit_item_category" class="form-control rounded-3 border-light-subtle" required>
                    </div>
                    <div class="col-md-6">
                        <label class="small fw-bold text-muted mb-1">Location</label>
                        <input type="text" name="location" id="edit_item_location" class="form-control rounded-3 border-light-subtle" required>
                    </div>
                    <div class="col-md-6">
                        <label class="small fw-bold text-muted mb-1">Date Reported</label>
                        <input type="date" name="date" id="edit_item_date" class="form-control rounded-3 border-light-subtle" required>
                    </div>
                    <div class="col-12">
                        <label class="small fw-bold text-muted mb-1">Description</label>
                        <textarea name="description" id="edit_item_description" rows="3" class="form-control rounded-3 border-light-subtle"></textarea>
                    </div>
                    <div class="col-12">
                        <label class="small fw-bold text-muted mb-1">Item Image</label>
                        <input type="file" name="image" class="form-control rounded-3 border-light-subtle">
                        <div id="current_image_preview" class="mt-3"></div>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-top d-flex gap-2">
                    <button type="button" class="btn btn-light px-4 py-2 fw-bold rounded-pill" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-5 py-2 fw-bold rounded-pill shadow-sm flex-grow-1">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    /**
     * PRE-FILLS AND OPENS THE EDIT MODAL
     */
    function openEditItemModal(id, name, type, category, location, date, description, status, image) {
        const form = document.getElementById('editItemForm');
        form.action = `/admin/items/${id}`;

        document.getElementById('edit_item_name').value = name;
        document.getElementById('edit_item_type').value = type;
        document.getElementById('edit_item_category').value = category;
        document.getElementById('edit_item_location').value = location;
        document.getElementById('edit_item_date').value = date;
        document.getElementById('edit_item_description').value = description;
        document.getElementById('edit_item_status').value = status;

        const previewDiv = document.getElementById('current_image_preview');
        if (image && image !== 'null') {
            previewDiv.innerHTML = `<p class="small text-muted mb-1">Current Image:</p>
                                    <img src="/storage/${image}" class="rounded-3 border shadow-sm" style="height: 100px; width: 100px; object-fit: cover;">`;
        } else {
            previewDiv.innerHTML = '';
        }

        const editModal = new bootstrap.Modal(document.getElementById('editItemModal'));
        editModal.show();
    }

    /**
     * SWEETALERT DELETE CONFIRMATION
     */
    function confirmItemDelete(itemId) {
        Swal.fire({
            title: 'Delete this item?',
            text: "This action cannot be undone and will remove the post.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Yes, delete it',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            customClass: { popup: 'rounded-4 shadow-lg border-0' },
            buttonsStyling: false,
            customClass: {
                confirmButton: 'btn btn-danger px-4 py-2 rounded-pill fw-bold ms-2',
                cancelButton: 'btn btn-light px-4 py-2 rounded-pill fw-bold text-dark',
                popup: 'rounded-4 border-0'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-item-' + itemId).submit();
            }
        })
    }
</script>

<style>
    .action-btn {
        width: 38px;
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    .action-btn:hover {
        transform: scale(1.15);
        background-color: #fff !important;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
    }
    .table-hover tbody tr:hover {
        background-color: #f8fafc;
    }
</style>
@endsection
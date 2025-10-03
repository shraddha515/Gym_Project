@extends('admin.layout')
@section('page-title', 'Super Admin Dashboard')

@section('content')
@section('styles')
<style>
    .form-label {
        color: #212529;

    }

    /* Global Styles */
    body {
        background-color: #f4f6f9;
        font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    }

    /* Dashboard Header */
    .dashboard-heading {
        font-weight: 600;
        color: #212529;
        font-size: 1.5rem;
    }

    /* Add Gym Button */
    .btn-add-gym {
        background-image: linear-gradient(45deg, #023661 0%, #015f70 100%);
        color: white;
        font-weight: 500;
        border: none;
        padding: 10px 25px;
        border-radius: 10px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(0, 123, 255, 0.3);
    }

    .btn-add-gym:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0, 123, 255, 0.4);
    }

    /* Table Professional Look */
    .card {
        border-radius: 12px;
        border: none;
    }

    .card-title {
        font-weight: 600;
        color: #f4f1f1;
        background: linear-gradient(45deg, #023661 0%, #015f70 100%);
        padding: 10px 10px 10px 10px;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
    }

    .table-professional {
        border-collapse: separate;
        border-spacing: 0;
        border-radius: 8px;
        overflow: hidden;
    }

    .table-professional thead tr {
        background: linear-gradient(45deg, #023661 0%, #015f70 100%);
        color: white;
    }

    .table-professional th,
    .table-professional td {
        padding: 12px 1rem;
        vertical-align: middle;
        font-size: 1rem;
        /* Reduced font size for a cleaner look */
        font-weight: normal;
        /* Ensures simple font, not bold */
    }

    .table-professional th {
        font-weight: 500;
        /* Slightly bolder for headers to distinguish them from content */
    }

    .table-professional tbody tr:hover {
        background-color: #f1f5f9;
        transform: scale(1.01);
        transition: transform 0.2s ease-in-out;
    }

    .table-professional tbody tr:nth-of-type(odd) {
        background-color: #c3c7cb;
    }

    .table-professional tbody tr:nth-of-type(even) {
        background-color: #ffffff;
    }

    /* Mobile Cards */
    .gym-card-mobile {
        border-radius: 12px;
        border: 1px solid #e9ecef;
    }

    .card-title-mobile {
        font-weight: 600;
        font-size: 1rem;
        color: #343a40;
    }

    .card-subtitle-mobile {
        font-size: 0.75rem;
        font-weight: 400;
    }

    .list-unstyled li {
        font-size: 0.85rem;
        color: #6c757d;
    }

    /* Modal Form */
    .professional-modal {
        border-radius: 12px;
        border: none;
    }

    .modal-header-gradient {
        background-image: linear-gradient(45deg, #023661 0%, #015f70 100%);
        color: white;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        border-bottom: none;
        margin-top: 44px;
    }

    .btn-close-white {
        filter: invert(1);
    }

    .professional-label {
        font-weight: 500;
        color: #343a40;
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }

    .professional-input {
        border-radius: 8px;
        padding: 10px 15px;
        font-size: 0.9rem;
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
    }

    .professional-input:focus {
        box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.15);
        border-color: #80bdff;
    }

    .professional-btn {
        background-image: linear-gradient(45deg, #023661 0%, #015f70 100%);
        border: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .professional-btn:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }

    /* Cards */
.member-card {
    border: 2px solid #e2e8f0;
    border-radius: 6px;
    background: #ffffff;
    transition: all 0.2s ease-in-out;
}
.member-card:hover {
    border-color: #3b82f6; /* theme highlight on hover */
}
.member-name {
    font-size: 0.9rem;
    font-weight: 500;
    color: #1f2937;
}

/* Fields inside card */
.member-fields .list-group-item {
    font-size: 0.82rem;
    padding: 6px 8px;
    border: none;
    display: flex;
    justify-content: space-between;
}
.member-fields .list-group-item:active,
.member-fields .list-group-item:focus {
    background: #f0f9ff;
    color: #111827;
}

/* Buttons */
.btn-action {
    font-size: 0.75rem;
    padding: 4px 44px !important;
    border-radius: 4px;
    background: linear-gradient(45deg, #053d96 0%, #00a0c6 100%);
    color: #fff;
    border: none;
    flex: 1;
    text-align: center;
    transition: all 0.3s ease;
}
.btn-action:hover {
    color: #111827;
    filter: brightness(110%);
}
.btn-action:active {
    transform: scale(0.95);
}

/* Badge size fix */
.badge {
    font-size: 0.8rem;
    padding: 4px 6px;
}

/* Mobile tweak */
@media (max-width: 768px) {
    .member-fields .list-group-item span:first-child {
        font-size: 0.85rem;
    }
}

</style>
@endsection
<div class="container-fluid py-4">
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="d-flex justify-content-end mb-4">
        <button class="btn btn-add-gym" data-bs-toggle="modal" data-bs-target="#addEditGymModal" id="addGymBtn">
            <i class="bi bi-plus-circle me-2"></i> Add New Gym
        </button>
    </div>

    <div class="card shadow-sm p-4">
        <h4 class="card-title mb-3">All Companies</h4>
        <div class="table-responsive d-none d-md-block">
            <table class="table table-hover table-striped table-professional align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Company</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Created</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($gyms as $gym)
                    <tr>
                        <td>{{ $gym->gym_id }}</td>
                        <td>{{ $gym->company_name }}</td>
                        <td>{{ $gym->email }}</td>
                        <td>{{ $gym->phone }}</td>
                        <td>{{ \Carbon\Carbon::parse($gym->created_at)->format('d M, Y') }}</td>
                        <td>
                            @if($gym->status == 'Active')
                            <span class="badge bg-success">Active</span>
                            @else
                            <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>


                        <td>
                            <button class="btn btn-sm  edit-btn"
                                data-bs-toggle="modal"
                                data-bs-target="#addEditGymModal"
                                data-gym-id="{{ $gym->gym_id }}"
                                data-company-name="{{ $gym->company_name }}"
                                data-email="{{ $gym->email }}"
                                data-phone="{{ $gym->phone }}"
                                data-address="{{ $gym->address }}"
                                data-status="{{ $gym->status }}"  style="background: linear-gradient(45deg, #053d96 0%, #00a0c6 100%); color:white;">
                                <i class="bi bi-pencil-square"></i> Edit
                            </button>


                            <form action="{{ route('superadmin.deleteCompany', $gym->gym_id) }}" method="POST"
                                class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger delete-btn">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No companies found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>



{{-- Mobile Companies Cards --}}
<div class="d-md-none" id="mobileCompaniesAccordion">
    @forelse($gyms as $gym)
        <div class="card mb-3 member-card">
            <div class="card-header d-flex justify-content-between align-items-center p-2"
                data-bs-toggle="collapse" data-bs-target="#companyCard{{ $gym->gym_id }}"
                style="cursor:pointer;">
                <span class="member-name">{{ $gym->company_name }}</span>
                <i class="bi bi-chevron-down text-muted"></i>
            </div>

            <div class="collapse" id="companyCard{{ $gym->gym_id }}" data-bs-parent="#mobileCompaniesAccordion">
                <div class="card-body p-2">
                    <ul class="list-group list-group-flush member-fields">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>ID</span>
                            <span>{{ $gym->gym_id }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Email</span>
                            <span>{{ $gym->email }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Phone</span>
                            <span>{{ $gym->phone }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Address</span>
                            <span>{{ $gym->address ?? '-' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Created</span>
                            <span>{{ \Carbon\Carbon::parse($gym->created_at)->format('d M, Y') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Status</span>
                            <span class="badge {{ $gym->status == 'Active' ? 'bg-success' : 'bg-danger' }}">
                                {{ $gym->status }}
                            </span>
                        </li>
                    </ul>

                    <div class="d-flex justify-content-between gap-1 mt-2">
                        <button class="btn btn-action"
                            data-bs-toggle="modal"
                            data-bs-target="#addEditGymModal"
                            data-gym-id="{{ $gym->gym_id }}"
                            data-company-name="{{ $gym->company_name }}"
                            data-email="{{ $gym->email }}"
                            data-phone="{{ $gym->phone }}"
                            data-address="{{ $gym->address }}"
                            data-status="{{ $gym->status }}">
                            <i class="bi bi-pencil-square me-1"></i> Edit
                        </button>

                        <form action="{{ route('superadmin.deleteCompany', $gym->gym_id) }}" method="POST"
                            onsubmit="return confirm('Are you sure?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-action">
                                <i class="bi bi-trash me-1"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center text-muted py-4">No companies found.</div>
    @endforelse
</div>

{{-- Collapse toggle script --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.card-header[data-bs-toggle="collapse"]');
        cards.forEach(card => {
            card.addEventListener('click', function() {
                cards.forEach(c => {
                    if (c !== this) {
                        const target = document.querySelector(c.dataset.bsTarget);
                        if (target.classList.contains('show')) {
                            bootstrap.Collapse.getInstance(target).hide();
                        }
                    }
                });
            });
        });
    });
</script>

{{-- Add/Edit Gym Modal --}}
<div class="modal fade" id="addEditGymModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content professional-modal">
            <div class="modal-header modal-header-gradient">
                <h5 class="modal-title text-white" id="modalTitle">Add New Gym Company</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form method="POST" id="gymForm">
                    @csrf
                    <input type="hidden" name="_method" id="methodField" value="POST">

                    <div class="mb-3">
                        <label for="company_name" class="form-label">Company Name</label>
                        <input type="text" name="company_name" id="company_name" class="form-control" required>
                    </div>

                    <div class="mb-3" id="passwordGroup">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" name="phone" id="phone" class="form-control" maxlength="10">
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" name="address" id="address" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>

 
                    <button type="submit" class="btn " id="submitBtn" style="background:linear-gradient(45deg, #023661 0%, #015f70 100%); color:white;">Add Gym</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('addEditGymModal');
        const form = document.getElementById('gymForm');
        const modalTitle = document.getElementById('modalTitle');
        const submitBtn = document.getElementById('submitBtn');
        const methodField = document.getElementById('methodField');
        const passwordGroup = document.getElementById('passwordGroup');
        const passwordInput = document.getElementById('password');

        // Add Gym Button
        document.getElementById('addGymBtn').addEventListener('click', function() {
            modalTitle.textContent = 'Add New Gym Company';
            submitBtn.textContent = 'Add Gym';
            form.reset();
            form.action = "{{ route('superadmin.addCompany') }}";
            methodField.value = "POST";
            passwordGroup.style.display = 'block';
            passwordInput.setAttribute('required', 'required');
        });

        // Edit Buttons
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                const gymId = this.dataset.gymId;
                modalTitle.textContent = 'Edit Gym Company';
                submitBtn.textContent = 'Update Gym';
                form.action = "{{ url('superadmin/update-company') }}/" + gymId;
                methodField.value = "PUT";
                passwordGroup.style.display = 'none';
                passwordInput.removeAttribute('required');

                document.getElementById('company_name').value = this.dataset.companyName;
                document.getElementById('email').value = this.dataset.email;
                document.getElementById('phone').value = this.dataset.phone;
                document.getElementById('address').value = this.dataset.address;
                document.getElementById('status').value = this.dataset.status;
            });
        });

        // Delete confirmation
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!confirm('Are you sure?')) e.preventDefault();
            });
        });
    });
</script>
@endsection
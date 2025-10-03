@extends('admin.layout')

@section('page-title', 'Staff Members')

@section('content')
@section('styles')
    <style>
        .form-label {
            color: black
        }

        /* General */
        .dashboard-heading {
            font-weight: 600;
            margin-bottom: 1rem;
            color: #ffffff;
        }

        .card {
            border-radius: 12px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .table th,
        .table td {
            vertical-align: middle !important;
            padding: 0.55rem 0.75rem;
        }

        .table th {
            font-weight: 500;
            font-size: 0.95rem;
        }

        .table td {
            font-size: 0.9rem;
        }

        /* Buttons */
        .btn-action {
            font-size: 0.75rem;
            padding: 4px 6px;
            border-radius: 6px;
            color: #fff;
            border: none;
            text-align: center;
            white-space: nowrap;
            transition: all 0.3s ease;
        }

        .btn-action:hover {
            filter: brightness(110%);
        }

        .btn-action:active {
            transform: scale(0.95);
        }

        .btn-action.btn-primary {
            background: linear-gradient(45deg, #3b82f6, #2563eb);
        }

        .btn-action.btn-danger {
            background: linear-gradient(45deg, #ef4444, #dc2626);
        }

        .btn-action.btn-info {
            background: linear-gradient(45deg, #0dcaf0, #0ea5e9);
        }

        /* Badges */
        .badge {
            font-size: 0.75rem;
            padding: 0.3em 0.5em;
        }

        /* Mobile Cards */
        .member-card {
            border: 2px solid #e2e8f0;
            border-radius: 6px;
            background: #fff;
            transition: all 0.2s ease;
        }

        .member-card:hover {
            border-color: #3b82f6;
        }

        .member-name {
            font-size: 0.9rem;
            font-weight: 500;
            color: #1f2937;
        }

        .member-fields .list-group-item {
            font-size: 0.82rem;
            padding: 6px 8px;
            display: flex;
            justify-content: space-between;
            border: none;
        }

        .status-badge {
            font-size: 0.8rem;
            padding: 0.2em 0.5em;
        }

        /* Responsive */
        @media(max-width:767px) {
            .member-card {
                margin-bottom: 0.75rem;
            }

            .member-name {
                font-size: 0.85rem;
            }

            .member-fields .list-group-item {
                font-size: 0.78rem;
                padding: 5px 6px;
            }

            .btn-action {
                font-size: 0.7rem;
                padding: 3px 4px;
            }

            .dashboard-heading {
                font-size: 1.5rem;
            }
        }
    </style>
@endsection
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <h2 class="dashboard-heading">Staff Members</h2>
        <button class="btn shadow-sm" data-bs-toggle="modal" data-bs-target="#staffModal"
            style="background: linear-gradient(45deg, #053d96 0%, #00a0c6 100%); color:white;">
            <i class="bi bi-person-plus-fill"></i> Add New Staff
        </button>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Modal for Add/Edit --}}
    <div class="modal fade" id="staffModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-3 border-0 shadow">
                <div class="modal-header" style="background: linear-gradient(45deg, #053d96 0%, #00a0c6 100%);">
                    <h5 class="modal-title text-white">
                        {{ isset($editStaff) ? 'Edit Staff' : 'Add New Staff' }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form
                    action="{{ isset($editStaff) ? route('gym.staff.update', $editStaff->id) : route('gym.staff.store') }}"
                    method="POST">
                    @csrf

                    <div class="modal-body">
                        <div class="row g-3" style="text-align: left;">
                            <div class="col-md-6">
                                <label class="form-label small">Name *</label>
                                <input type="text" name="name" class="form-control form-control-sm"
                                    value="{{ old('name', $editStaff->name ?? '') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small">Type *</label>
                                <select name="type" class="form-select form-select-sm" required>
                                    @foreach (['Gym Owner / Director', 'General Manager', 'Receptionist / Front Desk Executive', 'Membership Consultant / Sales Executive', 'Personal Trainer', 'Group Fitness Instructor', 'Strength & Conditioning Coach', 'Nutritionist / Dietitian', 'Physiotherapist', 'Cleaning Staff / Housekeeping', 'Maintenance Staff', 'Accountant / Cashier', 'Marketing Executive', 'IT/Admin Support', 'Security Guard'] as $t)
                                        <option value="{{ $t }}"
                                            {{ isset($editStaff) && $editStaff->type == $t ? 'selected' : '' }}>
                                            {{ $t }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small">Mobile *</label>
                                <input type="tel" name="mobile_number" class="form-control form-control-sm"
                                    value="{{ old('mobile_number', $editStaff->mobile_number ?? '') }}" required
                                    pattern="[0-9]{10}" maxlength="10"
                                    oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small">Email</label>
                                <input type="email" name="email" class="form-control form-control-sm"
                                    value="{{ old('email', $editStaff->email ?? '') }}">
                            </div>

                            <div class="col-12">
                                <label class="form-label small">Address</label>
                                <input type="text" name="address" class="form-control form-control-sm"
                                    value="{{ old('address', $editStaff->address ?? '') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small">Date of Birth</label>
                                <input type="date" name="date_of_birth" class="form-control form-control-sm"
                                    value="{{ old('date_of_birth', $editStaff->date_of_birth ?? '') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small">Status</label>
                                <select name="active_status" class="form-select form-select-sm">
                                    <option value="Active"
                                        {{ isset($editStaff) && $editStaff->active_status == 'Active' ? 'selected' : '' }}>
                                        Active</option>
                                    <option value="Inactive"
                                        {{ isset($editStaff) && $editStaff->active_status == 'Inactive' ? 'selected' : '' }}>
                                        Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer d-flex justify-content-end">
                        <button type="button" class="btn  btn-sm" data-bs-dismiss="modal"
                            style="background:linear-gradient(45deg, #023661 0%, #015f70 100%); color:white;">Cancel</button>
                        <button type="submit" class="btn  btn-sm px-3"
                            style="background:linear-gradient(45deg, #023661 0%, #015f70 100%); color:white;">
                            {{ isset($editStaff) ? 'Update Staff' : 'Add Staff' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Desktop Table --}}
    <div class="d-none d-md-block">
        <div class="card shadow-sm border rounded-3 p-3">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-dark small">Name</th>
                        <th class="text-dark small">Type</th>
                        <th class="text-dark small">Mobile</th>
                        <th class="text-dark small">Email</th>
                        <th class="text-dark small">Status</th>
                        <th class="text-dark small text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($staff as $s)
                        <tr>
                            <td class="small text-dark">{{ $s->name }}</td>
                            <td class="small text-dark">{{ $s->type }}</td>
                            <td class="small text-dark">{{ $s->mobile_number }}</td>
                            <td class="small text-dark">{{ $s->email ?? '-' }}</td>
                            <td class="small text-center">
                                <span
                                    class="badge {{ $s->active_status == 'Active' ? 'bg-success' : 'bg-secondary' }} small">{{ $s->active_status }}</span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1 flex-wrap">
                                    <form action="{{ route('gym.staff.destroy', $s->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        <button class="btn btn-action btn-danger small"
                                            onclick="return confirm('Delete staff?')">Delete</button>
                                    </form>
                                    <a href="{{ route('gym.staff.index', ['edit_id' => $s->id]) }}"
                                        class="btn btn-action btn-primary small">Edit</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Mobile Staff Cards --}}
    <div class="d-md-none" id="mobileStaffAccordion">
        @foreach ($staff as $s)
            <div class="card mb-3 member-card">
                <div class="card-header d-flex justify-content-between align-items-center p-2"
                    data-bs-toggle="collapse" data-bs-target="#staffCard{{ $s->id }}"
                    style="cursor:pointer;">
                    <div class="d-flex align-items-center gap-2">
                        <span class="member-name">{{ $s->name }}</span>
                        <small class="text-muted">{{ $s->type }}</small>
                    </div>
                    <span
                        class="badge {{ $s->active_status == 'Active' ? 'bg-success' : 'bg-secondary' }} status-badge">
                        {{ $s->active_status }}
                    </span>
                </div>

                <div class="collapse" id="staffCard{{ $s->id }}" data-bs-parent="#mobileStaffAccordion">
                    <div class="card-body p-2">
                        <ul class="list-group list-group-flush member-fields">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Mobile</span>
                                <span>{{ $s->mobile_number }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Email</span>
                                <span>{{ $s->email ?? '-' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Members</span>
                                <span>{{ DB::table('members')->where('assigned_staff_id', $s->id)->count() }}</span>
                            </li>
                        </ul>

                        <div class="d-flex justify-content-between gap-1 mt-2">
                            <form action="{{ route('gym.staff.destroy', $s->id) }}" method="POST"
                                onsubmit="return confirm('Are you sure?');" style="flex:1;">
                                @csrf
                                <button type="submit" class="btn btn-action btn-danger">
                                    <i class="bi bi-trash me-1"></i> Delete
                                </button>
                            </form>
                            <a href="{{ route('gym.staff.index', ['edit_id' => $s->id]) }}"
                                class="btn btn-action btn-primary">
                                <i class="bi bi-pencil me-1"></i> Edit
                            </a>
                            <a href="#" class="btn btn-action btn-info" data-bs-toggle="collapse"
                                data-bs-target="#assignedMembersStaff{{ $s->id }}">
                                <i class="bi bi-people me-1"></i> Members
                            </a>
                        </div>

                        <div class="collapse mt-2" id="assignedMembersStaff{{ $s->id }}">
                            <ul class="mb-0 mt-1 small text-dark">
                                @foreach (DB::table('members')->where('assigned_staff_id', $s->id)->get() as $m)
                                    <li>{{ $m->first_name }} {{ $m->last_name }} (ID: {{ $m->member_id }})</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</div>
@if (isset($editStaff))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var editModal = new bootstrap.Modal(document.getElementById('staffModal'));
            editModal.show();
        });
    </script>
@endif

@endsection

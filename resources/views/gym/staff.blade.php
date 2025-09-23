@extends('admin.layout')

@section('page-title','Staff Members')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <h2 class="dashboard-heading">Staff Members</h2>
        <button class="btn shadow-sm" data-bs-toggle="modal" data-bs-target="#staffModal"
            style="background: linear-gradient(45deg, #3b82f6 0%, #a855f7 100%); color:white;">
            <i class="bi bi-person-plus-fill"></i> Add New Staff
        </button>
    </div>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Modal for Add/Edit --}}
    <div class="modal fade" id="staffModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-3 border-0 shadow">
                <div class="modal-header" style="background: linear-gradient(45deg, #3b82f6, #a855f7);">
                    <h5 class="modal-title text-white">
                        {{ isset($editStaff) ? 'Edit Staff' : 'Add New Staff' }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ isset($editStaff) ? route('gym.staff.update',$editStaff->id) : route('gym.staff.store') }}" method="POST">
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
                                    @foreach(['Personal Trainer','Nutritionist'] as $t)
                                    <option value="{{ $t }}" {{ (isset($editStaff) && $editStaff->type==$t) ? 'selected' : '' }}>
                                        {{ $t }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small">Mobile *</label>
                                <input type="tel" name="mobile_number"
                                    class="form-control form-control-sm"
                                    value="{{ old('mobile_number', $editStaff->mobile_number ?? '') }}"
                                    required
                                    pattern="[0-9]{10}"
                                    maxlength="10"
                                    oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                            </div>


                            <div class="col-md-6">
                                <label class="form-label small">Email</label>
                                <input type="email" name="email" class="form-control form-control-sm"
                                    value="{{ old('email',$editStaff->email ?? '') }}">
                            </div>

                            <div class="col-12">
                                <label class="form-label small">Address</label>
                                <input type="text" name="address" class="form-control form-control-sm"
                                    value="{{ old('address',$editStaff->address ?? '') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small">Date of Birth</label>
                                <input type="date" name="date_of_birth" class="form-control form-control-sm"
                                    value="{{ old('date_of_birth',$editStaff->date_of_birth ?? '') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small">Status</label>
                                <select name="active_status" class="form-select form-select-sm">
                                    <option value="Active" {{ (isset($editStaff) && $editStaff->active_status=='Active') ? 'selected' : '' }}>Active</option>
                                    <option value="Inactive" {{ (isset($editStaff) && $editStaff->active_status=='Inactive') ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer d-flex justify-content-end">
                        <button type="button" class="btn  btn-sm" data-bs-dismiss="modal" style="background:linear-gradient(45deg, #3b82f6 0%, #a855f7 100%);">Cancel</button>
                        <button type="submit" class="btn  btn-sm px-3" style="background:linear-gradient(45deg, #3b82f6 0%, #a855f7 100%);">
                            {{ isset($editStaff) ? 'Update Staff' : 'Add Staff' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    @section('styles')
    <style>
        .dashboard-heading {
            font-weight: 600;
            margin-bottom: 1rem;
            color: #ffffffff;
        }

        .form-label {
            font-size: 0.8rem;
            font-weight: 500;
            color: #374151;
        }

        .form-control-sm,
        .form-select-sm {
            font-size: 0.85rem;
            padding: 0.4rem 0.6rem;
            border-radius: 6px;
        }

        .modal-content {
            border-radius: 12px;
        }

        .modal-title {
            font-size: 1rem;
            font-weight: 600;
        }

        .btn {
            font-size: 0.85rem;
            border-radius: 6px;
        }

        @media(max-width:767px) {

            .form-control-sm,
            .form-select-sm {
                font-size: 0.75rem;
                padding: 0.35rem 0.5rem;
            }

            .form-label {
                font-size: 0.7rem;
            }

            .modal-title {
                font-size: 0.9rem;
            }
        }
    </style>
    @endsection

    {{-- Desktop Table --}}
    <div class="d-none d-md-block">
        <div class="card shadow-sm border rounded-3 p-3">
            <table class="table table-hover align-middle mb-0 table-borderless">
                <thead class="table-light">
                    <tr>
                        <th class="small fw-normal text-dark">Name</th>
                        <th class="small fw-normal text-dark">Type</th>
                        <th class="small fw-normal text-dark">Mobile</th>
                        <th class="small fw-normal text-dark">Email</th>
                        <th class="small fw-normal text-dark">Status</th>
                        <th class="small fw-normal text-dark">Members</th>
                        <th class="small fw-normal text-dark">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($staff as $s)
                    <tr>
                        <td class="small text-dark">{{ $s->name }}</td>
                        <td class="small text-dark">{{ $s->type }}</td>
                        <td class="small text-dark">{{ $s->mobile_number }}</td>
                        <td class="small text-dark">{{ $s->email ?? '-' }}</td>
                        <td class="small">
                            <span class="badge {{ $s->active_status=='Active'?'bg-success':'bg-secondary' }} small">{{ $s->active_status }}</span>
                        </td>
                        <td class="small text-dark">{{ DB::table('members')->where('assigned_staff_id',$s->id)->count() }}</td>
                        <td>
                            <div class="d-flex gap-1 flex-wrap">
                                <form action="{{ route('gym.staff.destroy',$s->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button class="btn btn-outline-danger btn-sm small" onclick="return confirm('Delete staff?')">Delete</button>
                                </form>
                                <a href="{{ route('gym.staff.index',['edit_id'=>$s->id]) }}" class="btn btn-outline-primary btn-sm small">Edit</a>
                                {{-- <a href="#" class="btn btn-outline-info btn-sm small" data-bs-toggle="collapse" data-bs-target="#assignedMembers{{ $s->id }}">
                                    View Members
                                </a> --}}
                            </div>
                        </td>
                    </tr>
                    <tr class="collapse" id="assignedMembers{{ $s->id }}">
                        <td colspan="7">
                            <ul class="mb-0 mt-1 small text-dark">
                                @foreach(DB::table('members')->where('assigned_staff_id',$s->id)->get() as $m)
                                <li>{{ $m->first_name }} {{ $m->last_name }} (ID: {{ $m->member_id }})</li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <style>
        .card {
            border: 1px solid #d0d0d0;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .table-hover tbody tr:hover {
            background-color: #f5f5f5;
        }

        .table thead th {
            font-weight: 400;
            font-size: 1.15rem;
            color: #0d6efd;
            /* Theme color for headers */
            border-bottom: 1px solid #d0d0d0;
        }

        .table td {
            font-size: 1rem;
            color: #000;
            vertical-align: middle;
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.3em 0.5em;
        }

        .btn-sm {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }

        @media(max-width:991px) {

            .table td,
            .table th {
                font-size: 0.75rem;
            }

            .badge {
                font-size: 0.7rem;
            }

            .btn-sm {
                font-size: 0.7rem;
            }
        }
    </style>


    {{-- Mobile Staff Cards --}}
    <div class="d-md-none" id="mobileStaffAccordion">
        @foreach($staff as $s)
        <div class="card mb-3 member-card">
            <div class="card-header d-flex justify-content-between align-items-center p-2"
                data-bs-toggle="collapse" data-bs-target="#staffCard{{ $s->id }}"
                style="cursor:pointer;">
                <div class="d-flex align-items-center gap-2">
                    <span class="member-name">{{ $s->name }}</span>
                    <small class="text-muted">{{ $s->type }}</small>
                </div>
                <span class="badge {{ $s->active_status=='Active'?'bg-success':'bg-secondary' }} status-badge">
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
                            <span>{{ DB::table('members')->where('assigned_staff_id',$s->id)->count() }}</span>
                        </li>
                    </ul>

                    <div class="d-flex justify-content-between gap-1 mt-2">
                        <form action="{{ route('gym.staff.destroy',$s->id) }}" method="POST" onsubmit="return confirm('Are you sure?');" style="flex:1;">
                            @csrf
                            <button type="submit" class="btn btn-action btn-danger">
                                <i class="bi bi-trash me-1"></i> Delete
                            </button>
                        </form>
                        <a href="{{ route('gym.staff.index',['edit_id'=>$s->id]) }}" class="btn btn-action btn-primary">
                            <i class="bi bi-pencil me-1"></i> Edit
                        </a>
                        <a href="#" class="btn btn-action btn-info" data-bs-toggle="collapse" data-bs-target="#assignedMembersStaff{{ $s->id }}">
                            <i class="bi bi-people me-1"></i> Members
                        </a>
                    </div>

                    <div class="collapse mt-2" id="assignedMembersStaff{{ $s->id }}">
                        <ul class="mb-0 mt-1 small text-dark">
                            @foreach(DB::table('members')->where('assigned_staff_id',$s->id)->get() as $m)
                            <li>{{ $m->first_name }} {{ $m->last_name }} (ID: {{ $m->member_id }})</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <style>
        /* Mobile Card Styles */
        .member-card {
            border: 2px solid #e2e8f0;
            border-radius: 6px;
            background: #ffffff;
            transition: all 0.2s ease-in-out;
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
            border: none;
            display: flex;
            justify-content: space-between;
        }

        .status-badge {
            font-size: 0.8rem;
            padding: 0.2em 0.5em;
        }

        .btn-action {
            font-size: 0.75rem;
            padding: 4px 4px !important;
            border-radius: 6px;
            background: linear-gradient(45deg, #3b82f6, #a855f7);
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

        /* Individual button colors */
        .btn-action.btn-danger {
            background: linear-gradient(45deg, #ef4444, #dc2626);
        }

        .btn-action.btn-primary {
            background: linear-gradient(45deg, #3b82f6, #2563eb);
        }

        .btn-action.btn-info {
            background: linear-gradient(45deg, #0dcaf0, #0ea5e9);
        }

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
        }
    </style>


</div>
@endsection

@section('styles')
<style>
    .dashboard-heading {
        font-weight: 600;
        margin-bottom: 1rem;
        color: #ffffff;
    }

    .card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }

    .table-hover tbody tr:hover {
        background-color: #f9f9f9;
    }

    .btn-outline-primary {
        border-color: #0d6efd;
        color: #0d6efd;
    }

    .btn-outline-primary:hover {
        background-color: #0d6efd;
        color: #fff;
    }

    .btn-outline-danger {
        border-color: #dc3545;
        color: #dc3545;
    }

    .btn-outline-danger:hover {
        background-color: #dc3545;
        color: #fff;
    }

    .btn-outline-info {
        border-color: #0dcaf0;
        color: #0dcaf0;
    }

    .btn-outline-info:hover {
        background-color: #0dcaf0;
        color: #fff;
    }

    @media(max-width:767px) {
        .dashboard-heading {
            font-size: 1.5rem;
        }

        .card-body {
            padding: 1rem;
        }

        .btn {
            font-size: 0.85rem;
        }
    }
</style>
@endsection
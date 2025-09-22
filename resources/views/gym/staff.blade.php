@extends('admin.layout')

@section('page-title','Staff Members')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <h2 class="dashboard-heading">Staff Members</h2>
        <button class="btn btn-primary" data-bs-toggle="collapse" data-bs-target="#addEditStaffForm">
            <i class="bi bi-person-plus-fill"></i> Add New Staff
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Add/Edit Form --}}
    <div class="collapse {{ isset($editStaff) ? 'show' : '' }}" id="addEditStaffForm">
        <div class="card p-4 mb-4 shadow-sm border-0 rounded-3">
            <form action="{{ isset($editStaff) ? route('gym.staff.update',$editStaff->id) : route('gym.staff.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Name *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $editStaff->name ?? '') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Type *</label>
                        <select name="type" class="form-select" required>
                            @foreach(['Personal Trainer','Nutritionist','Admin'] as $t)
                                <option value="{{ $t }}" {{ (isset($editStaff) && $editStaff->type==$t) ? 'selected' : '' }}>{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Mobile Number *</label>
                        <input type="tel" name="mobile_number" class="form-control" value="{{ old('mobile_number',$editStaff->mobile_number ?? '') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email',$editStaff->email ?? '') }}">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Address</label>
                        <input type="text" name="address" class="form-control" value="{{ old('address',$editStaff->address ?? '') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth',$editStaff->date_of_birth ?? '') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Active Status</label>
                        <select name="active_status" class="form-select">
                            <option value="Active" {{ (isset($editStaff) && $editStaff->active_status=='Active') ? 'selected' : '' }}>Active</option>
                            <option value="Inactive" {{ (isset($editStaff) && $editStaff->active_status=='Inactive') ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-success">{{ isset($editStaff) ? 'Update Staff' : 'Add Staff' }}</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Desktop Table --}}
    <div class="d-none d-md-block">
        <div class="card shadow-sm border-0 rounded-3 p-3">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Mobile</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Members</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($staff as $s)
                        <tr>
                            <td>{{ $s->name }}</td>
                            <td>{{ $s->type }}</td>
                            <td>{{ $s->mobile_number }}</td>
                            <td>{{ $s->email ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $s->active_status=='Active'?'bg-success':'bg-secondary' }}">{{ $s->active_status }}</span>
                            </td>
                            <td>{{ DB::table('members')->where('assigned_staff_id',$s->id)->count() }}</td>
                            <td>
                                <div class="d-flex gap-2 flex-wrap">
                                    <form action="{{ route('gym.staff.destroy',$s->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete staff?')">Delete</button>
                                    </form>

                                    <a href="{{ route('gym.staff.index',['edit_id'=>$s->id]) }}" class="btn btn-sm btn-outline-primary">Edit</a>

                                    <a href="#" class="btn btn-sm btn-outline-info" data-bs-toggle="collapse" data-bs-target="#assignedMembers{{ $s->id }}">
                                        View Members
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <tr class="collapse" id="assignedMembers{{ $s->id }}">
                            <td colspan="7">
                                <ul class="mb-0 mt-1">
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

    {{-- Mobile Cards --}}
    <div class="d-md-none">
        @foreach($staff as $s)
            <div class="card mb-3 shadow-sm border-0 rounded-3">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h5 class="card-title mb-1">{{ $s->name }}</h5>
                            <small class="text-muted">{{ $s->type }}</small>
                        </div>
                        <span class="badge {{ $s->active_status=='Active'?'bg-success':'bg-secondary' }}">{{ $s->active_status }}</span>
                    </div>
                    <p class="mb-1"><strong>Mobile:</strong> {{ $s->mobile_number }}</p>
                    <p class="mb-1"><strong>Email:</strong> {{ $s->email ?? '-' }}</p>
                    <p class="mb-1"><strong>Members:</strong> {{ DB::table('members')->where('assigned_staff_id',$s->id)->count() }}</p>

                    <div class="d-flex gap-2 flex-wrap mt-2">
                        <form action="{{ route('gym.staff.destroy',$s->id) }}" method="POST" style="flex:1;">
                            @csrf
                            <button class="btn btn-outline-danger w-100">Delete</button>
                        </form>
                        <a href="{{ route('gym.staff.index',['edit_id'=>$s->id]) }}" class="btn btn-outline-primary w-100">Edit</a>
                        <a href="#" class="btn btn-outline-info w-100" data-bs-toggle="collapse" data-bs-target="#assignedMembersMobile{{ $s->id }}">
                            Members
                        </a>
                    </div>

                    <div class="collapse mt-2" id="assignedMembersMobile{{ $s->id }}">
                        <ul class="mb-0 mt-1">
                            @foreach(DB::table('members')->where('assigned_staff_id',$s->id)->get() as $m)
                                <li>{{ $m->first_name }} {{ $m->last_name }} (ID: {{ $m->member_id }})</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection

@section('styles')
<style>
.dashboard-heading {
    font-weight:600;
    margin-bottom:1rem;
    color:#333;
}

.card {
    background:#fff;
    border-radius:12px;
    box-shadow:0 2px 6px rgba(0,0,0,0.05);
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

@media(max-width:767px){
    .dashboard-heading{font-size:1.5rem;}
    .card-body{padding:1rem;}
    .btn{font-size:0.85rem;}
}
</style>
@endsection

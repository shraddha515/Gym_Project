@extends('admin.layout')

@section('page-title', 'Profile Settings')

@section('content')
@php
    $user = Auth::user();
    $role = $user?->role;
@endphp

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10 col-sm-12">

            {{-- Super Admin Management Section --}}
            @if($user->role === 'superadmin')
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-header  text-white" style="background: linear-gradient(45deg, #3b82f6 0%, #a855f7 100%);">
                    <h5 class="mb-0">Manage Super Admins</h5>
                </div>
                <div class="card-body">
                    <button class="btn  text-white mb-3" data-bs-toggle="modal" data-bs-target="#addSuperAdminModal" style="background: linear-gradient(45deg, #3b82f6 0%, #a855f7 100%);">Add New Super Admin</button>

                    {{-- Existing Super Admins List --}}
                    <div class="row">
                        @forelse($superAdmins as $sa)
                        <div class="col-md-4 mb-3">
                            <div class="card shadow-sm p-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>{{ $sa->name }}</div>
                                    <form method="POST" action="{{ route('superadmin.deleteUser', $sa->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this super admin?')">Delete</button>
                                    </form>
                                </div>
                                <small class="text-muted">{{ $sa->email }}</small>
                            </div>
                        </div>
                        @empty
                        <div class="col-12"><p class="text-muted">No other super admins found.</p></div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Add Super Admin Modal --}}
            <div class="modal fade" id="addSuperAdminModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header text-white" style="background: linear-gradient(45deg, #3b82f6 0%, #a855f7 100%);">
                            <h5 class="modal-title">Add New Super Admin</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('superadmin.add') }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Confirm Password</label>
                                    <input type="password" name="password_confirmation" class="form-control" required>
                                </div>
                                <input type="hidden" name="role" value="superadmin">
                                <button type="submit" class="btn  w-100" style="background: linear-gradient(45deg, #3b82f6 0%, #a855f7 100%);">Add Super Admin</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Current User Profile Form --}}
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header  text-white text-center py-3 rounded-top-3" style="background: linear-gradient(45deg, #3b82f6 0%, #a855f7 100%);">
                    <h4 class="mb-0">Profile Settings</h4>
                </div>
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success rounded-3">{{ session('success') }}</div>
                    @endif
                    <form action="{{ route('gym.settings.update') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        </div>
                       @if($user && $role === 'owner')
    <div class="mb-3">
        <label class="form-label">Mobile</label>
        <input type="text" 
               name="mobile" 
               class="form-control" 
               value="{{ old('mobile', $user->mobile) }}" 
               maxlength="10" 
               pattern="[0-9]{10}" 
               oninput="this.value = this.value.replace(/[^0-9]/g, '')"
               required>
        
    </div>
@endif


                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" name="password" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>
                        <button type="submit" class="btn  text-white w-50" style="background: linear-gradient(45deg, #3b82f6 0%, #a855f7 100%);">Update Profile</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection


<!-- Custom Styles for Modern Look -->
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f4f6f9;
    }
    .card {
        background: #ffffff;
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    }
    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13,110,253,.25);
    }
    .btn-primary {
        background-color: #0d6efd;
        border-color: #0d6efd;
        transition: all 0.3s ease;
    }
    .btn-primary:hover {
        background-color: #0b5ed7;
        border-color: #0a58ca;
    }
    @media (max-width: 576px) {
        .card {
            margin: 10px;
        }
    }
    .form-label{
        color: black; 
    }
</style>


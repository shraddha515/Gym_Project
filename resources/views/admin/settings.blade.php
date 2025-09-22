@extends('admin.layout')

@section('page-title', 'Profile Settings')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8 col-sm-10">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header  text-white text-center py-3 rounded-top-3" style="background-color: #e1771b">
                    <h4 class="mb-0" style="font-family: 'Poppins', sans-serif;">Profile Settings</h4>
                </div>
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success rounded-3">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('gym.settings.update') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control form-control-md rounded-3 shadow-sm" value="{{ old('name', $user->name) }}" placeholder="Enter your name">
                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" class="form-control form-control-md rounded-3 shadow-sm" value="{{ old('email', $user->email) }}" placeholder="Enter your email">
                            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="mobile" class="form-label fw-semibold">Mobile</label>
                            <input type="text" name="mobile" id="mobile" class="form-control form-control-md rounded-3 shadow-sm" value="{{ old('mobile', $user->mobile) }}" placeholder="Enter mobile number">
                            @error('mobile') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">New Password</label>
                            <input type="password" name="password" id="password" class="form-control form-control-md rounded-3 shadow-sm" placeholder="Enter new password">
                            @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-semibold">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control form-control-md rounded-3 shadow-sm" placeholder="Confirm new password">
                        </div>

                        <button type="submit" class="btn btn-primary w-100 btn-md rounded-3 shadow-sm" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Update Profile</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

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
</style>
@endsection

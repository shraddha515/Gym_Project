@extends('admin.layout')

@section('page-title', 'Super Admin Dashboard')

@section('content')

    <div class="container-fluid py-4">
        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Add Gym Button Section --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            {{-- <h2 class="dashboard-heading">Super Admin Dashboard</h2> --}}
            <button class="btn btn-add-gym" data-bs-toggle="modal" data-bs-target="#addGymModal">
                <i class="bi bi-plus-circle me-2"></i> Add New Gym
            </button>
        </div>

        {{-- All Companies Table --}}
        <div class="card shadow-sm p-4">
            <h4 class="card-title mb-3">All Companies</h4>
            <div class="table-responsive">
                <table class="table table-hover table-borderless table-striped align-middle">
                    <thead>
                        <tr class="table-dark">
                            <th>ID</th>
                            <th>Company</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($gyms as $gym)
                            <tr>
                                <td>{{ $gym->id }}</td>
                                <td>{{ $gym->company_name ?? $gym->name ?? $gym->company }}</td>
                                <td>{{ $gym->email }}</td>
                                <td>{{ $gym->phone }}</td>
                               <td>{{ \Carbon\Carbon::parse($gym->created_at)->format('M d, Y') }}</td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No companies found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Add Gym Modal --}}
    <div class="modal fade" id="addGymModal" tabindex="-1" aria-labelledby="addGymModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addGymModalLabel">Add New Gym Company</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('superadmin.addCompany') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="company_name" class="form-label">Company Name</label>
                            <input type="text" name="company_name" id="company_name" class="form-control form-control-sm" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control form-control-sm" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control form-control-sm" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone (10 digits)</label>
                            <input type="text" name="phone" id="phone" class="form-control form-control-sm" maxlength="10" inputmode="numeric">
                        </div>
                        <div class="mb-4">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" name="address" id="address" class="form-control form-control-sm">
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Add Gym</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
<style>
    /* Custom Styles */
    .dashboard-heading {
        color: #333;
        font-weight: 600;
    }

    .btn-add-gym {
        background-image: linear-gradient(45deg, #007bff, #00c6ff);
        color: white;
        font-weight: 600;
        border: none;
        padding: 10px 25px;
        border-radius: 50px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(0, 123, 255, 0.3);
    }
    .btn-add-gym:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0, 123, 255, 0.4);
        color: white;
    }

    .card {
        border: none;
        border-radius: 12px;
    }

    .card-title {
        font-weight: 600;
        color: #444;
    }

    .table-responsive {
        border-radius: 8px;
        overflow: hidden;
    }

    .table-hover tbody tr:hover {
        background-color: #f1f5f9;
        transform: scale(1.01);
        transition: transform 0.2s ease-in-out;
    }

    .table th, .table td {
        white-space: nowrap;
        vertical-align: middle;
        padding: 1rem;
    }

    .table thead th {
        background-color: #333;
        color: white;
        border-color: #444;
        font-weight: 500;
    }
    
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #f8f9fa;
    }
    .table-striped tbody tr:nth-of-type(even) {
        background-color: #ffffff;
    }

    .modal-content {
        border-radius: 12px;
        border: none;
    }
    .modal-header {
        border-bottom: none;
        padding-bottom: 0;
    }
    .modal-body {
        padding-top: 0;
    }

    .form-control {
        border-radius: 8px;
        padding: 10px 15px;
        font-size: 0.9rem;
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
    }
    .form-control:focus {
        box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.15);
        border-color: #80bdff;
    }
    
    .btn-primary {
        background-image: linear-gradient(45deg, #007bff, #00c6ff);
        border: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-primary:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }
</style>
@endsection
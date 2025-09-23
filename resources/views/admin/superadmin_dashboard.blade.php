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
        <button class="btn btn-add-gym" data-bs-toggle="modal" data-bs-target="#addGymModal">
            <i class="bi bi-plus-circle me-2"></i> Add New Gym
        </button>
    </div>

    {{-- All Companies Table --}}
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
                    </tr>
                </thead>
                <tbody>
                    @forelse($gyms as $gym)
                        <tr>
                            <td>{{ $gym->gym_id }}</td>
                            <td>{{ $gym->company_name ?? $gym->name ?? $gym->company }}</td>
                            <td>{{ $gym->email }}</td>
                            <td>{{ $gym->phone }}</td>
                            <td>{{ \Carbon\Carbon::parse($gym->created_at)->format('d M, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No companies found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile View Cards --}}
        <div class="d-md-none">
            @forelse($gyms as $gym)
                <div class="card mb-3 gym-card-mobile shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="card-title-mobile mb-1">{{ $gym->company_name ?? $gym->name ?? $gym->company }}</h6>
                                <p class="card-subtitle-mobile text-muted mb-2">ID: {{ $gym->gym_id }}</p>
                            </div>
                            <span class="badge bg-primary">{{ \Carbon\Carbon::parse($gym->created_at)->format('M d, Y') }}</span>
                        </div>
                        <ul class="list-unstyled mb-0 mt-3">
                            <li class="d-flex align-items-center mb-1"><i class="bi bi-envelope me-2"></i>{{ $gym->email }}</li>
                            <li class="d-flex align-items-center"><i class="bi bi-phone me-2"></i>{{ $gym->phone }}</li>
                        </ul>
                    </div>
                </div>
            @empty
                <div class="alert alert-info text-center">No companies found.</div>
            @endforelse
        </div>
    </div>

</div>

{{-- Add Gym Modal --}}
<div class="modal fade" id="addGymModal" tabindex="-1" aria-labelledby="addGymModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content professional-modal">
            <div class="modal-header modal-header-gradient">
                <h5 class="modal-title text-white" id="addGymModalLabel">Add New Gym Company</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form method="POST" action="{{ route('superadmin.addCompany') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="company_name" class="form-label professional-label">Company Name</label>
                        <input type="text" name="company_name" id="company_name" class="form-control form-control-sm professional-input" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label professional-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control form-control-sm professional-input" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label professional-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control form-control-sm professional-input" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label professional-label">Phone (10 digits)</label>
                        <input type="text" name="phone" id="phone" class="form-control form-control-sm professional-input" maxlength="10" inputmode="numeric">
                    </div>
                    <div class="mb-4">
                        <label for="address" class="form-label professional-label">Address</label>
                        <input type="text" name="address" id="address" class="form-control form-control-sm professional-input">
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary professional-btn">Add Gym</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
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
        background-image: linear-gradient(45deg, #3b82f6 0%, #a855f7 100%);
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
        background: linear-gradient(45deg, #3b82f6 0%, #a855f7 100%);
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
        background: linear-gradient(45deg, #3b82f6 0%, #a855f7 100%);
        color: white;
    }
    .table-professional th, .table-professional td {
        padding: 12px 1rem;
        vertical-align: middle;
        font-size: 1rem; /* Reduced font size for a cleaner look */
        font-weight: normal; /* Ensures simple font, not bold */
    }
    .table-professional th {
        font-weight: 500; /* Slightly bolder for headers to distinguish them from content */
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
        background-image: linear-gradient(45deg, #3b82f6 0%, #a855f7 100%);
        color: white;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        border-bottom: none;
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
        background-image: linear-gradient(45deg, #3b82f6 0%, #a855f7 100%);
        border: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .professional-btn:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }
</style>
@endsection
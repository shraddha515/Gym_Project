@extends('admin.layout')

@section('page-title', 'Members List')

@section('styles')
<style>
    /* Typography */
    body, .dashboard-heading {
        font-family: 'Poppins', sans-serif;
        color: #f0f4fa;
    }
    h2.dashboard-heading {
        font-weight: 600;
        font-size: 1.5rem;
    }

    /* Table Styles */
    table.table thead {
        background: linear-gradient(90deg, #3b82f6, #a855f7);
        color: #fff;
        font-weight: 500;
        font-size: 0.95rem;
    }
    table.table tbody tr:hover {
        background-color: #f3f4f6;
    }
    table.table td, table.table th {
        vertical-align: middle;
        font-size: 0.92rem;
    }

    /* Buttons */
    .btn-outline-info, .btn-outline-warning, .btn-outline-danger {
        border-width: 1.5px;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    .btn-outline-info:hover { background-color: #3b82f6; color: #fff; border-color: #3b82f6; }
    .btn-outline-warning:hover { background-color: #f97316; color: #fff; border-color: #f97316; }
    .btn-outline-danger:hover { background-color: #ef4444; color: #fff; border-color: #ef4444; }

    /* Cards */
    .card.shadow-sm {
        border-radius: 12px;
        border: none;
        box-shadow: 0 6px 15px rgba(0,0,0,0.08);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .card.shadow-sm:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 25px rgba(0,0,0,0.12);
    }
    .card-body ul.list-group-item {
        border: none;
        padding-left: 0;
        padding-right: 0;
        font-size: 0.9rem;
    }

    .card-body ul.list-group-item span:first-child {
        font-weight: 500;
        color: #374151;
    }

    /* Mobile Card Buttons */
    .card-body .btn {
        font-size: 0.85rem;
        padding: 5px 10px;
        border-radius: 8px;
    }

    /* Search + Export */
    .form-control {
        border-radius: 8px;
    }
    .btn-outline-primary, .btn-outline-secondary {
        border-radius: 8px;
        font-size: 0.85rem;
    }

    /* Table Photo */
    table.table img.rounded-circle {
        object-fit: cover;
        border-radius: 50%;
    }

    /* Status Badge */
    .badge-success {
        background-color: #10b981 !important;
        font-size: 0.8rem;
    }
    .badge-secondary {
        background-color: #6b7280 !important;
        font-size: 0.8rem;
    }

    /* Responsive tweaks */
    @media (max-width: 768px) {
        .card-body ul.list-group-item span:first-child {
            font-size: 0.85rem;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="dashboard-heading">Members List</h2>
        <a href="{{ route('gym.members.create') }}" class="btn btn-success  shadow-sm">
            <i class="bi bi-plus-circle me-2"></i> Add 
        </a>
    </div>

    {{-- Alerts --}}
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

    <div class="card shadow-sm p-4">

        {{-- Search + Export --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-3">
            <form id="searchForm" action="{{ route('gym.members.index') }}" method="GET"
                class="d-flex flex-grow-1 me-md-3 mb-2 mb-md-0">
                <input type="text" id="searchInput" name="search" class="form-control"
                    placeholder="Search by name, ID, mobile, or PT..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-outline-primary ms-2"><i class="bi bi-search"></i></button>
                @if (request('search'))
                    <a href="{{ route('gym.members.index') }}" class="btn btn-outline-secondary ms-2"><i class="bi bi-x-circle"></i></a>
                @endif
            </form>

            <div class="dropdown">
                <button class="btn btn-outline-dark btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-download me-1"></i> Export
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('gym.members.export.csv') }}"><i class="bi bi-file-earmark-spreadsheet me-2 text-success"></i>CSV</a></li>
                    <li><a class="dropdown-item" href="{{ route('gym.members.export.pdf') }}"><i class="bi bi-file-earmark-pdf me-2 text-danger"></i>PDF</a></li>
                </ul>
            </div>
        </div>

        {{-- Desktop Table --}}
        <div class="d-none d-md-block">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Photo</th>
                            <th>Member ID</th>
                            <th>Name</th>
                            <th>Assigned PT</th>
                            <th>Joining Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($members as $member)
                        <tr>
                            <td>
                                @if ($member->photo_path)
                                    <img src="{{ url('public/storage/' . $member->photo_path) }}" class="rounded-circle" width="50" height="50">
                                @else
                                    <div class="rounded-circle bg-gray-200 d-flex justify-content-center align-items-center" style="width:50px;height:50px;">
                                        <i class="bi bi-person-circle fs-4 text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>{{ $member->member_id }}</td>
                            <td>{{ $member->first_name }} {{ $member->last_name }}</td>
                            <td>{{ $member->pt_name ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($member->created_at)->format('M d, Y') }}</td>
                            <td>
                                <span class="badge bg-{{ ($member->status ?? 'Inactive') == 'Active' ? 'success' : 'secondary' }}">
                                    {{ $member->status ?? 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('gym.members.show', $member->id) }}" class="btn btn-outline-info btn-sm"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('gym.members.edit', $member->id) }}" class="btn btn-outline-warning btn-sm"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('gym.members.destroy', $member->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No members found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Mobile Card View --}}
<div class="d-md-none">
    @forelse($members as $member)
        <div class="card mb-2 shadow-sm border-0">
            <div class="card-header d-flex align-items-center justify-content-between p-2" 
                 style="background: #f3f4f6; border-radius:12px; cursor:pointer;" 
                 data-bs-toggle="collapse" data-bs-target="#memberCard{{ $member->id }}" aria-expanded="false">
                
                <div class="d-flex align-items-center">
                    @if ($member->photo_path)
                        <img src="{{ url('public/storage/' . $member->photo_path) }}" class="rounded-circle me-2" width="40" height="40">
                    @else
                        <div class="rounded-circle bg-gray-300 d-flex justify-content-center align-items-center me-2" style="width:40px;height:40px;">
                            <i class="bi bi-person-circle fs-5 text-muted"></i>
                        </div>
                    @endif
                    <span class="fw-semibold text-dark" style="font-size:0.9rem;">{{ $member->first_name }} {{ $member->last_name }}</span>
                </div>
                <i class="bi bi-chevron-down text-muted"></i>
            </div>

            <div class="collapse" id="memberCard{{ $member->id }}" data-bs-parent="#mobileMembersAccordion">
                <div class="card-body p-2">
                    <ul class="list-group list-group-flush mb-2" style="font-size:0.85rem;">
                        <li class="list-group-item d-flex justify-content-between py-1 border-0">
                            <span>Member ID:</span>
                            <span class="text-muted">{{ $member->member_id }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between py-1 border-0">
                            <span>Assigned PT:</span>
                            <span class="text-muted">{{ $member->pt_name ?? '-' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between py-1 border-0">
                            <span>Joining Date:</span>
                            <span class="text-muted">{{ \Carbon\Carbon::parse($member->created_at)->format('M d, Y') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between py-1 border-0">
                            <span>Status:</span>
                            <span class="badge bg-{{ $member->status == 'Active' ? 'success' : 'secondary' }}" style="font-size:0.75rem;">
                                {{ $member->status }}
                            </span>
                        </li>
                    </ul>
                    <div class="d-flex justify-content-end gap-1 flex-wrap">
                        <a href="{{ route('gym.members.show', $member->id) }}" class="btn btn-primary btn-sm" style="font-size:0.75rem; padding:3px 8px;"><i class="bi bi-eye me-1"></i>View</a>
                        <a href="{{ route('gym.members.edit', $member->id) }}" class="btn btn-primary btn-sm" style="font-size:0.75rem; padding:3px 8px;"><i class="bi bi-pencil me-1"></i>Edit</a>
                        <form action="{{ route('gym.members.destroy', $member->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-primary btn-sm" style="font-size:0.75rem; padding:3px 8px;"><i class="bi bi-trash me-1"></i>Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center text-muted py-4">No members found.</div>
    @endforelse
</div>

{{-- Add accordion wrapper for parent collapse control --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.card-header[data-bs-toggle="collapse"]');
        cards.forEach(card => {
            card.addEventListener('click', function() {
                // Collapse all other cards
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

    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const searchForm = document.getElementById('searchForm');
        let timeout = null;
        searchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => searchForm.submit(), 500);
        });
    });
</script>
@endpush

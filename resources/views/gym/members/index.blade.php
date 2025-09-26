@extends('admin.layout')

@section('page-title', 'Members List')

@section('styles')
    <style>
        /* Typography */
        body,
        .dashboard-heading {
            font-family: 'Poppins', sans-serif;
            color: #f0f4fa;
        }

        h2.dashboard-heading {
            font-weight: 600;
            font-size: 1.5rem;
            color: #f0f4fa;
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

        table.table td,
        table.table th {
            vertical-align: middle;
            font-size: 0.92rem;
        }

        /* Buttons */
        .btn-outline-info,
        .btn-outline-warning,
        .btn-outline-danger {
            border-width: 1.5px;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn-outline-info:hover {
            background-color: #3b82f6;
            color: #fff;
            border-color: #3b82f6;
        }

        .btn-outline-warning:hover {
            background-color: #f97316;
            color: #fff;
            border-color: #f97316;
        }

        .btn-outline-danger:hover {
            background-color: #ef4444;
            color: #fff;
            border-color: #ef4444;
        }

        /* Cards */
        .card.shadow-sm {
            border-radius: 12px;
            border: none;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card.shadow-sm:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.12);
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

        .btn-outline-primary,
        .btn-outline-secondary {
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

        .member-card {
            border: 2px solid #e2e8f0;
            /* lighter professional border */
            border-radius: 6px;
            background: #ffffff;
            transition: all 0.2s ease-in-out;
        }

        .member-card:hover {
            border-color: #3b82f6;
            /* theme highlight on hover */
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

        .member-fields .list-group-item:active,
        .member-fields .list-group-item:focus {
            background: #f0f9ff;
            /* highlight selected field */
            color: #111827;
        }

        .btn-action {
            font-size: 0.75rem;
            padding: 4px 4px !important;
            border-radius: 4px;
            background: linear-gradient(45deg, #3b82f6, #a855f7);
            color: #fff;
            border: none;
            flex: 1;
            /* buttons fit in one row */
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
    </style>
@endsection

@section('content')
    <div class="container-fluid py-4">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="dashboard-heading">Members List</h2>
            <a href="{{ route('gym.members.create') }}" class="btn   shadow-sm"
                style="background: linear-gradient(45deg, #3b82f6 0%, #a855f7 100%);">
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

        <div class="card shadow-sm p-3" style="background: var(--card-bg); border-radius:8px;">

            {{-- Search + Export --}}
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-3 gap-2">
                
                <div class="dropdown">
                    <button class="btn btn-outline-dark btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        style="font-size:0.85rem; padding:0.45rem 0.75rem;">
                        <i class="bi bi-download me-1"></i> Export
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('gym.members.export.csv') }}">
                                <i class="bi bi-file-earmark-spreadsheet me-2 text-success"></i>CSV
                            </a></li>
                        <li><a class="dropdown-item" href="{{ route('gym.members.export.pdf') }}">
                                <i class="bi bi-file-earmark-pdf me-2 text-danger"></i>PDF
                            </a></li>
                    </ul>
                </div>
            </div>

            {{-- Desktop Table --}}
            <div class="d-none d-md-block">
                <div class="table-responsive">
                    <!-- Include DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<!-- Include jQuery and DataTables JS -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<table id="membersTable" class="table align-middle table-hover" style="border-collapse: separate; border-spacing:0 6px;">
    <thead style="background: var(--accent-gradient); color:#fff; font-weight:500; font-size:0.9rem;">
        <tr>
            <th>Photo</th>
            <th>Member ID</th>
            <th>Name</th>
            <th>Aadhar no</th>
            <th>Joining Date</th>
            <th>Fees Paid</th>
            <th>Fees Due</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($members as $member)
            <tr style="background:#f8fafc; border-radius:6px;">
                <td>
                    @if ($member->photo_path)
                        <img src="{{ url('public/storage/' . $member->photo_path) }}"
                            class="rounded-circle" width="45" height="45"
                            style="object-fit:cover;">
                    @else
                        <div class="rounded-circle bg-gray-300 d-flex justify-content-center align-items-center"
                            style="width:45px;height:45px;">
                            <i class="bi bi-person-circle text-muted" style="font-size:1.2rem;"></i>
                        </div>
                    @endif
                </td>
                <td>{{ $member->member_id }}</td>
                <td>{{ $member->first_name }} {{ $member->last_name }}</td>
                <td>{{ $member->aadhar_no ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($member->created_at)->format('d M, Y') }}</td>
                <td>{{ $member->fees_paid ?? '0' }}</td>
                <td>{{ $member->fees_due ?? '0' }}</td>
                <td>
                    <span class="badge status-badge {{ $member->status == 'Active' ? 'bg-success' : 'bg-secondary' }}">
                        {{ $member->status ?? 'Inactive' }}
                    </span>
                </td>
                <td>
                    <div class="d-flex gap-1 flex-wrap">
                        <a href="{{ route('gym.members.show', $member->id) }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-eye"></i></a>
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
                <td colspan="9" class="text-center text-muted py-4">No members found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<script>
    $(document).ready(function() {
        $('#membersTable').DataTable({
            "paging": true,
            "searching": true,  // ✅ built-in search/filter
            "ordering": true,
            "info": true
        });
    });
</script>

                </div>
            </div>

            {{-- Mobile Cards --}}
            <div class="d-md-none" id="mobileMembersAccordion">
                @forelse($members as $member)
                    <div class="card mb-3 member-card">
                        <div class="card-header d-flex justify-content-between align-items-center p-2"
                            data-bs-toggle="collapse" data-bs-target="#memberCard{{ $member->id }}"
                            style="cursor:pointer;">
                            <div class="d-flex align-items-center gap-2">
                                @if ($member->photo_path)
                                    <img src="{{ url('public/storage/' . $member->photo_path) }}" class="rounded-circle"
                                        width="40" height="40" style="object-fit:cover;">
                                @else
                                    <div class="rounded-circle bg-light d-flex justify-content-center align-items-center"
                                        style="width:40px;height:40px;">
                                        <i class="bi bi-person text-muted fs-5"></i>
                                    </div>
                                @endif
                                <span class="member-name">{{ $member->first_name }} {{ $member->last_name }}</span>
                            </div>
                            <i class="bi bi-chevron-down text-muted"></i>
                        </div>

                        <div class="collapse" id="memberCard{{ $member->id }}"
                            data-bs-parent="#mobileMembersAccordion">
                            <div class="card-body p-2">
                                <ul class="list-group list-group-flush member-fields">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>Member ID</span>
                                        <span>{{ $member->member_id }}</span>
                                    </li>
                                    {{-- <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>Assigned PT</span>
                                        <span>{{ $member->pt_name ?? '-' }}</span>
                                    </li> --}}
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>Aadhar no</span>
                                        <span>{{ $member->aadhar_no ?? '-' }}</span>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>Joining Date</span>
                                        <span>{{ \Carbon\Carbon::parse($member->created_at)->format('d M, Y') }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>Fees Paid</span>
                                        <span>{{ $member->fees_paid ?? '0' }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>Fees Due</span>
                                        <span>{{ $member->fees_due ?? '0' }}</span>
                                    </li>

                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>Status</span>
                                        <span
                                            class="badge {{ $member->status == 'Active' ? 'bg-success' : 'bg-secondary' }} status-badge"
                                            data-member-id="{{ $member->id }}">
                                            {{ $member->status ?? 'Inactive' }}
                                        </span>
                                    </li>
                                </ul>

                                <div class="d-flex justify-content-between gap-1">
                                    <a href="{{ route('gym.members.show', $member->id) }}" class="btn btn-action">
                                        <i class="bi bi-eye me-1"></i> View
                                    </a>
                                    <a href="{{ route('gym.members.edit', $member->id) }}" class="btn btn-action">
                                        <i class="bi bi-pencil me-1"></i> Edit
                                    </a>
                                    <form action="{{ route('gym.members.destroy', $member->id) }}" method="POST"
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
                    <div class="text-center text-muted py-4">No members found.</div>
                @endforelse
            </div>

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

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const badges = document.querySelectorAll('.status-badge');

            badges.forEach(badge => {
                badge.addEventListener('click', function() {
                    const memberId = this.dataset.memberId;
                    const currentBadge = this;
                    const currentStatus = this.textContent.trim();

                    if (confirm(`Are you sure you want to change status from ${currentStatus}?`)) {
                        fetch(`/gym/members/${memberId}/toggle-status`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json',
                                },
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.status === 'success') {
                                    currentBadge.textContent = data.new_status;
                                    currentBadge.classList.toggle('bg-success');
                                    currentBadge.classList.toggle('bg-secondary');
                                } else {
                                    alert(data.message || 'Something went wrong!');
                                }
                            })
                            .catch(() => alert('Something went wrong!'));
                    }
                });
            });
        });
    </script>
@endpush

@push('scripts')
   
@endpush


@endsection



@extends('admin.layout')

@section('content')
    <style>
        /* --- General Card Styles --- */
        .report-card {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border: none;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }

        .report-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }

        /* Labels and Values */
        .report-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #6c757d;
            text-transform: uppercase;
            margin-bottom: 10px;
            letter-spacing: 0.5px;
        }

        .report-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 10px;
        }

        /* List inside cards */
        .report-list {
            list-style: none;
            padding-left: 0;
            margin: 0;
            max-height: 120px;
            /* Fixed height for list */
            overflow-y: auto;
        }

        /* Hide scrollbar */
        .report-list::-webkit-scrollbar {
            width: 0px;
            background: transparent;
            /* optional: just to be safe */
        }

        .report-list li {
            font-size: 0.85rem;
            color: #495057;
            margin-bottom: 4px;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .report-card {
                padding: 15px;
            }

            .report-value {
                font-size: 1.3rem;
            }

            .report-label {
                font-size: 0.8rem;
            }

            .report-list li {
                font-size: 0.8rem;
            }
        }

        .report-card ul {
            max-height: 100px;
            overflow-y: auto;
            padding-left: 15px;
        }

        /* Mobile Card Styling - Formal & Minimal */
        .member-card {
            background: var(--card-bg);
            border: 2px solid #8f2613;
            border-radius: 3px;
            box-shadow: 0 1px 4px rgb(0 0 0 / 59%);
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .member-card:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .member-name {
            font-size: 0.88rem;
            /* smaller name */
            font-weight: 500;
            color: var(--text-dark);
            text-align: left;
        }

        .member-card ul.member-info {
            font-size: 0.82rem;
            color: var(--text-dark);
            margin-bottom: 0;
            padding-left: 0;
            text-align: left;
        }

        .member-card ul.member-info li {
            margin-bottom: 3px;
            font-weight: 400;
        }

        .badge-expired {
            background-color: #ef4444;
            /* red */
            color: #fff;
            font-size: 0.7rem;
            padding: 2px 6px;
        }

        .badge-expiring {
            background-color: #3b82f6;
            /* blue accent */
            color: #fff;
            font-size: 0.7rem;
            padding: 2px 6px;
        }

        .btn-renew {
            background-color: #3b82f6;
            /* blue accent */
            border: 1px solid #3b82f6;
            font-size: 0.82rem;
            font-weight: 500;
            border-radius: 4px;
            color: #fff;
            transition: 0.2s ease;
        }

        .btn-renew:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        /* Custom Themed Table */
        .custom-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 6px;
            font-size: 0.9rem;
            color: var(--text-dark);
        }

        .custom-table th,
        .custom-table td {
            vertical-align: middle;
            padding: 0.65rem 0.75rem;
        }

        .custom-table-header {
            background: var(--topbar-gradient);
            color: #fff;
            font-weight: 500;
            border-radius: 8px 8px 0 0;
        }

        .custom-table tbody tr {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            transition: background 0.2s ease, transform 0.2s ease;
        }

        .custom-table tbody tr:hover {
            background: rgba(255, 255, 255, 0.12);
            transform: translateY(-1px);
        }

        .custom-table td {
            font-weight: 400;
            color: #090909;
        }

        .text-expired {
            color: #f87171;
            /* Red-ish for expired */
            font-weight: 500;
        }

        .text-expiring {
            color: #facc15;
            /* Yellow-ish for expiring */
            font-weight: 500;
        }

        .btn-renew {
            background: var(--accent-gradient);
            border: none;
            font-size: 0.8rem;
            padding: 3px 8px;
            border-radius: 5px;
            color: #fff;
            transition: 0.2s ease;
        }

        .btn-renew:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }
    </style>

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

    <div class="container-fluid py-4">

        <div class="row mb-4 g-3">
            <!-- Card 1: Active Members -->
            <div class="col-12 col-md-3">
                <div class="report-card shadow-sm">
                    <h6 class="report-label">Active Members</h6>
                    <h4 class="report-value">{{ $activeMembersCount }}</h4>
                    <ul class="report-list">
                        @foreach ($activeMembers as $am)
                            <li>{{ $am->first_name }} {{ $am->last_name }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Card 2: Staff -->
            <div class="col-12 col-md-3">
                <div class="report-card shadow-sm">
                    <h6 class="report-label">Staff</h6>
                    <h4 class="report-value">{{ $staffCount }}</h4>
                    <ul class="report-list">
                        @foreach ($staff as $s)
                            <li>{{ $s->name }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Card 3: Net Amount -->
            <div class="col-12 col-md-3">
                <div class="report-card shadow-sm">
                    <h6 class="report-label">Net Amount</h6>
                    <h4 class="report-value">₹{{ number_format($netAmount, 2) }}</h4>
                </div>
            </div>

            <!-- Card 4: Recently Joined Members -->
            <div class="col-12 col-md-3">
                <div class="report-card shadow-sm">
                    <h6 class="report-label">Recently Joined</h6>
                    <ul class="report-list">
                        @foreach ($recentMembers as $rm)
                            <li>{{ $rm->first_name }} {{ $rm->last_name }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>





        <h3 class="mb-3">
            @if ($filter === 'expiring')
                Members Expiring Today
            @else
                Expired Members
            @endif
        </h3>

        {{-- Filter & Search Form --}}
        <form id="memberSearchForm" action="{{ route('gym.members.filter') }}" method="GET"
            class="row g-2 align-items-center mb-3 justify-content-center">
            <div class="col-lg-3 col-md-4 col-sm-6">
                <select name="filter" class="form-select" onchange="this.form.submit()">
                    <option value="expiring" {{ $filter === 'expiring' ? 'selected' : '' }}>Expiring Today</option>
                    <option value="expired" {{ $filter === 'expired' ? 'selected' : '' }}>Expired Members</option>
                </select>
            </div>
            <div class="col-lg-4 col-md-5 col-sm-6">
                <input type="text" name="search" id="search" value="{{ request('search') }}" class="form-control"
                    placeholder="Search by Name or Mobile">
            </div>
        </form>





        <div class="table-responsive d-none d-md-block">
    <table class="table align-middle shadow-sm custom-table">
        <thead>
            <tr class="custom-table-header">
                <th>Member ID</th>
                <th>Name</th>
                <th>Mobile</th>
                <th>Aadhar no</th>
                <th>Join Date</th>
                <th>Expiry Date</th>
                <th>Package</th>
                <th>Fees Paid</th>
                <th>Fees Due</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($members as $member)
                <tr>
                    <td>{{ $member->member_id }}</td>
                    <td>{{ $member->first_name }} {{ $member->last_name }}</td>
                    <td>{{ $member->mobile_number }}</td>
                    <td>{{ $member->aadhar_no ?? '-' }}</td>
                    <td>{{ $member->membership_valid_from }}</td>
                    <td class="{{ $filter === 'expired' ? 'text-expired' : 'text-expiring' }}">
                        {{ $member->membership_valid_to }}
                    </td>
                    <td>{{ $member->membership_type }}</td>
                    <td>{{ $member->fees_paid ?? '-' }}</td>
                    <td>{{ $member->fees_due ?? '-' }}</td>
                    <td class="text-center">
                        <button type="button" 
                                class="btn btn-renew btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#renewModal"
                                data-id="{{ $member->id }}"
                                data-name="{{ $member->first_name }} {{ $member->last_name }}"
                                data-package="{{ $member->membership_type }}"
                                data-expiry="{{ $member->membership_valid_to }}">
                            Renew
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center text-muted">No members found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Renew Modal -->
<div class="modal fade" id="renewModal" tabindex="-1" aria-labelledby="renewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
  <form method="POST" id="renewForm" action="{{ route('members.renew', 0) }}">
    @csrf
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title text-black" id="renewModalLabel">Renew Membership</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <input type="hidden" name="member_id" id="renewMemberId">

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label text-black">Member Name</label>
                    <input type="text" class="form-control" id="renewMemberName" name="member_name" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-black">Current Package</label>
                    <input type="text" class="form-control" id="renewMemberPackage" name="membership_type" readonly>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label text-black">Current Expiry Date</label>
                    <input type="text" class="form-control" id="renewCurrentExpiry" name="current_expiry" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-black">New Expiry Date</label>
                    <input type="date" class="form-control" name="new_expiry_date" required>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Confirm Renew</button>
        </div>
    </div>
    </form>

  </div>
</div>
<script>
    $(document).ready(function () {
        $('#renewModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);

            var memberId = button.data('id');
            var memberName = button.data('name');
            var memberPackage = button.data('package');
            var memberExpiry = button.data('expiry');

            $('#renewMemberId').val(memberId);
            $('#renewMemberName').val(memberName);
            $('#renewMemberPackage').val(memberPackage);
            $('#renewCurrentExpiry').val(memberExpiry);

            // ✅ Correct: use Laravel's url() so folder name is included
            $('#renewForm').attr('action', "{{ url('members') }}/" + memberId + "/renew");
        });
    });
</script>







        {{-- Mobile Cards --}}
        <div class="d-md-none">
            @forelse($members as $member)
                <div class="card mb-3 member-card">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0 member-name">
                                {{ $member->first_name }} {{ $member->last_name }}
                            </h6>
                            @if ($filter === 'expired')
                                <span class="badge badge-expired small">Expired</span>
                            @else
                                <span class="badge badge-expiring small">Expiring</span>
                            @endif
                        </div>
                        <ul class="list-unstyled mb-0 member-info">
                            <li><strong>ID:</strong> {{ $member->member_id }}</li>
                            <li><strong>Mobile:</strong> {{ $member->mobile_number }}</li>
                            <li><strong>Aadhar:</strong> {{ $member->aadhar_no ?? '-' }}</li>
                            <li><strong>Join:</strong> {{ $member->membership_valid_from }}</li>
                            <li><strong>Expiry:</strong> {{ $member->membership_valid_to }}</li>
                            <li><strong>Package:</strong> {{ $member->package_name ?? $member->membership_type }}</li>
                            <li><strong>Total:</strong> {{ $member->total ?? '-' }}</li>
                            <li><strong>Deposit:</strong> {{ $member->deposit ?? '-' }}</li>
                        </ul>
                        @if (true)
                            <!-- always show -->
                            <form action="{{ route('members.renew', $member->id) }}" method="POST" class="mt-2">
                                @csrf
                                <button type="submit" class="btn btn-renew w-100">Renew</button>
                            </form>
                        @endif

                    </div>
                </div>
            @empty
                <div class="text-center text-muted">No members found.</div>
            @endforelse
        </div>



    </div>
    
    <script>
        const searchInput = document.getElementById('search');
        const searchForm = document.getElementById('memberSearchForm');

        // Debounce function to reduce frequent requests
        function debounce(func, delay) {
            let timer;
            return function() {
                clearTimeout(timer);
                timer = setTimeout(func, delay);
            }
        }

        searchInput.addEventListener('input', debounce(function() {
            searchForm.submit();
        }, 500)); // 500ms delay
    </script>
@endsection

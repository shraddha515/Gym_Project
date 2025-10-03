@extends('admin.layout')

@section('content')
    <style>
        /* Labels and Values */
        .report-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #000000;
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



        /* Default for large screens (4 cards per row) */
        .col-12.col-md-3 {
            flex: 1 1 calc(25% - 20px);
            display: flex;
        }

        /* Small screens: 2 cards per row */
        @media (max-width: 768px) {
            .col-12.col-md-3 {
                flex: 1 1 calc(50% - 10px);
                /* 2 cards per row with gap */
            }

            .row.mb-4.g-3 {
                gap: 10px;
                /* reduce gap on small screens */
            }

            .report-card {
                min-height: 140px;
                /* reduce card height for small screens */
                padding: 12px 15px;
            }

            .report-value {
                font-size: 1.5rem;
            }

            .report-label {
                font-size: 0.75rem;
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


        .custom-table-header th {
            background: #023762;
            color: #fff;
            padding: 12px;
            text-align: center;
            border: none;
            /* remove table default border if needed */
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

        /* --- Professional Dashboard Cards --- */
        .report-card {
            background: #ffffff;
            /* Clean white background */
            border-radius: 12px;
            padding: 20px 25px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            /* subtle shadow */
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 156px;
            width: 95%;
            /* full width of column */
        }

        /* Hover effect */
        .report-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.12);
        }

        /* Labels */
        .report-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #050505;
            /* muted gray for professional look */
            text-transform: uppercase;
            margin-bottom: 15px;
            letter-spacing: 0.5px;
        }

        /* Values / Numbers */
        .report-value {
            font-size: 2.2rem;
            /* slightly bigger for emphasis */
            font-weight: 700;
            color: #111827;
            /* dark for contrast */
            margin-bottom: 15px;
            text-align: center;
        }

        /* List inside cards */
        .report-list {
            list-style: none;
            padding-left: 0;
            margin: 0;
            max-height: 100px;
            overflow-y: auto;
        }

        .report-list::-webkit-scrollbar {
            width: 4px;
        }

        .report-list::-webkit-scrollbar-thumb {
            background-color: rgba(107, 114, 128, 0.3);
            border-radius: 8px;
        }

        .report-list li {
            font-size: 0.9rem;
            color: #374151;
            margin-bottom: 5px;
        }

        /* Gradient glow border (subtle) */
        .report-card::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 12px;
            padding: 4px;
            /* border thickness */
            background: linear-gradient(47deg, #041f4b, #4d0096, #01586d, #002054);

            background-size: 400% 400%;
            -webkit-mask:
                linear-gradient(#fff 0 0) content-box,
                linear-gradient(#fff 0 0);
            -webkit-mask-composite: destination-out;
            mask-composite: exclude;
            animation: gradientMove 6s linear infinite;
            opacity: 0.5;
        }

        /* Gradient animation */
        @keyframes gradientMove {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .report-card {
            position: relative;
            z-index: 1;
        }

        /* Column uniformity */
        .row.mb-4.g-3 {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
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
                <a href="{{ route('gym.members.index') }}" class="text-decoration-none">
                    <div class="report-card shadow-sm">

                        <h6 class="report-label">Active Members</h6>
                        <h4 class="report-value">{{ $activeMembersCount }}</h4>
                        {{-- <ul class="report-list">
                    @foreach ($activeMembers as $am)
                    <li>{{ $am->first_name }} {{ $am->last_name }}</li>
                    @endforeach
                </ul> --}}
                </a>
            </div>

        </div>

        <!-- Card 2: Staff -->
        <div class="col-12 col-md-3">
            <a href="{{ route('gym.staff.index') }}" class="text-decoration-none">
                <div class="report-card shadow-sm">
                    <h6 class="report-label">Staff</h6>
                    <h4 class="report-value">{{ $staffCount }}</h4>
                    {{-- <ul class="report-list">
                    @foreach ($staff as $s)
                    <li>{{ $s->name }}</li>
                    @endforeach
                </ul> --}}
            </a>
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
            <h6 class="report-label">Expired Members</h6>
            <h4 class="report-value">{{ $expiredCount }}</h4>
        </div>
    </div>
    </div>





    <h3 class="mb-3 text-white mt-5 ">
        @if ($filter === 'expiring')
            Members Expiring Today
        @else
            Expired Members
        @endif
    </h3>

    {{-- Filter & Search Form --}}
    {{-- Filter & Search Form --}}
    <form id="memberSearchForm" action="{{ route('gym.members.filter') }}" method="GET"
        class="row g-2 align-items-center mb-3 justify-content-center">

        <div class="col-lg-3 col-md-4 col-sm-6">
            <select name="filter" id="filterSelect" class="form-select">
                <option value="date_range" {{ $filter === 'date_range' ? 'selected' : '' }}>By Date Range</option>
                <option value="expiring" {{ $filter === 'expiring' ? 'selected' : '' }}>Expiring Today</option>
                <option value="expired" {{ $filter === 'expired' ? 'selected' : '' }}>Expired Members</option>

            </select>
        </div>

        {{-- Date Range Inputs (only show when "date_range" selected) --}}
        <div class="col-lg-3 col-md-4 col-sm-6 date-filter-inputs" style="display: none;">
            <input type="date" name="from" id="fromDate" class="form-control" value="{{ request('from') }}">
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6 date-filter-inputs" style="display: none;">
            <input type="date" name="to" id="toDate" class="form-control" value="{{ request('to') }}">
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
                        <td class="text-danger fw-bold">
                            {{ $member->membership_valid_to }}
                        </td>

                        @php
                            $membership = DB::table('memberships')->where('id', $member->membership_type)->first();
                        @endphp

                        <td>{{ $membership->name ?? 'N/A' }}</td>

                        <td>{{ $member->fees_paid ?? '-' }}</td>
                        <td>{{ $member->fees_due ?? '-' }}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-renew btn-sm" data-bs-toggle="modal"
                                data-bs-target="#renewModal" data-id="{{ $member->id }}"
                                data-name="{{ $member->first_name }} {{ $member->last_name }}"
                                data-package-id="{{ $member->membership_type }}"
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
    <!-- Renew Modal -->
    <div class="modal fade" id="renewModal" tabindex="-1" aria-labelledby="renewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form method="POST" id="renewForm" action="">
                @csrf
                <div class="modal-content">
                    <div class="modal-header" style="padding-top: 77px; ">
                        <h5 class="modal-title text-black" id="renewModalLabel">Renew Membership</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="member_id" id="renewMemberId">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label text-black ">Member Name</label>
                                <input type="text" class="form-control" id="renewMemberName" name="member_name"
                                    readonly>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-black">Select Package</label>
                                <select name="membership_type" id="renewMemberPackage" class="form-select" required>
                                    <option value="">-- Select Package --</option>
                                    @foreach ($membershipTypes as $type)
                                        <option value="{{ $type->id }}" data-fees="{{ $type->signup_fee }}">
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-black">Fees</label>
                                <input type="text" name="fees" id="renewMemberFees" class="form-control" readonly>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label text-black">Current Expiry Date</label>
                                <input type="text" class="form-control" id="renewCurrentExpiry" name="current_expiry"
                                    readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-black">New Expiry Date</label>
                                <input type="date" class="form-control" name="new_expiry_date" required>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn " style="background:linear-gradient(45deg, #053d96 0%, #00a0c6 100%); color:white">Confirm Renew</button>

                        {{-- <button type="button" id="viewHistoryBtn" class="btn btn-info text-white">View History</button> --}}
                    </div>
                    <div class="modal-footer d-flex justify-content-between">


                    </div>
                    {{-- History Section --}}
                    {{-- <div id="memberHistorySection" class="mt-3" style="display: none;">
                        <h6 class="text-black">Previous Renewals</h6>
                          <div class="table-responsive">
                            <table class="table table-sm table-striped">
                           <thead>
                            <tr>
                    <th>Package</th>
                    <th>Fees Paid</th>
                    <th>Valid From</th>
                    <th>Valid To</th>
                    <th>Renewed At</th>
                                  </tr>
                            </thead>
                      <tbody id="memberHistoryBody"></tbody>
                              </table>
                             </div>
                                </div> --}}



                    <script>
                        $('#viewHistoryBtn').on('click', function() {
                            let memberId = $('#renewMemberId').val();
                            if (!memberId) return;

                            $.get("{{ url('members') }}/" + memberId + "/history", function(data) {
                                let rows = "";
                                data.forEach(function(item) {
                                    rows += `
                                      <tr>
                                          <td>${item.membership_name}</td>
                                          <td>${item.fees_paid}</td>
                                          <td>${item.valid_from}</td>
                                          <td>${item.valid_to}</td>
                                          <td>${item.renewed_at}</td>
                                      </tr>
                                  `;
                                });
                                $('#memberHistoryBody').html(rows);
                                $('#memberHistorySection').show();
                            });
                        });
                    </script>
                </div>
            </form>
        </div>
    </div>







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
                        <li><strong>Package:</strong> {{ $membership->name ?? 'N/A' }}</li>
                        <li><strong>Fees Paid:</strong> {{ $member->fees_paid ?? '-' }}</li>
                        <li><strong>Fees Due:</strong> {{ $member->fees_due ?? '-' }}</li>
                    </ul>
                    @if (true)
                        <!-- always show -->
                        <form action="{{ route('members.renew', $member->id) }}" method="POST" class="mt-2">
                            @csrf
                            <button type="button" class="btn btn-renew btn-sm" data-bs-toggle="modal"
                                data-bs-target="#renewModal" data-id="{{ $member->id }}"
                                data-name="{{ $member->first_name }} {{ $member->last_name }}"
                                data-package-id="{{ $member->membership_type }}"
                                data-expiry="{{ $member->membership_valid_to }}">
                                Renew
                            </button>
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
        const filterSelect = document.getElementById('filterSelect');
        const dateInputs = document.querySelectorAll('.date-filter-inputs');
        const fromDate = document.getElementById('fromDate');
        const toDate = document.getElementById('toDate');

        // Debounce for search input
        function debounce(func, delay) {
            let timer;
            return function() {
                clearTimeout(timer);
                timer = setTimeout(func, delay);
            }
        }

        searchInput.addEventListener('input', debounce(function() {
            searchForm.submit();
        }, 500));

        // Show/Hide date range inputs
        function toggleDateInputs() {
            if (filterSelect.value === 'date_range') {
                dateInputs.forEach(el => el.style.display = 'block');
            } else {
                // Reset date inputs when not in date_range mode
                fromDate.value = '';
                toDate.value = '';
                dateInputs.forEach(el => el.style.display = 'none');
            }
        }

        // On filter change
        filterSelect.addEventListener('change', function() {
            toggleDateInputs();
            searchForm.submit();
        });

        // On date change -> auto submit
        fromDate.addEventListener('change', () => {
            if (filterSelect.value === 'date_range') searchForm.submit();
        });
        toDate.addEventListener('change', () => {
            if (filterSelect.value === 'date_range') searchForm.submit();
        });

        // Initial check
        toggleDateInputs();
    </script>


    <script>
        $(document).ready(function() {
            $('#renewModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);

                var memberId = button.data('id');
                var memberName = button.data('name');
                var memberPackageId = button.data('package-id'); // current membership id
                var memberExpiry = button.data('expiry');

                // Fill modal inputs
                $('#renewMemberId').val(memberId);
                $('#renewMemberName').val(memberName);
                $('#renewCurrentExpiry').val(memberExpiry);

                // Set form action dynamically
                $('#renewForm').attr('action', "{{ url('members') }}/" + memberId + "/renew");

                // Reset dropdown & fees
                $('#renewMemberPackage').val('');
                $('#renewMemberFees').val('');

                // If member already has a package selected
                if (memberPackageId) {
                    $('#renewMemberPackage').val(memberPackageId).trigger('change');
                }
            });

            // On membership change, auto-fill fees
            $('#renewMemberPackage').on('change', function() {
                var fees = $(this).find('option:selected').data('fees') || 0;
                $('#renewMemberFees').val(fees);
            });
        });
    </script>



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

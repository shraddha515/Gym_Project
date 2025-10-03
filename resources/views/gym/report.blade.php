@extends('admin.layout')

@section('content')
    <style>
        /* Report Cards */
        .report-card {
            border: 2px solid #3b82f6;
            /* theme border color (blue shade) */
            border-radius: 8px;
            padding: 15px;
            background: #fff;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            text-align: center;
            transition: all 0.2s ease-in-out;
        }

        .report-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        /* Labels */
        .report-label {
            font-size: 0.85rem;
            font-weight: 500;
            color: #6b7280;
            /* muted gray */
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Values */
        .report-value {
            font-size: 1.1rem;
            font-weight: 600;
            color: #111827;
            /* dark text */
            margin: 0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .report-card {
                padding: 12px;
            }

            .report-label {
                font-size: 0.8rem;
            }

            .report-value {
                font-size: 1rem;
            }
        }

        /* Compact filter form */
        .report-filter .form-control,
        .report-filter .form-select {
            font-size: 0.85rem;
            padding: 4px 8px;
            border: 1px solid #3b82f6;
            /* theme border color */
            border-radius: 6px;
        }

        .report-filter .form-label {
            font-size: 0.95rem;
            font-weight: 500;
            color: #ffffffff;
            /* muted dark */
        }

        .report-filter .btn-primaryy {
            background: linear-gradient(45deg, #3b82f6 0%, #a855f7 100%);
            /* theme blue */
            border-color: #3b82f6;
            font-size: 0.85rem;
            padding: 6px;
            border-radius: 6px;
        }

        .report-filter .btn-primaryy:hover {
            background-color: #2563eb;
            border-color: #2563eb;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {

            .report-filter .form-control,
            .report-filter .form-select {
                font-size: 0.8rem;
                padding: 3px 6px;
            }

            .report-filter .btn-primary {
                font-size: 0.8rem;
                padding: 5px;
            }
        }
    </style>
    <div class="container py-4 px-4"  style="min-width: 80vw;">
        <h2 class="mb-3 @if (!request()->has('pdf')) text-white @endif">Gym Reports</h2>

        @php
            // Make sure all variables exist
            $member_id = $member_id ?? null;
            $members = $members ?? collect();
            $expenses = $expenses ?? collect();
            $data = $data ?? collect();
            $items = request()->has('pdf') ? $data : ($type == 'members' ? $members : $expenses);
        @endphp

        @if (!request()->has('pdf'))
            <!-- Cards Section -->
            <div class="row mb-4 g-3">
                <div class="col-12 col-md-3">
                    <div class="report-card">
                        <h6 class="report-label">Total Fees Collected</h6>
                        <h4 class="report-value">₹{{ number_format($totalFees ?? 0, 2) }}</h4>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="report-card">
                        <h6 class="report-label">Total Expenses</h6>
                        <h4 class="report-value">₹{{ number_format($totalExpenses ?? 0, 2) }}</h4>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="report-card">
                        <h6 class="report-label">Net Amount</h6>
                        <h4 class="report-value">₹{{ number_format(($totalFees ?? 0) - ($totalExpenses ?? 0), 2) }}</h4>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="report-card">
                        <h6 class="report-label">Total Members</h6>
                        <h4 class="report-value">{{ $totalMembers ?? 0 }}</h4>
                    </div>
                </div>
            </div>

            <!-- Filter Section -->
            <form method="GET" action="{{ route('gym.report') }}" class="report-filter row g-2 mb-4 align-items-end"
                id="reportFilterForm">
                <div class="col-6 col-md-2">
                    <label class="form-label small mb-1">From</label>
                    <input type="date" name="from" value="{{ $from ?? '' }}"
                        class="form-control form-control-sm auto-filter">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label small mb-1">To</label>
                    <input type="date" name="to" value="{{ $to ?? '' }}"
                        class="form-control form-control-sm auto-filter">
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label small mb-1">Type</label>
                    <select name="type" id="typeSelect" class="form-select form-select-sm auto-filter">
                        <option value="members" @if ($type == 'members') selected @endif>Members Data</option>
                        <option value="expenses" @if ($type == 'expenses') selected @endif>Expenses Data</option>
                    </select>
                </div>
                <div class="col-6 col-md-3" id="memberFilterDiv">
                    <label class="form-label small mb-1">Member</label>
                    <select name="member_id" id="memberSelect" class="form-select form-select-sm auto-filter">
                        <option value="">All Members</option>
                        @foreach (DB::table('members')->where('gym_id', auth()->user()->gym_id)->get() as $mem)
                            <option value="{{ $mem->id }}" @if ($member_id == $mem->id) selected @endif>
                                {{ $mem->first_name }} {{ $mem->last_name ?? '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <!-- Optional fallback Filter button (hidden if auto-filter works) -->
                <div class="col-12 col-md-2 d-grid">
                    <button type="submit" class="btn btn-primaryy btn-sm d-none">Filter</button>
                </div>
            </form>


            <!-- Export Buttons -->
            <div class="mb-3 d-flex justify-content-end gap-2">
                {{-- <a href="{{ route('gym.report.pdf', request()->all()) }}" class="btn btn-danger btn-sm">Download PDF</a> --}}
                <a href="{{ route('gym.report.csv', request()->all()) }}" class="btn btn-success btn-sm">Download CSV</a>
            </div>
        @endif
        @php
            $items = request()->has('pdf') ? $data : ($type == 'members' ? $members : $expenses);
        @endphp

        <!-- Table Section -->
        <div class="card shadow-sm border-0">
            <div class="card-header text-white" style="background: linear-gradient(45deg, #023661 0%, #015f70 100%);">
                <h5 class="mb-0">{{ ucfirst($type) }} Details</h5>
            </div>

            <div class="table-responsive d-none d-md-block">
                <table class="table table-hover align-middle mb-0 @if (request()->has('pdf')) pdf-table @endif">
                    <thead>
                        @if ($type == 'members')
                            <tr style=" font-size: 17px;">
                                <th>ID</th>
                                <th>Name</th>
                                <th>Mobile</th>
                                <th>Membership Type</th>
                                <th>Valid From</th>
                                <th>Valid To</th>
                                <th>Amount</th>
                            </tr>
                        @else
                            <tr style=" font-size: 17px;">
                                <th>ID</th>
                                <th>Category</th>
                                <th>Amount</th>
                                <th>Description</th>
                                <th>Expense Date</th>
                            </tr>
                        @endif
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            @if ($type == 'members')
                                <tr style=" font-size: 17px;">
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->full_name ?? $item->first_name . ' ' . $item->last_name }}</td>
                                    <td>{{ $item->mobile_number }}</td>
                                    <td>{{ $item->membership_name ?? $item->membership_type }}</td>

                                    <td>{{ $item->membership_valid_from }}</td>
                                    <td>{{ $item->membership_valid_to }}</td>
                                    <td class="text-success">₹{{ number_format($item->amount ?? 0, 2) }}</td>
                                </tr>
                            @else
                                <tr style=" font-size: 17px;">
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->category }}</td>
                                    <td class="text-danger">₹{{ number_format($item->amount ?? 0, 2) }}</td>
                                    <td>{{ $item->description }}</td>
                                    <td>{{ $item->expense_date }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Accordion -->
            <div class="d-block d-md-none" id="{{ $type }}Accordion">
                @foreach ($items as $item)
                    <div class="card mb-2 shadow-sm" onclick="selectCard(this)">
                        <div class="card-header d-flex justify-content-between align-items-center p-2"
                            data-bs-toggle="collapse" data-bs-target="#{{ $type }}Card{{ $item->id }}"
                            style="cursor:pointer;">
                            <div>
                                @if ($type == 'members')
                                    {{ $item->full_name ?? $item->first_name . ' ' . $item->last_name }}
                                @else
                                    {{ $item->category }}
                                @endif
                            </div>
                            <i class="bi bi-chevron-down text-dark"></i>
                        </div>
                        <div class="collapse" id="{{ $type }}Card{{ $item->id }}"
                            data-bs-parent="#{{ $type }}Accordion">
                            <div class="card-body p-2">
                                @if ($type == 'members')
                                    <p><strong>ID:</strong> {{ $item->id }}</p>
                                    <p><strong>Mobile:</strong> {{ $item->mobile_number }}</p>
                                    <p><strong>Membership:</strong> {{ $item->membership_type }}</p>
                                    <p><strong>Valid:</strong> {{ $item->membership_valid_from }} →
                                        {{ $item->membership_valid_to }}</p>
                                    <p><strong>Amount:</strong> ₹{{ number_format($item->amount ?? 0, 2) }}</p>
                                @else
                                    <p><strong>ID:</strong> {{ $item->id }}</p>
                                    <p><strong>Amount:</strong> ₹{{ number_format($item->amount ?? 0, 2) }}</p>
                                    <p><strong>Description:</strong> {{ $item->description }}</p>
                                    <p><strong>Date:</strong> {{ $item->expense_date }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <script>
        function selectCard(card) {
            const parent = card.parentElement;
            [...parent.children].forEach(c => c.classList.remove('selected'));
            card.classList.add('selected');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.getElementById('typeSelect');
            const memberFilterDiv = document.getElementById('memberFilterDiv');
            const autoFilterElements = document.querySelectorAll('.auto-filter');
            const form = document.getElementById('reportFilterForm');

            // Show/hide member dropdown based on type
            function toggleMemberDropdown() {
                if (typeSelect.value === 'expenses') {
                    memberFilterDiv.style.display = 'none';
                } else {
                    memberFilterDiv.style.display = 'block';
                }
            }

            toggleMemberDropdown(); // Initial call

            typeSelect.addEventListener('change', function() {
                toggleMemberDropdown();
                form.submit(); // Auto submit when type changes
            });

            // Auto-submit when any filter input changes
            autoFilterElements.forEach(function(el) {
                el.addEventListener('change', function() {
                    form.submit();
                });
            });
        });
    </script>

@endsection

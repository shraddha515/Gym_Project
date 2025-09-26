@extends('admin.layout')

@section('content')
    <style>
        @media (max-width: 767px) {
            #addExpenseCard {
                margin-bottom: 15px;
            }
        }

        /* Custom CSS for the Search Bar */
        .filter-bar {
            background: var(--card-bg);
            /* Use a subtle background */
            border: 1px solid rgba(0, 0, 0, 0.05);
            /* Soft border */
        }

        /* Input Group Styling */
        .input-group .form-control {
            border-color: #ced4da;
            background-color: #f8f9fa;
            /* Lighter background for inputs */
            color: var(--text-dark);
        }

        .input-group .form-control:focus {
            border-color: #86b7fe;
            /* Bootstrap's focus color */
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .input-group-text {
            background-color: #e9ecef;
            border-color: #ced4da;
            color: #495057;
        }

        /* Specific input widths for desktop */
        @media (min-width: 768px) {
            .search-group {
                max-width: 250px;
                /* Reduces width of search input */
            }

            .date-group {
                max-width: 180px;
                /* Reduces width of date inputs */
            }
        }

        /* Button Styling */
        .btn {
            border: none;
            font-weight: 500;
        }

        .btn-primary {
            background: var(--accent-gradient);
            color: var(--text-light);
        }

        .btn-outline-warning {
            color: var(--text-dark);
            border-color: #ffc107;
        }

        .btn-outline-warning:hover {
            background-color: #ffc107;
            color: white;
        }

        .btn-outline-secondary,
        .btn-outline-info {
            color: var(--text-dark);
        }

        .btn-outline-secondary:hover {
            background-color: #6c757d;
            color: white;
        }

        .btn-outline-info:hover {
            background-color: #0dcaf0;
            color: white;
        }

        /* Responsive button group */
        .btn-group-responsive {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            /* Spacing between buttons on mobile */
            justify-content: center;
            /* Center buttons on mobile */
        }

        @media (max-width: 767.98px) {
            .btn-group-responsive .btn {
                flex-grow: 1;
                /* Make buttons fill the width */
            }
        }

        /* Custom CSS for Expense Dashboard */

        /* Card Styling */
        .card {
            border-radius: 0.75rem;
            overflow: hidden;
            background-color: var(--card-bg);
        }

        .card-header {
            background: var(--topbar-gradient);
            border-bottom: none;
            padding: 1rem 1.5rem;
        }

        .card-title {
            font-weight: 600;
        }

        /* Form Styling */
        .form-label {
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--text-dark);
            margin-bottom: 0.25rem;

        }

        .form-control-sm {
            height: calc(1.5em + 0.5rem + 2px);
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
        }

        .form-control {
            background-color: #f8f9fa;
            border: 1px solid #e0e0e0;
            transition: all 0.2s ease-in-out;
        }

        .form-control:focus {
            background-color: #ffffff;
            border-color: #a855f7;
            box-shadow: 0 0 0 0.2rem rgba(168, 85, 247, 0.25);
        }

        .btn-success {
            background: var(--accent-gradient);
            border: none;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn-success:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Table Styling */
        .table {
            font-size: 0.85rem;
            white-space: nowrap;
            /* Prevents text from wrapping in table cells */
        }

        .table thead th {
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            color: var(--text-dark);
        }

        .table tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.03);
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* Responsive adjustments */
        @media (max-width: 767.98px) {
            .add-expense-card {
                margin-bottom: 1.5rem;
            }
        }

        /* Modal Styling */
        .modal-header {
            background: var(--topbar-gradient);
            border-bottom: none;
            color: var(--text-light);
        }

        .modal-header .btn-close {
            filter: invert(1) grayscale(1) brightness(2);
            /* Makes the close button white */
        }

        /* Custom CSS for Mobile Expense Cards */
        .expenses-card-item {
            border-radius: 0.75rem;
            border: 1px solid #e0e0e0;
        }

        .expenses-card-item .card-body {
            padding: 1rem;
        }

        .expenses-card-item .text-primary {
            font-size: 1rem;
            font-weight: 600;
            color: var(--accent-gradient) !important;
        }

        .expenses-card-item .text-success {
            font-size: 1.1rem;
            font-weight: 700;
        }

        .expenses-card-item .text-muted {
            font-size: 0.75rem;
        }

        /* Ensure buttons are properly aligned and sized */
        .expenses-card-item .btn-sm {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
    </style>
    <div class="container py-4">
        <h3 class="mb-3">Gym Expenses</h3>

        {{-- Success/Error Messages --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="filter-bar d-flex flex-wrap justify-content-center align-items-center p-3 mb-4 rounded-3 shadow-sm">
            <div class="input-group search-group flex-grow-1 me-md-2 mb-2 mb-md-0">
                <span class="input-group-text bg-transparent border-end-0"><i class="bi bi-search"></i></span>
                <input type="text" id="searchKeyword" placeholder="Search category/description"
                    value="{{ $q ?? '' }}" class="form-control border-start-0" autocomplete="off">
            </div>

            <div class="input-group date-group me-md-2 mb-2 mb-md-0">
                <span class="input-group-text bg-transparent border-end-0"><i class="bi bi-calendar"></i></span>
                <input type="date" id="fromDate" value="{{ $from ?? '' }}" class="form-control border-start-0">
            </div>

            <div class="input-group date-group me-md-2 mb-2 mb-md-0">
                <span class="input-group-text bg-transparent border-end-0"><i class="bi bi-calendar"></i></span>
                <input type="date" id="toDate" value="{{ $to ?? '' }}" class="form-control border-start-0">
            </div>

            <div class="btn-group-responsive">
                <button class="btn btn-primary me-2" id="filterBtn">
                    <i class="bi bi-funnel"></i> Filter
                </button>
                <a href="{{ route('expenses.index') }}" class="btn btn-outline-warning me-2">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </a>
                <a href="{{ route('expenses.export.csv', ['from' => $from, 'to' => $to]) }}"
                    class="btn btn-outline-secondary me-2">
                    <i class="bi bi-file-earmark-arrow-down"></i> Export
                </a>
                {{-- <a href="{{ route('expenses.expensesreport', ['from' => $from, 'to' => $to]) }}" class="btn btn-outline-info">
            <i class="bi bi-bar-chart"></i> View Report
        </a> --}}
            </div>
        </div>

        <div class="row">
            {{-- Add Expense Button for Mobile and Desktop --}}
            <div class="col-12 mb-3 d-flex justify-content-end">
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addExpenseModal">
                    <i class="bi bi-plus-circle-fill me-2"></i>Add New Expense
                </button>
            </div>

            <div class="modal fade" id="addExpenseModal" tabindex="-1" aria-labelledby="addExpenseModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="addExpenseModalLabel"><i class="bi bi-plus-circle-fill me-2"></i>Add
                                New Expense</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('expenses.store') }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="category" class="form-label">Category</label>
                                    <input type="text" id="category" name="category"
                                        class="form-control form-control-sm" value="{{ old('category') }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Amount</label>
                                    <input type="number" step="0.01" id="amount" name="amount"
                                        class="form-control form-control-sm" value="{{ old('amount') }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="expense_date" class="form-label">Expense Date</label>
                                    <input type="date" id="expense_date" name="expense_date"
                                        class="form-control form-control-sm"
                                        value="{{ old('expense_date', date('d-m-y')) }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="payment_method" class="form-label">Payment Method</label>
                                    <input type="text" id="payment_method" name="payment_method"
                                        class="form-control form-control-sm" value="{{ old('payment_method') }}">
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea id="description" name="description" class="form-control form-control-sm" rows="2">{{ old('description') }}</textarea>
                                </div>
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-success mt-2"><i
                                            class="bi bi-floppy-fill me-2"></i>Save Expense</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12 d-none d-md-block">
                <div class="card expenses-table-card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 text-white">Expenses List</h5>
                        <span class="badge bg-primary rounded-pill">Total: {{ number_format($total, 2) }}</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped mb-0 small" id="expensesTable">
                                <thead class="bg-light">
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Category</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Payment</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($expenses as $e)
                                        <tr>
                                            <td>{{ $e->id }}</td>
                                            <td>{{ $e->expense_date }}</td>
                                            <td>{{ $e->category }}</td>
                                            <td>{{ number_format($e->amount, 2) }}</td>
                                            <td>{{ $e->payment_method }}</td>
                                            <td class="text-truncate" style="max-width: 150px;">{{ $e->description }}
                                            </td>
                                            <td class="text-nowrap">
                                                <a href="{{ route('expenses.edit', $e->id) }}"
                                                    class="btn btn-sm btn-outline-primary" title="Edit">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <form method="POST" action="{{ route('expenses.destroy', $e->id) }}"
                                                    class="d-inline" onsubmit="return confirm('Delete this expense?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        title="Delete">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">No records found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 d-block d-md-none">
                <h5 class="mb-3">Expenses List <span class="badge bg-primary rounded-pill small ms-2">Total:
                        {{ number_format($total, 2) }}</span></h5>
                @forelse($expenses as $e)
                    <div class="card mb-3 shadow-sm expenses-card-item">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0 text-primary">{{ $e->category }}</h6>
                                <span class="fw-bold text-success">{{ number_format($e->amount, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between small text-muted">
                                <span><i class="bi bi-calendar me-1"></i>{{ $e->expense_date }}</span>
                                <span><i class="bi bi-credit-card me-1"></i>{{ $e->payment_method }}</span>
                            </div>
                            <p class="mt-2 mb-2 small text-secondary">{{ Str::limit($e->description, 100) }}</p>
                            <div class="d-flex justify-content-end gap-2 mt-3">
                                <a href="{{ route('expenses.edit', $e->id) }}" class="btn btn-sm btn-outline-primary"><i
                                        class="bi bi-pencil-square me-1"></i>Edit</a>
                                <form method="POST" action="{{ route('expenses.destroy', $e->id) }}"
                                    onsubmit="return confirm('Delete this expense?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i
                                            class="bi bi-trash me-1"></i>Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info text-center">
                        No records found.
                    </div>
                @endforelse
            </div>

        </div>
    </div>
    {{-- Automatic search / filter with AJAX --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchKeyword');
            const fromInput = document.getElementById('fromDate');
            const toInput = document.getElementById('toDate');
            const filterBtn = document.getElementById('filterBtn');
            const tableContainer = document.getElementById('expensesTable');

            function fetchExpenses() {
                const params = new URLSearchParams({
                    q: searchInput.value,
                    from: fromInput.value,
                    to: toInput.value
                });
                fetch("{{ route('expenses.index') }}?" + params.toString(), {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.text())
                    .then(html => {
                        // Replace only the tbody
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newTbody = doc.querySelector('#expensesTable tbody');
                        tableContainer.querySelector('tbody').innerHTML = newTbody.innerHTML;
                    });
            }

            searchInput.addEventListener('keyup', fetchExpenses);
            filterBtn.addEventListener('click', function(e) {
                e.preventDefault();
                fetchExpenses();
            });
        });
    </script>


@endsection

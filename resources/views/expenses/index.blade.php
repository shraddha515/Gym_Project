@extends('admin.layout')

@section('content')
<div class="container py-4">
    <h3 class="mb-3">Gym Expenses</h3>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Filter/Search --}}
    <div class="mb-3 d-flex flex-wrap gap-2 align-items-center">
        <input type="text" id="searchKeyword" placeholder="Search category/description" value="{{ $q ?? '' }}" class="form-control me-2 flex-grow-1" autocomplete="off">
        <input type="date" id="fromDate" value="{{ $from ?? '' }}" class="form-control me-2">
        <input type="date" id="toDate" value="{{ $to ?? '' }}" class="form-control me-2">
        <button class="btn btn-primary me-2" id="filterBtn">Filter</button>
        <a href="{{ route('expenses.index') }}" class="btn btn-outline-warning me-2">Reset</a>
        <a href="{{ route('expenses.export.csv', ['from' => $from, 'to' => $to]) }}" class="btn btn-outline-secondary me-2">Export CSV</a>
        <a href="{{ route('expenses.report', ['from' => $from, 'to' => $to]) }}" class="btn btn-outline-info">View Report</a>
    </div>

    <div class="row">
        {{-- Add Expense Button for Mobile --}}
        <div class="d-block d-md-none mb-3">
            <button class="btn btn-success w-100" data-bs-toggle="collapse" data-bs-target="#addExpenseCard" aria-expanded="false" aria-controls="addExpenseCard">
                Add New Expense
            </button>
        </div>

        {{-- Add Expense Form --}}
        <div class="col-md-4 collapse d-md-block" id="addExpenseCard">
            <div class="card p-3 shadow-sm">
                <h5 class="mb-3">Add New Expense</h5>
                <form method="POST" action="{{ route('expenses.store') }}">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label">Category</label>
                        <input type="text" name="category" class="form-control" value="{{ old('category') }}" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Amount</label>
                        <input type="number" step="0.01" name="amount" class="form-control" value="{{ old('amount') }}" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Expense Date</label>
                        <input type="date" name="expense_date" class="form-control" value="{{ old('expense_date', date('Y-m-d')) }}" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Payment Method</label>
                        <input type="text" name="payment_method" class="form-control" value="{{ old('payment_method') }}">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                    </div>
                    <button class="btn btn-success w-100 mt-2">Save Expense</button>
                </form>
            </div>
        </div>

        {{-- Expenses List --}}
        <div class="col-md-8">
            <div class="card p-3 shadow-sm">
                <h5>Expenses List (Total: {{ $total }})</h5>
                <div class="table-responsive" id="expensesTable">
                    <table class="table table-striped table-sm align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Category</th>
                                <th>Amount</th>
                                <th>Payment</th>
                                <th>Description</th>
                                <th>Actions</th>
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
                                    <td style="max-width:220px; white-space:normal;">{{ Str::limit($e->description, 120) }}</td>
                                    <td>
                                        <a href="{{ route('expenses.edit', $e->id) }}" class="btn btn-sm btn-outline-primary mb-1">Edit</a>
                                        <form method="POST" action="{{ route('expenses.destroy', $e->id) }}" style="display:inline" onsubmit="return confirm('Delete this expense?');">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-danger mb-1">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center">No records found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Automatic search / filter with AJAX --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
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
            fetch("{{ route('expenses.index') }}?" + params.toString(), {headers: {'X-Requested-With': 'XMLHttpRequest'}})
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

<style>
    @media (max-width: 767px) {
        #addExpenseCard {
            margin-bottom: 15px;
        }
    }
</style>
@endsection

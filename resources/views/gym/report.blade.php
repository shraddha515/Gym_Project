@extends('admin.layout')

@section('content')
<div class="container py-4">

    <h2 class="mb-3">Gym Reports</h2>
<!-- Cards Section -->
    <div class="row mb-4">
        <div class="col-12 col-md-3 mb-2">
            <div class="card p-3 shadow-sm">
                <h6>Total Fees Collected</h6>
                <h4>₹{{ number_format($totalFees,2) }}</h4>
            </div>
        </div>
        <div class="col-12 col-md-3 mb-2">
            <div class="card p-3 shadow-sm">
                <h6>Total Expenses</h6>
                <h4>₹{{ number_format($totalExpenses,2) }}</h4>
            </div>
        </div>
        <div class="col-12 col-md-3 mb-2">
            <div class="card p-3 shadow-sm">
                <h6>Net Amount</h6>
                <h4>₹{{ number_format($totalFees - $totalExpenses,2) }}</h4>
            </div>
        </div>
        <div class="col-12 col-md-3 mb-2">
            <div class="card p-3 shadow-sm">
                <h6>Total Members</h6>
                <h4>{{ $totalMembers }}</h4>
            </div>
        </div>
    </div>
    <!-- Filter Section -->
    <form method="GET" action="{{ route('gym.report') }}" class="row g-2 mb-4">
        <div class="col-12 col-md-2">
            <input type="date" name="from" value="{{ $from ?? '' }}" class="form-control" placeholder="From Date">
        </div>
        <div class="col-12 col-md-2">
            <input type="date" name="to" value="{{ $to ?? '' }}" class="form-control" placeholder="To Date">
        </div>
        <div class="col-12 col-md-3">
            <select name="type" class="form-select">
                <option value="members" @if($type=='members') selected @endif>Members Data</option>
                <option value="expenses" @if($type=='expenses') selected @endif>Expenses Data</option>
            </select>
        </div>
        <div class="col-12 col-md-3">
            <select name="member_id" class="form-select">
                <option value="">All Members</option>
                @foreach(DB::table('members')->get() as $mem)
                    <option value="{{ $mem->id }}" @if($member_id==$mem->id) selected @endif>
                        {{ $mem->first_name }} {{ $mem->last_name ?? '' }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-md-2">
            <button class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    

    <!-- Export Buttons -->
    <div class="mb-3 d-flex gap-2">
        <a href="{{ route('gym.report.pdf', request()->all()) }}" class="btn btn-danger">Download PDF</a>
        <a href="{{ route('gym.report.csv', request()->all()) }}" class="btn btn-success">Download CSV</a>
    </div>

    <!-- Data Table -->
    @if($type=='members')
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Members Details</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Mobile</th>
                            <th>Membership Type</th>
                            <th>Valid From</th>
                            <th>Valid To</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($members as $m)
                        <tr>
                            <td>{{ $m->id }}</td>
                            <td>{{ $m->full_name ?? $m->first_name.' '.$m->last_name }}</td>
                            <td>{{ $m->mobile_number }}</td>
                            <td>{{ $m->membership_type }}</td>
                            <td>{{ $m->membership_valid_from }}</td>
                            <td>{{ $m->membership_valid_to }}</td>
                            <td>₹{{ number_format($m->amount ?? 0,2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Expenses Details</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Category</th>
                            <th>Amount</th>
                            <th>Description</th>
                            <th>Expense Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($expenses as $e)
                        <tr>
                            <td>{{ $e->id }}</td>
                            <td>{{ $e->category }}</td>
                            <td>₹{{ number_format($e->amount,2) }}</td>
                            <td>{{ $e->description }}</td>
                            <td>{{ $e->expense_date }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>
@endsection

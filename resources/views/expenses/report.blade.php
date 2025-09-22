@extends('admin.layout')

@section('content')
<div class="container py-4">
    <h3>Expenses Report</h3>
    <form method="GET" action="{{ route('expenses.report') }}" class="row g-2 mb-3">
        <div class="col-md-3"><input type="date" name="from" value="{{ $from ?? '' }}" class="form-control"></div>
        <div class="col-md-3"><input type="date" name="to" value="{{ $to ?? '' }}" class="form-control"></div>
        <div class="col-md-2"><button class="btn btn-primary">Apply</button></div>
    </form>

    <table class="table">
        <thead><tr><th>Category</th><th>Total Amount</th><th>Count</th></tr></thead>
        <tbody>
            @forelse($rows as $r)
                <tr>
                    <td>{{ $r->category }}</td>
                    <td>{{ number_format($r->total_amount,2) }}</td>
                    <td>{{ $r->count }}</td>
                </tr>
            @empty
                <tr><td colspan="3">No data</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

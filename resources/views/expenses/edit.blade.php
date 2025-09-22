@extends('admin.layout')

@section('content')
<div class="container py-4">
    <h3>Edit Expense #{{ $expense->id }}</h3>

    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('expenses.update', $expense->id) }}">
        @csrf
        <div class="mb-2">
            <label>Category</label>
            <input type="text" name="category" class="form-control" value="{{ old('category', $expense->category) }}" required>
        </div>
        <div class="mb-2">
            <label>Amount</label>
            <input type="number" step="0.01" name="amount" class="form-control" value="{{ old('amount', $expense->amount) }}" required>
        </div>
        <div class="mb-2">
            <label>Expense Date</label>
            <input type="date" name="expense_date" class="form-control" value="{{ old('expense_date', $expense->expense_date) }}" required>
        </div>
        <div class="mb-2">
            <label>Payment Method</label>
            <input type="text" name="payment_method" class="form-control" value="{{ old('payment_method', $expense->payment_method) }}">
        </div>
        <div class="mb-2">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="4">{{ old('description', $expense->description) }}</textarea>
        </div>
        <button class="btn btn-primary">Update</button>
        <a href="{{ route('expenses.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection

@extends('admin.layout')

@section('content')
    <style>
        /* Custom CSS for Edit Expense Page */

        /* Card Styling */
        .edit-expense-card {
            border-radius: 0.75rem;
            overflow: hidden;
            background-color: var(--card-bg);
        }

        .edit-expense-card .card-header {
            background: var(--topbar-gradient);
            border-bottom: none;
            padding: 1rem 1.5rem;
        }

        .edit-expense-card .card-title {
            font-weight: 600;
        }

        /* Form Element Styling */
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

        /* Button Styling */
        .btn-primary {
            background: var(--accent-gradient);
            border: none;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            font-weight: 500;
            font-size: 0.85rem;
            transition: all 0.2s ease;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card edit-expense-card shadow-lg">
                    <div class="card-header bg-gradient text-white">
                        <h5 class="card-title mb-0"><i class="bi bi-pencil-square me-2"></i>Edit Expense #{{ $expense->id }}
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger mb-3">{{ $errors->first() }}</div>
                        @endif

                        <form method="POST" action="{{ route('expenses.update', $expense->id) }}"
                            enctype="multipart/form-data">

                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="category" class="form-label">Category</label>
                                <input type="text" id="category" name="category" class="form-control form-control-sm"
                                    value="{{ old('category', $expense->category) }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="amount" class="form-label">Amount</label>
                                <input type="number" step="0.01" id="amount" name="amount"
                                    class="form-control form-control-sm" value="{{ old('amount', $expense->amount) }}"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="expense_date" class="form-label">Expense Date</label>
                                <input type="date" id="expense_date" name="expense_date"
                                    class="form-control form-control-sm"
                                    value="{{ old('expense_date', $expense->expense_date) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="invoice_number" class="form-label">Invoice Number</label>
                                <input type="text" id="invoice_number" name="invoice_number"
                                    class="form-control form-control-sm"
                                    value="{{ old('invoice_number', $expense->invoice_number) }}">
                            </div>

                            <div class="mb-3">
                                <label for="payment_method" class="form-label">Payment Method</label>
                                <input type="text" id="payment_method" name="payment_method"
                                    class="form-control form-control-sm"
                                    value="{{ old('payment_method', $expense->payment_method) }}">
                            </div>

                            <div class="mb-3">
                                <label for="document" class="form-label">Upload Document (PDF)</label>
                                <input type="file" id="document" name="document" class="form-control form-control-sm"
                                    accept="application/pdf">

                                {{-- Agar purana document uploaded hai to link show karo --}}
                                @if (!empty($expense->document))
                                    <p class="mt-2">
                                        Current File:
                                        <a href="{{ asset('storage/' . $expense->document) }}" target="_blank">
                                            View / Download PDF
                                        </a>
                                    </p>
                                    <input type="hidden" name="existing_document" value="{{ $expense->document }}">
                                @endif
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea id="description" name="description" class="form-control form-control-sm" rows="3">{{ old('description', $expense->description) }}</textarea>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="submit" class="btn btn-primary btn-sm"><i
                                        class="bi bi-arrow-up-circle-fill me-2"></i>Update</button>
                                <a href="{{ route('expenses.index') }}" class="btn btn-secondary btn-sm"><i
                                        class="bi bi-x-circle-fill me-2"></i>Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

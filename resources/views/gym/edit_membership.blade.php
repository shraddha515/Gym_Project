@extends('admin.layout')

@section('content')
    <div class="container mt-4">
        <div class="card mx-auto" style="max-width: 600px;"> <!-- Width limited for mobile -->
            <div class="card-header header-gradient "
                style="background: linear-gradient(45deg, #3b82f6 0%, #a855f7 100%); color:white;">
                <h5 class="mb-0">Edit Membership</h5>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data" action="{{ route('membership.update', $membership->id) }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12 col-md-8">
                            <label class="form-label">Membership Name *</label>
                            <input type="text" name="name" class="form-control" value="{{ $membership->name }}"
                                required>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label">Category *</label>
                            <select name="category_id" class="form-select">
                                <option value="">Select Category</option>
                                @foreach ($categories as $c)
                                    <option value="{{ $c->id }}"
                                        {{ $membership->category_id == $c->id ? 'selected' : '' }}>
                                        {{ $c->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-md-4">
                            <label class="form-label">Membership Period (days) *</label>
                            <input type="number" name="period_days" class="form-control"
                                value="{{ $membership->period_days }}" required>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label">Limit *</label>
                            <select name="limit_type" class="form-select">
                                <option value="Limited" {{ $membership->limit_type == 'Limited' ? 'selected' : '' }}>Limited
                                </option>
                                <option value="Unlimited" {{ $membership->limit_type == 'Unlimited' ? 'selected' : '' }}>
                                    Unlimited</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label">Classes Count</label>
                            <input type="number" name="classes_count" class="form-control"
                                value="{{ $membership->classes_count }}">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Amount ($)</label>
                            <input type="text" name="amount" class="form-control" value="{{ $membership->amount }}">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Signup Fee ($)</label>
                            <input type="text" name="signup_fee" class="form-control"
                                value="{{ $membership->signup_fee }}">
                        </div>

                        <div class="col-12 col-md-8">
                            <label class="form-label">Installment Plan</label>
                            <select name="installment_id" class="form-select">
                                <option value="">Select Installment Plan</option>
                                @foreach ($installments as $ins)
                                    <option value="{{ $ins->id }}"
                                        {{ $membership->installment_id == $ins->id ? 'selected' : '' }}>
                                        {{ $ins->title }} - ${{ number_format($ins->amount, 2) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" rows="4" class="form-control">{{ $membership->description }}</textarea>
                        </div>

                        {{-- <div class="col-12">
                        <label class="form-label">Photo</label>
                        <input type="file" name="image" class="form-control">
                        @if ($membership->image)
                            <div class="mt-2 text-center">
                                <img src="{{ asset($membership->image) }}" width="120" class="img-fluid rounded">
                            </div>
                        @endif
                    </div> --}}
                    </div>

                    <div class="mt-3 d-flex justify-content-between flex-wrap">
                        <a href="{{ route('gym.membership') }}" class="btn btn-outline-secondary me-2 mb-2"
                            style="background: linear-gradient(45deg, #3b82f6 0%, #a855f7 100%); color:white;">Cancel</a>
                        <button type="submit" class="btn btn-tertiary mb-2"
                            style="background: linear-gradient(45deg, #3b82f6 0%, #a855f7 100%); color:white;">Update
                            Membership</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

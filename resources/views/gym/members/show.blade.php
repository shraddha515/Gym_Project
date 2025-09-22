@extends('admin.layout')

@section('page-title', 'View Member')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="dashboard-heading"><i class="bi bi-eye me-2"></i> View Member</h2>
        <a href="{{ route('gym.members.index') }}" class="btn btn-secondary"><i class="bi bi-list me-2"></i> Members List</a>
    </div>

    <div class="card shadow-sm p-4">
        <div class="row">
            {{-- Left Column: Personal Info & QR Code --}}
            <div class="col-lg-5 mb-4">
                <div class="d-flex flex-column align-items-center text-center">
                    <img src="{{ url('public/storage/' . $member->photo_path) }}" alt="Member Photo" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px;">
                    <h3 class="fw-bold">{{ $member->first_name }} {{ $member->last_name }}</h3>
                </div>

                <div class="card mt-4 p-3 shadow-sm">
                    <div class="row g-2">
                        {{-- <div class="col-6 text-center">
                            {!! $qrCode !!}
                            <p class="mt-2 text-muted fw-bold">Member QR Code</p>
                        </div> --}}
                        <div class="col-6">
                            <ul class="list-unstyled fw-bold text-muted">
                                <li>Member ID: <span class="text-primary">{{ $member->member_id }}</span></li>
                                <li>Gender: <span class="text-secondary">{{ $member->gender ?? 'N/A' }}</span></li>
                                <li>Mobile: <span class="text-success">{{ $member->mobile_number }}</span></li>
                                <li>DOB: <span class="text-info">{{ $member->date_of_birth ?? 'N/A' }}</span></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="card mt-4 p-3 shadow-sm">
                    <h5 class="fw-bold text-primary mb-3">Contact Information</h5>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><i class="bi bi-phone me-2 text-primary"></i> Mobile: {{ $member->mobile_number }}</li>
                        <li class="mb-2"><i class="bi bi-geo-alt me-2 text-primary"></i> Address: {{ $member->address ?? 'N/A' }}, {{ $member->city ?? 'N/A' }}</li>
                    </ul>
                </div>
            </div>

            {{-- Right Column: Membership Info --}}
            <div class="col-lg-7">
                <div class="card p-4 shadow-sm h-100">
                    <h5 class="fw-bold text-success mb-4">Membership Details</h5>
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3">
                            <h6 class="fw-bold text-secondary">Membership Type</h6>
                            <p>{{ $member->membership_type ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="fw-bold text-secondary">Member Status</h6>
                            <p class="text-success">{{ $member->member_type ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="fw-bold text-secondary">Membership Valid From</h6>
                            <p>{{ $member->membership_valid_from ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="fw-bold text-secondary">Membership Valid To</h6>
                            <p>{{ $member->membership_valid_to ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="fw-bold text-secondary">Joining Date</h6>
                            <p>{{ \Carbon\Carbon::parse($member->created_at)->format('M d, Y') }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="fw-bold text-secondary">Interested Area</h6>
                            <p>{{ $member->interested_area ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .card {
        border-radius: 12px;
        border: none;
    }
    .img-fluid {
        border: 4px solid #f1f1f1;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .list-unstyled li {
        margin-bottom: 0.5rem;
    }
    .list-unstyled li i {
        font-size: 1.25rem;
    }
</style>
@endsection
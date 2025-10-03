@extends('admin.layout')

@section('page-title', 'View Member')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
        <h2 class="dashboard-heading fs-5 text-white"><i class="bi bi-eye me-2"></i> View Member</h2>
        <a href="{{ route('gym.members.index') }}" class="btn   shadow-sm"  style="background: linear-gradient(45deg, #053d96 0%, #00a0c6 100%) ; color:#fff; ">
            <i class="bi bi-list me-1"></i> Members List
        </a>
    </div>

    <div class="card shadow-sm p-3 p-md-4 border">
        <div class="row">
            {{-- Left Column: Personal Info & QR Code --}}
            <div class="col-lg-5 mb-3 mb-lg-0">
                <div class="d-flex flex-column align-items-center text-center">
                    <img src="{{ url('storage/app/public/' . $member->photo_path) }}" alt="Member Photo" 
                         class="img-fluid rounded-circle mb-2" style="width: 120px; height: 120px;">
                    <h4 class="text-dark mb-1">{{ $member->first_name }} {{ $member->last_name }}</h4>
                </div>

                <div class="card mt-3 p-3 shadow-sm border">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-1 text-dark">Member ID: <span class="text-primary">{{ $member->member_id }}</span></li>
                        <li class="mb-1 text-dark">Gender: {{ $member->gender ?? 'N/A' }}</li>
                        <li class="mb-1 text-dark">Mobile: {{ $member->mobile_number }}</li>
                        <li class="mb-1 text-dark">DOB: {{ $member->date_of_birth ?? 'N/A' }}</li>
                    </ul>
                </div>

                <div class="card mt-3 p-3 shadow-sm border">
                    <h6 class="text-primary mb-2">Contact Information</h6>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-1"><i class="bi bi-phone me-1 text-primary"></i> {{ $member->mobile_number }}</li>
                        <li class="mb-1"><i class="bi bi-geo-alt me-1 text-primary"></i> {{ $member->address ?? 'N/A' }}, {{ $member->city ?? 'N/A' }}</li>
                    </ul>
                </div>
            </div>

            {{-- Right Column: Membership Info --}}
            <div class="col-lg-7">
    <div class="card p-3 shadow-sm border h-100">
        <h6 class="text-success mb-3" style="font-weight:500; font-size:1.5rem;">Membership Details</h6>
        <div class="row g-2">
            <div class="col-6">
                <small class="text-dark" style="font-weight:400; font-size:1rem;">Fees Due</small>
                <p class="mb-1" style="font-size:0.85rem;">{{ $member->fees_due ?? '0' }}</p>
            </div>
            <div class="col-6">
                <small class="text-dark" style="font-weight:400; font-size:1rem;">Fees Paid</small>
                <p class="mb-1" style="font-size:0.85rem;">{{ $member->fees_paid ?? '0' }}</p>
            </div>
            <div class="col-6">
                <small class="text-dark" style="font-weight:400; font-size:1rem;">Membership Valid From</small>
                <p class="mb-1" style="font-size:0.85rem;">{{ $member->membership_valid_from ?? 'N/A' }}</p>
            </div>
            <div class="col-6">
                <small class="text-dark" style="font-weight:400; font-size:1rem;">Membership Valid To</small>
                <p class="mb-1" style="font-size:0.85rem;">{{ $member->membership_valid_to ?? 'N/A' }}</p>
            </div>
            <div class="col-6">
                <small class="text-dark" style="font-weight:400; font-size:1rem;">Joining Date</small>
                <p class="mb-1" style="font-size:0.85rem;">{{ \Carbon\Carbon::parse($member->created_at)->format('M d, Y') }}</p>
            </div>
            <div class="col-6">
                <small class="text-dark" style="font-weight:400; font-size:1rem;">Interested Area</small>
                <p class="mb-1" style="font-size:0.85rem;">{{ $member->interested_area ?? 'N/A' }}</p>
            </div>
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
        border-radius: 10px;
        border: 1px solid #d0d0d0;
    }

    .img-fluid {
        border: 3px solid #e5e5e5;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    }

    .list-unstyled li {
        margin-bottom: 0.4rem;
        font-weight: 400;
        font-size: 0.875rem;
    }

    h4, h6, small {
        font-weight: 500;
    }

    /* Responsive adjustments */
    @media (max-width: 767px) {
        .dashboard-heading {
            font-size: 1.1rem;
        }

        .card p, .card li {
            font-size: 0.8rem;
        }

        .img-fluid {
            width: 100px !important;
            height: 100px !important;
        }
    }
</style>
@endsection

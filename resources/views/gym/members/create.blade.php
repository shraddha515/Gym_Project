@extends('admin.layout')

@section('page-title', isset($member) ? 'Edit Member' : 'Add New Member')

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
            <h2 class="dashboard-heading" style="color: #f0f4fa;">{{ isset($member) ? 'Edit Member' : 'Add New Member' }}</h2>
            <a href="{{ route('gym.members.index') }}" class="btn gradient-btn mt-2 mt-md-0">
                <i class="bi bi-list me-2"></i> Members List
            </a>
            <style>
                .gradient-btn {
                    background: linear-gradient(45deg, #3b82f6 0%, #a855f7 100%);
                    color: #fff;
                    font-weight: 500;
                    border: none;
                    transition: all 0.3s ease;
                }

                .gradient-btn:hover {
                    color: #1f2937;
                    /* dark text on hover */
                    filter: brightness(110%);
                    text-decoration: none;
                }

                .gradient-btn:active {
                    color: #1f2937;
                    /* dark text on click */
                    transform: scale(0.97);
                }
            </style>

        </div>

        @if ($errors->any())
            <div class="alert alert-danger p-3 rounded">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card shadow-sm p-4 member-form-card">
            <form action="{{ isset($member) ? route('gym.members.update', $member->id) : route('gym.members.store') }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @if (isset($member))
                    @method('PUT')
                @endif

                {{-- Personal Information --}}
                <h4 class="form-section-heading">Personal Information</h4>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Member ID</label>
                        <input type="text" name="member_id" class="form-control"
                            value="{{ old('member_id', $member->member_id ?? ($memberId ?? '')) }}" readonly>
                    </div>

                    {{-- Text-specific validation for names --}}
                    <div class="col-md-6">
                        <label class="form-label">First Name *</label>
                        <input type="text" name="first_name" class="form-control" pattern="[A-Za-z\s]+"
                            title="Only letters and spaces are allowed"
                            value="{{ old('first_name', $member->first_name ?? '') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Last Name *</label>
                        <input type="text" name="last_name" class="form-control" pattern="[A-Za-z\s]+"
                            title="Only letters and spaces are allowed"
                            value="{{ old('last_name', $member->last_name ?? '') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Gender *</label>
                        <div class="d-flex gap-3 mt-1">
                            @foreach (['Male', 'Female', 'Other'] as $gender)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender"
                                        id="{{ strtolower($gender) }}" value="{{ $gender }}"
                                        {{ isset($member) && $member->gender == $gender ? 'checked' : '' }}>
                                    <label class="form-check-label"
                                        for="{{ strtolower($gender) }}" style="font-size: 16px;">{{ $gender }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Date Of Birth</label>
                        <input type="date" name="date_of_birth" class="form-control"
                            value="{{ old('date_of_birth', $member->date_of_birth ?? '') }}">
                    </div>

                    {{-- Aadhar Number: Only 12 digits --}}
                    <div class="col-md-6">
                        <label class="form-label">Aadhar Number</label>
                        <input type="text" name="aadhar_no" class="form-control" maxlength="12" inputmode="numeric"
                            pattern="\d{12}" title="Aadhar number must be exactly 12 digits"
                            value="{{ old('aadhar_no', $member->aadhar_no ?? '') }}">
                    </div>
                </div>

                {{-- Contact & Physical Info --}}
                <h4 class="form-section-heading mt-4">Contact & Physical Info</h4>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Mobile Number *</label>
                        <input type="tel" name="mobile_number" class="form-control"
                            value="{{ old('mobile_number', $member->mobile_number ?? '') }}" pattern="[0-9]{10}"
                            maxlength="10" required title="Please enter a valid 10-digit mobile number">
                    </div>


                    <div class="col-12">
                        <label class="form-label">Address *</label>
                        <input type="text" name="address" class="form-control"
                            value="{{ old('address', $member->address ?? '') }}" required>
                    </div>


                    {{-- Weight (KG): numeric, 0.1 step, positive --}}
                    <div class="col-md-4">
                        <label class="form-label">Weight (KG)</label>
                        <input type="number" step="0.1" min="0" name="weight" class="form-control"
                            value="{{ old('weight', $member->weight ?? '') }}" title="Enter weight in KG">
                    </div>

                    {{-- Height (CM): numeric, 0.1 step, positive --}}
                    <div class="col-md-4">
                        <label class="form-label">Height (CM)</label>
                        <input type="number" step="0.1" min="0" name="height" class="form-control"
                            value="{{ old('height', $member->height ?? '') }}" title="Enter height in CM">
                    </div>

                    {{-- Body Fat (%): numeric, 0.1 step, 0-100% --}}
                    <div class="col-md-4">
                        <label class="form-label">Body Fat (%)</label>
                        <input type="number" step="0.1" min="0" max="100" name="fat_percentage"
                            class="form-control" value="{{ old('fat_percentage', $member->fat_percentage ?? '') }}"
                            title="Enter body fat percentage (0-100%)">
                    </div>
                </div>

                {{-- Membership & Login --}}
                <h4 class="form-section-heading mt-4">Membership </h4>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Interested Area</label>
                        <input type="text" name="interested_area" class="form-control"
                            value="{{ old('interested_area', $member->interested_area ?? '') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Membership Type</label>
                        <select name="membership_type" class="form-select">
                            @foreach ($membershipTypes as $type)
                                <option value="{{ $type->id }}"
                                    {{ isset($member) && $member->membership_type == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    <div class="col-md-6">
                        <label class="form-label">Membership Valid From</label>
                        <input type="date" name="membership_valid_from" class="form-control"
                            value="{{ old('membership_valid_from', $member->membership_valid_from ?? '') }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Assign PT</label>
                        <select name="assigned_staff_id" class="form-control">
                            <option value="">Select PT</option>
                            @foreach ($pts as $pt)
                                <option value="{{ $pt->id }}"
                                    {{ isset($member) && $member->assigned_staff_id == $pt->id ? 'selected' : '' }}>
                                    {{ $pt->name }}
                                </option>
                            @endforeach
                        </select>

                    </div>


                    <div class="col-md-6">
                        <label class="form-label">Membership Valid To</label>
                        <input type="date" name="membership_valid_to" class="form-control"
                            value="{{ old('membership_valid_to', $member->membership_valid_to ?? '') }}">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Photo Capture</label>
                        <input type="file" name="photo" class="form-control" accept="image/*" capture="camera">
                    </div>

                    {{-- @if (isset($member) && $member->photo_path)
                        <div class="col-12 mt-2">
                            <p class="mb-1">Current Photo:</p>
                            <img src="{{ url('storage/member_photos/' . $member->photo_path) }}" alt="Current Photo"
                                class="img-thumbnail" style="max-height: 150px;">
                        </div>
                    @endif --}}
                </div>

                <div class="mt-4 d-flex justify-content-end">
                    <button type="submit" class="btn gradient-btn px-4">
                        {{ isset($member) ? 'Update Member' : 'Save Member' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('styles')
    {{-- <style>
        body {
            background-color: #f5f7fa;
            font-family: 'Segoe UI', sans-serif;
        }

        .dashboard-heading {
            font-weight: 600;
            color: #333;
        }

        .member-form-card {
            border-radius: 12px;
            background: #fff;
            border: none;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
        }

        .form-section-heading {
            font-weight: 600;
            color: rgb(63 79 148);
            border-bottom: 2px solid rgb(63 79 148);
            padding-bottom: 0.25rem;
            margin-bottom: 1rem;
        }

        .form-label {
            font-weight: 500;
            color: #555;
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            border: 1px solid #ced4da;
            transition: all 0.2s ease-in-out;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: rgb(63 79 148);
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .btn-success {
            border-radius: 8px;
            font-weight: 600;
        }

        .btn-success:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 12px rgba(40, 167, 69, 0.2);
        }

        @media(max-width: 767.98px) {
            .dashboard-heading {
                font-size: 1.5rem;
            }

            .d-flex.justify-content-between {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
            }

            .form-section-heading {
                font-size: 1.25rem;
                margin-top: 1.5rem;
            }
        }
    </style> --}}

    @section('styles')
<style>
    body {
        background-color: #f5f7fa;
        font-family: 'Segoe UI', sans-serif;
    }

    .dashboard-heading {
        font-weight: 600;
        color: #333;
    }

    .member-form-card {
        border-radius: 12px;
        background: #fff;
        border: none;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
    }

    .form-section-heading {
        font-weight: 600;
        color: rgb(63 79 148);
        border-bottom: 2px solid rgb(63 79 148);
        padding-bottom: 0.25rem;
        margin-bottom: 1rem;
    }

    .form-label {
        font-weight: 500;
        color: #555;
    }

    .form-control,
    .form-select {
        border-radius: 8px;
        border: 1px solid #ced4da;
        transition: all 0.2s ease-in-out;
        font-size: 0.95rem;
        padding: 0.55rem 0.75rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: rgb(63 79 148);
        box-shadow: 0 0 0 0.2rem rgba(63, 79, 148, 0.25);
    }

    .btn-success {
        border-radius: 8px;
        font-weight: 600;
    }

    .btn-success:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 12px rgba(40, 167, 69, 0.2);
    }

    /* ✅ Mobile Responsive Tweaks */
    @media(max-width: 767.98px) {
        .dashboard-heading {
            font-size: 1.25rem;
            font-weight: 500;
        }

        .d-flex.justify-content-between {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .member-form-card {
            padding: 1rem !important;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        }

        .form-section-heading {
            font-size: 1rem;
            font-weight: 500;
            margin-top: 1rem;
        }

        .form-label {
            font-size: 0.85rem;
            font-weight: 400;
        }

        .form-control,
        .form-select {
            font-size: 0.85rem;
            padding: 0.4rem 0.65rem;
            border-radius: 5px;
        }

        .btn.gradient-btn,
        .btn-success {
            width: 100%;
            font-size: 0.85rem;
            padding: 0.55rem;
        }
    }
</style>
@endsection

@endsection

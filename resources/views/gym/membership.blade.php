@extends('admin.layout')
@section('title', 'Memberships')

@section('content')
    <style>
        /* Small custom theme touches */
        .header-gradient {
            background: linear-gradient(90deg, #0ea5a4, #06b6d4);
            color: #fff;
            padding: 14px;
            border-radius: 6px;
        }

        .card-modern {
            border-radius: 8px;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.06);
        }

        .small-muted {
            font-size: .9rem;
            color: #6b7280;
        }

        .table-hover tbody tr:hover {
            background: #f8fafc;
        }

        .btn-tertiary {
            background: #14b8a6;
            color: #fff;
        }

        .card-modern {
            background: var(--card-bg);
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        }

        .table-modern {
            font-size: 0.85rem;
            color: var(--text-dark);
        }

        .table-modern thead {
            background: var(--bg-gradient);
            color: var(--text-light);
            font-weight: 500;
            font-size: 0.78rem;
            letter-spacing: 0.5px;
        }

        .table-modern th,
        .table-modern td {
            vertical-align: middle;
            padding: 10px 12px;
            border-bottom: 1px solid #e5e7eb;
        }

        .table-modern tbody tr:hover {
            background: rgba(59, 130, 246, 0.05);
        }

        .table-modern img {
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .table-actions .btn {
            font-size: 0.75rem;
            padding: 4px 7px;
            border-radius: 6px;
        }

        .btn-outline-primary {
            border-color: #3b82f6;
            color: #3b82f6;
        }

        .btn-outline-primary:hover {
            background: #3b82f6;
            color: #fff;
        }

        .btn-outline-danger {
            border-color: #ef4444;
            color: #ef4444;
        }

        .btn-outline-danger:hover {
            background: #ef4444;
            color: #fff;
        }



        .small-muted {
            font-size: 0.78rem;
            color: #6b7280;
        }

        /* Compact Table Action Buttons */
        .btn-action {
            font-size: 0.75rem;
            /* small font */
            padding: 3px 6px;
            /* reduced padding */
            border-radius: 6px;
            /* rounded corners */
            min-width: 20px !important;
            /* consistent width */
            text-align: center;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 3px;
            /* small gap between icon and text */
            transition: all 0.2s ease;
            background: linear-gradient(45deg, #053d96 0%, #00a0c6 100%);
            color: #fff
        }

        .btn-action i {
            font-size: 0.8rem;
        }

        /* Hover Effects */
        .btn-action:hover {
            filter: brightness(110%);
            color: #181717;
        }

        /* Specific button colors */
        .btn-danger.btn-action {
            background-color: #ef4444;
            border: none;
            color: #fff;
        }

        .btn-outline-info.btn-action {
            background-color: #3b82f6;
            border: none;
            color: #fff;
        }

        .btn-outline-info.btn-action:hover {
            background-color: #2563eb;
        }

        .member-card {
            border: 2px solid #e2e8f0;
            /* default light border */
            border-radius: 8px;
            background: #ffffff;
            transition: all 0.3s ease-in-out;
        }

        .member-card:hover {
            border-color: #3b82f6;
            /* hover highlight */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .member-card.selected {
            border-color: #3b82f6;
            /* green for selected card */
            box-shadow: 0 4px 15px rgba(30, 94, 110, 0.46);
        }

        .card-header {
            font-size: 0.92rem;
            font-weight: 500;
        }

        .member-name {
            font-size: 0.9rem;
            font-weight: 500;
            color: #1f2937;
        }

        .member-fields .list-group-item {
            font-size: 0.85rem;
            padding: 6px 8px;
            border: none;
            display: flex;
            justify-content: space-between;
        }

        /* Membership Modal Styling */
        #membershipModal .modal-content {
            border-radius: 10px;
            background: var(--card-bg);
            font-size: 0.85rem;
            color: var(--text-dark);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        #membershipModal .modal-header {
            background: var(--accent-gradient);
            color: var(--text-light);
            border-bottom: none;
            font-weight: 500;
            font-size: 0.9rem;
        }

        #membershipModal .modal-title {
            font-weight: 600;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        #membershipModal .btn-close {
            filter: brightness(150%);
        }

        #membershipModal .form-label {
            font-size: 0.82rem;
            font-weight: 500;
            color: var(--text-dark);
        }

        #membershipModal .form-control,
        #membershipModal .form-select,
        #membershipModal textarea {
            font-size: 0.83rem;
            border-radius: 6px;
            border: 1px solid #d1d5db;
            padding: 0.35rem 0.5rem;
        }

        #membershipModal .btn-outline-secondary {
            font-size: 0.8rem;
            border-radius: 6px;
            border-color: #6b7280;
            color: var(--text-dark);
            transition: all 0.3s ease;
        }

        #membershipModal .btn-outline-secondary:hover {
            background: #6b7280;
            color: #fff;
        }

        #membershipModal .btn-tertiary {
            font-size: 0.82rem;
            border-radius: 6px;
            background: var(--accent-gradient);
            color: #fff;
            border: none;
            transition: all 0.3s ease;
        }

        #membershipModal .btn-tertiary:hover {
            filter: brightness(110%);
        }

        #membershipModal img#previewImage {
            border-radius: 6px;
            margin-top: 0.5rem;
            display: block;
        }

        @media(max-width:767px) {
            #membershipModal .modal-content {
                font-size: 0.8rem;
            }

            #membershipModal .modal-title {
                font-size: 0.9rem;
            }

            #membershipModal .form-control,
            #membershipModal .form-select,
            #membershipModal textarea {
                font-size: 0.8rem;
            }

            #membershipModal .btn-outline-secondary,
            #membershipModal .btn-tertiary {
                font-size: 0.78rem;
                padding: 4px 8px;
            }
        }

        /* General Modal Styling */
        .modal-content {
            border-radius: 10px;
            font-size: 0.85rem;
            color: var(--text-dark);
            background: var(--card-bg);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            background: var(--accent-gradient);
            color: var(--text-light);
            font-weight: 500;
            font-size: 0.9rem;
            border-bottom: none;
        }

        .modal-title {
            font-weight: 600;
            font-size: 0.92rem;
        }

        .btn-close {
            filter: brightness(150%);
        }

        .form-control {
            font-size: 0.83rem;
            border-radius: 6px;
            border: 1px solid #d1d5db;
            padding: 0.35rem 0.5rem;
        }

        .btn-success {
            font-size: 0.82rem;
            background: var(--accent-gradient);
            border: none;
            color: #fff;
            border-radius: 6px;
            transition: 0.3s;
        }

        .btn-success:hover {
            filter: brightness(110%);
        }

        .btn-outline-secondary {
            font-size: 0.8rem;
            border-radius: 6px;
            border-color: #6b7280;
            color: var(--text-dark);
        }

        .btn-outline-secondary:hover {
            background: #6b7280;
            color: #fff;
        }

        .btn-danger {
            font-size: 0.78rem;
            border-radius: 6px;
            background: #ef4444;
            border: none;
            color: #fff;
            padding: 2px 8px;
        }

        .btn-danger:hover {
            filter: brightness(110%);
        }

        .list-group-item {
            font-size: 0.82rem;
            border-radius: 6px;
            padding: 0.4rem 0.6rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        @media(max-width:767px) {
            .modal-content {
                font-size: 0.8rem;
            }

            .modal-title {
                font-size: 0.88rem;
            }

            .form-control {
                font-size: 0.8rem;
            }

            .btn {
                font-size: 0.75rem;
                padding: 3px 6px;
            }
        }
    </style>

    <div class="container-fluid mt-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h4 class="text-white"><i class="bi bi-card-list me-2"></i> List</h4>
            <div>
                <!-- <button class="btn btn-outline-dark me-2" onclick="location.href='{{ route('gym.membership') }}'">
                                                        <i class="bi bi-list"></i> Membership List
                                                    </button>  -->
                <button class="btn btn-tertiary" data-bs-toggle="modal" data-bs-target="#membershipModal"
                    style="background: linear-gradient(45deg, #053d96 0%, #00a0c6 100%); color:white;">
                    <i class="bi bi-plus-lg"></i> Add
                </button>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success small-muted">{{ session('success') }}</div>
        @endif



        {{-- Desktop Table --}}
        <div class="d-none d-md-block">
            <div class="table-responsive">
                <table class="table table-hover align-middle table-modern"
                    style="border-collapse: separate; border-spacing:0 6px;">
                    <thead
                        style="background:linear-gradient(45deg, #053d96 0%, #00a0c6 100%); color:#fff; font-weight:500; font-size:0.9rem;">
                        <tr>
                            {{-- <th style="width:60px;">Photo</th> --}}
                            <th>Membership Name</th>
                            <th>Category</th>
                            <th>Period</th>
                            <th>Installment</th>
                            <th>Signup Fee</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($memberships as $m)
                            <tr style="background:#f8fafc; border-radius:6px;">
                                {{-- <td>
                                    @foreach ($memberships as $membership)
                                        @if ($membership->image)
                                            <img src="{{ asset($membership->image) }}" class="rounded-circle" width="45"
                        height="45" style="object-fit:cover;">
                        @else
                        <div class="rounded-circle bg-gray-300 d-flex justify-content-center align-items-center"
                            style="width:45px;height:45px;">
                            <i class="bi bi-person-circle text-muted" style="font-size:1.2rem;"></i>
                        </div>
                        @endif
                        @endforeach


                        </td> --}}
                                <td style="font-weight:500; color:var(--text-dark); font-size:0.87rem;">{{ $m->name }}
                                </td>
                                <td style="color:var(--text-dark); font-size:0.86rem;">{{ $m->category_name ?? '-' }}</td>
                                <td style="color:var(--text-dark); font-size:0.86rem;">{{ $m->period_days }} days</td>
                                <td style="color:var(--text-dark); font-size:0.86rem;">{{ $m->installment_title ?? '-' }}
                                </td>
                                <td style="color:var(--text-dark); font-size:0.86rem;">
                                    ₹{{ number_format($m->signup_fee, 2) }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1 flex-wrap">
                                        <a href="{{ route('membership.edit', $m->id) }}" class="btn btn-action btn-sm">
                                            <i class="bi bi-pencil me-1"></i> Edit
                                        </a>




                                        <form method="POST" action="{{ url('/membership/delete/' . $m->id) }}"
                                            style="display:inline-block;">
                                            @csrf
                                            <button type="submit" class="btn btn-action btn-sm btn-danger"
                                                onclick="return confirm('Delete this membership?')">
                                                <i class="bi bi-trash me-1"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No memberships found. Add one using
                                    "Add Membership".</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        {{-- Mobile Cards --}}
        <div class="d-md-none" id="mobileMembershipsAccordion">
            @forelse($memberships as $m)
                <div class="card mb-3 member-card shadow-sm" data-member-id="{{ $m->id }}">
                    <div class="card-header d-flex justify-content-between align-items-center p-2" data-bs-toggle="collapse"
                        data-bs-target="#membershipCard{{ $m->id }}" style="cursor:pointer;">
                        <div class="d-flex align-items-center gap-2">
                            {{-- <img src="{{ $m->image ? asset($m->image) : asset('images/placeholder-60.png') }}"
                                class="rounded-circle" width="40" height="40" style="object-fit:cover;"> --}}
                            <span class="member-name">{{ $m->name }}</span>
                        </div>
                        <i class="bi bi-chevron-down text-muted"></i>
                    </div>

                    <div class="collapse" id="membershipCard{{ $m->id }}"
                        data-bs-parent="#mobileMembershipsAccordion">
                        <div class="card-body p-2">
                            <ul class="list-group list-group-flush member-fields">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>Category</span>
                                    <span>{{ $m->category_name ?? '-' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>Period</span>
                                    <span>{{ $m->period_days }} days</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>Installment</span>
                                    <span>{{ $m->installment_title ?? '-' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>Signup Fee</span>
                                    <span>₹{{ number_format($m->signup_fee, 2) }}</span>
                                </li>
                            </ul>

                            <div class="d-flex justify-content-between gap-1 mt-2">
                                <a href="{{ route('membership.edit', $m->id) }}" class="btn btn-action btn-sm">
                                    <i class="bi bi-pencil me-1"></i> Edit
                                </a>



                                <button class="btn btn-action btn-sm ">
                                    <i class="bi bi-list-task me-1"></i> Activities
                                </button>
                                <form method="POST" action="{{ url('/membership/delete/' . $m->id) }}"
                                    style="display:inline-block;">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-action btn-sm"
                                        onclick="return confirm('Delete this membership?')">
                                        <i class="bi bi-trash me-1"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-4">No memberships found.</div>
            @endforelse
        </div>



        <!-- Membership Modal -->
        <div class="modal fade" id="membershipModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header header-gradient">
                        <h5 class="modal-title"><i class="bi bi-people"></i> <span id="modalTitle" style="color: white;">Add
                                Membership</span></h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <form id="membershipForm" method="POST" enctype="multipart/form-data"
                        action="{{ route('membership.store') }}">
                        @csrf
                        <div class="modal-body" style="max-height:70vh; overflow-y:auto;">
                            <input type="hidden" id="membership_id" name="membership_id" value="">

                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label class="form-label">Membership Name *</label>
                                    <input type="text" name="name" id="name" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Category *</label>
                                    <div class="d-flex gap-2">
                                        <select name="category_id" id="category_id" class="form-select">
                                            <option value="">Select Category</option>
                                            @foreach ($categories as $c)
                                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                                            @endforeach
                                        </select>
                                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal"
                                            data-bs-target="#categoryModal">Manage</button>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Membership Period (days) *</label>
                                    <input type="number" name="period_days" id="period_days" class="form-control"
                                        value="30" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Limit *</label>
                                    <select name="limit_type" id="limit_type" class="form-select">
                                        <option value="Limited">Limited</option>
                                        <option value="Unlimited">Unlimited</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Classes Count</label>
                                    <input type="number" name="classes_count" id="classes_count" class="form-control">
                                </div>
                                <!-- <div class="col-md-6">
                                        <label class="form-label">Amount (₹)</label>
                                        <input type="text" name="amount" id="amount" class="form-control">
                                    </div> -->
                                <div class="col-md-6">
                                    <label class="form-label">Signup Fee (₹)</label>
                                    <input type="text" name="signup_fee" id="signup_fee" class="form-control">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Installment Plan</label>
                                    <div class="d-flex gap-2">
                                        <select name="installment_id" id="installment_id" class="form-select">
                                            <option value="">Select Installment Plan</option>
                                            @foreach ($installments as $ins)
                                                <option value="{{ $ins->id }}">{{ $ins->title }} -
                                                    ${{ number_format($ins->amount, 2) }}</option>
                                            @endforeach
                                        </select>
                                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal"
                                            data-bs-target="#installmentModal">Manage</button>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" id="description" rows="4" class="form-control"></textarea>
                                </div>

                                <!-- <div class="col-12">
                                        <label class="form-label">Photo</label>
                                        <input type="file" name="image" id="image" class="form-control">
                                        <div class="mt-2">
                                            <img id="previewImage" src="{{ asset('images/placeholder-60.png') }}"
                                                width="120" style="display:none;">
                                        </div>
                                    </div> -->
                            </div>
                        </div>

                        <div class="modal-footer d-flex justify-content-end">
                            <button type="button" class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" id="saveBtn" class="btn btn-tertiary">Save Membership</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!-- Category Manager Modal -->
        <div class="modal fade" id="categoryModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add / Remove Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex gap-2 mb-3">
                            <input type="text" id="newCategoryName" class="form-control"
                                placeholder="Enter category name">
                            <button id="addCategoryBtn" class="btn btn-success">Add</button>
                        </div>
                        <ul class="list-group" id="categoriesList">
                            @foreach ($categories as $c)
                                <li class="list-group-item" data-id="{{ $c->id }}">
                                    <span>{{ $c->name }}</span>
                                    <button class="btn btn-danger remove-category">Delete</button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Installment Manager Modal -->
        <div class="modal fade" id="installmentModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add / Remove Installment Plan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-2 mb-3">
                            <div class="col">
                                <input type="text" id="newInstallTitle" class="form-control"
                                    placeholder="Plan title">
                            </div>
                            <div class="col-4">
                                <input type="text" id="newInstallAmount" class="form-control" placeholder="Amount">
                            </div>
                            <div class="col-auto">
                                <button id="addInstallBtn" class="btn btn-success">Add</button>
                            </div>
                        </div>

                        <ul class="list-group" id="installmentsList">
                            @foreach ($installments as $ins)
                                <li class="list-group-item" data-id="{{ $ins->id }}">
                                    <span>{{ $ins->title }} - ₹{{ number_format($ins->amount, 2) }}</span>
                                    <button class="btn btn-danger remove-install">Delete</button>
                                </li>
                            @endforeach
                        </ul>

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- JS: Highlight selected card -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const cards = document.querySelectorAll('.card-header[data-bs-toggle="collapse"]');
                cards.forEach(card => {
                    card.addEventListener('click', function() {
                        // Remove 'selected' from all
                        cards.forEach(c => c.closest('.member-card').classList.remove('selected'));
                        // Add 'selected' to the clicked one
                        this.closest('.member-card').classList.add('selected');
                    });
                });
            });

            document.addEventListener('DOMContentLoaded', function() {
                const cards = document.querySelectorAll('.card-header[data-bs-toggle="collapse"]');
                cards.forEach(card => {
                    card.addEventListener('click', function() {
                        // Collapse all other cards
                        cards.forEach(c => {
                            if (c !== this) {
                                const target = document.querySelector(c.dataset.bsTarget);
                                if (target.classList.contains('show')) {
                                    bootstrap.Collapse.getInstance(target).hide();
                                }
                            }
                        });
                    });
                });
            });

            const csrfToken = "{{ csrf_token() }}";

            // open edit modal and fill
            function openEdit(id) {
                fetch("{{ url('/membership/edit') }}/" + id)
                    .then(r => r.json())
                    .then(data => {
                        document.getElementById('modalTitle').innerText = 'Edit Membership';
                        document.getElementById('membershipForm').action = "{{ url('/membership/update') }}/" + id;
                        // fill fields
                        document.getElementById('name').value = data.name ?? '';
                        document.getElementById('category_id').value = data.category_id ?? '';
                        document.getElementById('period_days').value = data.period_days ?? '';
                        document.getElementById('limit_type').value = data.limit_type ?? 'Limited';
                        document.getElementById('classes_count').value = data.classes_count ?? '';
                        document.getElementById('classes_freq').value = data.classes_freq ?? '';
                        document.getElementById('amount').value = data.amount ?? '';
                        document.getElementById('signup_fee').value = data.signup_fee ?? '';
                        document.getElementById('installment_id').value = data.installment_id ?? '';
                        document.getElementById('description').value = data.description ?? '';
                        if (data.image) {
                            const preview = document.getElementById('previewImage');
                            preview.src = "{{ url('/') }}/" + data.image;
                            preview.style.display = 'block';
                        }
                        // open modal
                        var myModal = new bootstrap.Modal(document.getElementById('membershipModal'));
                        myModal.show();
                    })
                    .catch(err => console.error(err));
            }

            // reset on modal hide
            document.getElementById('membershipModal').addEventListener('hidden.bs.modal', function() {
                document.getElementById('membershipForm').action = "{{ route('membership.store') }}";
                document.getElementById('modalTitle').innerText = 'Add Membership';
                document.getElementById('membershipForm').reset();
                const preview = document.getElementById('previewImage');
                preview.style.display = 'none';
            });

            // preview image
            // document.getElementById('image').addEventListener('change', function(e) {
            //     const file = e.target.files[0];
            //     if (!file) return;
            //     const reader = new FileReader();
            //     reader.onload = function(ev) {
            //         const preview = document.getElementById('previewImage');
            //         preview.src = ev.target.result;
            //         preview.style.display = 'block';
            //     };
            //     reader.readAsDataURL(file);
            // });

            // Add category (AJAX)
            document.getElementById('addCategoryBtn').addEventListener('click', function() {
                const name = document.getElementById('newCategoryName').value.trim();
                if (!name) return alert('Enter category name');
                fetch("{{ route('membership.category.add') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            name
                        })
                    })
                    .then(r => r.json())
                    .then(obj => {
                        // append
                        const ul = document.getElementById('categoriesList');
                        const li = document.createElement('li');
                        li.className = 'list-group-item d-flex justify-content-between align-items-center';
                        li.dataset.id = obj.id;
                        li.innerHTML =
                            `${obj.name} <button class="btn btn-sm btn-danger remove-category">Delete</button>`;
                        ul.prepend(li);
                        // also add to main select
                        const sel = document.getElementById('category_id');
                        const opt = new Option(obj.name, obj.id);
                        sel.add(opt);
                        document.getElementById('newCategoryName').value = '';
                    })
                    .catch(e => console.error(e));
            });

            // delegate removal for categories
            document.getElementById('categoriesList').addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('remove-category')) {
                    const li = e.target.closest('li');
                    const id = li.dataset.id;

                    if (!confirm('Delete category?')) return;

                    fetch("{{ route('membership.category.delete', '') }}/" + id, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({})
                        })

                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                li.remove(); // remove from list
                                // remove from select dropdown
                                const sel = document.getElementById('category_id');
                                Array.from(sel.options).forEach(opt => {
                                    if (opt.value == id) opt.remove();
                                });
                            } else {
                                alert('Failed to delete category');
                            }
                        })
                        .catch(err => console.error(err));
                }
            });


            // Add installment
            document.getElementById('addInstallBtn').addEventListener('click', function() {
                const title = document.getElementById('newInstallTitle').value.trim();
                const amount = document.getElementById('newInstallAmount').value.trim() || 0;
                if (!title) return alert('Enter plan title');
                fetch("{{ route('membership.installment.add') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            title,
                            amount
                        })
                    })
                    .then(r => r.json())
                    .then(obj => {
                        const ul = document.getElementById('installmentsList');
                        const li = document.createElement('li');
                        li.className = 'list-group-item d-flex justify-content-between align-items-center';
                        li.dataset.id = obj.id;
                        li.innerHTML =
                            `${obj.title} - $${parseFloat(obj.amount).toFixed(2)} <button class="btn btn-sm btn-danger remove-install">Delete</button>`;
                        ul.prepend(li);

                        // add to installment select
                        const sel = document.getElementById('installment_id');
                        const opt = new Option(obj.title + ' - $' + parseFloat(obj.amount).toFixed(2), obj.id);
                        sel.add(opt);
                        document.getElementById('newInstallTitle').value = '';
                        document.getElementById('newInstallAmount').value = '';
                    })
                    .catch(e => console.error(e));
            });

            document.getElementById('installmentsList').addEventListener('click', function(e) {
                if (e.target && e.target.matches('.remove-install')) {
                    const li = e.target.closest('li');
                    const id = li.dataset.id;
                    if (!confirm('Delete plan?')) return;

                    fetch("{{ route('membership.installment.delete', '') }}/" + id, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({})
                        })
                        .then(r => r.json())
                        .then(() => {
                            li.remove();
                            const sel = document.getElementById('installment_id');
                            Array.from(sel.options).forEach(opt => {
                                if (opt.value == id) opt.remove();
                            });
                        })
                        .catch(e => console.error(e));
                }
            });
        </script>

    @endsection

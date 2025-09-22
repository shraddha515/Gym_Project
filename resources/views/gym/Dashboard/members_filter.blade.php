@extends('admin.layout')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="container-fluid py-4">
    <h3 class="mb-3">
        @if($filter === 'expiring') Members Expiring Today @else Expired Members @endif
    </h3>

    {{-- Filter & Search Form --}}
    <form action="{{ route('gym.dashboard.members.filter') }}" method="GET" class="row g-2 mb-3 align-items-end">
        <div class="col-md-3 col-sm-6">
            <select name="filter" class="form-select" onchange="this.form.submit()">
                <option value="expiring" {{ $filter==='expiring' ? 'selected' : '' }}>Expiring Today</option>
                <option value="expired" {{ $filter==='expired' ? 'selected' : '' }}>Expired Members</option>
            </select>
        </div>
        <div class="col-md-4 col-sm-6">
            <input type="text" name="search" value="{{ request('search') }}" 
                   class="form-control" placeholder="Search by Name or Mobile">
        </div>
        <div class="col-md-2 col-sm-6">
            <button type="submit" class="btn btn-primary w-100">Search</button>
        </div>
    </form>

    {{-- Desktop/Tablet Table --}}
    <div class="table-responsive d-none d-md-block">
        <table class="table table-hover table-bordered align-middle shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>Member ID</th>
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>Join Date</th>
                    <th>Expiry Date</th>
                    <th>Package</th>
                    <th>Total</th>
                    <th>Deposit</th>
                </tr>
            </thead>
            <tbody>
                @forelse($members as $member)
                <tr>
                    <td>{{ $member->member_id }}</td>
                    <td>{{ $member->first_name }} {{ $member->last_name }}</td>
                    <td>{{ $member->mobile_number }}</td>
                    <td>{{ $member->membership_valid_from }}</td>
                    <td class="{{ $filter === 'expired' ? 'text-danger fw-bold' : 'fw-semibold text-warning' }}">
                        {{ $member->membership_valid_to }}
                    </td>
                    <td>{{ $member->package_name ?? $member->membership_type }}</td>
                    <td>{{ $member->total ?? '-' }}</td>
                    <td>{{ $member->deposit ?? '-' }}</td>
                    <td>
    @if($filter === 'expired')
        <form action="{{ route('members.renew', $member->id) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-sm btn-success">Renew</button>
        </form>
    @endif
</td>

                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted">
                        No members found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile Cards --}}
    <div class="d-md-none">
        @forelse($members as $member)
        <div class="card mb-3 shadow-sm border-0 rounded-3 member-card">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0 fw-semibold text-dark">
                        {{ $member->first_name }} {{ $member->last_name }}
                    </h6>
                    @if($filter === 'expired')
                        <span class="badge bg-danger small">Expired</span>
                    @else
                        <span class="badge bg-warning text-dark small">Expiring Today</span>
                    @endif
                </div>
                <ul class="list-unstyled small mb-0 text-muted">
                    <li><i class="bi bi-person-badge me-2 text-primary"></i><strong>ID:</strong> {{ $member->member_id }}</li>
                    <li><i class="bi bi-telephone me-2 text-success"></i><strong>Mobile:</strong> {{ $member->mobile_number }}</li>
                    <li><i class="bi bi-calendar-check me-2 text-info"></i><strong>Join:</strong> {{ $member->membership_valid_from }}</li>
                    <li>
                        <i class="bi bi-calendar-x me-2 {{ $filter === 'expired' ? 'text-danger' : 'text-warning' }}"></i>
                        <strong>Expiry:</strong> {{ $member->membership_valid_to }}
                    </li>
                    <li><i class="bi bi-box-seam me-2 text-warning"></i><strong>Package:</strong> {{ $member->package_name ?? $member->membership_type }}</li>
                    <li><i class="bi bi-cash-coin me-2 text-success"></i><strong>Total:</strong> {{ $member->total ?? '-' }}</li>
                    <li><i class="bi bi-wallet2 me-2 text-secondary"></i><strong>Deposit:</strong> {{ $member->deposit ?? '-' }}</li>
                </ul>
                @if($filter === 'expired')
    <form action="{{ route('members.renew', $member->id) }}" method="POST" class="mt-2">
        @csrf
        <button type="submit" class="btn btn-sm btn-success w-100">Renew</button>
    </form>
@endif

            </div>
        </div>
        @empty
        <div class="text-center text-muted">No members found.</div>
        @endforelse
    </div>
</div>

<style>
.member-card {
    background: #fff;
    border-radius: 12px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.member-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.1);
}
.member-card h6 {
    font-size: 1rem;
}
.member-card ul li {
    margin-bottom: 4px;
}
.member-card ul li strong {
    font-weight: 500;
}
</style>
@endsection

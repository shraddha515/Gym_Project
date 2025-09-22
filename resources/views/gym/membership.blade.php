@extends('admin.layout')
@section('title', 'Memberships')

@section('content')
<style>
/* Small custom theme touches */
.header-gradient { background: linear-gradient(90deg,#0ea5a4,#06b6d4); color: #fff; padding: 14px; border-radius: 6px; }
.card-modern { border-radius: 8px; box-shadow: 0 6px 18px rgba(15,23,42,0.06); }
.small-muted { font-size: .9rem; color:#6b7280; }
.table-hover tbody tr:hover { background: #f8fafc; }
.btn-tertiary { background:#14b8a6; color:#fff; }
</style>

<div class="container-fluid mt-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h4 class="header-gradient"><i class="bi bi-card-list me-2"></i> Membership List</h4>
        <div>
            {{-- <button class="btn btn-outline-dark me-2" onclick="location.href='{{ route('gym.membership') }}'">
                <i class="bi bi-list"></i> Membership List
            </button> --}}
            <button class="btn btn-tertiary" data-bs-toggle="modal" data-bs-target="#membershipModal">
                <i class="bi bi-plus-lg"></i> Add Membership
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success small-muted">{{ session('success') }}</div>
    @endif

    <div class="card card-modern">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="small-muted text-muted">
                        <tr>
                            <th>Image</th>
                            <th>Membership Name</th>
                            <th>Category</th>
                            <th>Membership Period</th>
                            <th>Installment Plan</th>
                            <th>Signup Fee</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($memberships as $m)
                            <tr>
                                <td style="width:80px;">
                                    <img src="{{ $m->image ? asset($m->image) : asset('images/placeholder-60.png') }}" width="60" class="rounded">
                                </td>
                                <td class="fw-bold">{{ $m->name }}</td>
                                <td>{{ $m->category_name ?? '-' }}</td>
                                <td>{{ $m->period_days }} days</td>
                                <td>{{ $m->installment_title ?? '-' }}</td>
                                <td>${{ number_format($m->signup_fee,2) }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-primary" onclick="openEdit({{ $m->id }})"><i class="bi bi-pencil"></i></button>

                                        <form method="POST" action="{{ url('/membership/delete/'.$m->id) }}" style="display:inline-block;">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this membership?')"><i class="bi bi-trash"></i></button>
                                        </form>

                                        <button class="btn btn-sm btn-outline-info" title="Activities"><i class="bi bi-list-task"></i></button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center small-muted">No memberships found. Add one using "Add Membership".</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Membership Add/Edit Modal -->
<div class="modal fade" id="membershipModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header header-gradient">
        <h5 class="modal-title"><i class="bi bi-people"></i> <span id="modalTitle">Add Membership</span></h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <form id="membershipForm" method="POST" enctype="multipart/form-data" action="{{ route('membership.store') }}">
        @csrf
        <div class="modal-body">
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
                            @foreach($categories as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#categoryModal">Manage</button>
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Membership Period (days) *</label>
                    <input type="number" name="period_days" id="period_days" class="form-control" value="30" required>
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
                <div class="col-md-6">
                    <label class="form-label">Amount ($)</label>
                    <input type="text" name="amount" id="amount" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Signup Fee ($)</label>
                    <input type="text" name="signup_fee" id="signup_fee" class="form-control">
                </div>

                <div class="col-md-8">
                    <label class="form-label">Installment Plan</label>
                    <div class="d-flex gap-2">
                        <select name="installment_id" id="installment_id" class="form-select">
                            <option value="">Select Installment Plan</option>
                            @foreach($installments as $ins)
                                <option value="{{ $ins->id }}">{{ $ins->title }} - ${{ number_format($ins->amount,2) }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#installmentModal">Manage</button>
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" id="description" rows="4" class="form-control"></textarea>
                </div>

                <div class="col-12">
                    <label class="form-label">Photo</label>
                    <input type="file" name="image" id="image" class="form-control">
                    <div class="mt-2"><img id="previewImage" src="{{ asset('images/placeholder-60.png') }}" width="120" style="display:none;"></div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" id="saveBtn" class="btn btn-tertiary">Save membership</button>
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
            <input type="text" id="newCategoryName" class="form-control" placeholder="Enter category name">
            <button id="addCategoryBtn" class="btn btn-success">Add</button>
        </div>
        <ul class="list-group" id="categoriesList">
            @foreach($categories as $c)
                <li class="list-group-item d-flex justify-content-between align-items-center" data-id="{{ $c->id }}">
                    {{ $c->name }}
                    <button class="btn btn-sm btn-danger remove-category">Delete</button>
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
                <input type="text" id="newInstallTitle" class="form-control" placeholder="Plan title">
            </div>
            <div class="col-4">
                <input type="text" id="newInstallAmount" class="form-control" placeholder="Amount">
            </div>
            <div class="col-auto">
                <button id="addInstallBtn" class="btn btn-success">Add</button>
            </div>
        </div>

        <ul class="list-group" id="installmentsList">
            @foreach($installments as $ins)
                <li class="list-group-item d-flex justify-content-between align-items-center" data-id="{{ $ins->id }}">
                    {{ $ins->title }} - ${{ number_format($ins->amount,2) }}
                    <button class="btn btn-sm btn-danger remove-install">Delete</button>
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

<script>
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
document.getElementById('membershipModal').addEventListener('hidden.bs.modal', function () {
    document.getElementById('membershipForm').action = "{{ route('membership.store') }}";
    document.getElementById('modalTitle').innerText = 'Add Membership';
    document.getElementById('membershipForm').reset();
    const preview = document.getElementById('previewImage');
    preview.style.display = 'none';
});

// preview image
document.getElementById('image').addEventListener('change', function(e){
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(ev) {
        const preview = document.getElementById('previewImage');
        preview.src = ev.target.result;
        preview.style.display = 'block';
    };
    reader.readAsDataURL(file);
});

// Add category (AJAX)
document.getElementById('addCategoryBtn').addEventListener('click', function(){
    const name = document.getElementById('newCategoryName').value.trim();
    if (!name) return alert('Enter category name');
    fetch("{{ route('membership.category.add') }}", {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN': csrfToken},
        body: JSON.stringify({name})
    })
    .then(r => r.json())
    .then(obj => {
        // append
        const ul = document.getElementById('categoriesList');
        const li = document.createElement('li');
        li.className = 'list-group-item d-flex justify-content-between align-items-center';
        li.dataset.id = obj.id;
        li.innerHTML = `${obj.name} <button class="btn btn-sm btn-danger remove-category">Delete</button>`;
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
document.getElementById('categoriesList').addEventListener('click', function(e){
    if (e.target && e.target.matches('.remove-category')) {
        const li = e.target.closest('li');
        const id = li.dataset.id;
        if (!confirm('Delete category?')) return;
        fetch("{{ url('/membership/category/delete') }}/" + id, {
            method: 'POST',
            headers:{'X-CSRF-TOKEN': csrfToken}
        })
        .then(r => r.json())
        .then(() => {
            li.remove();
            // remove from select
            const sel = document.getElementById('category_id');
            Array.from(sel.options).forEach(opt => { if(opt.value == id) opt.remove(); });
        })
        .catch(e => console.error(e));
    }
});

// Add installment
document.getElementById('addInstallBtn').addEventListener('click', function(){
    const title = document.getElementById('newInstallTitle').value.trim();
    const amount = document.getElementById('newInstallAmount').value.trim() || 0;
    if (!title) return alert('Enter plan title');
    fetch("{{ route('membership.installment.add') }}", {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN': csrfToken},
        body: JSON.stringify({title, amount})
    })
    .then(r => r.json())
    .then(obj => {
        const ul = document.getElementById('installmentsList');
        const li = document.createElement('li');
        li.className = 'list-group-item d-flex justify-content-between align-items-center';
        li.dataset.id = obj.id;
        li.innerHTML = `${obj.title} - $${parseFloat(obj.amount).toFixed(2)} <button class="btn btn-sm btn-danger remove-install">Delete</button>`;
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

// remove installment
document.getElementById('installmentsList').addEventListener('click', function(e){
    if (e.target && e.target.matches('.remove-install')) {
        const li = e.target.closest('li');
        const id = li.dataset.id;
        if (!confirm('Delete plan?')) return;
        fetch("{{ url('/membership/installment/delete') }}/" + id, {
            method:'POST',
            headers:{'X-CSRF-TOKEN': csrfToken}
        })
        .then(r => r.json())
        .then(() => {
            li.remove();
            const sel = document.getElementById('installment_id');
            Array.from(sel.options).forEach(opt => { if(opt.value == id) opt.remove(); });
        })
        .catch(e => console.error(e));
    }
});
</script>

@endsection

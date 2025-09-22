<!-- Activity Manager Modal -->
<div class="modal fade" id="activityModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add / Remove Activities</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="d-flex gap-2 mb-3">
            <input type="text" id="newActivityName" class="form-control" placeholder="Enter activity name">
            <button id="addActivityBtn" class="btn btn-success">Add</button>
        </div>
        <ul class="list-group" id="activitiesList"></ul>
      </div>
      <div class="modal-footer">
        <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script>
let currentMembershipId = null;

function loadActivities(membershipId){
    currentMembershipId = membershipId;
    fetch("{{ url('/membership/activities') }}/" + membershipId)
    .then(r => r.json())
    .then(data => {
        const ul = document.getElementById('activitiesList');
        ul.innerHTML = '';
        data.forEach(act => {
            const li = document.createElement('li');
            li.className = 'list-group-item d-flex justify-content-between align-items-center';
            li.dataset.id = act.id;
            li.innerHTML = `${act.name} <button class="btn btn-sm btn-danger remove-activity">Delete</button>`;
            ul.appendChild(li);
        });
    });
}

// Add activity
document.getElementById('addActivityBtn').addEventListener('click', function(){
    const name = document.getElementById('newActivityName').value.trim();
    if (!name) return alert('Enter activity name');
    fetch("{{ route('membership.activity.add') }}", {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN': csrfToken},
        body: JSON.stringify({membership_id: currentMembershipId, name})
    })
    .then(r => r.json())
    .then(obj => {
        const ul = document.getElementById('activitiesList');
        const li = document.createElement('li');
        li.className = 'list-group-item d-flex justify-content-between align-items-center';
        li.dataset.id = obj.id;
        li.innerHTML = `${obj.name} <button class="btn btn-sm btn-danger remove-activity">Delete</button>`;
        ul.prepend(li);
        document.getElementById('newActivityName').value = '';
    });
});

// Remove activity
document.getElementById('activitiesList').addEventListener('click', function(e){
    if (e.target && e.target.matches('.remove-activity')) {
        const li = e.target.closest('li');
        const id = li.dataset.id;
        if (!confirm('Delete activity?')) return;
        fetch("{{ url('/membership/activity/delete') }}/" + id, {
            method: 'POST',
            headers:{'X-CSRF-TOKEN': csrfToken}
        })
        .then(r => r.json())
        .then(() => li.remove());
    }
});
</script>
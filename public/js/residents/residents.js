// ================= TOAST =================
function showToast(type = 'info', message = '') {
    if (typeof toastr === 'undefined') { alert(message); return; }
    toastr.options = { closeButton: true, progressBar: true, positionClass: "toast-top-right", timeOut: "3000" };
    toastr[type] ? toastr[type](message) : toastr.info(message);
}

// ================= HELPERS =================
function getInitials(first, last) {
    return ((first||'').trim().charAt(0) + (last||'').trim().charAt(0)).toUpperCase();
}
function getAge(birthdate) {
    if (!birthdate) return '—';
    const today = new Date(), dob = new Date(birthdate);
    let age = today.getFullYear() - dob.getFullYear();
    const m = today.getMonth() - dob.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) age--;
    return age;
}
function genderBadge(data) {
    const map = { 'Male': ['badge-male','fas fa-mars'], 'Female': ['badge-female','fas fa-venus'], 'Other': ['badge-other','fas fa-genderless'] };
    const [cls, icon] = map[data] || ['badge-other','fas fa-genderless'];
    return `<span class="badge-custom ${cls}"><i class="${icon}"></i> ${data||'—'}</span>`;
}
function voterBadge(d) {
    return d == 1
        ? `<span class="badge-custom badge-voter-yes"><i class="fas fa-check"></i> Yes</span>`
        : `<span class="badge-custom badge-voter-no"><i class="fas fa-times"></i> No</span>`;
}
function statusBadge(d) {
    const map = { 'Active':'badge-active', 'Inactive':'badge-inactive', 'Deceased':'badge-deceased', 'Transferred':'badge-transferred' };
    return `<span class="badge-custom ${map[d]||'badge-inactive'}">${d||'—'}</span>`;
}
function avatarClass(gender) {
    return gender === 'Male' ? 'avatar-m' : gender === 'Female' ? 'avatar-f' : 'avatar-o';
}

// ================= DATATABLE =================
let residentsTable;

$(document).ready(function () {
    if ($.fn.DataTable.isDataTable('#residentsTable')) $('#residentsTable').DataTable().destroy();

    residentsTable = $('#residentsTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        language: {
            processing: '<i class="fas fa-spinner fa-spin"></i> Loading…',
            emptyTable: '<div class="empty-state"><i class="fas fa-users"></i><p>No residents found</p></div>',
            zeroRecords: '<div class="empty-state"><i class="fas fa-search"></i><p>No matching records found</p></div>'
        },
        ajax: {
            url:  baseUrl + 'residents/fetchRecords',
            type: 'POST',
            data: function (d) { d.csrf_test_name = $('input[name=csrf_test_name]').val(); },
            error: function (xhr) { console.log(xhr.responseText); showToast('error', 'DataTable error'); }
        },
        columns: [
            { data: 'row_number', render: d => `<span class="row-num">${d}</span>` },
            { data: 'id', visible: false },
            {
                data: null,
                render: row => {
                    const initials = getInitials(row.first_name, row.last_name);
                    const age      = getAge(row.birthdate);
                    const avCls    = avatarClass(row.gender);
                    const fullName = `${row.first_name ?? ''} ${row.middle_name ? row.middle_name + ' ' : ''}${row.last_name ?? ''}`.trim();
                    return `<div class="resident-name">
                        <div class="resident-avatar ${avCls}">${initials}</div>
                        <div>
                            <div class="resident-fullname">${fullName}</div>
                            <div style="font-size:0.72rem;color:#8fa8be;">Age: ${age}</div>
                        </div>
                    </div>`;
                }
            },
            { data: 'birthdate', render: d => d ? `<span style="font-size:0.82rem;color:#4a6070;">${d}</span>` : '—' },
            { data: 'gender',      render: genderBadge },
            { data: 'civil_status', render: d => `<span style="font-size:0.83rem;">${d||'—'}</span>` },
            { data: 'is_voter',    render: voterBadge },
            { data: 'voter_id',    render: d => d ? `<code style="font-size:0.78rem;background:#f0f4f8;padding:2px 6px;border-radius:4px;">${d}</code>` : '—' },
            { data: 'household_id', render: d => d ? `<span style="font-size:0.82rem;">#${d}</span>` : '—' },
            { data: 'address_line1', render: d => `<span style="font-size:0.8rem;color:#4a6070;">${d||'—'}</span>` },
            { data: 'barangay',    render: d => `<span style="font-size:0.82rem;">${d||'—'}</span>` },
            { data: 'status',      render: statusBadge },
            {
                data: null, orderable: false, searchable: false,
                render: row => `<div style="display:flex;gap:5px;">
                    <button class="btn-action btn-edit edit-resident" data-id="${row.id}" title="Edit"><i class="far fa-edit"></i></button>
                    <button class="btn-action btn-delete delete-resident" data-id="${row.id}" title="Delete"><i class="fas fa-trash-alt"></i></button>
                </div>`
            }
        ]
    });

    loadResidentStats();
});

// ================= STAT CARDS =================
// Populates the 4 cards at the top of the Residents page:
//   #totalResidents  — Total Residents
//   #statActive      — Active residents
//   #statVoters      — Registered Voters  ← was showing "—"
//   #statGender      — Female / Male      ← was showing "0 / 0"
function loadResidentStats() {
    $.get(baseUrl + 'reports/reportStats', function (data) {
        // Dashboard-wide totals
        $('#totalResidents').text(data.total_residents  ?? 0);
        $('#totalHouseholds').text(data.total_households ?? 0);
        $('#totalBlotter').text(data.total_blotter      ?? 0);
        $('#totalClearances').text(data.total_clearances ?? 0);
        $('#totalOfficials').text(data.total_officials   ?? 0);
        $('#totalPermits').text(data.total_permits       ?? 0);
        $('#totalIndigents').text(data.total_indigents   ?? 0);

        // Residents page-specific stat cards
        $('#statActive').text(data.active_residents ?? data.total_residents ?? 0);
        $('#statVoters').text(data.total_voters     ?? 0);   // ← FIX: was showing "—"
        $('#statGender').text(
            (data.female_residents ?? 0) + ' / ' + (data.male_residents ?? 0)
        );                                                   // ← FIX: was showing "0 / 0"
    }, 'json').fail(function (xhr) {
        console.log('Stats error:', xhr.responseText);
        $('#totalResidents, #statActive, #statVoters, #statGender').text('—');
    });
}

// ================= REFRESH BUTTON =================
$('#btnRefreshTable').on('click', function () {
    const $icon = $(this).find('i');
    $icon.addClass('fa-spin');
    residentsTable.ajax.reload(function () {
        $icon.removeClass('fa-spin');
        showToast('info', 'Table refreshed');
    }, false);
    loadResidentStats();
});

// ================= ADD =================
$('#addResidentForm').on('submit', function (e) {
    e.preventDefault();
    const $btn = $(this).find('[type=submit]');
    $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving…');

    $.post(baseUrl + 'residents/save', $(this).serialize(), function (response) {
        if (response.status === 'success') {
            $('#AddNewModal').modal('hide');
            $('#addResidentForm')[0].reset();
            showToast('success', response.message);
            residentsTable.ajax.reload(null, false);
            loadResidentStats();
        } else {
            showToast('error', response.message);
        }
        updateCSRF(response);
    }, 'json').fail(function (xhr) {
        console.log(xhr.responseText);
        showToast('error', 'Server error');
    }).always(function () {
        $btn.prop('disabled', false).html('<i class="fas fa-save"></i> Save Resident');
    });
});

// ================= EDIT FETCH =================
$(document).on('click', '.edit-resident', function () {
    const id = $(this).data('id');
    if (!id) { showToast('error', 'Invalid ID'); return; }

    $.get(baseUrl + 'residents/get/' + id, function (response) {
        if (response.status === 'success') {
            const r = response.data;
            $('#editResidentId').val(r.id);
            $('#editFirstName').val(r.first_name);
            $('#editMiddleName').val(r.middle_name);
            $('#editLastName').val(r.last_name);
            $('#editSuffix').val(r.suffix);
            $('#editBirthdate').val(r.birthdate);
            $('#editGender').val(r.gender);
            $('#editCivilStatus').val(r.civil_status);
            $('#editIsVoter').val(r.is_voter);
            $('#editVoterId').val(r.voter_id);
            $('#editHouseholdId').val(r.household_id);
            $('#editStatus').val(r.status);
            $('#editAddressLine1').val(r.address_line1);
            $('#editBarangay').val(r.barangay);
            $('#editResidentModal').modal('show');
        } else {
            showToast('error', response.message);
        }
    }, 'json').fail(function (xhr) {
        showToast('error', 'Fetch failed'); console.log(xhr.responseText);
    });
});

// ================= UPDATE =================
$('#editResidentForm').on('submit', function (e) {
    e.preventDefault();
    const $btn = $(this).find('[type=submit]');
    $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving…');

    $.post(baseUrl + 'residents/update', $(this).serialize(), function (response) {
        if (response.status === 'success') {
            $('#editResidentModal').modal('hide');
            showToast('success', response.message);
            residentsTable.ajax.reload(null, false);
            loadResidentStats();
        } else {
            showToast('error', response.message);
        }
        updateCSRF(response);
    }, 'json').fail(function (xhr) {
        showToast('error', 'Update failed'); console.log(xhr.responseText);
    }).always(function () {
        $btn.prop('disabled', false).html('<i class="fas fa-save"></i> Update Resident');
    });
});

// ================= DELETE =================
$(document).on('click', '.delete-resident', function () {
    const id = $(this).data('id');
    if (!id) { showToast('error', 'Invalid ID'); return; }
    if (!confirm('Are you sure you want to delete this resident?')) return;

    $.post(baseUrl + 'residents/delete/' + id, {
        csrf_test_name: $('input[name=csrf_test_name]').val()
    }, function (response) {
        if (response.status === 'success') {
            showToast('success', response.message);
            residentsTable.ajax.reload(null, false);
            loadResidentStats();
        } else {
            showToast('error', response.message);
        }
        updateCSRF(response);
    }, 'json').fail(function (xhr) {
        showToast('error', 'Delete failed'); console.log(xhr.responseText);
    });
});

// ================= CSRF HELPER =================
function updateCSRF(response) {
    if (response && response.csrf_hash) {
        $('input[name=csrf_test_name]').val(response.csrf_hash);
    }
}

// ================= LEGACY ALIAS (keeps dashboard working) =================
function refreshReportStats() { loadResidentStats(); }

// ================= TOAST =================
function showToast(type, message) {
    if (typeof toastr === 'undefined') { alert(message); return; }
    toastr.options = { closeButton: true, progressBar: true, positionClass: "toast-top-right", timeOut: "3000" };
    if (toastr[type]) toastr[type](message);
    else toastr.info(message);
}

// ================= HELPERS =================
function getInitials(first, last) {
    return (((first || '').trim().charAt(0)) + ((last || '').trim().charAt(0))).toUpperCase();
}

function getAge(birthdate) {
    if (!birthdate) return '—';
    const today = new Date(), dob = new Date(birthdate);
    let age = today.getFullYear() - dob.getFullYear();
    const m = today.getMonth() - dob.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) age--;
    return age;
}

function formatDate(dateStr) {
    if (!dateStr) return '—';
    const d = new Date(dateStr);
    return d.toLocaleDateString('en-PH', { year: 'numeric', month: 'short', day: 'numeric' });
}

function genderBadge(data) {
    const map = {
        'Male':   ['badge-male',   'fas fa-mars'],
        'Female': ['badge-female', 'fas fa-venus'],
        'Other':  ['badge-other',  'fas fa-genderless']
    };
    const [cls, icon] = map[data] || ['badge-other', 'fas fa-genderless'];
    return `<span class="badge-custom ${cls}"><i class="${icon}"></i> ${data || '—'}</span>`;
}

function voterBadge(d) {
    return d == 1
        ? `<span class="badge-custom badge-voter-yes"><i class="fas fa-check"></i> Yes</span>`
        : `<span class="badge-custom badge-voter-no"><i class="fas fa-times"></i> No</span>`;
}

function statusBadge(d) {
    const map = {
        'Active':      'badge-active',
        'Work':        'badge-active',
        'Functional':  'badge-active',
        'Inactive':    'badge-inactive',
        'Deceased':    'badge-deceased',
        'Transferred': 'badge-transferred'
    };
    return `<span class="badge-custom ${map[d] || 'badge-inactive'}">${d || '—'}</span>`;
}

function avatarClass(gender) {
    return gender === 'Male' ? 'avatar-m' : gender === 'Female' ? 'avatar-f' : 'avatar-o';
}

function fullName(r) {
    return `${r.first_name ?? ''} ${r.middle_name ? r.middle_name + ' ' : ''}${r.last_name ?? ''}${r.suffix ? ' ' + r.suffix : ''}`.trim();
}

// ================= HERO DATE =================
$(function () {
    const opts = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    $('#heroDate').text(new Date().toLocaleDateString('en-PH', opts));
});

// ================= ACTIVE FILTER STATE =================
let viewType = 'all';

// ================= CSRF HELPER =================
function updateCSRF(response) {
    if (response && response.csrf_hash) {
        $('input[name="csrf_test_name"]').val(response.csrf_hash);
    }
}

function getCsrfToken() {
    return $('input[name="csrf_test_name"]').val();
}

// ================= DATATABLE =================
let residentsTable;

$(document).ready(function () {
    if ($.fn.DataTable.isDataTable('#residentsTable')) {
        $('#residentsTable').DataTable().destroy();
    }

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
            url: baseUrl + 'residents/fetchRecords',
            type: 'POST',
            data: function (d) {
                d.csrf_test_name = getCsrfToken();
                d.view_type = viewType;
            },
            dataSrc: function (json) {
                updateCSRF(json);
                return json.data;
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                showToast('error', 'Failed to load data. Please refresh.');
            }
        },
        columns: [
            {
                data: 'row_number',
                render: function (d) {
                    return `<span class="row-num">${d}</span>`;
                }
            },
            { data: 'id', visible: false },
            {
                data: null,
                render: function (row) {
                    const initials = getInitials(row.first_name, row.last_name);
                    const age      = getAge(row.birthdate);
                    const avCls    = avatarClass(row.gender);
                    const name     = fullName(row);
                    return `<div class="resident-name">
                        <div class="resident-avatar ${avCls}">${initials}</div>
                        <div>
                            <div class="resident-fullname">${name}</div>
                            <div class="resident-sub">Age ${age} &bull; HH #${row.household_id || '—'}</div>
                        </div>
                    </div>`;
                }
            },
            {
                data: 'birthdate',
                render: function (d) {
                    return d
                        ? `<span style="font-size:0.82rem;color:#4a6070;">${formatDate(d)}</span>
                           <div style="font-size:0.72rem;color:#8fa8be;">${getAge(d)} yrs old</div>`
                        : '—';
                }
            },
            { data: 'gender',      render: genderBadge },
            {
                data: 'civil_status',
                render: function (d) {
                    return `<span style="font-size:0.83rem;">${d || '—'}</span>`;
                }
            },
            {
                data: 'contact_number',
                render: function (d) {
                    return d
                        ? `<span style="font-size:0.83rem;"><i class="fas fa-phone-alt" style="font-size:0.7rem;margin-right:3px;color:#1d6fa4;"></i>${d}</span>`
                        : `<span style="color:#c0ccd8;font-size:0.78rem;">N/A</span>`;
                }
            },
            { data: 'is_voter',    render: voterBadge },
            {
                data: 'voter_id',
                render: function (d) {
                    return d
                        ? `<code style="font-size:0.78rem;background:#f0f4f8;padding:2px 6px;border-radius:4px;">${d}</code>`
                        : `<span style="color:#c0ccd8;font-size:0.78rem;">N/A</span>`;
                }
            },
            {
                data: null,
                render: function (row) {
                    const purok = row.address_line1 || '—';
                    const brgy  = row.barangay || 'Isio';
                    return `<div class="address-cell">
                        <div class="address-purok"><i class="fas fa-map-pin" style="font-size:0.68rem;margin-right:3px;"></i>${purok}</div>
                        <div class="address-brgy">Brgy. ${brgy}, Cauayan, Neg. Occ.</div>
                    </div>`;
                }
            },
            { data: 'status', render: statusBadge },
            {
                data: null, orderable: false, searchable: false,
                render: function (row) {
                    return `<div style="display:flex;gap:4px;">
                        <button class="btn-action btn-view view-resident" data-id="${row.id}" title="View Profile"><i class="fas fa-eye"></i></button>
                        <button class="btn-action btn-edit edit-resident" data-id="${row.id}" title="Edit"><i class="far fa-edit"></i></button>
                        <button class="btn-action btn-delete delete-resident" data-id="${row.id}" title="Delete"><i class="fas fa-trash-alt"></i></button>
                    </div>`;
                }
            }
        ]
    });

    loadResidentStats();
});

// ================= QUICK FILTERS =================
$(document).on('click', '.filter-pill', function () {
    $('.filter-pill').removeClass('active');
    $(this).addClass('active');

    const f = $(this).data('filter');
    viewType = f;

    let label = '';
    if (f === 'all') {
        label = '';
    } else if (f === 'voter-yes') {
        label = '— Voters: Yes';
    } else if (f === 'voter-no') {
        label = '— Voters: No';
    } else if (f.startsWith('purok-')) {
        const purokNum = f.replace('purok-', '');
        const purokLabel = purokNum === '7a' ? 'Purok 7A'
                         : purokNum === '7b' ? 'Purok 7B'
                         : 'Purok ' + purokNum;
        label = '— ' + purokLabel;
    } else if (f.startsWith('gender-')) {
        const g = f.replace('gender-', '');
        label = '— ' + g.charAt(0).toUpperCase() + g.slice(1);
    } else {
        label = '— ' + f;
    }

    $('#tableFilterLabel').text(label);
    residentsTable.ajax.reload(null, false);
});

// ================= STAT CARDS =================
// Uses residents/residentStats — self-contained, no external Reports dependency
function loadResidentStats() {
    $.get(baseUrl + 'residents/residentStats', function (data) {
        $('#totalResidents').text(data.total_residents  ?? 0);
        $('#statActive').text(data.active_residents     ?? 0);
        $('#statVoters').text(data.total_voters         ?? 0);
        $('#statGender').text((data.female_residents ?? 0) + ' / ' + (data.male_residents ?? 0));
    }, 'json').fail(function (xhr) {
        console.error('Stats error:', xhr.responseText);
        $('#totalResidents, #statActive, #statVoters, #statGender').text('—');
    });
}

function refreshReportStats() { loadResidentStats(); }

// ================= REFRESH =================
$('#btnRefreshTable').on('click', function () {
    const $icon = $(this).find('i');
    $icon.addClass('fa-spin');
    residentsTable.ajax.reload(function () {
        $icon.removeClass('fa-spin');
        showToast('info', 'Table refreshed');
    }, false);
    loadResidentStats();
});

// ================= EXPORT CSV =================
$('#btnExportCSV').on('click', function () {
    const params = new URLSearchParams({ view_type: viewType });
    window.location.href = baseUrl + 'residents/export?' + params.toString();
});

// ================= PRINT =================
$('#btnPrint').on('click', function () {
    const params = new URLSearchParams({ view_type: viewType });
    window.open(baseUrl + 'residents/printView?' + params.toString(), '_blank');
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
            showToast('error', response.message || 'Failed to save resident.');
        }
        updateCSRF(response);
    }, 'json').fail(function (xhr) {
        console.error(xhr.responseText);
        showToast('error', 'Server error. Please try again.');
    }).always(function () {
        $btn.prop('disabled', false).html('<i class="fas fa-save"></i> Save Resident');
    });
});

$('#AddNewModal').on('hidden.bs.modal', function () {
    $('#addResidentForm')[0].reset();
});

// ================= VIEW RESIDENT =================
$(document).on('click', '.view-resident', function () {
    const id = $(this).data('id');
    if (!id) { showToast('error', 'Invalid ID'); return; }

    $('#viewResidentBody').html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x text-muted"></i></div>');
    $('#viewResidentModal').modal('show');

    $.get(baseUrl + 'residents/get/' + id, function (response) {
        if (response.status !== 'success') {
            showToast('error', response.message || 'Failed to load resident.');
            return;
        }
        const r        = response.data;
        const age      = getAge(r.birthdate);
        const avCls    = avatarClass(r.gender);
        const initials = getInitials(r.first_name, r.last_name);
        const name     = fullName(r);

        $('#viewResidentBody').html(`
            <div style="display:flex;align-items:center;gap:16px;margin-bottom:20px;padding-bottom:16px;border-bottom:1px solid #e8edf3;">
                <div class="resident-avatar ${avCls}" style="width:56px;height:56px;border-radius:14px;font-size:1.1rem;">${initials}</div>
                <div>
                    <div style="font-size:1.05rem;font-weight:800;color:#1a2e42;">${name}</div>
                    <div style="font-size:0.78rem;color:#8fa8be;margin-top:4px;">
                        ${genderBadge(r.gender)} &nbsp; ${statusBadge(r.status)}
                    </div>
                </div>
            </div>
            <div class="view-detail-row">
                <span class="view-detail-label"><i class="fas fa-birthday-cake" style="width:14px;"></i> Birthdate</span>
                <span class="view-detail-value">${formatDate(r.birthdate)} &nbsp;<small style="color:#8fa8be;">(${age} years old)</small></span>
            </div>
            <div class="view-detail-row">
                <span class="view-detail-label"><i class="fas fa-heart" style="width:14px;"></i> Civil Status</span>
                <span class="view-detail-value">${r.civil_status || '—'}</span>
            </div>
            <div class="view-detail-row">
                <span class="view-detail-label"><i class="fas fa-phone-alt" style="width:14px;"></i> Contact</span>
                <span class="view-detail-value">${r.contact_number || '—'}</span>
            </div>
            <div class="view-detail-row">
                <span class="view-detail-label"><i class="fas fa-vote-yea" style="width:14px;"></i> Voter</span>
                <span class="view-detail-value">
                    ${voterBadge(r.is_voter)}
                    ${r.voter_id ? `<code style="font-size:0.78rem;background:#f0f4f8;padding:2px 6px;border-radius:4px;margin-left:6px;">${r.voter_id}</code>` : ''}
                </span>
            </div>
            <div class="view-detail-row">
                <span class="view-detail-label"><i class="fas fa-home" style="width:14px;"></i> Household</span>
                <span class="view-detail-value">${r.household_id ? '#' + r.household_id : '—'}</span>
            </div>
            <div class="view-detail-row">
                <span class="view-detail-label"><i class="fas fa-map-pin" style="width:14px;"></i> Purok</span>
                <span class="view-detail-value">${r.address_line1 || '—'}</span>
            </div>
            <div class="view-detail-row">
                <span class="view-detail-label"><i class="fas fa-map-marker-alt" style="width:14px;"></i> Address</span>
                <span class="view-detail-value">Brgy. ${r.barangay || 'Isio'}, Cauayan, Negros Occidental</span>
            </div>
        `);
        updateCSRF(response);
    }, 'json').fail(function (xhr) {
        console.error(xhr.responseText);
        showToast('error', 'Failed to load resident profile.');
    });
});

// ================= EDIT — FETCH & POPULATE =================
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
            $('#editContactNumber').val(r.contact_number);
            $('#editHouseholdId').val(r.household_id);
            $('#editStatus').val(r.status);
            $('#editAddressLine1').val(r.address_line1);
            $('#editBarangay').val(r.barangay || 'Isio');
            $('#editResidentModal').modal('show');
            updateCSRF(response);
        } else {
            showToast('error', response.message || 'Failed to fetch resident.');
        }
    }, 'json').fail(function (xhr) {
        console.error(xhr.responseText);
        showToast('error', 'Failed to fetch resident.');
    });
});

// ================= UPDATE (EDIT SUBMIT) =================
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
            showToast('error', response.message || 'Failed to update resident.');
        }
        updateCSRF(response);
    }, 'json').fail(function (xhr) {
        console.error(xhr.responseText);
        showToast('error', 'Server error. Please try again.');
    }).always(function () {
        $btn.prop('disabled', false).html('<i class="fas fa-save"></i> Update Resident');
    });
});

// ================= DELETE =================
$(document).on('click', '.delete-resident', function () {
    const id = $(this).data('id');
    if (!id) { showToast('error', 'Invalid ID'); return; }

    if (!confirm('Delete this resident? This action cannot be undone.')) return;

    $.post(baseUrl + 'residents/delete/' + id, {
        csrf_test_name: getCsrfToken()
    }, function (response) {
        if (response.status === 'success') {
            showToast('success', response.message);
            residentsTable.ajax.reload(null, false);
            loadResidentStats();
        } else {
            showToast('error', response.message || 'Failed to delete resident.');
        }
        updateCSRF(response);
    }, 'json').fail(function (xhr) {
        console.error(xhr.responseText);
        showToast('error', 'Delete failed. Please try again.');
    });
});
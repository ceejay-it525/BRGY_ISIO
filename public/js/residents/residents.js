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
    var today = new Date(), dob = new Date(birthdate);
    var age = today.getFullYear() - dob.getFullYear();
    var m = today.getMonth() - dob.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) age--;
    return age;
}

function formatDate(dateStr) {
    if (!dateStr) return '—';
    var d = new Date(dateStr);
    return d.toLocaleDateString('en-PH', { year: 'numeric', month: 'short', day: 'numeric' });
}

function genderBadge(data) {
    var map = {
        'Male':   ['badge-male',   'fas fa-mars'],
        'Female': ['badge-female', 'fas fa-venus'],
        'Other':  ['badge-other',  'fas fa-genderless']
    };
    var cls  = (map[data] || ['badge-other', 'fas fa-genderless'])[0];
    var icon = (map[data] || ['badge-other', 'fas fa-genderless'])[1];
    return '<span class="badge-custom ' + cls + '"><i class="' + icon + '"></i> ' + (data || '—') + '</span>';
}

function voterBadge(d) {
    return (d == 1)
        ? '<span class="badge-custom badge-voter-yes"><i class="fas fa-check"></i> Yes</span>'
        : '<span class="badge-custom badge-voter-no"><i class="fas fa-times"></i> No</span>';
}

function statusBadge(d) {
    var map = {
        'Active':      'badge-active',
        'Work':        'badge-active',
        'Functional':  'badge-active',
        'Inactive':    'badge-inactive',
        'Deceased':    'badge-deceased',
        'Transferred': 'badge-transferred'
    };
    return '<span class="badge-custom ' + (map[d] || 'badge-inactive') + '">' + (d || '—') + '</span>';
}

function avatarClass(gender) {
    return gender === 'Male' ? 'avatar-m' : (gender === 'Female' ? 'avatar-f' : 'avatar-o');
}

function fullName(r) {
    return ((r.first_name || '') + ' ' + (r.middle_name ? r.middle_name + ' ' : '') + (r.last_name || '') + (r.suffix ? ' ' + r.suffix : '')).trim();
}

// ================= BASE URL (ensure trailing slash) =================
var _baseUrl = (typeof baseUrl !== 'undefined') ? baseUrl : '/';
if (_baseUrl.charAt(_baseUrl.length - 1) !== '/') {
    _baseUrl = _baseUrl + '/';
}

// ================= HERO DATE =================
$(function () {
    var opts = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    $('#heroDate').text(new Date().toLocaleDateString('en-PH', opts));
});

// ================= ACTIVE FILTER STATE =================
var viewType = 'all';

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
var residentsTable;

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
            emptyTable:  '<div class="empty-state"><i class="fas fa-users"></i><p>No residents found</p></div>',
            zeroRecords: '<div class="empty-state"><i class="fas fa-search"></i><p>No matching records found</p></div>'
        },
        ajax: {
            url:  _baseUrl + 'residents/fetchRecords',
            type: 'POST',
            data: function (d) {
                d.csrf_test_name = getCsrfToken();
                d.view_type      = viewType;
            },
            dataSrc: function (json) {
                updateCSRF(json);
                return json.data;
            },
            error: function (xhr) {
                console.error('DataTable error:', xhr.responseText);
                showToast('error', 'Failed to load data. Please refresh.');
            }
        },
        columns: [
            {
                data: 'row_number',
                render: function (d) {
                    return '<span class="row-num">' + d + '</span>';
                }
            },
            { data: 'id', visible: false },
            {
                data: null,
                render: function (row) {
                    var initials = getInitials(row.first_name, row.last_name);
                    var age      = getAge(row.birthdate);
                    var avCls    = avatarClass(row.gender);
                    var name     = fullName(row);
                    return '<div class="resident-name">' +
                               '<div class="resident-avatar ' + avCls + '">' + initials + '</div>' +
                               '<div>' +
                                   '<div class="resident-fullname">' + name + '</div>' +
                                   '<div class="resident-sub">Age ' + age + ' &bull; HH #' + (row.household_id || '—') + '</div>' +
                               '</div>' +
                           '</div>';
                }
            },
            {
                data: 'birthdate',
                render: function (d) {
                    return d
                        ? '<span style="font-size:0.82rem;color:#4a6070;">' + formatDate(d) + '</span>' +
                          '<div style="font-size:0.72rem;color:#8fa8be;">' + getAge(d) + ' yrs old</div>'
                        : '—';
                }
            },
            { data: 'gender',      render: genderBadge },
            {
                data: 'civil_status',
                render: function (d) {
                    return '<span style="font-size:0.83rem;">' + (d || '—') + '</span>';
                }
            },
            {
                data: 'contact_number',
                render: function (d) {
                    return d
                        ? '<span style="font-size:0.83rem;"><i class="fas fa-phone-alt" style="font-size:0.7rem;margin-right:3px;color:#1d6fa4;"></i>' + d + '</span>'
                        : '<span style="color:#c0ccd8;font-size:0.78rem;">N/A</span>';
                }
            },
            { data: 'is_voter', render: voterBadge },
            {
                data: 'voter_id',
                render: function (d) {
                    return d
                        ? '<code style="font-size:0.78rem;background:#f0f4f8;padding:2px 6px;border-radius:4px;">' + d + '</code>'
                        : '<span style="color:#c0ccd8;font-size:0.78rem;">N/A</span>';
                }
            },
            {
                data: null,
                render: function (row) {
                    var purok = row.address_line1 || '—';
                    var brgy  = row.barangay || 'Isio';
                    return '<div class="address-cell">' +
                               '<div class="address-purok"><i class="fas fa-map-pin" style="font-size:0.68rem;margin-right:3px;"></i>' + purok + '</div>' +
                               '<div class="address-brgy">Brgy. ' + brgy + ', Cauayan, Neg. Occ.</div>' +
                           '</div>';
                }
            },
            { data: 'status', render: statusBadge },
            {
                data: null, orderable: false, searchable: false,
                render: function (row) {
                    return '<div style="display:flex;gap:4px;">' +
                        '<button class="btn-action btn-view view-resident" data-id="' + row.id + '" title="View Profile"><i class="fas fa-eye"></i></button>' +
                        '<button class="btn-action btn-edit edit-resident" data-id="' + row.id + '" title="Edit"><i class="far fa-edit"></i></button>' +
                        '<button class="btn-action btn-delete delete-resident" data-id="' + row.id + '" title="Delete"><i class="fas fa-trash-alt"></i></button>' +
                    '</div>';
                }
            }
        ],
        // ── Load stats AFTER DataTable first draw completes ──
        initComplete: function () {
            loadResidentStats();
        }
    });
});

// ================= QUICK FILTERS =================
$(document).on('click', '.filter-pill', function () {
    $('.filter-pill').removeClass('active');
    $(this).addClass('active');

    var f = $(this).data('filter');
    viewType = f;

    var label = '';
    if (f === 'all') {
        label = '';
    } else if (f === 'voter-yes') {
        label = '— Voters: Yes';
    } else if (f === 'voter-no') {
        label = '— Voters: No';
    } else if (f.indexOf('purok-') === 0) {
        var purokNum   = f.replace('purok-', '');
        var purokLabel = (purokNum === '7a') ? 'Purok 7A' : (purokNum === '7b') ? 'Purok 7B' : 'Purok ' + purokNum;
        label = '— ' + purokLabel;
    } else if (f.indexOf('gender-') === 0) {
        var g = f.replace('gender-', '');
        label = '— ' + g.charAt(0).toUpperCase() + g.slice(1);
    } else {
        label = '— ' + f;
    }

    $('#tableFilterLabel').text(label);
    residentsTable.ajax.reload(null, false);
});

// ================= STAT CARDS =================
function loadResidentStats() {
    // Show loading spinner dots while fetching
    $('#totalResidents, #statActive, #statVoters, #statGender')
        .html('<i class="fas fa-spinner fa-spin" style="font-size:0.9rem;color:#a0b8cc;"></i>');

    $.ajax({
        url:      _baseUrl + 'residents/residentStats',
        type:     'GET',
        dataType: 'json',
        timeout:  15000,
        success: function (data) {
            if (!data || typeof data !== 'object') {
                $('#totalResidents, #statActive, #statVoters, #statGender').text('0');
                return;
            }
            var total   = parseInt(data.total_residents)  || 0;
            var active  = parseInt(data.active_residents) || 0;
            var voters  = parseInt(data.total_voters)     || 0;
            var female  = parseInt(data.female_residents) || 0;
            var male    = parseInt(data.male_residents)   || 0;

            // Animate count-up for a polished feel
            animateCount('#totalResidents', total);
            animateCount('#statActive',     active);
            animateCount('#statVoters',     voters);
            // Gender stat shows F / M — no count-up, just set directly
            $('#statGender').text(female + ' / ' + male);
        },
        error: function (xhr, status, err) {
            console.error('[residentStats] AJAX failed:', status, err);
            console.error('Response:', xhr.responseText);
            $('#totalResidents, #statActive, #statVoters, #statGender').text('—');
            // Uncomment below to show a toast on stats failure:
            // showToast('warning', 'Could not load stat cards.');
        }
    });
}

// Simple count-up animation for stat numbers
function animateCount(selector, target) {
    var $el      = $(selector);
    var duration = 600; // ms
    var start    = 0;
    var increment = target / (duration / 16);
    var current  = start;

    var timer = setInterval(function () {
        current += increment;
        if (current >= target) {
            current = target;
            clearInterval(timer);
        }
        $el.text(Math.floor(current).toLocaleString());
    }, 16);
}

function refreshReportStats() { loadResidentStats(); }

// ================= REFRESH =================
$('#btnRefreshTable').on('click', function () {
    var $icon = $(this).find('i');
    $icon.addClass('fa-spin');
    residentsTable.ajax.reload(function () {
        $icon.removeClass('fa-spin');
        showToast('info', 'Table refreshed');
    }, false);
    loadResidentStats();
});

// ================= EXPORT CSV =================
$('#btnExportCSV').on('click', function () {
    var params = new URLSearchParams({ view_type: viewType });
    window.location.href = _baseUrl + 'residents/export?' + params.toString();
});

// ================= PRINT =================
$('#btnPrint').on('click', function () {
    var params = new URLSearchParams({ view_type: viewType });
    window.open(_baseUrl + 'residents/printView?' + params.toString(), '_blank');
});

// ================= ADD =================
$('#addResidentForm').on('submit', function (e) {
    e.preventDefault();
    var $btn = $(this).find('[type=submit]');
    $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving…');

    $.post(_baseUrl + 'residents/save', $(this).serialize(), function (response) {
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
    var id = $(this).data('id');
    if (!id) { showToast('error', 'Invalid ID'); return; }

    $('#viewResidentBody').html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x text-muted"></i></div>');
    $('#viewResidentModal').modal('show');

    $.get(_baseUrl + 'residents/get/' + id, function (response) {
        if (response.status !== 'success') {
            showToast('error', response.message || 'Failed to load resident.');
            return;
        }
        var r        = response.data;
        var age      = getAge(r.birthdate);
        var avCls    = avatarClass(r.gender);
        var initials = getInitials(r.first_name, r.last_name);
        var name     = fullName(r);

        $('#viewResidentBody').html(
            '<div style="display:flex;align-items:center;gap:16px;margin-bottom:20px;padding-bottom:16px;border-bottom:1px solid #e8edf3;">' +
                '<div class="resident-avatar ' + avCls + '" style="width:56px;height:56px;border-radius:14px;font-size:1.1rem;">' + initials + '</div>' +
                '<div>' +
                    '<div style="font-size:1.05rem;font-weight:800;color:#1a2e42;">' + name + '</div>' +
                    '<div style="font-size:0.78rem;color:#8fa8be;margin-top:4px;">' +
                        genderBadge(r.gender) + ' &nbsp; ' + statusBadge(r.status) +
                    '</div>' +
                '</div>' +
            '</div>' +
            '<div class="view-detail-row"><span class="view-detail-label"><i class="fas fa-birthday-cake" style="width:14px;"></i> Birthdate</span><span class="view-detail-value">' + formatDate(r.birthdate) + ' &nbsp;<small style="color:#8fa8be;">(' + age + ' years old)</small></span></div>' +
            '<div class="view-detail-row"><span class="view-detail-label"><i class="fas fa-heart" style="width:14px;"></i> Civil Status</span><span class="view-detail-value">' + (r.civil_status || '—') + '</span></div>' +
            '<div class="view-detail-row"><span class="view-detail-label"><i class="fas fa-phone-alt" style="width:14px;"></i> Contact</span><span class="view-detail-value">' + (r.contact_number || '—') + '</span></div>' +
            '<div class="view-detail-row"><span class="view-detail-label"><i class="fas fa-vote-yea" style="width:14px;"></i> Voter</span><span class="view-detail-value">' + voterBadge(r.is_voter) + (r.voter_id ? ' <code style="font-size:0.78rem;background:#f0f4f8;padding:2px 6px;border-radius:4px;margin-left:6px;">' + r.voter_id + '</code>' : '') + '</span></div>' +
            '<div class="view-detail-row"><span class="view-detail-label"><i class="fas fa-home" style="width:14px;"></i> Household</span><span class="view-detail-value">' + (r.household_id ? '#' + r.household_id : '—') + '</span></div>' +
            '<div class="view-detail-row"><span class="view-detail-label"><i class="fas fa-map-pin" style="width:14px;"></i> Purok</span><span class="view-detail-value">' + (r.address_line1 || '—') + '</span></div>' +
            '<div class="view-detail-row"><span class="view-detail-label"><i class="fas fa-map-marker-alt" style="width:14px;"></i> Address</span><span class="view-detail-value">Brgy. ' + (r.barangay || 'Isio') + ', Cauayan, Negros Occidental</span></div>'
        );
        updateCSRF(response);
    }, 'json').fail(function (xhr) {
        console.error(xhr.responseText);
        showToast('error', 'Failed to load resident profile.');
    });
});

// ================= EDIT — FETCH & POPULATE =================
$(document).on('click', '.edit-resident', function () {
    var id = $(this).data('id');
    if (!id) { showToast('error', 'Invalid ID'); return; }

    $.get(_baseUrl + 'residents/get/' + id, function (response) {
        if (response.status === 'success') {
            var r = response.data;
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
    var $btn = $(this).find('[type=submit]');
    $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving…');

    $.post(_baseUrl + 'residents/update', $(this).serialize(), function (response) {
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
    var id = $(this).data('id');
    if (!id) { showToast('error', 'Invalid ID'); return; }

    if (!confirm('Delete this resident? This action cannot be undone.')) return;

    $.post(_baseUrl + 'residents/delete/' + id, {
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

// ================= TOAST =================
function showToast(type, message) {
    if (typeof toastr === 'undefined') {
        alert(message);
        return;
    }
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: 'toast-top-right',
        timeOut: '3000'
    };
    (toastr[type] || toastr.info)(message);
}

// ================= STATE =================
let officials = [];
let selectedOfficialId = null;

// ================= HELPERS =================
function fullName(r) {
    return [r.first_name, r.middle_name, r.last_name].filter(Boolean).join(' ');
}

function formatTerm(r) {
    return (r.term_start || 'N/A') + ' — ' + (r.term_end || 'Present');
}

function getSelectedOfficial() {
    return officials.find(r => String(r.id) === String(selectedOfficialId)) || null;
}

function updateCSRF(response) {
    if (response && response.csrf_hash) {
        $('#csrfTokenField').val(response.csrf_hash);
        $('input[name=csrf_test_name]').val(response.csrf_hash);
    }
}

// ================= ENDPOINTS =================
// baseUrl is set by the inline script in the view
const endpoints = {
    fetchRecords : () => baseUrl + 'barangay-officials/fetchRecords',
    save         : () => baseUrl + 'barangay-officials/save',
    edit         : (id) => baseUrl + 'barangay-officials/edit/' + id,   // GET single record
    update       : () => baseUrl + 'barangay-officials/update',
    delete       : (id) => baseUrl + 'barangay-officials/delete/' + id,
};

// ================= DETAIL PANEL =================
function renderSelectedOfficial(record) {
    if (!record) {
        $('#selectedAvatar').html('?');
        $('#selectedName').text('No official selected');
        $('#selectedPosition').text('Choose an official card to view details.');
        $('#selectedStatus, #selectedTerm, #selectedContact, #selectedEmail').text('—');
        $('#selectedAddress').text('—');
        return;
    }

    if (record.photo_url) {
        $('#selectedAvatar').html('<img src="' + record.photo_url + '" alt="' + fullName(record) + '">');
    } else {
        $('#selectedAvatar').html(record.first_name ? record.first_name.charAt(0).toUpperCase() : 'O');
    }

    $('#selectedName').text(fullName(record));
    $('#selectedPosition').text(record.position || 'Position not specified');
    $('#selectedStatus').text(record.status || 'Inactive');
    $('#selectedTerm').text(formatTerm(record));
    $('#selectedContact').text(record.contact_number || '—');
    $('#selectedEmail').text(record.email || '—');
    $('#selectedAddress').text(record.address || 'No address available');
}

// ================= CARDS =================
function renderOfficialCards() {
    const container = $('#officialCards');
    container.empty();

    if (!officials.length) {
        container.append(
            '<div class="col-12"><div class="alert alert-secondary mb-0">' +
            'No officials found. Use the search box or add a new official.</div></div>'
        );
        renderSelectedOfficial(null);
        return;
    }

    officials.forEach(function (record) {
        const isSelected = String(selectedOfficialId) === String(record.id);
        const cardBorder  = isSelected ? 'border-success shadow' : 'border-light';
        const statusClass = record.status === 'Active' ? 'badge bg-success' : 'badge bg-warning text-dark';

        const avatarHtml = record.photo_url
            ? '<img src="' + record.photo_url + '" alt="' + fullName(record) + '" class="official-card-avatar">'
            : '<div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:54px;height:54px;font-size:20px;">' +
              (record.first_name ? record.first_name.charAt(0).toUpperCase() : 'O') + '</div>';

        const card = [
            '<div class="col-sm-6 col-xl-4 mb-3">',
            '  <div class="card official-card h-100 ' + cardBorder + '" data-id="' + record.id + '" style="cursor:pointer;">',
            '    <div class="card-body">',
            '      <div class="d-flex align-items-start justify-content-between mb-3">',
            '        <div>',
            '          <h5 class="mb-1">' + fullName(record) + '</h5>',
            '          <p class="text-muted mb-0">' + (record.position || 'No position') + '</p>',
            '        </div>',
            '        <span class="' + statusClass + ' badge-pill py-2 px-3" style="font-size:.75rem;white-space:nowrap;">' + (record.status || 'Inactive') + '</span>',
            '      </div>',
            '      <div class="d-flex align-items-center mb-3">',
            '        ' + avatarHtml,
            '        <div class="ml-3">',
            '          <div class="text-muted" style="font-size:.85rem;">Contact</div>',
            '          <div>' + (record.contact_number || '—') + '</div>',
            '        </div>',
            '      </div>',
            '      <div class="mb-3 small">',
            '        <div class="text-muted">Email</div>',
            '        <div>' + (record.email || '—') + '</div>',
            '        <div class="text-muted mt-2">Address</div>',
            '        <div>' + (record.address || '—') + '</div>',
            '      </div>',
            '      <div class="d-flex justify-content-between align-items-center">',
            '        <div class="small text-muted">' + formatTerm(record) + '</div>',
            '        <div class="btn-group" role="group">',
            '          <button type="button" class="btn btn-sm btn-outline-primary view-official" data-id="' + record.id + '" title="View"><i class="fas fa-eye"></i></button>',
            '          <button type="button" class="btn btn-sm btn-warning edit-official" data-id="' + record.id + '" title="Edit"><i class="far fa-edit"></i></button>',
            '          <button type="button" class="btn btn-sm btn-danger delete-official" data-id="' + record.id + '" title="Delete"><i class="fas fa-trash"></i></button>',
            '        </div>',
            '      </div>',
            '    </div>',
            '  </div>',
            '</div>'
        ].join('\n');

        container.append(card);
    });
}

// ================= FETCH =================
function fetchOfficials(searchTerm) {
    searchTerm = searchTerm || '';

    $.post(endpoints.fetchRecords(), {
        draw           : 1,
        start          : 0,
        length         : 1000,
        'search[value]': searchTerm,
        csrf_test_name : $('#csrfTokenField').val()
    }, function (response) {
        updateCSRF(response);
        officials = response.data || [];

        // Keep or reset selected
        if (!selectedOfficialId && officials.length) {
            selectedOfficialId = String(officials[0].id);
        } else if (!getSelectedOfficial() && officials.length) {
            selectedOfficialId = String(officials[0].id);
        }

        renderOfficialCards();
        renderSelectedOfficial(getSelectedOfficial());
    }, 'json').fail(function () {
        showToast('error', 'Unable to load officials.');
    });
}

// ================= PUROK HELPERS =================
function togglePurokDropdown(position, modalType) {
    const groupId  = modalType === 'add' ? '#addPurokGroup'  : '#editPurokGroup';
    const selectId = modalType === 'add' ? '#addPurok'       : '#editPurok';

    if (position === 'Purok President') {
        $(groupId).css('display', 'flex');
        $(selectId).attr('required', true);
    } else {
        $(groupId).css('display', 'none');
        $(selectId).attr('required', false).val('');
    }
}

function parsePurokFromPosition(position) {
    if (!position) return { base: '', purok: '' };
    const m = position.match(/^(Purok President)\s+(.+)$/);
    return m ? { base: m[1], purok: m[2] } : { base: position, purok: '' };
}

function buildPositionValue(position, purok) {
    if (position === 'Purok President' && purok) {
        return 'Purok President ' + purok;
    }
    return position;
}

// ================= DOCUMENT READY =================
$(document).ready(function () {

    // Search
    $('#officialsSearchBtn').on('click', function () {
        fetchOfficials($('#officialsSearch').val().trim());
    });
    $('#officialsSearch').on('keypress', function (e) {
        if (e.which === 13) { e.preventDefault(); fetchOfficials($(this).val().trim()); }
    });
    $('#officialsSearch').on('input', function () {
        if (!$(this).val()) { fetchOfficials(''); }
    });

    // Position dropdowns
    $(document).on('change', '#addPosition',  function () { togglePurokDropdown($(this).val(), 'add');  });
    $(document).on('change', '#editPosition', function () { togglePurokDropdown($(this).val(), 'edit'); });

    // Card click — select official (ignore btn-group clicks)
    $(document).on('click', '.official-card', function (e) {
        if ($(e.target).closest('.btn-group').length) return;
        const id = String($(this).data('id') || '');
        if (!id) return;
        selectedOfficialId = id;
        renderOfficialCards();
        renderSelectedOfficial(getSelectedOfficial());
    });

    // VIEW button — same as card click, highlights and shows detail panel
    $(document).on('click', '.view-official', function (e) {
        e.stopPropagation();
        const id = String($(this).data('id') || '');
        if (!id) return;
        selectedOfficialId = id;
        renderOfficialCards();
        renderSelectedOfficial(getSelectedOfficial());
        // Scroll detail panel into view on mobile
        $('html, body').animate({ scrollTop: $('#selectedName').offset().top - 80 }, 300);
    });

    // Initial load
    fetchOfficials();
});

// ================= ADD =================
$('#addOfficialForm').on('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    const position = formData.get('position');
    const purok    = formData.get('purok');
    formData.set('position', buildPositionValue(position, purok));
    formData.delete('purok');

    const $btn = $(this).find('[type=submit]').prop('disabled', true).text('Saving…');

    $.ajax({
        url         : endpoints.save(),
        type        : 'POST',
        data        : formData,
        processData : false,
        contentType : false,
        dataType    : 'json',
        success(response) {
            updateCSRF(response);
            if (response.status === 'success') {
                $('#AddNewModal').modal('hide');
                $('#addOfficialForm')[0].reset();
                togglePurokDropdown('', 'add');
                showToast('success', response.message);
                fetchOfficials($('#officialsSearch').val().trim());
            } else {
                showToast('error', response.message || 'Save failed.');
            }
        },
        error() { showToast('error', 'Save failed. Please try again.'); },
        complete() { $btn.prop('disabled', false).html('<i class="fa fa-save"></i> Save official'); }
    });
});

// ================= EDIT (open modal) =================
$(document).on('click', '.edit-official', function (e) {
    e.stopPropagation();
    const id = Number($(this).data('id'));
    if (!id) { showToast('error', 'Invalid official ID.'); return; }

    $.get(endpoints.edit(id), function (response) {
        if (response.status !== 'success') {
            showToast('error', response.message || 'Could not load official.');
            return;
        }
        const r      = response.data;
        const parsed = parsePurokFromPosition(r.position);

        $('#editOfficialId').val(r.id);
        $('#editFirstName').val(r.first_name   || '');
        $('#editMiddleName').val(r.middle_name  || '');
        $('#editLastName').val(r.last_name      || '');
        $('#editPosition').val(parsed.base      || '').trigger('change');
        $('#editPurok').val(parsed.purok        || '');
        $('#editTermStart').val(r.term_start    || '');
        $('#editTermEnd').val(r.term_end        || '');
        $('#editContact').val(r.contact_number  || '');
        $('#editEmail').val(r.email             || '');
        $('#editAddress').val(r.address         || '');
        $('#editStatus').val(r.status           || 'Active');

        togglePurokDropdown(parsed.base || '', 'edit');
        updateCSRF(response);

        $('#editOfficialModal').modal('show');
    }).fail(function () { showToast('error', 'Failed to load official.'); });
});

// ================= UPDATE =================
$('#editOfficialForm').on('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    const position = formData.get('position');
    const purok    = formData.get('purok');
    formData.set('position', buildPositionValue(position, purok));
    formData.delete('purok');

    const $btn = $(this).find('[type=submit]').prop('disabled', true).text('Updating…');

    $.ajax({
        url         : endpoints.update(),
        type        : 'POST',
        data        : formData,
        processData : false,
        contentType : false,
        dataType    : 'json',
        success(response) {
            updateCSRF(response);
            if (response.status === 'success') {
                $('#editOfficialModal').modal('hide');
                showToast('success', response.message);
                selectedOfficialId = String($('#editOfficialId').val());
                fetchOfficials($('#officialsSearch').val().trim());
            } else {
                showToast('error', response.message || 'Update failed.');
            }
        },
        error() { showToast('error', 'Update failed. Please try again.'); },
        complete() { $btn.prop('disabled', false).html('<i class="fa fa-save"></i> Update official'); }
    });
});

// ================= DELETE =================
$(document).on('click', '.delete-official', function (e) {
    e.stopPropagation();
    const id = Number($(this).data('id'));
    if (!id) { showToast('error', 'Invalid official ID.'); return; }

    if (!confirm('Are you sure you want to delete this official? This action cannot be undone.')) return;

    $.post(endpoints.delete(id), {
        csrf_test_name: $('#csrfTokenField').val()
    }, function (response) {
        updateCSRF(response);
        if (response.status === 'success') {
            showToast('success', response.message);
            if (String(selectedOfficialId) === String(id)) {
                selectedOfficialId = null;
            }
            fetchOfficials($('#officialsSearch').val().trim());
        } else {
            showToast('error', response.message || 'Delete failed.');
        }
    }, 'json').fail(function () { showToast('error', 'Delete failed. Please try again.'); });
});

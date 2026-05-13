// ================= TOAST =================
function showToast(type = 'info', message = '') {
    if (typeof toastr === 'undefined') {
        console.error('Toastr not loaded');
        alert(message);
        return;
    }

    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: "toast-top-right",
        timeOut: "3000"
    };

    toastr[type] ? toastr[type](message) : toastr.info(message);
}

// ================= OFFICIAL CARDS =================
let officials = [];
let selectedOfficialId = null;

function fullName(record) {
    return [record.first_name, record.middle_name, record.last_name].filter(Boolean).join(' ');
}

function formatTerm(record) {
    const start = record.term_start || 'N/A';
    const end = record.term_end || 'Present';
    return `${start} — ${end}`;
}

function renderSelectedOfficial(record) {
    if (!record) {
        $('#selectedAvatar').html('N/A');
        $('#selectedName').text('No official selected');
        $('#selectedPosition').text('Choose an official card to view details.');
        $('#selectedStatus').text('—');
        $('#selectedTerm').text('—');
        $('#selectedContact').text('—');
        $('#selectedEmail').text('—');
        $('#selectedAddress').text('—');
        return;
    }

    if (record.photo_url) {
        $('#selectedAvatar').html(`<img src="${record.photo_url}" alt="${fullName(record)}">`);
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

function renderOfficialCards() {
    const container = $('#officialCards');
    container.empty();

    if (!officials.length) {
        container.append(`
            <div class="col-12">
                <div class="alert alert-secondary mb-0">
                    No officials found. Use the search box or add a new official.
                </div>
            </div>
        `);
        renderSelectedOfficial(null);
        return;
    }

    officials.forEach((record) => {
        const hasSelection = String(selectedOfficialId) === String(record.id);
        const cardClass = hasSelection ? 'border-success shadow-sm' : 'border-light';
        const statusClass = record.status === 'Active' ? 'badge bg-success' : 'badge bg-warning';
        const avatarHtml = record.photo_url
            ? `<img src="${record.photo_url}" alt="${fullName(record)}" class="official-card-avatar">`
            : `<div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:54px;height:54px;font-size:20px;">${record.first_name ? record.first_name.charAt(0).toUpperCase() : 'O'}</div>`;

        const card = `
            <div class="col-sm-6 col-xl-4">
                <div class="card official-card h-100 ${cardClass}" data-id="${record.id}" style="cursor:pointer;">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div>
                                <h5 class="mb-1">${fullName(record)}</h5>
                                <p class="text-muted mb-0">${record.position || 'No position'}</p>
                            </div>
                            <div class="${statusClass} badge-pill py-2 px-3 text-uppercase" style="font-size:0.75rem;">${record.status || 'Inactive'}</div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            ${avatarHtml}
                            <div class="ml-3">
                                <div class="text-muted" style="font-size:0.85rem;">Contact</div>
                                <div>${record.contact_number || '—'}</div>
                            </div>
                        </div>
                        <div class="mb-3 small">
                            <div class="text-muted">Email</div>
                            <div>${record.email || '—'}</div>
                            <div class="text-muted mt-2">Address</div>
                            <div>${record.address || '—'}</div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="small text-muted">${formatTerm(record)}</div>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-primary view-official" data-id="${record.id}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-warning edit-official" data-id="${record.id}">
                                    <i class="far fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger delete-official" data-id="${record.id}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        container.append(card);
    });
}

function getSelectedOfficial() {
    return officials.find((record) => String(record.id) === String(selectedOfficialId)) || null;
}

function updateCSRF(response) {
    if (response && response.csrf_hash) {
        $('#csrfTokenField').val(response.csrf_hash);
        $('input[name=csrf_test_name]').val(response.csrf_hash);
    }
}

const endpoints = {
    fetchRecords: baseUrl + 'barangay-officials/fetchRecords',
    save: baseUrl + 'barangay-officials/save',
    get: (id) => baseUrl + 'barangay-officials/get/' + id,
    update: baseUrl + 'barangay-officials/update',
    delete: (id) => baseUrl + 'barangay-officials/delete/' + id,
};

function fetchOfficials(searchTerm = '') {
    const payload = {
        draw: 1,
        start: 0,
        length: 1000,
        'search[value]': searchTerm,
        csrf_test_name: $('#csrfTokenField').val()
    };

    $.post(endpoints.fetchRecords, payload, function (response) {
        updateCSRF(response);
        officials = response.data || [];

        if (!selectedOfficialId && officials.length) {
            selectedOfficialId = String(officials[0].id);
        }

        if (!getSelectedOfficial() && officials.length) {
            selectedOfficialId = String(officials[0].id);
        }

        renderOfficialCards();
        renderSelectedOfficial(getSelectedOfficial());
    }, 'json').fail(function (xhr, status, error) {
        console.error('Failed fetching officials:', status, error, xhr.responseText);
        showToast('error', 'Unable to load officials');
    });
}

// ================= PUROK SELECTION =================
function togglePurokDropdown(position, modalType = 'add') {
    const groupId = modalType === 'add' ? '#addPurokGroup' : '#editPurokGroup';
    const selectId = modalType === 'add' ? '#addPurok' : '#editPurok';
    
    if (position === 'Purok President' || position === 'Kagawad') {
        $(groupId).show();
        $(selectId).attr('required', true);
    } else {
        $(groupId).hide();
        $(selectId).attr('required', false);
        $(selectId).val('');
    }
}

function parsePurokFromPosition(position) {
    if (!position) return { base: '', purok: '' };
    
    // Check if it's a legacy format like "Purok President 1", "Purok President 7A", etc.
    const purokMatch = position.match(/^(Purok President|Kagawad)\s+(.+)$/);
    if (purokMatch) {
        return { base: purokMatch[1], purok: purokMatch[2] };
    }
    
    return { base: position, purok: '' };
}

function buildPositionValue(position, purok) {
    if (position === 'Purok President' && purok) {
        return `Purok President ${purok}`;
    }
    if (position === 'Kagawad' && purok) {
        return `Kagawad ${purok}`;
    }
    return position;
}

$(document).ready(function () {
    // Position dropdown change - Add Modal
    $(document).on('change', '#addPosition', function () {
        togglePurokDropdown($(this).val(), 'add');
    });

    // Position dropdown change - Edit Modal
    $(document).on('change', '#editPosition', function () {
        togglePurokDropdown($(this).val(), 'edit');
    });

    $('#officialsSearchBtn').on('click', function () {
        fetchOfficials($('#officialsSearch').val().trim());
    });

    $('#officialsSearch').on('keypress', function (e) {
        if (e.which === 13) {
            e.preventDefault();
            fetchOfficials($('#officialsSearch').val().trim());
        }
    });

    $(document).on('click', '.official-card', function (e) {
        if ($(e.target).closest('.btn-group').length) {
            return;
        }
        const id = String($(this).data('id') || '');
        if (!id) return;

        selectedOfficialId = id;
        renderOfficialCards();
        renderSelectedOfficial(getSelectedOfficial());
    });

    $(document).on('click', '.view-official', function (e) {
        e.stopPropagation();
        const id = String($(this).data('id') || '');
        if (!id) return;

        selectedOfficialId = id;
        renderOfficialCards();
        renderSelectedOfficial(getSelectedOfficial());
    });

    fetchOfficials();
});

// ================= ADD FORM =================
$('#addOfficialForm').on('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    
    // Combine position and purok if needed
    const position = formData.get('position');
    const purok = formData.get('purok');
    const combinedPosition = buildPositionValue(position, purok);
    formData.set('position', combinedPosition);
    formData.delete('purok');

    $.ajax({
        url: endpoints.save,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success(response) {
            if (response.status === 'success') {
                $('#AddNewModal').modal('hide');
                $('#addOfficialForm')[0].reset();
                $('#addPurokGroup').hide();
                showToast('success', response.message);
                fetchOfficials($('#officialsSearch').val().trim());
            } else {
                showToast('error', response.message || 'Save failed');
            }
            updateCSRF(response);
        },
        error() {
            showToast('error', 'Save failed');
        }
    });
});

// ================= EDIT =================
$(document).on('click', '.edit-official', function (e) {
    e.stopPropagation();
    const id = Number($(this).data('id'));

    if (!id) {
        showToast('error', 'Invalid official ID');
        return;
    }

    $.get(endpoints.get(id), function (response) {
        if (response.status === 'success') {
            const r = response.data;
            
            // Parse position if it contains purok info
            const parsed = parsePurokFromPosition(r.position);

            $('#editOfficialId').val(r.id);
            $('#editFirstName').val(r.first_name || '');
            $('#editMiddleName').val(r.middle_name || '');
            $('#editLastName').val(r.last_name || '');
            $('#editPosition').val(parsed.base || '');
            $('#editPurok').val(parsed.purok || '');
            $('#editTermStart').val(r.term_start || '');
            $('#editTermEnd').val(r.term_end || '');
            $('#editContact').val(r.contact_number || '');
            $('#editEmail').val(r.email || '');
            $('#editAddress').val(r.address || '');
            $('#editStatus').val(r.status || 'Active');
            
            // Show/hide purok dropdown
            togglePurokDropdown(parsed.base || '', 'edit');

            $('#editOfficialModal').modal('show');
        } else {
            showToast('error', response.message);
        }
    }).fail(function () {
        showToast('error', 'Failed to load official');
    });
});

// ================= UPDATE =================
$('#editOfficialForm').on('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    
    // Combine position and purok if needed
    const position = formData.get('position');
    const purok = formData.get('purok');
    const combinedPosition = buildPositionValue(position, purok);
    formData.set('position', combinedPosition);
    formData.delete('purok');

    $.ajax({
        url: endpoints.update,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success(response) {
            if (response.status === 'success') {
                $('#editOfficialModal').modal('hide');
                showToast('success', response.message);
                selectedOfficialId = Number($('#editOfficialId').val());
                fetchOfficials($('#officialsSearch').val().trim());
            } else {
                showToast('error', response.message);
            }
            updateCSRF(response);
        },
        error() {
            showToast('error', 'Update failed');
        }
    });
});

// ================= DELETE =================
$(document).on('click', '.delete-official', function (e) {
    e.stopPropagation();
    const id = Number($(this).data('id'));

    if (!id) {
        showToast('error', 'Invalid official ID');
        return;
    }

    if (!confirm('Delete this official?')) return;

    $.post(endpoints.delete(id), {
        csrf_test_name: $('#csrfTokenField').val()
    }, function (response) {
        if (response.status === 'success') {
            showToast('success', response.message);
            if (selectedOfficialId === id) {
                selectedOfficialId = null;
            }
            fetchOfficials($('#officialsSearch').val().trim());
        } else {
            showToast('error', response.message);
        }
        updateCSRF(response);
    }, 'json').fail(function () {
        showToast('error', 'Delete failed');
    });
});
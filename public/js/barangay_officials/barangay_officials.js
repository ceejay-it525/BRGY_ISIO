'use strict';

// ================= TOAST =================
function showToast(type = 'info', message = '') {
    if (typeof toastr === 'undefined') {
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
    const hash = response && (response.csrf_hash || response.csrfHash);
    if (hash) {
        $('#csrfTokenField').val(hash);
        $('input[name="' + csrfTokenName + '"]').val(hash);
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
        [csrfTokenName]: $('#csrfTokenField').val()
    };

    $.post(endpoints.fetchRecords, payload, function (response) {
        updateCSRF(response);
        officials = response.data || [];

        if (!selectedOfficialId && officials.length) {
            selectedOfficialId = String(officials[0].id);
        }
        renderOfficialCards();
        renderSelectedOfficial(getSelectedOfficial());
    }, 'json');
}

// ================= HELPER FUNCTIONS =================
function togglePurokDropdown(position, modalType = 'add') {
    const groupId = modalType === 'add' ? '#addPurokGroup' : '#editPurokGroup';
    const selectId = modalType === 'add' ? '#addPurok' : '#editPurok';
    
    if (position === 'Purok President' || position === 'Kagawad') {
        $(groupId).show();
        $(selectId).attr('required', true);
    } else {
        $(groupId).hide();
        $(selectId).attr('required', false);
    }
}

function parsePurokFromPosition(position) {
    if (!position) return { base: '', purok: '' };
    const purokMatch = position.match(/^(Purok President|Kagawad)\s+(.+)$/);
    if (purokMatch) {
        return { base: purokMatch[1], purok: purokMatch[2] };
    }
    return { base: position, purok: '' };
}

function buildPositionValue(position, purok) {
    if ((position === 'Purok President' || position === 'Kagawad') && purok) {
        return `${position} ${purok}`;
    }
    return position;
}

$(document).ready(function () {
    $(document).on('change', '#addPosition', function () {
        togglePurokDropdown($(this).val(), 'add');
    });

    $(document).on('change', '#editPosition', function () {
        togglePurokDropdown($(this).val(), 'edit');
    });

    $('#officialsSearchBtn').on('click', function () {
        fetchOfficials($('#officialsSearch').val().trim());
    });

    $(document).on('click', '.official-card', function (e) {
        if ($(e.target).closest('.btn-group').length) return;
        selectedOfficialId = String($(this).data('id'));
        renderOfficialCards();
        renderSelectedOfficial(getSelectedOfficial());
    });

    $(document).on('click', '.view-official', function (e) {
        e.stopPropagation();
        selectedOfficialId = String($(this).data('id'));
        renderOfficialCards();
        renderSelectedOfficial(getSelectedOfficial());
    });

    // Save
    $('#addOfficialForm').on('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        const position = formData.get('position');
        const purok = formData.get('purok');
        formData.set('position', buildPositionValue(position, purok));
        formData.delete('purok');

        $.ajax({
            url: endpoints.save,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: (res) => {
                updateCSRF(res);
                if (res.status === 'success') {
                    $('#AddNewModal').modal('hide');
                    $('#addOfficialForm')[0].reset();
                    $('#addPurokGroup').hide();
                    showToast('success', res.message);
                    fetchOfficials();
                } else {
                    showToast('error', res.message);
                }
            }
        });
    });

    // Edit Load
    $(document).on('click', '.edit-official', function (e) {
        e.stopPropagation();
        const id = $(this).data('id');
        $.get(endpoints.get(id), function (res) {
            updateCSRF(res);
            if (res.status === 'success') {
                const d = res.data;
                const parsed = parsePurokFromPosition(d.position);
                $('#editOfficialId').val(d.id);
                $('#editFirstName').val(d.first_name);
                $('#editMiddleName').val(d.middle_name);
                $('#editLastName').val(d.last_name);
                $('#editPosition').val(parsed.base);
                $('#editPurok').val(parsed.purok);
                $('#editTermStart').val(d.term_start);
                $('#editTermEnd').val(d.term_end);
                $('#editContact').val(d.contact_number);
                $('#editEmail').val(d.email);
                $('#editAddress').val(d.address);
                $('#editStatus').val(d.status);
                togglePurokDropdown(parsed.base, 'edit');
                $('#editOfficialModal').modal('show');
            }
        });
    });

    // Update Submit
    $('#editOfficialForm').on('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        const position = formData.get('position');
        const purok = formData.get('purok');
        formData.set('position', buildPositionValue(position, purok));
        formData.delete('purok');

        $.ajax({
            url: endpoints.update,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: (res) => {
                updateCSRF(res);
                if (res.status === 'success') {
                    $('#editOfficialModal').modal('hide');
                    showToast('success', res.message);
                    fetchOfficials();
                }
            }
        });
    });

    // Delete
    $(document).on('click', '.delete-official', function (e) {
        e.stopPropagation();
        const id = $(this).data('id');
        if (!confirm('Delete this official?')) return;
        $.post(endpoints.delete(id), { [csrfTokenName]: $('#csrfTokenField').val() }, (res) => {
            updateCSRF(res);
            showToast('success', res.message);
            fetchOfficials();
        }, 'json');
    });

    fetchOfficials();
});

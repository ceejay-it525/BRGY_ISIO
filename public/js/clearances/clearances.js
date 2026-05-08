// =====================================================================
// Clearances Module — clearances.js
// Full CRUD: DataTable (server-side) | Add | Edit | View | Delete
// =====================================================================

'use strict';

// ── Toast helper ──────────────────────────────────────────────────────────────
function showToast(type, message) {
    if (typeof toastr === 'undefined') {
        const cls = type === 'success' ? 'success' : (type === 'warning' ? 'warning' : 'danger');
        const el = $(
            `<div class="alert alert-${cls} alert-dismissible fade show"
                  style="position:fixed;top:70px;right:15px;z-index:9999;min-width:280px;" role="alert">
                ${message}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>`
        );
        $('body').append(el);
        setTimeout(() => el.fadeOut(400, () => el.remove()), 4000);
        return;
    }
    toastr.options = {
        closeButton: true, progressBar: true,
        positionClass: 'toast-top-right', timeOut: 3500
    };
    (toastr[type] || toastr.info)(message);
}

// ── CSRF refresh ──────────────────────────────────────────────────────────────
function refreshCsrf(response) {
    const token = response && (response.csrf_hash || response.csrfHash);
    if (token) $('input[name="csrf_test_name"]').val(token);
}

// ── Status badge ──────────────────────────────────────────────────────────────
function statusBadge(status) {
    const map = {
        'Pending'  : 'warning',
        'Approved' : 'info',
        'Released' : 'success',
        'Rejected' : 'danger',
        'Expired'  : 'secondary',
    };
    const icons = {
        'Pending'  : 'fa-clock',
        'Approved' : 'fa-check',
        'Released' : 'fa-handshake',
        'Rejected' : 'fa-times',
        'Expired'  : 'fa-calendar-times',
    };
    const color = map[status] || 'light';
    const icon = icons[status] || '';
    return `<span class="badge badge-${color}"><i class="fas ${icon} mr-1"></i>${status}</span>`;
}

// ── Show validation errors inside a modal ────────────────────────────────────
function showErrors(modalId, errors) {
    $(`#${modalId} .validation-errors`).remove();
    let html = '<div class="alert alert-danger validation-errors alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert">&times;</button><ul class="mb-0">';
    $.each(errors, (field, msg) => { html += `<li>${msg}</li>`; });
    html += '</ul></div>';
    $(`#${modalId} .modal-body`).prepend(html);
}

// ── Format currency ──────────────────────────────────────────────────────────
function formatCurrency(amount) {
    return '₱' + parseFloat(amount || 0).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

// ── Format date ──────────────────────────────────────────────────────────────
function formatDate(dateStr) {
    if (!dateStr || dateStr === '0000-00-00') return '—';
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-PH', { year: 'numeric', month: 'short', day: 'numeric' });
}

// ── DataTable instance ────────────────────────────────────────────────────────
let clearancesTable;
let pendingDeleteId = null;

$(function () {

    // Init DataTable
    if ($.fn.DataTable.isDataTable('#clearancesTable')) {
        $('#clearancesTable').DataTable().destroy();
    }

    clearancesTable = $('#clearancesTable').DataTable({
        processing  : true,
        serverSide  : true,
        responsive  : true,
        pageLength  : 25,
        order       : [[1, 'desc']],
        ajax: {
            url      : baseUrl + 'clearances/fetchRecords',
            type     : 'POST',
            dataType : 'json',
            data     : d => { d.csrf_test_name = $('input[name="csrf_test_name"]').val(); },
            error    : (xhr) => {
                console.error('fetchRecords error:', xhr.status, xhr.responseText);
                showToast('error', 'Failed to load clearances data. Check the console for details.');
            },
            dataFilter: function(data) {
                const json = JSON.parse(data);
                // Update stats
                updateStats(json);
                return data;
            }
        },
        columns: [
            { data: 'row_number',              width: '4%',  orderable: false },
            { data: 'clearance_id',            visible: false },
            { data: 'control_number',          defaultContent: '—' },
            { data: 'resident_name',           defaultContent: '—' },
            { data: 'type_name',               defaultContent: '—' },
            { data: 'purpose',                 defaultContent: '—' },
            { data: 'formatted_request_date',  defaultContent: '—' },
            { 
                data: 'issued_date',
                render: function(data) {
                    return data ? formatDate(data) : '<span class="text-muted">—</span>';
                }
            },
            {
                data: 'status',
                orderable: false,
                render: (data) => statusBadge(data)
            },
            {
                data: 'fee_amount',
                render: (data) => formatCurrency(data)
            },
            {
                data      : null,
                orderable : false,
                searchable: false,
                className : 'text-center',
                width     : '12%',
                render    : (row) => `
                    <div class="btn-group btn-group-sm" role="group">
                        <button class="btn btn-info btn-xs view-btn" data-id="${row.clearance_id}" title="View" data-toggle="tooltip">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-warning btn-xs edit-btn" data-id="${row.clearance_id}" title="Edit" data-toggle="tooltip">
                            <i class="far fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-xs delete-btn" data-id="${row.clearance_id}" title="Delete" data-toggle="tooltip">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>`
            }
        ],
        language: {
            processing : '<i class="fas fa-spinner fa-spin"></i> Loading...',
            emptyTable : 'No clearance records found.',
            zeroRecords: 'No matching records found'
        },
        dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>"
    });

    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // ── Initialize Select2 if available ──────────────────────────────────────
    if ($.fn.select2) {
        $('.select2resident').select2({
            theme      : 'bootstrap4',
            placeholder: '-- Select Resident --',
            width      : '100%',
        });
    }

    // Re-init Select2 when modals open (select2 + Bootstrap modal quirk)
    $('#addClearanceModal, #editClearanceModal').on('shown.bs.modal', function () {
        if ($.fn.select2) {
            $(this).find('.select2resident').select2({
                theme      : 'bootstrap4',
                placeholder: '-- Select Resident --',
                width      : '100%',
                dropdownParent: $(this),
            });
        }
    });

    // ── Set default dates ─────────────────────────────────────────────────────
    const today = new Date().toISOString().split('T')[0];
    $('#addRequestDate').val(today);

    // =========================================================================
    // ADD — submit
    // =========================================================================
    $('#addClearanceForm').on('submit', function (e) {
        e.preventDefault();

        const $btn  = $('#addSaveBtn');
        const $form = $(this);
        const orig  = $btn.html();

        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Saving…');

        $.ajax({
            url         : baseUrl + 'clearances/save',
            method      : 'POST',
            data        : new FormData(this),
            processData : false,
            contentType : false,
            dataType    : 'json',
            success: function (res) {
                refreshCsrf(res);
                if (res.status === 200) {
                    $form[0].reset();
                    $('#addClearanceModal').modal('hide');
                    showToast('success', res.message || 'Clearance issued successfully!');
                    clearancesTable.ajax.reload(null, false);
                } else if (res.status === 422) {
                    showErrors('addClearanceModal', res.errors);
                } else {
                    showToast('error', res.message || 'Failed to save clearance.');
                }
            },
            error: function (xhr) {
                console.error('Save error:', xhr.responseText);
                try {
                    const res = JSON.parse(xhr.responseText);
                    if (res.errors) { showErrors('addClearanceModal', res.errors); return; }
                } catch (err) { /* not JSON */ }
                showToast('error', 'An error occurred. Please try again.');
            },
            complete: () => $btn.prop('disabled', false).html(orig)
        });
    });

    // =========================================================================
    // EDIT — load record
    // =========================================================================
    $(document).on('click', '.edit-btn', function () {
        const id = $(this).data('id');

        $.get(baseUrl + 'clearances/edit/' + id)
            .done(function (res) {
                if (!res || !res.clearance_id) {
                    showToast('error', 'Record not found.');
                    return;
                }
                $('#editClearanceId').val(res.clearance_id);
                $('#editControlNumber').val(res.control_number  || '');
                $('#editResidentId').val(res.resident_id        || '').trigger('change');
                $('#editClearanceTypeId').val(res.clearance_type_id || '');
                $('#editPurpose').val(res.purpose               || '');
                $('#editRequestDate').val(res.request_date      || '');
                $('#editIssuedDate').val(res.issued_date        || '');
                $('#editExpiryDate').val(res.expiry_date        || '');
                $('#editStatus').val(res.status                 || 'Pending');
                $('#editFeeAmount').val(res.fee_amount          || '0.00');
                $('#editOrNumber').val(res.or_number            || '');
                $('#editRemarks').val(res.remarks               || '');
                $('#editClearanceModal .validation-errors').remove();
                $('#editClearanceModal').modal('show');
                refreshCsrf(res);
            })
            .fail(function (xhr) {
                console.error('Edit load error:', xhr.responseText);
                showToast('error', 'Failed to load record.');
            });
    });

    // =========================================================================
    // EDIT — submit update
    // =========================================================================
    $('#editClearanceForm').on('submit', function (e) {
        e.preventDefault();

        const id    = $('#editClearanceId').val();
        const $btn  = $('#editSaveBtn');
        const $form = $(this);
        const orig  = $btn.html();

        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Updating…');

        $.ajax({
            url         : baseUrl + 'clearances/update/' + id,
            method      : 'POST',
            data        : new FormData(this),
            processData : false,
            contentType : false,
            dataType    : 'json',
            success: function (res) {
                refreshCsrf(res);
                if (res.status === 200) {
                    $form[0].reset();
                    $('#editClearanceModal').modal('hide');
                    showToast('success', res.message || 'Clearance updated successfully!');
                    clearancesTable.ajax.reload(null, false);
                } else if (res.status === 422) {
                    showErrors('editClearanceModal', res.errors);
                } else {
                    showToast('error', res.message || 'Failed to update clearance.');
                }
            },
            error: function (xhr) {
                console.error('Update error:', xhr.responseText);
                try {
                    const res = JSON.parse(xhr.responseText);
                    if (res.errors) { showErrors('editClearanceModal', res.errors); return; }
                } catch (err) { /* not JSON */ }
                showToast('error', 'An error occurred. Please try again.');
            },
            complete: () => $btn.prop('disabled', false).html(orig)
        });
    });

    // =========================================================================
    // VIEW — show details
    // =========================================================================
    $(document).on('click', '.view-btn', function () {
        const id = $(this).data('id');

        $.get(baseUrl + 'clearances/edit/' + id)
            .done(function (res) {
                if (!res || !res.clearance_id) {
                    showToast('error', 'Record not found.');
                    return;
                }
                $('#viewClearanceBody').html(`
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th width="40%"><i class="fas fa-hashtag mr-2 text-muted"></i>Control Number</th>
                                    <td><strong>${res.control_number || '—'}</strong></td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-user mr-2 text-muted"></i>Resident</th>
                                    <td>${res.resident_name || '—'} (ID: ${res.resident_id || '—'})</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-tag mr-2 text-muted"></i>Clearance Type</th>
                                    <td>${res.type_name || '—'} (ID: ${res.clearance_type_id || '—'})</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-bullseye mr-2 text-muted"></i>Purpose</th>
                                    <td>${res.purpose || '—'}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th width="40%"><i class="fas fa-calendar-alt mr-2 text-muted"></i>Request Date</th>
                                    <td>${res.request_date ? formatDate(res.request_date) : '—'}</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-calendar-check mr-2 text-muted"></i>Issued Date</th>
                                    <td>${res.issued_date ? formatDate(res.issued_date) : '—'}</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-calendar-times mr-2 text-muted"></i>Expiry Date</th>
                                    <td>${res.expiry_date ? formatDate(res.expiry_date) : '—'}</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-info-circle mr-2 text-muted"></i>Status</th>
                                    <td>${statusBadge(res.status)}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th width="40%"><i class="fas fa-money-bill mr-2 text-muted"></i>Fee Amount</th>
                                    <td><strong>${formatCurrency(res.fee_amount)}</strong></td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-receipt mr-2 text-muted"></i>OR Number</th>
                                    <td>${res.or_number || '—'}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-comment-alt mr-2 text-muted"></i>Remarks</label>
                                <p class="text-muted mb-0">${res.remarks || 'No remarks'}</p>
                            </div>
                        </div>
                    </div>
                `);
                $('#viewClearanceModal').modal('show');
            })
            .fail(() => showToast('error', 'Failed to load record.'));
    });

    // =========================================================================
    // DELETE — show confirmation modal
    // =========================================================================
    $(document).on('click', '.delete-btn', function () {
        pendingDeleteId = $(this).data('id');
        $('#deleteConfirmModal').modal('show');
    });

    // =========================================================================
    // DELETE — confirm
    // =========================================================================
    $('#confirmDeleteBtn').on('click', function () {
        if (!pendingDeleteId) {
            showToast('error', 'No record selected for deletion.');
            return;
        }

        const $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Deleting…');

        $.ajax({
            url      : baseUrl + 'clearances/delete/' + pendingDeleteId,
            method   : 'POST',
            data     : { csrf_test_name: $('input[name="csrf_test_name"]').val() },
            dataType : 'json',
            success  : function (res) {
                refreshCsrf(res);
                if (res.status === 200) {
                    showToast('success', res.message || 'Clearance deleted successfully!');
                    clearancesTable.ajax.reload(null, false);
                    $('#deleteConfirmModal').modal('hide');
                } else {
                    showToast('error', res.message || 'Failed to delete clearance.');
                }
            },
            error: function (xhr) {
                console.error('Delete error:', xhr.responseText);
                showToast('error', 'An error occurred. Please try again.');
            },
            complete: () => {
                $btn.prop('disabled', false).html('<i class="fas fa-trash mr-1"></i> Delete');
                pendingDeleteId = null;
            }
        });
    });

    // ── Reset modals on close ─────────────────────────────────────────────────
    $('#addClearanceModal, #editClearanceModal').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset();
        $(this).find('.validation-errors').remove();
        // Reset default date
        const today = new Date().toISOString().split('T')[0];
        $('#addRequestDate').val(today);
    });

    // ── Update Stats ──────────────────────────────────────────────────────────
    function updateStats(data) {
        // This would require a separate stats endpoint, for now just show total
        if (data.recordsTotal !== undefined) {
            $('#totalClearances').text(data.recordsTotal);
        }
    }

    // ── Fetch stats on load ───────────────────────────────────────────────────
    function fetchStats() {
        $.get(baseUrl + 'clearances/stats')
            .done(function (res) {
                if (res.status === 'success') {
                    $('#totalClearances').text(res.data.total || 0);
                    $('#pendingClearances').text(res.data.pending || 0);
                    $('#approvedClearances').text(res.data.approved || 0);
                    $('#releasedClearances').text(res.data.released || 0);
                }
            })
            .fail(function () {
                // Stats endpoint not available, that's okay
            });
    }

    // Initialize stats
    fetchStats();
});
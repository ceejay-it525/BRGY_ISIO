// =============================================================================
//  clearances.js  —  Barangay Clearance Issuance System
//  Fixed: reject modal wired, release OR# modal wired, type_name display,
//         editClearanceType population, stats card IDs
// =============================================================================

// -----------------------------------------------------------------------------
// TOAST HELPER
// -----------------------------------------------------------------------------
function showToast(type, message) {
    if (typeof toastr !== 'undefined') {
        toastr.options = { closeButton: true, progressBar: true, positionClass: 'toast-top-right', timeOut: 3500 };
        if (toastr[type]) { toastr[type](message); return; }
    }
    const cls = { success: 'success', error: 'danger', warning: 'warning', info: 'info' }[type] || 'info';
    const el = $(`
        <div class="alert alert-${cls} alert-dismissible fade show"
             style="position:fixed;top:70px;right:15px;z-index:99999;min-width:300px;box-shadow:0 2px 12px rgba(0,0,0,.25)">
            ${message}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>`);
    $('body').append(el);
    setTimeout(() => el.alert('close'), 3500);
}

// -----------------------------------------------------------------------------
// CSRF
// -----------------------------------------------------------------------------
function csrfData() {
    return { csrf_test_name: $('input[name=csrf_test_name]').val() };
}
function updateCSRF(res) {
    const token = res && (res.csrf_hash || res.csrfHash);
    if (token) $('input[name=csrf_test_name]').val(token);
}

// -----------------------------------------------------------------------------
// FORMAT HELPERS
// -----------------------------------------------------------------------------
function formatDate(d) {
    if (!d || d === '0000-00-00') return '—';
    return new Date(d).toLocaleDateString('en-PH', { year: 'numeric', month: 'short', day: 'numeric' });
}
function formatCurrency(v) {
    return '₱' + parseFloat(v || 0).toLocaleString('en-PH', { minimumFractionDigits: 2 });
}

function statusBadge(status) {
    const map = {
        'Pending':  ['warning',   'fa-clock'],
        'Approved': ['info',      'fa-check-circle'],
        'Released': ['success',   'fa-handshake'],
        'Rejected': ['danger',    'fa-ban'],
        'Expired':  ['secondary', 'fa-exclamation-triangle'],
    };
    const [cls, icon] = map[status] || ['secondary', 'fa-question-circle'];
    return `<span class="badge badge-${cls}"><i class="fas ${icon} mr-1"></i>${status}</span>`;
}

/**
 * Workflow buttons shown in the View modal.
 *
 * Pending  → [Approve]  [Reject]
 *   Approve: sets status to Approved (no OR# needed yet)
 *   Reject:  opens reject modal to capture reason
 *
 * Approved → [Release]  [Reject]
 *   Release: opens release modal to capture OR#, then marks Released
 *   Reject:  opens reject modal
 *
 * Released → [Print]
 *   Print: opens print preview modal
 *
 * Rejected / Expired → info badge only, no further actions
 */
function workflowButtons(status, id) {
    const btn = (cls, action, icon, label) =>
        `<button class="btn btn-${cls} btn-sm mr-1 ${action}" data-id="${id}">
            <i class="fas ${icon} mr-1"></i>${label}
         </button>`;

    switch (status) {
        case 'Pending':
            return btn('info',   'approve-clearance', 'fa-check', 'Approve') +
                   btn('danger', 'reject-clearance',  'fa-ban',   'Reject');
        case 'Approved':
            return btn('success', 'release-clearance', 'fa-handshake', 'Release') +
                   btn('danger',  'reject-clearance',  'fa-ban',       'Reject');
        case 'Released':
            return btn('primary', 'print-clearance', 'fa-print', 'Print');
        case 'Rejected':
            return '<span class="badge badge-danger p-2"><i class="fas fa-ban mr-1"></i>Rejected — No Further Actions</span>';
        case 'Expired':
            return '<span class="badge badge-secondary p-2"><i class="fas fa-exclamation-triangle mr-1"></i>Expired — No Further Actions</span>';
        default:
            return '<span class="badge badge-secondary p-2">No Actions Available</span>';
    }
}

// -----------------------------------------------------------------------------
// DATATABLE
// -----------------------------------------------------------------------------
let clearancesTable;

$(function () {

    if ($.fn.DataTable.isDataTable('#clearancesTable')) {
        $('#clearancesTable').DataTable().destroy();
    }

    clearancesTable = $('#clearancesTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        pageLength: 25,
        order:      [[0, 'desc']],
        ajax: {
            url:  baseUrl + 'clearances/fetchRecords',
            type: 'POST',
            data: function (d) {
                return $.extend({}, d, csrfData(), { view_type: currentView });
            },
            error: function (xhr) {
                console.error('❌ DataTable error:', xhr.responseText);
                showToast('error', 'Failed to load records.');
            }
        },
        columns: [
            {
                data: 'row_number', width: '4%', orderable: false, searchable: false,
                render: d => `<span class="text-muted small">${d}</span>`
            },
            { data: 'clearance_id', visible: false },
            {
                data: 'control_number',
                render: d => `<strong><i class="fas fa-hashtag mr-1 text-secondary"></i>${d || '—'}</strong>`
            },
            {
                data: 'resident_name',
                render: (d, t, r) =>
                    `<a href="javascript:void(0)" class="view-clearance-link" data-id="${r.clearance_id}">
                        <i class="fas fa-user mr-1 text-primary"></i><strong>${d || '—'}</strong>
                     </a>`
            },
            {
                // FIX: use type_name from joined clearance_types table
                data: 'type_name',
                render: d => d ? `<span class="badge badge-secondary">${d}</span>` : '<span class="text-muted">—</span>'
            },
            {
                data: 'purpose',
                render: d => d ? (d.length > 50 ? d.substring(0, 50) + '…' : d) : '<span class="text-muted">—</span>'
            },
            { data: 'request_date', render: d => formatDate(d) },
            { data: 'fee_amount',   render: v => `<strong class="text-success">${formatCurrency(v)}</strong>` },
            { data: 'status',       render: v => statusBadge(v) },
            {
                data: null, orderable: false, searchable: false,
                className: 'text-center', width: '8%',
                render: r =>
                    `<div class="btn-group btn-group-sm">
                        <button class="btn btn-info    view-clearance-btn" data-id="${r.clearance_id}" title="View">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-warning edit-clearance"     data-id="${r.clearance_id}" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger  delete-clearance"   data-id="${r.clearance_id}" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>`
            }
        ],
        language: {
            processing:  '<i class="fas fa-spinner fa-spin mr-1"></i>Loading…',
            emptyTable:  '<div class="text-center py-4 text-muted"><i class="fas fa-inbox fa-2x d-block mb-2"></i>No clearances found.</div>',
            zeroRecords: '<div class="text-center py-4 text-muted"><i class="fas fa-search fa-2x d-block mb-2"></i>No matching clearances.</div>'
        }
    });

    // -----------------------------------------------------------------------
    // HELPERS
    // -----------------------------------------------------------------------
    function reloadTable() {
        if (clearancesTable) clearancesTable.ajax.reload(null, false);
    }

    function fetchStats() {
        $.get(baseUrl + 'clearances/stats', function (res) {
            if (res.status !== 'success') return;
            const d = res.data;
            // KPI cards
            $('#totalClearances')   .text(d.total    || 0);
            $('#pendingClearances') .text(d.pending  || 0);
            $('#releasedClearances').text(d.released || 0);
            $('#totalRevenue')      .text(formatCurrency(d.revenue));
            // Tab badges
            $('#pendingCount') .text(d.pending  || 0);
            $('#approvedCount').text(d.approved || 0);
            $('#releasedCount').text(d.released || 0);
            $('#rejectedCount').text(d.rejected || 0);
            $('#expiredCount') .text(d.expired  || 0);
        });
    }

    fetchStats();
    setInterval(fetchStats, 30000);

    // -----------------------------------------------------------------------
    // VIEW MODAL
    // Opened by: clicking resident name link  OR  eye icon button
    // -----------------------------------------------------------------------
    function openViewModal(id) {
        if (!id) return;
        $.get(baseUrl + 'clearances/view/' + id, function (res) {
            if (res.status !== 'success') { showToast('error', res.message || 'Failed to load.'); return; }
            const p = res.data;

            $('#viewClearanceId')  .val(id);
            $('#viewControlNumber').text(p.control_number || '—');
            $('#viewResidentName') .text(p.resident_name  || '—');
            $('#viewAddress')      .text(p.address_line1  || '—');
            // FIX: use type_name from the join (not clearance_type string column)
            $('#viewClearanceType').text(p.type_name      || '—');
            $('#viewPurpose')      .text(p.purpose        || '—');
            $('#viewRequestDate')  .text(formatDate(p.request_date));
            $('#viewIssuedDate')   .text(formatDate(p.issued_date));
            $('#viewExpiryDate')   .text(formatDate(p.expiry_date));
            $('#viewFee')          .text(formatCurrency(p.fee_amount));
            $('#viewOrNumber')     .text(p.or_number      || '—');
            $('#viewStatus')       .html(statusBadge(p.status));

            $('#workflowActions').html(workflowButtons(p.status, id));

            // History
            let historyHtml = '';
            if (res.history && res.history.length > 0) {
                historyHtml = '<ul class="list-group list-group-flush">';
                res.history.forEach(h => {
                    historyHtml += `<li class="list-group-item small">
                        <strong>${h.clearance_type || 'Clearance'}</strong>
                        <span class="text-muted mx-1">·</span>
                        ${formatDate(h.request_date)}
                        <span class="float-right">${statusBadge(h.status)}</span>
                    </li>`;
                });
                historyHtml += '</ul>';
            } else {
                historyHtml = '<p class="text-muted text-center py-3"><i class="fas fa-history mr-1"></i>No previous clearances</p>';
            }
            $('#clearanceHistory').html(historyHtml);

            $('#viewClearanceModal').modal('show');
        }).fail(() => showToast('error', 'Network error. Please try again.'));
    }

    $(document).on('click', '.view-clearance-link', function (e) {
        e.preventDefault();
        openViewModal($(this).data('id'));
    });

    $(document).on('click', '.view-clearance-btn', function () {
        openViewModal($(this).data('id'));
    });

    // "Edit" button inside View modal — closes view and opens edit
    $(document).on('click', '#editFromViewBtn', function () {
        const id = $('#viewClearanceId').val();
        $('#viewClearanceModal').modal('hide');
        openEditModal(id);
    });

    // -----------------------------------------------------------------------
    // ADD CLEARANCE
    // Triggered by: "Issue New Clearance" button
    // -----------------------------------------------------------------------
    $('#addClearanceForm').on('submit', function (e) {
        e.preventDefault();
        const $btn = $(this).find('[type=submit]')
            .prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Saving…');

        $.post(baseUrl + 'clearances/save', $(this).serialize(), function (res) {
            updateCSRF(res);
            if (res.status === 'success') {
                $('#AddNewModal').modal('hide');
                document.getElementById('addClearanceForm').reset();
                showToast('success', res.message);
                reloadTable();
                fetchStats();
            } else {
                showToast('error', res.message || 'Save failed.');
            }
        }, 'json')
        .fail(() => showToast('error', 'Server error.'))
        .always(() => $btn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i>Save Clearance'));
    });

    $('#AddNewModal').on('hidden.bs.modal', function () {
        document.getElementById('addClearanceForm').reset();
    });

    // -----------------------------------------------------------------------
    // EDIT CLEARANCE
    // Triggered by: pencil icon in table row, or "Edit" in View modal
    // -----------------------------------------------------------------------
    function openEditModal(id) {
        if (!id) return;
        $.get(baseUrl + 'clearances/edit/' + id, function (res) {
            if (res.status !== 'success') { showToast('error', res.message || 'Failed to load.'); return; }
            const p = res.data;
            $('#editClearanceId')  .val(p.clearance_id);
            $('#editResidentId')   .val(p.resident_id);
            // FIX: populate clearance type dropdown from type_name returned by controller
            $('#editClearanceType').val(p.type_name || '');
            $('#editPurpose')      .val(p.purpose);
            $('#editFeeAmount')    .val(p.fee_amount);
            $('#editStatus')       .val(p.status);
            $('#editRemarks')      .val(p.remarks);
            $('#editClearanceModal').modal('show');
        }).fail(() => showToast('error', 'Network error.'));
    }

    $(document).on('click', '.edit-clearance', function () {
        openEditModal($(this).data('id'));
    });

    $('#editClearanceForm').on('submit', function (e) {
        e.preventDefault();
        const $btn = $(this).find('[type=submit]')
            .prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Updating…');

        $.post(baseUrl + 'clearances/update', $(this).serialize(), function (res) {
            updateCSRF(res);
            if (res.status === 'success') {
                $('#editClearanceModal').modal('hide');
                showToast('success', res.message);
                reloadTable();
                fetchStats();
            } else {
                showToast('error', res.message || 'Update failed.');
            }
        }, 'json')
        .fail(() => showToast('error', 'Server error.'))
        .always(() => $btn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i>Update Clearance'));
    });

    // -----------------------------------------------------------------------
    // DELETE
    // Triggered by: trash icon in table row
    // -----------------------------------------------------------------------
    $(document).on('click', '.delete-clearance', function () {
        const id = $(this).data('id');
        if (!confirm('Delete this clearance? This cannot be undone.')) return;

        $.post(baseUrl + 'clearances/delete/' + id, csrfData(), function (res) {
            updateCSRF(res);
            if (res.status === 'success') {
                showToast('success', res.message);
                reloadTable();
                fetchStats();
            } else {
                showToast('error', res.message || 'Delete failed.');
            }
        }, 'json').fail(() => showToast('error', 'Server error.'));
    });

    // -----------------------------------------------------------------------
    // WORKFLOW: APPROVE
    // Triggered by: "Approve" button in View modal (Pending status)
    // Action: sets status → Approved, no modal needed
    // -----------------------------------------------------------------------
    $(document).on('click', '.approve-clearance', function () {
        const id = $(this).data('id');
        if (!confirm('Approve this clearance request?')) return;

        $(this).prop('disabled', true);

        $.post(baseUrl + 'clearances/approve/' + id, csrfData(), function (res) {
            updateCSRF(res);
            if (res.status === 'success') {
                $('#viewClearanceModal').modal('hide');
                showToast('success', res.message);
                reloadTable();
                fetchStats();
            } else {
                showToast('error', res.message || 'Failed to approve.');
            }
        }, 'json').fail(() => showToast('error', 'Server error.'));
    });

    // -----------------------------------------------------------------------
    // WORKFLOW: RELEASE
    // Triggered by: "Release" button in View modal (Approved status)
    // Action: opens #releaseModal to collect OR#, then POST to clearances/release/{id}
    // -----------------------------------------------------------------------
    $(document).on('click', '.release-clearance', function () {
        const id = $(this).data('id');
        $('#releaseClearanceId').val(id);
        $('#releaseOrNumber').val('');
        $('#releaseModal').modal('show');
    });

    $('#releaseForm').on('submit', function (e) {
        e.preventDefault();
        const id       = $('#releaseClearanceId').val();
        const orNumber = $('#releaseOrNumber').val().trim();

        if (!orNumber) { showToast('warning', 'Please enter the OR Number.'); return; }

        const $btn = $(this).find('[type=submit]')
            .prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Releasing…');

        $.post(baseUrl + 'clearances/release/' + id, { ...csrfData(), or_number: orNumber }, function (res) {
            updateCSRF(res);
            if (res.status === 'success') {
                $('#releaseModal').modal('hide');
                $('#viewClearanceModal').modal('hide');
                showToast('success', res.message);
                reloadTable();
                fetchStats();
            } else {
                showToast('error', res.message || 'Failed to release.');
            }
        }, 'json')
        .fail(() => showToast('error', 'Server error.'))
        .always(() => $btn.prop('disabled', false).html('<i class="fas fa-handshake mr-1"></i>Confirm Release'));
    });

    // -----------------------------------------------------------------------
    // WORKFLOW: REJECT
    // Triggered by: "Reject" button in View modal (Pending or Approved status)
    // Action: opens #rejectModal to collect reason, then POST to clearances/reject/{id}
    // FIX: rejectModal and rejectForm now actually exist in the view HTML
    // -----------------------------------------------------------------------
    $(document).on('click', '.reject-clearance', function () {
        const id = $(this).data('id');
        $('#rejectClearanceId').val(id);
        $('#rejectReason').val('');
        $('#rejectModal').modal('show');
    });

    $('#rejectForm').on('submit', function (e) {
        e.preventDefault();
        const id     = $('#rejectClearanceId').val();
        const reason = $('#rejectReason').val().trim();

        if (!reason) { showToast('warning', 'Please enter a rejection reason.'); return; }

        const $btn = $(this).find('[type=submit]')
            .prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Rejecting…');

        $.post(baseUrl + 'clearances/reject/' + id, { ...csrfData(), reason }, function (res) {
            updateCSRF(res);
            if (res.status === 'success') {
                $('#rejectModal').modal('hide');
                $('#viewClearanceModal').modal('hide');
                showToast('success', res.message);
                reloadTable();
                fetchStats();
            } else {
                showToast('error', res.message || 'Failed to reject.');
            }
        }, 'json')
        .fail(() => showToast('error', 'Server error.'))
        .always(() => $btn.prop('disabled', false).html('<i class="fas fa-ban mr-1"></i>Confirm Rejection'));
    });

    // -----------------------------------------------------------------------
    // PRINT
    // Triggered by: "Print" button in View modal (Released status only)
    // Action: loads clearances/getPrintPreview/{id}, shows in #printModal
    // "Print Now" opens browser print dialog in a new window
    // -----------------------------------------------------------------------
    $(document).on('click', '.print-clearance', function () {
        const id = $(this).data('id');
        $.ajax({
            url:      baseUrl + 'clearances/getPrintPreview/' + id,
            type:     'GET',
            dataType: 'json',
            timeout:  8000
        }).done(res => {
            updateCSRF(res);
            if (res && res.status === 'success' && res.html) {
                $('#printPreviewContent').html(res.html);
                $('#printModal').modal('show');
            } else {
                showToast('error', res?.message || 'Failed to load preview.');
            }
        }).fail(e => {
            console.error('❌ Print error:', e);
            showToast('error', 'Failed to load preview.');
        });
    });

    $('#printBtn').on('click', function () {
        const content = $('#printPreviewContent').html();
        const w = window.open('', '', 'width=860,height=700');
        w.document.write(`<html><head><title>Barangay Clearance</title></head><body onload="window.print();window.close();">${content}</body></html>`);
        w.document.close();
    });

});

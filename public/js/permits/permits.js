
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
function formatDateTime(d) {
    if (!d) return '—';
    return new Date(d).toLocaleString('en-PH', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });
}
function formatCurrency(v) {
    return '₱' + parseFloat(v || 0).toLocaleString('en-PH', { minimumFractionDigits: 2 });
}

function statusBadge(status) {
    const map = {
        Pending:  ['warning',   'fa-clock'],
        Approved: ['info',      'fa-check'],
        Paid:     ['secondary', 'fa-money-bill'],
        Active:   ['success',   'fa-check-circle'],
        Expired:  ['danger',    'fa-exclamation-triangle'],
        Rejected: ['dark',      'fa-ban'],
    };
    const [cls, icon] = map[status] || ['secondary', 'fa-question-circle'];
    return `<span class="badge badge-${cls}"><i class="fas ${icon} mr-1"></i>${status}</span>`;
}

function workflowButtons(status, id) {
    const btn = (cls, action, icon, label) =>
        `<button class="btn btn-${cls} btn-sm ${action}" data-id="${id}"><i class="fas ${icon} mr-1"></i>${label}</button>`;

    switch (status) {
        case 'Pending':
            return btn('success', 'approve-permit', 'fa-check',   'Approve') + ' ' +
                   btn('danger',  'reject-permit',  'fa-ban',     'Reject');
        case 'Approved':
            return btn('success', 'mark-paid-btn',  'fa-money-bill', 'Mark Paid');
        case 'Paid':
            return btn('primary', 'print-permit',   'fa-print',       'Print') + ' ' +
                   btn('success', 'mark-active-btn','fa-check-circle','Activate');
        case 'Active':
            return btn('primary', 'print-permit',   'fa-print', 'Print');
        default:
            return '<span class="badge badge-secondary">No Actions</span>';
    }
}

// -----------------------------------------------------------------------------
// DATATABLE
// -----------------------------------------------------------------------------
let permitsTable;

$(function () {

    if ($.fn.DataTable.isDataTable('#permitsTable')) {
        $('#permitsTable').DataTable().destroy();
    }

    permitsTable = $('#permitsTable').DataTable({
        processing:  true,
        serverSide:  true,
        responsive:  true,
        pageLength:  25,
        order:       [[6, 'desc']],

        ajax: {
            url:  baseUrl + 'permits/fetchRecords',
            type: 'POST',
            data: function (d) {
                d.csrf_test_name = $('input[name=csrf_test_name]').val();
                // Derive view_type from the current URL segment
                const seg = window.location.pathname.split('/').filter(Boolean).pop();
                d.view_type = ['pending','payment','print'].includes(seg) ? seg : 'all';
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                showToast('error', 'Failed to load records.');
            },
        },

        columns: [
            // 0  row number
            { data: 'row_number', width: '4%', orderable: false, searchable: false },
            // 1  id (hidden)
            { data: 'id', visible: false },
            // 2  business name — CLICKABLE
            {
                data: 'business_name',
                render: (data, type, row) =>
                    `<a href="javascript:void(0)" class="view-permit-link font-weight-bold text-primary"
                        data-id="${row.id}" title="Click to view details">${data || '—'}</a>`,
            },
            // 3  owner
            {
                data: 'owner_name',
                render: d => d ? `<span class="text-muted"><i class="fas fa-user-tie mr-1"></i>${d}</span>` : '—',
            },
            // 4  business type
            { data: 'business_type', render: d => d || '—' },
            // 5  permit type
            {
                data: 'permit_type',
                render: d => {
                    if (!d) return '—';
                    const colors = { New: 'info', Renewal: 'primary', Amendment: 'warning' };
                    return `<span class="badge badge-${colors[d] || 'secondary'}">${d}</span>`;
                },
            },
            // 6  issue date
            { data: 'issue_date',  render: d => formatDate(d) },
            // 7  expiry date
            {
                data: 'expiry_date',
                render: d => {
                    if (!d) return '—';
                    const past = new Date(d) < new Date();
                    const fmt  = formatDate(d);
                    return past
                        ? `<span class="text-danger"><i class="fas fa-exclamation-circle mr-1"></i>${fmt}</span>`
                        : fmt;
                },
            },
            // 8  status
            { data: 'status', render: d => statusBadge(d), width: '10%' },
            // 9  fees paid
            { data: 'fees_paid', render: d => formatCurrency(d) },
            // 10 actions — Edit + Delete only (view is via business name click)
            {
                data:       null,
                orderable:  false,
                searchable: false,
                className:  'text-center',
                width:      '8%',
                render: row =>
                    `<div class="btn-group btn-group-sm">
                        <button class="btn btn-warning edit-permit"  data-id="${row.id}" title="Edit"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-danger delete-permit" data-id="${row.id}" title="Delete"><i class="fas fa-trash"></i></button>
                    </div>`,
            },
        ],

        language: {
            processing:  '<i class="fas fa-spinner fa-spin mr-1"></i> Loading…',
            emptyTable:  'No business permits found.',
            zeroRecords: 'No matching records found.',
        },

        dom: "<'row'<'col-sm-6'l><'col-sm-6'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row'<'col-sm-5'i><'col-sm-7'p>>",
    });

    // -----------------------------------------------------------------------
    // Default issue date = today
    // -----------------------------------------------------------------------
    const todayISO = new Date().toISOString().split('T')[0];
    $('#addIssueDate').val(todayISO);

    // -----------------------------------------------------------------------
    // HELPERS
    // -----------------------------------------------------------------------
    function reloadTable() {
        if (permitsTable) permitsTable.ajax.reload(null, false);
    }

    function fetchStats() {
        $.get(baseUrl + 'permits/stats', function (res) {
            if (res.status !== 'success') return;
            const d = res.data;
            $('#totalPermits')  .text(d.total    || 0);
            $('#pendingPermits') .text(d.pending  || 0);
            $('#activePermits')  .text(d.active   || 0);
            $('#expiredPermits') .text(d.expired  || 0);
            $('#pendingCount')   .text(d.pending  || 0);
            $('#paymentCount')   .text(d.approved || 0);
            $('#printCount')     .text(d.paid     || 0);
        });
    }

    fetchStats();
    setInterval(fetchStats, 30000);

    // -----------------------------------------------------------------------
    // VIEW MODAL  (opened by clicking business name)
    // -----------------------------------------------------------------------
    function openViewModal(id) {
        if (!id) return;
        $.get(baseUrl + 'permits/view/' + id, function (res) {
            if (res.status !== 'success') { showToast('error', res.message || 'Failed to load.'); return; }
            const p = res.data;

            $('#viewPermitId')       .val(id);
            $('#viewBusinessName')   .text(p.business_name    || '—');
            $('#viewOwnerName')      .text(p.owner_name       || '—');
            $('#viewBusinessType')   .text(p.business_type    || '—');
            $('#viewBusinessAddress').text(p.business_address || '—');
            $('#viewPermitType')     .text(p.permit_type      || '—');
            $('#viewIssueDate')      .text(formatDate(p.issue_date));
            $('#viewExpiryDate')     .text(formatDate(p.expiry_date));
            $('#viewStatus')         .html(statusBadge(p.status));
            $('#viewFeesPaid')       .text(formatCurrency(p.fees_paid));

            $('#workflowActions').html(workflowButtons(p.status, id));

            const logs = res.activity && res.activity.length
                ? res.activity.map(a => `
                    <div class="border-bottom py-2 small">
                        <strong>${a.action}</strong><br>
                        <span class="text-muted">${a.details || ''}</span><br>
                        <small class="text-secondary">${formatDateTime(a.created_at)}</small>
                    </div>`).join('')
                : '<p class="text-muted text-center py-3"><i class="fas fa-history mr-1"></i>No activity yet</p>';

            $('#activityLog').html(logs);
            $('#viewPermitModal').modal('show');
        }).fail(() => showToast('error', 'Network error. Please try again.'));
    }

    // Click on business name
    $(document).on('click', '.view-permit-link', function (e) {
        e.preventDefault();
        openViewModal($(this).data('id'));
    });

    // Edit button inside view modal
    $(document).on('click', '#editFromViewBtn', function () {
        const id = $('#viewPermitId').val();
        $('#viewPermitModal').modal('hide');
        openEditModal(id);
    });

    // -----------------------------------------------------------------------
    // ADD PERMIT
    // -----------------------------------------------------------------------
    $('#addPermitForm').on('submit', function (e) {
        e.preventDefault();
        const $btn = $(this).find('[type=submit]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Saving…');

        $.post(baseUrl + 'permits/save', $(this).serialize(), function (res) {
            updateCSRF(res);
            if (res.status === 'success') {
                $('#AddNewModal').modal('hide');
                document.getElementById('addPermitForm').reset();
                $('#addIssueDate').val(todayISO);
                showToast('success', res.message);
                reloadTable(); fetchStats();
            } else {
                showToast('error', res.message || 'Save failed.');
            }
        }, 'json')
        .fail(() => showToast('error', 'Server error.'))
        .always(() => $btn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i>Save Permit'));
    });

    $('#AddNewModal').on('hidden.bs.modal', function () {
        document.getElementById('addPermitForm').reset();
        $('#addIssueDate').val(todayISO);
    });

    // -----------------------------------------------------------------------
    // EDIT PERMIT
    // -----------------------------------------------------------------------
    function openEditModal(id) {
        if (!id) return;
        $.get(baseUrl + 'permits/edit/' + id, function (res) {
            if (res.status !== 'success') { showToast('error', res.message || 'Failed to load.'); return; }
            const p = res.data;
            $('#editPermitId')      .val(p.id);
            $('#editBusinessName')  .val(p.business_name);
            $('#editOwnerName')     .val(p.owner_name);
            $('#editBusinessAddress').val(p.business_address);
            $('#editBusinessType')  .val(p.business_type);
            $('#editPermitType')    .val(p.permit_type);
            $('#editIssueDate')     .val(p.issue_date);
            $('#editExpiryDate')    .val(p.expiry_date);
            $('#editNotes')         .val(p.notes);
            $('#editPermitModal').modal('show');
        }).fail(() => showToast('error', 'Network error.'));
    }

    $(document).on('click', '.edit-permit', function () { openEditModal($(this).data('id')); });

    $('#editPermitForm').on('submit', function (e) {
        e.preventDefault();
        const $btn = $(this).find('[type=submit]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Updating…');

        $.post(baseUrl + 'permits/update', $(this).serialize(), function (res) {
            updateCSRF(res);
            if (res.status === 'success') {
                $('#editPermitModal').modal('hide');
                showToast('success', res.message);
                reloadTable(); fetchStats();
            } else {
                showToast('error', res.message || 'Update failed.');
            }
        }, 'json')
        .fail(() => showToast('error', 'Server error.'))
        .always(() => $btn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i>Update Changes'));
    });

    // -----------------------------------------------------------------------
    // DELETE
    // -----------------------------------------------------------------------
    $(document).on('click', '.delete-permit', function () {
        const id = $(this).data('id');
        if (!confirm('Delete this permit? This cannot be undone.')) return;

        $.post(baseUrl + 'permits/delete/' + id, csrfData(), function (res) {
            updateCSRF(res);
            if (res.status === 'success') { showToast('success', res.message); reloadTable(); fetchStats(); }
            else showToast('error', res.message || 'Delete failed.');
        }, 'json').fail(() => showToast('error', 'Server error.'));
    });

    // -----------------------------------------------------------------------
    // WORKFLOW: APPROVE
    // -----------------------------------------------------------------------
    $(document).on('click', '.approve-permit', function () {
        const id = $(this).data('id');
        if (!confirm('Approve this permit application?')) return;

        $.post(baseUrl + 'permits/approve/' + id, csrfData(), function (res) {
            updateCSRF(res);
            if (res.status === 'success') {
                $('#viewPermitModal').modal('hide');
                showToast('success', res.message);
                reloadTable(); fetchStats();
            } else showToast('error', res.message || 'Approve failed.');
        }, 'json').fail(() => showToast('error', 'Server error.'));
    });

    // -----------------------------------------------------------------------
    // WORKFLOW: REJECT
    // -----------------------------------------------------------------------
    $(document).on('click', '.reject-permit', function () {
        $('#rejectPermitId').val($(this).data('id'));
        $('#rejectReason').val('');
        $('#rejectModal').modal('show');
    });

    $('#rejectForm').on('submit', function (e) {
        e.preventDefault();
        const id     = $('#rejectPermitId').val();
        const reason = $('#rejectReason').val().trim();
        if (!reason) { showToast('warning', 'Please enter a rejection reason.'); return; }

        const $btn = $(this).find('[type=submit]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Rejecting…');

        $.post(baseUrl + 'permits/reject/' + id, { ...csrfData(), reason }, function (res) {
            updateCSRF(res);
            if (res.status === 'success') {
                $('#rejectModal').modal('hide');
                $('#viewPermitModal').modal('hide');
                showToast('success', res.message);
                reloadTable(); fetchStats();
            } else showToast('error', res.message || 'Rejection failed.');
        }, 'json')
        .fail(() => showToast('error', 'Server error.'))
        .always(() => $btn.prop('disabled', false).html('<i class="fas fa-ban mr-1"></i>Confirm Rejection'));
    });

    // -----------------------------------------------------------------------
    // WORKFLOW: MARK PAID
    // -----------------------------------------------------------------------
    $(document).on('click', '.mark-paid-btn', function () {
        $('#paidPermitId').val($(this).data('id'));
        $('#feesPaid').val('');
        $('#markPaidModal').modal('show');
    });

    $('#markPaidForm').on('submit', function (e) {
        e.preventDefault();
        const id   = $('#paidPermitId').val();
        const fees = parseFloat($('#feesPaid').val());

        if (!fees || fees <= 0) { showToast('warning', 'Enter a valid amount greater than 0.'); return; }

        const $btn = $(this).find('[type=submit]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Saving…');

        $.post(baseUrl + 'permits/markPaid/' + id, { ...csrfData(), fees_paid: fees }, function (res) {
            updateCSRF(res);
            if (res.status === 'success') {
                $('#markPaidModal').modal('hide');
                $('#viewPermitModal').modal('hide');
                showToast('success', res.message);
                reloadTable(); fetchStats();
            } else showToast('error', res.message || 'Payment failed.');
        }, 'json')
        .fail(() => showToast('error', 'Server error.'))
        .always(() => $btn.prop('disabled', false).html('<i class="fas fa-check mr-1"></i>Confirm Payment'));
    });

    // -----------------------------------------------------------------------
    // WORKFLOW: MARK ACTIVE
    // -----------------------------------------------------------------------
    $(document).on('click', '.mark-active-btn', function () {
        const id = $(this).data('id');
        if (!confirm('Mark this permit as Active?')) return;

        $.post(baseUrl + 'permits/markActive/' + id, csrfData(), function (res) {
            updateCSRF(res);
            if (res.status === 'success') {
                $('#viewPermitModal').modal('hide');
                showToast('success', res.message);
                reloadTable(); fetchStats();
            } else showToast('error', res.message || 'Activation failed.');
        }, 'json').fail(() => showToast('error', 'Server error.'));
    });

    // -----------------------------------------------------------------------
    // PRINT
    // -----------------------------------------------------------------------
    $(document).on('click', '.print-permit', function () {
        const id = $(this).data('id');
        $.get(baseUrl + 'permits/getPrintPreview/' + id, function (res) {
            if (res.status !== 'success') { showToast('error', res.message || 'Preview failed.'); return; }
            $('#printPreviewContent').html(res.html);
            $('#printModal').data('permitId', id).modal('show');
        }).fail(() => showToast('error', 'Network error.'));
    });

    $('#printBtn').on('click', function () {
        const w = window.open('', '', 'width=860,height=680');
        w.document.write('<html><head><title>Print</title></head><body>' + $('#printPreviewContent').html() + '</body></html>');
        w.document.close();
        w.print();

        const id = $('#printModal').data('permitId');
        if (id) {
            $.post(baseUrl + 'permits/printPermit/' + id, csrfData(), function (res) {
                updateCSRF(res);
                reloadTable();
            }, 'json');
        }
    });

});
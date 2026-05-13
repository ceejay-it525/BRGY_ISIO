/**
 * indigents.js — Social Assistance Module
 * Barangay ISIO, Cauayan, Negros Occidental
 *
 * All selectors match the view IDs exactly.
 *
 * Workflow: Pending Assessment → Approved → Completed (no skipping).
 */

'use strict';

// =============================================================================
// TOAST HELPER
// =============================================================================
function showToast(type, message) {
    if (typeof toastr !== 'undefined') {
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: 'toast-top-right',
            timeOut: 3500,
        };
        if (typeof toastr[type] === 'function') {
            toastr[type](message);
            return;
        }
    }

    const cls = { success: 'success', error: 'danger', warning: 'warning', info: 'info' }[type] || 'info';
    const $el = $(`
        <div class="alert alert-${cls} alert-dismissible fade show"
             style="position:fixed;top:70px;right:15px;z-index:99999;min-width:300px;box-shadow:0 2px 12px rgba(0,0,0,.25);">
            ${message}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>`);
    $('body').append($el);
    setTimeout(() => $el.alert('close'), 3500);
}

// =============================================================================
// CSRF
// =============================================================================
function csrfToken() { return $('input[name=csrf_test_name]').val() || ''; }
function csrfData() { return { csrf_test_name: csrfToken() }; }

function refreshCSRF(res) {
    const token = res && (res.csrf_hash || res.csrfHash);
    if (token) $('input[name=csrf_test_name]').val(token);
}

// =============================================================================
// FORMAT HELPERS
// =============================================================================
function fmtDate(d) {
    if (!d || d === '0000-00-00' || d === '0000-00-00 00:00:00') return '—';
    return new Date(d).toLocaleDateString('en-PH', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
}

function fmtDateTime(d) {
    if (!d || d === '0000-00-00 00:00:00') return '—';
    return new Date(d).toLocaleString('en-PH', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
}

function fmtCurrency(v) {
    return '₱' + parseFloat(v || 0).toLocaleString('en-PH', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

function statusBadge(status) {
    const map = {
        'Pending Assessment': ['warning', 'fa-clock'],
        'Approved': ['info', 'fa-check'],
        'Completed': ['success', 'fa-check-circle'],
        'Rejected': ['danger', 'fa-ban'],
    };
    const [cls, icon] = map[status] || ['secondary', 'fa-question-circle'];
    return `<span class="badge badge-${cls}"><i class="fas ${icon} mr-1"></i>${status}</span>`;
}

function categoryBadge(cat) {
    const colors = {
        '4Ps Family': '#17a2b8',
        'Senior Citizen': '#e67e22',
        'PWD': '#e74c3c',
        'Solo Parent': '#3498db',
        'Unemployed': '#2c3e50',
        'Homeless': '#95a5a6',
        'Indigenous People': '#27ae60',
        'Single Mother': '#e91e8c',
        'Widow/Widower': '#fd7e14',
        'Out of School Youth': '#6f42c1',
        'Low Income Family': '#20c997',
        'Disaster Victim': '#c0392b',
    };
    const color = colors[cat] || '#6c757d';
    return `<span class="badge badge-pill text-white" style="background:${color};padding:4px 9px;">${cat || '—'}</span>`;
}

function workflowButtons(status, id) {
    const btn = (cls, action, icon, label) =>
        `<button class="btn btn-${cls} btn-sm ${action} mr-1 mb-1" data-id="${id}">
           <i class="fas ${icon} mr-1"></i>${label}
         </button>`;

    switch (status) {
        case 'Pending Assessment':
            return btn('success', 'js-approve', 'fa-check', 'Approve') +
                   btn('danger', 'js-reject', 'fa-ban', 'Reject');
        case 'Approved':
            return btn('primary', 'js-complete', 'fa-check-circle', 'Mark Completed') +
                   btn('danger', 'js-reject', 'fa-ban', 'Reject');
        case 'Completed':
            return btn('secondary', 'js-print', 'fa-print', 'Print Certificate');
        case 'Rejected':
            return '<span class="badge badge-danger">Rejected — No Further Actions</span>';
        default:
            return '<span class="badge badge-secondary">No Actions Available</span>';
    }
}

// =============================================================================
// DATATABLE
// =============================================================================
let indigentsTable = null;

$(function () {
    if ($.fn.DataTable.isDataTable('#indigentsTable')) {
        $('#indigentsTable').DataTable().destroy();
    }

    indigentsTable = $('#indigentsTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        pageLength: 25,
        order: [[0, 'desc']],
        ajax: {
            url: BASE_URL + 'indigents/fetchRecords',
            type: 'POST',
            data: function (d) {
                return $.extend({}, d, csrfData(), { view_type: CURRENT_URI });
            },
            error: function (xhr) {
                console.error('DataTable AJAX error:', xhr.responseText);
                showToast('error', 'Failed to load records.');
            },
        },
        columns: [
            { data: 'row_number', width: '4%', orderable: false, searchable: false, render: d => `<span class="text-muted small">${d}</span>` },
            { data: 'id', visible: false },
            { data: 'full_name', render: (d, t, r) => `<a href="javascript:void(0)" class="js-view-name font-weight-bold text-primary" data-id="${r.id}"><i class="fas fa-user-circle mr-1"></i>${d || '—'}</a>` },
            { data: 'indigency_category', render: v => categoryBadge(v) },
            { data: 'assistance_type', render: d => d ? `<span><i class="fas fa-hand-holding-heart mr-1 text-success"></i>${d}</span>` : '<span class="text-muted">—</span>' },
            { data: 'assistance_amount', render: v => `<strong class="text-success">${fmtCurrency(v)}</strong>` },
            { data: 'date_assessed', render: fmtDate },
            { data: 'date_provided', render: fmtDate },
            { data: 'status', render: statusBadge },
            {
                data: null,
                orderable: false,
                searchable: false,
                className: 'text-center',
                width: '9%',
                render: r => `
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-info js-view-btn" data-id="${r.id}" title="View"><i class="fas fa-eye"></i></button>
                        <button class="btn btn-warning js-edit-btn" data-id="${r.id}" title="Edit"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-danger js-delete-btn" data-id="${r.id}" title="Delete"><i class="fas fa-trash"></i></button>
                    </div>`
            },
        ],
        language: {
            processing: '<i class="fas fa-spinner fa-spin mr-1"></i>Loading…',
            emptyTable: '<div class="text-center py-4 text-muted"><i class="fas fa-inbox fa-2x d-block mb-2"></i>No records found.</div>',
            zeroRecords: '<div class="text-center py-4 text-muted"><i class="fas fa-search fa-2x d-block mb-2"></i>No matching records.</div>',
        },
    });

    // Preloaded Select2 resident dropdown
    if ($.fn.select2) {
        $('#addResidentId').select2({
            dropdownParent: $('#addIndigentModal'),
            placeholder: '-- Select Resident --',
            allowClear: true,
            width: '100%'
        });
    }

    $('#addResidentId').on('change', function () {
        const id = $(this).val();
        const text = $('#addResidentId option:selected').text().trim();

        if (id) {
            // Resident selected - data will be fetched from server
        } else {
            // Resident deselected
        }
    });

    // Helpers
    function reloadTable() {
        if (indigentsTable) indigentsTable.ajax.reload(null, false);
    }

    function loadStats() {
        $.get(BASE_URL + 'indigents/stats')
            .done(function (res) {
                if (res.status !== 'success') return;
                const d = res.data || {};
                $('#statTotal').text(d.total || 0);
                $('#statPending').text(d.pending || 0);
                $('#statCompleted').text(d.completed || 0);
                $('#statRejected').text(d.rejected || 0);
                $('#pendingCount').text(d.pending || 0);
                $('#approvedCount').text(d.approved || 0);
                $('#releasedCount').text(d.completed || 0);
            });
    }

    loadStats();
    setInterval(loadStats, 30000);

    // VIEW MODAL
    function openViewModal(id) {
        if (!id) return;

        $('#viewFullName, #viewAddress, #viewCategory, #viewAssistanceType, #viewAmount, #viewPurpose, #viewDateAssessed, #viewDateProvided, #viewStatus').text('Loading…');
        $('#workflowActions').html('<i class="fas fa-spinner fa-spin"></i>');
        $('#assistanceHistory').html('<i class="fas fa-spinner fa-spin"></i>');
        $('#viewRemarks').html('<p class="text-muted py-2">Loading…</p>');
        $('#rejectedReasonCard').hide();

        $.get(BASE_URL + 'indigents/view/' + id)
            .done(function (res) {
                if (res.status !== 'success') {
                    showToast('error', res.message || 'Failed to load record.');
                    return;
                }

                refreshCSRF(res);
                const p = res.data || {};

                $('#viewIndigentId').val(p.id);
                $('#viewFullName').text(p.full_name || '—');
                $('#viewAddress').text(p.address || '—');
                $('#viewCategory').html(categoryBadge(p.indigency_category || '—'));
                $('#viewAssistanceType').text(p.assistance_type || '—');
                $('#viewAmount').html(`<strong class="text-success">${fmtCurrency(p.assistance_amount)}</strong>`);
                $('#viewPurpose').text(p.purpose || '—');
                $('#viewDateAssessed').text(fmtDate(p.date_assessed));
                $('#viewDateProvided').text(fmtDate(p.date_provided));
                $('#viewStatus').html(statusBadge(p.status));

                $('#workflowActions').html(workflowButtons(p.status, p.id));

                if (p.status === 'Rejected' && p.rejected_reason) {
                    $('#viewRejectedReason').text(p.rejected_reason);
                    $('#rejectedReasonCard').show();
                }

                $('#viewRemarks').html(
                    p.remarks
                        ? `<p class="mb-0">${$('<div>').text(p.remarks).html()}</p>`
                        : '<p class="text-muted text-center py-2"><i class="fas fa-sticky-note mr-1"></i>No remarks</p>'
                );

                const history = res.history || [];
                if (history.length === 0) {
                    $('#assistanceHistory').html('<p class="text-muted text-center py-2"><i class="fas fa-history mr-1"></i>No previous assistance records</p>');
                } else {
                    const rows = history.map(a => `
                        <div class="border-bottom py-2 small">
                            <strong>${a.assistance_type || 'Assistance'}</strong>
                            ${statusBadge(a.status)}<br>
                            <span class="text-success">${fmtCurrency(a.assistance_amount)}</span>
                            &mdash; ${fmtDate(a.date_assessed)}<br>
                            <small class="text-secondary">${fmtDateTime(a.created_at)}</small>
                        </div>`).join('');
                    $('#assistanceHistory').html(rows);
                }

                $('#viewIndigentModal').modal('show');
            })
            .fail(() => showToast('error', 'Network error. Please try again.'));
    }

    $(document).on('click', '.js-view-name', function (e) {
        e.preventDefault();
        openViewModal($(this).data('id'));
    });

    $(document).on('click', '.js-view-btn', function () {
        openViewModal($(this).data('id'));
    });

    $('#editFromViewBtn').on('click', function () {
        const id = $('#viewIndigentId').val();
        $('#viewIndigentModal').modal('hide');
        openEditModal(id);
    });

    // ADD NEW RECORD
    $('#addIndigentForm').on('submit', function (e) {
        e.preventDefault();

        if (!$('#addResidentId').val()) {
            showToast('warning', 'Please select a resident first.');
            return;
        }

        if (!$('#addIndigencyCategory').val()) {
            showToast('warning', 'Please select an indigency category.');
            return;
        }

        const $btn = $('#addIndigentSubmitBtn').prop('disabled', true)
            .html('<i class="fas fa-spinner fa-spin mr-1"></i>Saving…');

        const formData = $(this).serialize();
        console.log('Form data being sent:', formData);

        $.post(BASE_URL + 'indigents/save', formData, function (res) {
            console.log('Server response:', res);
            refreshCSRF(res);
            if (res.status === 'success') {
                $('#addIndigentModal').modal('hide');
                document.getElementById('addIndigentForm').reset();
                $('#addResidentId').val(null).trigger('change');
                showToast('success', res.message);
                reloadTable();
                loadStats();
            } else {
                showToast('error', res.message || 'Save failed.');
            }
        }, 'json')
        .fail(function(xhr, status, error) {
            console.error('Save error:', xhr.responseText);
            console.error('Status:', status);
            console.error('Error:', error);
            try {
                const errResponse = JSON.parse(xhr.responseText);
                showToast('error', errResponse.message || 'Server error. Please try again.');
            } catch (e) {
                showToast('error', 'Server error. Please try again.');
            }
        })
        .always(() => $btn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i>Save Record'));
    });

    $('#addIndigentModal').on('hidden.bs.modal', function () {
        document.getElementById('addIndigentForm').reset();
        $('#addResidentId').val(null).trigger('change');
    });

    // EDIT RECORD
    function openEditModal(id) {
        if (!id) return;

        $.get(BASE_URL + 'indigents/edit/' + id)
            .done(function (res) {
                if (res.status !== 'success') {
                    showToast('error', res.message || 'Failed to load record.');
                    return;
                }

                refreshCSRF(res);
                const p = res.data || {};

                $('#editId').val(p.id);
                $('#editFullName').val(p.full_name || '');
                $('#editAddress').val(p.address || '');
                $('#editIndigencyCategory').val(p.indigency_category || '');
                $('#editAssistanceType').val(p.assistance_type || '');
                $('#editAssistanceAmount').val(p.assistance_amount || '');
                $('#editPurpose').val(p.purpose || '');
                $('#editRemarks').val(p.remarks || '');

                $('#editIndigentModal').modal('show');
            })
            .fail(() => showToast('error', 'Network error.'));
    }

    $(document).on('click', '.js-edit-btn', function () {
        openEditModal($(this).data('id'));
    });

    $('#editIndigentForm').on('submit', function (e) {
        e.preventDefault();

        if (!$('#editIndigencyCategory').val()) {
            showToast('warning', 'Please select an indigency category.');
            return;
        }

        const $btn = $('#editIndigentSubmitBtn').prop('disabled', true)
            .html('<i class="fas fa-spinner fa-spin mr-1"></i>Updating…');

        $.post(BASE_URL + 'indigents/update', $(this).serialize(), function (res) {
            refreshCSRF(res);
            if (res.status === 'success') {
                $('#editIndigentModal').modal('hide');
                showToast('success', res.message);
                reloadTable();
                loadStats();
            } else {
                showToast('error', res.message || 'Update failed.');
            }
        }, 'json')
        .fail(() => showToast('error', 'Server error.'))
        .always(() => $btn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i>Update Changes'));
    });

    // DELETE
    $(document).on('click', '.js-delete-btn', function () {
        const id = $(this).data('id');
        if (!confirm('Delete this assistance record? This cannot be undone.')) return;

        $.post(BASE_URL + 'indigents/delete/' + id, csrfData(), function (res) {
            refreshCSRF(res);
            if (res.status === 'success') {
                showToast('success', res.message);
                reloadTable();
                loadStats();
            } else {
                showToast('error', res.message || 'Delete failed.');
            }
        }, 'json')
        .fail(() => showToast('error', 'Server error.'));
    });

    // WORKFLOW: APPROVE
    $(document).on('click', '.js-approve', function () {
        const id = $(this).data('id');
        if (!confirm('Approve this assistance request?')) return;

        const $btn = $(this).prop('disabled', true);

        $.post(BASE_URL + 'indigents/approve/' + id, csrfData(), function (res) {
            refreshCSRF(res);
            if (res.status === 'success') {
                $('#viewIndigentModal').modal('hide');
                showToast('success', res.message);
                reloadTable();
                loadStats();
            } else {
                showToast('error', res.message || 'Approve failed.');
            }
        }, 'json')
        .fail(() => showToast('error', 'Server error.'))
        .always(() => $btn.prop('disabled', false));
    });

    // WORKFLOW: REJECT
    $(document).on('click', '.js-reject', function () {
        $('#rejectIndigentId').val($(this).data('id'));
        $('#rejectReason').val('');
        $('#rejectModal').modal('show');
    });

    $('#rejectForm').on('submit', function (e) {
        e.preventDefault();

        const id = $('#rejectIndigentId').val();
        const reason = $('#rejectReason').val().trim();
        if (!reason) {
            showToast('warning', 'Please enter a rejection reason.');
            return;
        }

        const $btn = $('#rejectSubmitBtn').prop('disabled', true)
            .html('<i class="fas fa-spinner fa-spin mr-1"></i>Rejecting…');

        $.post(BASE_URL + 'indigents/reject/' + id, $.extend({}, csrfData(), { reason }), function (res) {
            refreshCSRF(res);
            if (res.status === 'success') {
                $('#rejectModal').modal('hide');
                $('#viewIndigentModal').modal('hide');
                showToast('success', res.message);
                reloadTable();
                loadStats();
            } else {
                showToast('error', res.message || 'Rejection failed.');
            }
        }, 'json')
        .fail(() => showToast('error', 'Server error.'))
        .always(() => $btn.prop('disabled', false).html('<i class="fas fa-ban mr-1"></i>Confirm Rejection'));
    });

    // WORKFLOW: COMPLETE
    $(document).on('click', '.js-complete', function () {
        const id = $(this).data('id');
        if (!confirm('Mark this assistance as completed? This will record the provision date as today.')) return;

        const $btn = $(this).prop('disabled', true);

        $.post(BASE_URL + 'indigents/complete/' + id, csrfData(), function (res) {
            refreshCSRF(res);
            if (res.status === 'success') {
                $('#viewIndigentModal').modal('hide');
                showToast('success', res.message);
                reloadTable();
                loadStats();
            } else {
                showToast('error', res.message || 'Complete failed.');
            }
        }, 'json')
        .fail(() => showToast('error', 'Server error.'))
        .always(() => $btn.prop('disabled', false));
    });

    // PRINT CERTIFICATE
    $(document).on('click', '.js-print', function () {
        const id = $(this).data('id');
        loadPrintPreview(id);
    });

    function loadPrintPreview(id) {
        $('#printPreviewContent').html('<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x"></i></div>');
        $('#printModal').modal('show');

        $.get(BASE_URL + 'indigents/getPrintPreview/' + id)
            .done(function (res) {
                if (res && res.status === 'success' && res.html) {
                    $('#printPreviewContent').html(res.html);
                } else {
                    $('#printPreviewContent').html(
                        `<div class="text-center text-danger py-5">
                            <i class="fas fa-exclamation-circle fa-2x d-block mb-2"></i>
                            ${res.message || 'Failed to load preview.'}
                         </div>`
                    );
                }
            })
            .fail(() => {
                $('#printPreviewContent').html(
                    '<div class="text-center text-danger py-5"><i class="fas fa-exclamation-circle fa-2x d-block mb-2"></i>Network error.</div>'
                );
            });
    }

    $('#printNowBtn').on('click', function () {
        const content = $('#printPreviewContent').html();
        const w = window.open('', '_blank', 'width=860,height=700');
        w.document.write(`<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Indigent Assistance Certificate</title>
  <style>
    @media print {
      @page { margin: 0.8cm; }
      body { margin: 0; }
    }
  </style>
</head>
<body onload="window.print(); window.close();">${content}</body>
</html>`);
        w.document.close();
    });
});
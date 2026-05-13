// ===============================================================
//  BLOTTER MODULE — blotter.js
//  Depends on: jQuery, DataTables, Select2, Toastr, SweetAlert2
// ===============================================================

// ── Select2 Init ────────────────────────────────────────────────
function initSelect2(context) {
    $(context || document).find('.select2-searchable').each(function () {
        if ($(this).hasClass('select2-hidden-accessible')) {
            $(this).select2('destroy');
        }
        $(this).select2({
            placeholder: '-- Type or Select Resident --',
            allowClear: true,
            width: '100%',
            dropdownParent: $(this).closest('.modal').length
                ? $(this).closest('.modal')
                : $(document.body),
            matcher: function (params, data) {
                if ($.trim(params.term) === '') return data;
                var term = params.term.toLowerCase();
                var text = (data.text || '').toLowerCase();
                var extra = ($(data.element).data('search') || '').toLowerCase();
                return (text.indexOf(term) > -1 || extra.indexOf(term) > -1) ? data : null;
            }
        });
    });
}

// ── Toast helper ────────────────────────────────────────────────
function showToast(type, message) {
    if (typeof toastr === 'undefined') { alert(message); return; }
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: 'toast-top-right',
        timeOut: 4000,
        newestOnTop: true
    };
    (toastr[type] || toastr.info)(message);
}

// ── CSRF helpers ─────────────────────────────────────────────────
function getCsrfData() {
    var name  = $('meta[name="csrf-name"]').attr('content')  || 'csrf_test_name';
    var value = $('input[name="' + name + '"]').first().val()
             || $('meta[name="csrf-token"]').attr('content');
    if (!value) { console.error('CSRF token not found'); return null; }
    var d = {}; d[name] = value; return d;
}

function updateCSRF(res) {
    if (res && res.csrf_hash) {
        $('input[name="csrf_test_name"]').val(res.csrf_hash);
    }
}

// ── Escape helper ─────────────────────────────────────────────────
function escapeHtml(v) {
    return String(v || '').replace(/[&<>"]/g, function (s) {
        return ({ '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;' })[s];
    });
}

// ── Print ────────────────────────────────────────────────────────
function printBlotterRecord(record) {
    var w = window.open('', '_blank');
    if (!w) { showToast('error', 'Allow popups to print.'); return; }
    function row(label, val) {
        return '<tr><td style="font-weight:700;width:180px;padding:8px 4px;vertical-align:top;">'
            + escapeHtml(label) + '</td><td style="padding:8px 4px;">'
            + escapeHtml(val || '—') + '</td></tr>';
    }
    w.document.write('<!DOCTYPE html><html><head><meta charset="UTF-8">'
        + '<title>Blotter Record</title>'
        + '<style>body{font-family:Arial,sans-serif;margin:24px;color:#222}'
        + 'h1{font-size:22px;margin-bottom:16px}'
        + 'table{width:100%;border-collapse:collapse}'
        + 'td{border-bottom:1px solid #ddd;padding:8px 4px}'
        + '.sec{margin:20px 0 8px;font-size:15px;font-weight:700}'
        + '@media print{button{display:none}}</style></head><body>'
        + '<h1>Blotter Record</h1><table>'
        + row('Case Number',  record.case_number)
        + row('Incident Type',record.incident_type)
        + row('Incident Date',record.incident_date)
        + row('Complainant',  record.complainant_full_name)
        + row('Respondent',   record.respondent_full_name)
        + row('Location',     record.incident_location)
        + row('Status',       record.status)
        + row('Narrative',    record.narrative)
        + row('Action Taken', record.action_taken)
        + '</table><div class="sec">Printed on</div>'
        + '<div>' + new Date().toLocaleString() + '</div>'
        + '</body></html>');
    w.document.close();
    w.focus();
    setTimeout(function () { w.print(); }, 300);
}

// ── Form validation ───────────────────────────────────────────────
function validateForm(formId) {
    var valid = true;
    $(formId + ' [required]').each(function () {
        $(this).removeClass('is-invalid');
        var val = $(this).val();
        if (!val || !String(val).trim()) {
            $(this).addClass('is-invalid');
            valid = false;
        }
    });
    return valid;
}

// ── Status badge renderer ─────────────────────────────────────────
function statusBadge(d) {
    var map = {
        'Pending':   'badge-pending',
        'Ongoing':   'badge-ongoing',
        'Settled':   'badge-settled',
        'Referred':  'badge-referred',
        'Dismissed': 'badge-dismissed'
    };
    return '<span class="badge-custom ' + (map[d] || 'badge-custom') + '">' + (d || '—') + '</span>';
}

// ================================================================
//  DOCUMENT READY
// ================================================================
$(document).ready(function () {

    // Hero date
    var today = new Date();
    $('#heroDate').text(today.toLocaleDateString('en-PH', {
        year: 'numeric', month: 'long', day: 'numeric'
    }));

    // Init Select2 on page load (for add modal selects)
    initSelect2();

    // ── DataTable ──────────────────────────────────────────────
    var currentFilter = 'all';
    var selectedRecord = null;

    if ($.fn.DataTable.isDataTable('#blotterTable')) {
        $('#blotterTable').DataTable().destroy();
    }

    var blotterTable = $('#blotterTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        pageLength: 25,
        order: [[0, 'desc']],
        searching: false,
        dom: 'lrtip',
        language: {
            processing: '<i class="fas fa-spinner fa-spin fa-2x text-primary"></i>',
            emptyTable: 'No blotter records found.',
            zeroRecords: 'No records match your filter.'
        },
        ajax: {
            url: baseUrl + 'blotter/fetchRecords',
            type: 'POST',
            data: function (d) {
                d.csrf_test_name = $('input[name="csrf_test_name"]').first().val();
                d.searchtype = 'all';
                d.searchterm = '';
                d.filter = currentFilter !== 'all' ? currentFilter : '';
            },
            error: function (xhr) {
                showToast('error', 'Failed to load blotter records. (' + xhr.status + ')');
            }
        },
        columns: [
            {
                data: 'row_number',
                width: '5%',
                render: function (d) {
                    return '<span class="row-num">' + d + '</span>';
                }
            },
            { data: 'id', visible: false },
            {
                data: 'case_number',
                render: function (d) {
                    return d
                        ? '<span class="badge-custom" style="background:#e3f0fb;color:#1d6fa4;padding:4px 8px;border-radius:4px;">' + escapeHtml(d) + '</span>'
                        : '<span class="text-muted">—</span>';
                }
            },
            {
                data: 'complainant_full_name',
                render: function (d) { return d ? escapeHtml(d.trim()) : '<span class="text-muted">—</span>'; }
            },
            {
                data: 'respondent_full_name',
                render: function (d) { return d ? escapeHtml(d.trim()) : '<span class="text-muted">—</span>'; }
            },
            {
                data: 'incident_type',
                render: function (d) { return d ? escapeHtml(d) : '<span class="text-muted">—</span>'; }
            },
            {
                data: 'incident_date',
                render: function (d) {
                    if (!d) return '<span class="text-muted">—</span>';
                    var date = new Date(d);
                    return date.toLocaleDateString('en-PH', {
                        year: 'numeric', month: 'short', day: 'numeric'
                    });
                }
            },
            {
                data: 'incident_location',
                render: function (d) {
                    if (!d) return '<span class="text-muted">—</span>';
                    return escapeHtml(d.length > 30 ? d.substring(0, 30) + '…' : d);
                }
            },
            {
                data: 'status',
                width: '12%',
                render: function (d) { return statusBadge(d); }
            },
            {
                data: null,
                width: '10%',
                orderable: false,
                searchable: false,
                className: 'text-center',
                render: function (row) {
                    return '<button class="btn-action btn-edit edit-blotter mr-1" data-id="' + row.id + '" title="Edit Record">'
                         + '<i class="fas fa-edit"></i></button>'
                         + '<button class="btn-action btn-delete delete-blotter" data-id="' + row.id + '" title="Delete Record">'
                         + '<i class="fas fa-trash"></i></button>';
                }
            }
        ],
        drawCallback: function () {
            $('[title]').tooltip({ trigger: 'hover' });
        }
    });

    // ── Stat cards (server-side totals via separate AJAX) ──────
    function refreshStatCards() {
        $.post(baseUrl + 'blotter/fetchRecords', {
            draw: 0, start: 0, length: 9999,
            csrf_test_name: $('input[name="csrf_test_name"]').first().val(),
            searchtype: 'all', searchterm: '', filter: ''
        }, function (res) {
            if (!res || !res.data) return;
            var total = res.recordsTotal || 0;
            var ongoing = 0, settled = 0, dismissed = 0;
            $.each(res.data, function (i, r) {
                if (r.status === 'Ongoing')   ongoing++;
                if (r.status === 'Settled')   settled++;
                if (r.status === 'Dismissed') dismissed++;
            });
            $('#totalCases').text(total);
            $('#statOngoing').text(ongoing);
            $('#statSettled').text(settled);
            $('#statDismissed').text(dismissed);
        }, 'json');
    }
    refreshStatCards();

    blotterTable.on('draw.dt', function () {
        refreshStatCards();
    });

    // ── Filter pills ───────────────────────────────────────────
    $('.filter-pill').on('click', function () {
        var filter = $(this).data('filter');
        currentFilter = filter;
        $('.filter-pill').removeClass('active');
        $(this).addClass('active');
        $('#tableFilterLabel').text(filter !== 'all' ? '(Filter: ' + filter + ')' : '');
        blotterTable.ajax.reload();
    });

    // ── Refresh button ─────────────────────────────────────────
    $('#btnRefreshTable').on('click', function () {
        var $icon = $(this).find('i');
        $icon.addClass('fa-spin');
        blotterTable.ajax.reload(function () {
            $icon.removeClass('fa-spin');
        });
    });

    // ── Row click → select record ──────────────────────────────
    $('#blotterTable tbody').on('click', 'tr', function (e) {
        if ($(e.target).closest('button').length) return;
        var rowData = blotterTable.row(this).data();
        if (!rowData) return;
        $('#blotterTable tbody tr').removeClass('selected');
        $(this).addClass('selected');
        selectedRecord = rowData;
    });

    // ================================================================
    //  ADD MODAL — open/close
    // ================================================================
    $('#addBlotterModal').on('shown.bs.modal', function () {
        initSelect2('#addBlotterModal');
        $('#addComplainantId').val(null).trigger('change');
        $('#addRespondentId').val(null).trigger('change');
    });

    $('#addBlotterModal').on('hidden.bs.modal', function () {
        $('#addBlotterForm')[0].reset();
        $('#addBlotterForm .form-control, #addBlotterForm select').removeClass('is-invalid');
        // Reset Select2 values
        $('#addComplainantId, #addRespondentId').val(null).trigger('change');
    });

    // ── ADD Submit ────────────────────────────────────────────
    $('#addBlotterForm').on('submit', function (e) {
        e.preventDefault();
        if (!validateForm('#addBlotterForm')) {
            showToast('warning', 'Please fill all required fields.');
            return;
        }
        var $btn = $(this).find('[type=submit]');
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Saving…');

        $.ajax({
            url: baseUrl + 'blotter/save',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (res) {
                if (res.status === 'success') {
                    $('#addBlotterModal').modal('hide');
                    showToast('success', res.message || 'Record saved successfully.');
                    blotterTable.ajax.reload(null, false);
                } else {
                    showToast('error', res.message || 'Failed to save record.');
                }
                updateCSRF(res);
            },
            error: function () { showToast('error', 'Server error. Please try again.'); },
            complete: function () {
                $btn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Save Record');
            }
        });
    });

    // ================================================================
    //  EDIT — fetch record then open modal
    // ================================================================
    $(document).on('click', '.edit-blotter', function () {
        var id = $(this).data('id');
        if (!id) { showToast('error', 'Invalid record ID.'); return; }

        $.get(baseUrl + 'blotter/get/' + id, function (res) {
            if (res.status !== 'success') {
                showToast('error', res.message || 'Record not found.');
                return;
            }
            var r = res.data;

            // Populate fields
            $('#editBlotterId').val(r.id);
            $('#editCaseNumber').val(r.case_number || '');
            $('#editIncidentType').val(r.incident_type || '');
            $('#editIncidentDate').val(r.incident_date || '');
            $('#editIncidentLocation').val(r.incident_location || '');
            $('#editStatus').val(r.status || 'Pending');
            $('#editNarrative').val(r.narrative || '');
            $('#editActionTaken').val(r.action_taken || '');

            // Show modal first, then init Select2 and set values
            $('#editBlotterModal').modal('show');

            // Set Select2 values after modal is visible
            $('#editBlotterModal').one('shown.bs.modal', function () {
                initSelect2('#editBlotterModal');
                $('#editComplainantId').val(r.complainant_resident_id || null).trigger('change');
                $('#editRespondentId').val(r.respondent_resident_id || null).trigger('change');
            });

        }).fail(function () { showToast('error', 'Failed to load record details.'); });
    });

    $('#editBlotterModal').on('hidden.bs.modal', function () {
        $('#editBlotterForm')[0].reset();
        $('#editBlotterForm .form-control, #editBlotterForm select').removeClass('is-invalid');
    });

    // ── EDIT Submit ───────────────────────────────────────────
    $('#editBlotterForm').on('submit', function (e) {
        e.preventDefault();
        if (!validateForm('#editBlotterForm')) {
            showToast('warning', 'Please fill all required fields.');
            return;
        }
        var $btn = $(this).find('[type=submit]');
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Updating…');

        $.ajax({
            url: baseUrl + 'blotter/update',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (res) {
                if (res.status === 'success') {
                    $('#editBlotterModal').modal('hide');
                    showToast('success', res.message || 'Record updated successfully.');
                    blotterTable.ajax.reload(null, false);
                } else {
                    showToast('error', res.message || 'Failed to update record.');
                }
                updateCSRF(res);
            },
            error: function () { showToast('error', 'Server error. Please try again.'); },
            complete: function () {
                $btn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Update Record');
            }
        });
    });

    // ================================================================
    //  DELETE
    // ================================================================
    $(document).on('click', '.delete-blotter', function () {
        var id = $(this).data('id');
        if (!id) { showToast('error', 'Invalid record ID.'); return; }

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Delete Record?',
                html: 'Are you sure you want to <strong>permanently delete</strong> this blotter record?<br><small class="text-muted">This action cannot be undone.</small>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-trash mr-1"></i> Yes, Delete It',
                cancelButtonText: '<i class="fas fa-times mr-1"></i> Cancel'
            }).then(function (result) {
                if (result.isConfirmed) executeDelete(id);
            });
        } else {
            if (confirm('Permanently delete this blotter record? This cannot be undone.')) {
                executeDelete(id);
            }
        }
    });

    function executeDelete(id) {
        var csrfData = getCsrfData();
        if (!csrfData) { showToast('error', 'Missing CSRF token. Please refresh the page.'); return; }

        $.post(baseUrl + 'blotter/delete/' + id, csrfData, function (res) {
            if (res.status === 'success') {
                showToast('success', res.message || 'Record deleted.');
                selectedRecord = null;
                blotterTable.ajax.reload(null, false);
            } else {
                showToast('error', res.message || 'Failed to delete record.');
            }
            updateCSRF(res);
        }, 'json').fail(function () {
            showToast('error', 'Failed to delete record. Please try again.');
        });
    }

    // ── Tooltips ──────────────────────────────────────────────
    $(window).on('load', function () { $('[title]').tooltip({ trigger: 'hover' }); });

}); // end document.ready

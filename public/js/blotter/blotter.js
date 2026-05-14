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

        timeOut: '4000',

        newestOnTop: true

    };

    toastr[type] ? toastr[type](message) : toastr.info(message);

}

// ================= STATUS CONFIG =================

const STATUS_FLOW = ['Ongoing', 'Investigation', 'Mediation', 'Settled', 'Closed'];

const STATUS_BADGE_MAP = {

    'Ongoing':       { cls: 'badge-warning text-dark', icon: 'fas fa-clock' },

    'Investigation': { cls: 'badge-primary',            icon: 'fas fa-search' },

    'Mediation':     { cls: 'badge-info',               icon: 'fas fa-handshake' },

    'Settled':       { cls: 'badge-success',            icon: 'fas fa-check-circle' },

    'Closed':        { cls: 'badge-secondary',          icon: 'fas fa-lock' }

};

const ADVANCE_BTN_MAP = {

    'Ongoing':       { cls: 'btn-primary',   label: 'Move to Investigation' },

    'Investigation': { cls: 'btn-info',      label: 'Move to Mediation' },

    'Mediation':     { cls: 'btn-success',   label: 'Mark as Settled' },

    'Settled':       { cls: 'btn-secondary', label: 'Close Case' },

    'Closed':        null

};

function statusBadgeHtml(status) {

    const b = STATUS_BADGE_MAP[status] || { cls: 'badge-primary', icon: 'fas fa-question' };

    return `<span class="badge ${b.cls} p-2" style="font-size:11px;">

                <i class="${b.icon} mr-1"></i>${status ?? '—'}

            </span>`;

}

// ================= DATATABLE INIT =================

let blotterTable;

let selectedBlotterRecord = null;

$(document).ready(function () {

    if ($.fn.DataTable.isDataTable('#blotterTable')) {

        $('#blotterTable').DataTable().destroy();

    }

    blotterTable = $('#blotterTable').DataTable({

        processing: true,

        serverSide: true,

        responsive: true,

        pageLength: 25,

        order: [[0, 'desc']],

        searching: false,

        dom: 'lrtip',

        language: {

            processing: '<i class="fas fa-spinner fa-spin fa-2x"></i>'

        },

        ajax: {

            url: baseUrl + 'blotter/fetchRecords',

            type: 'POST',

            data: function (d) {

                d.csrf_test_name = $('input[name=csrf_test_name]').val();

                d.search_type = $('#blotterSearchType').val() || 'all';

                d.search_term = $('#blotterSearchInput').val().trim();

            },

            error: function () {

                showToast('error', 'Failed to load blotter records.');

            }

        },

        columns: [

            { data: 'row_number', width: '5%' },

            { data: 'id', visible: false },

            {

                data: 'case_number',

                render: d => d ? d : '<span class="text-muted">—</span>'

            },

            {

                data: 'complainant_name',

                render: d => d ? d : '<span class="text-muted">—</span>'

            },

            {

                data: 'respondent_name',

                render: d => d ? d : '<span class="text-muted">—</span>'

            },

            {

                data: 'incident_type',

                render: d => d ? d : '<span class="text-muted">—</span>'

            },

            {

                data: 'incident_date',

                render: function (d) {

                    if (!d) return '<span class="text-muted">—</span>';

                    const date = new Date(d);

                    return date.toLocaleDateString('en-PH', {

                        year: 'numeric', month: 'short', day: 'numeric'

                    });

                }

            },

            {

                data: 'incident_location',

                render: function (d) {

                    if (!d) return '<span class="text-muted">—</span>';

                    return d.length > 35 ? d.substring(0, 35) + '…' : d;

                }

            },

            {

                data: 'status',

                width: '10%',

                render: function (d) {

                    return statusBadgeHtml(d);

                }

            },

            {

                data: null,

                width: '12%',

                orderable: false,

                searchable: false,

                className: 'text-center',

                render: function (row) {

                    return `

                        <div class="btn-group btn-group-sm">

                            <button class="btn btn-info view-blotter" data-id="${row.id}" title="View">

                                <i class="fas fa-eye"></i>

                            </button>

                            <button class="btn btn-warning edit-blotter" data-id="${row.id}" title="Edit">

                                <i class="fas fa-edit"></i>

                            </button>

                            <button class="btn btn-danger delete-blotter" data-id="${row.id}" title="Delete">

                                <i class="fas fa-trash"></i>

                            </button>

                        </div>`;

                }

            }

        ],

        drawCallback: function () {

            $('[title]').tooltip();

        }

    });

    blotterTable.on('xhr.dt', function () {

        clearBlotterDetails();

    });

    $('#blotterSearchBtn').on('click', function () {

        blotterTable.ajax.reload();

    });

    $('#blotterSearchInput').on('keypress', function (e) {

        if (e.which === 13) {

            blotterTable.ajax.reload();

            return false;

        }

    });

    $('#blotterSearchType').on('change', function () {

        blotterTable.ajax.reload();

    });

    $('#blotterResetSearch').on('click', function () {

        $('#blotterSearchType').val('all');

        $('#blotterSearchInput').val('');

        blotterTable.ajax.reload();

    });

    // ================= ROW CLICK — populate detail panel =================

    $('#blotterTable tbody').on('click', 'tr', function (e) {

        if ($(e.target).closest('button, .btn').length) {

            return;

        }

        const rowData = blotterTable.row(this).data();

        if (!rowData) return;

        $('#blotterTable tbody tr.selected').removeClass('selected');

        $(this).addClass('selected');

        selectedBlotterRecord = rowData;

        updateBlotterDetails(rowData);

    });

    // ================= DETAIL PANEL BUTTONS =================

    $('#editSelectedRecord').on('click', function () {

        if (!selectedBlotterRecord) { showToast('warning', 'Please select a blotter record first.'); return; }

        $('.edit-blotter[data-id="' + selectedBlotterRecord.id + '"]').click();

    });

    $('#deleteSelectedRecord').on('click', function () {

        if (!selectedBlotterRecord) { showToast('warning', 'Please select a blotter record first.'); return; }

        $('.delete-blotter[data-id="' + selectedBlotterRecord.id + '"]').click();

    });

    $('#printSelectedRecord').on('click', function () {

        if (!selectedBlotterRecord) { showToast('warning', 'Please select a blotter record first.'); return; }

        const id = selectedBlotterRecord.id;

        if (!id) { showToast('error', 'Invalid record ID.'); return; }

        $.get(baseUrl + 'blotter/get/' + id, function (res) {

            if (res.status === 'success') {

                selectedBlotterRecord = res.data;

                printBlotterRecord(res.data);

            } else {

                showToast('error', res.message || 'Record not found.');

            }

            updateCSRF(res);

        }, 'json').fail(function () {

            showToast('error', 'Failed to load record for printing.');

        });

    });

    // ================= DETAIL PANEL HELPERS =================

    function updateBlotterDetails(record) {

        if (!record) {

            selectedBlotterRecord = null;

            $('#selectedRecordLabel').text('None');

            $('#detailCaseNumber').text('—');

            $('#detailIncidentType').text('—');

            $('#detailComplainantName').text('—');

            $('#detailRespondentName').text('—');

            $('#detailLocation').text('—');

            $('#detailIncidentDate').text('—');

            $('#detailStatus').html('<span class="badge badge-secondary">—</span>');

            $('#detailNarrative').text('No record selected.');

            $('#detailActionTaken').text('No record selected.');

            $('#editSelectedRecord, #deleteSelectedRecord, #printSelectedRecord').prop('disabled', true);

            return;

        }

        $('#selectedRecordLabel').text(record.case_number ? 'Case #' + record.case_number : 'ID ' + record.id);

        $('#detailCaseNumber').text(record.case_number || '—');

        $('#detailIncidentType').text(record.incident_type || '—');

        $('#detailComplainantName').text(record.complainant_name || '—');

        $('#detailRespondentName').text(record.respondent_name || '—');

        $('#detailLocation').text(record.incident_location || '—');

        $('#detailIncidentDate').text(record.incident_date || '—');

        $('#detailStatus').html(statusBadgeHtml(record.status));

        $('#detailNarrative').text(record.narrative || 'No narrative provided.');

        $('#detailActionTaken').text(record.action_taken || 'No action recorded.');

        $('#editSelectedRecord, #deleteSelectedRecord, #printSelectedRecord').prop('disabled', false);

    }

    function clearBlotterDetails() {

        selectedBlotterRecord = null;

        $('#blotterTable tbody tr.selected').removeClass('selected');

        updateBlotterDetails(null);

    }

    function refreshSelectedRecord(id) {

        if (!id) return;

        $.get(baseUrl + 'blotter/get/' + id, function (res) {

            if (res.status === 'success') {

                selectedBlotterRecord = res.data;

                updateBlotterDetails(res.data);

            }

            updateCSRF(res);

        }, 'json').fail(function () {

            showToast('error', 'Failed to refresh selected record.');

        });

    }

    // ================= VIEW BUTTON =================

    $(document).on('click', '.view-blotter', function () {

        const id = $(this).data('id');

        if (!id) { showToast('error', 'Invalid record ID.'); return; }

        $.get(baseUrl + 'blotter/get/' + id, function (res) {

            if (res.status === 'success') {

                populateViewModal(res.data);

                $('#viewBlotterModal').modal('show');

            } else {

                showToast('error', res.message || 'Record not found.');

            }

            updateCSRF(res);

        }).fail(function () {

            showToast('error', 'Failed to load record.');

        });

    });

    // ================= VIEW MODAL — POPULATE =================

    function populateViewModal(record) {

        $('#viewBlotterModal').data('record-id', record.id);

        $('#viewModalCaseNo').text(record.case_number ? '#' + record.case_number : 'ID ' + record.id);

        $('#vCaseNumber').text(record.case_number || '—');

        $('#vIncidentType').text(record.incident_type || '—');

        $('#vComplainant').text(record.complainant_name || '—');

        $('#vRespondent').text(record.respondent_name || '—');

        $('#vLocation').text(record.incident_location || '—');

        $('#vIncidentDate').text(

            record.incident_date

                ? new Date(record.incident_date).toLocaleDateString('en-PH', { year: 'numeric', month: 'long', day: 'numeric' })

                : '—'

        );

        $('#vStatus').html(statusBadgeHtml(record.status));

        $('#vNarrative').text(record.narrative || 'No narrative provided.');

        $('#vActionTaken').text(record.action_taken || 'No action recorded.');

        renderTimeline(record.status);

        renderAdvanceButton(record.id, record.status);

    }

    // ================= TIMELINE RENDERER =================

    function renderTimeline(currentStatus) {

        const idx = STATUS_FLOW.indexOf(currentStatus);

        $('#blotterTimeline .tl-step').each(function () {

            const stepIdx = STATUS_FLOW.indexOf($(this).data('step'));

            $(this).removeClass('done active');

            if (stepIdx < idx)        $(this).addClass('done');

            else if (stepIdx === idx) $(this).addClass('active');

        });

        const totalSteps = STATUS_FLOW.length - 1;

        const pct = idx > 0 ? (idx / totalSteps) * 100 : 0;

        $('#tlProgress').css('width', pct + '%');

    }

    // ================= ADVANCE BUTTON RENDERER =================

    function renderAdvanceButton(id, status) {

        const cfg = ADVANCE_BTN_MAP[status];

        if (cfg) {

            $('#btnAdvanceStatus')

                .show()

                .removeClass('btn-primary btn-info btn-success btn-secondary btn-warning btn-danger')

                .addClass(cfg.cls)

                .data('id', id)

                .data('status', status);

            $('#btnAdvanceLabel').text(cfg.label);

            $('#statusClosedBadge').hide();

        } else {

            $('#btnAdvanceStatus').hide();

            $('#statusClosedBadge').show();

        }

    }

    // ================= ADVANCE STATUS CLICK =================

    $('#btnAdvanceStatus').on('click', function () {

        const id     = $(this).data('id');

        const status = $(this).data('status');

        const next   = STATUS_FLOW[STATUS_FLOW.indexOf(status) + 1];

        if (!id) return;

        const doAdvance = function () {

            const csrfData = getCsrfData();

            if (!csrfData) { showToast('error', 'Missing CSRF token.'); return; }

            $.post(baseUrl + 'blotter/advanceStatus/' + id, csrfData, function (res) {

                if (res.status === 'success') {

                    showToast('success', res.message);

                    renderTimeline(res.new_status);

                    renderAdvanceButton(id, res.new_status);

                    $('#vStatus').html(statusBadgeHtml(res.new_status));

                    blotterTable.ajax.reload(null, false);

                    // refresh detail panel if same record selected

                    if (selectedBlotterRecord && selectedBlotterRecord.id == id) {

                        refreshSelectedRecord(id);

                    }

                } else {

                    showToast('error', res.message || 'Failed to advance status.');

                }

                updateCSRF(res);

            }, 'json').fail(function () {

                showToast('error', 'Server error.');

            });

        };

        if (typeof Swal !== 'undefined') {

            Swal.fire({

                title: 'Advance Status?',

                html: `Move case from <strong>${status}</strong> to <strong>${next}</strong>?`,

                icon: 'question',

                showCancelButton: true,

                confirmButtonColor: '#28a745',

                cancelButtonColor: '#6c757d',

                confirmButtonText: '<i class="fas fa-arrow-circle-right mr-1"></i> Yes, Advance',

                cancelButtonText: 'Cancel'

            }).then(function (result) {

                if (result.isConfirmed) doAdvance();

            });

        } else {

            if (confirm('Move case from "' + status + '" to "' + next + '"?')) doAdvance();

        }

    });

    // ================= VIEW MODAL PRINT =================

    $('#btnViewPrint').on('click', function () {

        const id = $('#viewBlotterModal').data('record-id');

        if (!id) return;

        $.get(baseUrl + 'blotter/get/' + id, function (res) {

            if (res.status === 'success') printBlotterRecord(res.data);

            updateCSRF(res);

        });

    });

    // ================= EDIT FETCH =================

    $(document).on('click', '.edit-blotter', function () {

        const id = $(this).data('id');

        if (!id) { showToast('error', 'Invalid record ID.'); return; }

        $.get(baseUrl + 'blotter/get/' + id, function (res) {

            if (res.status === 'success') {

                const r = res.data;

                $('#editBlotterForm .form-control').removeClass('is-invalid');

                $('#editBlotterId').val(r.id);

                $('#editCaseNumber').val(r.case_number || '');

                $('#editIncidentType').val(r.incident_type || '');

                $('#editIncidentDate').val(r.incident_date || '');

                $('#editComplainantName').val(r.complainant_name || '');

                $('#editRespondentName').val(r.respondent_name || '');

                $('#editIncidentLocation').val(r.incident_location || '');

                $('#editStatus').val(r.status || 'Ongoing');

                $('#editNarrative').val(r.narrative || '');

                $('#editActionTaken').val(r.action_taken || '');

                $('#editBlotterModal').modal('show');

            } else {

                showToast('error', res.message || 'Record not found.');

            }

            updateCSRF(res);

        }).fail(function () {

            showToast('error', 'Failed to load record details.');

        });

    });

    // ================= ADD =================

    $('#addBlotterForm').on('submit', function (e) {

        e.preventDefault();

        if (!validateForm('#addBlotterForm')) {

            showToast('warning', 'Please fill all required fields.');

            return;

        }

        const btn = $(this).find('[type=submit]');

        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Saving…');

        $.ajax({

            url: baseUrl + 'blotter/save',

            type: 'POST',

            data: $(this).serialize(),

            dataType: 'json',

            success: function (res) {

                if (res.status === 'success') {

                    $('#addBlotterModal').modal('hide');

                    $('#addBlotterForm')[0].reset();

                    showToast('success', res.message);

                    blotterTable.ajax.reload(null, false);

                } else {

                    showToast('error', res.message || 'Failed to save record.');

                }

                updateCSRF(res);

            },

            error: function () { showToast('error', 'Server error. Please try again.'); },

            complete: function () { btn.prop('disabled', false).html('<i class="fa fa-save mr-1"></i> Save Record'); }

        });

    });

    // ================= UPDATE =================

    $('#editBlotterForm').on('submit', function (e) {

        e.preventDefault();

        if (!validateForm('#editBlotterForm')) {

            showToast('warning', 'Please fill all required fields.');

            return;

        }

        const btn = $(this).find('[type=submit]');

        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Updating…');

        $.ajax({

            url: baseUrl + 'blotter/update',

            type: 'POST',

            data: $(this).serialize(),

            dataType: 'json',

            success: function (res) {

                if (res.status === 'success') {

                    $('#editBlotterModal').modal('hide');

                    showToast('success', res.message);

                    const editedId = $('#editBlotterId').val();

                    blotterTable.ajax.reload(null, false);

                    if (selectedBlotterRecord && selectedBlotterRecord.id == editedId) {

                        refreshSelectedRecord(editedId);

                    }

                } else {

                    showToast('error', res.message || 'Failed to update record.');

                }

                updateCSRF(res);

            },

            error: function () { showToast('error', 'Server error. Please try again.'); },

            complete: function () { btn.prop('disabled', false).html('<i class="fa fa-save mr-1"></i> Update Record'); }

        });

    });

    // ================= DELETE =================

    $(document).on('click', '.delete-blotter', function () {

        const id = $(this).data('id');

        if (!id) { showToast('error', 'Invalid record ID.'); return; }

        if (!confirm('Are you sure you want to permanently delete this blotter record? This action cannot be undone.')) return;

        executeBlotterDelete(id);

    });

    // ================= RESET MODALS =================

    $('#addBlotterModal, #editBlotterModal').on('hidden.bs.modal', function () {

        $(this).find('form')[0].reset();

        $(this).find('.form-control').removeClass('is-invalid');

    });

    $(window).on('load', function () {

        $('[title]').tooltip();

    });

});

// ================= DELETE EXECUTOR =================

function executeBlotterDelete(id) {

    const csrfData = getCsrfData();

    if (!csrfData) { showToast('error', 'Unable to delete record: missing CSRF token.'); return; }

    $.post(baseUrl + 'blotter/delete/' + id, csrfData, function (res) {

        if (res.status === 'success') {

            showToast('success', res.message);

            selectedBlotterRecord = null;

            clearBlotterDetails();

            blotterTable.ajax.reload(null, false);

            $('#viewBlotterModal').modal('hide');

        } else {

            showToast('error', res.message || 'Failed to delete record.');

        }

        updateCSRF(res);

    }, 'json').fail(function () {

        showToast('error', 'Failed to delete record.');

    });

}

// ================= FORM VALIDATION =================

function validateForm(formId) {

    let valid = true;

    $(formId).find('input[required], select[required], textarea[required]').each(function () {

        const field = $(this);

        field.removeClass('is-invalid');

        if (!field.val().trim()) {

            field.addClass('is-invalid');

            valid = false;

        }

    });

    return valid;

}

// ================= UTILITIES =================

function getCsrfData() {

    const tokenName = $('meta[name=csrf-name]').attr('content') || 'csrf_test_name';

    const tokenValue = $('input[name="' + tokenName + '"]').val() || $('meta[name=csrf-token]').attr('content');

    if (!tokenName || !tokenValue) return null;

    const data = {};

    data[tokenName] = tokenValue;

    return data;

}

function updateCSRF(res) {

    if (res && res.csrf_hash) {

        const tokenName = $('meta[name=csrf-name]').attr('content') || 'csrf_test_name';

        $('meta[name=csrf-token]').attr('content', res.csrf_hash);

        $('input[name="' + tokenName + '"]').val(res.csrf_hash);

        $('input[name=csrf_test_name]').val(res.csrf_hash);

    }

}

// ================= PRINT =================

function escapeHtml(value) {

    return String(value || '').replace(/[&<>"]/g, function (s) {

        return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' })[s];

    });

}

function buildPrintRow(label, value) {

    return `

        <tr>

            <td style="padding:8px 4px;font-weight:700;vertical-align:top;width:20%;">${escapeHtml(label)}</td>

            <td style="padding:8px 4px;">${escapeHtml(value || '—')}</td>

        </tr>`;

}

function printBlotterRecord(record) {

    const printWindow = window.open('', '_blank');

    if (!printWindow) {

        showToast('error', 'Unable to open print window. Please allow popups for this site.');

        return;

    }

    const html = `

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Print Blotter Record</title>

    <style>

        body { font-family: Arial, sans-serif; margin: 24px; color: #222; }

        h1 { font-size: 24px; margin-bottom: 16px; }

        table { width: 100%; border-collapse: collapse; }

        td { border-bottom: 1px solid #ddd; padding: 10px 4px; }

        td:first-child { font-weight: 700; width: 180px; vertical-align: top; }

        .section-title { margin: 24px 0 8px; font-size: 16px; font-weight: 700; }

    </style>

</head>

<body>

    <h1>Blotter Record</h1>

    <table>

        ${buildPrintRow('Case Number', record.case_number)}

        ${buildPrintRow('Incident Type', record.incident_type)}

        ${buildPrintRow('Incident Date', record.incident_date)}

        ${buildPrintRow('Complainant', record.complainant_name)}

        ${buildPrintRow('Respondent', record.respondent_name)}

        ${buildPrintRow('Location', record.incident_location)}

        ${buildPrintRow('Status', record.status)}

        ${buildPrintRow('Narrative', record.narrative)}

        ${buildPrintRow('Action Taken', record.action_taken)}

    </table>

    <div class="section-title">Printed on</div>

    <div>${new Date().toLocaleString()}</div>

</body>

</html>`;

    printWindow.document.write(html);

    printWindow.document.close();

    printWindow.focus();

    printWindow.print();

}

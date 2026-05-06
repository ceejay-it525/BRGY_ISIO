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
                    const map = {
                        'Ongoing':          { cls: 'badge-warning text-dark', icon: 'fas fa-clock' },
                        'Settled':          { cls: 'badge-success',           icon: 'fas fa-check-circle' },
                        'Referred to Court':{ cls: 'badge-info',              icon: 'fas fa-gavel' },
                        'Dismissed':        { cls: 'badge-secondary',         icon: 'fas fa-ban' }
                    };
                    const b = map[d] || { cls: 'badge-primary', icon: 'fas fa-question' };
                    return `<span class="badge ${b.cls} p-2" style="font-size:11px;">
                                <i class="${b.icon} mr-1"></i>${d ?? '—'}
                            </span>`;
                }
            },
            {
                data: null,
                width: '10%',
                orderable: false,
                searchable: false,
                className: 'text-center',
                render: function (row) {
                    return `
                        <div class="btn-group btn-group-sm">
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

    $('#blotterTable tbody').on('click', 'tr', function (e) {
        if ($(e.target).closest('button, .btn').length) {
            return;
        }

        const rowData = blotterTable.row(this).data();
        if (!rowData) {
            return;
        }

        $('#blotterTable tbody tr.selected').removeClass('selected');
        $(this).addClass('selected');

        selectedBlotterRecord = rowData;
        updateBlotterDetails(rowData);
    });

    $('#editSelectedRecord').on('click', function () {
        if (!selectedBlotterRecord) {
            showToast('warning', 'Please select a blotter record first.');
            return;
        }
        $('.edit-blotter[data-id="' + selectedBlotterRecord.id + '"]').click();
    });

    $('#deleteSelectedRecord').on('click', function () {
        if (!selectedBlotterRecord) {
            showToast('warning', 'Please select a blotter record first.');
            return;
        }
        $('.delete-blotter[data-id="' + selectedBlotterRecord.id + '"]').click();
    });

    $('#printSelectedRecord').on('click', function () {
        if (!selectedBlotterRecord) {
            showToast('warning', 'Please select a blotter record first.');
            return;
        }
        printBlotterRecord(selectedBlotterRecord);
    });

    function styleStatusBadge(status) {
        const map = {
            'Ongoing':          { cls: 'badge-warning text-dark', label: 'Ongoing' },
            'Settled':          { cls: 'badge-success',           label: 'Settled' },
            'Referred to Court':{ cls: 'badge-info',              label: 'Referred' },
            'Dismissed':        { cls: 'badge-secondary',         label: 'Dismissed' }
        };
        const b = map[status] || { cls: 'badge-primary', label: status || '—' };
        return `<span class="badge ${b.cls}">${b.label}</span>`;
    }

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
            $('#detailStatus').html(styleStatusBadge(''));
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
        $('#detailStatus').html(styleStatusBadge(record.status));
        $('#detailNarrative').text(record.narrative || 'No narrative provided.');
        $('#detailActionTaken').text(record.action_taken || 'No action recorded.');
        $('#editSelectedRecord, #deleteSelectedRecord, #printSelectedRecord').prop('disabled', false);
    }

    function clearBlotterDetails() {
        updateBlotterDetails(null);
    }
});

function escapeHtml(value) {
    return String(value || '').replace(/[&<>"]/g, function (s) {
        return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'})[s];
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

// ================= FORM VALIDATION =================
function validateForm(formId) {
    let valid = true;
    $(formId + ' .form-control[required]').each(function () {
        const field = $(this);
        field.removeClass('is-invalid');
        if (!field.val().trim()) {
            field.addClass('is-invalid');
            valid = false;
        }
    });
    return valid;
}

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
        error: function () {
            showToast('error', 'Server error. Please try again.');
        },
        complete: function () {
            btn.prop('disabled', false).html('<i class="fa fa-save mr-1"></i> Save Record');
        }
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
    }).fail(function () {
        showToast('error', 'Failed to load record details.');
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
                blotterTable.ajax.reload(null, false);
            } else {
                showToast('error', res.message || 'Failed to update record.');
            }
            updateCSRF(res);
        },
        error: function () {
            showToast('error', 'Server error. Please try again.');
        },
        complete: function () {
            btn.prop('disabled', false).html('<i class="fa fa-save mr-1"></i> Update Record');
        }
    });
});

// ================= DELETE =================
$(document).on('click', '.delete-blotter', function () {
    const id = $(this).data('id');
    if (!id) { showToast('error', 'Invalid record ID.'); return; }

    const confirmDelete = function () {
        executeBlotterDelete(id);
    };

    if (typeof Swal !== 'undefined' && Swal.fire) {
        Swal.fire({
            title: 'Delete Record?',
            html: '<div style="text-align: center;">Are you sure you want to <strong>permanently delete</strong> this blotter record?<br><small>This action cannot be undone.</small></div>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-trash mr-1"></i> Yes, Delete It',
            cancelButtonText: '<i class="fas fa-times mr-1"></i> Cancel',
            buttonsStyling: false,
            customClass: {
                confirmButton: 'btn btn-danger btn-block mb-2',
                cancelButton: 'btn btn-secondary btn-block',
                actions: 'd-flex flex-column'
            }
        }).then(result => {
            if (!result.isConfirmed) return;
            confirmDelete();
        });
    } else {
        if (confirm('Are you sure you want to permanently delete this blotter record? This action cannot be undone.')) {
            confirmDelete();
        }
    }
});

function executeBlotterDelete(id) {
    const csrfData = getCsrfData();
    console.log('Delete record ID:', id);
    console.log('CSRF Data:', csrfData);
    
    if (!csrfData) {
        showToast('error', 'Unable to delete record: missing CSRF token.');
        return;
    }

    // Show loading state
    if (typeof Swal !== 'undefined' && Swal.isVisible && Swal.isVisible()) {
        Swal.update({
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }

    $.post(baseUrl + 'blotter/delete/' + id, csrfData, function (res) {
        console.log('Delete response:', res);
        
        if (typeof Swal !== 'undefined' && Swal.isVisible && Swal.isVisible()) {
            Swal.hideLoading();
        }
        
        if (res.status === 'success') {
            showToast('success', res.message);
            selectedBlotterRecord = null;
            clearBlotterDetails();
            blotterTable.ajax.reload(null, false);
            
            if (typeof Swal !== 'undefined' && Swal.isVisible && Swal.isVisible()) {
                Swal.close();
            }
        } else {
            showToast('error', res.message || 'Failed to delete record.');
        }
        updateCSRF(res);
    }, 'json').fail(function (jqXHR, textStatus, errorThrown) {
        console.error('Delete failed:', textStatus, errorThrown, jqXHR);
        
        if (typeof Swal !== 'undefined' && Swal.isVisible && Swal.isVisible()) {
            Swal.hideLoading();
        }
        
        showToast('error', 'Failed to delete record.');
    });
}

// ================= UTILITIES =================
function getCsrfData() {
    const tokenName = $('meta[name=csrf-name]').attr('content') || 'csrf_test_name';
    const tokenValue = $('input[name="' + tokenName + '"]').val() || $('meta[name=csrf-token]').attr('content');
    if (!tokenName || !tokenValue) {
        console.error('CSRF token not found. tokenName:', tokenName, 'tokenValue:', tokenValue);
        return null;
    }
    const data = {};
    data[tokenName] = tokenValue;
    return data;
}

function updateCSRF(res) {
    if (res && res.csrf_hash) {
        $('input[name=csrf_test_name]').val(res.csrf_hash);
    }
}

// Reset modals on close
$('#addBlotterModal, #editBlotterModal').on('hidden.bs.modal', function () {
    $(this).find('form')[0].reset();
    $(this).find('.form-control').removeClass('is-invalid');
});

// Tooltips on load
$(window).on('load', function () {
    $('[title]').tooltip();
});
// =============================================
// Clearances Module - clearances.js
// =============================================

function showToast(type = 'info', message = '') {
    // Fixed: Check if toastr is undefined, not empty string
    if (typeof toastr === 'undefined') {
        console[type === 'error' ? 'error' : 'log'](message);
        // Better fallback without alert
        const alertDiv = $(`
            <div class="alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show fixed-top m-3" 
                 style="z-index: 9999; right: 0; top: 80px; max-width: 400px;" role="alert">
                ${message}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        `);
        $('body').append(alertDiv);
        setTimeout(() => alertDiv.fadeOut(), 5000);
        return;
    }

    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: 'toast-top-right',
        timeOut: 3500,
        newestOnTop: true
    };

    // Fixed: Proper method call
    if (toastr[type]) {
        toastr[type](message);
    } else {
        toastr.info(message);
    }
}

function updateCsrf(response) {
    if (!response) return;
    const token = response.csrf_hash || response.csrfHash || response.csrfToken;
    if (token) {
        $('input[name=csrf_test_name]').val(token);
    }
}

function reloadClearancesTable() {
    if (typeof clearancesTable !== 'undefined' && clearancesTable && clearancesTable.ajax) {
        clearancesTable.ajax.reload(null, false);
    }
}

let clearancesTable;

$(document).ready(function () {
    // Destroy existing table if present
    if ($.fn.DataTable.isDataTable('#clearancesTable')) {
        $('#clearancesTable').DataTable().destroy();
    }

    // Initialize DataTable
    clearancesTable = $('#clearancesTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        pageLength: 25,
        order: [[2, 'asc']],
        ajax: {
            url: baseUrl + 'clearances/fetchRecords',
            type: 'POST',
            dataType: 'json',
            data: function (d) {
                d.csrf_test_name = $('input[name=csrf_test_name]').val();
            },
            error: function (xhr) {
                console.error('Clearances fetchRecords error:', xhr.responseText);
                showToast('error', 'Failed to load clearances data');
            }
        },
        columns: [
            { data: 'row_number', width: '5%' },
            { data: 'clearance_id', visible: false },
            { data: 'resident_name' },
            { data: 'type_name' },
            { data: 'purpose' },
            { data: 'formatted_issued_date', defaultContent: '' },
            { data: 'expiry_date', defaultContent: '' },
            { data: 'status' },
            {
                data: null,
                orderable: false,
                searchable: false,
                className: 'text-center',
                width: '10%',
                render: function (row) {
                    return `
                        <div class="btn-group btn-group-sm" role="group">
                            <button class="btn btn-warning edit-btn" data-id="${row.clearance_id}" title="Edit">
                                <i class="far fa-edit"></i>
                            </button>
                            <button class="btn btn-danger deleteBtn" data-id="${row.clearance_id}" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ],
        language: {
            processing: '<i class="fas fa-spinner fa-spin"></i> Loading...',
            emptyTable: 'No clearance records found'
        }
    });
});

// =============================================
// ADD NEW CLEARANCE
// =============================================
$('#addClearanceForm').on('submit', function (e) {
    e.preventDefault();

    const $form = $(this);
    const $btn = $form.find('button[type="submit"]');
    const originalText = $btn.html();

    // Client-side validation
    const residentId = $('input[name="resident_id"]').val().trim();
    const clearanceType = $('select[name="clearance_type"]').val();
    const dateIssued = $('input[name="date_issued"]').val();

    if (!residentId) {
        showToast('warning', 'Resident ID is required');
        $('input[name="resident_id"]').focus();
        return;
    }

    if (!clearanceType) {
        showToast('warning', 'Clearance Type is required');
        $('select[name="clearance_type"]').focus();
        return;
    }

    if (!dateIssued) {
        showToast('warning', 'Date Issued is required');
        $('input[name="date_issued"]').focus();
        return;
    }

    // Show loading state
    $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Saving...');

    const formData = new FormData(this);

    $.ajax({
        url: baseUrl + 'clearances/save',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function (response) {
            if (response.status === 200 || response.status === 'success') {
                $form[0].reset();
                $('#AddNewModal').modal('hide');
                showToast('success', response.message || 'Clearance saved successfully!');
                reloadClearancesTable();
            } else {
                showToast('error', response.message || 'Failed to save clearance');
            }
        },
        error: function (xhr) {
            console.error('Save error:', xhr.responseText);
            showToast('error', 'Failed to save clearance. Please try again.');
        },
        complete: function () {
            $btn.prop('disabled', false).html(originalText);
        }
    });
});

// =============================================
// EDIT CLEARANCE - Load Data
// =============================================
$(document).on('click', '.edit-btn', function () {
    const id = $(this).data('id');
    if (!id) {
        showToast('error', 'Invalid record ID');
        return;
    }

    $.get(baseUrl + 'clearances/edit/' + id)
    .done(function (response) {
        if (response && response.clearance_id) {
            // Populate form fields
            $('#editClearanceId').val(response.clearance_id);
            $('#editResidentId').val(response.resident_id || '');
            $('#editClearanceType').val(response.clearance_type || '');
            $('#editPurpose').val(response.purpose || '');
            $('#editDateIssued').val(response.date_issued || '');
            $('#editValidUntil').val(response.valid_until || '');
            $('#editClearanceStatus').val(response.status || '');
            $('#editRemarks').val(response.remarks || '');
            $('#editClearanceModal').modal('show');
        } else {
            showToast('error', 'Record not found');
        }
        updateCsrf(response);
    })
    .fail(function (xhr) {
        console.error('Edit load error:', xhr.responseText);
        showToast('error', 'Failed to load clearance details');
    });
});

// =============================================
// EDIT CLEARANCE - Save Changes
// =============================================
$('#editClearanceForm').on('submit', function (e) {
    e.preventDefault();

    const $form = $(this);
    const $btn = $form.find('button[type="submit"]');
    const originalText = $btn.html();

    const residentId = $('#editResidentId').val().trim();

    if (!residentId) {
        showToast('warning', 'Resident ID is required');
        $('#editResidentId').focus();
        return;
    }

    $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Updating...');

    const formData = new FormData(this);

    $.ajax({
        url: baseUrl + 'clearances/update',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function (response) {
            if (response.status === 200 || response.status === 'success') {
                $('#editClearanceModal').modal('hide');
                $form[0].reset();
                showToast('success', response.message || 'Clearance updated successfully!');
                reloadClearancesTable();
            } else {
                showToast('error', response.message || 'Failed to update clearance');
            }
        },
        error: function (xhr) {
            console.error('Update error:', xhr.responseText);
            showToast('error', 'Failed to update clearance');
        },
        complete: function () {
            $btn.prop('disabled', false).html(originalText);
        }
    });
});

// =============================================
// DELETE CLEARANCE
// =============================================
$(document).on('click', '.deleteBtn', function () {
    const id = $(this).data('id');
    if (!id) {
        showToast('error', 'Invalid record ID');
        return;
    }

    if (!confirm('Are you sure you want to delete this clearance record?')) {
        return;
    }

    $.ajax({
        url: baseUrl + 'clearances/delete/' + id,
        method: 'POST',
        data: { csrf_test_name: $('input[name=csrf_test_name]').val() },
        dataType: 'json',
        success: function (response) {
            if (response.status === 200 || response.status === 'success') {
                showToast('success', response.message || 'Clearance deleted successfully!');
                reloadClearancesTable();
            } else {
                showToast('error', response.message || 'Failed to delete clearance');
            }
        },
        error: function (xhr) {
            console.error('Delete error:', xhr.responseText);
            showToast('error', 'Failed to delete clearance');
        }
    });
});

// Reset forms on modal close
$('#AddNewModal, #editClearanceModal').on('hidden.bs.modal', function () {
    $(this).find('form')[0].reset();
});
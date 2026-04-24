function showToast(type, message) {
    if (type === 'success') toastr.success(message, 'Success');
    else toastr.error(message, 'Error');
}

// Add Clearance
$('#addClearanceForm').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
        url: baseUrl + 'clearances/save',
        method: 'POST', data: $(this).serialize(), dataType: 'json',
        success: function (response) {
            if (response.status === 'success') {
                $('#AddNewModal').modal('hide');
                $('#addClearanceForm')[0].reset();
                showToast('success', 'Clearance added successfully!');
                $('#example1').DataTable().ajax.reload();
            } else { showToast('error', response.message || 'Failed to add.'); }
        },
        error: function () { showToast('error', 'An error occurred.'); }
    });
});

// Edit Clearance
$(document).on('click', '.edit-btn', function () {
    const id = $(this).data('id');
    $.ajax({
        url: baseUrl + 'clearances/edit/' + id,
        method: 'GET', dataType: 'json',
        success: function (response) {
            if (response) {
                $('#clearanceId').val(response.id);
                $('#editResidentId').val(response.resident_id);
                $('#editClearanceType').val(response.clearance_type);
                $('#editPurpose').val(response.purpose);
                $('#editRequestDate').val(response.request_date);
                $('#editIssuedDate').val(response.issued_date || '');
                $('#editExpiryDate').val(response.expiry_date || '');
                $('#editFeeAmount').val(response.fee_amount);
                $('#editOrNumber').val(response.or_number || '');
                $('#editRequestStatus').val(response.request_status);
                $('#editRemarks').val(response.remarks || '');
                $('#editClearanceModal').modal('show');
            }
        },
        error: function () { alert('Error fetching data'); }
    });
});

// Update Clearance
$(document).ready(function () {
    $('#editClearanceForm').on('submit', function (e) {
        e.preventDefault();
        const id = $('#clearanceId').val();
        $.ajax({
            url: baseUrl + 'clearances/update/' + id,
            method: 'POST', data: $(this).serialize(), dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    $('#editClearanceModal').modal('hide');
                    showToast('success', 'Clearance updated successfully!');
                    $('#example1').DataTable().ajax.reload();
                } else { alert('Error updating: ' + (response.message || 'Unknown')); }
            },
            error: function () { alert('Error updating'); }
        });
    });
});

// Delete Clearance
$(document).on('click', '.deleteClearanceBtn', function () {
    const id = $(this).data('id');
    if (confirm('Are you sure you want to delete this clearance?')) {
        $.ajax({
            url: baseUrl + 'clearances/delete/' + id,
            method: 'POST',
            success: function (response) {
                if (response.status === 'success') {
                    showToast('success', 'Clearance deleted successfully.');
                    $('#example1').DataTable().ajax.reload();
                } else { alert(response.message || 'Failed to delete.'); }
            },
            error: function () { alert('Error deleting.'); }
        });
    }
});

// DataTable
$(document).ready(function () {
    $('#example1').DataTable({
        processing: true,
        serverSide: true,
        ajax: { url: baseUrl + 'clearances/fetchRecords', type: 'POST' },
        columns: [
            { data: 'row_number' },
            { data: 'id', visible: false },
            { data: 'control_number' },
            { data: 'resident_name' },
            { data: 'clearance_type' },
            { data: 'purpose' },
            { data: 'request_date' },
            { data: 'issued_date' },
            { data: 'fee_amount' },
            { data: 'request_status' },
            {
                data: null, orderable: false, searchable: false,
                render: function (data, type, row) {
                    return `
                    <button class="btn btn-sm btn-warning edit-btn" data-id="${row.id}"><i class="far fa-edit"></i></button>
                    <button class="btn btn-sm btn-danger deleteClearanceBtn" data-id="${row.id}"><i class="fas fa-trash-alt"></i></button>`;
                }
            }
        ],
        responsive: true, autoWidth: false
    });
});

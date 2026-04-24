function showToast(type, message) {
    if (type === 'success') toastr.success(message, 'Success');
    else toastr.error(message, 'Error');
}

// Add Permit
$('#addPermitForm').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
        url: baseUrl + 'permits/save',
        method: 'POST', data: $(this).serialize(), dataType: 'json',
        success: function (response) {
            if (response.status === 'success') {
                $('#AddNewModal').modal('hide');
                $('#addPermitForm')[0].reset();
                showToast('success', 'Permit added successfully!');
                $('#example1').DataTable().ajax.reload();
            } else { showToast('error', response.message || 'Failed to add.'); }
        },
        error: function () { showToast('error', 'An error occurred.'); }
    });
});

// Edit Permit
$(document).on('click', '.edit-btn', function () {
    const id = $(this).data('id');
    $.ajax({
        url: baseUrl + 'permits/edit/' + id,
        method: 'GET', dataType: 'json',
        success: function (response) {
            if (response) {
                $('#permitId').val(response.id);
                $('#editPermitType').val(response.permit_type);
                $('#editResidentId').val(response.resident_id);
                $('#editBusinessName').val(response.business_name || '');
                $('#editBusinessNature').val(response.business_nature || '');
                $('#editBusinessAddress').val(response.business_address || '');
                $('#editCapital').val(response.capital_investment || '');
                $('#editPurpose').val(response.purpose || '');
                $('#editApplicationDate').val(response.application_date);
                $('#editIssuedDate').val(response.issued_date || '');
                $('#editExpiryDate').val(response.expiry_date || '');
                $('#editFeeAmount').val(response.fee_amount);
                $('#editOrNumber').val(response.or_number || '');
                $('#editPermitStatus').val(response.permit_status);
                $('#editRemarks').val(response.remarks || '');
                $('#editPermitModal').modal('show');
            }
        },
        error: function () { alert('Error fetching data'); }
    });
});

// Update Permit
$(document).ready(function () {
    $('#editPermitForm').on('submit', function (e) {
        e.preventDefault();
        const id = $('#permitId').val();
        $.ajax({
            url: baseUrl + 'permits/update/' + id,
            method: 'POST', data: $(this).serialize(), dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    $('#editPermitModal').modal('hide');
                    showToast('success', 'Permit updated successfully!');
                    $('#example1').DataTable().ajax.reload();
                } else { alert('Error updating: ' + (response.message || 'Unknown')); }
            },
            error: function () { alert('Error updating'); }
        });
    });
});

// Delete Permit
$(document).on('click', '.deletePermitBtn', function () {
    const id = $(this).data('id');
    if (confirm('Are you sure you want to delete this permit?')) {
        $.ajax({
            url: baseUrl + 'permits/delete/' + id,
            method: 'POST',
            success: function (response) {
                if (response.status === 'success') {
                    showToast('success', 'Permit deleted successfully.');
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
        ajax: { url: baseUrl + 'permits/fetchRecords', type: 'POST' },
        columns: [
            { data: 'row_number' },
            { data: 'id', visible: false },
            { data: 'permit_number' },
            { data: 'permit_type' },
            { data: 'resident_name' },
            { data: 'business_name' },
            { data: 'application_date' },
            { data: 'issued_date' },
            { data: 'expiry_date' },
            { data: 'fee_amount' },
            { data: 'permit_status' },
            {
                data: null, orderable: false, searchable: false,
                render: function (data, type, row) {
                    return `
                    <button class="btn btn-sm btn-warning edit-btn" data-id="${row.id}"><i class="far fa-edit"></i></button>
                    <button class="btn btn-sm btn-danger deletePermitBtn" data-id="${row.id}"><i class="fas fa-trash-alt"></i></button>`;
                }
            }
        ],
        responsive: true, autoWidth: false
    });
});

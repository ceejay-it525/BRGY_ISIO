function showToast(type, message) {
    if (type === 'success') {
        toastr.success(message, 'Success');
    } else {
        toastr.error(message, 'Error');
    }
}

// Add Resident Form - EXACT COPY
$('#addResidentForm').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
        url: baseUrl + 'residents/save',
        method: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function (response) {
            if (response.status === 'success') {
                $('#AddNewModal').modal('hide');
                $('#addResidentForm')[0].reset();
                showToast('success', 'Resident added successfully!');
                $('#example1').DataTable().ajax.reload(); // Reload table
            } else {
                showToast('error', response.message || 'Failed to add resident.');
            }
        },
        error: function () {
            showToast('error', 'An error occurred.');
        }
    });
});

// Edit Resident - EXACT COPY
$(document).on('click', '.edit-btn', function () {
   const residentId = $(this).data('id'); 
   $.ajax({
    url: baseUrl + 'residents/edit/' + residentId,
    method: 'GET',
    dataType: 'json',
    success: function (response) {
        if (response) {
            $('#editResidentModal #residentId').val(response.id);
            $('#editResidentModal #editFirstName').val(response.first_name);
            $('#editResidentModal #editMiddleName').val(response.middle_name || '');
            $('#editResidentModal #editLastName').val(response.last_name);
            $('#editResidentModal #editGender').val(response.gender);
            $('#editResidentModal #editBirthdate').val(response.birthdate);
            $('#editResidentModal #editAddress').val(response.address);
            $('#editResidentModal #editContactNumber').val(response.contact_number);
            $('#editResidentModal #editCivilStatus').val(response.civil_status);
            $('#editResidentModal #editStatus').val(response.status);
            $('#editResidentModal').modal('show');
        } else {
            alert('Error fetching resident data');
        }
    },
    error: function () {
        alert('Error fetching resident data');
    }
});
});

// Update Resident - EXACT COPY
$(document).ready(function () {
    $('#editResidentForm').on('submit', function (e) {
        e.preventDefault(); 
        $.ajax({
            url: baseUrl + 'residents/update',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    $('#editResidentModal').modal('hide');
                    showToast('success', 'Resident updated successfully!');
                    $('#example1').DataTable().ajax.reload();
                } else {
                    alert('Error updating: ' + (response.message || 'Unknown error'));
                }
            },
            error: function (xhr) {
                alert('Error updating');
            }
        });
    });
});

// Delete - EXACT COPY
$(document).on('click', '.deleteResidentBtn', function () {
    const residentId = $(this).data('id');
    if (confirm('Are you sure you want to delete this resident?')) {
        $.ajax({
            url: baseUrl + 'residents/delete/' + residentId,
            method: 'POST',
            data: {
                [csrfName]: $('input[name="<?= csrf_hash() ?>"]').val()
            },
            success: function (response) {
                if (response.status === 'success') {
                    showToast('success', 'Resident deleted successfully.');
                    $('#example1').DataTable().ajax.reload();
                } else {
                    alert(response.message || 'Failed to delete.');
                }
            },
            error: function () {
                alert('Something went wrong while deleting.');
            }
        });
    }
});

// DataTable - EXACT COPY
$(document).ready(function () {
    const $table = $('#example1');
    $table.DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: baseUrl + 'residents/fetchRecords',
            type: 'POST'
        },
        columns: [
            { data: 'row_number' },
            { data: 'id', visible: false },
            { data: 'full_name' },
            { data: 'gender' },
            { data: 'age' },
            { data: 'address' },
            { data: 'contact_number' },
            { data: 'civil_status' },
            { data: 'status' },
            { data: 'created_at' },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    return `
                    <button class="btn btn-sm btn-warning edit-btn" data-id="${row.id}">
                      <i class="far fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger deleteResidentBtn" data-id="${row.id}">
                      <i class="fas fa-trash-alt"></i>
                    </button>
                    `;
                }
            }
        ],
        responsive: true,
        autoWidth: false
    });
});
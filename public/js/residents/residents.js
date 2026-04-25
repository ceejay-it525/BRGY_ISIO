function showToast(type, message) {
    if (type === 'success') {
        toastr.success(message, 'Success');
    } else {
        toastr.error(message, 'Error');
    }
}


// ================= ADD =================
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

                showToast('success', response.message);

                $('#residentsTable').DataTable().ajax.reload(null, false);
            } else {
                showToast('error', response.message);
            }
        },
        error: function (xhr) {
            console.log(xhr.responseText);
            showToast('error', 'Server error occurred');
        }
    });
});


// ================= EDIT FETCH =================
$(document).on('click', '.edit-resident', function () {
    const id = $(this).data('id');

    $.ajax({
        url: baseUrl + 'residents/get/' + id,
        method: 'GET',
        dataType: 'json',
        success: function (response) {

            if (response.status === 'success') {
                const r = response.data;

                $('#editResidentId').val(r.id);

                $('#editFirstName').val(r.first_name);
                $('#editMiddleName').val(r.middle_name);
                $('#editLastName').val(r.last_name);
                $('#editSuffix').val(r.suffix);

                $('#editBirthdate').val(r.birthdate);
                $('#editGender').val(r.gender);
                $('#editCivilStatus').val(r.civil_status);

                $('#editIsVoter').val(r.is_voter);
                $('#editVoterId').val(r.voter_id);
                $('#editHouseholdId').val(r.household_id);

                $('#editStatus').val(r.status);

                $('#editAddressLine1').val(r.address_line1);
                $('#editBarangay').val(r.barangay);

                $('#editResidentModal').modal('show');

            } else {
                showToast('error', response.message);
            }
        },
        error: function (xhr) {
            console.log(xhr.responseText);
            showToast('error', 'Failed to fetch data');
        }
    });
});


// ================= UPDATE =================
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

                showToast('success', response.message);

                $('#residentsTable').DataTable().ajax.reload(null, false);
            } else {
                showToast('error', response.message);
            }
        },
        error: function (xhr) {
            console.log(xhr.responseText);
            showToast('error', 'Update failed');
        }
    });
});


// ================= DELETE =================
$(document).on('click', '.delete-resident', function () {

    const id = $(this).data('id');

    if (!confirm('Are you sure?')) return;

    $.ajax({
        url: baseUrl + 'residents/delete/' + id,
        method: 'POST',
        dataType: 'json',
        success: function (response) {

            if (response.status === 'success') {
                showToast('success', response.message);
                $('#residentsTable').DataTable().ajax.reload(null, false);
            } else {
                showToast('error', response.message);
            }
        },
        error: function (xhr) {
            console.log(xhr.responseText);
            showToast('error', 'Delete failed');
        }
    });
});


// ================= DATATABLE =================
$(document).ready(function () {

    $('#residentsTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,

        ajax: {
            url: baseUrl + 'residents/fetchRecords',
            type: 'POST',

            data: function (d) {
                d.csrf_test_name = $('input[name=csrf_test_name]').val();
            },

            error: function (xhr) {
                console.log(xhr.responseText);
                alert("Datatable error. Check console.");
            }
        },

        columns: [
            { data: 'row_number' },

            {
                data: null,
                render: function (data, type, row) {
                    return `${row.first_name ?? ''} ${row.last_name ?? ''}`;
                }
            },

            { data: 'birthdate' },
            { data: 'gender' },
            { data: 'civil_status' },

            {
                data: 'is_voter',
                render: d => d == 1
                    ? '<span class="badge bg-success">Yes</span>'
                    : '<span class="badge bg-danger">No</span>'
            },

            { data: 'voter_id' },
            { data: 'household_id' },
            { data: 'address_line1' },
            { data: 'barangay' },

            {
                data: 'status',
                render: d => {
                    let c = (d === 'Active') ? 'bg-success' : 'bg-warning';
                    return `<span class="badge ${c}">${d}</span>`;
                }
            },

            {
                data: null,
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    return `
                        <button class="btn btn-sm btn-warning edit-resident" data-id="${row.id}">
                            <i class="far fa-edit"></i>
                        </button>

                        <button class="btn btn-sm btn-danger delete-resident" data-id="${row.id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    `;
                }
            }
        ]
    });
});
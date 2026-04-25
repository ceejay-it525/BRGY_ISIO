// ================= TOAST =================
function showToast(type = 'info', message = '') {

    if (typeof toastr === 'undefined') {
        console.error('Toastr not loaded');
        alert(message);
        return;
    }

    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: "toast-top-right",
        timeOut: "3000"
    };

    toastr[type] ? toastr[type](message) : toastr.info(message);
}


// ================= DATATABLE INIT =================
let residentsTable;

$(document).ready(function () {

    if ($.fn.DataTable.isDataTable('#residentsTable')) {
        $('#residentsTable').DataTable().destroy();
    }

    residentsTable = $('#residentsTable').DataTable({
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
                showToast('error', 'Datatable error');
            }
        },

        columns: [
            { data: 'row_number' },
            { data: 'id' },

            {
                data: null,
                render: row => `${row.first_name ?? ''} ${row.last_name ?? ''}`
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
                render: row => `
                    <button class="btn btn-sm btn-warning edit-resident" data-id="${row.id}">
                        <i class="far fa-edit"></i>
                    </button>

                    <button class="btn btn-sm btn-danger delete-resident" data-id="${row.id}">
                        <i class="fas fa-trash"></i>
                    </button>
                `
            }
        ]
    });
});


// ================= ADD =================
$('#addResidentForm').on('submit', function (e) {
    e.preventDefault();

    $.post(baseUrl + 'residents/save', $(this).serialize(), function (response) {

        if (response.status === 'success') {
            $('#AddNewModal').modal('hide');
            $('#addResidentForm')[0].reset();

            showToast('success', response.message);
            residentsTable.ajax.reload(null, false);
refreshReportStats(); // ✅ ADD THIS
        } else {
            showToast('error', response.message);
        }

        updateCSRF(response);

    }, 'json').fail(function (xhr) {
        console.log(xhr.responseText);
        showToast('error', 'Server error');
    });
});


// ================= EDIT FETCH =================
$(document).on('click', '.edit-resident', function () {

    const id = $(this).data('id');

    if (!id) {
        showToast('error', 'Invalid ID');
        return;
    }

    $.get(baseUrl + 'residents/get/' + id, function (response) {

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

    }, 'json').fail(function (xhr) {
        console.log(xhr.responseText);
        showToast('error', 'Fetch failed');
    });
});


// ================= UPDATE =================
$('#editResidentForm').on('submit', function (e) {
    e.preventDefault();

    $.post(baseUrl + 'residents/update', $(this).serialize(), function (response) {

        if (response.status === 'success') {
            $('#editResidentModal').modal('hide');

            showToast('success', response.message);
            residentsTable.ajax.reload(null, false);
            refreshReportStats(); // ✅ ADD THIS

        } else {
            showToast('error', response.message);
        }

        updateCSRF(response);

    }, 'json').fail(function (xhr) {
        console.log(xhr.responseText);
        showToast('error', 'Update failed');
    });
});


// ================= DELETE =================
$(document).on('click', '.delete-resident', function () {

    const id = $(this).data('id');

    if (!id) {
        showToast('error', 'Invalid ID');
        return;
    }

    if (!confirm('Are you sure you want to delete this resident?')) return;

    $.post(baseUrl + 'residents/delete/' + id, {
        csrf_test_name: $('input[name=csrf_test_name]').val()
    }, function (response) {

        if (response.status === 'success') {
            showToast('success', response.message);
            residentsTable.ajax.reload(null, false);
            refreshReportStats(); // ✅ ADD THIS

        } else {
            showToast('error', response.message);
        }

        updateCSRF(response);

    }, 'json').fail(function (xhr) {
        console.log(xhr.responseText);
        showToast('error', 'Delete failed');
    });
});


// ================= CSRF HELPER =================
function updateCSRF(response) {
    if (response && response.csrf_hash) {
        $('input[name=csrf_test_name]').val(response.csrf_hash);
    }
}// ================= REFRESH REPORT STATS =================
function refreshReportStats() {
    $.get(baseUrl + 'reports/reportStats', function (data) {

        $('#totalResidents').text(data.total_residents || 0);
        $('#totalHouseholds').text(data.total_households || 0);
        $('#totalBlotter').text(data.total_blotter || 0);
        $('#totalClearances').text(data.total_clearances || 0);
        $('#totalOfficials').text(data.total_officials || 0);
        $('#totalPermits').text(data.total_permits || 0);
        $('#totalIndigents').text(data.total_indigents || 0);

    }, 'json').fail(function (xhr) {
        console.log('Stats error:', xhr.responseText);
    });
}
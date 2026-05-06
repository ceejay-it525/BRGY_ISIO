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
let householdsTable;

$(document).ready(function () {

    if ($.fn.DataTable.isDataTable('#householdsTable')) {
        $('#householdsTable').DataTable().destroy();
    }

    householdsTable = $('#householdsTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,

        ajax: {
            url: baseUrl + 'households/fetchRecords',
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
            { data: 'id', visible: false },

            { data: 'head_name' },
            { data: 'address_line1' },
            { data: 'purok' },
            { data: 'barangay' },
            { data: 'city_municipality' },
            { data: 'province' },
            { data: 'zip_code' },

            {
                data: 'total_members',
                render: d => `<span class="badge bg-info">${d ?? 1}</span>`
            },

            {
                data: 'status',
                render: d => {
                    const map = {
                        'Active':      'bg-success',
                        'Inactive':    'bg-warning',
                        'Transferred': 'bg-secondary'
                    };
                    const cls = map[d] || 'bg-secondary';
                    return `<span class="badge ${cls}">${d}</span>`;
                }
            },

            {
                data: null,
                orderable: false,
                searchable: false,
                render: row => `
                    <button class="btn btn-sm btn-warning edit-household" data-id="${row.id}">
                        <i class="far fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger delete-household" data-id="${row.id}">
                        <i class="fas fa-trash"></i>
                    </button>
                `
            }
        ]
    });
});


// ================= ADD =================
$('#addHouseholdForm').on('submit', function (e) {
    e.preventDefault();

    $.post(baseUrl + 'households/save', $(this).serialize(), function (response) {

        if (response.status === 'success') {
            $('#AddNewModal').modal('hide');
            $('#addHouseholdForm')[0].reset();

            showToast('success', response.message);
            householdsTable.ajax.reload(null, false);
            refreshReportStats();
        } else {
            showToast('error', response.message);
        }

        updateCSRF(response);

    }, 'json').fail(function (xhr) {
        console.log(xhr.responseText);
        showToast('error', 'Server error');
    });
});
// ================= EDIT FETCH - FUNCTIONAL VERSION =================
$(document).on('click', '.edit-household', function () {
    const id = $(this).data('id');
    
    if (!id) {
        showToast('error', 'Invalid ID');
        return;
    }

    const url = baseUrl + 'households/get/' + id;
    const csrf = $('input[name=csrf_test_name]').val();
    
    // Use fetch with proper headers
    fetch(url, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrf,
            'Content-Type': 'application/json'
        },
        credentials: 'same-origin'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Server error: ${response.status}`);
        }
        return response.json();
    })
    .then(response => {
        console.log('✅ Edit response:', response);
        if (response.status === 'success') {
            const h = response.data;
            
            $('#editHouseholdForm')[0].reset();
            
            // Map all fields
            const fields = {
                editHouseholdId: 'id',
                editHeadName: 'head_name',
                editAddressLine1: 'address_line1',
                editPurok: 'purok',
                editBarangay: 'barangay',
                editCityMunicipality: 'city_municipality',
                editProvince: 'province',
                editZipCode: 'zip_code',
                editTotalMembers: 'total_members',
                editStatus: 'status'
            };
            
            Object.keys(fields).forEach(function(fieldId) {
                const value = h[fields[fieldId]] || '';
                $('#' + fieldId).val(value);
            });
            
            $('#editHouseholdModal').modal('show');
            showToast('success', 'Household loaded');
        } else {
            showToast('error', response.message || 'Load failed');
        }
    })
    .catch(error => {
        console.error('❌ Edit error:', error);
        showToast('error', 'Failed to load household: ' + error.message);
    });
});

// ================= DELETE =================
$(document).on('click', '.delete-household', function () {

    const id = $(this).data('id');

    if (!id) {
        showToast('error', 'Invalid ID');
        return;
    }

    if (!confirm('Are you sure you want to delete this household?')) return;

    $.post(baseUrl + 'households/delete/' + id, {
        csrf_test_name: $('input[name=csrf_test_name]').val()
    }, function (response) {

        if (response.status === 'success') {
            showToast('success', response.message);

            householdsTable.ajax.reload(null, false);
            refreshReportStats();
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
}


// ================= REFRESH REPORT STATS =================
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

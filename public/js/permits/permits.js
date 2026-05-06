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
let permitsTable;

$(document).ready(function () {

    if ($.fn.DataTable.isDataTable('#permitsTable')) {
        $('#permitsTable').DataTable().destroy();
    }

    permitsTable = $('#permitsTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,

        ajax: {
            url: baseUrl + 'permits/fetchRecords',
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

            { data: 'business_name' },
            { data: 'owner_name' },
            { data: 'business_type' },
            { data: 'permit_type' },
            { data: 'issue_date' },
            { data: 'expiry_date' },

            {
                data: 'status',
                render: d => {
                    const map = {
                        'Active':    'bg-success',
                        'Pending':   'bg-warning',
                        'Expired':   'bg-danger',
                        'Revoked':   'bg-secondary'
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
                    <button class="btn btn-sm btn-warning edit-permit" data-id="${row.id}">
                        <i class="far fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger delete-permit" data-id="${row.id}">
                        <i class="fas fa-trash"></i>
                    </button>
                `
            }
        ]
    });
});


// ================= ADD =================
$('#addPermitForm').on('submit', function (e) {
    e.preventDefault();

    $.post(baseUrl + 'permits/save', $(this).serialize(), function (response) {

        if (response.status === 'success') {
            $('#AddNewModal').modal('hide');
            $('#addPermitForm')[0].reset();

            showToast('success', response.message);
            reloadPermitsTable();
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
$(document).on('click', '.edit-permit', function () {
    const id = $(this).data('id');
    
    if (!id) {
        showToast('error', 'Invalid ID');
        return;
    }

    const url = baseUrl + 'permits/get/' + id;
    const csrf = $('input[name=csrf_test_name]').val();
    
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
            const p = response.data;
            
            $('#editPermitForm')[0].reset();
            
            // Map all fields
            const fields = {
                editPermitId: 'id',
                editBusinessName: 'business_name',
                editOwnerName: 'owner_name',
                editBusinessAddress: 'business_address',
                editBusinessType: 'business_type',
                editPermitType: 'permit_type',
                editIssueDate: 'issue_date',
                editExpiryDate: 'expiry_date',
                editPermitStatus: 'status',
                editFeesPaid: 'fees_paid'
            };
            
            Object.keys(fields).forEach(function(fieldId) {
                const value = p[fields[fieldId]] || '';
                $('#' + fieldId).val(value);
            });
            
            $('#editPermitModal').modal('show');
            showToast('success', 'Permit loaded');
        } else {
            showToast('error', response.message || 'Load failed');
        }
    })
    .catch(error => {
        console.error('❌ Edit error:', error);
        showToast('error', 'Failed to load permit: ' + error.message);
    });
});


// ================= EDIT SUBMIT =================
$('#editPermitForm').on('submit', function (e) {
    e.preventDefault();

    $.post(baseUrl + 'permits/update', $(this).serialize(), function (response) {

        if (response.status === 'success') {
            $('#editPermitModal').modal('hide');
            $('#editPermitForm')[0].reset();

            showToast('success', response.message);
            reloadPermitsTable();
        } else {
            showToast('error', response.message);
        }

        updateCSRF(response);

    }, 'json').fail(function (xhr) {
        console.log(xhr.responseText);
        showToast('error', 'Server error');
    });
});


// ================= DELETE =================
$(document).on('click', '.delete-permit', function () {

    const id = $(this).data('id');

    if (!id) {
        showToast('error', 'Invalid ID');
        return;
    }

    if (!confirm('Are you sure you want to delete this permit?')) return;

    $.post(baseUrl + 'permits/delete/' + id, {
        csrf_test_name: $('input[name=csrf_test_name]').val()
    }, function (response) {

        if (response.status === 'success') {
            showToast('success', response.message);

            reloadPermitsTable();
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
    if (!response) {
        return;
    }

    const token = response.csrf_hash || response.csrfHash || response.csrfToken;
    if (token) {
        $('input[name=csrf_test_name]').val(token);
    }
}

function reloadPermitsTable() {
    if (typeof permitsTable !== 'undefined' && permitsTable && permitsTable.ajax) {
        permitsTable.ajax.reload(null, false);
        return;
    }

    const table = $('#permitsTable').DataTable();
    if (table && table.ajax) {
        table.ajax.reload(null, false);
    }
}

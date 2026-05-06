// ================= TOAST =================
function showToast(type = 'info', message = '') {
    if (typeof toastr === 'undefined') {
        console[type === 'error' ? 'error' : 'log'](message);
        alert(message);
        return;
    }

    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: 'toast-top-right',
        timeOut: '3000'
    };

    toastr[type] ? toastr[type](message) : toastr.info(message);
}

function updateCsrf(response) {
    if (!response) {
        return;
    }

    const token = response.csrf_hash || response.csrfHash || response.csrfToken;
    if (token) {
        $('input[name=csrf_test_name]').val(token);
    }
}

function reloadUsersTable() {
    if (typeof usersTable !== 'undefined' && usersTable && usersTable.ajax) {
        usersTable.ajax.reload(null, false);
        return;
    }

    const table = $('#example1').DataTable();
    if (table && table.ajax) {
        table.ajax.reload(null, false);
    }
}

let usersTable;

$(document).ready(function () {
    if ($.fn.DataTable.isDataTable('#example1')) {
        $('#example1').DataTable().destroy();
    }

    usersTable = $('#example1').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: baseUrl + 'users/fetchRecords',
            type: 'POST',
            dataType: 'json',
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
            { data: 'name' },
            { data: 'email' },
            { data: 'role' },
            { data: 'status' },
            { data: 'phone', defaultContent: '' },
            { data: 'created_at', defaultContent: '' },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function (row) {
                    return `
                        <button class="btn btn-sm btn-warning edit-btn" data-id="${row.id}">
                            <i class="far fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger deleteUserBtn" data-id="${row.id}">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    `;
                }
            }
        ]
    });
});

// ================= ADD =================
$('#addUserForm').on('submit', function (e) {
    e.preventDefault();

    $.post(baseUrl + 'users/save', $(this).serialize(), function (response) {
        const success = response.status === 'success';

        if (success) {
            $('#AddNewModal').modal('hide');
            $('#addUserForm')[0].reset();
            showToast('success', response.message || 'User added successfully');
            reloadUsersTable();
        } else {
            showToast('error', response.message || 'Failed to add user');
        }

        updateCsrf(response);
    }, 'json').fail(function (xhr) {
        console.log(xhr.responseText);
        showToast('error', 'Server error');
    });
});

// ================= EDIT =================
$(document).on('click', '.edit-btn', function () {
    const id = $(this).data('id');
    if (!id) {
        showToast('error', 'Invalid user ID');
        return;
    }

    $.get(baseUrl + 'users/edit/' + id, function (response) {
        if (response && response.data) {
            const data = response.data;
            $('#editUserModal #userId').val(data.id);
            $('#editUserModal #name').val(data.name || '');
            $('#editUserModal #email').val(data.email || '');
            $('#editUserModal #password').val('');
            $('#editUserModal #role').val(data.role || '');
            $('#editUserModal #status').val(data.status || '');
            $('#editUserModal #phone').val(data.phone || '');
            $('#editUserModal').modal('show');
        } else {
            showToast('error', 'User not found');
        }
    }, 'json').fail(function (xhr) {
        console.log(xhr.responseText);
        showToast('error', 'Failed to load user');
    });
});

// ================= UPDATE =================
$('#editUserForm').on('submit', function (e) {
    e.preventDefault();

    $.post(baseUrl + 'users/update', $(this).serialize(), function (response) {
        const success = response.success === true;

        if (success) {
            $('#editUserModal').modal('hide');
            $('#editUserForm')[0].reset();
            showToast('success', response.message || 'User updated successfully');
            reloadUsersTable();
        } else {
            showToast('error', response.message || 'Failed to update user');
        }

        updateCsrf(response);
    }, 'json').fail(function (xhr) {
        console.log(xhr.responseText);
        showToast('error', 'Server error');
    });
});

// ================= DELETE =================
$(document).on('click', '.deleteUserBtn', function () {
    const id = $(this).data('id');

    if (!id) {
        showToast('error', 'Invalid user ID');
        return;
    }

    if (!confirm('Delete this user?')) {
        return;
    }

    $.post(baseUrl + 'users/delete/' + id, { csrf_test_name: $('input[name=csrf_test_name]').val() }, function (response) {
        const success = response.success === true;

        if (success) {
            showToast('success', response.message || 'User deleted successfully');
            reloadUsersTable();
        } else {
            showToast('error', response.message || 'Failed to delete user');
        }

        updateCsrf(response);
    }, 'json').fail(function (xhr) {
        console.log(xhr.responseText);
        showToast('error', 'Server error');
    });
});
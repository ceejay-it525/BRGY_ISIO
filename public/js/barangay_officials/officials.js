function showToast(type, message) {
    if (type === 'success') toastr.success(message, 'Success');
    else toastr.error(message, 'Error');
}

$('#addOfficialForm').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
        url: baseUrl + 'barangay-officials/save',
        method: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function (response) {
            if (response.status === 'success') {
                $('#AddNewModal').modal('hide');
                $('#addOfficialForm')[0].reset();
                showToast('success', 'Official added successfully!');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast('error', response.message || 'Failed to add official.');
            }
        },
        error: function () { showToast('error', 'An error occurred.'); }
    });
});

$(document).on('click', '.edit-btn', function () {
    const id = $(this).data('id');
    $.ajax({
        url: baseUrl + 'barangay-officials/edit/' + id,
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            if (response.data) {
                const d = response.data;
                $('#officialId').val(d.id);
                $('#e_first_name').val(d.first_name);
                $('#e_middle_name').val(d.middle_name);
                $('#e_last_name').val(d.last_name);
                $('#e_suffix').val(d.suffix);
                $('#e_position').val(d.position);
                $('#e_committee').val(d.committee);
                $('#e_term_start').val(d.term_start);
                $('#e_term_end').val(d.term_end);
                $('#e_contact_number').val(d.contact_number);
                $('#e_status').val(d.status);
                $('#editOfficialModal').modal('show');
            }
        },
        error: function () { alert('Error fetching data'); }
    });
});

$(document).ready(function () {
    $('#editOfficialForm').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            url: baseUrl + 'barangay-officials/update',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    $('#editOfficialModal').modal('hide');
                    showToast('success', 'Official updated successfully!');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast('error', response.message || 'Unknown error');
                }
            },
            error: function () { alert('Error updating'); }
        });
    });
});

$(document).on('click', '.deleteBtn', function () {
    const id = $(this).data('id');
    if (confirm('Are you sure you want to delete this official?')) {
        $.ajax({
            url: baseUrl + 'barangay-officials/delete/' + id,
            method: 'POST',
            success: function (response) {
                if (response.success) {
                    showToast('success', 'Official deleted successfully.');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    alert(response.message || 'Failed to delete.');
                }
            },
            error: function () { alert('Something went wrong.'); }
        });
    }
});

$(document).ready(function () {
    const csrfName  = 'csrf_test_name';
    const csrfToken = $('input[name="' + csrfName + '"]').val();

    $('#example1').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: baseUrl + 'barangay-officials/fetchRecords',
            type: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken }
        },
        columns: [
            { data: 'row_number' },
            { data: 'id', visible: false },
            { data: 'full_name' },
            { data: 'position' },
            { data: 'committee' },
            { data: 'term_start' },
            { data: 'term_end' },
            { data: 'contact_number' },
            {
                data: 'status',
                render: function (data) {
                    const badge = data === 'Active' ? 'badge-success' : 'badge-secondary';
                    return `<span class="badge ${badge}">${data}</span>`;
                }
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    return `
                        <button class="btn btn-sm btn-warning edit-btn" data-id="${row.id}">
                            <i class="far fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger deleteBtn" data-id="${row.id}">
                            <i class="fas fa-trash-alt"></i>
                        </button>`;
                }
            }
        ],
        responsive: true,
        autoWidth: false
    });
});
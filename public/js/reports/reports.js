function showToast(type, message) {
    if (type === 'success') toastr.success(message, 'Success');
    else toastr.error(message, 'Error');
}

// Add Report
$('#addReportForm').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
        url: baseUrl + 'reports/save',
        method: 'POST', data: $(this).serialize(), dataType: 'json',
        success: function (response) {
            if (response.status === 'success') {
                $('#AddNewModal').modal('hide');
                $('#addReportForm')[0].reset();
                showToast('success', 'Report generated successfully!');
                $('#example1').DataTable().ajax.reload();
            } else { showToast('error', response.message || 'Failed to generate.'); }
        },
        error: function () { showToast('error', 'An error occurred.'); }
    });
});

// Edit Report
$(document).on('click', '.edit-btn', function () {
    const id = $(this).data('id');
    $.ajax({
        url: baseUrl + 'reports/edit/' + id,
        method: 'GET', dataType: 'json',
        success: function (response) {
            if (response) {
                $('#reportId').val(response.id);
                $('#editTitle').val(response.title);
                $('#editReportType').val(response.report_type);
                $('#editPeriodStart').val(response.period_start);
                $('#editPeriodEnd').val(response.period_end);
                $('#editDescription').val(response.description || '');
                $('#editReportModal').modal('show');
            }
        },
        error: function () { alert('Error fetching data'); }
    });
});

// Update Report
$(document).ready(function () {
    $('#editReportForm').on('submit', function (e) {
        e.preventDefault();
        const id = $('#reportId').val();
        $.ajax({
            url: baseUrl + 'reports/update/' + id,
            method: 'POST', data: $(this).serialize(), dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    $('#editReportModal').modal('hide');
                    showToast('success', 'Report updated successfully!');
                    $('#example1').DataTable().ajax.reload();
                } else { alert('Error updating: ' + (response.message || 'Unknown')); }
            },
            error: function () { alert('Error updating'); }
        });
    });
});

// Delete Report
$(document).on('click', '.deleteReportBtn', function () {
    const id = $(this).data('id');
    if (confirm('Are you sure you want to delete this report?')) {
        $.ajax({
            url: baseUrl + 'reports/delete/' + id,
            method: 'POST',
            success: function (response) {
                if (response.status === 'success') {
                    showToast('success', 'Report deleted successfully.');
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
        ajax: { url: baseUrl + 'reports/fetchRecords', type: 'POST' },
        columns: [
            { data: 'row_number' },
            { data: 'id', visible: false },
            { data: 'title' },
            { data: 'report_type' },
            { data: 'period_start' },
            { data: 'period_end' },
            { data: 'generated_by_name' },
            { data: 'created_at' },
            {
                data: null, orderable: false, searchable: false,
                render: function (data, type, row) {
                    return `
                    <button class="btn btn-sm btn-warning edit-btn" data-id="${row.id}"><i class="far fa-edit"></i></button>
                    <button class="btn btn-sm btn-danger deleteReportBtn" data-id="${row.id}"><i class="fas fa-trash-alt"></i></button>`;
                }
            }
        ],
        responsive: true, autoWidth: false
    });
});

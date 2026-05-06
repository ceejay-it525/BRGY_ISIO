// =============================================
//  Indigents Module — indigents.js
//  Updated for direct name entry (no resident search)
// =============================================

function updateCsrf(response) {
    if (!response) return;
    const token = response.csrf_hash || response.csrfHash || response.csrfToken;
    if (token) $('input[name=csrf_test_name]').val(token);
}

function showToast(type, message) {
    if (typeof toastr === 'undefined') { 
        const alertDiv = $(`<div class="alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show" role="alert">${message}<button type="button" class="close" data-dismiss="alert">&times;</button></div>`);
        $('.content-header').prepend(alertDiv);
        setTimeout(() => alertDiv.fadeOut(), 5000);
        return; 
    }
    toastr.options = { closeButton: true, progressBar: true, positionClass: 'toast-top-right', timeOut: 3500 };
    (toastr[type] || toastr.info)(message);
}

function reloadIndigentsTable() {
    if (typeof indigentsTable !== 'undefined' && indigentsTable && indigentsTable.ajax) {
        indigentsTable.ajax.reload(null, false);
    }
}

function statusBadge(s) {
    const badges = {
        'Active': 'success',
        'Pending': 'warning', 
        'Completed': 'info',
        'Inactive': 'secondary'
    };
    return `<span class="badge badge-${badges[s] || 'secondary'}">${s || '—'}</span>`;
}

function categoryBadge(c) {
    const badges = {
        '4Ps Family': 'info',
        'Senior Citizen': 'warning',
        'PWD': 'danger',
        'Solo Parent': 'primary',
        'Unemployed': 'dark',
        'Homeless': 'secondary',
        'Indigenous People': 'success',
        'Single Mother': 'pink',
        'Widow/Widower': 'orange',
        'Out of School Youth': 'purple',
        'Low Income Family': 'cyan',
        'Disaster Victim': 'red'
    };
    return `<span class="badge badge-${badges[c] || 'secondary'}">${c || '—'}</span>`;
}

function pesoFormat(v) {
    if (!v && v !== 0) return '—';
    return '₱' + parseFloat(v).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

// =============================================
//  On DOM Ready
// =============================================
let indigentsTable;
$(document).ready(function () {

    // =============================================
    // DataTable
    // =============================================
    if ($.fn.DataTable.isDataTable('#indigentsTable')) {
        $('#indigentsTable').DataTable().destroy();
    }

    indigentsTable = $('#indigentsTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        pageLength: 25,
        order: [[2, 'asc']],
        ajax: {
            url: baseUrl + 'indigents/fetchRecords',
            type: 'POST',
            dataType: 'json',
            data: function (d) {
                d.csrf_test_name = $('input[name=csrf_test_name]').val();
            },
            error: function () {
                showToast('error', 'Failed to load records.');
            }
        },
        columns: [
            { data: 'row_number', width: '5%' },
            { data: 'id', visible: false },
            { data: 'resident_name' },
            { data: 'indigency_category', render: v => categoryBadge(v) },
            { data: 'assistance_type' },
            { data: 'assistance_amount', render: v => pesoFormat(v) },
            { data: 'date_provided' },
            { data: 'status', render: v => statusBadge(v), width: '10%' },
            {
                data: null,
                className: 'text-center no-wrap',
                width: '12%',
                orderable: false,
                render: row => `
                    <div class="btn-group btn-group-sm" role="group">
                        <button class="btn btn-warning edit-btn" data-id="${row.id}" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger deleteBtn" data-id="${row.id}" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>`
            }
        ],
        language: {
            processing: '<i class="fas fa-spinner fa-spin"></i> Loading...'
        }
    });

    // =============================================
    // ADD RECORD FORM
    // =============================================
    $('#addIndigentForm').on('submit', function (e) {
        e.preventDefault();

        // Validate required fields
        const firstName = $('input[name="first_name"]').val().trim();
        const lastName = $('input[name="last_name"]').val().trim();
        const category = $('select[name="indigency_category"]').val();
        
        if (!firstName) {
            showToast('warning', 'First Name is required.');
            $('input[name="first_name"]').focus();
            return;
        }
        
        if (!lastName) {
            showToast('warning', 'Last Name is required.');
            $('input[name="last_name"]').focus();
            return;
        }
        
        if (!category) {
            showToast('warning', 'Indigency Category is required.');
            $('select[name="indigency_category"]').focus();
            return;
        }

        const $btn = $('#btnAddSave');
        const originalText = $btn.html();
        
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Saving...');

        const formData = new FormData(this);
        
        $.ajax({
            url: baseUrl + 'indigents/save',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    $('#AddNewModal').modal('hide');
                    $('#addIndigentForm')[0].reset();
                    showToast('success', res.message);
                    reloadIndigentsTable();
                } else {
                    showToast('error', res.message || 'Failed to save record');
                }
            },
            error: function() {
                showToast('error', 'Network error. Please try again.');
            },
            complete: function() {
                $btn.prop('disabled', false).html(originalText);
            }
        });
    });

    // =============================================
    // EDIT RECORD - Load Data
    // =============================================
    $(document).on('click', '.edit-btn', function () {
        const id = $(this).data('id');
        
        $.get(baseUrl + 'indigents/edit/' + id, function (res) {
            if (res.data) {
                const d = res.data;
                
                // Clear form
                $('#editIndigentForm')[0].reset();
                
                // Populate fields
                $('#editIndigentId').val(d.id);
                $('input[name="first_name"]').val(d.first_name || '').prop('readonly', false);
                $('input[name="middle_name"]').val(d.middle_name || '');
                $('input[name="last_name"]').val(d.last_name || '');
                $('#editIndigencyCategory').val(d.indigency_category);
                $('#editAssistanceType').val(d.assistance_type);
                $('#editAssistanceAmount').val(d.assistance_amount);
                $('#editIndigentStatus').val(d.status);
                $('#editDateAssessed').val(d.date_assessed);
                $('#editDateProvided').val(d.date_provided);

                $('#editIndigentModal').modal('show');
            } else {
                showToast('error', 'Record not found');
            }
            updateCsrf(res);
        }).fail(function() {
            showToast('error', 'Failed to load record');
        });
    });

    // =============================================
    // EDIT RECORD - Save
    // =============================================
    $('#editIndigentForm').on('submit', function (e) {
        e.preventDefault();

        const firstName = $('input[name="first_name"]').val().trim();
        const lastName = $('input[name="last_name"]').val().trim();
        
        if (!firstName || !lastName) {
            showToast('warning', 'First Name and Last Name are required.');
            return;
        }

        const $btn = $('#btnEditSave');
        const originalText = $btn.html();
        
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Updating...');

        const formData = new FormData(this);
        
        $.ajax({
            url: baseUrl + 'indigents/update',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    $('#editIndigentModal').modal('hide');
                    showToast('success', res.message);
                    reloadIndigentsTable();
                } else {
                    showToast('error', res.message || 'Failed to update record');
                }
            },
            error: function() {
                showToast('error', 'Network error. Please try again.');
            },
            complete: function() {
                $btn.prop('disabled', false).html(originalText);
            }
        });
    });

    // =============================================
    // DELETE CONFIRM
    // =============================================
    $(document).on('click', '.deleteBtn', function () {
        const id = $(this).data('id');
        pendingDeleteId = id;
        $('#deleteConfirmModal').modal('show');
    });

    $('#confirmDeleteBtn').on('click', function() {
        if (!pendingDeleteId) return;

        $.post(baseUrl + 'indigents/delete/' + pendingDeleteId, {
            csrf_test_name: $('input[name=csrf_test_name]').val()
        }, function (res) {
            if (res.status === 'success') {
                $('#deleteConfirmModal').modal('hide');
                showToast('success', res.message);
                reloadIndigentsTable();
                pendingDeleteId = null;
            } else {
                showToast('error', res.message);
            }
            updateCsrf(res);
        }, 'json').fail(function() {
            showToast('error', 'Failed to delete record');
        });
    });

    // Reset forms when modals close
    $('#AddNewModal').on('hidden.bs.modal', function () {
        $('#addIndigentForm')[0].reset();
    });

    $('#editIndigentModal').on('hidden.bs.modal', function () {
        $('#editIndigentForm')[0].reset();
        $('#editIndigentId').val('');
    });
});
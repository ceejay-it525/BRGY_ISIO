// =============================================
//  Permits Module — permits.js
//  Full CRUD with improved UI and functionality
// =============================================

// ================= TOAST =================
function showToast(type = 'info', message = '') {
    if (typeof toastr === 'undefined') {
        const cls = type === 'success' ? 'success' : (type === 'warning' ? 'warning' : 'danger');
        const el = $(
            `<div class="alert alert-${cls} alert-dismissible fade show"
                  style="position:fixed;top:70px;right:15px;z-index:9999;min-width:280px;" role="alert">
                ${message}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>`
        );
        $('body').append(el);
        setTimeout(() => el.fadeOut(400, () => el.remove()), 4000);
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

// ================= FORMAT HELPERS =================
function formatCurrency(amount) {
    return '₱' + parseFloat(amount || 0).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function formatDate(dateStr) {
    if (!dateStr || dateStr === '0000-00-00') return '—';
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-PH', { year: 'numeric', month: 'short', day: 'numeric' });
}

function statusBadge(status) {
    const map = {
        'Active': { class: 'success', icon: 'fa-check-circle' },
        'Pending': { class: 'warning', icon: 'fa-clock' },
        'Expired': { class: 'danger', icon: 'fa-calendar-times' },
        'Revoked': { class: 'secondary', icon: 'fa-ban' }
    };
    const config = map[status] || { class: 'secondary', icon: 'fa-question-circle' };
    return `<span class="badge badge-${config.class}"><i class="fas ${config.icon} mr-1"></i>${status}</span>`;
}

// ================= DATATABLE INIT =================
let permitsTable;
let pendingDeleteId = null;

$(document).ready(function () {

    if ($.fn.DataTable.isDataTable('#permitsTable')) {
        $('#permitsTable').DataTable().destroy();
    }

    permitsTable = $('#permitsTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        pageLength: 25,
        order: [[1, 'desc']],
        ajax: {
            url: baseUrl + 'permits/fetchRecords',
            type: 'POST',
            data: function (d) {
                d.csrf_test_name = $('input[name=csrf_test_name]').val();
            },
            error: function (xhr) {
                console.log(xhr.responseText);
                showToast('error', 'Failed to load permits data.');
            }
        },
        columns: [
            { data: 'row_number', width: '5%' },
            { data: 'id', visible: false },
            { 
                data: 'business_name',
                render: function(data) {
                    return `<strong>${data || '—'}</strong>`;
                }
            },
            { 
                data: 'owner_name',
                render: function(data) {
                    return data ? `<span class="text-muted"><i class="fas fa-user-tie mr-1"></i>${data}</span>` : '—';
                }
            },
            { data: 'business_type' },
            { 
                data: 'permit_type',
                render: function(data) {
                    if (!data) return '—';
                    const badges = {
                        'New': 'info',
                        'Renewal': 'primary',
                        'Amendment': 'warning'
                    };
                    return `<span class="badge badge-${badges[data] || 'secondary'}">${data}</span>`;
                }
            },
            { 
                data: 'issue_date',
                render: function(data) {
                    return data ? formatDate(data) : '<span class="text-muted">—</span>';
                }
            },
            { 
                data: 'expiry_date',
                render: function(data) {
                    if (!data) return '<span class="text-muted">—</span>';
                    // Check if expired
                    const expiry = new Date(data);
                    const today = new Date();
                    const isExpired = expiry < today;
                    const formatted = formatDate(data);
                    return isExpired 
                        ? `<span class="text-danger"><i class="fas fa-exclamation-circle mr-1"></i>${formatted}</span>` 
                        : formatted;
                }
            },
            { data: 'status', render: d => statusBadge(d), width: '10%' },
            { 
                data: 'fees_paid',
                render: function(data) {
                    return formatCurrency(data);
                }
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                className: 'text-center',
                width: '12%',
                render: row => `
                    <div class="btn-group btn-group-sm" role="group">
                        <button class="btn btn-warning edit-permit" data-id="${row.id}" title="Edit" data-toggle="tooltip">
                            <i class="far fa-edit"></i>
                        </button>
                        <button class="btn btn-danger delete-permit" data-id="${row.id}" title="Delete" data-toggle="tooltip">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>`
            }
        ],
        language: {
            processing: '<i class="fas fa-spinner fa-spin"></i> Loading...',
            emptyTable: 'No business permits found.',
            zeroRecords: 'No matching records found'
        },
        dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>"
    });

    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Set default dates
    const today = new Date().toISOString().split('T')[0];
    $('#addIssueDate').val(today);

    // ================= ADD =================
    $('#addPermitForm').on('submit', function (e) {
        e.preventDefault();

        const $btn = $(this).find('button[type="submit"]');
        const originalText = $btn.html();
        
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Saving...');

        $.post(baseUrl + 'permits/save', $(this).serialize(), function (response) {

            if (response.status === 'success') {
                $('#AddNewModal').modal('hide');
                $('#addPermitForm')[0].reset();
                showToast('success', response.message);
                reloadPermitsTable();
            } else {
                showToast('error', response.message || 'Failed to save permit');
            }

            updateCSRF(response);

        }, 'json').fail(function (xhr) {
            console.log(xhr.responseText);
            showToast('error', 'Server error. Please try again.');
        }).always(function() {
            $btn.prop('disabled', false).html(originalText);
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
        
        $.get(url, function (response) {
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
            } else {
                showToast('error', response.message || 'Failed to load permit');
            }
        }).fail(function (xhr) {
            console.error('Edit error:', xhr.responseText);
            showToast('error', 'Failed to load permit data');
        });
    });

    // ================= EDIT SUBMIT =================
    $('#editPermitForm').on('submit', function (e) {
        e.preventDefault();

        const $btn = $(this).find('button[type="submit"]');
        const originalText = $btn.html();
        
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Updating...');

        $.post(baseUrl + 'permits/update', $(this).serialize(), function (response) {

            if (response.status === 'success') {
                $('#editPermitModal').modal('hide');
                $('#editPermitForm')[0].reset();
                showToast('success', response.message);
                reloadPermitsTable();
            } else {
                showToast('error', response.message || 'Failed to update permit');
            }

            updateCSRF(response);

        }, 'json').fail(function (xhr) {
            console.log(xhr.responseText);
            showToast('error', 'Server error. Please try again.');
        }).always(function() {
            $btn.prop('disabled', false).html(originalText);
        });
    });

    // ================= DELETE - Show Confirmation =================
    $(document).on('click', '.delete-permit', function () {
        pendingDeleteId = $(this).data('id');
        $('#deleteConfirmModal').modal('show');
    });

    // ================= DELETE - Confirm =================
    $('#confirmDeleteBtn').on('click', function () {
        if (!pendingDeleteId) {
            showToast('error', 'No permit selected for deletion.');
            return;
        }

        const $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Deleting...');

        $.post(baseUrl + 'permits/delete/' + pendingDeleteId, {
            csrf_test_name: $('input[name=csrf_test_name]').val()
        }, function (response) {

            if (response.status === 'success') {
                showToast('success', response.message);
                reloadPermitsTable();
                $('#deleteConfirmModal').modal('hide');
            } else {
                showToast('error', response.message || 'Failed to delete permit');
            }

            updateCSRF(response);

        }, 'json').fail(function (xhr) {
            console.log(xhr.responseText);
            showToast('error', 'Delete failed. Please try again.');
        }).always(function() {
            $btn.prop('disabled', false).html('<i class="fas fa-trash mr-1"></i> Delete');
            pendingDeleteId = null;
        });
    });

    // Reset forms when modals close
    $('#AddNewModal').on('hidden.bs.modal', function () {
        $('#addPermitForm')[0].reset();
        // Reset default date
        const today = new Date().toISOString().split('T')[0];
        $('#addIssueDate').val(today);
    });

    $('#editPermitModal').on('hidden.bs.modal', function () {
        $('#editPermitForm')[0].reset();
    });

    // ================= RELOAD TABLE =================
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

    // ================= FETCH STATS =================
    function fetchStats() {
        $.get(baseUrl + 'permits/stats')
            .done(function (res) {
                if (res.status === 'success') {
                    $('#totalPermits').text(res.data.total || 0);
                    $('#activePermits').text(res.data.active || 0);
                    $('#pendingPermits').text(res.data.pending || 0);
                    $('#expiredPermits').text(res.data.expired || 0);
                }
            })
            .fail(function () {
                // Stats endpoint not available, that's okay
            });
    }

    // Initialize stats
    fetchStats();
});
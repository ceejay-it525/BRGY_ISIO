// =====================================================================
// Events Module — events.js
// Full CRUD: DataTable (server-side) | Add | Edit | View | Delete
// =====================================================================

'use strict';

// ── Toast helper ──────────────────────────────────────────────────────────────
function showToast(type, message) {
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
        closeButton: true, progressBar: true,
        positionClass: 'toast-top-right', timeOut: 3500
    };
    (toastr[type] || toastr.info)(message);
}

// ── CSRF refresh ──────────────────────────────────────────────────────────────
function refreshCsrf(response) {
    const token = response && (response.csrf_hash || response.csrfHash);
    if (token) $('input[name="csrf_test_name"]').val(token);
}

// ── Color badge ──────────────────────────────────────────────────────────────
function colorBadge(color) {
    return `<span class="badge badge-${color}">${color.charAt(0).toUpperCase() + color.slice(1)}</span>`;
}

// ── Show validation errors inside a modal ────────────────────────────────────
function showErrors(modalId, errors) {
    $(`#${modalId} .validation-errors`).remove();
    let html = '<div class="alert alert-danger validation-errors alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><ul class="mb-0">';
    $.each(errors, (field, msg) => { html += `<li>${msg}</li>`; });
    html += '</ul></div>';
    $(`#${modalId} .modal-body`).prepend(html);
}

// ── DataTable instance ────────────────────────────────────────────────────────
let eventsTable;

$(function () {
    // Init DataTable
    if ($.fn.DataTable.isDataTable('#eventsTable')) {
        $('#eventsTable').DataTable().destroy();
    }

    eventsTable = $('#eventsTable').DataTable({
        processing  : true,
        serverSide  : true,
        responsive  : true,
        pageLength  : 25,
        order       : [[4, 'desc']], // Sort by event_date
        ajax: {
            url      : baseUrl + 'events/fetchRecords',
            type     : 'POST',
            dataType : 'json',
            data     : d => { d.csrf_test_name = $('input[name="csrf_test_name"]').val(); },
            error    : (xhr) => {
                console.error('fetchRecords error:', xhr.status, xhr.responseText);
                showToast('error', 'Failed to load events data. Check the console for details.');
            }
        },
        columns: [
            { data: 'row_number', width: '4%', orderable: false,
              render: (data, type, row, meta) => meta.row + 1
            },
            { data: 'id', visible: false },
            { 
                data: 'title',
                render: (data) => `<strong>${data || '—'}</strong>`
            },
            { 
                data: 'description',
                render: (data) => {
                    if (!data) return '<span class="text-muted">No description</span>';
                    return data.length > 50 ? data.substring(0, 50) + '...' : data;
                }
            },
            { 
                data: 'formatted_date',
                render: (data, type, row) => {
                    if (type === 'sort') return row.event_date;
                    return data || '—';
                }
            },
            { 
                data: 'days_until',
                render: (data) => {
                    if (data === null || data === undefined) return '—';
                    if (data < 0) return '<span class="text-danger">Past event</span>';
                    if (data === 0) return '<span class="text-success">Today!</span>';
                    if (data === 1) return '<span class="text-warning">Tomorrow</span>';
                    return `${data} day(s)`;
                }
            },
            { 
                data: 'color',
                orderable: false,
                render: (data) => colorBadge(data || 'secondary')
            },
            {
                data      : null,
                orderable : false,
                searchable: false,
                className : 'text-center',
                width     : '10%',
                render    : (row) => `
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-info btn-xs view-btn"   data-id="${row.id}" title="View">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-warning btn-xs edit-btn" data-id="${row.id}" title="Edit">
                            <i class="far fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-xs delete-btn" data-id="${row.id}" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>`
            }
        ],
        language: {
            processing : '<i class="fas fa-spinner fa-spin"></i> Loading...',
            emptyTable : 'No event records found.'
        }
    });

    // =========================================================================
    // ADD — submit
    // =========================================================================
    $('#addEventForm').on('submit', function (e) {
        e.preventDefault();

        const $btn  = $('#addSaveBtn');
        const $form = $(this);
        const orig  = $btn.html();

        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Saving…');

        $.ajax({
            url         : baseUrl + 'events/save',
            method      : 'POST',
            data        : new FormData(this),
            processData : false,
            contentType : false,
            dataType    : 'json',
            success: function (res) {
                refreshCsrf(res);
                if (res.status === 200) {
                    $form[0].reset();
                    $('#addEventModal').modal('hide');
                    showToast('success', res.message || 'Event created successfully!');
                    eventsTable.ajax.reload(null, false);
                } else if (res.status === 422) {
                    showErrors('addEventModal', res.errors);
                } else {
                    showToast('error', res.message || 'Failed to save event.');
                }
            },
            error: function (xhr) {
                console.error('Save error:', xhr.responseText);
                try {
                    const res = JSON.parse(xhr.responseText);
                    if (res.errors) { showErrors('addEventModal', res.errors); return; }
                } catch (err) { /* not JSON */ }
                showToast('error', 'An error occurred. Please try again.');
            },
            complete: () => $btn.prop('disabled', false).html(orig)
        });
    });

    // =========================================================================
    // EDIT — load record
    // =========================================================================
    $(document).on('click', '.edit-btn', function () {
        const id = $(this).data('id');

        $.get(baseUrl + 'events/edit/' + id)
            .done(function (res) {
                if (!res || !res.id) {
                    showToast('error', 'Record not found.');
                    return;
                }
                $('#editEventId').val(res.id);
                $('#editTitle').val(res.title || '');
                $('#editEventDate').val(res.event_date || '');
                $('#editDescription').val(res.description || '');
                $('#editColor').val(res.color || 'primary');
                $('#editIcon').val(res.icon || 'fa-calendar');
                $('#editEventModal .validation-errors').remove();
                $('#editEventModal').modal('show');
                refreshCsrf(res);
            })
            .fail(function (xhr) {
                console.error('Edit load error:', xhr.responseText);
                showToast('error', 'Failed to load record.');
            });
    });

    // =========================================================================
    // EDIT — submit update
    // =========================================================================
    $('#editEventForm').on('submit', function (e) {
        e.preventDefault();

        const id    = $('#editEventId').val();
        const $btn  = $('#editSaveBtn');
        const $form = $(this);
        const orig  = $btn.html();

        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Updating…');

        $.ajax({
            url         : baseUrl + 'events/update/' + id,
            method      : 'POST',
            data        : new FormData(this),
            processData : false,
            contentType : false,
            dataType    : 'json',
            success: function (res) {
                refreshCsrf(res);
                if (res.status === 200) {
                    $form[0].reset();
                    $('#editEventModal').modal('hide');
                    showToast('success', res.message || 'Event updated successfully!');
                    eventsTable.ajax.reload(null, false);
                } else if (res.status === 422) {
                    showErrors('editEventModal', res.errors);
                } else {
                    showToast('error', res.message || 'Failed to update event.');
                }
            },
            error: function (xhr) {
                console.error('Update error:', xhr.responseText);
                try {
                    const res = JSON.parse(xhr.responseText);
                    if (res.errors) { showErrors('editEventModal', res.errors); return; }
                } catch (err) { /* not JSON */ }
                showToast('error', 'An error occurred. Please try again.');
            },
            complete: () => $btn.prop('disabled', false).html(orig)
        });
    });

    // =========================================================================
    // VIEW — show details
    // =========================================================================
    $(document).on('click', '.view-btn', function () {
        const id = $(this).data('id');

        $.get(baseUrl + 'events/edit/' + id)
            .done(function (res) {
                if (!res || !res.id) {
                    showToast('error', 'Record not found.');
                    return;
                }
                const iconPreview = res.icon ? `<i class="fas ${res.icon} fa-2x text-${res.color}"></i>` : '';
                $('#viewEventBody').html(`
                    <div class="text-center mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-${res.color} text-white p-4 mb-3">
                            ${iconPreview}
                        </div>
                    </div>
                    <table class="table table-sm table-bordered">
                        <tr><th width="30%">Event Title</th><td><strong>${res.title || '—'}</strong></td></tr>
                        <tr><th>Event Date</th><td>${res.formatted_date || res.event_date || '—'}</td></tr>
                        <tr><th>Days Until</th><td>${res.days_until !== null ? res.days_until + ' day(s)' : '—'}</td></tr>
                        <tr><th>Color</th><td>${colorBadge(res.color)}</td></tr>
                        <tr><th>Icon</th><td><i class="fas ${res.icon || 'fa-calendar'}"></i> ${res.icon || '—'}</td></tr>
                        <tr><th>Description</th><td>${res.description || '<span class="text-muted">No description provided.</span>'}</td></tr>
                        ${res.created_at ? `<tr><th>Created</th><td>${new Date(res.created_at).toLocaleString()}</td></tr>` : ''}
                        ${res.updated_at ? `<tr><th>Last Updated</th><td>${new Date(res.updated_at).toLocaleString()}</td></tr>` : ''}
                    </table>
                `);
                $('#viewEventModal').modal('show');
            })
            .fail(() => showToast('error', 'Failed to load record.'));
    });

    // =========================================================================
    // DELETE
    // =========================================================================
    $(document).on('click', '.delete-btn', function () {
        const id = $(this).data('id');

        if (!confirm('Are you sure you want to delete this event?\nThis action cannot be undone.')) {
            return;
        }

        $.ajax({
            url      : baseUrl + 'events/delete/' + id,
            method   : 'POST',
            data     : { csrf_test_name: $('input[name="csrf_test_name"]').val() },
            dataType : 'json',
            success  : function (res) {
                refreshCsrf(res);
                if (res.status === 200) {
                    showToast('success', res.message || 'Event deleted successfully!');
                    eventsTable.ajax.reload(null, false);
                } else {
                    showToast('error', res.message || 'Failed to delete event.');
                }
            },
            error: function (xhr) {
                console.error('Delete error:', xhr.responseText);
                showToast('error', 'An error occurred. Please try again.');
            }
        });
    });

    // ── Reset modals on close ─────────────────────────────────────────────────
    $('#addEventModal, #editEventModal').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset();
        $(this).find('.validation-errors').remove();
    });

});
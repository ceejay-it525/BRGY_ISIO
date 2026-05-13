'use strict';

function showToast(type, message) {
    if (typeof toastr !== 'undefined') {
        toastr[type](message);
    } else {
        alert(message);
    }
}

function refreshCsrf(response) {
    const token = response && (response.csrf_hash || response.csrfHash);
    if (token && typeof csrfTokenName !== 'undefined') {
        $('input[name="' + csrfTokenName + '"]').val(token);
    }
}

function colorBadge(color) {
    return `<span class="badge badge-${color}">${color.charAt(0).toUpperCase() + color.slice(1)}</span>`;
}

let eventsTable;

$(function () {
    eventsTable = $('#eventsTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: baseUrl + 'events/fetchRecords',
            type: 'POST',
            data: d => { 
                if (typeof csrfTokenName !== 'undefined') {
                    d[csrfTokenName] = $('input[name="' + csrfTokenName + '"]').val(); 
                }
            },
            dataSrc: function(json) {
                refreshCsrf(json);
                return json.data;
            }
        },
        columns: [
            { data: null, render: (d, t, r, meta) => meta.row + 1 },
            { data: 'id', visible: false },
            { data: 'title', render: d => `<strong>${d}</strong>` },
            { data: 'description', render: d => d ? (d.length > 50 ? d.substring(0, 50) + '...' : d) : '<span class="text-muted">No description</span>' },
            { data: 'formatted_date' },
            { 
                data: 'days_until',
                render: d => {
                    if (d < 0) return '<span class="badge badge-danger">Past</span>';
                    if (d === 0) return '<span class="badge badge-success">Today</span>';
                    return `<span class="badge badge-info">${d} days</span>`;
                }
            },
            { data: 'color', render: d => colorBadge(d || 'secondary') },
            {
                data: null,
                render: r => `
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-info view-btn" data-id="${r.id}" title="View"><i class="fa fa-eye"></i></button>
                        <button class="btn btn-warning edit-btn" data-id="${r.id}" title="Edit"><i class="fa fa-edit"></i></button>
                        <button class="btn btn-danger delete-btn" data-id="${r.id}" title="Delete"><i class="fa fa-trash"></i></button>
                    </div>
                `
            }
        ]
    });

    $('#addEventForm, #editEventForm').on('submit', function (e) {
        e.preventDefault();
        const $form = $(this);
        const isEdit = $form.attr('id') === 'editEventForm';
        const id = isEdit ? $('#editEventId').val() : '';
        const url = isEdit ? baseUrl + 'events/update/' + id : baseUrl + 'events/save';
        const $btn = $form.find('button[type="submit"]');

        $btn.prop('disabled', true).prepend('<i class="fas fa-spinner fa-spin mr-1"></i>');

        $.ajax({
            url: url,
            method: 'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: (res) => {
                refreshCsrf(res);
                $('.modal').modal('hide');
                eventsTable.ajax.reload(null, false);
                showToast('success', res.message || 'Operation successful');
            },
            error: (xhr) => {
                const res = xhr.responseJSON;
                refreshCsrf(res);
                if (res && res.errors) {
                    alert('Validation Errors:\n' + Object.values(res.errors).join('\n'));
                } else {
                    showToast('error', 'An error occurred.');
                }
            },
            complete: () => {
                $btn.prop('disabled', false).find('.fa-spinner').remove();
            }
        });
    });

    $(document).on('click', '.view-btn', function() {
        const id = $(this).data('id');
        $.get(baseUrl + 'events/edit/' + id, (res) => {
            const iconHtml = res.icon ? `<i class="fas ${res.icon} fa-3x text-${res.color} mb-3"></i>` : '';
            $('#viewEventBody').html(`
                <div class="text-center">
                    ${iconHtml}
                    <h4>${res.title}</h4>
                    <p class="text-muted">${res.description || 'No description provided.'}</p>
                    <hr>
                    <div class="row text-left">
                        <div class="col-6"><strong>Date:</strong> ${res.formatted_date}</div>
                        <div class="col-6"><strong>Status:</strong> ${res.days_until < 0 ? 'Past' : (res.days_until === 0 ? 'Today' : res.days_until + ' days left')}</div>
                    </div>
                </div>
            `);
            $('#viewEventModal').modal('show');
        });
    });

    $(document).on('click', '.delete-btn', function() {
        const id = $(this).data('id');
        if(confirm('Are you sure you want to delete this event?')) {
            const data = {};
            if (typeof csrfTokenName !== 'undefined') {
                data[csrfTokenName] = $('input[name="' + csrfTokenName + '"]').val();
            }
            $.post(baseUrl + 'events/delete/' + id, data, (res) => {
                refreshCsrf(res);
                eventsTable.ajax.reload(null, false);
                showToast('success', 'Event deleted.');
            }).fail(() => showToast('error', 'Delete failed.'));
        }
    });
});

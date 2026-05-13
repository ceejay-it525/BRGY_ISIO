// =============================================================================
//  events.js  —  Barangay Events & Announcements System (Fixed)
// =============================================================================

// -----------------------------------------------------------------------------
// TOAST HELPER
// -----------------------------------------------------------------------------
function showToast(type, message) {
    if (typeof toastr !== 'undefined') {
        toastr.options = { closeButton: true, progressBar: true, positionClass: 'toast-top-right', timeOut: 3500 };
        if (toastr[type]) { toastr[type](message); return; }
    }
    const cls = { success: 'success', error: 'danger', warning: 'warning', info: 'info' }[type] || 'info';
    const el = $(`
        <div class="alert alert-${cls} alert-dismissible fade show"
             style="position:fixed;top:70px;right:15px;z-index:99999;min-width:300px;box-shadow:0 2px 12px rgba(0,0,0,.25)">
            ${message}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>`);
    $('body').append(el);
    setTimeout(() => el.alert('close'), 3500);
}

// -----------------------------------------------------------------------------
// CSRF
// -----------------------------------------------------------------------------
function csrfData() {
    return { csrf_test_name: $('input[name=csrf_test_name]').val() };
}
function updateCSRF(res) {
    const token = res && (res.csrf_hash || res.csrfHash);
    if (token) $('input[name=csrf_test_name]').val(token);
}

// -----------------------------------------------------------------------------
// FORMAT HELPERS
// -----------------------------------------------------------------------------
function formatDate(d) {
    if (!d || d === '0000-00-00') return '—';
    return new Date(d + 'T00:00:00').toLocaleDateString('en-PH', { year: 'numeric', month: 'short', day: 'numeric' });
}
function formatDateTime(d) {
    if (!d) return '—';
    return new Date(d).toLocaleString('en-PH', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });
}
function formatCurrency(v) {
    return '₱' + parseFloat(v || 0).toLocaleString('en-PH', { minimumFractionDigits: 2 });
}

function statusBadge(status) {
    if (!status) return '<span class="text-muted">—</span>';
    const map = {
        'Draft':     ['secondary', 'fa-file'],
        'Scheduled': ['info',      'fa-clock'],
        'Ongoing':   ['success',   'fa-play-circle'],
        'Completed': ['primary',   'fa-check-circle'],
        'Cancelled': ['danger',    'fa-ban'],
    };
    const [cls, icon] = map[status] || ['secondary', 'fa-question-circle'];
    return `<span class="badge badge-${cls}"><i class="fas ${icon} mr-1"></i>${status}</span>`;
}

function workflowButtons(status, id) {
    const btn = (cls, action, icon, label) =>
        `<button class="btn btn-${cls} btn-sm ${action} mr-1" data-id="${id}"><i class="fas ${icon} mr-1"></i>${label}</button>`;

    switch (status) {
        case 'Draft':
            return btn('success', 'mark-scheduled', 'fa-calendar-check', 'Mark Scheduled');
        case 'Scheduled':
            return btn('success', 'mark-ongoing', 'fa-play-circle', 'Mark Ongoing') +
                   btn('danger',  'cancel-event', 'fa-ban', 'Cancel');
        case 'Ongoing':
            return btn('primary', 'mark-completed', 'fa-check-circle', 'Mark Completed');
        case 'Completed':
            return '';
        case 'Cancelled':
            return '';
        default:
            return '';
    }
}

function daysRemainingBadge(days) {
    if (days === null || days === undefined || days === '') return '<span class="text-muted">—</span>';
    days = parseInt(days, 10);
    if (isNaN(days)) return '<span class="text-muted">—</span>';
    if (days < 0)  return '<span class="badge badge-danger">Past</span>';
    if (days === 0) return '<span class="badge badge-success">Today</span>';
    if (days === 1) return '<span class="badge badge-warning">Tomorrow</span>';
    return `<span class="badge badge-info">${days} days</span>`;
}

// -----------------------------------------------------------------------------
// Extract the last URI segment to use as view_type
// e.g. "events/draft" → "draft", "events" → "all"
// -----------------------------------------------------------------------------
function resolveViewType(uriString) {
    const parts = uriString.replace(/^\/|\/$/g, '').split('/');
    const last  = parts[parts.length - 1];
    const valid = ['draft', 'scheduled', 'ongoing', 'completed', 'cancelled'];
    return valid.includes(last) ? last : 'all';
}

// -----------------------------------------------------------------------------
// DATATABLE
// -----------------------------------------------------------------------------
let eventsTable;

$(function () {

    // currentView is the full URI string set in the blade template,
    // e.g. "events", "events/draft", "events/scheduled"
    const viewType = resolveViewType(typeof currentView !== 'undefined' ? currentView : '');

    if ($.fn.DataTable.isDataTable('#eventsTable')) {
        $('#eventsTable').DataTable().destroy();
    }

    eventsTable = $('#eventsTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        pageLength: 25,
        order:      [[0, 'desc']],
        ajax: {
            url:  baseUrl + 'events/fetchRecords',
            type: 'POST',
            data: function (d) {
                return $.extend({}, d, csrfData(), { view_type: viewType });
            },
            error: function (xhr) {
                console.error('❌ DataTable error:', xhr.responseText);
                showToast('error', 'Failed to load records.');
            }
        },
        columns: [
            {
                data: 'row_number', width: '4%', orderable: false, searchable: false,
                render: d => `<span class="text-muted small">${d}</span>`
            },
            { data: 'id', visible: false },
            {
                data: 'title',
                render: (d, t, r) =>
                    `<a href="javascript:void(0)" class="view-event-link" data-id="${r.id}">` +
                    `<i class="fas fa-calendar-alt mr-1 text-primary"></i><strong>${d || '—'}</strong></a>`
            },
            {
                data: 'description',
                render: d => d
                    ? (d.length > 50 ? d.substring(0, 50) + '…' : d)
                    : '<span class="text-muted">—</span>'
            },
            {
                data: 'venue',
                render: d => d
                    ? `<span><i class="fas fa-map-marker-alt mr-1 text-secondary"></i>${d}</span>`
                    : '<span class="text-muted">—</span>'
            },
            { data: 'event_date',     render: d => formatDate(d) },
            { data: 'days_remaining', render: v => daysRemainingBadge(v) },
            { data: 'budget',         render: v => `<strong class="text-success">${formatCurrency(v)}</strong>` },
            { data: 'status',         render: v => statusBadge(v) },
            {
                data: null, orderable: false, searchable: false, className: 'text-center', width: '8%',
                render: r =>
                    `<div class="btn-group btn-group-sm">` +
                    `<button class="btn btn-info view-event-btn"  data-id="${r.id}" title="View"><i class="fas fa-eye"></i></button>` +
                    `<button class="btn btn-warning edit-event"   data-id="${r.id}" title="Edit"><i class="fas fa-edit"></i></button>` +
                    `<button class="btn btn-danger delete-event"  data-id="${r.id}" title="Delete"><i class="fas fa-trash"></i></button>` +
                    `</div>`
            }
        ],
        language: {
            processing:  '<i class="fas fa-spinner fa-spin mr-1"></i>Loading…',
            emptyTable:  '<div class="text-center py-4 text-muted"><i class="fas fa-inbox fa-2x d-block mb-2"></i>No events found.</div>',
            zeroRecords: '<div class="text-center py-4 text-muted"><i class="fas fa-search fa-2x d-block mb-2"></i>No matching events.</div>'
        }
    });

    // -----------------------------------------------------------------------
    // HELPERS
    // -----------------------------------------------------------------------
    function reloadTable()  { if (eventsTable) eventsTable.ajax.reload(null, false); }

    function fetchStats() {
        $.get(baseUrl + 'events/stats', function (res) {
            if (res.status !== 'success') return;
            const d = res.data;
            $('#totalEvents')    .text(d.total     || 0);
            $('#draftEvents')    .text(d.draft     || 0);
            $('#scheduledEvents').text(d.scheduled || 0);
            $('#ongoingEvents')  .text(d.ongoing   || 0);
            $('#completedEvents').text(d.completed || 0);
            $('#cancelledEvents').text(d.cancelled || 0);
            $('#totalBudget')    .text(formatCurrency(d.budget));
            $('#draftCount')     .text(d.draft     || 0);
            $('#scheduledCount') .text(d.scheduled || 0);
            $('#ongoingCount')   .text(d.ongoing   || 0);
            $('#completedCount') .text(d.completed || 0);
            $('#cancelledCount') .text(d.cancelled || 0);
        });
    }

    fetchStats();
    setInterval(fetchStats, 30000);

    // -----------------------------------------------------------------------
    // VIEW MODAL
    // -----------------------------------------------------------------------
    function openViewModal(id) {
        if (!id) return;
        $.get(baseUrl + 'events/view/' + id, function (res) {
            if (res.status !== 'success') { showToast('error', res.message || 'Failed to load.'); return; }
            const p = res.data;

            $('#viewEventId')    .val(id);
            $('#viewTitle')      .text(p.title        || '—');
            $('#viewDescription').text(p.description  || '—');
            $('#viewVenue')      .text(p.venue         || '—');
            $('#viewEventDate')  .text(formatDate(p.event_date));
            $('#viewEventTime')  .text(p.event_time    || '—');
            $('#viewBudget')     .text(formatCurrency(p.budget));
            $('#viewParticipants').text(p.participants || '—');
            $('#viewStatus')     .html(statusBadge(p.status));
            $('#viewQRCode')     .text(p.qr_code       || '—');

            $('#printFromViewBtn').attr('data-id', id);
            $('#workflowActions').html(workflowButtons(p.status, id));

            const notes = p.notes
                ? `<div class="border-bottom py-2 small">
                       <strong>Notes:</strong><br>
                       <span>${p.notes}</span>
                   </div>`
                : '<p class="text-muted text-center py-3"><i class="fas fa-sticky-note mr-1"></i>No notes</p>';

            $('#viewNotes').html(notes);
            $('#viewEventModal').modal('show');
        }).fail(() => showToast('error', 'Network error. Please try again.'));
    }

    // Title link → view modal
    $(document).on('click', '.view-event-link', function (e) {
        e.preventDefault();
        openViewModal($(this).data('id'));
    });

    // Eye button in table → view modal  (FIX: was missing)
    $(document).on('click', '.view-event-btn', function () {
        openViewModal($(this).data('id'));
    });

    // Edit button inside view modal
    $(document).on('click', '#editFromViewBtn', function () {
        const id = $('#viewEventId').val();
        $('#viewEventModal').modal('hide');
        openEditModal(id);
    });

    // -----------------------------------------------------------------------
    // ADD EVENT
    // -----------------------------------------------------------------------
    $('#addEventForm').on('submit', function (e) {
        e.preventDefault();
        const $btn = $(this).find('[type=submit]').prop('disabled', true)
                            .html('<i class="fas fa-spinner fa-spin mr-1"></i>Saving…');

        $.post(baseUrl + 'events/save', $(this).serialize(), function (res) {
            updateCSRF(res);
            if (res.status === 'success') {
                $('#AddNewModal').modal('hide');
                document.getElementById('addEventForm').reset();
                showToast('success', res.message);
                reloadTable(); fetchStats();
            } else {
                showToast('error', res.message || 'Save failed.');
            }
        }, 'json')
        .fail(() => showToast('error', 'Server error.'))
        .always(() => $btn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i>Save Event'));
    });

    $('#AddNewModal').on('hidden.bs.modal', function () {
        document.getElementById('addEventForm').reset();
    });

    // -----------------------------------------------------------------------
    // EDIT EVENT
    // -----------------------------------------------------------------------
    function openEditModal(id) {
        if (!id) return;
        $.get(baseUrl + 'events/edit/' + id, function (res) {
            if (res.status !== 'success') { showToast('error', res.message || 'Failed to load.'); return; }
            const p = res.data;
            $('#editEventId')    .val(p.id);
            $('#editTitle')      .val(p.title);
            $('#editVenue')      .val(p.venue);
            $('#editEventDate')  .val(p.event_date);
            $('#editEventTime')  .val(p.event_time);
            $('#editEndDate')    .val(p.end_date);
            $('#editEndTime')    .val(p.end_time);
            $('#editBudget')     .val(p.budget);
            $('#editStatus')     .val(p.status);
            $('#editParticipants').val(p.participants);
            $('#editDescription').val(p.description);
            $('#editNotes')      .val(p.notes);
            $('#editIsPublic')   .prop('checked', p.is_public == 1);
            $('#editEventModal').modal('show');
        }).fail(() => showToast('error', 'Network error.'));
    }

    $(document).on('click', '.edit-event', function () { openEditModal($(this).data('id')); });

    $('#editEventForm').on('submit', function (e) {
        e.preventDefault();
        const $btn = $(this).find('[type=submit]').prop('disabled', true)
                            .html('<i class="fas fa-spinner fa-spin mr-1"></i>Updating…');

        $.post(baseUrl + 'events/update', $(this).serialize(), function (res) {
            updateCSRF(res);
            if (res.status === 'success') {
                $('#editEventModal').modal('hide');
                showToast('success', res.message);
                reloadTable(); fetchStats();
            } else {
                showToast('error', res.message || 'Update failed.');
            }
        }, 'json')
        .fail(() => showToast('error', 'Server error.'))
        .always(() => $btn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i>Update Event'));
    });

    // -----------------------------------------------------------------------
    // DELETE
    // -----------------------------------------------------------------------
    $(document).on('click', '.delete-event', function () {
        const id = $(this).data('id');
        if (!confirm('Delete this event? This cannot be undone.')) return;

        $.post(baseUrl + 'events/delete/' + id, csrfData(), function (res) {
            updateCSRF(res);
            if (res.status === 'success') { showToast('success', res.message); reloadTable(); fetchStats(); }
            else showToast('error', res.message || 'Delete failed.');
        }, 'json').fail(() => showToast('error', 'Server error.'));
    });

    // -----------------------------------------------------------------------
    // WORKFLOW: MARK SCHEDULED
    // -----------------------------------------------------------------------
    $(document).on('click', '.mark-scheduled', function () {
        const id = $(this).data('id');
        if (!confirm('Mark this event as Scheduled?')) return;
        $.post(baseUrl + 'events/markScheduled/' + id, csrfData(), function (res) {
            updateCSRF(res);
            if (res.status === 'success') {
                $('#viewEventModal').modal('hide');
                showToast('success', res.message);
                reloadTable(); fetchStats();
            } else showToast('error', res.message || 'Failed.');
        }, 'json').fail(() => showToast('error', 'Server error.'));
    });

    // -----------------------------------------------------------------------
    // WORKFLOW: MARK ONGOING
    // -----------------------------------------------------------------------
    $(document).on('click', '.mark-ongoing', function () {
        const id = $(this).data('id');
        if (!confirm('Mark this event as Ongoing?')) return;
        $.post(baseUrl + 'events/markOngoing/' + id, csrfData(), function (res) {
            updateCSRF(res);
            if (res.status === 'success') {
                $('#viewEventModal').modal('hide');
                showToast('success', res.message);
                reloadTable(); fetchStats();
            } else showToast('error', res.message || 'Failed.');
        }, 'json').fail(() => showToast('error', 'Server error.'));
    });

    // -----------------------------------------------------------------------
    // WORKFLOW: MARK COMPLETED
    // -----------------------------------------------------------------------
    $(document).on('click', '.mark-completed', function () {
        const id = $(this).data('id');
        if (!confirm('Mark this event as Completed?')) return;
        $.post(baseUrl + 'events/markCompleted/' + id, csrfData(), function (res) {
            updateCSRF(res);
            if (res.status === 'success') {
                $('#viewEventModal').modal('hide');
                showToast('success', res.message);
                reloadTable(); fetchStats();
            } else showToast('error', res.message || 'Failed.');
        }, 'json').fail(() => showToast('error', 'Server error.'));
    });

    // -----------------------------------------------------------------------
    // WORKFLOW: CANCEL EVENT
    // -----------------------------------------------------------------------
    $(document).on('click', '.cancel-event', function () {
        $('#cancelEventId').val($(this).data('id'));
        $('#cancelReason').val('');
        $('#cancelModal').modal('show');
    });

    $('#cancelEventForm').on('submit', function (e) {
        e.preventDefault();
        const id = $('#cancelEventId').val();
        const reason = $('#cancelReason').val().trim();
        if (!reason) { showToast('warning', 'Please enter a cancellation reason.'); return; }

        const $btn = $(this).find('[type=submit]').prop('disabled', true)
                            .html('<i class="fas fa-spinner fa-spin mr-1"></i>Cancelling…');

        $.post(
            baseUrl + 'events/cancelEvent/' + id,
            Object.assign({}, csrfData(), { reason }),
            function (res) {
                updateCSRF(res);
                if (res.status === 'success') {
                    $('#cancelModal').modal('hide');
                    $('#viewEventModal').modal('hide');
                    showToast('success', res.message);
                    reloadTable(); fetchStats();
                } else {
                    showToast('error', res.message || 'Cancellation failed.');
                }
            }, 'json'
        )
        .fail(() => showToast('error', 'Server error.'))
        .always(() => $btn.prop('disabled', false).html('<i class="fas fa-ban mr-1"></i>Confirm Cancellation'));
    });

    // -----------------------------------------------------------------------
    // PRINT PREVIEW
    // -----------------------------------------------------------------------
    $(document).on('click', '.print-event', function () {
        const id = $(this).data('id');
        $.ajax({
            url:      baseUrl + 'events/getPrintPreview/' + id,
            type:     'GET',
            dataType: 'json',
            timeout:  8000,
        })
        .done(res => {
            updateCSRF(res);
            if (res && res.status === 'success' && res.html) {
                $('#printPreviewContent').html(res.html);
                $('#printPreviewModal').modal('show');
            } else {
                showToast('error', res?.message || 'Failed to load preview.');
            }
        })
        .fail(e => {
            console.error('❌ Print error:', e);
            showToast('error', 'Failed to load preview.');
        });
    });

    $('#printNowBtn').on('click', function () {
        const content = $('#printPreviewContent').html();
        const w = window.open('', '', 'width=860,height=700');
        w.document.write(`<html><head><title>Event Report</title></head><body onload="window.print();window.close();">${content}</body></html>`);
        w.document.close();
    });
}); // end $(function)

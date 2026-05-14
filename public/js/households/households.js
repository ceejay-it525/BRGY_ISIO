// =============================================================
// HOUSEHOLDS MODULE — households.js
// Barangay Isio, Cauayan, Negros Occidental
// =============================================================

'use strict';

// ── CSRF ──────────────────────────────────────────────────────
function getCsrf() {
    return $('input[name=csrf_test_name]').val() || '';
}
function updateCSRF(response) {
    if (response && response.csrf_hash) {
        $('input[name=csrf_test_name]').val(response.csrf_hash);
    }
}

// ── Toast ─────────────────────────────────────────────────────
function showToast(type, message) {
    if (typeof toastr === 'undefined') { alert(message); return; }
    toastr.options = {
        closeButton:   true,
        progressBar:   true,
        positionClass: 'toast-top-right',
        timeOut:       3500
    };
    (toastr[type] || toastr.info)(message);
}

// ── Badge Helpers ─────────────────────────────────────────────
function statusBadge(status) {
    const map = {
        'Active':    ['badge-active',    'fa-check-circle'],
        'Relocated': ['badge-relocated', 'fa-truck-moving'],
        'Archived':  ['badge-archived',  'fa-archive']
    };
    const [cls, icon] = map[status] || ['badge-archived', 'fa-question-circle'];
    return `<span class="status-badge ${cls}"><i class="fas ${icon}"></i> ${status}</span>`;
}
function purokBadge(p) {
    return `<span class="badge-purok">Purok ${p}</span>`;
}
function membersBadge(n) {
    n = parseInt(n) || 1;
    return `<span class="badge-members"><i class="fas fa-users mr-1"></i>${n}</span>`;
}

// =============================================================
// ASSISTANCE STATE — in-memory (safe, no localStorage issues)
// =============================================================
let _assistedIds = [];

function getCheckedIds() {
    return _assistedIds.slice();
}
function saveCheckedIds(ids) {
    _assistedIds = ids.map(i => parseInt(i));
}
function isAssisted(id) {
    return _assistedIds.includes(parseInt(id));
}
function toggleAssisted(id) {
    id = parseInt(id);
    const idx = _assistedIds.indexOf(id);
    if (idx > -1) {
        _assistedIds.splice(idx, 1);
        return false;
    } else {
        _assistedIds.push(id);
        return true;
    }
}

// =============================================================
// DATATABLE
// =============================================================
let householdsTable;

$(document).ready(function () {

    if ($.fn.DataTable.isDataTable('#householdsTable')) {
        $('#householdsTable').DataTable().destroy();
    }

    householdsTable = $('#householdsTable').DataTable({
        processing : true,
        serverSide : true,
        responsive : true,
        pageLength : 10,
        lengthMenu : [[10, 25, 50, 100], [10, 25, 50, 100]],

        dom: '<"row align-items-center mb-2"' +
                 '<"col-sm-6 d-flex align-items-center gap-2"lB>' +
                 '<"col-sm-6"f>' +
             '>' +
             '<"row"<"col-12"tr>>' +
             '<"row align-items-center mt-2"<"col-sm-5"i><"col-sm-7"p>>',

        buttons: [
            { extend: 'excelHtml5', text: '<i class="fas fa-file-excel mr-1"></i>Excel', className: 'btn btn-sm btn-success', exportOptions: { columns: [0,1,2,3,4,5] } },
            { extend: 'pdfHtml5',   text: '<i class="fas fa-file-pdf mr-1"></i>PDF',     className: 'btn btn-sm btn-danger',  exportOptions: { columns: [0,1,2,3,4,5] } },
            { extend: 'print',      text: '<i class="fas fa-print mr-1"></i>Print',      className: 'btn btn-sm btn-secondary',exportOptions: { columns: [0,1,2,3,4,5] } }
        ],

        language: {
            processing:  '<div class="d-flex align-items-center justify-content-center py-3"><div class="spinner-border spinner-border-sm text-primary mr-2"></div><span class="text-muted">Loading records…</span></div>',
            emptyTable:  '<div class="empty-state"><i class="fas fa-home d-block"></i><h5 class="mt-2">No households registered yet</h5><p class="small text-muted mb-0">Click <strong>Add Household</strong> to get started.</p></div>',
            zeroRecords: '<div class="empty-state"><i class="fas fa-search d-block"></i><h5 class="mt-2">No matching records found</h5></div>'
        },

        ajax: {
            url:  baseUrl + 'households/fetchRecords',
            type: 'POST',
            data: function (d) { d.csrf_test_name = getCsrf(); },
            dataSrc: function (json) { updateCSRF(json); return json.data || []; },
            error: function (xhr) {
                console.error('DT error:', xhr.status, xhr.responseText);
                showToast('error', 'Failed to load records.');
            }
        },

        columns: [
            { data: 'row_number', className: 'text-center', orderable: false, width: '4%' },
            {
                data: 'head_name',
                render: (d, t, row) =>
                    `<a class="head-link view-household" data-id="${row.id}" title="View details">${d || '—'}</a>`
            },
            {
                data: 'address_line1',
                render: (d, t, row) => {
                    const city = row.city_municipality ? `, ${row.city_municipality}` : '';
                    return `<small class="text-muted">${d || '—'}${city}</small>`;
                }
            },
            { data: 'purok',         className: 'text-center', render: d => d ? purokBadge(d) : '—' },
            { data: 'total_members', className: 'text-center', render: d => membersBadge(d) },
            { data: 'status',        className: 'text-center', render: d => statusBadge(d || 'Archived') },
            {
                data: null,
                className: 'text-center actions-cell',
                orderable: false, searchable: false,
                width: '15%',
                render: function (d, t, row) {
                    const aided    = isAssisted(row.id);
                    const aidCls   = aided ? 'btn-act-assist-on'  : 'btn-act-assist-off';
                    const aidTitle = aided ? 'Remove from assistance' : 'Mark for assistance';
                    const aidIcon  = aided ? 'fa-heart'           : 'fa-hands-helping';
                    return `
                    <div class="action-group">
                        <button class="btn-act ${aidCls} toggle-assist" data-id="${row.id}" title="${aidTitle}">
                            <i class="fas ${aidIcon}"></i>
                        </button>
                        <button class="btn-act btn-act-edit edit-household" data-id="${row.id}" title="Edit">
                            <i class="fas fa-pen"></i>
                        </button>
                        <button class="btn-act btn-act-delete delete-household" data-id="${row.id}" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>`;
                }
            }
        ],

        initComplete: function () {
            $('#householdsTable_filter input')
                .addClass('form-control form-control-sm')
                .css('border-radius', '20px')
                .attr('placeholder', 'Search households…');

            // Load stats after table is ready
            fetchHouseholdStats();
        },

        drawCallback: function () {
            $('#householdsTable tbody tr').each(function (i) {
                const $tr = $(this);
                $tr.css({ opacity: 0, transform: 'translateY(6px)' });
                setTimeout(() => $tr.css({
                    transition: 'opacity .2s, transform .2s',
                    opacity: 1,
                    transform: 'translateY(0)'
                }), i * 30);
            });
        }
    });

    // ── Button bindings ──────────────────────────────────────
    $('#btnAddHousehold').on('click', function () {
        $('#addHouseholdForm')[0].reset();
        $('#addAlertBox').html('');
        $('#addHouseholdModal').modal('show');
    });

    $('#cardAssistance').on('click', function () { openAssistanceModal(); });
});

// =============================================================
// TOGGLE ASSISTANCE — inline table button
// =============================================================
$(document).on('click', '.toggle-assist', function (e) {
    e.stopPropagation();
    const $btn  = $(this);
    const id    = $btn.data('id');
    const isNow = toggleAssisted(id);

    $btn.css('transform', 'scale(1.4)');
    setTimeout(() => $btn.css('transform', 'scale(1)'), 200);

    if (isNow) {
        $btn.removeClass('btn-act-assist-off').addClass('btn-act-assist-on')
            .attr('title', 'Remove from assistance')
            .find('i').removeClass('fa-hands-helping').addClass('fa-heart');
        showToast('success', '<i class="fas fa-heart mr-1 text-danger"></i> Household marked for assistance.');
    } else {
        $btn.removeClass('btn-act-assist-on').addClass('btn-act-assist-off')
            .attr('title', 'Mark for assistance')
            .find('i').removeClass('fa-heart').addClass('fa-hands-helping');
        showToast('info', 'Removed from assistance list.');
    }
    updateAssistStatCard();
});

function updateAssistStatCard() {
    animateStat('statPriority', getCheckedIds().length);
}

// =============================================================
// VIEW DETAIL MODAL (head-name click)
// =============================================================
$(document).on('click', '.view-household', function (e) {
    e.preventDefault();
    const id = $(this).data('id');
    if (!id) return;

    $('#householdDetailBody').html(
        '<div class="text-center py-5"><div class="spinner-border text-info"></div>' +
        '<p class="mt-2 text-muted small">Loading details…</p></div>'
    );
    $('#householdDetailModal').modal('show');

    $.ajax({
        url: baseUrl + 'households/get/' + id,
        type: 'GET',
        dataType: 'json',
        success: function (res) {
            updateCSRF(res);
            if (res.status === 'success') {
                renderHouseholdDetail(res.data);
                $('#detailEditBtn').off('click').on('click', function () {
                    $('#householdDetailModal').modal('hide');
                    loadEditModal(id);
                });
            } else {
                $('#householdDetailBody').html(
                    '<div class="text-center py-4 text-danger"><i class="fas fa-exclamation-circle fa-2x mb-2 d-block"></i>' +
                    (res.message || 'Could not load.') + '</div>'
                );
            }
        },
        error: function (xhr) {
            console.error('View error:', xhr.status, xhr.responseText);
            $('#householdDetailBody').html(
                '<div class="text-center py-4 text-danger"><i class="fas fa-exclamation-circle fa-2x mb-2 d-block"></i>Server error.</div>'
            );
        }
    });
});

function renderHouseholdDetail(h) {
    const statusMap = {
        'Active':    ['badge-active',    'fa-check-circle'],
        'Relocated': ['badge-relocated', 'fa-truck-moving'],
        'Archived':  ['badge-archived',  'fa-archive']
    };
    const [sCls, sIcon] = statusMap[h.status] || ['badge-archived', 'fa-question-circle'];
    const date     = h.created_at ? h.created_at.substring(0, 10) : '—';
    const fullAddr = [h.address_line1, 'Purok ' + h.purok, h.barangay, h.city_municipality, h.province].filter(Boolean).join(', ');
    const aided    = isAssisted(h.id);

    $('#householdDetailBody').html(`
        <div class="detail-hero">
            <div class="detail-hero-avatar"><i class="fas fa-home"></i></div>
            <div class="detail-hero-name">${h.head_name || '—'}</div>
            <span class="status-badge ${sCls}" style="display:inline-flex;font-size:.8rem;">
                <i class="fas ${sIcon}"></i>&nbsp;${h.status || '—'}
            </span>
            ${aided ? '<div class="mt-2"><span class="assist-needs-badge"><i class="fas fa-heart mr-1"></i>Marked for Assistance</span></div>' : ''}
        </div>
        <div class="detail-rows">
            <div class="detail-row">
                <div class="detail-row-icon"><i class="fas fa-map-marker-alt"></i></div>
                <div><div class="detail-row-label">Full Address</div><div class="detail-row-value">${fullAddr || '—'}</div></div>
            </div>
            <div class="detail-row">
                <div class="detail-row-icon"><i class="fas fa-th-large"></i></div>
                <div><div class="detail-row-label">Purok</div><div class="detail-row-value">${h.purok ? purokBadge(h.purok) : '—'}</div></div>
            </div>
            <div class="detail-row">
                <div class="detail-row-icon"><i class="fas fa-users"></i></div>
                <div><div class="detail-row-label">Total Members</div><div class="detail-row-value">${membersBadge(h.total_members)}</div></div>
            </div>
            <div class="detail-row">
                <div class="detail-row-icon"><i class="fas fa-calendar-alt"></i></div>
                <div><div class="detail-row-label">Registered</div><div class="detail-row-value">${date}</div></div>
            </div>
        </div>`);
}

// =============================================================
// STATS
// =============================================================
function fetchHouseholdStats() {
    // Show spinners while loading
    ['statTotal', 'statActive', 'statNew', 'statPriority'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.innerHTML = '<div class="stat-spinner"></div>';
    });

    $.ajax({
        url:      baseUrl + 'households/fetchStats',
        type:     'GET',
        dataType: 'json',
        timeout:  15000,
        success: function (res) {
            if (res.status === 'success') {
                const d = res.data;
                animateStat('statTotal',    d.total_households  || 0);
                animateStat('statActive',   d.active_households || 0);
                animateStat('statNew',      d.new_households    || 0);
                // Assistance priority = locally tracked ids count
                animateStat('statPriority', getCheckedIds().length);
                updateCSRF(res);
            } else {
                console.warn('[fetchStats] error:', res.message);
                setStatError();
            }
        },
        error: function (xhr, status, err) {
            console.error('[fetchStats] AJAX failed:', status, err, xhr.responseText);
            setStatError();
        }
    });
}

function animateStat(id, target) {
    const el = document.getElementById(id);
    if (!el) return;
    target = parseInt(target) || 0;
    const step = Math.max(1, Math.ceil(target / 30));
    let cur = 0;
    el.textContent = '0';
    if (target === 0) { el.textContent = '0'; return; }
    const t = setInterval(() => {
        cur += step;
        if (cur >= target) { cur = target; clearInterval(t); }
        el.textContent = cur.toLocaleString();
    }, 16);
}

function setStatError() {
    ['statTotal', 'statActive', 'statNew', 'statPriority'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.textContent = '—';
    });
}

// =============================================================
// ASSISTANCE PRIORITY MODAL
// =============================================================
let assistAllData = [];

function openAssistanceModal() {
    // Reset UI
    $('#assistListWrapper').html(
        '<div class="text-center py-5">' +
        '<div class="spinner-border text-danger"></div>' +
        '<p class="mt-2 text-muted small">Loading households…</p>' +
        '</div>'
    );
    $('#assistSummaryText').html('<i class="fas fa-spinner fa-spin mr-1"></i>Loading…');
    $('#assistCheckedCount').html('');
    $('#assistSearch').val('');
    $('#assistancePriorityModal').modal('show');

    $.ajax({
        url:      baseUrl + 'households/fetchAssistancePriority',
        type:     'GET',
        dataType: 'json',
        timeout:  15000,
        success: function (res) {
            updateCSRF(res);
            if (res.status === 'success') {
                assistAllData = res.data || [];
                renderAssistanceList(assistAllData, '');
            } else {
                console.warn('[fetchAssistancePriority] error:', res.message);
                $('#assistListWrapper').html(
                    '<div class="assist-empty">' +
                    '<i class="fas fa-exclamation-circle d-block text-danger"></i>' +
                    '<p class="mt-2">' + (res.message || 'Could not load households.') + '</p>' +
                    '</div>'
                );
                $('#assistSummaryText').html('<span class="text-danger"><i class="fas fa-times-circle mr-1"></i>Failed to load</span>');
            }
        },
        error: function (xhr, status, err) {
            console.error('[fetchAssistancePriority] AJAX failed:', status, err);
            console.error('Response:', xhr.responseText);
            $('#assistListWrapper').html(
                '<div class="assist-empty">' +
                '<i class="fas fa-exclamation-circle d-block text-danger"></i>' +
                '<p class="mt-2">Server error. Check console for details.</p>' +
                '</div>'
            );
            $('#assistSummaryText').html('<span class="text-danger"><i class="fas fa-times-circle mr-1"></i>Server error</span>');
        }
    });
}

function renderAssistanceList(data, q) {
    const ids = getCheckedIds();
    const filtered = q
        ? data.filter(h =>
            [h.head_name, h.address_line1, h.purok, h.city_municipality]
                .join(' ').toLowerCase().includes(q.toLowerCase()))
        : data;

    $('#assistSummaryText').html(
        `<i class="fas fa-list mr-1"></i><strong>${data.length}</strong> active &nbsp;|&nbsp; Showing <strong>${filtered.length}</strong>`
    );
    $('#assistCheckedCount').html(
        ids.length > 0
            ? `<span class="badge badge-danger px-2 py-1"><i class="fas fa-heart mr-1"></i>${ids.length} marked</span>`
            : ''
    );

    if (!filtered.length) {
        $('#assistListWrapper').html(
            '<div class="assist-empty"><i class="fas fa-search d-block"></i><p>No households found.</p></div>'
        );
        return;
    }

    let html = '';
    filtered.forEach((h, i) => {
        const chk = ids.includes(parseInt(h.id));
        html += `
        <div class="assist-card ${chk ? 'is-checked' : ''}" data-id="${h.id}">
            <div class="assist-card-check">
                <input type="checkbox" class="assist-checkbox" data-id="${h.id}" ${chk ? 'checked' : ''} />
            </div>
            <div class="assist-card-rank">${i + 1}</div>
            <div class="assist-card-body">
                <div class="assist-card-name">
                    <i class="fas fa-home mr-1 text-muted" style="font-size:.8rem;"></i>${h.head_name || '—'}
                    ${chk ? '<span class="assist-needs-badge ml-2"><i class="fas fa-heart"></i> Needs Assistance</span>' : ''}
                </div>
                <div class="assist-card-addr">
                    <i class="fas fa-map-marker-alt mr-1"></i>${h.address_line1 || ''}${h.city_municipality ? ', ' + h.city_municipality : ''}
                </div>
                <div class="assist-card-meta">
                    ${purokBadge(h.purok)}
                    <span class="badge-members"><i class="fas fa-users mr-1"></i>${h.total_members || 1}</span>
                    <span class="status-badge badge-active" style="font-size:.7rem;padding:2px 8px;">
                        <i class="fas fa-check-circle"></i> Active
                    </span>
                </div>
            </div>
        </div>`;
    });
    $('#assistListWrapper').html(html);
}

// Checkbox change inside assist modal
$(document).on('change', '.assist-checkbox', function () {
    const id  = parseInt($(this).data('id'));
    const ids = getCheckedIds();

    if ($(this).is(':checked')) {
        if (!ids.includes(id)) ids.push(id);
    } else {
        const idx = ids.indexOf(id);
        if (idx > -1) ids.splice(idx, 1);
    }
    saveCheckedIds(ids);

    const $card = $(this).closest('.assist-card');
    if ($(this).is(':checked')) {
        $card.addClass('is-checked');
        $card.find('.assist-needs-badge').remove();
        $card.find('.assist-card-name').append('<span class="assist-needs-badge ml-2"><i class="fas fa-heart"></i> Needs Assistance</span>');
    } else {
        $card.removeClass('is-checked');
        $card.find('.assist-needs-badge').remove();
    }

    $('#assistCheckedCount').html(
        ids.length > 0
            ? `<span class="badge badge-danger px-2 py-1"><i class="fas fa-heart mr-1"></i>${ids.length} marked</span>`
            : ''
    );
    updateAssistStatCard();

    // Sync table row assist buttons
    householdsTable.rows().every(function () {
        const row = this.data();
        if (!row) return;
        const $btn = $(householdsTable.row(this.index()).node()).find('.toggle-assist');
        const aided = ids.includes(parseInt(row.id));
        $btn.removeClass('btn-act-assist-on btn-act-assist-off')
            .addClass(aided ? 'btn-act-assist-on' : 'btn-act-assist-off');
        $btn.find('i').removeClass('fa-heart fa-hands-helping')
            .addClass(aided ? 'fa-heart' : 'fa-hands-helping');
    });
});

// Clicking the card body (not the checkbox) also toggles
$(document).on('click', '.assist-card-body', function () {
    const $cb = $(this).closest('.assist-card').find('.assist-checkbox');
    $cb.prop('checked', !$cb.is(':checked')).trigger('change');
});

// Search inside assist modal
$('#assistSearch').on('keyup input', function () {
    renderAssistanceList(assistAllData, $(this).val().trim());
});

// Save list button
$('#btnSaveAssistance').on('click', function () {
    const ids = getCheckedIds();
    if (!ids.length) { showToast('warning', 'No households marked yet.'); return; }
    showToast('success', `${ids.length} household${ids.length > 1 ? 's' : ''} saved to assistance list.`);
    $('#assistancePriorityModal').modal('hide');
    updateAssistStatCard();
});

// Clear all button
$('#btnClearAssistance').on('click', function () {
    saveCheckedIds([]);
    renderAssistanceList(assistAllData, $('#assistSearch').val().trim());
    updateAssistStatCard();
    showToast('info', 'Assistance list cleared.');
    householdsTable.ajax.reload(null, false);
});

// On modal close
$('#assistancePriorityModal').on('hidden.bs.modal', function () {
    $('#assistSearch').val('');
    updateAssistStatCard();
});

// =============================================================
// ADD HOUSEHOLD
// =============================================================
$('#addHouseholdForm').on('submit', function (e) {
    e.preventDefault();
    $('#addAlertBox').html('');
    const $btn = $('#addSaveBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Saving…');

    $.ajax({
        url:      baseUrl + 'households/save',
        type:     'POST',
        data:     $(this).serialize() + '&csrf_test_name=' + getCsrf(),
        dataType: 'json',
        success: function (res) {
            updateCSRF(res);
            if (res.status === 'success') {
                $('#addHouseholdModal').modal('hide');
                $('#addHouseholdForm')[0].reset();
                showToast('success', res.message);
                householdsTable.ajax.reload(null, false);
                fetchHouseholdStats();
            } else {
                showAlert('addAlertBox', 'danger', res.message);
            }
        },
        error: function () { showToast('error', 'Server error.'); },
        complete: function () {
            $btn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i>Save Household');
        }
    });
});

// =============================================================
// EDIT HOUSEHOLD
// =============================================================
function loadEditModal(id) {
    $('#editAlertBox').html('');
    $.ajax({
        url:      baseUrl + 'households/get/' + id,
        type:     'GET',
        dataType: 'json',
        success: function (res) {
            updateCSRF(res);
            if (res.status === 'success') {
                const h = res.data;
                $('#editHouseholdId').val(h.id || '');
                $('#editHeadName').val(h.head_name || '');
                $('#editTotalMembers').val(h.total_members || 1);
                $('#editStatus').val(h.status || 'Active');
                $('#editAddressLine1').val(h.address_line1 || '');
                $('#editPurok').val(h.purok || '');
                $('#editHouseholdModal').modal('show');
            } else {
                showToast('error', res.message || 'Could not load.');
            }
        },
        error: function () { showToast('error', 'Failed to load data.'); }
    });
}

$(document).on('click', '.edit-household', function () {
    const $btn = $(this);
    $btn.html('<i class="fas fa-spinner fa-spin"></i>');
    setTimeout(() => $btn.html('<i class="fas fa-pen"></i>'), 800);
    loadEditModal($btn.data('id'));
});

$('#editHouseholdForm').on('submit', function (e) {
    e.preventDefault();
    $('#editAlertBox').html('');
    const $btn = $('#editSaveBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Updating…');

    $.ajax({
        url:      baseUrl + 'households/update',
        type:     'POST',
        data:     $(this).serialize() + '&csrf_test_name=' + getCsrf(),
        dataType: 'json',
        success: function (res) {
            updateCSRF(res);
            if (res.status === 'success') {
                $('#editHouseholdModal').modal('hide');
                showToast('success', res.message);
                householdsTable.ajax.reload(null, false);
                fetchHouseholdStats();
            } else {
                showAlert('editAlertBox', 'danger', res.message);
            }
        },
        error: function () { showToast('error', 'Server error.'); },
        complete: function () {
            $btn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i>Update Household');
        }
    });
});

// =============================================================
// DELETE HOUSEHOLD
// =============================================================
$(document).on('click', '.delete-household', function () {
    const $btn = $(this);
    const id   = $btn.data('id');
    if (!id) { showToast('error', 'Invalid record.'); return; }

    $btn.css('transform', 'rotate(-10deg)');
    setTimeout(() => $btn.css('transform', 'rotate(0deg)'), 300);

    const confirmFn = typeof Swal !== 'undefined'
        ? Swal.fire({
            title:              'Delete Household?',
            html:               '<p class="text-muted mb-0">This action <strong>cannot be undone</strong>.</p>',
            icon:               'warning',
            showCancelButton:   true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor:  '#6c757d',
            confirmButtonText:  '<i class="fas fa-trash mr-1"></i>Yes, Delete',
            cancelButtonText:   '<i class="fas fa-times mr-1"></i>Cancel',
            reverseButtons:     true,
            focusCancel:        true
          }).then(r => r.isConfirmed)
        : Promise.resolve(confirm('Delete this household?'));

    confirmFn.then(function (confirmed) {
        if (!confirmed) return;

        const $row = $btn.closest('tr');
        $row.css({ transition: 'opacity .3s ease, transform .3s ease', opacity: 0, transform: 'translateX(20px)' });

        setTimeout(function () {
            $.ajax({
                url:      baseUrl + 'households/delete/' + id,
                type:     'POST',
                data:     { csrf_test_name: getCsrf() },
                dataType: 'json',
                success: function (res) {
                    updateCSRF(res);
                    if (res.status === 'success') {
                        // Remove from assisted list if present
                        saveCheckedIds(getCheckedIds().filter(i => i !== parseInt(id)));
                        showToast('success', res.message);
                        householdsTable.ajax.reload(null, false);
                        fetchHouseholdStats();
                        updateAssistStatCard();
                    } else {
                        $row.css({ opacity: 1, transform: 'translateX(0)' });
                        showToast('error', res.message);
                    }
                },
                error: function () {
                    $row.css({ opacity: 1, transform: 'translateX(0)' });
                    showToast('error', 'Delete failed.');
                }
            });
        }, 300);
    });
});

// =============================================================
// HELPERS
// =============================================================
function showAlert(boxId, type, message) {
    $('#' + boxId).html(
        `<div class="alert alert-${type} alert-dismissible py-2 px-3 mb-2">
            <i class="fas fa-exclamation-circle mr-1"></i>${message}
            <button type="button" class="close py-2" data-dismiss="alert">&times;</button>
        </div>`
    );
}

$('#addHouseholdModal').on('hidden.bs.modal', function () {
    $('#addHouseholdForm')[0].reset();
    $('#addAlertBox').html('');
});
$('#editHouseholdModal').on('hidden.bs.modal', function () {
    $('#editAlertBox').html('');
});

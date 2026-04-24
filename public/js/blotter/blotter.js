const baseUrl = window.location.origin + '/';

function showToast(type, message) {
    if (type === 'success') toastr.success(message);
    else toastr.error(message);
}

$(document).ready(function () {

    const table = $('#blotterTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: baseUrl + 'blotter/fetchRecords',
            type: 'POST'
        },
        columns: [
            { data: 'row_number' },
            { data: 'case_number' },
            { data: 'incident_date' },
            { data: 'incident_location' },
            { data: 'status' },
            {
                data: null,
                render: function (data) {
                    return `
                        <button class="btn btn-warning btn-sm edit" data-id="${data.blotter_id}">Edit</button>
                        <button class="btn btn-danger btn-sm delete" data-id="${data.blotter_id}">Delete</button>
                    `;
                }
            }
        ]
    });

    // SAVE
    $('#addForm').submit(function (e) {
        e.preventDefault();

        $.post(baseUrl + 'blotter/save', $(this).serialize(), function (res) {
            $('#AddModal').modal('hide');
            showToast('success', res.message);
            table.ajax.reload();
        });
    });

    // EDIT
    $(document).on('click', '.edit', function () {
        let id = $(this).data('id');

        $.get(baseUrl + 'blotter/edit/' + id, function (data) {
            $('#edit_id').val(data.blotter_id);
            $('#edit_case').val(data.case_number);
            $('#edit_date').val(data.incident_date);
            $('#edit_location').val(data.incident_location);
            $('#EditModal').modal('show');
        });
    });

    // UPDATE
    $('#editForm').submit(function (e) {
        e.preventDefault();
        let id = $('#edit_id').val();

        $.post(baseUrl + 'blotter/update/' + id, $(this).serialize(), function (res) {
            $('#EditModal').modal('hide');
            showToast('success', res.message);
            table.ajax.reload();
        });
    });

    // DELETE
    $(document).on('click', '.delete', function () {
        if (!confirm('Delete this record?')) return;

        let id = $(this).data('id');

        $.post(baseUrl + 'blotter/delete/' + id, function (res) {
            showToast('success', res.message);
            table.ajax.reload();
        });
    });// LOAD PARTIES
function loadParties(id) {
    $.get(baseUrl + 'blotter/getParties/' + id, function (data) {
        let html = '';
        data.forEach(p => {
            html += `<tr>
                <td>${p.full_name}</td>
                <td>${p.role}</td>
                <td><button class="btn btn-danger btn-sm delParty" data-id="${p.blotter_party_id}">X</button></td>
            </tr>`;
        });
        $('#partyTable').html(html);
    });
}

// ADD PARTY
$('#addParty').click(function () {
    $.post(baseUrl + 'blotter/saveParty', {
        blotter_id: $('#edit_id').val(),
        full_name: $('#party_name').val(),
        role: $('#party_role').val()
    }, function () {
        loadParties($('#edit_id').val());
        showToast('success', 'Party added');
    });
});

// DELETE PARTY
$(document).on('click', '.delParty', function () {
    $.get(baseUrl + 'blotter/deleteParty/' + $(this).data('id'), function () {
        loadParties($('#edit_id').val());
    });
});

// LOAD HEARINGS
function loadHearings(id) {
    $.get(baseUrl + 'blotter/getHearings/' + id, function (data) {
        let html = '';
        data.forEach(h => {
            html += `<tr>
                <td>${h.hearing_date}</td>
                <td>${h.venue}</td>
                <td><button class="btn btn-danger btn-sm delHearing" data-id="${h.hearing_id}">X</button></td>
            </tr>`;
        });
        $('#hearingTable').html(html);
    });
}

// ADD HEARING
$('#addHearing').click(function () {
    $.post(baseUrl + 'blotter/saveHearing', {
        blotter_id: $('#edit_id').val(),
        hearing_date: $('#hearing_date').val(),
        venue: $('#hearing_venue').val()
    }, function () {
        loadHearings($('#edit_id').val());
    });
});

// DELETE HEARING
$(document).on('click', '.delHearing', function () {
    $.get(baseUrl + 'blotter/deleteHearing/' + $(this).data('id'), function () {
        loadHearings($('#edit_id').val());
    });
});

// WHEN EDIT CLICKED
$(document).on('click', '.edit', function () {
    let id = $(this).data('id');

    $.get(baseUrl + 'blotter/edit/' + id, function (data) {
        $('#edit_id').val(data.blotter_id);
        $('#edit_case').val(data.case_number);
        $('#EditModal').modal('show');

        loadParties(id);
        loadHearings(id);
    });
});

});
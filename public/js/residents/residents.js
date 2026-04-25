$(document).ready(function () {
    // These must be defined in your PHP file's <script> block
    const baseUrl = window.baseUrl; 
    const csrfName = window.csrfName;
    const csrfHash = window.csrfHash;

    // 🔧 1. Initialize DataTable
    let dataTable = $('#residentsTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        pageLength: 10,
        order: [[1, 'asc']], // Default sort by Full Name
        ajax: {
            url: baseUrl + 'residents/fetchRecords',
            type: 'POST',
            data: function(d) {
                d[csrfName] = csrfHash;
            }
        },
        columns: [
            { data: 'row_number', name: 'row_number', searchable: false }, // No.
            { 
                data: 'first_name', 
                render: function(data, type, row) {
                    // Combines names for the "Full Name" column
                    return `${row.first_name} ${row.last_name || ''}`.trim();
                } 
            }, // Full Name
            { data: 'gender' },       // Gender
            { data: 'civil_status' }, // Civil Status
            { 
                data: 'is_voter',
                render: function(data) {
                    return data == '1' ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-danger">No</span>';
                }
            }, // Voter
            { data: 'address_line1' }, // Address
            { 
                data: 'status',
                render: function(data) {
                    let badge = (data === 'Active' || data == '1') ? 'bg-success' : 'bg-warning';
                    let text = (data === 'Active' || data == '1') ? 'Active' : 'Inactive';
                    return `<span class="badge ${badge}">${text}</span>`;
                }
            }, // Status
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return `
                        <div class="btn-group">
                            <button class="btn btn-sm btn-warning edit-resident" data-id="${row.id}">
                                <i class="fas fa-edit text-white"></i>
                            </button>
                            <button class="btn btn-sm btn-danger delete-resident" data-id="${row.id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>`;
                }
            } // Actions
        ],
        // Re-attach events every time the table redrawing (for pagination/search)
        drawCallback: function() {
            attachEventHandlers();
        }
    });

    // 🔧 2. Form Submit (Save & Update)
    $('#addResidentForm, #editResidentForm').submit(function (e) {
        e.preventDefault();
        const form = $(this);
        const isEdit = form.attr('id') === 'editResidentForm';
        const url = isEdit ? baseUrl + 'residents/update' : baseUrl + 'residents/save';
        
        $.ajax({
            url: url,
            method: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    $('.modal').modal('hide');
                    form[0].reset();
                    dataTable.ajax.reload(null, false);
                    alert(response.message); 
                }
            },
            error: function(xhr) {
                const errors = xhr.responseJSON ? xhr.responseJSON.messages : "An error occurred";
                alert(typeof errors === 'object' ? Object.values(errors).join("\n") : errors);
            }
        });
    });

    // 🔧 3. Event Handlers
    function attachEventHandlers() {
        $('.edit-resident').off('click').on('click', function() {
            editResident($(this).data('id'));
        });
        $('.delete-resident').off('click').on('click', function() {
            deleteResident($(this).data('id'));
        });
    }

    // 🔧 4. Edit Function
    window.editResident = function(id) {
        $.ajax({
            url: baseUrl + 'residents/get/' + id,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    const r = response.data;
                    $('#editResidentId').val(r.id);
                    $('#editFirstName').val(r.first_name);
                    $('#editMiddleName').val(r.middle_name);
                    $('#editLastName').val(r.last_name);
                    $('#editSuffix').val(r.suffix);
                    $('#editBirthdate').val(r.birthdate);
                    $('#editGender').val(r.gender);
                    $('#editCivilStatus').val(r.civil_status);
                    $('#editIsVoter').val(r.is_voter);
                    $('#editVoterId').val(r.voter_id);
                    $('#editHouseholdId').val(r.household_id);
                    $('#editStatus').val(r.status);
                    $('#editAddressLine1').val(r.address_line1);
                    $('#editBarangay').val(r.barangay);
                    $('#editResidentModal').modal('show');
                }
            }
        });
    };

    // 🔧 5. Delete Function
    window.deleteResident = function(id) {
        if (confirm('Are you sure you want to delete this resident?')) {
            $.ajax({
                url: baseUrl + 'residents/delete/' + id,
                method: 'POST',
                data: { 
                    _method: 'DELETE',
                    [csrfName]: csrfHash 
                },
                success: function() {
                    dataTable.ajax.reload(null, false);
                }
            });
        }
    };
});
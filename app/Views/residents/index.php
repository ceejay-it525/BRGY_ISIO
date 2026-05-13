<?= $this->extend('theme/template') ?>

<?= $this->section('content') ?>

<style>
/* ============================================================
   RESIDENTS PAGE — CLEAN GOVERNMENT BLUE DESIGN
   ============================================================ */

/* -- Page wrapper -- */
.residents-wrapper {
    padding: 0;
    background: #f0f4f8;
    min-height: 100vh;
}

/* -- Top hero bar -- */
.residents-hero {
    background: linear-gradient(135deg, #1a3a5c 0%, #1d6fa4 60%, #2196c4 100%);
    padding: 28px 32px 24px;
    position: relative;
    overflow: hidden;
    margin-bottom: 0;
}
.residents-hero::before {
    content: '';
    position: absolute;
    top: -60px; right: -60px;
    width: 240px; height: 240px;
    background: rgba(255,255,255,0.05);
    border-radius: 50%;
}
.residents-hero::after {
    content: '';
    position: absolute;
    bottom: -40px; left: 20%;
    width: 160px; height: 160px;
    background: rgba(255,255,255,0.04);
    border-radius: 50%;
}
.residents-hero h1 {
    font-family: 'Segoe UI', 'Source Sans Pro', sans-serif;
    font-size: 1.75rem;
    font-weight: 700;
    color: #fff;
    margin: 0 0 4px 0;
    letter-spacing: -0.3px;
}
.residents-hero p.sub {
    color: rgba(255,255,255,0.72);
    font-size: 0.875rem;
    margin: 0;
}
.residents-hero .hero-breadcrumb {
    color: rgba(255,255,255,0.55);
    font-size: 0.8rem;
    margin-bottom: 10px;
}
.residents-hero .hero-breadcrumb a {
    color: rgba(255,255,255,0.75);
    text-decoration: none;
}
.residents-hero .hero-breadcrumb a:hover { color: #fff; }
.residents-hero .hero-breadcrumb span { margin: 0 6px; }

/* -- Stat cards -- */
.stat-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1px;
    background: #d0dbe8;
    border-bottom: 1px solid #d0dbe8;
}
.stat-card {
    background: #fff;
    padding: 18px 22px;
    display: flex;
    align-items: center;
    gap: 14px;
    transition: background 0.18s;
}
.stat-card:hover { background: #f7fafd; }
.stat-icon {
    width: 46px; height: 46px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.15rem;
    flex-shrink: 0;
}
.stat-icon.blue   { background: #e3f0fb; color: #1d6fa4; }
.stat-icon.green  { background: #e3f8ee; color: #1a8a4a; }
.stat-icon.orange { background: #fff3e0; color: #e07b00; }
.stat-icon.red    { background: #fdeaea; color: #c0392b; }
.stat-label {
    font-size: 0.75rem;
    color: #7a8fa6;
    font-weight: 600;
    letter-spacing: 0.4px;
    text-transform: uppercase;
    margin-bottom: 2px;
}
.stat-value {
    font-size: 1.4rem;
    font-weight: 700;
    color: #1a2e42;
    line-height: 1;
}

/* -- Main card -- */
.main-card {
    background: #fff;
    margin: 20px 24px;
    border-radius: 10px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.08), 0 4px 18px rgba(0,0,0,0.05);
    overflow: hidden;
}
.main-card-header {
    padding: 18px 22px;
    border-bottom: 1px solid #e8edf3;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
}
.main-card-header h3 {
    font-size: 1rem;
    font-weight: 700;
    color: #1a2e42;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}
.main-card-header h3 i {
    color: #1d6fa4;
}
.header-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

/* -- Buttons -- */
.btn-primary-custom {
    background: linear-gradient(135deg, #1d6fa4, #1a5a8a);
    color: #fff;
    border: none;
    border-radius: 7px;
    padding: 9px 18px;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    transition: all 0.18s;
    box-shadow: 0 2px 8px rgba(29,111,164,0.3);
    text-decoration: none;
}
.btn-primary-custom:hover {
    background: linear-gradient(135deg, #1a5a8a, #14456e);
    box-shadow: 0 4px 14px rgba(29,111,164,0.4);
    transform: translateY(-1px);
    color: #fff;
}
.btn-secondary-custom {
    background: #fff;
    color: #3a5a78;
    border: 1.5px solid #c4d4e4;
    border-radius: 7px;
    padding: 8px 16px;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    transition: all 0.18s;
    text-decoration: none;
}
.btn-secondary-custom:hover {
    background: #f0f6fc;
    border-color: #1d6fa4;
    color: #1d6fa4;
}

/* -- Table styling -- */
.main-card-body { padding: 0; }
#residentsTable_wrapper { padding: 16px 22px 22px; }
#residentsTable thead tr {
    background: #f4f8fc;
}
#residentsTable thead th {
    font-size: 0.73rem;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    color: #6b8aaa;
    border-bottom: 2px solid #e0e9f3 !important;
    border-top: none !important;
    padding: 12px 10px;
    white-space: nowrap;
}
#residentsTable tbody tr {
    transition: background 0.14s;
}
#residentsTable tbody tr:hover {
    background: #f5f9fd !important;
}
#residentsTable tbody td {
    font-size: 0.85rem;
    color: #2c3e50;
    padding: 11px 10px;
    vertical-align: middle;
    border-color: #edf2f7;
}

/* Row number pill */
.row-num {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 26px;
    height: 26px;
    background: #eef4fb;
    color: #4a7aa0;
    font-size: 0.75rem;
    font-weight: 700;
    border-radius: 6px;
}

/* -- Badges -- */
.badge-custom {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.73rem;
    font-weight: 700;
    letter-spacing: 0.2px;
}
.badge-male    { background: #e3f0fb; color: #1566a0; }
.badge-female  { background: #fce4ec; color: #b0254f; }
.badge-other   { background: #f3e8fd; color: #7b2db0; }
.badge-voter-yes  { background: #e0f7ea; color: #1a7a40; }
.badge-voter-no   { background: #fdeaea; color: #b02a2a; }
.badge-active     { background: #e0f7ea; color: #1a7a40; }
.badge-inactive   { background: #fff3cd; color: #856404; }
.badge-deceased   { background: #f0f0f0; color: #555; }
.badge-transferred { background: #e8f4fd; color: #0069c0; }

/* -- Full Name with avatar -- */
.resident-name {
    display: flex;
    align-items: center;
    gap: 10px;
}
.resident-avatar {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    letter-spacing: 0.3px;
}
.avatar-m { background: #dbeeff; color: #1a5f96; }
.avatar-f { background: #fce4ec; color: #9c2752; }
.avatar-o { background: #ede7f6; color: #5e35b1; }
.resident-fullname {
    font-weight: 600;
    color: #1a2e42;
    font-size: 0.865rem;
    line-height: 1.2;
}

/* -- Action buttons -- */
.btn-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    border-radius: 7px;
    border: none;
    cursor: pointer;
    transition: all 0.15s;
    font-size: 0.78rem;
}
.btn-edit   { background: #fff3cd; color: #9a6600; }
.btn-edit:hover { background: #ffc107; color: #fff; transform: scale(1.1); }
.btn-delete { background: #fdeaea; color: #b03030; }
.btn-delete:hover { background: #dc3545; color: #fff; transform: scale(1.1); }

/* -- DataTables override -- */
.dataTables_length select,
.dataTables_filter input {
    border: 1.5px solid #d0dbe8;
    border-radius: 6px;
    padding: 5px 10px;
    font-size: 0.83rem;
    color: #2c3e50;
    outline: none;
}
.dataTables_filter input:focus {
    border-color: #1d6fa4;
    box-shadow: 0 0 0 3px rgba(29,111,164,0.12);
}
.dataTables_info {
    font-size: 0.8rem;
    color: #7a8fa6;
    padding-top: 12px;
}
.dataTables_paginate .paginate_button {
    border-radius: 6px !important;
    font-size: 0.82rem !important;
    padding: 4px 10px !important;
}
.dataTables_paginate .paginate_button.current {
    background: #1d6fa4 !important;
    border-color: #1d6fa4 !important;
    color: #fff !important;
}
.dataTables_paginate .paginate_button:hover {
    background: #eef4fb !important;
    border-color: #c4d4e4 !important;
    color: #1d6fa4 !important;
}

/* ============================================================
   MODALS
   ============================================================ */
.modal-content {
    border: none;
    border-radius: 12px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    overflow: hidden;
}
.modal-header {
    background: linear-gradient(135deg, #1a3a5c, #1d6fa4);
    padding: 18px 24px;
    border: none;
}
.modal-title {
    color: #fff;
    font-weight: 700;
    font-size: 1rem;
    display: flex;
    align-items: center;
    gap: 8px;
}
.modal-header .close {
    color: rgba(255,255,255,0.75);
    opacity: 1;
    font-size: 1.3rem;
    text-shadow: none;
}
.modal-header .close:hover { color: #fff; }
.modal-body {
    padding: 24px;
    background: #fafcff;
}
.modal-footer {
    padding: 16px 24px;
    background: #f0f4f8;
    border-top: 1px solid #e0e9f3;
}
.section-label {
    font-size: 0.7rem;
    font-weight: 800;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    color: #7a8fa6;
    margin: 16px 0 10px;
    padding-bottom: 6px;
    border-bottom: 1px solid #e8edf3;
    display: block;
}
.form-group label {
    font-size: 0.78rem;
    font-weight: 700;
    color: #4a6070;
    margin-bottom: 5px;
    display: block;
}
.form-control {
    border: 1.5px solid #d0dbe8;
    border-radius: 7px;
    padding: 9px 12px;
    font-size: 0.85rem;
    color: #2c3e50;
    background: #fff;
    transition: border-color 0.15s, box-shadow 0.15s;
}
.form-control:focus {
    border-color: #1d6fa4;
    box-shadow: 0 0 0 3px rgba(29,111,164,0.12);
    background: #fff;
}
.required-star { color: #e05c5c; margin-left: 2px; }

/* -- Modal footer buttons -- */
.btn-modal-cancel {
    background: #fff;
    color: #5a7080;
    border: 1.5px solid #c4d4e4;
    border-radius: 7px;
    padding: 9px 20px;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s;
}
.btn-modal-cancel:hover { background: #f0f4f8; }
.btn-modal-save {
    background: linear-gradient(135deg, #1d6fa4, #1a5a8a);
    color: #fff;
    border: none;
    border-radius: 7px;
    padding: 9px 24px;
    font-size: 0.85rem;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    box-shadow: 0 3px 10px rgba(29,111,164,0.3);
    transition: all 0.15s;
}
.btn-modal-save:hover {
    background: linear-gradient(135deg, #1a5a8a, #14456e);
    box-shadow: 0 5px 16px rgba(29,111,164,0.4);
    transform: translateY(-1px);
}

/* Empty state */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #8fa8be;
}
.empty-state i { font-size: 2.5rem; margin-bottom: 12px; display: block; }
.empty-state p { font-size: 0.9rem; }
</style>

<div class="residents-wrapper content-wrapper">

    <!-- Hero Header -->
    <div class="residents-hero">
        <div class="hero-breadcrumb">
            <a href="#"><i class="fas fa-home"></i> Home</a>
            <span>/</span>
            <span>Residents</span>
        </div>
        <h1><i class="fas fa-users" style="margin-right:8px;opacity:0.85;"></i>Residents Registry</h1>
        <p class="sub">Manage and monitor all registered residents of the barangay</p>
    </div>

    <!-- Stat Summary Strip -->
    <div class="stat-row">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-users"></i></div>
            <div>
                <div class="stat-label">Total Residents</div>
                <div class="stat-value" id="totalResidents">—</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
            <div>
                <div class="stat-label">Active</div>
                <div class="stat-value" id="statActive">—</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon orange"><i class="fas fa-vote-yea"></i></div>
            <div>
                <div class="stat-label">Registered Voters</div>
                <div class="stat-value" id="statVoters">—</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon red"><i class="fas fa-venus-mars"></i></div>
            <div>
                <div class="stat-label">Female / Male</div>
                <div class="stat-value" id="statGender">—</div>
            </div>
        </div>
    </div>

    <!-- Main Table Card -->
    <section class="content">
        <div class="main-card">

            <div class="main-card-header">
                <h3>
                    <i class="fas fa-list"></i>
                    Residents List
                </h3>
                <div class="header-actions">
                    <button type="button" class="btn-secondary-custom" id="btnRefreshTable">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                    <button type="button" class="btn-primary-custom" data-toggle="modal" data-target="#AddNewModal">
                        <i class="fas fa-plus"></i> Add Resident
                    </button>
                </div>
            </div>

            <div class="main-card-body">
                <table id="residentsTable" class="table table-bordered table-striped table-sm" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th style="display:none;">ID</th>
                            <th>Resident</th>
                            <th>Birthdate</th>
                            <th>Gender</th>
                            <th>Civil Status</th>
                            <th>Voter</th>
                            <th>Voter ID</th>
                            <th>Household</th>
                            <th>Address</th>
                            <th>Barangay</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

        </div>
    </section>

</div>


<!-- ===================================================
     ADD MODAL
=================================================== -->
<div class="modal fade" id="AddNewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="addResidentForm">
            <?= csrf_field() ?>
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title">
                        <i class="fas fa-user-plus"></i> Add New Resident
                    </span>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">

                    <span class="section-label">Personal Information</span>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>First Name <span class="required-star">*</span></label>
                                <input type="text" name="first_name" class="form-control" placeholder="e.g. Juan" required>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Middle Name</label>
                                <input type="text" name="middle_name" class="form-control" placeholder="Optional">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Last Name <span class="required-star">*</span></label>
                                <input type="text" name="last_name" class="form-control" placeholder="e.g. Dela Cruz" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Suffix</label>
                                <input type="text" name="suffix" class="form-control" placeholder="Jr., Sr., III…">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Birthdate <span class="required-star">*</span></label>
                                <input type="date" name="birthdate" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Gender <span class="required-star">*</span></label>
                                <select name="gender" class="form-control" required>
                                    <option value="">— Select —</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Civil Status <span class="required-star">*</span></label>
                                <select name="civil_status" class="form-control" required>
                                    <option value="">— Select —</option>
                                    <option value="Single">Single</option>
                                    <option value="Married">Married</option>
                                    <option value="Widowed">Widowed</option>
                                    <option value="Divorced">Divorced</option>
                                    <option value="Separated">Separated</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <span class="section-label">Voter & Household</span>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Registered Voter?</label>
                                <select name="is_voter" class="form-control">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Voter ID</label>
                                <input type="text" name="voter_id" class="form-control" placeholder="Voter ID No.">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Household ID</label>
                                <input type="number" name="household_id" class="form-control" placeholder="Linked HH">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                    <option value="Deceased">Deceased</option>
                                    <option value="Transferred">Transferred</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <span class="section-label">Address</span>
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label>Address Line 1 <span class="required-star">*</span></label>
                                <input type="text" name="address_line1" class="form-control" placeholder="Purok, Street, Sitio…" required>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Barangay</label>
                                <input type="text" name="barangay" class="form-control" placeholder="Barangay name">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal-cancel" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="btn-modal-save">
                        <i class="fas fa-save"></i> Save Resident
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>


<!-- ===================================================
     EDIT MODAL
=================================================== -->
<div class="modal fade" id="editResidentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="editResidentForm">
            <?= csrf_field() ?>
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title">
                        <i class="fas fa-user-edit"></i> Edit Resident
                    </span>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">

                    <input type="hidden" id="editResidentId" name="id">

                    <span class="section-label">Personal Information</span>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>First Name <span class="required-star">*</span></label>
                                <input type="text" id="editFirstName" name="first_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Middle Name</label>
                                <input type="text" id="editMiddleName" name="middle_name" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Last Name <span class="required-star">*</span></label>
                                <input type="text" id="editLastName" name="last_name" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Suffix</label>
                                <input type="text" id="editSuffix" name="suffix" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Birthdate <span class="required-star">*</span></label>
                                <input type="date" id="editBirthdate" name="birthdate" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Gender <span class="required-star">*</span></label>
                                <select id="editGender" name="gender" class="form-control" required>
                                    <option value="">— Select —</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Civil Status <span class="required-star">*</span></label>
                                <select id="editCivilStatus" name="civil_status" class="form-control" required>
                                    <option value="">— Select —</option>
                                    <option value="Single">Single</option>
                                    <option value="Married">Married</option>
                                    <option value="Widowed">Widowed</option>
                                    <option value="Divorced">Divorced</option>
                                    <option value="Separated">Separated</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <span class="section-label">Voter & Household</span>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Registered Voter?</label>
                                <select id="editIsVoter" name="is_voter" class="form-control">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Voter ID</label>
                                <input type="text" id="editVoterId" name="voter_id" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Household ID</label>
                                <input type="number" id="editHouseholdId" name="household_id" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Status</label>
                                <select id="editStatus" name="status" class="form-control">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                    <option value="Deceased">Deceased</option>
                                    <option value="Transferred">Transferred</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <span class="section-label">Address</span>
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label>Address Line 1 <span class="required-star">*</span></label>
                                <input type="text" id="editAddressLine1" name="address_line1" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Barangay</label>
                                <input type="text" id="editBarangay" name="barangay" class="form-control">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal-cancel" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="btn-modal-save">
                        <i class="fas fa-save"></i> Update Resident
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>


<?= $this->section('scripts') ?>
<script>
    const baseUrl = "<?= base_url() ?>";
</script>
<script src="<?= base_url('js/residents/residents.js') ?>"></script>
<?= $this->endSection() ?>

<?= $this->extend('theme/template') ?>

<?= $this->section('content') ?>

<style>
/* ============================================================
   RESIDENTS PAGE — CLEAN GOVERNMENT BLUE DESIGN (ENHANCED)
   ============================================================ */

.residents-wrapper { padding: 0; background: #f0f4f8; min-height: 100vh; }

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
.residents-hero p.sub { color: rgba(255,255,255,0.72); font-size: 0.875rem; margin: 0; }
.residents-hero .hero-breadcrumb {
    color: rgba(255,255,255,0.55);
    font-size: 0.8rem;
    margin-bottom: 10px;
}
.residents-hero .hero-breadcrumb a { color: rgba(255,255,255,0.75); text-decoration: none; }
.residents-hero .hero-breadcrumb a:hover { color: #fff; }
.residents-hero .hero-breadcrumb span { margin: 0 6px; }
.hero-meta {
    display: flex;
    align-items: center;
    gap: 18px;
    margin-top: 8px;
}
.hero-meta-item {
    display: flex;
    align-items: center;
    gap: 6px;
    color: rgba(255,255,255,0.65);
    font-size: 0.8rem;
}
.hero-meta-item i { font-size: 0.75rem; }

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
    cursor: default;
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
.stat-icon.purple { background: #f3e8fd; color: #7b2db0; }
.stat-label {
    font-size: 0.72rem;
    color: #7a8fa6;
    font-weight: 700;
    letter-spacing: 0.4px;
    text-transform: uppercase;
    margin-bottom: 2px;
}
.stat-value { font-size: 1.4rem; font-weight: 700; color: #1a2e42; line-height: 1; }

/* -- Quick filter pills -- */
.filter-strip {
    background: #fff;
    border-bottom: 1px solid #e0e9f3;
    padding: 10px 24px;
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}
.filter-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.78rem;
    font-weight: 700;
    cursor: pointer;
    border: 1.5px solid transparent;
    transition: all 0.15s;
    background: #f0f4f8;
    color: #4a6070;
}
.filter-pill:hover, .filter-pill.active {
    background: #e3f0fb;
    border-color: #1d6fa4;
    color: #1d6fa4;
}
.filter-pill.all.active { background: #1d6fa4; color: #fff; border-color: #1d6fa4; }
.filter-strip-label { font-size: 0.78rem; font-weight: 700; color: #7a8fa6; margin-right: 4px; white-space: nowrap; }

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
.main-card-header h3 i { color: #1d6fa4; }
.header-actions { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }

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
    padding: 8px 14px;
    font-size: 0.83rem;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.18s;
    text-decoration: none;
}
.btn-secondary-custom:hover {
    background: #f0f6fc;
    border-color: #1d6fa4;
    color: #1d6fa4;
}
.btn-export-csv {
    background: #fff;
    color: #1a7a40;
    border: 1.5px solid #a3d9b8;
    border-radius: 7px;
    padding: 8px 14px;
    font-size: 0.83rem;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.18s;
}
.btn-export-csv:hover { background: #e0f7ea; border-color: #1a7a40; color: #1a7a40; }
.btn-export-print {
    background: #fff;
    color: #7b2db0;
    border: 1.5px solid #d4a8f0;
    border-radius: 7px;
    padding: 8px 14px;
    font-size: 0.83rem;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.18s;
}
.btn-export-print:hover { background: #f3e8fd; border-color: #7b2db0; color: #7b2db0; }

/* -- Table styling -- */
.main-card-body { padding: 0; }
#residentsTable_wrapper { padding: 16px 22px 22px; }
#residentsTable thead tr { background: #f4f8fc; }
#residentsTable thead th {
    font-size: 0.72rem;
    font-weight: 800;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    color: #6b8aaa;
    border-bottom: 2px solid #e0e9f3 !important;
    border-top: none !important;
    padding: 12px 10px;
    white-space: nowrap;
}
#residentsTable tbody tr { transition: background 0.14s; }
#residentsTable tbody tr:hover { background: #f5f9fd !important; }
#residentsTable tbody td {
    font-size: 0.84rem;
    color: #2c3e50;
    padding: 11px 10px;
    vertical-align: middle;
    border-color: #edf2f7;
}

.row-num {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 26px; height: 26px;
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
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.2px;
    white-space: nowrap;
}
.badge-male      { background: #e3f0fb; color: #1566a0; }
.badge-female    { background: #fce4ec; color: #b0254f; }
.badge-other     { background: #f3e8fd; color: #7b2db0; }
.badge-voter-yes { background: #e0f7ea; color: #1a7a40; }
.badge-voter-no  { background: #fdeaea; color: #b02a2a; }
.badge-active    { background: #e0f7ea; color: #1a7a40; }
.badge-inactive  { background: #fff3cd; color: #856404; }
.badge-deceased  { background: #f0f0f0; color: #555; }
.badge-transferred { background: #e8f4fd; color: #0069c0; }

/* -- Resident name with avatar -- */
.resident-name { display: flex; align-items: center; gap: 10px; }
.resident-avatar {
    width: 36px; height: 36px;
    border-radius: 9px;
    font-size: 0.75rem;
    font-weight: 800;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    letter-spacing: 0.3px;
}
.avatar-m { background: #dbeeff; color: #1a5f96; }
.avatar-f { background: #fce4ec; color: #9c2752; }
.avatar-o { background: #ede7f6; color: #5e35b1; }
.resident-fullname { font-weight: 700; color: #1a2e42; font-size: 0.875rem; line-height: 1.2; }
.resident-sub { font-size: 0.71rem; color: #8fa8be; margin-top: 1px; }

/* Address cell */
.address-cell { line-height: 1.45; }
.address-purok { font-weight: 700; color: #1d6fa4; font-size: 0.82rem; }
.address-brgy  { font-size: 0.74rem; color: #7a8fa6; }

/* -- Action buttons -- */
.btn-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 30px; height: 30px;
    border-radius: 7px;
    border: none;
    cursor: pointer;
    transition: all 0.15s;
    font-size: 0.78rem;
}
.btn-view   { background: #e8f4fd; color: #0069c0; }
.btn-view:hover { background: #0069c0; color: #fff; transform: scale(1.1); }
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
.dataTables_info { font-size: 0.8rem; color: #7a8fa6; padding-top: 12px; }
.dataTables_paginate .paginate_button { border-radius: 6px !important; font-size: 0.82rem !important; padding: 4px 10px !important; }
.dataTables_paginate .paginate_button.current { background: #1d6fa4 !important; border-color: #1d6fa4 !important; color: #fff !important; }
.dataTables_paginate .paginate_button:hover { background: #eef4fb !important; border-color: #c4d4e4 !important; color: #1d6fa4 !important; }

/* ============================================================
   MODALS
   ============================================================ */
.modal-content { border: none; border-radius: 12px; box-shadow: 0 20px 60px rgba(0,0,0,0.2); overflow: hidden; }
.modal-header { background: linear-gradient(135deg, #1a3a5c, #1d6fa4); padding: 18px 24px; border: none; }
.modal-title { color: #fff; font-weight: 700; font-size: 1rem; display: flex; align-items: center; gap: 8px; }
.modal-header .close { color: rgba(255,255,255,0.75); opacity: 1; font-size: 1.3rem; text-shadow: none; }
.modal-header .close:hover { color: #fff; }
.modal-body { padding: 24px; background: #fafcff; }
.modal-footer { padding: 16px 24px; background: #f0f4f8; border-top: 1px solid #e0e9f3; }

.section-label {
    font-size: 0.7rem; font-weight: 800; letter-spacing: 0.8px;
    text-transform: uppercase; color: #7a8fa6;
    margin: 16px 0 10px; padding-bottom: 6px;
    border-bottom: 1px solid #e8edf3; display: block;
}
.form-group label { font-size: 0.78rem; font-weight: 700; color: #4a6070; margin-bottom: 5px; display: block; }
.form-control {
    border: 1.5px solid #d0dbe8; border-radius: 7px;
    padding: 9px 12px; font-size: 0.85rem; color: #2c3e50;
    background: #fff; transition: border-color 0.15s, box-shadow 0.15s;
}
.form-control:focus { border-color: #1d6fa4; box-shadow: 0 0 0 3px rgba(29,111,164,0.12); background: #fff; }
.form-control[readonly] { background: #f4f8fc; color: #6b8aaa; cursor: not-allowed; }
.required-star { color: #e05c5c; margin-left: 2px; }

/* locked field hint */
.field-locked { position: relative; }
.field-locked .lock-badge {
    position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
    font-size: 0.7rem; color: #8fa8be; pointer-events: none;
}

.btn-modal-cancel {
    background: #fff; color: #5a7080; border: 1.5px solid #c4d4e4;
    border-radius: 7px; padding: 9px 20px; font-size: 0.85rem; font-weight: 600;
    cursor: pointer; transition: all 0.15s;
}
.btn-modal-cancel:hover { background: #f0f4f8; }
.btn-modal-save {
    background: linear-gradient(135deg, #1d6fa4, #1a5a8a);
    color: #fff; border: none; border-radius: 7px;
    padding: 9px 24px; font-size: 0.85rem; font-weight: 700;
    cursor: pointer; display: inline-flex; align-items: center; gap: 7px;
    box-shadow: 0 3px 10px rgba(29,111,164,0.3); transition: all 0.15s;
}
.btn-modal-save:hover { background: linear-gradient(135deg, #1a5a8a, #14456e); box-shadow: 0 5px 16px rgba(29,111,164,0.4); transform: translateY(-1px); }

/* View Modal */
.view-detail-row {
    display: flex; gap: 8px; margin-bottom: 10px; align-items: flex-start;
}
.view-detail-label { font-size: 0.72rem; font-weight: 800; color: #8fa8be; text-transform: uppercase; letter-spacing: 0.4px; min-width: 110px; padding-top: 1px; }
.view-detail-value { font-size: 0.87rem; color: #1a2e42; font-weight: 600; }

.empty-state { text-align: center; padding: 60px 20px; color: #8fa8be; }
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
        <div class="hero-meta">
            <div class="hero-meta-item"><i class="fas fa-map-marker-alt"></i> Brgy. Isio, Cauayan, Negros Occidental</div>
            <div class="hero-meta-item"><i class="fas fa-calendar-alt"></i> <span id="heroDate"></span></div>
        </div>
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

    <!-- Quick Filter Strip -->
    <div class="filter-strip">
        <span class="filter-strip-label"><i class="fas fa-filter"></i> Quick Filter:</span>
        <span class="filter-pill all active" data-filter="all">All</span>
        <span class="filter-pill" data-filter="Active">Active</span>
        <span class="filter-pill" data-filter="Inactive">Inactive</span>
        <span class="filter-pill" data-filter="Deceased">Deceased</span>
        <span class="filter-pill" data-filter="Transferred">Transferred</span>
        <span style="margin-left:8px;border-left:1px solid #d0dbe8;padding-left:12px;" n>
        <span style="margin-left:8px;border-left:1px solid #d0dbe8;padding-left:12px;" class="filter-strip-label">Voter:</span>
        <span class="filter-pill" data-filter="voter-yes">Yes</span>
        <span class="filter-pill" data-filter="voter-no">No</span>
        <span style="margin-left:8px;border-left:1px solid #d0dbe8;padding-left:12px;" class="filter-strip-label">Purok:</span>
        <span class="filter-pill" data-filter="purok-1">Purok 1</span>
        <span class="filter-pill" data-filter="purok-2">Purok 2</span>
        <span class="filter-pill" data-filter="purok-3">Purok 3</span>
        <span class="filter-pill" data-filter="purok-4">Purok 4</span>
        <span class="filter-pill" data-filter="purok-5">Purok 5</span>
        <span class="filter-pill" data-filter="purok-6">Purok 6</span>
        <span class="filter-pill" data-filter="purok-7a">Purok 7A</span>
        <span class="filter-pill" data-filter="purok-7b">Purok 7B</span>
        <span style="margin-left:8px;border-left:1px solid #d0dbe8;padding-left:12px;" class="filter-strip-label">Gender:</span>
        <span class="filter-pill" data-filter="gender-male">Male</span>
        <span class="filter-pill" data-filter="gender-female">Female</span>
    </div>

    <!-- Main Table Card -->
    <section class="content">
        <div class="main-card">

            <div class="main-card-header">
                <h3>
                    <i class="fas fa-list"></i>
                    Residents List
                    <span style="font-size:0.78rem;font-weight:500;color:#7a8fa6;margin-left:6px;" id="tableFilterLabel"></span>
                </h3>
                <div class="header-actions">
                    <button type="button" class="btn-export-csv" id="btnExportCSV" title="Export to CSV">
                        <i class="fas fa-file-csv"></i> Export CSV
                    </button>
                    <button type="button" class="btn-export-print" id="btnPrint" title="Print list">
                        <i class="fas fa-print"></i> Print
                    </button>
                    <button type="button" class="btn-secondary-custom" id="btnRefreshTable">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                    <button type="button" class="btn-primary-custom" data-toggle="modal" data-target="#AddNewModal">
                        <i class="fas fa-user-plus"></i> Add Resident
                    </button>
                </div>
            </div>

            <div class="main-card-body">
                <table id="residentsTable" class="table table-bordered table-striped table-sm" style="width:100%">
                    <thead>
                        <tr>
                            <th width="40">#</th>
                            <th style="display:none;">ID</th>
                            <th>Resident</th>
                            <th>Birthdate / Age</th>
                            <th>Gender</th>
                            <th>Civil Status</th>
                            <th>Contact Number</th>
                            <th>Voter</th>
                            <th>Voter ID</th>
                            <th>Purok / Address</th>
                            <th>Status</th>
                            <th width="100">Actions</th>
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
                                <select name="suffix" class="form-control">
                                    <option value="">— None —</option>
                                    <option value="Jr.">Jr.</option>
                                    <option value="Sr.">Sr.</option>
                                    <option value="II">II</option>
                                    <option value="III">III</option>
                                    <option value="IV">IV</option>
                                </select>
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
                                <label>Contact Number</label>
                                <input type="text" name="contact_number" class="form-control" placeholder="09XX-XXX-XXXX">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Household ID</label>
                                <input type="number" name="household_id" class="form-control" placeholder="Linked HH">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <span class="section-label">Address</span>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Purok <span class="required-star">*</span></label>
                                <select name="address_line1" class="form-control" required>
                                    <option value="">— Select Purok —</option>
                                    <option value="Purok 1">Purok 1</option>
                                    <option value="Purok 2">Purok 2</option>
                                    <option value="Purok 3">Purok 3</option>
                                    <option value="Purok 4">Purok 4</option>
                                    <option value="Purok 5">Purok 5</option>
                                    <option value="Purok 6">Purok 6</option>
                                    <option value="Purok 7A">Purok 7A</option>
                                    <option value="Purok 7B">Purok 7B</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Barangay</label>
                                <div class="field-locked" style="position:relative;">
                                    <input type="text" name="barangay" class="form-control" value="Isio" readonly>
                                    <span class="lock-badge"><i class="fas fa-lock"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Municipality</label>
                                <div class="field-locked" style="position:relative;">
                                    <input type="text" name="municipality" class="form-control" value="Cauayan, Negros Occidental" readonly>
                                    <span class="lock-badge"><i class="fas fa-lock"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p style="font-size:0.75rem;color:#8fa8be;margin-top:-6px;">
                        <i class="fas fa-info-circle"></i> Barangay and Municipality are fixed to <strong>Isio, Cauayan, Negros Occidental</strong>.
                    </p>

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
                                <select id="editSuffix" name="suffix" class="form-control">
                                    <option value="">— None —</option>
                                    <option value="Jr.">Jr.</option>
                                    <option value="Sr.">Sr.</option>
                                    <option value="II">II</option>
                                    <option value="III">III</option>
                                    <option value="IV">IV</option>
                                </select>
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
                                <label>Contact Number</label>
                                <input type="text" id="editContactNumber" name="contact_number" class="form-control" placeholder="09XX-XXX-XXXX">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Household ID</label>
                                <input type="number" id="editHouseholdId" name="household_id" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Status</label>
                                <select id="editStatus" name="status" class="form-control">
                                    <option value="Active">Active</option>
                                    <option value="Work">Work</option>
                                    <option value="Functional">Functional</option>
                                    <option value="Inactive">Inactive</option>
                                    <option value="Deceased">Deceased</option>
                                    <option value="Transferred">Transferred</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <span class="section-label">Address</span>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Purok <span class="required-star">*</span></label>
                                <select id="editAddressLine1" name="address_line1" class="form-control" required>
                                    <option value="">— Select Purok —</option>
                                    <option value="Purok 1">Purok 1</option>
                                    <option value="Purok 2">Purok 2</option>
                                    <option value="Purok 3">Purok 3</option>
                                    <option value="Purok 4">Purok 4</option>
                                    <option value="Purok 5">Purok 5</option>
                                    <option value="Purok 6">Purok 6</option>
                                    <option value="Purok 7A">Purok 7A</option>
                                    <option value="Purok 7B">Purok 7B</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Barangay</label>
                                <div class="field-locked" style="position:relative;">
                                    <input type="text" id="editBarangay" name="barangay" class="form-control" value="Isio" readonly>
                                    <span class="lock-badge"><i class="fas fa-lock"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Municipality</label>
                                <div class="field-locked" style="position:relative;">
                                    <input type="text" name="municipality" class="form-control" value="Cauayan, Negros Occidental" readonly>
                                    <span class="lock-badge"><i class="fas fa-lock"></i></span>
                                </div>
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


<!-- ===================================================
     VIEW MODAL
=================================================== -->
<div class="modal fade" id="viewResidentModal" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <span class="modal-title">
                    <i class="fas fa-id-card"></i> Resident Profile
                </span>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="viewResidentBody">
                <div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x text-muted"></i></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-modal-cancel" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const baseUrl = "<?= base_url() ?>";
</script>
<script src="<?= base_url('js/residents/residents.js') ?>"></script>
<?= $this->endSection() ?>
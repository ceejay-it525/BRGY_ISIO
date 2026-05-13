<?= $this->extend('theme/template') ?>

<?= $this->section('content') ?>

<style>
/* ============================================================
   BLOTTER PAGE — CLEAN GOVERNMENT BLUE DESIGN
   ============================================================ */

.blotter-wrapper { padding: 0; background: #f0f4f8; min-height: 100vh; }

/* -- Top hero bar -- */
.blotter-hero {
    background: linear-gradient(135deg, #1a3a5c 0%, #1d6fa4 60%, #2196c4 100%);
    padding: 28px 32px 24px;
    position: relative;
    overflow: hidden;
    margin-bottom: 0;
}
.blotter-hero::before {
    content: '';
    position: absolute; top: -60px; right: -60px;
    width: 240px; height: 240px;
    background: rgba(255,255,255,0.05); border-radius: 50%;
}
.blotter-hero::after {
    content: '';
    position: absolute; bottom: -40px; left: 20%;
    width: 160px; height: 160px;
    background: rgba(255,255,255,0.04); border-radius: 50%;
}
.blotter-hero h1 {
    font-family: 'Segoe UI', 'Source Sans Pro', sans-serif;
    font-size: 1.75rem; font-weight: 700; color: #fff;
    margin: 0 0 4px 0; letter-spacing: -0.3px;
}
.blotter-hero p.sub { color: rgba(255,255,255,0.72); font-size: 0.875rem; margin: 0; }
.blotter-hero .hero-breadcrumb {
    color: rgba(255,255,255,0.55); font-size: 0.8rem; margin-bottom: 10px;
}
.blotter-hero .hero-breadcrumb a { color: rgba(255,255,255,0.75); text-decoration: none; }
.blotter-hero .hero-breadcrumb a:hover { color: #fff; }
.blotter-hero .hero-breadcrumb span { margin: 0 6px; }
.hero-meta { display: flex; align-items: center; gap: 18px; margin-top: 8px; }
.hero-meta-item { display: flex; align-items: center; gap: 6px; color: rgba(255,255,255,0.65); font-size: 0.8rem; }
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
    background: #fff; padding: 18px 22px;
    display: flex; align-items: center; gap: 14px;
    transition: background 0.18s; cursor: default;
}
.stat-card:hover { background: #f7fafd; }
.stat-icon {
    width: 46px; height: 46px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.15rem; flex-shrink: 0;
}
.stat-icon.blue   { background: #e3f0fb; color: #1d6fa4; }
.stat-icon.green  { background: #e3f8ee; color: #1a8a4a; }
.stat-icon.orange { background: #fff3e0; color: #e07b00; }
.stat-icon.red    { background: #fdeaea; color: #c0392b; }
.stat-label {
    font-size: 0.72rem; color: #7a8fa6; font-weight: 700;
    letter-spacing: 0.4px; text-transform: uppercase; margin-bottom: 2px;
}
.stat-value { font-size: 1.4rem; font-weight: 700; color: #1a2e42; line-height: 1; }

/* -- Quick filter pills -- */
.filter-strip {
    background: #fff; border-bottom: 1px solid #e0e9f3;
    padding: 10px 24px; display: flex; align-items: center; gap: 8px; flex-wrap: wrap;
}
.filter-pill {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 700;
    cursor: pointer; border: 1.5px solid transparent; transition: all 0.15s;
    background: #f0f4f8; color: #4a6070;
}
.filter-pill:hover, .filter-pill.active {
    background: #e3f0fb; border-color: #1d6fa4; color: #1d6fa4;
}
.filter-pill.all.active { background: #1d6fa4; color: #fff; border-color: #1d6fa4; }
.filter-strip-label { font-size: 0.78rem; font-weight: 700; color: #7a8fa6; margin-right: 4px; white-space: nowrap; }

/* -- Main card -- */
.main-card {
    background: #fff; margin: 20px 24px; border-radius: 10px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.08), 0 4px 18px rgba(0,0,0,0.05); overflow: hidden;
}
.main-card-header {
    padding: 18px 22px; border-bottom: 1px solid #e8edf3;
    display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;
}
.main-card-header h3 {
    font-size: 1rem; font-weight: 700; color: #1a2e42; margin: 0;
    display: flex; align-items: center; gap: 8px;
}
.main-card-header h3 i { color: #1d6fa4; }
.header-actions { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }

/* -- Buttons -- */
.btn-primary-custom {
    background: linear-gradient(135deg, #1d6fa4, #1a5a8a);
    color: #fff; border: none; border-radius: 7px; padding: 9px 18px;
    font-size: 0.85rem; font-weight: 600; cursor: pointer;
    display: inline-flex; align-items: center; gap: 7px;
    transition: all 0.18s; box-shadow: 0 2px 8px rgba(29,111,164,0.3); text-decoration: none;
}
.btn-primary-custom:hover {
    background: linear-gradient(135deg, #1a5a8a, #14456e);
    box-shadow: 0 4px 14px rgba(29,111,164,0.4); transform: translateY(-1px); color: #fff;
}
.btn-secondary-custom {
    background: #fff; color: #3a5a78; border: 1.5px solid #c4d4e4; border-radius: 7px;
    padding: 8px 14px; font-size: 0.83rem; font-weight: 600; cursor: pointer;
    display: inline-flex; align-items: center; gap: 6px; transition: all 0.18s; text-decoration: none;
}
.btn-secondary-custom:hover { background: #f0f6fc; border-color: #1d6fa4; color: #1d6fa4; }

/* -- Table styling -- */
.main-card-body { padding: 0; }
#blotterTable_wrapper { padding: 16px 22px 22px; }
#blotterTable thead tr { background: #f4f8fc; }
#blotterTable thead th {
    font-size: 0.72rem; font-weight: 800; letter-spacing: 0.5px;
    text-transform: uppercase; color: #6b8aaa;
    border-bottom: 2px solid #e0e9f3 !important; border-top: none !important;
    padding: 12px 10px; white-space: nowrap;
}
#blotterTable tbody tr { transition: background 0.14s; }
#blotterTable tbody tr:hover { background: #f5f9fd !important; }
#blotterTable tbody tr.selected { background: #eaf3fb !important; }
#blotterTable tbody td {
    font-size: 0.84rem; color: #2c3e50; padding: 11px 10px;
    vertical-align: middle; border-color: #edf2f7;
}
.row-num {
    display: inline-flex; align-items: center; justify-content: center;
    width: 26px; height: 26px; background: #eef4fb; color: #4a7aa0;
    font-size: 0.75rem; font-weight: 700; border-radius: 6px;
}

/* -- Badges -- */
.badge-custom {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 4px 10px; border-radius: 20px; font-size: 0.72rem;
    font-weight: 700; letter-spacing: 0.2px; white-space: nowrap;
}
.badge-pending   { background: #f8f9fa; color: #495057; border: 1px solid #dee2e6; }
.badge-ongoing   { background: #e3f0fb; color: #1566a0; }
.badge-settled   { background: #e0f7ea; color: #1a7a40; }
.badge-referred  { background: #fff3cd; color: #9a6600; }
.badge-dismissed { background: #fdeaea; color: #b03030; }

/* -- Action buttons -- */
.btn-action {
    display: inline-flex; align-items: center; justify-content: center;
    width: 30px; height: 30px; border-radius: 7px; border: none;
    cursor: pointer; transition: all 0.15s; font-size: 0.78rem;
}
.btn-edit   { background: #fff3cd; color: #9a6600; }
.btn-edit:hover   { background: #ffc107; color: #fff; transform: scale(1.1); }
.btn-delete { background: #fdeaea; color: #b03030; }
.btn-delete:hover { background: #dc3545; color: #fff; transform: scale(1.1); }

/* -- DataTables override -- */
.dataTables_length select,
.dataTables_filter input {
    border: 1.5px solid #d0dbe8; border-radius: 6px;
    padding: 5px 10px; font-size: 0.83rem; color: #2c3e50; outline: none;
}
.dataTables_filter input:focus {
    border-color: #1d6fa4; box-shadow: 0 0 0 3px rgba(29,111,164,0.12);
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
.required-star { color: #e05c5c; margin-left: 2px; }
.is-invalid { border-color: #dc3545 !important; }

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
.btn-modal-save:hover {
    background: linear-gradient(135deg, #1a5a8a, #14456e);
    box-shadow: 0 5px 16px rgba(29,111,164,0.4); transform: translateY(-1px);
}

/* Select2 inside modals */
.select2-container--default .select2-selection--single {
    height: 40px !important; border: 1.5px solid #d0dbe8 !important; border-radius: 7px !important;
    display: flex !important; align-items: center !important;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 38px !important; padding-left: 12px !important; color: #2c3e50 !important;
    font-size: 0.85rem !important;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 38px !important; right: 6px !important;
}
.select2-container--default.select2-container--focus .select2-selection--single {
    border-color: #1d6fa4 !important; box-shadow: 0 0 0 3px rgba(29,111,164,0.12) !important;
}
.select2-dropdown { border: 1.5px solid #d0dbe8 !important; border-radius: 7px !important; }
.select2-container--default .select2-search--dropdown .select2-search__field {
    border: 1.5px solid #d0dbe8 !important; border-radius: 5px !important; padding: 6px 10px !important;
}
.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: #1d6fa4 !important;
}
.select2-container--default .select2-selection--single .select2-selection__placeholder {
    color: #aab4be !important;
}
</style>

<div class="blotter-wrapper content-wrapper">

    <!-- Hero Header -->
    <div class="blotter-hero">
        <div class="hero-breadcrumb">
            <a href="#"><i class="fas fa-home"></i> Home</a>
            <span>/</span>
            <span>Blotter</span>
        </div>
        <h1><i class="fas fa-gavel" style="margin-right:8px;opacity:0.85;"></i>Blotter Records Management</h1>
        <p class="sub">Track and manage all barangay blotter and complaint records</p>
        <div class="hero-meta">
            <div class="hero-meta-item"><i class="fas fa-map-marker-alt"></i> Brgy. Isio, Cauayan, Negros Occidental</div>
            <div class="hero-meta-item"><i class="fas fa-calendar-alt"></i> <span id="heroDate"></span></div>
        </div>
    </div>

    <!-- Stat Summary Strip -->
    <div class="stat-row">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-file-alt"></i></div>
            <div>
                <div class="stat-label">Total Cases</div>
                <div class="stat-value" id="totalCases">—</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon orange"><i class="fas fa-hourglass-half"></i></div>
            <div>
                <div class="stat-label">Ongoing</div>
                <div class="stat-value" id="statOngoing">—</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-handshake"></i></div>
            <div>
                <div class="stat-label">Settled</div>
                <div class="stat-value" id="statSettled">—</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon red"><i class="fas fa-ban"></i></div>
            <div>
                <div class="stat-label">Dismissed</div>
                <div class="stat-value" id="statDismissed">—</div>
            </div>
        </div>
    </div>

    <!-- Quick Filter Strip -->
    <div class="filter-strip">
        <span class="filter-strip-label"><i class="fas fa-filter"></i> Quick Filter:</span>
        <span class="filter-pill all active" data-filter="all">All</span>
        <span class="filter-pill" data-filter="Pending">
            <i class="fas fa-circle" style="font-size:0.5rem;color:#6c757d;"></i> Pending
        </span>
        <span class="filter-pill" data-filter="Ongoing">
            <i class="fas fa-circle" style="font-size:0.5rem;color:#1566a0;"></i> Ongoing
        </span>
        <span class="filter-pill" data-filter="Settled">
            <i class="fas fa-circle" style="font-size:0.5rem;color:#1a7a40;"></i> Settled
        </span>
        <span class="filter-pill" data-filter="Referred">
            <i class="fas fa-circle" style="font-size:0.5rem;color:#9a6600;"></i> Referred
        </span>
        <span class="filter-pill" data-filter="Dismissed">
            <i class="fas fa-circle" style="font-size:0.5rem;color:#b03030;"></i> Dismissed
        </span>
    </div>

    <!-- Main Table Card -->
    <section class="content">
        <div class="main-card">

            <div class="main-card-header">
                <h3>
                    <i class="fas fa-list"></i>
                    Blotter Records
                    <span style="font-size:0.78rem;font-weight:500;color:#7a8fa6;margin-left:6px;" id="tableFilterLabel"></span>
                </h3>
                <div class="header-actions">
                    <button type="button" class="btn-secondary-custom" id="btnRefreshTable">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                    <button type="button" class="btn-primary-custom" data-toggle="modal" data-target="#addBlotterModal">
                        <i class="fas fa-plus-circle"></i> Add Case
                    </button>
                </div>
            </div>

            <div class="main-card-body">
                <table id="blotterTable" class="table table-bordered table-striped table-sm" style="width:100%">
                    <thead>
                        <tr>
                            <th width="40">#</th>
                            <th style="display:none;">ID</th>
                            <th>Case Number</th>
                            <th>Complainant</th>
                            <th>Respondent</th>
                            <th>Accusation</th>
                            <th>Date</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th width="80">Actions</th>
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
<div class="modal fade" id="addBlotterModal" tabindex="-1" role="dialog" aria-labelledby="addBlotterModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <form id="addBlotterForm" autocomplete="off">
            <?= csrf_field() ?>
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title" id="addBlotterModalLabel">
                        <i class="fas fa-plus-circle"></i> Add New Blotter Case
                    </span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">

                    <span class="section-label">Case Information</span>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Case Number</label>
                                <input type="text" name="case_number" class="form-control" placeholder="e.g. BLT-2024-001">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Accusation Type <span class="required-star">*</span></label>
                                <select name="incident_type" class="form-control" required>
                                    <option value="">-- Select Type --</option>
                                    <option value="Physical Assault">Physical Assault</option>
                                    <option value="Verbal Abuse">Verbal Abuse</option>
                                    <option value="Theft">Theft</option>
                                    <option value="Trespassing">Trespassing</option>
                                    <option value="Noise Complaint">Noise Complaint</option>
                                    <option value="Domestic Violence">Domestic Violence</option>
                                    <option value="Property Damage">Property Damage</option>
                                    <option value="Threat">Threat</option>
                                    <option value="Others">Others</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Incident Date <span class="required-star">*</span></label>
                                <input type="date" name="incident_date" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <span class="section-label">Parties Involved</span>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Complainant <span class="required-star">*</span></label>
                                <select name="complainant_resident_id" id="addComplainantId"
                                        class="form-control select2-searchable" required>
                                    <option value="">-- Type to Search Resident --</option>
                                    <?php foreach ($residents as $r):
                                        $rId   = $r['id'] ?? '';
                                        $rName = trim(
                                            ($r['first_name']  ?? '') . ' ' .
                                            ($r['middle_name'] ?? '') . ' ' .
                                            ($r['last_name']   ?? '')
                                        );
                                    ?>
                                    <option value="<?= esc($rId) ?>"
                                            data-search="<?= esc($rName . ' ' . ($r['contact_number'] ?? '')) ?>">
                                        <?= esc($rName) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Respondent <span class="required-star">*</span></label>
                                <select name="respondent_resident_id" id="addRespondentId"
                                        class="form-control select2-searchable" required>
                                    <option value="">-- Type to Search Resident --</option>
                                    <?php foreach ($residents as $r):
                                        $rId   = $r['id'] ?? '';
                                        $rName = trim(
                                            ($r['first_name']  ?? '') . ' ' .
                                            ($r['middle_name'] ?? '') . ' ' .
                                            ($r['last_name']   ?? '')
                                        );
                                    ?>
                                    <option value="<?= esc($rId) ?>"
                                            data-search="<?= esc($rName . ' ' . ($r['contact_number'] ?? '')) ?>">
                                        <?= esc($rName) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <span class="section-label">Case Details</span>
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label>Incident Location</label>
                                <input type="text" name="incident_location" class="form-control"
                                       placeholder="Where did the incident occur?">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Status <span class="required-star">*</span></label>
                                <select name="status" class="form-control" required>
                                    <option value="Pending">Pending</option>
                                    <option value="Ongoing">Ongoing</option>
                                    <option value="Settled">Settled</option>
                                    <option value="Referred">Referred</option>
                                    <option value="Dismissed">Dismissed</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Narrative</label>
                                <textarea name="narrative" class="form-control" rows="3"
                                          placeholder="Brief description of the incident…"></textarea>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Action Taken</label>
                                <textarea name="action_taken" class="form-control" rows="3"
                                          placeholder="What actions were taken?"></textarea>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal-cancel" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="btn-modal-save">
                        <i class="fas fa-save"></i> Save Record
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ===================================================
     EDIT MODAL
=================================================== -->
<div class="modal fade" id="editBlotterModal" tabindex="-1" role="dialog" aria-labelledby="editBlotterModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <form id="editBlotterForm" autocomplete="off">
            <?= csrf_field() ?>
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title" id="editBlotterModalLabel">
                        <i class="fas fa-edit"></i> Edit Blotter Case
                    </span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">

                    <input type="hidden" name="id" id="editBlotterId">

                    <span class="section-label">Case Information</span>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Case Number</label>
                                <input type="text" name="case_number" id="editCaseNumber" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Accusation Type <span class="required-star">*</span></label>
                                <select name="incident_type" id="editIncidentType" class="form-control" required>
                                    <option value="">-- Select Type --</option>
                                    <option value="Physical Assault">Physical Assault</option>
                                    <option value="Verbal Abuse">Verbal Abuse</option>
                                    <option value="Theft">Theft</option>
                                    <option value="Trespassing">Trespassing</option>
                                    <option value="Noise Complaint">Noise Complaint</option>
                                    <option value="Domestic Violence">Domestic Violence</option>
                                    <option value="Property Damage">Property Damage</option>
                                    <option value="Threat">Threat</option>
                                    <option value="Others">Others</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Incident Date <span class="required-star">*</span></label>
                                <input type="date" name="incident_date" id="editIncidentDate" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <span class="section-label">Parties Involved</span>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Complainant <span class="required-star">*</span></label>
                                <select name="complainant_resident_id" id="editComplainantId"
                                        class="form-control select2-searchable" required>
                                    <option value="">-- Type to Search Resident --</option>
                                    <?php foreach ($residents as $r):
                                        $rId   = $r['id'] ?? '';
                                        $rName = trim(
                                            ($r['first_name']  ?? '') . ' ' .
                                            ($r['middle_name'] ?? '') . ' ' .
                                            ($r['last_name']   ?? '')
                                        );
                                    ?>
                                    <option value="<?= esc($rId) ?>"
                                            data-search="<?= esc($rName . ' ' . ($r['contact_number'] ?? '')) ?>">
                                        <?= esc($rName) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Respondent <span class="required-star">*</span></label>
                                <select name="respondent_resident_id" id="editRespondentId"
                                        class="form-control select2-searchable" required>
                                    <option value="">-- Type to Search Resident --</option>
                                    <?php foreach ($residents as $r):
                                        $rId   = $r['id'] ?? '';
                                        $rName = trim(
                                            ($r['first_name']  ?? '') . ' ' .
                                            ($r['middle_name'] ?? '') . ' ' .
                                            ($r['last_name']   ?? '')
                                        );
                                    ?>
                                    <option value="<?= esc($rId) ?>"
                                            data-search="<?= esc($rName . ' ' . ($r['contact_number'] ?? '')) ?>">
                                        <?= esc($rName) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <span class="section-label">Case Details</span>
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label>Incident Location</label>
                                <input type="text" name="incident_location" id="editIncidentLocation" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Status <span class="required-star">*</span></label>
                                <select name="status" id="editStatus" class="form-control" required>
                                    <option value="Pending">Pending</option>
                                    <option value="Ongoing">Ongoing</option>
                                    <option value="Settled">Settled</option>
                                    <option value="Referred">Referred</option>
                                    <option value="Dismissed">Dismissed</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Narrative</label>
                                <textarea name="narrative" id="editNarrative" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Action Taken</label>
                                <textarea name="action_taken" id="editActionTaken" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal-cancel" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="btn-modal-save">
                        <i class="fas fa-save"></i> Update Record
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
<script src="<?= base_url('js/blotter/blotter.js') ?>"></script>
<?= $this->endSection() ?>

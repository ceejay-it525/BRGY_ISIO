<?= $this->extend('theme/template') ?>

<?= $this->section('content') ?>
<div class="content-wrapper">

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2 align-items-center">
        <div class="col-sm-6">
          <h1 class="m-0 page-title"><i class="fas fa-home mr-2"></i>Households</h1>
          <small class="text-muted"><i class="fas fa-map-marker-alt mr-1"></i>Barangay Isio, Cauayan, Negros Occidental</small>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right mb-0">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>">Home</a></li>
            <li class="breadcrumb-item active">Households</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

      <!-- ── Stats Cards ──────────────────────────────────────── -->
      <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
          <div class="stat-card stat-blue">
            <div class="stat-icon"><i class="fas fa-home"></i></div>
            <div class="stat-body">
              <div class="stat-label">Total Households</div>
              <div class="stat-value" id="statTotal"><div class="stat-spinner"></div></div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
          <div class="stat-card stat-green">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <div class="stat-body">
              <div class="stat-label">Active Households</div>
              <div class="stat-value" id="statActive"><div class="stat-spinner"></div></div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
          <div class="stat-card stat-orange">
            <div class="stat-icon"><i class="fas fa-calendar-plus"></i></div>
            <div class="stat-body">
              <div class="stat-label">New This Month</div>
              <div class="stat-value" id="statNew"><div class="stat-spinner"></div></div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
          <div class="stat-card stat-red stat-clickable" id="cardAssistance" title="View assistance list">
            <div class="stat-icon"><i class="fas fa-hands-helping"></i></div>
            <div class="stat-body">
              <div class="stat-label">
                Assistance Priority
                <i class="fas fa-external-link-alt ml-1" style="font-size:.65rem;opacity:.7"></i>
              </div>
              <div class="stat-value" id="statPriority"><div class="stat-spinner"></div></div>
              <div class="stat-hint">Click to manage assistance list</div>
            </div>
          </div>
        </div>
      </div>

      <!-- ── Main Table ─────────────────────────────────────────── -->
      <div class="row">
        <div class="col-12">
          <div class="card card-outline card-primary shadow-sm rounded-lg">
            <div class="card-header d-flex align-items-center py-3">
              <h3 class="card-title mb-0 font-weight-bold">
                <i class="fas fa-list mr-2 text-primary"></i>List of Households
              </h3>
              <div class="ml-auto d-flex align-items-center gap-2">
                <span class="action-legend mr-3 d-none d-md-flex align-items-center gap-3">
                  <span><span class="legend-dot legend-assist"></span><small class="text-muted">Assistance</small></span>
                  <span><span class="legend-dot legend-edit"></span><small class="text-muted">Edit</small></span>
                  <span><span class="legend-dot legend-delete"></span><small class="text-muted">Delete</small></span>
                </span>
                <button type="button" class="btn btn-primary btn-sm px-3 rounded-pill" id="btnAddHousehold">
                  <i class="fas fa-plus-circle mr-1"></i> Add Household
                </button>
              </div>
            </div>
            <div class="card-body p-0">
              <div class="table-responsive p-3">
                <table id="householdsTable" class="table table-bordered table-hover table-sm w-100">
                  <thead class="thead-dark">
                    <tr>
                      <th width="4%"  class="text-center">No.</th>
                      <th width="20%">Head of Household</th>
                      <th>Address</th>
                      <th width="9%"  class="text-center">Purok</th>
                      <th width="9%"  class="text-center">Members</th>
                      <th width="10%" class="text-center">Status</th>
                      <th width="15%" class="text-center">Actions</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>

    <!-- ============================================================ -->
    <!-- ASSISTANCE PRIORITY MODAL                                     -->
    <!-- ============================================================ -->
    <div class="modal fade" id="assistancePriorityModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-lg">
          <div class="modal-header bg-danger text-white rounded-top">
            <div>
              <h5 class="modal-title font-weight-bold mb-0">
                <i class="fas fa-hands-helping mr-2"></i>Households Needing Assistance
              </h5>
              <small class="opacity-75">Check households that need assistance — use the heart button in the table too</small>
            </div>
            <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
          </div>
          <div class="modal-body p-0">
            <div class="assist-summary px-4 py-2 d-flex align-items-center justify-content-between flex-wrap gap-2">
              <span id="assistSummaryText" class="text-muted small"><i class="fas fa-spinner fa-spin mr-1"></i>Loading…</span>
              <div class="d-flex align-items-center gap-2">
                <span id="assistCheckedCount"></span>
                <input type="text" id="assistSearch" class="form-control form-control-sm" placeholder="Search households…" style="width:210px;" />
              </div>
            </div>
            <div class="assist-tip px-4 py-2">
              <i class="fas fa-lightbulb text-warning mr-1"></i>
              <small class="text-muted">Tip: You can also click the <strong><i class="fas fa-hands-helping"></i></strong> button next to any household row to toggle assistance directly.</small>
            </div>
            <div id="assistListWrapper" class="px-3 py-2"></div>
          </div>
          <div class="modal-footer bg-light rounded-bottom py-2 d-flex align-items-center justify-content-between">
            <div>
              <button type="button" class="btn btn-success btn-sm mr-2" id="btnSaveAssistance">
                <i class="fas fa-save mr-1"></i>Save List
              </button>
              <button type="button" class="btn btn-outline-secondary btn-sm" id="btnClearAssistance">
                <i class="fas fa-times mr-1"></i>Clear All
              </button>
            </div>
            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
              <i class="fas fa-times mr-1"></i>Close
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- ============================================================ -->
    <!-- HOUSEHOLD DETAIL MODAL                                        -->
    <!-- ============================================================ -->
    <div class="modal fade" id="householdDetailModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-lg">
          <div class="modal-header bg-info text-white rounded-top">
            <h5 class="modal-title font-weight-bold"><i class="fas fa-id-card mr-2"></i>Household Details</h5>
            <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
          </div>
          <div class="modal-body p-0" id="householdDetailBody"></div>
          <div class="modal-footer bg-light rounded-bottom py-2">
            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i class="fas fa-times mr-1"></i>Close</button>
            <button type="button" class="btn btn-warning btn-sm text-dark" id="detailEditBtn"><i class="fas fa-edit mr-1"></i>Edit This Household</button>
          </div>
        </div>
      </div>
    </div>

    <!-- ============================================================ -->
    <!-- ADD MODAL                                                     -->
    <!-- ============================================================ -->
    <div class="modal fade" id="addHouseholdModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-lg">
          <form id="addHouseholdForm" autocomplete="off">
            <?= csrf_field() ?>
            <div class="modal-header bg-primary text-white rounded-top">
              <h5 class="modal-title font-weight-bold"><i class="fas fa-plus-circle mr-2"></i>Add New Household</h5>
              <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body px-4 py-3">
              <div id="addAlertBox"></div>
              <div class="location-banner mb-3"><i class="fas fa-map-marker-alt mr-2"></i><strong>Barangay Isio</strong>, Cauayan, Negros Occidental</div>
              <div class="section-heading"><i class="fas fa-user"></i> Household Head</div>
              <div class="row mb-2">
                <div class="col-md-8">
                  <div class="form-group mb-2">
                    <label class="field-label">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="head_name" class="form-control form-control-sm" placeholder="e.g. Juan Dela Cruz" required />
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group mb-2">
                    <label class="field-label">Total Members</label>
                    <input type="number" name="total_members" class="form-control form-control-sm" value="1" min="1" max="99" />
                  </div>
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-5">
                  <div class="form-group mb-0">
                    <label class="field-label">Status</label>
                    <select name="status" class="form-control form-control-sm">
                      <option value="Active">Active</option>
                      <option value="Relocated">Relocated</option>
                      <option value="Archived">Archived</option>
                    </select>
                  </div>
                </div>
              </div>
              <hr class="my-3" />
              <div class="section-heading"><i class="fas fa-map-marker-alt"></i> Address</div>
              <div class="row mb-2">
                <div class="col-md-8">
                  <div class="form-group mb-2">
                    <label class="field-label">Street / House No. <span class="text-danger">*</span></label>
                    <input type="text" name="address_line1" class="form-control form-control-sm" placeholder="e.g. Blk 2 Lot 5, Maharlika St." required />
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group mb-2">
                    <label class="field-label">Purok <span class="text-danger">*</span></label>
                    <select name="purok" class="form-control form-control-sm" required>
                      <option value="">-- Select --</option>
                      <option value="1">Purok 1</option><option value="2">Purok 2</option>
                      <option value="3">Purok 3</option><option value="4">Purok 4</option>
                      <option value="5">Purok 5</option><option value="6">Purok 6</option>
                      <option value="7A">Purok 7A</option><option value="7B">Purok 7B</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4"><div class="form-group mb-0"><label class="field-label">Barangay</label><input type="text" class="form-control form-control-sm bg-light" value="Isio" readonly /></div></div>
                <div class="col-md-4"><div class="form-group mb-0"><label class="field-label">Municipality</label><input type="text" class="form-control form-control-sm bg-light" value="Cauayan" readonly /></div></div>
                <div class="col-md-4"><div class="form-group mb-0"><label class="field-label">Province</label><input type="text" class="form-control form-control-sm bg-light" value="Negros Occidental" readonly /></div></div>
              </div>
            </div>
            <div class="modal-footer bg-light rounded-bottom">
              <button type="button" class="btn btn-secondary btn-sm px-3" data-dismiss="modal"><i class="fas fa-times mr-1"></i>Cancel</button>
              <button type="submit" class="btn btn-primary btn-sm px-4" id="addSaveBtn"><i class="fas fa-save mr-1"></i>Save Household</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- ============================================================ -->
    <!-- EDIT MODAL                                                    -->
    <!-- ============================================================ -->
    <div class="modal fade" id="editHouseholdModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-lg">
          <form id="editHouseholdForm" autocomplete="off">
            <?= csrf_field() ?>
            <input type="hidden" id="editHouseholdId" name="id" />
            <div class="modal-header bg-warning rounded-top">
              <h5 class="modal-title font-weight-bold text-dark"><i class="fas fa-edit mr-2"></i>Edit Household</h5>
              <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body px-4 py-3">
              <div id="editAlertBox"></div>
              <div class="location-banner mb-3"><i class="fas fa-map-marker-alt mr-2"></i><strong>Barangay Isio</strong>, Cauayan, Negros Occidental</div>
              <div class="section-heading"><i class="fas fa-user"></i> Household Head</div>
              <div class="row mb-2">
                <div class="col-md-8">
                  <div class="form-group mb-2">
                    <label class="field-label">Full Name <span class="text-danger">*</span></label>
                    <input type="text" id="editHeadName" name="head_name" class="form-control form-control-sm" required />
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group mb-2">
                    <label class="field-label">Total Members</label>
                    <input type="number" id="editTotalMembers" name="total_members" class="form-control form-control-sm" min="1" max="99" />
                  </div>
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-5">
                  <div class="form-group mb-0">
                    <label class="field-label">Status</label>
                    <select id="editStatus" name="status" class="form-control form-control-sm">
                      <option value="Active">Active</option>
                      <option value="Relocated">Relocated</option>
                      <option value="Archived">Archived</option>
                    </select>
                  </div>
                </div>
              </div>
              <hr class="my-3" />
              <div class="section-heading"><i class="fas fa-map-marker-alt"></i> Address</div>
              <div class="row mb-2">
                <div class="col-md-8">
                  <div class="form-group mb-2">
                    <label class="field-label">Street / House No. <span class="text-danger">*</span></label>
                    <input type="text" id="editAddressLine1" name="address_line1" class="form-control form-control-sm" required />
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group mb-2">
                    <label class="field-label">Purok <span class="text-danger">*</span></label>
                    <select id="editPurok" name="purok" class="form-control form-control-sm" required>
                      <option value="">-- Select --</option>
                      <option value="1">Purok 1</option><option value="2">Purok 2</option>
                      <option value="3">Purok 3</option><option value="4">Purok 4</option>
                      <option value="5">Purok 5</option><option value="6">Purok 6</option>
                      <option value="7A">Purok 7A</option><option value="7B">Purok 7B</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4"><div class="form-group mb-0"><label class="field-label">Barangay</label><input type="text" class="form-control form-control-sm bg-light" value="Isio" readonly /></div></div>
                <div class="col-md-4"><div class="form-group mb-0"><label class="field-label">Municipality</label><input type="text" class="form-control form-control-sm bg-light" value="Cauayan" readonly /></div></div>
                <div class="col-md-4"><div class="form-group mb-0"><label class="field-label">Province</label><input type="text" class="form-control form-control-sm bg-light" value="Negros Occidental" readonly /></div></div>
              </div>
            </div>
            <div class="modal-footer bg-light rounded-bottom">
              <button type="button" class="btn btn-secondary btn-sm px-3" data-dismiss="modal"><i class="fas fa-times mr-1"></i>Cancel</button>
              <button type="submit" class="btn btn-warning btn-sm px-4 text-dark" id="editSaveBtn"><i class="fas fa-save mr-1"></i>Update Household</button>
            </div>
          </form>
        </div>
      </div>
    </div>

  </section>
</div>

<!-- ============================================================ -->
<!-- STYLES                                                        -->
<!-- ============================================================ -->
<style>
/* ── Page Title ──────────────────────────── */
.page-title { font-size:1.35rem; font-weight:700; color:#343a40; }

/* ── Stat Cards ──────────────────────────── */
.stat-card {
  display:flex; align-items:center; gap:1rem;
  border-radius:14px; padding:1.15rem 1.4rem; color:#fff;
  box-shadow:0 4px 18px rgba(0,0,0,.13);
  transition:transform .22s ease,box-shadow .22s ease;
  position:relative; overflow:hidden; min-height:90px;
}
.stat-card:hover { transform:translateY(-5px); box-shadow:0 10px 28px rgba(0,0,0,.2); }
.stat-card::before {
  content:''; position:absolute; right:-24px; top:-24px;
  width:100px; height:100px; border-radius:50%;
  background:rgba(255,255,255,.1); pointer-events:none;
}
.stat-blue   { background:linear-gradient(135deg,#1a73e8 0%,#0d4fd4 100%); }
.stat-green  { background:linear-gradient(135deg,#28a745 0%,#1a7a32 100%); }
.stat-orange { background:linear-gradient(135deg,#fd7e14 0%,#d95f00 100%); }
.stat-red    { background:linear-gradient(135deg,#dc3545 0%,#a71d2a 100%); }
.stat-clickable { cursor:pointer; border:2px solid transparent; }
.stat-clickable:hover { transform:translateY(-6px); box-shadow:0 12px 32px rgba(220,53,69,.35); border-color:rgba(255,255,255,.4); }
.stat-clickable:active { transform:translateY(-2px); }
.stat-hint { font-size:.68rem; opacity:.75; margin-top:2px; letter-spacing:.03em; }
.stat-icon  { font-size:2.2rem; opacity:.85; flex-shrink:0; }
.stat-body  { flex:1; }
.stat-label { font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.07em; opacity:.88; margin-bottom:2px; }
.stat-value { font-size:2rem; font-weight:800; line-height:1.1; }
.stat-spinner { width:20px; height:20px; border:2px solid rgba(255,255,255,.4); border-top-color:#fff; border-radius:50%; animation:spin .7s linear infinite; display:inline-block; }
@keyframes spin { to { transform:rotate(360deg); } }

/* ── Action Group (3 buttons) ────────────── */
.action-group {
  display:inline-flex; align-items:center; gap:4px;
  background:#f8f9fa; border:1px solid #e9ecef;
  border-radius:10px; padding:3px 5px;
  box-shadow:0 1px 3px rgba(0,0,0,.06);
}
.btn-act {
  width:30px; height:30px; border:none; border-radius:7px;
  display:inline-flex; align-items:center; justify-content:center;
  font-size:.78rem; cursor:pointer; padding:0;
  transition:all .18s cubic-bezier(.34,1.56,.64,1);
  position:relative; overflow:hidden;
}
.btn-act:hover { transform:translateY(-2px) scale(1.12); }
.btn-act:active { transform:scale(.94); }
.btn-act::after {
  content:''; position:absolute; inset:0; border-radius:inherit;
  background:rgba(255,255,255,0);
  transition:background .15s;
}
.btn-act:hover::after { background:rgba(255,255,255,.15); }

/* Assistance — off state */
.btn-act-assist-off {
  background:linear-gradient(135deg,#6f42c1,#4e2d8f);
  color:#fff;
  box-shadow:0 2px 6px rgba(111,66,193,.35);
}
.btn-act-assist-off:hover { box-shadow:0 5px 14px rgba(111,66,193,.55); }

/* Assistance — on state (marked) */
.btn-act-assist-on {
  background:linear-gradient(135deg,#dc3545,#a71d2a);
  color:#fff;
  box-shadow:0 2px 6px rgba(220,53,69,.4);
  animation:assist-pulse 2s ease-in-out infinite;
}
@keyframes assist-pulse {
  0%,100% { box-shadow:0 2px 6px rgba(220,53,69,.4); }
  50%      { box-shadow:0 4px 14px rgba(220,53,69,.7); }
}

/* Edit */
.btn-act-edit {
  background:linear-gradient(135deg,#ffc107,#e0a800);
  color:#212529;
  box-shadow:0 2px 6px rgba(255,193,7,.35);
}
.btn-act-edit:hover { box-shadow:0 5px 14px rgba(255,193,7,.55); }

/* Delete */
.btn-act-delete {
  background:linear-gradient(135deg,#dc3545,#a71d2a);
  color:#fff;
  box-shadow:0 2px 6px rgba(220,53,69,.3);
}
.btn-act-delete:hover { box-shadow:0 5px 14px rgba(220,53,69,.5); }

/* Divider between buttons */
.action-group .btn-act:not(:last-child)::before {
  display:none; /* no pseudo-divider needed, gap handles it */
}

/* Legend dots */
.legend-dot { width:10px; height:10px; border-radius:3px; display:inline-block; margin-right:4px; vertical-align:middle; }
.legend-assist { background:linear-gradient(135deg,#6f42c1,#4e2d8f); }
.legend-edit   { background:linear-gradient(135deg,#ffc107,#e0a800); }
.legend-delete { background:linear-gradient(135deg,#dc3545,#a71d2a); }

/* ── Head link ───────────────────────────── */
.head-link {
  color:#0d4fd4; font-weight:700; cursor:pointer;
  text-decoration:none; border-bottom:1px dashed #a0b8e8;
  transition:color .15s,border-color .15s;
}
.head-link:hover { color:#0a3da0; border-bottom-style:solid; text-decoration:none; }

/* ── Badges ──────────────────────────────── */
.badge-purok   { background:#e0ecff; color:#0d4fd4; font-weight:700; border:1px solid #b8d0f8; border-radius:20px; padding:3px 11px; font-size:.77rem; }
.badge-members { background:#e8f5ff; color:#0c63a3; font-weight:700; border-radius:20px; padding:3px 11px; font-size:.77rem; }
.status-badge  { border-radius:20px; padding:3px 11px; font-size:.77rem; font-weight:700; display:inline-flex; align-items:center; gap:4px; }
.badge-active    { background:#d4edda; color:#155724; border:1px solid #c3e6cb; }
.badge-relocated { background:#fff3cd; color:#856404; border:1px solid #ffeeba; }
.badge-archived  { background:#e2e3e5; color:#383d41; border:1px solid #d6d8db; }

/* ── Table rows ──────────────────────────── */
.card.rounded-lg { border-radius:12px !important; }
#householdsTable td { vertical-align:middle !important; }
#householdsTable tbody tr:hover { background:rgba(13,79,212,.03) !important; }
.actions-cell { white-space:nowrap; }

/* ── Empty State ─────────────────────────── */
.empty-state { text-align:center; padding:50px 20px; color:#adb5bd; }
.empty-state i { font-size:3.5rem; margin-bottom:15px; opacity:.45; }
.empty-state h5 { font-size:1rem; font-weight:600; color:#6c757d; }

/* ── Assist Modal ────────────────────────── */
.assist-summary { background:#fff5f5; border-bottom:1px solid #fecdd3; min-height:46px; }
.assist-tip { background:#fffbeb; border-bottom:1px solid #fde68a; padding:6px 24px; }
.assist-card {
  display:flex; align-items:stretch;
  border:1px solid #e9ecef; border-radius:10px;
  margin-bottom:10px; overflow:hidden;
  transition:box-shadow .15s,transform .15s,border-color .2s;
  background:#fff; cursor:pointer;
}
.assist-card:hover { box-shadow:0 4px 14px rgba(0,0,0,.1); transform:translateY(-1px); }
.assist-card.is-checked { border-color:#dc3545; background:#fff5f5; box-shadow:0 2px 10px rgba(220,53,69,.15); }
.assist-card-check { background:#f8f9fa; min-width:52px; display:flex; align-items:center; justify-content:center; border-right:1px solid #e9ecef; padding:10px; transition:background .15s; }
.assist-card.is-checked .assist-card-check { background:linear-gradient(180deg,#dc3545,#a71d2a); }
.assist-card-check input[type="checkbox"] { width:18px; height:18px; cursor:pointer; accent-color:#dc3545; }
.assist-card-rank { background:#f1f3f5; color:#6c757d; font-weight:800; font-size:1rem; min-width:40px; display:flex; align-items:center; justify-content:center; border-right:1px solid #e9ecef; }
.assist-card.is-checked .assist-card-rank { background:rgba(220,53,69,.08); color:#dc3545; }
.assist-card-body { flex:1; padding:10px 14px; }
.assist-card-name { font-weight:700; font-size:.95rem; color:#212529; margin-bottom:2px; }
.assist-card.is-checked .assist-card-name { color:#dc3545; }
.assist-card-addr { font-size:.8rem; color:#6c757d; margin-bottom:6px; }
.assist-card-meta { display:flex; flex-wrap:wrap; gap:6px; align-items:center; }
.assist-needs-badge { background:#fff3f3; color:#dc3545; border:1px solid #f5c6cb; border-radius:20px; padding:2px 10px; font-size:.75rem; font-weight:700; display:inline-flex; align-items:center; gap:4px; }
.assist-empty { text-align:center; padding:50px 20px; color:#adb5bd; }
.assist-empty i { font-size:3rem; margin-bottom:12px; opacity:.4; }

/* ── Detail Modal ────────────────────────── */
.detail-hero { background:linear-gradient(135deg,#17a2b8,#117a8b); color:#fff; padding:20px 24px; text-align:center; }
.detail-hero-avatar { width:64px; height:64px; border-radius:50%; background:rgba(255,255,255,.2); display:flex; align-items:center; justify-content:center; font-size:1.8rem; margin:0 auto 10px; border:3px solid rgba(255,255,255,.4); }
.detail-hero-name { font-size:1.1rem; font-weight:700; margin-bottom:4px; }
.detail-rows { padding:0 20px 16px; }
.detail-row { display:flex; align-items:flex-start; padding:9px 0; border-bottom:1px solid #f1f3f5; }
.detail-row:last-child { border-bottom:none; }
.detail-row-icon { width:30px; min-width:30px; text-align:center; color:#17a2b8; font-size:.85rem; margin-top:2px; }
.detail-row-label { font-size:.73rem; font-weight:700; color:#adb5bd; text-transform:uppercase; letter-spacing:.05em; }
.detail-row-value { font-size:.88rem; color:#343a40; font-weight:500; }

/* ── Location Banner ─────────────────────── */
.location-banner { background:#e8f4ff; border:1px solid #b8d9f8; border-radius:8px; padding:8px 14px; font-size:.85rem; color:#0d4fd4; }

/* ── Section Heading ─────────────────────── */
.section-heading { font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#6c757d; border-left:3px solid #007bff; padding-left:8px; margin-bottom:10px; }
.section-heading i { margin-right:5px; color:#007bff; }
.field-label { font-size:.8rem; font-weight:600; color:#495057; margin-bottom:3px; display:block; }
.rounded-pill { border-radius:50rem !important; }

/* ── Dark mode ───────────────────────────── */
body.dark-mode .action-group { background:#2d3238; border-color:#444; }
body.dark-mode .assist-summary { background:#2a0a0f; border-color:#5c1e2b; }
body.dark-mode .assist-tip { background:#2a2200; border-color:#6b5800; }
body.dark-mode .assist-card { background:#1e2227; border-color:#343a40; }
body.dark-mode .assist-card.is-checked { background:#3a0a10; border-color:#dc3545; }
body.dark-mode .assist-card-check { background:#2d3238; border-color:#444; }
body.dark-mode .assist-card-rank { background:#2a2f35; color:#adb5bd; border-color:#444; }
body.dark-mode .assist-card-name { color:#f1f3f5; }
body.dark-mode .assist-card.is-checked .assist-card-name { color:#ff6b6b; }
body.dark-mode .assist-card-addr { color:#9ca3af; }
body.dark-mode .detail-row { border-color:#2d3748; }
body.dark-mode .detail-row-value { color:#e2e8f0; }
body.dark-mode .location-banner { background:#0d2a5e; color:#90baff; border-color:#1a4a9e; }
body.dark-mode .field-label { color:#ced4da; }
body.dark-mode .section-heading { color:#adb5bd; }
body.dark-mode .badge-purok { background:#0d2a5e; color:#90baff; border-color:#1a4a9e; }
body.dark-mode .badge-members { background:#0c2d4a; color:#63b3ed; }
body.dark-mode .badge-active { background:#1a3c2a; color:#75c896; border-color:#2a6040; }
body.dark-mode .badge-relocated { background:#3d2d00; color:#f5c542; border-color:#7a5a00; }
body.dark-mode .badge-archived { background:#2d2d2d; color:#aaa; border-color:#444; }
body.dark-mode .head-link { color:#63b3ed; border-bottom-color:#3b6ea5; }
body.dark-mode #householdsTable tbody tr:hover { background:rgba(99,179,237,.05) !important; }
</style>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>const baseUrl = "<?= base_url() ?>";</script>
<script src="<?= base_url('js/households/households.js') ?>"></script>
<?= $this->endSection() ?>
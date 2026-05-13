<?= $this->extend('theme/template') ?>

<?= $this->section('content') ?>
<div class="content-wrapper">

  <!-- ── Content Header ──────────────────────────────────────────── -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark"><i class="fas fa-file-alt mr-2"></i>Barangay Clearances</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Clearances</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <!-- ── Main Content ────────────────────────────────────────────── -->
  <section class="content">
    <div class="container-fluid">

      <!-- Workflow Nav Tabs -->
      <div class="row mb-3">
        <div class="col-12">
          <div class="btn-group btn-group-lg w-100" role="group">
            <a href="<?= base_url('clearances') ?>"
               class="btn btn-outline-secondary <?= uri_string() === 'clearances' ? 'active' : '' ?>">
              <i class="fas fa-list mr-1"></i> All
            </a>
            <a href="<?= base_url('clearances/pending') ?>"
               class="btn btn-secondary <?= uri_string() === 'clearances/pending' ? 'active' : '' ?>">
              <i class="fas fa-clock mr-1"></i> Pending
              <span class="badge badge-light ml-1" id="pendingCount">0</span>
            </a>
            <a href="<?= base_url('clearances/approved') ?>"
               class="btn btn-info <?= uri_string() === 'clearances/approved' ? 'active' : '' ?>">
              <i class="fas fa-check-circle mr-1"></i> Approved
              <span class="badge badge-light ml-1" id="approvedCount">0</span>
            </a>
            <a href="<?= base_url('clearances/released') ?>"
               class="btn btn-success <?= uri_string() === 'clearances/released' ? 'active' : '' ?>">
              <i class="fas fa-handshake mr-1"></i> Released
              <span class="badge badge-light ml-1" id="releasedCount">0</span>
            </a>
            <a href="<?= base_url('clearances/rejected') ?>"
               class="btn btn-danger <?= uri_string() === 'clearances/rejected' ? 'active' : '' ?>">
              <i class="fas fa-ban mr-1"></i> Rejected
              <span class="badge badge-light ml-1" id="rejectedCount">0</span>
            </a>
            <a href="<?= base_url('clearances/expired') ?>"
               class="btn btn-secondary <?= uri_string() === 'clearances/expired' ? 'active' : '' ?>">
              <i class="fas fa-exclamation-triangle mr-1"></i> Expired
              <span class="badge badge-light ml-1" id="expiredCount">0</span>
            </a>
          </div>
        </div>
      </div>

      <!-- KPI Cards -->
      <div class="row">
        <div class="col-lg-3 col-6">
          <div class="small-box bg-info">
            <div class="inner"><h3 id="totalClearances">0</h3><p>Total Clearances</p></div>
            <div class="icon"><i class="fas fa-file-alt"></i></div>
            <a href="<?= base_url('clearances') ?>" class="small-box-footer">View All <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-success">
            <div class="inner"><h3 id="releasedClearances">0</h3><p>Released</p></div>
            <div class="icon"><i class="fas fa-handshake"></i></div>
            <a href="<?= base_url('clearances/released') ?>" class="small-box-footer">View <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-warning">
            <div class="inner"><h3 id="pendingClearances">0</h3><p>Pending</p></div>
            <div class="icon"><i class="fas fa-clock"></i></div>
            <a href="<?= base_url('clearances/pending') ?>" class="small-box-footer">Manage <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-secondary">
            <div class="inner"><h3 id="totalRevenue">₱0</h3><p>Total Revenue</p></div>
            <div class="icon"><i class="fas fa-money-bill-wave"></i></div>
            <a href="<?= base_url('clearances/released') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
      </div>

      <!-- DataTable Card -->
      <div class="row">
        <div class="col-12">
          <div class="card card-outline card-primary shadow">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-table mr-1"></i>
                <?php
                  $labels = [
                    'clearances/pending'  => 'Pending Clearances',
                    'clearances/approved' => 'Approved Clearances',
                    'clearances/released' => 'Released Clearances',
                    'clearances/rejected' => 'Rejected Clearances',
                    'clearances/expired'  => 'Expired Clearances',
                  ];
                  echo $labels[uri_string()] ?? 'All Clearances';
                ?>
              </h3>
              <div class="card-tools">
                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#AddNewModal">
                  <i class="fas fa-plus-circle mr-1"></i> Issue New Clearance
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="card-body table-responsive p-0">
              <table id="clearancesTable" class="table table-bordered table-striped table-hover w-100"
                     data-view="<?= uri_string() ?>">
                <thead class="thead-dark">
                  <tr>
                    <th width="4%">#</th>
                    <th style="display:none">id</th>
                    <th>Control Number</th>
                    <th>Resident Name <small class="text-info">(click to view)</small></th>
                    <th>Clearance Type</th>
                    <th>Purpose</th>
                    <th>Request Date</th>
                    <th>Fee</th>
                    <th>Status</th>
                    <th width="8%" class="text-center">Actions</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

    </div><!-- /.container-fluid -->
  </section>

  <!-- ====================================================================
       MODAL: ADD
       - Triggered by: "Issue New Clearance" button (card header)
       - Submits to: clearances/save (POST)
       - On success: closes modal, reloads table, refreshes KPI cards
  ===================================================================== -->
  <div class="modal fade" id="AddNewModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form id="addClearanceForm">
          <?= csrf_field() ?>
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title"><i class="fas fa-plus-circle mr-2"></i>Issue New Clearance</h5>
            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Resident <span class="text-danger">*</span></label>
                  <select name="resident_id" id="addResidentId" class="form-control select2resident" required>
                    <option value="">-- Select Resident --</option>
                    <?php foreach ($residents as $r):
                      $rId   = $r['id'] ?? '';
                      $rName = trim(($r['first_name'] ?? '') . ' ' . ($r['middle_name'] ?? '') . ' ' . ($r['last_name'] ?? ''));
                    ?>
                      <option value="<?= $rId ?>"><?= esc($rName) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Clearance Type <span class="text-danger">*</span></label>
                  <select name="clearance_type" class="form-control" required>
                    <option value="">-- Select Type --</option>
                    <option value="Barangay Clearance">Barangay Clearance</option>
                    <option value="Business Clearance">Business Clearance</option>
                    <option value="Police Clearance">Police Clearance</option>
                    <option value="Employment Clearance">Employment Clearance</option>
                    <option value="Travel Clearance">Travel Clearance</option>
                    <option value="Indigency Clearance">Indigency Clearance</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label>Purpose <span class="text-danger">*</span></label>
              <input type="text" name="purpose" class="form-control" placeholder="e.g. Employment, Travel, etc." required maxlength="500">
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Fee Amount (₱) <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text">₱</span></div>
                    <input type="number" step="0.01" min="0" name="fee_amount" class="form-control" value="50.00" required>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Remarks</label>
                  <textarea name="remarks" class="form-control" rows="2" placeholder="Optional remarks..."></textarea>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i>Save Clearance</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- ====================================================================
       MODAL: EDIT
       - Triggered by: pencil icon in table row, or "Edit" button inside View modal
       - Loads data from: clearances/edit/{id} (GET)
       - Submits to: clearances/update (POST)
       - On success: closes modal, reloads table, refreshes KPI cards
  ===================================================================== -->
  <div class="modal fade" id="editClearanceModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form id="editClearanceForm">
          <?= csrf_field() ?>
          <input type="hidden" name="id" id="editClearanceId">
          <div class="modal-header bg-warning text-dark">
            <h5 class="modal-title"><i class="fas fa-edit mr-2"></i>Edit Clearance</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Resident <span class="text-danger">*</span></label>
                  <select name="resident_id" id="editResidentId" class="form-control select2resident" required>
                    <option value="">-- Select Resident --</option>
                    <?php foreach ($residents as $r):
                      $rId   = $r['id'] ?? '';
                      $rName = trim(($r['first_name'] ?? '') . ' ' . ($r['middle_name'] ?? '') . ' ' . ($r['last_name'] ?? ''));
                    ?>
                      <option value="<?= $rId ?>"><?= esc($rName) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Clearance Type <span class="text-danger">*</span></label>
                  <select name="clearance_type" id="editClearanceType" class="form-control" required>
                    <option value="">-- Select Type --</option>
                    <option value="Barangay Clearance">Barangay Clearance</option>
                    <option value="Business Clearance">Business Clearance</option>
                    <option value="Police Clearance">Police Clearance</option>
                    <option value="Employment Clearance">Employment Clearance</option>
                    <option value="Travel Clearance">Travel Clearance</option>
                    <option value="Indigency Clearance">Indigency Clearance</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label>Purpose <span class="text-danger">*</span></label>
              <input type="text" name="purpose" id="editPurpose" class="form-control" required maxlength="500">
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Fee Amount (₱) <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text">₱</span></div>
                    <input type="number" step="0.01" min="0" name="fee_amount" id="editFeeAmount" class="form-control" required>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Status</label>
                  <select name="status" id="editStatus" class="form-control">
                    <option value="Pending">Pending</option>
                    <option value="Approved">Approved</option>
                    <option value="Released">Released</option>
                    <option value="Rejected">Rejected</option>
                    <option value="Expired">Expired</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label>Remarks</label>
              <textarea name="remarks" id="editRemarks" class="form-control" rows="2"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-warning"><i class="fas fa-save mr-1"></i>Update Clearance</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- ====================================================================
       MODAL: VIEW
       - Triggered by: eye icon in table row, or clicking the resident name link
       - Loads data from: clearances/view/{id} (GET)
       - Shows: full clearance details, workflow action buttons, clearance history
       - "Edit" button: closes this modal and opens Edit modal
  ===================================================================== -->
  <div class="modal fade" id="viewClearanceModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header bg-info text-white">
          <h5 class="modal-title"><i class="fas fa-eye mr-2"></i>Clearance Details</h5>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="viewClearanceId">
          <div class="row">
            <!-- Left: Details + Workflow -->
            <div class="col-md-7">
              <div class="card card-outline card-info mb-3">
                <div class="card-header"><h3 class="card-title">Clearance Information</h3></div>
                <div class="card-body p-0">
                  <table class="table table-sm mb-0">
                    <tr><th width="38%">Control Number</th><td id="viewControlNumber"></td></tr>
                    <tr><th>Resident Name</th><td id="viewResidentName"></td></tr>
                    <tr><th>Address</th><td id="viewAddress"></td></tr>
                    <tr><th>Clearance Type</th><td id="viewClearanceType"></td></tr>
                    <tr><th>Purpose</th><td id="viewPurpose"></td></tr>
                    <tr><th>Request Date</th><td id="viewRequestDate"></td></tr>
                    <tr><th>Issued Date</th><td id="viewIssuedDate"></td></tr>
                    <tr><th>Expiry Date</th><td id="viewExpiryDate"></td></tr>
                    <tr><th>Fee</th><td id="viewFee"></td></tr>
                    <tr><th>OR Number</th><td id="viewOrNumber"></td></tr>
                    <tr><th>Status</th><td id="viewStatus"></td></tr>
                  </table>
                </div>
              </div>
              <!-- Workflow Actions
                   Pending  → shows [Approve] [Reject]
                   Approved → shows [Release (asks for OR#)] [Reject]
                   Released → shows [Print]
                   Rejected / Expired → shows info badge only
              -->
              <div class="card card-outline card-success">
                <div class="card-header"><h3 class="card-title"><i class="fas fa-tasks mr-1"></i>Workflow Actions</h3></div>
                <div class="card-body text-center" id="workflowActions"></div>
              </div>
            </div>
            <!-- Right: History -->
            <div class="col-md-5">
              <div class="card card-outline card-secondary h-100">
                <div class="card-header"><h3 class="card-title"><i class="fas fa-history mr-1"></i>Clearance History</h3></div>
                <div class="card-body" id="clearanceHistory"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-warning" id="editFromViewBtn"><i class="fas fa-edit mr-1"></i>Edit</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- ====================================================================
       MODAL: RELEASE (with OR Number)
       - Triggered by: "Release" button inside View modal's Workflow Actions
       - Submits to: clearances/release/{id} (POST) with or_number
       - On success: closes both this modal and View modal, reloads table
  ===================================================================== -->
  <div class="modal fade" id="releaseModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="releaseForm">
          <input type="hidden" id="releaseClearanceId">
          <div class="modal-header bg-success text-white">
            <h5 class="modal-title"><i class="fas fa-handshake mr-2"></i>Release Clearance</h5>
            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <p class="text-muted mb-3">Enter the Official Receipt number to confirm payment and release the clearance.</p>
            <div class="form-group">
              <label>OR Number <span class="text-danger">*</span></label>
              <input type="text" id="releaseOrNumber" class="form-control" placeholder="e.g. OR-2024-00123" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-success"><i class="fas fa-handshake mr-1"></i>Confirm Release</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- ====================================================================
       MODAL: REJECT
       - Triggered by: "Reject" button inside View modal's Workflow Actions
       - Submits to: clearances/reject/{id} (POST) with reason
       - On success: closes both this modal and View modal, reloads table
  ===================================================================== -->
  <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="rejectForm">
          <input type="hidden" id="rejectClearanceId">
          <div class="modal-header bg-danger text-white">
            <h5 class="modal-title"><i class="fas fa-ban mr-2"></i>Reject Clearance</h5>
            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label>Rejection Reason <span class="text-danger">*</span></label>
              <textarea id="rejectReason" class="form-control" rows="3" placeholder="Enter the reason for rejection..." required></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-danger"><i class="fas fa-ban mr-1"></i>Confirm Rejection</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- ====================================================================
       MODAL: PRINT PREVIEW
       - Triggered by: "Print" button inside View modal (Released status only)
       - Loads from: clearances/getPrintPreview/{id} (GET)
       - "Print Now" button: opens the content in a new browser print window
  ===================================================================== -->
  <div class="modal fade" id="printModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title"><i class="fas fa-print mr-2"></i>Print Preview</h5>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body bg-light">
          <div id="printPreviewContent" class="bg-white shadow p-4 mx-auto" style="max-width:800px;min-height:400px;"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="printBtn"><i class="fas fa-print mr-1"></i>Print Now</button>
        </div>
      </div>
    </div>
  </div>

</div><!-- /.content-wrapper -->
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  const baseUrl     = "<?= base_url() ?>";
  const currentView = "<?= uri_string() ?>";
</script>
<script src="<?= base_url('js/clearances/clearances.js') ?>"></script>
<?= $this->endSection() ?>

<?= $this->extend('theme/template') ?>

<?= $this->section('content') ?>
<div class="content-wrapper">

  <!-- Content Header -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">
            <i class="fas fa-hand-holding-heart mr-2 text-success"></i>Social Assistance
          </h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Indigents</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <!-- Main Content -->
  <section class="content">
    <div class="container-fluid">

      <!-- Workflow Nav Tabs -->
      <div class="row mb-3">
        <div class="col-12">
          <div class="btn-group btn-group-lg w-100" role="group" aria-label="Workflow tabs">
            <a href="<?= base_url('indigents') ?>"
               class="btn btn-outline-secondary <?= uri_string() === 'indigents' ? 'active' : '' ?>">
              <i class="fas fa-list mr-1"></i>All
            </a>
            <a href="<?= base_url('indigents/pending') ?>"
               class="btn btn-outline-warning <?= uri_string() === 'indigents/pending' ? 'active' : '' ?>">
              <i class="fas fa-clock mr-1"></i>Pending
              <span class="badge badge-warning ml-1" id="pendingCount">0</span>
            </a>
            <a href="<?= base_url('indigents/approved') ?>"
               class="btn btn-outline-success <?= uri_string() === 'indigents/approved' ? 'active' : '' ?>">
              <i class="fas fa-check mr-1"></i>Approved
              <span class="badge badge-success ml-1" id="approvedCount">0</span>
            </a>
            <a href="<?= base_url('indigents/released') ?>"
               class="btn btn-outline-primary <?= uri_string() === 'indigents/released' ? 'active' : '' ?>">
              <i class="fas fa-check-circle mr-1"></i>Completed
              <span class="badge badge-primary ml-1" id="releasedCount">0</span>
            </a>
          </div>
        </div>
      </div>

      <!-- KPI Cards -->
      <div class="row">
        <div class="col-lg-3 col-6">
          <div class="small-box bg-info">
            <div class="inner"><h3 id="statTotal">0</h3><p>Total Records</p></div>
            <div class="icon"><i class="fas fa-users"></i></div>
            <a href="<?= base_url('indigents') ?>" class="small-box-footer">View All <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-warning">
            <div class="inner"><h3 id="statPending">0</h3><p>Pending</p></div>
            <div class="icon"><i class="fas fa-clock"></i></div>
            <a href="<?= base_url('indigents/pending') ?>" class="small-box-footer">Manage <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-success">
            <div class="inner"><h3 id="statCompleted">0</h3><p>Completed</p></div>
            <div class="icon"><i class="fas fa-check-circle"></i></div>
            <a href="<?= base_url('indigents/released') ?>" class="small-box-footer">View <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-danger">
            <div class="inner"><h3 id="statRejected">0</h3><p>Rejected</p></div>
            <div class="icon"><i class="fas fa-times-circle"></i></div>
            <a href="#" class="small-box-footer">View <i class="fas fa-arrow-circle-right"></i></a>
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
                    'indigents/pending'  => 'Pending Assessment',
                    'indigents/approved' => 'Approved Assistance',
                    'indigents/released' => 'Completed Assistance',
                  ];
                  echo $labels[uri_string()] ?? 'All Assistance Records';
                ?>
              </h3>
              <div class="card-tools">
                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addIndigentModal">
                  <i class="fas fa-plus-circle mr-1"></i>Add New Assistance
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="card-body table-responsive p-0">
              <table id="indigentsTable"
                     class="table table-bordered table-striped table-hover w-100"
                     data-view="<?= uri_string() ?>">
                <thead class="thead-dark">
                  <tr>
                    <th width="4%">#</th>
                    <th style="display:none">id</th>
                    <th>Beneficiary Name</th>
                    <th>Category</th>
                    <th>Assistance Type</th>
                    <th>Amount</th>
                    <th>Date Assessed</th>
                    <th>Date Provided</th>
                    <th>Status</th>
                    <th width="9%" class="text-center">Actions</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>

  <!-- MODAL: ADD NEW -->
  <div class="modal fade" id="addIndigentModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form id="addIndigentForm" autocomplete="off">
          <?= csrf_field() ?>
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title"><i class="fas fa-plus-circle mr-2"></i>Add New Assistance Record</h5>
            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
          </div>

          <div class="modal-body">
            <div class="form-group">
              <label>Resident <span class="text-danger">*</span>
                <small class="text-muted">(select from list)</small>
              </label>
              <select name="resident_id" id="addResidentId" class="form-control select2resident" required>
                <option value="">-- Select Resident --</option>
                <?php if (!empty($residents) && is_array($residents)): ?>
                  <?php foreach ($residents as $r):
                    $rId = $r['id'] ?? '';
                    $rName = trim(($r['first_name'] ?? '') . ' ' . ($r['middle_name'] ?? '') . ' ' . ($r['last_name'] ?? ''));
                  ?>
                    <option value="<?= esc($rId) ?>"><?= esc($rName) ?></option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </div>

            <!-- Assistance Details -->
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Indigency Category <span class="text-danger">*</span></label>
                  <select name="indigency_category" id="addIndigencyCategory" class="form-control" required>
                    <option value="">-- Select Category --</option>
                    <option>4Ps Family</option>
                    <option>Senior Citizen</option>
                    <option>PWD</option>
                    <option>Solo Parent</option>
                    <option>Unemployed</option>
                    <option>Homeless</option>
                    <option>Indigenous People</option>
                    <option>Single Mother</option>
                    <option>Widow/Widower</option>
                    <option>Out of School Youth</option>
                    <option>Low Income Family</option>
                    <option>Disaster Victim</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Assistance Type</label>
                  <select name="assistance_type" id="addAssistanceType" class="form-control">
                    <option value="">-- Select Type --</option>
                    <option>Financial</option>
                    <option>Medical</option>
                    <option>Food</option>
                    <option>Burial</option>
                    <option>Educational</option>
                    <option>Transportation</option>
                    <option>Housing</option>
                    <option>Livelihood</option>
                    <option>Legal</option>
                    <option>Counseling</option>
                    <option>Emergency Relief</option>
                    <option>Utility Assistance</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Assistance Amount (₱)</label>
                  <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text">₱</span></div>
                    <input type="number" step="0.01" min="0" name="assistance_amount"
                           id="addAssistanceAmount" class="form-control" placeholder="0.00">
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Purpose</label>
                  <input type="text" name="purpose" id="addPurpose" class="form-control"
                         placeholder="Reason for assistance…">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label>Remarks / Additional Notes</label>
                  <textarea name="remarks" id="addRemarks" class="form-control" rows="2"
                            placeholder="Optional notes…"></textarea>
                </div>
              </div>
            </div>

            <div class="alert alert-info mb-0 py-2">
              <i class="fas fa-info-circle mr-1"></i>
              <small><strong>Note:</strong> Date Assessed is auto-generated by the system. Status starts as <em>Pending Assessment</em>.</small>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">
              <i class="fas fa-times mr-1"></i>Cancel
            </button>
            <button type="submit" class="btn btn-primary" id="addIndigentSubmitBtn">
              <i class="fas fa-save mr-1"></i>Save Record
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- MODAL: EDIT -->
  <div class="modal fade" id="editIndigentModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form id="editIndigentForm" autocomplete="off">
          <?= csrf_field() ?>
          <input type="hidden" name="id" id="editId">
          <div class="modal-header bg-warning text-dark">
            <h5 class="modal-title"><i class="fas fa-edit mr-2"></i>Edit Assistance Record</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Full Name <span class="text-danger">*</span></label>
                  <input type="text" name="full_name" id="editFullName" class="form-control" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Address</label>
                  <input type="text" name="address" id="editAddress" class="form-control">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Indigency Category <span class="text-danger">*</span></label>
                  <select name="indigency_category" id="editIndigencyCategory" class="form-control" required>
                    <option value="">-- Select Category --</option>
                    <option>4Ps Family</option>
                    <option>Senior Citizen</option>
                    <option>PWD</option>
                    <option>Solo Parent</option>
                    <option>Unemployed</option>
                    <option>Homeless</option>
                    <option>Indigenous People</option>
                    <option>Single Mother</option>
                    <option>Widow/Widower</option>
                    <option>Out of School Youth</option>
                    <option>Low Income Family</option>
                    <option>Disaster Victim</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Assistance Type</label>
                  <select name="assistance_type" id="editAssistanceType" class="form-control">
                    <option value="">-- Select Type --</option>
                    <option>Financial</option>
                    <option>Medical</option>
                    <option>Food</option>
                    <option>Burial</option>
                    <option>Educational</option>
                    <option>Transportation</option>
                    <option>Housing</option>
                    <option>Livelihood</option>
                    <option>Legal</option>
                    <option>Counseling</option>
                    <option>Emergency Relief</option>
                    <option>Utility Assistance</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Assistance Amount (₱)</label>
                  <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text">₱</span></div>
                    <input type="number" step="0.01" min="0" name="assistance_amount"
                           id="editAssistanceAmount" class="form-control" placeholder="0.00">
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Purpose</label>
                  <input type="text" name="purpose" id="editPurpose" class="form-control">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label>Remarks</label>
                  <textarea name="remarks" id="editRemarks" class="form-control" rows="2"></textarea>
                </div>
              </div>
            </div>

            <div class="alert alert-warning mb-0 py-2">
              <i class="fas fa-exclamation-triangle mr-1"></i>
              <small>Status and workflow dates are managed through workflow buttons only.</small>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">
              <i class="fas fa-times mr-1"></i>Cancel
            </button>
            <button type="submit" class="btn btn-warning" id="editIndigentSubmitBtn">
              <i class="fas fa-save mr-1"></i>Update Changes
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- MODAL: VIEW / DETAILS -->
  <div class="modal fade" id="viewIndigentModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header bg-info text-white">
          <h5 class="modal-title"><i class="fas fa-eye mr-2"></i>Assistance Details</h5>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="viewIndigentId">

          <div class="row">
            <div class="col-md-7">
              <div class="card card-outline card-info mb-3">
                <div class="card-header py-2">
                  <h3 class="card-title"><i class="fas fa-user mr-1"></i>Beneficiary Information</h3>
                </div>
                <div class="card-body p-0">
                  <table class="table table-sm table-borderless mb-0">
                    <tr><th class="pl-3" width="38%">Full Name</th><td id="viewFullName"></td></tr>
                    <tr><th class="pl-3">Address</th><td id="viewAddress"></td></tr>
                    <tr><th class="pl-3">Category</th><td id="viewCategory"></td></tr>
                    <tr><th class="pl-3">Assistance Type</th><td id="viewAssistanceType"></td></tr>
                    <tr><th class="pl-3">Amount</th><td id="viewAmount"></td></tr>
                    <tr><th class="pl-3">Purpose</th><td id="viewPurpose"></td></tr>
                    <tr><th class="pl-3">Date Assessed</th><td id="viewDateAssessed"></td></tr>
                    <tr><th class="pl-3">Date Provided</th><td id="viewDateProvided"></td></tr>
                    <tr><th class="pl-3">Status</th><td id="viewStatus"></td></tr>
                  </table>
                </div>
              </div>

              <div class="card card-outline card-success mb-3">
                <div class="card-header py-2">
                  <h3 class="card-title"><i class="fas fa-tasks mr-1"></i>Workflow Actions</h3>
                </div>
                <div class="card-body text-center" id="workflowActions">
                  <span class="text-muted">Loading…</span>
                </div>
              </div>

              <div class="card card-outline card-danger mb-3" id="rejectedReasonCard" style="display:none;">
                <div class="card-header py-2">
                  <h3 class="card-title text-danger"><i class="fas fa-ban mr-1"></i>Rejection Reason</h3>
                </div>
                <div class="card-body" id="viewRejectedReason"></div>
              </div>
            </div>

            <div class="col-md-5">
              <div class="card card-outline card-secondary mb-3">
                <div class="card-header py-2">
                  <h3 class="card-title"><i class="fas fa-sticky-note mr-1"></i>Remarks</h3>
                </div>
                <div class="card-body" id="viewRemarks">
                  <p class="text-muted text-center py-2"><i class="fas fa-sticky-note mr-1"></i>No remarks</p>
                </div>
              </div>

              <div class="card card-outline card-secondary">
                <div class="card-header py-2">
                  <h3 class="card-title"><i class="fas fa-history mr-1"></i>Assistance History</h3>
                </div>
                <div class="card-body p-2" id="assistanceHistory">
                  <p class="text-muted text-center py-2"><i class="fas fa-spinner fa-spin mr-1"></i>Loading…</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-warning" id="editFromViewBtn">
            <i class="fas fa-edit mr-1"></i>Edit
          </button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- MODAL: REJECT -->
  <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="rejectForm" autocomplete="off">
          <?= csrf_field() ?>
          <input type="hidden" id="rejectIndigentId">
          <div class="modal-header bg-danger text-white">
            <h5 class="modal-title"><i class="fas fa-ban mr-2"></i>Reject Assistance</h5>
            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <div class="form-group mb-0">
              <label>Reason for Rejection <span class="text-danger">*</span></label>
              <textarea id="rejectReason" name="reason" class="form-control" rows="4" required
                        placeholder="Explain why this assistance is being rejected…"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-danger" id="rejectSubmitBtn">
              <i class="fas fa-ban mr-1"></i>Confirm Rejection
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- MODAL: PRINT PREVIEW -->
  <div class="modal fade" id="printModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title"><i class="fas fa-print mr-2"></i>Print Preview</h5>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body bg-light">
          <div id="printPreviewContent" class="bg-white shadow p-4 mx-auto" style="max-width:800px; min-height:400px;"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="printNowBtn">
            <i class="fas fa-print mr-1"></i>Print Now
          </button>
        </div>
      </div>
    </div>
  </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  const BASE_URL = "<?= rtrim(base_url(), '/') ?>/";
  const CURRENT_URI = "<?= uri_string() ?>";
</script>
<script src="<?= base_url('js/indigents/indigents.js') ?>"></script>
<?= $this->endSection() ?>
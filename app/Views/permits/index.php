<?= $this->extend('theme/template') ?>

<?= $this->section('content') ?>
<div class="content-wrapper">

  <!-- ── Content Header ──────────────────────────────────────────────────── -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark"><i class="fas fa-store mr-2"></i>Business Permits</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Business Permits</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <!-- ── Main Content ────────────────────────────────────────────────────── -->
  <section class="content">
    <div class="container-fluid">

      <!-- Workflow Nav Tabs -->
      <div class="row mb-3">
        <div class="col-12">
          <div class="btn-group btn-group-lg w-100" role="group">
            <a href="<?= base_url('permits') ?>"
               class="btn btn-outline-secondary <?= uri_string() === 'permits' ? 'active' : '' ?>">
              <i class="fas fa-list mr-1"></i> All
            </a>
            <a href="<?= base_url('permits/pending') ?>"
               class="btn btn-warning <?= uri_string() === 'permits/pending' ? 'active' : '' ?>">
              <i class="fas fa-clock mr-1"></i> Pending
              <span class="badge badge-light ml-1" id="pendingCount">0</span>
            </a>
            <a href="<?= base_url('permits/payment') ?>"
               class="btn btn-success <?= uri_string() === 'permits/payment' ? 'active' : '' ?>">
              <i class="fas fa-money-bill mr-1"></i> Payment
              <span class="badge badge-light ml-1" id="paymentCount">0</span>
            </a>
            <a href="<?= base_url('permits/print') ?>"
               class="btn btn-primary <?= uri_string() === 'permits/print' ? 'active' : '' ?>">
              <i class="fas fa-print mr-1"></i> Print
              <span class="badge badge-light ml-1" id="printCount">0</span>
            </a>
          </div>
        </div>
      </div>

      <!-- KPI Cards -->
      <div class="row">
        <div class="col-lg-3 col-6">
          <div class="small-box bg-info">
            <div class="inner"><h3 id="totalPermits">0</h3><p>Total Permits</p></div>
            <div class="icon"><i class="fas fa-certificate"></i></div>
            <a href="<?= base_url('permits') ?>" class="small-box-footer">View All <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-success">
            <div class="inner"><h3 id="activePermits">0</h3><p>Active</p></div>
            <div class="icon"><i class="fas fa-check-circle"></i></div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-warning">
            <div class="inner"><h3 id="pendingPermits">0</h3><p>Pending</p></div>
            <div class="icon"><i class="fas fa-clock"></i></div>
            <a href="<?= base_url('permits/pending') ?>" class="small-box-footer">Manage <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-danger">
            <div class="inner"><h3 id="expiredPermits">0</h3><p>Expired</p></div>
            <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
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
                    'permits/pending' => 'Pending Permits',
                    'permits/payment' => 'Awaiting Payment',
                    'permits/print'   => 'Ready to Print',
                  ];
                  echo $labels[uri_string()] ?? 'All Business Permits';
                ?>
              </h3>
              <div class="card-tools">
                <?php if (!in_array(uri_string(), ['permits/pending','permits/payment','permits/print'])): ?>
                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#AddNewModal">
                  <i class="fas fa-plus-circle mr-1"></i> Add New Permit
                </button>
                <?php endif; ?>
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="card-body table-responsive p-0">
              <table id="permitsTable" class="table table-bordered table-striped table-hover w-100"
                     data-view="<?= uri_string() ?>">
                <thead class="thead-dark">
                  <tr>
                    <th width="4%">#</th>
                    <th style="display:none">id</th>
                    <th>Business Name <small class="text-info">(click to view)</small></th>
                    <th>Owner</th>
                    <th>Business Type</th>
                    <th>Permit Type</th>
                    <th>Issue Date</th>
                    <th>Expiry Date</th>
                    <th>Status</th>
                    <th>Fees Paid</th>
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
  ===================================================================== -->
  <div class="modal fade" id="AddNewModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form id="addPermitForm">
          <?= csrf_field() ?>
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title"><i class="fas fa-plus-circle mr-2"></i>Add New Business Permit</h5>
            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Business Name <span class="text-danger">*</span></label>
                  <input type="text" name="business_name" class="form-control" placeholder="e.g. Aling Nena's Store" required>
                </div>
                <div class="form-group">
                  <label>Owner Name <span class="text-danger">*</span></label>
                  <input type="text" name="owner_name" class="form-control" placeholder="Full name" required>
                </div>
                <div class="form-group">
                  <label>Business Type</label>
                  <input type="text" name="business_type" class="form-control" placeholder="e.g. Sari-Sari Store, Eatery">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Permit Type</label>
                  <select name="permit_type" class="form-control">
                    <option value="New">New</option>
                    <option value="Renewal">Renewal</option>
                    <option value="Amendment">Amendment</option>
                  </select>
                </div>
                <div class="form-group">
                  <label>Issue Date</label>
                  <input type="date" name="issue_date" id="addIssueDate" class="form-control">
                </div>
                <div class="form-group">
                  <label>Expiry Date</label>
                  <input type="date" name="expiry_date" class="form-control">
                </div>
              </div>
              <div class="col-12">
                <div class="form-group">
                  <label>Business Address</label>
                  <textarea name="business_address" class="form-control" rows="2" placeholder="Complete address"></textarea>
                </div>
                <div class="form-group">
                  <label>Notes</label>
                  <textarea name="notes" class="form-control" rows="2"></textarea>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i>Save Permit</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- ====================================================================
       MODAL: EDIT
  ===================================================================== -->
  <div class="modal fade" id="editPermitModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form id="editPermitForm">
          <?= csrf_field() ?>
          <input type="hidden" name="id" id="editPermitId">
          <div class="modal-header bg-warning text-dark">
            <h5 class="modal-title"><i class="fas fa-edit mr-2"></i>Edit Business Permit</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Business Name <span class="text-danger">*</span></label>
                  <input type="text" name="business_name" id="editBusinessName" class="form-control" required>
                </div>
                <div class="form-group">
                  <label>Owner Name <span class="text-danger">*</span></label>
                  <input type="text" name="owner_name" id="editOwnerName" class="form-control" required>
                </div>
                <div class="form-group">
                  <label>Business Type</label>
                  <input type="text" name="business_type" id="editBusinessType" class="form-control">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Permit Type</label>
                  <select name="permit_type" id="editPermitType" class="form-control">
                    <option value="New">New</option>
                    <option value="Renewal">Renewal</option>
                    <option value="Amendment">Amendment</option>
                  </select>
                </div>
                <div class="form-group">
                  <label>Issue Date</label>
                  <input type="date" name="issue_date" id="editIssueDate" class="form-control">
                </div>
                <div class="form-group">
                  <label>Expiry Date</label>
                  <input type="date" name="expiry_date" id="editExpiryDate" class="form-control">
                </div>
              </div>
              <div class="col-12">
                <div class="form-group">
                  <label>Business Address</label>
                  <textarea name="business_address" id="editBusinessAddress" class="form-control" rows="2"></textarea>
                </div>
                <div class="form-group">
                  <label>Notes</label>
                  <textarea name="notes" id="editNotes" class="form-control" rows="2"></textarea>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-warning"><i class="fas fa-save mr-1"></i>Update Changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- ====================================================================
       MODAL: VIEW
  ===================================================================== -->
  <div class="modal fade" id="viewPermitModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header bg-info text-white">
          <h5 class="modal-title"><i class="fas fa-eye mr-2"></i>Permit Details</h5>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="viewPermitId">
          <div class="row">
            <!-- Left: Details + Workflow -->
            <div class="col-md-7">
              <div class="card card-outline card-info mb-3">
                <div class="card-header"><h3 class="card-title">Business Information</h3></div>
                <div class="card-body p-0">
                  <table class="table table-sm mb-0">
                    <tr><th width="38%">Business Name</th><td id="viewBusinessName"></td></tr>
                    <tr><th>Owner</th>                    <td id="viewOwnerName"></td></tr>
                    <tr><th>Business Type</th>            <td id="viewBusinessType"></td></tr>
                    <tr><th>Address</th>                  <td id="viewBusinessAddress"></td></tr>
                    <tr><th>Permit Type</th>              <td id="viewPermitType"></td></tr>
                    <tr><th>Issue Date</th>               <td id="viewIssueDate"></td></tr>
                    <tr><th>Expiry Date</th>              <td id="viewExpiryDate"></td></tr>
                    <tr><th>Status</th>                   <td id="viewStatus"></td></tr>
                    <tr><th>Fees Paid</th>                <td id="viewFeesPaid"></td></tr>
                  </table>
                </div>
              </div>
              <div class="card card-outline card-success">
                <div class="card-header"><h3 class="card-title"><i class="fas fa-tasks mr-1"></i>Workflow Actions</h3></div>
                <div class="card-body text-center" id="workflowActions"></div>
              </div>
            </div>
            <!-- Right: Activity Log -->
            <div class="col-md-5">
              <div class="card card-outline card-secondary h-100">
                <div class="card-header"><h3 class="card-title"><i class="fas fa-history mr-1"></i>Activity Log</h3></div>
                <div class="card-body overflow-auto" style="max-height:420px;" id="activityLog"></div>
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
       MODAL: REJECT
  ===================================================================== -->
  <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="rejectForm">
          <input type="hidden" id="rejectPermitId">
          <div class="modal-header bg-danger text-white">
            <h5 class="modal-title"><i class="fas fa-ban mr-2"></i>Reject Application</h5>
            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label>Reason for Rejection <span class="text-danger">*</span></label>
              <textarea id="rejectReason" class="form-control" rows="3" required
                        placeholder="Explain why this application is being rejected…"></textarea>
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
       MODAL: MARK PAID
  ===================================================================== -->
  <div class="modal fade" id="markPaidModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="markPaidForm">
          <input type="hidden" id="paidPermitId">
          <div class="modal-header bg-success text-white">
            <h5 class="modal-title"><i class="fas fa-money-bill mr-2"></i>Process Payment</h5>
            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label>Amount to Pay (₱) <span class="text-danger">*</span></label>
              <input type="number" step="0.01" min="0.01" id="feesPaid" class="form-control"
                     required placeholder="0.00">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-success"><i class="fas fa-check mr-1"></i>Confirm Payment</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- ====================================================================
       MODAL: PRINT PREVIEW
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
<script src="<?= base_url('js/permits/permits.js') ?>"></script>
<?= $this->endSection() ?>
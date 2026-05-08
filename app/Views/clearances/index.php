<?= $this->extend('theme/template') ?>

<?= $this->section('content') ?>
<div class="content-wrapper">
  <!-- Content Header -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">
            <i class="fas fa-file-alt mr-2"></i>Barangay Clearances
          </h1>
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

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- Stats Cards Row -->
      <div class="row">
        <div class="col-lg-3 col-6">
          <div class="small-box bg-info">
            <div class="inner">
              <h3 id="totalClearances">0</h3>
              <p>Total Clearances</p>
            </div>
            <div class="icon">
              <i class="fas fa-file-alt"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-warning">
            <div class="inner">
              <h3 id="pendingClearances">0</h3>
              <p>Pending</p>
            </div>
            <div class="icon">
              <i class="fas fa-clock"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-success">
            <div class="inner">
              <h3 id="approvedClearances">0</h3>
              <p>Approved</p>
            </div>
            <div class="icon">
              <i class="fas fa-check-circle"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-primary">
            <div class="inner">
              <h3 id="releasedClearances">0</h3>
              <p>Released</p>
            </div>
            <div class="icon">
              <i class="fas fa-handshake"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
      </div>

      <!-- Data Table Card -->
      <div class="row">
        <div class="col-12">
          <div class="card card-outline card-primary shadow">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-table mr-1"></i> Clearance Records
              </h3>
              <div class="card-tools">
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addClearanceModal">
                  <i class="fas fa-plus-circle mr-1"></i> Issue New Clearance
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="clearancesTable" class="table table-bordered table-striped table-hover dataTable w-100">
                <thead class="thead-dark">
                  <tr>
                    <th width="5%">No.</th>
                    <th style="display:none;">ID</th>
                    <th>Control Number</th>
                    <th>Resident Name</th>
                    <th>Clearance Type</th>
                    <th>Purpose</th>
                    <th>Request Date</th>
                    <th>Issued Date</th>
                    <th>Status</th>
                    <th>Fee</th>
                    <th width="12%" class="text-center">Actions</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
  </section>
  <!-- /.content -->

  <!-- ADD MODAL -->
  <div class="modal fade" id="addClearanceModal" tabindex="-1" role="dialog" aria-labelledby="addClearanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
        <form id="addClearanceForm" autocomplete="off">
          <?= csrf_field() ?>
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="addClearanceModalLabel">
              <i class="fas fa-plus-circle mr-2"></i>Issue New Clearance
            </h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <!-- Left Column -->
              <div class="col-md-6">
                <div class="form-group">
                  <label for="addControlNumber">Control Number <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                    </div>
                    <input type="text" name="control_number" class="form-control" id="addControlNumber"
                           placeholder="e.g., CLR-2024-001" required />
                  </div>
                </div>
                <div class="form-group">
                  <label for="addResidentId">Resident <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-user"></i></span>
                    </div>
                    <select name="resident_id" id="addResidentId" class="form-control select2resident" required>
                      <option value="">-- Select Resident --</option>
                      <?php foreach ($residents as $r):
                        $rId = $r['id'] ?? $r['resident_id'] ?? '';
                        $rName = trim(($r['first_name'] ?? '') . ' ' . ($r['middle_name'] ?? '') . ' ' . ($r['last_name'] ?? ''));
                      ?>
                        <option value="<?= $rId ?>"><?= esc($rName) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="addClearanceTypeId">Clearance Type <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-tag"></i></span>
                    </div>
                    <select name="clearance_type_id" id="addClearanceTypeId" class="form-control" required>
                      <option value="">-- Select Type --</option>
                      <?php foreach ($clearanceTypes as $ct):
                        $ctId = $ct['clearance_type_id'] ?? $ct['id'] ?? '';
                      ?>
                        <option value="<?= $ctId ?>"><?= esc($ct['type_name'] ?? '') ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="addPurpose">Purpose <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-bullseye"></i></span>
                    </div>
                    <input type="text" name="purpose" class="form-control" id="addPurpose"
                           placeholder="e.g., Employment, Travel, etc." required maxlength="255" />
                  </div>
                </div>
              </div>
              <!-- Right Column -->
              <div class="col-md-6">
                <div class="form-group">
                  <label for="addRequestDate">Request Date <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                    </div>
                    <input type="date" name="request_date" class="form-control" id="addRequestDate" required />
                  </div>
                </div>
                <div class="form-group">
                  <label for="addIssuedDate">Date Issued</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                    </div>
                    <input type="date" name="issued_date" class="form-control" id="addIssuedDate" />
                  </div>
                </div>
                <div class="form-group">
                  <label for="addExpiryDate">Expiry Date</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-calendar-times"></i></span>
                    </div>
                    <input type="date" name="expiry_date" class="form-control" id="addExpiryDate" />
                  </div>
                </div>
                <div class="form-group">
                  <label for="addStatus">Status <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                    </div>
                    <select name="status" class="form-control" id="addStatus" required>
                      <option value="Pending">Pending</option>
                      <option value="Approved">Approved</option>
                      <option value="Released">Released</option>
                      <option value="Rejected">Rejected</option>
                      <option value="Expired">Expired</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="addFeeAmount">Fee Amount <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">₱</span>
                    </div>
                    <input type="number" name="fee_amount" class="form-control" id="addFeeAmount"
                           step="0.01" min="0" value="0.00" required />
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="addOrNumber">OR Number</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-receipt"></i></span>
                    </div>
                    <input type="text" name="or_number" class="form-control" id="addOrNumber"
                           placeholder="Official Receipt No." />
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label for="addRemarks">Remarks</label>
              <textarea name="remarks" class="form-control" id="addRemarks" rows="2"
                        placeholder="Optional remarks or additional notes..."></textarea>
            </div>
          </div>
          <div class="modal-footer bg-light">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">
              <i class="fas fa-times-circle mr-1"></i> Cancel
            </button>
            <button type="submit" class="btn btn-primary" id="addSaveBtn">
              <i class="fas fa-save mr-1"></i> Save Clearance
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- EDIT MODAL -->
  <div class="modal fade" id="editClearanceModal" tabindex="-1" role="dialog" aria-labelledby="editClearanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
        <form id="editClearanceForm" autocomplete="off">
          <?= csrf_field() ?>
          <div class="modal-header bg-warning">
            <h5 class="modal-title" id="editClearanceModalLabel">
              <i class="fas fa-edit mr-2"></i>Edit Clearance
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <input type="hidden" id="editClearanceId" name="clearance_id" />
            <div class="row">
              <!-- Left Column -->
              <div class="col-md-6">
                <div class="form-group">
                  <label for="editControlNumber">Control Number <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                    </div>
                    <input type="text" id="editControlNumber" name="control_number"
                           class="form-control" required />
                  </div>
                </div>
                <div class="form-group">
                  <label for="editResidentId">Resident <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-user"></i></span>
                    </div>
                    <select name="resident_id" id="editResidentId" class="form-control select2resident" required>
                      <option value="">-- Select Resident --</option>
                      <?php foreach ($residents as $r):
                        $rId = $r['id'] ?? $r['resident_id'] ?? '';
                        $rName = trim(($r['first_name'] ?? '') . ' ' . ($r['middle_name'] ?? '') . ' ' . ($r['last_name'] ?? ''));
                      ?>
                        <option value="<?= $rId ?>"><?= esc($rName) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="editClearanceTypeId">Clearance Type <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-tag"></i></span>
                    </div>
                    <select name="clearance_type_id" id="editClearanceTypeId" class="form-control" required>
                      <option value="">-- Select Type --</option>
                      <?php foreach ($clearanceTypes as $ct):
                        $ctId = $ct['clearance_type_id'] ?? $ct['id'] ?? '';
                      ?>
                        <option value="<?= $ctId ?>"><?= esc($ct['type_name'] ?? '') ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="editPurpose">Purpose <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-bullseye"></i></span>
                    </div>
                    <input type="text" id="editPurpose" name="purpose"
                           class="form-control" required maxlength="255" />
                  </div>
                </div>
              </div>
              <!-- Right Column -->
              <div class="col-md-6">
                <div class="form-group">
                  <label for="editRequestDate">Request Date <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                    </div>
                    <input type="date" id="editRequestDate" name="request_date"
                           class="form-control" required />
                  </div>
                </div>
                <div class="form-group">
                  <label for="editIssuedDate">Date Issued</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                    </div>
                    <input type="date" id="editIssuedDate" name="issued_date"
                           class="form-control" />
                  </div>
                </div>
                <div class="form-group">
                  <label for="editExpiryDate">Expiry Date</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-calendar-times"></i></span>
                    </div>
                    <input type="date" id="editExpiryDate" name="expiry_date"
                           class="form-control" />
                  </div>
                </div>
                <div class="form-group">
                  <label for="editStatus">Status <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                    </div>
                    <select id="editStatus" name="status" class="form-control" required>
                      <option value="Pending">Pending</option>
                      <option value="Approved">Approved</option>
                      <option value="Released">Released</option>
                      <option value="Rejected">Rejected</option>
                      <option value="Expired">Expired</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="editFeeAmount">Fee Amount <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">₱</span>
                    </div>
                    <input type="number" id="editFeeAmount" name="fee_amount"
                           class="form-control" step="0.01" min="0" required />
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="editOrNumber">OR Number</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-receipt"></i></span>
                    </div>
                    <input type="text" id="editOrNumber" name="or_number"
                           class="form-control" />
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label for="editRemarks">Remarks</label>
              <textarea id="editRemarks" name="remarks" class="form-control" rows="2"></textarea>
            </div>
          </div>
          <div class="modal-footer bg-light">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">
              <i class="fas fa-times-circle mr-1"></i> Cancel
            </button>
            <button type="submit" class="btn btn-warning" id="editSaveBtn">
              <i class="fas fa-save mr-1"></i> Update Clearance
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- VIEW MODAL -->
  <div class="modal fade" id="viewClearanceModal" tabindex="-1" role="dialog" aria-labelledby="viewClearanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header bg-info text-white">
          <h5 class="modal-title" id="viewClearanceModalLabel">
            <i class="fas fa-eye mr-2"></i>Clearance Details
          </h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="viewClearanceBody">
          <!-- Filled by JS -->
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            <i class="fas fa-times-circle mr-1"></i> Close
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- DELETE CONFIRMATION MODAL -->
  <div class="modal fade" id="deleteConfirmModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title">
            <i class="fas fa-exclamation-triangle mr-2"></i>Confirm Delete
          </h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body text-center py-4">
          <i class="fas fa-trash-alt text-danger mb-3" style="font-size: 3rem;"></i>
          <h5>Are you sure?</h5>
          <p class="text-muted mb-0">This action cannot be undone.</p>
        </div>
        <div class="modal-footer justify-content-center">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
            <i class="fas fa-times mr-1"></i> Cancel
          </button>
          <button type="button" class="btn btn-danger btn-sm" id="confirmDeleteBtn">
            <i class="fas fa-trash mr-1"></i> Delete
          </button>
        </div>
      </div>
    </div>
  </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>const baseUrl = "<?= base_url() ?>";</script>
<script src="<?= base_url('js/clearances/clearances.js') ?>"></script>
<?= $this->endSection() ?>
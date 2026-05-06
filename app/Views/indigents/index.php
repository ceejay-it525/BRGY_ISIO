<?= $this->extend('theme/template') ?>

<?= $this->section('content') ?>
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Indigent Records</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Indigents</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card card-outline card-primary">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-hand-holding-heart mr-2"></i>List of Indigent Records</h3>
              <div class="float-right">
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#AddNewModal">
                  <i class="fa fa-plus-circle mr-1"></i> Add New Record
                </button>
              </div>
            </div>
            <div class="card-body">
              <table id="indigentsTable" class="table table-bordered table-striped table-hover table-sm">
                <thead class="thead-light">
                  <tr>
                    <th width="50">#</th>
                    <th style="display:none;">id</th>
                    <th>Resident Name</th>
                    <th>Category</th>
                    <th>Assistance Type</th>
                    <th>Amount</th>
                    <th>Date Provided</th>
                    <th width="90">Status</th>
                    <th width="100" class="text-center">Actions</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ADD NEW MODAL -->
    <div class="modal fade" id="AddNewModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <form id="addIndigentForm" autocomplete="off">
          <?= csrf_field() ?>
          <div class="modal-content">
            <div class="modal-header bg-primary">
              <h5 class="modal-title text-white"><i class="fa fa-plus-circle mr-2"></i>Add Indigent Record</h5>
              <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">

              <!-- Name Fields Row -->
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>First Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="first_name" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Middle Name</label>
                    <input type="text" class="form-control" name="middle_name">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Last Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="last_name" required>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="font-weight-bold">Indigency Category <span class="text-danger">*</span></label>
                    <select name="indigency_category" class="form-control" required>
                      <option value="">-- Select Category --</option>
                      <option value="4Ps Family">4Ps Family</option>
                      <option value="Senior Citizen">Senior Citizen</option>
                      <option value="PWD">PWD</option>
                      <option value="Solo Parent">Solo Parent</option>
                      <option value="Unemployed">Unemployed</option>
                      <option value="Homeless">Homeless</option>
                      <option value="Indigenous People">Indigenous People</option>
                      <option value="Single Mother">Single Mother</option>
                      <option value="Widow/Widower">Widow/Widower</option>
                      <option value="Out of School Youth">Out of School Youth</option>
                      <option value="Low Income Family">Low Income Family</option>
                      <option value="Disaster Victim">Disaster Victim</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="font-weight-bold">Assistance Type</label>
                    <select name="assistance_type" class="form-control">
                      <option value="">-- Select Assistance Type --</option>
                      <option value="Financial">Financial</option>
                      <option value="Medical">Medical</option>
                      <option value="Food">Food</option>
                      <option value="Burial">Burial</option>
                      <option value="Educational">Educational</option>
                      <option value="Transportation">Transportation</option>
                      <option value="Housing">Housing</option>
                      <option value="Livelihood">Livelihood</option>
                      <option value="Legal">Legal</option>
                      <option value="Counseling">Counseling</option>
                      <option value="Emergency Relief">Emergency Relief</option>
                      <option value="Utility Assistance">Utility Assistance</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="font-weight-bold">Assistance Amount (₱)</label>
                    <div class="input-group">
                      <div class="input-group-prepend"><span class="input-group-text">₱</span></div>
                      <input type="number" step="0.01" min="0" name="assistance_amount" class="form-control" placeholder="0.00" />
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="font-weight-bold">Status</label>
                    <select name="status" class="form-control">
                      <option value="Active">Active</option>
                      <option value="Inactive">Inactive</option>
                      <option value="Pending">Pending</option>
                      <option value="Completed">Completed</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="font-weight-bold">Date Assessed</label>
                    <input type="date" name="date_assessed" class="form-control" />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="font-weight-bold">Date Provided</label>
                    <input type="date" name="date_provided" class="form-control" />
                  </div>
                </div>
              </div>

            </div>
            <div class="modal-footer bg-light">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">
                <i class="fas fa-times-circle mr-1"></i> Cancel
              </button>
              <button type="submit" class="btn btn-primary" id="btnAddSave">
                <i class="fa fa-save mr-1"></i> Save Record
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- EDIT MODAL -->
    <div class="modal fade" id="editIndigentModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header bg-warning">
            <h5 class="modal-title text-dark"><i class="far fa-edit mr-2"></i>Edit Indigent Record</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <form id="editIndigentForm" autocomplete="off">
            <?= csrf_field() ?>
            <div class="modal-body">

              <input type="hidden" id="editIndigentId" name="id" />

              <!-- Name Fields Row for Edit -->
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>First Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="first_name" id="editFirstName" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Middle Name</label>
                    <input type="text" class="form-control" name="middle_name" id="editMiddleName">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Last Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="last_name" id="editLastName" required>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="font-weight-bold">Indigency Category</label>
                    <select name="indigency_category" id="editIndigencyCategory" class="form-control">
                      <option value="">-- Select Category --</option>
                      <option value="4Ps Family">4Ps Family</option>
                      <option value="Senior Citizen">Senior Citizen</option>
                      <option value="PWD">PWD</option>
                      <option value="Solo Parent">Solo Parent</option>
                      <option value="Unemployed">Unemployed</option>
                      <option value="Homeless">Homeless</option>
                      <option value="Indigenous People">Indigenous People</option>
                      <option value="Single Mother">Single Mother</option>
                      <option value="Widow/Widower">Widow/Widower</option>
                      <option value="Out of School Youth">Out of School Youth</option>
                      <option value="Low Income Family">Low Income Family</option>
                      <option value="Disaster Victim">Disaster Victim</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="font-weight-bold">Assistance Type</label>
                    <select name="assistance_type" id="editAssistanceType" class="form-control">
                      <option value="">-- Select Assistance Type --</option>
                      <option value="Financial">Financial</option>
                      <option value="Medical">Medical</option>
                      <option value="Food">Food</option>
                      <option value="Burial">Burial</option>
                      <option value="Educational">Educational</option>
                      <option value="Transportation">Transportation</option>
                      <option value="Housing">Housing</option>
                      <option value="Livelihood">Livelihood</option>
                      <option value="Legal">Legal</option>
                      <option value="Counseling">Counseling</option>
                      <option value="Emergency Relief">Emergency Relief</option>
                      <option value="Utility Assistance">Utility Assistance</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="font-weight-bold">Assistance Amount (₱)</label>
                    <div class="input-group">
                      <div class="input-group-prepend"><span class="input-group-text">₱</span></div>
                      <input type="number" step="0.01" min="0" name="assistance_amount"
                             id="editAssistanceAmount" class="form-control" placeholder="0.00" />
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="font-weight-bold">Status</label>
                    <select name="status" id="editIndigentStatus" class="form-control">
                      <option value="Active">Active</option>
                      <option value="Inactive">Inactive</option>
                      <option value="Pending">Pending</option>
                      <option value="Completed">Completed</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="font-weight-bold">Date Assessed</label>
                    <input type="date" name="date_assessed" id="editDateAssessed" class="form-control" />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="font-weight-bold">Date Provided</label>
                    <input type="date" name="date_provided" id="editDateProvided" class="form-control" />
                  </div>
                </div>
              </div>

            </div>
            <div class="modal-footer bg-light">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">
                <i class="fas fa-times-circle mr-1"></i> Cancel
              </button>
              <button type="submit" class="btn btn-warning" id="btnEditSave">
                <i class="fa fa-save mr-1"></i> Update Record
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- DELETE CONFIRM MODAL -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
          <div class="modal-header bg-danger">
            <h5 class="modal-title text-white"><i class="fas fa-exclamation-triangle mr-2"></i>Confirm Delete</h5>
            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body text-center">
            <p class="mb-1">Are you sure you want to delete this record?</p>
            <small class="text-muted">This action cannot be undone.</small>
          </div>
          <div class="modal-footer justify-content-center">
            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
              <i class="fas fa-times mr-1"></i>Cancel
            </button>
            <button type="button" class="btn btn-danger btn-sm" id="confirmDeleteBtn">
              <i class="fas fa-trash mr-1"></i>Delete
            </button>
          </div>
        </div>
      </div>
    </div>

  </section>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script> const baseUrl = "<?= base_url() ?>"; </script>
<script src="<?= base_url('js/indigents/indigents.js') ?>"></script>
<?= $this->endSection() ?>
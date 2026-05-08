<?= $this->extend('theme/template') ?>

<?= $this->section('content') ?>
<div class="content-wrapper">
  <!-- Content Header -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">
            <i class="fas fa-hand-holding-heart mr-2"></i>Indigent Assistance Records
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

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- Stats Cards Row -->
      <div class="row">
        <div class="col-lg-3 col-6">
          <div class="small-box bg-info">
            <div class="inner">
              <h3 id="totalIndigents">0</h3>
              <p>Total Records</p>
            </div>
            <div class="icon">
              <i class="fas fa-users"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-success">
            <div class="inner">
              <h3 id="activeIndigents">0</h3>
              <p>Active</p>
            </div>
            <div class="icon">
              <i class="fas fa-check-circle"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-warning">
            <div class="inner">
              <h3 id="pendingIndigents">0</h3>
              <p>Pending</p>
            </div>
            <div class="icon">
              <i class="fas fa-clock"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-primary">
            <div class="inner">
              <h3 id="totalAssistance">₱0</h3>
              <p>Total Assistance</p>
            </div>
            <div class="icon">
              <i class="fas fa-peso-sign"></i>
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
                <i class="fas fa-table mr-1"></i> Indigent Assistance Records
              </h3>
              <div class="card-tools">
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#AddNewModal">
                  <i class="fas fa-plus-circle mr-1"></i> Add New Record
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="indigentsTable" class="table table-bordered table-striped table-hover dataTable w-100">
                <thead class="thead-dark">
                  <tr>
                    <th width="5%">No.</th>
                    <th style="display:none;">id</th>
                    <th>Resident Name</th>
                    <th>Category</th>
                    <th>Assistance Type</th>
                    <th>Amount</th>
                    <th>Date Assessed</th>
                    <th>Date Provided</th>
                    <th width="10%">Status</th>
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

  <!-- ADD NEW MODAL -->
  <div class="modal fade" id="AddNewModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
        <form id="addIndigentForm" autocomplete="off">
          <?= csrf_field() ?>
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="addModalLabel">
              <i class="fas fa-user-plus mr-2"></i>Add Indigent Assistance Record
            </h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <!-- Personal Information Section -->
            <h6 class="text-primary mb-3"><i class="fas fa-user-circle mr-2"></i>Personal Information</h6>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label for="addFirstName">First Name <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-user"></i></span>
                    </div>
                    <input type="text" class="form-control" name="first_name" id="addFirstName" required>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="addMiddleName">Middle Name</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-user"></i></span>
                    </div>
                    <input type="text" class="form-control" name="middle_name" id="addMiddleName">
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="addLastName">Last Name <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-user"></i></span>
                    </div>
                    <input type="text" class="form-control" name="last_name" id="addLastName" required>
                  </div>
                </div>
              </div>
            </div>

            <!-- Assistance Details Section -->
            <h6 class="text-primary mb-3 mt-4"><i class="fas fa-hands-helping mr-2"></i>Assistance Details</h6>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="addIndigencyCategory">Indigency Category <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-tag"></i></span>
                    </div>
                    <select name="indigency_category" class="form-control" id="addIndigencyCategory" required>
                      <option value="">-- Select Category --</option>
                      <option value="4Ps Family">4Ps Family</option>
                      <option value="Senior Citizen">Senior Citizen</option>
                      <option value="PWD">PWD (Person with Disability)</option>
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
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="addAssistanceType">Assistance Type</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-hand-holding"></i></span>
                    </div>
                    <select name="assistance_type" class="form-control" id="addAssistanceType">
                      <option value="">-- Select Assistance Type --</option>
                      <option value="Financial">Financial Assistance</option>
                      <option value="Medical">Medical Assistance</option>
                      <option value="Food">Food Assistance</option>
                      <option value="Burial">Burial Assistance</option>
                      <option value="Educational">Educational Assistance</option>
                      <option value="Transportation">Transportation Assistance</option>
                      <option value="Housing">Housing Assistance</option>
                      <option value="Livelihood">Livelihood Assistance</option>
                      <option value="Legal">Legal Assistance</option>
                      <option value="Counseling">Counseling</option>
                      <option value="Emergency Relief">Emergency Relief</option>
                      <option value="Utility Assistance">Utility Assistance</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="addAssistanceAmount">Assistance Amount (₱)</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">₱</span>
                    </div>
                    <input type="number" step="0.01" min="0" name="assistance_amount" class="form-control" 
                           id="addAssistanceAmount" placeholder="0.00" />
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="addStatus">Status</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                    </div>
                    <select name="status" class="form-control" id="addStatus">
                      <option value="Active">Active</option>
                      <option value="Inactive">Inactive</option>
                      <option value="Pending">Pending</option>
                      <option value="Completed">Completed</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>

            <!-- Date Section -->
            <h6 class="text-primary mb-3 mt-4"><i class="fas fa-calendar-alt mr-2"></i>Dates</h6>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="addDateAssessed">Date Assessed</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-clipboard-check"></i></span>
                    </div>
                    <input type="date" name="date_assessed" class="form-control" id="addDateAssessed" />
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="addDateProvided">Date Provided</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                    </div>
                    <input type="date" name="date_provided" class="form-control" id="addDateProvided" />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer bg-light">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">
              <i class="fas fa-times-circle mr-1"></i> Cancel
            </button>
            <button type="submit" class="btn btn-primary" id="btnAddSave">
              <i class="fas fa-save mr-1"></i> Save Record
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- EDIT MODAL -->
  <div class="modal fade" id="editIndigentModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
        <form id="editIndigentForm" autocomplete="off">
          <?= csrf_field() ?>
          <div class="modal-header bg-warning">
            <h5 class="modal-title" id="editModalLabel">
              <i class="fas fa-user-edit mr-2"></i>Edit Indigent Assistance Record
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <input type="hidden" id="editIndigentId" name="id" />

            <!-- Personal Information Section -->
            <h6 class="text-primary mb-3"><i class="fas fa-user-circle mr-2"></i>Personal Information</h6>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label for="editFirstName">First Name <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-user"></i></span>
                    </div>
                    <input type="text" class="form-control" name="first_name" id="editFirstName" required>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="editMiddleName">Middle Name</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-user"></i></span>
                    </div>
                    <input type="text" class="form-control" name="middle_name" id="editMiddleName">
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="editLastName">Last Name <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-user"></i></span>
                    </div>
                    <input type="text" class="form-control" name="last_name" id="editLastName" required>
                  </div>
                </div>
              </div>
            </div>

            <!-- Assistance Details Section -->
            <h6 class="text-primary mb-3 mt-4"><i class="fas fa-hands-helping mr-2"></i>Assistance Details</h6>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="editIndigencyCategory">Indigency Category</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-tag"></i></span>
                    </div>
                    <select name="indigency_category" class="form-control" id="editIndigencyCategory">
                      <option value="">-- Select Category --</option>
                      <option value="4Ps Family">4Ps Family</option>
                      <option value="Senior Citizen">Senior Citizen</option>
                      <option value="PWD">PWD (Person with Disability)</option>
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
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="editAssistanceType">Assistance Type</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-hand-holding"></i></span>
                    </div>
                    <select name="assistance_type" class="form-control" id="editAssistanceType">
                      <option value="">-- Select Assistance Type --</option>
                      <option value="Financial">Financial Assistance</option>
                      <option value="Medical">Medical Assistance</option>
                      <option value="Food">Food Assistance</option>
                      <option value="Burial">Burial Assistance</option>
                      <option value="Educational">Educational Assistance</option>
                      <option value="Transportation">Transportation Assistance</option>
                      <option value="Housing">Housing Assistance</option>
                      <option value="Livelihood">Livelihood Assistance</option>
                      <option value="Legal">Legal Assistance</option>
                      <option value="Counseling">Counseling</option>
                      <option value="Emergency Relief">Emergency Relief</option>
                      <option value="Utility Assistance">Utility Assistance</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="editAssistanceAmount">Assistance Amount (₱)</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">₱</span>
                    </div>
                    <input type="number" step="0.01" min="0" name="assistance_amount"
                           id="editAssistanceAmount" class="form-control" placeholder="0.00" />
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="editIndigentStatus">Status</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                    </div>
                    <select name="status" class="form-control" id="editIndigentStatus">
                      <option value="Active">Active</option>
                      <option value="Inactive">Inactive</option>
                      <option value="Pending">Pending</option>
                      <option value="Completed">Completed</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>

            <!-- Date Section -->
            <h6 class="text-primary mb-3 mt-4"><i class="fas fa-calendar-alt mr-2"></i>Dates</h6>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="editDateAssessed">Date Assessed</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-clipboard-check"></i></span>
                    </div>
                    <input type="date" name="date_assessed" id="editDateAssessed" class="form-control" />
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="editDateProvided">Date Provided</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                    </div>
                    <input type="date" name="date_provided" id="editDateProvided" class="form-control" />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer bg-light">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">
              <i class="fas fa-times-circle mr-1"></i> Cancel
            </button>
            <button type="submit" class="btn btn-warning" id="btnEditSave">
              <i class="fas fa-save mr-1"></i> Update Record
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- DELETE CONFIRM MODAL -->
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
<script> const baseUrl = "<?= base_url() ?>"; </script>
<script src="<?= base_url('js/indigents/indigents.js') ?>"></script>
<?= $this->endSection() ?>
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
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">List of Indigent Records</h3>
              <div class="float-right">
                <button type="button" class="btn btn-md btn-primary" data-toggle="modal" data-target="#AddNewModal">
                  <i class="fa fa-plus-circle fa fw"></i> Add New
                </button>
              </div>
            </div>
            <div class="card-body">
              <table id="indigentsTable" class="table table-bordered table-striped table-sm">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th style="display:none;">id</th>
                    <th>Resident Name</th>
                    <th>Category</th>
                    <th>Assistance Type</th>
                    <th>Amount</th>
                    <th>Date Provided</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Add New Modal -->
    <div class="modal fade" id="AddNewModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <form id="addIndigentForm">
          <?= csrf_field() ?>
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title"><i class="fa fa-plus-circle fa fw"></i> Add Indigent Record</h5>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label>Resident ID <span class="text-danger">*</span></label>
                <input type="number" name="resident_id" class="form-control" required placeholder="Enter Resident ID" />
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Indigency Category <span class="text-danger">*</span></label>
                    <select name="indigency_category" class="form-control" required>
                      <option value="">-- Select --</option>
                      <option value="4Ps Family">4Ps Family</option>
                      <option value="Senior Citizen">Senior Citizen</option>
                      <option value="PWD">PWD</option>
                      <option value="Solo Parent">Solo Parent</option>
                      <option value="Unemployed">Unemployed</option>
                    </select>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Assistance Type</label>
                    <select name="assistance_type" class="form-control">
                      <option value="">-- Select --</option>
                      <option value="Financial">Financial</option>
                      <option value="Medical">Medical</option>
                      <option value="Food">Food</option>
                      <option value="Burial">Burial</option>
                      <option value="Educational">Educational</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Assistance Amount (₱)</label>
                    <input type="number" step="0.01" name="assistance_amount" class="form-control" />
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                      <option value="Active">Active</option>
                      <option value="Inactive">Inactive</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Date Assessed</label>
                    <input type="date" name="date_assessed" class="form-control" />
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Date Provided</label>
                    <input type="date" name="date_provided" class="form-control" />
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times-circle"></i> Cancel</button>
              <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editIndigentModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><i class="far fa-edit fa fw"></i> Edit Indigent Record</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <form id="editIndigentForm">
            <?= csrf_field() ?>
            <div class="modal-body">
              <input type="hidden" id="editIndigentId" name="id" />
              <div class="form-group">
                <label>Resident ID</label>
                <input type="number" name="resident_id" id="editResidentId" class="form-control" />
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Indigency Category</label>
                    <select name="indigency_category" id="editIndigencyCategory" class="form-control">
                      <option value="4Ps Family">4Ps Family</option>
                      <option value="Senior Citizen">Senior Citizen</option>
                      <option value="PWD">PWD</option>
                      <option value="Solo Parent">Solo Parent</option>
                      <option value="Unemployed">Unemployed</option>
                    </select>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Assistance Type</label>
                    <select name="assistance_type" id="editAssistanceType" class="form-control">
                      <option value="">-- Select --</option>
                      <option value="Financial">Financial</option>
                      <option value="Medical">Medical</option>
                      <option value="Food">Food</option>
                      <option value="Burial">Burial</option>
                      <option value="Educational">Educational</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Assistance Amount (₱)</label>
                    <input type="number" step="0.01" name="assistance_amount" id="editAssistanceAmount" class="form-control" />
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Status</label>
                    <select name="status" id="editIndigentStatus" class="form-control">
                      <option value="Active">Active</option>
                      <option value="Inactive">Inactive</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Date Assessed</label>
                    <input type="date" name="date_assessed" id="editDateAssessed" class="form-control" />
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Date Provided</label>
                    <input type="date" name="date_provided" id="editDateProvided" class="form-control" />
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times-circle"></i> Cancel</button>
              <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</div>
<div class="toasts-top-right fixed" style="position: fixed; top: 1rem; right: 1rem; z-index: 9999;"></div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script> const baseUrl = "<?= base_url() ?>"; </script>
<script src="<?= base_url('assets/js/indigents.js') ?>"></script>
<?= $this->endSection() ?>
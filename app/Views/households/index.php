<?= $this->extend('theme/template') ?>

<?= $this->section('content') ?>
<div class="content-wrapper">

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Households</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Households</li>
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
              <h3 class="card-title">List of Households</h3>
              <div class="float-right">
                <button type="button" class="btn btn-md btn-primary" data-toggle="modal" data-target="#AddNewModal">
                  <i class="fa fa-plus-circle fa-fw"></i> Add New
                </button>
              </div>
            </div>

            <div class="card-body">
              <table id="householdsTable" class="table table-bordered table-striped table-sm">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th style="display:none;">ID</th>
                    <th>Head of Household</th>
                    <th>Address</th>
                    <th>Purok /</th>
                    <th>Barangay</th>
                    <th>City / Municipality</th>
                    <th>Province</th>
                    <th>Zip Code</th>
                    <th>Members</th>
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

    <!-- ================= ADD MODAL ================= -->
    <div class="modal fade" id="AddNewModal" tabindex="-1">
      <div class="modal-dialog modal-lg">

        <form id="addHouseholdForm">
          <?= csrf_field() ?>

          <div class="modal-content">

            <div class="modal-header">
              <h5 class="modal-title">
                <i class="fa fa-plus-circle fa-fw"></i> Add New Household
              </h5>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">

              <!-- ROW 1 -->
              <div class="row">
                <div class="col-sm-8">
                  <div class="form-group">
                    <label>Head of Household *</label>
                    <input type="text" name="head_name" class="form-control" placeholder="Full name of household head" required>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Total Members</label>
                    <input type="number" name="total_members" class="form-control" value="1" min="1">
                  </div>
                </div>
              </div>

             <!-- ROW 2 -->
<div class="row">
  <div class="col-sm-8">
    <div class="form-group">
      <label>Address *</label>
      <input type="text" id="editAddressLine1" name="address_line1" class="form-control" required>
    </div>
  </div>
  <div class="col-sm-4">
    <div class="form-group">
      <label>Purok  <span class="text-danger">*</span></label>
      <select id="editPurok" name="purok" class="form-control" required>
        <option value="">Select Purok</option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
        <option value="7A">7A</option>
        <option value="7B">7B</option>
      </select>
    </div>
  </div>
</div>

              <!-- ROW 3 -->
              <div class="row">
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Barangay</label>
                    <input type="text" name="barangay" class="form-control">
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>City / Municipality</label>
                    <input type="text" name="city_municipality" class="form-control">
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Province</label>
                    <input type="text" name="province" class="form-control">
                  </div>
                </div>
              </div>

              <!-- ROW 4 -->
              <div class="row">
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Zip Code</label>
                    <input type="text" name="zip_code" class="form-control">
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                      <option value="Active">Active</option>
                      <option value="Inactive">Inactive</option>
                      <option value="Transferred">Transferred</option>
                    </select>
                  </div>
                </div>
              </div>

            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">
                <i class="fas fa-times-circle"></i> Cancel
              </button>
              <button type="submit" class="btn btn-primary">
                <i class="fa fa-save"></i> Save
              </button>
            </div>

          </div>
        </form>

      </div>
    </div>

    <!-- ================= EDIT MODAL ================= -->
    <div class="modal fade" id="editHouseholdModal" tabindex="-1">
      <div class="modal-dialog modal-lg">

        <form id="editHouseholdForm">
          <?= csrf_field() ?>

          <div class="modal-content">

            <div class="modal-header">
              <h5 class="modal-title">
                <i class="far fa-edit fa-fw"></i> Edit Household
              </h5>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">

              <input type="hidden" id="editHouseholdId" name="id">

              <!-- ROW 1 -->
              <div class="row">
                <div class="col-sm-8">
                  <div class="form-group">
                    <label>Head of Household *</label>
                    <input type="text" id="editHeadName" name="head_name" class="form-control" required>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Total Members</label>
                    <input type="number" id="editTotalMembers" name="total_members" class="form-control" min="1">
                  </div>
                </div>
              </div>

             <!-- ROW 2 -->
<div class="row">
  <div class="col-sm-8">
    <div class="form-group">
      <label>Address *</label>
      <input type="text" name="address_line1" class="form-control" placeholder="House No. / Block / Lot" required>
    </div>
  </div>
  <div class="col-sm-4">
    <div class="form-group">
      <label>Purok<span class="text-danger">*</span></label>
      <select name="purok" class="form-control" required>
        <option value="">Select Purok</option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
        <option value="7A">7A</option>
        <option value="7B">7B</option>
      </select>
    </div>
  </div>
</div>
              <!-- ROW 3 -->
              <div class="row">
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Barangay</label>
                    <input type="text" id="editBarangay" name="barangay" class="form-control">
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>City / Municipality</label>
                    <input type="text" id="editCityMunicipality" name="city_municipality" class="form-control">
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Province</label>
                    <input type="text" id="editProvince" name="province" class="form-control">
                  </div>
                </div>
              </div>

              <!-- ROW 4 -->
              <div class="row">
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Zip Code</label>
                    <input type="text" id="editZipCode" name="zip_code" class="form-control">
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Status</label>
                    <select id="editStatus" name="status" class="form-control">
                      <option value="Active">Active</option>
                      <option value="Inactive">Inactive</option>
                      <option value="Transferred">Transferred</option>
                    </select>
                  </div>
                </div>
              </div>

            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">
                <i class="fas fa-times-circle"></i> Cancel
              </button>
              <button type="submit" class="btn btn-primary">
                <i class="fa fa-save"></i> Save
              </button>
            </div>

          </div>
        </form>

      </div>
    </div>

  </section>
</div>

<div class="toasts-top-right fixed" style="position: fixed; top: 1rem; right: 1rem; z-index: 9999;"></div>
<?= $this->endSection() ?>


<?= $this->section('scripts') ?>
<script>
  const baseUrl = "<?= base_url() ?>";
</script>
<script src="<?= base_url('js/households/households.js') ?>"></script>
<?= $this->endSection() ?>

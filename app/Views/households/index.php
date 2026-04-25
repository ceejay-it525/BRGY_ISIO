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
                  <i class="fa fa-plus-circle fa fw"></i> Add New
                </button>
              </div>
            </div>
            <div class="card-body">
              <table id="householdsTable" class="table table-bordered table-striped table-sm">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th style="display:none;">id</th>
                    <th>Head Name</th>
                    <th>Address</th>
                    <th>City/Municipality</th>
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

    <!-- Add New Modal -->
    <div class="modal fade" id="AddNewModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <form id="addHouseholdForm">
          <?= csrf_field() ?>
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title"><i class="fa fa-plus-circle fa fw"></i> Add New Household</h5>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Head Name <span class="text-danger">*</span></label>
                    <input type="text" name="head_name" class="form-control" required />
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group">
                    <label>Total Members</label>
                    <input type="number" name="total_members" class="form-control" value="1" min="1" />
                  </div>
                </div>
                <div class="col-sm-3">
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
                <div class="col-sm-8">
                  <div class="form-group">
                    <label>Address Line 1 <span class="text-danger">*</span></label>
                    <input type="text" name="address_line1" class="form-control" required />
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Address Line 2</label>
                    <input type="text" name="address_line2" class="form-control" />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-3">
                  <div class="form-group">
                    <label>Barangay</label>
                    <input type="text" name="barangay" class="form-control" />
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group">
                    <label>City/Municipality</label>
                    <input type="text" name="city_municipality" class="form-control" />
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group">
                    <label>Province</label>
                    <input type="text" name="province" class="form-control" />
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group">
                    <label>ZIP Code</label>
                    <input type="text" name="zip_code" class="form-control" />
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
    <div class="modal fade" id="editHouseholdModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><i class="far fa-edit fa fw"></i> Edit Household</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <form id="editHouseholdForm">
            <?= csrf_field() ?>
            <div class="modal-body">
              <input type="hidden" id="editHouseholdId" name="id" />
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Head Name</label>
                    <input type="text" name="head_name" id="editHeadName" class="form-control" required />
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group">
                    <label>Total Members</label>
                    <input type="number" name="total_members" id="editTotalMembers" class="form-control" min="1" />
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group">
                    <label>Status</label>
                    <select name="status" id="editStatus" class="form-control">
                      <option value="Active">Active</option>
                      <option value="Inactive">Inactive</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-8">
                  <div class="form-group">
                    <label>Address Line 1</label>
                    <input type="text" name="address_line1" id="editAddressLine1" class="form-control" />
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Address Line 2</label>
                    <input type="text" name="address_line2" id="editAddressLine2" class="form-control" />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-3">
                  <div class="form-group">
                    <label>Barangay</label>
                    <input type="text" name="barangay" id="editBarangay" class="form-control" />
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group">
                    <label>City/Municipality</label>
                    <input type="text" name="city_municipality" id="editCityMunicipality" class="form-control" />
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group">
                    <label>Province</label>
                    <input type="text" name="province" id="editProvince" class="form-control" />
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group">
                    <label>ZIP Code</label>
                    <input type="text" name="zip_code" id="editZipCode" class="form-control" />
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
<script src="<?= base_url('assets/js/households.js') ?>"></script>
<?= $this->endSection() ?>
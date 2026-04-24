<?= $this->extend('theme/template') ?>

<?= $this->section('content') ?>
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Residents</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>">Home</a></li>
            <li class="breadcrumb-item active">Residents</li>
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
              <h3 class="card-title">List of Residents</h3>
              <div class="float-right">
                <button type="button" class="btn btn-md btn-primary" data-toggle="modal" data-target="#AddNewModal">
                  <i class="fa fa-plus-circle"></i> Add New
                </button>
              </div>
            </div>
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped table-sm">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th style="display:none;">id</th>
                    <th>Full Name</th>
                    <th>Gender</th>
                    <th>Birthdate</th>
                    <th>Civil Status</th>
                    <th>Purok</th>
                    <th>Contact</th>
                    <th>Voter</th>
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

    <!-- Add Modal -->
    <div class="modal fade" id="AddNewModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <form id="addResidentForm">
          <?= csrf_field() ?>
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title"><i class="fa fa-plus-circle"></i> Add New Resident</h5>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>First Name <span class="text-danger">*</span></label>
                    <input type="text" name="first_name" class="form-control" required />
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Middle Name</label>
                    <input type="text" name="middle_name" class="form-control" />
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group">
                    <label>Last Name <span class="text-danger">*</span></label>
                    <input type="text" name="last_name" class="form-control" required />
                  </div>
                </div>
                <div class="col-sm-1">
                  <div class="form-group">
                    <label>Suffix</label>
                    <input type="text" name="suffix" class="form-control" placeholder="Jr." />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Birthdate</label>
                    <input type="date" name="birthdate" class="form-control" />
                  </div>
                </div>
                <div class="col-sm-2">
                  <div class="form-group">
                    <label>Age</label>
                    <input type="number" name="age" class="form-control" min="0" max="150" />
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group">
                    <label>Gender</label>
                    <select name="gender" class="form-control">
                      <option value="Male">Male</option>
                      <option value="Female">Female</option>
                    </select>
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group">
                    <label>Civil Status</label>
                    <select name="civil_status" class="form-control">
                      <option value="Single">Single</option>
                      <option value="Married">Married</option>
                      <option value="Widowed">Widowed</option>
                      <option value="Separated">Separated</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Contact Number</label>
                    <input type="text" name="contact_number" class="form-control" />
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" />
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Occupation</label>
                    <input type="text" name="occupation" class="form-control" />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Purok</label>
                    <input type="text" name="purok" class="form-control" placeholder="e.g. Purok 1" />
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Voter Status</label>
                    <select name="voter_status" class="form-control">
                      <option value="Registered">Registered</option>
                      <option value="Not Registered">Not Registered</option>
                    </select>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                      <option value="Active">Active</option>
                      <option value="Inactive">Inactive</option>
                      <option value="Deceased">Deceased</option>
                      <option value="Transferred">Transferred</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label>Address</label>
                <textarea name="address" class="form-control" rows="2"></textarea>
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
    <div class="modal fade" id="editResidentModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><i class="far fa-edit"></i> Edit Resident</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <form id="editResidentForm">
            <?= csrf_field() ?>
            <div class="modal-body">
              <input type="hidden" id="residentId" name="id">
              <div class="row">
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>First Name <span class="text-danger">*</span></label>
                    <input type="text" id="edit_first_name" name="first_name" class="form-control" required />
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Middle Name</label>
                    <input type="text" id="edit_middle_name" name="middle_name" class="form-control" />
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group">
                    <label>Last Name <span class="text-danger">*</span></label>
                    <input type="text" id="edit_last_name" name="last_name" class="form-control" required />
                  </div>
                </div>
                <div class="col-sm-1">
                  <div class="form-group">
                    <label>Suffix</label>
                    <input type="text" id="edit_suffix" name="suffix" class="form-control" />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Birthdate</label>
                    <input type="date" id="edit_birthdate" name="birthdate" class="form-control" />
                  </div>
                </div>
                <div class="col-sm-2">
                  <div class="form-group">
                    <label>Age</label>
                    <input type="number" id="edit_age" name="age" class="form-control" />
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group">
                    <label>Gender</label>
                    <select id="edit_gender" name="gender" class="form-control">
                      <option value="Male">Male</option>
                      <option value="Female">Female</option>
                    </select>
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group">
                    <label>Civil Status</label>
                    <select id="edit_civil_status" name="civil_status" class="form-control">
                      <option value="Single">Single</option>
                      <option value="Married">Married</option>
                      <option value="Widowed">Widowed</option>
                      <option value="Separated">Separated</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Contact Number</label>
                    <input type="text" id="edit_contact_number" name="contact_number" class="form-control" />
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Email</label>
                    <input type="email" id="edit_email" name="email" class="form-control" />
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Occupation</label>
                    <input type="text" id="edit_occupation" name="occupation" class="form-control" />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Purok</label>
                    <input type="text" id="edit_purok" name="purok" class="form-control" />
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Voter Status</label>
                    <select id="edit_voter_status" name="voter_status" class="form-control">
                      <option value="Registered">Registered</option>
                      <option value="Not Registered">Not Registered</option>
                    </select>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Status</label>
                    <select id="edit_status" name="status" class="form-control">
                      <option value="Active">Active</option>
                      <option value="Inactive">Inactive</option>
                      <option value="Deceased">Deceased</option>
                      <option value="Transferred">Transferred</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label>Address</label>
                <textarea id="edit_address" name="address" class="form-control" rows="2"></textarea>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times-circle"></i> Cancel</button>
              <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Changes</button>
            </div>
          </form>
        </div>
      </div>
    </div>

  </section>
</div>
<div class="toasts-top-right fixed" style="position:fixed;top:1rem;right:1rem;z-index:9999;"></div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>const baseUrl = "<?= base_url() ?>";</script>
<script src="<?= base_url('js/residents/residents.js') ?>"></script>
<?= $this->endSection() ?>
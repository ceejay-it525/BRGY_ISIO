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
            <li class="breadcrumb-item"><a href="#">Home</a></li>
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
                  <i class="fa fa-plus-circle fa fw"></i> Add New
                </button>
              </div>
            </div>

            <div class="card-body">
              <table id="residentsTable" class="table table-bordered table-striped table-sm">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th style="display:none;">ID</th>
                    <th>Full Name</th>
                    <th>Birthdate</th>
                    <th>Gender</th>
                    <th>Civil Status</th>
                    <th>Voter</th>
                    <th>Voter ID</th>
                    <th>Household ID</th>
                    <th>Address</th>
                    <th>Barangay</th>
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

        <form id="addResidentForm">
          <?= csrf_field() ?>

          <div class="modal-content">

            <div class="modal-header">
              <h5 class="modal-title">
                <i class="fa fa-plus-circle fa fw"></i> Add New Resident
              </h5>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">

              <div class="row">
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>First Name *</label>
                    <input type="text" name="first_name" class="form-control" required>
                  </div>
                </div>

                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Middle Name</label>
                    <input type="text" name="middle_name" class="form-control">
                  </div>
                </div>

                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Last Name *</label>
                    <input type="text" name="last_name" class="form-control" required>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-sm-3">
                  <div class="form-group">
                    <label>Suffix</label>
                    <input type="text" name="suffix" class="form-control">
                  </div>
                </div>

                <div class="col-sm-3">
                  <div class="form-group">
                    <label>Birthdate *</label>
                    <input type="date" name="birthdate" class="form-control" required>
                  </div>
                </div>

                <div class="col-sm-3">
                  <div class="form-group">
                    <label>Gender *</label>
                    <select name="gender" class="form-control" required>
                      <option value="">-- Select --</option>
                      <option value="Male">Male</option>
                      <option value="Female">Female</option>
                      <option value="Other">Other</option>
                    </select>
                  </div>
                </div>

                <div class="col-sm-3">
                  <div class="form-group">
                    <label>Civil Status *</label>
                    <select name="civil_status" class="form-control" required>
                      <option value="">-- Select --</option>
                      <option value="Single">Single</option>
                      <option value="Married">Married</option>
                      <option value="Widowed">Widowed</option>
                      <option value="Divorced">Divorced</option>
                      <option value="Separated">Separated</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-sm-3">
                  <div class="form-group">
                    <label>Voter?</label>
                    <select name="is_voter" class="form-control">
                      <option value="0">No</option>
                      <option value="1">Yes</option>
                    </select>
                  </div>
                </div>

                <div class="col-sm-3">
                  <div class="form-group">
                    <label>Voter ID</label>
                    <input type="text" name="voter_id" class="form-control">
                  </div>
                </div>

                <div class="col-sm-3">
                  <div class="form-group">
                    <label>Household ID</label>
                    <input type="number" name="household_id" class="form-control">
                  </div>
                </div>

                <div class="col-sm-3">
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

              <div class="row">
                <div class="col-sm-8">
                  <div class="form-group">
                    <label>Address *</label>
                    <input type="text" name="address_line1" class="form-control" required>
                  </div>
                </div>

                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Barangay</label>
                    <input type="text" name="barangay" class="form-control">
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
    <div class="modal fade" id="editResidentModal" tabindex="-1">
      <div class="modal-dialog modal-lg">

        <form id="editResidentForm">
          <?= csrf_field() ?>

          <div class="modal-content">

            <div class="modal-header">
              <h5 class="modal-title">
                <i class="far fa-edit fa fw"></i> Edit Resident
              </h5>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">

              <input type="hidden" id="editResidentId" name="id">

              <!-- ROW 1 -->
              <div class="row">
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>First Name *</label>
                    <input type="text" id="editFirstName" name="first_name" class="form-control" required>
                  </div>
                </div>

                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Middle Name</label>
                    <input type="text" id="editMiddleName" name="middle_name" class="form-control">
                  </div>
                </div>

                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Last Name *</label>
                    <input type="text" id="editLastName" name="last_name" class="form-control" required>
                  </div>
                </div>
              </div>

              <!-- ROW 2 -->
              <div class="row">
                <div class="col-sm-3">
                  <div class="form-group">
                    <label>Suffix</label>
                    <input type="text" id="editSuffix" name="suffix" class="form-control">
                  </div>
                </div>

                <div class="col-sm-3">
                  <div class="form-group">
                    <label>Birthdate *</label>
                    <input type="date" id="editBirthdate" name="birthdate" class="form-control" required>
                  </div>
                </div>

                <div class="col-sm-3">
                  <div class="form-group">
                    <label>Gender *</label>
                    <select id="editGender" name="gender" class="form-control" required>
                      <option value="">-- Select --</option>
                      <option value="Male">Male</option>
                      <option value="Female">Female</option>
                      <option value="Other">Other</option>
                    </select>
                  </div>
                </div>

                <div class="col-sm-3">
                  <div class="form-group">
                    <label>Civil Status *</label>
                    <select id="editCivilStatus" name="civil_status" class="form-control" required>
                      <option value="">-- Select --</option>
                      <option value="Single">Single</option>
                      <option value="Married">Married</option>
                      <option value="Widowed">Widowed</option>
                      <option value="Divorced">Divorced</option>
                      <option value="Separated">Separated</option>
                    </select>
                  </div>
                </div>
              </div>

              <!-- ROW 3 -->
              <div class="row">
                <div class="col-sm-3">
                  <div class="form-group">
                    <label>Voter?</label>
                    <select id="editIsVoter" name="is_voter" class="form-control">
                      <option value="0">No</option>
                      <option value="1">Yes</option>
                    </select>
                  </div>
                </div>

                <div class="col-sm-3">
                  <div class="form-group">
                    <label>Voter ID</label>
                    <input type="text" id="editVoterId" name="voter_id" class="form-control">
                  </div>
                </div>

                <div class="col-sm-3">
                  <div class="form-group">
                    <label>Household ID</label>
                    <input type="number" id="editHouseholdId" name="household_id" class="form-control">
                  </div>
                </div>

                <div class="col-sm-3">
                  <div class="form-group">
                    <label>Status</label>
                    <select id="editStatus" name="status" class="form-control">
                      <option value="Active">Active</option>
                      <option value="Inactive">Inactive</option>
                      <option value="Deceased">Deceased</option>
                      <option value="Transferred">Transferred</option>
                    </select>
                  </div>
                </div>
              </div>

              <!-- ROW 4 -->
              <div class="row">
                <div class="col-sm-8">
                  <div class="form-group">
                    <label>Address *</label>
                    <input type="text" id="editAddressLine1" name="address_line1" class="form-control" required>
                  </div>
                </div>

                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Barangay</label>
                    <input type="text" id="editBarangay" name="barangay" class="form-control">
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
<script src="<?= base_url('js/residents/residents.js') ?>"></script>
<?= $this->endSection() ?>
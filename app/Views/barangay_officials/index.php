<?= $this->extend('theme/template') ?>

<?= $this->section('content') ?>
<div class="content-wrapper">

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Barangay Officials</h1>
        </div>

        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Officials</li>
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
              <h3 class="card-title">List of Barangay Officials</h3>

              <div class="float-right">
                <button type="button" class="btn btn-md btn-primary" data-toggle="modal" data-target="#AddNewModal">
                  <i class="fa fa-plus-circle fa fw"></i> Add New
                </button>
              </div>
            </div>

            <div class="card-body">
              <table id="officialsTable" class="table table-bordered table-striped table-sm">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th style="display:none;">id</th>
                    <th>Full Name</th>
                    <th>Position</th>
                    <th>Term Start</th>
                    <th>Term End</th>
                    <th>Contact</th>
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

        <form id="addOfficialForm">
          <?= csrf_field() ?>

          <div class="modal-content">

            <div class="modal-header">
              <h5 class="modal-title">
                <i class="fa fa-plus-circle fa fw"></i> Add New Official
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
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Position *</label>
                    <select name="position" class="form-control" required>
                      <option value="">-- Select Position --</option>
                      <option value="Barangay Captain">Barangay Captain</option>
                      <option value="Barangay Councilor">Barangay Councilor</option>
                      <option value="SK Chairman">SK Chairman</option>
                      <option value="SK Councilor">SK Councilor</option>
                      <option value="Secretary">Secretary</option>
                      <option value="Treasurer">Treasurer</option>
                    </select>
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
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Term Start *</label>
                    <input type="date" name="term_start" class="form-control" required>
                  </div>
                </div>

                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Term End</label>
                    <input type="date" name="term_end" class="form-control">
                  </div>
                </div>

                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Contact</label>
                    <input type="text" name="contact_number" class="form-control">
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control">
                  </div>
                </div>

                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Address</label>
                    <input type="text" name="address" class="form-control">
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
    <div class="modal fade" id="editOfficialModal" tabindex="-1">
      <div class="modal-dialog modal-lg">

        <form id="editOfficialForm">
          <?= csrf_field() ?>

          <div class="modal-content">

            <div class="modal-header">
              <h5 class="modal-title">
                <i class="far fa-edit fa fw"></i> Edit Official
              </h5>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">

              <input type="hidden" id="editOfficialId" name="id">

              <div class="row">
                <div class="col-sm-4">
                  <input type="text" id="editFirstName" name="first_name" class="form-control">
                </div>

                <div class="col-sm-4">
                  <input type="text" id="editMiddleName" name="middle_name" class="form-control">
                </div>

                <div class="col-sm-4">
                  <input type="text" id="editLastName" name="last_name" class="form-control">
                </div>
              </div>

              <!-- keep remaining fields same structure as add -->

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
<script src="<?= base_url('js/barangay_officials/barangay_officials.js') ?>"></script>
<?= $this->endSection() ?>
<?= $this->extend('theme/template') ?>

<?= $this->section('content') ?>
<style>
/* ENHANCED CONTENT STYLES */
.content-wrapper {
    background: linear-gradient(145deg, #f8fafc 0%, #ffffff 100%);
    min-height: calc(100vh - 60px);
    transition: all 0.3s ease;
}

body.dark-mode .content-wrapper {
    background: linear-gradient(145deg, #0f0f23 0%, #1a1a2e 100%);
}

/* MODERN CONTENT HEADER */
.content-header {
    background: rgba(255,255,255,0.9);
    backdrop-filter: blur(20px);
    border-bottom: 1px solid rgba(0,0,0,0.05);
    padding: 24px 0;
    margin-bottom: 30px;
    box-shadow: 0 2px 20px rgba(0,0,0,0.08);
}

body.dark-mode .content-header {
    background: rgba(26,26,46,0.95);
    border-bottom: 1px solid rgba(255,255,255,0.1);
    box-shadow: 0 2px 25px rgba(0,0,0,0.5);
}

.content-header h1 {
    font-family: 'Poppins', sans-serif;
    font-weight: 700;
    font-size: 2.2rem;
    color: #1a3c6e;
    margin: 0;
    letter-spacing: -0.5px;
    background: linear-gradient(135deg, #1a3c6e, #2d6a9f);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

body.dark-mode .content-header h1 {
    background: linear-gradient(135deg, #ffffff, #e2e8f0);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.breadcrumb {
    background: none;
    padding: 0;
    margin: 0;
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
}

.breadcrumb-item a {
    color: #64748b;
    text-decoration: none;
    transition: all 0.3s ease;
}

.breadcrumb-item a:hover {
    color: #ff6b35;
    text-shadow: 0 0 8px rgba(255,107,53,0.3);
}

.breadcrumb-item.active {
    color: #1a3c6e;
    font-weight: 600;
}

body.dark-mode .breadcrumb-item a { color: #94a3b8; }
body.dark-mode .breadcrumb-item.active { color: #e2e8f0; }

/* PREMIUM CARD DESIGN */
.card {
    border: none;
    border-radius: 20px;
    box-shadow: 
        0 20px 60px rgba(0,0,0,0.12),
        0 8px 25px rgba(0,0,0,0.08),
        inset 0 1px 0 rgba(255,255,255,0.6);
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(20px);
}

body.dark-mode .card {
    background: rgba(26,26,46,0.95);
    box-shadow: 
        0 20px 60px rgba(0,0,0,0.6),
        0 8px 25px rgba(0,0,0,0.4),
        inset 0 1px 0 rgba(255,255,255,0.08);
}

.card:hover {
    transform: translateY(-8px);
    box-shadow: 
        0 30px 80px rgba(0,0,0,0.18),
        0 12px 35px rgba(0,0,0,0.12) !important;
}

.card-header {
    background: linear-gradient(135deg, #1a3c6e, #2d6a9f);
    color: white;
    border: none;
    padding: 20px 28px;
    position: relative;
    overflow: hidden;
}

.card-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.6s;
}

.card:hover .card-header::before {
    left: 100%;
}

.card-title {
    font-family: 'Poppins', sans-serif;
    font-weight: 700;
    font-size: 1.4rem;
    margin: 0;
    letter-spacing: 0.5px;
}

/* ENHANCED BUTTONS */
.btn {
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
    border-radius: 12px;
    padding: 12px 28px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: none;
    position: relative;
    overflow: hidden;
}

.btn-primary {
    background: linear-gradient(135deg, #ff6b35, #f7931e);
    box-shadow: 0 8px 25px rgba(255,107,53,0.4);
}

.btn-primary:hover {
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 15px 40px rgba(255,107,53,0.6);
    background: linear-gradient(135deg, #f57c00, #ff6b35);
}

.btn-secondary {
    background: linear-gradient(135deg, #6b7280, #9ca3af);
    box-shadow: 0 8px 25px rgba(107,114,128,0.4);
}

.btn-secondary:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 40px rgba(107,114,128,0.6);
}

/* PREMIUM TABLE */
.table {
    background: rgba(255,255,255,0.8);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    margin: 0;
}

body.dark-mode .table {
    background: rgba(26,26,46,0.8);
    box-shadow: 0 10px 30px rgba(0,0,0,0.5);
}

.table thead th {
    background: linear-gradient(135deg, #1a3c6e, #2d6a9f);
    color: white;
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    border: none;
    padding: 18px 16px;
}

.table tbody td {
    padding: 16px;
    vertical-align: middle;
    border-color: rgba(0,0,0,0.05);
    font-family: 'Poppins', sans-serif;
}

.table-striped tbody tr:nth-of-type(odd) {
    background: rgba(255,107,53,0.03);
}

body.dark-mode .table-striped tbody tr:nth-of-type(odd) {
    background: rgba(255,107,53,0.08);
}

.table tbody tr:hover {
    background: linear-gradient(90deg, rgba(255,107,53,0.1), rgba(247,147,30,0.05)) !important;
    transform: scale(1.01);
}

/* MODAL ENHANCEMENTS */
.modal-content {
    border: none;
    border-radius: 24px;
    box-shadow: 
        0 40px 100px rgba(0,0,0,0.3),
        0 20px 50px rgba(0,0,0,0.2);
    overflow: hidden;
}

.modal-header {
    background: linear-gradient(135deg, #1a3c6e, #2d6a9f);
    color: white;
    border: none;
    padding: 24px 28px;
}

.modal-title {
    font-family: 'Poppins', sans-serif;
    font-weight: 700;
    font-size: 1.3rem;
}

.modal-body {
    padding: 28px;
    background: rgba(255,255,255,0.95);
}

body.dark-mode .modal-body {
    background: rgba(26,26,46,0.95);
}

.form-control {
    border: 2px solid rgba(0,0,0,0.08);
    border-radius: 12px;
    padding: 14px 18px;
    font-family: 'Poppins', sans-serif;
    font-weight: 500;
    transition: all 0.3s ease;
    background: rgba(255,255,255,0.8);
}

.form-control:focus {
    border-color: #ff6b35;
    box-shadow: 0 0 0 4px rgba(255,107,53,0.15);
    transform: translateY(-2px);
    background: white;
}

body.dark-mode .form-control {
    background: rgba(40,40,60,0.8);
    border-color: rgba(255,255,255,0.1);
    color: #e2e8f0;
}

body.dark-mode .form-control:focus {
    background: rgba(50,50,70,0.9);
    box-shadow: 0 0 0 4px rgba(255,107,53,0.25);
}

/* TOASTS */
.toasts-top-right .toast {
    border-radius: 16px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    backdrop-filter: blur(20px);
}
</style>

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
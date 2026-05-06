<?= $this->extend('theme/template') ?>
<?= $this->section('content') ?>
<style>
  .dataTables_filter, .dataTables_length { display: none !important; }

  .bg-gradient-primary { background: linear-gradient(135deg, #4a93e5, #1c5db8) !important; }
  .text-white-75 { color: rgba(255,255,255,0.75) !important; }

  #selectedAvatar img { width:100%; height:100%; object-fit:cover; border-radius:50%; }
  .official-card-avatar { width:54px; height:54px; object-fit:cover; border-radius:50%; }
  .btn-light.text-primary { color: #1c5db8 !important; }

  /* ── redesigned modal shared styles ── */
  .modal-content { border: none; border-radius: 12px; overflow: hidden; }

  .modal-header-custom {
    background: #1c5db8;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: none;
  }
  .modal-header-custom .mh-left { display: flex; align-items: center; gap: 10px; }
  .modal-header-custom .mh-icon {
    width: 34px; height: 34px; border-radius: 8px;
    background: rgba(255,255,255,0.15);
    display: flex; align-items: center; justify-content: center;
  }
  .modal-header-custom .mh-icon i { color: #fff; font-size: 15px; }
  .modal-header-custom .mh-title { font-size: 14px; font-weight: 500; color: #fff; margin: 0; }
  .modal-header-custom .mh-sub { font-size: 11px; color: rgba(255,255,255,0.55); margin: 2px 0 0; }
  .modal-header-custom .mh-close {
    width: 26px; height: 26px; border-radius: 50%;
    background: rgba(255,255,255,0.15); border: none;
    color: #fff; font-size: 15px; line-height: 1;
    display: flex; align-items: center; justify-content: center; cursor: pointer;
  }

  .modal-steps {
    display: flex; align-items: center; gap: 0;
    padding: 0 20px;
    background: #f8f9fb;
    border-bottom: 1px solid #e9ecef;
  }
  .modal-steps .mstep {
    display: flex; align-items: center; gap: 7px;
    padding: 10px 0; margin-right: 20px;
    font-size: 12px; color: #6c757d;
    border-bottom: 2px solid transparent;
  }
  .modal-steps .mstep.active { color: #1c5db8; border-bottom-color: #1c5db8; }
  .modal-steps .mstep-num {
    width: 20px; height: 20px; border-radius: 50%;
    background: #dee2e6; display: flex; align-items: center;
    justify-content: center; font-size: 10px; font-weight: 600; color: #6c757d;
  }
  .modal-steps .mstep.active .mstep-num { background: #1c5db8; color: #fff; }

  .modal-section-label {
    font-size: 10px; font-weight: 600;
    letter-spacing: 0.08em; color: #adb5bd;
    text-transform: uppercase;
    margin: 16px 0 10px;
    display: flex; align-items: center; gap: 8px;
  }
  .modal-section-label::after {
    content: ''; flex: 1; height: 1px; background: #e9ecef;
  }

  .modal-body { padding: 4px 20px 16px; }

  .modal-body .form-group { margin-bottom: 12px; }
  .modal-body .form-group label {
    font-size: 11px; font-weight: 600;
    color: #495057; margin-bottom: 5px; display: block;
  }
  .modal-body .form-group label .req { color: #E24B4A; }
  .modal-body .form-control {
    height: 34px; font-size: 13px;
    border: 1px solid #ced4da; border-radius: 6px;
    padding: 0 10px;
  }
  .modal-body .form-control:focus {
    border-color: #1c5db8;
    box-shadow: 0 0 0 3px rgba(28,93,184,0.1);
  }
  .modal-body .form-text { font-size: 10px; color: #adb5bd; margin-top: 3px; }

  .upload-zone {
    border: 1px dashed #ced4da; border-radius: 6px;
    padding: 14px 10px; text-align: center; cursor: pointer;
    background: #f8f9fb; transition: border-color .15s;
  }
  .upload-zone:hover { border-color: #1c5db8; }
  .upload-zone i { font-size: 20px; color: #adb5bd; display: block; margin-bottom: 4px; }
  .upload-zone span { font-size: 11px; color: #6c757d; display: block; }
  .upload-zone small { font-size: 10px; color: #adb5bd; }
  .upload-zone input[type=file] { display: none; }

  .modal-footer-custom {
    padding: 12px 20px;
    border-top: 1px solid #e9ecef;
    background: #f8f9fb;
    display: flex; align-items: center; justify-content: space-between;
  }
  .modal-footer-custom .f-hint { font-size: 11px; color: #adb5bd; }
  .modal-footer-custom .f-hint span { color: #E24B4A; }
</style>

<!-- (keep all existing page HTML unchanged up to the modals) -->
<div class="content-wrapper">

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Barangay Officials</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>">Home</a></li>
            <li class="breadcrumb-item active">Officials</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

      <div class="row mb-4">
        <div class="col-12">
          <div class="bg-gradient-primary rounded-lg shadow-sm p-4 d-flex flex-column flex-md-row justify-content-between align-items-start">
            <div>
              <h2 class="text-white mb-1">Barangay Officials</h2>
              <p class="text-white-75 mb-0">View, search, and manage your barangay officials in one place.</p>
            </div>
            <button type="button" class="btn btn-light btn-md text-primary" data-toggle="modal" data-target="#AddNewModal">
              <i class="fa fa-plus-circle"></i> Add New Official
            </button>
          </div>
        </div>
      </div>

      <div class="row mb-4">
        <div class="col-12">
          <div class="card shadow-sm border-0">
            <div class="card-body pb-0">
              <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
                <div>
                  <h3 class="card-title mb-1">Officials Directory</h3>
                  <p class="text-muted mb-0">Browse elected officials, view full details, and manage assignments.</p>
                </div>
                <div class="input-group" style="max-width: 420px; width: 100%;">
                  <input id="officialsSearch" type="search" class="form-control" placeholder="Search officials..." aria-label="Search officials">
                  <div class="input-group-append">
                    <button id="officialsSearchBtn" class="btn btn-secondary" type="button"><i class="fas fa-search"></i></button>
                  </div>
                </div>
              </div>
            </div>

            <div class="card-body pt-0">
              <div class="row">
                <div class="col-lg-4 mb-4">
                  <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                      <div class="text-center mb-4">
                        <div id="selectedAvatar" class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width:110px;height:110px;font-size:28px;"></div>
                        <h4 id="selectedName" class="mt-3 mb-1"></h4>
                        <p id="selectedPosition" class="text-muted mb-0"></p>
                      </div>
                      <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0 d-flex justify-content-between">
                          <span>Status</span><span id="selectedStatus" class="font-weight-bold"></span>
                        </li>
                        <li class="list-group-item px-0 d-flex justify-content-between">
                          <span>Term</span><span id="selectedTerm" class="font-weight-bold"></span>
                        </li>
                        <li class="list-group-item px-0 d-flex justify-content-between">
                          <span>Contact</span><span id="selectedContact"></span>
                        </li>
                        <li class="list-group-item px-0 d-flex justify-content-between">
                          <span>Email</span><span id="selectedEmail"></span>
                        </li>
                        <li class="list-group-item px-0">
                          <strong>Address</strong>
                          <div id="selectedAddress" class="text-muted"></div>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
                <div class="col-lg-8">
                  <div id="officialCards" class="row g-3"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <input type="hidden" id="csrfTokenField" name="csrf_test_name" value="<?= csrf_hash() ?>">

    </div>

<!-- ================= ADD MODAL ================= -->
<div class="modal fade" id="AddNewModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form id="addOfficialForm" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <div class="modal-content">

        <!-- Header -->
        <div class="modal-header-custom">
          <div class="mh-left">
            <div class="mh-icon"><i class="fa fa-user-plus"></i></div>
            <div>
              <p class="mh-title">Add new barangay official</p>
              <p class="mh-sub">Fill in all required fields to register an official</p>
            </div>
          </div>
          <button type="button" class="mh-close" data-dismiss="modal">&#215;</button>
        </div>

        <!-- Step indicators -->
        <div class="modal-steps">
          <div class="mstep active"><div class="mstep-num">1</div> Personal info</div>
          <div class="mstep"><div class="mstep-num">2</div> Position &amp; term</div>
          <div class="mstep"><div class="mstep-num">3</div> Contact &amp; status</div>
        </div>

        <div class="modal-body">

          <div class="modal-section-label">Full name</div>
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label>First name <span class="req">*</span></label>
                <input type="text" name="first_name" class="form-control" placeholder="e.g. Juan" required>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label>Middle name</label>
                <input type="text" name="middle_name" class="form-control" placeholder="Optional">
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label>Last name <span class="req">*</span></label>
                <input type="text" name="last_name" class="form-control" placeholder="e.g. dela Cruz" required>
              </div>
            </div>
          </div>

          <div class="modal-section-label">Position &amp; term</div>
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label>Position <span class="req">*</span></label>
                <select id="addPosition" name="position" class="form-control position-select" required>
                  <option value="">Select position</option>
                  <option>Barangay Captain</option>
                  <option>Barangay Councilor</option>
                  <option>SK Chairman</option>
                  <option>SK Councilor</option>
                  <option>Secretary</option>
                  <option>Treasurer</option>
                  <option>Purok President</option>
                  <option>Kagawad</option>
                </select>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                  <option>Active</option>
                  <option>Inactive</option>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label>Term start <span class="req">*</span></label>
                <input type="date" name="term_start" class="form-control" required>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label>Term end</label>
                <input type="date" name="term_end" class="form-control">
                <small class="form-text">Leave blank if currently serving</small>
              </div>
            </div>
          </div>

          <div class="modal-section-label">Contact &amp; address</div>
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label>Contact number</label>
                <input type="text" name="contact_number" class="form-control" placeholder="09171234567">
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label>Email address</label>
                <input type="email" name="email" class="form-control" placeholder="official@example.com">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-8">
              <div class="form-group">
                <label>Home address</label>
                <input type="text" name="address" class="form-control" placeholder="Purok, Sitio, Barangay">
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label>Photo</label>
                <label class="upload-zone w-100">
                  <input type="file" name="photo" accept="image/*">
                  <i class="fas fa-cloud-upload-alt"></i>
                  <span>Click to upload</span>
                  <small>JPG, PNG, GIF — max 2MB</small>
                </label>
              </div>
            </div>
          </div>

        </div>

        <div class="modal-footer-custom">
          <span class="f-hint">Fields marked <span>*</span> are required</span>
          <div>
            <button type="button" class="btn btn-secondary btn-sm mr-2" data-dismiss="modal">
              <i class="fas fa-times-circle"></i> Cancel
            </button>
            <button type="submit" class="btn btn-primary btn-sm">
              <i class="fa fa-save"></i> Save official
            </button>
          </div>
        </div>

      </div>
    </form>
  </div>
</div>
<div class="toasts-top-right fixed" style="position:fixed;top:1rem;right:1rem;z-index:9999;"></div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  (function() {
    const path = window.location.pathname;
    const basePath = window.location.origin + path.replace(/\/[^\/]+\/?$/, '/');
    window.baseUrl = basePath;
    const script = document.createElement('script');
    script.src = basePath + '../js/barangay_officials/barangay_officials.js';
    script.defer = true;
    document.body.appendChild(script);
  })();
</script>
<?= $this->endSection() ?>

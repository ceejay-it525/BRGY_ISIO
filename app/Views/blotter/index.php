<?= $this->extend('theme/template') ?>

<?= $this->section('content') ?>
<div class="content-wrapper">

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Blotter Records</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Blotter</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

      <!-- Persistent CSRF token for AJAX requests -->
      <?= csrf_field() ?>

      <style>
        #blotterTable tbody tr.selected { background: #dce9ff; }
        .detail-panel th { width: 40%; }
      </style>

      <div class="row">
        <div class="col-lg-8">
          <div class="card">

            <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
              <h3 class="card-title">Blotter Records</h3>
              <button type="button" class="btn btn-md btn-primary" data-toggle="modal" data-target="#addBlotterModal">
                <i class="fa fa-plus-circle fa-fw"></i> Add New
              </button>
            </div>

            <div class="card-body">
              <div class="row mb-3">
                <div class="col-md-4 mb-2 mb-md-0">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text bg-white">Search by</span>
                    </div>
                    <select id="blotterSearchType" class="form-control">
                      <option value="all">ALL</option>
                      <option value="case_number">Brgy. Case #</option>
                      <option value="incident_type">Accusation</option>
                      <option value="complainant_name">Complainant</option>
                      <option value="respondent_name">Suspect</option>
                      <option value="incident_location">Location</option>
                      <option value="status">Status</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6 mb-2 mb-md-0">
                  <div class="input-group">
                    <input id="blotterSearchInput" type="text" class="form-control" placeholder="Type and press Enter or click Search">
                    <div class="input-group-append">
                      <button id="blotterSearchBtn" class="btn btn-secondary" type="button">Search</button>
                    </div>
                  </div>
                </div>
                <div class="col-md-2 text-right">
                  <button id="blotterResetSearch" class="btn btn-outline-secondary btn-block" type="button">Reset</button>
                </div>
              </div>

              <div class="table-responsive">
                <table id="blotterTable" class="table table-bordered table-striped table-sm table-hover">
                  <thead>
                    <tr>
                      <th width="5%">No.</th>
                      <th style="display:none;">ID</th>
                      <th>Case No.</th>
                      <th>Complainant</th>
                      <th>Suspect</th>
                      <th>Accusation</th>
                      <th>Date</th>
                      <th>Location</th>
                      <th width="10%">Status</th>
                      <th width="10%" class="text-center">Actions</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>
            </div>

          </div>
        </div>

        <div class="col-lg-4">
          <div class="card card-info">
            <div class="card-header">
              <h3 class="card-title">Complaint vs Respondent</h3>
            </div>
            <div class="card-body">
              <div class="mb-3 d-flex justify-content-between align-items-center">
                <strong>Selected Record</strong>
                <span id="selectedRecordLabel" class="text-muted">None</span>
              </div>

              <table class="table table-sm table-borderless detail-panel mb-3">
                <tbody>
                  <tr><th>Case #</th><td id="detailCaseNumber">—</td></tr>
                  <tr><th>Accusation</th><td id="detailIncidentType">—</td></tr>
                  <tr><th>Complainant</th><td id="detailComplainantName">—</td></tr>
                  <tr><th>Suspect</th><td id="detailRespondentName">—</td></tr>
                  <tr><th>Location</th><td id="detailLocation">—</td></tr>
                  <tr><th>Case Date</th><td id="detailIncidentDate">—</td></tr>
                  <tr><th>Status</th><td id="detailStatus"><span class="badge badge-secondary">—</span></td></tr>
                </tbody>
              </table>

              <div class="mb-3">
                <h6>Narrative</h6>
                <p id="detailNarrative" class="small text-muted mb-0">No record selected.</p>
              </div>
              <div class="mb-3">
                <h6>Action Taken</h6>
                <p id="detailActionTaken" class="small text-muted mb-0">No record selected.</p>
              </div>
            </div>
            <div class="card-footer d-flex flex-column gap-2">
              <button id="editSelectedRecord" class="btn btn-warning btn-block" type="button" disabled>
                <i class="fas fa-edit mr-1"></i> Edit Selected
              </button>
              <button id="deleteSelectedRecord" class="btn btn-danger btn-block" type="button" disabled>
                <i class="fas fa-trash mr-1"></i> Delete Selected
              </button>
              <button id="printSelectedRecord" class="btn btn-secondary btn-block" type="button" disabled>
                <i class="fas fa-print mr-1"></i> Print
              </button>
            </div>

    </div>


    <!-- ================= ADD MODAL ================= -->
    <div class="modal fade" id="addBlotterModal" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <form id="addBlotterForm">
          <?= csrf_field() ?>
          <div class="modal-content">

            <div class="modal-header">
              <h5 class="modal-title"><i class="fa fa-plus-circle fa-fw"></i> Add New Blotter Record</h5>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">

              <div class="row">
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Case Number</label>
                    <input type="text" name="case_number" class="form-control" placeholder="e.g. BLT-2024-001">
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Incident Type <span class="text-danger">*</span></label>
                    <select name="incident_type" class="form-control" required>
                      <option value="">-- Select Type --</option>
                      <option value="Physical Assault">Physical Assault</option>
                      <option value="Verbal Abuse">Verbal Abuse</option>
                      <option value="Theft">Theft</option>
                      <option value="Trespassing">Trespassing</option>
                      <option value="Noise Complaint">Noise Complaint</option>
                      <option value="Domestic Violence">Domestic Violence</option>
                      <option value="Property Damage">Property Damage</option>
                      <option value="Threat">Threat</option>
                      <option value="Others">Others</option>
                    </select>
                    <div class="invalid-feedback">This field is required.</div>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Incident Date <span class="text-danger">*</span></label>
                    <input type="date" name="incident_date" class="form-control" required>
                    <div class="invalid-feedback">This field is required.</div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Complainant Name <span class="text-danger">*</span></label>
                    <input type="text" name="complainant_name" class="form-control" placeholder="Full name of complainant" required>
                    <div class="invalid-feedback">This field is required.</div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Suspect  Name <span class="text-danger">*</span></label>
                    <input type="text" name="respondent_name" class="form-control" placeholder="Full name of respondent" required>
                    <div class="invalid-feedback">This field is required.</div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-sm-8">
                  <div class="form-group">
                    <label>Incident Location</label>
                    <input type="text" name="incident_location" class="form-control" placeholder="Where did the incident occur?">
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                      <option value="Ongoing">Ongoing</option>
                      <option value="Settled">Settled</option>
                      <option value="Referred to Court">Referred to Court</option>
                      <option value="Dismissed">Dismissed</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Narrative</label>
                    <textarea name="narrative" class="form-control" rows="3" placeholder="Brief description of the incident..."></textarea>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Action Taken</label>
                    <textarea name="action_taken" class="form-control" rows="3" placeholder="What actions were taken?"></textarea>
                  </div>
                </div>
              </div>

            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">
                <i class="fas fa-times-circle"></i> Cancel
              </button>
              <button type="submit" class="btn btn-primary">
                <i class="fa fa-save"></i> Save Record
              </button>
            </div>

          </div>
        </form>
      </div>
    </div>


    <!-- ================= EDIT MODAL ================= -->
    <div class="modal fade" id="editBlotterModal" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <form id="editBlotterForm">
          <?= csrf_field() ?>
          <div class="modal-content">

            <div class="modal-header">
              <h5 class="modal-title"><i class="far fa-edit fa-fw"></i> Edit Blotter Record</h5>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">

              <input type="hidden" id="editBlotterId" name="id">

              <div class="row">
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Case Number</label>
                    <input type="text" id="editCaseNumber" name="case_number" class="form-control">
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Incident Type <span class="text-danger">*</span></label>
                    <select id="editIncidentType" name="incident_type" class="form-control" required>
                      <option value="">-- Select Type --</option>
                      <option value="Physical Assault">Physical Assault</option>
                      <option value="Verbal Abuse">Verbal Abuse</option>
                      <option value="Theft">Theft</option>
                      <option value="Trespassing">Trespassing</option>
                      <option value="Noise Complaint">Noise Complaint</option>
                      <option value="Domestic Violence">Domestic Violence</option>
                      <option value="Property Damage">Property Damage</option>
                      <option value="Threat">Threat</option>
                      <option value="Others">Others</option>
                    </select>
                    <div class="invalid-feedback">This field is required.</div>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Incident Date <span class="text-danger">*</span></label>
                    <input type="date" id="editIncidentDate" name="incident_date" class="form-control" required>
                    <div class="invalid-feedback">This field is required.</div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Complainant Name <span class="text-danger">*</span></label>
                    <input type="text" id="editComplainantName" name="complainant_name" class="form-control" required>
                    <div class="invalid-feedback">This field is required.</div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Suspect  Name <span class="text-danger">*</span></label>
                    <input type="text" id="editRespondentName" name="respondent_name" class="form-control" required>
                    <div class="invalid-feedback">This field is required.</div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-sm-8">
                  <div class="form-group">
                    <label>Incident Location</label>
                    <input type="text" id="editIncidentLocation" name="incident_location" class="form-control">
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Status</label>
                    <select id="editStatus" name="status" class="form-control">
                      <option value="Ongoing">Ongoing</option>
                      <option value="Settled">Settled</option>
                      <option value="Referred to Court">Referred to Court</option>
                      <option value="Dismissed">Dismissed</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Narrative</label>
                    <textarea id="editNarrative" name="narrative" class="form-control" rows="3"></textarea>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Action Taken</label>
                    <textarea id="editActionTaken" name="action_taken" class="form-control" rows="3"></textarea>
                  </div>
                </div>
              </div>

            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">
                <i class="fas fa-times-circle"></i> Cancel
              </button>
              <button type="submit" class="btn btn-primary">
                <i class="fa fa-save"></i> Update Record
              </button>
            </div>

          </div>
        </form>
      </div>
    </div>


  </section>
</div>
<?= $this->endSection() ?>


<?= $this->section('scripts') ?>
<script>
  const baseUrl = "<?= base_url() ?>";
</script>
<script src="<?= base_url('js/blotter/blotter.js') ?>"></script>
<?= $this->endSection() ?>
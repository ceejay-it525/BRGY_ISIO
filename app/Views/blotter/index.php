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
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Blotter Records</h3>
              <div class="float-right">
                <button type="button" class="btn btn-md btn-primary" data-toggle="modal" data-target="#AddNewModal">
                  <i class="fa fa-plus-circle fa fw"></i> File New Blotter
                </button>
              </div>
            </div>
            <div class="card-body">
              <table id="blotterTable" class="table table-bordered table-striped table-sm">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th style="display:none;">id</th>
                    <th>Complainant</th>
                    <th>Respondent</th>
                    <th>Type</th>
                    <th>Date Incident</th>
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
        <form id="addBlotterForm">
          <?= csrf_field() ?>
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title"><i class="fa fa-plus-circle fa fw"></i> File New Blotter</h5>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Complainant (Resident ID)</label>
                    <input type="number" name="complainant_id" class="form-control" placeholder="Enter Resident ID" />
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Respondent (Resident ID)</label>
                    <input type="number" name="respondent_id" class="form-control" placeholder="Enter Resident ID" />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Complaint Type <span class="text-danger">*</span></label>
                    <select name="complaint_type" class="form-control" required>
                      <option value="">-- Select Type --</option>
                      <option value="Theft">Theft</option>
                      <option value="Physical Injury">Physical Injury</option>
                      <option value="Threat">Threat</option>
                      <option value="Harassment">Harassment</option>
                      <option value="Disturbance">Disturbance</option>
                      <option value="Trespassing">Trespassing</option>
                      <option value="Domestic Violence">Domestic Violence</option>
                      <option value="Others">Others</option>
                    </select>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Date of Incident <span class="text-danger">*</span></label>
                    <input type="date" name="date_incident" class="form-control" required />
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label>Complaint Details</label>
                <textarea name="complaint_details" class="form-control" rows="3"></textarea>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                      <option value="Filed">Filed</option>
                      <option value="Under Investigation">Under Investigation</option>
                      <option value="Mediated">Mediated</option>
                      <option value="Settled">Settled</option>
                      <option value="Referred to Police">Referred to Police</option>
                      <option value="Closed">Closed</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label>Resolution Notes</label>
                <textarea name="resolution_notes" class="form-control" rows="2"></textarea>
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
    <div class="modal fade" id="editBlotterModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><i class="far fa-edit fa fw"></i> Edit Blotter Record</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <form id="editBlotterForm">
            <?= csrf_field() ?>
            <div class="modal-body">
              <input type="hidden" id="editBlotterId" name="id" />
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Complainant (Resident ID)</label>
                    <input type="number" name="complainant_id" id="editComplainantId" class="form-control" />
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Respondent (Resident ID)</label>
                    <input type="number" name="respondent_id" id="editRespondentId" class="form-control" />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Complaint Type</label>
                    <select name="complaint_type" id="editComplaintType" class="form-control">
                      <option value="Theft">Theft</option>
                      <option value="Physical Injury">Physical Injury</option>
                      <option value="Threat">Threat</option>
                      <option value="Harassment">Harassment</option>
                      <option value="Disturbance">Disturbance</option>
                      <option value="Trespassing">Trespassing</option>
                      <option value="Domestic Violence">Domestic Violence</option>
                      <option value="Others">Others</option>
                    </select>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Date of Incident</label>
                    <input type="date" name="date_incident" id="editDateIncident" class="form-control" />
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label>Complaint Details</label>
                <textarea name="complaint_details" id="editComplaintDetails" class="form-control" rows="3"></textarea>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Status</label>
                    <select name="status" id="editBlotterStatus" class="form-control">
                      <option value="Filed">Filed</option>
                      <option value="Under Investigation">Under Investigation</option>
                      <option value="Mediated">Mediated</option>
                      <option value="Settled">Settled</option>
                      <option value="Referred to Police">Referred to Police</option>
                      <option value="Closed">Closed</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label>Resolution Notes</label>
                <textarea name="resolution_notes" id="editResolutionNotes" class="form-control" rows="2"></textarea>
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
<script src="<?= base_url('assets/js/blotter.js') ?>"></script>
<?= $this->endSection() ?>
<?= $this->extend('theme/template') ?>

<?= $this->section('content') ?>
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Clearances</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Clearances</li>
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
              <h3 class="card-title">List of Clearances</h3>
              <div class="float-right">
                <button type="button" class="btn btn-md btn-primary" data-toggle="modal" data-target="#AddNewModal">
                  <i class="fa fa-plus-circle fa fw"></i> Issue Clearance
                </button>
              </div>
            </div>
            <div class="card-body">
              <table id="clearancesTable" class="table table-bordered table-striped table-sm">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th style="display:none;">id</th>
                    <th>Resident Name</th>
                    <th>Clearance Type</th>
                    <th>Purpose</th>
                    <th>Date Issued</th>
                    <th>Valid Until</th>
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
        <form id="addClearanceForm">
          <?= csrf_field() ?>
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title"><i class="fa fa-plus-circle fa fw"></i> Issue New Clearance</h5>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label>Resident ID <span class="text-danger">*</span></label>
                <input type="number" name="resident_id" class="form-control" required placeholder="Enter Resident ID" />
              </div>
              <div class="form-group">
                <label>Clearance Type <span class="text-danger">*</span></label>
                <select name="clearance_type" class="form-control" required>
                  <option value="">-- Select Type --</option>
                  <option value="Residency">Residency</option>
                  <option value="Good Moral Character">Good Moral Character</option>
                  <option value="Barangay Clearance">Barangay Clearance</option>
                </select>
              </div>
              <div class="form-group">
                <label>Purpose</label>
                <input type="text" name="purpose" class="form-control" />
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Date Issued <span class="text-danger">*</span></label>
                    <input type="date" name="date_issued" class="form-control" required />
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Valid Until</label>
                    <input type="date" name="valid_until" class="form-control" />
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                  <option value="Issued">Issued</option>
                  <option value="Cancelled">Cancelled</option>
                  <option value="Expired">Expired</option>
                </select>
              </div>
              <div class="form-group">
                <label>Remarks</label>
                <textarea name="remarks" class="form-control" rows="2"></textarea>
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
    <div class="modal fade" id="editClearanceModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><i class="far fa-edit fa fw"></i> Edit Clearance</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <form id="editClearanceForm">
            <?= csrf_field() ?>
            <div class="modal-body">
              <input type="hidden" id="editClearanceId" name="id" />
              <div class="form-group">
                <label>Resident ID</label>
                <input type="number" name="resident_id" id="editResidentId" class="form-control" />
              </div>
              <div class="form-group">
                <label>Clearance Type</label>
                <select name="clearance_type" id="editClearanceType" class="form-control">
                  <option value="Residency">Residency</option>
                  <option value="Good Moral Character">Good Moral Character</option>
                  <option value="Barangay Clearance">Barangay Clearance</option>
                </select>
              </div>
              <div class="form-group">
                <label>Purpose</label>
                <input type="text" name="purpose" id="editPurpose" class="form-control" />
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Date Issued</label>
                    <input type="date" name="date_issued" id="editDateIssued" class="form-control" />
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Valid Until</label>
                    <input type="date" name="valid_until" id="editValidUntil" class="form-control" />
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label>Status</label>
                <select name="status" id="editClearanceStatus" class="form-control">
                  <option value="Issued">Issued</option>
                  <option value="Cancelled">Cancelled</option>
                  <option value="Expired">Expired</option>
                </select>
              </div>
              <div class="form-group">
                <label>Remarks</label>
                <textarea name="remarks" id="editRemarks" class="form-control" rows="2"></textarea>
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
<script src="<?= base_url('js/clearances/clearances.js') ?>"></script>
<?= $this->endSection() ?>

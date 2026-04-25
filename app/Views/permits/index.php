<?= $this->extend('theme/template') ?>

<?= $this->section('content') ?>
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Business Permits</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Business Permits</li>
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
              <h3 class="card-title">List of Business Permits</h3>
              <div class="float-right">
                <button type="button" class="btn btn-md btn-primary" data-toggle="modal" data-target="#AddNewModal">
                  <i class="fa fa-plus-circle fa fw"></i> Add New
                </button>
              </div>
            </div>
            <div class="card-body">
              <table id="permitsTable" class="table table-bordered table-striped table-sm">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th style="display:none;">id</th>
                    <th>Business Name</th>
                    <th>Owner</th>
                    <th>Type</th>
                    <th>Permit Type</th>
                    <th>Issue Date</th>
                    <th>Expiry Date</th>
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
        <form id="addPermitForm">
          <?= csrf_field() ?>
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title"><i class="fa fa-plus-circle fa fw"></i> Add New Business Permit</h5>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Business Name <span class="text-danger">*</span></label>
                    <input type="text" name="business_name" class="form-control" required />
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Owner Name <span class="text-danger">*</span></label>
                    <input type="text" name="owner_name" class="form-control" required />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Business Address <span class="text-danger">*</span></label>
                    <input type="text" name="business_address" class="form-control" required />
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Business Type</label>
                    <input type="text" name="business_type" class="form-control" />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Permit Type</label>
                    <select name="permit_type" class="form-control">
                      <option value="">-- Select --</option>
                      <option value="New">New</option>
                      <option value="Renewal">Renewal</option>
                      <option value="Amendment">Amendment</option>
                    </select>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Issue Date <span class="text-danger">*</span></label>
                    <input type="date" name="issue_date" class="form-control" required />
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Expiry Date <span class="text-danger">*</span></label>
                    <input type="date" name="expiry_date" class="form-control" required />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                      <option value="Pending">Pending</option>
                      <option value="Active">Active</option>
                      <option value="Expired">Expired</option>
                      <option value="Revoked">Revoked</option>
                    </select>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Fees Paid (₱)</label>
                    <input type="number" step="0.01" name="fees_paid" class="form-control" value="0.00" />
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
    <div class="modal fade" id="editPermitModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><i class="far fa-edit fa fw"></i> Edit Business Permit</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <form id="editPermitForm">
            <?= csrf_field() ?>
            <div class="modal-body">
              <input type="hidden" id="editPermitId" name="id" />
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Business Name</label>
                    <input type="text" name="business_name" id="editBusinessName" class="form-control" required />
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Owner Name</label>
                    <input type="text" name="owner_name" id="editOwnerName" class="form-control" required />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Business Address</label>
                    <input type="text" name="business_address" id="editBusinessAddress" class="form-control" />
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Business Type</label>
                    <input type="text" name="business_type" id="editBusinessType" class="form-control" />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Permit Type</label>
                    <select name="permit_type" id="editPermitType" class="form-control">
                      <option value="New">New</option>
                      <option value="Renewal">Renewal</option>
                      <option value="Amendment">Amendment</option>
                    </select>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Issue Date</label>
                    <input type="date" name="issue_date" id="editIssueDate" class="form-control" />
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Expiry Date</label>
                    <input type="date" name="expiry_date" id="editExpiryDate" class="form-control" />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Status</label>
                    <select name="status" id="editPermitStatus" class="form-control">
                      <option value="Pending">Pending</option>
                      <option value="Active">Active</option>
                      <option value="Expired">Expired</option>
                      <option value="Revoked">Revoked</option>
                    </select>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Fees Paid (₱)</label>
                    <input type="number" step="0.01" name="fees_paid" id="editFeesPaid" class="form-control" />
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
<script src="<?= base_url('assets/js/permits.js') ?>"></script>
<?= $this->endSection() ?>
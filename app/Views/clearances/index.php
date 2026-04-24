<?= $this->extend('layouts/template') ?>

<?= $this->section('content') ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Clearances</h1>
    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" id="addBtn">
        <i class="fas fa-plus fa-sm text-white-50"></i> Add Clearance
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Clearance Records</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="clearancesTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Control No.</th>
                        <th>Resident</th>
                        <th>Type</th>
                        <th>Purpose</th>
                        <th>Request Date</th>
                        <th>Status</th>
                        <th>Fee</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div class="modal fade" id="clearanceModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Add Clearance</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="clearanceForm">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <input type="hidden" id="clearance_id" name="clearance_id">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Control Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="control_number" name="control_number" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Resident <span class="text-danger">*</span></label>
                                <select class="form-control" id="resident_id" name="resident_id" required>
                                    <option value="">Select Resident</option>
                                    <?php foreach($residents as $resident): ?>
                                        <option value="<?= $resident['resident_id'] ?>">
                                            <?= esc($resident['first_name'].' '.$resident['last_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Clearance Type <span class="text-danger">*</span></label>
                                <select class="form-control" id="clearance_type_id" name="clearance_type_id" required>
                                    <option value="">Select Type</option>
                                    <?php foreach($clearanceTypes as $type): ?>
                                        <option value="<?= $type['clearance_type_id'] ?>"><?= esc($type['type_name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Fee Amount <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="fee_amount" name="fee_amount" step="0.01" min="0" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Request Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="request_date" name="request_date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status <span class="text-danger">*</span></label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="Pending">Pending</option>
                                    <option value="Approved">Approved</option>
                                    <option value="Released">Released</option>
                                    <option value="Rejected">Rejected</option>
                                    <option value="Expired">Expired</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Purpose <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="purpose" name="purpose" rows="2" required></textarea>
                    </div>

                    <div class="form-group">
                        <label>Remarks</label>
                        <textarea class="form-control" id="remarks" name="remarks" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('public/js/module/clearances.js') ?>"></script>
<?= $this->endSection() ?>
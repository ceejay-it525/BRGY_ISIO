<?= $this->extend('theme/template') ?>

<?= $this->section('content') ?>
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6"><h1 class="m-0">Blotter</h1></div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>">Home</a></li>
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
                  <i class="fa fa-plus-circle"></i> File New Case
                </button>
              </div>
            </div>
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped table-sm">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th style="display:none;">id</th>
                    <th>Case No.</th>
                    <th>Complainant</th>
                    <th>Respondent</th>
                    <th>Incident Type</th>
                    <th>Incident Date</th>
                    <th>Status</th>
                    <th>Date Filed</th>
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
        <form id="addBlotterForm">
          <?= csrf_field() ?>
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title"><i class="fa fa-plus-circle"></i> File New Blotter Case</h5>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Complainant <span class="text-danger">*</span></label>
                    <input type="text" name="complainant" class="form-control" required />
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Respondent <span class="text-danger">*</span></label>
                    <input type="text" name="respondent" class="form-control" required />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Incident Type</label>
                    <select name="incident_type" class="form-control">
                      <option value="Physical Assault">Physical Assault</option>
                      <option value="Verbal Abuse">Verbal Abuse</option>
                      <option value="Theft">Theft</option>
                      <option value="Property Damage">Property Damage</option>
                      <option value="Noise Complaint">Noise Complaint</option>
                      <option value="Domestic Violence">Domestic Violence</option>
                      <option value="Trespassing">Trespassing</option>
                      <option value="Other">Other</option>
                    </select>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Incident Date</label>
                    <input type="date" name="incident_date" class="form-control" />
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label>Place of Incident</label>
                <input type="text" name="incident_place" class="form-control" />
              </div>
              <div class="form-group">
                <label>Narrative</label>
                <textarea name="narrative" class="form-control" rows="3" placeholder="Describe the incident..."></textarea>
              </div>
              <div class="row">
                <div class="col-sm-8">
                  <div class="form-group">
                    <label>Action Taken</label>
                    <textarea name="action_taken" class="form-control" rows="2"></textarea>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                      <option value="Pending">Pending</option>
                      <option value="Under Investigation">Under Investigation</option>
                      <option value="Settled">Settled</option>
                      <option value="Referred to Court">Referred to Court</option>
                      <option value="Dismissed">Dismissed</option>
                    </select>
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
    <div class="modal fade" id="editBlotterModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><i class="far fa-edit"></i> Edit Blotter Case</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <form id="editBlotterForm">
            <?= csrf_field() ?>
            <div class="modal-body">
              <input type="hidden" id="blotterId" name="id">
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Complainant</label>
                    <input type="text" id="e_complainant" name="complainant" class="form-control" required />
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Respondent</label>
                    <input type="text" id="e_respondent" name="respondent" class="form-control" required />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Incident Type</label>
                    <select id="e_incident_type" name="incident_type" class="form-control">
                      <option value="Physical Assault">Physical Assault</option>
                      <option value="Verbal Abuse">Verbal Abuse</option>
                      <option value="Theft">Theft</option>
                      <option value="Property Damage">Property Damage</option>
                      <option value="Noise Complaint">Noise Complaint</option>
                      <option value="Domestic Violence">Domestic Violence</option>
                      <option value="Trespassing">Trespassing</option>
                      <option value="Other">Other</option>
                    </select>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Incident Date</label>
                    <input type="date" id="e_incident_date" name="incident_date" class="form-control" />
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label>Place of Incident</label>
                <input type="text" id="e_incident_place" name="incident_place" class="form-control" />
              </div>
              <div class="form-group">
                <label>Narrative</label>
                <textarea id="e_narrative" name="narrative" class="form-control" rows="3"></textarea>
              </div>
              <div class="row">
                <div class="col-sm-8">
                  <div class="form-group">
                    <label>Action Taken</label>
                    <textarea id="e_action_taken" name="action_taken" class="form-control" rows="2"></textarea>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Status</label>
                    <select id="e_status" name="status" class="form-control">
                      <option value="Pending">Pending</option>
                      <option value="Under Investigation">Under Investigation</option>
                      <option value="Settled">Settled</option>
                      <option value="Referred to Court">Referred to Court</option>
                      <option value="Dismissed">Dismissed</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label>Date Settled (if applicable)</label>
                <input type="date" id="e_settled_date" name="settled_date" class="form-control" />
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
<script>
function showToast(type, message) {
    if (type === 'success') toastr.success(message, 'Success');
    else toastr.error(message, 'Error');
}
$('#addBlotterForm').on('submit', function (e) {
    e.preventDefault();
    $.ajax({ url: baseUrl + 'blotter/save', method: 'POST', data: $(this).serialize(), dataType: 'json',
        success: function (r) {
            if (r.status === 'success') { $('#AddNewModal').modal('hide'); $('#addBlotterForm')[0].reset(); showToast('success', 'Case filed!'); setTimeout(() => location.reload(), 1000); }
            else showToast('error', r.message);
        }, error: function () { showToast('error', 'An error occurred.'); }
    });
});
$(document).on('click', '.edit-btn', function () {
    $.ajax({ url: baseUrl + 'blotter/edit/' + $(this).data('id'), method: 'GET', dataType: 'json',
        success: function (r) {
            if (r.data) {
                const d = r.data;
                $('#blotterId').val(d.id); $('#e_complainant').val(d.complainant);
                $('#e_respondent').val(d.respondent); $('#e_incident_type').val(d.incident_type);
                $('#e_incident_date').val(d.incident_date); $('#e_incident_place').val(d.incident_place);
                $('#e_narrative').val(d.narrative); $('#e_action_taken').val(d.action_taken);
                $('#e_status').val(d.status); $('#e_settled_date').val(d.settled_date);
                $('#editBlotterModal').modal('show');
            }
        }
    });
});
$('#editBlotterForm').on('submit', function (e) {
    e.preventDefault();
    $.ajax({ url: baseUrl + 'blotter/update', method: 'POST', data: $(this).serialize(), dataType: 'json',
        success: function (r) {
            if (r.success) { $('#editBlotterModal').modal('hide'); showToast('success', 'Case updated!'); setTimeout(() => location.reload(), 1000); }
            else showToast('error', r.message);
        }
    });
});
$(document).on('click', '.deleteBtn', function () {
    if (confirm('Delete this blotter case?')) {
        $.ajax({ url: baseUrl + 'blotter/delete/' + $(this).data('id'), method: 'POST',
            success: function (r) { if (r.success) { showToast('success', 'Deleted!'); setTimeout(() => location.reload(), 1000); } else alert(r.message); }
        });
    }
});
const statusBadge = { 'Pending': 'badge-warning', 'Settled': 'badge-success', 'Dismissed': 'badge-secondary', 'Referred to Court': 'badge-danger', 'Under Investigation': 'badge-info' };
$(document).ready(function () {
    const csrfToken = $('input[name="csrf_test_name"]').val();
    $('#example1').DataTable({
        processing: true, serverSide: true,
        ajax: { url: baseUrl + 'blotter/fetchRecords', type: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken } },
        columns: [
            { data: 'row_number' }, { data: 'id', visible: false },
            { data: 'case_no' }, { data: 'complainant' }, { data: 'respondent' },
            { data: 'incident_type' }, { data: 'incident_date' },
            { data: 'status', render: d => `<span class="badge ${statusBadge[d] || 'badge-secondary'}">${d}</span>` },
            { data: 'created_at' },
            { data: null, orderable: false, searchable: false, render: (d, t, row) => `<button class="btn btn-sm btn-warning edit-btn" data-id="${row.id}"><i class="far fa-edit"></i></button> <button class="btn btn-sm btn-danger deleteBtn" data-id="${row.id}"><i class="fas fa-trash-alt"></i></button>` }
        ],
        responsive: true, autoWidth: false
    });
});
</script>
<?= $this->endSection() ?>
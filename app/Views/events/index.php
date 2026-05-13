<?= $this->extend('theme/template') ?>

<?= $this->section('content') ?>
<div class="content-wrapper">

  <!-- ── Content Header ──────────────────────────────────────────── -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark"><i class="fas fa-calendar-alt mr-2"></i>Barangay Events</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Events</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <!-- ── Main Content ────────────────────────────────────────────── -->
  <section class="content">
    <div class="container-fluid">

      <!-- Workflow Nav Tabs -->
      <div class="row mb-3">
        <div class="col-12">
          <div class="btn-group btn-group-lg w-100" role="group">
            <a href="<?= base_url('events') ?>"
               class="btn btn-outline-secondary <?= uri_string() === 'events' ? 'active' : '' ?>">
              <i class="fas fa-list mr-1"></i> All
            </a>
            <a href="<?= base_url('events/draft') ?>"
               class="btn btn-secondary <?= uri_string() === 'events/draft' ? 'active' : '' ?>">
              <i class="fas fa-file mr-1"></i> Draft
              <span class="badge badge-light ml-1" id="draftCount">0</span>
            </a>
            <a href="<?= base_url('events/scheduled') ?>"
               class="btn btn-info <?= uri_string() === 'events/scheduled' ? 'active' : '' ?>">
              <i class="fas fa-clock mr-1"></i> Scheduled
              <span class="badge badge-light ml-1" id="scheduledCount">0</span>
            </a>
            <a href="<?= base_url('events/ongoing') ?>"
               class="btn btn-success <?= uri_string() === 'events/ongoing' ? 'active' : '' ?>">
              <i class="fas fa-play-circle mr-1"></i> Ongoing
              <span class="badge badge-light ml-1" id="ongoingCount">0</span>
            </a>
            <a href="<?= base_url('events/completed') ?>"
               class="btn btn-primary <?= uri_string() === 'events/completed' ? 'active' : '' ?>">
              <i class="fas fa-check-circle mr-1"></i> Completed
              <span class="badge badge-light ml-1" id="completedCount">0</span>
            </a>
            <a href="<?= base_url('events/cancelled') ?>"
               class="btn btn-danger <?= uri_string() === 'events/cancelled' ? 'active' : '' ?>">
              <i class="fas fa-ban mr-1"></i> Cancelled
              <span class="badge badge-light ml-1" id="cancelledCount">0</span>
            </a>
          </div>
        </div>
      </div>

      <!-- KPI Cards -->
      <div class="row">
        <div class="col-lg-3 col-6">
          <div class="small-box bg-info">
            <div class="inner"><h3 id="totalEvents">0</h3><p>Total Events</p></div>
            <div class="icon"><i class="fas fa-calendar"></i></div>
            <a href="<?= base_url('events') ?>" class="small-box-footer">View All <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-success">
            <div class="inner"><h3 id="completedEvents">0</h3><p>Completed</p></div>
            <div class="icon"><i class="fas fa-check-circle"></i></div>
            <a href="<?= base_url('events/completed') ?>" class="small-box-footer">View <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-warning">
            <div class="inner"><h3 id="scheduledEvents">0</h3><p>Scheduled</p></div>
            <div class="icon"><i class="fas fa-clock"></i></div>
            <a href="<?= base_url('events/scheduled') ?>" class="small-box-footer">Manage <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-secondary">
            <div class="inner"><h3 id="totalBudget">₱0</h3><p>Total Budget</p></div>
            <div class="icon"><i class="fas fa-money-bill-wave"></i></div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
      </div>

      <!-- DataTable Card -->
      <div class="row">
        <div class="col-12">
          <div class="card card-outline card-primary shadow">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-table mr-1"></i>
                <?php
                  $labels = [
                    'events/draft'     => 'Draft Events',
                    'events/scheduled' => 'Scheduled Events',
                    'events/ongoing'   => 'Ongoing Events',
                    'events/completed' => 'Completed Events',
                    'events/cancelled' => 'Cancelled Events',
                  ];
                  echo $labels[uri_string()] ?? 'All Events';
                ?>
              </h3>
              <div class="card-tools">
                <!-- Add button is always visible — workflow tabs can still create new events -->
                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#AddNewModal">
                  <i class="fas fa-plus-circle mr-1"></i> Add New Event
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="card-body table-responsive p-0">
              <table id="eventsTable" class="table table-bordered table-striped table-hover w-100">
                <thead class="thead-dark">
                  <tr>
                    <th width="4%">#</th>
                    <th style="display:none">id</th>
                    <th>Event Title <small class="text-info">(click to view)</small></th>
                    <th>Description</th>
                    <th>Venue</th>
                    <th>Event Date</th>
                    <th>Days Remaining</th>
                    <th>Budget</th>
                    <th>Status</th>
                    <th width="8%" class="text-center">Actions</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

    </div><!-- /.container-fluid -->
  </section>

  <!-- ====================================================================
       MODAL: ADD
  ===================================================================== -->
  <div class="modal fade" id="AddNewModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form id="addEventForm">
          <?= csrf_field() ?>
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title"><i class="fas fa-plus-circle mr-2"></i>Add New Event</h5>
            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-8">
                <div class="form-group">
                  <label>Event Title <span class="text-danger">*</span></label>
                  <input type="text" name="title" class="form-control" placeholder="e.g. Barangay Assembly" required>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Venue <span class="text-danger">*</span></label>
                  <input type="text" name="venue" class="form-control" placeholder="e.g. Barangay Hall" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Event Date <span class="text-danger">*</span></label>
                  <input type="date" name="event_date" class="form-control" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Event Time</label>
                  <input type="time" name="event_time" class="form-control">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>End Date</label>
                  <input type="date" name="end_date" class="form-control">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>End Time</label>
                  <input type="time" name="end_time" class="form-control">
                </div>
              </div>
            </div>
            <div class="form-group">
              <label>Description</label>
              <textarea name="description" class="form-control" rows="3" placeholder="Event description..."></textarea>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Budget (₱)</label>
                  <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text">₱</span></div>
                    <input type="number" step="0.01" min="0" name="budget" class="form-control" placeholder="0.00">
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Participants</label>
                  <input type="number" min="0" name="participants" class="form-control" placeholder="Expected participants">
                </div>
              </div>
            </div>
            <div class="form-group">
              <label>Notes</label>
              <textarea name="notes" class="form-control" rows="2" placeholder="Additional notes..."></textarea>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Color</label>
                  <select name="color" class="form-control">
                    <option value="primary">Primary (Blue)</option>
                    <option value="success">Success (Green)</option>
                    <option value="warning">Warning (Yellow)</option>
                    <option value="danger">Danger (Red)</option>
                    <option value="info">Info (Cyan)</option>
                    <option value="secondary">Secondary (Gray)</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Icon</label>
                  <select name="icon" class="form-control">
                    <option value="fa-calendar">📅 Calendar</option>
                    <option value="fa-users">👥 People/Meeting</option>
                    <option value="fa-heartbeat">❤️ Health/Medical</option>
                    <option value="fa-graduation-cap">🎓 Education</option>
                    <option value="fa-music">🎵 Entertainment</option>
                    <option value="fa-running">⚽ Sports</option>
                    <option value="fa-hand-holding-heart">🤝 Social Service</option>
                    <option value="fa-bullhorn">📢 Announcement</option>
                    <option value="fa-gavel">⚖️ Legal/Court</option>
                    <option value="fa-church">⛪ Religious</option>
                    <option value="fa-flag">🚩 Flag Ceremony</option>
                    <option value="fa-award">🏆 Award/Ceremony</option>
                    <option value="fa-trash">🗑️ Clean-up Drive</option>
                    <option value="fa-first-aid">🚑 Emergency</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="custom-control custom-checkbox">
                <input type="checkbox" name="is_public" class="custom-control-input" id="isPublic" value="1">
                <label class="custom-control-label" for="isPublic">Make this event public (visible on public board)</label>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i>Save Event</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- ====================================================================
       MODAL: EDIT
  ===================================================================== -->
  <div class="modal fade" id="editEventModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form id="editEventForm">
          <?= csrf_field() ?>
          <input type="hidden" name="id" id="editEventId">
          <div class="modal-header bg-warning text-dark">
            <h5 class="modal-title"><i class="fas fa-edit mr-2"></i>Edit Event</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-8">
                <div class="form-group">
                  <label>Event Title <span class="text-danger">*</span></label>
                  <input type="text" name="title" id="editTitle" class="form-control" required>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Venue <span class="text-danger">*</span></label>
                  <input type="text" name="venue" id="editVenue" class="form-control" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Event Date <span class="text-danger">*</span></label>
                  <input type="date" name="event_date" id="editEventDate" class="form-control" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Event Time</label>
                  <input type="time" name="event_time" id="editEventTime" class="form-control">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>End Date</label>
                  <input type="date" name="end_date" id="editEndDate" class="form-control">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>End Time</label>
                  <input type="time" name="end_time" id="editEndTime" class="form-control">
                </div>
              </div>
            </div>
            <div class="form-group">
              <label>Description</label>
              <textarea name="description" id="editDescription" class="form-control" rows="3"></textarea>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Budget (₱)</label>
                  <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text">₱</span></div>
                    <input type="number" step="0.01" min="0" name="budget" id="editBudget" class="form-control" placeholder="0.00">
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Participants</label>
                  <input type="number" min="0" name="participants" id="editParticipants" class="form-control">
                </div>
              </div>
            </div>
            <div class="form-group">
              <label>Status</label>
              <select name="status" id="editStatus" class="form-control">
                <option value="Draft">Draft</option>
                <option value="Scheduled">Scheduled</option>
                <option value="Ongoing">Ongoing</option>
                <option value="Completed">Completed</option>
                <option value="Cancelled">Cancelled</option>
              </select>
            </div>
            <div class="form-group">
              <label>Notes</label>
              <textarea name="notes" id="editNotes" class="form-control" rows="2"></textarea>
            </div>
            <div class="form-group">
              <div class="custom-control custom-checkbox">
                <input type="checkbox" name="is_public" id="editIsPublic" class="custom-control-input" value="1">
                <label class="custom-control-label" for="editIsPublic">Make this event public (visible on public board)</label>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-warning"><i class="fas fa-save mr-1"></i>Update Event</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- ====================================================================
       MODAL: VIEW
  ===================================================================== -->
  <div class="modal fade" id="viewEventModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header bg-info text-white">
          <h5 class="modal-title"><i class="fas fa-eye mr-2"></i>Event Details</h5>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="viewEventId">
          <div class="row">
            <!-- Left: Details + Workflow -->
            <div class="col-md-7">
              <div class="card card-outline card-info mb-3">
                <div class="card-header"><h3 class="card-title">Event Information</h3></div>
                <div class="card-body p-0">
                  <table class="table table-sm mb-0">
                    <tr><th width="38%">Event Title</th><td id="viewTitle"></td></tr>
                    <tr><th>Description</th><td id="viewDescription"></td></tr>
                    <tr><th>Venue</th><td id="viewVenue"></td></tr>
                    <tr><th>Event Date</th><td id="viewEventDate"></td></tr>
                    <tr><th>Event Time</th><td id="viewEventTime"></td></tr>
                    <tr><th>Budget</th><td id="viewBudget"></td></tr>
                    <tr><th>Participants</th><td id="viewParticipants"></td></tr>
                    <tr><th>Status</th><td id="viewStatus"></td></tr>
                    <tr><th>QR Code</th><td id="viewQRCode"></td></tr>
                  </table>
                </div>
              </div>
              <div class="card card-outline card-success">
                <div class="card-header"><h3 class="card-title"><i class="fas fa-tasks mr-1"></i>Workflow Actions</h3></div>
                <div class="card-body text-center" id="workflowActions"></div>
              </div>
            </div>
            <!-- Right: Notes -->
            <div class="col-md-5">
              <div class="card card-outline card-secondary h-100">
                <div class="card-header"><h3 class="card-title"><i class="fas fa-sticky-note mr-1"></i>Notes &amp; Timeline</h3></div>
                <div class="card-body" id="viewNotes"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary print-event" id="printFromViewBtn" data-id=""><i class="fas fa-print mr-1"></i>Print Report</button>
          <button type="button" class="btn btn-warning" id="editFromViewBtn"><i class="fas fa-edit mr-1"></i>Edit</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- ====================================================================
       MODAL: CANCEL
  ===================================================================== -->
  <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="cancelEventForm">
          <input type="hidden" id="cancelEventId">
          <div class="modal-header bg-danger text-white">
            <h5 class="modal-title"><i class="fas fa-ban mr-2"></i>Cancel Event</h5>
            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label>Reason for Cancellation <span class="text-danger">*</span></label>
              <textarea id="cancelReason" name="reason" class="form-control" rows="3" required
                        placeholder="Explain why this event is being cancelled…"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-danger"><i class="fas fa-ban mr-1"></i>Confirm Cancellation</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- ====================================================================
       MODAL: PRINT PREVIEW
  ===================================================================== -->
  <div class="modal fade" id="printModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title"><i class="fas fa-print mr-2"></i>Print Preview</h5>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body bg-light">
          <div id="printPreviewContent" class="bg-white shadow p-4 mx-auto" style="max-width:800px;min-height:400px;"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="printBtn"><i class="fas fa-print mr-1"></i>Print Now</button>
        </div>
      </div>
    </div>
  </div>

</div><!-- /.content-wrapper -->
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  // FIX: extract just the last URI segment so JS resolveViewType() works correctly
  // e.g. "events/draft" → viewType becomes "draft"
  const baseUrl     = "<?= base_url() ?>";
  const currentView = "<?= uri_string() ?>";
</script>
<script src="<?= base_url('js/events/events.js') ?>"></script>
<?= $this->endSection() ?>

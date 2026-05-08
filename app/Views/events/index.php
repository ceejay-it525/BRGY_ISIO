<?= $this->extend('theme/template') ?>

<?= $this->section('content') ?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        <i class="fas fa-calendar-alt text-primary mr-2"></i>Barangay Events
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url() ?>">Home</a></li>
                        <li class="breadcrumb-item active">Events</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary card-outline shadow">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-calendar-check mr-1"></i> List of Events
                            </h3>
                            <div class="float-right">
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addEventModal">
                                    <i class="fa fa-plus-circle"></i> Add Event
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="eventsTable" class="table table-bordered table-striped table-hover table-sm w-100">
                                <thead class="thead-dark">
                                    <tr>
                                        <th width="5%">No.</th>
                                        <th style="display:none;">ID</th>
                                        <th>Event Title</th>
                                        <th>Description</th>
                                        <th>Event Date</th>
                                        <th>Days Until</th>
                                        <th>Color</th>
                                        <th width="10%" class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ADD EVENT MODAL -->
        <div class="modal fade" id="addEventModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form id="addEventForm" autocomplete="off">
                        <?= csrf_field() ?>
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title"><i class="fa fa-plus-circle mr-1"></i> Add New Event</h5>
                            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label>Event Title <span class="text-danger">*</span></label>
                                        <input type="text" name="title" class="form-control form-control-sm"
                                               placeholder="e.g. Barangay Assembly" required maxlength="255" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Event Date <span class="text-danger">*</span></label>
                                        <input type="date" name="event_date" class="form-control form-control-sm" required />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="description" class="form-control form-control-sm" rows="3"
                                          placeholder="Optional event description..." maxlength="1000"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Color <span class="text-danger">*</span></label>
                                        <select name="color" class="form-control form-control-sm" required>
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
                                        <label>Icon <span class="text-danger">*</span></label>
                                        <select name="icon" class="form-control form-control-sm" required>
                                            <option value="fa-calendar">📅 Calendar</option>
                                            <option value="fa-users">👥 People/Meeting</option>
                                            <option value="fa-heartbeat">❤️ Health/Medical</option>
                                            <option value="fa-graduation-cap">🎓 Education</option>
                                            <option value="fa-music">🎵 Entertainment</option>
                                            <option value="fa-sports">⚽ Sports</option>
                                            <option value="fa-hand-holding-heart">🤝 Social Service</option>
                                            <option value="fa-bullhorn">📢 Announcement</option>
                                            <option value="fa-gavel">⚖️ Legal/Court</option>
                                            <option value="fa-church">⛪ Religious</option>
                                            <option value="fa-flag">🚩 Flag Ceremony</option>
                                            <option value="fa-award">🏆 Award/Ceremony</option>
                                            <option value="fa-trash">🗑️ Clean-up Drive</option>
                                            <option value="fa-first-aid">🚑 Emergency</option>
                                            <option value="fa-file-alt">📄 Documentation</option>
                                            <option value="fa-store">🏪 Business</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                                <i class="fas fa-times-circle"></i> Cancel
                            </button>
                            <button type="submit" class="btn btn-primary btn-sm" id="addSaveBtn">
                                <i class="fa fa-save"></i> Save Event
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- EDIT EVENT MODAL -->
        <div class="modal fade" id="editEventModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form id="editEventForm" autocomplete="off">
                        <?= csrf_field() ?>
                        <div class="modal-header bg-warning">
                            <h5 class="modal-title"><i class="far fa-edit mr-1"></i> Edit Event</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="editEventId" name="id" />
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label>Event Title <span class="text-danger">*</span></label>
                                        <input type="text" id="editTitle" name="title" class="form-control form-control-sm"
                                               required maxlength="255" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Event Date <span class="text-danger">*</span></label>
                                        <input type="date" id="editEventDate" name="event_date"
                                               class="form-control form-control-sm" required />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea id="editDescription" name="description" class="form-control form-control-sm"
                                          rows="3" maxlength="1000"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Color <span class="text-danger">*</span></label>
                                        <select id="editColor" name="color" class="form-control form-control-sm" required>
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
                                        <label>Icon <span class="text-danger">*</span></label>
                                        <select id="editIcon" name="icon" class="form-control form-control-sm" required>
                                            <option value="fa-calendar">📅 Calendar</option>
                                            <option value="fa-users">👥 People/Meeting</option>
                                            <option value="fa-heartbeat">❤️ Health/Medical</option>
                                            <option value="fa-graduation-cap">🎓 Education</option>
                                            <option value="fa-music">🎵 Entertainment</option>
                                            <option value="fa-sports">⚽ Sports</option>
                                            <option value="fa-hand-holding-heart">🤝 Social Service</option>
                                            <option value="fa-bullhorn">📢 Announcement</option>
                                            <option value="fa-gavel">⚖️ Legal/Court</option>
                                            <option value="fa-church">⛪ Religious</option>
                                            <option value="fa-flag">🚩 Flag Ceremony</option>
                                            <option value="fa-award">🏆 Award/Ceremony</option>
                                            <option value="fa-trash">🗑️ Clean-up Drive</option>
                                            <option value="fa-first-aid">🚑 Emergency</option>
                                            <option value="fa-file-alt">📄 Documentation</option>
                                            <option value="fa-store">🏪 Business</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                                <i class="fas fa-times-circle"></i> Cancel
                            </button>
                            <button type="submit" class="btn btn-warning btn-sm" id="editSaveBtn">
                                <i class="fa fa-save"></i> Update Event
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- VIEW EVENT MODAL -->
        <div class="modal fade" id="viewEventModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title"><i class="fas fa-eye mr-1"></i> Event Details</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body" id="viewEventBody">
                        <!-- Filled by JS -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>const baseUrl = "<?= base_url() ?>";</script>
<script src="<?= base_url('js/events/events.js') ?>"></script>
<?= $this->endSection() ?>
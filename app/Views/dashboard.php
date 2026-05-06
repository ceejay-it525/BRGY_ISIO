<?= $this->extend('theme/template') ?>

<?= $this->section('content') ?>
<div class="content-wrapper">

    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-bold">
                        <i class="fas fa-home text-primary mr-2"></i>Barangay Dashboard
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="<?= base_url() ?>"><i class="fas fa-tachometer-alt"></i> Home</a>
                        </li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <!-- ================================================
                 STATS CARDS
            ================================================ -->
            <div class="row mb-4">
                <!-- Total Residents -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-gradient-info shadow">
                        <div class="inner">
                            <h3 class="text-white"><?= $totalResidents ?? 0 ?></h3>
                            <p class="text-white">Total Residents</p>
                        </div>
                        <div class="icon"><i class="fas fa-users fa-2x"></i></div>
                        <a href="<?= base_url('residents') ?>" class="small-box-footer text-white">
                            View <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Active Officials -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-gradient-primary shadow">
                        <div class="inner">
                            <h3 class="text-white"><?= $activeOfficials ?? 0 ?></h3>
                            <p class="text-white">Active Officials</p>
                        </div>
                        <div class="icon"><i class="fas fa-user-tie fa-2x"></i></div>
                        <a href="<?= base_url('barangay-officials') ?>" class="small-box-footer text-white">
                            Manage <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Total Blotter -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-gradient-warning shadow">
                        <div class="inner">
                            <h3 class="text-dark"><?= $totalBlotter ?? 0 ?></h3>
                            <p class="text-dark">Complaints</p>
                        </div>
                        <div class="icon"><i class="fas fa-exclamation-triangle fa-2x"></i></div>
                        <a href="<?= base_url('blotter') ?>" class="small-box-footer">
                            View All <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Business Permits -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-gradient-success shadow">
                        <div class="inner">
                            <h3 class="text-white"><?= $totalPermits ?? 0 ?></h3>
                            <p class="text-white">Permits</p>
                        </div>
                        <div class="icon"><i class="fas fa-store fa-2x"></i></div>
                        <a href="<?= base_url('permits') ?>" class="small-box-footer text-white">
                            Renewals <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- ================================================
                 QUICK ACTIONS
            ================================================ -->
            <div class="row mb-4">
                <div class="col-12">
                    <h5 class="mb-3">
                        <i class="fas fa-rocket text-primary mr-2"></i>Quick Actions
                    </h5>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                    <a href="<?= base_url('residents') ?>" class="card border-0 shadow h-100 text-decoration-none hover-shadow">
                        <div class="card-body text-center p-3">
                            <div class="bg-light rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width:60px;height:60px;">
                                <i class="fas fa-users fa-lg text-primary"></i>
                            </div>
                            <h6 class="text-primary mb-0">Residents</h6>
                            <small class="text-muted">Manage Residents</small>
                        </div>
                    </a>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                    <a href="<?= base_url('barangay-officials') ?>" class="card border-0 shadow h-100 text-decoration-none hover-shadow">
                        <div class="card-body text-center p-3">
                            <div class="bg-warning rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width:60px;height:60px;">
                                <i class="fas fa-user-tie fa-lg text-dark"></i>
                            </div>
                            <h6 class="text-warning mb-0">Officials</h6>
                            <small class="text-muted">Barangay Officials</small>
                        </div>
                    </a>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                    <a href="<?= base_url('blotter') ?>" class="card border-0 shadow h-100 text-decoration-none hover-shadow">
                        <div class="card-body text-center p-3">
                            <div class="bg-danger rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width:60px;height:60px;">
                                <i class="fas fa-file-alt fa-lg text-white"></i>
                            </div>
                            <h6 class="text-danger mb-0">Blotter</h6>
                            <small class="text-muted">Complaints Log</small>
                        </div>
                    </a>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                    <a href="<?= base_url('clearances') ?>" class="card border-0 shadow h-100 text-decoration-none hover-shadow">
                        <div class="card-body text-center p-3">
                            <div class="bg-success rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width:60px;height:60px;">
                                <i class="fas fa-certificate fa-lg text-white"></i>
                            </div>
                            <h6 class="text-success mb-0">Clearances</h6>
                            <small class="text-muted">Issue Certificates</small>
                        </div>
                    </a>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                    <a href="<?= base_url('permits') ?>" class="card border-0 shadow h-100 text-decoration-none hover-shadow">
                        <div class="card-body text-center p-3">
                            <div class="bg-info rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width:60px;height:60px;">
                                <i class="fas fa-store fa-lg text-white"></i>
                            </div>
                            <h6 class="text-info mb-0">Permits</h6>
                            <small class="text-muted">Business Permits</small>
                        </div>
                    </a>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                    <a href="<?= base_url('reports') ?>" class="card border-0 shadow h-100 text-decoration-none hover-shadow">
                        <div class="card-body text-center p-3">
                            <div class="bg-secondary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width:60px;height:60px;">
                                <i class="fas fa-chart-bar fa-lg text-white"></i>
                            </div>
                            <h6 class="text-secondary mb-0">Reports</h6>
                            <small class="text-muted">Generate Reports</small>
                        </div>
                    </a>
                </div>
            </div>

            <!-- ================================================
                 RECENT ACTIVITIES & CHART
            ================================================ -->
            <div class="row mb-4">

                <!-- Recent Activities -->
                <div class="col-lg-8">
                    <div class="card shadow">
                        <div class="card-header border-0 pb-0">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-clock text-info mr-2"></i>Recent Activities
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-sm mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Activity</th>
                                            <th width="120">Type</th>
                                            <th width="170">Date &amp; Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($recentActivities)): ?>
                                            <?php foreach ($recentActivities as $activity): ?>
                                            <tr>
                                                <td><?= esc($activity['activity']) ?></td>
                                                <td>
                                                    <span class="badge badge-<?= esc($activity['badge']) ?>">
                                                        <?= esc($activity['type']) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <small class="text-muted">
                                                        <?= date('M d, Y h:i A', strtotime($activity['created_at'])) ?>
                                                    </small>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="3" class="text-center text-muted py-4">
                                                    <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                                    No recent activities found.
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Monthly Stats Chart -->
                <div class="col-lg-4">
                    <div class="card shadow">
                        <div class="card-header border-0 pb-0">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-chart-line text-success mr-2"></i>Monthly Stats
                            </h5>
                        </div>
                        <div class="card-body">
                            <canvas id="statsChart" height="200"></canvas>
                        </div>
                    </div>
                </div>

            </div>

            <!-- ================================================
                 UPCOMING EVENTS / ALERTS TABS
            ================================================ -->
            <div class="row mt-2">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header bg-light p-0 border-bottom-0">
                            <ul class="nav nav-tabs card-header-tabs" id="notificationTab">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#events" data-toggle="tab">
                                        <i class="fas fa-calendar-alt mr-1"></i>Events
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#notifications" data-toggle="tab">
                                        <i class="fas fa-bell mr-1"></i>Alerts
                                        <?php if (!empty($alerts) && $alerts[0]['type'] !== 'success'): ?>
                                            <span class="badge badge-danger ml-1"><?= count($alerts) ?></span>
                                        <?php endif; ?>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">

                                <!-- EVENTS TAB -->
                                <div class="tab-pane fade show active" id="events">
                                    <?php if (!empty($upcomingEvents)): ?>
                                        <div class="row">
                                            <?php foreach ($upcomingEvents as $event): ?>
                                            <div class="col-md-3 col-sm-6 mb-3">
                                                <div class="card border-left-<?= esc($event['color']) ?> shadow-sm h-100">
                                                    <div class="card-body p-3">
                                                        <div class="d-flex align-items-center mb-2">
                                                            <div class="bg-<?= esc($event['color']) ?> rounded p-2 mr-2">
                                                                <i class="fas <?= esc($event['icon']) ?> text-white fa-sm"></i>
                                                            </div>
                                                            <strong class="text-<?= esc($event['color']) ?>">
                                                                <?= esc($event['title']) ?>
                                                            </strong>
                                                        </div>
                                                        <div class="text-muted">
                                                            <i class="fas fa-calendar mr-1"></i>
                                                            <small><?= date('F d, Y', strtotime($event['date'])) ?></small>
                                                        </div>
                                                        <div class="mt-1">
                                                            <small class="text-muted">
                                                                <?= ceil((strtotime($event['date']) - time()) / 86400) ?> day(s) from now
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <p class="text-muted text-center py-4">
                                            <i class="fas fa-calendar-times fa-2x mb-2 d-block"></i>
                                            No upcoming events.
                                        </p>
                                    <?php endif; ?>
                                </div>

                                <!-- ALERTS TAB -->
                                <div class="tab-pane fade" id="notifications">
                                    <?php if (!empty($alerts)): ?>
                                        <?php foreach ($alerts as $alert): ?>
                                        <div class="alert alert-<?= esc($alert['type']) ?> d-flex align-items-center justify-content-between mb-2">
                                            <div>
                                                <i class="fas <?= esc($alert['icon']) ?> mr-2"></i>
                                                <?= esc($alert['message']) ?>
                                            </div>
                                            <?php if (!empty($alert['link'])): ?>
                                                <a href="<?= esc($alert['link']) ?>" class="btn btn-sm btn-<?= esc($alert['type']) ?> ml-3">
                                                    <?= esc($alert['label']) ?>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="alert alert-success mb-0">
                                            <i class="fas fa-check-circle mr-2"></i>
                                            No urgent alerts at this time. All systems normal.
                                        </div>
                                    <?php endif; ?>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END EVENTS / ALERTS -->

        </div>
    </section>
</div>

<style>
/* Quick action cards hover */
.hover-shadow { transition: transform 0.25s ease, box-shadow 0.25s ease; }
.hover-shadow:hover { transform: translateY(-4px); box-shadow: 0 10px 25px rgba(0,0,0,0.18) !important; }

/* Gradient backgrounds */
.bg-gradient-primary { background: linear-gradient(45deg, #007bff, #0056b3) !important; }
.bg-gradient-info    { background: linear-gradient(45deg, #17a2b8, #117a8b) !important; }
.bg-gradient-success { background: linear-gradient(45deg, #28a745, #1e7e34) !important; }
.bg-gradient-warning { background: linear-gradient(45deg, #ffc107, #e0a800) !important; }

/* Left border accents for event cards */
.border-left-primary { border-left: 4px solid #007bff !important; }
.border-left-success { border-left: 4px solid #28a745 !important; }
.border-left-warning { border-left: 4px solid #ffc107 !important; }
.border-left-danger  { border-left: 4px solid #dc3545 !important; }
.border-left-info    { border-left: 4px solid #17a2b8 !important; }

.text-bold { font-weight: 700 !important; }
</style>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Pass PHP data to JS
const chartLabels = <?= json_encode(array_column($monthlyStats ?? [], 'label')) ?>;
const chartCounts = <?= json_encode(array_column($monthlyStats ?? [], 'count')) ?>;

const ctx = document.getElementById('statsChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: chartLabels,
        datasets: [{
            label: 'New Registrations',
            data: chartCounts,
            borderColor: '#007bff',
            backgroundColor: 'rgba(0, 123, 255, 0.08)',
            borderWidth: 2,
            pointBackgroundColor: '#007bff',
            pointRadius: 4,
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { precision: 0 }
            }
        }
    }
});
</script>
<?= $this->endSection() ?>
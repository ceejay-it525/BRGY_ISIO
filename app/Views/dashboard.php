<?= $this->extend('theme/template') ?>

<?= $this->section('content') ?>
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-bold">
                        <i class="fas fa-home text-primary me-2"></i>Barangay Dashboard
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url() ?>"><i class="fas fa-tachometer-alt"></i> Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
         <!-- BARANGAY STATS CARDS -->
<div class="row mb-4">
    <!-- Total Residents -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-gradient-info shadow">
            <div class="inner">
                <h3 id="totalResidents" class="text-white"><?= $totalResidents ?? 0 ?></h3>
                <p class="text-white">Total Residents</p>
            </div>
            <div class="icon">
                <i class="fas fa-users fa-2x"></i>
            </div>
            <a href="<?= base_url('residents') ?>" class="small-box-footer text-white">
                View <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- Active Officials -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-gradient-primary shadow">
            <div class="inner">
                <h3 id="activeOfficials" class="text-white"><?= $activeOfficials ?? 0 ?></h3>
                <p class="text-white">Active Officials</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-tie fa-2x"></i>
            </div>
            <a href="<?= base_url('barangay-officials') ?>" class="small-box-footer text-white">
                Manage <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- Pending Blotter -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-gradient-warning shadow">
            <div class="inner">
                <h3 id="pendingBlotter" class="text-dark"><?= $pendingBlotter ?? 0 ?></h3>
                <p class="text-dark">Complaints</p>
            </div>
            <div class="icon">
                <i class="fas fa-exclamation-triangle fa-2x"></i>
            </div>
            <a href="<?= base_url('blotter') ?>" class="small-box-footer">
                View All <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- Business Permits -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-gradient-success shadow">
            <div class="inner">
                <h3 id="businessPermits" class="text-white"><?= $activePermits ?? 0 ?></h3>
                <p class="text-white">Permits</p>
            </div>
            <div class="icon">
                <i class="fas fa-store fa-2x"></i>
            </div>
            <a href="<?= base_url('business-permits') ?>" class="small-box-footer text-white">
                Renewals <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>
            <!-- QUICK ACTIONS ROW -->
            <div class="row mb-4">
                <div class="col-12">
                    <h5 class="mb-3">
                        <i class="fas fa-rocket text-primary me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <a href="<?= base_url('residents') ?>" class="card border-0 shadow h-100 text-decoration-none hover-shadow">
                        <div class="card-body text-center p-3">
                            <div class="bg-light rounded-circle mx-auto mb-3 p-3" style="width: 70px; height: 70px;">
                                <i class="fas fa-users fa-2x text-primary"></i>
                            </div>
                            <h6 class="text-primary mb-0">Residents</h6>
                            <small class="text-muted">Manage Residents</small>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <a href="<?= base_url('barangay-officials') ?>" class="card border-0 shadow h-100 text-decoration-none hover-shadow">
                        <div class="card-body text-center p-3">
                            <div class="bg-warning rounded-circle mx-auto mb-3 p-3" style="width: 70px; height: 70px;">
                                <i class="fas fa-user-tie fa-2x text-dark"></i>
                            </div>
                            <h6 class="text-warning mb-0">Officials</h6>
                            <small class="text-muted">Barangay Officials</small>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <a href="<?= base_url('blotter') ?>" class="card border-0 shadow h-100 text-decoration-none hover-shadow">
                        <div class="card-body text-center p-3">
                            <div class="bg-danger rounded-circle mx-auto mb-3 p-3" style="width: 70px; height: 70px;">
                                <i class="fas fa-file-alt fa-2x text-white"></i>
                            </div>
                            <h6 class="text-danger mb-0">Blotter</h6>
                            <small class="text-muted">Complaints Log</small>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <a href="<?= base_url('clearances') ?>" class="card border-0 shadow h-100 text-decoration-none hover-shadow">
                        <div class="card-body text-center p-3">
                            <div class="bg-success rounded-circle mx-auto mb-3 p-3" style="width: 70px; height: 70px;">
                                <i class="fas fa-certificate fa-2x text-white"></i>
                            </div>
                            <h6 class="text-success mb-0">Clearances</h6>
                            <small class="text-muted">Issue Certificates</small>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <a href="<?= base_url('business-permits') ?>" class="card border-0 shadow h-100 text-decoration-none hover-shadow">
                        <div class="card-body text-center p-3">
                            <div class="bg-info rounded-circle mx-auto mb-3 p-3" style="width: 70px; height: 70px;">
                                <i class="fas fa-store fa-2x text-white"></i>
                            </div>
                            <h6 class="text-info mb-0">Permits</h6>
                            <small class="text-muted">Business Permits</small>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <a href="<?= base_url('reports') ?>" class="card border-0 shadow h-100 text-decoration-none hover-shadow">
                        <div class="card-body text-center p-3">
                            <div class="bg-secondary rounded-circle mx-auto mb-3 p-3" style="width: 70px; height: 70px;">
                                <i class="fas fa-chart-bar fa-2x text-white"></i>
                            </div>
                            <h6 class="text-secondary mb-0">Reports</h6>
                            <small class="text-muted">Generate Reports</small>
                        </div>
                    </a>
                </div>
            </div>

<!-- RECENT ACTIVITIES & CHARTS -->
<div class="row">
    <!-- Recent Activities -->
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header border-0 pb-0">
                <h5 class="card-title">
                    <i class="fas fa-clock text-info me-2"></i>Recent Activities
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Activity</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="recentActivities">
                            <tr>
                                <td>
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <strong>System</strong> Dashboard loaded successfully
                                        </div>
                                    </div>
                                </td>
                                <td>Just now</td>
                                <td><span class="badge bg-success">Success</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Chart -->
    <div class="col-lg-4">
        <div class="card shadow">
            <div class="card-header border-0 pb-0">
                <h5 class="card-title">
                    <i class="fas fa-chart-line text-success me-2"></i>Monthly Stats
                </h5>
            </div>
            <div class="card-body">
                <canvas id="statsChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- UPCOMING EVENTS / NOTIFICATIONS -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header bg-light">
                <ul class="nav nav-tabs card-header-tabs" id="notificationTab">
                    <li class="nav-item">
                        <a class="nav-link active" href="#events" data-bs-toggle="tab">Events</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#notifications" data-bs-toggle="tab">Alerts</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="events">
                        <div id="eventCalendar"></div>
                    </div>
                    <div class="tab-pane fade" id="notifications">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</section>
</div>

<style>
.hover-shadow:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(0,0,0,0.2) !important; transition: all 0.3s; }
.bg-gradient-primary { background: linear-gradient(45deg, #007bff, #0056b3) !important; }
.bg-gradient-info { background: linear-gradient(45deg, #17a2b8, #117a8b) !important; }
.bg-gradient-success { background: linear-gradient(45deg, #28a745, #1e7e34) !important; }
.bg-gradient-warning { background: linear-gradient(45deg, #ffc107, #e0a800) !important; }
.text-bold { font-weight: 700 !important; }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Initialize Chart.js
const ctx = document.getElementById('statsChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
            label: 'New Registrations',
            data: [12, 19, 3, 17, 6, 13],
            borderColor: '#007bff',
            tension: 0.4
        }]
    }
});
</script>

<?= $this->endSection() ?>
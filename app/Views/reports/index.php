<?= $this->extend('theme/template') ?>

<?= $this->section('content') ?>
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Reports & Statistics</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Reports</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

      <!-- Summary Cards -->
      <div class="row">
        <div class="col-lg-3 col-6">
          <div class="small-box bg-info">
            <div class="inner">
              <h3><?= $total_residents ?></h3>
              <p>Total Residents</p>
            </div>
            <div class="icon"><i class="fas fa-users"></i></div>
            <a href="<?= base_url('residents') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-success">
            <div class="inner">
              <h3><?= $total_households ?></h3>
              <p>Total Households</p>
            </div>
            <div class="icon"><i class="fas fa-home"></i></div>
            <a href="<?= base_url('households') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-warning">
            <div class="inner">
              <h3><?= $total_blotter ?></h3>
              <p>Blotter Records</p>
            </div>
            <div class="icon"><i class="fas fa-file-alt"></i></div>
            <a href="<?= base_url('blotter') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-danger">
            <div class="inner">
              <h3><?= $total_clearances ?></h3>
              <p>Clearances Issued</p>
            </div>
            <div class="icon"><i class="fas fa-certificate"></i></div>
            <a href="<?= base_url('clearances') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-3 col-6">
          <div class="small-box bg-secondary">
            <div class="inner">
              <h3><?= $total_officials ?></h3>
              <p>Active Officials</p>
            </div>
            <div class="icon"><i class="fas fa-user-tie"></i></div>
            <a href="<?= base_url('barangay-officials') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box" style="background:#6f42c1;color:#fff;">
            <div class="inner">
              <h3><?= $total_permits ?></h3>
              <p>Business Permits</p>
            </div>
            <div class="icon"><i class="fas fa-store"></i></div>
            <a href="<?= base_url('permits') ?>" class="small-box-footer" style="color:#fff;">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box" style="background:#20c997;color:#fff;">
            <div class="inner">
              <h3><?= $total_indigents ?></h3>
              <p>Indigent Records</p>
            </div>
            <div class="icon"><i class="fas fa-hand-holding-heart"></i></div>
            <a href="<?= base_url('indigents') ?>" class="small-box-footer" style="color:#fff;">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
      </div>

      <!-- Charts Row -->
      <div class="row">
        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Residents by Gender</h3>
            </div>
            <div class="card-body">
              <canvas id="genderChart" height="200"></canvas>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Residents by Status</h3>
            </div>
            <div class="card-body">
              <canvas id="statusChart" height="200"></canvas>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Top Blotter Types</h3>
            </div>
            <div class="card-body">
              <canvas id="blotterChart" height="200"></canvas>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Clearances Issued (Last 6 Months)</h3>
            </div>
            <div class="card-body">
              <canvas id="clearancesChart" height="200"></canvas>
            </div>
          </div>
        </div>
      </div>

      <!-- Data Tables Summary -->
      <div class="row">
        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Business Permits by Status</h3>
            </div>
            <div class="card-body p-0">
              <table class="table table-sm table-striped">
                <thead><tr><th>Status</th><th>Count</th></tr></thead>
                <tbody>
                  <?php foreach ($permits_by_status as $row): ?>
                  <tr><td><?= esc($row['status']) ?></td><td><?= $row['total'] ?></td></tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Indigents by Category</h3>
            </div>
            <div class="card-body p-0">
              <table class="table table-sm table-striped">
                <thead><tr><th>Category</th><th>Count</th></tr></thead>
                <tbody>
                  <?php foreach ($indigents_by_category as $row): ?>
                  <tr><td><?= esc($row['indigency_category']) ?></td><td><?= $row['total'] ?></td></tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script> const baseUrl = "<?= base_url() ?>"; </script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="<?= base_url('assets/js/reports.js') ?>"></script>
<script>
// Pass PHP data to JS
const genderData    = <?= json_encode($residents_by_gender) ?>;
const statusData    = <?= json_encode($residents_by_status) ?>;
const blotterData   = <?= json_encode($blotter_by_type) ?>;
const clearanceData = <?= json_encode($clearances_monthly) ?>;
</script>
<?= $this->endSection() ?>
<?= $this->extend('theme/template') ?>
<?= $this->section('content') ?>

<style>
/* ============================================================
   REPORTS & STATISTICS — UPGRADED DESIGN
   ============================================================ */
:root {
  --navy:   #1A3A5C;
  --blue:   #1D6FA4;
  --lblue:  #e8f4fd;
  --green:  #1a8a4a;
  --lgreen: #e3f8ee;
  --orange: #e07b00;
  --lorange:#fff3e0;
  --red:    #c0392b;
  --lred:   #fdeaea;
  --purple: #6c3fa0;
  --teal:   #0d8a7c;
  --gray:   #f0f4f8;
  --text:   #1a2e42;
  --muted:  #7a8fa6;
}

/* Page wrapper */
.rpt-page { 
  background:#f0f4f8; 
  min-height:100vh; 
  padding:0; 
  position: relative;
}

/* Background watermark */
.rpt-page::before {
  content: 'BARANGAY ISIO';
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  font-size: 8rem;
  font-weight: 900;
  color: rgba(26, 58, 92, 0.03);
  white-space: nowrap;
  z-index: 0;
  pointer-events: none;
  letter-spacing: 10px;
}

/* Hero header */
.rpt-hero {
  background: linear-gradient(135deg, #1a3a5c 0%, #1d6fa4 55%, #2196c4 100%);
  padding: 26px 30px 22px;
  position: relative; overflow: hidden;
  z-index: 1;
}
.rpt-hero::before {
  content:''; position:absolute; top:-50px; right:-50px;
  width:220px; height:220px; background:rgba(255,255,255,0.05); border-radius:50%;
}

/* Logo styling */
.rpt-logo {
  display: inline-flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 8px;
  position: relative;
  z-index: 10;
}
.rpt-logo-img {
  width: 60px;
  height: 60px;
  background: #fff;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.8rem;
  color: #1a3a5c;
  font-weight: 800;
  box-shadow: 0 4px 12px rgba(0,0,0,0.2);
  border: 3px solid rgba(255,255,255,0.3);
}
.rpt-logo-text {
  color: #fff;
  font-size: 1rem;
  font-weight: 700;
  letter-spacing: 0.5px;
  line-height: 1.4;
  text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.rpt-hero h1 { color:#fff; font-size:1.7rem; font-weight:700; margin:0 0 3px; letter-spacing:-.3px; }
.rpt-hero p  { color:rgba(255,255,255,.65); font-size:.85rem; margin:0; }
.rpt-hero .breadcrumb-row { color:rgba(255,255,255,.5); font-size:.78rem; margin-bottom:10px; }
.rpt-hero .breadcrumb-row a { color:rgba(255,255,255,.75); text-decoration:none; }

/* Summary stat cards */
.stat-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 0; background:#d0dbe8; border-bottom:1px solid #d0dbe8;
  position: relative;
  z-index: 2;
}
.stat-card {
  background:#fff; padding:20px 22px;
  display:flex; align-items:center; gap:14px;
  text-decoration:none; transition:background .15s;
  position:relative; overflow:hidden;
}
.stat-card:hover { background:#f7fafd; }
.stat-card::after {
  content:''; position:absolute; bottom:0; left:0; right:0;
  height:3px; background:var(--accent, #1d6fa4);
  transform:scaleX(0); transform-origin:left;
  transition:transform .2s;
}
.stat-card:hover::after { transform:scaleX(1); }
.stat-icon {
  width:48px; height:48px; border-radius:12px;
  display:flex; align-items:center; justify-content:center;
  font-size:1.2rem; flex-shrink:0;
}
.stat-label { font-size:.7rem; font-weight:700; letter-spacing:.5px; text-transform:uppercase; color:var(--muted); margin-bottom:2px; }
.stat-value { font-size:1.55rem; font-weight:800; color:var(--text); line-height:1; }

/* Section title */
.section-title {
  display:flex; align-items:center; gap:10px;
  font-size:.95rem; font-weight:700; color:var(--navy);
  padding:18px 22px 0; margin-bottom:14px;
}
.section-title i { color:var(--blue); }
.section-title .badge-count {
  background:var(--lblue); color:var(--blue);
  font-size:.7rem; font-weight:700; padding:2px 8px;
  border-radius:10px; margin-left:auto;
}

/* Content wrapper */
.rpt-body { padding:20px 24px; position: relative; z-index: 2; }

/* Card base */
.rpt-card {
  background:#fff; border-radius:10px;
  box-shadow:0 1px 4px rgba(0,0,0,.07), 0 4px 18px rgba(0,0,0,.04);
  overflow:hidden; margin-bottom:20px;
}
.rpt-card-header {
  padding:14px 20px; border-bottom:1px solid #e8edf3;
  display:flex; align-items:center; justify-content:space-between;
}
.rpt-card-header h4 {
  font-size:.9rem; font-weight:700; color:var(--navy); margin:0;
  display:flex; align-items:center; gap:8px;
}
.rpt-card-header h4 i { color:var(--blue); font-size:.85rem; }
.rpt-card-body { padding:18px 20px; }

/* Chart containers */
.chart-wrap { position:relative; height:260px; }
.chart-wrap-sm { position:relative; height:200px; }

/* Stat table inside card */
.stat-table { width:100%; border-collapse:collapse; }
.stat-table th {
  font-size:.7rem; font-weight:700; letter-spacing:.5px; text-transform:uppercase;
  color:var(--muted); padding:8px 12px; border-bottom:2px solid #e8edf3;
  background:#f9fbfd; text-align:left;
}
.stat-table td {
  padding:10px 12px; border-bottom:1px solid #edf2f7;
  font-size:.84rem; color:var(--text); vertical-align:middle;
}
.stat-table tr:last-child td { border-bottom:none; }
.stat-table tr:hover td { background:#f7fafd; }
.stat-table td:last-child { text-align:right; }

/* Progress bar inside table */
.prog-wrap { display:flex; align-items:center; gap:10px; }
.prog-bar { flex:1; height:6px; background:#edf2f7; border-radius:3px; overflow:hidden; }
.prog-fill { height:100%; border-radius:3px; transition:width .4s ease; }
.prog-count { font-size:.8rem; font-weight:700; color:var(--text); min-width:28px; text-align:right; }

/* Badge pills */
.pill {
  display:inline-flex; align-items:center; gap:4px;
  padding:3px 10px; border-radius:20px; font-size:.72rem; font-weight:700;
}
.pill-blue    { background:var(--lblue);   color:var(--blue); }
.pill-green   { background:var(--lgreen);  color:var(--green); }
.pill-orange  { background:var(--lorange); color:var(--orange); }
.pill-red     { background:var(--lred);    color:var(--red); }
.pill-gray    { background:#f0f4f8;        color:#5a7080; }

/* Expiry warning rows */
.expiry-urgent td { background:#fff8f0 !important; }
.expiry-warn  td  { background:#fffdf0 !important; }

/* Empty state */
.empty-state { text-align:center; padding:40px 20px; color:var(--muted); }
.empty-state i { font-size:2rem; display:block; margin-bottom:10px; opacity:.4; }
.empty-state p { font-size:.85rem; margin:0; }

/* Log entries */
.log-item {
  display:flex; align-items:center; gap:12px;
  padding:9px 0; border-bottom:1px solid #edf2f7;
}
.log-item:last-child { border-bottom:none; }
.log-icon {
  width:32px; height:32px; border-radius:8px; flex-shrink:0;
  display:flex; align-items:center; justify-content:center; font-size:.75rem;
}
.log-action { font-size:.83rem; color:var(--text); font-weight:500; line-height:1.3; }
.log-meta   { font-size:.73rem; color:var(--muted); margin-top:1px; }
.log-badge  { margin-left:auto; }

/* Quick-stats row inside card */
.qs-row { display:flex; gap:0; border-top:1px solid #edf2f7; }
.qs-item {
  flex:1; padding:12px 16px; text-align:center;
  border-right:1px solid #edf2f7;
}
.qs-item:last-child { border-right:none; }
.qs-val  { font-size:1.3rem; font-weight:800; color:var(--navy); line-height:1; }
.qs-lbl  { font-size:.68rem; font-weight:600; color:var(--muted); text-transform:uppercase; letter-spacing:.4px; margin-top:2px; }

/* Expiry countdown badge */
.days-badge {
  display:inline-block; padding:2px 8px; border-radius:10px;
  font-size:.72rem; font-weight:700;
}
.days-urgent { background:#fdeaea; color:#c0392b; }
.days-warn   { background:#fff3cd; color:#856404; }
.days-ok     { background:#e3f8ee; color:#1a7a40; }
</style>

<div class="rpt-page content-wrapper">

  <!-- Hero -->
  <div class="rpt-hero">
    <div class="rpt-logo" style="display: inline-flex; align-items: center; gap: 12px; margin-bottom: 8px; position: relative; z-index: 10;">
      <img src="<?= base_url('assets/img/isio.jpeg.png') ?>" alt="Barangay Isio Logo" style="width: 60px; height: 60px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.2); border: 3px solid rgba(255,255,255,0.3); object-fit: cover;">
      <div class="rpt-logo-text" style="color: #fff; font-size: 1rem; font-weight: 700; letter-spacing: 0.5px; line-height: 1.4; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">Republic of the Philippines<br>Barangay Isio</div>
    </div>
    <div class="breadcrumb-row">
      <a href="<?= base_url('/') ?>"><i class="fas fa-home"></i> Home</a>
      <span style="margin:0 6px;">/</span> Reports & Statistics
    </div>
    <h1><i class="fas fa-chart-bar" style="margin-right:8px;opacity:.85;"></i>Reports & Statistics</h1>
    <p>Real-time data overview of Barangay Isio's records and operations</p>
  </div>

  <!-- ── STAT CARDS ───────────────────────────────────────── -->
  <div class="stat-grid">
    <a href="<?= base_url('residents') ?>" class="stat-card" style="--accent:#1d6fa4">
      <div class="stat-icon" style="background:#e3f0fb;color:#1d6fa4;"><i class="fas fa-users"></i></div>
      <div>
        <div class="stat-label">Total Residents</div>
        <div class="stat-value"><?= $total_residents ?></div>
      </div>
    </a>
    <a href="<?= base_url('households') ?>" class="stat-card" style="--accent:#1a8a4a">
      <div class="stat-icon" style="background:#e3f8ee;color:#1a8a4a;"><i class="fas fa-home"></i></div>
      <div>
        <div class="stat-label">Total Households</div>
        <div class="stat-value"><?= $total_households ?></div>
      </div>
    </a>
    <a href="<?= base_url('blotter') ?>" class="stat-card" style="--accent:#e07b00">
      <div class="stat-icon" style="background:#fff3e0;color:#e07b00;"><i class="fas fa-gavel"></i></div>
      <div>
        <div class="stat-label">Blotter Records</div>
        <div class="stat-value"><?= $total_blotter ?></div>
      </div>
    </a>
    <a href="<?= base_url('clearances') ?>" class="stat-card" style="--accent:#c0392b">
      <div class="stat-icon" style="background:#fdeaea;color:#c0392b;"><i class="fas fa-file-alt"></i></div>
      <div>
        <div class="stat-label">Clearances Issued</div>
        <div class="stat-value"><?= $total_clearances ?></div>
      </div>
    </a>
    <a href="<?= base_url('barangay_officials') ?>" class="stat-card" style="--accent:#5a6070">
      <div class="stat-icon" style="background:#f0f2f5;color:#5a6070;"><i class="fas fa-user-tie"></i></div>
      <div>
        <div class="stat-label">Active Officials</div>
        <div class="stat-value"><?= $total_officials ?></div>
      </div>
    </a>
    <a href="<?= base_url('permits') ?>" class="stat-card" style="--accent:#6c3fa0">
      <div class="stat-icon" style="background:#f0e8fb;color:#6c3fa0;"><i class="fas fa-store"></i></div>
      <div>
        <div class="stat-label">Business Permits</div>
        <div class="stat-value"><?= $total_permits ?></div>
      </div>
    </a>
    <a href="<?= base_url('indigents') ?>" class="stat-card" style="--accent:#0d8a7c">
      <div class="stat-icon" style="background:#e0f5f2;color:#0d8a7c;"><i class="fas fa-hand-holding-heart"></i></div>
      <div>
        <div class="stat-label">Indigent Records</div>
        <div class="stat-value"><?= $total_indigents ?></div>
      </div>
    </a>
    <div class="stat-card" style="--accent:#1d6fa4">
      <div class="stat-icon" style="background:#e3f0fb;color:#1d6fa4;"><i class="fas fa-vote-yea"></i></div>
      <div>
        <div class="stat-label">Registered Voters</div>
        <div class="stat-value"><?= $total_voters ?></div>
      </div>
    </div>
  </div>

  <div class="rpt-body">

    <!-- ── ROW 1: CHARTS ──────────────────────────────────── -->
    <div class="row">

      <!-- Gender Doughnut -->
      <div class="col-lg-3 col-md-6">
        <div class="rpt-card">
          <div class="rpt-card-header">
            <h4><i class="fas fa-venus-mars"></i> Residents by Gender</h4>
          </div>
          <div class="rpt-card-body">
            <div class="chart-wrap-sm"><canvas id="chartGender"></canvas></div>
            <div class="qs-row mt-2">
              <div class="qs-item">
                <div class="qs-val" style="color:#1566a0;"><?= $male_residents ?></div>
                <div class="qs-lbl">Male</div>
              </div>
              <div class="qs-item">
                <div class="qs-val" style="color:#b0254f;"><?= $female_residents ?></div>
                <div class="qs-lbl">Female</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Civil Status Doughnut -->
      <div class="col-lg-3 col-md-6">
        <div class="rpt-card">
          <div class="rpt-card-header">
            <h4><i class="fas fa-heart"></i> Civil Status</h4>
          </div>
          <div class="rpt-card-body">
            <div class="chart-wrap-sm"><canvas id="chartCivil"></canvas></div>
          </div>
        </div>
      </div>

      <!-- Resident Status Doughnut -->
      <div class="col-lg-3 col-md-6">
        <div class="rpt-card">
          <div class="rpt-card-header">
            <h4><i class="fas fa-circle"></i> Resident Status</h4>
          </div>
          <div class="rpt-card-body">
            <div class="chart-wrap-sm"><canvas id="chartStatus"></canvas></div>
          </div>
        </div>
      </div>

      <!-- Age Groups Doughnut -->
      <div class="col-lg-3 col-md-6">
        <div class="rpt-card">
          <div class="rpt-card-header">
            <h4><i class="fas fa-child"></i> Age Groups</h4>
          </div>
          <div class="rpt-card-body">
            <div class="chart-wrap-sm"><canvas id="chartAge"></canvas></div>
            <div class="qs-row mt-2">
              <?php foreach ($age_groups as $ag): ?>
              <div class="qs-item">
                <div class="qs-val"><?= $ag['total'] ?></div>
                <div class="qs-lbl"><?= explode(' ', $ag['label'])[0] ?></div>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ── ROW 2: MONTHLY TRENDS ─────────────────────────── -->
    <div class="row">
      <div class="col-lg-8">
        <div class="rpt-card">
          <div class="rpt-card-header">
            <h4><i class="fas fa-chart-line"></i> Monthly Residents Registered (Last 6 Months)</h4>
          </div>
          <div class="rpt-card-body">
            <div class="chart-wrap"><canvas id="chartMonthly"></canvas></div>
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="rpt-card">
          <div class="rpt-card-header">
            <h4><i class="fas fa-file-check"></i> Clearances (Last 6 Months)</h4>
          </div>
          <div class="rpt-card-body">
            <div class="chart-wrap"><canvas id="chartClearancesMonthly"></canvas></div>
          </div>
        </div>
      </div>
    </div>

    <!-- ── ROW 3: BLOTTER + CLEARANCES ───────────────────── -->
    <div class="row">

      <!-- Blotter by Type -->
      <div class="col-lg-4 col-md-6">
        <div class="rpt-card">
          <div class="rpt-card-header">
            <h4><i class="fas fa-exclamation-triangle"></i> Top Blotter Types</h4>
            <span class="badge-count"><?= count($blotter_by_type) ?></span>
          </div>
          <?php if (empty($blotter_by_type)): ?>
            <div class="empty-state"><i class="fas fa-gavel"></i><p>No blotter records</p></div>
          <?php else: ?>
          <table class="stat-table">
            <thead><tr><th>Incident Type</th><th style="text-align:right;">Count</th></tr></thead>
            <tbody>
              <?php
                $maxB = max(array_column($blotter_by_type, 'total')) ?: 1;
                $colors = ['#e07b00','#c0392b','#1d6fa4','#6c3fa0','#0d8a7c','#1a8a4a'];
                foreach ($blotter_by_type as $i => $row):
                  $pct = round($row['total'] / $maxB * 100);
                  $clr = $colors[$i % count($colors)];
              ?>
              <tr>
                <td>
                  <div class="prog-wrap">
                    <div style="font-size:.82rem;min-width:100px;"><?= esc($row['incident_type']) ?></div>
                    <div class="prog-bar"><div class="prog-fill" style="width:<?= $pct ?>%;background:<?= $clr ?>;"></div></div>
                  </div>
                </td>
                <td><span class="pill pill-orange"><?= $row['total'] ?></span></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <?php endif; ?>
        </div>
      </div>

      <!-- Blotter by Status -->
      <div class="col-lg-4 col-md-6">
        <div class="rpt-card">
          <div class="rpt-card-header">
            <h4><i class="fas fa-tasks"></i> Blotter by Status</h4>
          </div>
          <?php if (empty($blotter_by_status)): ?>
            <div class="empty-state"><i class="fas fa-tasks"></i><p>No data</p></div>
          <?php else: ?>
          <div class="rpt-card-body" style="padding-top:8px;">
            <div style="height:200px;"><canvas id="chartBlotterStatus"></canvas></div>
          </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Clearances by Type -->
      <div class="col-lg-4 col-md-6">
        <div class="rpt-card">
          <div class="rpt-card-header">
            <h4><i class="fas fa-file-alt"></i> Clearances by Type</h4>
          </div>
          <?php if (empty($clearances_by_type)): ?>
            <div class="empty-state"><i class="fas fa-file-alt"></i><p>No clearances</p></div>
          <?php else: ?>
          <table class="stat-table">
            <thead><tr><th>Type</th><th style="text-align:right;">Count</th></tr></thead>
            <tbody>
              <?php foreach ($clearances_by_type as $row): ?>
              <tr>
                <td style="font-size:.82rem;"><?= esc($row['type_name'] ?? 'Unknown') ?></td>
                <td><span class="pill pill-blue"><?= $row['total'] ?></span></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- ── ROW 4: PERMITS + INDIGENTS ────────────────────── -->
    <div class="row">

      <!-- Permits by Status -->
      <div class="col-lg-3 col-md-6">
        <div class="rpt-card">
          <div class="rpt-card-header">
            <h4><i class="fas fa-store"></i> Permits by Status</h4>
          </div>
          <?php if (empty($permits_by_status)): ?>
            <div class="empty-state"><i class="fas fa-store"></i><p>No permit data</p></div>
          <?php else: ?>
          <table class="stat-table">
            <thead><tr><th>Status</th><th style="text-align:right;">Count</th></tr></thead>
            <tbody>
              <?php foreach ($permits_by_status as $row):
                $pc = $row['status']==='Active' ? 'pill-green' : ($row['status']==='Expired' ? 'pill-red' : 'pill-orange');
              ?>
              <tr>
                <td style="font-size:.82rem;"><?= esc($row['status']) ?></td>
                <td><span class="pill <?= $pc ?>"><?= $row['total'] ?></span></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <?php endif; ?>
        </div>
      </div>

      <!-- Permits Expiring Soon -->
      <div class="col-lg-5">
        <div class="rpt-card">
          <div class="rpt-card-header">
            <h4><i class="fas fa-clock" style="color:#e07b00;"></i> Permits Expiring Soon (30 days)</h4>
            <span class="badge-count" style="background:#fff3e0;color:#e07b00;">
              <?= count($permits_expiring_soon) ?>
            </span>
          </div>
          <?php if (empty($permits_expiring_soon)): ?>
            <div class="empty-state"><i class="fas fa-check-circle" style="opacity:.3;color:#1a8a4a;"></i><p>No permits expiring in 30 days</p></div>
          <?php else: ?>
          <table class="stat-table">
            <thead><tr><th>Business</th><th>Owner</th><th>Expiry</th><th>Days Left</th></tr></thead>
            <tbody>
              <?php foreach ($permits_expiring_soon as $row):
                $days = (int)round((strtotime($row['expiry_date']) - time()) / 86400);
                $dc = $days <= 7 ? 'days-urgent' : ($days <= 14 ? 'days-warn' : 'days-ok');
                $rc = $days <= 7 ? 'expiry-urgent' : 'expiry-warn';
              ?>
              <tr class="<?= $rc ?>">
                <td style="font-size:.8rem;font-weight:600;"><?= esc($row['business_name']) ?></td>
                <td style="font-size:.78rem;"><?= esc($row['owner_name']) ?></td>
                <td style="font-size:.78rem;"><?= $row['expiry_date'] ?></td>
                <td><span class="days-badge <?= $dc ?>"><?= $days ?>d</span></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <?php endif; ?>
        </div>
      </div>

      <!-- Indigents by Category -->
      <div class="col-lg-4 col-md-6">
        <div class="rpt-card">
          <div class="rpt-card-header">
            <h4><i class="fas fa-hand-holding-heart"></i> Indigents by Category</h4>
          </div>
          <?php if (empty($indigents_by_category)): ?>
            <div class="empty-state"><i class="fas fa-hand-holding-heart"></i><p>No indigent data</p></div>
          <?php else: ?>
          <div class="rpt-card-body" style="padding-top:8px;">
            <div style="height:200px;"><canvas id="chartIndigents"></canvas></div>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- ── ROW 5: HOUSEHOLDS + OFFICIALS + LOGS ──────────── -->
    <div class="row">

      <!-- Households by Purok -->
      <div class="col-lg-3 col-md-6">
        <div class="rpt-card">
          <div class="rpt-card-header">
            <h4><i class="fas fa-map-marker-alt"></i> Households by Purok</h4>
          </div>
          <?php if (empty($households_by_purok)): ?>
            <div class="empty-state"><i class="fas fa-map-marker-alt"></i><p>No data</p></div>
          <?php else: ?>
          <table class="stat-table">
            <thead><tr><th>Purok</th><th style="text-align:right;">Households</th></tr></thead>
            <tbody>
              <?php foreach ($households_by_purok as $row): ?>
              <tr>
                <td style="font-size:.82rem;">Purok <?= esc($row['purok']) ?></td>
                <td><span class="pill pill-blue"><?= $row['total'] ?></span></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <?php endif; ?>
        </div>
      </div>

      <!-- Officials by Position -->
      <div class="col-lg-3 col-md-6">
        <div class="rpt-card">
          <div class="rpt-card-header">
            <h4><i class="fas fa-user-tie"></i> Officials by Position</h4>
          </div>
          <?php if (empty($officials_by_position)): ?>
            <div class="empty-state"><i class="fas fa-user-tie"></i><p>No officials data</p></div>
          <?php else: ?>
          <table class="stat-table">
            <thead><tr><th>Position</th><th style="text-align:right;">Count</th></tr></thead>
            <tbody>
              <?php foreach ($officials_by_position as $row): ?>
              <tr>
                <td style="font-size:.82rem;"><?= esc($row['position']) ?></td>
                <td><span class="pill pill-gray"><?= $row['total'] ?></span></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <?php endif; ?>
        </div>
      </div>

      <!-- Indigent Assistance Amounts -->
      <div class="col-lg-3 col-md-6">
        <div class="rpt-card">
          <div class="rpt-card-header">
            <h4><i class="fas fa-peso-sign"></i> Assistance by Type</h4>
          </div>
          <?php if (empty($indigents_by_assistance)): ?>
            <div class="empty-state"><i class="fas fa-peso-sign"></i><p>No assistance data</p></div>
          <?php else: ?>
          <table class="stat-table">
            <thead><tr><th>Type</th><th style="text-align:right;">Total (₱)</th></tr></thead>
            <tbody>
              <?php foreach ($indigents_by_assistance as $row): ?>
              <tr>
                <td style="font-size:.82rem;"><?= esc($row['assistance_type']) ?></td>
                <td style="font-size:.82rem;font-weight:700;color:#1a8a4a;">
                  ₱<?= number_format($row['total_amount'] ?? 0, 2) ?>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <?php endif; ?>
        </div>
      </div>

      <!-- Recent Activity Logs -->
      <div class="col-lg-3 col-md-6">
        <div class="rpt-card">
          <div class="rpt-card-header">
            <h4><i class="fas fa-history"></i> Recent Activity</h4>
            <a href="<?= base_url('activity_logs') ?>" style="font-size:.75rem;color:var(--blue);">View All</a>
          </div>
          <div class="rpt-card-body" style="padding:8px 16px;">
            <?php if (empty($recent_logs)): ?>
              <div class="empty-state"><i class="fas fa-history"></i><p>No activity logs</p></div>
            <?php else: ?>
              <?php
                $logColors = [
                  'LOGIN'   => ['#e3f0fb','#1d6fa4','fa-sign-in-alt'],
                  'LOGOUT'  => ['#f0f0f0','#5a6070','fa-sign-out-alt'],
                  'ADD'     => ['#e3f8ee','#1a8a4a','fa-plus'],
                  'UPDATED' => ['#fff3e0','#e07b00','fa-edit'],
                  'DELETED' => ['#fdeaea','#c0392b','fa-trash'],
                ];
              ?>
              <?php foreach ($recent_logs as $log):
                $id = strtoupper($log['identifier'] ?? 'ADD');
                [$bg,$clr,$ico] = $logColors[$id] ?? ['#f0f4f8','#5a6070','fa-circle'];
              ?>
              <div class="log-item">
                <div class="log-icon" style="background:<?= $bg ?>;color:<?= $clr ?>;"><i class="fas <?= $ico ?>"></i></div>
                <div>
                  <div class="log-action"><?= esc(mb_strimwidth($log['ACTION'] ?? '', 0, 45, '…')) ?></div>
                  <div class="log-meta"><?= esc($log['USER_NAME'] ?? '—') ?> · <?= $log['DATELOG'] ?> <?= $log['TIMELOG'] ?></div>
                </div>
                <span class="log-badge">
                  <span class="pill" style="background:<?= $bg ?>;color:<?= $clr ?>;font-size:.65rem;"><?= $id ?></span>
                </span>
              </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <!-- ── ROW 6: LATEST BLOTTER TABLE ──────────────────── -->
    <div class="row">
      <div class="col-12">
        <div class="rpt-card">
          <div class="rpt-card-header">
            <h4><i class="fas fa-list-alt"></i> Latest Blotter Complaints</h4>
            <a href="<?= base_url('blotter') ?>" class="pill pill-orange">
              <i class="fas fa-arrow-right"></i> View All
            </a>
          </div>
          <?php
            $db = \Config\Database::connect();
            $latestBlotter = $db->table('blotter')
              ->where('deleted_at IS NULL')
              ->orderBy('id', 'DESC')
              ->limit(8)
              ->get()
              ->getResultArray();
          ?>
          <?php if (empty($latestBlotter)): ?>
            <div class="empty-state"><i class="fas fa-gavel"></i><p>No blotter complaints found.</p></div>
          <?php else: ?>
          <div style="overflow-x:auto;">
            <table class="stat-table" style="min-width:700px;">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Case No.</th>
                  <th>Type</th>
                  <th>Complainant</th>
                  <th>Respondent</th>
                  <th>Location</th>
                  <th>Status</th>
                  <th>Date</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($latestBlotter as $i => $b):
                  $sc = match($b['status']) {
                    'Settled'         => 'pill-green',
                    'Ongoing'         => 'pill-orange',
                    'Referred to Court'=> 'pill-red',
                    default           => 'pill-gray'
                  };
                ?>
                <tr>
                  <td style="color:var(--muted);font-size:.78rem;"><?= $i+1 ?></td>
                  <td><code style="font-size:.78rem;background:#f0f4f8;padding:2px 6px;border-radius:4px;"><?= esc($b['case_number']) ?></code></td>
                  <td style="font-size:.82rem;font-weight:600;"><?= esc($b['incident_type']) ?></td>
                  <td style="font-size:.82rem;"><?= esc($b['complainant_name']) ?></td>
                  <td style="font-size:.82rem;"><?= esc($b['respondent_name']) ?></td>
                  <td style="font-size:.78rem;color:var(--muted);"><?= esc($b['incident_location']) ?></td>
                  <td><span class="pill <?= $sc ?>"><?= esc($b['status']) ?></span></td>
                  <td style="font-size:.78rem;color:var(--muted);"><?= $b['incident_date'] ?></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

  </div><!-- /rpt-body -->
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// ── PHP → JS data ─────────────────────────────────────────────────────────────
const genderData        = <?= $chart_gender_labels ?>.map((l,i) => ({label:l, val:<?= $chart_gender_data ?>[i]}));
const statusData        = <?= json_encode($residents_by_status) ?>;
const civilData         = <?= $chart_civil_labels ?>.map((l,i) => ({label:l, val:<?= $chart_civil_data ?>[i]}));
const ageData           = <?= $chart_age_labels ?>.map((l,i) => ({label:l, val:<?= $chart_age_data ?>[i]}));
const monthlyLabels     = <?= $chart_monthly_labels ?>;
const monthlyVals       = <?= $chart_monthly_data ?>;
const blotterStatus     = <?= json_encode($blotter_by_status) ?>;
const indigentCategory  = <?= json_encode($indigents_by_category) ?>;
const clearMonthly      = <?= json_encode($clearances_monthly) ?>;

// ── Palette ────────────────────────────────────────────────────────────────────
const COLORS = ['#1d6fa4','#e07b00','#1a8a4a','#c0392b','#6c3fa0','#0d8a7c','#f39c12','#2980b9'];
const GENDER_COLORS  = ['#1566a0','#b0254f','#7b2db0'];
const STATUS_COLORS  = ['#1a8a4a','#856404','#555555','#0069c0'];
const CIVIL_COLORS   = ['#1d6fa4','#1a8a4a','#6c3fa0','#c0392b','#e07b00'];
const AGE_COLORS     = ['#1d6fa4','#1a8a4a','#e07b00'];

const defaultOpts = (cutout='65%') => ({
  responsive:true, maintainAspectRatio:false,
  cutout,
  plugins:{ legend:{ position:'bottom', labels:{ font:{size:10}, padding:8, boxWidth:10 } } }
});

// ── Gender ─────────────────────────────────────────────────────────────────────
new Chart(document.getElementById('chartGender'), {
  type:'doughnut',
  data:{ labels:genderData.map(d=>d.label), datasets:[{ data:genderData.map(d=>d.val), backgroundColor:GENDER_COLORS, borderWidth:2, borderColor:'#fff' }] },
  options:{ ...defaultOpts(), plugins:{ legend:{ position:'bottom', labels:{font:{size:10},padding:8,boxWidth:10} } } }
});

// ── Civil Status ───────────────────────────────────────────────────────────────
new Chart(document.getElementById('chartCivil'), {
  type:'doughnut',
  data:{ labels:civilData.map(d=>d.label), datasets:[{ data:civilData.map(d=>d.val), backgroundColor:CIVIL_COLORS, borderWidth:2, borderColor:'#fff' }] },
  options:defaultOpts()
});

// ── Resident Status ────────────────────────────────────────────────────────────
new Chart(document.getElementById('chartStatus'), {
  type:'doughnut',
  data:{ labels:statusData.map(d=>d.status), datasets:[{ data:statusData.map(d=>parseInt(d.total)), backgroundColor:STATUS_COLORS, borderWidth:2, borderColor:'#fff' }] },
  options:defaultOpts()
});

// ── Age Groups ─────────────────────────────────────────────────────────────────
new Chart(document.getElementById('chartAge'), {
  type:'doughnut',
  data:{ labels:ageData.map(d=>d.label), datasets:[{ data:ageData.map(d=>d.val), backgroundColor:AGE_COLORS, borderWidth:2, borderColor:'#fff' }] },
  options:defaultOpts()
});

// ── Monthly Residents (line) ───────────────────────────────────────────────────
new Chart(document.getElementById('chartMonthly'), {
  type:'bar',
  data:{
    labels: monthlyLabels.length ? monthlyLabels : ['No data'],
    datasets:[{
      label:'Residents Registered',
      data: monthlyVals.length ? monthlyVals : [0],
      backgroundColor:'rgba(29,111,164,0.15)',
      borderColor:'#1d6fa4', borderWidth:2,
      borderRadius:6, pointRadius:4
    }]
  },
  options:{
    responsive:true, maintainAspectRatio:false,
    plugins:{ legend:{ display:false } },
    scales:{
      y:{ beginAtZero:true, ticks:{stepSize:1,font:{size:11}}, grid:{color:'#edf2f7'} },
      x:{ ticks:{font:{size:11}}, grid:{display:false} }
    }
  }
});

// ── Monthly Clearances (bar) ───────────────────────────────────────────────────
new Chart(document.getElementById('chartClearancesMonthly'), {
  type:'bar',
  data:{
    labels: clearMonthly.length ? clearMonthly.map(d=>d.month_label) : ['No data'],
    datasets:[{
      label:'Clearances Issued',
      data: clearMonthly.length ? clearMonthly.map(d=>parseInt(d.total)) : [0],
      backgroundColor:'rgba(192,57,43,0.15)',
      borderColor:'#c0392b', borderWidth:2, borderRadius:6
    }]
  },
  options:{
    responsive:true, maintainAspectRatio:false,
    plugins:{ legend:{ display:false } },
    scales:{
      y:{ beginAtZero:true, ticks:{stepSize:1,font:{size:11}}, grid:{color:'#edf2f7'} },
      x:{ ticks:{font:{size:11}}, grid:{display:false} }
    }
  }
});

// ── Blotter by Status (horizontal bar) ────────────────────────────────────────
<?php if (!empty($blotter_by_status)): ?>
new Chart(document.getElementById('chartBlotterStatus'), {
  type:'bar',
  data:{
    labels: blotterStatus.map(d=>d.status),
    datasets:[{
      data: blotterStatus.map(d=>parseInt(d.total)),
      backgroundColor:['#fff3e0','#fdeaea','#e3f8ee','#f0f4f8'],
      borderColor:['#e07b00','#c0392b','#1a8a4a','#5a6070'],
      borderWidth:2, borderRadius:6
    }]
  },
  options:{
    indexAxis:'y', responsive:true, maintainAspectRatio:false,
    plugins:{ legend:{display:false} },
    scales:{
      x:{ beginAtZero:true, ticks:{stepSize:1,font:{size:11}}, grid:{color:'#edf2f7'} },
      y:{ ticks:{font:{size:11}}, grid:{display:false} }
    }
  }
});
<?php endif; ?>

// ── Indigents by Category (doughnut) ──────────────────────────────────────────
<?php if (!empty($indigents_by_category)): ?>
new Chart(document.getElementById('chartIndigents'), {
  type:'doughnut',
  data:{
    labels: indigentCategory.map(d=>d.indigency_category),
    datasets:[{ data: indigentCategory.map(d=>parseInt(d.total)), backgroundColor:COLORS, borderWidth:2, borderColor:'#fff' }]
  },
  options:defaultOpts()
});
<?php endif; ?>
</script>
<?= $this->endSection() ?>
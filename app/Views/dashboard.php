<?= $this->extend('theme/template') ?>

<?= $this->section('content') ?>
<div class="content-wrapper bmis-dashboard" id="bmisWrapper">

    <!-- ═══ TOPBAR ═══ -->
    <div class="dash-topbar">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-7">
                    <div class="d-flex align-items-center" style="gap:14px;">
                        <div class="topbar-icon-ring">
                            <i class="fas fa-tachometer-alt"></i>
                        </div>
                        <div>
                            <h1 class="topbar-title">Dashboard</h1>
                            <p class="topbar-sub">
                                <i class="fas fa-map-marker-alt mr-1"></i>Barangay Isio &mdash;
                                <span id="liveDateTime" class="live-clock"></span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-5 d-flex align-items-center justify-content-end" style="gap:12px;">
                    <button class="theme-toggle-btn" id="themeToggle" title="Toggle light / dark">
                        <div class="tt-track">
                            <div class="tt-thumb">
                                <i class="fas fa-sun tt-sun"></i>
                                <i class="fas fa-moon tt-moon"></i>
                            </div>
                        </div>
                        <span class="tt-label" id="ttLabel">Dark</span>
                    </button>
                    <ol class="breadcrumb mb-0 topbar-bc">
                        <li class="breadcrumb-item"><a href="<?= base_url() ?>"><i class="fas fa-home"></i></a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content dash-section">
        <div class="container-fluid">

            <!-- ═══ KPI CARDS ═══ -->
            <div class="row mb-4">

                <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 mb-3 kpi-col" style="--ki:0">
                    <a href="<?= base_url('residents') ?>" class="kpi-card kc-blue">
                        <div class="kc-bg-icon"><i class="fas fa-users"></i></div>
                        <div class="kc-icon-ring"><i class="fas fa-users"></i></div>
                        <div class="kc-num counter" data-target="<?= $totalResidents ?? 0 ?>">0</div>
                        <div class="kc-label">Residents</div>
                        <div class="kc-footer">View all <i class="fas fa-long-arrow-alt-right"></i></div>
                    </a>
                </div>

                <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 mb-3 kpi-col" style="--ki:1">
                    <a href="<?= base_url('households') ?>" class="kpi-card kc-teal">
                        <div class="kc-bg-icon"><i class="fas fa-home"></i></div>
                        <div class="kc-icon-ring"><i class="fas fa-home"></i></div>
                        <div class="kc-num counter" data-target="<?= $totalHouseholds ?? 0 ?>">0</div>
                        <div class="kc-label">Households</div>
                        <div class="kc-footer">View all <i class="fas fa-long-arrow-alt-right"></i></div>
                    </a>
                </div>

                <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 mb-3 kpi-col" style="--ki:2">
                    <a href="<?= base_url('barangay-officials') ?>" class="kpi-card kc-violet">
                        <div class="kc-bg-icon"><i class="fas fa-user-tie"></i></div>
                        <div class="kc-icon-ring"><i class="fas fa-user-tie"></i></div>
                        <div class="kc-num counter" data-target="<?= $activeOfficials ?? 0 ?>">0</div>
                        <div class="kc-label">Officials</div>
                        <div class="kc-footer">Manage <i class="fas fa-long-arrow-alt-right"></i></div>
                    </a>
                </div>

                <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 mb-3 kpi-col" style="--ki:3">
                    <a href="<?= base_url('blotter') ?>" class="kpi-card kc-red">
                        <div class="kc-bg-icon"><i class="fas fa-exclamation-triangle"></i></div>
                        <div class="kc-icon-ring"><i class="fas fa-exclamation-triangle"></i></div>
                        <div class="kc-num counter" data-target="<?= $totalBlotter ?? 0 ?>">0</div>
                        <div class="kc-label">Complaints</div>
                        <div class="kc-footer">View all <i class="fas fa-long-arrow-alt-right"></i></div>
                    </a>
                </div>

                <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 mb-3 kpi-col" style="--ki:4">
                    <a href="<?= base_url('clearances') ?>" class="kpi-card kc-emerald">
                        <div class="kc-bg-icon"><i class="fas fa-certificate"></i></div>
                        <div class="kc-icon-ring"><i class="fas fa-certificate"></i></div>
                        <div class="kc-num counter" data-target="<?= $totalClearances ?? 0 ?>">0</div>
                        <div class="kc-label">Clearances</div>
                        <div class="kc-footer">Issue <i class="fas fa-long-arrow-alt-right"></i></div>
                    </a>
                </div>

                <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 mb-3 kpi-col" style="--ki:5">
                    <a href="<?= base_url('permits') ?>" class="kpi-card kc-amber">
                        <div class="kc-bg-icon"><i class="fas fa-store"></i></div>
                        <div class="kc-icon-ring"><i class="fas fa-store"></i></div>
                        <div class="kc-num counter" data-target="<?= $totalPermits ?? 0 ?>">0</div>
                        <div class="kc-label">Permits</div>
                        <div class="kc-footer">Renewals <i class="fas fa-long-arrow-alt-right"></i></div>
                    </a>
                </div>

            </div>

            <!-- ═══ CHARTS ROW ═══ -->
            <div class="row mb-4">

                <!-- Monthly Registrations -->
                <div class="col-lg-8 mb-3 pcol" style="--pi:0">
                    <div class="dpanel h-100">
                        <div class="dpanel-head">
                            <div class="dpanel-title">
                                <span class="live-dot ld-blue"></span>
                                Monthly Registrations
                            </div>
                            <div class="d-flex align-items-center" style="gap:6px;">
                                <span class="dpill dpill-blue">This Year</span>
                                <button class="ctype-btn active" onclick="switchChart('line',this)"><i class="fas fa-chart-line"></i></button>
                                <button class="ctype-btn" onclick="switchChart('bar',this)"><i class="fas fa-chart-bar"></i></button>
                            </div>
                        </div>
                        <div class="dpanel-body">
                            <canvas id="statsChart" height="115"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Services Breakdown -->
                <div class="col-lg-4 mb-3 pcol" style="--pi:1">
                    <div class="dpanel h-100">
                        <div class="dpanel-head">
                            <div class="dpanel-title">
                                <span class="live-dot ld-orange"></span>
                                Services Issued
                            </div>
                            <span class="dpill dpill-orange">This Month</span>
                        </div>
                        <div class="dpanel-body">
                            <canvas id="servicesChart" height="155"></canvas>
                            <div class="svc-legend mt-2">
                                <span class="sl-item"><i style="background:#f97316"></i>Clearances</span>
                                <span class="sl-item"><i style="background:#3b82f6"></i>Permits</span>
                                <span class="sl-item"><i style="background:#ef4444"></i>Blotter</span>
                                <span class="sl-item"><i style="background:#22c55e"></i>Indigents</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- ═══ QUICK ACTIONS ═══ -->
            <div class="row mb-4">
                <div class="col-12 mb-2">
                    <div class="sec-chip"><i class="fas fa-bolt"></i> Quick Actions</div>
                </div>

                <?php
                $qacts = [
                    ['residents',         'fa-users',              'blue',    'Residents',   'Population registry'],
                    ['households',        'fa-home',               'teal',    'Households',  'Family profiles'],
                    ['barangay-officials','fa-user-tie',           'violet',  'Officials',   'Elected officials'],
                    ['blotter',           'fa-file-alt',           'red',     'Blotter',     'Complaints log'],
                    ['clearances',        'fa-certificate',        'emerald', 'Clearances',  'Issue certificates'],
                    ['permits',           'fa-store',              'amber',   'Permits',     'Business BPLS'],
                    ['indigents',         'fa-hand-holding-heart', 'rose',    'Indigents',   'Social services'],
                    ['reports',           'fa-chart-bar',          'slate',   'Reports',     'Analytics'],
                ];
                foreach ($qacts as $i => [$url, $icon, $col, $name, $desc]):
                ?>
                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-6 mb-3 qcol" style="--qi:<?= $i ?>">
                    <a href="<?= base_url($url) ?>" class="qtile qt-<?= $col ?>">
                        <div class="qt-icon"><i class="fas <?= $icon ?>"></i></div>
                        <div class="qt-body">
                            <div class="qt-name"><?= $name ?></div>
                            <div class="qt-desc"><?= $desc ?></div>
                        </div>
                        <div class="qt-arr"><i class="fas fa-chevron-right"></i></div>
                        <div class="qt-shimmer"></div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- ═══ EVENTS + ALERTS ═══ -->
            <div class="row mb-4">

                <div class="col-lg-7 mb-3 pcol" style="--pi:2">
                    <div class="dpanel h-100">
                        <div class="dpanel-head">
                            <div class="dpanel-title">
                                <span class="live-dot ld-amber"></span>
                                Upcoming Events
                            </div>
                            <a href="<?= base_url('events') ?>" class="dlink">View all <i class="fas fa-arrow-right"></i></a>
                        </div>
                        <div class="dpanel-body p-0">
                            <?php if (!empty($upcomingEvents)): ?>
                            <ul class="evl">
                                <?php foreach ($upcomingEvents as $ev):
                                    $days = ceil((strtotime($ev['date']) - time()) / 86400);
                                ?>
                                <li class="ev">
                                    <span class="ev-dot ev-<?= esc($ev['color']) ?>"></span>
                                    <div class="ev-info">
                                        <div class="ev-name"><?= esc($ev['title']) ?></div>
                                        <div class="ev-when"><i class="fas fa-calendar-day"></i> <?= date('F d, Y', strtotime($ev['date'])) ?></div>
                                    </div>
                                    <span class="ev-badge <?= $days <= 3 ? 'ev-urgent' : '' ?>"><?= $days ?>d</span>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                            <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-calendar-times"></i>
                                <p>No upcoming events</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5 mb-3 pcol" style="--pi:3">
                    <div class="dpanel h-100">
                        <div class="dpanel-head">
                            <div class="dpanel-title">
                                <span class="live-dot ld-red"></span>
                                System Alerts
                                <?php if (!empty($alerts) && ($alerts[0]['type'] ?? '') !== 'success'): ?>
                                <span class="abadge"><?= count($alerts) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="dpanel-body">
                            <?php if (!empty($alerts)): ?>
                                <?php foreach ($alerts as $a): ?>
                                <div class="salert sa-<?= esc($a['type']) ?>">
                                    <i class="fas <?= esc($a['icon']) ?> sa-ico"></i>
                                    <span class="sa-txt"><?= esc($a['message']) ?></span>
                                    <?php if (!empty($a['link'])): ?>
                                    <a href="<?= esc($a['link']) ?>" class="sa-btn"><?= esc($a['label']) ?></a>
                                    <?php endif; ?>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                            <div class="salert sa-success">
                                <i class="fas fa-check-circle sa-ico"></i>
                                <span class="sa-txt">All systems operational — no urgent alerts.</span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

<style>
/* ── Font ────────────────────────────────────────── */
@import url('https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&display=swap');

/* ── Variables (light) ──────────────────────────── */
:root {
    --base:       #eef2f7;
    --panel:      #ffffff;
    --panel2:     #f6f9fc;
    --border:     #e4eaf2;
    --tx:         #0c1a2e;
    --txb:        #3d4e63;
    --txm:        #8898aa;
    --topbg:      #0c1a2e;
    --sha:        0 2px 10px rgba(0,0,0,0.07);
    --shb:        0 6px 24px rgba(0,0,0,0.12);
    --shc:        0 12px 40px rgba(0,0,0,0.16);
    --tr:         all 0.3s cubic-bezier(.4,0,.2,1);
}
/* ── Dark overrides ─────────────────────────────── */
body.bmis-dark { --base:#0b0f17; --panel:#111827; --panel2:#161d2c; --border:#1f2a3c; --tx:#e8f0fe; --txb:#94a3b8; --txm:#4a5568; --topbg:#070b11; --sha:0 2px 10px rgba(0,0,0,0.4); --shb:0 6px 24px rgba(0,0,0,0.55); --shc:0 12px 40px rgba(0,0,0,0.7); }
body.bmis-dark .bmis-dashboard.content-wrapper { background: var(--base) !important; }
body.bmis-dark .content-wrapper { background: var(--base) !important; }

/* ── Base ───────────────────────────────────────── */
.bmis-dashboard { font-family: 'Sora', sans-serif !important; }
.bmis-dashboard * { font-family: 'Sora', sans-serif !important; box-sizing: border-box; }
.bmis-dashboard.content-wrapper { background: var(--base) !important; transition: background 0.45s ease; }

/* ── Topbar ─────────────────────────────────────── */
.dash-topbar {
    background: var(--topbg); padding: 17px 0;
    position: relative; overflow: hidden;
    transition: background 0.45s ease;
}
.dash-topbar::before {
    content:''; position:absolute; inset:0;
    background: radial-gradient(ellipse 70% 120% at 90% 50%, rgba(249,115,22,0.09) 0%, transparent 60%);
    pointer-events:none;
}
.topbar-title { font-size:20px; font-weight:800; color:#fff; margin:0; letter-spacing:-0.3px; }
.topbar-sub   { font-size:12px; color:#6b7a90; margin:2px 0 0; }
.topbar-icon-ring {
    width:44px; height:44px; border-radius:12px; flex-shrink:0;
    background:rgba(249,115,22,0.12); border:1px solid rgba(249,115,22,0.28);
    display:flex; align-items:center; justify-content:center;
    font-size:17px; color:#fb923c;
    animation: ringPulse 3s ease-in-out infinite;
}
@keyframes ringPulse { 0%,100%{box-shadow:0 0 0 0 rgba(249,115,22,0.35)} 50%{box-shadow:0 0 0 9px rgba(249,115,22,0)} }
.topbar-bc { background:transparent !important; padding:0; margin:0; }
.topbar-bc .breadcrumb-item a, .topbar-bc .breadcrumb-item.active { color:#5a6a80; font-size:12px; }
.topbar-bc .breadcrumb-item+.breadcrumb-item::before { color:#2d3a4a; }
.live-clock { font-variant-numeric: tabular-nums; }

/* ── Theme Toggle ───────────────────────────────── */
.theme-toggle-btn {
    display:flex; align-items:center; gap:8px; background:none; border:none; cursor:pointer; padding:0;
}
.tt-track {
    width:50px; height:26px; border-radius:13px;
    background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.18);
    padding:3px; display:flex; align-items:center;
    transition: background 0.35s, border-color 0.35s;
}
body.bmis-dark .tt-track { background:rgba(249,115,22,0.18); border-color:rgba(249,115,22,0.35); }
.tt-thumb {
    width:20px; height:20px; border-radius:50%; background:#fff;
    display:flex; align-items:center; justify-content:center; font-size:10px;
    box-shadow:0 1px 4px rgba(0,0,0,0.35); position:relative;
    transition: transform 0.38s cubic-bezier(.4,0,.2,1), background 0.3s;
}
body.bmis-dark .tt-thumb { transform:translateX(24px); background:#f97316; }
.tt-sun  { color:#f59e0b; position:absolute; transition:opacity 0.2s; }
.tt-moon { color:#e2e8f0; position:absolute; opacity:0; transition:opacity 0.2s; }
body.bmis-dark .tt-sun  { opacity:0; }
body.bmis-dark .tt-moon { opacity:1; }
.tt-label { font-size:12px; font-weight:600; color:#4a5a6e; transition:color 0.3s; }
body.bmis-dark .tt-label { color:#6b7a90; }

/* ── Section ────────────────────────────────────── */
.dash-section { padding-top:20px; }

/* ── KPI CARDS ──────────────────────────────────── */
.kpi-col { animation: kpiUp 0.5s ease both; animation-delay: calc(var(--ki)*0.075s); }
@keyframes kpiUp { from{opacity:0;transform:translateY(22px)} to{opacity:1;transform:translateY(0)} }

.kpi-card {
    display:block; position:relative; overflow:hidden;
    border-radius:16px; padding:20px 18px 0; min-height:140px; color:#fff;
    text-decoration:none !important; cursor:pointer;
    transition: transform 0.3s cubic-bezier(.4,0,.2,1), box-shadow 0.3s cubic-bezier(.4,0,.2,1), filter 0.3s;
}
.kpi-card:hover { transform:translateY(-7px) scale(1.025); filter:brightness(1.07); }

.kc-blue    { background:linear-gradient(140deg,#1d4ed8,#60a5fa); box-shadow:0 6px 22px rgba(29,78,216,0.45); }
.kc-teal    { background:linear-gradient(140deg,#0f766e,#5eead4); box-shadow:0 6px 22px rgba(15,118,110,0.45); }
.kc-violet  { background:linear-gradient(140deg,#6d28d9,#a78bfa); box-shadow:0 6px 22px rgba(109,40,217,0.45); }
.kc-red     { background:linear-gradient(140deg,#b91c1c,#f87171); box-shadow:0 6px 22px rgba(185,28,28,0.45); }
.kc-emerald { background:linear-gradient(140deg,#047857,#6ee7b7); box-shadow:0 6px 22px rgba(4,120,87,0.45); }
.kc-amber   { background:linear-gradient(140deg,#b45309,#fcd34d); box-shadow:0 6px 22px rgba(180,83,9,0.45); }

/* large background icon */
.kc-bg-icon {
    position:absolute; bottom:-8px; right:-8px;
    font-size:68px; opacity:0.10; color:#fff;
    transition: transform 0.35s ease, opacity 0.3s;
    pointer-events:none;
}
.kpi-card:hover .kc-bg-icon { transform:scale(1.18) rotate(-6deg); opacity:0.18; }

/* small front icon ring */
.kc-icon-ring {
    width:36px; height:36px; border-radius:10px;
    background:rgba(255,255,255,0.2); backdrop-filter:blur(6px);
    display:flex; align-items:center; justify-content:center;
    font-size:15px; margin-bottom:10px;
    transition: transform 0.25s ease;
}
.kpi-card:hover .kc-icon-ring { transform:rotate(-6deg) scale(1.1); }

.kc-num {
    font-size:32px; font-weight:800; line-height:1; letter-spacing:-1.5px; color:#fff;
}
.kc-label {
    font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:0.7px;
    margin-top:5px; opacity:0.82;
}
.kc-footer {
    font-size:11px; font-weight:600; padding:8px 0 10px;
    border-top:1px solid rgba(255,255,255,0.2); margin-top:14px; opacity:0.75;
    transition: opacity 0.2s, padding-left 0.2s;
}
.kpi-card:hover .kc-footer { opacity:1; padding-left:2px; }
.kc-footer i { transition:transform 0.2s; margin-left:3px; }
.kpi-card:hover .kc-footer i { transform:translateX(5px); }

/* ── PANELS ─────────────────────────────────────── */
.pcol { animation: panIn 0.55s ease both; animation-delay: calc(0.42s + var(--pi)*0.09s); }
@keyframes panIn { from{opacity:0;transform:translateY(18px)} to{opacity:1;transform:translateY(0)} }

.dpanel {
    background:var(--panel); border-radius:16px;
    border:1px solid var(--border); box-shadow:var(--sha);
    overflow:hidden; display:flex; flex-direction:column;
    transition: background 0.4s, border-color 0.4s, box-shadow 0.3s;
}
.dpanel:hover { box-shadow:var(--shb); }
.dpanel-head {
    display:flex; align-items:center; justify-content:space-between;
    padding:13px 18px; border-bottom:1px solid var(--border);
    transition:border-color 0.4s;
}
.dpanel-title {
    display:flex; align-items:center; gap:8px;
    font-size:14px; font-weight:700; color:var(--tx);
    transition:color 0.4s;
}
.dpanel-body { padding:16px 18px; flex:1; }

/* live dot */
.live-dot {
    width:8px; height:8px; border-radius:50%; flex-shrink:0;
    animation: ldBlink 2.2s ease-in-out infinite;
}
@keyframes ldBlink { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:0.35;transform:scale(0.85)} }
.ld-blue   { background:#3b82f6; box-shadow:0 0 6px #3b82f6; }
.ld-orange { background:#f97316; box-shadow:0 0 6px #f97316; }
.ld-amber  { background:#f59e0b; box-shadow:0 0 6px #f59e0b; }
.ld-red    { background:#ef4444; box-shadow:0 0 6px #ef4444; }

/* pills */
.dpill { font-size:10px; font-weight:700; padding:3px 10px; border-radius:20px; letter-spacing:0.3px; text-transform:uppercase; }
.dpill-blue   { background:#eff6ff; color:#1d4ed8; }
.dpill-orange { background:#fff7ed; color:#c2410c; }
body.bmis-dark .dpill-blue   { background:rgba(59,130,246,0.12); color:#93c5fd; }
body.bmis-dark .dpill-orange { background:rgba(249,115,22,0.12); color:#fdba74; }

/* chart type btns */
.ctype-btn {
    background:var(--panel2); border:1px solid var(--border); border-radius:7px;
    padding:4px 8px; font-size:12px; color:var(--txm); cursor:pointer;
    transition:var(--tr);
}
.ctype-btn.active,.ctype-btn:hover { background:#2563eb; color:#fff; border-color:#2563eb; }

/* services legend */
.svc-legend { display:flex; flex-wrap:wrap; gap:6px 12px; }
.sl-item { display:flex; align-items:center; gap:5px; font-size:11px; color:var(--txm); font-weight:500; }
.sl-item i { width:8px; height:8px; border-radius:50%; display:inline-block; flex-shrink:0; }

/* dlink */
.dlink { font-size:12px; font-weight:600; color:#2563eb; text-decoration:none; transition:color 0.2s; }
.dlink:hover { color:#1d4ed8; text-decoration:underline; }

/* ── QUICK ACTIONS ──────────────────────────────── */
.sec-chip {
    display:inline-flex; align-items:center; gap:6px;
    font-size:11px; font-weight:700; letter-spacing:0.6px; text-transform:uppercase;
    color:var(--tx); background:var(--panel); border:1px solid var(--border);
    border-radius:20px; padding:5px 14px; box-shadow:var(--sha);
    transition:background 0.4s, color 0.4s, border-color 0.4s;
}
.sec-chip i { color:#f97316; }

.qcol { animation: qIn 0.42s ease both; animation-delay: calc(0.7s + var(--qi)*0.055s); }
@keyframes qIn { from{opacity:0;transform:translateX(-12px)} to{opacity:1;transform:translateX(0)} }

.qtile {
    display:flex; align-items:center; gap:11px;
    background:var(--panel); border:1px solid var(--border);
    border-radius:13px; padding:12px 14px; color:var(--tx);
    position:relative; overflow:hidden; box-shadow:var(--sha);
    text-decoration:none !important;
    transition: transform 0.28s cubic-bezier(.4,0,.2,1),
                box-shadow 0.28s cubic-bezier(.4,0,.2,1),
                border-color 0.28s, background 0.4s;
}
.qtile:hover { transform:translateY(-4px); color:var(--tx); }
.qt-shimmer {
    position:absolute; top:0; left:-110%; width:55%; height:100%;
    background:linear-gradient(90deg,transparent,rgba(255,255,255,0.07),transparent);
    transform:skewX(-18deg); transition:left 0.55s ease; pointer-events:none;
}
.qtile:hover .qt-shimmer { left:160%; }

.qt-icon { width:38px; height:38px; border-radius:9px; display:flex; align-items:center; justify-content:center; font-size:15px; flex-shrink:0; transition:transform 0.25s ease; }
.qtile:hover .qt-icon { transform:scale(1.12) rotate(-5deg); }
.qt-body { flex:1; min-width:0; }
.qt-name { font-size:13px; font-weight:700; line-height:1.2; }
.qt-desc { font-size:11px; color:var(--txm); margin-top:1px; }
.qt-arr  { font-size:10px; color:var(--border); flex-shrink:0; transition:transform 0.2s, color 0.2s; }
.qtile:hover .qt-arr { transform:translateX(3px); color:var(--txm); }

/* light icon colors */
.qt-blue   .qt-icon { background:#eff6ff; color:#2563eb; }
.qt-teal   .qt-icon { background:#f0fdfa; color:#0d9488; }
.qt-violet .qt-icon { background:#f5f3ff; color:#7c3aed; }
.qt-red    .qt-icon { background:#fef2f2; color:#dc2626; }
.qt-emerald .qt-icon { background:#f0fdf4; color:#059669; }
.qt-amber  .qt-icon { background:#fffbeb; color:#d97706; }
.qt-rose   .qt-icon { background:#fff1f2; color:#e11d48; }
.qt-slate  .qt-icon { background:#f8fafc; color:#475569; }

/* hover borders */
.qt-blue:hover    { border-color:#bfdbfe; box-shadow:0 8px 24px rgba(59,130,246,0.16); }
.qt-teal:hover    { border-color:#99f6e4; box-shadow:0 8px 24px rgba(13,148,136,0.16); }
.qt-violet:hover  { border-color:#ddd6fe; box-shadow:0 8px 24px rgba(124,58,237,0.16); }
.qt-red:hover     { border-color:#fecaca; box-shadow:0 8px 24px rgba(220,38,38,0.16); }
.qt-emerald:hover { border-color:#a7f3d0; box-shadow:0 8px 24px rgba(5,150,105,0.16); }
.qt-amber:hover   { border-color:#fde68a; box-shadow:0 8px 24px rgba(217,119,6,0.16); }
.qt-rose:hover    { border-color:#fecdd3; box-shadow:0 8px 24px rgba(225,29,72,0.16); }
.qt-slate:hover   { border-color:#cbd5e1; box-shadow:0 8px 24px rgba(71,85,105,0.14); }

/* dark icon colors */
body.bmis-dark .qt-blue    .qt-icon { background:rgba(59,130,246,0.12); color:#93c5fd; }
body.bmis-dark .qt-teal    .qt-icon { background:rgba(13,148,136,0.12); color:#5eead4; }
body.bmis-dark .qt-violet  .qt-icon { background:rgba(124,58,237,0.12); color:#c4b5fd; }
body.bmis-dark .qt-red     .qt-icon { background:rgba(220,38,38,0.12);  color:#fca5a5; }
body.bmis-dark .qt-emerald .qt-icon { background:rgba(5,150,105,0.12);  color:#6ee7b7; }
body.bmis-dark .qt-amber   .qt-icon { background:rgba(217,119,6,0.12);  color:#fcd34d; }
body.bmis-dark .qt-rose    .qt-icon { background:rgba(225,29,72,0.12);  color:#fda4af; }
body.bmis-dark .qt-slate   .qt-icon { background:rgba(71,85,105,0.12);  color:#94a3b8; }

/* ── EVENTS ─────────────────────────────────────── */
.evl { list-style:none; margin:0; padding:0; }
.ev  { display:flex; align-items:center; gap:12px; padding:11px 18px; border-bottom:1px solid var(--border); transition:background 0.17s, border-color 0.4s; }
.ev:last-child { border-bottom:none; }
.ev:hover { background:var(--panel2); }
.ev-dot { width:9px; height:9px; border-radius:50%; flex-shrink:0; box-shadow:0 0 6px currentColor; }
.ev-primary { background:#3b82f6; color:#3b82f6; }
.ev-success { background:#22c55e; color:#22c55e; }
.ev-warning { background:#f59e0b; color:#f59e0b; }
.ev-danger  { background:#ef4444; color:#ef4444; }
.ev-info    { background:#06b6d4; color:#06b6d4; }
.ev-info  { flex:1; min-width:0; }
.ev-name  { font-size:13px; font-weight:600; color:var(--tx); transition:color 0.4s; }
.ev-when  { font-size:11px; color:var(--txm); margin-top:2px; }
.ev-when i { margin-right:4px; }
.ev-badge {
    font-size:11px; font-weight:700; padding:2px 9px; border-radius:20px;
    background:var(--panel2); color:var(--txm); border:1px solid var(--border);
    flex-shrink:0; transition:background 0.4s;
}
.ev-urgent { background:#fef2f2 !important; color:#dc2626 !important; border-color:#fecaca !important; }
body.bmis-dark .ev-urgent { background:rgba(220,38,38,0.14) !important; color:#fca5a5 !important; border-color:rgba(220,38,38,0.3) !important; }

/* fix: ev-info class collision */
.ev > .ev-info { flex:1; min-width:0; }

/* ── SYSTEM ALERTS ──────────────────────────────── */
.abadge {
    display:inline-flex; align-items:center; justify-content:center;
    width:18px; height:18px; border-radius:50%; background:#ef4444; color:#fff;
    font-size:10px; font-weight:700; flex-shrink:0;
    animation: aBounce 1s ease-in-out infinite alternate;
}
@keyframes aBounce { from{transform:scale(1)} to{transform:scale(1.18)} }

.salert {
    display:flex; align-items:flex-start; gap:10px; padding:11px 13px;
    border-radius:10px; margin-bottom:8px; font-size:13px; font-weight:500;
    border:1px solid transparent;
    transition: transform 0.22s ease;
}
.salert:hover { transform:translateX(4px); }
.salert:last-child { margin-bottom:0; }
.sa-ico { font-size:15px; flex-shrink:0; margin-top:1px; }
.sa-txt { flex:1; line-height:1.4; color:inherit; }
.sa-btn { font-size:11px; font-weight:700; padding:3px 9px; border-radius:6px; text-decoration:none; white-space:nowrap; flex-shrink:0; }

.sa-success { background:#f0fdf4; color:#15803d; border-color:#bbf7d0; }
.sa-success .sa-ico { color:#22c55e; }
.sa-warning { background:#fffbeb; color:#92400e; border-color:#fde68a; }
.sa-warning .sa-ico { color:#f59e0b; }
.sa-warning .sa-btn { background:#f59e0b; color:#fff; }
.sa-danger  { background:#fef2f2; color:#991b1b; border-color:#fecaca; }
.sa-danger  .sa-ico { color:#ef4444; }
.sa-danger  .sa-btn { background:#ef4444; color:#fff; }
.sa-info    { background:#f0f9ff; color:#075985; border-color:#bae6fd; }
.sa-info    .sa-ico { color:#0ea5e9; }
.sa-info    .sa-btn { background:#0ea5e9; color:#fff; }

body.bmis-dark .sa-success { background:rgba(34,197,94,0.08);  border-color:rgba(34,197,94,0.2);  color:#86efac; }
body.bmis-dark .sa-warning { background:rgba(245,158,11,0.08); border-color:rgba(245,158,11,0.2); color:#fcd34d; }
body.bmis-dark .sa-danger  { background:rgba(239,68,68,0.08);  border-color:rgba(239,68,68,0.2);  color:#fca5a5; }
body.bmis-dark .sa-info    { background:rgba(14,165,233,0.08); border-color:rgba(14,165,233,0.2); color:#7dd3fc; }

/* ── EMPTY STATE ────────────────────────────────── */
.empty-state { text-align:center; padding:36px 20px; color:var(--txm); }
.empty-state i { font-size:26px; margin-bottom:10px; display:block; opacity:0.35; }
.empty-state p { font-size:13px; margin:0; }

/* ── RESPONSIVE ─────────────────────────────────── */
@media(max-width:576px){
    .kc-num{font-size:26px;}
    .qt-desc{display:none;}
    .qtile{padding:10px 12px;}
    .topbar-title{font-size:16px;}
    .tt-label{display:none;}
}
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
/* ── Theme ───────────────────────────────────── */
const DARK_KEY = 'bmisDark';
const body = document.body;
const ttBtn = document.getElementById('themeToggle');
const ttLabel = document.getElementById('ttLabel');

(function initTheme(){
    if (localStorage.getItem(DARK_KEY) === '1') {
        body.classList.add('bmis-dark');
        ttLabel.textContent = 'Light';
    }
})();

ttBtn.addEventListener('click', () => {
    const isDark = body.classList.toggle('bmis-dark');
    localStorage.setItem(DARK_KEY, isDark ? '1' : '0');
    ttLabel.textContent = isDark ? 'Light' : 'Dark';
    refreshCharts();
});

const dark = () => body.classList.contains('bmis-dark');
const gColor  = () => dark() ? 'rgba(255,255,255,0.055)' : '#eef2f7';
const tkColor = () => dark() ? '#4a5568' : '#8898aa';
const tipBg   = () => dark() ? '#161d2c' : '#fff';
const tipTx   = () => dark() ? '#e8f0fe' : '#0c1a2e';
const tipSub  = () => dark() ? '#6b7a90' : '#3d4e63';
const tipBdr  = () => dark() ? '#1f2a3c' : '#e4eaf2';

/* ── Live Clock ──────────────────────────────── */
(function(){
    const el = document.getElementById('liveDateTime');
    const t = () => {
        const n = new Date();
        el.textContent = n.toLocaleDateString('en-PH',{weekday:'short',year:'numeric',month:'short',day:'numeric'})
            + ' · ' + n.toLocaleTimeString('en-PH',{hour:'2-digit',minute:'2-digit',second:'2-digit'});
    };
    t(); setInterval(t,1000);
})();

/* ── Counter Animation ───────────────────────── */
document.querySelectorAll('.counter').forEach(el => {
    const target = parseInt(el.dataset.target)||0;
    if(!target){return;}
    let s=0;
    const dur=1500;
    const run=ts=>{
        if(!s)s=ts;
        const p=Math.min((ts-s)/dur,1);
        const e=1-Math.pow(1-p,3);
        el.textContent=Math.floor(e*target).toLocaleString();
        if(p<1)requestAnimationFrame(run);
        else el.textContent=target.toLocaleString();
    };
    const obs=new IntersectionObserver(e=>{if(e[0].isIntersecting){requestAnimationFrame(run);obs.disconnect();}});
    obs.observe(el);
});

/* ── Shared tooltip options ──────────────────── */
const tipOpts = () => ({
    backgroundColor: tipBg(), titleColor: tipTx(), bodyColor: tipSub(),
    borderColor: tipBdr(), borderWidth: 1,
    titleFont:{family:'Sora',weight:'700'}, bodyFont:{family:'Sora',size:12},
    padding:10, cornerRadius:8,
});

/* ── Monthly Registrations Chart ─────────────── */
const chartLabels = <?= json_encode(array_column($monthlyStats ?? [], 'label')) ?>;
const chartCounts = <?= json_encode(array_column($monthlyStats ?? [], 'count')) ?>;

const sCtx = document.getElementById('statsChart').getContext('2d');
function makeGrad(){
    const g=sCtx.createLinearGradient(0,0,0,220);
    g.addColorStop(0,'rgba(59,130,246,0.28)');
    g.addColorStop(1,'rgba(59,130,246,0.00)');
    return g;
}

let statsChart = new Chart(sCtx, {
    type:'line',
    data:{
        labels:chartLabels,
        datasets:[{
            label:'Registrations',
            data:chartCounts,
            borderColor:'#3b82f6',
            backgroundColor:makeGrad(),
            borderWidth:2.5,
            pointBackgroundColor:'#fff',
            pointBorderColor:'#3b82f6',
            pointBorderWidth:2,
            pointRadius:4,
            pointHoverRadius:6,
            tension:0.45,fill:true
        }]
    },
    options:buildStatsOpts()
});

function buildStatsOpts(){
    return {
        responsive:true,maintainAspectRatio:true,
        plugins:{legend:{display:false},tooltip:{mode:'index',intersect:false,...tipOpts()}},
        scales:{
            x:{grid:{display:false},ticks:{color:tkColor(),font:{family:'Sora',size:11}}},
            y:{beginAtZero:true,grid:{color:gColor()},ticks:{precision:0,color:tkColor(),font:{family:'Sora',size:11}}}
        }
    };
}

function switchChart(type,btn){
    document.querySelectorAll('.ctype-btn').forEach(b=>b.classList.remove('active'));
    btn.classList.add('active');
    statsChart.config.type=type;
    statsChart.update();
}

/* ── Services Breakdown ──────────────────────── */
const svcMonths = <?= json_encode(array_column($monthlyStats ?? [], 'label')) ?>;
const svcClr    = <?= json_encode($serviceClearances ?? [8,12,9,15,11,18,14,20,17,22,19,25]) ?>;
const svcPmt    = <?= json_encode($servicePermits    ?? [5,7,6,9,8,11,10,13,11,15,12,16]) ?>;
const svcBlt    = <?= json_encode($serviceBlotter    ?? [3,5,4,6,4,7,5,8,6,9,7,10]) ?>;
const svcInd    = <?= json_encode($serviceIndigents  ?? [2,3,3,4,3,5,4,6,4,7,5,8]) ?>;

let svcChart = new Chart(document.getElementById('servicesChart'), {
    type:'bar',
    data:{
        labels:svcMonths,
        datasets:[
            {label:'Clearances',data:svcClr,backgroundColor:'#f97316',borderRadius:3,borderSkipped:false},
            {label:'Permits',   data:svcPmt,backgroundColor:'#3b82f6',borderRadius:3,borderSkipped:false},
            {label:'Blotter',   data:svcBlt,backgroundColor:'#ef4444',borderRadius:3,borderSkipped:false},
            {label:'Indigents', data:svcInd,backgroundColor:'#22c55e',borderRadius:3,borderSkipped:false},
        ]
    },
    options:{
        responsive:true,maintainAspectRatio:true,
        plugins:{legend:{display:false},tooltip:{mode:'index',intersect:false,...tipOpts()}},
        scales:{
            x:{stacked:true,grid:{display:false},ticks:{color:tkColor(),font:{family:'Sora',size:10}}},
            y:{stacked:true,beginAtZero:true,grid:{color:gColor()},ticks:{precision:0,color:tkColor(),font:{family:'Sora',size:10}}}
        }
    }
});

/* ── Refresh charts on theme toggle ──────────── */
function refreshCharts(){
    [statsChart, svcChart].forEach(ch=>{
        if(!ch)return;
        ch.options.scales.x.ticks.color=tkColor();
        ch.options.scales.y.ticks.color=tkColor();
        ch.options.scales.y.grid.color=gColor();
        if(ch.options.scales.x.grid) ch.options.scales.x.grid.color=gColor();
        ch.options.plugins.tooltip = {
            ...ch.options.plugins.tooltip, ...tipOpts(),
            mode: ch.options.plugins.tooltip.mode,
            intersect: ch.options.plugins.tooltip.intersect,
        };
        ch.update();
    });
    // rebuild gradient for line chart
    if(statsChart.config.type==='line'){
        statsChart.data.datasets[0].backgroundColor=makeGrad();
        statsChart.update();
    }
}
</script>
<?= $this->endSection() ?>
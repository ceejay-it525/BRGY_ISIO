<?php

namespace App\Controllers;

class Reports extends BaseController
{
    // ==============================
    // SHARED: run all queries once
    // ==============================
    private function getData(): array
    {
        $db = \Config\Database::connect();

        // ── Basic counts ──────────────────────────────────────────────────────
        $total_residents  = $db->table('residents')->countAllResults();
        $active_residents = $db->table('residents')->where('status', 'Active')->countAllResults();
        $total_voters     = $db->table('residents')->where('is_voter', 1)->countAllResults();
        $female_residents = $db->table('residents')->where('gender', 'Female')->countAllResults();
        $male_residents   = $db->table('residents')->where('gender', 'Male')->countAllResults();
        $total_households = $db->table('households')->countAllResults();
        $total_blotter    = $db->table('blotter')->countAllResults();
        $total_clearances = $db->table('clearances')->countAllResults();
        $total_officials  = $db->table('barangay_officials')->where('status', 'Active')->countAllResults();
        $total_permits    = $db->table('permits')->countAllResults();
        $total_indigents  = $db->table('indigents')->countAllResults();

        // ── Residents: gender breakdown ───────────────────────────────────────
        $residents_by_gender = $db->table('residents')
            ->select('gender, COUNT(*) AS total')
            ->groupBy('gender')
            ->orderBy('total', 'DESC')
            ->get()
            ->getResultArray();

        // ── Residents: civil status breakdown ────────────────────────────────
        $residents_by_civil_status = $db->table('residents')
            ->select('civil_status, COUNT(*) AS total')
            ->groupBy('civil_status')
            ->orderBy('total', 'DESC')
            ->get()
            ->getResultArray();

        // ── Residents: status breakdown ───────────────────────────────────────
        $residents_by_status = $db->table('residents')
            ->select('status, COUNT(*) AS total')
            ->groupBy('status')
            ->orderBy('total', 'DESC')
            ->get()
            ->getResultArray();

        // ── Residents: by barangay ────────────────────────────────────────────
        $residents_by_barangay = $db->table('residents')
            ->select('barangay, COUNT(*) AS total')
            ->where('barangay IS NOT NULL')
            ->where('barangay !=', '')
            ->groupBy('barangay')
            ->orderBy('total', 'DESC')
            ->get()
            ->getResultArray();

        // ── Residents: age groups ─────────────────────────────────────────────
        $age_row = $db->table('residents')
            ->select('
                SUM(CASE WHEN TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) < 18 THEN 1 ELSE 0 END) AS minors,
                SUM(CASE WHEN TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) BETWEEN 18 AND 59 THEN 1 ELSE 0 END) AS adults,
                SUM(CASE WHEN TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) >= 60 THEN 1 ELSE 0 END) AS seniors')
            ->where('birthdate IS NOT NULL')
            ->get()
            ->getRowArray();

        $age_groups = [
            ['label' => 'Minors (< 18)',    'total' => (int)($age_row['minors']  ?? 0)],
            ['label' => 'Adults (18-59)',   'total' => (int)($age_row['adults']  ?? 0)],
            ['label' => 'Seniors (60+)',    'total' => (int)($age_row['seniors'] ?? 0)],
        ];

        // ── Blotter: by incident type ─────────────────────────────────────────
        $blotter_by_type = $db->table('blotter')
            ->select('incident_type, COUNT(*) AS total')
            ->groupBy('incident_type')
            ->orderBy('total', 'DESC')
            ->get()
            ->getResultArray();

        // ── Blotter: by status ────────────────────────────────────────────────
        $blotter_by_status = $db->table('blotter')
            ->select('status, COUNT(*) AS total')
            ->groupBy('status')
            ->orderBy('total', 'DESC')
            ->get()
            ->getResultArray();

        // ── Clearances: by type ───────────────────────────────────────────────
        $clearances_by_type = $db->table('clearances c')
            ->select('ct.type_name, COUNT(c.clearance_id) AS total')
            ->join('clearance_types ct', 'c.clearance_type_id = ct.clearance_type_id', 'left')
            ->groupBy('ct.type_name')
            ->orderBy('total', 'DESC')
            ->get()
            ->getResultArray();

        // ── Clearances: by status ─────────────────────────────────────────────
        $clearances_by_status = $db->table('clearances')
            ->select('status, COUNT(*) AS total')
            ->groupBy('status')
            ->orderBy('total', 'DESC')
            ->get()
            ->getResultArray();

        // ── Permits: by status ────────────────────────────────────────────────
        $permits_by_status = $db->table('permits')
            ->select('status, COUNT(*) AS total')
            ->groupBy('status')
            ->orderBy('total', 'DESC')
            ->get()
            ->getResultArray();

        // ── Permits: by business type ─────────────────────────────────────────
        $permits_by_type = $db->table('permits')
            ->select('business_type, COUNT(*) AS total')
            ->where('business_type IS NOT NULL')
            ->groupBy('business_type')
            ->orderBy('total', 'DESC')
            ->get()
            ->getResultArray();

        // ── Permits: expiring within 30 days ─────────────────────────────────
        $permits_expiring_soon = $db->table('permits')
            ->select('business_name, owner_name, permit_type, expiry_date, status')
            ->where('expiry_date >=', 'CURDATE()', false)
            ->where('expiry_date <=', 'DATE_ADD(CURDATE(), INTERVAL 30 DAY)', false)
            ->orderBy('expiry_date', 'ASC')
            ->get()
            ->getResultArray();

        // ── Indigents: by category ────────────────────────────────────────────
        $indigents_by_category = $db->query(
            "SELECT indigency_category, COUNT(*) AS total
             FROM indigents
             GROUP BY indigency_category ORDER BY total DESC"
        )->getResultArray();

        // ── Indigents: by assistance type ─────────────────────────────────────
        $indigents_by_assistance = $db->query(
            "SELECT assistance_type, COUNT(*) AS total,
                    SUM(assistance_amount) AS total_amount
             FROM indigents
             GROUP BY assistance_type ORDER BY total DESC"
        )->getResultArray();

        // ── Households: by purok ──────────────────────────────────────────────
        $households_by_purok = $db->query(
            "SELECT purok, COUNT(*) AS total
             FROM households WHERE purok IS NOT NULL
             GROUP BY purok ORDER BY total DESC"
        )->getResultArray();

        // ── Households: by status ─────────────────────────────────────────────
        $households_by_status = $db->query(
            "SELECT status, COUNT(*) AS total
             FROM households
             GROUP BY status ORDER BY total DESC"
        )->getResultArray();

        // ── Officials: by position ────────────────────────────────────────────
        $officials_by_position = $db->query(
            "SELECT position, COUNT(*) AS total
             FROM barangay_officials
             GROUP BY position ORDER BY total DESC"
        )->getResultArray();

        // ── Recent activity logs ──────────────────────────────────────────────
        $recent_logs = $db->table('tbl_logs')
            ->select('USER_NAME, ACTION, DATELOG, TIMELOG, identifier')
            ->orderBy('LOGID', 'DESC')
            ->limit(10)
            ->get()
            ->getResultArray();

        // ── Monthly residents registered (last 6 months) ──────────────────────
        $monthly_residents = $db->table('residents')
            ->select("DATE_FORMAT(created_at, '%b %Y') AS month_label, COUNT(*) AS total")
            ->where('created_at >=', 'DATE_SUB(NOW(), INTERVAL 6 MONTH)', false)
            ->groupBy("DATE_FORMAT(created_at, '%Y-%m')")
            ->orderBy("DATE_FORMAT(created_at, '%Y-%m')", 'ASC')
            ->get()
            ->getResultArray();

        // ── Monthly clearances issued (last 6 months) ─────────────────────────
        $clearances_monthly = $db->table('clearances')
            ->select("DATE_FORMAT(COALESCE(issued_date, created_at), '%b %Y') AS month_label, COUNT(*) AS total")
            ->where('COALESCE(issued_date, created_at) >=', 'DATE_SUB(NOW(), INTERVAL 6 MONTH)', false)
            ->groupBy("DATE_FORMAT(COALESCE(issued_date, created_at), '%Y-%m')")
            ->orderBy("DATE_FORMAT(COALESCE(issued_date, created_at), '%Y-%m')", 'ASC')
            ->get()
            ->getResultArray();

        // ── Chart-ready JSON strings (used directly in view <script> blocks) ──
        $chart_gender_labels  = json_encode(array_column($residents_by_gender, 'gender'));
        $chart_gender_data    = json_encode(array_map('intval', array_column($residents_by_gender, 'total')));

        $chart_civil_labels   = json_encode(array_column($residents_by_civil_status, 'civil_status'));
        $chart_civil_data     = json_encode(array_map('intval', array_column($residents_by_civil_status, 'total')));

        $chart_age_labels     = json_encode(array_column($age_groups, 'label'));
        $chart_age_data       = json_encode(array_column($age_groups, 'total'));

        $chart_monthly_labels = json_encode(array_column($monthly_residents, 'month_label'));
        $chart_monthly_data   = json_encode(array_map('intval', array_column($monthly_residents, 'total')));

        return compact(
            // counts
            'total_residents', 'active_residents', 'total_voters',
            'female_residents', 'male_residents',
            'total_households', 'total_blotter', 'total_clearances',
            'total_officials',  'total_permits',  'total_indigents',
            // residents breakdowns
            'residents_by_gender', 'residents_by_civil_status',
            'residents_by_status', 'residents_by_barangay', 'age_groups',
            // blotter
            'blotter_by_type', 'blotter_by_status',
            // clearances
            'clearances_by_type', 'clearances_by_status', 'clearances_monthly',
            // permits
            'permits_by_status', 'permits_by_type', 'permits_expiring_soon',
            // indigents
            'indigents_by_category', 'indigents_by_assistance',
            // households
            'households_by_purok', 'households_by_status',
            // officials
            'officials_by_position',
            // logs & trends
            'recent_logs', 'monthly_residents',
            // chart JSON strings
            'chart_gender_labels',  'chart_gender_data',
            'chart_civil_labels',   'chart_civil_data',
            'chart_age_labels',     'chart_age_data',
            'chart_monthly_labels', 'chart_monthly_data'
        );
    }

    // ==============================
    // PAGE VIEW  — passes ALL vars
    // ==============================
    public function index()
    {
        return view('reports/index', $this->getData());
    }

    // ==============================
    // AJAX endpoint for JS stat cards
    // ==============================
    public function reportStats()
    {
        $d = $this->getData();

        return $this->response->setJSON([
            'total_residents'  => $d['total_residents'],
            'active_residents' => $d['active_residents'],
            'total_voters'     => $d['total_voters'],
            'female_residents' => $d['female_residents'],
            'male_residents'   => $d['male_residents'],
            'total_households' => $d['total_households'],
            'total_blotter'    => $d['total_blotter'],
            'total_clearances' => $d['total_clearances'],
            'total_officials'  => $d['total_officials'],
            'total_permits'    => $d['total_permits'],
            'total_indigents'  => $d['total_indigents'],
            'gender_data'      => array_combine(
                array_column($d['residents_by_gender'], 'gender'),
                array_map('intval', array_column($d['residents_by_gender'], 'total'))
            ),
            'civil_data'       => array_combine(
                array_column($d['residents_by_civil_status'], 'civil_status'),
                array_map('intval', array_column($d['residents_by_civil_status'], 'total'))
            ),
            'age_groups'       => [
                'minors'  => $d['age_groups'][0]['total'],
                'adults'  => $d['age_groups'][1]['total'],
                'seniors' => $d['age_groups'][2]['total'],
            ],
        ]);
    }

    // ==============================
    // FETCH RECORDS FOR DATATABLE
    // ==============================
    public function fetchRecords()
    {
        $request = service('request');
        $draw = (int) $request->getPost('draw');
        $start = (int) $request->getPost('start');
        $length = (int) $request->getPost('length');
        $search = $request->getPost('search');
        $searchValue = $search['value'] ?? '';

        $db = \Config\Database::connect();

        $builder = $db->table('residents');
        $builder->select('id, first_name, middle_name, last_name, suffix, birthdate, gender, civil_status, is_voter, voter_id, contact_number, address_line1, barangay, status');

        if (!empty($searchValue)) {
            $builder->groupStart()
                ->like('first_name', $searchValue)
                ->orLike('middle_name', $searchValue)
                ->orLike('last_name', $searchValue)
                ->orLike('voter_id', $searchValue)
                ->orLike('contact_number', $searchValue)
                ->groupEnd();
        }

        $totalFiltered = $builder->countAllResults(false);

        $builder->orderBy('last_name', 'ASC');
        $builder->limit($length, $start);

        $data = $builder->get()->getResultArray();

        $counter = $start + 1;
        foreach ($data as &$row) {
            $row['row_number'] = $counter++;
        }

        return $this->response->setJSON([
            'draw' => $draw,
            'recordsTotal' => $db->table('residents')->countAllResults(),
            'recordsFiltered' => $totalFiltered,
            'data' => $data,
            'csrf_hash' => csrf_hash()
        ]);
    }
}
<?php 

namespace App\Controllers;

use CodeIgniter\Controller;

class Reports extends Controller
{
    public function index()
    {
        $db = \Config\Database::connect();

        $data = [];

        // Summary counts
        $data['total_residents']  = $db->table('residents')->countAllResults();
        $data['total_households'] = $db->table('households')->countAllResults();
        $data['total_blotter']    = $db->table('blotter')->countAllResults();
        $data['total_clearances'] = $db->table('clearances')->countAllResults();
        $data['total_officials']  = $db->table('barangay_officials')->countAllResults();
        $data['total_permits']    = $db->table('permits')->countAllResults();
        $data['total_indigents']  = $db->table('indigents')->countAllResults();

        // Residents by gender
        $data['residents_by_gender'] = $db->query(
            "SELECT gender, COUNT(*) as total FROM residents GROUP BY gender"
        )->getResultArray();

        // Residents by status
        $data['residents_by_status'] = $db->query(
            "SELECT status, COUNT(*) as total FROM residents GROUP BY status"
        )->getResultArray();

        // Blotter by type
        $data['blotter_by_type'] = $db->query(
            "SELECT incident_type, COUNT(*) as total FROM blotter GROUP BY incident_type ORDER BY total DESC LIMIT 5"
        )->getResultArray();

        // Blotter by status
        $data['blotter_by_status'] = $db->query(
            "SELECT status, COUNT(*) as total FROM blotter GROUP BY status"
        )->getResultArray();

        // Clearances issued per month (last 6 months) — fixed: issued_date
        $data['clearances_monthly'] = $db->query(
            "SELECT DATE_FORMAT(issued_date, '%b %Y') AS month_label, COUNT(*) as total
             FROM clearances
             WHERE issued_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
             GROUP BY DATE_FORMAT(issued_date, '%Y-%m')
             ORDER BY MIN(issued_date)"
        )->getResultArray();

        // Business permits by status
        $data['permits_by_status'] = $db->query(
            "SELECT status, COUNT(*) as total FROM permits GROUP BY status"
        )->getResultArray();

        // Indigents by category
        $data['indigents_by_category'] = $db->query(
            "SELECT indigency_category, COUNT(*) as total FROM indigents GROUP BY indigency_category"
        )->getResultArray();

        // Latest blotter complaints
        $data['latest_blotter_records'] = $db->table('blotter')
            ->orderBy('incident_date', 'DESC')
            ->limit(10)
            ->get()
            ->getResultArray();

        return view('reports/index', $data);
    }

    public function reportStats()
    {
        $db = \Config\Database::connect();

        return $this->response->setJSON([
            'total_residents'   => $db->table('residents')->countAllResults(),
            'total_households'  => $db->table('households')->countAllResults(),
            'total_blotter'     => $db->table('blotter')->countAllResults(),
            'total_clearances'  => $db->table('clearances')->countAllResults(),
            'total_officials'   => $db->table('barangay_officials')->where('status', 'Active')->countAllResults(),
            'total_permits'     => $db->table('permits')->countAllResults(),
            'total_indigents'   => $db->table('indigents')->countAllResults(),
        ]);
    }
}

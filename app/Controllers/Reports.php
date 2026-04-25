<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Reports extends Controller
{
    public function index()
    {
        $db = \Config\Database::connect();

        // Summary counts for dashboard cards
        $data['total_residents']  = $db->table('residents')->countAll();
        $data['total_households'] = $db->table('households')->countAll();
        $data['total_officials']  = $db->table('barangay_officials')->where('status', 'Active')->countAll();
        $data['total_blotter']    = $db->table('blotter')->countAll();
        $data['total_clearances'] = $db->table('clearances')->countAll();
        $data['total_permits']    = $db->table('business_permits')->countAll();
        $data['total_indigents']  = $db->table('indigents')->countAll();

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
            "SELECT complaint_type, COUNT(*) as total FROM blotter GROUP BY complaint_type ORDER BY total DESC LIMIT 5"
        )->getResultArray();

        // Blotter by status
        $data['blotter_by_status'] = $db->query(
            "SELECT status, COUNT(*) as total FROM blotter GROUP BY status"
        )->getResultArray();

        // Clearances issued per month (last 6 months)
        $data['clearances_monthly'] = $db->query(
            "SELECT DATE_FORMAT(date_issued, '%b %Y') AS month_label, COUNT(*) as total
             FROM clearances
             WHERE date_issued >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
             GROUP BY DATE_FORMAT(date_issued, '%Y-%m')
             ORDER BY MIN(date_issued)"
        )->getResultArray();

        // Business permits by status
        $data['permits_by_status'] = $db->query(
            "SELECT status, COUNT(*) as total FROM business_permits GROUP BY status"
        )->getResultArray();

        // Indigents by category
        $data['indigents_by_category'] = $db->query(
            "SELECT indigency_category, COUNT(*) as total FROM indigents GROUP BY indigency_category"
        )->getResultArray();

        return view('reports/index', $data);
    }
}
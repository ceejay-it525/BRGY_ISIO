<?php

namespace App\Controllers;

use App\Models\ReportsModel;

class Reports extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        // ── Totals ────────────────────────────────────────────────────────
        $total_residents  = $db->table('residents')->where('deleted_at IS NULL')->countAllResults();
        $total_households = $db->table('households')->where('deleted_at IS NULL')->countAllResults();
        $total_blotter    = $db->table('blotter')->countAllResults(); // no deleted_at
        $total_clearances = $db->table('clearances')->where('deleted_at IS NULL')->countAllResults();
        $total_officials  = $db->table('barangay_officials')
                               ->where('deleted_at IS NULL')
                               ->where('status', 'Active')
                               ->countAllResults();
        $total_permits    = $db->table('permits')->where('deleted_at IS NULL')->countAllResults();
        $total_indigents  = $db->table('indigents')->where('deleted_at IS NULL')->countAllResults();
        $total_voters     = $db->table('residents')
                               ->where('deleted_at IS NULL')
                               ->where('is_voter', 1)
                               ->countAllResults();

        // ── Gender ────────────────────────────────────────────────────────
        $gender_raw = $db->table('residents')
            ->select('gender, COUNT(*) as total')
            ->where('deleted_at IS NULL')
            ->groupBy('gender')
            ->get()->getResultArray();

        $male_residents   = 0;
        $female_residents = 0;
        foreach ($gender_raw as $g) {
            if (strtolower($g['gender']) === 'male')   $male_residents   = (int)$g['total'];
            if (strtolower($g['gender']) === 'female') $female_residents = (int)$g['total'];
        }

        // ── Civil Status ──────────────────────────────────────────────────
        $civil_raw = $db->table('residents')
            ->select('civil_status, COUNT(*) as total')
            ->where('deleted_at IS NULL')
            ->groupBy('civil_status')
            ->get()->getResultArray();

        // ── Resident Status ───────────────────────────────────────────────
        $residents_by_status = $db->table('residents')
            ->select('status, COUNT(*) as total')
            ->where('deleted_at IS NULL')
            ->groupBy('status')
            ->get()->getResultArray();

        // ── Age Groups ────────────────────────────────────────────────────
        $age_groups = $db->query("
            SELECT 
                CASE 
                    WHEN TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) < 18 THEN 'Minor (<18)'
                    WHEN TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) BETWEEN 18 AND 59 THEN 'Adult (18-59)'
                    ELSE 'Senior (60+)'
                END as label,
                COUNT(*) as total
            FROM residents
            WHERE deleted_at IS NULL AND birthdate IS NOT NULL
            GROUP BY label
            ORDER BY label
        ")->getResultArray();

        // ── Monthly Residents (last 6 months) ─────────────────────────────
        $monthly_raw = $db->query("
            SELECT DATE_FORMAT(created_at, '%b %Y') as month_label,
                   DATE_FORMAT(created_at, '%Y-%m') as month_key,
                   COUNT(*) as total
            FROM residents
            WHERE deleted_at IS NULL
              AND created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
            GROUP BY month_key, month_label
            ORDER BY month_key ASC
        ")->getResultArray();

        // ── Monthly Clearances (last 6 months) ────────────────────────────
        $clearances_monthly = $db->query("
            SELECT DATE_FORMAT(created_at, '%b %Y') as month_label,
                   DATE_FORMAT(created_at, '%Y-%m') as month_key,
                   COUNT(*) as total
            FROM clearances
            WHERE deleted_at IS NULL
              AND created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
            GROUP BY month_key, month_label
            ORDER BY month_key ASC
        ")->getResultArray();

        // ── Blotter ───────────────────────────────────────────────────────
        $blotter_by_type = $db->table('blotter')
            ->select('incident_type, COUNT(*) as total')
            ->groupBy('incident_type')
            ->orderBy('total', 'DESC')
            ->limit(6)
            ->get()->getResultArray();

        $blotter_by_status = $db->table('blotter')
            ->select('status, COUNT(*) as total')
            ->groupBy('status')
            ->get()->getResultArray();

        // ── Clearances by Type ────────────────────────────────────────────
        $clearances_by_type = $db->table('clearances c')
            ->select('ct.type_name, COUNT(*) as total')
            ->join('clearance_types ct', 'ct.clearance_type_id = c.clearance_type_id', 'left')
            ->where('c.deleted_at IS NULL')
            ->groupBy('ct.type_name')
            ->get()->getResultArray();

        // ── Permits ───────────────────────────────────────────────────────
        $permits_by_status = $db->table('permits')
            ->select('status, COUNT(*) as total')
            ->where('deleted_at IS NULL')
            ->groupBy('status')
            ->get()->getResultArray();

        $permits_expiring_soon = $db->table('permits')
            ->where('deleted_at IS NULL')
            ->where('status', 'Active')
            ->where('expiry_date >=', date('Y-m-d'))
            ->where('expiry_date <=', date('Y-m-d', strtotime('+30 days')))
            ->orderBy('expiry_date', 'ASC')
            ->get()->getResultArray();

        // ── Indigents ─────────────────────────────────────────────────────
        $indigents_by_category = $db->table('indigents')
            ->select('indigency_category, COUNT(*) as total')
            ->where('deleted_at IS NULL')
            ->groupBy('indigency_category')
            ->get()->getResultArray();

        $indigents_by_assistance = $db->table('indigents')
            ->select('assistance_type, SUM(assistance_amount) as total_amount')
            ->where('deleted_at IS NULL')
            ->where('assistance_type IS NOT NULL')
            ->groupBy('assistance_type')
            ->get()->getResultArray();

        // ── Households by Purok ───────────────────────────────────────────
        $households_by_purok = $db->table('households')
            ->select('purok, COUNT(*) as total')
            ->where('deleted_at IS NULL')
            ->groupBy('purok')
            ->orderBy('purok', 'ASC')
            ->get()->getResultArray();

        // ── Officials by Position ─────────────────────────────────────────
        $officials_by_position = $db->table('barangay_officials')
            ->select('position, COUNT(*) as total')
            ->where('deleted_at IS NULL')
            ->groupBy('position')
            ->get()->getResultArray();

        // ── Recent Activity Logs ──────────────────────────────────────────
        $recent_logs = $db->table('tbl_logs')
            ->orderBy('LOGID', 'DESC')
            ->limit(8)
            ->get()->getResultArray();

        // ── Pass to view ──────────────────────────────────────────────────
        return view('reports/index', [
            'total_residents'         => $total_residents,
            'total_households'        => $total_households,
            'total_blotter'           => $total_blotter,
            'total_clearances'        => $total_clearances,
            'total_officials'         => $total_officials,
            'total_permits'           => $total_permits,
            'total_indigents'         => $total_indigents,
            'total_voters'            => $total_voters,

            'male_residents'          => $male_residents,
            'female_residents'        => $female_residents,

            'chart_gender_labels'     => json_encode(array_column($gender_raw,  'gender')),
            'chart_gender_data'       => json_encode(array_column($gender_raw,  'total')),

            'residents_by_status'     => $residents_by_status,

            'chart_civil_labels'      => json_encode(array_column($civil_raw, 'civil_status')),
            'chart_civil_data'        => json_encode(array_column($civil_raw, 'total')),

            'age_groups'              => $age_groups,
            'chart_age_labels'        => json_encode(array_column($age_groups, 'label')),
            'chart_age_data'          => json_encode(array_column($age_groups, 'total')),

            'chart_monthly_labels'    => json_encode(array_column($monthly_raw, 'month_label')),
            'chart_monthly_data'      => json_encode(array_column($monthly_raw, 'total')),

            'clearances_monthly'      => $clearances_monthly,
            'blotter_by_type'         => $blotter_by_type,
            'blotter_by_status'       => $blotter_by_status,
            'clearances_by_type'      => $clearances_by_type,
            'permits_by_status'       => $permits_by_status,
            'permits_expiring_soon'   => $permits_expiring_soon,
            'indigents_by_category'   => $indigents_by_category,
            'indigents_by_assistance' => $indigents_by_assistance,
            'households_by_purok'     => $households_by_purok,
            'officials_by_position'   => $officials_by_position,
            'recent_logs'             => $recent_logs,
        ]);
    }
}
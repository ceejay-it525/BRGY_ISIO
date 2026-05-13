<?php

namespace App\Models;

use CodeIgniter\Model;

class ReportsModel extends Model
{
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    public function getTotalResidents()
    {
        return $this->db->table('residents')
            ->where('deleted_at IS NULL')
            ->countAllResults();
    }

    public function getTotalHouseholds()
    {
        return $this->db->table('households')
            ->where('deleted_at IS NULL')
            ->countAllResults();
    }

    public function getTotalBlotter()
    {
        return $this->db->table('blotter')->countAllResults();
    }

    public function getTotalClearances()
    {
        return $this->db->table('clearances')
            ->where('deleted_at IS NULL')
            ->countAllResults();
    }

    public function getTotalOfficials()
    {
        return $this->db->table('barangay_officials')
            ->where('deleted_at IS NULL')
            ->where('status', 'Active')
            ->countAllResults();
    }

    public function getTotalPermits()
    {
        return $this->db->table('permits')
            ->where('deleted_at IS NULL')
            ->countAllResults();
    }

    public function getTotalIndigents()
    {
        return $this->db->table('indigents')
            ->where('deleted_at IS NULL')
            ->countAllResults();
    }

    public function getTotalVoters()
    {
        return $this->db->table('residents')
            ->where('deleted_at IS NULL')
            ->where('is_voter', 1)
            ->countAllResults();
    }

    public function getResidentsByGender()
    {
        return $this->db->table('residents')
            ->select('gender, COUNT(*) as total')
            ->where('deleted_at IS NULL')
            ->groupBy('gender')
            ->get()->getResultArray();
    }

    public function getResidentsByCivilStatus()
    {
        return $this->db->table('residents')
            ->select('civil_status, COUNT(*) as total')
            ->where('deleted_at IS NULL')
            ->groupBy('civil_status')
            ->get()->getResultArray();
    }

    public function getResidentsByStatus()
    {
        return $this->db->table('residents')
            ->select('status, COUNT(*) as total')
            ->where('deleted_at IS NULL')
            ->groupBy('status')
            ->get()->getResultArray();
    }

    public function getResidentsByAgeGroup()
    {
        $sql = "SELECT 
            CASE 
                WHEN TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) < 18 THEN 'Minor (<18)'
                WHEN TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) BETWEEN 18 AND 59 THEN 'Adult (18-59)'
                ELSE 'Senior (60+)'
            END as label,
            COUNT(*) as total
            FROM residents
            WHERE deleted_at IS NULL AND birthdate IS NOT NULL
            GROUP BY label
            ORDER BY label";
        return $this->db->query($sql)->getResultArray();
    }

    public function getMonthlyResidents()
    {
        $sql = "SELECT DATE_FORMAT(created_at, '%b %Y') as month_label, 
                       DATE_FORMAT(created_at, '%Y-%m') as month_key,
                       COUNT(*) as total
                FROM residents
                WHERE deleted_at IS NULL
                  AND created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                GROUP BY month_key, month_label
                ORDER BY month_key ASC";
        return $this->db->query($sql)->getResultArray();
    }

    public function getMonthlyClearances()
    {
        $sql = "SELECT DATE_FORMAT(created_at, '%b %Y') as month_label,
                       DATE_FORMAT(created_at, '%Y-%m') as month_key,
                       COUNT(*) as total
                FROM clearances
                WHERE deleted_at IS NULL
                  AND created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                GROUP BY month_key, month_label
                ORDER BY month_key ASC";
        return $this->db->query($sql)->getResultArray();
    }

    public function getBlotterByType()
    {
        return $this->db->table('blotter')
            ->select('incident_type, COUNT(*) as total')
            ->groupBy('incident_type')
            ->orderBy('total', 'DESC')
            ->limit(6)
            ->get()->getResultArray();
    }

    public function getBlotterByStatus()
    {
        return $this->db->table('blotter')
            ->select('status, COUNT(*) as total')
            ->groupBy('status')
            ->get()->getResultArray();
    }

    public function getClearancesByType()
    {
        return $this->db->table('clearances c')
            ->select('ct.type_name, COUNT(*) as total')
            ->join('clearance_types ct', 'ct.clearance_type_id = c.clearance_type_id', 'left')
            ->where('c.deleted_at IS NULL')
            ->groupBy('ct.type_name')
            ->get()->getResultArray();
    }

    public function getPermitsByStatus()
    {
        return $this->db->table('permits')
            ->select('status, COUNT(*) as total')
            ->where('deleted_at IS NULL')
            ->groupBy('status')
            ->get()->getResultArray();
    }

    public function getPermitsExpiringSoon()
    {
        return $this->db->table('permits')
            ->where('deleted_at IS NULL')
            ->where('status', 'Active')
            ->where('expiry_date >=', date('Y-m-d'))
            ->where('expiry_date <=', date('Y-m-d', strtotime('+30 days')))
            ->orderBy('expiry_date', 'ASC')
            ->get()->getResultArray();
    }

    public function getIndigentsByCategory()
    {
        return $this->db->table('indigents')
            ->select('indigency_category, COUNT(*) as total')
            ->where('deleted_at IS NULL')
            ->groupBy('indigency_category')
            ->get()->getResultArray();
    }

    public function getIndigentsByAssistance()
    {
        return $this->db->table('indigents')
            ->select('assistance_type, SUM(assistance_amount) as total_amount')
            ->where('deleted_at IS NULL')
            ->where('assistance_type IS NOT NULL')
            ->groupBy('assistance_type')
            ->get()->getResultArray();
    }

    public function getHouseholdsByPurok()
    {
        return $this->db->table('households')
            ->select('purok, COUNT(*) as total')
            ->where('deleted_at IS NULL')
            ->groupBy('purok')
            ->orderBy('purok', 'ASC')
            ->get()->getResultArray();
    }

    public function getOfficialsByPosition()
    {
        return $this->db->table('barangay_officials')
            ->select('position, COUNT(*) as total')
            ->where('deleted_at IS NULL')
            ->groupBy('position')
            ->get()->getResultArray();
    }

    public function getRecentLogs($limit = 8)
    {
        return $this->db->table('tbl_logs')
            ->orderBy('LOGID', 'DESC')
            ->limit($limit)
            ->get()->getResultArray();
    }

    public function getLatestBlotter($limit = 8)
    {
        // Note: blotter table has no deleted_at column
        return $this->db->table('blotter')
            ->orderBy('id', 'DESC')
            ->limit($limit)
            ->get()->getResultArray();
    }
}
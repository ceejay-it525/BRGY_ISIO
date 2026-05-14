<?php

namespace App\Models;

use CodeIgniter\Model;

class HouseholdsModel extends Model
{
    protected $table      = 'households';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'head_name',
        'address_line1',
        'purok',
        'barangay',
        'city_municipality',
        'province',
        'zip_code',
        'total_members',
        'status'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // ==============================
    // SERVER-SIDE DATATABLE RECORDS
    // ==============================
    public function getRecords(int $start, int $length, string $searchValue = ''): array
    {
        $db = $this->db;

        $builder = $db->table($this->table);
        $builder->select('id, head_name, address_line1, purok, barangay, city_municipality, total_members, status, created_at');
        $builder->where('deleted_at IS NULL');

        if ($searchValue !== '') {
            $builder->groupStart()
                ->like('head_name', $searchValue)
                ->orLike('address_line1', $searchValue)
                ->orLike('purok', $searchValue)
                ->orLike('barangay', $searchValue)
                ->orLike('city_municipality', $searchValue)
                ->orLike('status', $searchValue)
                ->groupEnd();
        }

        // Count filtered
        $countBuilder = $db->table($this->table);
        $countBuilder->where('deleted_at IS NULL');
        if ($searchValue !== '') {
            $countBuilder->groupStart()
                ->like('head_name', $searchValue)
                ->orLike('address_line1', $searchValue)
                ->orLike('purok', $searchValue)
                ->orLike('barangay', $searchValue)
                ->orLike('city_municipality', $searchValue)
                ->orLike('status', $searchValue)
                ->groupEnd();
        }
        $filteredCount = $countBuilder->countAllResults();

        $builder->orderBy('created_at', 'DESC');
        $builder->limit($length, $start);
        $data = $builder->get()->getResultArray();

        return [
            'data'     => $data,
            'filtered' => $filteredCount
        ];
    }

    // ==============================
    // DASHBOARD STATISTICS
    // ==============================
    public function getDashboardStats(): array
    {
        $db    = $this->db;
        $table = $this->table;

        // Check if deleted_at column exists to avoid query errors
        $hasDeletedAt = false;
        try {
            $cols         = $db->getFieldNames($table);
            $hasDeletedAt = is_array($cols) && in_array('deleted_at', $cols);
        } catch (\Throwable $e) {
            // table might not exist yet
            return [
                'total_households'    => 0,
                'active_households'   => 0,
                'new_households'      => 0,
                'assistance_priority' => 0,
            ];
        }

        $softDeleteClause = $hasDeletedAt ? 'AND deleted_at IS NULL' : '';

        $total = (int) $db->query(
            "SELECT COUNT(*) AS cnt FROM `{$table}` WHERE 1=1 {$softDeleteClause}"
        )->getRow()->cnt;

        $active = (int) $db->query(
            "SELECT COUNT(*) AS cnt FROM `{$table}` WHERE status = 'Active' {$softDeleteClause}"
        )->getRow()->cnt;

        $newThisMonth = (int) $db->query(
            "SELECT COUNT(*) AS cnt FROM `{$table}`
             WHERE MONTH(created_at) = MONTH(CURDATE())
               AND YEAR(created_at)  = YEAR(CURDATE())
               {$softDeleteClause}"
        )->getRow()->cnt;

        $assistancePriority = (int) $db->query(
            "SELECT COUNT(*) AS cnt FROM `{$table}`
             WHERE status = 'Active' {$softDeleteClause}"
        )->getRow()->cnt;

        return [
            'total_households'    => $total,
            'active_households'   => $active,
            'new_households'      => $newThisMonth,
            'assistance_priority' => $assistancePriority,
        ];
    }

    // ==============================
    // ASSISTANCE PRIORITY LIST
    // Returns ALL active households, safe soft-delete aware
    // ==============================
    public function getAssistancePriorityList(): array
    {
        $db    = $this->db;
        $table = $this->table;

        // Check if deleted_at column exists
        $hasDeletedAt = false;
        try {
            $cols         = $db->getFieldNames($table);
            $hasDeletedAt = is_array($cols) && in_array('deleted_at', $cols);
        } catch (\Throwable $e) {
            return [];
        }

        $builder = $db->table($table);
        $builder->select('id, head_name, address_line1, purok, barangay, city_municipality, province, total_members, status, created_at');

        if ($hasDeletedAt) {
            $builder->where('deleted_at IS NULL');
        }

        $builder->where('status', 'Active');
        $builder->orderBy('total_members', 'DESC');
        $builder->orderBy('head_name', 'ASC');

        $result = $builder->get();

        if (!$result) {
            return [];
        }

        return $result->getResultArray();
    }

    // ==============================
    // DUPLICATE HEAD CHECK
    // ==============================
    public function isDuplicateHead(string $headName, ?int $excludeId = null): bool
    {
        $builder = $this->db->table($this->table);
        $builder->where('deleted_at IS NULL');
        $builder->where('head_name', $headName);
        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }
        return $builder->countAllResults() > 0;
    }
}

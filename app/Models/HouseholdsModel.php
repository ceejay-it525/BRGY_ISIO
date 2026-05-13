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

        $total = (int) $db->query(
            "SELECT COUNT(*) AS cnt FROM `{$table}` WHERE deleted_at IS NULL"
        )->getRow()->cnt;

        $active = (int) $db->query(
            "SELECT COUNT(*) AS cnt FROM `{$table}` WHERE deleted_at IS NULL AND status = 'Active'"
        )->getRow()->cnt;

        $newThisMonth = (int) $db->query(
            "SELECT COUNT(*) AS cnt FROM `{$table}`
             WHERE deleted_at IS NULL
               AND MONTH(created_at) = MONTH(CURDATE())
               AND YEAR(created_at)  = YEAR(CURDATE())"
        )->getRow()->cnt;

        // Assistance Priority = all Active households (no member threshold)
        $assistancePriority = (int) $db->query(
            "SELECT COUNT(*) AS cnt FROM `{$table}`
             WHERE deleted_at IS NULL
               AND status = 'Active'"
        )->getRow()->cnt;

        return [
            'total_households'    => $total,
            'active_households'   => $active,
            'new_households'      => $newThisMonth,
            'assistance_priority' => $assistancePriority
        ];
    }

    // ==============================
    // ASSISTANCE PRIORITY LIST
    // All Active households — no member threshold
    // ==============================
    public function getAssistancePriorityList(): array
    {
        return $this->db->table($this->table)
            ->select('id, head_name, address_line1, purok, barangay, city_municipality, province, total_members, status, created_at')
            ->where('deleted_at IS NULL')
            ->where('status', 'Active')
            ->orderBy('total_members', 'DESC')
            ->orderBy('head_name', 'ASC')
            ->get()
            ->getResultArray();
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

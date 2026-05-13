<?php

namespace App\Models;

use CodeIgniter\Model;

class IndigentsModel extends Model
{
    protected $table            = 'indigents';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = false;

    protected $allowedFields = [
        'resident_id',
        'indigency_category',
        'assistance_type',
        'assistance_amount',
        'status',
        'purpose',
        'date_assessed',
        'date_provided',
        'remarks',
        'rejected_reason',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // -------------------------------------------------------------------------
    // VALIDATION
    // -------------------------------------------------------------------------
    protected $validationRules = [
        'resident_id'        => 'required|integer',
        'indigency_category' => 'required|max_length[100]',
        'assistance_type'    => 'permit_empty|max_length[100]',
        'assistance_amount'  => 'permit_empty|numeric',
        'status'             => 'permit_empty|max_length[50]',
        'purpose'            => 'permit_empty|max_length[500]',
        'remarks'            => 'permit_empty|max_length[1000]',
    ];

    protected $validationMessages = [
        'resident_id' => [
            'required' => 'Please select a resident.',
            'integer'  => 'Invalid resident ID.',
        ],
        'full_name' => [
            'required'   => 'Beneficiary name is required.',
            'max_length' => 'Beneficiary name cannot exceed 255 characters.',
        ],
        'indigency_category' => [
            'required'   => 'Indigency category is required.',
            'max_length' => 'Category cannot exceed 100 characters.',
        ],
    ];

    // =========================================================================
    // SERVER-SIDE DATATABLE
    // =========================================================================
    public function getRecords(string $search = '', int $limit = 10, int $offset = 0, string $order = 'i.id DESC', string $tab = 'indigents')
    {
        $builder = $this->db->table('indigents i');

        // Select fields and join with residents to get full name
        $builder->select('i.*, CONCAT(r.first_name, " ", COALESCE(r.middle_name, ""), " ", r.last_name) as full_name');
        $builder->join('residents r', 'r.id = i.resident_id', 'left');

        // Global search
        if ($search !== '') {
            $builder->groupStart()
                ->like('r.first_name',          $search)
                ->orLike('r.last_name',           $search)
                ->orLike('i.indigency_category',$search)
                ->orLike('i.assistance_type',   $search)
                ->orLike('i.status',            $search)
                ->orLike('i.purpose',           $search)
            ->groupEnd();
        }

        // Workflow tab filter
        $tabMap = [
            'indigents/pending'  => 'Pending Assessment',
            'indigents/approved' => 'Approved',
            'indigents/released' => 'Completed',
            'pending'            => 'Pending Assessment',
            'approved'           => 'Approved',
            'released'           => 'Completed',
        ];

        if (isset($tabMap[$tab])) {
            $builder->where('i.status', $tabMap[$tab]);
        }

        // Soft deletes
        $builder->where('i.deleted_at IS NULL');

        // Ordering
        $builder->orderBy($order);

        // Pagination
        $builder->limit($limit, $offset);

        $query = $builder->get();

        // Get total count for pagination
        $totalBuilder = $this->db->table('indigents i');
        $totalBuilder->join('residents r', 'r.id = i.resident_id', 'left');
        if (isset($tabMap[$tab])) {
            $totalBuilder->where('i.status', $tabMap[$tab]);
        }
        $totalBuilder->where('i.deleted_at IS NULL');
        $totalRecords = $totalBuilder->countAllResults(false);

        return [
            'data'  => $query->getResultArray(),
            'total' => $totalRecords,
        ];
    }

    // =========================================================================
    // STATS
    // =========================================================================
    public function getStats(): array
    {
        $db = \Config\Database::connect();
        $base = fn() => $db->table($this->table)->where('deleted_at IS NULL');

        return [
            'total'     => (int)$base()->countAllResults(),
            'pending'   => (int)$base()->where('status', 'Pending Assessment')->countAllResults(),
            'approved'  => (int)$base()->where('status', 'Approved')->countAllResults(),
            'completed' => (int)$base()->where('status', 'Completed')->countAllResults(),
            'rejected'  => (int)$base()->where('status', 'Rejected')->countAllResults(),
        ];
    }

    // =========================================================================
    // CERTIFICATE NUMBER HELPER
    // =========================================================================
    public function getLastIdForYear(string $year): int
    {
        $db  = \Config\Database::connect();
        $row = $db->table($this->table)
            ->where('YEAR(created_at)', $year)
            ->orderBy('id', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();

        return $row ? (int)$row['id'] : 0;
    }

    // =========================================================================
    // ASSISTANCE HISTORY  (excludes current record)
    // =========================================================================
    public function getAssistanceHistory(int $residentId, int $limit = 10, int $excludeId = 0): array
    {
        $builder = $this->where('resident_id', $residentId)
            ->where('deleted_at IS NULL', null, false);

        if ($excludeId > 0) {
            $builder->where('id !=', $excludeId);
        }

        return $builder
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }
}

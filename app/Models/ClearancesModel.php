<?php

namespace App\Models;

use CodeIgniter\Model;

class ClearancesModel extends Model
{
    protected $table            = 'clearances';
    protected $primaryKey       = 'clearance_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'control_number', 'resident_id', 'clearance_type_id', 'purpose',
        'request_date', 'issued_date', 'expiry_date', 'status',
        'fee_amount', 'or_number', 'remarks', 'processed_by', 'signed_by'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'resident_id'      => 'required|numeric',
        'clearance_type_id'=> 'required|numeric',
        'purpose'          => 'required|max_length[255]',
        'fee_amount'       => 'decimal[10,2]',
        'status'           => 'in_list[Pending,Approved,Released,Rejected,Expired]',
    ];

    protected $validationMessages = [
        'resident_id' => [
            'required' => 'Resident is required.',
        ],
        'clearance_type_id' => [
            'required' => 'Clearance type is required.',
        ],
        'purpose' => [
            'required' => 'Purpose is required.',
        ],
    ];

    // -------------------------------------------------------------------------
    // SERVER-SIDE DATATABLE
    // -------------------------------------------------------------------------
    public function getRecords(int $start, int $length, string $search, string $viewType = 'all'): array
    {
        $db = \Config\Database::connect();

        // Check if residents table exists using query builder
        $tables = $db->table('information_schema.tables')
            ->where('table_schema', $db->database)
            ->where('table_name', 'residents')
            ->countAllResults();
        $residentsExists = ($tables > 0);

        if ($residentsExists) {
            $builder = $db->table($this->table . ' c')
                ->select('c.clearance_id, c.control_number, c.resident_id, c.clearance_type_id, c.purpose, c.request_date, c.issued_date, c.expiry_date, c.status, c.fee_amount, c.or_number, c.remarks, c.processed_by, c.signed_by, c.created_at, c.updated_at, TRIM(CONCAT(r.first_name, " ", COALESCE(NULLIF(r.middle_name,""), ""), " ", r.last_name)) AS resident_name, r.address_line1, ct.type_name')
                ->join('residents r', 'c.resident_id = r.id', 'left')
                ->join('clearance_types ct', 'c.clearance_type_id = ct.clearance_type_id', 'left')
                ->where('c.deleted_at', null);

            if ($search !== '') {
                $builder->groupStart()
                    ->like('c.control_number', $search)
                    ->orLike('c.purpose', $search)
                    ->orLike('c.status', $search)
                    ->orLike('r.first_name', $search)
                    ->orLike('r.last_name', $search)
                    ->groupEnd();
            }
        } else {
            // No residents table - query without join
            $builder = $db->table($this->table . ' c')
                ->select('c.clearance_id, c.control_number, c.resident_id, c.clearance_type_id, c.purpose, c.request_date, c.issued_date, c.expiry_date, c.status, c.fee_amount, c.or_number, c.remarks, c.processed_by, c.signed_by, c.created_at, c.updated_at, "Unknown Resident" AS resident_name, "" AS address_line1')
                ->where('c.deleted_at', null);

            if ($search !== '') {
                $builder->groupStart()
                    ->like('c.control_number', $search)
                    ->orLike('c.purpose', $search)
                    ->orLike('c.status', $search)
                    ->groupEnd();
            }
        }

        // Workflow tab → status filter
        $tabMap = [
            'pending'  => 'Pending',
            'approved' => 'Approved',
            'released' => 'Released',
            'rejected' => 'Rejected',
            'expired'  => 'Expired',
        ];
        if (isset($tabMap[$viewType])) {
            $builder->where('c.status', $tabMap[$viewType]);
        }

        // Counts
        $filteredCount = (int) (clone $builder)->countAllResults(false);
        $totalCount    = (int) $db->table($this->table)->where('deleted_at', null)->countAllResults();

        // Data
        $rows = $builder->orderBy('c.created_at', 'DESC')
                        ->limit($length, $start)
                        ->get()
                        ->getResultArray();

        // Add row numbers
        $counter = $start + 1;
        foreach ($rows as &$row) {
            $row['row_number'] = $counter++;
        }

        return [
            'data'         => $rows,
            'filtered'     => $filteredCount,
            'recordsTotal' => $totalCount,
        ];
    }

    // -------------------------------------------------------------------------
    // STATS
    // -------------------------------------------------------------------------
    public function getStats(): array
    {
        $db = \Config\Database::connect();

        $total     = $db->table($this->table)->where('deleted_at', null)->countAllResults();
        $pending   = $db->table($this->table)->where('deleted_at', null)->where('status', 'Pending')->countAllResults();
        $approved  = $db->table($this->table)->where('deleted_at', null)->where('status', 'Approved')->countAllResults();
        $released  = $db->table($this->table)->where('deleted_at', null)->where('status', 'Released')->countAllResults();
        $rejected  = $db->table($this->table)->where('deleted_at', null)->where('status', 'Rejected')->countAllResults();
        $expired   = $db->table($this->table)->where('deleted_at', null)->where('status', 'Expired')->countAllResults();

        // Calculate total revenue from released clearances
        $totalRevenue = $db->table($this->table)
            ->selectSum('fee_amount')
            ->where('deleted_at', null)
            ->where('status', 'Released')
            ->get()
            ->getRowArray();

        $revenue = $totalRevenue['fee_amount'] ?? 0;

        return [
            'total'     => $total,
            'pending'   => $pending,
            'approved'  => $approved,
            'released'  => $released,
            'rejected'  => $rejected,
            'expired'   => $expired,
            'revenue'   => $revenue,
        ];
    }

    // -------------------------------------------------------------------------
    // WORKFLOW
    // -------------------------------------------------------------------------
    public function approve(int $id): bool
    {
        return (bool) $this->update($id, ['status' => 'Approved']);
    }

    public function release(int $id, string $orNumber = null): bool
    {
        $data = [
            'status' => 'Released',
            'issued_date' => date('Y-m-d'),
        ];
        if ($orNumber) {
            $data['or_number'] = $orNumber;
        }
        return (bool) $this->update($id, $data);
    }

    public function reject(int $id, string $reason = null): bool
    {
        $data = ['status' => 'Rejected'];
        if ($reason) {
            $data['remarks'] = $reason;
        }
        return (bool) $this->update($id, $data);
    }

    // -------------------------------------------------------------------------
    // RESIDENT LOOKUP
    // -------------------------------------------------------------------------
    public function getResidentInfo($residentId)
    {
        $db = \Config\Database::connect();
        return $db->table('residents')
            ->select('first_name, middle_name, last_name, address_line1, barangay')
            ->where('id', $residentId)
            ->where('deleted_at IS NULL', null, false)
            ->get()
            ->getRowArray();
    }

    // -------------------------------------------------------------------------
    // CLEARANCE HISTORY
    // -------------------------------------------------------------------------
    public function getClearanceHistory($residentId, $limit = 10): array
    {
        return $this->where('resident_id', $residentId)
            ->where('deleted_at', null)
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }
}

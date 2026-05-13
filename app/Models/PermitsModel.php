<?php

namespace App\Models;

use CodeIgniter\Model;

class PermitsModel extends Model
{
    protected $table      = 'permits';
    protected $primaryKey = 'id';

    protected $useTimestamps  = true;
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';
    protected $useSoftDeletes = true;
    protected $deletedField   = 'deleted_at';

    /**
     * ⚠️  'payment_date' is intentionally excluded because the column
     *     does not exist in the database yet.
     *     Run this migration if you want it:
     *       ALTER TABLE permits ADD payment_date DATETIME NULL AFTER fees_paid;
     *     Then add 'payment_date' back to this array.
     */
    protected $allowedFields = [
        'business_name',
        'owner_name',
        'business_address',
        'business_type',
        'permit_type',
        'issue_date',
        'expiry_date',
        'status',
        'fees_paid',
        'rejected_reason',
        'notes',
    ];

    // -------------------------------------------------------------------------
    // STATS for KPI cards
    // -------------------------------------------------------------------------
    public function getStats(): array
    {
        $db = \Config\Database::connect();

        $q = function (string $status = '') use ($db): int {
            $b = $db->table($this->table)->where('deleted_at IS NULL');
            if ($status !== '') {
                $b->where('status', $status);
            }
            return (int) $b->countAllResults();
        };

        return [
            'total'    => $q(),
            'pending'  => $q('Pending'),
            'approved' => $q('Approved'),
            'paid'     => $q('Paid'),
            'active'   => $q('Active'),
            'expired'  => $q('Expired'),
            'rejected' => $q('Rejected'),
        ];
    }

    // -------------------------------------------------------------------------
    // SERVER-SIDE DATATABLE
    // -------------------------------------------------------------------------
    public function getRecords(int $start, int $length, string $search, string $viewType = 'all'): array
    {
        $db = \Config\Database::connect();

        $builder = $db->table($this->table)->where('deleted_at IS NULL');

        if ($search !== '') {
            $builder->groupStart()
                ->like('business_name',  $search)
                ->orLike('owner_name',   $search)
                ->orLike('business_type',$search)
                ->orLike('permit_type',  $search)
                ->orLike('status',       $search)
            ->groupEnd();
        }

        // Workflow tab → status filter
        $tabMap = [
            'pending' => 'Pending',
            'payment' => 'Approved',
            'print'   => 'Paid',
        ];
        if (isset($tabMap[$viewType])) {
            $builder->where('status', $tabMap[$viewType]);
        }

        // Counts
        $filteredCount = (int) (clone $builder)->countAllResults(false);
        $totalCount    = (int) $db->table($this->table)->where('deleted_at IS NULL')->countAllResults();

        // Data
        $rows = $builder->orderBy('created_at', 'DESC')
                        ->limit($length, $start)
                        ->get()
                        ->getResultArray();

        return [
            'data'         => $rows,
            'filtered'     => $filteredCount,
            'recordsTotal' => $totalCount,
        ];
    }

    // -------------------------------------------------------------------------
    // WORKFLOW
    // -------------------------------------------------------------------------
    public function approve(int $id): bool
    {
        return (bool) $this->update($id, ['status' => 'Approved']);
    }

    public function reject(int $id, string $reason): bool
    {
        $ok = $this->update($id, [
            'status'          => 'Rejected',
            'rejected_reason' => $reason,
        ]);

        if ($ok) {
            try {
                \Config\Database::connect()->table('rejected_permits')->insert([
                    'permit_id'       => $id,
                    'rejected_reason' => $reason,
                    'created_at'      => date('Y-m-d H:i:s'),
                ]);
            } catch (\Throwable $e) {
                // table may not exist — ignore silently
            }
        }

        return (bool) $ok;
    }

    public function markPaid(int $id, float $fees): bool
    {
        return (bool) $this->update($id, [
            'status'    => 'Paid',
            'fees_paid' => $fees,
        ]);
    }

    public function markActive(int $id): bool
    {
        return (bool) $this->update($id, ['status' => 'Active']);
    }
}
<?php

namespace App\Models;

use CodeIgniter\Model;

class ClearancesModel extends Model
{
    protected $table      = 'clearances';
    protected $primaryKey = 'clearance_id';

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $allowedFields = [
        'control_number',
        'resident_id',
        'clearance_type_id',
        'purpose',
        'request_date',
        'issued_date',
        'expiry_date',
        'status',
        'fee_amount',
        'or_number',
        'remarks',
        'processed_by',
        'signed_by',
    ];

    // ─── DataTables server-side ───────────────────────────────────────────────

    public function getRecords(int $start = 0, int $length = 10, string $search = ''): array
    {
        $builder = $this->db->table("{$this->table} c");

        $builder->select("
            c.clearance_id,
            c.control_number,
            c.resident_id,
            c.clearance_type_id,
            c.purpose,
            c.status,
            c.fee_amount,
            c.or_number,
            c.remarks,
            c.request_date,
            c.issued_date,
            c.expiry_date,
            TRIM(CONCAT(
                r.first_name, ' ',
                COALESCE(NULLIF(r.middle_name,''), ''),
                ' ', r.last_name
            )) AS resident_name,
            ct.type_name,
            DATE_FORMAT(c.request_date, '%M %d, %Y') AS formatted_request_date,
            DATE_FORMAT(c.issued_date,  '%M %d, %Y') AS formatted_issued_date,
            DATE_FORMAT(c.expiry_date,  '%M %d, %Y') AS formatted_expiry_date
        ");

        $builder->join('residents r',        'c.resident_id = r.id',                       'left');
        $builder->join('clearance_types ct', 'c.clearance_type_id = ct.clearance_type_id', 'left');

        $this->applySearch($builder, $search);

        $builder->orderBy('c.created_at', 'DESC');
        $builder->limit($length, $start);

        $rows = $builder->get()->getResultArray();

        // Inject 1-based row numbers relative to current page offset
        $counter = $start + 1;
        foreach ($rows as &$row) {
            $row['row_number'] = $counter++;
        }
        unset($row);

        return ['data' => $rows];
    }

    // ─── Count helpers ────────────────────────────────────────────────────────

    public function countFiltered(string $search = ''): int
    {
        $builder = $this->db->table("{$this->table} c");
        $builder->join('residents r',        'c.resident_id = r.id',                       'left');
        $builder->join('clearance_types ct', 'c.clearance_type_id = ct.clearance_type_id', 'left');

        $this->applySearch($builder, $search);

        return (int) $builder->countAllResults();
    }

    // ─── Shared search filter ─────────────────────────────────────────────────

    private function applySearch($builder, string $search): void
    {
        if ($search === '') {
            return;
        }

        $builder->groupStart();
            $builder->like('c.control_number', $search);
            $builder->orLike('c.purpose',      $search);
            $builder->orLike('c.status',       $search);
            $builder->orLike('r.first_name',   $search);
            $builder->orLike('r.last_name',    $search);
            $builder->orLike('ct.type_name',   $search);
        $builder->groupEnd();
    }
}

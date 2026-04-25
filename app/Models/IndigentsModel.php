<?php

namespace App\Models;

use CodeIgniter\Model;

class IndigentsModel extends Model
{
    protected $table      = 'indigents';
    protected $primaryKey = 'id';

    protected $useTimestamps = false; // indigents table has only created_at

    protected $allowedFields = [
        'resident_id', 'indigency_category', 'assistance_type',
        'assistance_amount', 'date_assessed', 'date_provided', 'status',
    ];

    public function getRecords($start, $length, $searchValue = '')
    {
        $builder = $this->db->table('indigents i');
        $builder->select(
            'i.*, CONCAT(r.first_name, " ", r.last_name) AS resident_name'
        );
        $builder->join('residents r', 'r.id = i.resident_id', 'left');

        if (!empty($searchValue)) {
            $builder->groupStart()
                ->like('i.indigency_category', $searchValue)
                ->orLike('i.assistance_type', $searchValue)
                ->orLike('i.status', $searchValue)
                ->orLike('r.first_name', $searchValue)
                ->orLike('r.last_name', $searchValue)
                ->groupEnd();
        }

        $filteredBuilder = clone $builder;
        $filteredRecords = $filteredBuilder->countAllResults();

        $builder->limit($length, $start);
        $data = $builder->get()->getResultArray();

        return ['data' => $data, 'filtered' => $filteredRecords];
    }
}
<?php

namespace App\Models;

use CodeIgniter\Model;

class ClearanceModel extends Model
{
    protected $table      = 'clearances';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'control_no', 'resident_id', 'resident_name', 'purpose',
        'issued_date', 'valid_until', 'issued_by', 'or_number',
        'amount', 'status', 'created_at', 'updated_at', 'deleted_at'
    ];

    public function getRecords($start, $length, $searchValue = '')
    {
        $builder = $this->builder();
        $builder->select('*');

        if (!empty($searchValue)) {
            $builder->groupStart()
                ->like('control_no', $searchValue)
                ->orLike('resident_name', $searchValue)
                ->orLike('purpose', $searchValue)
                ->groupEnd();
        }

        $filteredBuilder = clone $builder;
        $filteredRecords = $filteredBuilder->countAllResults();

        $builder->limit($length, $start);
        $data = $builder->get()->getResultArray();

        return ['data' => $data, 'filtered' => $filteredRecords];
    }
}
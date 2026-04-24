<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangayOfficialsModel extends Model
{
    protected $table      = 'barangay_officials';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'first_name', 'middle_name', 'last_name', 'suffix',
        'position', 'committee', 'term_start', 'term_end',
        'contact_number', 'status',
        'created_at', 'updated_at', 'deleted_at'
    ];

    public function getRecords($start, $length, $searchValue = '')
    {
        $builder = $this->builder();
        $builder->select('*');

        if (!empty($searchValue)) {
            $builder->groupStart()
                ->like('first_name', $searchValue)
                ->orLike('last_name', $searchValue)
                ->orLike('position', $searchValue)
                ->orLike('committee', $searchValue)
                ->groupEnd();
        }

        $filteredBuilder = clone $builder;
        $filteredRecords = $filteredBuilder->countAllResults();

        $builder->limit($length, $start);
        $data = $builder->get()->getResultArray();

        return ['data' => $data, 'filtered' => $filteredRecords];
    }
}
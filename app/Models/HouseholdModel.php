<?php

namespace App\Models;

use CodeIgniter\Model;

class HouseholdModel extends Model
{
    protected $table      = 'households';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'household_no', 'head_of_family', 'purok', 'address',
        'contact_number', 'total_members', 'house_ownership',
        'status', 'created_at', 'updated_at', 'deleted_at'
    ];

    public function getRecords($start, $length, $searchValue = '')
    {
        $builder = $this->builder();
        $builder->select('*');

        if (!empty($searchValue)) {
            $builder->groupStart()
                ->like('household_no', $searchValue)
                ->orLike('head_of_family', $searchValue)
                ->orLike('purok', $searchValue)
                ->orLike('address', $searchValue)
                ->groupEnd();
        }

        $filteredBuilder = clone $builder;
        $filteredRecords = $filteredBuilder->countAllResults();

        $builder->limit($length, $start);
        $data = $builder->get()->getResultArray();

        return ['data' => $data, 'filtered' => $filteredRecords];
    }
}
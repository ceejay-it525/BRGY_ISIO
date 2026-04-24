<?php

namespace App\Models;

use CodeIgniter\Model;

class ResidentsModel extends Model
{
    protected $table      = 'residents';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'household_id', 'first_name', 'middle_name', 'last_name',
        'suffix', 'birthdate', 'age', 'gender', 'civil_status',
        'contact_number', 'email', 'occupation', 'voter_status',
        'purok', 'address', 'status',
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
                ->orLike('middle_name', $searchValue)
                ->orLike('purok', $searchValue)
                ->orLike('contact_number', $searchValue)
                ->groupEnd();
        }

        $filteredBuilder    = clone $builder;
        $filteredRecords    = $filteredBuilder->countAllResults();

        $builder->limit($length, $start);
        $data = $builder->get()->getResultArray();

        return ['data' => $data, 'filtered' => $filteredRecords];
    }
}
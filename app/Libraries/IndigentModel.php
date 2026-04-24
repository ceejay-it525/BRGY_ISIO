<?php

namespace App\Models;

use CodeIgniter\Model;

class IndigentModel extends Model
{
    protected $table      = 'indigents';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'resident_id', 'first_name', 'last_name', 'middle_name',
        'birthdate', 'gender', 'civil_status', 'purok', 'address',
        'contact_number', 'monthly_income', 'household_members',
        'classification', 'benefits_received', 'status',
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
                ->orLike('purok', $searchValue)
                ->orLike('classification', $searchValue)
                ->groupEnd();
        }

        $filteredBuilder = clone $builder;
        $filteredRecords = $filteredBuilder->countAllResults();

        $builder->limit($length, $start);
        $data = $builder->get()->getResultArray();

        return ['data' => $data, 'filtered' => $filteredRecords];
    }
}
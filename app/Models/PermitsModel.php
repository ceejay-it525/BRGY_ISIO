<?php

namespace App\Models;

use CodeIgniter\Model;

class PermitsModel extends Model
{
    protected $table      = 'permits';
    protected $primaryKey = 'id';

    protected $useTimestamps = true;

    protected $allowedFields = [
        'business_name', 'owner_name', 'owner_resident_id', 'business_address',
        'business_type', 'permit_type', 'issue_date', 'expiry_date',
        'status', 'fees_paid',
    ];

    public function getRecords($start, $length, $searchValue = '')
    {
        $builder = $this->builder();
        $builder->select('*');

        if (!empty($searchValue)) {
            $builder->groupStart()
                ->like('business_name', $searchValue)
                ->orLike('owner_name', $searchValue)
                ->orLike('business_address', $searchValue)
                ->orLike('business_type', $searchValue)
                ->orLike('status', $searchValue)
                ->groupEnd();
        }

        $filteredBuilder = clone $builder;
        $filteredRecords = $filteredBuilder->countAllResults();

        $builder->limit($length, $start);
        $data = $builder->get()->getResultArray();

        return ['data' => $data, 'filtered' => $filteredRecords];
    }
}
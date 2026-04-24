<?php

namespace App\Models;

use CodeIgniter\Model;

class BlotterModel extends Model
{
    protected $table      = 'blotter';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'case_no', 'complainant', 'respondent', 'incident_type',
        'incident_date', 'incident_place', 'narrative', 'action_taken',
        'status', 'settled_date', 'created_at', 'updated_at', 'deleted_at'
    ];

    public function getRecords($start, $length, $searchValue = '')
    {
        $builder = $this->builder();
        $builder->select('*');

        if (!empty($searchValue)) {
            $builder->groupStart()
                ->like('case_no', $searchValue)
                ->orLike('complainant', $searchValue)
                ->orLike('respondent', $searchValue)
                ->orLike('incident_type', $searchValue)
                ->groupEnd();
        }

        $filteredBuilder = clone $builder;
        $filteredRecords = $filteredBuilder->countAllResults();

        $builder->limit($length, $start);
        $data = $builder->get()->getResultArray();

        return ['data' => $data, 'filtered' => $filteredRecords];
    }
}
<?php

namespace App\Models;

use CodeIgniter\Model;

class BlotterModel extends Model
{
    protected $table      = 'blotter';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'case_number',
        'incident_type',
        'incident_date',
        'complainant_name',
        'respondent_name',
        'incident_location',
        'status',
        'narrative',
        'action_taken',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getRecords($start, $length, $searchValue = '', $searchType = 'all')
    {
        $builder = $this->db->table($this->table);
        $builder->select('*');

        $countBuilder = $this->db->table($this->table);
        $countBuilder->select('id');

        if (!empty($searchValue)) {
            $allowedTypes = ['case_number', 'incident_type', 'complainant_name', 'respondent_name', 'incident_location', 'status'];

            if ($searchType !== 'all' && in_array($searchType, $allowedTypes, true)) {
                $builder->like($searchType, $searchValue);
                $countBuilder->like($searchType, $searchValue);
            } else {
                $builder->groupStart()
                    ->like('case_number', $searchValue)
                    ->orLike('complainant_name', $searchValue)
                    ->orLike('respondent_name', $searchValue)
                    ->orLike('incident_type', $searchValue)
                    ->orLike('incident_location', $searchValue)
                    ->orLike('status', $searchValue)
                    ->orLike('narrative', $searchValue)
                    ->groupEnd();

                $countBuilder->groupStart()
                    ->like('case_number', $searchValue)
                    ->orLike('complainant_name', $searchValue)
                    ->orLike('respondent_name', $searchValue)
                    ->orLike('incident_type', $searchValue)
                    ->orLike('incident_location', $searchValue)
                    ->orLike('status', $searchValue)
                    ->orLike('narrative', $searchValue)
                    ->groupEnd();
            }
        }

        $filteredRecords = $countBuilder->countAllResults();

        $builder->orderBy('id', 'DESC');
        $builder->limit($length, $start);
        $data = $builder->get()->getResultArray();

        return [
            'data'     => $data,
            'filtered' => $filteredRecords,
        ];
    }
}
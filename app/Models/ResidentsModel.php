<?php

namespace App\Models;

use CodeIgniter\Model;

class ResidentsModel extends Model
{
    protected $table = 'residents';
    protected $primaryKey = 'id';

    // ✅ FULL FIELD SET (matches your add + edit modal)
    protected $allowedFields = [
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'birthdate',
        'gender',
        'civil_status',
        'is_voter',
        'voter_id',
        'household_id',
        'address_line1',
        'barangay',
        'status'
    ];

    public function getRecords($start, $length, $searchValue = '')
    {
        // -------------------------
        // BASE BUILDER (DATA QUERY)
        // -------------------------
        $builder = $this->db->table($this->table);

        $builder->select('*');

        if (!empty($searchValue)) {
            $builder->groupStart()
                ->like('first_name', $searchValue)
                ->orLike('middle_name', $searchValue)
                ->orLike('last_name', $searchValue)
                ->orLike('gender', $searchValue)
                ->orLike('civil_status', $searchValue)
                ->orLike('address_line1', $searchValue)
                ->orLike('barangay', $searchValue)
                ->orLike('voter_id', $searchValue)
                ->groupEnd();
        }

        // -------------------------
        // CLONE FOR COUNT (SAFE WAY)
        // -------------------------
        $countBuilder = $this->db->table($this->table);
        $countBuilder->select('id');

        if (!empty($searchValue)) {
            $countBuilder->groupStart()
                ->like('first_name', $searchValue)
                ->orLike('middle_name', $searchValue)
                ->orLike('last_name', $searchValue)
                ->orLike('gender', $searchValue)
                ->orLike('civil_status', $searchValue)
                ->orLike('address_line1', $searchValue)
                ->orLike('barangay', $searchValue)
                ->orLike('voter_id', $searchValue)
                ->groupEnd();
        }

        $filteredRecords = $countBuilder->countAllResults();

        // -------------------------
        // PAGINATION
        // -------------------------
        $builder->limit($length, $start);
        $data = $builder->get()->getResultArray();

        return [
            'data' => $data,
            'filtered' => $filteredRecords
        ];
    }
}

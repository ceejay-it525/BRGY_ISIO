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
        'contact_number',
        'household_id',
        'address_line1',
        'barangay',
        'status'
    ];

    public function getRecords($start, $length, $searchValue = '', $viewType = 'all')
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
                ->orLike('contact_number', $searchValue)
                ->groupEnd();
        }

        // View type filter (similar to clearance module)
        $tabMap = [
            'active'      => 'Active',
            'work'        => 'Work',
            'functional'  => 'Functional',
            'inactive'    => 'Inactive',
            'deceased'    => 'Deceased',
            'transferred' => 'Transferred',
            'voter-yes'   => '1',
            'voter-no'    => '0',
        ];

        // Handle purok filters
        if (strpos($viewType, 'purok-') === 0) {
            $purokLabel = str_replace('purok-', 'Purok ', $viewType);
            $purokLabel = str_replace('purok 7a', 'Purok 7A', $purokLabel);
            $purokLabel = str_replace('purok 7b', 'Purok 7B', $purokLabel);
            $builder->like('address_line1', $purokLabel);
        } elseif (strpos($viewType, 'gender-') === 0) {
            $gender = str_replace('gender-', '', $viewType);
            $builder->where('gender', $gender);
        } elseif (isset($tabMap[$viewType])) {
            if (strpos($viewType, 'voter-') === 0) {
                $builder->where('is_voter', $tabMap[$viewType]);
            } else {
                $builder->where('status', $tabMap[$viewType]);
            }
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
                ->orLike('contact_number', $searchValue)
                ->groupEnd();
        }

        // Apply same filters to count builder
        if (strpos($viewType, 'purok-') === 0) {
            $purokLabel = str_replace('purok-', 'Purok ', $viewType);
            $purokLabel = str_replace('purok 7a', 'Purok 7A', $purokLabel);
            $purokLabel = str_replace('purok 7b', 'Purok 7B', $purokLabel);
            $countBuilder->like('address_line1', $purokLabel);
        } elseif (strpos($viewType, 'gender-') === 0) {
            $gender = str_replace('gender-', '', $viewType);
            $countBuilder->where('gender', $gender);
        } elseif (isset($tabMap[$viewType])) {
            if (strpos($viewType, 'voter-') === 0) {
                $countBuilder->where('is_voter', $tabMap[$viewType]);
            } else {
                $countBuilder->where('status', $tabMap[$viewType]);
            }
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

<?php
namespace App\Models;

use CodeIgniter\Model;

class ResidentsModel extends Model
{
    protected $table = 'residents';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'first_name', 'gender', 'civil_status', 'is_voter', 
        'address_line1', 'status'
    ];

    public function getRecords($start, $length, $searchValue = '', $orderColumnIndex = 0, $orderDir = 'ASC')
    {
        $builder = $this->builder();

        // ✅ TOTAL RECORDS (unfiltered)
        $totalRecords = $this->countAllResults();

        // ✅ SEARCH
        if (!empty($searchValue)) {
            $builder->groupStart()
                ->like('first_name', $searchValue)
                ->orLike('gender', $searchValue)
                ->orLike('civil_status', $searchValue)
                ->orLike('address_line1', $searchValue)
                ->groupEnd();
        }

        // ✅ FILTERED COUNT
        $recordsFiltered = $builder->countAllResults(false);

        // ✅ ORDERING - Map DataTable column index to DB column
       // ✅ Update this inside getRecords() in your ResidentsModel
$columns = [
    0 => 'id',             // "No." column usually maps to ID or row_number
    1 => 'first_name',     // "Full Name"
    2 => 'gender',         // "Gender"
    3 => 'civil_status',   // "Civil Status"
    4 => 'is_voter',       // "Voter"
    5 => 'address_line1',  // "Address"
    6 => 'status'          // "Status"
];
        $orderColumn = $columns[$orderColumnIndex] ?? 'first_name';
        $builder->orderBy($orderColumn, $orderDir);

        // ✅ PAGINATION
        $builder->limit($length, $start);
        $data = $builder->get()->getResultArray();

        return [
            'data' => $data,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $recordsFiltered
        ];
    }
}
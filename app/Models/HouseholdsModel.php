<?php

namespace App\Models;

use CodeIgniter\Model;

class HouseholdsModel extends Model
{
    protected $table      = 'households';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'head_name',
        'address_line1',
        'purok',
        'barangay',
        'city_municipality',
        'province',
        'zip_code',
        'total_members',
        'status'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function getRecords($start, $length, $searchValue = '')
    {
        // -------------------------
        // BASE BUILDER (DATA QUERY)
        // -------------------------
        $builder = $this->db->table($this->table);

        $builder->select('*');
        $builder->where('deleted_at IS NULL');

        if (!empty($searchValue)) {
            $builder->groupStart()
                ->like('head_name', $searchValue)
                ->orLike('address_line1', $searchValue)
                ->orLike('purok', $searchValue)
                ->orLike('barangay', $searchValue)
                ->orLike('city_municipality', $searchValue)
                ->orLike('province', $searchValue)
                ->orLike('zip_code', $searchValue)
                ->orLike('status', $searchValue)
                ->groupEnd();
        }

        // -------------------------
        // CLONE FOR COUNT (SAFE WAY)
        // -------------------------
        $countBuilder = $this->db->table($this->table);
        $countBuilder->select('id');
        $countBuilder->where('deleted_at IS NULL');

        if (!empty($searchValue)) {
            $countBuilder->groupStart()
                ->like('head_name', $searchValue)
                ->orLike('address_line1', $searchValue)
                ->orLike('purok', $searchValue)
                ->orLike('barangay', $searchValue)
                ->orLike('city_municipality', $searchValue)
                ->orLike('province', $searchValue)
                ->orLike('zip_code', $searchValue)
                ->orLike('status', $searchValue)
                ->groupEnd();
        }

        $filteredRecords = $countBuilder->countAllResults();

        // -------------------------
        // PAGINATION
        // -------------------------
        $builder->limit($length, $start);
        $data = $builder->get()->getResultArray();

        return [
            'data'     => $data,
            'filtered' => $filteredRecords
        ];
    }
}

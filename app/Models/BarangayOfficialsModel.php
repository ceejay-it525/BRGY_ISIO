<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangayOfficialsModel extends Model
{
    protected $table      = 'barangay_officials';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'first_name', 'middle_name', 'last_name', 'position', 
        'term_start', 'term_end', 'contact_number', 'email', 
        'address', 'status', 'photo'
    ];

   public function getRecords($start, $length, $search = '')
{
    $builder = $this->builder();

    if ($search) {
        $builder->groupStart()
            ->like('first_name', $search)
            ->orLike('middle_name', $search)
            ->orLike('last_name', $search)
            ->orLike('position', $search)
            ->groupEnd();
    }

    $filtered = $builder->countAllResults(false);

    $builder->limit($length, $start);

    return [
        'data' => $builder->get()->getResultArray(),
        'filtered' => $filtered
    ];
}
}
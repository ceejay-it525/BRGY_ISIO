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
                ->orLike('contact_number', $search)
                ->orLike('email', $search)
                ->groupEnd();
        }

        $filtered = $builder->countAllResults(false);

        $builder->orderBy('id', 'ASC');
        $builder->limit($length, $start);

        return [
            'data'     => $builder->get()->getResultArray(),
            'filtered' => $filtered,
        ];
    }

    public function countAll(): int
    {
        return (int) $this->builder()->countAllResults();
    }
}

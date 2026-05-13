<?php

namespace App\Models;

use CodeIgniter\Model;

class ReportsModel extends Model
{
    protected $table = 'reports';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'title',
        'report_type',
        'description',
        'period_start',
        'period_end',
        'parameters',
        'file_path',
        'generated_by',
        'status'
    ];

    public function getRecords($start = 0, $length = 10, $search = '')
    {
        $builder = $this->builder();

        if (!empty($search)) {
            $builder->groupStart()
                ->like('title', $search)
                ->orLike('report_type', $search)
                ->orLike('description', $search)
                ->groupEnd();
        }

        $filtered = $builder->countAllResults(false);
        $builder->limit($length, $start);
        $data = $builder->get()->getResultArray();

        return [
            'data' => $data,
            'filtered' => $filtered
        ];
    }
}

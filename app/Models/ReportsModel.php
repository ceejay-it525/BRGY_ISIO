<?php

namespace App\Models;

use CodeIgniter\Model;
use Ramsey\Uuid\Uuid;

class ReportsModel extends Model
{
    protected $table            = 'reports';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $useSoftDeletes   = true;
    protected $allowedFields    = [
        'uuid', 'title', 'report_type', 'description', 'period_start',
        'period_end', 'parameters', 'file_path', 'generated_by', 'status',
        'created_at', 'updated_at',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules = [
        'title'        => 'required|min_length[3]',
        'report_type'  => 'required|in_list[Blotter,Clearance,Permit,Demographic,Financial]',
        'period_start' => 'required|valid_date',
        'period_end'   => 'required|valid_date',
    ];

    public function getRecords($start, $length, $searchValue, $orderColumn, $orderDir)
    {
        $builder = $this->builder()
            ->select('reports.id, reports.uuid, reports.title, reports.report_type,
                      reports.description, reports.period_start, reports.period_end,
                      reports.file_path, reports.status, reports.created_at,
                      CONCAT(users.first_name, " ", users.last_name) AS generated_by_name')
            ->join('users', 'users.id = reports.generated_by', 'left')
            ->where('reports.deleted_at', null);

        $totalRecords = $builder->countAllResults(false);

        if (!empty($searchValue)) {
            $builder->groupStart()
                ->like('reports.title', $searchValue)
                ->orLike('reports.report_type', $searchValue)
                ->orLike('reports.description', $searchValue)
                ->orLike('users.first_name', $searchValue)
                ->orLike('users.last_name', $searchValue)
                ->groupEnd();
        }

        $filteredRecords = $builder->countAllResults(false);

        $columns = ['reports.id', 'reports.title', 'reports.report_type',
                    'reports.period_start', 'reports.period_end',
                    'generated_by_name', 'reports.created_at'];
        if (isset($columns[$orderColumn])) {
            $builder->orderBy($columns[$orderColumn], $orderDir);
        } else {
            $builder->orderBy('reports.id', 'DESC');
        }

        $builder->limit($length, $start);
        $records = $builder->get()->getResultArray();

        return [
            'data'            => $records,
            'recordsTotal'    => $totalRecords,
            'recordsFiltered' => $filteredRecords,
        ];
    }

    /* ---------- Live summary helpers ---------- */

    public function getBlotterSummary(string $from, string $to): array
    {
        return $this->db->table('blotters')
            ->select('incident_type, case_status, COUNT(*) AS total_cases')
            ->where('deleted_at', null)
            ->where('incident_date >=', $from)
            ->where('incident_date <=', $to)
            ->groupBy(['incident_type', 'case_status'])
            ->orderBy('incident_type')
            ->get()->getResultArray();
    }

    public function getClearanceSummary(string $from, string $to): array
    {
        return $this->db->table('clearances')
            ->select('clearance_type, request_status,
                      COUNT(*) AS total_issued,
                      COALESCE(SUM(fee_amount), 0) AS total_collected')
            ->where('deleted_at', null)
            ->where('request_date >=', $from)
            ->where('request_date <=', $to)
            ->groupBy(['clearance_type', 'request_status'])
            ->orderBy('clearance_type')
            ->get()->getResultArray();
    }

    public function getPermitSummary(string $from, string $to): array
    {
        return $this->db->table('permits')
            ->select('permit_type, permit_status,
                      COUNT(*) AS total_issued,
                      COALESCE(SUM(fee_amount), 0) AS total_collected')
            ->where('deleted_at', null)
            ->where('application_date >=', $from)
            ->where('application_date <=', $to)
            ->groupBy(['permit_type', 'permit_status'])
            ->orderBy('permit_type')
            ->get()->getResultArray();
    }

    protected function beforeInsert(array $data)
    {
        $data['data']['uuid']       = Uuid::uuid4()->toString();
        $data['data']['created_at'] = date('Y-m-d H:i:s');
        $data['data']['updated_at'] = date('Y-m-d H:i:s');
        return $data;
    }

    protected function beforeUpdate(array $data)
    {
        $data['data']['updated_at'] = date('Y-m-d H:i:s');
        return $data;
    }
}

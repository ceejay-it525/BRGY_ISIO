<?php

namespace App\Controllers;

use App\Models\ReportsModel;
use CodeIgniter\API\ResponseTrait;

class Reports extends BaseController
{
    use ResponseTrait;

    protected $reportsModel;

    public function __construct()
    {
        $this->reportsModel = new ReportsModel();
    }

    public function index()
    {
        return view('reports/index');
    }

    public function fetchRecords()
    {
        $draw        = $this->request->getPost('draw');
        $start       = $this->request->getPost('start');
        $length      = $this->request->getPost('length');
        $searchValue = $this->request->getPost('search')['value'];
        $orderColumn = $this->request->getPost('order')[0]['column'];
        $orderDir    = $this->request->getPost('order')[0]['dir'];

        $result = $this->reportsModel->getRecords($start, $length, $searchValue, $orderColumn, $orderDir);

        $counter = $start + 1;
        foreach ($result['data'] as &$row) {
            $row['row_number']   = $counter++;
            $row['period_start'] = date('M d, Y', strtotime($row['period_start']));
            $row['period_end']   = date('M d, Y', strtotime($row['period_end']));
            $row['created_at']   = date('M d, Y', strtotime($row['created_at']));
        }

        return $this->respond([
            'draw'            => $draw,
            'recordsTotal'    => $result['recordsTotal'],
            'recordsFiltered' => $result['recordsFiltered'],
            'data'            => $result['data'],
        ]);
    }

    public function save()
    {
        if (!$this->validate([
            'title'        => 'required|min_length[3]',
            'report_type'  => 'required|in_list[Blotter,Clearance,Permit,Demographic,Financial]',
            'period_start' => 'required|valid_date',
            'period_end'   => 'required|valid_date',
        ])) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $data = [
            'title'        => $this->request->getPost('title'),
            'report_type'  => $this->request->getPost('report_type'),
            'description'  => $this->request->getPost('description'),
            'period_start' => $this->request->getPost('period_start'),
            'period_end'   => $this->request->getPost('period_end'),
            'parameters'   => $this->request->getPost('parameters'),
            'generated_by' => session('user_id'),
            'status'       => 'Active',
        ];

        if ($this->reportsModel->insert($data)) {
            return $this->respondCreated(['status' => 'success', 'message' => 'Report generated successfully']);
        }
        return $this->fail('Failed to generate report');
    }

    public function edit($id)
    {
        $record = $this->reportsModel->find($id);
        if ($record) return $this->respond($record);
        return $this->failNotFound('Report not found');
    }

    public function update($id)
    {
        if (!$this->validate([
            'title'        => 'required|min_length[3]',
            'report_type'  => 'required|in_list[Blotter,Clearance,Permit,Demographic,Financial]',
            'period_start' => 'required|valid_date',
            'period_end'   => 'required|valid_date',
        ])) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $data = [
            'title'        => $this->request->getPost('title'),
            'report_type'  => $this->request->getPost('report_type'),
            'description'  => $this->request->getPost('description'),
            'period_start' => $this->request->getPost('period_start'),
            'period_end'   => $this->request->getPost('period_end'),
            'parameters'   => $this->request->getPost('parameters'),
        ];

        if ($this->reportsModel->update($id, $data)) {
            return $this->respond(['status' => 'success', 'message' => 'Report updated successfully']);
        }
        return $this->fail('Failed to update report');
    }

    public function delete($id)
    {
        if ($this->reportsModel->delete($id)) {
            return $this->respond(['status' => 'success', 'message' => 'Report deleted successfully']);
        }
        return $this->fail('Failed to delete report');
    }

    /* ---------- Live summary endpoints (used by report dashboard) ---------- */

    public function blotterSummary()
    {
        $from = $this->request->getGet('from') ?: date('Y-m-01');
        $to   = $this->request->getGet('to')   ?: date('Y-m-d');
        return $this->respond($this->reportsModel->getBlotterSummary($from, $to));
    }

    public function clearanceSummary()
    {
        $from = $this->request->getGet('from') ?: date('Y-m-01');
        $to   = $this->request->getGet('to')   ?: date('Y-m-d');
        return $this->respond($this->reportsModel->getClearanceSummary($from, $to));
    }

    public function permitSummary()
    {
        $from = $this->request->getGet('from') ?: date('Y-m-01');
        $to   = $this->request->getGet('to')   ?: date('Y-m-d');
        return $this->respond($this->reportsModel->getPermitSummary($from, $to));
    }
}

<?php

namespace App\Controllers;

use App\Models\IndigentsModel;
use CodeIgniter\Controller;
use App\Models\LogModel;

class Indigents extends Controller
{
    public function index()
    {
        return view('indigents/index');
    }

    public function save()
    {
        $model = new IndigentsModel();
        $logModel = new LogModel();

        $resident_id      = $this->request->getPost('resident_id');
        $indigency_category = $this->request->getPost('indigency_category');

        if (!$resident_id || !$indigency_category) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Resident and indigency category are required']);
        }

        $data = [
            'resident_id'        => $resident_id,
            'indigency_category' => $indigency_category,
            'assistance_type'    => $this->request->getPost('assistance_type'),
            'assistance_amount'  => $this->request->getPost('assistance_amount') ?: null,
            'date_assessed'      => $this->request->getPost('date_assessed') ?: null,
            'date_provided'      => $this->request->getPost('date_provided') ?: null,
            'status'             => $this->request->getPost('status'),
        ];

        if ($model->insert($data)) {
            $logModel->addLog('New Indigent record added for Resident ID: ' . $resident_id, 'ADD');
            return $this->response->setJSON(['status' => 'success']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to save indigent record']);
        }
    }

    public function edit($id)
    {
        $model = new IndigentsModel();
        $record = $model->find($id);

        if ($record) {
            return $this->response->setJSON(['data' => $record]);
        } else {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Record not found']);
        }
    }

    public function update()
    {
        $model = new IndigentsModel();
        $logModel = new LogModel();

        $id          = $this->request->getPost('id');
        $resident_id = $this->request->getPost('resident_id');

        if (empty($resident_id)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Resident is required']);
        }

        $data = [
            'resident_id'        => $resident_id,
            'indigency_category' => $this->request->getPost('indigency_category'),
            'assistance_type'    => $this->request->getPost('assistance_type'),
            'assistance_amount'  => $this->request->getPost('assistance_amount') ?: null,
            'date_assessed'      => $this->request->getPost('date_assessed') ?: null,
            'date_provided'      => $this->request->getPost('date_provided') ?: null,
            'status'             => $this->request->getPost('status'),
        ];

        if ($model->update($id, $data)) {
            $logModel->addLog('Indigent record updated: ID ' . $id, 'UPDATED');
            return $this->response->setJSON(['success' => true, 'message' => 'Indigent record updated successfully.']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Error updating indigent record.']);
        }
    }

    public function delete($id)
    {
        $model = new IndigentsModel();
        $logModel = new LogModel();

        $record = $model->find($id);
        if (!$record) {
            return $this->response->setJSON(['success' => false, 'message' => 'Record not found.']);
        }

        if ($model->delete($id)) {
            $logModel->addLog('Indigent record deleted: ID ' . $id, 'DELETED');
            return $this->response->setJSON(['success' => true, 'message' => 'Indigent record deleted successfully.']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete indigent record.']);
        }
    }

    public function fetchRecords()
    {
        $request = service('request');
        $model = new IndigentsModel();

        $start       = $request->getPost('start') ?? 0;
        $length      = $request->getPost('length') ?? 10;
        $searchValue = $request->getPost('search')['value'] ?? '';

        $totalRecords = $model->countAll();
        $result = $model->getRecords($start, $length, $searchValue);

        $data = [];
        $counter = $start + 1;
        foreach ($result['data'] as $row) {
            $row['row_number'] = $counter++;
            $data[] = $row;
        }

        return $this->response->setJSON([
            'draw'            => intval($request->getPost('draw')),
            'recordsTotal'    => $totalRecords,
            'recordsFiltered' => $result['filtered'],
            'data'            => $data,
        ]);
    }
}
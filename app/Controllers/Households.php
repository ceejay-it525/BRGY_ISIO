<?php

namespace App\Controllers;

use App\Models\HouseholdsModel;
use CodeIgniter\Controller;
use App\Models\LogModel;

class Households extends Controller
{
    public function index()
    {
        return view('households/index');
    }

    public function save()
    {
        $model = new HouseholdsModel();
        $logModel = new LogModel();

        $head_name    = $this->request->getPost('head_name');
        $address_line1 = $this->request->getPost('address_line1');

        if (!$head_name || !$address_line1) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Head name and address are required']);
        }

        $data = [
            'head_name'          => $head_name,
            'address_line1'      => $address_line1,
            'address_line2'      => $this->request->getPost('address_line2'),
            'barangay'           => $this->request->getPost('barangay'),
            'city_municipality'  => $this->request->getPost('city_municipality'),
            'province'           => $this->request->getPost('province'),
            'zip_code'           => $this->request->getPost('zip_code'),
            'total_members'      => $this->request->getPost('total_members') ?? 1,
            'status'             => $this->request->getPost('status'),
        ];

        if ($model->insert($data)) {
            $logModel->addLog('New Household added: ' . $head_name, 'ADD');
            return $this->response->setJSON(['status' => 'success']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to save household']);
        }
    }

    public function edit($id)
    {
        $model = new HouseholdsModel();
        $household = $model->find($id);

        if ($household) {
            return $this->response->setJSON(['data' => $household]);
        } else {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Household not found']);
        }
    }

    public function update()
    {
        $model = new HouseholdsModel();
        $logModel = new LogModel();

        $id        = $this->request->getPost('id');
        $head_name = $this->request->getPost('head_name');

        if (empty($head_name)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Head name is required']);
        }

        $data = [
            'head_name'         => $head_name,
            'address_line1'     => $this->request->getPost('address_line1'),
            'address_line2'     => $this->request->getPost('address_line2'),
            'barangay'          => $this->request->getPost('barangay'),
            'city_municipality' => $this->request->getPost('city_municipality'),
            'province'          => $this->request->getPost('province'),
            'zip_code'          => $this->request->getPost('zip_code'),
            'total_members'     => $this->request->getPost('total_members'),
            'status'            => $this->request->getPost('status'),
            'updated_at'        => date('Y-m-d H:i:s'),
        ];

        if ($model->update($id, $data)) {
            $logModel->addLog('Household updated: ' . $head_name, 'UPDATED');
            return $this->response->setJSON(['success' => true, 'message' => 'Household updated successfully.']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Error updating household.']);
        }
    }

    public function delete($id)
    {
        $model = new HouseholdsModel();
        $logModel = new LogModel();

        $household = $model->find($id);
        if (!$household) {
            return $this->response->setJSON(['success' => false, 'message' => 'Household not found.']);
        }

        if ($model->delete($id)) {
            $logModel->addLog('Household deleted: ID ' . $id, 'DELETED');
            return $this->response->setJSON(['success' => true, 'message' => 'Household deleted successfully.']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete household.']);
        }
    }

    public function fetchRecords()
    {
        $request = service('request');
        $model = new HouseholdsModel();

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
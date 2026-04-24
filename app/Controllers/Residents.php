<?php

namespace App\Controllers;

use App\Models\ResidentsModel;
use CodeIgniter\API\ResponseTrait;

class Residents extends BaseController
{
    use ResponseTrait;

    protected $residentsModel;

    public function __construct()
    {
        $this->residentsModel = new ResidentsModel();
    }

    public function index()
    {
        return view('residents/index');
    }

    public function fetchRecords()
    {
        $draw = $this->request->getPost('draw');
        $start = $this->request->getPost('start');
        $length = $this->request->getPost('length');
        $searchValue = $this->request->getPost('search')['value'];
        $orderColumn = $this->request->getPost('order')[0]['column'];
        $orderDir = $this->request->getPost('order')[0]['dir'];

        $result = $this->residentsModel->getRecords($start, $length, $searchValue, $orderColumn, $orderDir);

        // Add row number
        $counter = $start + 1;
        foreach ($result['data'] as &$row) {
            $row['row_number'] = $counter++;
            $row['full_name'] = $row['first_name'] . ' ' . ($row['middle_name'] ? $row['middle_name'] . ' ' : '') . $row['last_name'];
            $row['created_at'] = date('M d, Y', strtotime($row['created_at']));
        }

        $response = [
            'draw' => $draw,
            'recordsTotal' => $result['recordsTotal'],
            'recordsFiltered' => $result['recordsFiltered'],
            'data' => $result['data']
        ];

        return $this->respond($response);
    }

    public function save()
    {
        if (!$this->validate([
            'first_name' => 'required|min_length[2]',
            'last_name' => 'required|min_length[2]',
            'birthdate' => 'required|valid_date',
            'contact_number' => 'required|min_length[10]',
            'status' => 'required|in_list[Active,Inactive]'
        ])) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $data = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'middle_name' => $this->request->getPost('middle_name'),
            'gender' => $this->request->getPost('gender'),
            'birthdate' => $this->request->getPost('birthdate'),
            'address' => $this->request->getPost('address'),
            'contact_number' => $this->request->getPost('contact_number'),
            'civil_status' => $this->request->getPost('civil_status'),
            'status' => $this->request->getPost('status')
        ];

        if ($this->residentsModel->insert($data)) {
            return $this->respondCreated(['status' => 'success', 'message' => 'Resident added successfully']);
        }

        return $this->fail('Failed to add resident');
    }

    public function edit($id)
    {
        $resident = $this->residentsModel->find($id);
        if ($resident) {
            return $this->respond($resident);
        }
        return $this->failNotFound('Resident not found');
    }

    public function update($id)
    {
        if (!$this->validate([
            'first_name' => 'required|min_length[2]',
            'last_name' => 'required|min_length[2]',
            'birthdate' => 'required|valid_date',
            'contact_number' => 'required|min_length[10]|is_unique[residents.contact_number,id,' . $id . ']',
            'status' => 'required|in_list[Active,Inactive]'
        ])) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $data = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'middle_name' => $this->request->getPost('middle_name'),
            'gender' => $this->request->getPost('gender'),
            'birthdate' => $this->request->getPost('birthdate'),
            'address' => $this->request->getPost('address'),
            'contact_number' => $this->request->getPost('contact_number'),
            'civil_status' => $this->request->getPost('civil_status'),
            'status' => $this->request->getPost('status')
        ];

        if ($this->residentsModel->update($id, $data)) {
            return $this->respond(['status' => 'success', 'message' => 'Resident updated successfully']);
        }

        return $this->fail('Failed to update resident');
    }

    public function delete($id)
    {
        if ($this->residentsModel->delete($id)) {
            return $this->respond(['status' => 'success', 'message' => 'Resident deleted successfully']);
        }
        return $this->fail('Failed to delete resident');
    }
}
<?php

namespace App\Controllers;

use App\Models\BarangayOfficialsModel;
use CodeIgniter\API\ResponseTrait;

class BarangayOfficials extends BaseController
{
    use ResponseTrait;

    protected $officialsModel;

    public function __construct()
    {
        $this->officialsModel = new BarangayOfficialsModel();
    }

    public function index()
    {
        return view('barangay_officials/index');
    }

    public function fetchRecords()
    {
        $draw = $this->request->getPost('draw');
        $start = $this->request->getPost('start');
        $length = $this->request->getPost('length');
        $searchValue = $this->request->getPost('search')['value'];
        $orderColumn = $this->request->getPost('order')[0]['column'];
        $orderDir = $this->request->getPost('order')[0]['dir'];

        $result = $this->officialsModel->getRecords($start, $length, $searchValue, $orderColumn, $orderDir);

        $counter = $start + 1;
        foreach ($result['data'] as &$row) {
            $row['row_number'] = $counter++;
            $row['full_name'] = $row['first_name'] . ' ' . ($row['middle_name'] ? $row['middle_name'] . ' ' : '') . $row['last_name'];
            $row['term'] = date('M Y', strtotime($row['term_start'])) . ' - ' . date('M Y', strtotime($row['term_end']));
            $row['created_at'] = date('M d, Y', strtotime($row['created_at']));
        }

        return $this->respond([
            'draw' => $draw,
            'recordsTotal' => $result['recordsTotal'],
            'recordsFiltered' => $result['recordsFiltered'],
            'data' => $result['data']
        ]);
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $data = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'middle_name' => $this->request->getPost('middle_name'),
            'position' => $this->request->getPost('position'),
            'gender' => $this->request->getPost('gender'),
            'birthdate' => $this->request->getPost('birthdate'),
            'address' => $this->request->getPost('address'),
            'contact_number' => $this->request->getPost('contact_number'),
            'email' => $this->request->getPost('email'),
            'term_start' => $this->request->getPost('term_start'),
            'term_end' => $this->request->getPost('term_end'),
            'status' => $this->request->getPost('status')
        ];

        if ($this->officialsModel->insert($data)) {
            return $this->respondCreated(['status' => 'success', 'message' => 'Barangay Official added successfully']);
        }

        return $this->fail('Failed to add official');
    }

    public function edit($id)
    {
        $official = $this->officialsModel->find($id);
        if ($official) {
            return $this->respond($official);
        }
        return $this->failNotFound('Official not found');
    }

    public function update($id)
    {
        if (!$this->validate([
            'contact_number' => 'required|min_length[10]|is_unique[barangay_officials.contact_number,id,' . $id . ']'
        ])) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $data = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'middle_name' => $this->request->getPost('middle_name'),
            'position' => $this->request->getPost('position'),
            'gender' => $this->request->getPost('gender'),
            'birthdate' => $this->request->getPost('birthdate'),
            'address' => $this->request->getPost('address'),
            'contact_number' => $this->request->getPost('contact_number'),
            'email' => $this->request->getPost('email'),
            'term_start' => $this->request->getPost('term_start'),
            'term_end' => $this->request->getPost('term_end'),
            'status' => $this->request->getPost('status')
        ];

        if ($this->officialsModel->update($id, $data)) {
            return $this->respond(['status' => 'success', 'message' => 'Barangay Official updated successfully']);
        }

        return $this->fail('Failed to update official');
    }

    public function delete($id)
    {
        if ($this->officialsModel->delete($id)) {
            return $this->respond(['status' => 'success', 'message' => 'Barangay Official deleted successfully']);
        }
        return $this->fail('Failed to delete official');
    }
}
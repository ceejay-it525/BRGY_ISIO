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
        $draw = (int) $this->request->getPost('draw');
        $start = (int) $this->request->getPost('start');
        $length = (int) $this->request->getPost('length');
        $searchValue = $this->request->getPost('search')['value'] ?? '';
        $orderColumnIndex = (int) $this->request->getPost('order')[0]['column'];
        $orderDir = strtoupper($this->request->getPost('order')[0]['dir']);

        // ✅ Match YOUR DataTable columns exactly
        $result = $this->residentsModel->getRecords($start, $length, $searchValue, $orderColumnIndex, $orderDir);

        // ✅ Add row_number (your JS expects it)
        $counter = $start + 1;
        foreach ($result['data'] as &$row) {
            $row['row_number'] = $counter++;
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
        if (!$this->validate([
            'first_name' => 'required|min_length[2]',
            'gender' => 'required',
            'civil_status' => 'required',
            'address_line1' => 'required',
            'status' => 'required|in_list[1,0]'
        ])) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $data = [
            'first_name' => $this->request->getPost('first_name'),
            'gender' => $this->request->getPost('gender'),
            'civil_status' => $this->request->getPost('civil_status'),
            'is_voter' => $this->request->getPost('is_voter') ? 1 : 0,
            'address_line1' => $this->request->getPost('address_line1'),
            'status' => $this->request->getPost('status')
        ];

        if ($this->residentsModel->insert($data)) {
            return $this->respond(['status' => 'success', 'message' => 'Resident saved successfully']);
        }
        return $this->fail('Failed to save resident');
    }

    public function get($id)
    {
        $resident = $this->residentsModel->find($id);
        if ($resident) {
            return $this->respond(['status' => 'success', 'data' => $resident]);
        }
        return $this->failNotFound('Resident not found');
    }

    public function update()
    {
        $id = $this->request->getPost('id');
        
        if (!$this->validate([
            'first_name' => 'required|min_length[2]',
            'gender' => 'required',
            'civil_status' => 'required',
            'address_line1' => 'required',
            'status' => 'required|in_list[1,0]'
        ])) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $data = [
            'first_name' => $this->request->getPost('first_name'),
            'gender' => $this->request->getPost('gender'),
            'civil_status' => $this->request->getPost('civil_status'),
            'is_voter' => $this->request->getPost('is_voter') ? 1 : 0,
            'address_line1' => $this->request->getPost('address_line1'),
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
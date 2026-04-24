<?php

namespace App\Controllers;

use App\Models\ClearancesModel;
use App\Models\ResidentsModel;
use App\Models\ClearanceTypesModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\ResponseInterface;

class Clearances extends Controller
{
    use \CodeIgniter\API\ResponseTrait;

    protected $clearancesModel;
    protected $residentsModel;
    protected $clearanceTypesModel;
    protected $validation;

    public function __construct()
    {
        $this->clearancesModel = new ClearancesModel();
        $this->residentsModel = new ResidentsModel();
        $this->clearanceTypesModel = new ClearanceTypesModel();
        $this->validation = \Config\Services::validation();
    }

    public function index()
    {
        $data = [
            'title' => 'Clearances',
            'residents' => $this->residentsModel->findAll(),
            'clearanceTypes' => $this->clearanceTypesModel->findAll()
        ];
        return view('clearances/index', $data);
    }

    public function fetchRecords()
    {
        $draw = $this->request->getPost('draw');
        $start = $this->request->getPost('start');
        $length = $this->request->getPost('length');
        $searchValue = $this->request->getPost('search')['value'];

        $records = $this->clearancesModel->getRecords($start, $length, $searchValue);

        $response = [
            'draw' => $draw,
            'recordsTotal' => $this->clearancesModel->countAll(),
            'recordsFiltered' => $this->clearancesModel->countFiltered($searchValue),
            'data' => $records
        ];

        return $this->respond($response);
    }

    public function save()
    {
        $validationRules = [
            'control_number' => 'required|is_unique[clearances.control_number,control_number,{clearance_id}]',
            'resident_id' => 'required|integer',
            'clearance_type_id' => 'required|integer',
            'purpose' => 'required|max_length[255]',
            'request_date' => 'required|valid_date',
            'status' => 'required|in_list[Pending,Approved,Released,Rejected,Expired]',
            'fee_amount' => 'required|numeric|greater_than_equal_to[0]'
        ];

        if (!$this->validate($validationRules)) {
            return $this->fail($this->validation->getErrors());
        }

        $data = [
            'control_number' => $this->request->getPost('control_number'),
            'resident_id' => $this->request->getPost('resident_id'),
            'clearance_type_id' => $this->request->getPost('clearance_type_id'),
            'purpose' => $this->request->getPost('purpose'),
            'request_date' => $this->request->getPost('request_date'),
            'status' => $this->request->getPost('status'),
            'fee_amount' => $this->request->getPost('fee_amount'),
            'remarks' => $this->request->getPost('remarks'),
            'processed_by' => session()->get('official_id') ?? 1
        ];

        $clearance_id = $this->clearancesModel->insert($data);

        if ($clearance_id) {
            return $this->respond(['status' => 200, 'message' => 'Clearance record saved successfully', 'clearance_id' => $clearance_id]);
        }

        return $this->fail('Failed to save clearance record');
    }

    public function edit($id)
    {
        $data = $this->clearancesModel->find($id);
        if ($data) {
            return $this->respond($data);
        }
        return $this->failNotFound('Clearance record not found');
    }

    public function update($id)
    {
        $validationRules = [
            'control_number' => 'required|is_unique[clearances.control_number,control_number,{clearance_id},clearance_id,' . $id . ']',
            'resident_id' => 'required|integer',
            'clearance_type_id' => 'required|integer',
            'purpose' => 'required|max_length[255]',
            'request_date' => 'required|valid_date',
            'status' => 'required|in_list[Pending,Approved,Released,Rejected,Expired]',
            'fee_amount' => 'required|numeric|greater_than_equal_to[0]'
        ];

        if (!$this->validate($validationRules)) {
            return $this->fail($this->validation->getErrors());
        }

        $data = [
            'control_number' => $this->request->getPost('control_number'),
            'resident_id' => $this->request->getPost('resident_id'),
            'clearance_type_id' => $this->request->getPost('clearance_type_id'),
            'purpose' => $this->request->getPost('purpose'),
            'request_date' => $this->request->getPost('request_date'),
            'status' => $this->request->getPost('status'),
            'fee_amount' => $this->request->getPost('fee_amount'),
            'remarks' => $this->request->getPost('remarks'),
            'processed_by' => session()->get('official_id') ?? 1
        ];

        $result = $this->clearancesModel->update($id, $data);

        if ($result) {
            return $this->respond(['status' => 200, 'message' => 'Clearance record updated successfully']);
        }

        return $this->fail('Failed to update clearance record');
    }

    public function delete($id)
    {
        $result = $this->clearancesModel->delete($id);
        if ($result) {
            return $this->respond(['status' => 200, 'message' => 'Clearance record deleted successfully']);
        }
        return $this->fail('Failed to delete clearance record');
    }
}
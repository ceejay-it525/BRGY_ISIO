<?php

namespace App\Controllers;

use App\Models\ResidentsModel;
use App\Models\ClearancesModel;
use App\Models\ClearanceTypesModel;
use CodeIgniter\Controller;

class Clearances extends Controller
{
    use \CodeIgniter\API\ResponseTrait;

    protected $clearancesModel;
    protected $residentsModel;
    protected $clearanceTypesModel;

    public function __construct()
    {
        $this->clearancesModel     = new ClearancesModel();
        $this->residentsModel      = new ResidentsModel();
        $this->clearanceTypesModel = new ClearanceTypesModel();
    }

    // ─── Index ────────────────────────────────────────────────────────────────

    public function index()
    {
        $data = [
            'title'          => 'Clearances',
            'residents'      => $this->residentsModel->findAll(),
            'clearanceTypes' => $this->clearanceTypesModel->findAll(),
        ];
        return view('clearances/index', $data);
    }

    // ─── DataTables server-side fetch ────────────────────────────────────────

    public function fetchRecords()
    {
        $draw        = $this->request->getPost('draw');
        $start       = (int)($this->request->getPost('start') ?? 0);
        $length      = (int)($this->request->getPost('length') ?? 10);
        $searchValue = $this->request->getPost('search')['value'] ?? '';

        $result = $this->clearancesModel->getRecords($start, $length, $searchValue);

        return $this->response->setJSON([
            'draw'            => $draw,
            'recordsTotal'    => $this->clearancesModel->countAll(),
            'recordsFiltered' => $this->clearancesModel->countFiltered($searchValue),
            'data'            => $result['data'],
        ]);
    }

    // ─── Save (insert) ────────────────────────────────────────────────────────

    public function save()
    {
        $rules = [
            'control_number'    => 'required|is_unique[clearances.control_number]',
            'resident_id'       => 'required|integer',
            'clearance_type_id' => 'required|integer',
            'purpose'           => 'required|max_length[255]',
            'request_date'      => 'required|valid_date',
            'status'            => 'required|in_list[Pending,Approved,Released,Rejected,Expired]',
            'fee_amount'        => 'required|numeric|greater_than_equal_to[0]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status'    => 422,
                'message'   => 'Validation failed',
                'errors'    => $this->validator->getErrors(),
                'csrf_hash' => csrf_hash(),
            ])->setStatusCode(422);
        }

        $data = [
            'control_number'    => $this->request->getPost('control_number'),
            'resident_id'       => $this->request->getPost('resident_id'),
            'clearance_type_id' => $this->request->getPost('clearance_type_id'),
            'purpose'           => $this->request->getPost('purpose'),
            'request_date'      => $this->request->getPost('request_date'),
            'issued_date'       => $this->request->getPost('issued_date') ?: null,
            'expiry_date'       => $this->request->getPost('expiry_date') ?: null,
            'status'            => $this->request->getPost('status'),
            'fee_amount'        => $this->request->getPost('fee_amount'),
            'or_number'         => $this->request->getPost('or_number') ?: null,
            'remarks'           => $this->request->getPost('remarks') ?: null,
            'processed_by'      => session()->get('official_id') ?? 1,
        ];

        $clearanceId = $this->clearancesModel->insert($data);

        if ($clearanceId) {
            return $this->response->setJSON([
                'status'       => 200,
                'message'      => 'Clearance issued successfully.',
                'clearance_id' => $clearanceId,
                'csrf_hash'    => csrf_hash(),
            ]);
        }

        return $this->response->setJSON([
            'status'    => 500,
            'message'   => 'Failed to save clearance record.',
            'csrf_hash' => csrf_hash(),
        ])->setStatusCode(500);
    }

    // ─── Edit (fetch single record as JSON) ───────────────────────────────────

    public function edit($id)
    {
        $data = $this->clearancesModel->find($id);
        if ($data) {
            return $this->response->setJSON($data);
        }
        return $this->response->setJSON([
            'status'  => 404,
            'message' => 'Clearance record not found.',
        ])->setStatusCode(404);
    }

    // ─── Update ───────────────────────────────────────────────────────────────

    public function update($id)
    {
        $rules = [
            'control_number'    => "required|is_unique[clearances.control_number,clearance_id,{$id}]",
            'resident_id'       => 'required|integer',
            'clearance_type_id' => 'required|integer',
            'purpose'           => 'required|max_length[255]',
            'request_date'      => 'required|valid_date',
            'status'            => 'required|in_list[Pending,Approved,Released,Rejected,Expired]',
            'fee_amount'        => 'required|numeric|greater_than_equal_to[0]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status'    => 422,
                'message'   => 'Validation failed',
                'errors'    => $this->validator->getErrors(),
                'csrf_hash' => csrf_hash(),
            ])->setStatusCode(422);
        }

        $data = [
            'control_number'    => $this->request->getPost('control_number'),
            'resident_id'       => $this->request->getPost('resident_id'),
            'clearance_type_id' => $this->request->getPost('clearance_type_id'),
            'purpose'           => $this->request->getPost('purpose'),
            'request_date'      => $this->request->getPost('request_date'),
            'issued_date'       => $this->request->getPost('issued_date') ?: null,
            'expiry_date'       => $this->request->getPost('expiry_date') ?: null,
            'status'            => $this->request->getPost('status'),
            'fee_amount'        => $this->request->getPost('fee_amount'),
            'or_number'         => $this->request->getPost('or_number') ?: null,
            'remarks'           => $this->request->getPost('remarks') ?: null,
            'processed_by'      => session()->get('official_id') ?? 1,
        ];

        $result = $this->clearancesModel->update($id, $data);

        if ($result) {
            return $this->response->setJSON([
                'status'    => 200,
                'message'   => 'Clearance updated successfully.',
                'csrf_hash' => csrf_hash(),
            ]);
        }

        return $this->response->setJSON([
            'status'    => 500,
            'message'   => 'Failed to update clearance record.',
            'csrf_hash' => csrf_hash(),
        ])->setStatusCode(500);
    }

    // ─── Delete ───────────────────────────────────────────────────────────────

    public function delete($id)
    {
        $result = $this->clearancesModel->delete($id);

        if ($result) {
            return $this->response->setJSON([
                'status'    => 200,
                'message'   => 'Clearance deleted successfully.',
                'csrf_hash' => csrf_hash(),
            ]);
        }

        return $this->response->setJSON([
            'status'    => 500,
            'message'   => 'Failed to delete clearance record.',
            'csrf_hash' => csrf_hash(),
        ])->setStatusCode(500);
    }
}

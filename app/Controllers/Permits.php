<?php

namespace App\Controllers;

use App\Models\PermitsModel;
use CodeIgniter\API\ResponseTrait;

class Permits extends BaseController
{
    use ResponseTrait;

    protected $permitsModel;

    public function __construct()
    {
        $this->permitsModel = new PermitsModel();
    }

    public function index()
    {
        return view('permits/index');
    }

    public function fetchRecords()
    {
        $draw        = $this->request->getPost('draw');
        $start       = $this->request->getPost('start');
        $length      = $this->request->getPost('length');
        $searchValue = $this->request->getPost('search')['value'];
        $orderColumn = $this->request->getPost('order')[0]['column'];
        $orderDir    = $this->request->getPost('order')[0]['dir'];

        $result = $this->permitsModel->getRecords($start, $length, $searchValue, $orderColumn, $orderDir);

        $counter = $start + 1;
        foreach ($result['data'] as &$row) {
            $row['row_number']       = $counter++;
            $row['application_date'] = $row['application_date'] ? date('M d, Y', strtotime($row['application_date'])) : '';
            $row['issued_date']      = $row['issued_date'] ? date('M d, Y', strtotime($row['issued_date'])) : '-';
            $row['expiry_date']      = $row['expiry_date'] ? date('M d, Y', strtotime($row['expiry_date'])) : '-';
            $row['fee_amount']       = number_format((float) $row['fee_amount'], 2);
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
            'permit_type'      => 'required|in_list[Business Permit,Building Permit,Excavation Permit,Burial Permit]',
            'resident_id'      => 'required|is_natural_no_zero',
            'application_date' => 'required|valid_date',
            'permit_status'    => 'required|in_list[Pending,Approved,Released,Rejected,Expired,Revoked]',
        ])) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $data = [
            'permit_number'      => $this->permitsModel->generatePermitNumber(),
            'permit_type'        => $this->request->getPost('permit_type'),
            'resident_id'        => $this->request->getPost('resident_id'),
            'business_name'      => $this->request->getPost('business_name'),
            'business_address'   => $this->request->getPost('business_address'),
            'business_nature'    => $this->request->getPost('business_nature'),
            'capital_investment' => $this->request->getPost('capital_investment') ?: null,
            'purpose'            => $this->request->getPost('purpose'),
            'application_date'   => $this->request->getPost('application_date'),
            'issued_date'        => $this->request->getPost('issued_date') ?: null,
            'expiry_date'        => $this->request->getPost('expiry_date') ?: null,
            'fee_amount'         => $this->request->getPost('fee_amount') ?: 0,
            'or_number'          => $this->request->getPost('or_number'),
            'remarks'            => $this->request->getPost('remarks'),
            'permit_status'      => $this->request->getPost('permit_status'),
            'status'             => 'Active',
        ];

        if ($this->permitsModel->insert($data)) {
            return $this->respondCreated(['status' => 'success', 'message' => 'Permit added successfully']);
        }
        return $this->fail('Failed to add permit');
    }

    public function edit($id)
    {
        $record = $this->permitsModel->find($id);
        if ($record) return $this->respond($record);
        return $this->failNotFound('Permit not found');
    }

    public function update($id)
    {
        if (!$this->validate([
            'permit_type'      => 'required|in_list[Business Permit,Building Permit,Excavation Permit,Burial Permit]',
            'resident_id'      => 'required|is_natural_no_zero',
            'application_date' => 'required|valid_date',
            'permit_status'    => 'required|in_list[Pending,Approved,Released,Rejected,Expired,Revoked]',
        ])) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $data = [
            'permit_type'        => $this->request->getPost('permit_type'),
            'resident_id'        => $this->request->getPost('resident_id'),
            'business_name'      => $this->request->getPost('business_name'),
            'business_address'   => $this->request->getPost('business_address'),
            'business_nature'    => $this->request->getPost('business_nature'),
            'capital_investment' => $this->request->getPost('capital_investment') ?: null,
            'purpose'            => $this->request->getPost('purpose'),
            'application_date'   => $this->request->getPost('application_date'),
            'issued_date'        => $this->request->getPost('issued_date') ?: null,
            'expiry_date'        => $this->request->getPost('expiry_date') ?: null,
            'fee_amount'         => $this->request->getPost('fee_amount') ?: 0,
            'or_number'          => $this->request->getPost('or_number'),
            'remarks'            => $this->request->getPost('remarks'),
            'permit_status'      => $this->request->getPost('permit_status'),
        ];

        if ($this->permitsModel->update($id, $data)) {
            return $this->respond(['status' => 'success', 'message' => 'Permit updated successfully']);
        }
        return $this->fail('Failed to update permit');
    }

    public function delete($id)
    {
        if ($this->permitsModel->delete($id)) {
            return $this->respond(['status' => 'success', 'message' => 'Permit deleted successfully']);
        }
        return $this->fail('Failed to delete permit');
    }
}

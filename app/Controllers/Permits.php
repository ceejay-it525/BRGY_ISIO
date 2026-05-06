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
        $request = service('request');

        $draw   = (int) $request->getPost('draw');
        $start  = (int) $request->getPost('start');
        $length = (int) $request->getPost('length');

        $search      = $request->getPost('search');
        $searchValue = $search['value'] ?? '';

        $result = $this->permitsModel->getRecords($start, $length, $searchValue);

        $counter = $start + 1;
        foreach ($result['data'] as &$row) {
            $row['row_number'] = $counter++;
        }

        return $this->response->setJSON([
            'draw'            => $draw,
            'recordsTotal'    => $result['recordsTotal'],
            'recordsFiltered' => $result['filtered'],
            'data'            => $result['data']
        ]);
    }

    public function save()
    {
        $data = [
            'business_name'     => $this->request->getPost('business_name'),
            'owner_name'        => $this->request->getPost('owner_name'),
            'business_address'  => $this->request->getPost('business_address'),
            'business_type'     => $this->request->getPost('business_type'),
            'permit_type'       => $this->request->getPost('permit_type'),
            'issue_date'        => $this->request->getPost('issue_date'),
            'expiry_date'       => $this->request->getPost('expiry_date'),
            'status'            => $this->request->getPost('status'),
            'fees_paid'         => $this->request->getPost('fees_paid') ?: 0,
        ];

        if (empty($data['business_name']) || empty($data['owner_name']) || empty($data['business_address'])) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Business Name, Owner Name, and Address are required'
            ]);
        }

        if ($this->permitsModel->insert($data)) {
            return $this->response->setJSON([
                'status'    => 'success',
                'message'   => 'Permit saved successfully',
                'csrf_hash' => csrf_hash()
            ]);
        }

        return $this->response->setJSON([
            'status'    => 'error',
            'message'   => 'Failed to save permit',
            'csrf_hash' => csrf_hash()
        ]);
    }

    public function get($id)
    {
        $permit = $this->permitsModel->find($id);

        if ($permit) {
            return $this->response->setJSON([
                'status' => 'success',
                'data'   => $permit
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'Permit not found'
        ]);
    }

    public function update()
    {
        $id = $this->request->getPost('id');

        if (empty($id)) {
            return $this->response->setJSON([
                'status'      => 'error',
                'message'     => 'Invalid permit ID',
                'csrf_hash'   => csrf_hash()
            ]);
        }

        $data = [
            'business_name'     => $this->request->getPost('business_name'),
            'owner_name'        => $this->request->getPost('owner_name'),
            'business_address'  => $this->request->getPost('business_address'),
            'business_type'     => $this->request->getPost('business_type'),
            'permit_type'       => $this->request->getPost('permit_type'),
            'issue_date'        => $this->request->getPost('issue_date'),
            'expiry_date'       => $this->request->getPost('expiry_date'),
            'status'            => $this->request->getPost('status'),
            'fees_paid'         => $this->request->getPost('fees_paid') ?: 0,
        ];

        if (empty($data['business_name']) || empty($data['owner_name']) || empty($data['business_address'])) {
            return $this->response->setJSON([
                'status'      => 'error',
                'message'     => 'Business Name, Owner Name, and Address are required',
                'csrf_hash'   => csrf_hash()
            ]);
        }

        if ($this->permitsModel->update($id, $data)) {
            return $this->response->setJSON([
                'status'      => 'success',
                'message'     => 'Permit updated successfully',
                'csrf_hash'   => csrf_hash()
            ]);
        }

        return $this->response->setJSON([
            'status'      => 'error',
            'message'     => 'Failed to update permit',
            'csrf_hash'   => csrf_hash()
        ]);
    }

    public function delete($id)
    {
        if ($this->permitsModel->delete($id)) {
            return $this->response->setJSON([
                'status'      => 'success',
                'message'     => 'Permit deleted successfully',
                'csrf_hash'   => csrf_hash()
            ]);
        }

        return $this->response->setJSON([
            'status'      => 'error',
            'message'     => 'Failed to delete permit',
            'csrf_hash'   => csrf_hash()
        ]);
    }
}

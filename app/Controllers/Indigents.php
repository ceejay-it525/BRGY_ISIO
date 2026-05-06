<?php

namespace App\Controllers;

use App\Models\IndigentsModel;

class Indigents extends BaseController
{
    protected $indigentModel;

    public function __construct()
    {
        $this->indigentModel = new IndigentsModel();
    }

    public function index()
    {
        return view('indigents/index');
    }

    public function save()
    {
        $data = $this->request->getPost();

        if (empty($data['first_name']) || empty($data['last_name']) || empty($data['indigency_category'])) {
            return $this->response->setJSON([
                'status'    => 'error',
                'message'   => 'First Name, Last Name, and Indigency Category are required',
                'csrf_hash' => csrf_hash()
            ]);
        }

        // ✅ Do NOT set resident_name — it's a GENERATED column in the DB
        unset($data['resident_name']);

        $data['assistance_amount'] = floatval($data['assistance_amount'] ?? 0);

        // Sanitize empty dates to null
        $data['date_assessed'] = !empty($data['date_assessed']) ? $data['date_assessed'] : null;
        $data['date_provided']  = !empty($data['date_provided'])  ? $data['date_provided']  : null;

        if ($this->indigentModel->insert($data)) {
            return $this->response->setJSON([
                'status'    => 'success',
                'message'   => 'Record saved successfully!',
                'csrf_hash' => csrf_hash()
            ]);
        }

        return $this->response->setJSON([
            'status'    => 'error',
            'message'   => 'Failed to save record. ' . implode(' ', $this->indigentModel->errors()),
            'csrf_hash' => csrf_hash()
        ]);
    }

    public function edit($id)
    {
        $record = $this->indigentModel->find($id);

        if (!$record) {
            return $this->response->setJSON([
                'status'    => 'error',
                'message'   => 'Record not found',
                'csrf_hash' => csrf_hash()
            ]);
        }

        return $this->response->setJSON([
            'data'      => $record,
            'csrf_hash' => csrf_hash()
        ]);
    }

    public function update()
    {
        $id   = $this->request->getPost('id');
        $data = $this->request->getPost();

        if (empty($id)) {
            return $this->response->setJSON([
                'status'    => 'error',
                'message'   => 'Invalid record ID',
                'csrf_hash' => csrf_hash()
            ]);
        }

        if (empty($data['first_name']) || empty($data['last_name'])) {
            return $this->response->setJSON([
                'status'    => 'error',
                'message'   => 'First Name and Last Name are required',
                'csrf_hash' => csrf_hash()
            ]);
        }

        // ✅ Do NOT set resident_name — it's a GENERATED column in the DB
        unset($data['resident_name']);
        unset($data['id']); // remove id from data array, we pass it separately

        $data['assistance_amount'] = floatval($data['assistance_amount'] ?? 0);

        // Sanitize empty dates to null
        $data['date_assessed'] = !empty($data['date_assessed']) ? $data['date_assessed'] : null;
        $data['date_provided']  = !empty($data['date_provided'])  ? $data['date_provided']  : null;

        if ($this->indigentModel->update($id, $data)) {
            return $this->response->setJSON([
                'status'    => 'success',
                'message'   => 'Record updated successfully!',
                'csrf_hash' => csrf_hash()
            ]);
        }

        return $this->response->setJSON([
            'status'    => 'error',
            'message'   => 'Failed to update record. ' . implode(' ', $this->indigentModel->errors()),
            'csrf_hash' => csrf_hash()
        ]);
    }

    public function delete($id)
    {
        if (empty($id)) {
            return $this->response->setJSON([
                'status'    => 'error',
                'message'   => 'Invalid record ID',
                'csrf_hash' => csrf_hash()
            ]);
        }

        $record = $this->indigentModel->find($id);

        if (!$record) {
            return $this->response->setJSON([
                'status'    => 'error',
                'message'   => 'Record not found',
                'csrf_hash' => csrf_hash()
            ]);
        }

        if ($this->indigentModel->delete($id)) {
            return $this->response->setJSON([
                'status'    => 'success',
                'message'   => 'Record deleted successfully!',
                'csrf_hash' => csrf_hash()
            ]);
        }

        return $this->response->setJSON([
            'status'    => 'error',
            'message'   => 'Failed to delete record',
            'csrf_hash' => csrf_hash()
        ]);
    }

    public function fetchRecords()
    {
        $draw   = intval($this->request->getPost('draw') ?? 1);
        $start  = intval($this->request->getPost('start') ?? 0);
        $length = intval($this->request->getPost('length') ?? 25);
        $search = $this->request->getPost('search')['value'] ?? '';

        $data = $this->indigentModel->getRecords($start, $length, $search);

        return $this->response->setJSON([
            'draw'            => $draw,
            'recordsTotal'    => $this->indigentModel->countAllResults(),
            'recordsFiltered' => $data['filtered'],
            'data'            => $data['data'],
            'csrf_hash'       => csrf_hash()
        ]);
    }
}
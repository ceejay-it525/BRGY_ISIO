<?php

namespace App\Controllers;

use App\Models\HouseholdsModel;

class Households extends BaseController
{
    protected $householdsModel;

    public function __construct()
    {
        $this->householdsModel = new HouseholdsModel();
    }

    public function index()
    {
        return view('households/index');
    }

    // ==============================
    // FETCH DATATABLE RECORDS
    // ==============================
    public function fetchRecords()
    {
        $request = service('request');

        $draw   = (int) $request->getPost('draw');
        $start  = (int) $request->getPost('start');
        $length = (int) $request->getPost('length');

        $search      = $request->getPost('search');
        $searchValue = $search['value'] ?? '';

        $result = $this->householdsModel->getRecords($start, $length, $searchValue);

        $counter = $start + 1;
        foreach ($result['data'] as &$row) {
            $row['row_number'] = $counter++;
        }

        return $this->response->setJSON([
            'draw'            => $draw,
            'recordsTotal'    => $this->householdsModel->where('deleted_at IS NULL')->countAllResults(false),
            'recordsFiltered' => $result['filtered'],
            'data'            => $result['data'],
            'csrf_hash'       => csrf_hash()
        ]);
    }

    // ==============================
    // SAVE HOUSEHOLD
    // ==============================
    public function save()
    {
        $data = [
            'head_name'         => $this->request->getPost('head_name'),
            'address_line1'     => $this->request->getPost('address_line1'),
            'purok'      => $this->request->getPost('purok'),
            'barangay'          => $this->request->getPost('barangay'),
            'city_municipality' => $this->request->getPost('city_municipality'),
            'province'          => $this->request->getPost('province'),
            'zip_code'          => $this->request->getPost('zip_code'),
            'total_members'     => $this->request->getPost('total_members') ?: 1,
            'status'            => $this->request->getPost('status'),
        ];

        // Validation - Purok/ is now required
        if (empty($data['head_name']) || empty($data['address_line1']) || empty($data['purok'])) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Head of Household, Address, and Purok/ are required'
            ]);
        }

        // Validate purok value
        $validPuroks = ['1', '2', '3', '4', '5', '6', '7', 'A', '7B'];
        if (!in_array($data['purok'], $validPuroks)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Invalid Purok/ selection'
            ]);
        }

        if ($this->householdsModel->insert($data)) {
            return $this->response->setJSON([
                'status'    => 'success',
                'message'   => 'Household saved successfully',
                'csrf_hash' => csrf_hash()
            ]);
        }

        return $this->response->setJSON([
            'status'    => 'error',
            'message'   => 'Failed to save household',
            'csrf_hash' => csrf_hash()
        ]);
    }

    // ==============================
    // GET SINGLE HOUSEHOLD
    // ==============================
    public function get($id)
    {
        $household = $this->householdsModel->find($id);

        if ($household) {
            return $this->response->setJSON([
                'status'    => 'success',
                'data'      => $household,
                'csrf_hash' => csrf_hash()
            ]);
        }

        return $this->response->setJSON([
            'status'    => 'error',
            'message'   => 'Household not found',
            'csrf_hash' => csrf_hash()
        ]);
    }

    // ==============================
    // UPDATE HOUSEHOLD
    // ==============================
    public function update()
    {
        $id = $this->request->getPost('id');

        if (empty($id)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Invalid household ID'
            ]);
        }

        $data = [
            'head_name'         => $this->request->getPost('head_name'),
            'address_line1'     => $this->request->getPost('address_line1'),
            'purok'      => $this->request->getPost('purok'),
            'barangay'          => $this->request->getPost('barangay'),
            'city_municipality' => $this->request->getPost('city_municipality'),
            'province'          => $this->request->getPost('province'),
            'zip_code'          => $this->request->getPost('zip_code'),
            'total_members'     => $this->request->getPost('total_members') ?: 1,
            'status'            => $this->request->getPost('status'),
        ];

        // Validation - Purok/ is now required
        if (empty($data['head_name']) || empty($data['address_line1']) || empty($data['purok'])) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Head of Household, Address, and Purok/ are required'
            ]);
        }

        // Validate purok value
        $validPuroks = ['1', '2', '3', '4', '5', '6', '7A', '7B'];
        if (!in_array($data['purok'], $validPuroks)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Invalid Purok/ selection'
            ]);
        }

        if ($this->householdsModel->update($id, $data)) {
            return $this->response->setJSON([
                'status'    => 'success',
                'message'   => 'Household updated successfully',
                'csrf_hash' => csrf_hash()
            ]);
        }

        return $this->response->setJSON([
            'status'    => 'error',
            'message'   => 'Failed to update household',
            'csrf_hash' => csrf_hash()
        ]);
    }

    // ==============================
    // DELETE HOUSEHOLD
    // ==============================
    public function delete($id)
    {
        if ($this->householdsModel->delete($id)) {
            return $this->response->setJSON([
                'status'    => 'success',
                'message'   => 'Household deleted successfully',
                'csrf_hash' => csrf_hash()
            ]);
        }

        return $this->response->setJSON([
            'status'    => 'error',
            'message'   => 'Failed to delete household',
            'csrf_hash' => csrf_hash()
        ]);
    }
}
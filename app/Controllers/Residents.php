<?php

namespace App\Controllers;

use App\Models\ResidentsModel;

class Residents extends BaseController
{
    protected $residentsModel;

    public function __construct()
    {
        $this->residentsModel = new ResidentsModel();
    }

    public function index()
    {
        return view('residents/index');
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

    $search = $request->getPost('search');
    $searchValue = $search['value'] ?? '';

    $result = $this->residentsModel->getRecords($start, $length, $searchValue);

    $counter = $start + 1;
    foreach ($result['data'] as &$row) {
        $row['row_number'] = $counter++;
    }

    return $this->response->setJSON([
        'draw'            => $draw,
        'recordsTotal'    => $this->residentsModel->countAllResults(false),
        'recordsFiltered' => $result['filtered'],
        'data'            => $result['data']
    ]);
}

    // ==============================
    // SAVE RESIDENT (FULL FIELDS)
    // ==============================
    public function save()
    {
        $data = [
            'first_name'     => $this->request->getPost('first_name'),
            'middle_name'    => $this->request->getPost('middle_name'),
            'last_name'      => $this->request->getPost('last_name'),
            'suffix'         => $this->request->getPost('suffix'),
            'birthdate'      => $this->request->getPost('birthdate'),
            'gender'         => $this->request->getPost('gender'),
            'civil_status'   => $this->request->getPost('civil_status'),
            'is_voter'       => $this->request->getPost('is_voter') ? 1 : 0,
            'voter_id'       => $this->request->getPost('voter_id'),
            'household_id'   => $this->request->getPost('household_id'),
            'address_line1'  => $this->request->getPost('address_line1'),
            'barangay'       => $this->request->getPost('barangay'),
            'status'         => $this->request->getPost('status')
        ];

        // Basic validation (User-style simplicity)
        if (
            empty($data['first_name']) ||
            empty($data['last_name']) ||
            empty($data['gender']) ||
            empty($data['address_line1'])
        ) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Required fields are missing'
            ]);
        }

        if ($this->residentsModel->insert($data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Resident saved successfully'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to save resident'
        ]);
    }

    // ==============================
    // GET SINGLE RESIDENT
    // ==============================
    public function get($id)
    {
        $resident = $this->residentsModel->find($id);

        if ($resident) {
            return $this->response->setJSON([
                'status' => 'success',
                'data' => $resident
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Resident not found'
        ]);
    }

    // ==============================
    // UPDATE RESIDENT (FULL FIELDS)
    // ==============================
    public function update()
    {
        $id = $this->request->getPost('id');

        if (empty($id)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid resident ID'
            ]);
        }

        $data = [
            'first_name'     => $this->request->getPost('first_name'),
            'middle_name'    => $this->request->getPost('middle_name'),
            'last_name'      => $this->request->getPost('last_name'),
            'suffix'         => $this->request->getPost('suffix'),
            'birthdate'      => $this->request->getPost('birthdate'),
            'gender'         => $this->request->getPost('gender'),
            'civil_status'   => $this->request->getPost('civil_status'),
            'is_voter'       => $this->request->getPost('is_voter') ? 1 : 0,
            'voter_id'       => $this->request->getPost('voter_id'),
            'household_id'   => $this->request->getPost('household_id'),
            'address_line1'  => $this->request->getPost('address_line1'),
            'barangay'       => $this->request->getPost('barangay'),
            'status'         => $this->request->getPost('status')
        ];

        if ($this->residentsModel->update($id, $data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Resident updated successfully'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to update resident'
        ]);
    }

    // ==============================
    // DELETE RESIDENT
    // ==============================
    public function delete($id)
    {
        if ($this->residentsModel->delete($id)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Resident deleted successfully'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to delete resident'
        ]);
    }
}
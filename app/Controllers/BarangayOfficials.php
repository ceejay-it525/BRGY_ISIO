<?php

namespace App\Controllers;

use App\Models\BarangayOfficialsModel;
use CodeIgniter\API\ResponseTrait;

class BarangayOfficials extends BaseController
{
    use ResponseTrait;

    protected $barangayOfficialsModel;

    public function __construct()
    {
        $this->barangayOfficialsModel = new BarangayOfficialsModel();
    }

    public function index()
    {
        return view('barangay_officials/index');
    }

    public function fetchRecords()
    {
        $request = service('request');

        $draw   = (int) $request->getPost('draw');
        $start  = (int) $request->getPost('start');
        $length = (int) $request->getPost('length');
        
        $searchPost = $request->getPost('search');
        $search = is_array($searchPost) ? ($searchPost['value'] ?? '') : ($request->getPost('search[value]') ?? '');

        $result = $this->barangayOfficialsModel->getRecords($start, $length, $search);

        $data = [];
        foreach ($result['data'] as $row) {
            $row['full_name'] = trim("{$row['first_name']} {$row['middle_name']} {$row['last_name']}");
            $row['photo_url'] = (!empty($row['photo'])) 
                ? base_url("uploads/officials/{$row['photo']}") 
                : '';
            $row['term_end_display'] = $row['term_end'] ?: 'Present';
            $row['status_badge'] = $row['status'] === 'Active'
                ? '<span class="badge bg-success">Active</span>'
                : '<span class="badge bg-warning">Inactive</span>';
            $data[] = $row;
        }

        return $this->response->setJSON([
            'draw' => $draw,
            'recordsTotal' => $this->barangayOfficialsModel->countAllResults(),
            'recordsFiltered' => $result['filtered'],
            'data' => $data,
            'csrf_hash' => csrf_hash()
        ]);
    }

    public function save()
    {
        $rules = [
            'first_name' => 'required',
            'last_name'  => 'required',
            'position'   => 'required',
            'photo'      => 'permit_empty|is_image[photo]|max_size[photo,2048]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status'    => 'error',
                'message'   => 'Validation failed',
                'errors'    => $this->validator->getErrors(),
                'csrf_hash' => csrf_hash()
            ]);
        }

        $data = $this->request->getPost();
        $photo = $this->request->getFile('photo');

        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            $destination = FCPATH . 'uploads/officials/';
            if (!is_dir($destination)) {
                mkdir($destination, 0755, true);
            }
            $photoName = $photo->getRandomName();
            $photo->move($destination, $photoName);
            $data['photo'] = $photoName;
        }

        if ($this->barangayOfficialsModel->insert($data)) {
            return $this->response->setJSON([
                'status'    => 'success',
                'message'   => 'Saved successfully',
                'csrf_hash' => csrf_hash()
            ]);
        }

        return $this->response->setJSON([
            'status'    => 'error',
            'message'   => 'Failed to save',
            'csrf_hash' => csrf_hash()
        ]);
    }

    public function get($id)
    {
        $data = $this->barangayOfficialsModel->find($id);

        if (!$data) {
            return $this->failNotFound('Not found');
        }

        return $this->response->setJSON([
            'status'    => 'success',
            'data'      => $data,
            'csrf_hash' => csrf_hash()
        ]);
    }

    public function update()
    {
        $id = $this->request->getPost('id');
        
        $rules = [
            'first_name' => 'required',
            'last_name'  => 'required',
            'position'   => 'required',
            'photo'      => 'permit_empty|is_image[photo]|max_size[photo,2048]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status'    => 'error',
                'message'   => 'Validation failed',
                'csrf_hash' => csrf_hash()
            ]);
        }

        $data = $this->request->getPost();
        $photo = $this->request->getFile('photo');

        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            $destination = FCPATH . 'uploads/officials/';
            if (!is_dir($destination)) {
                mkdir($destination, 0755, true);
            }
            $photoName = $photo->getRandomName();
            $photo->move($destination, $photoName);
            $data['photo'] = $photoName;
        }

        if ($this->barangayOfficialsModel->update($id, $data)) {
            return $this->response->setJSON([
                'status'    => 'success',
                'message'   => 'Updated successfully',
                'csrf_hash' => csrf_hash()
            ]);
        }

        return $this->response->setJSON([
            'status'    => 'error',
            'message'   => 'Update failed',
            'csrf_hash' => csrf_hash()
        ]);
    }

    public function delete($id)
    {
        if ($this->barangayOfficialsModel->delete($id)) {
            return $this->response->setJSON([
                'status'    => 'success',
                'message'   => 'Deleted successfully',
                'csrf_hash' => csrf_hash()
            ]);
        }
        return $this->response->setJSON([
            'status'    => 'error',
            'message'   => 'Delete failed',
            'csrf_hash' => csrf_hash()
        ]);
    }
}

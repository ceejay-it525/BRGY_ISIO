<?php

namespace App\Controllers;

use App\Models\BarangayOfficialsModel;

class BarangayOfficials extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new BarangayOfficialsModel();
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
        $search = '';

        $searchPost = $request->getPost('search');
        if (is_array($searchPost)) {
            $search = $searchPost['value'] ?? '';
        } else {
            $search = $request->getPost('search[value]') ?? '';
        }

        $result = $this->model->getRecords($start, $length, $search);

        $data = [];
        foreach ($result['data'] as $row) {
            $row['full_name'] = trim("{$row['first_name']} {$row['middle_name']} {$row['last_name']}");
            $photo = isset($row['photo']) ? $row['photo'] : null;
            $row['photo_url'] = $photo
                ? base_url("uploads/officials/{$photo}")
                : '';
            $row['term_end_display'] = $row['term_end'] ?: 'Present';
            $row['status_badge'] = $row['status'] === 'Active'
                ? '<span class="badge bg-success">Active</span>'
                : '<span class="badge bg-warning">Inactive</span>';
            $data[] = $row;
        }

        return $this->response->setJSON([
            'draw' => $draw,
            'recordsTotal' => $this->model->countAll(),
            'recordsFiltered' => $result['filtered'],
            'data' => $data,
            'csrf_hash' => csrf_hash()
        ]);
    }

    public function save()
    {
        $photo = $this->request->getFile('photo');
        $data = $this->request->getPost();

        if (!$this->validate([
            'first_name' => 'required',
            'last_name'  => 'required',
            'position'   => 'required',
            'photo'      => 'permit_empty|mime_in[photo,image/jpg,image/jpeg,image/png,image/gif]|max_size[photo,2048]'
        ])) {
            return $this->fail('Validation failed');
        }

        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            $destination = FCPATH . 'uploads/officials/';
            if (!is_dir($destination)) {
                mkdir($destination, 0755, true);
            }
            $photoName = $photo->getRandomName();
            $photo->move($destination, $photoName);
            $data['photo'] = $photoName;
        }

        $this->model->insert($data);

        return $this->response->setJSON([
            'status'    => 'success',
            'message'   => 'Saved successfully',
            'csrf_hash' => csrf_hash()
        ]);
    }

    public function get($id)
    {
        $data = $this->model->find($id);

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
        $photo = $this->request->getFile('photo');
        $data = $this->request->getPost();

        if (!$this->validate([
            'first_name' => 'required',
            'last_name'  => 'required',
            'position'   => 'required',
            'photo'      => 'permit_empty|mime_in[photo,image/jpg,image/jpeg,image/png,image/gif]|max_size[photo,2048]'
        ])) {
            return $this->fail('Validation failed');
        }

        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            $destination = FCPATH . 'uploads/officials/';
            if (!is_dir($destination)) {
                mkdir($destination, 0755, true);
            }
            $photoName = $photo->getRandomName();
            $photo->move($destination, $photoName);
            $data['photo'] = $photoName;
        }

        $this->model->update($id, $data);

        return $this->response->setJSON([
            'status'    => 'success',
            'message'   => 'Updated successfully',
            'csrf_hash' => csrf_hash()
        ]);
    }

    public function delete($id)
    {
        $this->model->delete($id);

        return $this->response->setJSON([
            'status'    => 'success',
            'message'   => 'Deleted successfully',
            'csrf_hash' => csrf_hash()
        ]);
    }
}
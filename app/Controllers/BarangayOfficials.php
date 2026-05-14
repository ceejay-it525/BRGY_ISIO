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

        $searchPost = $request->getPost('search');
        if (is_array($searchPost)) {
            $search = $searchPost['value'] ?? '';
        } else {
            $search = $request->getPost('search[value]') ?? '';
        }

        $result = $this->model->getRecords($start, $length, $search);

        $data = [];
        foreach ($result['data'] as $row) {
            $row['full_name']         = trim("{$row['first_name']} {$row['middle_name']} {$row['last_name']}");
            $row['photo_url']         = !empty($row['photo'])
                ? base_url("uploads/officials/{$row['photo']}")
                : '';
            $row['term_end_display']  = $row['term_end'] ?: 'Present';
            $row['status_badge']      = $row['status'] === 'Active'
                ? '<span class="badge bg-success">Active</span>'
                : '<span class="badge bg-warning text-dark">Inactive</span>';
            $data[] = $row;
        }

        return $this->response->setJSON([
            'draw'            => $draw,
            'recordsTotal'    => $this->model->countAll(),
            'recordsFiltered' => $result['filtered'],
            'data'            => $data,
            'csrf_hash'       => csrf_hash(),
        ]);
    }

    public function save()
    {
        $photo = $this->request->getFile('photo');
        $data  = $this->request->getPost();

        if (!$this->validate([
            'first_name' => 'required',
            'last_name'  => 'required',
            'position'   => 'required',
            'photo'      => 'permit_empty|mime_in[photo,image/jpg,image/jpeg,image/png,image/gif]|max_size[photo,2048]',
        ])) {
            return $this->response->setJSON([
                'status'    => 'error',
                'message'   => 'Validation failed: ' . implode(', ', $this->validator->getErrors()),
                'csrf_hash' => csrf_hash(),
            ]);
        }

        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            $destination = FCPATH . 'uploads/officials/';
            if (!is_dir($destination)) {
                mkdir($destination, 0755, true);
            }
            $photoName      = $photo->getRandomName();
            $photo->move($destination, $photoName);
            $data['photo']  = $photoName;
        }

        unset($data['csrf_test_name'], $data['id']);

        $this->model->insert($data);

        return $this->response->setJSON([
            'status'    => 'success',
            'message'   => 'Official saved successfully.',
            'csrf_hash' => csrf_hash(),
        ]);
    }

    // GET — fetch a single record for the Edit modal
    public function edit($id)
    {
        $record = $this->model->find($id);

        if (!$record) {
            return $this->failNotFound('Official not found.');
        }

        return $this->response->setJSON([
            'status'    => 'success',
            'data'      => $record,
            'csrf_hash' => csrf_hash(),
        ]);
    }

    public function update()
    {
        $id    = (int) $this->request->getPost('id');
        $photo = $this->request->getFile('photo');
        $data  = $this->request->getPost();

        if (!$id) {
            return $this->response->setJSON([
                'status'    => 'error',
                'message'   => 'Invalid ID.',
                'csrf_hash' => csrf_hash(),
            ]);
        }

        if (!$this->validate([
            'first_name' => 'required',
            'last_name'  => 'required',
            'position'   => 'required',
            'photo'      => 'permit_empty|mime_in[photo,image/jpg,image/jpeg,image/png,image/gif]|max_size[photo,2048]',
        ])) {
            return $this->response->setJSON([
                'status'    => 'error',
                'message'   => 'Validation failed: ' . implode(', ', $this->validator->getErrors()),
                'csrf_hash' => csrf_hash(),
            ]);
        }

        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            $destination = FCPATH . 'uploads/officials/';
            if (!is_dir($destination)) {
                mkdir($destination, 0755, true);
            }
            $photoName     = $photo->getRandomName();
            $photo->move($destination, $photoName);
            $data['photo'] = $photoName;
        } else {
            unset($data['photo']); // keep existing photo
        }

        unset($data['csrf_test_name'], $data['id']);

        $this->model->update($id, $data);

        return $this->response->setJSON([
            'status'    => 'success',
            'message'   => 'Official updated successfully.',
            'csrf_hash' => csrf_hash(),
        ]);
    }

    // Accepts both GET and POST (JS sends $.post)
    public function delete($id)
    {
        $id = (int) $id;

        if (!$id || !$this->model->find($id)) {
            return $this->response->setJSON([
                'status'    => 'error',
                'message'   => 'Official not found.',
                'csrf_hash' => csrf_hash(),
            ]);
        }

        $this->model->delete($id);

        return $this->response->setJSON([
            'status'    => 'success',
            'message'   => 'Official deleted successfully.',
            'csrf_hash' => csrf_hash(),
        ]);
    }
}

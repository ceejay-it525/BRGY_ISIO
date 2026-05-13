<?php

namespace App\Controllers;

use App\Models\EventsModel;
use CodeIgniter\Controller;

class Events extends Controller
{
    use \CodeIgniter\API\ResponseTrait;

    protected $eventsModel;

    public function __construct()
    {
        $this->eventsModel = new EventsModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Barangay Events',
        ];
        return view('events/index', $data);
    }

    public function fetchRecords()
    {
        $draw        = $this->request->getPost('draw');
        $start       = (int)($this->request->getPost('start') ?? 0);
        $length      = (int)($this->request->getPost('length') ?? 10);
        $searchValue = $this->request->getPost('search')['value'] ?? '';
        $order       = $this->request->getPost('order');

        $builder = $this->eventsModel->builder();
        
        // Search Logic
        if (!empty($searchValue)) {
            $builder->groupStart()
                ->like('title', $searchValue)
                ->orLike('description', $searchValue)
                ->orLike('event_date', $searchValue)
                ->groupEnd();
        }

        // Server-side Sorting
        if ($order) {
            $columns = [
                2 => 'title',
                3 => 'description',
                4 => 'event_date',
                5 => 'event_date',
            ];
            $colIdx = $order[0]['column'];
            $colDir = $order[0]['dir'];
            if (isset($columns[$colIdx])) {
                $builder->orderBy($columns[$colIdx], $colDir);
            }
        } else {
            $builder->orderBy('event_date', 'DESC');
        }

        $totalRecords = $this->eventsModel->countAllResults(false);
        $filteredRecords = $builder->countAllResults(false);
        $result = $builder->limit($length, $start)->get()->getResultArray();

        // Data Transformation
        foreach ($result as &$row) {
            $row['formatted_date'] = date('F d, Y', strtotime($row['event_date']));
            $row['days_until'] = (int)ceil((strtotime($row['event_date']) - time()) / 86400);
        }

        return $this->response->setJSON([
            'draw'            => $draw,
            'recordsTotal'    => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data'            => $result,
            'csrf_hash'       => csrf_hash(), 
        ]);
    }

    public function save()
    {
        $rules = [
            'title'       => 'required|max_length[255]',
            'description' => 'permit_empty|max_length[1000]',
            'event_date'  => 'required|valid_date',
            'color'       => 'required|in_list[primary,success,warning,danger,info,secondary]',
            'icon'        => 'required|max_length[50]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status'    => 422,
                'errors'    => $this->validator->getErrors(),
                'csrf_hash' => csrf_hash(),
            ])->setStatusCode(422);
        }

        $data = $this->request->getPost(['title', 'description', 'event_date', 'color', 'icon']);
        
        if ($this->eventsModel->insert($data)) {
            return $this->response->setJSON([
                'status'    => 200,
                'message'   => 'Event created successfully.',
                'csrf_hash' => csrf_hash(),
            ]);
        }

        return $this->response->setJSON(['status' => 500, 'csrf_hash' => csrf_hash()])->setStatusCode(500);
    }

    public function edit($id)
    {
        $data = $this->eventsModel->find($id);

        if ($data) {
            $data['formatted_date'] = date('F d, Y', strtotime($data['event_date']));
            $data['days_until'] = (int)ceil((strtotime($data['event_date']) - time()) / 86400);
            $data['csrf_hash'] = csrf_hash();
            return $this->response->setJSON($data);
        }

        return $this->response->setJSON(['status' => 404, 'csrf_hash' => csrf_hash()])->setStatusCode(404);
    }

    public function update($id)
    {
        $rules = $this->eventsModel->getValidationRules();
        if (!$this->validate($rules)) {
             return $this->response->setJSON(['status' => 422, 'errors' => $this->validator->getErrors(), 'csrf_hash' => csrf_hash()]);
        }

        $data = $this->request->getPost(['title', 'description', 'event_date', 'color', 'icon']);
        
        if ($this->eventsModel->update($id, $data)) {
            return $this->response->setJSON(['status' => 200, 'message' => 'Updated!', 'csrf_hash' => csrf_hash()]);
        }

        return $this->response->setJSON(['status' => 500, 'csrf_hash' => csrf_hash()]);
    }

    public function delete($id)
    {
        if ($this->eventsModel->delete($id)) {
            return $this->response->setJSON(['status' => 200, 'csrf_hash' => csrf_hash()]);
        }
        return $this->response->setJSON(['status' => 500, 'csrf_hash' => csrf_hash()]);
    }
}

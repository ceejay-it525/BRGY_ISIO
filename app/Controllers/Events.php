<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Events extends Controller
{
    use \CodeIgniter\API\ResponseTrait;

    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    // ─── Index ────────────────────────────────────────────────────────────────
    public function index()
    {
        $data = [
            'title' => 'Barangay Events',
        ];
        return view('events/index', $data);
    }

    // ─── Fetch All Events (for DataTable) ────────────────────────────────────────
    public function fetchRecords()
    {
        $draw        = $this->request->getPost('draw');
        $start       = (int)($this->request->getPost('start') ?? 0);
        $length      = (int)($this->request->getPost('length') ?? 10);
        $searchValue = $this->request->getPost('search')['value'] ?? '';

        // Build query
        $builder = $this->db->table('barangay_events');
        
        // Search
        if (!empty($searchValue)) {
            $builder->groupStart();
            $builder->like('title', $searchValue);
            $builder->orLike('description', $searchValue);
            $builder->orLike('event_date', $searchValue);
            $builder->groupEnd();
        }

        // Get total count
        $totalRecords = $this->db->table('barangay_events')->countAllResults();

        // Get filtered count
        $filteredRecords = $builder->countAllResults(false);

        // Get data with pagination
        $builder->orderBy('event_date', 'DESC');
        $builder->limit($length, $start);
        $result = $builder->get()->getResultArray();

        // Format dates
        foreach ($result as &$row) {
            $row['formatted_date'] = date('F d, Y', strtotime($row['event_date']));
            $row['days_until'] = ceil((strtotime($row['event_date']) - time()) / 86400);
        }

        return $this->response->setJSON([
            'draw'            => $draw,
            'recordsTotal'    => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data'            => $result,
        ]);
    }

    // ─── Save (insert) ────────────────────────────────────────────────────────
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
                'message'   => 'Validation failed',
                'errors'    => $this->validator->getErrors(),
                'csrf_hash' => csrf_hash(),
            ])->setStatusCode(422);
        }

        $data = [
            'title'       => $this->request->getPost('title'),
            'description' => $this->request->getPost('description') ?: null,
            'event_date'  => $this->request->getPost('event_date'),
            'color'       => $this->request->getPost('color'),
            'icon'        => $this->request->getPost('icon'),
            'created_at'  => date('Y-m-d H:i:s'),
        ];

        $builder = $this->db->table('barangay_events');
        $result = $builder->insert($data);

        if ($result) {
            return $this->response->setJSON([
                'status'    => 200,
                'message'   => 'Event created successfully.',
                'csrf_hash' => csrf_hash(),
            ]);
        }

        return $this->response->setJSON([
            'status'    => 500,
            'message'   => 'Failed to save event.',
            'csrf_hash' => csrf_hash(),
        ])->setStatusCode(500);
    }

    // ─── Edit (fetch single record as JSON) ───────────────────────────────────
    public function edit($id)
    {
        $builder = $this->db->table('barangay_events');
        $data = $builder->where('id', $id)->get()->getRowArray();

        if ($data) {
            return $this->response->setJSON($data);
        }

        return $this->response->setJSON([
            'status'  => 404,
            'message' => 'Event not found.',
        ])->setStatusCode(404);
    }

    // ─── Update ───────────────────────────────────────────────────────────────
    public function update($id)
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
                'message'   => 'Validation failed',
                'errors'    => $this->validator->getErrors(),
                'csrf_hash' => csrf_hash(),
            ])->setStatusCode(422);
        }

        $data = [
            'title'       => $this->request->getPost('title'),
            'description' => $this->request->getPost('description') ?: null,
            'event_date'  => $this->request->getPost('event_date'),
            'color'       => $this->request->getPost('color'),
            'icon'        => $this->request->getPost('icon'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ];

        $builder = $this->db->table('barangay_events');
        $result = $builder->where('id', $id)->update($data);

        if ($result) {
            return $this->response->setJSON([
                'status'    => 200,
                'message'   => 'Event updated successfully.',
                'csrf_hash' => csrf_hash(),
            ]);
        }

        return $this->response->setJSON([
            'status'    => 500,
            'message'   => 'Failed to update event.',
            'csrf_hash' => csrf_hash(),
        ])->setStatusCode(500);
    }

    // ─── Delete ───────────────────────────────────────────────────────────────
    public function delete($id)
    {
        $builder = $this->db->table('barangay_events');
        $result = $builder->where('id', $id)->delete();

        if ($result) {
            return $this->response->setJSON([
                'status'    => 200,
                'message'   => 'Event deleted successfully.',
                'csrf_hash' => csrf_hash(),
            ]);
        }

        return $this->response->setJSON([
            'status'    => 500,
            'message'   => 'Failed to delete event.',
            'csrf_hash' => csrf_hash(),
        ])->setStatusCode(500);
    }

    // ─── Get upcoming events for dashboard ─────────────────────────────────────
    public function upcoming()
    {
        $builder = $this->db->table('barangay_events');
        $events = $builder->where('event_date >=', date('Y-m-d'))
            ->orderBy('event_date', 'ASC')
            ->limit(4)
            ->get()
            ->getResultArray();

        return $this->response->setJSON([
            'status' => 200,
            'events' => $events,
        ]);
    }
}
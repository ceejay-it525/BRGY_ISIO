<?php

namespace App\Controllers;

use App\Models\BlotterModel;

class Blotter extends BaseController

{

    protected $blotterModel;

    // Valid forward-only status transitions
    protected $statusFlow = [
        'Ongoing'       => 'Investigation',
        'Investigation' => 'Mediation',
        'Mediation'     => 'Settled',
        'Settled'       => 'Closed',
        'Closed'        => null,
    ];

    public function __construct()

    {

        $this->blotterModel = new BlotterModel();

    }

    public function index()

    {

        return view('blotter/index');

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

        $searchType  = $request->getPost('search_type') ?? 'all';

        $searchTerm  = trim($request->getPost('search_term') ?? $searchValue);

        $result  = $this->blotterModel->getRecords($start, $length, $searchTerm, $searchType);

        $counter = $start + 1;

        foreach ($result['data'] as &$row) {

            $row['row_number'] = $counter++;

        }

        return $this->response->setJSON([

            'draw'            => $draw,

            'recordsTotal'    => $result['recordsTotal'] ?? 0,

            'recordsFiltered' => $result['filtered'],

            'data'            => $result['data'],

            'csrf_hash'       => csrf_hash()

        ]);

    }

    // ==============================
    // SAVE BLOTTER
    // ==============================

    public function save()

    {

        $data = [

            'case_number'      => $this->request->getPost('case_number'),

            'incident_type'    => $this->request->getPost('incident_type'),

            'incident_date'    => $this->request->getPost('incident_date'),

            'complainant_name' => $this->request->getPost('complainant_name'),

            'respondent_name'  => $this->request->getPost('respondent_name'),

            'incident_location'=> $this->request->getPost('incident_location'),

            'status'           => 'Ongoing',

            'narrative'        => $this->request->getPost('narrative'),

            'action_taken'     => $this->request->getPost('action_taken'),

        ];

        if (

            empty($data['incident_type']) ||

            empty($data['incident_date']) ||

            empty($data['complainant_name']) ||

            empty($data['respondent_name'])

        ) {

            return $this->response->setJSON([

                'status'  => 'error',

                'message' => 'Required fields are missing.',

            ]);

        }

        if ($this->blotterModel->insert($data)) {

            return $this->response->setJSON([

                'status'    => 'success',

                'message'   => 'Blotter record saved successfully.',

                'csrf_hash' => csrf_hash()

            ]);

        }

        return $this->response->setJSON([

            'status'    => 'error',

            'message'   => 'Failed to save blotter record.',

            'csrf_hash' => csrf_hash()

        ]);

    }

    // ==============================
    // GET SINGLE BLOTTER
    // ==============================

    public function get($id)

    {

        $blotter = $this->blotterModel->find($id);

        if ($blotter) {

            $blotter['next_status'] = $this->statusFlow[$blotter['status']] ?? null;

            return $this->response->setJSON([

                'status'    => 'success',

                'data'      => $blotter,

                'csrf_hash' => csrf_hash()

            ]);

        }

        return $this->response->setJSON([

            'status'    => 'error',

            'message'   => 'Blotter record not found.',

            'csrf_hash' => csrf_hash()

        ]);

    }

    // ==============================
    // UPDATE BLOTTER
    // ==============================

    public function update()

    {

        $id = $this->request->getPost('id');

        if (empty($id)) {

            return $this->response->setJSON([

                'status'  => 'error',

                'message' => 'Invalid blotter ID.',

            ]);

        }

        $data = [

            'case_number'      => $this->request->getPost('case_number'),

            'incident_type'    => $this->request->getPost('incident_type'),

            'incident_date'    => $this->request->getPost('incident_date'),

            'complainant_name' => $this->request->getPost('complainant_name'),

            'respondent_name'  => $this->request->getPost('respondent_name'),

            'incident_location'=> $this->request->getPost('incident_location'),

            'status'           => $this->request->getPost('status') ?: 'Ongoing',

            'narrative'        => $this->request->getPost('narrative'),

            'action_taken'     => $this->request->getPost('action_taken'),

        ];

        if (

            empty($data['incident_type']) ||

            empty($data['incident_date']) ||

            empty($data['complainant_name']) ||

            empty($data['respondent_name'])

        ) {

            return $this->response->setJSON([

                'status'  => 'error',

                'message' => 'Required fields are missing.',

            ]);

        }

        if ($this->blotterModel->update($id, $data)) {

            return $this->response->setJSON([

                'status'    => 'success',

                'message'   => 'Blotter record updated successfully.',

                'csrf_hash' => csrf_hash()

            ]);

        }

        return $this->response->setJSON([

            'status'    => 'error',

            'message'   => 'Failed to update blotter record.',

            'csrf_hash' => csrf_hash()

        ]);

    }

    // ==============================
    // ADVANCE STATUS
    // ==============================

    public function advanceStatus($id)

    {

        $blotter = $this->blotterModel->find($id);

        if (!$blotter) {

            return $this->response->setJSON([

                'status'    => 'error',

                'message'   => 'Record not found.',

                'csrf_hash' => csrf_hash()

            ]);

        }

        $nextStatus = $this->statusFlow[$blotter['status']] ?? null;

        if ($nextStatus === null) {

            return $this->response->setJSON([

                'status'    => 'error',

                'message'   => 'This record is already at the final status.',

                'csrf_hash' => csrf_hash()

            ]);

        }

        if ($this->blotterModel->update($id, ['status' => $nextStatus])) {

            return $this->response->setJSON([

                'status'      => 'success',

                'message'     => 'Status advanced to ' . $nextStatus . '.',

                'new_status'  => $nextStatus,

                'next_status' => $this->statusFlow[$nextStatus] ?? null,

                'csrf_hash'   => csrf_hash()

            ]);

        }

        return $this->response->setJSON([

            'status'    => 'error',

            'message'   => 'Failed to advance status.',

            'csrf_hash' => csrf_hash()

        ]);

    }

    // ==============================
    // DELETE BLOTTER
    // ==============================

    public function delete($id)

    {

        if ($this->blotterModel->delete($id)) {

            return $this->response->setJSON([

                'status'    => 'success',

                'message'   => 'Blotter record deleted successfully.',

                'csrf_hash' => csrf_hash()

            ]);

        }

        return $this->response->setJSON([

            'status'    => 'error',

            'message'   => 'Failed to delete blotter record.',

            'csrf_hash' => csrf_hash()

        ]);

    }

}

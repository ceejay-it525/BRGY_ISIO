<?php
namespace App\Controllers;

use App\Models\BlotterModel;
use App\Models\ResidentsModel;

class Blotter extends BaseController
{
    protected $blotterModel;
    protected $residentsModel;

    public function __construct()
    {
        $this->blotterModel   = new BlotterModel();
        $this->residentsModel = new ResidentsModel();
    }

    public function index()
    {
        $data['residents'] = $this->residentsModel->findAll();
        return view('blotter/index', $data);
    }

    public function fetchRecords()
    {
        $request    = service('request');
        $draw       = (int) $request->getPost('draw');
        $start      = (int) $request->getPost('start');
        $length     = (int) $request->getPost('length');
        $searchType = $request->getPost('searchtype') ?? 'all';
        $searchTerm = trim($request->getPost('searchterm') ?? '');
        $filter     = trim($request->getPost('filter') ?? '');

        $result  = $this->blotterModel->getRecords($start, $length, $searchTerm, $searchType, $filter);
        $counter = $start + 1;
        foreach ($result['data'] as &$row) {
            $row['row_number'] = $counter++;
        }

        return $this->response->setJSON([
            'draw'            => $draw,
            'recordsTotal'    => $this->blotterModel->countAllResults(false),
            'recordsFiltered' => $result['filtered'],
            'data'            => $result['data'],
            'csrf_hash'       => csrf_hash(),
        ]);
    }

    public function save()
    {
        $validStatuses = ['Pending', 'Ongoing', 'Settled', 'Referred', 'Dismissed'];
        $status        = $this->request->getPost('status');

        $data = [
            'case_number'            => $this->request->getPost('case_number'),
            'incident_type'          => $this->request->getPost('incident_type'),
            'incident_date'          => $this->request->getPost('incident_date'),
            'complainant_resident_id'=> $this->request->getPost('complainant_resident_id'),
            'respondent_resident_id' => $this->request->getPost('respondent_resident_id'),
            'incident_location'      => $this->request->getPost('incident_location'),
            'status'                 => in_array($status, $validStatuses) ? $status : 'Pending',
            'narrative'              => $this->request->getPost('narrative'),
            'action_taken'           => $this->request->getPost('action_taken'),
        ];

        if (
            empty($data['incident_type'])           ||
            empty($data['incident_date'])           ||
            empty($data['complainant_resident_id']) ||
            empty($data['respondent_resident_id'])
        ) {
            return $this->response->setJSON([
                'status'    => 'error',
                'message'   => 'Required fields are missing.',
                'csrf_hash' => csrf_hash(),
            ]);
        }

        if ($this->blotterModel->insert($data)) {
            return $this->response->setJSON([
                'status'    => 'success',
                'message'   => 'Blotter record saved successfully.',
                'csrf_hash' => csrf_hash(),
            ]);
        }

        return $this->response->setJSON([
            'status'    => 'error',
            'message'   => 'Failed to save blotter record.',
            'csrf_hash' => csrf_hash(),
        ]);
    }

    public function get($id)
    {
        $blotter = $this->blotterModel->find($id);
        if ($blotter) {
            return $this->response->setJSON([
                'status'    => 'success',
                'data'      => $blotter,
                'csrf_hash' => csrf_hash(),
            ]);
        }

        return $this->response->setJSON([
            'status'    => 'error',
            'message'   => 'Blotter record not found.',
            'csrf_hash' => csrf_hash(),
        ]);
    }

    public function update()
    {
        $id = $this->request->getPost('id');
        if (empty($id)) {
            return $this->response->setJSON([
                'status'    => 'error',
                'message'   => 'Invalid blotter ID.',
                'csrf_hash' => csrf_hash(),
            ]);
        }

        $validStatuses = ['Pending', 'Ongoing', 'Settled', 'Referred', 'Dismissed'];
        $status        = $this->request->getPost('status');

        $data = [
            'case_number'            => $this->request->getPost('case_number'),
            'incident_type'          => $this->request->getPost('incident_type'),
            'incident_date'          => $this->request->getPost('incident_date'),
            'complainant_resident_id'=> $this->request->getPost('complainant_resident_id'),
            'respondent_resident_id' => $this->request->getPost('respondent_resident_id'),
            'incident_location'      => $this->request->getPost('incident_location'),
            'status'                 => in_array($status, $validStatuses) ? $status : 'Pending',
            'narrative'              => $this->request->getPost('narrative'),
            'action_taken'           => $this->request->getPost('action_taken'),
        ];

        if (
            empty($data['incident_type'])           ||
            empty($data['incident_date'])           ||
            empty($data['complainant_resident_id']) ||
            empty($data['respondent_resident_id'])
        ) {
            return $this->response->setJSON([
                'status'    => 'error',
                'message'   => 'Required fields are missing.',
                'csrf_hash' => csrf_hash(),
            ]);
        }

        if ($this->blotterModel->update($id, $data)) {
            return $this->response->setJSON([
                'status'    => 'success',
                'message'   => 'Blotter record updated successfully.',
                'csrf_hash' => csrf_hash(),
            ]);
        }

        return $this->response->setJSON([
            'status'    => 'error',
            'message'   => 'Failed to update blotter record.',
            'csrf_hash' => csrf_hash(),
        ]);
    }

    public function delete($id)
    {
        if (empty($id) || !is_numeric($id)) {
            return $this->response->setJSON([
                'status'    => 'error',
                'message'   => 'Invalid blotter ID.',
                'csrf_hash' => csrf_hash(),
            ]);
        }

        if ($this->blotterModel->delete($id)) {
            return $this->response->setJSON([
                'status'    => 'success',
                'message'   => 'Blotter record deleted successfully.',
                'csrf_hash' => csrf_hash(),
            ]);
        }

        return $this->response->setJSON([
            'status'    => 'error',
            'message'   => 'Failed to delete blotter record.',
            'csrf_hash' => csrf_hash(),
        ]);
    }
}

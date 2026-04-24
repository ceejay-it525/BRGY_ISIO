<?php

namespace App\Controllers;
use App\Models\BlotterPartiesModel;
use App\Models\BlotterHearingsModel;
use App\Models\BlotterModel;
use CodeIgniter\API\ResponseTrait;

class Blotter extends BaseController
{
    use ResponseTrait;

    protected $blotterModel;

    public function __construct()
    {
        $this->blotterModel = new BlotterModel();
    }

    public function index()
    {
        return view('blotter/index');
    }

    public function fetchRecords()
    {
        $draw = $this->request->getPost('draw');
        $start = $this->request->getPost('start');
        $length = $this->request->getPost('length');
        $searchValue = $this->request->getPost('search')['value'];
        $orderColumn = $this->request->getPost('order')[0]['column'];
        $orderDir = $this->request->getPost('order')[0]['dir'];

        $result = $this->blotterModel->getRecords($start, $length, $searchValue, $orderColumn, $orderDir);

        $counter = $start + 1;
        foreach ($result['data'] as &$row) {
            $row['row_number'] = $counter++;
            $row['incident_date'] = date('M d, Y', strtotime($row['incident_date']));
        }

        return $this->respond([
            'draw' => $draw,
            'recordsTotal' => $result['recordsTotal'],
            'recordsFiltered' => $result['recordsFiltered'],
            'data' => $result['data']
        ]);
    }public function getParties($blotter_id)
{
    $model = new BlotterPartiesModel();
    return $this->respond($model->where('blotter_id', $blotter_id)->findAll());
}

public function saveParty()
{
    $model = new BlotterPartiesModel();
    $model->insert($this->request->getPost());
    return $this->respond(['status' => 'success']);
}

public function deleteParty($id)
{
    $model = new BlotterPartiesModel();
    $model->delete($id);
    return $this->respond(['status' => 'deleted']);
}

// ================= HEARINGS =================

public function getHearings($blotter_id)
{
    $model = new BlotterHearingsModel();
    return $this->respond($model->where('blotter_id', $blotter_id)->findAll());
}

public function saveHearing()
{
    $model = new BlotterHearingsModel();
    $model->insert($this->request->getPost());
    return $this->respond(['status' => 'success']);
}

public function deleteHearing($id)
{
    $model = new BlotterHearingsModel();
    $model->delete($id);
    return $this->respond(['status' => 'deleted']);
}

    public function save()
    {
        if (!$this->validate([
            'case_number' => 'required',
            'incident_date' => 'required',
            'incident_location' => 'required',
            'status' => 'required'
        ])) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        if ($this->blotterModel->insert($this->request->getPost())) {
            return $this->respondCreated(['status' => 'success', 'message' => 'Blotter added successfully']);
        }

        return $this->fail('Failed to save blotter');
    }

    public function edit($id)
    {
        $data = $this->blotterModel->find($id);
        return $data ? $this->respond($data) : $this->failNotFound('Not found');
    }

    public function update($id)
    {
        if ($this->blotterModel->update($id, $this->request->getPost())) {
            return $this->respond(['status' => 'success', 'message' => 'Updated successfully']);
        }
        return $this->fail('Update failed');
    }

    public function delete($id)
    {
        if ($this->blotterModel->delete($id)) {
            return $this->respond(['status' => 'success', 'message' => 'Deleted successfully']);
        }
        return $this->fail('Delete failed');
    }
};

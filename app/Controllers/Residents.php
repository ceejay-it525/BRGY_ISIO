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

        $search      = $request->getPost('search');
        $searchValue = $search['value'] ?? '';

        $viewType = (string) ($request->getPost('view_type') ?? 'all');

        $result = $this->residentsModel->getRecords($start, $length, $searchValue, $viewType);

        $counter = $start + 1;
        foreach ($result['data'] as &$row) {
            $row['row_number'] = $counter++;
        }

        return $this->response->setJSON([
            'draw'            => $draw,
            'recordsTotal'    => $this->residentsModel->countAllResults(false),
            'recordsFiltered' => $result['filtered'],
            'data'            => $result['data'],
            'csrf_hash'       => csrf_hash()
        ]);
    }

    // ==============================
    // RESIDENT STATS FOR STAT CARDS
    // ==============================
    public function residentStats()
    {
        // Force JSON header immediately
        $this->response->setHeader('Content-Type', 'application/json');

        $db = \Config\Database::connect();

        $defaults = [
            'total_residents'  => 0,
            'active_residents' => 0,
            'total_voters'     => 0,
            'male_residents'   => 0,
            'female_residents' => 0,
        ];

        // Check if deleted_at column exists
        $hasDeletedAt = false;
        try {
            $columns = $db->getFieldNames('residents');
            $hasDeletedAt = in_array('deleted_at', $columns);
        } catch (\Throwable $e) {
            // ignore
        }

        $whereClause = $hasDeletedAt ? 'WHERE deleted_at IS NULL' : '';

        try {
            $sql = "
                SELECT
                    COUNT(*)                                              AS total_residents,
                    SUM(CASE WHEN status  = 'Active' THEN 1 ELSE 0 END) AS active_residents,
                    SUM(CASE WHEN is_voter = 1        THEN 1 ELSE 0 END) AS total_voters,
                    SUM(CASE WHEN gender  = 'Male'   THEN 1 ELSE 0 END) AS male_residents,
                    SUM(CASE WHEN gender  = 'Female' THEN 1 ELSE 0 END) AS female_residents
                FROM residents
                {$whereClause}
            ";

            $row = $db->query($sql)->getRowArray();

            if (empty($row)) {
                return $this->response->setJSON($defaults);
            }

            return $this->response->setJSON([
                'total_residents'  => (int) ($row['total_residents']  ?? 0),
                'active_residents' => (int) ($row['active_residents'] ?? 0),
                'total_voters'     => (int) ($row['total_voters']     ?? 0),
                'male_residents'   => (int) ($row['male_residents']   ?? 0),
                'female_residents' => (int) ($row['female_residents'] ?? 0),
            ]);

        } catch (\Throwable $e) {
            log_message('error', '[residentStats] ' . $e->getMessage());
            return $this->response->setJSON($defaults);
        }
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
            'contact_number' => $this->request->getPost('contact_number'),
            'household_id'   => $this->request->getPost('household_id'),
            'address_line1'  => $this->request->getPost('address_line1'),
            'barangay'       => $this->request->getPost('barangay'),
            'status'         => $this->request->getPost('status')
        ];

        if (
            empty($data['first_name']) ||
            empty($data['last_name']) ||
            empty($data['gender']) ||
            empty($data['address_line1'])
        ) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Required fields are missing'
            ]);
        }

        if ($this->residentsModel->insert($data)) {
            return $this->response->setJSON([
                'status'    => 'success',
                'message'   => 'Resident saved successfully',
                'csrf_hash' => csrf_hash()
            ]);
        }

        return $this->response->setJSON([
            'status'    => 'error',
            'message'   => 'Failed to save resident',
            'csrf_hash' => csrf_hash()
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
                'status'    => 'success',
                'data'      => $resident,
                'csrf_hash' => csrf_hash()
            ]);
        }

        return $this->response->setJSON([
            'status'    => 'error',
            'message'   => 'Resident not found',
            'csrf_hash' => csrf_hash()
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
                'status'  => 'error',
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
            'contact_number' => $this->request->getPost('contact_number'),
            'household_id'   => $this->request->getPost('household_id'),
            'address_line1'  => $this->request->getPost('address_line1'),
            'barangay'       => $this->request->getPost('barangay'),
            'status'         => $this->request->getPost('status')
        ];

        if ($this->residentsModel->update($id, $data)) {
            return $this->response->setJSON([
                'status'    => 'success',
                'message'   => 'Resident updated successfully',
                'csrf_hash' => csrf_hash()
            ]);
        }

        return $this->response->setJSON([
            'status'    => 'error',
            'message'   => 'Failed to update resident',
            'csrf_hash' => csrf_hash()
        ]);
    }

    // ==============================
    // DELETE RESIDENT
    // ==============================
    public function delete($id)
    {
        if ($this->residentsModel->delete($id)) {
            return $this->response->setJSON([
                'status'    => 'success',
                'message'   => 'Resident deleted successfully',
                'csrf_hash' => csrf_hash()
            ]);
        }

        return $this->response->setJSON([
            'status'    => 'error',
            'message'   => 'Failed to delete resident',
            'csrf_hash' => csrf_hash()
        ]);
    }

    // ==============================
    // RESIDENT LOOKUP FOR SELECT2 (AJAX)
    // ==============================
    public function lookup()
    {
        $search = $this->request->getGet('q');
        $page   = (int) ($this->request->getGet('page') ?? 1);

        $builder = $this->residentsModel->builder();
        $builder->select('id, first_name, middle_name, last_name, suffix, address_line1, barangay, voter_id, contact_number, photo, birthdate, gender, civil_status, is_blacklisted');

        if (!empty($search)) {
            $builder->groupStart()
                ->like('first_name', $search)
                ->orLike('middle_name', $search)
                ->orLike('last_name', $search)
                ->orLike('voter_id', $search)
                ->orLike('contact_number', $search)
                ->groupEnd();
        }

        $builder->where('deleted_at', null);
        $builder->orderBy('last_name', 'ASC');

        $total = $builder->countAllResults(false);
        $builder->limit(20, ($page - 1) * 20);
        $residents = $builder->get()->getResultArray();

        $results = [];
        foreach ($residents as $resident) {
            $fullName = trim(
                $resident['first_name'] . ' ' .
                ($resident['middle_name'] ? $resident['middle_name'] . ' ' : '') .
                $resident['last_name'] .
                ($resident['suffix'] ? ' ' . $resident['suffix'] : '')
            );

            $age = 0;
            if ($resident['birthdate']) {
                $age = date_diff(date_create($resident['birthdate']), date_create('today'))->y;
            }

            $results[] = [
                'id'             => $resident['id'],
                'text'           => $fullName,
                'first_name'     => $resident['first_name'],
                'middle_name'    => $resident['middle_name'],
                'last_name'      => $resident['last_name'],
                'suffix'         => $resident['suffix'],
                'full_name'      => $fullName,
                'address'        => $resident['address_line1'] . ', ' . $resident['barangay'],
                'contact_number' => $resident['contact_number'],
                'photo'          => $resident['photo'],
                'age'            => $age,
                'gender'         => $resident['gender'],
                'civil_status'   => $resident['civil_status'],
                'is_blacklisted' => $resident['is_blacklisted']
            ];
        }

        return $this->response->setJSON([
            'results'    => $results,
            'pagination' => ['more' => $page * 20 < $total]
        ]);
    }

    // ==============================
    // GET RESIDENT DETAILS BY ID
    // ==============================
    public function getResidentDetails($id)
    {
        $resident = $this->residentsModel->find($id);

        if ($resident) {
            $fullName = trim(
                $resident['first_name'] . ' ' .
                ($resident['middle_name'] ? $resident['middle_name'] . ' ' : '') .
                $resident['last_name'] .
                ($resident['suffix'] ? ' ' . $resident['suffix'] : '')
            );

            $resident['full_name']    = $fullName;
            $resident['full_address'] = $resident['address_line1'] . ', ' . $resident['barangay'];

            return $this->response->setJSON([
                'status'    => 'success',
                'data'      => $resident,
                'csrf_hash' => csrf_hash()
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'Resident not found',
            'csrf_hash' => csrf_hash()
        ]);
    }

    // ==============================
    // EXPORT RESIDENTS TO CSV
    // ==============================
    public function export()
    {
        $viewType = $this->request->getGet('view_type') ?? 'all';

        $builder = $this->residentsModel->builder();
        $builder->select('id, first_name, middle_name, last_name, suffix, birthdate, gender, civil_status, is_voter, voter_id, contact_number, household_id, address_line1, barangay, status');
        $builder->where('deleted_at', null);

        if ($viewType === 'voter-yes') {
            $builder->where('is_voter', 1);
        } elseif ($viewType === 'voter-no') {
            $builder->where('is_voter', 0);
        } elseif (strpos($viewType, 'purok-') === 0) {
            $purokNum   = str_replace('purok-', '', $viewType);
            $purokLabel = ($purokNum === '7a') ? 'Purok 7A' : (($purokNum === '7b') ? 'Purok 7B' : 'Purok ' . $purokNum);
            $builder->like('address_line1', $purokLabel);
        } elseif (strpos($viewType, 'gender-') === 0) {
            $gender = ucfirst(str_replace('gender-', '', $viewType));
            $builder->where('gender', $gender);
        } elseif (!in_array($viewType, ['all', ''])) {
            $builder->where('status', ucfirst($viewType));
        }

        $residents = $builder->get()->getResultArray();

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="residents_export_' . date('Y-m-d') . '.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'First Name', 'Middle Name', 'Last Name', 'Suffix', 'Birthdate', 'Gender', 'Civil Status', 'Voter', 'Voter ID', 'Contact Number', 'Household ID', 'Address', 'Barangay', 'Status']);

        foreach ($residents as $resident) {
            fputcsv($output, [
                $resident['id'],
                $resident['first_name'],
                $resident['middle_name'],
                $resident['last_name'],
                $resident['suffix'],
                $resident['birthdate'],
                $resident['gender'],
                $resident['civil_status'],
                $resident['is_voter'] ? 'Yes' : 'No',
                $resident['voter_id'],
                $resident['contact_number'],
                $resident['household_id'],
                $resident['address_line1'],
                $resident['barangay'],
                $resident['status']
            ]);
        }

        fclose($output);
        exit;
    }

    // ==============================
    // PRINT VIEW FOR RESIDENTS
    // ==============================
    public function printView()
    {
        $viewType = $this->request->getGet('view_type') ?? 'all';

        $builder = $this->residentsModel->builder();
        $builder->select('id, first_name, middle_name, last_name, suffix, birthdate, gender, civil_status, is_voter, voter_id, contact_number, household_id, address_line1, barangay, status');
        $builder->where('deleted_at', null);

        if ($viewType === 'voter-yes') {
            $builder->where('is_voter', 1);
        } elseif ($viewType === 'voter-no') {
            $builder->where('is_voter', 0);
        } elseif (strpos($viewType, 'purok-') === 0) {
            $purokNum   = str_replace('purok-', '', $viewType);
            $purokLabel = ($purokNum === '7a') ? 'Purok 7A' : (($purokNum === '7b') ? 'Purok 7B' : 'Purok ' . $purokNum);
            $builder->like('address_line1', $purokLabel);
        } elseif (strpos($viewType, 'gender-') === 0) {
            $gender = ucfirst(str_replace('gender-', '', $viewType));
            $builder->where('gender', $gender);
        } elseif (!in_array($viewType, ['all', ''])) {
            $builder->where('status', ucfirst($viewType));
        }

        $residents = $builder->get()->getResultArray();

        return view('residents/print_view', [
            'residents' => $residents,
            'viewType'  => $viewType
        ]);
    }
}
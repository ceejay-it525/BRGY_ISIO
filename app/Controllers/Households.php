<?php

namespace App\Controllers;

use App\Models\HouseholdsModel;

class Households extends BaseController
{
    protected $householdsModel;

  
    private const BARANGAY          = 'Isio';
    private const CITY_MUNICIPALITY = 'Cauayan';
    private const PROVINCE          = 'Negros Occidental';
    private const ZIP_CODE          = '6112';

    public function __construct()
    {
        $this->householdsModel = new HouseholdsModel();
    }

    
    public function index()
    {
        return view('households/index');
    }

   
    public function fetchRecords()
    {
        $request = service('request');

        $draw        = (int) $request->getPost('draw');
        $start       = (int) $request->getPost('start');
        $length      = (int) $request->getPost('length');
        $search      = $request->getPost('search');
        $searchValue = $search['value'] ?? '';

        $result  = $this->householdsModel->getRecords($start, $length, $searchValue);
        $counter = $start + 1;

        foreach ($result['data'] as &$row) {
            $row['row_number'] = $counter++;
        }

        $totalAll = $this->householdsModel
            ->where('deleted_at IS NULL')
            ->countAllResults(false);

        return $this->response->setJSON([
            'draw'            => $draw,
            'recordsTotal'    => $totalAll,
            'recordsFiltered' => $result['filtered'],
            'data'            => $result['data'],
            'csrf_hash'       => csrf_hash()
        ]);
    }

    
    public function fetchStats()
    {
       
        header('Content-Type: application/json');

        try {
            $stats = $this->householdsModel->getDashboardStats();

            echo json_encode([
                'status'    => 'success',
                'data'      => $stats,
                'csrf_hash' => csrf_hash()
            ]);
            exit;
        } catch (\Throwable $e) {
            log_message('error', '[Households::fetchStats] ' . $e->getMessage());
            echo json_encode([
                'status'  => 'error',
                'message' => $e->getMessage(),
                'data'    => [
                    'total_households'    => 0,
                    'active_households'   => 0,
                    'new_households'      => 0,
                    'assistance_priority' => 0
                ]
            ]);
            exit;
        }
    }

  
    public function fetchAssistancePriority()
    {
        // Force JSON — prevents any HTML being returned on error
        header('Content-Type: application/json');

        try {
            $list = $this->householdsModel->getAssistancePriorityList();

            echo json_encode([
                'status'    => 'success',
                'data'      => $list,
                'count'     => count($list),
                'csrf_hash' => csrf_hash()
            ]);
            exit;
        } catch (\Throwable $e) {
            log_message('error', '[Households::fetchAssistancePriority] ' . $e->getMessage());
            echo json_encode([
                'status'  => 'error',
                'message' => 'Failed to load assistance list: ' . $e->getMessage(),
                'data'    => []
            ]);
            exit;
        }
    }

  
    public function save()
    {
        $headName    = trim($this->request->getPost('head_name') ?? '');
        $addressLine = trim($this->request->getPost('address_line1') ?? '');
        $purok       = trim($this->request->getPost('purok') ?? '');
        $members     = (int) ($this->request->getPost('total_members') ?: 1);
        $status      = $this->request->getPost('status') ?: 'Active';

        if (empty($headName) || empty($addressLine) || empty($purok)) {
            return $this->response->setJSON([
                'status'    => 'error',
                'message'   => 'Head of Household, Address, and Purok are required.',
                'csrf_hash' => csrf_hash()
            ]);
        }

        $validPuroks = ['1', '2', '3', '4', '5', '6', '7A', '7B'];
        if (!in_array($purok, $validPuroks)) {
            return $this->response->setJSON([
                'status'    => 'error',
                'message'   => 'Invalid Purok selection.',
                'csrf_hash' => csrf_hash()
            ]);
        }

        if ($members < 1 || $members > 99) {
            return $this->response->setJSON([
                'status'    => 'error',
                'message'   => 'Total members must be between 1 and 99.',
                'csrf_hash' => csrf_hash()
            ]);
        }

        if ($this->householdsModel->isDuplicateHead($headName)) {
            return $this->response->setJSON([
                'status'    => 'error',
                'message'   => 'A household with this head name already exists.',
                'csrf_hash' => csrf_hash()
            ]);
        }

        $data = [
            'head_name'         => $headName,
            'address_line1'     => $addressLine,
            'purok'             => $purok,
            'barangay'          => self::BARANGAY,
            'city_municipality' => self::CITY_MUNICIPALITY,
            'province'          => self::PROVINCE,
            'zip_code'          => self::ZIP_CODE,
            'total_members'     => $members,
            'status'            => $status,
        ];

        if ($this->householdsModel->insert($data)) {
            return $this->response->setJSON([
                'status'    => 'success',
                'message'   => 'Household added successfully.',
                'csrf_hash' => csrf_hash()
            ]);
        }

        return $this->response->setJSON([
            'status'    => 'error',
            'message'   => 'Failed to save household. Please try again.',
            'csrf_hash' => csrf_hash()
        ]);
    }

  
    public function get($id)
    {
        $household = $this->householdsModel->find($id);

        if ($household) {
            return $this->response->setJSON([
                'status'    => 'success',
                'data'      => $household,
                'csrf_hash' => csrf_hash()
            ]);
        }

        return $this->response->setJSON([
            'status'    => 'error',
            'message'   => 'Household not found.',
            'csrf_hash' => csrf_hash()
        ]);
    }

    public function update()
    {
        $id          = (int) ($this->request->getPost('id') ?: 0);
        $headName    = trim($this->request->getPost('head_name') ?? '');
        $addressLine = trim($this->request->getPost('address_line1') ?? '');
        $purok       = trim($this->request->getPost('purok') ?? '');
        $members     = (int) ($this->request->getPost('total_members') ?: 1);
        $status      = $this->request->getPost('status') ?: 'Active';

        if (empty($id)) {
            return $this->response->setJSON([
                'status'    => 'error',
                'message'   => 'Invalid household ID.',
                'csrf_hash' => csrf_hash()
            ]);
        }

        if (empty($headName) || empty($addressLine) || empty($purok)) {
            return $this->response->setJSON([
                'status'    => 'error',
                'message'   => 'Head of Household, Address, and Purok are required.',
                'csrf_hash' => csrf_hash()
            ]);
        }

        $validPuroks = ['1', '2', '3', '4', '5', '6', '7A', '7B'];
        if (!in_array($purok, $validPuroks)) {
            return $this->response->setJSON([
                'status'    => 'error',
                'message'   => 'Invalid Purok selection.',
                'csrf_hash' => csrf_hash()
            ]);
        }

        if ($members < 1 || $members > 99) {
            return $this->response->setJSON([
                'status'    => 'error',
                'message'   => 'Total members must be between 1 and 99.',
                'csrf_hash' => csrf_hash()
            ]);
        }

        if ($this->householdsModel->isDuplicateHead($headName, $id)) {
            return $this->response->setJSON([
                'status'    => 'error',
                'message'   => 'A household with this head name already exists.',
                'csrf_hash' => csrf_hash()
            ]);
        }

        $data = [
            'head_name'         => $headName,
            'address_line1'     => $addressLine,
            'purok'             => $purok,
            'barangay'          => self::BARANGAY,
            'city_municipality' => self::CITY_MUNICIPALITY,
            'province'          => self::PROVINCE,
            'zip_code'          => self::ZIP_CODE,
            'total_members'     => $members,
            'status'            => $status,
        ];

        if ($this->householdsModel->update($id, $data)) {
            return $this->response->setJSON([
                'status'    => 'success',
                'message'   => 'Household updated successfully.',
                'csrf_hash' => csrf_hash()
            ]);
        }

        return $this->response->setJSON([
            'status'    => 'error',
            'message'   => 'Failed to update household. Please try again.',
            'csrf_hash' => csrf_hash()
        ]);
    }


    public function delete($id)
    {
        $id = (int) $id;

        if (empty($id)) {
            return $this->response->setJSON([
                'status'    => 'error',
                'message'   => 'Invalid household ID.',
                'csrf_hash' => csrf_hash()
            ]);
        }

        if ($this->householdsModel->delete($id)) {
            return $this->response->setJSON([
                'status'    => 'success',
                'message'   => 'Household deleted successfully.',
                'csrf_hash' => csrf_hash()
            ]);
        }

        return $this->response->setJSON([
            'status'    => 'error',
            'message'   => 'Failed to delete household.',
            'csrf_hash' => csrf_hash()
        ]);
    }
}

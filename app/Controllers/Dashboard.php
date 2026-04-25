<?php

namespace App\Controllers;

use App\Models\ResidentsModel;

class Dashboard extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('/login');
        }

        $residentsModel = new ResidentsModel();

        $data = [
            'totalResidents'  => $residentsModel->countAll(),
            'activeOfficials' => $this->db->table('barangay_officials')->where('status', 'active')->countAllResults(),
            'pendingBlotter'  => $this->db->table('blotter')->where('status', 'pending')->countAllResults(),
            'activePermits'   => $this->db->table('permits')->where('status', 'active')->countAllResults(),
        ];

        return view('dashboard', $data);
    }

    public function stats()
    {
        $residentsModel = new ResidentsModel();

        return $this->response->setJSON([
            'residents'       => $residentsModel->countAll(),
            'officials'       => $this->db->table('barangay_officials')->where('status', 'active')->countAllResults(),
            'pendingBlotter'  => $this->db->table('blotter')->where('status', 'pending')->countAllResults(),
            'businessPermits' => $this->db->table('permits')->where('status', 'active')->countAllResults(),
        ]);
    }
}
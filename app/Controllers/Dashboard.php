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

        // Get statistics
        $data = [
            'totalResidents'  => $residentsModel->countAll(),
            'activeOfficials' => $this->db->table('barangay_officials')->where('status', 'Active')->countAllResults(),
            'totalBlotter'    => $this->db->table('blotter')->countAllResults(),
            'totalPermits'    => $this->db->table('permits')->where('status', 'Active')->countAllResults(),
        ];

        // Get recent activities from logs
        $data['recentActivities'] = $this->db->table('tbl_logs')
            ->orderBy('LOGID', 'DESC')
            ->limit(10)
            ->get()
            ->getResultArray();

        // Format activities for dashboard
        $formattedActivities = [];
        foreach ($data['recentActivities'] as $log) {
            $badge = 'info';
            $type = 'INFO';
            
            if (strpos($log['ACTION'], 'Login') !== false) {
                $badge = 'success';
                $type = 'LOGIN';
            } elseif (strpos($log['ACTION'], 'Logout') !== false) {
                $badge = 'warning';
                $type = 'LOGOUT';
            } elseif (strpos($log['ACTION'], 'added') !== false) {
                $badge = 'primary';
                $type = 'ADD';
            } elseif (strpos($log['ACTION'], 'apdated') !== false || strpos($log['ACTION'], 'updated') !== false) {
                $badge = 'info';
                $type = 'UPDATE';
            } elseif (strpos($log['ACTION'], 'Delete') !== false) {
                $badge = 'danger';
                $type = 'DELETE';
            }

            $formattedActivities[] = [
                'activity'   => $log['ACTION'],
                'type'       => $type,
                'badge'      => $badge,
                'created_at' => $log['DATELOG'] . ' ' . $log['TIMELOG']
            ];
        }
        $data['recentActivities'] = $formattedActivities;

        // Get monthly stats for chart (last 6 months of residents)
        $currentYear = date('Y');
        $monthlyStats = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = date('m', strtotime("-$i months"));
            $label = date('M Y', strtotime("-$i months"));
            
            $count = $this->db->table('residents')
                ->where('MONTH(created_at)', $month)
                ->where('YEAR(created_at)', $currentYear)
                ->countAllResults();
            
            $monthlyStats[] = [
                'label' => $label,
                'count' => $count
            ];
        }
        $data['monthlyStats'] = $monthlyStats;

        // Get upcoming events
        $data['upcomingEvents'] = $this->db->table('barangay_events')
            ->where('event_date >=', date('Y-m-d'))
            ->orderBy('event_date', 'ASC')
            ->limit(4)
            ->get()
            ->getResultArray();

        // Format events for dashboard
        $formattedEvents = [];
        foreach ($data['upcomingEvents'] as $event) {
            $formattedEvents[] = [
                'title'  => $event['title'],
                'date'   => $event['event_date'],
                'color'  => $event['color'] ?? 'primary',
                'icon'   => $event['icon'] ?? 'fa-calendar'
            ];
        }
        $data['upcomingEvents'] = $formattedEvents;

        // Get alerts (pending blotter cases, expiring permits, etc.)
        $alerts = [];
        
        // Check for ongoing blotter cases
        $ongoingBlotter = $this->db->table('blotter')
            ->where('status', 'Ongoing')
            ->countAllResults();
        
        if ($ongoingBlotter > 0) {
            $alerts[] = [
                'type'    => 'warning',
                'icon'    => 'fas fa-exclamation-triangle',
                'message' => "$ongoingBlotter ongoing blotter case(s) require attention.",
                'label'   => 'View Blotter',
                'link'    => base_url('blotter')
            ];
        }

        // Check for pending clearances
        $pendingClearances = $this->db->table('clearances')
            ->where('status', 'Pending')
            ->countAllResults();
        
        if ($pendingClearances > 0) {
            $alerts[] = [
                'type'    => 'info',
                'icon'    => 'fas fa-clock',
                'message' => "$pendingClearances clearance(s) pending approval.",
                'label'   => 'View Clearances',
                'link'    => base_url('clearances')
            ];
        }

        // Check for expiring permits (within 30 days)
        $expiringPermits = $this->db->table('permits')
            ->where('status', 'Active')
            ->where('expiry_date <=', date('Y-m-d', strtotime('+30 days')))
            ->where('expiry_date >=', date('Y-m-d'))
            ->countAllResults();
        
        if ($expiringPermits > 0) {
            $alerts[] = [
                'type'    => 'danger',
                'icon'    => 'fas fa-store',
                'message' => "$expiringPermits business permit(s) expiring soon.",
                'label'   => 'View Permits',
                'link'    => base_url('permits')
            ];
        }

        $data['alerts'] = $alerts;

        return view('dashboard', $data);
    }

    public function stats()
    {
        $residentsModel = new ResidentsModel();

        return $this->response->setJSON([
            'total_residents'   => $residentsModel->countAll(),
            'total_households'  => $this->db->table('households')->countAllResults(),
            'total_blotter'     => $this->db->table('blotter')->countAllResults(),
            'total_clearances'  => $this->db->table('clearances')->countAllResults(),
            'total_officials'   => $this->db->table('barangay_officials')->where('status', 'Active')->countAllResults(),
            'total_permits'     => $this->db->table('permits')->countAllResults(),
            'total_indigents'   => $this->db->table('indigents')->countAllResults(),
        ]);
    }
}

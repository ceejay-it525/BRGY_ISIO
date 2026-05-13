<?php

namespace App\Models;

use CodeIgniter\Model;

class ActivityLogModel extends Model
{
    protected $table      = 'activity_logs';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'permit_id',
        'action',
        'details',
        'user_id',
        'ip_address',
        'device_used'
    ];

    public function getActivityByPermitId($permitId)
    {
        return $this->where('permit_id', $permitId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    public function logActivity($permitId, $action, $details = null, $userId = null, $userName = null)
    {
        $data = [
            'permit_id' => $permitId,
            'action' => $action,
            'details' => $details,
            'user_id' => $userId ?? session()->get('user_id'),
            'ip_address' => $this->request->getIPAddress(),
            'device_used' => $this->getUserAgent()
        ];
        return $this->insert($data);
    }

    public function getActivityByIndigentId($indigentId)
    {
        return $this->where('permit_id', $indigentId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    public function getRecentActivity()
    {
        return $this->orderBy('created_at', 'DESC')
                    ->limit(10)
                    ->findAll();
    }

    private function getUserAgent()
    {
        $agent = $this->request->getUserAgent();
        if ($agent) {
            return $agent->getBrowser() . ' on ' . $agent->getPlatform();
        }
        return 'Unknown';
    }
}
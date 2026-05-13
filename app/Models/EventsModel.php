<?php

namespace App\Models;

use CodeIgniter\Model;

class EventsModel extends Model
{
    protected $table      = 'barangay_events';
    protected $primaryKey = 'id';
    protected $allowedFields = ['title', 'description', 'event_date', 'color', 'icon'];
    protected $useTimestamps = true;

    protected $validationRules = [
        'title'      => 'required|max_length[255]',
        'event_date' => 'required|valid_date',
        'color'      => 'required',
        'icon'       => 'required'
    ];

    public function getUpcomingEvents(int $limit = 8): array
    {
        return $this->where('event_date >=', date('Y-m-d'))
                    ->orderBy('event_date', 'ASC')
                    ->limit($limit)
                    ->findAll();
    }
}

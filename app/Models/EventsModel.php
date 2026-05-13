<?php

namespace App\Models;

use CodeIgniter\Model;

class EventsModel extends Model
{
    protected $table            = 'barangay_events';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'title', 'description', 'venue',
        'event_date', 'event_time', 'end_date', 'end_time',
        'budget', 'status', 'participants',
        'notes', 'poster_image', 'is_public',
        'qr_code', 'sms_sent', 'color', 'icon',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules = [
        'title'       => 'required|max_length[255]',
        'event_date'  => 'required|valid_date',
        'venue'       => 'permit_empty|max_length[255]',
        'budget'      => 'permit_empty|decimal',
        'status'      => 'permit_empty|in_list[Draft,Scheduled,Ongoing,Completed,Cancelled]',
        'description' => 'permit_empty|max_length[1000]',
    ];

    protected $validationMessages = [
        'title'      => ['required' => 'Event title is required.', 'max_length' => 'Max 255 characters.'],
        'event_date' => ['required' => 'Event date is required.', 'valid_date' => 'Invalid date.'],
        'venue'      => ['required' => 'Venue is required.'],
    ];

    // -------------------------------------------------------------------------
    // SERVER-SIDE DATATABLE
    // -------------------------------------------------------------------------
    public function getRecords(int $start, int $length, string $search, string $viewType = 'all'): array
    {
        if ($search !== '') {
            $this->groupStart()
                 ->like('title',         $search)
                 ->orLike('description', $search)
                 ->orLike('venue',       $search)
                 ->orLike('status',      $search)
                 ->groupEnd();
        }

        $tabMap = [
            'draft'     => 'Draft',
            'scheduled' => 'Scheduled',
            'ongoing'   => 'Ongoing',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
        ];
        if (isset($tabMap[$viewType])) {
            $this->where('status', $tabMap[$viewType]);
        }

        $filteredCount = $this->countAllResults(false);
        $totalCount    = $this->countAll();

        $rows  = $this->orderBy('event_date', 'DESC')->findAll($length, $start);
        $today = date('Y-m-d');

        foreach ($rows as &$row) {
            $d = $row['event_date'] ?? null;
            $status = $row['status'] ?? 'Draft';
            $row['status'] = $status;
            $row['days_remaining'] = ($d && !in_array($status, ['Completed','Cancelled']))
                ? (int) floor((strtotime($d) - strtotime($today)) / 86400)
                : null;
        }
        unset($row);

        return ['data' => $rows, 'filtered' => $filteredCount, 'recordsTotal' => $totalCount];
    }

    // -------------------------------------------------------------------------
    // STATS
    // -------------------------------------------------------------------------
    public function getStats(): array
    {
        $total = $this->countAll();

        // Check if status column exists by trying to query it
        try {
            $draft     = $this->where('status', 'Draft')->countAllResults();
            $scheduled = $this->where('status', 'Scheduled')->countAllResults();
            $ongoing   = $this->where('status', 'Ongoing')->countAllResults();
            $completed = $this->where('status', 'Completed')->countAllResults();
            $cancelled = $this->where('status', 'Cancelled')->countAllResults();
        } catch (\Exception $e) {
            // If status column doesn't exist, return zeros
            $draft = $scheduled = $ongoing = $completed = $cancelled = 0;
        }

        // Budget sum — handle missing column
        $budget = 0;
        try {
            $db        = \Config\Database::connect();
            $budgetRow = $db->table($this->table)
                            ->selectSum('budget')
                            ->get()->getRowArray();
            $budget    = (float) ($budgetRow['budget'] ?? 0);
        } catch (\Exception $e) {
            $budget = 0;
        }

        return compact('total','draft','scheduled','ongoing','completed','cancelled','budget');
    }

    // -------------------------------------------------------------------------
    // WORKFLOW
    // -------------------------------------------------------------------------
    public function markScheduled(int $id): bool { return (bool) $this->update($id, ['status' => 'Scheduled']); }
    public function markOngoing(int $id): bool   { return (bool) $this->update($id, ['status' => 'Ongoing']); }
    public function markCompleted(int $id): bool { return (bool) $this->update($id, ['status' => 'Completed']); }

    public function cancelEvent(int $id, string $reason): bool
    {
        return (bool) $this->update($id, ['status' => 'Cancelled', 'notes' => $reason]);
    }

    // -------------------------------------------------------------------------
    // UPCOMING / PUBLIC
    // -------------------------------------------------------------------------
    public function getUpcomingEvents(int $limit = 8): array
    {
        return $this->where('event_date >=', date('Y-m-d'))
                    ->where('status !=', 'Cancelled')
                    ->orderBy('event_date', 'ASC')
                    ->limit($limit)->findAll();
    }

    public function getPublicEvents(int $limit = 20): array
    {
        return $this->where('is_public', 1)
                    ->where('event_date >=', date('Y-m-d'))
                    ->where('status', 'Scheduled')
                    ->orderBy('event_date', 'ASC')
                    ->limit($limit)->findAll();
    }
}

<?php

namespace App\Controllers;

use App\Models\EventsModel;
use App\Models\ActivityLogModel;
use CodeIgniter\API\ResponseTrait;

class Events extends BaseController
{
    use ResponseTrait;

    protected EventsModel $eventsModel;
    protected ?ActivityLogModel $activityLogModel;

    public function __construct()
    {
        $this->eventsModel = new EventsModel();

        // Guard: only load if the class actually exists
        $this->activityLogModel = class_exists('App\\Models\\ActivityLogModel')
            ? new ActivityLogModel()
            : null;
    }

    // =========================================================================
    // PAGE ROUTES
    // =========================================================================

    public function index()      { return view('events/index'); }
    public function draft()      { return $this->index(); }
    public function scheduled()  { return $this->index(); }
    public function ongoing()    { return $this->index(); }
    public function completed()  { return $this->index(); }
    public function cancelled()  { return $this->index(); }

    // =========================================================================
    // DATATABLE — server-side
    // =========================================================================

    public function fetchRecords()
    {
        try {
            $req      = service('request');
            $draw     = (int) ($req->getPost('draw')   ?? 1);
            $start    = (int) ($req->getPost('start')  ?? 0);
            $length   = (int) ($req->getPost('length') ?? 25);
            $search   = (string) ($req->getPost('search')['value'] ?? '');

            // view_type arrives as the last URI segment, e.g. "draft", "all"
            $viewType = trim((string) ($req->getPost('view_type') ?? 'all'));

            $result  = $this->eventsModel->getRecords($start, $length, $search, $viewType);
            $counter = $start + 1;

            foreach ($result['data'] as &$row) {
                $row['row_number'] = $counter++;
            }
            unset($row);

            return $this->response->setJSON([
                'draw'            => $draw,
                'recordsTotal'    => $result['recordsTotal'],
                'recordsFiltered' => $result['filtered'],
                'data'            => $result['data'],
                'csrf_hash'       => csrf_hash(),
            ]);

        } catch (\Throwable $e) {
            log_message('error', '[Events::fetchRecords] ' . $e->getMessage());
            return $this->response->setJSON([
                'draw' => 0, 'recordsTotal' => 0, 'recordsFiltered' => 0,
                'data' => [], 'error' => $e->getMessage(),
            ]);
        }
    }

    // =========================================================================
    // STATS
    // =========================================================================

    public function stats()
    {
        try {
            return $this->response->setJSON([
                'status'    => 'success',
                'data'      => $this->eventsModel->getStats(),
                'csrf_hash' => csrf_hash(),
            ]);
        } catch (\Throwable $e) {
            log_message('error', '[Events::stats] ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    // =========================================================================
    // SAVE (create)
    // =========================================================================

    public function save()
    {
        try {
            $data = $this->request->getPost();
            unset($data['csrf_test_name']);

            if (empty($data['title'])) {
                return $this->response->setJSON([
                    'status'    => 'error',
                    'message'   => 'Event title is required.',
                    'csrf_hash' => csrf_hash(),
                ]);
            }

            if (empty($data['status'])) {
                $data['status'] = 'Draft';
            }

            // Generate QR code for event
            $data['qr_code'] = 'EVT-' . date('Y') . '-' . strtoupper(substr(uniqid(), -6));

            // Normalise checkbox — not posted when unchecked
            $data['is_public'] = isset($data['is_public']) ? 1 : 0;

            if (!$this->eventsModel->insert($data)) {
                $errors = implode(', ', $this->eventsModel->errors());
                return $this->response->setJSON([
                    'status'    => 'error',
                    'message'   => 'Save failed: ' . $errors,
                    'csrf_hash' => csrf_hash(),
                ]);
            }

            $this->logActivity('create', 'Created event', $this->eventsModel->getInsertID());

            return $this->response->setJSON([
                'status'    => 'success',
                'message'   => 'Event saved successfully.',
                'csrf_hash' => csrf_hash(),
            ]);

        } catch (\Throwable $e) {
            log_message('error', '[Events::save] ' . $e->getMessage());
            return $this->response->setJSON([
                'status'    => 'error',
                'message'   => $e->getMessage(),
                'csrf_hash' => csrf_hash(),
            ]);
        }
    }

    // =========================================================================
    // GET / EDIT (return JSON for modal population)
    // =========================================================================

    public function get($id)
    {
        try {
            $event = $this->eventsModel->find((int) $id);
            if (!$event) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Event not found.']);
            }
            return $this->response->setJSON(['status' => 'success', 'data' => $event]);
        } catch (\Throwable $e) {
            log_message('error', '[Events::get] ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function edit($id) { return $this->get($id); }

    // =========================================================================
    // VIEW (detail + notes)
    // =========================================================================

    public function view($id)
    {
        try {
            $event = $this->eventsModel->find((int) $id);
            if (!$event) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Event not found.']);
            }

            return $this->response->setJSON([
                'status'    => 'success',
                'data'      => $event,
                'csrf_hash' => csrf_hash(),
            ]);
        } catch (\Throwable $e) {
            log_message('error', '[Events::view] ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    // =========================================================================
    // UPDATE
    // =========================================================================

    public function update()
    {
        try {
            $id   = (int) $this->request->getPost('id');
            $data = $this->request->getPost();
            unset($data['csrf_test_name'], $data['id']);

            // Normalise checkbox
            $data['is_public'] = isset($data['is_public']) ? 1 : 0;

            if (!$id) {
                return $this->response->setJSON([
                    'status'    => 'error',
                    'message'   => 'Invalid event ID.',
                    'csrf_hash' => csrf_hash(),
                ]);
            }

            if (!$this->eventsModel->update($id, $data)) {
                $errors = implode(', ', $this->eventsModel->errors());
                return $this->response->setJSON([
                    'status'    => 'error',
                    'message'   => 'Update failed: ' . $errors,
                    'csrf_hash' => csrf_hash(),
                ]);
            }

            $this->logActivity('update', 'Updated event', $id);

            return $this->response->setJSON([
                'status'    => 'success',
                'message'   => 'Event updated successfully.',
                'csrf_hash' => csrf_hash(),
            ]);
        } catch (\Throwable $e) {
            log_message('error', '[Events::update] ' . $e->getMessage());
            return $this->response->setJSON([
                'status'    => 'error',
                'message'   => $e->getMessage(),
                'csrf_hash' => csrf_hash(),
            ]);
        }
    }

    // =========================================================================
    // DELETE
    // =========================================================================

    public function delete($id)
    {
        try {
            if (!$this->eventsModel->delete((int) $id)) {
                return $this->response->setJSON([
                    'status'    => 'error',
                    'message'   => 'Delete failed.',
                    'csrf_hash' => csrf_hash(),
                ]);
            }

            $this->logActivity('delete', 'Deleted event', (int) $id);

            return $this->response->setJSON([
                'status'    => 'success',
                'message'   => 'Event deleted successfully.',
                'csrf_hash' => csrf_hash(),
            ]);
        } catch (\Throwable $e) {
            log_message('error', '[Events::delete] ' . $e->getMessage());
            return $this->response->setJSON([
                'status'    => 'error',
                'message'   => $e->getMessage(),
                'csrf_hash' => csrf_hash(),
            ]);
        }
    }

    // =========================================================================
    // WORKFLOW: MARK SCHEDULED
    // =========================================================================

    public function markScheduled($id)
    {
        try {
            if (!$this->eventsModel->markScheduled((int) $id)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to mark as scheduled.', 'csrf_hash' => csrf_hash()]);
            }
            $this->logActivity('workflow', 'Marked as Scheduled', (int) $id);
            return $this->response->setJSON(['status' => 'success', 'message' => 'Event marked as scheduled.', 'csrf_hash' => csrf_hash()]);
        } catch (\Throwable $e) {
            log_message('error', '[Events::markScheduled] ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage(), 'csrf_hash' => csrf_hash()]);
        }
    }

    // =========================================================================
    // WORKFLOW: MARK ONGOING
    // =========================================================================

    public function markOngoing($id)
    {
        try {
            if (!$this->eventsModel->markOngoing((int) $id)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to mark as ongoing.', 'csrf_hash' => csrf_hash()]);
            }
            $this->logActivity('workflow', 'Marked as Ongoing', (int) $id);
            return $this->response->setJSON(['status' => 'success', 'message' => 'Event marked as ongoing.', 'csrf_hash' => csrf_hash()]);
        } catch (\Throwable $e) {
            log_message('error', '[Events::markOngoing] ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage(), 'csrf_hash' => csrf_hash()]);
        }
    }

    // =========================================================================
    // WORKFLOW: MARK COMPLETED
    // =========================================================================

    public function markCompleted($id)
    {
        try {
            if (!$this->eventsModel->markCompleted((int) $id)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to mark as completed.', 'csrf_hash' => csrf_hash()]);
            }
            $this->logActivity('workflow', 'Marked as Completed', (int) $id);
            return $this->response->setJSON(['status' => 'success', 'message' => 'Event marked as completed.', 'csrf_hash' => csrf_hash()]);
        } catch (\Throwable $e) {
            log_message('error', '[Events::markCompleted] ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage(), 'csrf_hash' => csrf_hash()]);
        }
    }

    // =========================================================================
    // WORKFLOW: CANCEL EVENT
    // =========================================================================

    public function cancelEvent($id)
    {
        try {
            $reason = trim((string) ($this->request->getPost('reason') ?? ''));
            if ($reason === '') {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Cancellation reason is required.', 'csrf_hash' => csrf_hash()]);
            }

            if (!$this->eventsModel->cancelEvent((int) $id, $reason)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to cancel event.', 'csrf_hash' => csrf_hash()]);
            }

            $this->logActivity('workflow', 'Cancelled: ' . $reason, (int) $id);

            return $this->response->setJSON(['status' => 'success', 'message' => 'Event cancelled successfully.', 'csrf_hash' => csrf_hash()]);
        } catch (\Throwable $e) {
            log_message('error', '[Events::cancelEvent] ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage(), 'csrf_hash' => csrf_hash()]);
        }
    }

    // =========================================================================
    // PRINT PREVIEW
    // =========================================================================

    public function getPrintPreview($id)
    {
        try {
            $event = $this->eventsModel->find((int) $id);
            if (!$event) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Event not found.']);
            }

            $eventDate   = !empty($event['event_date']) ? date('F d, Y', strtotime($event['event_date'])) : 'N/A';
            $eventTime   = !empty($event['event_time']) ? date('h:i A', strtotime($event['event_time'])) : 'N/A';
            $budget      = '&#8369;' . number_format((float) ($event['budget'] ?? 0), 2);
            $participants = $event['participants'] ?? 'N/A';
            $title       = htmlspecialchars($event['title'] ?? '', ENT_QUOTES);
            $description = htmlspecialchars($event['description'] ?? '', ENT_QUOTES);
            $venue       = htmlspecialchars($event['venue'] ?? '', ENT_QUOTES);
            $status      = htmlspecialchars($event['status'] ?? '', ENT_QUOTES);
            $notes       = htmlspecialchars($event['notes'] ?? '', ENT_QUOTES);

            $html = <<<HTML
<style>
  body { font-family: Arial, sans-serif; padding: 20px; }
  .event-header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
  .event-header h1 { margin: 0; font-size: 24px; }
  .event-body { font-size: 14px; line-height: 1.6; }
  .event-body p { margin: 5px 0; }
</style>
<div class="event-header">
  <h1>EVENT SUMMARY REPORT</h1>
</div>
<div class="event-body">
  <p><strong>Event Title:</strong> {$title}</p>
  <p><strong>Description:</strong> {$description}</p>
  <p><strong>Venue:</strong> {$venue}</p>
  <p><strong>Event Date:</strong> {$eventDate}</p>
  <p><strong>Event Time:</strong> {$eventTime}</p>
  <p><strong>Budget:</strong> {$budget}</p>
  <p><strong>Status:</strong> {$status}</p>
  <p><strong>Participants:</strong> {$participants}</p>
  <p><strong>Notes:</strong> {$notes}</p>
  <br>
  <p style="margin-top:16px;">
    This report certifies that the above event was conducted in Barangay Isio
    under the supervision of the Barangay Captain.
  </p>
  <br>
  <div style="margin-top: 30px; text-align: center;">
    <p>__________________________</p>
    <p>HON. JEROME AGUSTIN</p>
    <p>Punong Barangay</p>
  </div>
</div>
HTML;

            return $this->response->setJSON([
                'status'    => 'success',
                'html'      => $html,
                'csrf_hash' => csrf_hash(),
            ]);
        } catch (\Throwable $e) {
            log_message('error', '[Events::getPrintPreview] ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    // =========================================================================
    // HELPERS
    // =========================================================================

    private function logActivity(string $action, string $description, int $recordId): void
    {
        try {
            if ($this->activityLogModel && method_exists($this->activityLogModel, 'log')) {
                $this->activityLogModel->log('events', $action, $description, $recordId);
            }
        } catch (\Throwable $e) {
            log_message('warning', '[ActivityLog] ' . $e->getMessage());
        }
    }
}

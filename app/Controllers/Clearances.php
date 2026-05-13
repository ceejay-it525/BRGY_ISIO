<?php

namespace App\Controllers;

use App\Models\ClearancesModel;
use App\Models\ResidentsModel;
use App\Models\ActivityLogModel;
use CodeIgniter\API\ResponseTrait;

class Clearances extends BaseController
{
    use ResponseTrait;

    protected ClearancesModel $clearancesModel;
    protected ResidentsModel $residentsModel;
    protected ActivityLogModel $activityLogModel;

    public function __construct()
    {
        $this->clearancesModel = new ClearancesModel();
        $this->residentsModel  = new ResidentsModel();
        $this->activityLogModel = new ActivityLogModel();
    }

    // =========================================================================
    // PAGE ROUTES
    // =========================================================================

    public function index()
    {
        $residents = $this->residentsModel
            ->where('deleted_at IS NULL', null, false)
            ->orderBy('last_name', 'ASC')
            ->findAll();
        return view('clearances/index', ['residents' => $residents]);
    }
    public function pending()  { return $this->index(); }
    public function approved() { return $this->index(); }
    public function released() { return $this->index(); }
    public function rejected() { return $this->index(); }
    public function expired()  { return $this->index(); }

    // =========================================================================
    // DATATABLE — server-side
    // =========================================================================

    public function fetchRecords()
    {
        try {
            $req      = service('request');
            $draw     = (int)    ($req->getPost('draw')             ?? 1);
            $start    = (int)    ($req->getPost('start')            ?? 0);
            $length   = (int)    ($req->getPost('length')           ?? 25);
            $search   = (string) ($req->getPost('search')['value']  ?? '');
            $viewType = (string) ($req->getPost('view_type')        ?? 'all');

            $result = $this->clearancesModel->getRecords($start, $length, $search, $viewType);

            return $this->response->setJSON([
                'draw'            => $draw,
                'recordsTotal'    => $result['recordsTotal'],
                'recordsFiltered' => $result['filtered'],
                'data'            => $result['data'],
                'csrf_hash'       => csrf_hash(),
            ]);

        } catch (\Throwable $e) {
            log_message('error', '[Clearances::fetchRecords] ' . $e->getMessage());
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
                'data'      => $this->clearancesModel->getStats(),
                'csrf_hash' => csrf_hash(),
            ]);
        } catch (\Throwable $e) {
            log_message('error', '[Clearances::stats] ' . $e->getMessage());
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

            if (empty($data['status'])) {
                $data['status'] = 'Pending';
            }

            $data['request_date']   = date('Y-m-d');
            $data['control_number'] = 'CLR-' . date('Y') . '-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
            $data['expiry_date']    = date('Y-m-d', strtotime('+6 months'));

            // FIX: clearance_type_id — map the type name to the FK via clearance_types table
            // If your form sends clearance_type_id directly (numeric), use as-is.
            // If it sends a type name string, look it up.
            if (!empty($data['clearance_type']) && empty($data['clearance_type_id'])) {
                $db  = \Config\Database::connect();
                $row = $db->table('clearance_types')
                          ->where('type_name', $data['clearance_type'])
                          ->get()->getRowArray();
                $data['clearance_type_id'] = $row['clearance_type_id'] ?? null;
            }
            unset($data['clearance_type']);

            if (!$this->clearancesModel->insert($data)) {
                $errors = implode(', ', $this->clearancesModel->errors());
                return $this->response->setJSON([
                    'status'    => 'error',
                    'message'   => 'Validation failed: ' . $errors,
                    'csrf_hash' => csrf_hash(),
                ]);
            }

            $id = $this->clearancesModel->getInsertID();
            $this->logActivity('create', 'Created clearance request', $id);

            return $this->response->setJSON([
                'status'    => 'success',
                'message'   => 'Clearance request created successfully.',
                'csrf_hash' => csrf_hash(),
            ]);

        } catch (\Throwable $e) {
            log_message('error', '[Clearances::save] ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage(), 'csrf_hash' => csrf_hash()]);
        }
    }

    // =========================================================================
    // GET / EDIT
    // =========================================================================

    public function get($id)
    {
        try {
            // FIX: join clearance_types so we also return type_name for the edit modal
            $db = \Config\Database::connect();
            $clearance = $db->table('clearances c')
                ->select('c.*, ct.type_name')
                ->join('clearance_types ct', 'c.clearance_type_id = ct.clearance_type_id', 'left')
                ->where('c.clearance_id', (int) $id)
                ->where('c.deleted_at', null)
                ->get()->getRowArray();

            if (!$clearance) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Clearance not found.']);
            }
            return $this->response->setJSON(['status' => 'success', 'data' => $clearance]);
        } catch (\Throwable $e) {
            log_message('error', '[Clearances::get] ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function edit($id) { return $this->get($id); }

    // =========================================================================
    // VIEW (includes resident info + activity log)
    // =========================================================================

    public function view($id)
    {
        try {
            $db = \Config\Database::connect();
            $clearance = $db->table('clearances c')
                ->select('c.*, TRIM(CONCAT(r.first_name, " ", COALESCE(NULLIF(r.middle_name,""), ""), " ", r.last_name)) AS resident_name, r.address_line1, ct.type_name')
                ->join('residents r',        'c.resident_id = r.id',                          'left')
                ->join('clearance_types ct', 'c.clearance_type_id = ct.clearance_type_id',    'left')
                ->where('c.clearance_id', (int) $id)
                ->where('c.deleted_at', null)
                ->get()->getRowArray();

            if (!$clearance) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Clearance not found.']);
            }

            $history = [];
            if (!empty($clearance['resident_id'])) {
                $history = $this->clearancesModel->getClearanceHistory($clearance['resident_id']);
            }

            return $this->response->setJSON([
                'status'    => 'success',
                'data'      => $clearance,
                'history'   => $history,
                'csrf_hash' => csrf_hash(),
            ]);
        } catch (\Throwable $e) {
            log_message('error', '[Clearances::view] ' . $e->getMessage());
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

            // FIX: same type name → ID mapping as save()
            if (!empty($data['clearance_type']) && empty($data['clearance_type_id'])) {
                $db  = \Config\Database::connect();
                $row = $db->table('clearance_types')
                          ->where('type_name', $data['clearance_type'])
                          ->get()->getRowArray();
                $data['clearance_type_id'] = $row['clearance_type_id'] ?? null;
            }
            unset($data['clearance_type']);

            if (!$this->clearancesModel->update($id, $data)) {
                $errors = implode(', ', $this->clearancesModel->errors());
                return $this->response->setJSON([
                    'status'    => 'error',
                    'message'   => 'Update failed: ' . $errors,
                    'csrf_hash' => csrf_hash(),
                ]);
            }

            $this->logActivity('update', 'Updated clearance record', $id);

            return $this->response->setJSON([
                'status'    => 'success',
                'message'   => 'Clearance updated successfully.',
                'csrf_hash' => csrf_hash(),
            ]);
        } catch (\Throwable $e) {
            log_message('error', '[Clearances::update] ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage(), 'csrf_hash' => csrf_hash()]);
        }
    }

    // =========================================================================
    // DELETE
    // =========================================================================

    public function delete($id)
    {
        try {
            if (!$this->clearancesModel->delete($id)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Delete failed.', 'csrf_hash' => csrf_hash()]);
            }
            $this->logActivity('delete', 'Deleted clearance record', (int) $id);
            return $this->response->setJSON(['status' => 'success', 'message' => 'Clearance deleted successfully.', 'csrf_hash' => csrf_hash()]);
        } catch (\Throwable $e) {
            log_message('error', '[Clearances::delete] ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage(), 'csrf_hash' => csrf_hash()]);
        }
    }

    // =========================================================================
    // WORKFLOW: APPROVE
    // =========================================================================

    public function approve($id)
    {
        try {
            if (!$this->clearancesModel->approve((int) $id)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to approve.', 'csrf_hash' => csrf_hash()]);
            }
            $this->logActivity('workflow', 'Approved clearance', (int) $id);
            return $this->response->setJSON(['status' => 'success', 'message' => 'Clearance approved — ready for release.', 'csrf_hash' => csrf_hash()]);
        } catch (\Throwable $e) {
            log_message('error', '[Clearances::approve] ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage(), 'csrf_hash' => csrf_hash()]);
        }
    }

    // =========================================================================
    // WORKFLOW: RELEASE
    // =========================================================================

    public function release($id)
    {
        try {
            $orNumber = trim((string) ($this->request->getPost('or_number') ?? ''));

            if (!$this->clearancesModel->release((int) $id, $orNumber)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to release.', 'csrf_hash' => csrf_hash()]);
            }
            $this->logActivity('workflow', 'Released clearance', (int) $id);
            return $this->response->setJSON(['status' => 'success', 'message' => 'Clearance released successfully.', 'csrf_hash' => csrf_hash()]);
        } catch (\Throwable $e) {
            log_message('error', '[Clearances::release] ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage(), 'csrf_hash' => csrf_hash()]);
        }
    }

    // =========================================================================
    // WORKFLOW: REJECT  ← FIX: was completely missing from controller
    // =========================================================================

    public function reject($id)
    {
        try {
            $reason = trim((string) ($this->request->getPost('reason') ?? ''));

            if (!$this->clearancesModel->reject((int) $id, $reason)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to reject.', 'csrf_hash' => csrf_hash()]);
            }
            $this->logActivity('workflow', 'Rejected clearance', (int) $id);
            return $this->response->setJSON(['status' => 'success', 'message' => 'Clearance rejected.', 'csrf_hash' => csrf_hash()]);
        } catch (\Throwable $e) {
            log_message('error', '[Clearances::reject] ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage(), 'csrf_hash' => csrf_hash()]);
        }
    }

    // =========================================================================
    // RESIDENT SEARCH
    // =========================================================================

    public function searchResident()
    {
        try {
            $term = trim((string) ($this->request->getGet('term') ?? ''));

            if (strlen($term) < 2) {
                return $this->response->setJSON(['results' => []]);
            }

            $residents = $this->residentsModel
                ->where('deleted_at IS NULL', null, false)
                ->groupStart()
                    ->like('first_name',  $term)
                    ->orLike('middle_name', $term)
                    ->orLike('last_name',  $term)
                    ->orLike('CONCAT(first_name, " ", last_name)', $term, false)
                ->groupEnd()
                ->orderBy('last_name', 'ASC')
                ->limit(50)
                ->findAll();

            $results = array_map(function ($r) {
                $fullName = trim(($r['first_name'] ?? '') . ' ' . ($r['middle_name'] ?? '') . ' ' . ($r['last_name'] ?? ''));
                return [
                    'id'          => $r['id'],
                    'text'        => $fullName,
                    'first_name'  => $r['first_name']    ?? '',
                    'middle_name' => $r['middle_name']   ?? '',
                    'last_name'   => $r['last_name']     ?? '',
                    'address'     => $r['address_line1'] ?? '',
                ];
            }, $residents);

            return $this->response->setJSON(['results' => $results]);
        } catch (\Throwable $e) {
            log_message('error', '[Clearances::searchResident] ' . $e->getMessage());
            return $this->response->setJSON(['results' => [], 'error' => $e->getMessage()], 500);
        }
    }

    // =========================================================================
    // PRINT PREVIEW
    // =========================================================================

    public function getPrintPreview($id)
    {
        try {
            $db = \Config\Database::connect();
            $clearance = $db->table('clearances c')
                ->select('c.*, TRIM(CONCAT(r.first_name, " ", COALESCE(NULLIF(r.middle_name,""), ""), " ", r.last_name)) AS resident_name, r.address_line1, ct.type_name')
                ->join('residents r',        'c.resident_id = r.id',                          'left')
                ->join('clearance_types ct', 'c.clearance_type_id = ct.clearance_type_id',    'left')
                ->where('c.clearance_id', (int) $id)
                ->where('c.deleted_at',   null)
                ->get()->getRowArray();

            if (!$clearance) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Clearance not found.']);
            }

            $requestDate = !empty($clearance['request_date']) ? date('F d, Y', strtotime($clearance['request_date'])) : 'N/A';
            $issuedDate  = !empty($clearance['issued_date'])  ? date('F d, Y', strtotime($clearance['issued_date']))  : 'N/A';
            $expiryDate  = !empty($clearance['expiry_date'])  ? date('F d, Y', strtotime($clearance['expiry_date']))  : 'N/A';
            $fee         = '₱' . number_format((float) ($clearance['fee_amount'] ?? 0), 2);

            $html = <<<HTML
<style>
  body { font-family: "Times New Roman", serif; color: #111; }
  .clearance-wrap { padding: 40px; max-width: 760px; margin: auto; }
  .clearance-header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 12px; margin-bottom: 24px; }
  .clearance-header h3 { margin: 2px 0; font-size: 15px; }
  .clearance-header h1 { font-size: 22px; letter-spacing: 3px; margin: 10px 0 4px; text-decoration: underline; }
  .clearance-body p { font-size: 14px; line-height: 1.9; margin: 6px 0; }
  .clearance-body strong { min-width: 130px; display: inline-block; }
  .clearance-footer { margin-top: 70px; text-align: right; }
  .clearance-footer .sig-name { font-size: 15px; font-weight: bold; text-transform: uppercase; }
  .clearance-footer .sig-title { font-size: 13px; color: #555; }
</style>
<div class="clearance-wrap">
  <div class="clearance-header">
    <h3>REPUBLIC OF THE PHILIPPINES</h3>
    <h3>PROVINCE OF NEGROS OCCIDENTAL</h3>
    <h3>MUNICIPALITY OF CAUAYAN</h3>
    <h3>BARANGAY ISIO</h3>
    <h1>CLEARANCE CERTIFICATE</h1>
    <p class="control-no">Control No.: <strong>{$clearance['control_number']}</strong></p>
  </div>
  <div class="clearance-body">
    <p><strong>Resident Name:</strong> {$clearance['resident_name']}</p>
    <p><strong>Address:</strong> {$clearance['address_line1']}</p>
    <p><strong>Clearance Type:</strong> {$clearance['type_name']}</p>
    <p><strong>Purpose:</strong> {$clearance['purpose']}</p>
    <p><strong>Request Date:</strong> {$requestDate}</p>
    <p><strong>Issued Date:</strong> {$issuedDate}</p>
    <p><strong>Expiry Date:</strong> {$expiryDate}</p>
    <p><strong>Fee:</strong> {$fee}</p>
    <p><strong>OR Number:</strong> {$clearance['or_number']}</p>
    <p><strong>Status:</strong> {$clearance['status']}</p>
    <br>
    <p style="margin-top:16px;">
      This is to certify that the above-named individual has been granted clearance
      in accordance with barangay policies and regulations of Barangay Isio.
    </p>
  </div>
  <div class="clearance-footer">
    <br><br>
    <div class="sig-name">HON. JEROME AGUSTIN</div>
    <div class="sig-title">Punong Barangay, Barangay Isio</div>
  </div>
</div>
HTML;

            return $this->response->setJSON(['status' => 'success', 'html' => $html]);

        } catch (\Throwable $e) {
            log_message('error', '[Clearances::getPrintPreview] ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    // =========================================================================
    // HELPERS
    // =========================================================================

    private function logActivity(string $action, string $description, int $recordId): void
    {
        try {
            if (method_exists($this->activityLogModel, 'log')) {
                $this->activityLogModel->log('clearances', $action, $description, $recordId);
            }
        } catch (\Throwable $e) {
            log_message('warning', '[ActivityLog] ' . $e->getMessage());
        }
    }
}

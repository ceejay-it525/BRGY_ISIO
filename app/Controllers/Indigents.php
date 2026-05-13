<?php

namespace App\Controllers;

use App\Models\IndigentsModel;
use App\Models\ResidentsModel;
use App\Models\ActivityLogModel;
use CodeIgniter\Controller;

class Indigents extends Controller
{
    protected IndigentsModel  $indigentsModel;
    protected ResidentsModel  $residentsModel;
    protected ActivityLogModel $activityLogModel;

    public function __construct()
    {
        $this->indigentsModel   = new IndigentsModel();
        $this->residentsModel   = new ResidentsModel();
        $this->activityLogModel = new ActivityLogModel();
        helper(['url', 'form']);
    }

    // =========================================================================
    // PAGE ROUTES
    // =========================================================================

   public function index()
{
    $data['residents'] = $this->residentsModel
        ->where('deleted_at', null)
        ->orderBy('last_name', 'ASC')
        ->findAll();

    return view('indigents/index', $data);
}

public function pending()
{
    $data['residents'] = $this->residentsModel
        ->where('deleted_at', null)
        ->orderBy('last_name', 'ASC')
        ->findAll();

    return view('indigents/index', $data);
}

public function approved()
{
    $data['residents'] = $this->residentsModel
        ->where('deleted_at', null)
        ->orderBy('last_name', 'ASC')
        ->findAll();

    return view('indigents/index', $data);
}

public function released()
{
    $data['residents'] = $this->residentsModel
        ->where('deleted_at', null)
        ->orderBy('last_name', 'ASC')
        ->findAll();

    return view('indigents/index', $data);
}

    // =========================================================================
    // DATATABLE — server-side
    // =========================================================================

    public function fetchRecords()
    {
        try {
            $req      = service('request');
            $draw     = (int)   ($req->getPost('draw')   ?? 1);
            $start    = (int)   ($req->getPost('start')  ?? 0);
            $length   = (int)   ($req->getPost('length') ?? 25);
            $search   = (string)($req->getPost('search')['value'] ?? '');
            $viewType = (string)($req->getPost('view_type') ?? 'all');

            $result  = $this->indigentsModel->getRecords($search, $length, $start, 'i.id DESC', $viewType);
            $counter = $start + 1;

            foreach ($result['data'] as &$row) {
                $row['row_number'] = $counter++;
            }
            unset($row);

            return $this->response->setJSON([
                'draw'            => $draw,
                'recordsTotal'    => $result['total'],
                'recordsFiltered' => $result['total'],
                'data'            => $result['data'],
                'csrf_hash'       => csrf_hash(),
            ]);

        } catch (\Throwable $e) {
            log_message('error', '[Indigents::fetchRecords] ' . $e->getMessage());
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
                'data'      => $this->indigentsModel->getStats(),
                'csrf_hash' => csrf_hash(),
            ]);
        } catch (\Throwable $e) {
            log_message('error', '[Indigents::stats] ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    // =========================================================================
    // SAVE (create)
    // =========================================================================

public function save()
{
    try {
        $residentId = $this->request->getPost('resident_id');

        if (!$residentId) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Resident is required.'
            ]);
        }

        $resident = $this->residentsModel->find($residentId);
        if (!$resident) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Resident not found.'
            ]);
        }

        $fullName = trim(
            ($resident['first_name'] ?? '') . ' ' .
            ($resident['middle_name'] ?? '') . ' ' .
            ($resident['last_name'] ?? '')
        );

        $data = [
            'resident_id'        => $residentId,
            'indigency_category' => $this->request->getPost('indigency_category'),
            'assistance_type'    => $this->request->getPost('assistance_type'),
            'assistance_amount'  => $this->request->getPost('assistance_amount') ?: 0,
            'purpose'            => $this->request->getPost('purpose'),
            'remarks'            => $this->request->getPost('remarks'),
            'status'             => 'Pending Assessment',
            'date_assessed'      => date('Y-m-d'),
        ];

        $insertId = $this->indigentsModel->insert($data);
        if (!$insertId) {
            $errors = implode(', ', $this->indigentsModel->errors());
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Save failed: ' . $errors
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Record saved successfully.'
        ]);
    } catch (\Throwable $e) {
        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'Server error: ' . $e->getMessage()
        ]);
    }
}

    // =========================================================================
    // GET / EDIT (return JSON for modal population)
    // =========================================================================

    public function get($id)
    {
        try {
            $indigent = $this->indigentsModel->find((int) $id);
            if (!$indigent) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Record not found.']);
            }
            return $this->response->setJSON([
                'status'    => 'success',
                'data'      => $indigent,
                'csrf_hash' => csrf_hash(),
            ]);
        } catch (\Throwable $e) {
            log_message('error', '[Indigents::get] ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        try {
            $indigent = $this->indigentsModel->find((int) $id);
            if (!$indigent) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Record not found.']);
            }

            // Get resident info to populate full_name and address
            if ($indigent['resident_id']) {
                $resident = $this->residentsModel->find($indigent['resident_id']);
                if ($resident) {
                    $indigent['full_name'] = trim(
                        ($resident['first_name'] ?? '') . ' ' .
                        ($resident['middle_name'] ?? '') . ' ' .
                        ($resident['last_name'] ?? '')
                    );
                    $indigent['address'] = $resident['address_line1'] ?? '';
                }
            }

            return $this->response->setJSON([
                'status' => 'success',
                'data'   => $indigent,
            ]);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    // =========================================================================
    // VIEW (includes activity log + assistance history)
    // =========================================================================

    public function view($id)
    {
        try {
            $indigent = $this->indigentsModel->find((int) $id);
            if (!$indigent) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Record not found.']);
            }

            $resident = null;
            if ($indigent['resident_id']) {
                $resident = $this->residentsModel->find($indigent['resident_id']);
                if ($resident) {
                    $indigent['full_name'] = trim(
                        ($resident['first_name'] ?? '') . ' ' .
                        ($resident['middle_name'] ?? '') . ' ' .
                        ($resident['last_name'] ?? '')
                    );
                    $indigent['address'] = $resident['address_line1'] ?? '';
                }
            }

            $history = $this->indigentsModel->getAssistanceHistory($indigent['resident_id'], $id);

            return $this->response->setJSON([
                'status'  => 'success',
                'data'    => $indigent,
                'history' => $history,
            ]);
        } catch (\Throwable $e) {
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

            // Remove fields that don't exist in database
            unset($data['full_name'], $data['address']);

            // Prevent overwriting system date fields
            unset($data['date_assessed']);

            if (!$this->indigentsModel->update($id, $data)) {
                $errors = implode(', ', $this->indigentsModel->errors());
                return $this->response->setJSON([
                    'status'    => 'error',
                    'message'   => 'Update failed: ' . $errors,
                    'csrf_hash' => csrf_hash(),
                ]);
            }

            $this->logActivity('update', 'Updated indigent record', $id);

            return $this->response->setJSON([
                'status'    => 'success',
                'message'   => 'Record updated successfully.',
                'csrf_hash' => csrf_hash(),
            ]);
        } catch (\Throwable $e) {
            log_message('error', '[Indigents::update] ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage(), 'csrf_hash' => csrf_hash()]);
        }
    }

    // =========================================================================
    // DELETE
    // =========================================================================

    public function delete($id)
    {
        try {
            $record = $this->indigentsModel->find((int) $id);
            if (!$record) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Record not found.', 'csrf_hash' => csrf_hash()]);
            }

            if (!$this->indigentsModel->delete((int) $id)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Delete failed.', 'csrf_hash' => csrf_hash()]);
            }

            $this->logActivity('delete', 'Deleted indigent record', (int) $id);

            return $this->response->setJSON([
                'status'    => 'success',
                'message'   => 'Record deleted successfully.',
                'csrf_hash' => csrf_hash(),
            ]);
        } catch (\Throwable $e) {
            log_message('error', '[Indigents::delete] ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage(), 'csrf_hash' => csrf_hash()]);
        }
    }

    // =========================================================================
    // WORKFLOW: APPROVE  (Pending Assessment → Approved)
    // =========================================================================

    public function approve($id)
    {
        try {
            $record = $this->indigentsModel->find((int) $id);
            if (!$record) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Record not found.', 'csrf_hash' => csrf_hash()]);
            }
            if ($record['status'] !== 'Pending Assessment') {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Record must be in Pending Assessment status to approve.', 'csrf_hash' => csrf_hash()]);
            }

            $this->indigentsModel->update((int) $id, [
                'status' => 'Approved',
            ]);

            $this->logActivity('workflow', 'Approved assistance record', (int) $id);

            return $this->response->setJSON([
                'status'    => 'success',
                'message'   => 'Assistance approved — ready for provision.',
                'csrf_hash' => csrf_hash(),
            ]);
        } catch (\Throwable $e) {
            log_message('error', '[Indigents::approve] ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage(), 'csrf_hash' => csrf_hash()]);
        }
    }

    // =========================================================================
    // WORKFLOW: REJECT
    // =========================================================================

    public function reject($id)
    {
        try {
            $reason = trim((string)($this->request->getPost('reason') ?? ''));
            if ($reason === '') {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Rejection reason is required.', 'csrf_hash' => csrf_hash()]);
            }

            $record = $this->indigentsModel->find((int) $id);
            if (!$record) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Record not found.', 'csrf_hash' => csrf_hash()]);
            }
            if (!in_array($record['status'], ['Pending Assessment', 'Approved'])) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Cannot reject a record in its current status.', 'csrf_hash' => csrf_hash()]);
            }

            $this->indigentsModel->update((int) $id, [
                'status'          => 'Rejected',
                'rejected_reason' => $reason,
            ]);

            $this->logActivity('workflow', 'Rejected: ' . $reason, (int) $id);

            return $this->response->setJSON([
                'status'    => 'success',
                'message'   => 'Assistance rejected and logged.',
                'csrf_hash' => csrf_hash(),
            ]);
        } catch (\Throwable $e) {
            log_message('error', '[Indigents::reject] ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage(), 'csrf_hash' => csrf_hash()]);
        }
    }

    // =========================================================================
    // WORKFLOW: COMPLETE  (Approved → Completed)
    // =========================================================================

    public function complete($id)
    {
        try {
            $record = $this->indigentsModel->find((int) $id);
            if (!$record) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Record not found.', 'csrf_hash' => csrf_hash()]);
            }
            if ($record['status'] !== 'Approved') {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Record must be Approved before completing.', 'csrf_hash' => csrf_hash()]);
            }

            $this->indigentsModel->update((int) $id, [
                'status'        => 'Completed',
                'date_provided' => date('Y-m-d'),
            ]);

            $this->logActivity('workflow', 'Completed assistance record', (int) $id);

            return $this->response->setJSON([
                'status'    => 'success',
                'message'   => 'Assistance marked as completed.',
                'csrf_hash' => csrf_hash(),
            ]);
        } catch (\Throwable $e) {
            log_message('error', '[Indigents::complete] ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage(), 'csrf_hash' => csrf_hash()]);
        }
    }

    // =========================================================================
    // SEARCH RESIDENT (Select2 AJAX)
    // =========================================================================

    public function searchResident()
    {
        try {
            $term = trim((string)($this->request->getGet('term') ?? ''));

            if (strlen($term) < 2) {
                return $this->response->setJSON(['results' => []]);
            }

            $residents = $this->residentsModel
                ->where('deleted_at', null)
                ->groupStart()
                    ->like('first_name', $term)
                    ->orLike('middle_name', $term)
                    ->orLike('last_name', $term)
                    ->orLike("CONCAT(first_name, ' ', last_name)", $term, false)
                ->groupEnd()
                ->orderBy('last_name', 'ASC')
                ->limit(50)
                ->findAll();

            $results = array_map(function ($r) {
                $fullName = trim(
                    ($r['first_name']  ?? '') . ' ' .
                    ($r['middle_name'] ?? '') . ' ' .
                    ($r['last_name']   ?? '')
                );
                return [
                    'id'          => $r['id'],
                    'text'        => $fullName,
                    'first_name'  => $r['first_name']  ?? '',
                    'middle_name' => $r['middle_name'] ?? '',
                    'last_name'   => $r['last_name']   ?? '',
                    'address'     => $r['address_line1'] ?? '',
                    'birthdate'   => $r['birthdate']   ?? '',
                    'civil_status'=> $r['civil_status'] ?? '',
                ];
            }, $residents);

            return $this->response->setJSON(['results' => $results]);
        } catch (\Throwable $e) {
            log_message('error', '[Indigents::searchResident] ' . $e->getMessage());
            return $this->response->setJSON(['results' => [], 'error' => $e->getMessage()], 500);
        }
    }

    // =========================================================================
    // GET RESIDENT INFO  (for autofill via resident_id)
    // =========================================================================

    public function getResidentInfo($residentId)
    {
        try {
            $r = $this->residentsModel->find((int) $residentId);
            if (!$r) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Resident not found.']);
            }
            return $this->response->setJSON([
                'status' => 'success',
                'data'   => [
                    'full_name'    => trim(($r['first_name'] ?? '') . ' ' . ($r['middle_name'] ?? '') . ' ' . ($r['last_name'] ?? '')),
                    'address'      => $r['address_line1'] ?? '',
                    'purok'        => $r['purok'] ?? '',
                    'contact_no'   => $r['contact_no'] ?? '',
                    'birthdate'    => $r['birthdate']     ?? '',
                    'civil_status' => $r['civil_status']  ?? '',
                ],
            ]);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    // =========================================================================
    // PRINT PREVIEW
    // =========================================================================

    public function getPrintPreview($id)
    {
        try {
            $indigent = $this->indigentsModel->find((int) $id);
            if (!$indigent) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Record not found.']);
            }

            $dateAssessed = !empty($indigent['date_assessed']) ? date('F d, Y', strtotime($indigent['date_assessed'])) : 'N/A';
            $dateProvided = !empty($indigent['date_provided']) ? date('F d, Y', strtotime($indigent['date_provided'])) : 'Not Yet Provided';
            $amount       = '₱' . number_format((float)($indigent['assistance_amount'] ?? 0), 2);
            $certNo       = $indigent['certificate_no'] ?? 'ISIO-IND-' . date('Y') . '-' . str_pad((int)$id, 6, '0', STR_PAD_LEFT);
            $fullName     = strtoupper($indigent['full_name'] ?? 'N/A');
            $address      = $indigent['address']          ?? 'Barangay Isio, Cauayan, Negros Occidental';
            $category     = $indigent['indigency_category'] ?? 'N/A';
            $assistType   = $indigent['assistance_type']   ?? 'N/A';
            $purpose      = $indigent['purpose']           ?? 'N/A';

            $html = <<<HTML
<style>
  * { box-sizing: border-box; }
  body { font-family: "Times New Roman", Times, serif; color: #111; margin: 0; }
  .doc-wrap { padding: 48px 56px; max-width: 780px; margin: 0 auto; position: relative; }
  .watermark {
    position: fixed; top: 50%; left: 50%;
    transform: translate(-50%, -50%) rotate(-35deg);
    font-size: 72px; color: rgba(0,0,0,0.04);
    font-weight: 900; letter-spacing: 4px;
    pointer-events: none; white-space: nowrap; z-index: 0;
    text-transform: uppercase;
  }
  .doc-header { text-align: center; border-bottom: 3px double #222; padding-bottom: 14px; margin-bottom: 20px; }
  .doc-header .seal { font-size: 40px; margin-bottom: 6px; }
  .doc-header h4 { margin: 2px 0; font-size: 12px; letter-spacing: 1px; }
  .doc-header h2 { font-size: 18px; margin: 10px 0 4px; letter-spacing: 3px; text-transform: uppercase; text-decoration: underline; }
  .cert-no { font-size: 12px; color: #555; margin-top: 4px; }
  .doc-body { font-size: 14px; line-height: 1.9; position: relative; z-index: 1; }
  .doc-body table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
  .doc-body table td { padding: 5px 8px; border: 1px solid #ddd; }
  .doc-body table td:first-child { font-weight: bold; width: 38%; background: #f9f9f9; }
  .attestation { margin-top: 20px; text-align: justify; font-size: 13.5px; line-height: 1.8; border: 1px solid #ddd; padding: 14px; background: #fafafa; }
  .doc-footer { margin-top: 64px; }
  .sig-block { text-align: center; float: right; width: 260px; }
  .sig-line { border-top: 2px solid #111; margin: 0 auto 6px; width: 240px; }
  .sig-name { font-size: 15px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
  .sig-title { font-size: 12px; color: #444; }
  .clearfix::after { content: ""; display: table; clear: both; }
</style>
<div class="doc-wrap">
  <div class="watermark">OFFICIAL DOCUMENT</div>
  <div class="doc-header">
    <div class="seal">🏛️</div>
    <h4>REPUBLIC OF THE PHILIPPINES</h4>
    <h4>PROVINCE OF NEGROS OCCIDENTAL</h4>
    <h4>MUNICIPALITY OF CAUAYAN</h4>
    <h4><strong>BARANGAY ISIO</strong></h4>
    <h2>Indigent Assistance Certificate</h2>
    <p class="cert-no">Certificate No.: <strong>{$certNo}</strong></p>
  </div>
  <div class="doc-body">
    <table>
      <tr><td>Beneficiary Name</td><td><strong>{$fullName}</strong></td></tr>
      <tr><td>Address</td><td>{$address}</td></tr>
      <tr><td>Indigency Category</td><td>{$category}</td></tr>
      <tr><td>Assistance Type</td><td>{$assistType}</td></tr>
      <tr><td>Assistance Amount</td><td><strong>{$amount}</strong></td></tr>
      <tr><td>Purpose</td><td>{$purpose}</td></tr>
      <tr><td>Date Assessed</td><td>{$dateAssessed}</td></tr>
      <tr><td>Date Provided</td><td>{$dateProvided}</td></tr>
    </table>
    <div class="attestation">
      This is to certify that the above-named individual has been duly assessed and qualified
      for indigent assistance in accordance with the social welfare programs of Barangay Isio,
      Cauayan, Negros Occidental. This certificate is issued for whatever legal purpose it may serve.
    </div>
  </div>
  <div class="doc-footer clearfix">
    <div class="sig-block">
      <br><br>
      <div class="sig-line"></div>
      <div class="sig-name">Hon. Jerome Agustin</div>
      <div class="sig-title">Punong Barangay, Barangay Isio</div>
    </div>
  </div>
</div>
HTML;

            return $this->response->setJSON(['status' => 'success', 'html' => $html]);

        } catch (\Throwable $e) {
            log_message('error', '[Indigents::getPrintPreview] ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    // =========================================================================
    // HELPERS
    // =========================================================================

    private function generateCertificateNo(): string
    {
        try {
            $year = date('Y');
            $lastId = $this->indigentsModel->getLastIdForYear($year);
            $sequence = $lastId + 1;
            return 'ISIO-IND-' . $year . '-' . str_pad($sequence, 6, '0', STR_PAD_LEFT);
        } catch (\Throwable $e) {
            log_message('warning', '[Indigents::generateCertificateNo] Error: ' . $e->getMessage() . ', using fallback');
            return 'ISIO-IND-' . date('Y') . '-' . date('YmdHis');
        }
    }

    private function logActivity(string $action, string $description, int $recordId): void
    {
        try {
            if (method_exists($this->activityLogModel, 'log')) {
                $this->activityLogModel->log('indigents', $action, $description, $recordId);
            }
        } catch (\Throwable $e) {
            log_message('warning', '[ActivityLog] ' . $e->getMessage());
        }
    }
}

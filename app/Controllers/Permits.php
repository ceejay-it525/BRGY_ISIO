<?php

namespace App\Controllers;

use App\Models\PermitsModel;
use App\Models\ActivityLogModel;
use CodeIgniter\API\ResponseTrait;

class Permits extends BaseController
{
    use ResponseTrait;

    protected PermitsModel     $permitsModel;
    protected ActivityLogModel $activityLogModel;

    public function __construct()
    {
        $this->permitsModel     = new PermitsModel();
        $this->activityLogModel = new ActivityLogModel();
    }

    // =========================================================================
    // PAGE ROUTES
    // =========================================================================

    public function index()   { return view('permits/index'); }
    public function pending() { return view('permits/index'); }
    public function payment() { return view('permits/index'); }
    public function print()   { return view('permits/index'); }

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
            $viewType = (string) ($req->getPost('view_type') ?? 'all');

            $result  = $this->permitsModel->getRecords($start, $length, $search, $viewType);
            $counter = $start + 1;

            foreach ($result['data'] as &$row) {
                $row['row_number'] = $counter++;
            }

            return $this->response->setJSON([
                'draw'            => $draw,
                'recordsTotal'    => $result['recordsTotal'],
                'recordsFiltered' => $result['filtered'],
                'data'            => $result['data'],
                'csrf_hash'       => csrf_hash(),
            ]);

        } catch (\Throwable $e) {
            log_message('error', '[Permits::fetchRecords] ' . $e->getMessage());
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
                'data'      => $this->permitsModel->getStats(),
                'csrf_hash' => csrf_hash(),
            ]);
        } catch (\Throwable $e) {
            log_message('error', '[Permits::stats] ' . $e->getMessage());
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

            if (!$this->permitsModel->insert($data)) {
                $errors = implode(', ', $this->permitsModel->errors());
                return $this->response->setJSON([
                    'status'    => 'error',
                    'message'   => 'Validation failed: ' . $errors,
                    'csrf_hash' => csrf_hash(),
                ]);
            }

            $id = $this->permitsModel->getInsertID();
            $this->activityLogModel->logPermitAction($id, 'Created', 'Permit application submitted');

            return $this->response->setJSON([
                'status'    => 'success',
                'message'   => 'Permit saved successfully.',
                'csrf_hash' => csrf_hash(),
            ]);

        } catch (\Throwable $e) {
            log_message('error', '[Permits::save] ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage(), 'csrf_hash' => csrf_hash()]);
        }
    }

    // =========================================================================
    // GET / EDIT (return JSON for modal population)
    // =========================================================================

    public function get($id)
    {
        try {
            $permit = $this->permitsModel->find($id);
            if (!$permit) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Permit not found.']);
            }
            return $this->response->setJSON(['status' => 'success', 'data' => $permit]);
        } catch (\Throwable $e) {
            log_message('error', '[Permits::get] ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function edit($id) { return $this->get($id); }

    // =========================================================================
    // VIEW (includes activity log)
    // =========================================================================

    public function view($id)
    {
        try {
            $permit = $this->permitsModel->find($id);
            if (!$permit) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Permit not found.']);
            }

            $activity = $this->activityLogModel->getActivityByPermitId($id);

            return $this->response->setJSON([
                'status'   => 'success',
                'data'     => $permit,
                'activity' => $activity,
            ]);
        } catch (\Throwable $e) {
            log_message('error', '[Permits::view] ' . $e->getMessage());
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

            if (!$this->permitsModel->update($id, $data)) {
                $errors = implode(', ', $this->permitsModel->errors());
                return $this->response->setJSON([
                    'status'    => 'error',
                    'message'   => 'Update failed: ' . $errors,
                    'csrf_hash' => csrf_hash(),
                ]);
            }

            $this->activityLogModel->logPermitAction($id, 'Updated', 'Permit details updated');

            return $this->response->setJSON([
                'status'    => 'success',
                'message'   => 'Permit updated successfully.',
                'csrf_hash' => csrf_hash(),
            ]);
        } catch (\Throwable $e) {
            log_message('error', '[Permits::update] ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage(), 'csrf_hash' => csrf_hash()]);
        }
    }

    // =========================================================================
    // DELETE
    // =========================================================================

    public function delete($id)
    {
        try {
            if (!$this->permitsModel->delete($id)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Delete failed.', 'csrf_hash' => csrf_hash()]);
            }

            $this->activityLogModel->logPermitAction($id, 'Deleted', 'Permit removed from active records');

            return $this->response->setJSON([
                'status'    => 'success',
                'message'   => 'Permit deleted successfully.',
                'csrf_hash' => csrf_hash(),
            ]);
        } catch (\Throwable $e) {
            log_message('error', '[Permits::delete] ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage(), 'csrf_hash' => csrf_hash()]);
        }
    }

    // =========================================================================
    // WORKFLOW: APPROVE
    // =========================================================================

    public function approve($id)
    {
        try {
            if (!$this->permitsModel->approve((int) $id)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to approve.', 'csrf_hash' => csrf_hash()]);
            }

            $actor = session()->get('username') ?? 'Admin';
            $this->activityLogModel->logPermitAction($id, 'Approved', 'Approved by ' . $actor);

            return $this->response->setJSON([
                'status'    => 'success',
                'message'   => 'Permit approved — ready for payment.',
                'csrf_hash' => csrf_hash(),
            ]);
        } catch (\Throwable $e) {
            log_message('error', '[Permits::approve] ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage(), 'csrf_hash' => csrf_hash()]);
        }
    }

    // =========================================================================
    // WORKFLOW: REJECT
    // =========================================================================

    public function reject($id)
    {
        try {
            $reason = trim((string) ($this->request->getPost('reason') ?? ''));
            if ($reason === '') {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Rejection reason is required.', 'csrf_hash' => csrf_hash()]);
            }

            if (!$this->permitsModel->reject((int) $id, $reason)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to reject.', 'csrf_hash' => csrf_hash()]);
            }

            $this->activityLogModel->logPermitAction($id, 'Rejected', 'Reason: ' . $reason);

            return $this->response->setJSON([
                'status'    => 'success',
                'message'   => 'Permit rejected and logged.',
                'csrf_hash' => csrf_hash(),
            ]);
        } catch (\Throwable $e) {
            log_message('error', '[Permits::reject] ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage(), 'csrf_hash' => csrf_hash()]);
        }
    }

    // =========================================================================
    // WORKFLOW: MARK PAID
    // =========================================================================

    public function markPaid($id)
    {
        try {
            $fees = $this->request->getPost('fees_paid');

            if ($fees === null || $fees === '') {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Please enter the amount paid.', 'csrf_hash' => csrf_hash()]);
            }

            $fees = (float) $fees;
            if ($fees <= 0) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Amount must be greater than zero.', 'csrf_hash' => csrf_hash()]);
            }

            if (!$this->permitsModel->markPaid((int) $id, $fees)) {
                $errors = implode(', ', $this->permitsModel->errors());
                return $this->response->setJSON([
                    'status'    => 'error',
                    'message'   => 'Failed to process payment.' . ($errors ? ' ' . $errors : ''),
                    'csrf_hash' => csrf_hash(),
                ]);
            }

            $this->activityLogModel->logPermitAction(
                $id, 'Paid',
                'Fees of ₱' . number_format($fees, 2) . ' received'
            );

            return $this->response->setJSON([
                'status'    => 'success',
                'message'   => 'Payment recorded — permit ready to print.',
                'csrf_hash' => csrf_hash(),
            ]);
        } catch (\Throwable $e) {
            log_message('error', '[Permits::markPaid] ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage(), 'csrf_hash' => csrf_hash()]);
        }
    }

    // =========================================================================
    // WORKFLOW: MARK ACTIVE
    // =========================================================================

    public function markActive($id)
    {
        try {
            if (!$this->permitsModel->markActive((int) $id)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to activate.', 'csrf_hash' => csrf_hash()]);
            }

            $this->activityLogModel->logPermitAction($id, 'Activated', 'Permit marked as Active');

            return $this->response->setJSON([
                'status'    => 'success',
                'message'   => 'Permit is now Active.',
                'csrf_hash' => csrf_hash(),
            ]);
        } catch (\Throwable $e) {
            log_message('error', '[Permits::markActive] ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage(), 'csrf_hash' => csrf_hash()]);
        }
    }

    // =========================================================================
    // PRINT PREVIEW
    // =========================================================================

    public function getPrintPreview($id)
    {
        try {
            $permit = $this->permitsModel->find($id);
            if (!$permit) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Permit not found.']);
            }

            $issueDate  = !empty($permit['issue_date'])  ? date('F d, Y', strtotime($permit['issue_date']))  : 'N/A';
            $expiryDate = !empty($permit['expiry_date']) ? date('F d, Y', strtotime($permit['expiry_date'])) : 'N/A';
            $feesPaid   = '₱' . number_format((float) ($permit['fees_paid'] ?? 0), 2);
            $permitNo   = 'BP-' . str_pad($permit['id'], 5, '0', STR_PAD_LEFT) . '-' . date('Y');

            $html = <<<HTML
<style>
  body { font-family: "Times New Roman", serif; color: #111; }
  .permit-wrap { padding: 40px; max-width: 760px; margin: auto; }
  .permit-header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 12px; margin-bottom: 24px; }
  .permit-header h3 { margin: 2px 0; font-size: 15px; }
  .permit-header h1 { font-size: 22px; letter-spacing: 3px; margin: 10px 0 4px; text-decoration: underline; }
  .permit-no { font-size: 13px; color: #555; }
  .permit-body p { font-size: 14px; line-height: 1.9; margin: 6px 0; }
  .permit-body strong { min-width: 130px; display: inline-block; }
  .permit-footer { margin-top: 70px; text-align: right; }
  .permit-footer .sig-name { font-size: 15px; font-weight: bold; text-transform: uppercase; }
  .permit-footer .sig-title { font-size: 13px; color: #555; }
</style>
<div class="permit-wrap">
  <div class="permit-header">
    <h3>REPUBLIC OF THE PHILIPPINES</h3>
    <h3>PROVINCE OF NEGROS OCCIDENTAL</h3>
    <h3>MUNICIPALITY OF CAUAYAN</h3>
    <h3>BARANGAY ISIO</h3>
    <h1>BUSINESS PERMIT</h1>
    <p class="permit-no">Permit No.: <strong>{$permitNo}</strong></p>
  </div>
  <div class="permit-body">
    <p><strong>Business Name:</strong> {$permit['business_name']}</p>
    <p><strong>Owner / Operator:</strong> {$permit['owner_name']}</p>
    <p><strong>Business Type:</strong> {$permit['business_type']}</p>
    <p><strong>Address:</strong> {$permit['business_address']}</p>
    <p><strong>Permit Type:</strong> {$permit['permit_type']}</p>
    <p><strong>Issue Date:</strong> {$issueDate}</p>
    <p><strong>Valid Until:</strong> {$expiryDate}</p>
    <p><strong>Fees Paid:</strong> {$feesPaid}</p>
    <br>
    <p style="margin-top:16px;">
      This permit is hereby granted in accordance with the ordinances of Barangay Isio,
      authorizing the above-named individual/entity to operate the stated business within
      the jurisdiction of this Barangay.
    </p>
  </div>
  <div class="permit-footer">
    <br><br>
    <div class="sig-name">Jerome Agustin</div>
    <div class="sig-title">Barangay Captain, Barangay Isio</div>
  </div>
</div>
HTML;

            return $this->response->setJSON(['status' => 'success', 'html' => $html]);

        } catch (\Throwable $e) {
            log_message('error', '[Permits::getPrintPreview] ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function printPermit($id)
    {
        try {
            $this->activityLogModel->logPermitAction($id, 'Printed', 'Permit document generated');
            return $this->response->setJSON(['status' => 'success', 'csrf_hash' => csrf_hash()]);
        } catch (\Throwable $e) {
            log_message('error', '[Permits::printPermit] ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
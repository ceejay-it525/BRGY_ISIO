<?php
namespace App\Models;

use CodeIgniter\Model;

class BlotterModel extends Model
{
    protected $table      = 'blotter';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'case_number',
        'incident_type',
        'incident_date',
        'complainant_resident_id',
        'respondent_resident_id',
        'complainant_name',
        'respondent_name',
        'complainant_address',
        'respondent_address',
        'complainant_contact',
        'respondent_contact',
        'incident_location',
        'status',
        'narrative',
        'action_taken',
        'qr_code',
        'dismissed_reason',
        'dismissed_date',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Fetch paginated blotter records with joined resident full names.
     *
     * @param int    $start        DataTables start offset
     * @param int    $length       DataTables page length
     * @param string $searchValue  Search keyword
     * @param string $searchType   Column to restrict search to (or 'all')
     * @param string $statusFilter Status filter (Pending/Ongoing/Settled/Referred/Dismissed)
     * @return array ['data' => [...], 'filtered' => int]
     */
    public function getRecords(
        int    $start       = 0,
        int    $length      = 25,
        string $searchValue = '',
        string $searchType  = 'all',
        string $statusFilter = ''
    ): array {
        $validStatuses = ['Pending', 'Ongoing', 'Settled', 'Referred', 'Dismissed'];

        // ── Main query ────────────────────────────────────────────
        $builder = $this->db->table($this->table . ' b')
            ->select("b.*,
                TRIM(CONCAT(
                    COALESCE(c.first_name, ''), ' ',
                    COALESCE(NULLIF(c.middle_name, ''), ''), ' ',
                    COALESCE(c.last_name, ''), ' ',
                    COALESCE(c.suffix, '')
                )) AS complainant_full_name,
                c.address_line1  AS complainant_address_auto,
                c.contact_number AS complainant_contact_auto,
                TRIM(CONCAT(
                    COALESCE(r.first_name, ''), ' ',
                    COALESCE(NULLIF(r.middle_name, ''), ''), ' ',
                    COALESCE(r.last_name, ''), ' ',
                    COALESCE(r.suffix, '')
                )) AS respondent_full_name,
                r.address_line1  AS respondent_address_auto,
                r.contact_number AS respondent_contact_auto")
            ->join('residents c', 'b.complainant_resident_id = c.id AND c.deleted_at IS NULL', 'left')
            ->join('residents r', 'b.respondent_resident_id  = r.id AND r.deleted_at IS NULL', 'left');

        // ── Count query (mirrors filters but no SELECT/ORDER/LIMIT) ──
        $countBuilder = $this->db->table($this->table . ' b')
            ->select('COUNT(b.id) AS cnt')
            ->join('residents c', 'b.complainant_resident_id = c.id AND c.deleted_at IS NULL', 'left')
            ->join('residents r', 'b.respondent_resident_id  = r.id AND r.deleted_at IS NULL', 'left');

        // ── Status filter ─────────────────────────────────────────
        if ($statusFilter !== '' && in_array($statusFilter, $validStatuses, true)) {
            $builder->where('b.status', $statusFilter);
            $countBuilder->where('b.status', $statusFilter);
        }

        // ── Search ────────────────────────────────────────────────
        $allowedColumns = [
            'case_number', 'incident_type', 'complainant_name',
            'respondent_name', 'incident_location', 'status',
        ];

        if ($searchValue !== '') {
            if ($searchType !== 'all' && in_array($searchType, $allowedColumns, true)) {
                $builder->like('b.' . $searchType, $searchValue);
                $countBuilder->like('b.' . $searchType, $searchValue);
            } else {
                $builder->groupStart()
                    ->like('b.case_number',       $searchValue)
                    ->orLike('b.complainant_name', $searchValue)
                    ->orLike('b.respondent_name',  $searchValue)
                    ->orLike('c.first_name',        $searchValue)
                    ->orLike('c.last_name',         $searchValue)
                    ->orLike('r.first_name',        $searchValue)
                    ->orLike('r.last_name',         $searchValue)
                    ->orLike('b.incident_type',     $searchValue)
                    ->orLike('b.incident_location', $searchValue)
                    ->orLike('b.status',            $searchValue)
                    ->orLike('b.narrative',         $searchValue)
                    ->groupEnd();

                $countBuilder->groupStart()
                    ->like('b.case_number',       $searchValue)
                    ->orLike('b.complainant_name', $searchValue)
                    ->orLike('b.respondent_name',  $searchValue)
                    ->orLike('c.first_name',        $searchValue)
                    ->orLike('c.last_name',         $searchValue)
                    ->orLike('r.first_name',        $searchValue)
                    ->orLike('r.last_name',         $searchValue)
                    ->orLike('b.incident_type',     $searchValue)
                    ->orLike('b.incident_location', $searchValue)
                    ->orLike('b.status',            $searchValue)
                    ->orLike('b.narrative',         $searchValue)
                    ->groupEnd();
            }
        }

        // ── Count filtered records ────────────────────────────────
        $filteredRow = $countBuilder->get()->getRowArray();
        $filtered    = (int) ($filteredRow['cnt'] ?? 0);

        // ── Order + paginate ──────────────────────────────────────
        $builder->orderBy('b.id', 'DESC')
                ->limit($length, $start);

        return [
            'data'     => $builder->get()->getResultArray(),
            'filtered' => $filtered,
        ];
    }
}

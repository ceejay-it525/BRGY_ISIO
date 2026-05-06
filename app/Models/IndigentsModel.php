<?php

namespace App\Models;

use CodeIgniter\Model;

class IndigentsModel extends Model
{
    protected $table      = 'indigents';
    protected $primaryKey = 'id';

    // ✅ resident_name is GENERATED — never include it here
    protected $allowedFields = [
        'first_name',
        'middle_name',
        'last_name',
        'indigency_category',
        'assistance_type',
        'assistance_amount',
        'date_assessed',
        'date_provided',
        'status',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $useSoftDeletes = true;

    public function getRecords(int $start, int $length, string $search = ''): array
    {
        // ✅ Use a subquery so ROW_NUMBER() doesn't conflict with countAllResults
        $db = \Config\Database::connect();

        $inner = $db->table($this->table)
                    ->select('id, first_name, middle_name, last_name, resident_name,
                              indigency_category, assistance_type, assistance_amount,
                              date_assessed, date_provided, status, created_at')
                    ->where('deleted_at IS NULL');

        if ($search !== '') {
            $inner->groupStart()
                  ->like('first_name', $search)
                  ->orLike('last_name', $search)
                  ->orLike('resident_name', $search)
                  ->orLike('indigency_category', $search)
                  ->orLike('assistance_type', $search)
                  ->groupEnd();
        }

        // Count filtered (clone before adding limit)
        $totalFiltered = (clone $inner)->countAllResults();

        // Fetch paginated rows with row number via PHP
        $rows = $inner->orderBy('id', 'DESC')
                      ->limit($length, $start)
                      ->get()
                      ->getResultArray();

        // Add row_number manually (avoids OVER() compatibility issues)
        foreach ($rows as $i => &$row) {
            $row['row_number'] = $start + $i + 1;
        }

        return [
            'data'     => $rows,
            'filtered' => $totalFiltered,
        ];
    }
}
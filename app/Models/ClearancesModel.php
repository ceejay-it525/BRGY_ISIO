<?php

namespace App\Models;

use CodeIgniter\Model;

class ClearancesModel extends Model
{
    protected $table = 'clearances';
    protected $primaryKey = 'clearance_id';
    
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $allowedFields = [
        'control_number', 'resident_id', 'clearance_type_id', 'purpose', 
        'request_date', 'issued_date', 'expiry_date', 'status', 
        'fee_amount', 'or_number', 'remarks', 'processed_by', 'signed_by'
    ];

    protected $validationRules = [
        'control_number' => 'required|is_unique[clearances.control_number]',
        'resident_id' => 'required|integer',
        'clearance_type_id' => 'required|integer',
        'purpose' => 'required|max_length[255]',
        'request_date' => 'required|valid_date',
        'status' => 'required|in_list[Pending,Approved,Released,Rejected,Expired]',
        'fee_amount' => 'required|numeric|greater_than_equal_to[0]'
    ];

    public function getRecords($start = 0, $length = 10, $search = '')
    {
        $builder = $this->db->table($this->table . ' c');
        $builder->select("
            c.*, 
            CONCAT(resident.first_name, ' ', COALESCE(resident.middle_name, ''), ' ', resident.last_name) as resident_name,
            ct.type_name,
            DATE_FORMAT(c.request_date, '%M %d, %Y') as formatted_request_date,
            DATE_FORMAT(c.issued_date, '%M %d, %Y') as formatted_issued_date
        ");
        $builder->join('residents resident', 'c.resident_id = resident.resident_id', 'left');
        $builder->join('clearance_types ct', 'c.clearance_type_id = ct.clearance_type_id', 'left');
        
        if (!empty($search)) {
            $builder->groupStart();
            $builder->like('c.control_number', $search);
            $builder->orLike('c.purpose', $search);
            $builder->orLike('c.status', $search);
            $builder->orLike('resident.first_name', $search);
            $builder->orLike('resident.last_name', $search);
            $builder->groupEnd();
        }

        $builder->orderBy('c.created_at', 'DESC');
        $builder->limit($length, $start);

        return $builder->get()->getResultArray();
    }

    public function countFiltered($search = '')
    {
        $builder = $this->db->table($this->table . ' c');
        $builder->select('COUNT(*) as total');
        $builder->join('residents resident', 'c.resident_id = resident.resident_id', 'left');
        
        if (!empty($search)) {
            $builder->groupStart();
            $builder->like('c.control_number', $search);
            $builder->orLike('c.purpose', $search);
            $builder->orLike('c.status', $search);
            $builder->orLike('resident.first_name', $search);
            $builder->orLike('resident.last_name', $search);
            $builder->groupEnd();
        }

        $result = $builder->get()->getRowArray();
        return $result['total'] ?? 0;
    }
}


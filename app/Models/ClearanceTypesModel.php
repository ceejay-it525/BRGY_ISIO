<?php

namespace App\Models;

use CodeIgniter\Model;

class ClearanceTypesModel extends Model
{
    protected $table = 'clearance_types';
    protected $primaryKey = 'clearance_type_id';

    protected $allowedFields = [
        'type_name'
    ];
}


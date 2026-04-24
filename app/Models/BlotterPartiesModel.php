<?php

namespace App\Models;

use CodeIgniter\Model;

class BlotterPartiesModel extends Model
{
    protected $table = 'blotter_parties';
    protected $primaryKey = 'blotter_party_id';

    protected $allowedFields = [
        'blotter_id',
        'resident_id',
        'full_name',
        'address',
        'contact_number',
        'role',
        'statement'
    ];
}
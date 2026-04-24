<?php

namespace App\Models;

use CodeIgniter\Model;

class BlotterHearingsModel extends Model
{
    protected $table = 'blotter_hearings';
    protected $primaryKey = 'hearing_id';

    protected $allowedFields = [
        'blotter_id',
        'hearing_date',
        'hearing_time',
        'venue',
        'minutes',
        'presided_by'
    ];
}
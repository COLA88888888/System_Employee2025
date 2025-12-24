<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    protected $fillable = [
        'employee_id',
        'break_type_id',
        'start_date',
        'end_date',
        'reason',
        'status',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
        protected $fillable = [
            'employee_id',
            'date_here',
            'check_in',
            'check_out',
            'break_start',
            'break_end',
            'hour',
        ];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HrmAttendance extends Model
{
    protected $fillable = array('employee_id', 'shift_id', 'attended_date', 'attendance_type_id', 'organization_id', 'in_time', 'out_time', 'status');
}

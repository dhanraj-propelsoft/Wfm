<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HrmShift extends Model
{
    public function breaks() {
    	return $this->belongsToMany('App\HrmBreak', 'hrm_break_shift', 'shift_id', 'break_id');
    }

    public static function checkBreakExists($breaks, $shift_id) {
    	if(self::find($shift_id)->breaks()->where('id', $breaks)->first()) {
    		return true;
    	}
    	return false;
    }
}

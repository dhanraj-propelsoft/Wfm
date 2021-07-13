<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    public function voucher_type()
    {
        return $this->belongsToMany('App\AccountVoucherType', 'module_voucher', 'module_id', 'voucher_type_id');
    }

    public function hasVoucherType($id) {
    	if($this->voucher_type()->where('voucher_type_id', $id)->first()) {
			return true;     
		}
		return false;
	}
}

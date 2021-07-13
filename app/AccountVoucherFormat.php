<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountVoucherFormat extends Model
{
    public function separator() {
        return $this->belongsToMany('App\AccountVoucherSeparator', 'account_format_separator', 'format_id', 'separator_id');
    }

    public function format() {
        return json_decode($this->separator()->get(), true);
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountLedger extends Model
{
    public function person_type() {
        return $this->belongsToMany('App\AccountPersonType', 'ledger_person_types', 'ledger_id', 'person_type_id');
    }
}

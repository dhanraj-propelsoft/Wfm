<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountGroup extends Model
{
    public function ledger() {
        return $this->hasMany('App\AccountLedger', 'group_id');
    }

    public function hasLedger($ledger, $group_id) {
        if($this->find($group_id)->ledger()->where('name', $ledger)->first()) {
            return true;     
        }
        return false;
    }

    public function ledger_group() {
        return $this->belongsToMany('App\AccountLedgerType', 'account_ledgertype_group', 'group_id', 'ledger_type_id');
    }
}

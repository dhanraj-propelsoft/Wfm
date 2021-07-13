<?php

namespace App\Http\Controllers\Tradewms\Jobcard\Model;

use App\HrmEmployee;
use App\TransactionItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobCard extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function reference(){
        return $this->hasMany('App\Transaction','reference_id')->select(array('id', 'entry_id','reference_no','reference_id','order_no','transaction_type_id','organization_id','approval_status'));
    }

    public function accountVoucher(){
        return $this->belongsTo('App\AccountVoucher','transaction_type_id','id')->select(array('id', 'name','display_name','code','organization_id','voucher_type_id'));
    }

    public function jobCardDetail(){
        return $this->hasOne('App\Http\Controllers\Tradewms\Jobcard\Model\JobCardDetail','job_card_id');
    }

    public function transactionItem(){
        return $this->hasMany('App\Http\Controllers\Tradewms\Jobcard\Model\JobCardItem','job_card_id');
    }
    
    public function person(){
        return $this->belongsTo('App\Person','people_id');
    }

    public function business(){
        return $this->belongsTo('App\Business','people_id');
    }

    public function referencedIn()
    {
        return $this->morphMany('App\Transaction', 'originated_from');
    }
}


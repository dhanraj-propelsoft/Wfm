<?php

namespace App;
use App\HrmEmployee;
use App\TransactionItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

   /* public function transaction_employee(){

    	return $this->hasMany('App\HrmEmployee','id');
    }
    public function transaction_items(){
    	return $this->hasMany('App\TransactionItem','transaction_id');
    }*/

    public function reference(){
        return $this->hasMany('App\Transaction','reference_id')->select(array('id', 'entry_id','reference_no','reference_id','order_no','transaction_type_id','organization_id','approval_status'));
    }

    public function accountVoucher(){
        return $this->belongsTo('App\AccountVoucher','transaction_type_id','id')->select(array('id', 'name','display_name','code','organization_id','voucher_type_id'));
    }

    public function wmsTransaction(){
        return $this->hasOne('App\WmsTransaction','transaction_id');
    }

    public function transactionItem(){
        return $this->hasMany('App\TransactionItem','transaction_id');
    }
    
    public function person(){
        return $this->belongsTo('App\Person','people_id');
    }

    public function business(){
        return $this->belongsTo('App\Business','people_id');
    }
    
    /**
     * Get all of the models that own comments.
     */
    public function originatedFrom()
    {
        return $this->morphTo();
    }
    
    // public function scopeUser($query){
    //     return $query
    //           ->when($this->user_type && $this->user_type === '0',function($q){
    //               return $q->with('person');
    //          })
    //          ->when($this->type === '1',function($q){
    //             return $q->with('business');
    //        },function($q){
    //            return $q;
    //        });

    // }
}


<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class TransactionItem extends Model
{
    //
    protected $fillable = ['item_id','transaction_id','quantity','description','assigned_employee_id','start_time','end_time','job_item_status','duration'];



      /**
     * Set the start time.
     *
     * @param  string  $value
     * @return void
     */
    public function setStartTimeAttribute($value)
    {
        $this->attributes['start_time'] = $value?Carbon::parse($value)->format('Y-m-d H:i:s'):null;
        
    }

    //  /**
    //  * Set the start time.
    //  *
    //  * @param  string  $value
    //  * @return void
    //  */
    // public function getStartTimeAttribute($value)
    // {
        
    //     $this->attributes['start_time'] = $value?Carbon::parse($value)->format('d-m-Y H:i:s'):null;
    // }

}

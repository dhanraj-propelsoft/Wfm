<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WmsTransactionComplaintService extends Model
{
    protected $fillable = ['uuid','organization_id','service_group_name_type','service_group_name_id','service_status','additional_complaints','organization_id','created_by','transaction_id'];
}

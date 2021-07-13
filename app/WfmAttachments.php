<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WfmAttachments extends Model
{
    protected $fillable = ['attach_id','attach_type','upload_file','file_suffix','created_by'];
}

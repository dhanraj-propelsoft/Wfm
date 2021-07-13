<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WmsChecklist extends Model
{
    //
    protected $fillable=["transaction_id","checklist_id","checklist_status", "checklist_notes"];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WmsTransactionReading extends Model
{
    //
     protected $fillable=["transaction_id",
"reading_factor_id",
"reading_values",
"reading_notes"];
}

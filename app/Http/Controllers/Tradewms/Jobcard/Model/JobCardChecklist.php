<?php

namespace App\Http\Controllers\Tradewms\Jobcard\Model;

use Illuminate\Database\Eloquent\Model;

class JobCardChecklist extends Model
{
    //
    protected $fillable=["job_card_id","checklist_id","checklist_status", "checklist_notes","created_by","last_modified_by","created_at","updated_at"];

    /**
     * @param  array                                          $columns
     * @param  \Illuminate\Contracts\Support\Arrayable|array  $values
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public static function whereInMultiple(array $columns, $values)
    {
        $values = array_map(function (array $value) {
            return "('".implode($value, "', '")."')"; 
        }, $values);

        return static::query()->whereRaw(
            '('.implode($columns, ', ').') in ('.implode($values, ', ').')'
        );
    }
}

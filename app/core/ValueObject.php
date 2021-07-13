<?php
namespace App\Core;

use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;


abstract class ValueObject
{

    public $pId;
    
    public $pOrganizationId;
    
    public $pCreatedBy;
    public $pCreatedAt;
    
    public $pLastModifiedBy;
    public $pLastModifiedAt;
    
    public $pDeletedBy;
    public $pDeletedAt;
    
    /**
     * Create a base value object instance.
     *
     * @param  object model
     * @return void
     */
    public function __construct($model = false)
    {
       // Log::channel('daily_data')->debug('ValueObject:> '.json_encode($model) );

        if ($model == false) {
            
            $this->pId = '';
            
            //$this->pOrganizationId = Session::get('organization_id');
            
            $this->pCreatedBy='';
            $this->pCreatedAt='';
            
            $this->pLastModifiedBy='';
            $this->pLastModifiedAt='';

            $this->pDeletedBy='';
            $this->pDeletedAt='';
            
        } else {
            
            if (Schema::connection($model->getConnection()->getName())->hasColumn($model->getTable(), 'id')){
                $this->pId  =   (isset($model->id)) ? $model->id : '';
            }
            
//             if (Schema::connection($model->getConnection()->getName())->hasColumn($model->getTable(), 'organization_id')){
//                 $this->pOrganizationId =    (isset($model->organization_id)) ? $model->organization_id : pAuthOrganizationId();
//             }
            
            if (Schema::connection($model->getConnection()->getName())->hasColumn($model->getTable(), 'created_by')){
                $this->pCreatedBy = (isset($model->created_by)) ? $model->created_by : '';
            }

            if (Schema::connection($model->getConnection()->getName())->hasColumn($model->getTable(), 'created_at')){
                $this->pCreatedAt = (isset($model->created_at)) ? $model->created_at->format(config('app.date_format_model')) : '';
            }
            
            if (Schema::connection($model->getConnection()->getName())->hasColumn($model->getTable(), 'last_modified_by')){
                $this->pLastModifiedBy = (isset($model->last_modified_by)) ? $model->last_modified_by : '';
            }

            if (Schema::connection($model->getConnection()->getName())->hasColumn($model->getTable(), 'updated_at')){
                $this->pLastModifiedAt = (isset($model->updated_at)) ? $model->updated_at->format(config('app.date_format_model')) : '';
            }

            if (Schema::connection($model->getConnection()->getName())->hasColumn($model->getTable(), 'deleted_by')){
                $this->pDeletedBy = (isset($model->deleted_by)) ? $model->deleted_by : '';
            }
            
            if (Schema::connection($model->getConnection()->getName())->hasColumn($model->getTable(), 'deleted_at')){
                $this->pDeletedAt = (isset($model->deleted_at)) ? $model->deleted_at->format(config('app.date_format_model')) : '';
            }
            
        }
    }
    
}

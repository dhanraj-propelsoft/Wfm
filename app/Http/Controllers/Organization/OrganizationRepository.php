<?php

namespace App\Http\Controllers\Organization;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Session;
use App\Person;
use App\People;
use App\AccountPersonType;
use App\PeoplePersonType;
use App\Custom;
use App\Organization;


class OrganizationRepository 
{
    
    public function SwitchOrg($request)
    {    
        //  Log::info('OrganizationRepository->SwitchOrg:-Inside '.json_encode($request->id));


        // $status = $request->status;

        // $result = collect($request->id)->map(function ($model) use ($status) {
           
        //         $update = Category::findOrFail($model['pId']);
        //         $update->status = $status;
        //         $update->save();

        //         return $update;
            
        // });
        
        
        // Log::info('OrganizationRepository:-Return '. json_encode($result));

       return $result;
    
    }
}

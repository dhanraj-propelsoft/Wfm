<?php

namespace App\Http\Controllers\Wfm\Repository;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Organization;
use App\Http\Controllers\Wfm\Repository\DashboardRepositoryInterface;
use App\Http\Controllers\Wfm\Model\Task;
use Illuminate\Support\Facades\Log;
use Session;
use DB;


class OrganizationRepository 
{
  


    public function SwitchOrg($data)
    {    
      Log::info('OrganizationRepository->SwitchOrg:-Inside '.json_encode($data->otherorg));

            $res = collect($data->otherorg)->map(function ($model)  {

           
                    $update = Organization::findOrFail($model['id']);
                    $update->is_active = 0;
                    $update->save();

                    return $update;   
            });


                  $futureorg = Organization::findOrFail($data->currentorg);
                  $futureorg->is_active = 1;
                  $futureorg->save();
                
            return ['status' => 1, 
                    'message' => "Switch Organization is Suceessfully", 
                    'data' =>$futureorg
                  ];
    }


   
}

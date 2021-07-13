<?php

namespace App\Http\Controllers\Vehicle;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Vehicle\VehicleRepositoryInterface;

use Illuminate\Support\Facades\DB;
use App\VehicleRegisterDetail;
use App\VehicleVariant;
use App\VehiclePermit;
use App\VehicleCategory;
use Session;
use App\Custom;
use App\WmsVehicleOrganization;
use App\ServiceType;

class VehicleRepository implements VehicleRepositoryInterface
{

  public function findAll_API($request)
  {
    Log::info("VehicleRepository->findAll_API :- Inside ");
    $request = (object) $request;
    Log::info("VehicleRepository->findAll_API :- Inside Data - ".json_encode($request));
    
    $organization_id = $request->org_id;
    $offset = $request->page;
    $limit = $request->per_page;

    $vehicles_register = VehicleRegisterDetail::select('vehicle_register_details.id', 'vehicle_register_details.registration_no', 'vehicle_variants.vehicle_configuration','people.mobile_no as customer_mobile',DB::raw("IF(people.display_name IS NULL, business.display_name, people.display_name) as customer"),DB::raw('(CASE WHEN vehicle_register_details.user_type = 0  THEN person_communication_addresses.mobile_no ELSE business_communication_addresses.mobile_no END) AS customer_mobile'));
    $vehicles_register->leftJoin('people', function($join) use($organization_id)
        {
            $join->on('people.person_id','=', 'vehicle_register_details.owner_id')
            ->where('people.organization_id', $organization_id)
            ->where('vehicle_register_details.user_type', '0');
        });
    $vehicles_register->leftJoin('person_communication_addresses', function($join) use($organization_id)
        {
            $join->on('person_communication_addresses.person_id','=', 'vehicle_register_details.owner_id')
         
            ->where('vehicle_register_details.user_type', '0');
        });

    $vehicles_register->leftJoin('people AS business', function($join) use($organization_id)
        {
            $join->on('business.business_id','=', 'vehicle_register_details.owner_id')
            ->where('business.organization_id', $organization_id)
            ->where('vehicle_register_details.user_type', '1');
    });

    $vehicles_register->leftJoin('business_communication_addresses', function($join) use($organization_id)
    {
        $join->on('business_communication_addresses.business_id','=', 'vehicle_register_details.owner_id')
       
        ->where('vehicle_register_details.user_type', '1')
        ->where('business_communication_addresses.address_type', '1');
});

    $vehicles_register->leftJoin('vehicle_variants', 'vehicle_variants.id','=','vehicle_register_details.vehicle_variant_id');

    $vehicles_register->leftjoin('wms_vehicle_organizations','wms_vehicle_organizations.vehicle_id','=','vehicle_register_details.id');

    $vehicles_register->where('vehicle_register_details.status', '1');

    $vehicles_register->where('wms_vehicle_organizations.organization_id', $organization_id);

    $vehicles_register->orderby('vehicle_register_details.id','DESC');
    
    $vehicles_register->skip($offset*$limit);
    
    $vehicles_register->take($limit);

    $data = $vehicles_register->get();
    
    Log::info("VehicleRepository->findAll_API :- Return ");
    return $data;

  }
  
  public function findAllVehicleByOrgId($orgId){
    
    Log::info("VehicleRepository->findAllVehicleByOrg :- Inside ");
    $query = VehicleRegisterDetail::query();

    if($orgId){
      $query->whereHas('vehicleOrgAssoc', function ($query) use ($orgId) {
        $query->where(['organization_id' => $orgId]);
      });
        
    }
    Log::info("VehicleRepository->findAllVehicleByOrg :- Return ");
    return $query->get(); 
  }
  public function findByVehicleNo($vehicleNo,$organization_id = false)
  {
    
    Log::info("VehicleRepository->findByVehicleNo :- Inside ");
   
    $query = VehicleRegisterDetail::with('vehicleVariant','vehicleCategory','vehicleOrgAssoc')->where(['registration_no' => $vehicleNo]);
    
    if($organization_id){
      $query->whereHas('vehicleOrgAssoc', function ($query) use ($organization_id) {
        $query->where(['organization_id' => $organization_id]);
      });
        
    }
    
    Log::info("VehicleRepository->findByVehicleNo :- Query : -".$query->toSql());  
    Log::info('VehicleRepository->findByVehicleNo :- QueryBinding '.json_encode($query->getBindings()));
    Log::info("VehicleRepository->findByVehicleNo :- Return ");
    return $query->first();

  }

  public function findById($id,$organization_id = false)
  {
    
    Log::info("VehicleRepository->findById :- Inside ");
   
    $query = VehicleRegisterDetail::with('vehicleVariant','vehicleCategory','vehicleOrgAssoc')->where(['id' => $id]);
    if($organization_id){
      $query->whereHas('vehicleOrgAssoc', function ($query) use ($organization_id) {
        $query->where(['organization_id' => $organization_id]);
      });
     }
    
    Log::info("VehicleRepository->findById :- Query : -".$query->toSql());  
    Log::info('MemberDetailRepository->findById:- QueryBinding '.json_encode($query->getBindings()));
    Log::info("VehicleRepository->findById :- Return ");
    return $query->first();

  }

  public function findAllVehicleVariant()
  {
    
    Log::info("VehicleRepository->findAllVehicleVariant :- Inside ");
    $query = VehicleVariant::orderby('vehicle_configuration');
    //$query = VehicleVariant::distinct();
   // Log::info("VehicleRepository->findAllVehicleVariant :- Query : -".$query->toSql());  
  //  Log::info('MemberDetailRepository->findAllVehicleVariant:- QueryBinding '.json_encode($query->getBindings()));
    Log::info("VehicleRepository->findAllVehicleVariant :- Return ");
    return $query->get();
  }

  public function findAllVehiclePermit()
  {
    
    Log::info("VehicleRepository->findAllVehiclePermit :- Inside ");
    $query = VehiclePermit::get();
    Log::info("VehicleRepository->findAllVehiclePermit :- Return ");
    return $query;
  }



  public function findVehicleVariantById($id,$isPluck = false)
  {
    
    Log::info("VehicleRepository->findAllVehicleVariant :- Inside ");
    $query = VehicleVariant::orderby('vehicle_configuration')->where('id',$id);
    Log::info("VehicleRepository->findAllVehicleVariant :- Return ");
    if($isPluck){

      return $query->pluck('vehicle_configuration', 'id');
    }else{
      return $query->first();
    }
  }
  public function findVehiclePermitById($id, $isPluck = false)
  {
    Log::info("VehicleRepository->findVehiclePermitById :- Inside ");
    $query = VehiclePermit::where('id',$id);  
    Log::info("VehicleRepository->findVehiclePermitById :- Return ");
    if($isPluck){
      return $query->pluck('name', 'id');
    }else{
      return $query->first();
    }
  }

  public function isVehicleExistInOrganization($vehicleId,$orgId)
  {
    Log::info("VehicleRepository->isVehicleExistInOrganization :- Inside ");
    $query = WmsVehicleOrganization::where(['organization_id'=>$orgId,"vehicle_id"=>$vehicleId])->exists();
    Log::info("VehicleRepository->isVehicleExistInOrganization :- Return ");
    return $query;
  }

  public function findVehicleCategoryById( $id){
    Log::info("VehicleRepository->findVehicleCategoryById :- Inside ");
    $vehicleVarient = VehicleVariant::where('id',$id)->first();
	$data = VehicleCategory::where('id', $vehicleVarient->category_id)->first();
    Log::info("VehicleRepository->findVehicleCategoryById :- Return ");
    return  $data;
  }

  public function findVehicleServiceType($isPluck = false){
    $data = ServiceType::where('status', '1')->orderBy('name')->get();
    if($isPluck){
    $data  = $data->pluck('display_name', 'id');
    }
    return $data;
  }


  public function saveVehicle( $model, $vehicleOrgAssoc = false){

    Log::info('VehicleRepository->saveVehicle :- Inside');

    try {

        $result = DB::transaction(function () use ($model, $vehicleOrgAssoc) {
          
            $model->save();

            // Update created By and Last modified fields
            Custom::userby($model, true);
            
            Log::info('VehicleRepository->saveVehicle :- Save  - '.json_encode($model));
            
            if($vehicleOrgAssoc){
              $model->vehicleOrgAssoc()->save($vehicleOrgAssoc);
              Log::info('VehicleRepository->saveVehicle :- Save2  - '.json_encode($vehicleOrgAssoc)); 
            }
                 
         //   Custom::userby($model->vehicleOrgAsso, true);
            

            return [
                'message' => pStatusSuccess(),
                'data' => $model
            ];
        });
        Log::info('VehicleRepository->saveVehicle :- Return try - ');
        return $result;
    } catch (\Exception $e) {
        Log::error('VehicleRepository->saveVehicle :- Return catch Error '.json_encode($e));
        return [
            'message' => pStatusFailed(),
            'data' => $e
        ];
    }


  }

 // $config = VehicleVariant::where('id',$request->input('vehicle_config'))->first();
 public function saveVehicleOrgAssoc($model){

  Log::info('VehicleRepository->saveVehicleOrgAssoc :- Inside');

  try {

      $result = DB::transaction(function () use ($model) {
        
          $model->save();

          // Update created By and Last modified fields
          Custom::userby($model, true);
          
          Log::info('VehicleRepository->saveVehicleOrgAssoc :- Save  - '.json_encode($model));
          return [
              'message' => pStatusSuccess(),
              'data' => $model
          ];
      });
      Log::info('VehicleRepository->saveVehicleOrgAssoc :- Return try - ');
      return $result;
  } catch (\Exception $e) {
      Log::error('VehicleRepository->saveVehicleOrgAssoc :- Return catch Error '.json_encode($e));
      return [
          'message' => pStatusFailed(),
          'data' => $e
      ];
  }


}

}

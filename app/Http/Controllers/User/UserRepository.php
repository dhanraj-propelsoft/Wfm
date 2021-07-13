<?php

namespace App\Http\Controllers\User;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Session;
use App\Person;
use App\People;
use App\AccountPersonType;
use App\PeoplePersonType;
use App\BusinessCommunicationAddress;
use App\PersonCommunicationAddress;
use App\Custom;
use App\Business;

class UserRepository implements UserRepositoryInterface
{

  public function findPersonById($id,$organization_id = false)
  {
    
    Log::info("UserRepository->findPersonById :- Inside ");
    
    $query = Person::with('address')->where(['id' => $id]);
    
    if($organization_id){
        $query->where(['organization_id' => $organization_id]);
    }
    
    Log::info("UserRepository->findPersonById :- Query : -".$query->toSql());  
    Log::info('UserRepository->findPersonById :- QueryBinding '.json_encode($query->getBindings()));
    Log::info("UserRepository->findPersonById :- Return ");
    return $query->first();

  }

  public function findPersonAssocDetailByMobile($mobileNumber){
  
  Log::info("UserRepository->findPersonAssocDetailByMobile :- Inside ");
    
  $data = Person::with('personOrgAssoication.PeoplePersonType.accountType', 'address.city')->
  whereHas('address', function ($query) use ($mobileNumber) {
      $query->where('mobile_no', $mobileNumber);
  });
  Log::info('UserRepository->findPersonAssocDetailByMobile:-query ... ' . $data->toSql());
  $data = $data->get();

  Log::info('JobCardService->findCustomerByMobile:-count' . count($data));
   
  Log::info("UserRepository->findPersonAssocDetailByMobile :- Return ");
  return $data;
  
  }
  public function findPersonCommunicationByPersonId($personId){
    Log::info("UserRepository->findPersonCommiuncationByPersonId :- Inside ");
    $data = PersonCommunicationAddress::where('person_id',$personId);
    Log::info("UserRepository->findPersonCommiuncationByPersonId :- Return ");
    return $data->first();
  }

  public function findBusinessAssocDetailByMobile( $mobileNumber){
    Log::info("UserRepository->findBusinessAssocDetailByMobile :- Inside ");
    
    $data = Business::with('address.city', 'businessOrgAssociation.PeoplePersonType.accountType')->whereHas('address', function ($query) use ($mobileNumber) {
      $query->where('mobile_no', $mobileNumber);
    });
    Log::info('UserRepository->findBusinessAssocDetailByMobile:-query ... ' . $data->toSql());
    $data = $data->get();
    // dd($data);
    Log::info('UserRepository->findBusinessAssocDetailByMobile:-count' . count($data));
    Log::info("UserRepository->findBusinessAssocDetailByMobile :- Return ");
    return $data;
  }
  public function findBusinessById($id){
    Log::info("UserRepository->findBusinessById :- Inside ");
    $query = Business::find($id);
    Log::info("UserRepository->findBusinessById :- Return ");
    return $query;
  }

  public function findBusinessCommunicationByBusinessId( $businessId){
    Log::info("UserRepository->findBusinessCommunicationByBusinessId :- Inside ");
    $query = BusinessCommunicationAddress::where('business_id',$businessId);
    Log::info("UserRepository->findBusinessCommunicationByBusinessId :- Return ");
    return $query->first();
  }

  public function savePerson($model,$commModel){
    
    Log::info('UserRepository->savePerson :- Inside');

    try {

        $result = DB::transaction(function () use ($model, $commModel) {

            $model->save();

            $model->address()->save($commModel);

            // Update created By and Last modified fields
            Custom::userby($model, true);
            Custom::userby($model->address, true);


            return [
                'message' => pStatusSuccess(),
                'data' => $model
            ];
        });
        Log::info('UserRepository->savePerson :- Return try - ');
        return $result;
    } catch (\Exception $e) {
        Log::error('UserRepository->savePerson :- Return catch Error '.json_encode($e));
        return [
            'message' => pStatusFailed(),
            'data' => $e
        ];
    }

  }

}

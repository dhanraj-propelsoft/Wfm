<?php

namespace App\Http\Controllers\People;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Session;
use App\Person;
use App\People;
use App\AccountPersonType;
use App\PeoplePersonType;
use App\Custom;

class PeopleRepository implements PeopleRepositoryInterface
{

  public function findAccountPersonTypeByName($name)
  {
    
    Log::info("PeopleRepository->findAccountPersonTypeByName :- Inside ");
    
    $person_type = AccountPersonType::where('name',$name)->first();
    Log::info("PeopleRepository->findAccountPersonTypeByName :- Return ");
    return $person_type;

  }

  public function isExistCustomer($peopleId)
  {
    
    Log::info("PeopleRepository->isExistCustomer :- Inside ");
    
    $query = PeoplePersonType::where('people_id',$peopleId)
    ->where('person_type_id',2)
    ->exists();
    Log::info("PeopleRepository->isExistCustomer :- Return ");
    return $query;

  }

  public function isExistPeopleByPersonIdAndOrgId($id,$organization_id)
  {
    
    Log::info("PeopleRepository->isExistPeopleByPersonIdAndOrgId :- Inside ");
    
    $query = People::where(['person_id' => $id,'organization_id' => $organization_id])->first();

    Log::info("PeopleRepository->isExistPeopleByPersonIdAndOrgId :- Return ");
    return $query;

  }

  public function isExistPeopleByBusinessIdAndOrgId($id,$organization_id)
  {
    
    Log::info("PeopleRepository->isExistPeopleByBusinessIdAndOrgId :- Inside ");
    
    $query = People::where(['business_id' => $id,'organization_id' => $organization_id])->first();

    Log::info("PeopleRepository->isExistPeopleByBusinessIdAndOrgId :- Return ");
    return $query;

  }

  public function findById($id){
    Log::info("PeopleRepository->findById :- Inside ");
    $query = People::find($id);
    Log::info("PeopleRepository->findById :- Return ");
    return $query;
    
  }

  public function findPeople($orgId,$userType,$id)
  {
    Log::info("PeopleRepository->findPeople :- Inside ");
    $query =  People::with('PeoplePersonType')->where(['organization_id' => $orgId]);

              // find customer
            //   ->whereHas('PeoplePersonType', function ($q)  {
            //     $q->where('person_type_id',2);

            // });

    if($userType == "0"){
      $query->where('person_id',$id);
    } else if($userType == "1"){
      $query->where('business_id',$id);
    }
    Log::info("PeopleRepository->findPeople :- Return ");
    return $query->first();
  }



  public function savePeople($model,$accPersonTypeModel = false){
    
    Log::info('PeopleRepository->savePeople :- Inside');

    try {

        $result = DB::transaction(function () use ($model, $accPersonTypeModel) {

            $model->save();
            
             // Update created By and Last modified fields
            Custom::userby($model, true);
          
            if($accPersonTypeModel){
          //  $model->address()->save($commModel);
               $model->PeoplePersonType()->saveMany([$accPersonTypeModel]);
            }

            return [
                'message' => pStatusSuccess(),
                'data' => $model
            ];
        });
        Log::info('PeopleRepository->savePeople :- Return try - ');
        return $result;
    } catch (\Exception $e) {
        Log::error('PeopleRepository->savePeople :- Return catch Error '.json_encode($e));
        return [
            'message' => pStatusFailed(),
            'data' => $e
        ];
    }

  }

  public function savePeopleAccountType($model){
    
    Log::info('CustomerRepository->savePeopleAccountType :- Inside');

    try {

        $result = DB::transaction(function () use ($model) {

            $model->save();

            return [
                'message' => pStatusSuccess(),
                'data' => $model
            ];
        });
        Log::info('CustomerRepository->savePeopleAccountType :- Return try - ');
        return $result;
    } catch (\Exception $e) {
        Log::error('CustomerRepository->savePeopleAccountType :- Return catch Error '.json_encode($e));
        return [
            'message' => pStatusFailed(),
            'data' => $e
        ];
    }

  }
}

<?php

namespace App\Http\Controllers\Vehicle;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Vehicle\VehicleRepository;
use App\Http\Controllers\Tradewms\Jobcard\JobCardRepository;
use App\WmsTransaction;
use App\VehicleCategory;
use App\VehiclePermit;
use App\VehicleVariant;
use Auth;
use Session;
use App\City;
use DB;
use App\Enums\BankLoan;
use App\Custom;
use App\State;
use  App\Http\Controllers\Vehicle\VehicleDetailVO;
use App\Http\Controllers\People\CustomerDetailVO;
use App\Http\Controllers\People\PeopleRepository;

class VehicleService
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */

	public function __construct(VehicleRepository $repo,JobCardRepository $jobcardRepo,PeopleRepository $peopleRepo)
	{
		$this->repo = $repo;
		$this->jobcardRepo = $jobcardRepo;
		$this->peopleRepo = $peopleRepo;
	}



	public function findVehicleDetail($vehicleNo = false,$vechicleId = false , $isReturn = false, $transactionId =  false)
	{
		Log::info("VehicleService->findVehicleDetaiel :- Inside ");
		Log::info('registration_no ' . $vehicleNo);
		
		$organization_id =  Session::get('organization_id');

		$vehicleNewToOrg = false;
		if($vehicleNo ){

			$vehicleRegisterDetail = $this->repo->findByVehicleNo($vehicleNo,$organization_id);
			
		}else if($vechicleId){
			$vehicleRegisterDetail = $this->repo->findById($vechicleId,$organization_id );
		}
		
	
		//if not found for the logged in org, check vehicle across orgs.
		if (!$vehicleRegisterDetail){
		    $vehicleRegisterDetail = $this->repo->findByVehicleNo($vehicleNo);
		    if ($vehicleRegisterDetail){
		      $vehicleNewToOrg = true;
		    }
		}


		/* ENUMS */
		// convert object to array
		$bankLoanArray = array();
		$obj = BankLoan::getInstances();
		foreach ($obj as &$value) {
			$bankLoanArray[$value->key] = $value->value;
		}

		 // vehicle doesn't exist in system
		if (!$vehicleRegisterDetail) {
			$vehicleConfigList = $this->repo->findAllVehicleVariant()->pluck('vehicle_configuration', 'id');

			$permitTypeList = $this->repo->findAllVehiclePermit()->pluck('name', 'id');

			return [
				'status' => 0, 'message' => 'Vehicle not found. Please check number again or fill required fields below to register.',
				'data' => [
					'configurationList' => $vehicleConfigList,
					'permitTypeList' => $permitTypeList,
					'bankLoanList' => $bankLoanArray
				]
			];
		} else {
			// get last job card for  vehicle in logged in organization
			$jobCardDate = '';
			$jobCardOrderNo = '';
			$jobcardId = '';
			$encryptedURL = '';
			
			if(!$vehicleNewToOrg){
				$vehicleId = $vehicleRegisterDetail->id;
				$lastJobcardEntity = $this->jobcardRepo->findLastJobcard($vehicleId,$transactionId);
				if($lastJobcardEntity){
    				$jobCardDate =  $lastJobcardEntity->job_date;
    				$jobCardOrderNo =  $lastJobcardEntity->order_no;
					$jobcardId = $lastJobcardEntity->id;
					$encryptedURL = generateEncryptedURL(url('job_card_acknowledgement/'),$jobcardId);
				}
			}

			/*  Pluck return the key value pair purpose of dropdown */
			// get  vehicleVariant by vehicle Config 
			$vehicleConfigList = $this->repo->findVehicleVariantById($vehicleRegisterDetail->vehicle_configuration_id, $isPluck = true);


			if($vehicleRegisterDetail->permit_type){
				// true pass the params its return the array format id name pair
				$permitTypeList = $this->repo->findVehiclePermitById($vehicleRegisterDetail->permit_type, $isPluck = true);
			}else{
				$permitTypeList = $this->repo->findAllVehiclePermit()->pluck('name', 'id');
			}
			
			// get owner detail
			$owner = $vehicleRegisterDetail->owner;
			$people = $this->peopleRepo->findPeople($organization_id,$vehicleRegisterDetail->user_type,$owner->id);
			//dd($vehicleRegisterDetail);
				//dd($people);
			
			// if owner address doesn't exist, return empty state
			$customerStateId = "";
			$customerCityId = "";
			$customerActiveStateArray = "";
			$customerActiveCityArray = "";
			$states = "";

			
			
			$stateList = Custom::getStateByCountryId()->pluck('name','id');

			// owner address doesn't exist , set all states to dropdown
			if($owner->address){
				$customerStateId = ($vehicleRegisterDetail->user_type == 0) ? $owner->address->city->state_id : $owner->address->city->state_id;
				$customerCityId = ($vehicleRegisterDetail->user_type == 0) ? $owner->address->city->id : $owner->address->city->id;
				$customerActiveStateArray = [$customerStateId => $stateList[$customerStateId]];
				$customerActiveCityArray = Custom::getCityById($customerCityId, $isPluck = true);
			}else{
				$states = $stateList;
			}
			
			 // defalut get the india states
	
			//dd($states);
			
			$data = $this->convertToVO($vehicleRegisterDetail,$vehicleRegisterDetail->user_type,$organization_id,$people,$permitTypeList,$vehicleConfigList,$bankLoanArray,$customerActiveStateArray,$customerActiveCityArray,$states);
		
			
				if($isReturn){


					return ['vehicleWithCustomerDetail'=>$data,'lastJobCardDetail'=>[ 'id' => $jobcardId,'lastUpdateDate' => $jobCardDate,	'lastUpdateJC' => $jobCardOrderNo,'encryptedURL' => $encryptedURL]];

				}else{

					return ['status' => 1, 'data' => ['vehicleWithCustomerDetail'=>$data,'states'=> $stateList, 'lastJobCardDetail'=>['lastUpdateDate' => $jobCardDate,	'lastUpdateJC' => $jobCardOrderNo]]];
				}

			
	 }
	}



	public function findVehicleCatgoryById($id)
	{
		Log::info("VehicleService->findVehicleCatgoryById :- Inside ");
		$data = $this->repo->findVehicleCategoryById($id);
		Log::info("VehicleService->findVehicleCatgoryById :- Return ");
		return ['status' => pStatusSuccess(), 'data' => $data];
	}

	public function convertToVO($data,$userType = false, $organization_id = false, $people = false ,$permitTypeArray = false, $ConfigArray = false, $bankLoanArray = false,$customerActiveStateArray = false,$customerActiveCityArray =  false,$states = false)
	{
		Log::info("VehicleService->convertToVO :- Inside ");
		$customerDetail = $this->convertToCustomerDetailVO($data->owner,$userType,$organization_id,$people,false,$customerActiveStateArray,$customerActiveCityArray,$states);
		$vo = new  VehicleDetailVO($data ,$permitTypeArray , $ConfigArray , $bankLoanArray, $customerDetail );
		Log::info("VehicleService->convertToVO :- Return ");
		return $vo;

	}

	public function convertToCustomerDetailVO($data, $userType = false, $organization_id = false,$person = false, $assoicatedType = false,$activeStateDropdown = false,$activeCityDropdown= false ,$states = false)
	{
		
		$vo = new CustomerDetailVO($data,$userType,$organization_id,$person,$assoicatedType,$activeStateDropdown,$activeCityDropdown,$states);
		// Log::info("JobCardService->convertToVO :- return ");
		return $vo;
	}


}

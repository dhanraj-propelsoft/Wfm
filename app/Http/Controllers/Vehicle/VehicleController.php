<?php

namespace App\Http\Controllers\Vehicle;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;


use Session;
use Mail;
use Auth;
use DB;
use PDF;
use File;
use Validator;
use Storage;
use Input;



class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
	protected $jobCardRepo;
	public function __construct(VehicleService $serv)
    {
        $this->serv = $serv;
    }

   public function index(request $request)
    {
       
		Log::info('VehicleController->index:-Inside ');
// 		$entities = $this->serv->findAll($request->all());
		Log::info('VehicleController->index:- Return ');
// 		return $entities;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Log::info('VehicleController->Create:-Inside ');
        Log::info('VehicleController->Create:-End ');
//         return view('trade_wms.jobcard.JobCardDetail.JobCard-Detail');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    
            Log::info("VehicleController->store :- Inside ");
//             $store_return = $this->store_transaction($request,"store");
            Log::info("VehicleController->store :- Return ");
//             return $store_return;
        
    
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
		
	   //dd("edit");
		Log::info('VehicleController->show:-Inside ');
// 		$entities = $this->serv->findById($id);
		
		Log::info('VehicleController->show:-Return ');
// 		return view('trade_wms.jobcard.JobCardDetail.JobCard-Detail',compact('id'));
		
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
          Log::info('VehicleController->Edit:-Inside Anitha');

          Log::info('VehicleController->Edit:-End ');


      }

   

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Log::info("VehicleController->update :- Inside ");
//         $store_return = $this->store_transaction($request,"update");
        Log::info("VehicleController->update :- Return ");
//         return $store_return;
    }

    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }



    public function findVehicleDetail($vehicleNo)
    {
        Log::info('VehicleController->findVehicleDetail:-Inside ');
        $response = $this->serv->findVehicleDetail($vehicleNo);
        Log::info('VehicleController->findVehicleDetail:-Return ');

        return response()->json($response);
      
    }

    public function findVehicleCatgoryById($id)
    {
        Log::info('VehicleController->findVehicleCatgoryById:-Inside ');
		$response = $this->serv->findVehicleCatgoryById($id);
		Log::info('VehicleController->findVehicleCatgoryById:- Return ');
        return response()->json($response);
    }
}

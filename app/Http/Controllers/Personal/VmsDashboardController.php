<?php

namespace App\Http\Controllers\Personal;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\VehicleRegisterDetail;
use App\Transaction;
use Carbon\Carbon;
use Auth;
use DB;


class VmsDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Auth::user()->id;

         $person_id = Auth::user()->person_id;

        
         $organizations = DB::table('organization_person')->where('person_id',$person_id)->first();

         $organization_id = $organizations->organization_id;

         $business = DB::table('organizations')->where('id',$organization_id)->first();       
        
         $business_id = $business->business_id ;
      

        $vehicles_register = VehicleRegisterDetail::select('vehicle_register_details.registration_no', 'vehicle_register_details.is_own', 'people.first_name as customer');
      
        

        $vehicles_register->leftJoin('people', function($join) use($organization_id)
            {
                $join->on('people.person_id','=', 'vehicle_register_details.owner_id')
                ->where('people.organization_id', $organization_id)
                ->where('vehicle_register_details.user_type', '0');
            });

        
       $vehicles_register->leftJoin('vehicle_configurations', 'vehicle_configurations.id','=','vehicle_register_details.vehicle_configuration_id');

        

        $vehicles_register->leftJoin('vehicle_categories', 'vehicle_categories.id','=','vehicle_register_details.vehicle_category_id');

        
        $vehicles_register->leftJoin('vehicle_variants', 'vehicle_variants.id','=','vehicle_register_details.vehicle_variant_id');
      
        // $vehicles_register->leftJoin('organizations', 'organizations.id','=','vehicle_register_details.organization_id');
     
        // $vehicles_register->where('organizations.business_id',$business_id);
      

        // $vehicles_register->where('vehicle_register_details.organization_id', $organization_id);
        $vehicles_register->where('vehicle_register_details.owner_id',$business_id);
        $vehicles_register->where ('people.first_name','!=' ,NULL);

        $vehicles_register->orderby('vehicle_register_details.id');
          $vehicles_register->groupby('vehicle_register_details.registration_no');

        $vehicles_registers = $vehicles_register->get();
        //dd(  $vehicles_registers );

        $vehicle_count = count($vehicles_registers);

        return view('personal.vms_dashboard' , compact('vehicles_registers', 'vehicle_count'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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
}

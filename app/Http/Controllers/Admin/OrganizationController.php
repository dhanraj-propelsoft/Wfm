<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Organization;
use App\OrganizationPackage;
use App\ Custom;
use App\SubscriptionPlan;
use Carbon\Carbon;
use App\Gst;
use DB;

class OrganizationController extends Controller
{
    public function index()
    {
        $organizations = Organization::select('organizations.id', 'organizations.name', 'organization_packages.status', 'organizations.is_active', 'organizations.business_id', 'businesses.bcrm_code AS propel_id', DB::raw('DATE_FORMAT(organizations.created_at, "%d %M, %Y") AS started_date'), DB::raw('DATE_FORMAT(organization_packages.expire_on, "%d %M, %Y") AS expire_on'),'organization_packages.expire_on as expiredate' )->leftjoin('organization_packages','organization_packages.organization_id','=','organizations.id')
          ->leftjoin('businesses','businesses.id','=','organizations.business_id')

                ->where('organization_packages.status', 1)
		->orWhere(function ($query) {
		    $query->where('organization_packages.status', '=', 0)
		          ->whereNull('organization_packages.subscription_id');
		})
        ->orderby('organizations.name')
        ->groupby('organizations.id')
        ->get();
     //dd($organizations);
      //  $expiredate=$organizations->expiredate;
 // $effectiveDate = date('Y-m-d', strtotime("+1 months", strtotime($expiredate)));
   //dd($effectiveDate);     
      return view('admin.organizations', compact('organizations'));
    }
      public function organization_create($id)
    {
        // dd($id);
  $organization= Organization::select('id')

       
        ->where('id',$id)->first();
       

         return view('admin.Organization_Edit',compact('organization'));
    }
     public function status(Request $request){
// var_dump($request->all());
// die("ok");
        if($request->input('status')==="1")
        {
            $UpdateData=['status' => $request->input('status')];
        }else{
            $UpdateData=['status' => $request->input('status')];
        }
        organizationpackage::where('id', $request->input('id'))->update($UpdateData);
        return response()->json(array('result' => "success",'status'=>$UpdateData));
        }
//     public function status(Request $request)
//     {
//         //dd($request->all());
//         if($request->input('status')==="1")
//         {
//             $UpdateData=['status' => $request->input('status'),'expire_on'=>DB::raw('DATE_ADD(expire_on, INTERVAL 1 MONTH)')];
//         }else{
//             $UpdateData=['status' => $request->input('status')];
//         }
//         OrganizationPackage::where('id', $request->input('id'))->update($UpdateData);
//         $expiredate=OrganizationPackage::select(DB::raw('DATE_FORMAT(organization_packages.expire_on, "%d %M, %Y") AS expire_on'))->where('id', $request->input('id'))->first()->expire_on;
//               return response()->json(array('result' => "success",'expire_on'=>$expiredate));
//     }
// //      public function organization_extendcheck(Request $request) {
// // dd($request->all());     
// //         $categoryname = GlobalItemMainCategory::where('category_type_id', $request->types)
// //         ->where('name',$request->categoryname)
// //         ->first();
// //         if(!empty($categoryname->id)) {
// //             echo 'false';
// //         } else {
// //             echo 'true';
// //         }
// //     }

    public function organization_extend(Request $request){

      $to_date = Carbon::parse($request->input('expire_date'))->format('Y-m-d h:m:s');
        

          $UpdateData=['expire_on' => $to_date];
 
       
        OrganizationPackage::where('organization_id', $request->input('id'))->update($UpdateData);

         $expiredate=OrganizationPackage::select(DB::raw('DATE_FORMAT(organization_packages.expire_on, "%d %M, %Y") AS expire_on'))->where('organization_id', $request->input('id'))->first()->expire_on;
              
              return response()->json(array('result' => "success",'expire_on'=>$expiredate));
       
        }

    public function plan_ledgers()
    {
        $ledgers = OrganizationPackage::select('addons.display_name','organizations.business_id','organizations.id','organizations.name AS company_name','packages.display_name AS package', 'subscription_plans.display_name AS plan', 'subscription_plans.name AS plan_name',  'businesses.bcrm_code AS propel_id', DB::raw('DATE_FORMAT(organization_packages.added_on, "%b %d, %Y") AS added_on'),  DB::raw('DATE_FORMAT(organization_packages.expire_on, "%b %d, %Y") AS expire_on'),'addon_organization.used','addon_organization.value')
        ->leftjoin('packages', 'packages.id', '=', 'organization_packages.package_id')
        ->leftjoin('subscriptions', 'subscriptions.id', '=', 'organization_packages.subscription_id')
        ->leftjoin('subscription_plans', 'subscription_plans.id', '=', 'organization_packages.plan_id')
        ->leftjoin('addon_organization','addon_organization.organization_id','=','organization_packages.organization_id')
        ->leftjoin('organizations','organizations.id','=','organization_packages.organization_id')
             ->leftjoin('businesses','businesses.id','=','organizations.business_id')
              ->leftjoin('addons','addons.id','=','addon_organization.addon_id')
        ->where('addon_organization.addon_id',1)
        ->groupby('organizations.id')
        ->get();

//dd($ledgers);
        return view('admin.Organization_Ledgers', compact('ledgers'));
    }
    public function plan_sms()
    {
        $sms = OrganizationPackage::select('organizations.business_id','organizations.id','organizations.name AS company_name','packages.display_name AS package', 'subscription_plans.display_name AS plan', 'subscription_plans.name AS plan_name', 'businesses.bcrm_code AS propel_id',  DB::raw('DATE_FORMAT(organization_packages.added_on, "%b %d, %Y") AS added_on'),  DB::raw('DATE_FORMAT(organization_packages.expire_on, "%b %d, %Y") AS expire_on'),'addon_organization.used','addon_organization.value')
        ->leftjoin('packages', 'packages.id', '=', 'organization_packages.package_id')
        ->leftjoin('subscriptions', 'subscriptions.id', '=', 'organization_packages.subscription_id')
        ->leftjoin('subscription_plans', 'subscription_plans.id', '=', 'organization_packages.plan_id')
        ->leftjoin('addon_organization','addon_organization.organization_id','=','organization_packages.organization_id')
        ->leftjoin('organizations','organizations.id','=','organization_packages.organization_id')
                    ->leftjoin('businesses','businesses.id','=','organizations.business_id')
->where('addon_organization.addon_id',2)
        ->groupby('organizations.id')
        ->get();


        return view('admin.Organization_SMS', compact('sms'));
    }

     public function plan_memory_size()
    {
        $memory = OrganizationPackage::select('organizations.business_id','organizations.id','organizations.name AS company_name','packages.display_name AS package', 'subscription_plans.display_name AS plan', 'subscription_plans.name AS plan_name',  'businesses.bcrm_code AS propel_id', DB::raw('DATE_FORMAT(organization_packages.added_on, "%b %d, %Y") AS added_on'),  DB::raw('DATE_FORMAT(organization_packages.expire_on, "%b %d, %Y") AS expire_on'),'addon_organization.used','addon_organization.value')
        ->leftjoin('packages', 'packages.id', '=', 'organization_packages.package_id')
        ->leftjoin('subscriptions', 'subscriptions.id', '=', 'organization_packages.subscription_id')
        ->leftjoin('subscription_plans', 'subscription_plans.id', '=', 'organization_packages.plan_id')
        ->leftjoin('addon_organization','addon_organization.organization_id','=','organization_packages.organization_id')
        ->leftjoin('organizations','organizations.id','=','organization_packages.organization_id')
             ->leftjoin('businesses','businesses.id','=','organizations.business_id')
        ->where('addon_organization.addon_id',19)
        ->groupby('organizations.id')
        ->get();

//dd($memory);
        return view('admin.Organization_Memorysize', compact('memory'));
    }

     public function plan_employee()
    {
       $employee = OrganizationPackage::select('organizations.business_id','organizations.id','organizations.name AS company_name','packages.display_name AS package', 'subscription_plans.display_name AS plan', 'subscription_plans.name AS plan_name', 'businesses.bcrm_code AS propel_id',  DB::raw('DATE_FORMAT(organization_packages.added_on, "%b %d, %Y") AS added_on'),  DB::raw('DATE_FORMAT(organization_packages.expire_on, "%b %d, %Y") AS expire_on'),'addon_organization.used','addon_organization.value')
        ->leftjoin('packages', 'packages.id', '=', 'organization_packages.package_id')
        ->leftjoin('subscriptions', 'subscriptions.id', '=', 'organization_packages.subscription_id')
        ->leftjoin('subscription_plans', 'subscription_plans.id', '=', 'organization_packages.plan_id')
        ->leftjoin('addon_organization','addon_organization.organization_id','=','organization_packages.organization_id')
        ->leftjoin('organizations','organizations.id','=','organization_packages.organization_id')
                    ->leftjoin('businesses','businesses.id','=','organizations.business_id')
->where('addon_organization.addon_id',3)
        ->groupby('organizations.id')
        ->get();



//dd($memory);
        return view('admin.Organization_Employee',compact('employee'));
    }
     public function plan_customer()
    {
       $customer = OrganizationPackage::select('organizations.business_id','organizations.id','organizations.name AS company_name','packages.display_name AS package', 'subscription_plans.display_name AS plan', 'subscription_plans.name AS plan_name', 'businesses.bcrm_code AS propel_id',  DB::raw('DATE_FORMAT(organization_packages.added_on, "%b %d, %Y") AS added_on'),  DB::raw('DATE_FORMAT(organization_packages.expire_on, "%b %d, %Y") AS expire_on'),'addon_organization.used','addon_organization.value')
        ->leftjoin('packages', 'packages.id', '=', 'organization_packages.package_id')
        ->leftjoin('subscriptions', 'subscriptions.id', '=', 'organization_packages.subscription_id')
        ->leftjoin('subscription_plans', 'subscription_plans.id', '=', 'organization_packages.plan_id')
        ->leftjoin('addon_organization','addon_organization.organization_id','=','organization_packages.organization_id')
        ->leftjoin('organizations','organizations.id','=','organization_packages.organization_id')
                    ->leftjoin('businesses','businesses.id','=','organizations.business_id')
->where('addon_organization.addon_id',4)
        ->groupby('organizations.id')
        ->get();



//dd($customer);
        return view('admin.Organization_Customer',compact('customer'));
    }
    public function plan_supplier()
    {
       $supplier = OrganizationPackage::select('organizations.business_id','organizations.id','organizations.name AS company_name','packages.display_name AS package', 'subscription_plans.display_name AS plan', 'subscription_plans.name AS plan_name', 'businesses.bcrm_code AS propel_id',  DB::raw('DATE_FORMAT(organization_packages.added_on, "%b %d, %Y") AS added_on'),  DB::raw('DATE_FORMAT(organization_packages.expire_on, "%b %d, %Y") AS expire_on'),'addon_organization.used','addon_organization.value')
        ->leftjoin('packages', 'packages.id', '=', 'organization_packages.package_id')
        ->leftjoin('subscriptions', 'subscriptions.id', '=', 'organization_packages.subscription_id')
        ->leftjoin('subscription_plans', 'subscription_plans.id', '=', 'organization_packages.plan_id')
        ->leftjoin('addon_organization','addon_organization.organization_id','=','organization_packages.organization_id')
        ->leftjoin('organizations','organizations.id','=','organization_packages.organization_id')
                    ->leftjoin('businesses','businesses.id','=','organizations.business_id')
->where('addon_organization.addon_id',5)
        ->groupby('organizations.id')
        ->get();



//dd($customer);
        return view('admin.Organization_Supplier',compact('supplier'));
    }
 public function plan_purchase()
    {
        $purchase = OrganizationPackage::select('organizations.business_id','organizations.id','organizations.name AS company_name','packages.display_name AS package', 'subscription_plans.display_name AS plan', 'subscription_plans.name AS plan_name',  'businesses.bcrm_code AS propel_id', DB::raw('DATE_FORMAT(organization_packages.added_on, "%b %d, %Y") AS added_on'),  DB::raw('DATE_FORMAT(organization_packages.expire_on, "%b %d, %Y") AS expire_on'),'addon_organization.used','addon_organization.value')
        ->leftjoin('packages', 'packages.id', '=', 'organization_packages.package_id')
        ->leftjoin('subscriptions', 'subscriptions.id', '=', 'organization_packages.subscription_id')
        ->leftjoin('subscription_plans', 'subscription_plans.id', '=', 'organization_packages.plan_id')
        ->leftjoin('addon_organization','addon_organization.organization_id','=','organization_packages.organization_id')
        ->leftjoin('organizations','organizations.id','=','organization_packages.organization_id')
                 ->leftjoin('businesses','businesses.id','=','organizations.business_id')
        ->where('addon_organization.addon_id',7)
        ->groupby('organizations.id')
        ->get();

//dd($memory);
        return view('admin.Organization_Purchase', compact('purchase'));
    }


 public function plan_invoice()
    {
        $invoice = OrganizationPackage::select('organizations.business_id','organizations.id','organizations.name AS company_name','packages.display_name AS package', 'subscription_plans.display_name AS plan', 'subscription_plans.name AS plan_name',  'businesses.bcrm_code AS propel_id', DB::raw('DATE_FORMAT(organization_packages.added_on, "%b %d, %Y") AS added_on'),  DB::raw('DATE_FORMAT(organization_packages.expire_on, "%b %d, %Y") AS expire_on'),'addon_organization.used','addon_organization.value')
        ->leftjoin('packages', 'packages.id', '=', 'organization_packages.package_id')
        ->leftjoin('subscriptions', 'subscriptions.id', '=', 'organization_packages.subscription_id')
        ->leftjoin('subscription_plans', 'subscription_plans.id', '=', 'organization_packages.plan_id')
        ->leftjoin('addon_organization','addon_organization.organization_id','=','organization_packages.organization_id')
        ->leftjoin('organizations','organizations.id','=','organization_packages.organization_id')
                 ->leftjoin('businesses','businesses.id','=','organizations.business_id')
        ->where('addon_organization.addon_id',10)
        ->groupby('organizations.id')
        ->get();

//dd($memory);
        return view('admin.Organization_Invoice', compact('invoice'));
    }


 public function plan_grn()
    {
        $grn = OrganizationPackage::select('organizations.business_id','organizations.id','organizations.name AS company_name','packages.display_name AS package', 'subscription_plans.display_name AS plan', 'subscription_plans.name AS plan_name',  'businesses.bcrm_code AS propel_id', DB::raw('DATE_FORMAT(organization_packages.added_on, "%b %d, %Y") AS added_on'),  DB::raw('DATE_FORMAT(organization_packages.expire_on, "%b %d, %Y") AS expire_on'),'addon_organization.used','addon_organization.value')
        ->leftjoin('packages', 'packages.id', '=', 'organization_packages.package_id')
        ->leftjoin('subscriptions', 'subscriptions.id', '=', 'organization_packages.subscription_id')
        ->leftjoin('subscription_plans', 'subscription_plans.id', '=', 'organization_packages.plan_id')
        ->leftjoin('addon_organization','addon_organization.organization_id','=','organization_packages.organization_id')
        ->leftjoin('organizations','organizations.id','=','organization_packages.organization_id')
                 ->leftjoin('businesses','businesses.id','=','organizations.business_id')
        ->where('addon_organization.addon_id',11)
        ->groupby('organizations.id')
        ->get();

//dd($memory);
        return view('admin.Organization_GRN', compact('grn'));
    }


 public function plan_vehicle()
    {
        $vehicle = OrganizationPackage::select('organizations.business_id','organizations.id','organizations.name AS company_name','packages.display_name AS package', 'subscription_plans.display_name AS plan', 'subscription_plans.name AS plan_name',  'businesses.bcrm_code AS propel_id', DB::raw('DATE_FORMAT(organization_packages.added_on, "%b %d, %Y") AS added_on'),  DB::raw('DATE_FORMAT(organization_packages.expire_on, "%b %d, %Y") AS expire_on'),'addon_organization.used','addon_organization.value')
        ->leftjoin('packages', 'packages.id', '=', 'organization_packages.package_id')
        ->leftjoin('subscriptions', 'subscriptions.id', '=', 'organization_packages.subscription_id')
        ->leftjoin('subscription_plans', 'subscription_plans.id', '=', 'organization_packages.plan_id')
        ->leftjoin('addon_organization','addon_organization.organization_id','=','organization_packages.organization_id')
        ->leftjoin('organizations','organizations.id','=','organization_packages.organization_id')
                 ->leftjoin('businesses','businesses.id','=','organizations.business_id')
        ->where('addon_organization.addon_id',13)
        ->groupby('organizations.id')
        ->get();

//dd($memory);
        return view('admin.Organization_Vehicle', compact('vehicle'));
    }

 public function plan_jobcard()
    {
        $jobcard = OrganizationPackage::select('organizations.business_id','organizations.id','organizations.name AS company_name','packages.display_name AS package', 'subscription_plans.display_name AS plan', 'subscription_plans.name AS plan_name',  'businesses.bcrm_code AS propel_id', DB::raw('DATE_FORMAT(organization_packages.added_on, "%b %d, %Y") AS added_on'),  DB::raw('DATE_FORMAT(organization_packages.expire_on, "%b %d, %Y") AS expire_on'),'addon_organization.used','addon_organization.value')
        ->leftjoin('packages', 'packages.id', '=', 'organization_packages.package_id')
        ->leftjoin('subscriptions', 'subscriptions.id', '=', 'organization_packages.subscription_id')
        ->leftjoin('subscription_plans', 'subscription_plans.id', '=', 'organization_packages.plan_id')
        ->leftjoin('addon_organization','addon_organization.organization_id','=','organization_packages.organization_id')
        ->leftjoin('organizations','organizations.id','=','organization_packages.organization_id')
                 ->leftjoin('businesses','businesses.id','=','organizations.business_id')
        ->where('addon_organization.addon_id',14)
        ->groupby('organizations.id')
        ->get();

//dd($memory);
        return view('admin.Organization_Jobcard', compact('jobcard'));
    }

 public function plan_transaction()
    {
        $transaction = OrganizationPackage::select('organizations.business_id','organizations.id','organizations.name AS company_name','packages.display_name AS package', 'subscription_plans.display_name AS plan', 'subscription_plans.name AS plan_name',  'businesses.bcrm_code AS propel_id', DB::raw('DATE_FORMAT(organization_packages.added_on, "%b %d, %Y") AS added_on'),  DB::raw('DATE_FORMAT(organization_packages.expire_on, "%b %d, %Y") AS expire_on'),'addon_organization.used','addon_organization.value')
        ->leftjoin('packages', 'packages.id', '=', 'organization_packages.package_id')
        ->leftjoin('subscriptions', 'subscriptions.id', '=', 'organization_packages.subscription_id')
        ->leftjoin('subscription_plans', 'subscription_plans.id', '=', 'organization_packages.plan_id')
        ->leftjoin('addon_organization','addon_organization.organization_id','=','organization_packages.organization_id')
        ->leftjoin('organizations','organizations.id','=','organization_packages.organization_id')
                 ->leftjoin('businesses','businesses.id','=','organizations.business_id')
        ->where('addon_organization.addon_id',15)
        ->groupby('organizations.id')
        ->get();

//dd($memory);
        return view('admin.Organization_Transaction', compact('transaction'));
    }

 public function plan_print()
    {
        $print = OrganizationPackage::select('organizations.business_id','organizations.id','organizations.name AS company_name','packages.display_name AS package', 'subscription_plans.display_name AS plan', 'subscription_plans.name AS plan_name',  'businesses.bcrm_code AS propel_id', DB::raw('DATE_FORMAT(organization_packages.added_on, "%b %d, %Y") AS added_on'),  DB::raw('DATE_FORMAT(organization_packages.expire_on, "%b %d, %Y") AS expire_on'),'addon_organization.used','addon_organization.value')
        ->leftjoin('packages', 'packages.id', '=', 'organization_packages.package_id')
        ->leftjoin('subscriptions', 'subscriptions.id', '=', 'organization_packages.subscription_id')
        ->leftjoin('subscription_plans', 'subscription_plans.id', '=', 'organization_packages.plan_id')
        ->leftjoin('addon_organization','addon_organization.organization_id','=','organization_packages.organization_id')
        ->leftjoin('organizations','organizations.id','=','organization_packages.organization_id')
                 ->leftjoin('businesses','businesses.id','=','organizations.business_id')
        ->where('addon_organization.addon_id',17)
        ->groupby('organizations.id')
        ->get();

//dd($memory);
        return view('admin.Organization_Print', compact('print'));
    }

 public function plan_call()
    {
        $call = OrganizationPackage::select('organizations.business_id','organizations.id','organizations.name AS company_name','packages.display_name AS package', 'subscription_plans.display_name AS plan', 'subscription_plans.name AS plan_name',  'businesses.bcrm_code AS propel_id', DB::raw('DATE_FORMAT(organization_packages.added_on, "%b %d, %Y") AS added_on'),  DB::raw('DATE_FORMAT(organization_packages.expire_on, "%b %d, %Y") AS expire_on'),'addon_organization.used','addon_organization.value')
        ->leftjoin('packages', 'packages.id', '=', 'organization_packages.package_id')
        ->leftjoin('subscriptions', 'subscriptions.id', '=', 'organization_packages.subscription_id')
        ->leftjoin('subscription_plans', 'subscription_plans.id', '=', 'organization_packages.plan_id')
        ->leftjoin('addon_organization','addon_organization.organization_id','=','organization_packages.organization_id')
        ->leftjoin('organizations','organizations.id','=','organization_packages.organization_id')
                 ->leftjoin('businesses','businesses.id','=','organizations.business_id')
        ->where('addon_organization.addon_id',20)
        ->groupby('organizations.id')
        ->get();

//dd($memory);
        return view('admin.Organization_Call', compact('call'));
    }

 public function exceeded()
    {
        $exceeded = OrganizationPackage::select('addons.display_name as addonname','organizations.business_id','organizations.id','organizations.name AS company_name','packages.display_name AS package', 'subscription_plans.display_name AS plan', 'subscription_plans.name AS plan_name',  'businesses.bcrm_code AS propel_id', DB::raw('DATE_FORMAT(organization_packages.added_on, "%b %d, %Y") AS added_on'),  DB::raw('DATE_FORMAT(organization_packages.expire_on, "%b %d, %Y") AS expire_on'),'addon_organization.used','addon_organization.value')
        ->leftjoin('packages', 'packages.id', '=', 'organization_packages.package_id')
        ->leftjoin('subscriptions', 'subscriptions.id', '=', 'organization_packages.subscription_id')
        ->leftjoin('subscription_plans', 'subscription_plans.id', '=', 'organization_packages.plan_id')
        ->leftjoin('addon_organization','addon_organization.organization_id','=','organization_packages.organization_id')
        ->leftjoin('organizations','organizations.id','=','organization_packages.organization_id')
                 ->leftjoin('businesses','businesses.id','=','organizations.business_id')
       ->leftjoin('addons','addons.id','=','addon_organization.addon_id')
       ->whereRaw('addon_organization.used >= addon_organization.value')
       
     
        ->get();

//dd($memory);
        return view('admin.Organization_Exceeded', compact('exceeded'));
    }
public function create()
    {
       

        return view('admin.Organization_Create', compact('parent_dept'));
    }
    public function gst(){
        
        $gst=GST::select('id','code','chapter','chapter_name','description')
         
         ->get();
//dd($gst);
        return view('admin.gst', compact('gst'));
    }
     public function gst_create()
    {
           
      return view('admin.Gst_Create');
    }

public function gst_edit($id)
    {
        $gst= GST::select('id', 'code','chapter','chapter_name','description',DB::raw('FLOOR(rate)as rate'))
        ->where('id',$id)->first();

        $rates=GST::select(DB::raw('FLOOR(rate)as rate'),'rate as id')
                ->groupby('rate')
                ->get();

        
        $tax=$gst->rate;
     

        return view('admin.Gst_Edit', compact('gst','rates','tax'));
    }

public function code_search(Request $request)
    {   
        $code = $request->get('code');

        $gst=GST::select('id','code','chapter','chapter_name','description',DB::raw('FLOOR(rate)as rate'))->where(function ($query) use ($code){
              $query->where('code','like',  '%' . $code .'%')
                    ->orWhere('chapter','like',  '%' . $code .'%')
                    ->orWhere('rate',$code)
                    ->orWhere('chapter_name','like',  '%' . $code .'%')
                    ->orWhere('description','like',  '%' . $code .'%');
                    })
                ->get();

        // $gst=GST::select('id','code','chapter','chapter_name','description',DB::raw('FLOOR(rate)as rate'))
                // ->where('code',$code)
                // ->get();

        return response()->json(['gst'=>$gst]);


    }
public function gst_update(Request $request)
    {
      
        $gst=GST::findorFail($request->get('id'));
        $gst->code=$request->get('code');
        $gst->chapter=$request->get('chapter');
        $gst->chapter_name=$request->get('chaptername');
        $gst->description=$request->get('description');
        $gst->rate=$request->get('rate');
        $gst->save();

        
        return response()->json([ 'message' => 'Gst'.config('constants.flash.updated'), 'data' =>['code'=>$gst->code,'chapter'=>$gst->chapter,'chapter_name'=>$gst->chapter_name,'description'=>$gst->description,'rate'=>$gst->rate,'id'=>$gst->id]]);


    }

}
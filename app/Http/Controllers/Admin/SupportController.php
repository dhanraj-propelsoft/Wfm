<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Module;
use Carbon\Carbon;
use App\Support;
use App\Organization;
use App\HrmEmployee;
use App\support_priority;
use App\support_status;
use Session;
use Auth;
use DB;
use App\Helpers\Helper;

class SupportController extends Controller
{
    public function index()
    {
        $organization_id = Session::get('organization_id'); 
        $status=support_status::where('status',1)->pluck('name','id');
        $status->prepend('All Status','');

        $support_ticket=Support::select('supports.id',
      'supports.ticket_number','supports.organization_id','organizations.name','supports.ticket_name','supports.priority','hrm_employees.first_name as raised_by','supports.ticket_message',DB::raw('DATE_FORMAT(supports.created_at, "%d %M, %Y") AS start_date'),'supports.status')
         ->leftjoin('organizations','organizations.id','=','supports.organization_id')
         ->leftjoin('hrm_employees','hrm_employees.person_id','=','supports.issued_by')
         ->groupby('supports.id')
         ->get();
 
        return view('admin.Support_Ticket',compact('support_ticket','status'));
    }
    public function show( $id)
    {
       
       
        $organization_id = Session::get('organization_id'); 
        
        $priority=support_priority::where('status',1)->pluck('name','id');
  
        $status=support_status::where('status',1)->pluck('name','id');

        $show=Support::select('support_statuses.id as statusname','support_priorities.id as priorityname','supports.id','supports.ticket_number','supports.organization_id','organizations.name','supports.ticket_name','hrm_employees.first_name as raised_by','hrm_employees.phone_no','supports.ticket_message',DB::raw('DATE_FORMAT(supports.created_at, "%d %M, %Y") AS start_date'),DB::raw('DATE_FORMAT(supports.closed_on, "%d %M, %Y") AS closed_on'),'supports.status')
            ->leftjoin('organizations','organizations.id','=','supports.organization_id')
           ->leftjoin('hrm_employees','hrm_employees.person_id','=','supports.issued_by')
           ->leftjoin('support_priorities','support_priorities.id','=','supports.priority')
           ->leftjoin('support_statuses','support_statuses.id','=','supports.status')
           
           ->where('supports.id',$id)
           ->first();

        return view('admin.Support_Ticket_show',compact('show','priority','status'));
    }

    public function update(Request $request)
    {

        $to_date = Carbon::parse($request->input('closed_on'))->format('Y-m-d');
       
        $support = Support::findOrFail($request->input('supportid'));
        $support->priority = $request->input('priority');
        $support->propel_reply=$request->input('propel_reply');
        $support->status=$request->input('status');
        $support->closed_on= $to_date;
        $support->save();

        $org_name =($support->organization_id != null) ? Organization::findorFail($support->organization_id)->name : "";
       

         return response()->json(['status' => 1, 'message' => ' Support_Ticket'.config('constants.flash.updated'), 'data' =>['id'=>$support->id,'ticket_number'=>$support->ticket_number,'org_id'=>$support->organization_id,'org_name'=> $org_name,'ticket_name'=> $support->ticket_name,'priority'=> $support->priority,'created_at'=> $support->created_at->format('d F, Y'),'status'=>$support->status]]) ;
        

    }

    public function select_status( Request $request)
    {
     
       
       $organization_id = Session::get('organization_id'); 
       $status=support_status::where('status',1)->pluck('name','id');
       $status->prepend('All Status','');
  

       $support_ticket=Support::select('supports.id','supports.ticket_number','supports.organization_id','organizations.name','supports.ticket_name','supports.priority','hrm_employees.first_name as raised_by','supports.ticket_message',DB::raw('DATE_FORMAT(supports.created_at, "%d %M, %Y") AS start_date'),'supports.status')
            ->leftjoin('organizations','organizations.id','=','supports.organization_id')
           ->leftjoin('hrm_employees','hrm_employees.person_id','=','supports.issued_by')
            ->groupby('supports.id');
            
            if ($request->input('select_status')==1||$request->input('select_status')==2||$request->input('select_status')==3) {
                 
               $inventory_item_search =$support_ticket->where('supports.status', $request->input('select_status'))->get();
            }else{
                  $inventory_item_search =$support_ticket->whereNotNull('supports.status')->get();
            }
    
         return response()->json(['data'=> $inventory_item_search]); 
    }

}

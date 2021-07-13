<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Support;
use App\Organization;
use App\HrmEmployee;
use App\support_priority;
use App\User;
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
  
     $support_ticket=Support::select('supports.id','supports.ticket_number','hrm_employees.first_name as raised_by','supports.ticket_name','supports.ticket_message',DB::raw('DATE_FORMAT(supports.created_at, "%d %M, %Y") AS start_date'),'supports.status')
        ->leftjoin('hrm_employees','hrm_employees.person_id','=','supports.issued_by')
        ->where('supports.organization_id',$organization_id)
        ->groupby('supports.id')
        ->get();
    
		return view('settings.Support_Ticket',compact('support_ticket','status'));
	}
    public function create()
	{
     $organization_id = Session::get('organization_id'); 
     $current_timestamp = now()->format('YmdHis');
     $ticket_number="PT0".$organization_id.  $current_timestamp;
     $Auth_Person_ID=Auth::user()->person_id;

     $Loggined_employee_id = GetEmployeeData($organization_id, $Auth_Person_ID);
       $issued_by=HrmEmployee::where('hrm_employees.organization_id',$organization_id)->pluck('first_name','person_id');
     $issued_by->prepend('Select issued person', '');

	
		return view('settings.SupportTicket_Add',compact('ticket_number','issued_by','Loggined_employee_id'));
	}

     public function store(Request $request)
    {
         
        $organization_id = Session::get('organization_id'); 
        $support_ticket= new \App\Support;
        $support_ticket->ticket_number=$request->input('ticketnumber');
        $support_ticket->priority=$request->input('priority');
        $support_ticket->ticket_message=$request->input('ticketmessage');
        $support_ticket->organization_id= $organization_id;
         $support_ticket->issued_by=$request->input('issued_by');
        $support_ticket->ticket_name=$request->input('ticketname');
        $support_ticket->created_by=Auth::user()->id;
        $support_ticket->last_modified_by=Auth::user()->id;
        $support_ticket->save();

        $raised_by =($support_ticket->issued_by != null) ? User::findorFail($support_ticket->issued_by)->name : "";

          return response()->json(['status' => 1, 'message' => ' Support_Ticket'.config('constants.flash.added'), 'data' =>['id'=>$support_ticket->id,'ticket_number'=>$support_ticket->ticket_number,'raised_by'=>$raised_by ,'ticket_name'=> $support_ticket->ticket_name,'ticket_message'=> $support_ticket->ticket_message,'created_at'=> $support_ticket->created_at->format('d F, Y'),'status'=>$support_ticket->status]]) ;
        

        

   }
    public function support_ticket_status(Request $request){
      
        if($request->input('status')==="1"||$request->input('status')==="2"||$request->input('status')==="3")
        {
            $UpdateData=['status' => $request->input('status')];
        }
         
        else{
            $UpdateData=['status' => $request->input('status')];
        }
        Support::where('id', $request->input('id'))->update($UpdateData);
               
          return response()->json(array('result' => "success",'status'=>$UpdateData));
    }
    public function show( $id)
    {
      $organization_id = Session::get('organization_id'); 
      $priority=support_priority::where('status',1)->pluck('name','id');
      $status=support_status::where('id',$id)->select('name');
      $date=Carbon::now()->format("Y-m-d");

      $show=Support::select(DB::raw('DATE_FORMAT(supports.closed_on, "%d %M, %Y") AS closed_on'),'support_statuses.name as statusname','support_priorities.id as priorityname','supports.id','supports.ticket_number','supports.organization_id','organizations.name','supports.ticket_name','hrm_employees.first_name as raised_by','hrm_employees.phone_no','supports.ticket_message',DB::raw('DATE_FORMAT(supports.created_at, "%d %M, %Y") AS start_date'),'supports.status','supports.propel_reply')
         ->leftjoin('organizations','organizations.id','=','supports.organization_id')
         ->leftjoin('hrm_employees','hrm_employees.person_id','=','supports.issued_by')
         ->leftjoin('support_priorities','support_priorities.id','=','supports.priority')
         ->leftjoin('support_statuses','support_statuses.id','=','supports.status')
         ->where('supports.organization_id',$organization_id)
         ->where('supports.id',$id)
         ->first();

        return view('settings.SupportTicket_View',compact('show','priority','status','date'));
    }
     public function update(Request $request)
    {
        $support = Support::findOrFail($request->input('supportid'));
        $support->ticket_name = $request->input('ticketname');
        $support->ticket_message=$request->input('ticketmessage');
        $support->save();

 
        $raised_by =($support->issued_by != null) ? User::findorFail($support->issued_by)->name : "";
        return response()->json(['status' => 1, 'message' => ' Support_Ticket'.config('constants.flash.updated'), 'data' =>['id'=>$support->id,'ticket_number'=>$support->ticket_number,'raised_by'=>$raised_by ,'ticket_name'=> $support->ticket_name,'ticket_message'=> $support->ticket_message,'created_at'=> $support->created_at->format('d F, Y'),'status'=>$support->status]]) ;
    }

    public function select_status( Request $request)
    {
        $organization_id = Session::get('organization_id'); 
        $status=support_status::where('status',1)->pluck('name','id');
        $status->prepend('All Status','');
  
        $support_ticket=Support::select('supports.id','supports.ticket_number','hrm_employees.first_name as raised_by','supports.ticket_name','supports.ticket_message',DB::raw('DATE_FORMAT(supports.created_at, "%d %M, %Y") AS start_date'),'supports.status')
        ->leftjoin('hrm_employees','hrm_employees.person_id','=','supports.issued_by')
        ->where('supports.organization_id',$organization_id);
        
         if ($request->input('select_status')==1||$request->input('select_status')==2||$request->input('select_status')==3) {
         
            $inventory_item_search =$support_ticket->where('supports.status', $request->input('select_status')) ->groupby('supports.id')->get();
         }else{
          $inventory_item_search =$support_ticket->whereNotNull('supports.status') ->groupby('supports.id')->get();
        }
       return response()->json(['data'=> $inventory_item_search]);
    }
}

@section('dom_links')
@parent
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/datatables.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/plugins/jquery.contextMenu.min.js') }}"></script>

@include('trade_wms.jobcard.JobCard-Common-JavaScript')

<script type="text/javascript">




var current_select_item = null;

function getcurrrentTime() {
	var date = new Date();
    var dateformat =   String(date.getFullYear()+'-'+date.getMonth()+'-'+date.getDate()+' '+date.getHours()+':'+date.getMinutes()+':'+date.getSeconds()).padEnd(3, '0')+ '.' + String(date.getMilliseconds()).padEnd(6, '0');
    return dateformat;
  }


var transaction_id = "";
var jobcard_store_route = "";
var jobcard_ack_route = "{{ url('job_card_acknowledgement/')}}";

@if(!empty($id))
transaction_id = "{{$id}}";
@endif

/* MasterData URL */
var masterDataURL = '{{ route("jobcardDetail.masterData") }}';
  	masterDataURL += transaction_id?"/"+transaction_id:"";
console.log("masterDataURL?");
console.log(masterDataURL);
		
/* get Edit screen data  */


/* form fields */
let $jobcardStatusEle = $(`{!!Form::select('job_card_status',defalutSelectDropDownArray(""),'', ['class' => 'form-control job-card-status' ]); !!}`);
let $jobItemStatusEle = $(`{!!Form::select('transaction_item[][job_item_status]',defalutSelectDropDownArray(""),'', ['class' => 'form-control job_item_status' ]); !!}`);
let job_item_status_dropdown = "";
var $assignedEle = $(`{{ Form::select('assigned_employee_id',defalutSelectDropDownArray(""),null, ['class' => 'form-control select_item', 'id' => 'employee_id']) }}`);
var assigned_to = `{{ Form::select('transaction_item[][assigned_employee_id]',defalutSelectDropDownArray(""),null, ['class' => 'form-control select_item', 'id' => 'employee_id']) }}`;

		
		
@if(!empty($id))
	jobcard_store_route = "{{ route('jobcard.update',$id) }}";
@else
	jobcard_store_route = "{{ route('jobcard.store') }}";
@endif

  var jobcard_img_destory_route = "{{ url('destroyJobCardImage') }}";
</script>

<script type="text/javascript" src="{{ URL::asset('assets/plugins/dropzone/dropzone.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('resources/views/trade_wms/jobcard/JobCardDetail/JobCard-Detail.js') }}"></script>
@stop

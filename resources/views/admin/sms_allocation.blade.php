@extends('layouts.master')
@section('head_links') @parent
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
@stop
@if($module_name == "admin")
@include('includes.admin')
@else
@include('includes.settings')
@endif
@section('content')
<div class="alert alert-success">
	{{ Session::get('flash_message') }}
</div>

@if($errors->any())
	<div class="alert alert-danger">
		@foreach($errors->all() as $error)
			<p>{{ $error }}</p>
		@endforeach
	</div>
@endif

<div class="fill header">
  <h4 class="float-left page-title">SMS Ledger</h4>
  @if($module_name == "admin")
  <a class="btn btn-danger float-right add" style="color: #fff">+ New</a>
  <br>
  <br>
  <div class="row">
			<div class="col-md-3 col-md-offset-3" style="margin-left:-120px">
				<div class="form-group">				
					{!! Form::select('organization',$organizationData,null,['class' => 'form-control select_item','id' => 'organization']) !!}
				</div>
			</div>
		</div>
  @endif
</div>

<div class="float-left table_container" style="width: 100%; padding-top: 10px;">
<table id="datatable" class="table data_table" width="100%" cellspacing="0">
	<thead>
	  <tr>
		  <th>Date</th>		 
		  <th>Describtion</th>		  
		  <th>Credit</th> 
	      <th> Debit </th>			
		  <th> Balance </th>		
	  </tr>
	</thead>
	<tbody>
	 		 @foreach ($smsLedgers as $smsLedger)
			<tr>
				<td>{{$smsLedger->pDate}}</td>
				
				<td>{{$smsLedger->pDescribtion}}</td>				
								
				<td>{{$smsLedger->pCredit}}</td>
				<td>{{$smsLedger->pDebit}}</td>
				<td>{{$smsLedger->pBalance}}</td>				
			</tr>
			@endforeach
			
	</tbody>
  </table>
</div>

@stop

@section('dom_links')
@parent
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmake-0.1.32/pdfmake.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmake-0.1.32/vfs_fonts.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/datatables.min.js') }}"></script>
<script type="text/javascript">
   var datatable = null;

   var datatable_options = {"order": [], "stateSave": true};


  $(document).ready(function() {

	datatable = $('#datatable').DataTable(datatable_options);

  $('.add').on('click', function(e) {
		e.preventDefault();
		$.get("{{ route('smsAllocation.Create') }}", function(data) {
		  $('.crud_modal .modal-container').html("");
		  $('.crud_modal .modal-container').html(data);
		});
		//$('.crud_modal').find('.modal-dialog').addClass('modal-lg');
		$('.crud_modal').modal('show');
  });

  $('#organization').on('change',function(){
		var html='';
		var org_no= $('select[name=organization]').val();
		
		$.ajax({
			url : "{{ route('smsAllocation.getOrganizationSms') }}",
			type: 'POST',
			data:
			{
				_token: '{{ csrf_token() }}',
				org_no : org_no,
				
			},
			success:function(data,textStatus,jqXHR)
			{
				
				var smsLedger = data.data;
				$('#datatable tbody').empty();

				if( data.status == 1)
				{
					for(var i in smsLedger)
					{
						
						html+=`<tr>
	        			
	        			<td>`+smsLedger[i].pDate+`</td>
	        			
	        			<td>`+smsLedger[i].pDescribtion+`</td>
	        			
	        			<td>`+smsLedger[i].pCredit+`</td>
	        			<td>`+smsLedger[i].pDebit+`</td>
	        			<td>`+smsLedger[i].pBalance+`</td>
	        			
	        			
	      			 	
	    			</tr>`;
	    			}
	  				//$('tbody').html(html);
					//call_back_on(html);
					call_back_optional(html,`add`,``);

				}
				

				
			},
			error:function()
			{

			}


		});
	});



  });
  </script>
@stop
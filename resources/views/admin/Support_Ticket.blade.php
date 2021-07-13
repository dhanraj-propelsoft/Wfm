@extends('layouts.master')
@section('head_links') @parent
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
@stop
@include('includes.admin')
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
  <h4 class="float-left page-title">Support Tickets</h4>
 

	
 
	
	
</div>
<div class="col-md-3 col-md-offset-3">
							{{ Form::select('select_status', $status, null, ['class' => 'form-control select_item select_status', 'id' => 'select_status']) }}
						</div>
<div class="float-right" style="width: 100%; padding-top: 10px">
	
<table id="datatable" class="table data_table table-hover" width="100%" cellspacing="0">
	<thead>
	  <tr>								

	  	<th>Ticket number</th>
	  	<th> OrganizationId </th> 
		<th> OrganizationName </th> 
		<th> Ticket Name</th>
		<th> Priority</th>
		<th> AssignedTo</th>
		<th>Created on</th>
		<th>Status</th>
		<th> View</th>
				
	  </tr>
	</thead>
	<tbody>
		@foreach($support_ticket as $support)
		<tr>
			<td>{{$support->ticket_number}}</td>
			<td>{{$support->organization_id}}</td>
			<td>{{$support->name}}</td>
			<td>{{$support->ticket_name}}</td>
			<td>@if($support->priority == 1)
			  		<label class="grid_label badge badge-primary ">Low</label>
				@elseif($support->priority == 2)
			  		<label class="grid_label badge badge-warning ">Medium</label>
			  		@elseif($support->priority == 3)
			  		<label class="grid_label badge badge-danger ">High</label>
				@endif</td>
			
			<td>{{$support->assigned_by}}</td>
			<td>{{$support->start_date}}</td>
		<td>@if($support->status == 1)
			  		<label class="grid_label badge badge-primary ">Open</label>
				@elseif($support->status == 2)
			  		<label class="grid_label badge badge-warning ">Progress</label>
			  		@elseif($support->status == 3)
			  		<label class="grid_label badge badge-danger ">Close</label>
				@endif</td>
		
			<td><a  data-id="{{ $support->id}}"class="grid_label action-btn show-icon view "><i class="fa fa-eye"></i> </a>
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

   var datatable_options = {"columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": [[1, "asc"]], "stateSave": true};

  $(document).ready(function() {

	datatable = $('#datatable').DataTable(datatable_options);

  $('.add').on('click', function(e) {
		e.preventDefault();
		$.get("{{ route('support_ticket_create') }}", function(data) {
		  $('.crud_modal .modal-container').html("");
		  $('.crud_modal .modal-container').html(data);
		});
		//$('.crud_modal').find('.modal-dialog').addClass('modal-lg');
		$('.crud_modal').modal('show');
  });
 

  $('body').on('click', '.view', function(e) {
        e.preventDefault();
        isFirstIteration = true;

        $.get("{{ url('admin/ticketshow') }}/"+$(this).data('id')+"/view", function(data) {
        	$('.crud_modal .modal-container').html("");
        	$('.crud_modal .modal-container').html(data);
        });
        $('.crud_modal').modal('show');
	});
  $('body').on('change', '.select_status', function(e) {
  	console.log("hi");
        e.preventDefault();
        isFirstIteration = true;
	$.ajax({
			url: '{{ route('select_status') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				select_status: $('select[name=select_status]').val(),
				},
			success:function(data, textStatus, jqXHR) {
				
				
				var status=data.data;
             
               console.log(status);
    //            	var low_selected = "";
				// var medium_selected = "";
				// var high_selected = "";
				
				// var selected_text1 = "Low";
				// var selected_class1 = "badge-primary";

				if(status.priority == 1) {
					low_selected = "selected";
					selected_text1 = "Low";
					selected_class1 = "badge-primary";
				} else if(status.priority == 2) {
					medium_selected = "selected";
					selected_text1 = "Medium";
					selected_class1 = "badge-warning";
				} else if(status.priority == 3) {
					high_selected  = "selected";
					selected_text1= "High";
					selected_class1 = "badge-danger";
				}


                  var selected_text = "";
				var selected_class = "";
				


				
					//datatable.destory();
  					$('#datatable tbody').empty();
  					/*var updated_price=data.data.price;*/
                   html=``;
					//console.log(status);
					for(var i in status)
					{
//console.log(status.status)
					if(status[i].status == 1) {
					selected_text = "Open";
					selected_class = "badge-primary";
					} else if(status[i].status == 2) {
					selected_text = "Progress";
					selected_class = "badge-warning";
					} else if(status[i].status == 3) {
					selected_text = "Close";
					selected_class = "badge-danger";
					}

					if(status[i].priority == 1) {
					
					selected_text1 = "Low";
					selected_class1 = "badge-primary";
				} else if(status[i].priority == 2) {
				
					selected_text1 = "Medium";
					selected_class1 = "badge-warning";
				} else if(status[i].priority == 3) {
					
					selected_text1= "High";
					selected_class1 = "badge-danger";
				}

  					var status_select=status[i];

  						
						html+=`<tr>
					
					<td>`+status[i].ticket_number+`</td>
					<td>`+status[i].organization_id+`</td>
					<td>`+status[i].name+`</td>
					<td>`+status[i].ticket_name+`</td>
				
					<td>
						<label class="grid_label badge `+selected_class1+` priority">`+selected_text1+`</label>
						
					</td>
					<td></td>
					<td>`+status[i].start_date+`</td>
					<td>
  						<label class="grid_label badge `+selected_class+` status">`+selected_text+`</label>

					</td>
					
					<td><a data-id="`+status[i].id+`" class="grid_label action-btn show-icon view edit"><i class="fa fa-eye"></i> </a>
					</td>
					</tr>`;
					}
					call_back_optional(html,`add`,``);


// html=``;


// html+ =`<tr role="row" class="odd">
// 					<td>`+data.data[].id+`</td>
// 				     <td>`+data.data.name+`</td>
// 					<td>`+data.data.main_category+`</td>
// 					<td>`+data.data.created_by+`</td>
// 					<td>`+data.data.created_at+`</td>
// 					<td><a data-id="`+data.data.id+`" class="action-btn grid_label edit-icon edit"><i class="fa li_pen"></i></a></td>
// 					<td>
											
// 										<label class="grid_label badge badge-success status">Active</label>
// 										<select style="display:none" id="`+data.data.status+`" class="active_status form-control">
// 										<option value="1">Active</option>
// 										<option value="0">In-active</option>
// 										</select>
// 									</td>
					
// 					</tr>`;


// 				call_back(html,`add`,data.message, data.data.id);

// 				$('.loader_wall_onspot').hide();
				

// 				},
// 			error:function(jqXHR, textStatus, errorThrown) {
// 				//alert("New Request Failed " +textStatus);
// 				}
// 			});        });
//         $('.crud_modal').modal('show');
	}
 
  });
})
  })
  </script>
@stop
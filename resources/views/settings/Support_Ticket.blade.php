@extends('layouts.master')
@section('head_links') @parent
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
@stop
@include('includes.settings')
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
 

	<a class="btn btn-danger float-right add" style="color: #fff">+ New</a>
 
	
	<div class="col-md-3 col-md-offset-3">
							{{ Form::select('select_status', $status, null, ['class' => 'form-control select_item select_status', 'id' => 'select_status']) }}
						</div>
</div>

<div class="float-right" style="width: 100%; padding-top: 10px">
	
<table id="datatable" class="table data_table table-hover" width="100%" cellspacing="0">
	<thead>
	  <tr>								

	  	<th>Ticket number</th>
		<th> RaisedByName </th> 
		<th> Ticket Name</th>
		<th> Ticket Message</th>
		<th>Created on</th>
		<th>Status</th>
		<th>View</th>
				
	  </tr>
	</thead>
	<tbody>
		@foreach($support_ticket as $support)
		<tr>
		
				<td>{{$support->ticket_number}}</td>
				<td>{{$support->raised_by}}</td>
				<td>{{$support->ticket_name}}</td>
				<td>{{$support->ticket_message}}</td>
				<td>{{$support->start_date}}</td>
				<td>

					@if($support->status == 1)
					<label class="grid_label badge badge-success status">Open</label>
					@elseif($support->status == 2)
						<label class="grid_label badge badge-warning status">Progress
						</label>
					@elseif($support->status == 3)
						<label class="grid_label badge badge-danger status">Close
						</label>	
						
					@endif
				   
				</td>
				<td><a  data-id="{{ $support->id}}"class="grid_label action-btn show-icon view"><i class="fa fa-eye"></i> </a>
			
			
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

        $.get("{{ url('support_ticketshow') }}/"+$(this).data('id')+"/view", function(data) {
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
			url: '{{ route('select_status_settings') }}',
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


  					var status_select=status[i];

  						
						html+=`<tr>
					
					<td>`+status[i].ticket_number+`</td>
					<td>`+status[i].raised_by+`</td>
					<td>`+status[i].ticket_name+`</td>
					<td>`+status[i].ticket_message+`</td>
					<td>`+status[i].start_date+`</td>
					<td>
  						<label class="grid_label badge `+selected_class+` status">`+selected_text+`</label>

					</td>
					
					<td><a data-id="`+status[i].id+`" class="grid_label action-btn show-icon view edit"><i class="fa fa-eye"></i> </a>
					</td>
					</tr>`;
					}
					call_back_optional(html,`add`,``);
//   $(document).ready(function() {
// $('#datatable').each( function () {
//         var title = $('#datatable').eq( $(this).index() ).text();
//         $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
// });
// $("#support_status").on( 'change', function () {
//         table
//             .column( $(this).parent().index()+':visible' )
//             .search( this.value )
//             .draw();
// });
// })
}
 
  });
})
  })
  
  </script>
@stop
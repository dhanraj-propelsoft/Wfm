@extends('layouts.master')
@section('head_links') @parent
	
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
  	<h4 class="float-left page-title">Items</h4>
  	<div class="col-md-3 col-md-offset-3">
		{{ Form::select('select_maincategory',$main_category, null, ['class' => 'form-control select_maincategory ', 'id' => 'select_maincategory']) }}
	</div>
	<a class="btn btn-danger float-right excel_export" style="color: #fff">Export to Excel</a>
	<a class="btn btn-danger float-right add" style="color: #fff">+ New</a>
 
</div>

<div class="float-left table_container" style="width: 100%; padding-top: 10px;">
	
<table id="datatable" class="table data_table" width="100%" cellspacing="0">
	<thead>
	  <tr>
	  	
	 	<th>DB Primary Key ID</th>
		<th> Name </th> 
		<th>Item Type</th>
		<th>MainCategory</th>
		<th> Category </th> 
		<th> Type</th>
		<th> Make</th>
		<th> Identifier </th>
		<th> CreatedBy </th>
		<th> CreatedOn </th>
		<th>Action</th>
		<th>status</th>
	  </tr>
	</thead>
	<tbody>
	  @foreach($items as $item)
		<tr>		
			
			<td>{{ $item->id }}</td>
			<td>{{ $item->name }}</td>
			<td>{{ $item->category_type_name }}</td>
			<td>{{ $item->main_category_name }}</td>
		  	<td>{{ $item->category_name }}</td>
		  	<td>{{ $item->type_name }}</td>
		  	<td>{{ $item->make_name }}</td>
		  	<td>{{ $item->companyname }}</td>
		  	<td>{{ $item->user_name }}</td>		  				
		  	<td>{{ $item->start_date}}</td> 

		  		<td>
                	<a data-id="{{ $item->id}}" class="grid_label action-btn edit-icon edit"><i class="fa li_pen"></i></a>				
				       
				</td>

				<td>

					@if($item->status == 1)
						<label class="grid_label badge badge-success status">Active</label>
					@elseif($item->status == 0)
						<label class="grid_label badge badge-warning status">In-Active
						</label>
					@endif

					<select style="display:none" id="{{ $item->id }}" class="active_status form-control">
						<option @if($item->status == 1) selected="selected" @endif value="1">Active</option>
						<option @if($item->status == 0) selected="selected" @endif value="0">In-Active</option>
					
					</select>
				</td>
		  	
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


     var isFirstIteration = true;        

     var datatable_options = {"pageLength": 10, "columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": [],

        dom: 'lBfrtip',
        buttons: [  
           
            {
                extend: 'excel',
                exportOptions: {
                     columns: [0,1,2,3,4,5,6,7,8]
                },
                footer: false
            },
           
        ]


    };


  $(document).ready(function() {

	datatable = $('#datatable').DataTable(datatable_options);
	$('.buttons-excel').css('display','none');
	
	$('body').on('click', '.excel_export', function(){
        $(".buttons-excel")[0].click(); //trigger the click event
    });


  $('.add').on('click', function(e) {
		e.preventDefault();
		$.get("{{ route('item_create') }}", function(data) {
		  $('.crud_modal .modal-container').html("");
		  $('.crud_modal .modal-container').html(data);
		});
		//$('.crud_modal').find('.modal-dialog').addClass('modal-lg');
		$('.crud_modal').modal('show');
  });

  	 $('body').on('click', '.edit', function(e) {
        e.preventDefault();
        isFirstIteration = true;
         $.get("{{ url('admin/model') }}/"+$(this).data('id')+"/edit", function(data) {
        	$('.crud_modal .modal-container').html("");
        	$('.crud_modal .modal-container').html(data);
        });
        $('.crud_modal').modal('show');
	});

    $('body').on('click', '.status', function(e) {
		$(this).hide();
		$(this).parent().find('select').css('display', 'block');
  	});

  $('body').on('change', '.active_status', function(e) {
			var status = $(this).val();
			var id = $(this).attr('id');
			var obj = $(this);
			var url = "{{ route('model.status') }}";
			var data = change_status(id, obj, status, url, "{{ csrf_token() }}");
			console.log(data);
			// $("#expire_"+id).text(data.expire_on);
		});

  	
 	$('body').on('change', '.select_maincategory', function(e) {
  		//alert("work it");
  		var html='';

		var main_category= $('select[name=select_maincategory]').val();
		//alert( main_category);
		$.ajax({
			url : '{{ route('select_maincategory') }}',
			type: 'POST',
			data:
			{
				_token: '{{ csrf_token() }}',
				maincategory : main_category ,
				
			},

	
        
			success:function(data, textStatus, jqXHR) {

				var active_selected = "";
				var inactive_selected = "";
				
				var selected_text = "Active";
				var selected_class = "badge-success";

				if(data.data.status == 1) {
					active_selected  = "selected";
					selected_text = "Active";
					selected_class = "badge-success";
				} else if(data.data.status == 0) {
					inactive_selected = "selected";
					selected_text = "In-Active";
					selected_class = "badge-warning";
				} 
				//alert(data.data);
				var maincategory=data.data;
             	$('#datatable tbody').empty();
				
				if( data.status == 1)
				{
					for(var i in maincategory)
					{
						html+=`<tr>
	        			<td >`+maincategory[i].model_id+`</td>
	        			<td>`+maincategory[i].name+`</td>
	        			<td>`+maincategory[i].category_type_name+`</td>
	        			<td>`+maincategory[i].main_category_name+`</td>
	        			<td>`+maincategory[i].category_name+`</td>
	        			<td>`+maincategory[i].type_name+` </td> 
	        			<td >`+maincategory[i].make_name+`</td>
	        			<td></td>
	        			<td>`+maincategory[i].user_name+`</td>
	        			<td>`+maincategory[i].start_date+`</td>
	        		<td><a data-id="`+maincategory[i].model_id+`" class="action-btn grid_label edit-icon edit"><i class="fa li_pen"></i></a></td>
	        			<td>
						<label class="grid_label badge `+selected_class+` status">`+selected_text+`</label>
						<select style="display:none" id="`+maincategory[i].model_id+`"  class=" form-control active_status">
							<option `+active_selected+` value="1">Active</option>
							<option `+inactive_selected+`value="0">InActive</option>
							
						</select>
					</td>
					 
	        			
	      			 	
	    			</tr>`;
	    			}
	  				//$('tbody').html(html);
					//call_back_on(html);
					call_back_optional(html,`add`,``);

				}
				else
				{ 
					console.log("error");
					call_back_optional(``,`add`,``);
					alert_message(data.message,'error');
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
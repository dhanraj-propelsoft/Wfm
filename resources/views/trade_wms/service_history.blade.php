@extends('layouts.master')
@section('head_links') @parent
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
@stop
@include('includes.trade_wms')
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
  <h4 class="float-left page-title">Service History</h4>
  
	<!-- <a class="btn btn-danger float-right add" style="color: #fff">+ New</a> -->

</div>

{!! Form::open([
		'class' => 'form-horizontal search'
	]) !!}                                        
	{{ csrf_field() }}

<div class="modal-body">
	<div class="clearfix"></div>
	<div class="form-group">
		<div class="row">
			<div class="col-12">
				<div class="form-inline">
	                <div class="col-md-3 form-group">
	                    <label class="col-sm-3 col-form-label" for="customer_name">Customer Name</label>
	                    <input type="text" class="form-control" name="customer_name" id="customer_name">
	                </div>	            
	                <div class="col-md-3 form-group">
	                    <label class="col-sm-6 col-form-label" for="registration_number">Vehicle Number</label>
	                    {{ Form::select('registration_number', $vehicles_register, null, ['class' => 'form-control select_item', 'id' => 'registration_number']) }}
	                </div>	            
	                <div class="col-md-3 form-group">
	                    <label class="col-sm-2 col-form-label" for="from_date">From Date</label>
	                    {{ Form::text('from_date', null, ['class'=>'form-control date-picker datetype', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off', 'id' => 'from_date']) }}
	                </div>
	                <div class="col-md-3 form-group">
	                    <label class="col-sm-2 col-form-label" for="to_date">To Date</label>{{ Form::text('to_date', null, ['class'=>'form-control date-picker datetype', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off', 'id' => 'to_date']) }}
	                </div>
	            </div>
			</div>			
		</div>
		<div class="row">
			<div class="col-md-10"></div>
			<button type="submit" class="btn btn-success tab_save_btn"> Search </button>&nbsp;
			<button type="submit" class="btn btn-primary tab_save_btn"> Export </button>			
		</div>
	</div>
</div>

<div class="float-left table_container" style="width: 100%; padding-top: 10px;">
	
<table id="datatable" class="table data_table" width="100%" cellspacing="0">
	<thead>
	  <tr>
		<th> Customer Name </th> 
		<th> Registration Number </th>
		<th> Make </th>
		<!-- <th> Model </th>
		<th> Variant </th> -->
		<th> Date Serviced </th>
		<th> Serviced At </th>
		<th style="width: 75%;"> Work / Job </th>
		<th> Notes </th>
		<th> Next Service </th>
		<th> Trade Amount </th>
		<th> Usage </th>
	  </tr>
	</thead>
	<tbody>
		
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
		datatable = $('#datatable').DataTable();
  	});

  	loaddata();

  	$('.search').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		/*rules: {
			customer_name: { required: true },                
		},

		messages: {
			customer_name: { required: "Customer Name is required." },                
		},*/

		invalidHandler: function(event, validator) 
		{ 
			//display error alert on form submit   
			$('.alert-danger', $('.login-form')).show();
		},

		highlight: function(element) 
		{ // hightlight error inputs
			$(element).closest('.form-group').addClass('has-error'); // set error class to the control group
		},

		success: function(label) {
			label.closest('.form-group').removeClass('has-error');
			label.remove();
		},

		submitHandler: function(form) {
			$('.loader_wall_onspot').show();

loaddata();
		}
	});


	function loaddata() {
		$.ajax({
			url: '{{ route('search_service_history') }}',
			type: 'get',
			data: {
				_token: '{{ csrf_token() }}',
				name: $('input[name=customer_name]').val(),
				registration_no: $('select[name=registration_number]').val(),
				from_date: $('input[name=from_date]').val(),
				to_date: $('input[name=to_date]').val()          
			},
			success:function(data, textStatus, jqXHR) {
				var result=data.data;
				datatable.destroy();
				var html = ``;

				for (var i in result) {

					var items = result[i].item_name;
					var amounts = result[i].item_amount;

					/*if(result[i].item_name != null && result[i].item_amount != null)
					{
						var items = result[i].item_name.split(',');
						var amounts = result[i].item_amount.split(',');						
						for(var j in items){
							selected_items+=items[j]+'<br><hr>';
							selected_amounts+=amounts[j]+'<br><hr>';
						}
					}*/					

						html += `<tr>
							<td> `+result[i].owner_name+` </td>
							<td> `+result[i].registration_no+` </td>
							<td> `+result[i].make_name+` </td>
							<td> `+result[i].date+` </td>
							<td> `+result[i].organization_name+` </td>
							<td> `+result[i].item_name+` </td>
							<td> `+result[i].vehicle_note+` </td>
							<td> `+result[i].item_amount+` </td>
							<td> `+result[i].organization_name+` </td>
							<td> `+result[i].usage_name+` </td>
						  </tr>`;
					}
				

			$('table tbody').html(html);
			datatable = $('#datatable').DataTable();

			$('.loader_wall_onspot').hide();
			},
			error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
			}
		});
	}

</script>
@stop
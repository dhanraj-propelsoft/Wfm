@extends('layouts.master')
@section('head_links') @parent
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
@stop
@include('includes.trade')
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
  	<h4 class="float-left page-title">Warehouse Summary</h4>
  	@permission('discount-create')
  		<!-- <a class="btn btn-danger float-right add" style="color: #fff">+ New</a> -->
  	@endpermission
</div> 

<div class="float-left table_container" style="width: 100%; padding-top: 10px;">
	<!-- <div class="form-body">
	<div class="row">
		<div class="form-group col-md-4 on_date"> 
			{!! Form::label('on_date', 'On Date', array('class' => 'control-label col-md-3 required')) !!}
			<div class="col-md-6">
				{!! Form::text('on_date', null,['class' => 'form-control date-picker','data-date-format' => 'dd-mm-yyyy']) !!}
			</div>
		</div>
		<div class="form-group col-md-4 end_date"> 
			{!! Form::label('end_date', 'End Date', array('class' => 'control-label col-md-3 required')) !!}
			<div class="col-md-6">
				{!! Form::text('end_date', null,['class' => 'form-control date-picker','data-date-format' => 'dd-mm-yyyy']) !!}
			</div>
		</div>
		<div class="form-group col-md-4"> 
			<button type="submit" class="btn btn-success submit">Submit</button>
		</div>
	</div>
	</div> -->
	<!-- <div class="batch_container">
		<div class="batch_action">
			<i class="fa icon-arrows-slide-down1 arrow"></i>Batch Actions <i class="fa fa-caret-down"></i>
		</div>
		<ul class="batch_list">
			@permission('discount-delete')
		  		<li><a class="multidelete">Delete</a></li>
		  	@endpermission
		  	@permission('discount-edit')
			  	<li><a data-value="1" class="multiapprove">Make Active</a></li>
			  	<li><a data-value="0" class="multiapprove">Make In-Active</a></li>
		  	@endpermission
		</ul>
	</div> -->
  	<table id="datatable" class="table data_table table-hover" width="100%" cellspacing="0">
        <thead>
            <tr>
            	<!-- <th> {{ Form::checkbox('check_all', 'check_all', null, ['id' => 'check_all'] ) }} <label for="check_all"><span></span></label></th> -->
				<th>Name</th>
				<th>Category</th>
				<th>Quantity</th>
            </tr>
        </thead>
        <tbody>
        @foreach($warehouses as $warehouse)
            <tr>
            	<!-- <td width="1">{{ Form::checkbox('warehouse',$warehouse->id, null, ['id' => $warehouse->id, 'class' => 'item_check']) }}<label for="{{$warehouse->id}}"><span></span></label></td> -->
                <td>{{ $warehouse->name }}</td>
                <td>{{ $warehouse->category_name }}</td>
				<td>{{ $warehouse->total_quantity }} {{ $warehouse->unit }}</td>
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

   var datatable_options = {"stateSave": true};

	$(document).ready(function() {

	datatable = $('#datatable').DataTable(datatable_options);

	});

	$(".submit").click(function(){
	    var start_date = $('input[name=start_date]').val();
		var end_date = $('input[name=end_date]').val(); 
		//alert(start_date);
		$.ajax({
			url: '{{ route('warehouse_summary.date_summary') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				start_date: $('input[name=start_date]').val(),
				end_date: $('input[name=end_date]').val(),    
			},
			success:function(data, textStatus, jqXHR) {

				call_back(`<tr role="row" class="odd">					
				<td>`+data.data.name+`</td>
				<td>`+data.data.total_quantity+`</td>	
				</tr>`, `add`, data.message);

				$('.loader_wall_onspot').hide();
			},
			error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
			}
		});
	}); 
</script>
@stop
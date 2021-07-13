@extends('layouts.master')
@section('head_links') @parent
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
<style>
        .table td
        {
            padding: 2px;
        }
        body
        {
            font-size: 12px !important;
        }
        .btn
        {
            line-height: 1;
        }
</style>
@stop
@include('includes.inventory')
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
<div class="fill header" style="height:40px;width: 102%;background-color: #e3e3e9;margin-left: -10px;">
  <h4 class="float-left page-title">Age Of Goods</h4>
  <a class="btn btn-success float-right excel_export" style="color: #fff">Export Excel</a> 	
</div>
<div class="float-left table_container" style="width: 100%; padding-top: 10px;">
<table id="datatable" class="table data_table" width="100%" cellspacing="0">
	<thead>
	  <tr>
	  	<th>Product_name</th> 
	  	<th>Total Qty</th> 
		<th>0-15 Days</th> 
		<th>15-30 Days</th>
		<th>30-60 Days</th> 
		<th>60-90 Days</th>
		<th>90+ Days</th>
		<th>Batch</th>
	  </tr>
	</thead>
	<tbody>
		@foreach($age_of_goods as $age_of_good)
		<tr>
		<td>{{$age_of_good->name}}</td>
		<td>{{$age_of_good->total_quantity}}</td>
		<td>{{$age_of_good->fiftten_days}}</td>
		<td>{{$age_of_good->thirty_days}}</td>
		<td>{{$age_of_good->sixty_days}}</td>
		<td>{{$age_of_good->ninty_days}}</td>
		<td>{{$age_of_good->ninty_days_plus}}</td>
		<td><a href="#" data-id="{{$age_of_good->id}}" class="batch">More</a></td>
		</tr>
		@endforeach
	</tbody>
  </table>
  <table id="excel_export" style="display: none;">
	<thead>
	  <tr>
	  	<th>Product_name</th> 
	  	<th>Total Qty</th> 
		<th>0-15 Days</th> 
		<th>15-30 Days</th>
		<th>30-60 Days</th> 
		<th>60-90 Days</th>
		<th>90+ Days</th>
	</thead>
	<tbody>
		@foreach($age_of_goods as $age_of_good)
		<tr>
		<td>{{$age_of_good->name}}</td>
		<td>{{$age_of_good->total_quantity}}</td>
		<td>{{$age_of_good->fiftten_days}}</td>
		<td>{{$age_of_good->thirty_days}}</td>
		<td>{{$age_of_good->sixty_days}}</td>
		<td>{{$age_of_good->ninty_days}}</td>
		<td>{{$age_of_good->ninty_days_plus}}</td>
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
<script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>
<script type="text/javascript">
	var datatable = null;

	var datatable_options = {"order": [[1, "asc"]], "stateSave": true};
 

	$(document).ready(function() {
		datatable = $('#datatable').DataTable(datatable_options);
	$('.buttons-excel').css('display','none');	
});

$('body').on('click', '.batch', function(e){
			e.preventDefault();
	$.get("{{url('inventory/items_batch')}}/"+$(this).data('id')+"/item",function(data){
				$('.crud_modal .modal-container').html("");
				$('.crud_modal .modal-container').html(data);
			});
			 $('.crud_modal').find('.modal-dialog').addClass('modal-lg');
       		$('.crud_modal').modal('show'); 
		
});

$('body').on('click', '.excel_export', function(){
         TableToExcel.convert(document.getElementById("excel_export"));
    });
</script>
@stop
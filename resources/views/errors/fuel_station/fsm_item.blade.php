@extends('layouts.master')
@section('head_links') @parent
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
@stop



@if(Session::get('module_name') == "fuel_station")
	@include('includes.fuel_station')
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
  <h4 class="float-left page-title">Items</h4>
   <a class="btn btn-danger float-right refresh" style="color: #fff">Refresh</a><a class="btn btn-danger float-right delete" style="color: #fff" >Delete</a>
  <a class="btn btn-danger float-right add" style="color: #fff">+ New</a>

</div>

<div class="float-left table_container" style="width: 100%; padding-top: 10px;">
	
<table id="datatable" class="table data_table" width="100%" cellspacing="0">
	<thead>
	  <tr>
	  <th> {{ Form::checkbox('check_all', 'check_all', null, ['id' => 'check_all'] ) }} <label for="check_all"><span></span></label></th>
				<th> Name </th>	
				<th> Category </th>
				<th> In Stock </th>							
				<th> Purchase Price </th>
				<th> selling Unit Price </th>				
				<th> selling Unit Price + Tax </th> 							
				<th> Status </th>
				<th> Action </th>
	  </tr>
	</thead>
	<tbody>
		  @foreach($inventory_items as $inventory_item)
	<tr>
			<td width="1">{{ Form::checkbox('inventory_item',$inventory_item->id, null, ['id' => $inventory_item->id, 'class' => 'item_check']) }}<label for="{{$inventory_item->id}}"><span></span></label></td>
		<td>{{ $inventory_item->name }}</td>
		
	<td>{{ $inventory_item->category_name }}</td>
		<td>{{$inventory_item->in_stock}} {{$inventory_item->unit}}</td>
		<td>{{ $inventory_item->purchase_price }}</td>
		<td>{{ $inventory_item->selling_price }}</td>
		<td><?php $sale_price = App\Custom::get_least_closest_date(json_decode($inventory_item->sale_price_data, true));
				if($inventory_item->include_tax != null) {
					echo App\Custom::two_decimal($sale_price['price']*(($inventory_item->tax/100) + 1)) ;
				}
				else {
					echo $sale_price['price'];
				}
			echo "<span style='color:#aaa'> From ".Carbon\Carbon::parse($sale_price['date'])->format('jS \\of M Y')."</span>" ;
				

				 ?></td>
			<td>
						@if($inventory_item->status == 1)
							<label class="grid_label badge badge-success status">Active</label>
						@elseif($inventory_item->status == 0)
							<label class="grid_label badge badge-warning status">In-Active</label>
						@endif
						
						<select style="display:none" id="{{ $inventory_item->id }}" class="active_status form-control">
							<option @if($inventory_item->status == 1) selected="selected" @endif value="1">Active</option>
							<option @if($inventory_item->status == 0) selected="selected" @endif value="0">In-Active</option>
						</select>
						
					</td>
		<td>
						
						<a data-id="{{$inventory_item->id}}" class="grid_label action-btn edit-icon edit"><i class="fa li_pen"></i></a>
				
						<a data-id="{{$inventory_item->id}}" class="grid_label action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a>
					
						<a href="{{route('item.show', $inventory_item->id)}}" data-id="{{$inventory_item->id}}" class="grid_label action-btn show-icon show"><i class="fa fa-eye"></i></a>
				
						@if($inventory_item->in_stock != null)
						<a href="javascript:;" data-id="{{$inventory_item->id}}" data-stock="{{$inventory_item->in_stock}}" class="grid_label badge badge-info create">Adjust Quantity</a>
						@endif
					
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

   var datatable_options = {"columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": [[1, "asc"]], "stateSave": true};

  $(document).ready(function() {

	datatable = $('#datatable').DataTable(datatable_options);

 
	$('.add').on('click', function(e) {

        e.preventDefault();
        $.get("{{ route('fsm_item.create') }}", function(data) {

        	$('.crud_modal .modal-container').html("");
        	$('.crud_modal .modal-container').html(data);
        });
        $('.crud_modal').find('.modal-dialog').addClass('modal-lg');
        $('.crud_modal').modal('show');


	});

  	$(document).delegate(".edit", "click", function(event){
		event.preventDefault();
	    var obj = $(this);
        $.get("{{ url('inventory/items') }}/"+$(this).data('id')+"/edit", function(data) {
        	$('.crud_modal .modal-container').html("");
        	$('.crud_modal .modal-container').html(data);
        	$('.crud_modal .modal-container').find('input[name=type]').closest('.form-group').hide();
        });
        $('.crud_modal').find('.modal-dialog').addClass('modal-lg');
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
			var url = "{{ route('item.status') }}";
			change_status(id, obj, status, url, "{{ csrf_token() }}");
		});

  	$('body').on('click', '.delete', function(){
		var id = $(this).data('id');

		var parent = $(this).closest('tr');
		var delete_url = '{{ route('tank.destroy') }}';
		delete_row(id, parent, delete_url, "{{ csrf_token() }}");
	});
	
  	


  });
  </script>
@stop
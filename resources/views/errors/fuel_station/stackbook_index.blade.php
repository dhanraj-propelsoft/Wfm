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
          <h4 class="float-left page-title">DailyStockBook</h4>
			<a class="btn btn-danger float-right refresh" style="color: #fff">Refresh</a>
          	<a class="btn btn-danger float-right multidelete" style="display:none; color: #fff">Delete</a>
          		<a class="btn btn-danger float-right edit" style="display:none; color: #fff">Edit</a>
          	<a class="btn btn-danger float-right add" style="color: #fff">+ New</a>
          	
          	
  
</div>





	<div class="float-left" style="width: 100%; padding-top: 10px">
	  	<table id="datatable" class="table data_table table-hover" width="100%" cellspacing="0">
	        <thead>
	            <tr>
	            	 <th> {{ Form::checkbox('check_all', 'check_all', null, ['id' => 'check_all'] ) }} <label for="check_all"><span></span></label></th>
	            	<th> Date  </th>
	            	<th> Tank </th>					
					<th> Product </th>						
					<th> Opening </th>				
					<th> Purchase </th>					
	            	<th> Sales </th>
	            	<th> Total Stock </th>			
					<th> Testing </th>
					<th> Closing </th>	
					<th> Unit Rate</th>			
					<th> Sales Worth </th>
					<th> Stock Worth </th>				
				
	            </tr>
	        </thead>
	        <tbody>      
	      	 @foreach($stockbook as $stockbook)
		<tr>
		<td width="1">{{ Form::checkbox('stockbook',$stockbook->id, null, ['id' => $stockbook->id, 'class' => 'item_check','data-id' => $stockbook->id]) }}<label for="{{$stockbook->id}}"><span></span></label></td>
	      	  
	      	  	<td>{{$stockbook->date}}</td>
	      	  	<td>{{$stockbook->tankname}}</td>
	      	  	<td>{{$stockbook->productname}}</td>
	      	  	<td>{{$stockbook->opening}}</td>
	      	  	<td>{{$stockbook->purchase}}</td>
	      	  	<td>{{$stockbook->sales}}</td>
	      	  	<td>{{$stockbook->total_stock}}</td>
	      	  	<td>{{$stockbook->testing}}</td>
	      	  	<td>{{$stockbook->closing}}</td>
	      	  	<td>{{$stockbook->unit_rate}}</td>
	      	  	<td>{{$stockbook->sales_worth}}</td>
	      	  	<td>{{$stockbook->stock_worth}}</td>
	      	  	
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

   var datatable_options = {"order": [[ 3, "desc" ]]};

	$(document).ready(function() {


	datatable = $('#datatable').DataTable(datatable_options);

	$('.item_check').on('click', function(e)
	 {
		
		if ($('.item_check').is(":checked")) 
		{

			$('.edit').show();
			$('.multidelete').show();

		}
		else
		{

		$('.edit').hide();
		$('.multidelete').hide();

		}
	});
 
		$('body').on('click', '.edit', function(e) {
  		var book_id =$('.item_check:checked').val();
  		//console.log(item_id);
		e.preventDefault();
		$.get("{{ url('fuel_station/stockbook_edit') }}/"+book_id, function(data) {
		  $('.crud_modal .modal-container').html("");
		  $('.crud_modal .modal-container').html(data);
		});
		$('.crud_modal').modal('show');
  	});


	$('.add').on('click', function(e) {

        e.preventDefault();
        $.get("{{ route('stockbook_create') }}", function(data) {

        	$('.crud_modal .modal-container').html("");
        	$('.crud_modal .modal-container').html(data);
        });
        
        $('.crud_modal').modal('show');
	});

$('body').on('click', '.multidelete', function() {
		var url = "{{ route('fuel_station.stockbook_multidestroy') }}";
		multidelete(url);
	});

	



		function multidelete( url) {
			var values = [];
			$(".data_table").find('tbody tr').each(function() {
				var value = $(this).find("td:first").find("input:checked").val();
				if(value != undefined) {
					values.push(value);
				}
			});
			$('.delete_modal_ajax').modal('show');
			$('.delete_modal_ajax_btn').off().on('click', function() {
				$.ajax({
					url: url,
					type: 'post',
					data: {
						_method: 'delete',
						_token: '{{ csrf_token() }}',
						id: values.join(",")
					},
					dataType: "json",
					success: function(data, textStatus, jqXHR) {
						datatable.destroy();
						var list = data.data.list;
						// alert(list);
						for(var i in list) {


							$('body').find("input.item_check[value="+list[i]+"]").closest('tr').remove();
						}
						$('#datatable').find('thead tr th:first :checkbox').prop('indeterminate', false);
						$("input.item_check, input[name=checkbox_all]").prop('checked', false);
						datatable = datatable = $('#datatable').DataTable(datatable_options);
						$('.delete_modal_ajax').modal('hide');
					},
					error: function(jqXHR, textStatus, errorThrown) {}
				});
			});
		}


	});

	</script>
@stop
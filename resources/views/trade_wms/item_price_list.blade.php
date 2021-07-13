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



<div class="fill header" style="height:40px;width: 102%;background-color: #e3e3e9;margin-left: -10px;">

  <h5 class="float-left page-title" style="padding-top: 8px;padding-left: 10px;"><b>Item Price List</b></h5>

  

	<!-- <a class="btn btn-danger float-right add" style="color: #fff">+ New</a> -->



</div>



<div class="modal-body">

	<div class="clearfix"></div>

		<div class="form-group">

			<div class="row">

				<div class="col-md-12">

				

					<div class="row">

						<div class="col-md-3">

						 	<label for="item_name">Item Name</label>

							{{ Form::text('item_name', null, ['class'=>'form-control', 'id' => 'item_name']) }}

						</div>

						<div class="col-md-3">

						 	<label  for="item_rate">Rate</label>

							{{ Form::text('item_rate', null, ['class'=>'form-control', 'id' => 'item_rate']) }} 

						</div>

						

						<div class="col-md-3">

						 	<label  for="vehicle_segment">Pricing Segment</label>

							{{ Form::select('vehicle_segment', $vehicle_segment, null, ['class' => 'form-control select_item', 'id' => 'vehicle_segment']) }}

						</div>

							<div class="col-md-3" style=" top: 25px;">

						  	<button class="btn btn-success tab_save_btn search"> Search</button>&nbsp;

							<button type="submit" class="btn btn-primary tab_save_btn update" style="display:none";> Update </button>

					  	</div>

					</div>

					<div class="row">

						<!-- <div class="col-md-3">

											 		<label class="col-sm-2 col-form-label" for="modal_name">Make</label>

						    {{ Form::select('vehicle_make', $vehicle_make_id, null, ['class'=>'form-control select_item', 'id' => 'vehicle_make']) }}

											 	</div>

											   	<div class="col-md-3">

											 	    <label class="col-sm-2 col-form-label" for="modal_name">Model</label>

						    {{ Form::select('vehicle_model', $vehicle_model_id, null, ['class'=>'form-control select_item', 'id' => 'vehicle_model']) }}

											 	</div>

											  	<div class="col-md-3">

											 	    <label class="col-sm-2 col-form-label" for="variant_name">Variant</label>

						    {{ Form::select('vehicle_variant', $vehicle_variant_id, null, ['class'=>'form-control select_item', 'id' => 'vehicle_variant']) }}

											 	</div> -->

					  

					</div>

        

				</div>		

			</div>

		</div>

</div>

<div class="float-left" style="width: 100%; padding-top: 10px;">

	<table id="datatable" class="table data_table" width="100%" cellspacing="0">

		<thead>

			<tr>

				<th> Item</th>
				<th> Pricing Segment  </th> 
				<th> Tax </th>               
				<th> Selling Price + Tax </th>
				<th> Price + Tax </th>
				<th> Status </th>

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



	datatable = $('#datatable').DataTable(datatable_options);







	$("input[name=item_name]").autocomplete({



		source: "{{ route('inventory_item_search') }}",

		minLength:2,

		select: function( event, ui ) {	

		      		

		      	console.log(ui);	      	



		       	$('input[name=item_name]').val(ui.item.label);		       		

	       		$('input[name=item_rate]').val(ui.item.price);

		       		     		



		       	}

	});



	$('body').on('click', '.status', function(e) {

			$(this).hide();

			$(this).parent().find('select').css('display', 'block');

		});

	

	$('body').on('change', '.active_status', function(e) {

			var status = $(this).val();

			var id = $(this).attr('id');

			var obj = $(this);

			var url = "{{ route('price_list_status_approval') }}";

			change_status(id, obj, status, url, "{{ csrf_token() }}");

		});



		$('.search').on('click',function(e){

			e.preventDefault();

			var html='';

			//alert();



			$.ajax({



				url: '{{ route('price_list_search') }}',

				method: 'POST',

				data:

				{

					_token: '{{ csrf_token()}}',

					item_name:$('input[name=item_name]').val(),

					vehicle_segment:$('select[name=vehicle_segment]').val(),

					



				},

				success:function(data,textStatus,jqXHR)

				{

					//alert();



					var details=data.data;

					//datatable.destory();

  					$('#datatable tbody').empty();

  					/*var updated_price=data.data.price;*/



					console.log(details);

					for(var i in details)

					{

  						var updated_price=details[i].price;
  						var selling_price = details[i].selling_price;
  						var tax_value = parseFloat(details[i].tax_value) + parseFloat(details[i].tax_value);

						var tax_amount = parseFloat(isNaN(tax_value) ? 0 : tax_value/100 ) * parseFloat(selling_price);

						var tax_amount1 = parseFloat(isNaN(tax_value) ? 0 : tax_value/100 ) * parseFloat(updated_price);
						var sale_tax_amount = (parseFloat(selling_price) + parseFloat(tax_amount));

  						if(updated_price == null)

  						{

  							var price= parseFloat(sale_tax_amount).toFixed(2);

  						}



  						else

  						{

  							var price= parseFloat(details[i].price) + parseFloat(tax_amount1);

  						}



						html+=`<tr>

					 

					<input type="hidden" name="price_list_id[]" value="`+details[i].vehicle_price_list_id+`">

					

					<td><input type="hidden" name="item_id[]"

					value="`+details[i].id+`">`+details[i].name+`</td>

					<td><input type="hidden" name="segment_id[]" value="`+details[i].segment_id+`">`+details[i].segment+`</td>

					<td><input type="hidden" name="tax[]" value="`+tax_value+`">`+details[i].tax+`</td>

					<td><input type="hidden" name="base_price[]" value="`+selling_price+`" disabled>`+parseFloat(sale_tax_amount).toFixed(2)+`</td>

					<td><input type="text" name="item_price[]" value="`+parseFloat(price).toFixed(2)+`">

					</td>

					<td><label class="grid_label badge badge-success status">Active</label>

						<select style="display:none" id="`+data.data.id+`" class="active_status form-control">

							<option value="1">Active</option>

							<option value="0">In-active</option>

						</select>

					</td>

					

					</tr>`;

					}

					call_back_optional(html,`add`,``);



					//call_back(html,`add`,``);

					//datatable = $('#datatable').DataTable(datatable_options);

					//$('#datatable tbody').append(html);

					$('.update').show();

					

					



				},

				error:function()

				{



				}





			});



		});





		$('.update').on('click',function(e){

			e.preventDefault();



				var price_list_id = $("input[name='price_list_id[]']").map(function(){

					return this.value;

				}).get();



				var item_id = $("input[name='item_id[]']").map(function(){ 

        		return this.value; 

        	}).get();

				var segment_id= $("input[name='segment_id[]']").map(function(){ 

        		return this.value; 

        	}).get();

				var base_price=$("input[name='base_price[]']").map(function(){ 

        		return this.value; 

        	}).get();

				var item_price=$("input[name='item_price[]']").map(function(){ 

        		return this.value; 

        	}).get();

				var tax=$("input[name='tax[]']").map(function(){ 
        		return this.value; 
        	}).get();


				$.ajax({



					url: '{{ route('price_list_update') }}',

					type: 'POST',

					data: 

					{

						_token: '{{ csrf_token()}}',

						price_list_id: price_list_id,

						item_id: item_id,

						segment_id: segment_id,

						base_price: base_price,

						item_price: item_price,

						tax: tax,

					},

					success:function(data,textStatus,jqXHR)

					{

						//alert();

  					//$('#datatable tbody').html("");

  					$('.update').hide();

  					$('.search').show();

  					$('input[name=item_name]').val("");

  					$('input[name=item_rate]').val("");

  					$('select[name=vehicle_segment]').attr("placeholder" ,"Pricing");

					alert_message(data.message,'success');





					},

					error:function()

					{



					}



				});

		});







  	//**** Start of getting model based on make ****//



		

		/*$('#vehicle_make').change(function() {

			//alert();

			var make = $('#vehicle_make option:selected').val();

			//alert(make);

			$('#vehicle_model').html('');

			$('#vehicle_model').append("<option value=''>Select Model</option>");

			$('#vehicle_variant').html('');

			$('#vehicle_variant').append("<option value=''>Select Varient</option>");

			$.ajax({

				url: '{{ route('get_vehicle_model_name') }}',

				type: "post",

				data: {

					_token: '{{ csrf_token() }}',

					id: make,

				},

				dataType: "json",

				success:function(data, textStatus, jqXHR) {

					var model = data.result;

					for (var i in model) {

						$('#vehicle_model').append("<option value='"+model[i].id+"'>"+model[i].name+"</option>");

					}

				},

				error:function(jqXHR, textStatus, errorThrown){

				}

			});

		});*/

//**** End of getting model based on make ****//



//**** Start of getting varient based on modal ****//

		/*$('#vehicle_model').change(function() {

			var model_id = $('#vehicle_model option:selected').val();

			//alert(make);

			$('#vehicle_variant').html('');

			$('#vehicle_variant').append("<option value=''>Select Varient</option>");

			$.ajax({

				url: '{{ route('get_vehicle_variant_name') }}',

				type: "post",

				data: {

					_token: '{{ csrf_token() }}',

					id: model_id,

				},

				dataType: "json",

				success:function(data, textStatus, jqXHR) {

					var model = data.result;

					for (var i in model) {

						$('#vehicle_variant').append("<option value='"+model[i].id+"'>"+model[i].name+"</option>");

					}

				},

				error:function(jqXHR, textStatus, errorThrown){

				}

			});

		});	*/

//**** End of getting varient based on modal ****//	

	



  });

  </script>

@stop
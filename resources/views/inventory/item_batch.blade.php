<div class="modal-header" style="background-color: #e9ecef;">
	<h5 class="modal-title float-right"><b>Item Batches</b></h5>
	<a  class="close" data-dismiss="modal">&times;</a>
</div>	

	{!! Form::open(['class' => 'form-horizontal validateform']) !!}                                        
	{{ csrf_field() }}

<div class="modal-body" style="overflow-y: scroll;max-height: 600px;">
  	<div class="form-body">

	  	{!! Form::hidden('id', null) !!}	  	

	  	<!-- <div class="">
			<h5 class="modal-title float-left">Item Batches :</h5>
		</div> -->	 
	
		<table id="datatable-batch" class="table data_table table-hover" width="100%" cellspacing="0">
			<thead>
			  <tr>			  	
				<th> Item </th>

				@if($main_type->category_type_id == 1)
				<th> Batch No </th>
				@endif

				@if($main_type->category_type_id == 2)
				<th> Segment </th>
				@endif

				@if($main_type->category_type_id == 1)
				<th> Quantity </th>				
				<th> Purchase Price </th>
				@endif

				<th> Sale Price </th>
				<th> Tax </th>
				<th> Action </th>
			  </tr>
			</thead>
			<tbody>

				@foreach($item_batches as $item_batch)
				<tr>
					<td>{{ $item_batch->name }}</td>

					@if($main_type->category_type_id == 1)
					<td>{{ $item_batch->batch_number }}</td>
					@endif

					@if($main_type->category_type_id == 2)
					<td>{{ $item_batch->segment_name }}</td>
					@endif
					@if($main_type->category_type_id == 1)
					<td>{{ $item_batch->quantity }}</td>
					<td>{{ $item_batch->purchase_plus_tax_price }}</td>
					@endif
					
					@if($main_type->category_type_id == 1)

						@if(isset($item_batch->selling_plus_tax_price))
						  	<td>{{$item_batch->selling_plus_tax_price}}</td>
						  @endif
					@endif

					@if($main_type->category_type_id == 2)
					  	<td>{{$item_batch->service_batch_price}}</td>
					@endif

					<td>{{ $item_batch->tax }}</td>

					@if($main_type->category_type_id == 1)
						@if(isset($item_batch->item_batch_id))
						<td>
							<a href="javascript:;" data-id="{{ $item_batch->item_batch_id }}" data-item-id="{{ $item_batch->item_id }}"  data-item-type = "{{$main_type->category_type_id }}" class="select_batch">Select</a>
						@endif
					@endif

					@if($main_type->category_type_id == 2)
						<td>
							<a href="javascript:;" data-id="{{ $item_batch->service_batch_id }}" data-item-id="{{ $item_batch->item_id }}" data-item-type = "{{$main_type->category_type_id }}" class="select_batch">Select</a>
					@endif

						<!-- <button type="button" data-id="{{ $item_batch->id }}" class="btn btn-success tab_print_btn"> Select  </button> -->
					</td>
					

				</tr>
				
			 @endforeach
			</tbody>
	  	</table>
	
  	</div>
</div>

<div class="modal-footer" style="background-color: #e9ecef;">                                            
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	
</div>


{!! Form::close() !!}



<script type="text/javascript">
   
   	//var datatable = null;

  	var datatable_options = {"columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": [[1, "asc"]], "stateSave": true};  
			
	/*if (! $.fn.DataTable.isDataTable('#datatable-batch') ) { 

		//datatable = $('#datatable12').DataTable(datatable_options); 

		//datatable = $('#datatable-batch').DataTable(datatable_options);
	}*/

	var tables = $.fn.dataTable.fnTables(true);

		$(tables).each(function () {
		  //$(this).dataTable().fnClearTable();
		  $(this).dataTable().fnDestroy();
		});
	
	datatable = $('#datatable-batch').DataTable(datatable_options);
	
  
  	$(document).ready(function() {

		//basic_functions();
		
		//datatable = $('#datatable').DataTable(datatable_options);

		$('.select_batch').on('click', function(){

   			//e.preventDefault();

	 		var id = $(this).attr('data-id');

	 		var item_id = $(this).attr('data-item-id');

	 		var item_type = $(this).attr('data-item-type');
	 		
		 	var tr_id = $('.crud_modal').find('.modal-dialog').attr('data-tr');

		 	var over_all_discount = $('body').find('input[name=new_discount_value]').val();

		 	var current_row = $('#'+tr_id);

			$.ajax({
		  		url: "{{ route('select_batch') }}",
				type: 'post',
				data: {
					_token : '{{ csrf_token() }}',
			  		id:id,
			  		item_id : item_id,
			  		item_type : item_type
				},
				success:function(data, textStatus, jqXHR) {

					$('.crud_modal').find('.modal-dialog').addClass('modal-lg');
					$('.crud_modal').modal('hide');

					console.log(data.batch_query.id);

					var module_name = data.module_name;
					var goods_batch_id = data.batch_query.goods_batch_id;
					var service_batch_id = data.batch_query.service_batch_id;
					var batch_stock = data.batch_query.quantity;

					

					$('.crud_table').find(current_row).find('select[name=item_id]').val(data.batch_query.id);

					$('.crud_table').find(current_row).find('select[name=item_id]').trigger('change',[1]);

					if(module_name == 'inventory')
					{
						$('.crud_table').find(current_row).find('input[name=rate]').val(data.batch_query.purchase_price);

						$('.crud_table').find(current_row).find('input[name=amount]').val(data.batch_query.purchase_price);

						$('.crud_table').find(current_row).find('input[name=tax_total]').val(data.batch_query.purchase_plus_tax_price);

						$('.crud_table').find(current_row).find('input[name=base_price]').val(data.batch_query.selling_price);

						$('.crud_table').find(current_row).find('input[name=new_base_price]').val(data.batch_query.selling_price);

							$('.select_item').each(function() { 
							var select = $(this);  
							if(select.data('select2')) { 
								select.select2("destroy"); 
							} 
							});

						$('.crud_table').find(current_row).find('select[name=tax_id]').val(data.batch_query.purchase_tax);
					}

					if(module_name == 'trade' || module_name == 'trade_wms')
					{

						if(over_all_discount != null)
						{
							$('.crud_table').find(current_row).find(' input[name=discount_value]').val(over_all_discount);
						}

						if(item_type == 1)
						{
							$('.crud_table').find(current_row).find('input[name=rate]').val(data.batch_query.selling_price);	
						}

						if(item_type == 2){
							$('.crud_table').find(current_row).find('input[name=rate]').val(data.service_base_price);
						}
						
						if(item_type == 1)
						{
							$('.crud_table').find(current_row).find('input[name=batch_id]').val(goods_batch_id);
						}

						if(item_type == 2)
						{
							$('.crud_table').find(current_row).find('input[name=batch_id]').val(service_batch_id);
						}

						//$('.crud_table').find('tr').last().find('input[name=rate]').val(data.data.selling_price);

						if(item_type == 1)
						{
							$('.crud_table').find(current_row).find('input[name=amount]').val(data.batch_query.selling_price);
						}

						if(item_type == 2)
						{
							$('.crud_table').find(current_row).find('input[name=amount]').val(data.service_base_price);
						}							

							$('.select_item').each(function() { 
							var select = $(this);  
							if(select.data('select2')) { 
								select.select2("destroy"); 
							} 
							});

						$('.crud_table').find(current_row).find('select[name=tax_id]').val(data.batch_query.tax_id);

						if(item_type == 1)
						{
							$('.crud_table').find(current_row).find('input[name=tax_total]').val(data.batch_query.selling_plus_tax_price);
						}
						if(item_type == 2)
						{
							$('.crud_table').find(current_row).find('input[name=tax_total]').val(data.batch_query.service_batch_price);
						}
						
					}			


					$('.crud_table').find(current_row).find('input[name=quantity]').val(1);

					$('.crud_table').find(current_row).find('input[name=in_stock]').val(batch_stock);

					/*for(var i in result) {
						$('select[name=category_id]').append(`<option value='`+result[i].id+`'>`+result[i].name+`</option>`);
					}*/
					
					$('.select_item').select2();
										
					table();

					$('.loader_wall_onspot').hide();

				}

			});

		});

   	});  		 		
  		

</script>
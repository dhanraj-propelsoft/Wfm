
<div class="modal-header">
  <h4 class="modal-title float-right">Edit Item Group</h4>
</div>
{!!Form::model($inventory_items, ['class' => 'form-horizontal validateform'])!!}
										
							{{ csrf_field() }}
	<div class="modal-body">

  		<div class="form-body">

  			{!! Form::hidden('id', null) !!}

				<div class="row">
					<div class="form-group col-md-8">

							<div class="row">
								<div class="form-group col-md-12">
								{{ Form::label('type', 'Inventory Type', array('class' => 'control-label col-md-12 required')) }}
							  
								<div class="col-md-12">
									<div class="row">
									 
									<div class="col-md-4">
									<input name="type" value="1" type="radio" class = 'md-radiobtn'
									id = 'goods' @if($inventory_items->category_type_id == 1) checked="checked" @endif />
								<label for="goods"><span></span>Goods</label>
									</div>

									<div class="col-md-4">
									<input name="type" value="2" type="radio" class = 'md-radiobtn'
									id = 'service' @if($inventory_items->category_type_id == 2) checked="checked" @endif />
								<label for="service"><span></span>Service</label>
									</div>
										
									</div>
									</div>
								</div>

								<div class="form-group col-md-12">
									{{ Form::label('Name', 'Name', array('class' => 'control-label col-md-5 required')) }}
									<div class="col-md-12">{!! Form::text('name', null, ['class'=>'form-control', 'placeholder'=>'Name','id'=>'name']) !!}</div>
								</div>

							</div>
							

							<div class="row">
								<div class="form-group col-md-4">
							  	<div class="col-md-12"> 
							  		
							  		<input name="grouping" value="1" type="radio" class = 'md-radiobtn'
									id = 'group_price' @if($inventory_items->is_group == 1) checked="checked" @endif />
								<label for="group_price"><span></span>Group Price</label>
							  	</div>
								</div>
								<div class="form-group col-md-4">
								  	<div class="col-md-12"> 
								  		
								  		<input name="grouping" value="0" type="radio" class = 'md-radiobtn' id = 'individual_price'
									@if($inventory_items->is_group == 0) checked="checked" @endif />
									<label for="individual_price"><span></span>Individual Price</label>
								  </div>
								</div>
							</div>

							{!! Form::hidden('category_id',null,['class' => 'form-control' ]) !!}

							

					</div>

				  	<div class="form-group col-md-4">
						<div class="row">
							<div class="col-md-6">
							  <div style="position: relative; height: 130px; width: 130px;" class="dropzone" id="image-upload"> </div>
							</div>
						</div>
				  	</div>
				</div>
				
				
				<div class="form-group">
				  	<table class="table">
							        <thead>
							          <tr>
							            <th>Item</th>
							            <th class="price">Price</th>
							            <th style=" @if($inventory_items->type_name == 'service') display:none @endif">Quantity</th>
							            <th></th>
							          </tr>
							        </thead>
							        <tbody>
							        @if(count($group_items) > 0 ) 
							        @foreach($group_items as $item)
							        <tr>
							        
							        <td>
										
										{!! Form::select('item_id', $inventory_item, $item->item_id, ['class' => 'select_item form-control', 'id' => 'item_id']) !!}
										
									</td>

									<td>
									{{ Form::text('price', $item->price, ['class'=>'form-control','disabled' => 'true']) }}
									</td>
									
									<td style=" @if($inventory_items->type_name == 'service') display:none @endif">
									
									{!! Form::text('quantity', $item->quantity, ['class'=>'form-control numbers', 'placeholder'=>'Item Quantity','id'=>'quantity']) !!}
									
									</td>

									
									
									<td>
									<select name='item_tax_id' class='form-control select_item taxes' id = 'item_tax_id' readonly>
										 <option value="">Select Tax</option>
										 @foreach($taxes as $tax) 
										 <option @if($item->tax_id == $tax->id) selected="selected" @endif value="{{$tax->id}}" data-value="{{$tax->value}}" data-type="{{$tax->tax_type}}">{{$tax->display_name}}</option>
										 @endforeach
									</select> 
					
						 		</td>
									<td><a class="grid_label action-btn delete-icon remove_row"><i class="fa fa-trash-o"></i></a> <a class="grid_label action-btn edit-icon add_row"><i class="fa fa-plus"></i></a></td>
									</tr>
									@endforeach
									@endif 										
									</tbody>
								</table>
				</div><br>

				<div class="row">
					  <div class="form-group col-md-6"> {{ Form::label('hsn', 'HSN', array('class' => 'control-label col-md-5')) }}
											<div class="col-md-12"> {!! Form::text('hsn', null, ['class'=>'form-control gst_no', 'placeholder'=>'HSN', 'id'=>'hsn', 'readonly']) !!} </div>
										  </div>
					  <div class="form-group col-md-3 unit">
							{{ Form::label('unit_id', 'Unit', array('class' => 'control-label col-md-5 required')) }}

						<div class="col-md-12"> 
							{!! Form::select('unit_id', $units, null, ['class' => 'select_item form-control', 'id' => 'unit_id']) !!} 
						</div>
					  </div>
					  <!-- <div class="form-group col-md-3 unit">
						{{ Form::label('minimum_order_quantity', 'MOQ', array('class' => 'control-label col-md-5 required')) }}
						<div class="col-md-12">
							{!! Form::text('minimum_order_quantity', null, ['class'=>'form-control numbers', 'placeholder'=>'Minimum Order Quantity','id'=>'minimum_order_quantity']) !!}
							 </div>
					  </div> -->
				</div><br>
				
				<div class="row">
					<div class="form-group col-md-4">
						  <div class="col-md-12 include_tax"> {{ Form::checkbox('include_tax', '1', null, array('id' => 'include_tax')) }}
							<label for="include_tax"><span></span>Include Sale Tax</label>
						  </div>
					</div>
				</div>

				<div class="row">
					  <div class="form-group col-md-3 tax_id">
							{{ Form::label('tax_id', 'Sales Tax', array('class' => 'control-label col-md-12 required')) }}	
								<div class="col-md-12">
									<select name='tax_id' class='form-control select_item' id = 'tax_id'>
									 <option value="">Select Tax</option>
									 @foreach($taxes as $tax) 
									 <option value="{{$tax->id}}" @if($inventory_items->tax_id == $tax->id) selected="" @endif  data-value="{{$tax->value}}" data-type="{{$tax->tax_type}}">{{$tax->display_name}}</option>
									 @endforeach
									</select> 
									</div>
								</div>

					  <div class="form-group col-md-3 list_price"> 
					  	{{ Form::label('list_price', 'List Price', array('class' => 'control-label col-md-12 required')) }}
						<div class="col-md-12"> {!! Form::text('list_price',$price, ['class'=>'form-control numbers', 'placeholder'=>'List Price','id'=>'list_price']) !!} </div>
					  </div>


					  <div class="form-group col-md-3 discount"> {{ Form::label('discount', 'Discount', array('class' => 'control-label col-md-12 required')) }}
						<div class="col-md-12"> {!! Form::text('discount', 0, ['class'=>'form-control numbers', 'placeholder'=>'Discount','id'=>'discount']) !!} </div>
					  </div>

					  <div class="form-group col-md-3 sale_price"> 
					  {{ Form::label('sale_price', 'Sale Price', array('class' => 'control-label col-md-12 required')) }}
					  <div class="col-md-12">{!! Form::text('sale_price', $price, ['class'=>'form-control', 'placeholder'=>'Sale Price','id'=>'sale_price']) !!}
					  </div>
					  </div>
				</div>

				<div class="row">
				  	<div class="form-group col-md-3">
				  	{!! Form::label('on_date', 'On Date', array('class' => 'control-label col-md-6 required')) !!}


				  	<div class="col-md-12">                     
				  	{!! Form::text('on_date',$on_date,['class' => 'form-control date-picker', 'data-date-format' => 'dd-mm-yyyy', 'id'=>'on_date']) !!}
				  	</div>
				  	</div>

				  	<div class="form-group col-md-3">
				  	{{ Form::label('income_account', 'Sale Account', array('class' => 'control-label col-md-12 required')) }}

				  	<div class="col-md-12">
				  	{!! Form::select('income_account',  $sale_account , null, ['class' => 'select_item form-control', 'id' => 'income_account']) !!}
				  	</div>
				  	</div>
				</div>

				<!-- <div class="row">
					<div class="form-group col-md-4 main_inventory">
						<?php 
						if(App\Organization::checkModuleExists('inventory', Session::get('organization_id'))) {
							$selection = true;
						} else {
							$selection = false;
						} 
						?>
						<div class="col-md-12"> {{ Form::checkbox('purchase', '1', $selection, array('id' => 'purchase')) }}
						  <label for="purchase"><span></span>Maintain Inventory</label>
						</div>
					</div>
				</div>

				<div class="form-group purchase">
					<div class="row">
						<div class="form-group col-md-4"> 
						  {{ Form::label('initial_quantity', 'Initial Quantity', array('class' => 'control-label col-md-12 required')) }}
						  
						  <div class="col-md-12">
						  	{!! Form::text('initial_quantity', ($inventory_item_stocks != null) ? $inventory_item_stocks->in_stock: null, ['class'=>'form-control numbers', 'placeholder'=>'Quantity','id'=>'low_stock']) !!}
						  </div>
						  
						</div>


						<div class="form-group col-md-4"> 
						  {{ Form::label('low_stock', 'Low Stock Alert', array('class' => 'control-label col-md-12')) }}
						  <div class="col-md-12">{!! Form::text('low_stock', null, ['class'=>'form-control numbers', 'placeholder'=>'Low Stock Alert','id'=>'low_stock']) !!}
						  </div>
						</div>

						<div class="form-group col-md-4"> 
						  {{ Form::label('sku', 'SKU', array('class' => 'control-label col-md-5')) }}
						  <div class="col-md-12">{!! Form::text('sku', null, ['class'=>'form-control', 'placeholder'=>'SKU','id'=>'sku']) !!}
						  </div>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-md-4"> 
						  <div class="col-md-12">
						  {{ Form::checkbox('include_purchase_tax', '1', '', array('id' => 'include_purchase_tax')) }} <label for="include_purchase_tax"><span></span>Include Purchase Tax</label>
						  </div>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-md-3">
							{{ Form::label('purchase_tax_id', 'Purchase Tax', array('class' => 'control-label col-md-12 required')) }}	
							<div class="col-md-12">								
							<select name='purchase_tax_id' class='form-control select_item' id = 'purchase_tax_id'>
							 <option value="">Select Tax</option>
							 @foreach($purchase_taxes as $tax) 
							 <option value="{{$tax->id}}" data-value="{{$tax->value}}" data-type="{{$tax->tax_type}}">{{$tax->display_name}}</option>
							 @endforeach
							</select> 							
							</div>
						</div>

						<div class="form-group col-md-3"> 
							{{ Form::label('purchase_price', 'Purchase Price', array('class' => 'control-label col-md-12 required')) }}
							<div class="col-md-12">{!! Form::text('purchase_price', null, ['class'=>'form-control', 'placeholder'=>'Purchase Price','id'=>'purchase_price']) !!}
							</div>
						</div>
						

						<div class="form-group col-md-3">
							{{ Form::label('expense_account', 'Purchase Account', array('class' => 'control-label col-md-12 required')) }}

							<div class="col-md-12">
							{!! Form::select('expense_account',  $purchase_account , null, ['class' => 'select_item form-control', 'id' => 'expense_account']) !!}
							</div>
						</div>

						<div class="form-group col-md-3">
							{{ Form::label('inventory_account', 'Inventory Account', array('class' => 'control-label col-md-12 required')) }}
							<div class="col-md-12">                   
							  {{ Form::select('inventory_account', $inventory_account, null, ['class' => 'form-control select_item', 'id' => 'inventory_account']) }} 
							 </div>
						</div>
					</div>
				</div> -->

				<div class="row">
					  <div class="form-group col-md-12"> {{ Form::label('description', 'Description', array('class' => 'control-label col-md-5')) }}
						<div class="col-md-12">{!! Form::textarea('description', null, ['class'=>'form-control', 'placeholder'=>'Description','id'=>'description', 'size' => '3x4']) !!} </div>
					  </div>
				</div>

				

  		</div>
	</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
  <button type="submit" class="btn btn-success">Submit</button>
</div>
{!! Form::close() !!} 
<script type="text/javascript">

var image_upload = new Dropzone("div#image-upload", {
	paramName: 'file',
	url: "{{route('item_group_image_upload')}}",
	params: {
	  _token: '{{ csrf_token() }}'
	},
	dictDefaultMessage: "Drop or click to upload image",
	clickable: true,
	maxFilesize: 5, // MB
	acceptedFiles: "image/*",
	maxFiles: 10,
	autoProcessQueue: false,
	addRemoveLinks: true,
	removedfile: function(file) {
	  file.previewElement.remove();
	},
	queuecomplete: function() {
	  image_upload.removeAllFiles();
	}
  });

$(document).ready(function() {

	basic_functions();

	
	$('select[name=item_id]').on('change', function() {
		
		var obj = $(this);
		var id = obj.val();

		if(id != "") {

			$.ajax({

				 url: '{{ route('item_group.get_item_price') }}',
				 type: 'post',
				 data: {
					_token : '{{csrf_token()}}',
					id: id,
				 },
				 dataType: "json",
				 success:function(data, textStatus, jqXHR) {
					//var result = data.price;

					obj.closest('tr').find('input[name=price]').val(data.price);
					obj.closest('tr').find('input[name=quantity]').prop("disabled", false).val(1);
					obj.closest('tr').find('select[name=item_tax_id]').prop("disabled", true).val(data.tax_id);


					obj.closest('tr').find('select[name=item_tax_id]').trigger('change');
					

					if($('select[name=tax_id]').val() == ''){
						
						$('select[name=tax_id]').val(data.tax_id);
						$('input[name=hsn]').val(data.hsn);
						obj.closest('tr').find('select[name=item_tax_id]').val(data.tax_id);

						$('select[name=tax_id]').val(data.tax_id).trigger('change');

					}
					else{


						if($('select[name=tax_id] option:selected').data('value') < data.tax_value) {

							$('select[name=tax_id]').val(data.tax_id);
							$('input[name=hsn]').val(data.hsn);

							$('select[name=tax_id]').val(data.tax_id).trigger('change');
						}
					}
				}
			});
		} else {
			obj.closest('tr').find('input[name=quantity], input[name=price]').val("");
			obj.closest('tr').find('input[name=quantity]').prop("disabled", true).val(1);
			obj.closest('tr').find('select[name=item_tax_id]').val(data.tax_id);
			$('select[name=tax_id]').val(data.tax_id);
			$('input[name=hsn]').val(data.hsn);

			obj.closest('tr').find('select[name=item_tax_id]').trigger('change');
			$('select[name=tax_id]').val(data.tax_id).trigger('change');
		}
	});

	@if (!App\Organization::checkModuleExists('inventory', Session::get('organization_id')))
		$(".purchase").hide();
	@endif

	

	$('input[name=purchase]').on('change', function() {
	
			if($(this).is(":checked")) {
					
				$(".purchase").show();
			} 
			else {
				$(".purchase").hide();
				$('input[name=initial_quantity]').val('');
				$('input[name=low_stock]').val('');
				$('input[name=purchase_price]').val('');
				$('input[name=sku]').val('');
				$('input[name=include_purchase_tax]').prop('checked', false);
				$('select[name=expense_account]').val('');
				$('select[name=expense_account]').trigger('change');
				$('select[name=inventory_account]').val('');
				$('select[name=inventory_account]').trigger('change');
			}
	});

	grouping($('select[name=item_id]'), $('input[name=grouping]:checked').val());

	$('input[name=grouping]').trigger('change');

	$('input[name=grouping]').on('change', function() {
		var obj = $('select[name=item_id]');

		grouping(obj, $(this).val());

	});

	function grouping(obj, isGroup) {
		if(isGroup == 0) {
			
			obj.closest('tr').find('input[name=price]').prop("disabled", false);
				$('.tax_id').hide();
				$('.list_price').hide();
				$('.discount').hide();
				$('.sale_price').hide();
				$('input[name=include_purchase_tax]').hide();
				$('.include_tax').hide();
		}
		else{
			obj.closest('tr').find('input[name=price]').prop("disabled", true);
				$('.tax_id').show();
				$('.list_price').show();
				$('.discount').show();
				$('.sale_price').show();
				$('.include_tax').show();
		}
	}

	


	$('input[name=list_price], input[name=discount]').on('change input', function() {

		var list_price = $('input[name=list_price]').val();
		var discount = $('input[name=discount]').val();
		var sale_price = $('input[name=sale_price]');
	
			if($.trim(list_price).length > 0) {
				if($.trim(discount).length > 0 && discount != 0 ) {
					sale_price.val( $.trim(list_price) - ($.trim(list_price) * (discount/100)) );
				} else {
					sale_price.val($.trim(list_price));
				}
			} else {
				sale_price.val("");
			}
	});

	@include('modals.add_gst')

		$('body').off('click', '.add_row').on('click', '.add_row', function() {
			var obj = $(this);
			var quantity = obj.closest("tr").find('input[name="quantity"]');
			var item = obj.closest("tr").find('select[name="item_id"]');
			var remaining_item = obj.closest("tr").find('select[name="item_id"] > option');

			if(item.val() != "" && quantity.val() != "" && quantity.val() != 0 ) {
			  $('.select_item').each(function() { 
				var select = $(this);  
				if(select.data('select2')) { 
				  select.select2("destroy"); 
				} 
		 	});

			var clone = $(this).closest('tr').clone(true, true);
			var selected_item = item.find(':selected').val();
			clone.find('select[name=item_id], input[name=quantity]').val("");
			clone.find('select[name=item_id] > option[value="' + selected_item + '"]').wrap('<span>');

			if(remaining_item.length > 1) {

				clone.find('td').last().html('<a class="grid_label action-btn delete-icon remove_row"><i class="fa fa-trash-o"></i></a><a class="grid_label action-btn edit-icon add_row"><i class="fa fa-plus"></i></a>');

				if(remaining_item.length == 2) {
				  clone.find('td').last().html('<a class="grid_label action-btn delete-icon remove_row"><i class="fa fa-trash-o"></i></a>');
				}

				obj.closest('tr').after(clone);
			  }

				obj.parent().html('<a class="grid_label action-btn delete-icon remove_row"><i class="fa fa-trash-o"></i></a>');
				item.find('option[value!="' + selected_item + '"]').wrap('<span>');

					$('.select_item').select2();
					}
		});

		$('body').on('click', '.remove_row', function() {
		
			var obj = $(this);
			var item = obj.closest("tr").find('select[name="item_id"]');
			var remaining_item = obj.closest("tr").find('select[name="item_id"] > option');
			var last_row_item = obj.closest("table").find('tr').last().find('select[name="item_id"] > option');
			var selected_item = item.find(':selected').val();
			var selected_item_array = [];

			last_row_item.each(function() {
			  selected_item_array.push($(this).val());
			});

			selected_item_array.push(selected_item);
	  
			obj.closest('tr').remove();

			for (var i in selected_item_array) {
			  $('select[name=item_id]:last').find('span > option[value="' + selected_item_array[i] + '"]').unwrap();
			}

			$('select[name="item_id"]:last > span > option').unwrap();

			var row_index = $('.table tbody > tr').length;

			//console.log(row_index);

			if(row_index > 1) {
			  $('.table').find('tr').last().find('td').last().html('<a class="grid_label action-btn delete-icon remove_row"><i class="fa fa-trash-o"></i></a><a class="grid_label action-btn edit-icon add_row"><i class="fa fa-plus"></i></a>');
			} else {
			  $('.table').find('tr').last().find('td').last().html('<a class="grid_label action-btn edit-icon add_row"><i class="fa fa-plus"></i></a>');
			}

		});

		/*$('input[name=type]').on('change', function(){
		  
				var obj = $(this);
				var id = obj.val();

				if(obj.attr("id") == 'service') {
				  $('.main_inventory').hide();
				  $('.unit').hide();
				  $('.quantity').hide();
				  $('.item').text('Service');

				}
				else {
				  $('.main_inventory').show();
				  $('.unit').show();
				  $('.quantity').show();
				  $('.item').text('Item');
				}

				var category_id = $('select[name=category_id]');

				category_id.empty();
				category_id.append("<option value=''>Select Category</option>");
				category_id.val("").trigger("change");
				
				$.ajax({
				  url: "{{ route('get_categories_group') }}",
					type: 'get',
					data: {
					  id:id,

					},
					success:function(data, textStatus, jqXHR) {
						  var result = data;
						  //console.log(result);
						  for(var i in result) {

							$('select[name=category_id]').append(`<option value='`+result[i].id+`'>`+result[i].name+`</option>`);

						  }
						  

						}

				  });

		});*/

		
	});

		$('.validateform').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input

            rules: {
                name: {
                    required: true
                },
                
                unit_id: {
                    required: true
                },
				initial_quantity: {
					required: true
				},
				list_price: {
					required: true
				},
				discount: {
					required: true
				},
				sale_price: {
					required: true
				},
				income_account: {
					required: true
				}
            },

            messages: {
                 name: {
                    required: "Inventory Item Name is required."
                },
                
                
                unit_id: {
                    required: "Unit is required."
                },
				initial_quantity: {
					required: "Initial Quantity is required."
				},
				list_price: {
					required: "List Price is required."
				},
				discount: {
					required: "Discount is required."
				},
				sale_price: {
					required: "Sale Price Quantity is required."
				},
				income_account: {
					required: "Income Account is required."
				}
            },
            

            invalidHandler: function(event, validator) { //display error alert on form submit   
                $('.alert-danger', $('.login-form')).show();
            },

            highlight: function(element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },

            success: function(label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },

            submitHandler: function(form) {
            	$('.loader_wall_onspot').show();
				$.ajax({
				 url: '{{ route('item_group.update') }}',
				 type: 'post',
				 data: {
				 	_token: '{{ csrf_token() }}',
				 	_method: 'PATCH',
				 	id: $('input[name=id]').val(),
				 	name: $('input[name=name]').val(),
				 	//sku: $('input[name=sku]').val(),
				 	hsn: $('input[name=hsn]').val(),
				 	//minimum_order_quantity: $('input[name=minimum_order_quantity]').val(),
				 	//purchase_price: $('input[name=purchase_price]').val(),
				 	list_price: $('input[name=list_price]').val(),
				 	description: $('textarea[name=description]').val(),
				 	low_stock: $('input[name=low_stock]').val(),
				 	include_tax: $('input[name=include_tax]:checked').val(),
				 	//include_purchase_tax: $('input[name=include_purchase_tax]:checked').val(),
				 	income_account: $('select[name=income_account]').val(),
				 	//expense_account: $('select[name=expense_account]').val(),
				 	//inventory_account: $('select[name=inventory_account]').val(),
				 	//category_id: $('input[name=category_id]').val(),
				 	sale_price: $('input[name=sale_price]').val(),
				 	//initial_quantity: $('input[name=initial_quantity]').val(),
				 	unit_id: $('select[name=unit_id]').val(),
				 	tax_id: $('select[name=tax_id]').val(),
				 	
				 	category_type_id: $('input[name=type]:checked').val(),
				 	grouping: $('input[name=grouping]:checked').val(),
					item_id: $('select[name=item_id]').map(function() { 
						return this.value; 
					}).get(),
					quantity: $('input[name=quantity]').map(function() { 
						return this.value; 
					}).get(),
					price: $('input[name=price]:not(:disabled)').map(function() { 
						return this.value; 
					}).get(),
					item_tax_id: $('select[name=item_tax_id]').map(function() { 
						return this.value; 
					}).get(),
			 	
					},
				 dataType: "json",
					success:function(data, textStatus, jqXHR) {

						image_upload.on("sending", function(file, xhr, response) {
								response.append("id", data.data.id);
							});

						image_upload.processQueue();

						var active_selected = "";
						var inactive_selected = "";
						var selected_text = "In-Active";
						var selected_class = "badge-warning";

						var category_name = ($('input[name=category_id]').val() == "") ? '' : $('input[name=category_id]').val();

						//var in_stock = ($('input[name=initial_quantity]').val() != "") ? $('input[name=initial_quantity]').val()+ ' ' +data.data.unit : '';



						if(data.data.status == 1) {
							active_selected = "selected";
							selected_text = "Active";
							selected_class = "badge-success";
						} else if(data.data.status == 0) {
							inactive_selected = "selected";
						}

					
						var adjust_text = "";
						var adjust_class = "";

						/*if(in_stock != "") {
							adjust_text = "Adjust Quantity";
							adjust_class = "badge-info";
						}*/
					
						var result = "";

						if((data.data.groups).length > 0) {

							result += "<ul class='inner_table'>";
            
							var groups = data.data.groups;
							for(i in groups) {
								result += "<li><span>"+groups[i].name+"</span><span>"+groups[i].quantity+"</span></li>";
							}
							result += "</ul>";
						}


						call_back(`<tr>
							<td>
								<input id="`+data.data.id+`" class="item_check" name="discount" value="`+data.data.id+`" type="checkbox">
								<label for="`+data.data.id+`"><span></span></label>
							</td>
							<td>`+data.data.name+result+`</td>
								      	
				            <td>`+data.data.sale_price+`</td>
							
							<td>
								<label class="grid_label badge `+selected_class+` status">`+selected_text+`</label>
								<select style="display:none" id="`+data.data.id+`" class="active_status form-control">
									<option `+active_selected+` value="1">Active</option>
									<option `+inactive_selected+` value="0">In-Active</option>
								</select>
							</td>
							<td>
							<a data-id="`+data.data.id+`" class="action-btn grid_label edit-icon edit"><i class="fa li_pen"></i></a>&nbsp;
							<a data-id="`+data.data.id+`" class="action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a>
							&nbsp;
							</td></tr>`, `edit`, data.message, data.data.id);		
						$('.loader_wall_onspot').hide();
					},
				 error:function(jqXHR, textStatus, errorThrown) {
					//alert("New Request Failed " +textStatus);
					}
				});
            }
        });
	</script> 

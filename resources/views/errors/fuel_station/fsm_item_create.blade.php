{{--
@extends('layouts.master')
@section('content')

--}}


<div class="modal-header">
  <h4 class="modal-title float-right">Add Item</h4>
</div>
{!! Form::open([
	'class' => 'form-horizontal validateform'
]) !!}
										
{{ csrf_field() }}

				<div class="modal-body">
					<div class="form-body">

						<div class="row">
				  			<div class="form-group col-md-8">
								<div class="row">
								  	<div class="form-group col-md-12"> 
									  	{{ Form::label('inventory_type', 'Item Type', array('class' => 'control-label col-md-12 required')) }}
										<div class="col-md-12">
										  <div class="row">
										   @foreach($inventory_types as $type)
											<div class="col-md-4">
										  		<input type="radio" name="type" id="{{$type->name}}" value="{{$type->id}}" <?php ($type->id=="1") ? 'selected=selected' : ''; ?> />
										  		<label for="{{$type->name}}"><span></span>{{$type->display_name}}</label>
											</div>
											@endforeach
											</div>
										</div>
									</div>

									<div class="form-group col-md-12"> 
										{{ Form::label('item_name', 'Item', array('class' => 'control-label col-md-5 required', 'autocomplete' => 'off')) }}

										<div class="col-md-12">
										{!! Form::text('item_name', null, ['class'=>'form-control item_modal', 'placeholder'=>'Item','id'=>'item_name', 'autocomplete' => 'off','readonly']) !!}
										{!! Form::hidden('item_id', null) !!}
									 	</div>				
									</div>
									
								</div>
				 			</div>

							
						</div>

						<div class="row">
							<div class="form-group col-md-4"> 
								{{ Form::label('global_main_category', 'Main Category', array('class' => 'control-label col-md-12 required')) }}
								<div class="col-md-12">
						  		{!! Form::text('global_main_category',null, ['class' => ' form-control', 'id' => 'global_main_category','placeholder'=>'Main Category','readonly']) !!}
						  		</div>				  		
							</div>
													
							<div class="form-group col-md-4"> 
								{{ Form::label('global_category', 'Category', array('class' => 'control-label col-md-12 required')) }}
									
								{!! Form::text('global_category', null, ['class' => 'form-control', 'id' => 'global_category','placeholder'=>'Category','readonly']) !!}
						  	
							</div>
						</div>

						<div class="row">
							<div  class="form-group col-md-4"> 
								{{ Form::label('global_type', 'Type', array('class' => 'control-label col-md-12')) }}
								<div class="col-md-12">
								{!! Form::text('global_type',null, ['class' => 'form-control', 'id' => 'global_type','placeholder'=>'Type','readonly']) !!} 
						  		</div>
						  	</div>						
													
							<div  class="form-group col-md-4"> 
								{{ Form::label('global_make', 'Make', array('class' => 'control-label col-md-12')) }}
								
								{!! Form::text('global_make', null, ['class' => 'form-control', 'id' => 'global_make','placeholder'=>'Make','readonly']) !!}
							</div>

							<div  class="form-group col-md-4"> 
							{{ Form::label('identifier_a', 'Identifier 1', array('class' => 'control-label ')) }}				  			
							
							{!! Form::text('identifier_a', null, ['class' => 'form-control', 'id' => 'identifier_a','readonly']) !!}
						</div>
						</div>

						<div class="row">
							<div class="form-group col-md-4"> 
								{{ Form::label('hsn', 'HSN', array('class' => 'control-label col-md-5  required')) }}
								<div class="col-md-12"> 
								{!! Form::text('hsn', null, ['class'=>'form-control gst_no', 'placeholder'=>'HSN', 'id'=>'hsn', 'readonly']) !!} 
								</div>
						  </div>
							<div class="form-group col-md-4 unit"> 
								{{ Form::label('unit_id', 'Unit', array('class' => 'control-label  required')) }}
							
								{!! Form::select('unit_id', $units, null, ['class' => 'select_item form-control', 'id' => 'unit_id']) !!} 
							</div>

							<div class="form-group col-md-4"> 
								{{ Form::label('mpn', 'MPN', array('class' => 'control-label ')) }}
							
								{!! Form::text('mpn', null, ['class' => 'form-control', 'id' => 'mpn']) !!} 
							</div>	  
						</div>

						<div class="row">
								<div class="form-group col-md-12"> 
									{{ Form::label('description', 'Description', array('class' => 'control-label col-md-5')) }}
								<div class="col-md-12"> {!! Form::textarea('description', null, ['class'=>'form-control', 'placeholder'=>'Description','id'=>'description', 'size' => '3x4']) !!}</div>
							</div>
						</div>
							
						<div class="row" style="display: none;">
								<div class="form-group col-md-4">
								<div class="col-md-12"> 
									{{ Form::checkbox('include_tax', '1', null, array('id' => 'include_tax')) }}
  								<label for="include_tax"><span></span>Include Sale Tax</label>
								</div>
							</div>
						</div>

						<div class="row">
		  					<div class="form-group col-md-4"> 
		  						{{ Form::label('tax_id', 'Sales Tax', array('class' => 'control-label col-md-12 required')) }}
								<div class="col-md-12">
				  					<select name='tax_id' class='form-control select_item' id = 'tax_id'>
										<option value="">Select Tax</option>
													
							 			@foreach($taxes as $tax) 
							 
											<option value="{{$tax->id}}" data-value="{{$tax->value}}" data-type="{{$tax->tax_type}}">{{$tax->display_name}}</option>
													
							 			@endforeach					
									</select>
								</div>
							</div>

							<div class="form-group col-md-4"> 
							  	{{ Form::label('list_price', 'Unit Price', array('class' => 'control-label col-md-12 required')) }}
								<div class="col-md-12"> 
								{!! Form::text('list_price', null, ['class'=>'form-control', 'placeholder'=>'List Price','id'=>'list_price']) !!} </div>
							</div>

							<div style="display: none;" class="form-group col-md-3"> 
							  	{{ Form::label('discount', 'Discount%', array('class' => 'control-label col-md-12 required')) }}
								<div class="col-md-12"> 
								{!! Form::text('discount', 0, ['class'=>'form-control numbers', 'placeholder'=>'Discount','id'=>'discount']) !!} </div>
							</div>

							<div class="form-group col-md-4"> 
								{{ Form::label('sale_price', 'Unit Price + Tax', array('class' => 'control-label col-md-12 required')) }}
								<div class="col-md-12"> {!! Form::text('sale_price', null, ['class'=>'form-control', 'placeholder'=>'Sale Price','id'=>'sale_price']) !!} </div>
							</div>
						</div>

						<div class="row">	
							<div class="form-group col-md-4"> 
								{{ Form::label('profit', 'Profit by Unit Price', array('class' => 'control-label col-md-12 required')) }}
								<div class="col-md-12"> {!! Form::text('profit', null, ['class'=>'form-control', 'placeholder'=>'profit','id'=>'profit','readonly']) !!} </div>
							</div>
							<div class="form-group col-md-4"> 
								{!! Form::label('on_date', 'Pricing Date', array('class' => 'control-label col-md-12 required')) !!}
							<div class="col-md-12"> 
								{!! Form::text('on_date',date('d-m-Y'),['class' => 'form-control date-picker', 'data-date-format' => 'dd-mm-yyyy', 'id'=>'on_date']) !!} </div>
						  	</div>
								<div class="form-group col-md-4"> 
									{{ Form::label('income_account', 'Sale Account', array('class' => 'control-label col-md-12 required')) }}
							<div class="col-md-12"> 
								{!! Form::select('income_account',  $sale_account , null, ['class' => 'select_item form-control', 'id' => 'income_account']) !!} </div>
						  	</div>
						</div>

		<hr style="height:1px;border:none;color:#333;background-color:#333;">
						<div class="row">
	  						<div class="form-group col-md-4 main_inventory">
								<?php 
									if(App\Organization::checkModuleExists('inventory', Session::get('organization_id'))) {
										$selection = true;
									} else {
										$selection = false;
									} 
									?>
								<div class="col-md-12"> 
									{{ Form::checkbox('purchase', '1', $selection, array('id' => 'purchase')) }}
		  							<label for="purchase"><span></span>Maintain Inventory</label>
								</div>
							</div>
						</div>


						<div class="form-group purchase">
	  						<div class="row">
								<div class="form-group col-md-3"> 
									{{ Form::label('initial_quantity', 'Initial Quantity', array('class' => 'control-label col-md-12 required')) }}
		  							<div class="col-md-12">
		  							{!! Form::text('initial_quantity', null, ['class'=>'form-control numbers', 'placeholder'=>'Quantity','id'=>'low_stock']) !!} </div>
								</div>
								<div class="form-group col-md-3"> 
									{{ Form::label('low_stock', 'Low Stock Alert', array('class' => 'control-label col-md-12')) }}
		  
		  							<div class="col-md-12">
		  								{!! Form::text('low_stock', null, ['class'=>'form-control numbers', 'placeholder'=>'Low Stock Alert','id'=>'low_stock']) !!} </div>
								</div>
								<div class="form-group col-md-3"> 
									{{ Form::label('sku', 'SKU', array('class' => 'control-label col-md-5')) }}
		  							<div class="col-md-12">{!! Form::text('sku', null, ['class'=>'form-control', 'placeholder'=>'SKU','id'=>'sku']) !!} </div>
								</div>

								<div class="form-group col-md-3 unit"> 
									{{ Form::label('minimum_order_quantity', 'MOQ', array('class' => 'control-label')) }}
										
									{!! Form::text('minimum_order_quantity', null, ['class'=>'form-control numbers', 'placeholder'=>'Minimum Order Quantity','id'=>'minimum_order_quantity']) !!} </div>
							</div>
							<hr style="height:1px;border:none;color:#333;background-color:#333;">
						<!-- 
	  						<div class="row">
								<div class="form-group col-md-4">
		  							<div class="col-md-12"> 
		  								{{ Form::checkbox('include_purchase_tax', '1', '', array('id' => 'include_purchase_tax')) }}
										<label for="include_purchase_tax"><span></span>Include Purchase Tax</label>
									</div>		
								</div>
							</div>
 																-->

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
									{{ Form::label('purchase_price', 'Purchase Price(No Tax)', array('class' => 'control-label col-md-12 required')) }}
	  								<div class="col-md-12"> {!! Form::text('purchase_price', null, ['class'=>'form-control', 'placeholder'=>'Purchase Price','id'=>'purchase_price']) !!} </div>
								</div>
								<div class="form-group col-md-3"> 
									{{ Form::label('purchase_price_withtax', 'Purchase Price + Tax', array('class' => 'control-label col-md-12 required')) }}
	  								<div class="col-md-12"> {!! Form::text('purchase_price_withtax', null, ['class'=>'form-control numbers', 'placeholder'=>'Purchase Price','id'=>'purchase_price_withtax','readonly']) !!} </div>
								</div>
								<div class="form-group col-md-3"> 
									{{ Form::label('expense_account', 'Purchase Account', array('class' => 'control-label col-md-12 required')) }}
	  								<div class="col-md-12"> {!! Form::select('expense_account',  $purchase_account , null, ['class' => 'select_item form-control', 'id' => 'expense_account']) !!} </div>
								</div>

								<div class="form-group col-md-3"> 
									{{ Form::label('inventory_account', 'Inventory Account', array('class' => 'control-label col-md-12 required')) }}
	  								<div class="col-md-12"> {{ Form::select('inventory_account', $inventory_account, null, ['class' => 'form-control select_item', 'id' => 'inventory_account']) }} </div>
								</div>
							</div>
						</div>

					</div>
				</div>

			<div class="modal-footer">

  				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-success">Submit</button>

			</div>


{!! Form::close() !!}		
		







{{--

	@stop

@section('dom_links')
@parent 				
 

--}}








<script type="text/javascript">



/*var global_image_upload = new Dropzone("div#global-upload", {
	  paramName: 'file',
	  url: "{{route('item_image_upload')}}",
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
	  },
	  success: function(file, response){
		image_call_back(response.data.path, response.data.id);
	  }
	});*/

$(document).ready(function() {

	basic_functions();


	/*$('input[name=list_price], input[name=discount]').on('change input', function() {


		var obj = $(this);
		var list_price = $('input[name=list_price]').val();
		var discount = $('input[name=discount]').val();
		var sale_price = $('input[name=sale_price]');
		

		//alert(in_stock);
		
		var tax_id = parent.find('select[name=tax_id]').find('option:selected').data('value');
		var discount_id = parent.find('input[name=discount_value]').val();
		var tax_value = isNaN(tax_id) ? 0 : tax_id/100;

		var discount_value = isNaN(discount_id) ? 0 : discount_id/100;

		var amount = (rate*quantity).toFixed(2);
		var tax_amount = (amount*tax_value).toFixed(2);
		var discount_amount = (amount*discount_value).toFixed(2);
		
		sale_price.val(amount);
 
		//table();
		
	});*/


	@if (!App\Organization::checkModuleExists('inventory', Session::get('organization_id')))
		$(".purchase").hide();
	@else
		$('.main_inventory').closest('.row').show();
	@endif

	$('input[name=purchase]').on('change', function() {
	
			if($(this).is(":checked")) {
				$(".purchase").show();
			} else {
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

	
$('select[name=tax_id], input[name=list_price], input[name=discount]').on('change input', function() {

		var list_price = $('input[name=list_price]').val();
		var discount = $('input[name=discount]').val();
		var sale_price = $('input[name=sale_price]');
		var tax_id = $('select[name=tax_id]').val();

		if(discount != null && discount != 0) {
		 	var discount_amount = list_price * (discount / 100);
		} else {
		 	var discount_amount = 0;
		}
		

		var tax_value1 = $('select[name=tax_id]').find('option:selected').data('value');

		/*  tax exclude */

		//var	tax_amount1 = parseFloat(isNaN(tax_value1) ? 0 : (tax_value1/100) + 1 );

		/* tax Include */

		var	tax_amount1 = parseFloat(isNaN(tax_value1) ? 0 : tax_value1/100 ) * parseFloat(list_price);	

		
			if(tax_amount1 != 0){
		
				if(list_price.length > 0) {

					if(discount.length > 0 && discount != 0 ) {

						//sale_price.val( $.trim(list_price) - ($.trim(list_price) * (discount/100)));

						var dicount_sale =  (parseFloat(list_price - discount_amount ) / (tax_amount1)) ;

						//var dicount_sale = (parseFloat(list_price) - ((list_price) * (discount/100)) / (tax_amount1)) ;

						sale_price.val(dicount_sale.toFixed(2));

					} else {

						/* tax exclude */

						//var sale = (parseFloat(list_price) / parseFloat(tax_amount1));

						/* tax Include */

						var sale = (parseFloat(list_price) + parseFloat(tax_amount1));
						
						sale_price.val(sale.toFixed(2));

						//sale_price.val($.trim(parseFloat(list_price)));
						
					}
				} 
				else {
					sale_price.val("");
				}
			}					

			else{				

				if(discount.length > 0 && discount != 0) 
				{
					var dicount_sale = (parseFloat(list_price - discount_amount));
					sale_price.val(dicount_sale.toFixed(2));
				}
				else{
					sale_price.val(list_price);

				}
				
			}		
		 		

			
	});

$('select[name=tax_id], input[name=sale_price]').on('change input', function() {
      var list_price = $('input[name=list_price]');
      var sale_price = $('input[name=sale_price]').val();
      var tax_id = $('select[name=tax_id]').val();
      var tax_value2 = $('select[name=tax_id]').find('option:selected').data('value');
      if(tax_value2 == undefined){
      	tax_value2 = 0.00;
      }else{
      	tax_value2 = $('select[name=tax_id]').find('option:selected').data('value');
      }
	  var tax_amount2 = 	sale_price / (1 + tax_value2/100);	

         list_price.val(tax_amount2.toFixed(2));
	});

	/*$('input[name=low_stock]').on('keyup change', function(){

		if($(this).val() > 10) {
			alert($(this).val());
		}
	});*/

$('select[name=purchase_tax_id], input[name=purchase_price]').on('change input', function() {

		var purchase_price = $('input[name=purchase_price]').val();	
		var purchase_price_withtax = $('input[name=purchase_price_withtax]');
		var purchase_tax_id = $('select[name=purchase_tax_id]').val();
        var purchase_tax_value = $('select[name=purchase_tax_id]').find('option:selected').data('value'); 
        if(purchase_tax_value == undefined){
        	purchase_tax_value = 0.00;
        }else{
        	 purchase_tax_value = $('select[name=purchase_tax_id]').find('option:selected').data('value'); 
        }

        var	purchase_amount_withtax = parseFloat(isNaN(purchase_tax_value) ? 0 : purchase_tax_value/100 ) * parseFloat(purchase_price);
       var purchase_tax_amount = (parseFloat(purchase_price) + parseFloat(purchase_amount_withtax));
       
       if(isNaN(purchase_tax_amount)){
               purchase_price_withtax.val("");
             }else{
	             purchase_price_withtax.val(parseFloat(purchase_tax_amount).toFixed(2));
            }     	
	    });

$('input[name=list_price], input[name=sale_price], input[name=purchase_price]').on('change input', function() {
    var selling_price = $('input[name=list_price]').val();
    var purchase_price = $('input[name=purchase_price]').val();
    var profit = parseFloat(selling_price) - parseFloat(purchase_price);
if(isNaN(profit)){
    $('input[name=profit]').val("");
}else{
   $('input[name=profit]').val(parseFloat(profit).toFixed(2));
}
});

		@include('modals.add_gst_fsm')
		

		$('input[name=item_name]').on('click', function() {

			$('input[name=global_model]').focus();
		});

		$('input[name=type]').on('change', function(){
	  
			var obj = $(this);
			//alert(obj);
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

	  	});
		
	});


	



	/*$(".validateform").validate({
   		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
        rules: {
        	global_model: "required",
            global_main_category: "required",
            global_category: "required"
        },
        messages: {
        	global_model: "Item is required.",
            global_main_category: "Main category is required.",
            global_category: "Category is required."

        },
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
		error:function(jqXHR, textStatus, errorThrown) {
			//alert("New Request Failed " +textStatus);
		}
    })

    $('#save_item').click(function() {
        $(".validateform").valid();
    });*/




		$('.validateform').validate({
			errorElement: 'span', //default input error message container
			errorClass: 'help-block', // default input error message class
			focusInvalid: false, // do not focus the last invalid input
			rules: {
				name: {
					required: true
				},	
				
				hsn: {
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
				
				hsn: {
					required: "HSN is required."
				},
				unit_id: {
					required: "Unit is required."
				},
				initial_quantity: {
					required: "Initial Quantity is required."
				},
				list_price: {
					required: "List Price Quantity is required."
				},
				discount: {
					required: "Discount Quantity is required."
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
				
				if($('input[name=item_id]').val() != "") {
					$.ajax({
					 url: '{{ route('item.store') }}',
					 type: 'post',
					 data: {
						_token: '{{ csrf_token() }}',
					
						name: $('input[name=item_name]').val(),
						sku: $('input[name=sku]').val(),
						hsn: $('input[name=hsn]').val(),
						mpn: $('input[name=mpn]').val(),
						item_id: $('input[name=item_id]').val(),
						minimum_order_quantity: $('input[name=minimum_order_quantity]').val(),
						purchase_price: $('input[name=purchase_price]').val(),
						list_price: $('input[name=list_price]').val(),
						discount: $('input[name=discount]').val(),
						sale_price: $('input[name=sale_price]').val(),
						on_date: $('input[name=on_date]').val(),
						description: $('textarea[name=description]').val(),
						low_stock: $('input[name=low_stock]').val(),
						initial_quantity: $('input[name=initial_quantity]').val(),
						include_tax: $('input[name=include_tax]:checked').val(),
						include_purchase_tax: $('input[name=include_purchase_tax]:checked').val(),
						income_account: $('select[name=income_account]').val(),
						expense_account: $('select[name=expense_account]').val(),
						inventory_account: $('select[name=inventory_account]').val(),
						//category_id: $('select[name=category_id]').val(),
						sale_price: $('input[name=sale_price]').val(),
						unit_id: $('select[name=unit_id]').val(),
						tax_id: $('select[name=tax_id]').val(),
						purchase_tax_id: $('select[name=purchase_tax_id]').val(),
						purchase: $('input[name=purchase]:checked').val(),	
						},
						
						dataType: "json",
					success:function(data, textStatus, jqXHR) {
                                
							

							var category_name = ($('select[name=category_id] option:selected').val() == "") ? '' : $('select[name=category_id] option:selected').text();

							var in_stock = ($('input[name=initial_quantity]').val() != "") ? $('input[name=initial_quantity]').val()+ ' ' +data.data.unit : '';

							var adjust_text = "";
							var adjust_class = "";

							if(in_stock != "") {
								adjust_text = "Adjust Quantity";
								adjust_class = "badge-info";
							}

							call_back(`<tr>
									<td>
										<input id="`+data.data.id+`" class="item_check" name="discount" value="`+data.data.id+`" type="checkbox">
										<label for="`+data.data.id+`"><span></span></label>
									</td>
									<td>`+data.data.name+`</td>	
									<td>`+data.data.category_name+`</td>
									<td>`+data.data.in_stock+`</td>	
									<td>`+data.data.purchase_price+`</td> 
									<td>`+data.data.selling_price+`</td> 		
									<td>`+data.data.sale_price+`</td>
				   
									<td>
											
										<label class="grid_label badge badge-success status">Active</label>
										<select style="display:none" id="`+data.data.id+`" class="active_status form-control">
										<option value="1">Active</option>
										<option value="0">In-active</option>
										</select>
									</td>
									<td>
									<a data-id="`+data.data.id+`" class="action-btn grid_label edit-icon edit"><i class="fa li_pen"></i></a>&nbsp;

									<a data-id="`+data.data.id+`" class="action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a>&nbsp;

									<a href="{{url('item')}}/`+data.data.id+`" data-id="`+data.data.id+`" class="grid_label action-btn show-icon show"><i class="fa fa-eye"></i></a>
									
									<a href="javascript:;" data-id="`+data.data.id+`" data-stock="`+in_stock+`" class="grid_label badge `+adjust_class+` create">`+adjust_text+`</a>
									</td>
								</tr>`, `add`, data.message);	

							$('.loader_wall_onspot').hide();
					},
					error:function(jqXHR, textStatus, errorThrown) {
						//alert("New Request Failed " +textStatus);
					}
				});
				}
			}
		});
	</script> 

{{--
@stop
--}}
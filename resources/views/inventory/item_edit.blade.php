<style>
    .form-group
    {
      margin:0px;
     
		
    }
    label
    {
    	margin-bottom: 0px;
    }
     .select2-container--default .select2-selection--single #select2-tax_id-container
    {
    	background-color: yellow;
    }
    .select2-container--default .select2-selection--single #select2-purchase_tax_id-container
    {
    	background-color: yellow;
    }
</style>
<div class="modal-header" style="background-color: #e9ecef;">
	<h5 class="modal-title float-right"><b>Edit Items</b></h5>
	<a  class="close" data-dismiss="modal">&times;</a>

</div>


	{!!Form::model($inventory_items, ['class' => 'form-horizontal validateform'])!!}
										
	{{ csrf_field() }}

	<div class="modal-body" style="max-height:600px;overflow-y: scroll;">
		<div class="form-body">
				{!! Form::hidden('id', null) !!}

					<div class="row">
						<div class="form-group col-md-8"> 
							<div class="row">
								<div class="form-group col-md-12">
									{{ Form::label('inventory_type', 'Inventory Type', array('class' => 'control-label col-md-12 required')) }}
							
								
									<div class="row">
									@foreach($inventory_types as $type) 
										<div class="col-md-4">
											<input type="radio" name="type" id="{{$type->name}}" value="{{$type->id}}"/>
											<label for="{{$type->name}}"><span></span>{{$type->display_name}}</label>
										</div>
									@endforeach
									</div>
									
								</div>

								<div class="form-group col-md-8">
									{{ Form::label('Item', 'Item', array('class' => 'control-label col-md-5 required')) }}
									{!! Form::text('name', null, ['class'=>'form-control', 'placeholder'=>'Item','id'=>'name','style' => 'background-color:yellow;']) !!}
									
								</div>
								<div  class="form-group col-md-4"> 
									{{ Form::label('global_make', 'Make', array('class' => 'control-label col-md-12')) }}
									
									{!! Form::text('global_make', $inventory_items->make_name, ['class' => 'form-control', 'id' => 'global_make','placeholder'=>'Make','readonly']) !!}
								</div>

									

							</div>
							<!-- <div class="row">
								<div class="form-group col-md-6"> 
									{{ Form::label('global_main_category', 'Main Category', array('class' => 'control-label col-md-12 required')) }}
									
							  		{!! Form::text('global_main_category',$inventory_items->main_category_name, ['class' => ' form-control', 'id'=>'global_main_category','placeholder'=>'Main Category','readonly']) !!}
							  					  		
								</div>
													
								<div class="form-group col-md-6"> 
									{{ Form::label('global_category', 'Category', array('class' => 'control-label col-md-12 required')) }}
										
									{!! Form::text('global_category',$inventory_items->category_name, ['class' => 'form-control', 'id' => 'global_category','placeholder'=>'Category','readonly']) !!}
								</div>
							</div> -->
						</div>

						<div class="form-group col-md-2">
							<div class="row">
								
							
							<div class="col-md-6">
								<div style="position: relative; height: 115px; width: 130px;" class="dropzone" id="image-upload"> </div>
							</div>
							</div>
						</div>
					</div>
	
					<!-- <div class="row">
						<div class="form-group col-md-4"> 
							{{ Form::label('global_main_category', 'Main Category', array('class' => 'control-label col-md-12 required')) }}
							<div class="col-md-12">
					  		{!! Form::text('global_main_category',$inventory_items->main_category_name, ['class' => ' form-control', 'id'=>'global_main_category','placeholder'=>'Main Category','readonly']) !!}
					  		</div>				  		
						</div>
												
						<div class="form-group col-md-4"> 
							{{ Form::label('global_category', 'Category', array('class' => 'control-label col-md-12 required')) }}
								
							{!! Form::text('global_category',$inventory_items->category_name, ['class' => 'form-control', 'id' => 'global_category','placeholder'=>'Category','readonly']) !!}
						</div>
					</div> -->

					<div class="row">
						<div class="form-group col-md-4"> 
							{{ Form::label('global_main_category', 'Main Category', array('class' => 'control-label col-md-12 required')) }}
							
					  		{!! Form::text('global_main_category',$inventory_items->main_category_name, ['class' => ' form-control', 'id'=>'global_main_category','placeholder'=>'Main Category','readonly']) !!}
					  					  		
						</div>
											
						<div class="form-group col-md-4"> 
							{{ Form::label('global_category', 'Category', array('class' => 'control-label col-md-12 required')) }}
								
							{!! Form::text('global_category',$inventory_items->category_name, ['class' => 'form-control', 'id' => 'global_category','placeholder'=>'Category','readonly']) !!}
						</div>
						<div  class="form-group col-md-4"> 
							{{ Form::label('global_type', 'Type', array('class' => 'control-label col-md-12')) }}
							
							{!! Form::text('global_type', $inventory_items->type, ['class' => 'form-control', 'id' => 'global_type','placeholder'=>'Type','readonly']) !!} 
					  		
					  	</div>						
												
						<!-- <div  class="form-group col-md-4"> 
							{{ Form::label('global_make', 'Make', array('class' => 'control-label col-md-12')) }}
							
							{!! Form::text('global_make', $inventory_items->make_name, ['class' => 'form-control', 'id' => 'global_make','placeholder'=>'Make','readonly']) !!}
						</div> -->
						<!-- <div  class="form-group col-md-4"> 
							{{ Form::label('identifier_a', 'Identifier 1', array('class' => 'control-label ')) }}				  			
							
							{!! Form::text('identifier_a',$inventory_items->identifier_a, ['class' => 'form-control', 'id' => 'identifier_a','readonly']) !!}
						</div> -->
					</div>

					<div class="row">
							<div class="form-group col-md-3 show_hsn_text">
								{{ Form::label('hsn', 'HSN', array('class' => 'control-label required')) }}
								
								{!! Form::text('hsn', null, ['class'=>'form-control gst_no', 'placeholder'=>'HSN', 'id'=>'hsn','disabled','style' => 'background-color:yellow;']) !!} 
								
							</div>
							 <div class="col-md-1 show_hsn_text" style="margin-top: 20px;">
					  			<a class="grid_label action-btn edit-icon show_hsn"><i class="fa li_pen" data-toggle="tooltip" data-placement="top" title="Click on the field to Edit"></i></a>
					  	    </div>

						  	<div class="form-group col-md-3 show_hsn_select" style=""> 
								{{ Form::label('hsn_name', 'HSN', array('class' => 'control-label required')) }}
							
								{{ Form::text('hsn_name', $inventory_items->hsn,['class'=>'form-control hsn_name','placeholder' => 'Type HSN Code','id' => 'hsn_name','style' => 'background-color:yellow;']) }}
								
						  	</div> 
						  	<div class="col-md-1 show_hsn_select" style="margin-top: 20px;">
						  		<a class="grid_label action-btn edit-icon hide_hsn"><i class="fa fa-search" data-toggle="tooltip" data-placement="top" title="Click on the field to Search"></i></a>
						  	</div>
							<div  class="form-group col-md-4"> 
								{{ Form::label('identifier_a', 'Identifier 1', array('class' => 'control-label ')) }}				  			
								
								{!! Form::text('identifier_a',$inventory_items->identifier_a, ['class' => 'form-control', 'id' => 'identifier_a','readonly']) !!}
							</div>

							<div class="form-group col-md-4 unit" style=" @if($inventory_items->type_name == 'service') display:none @endif">
								{{ Form::label('unit_id', 'Unit', array('class' => 'control-label  required')) }}
								
								{!! Form::select('unit_id', $units, null, ['class' => 'select_item form-control', 'id' => 'unit_id']) !!}
							</div>

							<!-- <div class="form-group col-md-4"> 
								{{ Form::label('mpn', 'MPN', array('class' => 'control-label ')) }}
													
							{!! Form::text('mpn', null, ['class' => 'form-control', 'id' => 'mpn']) !!} 
														</div> -->
					</div>

					<div class="row">
						<div class="form-group col-md-4"> 
							{{ Form::label('mpn', 'MPN', array('class' => 'control-label ')) }}
													
							{!! Form::text('mpn', null, ['class' => 'form-control', 'id' => 'mpn']) !!} 
						</div>
						<div class="form-group col-md-8">	
							{{ Form::label('description', 'Description', array('class' => 'control-label col-md-5')) }}
							
							{!! Form::text('description', null, ['class'=>'form-control', 'placeholder'=>'Description','id'=>'description']) !!}
							
						</div>
					</div>
		<hr style="height:1px;border:none;color:#333;background-color:#333;">


					<div class="row" style="display: none;">	

						<div class="form-group col-md-4">	
							<div class="col-md-12">
							{{ Form::checkbox('include_tax', '1', null, array('id' => 'include_tax','checked')) }} 
							<label for="include_tax"><span></span>Include Sale Tax</label>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-md-4">
							{{ Form::label('tax_id', 'Sales Tax', array('class' => 'control-label col-md-12 required')) }}	
							
								<select name='sales_tax_id' class='form-control select_item' id = 'tax_id'>
								 <option value="">Select Tax</option>
								 @foreach($taxes as $tax) 
								 <option value="{{$tax->id}}" @if($inventory_items->tax_id == $tax->id) selected="" @endif  data-value="{{$tax->value}}" data-type="{{$tax->tax_type}}">{{$tax->display_name}}</option>
								 @endforeach
								</select> 
							
						</div>
					
						<div class="form-group col-md-4"> 
							{{ Form::label('list_price', 'Unit Price', array('class' => 'control-label col-md-12 required')) }}
							 
							{!! Form::text('list_price', $list_price, ['class'=>'form-control', 'placeholder'=>'List Price','id'=>'list_price']) !!} 
						  </div>

						  <div style="display: none;" class="form-group col-md-3"> 
						  	{{ Form::label('discount', 'Discount%', array('class' => 'control-label col-md-12 required')) }}
							
							{!! Form::text('discount', $discount, ['class'=>'form-control numbers', 'placeholder'=>'Discount','id'=>'discount']) !!}
						  </div>

						<div class="form-group col-md-4">	
								{{ Form::label('sale_price', 'Unit Price + Tax', array('class' => 'control-label col-md-12 required')) }}
								
								{!! Form::text('sale_price', $price, ['class'=>'form-control', 'placeholder'=>'Sale Price','id'=>'sale_price']) !!}
								
						</div>
					</div>

					<div class="row">
						<div class="form-group col-md-4"> 
								{{ Form::label('profit', 'Profit by Unit Price', array('class' => 'control-label col-md-12 required')) }}
								{!! Form::text('profit', null, ['class'=>'form-control', 'placeholder'=>'profit','id'=>'profit','readonly']) !!}
						</div>
						<div class="form-group col-md-4">
								{!! Form::label('on_date', 'Pricing Date', array('class' => 'control-label col-md-12 required')) !!}
								
								{!! Form::text('on_date',$date,['class' => 'form-control date-picker', 'data-date-format' => 'dd-mm-yyyy', 'id'=>'on_date']) !!}
								
						</div>		


						<div class="form-group col-md-4">
							{{ Form::label('income_account', 'Sale Account', array('class' => 'control-label col-md-12 required')) }}

							
							{!! Form::select('income_account',  $sale_account , null, ['class' => 'select_item form-control', 'id' => 'income_account']) !!}
							
						</div>

					</div>

					<div class="row">	
						<div class="form-group col-md-4"> 
							{{ Form::label('duration', 'Duration', array('class' => 'control-label col-md-12')) }}
							 
							{!! Form::text('duration', $inventory_items->duration, ['class'=>'form-control']) !!}
						</div>
						<div class="form-group col-md-4"> 
							{{ Form::label('mrp', 'MRP', array('class' => 'control-label col-md-12 ')) }}
							{!! Form::text('mrp', $inventory_items->mrp, ['class'=>'form-control']) !!} 
						</div>
						<div class="form-group col-md-4">

						<div class="col-md-12">
								<input type="checkbox" name="marketing" id="marketing" @if($inventory_items->marketing_price) checked @endif>
								<label for="marketing"><span></span>Marketing</label></input>
								<div class="form-group" id ="show_marketing" style="@if($inventory_items->marketing_price) display:block; @else  display:none; @endif "> 
									{!! Form::text('marketing_price', null, ['class'=>'form-control']) !!}
								</div>
							</div>

						</div>

					<!--	@if($inventory_items->marketing_price)
							<div class="form-group col-md-4" id ="show_marketing"> 
								{{ Form::label('marketing_price', 'Marketing Price', array('class' => 'control-label col-md-12 ')) }}
								<div class="col-md-12">
									{!! Form::text('marketing_price', $inventory_items->marketing_price, ['class'=>'form-control']) !!} 
								</div>
							</div>
						@endif-->
						<!-- <div class="form-group col-md-1" id="service_itc" style="@if($inventory_items->type_name == 'goods') display:none;@endif;margin-top: 30px;margin-left:15px"> 
								
								<input type="checkbox" name="itc" id="itc" checked="checked" disabled="disabled">
								<label for="itc"><span></span>ITC</label></input>					
						</div>  -->

						<div class="form-group col-md-4 service_purchase" style="@if($inventory_items->type_name == 'goods') display:none;@endif;" >
								{{ Form::label('expense_account', 'Purchase Account', array('class' => 'control-label required')) }}
						
								
								{!! Form::select('expense_account',  $purchase_account , null, ['class' => 'select_item form-control', 'id' => 'expense_account']) !!}
								
						</div>
						<div class="form-group col-md-4 service_purchase_taxid" style="@if($inventory_items->type_name == 'goods') display:none;@endif;">
								{{ Form::label('purchase_tax_id', 'Purchase Tax', array('class' => 'control-label required')) }}	
								
								
									<select name='purchase_tax_id' class='form-control select_item' id="service_purchase_taxid">
									 <option value="">Select Tax</option>
									 @foreach($purchase_taxes as $tax) 
									 <option value="{{$tax->id}}" @if($inventory_items->purchase_tax_id == $tax->id) selected="" @endif data-value="{{$tax->value}}" data-type="{{$tax->tax_type}}">{{$tax->display_name}}</option>
									 @endforeach
									</select> 
							
								
						 </div> 
				</div>

				<!-- 	<div class="row">
				
					<div class="form-group col-md-4">
				
						<div class="col-md-12">
							<input type="checkbox" name="marketing" id="marketing" @if($inventory_items->marketing_price) checked @endif>
							<label for="marketing"><span></span>Marketing</label></input>
						</div>
				
					</div>
				
					@if($inventory_items->marketing_price)
						<div class="form-group col-md-4" id ="show_marketing"> 
							{{ Form::label('marketing_price', 'Marketing Price', array('class' => 'control-label col-md-12 ')) }}
							<div class="col-md-12">
								{!! Form::text('marketing_price', $inventory_items->marketing_price, ['class'=>'form-control']) !!} 
							</div>
						</div>
					@endif
													
				</div> -->
					<hr style="height:1px;border:none;color:#333;background-color:#333;">
					

						<!-- 	<div class="row">
						<div class="form-group col-md-4">	
							<div class="col-md-12">
							
							{{ Form::checkbox('include_purchase_tax', '1', null, array('id' => 'include_purchase_tax')) }} <label for="include_purchase_tax"><span></span>Include Purchase Tax</label>
							</div>
						</div>
						</div> -->
						@if($inventory_items->type_name == 'goods')
						<div class="row">
							<div class="form-group col-md-3">
								{{ Form::label('purchase_tax_id', 'Purchase Tax', array('class' => 'control-label col-md-12 required')) }}	
								
								
									<select name='purchase_tax_id' class='form-control select_item' id = 'purchase_tax_id'>
									 <option value="">Select Tax</option>
									 @foreach($purchase_taxes as $tax) 
									 <option value="{{$tax->id}}" @if($inventory_items->purchase_tax_id == $tax->id) selected="" @endif data-value="{{$tax->value}}" data-type="{{$tax->tax_type}}">{{$tax->display_name}}</option>
									 @endforeach
									</select> 
							
								
							</div>

							<div class="form-group col-md-3">	
								{{ Form::label('purchase_price', 'Purchase Price(No Tax)', array('class' => 'control-label col-md-12 required')) }}
								{!! Form::text('purchase_price', null, ['class'=>'form-control', 'placeholder'=>'Purchase Price','id'=>'purchase_price']) !!}
								
							</div>

                            <div class="form-group col-md-3"> 
								{{ Form::label('purchase_price_withtax', 'Purchase Price + Tax', array('class' => 'control-label col-md-12 required')) }}
	  							{!! Form::text('purchase_price_withtax', $purchase_price_tax, ['class'=>'form-control numbers', 'placeholder'=>'Purchase Price','id'=>'purchase_price_withtax']) !!} 
							</div>
							
																
							<div class="form-group col-md-3">
								{{ Form::label('expense_account', 'Purchase Account', array('class' => 'control-label col-md-12 required')) }}

								
								{!! Form::select('expense_account',  $purchase_account , null, ['class' => 'select_item form-control', 'id' => 'expense_account']) !!}
								
							</div>

							<div class="form-group col-md-3">
								{{ Form::label('inventory_account', 'Inventory Account', array('class' => 'control-label col-md-12 required')) }}
																		
								  {{ Form::select('inventory_account', $inventory_account, null, ['class' => 'form-control select_item', 'id' => 'inventory_account']) }} 
								
							</div>
						</div>	
						<hr style="height:1px;border:none;color:#333;background-color:#333;">

						@endif




					<div class="row" style=" @if($inventory_items->type_name == 'service') display:none @endif">							
							<div class="form-group col-md-4">								
								<div class="col-md-12">
								<?php $check_status = false;
								if($inventory_item_stocks != null) $check_status = true;					
								?>
								{{ Form::checkbox('purchase', '1', $check_status, array('id' => 'purchase')) }} <label for="purchase"><span></span>Maintain Inventory</label>
								</div>
							</div>
					</div>

			@if($inventory_items->type_name == 'goods')

				<div class="form-group purchase">
					<div class="row">

						<div class="form-group col-md-3">	
							{{ Form::label('initial_quantity', 'Initial Quantity', array('class' => 'control-label col-md-12 required')) }}
							
							{!! Form::text('initial_quantity', ($inventory_item_stocks != null) ? $inventory_item_stocks->in_stock: null, ['class'=>'form-control numbers', 'placeholder'=>'Quantity','id'=>'low_stock','disabled','data-toggle'=>'tooltip', 'data-placement' => 'top' ,'title' => 'Please change the quantity by the feature "Adjust Quantity"']) !!}
							
							
						</div>
						<div class="form-group col-md-3">	
							{{ Form::label('opening_quantity', 'Opening Quantity', array('class' => 'control-label col-md-12 required')) }}
							
							{!! Form::text('opening_quantity', ($inventory_item_stocks != null) ? $inventory_item_stocks->in_stock: null, ['class'=>'form-control numbers', 'placeholder'=>'Quantity','id'=>'low_stock','disabled','data-toggle'=>'tooltip', 'data-placement' => 'top' ,'title' => 'Please change the quantity by the feature "Adjust Quantity"']) !!}
							
							
						</div>

						<div class="form-group col-md-3">	
							{{ Form::label('low_stock', 'Low Stock Alert', array('class' => 'control-label col-md-12')) }}
							
							{!! Form::text('low_stock', null, ['class'=>'form-control numbers', 'placeholder'=>'Low Stock Alert','id'=>'low_stock']) !!}
							
						</div>

						<div class="form-group col-md-2">	
							{{ Form::label('sku', 'SKU', array('class' => 'control-label col-md-5')) }}
							{!! Form::text('sku', null, ['class'=>'form-control', 'placeholder'=>'SKU','id'=>'sku']) !!}
							
						</div>

						<div class="form-group col-md-1 unit" style=" @if($inventory_items->type_name == 'service') display:none @endif">
							{{ Form::label('minimum_order_quantity', 'MOQ', array('class' => 'control-label')) }}
					
							{!! Form::text('minimum_order_quantity', null, ['class'=>'form-control numbers', 'placeholder'=>'Minimum Order Quantity','id'=>'minimum_order_quantity']) !!}
						</div>
						
					</div>

										
				</div>
			@endif	

			</div>
			<br><br>
		</div>					
															
						

		<div class="modal-footer" style="background-color: #e9ecef;">
			<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
			<button type="submit" class="btn btn-success">Submit</button>
		</div>

{!! Form::close() !!}		
						
					


<script type="text/javascript">

	var image_upload = new Dropzone("div#image-upload", {
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
	});



$(document).ready(function() {

	basic_functions();
	
	$('#marketing').on('click',function(){
				//alert();
				if($(this).is(":checked"))
				{
					$('#show_marketing').show();
				}
				else
				{
					$('#show_marketing').hide();

				}
			});


	@if (App\Organization::checkModuleExists('inventory', Session::get('organization_id')))
		$('.main_inventory').closest('.row').hide();
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


 var list_price_length = $('input[name=list_price]').length;
 if(list_price_length  > 0){
 	var sale_place = $('input[name=sale_price]');
 	var list_price_val = $('input[name=list_price]').val();
 	var tax_value = $('select[name=sales_tax_id]').find('option:selected').data('value');
 	var	tax_amount = parseFloat(isNaN(tax_value) ? 0 : tax_value/100 ) * parseFloat(list_price_val);
 	var sale_amount = (parseFloat(list_price_val) + parseFloat(tax_amount));
    sale_place.val(sale_amount.toFixed(2));
 }
	$('select[name=sales_tax_id], input[name=list_price], input[name=discount]').on('change input', function() {

		var list_price = $('input[name=list_price]').val();
		var discount = $('input[name=discount]').val();
		var sale_price = $('input[name=sale_price]');
		var tax_id = $('select[name=sales_tax_id]').val();

		if(discount != null && discount != 0) {
		 	var discount_amount = list_price * (discount / 100);
		} else {
		 	var discount_amount = 0;
		}
		

		var tax_value1 = $('select[name=sales_tax_id]').find('option:selected').data('value');

		/*  tax exclude */

		//var	tax_amount1 = parseFloat(isNaN(tax_value1) ? 0 : (tax_value1/100) + 1 );

		/* tax Include */

		var	tax_amount1 = parseFloat(isNaN(tax_value1) ? 0 : tax_value1/100 ) * parseFloat(list_price);	

		

			if(tax_amount1 != 0){
		
				if(list_price.length > 0) {

					if(discount.length > 0 && discount != 0 ) {

						/* tax exclude */

						//var dicount_sale =  (parseFloat(list_price - discount_amount ) / (tax_amount1)) ;

						/* tax Include */

						var dicount_sale =  (parseFloat(list_price - discount_amount ) + parseFloat(tax_amount1)) ;	

						sale_price.val(dicount_sale.toFixed(2));

					} else {

						/* tax exclude */

						//var sale = (parseFloat(list_price) / parseFloat(tax_amount1));

						/* tax Include */

						var sale = (parseFloat(list_price) + parseFloat(tax_amount1));
						
						
						sale_price.val(sale.toFixed(2));

						
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

$('select[name=sales_tax_id], input[name=sale_price]').on('change input', function() {
      var list_price = $('input[name=list_price]');
      var sale_price = $('input[name=sale_price]').val();
      var tax_id = $('select[name=sales_tax_id]').val();
      var tax_value2 = $('select[name=sales_tax_id]').find('option:selected').data('value');
      if(tax_value2 == undefined){
      	tax_value2 = 0.00;
      }else{
      	tax_value2 = $('select[name=sales_tax_id]').find('option:selected').data('value');
      }

	  var tax_amount2 = sale_price / (1 + tax_value2/100);	

         list_price.val(tax_amount2.toFixed(2));
	});
	
	//to show and hide hsn select and text box
	$('.show_hsn_text').hide();


	$('.show_hsn').on('click',function(){

		//alert();
		
		$('.show_hsn_select').toggle();
		//$('.show_hsn_text').css('display','none');
		$('input[name=hsn_name]').removeAttr('disabled');
		$('input[name=hsn]').prop('disabled',true);
		
		$('.show_hsn_text').toggle();



	});
	$('.hide_hsn').on('click',function(){

		//alert();
		
		$('.show_hsn_select').toggle();
		//$('.show_hsn_text').css('display','none');
		$('input[name=hsn_name]').attr('disabled','disabled');
		$('input[name=hsn]').prop('disabled',false);		
		$('.show_hsn_text').toggle();



	});

	/*$('input[name=hsn_name]').blur(function(){
		var sales_id = $('select[name=sales_tax_id]').val();
		console.log("sales_id"+sales_id);
		var purchase_id = $('select[name=purchase_tax_id]').val();
		console.log("purchase_id"+purchase_id);

		if($('input[name=hsn_name]').val() && (sales_id == '' || purchase_id == ''))
		{
		console.log("work");

			
			$('.vehicle_search_modal_ajax').modal('show');
			$('.vehicle_search_modal_ajax').find('.yes_btn').css('display','none');
			$('.vehicle_search_modal_ajax').find('#content').text('Select  the Sales Tax and Purchase Tax.');

			$('.add_modal_ajax_btn').on('click',function(){
				$('.vehicle_search_modal_ajax').modal('hide');

					
			});

		}


	});*/


	$(".hsn_name").autocomplete({

		source: "{{ route('hsn_name_search') }}",
		minLength:2,
		select: function( event, ui ) {	
		      		
		       	$('input[name=hsn_name]').val(ui.item.label);

		       		var id=ui.item.id;
		       	
		       		$.ajax({
		       			url : "{{ route('gst_hsn_taxes') }}",
		       			type : "post",
		       			data :
		       			{
		       				_token : "{{ csrf_token() }}",
		       				id : id
		       			},
		       			success:function(data)
		       			{
		       				
		       				var valu = $('select[name=sales_tax_id] option[data-value="'+data.rate+'"]').val();
		       				$('select[name=sales_tax_id]').val(valu).trigger('change');
		       				$('select[name=purchase_tax_id]').val(valu).trigger('change');
		       			},
		       			error:function()
		       			{

		       			}

		       		});		       		
	       		
		       		     		

		       	}
	});


$('input[name=purchase_price_withtax]').on('input',function(){
		var purchase_price_and_tax = $('input[name=purchase_price_withtax]').val();
		var purchase_price_value = $('input[name=purchase_price]');
		var selected_value = $('#purchase_tax_id').find('option:selected').data('value');
		if(selected_value == undefined)
		{
			selected_value = 0.00;
		}else{
			selected_value = $('#purchase_tax_id').find('option:selected').data('value');
		}
		
		tax =  1 + selected_value/100;
		tax_am = parseFloat(purchase_price_and_tax) / parseFloat(tax);
		purchase_price_value.val(tax_am.toFixed(2));

	});

	$('input[name=purchase_price],#purchase_tax_id').on('input change',function(){
		var purchase_price_withtax = $('input[name=purchase_price_withtax]');
		var purchase_price = $('input[name=purchase_price]').val();
		var purchase_price_withtax_amount = $('#purchase_tax_id').find('option:selected').data('value');
				
		if(purchase_price_withtax_amount == undefined)
		{
			purchase_price_withtax_amount = 0.00;
		}else{
			purchase_price_withtax_amount = purchase_price_withtax_amount;
		}
		var tot = purchase_price_withtax_amount/100;
		var tot_amount = purchase_price * tot;
		
		purchase_price_withtax.val((parseFloat(purchase_price) + parseFloat(tot_amount)).toFixed(2));

	});

/*$('select[name=purchase_tax_id], input[name=purchase_price]').on('change input', function() {

		var purchase_price = $('input[name=purchase_price]').val();	
		var purchase_price_withtax = $('input[name=purchase_price_withtax]');
		var purchase_tax_id = $('select[name=purchase_tax_id]').val();
        var purchase_tax_value = $('select[name=purchase_tax_id]').find('option:selected').data('value'); 
        
        if(purchase_tax_value == undefined){
        	purchase_tax_value = 0.00;
        }else{
        	 purchase_tax_value = $('select[name=purchase_tax_id]').find('option:selected').data('value'); 
        }

        var	purchase_tax_amount = parseFloat(isNaN(purchase_tax_value) ? 0 : purchase_tax_value/100 ) * parseFloat(purchase_price);

       var purchase_tax_amount = (parseFloat(purchase_price) + parseFloat(purchase_tax_amount));
       
       if(isNaN(purchase_tax_amount)){
               purchase_price_withtax.val("");
             }else{
	             purchase_price_withtax.val(parseFloat(purchase_tax_amount).toFixed(2));
            }     	
	    });

	    var selling_price = $('input[name=list_price]').length;
	    var purchase_price = $('input[name=purchase_price]').length;
	    var list_price = $('input[name=list_price]').length;

	if(selling_price && purchase_price > 0){
		var selling_amount = $('input[name=list_price]').val();
		var purchase_amount = $('input[name=purchase_price]').val();
		var profit = parseFloat(selling_amount) - parseFloat(purchase_amount);
	    if(isNaN(profit)){
	    $('input[name=profit]').val("");
	     }else{
	     $('input[name=profit]').val(parseFloat(profit).toFixed(2));

		}
	}

 if(purchase_price > 0){

 	var purchase_amount = $('input[name=purchase_price]').val();
 	var purchase_amount_withtax = $('input[name=purchase_price_withtax]');
 	var purchase_tax_id = $('select[name=purchase_tax_id]').val();
    var purchase_tax_value = $('select[name=purchase_tax_id]').find('option:selected').data('value');
    if(purchase_tax_value == undefined){
        	purchase_tax_value = 0.00;
        }else{
        	 purchase_tax_value = $('select[name=purchase_tax_id]').find('option:selected').data('value'); 
        } 
    var	pruchasetax_amount = parseFloat(isNaN(purchase_tax_value) ? 0 : purchase_tax_value/100 ) * parseFloat(purchase_amount);

    var purchaseprice_withtax = (parseFloat(purchase_amount) + parseFloat(pruchasetax_amount));
    if(isNaN(purchaseprice_withtax)){
    	purchase_amount_withtax.val('');
    }else{
    	purchase_amount_withtax.val(parseFloat(purchaseprice_withtax).toFixed(2));
    }
    
 }*/

	$('input[name=list_price], input[name=sale_price], input[name=purchase_price]').on('change input', function() {

	    var selling_price = $('input[name=list_price]').val();
	    var purchase_price = $('input[name=purchase_price]').val();
	    var profit = parseFloat(selling_price)- parseFloat(purchase_price);
		if(isNaN(profit)){
		    $('input[name=profit]').val("");
		}else{
		   $('input[name=profit]').val(parseFloat(profit).toFixed(2));
		}
	});
	
		@include('modals.add_gst')

	
			
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
				
				sale_price: {
					required: true
				},
				income_account: {
					required: true
				},
				on_date: {
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
				
				sale_price: {
					required: "Sale Price Quantity is required."
				},
				income_account: {
					required: "Income Account is required."
				},
				on_date: {
					required: "On Date is required."
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
				 url: '{{ route('item.update') }}',
				 type: 'post',
				 data: {
					_token: '{{ csrf_token() }}',
					_method: 'PATCH',
					id: $('input[name=id]').val(),
					name: $('input[name=name]').val(),
					on_date: $('input[name=on_date]').val(),
					sku: $('input[name=sku]').val(),
					//hsn: $('input[name=hsn]').val(),
					hsn : $('input[name=hsn]').is(':disabled') ? $('input[name=hsn_name]').val() : $('input[name=hsn]').val(),
					mpn: $('input[name=mpn]').val(),
					duration : $('input[name=duration]').val(),
					mrp : $('input[name=mrp]').val(),
					marketing_price : $('input[name=marketing_price]').val(),
					minimum_order_quantity: $('input[name=minimum_order_quantity]').val(),
					purchase_price: $('input[name=purchase_price]').val(),
					list_price: $('input[name=list_price]').val(),
					discount: $('input[name=discount]').val(),
					sale_price: $('input[name=sale_price]').val(),
					description: $('text[name=description]').val(),
					low_stock: $('input[name=low_stock]').val(),
					include_tax: $('input[name=include_tax]:checked').val(),
					include_purchase_tax: $('input[name=include_purchase_tax]:checked').val(),
					income_account: $('select[name=income_account]').val(),
					expense_account: $('select[name=expense_account]').val(),
					inventory_account: $('select[name=inventory_account]').val(),
					//category_id: $('select[name=category_id]').val(),					
					initial_quantity: $('input[name=initial_quantity]').val(),
					unit_id: $('select[name=unit_id]').val(),
					tax_id: $('select[name=sales_tax_id]').val(),
					purchase_tax_id: $('select[name=purchase_tax_id]').val(),
					purchase: $('input[name=purchase]:checked').val(),				 	
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

						var category_name = ($('select[name=category_id] option:selected').val() == "") ? '' : $('select[name=category_id] option:selected').text();

						var in_stock = ($('input[name=initial_quantity]').val() != "") ? $('input[name=initial_quantity]').val()+ ' ' +data.data.unit : '';



						if(data.data.status == 1) {
							active_selected = "selected";
							selected_text = "Active";
							selected_class = "badge-success";
						} else if(data.data.status == 0) {
							inactive_selected = "selected";
						}

					
						var adjust_text = "";
						var adjust_class = "";

						if(in_stock != "") {
							adjust_text = "Adjust Quantity";
							adjust_class = "badge-info";
						}
					

						call_back(`<tr role="row" class="odd">
							<td>
								<input id="`+data.data.id+`" class="item_check" name="discount" value="`+data.data.id+`" type="checkbox">
								<label for="`+data.data.id+`"><span></span></label>
							</td>
							<td><img style="border:2px solid #ccc; border-radius: 3px;" src="" width="50" height="50">`+data.data.name+`</td>
							<td>`+data.data.category_name+`</td>	
							<td>`+data.data.in_stock+`</td>	
							<td>`+data.data.purchase_price+`</td>			      	
							<td>`+data.data.selling_price+`</td>			      			      	
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
							<a href="{{url('item')}}/`+data.data.id+`" data-id="`+data.data.id+`" class="grid_label action-btn show-icon show"><i class="fa fa-eye"></i></a>
							&nbsp;
							<a href="javascript:;" data-id="`+data.data.id+`" data-stock="`+in_stock+`" class="grid_label badge `+adjust_class+` create">`+adjust_text+`</a></td></tr>`, `edit`, data.message, data.data.id);		
						$('.loader_wall_onspot').hide();
					},
				 error:function(jqXHR, textStatus, errorThrown) {
					//alert("New Request Failed " +textStatus);
					}
				});
			}
		});

	</script>

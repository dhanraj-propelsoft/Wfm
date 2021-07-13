<div class="content">
<!-- <div class="modal-header"> -->
<div class="fill header">
	<h3 class="float-left voucher_name">
		Material Receipt# {{$voucher_no}}
	</h3>
	<!-- <div style="cursor: pointer;" class="float-left voucher_code"><i style="font-size: 20px; color: #b73c3c; padding-top: 5px; padding-left: 5px;" class="fa icon-basic-gear"></i></div> -->
	<div class="float-right close_full_modal"><i style="font-size: 60px; margin-top: -15px;" class="fa icon-arrows-remove"></i>

	</div>
</div>
<!-- </div> -->
<div class="clearfix"></div>
{!! Form::open(['class' => 'form-horizontal validateform']) !!}
	{{ csrf_field() }}
<!--   <div class="modal-body"> -->

<div class="form-body" style="padding: 15px 25px; ">
	<div class="form-group">

		

	<div class="form-group">
		<div class="row">
			<div class="col-md-3 customer_type" > 
				{{ Form::label('customer', 'Vendor Type', array('class' => 'control-label required')) }} <br>
			<input id="people_type" value="0" type="radio" name="customer" checked="checked" id="people_type" />
			<label for="people_type"><span></span>People</label>
			<input id="business_type" value="1" type="radio" name="customer" id="business_type" />
			<label for="business_type"><span></span>Business</label>
			</div>
			<div class="col-md-3 search_container people"> 
				{{ Form::label('people', 'Vendor', array('class' => 'control-label required')) }}
				{{ Form::select('people_id', $people, null, ['class' => 'form-control person_id', 'id' => 'person_id']) }}
			<div class="content"></div>
			</div>

			<div class="col-md-3 search_container business"  > {{ Form::label('business', 'Vendor', array('class' => 'control-label required')) }}
			{{ Form::select('people_id', $business, null, ['class' => 'form-control business_id', 'id' => 'business_id', 'disabled']) }}
			<div class="content"></div>
			</div>
			<div class="col-md-3">
			<label class="required" for="date">Date</label>
			{{ Form::text('date', ($transaction_type->date_setting == 0) ? date('d-m-Y') : null, ['class'=>'form-control accounts-date-picker']) }}
			</div>					
		</div>
	</div>

	<div class="form-group">
		<div class="row">
			<div class="col-md-3">
				<label  for="warehouse_id">Warehouse</label>
				{!! Form::select('warehouse_id', $warehouse, null, ['class' => 'form-control select_item','id' => 'warehouse_id']); !!}
			</div>
			<div class="col-md-3">
				<label  for="store_id">Store</label>
				{!! Form::select('store_id', $stores, null, ['class' => 'form-control select_item', 'id' => 'store_id']); !!}
			</div>
			<div class="col-md-3">
				<label  for="rack_id">Rack</label>
				{!! Form::select('rack_id', ['' => 'Select Rack'], null, ['class' => 'form-control select_item', 'id' => 'rack_id']); !!}
			</div>
		</div>
	</div>

	<div class="form-group">
		<div class="row">
			<div class="col-md-3 search_container people"> 
				{{ Form::label('employee_id', 'Employee', array('class' => 'control-label required')) }}
				{{ Form::select('employee_id', $employees, null, ['class' => 'form-control employee_id', 'id' => 'employee_id']) }}
			<div class="content"></div>
			</div>
			<div class="col-md-3" > 
				<label class="required" for="usage_type">Usage Type</label><br>
			<input id="only_for_storage" type="radio" name="usage_type" value="0" checked>
			<label for="only_for_storage"><span></span>Only for Storage</label>
			<input id="received_for_work" type="radio" name="usage_type" value="1">
			<label for="received_for_work"><span></span>Received for work</label>
			</div>

			<div class="col-md-3 type_of_work" style="display:none;">
				<label  for="inventory_category_id">Type of Work</label>
				{!! Form::select('inventory_category_id', [], null, ['class' => 'form-control select_item', 'id' => 'inventory_category_id']); !!}
			</div>
		</div>
	</div>	

	<div class="form-group">
		<table style="border-collapse: collapse;" class="table table-bordered crud_table">
			<thead>
			<tr>
				<th width="4%">#</th>
				<th width="25%"> Item </th>
				<th style=" width="10%">Quantity</th>
				<th width="7%"></th>
			</tr>
			<tr>
			</thead>
			<tbody>
			<tr>
				<td class="sorter"><span class="index_number" style="float: right; padding-left: 5px;">1</span></td>
				<td>
					<select name="item_id" class="form-control select_item" id="item_id">
					<option value="">Select Item</option>
					<?php $selected_item = null; ?>
					
					
						@foreach($items as $item)
							@if($selected_item != $item->category) 
					
					<optgroup label="{{$item->category}}"> @endif
							
					
					<?php $selected_item = $item->category; ?>
					<option data-tax="{{$item->include_tax}}" data-purchase_tax="{{$item->include_purchase_tax}}" data-rate = "" value="{{$item->id}}">{{$item->name}}</option>					
					
					@endforeach
							</optgroup>
					</select>
					{{ Form::textarea('description', null, ['class'=>'form-control', 'style'=>'margin-top:5px; border: 1px dashed #ccc' , 'placeholder' => 'Description', 'size' => '3x2', ]) }}
				</td>				
				<td>{{ Form::text('quantity', null, ['class'=>'form-control']) }}</td>
				<td><a style="display: none;" class="grid_label action-btn delete-icon remove_row"><i class="fa fa-trash-o"></i></a> <a class="grid_label action-btn edit-icon add_row"><i class="fa fa-plus"></i></a></td>
			</tr>
			</tbody>
		</table>
	</div>

</div>
<!--   </div> -->
	<div class="save_btn_container">
	<button type="reset" class="btn btn-default cancel_transaction clear">Cancel</button>
	<button style="float:right" type="submit" class="btn btn-success save">Save </button>
	<div style="margin:-25px auto 0px; width: 150px;">
	</div>
	{!! Form::close() !!} 

<script type="text/javascript">

var current_select_item = null;
	
	$(document).ready(function() {

	basic_functions();

	$('#received_for_work').on('click',function() {
		$('.type_of_work').show();
	});

	$('#only_for_storage').on('click',function() {
		$('.type_of_work').hide();
	});

	$("table").rowSorter({
		handler: "td.sorter",
		onDrop: function() { 
			var i = 1;
			$('.crud_table').find('tbody tr').each(function() {
				$(this).find('.index_number').text(i++);
			}) 
		}
	});

	$('.cancel_transaction').on('click', function(e) {
		e.preventDefault();
		$('.close_full_modal').trigger('click');
		
	});

	

	$("select[name=warehouse_id]" ).change(function () {
		var store = $( "select[name=store_id]" );
		var rack = $( "select[name=rack_id]" );
		var id = $(this).val();
		store.val("");
		store.select2('val', '');
		store.empty();
		rack.val("");
		rack.select2('val', '');
		rack.empty();
		
			$('.loader_wall_onspot').show();
			$.ajax({
				 url: '{{ route('get_store') }}',
				 type: 'get',
				 data: {
					_token :$('input[name=_token]').val(),
					warehouse_id: id
					},
				 dataType: "json",
					success:function(data, textStatus, jqXHR) {
						var store_result = data.store_result;
						var rack_result = data.rack_result;
						store.append("<option value=''>Select Store</option>");
						rack.append("<option value=''>Select Rack</option>");
						for(var i in store_result) {  
							store.append("<option value='"+store_result[i].store_id+"'>"+store_result[i].store_name+"</option>");		
						}
						for(var i in rack_result) {  

							rack.append("<option value='"+rack_result[i].rack_id+"'>"+rack_result[i].rack_name+"</option>");
						}
						$('.loader_wall_onspot').hide();
					},
			 error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
		
	});

	$("select[name=store_id]" ).change(function () {
		var rack = $( "select[name=rack_id]" );
		var id = $(this).val();
		rack.val("");
		rack.select2('val', '');
		rack.empty();
		if(id != "") {
			$('.loader_wall_onspot').show();
			$.ajax({
				 url: '{{ route('get_rack') }}',
				 type: 'get',
				 data: {
					_token :$('input[name=_token]').val(),
					store_id: id
					},
				 dataType: "json",
					success:function(data, textStatus, jqXHR) {
						var result = data.result;
						rack.append("<option value=''>Select Rack</option>");
						for(var i in result) {  
							rack.append("<option value='"+result[i].id+"'>"+result[i].name+"</option>");
						}
						$('.loader_wall_onspot').hide();
					},
			 error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
		}
	});

	/*$("select[name=warehouse_id]" ).change(function () {
		var store = $( "select[name=store_id]" );
		var id = $(this).val();
		store.val("");
		store.select2('val', '');
		store.empty();
		if(id != "") {
			$('.loader_wall_onspot').show();
			$.ajax({
				 url: '{{ route('get_store') }}',
				 type: 'get',
				 data: {
					_token :$('input[name=_token]').val(),
					warehouse_id: id
					},
				 dataType: "json",
					success:function(data, textStatus, jqXHR) {
						var result = data.result;
						store.append("<option value=''>Select Store</option>");
						for(var i in result) {  
							store.append("<option value='"+result[i].id+"'>"+result[i].name+"</option>");
						}
						$('.loader_wall_onspot').hide();
					},
			 error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
		}
	});*/

	

	$('body').off('click', '.add_row').on('click', '.add_row', function() {

		var obj = $(this);
		var quantity = obj.closest("tr").find('input[name="quantity"]');
		var item = obj.closest("tr").find('select[name="item_id"]');
		var remaining_item = obj.closest("tr").find('select[name="item_id"] > optgroup > option');

		if(item.val() != "" && quantity.val() != "" && quantity.val() != 0 ) {
			$('.select_item').each(function() { 
				var select = $(this);  
				if(select.data('select2')) { 
					select.select2("destroy"); 
				} 
			});

			var clone = $(this).closest('tr').clone(true, true);
			var selected_item = item.find(':selected').val();
			clone.find('select[name=item_id]').val("");
			clone.find('select[name=item_id] > optgroup > option[value="' + selected_item + '"]').wrap('<span>');
			clone.find('.index_number').text($('.crud_table tbody tr').length + 1);

			if(remaining_item.length > 1) {

				clone.find('td').last().html('<a class="grid_label action-btn delete-icon remove_row"><i class="fa fa-trash-o"></i></a><a class="grid_label action-btn edit-icon add_row"><i class="fa fa-plus"></i></a>');

				if(remaining_item.length == 2) {
					clone.find('td').last().html('<a class="grid_label action-btn delete-icon remove_row"><i class="fa fa-trash-o"></i></a>');
				}

				obj.closest('tr').after(clone);
			}

			obj.parent().html('<a class="grid_label action-btn delete-icon remove_row"><i class="fa fa-trash-o"></i></a>');

			item.find('optgroup > option[value!="' + selected_item + '"]').wrap('<span>');
			$('.select_item').select2();
		}

		});

	$('body').on('click', '.remove_row', function() {

		var obj = $(this);
		var item = obj.closest("tr").find('select[name="item_id"]');
		var remaining_item = obj.closest("tr").find('select[name="item_id"] > optgroup > option');
		var last_row_item = obj.closest("table").find('tr').last().find('select[name="item_id"] > optgroup > option');
		var selected_item = item.find(':selected').val();
		var selected_item_array = [];

		last_row_item.each(function() {
			selected_item_array.push($(this).val());
		});

		selected_item_array.push(selected_item);
	
		obj.closest('tr').remove();

		for (var i in selected_item_array) {
			$('select[name=item_id]:last').find('optgroup > span > option[value="' + selected_item_array[i] + '"]').unwrap();
		}

		$('select[name="item_id"]:last > span > option').unwrap();

		var row_index = $('.crud_table tbody > tr').length;

		//console.log(row_index);

		if(row_index > 1) {
			$('.crud_table').find('tr').last().find('td').last().html('<a class="grid_label action-btn delete-icon remove_row"><i class="fa fa-trash-o"></i></a><a class="grid_label action-btn edit-icon add_row"><i class="fa fa-plus"></i></a>');
		} else {
			$('.crud_table').find('tr').last().find('td').last().html('<a class="grid_label action-btn edit-icon add_row"><i class="fa fa-plus"></i></a>');
		}

	});


	$('.business').hide();

	$('#people_type').on('click', function(){
		$('.people').show();
		$('.business').hide();
		$('.people').find('select').prop('disabled', false);
		$('.business').find('select').prop('disabled', true);

	});

	$('#business_type').on('click', function(){	
		$('.business').show();
		$('.people').hide();
		$('.business').find('select').prop('disabled', false);
		$('.people').find('select').prop('disabled', true);
	});



	$('#person_id').each(function() {
		$(this).prepend('<option value="0"></option>');
		select_user($(this));
	});

	$('#business_id').each(function() {
		$(this).prepend('<option value="0"></option>');
		select_business($(this));
	});



});

	$('.validateform').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		rules: {
			people_id: { required: true },
			employee_id: { required: true },                
		},

		messages: {
			people_id: { required: "Vendor Name is required." },
			employee_id: { required: "Employee is required."},                
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

		submitHandler: function(form) {
			$('.loader_wall_onspot').show();
			$.ajax({
			url: '{{ route('material_receipt.store') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				user_type: $('input[name=customer]:checked').val(),
				people_id: $('select[name=people_id]:not([disabled])').val(),
				employee_id: $('select[name=employee_id]').val(),
				date: $('input[name=date]').val(),
				warehouse_id: $('select[name=warehouse_id]').val(),
				store_id: $('select[name=store_id]').val(),
				rack_id: $('select[name=rack_id]').val(),
				work_id: $('select[name=inventory_category_id]').val(),

				item_id: $('select[name=item_id]').map(function() { 
				return this.value; 
				}).get(),
				description: $('textarea[name=description]').map(function() { 
				return this.value; 
				}).get(),
				quantity: $('input[name=quantity]').map(function() { 
				return this.value; 
				}).get(),

				},
			success:function(data, textStatus, jqXHR) {

				call_back(`<tr role="row" class="odd">
					<td><input id="`+data.data.id+`" class="item_check" name="material_receipt" value="`+data.data.id+`" type="checkbox"><label for="`+data.data.id+`"><span></span></label>
					</td>
					<td>`+data.data.employee_name+`</td>
					<td>`+data.data.order_no+`</td>
					<td>`+data.data.date+`</td>
					
					<td>
					<a data-id="`+data.data.id+`" class="action-btn grid_label edit-icon edit"><i class="fa li_pen"></i></a>&nbsp;
					<a data-id="`+data.data.id+`" class="action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a>
					</td></tr>`, `add`, data.message);

				$('.close_full_modal').trigger('click');
				$('.loader_wall_onspot').hide();

				},
			error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
		}
	});


</script> 
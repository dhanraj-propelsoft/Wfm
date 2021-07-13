@extends('layouts.master')
@section('head_links') @parent
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
@stop

@if(Session::get('module_name') == "trade_wms")
	@include('includes.trade_wms')
@endif

@section('content')
@include('modals.user_search_modal')
@include('modals.add_user_modal')

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
	<h4 class="float-left page-title">Customer</h4>
	
		<!-- <a class="btn btn-danger float-right add" style="color: #fff">+ New</a> -->
	
</div>

<div class="float-left table_container" style="width: 100%; padding-top: 10px;">
	<div class="batch_container">
		<div class="batch_action"><i class="fa icon-arrows-slide-down1 arrow"></i>Batch Actions <i class="fa fa-caret-down "></i>
		</div>
		<ul class="batch_list">
			@permission('department-delete')
				<li><a class="multidelete">Delete</a></li>
			@endpermission
			@permission('department-edit')
				<li><a data-value="1" class="multiapprove">Make Active</a></li>
				<li><a data-value="0" class="multiapprove">Make In-Active</a></li>
			@endpermission
		</ul>
	</div>
	<table id="datatable" class="table data_table table-hover" width="100%" cellspacing="0">
		<thead>
			<tr>
				<th> {{ Form::checkbox('check_all', 'check_all', null, ['id' => 'check_all'] ) }} <label for="check_all"><span></span></label></th>
				<th>Propel ID</th>
					@if($name == "vendor")
				<th>Supplier Name</th>
					@elseif($name == "customer")
					<th>Customer Name</th>
					@endif

				<th>Contact Person</th>
				<th>Contact</th>				
				<th>Overall Supply</th>
				<th>Credit Limit</th>
				<th>Status</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
		@foreach($peoples as $customer)
			<tr>
				<td width="1">{{ Form::checkbox('customer',$customer->id, null, ['id' => $customer->id, 'class' => 'item_check']) }}<label for="{{$customer->id}}"><span></span></label></td>
				<td>{{ $customer->crm_id }}</td>
				<td class="display_name">{{ $customer->display_name }} </td>
				<td class="customer_name">{{ $customer->contact_person }}</td>
				<td>
					<span class="mobile_no">{{ $customer->mobile_no }}</span> <br>
					<span class="email_address"> {{ $customer->email_address }}</span></td>				
				<td>{{ $customer->total }}</td>
				<td class="max_credit_limit">{{ $customer->max_credit_limit }}</td>
				<td>
					@if($customer->status == 1)
						<label class="grid_label badge badge-success status">Active</label>
					@elseif($customer->status == 0)
						<label class="grid_label badge badge-warning status">In-Active</label>
					@endif

					<select style="display:none" id="{{ $customer->id }}" class="active_status form-control">
					<option @if($customer->status == 1) selected="selected" @endif value="1">Active</option>
					<option @if($customer->status == 0) selected="selected" @endif value="0">In-Active</option>
					</select>
				</td>
				<td>	
					<a data-id="{{ $customer->id }}" class="grid_label action-btn edit-icon edit"><i class="fa li_pen"></i></a>
					
					<a data-id="{{ $customer->id }}" class="grid_label action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a>
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
   var edit_id = null;
   var edit_obj = null;

   var datatable_options = {"columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": [[1, "asc"]], "stateSave": true};

	function call_back(data, modal, message, id = null) {
		datatable.destroy();
		if($('.edit[data-id="' + id + '"]')) {
			$('.edit[data-id="' + id + '"]').closest('tr').remove();
		}
		$('.data_table tbody').prepend(data);
		datatable = $('#datatable').DataTable(datatable_options);
		$('.crud_modal').modal('hide');

		$('.alert-success').text(message);
		$('.alert-success').show();

		setTimeout(function() { $('.alert').fadeOut(); }, 3000);
	}

	$(document).ready(function() {


		$('.add').on('click', function(e) {
		e.preventDefault();
		$.get("{{ route('breaks.create') }}", function(data) {
		  $('.crud_modal .modal-container').html("");
		  $('.crud_modal .modal-container').html(data);
		});
		//$('.crud_modal').find('.modal-dialog').addClass('modal-lg');
		$('.crud_modal').modal('show');
  		});

	datatable = $('#datatable').DataTable(datatable_options);

	$('body').on('click', '.edit', function(e) {
		e.preventDefault();
		  $('.add_user_modal').modal('show');
		  $('#search_user').closest('.search_user_modal').find('.result tbody').html("");
		  $('#add_user').closest('.search_user_modal').find('.modal-title').text("Search User");
		  $('#add_user')[0].reset();

			edit_id = $(this).data('id');
			edit_obj = $(this);

		  $.ajax({
			url: "{{ url('people') }}/"+$(this).data('id')+"/edit",
			type: 'get',
			success:function(response, textStatus, jqXHR) {
				var data = response.result;
				var billing_cities = response.billing_city;
				var billing_city = $('#add_user').find('select[name=billing_city_id]');

				var shipping_cities = response.shipping_city;
				var shipping_city = $('#add_user').find('select[name=shipping_city_id]');

				billing_city.empty();
				billing_city.append('<option value="">Select City</option>');

				shipping_city.empty();
				shipping_city.append('<option value="">Select City</option>');
				

				for(var i in billing_cities)
				{
					billing_city.append('<option value="'+billing_cities[i].id+'">'+billing_cities[i].name+'</option>');
				}

				for(var i in shipping_cities)
				{
					shipping_city.append('<option value="'+shipping_cities[i].id+'">'+shipping_cities[i].name+'</option>');
				}

				billing_city.val(data.billing_city_id).trigger('change');
				shipping_city.val(data.shipping_city_id).trigger('change');

				$('#add_user').find('select[name=title]').val(data.title_id).trigger('change.select2');
				$('#add_user').find('input[name=first_name]').val(data.first_name);
				$('#add_user').find('input[name=last_name]').val(data.last_name);
				$('#add_user').find('input[name=display_name]').val(data.display_name);
				$('#add_user').find('input[name=mobile_no]').val(data.mobile_no);
				$('#add_user').find('input[name=email_address]').val(data.email_address);
				$('#add_user').find('input[name=web_address]').val(data.web_address);
				$('#add_user').find('input[name=max_credit_limit]').val(data.max_credit_limit);
				$('#add_user').find('select[name=payment_mode_id]').val(data.payment_mode_id).trigger('change.select2');

				$('#add_user').find('input[name=billing_id]').val(data.billing_id);
				$('#add_user').find('input[name=shipping_id]').val(data.shipping_id);
				$('#add_user').find('select[name=term_id]').val(data.term_id).trigger('change.select2');
				$('#add_user').find('textarea[name=billing_address]').val(data.billing_address);
				$('#add_user').find('textarea[name=shipping_address]').val(data.shipping_address);
				$('#add_user').find('select[name=billing_state_id]').val(response.billing_state).trigger('change.select2');
				$('#add_user').find('select[name=shipping_state_id]').val(response.shipping_state).trigger('change.select2');
				$('#add_user').find('input[name=billing_pin]').val(data.billing_pin);
				$('#add_user').find('input[name=billing_google]').val(data.billing_google);
				$('#add_user').find('input[name=shipping_pin]').val(data.shipping_pin);
				$('#add_user').find('input[name=shipping_google]').val(data.shipping_google);
				$('#add_user').find('input[name=phone]').val(data.phone);
				$('#add_user').find('input[name=pan_no]').val(data.pan_no);
				$('#add_user').find('input[name=gst_no]').val(data.gst_no);

				$('.loader_wall_onspot').hide();

			},
			error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
			}
		});
		$('#add_user').show();
  	});

  	$('body').on('click', '.form-update', function(e) {
		e.preventDefault();	    

		$.ajax({
			url: '{{ route('people.update') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				_method: 'PATCH',
				id: edit_id, 
				title_id: $('.add_user_modal select[name=title]').val(),               
				first_name: $('.add_user_modal input[name=first_name]').val(),
				last_name: $('.add_user_modal input[name=last_name]').val(),
				display_name: $('.add_user_modal input[name=display_name]').val(),
				mobile_no: $('.add_user_modal input[name=mobile_no]').val(),
				phone: $('.add_user_modal input[name=phone]').val(),
				email: $('.add_user_modal input[name=email_address]').val(),
				payment_mode_id: $('.add_user_modal select[name=payment_mode_id]').val(),
				term_id: $('.add_user_modal select[name=term_id]').val(),
				web_address: $('.add_user_modal input[name=web_address]').val(),
				credit_limit: $('.add_user_modal input[name=max_credit_limit]').val(),
				billing_id: $('.add_user_modal input[name=billing_id]').val(),

				shipping_id: $('.add_user_modal input[name=shipping_id]').val(),

				billing_address: $('.add_user_modal textarea[name=billing_address]').val(),
				shipping_address: $('.add_user_modal textarea[name=shipping_address]').val(),

				billing_pin: $('.add_user_modal input[name=billing_pin]').val(),
				billing_google: $('.add_user_modal input[name=billing_google]').val(),

				shipping_pin: $('.add_user_modal input[name=shipping_pin]').val(),
				shipping_google: $('.add_user_modal input[name=shipping_google]').val(),				

				billing_city_id: $('.add_user_modal select[name=billing_city_id]').val(),
				shipping_city_id: $('.add_user_modal select[name=shipping_city_id]').val(),
					
				pan_no: $('.add_user_modal input[name=pan_no]').val(),
				gst_no: $('.add_user_modal input[name=gst_no]').val()               
				 
			},
			success:function(data, textStatus, jqXHR) {
				$('.add_user_modal').modal('hide');
				$('#search_user').closest('.search_user_modal').find('.result tbody').html("");
				$('#add_user').closest('.search_user_modal').find('.modal-title').text("Search User");
				$('#add_user')[0].reset();   

				edit_obj.closest('tr').find('.display_name').text(data.data.display_name);
				edit_obj.closest('tr').find('.customer_name').text(data.data.first_name + " " + data.data.last_name);
				edit_obj.closest('tr').find('.mobile_no').text(data.data.mobile_no);
				edit_obj.closest('tr').find('.email_address').text(data.data.email_address);
				edit_obj.closest('tr').find('.max_credit_limit').text(data.data.credit_limit);
			},
			error:function(jqXHR, textStatus, errorThrown) { }
		});
		$('#add_user').show();
  	});

	$('body').on('click', '.status', function(e) {
		$(this).hide();
		$(this).parent().find('select').css('display', 'block');
	});

	$('body').on('change', '.active_status', function(e) {
			var status = $(this).val();
			var id = $(this).attr('id');
			var obj = $(this);
			var url = "{{ route('contact_status_approval') }}";
			change_status(id, obj, status, url, "{{ csrf_token() }}");
		});


  	$('body').on('click', '.delete', function(){
		var id = $(this).data('id');
		var parent = $(this).closest('tr');
		var delete_url = '{{ route('contact.destroy') }}';
		delete_row(id, parent, delete_url, "{{ csrf_token() }}");
	});

	$('body').on('click', '.multidelete', function() {
		var url = "{{ route('contact.multidestroy') }}";
		multidelete($(this), url, "{{ csrf_token() }}");
	});

	$('body').on('click', '.multiapprove', function() {
		var url = "{{ route('contact.multiapprove') }}";
		multi_status($(this), $(this).data('value'), url, "{{ csrf_token() }}");
	});


  });
</script>
@stop
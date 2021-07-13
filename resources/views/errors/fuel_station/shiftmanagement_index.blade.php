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
  <h4 class="float-left page-title">Shift Management</h4>
   <a class="btn btn-danger float-right refresh" style="color: #fff">Refresh</a>
   <a class="btn btn-danger float-right multidelete" style="display:none; color: #fff" >Delete</a>
   <a class="btn btn-danger float-right add" style="display:none; color: #fff">End Pump Shift</a>
  <a class="btn btn-danger float-right start"style= "@if($start_shift == 2) display:none @endif ;color: #fff">Start Pump Shift</a>
  <!-- <a class="btn btn-danger float-right invoice" style="color: #fff">Invoices</a> -->
  


</div>

<div class="float-left table_container" style="width: 100%; padding-top: 10px;">
	
<table id="datatable" class="table data_table" width="100%" cellspacing="0">
	<thead>
	  <tr>
	  	<th> {{ Form::checkbox('check_all', 'check_all', null, ['id' => 'check_all'] ) }} <label for="check_all"><span></span></label></th>
		<th> Shift </th> 
		<th>Date </th>
		<th> PumpName </th>
		<th> Person </th>
		<th> Started At </th>
		<th> Ended At </th>
		<th> Open meter </th>
		<th> Close meter </th>
		<th> Test Qty </th>
		<th> Sales Qty </th>
		<th> Sales Amount </th>
		<th> Approved</th>
	  </tr>
	</thead>
	<tbody>

		@foreach($shift as $shift)
		<tr>
		<td width="1">{{ Form::checkbox('shift',$shift->id, null, ['id' => $shift->id, 'class' => 'item_checkbox','data-id' => $shift->id]) }}<label for="{{$shift->id}}"><span></span></label></td>
		<td>{{$shift->shift_name}}</td>
		<td>{{$shift->start_date}}</td>
		<td>{{$shift->pumpname}}</td>
		<td>{{$shift->employeename}}</td>
		<td>{{$shift->start_time}}</td>
		<td>{{$shift->end_time}}</td>
		<td>{{$shift->pump_openmeter}}</td>
		<td>{{$shift->pump_closemeter}}</td>
		<td>{{$shift->pump_testing}}</td>
		<td>{{$shift->pump_salesquantity}}</td>
		<td>{{$shift->pump_sales}}</td>
		<td>@if($shift->approvel_status == 0)
						<label class="grid_label badge badge-warning ">Draft</label> 
					@elseif($shift->approvel_status == 1)
						<label class="grid_label badge badge-success ">Approved</label> 
					@endif</td>
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

	$('.item_checkbox').on('click', function(e) {
		var item_id =$('.item_check:checked').val();
		//alert(item_id);
		$('.add').show();
		$('.multidelete').show();

	});

   var datatable = null;

   var datatable_options = {"columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": [[1, "asc"]], "stateSave": true};

  $(document).ready(function() {

	datatable = $('#datatable').DataTable(datatable_options);

  $('.start').on('click', function(e) {
		e.preventDefault();

		$.get("{{ route('shift.create') }}", function(data) {
		  $('.crud_modal .modal-container').html("");
		  $('.crud_modal .modal-container').html(data);
		});
		$('.crud_modal').find('.modal-dialog').addClass('modal-lg');
		$('.crud_modal').modal('show');
  });
  $('.add').on('click', function(e) {
  		var item_id =$('.item_checkbox:checked').val();
  		//alert(a);
		e.preventDefault();
		$('.loader_wall_onspot').show();
		$('body').css('overflow', 'hidden');
		$('.full_modal_content').animate({ height: $(window).height() + 'px' }, 400, function() {
		$.get("{{ url('fuel_station/end_pumpshift') }}/"+item_id, function(data) {
		$('.full_modal_content').show();
		$('.full_modal_content').html("");
		$('.full_modal_content').html(data);
		
		$('.full_modal_content').css({ top: $("#page-header").height()+'px', left: ($('#sidebar-menu').outerWidth())+'px', width: ($(window).outerWidth()-$('#sidebar-menu').outerWidth()) +'px' });
		$('.loader_wall_onspot').hide();  
	});
  });
		});

  	$('body').on('click', '.edit', function(e) {
		e.preventDefault();
		$.get("{{ url('hrm/departments') }}/"+$(this).data('id')+"/edit", function(data) {
		  $('.crud_modal .modal-container').html("");
		  $('.crud_modal .modal-container').html(data);
		});
		$('.crud_modal').modal('show');
  	});

  	$('body').on('click', '.status', function(e) {
		$(this).hide();
		$(this).parent().find('select').css('display', 'block');
  	});
	$('body').on('change', '.active_status', function(e) {
			var status = $(this).val();
			//alert(status);
			var id = $(this).attr('id');
			var obj = $(this);
			var url = "{{ route('pump.status') }}";
			var data=change_status(id, obj, status, url, "{{ csrf_token() }}");
			console.log(data);
		});
  	$('body').on('click', '.delete', function(){
	var id = $(this).data('id');
	var parent = $(this).closest('tr');
	var delete_url = '{{ route('hrm_departments.destroy') }}';
	delete_row(id, parent, delete_url);
   	});

	$('body').on('click', '.multidelete', function() {
		var url = "{{ route('fuel_station.shift_multidestroy') }}";
			//alert(url);
		multidelete( url);
	});

	function multidelete( url) {
		//alert(url);
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


							$('body').find("input.item_checkbox[value="+list[i]+"]").closest('tr').remove();
						}
						$('#datatable').find('thead tr th:first :checkbox').prop('indeterminate', false);
						$("input.item_checkbox, input[name=checkbox_all]").prop('checked', false);
						datatable = datatable = $('#datatable').DataTable(datatable_options);
						$('.delete_modal_ajax').modal('hide');
					},
					error: function(jqXHR, textStatus, errorThrown) {}
				});
			});
		}

	$('body').on('click', '.multiapprove', function() {
		var url = "{{ route('hrm_departments.multiapprove') }}";
		multi_status($(this), $(this).data('value'), url);
	});

  });

		
  </script>
@stop
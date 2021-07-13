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
  <h4 class="float-left page-title">DailyDipReading</h4>
   <a class="btn btn-danger float-right refresh" style="color: #fff">Refresh</a>
   <a class="btn btn-danger float-right multidelete" style="display: none; color: #fff" >Delete</a>
    <a class="btn btn-danger float-right edit" style="display: none; color: #fff" >Edit</a>
  <a class="btn btn-danger float-right add" style="color: #fff">+ New</a>

</div>

<div class="float-left table_container" style="width: 100%; padding-top: 10px;">
	
	<table id="datatable" class="table data_table" width="100%" cellspacing="0">
		<thead>
		  <tr>
		 	 <th> {{ Form::checkbox('check_all', 'check_all', null, ['id' => 'check_all'] ) }} <label for="check_all"><span></span></label></th>
			<th> Date </th> 
			<th> TankName </th>
			<th> Reading At </th>
			<th> Reading </th>
			<th> Quantity </th>
			<th> Temparature </th>
			<th> Reading By</th>
			<th> Reading Type </th>
			<th> Status </th>

		 </tr>
		</thead>
		<tbody>
			@foreach($reading as $reading)
		<tr>
			<td width="1">{{ Form::checkbox('tank',$reading->id, null, ['id' => $reading->id, 'class' => 'item_check']) }}<label for="{{$reading->id}}"><span></span></label></td>
			<td>{{ $reading->start_date }}</td>
			
			<td>{{ $reading->name }}</td>
			<td>{{ $reading->start_time }}</td>
			<td>{{ $reading->dip_reading }}</td>

			<td>{{ $reading->quantity }}</td>
			<td>{{ $reading->temparature }}</td>
			<td>{{ $reading->first_name }}</td>
			<td>
				@if($reading->reading_type == 1)
				<label class="grid_label badge badge-success ">Open</label>
				@elseif($reading->reading_type == 2)
				<label class="grid_label badge badge-warning ">Close</label>
				@elseif($reading->reading_type == 3)
				<label class="grid_label badge badge-default ">On-Demand</label>
				@endif
			</td>
			<td>@if($reading->status == 1)
					  		<label class="grid_label badge badge-success status">Approved</label>
						@elseif($reading->status == 0)
					  		<label class="grid_label badge badge-warning status">Draft</label>
						@endif
						<select style="display:none" id="{{ $reading->id }}" class="active_status form-control">
							<option @if($reading->status == 1) selected="selected" @endif value="1">Approved</option>
							<option @if($reading->status == 0) selected="selected" @endif value="0">Draft</option>
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

   var datatable = null;

   var datatable_options = {"columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": [[1, "asc"]], "stateSave": true};

  $(document).ready(function() {

	datatable = $('#datatable').DataTable(datatable_options);

  $('.add').on('click', function(e) {
		e.preventDefault();
		$.get("{{ route('dipreading.create') }}", function(data) {
		  $('.crud_modal .modal-container').html("");
		  $('.crud_modal .modal-container').html(data);
		});
		
		$('.crud_modal').modal('show');
  });

	// $('body').on('click','.edit',function(e)
	// {
	// 	var id=$('.item_check:checked').val();
	// 	e.preventDefault();
	// 	$.get("{{ url('fuel_station/dipreading_edit') }}/"+id,function(data)
	// 	{
	// 		$.('.crud_modal .modal-container').html("");
	// 		$.('.crud_modal .modal-container').html(data);
	// 	});	
	// 	$.('.crud_modal').modal('show');

	// });

	$('body').on('click', '.edit', function(e) {
  		var id =$('.item_check:checked').val();
  		//console.log(item_id);
		e.preventDefault();
		$.get("{{ url('fuel_station/dipreading_edit') }}/"+id, function(data) {
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
			var url = "{{ route('dipreading.status') }}";
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
		var url = "{{ route('fuel_station.dipreading_multidestroy') }}";
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
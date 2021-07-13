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



@if(Session::get('module_name') == "trade_wms")

	@include('includes.trade_wms')

@else

	@include('includes.inventory')

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



<div class="fill header" style="height:43px;width: 102%;background-color: #e3e3e9;margin-left: -10px;">

	<h5 class="float-left page-title" style="padding-top: 8px;padding-left: 10px;"><b>Vehicle Specification values</b></h5>

		<div class="btn-group float-right" style="margin-right: 25px;padding-top: 5px;">

	@permission('specification-values-create')

	<a class="btn btn-danger float-left add" style="color: #fff">New</a>

	@endpermission



	<!-- <a class="btn btn-danger float-left edit" style="color: #fff">Edit</a>



	<a class="btn btn-danger float-left delete" style="color: #fff">Delete</a> -->



	<a class="btn btn-danger float-left refresh" style="color: #fff">Refresh</a>



</div> 



</div>



<div class="float-left table_container" style="width: 100%; padding-top: 10px;">

	<!-- <div class="col-md-2" style="left:180px;top:30px;">

	     <label for="type">Wms Division</label>

	     <select id="" style="width:120px;height:25px;">

  <option></option>

  <option </option>

 

    <option value=""></option>

  

     </select>

	 </div> -->

	<!--  <div class="batch_container">

		<div class="batch_action">

			<i class="fa icon-arrows-slide-down1 arrow"></i>Batch Actions <i class="fa fa-caret-down"></i>

		</div>

		<ul class="batch_list">

			

				<li><a class="multidelete">Delete</a></li>

			

				<li><a data-value="1" class="multiapprove">Make Active</a></li>

				<li><a data-value="0" class="multiapprove">Make In-Active</a></li>

			

		</ul>

	</div>  --> 

	<table id="datatable" class="table data_table" width="100%" cellspacing="0">

		<thead>

			<tr>

				<th>  <label for="check_all"><span></span></label></th>

				<th>Vehicle Type</th>              

				<th>Specification</th>

				<th> Value </th>

				<th> Description </th>

                 <th> Action </th>

			</tr>

		</thead>

		<tbody>

			 @foreach($spec_values as $spec_value)

			<tr>

				<td width="1" style="padding-left: 7px;">{{ Form::checkbox('spec_value',$spec_value->id, null, ['id' => $spec_value->id, 'class' => 'item_check']) }}<label for="{{$spec_value->id}}"><span></span></label></td>   

				<td>{{$spec_value->type_name}}</td>              

				<td>{{$spec_value->spec_name}}</td>

				<td>{{$spec_value->value}}</td>

				<td>{{$spec_value->description}}</td>

                <td><a data-id="{{ $spec_value->id }}" class="grid_label action-btn edit-icon edit"><i class="fa li_pen"></i></a>

				

					<a data-id="{{ $spec_value->id }}" class="grid_label action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a> </td>

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



	var datatable_options = {"columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": [[1, "asc"]], "stateSave": true};



	$(document).ready(function() {



		datatable = $('#datatable').DataTable(datatable_options);



		$('.add').on('click', function(e) {

			e.preventDefault();

			$.get("{{ route('specification_values.create') }}", function(data) {

				$('.crud_modal .modal-container').html("");

				$('.crud_modal .modal-container').html(data);

			});

			$('.crud_modal').find('.modal-dialog').addClass('spec_values');

			$('.crud_modal').modal('show');

		});



		$('body').on('click', '.edit', function(e) {

			e.preventDefault();

			$.get("{{ url('trade_wms/specification_values') }}/"+$(this).data('id')+"/edit", function(data) {

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

			var id = $(this).attr('id');

			var obj = $(this);

			var url = "{{ route('specification_values_status_approval') }}";

			change_status(id, obj, status, url, "{{ csrf_token() }}");

		});



        $('body').on('click', '.delete', function(){

              		var id = $(this).data('id');

			var parent = $(this).closest('tr');

			var delete_url = "{{ route('specification_values.destroy') }}";

			delete_row(id, parent, delete_url, "{{ csrf_token() }}");

	   	});

        $('.refresh').click(function() {

    location.reload();

});



});

</script>

@stop
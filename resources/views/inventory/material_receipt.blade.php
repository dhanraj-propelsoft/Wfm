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
        .select2-container .select2-selection--single
		{
			height: 25px !important;
		}
		.select2-container--default .select2-selection--single .select2-selection__rendered
		{
			line-height: 22px;
		}
		
		input.form-control
		{
			height: 25px;
			
		}
		.full_modal_content .content label
		{
			font-weight: bold;
			font-size : 12px;
		}
    </style>

@stop

@include('includes.inventory')

@section('content')

@include('includes.add_user')

@include('includes.add_business')

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

	<div class="btn-group float-right">

	<a class="btn btn-danger float-left add" style="color: #fff">New</a>

	<a class="btn btn-danger float-left edit" style="color: #fff; display: none;">Edit</a>

	<a class="btn btn-danger float-left multidelete" style="color: #fff">Delete</a>

	<a class="btn btn-danger float-left multiapprove" data-status="1" style="color: #fff">Approve</a>

	<a class="btn btn-danger float-left multinotapprove" data-status="0" style="color: #fff">Not Approve</a>

	<a class="btn btn-danger float-left print" style="color: #fff">Print</a>

	<a class="btn btn-danger float-left excel_export" style="color: #fff">Export to Excel</a>

</div>

</div>



<div class="float-left table_container" style="width: 100%; padding-top: 10px;">

	<div class="batch_container">

		<div class="batch_action">

			<i class="fa icon-arrows-slide-down1 arrow"></i>Batch Actions <i class="fa fa-caret-down"></i>

		</div>

		<ul class="batch_list">

			<li><a class="multidelete">Delete</a></li>

			<li><a data-value="1" class="multiapprove">Make Active</a></li>

			<li><a data-value="0" class="multiapprove">Make In-Active</a></li>

		</ul>

	</div>

	<table id="datatable" class="table data_table table-hover" width="100%" cellspacing="0">

		<thead>

			<tr>

				<th> {{ Form::checkbox('check_all', 'check_all', null, ['id' => 'check_all'] ) }} <label for="check_all"><span></span></label></th>

				<th> Employee Name </th>

				<th> Order No </th>              

				<th> Date </th>	

				<th> Action </th>

			</tr>

		</thead>

		<tbody>

		@foreach($material_receipts as $material_receipt)

			<tr>

				<td width="1" style="padding-left: 7px;">{{ Form::checkbox('material_receipt',$material_receipt->id, null, ['id' => $material_receipt->id, 'class' => 'item_check']) }}<label for="{{$material_receipt->id}}"><span></span></label></td>   

				<td>{{ $material_receipt->first_name }}</td>   

				<td>{{ $material_receipt->order_no }}</td>              

				<td class="rearrangedatetext">{{ $material_receipt->date }}</td>

				

				<td> 

				

			  <a data-id="{{ $material_receipt->id }}" class="grid_label action-btn edit-icon edit"><i class="fa li_pen"></i></a>	



			

			  <a data-id="{{ $material_receipt->id }}" class="grid_label action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a> 

			

					

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



   var datatable_options = {"columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": [[1, "asc"]], "stateSave": true};





  $(document).ready(function() {



  datatable = $('#datatable').DataTable(datatable_options);



  $('.add').on('click', function(e) {

			e.preventDefault(); 

			$('.loader_wall_onspot').show();

			$('body').css('overflow', 'hidden');

			$('.full_modal_content').animate({ height: $(window).height() + 'px' }, 400, function() {



					$.get("{{ route('material_receipt.create') }}", function(data) {

					  $('.full_modal_content').show();

					  $('.full_modal_content').html("");

					  $('.full_modal_content').html(data);

					  $('.full_modal_content form').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });

	        		  $('.full_modal_content').css({ top: $("#page-header").height()+'px', left: ($('#sidebar-menu').outerWidth())+'px', width: ($(window).outerWidth()-$('#sidebar-menu').outerWidth()) +'px' });

					  $('.loader_wall_onspot').hide();

					});

		

			});

				

		});



  /*$('body').on('click', '.edit', function(e) {

		e.preventDefault();

		$.get("{{ url('inventory/material-receipt') }}/"+$(this).data('id')+"/edit", function(data) {

		  $('.crud_modal .modal-container').html("");

		  $('.crud_modal .modal-container').html(data);

		});

		$('.crud_modal').modal('show');

  });*/



  $('body').on('click', '.edit', function(e) {

			e.preventDefault();

			$('.loader_wall_onspot').show();

			$('body').css('overflow', 'hidden');

			obj = $(this);

			var id = obj.data('id');

			$('.full_modal_content').animate({ height: $(window).height() + 'px' }, 400, function() {



					$.get("{{ url('inventory/material-receipt') }}/"+id+"/edit", function(data) {

					  $('.full_modal_content').show();

					  $('.full_modal_content').html("");

					  $('.full_modal_content').html(data);

					  $('.full_modal_content form').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });

	        		  $('.full_modal_content').css({ top: $("#page-header").height()+'px', left: ($('#sidebar-menu').outerWidth())+'px', width: ($(window).outerWidth()-$('#sidebar-menu').outerWidth()) +'px' });

					  $('.loader_wall_onspot').hide();

					  

					});

		

			});

		});



  $('body').on('click', '.status', function(e) {

	$(this).hide();

	$(this).parent().find('select').css('display', 'block');

  });



  $('body').on('change', '.active_status', function(e) {

			var status = $(this).val();

			var id = $(this).attr('id');

			var obj = $(this);

			var url = "{{ route('department_status_approval') }}";

			change_status(id, obj, status, url, "{{ csrf_token() }}");

		});



  $('body').on('click', '.delete', function(){

	var id = $(this).data('id');

	var parent = $(this).closest('tr');

	var delete_url = '{{ route('material_receipt.destroy') }}';

	delete_row(id, parent, delete_url, "{{ csrf_token() }}");

   });



  	$('body').on('click', '.multidelete', function() {

		var url = "{{ route('material_receipt.multidestroy') }}";

		multidelete($(this), url, "{{ csrf_token() }}");

	});



  });

  </script>

@stop
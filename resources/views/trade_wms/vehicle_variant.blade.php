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

		<style>

		.dt-buttons {

			display: none;

		}

		.dataTables_length {

			margin-bottom: -35px;

		}

		.dataTables_processing {

			position: absolute;

			top: 50%;

			left: 50%;

			width: 100%;

			height: 40px;

			margin-left: -50%;

			margin-top: -25px;

			padding-top: 20px;

			text-align: center;

			font-size: 1.2em;

			color: #333333;

			background-color: white;

			background: -webkit-gradient(linear, left top, right top, color-stop(0%, rgba(255, 255, 255, 0)), color-stop(25%, rgba(255, 255, 255, 0.9)), color-stop(75%, rgba(255, 255, 255, 0.9)), color-stop(100%, rgba(255, 255, 255, 0)));

			background: -webkit-linear-gradient(left, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 0.9) 25%, rgba(255, 255, 255, 0.9) 75%, rgba(255, 255, 255, 0) 100%);

			background: -moz-linear-gradient(left, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 0.9) 25%, rgba(255, 255, 255, 0.9) 75%, rgba(255, 255, 255, 0) 100%);

			background: -ms-linear-gradient(left, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 0.9) 25%, rgba(255, 255, 255, 0.9) 75%, rgba(255, 255, 255, 0) 100%);

			background: -o-linear-gradient(left, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 0.9) 25%, rgba(255, 255, 255, 0.9) 75%, rgba(255, 255, 255, 0) 100%);

			background: linear-gradient(to right, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 0.9) 25%, rgba(255, 255, 255, 0.9) 75%, rgba(255, 255, 255, 0) 100%);

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

	<h5 class="float-left page-title" style="padding-top: 8px;padding-left: 10px;"><b>Vehicle Variant</b></h5>

	@permission('variant-create')
	<div style="margin-right: 25px;padding-top: 5px;">
		<a class="btn btn-primary float-right add_version" style="color: #fff">+ Add Version</a>&nbsp;&nbsp;

		<a class="btn btn-danger float-right add" style="color: #fff">+ New Variant</a>
	</div>

	@endpermission

	

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

	 

	

	<div class="float-left table_container" style="width: 100%; padding-top: 10px;" id="table_data">

	

		@include('trade_wms.vehicle_variant_pagination')

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



		SearchElement='<label>Search:<input type="search" class="form-control form-control-sm" placeholder="search" /></label>';

    

		$("#datatable_filter").html(SearchElement);

		

		$("#datatable_paginate").hide();



		



//Start Pagination using Ajax 

// 10.6.19

// 



	/* 

	Page define the pagination Number



	 */

	var page;



var current_sort_options;

var search_input;



/* 

dataTables_Processing - loader in data table



 */

 		$("#datatable_wrapper").find(".dataTables_info").hide();

		$('.dataTables_processing').hide();

	/* *

	@ Click event

		For pagination

	 */



	 $(document).on( 'keyup change','label>input[type="search"]', function (event) {

		

		event.preventDefault(); 

		//search_input = $('.dataTables_filter input').val();

		search_input = $(this).val();

		

		fetch_data(page,entries=false,search_input);

	} );



	// method Pagination



	$(document).on('click', '.pagination a', function(event){



		$('.dataTables_processing').hide();



		current_sort_options=datatable.order();

	

		event.preventDefault(); 

		

		page = $(this).attr('href').split('page=')[1];

		

		fetch_data(page,'',search_input);

	});



	/* ***

	* Change event get the  entries from show entries dropdown

	defalut page value is 1.



	 */



	$(document).on('change', "select[name='datatable_length']", function(event){

		

		$('.dataTables_processing').hide();

		

		event.preventDefault(); 



		console.log(search_input);

		

		page=(page)?page:1;

		

		var datatableLength=$("select[name='datatable_length']").val();

		

		fetch_data(page,datatableLength,search_input);



	});





	/* ***

	* method Fetch_data parameters like page, entries

	page -pagination page

	entriee - no of rows displayed in page.



	defalut page value is 1.

	

	datatable_options_new = {"pageLength": datatableLength, "columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": [],



	datatableLength - no of rows depending on show entries dropdown 

	 */



	function fetch_data(page,entries=false,search=null)

	{

				$('.dataTables_processing').show();

				var sort_options=(current_sort_options)?current_sort_options:[];



				var datatableLength=(entries)?entries:$("select[name='datatable_length']").val();

				

				//console.log(datatableLength);



				url="";

				

				if(search)

				{

					url="variant/global_search?page="+page+"&entrires="+datatableLength+"&search="+search+"";

				

				}else{

				

					url="variant/pagination?page="+page+"&entrires="+datatableLength+"";



				}

			//	var pageLength=(search)?search_result_length:datatableLength;

				

				console.log(datatableLength);

				

				var datatable_options_new = {"pageLength": datatableLength, "columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": sort_options,







							};	





			$.ajax({

				url:url,

				success:function(data)

				{

					$('.dataTables_processing').hide();

					datatable.destroy();

					

					$('#table_data').html(data);

					

					datatable = $('#datatable').DataTable(datatable_options_new);

				

					$("#datatable_filter").html(SearchElement);

					

					$('.dataTables_filter input').val(search_input);

					

					$("#datatable_paginate").hide();

					

					$("#datatable_wrapper").find(".dataTables_info").hide();



					/*

						If Search Input Exist ,Focus the search box 

					 */

					

					if(search_input)

					{

					

						$('.dataTables_filter input').focus();



					}

					// After getting the result from server 

					//console.log(search_input);

					

					// if(search_input){

					// 	datatable.search(search_input).draw();

					// }

	

				}

				});

	}



			

















				//End Pagination Using Ajax



		

		$('.add').on('click', function(e) {

			e.preventDefault();

			$.get("{{ route('vehicle_variant.create') }}", function(data) {

				$('.crud_modal .modal-container').html("");

				$('.crud_modal .modal-container').html(data);

			});

			//$('.crud_modal').find('.modal-dialog').addClass('modal-lg');

			$('.crud_modal').modal('show');

		});

		

		$('.add_version').on('click', function(e) {

			e.preventDefault();

			$.get("{{ route('vehicle_variant_version.create') }}", function(data) {

				$('.crud_modal .modal-container').html("");

				$('.crud_modal .modal-container').html(data);

			});

			//$('.crud_modal').find('.modal-dialog').addClass('modal-lg');

			$('.crud_modal').modal('show');

		});



		$('body').on('click', '.edit', function(e) {

			e.preventDefault();

			$.get("{{ url('trade_wms/vehicle/variant') }}/"+$(this).data('id')+"/edit", function(data) {

				$('.crud_modal .modal-container').html("");

				$('.crud_modal .modal-container').html(data);

			});

			$('.crud_modal').modal('show');

		});



		$('body').on('click', '.status', function(e) {

			$(this).hide();

			$(this).parent().find('select').css('display', 'block');

		});



		$('body').on('click', '.multidelete', function() {

			var url = "{{ route('vehicle_variant.multidestroy') }}";

			multidelete($(this), url, "{{ csrf_token() }}");

		});



		$('body').on('click', '.multiapprove', function() {

			var url = "{{ route('vehicle_variant.multiapprove') }}";

			multi_status($(this), $(this).data('value'), url, "{{ csrf_token() }}");

		});



		$('body').on('change', '.active_status', function(e) {

			var status = $(this).val();

			var id = $(this).attr('id');

			var obj = $(this);

			var url = "{{ route('vehicle_variant_status_approval') }}";

			change_status(id, obj, status, url, "{{ csrf_token() }}");

		});



	  	$('body').on('click', '.delete', function(){

			var id = $(this).data('id');

			var parent = $(this).closest('tr');

			var delete_url = '{{ route('vehicle_variant.destroy') }}';

			delete_row(id, parent, delete_url, "{{ csrf_token() }}");

	   	});



});

</script>

@stop
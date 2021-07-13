@extends('layouts.master')
@section('head_links') @parent
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
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
@include('includes.admin')
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
  <h4 class="float-left page-title">Variant</h4>
  
	<a class="btn btn-danger float-right excel_export" style="color: #fff ">Export to Excel</a>
	<a class="btn btn-primary float-right versionadd" style="color: #fff">+Add Versions</a>
	<a class="btn btn-danger float-right add" style="color: #fff">+ New</a>
 </div>

<div class="float-left table_container" style="width: 100%; padding-top: 10px;" id="table_data">
	
	{{-- <table id="datatable" class="table data_table" width="100%" cellspacing="0">
	<thead>
	  <tr>
	  	
		<th>DB Primary ID</th> 
		<th>Type</th>
		<th>Category</th>
		<th>Make</th>
		<th>Model</th>
		<th> Varient  </th>	
		<th>Version</th>
		<th>CreatedBy </th>
	
		<th>CreatedOn</th>
		<th>Edit</th>
		<th></th>
		 </tr>
	</thead>
	<tbody>
				@foreach($variants as $varient)
				<tr>
					 <td>{{$varient->id}}</td>
					 <td>{{$varient->type}}</td>
					 <td>{{$varient->category}}</td>
					  <td > {{$varient->make}} </td>
					   <td > {{$varient->model}} </td>
					 <td > {{$varient->name}} </td>
					   <td > {{$varient->version}} </td>
					<td> {{$varient->user_name}} </td>
					

					<td> {{$varient->start_date}} </td>
					<td>
                	<a data-id="{{ $varient->id}}" class="grid_label action-btn edit-icon edit"><i class="fa li_pen"></i></a>
					
				       
				</td>
				<td>

					@if($varient->status == 1)
						<label class="grid_label badge badge-success status">Active</label>
					@elseif($varient->status == 0)
						<label class="grid_label badge badge-warning status">In-Active
						</label>
						
					@endif

					<select style="display:none" id="{{ $varient->id }}" class="active_status form-control">
					<option @if($varient->status == 1) selected="selected" @endif value="1">Active</option>
					<option @if($varient->status == 0) selected="selected" @endif value="0">In-Active</option>
					
					</select>
				</td>
					
				</tr>
				@endforeach
			</tbody>
  </table>
  {{$variants->links()}} --}}

  @include('admin.VehicleMasters_Variants_Pagination')
</div>

@stop

@section('dom_links')
@parent
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmodel-0.1.32/pdfmodel.min.js') }}"></script>

<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/datatables.min.js') }}"></script>
<script type="text/javascript">

   var datatable = null;





     var isFirstIteration = true;        



     var datatable_options = {"pageLength": 10, "columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": [],
        buttons: [     
        {
            extend: 'excel',
            exportOptions: {
                columns: [0,1,2,3,4,5,6]
            },
            footer: true
        },
        ],
        dom: 'lBfrtip'
    };



  $(document).ready(function() {



	datatable = $('#datatable').DataTable(datatable_options);



	$("#datatable_wrapper").find(".dataTables_info").hide();

	

	SearchElement='<label>Search:<input type="search" class="form-control form-control-sm" placeholder="search" /></label>';

    

	$("#datatable_filter").html(SearchElement);

	$("#datatable_paginate").hide();





//Start Pagination using Ajax 

// 10.6.19

// 



	/* 

	Page define the pagination Number



	 */

	var page = 1;



	var current_sort_options;

	var search_input = '';

	

	/* 

	dataTables_Processing - loader in data table



	 */

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

					

				



					url="";

					

					if(search)

					{

						url="VehicleMasters_Varient/global_search?page="+page+"&entrires="+datatableLength+"&search="+search+"";

					

					}else{

					

						url="VehicleMasters_Varient/Pagination?page="+page+"&entrires="+datatableLength+"";



					}

				//	var pageLength=(search)?search_result_length:datatableLength;

					

				

					

				var datatable_options_new = {"pageLength": datatableLength, "columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": sort_options,
        buttons: [     
        {
            extend: 'excel',
            exportOptions: {
                columns: [0,1,2,3,4,5,6]
            },
            footer: true
        },
        ],
        dom: 'lBfrtip'
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

					

						

						// if(search_input){

						// 	datatable.search(search_input).draw();

						// }

		

					}

					});

		}



				



	













					//End Pagination Using Ajax











$('body').on('click', '.excel_export', function(){

        $(".buttons-excel")[0].click(); //trigger the click event

    });



  $('.add').on('click', function(e) {

		e.preventDefault();

		$.get("{{ route('vehiclemasters_varient.create') }}", function(data) {

		  $('.crud_modal .modal-container').html("");

		  $('.crud_modal .modal-container').html(data);

		});

		//$('.crud_modal').find('.modal-dialog').addClass('modal-lg');

		$('.crud_modal').modal('show');

  });

  

  $('.versionadd').on('click', function(e) {

		e.preventDefault();

		$.get("{{ route('vehiclemasters_varient.addversion') }}", function(data) {

		  $('.crud_modal .modal-container').html("");

		  $('.crud_modal .modal-container').html(data);

		});

		//$('.crud_modal').find('.modal-dialog').addClass('modal-lg');

		$('.crud_modal').modal('show');

  });





  	 $('body').on('click', '.edit', function(e) {

        e.preventDefault();

        isFirstIteration = true;

         $.get("{{ url('admin/variants') }}/"+$(this).data('id')+"/edit", function(data) {

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

			var url = "{{ route('vehicle_variant.status') }}";

			var data = change_status(id, obj, status, url, "{{ csrf_token() }}");

		

			// $("#expire_"+id).text(data.expire_on);

		});

  	$('body').on('click', '.delete', function(){

	var id = $(this).data('id');

	var parent = $(this).closest('tr');

	var delete_url = '{{ route('hrm_departments.destroy') }}';

	delete_row(id, parent, delete_url);

   	});



	$('body').on('click', '.multidelete', function() {

		var url = "{{ route('hrm_departments.multidestroy') }}";

		multidelete($(this), url);

	});



	$('body').on('click', '.multiapprove', function() {

		var url = "{{ route('hrm_departments.multiapprove') }}";

		multi_status($(this), $(this).data('value'), url);

	});

	



  });

  </script>
@stop


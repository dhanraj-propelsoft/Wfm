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

@include('includes.inventory')

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

          <h5 class="float-left page-title" style="padding-top: 8px;padding-left: 10px;"><b>Adjustment</b></h5>
			<div style="margin-right: 25px;padding-top: 5px;">
				@permission('adjustment-create')

	          	<a class="btn btn-danger float-right add" style="color: #fff">+ New</a>

	     		@endpermission
	     	</div>

        </div>









	<div class="float-left" style="width: 100%; padding-top: 10px">

	  	<table id="datatable" class="table data_table table-hover" width="100%" cellspacing="0">

	        <thead>

	            <tr>

	            	<th> Adjustment No </th>					

					<th> Item </th>	

					<th> Quantity </th>

					<th> Date </th>	

					

					<th> Action </th>

	            </tr>

	        </thead>

	        <tbody>

	        @foreach($inventory_adjustments as $inventory_adjustment)

	            <tr>

	            	<td>{{ $inventory_adjustment->adjustment_no }}</td>

	            	<td>{{ $inventory_adjustment->item_name }}</td>

	            	<td>{{ $inventory_adjustment->quantity }}</td>

	            	<td class="rearrangedatetext">{{ $inventory_adjustment->date }}</td>	

	            							

					

	        

						<td>

							@permission('adjustment-edit')

							<!-- <a data-id="{{$inventory_adjustment->id}}" class="grid_label action-btn edit-icon edit"><i class="fa li_pen"></i></a> -->

							@endpermission

							@permission('adjustment-delete')

							<a data-id="{{$inventory_adjustment->id}}" class="grid_label action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a>

							@endpermission

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



   var datatable_options = {"order": [[ 3, "desc" ]]};



	$(document).ready(function() {





	datatable = $('#datatable').DataTable(datatable_options);



	



	$('.add').on('click', function(e) {



        e.preventDefault();

        $.get("{{ route('adjustment.create') }}", function(data) {

        	/*adjustment is same blade so use page wise return*/
        		$('.crud_modal .modal-container').attr('data-page',2); 
        	 /*end*/ 

        	$('.crud_modal .modal-container').html("");

        	$('.crud_modal .modal-container').html(data);

        });

        $('.crud_modal').modal('show');

	});





	$('body').on('click', '.edit', function(e) {

        e.preventDefault();

        $.get("{{ url('inventory/adjustment') }}/"+$(this).data('id')+"/edit", function(data) {

        	$('.crud_modal .modal-container').html("");

        	$('.crud_modal .modal-container').html(data);

        });



        $('.crud_modal').modal('show');

	});









		$('body').on('click', '.delete', function(){

		

			var id = $(this).data('id');

			var parent = $(this).closest('tr');

			var delete_url = '{{ route('adjustment.destroy') }}';

			delete_row(id, parent, delete_url, "{{ csrf_token() }}");

	   });



	});

	</script>

@stop
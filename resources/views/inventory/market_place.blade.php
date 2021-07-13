@extends('layouts.master')

@section('head_links') @parent

	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">

	



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

    <h5 class="float-left page-title" style="padding-top: 8px;padding-left: 10px;"><b>Market Place</b></h5>

</div>

	<div class="float-left" style="width: 100%; padding-top: 10px">

	  	<table id="datatable" class="table data_table table-hover" width="100%" cellspacing="0">

	        <thead>

	            <tr>

	            	<th>No </th>					

					<th>Item Name </th>	

					<th> Make </th>

					<th>Category </th>	

					<th>MRP</th>	

					<th>Price</th>	

					<th>Organization</th>	

					<th>City</th>	

					<th>Contact Numbers</th>		

					<th> Notes </th>

	            </tr>

	        </thead>

	        <tbody>

	      			@foreach($items as $key=>$item)

	            <tr>

	            	<td>{{$key+1}}</td>

	            	<td>{{$item->item_name}}</td>

	            	<td>{{$item->make_name}}</td>

	            	<td>{{$item->catgory_name}}</td>

					<td>{{$item->mrp}}</td>

					<td>{{$item->price}}</td>

	            	<td>{{$item->org_name}}</td>

	            	<td>{{$item->city_name}}</td>

	            	<td>{{$item->mobile_no}}</td>     

					<td><a data-value="{{$item->notes}}" class="grid_label action-btn notes" data-toggle="tooltip" data-placement="top" title="{{$item->notes}}"><i class="fa fa-sticky-note" aria-hidden="true"></i></td>

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

	

	});

	 $('body').on('click', '.notes', function(){

	 	var notes=$(this).data('value');

	 	var data = "<div class='col-md-12'><center><textarea class='form-control content' rows='5' disabled='disabled'></textarea></center></div>";

	 	$('.delete_modal_ajax').find('.modal-title').text("Item Notes");

        $('.delete_modal_ajax').find('.modal-body').html(data);

         $('.delete_modal_ajax').find('.modal-body').find('.content').html("");

        $('.delete_modal_ajax').find('.modal-body').find('.content').html(notes);

       

         $('.delete_modal_ajax').find('.modal-footer').find('.default').text("Close"); 

        $('.delete_modal_ajax').find('.modal-footer').find('.btn-danger').hide();    

		$('.delete_modal_ajax').modal('show');	



	 });



	</script>

@stop
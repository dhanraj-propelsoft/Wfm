@extends('layouts.master')

@section('head_links') @parent

	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">

	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/dropzone/dropzone.css') }}">
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
        
        input.form-control
        {
            height: 25px;
            
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

  	<h5 class="float-left page-title" style="padding-top: 8px;padding-left: 10px;"><b>Low Stock Report</b> </h5>

	@permission('item-create')

		<!-- <a class="btn btn-danger float-right add" style="color: #fff">+ New</a> -->

	@endpermission

</div>

<div class="btn-group float-right">

    <button onclick="select_row(event)" class="btn btn-success make_purchase">Purchase Order</button>

    <!-- <a onclick="event.preventDefault();

    document.getElementById('purchase_order').submit();" type="purchase_order" class="make_transaction btn btn-success" style="color: white;">Purchase Order</a>  --> 

</div>



<div class="float-left table_container" style="width: 100%; padding-top: 10px;">

		<!-- <div class="batch_container">

		<div class="batch_action"><i class="fa icon-arrows-slide-down1 arrow"></i>Batch Actions <i class="fa fa-caret-down "></i>

		</div>

		<ul class="batch_list">

			<li>

			<a onclick="event.preventDefault();

				 document.getElementById('purchase_order').submit();" type="purchase_order" class="make_transaction">Purchase Order</a>			

			</li>

			<li><a data-value="1" class="multiapprove">Make Active</a></li>

			<li><a data-value="0" class="multiapprove">Make In-Active</a></li> 

		</ul>

		</div> -->

        <form id="purchase_order" action="{{ route('add_to_lowstock_account') }}" method="POST">

						{{ csrf_field() }}

  			<table id="datatable" class="table data_table" width="100%" cellspacing="0">

        <thead>

            <tr>

            	<!-- <th> {{ Form::checkbox('check_all', 'check_all', null, ['id' => 'check_all'] ) }} <label for="check_all"><span></span></label></th> -->

                <th></th>

				<th> Name </th>	

				<th>Last Purchase Price </th>
                <th>Last Batch Number</th>

				<th> MOQ </th>

				<th> In Stock </th>							

				<th> Re-Order Point </th>

            </tr>

        </thead>

        <tbody>

        @foreach($inventory_items as $inventory_item)
            <tr>
            	<td width="1">{{ Form::checkbox('inventory_item[]',$inventory_item->id, null, ['id' => $inventory_item->id, 'class' => 'check']) }}<label for="{{$inventory_item->id}}"><span></span></label></td>
            	<td>
            		<image style="border:2px solid #ccc; border-radius: 3px;" width="50" height="50" src="{{ $inventory_item->image }}" />
            		<span>{{ $inventory_item->name }}</td></span>
            	<td><a href="#" class="batch" data-id="{{$inventory_item->id}}">{{ $inventory_item->purchase_price }}</a></td>
                <td>{{ $inventory_item->batch_number }}</td>
            	<td>{{ $inventory_item->minimum_order_quantity }}</td>
            	<td>@if($inventory_item->in_stock != null)
            			{{$inventory_item->in_stock}} {{$inventory_item->unit}}
            		@endif
            	</td>							
				<td>
					{{$inventory_item->low_stock}}
				</td>
            </tr>
        @endforeach

        </tbody>

    </table>

        </form>

</div>

					

@stop



@section('dom_links')

@parent

<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmake-0.1.32/pdfmake.min.js') }}"></script>

<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmake-0.1.32/vfs_fonts.js') }}"></script>

<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/datatables.min.js') }}"></script>

<script type="text/javascript" src="{{ URL::asset('assets/plugins/dropzone/dropzone.js') }}"></script> 

   <script type="text/javascript">

    var datatable = null;

    var clone = null;

    var tbody = $('.data_table tbody');



    $('.make_purchase').hide();



    $($('input[type = checkbox]')).each(function() {

        $(this).on('change', function() {

            if($('input[type = checkbox]:checked').length > 0) {

                $('.make_purchase').show();

            } else{

                $('.make_purchase').hide();

            }

        });

    });





    function select_row(event) {

        //alert($('input[type=checkbox]:checked').length);

        if($('input[type = checkbox]:checked').length > 0)

        {

            event.preventDefault();

            document.getElementById('purchase_order').submit();

        } else{

            $('.make_purchase').hide();

        }

    }



	function adjust_item(id, in_stock) {

	 	$('.edit[data-id="' + id + '"]').closest('tr').remove();

	 	tbody.prepend(clone);

	 	clone.find('td:nth-child(3)').text(in_stock);

        datatable = $('#datatable').DataTable({"columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": [[1, "asc"]], "stateSave": true});

    }



	$(document).ready(function() {



	datatable = $('#datatable').DataTable({"columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": [[1, "asc"]], "stateSave": true});


});
 $('body').on('click', '.batch', function(e){
            e.preventDefault();
            $.get("{{url('inventory/items_batch')}}/"+$(this).data('id')+"/low_stock",function(data){
                $('.crud_modal .modal-container').html("");
                $('.crud_modal .modal-container').html(data);
            });
             $('.crud_modal').find('.modal-dialog').addClass('modal-lg');
            $('.crud_modal').modal('show'); 
        
       });

</script>

@stop
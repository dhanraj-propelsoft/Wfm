@extends('layouts.master')

@section('head_links') @parent

	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">

	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/dropzone/dropzone.css') }}">

@stop

@if($item_group == "item-group")

	@if(Session::get('module_name') == "inventory")

		@include('includes.inventory')

	@elseif(Session::get('module_name') == "trade")

		@include('includes.trade')

	@else

		@include('includes.inventory')

	@endif

@elseif($item_group == "work-group")

@include('includes.workshop')

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

          	<h5 class="float-left page-title" style="padding-top: 8px;padding-left: 10px;"><b>@if($item_group == "item-group") Item Groups @elseif($item_group == "work-group") Work Groups @endif</b></h5>
          	<div style="margin-right: 25px;padding-top: 5px;">		

          		<a class="btn btn-danger float-right add" style="color: #fff">+ New</a>
          	</div>

     

        </div>









<div class="float-left table_container" style="width: 100%; padding-top: 10px;">

		<div class="batch_container">

		<div class="batch_action"><i class="fa icon-arrows-slide-down1 arrow"></i>Batch Actions <i class="fa fa-caret-down "></i>

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

				<th> Item Group </th>	

				

										

				<th> Price </th>				

				<th> Status </th>

				<th> Action </th>

            </tr>

        </thead>

        <tbody>

        @foreach($inventory_items as $inventory_item)

            <tr>

            	<td width="1">{{ Form::checkbox('inventory_item',$inventory_item->id, null, ['id' => $inventory_item->id, 'class' => 'item_check']) }}<label for="{{$inventory_item->id}}"><span></span></label></td>

            	

            	<td valign="middle">

            	<image style="border:2px solid #ccc; border-radius: 3px;" width="50" height="50" src="{{ $inventory_item->image }}" />

            	<span style="vertical-align: middle;">{{ $inventory_item->name }}

			 <?php

            	$item_name = explode('`', $inventory_item->item_name);

            	$item_quantity = explode('`', $inventory_item->item_quantity);



            	for($i=0; $i<count($item_name); $i++){



            		echo "<ul class = 'inner_table'><li><span>".$item_name[$i]."</span>";

            		if(!empty($item_quantity[$i])) echo "<span>".$item_quantity[$i]."</span>";

		            echo "</li></ul>";

				

				     }

				  ?> 

					

            	</td></span>

            	

            								

				<td><?php $sale_price = App\Custom::get_least_closest_date(json_decode($inventory_item->sale_price_data, true));

				if($inventory_item->include_tax != null) {

					echo App\Custom::two_decimal($sale_price['price']*(($inventory_item->tax/100) + 1)) ;

				}

				else {

					echo $sale_price['price'];

				}



				echo "<span style='color:#aaa'> From ".Carbon\Carbon::parse($sale_price['date'])->format('jS \\of M Y')."</span>" ;



				 ?></td>

            		<td>

						@if($inventory_item->status == 1)

							<label class="grid_label badge badge-success status">Active</label>

						@elseif($inventory_item->status == 0)

							<label class="grid_label badge badge-warning status">In-Active</label>

						@endif

					

						<select style="display:none" id="{{ $inventory_item->id }}" class="active_status form-control">

							<option @if($inventory_item->status == 1) selected="selected" @endif value="1">Active</option>

							<option @if($inventory_item->status == 0) selected="selected" @endif value="0">In-Active</option>

						</select>					

					</td>

					<td>									

						<a data-id="{{$inventory_item->id}}" class="grid_label action-btn edit-icon edit"><i class="fa li_pen"></i></a>					

					

						<a data-id="{{$inventory_item->id}}" class="grid_label action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a>				

				

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

<script type="text/javascript" src="{{ URL::asset('assets/plugins/dropzone/dropzone.js') }}"></script> 

   <script type="text/javascript">

   var datatable = null;

   var clone = null;

   var tbody = $('.data_table tbody');



   var datatable_options = {"columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": [[1, "asc"]], "stateSave": true};





	function adjust_item(id, in_stock) {



	 	$('.edit[data-id="' + id + '"]').closest('tr').remove();

	 	tbody.prepend(clone);

	 	clone.find('td:nth-child(3)').text(in_stock);

        datatable = $('#datatable').DataTable(datatable_options);



    }



	$(document).ready(function() {







	datatable = $('#datatable').DataTable(datatable_options);



	$('.add').on('click', function(e) {



        e.preventDefault();

        $.get("{{ route('item_group.create') }}", function(data) {



        	$('.crud_modal .modal-container').html("");

        	$('.crud_modal .modal-container').html(data);

        });

        $('.crud_modal').find('.modal-dialog').addClass('modal-lg');

        $('.crud_modal').modal('show');





	});



	$('body').on('click', '.create', function(e) {

		

		var obj = $(this);



        e.preventDefault();

        $.get("{{ route('adjustment.create') }}", function(data) {



        	$('.crud_modal .modal-container').html("");

        	$('.crud_modal .modal-container').html(data);

        	$('.crud_modal .modal-container').find('.modal-title').text('Adjust Quantity');

        	$('.crud_modal .modal-container').find('select[name=item_id]').closest('.form-group').hide();

        	$('.crud_modal .modal-container').find('select[name=item_id]').val(obj.data('id'));

        	$('.crud_modal .modal-container').find('input[name=in_stock]').val(obj.data('stock'));

        	$('.crud_modal .modal-container').find('select[name=item_id]').trigger('change');

        	clone = obj.closest('tr').clone();



        });



        $('.crud_modal').modal('show');

	});







	$('body').on('click', '.edit', function(e) {

        e.preventDefault();

        $.get("{{ url('inventory/item-group') }}/"+$(this).data('id')+"/edit", function(data) {

        	$('.crud_modal .modal-container').html("");

        	$('.crud_modal .modal-container').html(data);

        	$('.crud_modal .modal-container').find('input[name=type]').closest('.form-group').hide();

        });

        $('.crud_modal').find('.modal-dialog').addClass('modal-lg');

        $('.crud_modal').modal('show');

	});





	$('body').on('click', '.status', function(e) {

		$(this).hide();

		$(this).parent().find('select').css('display', 'block');

	});



	$('body').on('click', '.multidelete', function() {

	var url = "{{ route('item_group.multidestroy') }}";

	multidelete($(this), url, "{{ csrf_token() }}");

	});



	$('body').on('click', '.multiapprove', function() {

		var url = "{{ route('item_group.multiapprove') }}";

		multi_status($(this), $(this).data('value'), url, "{{ csrf_token() }}");

	});



	$('body').on('change', '.active_status', function(e) {

			var status = $(this).val();

			var id = $(this).attr('id');

			var obj = $(this);

			var url = "{{ route('item.status') }}";

			change_status(id, obj, status, url, "{{ csrf_token() }}");

		});



		$('body').on('click', '.delete', function(){

		

			var id = $(this).data('id');

			var parent = $(this).closest('tr');

			var delete_url = '{{ route('item_group.destroy') }}';

			delete_row(id, parent, delete_url, "{{ csrf_token() }}");

	   });









	});

	</script>

@stop
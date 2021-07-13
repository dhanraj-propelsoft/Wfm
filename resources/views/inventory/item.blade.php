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

@if($item == "items")

@if(Session::get('module_name') == "inventory")
	@include('includes.inventory')
@elseif(Session::get('module_name') == "trade")
	@include('includes.trade')
@elseif(Session::get('module_name') == "trade_wms")
	@include('includes.trade_wms')
@elseif(Session::get('module_name') == "fuel_station")
	@include('includes.fuel_station')
@else
	@include('includes.inventory')
@endif

@elseif($item == "works")
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
          <h5 class="float-left page-title" style="padding-top: 8px;padding-left: 10px;"><b>@if($item == "items") Item @elseif($item == "works") Work @endif</b></h5>
			@permission('item-create')
		<div style="margin-right: 25px;padding-top: 5px;">
          <!-- 	<a class="btn btn-danger float-right add" style="color: #fff">+ New</a> -->
          	<a class="btn btn-danger float-right add_goods" data-name="service" style="color: #fff">+ Add service</a>
           	<a class="btn btn-danger float-right add_goods" data-name="goods" style="color: #fff">+ Add Goods</a>
         </div>
     		@endpermission
        </div>




<div class="float-left table_container" style="width: 100%; padding-top: 10px;">
		<div class="batch_container">
		<div class="batch_action"><i class="fa icon-arrows-slide-down1 arrow"></i>Batch Actions<i class="fa fa-caret-down "></i>
		</div>
		<ul class="batch_list">
			<li><a class="multidelete">Delete</a></li>
			<li><a data-value="1" class="multiapprove">Make Active</a></li>
			<li><a data-value="0" class="multiapprove">Make In-Active</a></li>
		</ul>
		</div>
		<div class="float-left table_container" style="width: 100%; padding-top: 10px;" id="table_data">
	
				@include('inventory.item_pagination')
	
		</div>
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


	function image_call_back(image, id) {
    	$('body').find('.edit[data-id="' + id + '"]').closest('tr').find('img').attr('src', image);
	}

	function adjust_item(id, in_stock) {
	 	$('.edit[data-id="' + id + '"]').closest('tr').remove();
	 	tbody.prepend(clone);
	 	clone.find('td:nth-child(3)').text(in_stock);
        datatable = $('#datatable').DataTable(datatable_options);

    }

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
$('.dataTables_processing').hide();

$("#datatable_wrapper").find(".dataTables_info").hide();
	/* *
	@ Click event
		For pagination
	 */

	 $('body').on( 'keyup change','label>input[type="search"]', function (event) {
		
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
		
	//	console.log($("#datatable_wrapper").find(".dataTables_info").length);
		
	

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
				
				//console.log(datatableLength);

				url="";
				params="items";
				if(search)
				{
					url=params+"/global_search?page="+page+"&entrires="+datatableLength+"&search="+search+"";
				
				}else{
				
					url=params+"/pagination?page="+page+"&entrires="+datatableLength+"";

				}
			//	var pageLength=(search)?search_result_length:datatableLength;
				
				
				
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


	 var module_name="<?php echo $module_name ?>";
	if(module_name == "fuel_station")
	{
		$('.add').on('click', function(e) {

        e.preventDefault();
        $.get("{{ route('fsm_item.create') }}", function(data) {

        	$('.crud_modal .modal-container').html("");
        	$('.crud_modal .modal-container').html(data);
        });
        $('.crud_modal').find('.modal-dialog').addClass('modal-lg');
        $('.crud_modal').modal('show');


		});
	}
	else
	{
		$('.add').on('click', function(e) {
	        e.preventDefault();
	        $.get("{{ route('item.create') }}", function(data) {
	        	$('.crud_modal .modal-container').html("");
	        	$('.crud_modal .modal-container').html(data);
	        });
	        $('.crud_modal').find('.modal-dialog').addClass('modal-lg');
	        $('.crud_modal').modal('show');


		});
		$('.add_goods').on('click', function(e) {
	        e.preventDefault();
	        var name = $(this).attr('data-name');
	        //console.log(name);
	        $.get("{{ url('inventory/items/create') }}/"+$(this).attr('data-name'), function(data) {
	        	$('.crud_modal .modal-container').html("");
	        	$('.crud_modal .modal-container').html(data);
	        });
	        $('.crud_modal').find('.modal-dialog').addClass('modal-lg');
	        $('.crud_modal').modal('show');
	    
		});
    }

	$('body').on('click', '.create', function(e) {
		
		var obj = $(this);

        e.preventDefault();
        $.get("{{ route('adjustment.create') }}", function(data) {

        	/*adjustment is same blade so use page wise return*/
        		$('.crud_modal .modal-container').attr('data-page',1); 
        	 /*end*/ 

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

	$(document).delegate(".edit", "click", function(event){
		event.preventDefault();
	    var obj = $(this);
        $.get("{{ url('inventory/items') }}/"+$(this).data('id')+"/edit", function(data) {
        	$('.crud_modal .modal-container').html("");
        	$('.crud_modal .modal-container').html(data);
        	$('.crud_modal .modal-container').find('input[name=type]').closest('.form-group').hide();
        });
        $('.crud_modal').find('.modal-dialog').addClass('modal-lg');
        $('.crud_modal').modal('show'); 
	});

	// $('body').on('click', '.edit', function(e) {
 //        e.preventDefault();
 //        var obj = $(this);
 //        $.get("{{ url('inventory/items') }}/"+$(this).data('id')+"/edit", function(data) {
 //        	$('.crud_modal .modal-container').html("");
 //        	$('.crud_modal .modal-container').html(data);
 //        	$('.crud_modal .modal-container').find('input[name=type]').closest('.form-group').hide();
 //        });
 //        $('.crud_modal').find('.modal-dialog').addClass('modal-lg');
 //        $('.crud_modal').modal('show');
	// });


	$('body').on('click', '.status', function(e) {
		$(this).hide();
		$(this).parent().find('select').css('display', 'block');
	});

	$('body').on('click', '.multidelete', function() {
	var url = "{{ route('item.multidestroy') }}";
	multidelete($(this), url, "{{ csrf_token() }}");
	});

	$('body').on('click', '.multiapprove', function() {
		var url = "{{ route('item.multiapprove') }}";
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
			var delete_url = '{{ route('item.destroy') }}';
			delete_row(id, parent, delete_url, "{{ csrf_token() }}");
	   });
	   $('body').on('click', '.batch', function(e){
			e.preventDefault();
			$.get("{{url('inventory/items_batch')}}/"+$(this).data('id')+"/item",function(data){
				$('.crud_modal .modal-container').html("");
				$('.crud_modal .modal-container').html(data);
			});
			 $('.crud_modal').find('.modal-dialog').addClass('modal-lg');
       		$('.crud_modal').modal('show'); 
		
	   }); 

	});
	</script>
@stop
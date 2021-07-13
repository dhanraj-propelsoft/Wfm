@extends('layouts.master')
@section('head_links') @parent
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
@stop
@include('includes.settings')
@section('content')

<div class="alert alert-success"> {{ Session::get('flash_message') }} </div>
@if($errors->any())
<div class="alert alert-danger"> @foreach($errors->all() as $error)
  <p>{{ $error }}</p>
  @endforeach </div>
@endif

<div class="fill header">
  <h4 class="float-left page-title">Voucher List</h4>
  <div class="float-left form-inline" style="margin-left:650px;margin-top: 10px;">
			
		<input id="to_restart_all_voucher" type="checkbox" name="to_restart_all_voucher" value="0" />
										
		<label for="to_restart_all_voucher" ><span></span>To restart all vouchers</label>	
			
		
	</div>
   @permission('voucher-master-create') 
  	<a class="btn btn-danger float-right add" style="color: #fff">+ New</a> 
  @endpermission
</div>

<div class="float-left" style="width: 100%; padding-top: 10px">
  <table id="datatable" class="table data_table" width="100%" cellspacing="0">
	<thead>
		<tr>
			<th> Voucher Name </th>
			<th> Voucher Type </th>
			<th> Voucher Code </th>
			<th> Action </th>
		 </tr>
	</thead>
	<tbody>
	
	@foreach($account_voucher_masters as $account_voucher_master)
	<tr>
		<td>{{ $account_voucher_master->display_name }}</td>
		<td>{{ $account_voucher_master->voucher_type_name }}</td>
		<td>{{ $account_voucher_master->code }}</td>
		<td>           
			@permission('voucher-master-edit')
			  <a data-id="{{ $account_voucher_master->id }}" class="grid_label action-btn edit-icon edit">
			  <i class="fa li_pen"></i></a>
			@endpermission

			@if($account_voucher_master->delete_status == 1)
				@permission('voucher-master-delete')
				  <a data-id="{{ $account_voucher_master->id }}" class="grid_label action-btn delete-icon delete">
				  <i class="fa fa-trash-o"></i></a> 
				@endpermission
			@endif
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

   var datatable_options = {};

  $(document).ready(function() {

  datatable = $('#datatable').DataTable();

  $('.add').on('click', function(e) {
		e.preventDefault();
		$.get("{{ route('voucher_list.create') }}", function(data) {
		  $('.crud_modal .modal-container').html("");
		  $('.crud_modal .modal-container').html(data);
		});
		$('.crud_modal').find('.modal-dialog').addClass('modal-lg');
		$('.crud_modal').modal('show');
  });
  
   $('#to_restart_all_voucher').on('change',function(){
   	//alert();
   		if($(this).prop("checked") == true)
		{
			
		 $('.voucher_restart_modal').modal('show');
		 $('.confirm_to_continue').on('click',function(){
		 	//alert();
		 	$('.voucher_restart_modal').find('#content').text('');
		 	$('.voucher_restart_modal').find('#content').text("This will restart for all the voucher..For Ex: The new invoice Gen number start from 1. IN / 20 / 1...Please accept for the seplatro or system admin");
		 	$('.confirm_to_continue').css('display','none');
		 	$('.cancel_btn').css('display','none');
		 	
		 	
		 	$(".close_btn").removeAttr("style");

		 	$(".confirm_to_restart").removeAttr("style");
		 		$('.confirm_to_restart').on('click',function(){
		 			//alert();
		 			$.ajax({
		 				url : "{{ route('restart_all_vouchers') }}",
		 				type : 'POST',
		 				data :
		 				{
		 					_token : "{{ csrf_token() }}",
		 				},
		 				success:function(data)
		 				{
		 					//alert();
					 	$('.voucher_restart_modal').find('#content').text('');
					 	$('.voucher_restart_modal').find('#content').text(data.message);
		 				$('.confirm_to_restart').css('display','none');


		 				},
		 				error:function()
		 				{

		 				}

		 			});

		 		});


		 });
				
		}

   });

  $('body').on('click', '.edit', function(e) {
		e.preventDefault();
		$.get("{{ url('settings-voucher') }}/"+$(this).data('id')+"/edit", function(data) {
		  $('.crud_modal .modal-container').html("");
		  $('.crud_modal .modal-container').html(data);
		});
		$('.crud_modal').find('.modal-dialog').addClass('modal-lg');
		$('.crud_modal').modal('show');
  });

  $('body').on('click', '.delete', function(){
	var id = $(this).data('id');
	var parent = $(this).closest('tr');
	var delete_url = '{{ route('voucher_list.destroy') }}';
	delete_row(id, parent, delete_url, "{{ csrf_token() }}");
   });

  });
  </script>
@stop
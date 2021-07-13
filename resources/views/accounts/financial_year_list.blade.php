@extends('layouts.master')
@section('head_links') 
@parent
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
@stop
@include('includes.accounts')
@section('content')
<div class="alert alert-success"> {{ Session::get('flash_message') }} </div>
@if($errors->any())
<div class="alert alert-danger"> 
@foreach($errors->all() as $error)
  <p>{{ $error }}</p>
@endforeach </div>
@endif
<div class="fill header">
  <h4 class="float-left page-title">Financial Year</h4>
  	<a class="btn btn-danger float-right add" style="color: #fff">+ New</a>
</div>

<div class="float-left" style="width: 100%; padding-top: 10px">
  <table id="datatable" class="table data_table table-hover" width="100%" cellspacing="0">
    <thead>
      	<tr>
	        <th>Name </th>
	        <th>Book-Start</th>
	        <th>Book End</th>
	        <th>FY-Start</th>
	        <th>FY-End</th>
	        <th>Vouchers-Format</th>
	        <th>Last accessed</th>
	        <th>Current</th>
	        <th>Actions</th>	
	     </tr>
    </thead>
    <tbody>
    @foreach($financial_years as $financial_year)
		<tr>
			<td>{{$financial_year->name}}</td>
			<td>{{$financial_year->books_start_year}}</td>
			<td>{{$financial_year->books_end_year}}</td>
			<td>{{$financial_year->financial_start_year}}</td>
			<td>{{$financial_year->financial_end_year}}</td>
			<td>{{$financial_year->voucher_year_format}}</td>
			<td>{{$financial_year->updated_at}}</td>
			<td>
			@if($financial_year->status == '0')
			  <label class="grid_label badge badge-warning status">Old Year</label>
			@else
			   <label class="grid_label badge badge-success status">Current Year</label>
			@endif
			<select style="display:none" id="{{ $financial_year->id }}" class="active_status form-control">
					<option @if($financial_year->status == 1) selected="selected" @endif value="1">Current Year</option>
					<option @if($financial_year->status == 0) selected="selected"@endif value="0">Old Year</option>
			</select>
			</td>		
			<td><a data-id="{{ $financial_year->id }}" class="grid_label action-btn edit-icon edit"><i class="fa li_pen"></i></a>
			<!-- <a data-id="{{ $financial_year->id }}" class="grid_label action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a> --></td>
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

   var datatable_options = {"stateSave": true};

  $(document).ready(function() {

  datatable = $('#datatable').DataTable(datatable_options);

  $('.add').on('click', function(e) {
        e.preventDefault();
        $.get("{{ route('financial_year.create') }}", function(data) {
          $('.crud_modal .modal-container').html("");
          $('.crud_modal .modal-container').html(data);
        });
        $('.crud_modal').find('.modal-dialog').addClass('modal-md');
        $('.crud_modal').modal('show');
  });

  $('body').on('click', '.edit', function(e) {
        e.preventDefault();
        $.get("{{ route('financial_year.create') }}/"+$(this).data('id'), function(data) {
          $('.crud_modal .modal-container').html("");
          $('.crud_modal .modal-container').html(data);
        });
        $('.crud_modal').find('.modal-dialog').addClass('modal-md');
        $('.crud_modal').modal('show');
  });

  
  $('body').on('click', '.status', function(e) {
		$(this).hide();
		$(this).parent().find('select').css('display', 'block').prop('selected', false);
  	});
   	$('body').on('change', '.active_status', function(e) {
			var status = $(this).val();
			var id = $(this).attr('id');
			var obj = $(this);
			var url = "{{ route('change_current_year') }}";
			if(status == '1'){
				var content = "Click Cancel to stop this change <br>Click Change to continue.<br>In both the cases please refresh the page.";
				$('.delete_modal_ajax').find('.modal-title').text("Conform Change");
	            $('.delete_modal_ajax').find('.modal-body').html(content);
	            $('.delete_modal_ajax').find('.modal-footer').find('.default').text("Cancel");
	            $('.delete_modal_ajax').find('.modal-footer').find('.btn-danger').text("Change");
				$('.delete_modal_ajax').modal('show');
				$('.delete_modal_ajax_btn').off().on('click', function() {
				var data = change_status(id, obj, status, url, "{{ csrf_token() }}");
				// window.location.href = "{{ route('financialyear_list.index') }}";

				$('.delete_modal_ajax').modal('hide');
		});
			}else{
				var data = change_status(id, obj, status, url, "{{ csrf_token() }}");
				$('.delete_modal_ajax').modal('hide');	
			}

		});
   	

  });
  </script>
@stop
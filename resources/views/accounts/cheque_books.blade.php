@extends('layouts.master')
@section('head_links') @parent
@if(app()->environment() == "production")
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.16/css/jquery.dataTables.min.css">
@elseif(app()->environment() == "local")
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
@endif
@stop
@include('includes.accounts')
@section('content') 

<!-- Modal Starts -->

<div class="modal fade bs-modal-lg group_modal" tabindex="-1" role="basic" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-container"> </div>
    </div>
    <!-- /.modal-content --> 
  </div>
  <!-- /.modal-dialog --> 
</div>

<!-- Modal Ends -->

<div class="alert alert-success"> {{ Session::get('flash_message') }} </div>
@if($errors->any())
<div class="alert alert-danger"> @foreach($errors->all() as $error)
  <p>{{ $error }}</p>
  @endforeach </div>
@endif
<div class="fill header">
  	<h4 class="float-left page-title">Cheque Book</h4>
  	@permission('cheque-book-create') 
 	 	<!-- <a class="btn btn-danger float-right add_group" style="color: #fff">+ New</a>  -->
	@endpermission 
</div>

<div class="float-left" style="width: 100%; padding-top: 10px">
  <table id="datatable" class="table group_table table-hover" width="100%" cellspacing="0">
    <thead>
      <tr>
        <th> Bank </th>
        <th> A/C No. </th>
        <th> Book No. </th>
        <th> Cheque No. From </th>
        <th> Cheque No. To </th>
        <th> Next Book Warning </th>
        <th> Action </th>
      </tr>
    </thead>
    <tbody>
    @foreach($chequebooks as $chequebook)
    <tr>
      	<td>{{ $chequebook->bank_name }}  &amp; <br> {{ $chequebook->bank_branch }} </td>
      	<td>{{ $chequebook->account_no }} &amp; <br> {{ $chequebook->account_type }} </td>
      	<td>{{ $chequebook->book_no }}</td>
     	<td>{{ $chequebook->cheque_no_from }}</td>
      	<td>{{ $chequebook->cheque_no_to }}</td>
      	<td>{{ $chequebook->next_book_warning }}</td>
      	<td> 
      	@permission('cheque-book-edit') 
      		<a class="grid_label badge badge-info continue_edit_group" data-id="{{$chequebook->id}}"> Continue Next Book </a> 
      		<a data-id="{{$chequebook->id}}" class="grid_label action-btn edit-icon edit_group"><i class="fa li_pen"></i></a> 
      	@endpermission
        
        @permission('cheque-book-delete') 
        	<a data-id="{{$chequebook->id}}" class="grid_label action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a>
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
@if(app()->environment() == "production")
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script> 
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script> 
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.16/js/jquery.dataTables.min.js"></script> 
@elseif(app()->environment() == "local")
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmake-0.1.32/pdfmake.min.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmake-0.1.32/vfs_fonts.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/datatables.min.js') }}"></script> 
@endif
<script type="text/javascript">
   var datatable = null;

   var datatable_options = {"stateSave": true};

$(document).ready(function() {

	datatable = $('#datatable').DataTable(datatable_options);


	$('body').on('click', '.edit_group', function(e) {
        e.preventDefault();
        $.get("{{ url('accounts/cheque-book') }}/"+$(this).data('id')+"/edit", function(data) {
        	$('.group_modal .modal-container').html("");
        	$('.group_modal .modal-container').html(data);
        });
        $('.group_modal').modal('show');
	});

	$('body').on('click', '.continue_edit_group', function(e) {
        e.preventDefault();
        $.get("{{ url('accounts/cheque-book/continue') }}/"+$(this).data('id')+"/edit", function(data) {
        	$('.group_modal .modal-container').html("");
        	$('.group_modal .modal-container').html(data);
        });
        $('.group_modal').modal('show');
	});


	$('body').on('click', '.status', function(e) {
		$(this).hide();
		$(this).parent().find('select').css('display', 'block');
	});

	$('body').on('change', '.active_status', function(e) {
		var status = $(this).val();
		var id = $(this).attr('id');
		var obj = $(this);
		var url = "{{-- route('change_status') --}}";
		change_status(id, obj, status, url, "{{ csrf_token() }}");
	});

		$('body').on('click', '.delete', function(){
			var id = $(this).data('id');
			var parent = $(this).closest('tr');
			var delete_url = '';
			delete_row(id, parent, delete_url, "{{ csrf_token() }}");
	   });

	});
	</script> 
@stop
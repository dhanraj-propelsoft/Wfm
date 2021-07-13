@extends('layouts.master')
@section('head_links') @parent
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
@stop
@include('includes.settings')
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
  <h4 class="float-left page-title">Template</h4>
  @permission('department-create')
	<a class="btn btn-danger float-right add" style="color: #fff">+ New</a>
  @endpermission
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
		<th> Name </th> 
		<th> Status </th>
		<th> Action </th>
	  </tr>
	</thead>
	<tbody>
	  @foreach($templates as $template)
		<tr>
			<td width="1">{{ Form::checkbox('template',$template->id, null, ['id' => $template->id, 'class' => 'item_check']) }}<label for="{{$template->id}}"><span></span></label></td>
		  <td>{{ $template->name }}</td>
		  <td>
			@if($template->status == '1')
			  <label class="grid_label badge badge-success status">Active</label>
			@elseif($template->status == '0')
			  <label class="grid_label badge badge-warning status">In-Active</label>
			@endif

			@permission('department-edit')
			  <select style="display:none" id="{{ $template->id }}" class="active_status form-control">
				<option @if($template->status == 1) selected="selected" @endif value="1">Active</option>
				<option @if($template->status == 0) selected="selected" @endif value="0">In-Active</option>
			  </select>
			@endpermission
		  </td>
		  <td>
		  <a data-id="{{ $template->id }}" class="grid_label action-btn print-icon payslip"><i class="fa icon-basic-printer"></i></a> 
			
			  <a href="{{ route('print.edit', [$template->id]) }}" class="grid_label action-btn edit-icon"><i class="fa li_pen"></i></a>
			

			@permission('department-delete')
			  <a data-id="{{ $template->id }}" class="grid_label action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a> 
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

var datatable_options = {"stateSave": true};

	$(document).ready(function() {

		datatable = $('#datatable').DataTable(datatable_options);

		$('.add').on('click', function(e) {
	        e.preventDefault();
	        $.get("{{ route('print.create') }}", function(data) {
	        	$('.crud_modal .modal-container').html("");
	        	$('.crud_modal .modal-container').html(data);
	        });
	        $('.crud_modal').modal('show');
		});

		$('body').on('click', '.payslip', function(e) {
			e.preventDefault(); 
			var id = $(this).data('id');
			$('.loader_wall_onspot').show();
			$('.payslip_content').find('.modal-footer').show();
			$('.payslip_content').animate({ height: $(window).height() + 'px' }, 400, function() {

				$.get("{{ url('settings/print') }}/"+id, function(data) {
					var container = $('.payslip_content').find("#print");
					container.html("");
					container.html(data);

					$('.loader_wall_onspot').hide();
				});
		
			});
				
		});

		$('body').on('click', '.print', function() {
			printDiv();
		});

		$('body').on('click', '.close_full_modal', function() {
			$('.payslip_content').animate({ top:-$('.payslip_content').outerHeight() + 'px' }, 300, function() { 
				$('.payslip_content').find("#print").html("");
				$('.payslip_content').find('.modal-footer').hide();
				$('.payslip_content').removeAttr('style');
				$('.payslip_content').animate({top: '0px'}); 
			});
		});

		$('body').on('click', '.delete', function(){
			var id = $(this).data('id');
			var parent = $(this).closest('tr');
			var delete_url = '{{ route('print.destroy') }}';
			delete_row(id, parent, delete_url, "{{ csrf_token() }}");
	   });
	});

	function printDiv() 
	{
	  var divToPrint=document.getElementById('print');
	  var newWin=window.open('','Propel');

	  newWin.document.open();
	  newWin.document.write(`<html>
		
<style>


body {
	font-family: 'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;font-size: 13px;color: #3e4855;line-height: 1.42857143;
}

table, table tr, table tr td {
	border:0;
	padding:0;
	border-spacing: 0;
	border-collapse:collapse;
	border: none;
	font-size: 13px;
}

.border {
	border:1px solid #000 !important
}

.border_td td {
	border:1px solid #000 !important
}

.border_bottom {
	border-bottom:1px solid #000 !important
}

.padding {
	padding:5px 15px;
}

.table_padding td {
	padding:1px 15px;
}

</style>
	  	<body onload="window.print()">`+divToPrint.innerHTML+`</body></html>`);
	  newWin.document.close();

	  setTimeout(function(){newWin.close();},10);

	}
</script> 
@stop
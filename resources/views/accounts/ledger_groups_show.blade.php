@extends('layouts.master')
@section('head_links') @parent
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
@stop
@include('includes.accounts')
@section('content')

@foreach($ledger_groups as $ledger_group)
	<div class="fill header">
		<h4 class="float-left page-title">{{ $ledger_group->display_name}}</h4>
	</div>
	<br> <br><br></br>

	<div class="row">
		{!! Form::label('ledger_name', 'Group Name :', array('class' => 'control-label col-md-2')) !!}
			{{ $ledger_group->ledger_group_name }}
	</div>

	<div class="row">
		{!! Form::label('status', 'Status :', array('class' => 'control-label col-md-2')) !!}

		@if($ledger_group->approval_status == 1)
			<label class="grid_label badge badge-success status">Approved</label>
		@elseif($ledger_group->approval_status == 0)
			<label class="grid_label badge badge-warning status">Not Approved</label>
		@endif
	
		<select style="display:none" id="{{ $ledger_group->id }}" class="active_status form-control col-md-2">
			<option @if($ledger_group->approval_status == 1) selected="selected" @endif value="1">Approved</option>
			<option @if($ledger_group->approval_status == 0) selected="selected" @endif value="0">Not Approved</option>
		</select>
	</div>
@endforeach				
@stop

@section('dom_links')
@parent 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmake-0.1.32/pdfmake.min.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmake-0.1.32/vfs_fonts.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/datatables.min.js') }}"></script> 
<script type="text/javascript">
   
   $('body').on('click', '.status', function(e) {
			$(this).hide();
			$(this).parent().find('select').css('display', 'block');
		});	

		$('body').on('change', '.active_status', function(e) {
		  var status = $(this).val();
		  var id = $(this).attr('id');
		  var current = $(this);
		  $.ajax({
		   url: "{{ route('change_approval_status') }}",
		   type: 'post',
		   data: {
			id: id,
			_token :"{{ csrf_token() }}",
			status: status
			},
		   dataType: "json",
			success:function(data, textStatus, jqXHR) {
			  if(status == 0) {
				$('label.status').removeClass('badge-success');
				$('label.status').addClass('badge-warning');
			  }else if(status == 1) {
				$('label.status').removeClass('badge-warning');
				$('label.status').addClass('badge-success');
			  }
			  current.hide();
			  $('label.status').show();
			  $('label.status').text(current.find('option:selected').text());
			},
		   error:function(jqXHR, textStatus, errorThrown) {
			//alert("New Request Failed " +textStatus);
			}
		  });
		});

</script> 
@stop
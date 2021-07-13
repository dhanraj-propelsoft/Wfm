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

@permission('Application-Custom-Values-Edit')
<div class="fill header">
  <h4 class="float-left page-title">Custom Values</h4>
  	<a class="btn btn-danger float-right edit" id="" style="color: #fff">Edit</a> 
</div>
@endpermission

<div class="float-left" style="width: 100%; padding-top: 10px">
  <table id="datatable" class="table data_table" width="100%" cellspacing="0">
	<thead>
		<tr>
			<th> {{ Form::checkbox('check_all', '[]', null, ['id' => 'check_all'] ) }} <label for="check_all"><span></span></label></th>
			<th> Module</th>
			<th> Screen </th>
			<th> Factor </th>
			<th> Multiple </th>
			<th> Value </th>
		 </tr>
	</thead>
	<tbody>
	
	@foreach($custom_values as $custom_value)
	 
	<tr>
		<td width="1" style="padding-left: 7px;">{{ Form::checkbox('custom_value',$custom_value->id, null, ['id' => $custom_value->id, 'class' => 'item_checkbox']) }}<label for="{{$custom_value->id}}"><span></span></label></td>
		<td>{{ $custom_value->module }}</td>
		<td>{{ $custom_value->screen }}</td>
		<td>{{ $custom_value->factor }}</td>
		@if($custom_value->multiple == 1)
		<td> yes  </td>           
		@elseif($custom_value->multiple == 0)   
		<td> no </td> 
		@endif	
		<td>{{$custom_value->sample }}</td>
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





  $('body').on('click', '.edit', function(e) {
		e.preventDefault();
		var id = $(".item_checkbox:checked").val();
		$.get("{{ url('settings/settings-custom_values') }}/"+id+"/edit", function(data) {
		  $('.crud_modal .modal-container').html("");
		  $('.crud_modal .modal-container').html(data);
		});
		$('.crud_modal').modal('show');
  });


  });
  </script>
@stop
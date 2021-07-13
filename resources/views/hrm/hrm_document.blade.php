@extends('layouts.master')
@section('head_links') @parent
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
@stop
@include('includes.hrm')
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
  	<h4 class="float-left page-title">Documents</h4>
	  	@permission('hrm-document-create')
		<a class="btn btn-danger float-right refresh" style="color: #fff"> Refresh</a>
		<a class="btn btn-danger float-right discard" style="color: #fff"> Delete</a>
		<a class="btn btn-danger float-right edit" style="color: #fff"> Edit</a>
		<a class="btn btn-danger float-right add" style="color: #fff"> New</a>
		@endpermission
  	
</div>
<div class="fill header float-left col-sm-12">
			{{ Form::label('document_type','Document Types',array('class' => 'control-label col-sm-2')) }}
			{{ Form::select('document_type', $types, null, ['class'=>'from-control col-sm-2']) }}
			
</div>

<div class="float-left table_container" style="width: 100%; padding-top: 10px;">
	<div class="batch_container">

		<div class="batch_action">
			<i class="fa icon-arrows-slide-down1 arrow"></i>Batch Actions <i class="fa fa-caret-down"></i>
		</div>

		<ul class="batch_list">
	  			@permission('hrm-document-delete')
			  	<li><a class="multidelete">Delete</a></li>
	  			@endpermission
		
			
			  	<li><a data-value="1" class="multiapprove">Make Active</a></li>
			  	<li><a data-value="0" class="multiapprove">Make In-Active</a></li>
			
		</ul>
	</div>
	
  	<table id="datatable" class="table data_table table-hover" width="100%" cellspacing="0">
		<thead>
			<tr>
				<th> {{ Form::checkbox('check_all', 'check_all', null, ['id' => 'check_all'] ) }} <label for="check_all"><span></span></label></th>
				<th> Serial Number </th> 
				<th> Document Name </th>               
				<th> Document  </th>
				<th> Document Type </th>
				<th> Uploaded On </th>
				<th> Valid From </th>
				<th> Document Status </th>
				<th> Action </th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$i=1;
			?>
			@foreach($documents as $document)
				<tr>
					<td width="1">{{ Form::checkbox('document',$document->id, null, ['id' => $document->id, 'class' => 'item_check']) }}<label for="{{$document->id}}"><span></span></label></td>
					<td> {{ $i }}</td>
					<td> {{ $document->name }}</td>
					<td>  </td>
					<td> {{ $document->document_type }} </td>
					<td> {{ $document->created_at }} </td>
					<td> {{ $document->valid_from }} </td>

					<td>
					@if($document->status == '1')
					  	<label class="grid_label badge badge-success status">Active</label>
					@elseif($document->status == '0')
					  	<label class="grid_label badge badge-warning status">In-Active</label>
					@endif

					
						<select style="display:none" id="{{ $document->id }}" class="active_status form-control">
							<option @if($document->status == 0) selected="selected" @endif value="0">In-Active</option>
							<option @if($document->status == 1) selected="selected" @endif value="1">Active</option>
						</select>
					
					</td>
					<td>           
	  					@permission('hrm-document-edit')
					  	<a data-id="{{ $document->id }}" class="grid_label action-btn edit-icon edit"><i class="fa li_pen"></i></a>
	  					@endpermission
					
	  					@permission('hrm-document-delete')
					  	<a data-id="{{ $document->id }}" class="grid_label action-btn delete-icon delete">
					  	<i class="fa fa-trash-o"></i></a> 
	  					@endpermission

					
			  	</td>


				</tr>
			<?php
			$i++;
			?> 
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

   var datatable_options = {"columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": [[1, "asc"]], "stateSave": true};

  	$(document).ready(function() {

  			$('select[name=document_type]').change(function(){

  					var id=$(this).val();
  				//var doc=$(this).text();
  				//alert(id);
  				$.ajax({

  					url: '{{ route('document_type_search')}}',
  					type: 'get',
  					data: 
  					{
  						id: id

  					},
  					success:function(data,textStatus,jqXHR)
  					{
  						//alert();
  						//alert($('tbody tr').remove());

  						$(`tbody`).html("");
  						var j=1;
  						var type=data.type;
  						//var message=data.message;

  						
          				var html="";
  						for(var i in type)
  						{

  						html +=`<tr role="row" class="odd">
					<td><input id="`+type[i].id+`" class="item_check" name="vacancy" value="`+type[i].id+`" type="checkbox"><label for="`+type[i].id+`"><span></span></label>
					</td>
					
					<td>`+j+`</td>
					
					<td>`+type[i].name+`</td>
					<td></td>
					<td>`+type[i].type_name+`</td>
					<td>`+type[i].updated_at+`</td>
					<td>`+type[i].valid_from+`</td>

					
					<td>
						<label class="grid_label badge badge-success status">Active</label>
						<select style="display:none" id="`+type[i].id+`" class="active_status form-control">
							<option value="1">Active</option>
							<option value="0">In-Active</option>
						</select>
					</td>
					<td>
					<a data-id="`+type[i].id+`" class="action-btn grid_label edit-icon edit"><i class="fa li_pen"></i></a>&nbsp;
					<a data-id="`+type[i].id+`" class="action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a>
					</td></tr>`;
  						//console.log();
						j++;

  					}
  						$('tbody').html(html);
  					
  					
  					
  					},
  					error:function()
  					{

  					}

  				});
  				
  			});


  	datatable = $('#datatable').DataTable(datatable_options);

  	$('.add').on('click', function(e) {
		e.preventDefault();
		$.get("{{ route('document.create') }}", function(data) {
		  	$('.crud_modal .modal-container').html("");
		  	$('.crud_modal .modal-container').html(data);
		});
		//$('.crud_modal').find('.modal-dialog').addClass('modal-lg');
		$('.crud_modal').modal('show');
  	});

  	$('body').on('click', '.edit', function(e) {
		e.preventDefault();
		$.get("{{ url('hrm/document') }}/"+$(this).data('id')+"/edit", function(data) {
		  	$('.crud_modal .modal-container').html("");
		  	$('.crud_modal .modal-container').html(data);
		});
		$('.crud_modal').modal('show');
  	});

  	$('body').on('click', '.status', function(e) {
		$(this).hide();
		$(this).parent().find('select').css('display', 'block');
  	});

  	/*$('body').on('click', '.multidelete', function() {
		var url = "{{ route('designations.multidestroy') }}";
		multidelete($(this), url, "{{ csrf_token() }}");
  	});

  	$('body').on('click', '.multiapprove', function() {
		var url = "{{ route('designations.multiapprove') }}";
		multi_status($(this), $(this).data('value'), url, "{{ csrf_token() }}");
  	});*/

  	$('body').on('change', '.active_status', function(e) {
		var status = $(this).val();
		var id = $(this).attr('id');
		var obj = $(this);
		//alert(obj);
		var url = "{{ route('document.status') }}";
		change_status(id, obj, status, url, "{{ csrf_token() }}");
	});

  	$('body').on('click', '.delete', function(){
		var id = $(this).data('id');
		var parent = $(this).closest('tr');
		var delete_url = '{{ route('document.destroy') }}';
		delete_row(id, parent, delete_url, "{{ csrf_token() }}");
	});
           

});
</script> 
@stop
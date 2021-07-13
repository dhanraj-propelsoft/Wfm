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
  	<h4 class="float-left page-title">Vacancies</h4>
  		@permission('hrm-vacancy-create')
		<a class="btn btn-danger float-right refresh" style="color: #fff"> Refresh</a>
		<a class="btn btn-danger float-right discard" style="color: #fff"> Discard</a>
		<a class="btn btn-danger float-right edit" style="color: #fff"> Edit</a>
		<a class="btn btn-danger float-right add" style="color: #fff"> New</a>
 		@endpermission
		
  	
</div>
<div class="fill header float-left col-sm-12">
			{{ Form::label('vacant_status','Vacant Status',array('class' => 'control-label col-sm-2')) }}
			{{ Form::select('vacant_status', ['0'=> 'Open', '1' =>'Close'], null, ['class'=>'from-control col-sm-2','placeholder' => 'Choose a status']) }}
			
</div>

<div class="float-left table_container" style="width: 100%; padding-top: 10px;">
	<div class="batch_container">

		<div class="batch_action">
			<i class="fa icon-arrows-slide-down1 arrow"></i>Batch Actions <i class="fa fa-caret-down"></i>
		</div>

		<ul class="batch_list">
  				@permission('hrm-vacancy-delete')
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
				<th> Designation Name </th> 
				<th> No of Positions </th>               
				<th> No of Vacancies  </th>
				<th> From </th>
				<th> Filled Status </th>
				<th> Action </th>
			</tr>
		</thead>
		<tbody>
			<?php
				$i=1;
			?>
		@foreach($vacancies as $vacancy)
			<tr>
				<td width="1">{{ Form::checkbox('vacancy',$vacancy->id, null, ['id' => $vacancy->id, 'class' => 'item_check']) }}<label for="{{$vacancy->id}}"><span></span></label></td>
				<td>{{ $i }}</td>              
				<td>{{ $vacancy->designation_name }}</td> 
				<td>{{ $vacancy->no_of_positions }}</td>
				 <td>{{ $vacancy->no_of_vacancies }}</td>
				<td>{{ $vacancy->create_update_date }}</td>
				<td>
					@if($vacancy->status == '1')
					  	<label class="grid_label badge badge-success status">Close</label>
					@elseif($vacancy->status == '0')
					  	<label class="grid_label badge badge-warning status">Open</label>
					@endif

					
						<select style="display:none" id="{{ $vacancy->id }}" class="active_status form-control">
							<option @if($vacancy->status == 1) selected="selected" @endif value="1">Close</option>
							<option @if($vacancy->status == 0) selected="selected" @endif value="0">Open</option>
						</select>
					
				</td>
				<td>           
  						@permission('hrm-vacancy-edit')
						<a data-id="{{ $vacancy->id }}" class="grid_label action-btn edit-icon edit"><i class="fa li_pen"></i></a>
 						@endpermission

					
  						@permission('hrm-vacancy-delete')
						<a data-id="{{ $vacancy->id }}" class="grid_label action-btn delete-icon delete">
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

  			$('select[name=vacant_status]').change(function(){

  					//alert();
  					var id=$(this).val();
  					//alert(id);
  					$.ajax({

  						url: '{{  route('vacancy_status_search') }}',
  						type: 'get',
  						data: 
  						{
  							id: id
  						},
  						success:function(data,textStatus,jqXHR)
  						{
  							//alert();
  							//console.log(data.status);
  							$('tbody').html("");
  							var status=data.status;
  							var j=1;
  							var html="";
  						for(var i in status)
  						{

  						html +=`<tr role="row" class="odd">
					<td><input id="`+status[i].id+`" class="item_check" name="vacancy" value="`+status[i].id+`" type="checkbox"><label for="`+status[i].id+`"><span></span></label>
					</td>
					
					<td>`+j+`</td>
					
					<td>`+status[i].designation_name+`</td>
			
					<td>`+status[i].no_of_positions+`</td>
					<td>`+status[i].no_of_vacancies+`</td>
					<td>`+status[i].create_update_date+`</td>

					
					<td>
						<label class="grid_label badge badge-success status">Open</label>
						<select style="display:none" id="`+status[i].id+`" class="active_status form-control">
							<option value="1">Close</option>
							<option value="0">Open</option>
						</select>
					</td>
					<td>
					<a data-id="`+status[i].id+`" class="action-btn grid_label edit-icon edit"><i class="fa li_pen"></i></a>&nbsp;
					<a data-id="`+status[i].id+`" class="action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a>
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
		$.get("{{ route('vacancy.create') }}", function(data) {
		  	$('.crud_modal .modal-container').html("");
		  	$('.crud_modal .modal-container').html(data);
		});
		//$('.crud_modal').find('.modal-dialog').addClass('modal-lg');
		$('.crud_modal').modal('show');
  	});

  	$('body').on('click', '.edit', function(e) {
		e.preventDefault();
		$.get("{{ url('hrm/vacancy') }}/"+$(this).data('id')+"/edit", function(data) {
		  	$('.crud_modal .modal-container').html("");
		  	$('.crud_modal .modal-container').html(data);
		});
		$('.crud_modal').modal('show');
  	});

  	$('body').on('click', '.status', function(e) {
		$(this).hide();
		$(this).parent().find('select').css('display', 'block');
  	});

  	$('body').on('click', '.multidelete', function() {
		var url = "{{ route('designations.multidestroy') }}";
		multidelete($(this), url, "{{ csrf_token() }}");
  	});

  	$('body').on('click', '.multiapprove', function() {
		var url = "{{ route('designations.multiapprove') }}";
		multi_status($(this), $(this).data('value'), url, "{{ csrf_token() }}");
  	});

  	$('body').on('change', '.active_status', function(e) {
		var status = $(this).val();
		var id = $(this).attr('id');
		var obj = $(this);
		var url = "{{ route('vacancy.status') }}";
		change_status(id, obj, status, url, "{{ csrf_token() }}");
	});

  	$('body').on('click', '.delete', function(){
		var id = $(this).data('id');
		var parent = $(this).closest('tr');
		var delete_url = '{{ route('vacancy.destroy') }}';
		delete_row(id, parent, delete_url, "{{ csrf_token() }}");
	});
           

});
</script> 
@stop
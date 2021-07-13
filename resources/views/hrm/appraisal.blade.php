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
  	<h4 class="float-left page-title">Appraisals</h4>
		<a class="btn btn-danger float-right refresh" style="color: #fff"> Refresh</a>
		<a class="btn btn-danger float-right discard" style="color: #fff">  Edit</a>
		<a class="btn btn-danger float-right random" style="color: #fff"> Random </a>
		<a class="btn btn-danger float-right initiate " style="color: #fff"> Initiate</a>
		
</div>

<div class="fill header float-left col-sm-12">
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ Form::label('appraisal_year','Appraisal year',array('class' => 'control-label')) }}
			{{ Form::select('appraisal_year', [], null, ['class'=>'from-control col-sm-2','placeholder' => 'Year']) }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			

		{{ Form::label('status','Status',array('class' => 'control-label ')) }}
			{{ Form::select('status', ['0'=> 'Progress', '1' =>'Appealed' ,'3' =>'Resulted'], null, ['class'=>'from-control col-sm-2','placeholder' => 'Choose a status']) }}
			
</div>

<div class="float-left table_container" style="width: 100%; padding-top: 10px;">
	<div class="batch_container">

		<div class="batch_action">
			<i class="fa icon-arrows-slide-down1 arrow"></i>Batch Actions <i class="fa fa-caret-down"></i>
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
				<!-- <th> Serial Number </th>  -->
				<th> Employee Name </th> 
				<th> Contact </th>               
				<th> Year </th>
				<th> Status </th>
				<th> Meeting On </th>
				<th> Meeting By</th>
				<th> Points</th>
				<th> Action</th>

			</tr>
		</thead>
		<tbody>
			
			@foreach($appraisals as $appraisal)
			<tr>
				<td width="1">{{ Form::checkbox('appraisal',$appraisal->id, null, ['id' => $appraisal->id, 'class' => 'item_check']) }}<label for="{{$appraisal->id}}"><span></span></label></td>
				
				<td> <a class="direct_random"><u>{{ $appraisal->name }}</u></a>  </td>
				<td></td>
				<td> {{ $appraisal->appraisal_year }}</td>
				<td>
					@if($appraisal->status == '0')
					  	<label class="grid_label badge badge-success status">Progress</label>
					@elseif($appraisal->status == '1')
					  	<label class="grid_label badge badge-warning status">Appealed</label>
					@elseif($appraisal->status == '2')
					  	<label class="grid_label badge badge-info status">Resulted</label>
					@endif

					<select style="display:none" id="{{ $appraisal->id }}" class="active_status form-control">
							<option @if($appraisal->status == 0) selected="selected" @endif value="0">Progress</option>
							<option @if($appraisal->status == 1) selected="selected" @endif value="1">Appealed</option>
							<option @if($appraisal->status == 2) selected="selected" @endif value="2">Resulted</option>
					</select>
				</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
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

   var datatable_options = {"columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": [[1, "asc"]], "stateSave": true};

  	$(document).ready(function() {

  	


  	datatable = $('#datatable').DataTable(datatable_options);

  	$('.initiate').on('click', function(e) {
		e.preventDefault();
		$.get("{{ route('appraisal_initiate.create') }}", function(data) {
		  	$('.crud_modal .modal-container').html("");
		  	$('.crud_modal .modal-container').html(data);
		});
		$('.crud_modal').find('.modal-dialog').addClass('modal-lg');
		$('.crud_modal').modal('show');
  	});
  	
  	
  	$('body').on('click','.random,.direct_random' ,function(e) {
		e.preventDefault();
		$.get("{{ route('appraisal_random.create') }}", function(data) {
		  	$('.crud_modal .modal-container').html("");
		  	$('.crud_modal .modal-container').html(data);
		});
		$('.crud_modal').find('.modal-dialog').addClass('modal-lg');
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

  		//alert();
		var status = $(this).val();
		//alert(status);
		var id = $(this).attr('id');
		//alert(id);

		var obj = $(this);
		//console.log(obj);

		$.ajax({
			 url: '{{ route('appraisal.status') }}',
			 type: 'post',
			 data: {
			 	_token: "{{csrf_token()}}",
			 	id: id,
			 	status: status
				},
			 dataType: "json",
				success:function(data, textStatus, jqXHR) {
					//console.log(data.status);
					if(status == 0) {
						obj.parent().find('label').removeClass('badge-warning');
						obj.parent().find('label').removeClass('badge-info');
						obj.parent().find('label').addClass('badge-success');
					}else if(status == 1) {
				
						obj.parent().find('label').removeClass('badge-info');
						obj.parent().find('label').removeClass('badge-success');
						obj.parent().find('label').addClass('badge-warning');
					}
				
					else if(status == 2) {
						obj.parent().find('label').removeClass('badge-success');
						obj.parent().find('label').removeClass('badge-warning');
						obj.parent().find('label').addClass('badge-info');
					}
					obj.hide();
					obj.parent().find('label').show();
					obj.parent().find('label').text(obj.find('option:selected').text());
				},
			 error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
		
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
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
  	<h4 class="float-left page-title">Candidates</h4>
		@permission('hrm-candidate-create')
		<a class="btn btn-danger float-right refresh" style="color: #fff"> Refresh</a>
		<a class="btn btn-danger float-right discard" style="color: #fff"> Discard</a>
		<a class="btn btn-danger float-right edit" style="color: #fff"> Edit</a>
		<a class="btn btn-danger float-right add" style="color: #fff"> New</a>
		@endpermission
		
  	
</div>
<div class="fill header float-left col-sm-12">
			{{ Form::label('recruitment_status','Recruitment Status',array('class' => 'control-label col-sm-2')) }}
			{{ Form::select('recruitment_status', $recruitment_statuses, null, ['class'=>'from-control col-sm-2']) }}
			
</div>

<div class="float-left table_container" style="width: 100%; padding-top: 10px;">
	<div class="batch_container">

		<div class="batch_action">
			<i class="fa icon-arrows-slide-down1 arrow"></i>Batch Actions <i class="fa fa-caret-down"></i>
		</div>

		<ul class="batch_list">
				@permission('hrm-candidate-delete')
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
				<th> Candidate Name </th>               
				<th> Phone Contact  </th>
				<th> Main Skill </th>
				<th> Applied For </th>
				<th> Applied On </th>
				<th> Status </th>
				<th> Action </th>

			</tr>
		</thead>
		<tbody>
			<?php
				$i=1;
			?>
		@foreach($candidates as $candidate)
			<tr>
				<td width="1">{{ Form::checkbox('candidate',$candidate->id, null, ['id' => $candidate->id, 'class' => 'item_check']) }}<label for="{{$candidate->id}}"><span></span></label></td>
				<td> {{ $i }} </td>              
				<td> {{ $candidate->name }} </td>  
				<td> {{ $candidate->contact_number }} </td>
				<td> {{ $candidate->skill_set_1 }} </td>
				<td> {{ $candidate->designation_id }} </td>
				<td> {{ $candidate->applied_on }} </td>

				<td>
					@if($candidate->recruitment_status == '1')
					  	<label class="grid_label badge badge-default status">New</label>
					@elseif($candidate->recruitment_status == '2')
					  	<label class="grid_label badge badge-success status">Progress</label>
					@elseif($candidate->recruitment_status == '3')
					  	<label class="grid_label badge badge-warning status">Passed</label>
					@elseif($candidate->recruitment_status == '4')
					  	<label class="grid_label badge badge-danger status">Failed</label>
					@elseif($candidate->recruitment_status == '5')
					  	<label class="grid_label badge badge-default status">Offered</label>
					@elseif($candidate->recruitment_status == '6')
					  	<label class="grid_label badge badge-primary status">Recruited</label>
					@elseif($candidate->recruitment_status == '7')
					  	<label class="grid_label badge badge-info status">Discarded</label>
					@elseif($candidate->recruitment_status == '8')
					  	<label class="grid_label badge badge-warning status">On hold</label>
					@endif

					
						<select style="display:none" id="{{ $candidate->id }}" class="active_status form-control">
							<option @if($candidate->recruitment_status == 1) selected="selected" @endif value="1">New</option>
							<option @if($candidate->recruitment_status == 2) selected="selected" @endif value="2">Progress</option>recruitment_status
							<option @if($candidate->recruitment_status == 3) selected="selected" @endif value="3">Passed</option>
							<option @if($candidate->recruitment_status == 4) selected="selected" @endif value="4">Failed</option>
							<option @if($candidate->recruitment_status == 5) selected="selected" @endif value="5">Offered</option>
							<option @if($candidate->recruitment_status == 6) selected="selected" @endif value="6">Recruited</option>
							<option @if($candidate->recruitment_status == 7) selected="selected" @endif value="7">Discarded</option>
							<option @if($candidate->recruitment_status == 8) selected="selected" @endif value="8">On hold</option>
						</select>
					
				</td>
				<td>           
						@permission('hrm-candidate-edit')
					  	<a data-id="{{ $candidate->id }}" class="grid_label action-btn edit-icon edit"><i class="fa li_pen"></i></a>
						@endpermission
						
						@permission('hrm-candidate-delete')
						<a data-id="{{ $candidate->id }}" class="grid_label action-btn delete-icon delete">
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


  		$('select[name=recruitment_status]').change(function(){

  				//alert();
  				var id=$(this).val();
  				//alert(id);
  				$.ajax({

  					url:'{{ route('get_recruitment_status') }}',
  					type: 'get',
  					data: 
  					{
  						id:id
  					},
  					success:function(data,textStatus,jqXHR)

  					{
  						//alert();
  						//console.log(data.status);
  						var status=data.status;
  						$('tbody').html("");
  						var html="";
  						var j=1;

  						//var status_id=data.status.status;


  				


  						//var status_id=data.status.status;
  						//alert(status_id);
  						for(var i in status)
  						{
  							//console.log(status_id);

				  				if(status[i].recruitment_status == 1) 
								{
									active_selected = "selected";
									selected_text = "New";
									selected_class = "badge-default";
								}
								else if(status[i].recruitment_status == 2) 
								{
									active_selected = "selected";
									selected_text = "Progress";
									selected_class = "badge-success";
								}
								else if(status[i].recruitment_status == 3) 
								{
									active_selected = "selected";
									selected_text = "Passed";
									selected_class = "badge-warning";
								}
								else if(status[i].recruitment_status == 4) 
								{
									active_selected = "selected";
									selected_text = "Failed";
									selected_class = "badge-danger";
								}
								else if(status[i].recruitment_status == 5) 
								{
									active_selected = "selected";
									selected_text = "Offered";
									selected_class = "badge-default";
								}
								else if(status[i].recruitment_status == 6) 
								{
									active_selected = "selected";
									selected_text = "Recruited";
									selected_class = "badge-primary";
								}
								else if(status[i].recruitment_status ==7)
								 {
									active_selected = "selected";
									selected_text = "Discarded";
									selected_class = "badge-info";
								} 
								else if(status[i].recruitment_status == 8)
								 {
									inactive_selected = "selected";
									var selected_text = "On hold";
									var selected_class = "badge-warning";
								}
  							html+=`<tr>
  							<td><input id="`+status[i].id+`" class="item_check" name="vacancy" value="`+status[i].id+`" type="checkbox"><label for="`+status[i].id+`"><span></span></label>
  							</td>
  							<td> `+j+`</td> 
  							<td>`+status[i].name+`</td>
  							<td>`+status[i].contact_number+`</td>
  							<td>`+status[i].skill_set_1+`</td>
  							<td>`+status[i].designation_name+`</td>
  							<td>`+status[i].applied_on+`</td>
  							<td>
  							<label class="grid_label badge `+selected_class+` status">`+selected_text+`</label>
										


  							</td>
  							<td>
							<a data-id="`+status[i].id+`" class="action-btn grid_label edit-icon edit"><i class="fa li_pen"></i></a>&nbsp;
						  	<a data-id="`+status[i].id+`" class="action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a>
  							</td>
  							</tr>`;
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
		$.get("{{ route('candidate.create') }}", function(data) {
		  	$('.crud_modal .modal-container').html("");
		  	$('.crud_modal .modal-container').html(data);
		});
		$('.crud_modal').find('.modal-dialog').addClass('modal-lg');
		$('.crud_modal').modal('show');
  	});

  	$('body').on('click', '.edit', function(e) {
		e.preventDefault();
		$.get("{{ url('hrm/candidate') }}/"+$(this).data('id')+"/edit", function(data) {
		  	$('.crud_modal .modal-container').html("");
		  	$('.crud_modal .modal-container').html(data);
		});
		$('.crud_modal').find('.modal-dialog').addClass('modal-lg');
		$('.crud_modal').modal('show');
  	});

  	$('body').on('click', '.status', function(e) {
		$(this).hide();
		$(this).parent().find('select').css('display', 'block');
  	});

  // 	$('body').on('click', '.multidelete', function() {
		// var url = "{{ route('designations.multidestroy') }}";
		// multidelete($(this), url, "{{ csrf_token() }}");
  // 	});

  // 	$('body').on('click', '.multiapprove', function() {
		// var url = "{{ route('designations.multiapprove') }}";
		// multi_status($(this), $(this).data('value'), url, "{{ csrf_token() }}");
  // 	});

  	$('body').on('change', '.active_status', function(e) {

  		//alert();
		var status = $(this).val();
		//alert(status);
		var id = $(this).attr('id');
		//alert(id);

		var obj = $(this);
		//console.log(obj);

		$.ajax({
			 url: '{{ route('candidate.status') }}',
			 type: 'post',
			 data: {
			 	_token: "{{csrf_token()}}",
			 	id: id,
			 	status: status
				},
			 dataType: "json",
				success:function(data, textStatus, jqXHR) {
					//console.log(data.status);
					if(status == 1) {
						obj.parent().find('label').removeClass('badge-success');
						obj.parent().find('label').removeClass('badge-warning');
						obj.parent().find('label').removeClass('badge-danger');
						obj.parent().find('label').removeClass('badge-primary');
						obj.parent().find('label').removeClass('badge-info');
						
						obj.parent().find('label').addClass('badge-default');
					}else if(status == 2) {

						obj.parent().find('label').removeClass('badge-warning');
						obj.parent().find('label').removeClass('badge-danger');
						obj.parent().find('label').removeClass('badge-primary');
						obj.parent().find('label').removeClass('badge-info');
						obj.parent().find('label').removeClass('badge-default');

						obj.parent().find('label').addClass('badge-success');
					}
					else if(status == 3) {

						obj.parent().find('label').removeClass('badge-success');
						obj.parent().find('label').removeClass('badge-danger');
						obj.parent().find('label').removeClass('badge-primary');
						obj.parent().find('label').removeClass('badge-info');
						obj.parent().find('label').removeClass('badge-default');

						obj.parent().find('label').addClass('badge-warning');
					}
					else if(status == 4) {

						obj.parent().find('label').removeClass('badge-success');
						obj.parent().find('label').removeClass('badge-primary');
						obj.parent().find('label').removeClass('badge-info');
						obj.parent().find('label').removeClass('badge-default');
						obj.parent().find('label').removeClass('badge-warning');

						obj.parent().find('label').addClass('badge-danger');
					}
					else if(status == 5) {

						obj.parent().find('label').removeClass('badge-success');
						obj.parent().find('label').removeClass('badge-primary');
						obj.parent().find('label').removeClass('badge-info');
						obj.parent().find('label').removeClass('badge-danger');
						obj.parent().find('label').removeClass('badge-warning');


						obj.parent().find('label').addClass('badge-default');
					}
					else if(status == 6) {

						obj.parent().find('label').removeClass('badge-success');
						obj.parent().find('label').removeClass('badge-default');
						obj.parent().find('label').removeClass('badge-info');
						obj.parent().find('label').removeClass('badge-danger');
						obj.parent().find('label').removeClass('badge-warning');

						obj.parent().find('label').addClass('badge-primary');
					}
					else if(status == 7) {
						obj.parent().find('label').removeClass('badge-success');
						obj.parent().find('label').removeClass('badge-primary');
						obj.parent().find('label').removeClass('badge-info');
						obj.parent().find('label').removeClass('badge-default');
						obj.parent().find('label').removeClass('badge-warning');

						obj.parent().find('label').addClass('badge-info');

					}
					else if(status == 8) {
						obj.parent().find('label').removeClass('badge-success');
						obj.parent().find('label').removeClass('badge-primary');
						obj.parent().find('label').removeClass('badge-info');
						obj.parent().find('label').removeClass('badge-default');
						obj.parent().find('label').removeClass('badge-danger');

						obj.parent().find('label').addClass('badge-warning');
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
		var delete_url = '{{ route('candidate.destroy') }}';
		delete_row(id, parent, delete_url, "{{ csrf_token() }}");
	});
           

});
</script> 
@stop
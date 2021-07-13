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
  	<h4 class="float-left page-title">Appraisal KPIs</h4>
		
		@permission('hrm-appraisal-kpi-create')
		<a class="btn btn-danger float-right refresh" style="color: #fff"> Refresh</a>&nbsp;&nbsp;&nbsp;
		<!-- <a class="btn btn-danger float-right finish" style="color: #fff;display:none;">Finish </a> -->
		<a class="btn btn-danger float-right delete" style="color: #fff;display:none;"> Delete </a>
		<a class="btn btn-danger float-right edit" style="color: #fff;display:none;">Edit Line </a>
		<a class="btn btn-danger float-right add" style="color: #fff;display:none;">New </a>
		<a class="btn btn-danger float-right update" style="color: #fff">Update KPIs </a>
		@endpermission
		
</div>

<div class="float-left table_container" style="width: 100%; padding-top: 10px;">
	<div class="batch_container">

		<div class="batch_action">
			<i class="fa icon-arrows-slide-down1 arrow"></i>Batch Actions <i class="fa fa-caret-down"></i>
		</div>

		<ul class="batch_list">
				@permission('hrm-appraisal-kpi-delete')
				<li><a class="multidelete">Delete</a></li>
				@endpermission

		
			
			  	<li><a data-value="1" class="multiapprove">Make Active</a></li>
			  	<li><a data-value="0" class="multiapprove">Make In-Active</a></li>
			
		</ul>
	</div>
	
  	<table id="datatable" class="table data_table table-hover" width="100%" cellspacing="0">
		<thead>
			<tr>
				<th width="1%"> {{ Form::checkbox('check_all', 'check_all', null, ['id' => 'check_all'] ) }} <label for="check_all"><span></span></label></th>
			
				<th width="10%"> KPI Name </th>               
				<th width="10%"> Defintion  </th>
				<th width="10%"> Weight </th>
				<th width="10%"> Valid From </th>
				<!-- <th width="2%"> Action </th> -->

			</tr>
		</thead>
		<tbody >

			@foreach($appraisals as $appraisal)
			<tr>
				<td width="1">{{ Form::checkbox('appraisal',$appraisal->id, null, ['id' => $appraisal->id, 'class' => 'check']) }}<label for="{{$appraisal->id}}"><span></span></label></td>
			             
				<td>{{ $appraisal->name }}</td> 
				<td>{{ $appraisal->description }}</td>
				 <td class="weight">{{ $appraisal->weight }}</td>
				<td>{{ $appraisal->valid_from }}</td>
				
				
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

						$('.update').on('click',function(){
							//alert();
							$('.add').show();
							$('.edit').show();
							$('.delete').show();
							$('.finish').show();

						});	

						$('.add').on('click',function(e){
						
							e.preventDefault();
							$.get("{{ route('appraisal_kpi.create') }}", function(data) {
						  	$('.crud_modal .modal-container').html("");
						  	$('.crud_modal .modal-container').html(data);
							});
						$('.crud_modal').modal('show');
						//$('.crud_modal').find('.modal-dialog').addClass('modal-lg');

						});	

  	datatable = $('#datatable').DataTable(datatable_options);

  						$('input[type=checkbox]').on('click',function(){
  							//alert();

  							if($(this).prop("checked") == true)
  							{
  								//alert();
  								$('.edit').on('click',function(e){
  									
  									//alert();
  									e.preventDefault();
  							$.get("{{ route('appraisal_kpi.edit') }}",function(){
  							$('.crud_modal .modal-container').html("");
						  	$('.crud_modal .modal-container').html(data);
  									});



  								});  							
  							}

  						});


			
   	
	  	});		
  

</script> 
@stop
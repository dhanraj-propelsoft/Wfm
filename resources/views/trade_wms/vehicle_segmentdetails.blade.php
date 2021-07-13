@extends('layouts.master')

@section('head_links') @parent

	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
	<style>
    .table td
        {
            padding: 2px;
        }
        body
        {
            font-size: 12px !important;
        }
        .btn
        {
            line-height: 1;
        }
    </style>

@stop



@if(Session::get('module_name') == "trade_wms")

	@include('includes.trade_wms')

@else

	@include('includes.inventory')

@endif



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



<div class="fill header" style="height:40px;width: 102%;background-color: #e3e3e9;margin-left: -10px;">

	<h5 class="float-left page-title" style="padding-top: 8px;padding-left: 10px;"><b>Price Segment Details</b></h5>

	<!-- 	<a class="btn btn-danger float-right add" style="color: #fff">+ New</a> -->

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

				<th> {{ Form::checkbox('check_all', 'check_all', null, ['id' => 'check_all'] ) }} <label for="check_all"><span></span></label></th> <th> Make </th>

				<th> Model</th>

				<th> Varient </th>

				<th> Segment </th>

				<th> Action</th>

			</tr>

		</thead>

	 	<tbody >

		@foreach($vehicle_variants as $vehicle_variant)

			<tr>

				<td width="1" style="padding-left: 7px;">{{ Form::checkbox('vehicle_variant',$vehicle_variant->segment_id, null, ['id' => $vehicle_variant->segment_id, 'class' => 'item_check']) }}<label for="{{$vehicle_variant->segment_id}}"><span></span></label></td>             

				<td id="{{ $vehicle_variant->vehicle_make_id}}" class="make">{{ $vehicle_variant->make_name }}</td>              

				<td id="{{$vehicle_variant->vehicle_model_id}}" class="model">{{ $vehicle_variant->model_name }}</td>              

				<td id="{{$vehicle_variant->id}}" class="variant">{{ $vehicle_variant->name }}</td>
				<td>
						
					<label class="grid_label badge badge-success status">
						@if($vehicle_variant->segment_name)
							{{ $vehicle_variant->segment_name}}
						@else
							Select Segment					
						@endif
					</label>

					{!! Form::select('segment', $segments,$vehicle_variant->vehicle_segment_id, array('class' => 'active_status form-control segment','id' => '','style'=>'display:none;width:80%;border:none;','placeholder'=>"Select Segment")) !!}
				</td>


	        	<!-- @if($segments != "[]") 
	        	
	        						<td>
	        	
	        							<label class="grid_label badge badge-success status" id="segment">@if($vehicle_variant->segment_name)
	        	
	        								{{ $vehicle_variant->segment_name}}
	        	
	        							@else
	        	
	        								Select Segment					
	        	
	        							@endif
	        	
	        						</label>
	        	
	        							
	        	
	        							{!! Form::select('segment',$segments,$vehicle_variant->vehicle_segment_id, array('class' => 'active_status form-control segment','id' => '','style'=>'display:none;width:80%;border:none;','placeholder'=>"Select Segment")) !!}
	        	
	        						</td>
	        	
	        	               @elseif($segments == "[]")
	        	
	        	                 <td>
	        	
	        							<label class="grid_label badge badge-success status" id="segment">
	        	
	        								Select Segment				
	        	
	        						</label>
	        	
	        							
	        	
	        							{!! Form::select('segment', $segments,$vehicle_variant->vehicle_segment_id, array('class' => 'active_status form-control segment','id' => '','style'=>'display:none;width:80%;border:none;','placeholder'=>"Select Segment")) !!}
	        	
	        						</td>
	        	
	        					@endif -->

				<td>          

					

					

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



	var datatable_options = {"columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": [[1, "asc"]], "stateSave": true};



	$(document).ready(function() {



		$('body').on('click', '.status', function(e) {



			$(this).hide();

			$(this).parent().find('select').css('display', 'block');

		});

    

		datatable = $('#datatable').DataTable(datatable_options);



		$('body').on('change','.segment',function(){
			var obj = $(this);


      $.ajax({

 			url: '{{ route("VehicleSegmentDetail.update") }}',

 			type: 'post',

 		data: {

			_token:'{{csrf_token()}}',

		 	_method:'PATCH',

			make:$(this).closest('tr').find('.make').attr('id'),

	    	model: $(this).closest('tr').find('.model').attr('id'),

	   		variant:$(this).closest('tr').find('.variant').attr('id'),

	    	segment:$(this).closest('tr').find('select[name=segment]').val()

		  },

success:function(data, textStatus, jqXHR) { 

 

	alert_message(data.message,'success');
	obj.hide();
	obj.parent().find('label').show();
	obj.parent().find('label').text(obj.find('option:selected').text());

}



});



		});





});

</script>

@stop


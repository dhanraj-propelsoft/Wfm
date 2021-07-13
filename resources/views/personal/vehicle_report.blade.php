@extends('layouts.master')
@section('head_links') @parent
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
@stop
@include('includes.vehicle_management')
@section('content')

<div class="alert alert-success">
	{{ Session::get('flash_message') }}
</div>
 <div class="alert alert-danger date">The End Date not before the Start Date.</br>
        <center>Please enter the valid end date.</center>
</div> 

@if($errors->any())
	<div class="alert alert-danger">
		@foreach($errors->all() as $error)
			<p>{{ $error }}</p>
		@endforeach
	</div>
@endif

<div class="fill header">
  <h4 class="float-left page-title">Vehicle Service Report</h4>
  
	<!-- <a class="btn btn-danger float-right add" style="color: #fff">+ New</a> -->

</div>
<div class="modal-body">
	<div class="clearfix"></div>
	<div class="form-group">
		<div class="row">
			<div class="col-12">
				<div class="form-inline">
	                <div class="col-md-3 form-group">
	                    <label class="col-form-label" for="vehicle_no">Vehicle No</label>
	                    {{ Form::select('vehicle_no',$vehicles_register,null, ['class'=>'form-control select_item', 'id' => 'category_name']) }}
	                </div>	            
	               <!--  <div class="col-md-3 form-group">
	               	                <label class="col-form-label" for="customer_name">Customer Name</label>
	               	                {{ Form::select('customer_name', ['0'=>'Test'], null, ['class' => 'form-control select_item', 'id' => 'make_name', 'placeholder' => 'Select Customer Name']) }}
	               	            </div> -->	            
	                <div class="col-md-2 form-group">
	                    <label class="col-form-label" for="from_date">From Date</label>
	                    {!! Form::text('from_date', null, array('class' => 'form-control date-picker', 'data-date-format' => 'dd-mm-yyyy','id'=>'from','style'=>'color: #919191;')) !!}
	                </div>
	                <div class="col-md-2 form-group">
	                    <label class="col-form-label" for="to_date">To Date</label>
	                    {!! Form::text('to_date', null, array('class' => 'form-control date-picker to-date-picker', 'data-date-format' => 'dd-mm-yyyy','style'=>'color: #919191;','id'=>'to')) !!}
	                </div>
	                <div class="col-md-2">
	                	<br><br>
				        <button type="submit" class="btn btn-success search"> Search </button>
				        
				    </div>	
	            </div>
			</div>			
		</div>
		
	</div>
</div>

<div class="float-left table_container" style="width: 100%; padding-top: 10px;">
	
<table id="datatable" class="table data_table table-hover" width="100%" cellspacing="0">
	<thead>
	  <tr>
		<th>{{ Form::checkbox('checkbox_all', 'checkbox_all', null ) }} <label for="check_all"><span></span></label></th>
		<th> Invoice Number </th>
		<th>Organization Name </th>
		<th> Item </th>
		<th> Amount </th>
		</tr>
	</thead>
	 <tbody>
   
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

		$('.clear').on('click',function(){
			//alert();
			$('#datatable tbody').find('tr').remove();
			//datatable.destory();
			//call_back('','add',``);
		});

      $("input[name=to_date]").on("change", function () {
            var startDate = $("input[name=from_date]").val();
            var endDate = $("input[name=to_date]").val();
                 

            if (startDate > endDate) {
               // alert("Start date should be Less than End date");
                $('.alert alert-danger date').show();
				setTimeout(function() { $('.alert-danger').fadeOut(); }, 2500);

               
            }
        });

	
 
    // Order by the grouping
    $('#datatable tbody').on( 'click', 'tr.group', function () {
        var currentOrder = table.order()[0];
    	console.log(currentOrder);
        if ( currentOrder[0] === groupColumn && currentOrder[1] === 'asc' ) {
            table.order( [ groupColumn, 'desc' ] ).draw();
        }
        else {
            table.order( [ groupColumn, 'asc' ] ).draw();
        }
    } );


	$('.search').on('click',function(){
	//alert();
		var html='';
		var vehicle_no= $('select[name=vehicle_no]').val();
		//alert(vehicle_no);
		//console.log(vehicle_no);
		
		var from_date = $('input[name=from_date]').val();
		//alert(from_date);
		var to_date = $('input[name=to_date]').val();
		//alert(to_date);
		$.ajax({
			url : '{{ route('personal_vehicle_report') }}',
			type: 'POST',
			data:
			{
				_token: '{{ csrf_token() }}',
				vehicle_no : vehicle_no,
				from_date: from_date,
				to_date : to_date
			},
			success:function(data,textStatus,jqXHR)
			{
				//alert();
				console.log(data.data);
  				//$('#example tbody').destory();

				var report = data.data;
				$('#datatable tbody').empty();
				
				if( data.status == 1)
				{
					for(var i in report)
					{
						html+=`<tr>
	        			<td ><input id="`+report[i].id+`" class="item_check" name="vacancy" value="`+report[i].id+`" type="checkbox"><label for="`+report[i].id+`"><span></span></label>
	        			</td>
	        			<td>`+report[i].order_no+`</td>
	        			<td>`+report[i].orgname+`</td>
	        			<td>`+report[i].name+` </td> 
	        			<td>`+report[i].total+`</td>
	        			
	      			 	
	    			</tr>`;
	    			
	    			}
	  				//$('tbody').html(html);
					//call_back_on(html);
					call_back_optional(html,`add`,``);

				}
				else
				{
					call_back_optional(``,`add`,``);
					alert_message(data.message,'error');
				}


				
			},
			error:function()
			{

			}


		});
	});

  


  });
  </script>
@stop
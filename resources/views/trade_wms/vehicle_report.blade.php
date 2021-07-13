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
        .select2-container .select2-selection--single
		{
			height: 25px !important;
		}
		.select2-container--default .select2-selection--single .select2-selection__rendered
		{
			line-height: 22px;
		}
		input.form-control
		{
			height: 25px;
			
		}
    </style>
@stop
@include('includes.trade_wms')
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

<div class="fill header" style="height:43px;width: 102%;background-color: #e3e3e9;margin-left: -10px;">
  <h5 class="float-left page-title" style="padding-top: 8px;padding-left: 10px;"><b>Vehicle-Invoice Report</b></h5>
  
	<!-- <a class="btn btn-danger float-right add" style="color: #fff">+ New</a> -->

</div>

<div class="modal-body">
	<di
	v class="clearfix"></div>
	<div class="form-group">
		<div class="row">
			<div class="col-12">
				<div class="form-inline">
	                <div class="col-md-3 form-group">
	                    <label class="col-form-label" for="vehicle_no">Vehicle No</label>
	                    {{ Form::select('vehicle_no', $reg_no, null, ['class'=>'form-control select_item', 'id' => 'category_name']) }}
	                </div>	
	                 <div class="col-md-2 form-group"> 
				 	<label class="col-form-label">Customer</label>
				 	{!! Form::select('customer',$customer,null,['class' =>'form-control select_item customer_id','id'=>'customer','data-value'=>'Customer']) !!}  	 
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
				        <!-- <input type="reset" name="clear" value=" Clear" class="btn btn-primary clear"> </input> -->
				    </div>	
	            </div>
			</div>			
		</div>
		<!-- <div class="row">
				<div class="col-12">
					<div class="form-inline">
				                <div class="col-md-3 form-group">
				                    <label class="col-md-1 col-form-label" for="body_type">Body Type</label>
				                    {{ Form::select('body_type', ['0'=>'Test'], null, ['class'=>'form-control select_item', 'id' => 'body_type', 'placeholder' => 'Select Body Type']) }}
				                </div>	            
				                <div class="col-md-3 form-group">
				                    <label class="col-sm-2 col-form-label" for="vehicle_usage">Usage</label>
				                    {{ Form::select('vehicle_usage', ['0'=>'Test'], null, ['class' => 'form-control select_item', 'id' => 'vehicle_usage', 'placeholder' => 'Select Vehicle Usage']) }}
				                </div>
				                <div class="col-md-4"></div>
				                <div class="col-md-2">
				                	<button type="submit" class="btn btn-success tab_save_btn"> Search </button>&nbsp;
							<button type="submit" class="btn btn-primary tab_save_btn"> Export </button>
				                </div>	                
				            </div>
				</div>
			</div>	 -->	
	</div>
</div>

<div class="float-left table_container" style="width: 100%; padding-top: 10px;">
	
<table id="datatable" class="table data_table table-hover" width="100%" cellspacing="0">
	<thead>
	  <tr>
		<th>{{ Form::checkbox('checkbox_all', 'checkbox_all', null ) }} <label for="check_all"><span></span></label></th>
		<th> Invoice Number </th>
		<th>Invoice Date</th>
		<th> Customer </th>
		<th> Amount </th>
		<th> Item </th>
		<!-- <th> Vehicle Number </th> -->
		<!-- <th> Date </th> -->
		<!-- <th> Amount </th> -->
		<!-- <th> Body Type </th>
		<th> Drive </th>
		<th> Fuel Type </th>
		<th> No of Wheels </th>
		<th> Rim / wheel </th>
		<th> Tyre Size </th>
		<th> Trade Amount </th>
		<th> Usage </th> -->
	  </tr>
	</thead>
	 <tbody>
   		@foreach($reports as $report)
		<tr>
			<td><input id="{{$report->id}}" class="item_check" name="vacancy" value="{{$report->id}}" type="checkbox"><label for="{{$report->id}}"><span></span></label></td>
			<td>{{$report->order_no}}</td>
			<td>{{$report->job_date}}</td>
			<td>{{$report->customer}}</td>
			<td>{{$report->total}}</td>
			<td>{{$report->name}}</td>				
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

  $("select[name=vehicle_no]").on("change", function () {
		$("select[name=customer]").val('').trigger('change.select2');
		return false;	
   });

$("select[name=customer]").on("change", function () {	
		$("select[name=vehicle_no]").val('').trigger('change.select2');
				return false;
     });

 
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

	//var groupColumn = 3;
 
   /* var table = $('#datatable').DataTable({
        "columnDefs": [
            { "visible": false, "targets": groupColumn }
        ],
        "order": [[ groupColumn, 'asc' ]],
        "displayLength": 25,
        "drawCallback": function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last=null;
 
            api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
                if ( last !== group ) {
                    $(rows).eq( i ).before(
                        '<tr class="group"><td colspan="8">Invoice Number:'+group+'</td></tr>'
                    );
 
                    last = group;
                }
            } );
        }
    } );*/

     /*$('#example').DataTable({
  		 "columnDefs": [
            { "visible": false, "targets": groupColumn }
        ],
        "order": [[ groupColumn, 'asc' ]],
           
            "responsive": true,
    drawCallback: function (settings) {
                var api = this.api();
                var rows = api.rows({ page: 'current' }).nodes();
                var last = null;

                api.column(4, { page: 'current' }).data().each(function (group, i) {

                    if (last !== group) {

                        $(rows).eq(i).before(
                            '<tr class="group"><td colspan="8" style="BACKGROUND-COLOR:rgb(237, 208, 0);font-weight:700;color:#006232;">' + 'GRUPO ....' + group  + '</td></tr>'
                        );

                        last = group;
                    }
                });
            }

    });*/

 
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
		var html='';
		var vehicle_no= $('select[name=vehicle_no]').val();
		var customer_id = $('select[name=customer]').val();
		var from_date = $('input[name=from_date]').val();
		var to_date = $('input[name=to_date]').val();
		$.ajax({
			url : "{{ route('vehicle_list_report') }}",
			type: 'GET',
			data:
			{
				_token: '{{ csrf_token() }}',
				vehicle_no : vehicle_no,
				customer_id: customer_id,
				from_date: from_date,
				to_date : to_date
			},
			success:function(data,textStatus,jqXHR)
			{
				var report = data.data;
				$('#datatable tbody').empty();
				if( data.status == 1)
				{
					for(var i in report)
					{
						var name = report[i].name;
						if(name == null){
							name = " ";
						}else{
							name = report[i].name;
						}
						html+=`<tr>
	        			<td style="padding-left:7px;"><input id="`+report[i].id+`" class="item_check" name="vacancy" value="`+report[i].id+`" type="checkbox"><label for="`+report[i].id+`"><span></span></label>
	        			</td>
	        			<td>`+report[i].order_no+`</td>
	        			<td>`+report[i].job_date+`</td>
	        			<td>`+report[i].customer+`</td>
	        			<td>`+report[i].total+`</td>
	        			<td>`+name+` </td> 
	        			
	      			 	
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
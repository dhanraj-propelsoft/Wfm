@extends('layouts.master')
@section('head_links') @parent
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
		<link rel="stylesheet" type="text/css" href="http://localhost/propel/assets/plugins/dropzone/dropzone.css">
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
@include('includes.trade_wms')
@section('content')

<div class="alert alert-success">
	{{ Session::get('flash_message') }}
</div>
 <div class="alert alert-danger date">
 No Match Found      
</div> 
@if($errors->any())
	<div class="alert alert-danger">
		@foreach($errors->all() as $error)
			<p>{{ $error }}</p>
		@endforeach
	</div>
@endif

<div class="fill header" style="height:43px;width: 102%;background-color: #e3e3e9;margin-left: -10px;">
  <h5 class="float-left page-title" style="padding-top: 8px;padding-left: 10px;"><b>Customer Promotion</b></h5>
</div>
<div style="padding-top:50px;">
	<div class="row" style="height:65px;">
		<div class="form-group col-md-4"> 
			{{ Form::label('report', 'Report', array('class' => 'control-label col-md-12 required')) }}
				<div class="col-md-8">
					<select class="form-control select_item" id="report">
						<option selected="selected">Select Report</option>
                        <option value="0">Report By Customer Service</option>
                        <option value="1">Report By Last Service</option>
                        <option value="2">Report By Next Service</option>
                    </select>
				</div>				  		
		</div>
		<div class="form-group col-6 float-left default" style="right:140px;display:block;top: 23px;">
			<button type="submit" class="btn btn-success default_search"> Search </button>
	        <button type="submit" class="btn btn-success sms"> Send SMS</button>
		</div> 
		<div class="form-group col-6 float-left cus_grouping" style="right:140px;display:none;top: -3px;" >
			<div class="form-inline">
			<div class="col-md-6 form-group">
	            {{ Form::label('customer_grouping', 'Customer Group Name', array('class' => 'control-label col-md-12 required')) }}
	            {{ Form::select('customer_grouping',$group_name, null, ['class'=>'form-control select_item', 'id' => 'customer_grouping']) }}
	        </div><br>
	        <div class="form-group searchcase" style="padding-left: 82px;padding-top: 32px;display:none;">
	        	<button type="submit" class="btn btn-success search"> Search </button>
	        	<button type="submit" class="btn btn-success sms"> Send SMS</button>
	        </div>
			</div>			  	
		</div>
		<div class="form-group col-6 float-left last_visit" style="right:140px;display:none;top: -55px;" >
			<div class="form-inline">
			<div class="col-md-3 form-group">
	            {{ Form::label('last_visit_from', 'Last Visit From', array('class' => 'col-form-label required','palceholder'=>'Last Visit From')) }}
	            {!! Form::text('last_from_date', null, array('class' => 'form-control date-picker', 'data-date-format' => 'dd-mm-yyyy','id'=>'from','style'=>'color: #919191;')) !!}
	        </div><br>
	        <div class="col-md-3 form-group" style="padding: 50px;">
	            {{ Form::label('to_date_lst_visit', 'To', array('class' => 'col-form-label required','placeholder'=>'To Date')) }}
	            {!! Form::text('to_date_lst_visit', null, array('class' => 'form-control date-picker to-date-picker', 'data-date-format' => 'dd-mm-yyyy','style'=>'color: #919191;','id'=>'to')) !!}
	        </div>
	        <div class="form-group searchcase1" style="padding-left: 82px;padding-top: 32px;display:none;">
	        	<button type="submit" class="btn btn-success search1"> Search </button>
	        	<button type="submit" class="btn btn-success sms"> Send SMS</button>
	        </div>
			</div>			  	
		</div>

		<div class="form-group col-6 float-left next_visit" style="right:140px;display:none;top: -55px;" >
			<div class="form-inline">
			<div class="col-md-3 form-group">
	            {{ Form::label('next_visit_from', 'Next Visit From', array('class' => 'col-form-label required','palceholder'=>'Last Visit From')) }}
	            {!! Form::text('next_visit_from', null, array('class' => 'form-control date-picker', 'data-date-format' => 'dd-mm-yyyy','id'=>'from','style'=>'color: #919191;')) !!}
	        </div><br>
	        <div class="col-md-3 form-group" style="padding: 50px;">
	            {{ Form::label('to_date_nxt_visit', 'To', array('class' => 'col-form-label required','placeholder'=>'To Date')) }}
	            {!! Form::text('to_date_nxt_visit', null, array('class' => 'form-control date-picker to-date-picker', 'data-date-format' => 'dd-mm-yyyy','style'=>'color: #919191;','id'=>'to')) !!}
	        </div>
	        <div class="form-group searchcase2" style="padding-left: 82px;padding-top: 32px;display:none;">
	        	<button type="submit" class="btn btn-success search2"> Search </button>
	        	<button type="submit" class="btn btn-success sms"> Send SMS</button>
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
		<th>{{ Form::checkbox('check_all', 'check_all', null, ['id' => 'check_all'] ) }} <label for="check_all"><span></span></label></th>
		<th>Vehicle</th>
		<th>Owner</th>
		<th>Customer Ph.no</th>
		<th>Driver Name</th>
		<th>Driver Ph.no</th>
		<th>Customer Group</th>
		<th>Last Visit Date</th>
		<th>Next Visit Due</th>
		<th>Next Visit Mileage</th>
		<th>Next Visit Reason</th>
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
<script type="text/javascript" src="http://localhost/propel/assets/plugins/dropzone/dropzone.js"></script> 

<script type="text/javascript">
   var datatable = null;

   var isFirstIteration = true;

   var datatable_options = {"columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": [[1, "asc"]], "stateSave": true};

  	$(document).ready(function() {  		

		datatable = $('#datatable').DataTable(datatable_options);

		$('#report').on('change',function(){
        	var report_val = $(this).val();
        	$('.searchcase').css("display", "block")
           		if(report_val == 0){
              		$('.cus_grouping').css("display", "block");
              		$('.searchcase1').css("display", "none");
              		$('.searchcase2').css("display", "none");
              		$('.searchcase').css("display", "block");
               		$('.last_visit').css("display", "none");
               		$('.next_visit').css("display", "none");
               		$('.default').css("display", "none");
           		}else if(report_val == 1){
              		$('.last_visit').css("display", "block");
              		$('.cus_grouping').css("display", "none");
              		$('.next_visit').css("display", "none");
               		$('.searchcase1').css("display", "block");
                	$('.searchcase2').css("display", "none");
              		$('.searchcase').css("display", "none");
               		$('.default').css("display", "none");
           		}else if(report_val == 2){
             		$('.next_visit').css("display", "block");
             		$('.cus_grouping').css("display", "none");
             		$('.last_visit').css("display", "none");
              		$('.searchcase2').css("display", "block");
               		$('.searchcase1').css("display", "none");
              		$('.searchcase').css("display", "none");
               		$('.default').css("display", "none");
           		}
		});

		$('.default_search').on('click',function(){

			var html = '';
			var group_id = null;
			$.ajax({
				url : '{{ route('get_search_result') }}',
				type: 'POST',
				data:
				{
					_token: '{{ csrf_token() }}',
					group_id : group_id
				},
				success:function(data,textStatus,jqXHR)
				{                      
					var report = data.data;
				
					$('#datatable table thead').empty();
					$('#datatable table tbody').empty();
				
					if( data.status == 1)
					{
						for(var i in report)
						{
							html+=`<tr>
	        				<td style="padding-left: 7px;">
							<input id="`+report[i].owner_mobile_no+`" class="item_check" name="category" value="`+report[i].owner_mobile_no+`" data-name="`+report[i].owner+`" type="checkbox">
							<label for="`+report[i].owner_mobile_no+`"><span></span></label>
					    	</td>
	        				<td></td>
	        				<td>`+report[i].owner+`</td>
	        				<td>`+report[i].owner_mobile_no+`</td>
	        				<td></td>
	        				<td></td>
	        				<td>`+report[i].group_name+`</td>
	        				<td></td> 
	        				<td></td> 
	        				<td></td> 
	        				<td></td>	
	    					</tr>`;
	    				}

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

		$('.search').on('click',function(){

		  	var html='';
          	var group_id = $('.cus_grouping option:selected').val();
          	// var last_visit_fromdate = $('input[name=last_from_date]').val();
          	// var last_visit_todate = $('input[name=to_date_lst_visit]').val();
          	$.ajax({
				url : '{{ route('get_search_result') }}',
				type: 'POST',
				data:
				{
					_token: '{{ csrf_token() }}',
					group_id : group_id
				},
				success:function(data,textStatus,jqXHR)
				{

				
					var report = data.data;
				
					$('#datatable table thead').empty();
					$('#datatable table tbody').empty();
				
					if( data.status == 1)
					{
						for(var i in report)
						{
							html+=`<tr>
	        				<td style="padding-left: 7px;">
							<input id="`+report[i].owner_mobile_no+`" class="item_check" name="category" value="`+report[i].owner_mobile_no+`" data-name="`+report[i].owner+`" type="checkbox">
							<label for="`+report[i].owner_mobile_no+`"><span></span></label>
					    	</td>
	        				<td></td>
	        				<td>`+report[i].owner+`</td>
	        				<td>`+report[i].owner_mobile_no+`</td>
	        				<td></td>
	        				<td></td>
	        				<td>`+report[i].group_name+`</td>
	        				<td></td> 
	        				<td></td> 
	        				<td></td> 
	        				<td></td>	
	    					</tr>`;
	    				}
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

		$('.search1').on('click',function(){

		  var html='';
         // var group_id = $('.cus_grouping option:selected').val();
          var last_visit_fromdate = $('input[name=last_from_date]').val();
          var last_visit_todate = $('input[name=to_date_lst_visit]').val();
          	$.ajax({
				url : '{{ route('get_search_result') }}',
				type: 'POST',
				data:
				{
					_token: '{{ csrf_token() }}',
					last_visit_fromdate : last_visit_fromdate,
					last_visit_todate:last_visit_todate
				},
				success:function(data,textStatus,jqXHR)
				{

				//console.log(data.data);
				var report = data.data;
				$('#datatable tbody').empty();
				
				if( data.status == 1)
				{
					for(var i in report)
					{
						html+=`<tr>
	        			<td><input id="`+report[i].owner_mobile_no+`" class="item_check" name="category" value="`+report[i].owner_mobile_no+`" data-name="`+report[i].owner+`" type="checkbox">
						<label for="`+report[i].owner_mobile_no+`"><span></span></label></td>
	        			<td>`+report[i].registration_no+`</td>
	        			<td>`+report[i].owner+`</td>
	        			<td>`+report[i].owner_mobile_no+`</td>
	        			<td>`+report[i].driver_name+`</td>
	        			<td>`+report[i].driver_mobile+`</td>
	        			<td>`+report[i].group_name+`</td>
	        			<td>`+report[i].last_visit+` </td> 
	        			<td>`+report[i].next_visit+` </td> 
	        			<td>`+report[i].next_visit_mileage+` </td> 
	        			<td>`+report[i].vehicle_next_visit_reason+` </td>
	      			 	
	    			</tr>`;
	    			}
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

		$('.search2').on('click',function(){

		  	var html='';
         	// var group_id = $('.cus_grouping option:selected').val();
          	var next_visit_fromdate = $('input[name=next_visit_from]').val();
          	var next_visit_todate = $('input[name=to_date_nxt_visit]').val();
          	$.ajax({
				url : '{{ route('get_search_result') }}',
				type: 'POST',
				data:
				{
					_token: '{{ csrf_token() }}',
					next_visit_fromdate : next_visit_fromdate,
					next_visit_todate : next_visit_todate
				},
				success:function(data,textStatus,jqXHR)
				{

				var report = data.data;
				$('#datatable tbody').empty();
				
				if( data.status == 1)
				{
					for(var i in report)
					{
						html+=`<tr>
	        			<td><input id="`+report[i].owner_mobile_no+`" class="item_check" name="category" value="`+report[i].owner_mobile_no+`" data-name="`+report[i].owner+`" type="checkbox">
						<label for="`+report[i].owner_mobile_no+`"><span></span></label></td>
	        			<td>`+report[i].registration_no+`</td>
	        			<td>`+report[i].owner+`</td>
	        			<td>`+report[i].owner_mobile_no+`</td>
	        			<td>`+report[i].driver_name+`</td>
	        			<td>`+report[i].driver_mobile+`</td>
	        			<td>`+report[i].group_name+`</td>
	        			<td>`+report[i].last_visit+` </td> 
	        			<td>`+report[i].next_visit+` </td> 
	        			<td>`+report[i].next_visit_mileage+` </td> 
	        			<td>`+report[i].vehicle_next_visit_reason+` </td>
	      			 	
	    			</tr>`;
	    			}
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
   
		$('#check_all').on('click',function(){
           $('input:checkbox').prop('checked', this.checked);   
           var total_count = $('input:checkbox:checked').length;
           var empty_checkbox = $(this).length;
           var count = total_count - empty_checkbox;
           alert_message(count+" Mobile Numbers Selected",'error');
		});	


        var mobile_number = '';
       	var customer_name = '';
        var id = '';
        var path = '';
        var image_upload= '';
             
		$('.sms').on('click',function(e){

			e.preventDefault();

			if($('input:checkbox:checked').length > 0)
			{
				var data = "<div class='col-md-12'> <textarea class='form-control content' rows='5' placeholder='Enter your text here..(Max: 220 characters)' maxlength='220'  id='char_text'></textarea></div><br><div class='col-md-12'><div class='row'><div class='col-md-6 form-group'> 0 of &nbsp;<span id='char_num'>220</span> &nbsp;<span id='char_num'>Characters are used</span></div></div> <div class='row'><div class='form-group col-md-12'><span style='color:#b73c3c;''>Click Yes If You want to Add Any Images..</span>Yes<input type='checkbox' class='need_image' style = 'display:block;width: 506px;height: 19px; margin-top: -18px;'></div></div><div class='col-md-12 image_box' style='display:none;'> <div class = 'dropzone' id='image-upload'></div></div>";

				$('.delete_modal_ajax').find('.modal-title').text("Enter You Content to Send SMS:");
            	$('.delete_modal_ajax').find('.modal-body').html(data);
            	$('.delete_modal_ajax').find('.modal-footer').find('.default').text("Cancel");
            	$('.delete_modal_ajax').find('.modal-footer').find('.btn-danger').text("Send");
				$('.delete_modal_ajax').modal('show');

				id = '{{$organization_id}}';

				
				$('.need_image').on('click',function(){
					if($(this). prop("checked") == true){
						$('.image_box').css('display','block');
						path = "{{ url('customerpromotion') }}/"+'{{$organization_id}}';
					}else{
						$('.image_box').css('display','none');
						path = " ";
					}
				});

				image_upload = new Dropzone("div#image-upload", 
				{
				 	paramName: 'file',
					url: "{{route('upload_promotion_image')}}",
					params: {
						_token: '{{ csrf_token() }}'
					},
					dictDefaultMessage: "Drop or click to upload image",
					clickable: true,
					maxFilesize: 5, // MB
					acceptedFiles: "image/*",
					maxFiles: 4,
					parallelUploads: 4,
					autoProcessQueue: false,
					addRemoveLinks: true,
					uploadMultiple: true,
					removedfile: function(file) {
						file.previewElement.remove();
					},
					queuecomplete: function() {
						//image_upload.removeAllFiles();	
					},
					success: function(file, response){
					}
				});


				
				$("#char_text").on("input", function(){
				    el = $(this);
				    if(el.val().length >= 220){
				        el.val( el.val().substr(0, 220) );
				    } else {
				        $("#char_num").text(220-el.val().length);
				    }
				});
			}	

		});

		$('.delete_modal_ajax').find('.modal-footer').find('.delete_modal_ajax_btn').on('click',function(e){

				e.preventDefault();	

				$('.loader_wall_onspot').show();

				$.ajax({
					url: "{{ route('promotion_sms_limitation') }}",
					type: 'get',
					data:{
						_token : '{{ csrf_token() }}', 
					},

					success: function(data, textStatus, jqXHR)
					{
						var promotion_sms_limit = data.promotion_sms_limitation;

						if(promotion_sms_limit == true)
						{						

							image_upload.processQueue();

							var message = $('.delete_modal_ajax').find('.content').val();
							
							$(".data_table").find('tbody tr').find('.item_check:checked').map(function() {	

					 			mobile_number = $(this).val();
				     			customer_name = $(this).attr('data-name');

	              				$.ajax({
									url: "{{ route('customer_promotion.send_sms') }}",
									type: 'post',
									data: {
										_token: '{{ csrf_token() }}',
										customer_name:customer_name,
										mobile_number:mobile_number,
										message:message,
										url: path
									},
									success: function(data, textStatus, jqXHR) {
										$('.delete_modal_ajax').modal('hide');
										$('.loader_wall_onspot').hide();
										/*alert_message("SMS Sent to all the selected Customers",'success');*/
										alert_message(data.message, "success");

									},
									error: function(jqXHR, textStatus, errorThrown) {}
								});
	             			});

						}

						else{
							$('#error_dialog #title').text('Limit Exceeded!');
							$('#error_dialog #message').text('{{ config('constants.error.promotion_sms_no') }}');
							$('#error_dialog').modal('show');

							return false;
						}
					}

				});
		});


		



	


  	});

  </script>
@stop
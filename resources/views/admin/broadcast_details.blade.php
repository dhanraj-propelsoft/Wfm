<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/dropzone/dropzone.css') }}">
<div class="alert alert-danger exist_year">
    {{ Session::get('flash_message') }}
</div>
<div class="modal-header">
	<h4 class="modal-title float-right">System Broadcasting</h4>
	 <a  class="close" data-dismiss="modal">&times;</a>
</div>
	{!! Form::model($broadcast_details, ['class' => 'form-horizontal validateform'
		 ]) !!}
	{{ csrf_field() }}
<div class="modal-body">
	<div class="form-body">		
		<div class="row" style="margin-left: 40px;margin-right: 40px">
			<div class="form-group col-md-12"> 
				{{ Form::label('summary', 'Summary', array('class' => 'control-label required')) }}	
				{!! Form::text('tittle', null,['class' => 'form-control']) !!}
			</div>
		</div>
		<div class="row" style="margin-left: 25px;margin-right: 25px">
			<div class="col-md-12">
				<div class="form-group">
				{!! Form::label('message', 'Message', array('class' => 'control-label col-md-12 required')) !!}
				<div class="col-md-12">
				{{ Form::textarea('message', null, ['class' => 'form-control', 'rows' =>03]) }}
			    </div>
		        </div>
			</div>
		</div>
		<div class="row" style="margin-left: 25px;margin-right: 25px">
			<div class="col-md-12">
				<div class="form-group">
				{!! Form::label('message_type', 'Message Type', array('class' => 'control-label col-md-12 required')) !!}
				<div class="col-md-12">
				{!! Form::select('message_type', (['' => 'Select Type','Normal' => 'Normal','Alert'=>'Alert','Warning' =>'Warning','System Down'=>'System Down']), 
            	null, ['class' => 'form-control']) !!}
			    </div>
		        </div>
			</div>
		</div>
		<div class="row" style="margin-left: 25px;margin-right: 25px">
			<div class="col-md-12">
				<div class="form-group">
				{!! Form::label('module', 'Module', array('class' => 'control-label col-md-12')) !!}
				<div class="col-md-12">
				@isset($broadcast_details->id)	
				{!! Form::select('module_name',$module_name,$broadcast_details->module_id,['class' =>'form-control select_item']) !!}
				@else
				{!! Form::select('module_name',$module_name,null,['class' =>'form-control select_item']) !!}
				@endisset  
			    </div>
		        </div>
			</div>
		</div>			
		<div class="row" style="margin-left: 25px;margin-right: 25px">
			<div class="col-md-12">
				<div class="form-group">
				{!! Form::label('organization', 'Organization', array('class' => 'control-label col-md-12')) !!}
				<div class="col-md-12">	
				@isset($broadcast_details->id)
				{!! Form::select('organization_name',$organization_name,$broadcast_details->organization_id,['class' =>'form-control select_item']) !!}
				@else
				{!! Form::select('organization_name',$organization_name,null,['class' =>'form-control select_item']) !!}
				@endisset  
			    </div>
		        </div>
			</div>
		</div>	
		<div class="row" style="margin-left: 25px;margin-right: 25px">
			<div class="col-md-12">
				<div class="form-group">
				{!! Form::label('user', 'User', array('class' => 'control-label col-md-12')) !!}
				<div class="col-md-12">
				@isset($broadcast_details->id)	
				{!! Form::select('user_name',$user_name,$broadcast_details->user_id,['class' =>'form-control select_item']) !!}
				@else
				{!! Form::select('user_name',$user_name,null,['class' =>'form-control select_item']) !!}
				@endisset  
			    </div>
		        </div>
			</div>
		</div>
		<div class="row" style="margin-left: 25px;margin-right: 25px">
			<div class="col-md-12">
				<div class="form-group">
				{!! Form::label('from', 'From', array('class' => 'control-label col-md-12 required ')) !!}
				<div class="col-md-12">
				{{ Form::text('valid_from', null, ['class' => 'form-control date']) }}
			    </div>
		        </div>
			</div>
		</div>
		<div class="row" style="margin-left: 25px;margin-right: 25px">
			<div class="col-md-12">
				<div class="form-group">
				{!! Form::label('to', 'To', array('class' => 'control-label col-md-12 required')) !!}
				<div class="col-md-12">
				{{ Form::text('valid_to', null, ['class' => 'form-control date']) }}
			    </div>
		        </div>
			</div>
		</div>
		 <div class="form-group col-md-12" style="margin-left: 40px;margin-top: 15px;">
			 {!! Form::checkbox('active','1', null, array('id' => 'active')) !!}
			<label for="active"> <span></span>Active</label>


		</div> 
	</div>
</div>
<div class="modal-footer">                                            
	<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
	<button type="submit" class="btn btn-success">Submit</button>
</div>
{!! Form::close() !!}
<script type="text/javascript" src="{{ URL::asset('assets/plugins/dropzone/dropzone.js') }}">
</script>
<script type="text/javascript">

	basic_functions();

		$('.date').datepicker({
        format: "yyyy-mm-dd",
        changeMonth:true,
        changeYear:true,
        autoclose:true
    });


		$('.validateform').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		rules: {
			tittle:{ required: true },
			message:{ required: true },
			message_type:{ required: true },
			valid_from:{ required:true},
			valid_to:{ required:true},
			active:{ required:true},				
			},
			messages: {
			tittle: { required: " Summary is required."},
			message: { required: " Message is required."},
			message_type: { required: " Messagetype is required."},
			valid_from: { required: "ValidFrom is required."},
			valid_to: { required: "ValidTo is required."},
			active:{ required:"Active is required"},

		},


		invalidHandler: function(event, validator)
		{ 
			//display error alert on form submit   
			$('.alert-danger', $('.login-form')).show();
		},

		highlight: function(element) 
		{ // hightlight error inputs
			$(element).closest('.form-group').addClass('has-error'); // set error class to the control group
		},

		success: function(label) {
			label.closest('.form-group').removeClass('has-error');
			label.remove();
		},


				

		submitHandler: function(form) {
			$('.loader_wall_onspot').show();
			$.ajax({
			@isset($broadcast_details->id)
			url: '{{ route('broadcast_store',["id"=>$broadcast_details->id]) }}',
			@else
			url: '{{ route('broadcast_store') }}',
			@endisset
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				tittle: $('input[name=tittle]').val(),
				message:$('textarea[name=message]').val(),
				message_type:$('select[name=message_type]').val(),
				module_id: $('select[name=module_name]').val(),
				organization_id:$('select[name=organization_name]').val(),
				user_id: $('select[name=user_name]').val(),
				from:$('input[name=valid_from]').val(),
				to:$('input[name=valid_to]').val(),
				active:$('input[name=active]:checked').val(),    
				},
			success:function(data, textStatus, jqXHR) {
				var broadcast=data.data.broadcast;
				var module_name=broadcast.module_name;
				var broadcast_module=""
				if(module_name!=null)
				{
					broadcast_module=module_name;
				}
				else{
					broadcast_module="";
				}
				var organization_name=broadcast.organization_name;
				var broadcast_organization=""
				if(organization_name!=null)
				{
					broadcast_organization=organization_name;
				}
				else{
					broadcast_organization="";
				}
				var user_name=broadcast.user_name;
				var broadcast_user=""
				if(user_name!=null)
				{
					broadcast_user=user_name;
				}
				else{
					broadcast_user="";
				}
				var active_status=broadcast.active;
				var active_selected = "";
				var in_active_selected = "";
				var selected_text = "";
				var selected_class = "";
				if(active_status == 1) {
					active_selected = "selected";
					selected_text = "Active";
					selected_class = "badge-success";
				} else if(status == 0) {
					in_active_selected = "selected";
					selected_text = "In-Active";
					selected_class = "badge-warning";
				} 
					call_back(`<tr role="row" class="odd">			
				    <td>`+broadcast.tittle+`</td>
				    <td>`+broadcast.message_type+`</td>
				    <td>`+broadcast_module+`</td>
				    <td>`+broadcast_organization+`</td>
				    <td>`+broadcast_user+`</td>
					<td>
						<label class="grid_label badge `+selected_class+` status">`+selected_text+`</label>
						<select style="display:none" id="`+broadcast.id+`"  class=" form-control active_status">
							<option `+active_selected+` value="1">Active</option>
							<option `+in_active_selected+`value="0">In-Active</option>
						</select>
					
					</td>		
					<td>
						<a data-id="`+broadcast.id+`" class="action-btn grid_label edit-icon edit"><i class="fa li_pen"></i></a>&nbsp;
					<a data-id="`+broadcast.id+`" class="action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a>
					</td>
					</tr>`,`edit`,data.message,broadcast.id);
					$('.loader_wall_onspot').hide();
				},
			error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
		}
	});;
	
</script>





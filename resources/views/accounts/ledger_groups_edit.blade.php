<div class="modal-header">
  <h4 class="modal-title float-right">Edit Ledger Group</h4>
</div>
{!!Form::model($ledger_group, ['class' => 'form-horizontal validateform'])!!}
										
							{{ csrf_field() }}
<div class="modal-body">
  <div class="form-body"> {!! Form::hidden('id', null) !!}
    <div class="form-group"> {!! htmlspecialchars_decode(Form::label('Name', 'Name', array('class' => 'control-label col-md-3 required'))) !!}
      <div class="col-md-12">{!! Form::text('name', null, ['class'=>'form-control', 'placeholder'=>'Name','id'=>'name']) !!} </div>
    </div>
    <div class="form-group"> {!! htmlspecialchars_decode(Form::label('parent_id', 'Parent', array('class' => 'control-label col-md-3 required'))) !!}
      <div class="col-md-12"> {!! Form::select('parent_id', $ledger_group_list, $ledger_group->parent_id, ['class' => 'select_item form-control','id' => 'parent_id']) !!} </div>
    </div>
    <div class="form-group"> {!! Form::label('account_head','Account Head', ['class' => 'col-md-3 control-label']) !!}
      <div class="col-md-12"> {!! Form::text('account_head', null, ['class'=>'form-control', 'disabled']) !!} </div>
    </div>
    <div class="form-group"> {!! htmlspecialchars_decode(Form::label('account_ledger_group_types', 'Ledger Group Type', array('class' => 'control-label col-md-12 required'))) !!}
      <div class="col-md-12"> @foreach($ledger_types as $ledger_type)
        <input id="group_{{$ledger_type->id}}" type="checkbox" name="account_ledger_group_types[]" 
										<?php if(in_array($ledger_type->id, $selected_ledger_types)) { echo 'checked="checked"'; } ?>value="{{$ledger_type->id}}">
         <label for="group_{{$ledger_type->id}}"><span></span>{{ $ledger_type->display_name }}</label> &nbsp;&nbsp;
        @endforeach </div>
    </div>
  </div>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
  <button type="submit" class="btn btn-success">Submit</button>
</div>
{!! Form::close() !!} 
<script type="text/javascript">
$(document).ready(function() {


		$('select[name=parent_id]').on('change', function()
		{
			var value = $(this).val();			

			if(value == "") 
			{
				$('input[name=account_head]').val("");
			} 
			else if(value != "") 
			{
				$('.loader_wall_onspot').show();
				$.ajax({
				 url: "{{ route('parent_group') }}",
				 type: 'get',
				 data: {
				 	id: value
					},
				 dataType: "json",
					success:function(data, textStatus, jqXHR) {
						console.log(data);
						$('input[name=account_head]').val(data[0].display_name);					
						$('.loader_wall_onspot').hide();
					},
				 error:function(jqXHR, textStatus, errorThrown) {
					//alert("New Request Failed " +textStatus);
					}
				});
			}
		});
		
	});

		$('.validateform').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input

            rules: {
                name: {
                    required: true
                },
                parent_id: {
                    required: true
                },
                'account_ledger_group_types[]': {
                    required: true
                }
            },

            messages: {
                name: {
                    required: "Ledger Group Name is required."
                },
                parent_id: {
                    required: "Parent Ledger is required."
                },
                'account_ledger_group_types[]': {
                    required: "Ledger Group Types is required."
                }
            },
            

            invalidHandler: function(event, validator) { //display error alert on form submit   
                $('.alert-danger', $('.login-form')).show();
            },

            highlight: function(element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },

            success: function(label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },

            submitHandler: function(form) {
            	$('.loader_wall_onspot').show();
				$.ajax({
				 url: "{{ route('group.update') }}",
				 type: 'post',
				 data: {
				 	_token: '{{ csrf_token() }}',
				 	_method: 'PATCH',
				 	id: $('input[name=id]').val(),
				 	name: $('input[name=name]').val(),
				 	parent_id: $('select[name=parent_id]').val(),
				 	account_ledger_group_types: $("input[name='account_ledger_group_types[]']:checked").map(function() { 
                    	return this.value; 
                	}).get()
					},
				 dataType: "json",
					success:function(data, textStatus, jqXHR) {

						var active_selected = "";
						var inactive_selected = "";
						var selected_text = "In-Active";
						var selected_class = "badge-warning";

						if(data.data.status == 1) {
							active_selected = "selected";
							selected_text = "Active";
							selected_class = "badge-success";
						} else if(data.data.status == 0) {
							inactive_selected = "selected";
						}

						var active_approve_selected = "";
						var inactive_approve_selected = "";
						var selected_approve_text = "Not Approved";
						var selected_approve_class = "badge-warning";

						if(data.data.approval_status == 1) {
							active_approve_selected = "selected";
							selected_approve_text = "Approved";
							selected_approve_class = "badge-info";
						} else if(data.data.status == 0) {
							inactive_approve_selected = "selected";
						}

						call_back(`<tr role="row" class="odd">
							<td><input id="`+data.data.id+`" class="item_check" name="team" value="`+data.data.id+`" type="checkbox">
                    	<label for="`+data.data.id+`"><span></span></label>
                    </td>
							<td class="sorting_1">`+data.data.name+`</td>
							<td>`+$('select[name=parent_id] option:selected').text()+`</td>
							<td>
								<label class="grid_label badge `+selected_approve_class+` status">`+selected_approve_text+`</label>
								<select style="display:none" id="`+data.data.id+`" class="approval_status form-control">
									<option `+active_approve_selected+` value="1">Approved</option>
									<option `+inactive_approve_selected+` value="0">Not Approved</option>
								</select>
							</td>
							<td>
								<label class="grid_label badge `+selected_class+` status">`+selected_text+`</label>
								<select style="display:none" id="`+data.data.id+`" class="active_status form-control">
									<option `+active_selected+` value="1">Active</option>
									<option `+inactive_selected+` value="0">In-Active</option>
								</select>
							</td>
							<td>
							<a data-id="`+data.data.id+`" class="action-btn grid_label edit-icon edit"><i class="fa li_pen"></i></a>&nbsp;
							<a data-id="`+data.data.id+`" class="action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a>
							</td></tr>`, `edit`, data.message, data.data.id);		
						$('.loader_wall_onspot').hide();
					},
				 error:function(jqXHR, textStatus, errorThrown) {
					//alert("New Request Failed " +textStatus);
					}
				});
            }
        });
	</script> 

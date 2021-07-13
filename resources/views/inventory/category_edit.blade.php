<div class="modal-header">
											<h4 class="modal-title float-right">Edit Add @if($category == "category") Category @elseif($category == "division") Division @endif</h4>
										</div>


							{!!Form::model($inventory_categories, ['class' => 'form-horizontal validateform'])!!}
										
							{{ csrf_field() }}
<div class="modal-body">
							<div class="form-body">
									{!! Form::hidden('id', null) !!}
								@if($category == "category")
								<div class="form-group">
									{{ Form::label('category_type_id', 'Category Type', array('class' => 'control-label col-md-12 required')) }}
							
									<div class="col-md-12">
									<div class="row">
									@foreach($inventory_types as $type) 
									<div class="col-md-4">
							  		<input type="radio" name="category_type_id" id="{{$type->name}}" value="{{$type->id}}" @if ($type->id == $inventory_categories->category_type_id) checked @endif/>
							  		<label for="{{$type->name}}"><span></span>{{$type->display_name}}</label>
							  		</div>
							  		@endforeach
							  	    </div>
							  	    </div>
								</div>
								@endif
									<div class="form-group">	
										{{ Form::label('Category', 'Category', array('class' => 'control-label col-md-3 required')) }}
										<div class="col-md-12">{!! Form::text('name', null, ['class'=>'form-control', 'placeholder'=>'Category','id'=>'name']) !!}
										</div>
									</div>

							<div class="form-group">									
								<div class="col-md-12">
								<?php $check_status = false;
								 if($inventory_categories->parent_id != null) $check_status = true; ?>
								{{ Form::checkbox('parent_name', '1', $check_status, array('id' => 'sub_category')) }} <label for="sub_category"><span></span>Is @if($category == "category") sub-category @elseif($category == "division") sub-division @endif</label>
								</div>
							</div>
		
							<div class="form-group parentname" @if(!$inventory_categories->parent_id) style="display:none" @endif>
								{{ Form::label('parent_id', 'Parent Category', array('class' => 'control-label col-md-12 required')) }}
								<div class="col-md-12">
								{!! Form::select('parent_id', $inventory_category, null, ['class' => 'select_item form-control','id' => 'parent_id']) !!}
								</div>
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

	$('input[type="checkbox"]').on('change', function() {
		    if($(this).is(":checked")) {
		        $(".parentname").show();
		    } 
		    else {
			    $(".parentname").hide();
			    $('select[name=parent_id]').val('');
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
                }
            },

            messages: {
                name: {
                    required: "Inventory Category is required."
                },
                parent_id: {
                    required: "Parent Category is required."
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
				 url: '{{ route('category.update') }}',
				 type: 'post',
				 data: {
				 	_token: '{{ csrf_token() }}',
				 	_method: 'PATCH',
				 	id: $('input[name=id]').val(),
				 	name: $('input[name=name]').val(),
				 	category_type_id: $('input[name=category_type_id]:checked').val(),
				 	parent_id: $('select[name=parent_id]').val(),
				 	
					},
				 dataType: "json",
					success:function(data, textStatus, jqXHR) {

						var active_selected = "";
						var inactive_selected = "";
						var selected_text = "In-Active";
						var selected_class = "badge-warning";

						var parent_name = ($('select[name=parent_id] option:selected').val() == "") ? '' : $('select[name=parent_id] option:selected').text();

						if(data.data.status == 1) {
							active_selected = "selected";
							selected_text = "Active";
							selected_class = "badge-success";
						} else if(data.data.status == 0) {
							inactive_selected = "selected";
						}

					

						call_back(`<tr role="row" class="odd">
							<td>
	                            <input id="`+data.data.id+`" class="item_check" name="discount" value="`+data.data.id+`" type="checkbox">
	                            <label for="`+data.data.id+`"><span></span></label>
	                        </td>
							<td class="sorting_1">`+data.data.name+`</td>
							<td>`+parent_name+`</td>
							
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

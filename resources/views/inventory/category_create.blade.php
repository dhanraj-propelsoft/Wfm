
<div class="modal-header">
											<h4 class="modal-title float-right">Add @if($category == "category") Category @elseif($category == "division") Division @endif</h4>
										</div>

							{!! Form::open([
								'class' => 'form-horizontal validateform'
							]) !!}
										
							{{ csrf_field() }}
<div class="modal-body">
							<div class="form-body">
							@if($category == "category")
								<div class="form-group">
									{{ Form::label('category_type_id', 'Category Type', array('class' => 'control-label col-md-12 required')) }}
							
									<div class="col-md-12">
									<div class="row">
									@foreach($inventory_types as $type) 
									<div class="col-md-4">
							  		<input type="radio" name="category_type_id" id="{{$type->name}}" value="{{$type->id}}" />
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
										{{ Form::checkbox('parent_name', '1', null, array('id' => 'sub_category')) }} <label for="sub_category"><span></span>Is @if($category == "category") sub-category @elseif($category == "division") sub-division @endif</label>
									</div>
									</div>

									<div class="form-group parentname">
										{{ Form::label('parent_id', 'Parent Category', array('class' => 'control-label col-md-12 required')) }}

										<div class="col-md-12">
										{!! Form::select('parent_id', $inventory_category, null, ['class' => 'select_item form-control parentname', 'id' => 'parent_id']) !!}
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

	$(".parentname").hide();

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
                    required: "Inventory Category Name is required."
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
				 url: '{{ route('category.store') }}',
				 type: 'post',
				 data: {
				 	_token: '{{ csrf_token() }}',
				 	name: $('input[name=name]').val(),
				 	category_type_id: $('input[name=category_type_id]:checked').val(),
				 	parent_id: $('select[name=parent_id]').val(),				 	
                	},
					
				 dataType: "json",
					success:function(data, textStatus, jqXHR) {
						var parent_name = ($('select[name=parent_id] option:selected').val() == "") ? '' : $('select[name=parent_id] option:selected').text();

						call_back(`<tr>
								<td>
		                            <input id="`+data.data.id+`" class="item_check" name="discount" value="`+data.data.id+`" type="checkbox">
		                            <label for="`+data.data.id+`"><span></span></label>
		                        </td>
				            	<td>`+data.data.name+`</td>							
								<td>`+parent_name+`</td>
				                
				                <td>
										
									<label class="grid_label badge badge-success status">Active</label>
									<select style="display:none" id="`+data.data.id+`" class="active_status form-control">
									<option value="1">Active</option>
									<option value="0">In-active</option>
									</select>
								</td>
								<td>
								<a data-id="`+data.data.id+`" class="action-btn grid_label edit-icon edit"><i class="fa li_pen"></i></a>&nbsp;
								<a data-id="`+data.data.id+`" class="action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a>
								</td>
				            </tr>`, `add`, data.message);					
						$('.loader_wall_onspot').hide();
					},
				 error:function(jqXHR, textStatus, errorThrown) {
					//alert("New Request Failed " +textStatus);
					}
				});
            }
        });
	</script>

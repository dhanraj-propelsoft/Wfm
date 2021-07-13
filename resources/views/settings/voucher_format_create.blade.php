
<div class="modal-header">
  <h4 class="modal-title float-right">Add Voucher Format</h4>
</div>
{!! Form::open(['class' => 'form-horizontal validateform']) !!}
										
	{{ csrf_field() }}

<div class="modal-body">
  <div class="form-body">
    <div class="form-group"> {{ Form::label('name', 'Format Name', array('class' => 'control-label col-md-5 required')) }}
      <div class="col-md-12">{!! Form::text('name', null, ['class'=>'form-control','id'=>'name']) !!} </div>
    </div>
    <div class="form-group"> {{ Form::label('icon',' Separator Symbol', ['class' => 'control-label col-md-5 required']) }}
      <div class="col-md-12"> {!! Form::select('icon', ["/" => "/","." => ".","-" => "-"], null, ['class' => ' form-control select_item', 'placeholder' => 'Select Separator']) !!} </div>
    </div>
    <div class="form-group"> {{ Form::label('separator_count','Number of separators', ['class' => 'control-label col-md-5 required']) }}
      <div class="col-md-12">
      {!! Form::selectRange('separator_count', 1, 3, 1, ['class' => 'form-control select_item' ]) !!}
      </div>
    </div>
    <div class="form-group separator"> 
    	{{ Form::label('separator','Separator 1',['class' => 'control-label col-md-5']) }}
	  <div class="col-md-12"> 
	 <select name='separator' class='form-control separations select_item'>
	 <option value="">Select Separator</option>
	 @foreach($separators as $separator) 
			<option value="{{$separator->id}}" data-name="{{$separator->name}}">{{$separator->display_name}}</option>
	 @endforeach
	 </select>
	  </div>

      <div class="form-group preceding" style="display: none"> {{ Form::label('preceding_zeros','Preceding', ['class' => 'control-label col-md-5']) }}
	      <div class="col-md-12">
	      	{!! Form::selectRange('preceding_zeros', 1, 5, null, ['class' => 'form-control select_item', 'placeholder' => 'Select Separator']) !!}
	      </div>
      </div>
    </div>
    <div class="separator_list"></div>

    <div class="form-group">
		 {{ Form::label('','Preview',['class' => 'col-md-3 control-label']) }}
		<div class="col-md-12">
		{!! Form::label('preview','',['class' => 'form-control preview', 'style' => 'font-size:12px; padding:5px;', 'disabled']) !!}
		</div>
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
	basic_functions();


		$('select[name=separator_count]').on('change', function()
		{ 
			var separator_count =  $('select[name=separator_count]').val();
			$('.separator').not(':first').remove();

				for(i=1;i<separator_count;i++) {

					var last_select = $(".separator:last").find('select');
					var last_selected = last_select.find(':selected').val();

					$('.modal-body .select_item').each(function() { 
						var select = $(this);  
						if(select.data('select2')) { 
							select.select2("destroy"); 
						} 
					});

                	var last_clone = $(".separator:last").clone(true);
                	last_clone.appendTo(".separator_list");
                	$(".separator:last").find('label:first').text("separator "+(i+1));

                	$('.select_item').select2();
                	
				}
		});




		
		$("select[name='separator'], select[name=icon]").on('change', function()
		{
	
			
			var obj = $(this);
			obj.closest('.separator').find('.preceding').val('0');
			obj.closest('.separator').find('.preceding').hide();
			var preceding = $('select[name=preceding_zeros]').val();

			//separate (1, obj);

			separate (pad (1, parseInt(preceding)+1), $(this));
			
		});


		$("select[name=preceding_zeros]").on('change', function()
		{
		var preceding = $('select[name=preceding_zeros]').val();

			separate (pad (1, parseInt(preceding)+1), $(this));
		
		});

	
	});
function separate (auto_gen, obj) {
	var values = [];
	var separator = $("select[name=icon]").val();
	obj.closest('.separator').find('.preceding').val('0');
	obj.closest('.separator').find('.preceding').hide();
	$("select[name='separator']").each(function(){

				if($(this).val() != '')	{
					var separator_option = $(this).find('option:selected').data('name');

					switch(separator_option) {
						case 'auto_number':
							separator_option = auto_gen;
							obj.closest('.separator').find('.preceding').show();
							
						break;
					
						case 'financial_year':
							separator_option = new Date().getFullYear();
						break;
						case 'voucher_code':
							separator_option = 'AA';
						break;
					}
	    		values.push(separator_option);
				}					

				
			});

			$('.preview').text(values.join(separator));
}


function pad (str, max) {
      str = str.toString();
      return str.length < max ? pad("0" + str, max) : str;
    }


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
				 url: "{{ route('voucher_format.store') }}",
				 type: 'post',
				 data: {
				 	_token: '{{ csrf_token() }}',
				 	name: $('input[name=name]').val(),
				 	icon: $('select[name=icon]').val(),
				 	separator:$('select[name=separator]').map(function() { 
                        return this.value; 
                    }).get(),
                    preceding_zeros: $('select[name=preceding_zeros]').val(),				 	
                	},
					
				 dataType: "json",
					success:function(data, textStatus, jqXHR) {
						

						call_back(`<tr>
				            	<td>`+data.data.name+`</td>							
				                
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

<div class="modal-header">
    <h4 class="modal-title float-right">Add Task Substatus</h4>
</div>

{!! Form::open(['class' => 'form-horizontal validateform']) !!}
{{ csrf_field() }}

    <div class="modal-body">
        <div class="form-body">
          <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                    {!! Form::label('name', 'Task Status Name', ['class' => ' control-label required']) !!}
                
                    {!! Form::select('task_status_name', $Taskstatus_list, null, ['class' => 'form-control select_item payment', 'style' => 'width: 100%']); !!}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                    {!! Form::label('name', 'Task Substatus Name', ['class' => ' control-label required']) !!}
                
                    {!! Form::text('task_substatus_name', null, array('class' => 'form-control')) !!}
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
                                
<script>
$(document).ready(function()
{
    basic_functions();


    $('.validateform').validate({
        errorElement: 'span', //default input error message container
        errorClass: 'help-block', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        rules: {
              
            task_substatus_name: { required: true  },
            task_status_name: {
                     required: true
                     }
                },

        messages: {
            task_substatus_name:{required:"Task Substatus Name is rquired"},
            task_status_name:{required:"Task Status Name is rquired"}
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
                url: '{{ route('tasksubstatus.store') }}',
                type: 'post',
                data: {
                    _token: '{{ csrf_token() }}',
                    task_substatus_name: $('input[name=task_substatus_name]').val(),
                    task_status_id:$('input[taskname=task_status_id').val(),
                },
                success:function(data, textStatus, jqXHR) {
                    if(data.status==0)
                    {
                        
                          $('.loader_wall_onspot').hide();
                         input='input[name=task_substatus_name]';
                         input='input[name=task_status_id]';
                    field_name="task substatus Name";
                         error_msg(input,field_name);
                         return false;
                    }
                    call_back(`<tr role="row" class="odd">
                      
                        <td>`+data.data.name+`</td>
                        <td>`+data.data.name+`</td>
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
                        </td></tr>`, `add`, data.message);

                    $('.loader_wall_onspot').hide();

                },
                error:function(jqXHR, textStatus, errorThrown) {
                    //alert("New Request Failed " +textStatus);
                }
            });
        }
    });
});
</script>

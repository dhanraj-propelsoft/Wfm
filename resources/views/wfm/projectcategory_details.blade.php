<div class="alert alert-success">
    {{ Session::get('flash_message') }}
</div>


<div class="alert alert-danger">
        @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
</div>
<div class="modal-header">
    <h4 class="modal-title float-right">Department</h4>
</div>

{!! Form::open(['class' => 'form-horizontal validateform']) !!}
{{ csrf_field() }}

    <div class="modal-body">
        <div class="form-body">
            {!! Form::hidden('pId', $data->pId, array('class' => 'form-control')) !!}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">

                    {!! Form::label('pWfmDepartment', 'Department', ['class' => ' control-label required']) !!}
                
                    {!! Form::text('pWfmDepartment', $data->pWfmDepartment, array('class' => 'form-control')) !!}
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
            pWfmDepartment: { required: true  },
           
        },

        messages: {
            pWfmDepartment: { required: "Department Name is required."},
          
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
            var id = $('input[name=pId]').val();
            var type = 'post';
            if($('input[name=pId]').val() == ''){
               var url = "{{ route('projectcategory.store') }}";
            }else{
               var url = "{{ url('wfm/projectcategory') }}/"+id;
               type = "PUT";
            }
         
            $.ajax({ 
                url: url,
                type: type,
                data: {
                    _token: '{{ csrf_token() }}',
                    pWfmDepartment: $('input[name=pWfmDepartment]').val(),
            
                },
                success:function(data, textStatus, jqXHR) {
                   var response = data;
                    if(response.message == "Failed")
                    {
                        
                          // $('.loader_wall_onspot').hide();
                         input='input[name=pWfmDepartment]';
                         field_name="Department Name";
                         error_msg(input,field_name);
                         $('.crud_modal').modal('show');
                         return false;
                    }
                    if(response.data != ""){
                    call_back(`<tr role="row" class="odd">
                        <td>`+response.data.wfm_department+`</td>
                        <td>
                            <label class="grid_label badge badge-success status">Active</label>
                            <select style="display:none" id="`+response.data.id+`" class="active_status form-control">
                            <option value="1">Active</option>
                            <option value="0">In-active</option>
                            </select>
                        </td>                           
                        <td>
                        <a data-id="`+response.data.id+`" class="action-btn grid_label edit-icon edit"><i class="fa li_pen"></i></a>&nbsp;
                        <a data-id="`+response.data.id+`" class="action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a>
                        </td></tr>`, `add`, response.message,response.data.id);
                }
                else
                {
                   
                    alert_message("Department is Already is Exist!!!",'error');
                    $('input[name=pWfmDepartment]').val('');
                    
                }
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

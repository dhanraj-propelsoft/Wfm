<div class="modal-header">
    <h4 class="modal-title float-right">Edit Size</h4>
</div>

{!! Form::model($Size, ['class' => 'form-horizontal validateform']) !!}
{{ csrf_field() }}

    <div class="modal-body">
        <div class="form-body">
            {{Form::hidden('id',null)}}

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                    {!! Form::label('name', 'Size Name', ['class' => ' control-label required']) !!}
                
                    {!! Form::text('size_name', null, array('class' => 'form-control')) !!}
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
            
size_name: {
                required: true
            },
        },

        size_name: {
            name: {
                required: "Size is required.."
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
                url: '{{ route('size.update') }}',
                type: 'post',
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'PATCH',
                    id: $('input[name=id]').val(),
                    size_name: $('input[name=size_name]').val(),
                                
                },
                success:function(data, textStatus, jqXHR) {

                    var active_selected = "";
                    var inactive_selected = "";
                    var selected_text = "In-Active";
                    var selected_class = "badge-warning";
                     if(data.status==2)
                    {
                         $('.loader_wall_onspot').hide();
                         input='input[name=size_name]';
                         field_name="Size Name";
                         error_msg(input,field_name);
                         return false;
                    }

                    if(data.data.status == 1) {
                        active_selected = "selected";
                        selected_text = "Active";
                        selected_class = "badge-success";
                    } else if(data.data.status == 0) {
                        inactive_selected = "selected";
                    }
                    //console.log(data.data);return false;
                       call_back(`<tr role="row" class="odd">
                        
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
                        </td></tr>`, `edit`, data.message,data.data.id);

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

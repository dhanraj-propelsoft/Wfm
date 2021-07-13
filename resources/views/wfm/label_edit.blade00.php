<div class="modal-header">
    <h4 class="modal-title float-right">Edit Label</h4>
</div>
 <div class="alert alert-danger label_errror" style="display: none">
        <ul class="error_li">
               
        </ul>
    </div>
<?php// print_r($Label);exit; ?>
{!! Form::model($Label, ['class' => 'form-horizontal validateform']) !!}
{{ csrf_field() }}
    <div class="modal-body">
        <div class="form-body">
            {{Form::hidden('id',null)}}

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                    {!! Form::label('name', 'Pharse', ['class' => ' control-label required']) !!}
                
                     {!! Form::text('phrase', null, array('class' => 'form-control')) !!}
                    
                    </div>
                    <div class="form-group">
                    {!! Form::label('name', 'Label name', ['class' => ' control-label required']) !!}
                
                    {!! Form::text('label_name', null, array('class' => 'form-control')) !!}

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
            
            pharse: {
                required: true
            },
        },

        pharse: {
            name: {
                required: "Pharse  is required."
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
                url: '{{ route('label.update') }}',
                type: 'post',
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'PATCH',
                    id: $('input[name=id]').val(),
                    phrase: $('input[name=phrase]').val(),
                    label_name: $('input[name=label_name]').val(),
                                
                },
                success:function(data, textStatus, jqXHR) {

                    var active_selected = "";
                    var inactive_selected = "";
                    var selected_text = "In-Active";
                    var selected_class = "badge-warning";
                    if(data.status==0)
                    {

                        $('.loader_wall_onspot').hide();
                         custom_validator_msg(data.message);
                         $(".label_errror").css("display","block");
                         return false;
                    }
                    

                    if(data.data.status == 1) {
                        active_selected = "selected";
                        selected_text = "Active";
                        selected_class = "badge-success";
                    } else if(data.data.status == 0) {
                        inactive_selected = "selected";
                    }

                    call_back(`<tr role="row" class="odd">
                        
                        <td>`+data.data.pharse+`</td>
                        <td>`+data.data.label_name+`</td>
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

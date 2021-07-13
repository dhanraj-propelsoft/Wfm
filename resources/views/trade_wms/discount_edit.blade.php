<div class="modal-header">
    <h4 class="modal-title float-right">Edit Discount</h4>
</div>

{!! Form::model($discount, ['class' => 'form-horizontal validateform']) !!}
{{ csrf_field() }}

    <div class="modal-body">
        <div class="form-body">
            {{Form::hidden('id',null)}}

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                    {!! Form::label('name', 'Name', ['class' => ' control-label required']) !!}
                
                    {!! Form::text('name', null, array('class' => 'form-control')) !!}
                    </div>
                </div>
            </div>

            <div class="row" >
                <div class="col-md-12">
                    <div class="form-group">
                    {!! Form::label('value', 'Discount Value', array('class' => 'control-label required')) !!}
                    {!! Form::text('value', null,['class' => 'form-control']) !!}
                    </div>
                </div>              
            </div>
            <div class="row" >
                <div class="col-md-12">
                    <div class="form-group">
                    {!! Form::checkbox('is_percent','1', null, ['id' => 'is_percent']) !!} 
                    <label for="is_percent"><span></span>Is Percent</label>
                    </div>
                </div>              
            </div>
            <div class="row">
                <div class="col-md-6">              
                    <input name="is_sales" id="is_sales" type="checkbox" value="1" 
                    @if ($discount->is_sales != 0) checked='checked' @endif />
                    <label for="is_sales"><span></span>Sales</label>
                </div>
                <div class="col-md-6">
                    <input name="is_purchase" id="is_purchase" type="checkbox" value="1"
                    @if ($discount->is_purchase != 0) checked='checked' @endif />              
                    <label for="is_purchase"><span></span>Purchase</label>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6" >
                    <div class="form-group sales_ledgers" @if($discount->sales_ledger_id ==null)style="display: none; @endif">
                        {!! Form::label('sales_ledger_id', 'Sales Accounts', array('class' => 'control-label')) !!}

                        {!! Form::select('sales_ledger_id',$sales_ledgers,null,['class' => 'form-control select_item']) !!}
                    </div>
                </div>
                <div class="col-md-6 " >
                    <div class="form-group purchase_ledgers" @if($discount->purchase_ledger_id ==null)style="display: none; @endif">
                        {!! Form::label('purchase_ledger_id','Purchase Accounts', array('class' => 'control-label')) !!}

                        {!! Form::select('purchase_ledger_id',$purchase_ledgers,null,['class' => 'form-control select_item']) !!}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                    {!! Form::label('description', 'Description', ['class' => 'control-label']) !!}             
                    {!! Form::textarea('description', null, array('class' => 'form-control','rows'=>'3 ','cols'=>'40')) !!}
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

    $("input[name=is_sales]").on('change', function(){
        if(!$(this).is(':checked'))
        {           
            $("select[name=sales_ledger_id]").val('');
        }
    });

    $("input[name=is_purchase]").on('change', function(){
        if(!$(this).is(':checked'))
        {           
            $("select[name=purchase_ledger_id]").val('');
        }
    });

    $("input[name = is_sales]").on('change', function(){
        if($(this).is(':checked'))
        {
            $('.sales_ledgers').show();
        }
        else {
            $('.sales_ledgers').hide();
        }
    });

    $("input[name = is_purchase]").on('change', function(){
        if($(this).is(':checked'))
        {           
            $('.purchase_ledgers').show();
        }
        else {
            $('.purchase_ledgers').hide();
        }
     });

    $('.validateform').validate({
        errorElement: 'span', //default input error message container
        errorClass: 'help-block', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        rules: {
            name: {
                required: true
            },
            value: {
                required: true
            }
        },

        messages: {
            name: {
                required: "Name is required."
            },
            value: {
                required: "Tax Value is required."
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
                url: '{{ route('discount.update') }}',
                type: 'post',
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'PATCH',
                    id: $('input[name=id]').val(),
                    name: $('input[name=name]').val(),
                    value: $('input[name=value]').val(),
                    is_percent: $('input[name=is_percent]:checked').val(),
                    is_sales: $('input[name=is_sales]:checked').val(),
                    is_purchase: $('input[name=is_purchase]:checked').val(),
                    sales_ledger_id: $('select[name=sales_ledger_id]').val(),
                    purchase_ledger_id: $('select[name=purchase_ledger_id]').val(),
                    description: $('textarea[name=description]').val(),               
                },
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

                    call_back(`<tr role="row" class="odd">                        
                        <td>
                            <input id="`+data.data.id+`" class="item_check" name="discount" value="`+data.data.id+`" type="checkbox">
                            <label for="`+data.data.id+`"><span></span></label>
                        </td>
                        <td>`+data.data.name+`</td>
                        <td>`+data.data.value+`</td>
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

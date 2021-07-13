@extends('layouts.master')
@section('head_links') @parent
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
@stop
@include('includes.trade')
@section('content')

<div class="alert alert-success">
    {{ Session::get('flash_message') }}
</div>



@if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif
<div class="modal-header">
    <h4 class="modal-title float-right">Add People</h4>
</div>


                                        {!! Form::open([
                                            'class' => 'form-horizontal validateform'
                                        ]) !!} 
                                        {{ csrf_field() }}
<div class="modal-body">

                                <div class="form-body">

                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                {!! Form::label('title', 'Title', ['class' => 'control-label  ']) !!}
                                                
                                                {!! Form::select('title',$title, null, ['class' => 'select_item form-control' ,'id'=> 'state' ]) !!}
                                            </div>
                                        </div> 
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                {!! Form::label('first_name', 'First Name', ['class' => 'control-label ']) !!}
                                                
                                                {!! Form::text('first_name', null, ['class' => 'form-control ']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                {!! Form::label('last_name', 'Last Name', ['class' => 'control-label ']) !!}
                                                
                                                {!! Form::text('last_name', null, ['class' => 'form-control ']) !!}
                                            </div>
                                        </div>                                       
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {!! Form::label('display_name', 'Display Name', ['class' => 'control-label']) !!}

                                                {!! Form::text('display_name', null, ['class' => 'form-control']) !!}
                                            </div>
                                        </div>                                  
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {!! Form::label('mobile_no', 'Mobile Number', ['class' => 'control-label']) !!}

                                                {!! Form::text('mobile_no', null, ['class' => 'form-control']) !!}
                                            </div>
                                        </div>                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {!! Form::label('phone', 'Phone', ['class' => 'control-label']) !!}

                                                {!! Form::text('phone', null, ['class' => 'form-control']) !!}
                                            </div>
                                        </div>                                  
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {!! Form::label('email_address', 'Email', ['class' => 'control-label']) !!}

                                                {!! Form::text('email_address', null, ['class' => 'form-control numbers']) !!}
                                            </div>
                                        </div>                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {!! Form::label('web_address', 'Web Address', ['class' => 'control-label']) !!}

                                                {!! Form::text('web_address', null, ['class' => 'form-control']) !!}
                                            </div>
                                        </div>                                  
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {!! Form::label('pan', 'PAN', ['class' => 'control-label']) !!}

                                                {!! Form::text('pan', null, ['class' => 'form-control numbers']) !!}
                                            </div>
                                        </div>                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {!! Form::label('gst', 'GST', ['class' => 'control-label']) !!}

                                                {!! Form::text('gst', null, ['class' => 'form-control']) !!}
                                            </div>
                                        </div>                                  
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                {!! Form::label('address', 'Address', ['class' => 'control-label']) !!}

                                                {!! Form::textarea('address', null, ['class' => 'form-control','rows'=>'3 ','cols'=>'40']) !!}
                                            </div>
                                        </div>
                                     </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {!! Form::label('state', 'State', ['class' => 'control-label']) !!}

                                                {!! Form::select('state',$state, null, ['class' => 'select_item form-control' ,'id'=> 'state' ]) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {!! Form::label('web_address', 'Web Address', ['class' => 'control-label']) !!}

                                                {!! Form::select('state',$city, null, ['class' => 'select_item form-control' ,'id'=> 'city' ]) !!}
                                            </div>
                                        </div>                                  
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {!! Form::label('pin', 'Postal Code', ['class' => 'control-label']) !!}

                                                {!! Form::text('pin',null, ['class' => 'form-control']) !!}
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

@stop

@section('dom_links')
@parent
                                
<script>

$('.validateform').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                name: {
                    required: true
                },
                display_name: {
                    required: true
                }
            },

            messages: {
                name: {
                    required: "Name is required."
                },
                display_name: {
                    required: "Display Name is required."
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

            errorPlacement: function(error, element) {
                error.insertAfter(element.closest('.input-group'));
            },

            submitHandler: function(form) {

                $('.loader_wall_onspot').show();

                $.ajax({
                 url: '{{ route('people.store') }}',
                 type: 'post',
                 data: {
                    _token: '{{ csrf_token() }}',
                    title: $('select[name=title]').val(),
                    first_name: $('input[name=first_name]').val(),
                    last_name: $('input[name=last_name]').val(),
                    mobile_no: $('input[name=mobile_no]').val(),
                    phone: $('input[name=phone]').val(),
                    email_address: $('input[name=email_address]').val(),
                    web_address: $('input[name=web_address]').val(),
                    pan: $('input[name=pan]').val(),
                    gst: $('input[name=gst]').val(),
                    address: $('textarea[name=address]').val(),   
                    state_id: $('select[name=state]').val(),
                    city_id: $('select[name=city]').val(),
                    pin: $('input[name=pin]').val(),          
                    },
                 success:function(data, textStatus, jqXHR) {

                    call_back(`<tr role="row" class="odd">
                            <td>`+data.data.title+`</td>
                            <td>`+data.data.name+`</td>
                            <td>`+data.data.first_name+`</td>
                            <td>`+data.data.last_name+`</td>
                            <td>`+data.data.mobile_no+`</td>
                            <td>`+data.data.phone+`</td>
                            <td>`+data.data.email_address+`</td>
                            <td>`+data.data.web_address+`</td>
                            <td>`+data.data.pan+`</td>
                            <td>`+data.data.gst+`</td>
                            <td>`+data.data.address+`</td>
                            <td>`+data.data.state_id+`</td>
                            <td>`+data.data.city_id+`</td>
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
</script>

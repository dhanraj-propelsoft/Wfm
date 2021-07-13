<div class="modal-header">
    <h4 class="modal-title float-right">Add Role</h4>
     <div class="float-right close_full_modal"><i style="font-size: 60px; margin-top: -15px;" class="fa icon-arrows-remove"></i></div>
</div>
        {!! Form::open(['class' => 'form-horizontal validateform']) !!}                                        
        {{ csrf_field() }}

            <div class="modal-body">
                <div class="form-body">

                    <div class="form-group">
                        {!! Form::label('name', 'Name', ['class' => 'col-md-3 control-label required']) !!}
                        <div class="col-md-6">
                        {!! Form::text('name', null, array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('display_name', 'Display Name', ['class' => 'col-md-3 control-label required']) !!}
                        <div class="col-md-6">
                        {!! Form::text('display_name', null, array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('description', 'Description', ['class' => 'col-md-3 control-label']) !!}
                        {!! Form::label('', '&nbsp;&nbsp;', ['class' => 'control-label','style'=>'float:left;']) !!}
                        <div class="col-md-6">
                        {!! Form::textarea('description', null, array('class' => 'form-control','size' => '30x1')) !!}
                        </div>
                    </div>

                    <div class="form-group" style="font-weight: bold;">
                        {!! Form::label('permission', 'Permission Menu', ['class' => 'col-md-3 control-label']) !!}
                    </div>

                    

                    <!--  just hide buttons -->

                    <!-- <div class="form-group">
                        <div class="col-md-6">


                        @foreach($module_name as $module_btn)

                            @if($module_btn == "books")
                                <a href="#accounts"><button type="button" class="btn btn-danger">Books</button></a>
                            @endif

                            @if($module_btn == "hrm")
                                <a href="#hrm"><button type="button" class="btn btn-danger" >HRM</button></a>
                            @endif

                            @if($module_btn == "inventory")
                                <a href="#inventory"><button type="button" class="btn btn-danger" >Inventory</button></a>
                            @endif

                            @if($module_btn == "trade")
                                <a href="#trade"><button type="button" class="btn btn-danger" >Trade</button></a>
                            @endif

                            @if($module_btn == "trade_wms")
                                <a href="#trade_wms"><button type="button" class="btn btn-danger" >WMS</button></a>
                            @endif

                             @if($module_btn == "wfm")

                            <a href="#wfm"><button type="button" class="btn btn-danger" >WFM</button></a>

                            @endif

                            @if($module_btn == "super_admin")
                                <a href="#admin"><button type="button" class="btn btn-danger" >Admin</button></a>
                            @endif

                        @endforeach
                            
                        </div>
                    </div>  -->

                                   
                        
                    <div class="form-group">
                        <div class="row">

                        <?php $val = ""; ?>

                        @foreach($permission as $value)

                            <!-- @if($value->module == "settings_permission" )

                                    <div class="col-md-12">
                                        <span style="border-bottom: 1px solid #ccc; float: left; margin:10px 0; width: 100%; text-transform: capitalize;" id="roles_menu">

                                        <div id="{{$value->module}}">
                                            Settings
                                        </div>
                                        </span>
                                    </div>


                                    <div class="col-md-3">
                                        {{ Form::checkbox('permission[]', $value->id, false, array('class' => 'name', 'style' => 'float:left; padding-right: 10px', 'id' => $value->display_name)) }}

                                        <label for="{{$value->display_name}}" style="white-space:nowrap; overflow: hidden; width: 88%; text-overflow: ellipsis; float: left; padding-left: 5px;"><span></span> {{ $value->display_name }}</label>

                                    </div>

                            @endif -->

                            @foreach($module_name as $module_org)


                                @if($value->module == "books_permission" && $module_org == "books")

                                    <div class="col-md-12">
                                        <span style="border-bottom: 1px solid #ccc; float: left; margin:10px 0; width: 100%; text-transform: capitalize;" id="roles_menu">

                                        <div id="{{$value->module}}">
                                            {{$value->module}}
                                        </div>
                                        </span>
                                    </div>


                                    <div class="col-md-3">
                                        {{ Form::checkbox('permission[]', $value->id, false, array('class' => 'name', 'style' => 'float:left; padding-right: 10px', 'id' => $value->display_name)) }}

                                        <label for="{{$value->display_name}}" style="white-space:nowrap; overflow: hidden; width: 88%; text-overflow: ellipsis; float: left; padding-left: 5px;"><span></span> {{ $value->display_name }}</label>

                                    </div>

                                @endif


                                @if($value->module == "hrm_permission" && $module_org == "hrm")

                                    <div class="col-md-12">
                                        <span style="border-bottom: 1px solid #ccc; float: left; margin:10px 0; width: 100%; text-transform: capitalize;" id="roles_menu">

                                        <div id="{{$value->module}}">
                                            {{$value->module}}
                                        </div>
                                        </span>
                                    </div>


                                    <div class="col-md-3">
                                        {{ Form::checkbox('permission[]', $value->id, false, array('class' => 'name', 'style' => 'float:left; padding-right: 10px', 'id' => $value->display_name)) }}

                                        <label for="{{$value->display_name}}" style="white-space:nowrap; overflow: hidden; width: 88%; text-overflow: ellipsis; float: left; padding-left: 5px;"><span></span> {{ $value->display_name }}</label>

                                    </div>

                                @endif

                                @if($value->module == "wfm_permission" && $module_org == "wfm")

                                    <div class="col-md-12">
                                        <span style="border-bottom: 1px solid #ccc; float: left; margin:10px 0; width: 100%; text-transform: capitalize;" id="roles_menu">

                                        <div id="{{$value->module}}">
                                            {{$value->module}}
                                        </div>
                                        </span>
                                    </div>


                                    <div class="col-md-3">
                                        {{ Form::checkbox('permission[]', $value->id, false, array('class' => 'name', 'style' => 'float:left; padding-right: 10px', 'id' => $value->display_name)) }}

                                        <label for="{{$value->display_name}}" style="white-space:nowrap; overflow: hidden; width: 88%; text-overflow: ellipsis; float: left; padding-left: 5px;"><span></span> {{ $value->display_name }}</label>

                                    </div>

                                @endif

                                @if($value->module == "inventory_permission" && $module_org == "inventory")

                                    <div class="col-md-12">
                                        <span style="border-bottom: 1px solid #ccc; float: left; margin:10px 0; width: 100%; text-transform: capitalize;" id="roles_menu">

                                        <div id="{{$value->module}}">
                                            {{$value->module}}
                                        </div>
                                        </span>
                                    </div>


                                    <div class="col-md-3">
                                        {{ Form::checkbox('permission[]', $value->id, false, array('class' => 'name', 'style' => 'float:left; padding-right: 10px', 'id' => $value->display_name)) }}

                                        <label for="{{$value->display_name}}" style="white-space:nowrap; overflow: hidden; width: 88%; text-overflow: ellipsis; float: left; padding-left: 5px;"><span></span> {{ $value->display_name }}</label>

                                    </div>

                                @endif

                                @if($value->module == "trade_permission" && $module_org == "trade")

                                    <div class="col-md-12">
                                        <span style="border-bottom: 1px solid #ccc; float: left; margin:10px 0; width: 100%; text-transform: capitalize;" id="roles_menu">

                                        <div id="{{$value->module}}">
                                            {{$value->module}}
                                        </div>
                                        </span>
                                    </div>


                                    <div class="col-md-3">
                                        {{ Form::checkbox('permission[]', $value->id, false, array('class' => 'name', 'style' => 'float:left; padding-right: 10px', 'id' => $value->display_name)) }}

                                        <label for="{{$value->display_name}}" style="white-space:nowrap; overflow: hidden; width: 88%; text-overflow: ellipsis; float: left; padding-left: 5px;"><span></span> {{ $value->display_name }}</label>

                                    </div>

                                @endif

                                @if($value->module == "trade_wms_permission" && $module_org == "trade_wms")

                                    <div class="col-md-12">
                                        <span style="border-bottom: 1px solid #ccc; float: left; margin:10px 0; width: 100%; text-transform: capitalize;" id="roles_menu">

                                        <div id="{{$value->module}}">
                                            {{$value->module}}
                                        </div>
                                        </span>
                                    </div>


                                    <div class="col-md-3">
                                        {{ Form::checkbox('permission[]', $value->id, false, array('class' => 'name', 'style' => 'float:left; padding-right: 10px', 'id' => $value->display_name)) }}

                                        <label for="{{$value->display_name}}" style="white-space:nowrap; overflow: hidden; width: 88%; text-overflow: ellipsis; float: left; padding-left: 5px;"><span></span> {{ $value->display_name }}</label>

                                    </div>

                                @endif

                            @endforeach

                        @endforeach


                        
                        @foreach($permission as $value)

                            @if($value->module != $val) 

                                <?php $val = $value->module; ?>                            

                                @foreach($module_name as $module_org)

                                    @if($value->module == $module_org)

                                    <div class="col-md-12"><span style="border-bottom: 1px solid #ccc; float: left; margin:10px 0; width: 100%; text-transform: capitalize;" id="roles_menu">

                                        <div id="{{$value->module}}">
                                            {{$value->module}}
                                        </div>

                                        </span></div> 

                                    @endif

                                @endforeach

                                @if($value->module == "settings")

                                    <div class="col-md-12"><span style="border-bottom: 1px solid #ccc; float: left; margin:10px 0; width: 100%; text-transform: capitalize;" id="roles_menu">

                                        <div id="{{$value->module}}">
                                            {{$value->module}}
                                        </div>

                                        </span></div> 

                                @endif

                                

                            @endif



                            @foreach($module_name as $module_org)

                                @if($value->module== $module_org)

                                    <div class="col-md-3">
                                        {{ Form::checkbox('permission[]', $value->id, false, array('class' => 'name', 'style' => 'float:left; padding-right: 10px', 'id' => $value->display_name)) }}

                                    <label for="{{$value->display_name}}" style="white-space:nowrap; overflow: hidden; width: 88%; text-overflow: ellipsis; float: left; padding-left: 5px;"><span></span> {{ $value->display_name }}</label>

                                    </div>

                                @endif

                            @endforeach

                            @if($value->module == "settings")

                                <div class="col-md-3">
                                    {{ Form::checkbox('permission[]', $value->id, false, array('class' => 'name', 'style' => 'float:left; padding-right: 10px', 'id' => $value->display_name)) }}

                                <label for="{{$value->display_name}}" style="white-space:nowrap; overflow: hidden; width: 88%; text-overflow: ellipsis; float: left; padding-left: 5px;"><span></span> {{ $value->display_name }}</label>

                                </div>

                            @endif

                                                    

                        @endforeach 


                        </div>

                            
                    </div>                  

                </div>

            </div>

            <div class="modal-footer">
                {!! Form::hidden('organization_id', Auth::user()->organization_id, array('placeholder' => 'Display Name','class' => 'form-control')) !!}
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success">Submit</button>
            </div>
            <br><br>

        {!! Form::close() !!}
                                        


     
<script>
$(document).ready(function(){

    basic_functions();
  
          $("a").on('click', function(event) {

            if (this.hash !== "") {
             
              event.preventDefault();
              // Store hash
              var hash = this.hash;
              
              $('html, body').animate({
                scrollTop: $(hash).offset().top
              }, 500, function(){
           
                // Add hash (#) to URL when done scrolling (default click behavior)
                window.location.hash = hash;
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
                 url: '{{ route('roles.store') }}',
                 type: 'post',
                 data: {
                    _token: '{{ csrf_token() }}',
                    name: $('input[name=name]').val(),
                    display_name: $('input[name=display_name]').val(),
                    description: $('textarea[name=description]').val(),
                    permission: $("input[name='permission[]']:checked").map(function() { 
                        return this.value; 
                    }).get()
                    },
                 success:function(data, textStatus, jqXHR) {

                    call_back(`<tr role="row" class="odd">
                        <td><input id="`+data.data.id+`" class="item_check" name="role_check" value="`+data.data.id+`" type="checkbox">
                        <label for="`+data.data.id+`"><span></span></label>
                        </td>
                            <td>`+data.data.name+`</td>
                            <td>`+data.data.display_name+`</td>
                            <td>`+data.data.description+`</td>
                            <td>
                            <a data-id="`+data.data.id+`" class="action-btn grid_label view-icon"><i class="fa fa-eye"></i></a>&nbsp;
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

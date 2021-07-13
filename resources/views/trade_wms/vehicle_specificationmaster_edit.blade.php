<div class="modal-header" style="background-color: #e9ecef;">

	<h5 class="modal-title float-right"><b>Edit Specification</b></h5>
    <a  class="close" data-dismiss="modal">&times;</a>


</div>



{!!Form::model($specification, [

    'class' => 'form-horizontal validateform'

  ]) !!}



  {{ csrf_field() }}



<div class="modal-body" style="overflow-y: scroll;">

	<div class="form-body">

    {!! Form::hidden('id', null) !!}

	<div class="form-row">

    <div class="form-group col-md-6">

      <label for="inputEmail4">Type</label>

      {!! Form::select('type',$type ,$specification->vehicle_type_id, ['class' => 'form-control select_item']) !!}

    </div>

    <div class="form-group col-md-6">

      <label for="specification">Specification</label>

     {!! Form::text('specification',$spec->display_name,['class' => 'form-control']) !!}

    </div>

  </div>

  <div class="form-row">

    <div class="form-group col-md-6">

      <label for="list">List values?</label>

      <select class="form-control" id="" name="list">

        <option value="1">yes</option>

        <option value="0">no</option>

      </select>

    </div>

  </div>

  <div class="form-group">

    <label for="description">Description</label>

    {!! Form::textarea('description', null, ['class' => 'form-control', 'rows'=>'3', 'cols'=>'40']) !!}

  </div>

	</div>

</div>



<div class="modal-footer" style="background-color: #e9ecef;">                                            

	<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>

	<button type="submit" class="btn btn-success">Save</button>

</div>

	

{!! Form::close() !!}

<script>

$('.validateform').validate({

    errorElement: 'span', //default input error message container

    errorClass: 'help-block', // default input error message class

    focusInvalid: false, // do not focus the last invalid input

    rules: {

        specification: {

          required: true

        },  

        type: {

          required: true

        }

      },



      messages: {

        specification: {

          required: "Specification is required."

        },        

        type: {

          required: "Type is required."

        }

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

        url: '{{ route('specification_master.update') }}',

        type: 'post',

        data: {

          _token: '{{ csrf_token() }}',

          _method: 'PATCH',

          id:$('input[name=id]').val(),

          type: $('select[name=type]').val(),

        specification: $('input[name=specification]').val(),

        list: $('select[name=list]').val(),

        description: $('textarea[name=description]').val()                 

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

            <input id="`+data.data.id+`" class="item_check" name="category" value="`+data.data.id+`" type="checkbox">

            <label for="`+data.data.id+`"><span></span></label>

          </td>

          <td>`+data.data.specification+`</td>

          <td>`+data.data.type+`</td>

          <td>`+data.data.description+`</td>

          <td>

                        <label class="grid_label badge `+selected_class+` status">`+selected_text+`</label>

                        <select style="display:none" id="`+data.data.id+`" class="active_status form-control">

                            <option `+active_selected+` value="1">Active</option>

                            <option `+inactive_selected+` value="0">In-Active</option>

                        </select>

                    </td>

          <td>

              <a data-id="`+data.data.id+`" class="action-btn grid_label edit-icon edit"><i class="fa li_pen"></i></a>

              <a data-id="`+data.data.id+`" class="action-btn grid_label delete-icon delete"><i class="fa fa-trash-o"></i></a>

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


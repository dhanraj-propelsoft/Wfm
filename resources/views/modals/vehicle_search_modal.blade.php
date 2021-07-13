<div class="bs-modal-lg modal fade search_business_modal" tabindex="-1" role="basic" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Search Business</h4>
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      {!! Form::open(['class' => 'form-horizontal', 'id' => 'search_business']) !!}
        {{ csrf_field() }}
      <div class="modal-body"> 
      <div class="alert alert-danger" style="margin-bottom: 5px; padding: 5px;" id="errorlist"></div>
        <div class="form-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group"> {!! Form::text('mobile_no', null , ['class' => 'form-control', 'placeholder' => 'Mobile Number']) !!} </div>
            </div>
            <!--/span-->
            <div class="col-md-6">
              <div class="form-group"> {!! Form::text('email_address', null, ['class' => 'form-control', 'placeholder' => 'Email Address']) !!} </div>
            </div>
            <!--/span--> 
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group"> {!! Form::text('gst_no', null , ['class' => 'form-control', 'placeholder' => 'GST Number']) !!} </div>
            </div>
            <!--/span-->
            <div class="col-md-6">
              <div class="form-group"> {!! Form::text('pan_no', null, ['class' => 'form-control', 'placeholder' => 'PAN Number']) !!} </div>
            </div>
            <!--/span--> 
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group"> {!! Form::text('phone_no', null , ['class' => 'form-control', 'placeholder' => 'Phone Number']) !!} </div>
            </div>
            <!--/span-->
            <div class="col-md-6">
              <div class="form-group"> {!! Form::text('web_address', null, ['class' => 'form-control', 'placeholder' => 'Web Address']) !!} </div>
            </div>
            <!--/span--> 
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-danger">Search</button>
      </div>
      {!! Form::close() !!}
        
        <table style="display: none;" class="table result">
          <tbody>
            
          </tbody>
        </table>
       </div>
    <!-- /.modal-content --> 
  </div>
  <!-- /.modal-dialog --> 
</div>

<!-- Modal Ends --> 
@section('dom_links')
@parent 
<script type="text/javascript">

$('.search_business_modal').on('hidden.bs.modal', function(e){ 
   $('#search_business').closest('.search_business_modal').find('.result tbody').html("");
   $('#search_business').closest('.search_business_modal').find('.modal-title').text("Search User");
   $('#search_business')[0].reset();
});

$("input:text").on('focus', function() {
  $('#search_business').closest('.search_business_modal').find('#errorlist').hide();
  $('#search_business').closest('.search_business_modal').find('#errorlist').text("");
});

$('body').on('click', '#business_detailed_search', function() {
      $('.search_business_modal').modal('show');
      $('#search_business').closest('.search_business_modal').find('.result tbody').html("");
          $('#search_business').closest('.search_business_modal').find('.modal-title').text("Search Business");
          $('#search_business')[0].reset();
          $('#search_business').show();
      current_select_item = $(this).closest('.search_container').find('select.business_id');
    });


	$('#search_business').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                mobile_no: {
                	number: true,
                    minlength:10,
      				      maxlength:10
                }, 
                email_address: {
        					email:true,
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
              var mobile = $('.search_business_modal input[name=mobile_no]').val();
              var email = $('.search_business_modal input[name=email_address]').val();
              var pan_no = $('.search_business_modal input[name=pan_no]').val();
              var gst_no = $('.search_business_modal input[name=gst_no]').val();
              var phone_no = $('.search_business_modal input[name=phone_no]').val();
              var web_address = $('.search_business_modal input[name=web_address]').val();

               if(mobile == "" && email == "" &&  pan_no == ""  && gst_no == ""  && phone_no == ""  && web_address == "" ) {
                $('#search_business').closest('.search_business_modal').find('#errorlist').text("Fill any field to search!");
                $('#search_business').closest('.search_business_modal').find('#errorlist').show();
               } else {

               $('.loader_wall_onspot').show();
        				$.ajax({
        				 url: "{{ route('advanced_business_search') }}",
        				 type: 'post',
        				 data: {
        				 	_token: '{{ csrf_token() }}',
        				 	email: email,
        				 	mobile: mobile,
        				 	pan_no: pan_no,
        				 	gst_no: gst_no,
        				 	phone_no: phone_no,
        				 	web_address: web_address
        					},
        					success:function(data, textStatus, jqXHR) {
        				    $('#search_business').hide();

                    var html = "";

                    for(var i in data) {
                      html += `<tr><td><b>`+data[i].business_name+ ` (`+data[i].alias+`)</b>`;

                      if($('.search_business_modal input[name=mobile_no]').val() != "") {
                        html += `<br>`+mobile;
                      }

                      if($('.search_business_modal input[name=email_address]').val() != "") {
                        html += `<br>`+email;
                      }

                      if($('.search_business_modal input[name=phone_no]').val() != "") {
                        html += `<br>`+phone_no;
                      }

                      if($('.search_business_modal input[name=web_address]').val() != "") {
                        html += `<br>`+web_address;
                      }

                       html += `</td><td><button data-id="`+data[i].id+`" data-name="`+data[i].business_name+ ` (`+data[i].alias+`)" style="padding: 3px;" class="btn btn-success float-right select_business">Select</button></td></tr>`;

                    }
                    $('#search_business').closest('.search_business_modal').find('.result tbody').append(html);
                    $('#search_business').closest('.search_business_modal').find('.result').show();
                    $('#search_business').closest('.search_business_modal').find('.modal-title').text("Search result..");
        						$('.loader_wall_onspot').hide();
        					},
        				 error:function(jqXHR, textStatus, errorThrown) {
        					//alert("New Request Failed " +textStatus);
        					}
        				});
                
               }
            }
        });

</script> 
@stop
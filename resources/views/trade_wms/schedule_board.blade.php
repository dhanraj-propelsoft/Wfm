@extends('layouts.master')
@section('head_links') @parent
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/>
<style type="text/css">
    #blog{
        margin-top: -22px;
        margin-left:  -23px;
      
        height: 10% !important;

         }
         .fc-agendaWeek-button{
            display: none;
         }
         .fc-agendaDay-button{
            display: none;
         }

</style>
@stop
@include('includes.trade_wms')
@section('content')
@include('includes.add_user')
@include('includes.add_business')
<div class="alert alert-success">
    {{ Session::get('flash_message') }}
</div>
<div class="alert alert-danger"></div>
@if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif
<div>
    <a class="btn btn-danger float-right addnew_jobcard" style="color: #fff;padding-top: 1px;">New Jobcard</a>
</div>
<div class="container" id="blog" style="max-width: 920px;" >
    <div class="row" >
       <div class="col-lg-8 col-md-6 col-sm-6 ">
            <div class="panel panel-default">
            <div class="panel-heading ">Schedule Board</div>
 
                <div class="panel-body">
                       
                    {!! $calendar->calendar() !!}
                </div>

            </div>
        </div>
    </div>
</div>
@include('modals.invoice_modal')
@stop

@section('dom_links')
@parent
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmake-0.1.32/pdfmake.min.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmake-0.1.32/vfs_fonts.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/datatables.min.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/jspdf.min.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/dropzone/dropzone.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.js"></script>
{!! $calendar->script() !!}
<script type="text/javascript">
    $('.addnew_jobcard').on('click', function(e) {
            e.preventDefault(); 
            $('.loader_wall_onspot').show();
            $('body').css('overflow', 'hidden');
            $('.full_modal_content').attr("data-id",2)
            $('.full_modal_content').animate({ height: $(window).height() + 'px' }, 400, function() {
                    $.get("{{ route('transaction.create', ['job_card']) }}", function(data) {
                      $('.full_modal_content').show();
                      $('.full_modal_content').html("");
                      $('.full_modal_content').html(data);
                      $('.full_modal_content form').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });
                      $('.full_modal_content').css({ top: $("#page-header").height()+'px', left: ($('#sidebar-menu').outerWidth())+'px', width: ($(window).outerWidth()-$('#sidebar-menu').outerWidth()) +'px' });
                      $('.loader_wall_onspot').hide();
                    });
            });         
    });

</script>


@stop
@extends('layouts.master')
@section('head_links') @parent
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
@stop
@include('includes.settings')
@section('content')

@if(Session::has('flash_message'))
    <div class="alert alert-success" style="display: block;">
        {{ Session::get('flash_message') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger" style="display: block;">
        @foreach($errors->all() as $error)
            {{ $error }}
        @endforeach
    </div>
@endif

<div class="fill header"><h4 class="float-left">User Log</h4></div>
<div class="clearfix"></div>
<div class="container">
<div>
    <input style="float: left; width: auto;" name="date" type="text" class="form-control user_log_date datetype" placeholder="Month" data-date-format="mm-yyyy" />
    <button style="float: left; padding: 3px 12px; border-radius: 0 3px 3px 0" type="submit" class="date btn btn-success"><i class="fa fa-search" aria-hidden="true"></i></button>
</div>
<div class="clearfix"></div>
<br><br>
<div class="row">
    <div class="col-sm-3 users dashboard_container">
    </div>
    <div class="col-sm-9 user_table"></div>
  </div>   
</div>            
@stop

@section('dom_links')
@parent
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmake-0.1.32/pdfmake.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmake-0.1.32/vfs_fonts.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/datatables.min.js') }}"></script>
<script>

$(document).ready(function() {

     $('#user').DataTable();

    $('.user_log_date').datepicker({
        autoclose: true,
        minViewMode: 1,
        format: 'mm-yyyy'
    });

    $('.date').on('click', function() {

            var html = "";
            var url = window.location.href;
            var page = $.trim($('.page-title').clone().find('a').remove().end().text());
            var date = $('input[name=date]').val();

            if(date != "") {
            $('.users').html();
            $.ajax({
                 url: "{{ route('get_user_log') }}",
                 type: 'post',
                 data: {
                    
                    _token :"{{csrf_token()}}",
                    title: page,
                    url:url,
                    date:date,
                    },
                dataType: "json",
                    success:function(data, textStatus, jqXHR) {
                        var result = data.result;

                       for(i in result){
                             html += "<div><a style='padding: 5px; border: 1px solid #ccc; float: left; width: 100%; margin: 0; text-align: left; border-radius: 0;' data-id='"+result[i].id+"'>"+result[i].name+"</a></div>";
                       }

                       $('.users').html(html);

                    },
                    error:function(jqXHR, textStatus, errorThrown) {
                    //
                    }
            });
                
            }
    });

    $('body').on('click', '.users a', function(){

            var html = "";
            var id = $(this).data('id');
            var url = window.location.href;
            var date = $('input[name=date]').val();         
            var page = $.trim($('.page-title').clone().find('a').remove().end().text());

            $('.user_table').html();
            $.ajax({
                url: "{{ route('list_user_log') }}",
                type: 'post',
                data: {               
                _token :"{{csrf_token()}}",
                id:id,
                title: page,
                url:url,
                date:date,
                },
                dataType: "json",
                success:function(data, textStatus, jqXHR) {

                    html += `<table class="table table-striped table-hover table-bordered" id="user">
                                <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>URL</th>
                                    <th>Date</th>                               
                                </tr>
                                </thead>
                                <tbody>`;
                    for(i in data){

                        var date_time = data[i].datetime;
                        var result = date_time.split('-');

                        html += "<tr><td>"+data[i].page+"</td><td>"+data[i].url+"</td><td>"+result[2]+"-"+result[1]+"-"+result[0]+"</td></tr>";

                    }

                    html += "</tbody></table>";

                    $('.user_table').html(html);

                    $('#user').DataTable( {
                        dom: 'Bfrtip',
                          buttons: [
                              'excel', 'pdf', 'print'
                          ]
                    } );
                },
            });
        });
});
</script>
@stop
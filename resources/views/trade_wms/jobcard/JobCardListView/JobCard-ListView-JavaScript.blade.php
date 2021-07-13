@section('dom_links')
@parent
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/datatables.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/plugins/jquery.contextMenu.min.js') }}"></script>

@include('trade_wms.jobcard.JobCard-Common-JavaScript')

<script type="text/javascript">

    //data from PHP
    var dateRangeList1 = <?php echo json_encode(dateRange());?>;
    var orgName = "{{ Session::get('business') }}";
</script>

<script type="text/javascript" src="{{ URL::asset('resources/views/trade_wms/jobcard/JobCardListView/JobCard-ListView.js') }}"></script>

@stop
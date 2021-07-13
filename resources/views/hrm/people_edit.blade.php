<!-- Modal Starts -->

@include('modals.user_search_modal')
@include('modals.add_user_modal')

@section('dom_links')
@parent
<script type="text/javascript">

$(document).ready(function() {
	$('body').on('click', '#user_detailed_add', function() {
      
      $('.add_user_modal').modal('show');
      

    });
});




</script>
@stop
<!-- Modal Ends -->
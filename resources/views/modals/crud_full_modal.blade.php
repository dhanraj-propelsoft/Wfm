
<div class="settings_panel">
	<div class="float-right close_side_panel"><i style="font-size: 40px; " class="fa icon-arrows-remove"></i></div>
	<div id="container" class="container"></div>
</div>
<div class="slide_panel_bg"></div>
<div class="full_modal_content"></div>
@section('dom_links')
@parent 
<script type="text/javascript">
	$(document).ready(function() {

		$('.side_panel').on('click', function() {
			$('.slide_panel_bg').fadeIn();
			$('.settings_panel').animate({ right: 0 });
		});

		$('.close_side_panel').on('click', function() {
			$('.slide_panel_bg').fadeOut();
			$('.settings_panel').animate({ right: "-25%" });
		});

	});
</script> 
@stop 
<!-- Modal Ends --> 
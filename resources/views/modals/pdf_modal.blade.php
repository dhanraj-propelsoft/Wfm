
<style>

body {
	font-family: 'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;font-size: 13px;color: #3e4855;line-height: 1.42857143;
}

table, table tr, table tr td {
	border:0;
	padding:0;
	border-spacing: 0;
	border-collapse:collapse;
	border: none;
}

.border {
	border:1px solid #000 !important
}

.border_td td {
	border:1px solid #000 !important
}

.border_bottom {
	border-bottom:1px solid #000 !important
}

.padding {
	padding:5px 15px;
}

.table_padding td {
	padding:1px 15px;
}

.remove, .remove_image {
	display: none;
}

</style>

 <div class="pdf_content">
	<div id="pdf"></div>
	<div style="display: none;" class="modal-footer">                                            
	    <a type="button" class="btn btn-default close_pdf_modal">Cancel</a>
	    <a style="color: #fff" class="btn btn-primary pdf"><i class="fa icon-basic-printer"></i> print</a>
	</div>
</div>  
@section('dom_links')
@parent 
<script type="text/javascript">
	$(document).ready(function() {

		$('body').on('click', '.tab_print_btn', function() {
			printDiv();
		});

		$('body').on('click', '.close_pdf_modal', function() {
			$('.pdf_content').animate({ top:-$('.pdf_content').outerHeight() + 'px' }, 300, function() { 
				$('.pdf_content #pdf').removeAttr('style');
				$('.pdf_content #pdf').html("");
				$('.pdf_content').removeAttr('style');
				$('.pdf_content .modal-footer').hide();
				$('.pdf_content').animate({top: '0px'}); 
				$('body').css('overflow', '');
			});
		});

	});

	function printDiv() 
	{
	  var divToPrint=document.getElementById('tab_print_btn');
	  var newWin=window.open('','Propel');

	  newWin.document.open();
	  newWin.document.write(`<html>
		
<style>


.item_table {
		border-collapse: collapse;
		border-width: 0px;
		border: none;
	}

		.total_container td {
			padding: 5px;
		}
   @media pdf {

   }

</style>
<script>

window.onload=function()
{
 window.pdf();
}

</scr`+`ipt>
	  	<body>`+divToPrint.innerHTML+`</body></html>`);
	  newWin.document.close();

	  setTimeout(function(){newWin.close();},10);

	    $('.pdf_content #pdf').removeAttr('style');
		$('.pdf_content #pdf').html("");
		$('.pdf_content').removeAttr('style');
		$('.pdf_content .modal-footer').hide();
		$('.pdf_content').animate({top: '0px'}); 
		$('body').css('overflow', '');

	}
</script> 
@stop 
<!-- Modal Ends --> 


	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.8.3/jquery.js" type="text/javascript" charset="utf-8"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="http://propelsoft.in/propel/assets/plugins/dropzone/dropzone.js"></script>


<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/dropzone/dropzone.css') }}">


<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
<style type="text/css">
	.dropzone .dz-preview .dz-progress
	{
		display: none !important;
	}
</style>
		<button onclick="getImages()">click me</button>

<table id="datatable" class="table data_table tableContent" width="100%" cellspacing="0">
        <thead>
        	<tr>
        		<td>
<div id="thumbnail_images">
</div>	
        		</td>
        	</tr>
        </thead>
    </table>
<script>
function getImages(){

	var imgDropzone = Dropzone.forElement("#thumbnail_images");
	$.get("{{ route('gallery_images') }}", function(data) {
		$.each(data, function(key,value) {
			var mockFile = { name: value.name, size: value.size };
			thisDropzone.options.addedfile.call(thisDropzone, mockFile);
			thisDropzone.options.thumbnail.call(thisDropzone, mockFile, "uploads/"+value.name);
		});
	});
}


</script>

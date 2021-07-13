@extends('layouts.master')
@section('head_links') @parent
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
<style>
#content_container {
   background:url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAUAAAAFCAYAAACNbyblAAAAEElEQVQImWNgYGAwZqADAAAUiAA0Trq+aAAAAABJRU5ErkJggg==') repeat;
   box-shadow: 0px 0px 3px #ccc ;
   border-radius:2px;
   position: relative;
   float: right;
   max-width:  800px;
   height: 600px;
   /* transform: scale(0.6); */
}
.content_container {
	border:1px solid rgb(255, 0, 12, .1);
	float: left;
	width: 100%;
	display: block;
}
.menu_list {
	float: left;
	width: 50px;
	padding: 0;
	margin: 0 10px 0 0;
	position: absolute;
	left: -65px;
}
.menu_list li {
	float: left;
	margin: 2px 0;
	width: 100%;
}
.horizontal {
	display: block;
}
.horizontal, .vertical, .draggable {
	float:left;
}
.horizontal div, .vertical div {
}
.bold {
	font-weight: bold;
}
.label_result {
	padding: 5px;
	float: left;
	cursor: crosshair;
}


.value_result {
	padding: 5px;
	float: left;
	cursor: crosshair;
}

.line_result {
	padding: 5dp;
	float: left;
	cursor: crosshair;
}

.text_result {
	padding: 5dp;
	float: left;
	cursor: crosshair;
}

.image_result {
	padding: 5dp;
	float: left;
	text-align: center;
	cursor: crosshair;
}

.selected_item {
	background: #EDEDED;
}

/* .property input, .property select, .property textarea, .property button {
	display:none;
} */

.remove, .remove_image {
	position: absolute;
	left: -10px;
	top:-10px;
	padding: 2px;
	border-radius:1px 5px;
	background: rgba(0,0,0,.3);
	cursor: pointer;
	display:none;
}
.total_container {
			line-height: 20px;
		}
		.total_container td {
			padding: 5px;
		}

</style>
@stop
@include('includes.settings')
@section('content') 

<!-- Modal Starts -->

<div class="modal fade bs-modal-lg" tabindex="-1" role="basic" id="print_save" aria-hidden="true">
  <div class="modal-dialog modal-sm">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
		<h4 class="modal-title">Save Template</h4>
	  </div>
	  <div class="modal-body">
		<input name="file_name" class="form-control" placeholder="Name" value="{{$template->name}}">
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn default" data-dismiss="modal">Close</button>
		<button type="button" class="btn btn-info save_content">Save</button>
	  </div>
	</div>
	<!-- /.modal-content --> 
  </div>
  <!-- /.modal-dialog --> 
</div>

<!-- Modal Ends -->

<div class="alert alert-success"> {{ Session::get('flash_message') }} </div>
@if($errors->any())
<div class="alert alert-danger"> @foreach($errors->all() as $error)
  <p>{{ $error }}</p>
  @endforeach </div>
@endif
@if($template)
<div class="fill header">
  <h4 class="float-left page-title">{{$template->name}} Edit</h4>
</div>
@endif
<div class="clearfix"></div>
<div class="row">
  <div class="col-md-12">
	<div id="content_container">
	  <ul class="menu_list">
		<li title="Label Value Pair" class="btn btn-default label_value"><i class="fa icon-software-font-kerning"></i></li>
		<li title="Text" class="btn btn-default text"><i class="fa icon-software-character"></i></li>
		<li title="Rectangle" class="btn btn-default rectangle"><i class="fa icon-software-shape-rectangle"></i></li>
		<li title="Image" class="btn btn-default image"><i class="fa icon-basic-picture"></i></li>
		<li title="Line" class="btn btn-default line"><i class="fa icon-software-vector-line"></i></li>
		<li title="Preview" class="btn btn-default preview"><i class="fa icon-basic-eye"></i></li>
		<li title="Print" class="btn btn-default print"><i class="fa icon-basic-printer"></i></li>
		<li title="Save" class="btn btn-success save"><i class="fa icon-basic-floppydisk"></i></li>
	  </ul>
	  <div style="width: 100%; height: 100%; overflow-y: auto;">
		<div class="content"> {!! $template->data !!} </div>
	  </div>
	</div>
	<div style="height: 400px; width:280px; float: left; margin:15px 10px;">
	  <div class="property">
		<div class="text_properties">
		  <div class="form-group" style="display: none;">
			<div class="row">
			  <label class="control-label col-md-4">Area</label>
			  <div class="col-md-8">
				<select id="area" class="form-control" name="area">
				  <option value="header_container">Header</option>
				  <option value="content_container">Content</option>
				  <option value="total_container">Total</option>
				  <option value="footer_container">Footer</option>
				</select>
			  </div>
			</div>
		  </div>
		  <div class="form-group">
			<div class="row">
			  <label class="control-label col-md-4">Background</label>
			  <div class="col-md-8">
				<input style="width: 30px;" class="form-control" type="color" name="background_color" />
			  </div>
			</div>
		  </div>
		  <div class="form-group">
			<div class="row">
			  <label class="control-label col-md-6">Margin</label>
			  <div class="col-md-12">
				<input style="width: 23%; margin: 2px; float: left;" class="form-control numbers" type="text" placeholder="Top" name="margin_top" />
				<input style="width: 23%; margin: 2px; float: left;" class="form-control numbers" type="text" placeholder="Right" name="margin_right" />
				<input style="width: 23%; margin: 2px; float: left;" class="form-control numbers" type="text" placeholder="Bottom" name="margin_bottom" />
				<input style="width: 23%; margin: 2px; float: left;" class="form-control numbers" type="text" placeholder="Left" name="margin_left" />
			  </div>
			</div>
		  </div>
		  <div class="form-group" style="display: none;">
			<div class="row">
			  <label class="control-label col-md-4">Label</label>
			  <div class="col-md-8">
				<input  class="form-control" type="text" name="label" />
			  </div>
			</div>
		  </div>
		  <div class="form-group" style="display: none;">
			<div class="row">
			  <label class="control-label col-md-4">Value</label>
			  <div class="col-md-8"> {!! Form::select('value', $values, null, ['class' => 'form-control', 'id' => 'value']) !!} </div>
			</div>
		  </div>
		  <div class="form-group" style="display: none;">
			<div class="row">
			  <label class="control-label col-md-4">Text</label>
			  <div class="col-md-8">
				<textarea class="form-control" name="description"></textarea>
			  </div>
			</div>
		  </div>
		  <div class="form-group" style="display: none;">
			<div class="row">
			  <label class="control-label col-md-4">Width</label>
			  <div class="col-md-8">
				<input class="form-control" type="text" placeholder="" name="width" />
			  </div>
			</div>
		  </div>
		  <div class="form-group" style="display: none;">
			<div class="row">
			  <label class="control-label col-md-4">Height</label>
			  <div class="col-md-8">
				<input class="form-control" type="text" placeholder="" name="height" />
			  </div>
			</div>
		  </div>
		  <div  class="form-group" style="display: none;">
			<div class="row">
			  <input style="float: left;" id="bold" type="checkbox" name="bold" />
			  <label style="float: left; padding-left: 15px;" for="bold"><span></span>Make Bold</label>
			</div>
		  </div>
		  <div class="form-group">
			<div class="row">
			  <label class="control-label col-md-4">Font-Size</label>
			  <div class="col-md-8">
				<select class="form-control" name="font_size">
				  <option value="10">10</option>
				  <option value="11">11</option>
				  <option value="12">12</option>
				  <option value="14">14</option>
				  <option value="16">16</option>
				  <option value="18">18</option>
				  <option value="20">20</option>
				  <option value="22">22</option>
				  <option value="24">24</option>
				  <option value="26">26</option>
				  <option value="28">28</option>
				  <option value="30">30</option>
				  <option value="32">32</option>
				</select>
			  </div>
			</div>
		  </div>
		  <div class="form-group">
			<div class="row">
			  <label class="control-label col-md-4">Color</label>
			  <div class="col-md-8">
				<input style="width: 30px;" class="form-control" type="color" name="color" />
			  </div>
			</div>
		  </div>
		  <div class="form-group"">
			<div class="row">
			  <label class="control-label col-md-4">Font</label>
			  <div class="col-md-8">
				<select class="form-control" name="fontfamily">
				  <option value="Arial, sans-serif">Arial</option>
				  <option value="'Arial Black', sans-serif">Arial Black</option>
				  <option value="'Book Antiqua', Palatino, serif">Book Antiqua</option>
				  <option value="'Comic Sans MS', cursive">Comic Sans MS</option>
				  <option value="'Courier New', monospace">Courier New</option>
				  <option value="Courier, monospace">Courier</option>
				  <option value="Gadget, sans-serif">Gadget</option>
				  <option value="Geneva, sans-serif">Geneva</option>
				  <option value="Georgia, serif">Georgia</option>
				  <option value="Helvetica, sans-serif">Helvetica</option>
				  <option value="'Lucida Console', monospace">Lucida Console</option>
				  <option value="'Lucida Grande', sans-serif">Lucida Grande</option>
				  <option value="'Lucida Sans Unicode', sans-serif">Lucida Sans Unicode</option>
				  <option value="Monaco, monospace">Monaco</option>
				  <option value="'MS Serif', serif">MS Serif</option>
				  <option value="'Palatino Linotype', Palatino, serif">Palatino Linotype</option>
				  <option value="Tahoma, sans-serif">Tahoma</option>
				  <option value="'Times New Roman', Times, serif">Times New Roman</option>
				  <option value="'Trebuchet MS', sans-serif">Trebuchet MS</option>
				  <option value="Verdana, sans-serif">Verdana</option>
				</select>
			  </div>
			</div>
		  </div>
		  <div class="form-group" style="display: none;">
			<div class="row">
			  <label class="control-label col-md-4">Border-Style</label>
			  <div class="col-md-8">
				<select class="form-control"  name="border_type">
				  <option value="solid">Solid</option>
				  <option value="dotted">Dotted</option>
				</select>
			  </div>
			</div>
		  </div>
		  <div class="form-group" style="display: none;">
			<div class="row">
			  <label class="control-label col-md-4">Border-Size</label>
			  <div class="col-md-8">
				<input class="form-control" type="text" name="border_size" />
			  </div>
			</div>
		  </div>
		  <div class="form-group" style="display: none;">
			<div class="row">
			  <label class="control-label col-md-4">Image</label>
			  <div class="col-md-8">
				<input class="form-control" type="file" name="image" />
			  </div>
			</div>
		  </div>
		  <button id="submit" class="btn btn-info">Submit</button>
		</div>
		<div class="table_properties" style="display: none;">
		  <div class="form-group">
			<div class="row">
			  <label class="control-label col-md-6">Head Color</label>
			  <div class="col-md-6">
				<input style="width: 30px;" class="form-control" type="color" name="head_color" />
			  </div>
			</div>
			</div>
			<div class="form-group">
			<div class="row">
			  <label class="control-label col-md-6">Head Background Color</label>
			  <div class="col-md-6">
				<input style="width: 30px;" class="form-control" type="color" name="head_bgcolor" />
			  </div>
			</div>
			</div>
			<div class="form-group">
			<div class="row">
			  <label class="control-label col-md-6">Body Color</label>
			  <div class="col-md-6">
				<input style="width: 30px;" class="form-control" type="color" name="body_color" />
			  </div>
			</div>
			</div>
			<div class="form-group">
			<div class="row">
			  <label class="control-label col-md-6">Alternate Row Color</label>
			  <div class="col-md-6">
				<input style="width: 30px;" class="form-control" type="color" name="alternate_bgcolor" />
			  </div>
			</div>
			</div>
			@if($template->print_template == "sales")
			<div  class="form-group">
			  <div class="row">
				<input style="float: left;" id="col_id" type="checkbox" name="col_id" />
				<label style="float: left; padding-left: 15px;" for="col_id"><span></span>Id</label>
			  </div>
			</div>
			<div  class="form-group">
			  <div class="row">
				<input style="float: left;" id="col_desc" type="checkbox" name="col_desc" />
				<label style="float: left; padding-left: 15px;" for="col_desc"><span></span>Description</label>
			  </div>
			</div>
			<div  class="form-group">
			  <div class="row">
				<input style="float: left;" id="col_hsn" type="checkbox" name="col_hsn" />
				<label style="float: left; padding-left: 15px;" for="col_hsn"><span></span>HSN</label>
			  </div>
			</div>
			<div  class="form-group">
			  <div class="row">
				<input style="float: left;" id="col_gst" type="checkbox" name="col_gst" />
				<label style="float: left; padding-left: 15px;" for="col_gst"><span></span>GST</label>
			  </div>
			</div>
			<div  class="form-group">
			  <div class="row">
				<input style="float: left;" id="col_discount" type="checkbox" name="col_discount" />
				<label style="float: left; padding-left: 15px;" for="col_discount"><span></span>Discount</label>
			  </div>
			</div>
			<div  class="form-group">
			  <div class="row">
				<input style="float: left;" id="col_quantity" type="checkbox" name="col_quantity" />
				<label style="float: left; padding-left: 15px;" for="col_quantity"><span></span>Quantity</label>
			  </div>
			</div>
			<div  class="form-group">
			  <div class="row">
				<input style="float: left;" id="col_rate" type="checkbox" name="col_rate" />
				<label style="float: left; padding-left: 15px;" for="col_rate"><span></span>Rate</label>
			  </div>
			</div>
			<div  class="form-group">
			  <div class="row">
				<input style="float: left;" id="col_amount" type="checkbox" name="col_amount" />
				<label style="float: left; padding-left: 15px;" for="col_amount"><span></span>Amount</label>
			  </div>
			</div>
			@elseif($template->print_template == "payslip")
			<div  class="form-group">
			  <div class="row">
				<input style="float: left;" id="col_earnings" type="checkbox" name="col_earnings" />
				<label style="float: left; padding-left: 15px;" for="col_earnings"><span></span>Show Earnings</label>
			  </div>
			</div>
			<div  class="form-group">
			  <div class="row">
				<input style="float: left;" id="col_deductions" type="checkbox" name="col_deductions" />
				<label style="float: left; padding-left: 15px;" for="col_deductions"><span></span>Show Deductions</label>
			  </div>
			</div>
			@endif
			<button id="submit" class="btn btn-info">Submit</button>
		  </div>
		
		<div class="total_properties"  style="display: none;">
		  <button id="submit" class="btn btn-info">Submit</button>
		</div>
		
		</div>
	  </div>
	</div>
  </div>
</div>
@stop

@section('dom_links')
@parent 
<script type="text/javascript">
	$(document).ready(function() {

	sidebar_minimized();

	$('body').on('mouseenter', '.draggable', function() {
		$(this).find('.remove, .remove_image').show();
	});

	$('body').on('mouseleave', '.draggable', function() {
		$(this).find('.remove, .remove_image').hide();
	});

	$('select[name=orientation]').val($('.workspace').data('type'));

	if($('.workspace').css('backgroundColor') != "rgba(0, 0, 0, 0)") {
		$('input[name=background_color]').val(rgb2hex($('.workspace').css('backgroundColor')));
	}

	$(".table_properties input[type=checkbox]").each(function() {

		$(this).on('click', function() {
			if($(this).is(":checked")) {
				$('.content_container .'+$(this).attr('name')).show();
			} else {
				$('.content_container .'+$(this).attr('name')).hide();
			}


			if($('input[name=col_earnings]').is(":checked") && $('input[name=col_deductions]').is(":checked")) {
				$('.col_earnings').show();
				$('.col_deductions').show();
				$('.col_earnings, .col_deductions').css('width', '100%');
			} else if($('input[name=col_earnings]').is(":checked") && !$('input[name=col_deductions]').is(":checked")) {
				$('.col_earnings').show();
				$('.col_earnings').css('width', '100%');
				$('.col_deductions').hide();
			} else if(!$('input[name=col_earnings]').is(":checked") && $('input[name=col_deductions]').is(":checked")) {
				$('.col_earnings').hide();
				$('.col_deductions').show();
				$('.col_deductions').css('width', '100%');
			} else {
				$('.col_earnings').hide();
				$('.col_deductions').hide();
			}


		});

	});

	$('input[name=margin_top]').val($('.workspace').css('padding-top').replace('px', ''));
	$('input[name=margin_right]').val($('.workspace').css('padding-right').replace('px', ''));
	$('input[name=margin_bottom]').val($('.workspace').css('padding-bottom').replace('px', ''));
	$('input[name=margin_left]').val($('.workspace').css('padding-left').replace('px', ''));


	setWorkSpace($(".workspace"));

	$("body").on("DOMNodeInserted", ".draggable", makeDraggable);

	$(".workspace").on('click', function(event) {

		if ($(event.target).closest('.body_container').hasClass('body_container')) {
			$('.table_properties').show();
			$('.total_properties').hide();
			$('.text_properties').hide();

			$('input[name=head_color]').val(rgb2hex($('.item_table thead th').css('color')));
			$('input[name=head_bgcolor]').val(rgb2hex($('.item_table thead th').css('backgroundColor')));
			$('input[name=body_color]').val(rgb2hex($('.item_table tbody td').css('color')));

			if($('.item_table tbody tr:nth-child(2)').length > 0) {
				$('input[name=alternate_bgcolor]').val(rgb2hex($('.item_table tbody tr:nth-child(2)').css('backgroundColor')));
			}



			$('.item_table thead th').each(function() {
				if($(this).is(":visible")) {
					$('.table_properties input[name="'+$(this).attr('class')+'"]').prop('checked', true);
				} else {
					$('.table_properties input[name="'+$(this).attr('class')+'"]').prop('checked', false);
				}
				
			});

			if($('.col_deductions').is(":visible")) {
				$('.table_properties input[name="col_deductions"]').prop('checked', true);
			} else {
				$('.table_properties input[name="col_deductions"]').prop('checked', false);
			}

			if($('.col_earnings').is(":visible")) {
				$('.table_properties input[name="col_earnings"]').prop('checked', true);
			} else {
				$('.table_properties input[name="col_earnings"]').prop('checked', false);
			}

			

		} else if ($(event.target).closest('.total_container').hasClass('total_container')) {
			$('.table_properties').hide();
			$('.total_properties').show();
			$('.text_properties').hide();
		} else {
			$('.table_properties').hide();
			$('.total_properties').hide();
			$('.text_properties').show();
		}

		$('.table_properties #submit').off().on('click', function() {

			$('.item_table thead th').css('color', $('input[name=head_color]').val());
			$('.item_table thead th').css('backgroundColor', $('input[name=head_bgcolor]').val());
			$('.item_table tbody td').css('color', $('input[name=body_color]').val());

			$(".item_table tr:even").css("background-color", $('input[name=alternate_bgcolor]').val());

		});

		$('.remove_image').off().on('click', function() {
			var image = $(this).parent().find('img').attr('src');
			var obj = $(this);
			$.ajax({
				url: "{{ route('print_remove_image') }}",
				type: "post",
				data: {
					image: image,
					_token: '{{ csrf_token() }}'
				},
				dataType: 'json',
				beforeSend: function() {},
				success: function(res) {}
			});
			obj.parent().remove();
		});

		

		$('.remove').off().on('click', function() {
			$(this).parent().remove();
		});

		switch ($(event.target).attr('class')) {
			case "workspace":
				{

					if($('.workspace').css('backgroundColor') != "rgba(0, 0, 0, 0)") {
						$('input[name=background_color]').val(rgb2hex($('.workspace').css('backgroundColor')));
					}

					$('input[name=margin_top]').val($('.workspace').css('padding-top').replace('px', ''));
					$('input[name=margin_right]').val($('.workspace').css('padding-right').replace('px', ''));
					$('input[name=margin_bottom]').val($('.workspace').css('padding-bottom').replace('px', ''));
					$('input[name=margin_left]').val($('.workspace').css('padding-left').replace('px', ''));

					$('.property .text_properties select[name=paper_size]').closest('.form-group').show();
					$('.property .text_properties select[name=orientation]').closest('.form-group').show();
					$('.property .text_properties input[name=background_color]').closest('.form-group').show();
					$('.property .text_properties input[name=margin_top]').closest('.form-group').show();
					$('.property .text_properties input[name=margin_right]').closest('.form-group').show();
					$('.property .text_properties input[name=margin_bottom]').closest('.form-group').show();
					$('.property .text_properties input[name=margin_left]').closest('.form-group').show();
					$('.property .text_properties input[name=color]').closest('.form-group').show();
					$('.property .text_properties select[name=font_size]').closest('.form-group').show();
					$('.property .text_properties select[name=fontfamily]').closest('.form-group').show();
					$('.property .text_properties button').show();

					setWorkSpace($(event.target));
				}
				break;
			case "label_result":
				{
					
					header_hide($(event.target));

					$('.property .text_properties input[name=label]').val($(event.target).text());
					$('.property .text_properties select[name=value]').val($(event.target).closest('.draggable').find('.value_result').data('value'));
					$('.property .text_properties input[name=color]').val(rgb2hex($(event.target).css('color')));
					$('.property .text_properties select[name=font_size]').val($(event.target).css('font-size').replace('px', ''));
					
					if($(event.target).css('font-weight') != "") {
						$('.property .text_properties input[name=bold]').prop('checked', true);
					} else {
						$('.property .text_properties input[name=bold]').prop('checked', false);
					}
					
					$('.property .text_properties select[name=fontfamily]').val($(event.target).css('font-family'));


					$(event.target).addClass('selected_item');

					$('.property .text_properties input[name=label]').closest('.form-group').show();
					$('.property .text_properties select[name=value]').closest('.form-group').show();
					$('.property .text_properties input[name=color]').closest('.form-group').show();
					$('.property .text_properties select[name=font_size]').closest('.form-group').show();
					$('.property .text_properties select[name=area]').closest('.form-group').show();
					$('.property .text_properties input[name=bold]').closest('.form-group').show();
					$('.property .text_properties select[name=fontfamily]').closest('.form-group').show();
					$('.property .text_properties button').show();

					setLabelProperty($(event.target));
				}
				break;
			case "value_result":
				{
					header_hide($(event.target));

					$('.property .text_properties select[name=value]').val($(event.target).closest('.draggable').find('.value_result').data('value'));
					$('.property .text_properties input[name=color]').val(rgb2hex($(event.target).css('color')));
					$('.property .text_properties select[name=font_size]').val($(event.target).css('font-size').replace('px', ''));
					
					if($(event.target).css('font-weight') != "") {
						$('.property .text_properties input[name=bold]').prop('checked', true);
					} else {
						$('.property .text_properties input[name=bold]').prop('checked', false);
					}
					
					$('.property .text_properties select[name=fontfamily]').val($(event.target).css('font-family'));


					$(event.target).addClass('selected_item');
					
					$('.property .text_properties select[name=value]').closest('.form-group').show();
					$('.property .text_properties input[name=color]').closest('.form-group').show();
					$('.property .text_properties select[name=font_size]').closest('.form-group').show();
					$('.property .text_properties input[name=bold]').closest('.form-group').show();
					$('.property .text_properties select[name=fontfamily]').closest('.form-group').show();
					$('.property .text_properties button').show();

					setValueProperty($(event.target));
				}
				break;

			case "text_result":
				{
					header_hide($(event.target));

					$('.property .text_properties textarea[name=description]').val($(event.target).closest('.draggable').find('.text_result').html().replace('<br>', '\n'));
					$('.property .text_properties input[name=color]').val(rgb2hex($(event.target).css('color')));
					$('.property .text_properties select[name=font_size]').val($(event.target).css('font-size').replace('px', ''));
					
					if($(event.target).css('font-weight') != "") {
						$('.property .text_properties input[name=bold]').prop('checked', true);
					} else {
						$('.property .text_properties input[name=bold]').prop('checked', false);
					}
					
					$('.property .text_properties select[name=fontfamily]').val($(event.target).css('font-family'));

					$(event.target).addClass('selected_item');

					$('.property .text_properties textarea[name=description]').closest('.form-group').show();
					$('.property .text_properties input[name=color]').closest('.form-group').show();
					$('.property .text_properties select[name=font_size]').closest('.form-group').show();
					$('.property .text_properties select[name=area]').closest('.form-group').show();
					$('.property .text_properties input[name=bold]').closest('.form-group').show();
					$('.property .text_properties select[name=fontfamily]').closest('.form-group').show();

					
					$('.property .text_properties button').show();

					setTextProperty($(event.target));
				}
				break;
				case "rectangle_result":
				{

					header_hide($(event.target));

					$('.property .text_properties input[name=color]').val(rgb2hex($(event.target).css('border-left-color')));
					$('.property .text_properties select[name=border_type]').val($(event.target).css('borderBottomStyle'));
					$('.property .text_properties input[name=border_size]').val($(event.target).css('borderBottomWidth').replace('px', ''));
					$('.property .text_properties input[name=width]').val($(event.target).css('width').replace('px', ''));
					$('.property .text_properties input[name=height]').val($(event.target).css('height').replace('px', ''));

					$('.property .text_properties input[name=width]').closest('.form-group').show();
					$('.property .text_properties input[name=height]').closest('.form-group').show();
					$('.property .text_properties input[name=border_size').closest('.form-group').show();
					$('.property .text_properties input[name=color]').closest('.form-group').show();
					$('.property .text_properties select[name=border_type]').closest('.form-group').show();

					
					$('.property .text_properties button').show();

					setRectangleProperty($(event.target));
				}
				break;

			case "line_result":
				{
					header_hide($(event.target));

					$('.property .text_properties input[name=color]').val(rgb2hex($(event.target).css('border-left-color')));
					$('.property .text_properties select[name=border_type]').val($(event.target).css('borderBottomStyle'));
					$('.property .text_properties input[name=border_size]').val($(event.target).css('borderBottomWidth').replace('px', ''));
					$('.property .text_properties input[name=width]').val($(event.target).css('width').replace('px', ''));
					$('.property .text_properties input[name=height]').val($(event.target).css('height').replace('px', ''));

					$(event.target).addClass('selected_item');

					$('.property .text_properties input[name=width]').closest('.form-group').show();
					$('.property .text_properties input[name=border_size').closest('.form-group').show();
					$('.property .text_properties input[name=color]').closest('.form-group').show();
					$('.property .text_properties select[name=border_type]').closest('.form-group').show();

					setLineProperty($(event.target));
				}
				break;

			case "image_result":
				{
					header_hide($(event.target));

					$('.property .text_properties input[name=width]').val($(event.target).css('width').replace('px', ''));
					$('.property .text_properties input[name=height]').val($(event.target).css('height').replace('px', ''));

					$(event.target).addClass('selected_item');

					$('.property .text_properties input[name=width]').closest('.form-group').show();
					$('.property .text_properties input[name=height]').closest('.form-group').show();
					$('.property .text_properties input[name=image]').closest('.form-group').show();
					$('.property .text_properties button').show();

					setImageProperty($(event.target));
				}
				break;
			default:
			{
				console.log(event.target);
			}
		}
		/*if($(event.target).attr('class') == "label_text") {   
		alert($(event.target).attr('class'));
			//localStorage.selected = $(event.target);
			setProperty($(event.target));
		}*/
	});


function header_hide(e) {

	if($(e).closest('.header_container').hasClass('header_container')) {
		$(".property select[name=area] option[value='header_container']").prop('selected', true).trigger('change');
	} else if($(e).closest('.body_container').hasClass('body_container')) {
		$(".property select[name=area] option[value='body_container']").prop('selected', true).trigger('change');
	} else if($(e).closest('.total_container').hasClass('total_container')) {
		$(".property select[name=area] option[value='total_container']").prop('selected', true).trigger('change');
	} else if($(e).closest('.footer_container').hasClass('footer_container')) {
		$(".property select[name=area] option[value='footer_container']").prop('selected', true).trigger('change');
	}

	$('div').removeClass('selected_item');
	$('img').removeClass('selected_item');
	$('.property .text_properties input').closest('.form-group').hide();
	$('.property .text_properties select').closest('.form-group').hide();
	$('.property .text_properties textarea').closest('.form-group').hide();
	$('.property .text_properties button').closest('.form-group').hide();
}


var myStyle = `<style> 

.item_table {
		border-collapse: collapse;
		border-width: 0px;
		border: none;
	}
	.total_container td {
		padding: 5px;
	}
   @media print {
		body {
		-webkit-print-color-adjust: exact;
		}
   }
</style>`;




	$('.preview').on('click', function() {
		var content = $(".workspace").html();
		var myWindow = window.open("", "", "width=" + $(".workspace").width() + ",height=" + ($(".workspace").height() + 50));
		var doc = myWindow.document;
		doc.open();
		doc.write(myStyle);
		doc.write("<div class='content'>");
		doc.write(content);
		doc.write("</div>");
		doc.close();

	});


	$('body').on('click', '.label_value', function() {
		var label_text = $('<div style="width:auto;  float:left; font-family: Arial,sans-serif; z-index: 1; min-width: 150px;" class="draggable"><div><div style="float:left;"><div style="float:left; padding-right:15px;" class="label_result">Label</div><div style="right:-5px; top: 15px; width:15px;" class="remove"><i class="fa fa-times"></i></div></div><div style="float:left;" class="value_result">Value</div> <div class="remove"><i class="fa fa-times"></i></div></div></div>');

		var area = $('select[name=area]').val();
              // console.log(area);
		$('.'+area).append(label_text);
	});

	$('.text').on('click', function() {
		var label_text = $('<div style="width:auto; float:left; font-family: Arial,sans-serif; z-index: 1;" class="draggable"><div class="text_result">Sample Text</div><div class="remove"><i class="fa fa-times"></i></div></div>');

		var area = $('select[name=area]').val();

		$('.'+area).append(label_text);
	});

	$('.rectangle').on('click', function() {
		var rectangle = $('<div style="width:auto; float:left;" class="draggable"><div style="border-style:solid; border-color:#000; border-width:1px; width:100px; height:100px;" class="rectangle_result"></div><div class="remove"><i class="fa fa-times"></i></div></div>');

		var area = $('select[name=area]').val();

		$('.'+area).append(rectangle);
	});

	$('.image').on('click', function() {
		var image = $('<div style="width:50px; height:50px; display: block; float:left;" class="horizontal draggable"><img width="100%" height="100%" class="image_result" alt="image" /><div class="remove_image"><i class="fa fa-times"></i></div></div>');

		var area = $('select[name=area]').val();

		$('.'+area).append(image);
	});

	$('.line').on('click', function() {
		var label_text = $('<div style="width:auto; height:10px; float:left; " class="draggable"><div style="color:transparent; border-style:solid; border-color:#000; border-width:0px 0px 1px 0px" class="line_result">Static Text</div><div class="remove"><i class="fa fa-times"></i></div></div>');

		var area = $('select[name=area]').val();

		$('.'+area).append(label_text);
	});

	$('.print').on('click', function() {
		var content = $(".workspace").html();
		var myWindow = window.open("", "", "width=" + $(".workspace").width() + ",height=" + ($(".workspace").height() + 50));
		var doc = myWindow.document;
		doc.open();
		doc.write(myStyle);
		doc.write("<div class='content'>");
		doc.write(content);
		doc.write("</div>");
		doc.close();
		myWindow.focus();
		myWindow.print();
		myWindow.close();
	});

	$('.save').on('click', function(e) {
		e.preventDefault();
		if ($("#content_container").html().length > 0) {
			$('#print_save').modal('show');
		}
	});

	$('.save_content').on('click', function(e) {
		e.preventDefault();
		var content = $(".content").html().replace(' selected_item', '');
    	

		//console.log(content);
		var file_name = $('input[name=file_name]').val();
		$('.loader_wall').show();
		$.ajax({
			url: "{{ route('print.update', $id) }}",
			type: "post",
			data: {
				name: file_name,
				id: {{ $id }},
				_method: 'patch',
				_token: '{{ csrf_token() }}',
				data: content
			},
			dataType: 'json',
			beforeSend: function() {},
			success: function(res) {
				window.location.replace("{{ route('print.index') }}");
				//$('.loader_wall').hide();
			}
		});
	});


	function setWorkSpace(a) {
		$('.text_properties #submit').off().on('click', function() {

			console.log(a);
			var current = a;
			var ancestor = $(this).closest('.property');
			var background = ancestor.find('input[name=background_color]').val();
			var margin_top = ancestor.find('input[name=margin_top]').val();
			var margin_right = ancestor.find('input[name=margin_right]').val();
			var margin_bottom = ancestor.find('input[name=margin_bottom]').val();
			var margin_left = ancestor.find('input[name=margin_left]').val();
			var font_size = ancestor.find('select[name=font_size]').val();
			var font_color = ancestor.find('input[name=color]').val();
			var font_family = ancestor.find('select[name=fontfamily]').val();

			current.css('background', background);

			$('.workspace *:not(.fa)').css({
				"font-family": font_family,
				"font-size": font_size + 'px',
				"color": font_color
			});

			//.label_result, .value_result, .text_result
			//alert(width+ " "+ height);
		});
	}


	function setLabelProperty(a) {
		$('.text_properties #submit').off().on('click', function() {

			console.log(a);
			var current = a;
			var current_value = a.closest('.draggable').find('.value_result');
			var ancestor = $(this).closest('.property');
			var label = ancestor.find('input[name=label]').val();
			var value = ancestor.find('select[name=value]').val();
			var value_text = ancestor.find('select[name=value] option:selected').text();
			var bold = ancestor.find('input[name=bold]:checked');
			var fontsize = ancestor.find('select[name=font_size]').val();
			var fontfamily = ancestor.find('select[name=fontfamily]').val();
			var color = ancestor.find('input[name=color]').val();

			current.css({
				"color": color,
				"font-size": fontsize + 'px',
				"font-family": fontfamily
			});

			current_value.css({
				"color": color,
				"font-size": fontsize + 'px',
				"font-family": fontfamily
			});

			if (bold.is(":checked")) {
				current.css({
					"font-weight": "bold"
				});

				current_value.css({
					"font-weight": "bold"
				});
			} else {
				current.css({
					"font-weight": ""
				});

				current_value.css({
					"font-weight": ""
				});
			}

			a.text(label);
			a.closest('.draggable').find('.value_result').text(value_text);
			a.closest('.draggable').find('.value_result').attr('data-value', value);
		});
	}

	function setValueProperty(a) {
		$('.text_properties #submit').off().on('click', function() {

			console.log(a);
			var current = a;
			var ancestor = $(this).closest('.property');
			var value = ancestor.find('select[name=value]').val();
			var value_text = ancestor.find('select[name=value] option:selected').text();
			var bold = ancestor.find('input[name=bold]');
			var fontsize = ancestor.find('select[name=font_size]').val();
			var fontfamily = ancestor.find('select[name=fontfamily]').val();
			var color = ancestor.find('input[name=color]').val();
			current.css({
				"color": color,
				"font-size": fontsize + 'px',
				"font-family": fontfamily
			});
			if (bold.is(":checked")) {
				current.css({
					"font-weight": "bold"
				});
			} else {
				current.css({
					"font-weight": ""
				});
			}
			a.text(value_text);
			a.attr('data-value', value);
		});
	}

	function setTextProperty(a) {
		$('.text_properties #submit').off().on('click', function() {

			console.log(a);
			var current = a;
			var ancestor = $(this).closest('.property');
			var text_value = ancestor.find('textarea[name=description]').val();
			var bold = ancestor.find('input[name=bold]');
			var fontsize = ancestor.find('select[name=font_size]').val();
			var fontfamily = ancestor.find('select[name=fontfamily]').val();
			var color = ancestor.find('input[name=color]').val();
			current.css({
				"color": color,
				"font-size": fontsize + 'px',
				"font-family": fontfamily
			});
			if (bold.is(":checked")) {
				current.css({
					"font-weight": "bold"
				});
			} else {
				current.css({
					"font-weight": ""
				});
			}
			a.html(text_value.replace(/\r?\n/g, '<br />'));
		});
	}

	function setRectangleProperty(a) {
		$('.text_properties #submit').off().on('click', function() {

			console.log(a);
			var current = a;
			var ancestor = $(this).closest('.property');
			var width = ancestor.find('input[name=width]').val();
			var height = ancestor.find('input[name=height]').val();
			var color = ancestor.find('input[name=color]').val();
			var size = ancestor.find('input[name=border_size]').val();
			var border_style = ancestor.find('select[name=border_type]').val();
			current.css({
				"width": width + 'px',
				"height": height + 'px',
				"border-width": size + 'px',
				"border-style": border_style,
				"border-color": color
			});
			//alert(width+ " "+ height);
		});
	}

	function setLineProperty(a) {
		$('.text_properties #submit').off().on('click', function() {

			console.log(a);
			var current = a;
			var ancestor = $(this).closest('.property');
			var width = ancestor.find('input[name=width]').val();
			var color = ancestor.find('input[name=color]').val();
			var size = ancestor.find('input[name=border_size]').val();
			var border_style = ancestor.find('select[name=border_type]').val();
			current.css({
				"width": width + 'px',
				"border-bottom-width": size + 'px',
				"border-style": border_style,
				"border-color": color
			});
			//alert(width+ " "+ height);
		});
	}

	function setImageProperty(a) {
		$('.text_properties #submit').off().on('click', function() {

			//console.log(a);
			var current = a;
			var ancestor = $(this).closest('.property');
			var image = ancestor.find('input[name=image]').prop('files')[0];
			var width = ancestor.find('input[name=width]').val();
			var height = ancestor.find('input[name=height]').val();
			var form_data = new FormData();
			form_data.append('image', image);
			form_data.append('_token', '{{ csrf_token() }}');
			//console.log(image);

			$.ajax({
				url: "{{ route('print_image') }}",
				type: "post",
				data: form_data,
				contentType: false,
				cache: false,
				processData: false,
				beforeSend: function() {
					current.removeAttr('src');
				},
				success: function(res) {
					console.log(res);
					current.parent().css({
						"width": width,
						"height": height
					});
					current.attr('src', res);
				}
			});


		});
	}


	$('.draggable').draggable({
		//containment: '#content',
		cursor: 'move',
		//snap: '#content',
		drag: function( event, ui ) {
			var a = 0;
			$(ui.helper.closest('.content_container').find('.draggable')).each(function() {
				if(parseInt($(this).css('top').replace('px', '')) > a) {
					a = parseInt($(this).css('top').replace('px', ''));
				}
			});
			ui.helper.closest('.content_container').css('height', (a+ 45)+'px');
		}
	});

	$('.draggable').each(function() {
		var top = $(this).position().top + 'px';
		var left = $(this).position().left + 'px';
		$(this).css({
			top: top,
			left: left
		});
	}).css({
		position: 'absolute'
	});

});

function makeDraggable() {
	$(this).draggable({
		//containment: '#content',
		cursor: 'move',
		//snap: '#content',
		drag: function( event, ui ) {
			var a = 0;
			$(ui.helper.closest('.content_container').find('.draggable')).each(function() {
				if(parseInt($(this).css('top').replace('px', '')) > a) {
					a = parseInt($(this).css('top').replace('px', ''));
				}
			});
			ui.helper.closest('.content_container').css('height', (a+ 45)+'px');
		}
	});

	$(this).css({
		position: 'absolute'
	});

	var top = $(this).position().top + 'px';
	var left = $(this).position().left + 'px';
	$(this).css({
		top: top,
		left: left
	});
}

function rgb2hex(rgb) {
	if (/^#[0-9A-F]{6}$/i.test(rgb)) return rgb;

	rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
	function hex(x) {
		return ("0" + parseInt(x).toString(16)).slice(-2);
	}
	return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
}
</script> 
@stop
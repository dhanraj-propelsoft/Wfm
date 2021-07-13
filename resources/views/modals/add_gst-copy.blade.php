
$('input[name=item_name]').on('click', function() {
	var obj = $(this);
	var container = $(this).closest('.form-group');
  
		if((container.find('.item_modal_container')).length > 0) {

			$(this).closest('.form-group').find('.item_modal_container').remove();
			
		}
		
			$(this).closest('.form-group').append(`<div style="position: absolute; background: #fff; left: 50px; z-index:2; min-width: 450px; display: block;" class="row  gst_popover item_modal item_modal_container" id="item_modal_container">
				

		  		<div style="padding-top: 15px;" class="col-md-12">
					<label class="control-label" style="font-weight: bold">Search & Add Items</label>
					<br>
				</div>
					
				<div class="col-md-12">

					<div class="row">
						<div class="form-group col-md-12">
							{{ Form::label('global_model','Item',array('class' => 'control-label'))}}
							<div class="ui-widget">
								{{ Form::text('global_model',null,['class' => 'form-control unique ','placeholder' => 'Search for Items...'])}}

							</div>
     					</div>
  					</div>

			  		<div class="row">
						<div class="form-group col-md-6"> 
							{{ Form::label('global_main_category', 'Main Category', array('class' => 'control-label required')) }}

					  		{!! Form::select('global_main_category',  ['' => 'Select Category'] , null, ['class' => 'select_item form-control', 'id' => 'global_main_category']) !!}
					  							  		
						</div>
												
						<div class="form-group col-md-6"> 
							{{ Form::label('global_category', 'Category', array('class' => 'control-label  required')) }}
					  		
					  		{!! Form::select('global_category',[] , null, ['class' => 'select_item form-control', 'id' => 'global_category','placeholder'=>'Select Category']) !!}

					  	</div>
					</div>
						
					<div class="row">
						<div  class="form-group col-md-6"> 
							{{ Form::label('global_type', 'Type', array('class' => 'control-label')) }}
					  		
					  		{!! Form::select('global_type', $itemtype , $item_type, ['class' => 'select_item form-control item_type', 'id' => 'global_type']) !!}

					  	</div>						
												
						<div  class="form-group col-md-6"> 
							{{ Form::label('global_make', 'Make', array('class' => 'control-label')) }}
				  			
				  			{!! Form::select('global_make',  $make , null, ['class' => 'select_item form-control', 'id' => 'global_make']) !!} 

							{!! Form::text('global_make_name', null, ['class' => 'form-control ', 'id' => 'global_make_name', 'style' => 'display: none;']) !!} 

							<a style=" color: #006bcf;" class="global_make">Add Make</a> 
				  		</div>						
					</div>

					<div class="row">
						<div style="display: block;" class="form-group col-md-6"> 
							{{ Form::label('identifier_a', 'Identifier 1', array('class' => 'control-label ')) }}				  			
							
							{!! Form::text('identifier_a', null, ['class' => 'form-control', 'id' => 'identifier_a']) !!}
						</div>
						
					</div>

					<div style="width: 100%;" class="clearfix"></div>
											
					<div style="display: block; justify-content: initial;" class="modal-footer col-md-12">
						<!-- <a style="color: #007bff; cursor: pointer; display: none;" class="float-left add_global_item">Add New Item</a> -->
				  		<button type="button" class="btn btn-default cancel_modal" >Cancel</button>
				 		<button type="button" class="float-right btn btn-success add_item">Add selected Item</button>


				 		<button type="button" class="float-right btn btn-success save_item" id="save_item">Save Entered Item</button>

				 		<!-- <button type="button" class="float-right btn btn-success add_item" id="save_item">Add</button> -->

					</div>
				</div>

			</div>
		
		`);



		


var global_main_category = container.find('select[name=global_main_category]');
var global_category = container.find('select[name=global_category]');
var global_type = container.find('select[name=global_type]');
var global_make = container.find('select[name=global_make]');

var identifier_a = container.find('input[name=identifier_a]');
var saveitem = container.find('#save_item');
var add_item = container.find('.add_item');
var global_model = container.find('input[name=global_model]');

global_main_category.empty();
global_main_category.append("<option value=''>Select Main Category</option>");
global_main_category.val("").trigger("change");

//global_category.empty();
//global_category.append("<option value=''>Select Category</option>");
global_category.val("").trigger("change");

//global_type.empty();
//global_type.append("<option value=''>Select Type</option>");

//global_type.val("").trigger("change");

//global_make.empty();
//global_make.append("<option value=''>Select Make</option>");
global_make.val("").trigger("change");


		var item_type = $('input[name=type]:checked').val();

		

		$("input[name=global_model]").autocomplete({
		     	
		     	source: function(request, response) {
	    			$.getJSON("{{ route('item_search') }}", { term: $("input[name=global_model]").val(), type_id: item_type }, 
	              	response);
  				},
		      	minLength: 2,

		      	select: function( event, ui ) {			      		

		       		$('input[name=item_name]').val(ui.item.label);			       		       		    		

		       		var global_main_category = $('select[name=global_main_category]').find("option:selected").val(ui.item.main_category_id).text(ui.item.main_category_name);

		       		$('select[name=global_category]').find("option:selected").val(ui.item.category_id).text(ui.item.category_name);

		       		$('select[name=global_make]').find("option:selected").val(ui.item.make_id).text(ui.item.make_name);
		       		<!-- $('select[name=global_category]').val(ui.item.category_id); -->

		       		$('select[name=global_type]').val(ui.item.type_id);

		       		<!-- $('select[name=global_make]').val(ui.item.make_id);   -->  	

		       		$('input[name=item_id]').val(ui.item.id);

		       		//$('.item_modal_container').remove();		       		

		       		var tax = $('input[name=item_name]').closest('.modal-container').find('select[name=sales_tax_id]');

		       		var purchase_tax = $('input[name=item_name]').closest('.modal-container').find('select[name=purchase_tax_id]');

		       		var gst = ui.item.gst;

		       		$('#save_item').prop('disabled', true);
		       		

		       	add_item.on('click', function() {

		       		container.find('.item_modal_container').remove();

		       		$('input[name=global_main_category]').val(ui.item.main_category_name);
		       		$('input[name=global_category]').val(ui.item.category_name);
		       		$('input[name=global_type]').val(ui.item.type_name);
		       		$('input[name=global_make]').val(ui.item.make_name);

		       		if(gst != "" && gst != null) {
						$('input[name=hsn]').val(gst);

						$.ajax({
							url: "{{ route('search_gst_code') }}",
							type: 'post',
							data: {
							 	_token : '{{csrf_token()}}',
							 	code: gst
							},
							success:function(data) {
								if(tax.length > 0) {
								console.log(tax.find('option[data-value="'+data+'"]'));
									tax.val(tax.find('option[data-value="'+data+'"]').val()).trigger("change");
								}
								if(purchase_tax.length > 0) {
								console.log(purchase_tax.find('option[data-value="'+data+'"]'));
									purchase_tax.val(purchase_tax.find('option[data-value="'+data+'"]').val()).trigger("change");
								}
							}
						});
					}
				});



		      }
		});


		$(".cancel_modal").on('click', function() {
			container.find('.item_modal_container').remove();		
		});	


		

		$("#global_main_category, #global_category, #global_type, #global_make").on('change', function() {
			$('#save_item').prop('disabled', false);		
		});		


		$('body').on('click', '.global_make', function(e) {
			e.preventDefault();

				var select = $(this).closest('.form-group').find('select');
				var input = $(this).closest('.form-group').find('input:text');
				var obj = $(this);

				$('#save_item').prop('disabled', false);
				
				if(select.is(':visible')) {
					input.show();
					select.hide();
					select.val("");
					obj.text('Select Make');
				} else if(input.is(':visible')) {
					select.show();
					input.hide();
					input.val("");
					obj.text('Add Make');
				}	
				
		});	




		$('body').on('click', '.save_item', function(e) {
			e.preventDefault();

			global_model.closest('div').find('span.error').remove();
			global_main_category.closest('div').find('span.error').remove();
			global_category.closest('div').find('span.error').remove();
			

			var global_make_name = '';

			if(global_make.val() == ''){
				global_make_name = $('input[name=global_make_name]').val();
			} else {
				global_make_name = global_make.val();
			}

			if(global_model.val() == "") {

        		global_model.closest('div').append('<span class="error" style="color:red">Enter Item Name</span>');
        	}    	


			else if(global_main_category.val() == "") {

        		global_main_category.closest('div').append('<span class="error" style="color:red">Select Main Category</span>');

        	}else if(global_category.val() == "") {

        		global_category.closest('div').append('<span class="error" style="color:red">Select Category</span>');
        	}
        	else{

			
			if((global_category != "" || $.trim(global_category).length > 0 ) && $.trim(global_model).length > 0) {
			
				$.ajax({
					url: "{{ route('add_global_item') }}",
					type: 'post',
					data: {
					 	_token : '{{csrf_token()}}',

						global_model: global_model.val(),
					 	global_main_category: global_main_category.val(),
					 	global_category: global_category.val(),					 	
					 	global_type:global_type.val(),					 	
					 	global_make: global_make_name,					 	
					 	identifier_a: identifier_a.val()
					},
					success:function(data, textStatus, jqXHR) {
						
						container.find('.item_modal_container').remove();			

						var model = data.data.model[0];
						var item = data.data.item;

						$('input[name=item_name]').val(item.name);
						$('input[name=global_main_category]').val(model.main_category_name);
			       		$('input[name=global_category]').val(model.category_name);
			       		$('input[name=global_type]').val(model.type_name);
			       		$('input[name=global_make]').val(model.make_name);
			       		$('input[name=identifier_a]').val(item.identifier_a);

			       		$('input[name=item_id]').val(item.id);

						
					}
				});
			}

		}				
			
		});	



		$.ajax({
			url: "{{ route('item.get_category_type') }}",
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				id: $('input[name=type]:checked').val()
			},
			success:function(data, textStatus, jqXHR) {
				var main_category = data.data.main_category;
				var category = data.data.category;
				var type = data.data.type;
				var make = data.data.make;
				var model = data.data.model;
                //alert(main_category.length);
				
				if(main_category.length > 0) {
					global_main_category.closest('.form-group').show();
					for(var i in main_category) {
						global_main_category.append(`<option value='`+main_category[i].id+`'>`+main_category[i].name+`</option>`);
					}	
				} else {
					container.find('.item_modal_container').html("<label class='required'>Select Item Type</label>");
				}
				
				if(category.length > 0) {
					global_category.closest('.form-group').show();
					for(var i in category) {
						global_category.append(`<option value='`+category[i].id+`'>`+category[i].name+`</option>`);
					}
				} else {
					//global_category.closest('.form-group').hide();
				}
				
				if(type.length > 0) {
					global_type.closest('.form-group').show();
					for(var i in type) {
						global_type.append(`<option value='`+type[i].id+`'>`+type[i].name+`</option>`);
					}
				} else {
					//global_type.closest('.form-group').hide();
				}
				
				if(make.length > 0) {
					global_make.closest('.form-group').show();
					for(var i in make) {
						global_make.append(`<option value='`+make[i].id+`'>`+make[i].name+`</option>`);
					}
				} else {
					//global_make.closest('.form-group').hide();
				}
				
				if(model.length > 0) {
					global_model.closest('.form-group').show();
					for(var i in model) {
						global_model.append(`<option data-gst='`+model[i].hsn+`' value='`+model[i].id+`'>`+model[i].name+`</option>`);
					}
				} else {
					//global_model.closest('.form-group').hide();
				}
			}

		});


		global_main_category.on('change', function() {

			var obj = $(this);
			var id = obj.val();

			
			global_category.empty();
			global_category.append("<option value=''>Select Category</option>");
			global_category.val("").trigger("change");

			global_type.empty();
			global_type.append("<option value=''>Select Type</option>");
			global_type.val("").trigger("change");

			global_make.empty();
			global_make.append("<option value=''>Select Make</option>");
			global_make.val("").trigger("change");

			
			if(id != "") {
			$(".add_global_item").show();
			identifier_a.closest('.form-group').show();
				$.ajax({
					url: "{{ route('item.get_main_categories') }}",
					type: 'post',
					data: {
						_token: '{{ csrf_token() }}',
						id:id,
						identifier_a: identifier_a.val()
					},
					success:function(data, textStatus, jqXHR) {
						var category = data.data.category;
						var type = data.data.type;
						var make = data.data.make;
						var model = data.data.model;


						if(category.length > 0) {
							global_category.closest('.form-group').show();
							for(var i in category) {
								global_category.append(`<option value='`+category[i].id+`'>`+category[i].name+`</option>`);
							}
						} else {
							global_category.closest('.form-group').show();
						}
						
						if(type.length > 0) {
							global_type.closest('.form-group').show();
							for(var i in type) {
								global_type.append(`<option value='`+type[i].id+`'>`+type[i].name+`</option>`);
							}
						} else {
							global_type.closest('.form-group').show();
						}

						
						if(make.length > 0) {
							global_make.closest('.form-group').show();
							for(var i in make) {
								global_make.append(`<option value='`+make[i].id+`'>`+make[i].name+`</option>`);
							}
						} else {
							global_make.closest('.form-group').show();
						}
						
						if(model.length > 0) {
							global_model.closest('.form-group').show();
							for(var i in model) {
								global_model.append(`<option data-gst='`+model[i].hsn+`' value='`+model[i].id+`'>`+model[i].name+`</option>`);
								
							}
						} else {
							//global_model.closest('.form-group').show();
						}	
					}

				});
			}
			else {
				global_category.closest('.form-group').show();
				global_type.closest('.form-group').show();
				global_make.closest('.form-group').show();
				//global_model.closest('.form-group').hide();
				identifier_a.closest('.form-group').show();
				$(".add_global_item").hide();
			}

		});


		global_category.on('change', function() {

			var obj = $(this);
			var id = obj.val();
			
			global_type.empty();
			global_type.append("<option value=''>Select Type</option>");
			global_type.val("").trigger("change");

			global_make.empty();
			global_make.append("<option value=''>Select Make</option>");
			global_make.val("").trigger("change");

			
			
			if(id != "") {
				$.ajax({
					url: "{{ route('item.get_categories') }}",
					type: 'post',
					data: {
						_token: '{{ csrf_token() }}',
						id:id,
						identifier_a: identifier_a.val()
					},
					success:function(data, textStatus, jqXHR) {
						var type = data.data.type;
						var make = data.data.make;
						var model = data.data.model;

						if(type.length > 0) {
							global_type.closest('.form-group').show();
							for(var i in type) {
								global_type.append(`<option value='`+type[i].id+`'>`+type[i].name+`</option>`);
							}
						} else {
							global_type.closest('.form-group').show();
						}
						
						if(make.length > 0) {
							global_make.closest('.form-group').show();
							for(var i in make) {
								global_make.append(`<option value='`+make[i].id+`'>`+make[i].name+`</option>`);
							}
						} else {
							global_make.closest('.form-group').show();
						}
						
						if(model.length > 0) {
							global_model.closest('.form-group').show();
							for(var i in model) {
								global_model.append(`<option data-gst='`+model[i].hsn+`' value='`+model[i].id+`'>`+model[i].name+`</option>`);
							}
						} else {
							//global_model.closest('.form-group').hide();
						}	
					}

				});
			} else {
				global_type.closest('.form-group').show();
				global_make.closest('.form-group').show();
				//global_model.closest('.form-group').hide();
			}

		});



		global_type.on('change', function() {

			var obj = $(this);
			var id = obj.val();
			
			global_make.empty();
			global_make.append("<option value=''>Select Make</option>");
			global_make.val("").trigger("change");

			
			
			if(id != "") {
				$.ajax({
					url: "{{ route('item.get_types') }}",
					type: 'post',
					data: {
						_token: '{{ csrf_token() }}',
						id:id,
						identifier_a: identifier_a.val()
					},
					success:function(data, textStatus, jqXHR) {
						var make = data.data.make;
						var model = data.data.model;

						if(make.length > 0) {
							global_make.closest('.form-group').show();
							for(var i in make) {
								global_make.append(`<option value='`+make[i].id+`'>`+make[i].name+`</option>`);
							}
						} else {
							global_make.closest('.form-group').show();
						}
						
						if(model.length > 0) {
							global_model.closest('.form-group').show();
							for(var i in model) {
								global_model.append(`<option data-gst='`+model[i].hsn+`' value='`+model[i].id+`'>`+model[i].name+`</option>`);
							}
						} else {
							//global_model.closest('.form-group').show();
						}	
					}

				});
			} else {
				global_make.closest('.form-group').show();
				//global_model.closest('.form-group').hide();
			}

		});




		global_make.on('change', function() {

			var obj = $(this);
			var id = obj.val();
			
			
			
			if(id != "") {
				$.ajax({
					url: "{{ route('item.get_make') }}",
					type: 'post',
					data: {
						_token: '{{ csrf_token() }}',
						id:id,
						identifier_a: identifier_a.val()
					},
					success:function(data, textStatus, jqXHR) {

						var model = data.data.model;

						if(model.length > 0) {
							global_model.closest('.form-group').show();
							for(var i in model) {
								global_model.append(`<option data-gst='`+model[i].hsn+`' value='`+model[i].id+`'>`+model[i].name+`</option>`);
							}
						} else {
							//global_model.closest('.form-group').hide();
						}	
					}

				});
			} else {
				//global_model.closest('.form-group').hide();
			}

		});	


		


	});

	



	$('input[name=hsn]').on('click', function() {
		var obj = $(this);
		var container = $(this).closest('.form-group').find('.gst_container');

		if(container.length > 0) {
			$(this).closest('.form-group').find('.gst_container').remove();
		}
			$(this).closest('.form-group').append(`<div style="position: absolute; background: #fff; left: 50px; z-index:2; min-width: 450px; display: block;" class="row gst_container gst_popover gst_no">
			  <div style="padding-top: 15px;" class="col-md-12">
			    <label class="control-label" style="font-weight: bold">SEARCH HSN Code by any option</label>
			    <br>
			  </div>
			  <div class="col-md-12">
			    <div class="row">
			      <div class="col-md-6">
			        <label class="control-label" for="keyword">Keyword</label>
			        <input name="keyword" placeholder="e.g. horses, asses, mules" type="text" class="form-control" />
			      </div>
			      <div class="col-md-6">
			        <label class="control-label" for="code">Code</label>
			        <input name="code" placeholder="e.g. 11023000" type="text" class="form-control" />
			      </div>
			    </div>
			  </div>
			  <div class="col-md-12">
			    <div class="row">
			      <div style="margin-top: 5px" class="col-md-8">
			        <label class="control-label" for="chapter">Chapter</label>
			        <select name="chapter" class="select2_category form-control">
			        </select>
			      </div>
			      <div class="col-md-3"> <a style="float: right; margin: 25px 5px 5px; color: #fff;" class="btn btn-success search_gst">Search</a> </div>
			    </div>
			  </div>
			  <div class="col-md-12" style="height: 150px; overflow-y: auto;">
			    <table class="table gst_table">
			      <tbody>
			      </tbody>
			    </table>
			  </div>
			</div>
		`);



		$('select[name=chapter]').select2({ 
			dropdownParent: $('select[name=chapter]').parent()
		});

		if($('input[name=type]:checked').val() != null) {
			get_chapter($('input[name=type]:checked').val());
		}

		$('.search_gst').on('click', function() {
			var gst_table = $('.gst_table tbody');
			gst_table.empty();
			var keyword = $('input[name=keyword]').val();
			var code = $('input[name=code]').val();
			var chapter = $('select[name=chapter]').val();

			if(keyword != null || code != null || chapter != null) {

				$('.loader_wall_onspot').show();

				$.ajax({
					url: "{{ route('search_gst') }}",
					type: 'post',
					data: {
					 	_token : '{{csrf_token()}}',
					 	keyword: keyword,
					 	code: code,
					 	chapter: chapter,
					 	type: $('input[name=type]:checked').val()
					},
					dataType: "json",

					success:function(data, textStatus, jqXHR) {
						for(var i in data) {
						gst_table.append(`<tr>
							<td>`+data[i].code+`</td>
							<td>`+data[i].description+`</td>
							<td>`+data[i].rate_percent+`</td>
							<td><a data-code="`+data[i].code+`" data-rate="`+data[i].rate+`" style="float: right; margin: 5px; color: #fff;" class="grid_label badge badge-primary add_gst">Add</a></td></tr>`);

						}
						$('.loader_wall_onspot').hide();
					}
				});
			}
		});
		

		$(this).closest('.form-group').find('.gst_container').show();

	});


	$(".modal-body").on('click', function(e) {
		if (!$(e.target).closest('.gst_no').hasClass('gst_no')) {
			if (!$(e.target).hasClass('gst_no')) {
				$('.gst_container').remove();
			}
		}
	});	


	$(".modal-body").on('click', function(e) {
		if (!$(e.target).closest('.item_modal').hasClass('item_modal')) {
			if (!$(e.target).hasClass('item_modal')) {
				$('.item_modal_container').remove();
			}
		}
	});

	$('.modal-body').on('click', '.add_gst', function() {
		$(this).closest('.gst_container').closest('.form-group').find('input[name=hsn]').val($(this).data('code'));
		var tax = $(this).closest('.modal-container').find('select[name=sales_tax_id]');
		var purchase_tax = $(this).closest('.modal-container').find('#purchase_tax_id');
		var service_purchase_taxid = $(this).closest('.modal-container').find('#service_purchase_taxid');

		if($(this).data('rate') != "") {
			var tax_value = $('.modal-container').find('select[name=sales_tax_id] option[data-value="'+$(this).data('rate')+'"]').val();
			$(this).closest('.gst_container').remove();

			if(tax.length > 0) {
				tax.val(tax_value).trigger("change");
			}
			if(purchase_tax.length > 0) {
				purchase_tax.val(tax_value).trigger("change");
			}
			if(service_purchase_taxid.length > 0) {
				service_purchase_taxid.val(tax_value).trigger("change");
			}
		}
		
	});


	$('input[name=type]').on('change', function() {

		var obj = $(this);
		var id = obj.val();

		if(obj.attr("id") == 'service') {
			$('.main_inventory').hide();
			$('input[name=hsn]').closest('.form-group').find('label').text('SAC');
			$('input[name=hsn]').attr('placeholder', 'SAC');
			$('.unit, .purchase').hide();
			$('input[name=purchase]').prop("checked", false);
		} else {
			$('.main_inventory').show();
			$('.unit, .purchase').show();
			$('input[name=hsn]').closest('.form-group').find('label').text('HSN');
			$('input[name=hsn]').attr('placeholder', 'HSN');
			$('input[name=purchase]').prop("checked", true);
		}

		get_chapter(id);

	});


	function get_chapter(id) {

		$('select[name=chapter]').empty();

		var options = "<option value=''>Select Chapter</option>";

		if($('select[name=chapter]').length > 0) {
			$.ajax({
				 url: "{{ route('get_chapter') }}",
				 type: 'post',
				 data: {
				 	_token : '{{csrf_token()}}',
				 	id: id,
				 },
				 dataType: "json",
				 success:function(data, textStatus, jqXHR) {

				 	for(i in data) {
				 		options += "<option value='"+data[i].chapter+"'>"+data[i].name+"</option>";
				 	}

				 	$('select[name=chapter]').append(options);
				 	$('select[name=chapter]').val("").trigger("change");
				 }
			});
		}
	}

	


	

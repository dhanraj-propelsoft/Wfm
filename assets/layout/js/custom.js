$(window).on('load', function() {
	setTimeout(function() {
		$('.loader_wall').fadeOut(400, "linear");
	}, 300);
});

$('.rearrangedate').each(function() {
	if ($(this).val() == '00-00-0000' || $(this).val() == '0000-00-00') {
		$(this).val("");
	}
});

function alert_message(message, type) {

		if(type == "success") {
			$('.alert-success').text(message).show();
		} else if(type == "error") {
			$('.alert-danger').text(message).show();
		}

		setTimeout(function() { $('.alert').fadeOut(); }, 2800);
	}

function call_back(data, modal, message, id = null) {
		datatable.destroy();

		if($('.edit[data-id="' + id + '"]')) {
			$('.edit[data-id="' + id + '"]').closest('tr').remove();
		}

		$('.data_table tbody').prepend(data);
		datatable = $('#datatable').DataTable(datatable_options);
			
		$('.crud_modal').modal('hide');

		alert_message(message, "success");
  }
  function call_back_only(data, modal, message, id = null) {
		datatable.destroy();

		if($('.edit[data-id="' + id + '"]')) {
			$('.edit[data-id="' + id + '"]').closest('tr').remove();
		}
		//$('#datatable').DataTable().clear().draw();
		datatable = $('#datatable').DataTable(datatable_options).clear();
		$('.data_table tbody').prepend(data);
			
		$('.crud_modal').modal('hide');

	//	alert_message(message, "success");
  }
	function call_back_optional(data, modal, message, id = null) 
  	{
		  datatable.destroy();

		  
		  datatable=$('#datatable').DataTable()
		  datatable.clear().draw();


		  if($.trim(data))
		  {

		   data = data.replace(/^\s*|\s*$/g, '');
		   data = data.replace(/\\r\\n/gm, '');

		   var expr = "</tr>\\s*<tr";
		   var regEx = new RegExp(expr, "gm");
		   var newRows = data.replace(regEx, "</tr><tr");
		   datatable.rows.add($(newRows )).draw();
		 }
	}


		function delete_row(id, parent, delete_url, token) {
			$('.delete_modal_ajax').modal('show');
				$('.delete_modal_ajax_btn').off().on('click', function() {
					$.ajax({
						 url: delete_url,
						 type: 'post',
						 data: {
							_method: 'delete',
							_token : token,
							id: id,
							},
						 dataType: "json",
							success:function(data, textStatus, jqXHR) {
								

								if(data.status == '1'){
									datatable.destroy();
									parent.remove();
									datatable = $('#datatable').DataTable(datatable_options);
									$('.delete_modal_ajax').modal('hide');
									alert_message(data.message, "success");
								} else{
									$('.delete_modal_ajax').modal('hide');
									alert_message(data.message, "error");
								}
							},
						 error:function(jqXHR, textStatus, errorThrown) {
							}
						});
				});
		}


		function multidelete(obj, url, token, table = null) {
			var values = [];

			var table_container;

			if(table == null) {
				table_container = obj.closest(".table_container");
			} else {
				table_container = $(table);
			}
			table_container.find('tbody tr').each(function() {
				var value = $(this).find("td:first").find("input:checked").val();
				if(value != undefined) {
					values.push(value);
				}
			});
			
			$('.delete_modal_ajax').modal('show');
			$('.delete_modal_ajax_btn').off().on('click', function() {
				$.ajax({
					url: url,
					type: 'post',
					data: {
						_method: 'delete',
						_token: token,
						id: values.join(",")
					},
					dataType: "json",
					success: function(data, textStatus, jqXHR) {
						datatable.destroy();
						var list = data.data.list;
						for(var i in list) {
							$("input.item_check[value="+list[i]+"]").closest('tr').remove();
						}
						$(obj).closest('.batch_container').hide();
						$('#datatable').find('thead tr th:first :checkbox').prop('indeterminate', false);
						$("input.item_check, input[name=check_all]").prop('checked', false);
						datatable = $('#datatable').DataTable(datatable_options);
						$('.delete_modal_ajax').modal('hide');
					},
					error: function(jqXHR, textStatus, errorThrown) {}
				});
			});
		}

	function change_status(id, obj, status, url, token) {

		$.ajax({
			 url: url,
			 type: 'post',
			 data: {
				id: id,
				_token :token,
				status: status
				},
			 dataType: "json",
				success:function(data, textStatus, jqXHR) {
					if(status == 0) {
						obj.parent().find('label').removeClass('badge-success');
						obj.parent().find('label').addClass('badge-warning');
					}else if(status == 1) {
						obj.parent().find('label').removeClass('badge-warning');
						obj.parent().find('label').addClass('badge-success');
					}
					obj.hide();
					obj.parent().find('label').show();
					obj.parent().find('label').text(obj.find('option:selected').text());
				},
			 error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
	}

	function multi_status(obj, status, url, token) {
		var values = [];
		obj.closest(".table_container").find('tbody tr').each(function() {
			var value = $(this).find("td:first").find("input:checked").val();
			if(value != undefined) {
				values.push(value);
			}
		});
		$.ajax({
				url: url,
				type: 'post',
				data: {
					_token: token,
					id: values.join(","),
					status: status
				},
				dataType: "json",
				success: function(data, textStatus, jqXHR) {
					datatable.destroy();
					var list = data.data.list;
					for(var i in list) {
						if(status == 1) {
							$("input.item_check[value="+list[i]+"]").closest('tr').find('label.status').removeClass('badge-warning');
							$("input.item_check[value="+list[i]+"]").closest('tr').find('label.status').addClass('badge-success');
						}else if(status == 0) {
							$("input.item_check[value="+list[i]+"]").closest('tr').find('label.status').removeClass('badge-success');
							$("input.item_check[value="+list[i]+"]").closest('tr').find('label.status').addClass('badge-warning');
						}
						

						var active_text = $("input.item_check[value="+list[i]+"]").closest('tr').find('label.status').closest('td').find('select').find('option[value="'+status+'"]').text();
						$("input.item_check[value="+list[i]+"]").closest('tr').find('label.status').text(active_text);
					}
					$(obj).closest('.batch_container').hide();
					$('#datatable').find('thead tr th:first :checkbox').prop('indeterminate', false);
					$("input.item_check, input[name=check_all]").prop('checked', false);
					datatable = $('#datatable').DataTable(datatable_options);
				},
				error: function(jqXHR, textStatus, errorThrown) {}
			});
	}
	

$(document).ready(function() {

	$('.loader_wall').css({ top: $("#page-header").height()+'px', left: ($('#sidebar-menu').outerWidth())+'px' });
	

	var body = $('body');
	var sidebar = $('#page-sidebar');
	var pgurl = window.location.href;


	var slimscroll_height;

	var menu = $('#sidebar-menu');

	$('#page-sidebar').css('height', ($(window).height() + 200) + 'px');

	menu.css('height', ($(window).height() + 200) + 'px');

	if (getVisible(menu) > 430) {
		slimscroll_height = (getVisible(menu)) + 'px';
	} else {
		slimscroll_height = (getVisible($('.page-sidebar')) + 450) + 'px';
	}

	menu.slimScroll({
		height: slimscroll_height
	});

	$(this).next().hasClass('sidebar-submenu');

	$('body').on('click', '.sidebar-toggler', function(e) {
		if ($('#sidebar-menu span').is(':visible')) {
			localStorage.setItem('sidebar_closed', '1');
			sidebar_close();
		} else {
			localStorage.setItem('sidebar_closed', '0');
			sidebar_open();
		}

		$('.loader_wall').css({ top: $("#page-header").height()+'px', left: ($('#sidebar-menu').outerWidth())+'px' });
		$('.full_modal_content').css({ top: $("#page-header").height()+'px', left: ($('#sidebar-menu').outerWidth())+'px', width: ($(window).outerWidth()-$('#sidebar-menu').outerWidth()) +'px' });
	});

	$('.accounts-daterange').datepicker({
		format: 'dd-mm-yyyy'
	});

	if (localStorage.sidebar_closed === '0' || localStorage.sidebar_closed === undefined) {
		sidebar_open();
	} else if (localStorage.sidebar_closed === '1') {
		sidebar_close();
	}

	setTimeout(function() {
		$('.alert').fadeOut();
	}, 18000);

	function sidebar_open() {
		$('#header-logo').css('width', '260px');
		$('.company_name, .company_slogan').show();
		$('#sidebar-menu .sidebar-submenu').css('background', 'rgba(255, 255, 255, 0.05)');
		$('#sidebar-menu .header, #sidebar-menu span').show();
		$('#sidebar-menu').css('padding', '5px 20px');
		sidebar.css('width', '260px');
		$('#page-content').css('margin-left', '260px');
		$('.sidebar-toggler').find('i').removeClass().addClass('fa').addClass('fa-angle-left');
		$('#page-sidebar  .slimScrollDiv, #page-sidebar  #sidebar-menu').css('overflow', 'hidden');
		$('.sidebar-submenu').attr('class', 'sidebar-submenu');
		$('#page-sidebar li a.sub-menu').removeClass('hidden');
	}

	function sidebar_close() {
		$('#header-logo').css('width', '100px');
		$('.company_name, .company_slogan').hide();
		$('#sidebar-menu .sidebar-submenu').css('background', '');
		$('#sidebar-menu .header, #sidebar-menu span').hide();
		$('#sidebar-menu .sidebar-submenu span').show();
		$('#sidebar-menu').css('padding', '5px');
		sidebar.css('width', '50px');
		$('#page-content').css('margin-left', '50px');
		$('.sidebar-toggler').find('i').removeClass().addClass('fa').addClass('fa-angle-right');
		$('#page-sidebar  .slimScrollDiv, #page-sidebar  #sidebar-menu').css('overflow', 'visible');
		$('#page-sidebar .sidebar-submenu').addClass('sidebar-submenu-mini').addClass($('#page-sidebar').attr('class'));
		$('#page-sidebar li a.sub-menu').addClass('hidden');
	}

	$('#full-screen').on('click', function() {
		if (screenfull.enabled) {
			screenfull.toggle();
		}
	});

	$('.refresh').on('click', function() {
		location.reload();
	});

	sidebar.find('li a').on('click', function() {
		var obj = $(this);
		if (obj.next().hasClass('sidebar-submenu')) {
			if (localStorage.sidebar_closed === '0' || localStorage.sidebar_closed === undefined) {
				if (obj.closest('li').find('.sidebar-submenu').is(':visible')) {
					obj.next().slideUp();
					obj.removeClass('active');
				} else if (obj.closest('li').find('.sidebar-submenu').is(':hidden')) {
					obj.next().slideDown();
					obj.addClass('active');
				}
			}
		}
	});


	sidebar.find('li').on('mouseenter', function() {
		var obj = $(this).find('a');
		if (localStorage.sidebar_closed === '1') {
			obj.next().show();
		}
	}).on('mouseleave', function() {
		var obj = $(this).find('a');
		if (localStorage.sidebar_closed === '1') {
			obj.next().hide();
		}
	});

	basic_functions();

	$('body').on('change', 'input[name=check_all]', function(e) {
		if ($(this).is(":checked")) {
			$(this).closest('table').find('tbody tr').find('td:first :checkbox').prop('checked', true);
			show_batch($(this));
		} else {
			$(this).closest('table').find('tbody tr').find('td:first :checkbox').prop('checked', false);
			hide_batch($(this));
		}
	});

	$('body').on('change', '.item_check', function(e) {
		if ($(".item_check:checked").length > 0) {
			$(this).closest('table').find('thead tr th:first :checkbox').prop('indeterminate', true);
			show_batch($(this));
		} else {
			$(this).closest('table').find('thead tr th:first :checkbox').prop('indeterminate', false);
			hide_batch($(this));
		}
	});

	$('body').on('click', '.batch_action', function(e) {
		var batch_list = $(this).closest('.batch_container').find('ul.batch_list');

		if (batch_list.is(":visible")) {
			$(this).closest('.batch_container').find('ul.batch_list').hide();
		} else {
			$(this).closest('.batch_container').find('ul.batch_list').show();
		}

	});

	$('body').on('click', '.dashboard_option_action', function(e) {
		var batch_list = $(this).closest('.dashboard_option_container').find('ul.dashboard_option_list');

		if (batch_list.is(":visible")) {
			$(this).closest('.dashboard_option_container').find('ul.dashboard_option_list').hide();
		} else {
			$(this).closest('.dashboard_option_container').find('ul.dashboard_option_list').show();
		}

	});


	$("#sidebar-menu li a").each(function() {
		var link = $(this);
		var data_link = link.attr('data-link');

		if (data_link) {
			var data_list = data_link.split("|");
			for (var i = 0; i < data_list.length; i++) {
				if (pgurl.indexOf(data_list[i]) >= 0) {
					link.addClass("active");
					if (link.closest(".sidebar-submenu")) {
						if (localStorage.sidebar_closed === '0' || localStorage.sidebar_closed === undefined) {
							link.closest(".sidebar-submenu").show();
						} else {
							link.closest(".sidebar-submenu").hide();
						}
						link.closest(".sidebar-submenu").prev().addClass("active");
					}
				}
			}
		}
	});

// ***  change the click 'event' name to 'mouseover' name for mouseover function  ***
	$('.dropdown > a').on('click', function() {
		//$('.drop-menu').slideUp();
		if ($(this).closest('.dropdown').find('.drop-menu').is(':hidden')) {
			$(this).closest('.dropdown').find('.drop-menu').slideDown();
		}
	});
	
// ***  change the click 'event' name to 'mouseover' name for mouseover function  ***
	$("body").on('click', function(e) {
		if (!$(e.target).closest('.dropdown').hasClass('dropdown')) {
			$('.drop-menu').slideUp();
		}
	});


	$("body").on('click', function(e) {


		if (!$(e.target).closest('.search_popover').hasClass('search_popover')) {
			$('.search_popover').hide();
		}

		if (!$(e.target).closest('.batch_container').hasClass('batch_container')) {
			$('.batch_list').hide();
		}

		if (!$(e.target).closest('.dashboard_option_container').hasClass('dashboard_option_container')) {
			$('.dashboard_option_list').hide();
		}

		if (!$(e.target).closest('.discount_picker_container').hasClass('discount_picker_container')) {
			$('.discount_picker').hide();
		}

		if (!$(e.target).closest('.settings_panel').hasClass('settings_panel') && !$(e.target).closest('.side_panel').hasClass('side_panel')) {
			$('.slide_panel_bg').fadeOut();
			$('.settings_panel').animate({ right: "-25%" });
		}

		/*if(!$(e.target).closest('.status').hasClass('status')) {
			$('.status').show();
			$('.approval_status, .active_status').hide();
			$(e.target).closest('td').find('.approval_status, .active_status').show();
			$(e.target).hide();
		} else {
			$('.status').show();
			$('.approval_status, .active_status').hide();
		}*/
	});

	$('body').on('click', '.close_full_modal', function() {
		$('.full_modal_content').html("");
		$('.full_modal_content').removeAttr('style');
		$('.full_modal_content').css({ top: $("#page-header").height()+'px', left: ($('#sidebar-menu').outerWidth())+'px', width: ($(window).outerWidth()-$('#sidebar-menu').outerWidth()) +'px' });
		$('body').css('overflow', '');
		/*$('.full_modal_content').animate({ top:-$('.full_modal_content').outerHeight() + 'px' }, 300, function() { 
			$('.full_modal_content').html("");
			$('.full_modal_content').removeAttr('style');
			$('.full_modal_content').css({ top: '0px', left: ($('#sidebar-menu').outerWidth())+'px', width: ($(window).outerWidth()-$('#sidebar-menu').outerWidth()) +'px' });
			//$('.full_modal_content').animate({top: '0px'}); 
			$('body').css('overflow', '');
		});*/
	});

});

function sidebar_minimized() {
		$('#sidebar-menu .sidebar-submenu').css('background', '');
		$('#sidebar-menu .header, #sidebar-menu span').hide();
		$('#sidebar-menu .sidebar-submenu span').show();
		$('#sidebar-menu').css('padding', '5px');
		$('#page-sidebar').css('width', '50px');
		$('#page-content').css('margin-left', '50px');
		$('.sidebar-toggler').find('i').removeClass().addClass('fa').addClass('fa-angle-right');
		$('#page-sidebar  .slimScrollDiv, #page-sidebar  #sidebar-menu').css('overflow', 'visible');
		$('#page-sidebar .sidebar-submenu').addClass('sidebar-submenu-mini').addClass($('#page-sidebar').attr('class'));
		$('#page-sidebar li a.sub-menu').addClass('hidden');
	}

function format(state) {
	if (state.id == "0" && state.text == "") return $('<i class="add_new_link">+ Add New</i>');

	return state.text;
};

function formatPeople(state) {
	//if (state.id == "0" && state.name == "") return $('<i class="add_new_link">+ Add New</i>');

	if (state.id == "-1" && state.name == "") return $('<i class="add_new_link">Search Globally (Or) + Add New </i>');

	return state.name;
}


function getVisible(el) {
	if (typeof el.offset() !== 'undefined') {
		var scrollTop = $(this).scrollTop();
		var scrollBot = scrollTop + $(this).height();
		var elTop = el.offset().top;
		var elBottom = elTop + el.outerHeight();
		var visibleTop = elTop < scrollTop ? scrollTop : elTop;
		var visibleBottom = elBottom > scrollBot ? scrollBot : elBottom;
		return (visibleBottom - visibleTop);
	}
}

(function($) {
	$.fn.clickToggle = function(func1, func2) {
		var funcs = [func1, func2];
		this.data('toggleclicked', 0);
		this.click(function() {
			var data = $(this).data();
			var tc = data.toggleclicked;
			$.proxy(funcs[tc], this)();
			data.toggleclicked = (tc + 1) % 2;
		});
		return this;
	};
}(jQuery));

function hide_batch(obj) {
	$(obj).closest('.table_container').find('.dataTables_length').show();
	$(obj).closest('.table_container').find('.batch_container').hide();
}

function show_batch(obj) {
	$(obj).closest('.table_container').find('.dataTables_length').hide();
	$(obj).closest('.table_container').find('.batch_container').show();
}

function basic_functions() {

	removeSign();

	$('.select_item').each(function() {
		if ($(this).closest('.modal').length > 0) {
			$(this).select2({
				dropdownParent: $(this).parent()
				//minimumResultsForSearch: -1
			});
		} else {
			$(this).select2();
		}
	});

	$('.tooltips').tooltip(); 

	$('.numbers').keypress(function(e) {
		if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
			return false;
		}
	});

	$('.date-picker, .accounts-date-picker').keypress(function(e) {
		if (e.which != 8 && e.which != 0 && e.which != 45 && (e.which < 48 || e.which > 57)) {
			return false;
		}
	});

	$(".rearrangedate").each(function() {
		var data = $(this).val();
		var mycustomdate = data.split('-');
		//console.log(mycustomdate);
		if (data != "") {
			$(this).attr('value', $.trim(mycustomdate[2]) + '-' + $.trim(mycustomdate[1]) + '-' + $.trim(mycustomdate[0]));
		}
	});

	$(".rearrangedatetext").each(function() {
		var data = $(this).text();
		var mycustomdate = data.split('-');
		$(this).text($.trim(mycustomdate[2]) + '-' + $.trim(mycustomdate[1]) + '-' + $.trim(mycustomdate[0]));
	});

	$('.text, .rearrangedatetext, .datetype').each(function() {
		if ($(this).text() == '00-00-0000' || $(this).text() == '0000-00-00') {
			$(this).text("");
		}
	});

	$('.date-picker').datepicker({
		rtl: false,
		orientation: "left",
		todayHighlight: true,
		autoclose: true
	});

	$('.accounts-date-picker').datepicker({
		rtl: false,
		orientation: "left",
		todayHighlight: true,
		autoclose: true,
		startDate: financialyear_start,
		endDate: financialyear_end,
		format: 'dd-mm-yyyy'
	});

	$('.price').each(function() {
		var price = $(this);
		if (price.val() != "") {
			if (price.val().indexOf(".00") == -1) {
				price.val( parseFloat(price).toFixed(2) );
			}
		}

	});

	$('.price').each(function() {
		if ($(this).val() == "") {
			$(this).val("0.00");
		}
	});

	$('.price').on('focus', function() {

		if ($(this).val() == "0.00") {
			$(this).val("");
		} else if (($(this).val()).length > 0) {
			$(this).val((parseFloat($(this).val())).toFixed(2));
		}
	});

	$('.price').on('focusout', function() {

		if ($(this).val() == "") {
			$(this).val("0.00");
		} else if (($(this).val()).length > 0) {
			$(this).val((parseFloat($(this).val())).toFixed(2));
		}
	});

	$('.price').keypress(function(e) {
		if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
			return false;
		}
	});

	$('.decimal').keypress(function(e) {
		if (e.which != 8 && e.which != 0 && e.which != 43 && e.which != 45 && e.which != 46 && (e.which < 48 || e.which > 57)) {
			return false;
		}
	});

	$(".timepicker-default").timepicker({
		autoclose: !0,
		showSeconds: !0,
		minuteStep: 1
	}), $(".timepicker-no-seconds").timepicker({
		autoclose: !0,
		minuteStep: 5
	}), $(".timepicker-24").timepicker({
		autoclose: !0,
		minuteStep: 5,
		showSeconds: !1,
		defaultTime: false,
		showMeridian: !1
	}), $(".timepicker").parent(".input-group").on("click", ".input-group-btn", function(t) {
		t.preventDefault(), $(this).parent(".input-group").find(".timepicker").timepicker("showWidget")
	});
	

    /*$('.datetimepicker').datetimepicker({
        format: 'dd/MM/yyyy hh:mm:ss',
        language: 'pt-BR'
    });*/

    $('.datetimepicker2').datetimepicker({
      language: 'en',
      pick12HourFormat: true
    });

	


}

function pad(str, max) {
	str = str.toString();
	return str.length < max ? pad("0" + str, max) : str;
}

function removeSign() {

	$("body .removeSign").each(function() {
		if ($.trim(($(this).text())).length > 0) {
			if (!isNaN($(this).text())) {
				var sign = parseFloat($(this).text()).toFixed(2);
				$(this).text(sign.replace('-', ''));
			}
		} else {
			$(this).text("0.00");
		}

	});
}



function negativeSign() {
	$(".negativeSign").each(function() {
		console.log($(this).text());
		if ($.trim(($(this).text())).length > 0) {
			if (!isNaN($(this).text())) {
				if ($(this).text().indexOf('-') > -1) {
					$(this).css('color', 'red');
					$(this).text(parseFloat($(this).text()).toFixed(2));
				}
			}
		}

	});
}

function replaceSign() {
	$(".replaceSign").each(function() {
		if ($.trim(($(this).text())).length > 0) {
			if (!isNaN($(this).text())) {
				if ($(this).text().indexOf('-') > -1) {
					$(this).text(parseFloat($(this).text().replace("-", "")).toFixed(2));
				} else if ($(this).text() == "0.00")  {
					$(this).text(parseFloat($(this).text()).toFixed(2));
				} else {
					$(this).css('color', 'red');
					$(this).text("-"+parseFloat($(this).text()).toFixed(2));
				}
			}
		}

	});
}


function tree_list() {
	$('.tree_list li i').each(function() {
		var icon = $(this);
		var list = $(this).parent().find('ol');

		if (list.is(":visible")) {
			icon.removeClass('fa-minus-square-o').addClass('fa-plus-square-o');
		} else {
			icon.removeClass('fa-plus-square-o').addClass('fa-minus-square-o');
		}

		if (icon.parent().has("ol").length > 0) {
			icon.text('');
			icon.addClass('fa-plus-square-o');
			icon.parent().find('div').first().css('font-weight', 'bold');
			icon.parent().find('div, i').off().on('click', function() {

				if (list.is(":visible")) {
					$(this).parent().find('ol').slideUp();
					icon.removeClass('fa-minus-square-o').addClass('fa-plus-square-o');
				} else {
					$(this).parent().find('ol').slideDown();
					icon.removeClass('fa-plus-square-o').addClass('fa-minus-square-o');
				}

			});
		} else {
			icon.append("&nbsp;");
		}
	});
}



/*WFM   JS*/
/*Error message in textbox in Particular textbox*/
function error_msg(input,field_name)
{
var msg="<p style='color:red' class='error_exist'>"+field_name+" Already  exist</p>";
if($(".error_exist").length==0)
 {

  	if($(input).length)
  	{

  		return   $(input).after(msg);
  	}else{

  		return "invalid input field";
  	}
                            
   }
}

function error_msg_multiple(input,field_name)
{
    $(".error_exist").remove(); 
	if($(".error_exist").length==0)
 {
		for(var i = 0; i < input.length; i++) {
			
			var msg="<p style='color:red' class='error_exist'>"+field_name[i]+" Already  exist</p>";
		// console.log($(input[i]).length);
		  		//console.log($(input[i]).val());
		  	
		  	if($(input[i]).length>0)
		  	{

		  		return   $(input[i]).after(msg);
		  	}else{

		  		return "invalid input field";
		  	}
		}
	}
}
function custom_validator_msg(message)
{
	$(".error_li").html();
	var msg="";
	if(Array.isArray(message)==true){


		$.each(message, function( index, value ) {
			var msg = "<li  class='error_li'>"+value+" </li>";
			console.log(value);
			$(".label_errror").html(value);
		});
	}else{
		var msg = "<li  class='error_li'>"+message+" </li>";
				console.log(msg);
		//	$(".label_errror").html(message);
		//	console.log(message);
		
	}

    return msg;	
//	$('div.alert').not('.alert-important').delay(3000).fadeOut(350);
}
function custom_success_msg(message)
{
	$("div.alert-success>ul>li").html();
	var msg="";
	if(Array.isArray(message)==true){


		$.each(message, function( index, value ) {
			var msg = "<ul><li  class='error_li'>"+value+" </li></ul>";
			$("div.alert-success").html(msg);
		});
	}else{
		var msg = "<ul><li  class='error_li'>"+message+" </li></ul>";
			$("div.alert-success").html(msg);
		
	}
	$("div.alert-success").css("z-index","10").show();
	$('div.alert-success').not('.alert-important').delay(3000).fadeOut(350);
	
}

function Form_uploads(input_id) {
    var formData = new FormData();
    // these image appends are getting dropzones instances
    
    formData.append('upload_files', $('#'+input_id)[0].dropzone.getAcceptedFiles()[0]); // attach dropzone image element

    return formData;
}
/*Error message in textbox in Particular textbox*/


function status_approval(id, obj, status, url, token) {

        $.ajax({
             url: url,
             type: 'post',
             data: {
                id: id,
                _token :token,
                status: status
                },
             dataType: "json",
                success:function(data, textStatus, jqXHR) {
                    if(status == 1) {
                        obj.parent().find('label').removeAttr("style").attr("style","background-color: #ff9933");
    
                    }else if(status == 2) {
                      obj.parent().find('label').removeAttr("style").attr("style","background-color: #33cc33");
                    }else if(status == 3) {
                        obj.parent().find('label').removeAttr("style").attr("style","background-color: #ff3300");
                    }else if(status == 4) {
                        obj.parent().find('label').removeAttr("style").attr("style","background-color: #FFFF00");
                    }
                    obj.hide();
                    obj.parent().find('label').show();
                    obj.parent().find('label').text(obj.find('option:selected').text());
                },
             error:function(jqXHR, textStatus, errorThrown) {
                //alert("New Request Failed " +textStatus);
                }
            });
    }







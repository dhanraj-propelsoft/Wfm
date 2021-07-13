<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PrintTemplateType;
use App\PrintTemplate;
use App\Organization;
use App\Business;
use App\Custom;
use DateTime;
use Session;
use File;

class PrintController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
	$organization_id = Session::get('organization_id');
	$templates = PrintTemplate::select('print_templates.id', 'print_templates.display_name AS name', 'print_templates.status',  'print_template_types.display_name AS type')->leftjoin('print_template_types', 'print_template_types.id', '=', 'print_templates.print_template_type_id')->where('print_templates.organization_id',$organization_id)->where('print_templates.status', 1)->orderby('print_templates.name')->get();
	return view('settings.print', compact('templates'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
	$template_type = PrintTemplateType::where('status', 1)->pluck('display_name', 'id');
	return view('settings.print_create', compact('template_type'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
   //dd($request->all());
	$template_type = PrintTemplateType::find($request->input('template_type'));
	//dd($template_type);

	$template = new PrintTemplate;
	$template->name = $request->input('name');
	$template->display_name = $request->input('name');
	if($template_type->name == "general") {
	  $data = '<style>.item_table {border-collapse: collapse;}@media print {body {-webkit-print-color-adjust: exact;}}
	  </style>
	  <div data-type="portrait" style="width: 273mm; height: 200mm; padding: 57mm 16mm 27mm 16mm; background: rgb(255, 255, 255) none repeat scroll 0% 0%;" class="workspace"><div style="position:relative;" class="header_container"></div>
	  <div style="position:relative;" class="body_container"></div>
	  <div style="position:relative;" class="total_container"></div>
	  <div style="position:relative;" class="footer_container"></div></div>';
	} 
	else if($template_type->name == "payslip") {
		$data = '<style>
			.item_table {
			  border-collapse: collapse;
			  }
			  @media print {
			  body {
				-webkit-print-color-adjust: exact;
			  }
			  }
			</style>
			<div data-type="portrait" style="width: 273mm; height: 200mm; padding: 57mm 16mm 27mm 16mm; background: rgb(255, 255, 255) none repeat scroll 0% 0%;" class="workspace">
			<div style="position: relative; min-height: 140px;  height: 232px;" class="header_container content_container">
			<div style="width: auto; float: left; position: absolute; top: 1px; left: 0px; font-family: Arial, sans-serif; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle">
			<div style="border-style: solid; border-color: rgb(0, 0, 0); border-width: 1px; width: 912px; height: 97px; font-family: Arial, sans-serif; color: rgb(0, 0, 0);" class="rectangle_result"></div>
			<div class="remove" style="display: none;"><i class="fa fa-times"></i></div>
			</div>
			<div style="width: auto; float: left; position: absolute; top: 100px; left: 0px;" class="draggable ui-draggable ui-draggable-handle">
			<div style="border-style: solid; border-color: rgb(0, 0, 0); border-width: 1px; width: 912px; height: 40px;" class="rectangle_result">
			  <div class="value_result" data-value="business_name" style="color: rgb(0, 0, 0); font-size: 26px; font-family: Tahoma, sans-serif; width:100%;">Business Name</div>
			</div>
			<div class="remove" style="display: none;"><i class="fa fa-times"></i></div>
			</div>
			<div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; position: absolute; top: 0px; left: 0px;" class="draggable ui-draggable ui-draggable-handle"></div>
			<div style="width: auto; float: left; position: absolute; top: 141px; left: 0px;" class="draggable ui-draggable ui-draggable-handle">
			<div style="border-style: solid; border-color: rgb(0, 0, 0); border-width: 1px; width: 912px; height: 100px;" class="rectangle_result"></div>
			</div>
			</div>
			<div style="position: relative;float: left; width:914px;" class="body_container content_container">
			  <div class="col_earnings" style="float: left; width: 50%;">
			  <table class="item_table earnings" width="100%" border="0">
			  <thead>
				<tr>
				  <th style="background: rgb(51, 51, 51) none repeat scroll 0% 0%; color: rgb(255, 255, 255); padding: 5px 10px; text-align:left;" width="50%">Earnings</th>
				  <th style="background: rgb(51, 51, 51) none repeat scroll 0% 0%; color: rgb(255, 255, 255); padding: 5px 10px; text-align:right;" width="50%">Amount</th>
				</tr>
			  </thead>
			  <tbody>
				<tr>
				  <td style="padding:5px; text-align:left;"></td>
				  <td style="padding:5px; text-align:right;"></td>
				</tr>
				<tr style="background: #f2f2f2">
				  <td style="padding:5px; text-align:left;"></td>
				  <td style="padding:5px; text-align:right;"></td>
				</tr>
			  </tbody>
			</table>
			  <table style="border-top: none;" class="item_table" width="100%" border="0">
			  <tbody>
				<tr>
				  <td style="padding:5px; text-align:left; font-weight:bold;" width="50%">Total Earnings</td>
				  <td style="padding:5px; text-align:right; font-weight:bold;" width="50%"><span data-value="total_earnings"></span></td>
				</tr>
			  </tbody>
			  </table>
				</div>
			  <div class="col_deductions" style="float: left; width: 50%;">
			  <table class="item_table deductions" width="100%" border="0">
				<thead>
				<tr>
				<th style="background: rgb(51, 51, 51) none repeat scroll 0% 0%; color: rgb(255, 255, 255); padding: 5px 10px; text-align:left;" width="50%">Deductions</th>
				  <th style="background: rgb(51, 51, 51) none repeat scroll 0% 0%; color: rgb(255, 255, 255); padding: 5px 10px; text-align:right;" width="50%">Amount</th>
				</tr>
			  </thead>
			  <tbody>
				<tr>
				  <td style="padding:5px; text-align:left;"></td>
				  <td style="padding:5px; text-align:right;"></td>
				</tr>
				<tr style="background: #f2f2f2">
				  <td style="padding:5px; text-align:left;"></td>
				  <td style="padding:5px; text-align:right;"></td>
				</tr>
			  </tbody>
			</table>
			<table style="border-top: none;" class="item_table" width="100%" border="0">
			  <tbody>
				<tr>
				  <td style="padding:5px; text-align:left; font-weight:bold;" width="50%">Total Deductions</td>
				  <td style="padding:5px; text-align:right; font-weight:bold;" width="50%"><span data-value="total_deductions"></span></td>
				</tr>
			 </tbody>
			</table>
		   	</div>
			</div>
			<div style="position: relative; float: left; width:100%;" class="total_container content_container"> </div>
			<div style="position: relative; height: 140px;  float: left; width: 100%;" class="footer_container content_container">
			<div style="width: auto; float: left; position: absolute; top: 0px; left: 0px;" class="draggable ui-draggable ui-draggable-handle">
			<div style="border-style: solid; border-color: rgb(0, 0, 0); border-width: 1px; width: 912px; height: 35px;" class="rectangle_result"></div>
		   <div class="remove" style="display: none;"><i class="fa fa-times"></i></div>
			</div>
			<div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; position: absolute; top: 55px; left: 3px;" class="draggable ui-draggable ui-draggable-handle">
		   <div class="text_result">Net Pay (In Words): </div>
			<div class="remove" style="display: none;"><i class="fa fa-times"></i></div>
			</div>
			<div style="width: auto; height: 10px; float: left; position: absolute; top: 65px; left: 1px;" class="draggable ui-draggable ui-draggable-handle">
			<div style="color: transparent; border-style: solid; border-color: rgb(0, 0, 0); border-width: 0px 0px 1px; width: 912px;" class="line_result">Static Text</div>
			<div class="remove" style="display: none;"><i class="fa fa-times"></i></div>
			</div>
			<div style="float: left; font-family: Arial, sans-serif; z-index: 1; position: absolute; top: 95px; width: 100%; text-align: center; left: 0px;" class="draggable ui-draggable ui-draggable-handle">
			<div class="text_result" style="color: rgb(0, 0, 0); font-family: Arial, sans-serif; font-size: 12px;">*This is computer generated Payslip. Signature not required!</div>
		   <div class="remove" style="display: none;"><i class="fa fa-times"></i></div>
		   </div>
			<div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute;top: 55px; left: 170px;" class="draggable ui-draggable ui-draggable-handle">
			<div>
			<div style="float: left;" class="value_result" data-value="net_pay_words">Net Pay in Words</div>
			<div class="remove" style="display: none;"><i class="fa fa-times"></i></div>
			</div>
			</div>
			</div>
		</div>';
	} 
	else if($template_type->name == "sale") {
	  $data = '<html class="gr__localhost"><head><link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
				  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
				  <style> .item_table { border-collapse: collapse; border-width: 0px; border: none; } .total_container td { padding: 5px; } @media  print {  } </style> </head><body data-gr-c-s-loaded="true"><style>
		.workspace
		{
		  display: block;
		  page-break-inside: avoid;
		  page-break-before: avoid;
		  page-break-after: avoid;
		  -webkit-region-break-inside: avoid; 
		}

			  </style>
	  <div data-type="portrait" style="width: 210mm; height: 200mm; padding: 27mm 16mm; background: rgb(255, 255, 255);" class="workspace">
	  <div class="invoice_print" style="border:1px solid black;">
		<div style="position: relative; min-height: 200px; height: 528px; font-family: Arial, sans-serif; color: rgb(0, 0, 0);" class="header_container content_container">

		<div style="float: left; position: absolute; top: 88px;">
	   <hr style="border:1px solid black;width: 670px;">
		</div>
		<div style="top: 104px;position: absolute;width: 27%;height: 140px;"></div>
		<div style="border: 1px solid black;top: 106px;position: absolute;width: 35%;height: 140px;left: 215px;"></div>
	  <div style="top: 104px;position: absolute;width: 23%;height: 140px;left: 477px;
	  "></div>
		<div style="width: 215px;height: 65px;display: block;float: left;position: absolute;top: 257px;left: 434px;">
	  <img width="100%" height="100%" class="image_result" alt="image" src="http://mypropelsoft.com/public/print/meter.png">
	  </div>
	  <div style="position: absolute;top: 340px;float: left;left: 481px;border-bottom: 1px solid black;width: 138px;">
	  <span class="value_result" data-value="fuel_checklist" style="font-size: 10px;"></span></div>
	  <div class="complaints" style="    top: 248px;
		  position: absolute;
		  border: 1px solid black;
		  height: 103px;
		  width: 433px;
	  "><span><u>Complaints:</u></span><br><span class="value_result" data-value="complaints" style="font-size:12px;">&nbsp;</span></div>
		<div style="float: left; position: absolute; top: 230px;">
	   <hr style="border:1px solid black;width: 670px;">
		</div>
		<div style="width: 654px;height: 72px;display: block;float: left;position: absolute;top: 356px;left: 15px;font-family: Arial, sans-serif;color: rgb(0, 0, 0);">
		<img width="100%" height="100%" class="image_result" alt="image" style="font-family: Arial, sans-serif; color: rgb(0, 0, 0);" src="http://mypropelsoft.com/public/print/Carimage.png">
	  </div>

	  <div style="position: absolute;top: 439px;left: 15px;border-bottom: 1px solid black;width: 131px;">
	  <span class="value_result" data-value="left" style="font-size: 10px;"></span></div>


	  <div style="position: absolute;top: 439px;left: 176px;border-bottom: 1px solid black;width: 138px;">
	  <span class="value_result" data-value="top" style="font-size: 10px;"></span></div>


	  <div style="position: absolute;top: 439px;left: 580px;border-bottom: 1px solid black;width: 86px;">
	  <span class="value_result" data-value="back" style="font-size: 10px;"></span></div>

	  <div style="position: absolute;top: 439px;left: 483px;border-bottom: 1px solid black;width: 86px;">
	  <span class="value_result" data-value="front" style="font-size: 10px;"></span></div>

	  <div style="position: absolute;top: 439px;left: 342px;border-bottom: 1px solid black;width: 117px;">
	  <span class="value_result" data-value="right" style="font-size: 10px;"></span></div>
		<div style="float: left;position: absolute;top: 333px;">
	   <hr style="border:1px solid black;width: 670px;">
		</div>
		
		<div style="float: left;position: absolute;top: 436px;">
	   <hr style="border:1px solid black;width: 669px;">
		</div>
		</div>
	  <div style="position:relative;top: -74px;" class="body_container">
	  <table style="width:100%;">
	  <tbody><tr><td>
	  <table style="width:50%;border-collapse: collapse;border: 1px solid black;float:left;" class="job_card_table">
	  <thead>
	  <tr>
	  <td style="padding: 5px;border-collapse: collapse;border: 1px solid black;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_s_no">S.no</td>
	  <td style="padding: 5px;border-collapse: collapse;border: 1px solid black;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_items">Items/Jobs</td>
	  <td style="padding: 5px;border-collapse: collapse;border: 1px solid black;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_qty">Qty</td>
	  <td style="padding: 5px;border-collapse: collapse;border: 1px solid black;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_total_price">Total Price(RS)</td>
	  </tr>
	  </thead>
	  <tbody>
	  <tr>
	  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_s_no">&nbsp;</td>
	  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: left;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_items">&nbsp;</td>
	  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_qty">&nbsp;</td>
	  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_total_price">&nbsp;</td>
	  </tr>
	  <tr>
	  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_s_no">&nbsp;</td>
	  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: left;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_items">&nbsp;</td>
	  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_qty">&nbsp;</td>
	  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_total_price">&nbsp;</td>
	  </tr>
	  <tr>
	  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_s_no">&nbsp;</td>
	  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: left;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_items">&nbsp;</td>
	  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_qty">&nbsp;</td>
	  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_total_price">&nbsp;</td>
	  </tr>
	  <tr>
	  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_s_no">&nbsp;</td>
	  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: left;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_items">&nbsp;</td>
	  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_qty">&nbsp;</td>
	  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_total_price">&nbsp;</td>
	  </tr>
	  <tr>
	  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_s_no">&nbsp;</td>
	  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: left;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_items">&nbsp;</td>
	  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_qty">&nbsp;</td>
	  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_total_price">&nbsp;</td>
	  </tr>
	  <tr>
	  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_s_no">&nbsp;</td>
	  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: left;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_items">&nbsp;</td>
	  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_qty">&nbsp;</td>
	  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_igst_amount">&nbsp;</td>
	  </tr>
	  <tr>
	  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_s_no">&nbsp;</td>
	  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: left;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_items">&nbsp;</td>
	  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_qty">&nbsp;</td>
	  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_igst_amount">&nbsp;</td>
	  </tr>
	  <tr>
	  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_s_no">&nbsp;</td>
	  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: left;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_items">&nbsp;</td>
	  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_qty">&nbsp;</td>
	  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_igst_amount">&nbsp;</td>
	  </tr>
	  <tr>
	  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_s_no">&nbsp;</td>
	  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: left;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_items">&nbsp;</td>
	  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_qty">&nbsp;</td>
	  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_igst_amount">&nbsp;</td>
	  </tr>
	  <tr>
	  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_s_no">&nbsp;</td>
	  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: left;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_items">&nbsp;</td>
	  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_qty">&nbsp;</td>
	  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_igst_amount">&nbsp;</td>
	  </tr>
	  <tr>
	  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_s_no">&nbsp;</td>
	  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: left;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_items">&nbsp;</td>
	  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_qty">&nbsp;</td>
	  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_igst_amount">&nbsp;</td>
	  </tr>
	  </tbody>
	  </table>
	  <table style="width:50%;float:left;border-bottom:1px solid black;border-top:1px solid black;border-right:1px solid black;border-left:1px solid black;" class="checklist">
	  <tbody>
	  <tr>
	  <td style="padding: 5px;font-family: Times New Roman, Times, serif;" class="col_checkbox"><input type="checkbox"></td>
	  <td style="padding: 5px;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_checklist">At Top Side ... D-Dent, S-Scratch, P-Peal, C-Cut</td>
	  <td style="padding: 5px;font-family: Times New Roman, Times, serif;border-bottom:1px solid black;font-size: 12px;" class="col_notes"></td>
	  </tr><tr>
	  <td style="padding: 5px;font-family: Times New Roman, Times, serif;" class="col_checkbox"><input type="checkbox"></td>
	  <td style="padding: 5px;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_checklist">CD, USB, SD</td>
	  <td style="padding: 5px;font-family: Times New Roman, Times, serif;border-bottom:1px solid black;font-size: 12px;" class="col_notes"></td>
	  </tr><tr>
	  <td style="padding: 5px;font-family: Times New Roman, Times, serif;" class="col_checkbox"><input type="checkbox"></td>
	  <td style="padding: 5px;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_checklist">Idol, Picture, hangings</td>
	  <td style="padding: 5px;font-family: Times New Roman, Times, serif;border-bottom:1px solid black;font-size: 12px;" class="col_notes"></td>
	  </tr><tr>
	  <td style="padding: 5px;font-family: Times New Roman, Times, serif;" class="col_checkbox"><input type="checkbox"></td>
	  <td style="padding: 5px;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_checklist">Jack and Handle</td>
	  <td style="padding: 5px;font-family: Times New Roman, Times, serif;border-bottom:1px solid black;font-size: 12px;" class="col_notes"></td>
	  </tr><tr>
	  <td style="padding: 5px;font-family: Times New Roman, Times, serif;" class="col_checkbox"><input type="checkbox"></td>
	  <td style="padding: 5px;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_checklist">Lighter, Charger</td>
	  <td style="padding: 5px;font-family: Times New Roman, Times, serif;border-bottom:1px solid black;font-size: 12px;" class="col_notes"></td>
	  </tr><tr>
	  <td style="padding: 5px;font-family: Times New Roman, Times, serif;" class="col_checkbox"><input type="checkbox"></td>
	  <td style="padding: 5px;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_checklist">Mats, Seat covers</td>
	  <td style="padding: 5px;font-family: Times New Roman, Times, serif;border-bottom:1px solid black;font-size: 12px;" class="col_notes"></td>
	  </tr><tr>
	  <td style="padding: 5px;font-family: Times New Roman, Times, serif;" class="col_checkbox"><input type="checkbox"></td>
	  <td style="padding: 5px;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_checklist">Perfumes/Odor bottles</td>
	  <td style="padding: 5px;font-family: Times New Roman, Times, serif;border-bottom:1px solid black;font-size: 12px;" class="col_notes"></td>
	  </tr><tr>
	  <td style="padding: 5px;font-family: Times New Roman, Times, serif;" class="col_checkbox"><input type="checkbox"></td>
	  <td style="padding: 5px;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_checklist">Remote Key</td>
	  <td style="padding: 5px;font-family: Times New Roman, Times, serif;border-bottom:1px solid black;font-size: 12px;" class="col_notes"></td>
	  </tr><tr>
	  <td style="padding: 5px;font-family: Times New Roman, Times, serif;" class="col_checkbox"><input type="checkbox"></td>
	  <td style="padding: 5px;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_checklist">Spare Wheel</td>
	  <td style="padding: 5px;font-family: Times New Roman, Times, serif;border-bottom:1px solid black;font-size: 12px;" class="col_notes"></td>
	  </tr><tr>
	  <td style="padding: 5px;font-family: Times New Roman, Times, serif;" class="col_checkbox"><input type="checkbox"></td>
	  <td style="padding: 5px;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_checklist">Steering Cover</td>
	  <td style="padding: 5px;font-family: Times New Roman, Times, serif;border-bottom:1px solid black;font-size: 12px;" class="col_notes"></td>
	  </tr><tr>
	  <td style="padding: 5px;font-family: Times New Roman, Times, serif;" class="col_checkbox"><input type="checkbox"></td>
	  <td style="padding: 5px;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_checklist">Tool Kit</td>
	  <td style="padding: 5px;font-family: Times New Roman, Times, serif;border-bottom:1px solid black;font-size: 12px;" class="col_notes"></td>
	  </tr><tr>
	  <td style="padding: 5px;font-family: Times New Roman, Times, serif;" class="col_checkbox"><input type="checkbox"></td>
	  <td style="padding: 5px;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_checklist">Warranty Booklet</td>
	  <td style="padding: 5px;font-family: Times New Roman, Times, serif;border-bottom:1px solid black;font-size: 12px;" class="col_notes"></td>
	  </tr>
	  <tr>
	  <td style="padding: 5px;font-family: Times New Roman, Times, serif;padding-bottom: 13px;" class="col_checkbox"><input type="checkbox"></td>
	  <td style="padding: 5px;font-family: Times New Roman, Times, serif;font-size: 12px;" class="col_checklist">Wheel Cap</td>
	  <td style="padding: 5px;font-family: Times New Roman, Times, serif;border-bottom:1px solid black;font-size: 12px;" class="col_notes"></td>
	  </tr></tbody>
	  </table>
	  </td>
	  </tr></tbody></table>

	  </div>
		<div style="position: relative;font-family: Times New Roman, Times, serif; color: rgb(0, 0, 0);  width: 100%;" class="footer_container content_container">
		Disclaimer:  <br>                                                                                                                  <i style="float:right;padding-right:99px;">Signature</i>
		Goods and servic appear hear are approximate <br>
		This is used for internal usage during the work.<br>
		The list of items and work may vary during work course.<br>
		</div>
		</div>
	  </div>


			   </body></html>';

	}

	else if($template_type->name == "wms_receipt"){
	  	$data ='<style>
			.item_table {
			  border-collapse: collapse;
			  border-width: 0px;
			  border: 1px solid #000;
			}
			 @media print {
			body {
			  -webkit-print-color-adjust: exact;
			}
			}
			</style>

			<div data-type="portrait" style="width: 273mm; height: 200mm; padding: 57mm 16mm 27mm 16mm; background: rgb(255, 255, 255) none repeat scroll 0% 0%;" class="workspace">
			  <div style="position: relative; min-height: 140px; font-family: Arial, sans-serif; color: rgb(0, 0, 0); height: 480px;" class="header_container content_container">
			  <div style="width: auto; float: left; position: absolute; top: 0px; left: 0px; font-family: Arial, sans-serif; color: rgb(0, 0, 0);" >
				<div style="border-style: solid; border-color: rgb(0, 0, 0); border-width: 1px; width: 910px; height: 80px; font-family: Arial, sans-serif; color: rgb(0, 0, 0);" ></div>
			  </div>
			  <div style="float: left; font-family: Arial, sans-serif; z-index: 1; position: absolute; top: 243px; left: 610px; width: 100%; text-align: center; color: rgb(0, 0, 0);" >
			  </div>
			  <div style="width: auto; float: left; position: absolute; top: 79px; left: 0px;" >
				<div style="border-style: solid; border-color: rgb(0, 0, 0); border-width: 1px; width: 912px; height: 40px;" class="rectangle_result"></div>
			  </div>
			  <div style="float: left; font-family: Arial, sans-serif; z-index: 1; position: absolute; top: 110px; width: 100%; text-align: center; left: 0px;" >
			  </div>
			  <div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; position: absolute; top: 0px; left: 0px;" ></div>
			  <div style="width: auto; float: left; position: absolute; top: 118px; left: 0px;" >
				<div style="border-style: solid; border-color: rgb(0, 0, 0); border-width: 1px; width: 912px; height: 50px;" class="rectangle_result"></div>
			  </div>
			  <div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 435px; left: 301px;" >
				<div>
			  
				
				</div>
			  </div>
			  <div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; position: absolute; top: 8px; left: 328px;"></div>

			  <div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; position: absolute; top: 36px; left: 243px;"></div>

			  <div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 90px; left: 21px;"></div>

			  <div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 130px; left: 26px;">
				<div>
				  <div style="float:left;">
					<div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: Arial, sans-serif;"><b>RECEIPT NO:</b> <span data-value="receipt_no" style="font-size: 16px;">WR-18</span> </div>
				  </div>
				</div>
			  </div>

			  <div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 131px; left: 710px;">
				<div>
				  <div style="float:left;">
					<div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: Arial, sans-serif;"><b>DATE:</b> <span data-value="date" style="font-size: 16px;">04/01/2019</span></div>
				  </div>
				 </div>
			   </div>

			  <div style="width: auto; float: left; position: absolute; top: 167px; left: 0px;">
				<div style="border-style: solid; border-color: rgb(0, 0, 0); border-width: 1px; width: 910px; height: 200px;"></div>
			   </div>

			  <div style="min-width: 10px; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 181px; left: 36px;">
				<div>
				<div style="float:left;">
				<div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: Arial, sans-serif;"><b>Amount in Words:</b> <span data-value="wording_amount" style="font-size: 16px;
				padding-left: 100px;">Three Thousand Only</span></div>
				</div>
			</div></div>

			  <div style="min-width: 10px; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 212px; left: 38px;">
				<div>
				  <div style="float:left;">
					<div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: Arial, sans-serif;"><b>Received From:</b> <span data-value="received_from" style="font-size: 16px;
				padding-left: 115px;">customer name</span></div>
				</div>
			</div></div>

			  <div style="min-width: 10px; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 240px; left: 38px;">
				<div>
				  <div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: Arial, sans-serif;"><b>By the mode of:</b> <span data-value="mode" style="font-size: 16px;
				padding-left: 115px;">cash</span></div>
			  </div>
			</div></div>

			  <div style="min-width: 10px; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 268px; left: 35px;">
				<div>
				  <div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: Arial, sans-serif;" class="label_result"><b>On the Date Of:</b> <span data-value="on_date" style="font-size: 16px;
				padding-left: 117px;">04/01/2019</span></div>
			 </div>
			</div></div>
			<div style="min-width: 10px; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 296px; left: 35px;">
				<div>
				  <div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: Arial, sans-serif;" class="label_result"><b>Reference Job card No:</b> <span data-value="jc_no" style="font-size: 16px;float:right;padding-left:66px">Jc-1</span></div>
			 </div>
			</div></div>
			  <div style="width: auto; float: left; position: absolute; top: 196px; left: 711px;">
				<div style="border-style: solid; border-color: rgb(0, 0, 0); border-width: 1px; width: 100px; height: 40px;" class="rectangle_result"></div></div>

			  <div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 200px; left: 714px;">
				<div>
				  <div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: Arial, sans-serif; font-weight: bold;">Rs:</div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: Arial, sans-serif; font-weight: bold;" data-value="amount">3000</div></div>

			  <div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; position: absolute; top: 100px; "><div class="text_result" style="color: rgb(0, 0, 0); font-size: 14px; font-family: Arial, sans-serif;">Received by (Signature here)</div></div>
			</div>

			  <div style="position: relative; font-family: Arial, sans-serif; color: rgb(0, 0, 0);float: left; width:914px;" class="body_container content_container">

			  </div>
			  <div style="position: relative; float: left; width:100%; font-family: Arial, sans-serif; color: rgb(0, 0, 0);" class="total_container content_container"> </div>
			  <div style="position: relative; height: 112px; font-family: Arial, sans-serif; color: rgb(0, 0, 0); float: left; width: 100%;" class="footer_container content_container">
			  
			  <div style="float: left; font-family: Arial, sans-serif; z-index: 1; position: absolute; top: 10px; width: 100%; text-align: right; left: 0px;">

			  </div>
			  </div>
		</div>';
	}	

	else if($template_type->name == "B2C_OneLineTax_JobInvoice") {
	  	$data = '<style>
			  .item_table {
				border-collapse: collapse;
			  }
			   @media print {
			  body {
				-webkit-print-color-adjust: exact;
			  }
			  }
			  </style>
			  <div data-type="portrait" style="width: 210mm; height: 200mm; padding: 27mm 16mm; background: rgb(255, 255, 255) none repeat scroll 0% 0%;" class="workspace">
				<div style="position: relative; min-height: 200px; height: 332px;" class="header_container content_container">

				</div>
				<div style="position: relative;" class="body_container content_container">
				  <table border="1" class="no_tax_item_table" width="100%" border="0">
					<thead>
					  <tr style="font-family: &quot;Courier New&quot;, monospace; font-size: 14px; color: rgb(0, 0, 0); background-color: rgb(242, 242, 242);">
						<th style="background: rgb(51, 51, 51) none repeat scroll 0% 0%;" class="col_id">#</th>
						<th style="background: rgb(51, 51, 51) none repeat scroll 0% 0%;" class="col_desc">Item Description</th>
						<th style="background: rgb(51, 51, 51) none repeat scroll 0% 0%;" class="col_quantity">Quantity</th>
						<th style="background: rgb(51, 51, 51) none repeat scroll 0% 0%;" class="col_rate">UnitRate RS</th>
					<th style="background: rgb(51, 51, 51) none repeat scroll 0% 0%;" class="col_discount">Discount RS</th>
						<th style="background: rgb(51, 51, 51) none repeat scroll 0% 0%;" class="col_amount">Total Rs</th>
					  </tr>
					</thead>
					<tbody>
					  <tr>
						<td style="padding: 5px;" class="col_id">&nbsp;</td>
						<td style="padding: 5px;" class="col_desc">&nbsp;</td>
					 <td style="padding: 5px;text-align: right;" class="col_quantity">&nbsp;</td>
					 <td style="padding: 5px;text-align: right;" class="col_rate">&nbsp;</td>
						<td style="padding: 5px;text-align: right;" class="col_discount">&nbsp;</td>
						<td style="padding: 5px;text-align: right;" class="col_amount">&nbsp;</td>
					  </tr>
					  <tr>
						<td style="padding: 5px;" class="col_id">&nbsp;</td>
						<td style="padding: 5px;" class="col_desc">&nbsp;</td>
					 <td style="padding: 5px;text-align: right;" class="col_quantity">&nbsp;</td>
					 <td style="padding: 5px;text-align: right;" class="col_rate">&nbsp;</td>
						<td style="padding: 5px;text-align: right;" class="col_discount">&nbsp;</td>
						<td style="padding: 5px;text-align: right;" class="col_amount">&nbsp;</td>
					  </tr>
					</tbody>
				  </table>
				</div>
				<br>
				<div class="total_container content_container">
				  <table class="total_table" width="100%" align="right">
					<tbody>
					  <tr>
						<td width="25%"></td>
						<td width="25%"></td>
						<td>Sub-Total</td>
						<td class="invoice_sub_total" style="text-align: right;">0.00</td>
					  </tr>
					  <tr>
						<td width="25%"></td>
						<td width="25%"></td>
						<td class="tax_name">Tax Amount</td>
						<td class="tax_value" style="text-align: right;">0.00</td>
					  </tr>
					  <tr>
						<td width="25%"></td>
						<td width="25%"></td>
						<td>Total</td>
						<td class="invoice_total_amount" style="text-align: right;">0.00</td>
					  </tr>
					</tbody>
				  </table>
				</div>
				<div style="position: relative; float: left; width: 100%; height: 200px;" class="footer_container content_container">
				  
				</div>
			</div>';

	}
	else if($template_type->name == "B2B_HSN_Invoice") {

		$data = '<style>
				.workspace
				{
					  display: block;
					  page-break-inside: avoid;
					  page-break-before: avoid;
					  page-break-after: avoid;
					  -webkit-region-break-inside: avoid; 
				}
			  </style>
			  <div data-type="portrait" style="background: rgb(255, 255, 255);" class="workspace">
			  <div class="invoice_print" style="border:1px solid black;">
				<div style="position: relative; min-height: 300px; height: 210px; font-family: Arial, sans-serif; font-size: 10px; color: rgb(0, 0, 0);" class="header_container content_container">

				<div style="float: left; position: absolute; top: 143px;" class="draggable ui-draggable ui-draggable-handle">
			   <hr style="border:1px solid black;width:700px">
				<div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div>
				</div>
			  <div style="position:relative;" class="body_container">
			  <table style="width:100%;border: 1px solid black;border-collapse: collapse;" class="invoice_item_table">
			  <thead>
				  <tr>
					<th style="padding: 5px;border: 1px solid black;border-collapse: collapse;text-align: center;font-family: Times New Roman, Times, serif;" class="col_id">Sl.No</th>
					<th style="padding: 5px;border: 1px solid black;border-collapse: collapse;text-align: center;font-family: Times New Roman, Times, serif;" class="col_desc">PARTICULARS</th>
					<th style="padding: 5px;border: 1px solid black;border-collapse: collapse;text-align: center;font-family: Times New Roman, Times, serif;" class="col_hsn">HSN/SAC</th>
					<th style="padding: 5px;border: 1px solid black;border-collapse: collapse;text-align: center;font-family: Times New Roman, Times, serif;" class="col_quantity">QTY</th>
					<th style="padding: 5px;border: 1px solid black;border-collapse: collapse;text-align: center;font-family: Times New Roman, Times, serif;" class="col_rate">RATE</th>
					<th style="padding: 5px;border: 1px solid black;border-collapse: collapse;text-align: center;font-family: Times New Roman, Times, serif;"  class="col_discount">DISCOUNT</th>
					<th style="padding: 5px;border: 1px solid black;border-collapse: collapse;text-align: center;font-family: Times New Roman, Times, serif;"  class="col_t_amount">AMOUNT</th>
				  </tr>
			  </thead>
			  <tbody>
				   <tr>
				  <td style="padding: 5px;text-align: center;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_id">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_desc">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_hsn">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_quantity">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_rate">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_discount">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_t_amount">&nbsp;</td>
				 </tr>
				   <tr>
				  <td style="padding: 5px;text-align: center;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_id">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_desc">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_hsn">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_quantity">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_rate">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_discount">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_t_amount">&nbsp;</td>
				 </tr>
					  <tr>
				  <td style="padding: 5px;text-align: center;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_id">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_desc">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_hsn">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_quantity">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_rate">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_discount">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_t_amount">&nbsp;</td>
				 </tr>
					  <tr>
				  <td style="padding: 5px;text-align: center;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_id">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_desc">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_hsn">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_quantity">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_rate">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_discount">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_t_amount">&nbsp;</td>
				 </tr>
					  <tr>
				  <td style="padding: 5px;text-align: center;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_id">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_desc">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_hsn">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_quantity">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_rate">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_discount">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_t_amount">&nbsp;</td>
				 </tr>
					  <tr>
				  <td style="padding: 5px;text-align: center;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_desc">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_hsn">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_quantity">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_rate">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_discount">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_t_amount">&nbsp;</td>
				 </tr>
					  <tr>
				  <td style="padding: 5px;text-align: center;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_desc">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_hsn">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_quantity">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_rate">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_discount">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_t_amount">&nbsp;</td>
				 </tr>
					  <tr>
				  <td style="padding: 5px;text-align: center;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_desc">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_hsn">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_quantity">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_rate">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_discount">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_t_amount">&nbsp;</td>
				 </tr>
					  <tr>
				  <td style="padding: 5px;text-align: center;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_desc">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_hsn">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_quantity">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_rate">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_discount">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_t_amount" >&nbsp;</td>
				 </tr>
					  <tr>
				  <td style="padding: 5px;text-align: center;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_desc">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_hsn">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_quantity">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_rate">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_discount">&nbsp;</td>
				  <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_t_amount">&nbsp;</td>
				 </tr>
				 <tfoot>
				 <tr>
				 <td  class="col_id" colspan = "3" style="border-top: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;">E & OE</td>
					<td style="border-top: 1px solid black;font-family: Times New Roman, Times, serif;">&nbsp;</td>
					<td style="border-top: 1px solid black;font-family: Times New Roman, Times, serif;">&nbsp;</td>
					<td style="border-top: 1px solid black;border-right: 1px solid black;border-left: 1px solid black;font-family: Times New Roman, Times, serif;">Total:<span class="value_result" data-value="total_discount" style="font-size: 14px;font-family: Times New Roman, Times, serif;">000.00</span></td>
					<td style="border-top: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;">Total:<span class="value_result" data-value="total_amount" style="font-size: 14px;font-family: Times New Roman, Times, serif;">000.00</span></td>
				 </tr>
				 </tfoot>
			  </tbody>  
			  </table>
			  <table style="width:100%;">
			  <td>
			  <table style = "width:80%;border-collapse: collapse;border: 1px solid black;float:left;" class= "hsnbasedTable">
			  <thead>
			  <tr>
			  <td style = "padding: 5px;border-collapse: collapse;border: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_sac">HSN/SAC</td>
			  <td style = "padding: 5px;border-collapse: collapse;border: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_tax_value">Taxable</td>
			  <td style = "padding: 5px;border-collapse: collapse;border: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_igst">IGST%</td>
			  <td style = "padding: 5px;border-collapse: collapse;border: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_igst_amount">IGST Amt</td>
			  <td style = "padding: 5px;border-collapse: collapse;border: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_cgst">CGST%</td>
			  <td style = "padding: 5px;border-collapse: collapse;border: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_cgst_amount">CGST Amt</td>
			  <td style = "padding: 5px;border-collapse: collapse;border: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_sgst">SGST%</td>
			  <td style = "padding: 5px;border-collapse: collapse;border: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_sgst_amount">SGST Amt</td>
			  </tr>
			  </thead>
			  <tbody>
			  <tr>
			  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_sac">&nbsp;</td>
			  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_tax_value">&nbsp;</td>
			  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_igst">&nbsp;</td>
			  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_igst_amount">&nbsp;</td>
			  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_cgst">&nbsp;</td>
			  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_cgst_amount">&nbsp;</td>
			  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_sgst">&nbsp;</td>
			  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_sgst_amount">&nbsp;</td>
			  </tr>
			  <tr>
			  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_sac">&nbsp;</td>
			  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_tax_value">&nbsp;</td>
			  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_igst">&nbsp;</td>
			  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_igst_amount">&nbsp;</td>
			  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_cgst">&nbsp;</td>
			  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_cgst_amount">&nbsp;</td>
			  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_sgst">&nbsp;</td>
			  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_sgst_amount">&nbsp;</td>
			  </tr>
			  <tr>
			  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_sac">&nbsp;</td>
			  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_tax_value">&nbsp;</td>
			  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_igst">&nbsp;</td>
			  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_igst_amount">&nbsp;</td>
			  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_cgst">&nbsp;</td>
			  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_cgst_amount">&nbsp;</td>
			  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_sgst">&nbsp;</td>
			  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_sgst_amount">&nbsp;</td>
			  </tr>
			  <tr>
			  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_sac">&nbsp;</td>
			  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_tax_value">&nbsp;</td>
			  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_igst">&nbsp;</td>
			  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_igst_amount">&nbsp;</td>
			  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_cgst">&nbsp;</td>
			  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_cgst_amount">&nbsp;</td>
			  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_sgst">&nbsp;</td>
			  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_sgst_amount">&nbsp;</td>
			  </tr>
			  <tr>
			  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_sac">&nbsp;</td>
			  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_tax_value">&nbsp;</td>
			  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_igst">&nbsp;</td>
			  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_igst_amount">&nbsp;</td>
			  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_cgst">&nbsp;</td>
			  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_cgst_amount">&nbsp;</td>
			  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_sgst">&nbsp;</td>
			  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_sgst_amount">&nbsp;</td>
			  </tr>
			  <tr>
			  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_sac">&nbsp;</td>
			  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_tax_value">&nbsp;</td>
			  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_igst">&nbsp;</td>
			  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_igst_amount">&nbsp;</td>
			  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_cgst">&nbsp;</td>
			  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_cgst_amount">&nbsp;</td>
			  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_sgst">&nbsp;</td>
			  <td style = "padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_sgst_amount">&nbsp;</td>
			  </tr>
			  </tbody>
			  <tfoot>
			  <tr>
			  <td colspan = "8" style="border-top: 1px solid black;padding-bottom: 25px;font-family: Times New Roman, Times, serif;">Rupees: <i class="value_result" data-value="rupees" style="font-size: 14px;font-family: Times New Roman, Times, serif;">Four Thousand Only</i></td>
			  </tr>
			  </tfoot>
			  </table>
			  <table style = "width:20%;float:left;border-bottom:1px solid black;border-top:1px solid black;border-right:1px solid black;border-left:1px solid black;" class="ft">
			  <tr>
			  <td style="padding-top:5px;padding-bottom:5px;">CGST</td>
			  <td style="text-align: right;font-family: Times New Roman, Times, serif;"><span class="value_result" data-value="total_cgst" style="font-family: Times New Roman, Times, serif; font-size: 14px; color: rgb(0, 0, 0);float:right;">000.00</span></td>
			  </tr>
			  <tr>
			  <td style="padding-top:5px;padding-bottom:5px;">SGST</td>
			  <td style="text-align: right;"><span class="value_result" data-value="total_sgst" style="font-family: Times New Roman, Times, serif; font-size: 14px; color: rgb(0, 0, 0);float:right;">00.00</span></td>
			  </tr>
			  <tr>
			  <td style="padding-top:5px;padding-bottom:5px;">IGST</td>
			  <td style="text-align: right;"><span class="value_result" data-value="total_igst" style="font-family: Times New Roman, Times, serif; font-size: 14px; color: rgb(0, 0, 0);float:right;">000.00</span></td>
			  </tr>
			  <tr>
			  <td style="padding-top:5px;padding-bottom:5px;">Round off</td>
			  <td style="text-align: right;"><span class="value_result" data-value="round_off" style="font-family: Times New Roman, Times, serif; font-size: 14px; color: rgb(0, 0, 0);float:right;">000.00</span></td>
			  </tr>
			  <tr>
			  <td style="padding-top:5px;padding-bottom:5px;">&nbsp;</td>
			  <td style="text-align: right;">&nbsp;</td>
			  </tr>
			  <tr>
			  <td style="padding-top:5px;padding-bottom:5px;">&nbsp;</td>
			  <td style="text-align: right;">&nbsp;</td>
			  </tr>
			  <tr>
			  <td style="padding-top:20px;padding-bottom:38px;font-family: Times New Roman, Times, serif;">TOTAL:</td>
			  <td style="text-align: right;padding-top:20px;padding-bottom:38px;font-family: Times New Roman, Times, serif;"><span class="value_result" data-value="total_amountwithtax" style="font-family: Times New Roman, Times, serif; font-size: 14px; color: rgb(0, 0, 0);float:right;">000.00</span></td>
			  </tr>
			  </table>
			  </td>
			  </table>
			  </div>
				<div style="position: relative;font-family: Times New Roman, Times, serif; color: rgb(0, 0, 0);  width: 100%;" class="footer_container content_container">
				Discription:Goods once sold can not be taken back!
				</div>
				</div>
		</div>';

	}
	else if($template_type->name == "B2C_NoTax_Sales"){
	  
	   $data = '<style>
			 	.item_table {
				border-collapse: collapse;
			  	}
			   @media print {
			  	body {
				-webkit-print-color-adjust: exact;
			  	}
			 	 }
			  	</style>
			  <div data-type="portrait" style="width: 210mm; height: 300mm; background: rgb(255, 255, 255) none repeat scroll 0% 0%;border:1px solid" class="workspace">
				<div style="position: relative; min-height: 200px; height: 332px;" class="header_container content_container">

				</div>
				<div style="position: relative;" class="body_container content_container">
				  <table class="no_tax_sales_table" width="100%" style="border-top:1px solid;border-bottom:1px solid;">
					<thead>
					  <tr style="font-family: &quot;Courier New&quot;, monospace; font-size: 14px; color: rgb(0, 0, 0); background-color: rgb(242, 242, 242);">
						<th style="" class="col_id">#</th>
						<th style="" class="col_desc">Item Description</th>
						<th style="text-align: right;" class="col_quantity">Quantity</th>
						<th style="text-align: right;" class="col_rate">UnitRate RS</th>
						<th style="text-align: right;" class="col_discount">Discount RS</th>
						<th style="text-align: right;" class="col_amount">Total Rs</th>
					  </tr>
					</thead>
					<tbody>
					  <tr>
						<td style="padding: 5px;" class="col_id">&nbsp;</td>
						<td style="padding: 5px;" class="col_desc">&nbsp;</td>
					 <td style="padding: 5px;text-align: right;" class="col_quantity">&nbsp;</td>
					 <td style="padding: 5px;text-align: right;" class="col_rate">&nbsp;</td>
						<td style="padding: 5px;text-align: right;" class="col_discount">&nbsp;</td>
						<td style="padding: 5px;text-align: right;" class="col_amount">&nbsp;</td>
					  </tr>
					  <tr>
						<td style="padding: 5px;" class="col_id">&nbsp;</td>
						<td style="padding: 5px;" class="col_desc">&nbsp;</td>
					 <td style="padding: 5px;text-align: right;" class="col_quantity">&nbsp;</td>
					 <td style="padding: 5px;text-align: right;" class="col_rate">&nbsp;</td>
						<td style="padding: 5px;text-align: right;" class="col_discount">&nbsp;</td>
						<td style="padding: 5px;text-align: right;" class="col_amount">&nbsp;</td>
					  </tr>
					</tbody>
				  </table>
				</div>
				<br>
				<div class="total_container content_container">
				  <table class="total_table" width="100%" align="right" style="border-bottom:1px solid;">
					<tbody>
					  <tr>
						<td width="25%"></td>
						<td width="25%"></td>
						<td>Total</td>
						<td class="sales_total_amount" style="text-align: right;">0.00</td>
					  </tr>
					</tbody>
				  </table>
				</div>
				<div style="position: relative; float: left; width: 100%; height: 200px;" class="footer_container content_container">
				  
				</div>
			</div>';
	}

	else if($template_type->name == "B2B_HSNbased_Invoice"){
	  	
	  	$data = '<style>
			.workspace
			{
			    display: block;
			    page-break-inside: avoid;
			  page-break-before: avoid;
			  page-break-after: avoid;
			    -webkit-region-break-inside: avoid; 
			}
			</style>
			<div data-type="portrait" style="background: rgb(255, 255, 255);" class="workspace">
			<div class="invoice_print" style="border:1px solid black;">
			  <div style="position: relative; min-height: 300px; height: 299px; font-family: Arial, sans-serif; font-size: 10px; color: rgb(0, 0, 0);" class="header_container content_container">

			  <div style="float: left; position: absolute; top: 143px; left: 0.0000292188px;" class="draggable ui-draggable ui-draggable-handle">
			 <hr style="border:1px solid black;width:700px">
			  <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div>
			  <div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; position: absolute; top: 114.467px; left: 253.467px;" class="draggable ui-draggable ui-draggable-handle"><div class="text_result" style="color: rgb(0, 0, 0); font-size: 20px; font-family: &quot;Times New Roman&quot;, Times, serif; font-weight: bold;">Tax Invoice</div><div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 166px; left: 8.00003px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Vehicle:</div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="vehicle_number">Vehicle Number</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 191px; left: 10px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Modal: </div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="make_model_variant">Make-Model-variant</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 212px; left: 10px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Customer:</div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="customer_vendor">Customer / Vendor</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 233px; left: 11px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Address:</div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="customer_address">Customer Address</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 254px; left: 11px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">GST No:</div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="customer_gst">Customer GST</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 165px; left: 456px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Voucher#: </div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="purchase">Purchase</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 184px; left: 474px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Dated:</div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="date">Date</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 0.233321px; left: 193.233px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(0, 0, 0); font-size: 24px; font-family: &quot;Times New Roman&quot;, Times, serif; font-weight: bold;" class="value_result" data-value="company_name">My company Name</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 40.4667px; left: 185.467px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_address">My company Address</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 70.4667px; left: 172.467px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Phone: </div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_phone">My company Phone</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 70.35px; left: 338.35px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">GSTN : </div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_gst">Company GST</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div></div>
			<div style="position:relative;" class="body_container">
			<table style="width:100%;border: 1px solid black;border-collapse: collapse;" class="invoice_item_table">
			<thead>
			    <tr>
			      <th style="padding: 5px;border: 1px solid black;border-collapse: collapse;text-align: center;font-family: Times New Roman, Times, serif;" class="col_id">Sl.No</th>
			      <th style="padding: 5px;border: 1px solid black;border-collapse: collapse;text-align: center;font-family: Times New Roman, Times, serif;" class="col_desc">PARTICULARS</th>
			      <th style="padding: 5px;border: 1px solid black;border-collapse: collapse;text-align: center;font-family: Times New Roman, Times, serif;" class="col_hsn">HSN/SAC</th>
			      <th style="padding: 5px;border: 1px solid black;border-collapse: collapse;text-align: center;font-family: Times New Roman, Times, serif;" class="col_quantity">QTY</th>
			      <th style="padding: 5px;border: 1px solid black;border-collapse: collapse;text-align: center;font-family: Times New Roman, Times, serif;" class="col_rate">RATE</th>
			      <th style="padding: 5px;border: 1px solid black;border-collapse: collapse;text-align: center;font-family: Times New Roman, Times, serif;" class="col_discount">DISCOUNT</th>
			      <th style="padding: 5px;border: 1px solid black;border-collapse: collapse;text-align: center;font-family: Times New Roman, Times, serif;" class="col_t_amount">AMOUNT</th>
			    </tr>
			</thead>
			<tbody>
			     <tr>
			    <td style="padding: 5px;text-align: center;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_id">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_desc">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_hsn">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_quantity">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_rate">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_discount">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_t_amount">&nbsp;</td>
			   </tr>
			     <tr>
			    <td style="padding: 5px;text-align: center;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_id">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_desc">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_hsn">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_quantity">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_rate">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_discount">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_t_amount">&nbsp;</td>
			   </tr>
			        <tr>
			    <td style="padding: 5px;text-align: center;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_id">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_desc">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_hsn">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_quantity">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_rate">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_discount">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_t_amount">&nbsp;</td>
			   </tr>
			        <tr>
			    <td style="padding: 5px;text-align: center;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_id">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_desc">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_hsn">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_quantity">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_rate">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_discount">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_t_amount">&nbsp;</td>
			   </tr>
			        <tr>
			    <td style="padding: 5px;text-align: center;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_id">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_desc">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_hsn">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_quantity">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_rate">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_discount">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_t_amount">&nbsp;</td>
			   </tr>
			        <tr>
			    <td style="padding: 5px;text-align: center;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_desc">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_hsn">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_quantity">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_rate">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_discount">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_t_amount">&nbsp;</td>
			   </tr>
			        <tr>
			    <td style="padding: 5px;text-align: center;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_desc">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_hsn">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_quantity">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_rate">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_discount">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_t_amount">&nbsp;</td>
			   </tr>
			        <tr>
			    <td style="padding: 5px;text-align: center;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_desc">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_hsn">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_quantity">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_rate">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_discount">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_t_amount">&nbsp;</td>
			   </tr>
			        <tr>
			    <td style="padding: 5px;text-align: center;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_desc">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_hsn">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_quantity">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_rate">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_discount">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_t_amount">&nbsp;</td>
			   </tr>
			        <tr>
			    <td style="padding: 5px;text-align: center;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_desc">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_hsn">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_quantity">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_rate">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_discount">&nbsp;</td>
			    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_t_amount">&nbsp;</td>
			   </tr>
			   </tbody><tfoot>
			   <tr>
			   <td class="col_id" colspan="3" style="border-top: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;">E &amp; OE</td>
			      <td style="border-top: 1px solid black;font-family: Times New Roman, Times, serif;">&nbsp;</td>
			      <td style="border-top: 1px solid black;font-family: Times New Roman, Times, serif;">&nbsp;</td>
			      <td style="border-top: 1px solid black;border-right: 1px solid black;border-left: 1px solid black;font-family: Times New Roman, Times, serif;">Total:<span class="value_result" data-value="total_discount" style="font-size: 14px;font-family: Times New Roman, Times, serif;">000.00</span></td>
			      <td style="border-top: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;">Total:<span class="value_result" data-value="total_amount" style="font-size: 14px;font-family: Times New Roman, Times, serif;">000.00</span></td>
			   </tr>
			   </tfoot>
			  
			</table>
			<table style="width:100%;">
			<tbody><tr><td>
			<table style="width:80%;border-collapse: collapse;border: 1px solid black;float:left;" class="hsnbasedTable">
			<thead>
			<tr>
			<td style="padding: 5px;border-collapse: collapse;border: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_sac">HSN/SAC</td>
			<td style="padding: 5px;border-collapse: collapse;border: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_tax_value">Taxable</td>
			<td style="padding: 5px;border-collapse: collapse;border: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_igst">IGST%</td>
			<td style="padding: 5px;border-collapse: collapse;border: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_igst_amount">IGST Amt</td>
			<td style="padding: 5px;border-collapse: collapse;border: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_cgst">CGST%</td>
			<td style="padding: 5px;border-collapse: collapse;border: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_cgst_amount">CGST Amt</td>
			<td style="padding: 5px;border-collapse: collapse;border: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_sgst">SGST%</td>
			<td style="padding: 5px;border-collapse: collapse;border: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_sgst_amount">SGST Amt</td>
			</tr>
			</thead>
			<tbody>
			<tr>
			<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_sac">&nbsp;</td>
			<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_tax_value">&nbsp;</td>
			<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_igst">&nbsp;</td>
			<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_igst_amount">&nbsp;</td>
			<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_cgst">&nbsp;</td>
			<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_cgst_amount">&nbsp;</td>
			<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_sgst">&nbsp;</td>
			<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_sgst_amount">&nbsp;</td>
			</tr>
			<tr>
			<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_sac">&nbsp;</td>
			<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_tax_value">&nbsp;</td>
			<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_igst">&nbsp;</td>
			<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_igst_amount">&nbsp;</td>
			<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_cgst">&nbsp;</td>
			<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_cgst_amount">&nbsp;</td>
			<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_sgst">&nbsp;</td>
			<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_sgst_amount">&nbsp;</td>
			</tr>
			<tr>
			<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_sac">&nbsp;</td>
			<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_tax_value">&nbsp;</td>
			<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_igst">&nbsp;</td>
			<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_igst_amount">&nbsp;</td>
			<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_cgst">&nbsp;</td>
			<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_cgst_amount">&nbsp;</td>
			<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_sgst">&nbsp;</td>
			<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_sgst_amount">&nbsp;</td>
			</tr>
			<tr>
			<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_sac">&nbsp;</td>
			<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_tax_value">&nbsp;</td>
			<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_igst">&nbsp;</td>
			<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_igst_amount">&nbsp;</td>
			<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_cgst">&nbsp;</td>
			<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_cgst_amount">&nbsp;</td>
			<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_sgst">&nbsp;</td>
			<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_sgst_amount">&nbsp;</td>
			</tr>
			<tr>
			<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_sac">&nbsp;</td>
			<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_tax_value">&nbsp;</td>
			<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_igst">&nbsp;</td>
			<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_igst_amount">&nbsp;</td>
			<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_cgst">&nbsp;</td>
			<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_cgst_amount">&nbsp;</td>
			<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_sgst">&nbsp;</td>
			<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_sgst_amount">&nbsp;</td>
			</tr>
			<tr>
			<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_sac">&nbsp;</td>
			<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_tax_value">&nbsp;</td>
			<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_igst">&nbsp;</td>
			<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_igst_amount">&nbsp;</td>
			<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_cgst">&nbsp;</td>
			<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_cgst_amount">&nbsp;</td>
			<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_sgst">&nbsp;</td>
			<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_sgst_amount">&nbsp;</td>
			</tr>
			</tbody>
			<tfoot>
			<tr>
			<td colspan="8" style="border-top: 1px solid black;padding-bottom: 25px;font-family: Times New Roman, Times, serif;">Rupees: <i class="value_result" data-value="rupees" style="font-size: 14px;font-family: Times New Roman, Times, serif;">Four Thousand Only</i></td>
			</tr>
			</tfoot>
			</table>
			<table style="width:20%;float:left;border-bottom:1px solid black;border-top:1px solid black;border-right:1px solid black;border-left:1px solid black;" class="ft">
			<tbody><tr>
			<td style="padding-top:5px;padding-bottom:5px;">CGST</td>
			<td style="text-align: right;font-family: Times New Roman, Times, serif;"><span class="value_result" data-value="total_cgst" style="font-family: Times New Roman, Times, serif; font-size: 14px; color: rgb(0, 0, 0);float:right;">000.00</span></td>
			</tr>
			<tr>
			<td style="padding-top:5px;padding-bottom:5px;">SGST</td>
			<td style="text-align: right;"><span class="value_result" data-value="total_sgst" style="font-family: Times New Roman, Times, serif; font-size: 14px; color: rgb(0, 0, 0);float:right;">00.00</span></td>
			</tr>
			<tr>
			<td style="padding-top:5px;padding-bottom:5px;">IGST</td>
			<td style="text-align: right;"><span class="value_result" data-value="total_igst" style="font-family: Times New Roman, Times, serif; font-size: 14px; color: rgb(0, 0, 0);float:right;">000.00</span></td>
			</tr>
			<tr>
			<td style="padding-top:5px;padding-bottom:5px;">Round off</td>
			<td style="text-align: right;"><span class="value_result" data-value="round_off" style="font-family: Times New Roman, Times, serif; font-size: 14px; color: rgb(0, 0, 0);float:right;">000.00</span></td>
			</tr>
			<tr>
			<td style="padding-top:5px;padding-bottom:5px;">&nbsp;</td>
			<td style="text-align: right;">&nbsp;</td>
			</tr>
			<tr>
			<td style="padding-top:5px;padding-bottom:5px;">&nbsp;</td>
			<td style="text-align: right;">&nbsp;</td>
			</tr>
			<tr>
			<td style="padding-top:20px;padding-bottom:38px;font-family: Times New Roman, Times, serif;">TOTAL:</td>
			<td style="text-align: right;padding-top:20px;padding-bottom:38px;font-family: Times New Roman, Times, serif;"><span class="value_result" data-value="total_amountwithtax" style="font-family: Times New Roman, Times, serif; font-size: 14px; color: rgb(0, 0, 0);float:right;">000.00</span></td>
			</tr>
			</tbody></table>
			</td>
			</tr></tbody></table>
			</div>
			  <div style="position: relative;font-family: Times New Roman, Times, serif; color: rgb(0, 0, 0);  width: 100%;" class="footer_container content_container">
			  Discription:Goods once sold can not be taken back!
			  </div>
			  </div>
		</div>';
	}
	else if($template_type->name == "B2B_HSNbased_JobEstimation"){
	  	
	  	$data = '<style>
				.workspace
				{
				    display: block;
				    page-break-inside: avoid;
				  page-break-before: avoid;
				  page-break-after: avoid;
				    -webkit-region-break-inside: avoid; 
				}
				</style>
				<div data-type="portrait" style="background: rgb(255, 255, 255);" class="workspace">
				<div class="invoice_print" style="border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				  <div style="position: relative; min-height: 300px; height: 299px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="header_container content_container">

				  <div style="float: left; position: absolute; top: 143px; left: 0.0000292188px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle">
				 <hr style="border: 1px solid black; width: 700px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				  <div class="remove" style="display: none; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><i class="fa fa-times"></i></div></div>
				  <div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 166px; left: 8.00003px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Vehicle:</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="vehicle_number">Vehicle Number</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 191px; left: 10px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Modal: </div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="make_model_variant">Make-Model-variant</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 212px; left: 10px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Customer:</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="customer_vendor">Customer / Vendor</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 233px; left: 11px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Address:</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="customer_address">Customer Address</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 254px; left: 11px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">GST No:</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="customer_gst">Customer GST</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 165px; left: 456px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Voucher#: </div><div style="right: -5px; top: 15px; width: 15px; display: none; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="purchase">Purchase</div> <div class="remove" style="display: none; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 184px; left: 474px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Dated:</div><div style="right: -5px; top: 15px; width: 15px; display: none; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="date">Date</div> <div class="remove" style="display: none; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 0.116652px; left: 183.117px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(0, 0, 0); font-size: 24px; font-family: &quot;Times New Roman&quot;, Times, serif; font-weight: bold;" class="value_result" data-value="company_name">My company Name</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 39.1167px; left: 204.117px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_address">My company Address</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 67.1167px; left: 178.117px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Phone : </div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_phone">My company Phone</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 67.4667px; left: 336.467px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">GSTN : </div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_gst">Company GST</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 108.233px; left: 237.233px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(0, 0, 0); font-size: 22px; font-family: &quot;Times New Roman&quot;, Times, serif; font-weight: bold;" class="value_result" data-value="voucher_type">Voucher Type</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div></div>
				<div style="position: relative; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="body_container">
				<table style="width: 100%; border: 1px solid black; border-collapse: collapse; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="invoice_item_table">
				<thead style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				    <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_id">Sl.No</th>
				      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_desc">PARTICULARS</th>
				      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_hsn">HSN/SAC</th>
				      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_quantity">QTY</th>
				      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_rate">RATE</th>
				      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_discount">DISCOUNT</th>
				      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_t_amount">AMOUNT</th>
				    </tr>
				</thead>
				<tbody style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				     <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
				   </tr>
				     <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
				   </tr>
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
				   </tr>
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
				   </tr>
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
				   </tr>
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
				   </tr>
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
				   </tr>
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
				   </tr>
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
				   </tr>
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
				   </tr>
				   </tbody><tfoot style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				   <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				   <td class="col_id" colspan="3" style="border-top: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">E &amp; OE</td>
				      <td style="border-top: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">&nbsp;</td>
				      <td style="border-top: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">&nbsp;</td>
				      <td style="border-top: 1px solid black; border-right: 1px solid black; border-left: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">Total:<span class="value_result" data-value="total_discount" style="font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif; color: rgb(0, 0, 0);">000.00</span></td>
				      <td style="border-top: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">Total:<span class="value_result" data-value="total_amount" style="font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif; color: rgb(0, 0, 0);">000.00</span></td>
				   </tr>
				   </tfoot>
				  
				</table>
				<table style="width: 100%; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<tbody style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><td style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<table style="width: 80%; border-collapse: collapse; border: 1px solid black; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="hsnbasedTable">
				<thead style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sac">HSN/SAC</td>
				<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_tax_value">Taxable</td>
				<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_igst">IGST%</td>
				<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_igst_amount">IGST Amt</td>
				<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_cgst">CGST%</td>
				<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_cgst_amount">CGST Amt</td>
				<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sgst">SGST%</td>
				<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sgst_amount">SGST Amt</td>
				</tr>
				</thead>
				<tbody style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sac">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_tax_value">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_igst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_igst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_cgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_cgst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sgst_amount">&nbsp;</td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sac">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_tax_value">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_igst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_igst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_cgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_cgst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sgst_amount">&nbsp;</td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sac">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_tax_value">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_igst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_igst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_cgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_cgst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sgst_amount">&nbsp;</td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sac">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_tax_value">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_igst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_igst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_cgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_cgst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sgst_amount">&nbsp;</td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sac">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_tax_value">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_igst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_igst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_cgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_cgst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sgst_amount">&nbsp;</td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sac">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_tax_value">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_igst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_igst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_cgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_cgst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sgst_amount">&nbsp;</td>
				</tr>
				</tbody>
				<tfoot style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<td colspan="8" style="border-top: 1px solid black; padding-bottom: 25px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">Rupees: <i class="value_result" data-value="rupees" style="font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif; color: rgb(0, 0, 0);">Four Thousand Only</i></td>
				</tr>
				</tfoot>
				</table>
				<table style="width: 20%; float: left; border-color: black; border-style: solid; border-width: 1px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="ft">
				<tbody style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<td style="padding-top: 5px; padding-bottom: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">CGST</td>
				<td style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><span class="value_result" data-value="total_cgst" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); float: right;">000.00</span></td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<td style="padding-top: 5px; padding-bottom: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">SGST</td>
				<td style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><span class="value_result" data-value="total_sgst" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); float: right;">00.00</span></td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<td style="padding-top: 5px; padding-bottom: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">IGST</td>
				<td style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><span class="value_result" data-value="total_igst" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); float: right;">000.00</span></td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<td style="padding-top: 5px; padding-bottom: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">Round off</td>
				<td style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><span class="value_result" data-value="round_off" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); float: right;">000.00</span></td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<td style="padding-top: 5px; padding-bottom: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">&nbsp;</td>
				<td style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">&nbsp;</td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<td style="padding-top: 5px; padding-bottom: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">&nbsp;</td>
				<td style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">&nbsp;</td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<td style="padding-top: 20px; padding-bottom: 38px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">TOTAL:</td>
				<td style="text-align: right; padding-top: 20px; padding-bottom: 38px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><span class="value_result" data-value="total_amountwithtax" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); float: right;">000.00</span></td>
				</tr>
				</tbody></table>
				</td>
				</tr></tbody></table>
				</div>
				  <div style="position: relative; font-family: &quot;Times New Roman&quot;, Times, serif; color: rgb(0, 0, 0); width: 100%; font-size: 12px;" class="footer_container content_container">
				  Discription:Goods once sold can not be taken back!
				  </div>
				  </div>
				</div>';
	}
	else if($template_type->name == "B2B_TaxPercentage_Invoice"){
	  	
	  	$data = '<style>
					.workspace
					{
					    display: block;
					    page-break-inside: avoid;
					  page-break-before: avoid;
					  page-break-after: avoid;
					    -webkit-region-break-inside: avoid; 
					}
					</style>
					<div data-type="portrait" style="background: rgb(255, 255, 255);" class="workspace">
					<div class="invoice_print" style="border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					  <div style="position: relative; min-height: 300px; height: 302px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="header_container content_container">

					  <div style="float: left; position: absolute; top: 143px; left: -0.0000318164px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle">
					 <hr style="border: 1px solid black; width: 700px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					  <div class="remove" style="display: none; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><i class="fa fa-times"></i></div></div>
					  <div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 214.233px; left: 4.23331px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Customer : </div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="customer_vendor">Customer / Vendor</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 237.233px; left: 11.2333px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Address :</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="customer_address">Customer Address</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 257.35px; left: 10.3499px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">GST no : </div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="customer_gst">Customer GST</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 164.117px; left: 9.11667px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Vehicle :</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="vehicle_number">Vehicle Number</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 189.35px; left: 9.34994px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Modal : </div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="make_model_variant">Make-Model-variant</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 162.583px; left: 478.583px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Voucher # :</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="purchase">Purchase</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 186.7px; left: 502.7px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Dated :</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="date">Date</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: -0.533342px; left: 232.467px;" class="draggable ui-draggable ui-draggable-handle"></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 40.4667px; left: 214.467px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_address">My company Address</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 66.35px; left: 201.35px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Phone : </div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_phone">My company Phone</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 66.35px; left: 368.35px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">GSTN : </div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_gst">Company GST</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 112.467px; left: 254.467px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(0, 0, 0); font-size: 20px; font-family: &quot;Times New Roman&quot;, Times, serif; font-weight: bold;" class="value_result" data-value="voucher_type">Voucher Type</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: -2.53334px; left: 209.467px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(0, 0, 0); font-size: 26px; font-family: &quot;Times New Roman&quot;, Times, serif; font-weight: bold;" class="value_result" data-value="company_name">My company Name</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div></div>
					<div style="position: relative; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="body_container">
					<table style="width: 100%; border: 1px solid black; border-collapse: collapse; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="invoice_item_table">
					<thead style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					    <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_id">Sl.No</th>
					      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">PARTICULARS</th>
					      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">HSN/SAC</th>
					      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">QTY</th>
					      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">RATE</th>
					      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">DISCOUNT</th>
					      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">AMOUNT</th>
					    </tr>
					</thead>
					<tbody style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					     <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
					   </tr>
					     <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
					   </tr>
					        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
					   </tr>
					        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
					   </tr>
					        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
					   </tr>
					        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
					   </tr>
					        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
					   </tr>
					        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
					   </tr>
					        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
					   </tr>
					        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
					   </tr>
					   </tbody><tfoot style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					   <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					   <td class="col_id" colspan="3" style="border-top: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">E &amp; OE</td>
					      <td style="border-top: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
					      <td style="border-top: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
					      <td style="border-top: 1px solid black; border-right: 1px solid black; border-left: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">Total:<span class="value_result" data-value="total_discount" style="font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif; color: rgb(0, 0, 0);">000.00</span></td>
					      <td style="border-top: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">Total:<span class="value_result" data-value="total_amount" style="font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif; color: rgb(0, 0, 0);">000.00</span></td>
					   </tr>
					   </tfoot>
					  
					</table>
					<table style="width: 100%; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<tbody style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><td style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<table style="width: 80%; border-collapse: collapse; border: 1px solid black; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="floatedTable">
					<thead style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_gst">GST%</td>
					<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_tax_value">Taxable</td>
					<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst">IGST%</td>
					<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst_amount">IGST Amt</td>
					<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst">CGST%</td>
					<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst_amount">CGST Amt</td>
					<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst">SGST%</td>
					<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst_amount">SGST Amt</td>
					</tr>
					</thead>
					<tbody style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_gst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_tax_value">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst_amount">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst_amount">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst_amount">&nbsp;</td>
					</tr>
					<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_gst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_tax_value">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst_amount">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst_amount">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst_amount">&nbsp;</td>
					</tr>
					<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_gst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_tax_value">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst_amount">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst_amount">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst_amount">&nbsp;</td>
					</tr>
					<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_gst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_tax_value">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst_amount">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst_amount">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst_amount">&nbsp;</td>
					</tr>
					<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_gst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_tax_value">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst_amount">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst_amount">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst_amount">&nbsp;</td>
					</tr>
					<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_gst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_tax_value">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst_amount">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst_amount">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst_amount">&nbsp;</td>
					</tr>
					</tbody>
					<tfoot style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<td colspan="8" style="border-top: 1px solid black; padding-bottom: 25px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">Rupees: <i class="value_result" data-value="rupees" style="font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif; color: rgb(0, 0, 0);">Four Thousand Only</i></td>
					</tr>
					</tfoot>
					</table>
					<table style="width: 20%; float: left; border-color: black; border-style: solid; border-width: 1px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="ft">
					<tbody style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<td style="padding-top: 5px; padding-bottom: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">CGST</td>
					<td style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><span class="value_result" data-value="total_cgst" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); float: right;">000.00</span></td>
					</tr>
					<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<td style="padding-top: 5px; padding-bottom: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">SGST</td>
					<td style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><span class="value_result" data-value="total_sgst" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); float: right;">00.00</span></td>
					</tr>
					<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<td style="padding-top: 5px; padding-bottom: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">IGST</td>
					<td style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><span class="value_result" data-value="total_igst" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); float: right;">000.00</span></td>
					</tr>
					<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<td style="padding-top: 5px; padding-bottom: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">Round off</td>
					<td style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><span class="value_result" data-value="round_off" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); float: right;">000.00</span></td>
					</tr>
					<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<td style="padding-top: 5px; padding-bottom: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
					<td style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
					</tr>
					<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<td style="padding-top: 5px; padding-bottom: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
					<td style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
					</tr>
					<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<td style="padding-top: 20px; padding-bottom: 38px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">TOTAL:</td>
					<td style="text-align: right; padding-top: 20px; padding-bottom: 38px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><span class="value_result" data-value="total_amountwithtax" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); float: right;">000.00</span></td>
					</tr>
					</tbody></table>
					</td>
					</tr></tbody></table>
					</div>
					  <div style="position: relative; font-family: &quot;Times New Roman&quot;, Times, serif; color: rgb(0, 0, 0); width: 100%; font-size: 14px;" class="footer_container content_container">
					  Discription:Goods once sold can not be taken back!
					  </div>
					  </div>
				</div>';
	}
	else if($template_type->name == "B2B_TaxPercentage_JobEstimation"){
	  	
	  	$data = '<style>
				.workspace
					{
					    display: block;
					    page-break-inside: avoid;
					  page-break-before: avoid;
					  page-break-after: avoid;
					    -webkit-region-break-inside: avoid; 
					}
				</style>
				<div data-type="portrait" style="background: rgb(255, 255, 255);" class="workspace">
				<div class="invoice_print" style="border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				  <div style="position: relative; min-height: 300px; height: 302px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="header_container content_container">

				  <div style="float: left; position: absolute; top: 143px; left: -0.0000318164px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle">
				 <hr style="border: 1px solid black; width: 700px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				  <div class="remove" style="display: none; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><i class="fa fa-times"></i></div></div>
				  <div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 214.233px; left: 4.23331px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Customer : </div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="customer_vendor">Customer / Vendor</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 237.233px; left: 11.2333px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Address :</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="customer_address">Customer Address</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 257.35px; left: 10.3499px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">GST no : </div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="customer_gst">Customer GST</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 164.117px; left: 9.11667px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Vehicle :</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="vehicle_number">Vehicle Number</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 189.35px; left: 9.34994px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Modal : </div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="make_model_variant">Make-Model-variant</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 162.583px; left: 478.583px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Voucher # :</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="purchase">Purchase</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 186.7px; left: 502.7px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Dated :</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="date">Date</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 2.11665px; left: 207.117px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(0, 0, 0); font-size: 26px; font-family: &quot;Times New Roman&quot;, Times, serif; font-weight: bold;" class="value_result" data-value="company_name">My company Name</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 48.35px; left: 196.35px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_address">My company Address</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 77.2333px; left: 185.233px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Phone : </div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_phone">My company Phone</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 22.1167px; left: 29.1167px;" class="draggable ui-draggable ui-draggable-handle"></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 77.2333px; left: 342.233px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">GST : </div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_gst">Company GST</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 106.467px; left: 250.467px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(0, 0, 0); font-size: 22px; font-family: &quot;Times New Roman&quot;, Times, serif; font-weight: bold;" class="value_result" data-value="voucher_type">Voucher Type</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div></div>
				<div style="position: relative; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="body_container">
				<table style="width: 100%; border: 1px solid black; border-collapse: collapse; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="invoice_item_table">
				<thead style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				    <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_id">Sl.No</th>
				      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">PARTICULARS</th>
				      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">HSN/SAC</th>
				      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">QTY</th>
				      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">RATE</th>
				      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">DISCOUNT</th>
				      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">AMOUNT</th>
				    </tr>
				</thead>
				<tbody style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				     <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
				   </tr>
				     <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
				   </tr>
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
				   </tr>
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
				   </tr>
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
				   </tr>
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
				   </tr>
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
				   </tr>
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
				   </tr>
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
				   </tr>
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
				   </tr>
				   </tbody><tfoot style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				   <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				   <td class="col_id" colspan="3" style="border-top: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">E &amp; OE</td>
				      <td style="border-top: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
				      <td style="border-top: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
				      <td style="border-top: 1px solid black; border-right: 1px solid black; border-left: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">Total:<span class="value_result" data-value="total_discount" style="font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif; color: rgb(0, 0, 0);">000.00</span></td>
				      <td style="border-top: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">Total:<span class="value_result" data-value="total_amount" style="font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif; color: rgb(0, 0, 0);">000.00</span></td>
				   </tr>
				   </tfoot>
				  
				</table>
				<table style="width: 100%; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				<tbody style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><td style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				<table style="width: 80%; border-collapse: collapse; border: 1px solid black; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="floatedTable">
				<thead style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_gst">GST%</td>
				<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_tax_value">Taxable</td>
				<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst">IGST%</td>
				<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst_amount">IGST Amt</td>
				<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst">CGST%</td>
				<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst_amount">CGST Amt</td>
				<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst">SGST%</td>
				<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst_amount">SGST Amt</td>
				</tr>
				</thead>
				<tbody style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_gst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_tax_value">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst_amount">&nbsp;</td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_gst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_tax_value">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst_amount">&nbsp;</td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_gst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_tax_value">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst_amount">&nbsp;</td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_gst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_tax_value">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst_amount">&nbsp;</td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_gst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_tax_value">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst_amount">&nbsp;</td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_gst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_tax_value">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst_amount">&nbsp;</td>
				</tr>
				</tbody>
				<tfoot style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				<td colspan="8" style="border-top: 1px solid black; padding-bottom: 25px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">Rupees: <i class="value_result" data-value="rupees" style="font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif; color: rgb(0, 0, 0);">Four Thousand Only</i></td>
				</tr>
				</tfoot>
				</table>
				<table style="width: 20%; float: left; border-color: black; border-style: solid; border-width: 1px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="ft">
				<tbody style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				<td style="padding-top: 5px; padding-bottom: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">CGST</td>
				<td style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><span class="value_result" data-value="total_cgst" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); float: right;">000.00</span></td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				<td style="padding-top: 5px; padding-bottom: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">SGST</td>
				<td style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><span class="value_result" data-value="total_sgst" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); float: right;">00.00</span></td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				<td style="padding-top: 5px; padding-bottom: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">IGST</td>
				<td style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><span class="value_result" data-value="total_igst" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); float: right;">000.00</span></td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				<td style="padding-top: 5px; padding-bottom: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">Round off</td>
				<td style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><span class="value_result" data-value="round_off" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); float: right;">000.00</span></td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				<td style="padding-top: 5px; padding-bottom: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
				<td style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				<td style="padding-top: 5px; padding-bottom: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
				<td style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				<td style="padding-top: 20px; padding-bottom: 38px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">TOTAL:</td>
				<td style="text-align: right; padding-top: 20px; padding-bottom: 38px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><span class="value_result" data-value="total_amountwithtax" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); float: right;">000.00</span></td>
				</tr>
				</tbody></table>
				</td>
				</tr></tbody></table>
				</div>
				  <div style="position: relative; font-family: &quot;Times New Roman&quot;, Times, serif; color: rgb(0, 0, 0); width: 100%; font-size: 14px;" class="footer_container content_container">
				  Discription:Goods once sold can not be taken back!
				  </div>
				  </div>
				</div>';
	}
	else if($template_type->name == "B2C_NoTax_JobEstimation"){
	  	
	  	$data = '<style>
	              .item_table {
	                border-collapse: collapse;
	              }
	               @media print {
	              body {
	                -webkit-print-color-adjust: exact;
	              }
	              }
	              </style>
	              <div data-type="portrait" style="width: 210mm; height: 300mm; background: rgb(255, 255, 255) none repeat scroll 0% 0%;border:1px solid" class="workspace">
	                <div style="position: relative; min-height: 200px; height: 266px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="header_container content_container">

	                <div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: -1.53334px; left: 274.467px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; color: rgb(0, 0, 0); font-size: 26px; font-family: &quot;Times New Roman&quot;, Times, serif; font-weight: bold;" class="value_result" data-value="company_name">My company Name</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 40.1167px; left: 328.117px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 92.5833px; left: 334.583px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; color: rgb(0, 0, 0); font-size: 24px; font-family: &quot;Times New Roman&quot;, Times, serif; font-weight: bold;" class="value_result" data-value="voucher_type">Voucher Type</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 182.467px; left: 2.4667px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Customer : </div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="customer_vendor">Customer / Vendor</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 202.7px; left: 13.7px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Address:</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="customer_address">Customer Address</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 144.467px; left: 16.4667px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Vehicle : </div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="vehicle_number">Vehicle Number</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 164.583px; left: 14.5833px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Vehicle :</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="make_model_variant">Make-Model-variant</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 221.467px; left: 22.4667px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">GSTN : </div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="customer_gst">Customer GST</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 143.467px; left: 514.467px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Estimation :</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="estimate_no">Estimation No</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 162.467px; left: 544.467px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Dated : </div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="date">Estimation Date</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 39.8167px; left: 249.817px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_address">My company Address</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 65.35px; left: 223.35px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Phone : </div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_phone">My company Phone</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 65.2333px; left: 445.233px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">GSTN : </div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_gst">Company GST</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; height: 10px; float: left; position: absolute; top: 118.117px; left: 0.116667px;" class="draggable ui-draggable ui-draggable-handle"><div style="color: transparent; border-style: solid; border-color: rgb(0, 0, 0); border-width: 0px 0px 1.11667px; width: 790px;" class="line_result">Static Text</div><div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div>
	                <div style="position: relative; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="body_container content_container">
	                  <table border="1" class="no_tax_sales_table" style="border-top: 1px solid; border-bottom: 1px solid; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" width="100%">
	                    <thead style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
	                      <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); background-color: rgb(242, 242, 242);">
	                        <th style="text-align: center;font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_id">#</th>
	                        <th style="text-align: center;font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">Item Description</th>
	                        <th style="text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">Quantity</th>
	                        <th style="text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">UnitRate RS</th>
	                        <th style="text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">Discount RS</th>
	                        <th style="text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_amount">Total Rs</th>
	                      </tr>
	                    </thead>
	                    <tbody style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
	                      <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
	                        <td style="text-align: center;padding: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
	                        <td style="text-align: center;padding: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
	                     <td style="padding: 5px; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
	                     <td style="padding: 5px; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
	                        <td style="padding: 5px; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
	                        <td style="padding: 5px; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_amount">&nbsp;</td>
	                      </tr>
	                      <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
	                        <td style="text-align: center;padding: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
	                        <td style="text-align: center;padding: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
	                     <td style="padding: 5px; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
	                     <td style="padding: 5px; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
	                        <td style="padding: 5px; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
	                        <td style="padding: 5px; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_amount">&nbsp;</td>
	                      </tr>
	                    </tbody>
	                  </table>
	                </div>
	                <br style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
	                <div class="total_container content_container" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
	                  <table class="total_table" style="border-bottom: 1px solid; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" width="100%" align="right">
	                    <tbody style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
	                      <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
	                        <td style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" width="25%"></td>
	                        <td style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" width="25%"></td>
	                        <td style="text-align: center;font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">Total</td>
	                        <td class="sales_total_amount" style="text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">0.00</td>
	                      </tr>
	                    </tbody>
	                  </table>
	                </div>
	                <div style="position: relative; float: left; width: 100%; height: 49px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="footer_container content_container">
	                  
	                <div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; position: absolute; top: 4.23331px; left: 5.23331px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div class="text_result" style="color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;">Disclaimer:<br style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">Above work information and amount mentioned here may change<br style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">during actual Job. The amount for each work or goods<br style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"> mentioned here has tax included as per the Government norms. <br style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">Please contact us within 7 days of this estimation for any note.<br style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"></div><div class="remove" style="display: none; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><i class="fa fa-times"></i></div></div><div style="width: auto; float: left; position: absolute; top: 3.35001px; left: 585.35px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="border-style: solid; border-color: rgb(0, 0, 0); border-width: 1.11667px; width: 200px; height: 100px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="rectangle_result"></div><div class="remove" style="display: none; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><i class="fa fa-times"></i></div></div></div>
	            </div>';
	}
	else if($template_type->name == "B2C_NoTax_JobInvoice"){
	  	
	  	$data = '<style>
	              .item_table {
	                border-collapse: collapse;
	              }
	               @media print {
	              body {
	                -webkit-print-color-adjust: exact;
	              }
	              }
	              </style>
	              <div data-type="portrait" style="width: 210mm; height: 300mm; background: rgb(255, 255, 255) none repeat scroll 0% 0%;border:1px solid" class="workspace">
	                <div style="position: relative; min-height: 200px; height: 266px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="header_container content_container">

	                <div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: -1.53334px; left: 274.467px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; color: rgb(0, 0, 0); font-size: 26px; font-family: &quot;Times New Roman&quot;, Times, serif; font-weight: bold;" class="value_result" data-value="company_name">My company Name</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 40.1px; left: 328.1px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 92.5667px; left: 334.567px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; color: rgb(0, 0, 0); font-size: 24px; font-family: &quot;Times New Roman&quot;, Times, serif; font-weight: bold;" class="value_result" data-value="voucher_type">Voucher Type</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 182.467px; left: 2.4667px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Customer : </div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="customer_vendor">Customer / Vendor</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 202.683px; left: 13.7px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Address:</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="customer_address">Customer Address</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 144.467px; left: 16.4667px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Vehicle : </div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="vehicle_number">Vehicle Number</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 164.567px; left: 14.5667px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Vehicle :</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="make_model_variant">Make-Model-variant</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 221.45px; left: 22.4667px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">GSTN : </div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="customer_gst">Customer GST</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 142.45px; left: 534.417px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Invoice :</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="estimate_no">Estimation No</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 162.467px; left: 544.467px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Dated : </div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="date">Estimation Date</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 39.8167px; left: 249.817px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_address">My company Address</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 65.35px; left: 223.333px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Phone : </div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_phone">My company Phone</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 65.2333px; left: 445.233px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">GSTN : </div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_gst">Company GST</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; height: 10px; float: left; position: absolute; top: 118.1px; left: 0.100005px;" class="draggable ui-draggable ui-draggable-handle"><div style="color: transparent; border-style: solid; border-color: rgb(0, 0, 0); border-width: 0px 0px 1.11667px; width: 790px;" class="line_result">Static Text</div><div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div>
	                <div style="position: relative; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="body_container content_container">
	                  <table border="1" class="no_tax_sales_table" style="border-top: 1px solid; border-bottom: 1px solid; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" width="100%">
	                    <thead style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
	                      <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); background-color: rgb(242, 242, 242);">
	                        <th style="text-align: center;font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_id">#</th>
	                        <th style="text-align: center;font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">Item Description</th>
	                        <th style="text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">Quantity</th>
	                        <th style="text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">UnitRate RS</th>
	                        <th style="text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">Discount RS</th>
	                        <th style="text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_amount">Total Rs</th>
	                      </tr>
	                    </thead>
	                    <tbody style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
	                      <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
	                        <td style="text-align: center;padding: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
	                        <td style="text-align: center;padding: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
	                     <td style="padding: 5px; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
	                     <td style="padding: 5px; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
	                        <td style="padding: 5px; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
	                        <td style="padding: 5px; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_amount">&nbsp;</td>
	                      </tr>
	                      <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
	                        <td style="text-align: center;padding: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
	                        <td style="text-align: center;padding: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
	                     <td style="padding: 5px; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
	                     <td style="padding: 5px; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
	                        <td style="padding: 5px; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
	                        <td style="padding: 5px; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_amount">&nbsp;</td>
	                      </tr>
	                    </tbody>
	                  </table>
	                </div>
	                <br style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
	                <div class="total_container content_container" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
	                  <table class="total_table" style="border-bottom: 1px solid; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" width="100%" align="right">
	                    <tbody style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
	                      <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
	                        <td style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" width="25%"></td>
	                        <td style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" width="25%"></td>
	                        <td style="text-align: center;font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">Total</td>
	                        <td class="sales_total_amount" style="text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">0.00</td>
	                      </tr>
	                    </tbody>
	                  </table>
	                </div>
	                <div style="position: relative; float: left; width: 100%; height: 49px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="footer_container content_container">
	                  
	                <div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; position: absolute; top: 4.21664px; left: 5.23331px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div class="text_result" style="color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;">Disclaimer:<br style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">Above work information and amount mentioned here may change<br style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">during actual Job. The amount for each work or goods<br style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"> mentioned here has tax included as per the Government norms. <br style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">Please contact us within 7 days of this estimation for any note.<br style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"></div><div class="remove" style="display: none; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><i class="fa fa-times"></i></div></div><div style="width: auto; float: left; position: absolute; top: 3.33334px; left: 585.35px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="border-style: solid; border-color: rgb(0, 0, 0); border-width: 1.11667px; width: 200px; height: 100px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="rectangle_result"></div><div class="remove" style="display: none; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><i class="fa fa-times"></i></div></div></div>
	            </div>';
	}
	else if($template_type->name == "B2C_OneLine_Tax_Invoice"){
	  	
	  	$data = '<style>
				.item_table {
				  border-collapse: collapse;
				}
				 @media print {
				body {
				  -webkit-print-color-adjust: exact;
				}
				}
				</style>
				<div data-type="portrait" style="width: 210mm; height: 200mm; padding: 27mm 16mm; background: rgb(255, 255, 255);" class="workspace">
				  <div style="position: relative; min-height: 200px; height: 258px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="header_container content_container">

				<div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 170.7px; left: 11.7px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="value_result" data-value="customer_vendor">Customer / Vendor</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 191.7px; left: 12.7px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="customer_address">Customer Address</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 125.467px; left: 474.467px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Invoice No :</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="purchase">Purchase</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 147.7px; left: 477.7px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Invoice Dt :</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="date">Date</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: -2.53334px; left: 209.467px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; color: rgb(0, 0, 0); font-size: 26px; font-family: &quot;Times New Roman&quot;, Times, serif; font-weight: bold;" class="value_result" data-value="company_name">My company Name</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 35px; left: 15px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 54.5833px; left: 173.583px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Phone:</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_phone">My company Phone</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 57.5833px; left: 351.583px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">GSTN: </div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_gst">Company GST</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 74.4667px; left: 246.467px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(0, 0, 0); font-size: 22px; font-family: &quot;Times New Roman&quot;, Times, serif; font-weight: bold;" class="value_result" data-value="voucher_type">Voucher Type</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 130.233px; left: 12.2334px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="vehicle_number">Vehicle Number</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 149.467px; left: 13.4667px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="make_model_variant">Make-Model-variant</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 213.35px; left: 14.35px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="customer_gst">Customer GST</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 32.2333px; left: 235.233px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_address">My company Address</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; height: 10px; float: left; position: absolute; top: 102.117px; left: 0.116728px;" class="draggable ui-draggable ui-draggable-handle"><div style="color: transparent; border-style: solid; border-color: rgb(0, 0, 0); border-width: 0px 0px 1.11667px; width: 680px;" class="line_result">Static Text</div><div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: -0.0000165576px; left: 0.0000292188px;" class="draggable ui-draggable ui-draggable-handle"></div></div>
				  <div style="position: relative; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="body_container content_container">
				    <table border="1" class="no_tax_item_table" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" width="100%" border="0">
				      <thead style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); background-color: rgb(242, 242, 242);">
				          <th style="background: rgb(51, 51, 51) none repeat scroll 0% 0%; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_id">#</th>
				          <th style="text-align:center;background: rgb(51, 51, 51) none repeat scroll 0% 0%; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">Item Description</th>
				          <th style="text-align:center;background: rgb(51, 51, 51) none repeat scroll 0% 0%; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">Quantity</th>
				          <th style="text-align:center;background: rgb(51, 51, 51) none repeat scroll 0% 0%; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">UnitRate RS</th>
				      <th style="text-align:center;background: rgb(51, 51, 51) none repeat scroll 0% 0%; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">Discount RS</th>
				          <th style="text-align:center;background: rgb(51, 51, 51) none repeat scroll 0% 0%; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_amount">Total Rs</th>
				        </tr>
				      </thead>
				      <tbody style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				          <td style="padding: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
				          <td style="text-align:center;padding: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				       <td style="padding: 5px; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				       <td style="padding: 5px; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				          <td style="padding: 5px; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				          <td style="padding: 5px; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_amount">&nbsp;</td>
				        </tr>
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				          <td style="padding: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
				          <td style="text-align:center;padding: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				       <td style="padding: 5px; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				       <td style="padding: 5px; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				          <td style="padding: 5px; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				          <td style="padding: 5px; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_amount">&nbsp;</td>
				        </tr>
				      </tbody>
				    </table>
				  </div>
				  <br style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				  <div class="total_container content_container" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); height: 280px;">
				    <table class="total_table" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" width="100%" align="right">
				      <tbody style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				          <td style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" width="25%"></td>
				          <td style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" width="25%"></td>
				          <td style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">Sub-Total</td>
				          <td class="invoice_sub_total" style="text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">0.00</td>
				        </tr>
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				          <td style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" width="25%"></td>
				          <td style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" width="25%"></td>
				          <td class="tax_name" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">Tax Amount</td>
				          <td class="tax_value" style="text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">0.00</td>
				        </tr>
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				          <td style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" width="25%"></td>
				          <td style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" width="25%"></td>
				          <td style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">Total</td>
				          <td class="invoice_total_amount" style="text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">0.00</td>
				        </tr>
				      </tbody>
				    </table>
				  <div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 235.05px; left: 402.467px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"></div></div>
				  <div style="position: relative; float: left; width: 100%; height: 102px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="footer_container content_container">
				    
				<div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 57px; left: 25px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; position: absolute; top: -172.883px; left: 7.11673px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div class="text_result" style="color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;">Disclaimer:<br style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">Products sold can not be returned or Exchanged unless it is mentioned in Company warranty. These prices include Government taxes as per norms</div><div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div><div style="width: auto; height: 10px; float: left; position: absolute; top: -196.883px; left: 1.1167px;" class="draggable ui-draggable ui-draggable-handle"><div style="color: transparent; border-style: solid; border-color: rgb(0, 0, 0); border-width: 0px 0px 1.11667px; width: 680px;" class="line_result">Static Text</div><div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div>
				</div>';
	}
	else if($template_type->name == "B2C_OneLineTax_Job_Estimation"){
	  	
	  	$data = '<style>
				.item_table {
				  border-collapse: collapse;
				}
				 @media print {
				body {
				  -webkit-print-color-adjust: exact;
				}
				}
				</style>
				<div data-type="portrait" style="width: 210mm; height: 200mm; padding: 27mm 16mm; background: rgb(255, 255, 255) none repeat scroll 0% 0%;" class="workspace">
				  <div style="position: relative; min-height: 200px; height: 250px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="header_container content_container">

				  <div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: -0.0000165576px; left: 0.0000292188px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 111.117px; left: -0.883272px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; position: absolute; top: 118.117px; left: 2.11673px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div class="text_result" style="color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif; font-weight: bold;">Customer Information:</div><div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 134.117px; left: 4.11673px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="customer_vendor">Customer / Vendor</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 156.117px; left: 2.11673px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="customer_address">Customer Address</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 180.117px; left: 2.11673px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="customer_gst">Customer GST</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 203.467px; left: 2.4667px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 205.117px; left: 5.11673px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="vehicle_number">Vehicle Number</div> <div class="remove" style="display: none; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 114.233px; left: 492.233px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif; font-weight: bold;" class="label_result">Voucher:</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif; font-weight: bold;" class="value_result" data-value="voucher_type">Voucher Type</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 133.35px; left: 472.35px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Estimation No:</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="purchase">Purchase</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 154.117px; left: 475.117px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Estimation Dt:</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="date">Date</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 174.117px; left: 474.117px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Estimation By:</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="assigned_to">Mechannic Name</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; height: 10px; float: left; position: absolute; top: 93.1166px; left: -0.883272px;" class="draggable ui-draggable ui-draggable-handle"><div style="color: transparent; border-style: solid; border-color: rgb(0, 0, 0); border-width: 0px 0px 1.11667px; width: 665px;" class="line_result">Static Text</div><div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: -4.65001px; left: 197.35px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(0, 0, 0); font-size: 24px; font-family: &quot;Times New Roman&quot;, Times, serif; font-weight: bold;" class="value_result" data-value="company_name">My company Name</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 35.7px; left: 191.7px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_address">My company Address</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 69.35px; left: 145.35px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Phone : </div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_phone">My company Phone</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 70.35px; left: 330.35px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">GSTN : </div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_gst">Company GST</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div></div>
				  <div style="position: relative; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="body_container content_container">
				    <table border="1" class="no_tax_item_table" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" width="100%" border="0">
				      <thead style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); background-color: rgb(242, 242, 242);">
				          <th style="text-align: center;background: rgb(51, 51, 51) none repeat scroll 0% 0%; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_id">#</th>
				          <th style="text-align: center;background: rgb(51, 51, 51) none repeat scroll 0% 0%; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_desc">Item Description</th>
				          <th style="text-align: center;background: rgb(51, 51, 51) none repeat scroll 0% 0%; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_quantity">Quantity</th>
				          <th style="text-align: right;background: rgb(51, 51, 51) none repeat center 0% 0%; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_rate">UnitRate RS</th>
				      <th style="text-align: center;background: rgb(51, 51, 51) none repeat scroll 0% 0%; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_discount">Discount RS</th>
				          <th style="text-align: center;background: rgb(51, 51, 51) none repeat scroll 0% 0%; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_amount">Total Rs</th>
				        </tr>
				      </thead>
				      <tbody style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				          <td style="text-align: center;padding: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
				          <td style="text-align: center;padding: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				       <td style="padding: 5px; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				       <td style="padding: 5px; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				          <td style="padding: 5px; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				          <td style="padding: 5px; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_amount">&nbsp;</td>
				        </tr>
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				          <td style="text-align: center;padding: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
				          <td style="text-align: center;padding: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				       <td style="padding: 5px; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				       <td style="padding: 5px; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				          <td style="padding: 5px; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				          <td style="padding: 5px; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_amount">&nbsp;</td>
				        </tr>
				      </tbody>
				    </table>
				  </div>
				  <br style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				  <div class="total_container content_container" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				    <table class="total_table" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" width="100%" align="right">
				      <tbody style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				          <td style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" width="25%"></td>
				          <td style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" width="25%"></td>
				          <td style="text-align: center;font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">Sub-Total</td>
				          <td class="invoice_sub_total" style="text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">0.00</td>
				        </tr>
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				          <td style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" width="25%"></td>
				          <td style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" width="25%"></td>
				          <td class="tax_name" style="text-align: center;font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">Tax Amount</td>
				          <td class="tax_value" style="text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">0.00</td>
				        </tr>
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				          <td style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" width="25%"></td>
				          <td style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" width="25%"></td>
				          <td style="text-align: center;font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">Total</td>
				          <td class="invoice_total_amount" style="text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">0.00</td>
				        </tr>
				      </tbody>
				    </table>
				  </div>
				  <div style="position: relative; float: left; width: 100%; height: 200px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="footer_container content_container">
				    
				  <div style="width: auto; height: 10px; float: left; position: absolute; top: -0.0000318164px; left: 0.0000292188px;" class="draggable ui-draggable ui-draggable-handle"><div style="color: transparent; border-style: solid; border-color: rgb(0, 0, 0); border-width: 0px 0px 1.11667px; width: 670px;" class="line_result">Static Text</div><div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div>
				</div>';
	}
	else if($template_type->name == "Payslip_Print"){
	  	
	  	$data = '<style>
				.item_table {
					border-collapse: collapse;
					border-width: 0px;
					border: 1px solid #000;
				}
				 @media print {
				body {
					-webkit-print-color-adjust: exact;
				}
				}
				</style>

				<div data-type="portrait" style="width: 273mm; height: 200mm; padding: 57mm 16mm 27mm 16mm; background: rgb(255, 255, 255) none repeat scroll 0% 0%;" class="workspace">
				  <div style="position: relative; min-height: 140px; font-family: Arial, sans-serif; color: rgb(0, 0, 0); height: 232px;" class="header_container content_container">
					<div style="width: auto; float: left; position: absolute; top: 0.999999px; left: 0.0000292188px; font-family: Arial, sans-serif; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle">
					  <div style="border-style: solid; border-color: rgb(0, 0, 0); border-width: 1px; width: 912px; height: 97px; font-family: Arial, sans-serif; color: rgb(0, 0, 0);" class="rectangle_result"></div>
					  <div class="remove" style="font-family: Arial, sans-serif; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div>
					</div>
					<div style="float: left; font-family: Arial, sans-serif; z-index: 1; position: absolute; top: 10px; left: -4.99997px; width: 100%; text-align: center; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle">
					  <div class="text_result" style="color: rgb(0, 0, 0); font-size: 26px; font-family: Tahoma, sans-serif; width:100%;">Demo company</div>
					  <div class="remove" style="font-family: Arial, sans-serif; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div>
					</div>
					<div style="float: left; font-family: Arial, sans-serif; z-index: 1; position: absolute; top: 47px; left: -1.99997px; width: 100%; text-align: center; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle">
					  <div class="text_result" style="color: rgb(0, 0, 0); font-family: Tahoma, sans-serif; font-size: 12px; width:100%;"> Abiramam. Tamil Nadu.</div>
					  <div class="remove" style="font-family: Arial, sans-serif; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div>
					</div>
					<div style="float: left; font-family: Arial, sans-serif; z-index: 1; position: absolute; top: 67px; left: 0.0000292188px; width: 100%; color: rgb(0, 0, 0); text-align: center;" class="draggable ui-draggable ui-draggable-handle">
					  <div class="text_result" style="color: rgb(0, 0, 0); font-family: Tahoma, sans-serif; font-size: 12px; width:100%;">Mobile: 8056259119, Email: rajeshkennedy@yahoo.com, </div>
					  <div class="remove" style="font-family: Arial, sans-serif; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div>
					</div>
					<div style="width: auto; float: left; position: absolute; top: 100px; left: 0.0000292188px;" class="draggable ui-draggable ui-draggable-handle">
					  <div style="border-style: solid; border-color: rgb(0, 0, 0); border-width: 1px; width: 912px; height: 40px;" class="rectangle_result"></div>
					  <div class="remove" style="display: none;"><i class="fa fa-times"></i></div>
					</div>
					<div style="float: left; font-family: Arial, sans-serif; z-index: 1; position: absolute; top: 110px; width: 100%; text-align: center; left: 0.0000292188px;" class="draggable ui-draggable ui-draggable-handle">
					  <div class="remove" style="display: none;"><i class="fa fa-times"></i></div>
					  <div style=" display:inline-block;">
						<div style="color: rgb(0, 0, 0); font-family: Arial, sans-serif; font-weight: bold; display:inline-block;" class="label_result">PAY SLIP FOR THE MONTH</div>
						<div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div>
					  </div>
					  <div style="color: rgb(0, 0, 0); font-family: Arial, sans-serif; font-weight: bold;display:inline-block;" class="value_result" data-value="salary_month_year">Salary Month - Year</div>
					  <div class="remove" style="display: none;"><i class="fa fa-times"></i></div>
					</div>
					<div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; position: absolute; top: -0.00000129883px; left: 0.0000292188px;" class="draggable ui-draggable ui-draggable-handle"></div>
					<div style="width: auto; float: left; position: absolute; top: 141px; left: 0.0000292188px;" class="draggable ui-draggable ui-draggable-handle">
					  <div style="border-style: solid; border-color: rgb(0, 0, 0); border-width: 1px; width: 912px; height: 100px;" class="rectangle_result"></div>
					</div>
					<div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 150px; left: 10px;" class="draggable ui-draggable ui-draggable-handle">
					  <div>
						<div style="float:left;">
						  <div style="float: left; padding-right: 15px; width:120px; color: rgb(0, 0, 0); font-family: Arial, sans-serif; font-weight: bold;" class="label_result">Employee</div>
						  <div style="right: 10px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div>
						</div>
						<div style="float: left; color: rgb(0, 0, 0); font-family: Arial, sans-serif; font-weight: bold;" class="value_result" data-value="employee">Employee Name</div>
						<div class="remove" style="display: none;"><i class="fa fa-times"></i></div>
					  </div>
					</div>
					<div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 175px; left: 10px;" class="draggable ui-draggable ui-draggable-handle">
					  <div>
						<div style="float:left;">
						  <div style="float: left; padding-right: 15px; width:120px; color: rgb(0, 0, 0); font-family: Arial, sans-serif; font-weight: bold;" class="label_result">Employee ID</div>
						  <div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div>
						</div>
						<div style="float: left; color: rgb(0, 0, 0); font-family: Arial, sans-serif; font-weight: bold;" class="value_result" data-value="employee_id">Employee ID</div>
						<div class="remove" style="display: none;"><i class="fa fa-times"></i></div>
					  </div>
					</div>
					<div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 200px; left: 10px;" class="draggable ui-draggable ui-draggable-handle">
					  <div>
						<div style="float:left;">
						  <div style="float: left; padding-right: 15px; width:120px; color: rgb(0, 0, 0); font-family: Arial, sans-serif; font-weight: bold;" class="label_result">Designation</div>
						  <div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div>
						</div>
						<div style="float: left; color: rgb(0, 0, 0); font-family: Arial, sans-serif; font-weight: bold;" class="value_result" data-value="designation">Designation</div>
						<div class="remove" style="display: none;"><i class="fa fa-times"></i></div>
					  </div>
					</div>
				  </div>
				  <div style="position: relative; font-family: Arial, sans-serif; color: rgb(0, 0, 0);float: left; width:914px;" class="body_container content_container">
					<div class="col_earnings" style="float: left; width: 50%;">
					  <table class="item_table earnings" width="100%" border="0">
						<thead>
						  <tr>
							<th style="background: rgb(51, 51, 51) none repeat scroll 0% 0%; color: rgb(255, 255, 255); padding: 5px 10px; text-align:left;" width="50%">Earnings</th>
							<th style="background: rgb(51, 51, 51) none repeat scroll 0% 0%; color: rgb(255, 255, 255); padding: 5px 10px; text-align:right;" width="50%">Amount</th>
						  </tr>
						</thead>
						<tbody>
						  <tr>
							<td style="padding:5px; text-align:left;"></td>
							<td style="padding:5px; text-align:right;"></td>
						  </tr>
						  <tr style="background: #f2f2f2">
							<td style="padding:5px; text-align:left;"></td>
							<td style="padding:5px; text-align:right;"></td>
						  </tr>
						</tbody>
					  </table>
					  <table style="border-top: none;" class="item_table" width="100%" border="0">
						<tbody>
						  <tr>
							<td style="padding:5px; text-align:left; font-weight:bold;" width="50%">Total Earnings</td>
							<td style="padding:5px; text-align:right; font-weight:bold;" width="50%"><span data-value="total_earnings"></span></td>
						  </tr>
						</tbody>
					  </table>
					</div>
					<div class="col_deductions" style="float: left; width: 50%;">
					  <table class="item_table deductions" width="100%" border="0">
						<thead>
						  <tr>
							<th style="background: rgb(51, 51, 51) none repeat scroll 0% 0%; color: rgb(255, 255, 255); padding: 5px 10px; text-align:left;" width="50%">Deductions</th>
							<th style="background: rgb(51, 51, 51) none repeat scroll 0% 0%; color: rgb(255, 255, 255); padding: 5px 10px; text-align:right;" width="50%">Amount</th>
						  </tr>
						</thead>
						<tbody>
						  <tr>
							<td style="padding:5px; text-align:left;"></td>
							<td style="padding:5px; text-align:right;"></td>
						  </tr>
						  <tr style="background: #f2f2f2">
							<td style="padding:5px; text-align:left;"></td>
							<td style="padding:5px; text-align:right;"></td>
						  </tr>
						</tbody>
					  </table>
					  <table style="border-top: none;" class="item_table" width="100%" border="0">
						<tbody>
						  <tr>
							<td style="padding:5px; text-align:left; font-weight:bold;" width="50%">Total Deductions</td>
							<td style="padding:5px; text-align:right; font-weight:bold;" width="50%"><span data-value="total_deductions"></span></td>
						  </tr>
						</tbody>
					  </table>
					</div>
				  </div>
				  <div style="position: relative; float: left; width:100%; font-family: Arial, sans-serif; color: rgb(0, 0, 0);" class="total_container content_container"> </div>
				  <div style="position: relative; height: 123px; font-family: Arial, sans-serif; color: rgb(0, 0, 0); float: left; width: 100%;" class="footer_container content_container">
					<div style="width: auto; float: left; position: absolute; top: -0.0000318164px; left: 0.0000292188px;" class="draggable ui-draggable ui-draggable-handle">
					  <div style="border-style: solid; border-color: rgb(0, 0, 0); border-width: 1px; width: 912px; height: 35px;" class="rectangle_result"></div>
					  <div class="remove" style="display: none;"><i class="fa fa-times"></i></div>
					</div>
					<div style="float: left; font-family: Arial, sans-serif; z-index: 1; position: absolute; top: 9.99997px; width: 100%; text-align: right; left: 0.0000292188px;" class="draggable ui-draggable ui-draggable-handle">
					  <div class="remove" style="display: none;"><i class="fa fa-times"></i></div>
					  <div style=" display:inline-block;">
						<div style="color: rgb(0, 0, 0); font-family: Arial, sans-serif; font-weight: bold; display:inline-block;" class="label_result selected_item">Net Pay  </div>
						<div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div>
					  </div>
					  <div style="color: rgb(0, 0, 0); font-family: Arial, sans-serif; font-weight: bold;display:inline-block;" class="value_result" data-value="net_total">Total</div>
					  <div class="remove" style="display: none;"><i class="fa fa-times"></i></div>
					</div>
					<div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; position: absolute; top: 55px; left: 3.00003px;" class="draggable ui-draggable ui-draggable-handle">
					  <div class="text_result" style="color: rgb(0, 0, 0); font-family: Arial, sans-serif; font-weight: bold;">Net Pay (In Words): <span class="net_pay_in_words"></span></div>
					  <div class="remove" style="display: none;"><i class="fa fa-times"></i></div>
					</div>
					<div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 55px; left: 170px;" class="draggable ui-draggable ui-draggable-handle">
					<div>
					<div style="float: left; color: rgb(0, 0, 0); font-family: Arial, sans-serif; font-weight: bold;" class="value_result" data-value="net_pay_words">Net Pay in Words</div> 
					<div class="remove" style="display: none;"><i class="fa fa-times"></i></div>
					</div>
					</div>
					</div>
					<div style="width: auto; height: 10px; float: left; position: absolute; top: 65px; left: 1px;" class="draggable ui-draggable ui-draggable-handle">
					  <div style="color: transparent; border-style: solid; border-color: rgb(0, 0, 0); border-width: 0px 0px 1px; width: 912px;" class="line_result">Static Text</div>
					  <div class="remove" style="display: none;"><i class="fa fa-times"></i></div>
					</div>
					<div style="float: left; font-family: Arial, sans-serif; z-index: 1; position: absolute; top: 95px; width: 100%; text-align: center; left: 0px;" class="draggable ui-draggable ui-draggable-handle">
					  <div class="text_result" style="color: rgb(0, 0, 0); font-family: Arial, sans-serif; font-size: 12px;">*This is computer generated Payslip. Signature not required!</div>
					  <div class="remove" style="display: none;"><i class="fa fa-times"></i></div>
					</div>
			</div>';
	}
	else if($template_type->name == "PO_Purchase_GRN"){
	  	
	  	$data = '<style>
				.item_table {
				  border-collapse: collapse;
				}
				 @media print {
				body {
				  -webkit-print-color-adjust: exact;
				}
				}
				</style>
				<div data-type="portrait" style="width: 210mm; height: 200mm; padding: 27mm 16mm; background: rgb(255, 255, 255) none repeat scroll 0% 0%;" class="workspace">
				  <div style="position: relative; min-height: 200px; height: 199px; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" class="header_container content_container">

				  <div style="width: auto; float: left; font-family: &quot;MS Serif&quot;, serif; z-index: 1; min-width: 150px; position: absolute; top: 111px; left: 9.00003px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;MS Serif&quot;, serif;" class="label_result">Supplier:</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;MS Serif&quot;, serif;" class="value_result" data-value="customer_vendor">Customer / Vendor</div> <div class="remove" style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;MS Serif&quot;, serif; z-index: 1; min-width: 150px; position: absolute; top: 132px; left: 9.00003px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;MS Serif&quot;, serif;" class="label_result">GSTN: </div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;MS Serif&quot;, serif;" class="value_result" data-value="customer_gst">Customer GST</div> <div class="remove" style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;MS Serif&quot;, serif; z-index: 1; min-width: 150px; position: absolute; top: 154px; left: 11px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;MS Serif&quot;, serif;" class="label_result">Address: </div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;MS Serif&quot;, serif;" class="value_result" data-value="customer_address">Customer Address</div> <div class="remove" style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;MS Serif&quot;, serif; z-index: 1; min-width: 150px; position: absolute; top: 113.117px; left: 468.117px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;MS Serif&quot;, serif;" class="label_result">Voucher : </div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;MS Serif&quot;, serif;" class="value_result" data-value="grn">Goods Receipt Note</div> <div class="remove" style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;MS Serif&quot;, serif; z-index: 1; min-width: 150px; position: absolute; top: 129.35px; left: 488.35px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;MS Serif&quot;, serif;" class="label_result">Date: </div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;MS Serif&quot;, serif;" class="value_result" data-value="date">Date</div> <div class="remove" style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: -4.65001px; left: 224.35px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(0, 0, 0); font-size: 24px; font-family: &quot;Times New Roman&quot;, Times, serif; font-weight: bold;" class="value_result" data-value="company_name">My company Name</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 39.4667px; left: 236.467px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_address">My company Address</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 66.2333px; left: 185.233px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Phone : </div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_phone">My company Phone</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 67.2333px; left: 371.233px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">GSTN : </div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_gst">Company GST</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; height: 10px; float: left; position: absolute; top: 84.1167px; left: 1.1167px;" class="draggable ui-draggable ui-draggable-handle"><div style="color: transparent; border-style: solid; border-color: rgb(0, 0, 0); border-width: 0px 0px 1.11667px; width: 670px;" class="line_result">Static Text</div><div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div>
				  <div style="position: relative; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" class="body_container content_container">
				    <table class="no_tax_item_table" style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" width="100%" border="0">
				      <thead style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);">
				        <tr style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0); background-color: rgb(242, 242, 242);">
				          <th style="background: rgb(51, 51, 51) none repeat scroll 0% 0%; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_id">#</th>
				          <th style="background: rgb(51, 51, 51) none repeat scroll 0% 0%; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_desc">Item Description</th>
				          <th style="background: rgb(51, 51, 51) none repeat scroll 0% 0%; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_quantity">Quantity</th>
				          <th style="background: rgb(51, 51, 51) none repeat scroll 0% 0%; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_rate">UnitRate RS</th>
				      <th style="background: rgb(51, 51, 51) none repeat scroll 0% 0%; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_discount">Discount RS</th>
				          <th style="background: rgb(51, 51, 51) none repeat scroll 0% 0%; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_amount">Total Rs</th>
				        </tr>
				      </thead>
				      <tbody style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);">
				        <tr style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);">
				          <td style="padding: 5px; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
				          <td style="padding: 5px; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				       <td style="padding: 5px; text-align: right; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				       <td style="padding: 5px; text-align: right; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				          <td style="padding: 5px; text-align: right; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				          <td style="padding: 5px; text-align: right; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_amount">&nbsp;</td>
				        </tr>
				        <tr style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);">
				          <td style="padding: 5px; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
				          <td style="padding: 5px; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				       <td style="padding: 5px; text-align: right; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				       <td style="padding: 5px; text-align: right; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				          <td style="padding: 5px; text-align: right; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				          <td style="padding: 5px; text-align: right; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_amount">&nbsp;</td>
				        </tr>
				      </tbody>
				    </table>
				  </div>
				  <br style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);">
				  <div class="total_container content_container" style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);">
				    <table class="total_table" style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" width="100%" align="right">
				      <tbody style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);">
				        <tr style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);">
				          <td style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" width="25%"></td>
				          <td style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" width="25%"></td>
				          <td style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);">Sub-Total</td>
				          <td class="invoice_sub_total" style="text-align: right; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);">0.00</td>
				        </tr>
				        <tr style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);">
				          <td style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" width="25%"></td>
				          <td style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" width="25%"></td>
				          <td class="tax_name" style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);">Tax Amount</td>
				          <td class="tax_value" style="text-align: right; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);">0.00</td>
				        </tr>
				        <tr style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);">
				          <td style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" width="25%"></td>
				          <td style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" width="25%"></td>
				          <td style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);">Total</td>
				          <td class="invoice_total_amount" style="text-align: right; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);">0.00</td>
				        </tr>
				      </tbody>
				    </table>
				  </div>
				<div style="position: relative; float: left; width: 100%; height: 200px; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" class="footer_container content_container">
				    
				<div style="width: auto; float: left; font-family: &quot;MS Serif&quot;, serif; z-index: 1; position: absolute; top: -0.0000318164px; left: 0.0000292188px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div class="text_result" style="color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;MS Serif&quot;, serif;">Desclaimer:<br style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);">Above list of items and figures are printed as received. For any discrepancies please contact us within 14 days of receiving this.  </div><div class="remove" style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div>
				</div>';
	}
	else if($template_type->name == "B2B_TaxPercentage_Nodisc_Common")
	{
		 	$data = '<style>
					.workspace
					{
					    display: block;
					    page-break-inside: avoid;
					  page-break-before: avoid;
					  page-break-after: avoid;
					    -webkit-region-break-inside: avoid; 
					}
					</style>
					<div data-type="portrait" style="background: rgb(255, 255, 255);" class="workspace">
					<div class="invoice_print" style="border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					  <div style="position: relative; min-height: 300px; height: 302px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="header_container">

					  <div style="float: left; position: absolute; top: 143px; left: -0.0000318164px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle">
					 <hr style="border: 1px solid black; width: 700px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					  <div class="remove" style="display: none; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><i class="fa fa-times"></i></div></div>
					  <div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 214.233px; left: 4.23331px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Customer : </div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="customer_vendor">Customer / Vendor</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 237.233px; left: 11.2333px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Address :</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="customer_address">Customer Address</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 257.35px; left: 10.3499px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">GST no : </div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="customer_gst">Customer GST</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 164.117px; left: 9.11667px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Vehicle :</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="vehicle_number">Vehicle Number</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 189.35px; left: 9.34994px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Modal : </div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="make_model_variant">Make-Model-variant</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 162.583px; left: 478.583px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Voucher # :</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="purchase">Purchase</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 186.7px; left: 502.7px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Dated :</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="date">Date</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: -0.533342px; left: 232.467px;" class="draggable ui-draggable ui-draggable-handle"></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 40.4667px; left: 214.467px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_address">My company Address</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 66.35px; left: 201.35px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Phone : </div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_phone">My company Phone</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 66.35px; left: 368.35px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">GSTN : </div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_gst">Company GST</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 112.467px; left: 254.467px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(0, 0, 0); font-size: 20px; font-family: &quot;Times New Roman&quot;, Times, serif; font-weight: bold;" class="value_result" data-value="voucher_type">Voucher Type</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: -2.53334px; left: 209.467px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(0, 0, 0); font-size: 26px; font-family: &quot;Times New Roman&quot;, Times, serif; font-weight: bold;" class="value_result" data-value="company_name">My company Name</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div></div>
					<div style="position: relative; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="body_container">
					<table style="width: 100%; border: 1px solid black; border-collapse: collapse; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="invoice_item_table">
					<thead style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					    <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_id">Sl.No</th>
					      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">PARTICULARS</th>
					      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">HSN/SAC</th>
					      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">QTY</th>
					      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">RATE</th>
					      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_tax">Tax %</th>
					      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">AMOUNT</th>
					    </tr>
					</thead>
					<tbody style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					     <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_tax">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
					   </tr>
					     <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_tax">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
					   </tr>
					        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_tax">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
					   </tr>
					        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_tax">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
					   </tr>
					        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_tax">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
					   </tr>
					        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_tax">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
					   </tr>
					        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_tax">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
					   </tr>
					        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
					   </tr>
					        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_tax">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
					   </tr>
					        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_tax">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
					   </tr>
					   </tbody><tfoot style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					   <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					   <td class="col_id" colspan="3" style="border-top: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">E &amp; OE</td>
					      <td style="border-top: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
					      <td style="border-top: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
					      <td style="border-top: 1px solid black; border-right: 1px solid black; border-left: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">Total:<span class="value_result" data-value="total_discount" style="font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif; color: rgb(0, 0, 0);">000.00</span></td>
					      <td style="border-top: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">Total:<span class="value_result" data-value="total_amount" style="font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif; color: rgb(0, 0, 0);">000.00</span></td>
					   </tr>
					   </tfoot>
					  
					</table>
					<table style="width: 100%; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<tbody style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><td style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<table style="width: 80%; border-collapse: collapse; border: 1px solid black; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="floatedTable">
					<thead style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_gst">GST%</td>
					<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_tax_value">Taxable</td>
					<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst">IGST%</td>
					<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst_amount">IGST Amt</td>
					<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst">CGST%</td>
					<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst_amount">CGST Amt</td>
					<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst">SGST%</td>
					<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst_amount">SGST Amt</td>
					</tr>
					</thead>
					<tbody style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_gst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_tax_value">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst_amount">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst_amount">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst_amount">&nbsp;</td>
					</tr>
					<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_gst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_tax_value">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst_amount">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst_amount">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst_amount">&nbsp;</td>
					</tr>
					<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_gst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_tax_value">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst_amount">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst_amount">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst_amount">&nbsp;</td>
					</tr>
					<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_gst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_tax_value">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst_amount">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst_amount">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst_amount">&nbsp;</td>
					</tr>
					<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_gst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_tax_value">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst_amount">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst_amount">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst_amount">&nbsp;</td>
					</tr>
					<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_gst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_tax_value">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst_amount">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst_amount">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst_amount">&nbsp;</td>
					</tr>
					</tbody>
					<tfoot style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<td colspan="8" style="border-top: 1px solid black; padding-bottom: 25px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">Rupees: <i class="value_result" data-value="rupees" style="font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif; color: rgb(0, 0, 0);">Four Thousand Only</i></td>
					</tr>
					</tfoot>
					</table>
					<table style="width: 20%; float: left; border-color: black; border-style: solid; border-width: 1px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="ft">
					<tbody style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<td style="padding-top: 5px; padding-bottom: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">CGST</td>
					<td style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><span class="value_result" data-value="total_cgst" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); float: right;">000.00</span></td>
					</tr>
					<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<td style="padding-top: 5px; padding-bottom: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">SGST</td>
					<td style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><span class="value_result" data-value="total_sgst" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); float: right;">00.00</span></td>
					</tr>
					<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<td style="padding-top: 5px; padding-bottom: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">IGST</td>
					<td style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><span class="value_result" data-value="total_igst" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); float: right;">000.00</span></td>
					</tr>
					<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<td style="padding-top: 5px; padding-bottom: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">Round off</td>
					<td style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><span class="value_result" data-value="round_off" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); float: right;">000.00</span></td>
					</tr>
					<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<td style="padding-top: 5px; padding-bottom: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
					<td style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
					</tr>
					<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<td style="padding-top: 5px; padding-bottom: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
					<td style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
					</tr>
					<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<td style="padding-top: 20px; padding-bottom: 38px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">TOTAL:</td>
					<td style="text-align: right; padding-top: 20px; padding-bottom: 38px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><span class="value_result" data-value="total_amountwithtax" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); float: right;">000.00</span></td>
					</tr>
					</tbody></table>
					</td>
					</tr></tbody></table>
					</div>
					
					 	
					  </div>
					   <div style="position: relative; font-family: &quot;Times New Roman&quot;, Times, serif; color: rgb(0, 0, 0); width: 100%; font-size: 14px;" class="footer_container content_container">
					  Disclimer:
					  </div>

					 
					   
				</div>
				';
	}
	else{
			$data = '';
		}

	//dd($data);
	$template->data = $data;
	$template->original_data = $data;
	$template->organization_id = Session::get('organization_id');
	$template->print_template_type_id = $request->input('template_type');
	$template->save();

	Custom::add_addon('records');

	return response()->json(['status' => 1, 'message' => 'Template'.config('constants.flash.added'), 'data' => ['id' => $template->id, 'name' => $template->name, 'status' => $template->status]]);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
	$template = PrintTemplate::findOrFail($id);
	return $template->data;
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
	$template = PrintTemplate::select('print_templates.id', 'print_templates.display_name AS name', 'print_templates.data', 'print_templates.status', 'print_templates.delete_status', 'print_template_types.name AS print_template')->leftjoin('print_template_types', 'print_template_types.id', '=', 'print_templates.print_template_type_id')->where('print_templates.id', $id)->first();

	if($template->print_template == "sale") {
	  $values = ["company_name"=>"My company Name","company_address"=>"My company Address","assigned_to"=>"Work By","po" => "JC Number","vehicle_number"=>"Vehicle","customer_vendor" => "Customer","customer_mobile" => "Customer Mobile Number","driver"=>"Driver","driver_mobile_no" => "Driver Mobile Number","warranty" => "Warranty KM","insurance"=>"Insurance","mileage" => "Mileage","make_model_variant" =>"Make-Model-variant","engine_no" =>"Engine Number","chassis_no" => "Chassis Number","specification" => "Specifications","job_due_on"=>"Job Due On","last_visit_on"=>"Last Visit On","next_visit_on"=>"Next Visit On","service_on"=>"Service On","last_visit_jc"=>"Last Visit JC"];
	} else if($template->print_template == "payslip") {
	  $values = ["employee" => "Employee Name", "designation" => "Designation", "department" => "Department", "employee_id" => "Employee ID", "date" => "Date", "salary_month_year" => "Salary Month - Year", "net_pay" => "Net Pay", "net_pay_words" => "Net Pay in Words", "total_earnings" => "Total Earnings", "total_deductions" => "Total Deductions"];
	} else if($template->print_template == "wms_receipt") {
	  $values = ["date" => "Date","company_name" => "My Company Name", "company_address" => "My Company Address", "city" => "My Company City", "pin" => "My Company Pin","mobile_no" => "My Company Mobile Number","company_email_id" => "My Company Mail Id","customer_name" => "Customer Name","customer_address" => "Customer Address","customer_mobile_no" => "Customer Mobile Number","customer_email" => "Customer Email ID"];
	}else if($template->print_template == "B2C_OneLineTax_JobInvoice") {
	  $values = ["billing_name" => "Bill To","customer_communication_gst"=>"Customer Communication GST","billing_communication_gst"=>"Billing Communication GST","po" => "Purchase Order", "purchase" => "Purchase", "grn" => "Goods Receipt Note", "customer_vendor" => "Customer / Vendor", "date" => "Date", "payment_mode" => "Payment Mode", "resource_person" => "Resource Person","customer_address" => "Customer Address","billing_address" => "Billing Address", "shipping_address" => "Shipping Address", 'voucher_type' => 'Voucher Type',"vehicle_number" => "Vehicle Number","make_model_variant" =>"Make-Model-variant","company_name"=>"My company Name","company_address"=>"My company Address","company_phone"=>"My company Phone","company_gst"=>"Company GST","customer_gst"=>"Customer GST","km"=>"Mileage","assigned_to"=>"Mechannic Name","customer_mobile" => " Customer Mobile No"];
	}else if($template->print_template == "B2B_HSN_Invoice") {
	  $values = ["billing_name" => "Bill To","customer_communication_gst"=>"Customer Communication GST","billing_communication_gst"=>"Billing Communication GST","po" => "Purchase Order", "purchase" => "Purchase", "grn" => "Goods Receipt Note", "customer_vendor" => "Customer / Vendor", "date" => "Date", "payment_mode" => "Payment Mode", "resource_person" => "Resource Person","customer_address" => "Customer Address","billing_address" => "Billing Address", "shipping_address" => "Shipping Address", 'voucher_type' => 'Voucher Type',"vehicle_number" => "Vehicle Number","make_model_variant" =>"Make-Model-variant","company_name"=>"My company Name","company_address"=>"My company Address","company_phone"=>"My company Phone","company_gst"=>"Company GST","customer_gst"=>"Customer GST","km"=>"Mileage","assigned_to"=>"Mechannic Name","customer_mobile" => " Customer Mobile No"];
	}else if($template->print_template == "B2C_NoTax_Sales"){
	  $values = ["billing_name" => "Bill To","customer_communication_gst"=>"Customer Communication GST","billing_communication_gst"=>"Billing Communication GST","customer_vendor" => "Customer / Vendor","customer_address" => "Customer Address","vehicle_number" => "Vehicle Number","estimate_no"=>"Estimation No","customer_gst"=>"Customer GST","date"=>"Estimation Date","payment_mode" => "Payment Mode", "resource_person" => "Resource Person","billing_address" => "Billing Address", "shipping_address" => "Shipping Address", 'voucher_type' => 'Voucher Type',"make_model_variant" =>"Make-Model-variant","company_name"=>"My company Name","company_address"=>"My company Address","company_phone"=>"My company Phone","company_gst"=>"Company GST","customer_gst"=>"Customer GST","km"=>"Mileage","assigned_to"=>"Mechannic Name","customer_mobile" => " Customer Mobile No"];
	}
	else if($template->print_template == "B2B_HSNbased_Invoice") {
	  $values = ["billing_name" => "Bill To","customer_communication_gst"=>"Customer Communication GST","billing_communication_gst"=>"Billing Communication GST","po" => "Purchase Order", "purchase" => "Purchase", "grn" => "Goods Receipt Note", "customer_vendor" => "Customer / Vendor", "date" => "Date", "payment_mode" => "Payment Mode", "resource_person" => "Resource Person","customer_address" => "Customer Address","billing_address" => "Billing Address", "shipping_address" => "Shipping Address", 'voucher_type' => 'Voucher Type',"vehicle_number" => "Vehicle Number","make_model_variant" =>"Make-Model-variant","company_name"=>"My company Name","company_address"=>"My company Address","company_phone"=>"My company Phone","company_gst"=>"Company GST","customer_gst"=>"Customer GST","km"=>"Mileage","assigned_to"=>"Mechannic Name","customer_mobile" => " Customer Mobile No"];
	}
	else if($template->print_template == "B2B_HSNbased_JobEstimation") {
	  $values = ["billing_name" => "Bill To","customer_communication_gst"=>"Customer Communication GST","billing_communication_gst"=>"Billing Communication GST","po" => "Purchase Order", "purchase" => "Purchase", "grn" => "Goods Receipt Note", "customer_vendor" => "Customer / Vendor", "date" => "Date", "payment_mode" => "Payment Mode", "resource_person" => "Resource Person","customer_address" => "Customer Address","billing_address" => "Billing Address", "shipping_address" => "Shipping Address", 'voucher_type' => 'Voucher Type',"vehicle_number" => "Vehicle Number","make_model_variant" =>"Make-Model-variant","company_name"=>"My company Name","company_address"=>"My company Address","company_phone"=>"My company Phone","company_gst"=>"Company GST","customer_gst"=>"Customer GST","km"=>"Mileage","assigned_to"=>"Mechannic Name","customer_mobile" => " Customer Mobile No"];
	}
	else if($template->print_template == "B2B_TaxPercentage_Invoice") {
	  $values = ["billing_name" => "Bill To","customer_communication_gst"=>"Customer Communication GST","billing_communication_gst"=>"Billing Communication GST","po" => "Purchase Order", "purchase" => "Purchase", "grn" => "Goods Receipt Note", "customer_vendor" => "Customer / Vendor", "date" => "Date", "payment_mode" => "Payment Mode", "resource_person" => "Resource Person","customer_address" => "Customer Address","billing_address" => "Billing Address", "shipping_address" => "Shipping Address", 'voucher_type' => 'Voucher Type',"vehicle_number" => "Vehicle Number","make_model_variant" =>"Make-Model-variant","company_name"=>"My company Name","company_address"=>"My company Address","company_phone"=>"My company Phone","company_gst"=>"Company GST","customer_gst"=>"Customer GST","km"=>"Mileage","assigned_to"=>"Mechannic Name","customer_mobile" => " Customer Mobile No"];
	}
	else if($template->print_template == "B2B_TaxPercentage_JobEstimation") {
	  $values = ["billing_name" => "Bill To","customer_communication_gst"=>"Customer Communication GST","billing_communication_gst"=>"Billing Communication GST","po" => "Purchase Order", "purchase" => "Purchase", "grn" => "Goods Receipt Note", "customer_vendor" => "Customer / Vendor", "date" => "Date", "payment_mode" => "Payment Mode", "resource_person" => "Resource Person","customer_address" => "Customer Address","billing_address" => "Billing Address", "shipping_address" => "Shipping Address", 'voucher_type' => 'Voucher Type',"vehicle_number" => "Vehicle Number","make_model_variant" =>"Make-Model-variant","company_name"=>"My company Name","company_address"=>"My company Address","company_phone"=>"My company Phone","company_gst"=>"Company GST","customer_gst"=>"Customer GST","km"=>"Mileage","assigned_to"=>"Mechannic Name","customer_mobile" => " Customer Mobile No"];
	}
	else if($template->print_template == "B2C_NoTax_JobEstimation") {
	  $values = ["billing_name" => "Bill To","customer_communication_gst"=>"Customer Communication GST","billing_communication_gst"=>"Billing Communication GST","po" => "Purchase Order", "purchase" => "Purchase", "grn" => "Goods Receipt Note", "customer_vendor" => "Customer / Vendor", "date" => "Date", "payment_mode" => "Payment Mode", "resource_person" => "Resource Person","customer_address" => "Customer Address","billing_address" => "Billing Address", "shipping_address" => "Shipping Address", 'voucher_type' => 'Voucher Type',"vehicle_number" => "Vehicle Number","make_model_variant" =>"Make-Model-variant","company_name"=>"My company Name","company_address"=>"My company Address","company_phone"=>"My company Phone","company_gst"=>"Company GST","customer_gst"=>"Customer GST","km"=>"Mileage","assigned_to"=>"Mechannic Name","customer_mobile" => " Customer Mobile No"];
	}
	else if($template->print_template == "B2C_NoTax_JobInvoice") {
	  $values = ["billing_name" => "Bill To","customer_communication_gst"=>"Customer Communication GST","billing_communication_gst"=>"Billing Communication GST","po" => "Purchase Order", "purchase" => "Purchase", "grn" => "Goods Receipt Note", "customer_vendor" => "Customer / Vendor", "date" => "Date", "payment_mode" => "Payment Mode", "resource_person" => "Resource Person","customer_address" => "Customer Address","billing_address" => "Billing Address", "shipping_address" => "Shipping Address", 'voucher_type' => 'Voucher Type',"vehicle_number" => "Vehicle Number","make_model_variant" =>"Make-Model-variant","company_name"=>"My company Name","company_address"=>"My company Address","company_phone"=>"My company Phone","company_gst"=>"Company GST","customer_gst"=>"Customer GST","km"=>"Mileage","assigned_to"=>"Mechannic Name","customer_mobile" => " Customer Mobile No"];
	}
	else if($template->print_template == "B2C_OneLine_Tax_Invoice") {
	  $values = ["billing_name" => "Bill To","customer_communication_gst"=>"Customer Communication GST","billing_communication_gst"=>"Billing Communication GST","po" => "Purchase Order", "purchase" => "Purchase", "grn" => "Goods Receipt Note", "customer_vendor" => "Customer / Vendor", "date" => "Date", "payment_mode" => "Payment Mode", "resource_person" => "Resource Person","customer_address" => "Customer Address","billing_address" => "Billing Address", "shipping_address" => "Shipping Address", 'voucher_type' => 'Voucher Type',"vehicle_number" => "Vehicle Number","make_model_variant" =>"Make-Model-variant","company_name"=>"My company Name","company_address"=>"My company Address","company_phone"=>"My company Phone","company_gst"=>"Company GST","customer_gst"=>"Customer GST","km"=>"Mileage","assigned_to"=>"Mechannic Name","customer_mobile" => " Customer Mobile No"];
	}
	else if($template->print_template == "B2C_OneLineTax_Job_Estimation") {
	  $values = ["billing_name" => "Bill To","customer_communication_gst"=>"Customer Communication GST","billing_communication_gst"=>"Billing Communication GST","po" => "Purchase Order", "purchase" => "Purchase", "grn" => "Goods Receipt Note", "customer_vendor" => "Customer / Vendor", "date" => "Date", "payment_mode" => "Payment Mode", "resource_person" => "Resource Person","customer_address" => "Customer Address","billing_address" => "Billing Address", "shipping_address" => "Shipping Address", 'voucher_type' => 'Voucher Type',"vehicle_number" => "Vehicle Number","make_model_variant" =>"Make-Model-variant","company_name"=>"My company Name","company_address"=>"My company Address","company_phone"=>"My company Phone","company_gst"=>"Company GST","customer_gst"=>"Customer GST","km"=>"Mileage","assigned_to"=>"Mechannic Name","customer_mobile" => " Customer Mobile No"];
	}
	else if($template->print_template == "Payslip_Print") {
	  $values = ["billing_name" => "Bill To","customer_communication_gst"=>"Customer Communication GST","billing_communication_gst"=>"Billing Communication GST","po" => "Purchase Order", "purchase" => "Purchase", "grn" => "Goods Receipt Note", "customer_vendor" => "Customer / Vendor", "date" => "Date", "payment_mode" => "Payment Mode", "resource_person" => "Resource Person","customer_address" => "Customer Address","billing_address" => "Billing Address", "shipping_address" => "Shipping Address", 'voucher_type' => 'Voucher Type',"vehicle_number" => "Vehicle Number","make_model_variant" =>"Make-Model-variant","company_name"=>"My company Name","company_address"=>"My company Address","company_phone"=>"My company Phone","company_gst"=>"Company GST","customer_gst"=>"Customer GST","km"=>"Mileage","assigned_to"=>"Mechannic Name","customer_mobile" => " Customer Mobile No"];
	}
	else if($template->print_template == "PO_Purchase_GRN") {
	  $values = ["billing_name" => "Bill To","customer_communication_gst"=>"Customer Communication GST","billing_communication_gst"=>"Billing Communication GST","po" => "Purchase Order", "purchase" => "Purchase", "grn" => "Goods Receipt Note", "customer_vendor" => "Customer / Vendor", "date" => "Date", "payment_mode" => "Payment Mode", "resource_person" => "Resource Person","customer_address" => "Customer Address","billing_address" => "Billing Address", "shipping_address" => "Shipping Address", 'voucher_type' => 'Voucher Type',"vehicle_number" => "Vehicle Number","make_model_variant" =>"Make-Model-variant","company_name"=>"My company Name","company_address"=>"My company Address","company_phone"=>"My company Phone","company_gst"=>"Company GST","customer_gst"=>"Customer GST","km"=>"Mileage","assigned_to"=>"Mechannic Name"];
	}
	else {
	  $values = [ "date" => "Date"];
	}
		 //dd($template);
	return view('settings.print_edit', compact('id', 'template', 'values'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
	$template = PrintTemplate::findOrFail($id);
	$template->name = $request->input('name');
	$template->display_name = $request->input('name');
	$template->data = $request->input('data');
	//dd($template->data);
	$template->save();

	 return response()->json(array('status' => 'Successfully completed!'));
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy(Request $request)
  {
	$template = PrintTemplate::findOrFail($request->id);

	$template->delete();

	Custom::delete_addon('records');

	return response()->json(['status' => 1, 'message' => 'Template'.config('constants.flash.deleted'), 'data' => []]);
  }

  public function store_image(Request $request)
  {
	$this->validate($request, [
	  'image' => 'required'
	]);

	$business_id = Organization::findOrFail(Session::get('organization_id'))->business_id;
	$business_name = Business::findOrFail($business_id)->business_name;

	$dt = new DateTime();
	
	$image = $request->file('image');

	$name = $dt->format('Y-m-d-H-i-s').".".$image->getClientOriginalExtension();

	Custom::image_resize($image, '300', $name, 'organizations/'.$business_name.'/print');

	return url('public/organizations/'.$business_name.'/print').'/'.$name;
  }

  public function remove_image(Request $request)
  {
	$this->validate($request, [
	  'image' => 'required'
	]);

	$path = explode('/', $request->image);

	$end = end($path);

	$business_id = Organization::findOrFail(Session::get('organization_id'))->business_id;
	$business_name = Business::findOrFail($business_id)->business_name;

	unlink(public_path('organizations/'.$business_name.'/print').'/'.$end);
  }
}

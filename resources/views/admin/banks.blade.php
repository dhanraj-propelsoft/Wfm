@extends('layouts.master')
@section('head_links') @parent
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
@stop
@include('includes.admin')
@section('content')

<div class="alert alert-success">
	{{ Session::get('flash_message') }}
</div>

@if($errors->any())
	<div class="alert alert-danger">
		@foreach($errors->all() as $error)
			<p>{{ $error }}</p>
		@endforeach
	</div>
@endif









<div class="fill header">
  <h4 class=" page-title">Banks</h4>
</div>


<div class="row">
				<div class="col-md-12">
					<div class="tabbable-line boxless tabbable-reversed">

						
					<!-- BEGIN EXAMPLE TABLE PORTLET-->
					<div class="portlet box blue">

						<div class="portlet-body">
							<div class="table-toolbar">
<div>

<h4 style="padding:15px;" class="pull-right text-primary">Last edited on: {{$last_edited_date}}</h4>

<a class="pull-left" href="https://www.rbi.org.in/scripts/neft.aspx" target="_blank">www.rbi.org.in/scripts/neft.aspx</a><br><br>

<span class="pull-left">If the banks were updated, we need to update our system too. Follow the steps to update the bank list</span>
<br><br>
<ul class="pull-left">
<li>Check the above link for any new update in the bank list.</li>
<li>Find the List of NEFT enabled bank branches (Consolidated IFS Codes) date. If the date is greater than our last edited date. Then click the link to download the excel. </li>
<li>Then Browse the excel sheet and upload it in our site.</li>
</ul>

									<div class="col-md-12">
<a class="btn btn-info pull-left upload_bank">Upload Banks</a>



{!! Form::open([
											'route' => 'banks.store',
											'class' => 'validateform banks',
											'style' => 'display:none',
											'files' => true
										]) !!}

										{{ csrf_field() }}
										<div class="form-body">
												
												<div class="row">
													<div class="col-md-6">

														{!! Form::label('bank', 'Upload Bank Excel', ['class' => 'control-label']) !!}
														{!! Form::label('', '*', ['class' => 'control-label text-danger']) !!}
														{!! Form::file('bank', ['class' => 'form-control']) !!}
														
</div>

													<div class="col-md-6">
														<button style="margin-top:26px;" type="submit" class="btn blue"><i class="fa fa-check"></i> Upload</button>
														
</div>


</div>
<br><br>
</div>


{!! Form::close() !!}









									</div>
								</div>
							</div>
							<table class="table table-striped table-hover table-bordered" id="sample_editable_1">
							<thead>
							<tr>
								<th>
									 S.No
								</th>
								<th>
									Bank Name
								</th>
								<th>
									IFSC Code
								</th>
								<th>
									MICR Code
								</th>
								<th>
									Branch
								</th>
								<th>
									Address
								</th>
								<th>
									Contact
								</th>
								<th>
									City
								</th>
								<th>
									District
								</th>
								<th>
									State
								</th>
							</tr>
							</thead>
							<tbody>
							
						<?php if(isset($_GET['page'])) { $i = ($_GET['page'] * 10) - 9; } else { $i=1; } ?>	
							@foreach($banks as $bank)
							
							
<tr>
								<td>
									{{$i}}
								</td>
								<td>
									{{ $bank->bank }}
								</td>
								<td>
									{{ $bank->ifsc }}
								</td>
								<td>
									{{ $bank->micr }}	
								</td>
								<td>
									{{ $bank->branch }}
								</td>
								<td>
									{{ $bank->address }}
								</td>
								<td>
									{{ $bank->contact }}
								</td>
								<td>
									{{ $bank->city}}
								</td>
								<td>
									{{ $bank->district }}
								</td>
								<td>
									{{ $bank->state }}
								</td>
</tr>
<?php $i++; ?>     
	@endforeach						
                                
							
						
					
						
						
							</tbody>
							</table>
						
						<div class="pagination"> {!! $banks->render() !!} </div>
						
						</div>
					</div>
					<!-- END EXAMPLE TABLE PORTLET-->
				</div>
			</div>
			



@stop
@section('dom_links')
@parent
<script>
$(document).ready(function() {
	$('.upload_bank').on('click', function() {
		$('.banks').show();
		$(this).hide();
	});
});
</script>
@stop
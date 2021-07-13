<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ URL::asset('') }}favicon.ico" type="image/x-icon">
    <link rel="icon" href="{{ URL::asset('assets/layout/images/fav_icon.png') }}" type="image/x-icon">
    <meta content="" name="description"/>
    <meta content="" name="author"/>
    <noscript>
        <meta http-equiv="refresh" content="0; URL={{url('script')}}">
    </noscript>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @section('head_links')
        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Styles -->
        @if(app()->environment() == "production")
            <link rel="stylesheet" type="text/css"
                  href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/css/bootstrap.min.css">
            <link rel="stylesheet" type="text/css"
                  href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
            <link rel="stylesheet" type="text/css"
                  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
            <link rel="stylesheet" type="text/css"
                  href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css">
            <link rel="stylesheet" type="text/css"
                  href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.0/css/bootstrap-datepicker.min.css"/>
            <link rel="stylesheet" type="text/css"
                  href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/css/bootstrap-timepicker.min.css"/>
            <link rel="stylesheet" type="text/css"
                  href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.2/css/bootstrap2/bootstrap-switch.min.css"/>
        @elseif(app()->environment() == "local")
            <link rel="stylesheet" type="text/css"
                  href="{{ URL::asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">
            <link rel="stylesheet" type="text/css"
                  href="{{ URL::asset('assets/plugins/jquery-ui/jquery-ui.min.css') }}">
            <link rel="stylesheet" type="text/css"
                  href="{{ URL::asset('assets/plugins/font-awesome-4.7.0/css/font-awesome.min.css') }}">
            <link rel="stylesheet" type="text/css"
                  href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}">
            <link rel="stylesheet" type="text/css"
                  href="{{ URL::asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}"/>
            <link rel="stylesheet" type="text/css"
                  href="{{ URL::asset('assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}"/>
            <link rel="stylesheet" type="text/css"
                  href="{{ URL::asset('assets/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}"/>

        @endif

        <link rel="stylesheet" type="text/css"
              href="{{ URL::asset('assets/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }}"/>



        <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/layout/css/theme.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/layout/css/linecon.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/layout/css/background.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/layout/css/stylesheet.css') }}">
        <link rel="stylesheet" type="text/css"
              href="{{ URL::asset('assets/plugins/boostrap-tags/bootstrap-tagsinput.css') }}">
        <link href="{{ URL::asset('assets/plugins/bootsrap-tagit/css/jquery.tagit.css') }}" rel="stylesheet"
              type="text/css">
        <link href="{{ URL::asset('assets/plugins/bootsrap-tagit/css/tagit.ui-zendesk.css') }}" rel="stylesheet"
              type="text/css">
        <style type="text/css">


            .wrapper-inner-tab-backgrounds-second {
                float: left;
                height: 300px;
                width: 33.33%;
                background-color: #5e7c87;
            }

            table.dataTable tbody > tr.selected, table.dataTable tbody > tr > .selected {
                background-color: #ccc !important;
            }

            .button8 {
                color: #4b5056;
                /*color: rgba(255,255,255,1);*/
                -webkit-transition: all 0.5s;
                -moz-transition: all 0.5s;
                -o-transition: all 0.5s;
                transition: all 0.5s;
                border: 1px solid rgba(255, 255, 255, 0.5);
                position: relative;
            }

            .save_search, input[name=save_search] {
                display: none;
            }

            .button8 a {
                color: #4b5056;
                /*color: rgba(51,51,51,1);*/
                text-decoration: none;
                display: block;
            }

            .button8 span {
                z-index: 2;
                display: block;
                position: absolute;
                width: 100%;
                height: 100%;
            }

            .button8::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 0%;
                height: 100%;
                z-index: 1;
                opacity: 0;
                color: #4b5056;
                background-color: #0cc285;
                -webkit-transition: all 0.3s;
                -moz-transition: all 0.3s;
                -o-transition: all 0.3s;
                transition: all 0.3s;

            }

            .button8:hover::before {
                opacity: 1;
                width: 100%;
            }

            .button-4 {
                width: 100px;
                height: 30px;
                border: 2px solid #34495e;
                float: left;
                text-align: center;
                cursor: pointer;
                position: relative;
                box-sizing: border-box;
                overflow: hidden;
                /*margin:13px 0 2% 8.5%;*/
                border-radius: 2px;
                margin-right: 10px;
            }

            .button-4 a {
                font-size: 9px;;
                color: #34495e;
                text-decoration: none;
                line-height: 27px;
                transition: all .5s ease;
                z-index: 2;
                position: relative;
                text-transform: uppercase;
            }

            .eff-4 {
                width: 100px;
                height: 30px;
                left: -140px;
                background: #34495e;
                position: absolute;
                transition: all .5s ease;
                z-index: 1;
            }

            .button-4:hover .eff-4 {
                left: 0;
            }

            .button-4:hover a {
                color: #fff;
            }

            .menu_effect_add {
                border-bottom: 1px solid #247abd;
                color: #247abd;
            }

            .get_qucikfilter {
                margin: 5px 12px 1px 5px;
                z-index: 2;

                cursor: pointer;
            }

            .close_btn_position {
                position: absolute;
                right: 0;
                cursor: pointer;
            }

            .Filter_menu {

                font-size: 85%
            }

            .advance_filter_options td {
                padding: 2px;
                /*font-weight: bold;*/
            }

            .advance_filter_options td input[type=text] {
                line-height: 0;
                padding: 0;
            }

            .spliter {

                border-right: 1px solid #aaa;
            }

            input[type=checkbox] {
                display: block;
            }

            .table_outer {
                padding-left: 3%;
                padding-right: 1%;
            }

            .table_inner {
                padding-left: 1%;
                padding-right: 1%;
            }

            .table_title {
                font-weight: bold;
                text-transform: uppercase;
            }

            .get_detailsbar {
                cursor: pointer;
            }

            .box_icon {
                background: #fafcfe;
                padding: 2% 4%;
                min-width: 75px;

                width: 10%;
            }

            .box_title {
                color: #aaa;
                font-weight: bold;
                padding-left: 2px;
                margin: auto auto auto 0;
            }

            .bar-header {

                background-color: #e9ecef;
            }

            .td_formData {
                padding: 2%;
            }

            .btn_round {
                float: left;
                border: 2px solid #247abd;
                color: #3e4855;
                border-radius: 100%;
                text-align: center;
                text-transform: uppercase;
                font-size: 11px;
                line-height: 27px;
                font-weight: 700;
                width: 29px;
                height: 30px;
                border: 2px solid #004085;
                border-radius: 100%;
                position: absolute;
                overflow: visible;
                width: 30px;
                height: 30px
            }

            .icon {
                vertical-align: middle;
                color: #004085;
                font-size: 19px;
                line-height: 24px;
            }

            .md-open-menu-container {
                position: fixed;
                left: 0;
                top: 0;
                z-index: 100;
                opacity: 0;
                border-radius: 2px;
            }

            .md-open-menu-container.md-active {
                opacity: 1;
                -webkit-transition: all .4s cubic-bezier(.25, .8, .25, 1);
                transition: all .4s cubic-bezier(.25, .8, .25, 1);
                -webkit-transition-duration: .2s;
                transition-duration: .2s;
                background-color: rgb(255, 255, 255);
                padding: 1%
            }

            .md-whiteframe-z2 {
                box-shadow: 0 2px 4px -1px rgba(0, 0, 0, .2), 0 4px 5px 0 rgba(0, 0, 0, .14), 0 1px 10px 0 rgba(0, 0, 0, .12);
            }

            md-menu-content {
                border-top: 4px solid #237abd;
            }

            @media (min-width: 960px) {
                md-menu-content {

                    min-width: 96px;
                }

            }

            md-menu-content {
                border-top: 4px solid #237abd;
            }

            md-menu-content {
                background-color: rgb(255, 255, 255);
            }

            .userddn-a-menu {
                padding: 0;
                width: 240px;
                overflow: hidden;
            }

            .TaskToggle, .get_AdFilter {
                cursor: pointer;
            }

            .input_icon {
                position: absolute;
                top: 0;
                margin: 1% 1%;
                color: #999;
            }

            .input_icon_fixed {

                top: 24px;
                /*    margin: 1% 1%;
                */
                color: #999;
                padding: 5px 6px 4px 5px;
                /* border-color: black; */
                border: 1px solid #ddd;
                left: 2px;
            }

            .follower_popup {
                min-width: 190px;
                width: auto;
                background: #fff;
                position: absolute;
                z-index: 99;

                box-shadow: 0 0 5px #ddd;
                border: 1px solid #ccc;
                border-radius: 3px;
                padding: 5px;
                top: 29px;

            }

            .add_follower {
                cursor: pointer;
            }

            .follower_div {
                float: left;
                width: 100%;
                margin: 3%;
            }

            .follower_div .user_email {
                display: inline-block;
                vertical-align: middle;
                max-width: calc(100% - 45px);
                color: #616161;
                font-size: 11px;
                margin: 0 0 0 5px;
                white-space: pre;
                overflow: hidden;
                text-overflow: ellipsis;
                margin: auto auto;
            }

            .follower_popup {
                min-width: 190px;
                width: auto;
                background: #fff;
                position: absolute;
                z-index: 99;

                box-shadow: 0 0 5px #ddd;
                border: 1px solid #ccc;
                border-radius: 3px;
                padding: 5px;
                top: 29px;

            }

            .save_popup {
                min-width: 190px;
                width: auto;
                background: #fff;
                position: absolute;
                z-index: 99;

                box-shadow: 0 0 5px #ddd;
                border: 1px solid #ccc;
                border-radius: 3px;
                padding: 5px;
                top: 29px;

            }

            .add_save {
                cursor: pointer;
            }

            .save_div {
                float: left;
                width: 100%;
                margin: 3%;
            }

            .save_div .user_email {
                display: inline-block;
                vertical-align: middle;
                max-width: calc(100% - 45px);
                color: #616161;
                font-size: 11px;
                margin: 0 0 0 5px;
                white-space: pre;
                overflow: hidden;
                text-overflow: ellipsis;
                margin: auto auto;
            }

            .input_box_hidden {
                margin-left: -5px;
                background-color: white;
                color: #999;
                /* border: 0px solid; */
                /*  border-left: 1px;
                  border-right: 1px;
                  border-right: 1px;*/
            }

        
                 @IF (!strpos(Session::get('theme_sidebar'), 'bg'))
                        .select2-container--default .select2-selection--single .select2-selection__rendered {
                                   height: 29px;
                         }
                @else
                .select2-container--default .select2-selection--single .select2-selection__rendered {
                                   height: 29px;
                 color: rgba(255, 255, 255, .62); 
                         }


                @endif
         

            .select2-container, .select2-container--default .select2-selection--single {

                height: 29px;
            }

            .label-attachment {
                cursor: pointer;
                /* Style as you please, it will become the visible UI component. */
            }

            #upload-photo {
                opacity: 0;
                position: absolute;
                z-index: -1;
            }

            .circle:hover {
                background: #ffab60;
            }

            .circle {

                position: relative;
                border-radius: 50%;
                width: 50px;
                height: 50px;
                top: -15px;
                padding: 0;
                background: #ffc490;
                box-shadow: 1px 2px #ccc;
                cursor: pointer;
                z-index: 20;
            }

            .sub-circle {
                position: relative;
                border-radius: 50%;
                width: 50px;
                height: 50px;
                top: -15px;
                padding: 0;
                background: #0000;
                box-shadow: 1px 2px #757a7d;
                cursor: pointer;
                z-index: 20;
                border: 1px solid #ffab60;;
            }

            .add_icon {
                font-size: 2em;
                color: white;
                padding: 25% 25% 25% 28%;
            }

            .sub_add_icon {
                font-size: 2em;
                color: #ffab60;
                padding: 25% 25% 25% 28%
            }

            .data-label {

                position: absolute;
                top: -1px;
                text-align: left;
                background: rgba(0, 0, 0, .6);
                color: #fff;
                font-size: 9px;
                margin: 0 15px 0 0;
                padding: 6px 0 6px 9px;
                border-radius: 2px;
                overflow: hidden;
                width: 100px;
                right: 52px;
                opacity: 1;
                cursor: pointer;
            }

            .sub-circle:hover {

                background: #aaa3
            }

            .modal_align {
                margin: 0 auto;
            }

            .menu-wfm {
                border: 0;
                background-color: #ccc;
                float: right;
                cursor: pointer;
            }

            .content_para {
                margin: 3% auto 5% 0;
            }

            .priority_low {
                color: black;
            }

            .priority_normal {
                color: blue;
            }

            .priority_medium {
                color: green;
            }

            .priority_high {
                color: red;
            }

            table.dashboard th:nth-child(3),
            table.dashboard td:nth-child(3) {
                width: 30% !important;
                padding: 0 10% 0 10%;
            }

            .display_weeks {
                display: inline;
            }

            .weekDays-selector input {
                display: none !important;
            }

            .weekDays-selector input[type=checkbox] + label {
                display: inline-block;
                border-radius: 100px;
                background: #dddddd;
                height: 40px;
                width: 40px;
                margin-right: 3px;
                line-height: 40px;
                text-align: center;
                cursor: pointer;
            }

            .weekDays-selector input[type=checkbox]:checked + label {
                background: #FB8D00;
                color: #ffffff;
            }

            .label {
                background-color: #ffa85b;
                display: inline;
                padding: .2em .6em .3em;
                font-size: 75%;
                font-weight: 700;
                line-height: 1;
                color: #fff;
                text-align: center;
                white-space: nowrap;
                vertical-align: baseline;
                border-radius: .25em;
            }

            .search_btn {
                position: absolute;
                top: -28px;
                left: 463px;

            }

            .save_search_btn {
                position: absolute;
                top: -30px;
                left: 300px;

            }

            .input-group-addon {
                line-height: 1;
                background-color: transparent !important;
            }

            .row {
                margin-bottom: 15px;
            }

            element.style {
                width: 100%;

            }

            #specialColor {
                /*width: 20%;*/
                background-color: #ddd !important;
                text-align: center !important;
                padding-left: 1%;
                padding-right: 2%;
                font-family: 'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
                color: buttontext;

            }

            .input-group-addon {

                border: 0;
            }

            .input-group .form-control {

                border: 0;

            }

            .modal-body {

                padding: 7px;
            }

            .row {
                margin-bottom: 2px;
            }

            .form-group {
                margin-bottom: auto;
            }

            .modal-dialog {
                max-width: 600px !important;
            }

            input:focus ~ .floating-label,
            input:not(:focus):valid ~ .floating-label {
                top: 8px;
                bottom: 10px;
                left: 20px;
                /*font-size: 11px;*/
                opacity: 1;
            }

            .inputText {
                font-family: "Open Sans", "Helvetica Neue", Helvetica, Arial, sans-serif !important;
                font-size: 13px !important;
                padding-top: 20px;

                width: 100%;
                height: 50px;
            }

            .inputTextArea {
                width: 100%;
                height: 80px;
            }

            .floating-label {
                position: absolute;
                pointer-events: none;
                left: 20px;
                top: 18px;
                transition: 0.2s ease all;
            }

            ul.tagit {
                /* padding: 1px 5px; */
                overflow: auto;
                margin-left: inherit;
                margin-right: inherit;
                margin-bottom: inherit;
            }

            .ui-widget.ui-widget-content {
                border: 0px solid #c5c5c5;
            }

            .input_box_hidden {
                /* margin-left: -5px; */
                background-color: white;
                color: #999;
                /* border: 0px solid; */
            }

            select {
                text-transform: capitalize;
                color: #919191;
            }

            select.form-control:not([size]):not([multiple]), select.form-control:not([size]):not([multiple]) option {

                color: #919191;

            }

            .select2-container--default .select2-selection--multiple {
                background-color: white;
                border: 1px solid #aaaaaa54;
                border-radius: 4px;
                cursor: text;
            }

            select option {
                text-transform: capitalize
            }

            .page-item.active .page-link {
                background-color: #6c757d;;
                border-color: #666e76;

            }

            option:first-child {
                display: none;
            }

            /*loader css*/
            .lds-ellipsis {
                display: inline-block;
                position: relative;
                width: 64px;
                height: 64px;
            }

            .lds-ellipsis div {
                position: absolute;
                top: 27px;
                width: 11px;
                height: 11px;
                border-radius: 50%;
                background: #fff;
                animation-timing-function: cubic-bezier(0, 1, 1, 0);
            }

            .lds-ellipsis div:nth-child(1) {
                left: 6px;
                animation: lds-ellipsis1 0.6s infinite;
            }

            .lds-ellipsis div:nth-child(2) {
                left: 6px;
                animation: lds-ellipsis2 0.6s infinite;
            }

            .lds-ellipsis div:nth-child(3) {
                left: 26px;
                animation: lds-ellipsis2 0.6s infinite;
            }

            .lds-ellipsis div:nth-child(4) {
                left: 45px;
                animation: lds-ellipsis3 0.6s infinite;
            }

            @keyframes lds-ellipsis1 {
                0% {
                    transform: scale(0);
                }
                100% {
                    transform: scale(1);
                }
            }

            @keyframes lds-ellipsis3 {
                0% {
                    transform: scale(1);
                }
                100% {
                    transform: scale(0);
                }
            }

            @keyframes lds-ellipsis2 {
                0% {
                    transform: translate(0, 0);
                }
                100% {
                    transform: translate(19px, 0);
                }
            }

            /*loader css*/

            .sidemenu_scroll {
                height: 300px !important;
                overflow-y: scroll;
            }

            #select2-project_category_id-container, #select2-create_by-container {
                color: #919191;
            }
        </style>

    @show
</head>

<body>
@include('modals.crud_modal')
@include('modals.wfm_crud_modal')
@include('modals.wfm_project_crud_modal')
@include('modals.wfm_attachment_modal')
@include('modals.crud_full_modal')
@include('modals.print_modal')
@include('modals.confirm_delete_modal')
@include('modals.close_confirmation_modal')
@include('modals.error_modal')
@include('includes.loader')
<div id="page-wrapper">
    <div id="page-header" class="{{ Session::get('theme_header') }}">
        <div id="header-logo" class="logo-bg">
            <a href="{{ route('dashboard') }}" class="logo-content">
                <span class="logo"></span>
                @if(Session::get('business'))
                    <span class="company_name">{{ Session::get('business') }}</span>
                @else
                    <span class="company_name">PROPELSOFT</span>
                @endif

                @if(Session::get('bcrm_code'))
                    <span class="company_slogan">Business ID: {{ Session::get('bcrm_code') }}</span>
                @else
                    <span class="company_slogan">Accelerating Business Ahead</span>
                @endif
            </a>

            <a id="close-sidebar" class="sidebar-toggler" href="#" title="Close sidebar"><i
                        class="fa fa-angle-left"></i></a>
        </div>

        <!-- <div id="header-nav-left">
			<div class="user-account-btn dropdown"> 
				<a href="#" title="My Account" class="user-profile clearfix"> 
					<img src="{{ URL::to('/') }}/public/users/images/{{ App\Person::user_image(Auth::user()->person_id) }}" alt="Profile image" width="28"> <span>{{ Auth::user()->name }}</span> 
					<i class="fa fa-angle-down"></i> </a>
				<div class="drop-menu left float-left">
					<div class="box-sm">
						<div class="login-box clearfix">
							<div class="user-img"> <img src="{{ URL::to('/') }}/public/users/images/{{ App\Person::user_image(Auth::user()->person_id) }}" alt=""> </div>
							<div class="user-info"> <span> 
								<span style="color: #333; padding: 0;">{{ Auth::user()->name }}</span> -->

        <!-- <i>Administrator</i>  -->

        <!-- <i style="color: #666;">Propel-ID: {{ Session::get('crm_code') }}</i>
							</span> <a href="{{ route('person_profile.show', [Auth::user()->person_id]) }}">Edit profile</a> <a href="{{ route('companies.index') }}">Change Account</a> </div>
						</div>
						<div class="divider"></div>
						<div class="login-box clearfix">
							<p>Propel Soft is a Marketplace for all your Business Services supported by All-In-One Platform. Instant access to customer, vendor and employee information. </p>
							&copy; 2018,  <a href="http://www.propelsoft.in" target="_blank">PropelSoft</a>. All Rights Reserved.</div>
					</div>
				</div>
			</div>
		</div> -->

        <div id="header-nav-left">
            @if(Session::get('organization_id') && App\Custom::plan_renewal() != "")
                <span style="color: #ff2100; padding: 5px; font-weight: bold; margin-top: 15px; background: #ffff; border-radius: 5px; margin-left: 300px"
                      class="pull-left">
				{{ App\Custom::plan_renewal() }} <a style="color: #0e74b7;" href="{{route('plan')}}"> Subscribe </a>
			</span>
            @endif
        </div>
        <div id="header-nav-right">
            @if(Session::get('organization_id'))
                    <!-- <a href="#" title="Full Screen" id="full-screen" class="hdr-btn"><i class="fa fa-arrows-alt"></i></a>
			<a href="#" title="Chat" class="hdr-btn"><i class="fa fa-comments-o"></i></a> -->
            @endif
            @if(Session::get('organization_id'))
                @if (App\Custom::check_module_list())
                    <div class="dropdown"><a title="Menus" href="#"><i class="fa icon-arrows-squares"></i></a>

                        <div class="drop-menu right float-right">
                            <div class="box-sm menu-box-sm main-menu">
                                <ul>
                                    @if(Session::get('organization_id'))
                                        @if (App\Organization::checkModuleExists('books', Session::get('organization_id')))
                                            @permission('books')
                                            <li><a href="{{ route('books.dashboard') }}">
                                                    <img src="{{ URL::to('/') }}/public/package/1.jpg" width="70">
                                                    Books </a></li>
                                            @endpermission
                                        @endif
                                    @endif
                                    @if(Session::get('organization_id'))
                                        @if (App\Organization::checkModuleExists('hrm', Session::get('organization_id')))
                                            @permission('hrm')
                                            <li><a href="{{ route('hrm.dashboard') }}">
                                                    <img src="{{ URL::to('/') }}/public/package/2.jpg" width="70">
                                                    HRM </a></li>
                                            @endpermission
                                        @endif
                                    @endif

                                    @if(Session::get('organization_id'))

                                        @if (App\Organization::checkModuleExists('wfm', Session::get('organization_id')))
                                            @permission('wfm')

                                            <li><a href="{{ route('wfm.dashboard') }}">
                                                    <img src="{{ URL::to('/') }}/public/package/3.jpg" width="70">
                                                    WFM </a></li>
                                            @endpermission

                                        @endif
                                    @endif

                                    @if(Session::get('organization_id'))
                                        @if (App\Organization::checkModuleExists('inventory', Session::get('organization_id')))
                                            @permission('inventory')
                                            <li><a href="{{ route('inventory.dashboard') }}">
                                                    <img src="{{ URL::to('/') }}/public/package/4.jpg" width="70">
                                                    Inventory </a></li>
                                            @endpermission
                                        @endif
                                    @endif
                                    @if(Session::get('organization_id'))
                                        @if (App\Organization::checkModuleExists('trade', Session::get('organization_id')))
                                            @permission('trade')
                                            <li><a href="{{ route('trade.dashboard') }}">
                                                    <img src="{{ URL::to('/') }}/public/package/5.jpg" width="70">
                                                    Trade </a></li>
                                            @endpermission
                                            @endif
                                            @endif

                                                    <!-- @if(Session::get('organization_id'))
                                            @if (App\Organization::checkModuleExists('project', Session::get('organization_id')))
                                            @permission('project')
                                                    <li> <a class="project" href="{{ route('project.dashboard') }}"> <i class="fa fa-shopping-cart"></i> Project </a> </li>
								@endpermission
                                            @endif
                                            @endif -->
                                            <!-- @if(Session::get('organization_id'))
                                            @if (App\Organization::checkModuleExists('workshop', Session::get('organization_id')))
                                            @permission('workshop')
                                                    <li> <a class="workshop" href="{{ route('workshop.dashboard') }}">
							<img src="{{ URL::to('/') }}/public/package/6.jpg" width="70"> 
							Workshop </a> </li>
								@endpermission
                                            @endif
                                            @endif -->
                                            @if(Session::get('organization_id'))

                                                @permission('trade-wms')
                                                <li><a href="{{ route('trade_wms.dashboard') }}">
                                                        <img src="{{ URL::to('/') }}/public/package/7.jpg" width="70">
                                                        WMS </a></li>
                                                @endpermission

                                            @endif


                                </ul>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="dropdown">
                        <a title="Home" href="{{ route('dashboard') }}"><i class="fa icon-basic-home"></i></a>
                    </div>
                @endif
            @else
                <div class="dropdown">
                    <a title="Menus" href="#"><i class="fa icon-arrows-squares"></i></a>

                    <div class="drop-menu right float-right">
                        <div class="box-sm menu-box-sm main-menu">
                            <ul>
                                <li><a href="{{ route('user.dashboard') }}">
                                        <img src="{{ URL::to('/') }}/public/package/1.jpg" width="70">
                                        My Accounts </a></li>
                                <li><a href="{{ route('personal_people.index') }}">
                                        <img src="{{ URL::to('/') }}/public/package/2.jpg" width="70">
                                        My People </a></li>
                                <li><a href="{{ route('vehicle_management.dashboard') }}">
                                        <img src="{{ URL::to('/') }}/public/package/vms.jpg" width="70"> VMS </a></li>
                                <li><a href="{{ route('books.dashboard') }}">
                                        <img src="{{ URL::to('/') }}/public/package/assets_locked.jpg" width="70">
                                        Assets </a></li>
                                <li><a href="{{ route('books.dashboard') }}">
                                        <img src="{{ URL::to('/') }}/public/package/prescriptions_locked.jpg"
                                             width="70"> Prescriptions </a></li>
                                <li><a href="{{ route('books.dashboard') }}">
                                        <img src="{{ URL::to('/') }}/public/package/tasks_locked.jpg" width="70"> Tasks
                                    </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <div id="notification" class="dropdown">
                <a title="Notifications" href="#">
                    <span style="display: none;" class="small-badge bg-yellow"></span>
                    <i class="fa fa-bell-o"></i></a>

                <div class="drop-menu right float-right">
                    <div class="box-sm main-menu">

                        <h5 style="margin: 0; padding: 5px;">Notifications</h5>

                        <hr style="margin: 0 0 5px;">

                        <ul class="toolbar_notifications">
                        </ul>
                        <a style="background: #2991D8; color: #fff;display: block; overflow: hidden; height: 100%; content: ''; border-radius: 3px; position: relative; min-height: 1px; padding: 10px;"

                           @if(Session::get('account_type') == 'business')
                           href="{{ route('notifications') }}"
                           @elseif(Session::get('account_type') == 'user')
                           href="{{ route('user_notifications') }}"
                           @endif


                           class="label label-sm label-success">View All</a>
                    </div>
                </div>
            </div>

            <div class="dropdown"><a title="Settings" href="{{ route('settings') }}">
                    <i class="fa icon-basic-settings"></i></a>
            </div>


            <div class="dropdown">
                <a href="#" title="My Account" class="user-profile clearfix">
                    <img src="{{ URL::to('/') }}/public/users/images/{{ App\Person::user_image(Auth::user()->person_id) }}"
                         alt="Profile image" width="18" style="vertical-align:top ; width: 20px; margin: 5px;">
                    <!-- <span>{{ Auth::user()->name }}</span>
					<i class="fa fa-angle-down"></i> --> <!-- <i class="fa icon-basic-lock-open"></i> --></a>

                <div class="drop-menu right float-right">
                    <div class="box-sm">
                        <div class="login-box clearfix">
                            <div class="row">

                                <div class="col-md-4">

                                    <div class="form-group col-md-12 user-img">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <img src="{{ URL::to('/') }}/public/users/images/{{ App\Person::user_image(Auth::user()->person_id) }}"
                                                     alt="">

                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-8 user-info">

                                    <div class="form-group col-md-12">
                                        <div class="row">
                                            <div class="col-md-12" style="color: #333;">
                                                {{ Auth::user()->name }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <div class="row">
                                            <div class="col-md-12" style="color: #666;">
                                                Propel-ID: {{ Session::get('crm_code') }}
                                            </div>
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="form-group col-md-12">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <a href="{{ route('person_profile.show', [Auth::user()->person_id]) }}">Edit
                                                    profile</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <a href="{{ route('companies.index') }}">Change Account</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <a href="{{ route('settings') }}">Settings</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <a href="{{ route('settings') }}">Support</a>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group col-md-12">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <a href="{{ url('/logout') }}"
                                                   onclick="event.preventDefault();
													document.getElementById('logout-form').submit();">Log Out</a>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- <div class="divider"></div> -->
                        <!-- <div class="login-box clearfix">
                            <p>Propel Soft is a Marketplace for all your Business Services supported by All-In-One Platform. Instant access to customer, vendor and employee information. </p>
                            &copy; 2018,  <a href="http://www.propelsoft.in" target="_blank">PropelSoft</a>. All Rights Reserved.</div> -->
                    </div>
                </div>
            </div>

            <!-- <a class="header-btn" title="Logout" id="logout-btn" href="#"
            onclick="event.preventDefault();
                 document.getElementById('logout-form').submit();">
                 <i class="fa icon-basic-lock-open"></i>
                </a> -->
            <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
        </div>
    </div>
    <div id="page-sidebar" class="{{ Session::get('theme_sidebar') }}">
        <ul id="sidebar-menu" class="sf-js-enabled sf-arrows" style="overflow-y:;width: 73% !important ">
            @section('sidebar') @show
        </ul>
    </div>
    <div id="page-content-wrapper">
        <div id="page-content" style="padding:0;top:-24px;overflow-x: hidden">
            <div class="row filter_bar" style="background-color: #ddd;margin:0">

                <button class="TaskToggle  apper-inner-tab-backgrounds-first  " style="border:0;">
                    <i class="fa fa-filter" style="  margin:10px 0 10px 0px;  ">&nbsp;</i>
                    <span>Task Filter</span>
                </button>
                <button class="get_AdFilter  apper-inner-tab-backgrounds-first " style="border:0;">
                    <i class="fa fa-filter" style=" margin:10px 0 10px 0px;  ">&nbsp;</i><span>Advance Filter</span>
                </button>
                <!-- 	<select id="specialColor" type="select" class="get_savedsearch" name="" style="border:0;">
                        <option selected disabled hidden>Saved Search</option>
                        <option>Project 1</option>
                        <option>Project 2</option>
                        <option>Project 3</option>
                    </select> -->
                {!! Form::select('saved_search',$Savesearches, null, ['class' => 'get_savedsearch', 'style' => 'border:0;width:120px', 'id'=>'specialColor','type'=>'select']); !!}
                <div class="md-input-container md-block md-input-has-placeholder" style="width:10%;margin: 10px 0 0 0;">
                    <input type="text" placeholder="go to Task" ng-model="searchProject.name"
                           class="ng-pristine ng-valid md-input ng-empty ng-touched search_task" aria-label="enter Task Name"
                           id="input_2" aria-invalid="false" style="">

                    <div class="md-errors-spacer"></div>
                    <button class="md-button md-ink-ripple" type="button" ng-transclude="" id="TaskFilter"
                            data-org-id="{{Session::get('organization_id')}}">
                        <i class="fa fa-search" aria-hidden="true" style="color: #495057;"></i>

                        <div class="md-ripple-container" style=""></div>
                    </button>
                </div>
                <div class="pull-right" style="margin:5px">
                    <p style="float: left"><input type="text" name="save_search" placeholder="save search"
                                                  ng-model="searchProject.name" aria-label="go to Task" id="input_2"
                                                  aria-invalid="false" style="">&nbsp;&nbsp;&nbsp;

                    <div class="button-4 save_search">
                        <div class="eff-4"></div>
                        <a href="#">Save Search </a>
                    </div>
                    </p>
                </div>
                <!-- <select class="" style="border:0;background-color: #ccc">
                    <i class="fa fa-filter" style=" font-weight: bold; margin:10px 0 10px 0px;  ">&nbsp;Save As Search</i>
                </select>
            -->


                <div class="Filter_menu" id="hidden"
                     style=" position: absolute;top: 13px;left: 85px;display: none;font-weight: bold;">

                    <!--  <span class="get_qucikfilter">Quick Filter</span> -->
                    <!-- <span class="get_AdFilter">Advance Filter</span>  -->
                </div>


                <!-- <button style="margin:0 0 0 auto;" class="menu-wfm"><i class="fa fa-home" ></i>&nbsp;<span >Home</span></button>
				<button class="menu-wfm"><i class="fa fa-sitemap" ></i>&nbsp;<span >Reports</span></button>
				<a style=" color: #37393d;padding-top: 8px;" href="{{ url('wfm/wfm_settings') }}"class="menu-wfm"><i class="fa fa-cog" ></i>&nbsp;<span >Settings</span></a> -->
                @include('wfm.breadcrumb')
            </div>
            <!-- Start Quick filter -->
            <div class="col-lg-12 col-md-10 col-sm-12 quick_filter"
                 style="min-height: 60px;display: none;background-color: #ddd;position: relative;z-index: 2;padding: 1%;">
                <?php

                if (isset($project_id) && $project_id != "") {

                } else {
                    $project_id = "";
                }
                //print_r($project_id);exit;
                ?>
                <div class="col-lg-2 col-md-2 col-sm-2 button-4 getTaskFilter"
                     data-organisation-id="<?php echo Session::get('organization_id');?>"
                     data-assigned-by="{{GetEmployeeData(Session::get('organization_id'),Auth::user()->person_id)}}"
                     data-assigned-to="{{GetEmployeeData(Session::get('organization_id'),Auth::user()->person_id)}}"
                     data-project-id="<?php echo $project_id ?>" data-followed-by-me="" data-high-priority=""
                     data-due-today="">
                    <div class="eff-4"></div>

                    <a href="#"> SHOW ALL </a>
                </div>
                <div class="button-4 getTaskFilter" data-organisation-id="<?php echo Session::get('organization_id');?>"
                     data-assigned-by="{{GetEmployeeData(Session::get('organization_id'),Auth::user()->person_id)}}"
                     data-assigned-to="" data-project-id="<?php echo $project_id ?>" data-followed-by-me=""
                     data-high-priority="" data-due-today="">
                    <div class="eff-4"></div>
                    <a href="#"> created by me </a>
                </div>
                <div class="button-4 getTaskFilter" data-organisation-id="<?php echo Session::get('organization_id');?>"
                     data-assigned-by=""
                     data-assigned-to="{{GetEmployeeData(Session::get('organization_id'),Auth::user()->person_id)}}"
                     data-project-id="<?php echo $project_id ?>" data-followed-by-me="" data-high-priority=""
                     data-due-today="">
                    <div class="eff-4"></div>
                    <a href="#"> assigned to me </a>
                </div>

                <div class="button-4 getTaskFilter" data-organisation-id="<?php echo Session::get('organization_id');?>"
                     data-assigned-by="" data-assigned-to="" data-project-id="<?php echo $project_id ?>"
                     data-followed-by-me="{{GetEmployeeData(Session::get('organization_id'),Auth::user()->person_id)}}"
                     data-high-priority="" data-due-today="">
                    <div class="eff-4"></div>
                    <a href="#"> followed by me </a>
                </div>
                <div class="button-4 getTaskFilter" data-organisation-id="<?php echo Session::get('organization_id');?>"
                     data-assigned-by=""
                     data-assigned-to="{{GetEmployeeData(Session::get('organization_id'),Auth::user()->person_id)}}"
                     data-project-id="<?php echo $project_id ?>" data-followed-by-me="" data-high-priority="4"
                     data-due-today="">
                    <div class="eff-4"></div>
                    <a href="#">My high priority </a>
                </div>

                <div class="button-4 getTaskFilter" data-organisation-id="<?php echo Session::get('organization_id');?>"
                     data-assigned-by=""
                     data-assigned-to="{{GetEmployeeData(Session::get('organization_id'),Auth::user()->person_id)}}"
                     data-project-id="<?php echo $project_id ?>" data-followed-by-me="" data-high-priority=""
                     data-due-today="<?php echo Carbon\Carbon::now()->format('Y-m-d'); ?>">
                    <div class="eff-4"></div>
                    <a href="#"> Due today </a>
                </div>


                <i class="fa fa-close close_btn_position"></i>
            </div>
            <!-- End Quick filter -->

            <!-- Start Advanced filter -->

            <div class="row advance_filter"
                 style="min-height: 50px;width:90%;display:none;position: absolute;z-index: 5;background-color: #ddd;margin-left: -1px;padding: 1%;margin-left: 7%">

                <div class="col-md-4 spliter table_outer pull-left">

                    <table class="advance_filter_options ">
                        <tr>
                            <td>Project</td>
                            <td>
                                <!-- 				<select id="multiselect" multiple="multiple">
                          <option value="http://ipv4.download.thinkbroadband.com/5MB.zip">Option 1</option>
                          <option value="http://ipv4.download.thinkbroadband.com/10MB.zip">Option 2</option>
                          <option value="http://ipv4.download.thinkbroadband.com/20MB.zip">Option 3</option>
                          <option value="http://ipv4.download.thinkbroadband.com/50MB.zip">Option 4</option>
                        </select> -->
                                {!! Form::select('projects',$projects, null, ['class' => 'form-control multiselect ad_search_select', 'style' => 'width: 100%;',"id"=>"", 'multiple'=>'multiple']); !!}
                            </td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>{!! Form::select('status',$Statuses, null, ['class' => 'form-control multiselect ad_search_select', 'style' => 'width: 100%', 'multiple'=>'multiple']); !!}</td>
                        </tr>
                        <tr>
                            <td>Priority</td>
                            <td>{!! Form::select('priority', ['0'=>'select','1'=>'Low','2'=>'Normal','3'=>'Medium','4'=>'High'], null, ['class' => 'form-control multiselect ad_search_select', 'style' => 'width: 100%', 'multiple'=>'multiple']); !!}</td>
                        </tr>
                        <tr>
                            <td>Created by</td>
                            <td>{!! Form::select('created_by',$EmployeeList, null, ['class' => 'form-control multiselect ad_search_select', 'style' => 'width: 100%', 'multiple'=>'multiple']); !!}</td>
                        </tr>
                        <tr>
                            <td>Assigned to</td>
                            <td>{!! Form::select('created_to',$EmployeeList, null, ['class' => 'form-control multiselect ad_search_select', 'style' => 'width: 100%', 'multiple'=>'multiple']); !!}</td>
                        </tr>

                    </table>
                </div>

                <div class="col-md-3 spliter table_inner pull-left">
                    <table class="advance_filter_options ">
                        <tr>
                            <td style="width:30%">Project Owner</td>
                            <td>
                                {!! Form::select('pro_owner',$EmployeeList, null, ['class' => 'form-control multiselect ad_search_select', 'style' => 'width: 100%', 'multiple'=>'multiple']); !!}
                            </td>
                        </tr>
                        <tr>
                            <td>Task Size</td>
                            <td>{!! Form::select('size', $Sizes, null, ['class' => 'form-control multiselect ad_search_select', 'style' => 'width: 100%', 'multiple'=>'multiple']); !!}</td>
                        </tr>
                        <tr>
                            <td>Worth Between</td>
                            <td>
                                <div style="width:40%;float:left;"> {{ Form::text('worth_from', null,['class' => 'form-control ad_search_input', 'style' => 'width: 100%;min-height: 28px']) }}</div>
                                <div style="width:40%;float:right;min-height: 28px">{{ Form::text('worth_to', null,['class' => 'form-control ad_search_input', 'style' => 'width: 100%;min-height: 28px']) }}</div>
                            </td>
                        </tr>
                        </td></tr>
                        <tr>
                            <td>Tag text</td>
                            <td>{{ Form::text('tags', null,['class' => 'form-control ad_search_input', 'style' => 'width: 100%;min-height: 28px']) }}</td>
                        </tr>
                        <tr>
                            <td>Task Name</td>
                            <td>{{ Form::text('search_task_name', null,['class' => 'form-control ad_search_input', 'style' => 'width: 100%;min-height: 28px']) }}</td>
                        </tr>
                        @if(Session::get('organization_id'))
                            <tr>
                                <td colspan="2" style="text-align: center;margin: 0 auto">
                                    <div class="button-4  Ad_Search" data-org-id="{{Session::get('organization_id')}}">
                                        <div class="eff-4"></div>
                                        <a href="#"> Search </a>
                                    </div>
                                    <div class="button-4 ">
                                        <div class="eff-4"></div>
                                        <a href="#"> refresh </a>
                                    </div>
                                </td>
                            </tr>
                        @endif

                    </table>
                </div>


                <div class="col-md-3 spliter pull-left">

                    <table class="advance_filter_options ">
                        <tr>
                            <td style="text-align: center;" class="table_title">Task Due Date</td>
                        </tr>
                        <!-- 	<tr><td style="text-align: left">From:</td><td style="text-align: left">To</td></tr> -->
                        <tr>
                            <td>{!! Form::text('due_date_from', '', array('class' => 'form-control date-picker ad_search_input input_due_date', 'data-date-format' => 'dd-mm-yyyy','id'=>'due_date_from','style'=>'color: #919191;min-height:28px','placeholder'=>'From date','data-date-format' => 'dd-mm-yyyy')) !!}</td>
                            <td>{!! Form::text('due_date_to', '', array('class' => 'form-control date-picker ad_search_input input_due_date', 'data-date-format' => 'dd-mm-yyyy','id'=>'due_date_to','style'=>'color: #919191;min-height:28px','placeholder'=>'To date','data-date-format' => 'dd-mm-yyyy')) !!}</td>
                        </tr>

                        <tr>
                            <td style="text-align: center;" class="table_title">Created Date</td>
                        </tr>
                        <!-- <tr><td style="text-align: left">From:</td><td style="text-align: left">To</td></tr> -->
                        <tr>
                            <td>{!! Form::text('create_date_from', '', array('class' => 'form-control date-picker ad_search_input input_create_date', 'data-date-format' => 'dd-mm-yyyy','id'=>'create_date_from','style'=>'color: #919191;min-height:28px','placeholder'=>'From date','data-date-format' => 'dd-mm-yyyy')) !!}</td>
                            <td>{!! Form::text('create_date_to', '', array('class' => 'form-control date-picker ad_search_input input_create_date', 'data-date-format' => 'dd-mm-yyyy','id'=>'create_date_to','style'=>'color: #919191;min-height:28px','placeholder'=>'To date','data-date-format' => 'dd-mm-yyyy')) !!}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;" class="table_title">Project Deadline</td>
                        </tr>
                        <!-- 	<tr><td style="text-align: left">From:</td><td style="text-align: left">To</td></tr> -->
                        <tr>
                            <td>{!! Form::text('project_due_date_from', '', array('class' => 'form-control date-picker ad_search_input input_project_duedate', 'data-date-format' => 'dd-mm-yyyy','id'=>'project_due_date_from','style'=>'color: #919191;min-height:28px','placeholder'=>'From date','data-date-format' => 'dd-mm-yyyy')) !!}</td>
                            <td>{!! Form::text('project_due_date_to','', array('class' => 'form-control date-picker ad_search_input input_project_duedate', 'data-date-format' => 'dd-mm-yyyy','id'=>'project_due_date_to','style'=>'color: #919191;min-height:28px','placeholder'=>'To date','data-date-format' => 'dd-mm-yyyy')) !!}</td>
                        </tr>

                    </table>
                </div>


                <i class="fa fa-close close_btn_position"></i>

                <div class="row col-md-6 pull-left" style="margin:0 0 0 auto">


                    <!-- <div class="button-4 pull-right">
							<div class="eff-4"></div>
								<a href="#" > Search </a>
						</div>&nbsp;&nbsp;
						
							
								<div class="form-group">
									<label class="input_icon"  for="save_search" ><i class="fa fa-save "></i>&nbsp;Save  Search as</label>
									{!! Form::text('project', null, array('class' => 'form-control','rows'=>4,'style'=>'width:90%','id'=>'save_search')) !!}
                            </div>  -->


                    <!-- Popup saved -->

                    <!-- /Popup saved -->
                </div>
            </div>

            <!-- End Advanced filter -->

            <div id="page-content" style="margin-left:0">
                <div id="container"> @yield('content')
                </div>

            </div>
        </div>
    </div>


    <div class="row" style="position:fixed;display: block;bottom:1px;right: 3%;z-index: 999">
        <div class="row popup_menu_items" style="display: none;">
            <!-- Add Option -->
            @permission('wfm-add-project-menu')

            <div class="col-lg-12" style="height: 62px; ">
                <div class="col-lg-2 col-md-2 col-sm-5 pull-right">
                    <div class="sub-circle pull-right Add_project" id="add_proj"><i
                                class="fa fa-folder-open-o sub_add_icon"></i></div>
                    <label class="data-label Add_project" for="add_proj">Add Project</label>
                </div>
            </div>
            @endpermission
            
            <div class="col-lg-12" style="height: 62px; ">
                <div class="col-lg-2 col-md-2 col-sm-5 pull-right">
                    <div class="sub-circle pull-right Add_task"><i class="fa fa-file-text-o sub_add_icon"></i></div>
                    <label class="data-label Add_task">Add Task</label>
                </div>
            </div>
        </div>
        <!-- /Add Option -->

        <!-- Add Icon -->
        <div class="row ">
            <div class="col-lg-12" style="">
                <div class="col-lg-2 col-md-2 col-sm-5 pull-right">
                    <div class="circle pull-right" id="popup_menu"><i class="fa fa-plus-square-o add_icon"></i></div>
                </div>
            </div>
            <!-- <div class="col-lg-12" style="height: 62px; ">
                <div class="col-lg-2 col-md-2 col-sm-5 pull-right">
                    <div class="circle pull-right"><i class="fa fa-file-text-o sub_add_icon"></i></div><label class="data-label">Add Task</label>
                </div>
            </div> -->
            <!--/Add Icon  -->
        </div>
    </div>


</div>


@section('dom_links')
        <!-- Scripts -->
<!--[if lt IE 9]>
<script src="{{ URL::asset('assets/plugins/respond.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/excanvas.min.js') }}"></script>
<![endif]-->
@if(app()->environment() == "production")
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.js"></script>
    <script type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/js/bootstrap.bundle.js"></script>
    <!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/popper.min.js"></script> -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script> -->
    <script type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
    <script type="text/javascript"
            src="{{ URL::asset('assets/plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>
    <script type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.0/js/bootstrap-datepicker.js"></script>
    <script type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js"></script>
    <script type="text/javascript"
            src="{{ URL::asset('assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js') }}"></script>
    <script type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.2/js/bootstrap-switch.min.js"></script>
    <script type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/screenfull.js/3.3.2/screenfull.min.js"></script>
    <script type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-slimScroll/1.3.2/jquery.slimscroll.min.js"></script>

@elseif(app()->environment() == "local")


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.8.3/jquery.js" type="text/javascript"
            charset="utf-8"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript"
            charset="utf-8"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js" type="text/javascript"
            charset="utf-8"></script>



    <!-- <script type="text/javascript" src="{{ URL::asset('assets/plugins/jquery.min.js') }}"></script>  -->
    <script type="text/javascript" src="{{ URL::asset('assets/plugins/modernizr-custom.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/plugins/jquery-ui/jquery-ui.min.js') }}"></script>

    <script type="text/javascript"
            src="{{ URL::asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script type="text/javascript"
            src="{{ URL::asset('assets/plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/plugins/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript"
            src="{{ URL::asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script type="text/javascript"
            src="{{ URL::asset('assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js') }}"></script>
    <script type="text/javascript"
            src="{{ URL::asset('assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js') }}"></script>
    <script type="text/javascript"
            src="{{ URL::asset('assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/plugins/screenfull.min.js') }}"></script>
    <script type="text/javascript"
            src="{{ URL::asset('assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
@endif

<script type="text/javascript" src="{{ URL::asset('assets/plugins/select2/js/select2.full.min.js') }}"></script> 
<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
<script type="text/javascript" src="{{ URL::asset('assets/plugins/boostrap-tags/tagit.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/plugins/popper.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/plugins/row-sorter.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/layout/js/custom.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/plugins/boostrap-tags/bootstrap-tagsinput.js') }}"></script>
<script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/js/bootstrap.bundle.min.js"></script>

<script type="text/javascript">

    window.onbeforeunload = null;
    /*window.addEventListener("beforeunload", function(event) {
     event.returnValue = "Your custom message.";
     });*/
    if (moment().month() > 3) {
        var fiscal_year = "01 04 " + moment().year();
    } else {
        var fiscal_year = "01 04 " + moment().subtract(1, 'year').format('YYYY');
    }

    @if(Session::get('organization_id'))

    <?php $financialyear = App\AccountFinancialYear::select(DB::raw('DATE_FORMAT(financial_start_year, "%d-%m-%Y") AS financial_start_year'), DB::raw('DATE_FORMAT(financial_end_year, "%d-%m-%Y") AS financial_end_year'))->where('organization_id', Session::get('organization_id'))->where('status', '1')->first(); ?>


    <?php
        $organization_id = Session::get('organization_id');
        
        $plan = ['Free14Days','Starter','Lite','Standard','Professional','Enterprise','Corporate'];     
    ?>

    @if(App\Custom::plan_expire($plan,$organization_id))
    
    $('.transaction_change').each(function () {
        $(this).removeClass('add');
        $(this).removeClass('edit');
        $(this).removeClass('delete');
    });

    $('body').on('click', '.transaction_change', function (e) {
        e.preventDefault();
        $('#error_dialog #title').text('Plan Expired!');
        $('#error_dialog #message').text('{{ config('constants.error.expire') }}');
        $('#error_dialog').modal('show');

        return;
    });
    @endif

    @if(App\Custom::remaining_ledger())

    $('.master_add').each(function () {
        $(this).removeClass('add');
    });

    $('body').on('click', '.master_add', function (e) {
        e.preventDefault();
        $('#error_dialog #title').text('Limit Exceeded!');
        $('#error_dialog #message').text('{{ config('constants.error.limit_exceed') }}');
        $('#error_dialog').modal('show');

        return false;
    });
    @endif

    @endif

    setNotification();

    var financialyear_start = '{{$financialyear->financial_start_year or ''}}';
    var financialyear_end = '{{$financialyear->financial_end_year or ''}}';


    $(document).ready(function () {

        $('.toolbar_notifications').slimScroll({
            height: '220'
        });

        $.ajax({
            url: "{{ route('user_log') }}",
            type: 'post',
            data: {
                _token: "{{csrf_token()}}",
                page: $.trim($('.page-title').clone().find('a').remove().end().text()),
                url: window.location.href,
            },
            dataType: "json"
        });
    });

    function setNotification() {
        $.ajax({
            @if(Session::get('account_type') == 'business')
            url: "{{ route('get_notifications') }}",
            @elseif(Session::get('account_type') == 'user')
            url: "{{ route('get_user_notifications') }}",
            @endif

            type: 'post',
            data: {
                _token: "{{csrf_token()}}"
            },
            dataType: "json",
            success: function (data, textStatus, jqXHR) {
                var result = data.notifications;
                if (data.total > 0) {
                    $("#notification .bg-yellow").show();
                    if (!$("#notification > div").hasClass('drop-menu')) {
                        $("#notification > div").addClass('drop-menu');
                    }
                } else {
                    $("#notification .bg-yellow").hide();
                    if ($("#notification > div").hasClass('drop-menu')) {
                        $("#notification > div").hide();
                        $("#notification > div").removeClass('drop-menu');
                    }

                }
                var html = ``;
                for (var i in result) {
                    html += `<
                    li >
                    < div
                class
                    = "col1" >
                            < div
                class
                    = "cont" >
                            < div
                class
                    = "cont-col1" >
                            < div
                    style = "background: #ead941;"
                class
                    = "label label-sm label-success" >
                            < i
                class
                    = "fa fa-bell-o" > < / i >
                            < / div >
                            < / div >
                            < div
                class
                    = "cont-col2" >
                            < div
                class
                    = "desc" > `+result[i].message +`<
                    br >
                    < !-- < span
                    style = "padding: 2px 0px; float: left; font-size: 11px;" >`+result[i].type +`</
                    span > -- >
                    < / div >
                    < / div >
                    < / div >
                    < / div >
                    < div
                class
                    = "col2" >
                            < div
                class
                    = "date" > `+result[i].time +` </
                    div >
                    < / div >
                    < / li >`;
                }
                $('.toolbar_notifications').empty();
                $('.toolbar_notifications').append(html);
            }
        });


    }


    /* Start Task filter toggle event*/
    var shown_qfilter = false;

    var shown_afilter = false;

    var shown_savedsearch = false;


    var segment_url = "{{ Request::segment(1) }}/{{ Request::segment(2) }}";


    /*Hence 4 parameter as Project Name*/


    <?php
    if(Request::segment(4))
    {
        ?>
        //url=
    Project_name = $(".project_<?php echo Request::segment(4) ?>").find('div .text-overlap').text();

    page_breadcrumb(segment_url, "Project", Project_name);
    //alert(return_var);

    <?php }
    ?>

    /*Hence 4 parameter as Project Name*/


    $('.TaskToggle').click(function () {
        if ($('.Filter_menu').is(':visible')) {

            $('.Filter_menu').toggle('slide', {
                direction: 'left'
            }, 500);
            /*
             if(shown_qfilter)
             {
             $('.quick_filter').slideUp();
             $('.get_qucikfilter').removeClass('menu_effect_add');
             shown_qfilter = false;
             }*/

            if (shown_afilter) {
                $('.advance_filter').slideUp();
                $('.get_AdFilter').removeClass('menu_effect_add');
                shown_afilter = false;
            }
        }
        else {
            $('.Filter_menu').toggle('slide', {
                direction: 'left'
            }, 500, function () {
                $('#cat_icon').fadeIn();
            });


        }
        $('.DetailsBar').css("display", "none");
    });

    /* End Task filter toggle event*/



    /* Start Quickilter toggle event*/
    $('body').on('click', '.TaskToggle', function () {
        //alert(shown_qfilter);
        if (shown_afilter) {
            $('.advance_filter').slideUp();
            $('.get_AdFilter').removeClass('menu_effect_add');
            shown_afilter = false;
        }
        if (!shown_qfilter) {
            $('.get_qucikfilter').addClass('menu_effect_add');
            $('.quick_filter').slideDown();
        } else {
            $('.quick_filter').slideUp();
            $('.get_qucikfilter').removeClass('menu_effect_add');


        }
        shown_qfilter = !shown_qfilter;
    });
    /* End Quickilter toggle event*/



    /* Start AdvancedFilter toggle event*/
    $('body').on('click', '.get_AdFilter', function () {
        //	alert(shown_afilter);
        if (shown_qfilter) {
            $('.quick_filter').slideUp();
            $('.get_qucikfilter').removeClass('menu_effect_add');
            shown_qfilter = true;
        }

        if (!shown_afilter) {
            $('.get_AdFilter').addClass('menu_effect_add');
            $('.advance_filter').slideDown();
        } else {
            $('.advance_filter').slideUp();
            $('.get_AdFilter').removeClass('menu_effect_add');


        }
        shown_afilter = !shown_afilter;
    });
    /* End AdvancedFilter toggle event*/


    /* Start Savedsearch toggle event*/

    $('body').on('click', '.get_savedsearch', function () {
        //	alert(shown_afilter);
        if (shown_qfilter) {
            $('.quick_filter').slideUp();
            $('.get_qucikfilter').removeClass('menu_effect_add');
            shown_qfilter = true;
        }
        if (shown_afilter) {
            $('.advance_filter').slideUp();
            $('.get_AdFilter').removeClass('menu_effect_add');
            shown_qfilter = true;
        }
        /*if (!shown_savedsearch) {
         $('.get_AdFilter').addClass('menu_effect_add');
         $('.advance_filter').slideDown();
         }else{
         $('.advance_filter').slideUp();
         $('.get_AdFilter').removeClass('menu_effect_add');


         }*/
        shown_savedsearch = !shown_savedsearch;
    });
    /* End Savedsearch toggle event*/

    /* Start Close  event*/
    $('body').on('click', '.close_btn_position,.close_event', function () {

        if ($('.quick_filter').is(':visible')) {
            $('.quick_filter').slideUp();
            $('.get_qucikfilter').removeClass('menu_effect_add');
        }
        if ($('.advance_filter').is(':visible')) {
            $('.advance_filter').slideUp();
            $('.get_AdFilter').removeClass('menu_effect_add');
        }

        if ($('.DetailsBar').is(':visible')) {
            $(".progress_data").css("visibilty", "hidden");
            $(".progress_label").css("visibilty", "hidden");
            $('.DetailsBar').toggle('slide', {
                direction: 'right'
            }, 500);
            $(".progress_label").css("visibility", "hidden");
            $(".progress_data").css("visibility", "hidden");

        }
        if ($("#Toggle_screen").hasClass("col-lg-12 col-md-6 col-sm-4 DetailsBar")) {
            $("#Toggle_screen").removeClass("col-lg-12 col-md-6 col-sm-4 DetailsBar");
            $("#Toggle_screen").addClass("col-lg-5 col-md-6 col-sm-4  DetailsBar");
            $("#toggle_taskDetails").removeClass('fa fa-compress');

            $("#toggle_taskDetails").addClass('fa fa-expand');
        }
        $('#popup_menu').css('display','block');
        //	sub-menu hidden


    })
    /* End Close  event*/
    /*Start Click outside of the popup closed*/

    $(document).click(function (event) {


        //if you click on anything except the modal itself or the "open modal" link, close the modal
        if (!$(event.target).closest(".quick_filter,.filter_bar").is(':visible')) {
            $('.quick_filter').slideUp();
            $('.get_qucikfilter').removeClass('menu_effect_add');
        }

        /*  if (!$(event.target).closest(".advance_filter,.filter_bar").is(':visible')) {
         $('.advance_filter').slideUp();
         $('.get_AdFilter').removeClass('menu_effect_add');
         }*/

        <?php /* ?>  if (!$(event.target).closest('.DetailsBar,#datatable,.get_detailsbar,#image-upload,.datepicker-days,td.active.day,td.day').is(':visible')) {
//console.log("tested");
            //Check length for when upload the file in view task details

            $('#fountainG').css("display", "block");
            $('.DetailsBar').css('display', 'none');
            /*if($(event.target).closest('input').find() > 0 && !$('.dz-hidden-input').is(':visible'))
             {

             }

             }
            <?php */ ?>
                        var input=$(this).find('form-control');
             var input_icon=$(this).parent().find('.input_icon');

             if(!$(event.target).closest('#follower_panel,.add_follower').is(':visible'))
             {
             //console.log( $('#follower_panel').length);
             $('#follower_panel').css('display','none');

             }
             if(!$(event.target).closest('.Add_project,.popup_menu,.add_icon,.sub_add_icon,.Add_task').is(':visible'))
             {

             if($('.popup_menu_items').is(':visible'))
             {
             $('.popup_menu_items').css('display','none')
             }
             }
             });
             /* End Click outside of the popup closed*/


            /*popup Window for create project*/


            var datatable = null;

            var Search_inputs;
            var datatable_options = {
                "columnDefs": [{"orderable": true, "targets": [0]}],
                "order": [[1, "asc"]],
                "stateSave": true
            };


            $(document).ready(function () {
                $('.multiselect').multiselect();
            });
            $(document).ready(function () {

                datatable = $('#datatable').DataTable(datatable_options);
                var Buss_Name = $("span#getBusiness").text();
                //console.log(datatable);
                $('body').on('click', '.Add_project', function (e) {
                    e.preventDefault();
                    $.get("{{ route('project.create') }}", function (data) {
                        $('.wfm_project_crud_modal .modal-container').html("");
                        $('.wfm_project_crud_modal .modal-container').html(data);
                        //$('.crud_modal .modal-container .modal-header h4').append("("+Buss_Name+")");

                    });
                    //$('.crud_modal').find('.modal-dialog').addClass('modal-lg');
                    $('.wfm_project_crud_modal').modal('show');
                });


                $('body').on('click', '.Add_task', function (e) {
                    e.preventDefault();
                    $.get("{{ route('task.create') }}", function (data) {
                        //	console.log(data);
                        $('.wfm_crud_modal .modal-container').html("");
                        $('.wfm_crud_modal .modal-container').html(data);
                        //	$('.wfm_crud_modal .modal-container .modal-header h4').append("("+Buss_Name+")");
                    });
                    //$('.crud_modal').find('.modal-dialog').addClass('modal-lg');
                    $('.wfm_crud_modal').modal('show');
                });


                $('body').on('click', '.Task_attachments', function (e) {
                  
                    id = "12";
                    $.get("{{ route('task.attachments') }}", function (data) {
                        //	console.log(data);
                        $('.wfm_attachment_modal .modal-container').html("");
                        $('.wfm_attachment_modal .modal-container').html("");
                        $('.wfm_attachment_modal .modal-container').html(data);
                        //	$('.wfm_crud_modal .modal-container .modal-header h4').append("("+Buss_Name+")");
                    });
                    //$('.crud_modal').find('.modal-dialog').addClass('modal-lg');
                    $('.wfm_attachment_modal').modal('show');
                });

                $('body').on('click', '.close_model', function (e) {

                    $('.wfm_project_crud_modal').css('z-index', '1040');
                    $('.wfm_crud_modal').css('z-index', '1040');
                    $("div.confirmation_modal_ajax  h4.modal-title").text('Alert');
                    $("div.confirmation_modal_ajax  div.modal-body").text('Your changes have not been saved. To stay on this page so that you can save the changes or click cancel');


                    $("div.confirmation_modal_ajax  div.modal-footer button.btn.default").text('Cancel').on('click', function () {
                        $('.wfm_project_crud_modal').css('z-index', '1050');
                        $('.wfm_attachment_modal').css('z-index', '1050');
                        $('.wfm_crud_modal').css('z-index', '1050');
                        $('.wfm_crud_modal').modal('hide');
                        $('.wfm_project_crud_modal').modal('hide');
                        $(".confirmation_modal_ajax").modal('hide');
                        $(".wfm_attachment_modal").modal('hide');

                    });


                    $("div.confirmation_modal_ajax  div.modal-footer button.delete_modal_ajax_btn").text('Stay/Continue').on('click', function () {

                        $('.wfm_project_crud_modal').css('z-index', '1050');
                        $('.wfm_crud_modal').css('z-index', '1050');
                        $(".confirmation_modal_ajax").modal('hide');
                    });
                    ;
                    $(".confirmation_modal_ajax").modal('show');

                });


                $('body').on('focusin', '.form-control', function (e) {

                    var input_icon = $(this).parent().find('.input_icon');

                    if (input_icon.length > 0) {
                        $(input_icon).fadeOut();
                    }


                });
            });


            $('body').on('focusout', '.form-control', function (e) {
                e.preventDefault(e);

                var input_icon = $(this).parent().find('.input_icon');
                if (input_icon.length > 0) {
                    if ($(this).val() == "") {

                        $(input_icon).fadeIn();
                    }
                }

            });


            $('body').on('click', '#popup_menu', function () {

                if ($('.popup_menu_items').is(':visible')) {
                    $('.popup_menu_items').css('display', 'none')
                } else {

                    $('.popup_menu_items').css('display', 'block')
                }
            })


            $(window).on("loader mouseenter", function () {
                var width = $("#page-sidebar").width();
                if (width == 50) {
                    $(".sidebar-submenu").css("display", "none");
                }
            });
            $(document).ready(function () {
                $('body [data-toggle="tooltip"]').tooltip();


                $('body').on('change', 'select[name=project_list]', function () {
                    //	alert(newurl);
                    $('.chart').attr('id', $(this).val());
                    console.log($(this).val());
                    var link = $(this).find(':selected').attr('data-href');
                    window.history.pushState({path: newurl}, '', link);
                    //alert(link);
                    $('#tableContent').load(link);
                    //alert(link);
                })
                /*end  select project*/

                var ProData;

                /*select org data*/
                $('body').on('change', '.getOrgData', function () {
                    //e.preventDefault();
                    var DataElement = $("li.Position");
                    var organization_id = $(this).val();
                    console.log(organization_id);
                    if (organization_id != "") {
                        $("#TaskFilter").attr("data-org-id", organization_id);
                        $(".Ad_Search").attr("data-org-id", organization_id);
                        url = "{{ route('wfm.orgdetails') }}";
                        $.ajax({
                            url: url,

                            type: 'post',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                organization_id: organization_id,
                            },
                            dataType: "json",
                            success: function (data, textStatus, jqXHR) {
                                //	console.log(data);
                                if (data.status == 1) {
                                     location.reload();
                                   /* OptionElement = $(".Position select");
                                    ListElement = $("ul.project_list");
                                    //alert($(ListElement).empty());
                                    //	return false;

                                    ProjectList(organization_id, OptionElement, data.Options, ListElement, data.Latest_projects);
                                    $(".company_name").text(data.business_name);
                                    $(".company_slogan").text(data.bcrm_code);*/
                                }
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                            }
                        });

                    }
                });


                /*START ADVANCED SEARCH*/
                /*select org data*/

                $('body').on('click', '.Ad_Search', function () {
                    
                    //e.preventDefault();
                    var status = 0;
                    var organization_id = $(this).attr('data-org-id');
                    /*	$(".ad_search_input").filter(function () {

                     if($.trim($(this).val())!= "")
                     {
                     status=1
                     }


                     });*/

                    $(".ad_search_select").filter(function () {
                        //	console.log($.trim($(this).val()));
                        if ($.trim($(this).val()).length != 0) {
                            status = 1
                        }

                    });
                    worth_from = $('input[name=worth_from]').val();
                    worth_to = $('input[name=worth_to]').val();
                    due_date_from = $('input[name=due_date_from]').val();
                    due_date_to = $('input[name=due_date_to]').val();
                    create_date_from = $('input[name=create_date_from]').val();
                    create_date_to = $('input[name=create_date_to]').val();
                    project_due_date_from = $('input[name=project_due_date_from]').val();
                    project_due_date_to = $('input[name=project_due_date_to]').val();
                    tag = $('input[name=tags]').val();
                    task_name = $('input[name=task_name]').val();
                    
                    /*if(tag=="" && task_name=="" && status==0)
                     {
                     status=0;
                     }*/
                    if (task_name != "" || tag != "") {

                        status = 1;
                    }
                    //console.log(status);
                    if (worth_from != "" && worth_to != "") {
                        status = 1;
                    }
                    if (due_date_from != "" && due_date_to != "") {
                        status = 1;
                    }
                    if (create_date_from != "" && create_date_to != "") {
                        status = 1;
                    }
                    if (project_due_date_from != "" && project_due_date_to != "") {
                        status = 1;
                    }
                    if (status == 0) {
                        //console.log($(".tableContent").find('tbody tr').length);

                        if ($(".tableContent").find('tbody tr').length) {

                            $(".tableContent").find('tbody tr').empty();
                        }
                    }
                    /*	console.log($('select[name=pro_owner]'));
                     return false;*/
                    if (status == 1) {
                        $("#table_container").addClass('blur_background');
                        $(".table_container_loader").show();
                        Search_inputs = "";
                        Search_inputs = {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            projects: $('select[name=projects]').val(),
                            status: $('select[name=status]').val(),
                            priority: $('select[name=priority]').val(),
                            created_by: $('select[name=created_by]').val(),
                            created_to: $('select[name=created_to]').val(),
                            project_owner: $('select[name=pro_owner]').val(),
                            size: $('select[name=size]').val(),
                            worth_from: $('input[name=worth_from]').val(),
                            worth_to: $('input[name=worth_to]').val(),
                            tags: $('input[name=tags]').val(),
                            task_name: $('input[name=task_name]').val(),
                            due_date_from: $('input[name=due_date_from]').val(),
                            due_date_to: $('input[name=due_date_to]').val(),
                            create_date_from: $('input[name=create_date_from]').val(),
                            create_date_to: $('input[name=create_date_to]').val(),
                            project_due_date_from: $('input[name=project_due_date_from]').val(),
                            project_due_date_to: $('input[name=project_due_date_to]').val(),
                            organization_id: organization_id,
                        },
                                $.ajax({
                                    url: "{{ route('task.advancefilter') }}",

                                    type: 'post',
                                    data: {
                                        _token: $('meta[name="csrf-token"]').attr('content'),
                                        projects: $('select[name=projects]').val(),
                                        status: $('select[name=status]').val(),
                                        priority: $('select[name=priority]').val(),
                                        created_by: $('select[name=created_by]').val(),
                                        created_to: $('select[name=created_to]').val(),
                                        project_owner: $('select[name=pro_owner]').val(),
                                        size: $('select[name=size]').val(),
                                        worth_from: $('input[name=worth_from]').val(),
                                        worth_to: $('input[name=worth_to]').val(),
                                        tags: $('input[name=tags]').val(),
                                        task_name: $('input[name=task_name]').val(),
                                        due_date_from: $('input[name=due_date_from]').val(),
                                        due_date_to: $('input[name=due_date_to]').val(),
                                        create_date_from: $('input[name=create_date_from]').val(),
                                        create_date_to: $('input[name=create_date_to]').val(),
                                        project_due_date_from: $('input[name=project_due_date_from]').val(),
                                        project_due_date_to: $('input[name=project_due_date_to]').val(),
                                        organization_id: organization_id,
                                    },
                                    dataType: "html",
                                    success: function (data, textStatus, jqXHR) {

                                        //	console.log($(".tableContent").length);
                                        // $(".tableContent").find('tbody tr').append(data);
                                        page_breadcrumb('wfm/advancefilter');
                                        call_back_optional(data, `add`,`test`)
                                        ;
                                        //	console.log(data);
                                        $('input[name=save_search]').val(" ");
                                        $('.save_search').show();
                                        $('input[name=save_search]').show();

                                        $("#table_container").removeClass('blur_background');
                                        $(".table_container_loader").hide();
                                        //console.log(Search_inputs);
                                    },
                                    error: function (jqXHR, textStatus, errorThrown) {
                                    }
                                });
                    }

                })
            });

            /*END ADVANCED SEARCH*/
            /*STAR SAVE SEARCH*/

            //	$('.save_search')
            $('body').on('click', '.save_search', function () {
                search_name = $('input[name=save_search]').val();
                search = $('input[name=save_search]').val();
                //console.log(search_name);
                if (Search_inputs && search_name != "") {
                    object2 = {"search_name": search_name}
                    $.extend(Search_inputs, object2)


                    $.ajax({
                        url: "{{ url('wfm/save_search') }}",

                        type: 'post',
                        data: Search_inputs,

                        dataType: "json",
                        success: function (data, textStatus, jqXHR) {
                            //console.log(data);
                            if (data.status == 1) {
                                //console.log($(".get_savedsearch").length);

                                $('.save_search').hide();
                                $('input[name=save_search]').hide();
                                $(".get_savedsearch").append('<option value="' + data.lastInsertId + '">' + data.save_search + '</option>');


                            }

                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                        }
                    });

                } else {
                    console.log("empty");
                }
            });

            /*END SAVE SEARCH*/

            /*START SAVE SEARCH RESULTS 13-12-2018*/

            $('body').on('change', '.get_savedsearch', function () {
                getSearch_id = $(this).val();
                search_name = $(this).find(':selected').text();
                if (getSearch_id) {
                    $.ajax({
                        url: "{{ url('wfm/get_savedsearch') }}/" + getSearch_id,

                        type: 'get',
                        dataType: "html",
                        success: function (data, textStatus, jqXHR) {
                            page_breadcrumb('wfm/get_savedsearch', search_name);
                            //	console.log(data);
                            call_back_optional(data, `add`,`test`)
                            ;

                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                        }
                    });
                }

            });

            /*END SAVE SEARCH RESULTS 13-12-2018*/


            /*select org data*/
            function GetInputArray(input_data, type='') {
                var title_return = [];
                $(input_data).each(function (i, value) {
                    var newElement = {};
                    key = i + 1;
                    if ($(this).attr('data-original-title')) {

                        if (type == 1) {

                            title_return.push($(this).attr('data-original-title'));

                        } else {

                            input_data[i].setAttribute("id", "title" + key);
                            newElement["title" + key] = $(this).attr('data-original-title');
                            title_return.push(newElement);

                        }


                    }
                });
                return title_return;

            }


            $('body').on('change', '.GetProjectForm,.GetProjectCategory', function () {


                field_val = $(this).val();
                function_name = $(this).attr('data-function')

                field_name = $(this).attr('data-name');
                return_fields = $(this).attr('data-return');
                form_id = $(this).attr('data-form-id');
                form_title_class = $(this).attr('data-title-class');
                input_data = $('#' + form_id).find('.' + form_title_class);

                title_return = GetInputArray(input_data);
                title_input = GetInputArray(input_data, 1);

                //console.log( return_fields);


                $.ajax({
                    url: '{{URL::to('/wfm/')}}/' + function_name + '/' + field_val,

                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        tooltip_title: title_return,


                    },

                    dataType: "json",
                    success: function (data, textStatus, jqXHR) {
                        if (data.status == 1) {
                            for (i = 0; i < title_return.length; i++) {
                                $.each(title_return[i], function (field_id, field_title) {
                                    $('#' + field_id).attr('data-original-title', data.tooltip_title[field_id]);
                                    //console.log();
                                });
                            }
                            //return false;
                            $.each($.parseJSON(return_fields), function (field_name, field_value) {
                                //	console.log(field_name);
                                if ($("#" + field_name).attr('type') == 'select') {
                                    $("#" + field_name).empty();
                                    $("#" + field_name).append('<option selected="selected" value="">Select</option>');

                                    selectValues = data[field_value];
                                    //console.log(selectValues);
                                    $.each(selectValues, function (key, value) {
                                        $("#" + field_name)
                                                .append($("<option></option>")
                                                        .attr("value", key)
                                                        .text(value));
                                    });

                                }


                            });

                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                    }
                });

            })


            var url = "{{URL::to("/")}}/wfm/dashboard";
            $('body [data-toggle="tooltip"]').tooltip();

            function ProjectList(organization_id, OptionElement, OptionDataArray, ListElement, ListDataArray=false) {
                OptionData = "";
                ListData = "";
                $(OptionElement).empty();
                //return false;
                if (Array.isArray(OptionDataArray)) {

                    OptionData += "<option value=''>select</option>";
                    OptionDataArray.forEach(function (key, value) {
                        OptionData += "<option value='" + key['id'] + "' data-href='http://localhost/propel/wfm/dashboard/" + organization_id + "/" + key['id'] + "'>" + key['project_name'] + "</option>";
                    });
                    $(OptionElement).append(OptionData);

                    //console.log("true");
                }
                if (Array.isArray(ListDataArray)) {
                    $(ListElement).empty();
                    ListDataArray.forEach(function (key, value) {
                        ListData +=`<
                        li
                        style = "display: inline-flex;width: 100%;" > < a
                        data - link = "job-allocation"
                        class
                        = " getproject"
                        id = "project_`+key[`id`]+`"
                        data - id = "`+key[`id`]+`"
                        data - org - id = "`+key[`organization_id`]+`"
                        data - href = "http://localhost/propel/wfm/dashboard/`+organization_id+`/`+key[`id`]+`" > < span >`+key[`project_name`]
                        +`</
                        span > < / a > < span
                        class
                        = "count popoverThis"
                        data - html = "true"
                        title = ""
                        data - toggle = "popover"
                        data - placement = "bottom"
                        data - content = "<div><li style='color: #666;'><a>Edit Project</a></li><li style='color: #666;'><a>Close Project</a></li><li style='color: #666;'><a>Archive Project</a></li><li style='color: #666;'><a>Project Log</a></li></div><div></div>"
                        data - id = "projectcount_`+key[`id`]+` "
                        id = "project_popup_`+key[`id`]+`"
                        data - popup - id = "project_popup_`+key[`id`]+`"
                        style = "border-top:#ffab60;"
                        data - original - title = "<i class='fa fa-pie-chart' style='font-size:40px;text-align: center;color: #666;'><span class='dispaly'>View Summery</span></i><i class='fa fa-folder-open' style='font-size:40px;text-align: center;color: #666;'><span class='display'>Manage Project</span></i>" > `+key[`count`]
                        +`</
                        span >

                        < / li >`;

                    });
                    $(ListElement).append(ListData);
                    //console.log(ListData);
                    $("#getProjectName").text("/");
                    $("#datatable tbody").html("");
                    var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname;
                    window.history.pushState({path: newurl}, '', url);
                    //	console.log("true");
                }


                //$(parent_element).find('select').
            }

            function page_breadcrumb(url, content_type="", optional="") {

                if (url) {
                    $.ajax({
                        url: '{{route('page_breadcrum')}}',
//page_breadcrum
                        type: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            segment: url,
                            content_type: content_type,
                            optional: optional


                        },

                        dataType: "json",
                        success: function (data, textStatus, jqXHR) {
                            if (data.status == 1) {


                                $('.breadcrumb').find('li a').text(data.bredcrum);

                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                        }
                    });

                }
            }
</script>

<!--  <link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" />
  <script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
-->

@show
@section('foot_links')
@show
</body>
</html>

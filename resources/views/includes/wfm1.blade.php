
@section('sidebar')
<style type="text/css">
body{
    background-color: #fafcfe;
}
html,body,table,p
{
  font-size:90%;

}

.md-input-container {
    border: 1px solid  #495057;
    position: relative;
    width: 85%;
    border-radius: 20px;
    padding: 0;
    height: 22px;
    margin: 2px 0 0;
}
input::placeholder  {
    color: #495057;
}
.md-input-container input[type=text]
{

 -webkit-appearance: none;
}
.md-input-container:not(.md-input-has-value) input:not(:focus)
{
  color: transparent;

}
.md-input-container.md-block {
    display: block;
    margin: 0 auto;
}

.md-input {
    border: 0;
    color: rgba(255,255,255,.62);
    font-size: 11px;
    margin: 0;
    height: auto;
    line-height: 19px;
    font-style: italic;
    padding: 0 15px;
}
.md-input {
    -webkit-box-ordinal-group: 3;
    -webkit-order: 2;
    order: 2;
    display: block;
    margin-top: 0;
    background: none;
    padding: 2px 2px 1px;
    border-width: 0 0 1px;
    line-height: 26px;
    height: 20px;
    -ms-flex-preferred-size: 26px;
    border-radius: 0;
    border-style: solid;
    width: 100%;
    box-sizing: border-box;
    float: left;
}
.md-input::placeholder
{

    color: rgba(255,255,255,.62);
}
.md-input-container:after {
    content: '';
    display: table;
    clear: both;
}
.md-button {
    position: absolute;
    right: 6px;
    top: 3px;
    width: 14px;
}

.md-icon {
    font-size: 13px;
    color: #959595;
}
.md-button md-icon, .md-button:not([disabled]) md-icon
{
 width: auto;
 height: auto;
 min-height: 0;
 min-width: 0;
 line-height: normal;
}
/*span.blocktext
{

      margin-left: auto;
    margin-right: auto;
    padding: 0 10% 0 10%;
    text-align: justify;
    float: left;
    width:30%;
}*/
.center-table th {
    text-align: center;
    vertical-align: middle;
}

.center-table td {
    text-align: center;
    vertical-align: middle;
}
.md-icon {
    margin: auto;
    background-repeat: no-repeat;
    display: inline-block;
    vertical-align: middle;
    fill: currentColor;
    height: 24px;
    width: 24px;
    min-height: 24px;
    min-width: 24px;
}
.md-button {
    position: absolute;
    right: 6px;
    top: 3px;
    width: 14px;
}
.md-button, .md-button:not([disabled])
{
 min-height: 0;
 min-width: 0;
 background: 0 0;
 padding: 0;
 margin: 0;
 width: auto;
 height: auto;
 line-height: normal;
}
.md-button {
 border:0px;
 letter-spacing: .01em;
 cursor:pointer;
}
.text-overlap
{
  white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    float: left
}
p,table,.dcolor{
    color:#919191;
}
select {
   /* border: 1px solid #fff;*/
    background-color: rgba(255,255,255,.5);
    padding: 5px;
}

select option{
    background-color: transparent !important;
    border: 1px solid #e4e4e4;
    color: #000;
    -webkit-appearance: none; 
    -moz-appearance: none; 
}
.md-block1 {
    padding: 0;
    border: 1px solid #e8e8e8;
    border-radius: 2px;
    margin: 0 0 10px;
    overflow: hidden;
    }

.md-block1.slctddn label:not(.md-no-float):not(.md-container-ignore) {
    position: relative;
    vertical-align: top;
    z-index: 10;
    pointer-events: auto;
}
 .md-block1:not(.txtareaDV) label:not(.md-no-float):not(.md-container-ignore) md-icon {
    color: inherit;
    font-size: 16px;
    margin: 0;
    width: auto;
    height: auto;
    vertical-align: text-top;
    min-width: 23px;
}

/*internet explorer scrollbalken*/
body{
  scrollbar-base-color: #C0C0C0;
  scrollbar-base-color: #C0C0C0;
  scrollbar-3dlight-color: #C0C0C0;
  scrollbar-highlight-color: #C0C0C0;
  scrollbar-track-color: #EBEBEB;
  scrollbar-arrow-color: black;
  scrollbar-shadow-color: #C0C0C0;
  scrollbar-dark-shadow-color: #C0C0C0;
}
/*mozilla scrolbalken*/
@-moz-document url-prefix(http://),url-prefix(https://) {
scrollbar {
   -moz-appearance: none !important;
   background: rgb(0,255,0) !important;
}
thumb,scrollbarbutton {
   -moz-appearance: none !important;
   background-color: rgb(0,0,255) !important;
}

thumb:hover,scrollbarbutton:hover {
   -moz-appearance: none !important;
   background-color: rgb(255,0,0) !important;
}

scrollbarbutton {
   display: none !important;
}

scrollbar[orient="vertical"] {
  min-width: 15px !important;
}
}
/*Scrollbar css*/
::-webkit-scrollbar-track
{
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
    border-radius: 10px;
    background-color: #eee;
}
::-webkit-scrollbar {
    width: 7px;
    background-color: #F5F5F5;
    padding-right: 2px;
}
::-webkit-scrollbar-thumb {
    border-radius: 10px;
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
    background-color: #f2f2f2;
}
::-webkit-scrollbar-thumb:hover {
    background-color: #b7b7b7;
}
button.button
{
      background: #ffc490;
      border:  #ffc490;
}
.btn-secondary:hover
{
      background: #ffab60;
      border:  #ffc490;
}
.btn-secondary-round
{
  border:2px solid   #3e4855;
     
}

/*Scrollbar css*/
 /*
#sidebar-menu {
    line-height:4em;
    position: relative;
}*/
   /*  //   box-shadow: 0 0 6px #ccc;*/

#sidebar-menu::after {
    content: ' ';
    width: 2px;
    height: 100%;
    background-color: #ccc;
    display: block;
    position: absolute;
    top: 0;
    right: 0;
}
span.count
{
  height: 15px;
  width: 25px;
  color: #fff;
  background: #ffab60;
  border-radius: 50%;
  margin: 0 0 0 14%;
  text-align: center;
  font-size: 11px;
  cursor:pointer;
}
/*#page-sidebar #sidebar-menu li a:hover span.count
{
    background-color: #ffab60;
}
#page-sidebar #sidebar-menu li a:hover
{
    color:#ffab60;
}*/
/*#page-sidebar #sidebar-menu li a.selected span.count
{
    background-color: #ffab60;
}
#page-sidebar #sidebar-menu li a.selected
{
    color:#ffab60;
}*/
.table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>th {
    color: #f5f8fb;
    background-color: #999;
    }
    .progress{
        background: #eee;
    padding: 1px 3px 3px;
    font-size: 12px;
    color: #888;
    border-radius: 0;
    }

element.style {
}
*, ::after, ::before {
    box-sizing: border-box;
}
user agent stylesheet
div {
    display: block;
} 
.popover {
    border-top:3px solid !important;
    border-top-color: #ffab60 !important;
    }
.popover.bs-popover-auto[x-placement^=bottom] .arrow::before, .popover.bs-popover-bottom .arrow::before {
    top: -.8rem;
    border-bottom-color: #ffab60 !important;
}
.popover.bs-popover-auto[x-placement^=bottom] .arrow::after, .popover.bs-popover-auto[x-placement^=bottom] .arrow::before, .popover.bs-popover-bottom .arrow::after, .popover.bs-popover-bottom .arrow::before {
    margin-left: 3.5rem;
    }
.popover.bs-popover-auto[x-placement^=bottom], .popover.bs-popover-bottom {
    margin-top: .8rem;
    left: -48px !important;
  /* width: 28%;*/
    height: 30%;
}
.popover.popover-footer {
  margin: 0;
  padding: 8px 14px;
  font-size: 14px;
  font-weight: 400;
  line-height: 18px;
  background-color: #F7F7F7;
  border-top: 1px solid #EBEBEB;
}
.fa-pie-chart .dispaly {
  display: block;
  font-size: 11px;
  padding-right: 1.5px;
      
}
.fa-folder-open .display {
  display: block;
  font-size: 11px;
  padding-left:1.5px;
      
}
.round {
    width: 115%;
    border-radius: 20px;
    border: 1px #0000004a solid;
    padding: 5px 5px 5px 25px;
    position: absolute;
    top: 0;
    left: 0;
    z-index: 5;
    height: 25px;
    left:10px;
}

.search {
    position: relative;
    width: 160px;
    height: 30px;
}


</style>

    @parent
    @if(Session::get('organization_id'))
    @if (App\Organization::checkModuleExists('wfm', Session::get('organization_id')))

    <?php 
        $plan =['Free14Days','Starter','Lite','Standard','Professional','Enterprise','Corporate'];  
         
    ?>

        @if(App\Organization::checkPlan($plan, Session::get('organization_id')))

            <?php
                $plan_name = App\Organization::checkPlan($plan, Session::get('organization_id'),$return_plan=true);
            ?>

            @include('includes.wfm_free')
            @include('includes.wfm_starter')
            @include('includes.wfm_lite')
            @include('includes.wfm_standard')
            @include('includes.wfm_professional')
            @include('includes.wfm_enterprise')
            @include('includes.wfm_corporate')


        @endif  
          

                    @endif
                @endif
        @stop

        @section('dom_links')
          
        @parent


<script>
$(document).ready(function(){   
     $('[data-toggle="popover"]').popover(); 

});
$('.count').on('click',function(){
    var id = $(this).attr('data-popup-id'); 
      //$(this).not($("#"+id)).popover('hide'); 
      $('[data-toggle="popover"]').not(this).popover('hide');
})
var isVisible = false;
var clickedAway = false;

$('.chart').on('click',function(){
   });
</script>
            @stop

            
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>AirmedLabs</title>
        <link rel="stylesheet" href="<?php echo base_url(); ?>user_assets/jquery.mobile/jquery.mobile.icons-1.4.5.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>user_assets/jquery.mobile/theme-classic.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>user_assets/jquery.mobile/jquery.mobile.structure-1.4.5.min.css">
        <link href="<?php echo base_url(); ?>user_assets/jquery.mobile/bootstrap.min.css" rel="stylesheet" type="text/css" />
		
		<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" rel="stylesheet" />

        <script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
        <script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/jquery.mobile/bootstrap-datepicker.min.js"></script>
        
        <style>
            .full_bg{background: rgba(0,0,0,0.3); width:100%; height:100%;padding-bottom:100%;padding:250px 0; position:relative; z-index:9; top:0; bottom:0;}

            .loader {width: 70px;height: 70px;margin: 0 auto;}
            .full_bg .loader img{width:70px; height:70px;}
            .text_highlight:hover{
                text-decoration: underline;
                /*font-weight: bold;
                font-size: 12px;*/
            }	
            .h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6 {
                /* font-family: inherit; */
                font-weight: 0px;
                /* line-height: 1.1; */
                /* color: inherit; */
            }

        </style>
    </head>
    <body>
        <div class="full_bg" style="display:none;" id="loader_div">
            <div class="loader">
                <img src="http://static.heart.org/riskcalc/app/assets/img/loading.gif">
            </div>
        </div>

        <div data-role="page" class="ui-page-theme-b" >

            <div data-role="panel" data-position="right" id="panel-reveal">
                <ul data-role="listview">
                    <li><a href="<?= base_url() . "pathologist/Dashboard/patientlist"; ?>" data-ajax="false" data-role="button">Home</a></li>
					 <li><a href="<?= base_url() . "pathologist/Dashboard"; ?>" data-ajax="false" data-role="button">All Bookings</a></li>
                    <li><a href="javascript:void(0)" id="pagelogout" data-role="button">Log out</a></li>
                </ul>

            </div>

            <script>

                $(document).on('vclick', '#pagelogout', function () {
                    $.ajax({
                        url: "<?php echo base_url(); ?>pathologist/Login/logout",
                        type: "GET",
                        beforeSend: function () {
                            $("#loader_div").show();
                        },
                        success: function (data) {
                            window.location = '<?= base_url() . "pathologist/login" ?>';

                        },
                        complete: function () {
                            $("#loader_div").hide();
                        },
                    });

                    return false;
                });

                $(document).on('vclick', '.checkjobstatus', function () {
                    var statusid = $(this).attr('id');
                    if (statusid == 1) {
                        $("#alertstatus").html("<p>Test already approved</p>");
                    } else {
                        $("#alertstatus").html("<p>Result not uploaded</p>");
                    }
                    $("#alertstatus").popup("open");
                });

                /* $(document).ready(function(){
                 $("#pagelogout").on("click",function(){
                 
                 $.ajax({
                 url: "<?php echo base_url(); ?>pathologist/Login/logout",
                 type: "GET",
                 beforeSend: function () {
                 $("#loader_div").show();
                 },
                 success: function (data) {
                 window.location = '<?= base_url() . "pathologist/login" ?>';
                 
                 },
                 complete: function () {
                 $("#loader_div").hide();
                 },
                 });
                 
                 return false;
                 
                 });
                 }); */

function backevent(){
	parent.history.back();
	
		 /* var href = $("#backurl").attr('href');
		alert(href);
		return false;
		if(href){ window.location = href; } */
	}

            </script>
            <script>

                // jquery document ready
               
            </script>
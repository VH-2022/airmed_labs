<div class="modal2" id="arrowLoader" style="display:none;"></div>
<style>
    .modal2 {
        position: fixed;
        z-index: 1000;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        background: rgba(0, 0, 0, .5) url('<?php echo base_url(); ?>user_assets/images/loader12.gif') 50% 50% no-repeat;
    }
    .select2.select2-container.select2-container--default { 
        left: 15px;
        position: absolute;
        top: 10px;
        -- z-index: 999;width:160px !important;}
    #select2-tst_city_list-container{text-transform:uppercase; background:url('<?php echo base_url(); ?>user_assets/images/loc.png') no-repeat 0%;padding-left: 25px !important;}
    .subtitle.text-center{text-transform:uppercase; color:#333!important}
    .alert .close {position: unset !important; opacity: 1 !important;}
    .alert .close:hover, .alert .close:focus {color: #7b7b7b !important; opacity: 1 !important;}
    .form_div{background:none}
    .indx_big_img{height:680px; background-position:100%;}
    .select2.select2-container.select2-container--default {top: 0; width: 160px !important;}
    /*  .select2-container .select2-selection--single {border-bottom: 1px solid #D01130; border-left: 1px solid #D01130; --border-radius: 20px 0 0 20px;border-radius: 0; border-top: 1px solid #D01130; box-shadow: 4px 3px rgba(0, 0, 0, 0.4); height: 45px;}*/
    .indx_srch_a{border-radius:0px;}
    .select2-container--default .select2-selection--single .select2-selection__rendered {line-height: 30px;}
    .select2-container--default .select2-selection--single .select2-selection__arrow {top: 9px;}
    #team{
        background:url(<?php echo base_url(); ?>user_assets/images/new/back-package.png); background-size:cover; padding:30px 0;background-repeat:no-repeat;
    }

    @media only screen and (max-width: 767px) {
        .select2-container--default .select2-selection--single .select2-selection__arrow {top: 13px;}
        #depertments .full-width{margin:0 !important}.col-sm-3.icon-content{background:none}
        .select2.select2-container.select2-container--default {position: relative !important; left: 0 !important; width: 100% !important;}.col-my-5{}
        .select2-container .select2-selection--single { border: 1px solid #d01130 !important;}
        ul.token-input-list-facebook {background-color: #fff !important;margin-top: 15px !important; border: 1px solid #d01130 !important;}
        .bnr_srch_deskpdng0 {padding-left: 15px; padding-right: 15px;}
        .select2-results__options{--border-radius: 10px;}
        .select2-dropdown{--border-radius: 10px !important;}
        #navMenuSecWrapper1{--border-radius: 10px;}
        .indx_srch_a{--border-radius:10px;border-left: 1px solid #000; box-shadow:none;}
        #team{background:none;}
        .select2-container--default .select2-selection--single .select2-selection__rendered {line-height: 45px !important;}
        .select2-container--default .select2-selection--single{ height: 45px;}
        .select2-container .select2-selection--single .select2-selection__rendered {padding-left: 45px;}


    }
    @media (min-width:768px) and (max-width:980px){
        .inpt_rgt_div li a span:first-child {width: 74%; float: left;}
        .select2.select2-container.select2-container--default {width: 134px !important;}
    }
    @media (min-width:980px)
    {.col-my-5{width:20% !important;  padding: 20px 0;}}
    #depertments{ background: rgba(255, 255, 255, 1) none repeat scroll 0 0 !important;}

    #depertments .full-width {width:100%;float:left;
                              background: rgba(255, 255, 255, 1) none repeat scroll 0 0 !important;
                              margin-top: -180px;box-shadow:1px 1px 5px #ddd;
    }
    .token-input-token-facebook.token-input-selected-token-facebook > p {
        color: #737374;
    }
</style>
<style>
    .full_bg{background: rgba(0,0,0,0.3); width:100%; height:100%; float:left; padding:350px; position:fixed; z-index:9; top:0; bottom:0;}
    .full_bg .loader img{width:70px; height:70px;}
    .border-search{ background-color: #fff; border: 1px solid #000; border-radius: 0; -- border-top: 1px solid #d01130; box-shadow: 1px 1px rgba(0, 0, 0, 0.4); clear: left;}
    .carousel-indicators{z-index:2;}
    #myCarousel	{height:680px;}	
    #myCarousel .carousel-inner{height:100%;}
    #myCarousel img	{height:680px !important;width:100%;}	

</style>
<div class="full_bg" style="display:none;" id="loader_div">
    <div class="loader">
        <center><img src="http://static.heart.org/riskcalc/app/assets/img/loading.gif"></center>
    </div>
</div>
<!-- Start main-content -->
<div class="main-content">
    <!-- Section: home -->

    <script type="text/javascript">
        $(document).ready(function () {
            //  $("#myModal_onload").modal('show');
        });
    </script>
    <div id="myModal_onload" class="modal fade" tabindex='-1'>
        <div class="modal-dialog home_mdl_dialog_cash">
            <div class="modal-content full_div text-center">
                <div class="modal-header full_div">
                    <button type="button" class="close" style="color: #000;" data-dismiss="modal" aria-hidden="true">�</button>
                    <h4 class="modal-title home_mdl_title">Get <?php echo $this->cash_back[0]["caseback_per"]; ?>% Cash Back On Online Booking</h4>
                </div>
                <div class="modal-body full_div">
                    <div class="col-sm-6">
                        <img src="<? echo base_url(); ?>user_assets/images/cash_back_30.png"/>
                    </div>
                    <div class="col-sm-6">
                        <p class="hm_onld_p_1"><span class="promo_span_clr">No Promocode Required.</span> <br/> Cashback will be applied directly when you book the test.</p>
                        <p class=""><span style="color: #3f897b; float: right">*</span> <span class="hm_onld_p_trms">Terms & Conditions Apply</span> </>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="happyNewYear" class="modal fade" tabindex='-1'>
        <div class="modal-dialog modal-md">
            <div class="modal-content full_div text-center">
                <div class="">
                    <button type="button" class="close" style="color: #000;margin-top:6px;margin-right:0px;" data-dismiss="modal" aria-hidden="true">x</button>
                </div>
                <div class="">
                    <img src="<?php echo base_url(); ?>img/happyNewYearBnanner.jpg" style="" class="img" alt="Happy New Year"> 
                </div>
            </div>
        </div>
    </div>
<script>
    /*setTimeout(function(){$('#happyNewYear').modal('show');},3000);*/
</script>
    <style>
        .border-search{ background-color: #fff;
                        border: 1px solid #000;
                        border-radius: 0;
                        -- border-top: 1px solid #d01130;
                        box-shadow: 1px 1px rgba(0, 0, 0, 0.4);
                        clear: left;}
        </style>
        <section id="home">
           <!--<div class="indx_big_img" style="background-image: url('<?php echo base_url(); ?>upload/<?php echo $set_setting[0]['index_banner']; ?>');">-->
        <div id="myCarousel" class="carousel slide" data-ride="carousel">
            <!-- Indicators -->

            <!-- Wrapper for slides -->
            <div class="carousel-inner">
                <div class="item active">
                    <img src="<?php echo base_url(); ?>user_assets/images/bg/slider02.jpg" alt=""  class="slide-image"/>
                </div>

                <div class="item">
                    <img src="<?php echo base_url(); ?>user_assets/images/bg/slider04.jpg" alt=""  class="slide-image"/>
                </div>

                <div class="item">
                    <img src="<?php echo base_url(); ?>user_assets/images/bg/slider01.jpg" alt=""  class="slide-image"/>
                </div>
            </div>

            <!-----Left and right controls 
            <a class="left carousel-control" href="#myCarousel" data-slide="prev">
              <span class="glyphicon glyphicon-chevron-left"></span>
              <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control" href="#myCarousel" data-slide="next">
              <span class="glyphicon glyphicon-chevron-right"></span>
              <span class="sr-only">Next</span>------->
            </a>
        </div>

        <div class="main_home_srch animated fadeInDown">
            <div class="container">
                <?php if ($success[0] != NULL) { ?>
                    <div class="alert alert-success alert-dismissable" style="margin-top: 20px;">
                        <a aria-hidden="true" data-dismiss="alert" class="close">?</a>
                        <?php echo $success['0']; ?>
                    </div>
                <?php } ?>
                <div class="col-sm-12 res_pdng_0">
                    <div class="col-sm-10 col-sm-offset-1 form_div" id="wrapper1" style="display:none;"> 
                       <!-- <p class="indx_mdl_big_p">Find your Tests and Packages</p>-->
                        <div class="">
                            <h1>India's Fastest Growing Pathology Labs </h1>
                            <div class="col-sm-10 pdng_0 border-search">
                                <div class="col-sm-4 col-md-3 bnr_srch_deskpdng0">
                                    <select class="form-controll" id="tst_city_list" onchange="get_packages(this.value);">
                                        <i class="fa fa-map-marker"></i>
                                        <?php foreach ($test_cities as $key) { ?>
                                            <option value="<?= $key["id"] ?>" <?php
                                            if ($test_city_session != '') {
                                                if ($test_city_session == $key["id"]) {
                                                    echo "selected";
                                                }
                                            }
                                            ?>><?= ucfirst($key["name"]) ?></option>
                                                <?php } ?>
                                    </select>
                                </div>
                                <div class="col-sm-8 col-md-9 bnr_srch_deskpdng0 ">
                                    <div id="searchbar" class=" menuBtn" placeholder="Enter test name" onclick="add_class();">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-2 fnd_pdng_lft_0">
                            <a href="#" class="indx_srch_a" onclick="get_select_value();"><span id="btn_search">Search</span></a>
                        </div>
                    </div>
                    <div class="col-sm-12 res_pdng_0">
                        <div class="choose_pkg_srch">
                            <ul>
                                <li>
                                    <a href="<?php echo base_url(); ?>user_master/all_packages">Choose from Popular Packages</a>
                                </li>
                                <li>|</li>
                                <li>
                                    <a href="<?php echo base_url(); ?>user_master/about_us">Know about Airmed Labs</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="call_health_adviser">
                            <ul>
                                <li>
                                    <button type="button" class="btn btn-dark btn-theme-colored btn-flat helth_advisor" data-toggle="modal" data-target="#myModal1"><img src="<?php echo base_url(); ?>user_assets/images/bg/hp-call-icon.png">Call To Book Test</button>
                                </li>
                                <li>
                                    <button onclick="open_browse();" type="button" class="btn btn-dark btn-theme-colored btn-flat upload_prec" data-toggle="modal" data-target="#myModal">Book Test from Prescription</button>
                                </li>

                            </ul>
                        </div>
                        <!----<div class="span-my"> <h1>OR</h1> </div>
                        <div  id="after_depertments" class="container res_dsply_none">
                            <div class="section-content "> 
                                <div class="row">
                                    <center> <a href="javascript:void(0);" onclick="$('#myModal').modal('show');open_browse();">
                                            <img src="<?php echo base_url(); ?>user_assets/images/rx_img.png"></a>
                                    </center>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="col-sm-3 col-sm-offset-5 set_sm_ofset_5" id="up_pre">

                                <input onclick="open_browse();" type="button" class="btn btn-dark btn-theme-colored btn-flat upload_prec" value="Upload Prescription " data-toggle="modal" data-target="#myModal"/>
                                <div class="modal fade" id="myModal" role="dialog">
                                    <div class="modal-dialog">
                                        <div class="modal-content srch_popup_full">
                                            <div class="modal-header srch_popup_full srch_head_clr">
                                                <button  type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title clr_fff" >Upload Prescription</h4>
                                            </div>
                                            <form action="<?php echo base_url(); ?>user_master/home_upload_prescription" method="post" enctype="multipart/form-data" id="uploadform"/>
                                            <input type="hidden" name="city" id="tst_city">
                                            <div class="modal-body srch_popup_full">
                                                <div class="uplod_prec_full">
                                                    <div class="col-sm-12 pdng_0 full_div">
                                                        <label class="pull-left full_div text-left">Mobile No.<span style="color:red;">*</span></label>
                                                        <div class="input-group">
                                                            <span class="input-group-addon pkgdtl_spn_91">+91</span>
                                                            <input class="srch_pop_inpt nobrdr_rds_tplft decimal" type="text" name="mobile" <?php
                        if (isset($user->full_name)) {
                            echo "readonly='readonly'";
                        }
                        ?> id="mobile" placeholder="Mobile"  value="<?php
                        if (isset($user->full_name)) {
                            echo $user->mobile;
                        }
                        ?>" onchange="validmobile()"/>
                                                        </div>
                                                        <div id="error_mobile" style="color:red; float: left;"></div>
                                                    </div>
                                                    <div class="col-sm-12 pdng_0 full_div">
                                                        <label class="pull-left full_div text-left">Description <span style="color:red;">*</span></label>
                                                        <textarea class="upld_txtara" required placeholder="Description" id="desc" name="desc"></textarea>
                                                        <div id="error_desc" style="color:red; float: left;"></div>
                                                    </div>
                                                    <div class="col-sm-12 pdng_0 full_div">
                                                        <label class="pull-left col-sm-12 text-left pdng_0 full_div">Upload Document <span style="color:red;">*</span></label>
                                                        <div class="input-group">
                                                            <span class="input-group-btn">
                                                                <span class="btn btn-primary btn-file upld_btm">
                                                                    Browse&hellip;
                                                                    <input type="file" name="userfile" id="id_browse">
                                                                </span>
                                                            </span>
                                                            <input type="text" id="file_name" class="form-control upld_inpt" readonly>

                                                        </div>
                                                        <div id="error_file" style="color:red; float: left;"></div>
                                                    </div>
                                                   
                                                    <div class='col-sm-12 pdng_0 full_div'>
                                                        <br>
                                                        <script src='https://www.google.com/recaptcha/api.js'></script>
                                                        <div class="g-recaptcha" data-callback="recaptchaCallback" data-sitekey="6Ld5_x8UAAAAAPoCzraL5sfQ8nzvvk3e5EIC1Ljr"></div>
                                                        <spam id="captch_error" style="color:red;"></spam>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer uplod_prec_full">
                                                <button type="button" onclick='vlidation_btn();' class="btn btn-default" >Upload</button>
                                            </div>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>------>
                    </div>

                </div>
            </div>
        </div>
        <div class="modal fade" id="myModal1" role="dialog">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="text">Mobile No.:</label>
                                <input type="text" id="get_inquiry_id" class="form-control">
                                <div id="get_inquiry_id_error"></div>
                            </div>
                            <div class="form-group">
                                <button type="button" id="get_inquiry_id_error_btn" onclick="get_inquiry();" class="btn btn-default">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content srch_popup_full">
                    <div class="modal-header srch_popup_full srch_head_clr">
                        <button  type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title clr_fff" >Upload Prescription</h4>
                    </div>
                    <form action="<?php echo base_url(); ?>user_master/home_upload_prescription" method="post" enctype="multipart/form-data" id="uploadform"/>
                    <input type="hidden" name="city" id="tst_city">
                    <div class="modal-body srch_popup_full">
                        <div class="uplod_prec_full">
                            <div class="col-sm-12 pdng_0 full_div">
                                <label class="pull-left full_div text-left">Mobile No.<span style="color:red;">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-addon pkgdtl_spn_91">+91</span>
                                    <input class="srch_pop_inpt nobrdr_rds_tplft decimal" type="text" name="mobile" <?php
                                    if (isset($user->full_name)) {
                                        echo "readonly='readonly'";
                                    }
                                    ?> id="mobile" placeholder="Mobile"  value="<?php
                                           if (isset($user->full_name)) {
                                               echo $user->mobile;
                                           }
                                           ?>" onchange="validmobile()"/>
                                </div>
                                <div id="error_mobile" style="color:red; float: left;"></div>
                            </div>
                            <div class="col-sm-12 pdng_0 full_div">
                                <label class="pull-left full_div text-left">Description <span style="color:red;">*</span></label>
                                <textarea class="upld_txtara" required placeholder="Description" id="desc" name="desc"></textarea>
                                <div id="error_desc" style="color:red; float: left;"></div>
                            </div>
                            <div class="col-sm-12 pdng_0 full_div">
                                <label class="pull-left col-sm-12 text-left pdng_0 full_div">Upload Document <span style="color:red;">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <span class="btn btn-primary btn-file upld_btm">
                                            Browse&hellip;
                                            <input type="file" name="userfile" id="id_browse">
                                        </span>
                                    </span>
                                    <input type="text" id="file_name" class="form-control upld_inpt" readonly>

                                </div>
                                <div id="error_file" style="color:red; float: left;"></div>
                            </div>

                            <div class='col-sm-12 pdng_0 full_div'>
                                <br />
                               <script src='https://www.google.com/recaptcha/api.js'></script>
                                <div class="g-recaptcha" id="RecaptchaField_2" data-callback="recaptchaCallback" data-sitekey="6Ld5_x8UAAAAAPoCzraL5sfQ8nzvvk3e5EIC1Ljr"></div>
                                <spam id="captch_error" style="color:red;"></spam>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer uplod_prec_full">
                        <button type="button" onclick='vlidation_btn();' class="btn btn-default" >Upload</button>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </section>
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">AIRMED PATHOLAB</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php echo $payment_success[0]; ?>
                    <div style="width:100%;text-align:center;"><img src="<?php echo base_url(); ?>user_assets/right-images.jpg"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Section: Departments  -->
    <section id="depertments" class="bg-lighter parlx_back">
        <div class="container indx_4_img">
            <div class="section-content "> <!--col-md-10 col-md-offset-1-->
                <div class="row indx_round_div">

                    <div class="full-width">
                        <div class="col-md-2 col-sm-6 col-xs-6">
                            <div class="icon-box text-center">
                                <div class="col-md-12 col-sm-3 icon-content focus res_indx_cl4_img">
                                    <a href="javascript:" class="icon bg-theme-colored icon-circled icon-border-effect effect-circled icon-md hm_mdl_247_a four_img_hvr a_247_res_768media">
                                        <img class="" src="<?php echo base_url(); ?>user_assets/images/new/hometreat-new.png">
                                    </a>
                                </div>
                                <div class="col-md-12 col-sm-9 icon-content focus four_img_mrgntop_20px_768 pdng_0">
                                    <span class="icon-box-title res_indx_cl8_title res_focussed new_res_247_spn1_full"><a href="javascript:">On Time<br/>Home visits</a></span>
                                   <!-- <span class="dark_spn new_res_247_spn2_full">24 x 7</span>-->
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-6 col-xs-6">
                            <div class="icon-box text-center">
                                <div class="col-md-12 col-sm-3 icon-content blood res_indx_cl4_img">
                                    <a href="javascript:" class="icon bg-theme-colored icon-circled icon-border-effect effect-circled icon-md four_img_hvr img_lctn_res a_lctn_res_768media">
                                        <img class="lctn_img " src="<?php echo base_url(); ?>user_assets/images/new/fastestturn-new.png">
                                    </a>
                                </div>
                                <div class="col-md-12 col-sm-9 icon-content blood four_img_mrgntop_20px_768 pdng_0">
                                    <span class="icon-box-title res_indx_cl8_title res_480_loctn"><a href="javascript:" style="">Fastest<br/>Turn Around Time</a></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-6 col-xs-6">
                            <div class="icon-box text-center">
                                <div class="col-md-12 col-sm-3 icon-content weare res_indx_cl4_img">
                                    <a href="javascript:" class="icon bg-theme-colored icon-circled icon-border-effect effect-circled icon-md four_img_hvr img_hndmbl_res a_hndmbl_res_768media">
                                        <img class="logo_img " src="<?php echo base_url(); ?>user_assets/images/new/painless-new.png">
                                    </a>
                                </div>
                                <div class="col-md-12 col-sm-9 icon-content weare icon-title four_img_mrgntop_20px_768 pdng_0">
                                    <span class="icon-box-title fast_dlvr_txt res_indx_cl8_title" style=""><a href="javascript:">Painless <br/> Testing</a></span>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-6 col-xs-6">
                            <div class="icon-box text-center">
                                <div class="col-md-12 col-sm-3 icon-content weare res_indx_cl4_img">
                                    <a href="javascript:" class="icon bg-theme-colored icon-circled icon-border-effect effect-circled icon-md four_img_hvr img_hndmbl_res a_hndmbl_res_768media">
                                        <img class="logo_img " src="<?php echo base_url(); ?>user_assets/images/new/icon4-new.png">
                                    </a>
                                </div>
                                <div class="col-md-12 col-sm-9 icon-content weare icon-title four_img_mrgntop_20px_768 pdng_0">
                                    <span class="icon-box-title fast_dlvr_txt res_indx_cl8_title" style=""><a href="javascript:">Accurate  <br/> Test Results</a></span>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-6 col-xs-6 res_for_bookng">
                            <div class="icon-box text-center">
                                <div class="col-md-12 col-sm-3 icon-content forbook res_indx_cl4_img">
                                    <a href="tel:+91 70 43 21 50 52" class="icon bg-theme-colored icon-circled icon-border-effect effect-circled icon-md four_img_hvr img_ringphn_res a_ringphn_res_768media">
                                        <img class="" src="<?php echo base_url(); ?>user_assets/images/new/24x4-new.png">
                                    </a>
                                </div>
                                <div class="col-md-12 col-sm-9 icon-content forbook four_img_mrgntop_20px_768 pdng_0">
                                    <span class="icon-box-title res_indx_cl8_title res_for_bookng_h3 new_res_247_spn1_full"><a href="javascript:">Round The clock <br/>Assistance</a></span>
                                    <!--<span class="dark_spn new_res_247_spn2_full" style="">+91 8101161616</span>-->
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-6 col-xs-6 res_for_bookng">
                            <div class="icon-box text-center">
                                <div class="col-md-12 col-sm-3 icon-content forbook res_indx_cl4_img">
                                    <a href="javascript:" class="icon bg-theme-colored icon-circled icon-border-effect effect-circled icon-md hm_mdl_247_a four_img_hvr a_247_res_768media">
                                        <img class="" src="<?php echo base_url(); ?>user_assets/images/new/blood-report-new.png">
                                    </a>
                                </div>
                                <div class="col-md-12 col-sm-9 icon-content forbook four_img_mrgntop_20px_768 pdng_0">
                                    <span class="icon-box-title res_indx_cl8_title res_for_bookng_h3 new_res_247_spn1_full"><a href="javascript:">Manage & Maintain<br>Blood Reports</a></span>
                                    <!--<span class="dark_spn new_res_247_spn2_full" style="">+91 8101161616</span>-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="border-btn mt-30"><span></span></div>
    <section class="sctn_full">
        <div class="container">
            <div class="section-content book_test">
                <div class="row multi-row-clearfix">
                    <div class="logistic_it_pltfrm_title">
                        <h1 class="subtitle text-center txt_blue_clr" style="margin-bottom: 25px;color:#333;">Strong Logistics & IT Platform</h1>
                    </div>
                    <div class="col-md-12 col-sm-12">

                        <div class="col-md-4 col-sm-4">
                            <div class="logistic_it_pltfrm">
                                <h2>Technology</h2>
                                <img src="<?php echo base_url(); ?>user_assets/images/home/banner_img2.jpg" />
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <div class="logistic_it_pltfrm">
                                <h2>Lowest tat</h2>
                                <img src="<?php echo base_url(); ?>user_assets/images/home/banner_img3.png" />
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <div class="logistic_it_pltfrm">
                                <h2>Trained Logistics Team</h2>
                                <img src="<?php echo base_url(); ?>user_assets/images/home/banner_img1.jpg" />
                            </div>
                        </div>

                    </div>
                </div>
            </div>
		 </div>
		 </section>
		 <div class="border-btn mt-30"><span></span></div>
    <section class="sctn_full">
<div class="container">
                
		<div class="section-content book_test">
                <div class="row multi-row-clearfix">
                    <div class="logistic_it_pltfrm_title">
                        <h1 class="subtitle text-center txt_blue_clr" style="margin-bottom: 25px;color:#333;">Accreditations</h1>
                    </div>
                    <div class="col-md-12 col-sm-12">

                        <div class="col-md-4 col-sm-4">
                          
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <div class="logistic_it_pltfrm">
                             </br>
                               <img src="<?php echo base_url(); ?>user_assets/images/nabl_logo.png" alt="">
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4">
                           
                        </div>

                    </div>
                </div>
            </div>
		
		</div>
    </section>
    <script>
        function get_packages(city_fk) {
            /* $.ajax({
             url: '<?= base_url(); ?>User_master/package_list',
             type: 'post',
             tryCount: 0,
             retryLimit: 3,
             data: {city: city_fk},
             success: function (data) {
             $("#packages").html(data);
             },
             error: function (xhr, textStatus, errorThrown) {
             if (textStatus == 'timeout') {
             this.tryCount++;
             if (this.tryCount <= this.retryLimit) {
             //try again
             $.ajax(this);
             return;
             }
             return;
             }
             if (xhr.status == 500) {
             //handle error
             } else {
             //handle error
             }
             }
             }); */
            get_packages1(city_fk);
        }

        function get_packages1(city_fk) {
            var d = new Date();
            var n = d.getTime();
            $.ajax({
                url: '<?= base_url(); ?>User_master/package_list1?' + n,
                type: 'post',
                tryCount: 0,
                cache: false,
                retryLimit: 3,
                data: {city: city_fk},
                beforeSend: function () {
                    $("#loader_div").attr("style", "");
                },
                success: function (result1) {

                    //  setTimeout(function () {
                    $("#searchbar").removeData("test1");
                    //$("#searchbar").empty();
                    $("#searchbar").html(Date());
                    $("#searchbar").empty();
                    $("#searchbar").html(result1);
                    $("#wrapper1").removeAttr("style");
                    //window.location.reload();
                    //   }, 1000);
                },
                error: function (xhr, textStatus, errorThrown) {
                    if (textStatus == 'timeout') {
                        this.tryCount++;
                        if (this.tryCount <= this.retryLimit) {
                            //try again
                            $.ajax(this);
                            return;
                        }
                        return;
                    }
                    if (xhr.status == 500) {
                        //handle error
                    } else {
                        //handle error
                    }
                }, complete: function () {
                    setTimeout(function () {
                        $("#loader_div").attr("style", "display:none;");
                    }, 2000);
                }
            });

        }

        var city_ids = $("#tst_city_list").val();
        get_packages(city_ids);
    </script>
    <section class="indx_mbl_ovrlay" style="padding-bottom:0;margin-bottom:0">
        <div class="container mbl_containr" style="padding-bottom:0">
            <div class="row">
                <div class="col-sm-12 pdng_0">
                    <div class="indx_mbl_mdl">
                        <?php /* <h1 class="mbl_title center">App Communication Space</h1>
                          - */ ?>
                        <div class="col-sm-4  col-xs-4">
                            <center><h2 ><strong  style="color:#bf2d37; font-weight:bold">BOOK</strong> <span style="color:#000">TEST</span></h2></center>
                            <img src="<?php echo base_url(); ?>user_assets/images/home/img1.png"/> 
                        </div>
                        <div class="col-sm-4 col-xs-4">
                            <center><h2><strong  style="color:#bf2d37; font-weight:bold">MANAGE</strong> <span style="color:#000">REPORTS</span></h2></center>
                            <img src="<?php echo base_url(); ?>user_assets/images/home/img2.png"/> 
                        </div>
                        <div class="col-sm-4 col-xs-4">
                            <center><h2><strong  style="color:#bf2d37; font-weight:bold">SHARE</strong> <span style="color:#000">REPORTS</span></h2></center>
                            <img src="<?php echo base_url(); ?>user_assets/images/home/img3.png"/> 
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="indx_mbl_ovrlay" style="  margin-bottom:0; background:#d7d7d7; background-repeat:no-repeat; ">
        <div class="container mbl_containr">
            <div class="row">
                <div class="col-sm-12" style="text-align:center;">
                    <div class="col-sm-1 col-xs-3 pdng_0 col-sm-offset-2 ">
                        <img src="<?php echo base_url(); ?>user_assets/images/new/icon-a.png"/> 
                    </div>
                    <div class="col-sm-7  col-xs-9 pdng_0 ">    <h1 class="mbl_title center" style="margin-top:0px; margin-bottom:0px;">DOWNLOAD AIRMED MOBILE APP<br/> & GET <b style="font-family: 'Montserrat', sans-serif;"><?php echo $this->cash_back[0]["caseback_per"]; ?>% CASH BACK</B> </h1>

                    </div>
                    <div class="clearfix"></div><br/>
                    <div class="col-sm-6  pdng_0 col-sm-offset-4">
                        <div class="col-sm-4">
                            <a href="https://itunes.apple.com/in/app/airmed-pathlabs/id1152367695?mt=8" target="_blank"><img class="app_full_img" src="<?php echo base_url(); ?>thumb_helper.php?h=53&w=173&src=user_assets/images/apple_appstore_big.png"/></a>
                        </div>
                        <div class="col-sm-4">
                            <a href="https://play.google.com/store/apps/details?id=com.patholab&hl=en" target="_blank"><img class="mbl_googl_res_mrgn app_full_img" src="<?php echo base_url(); ?>thumb_helper.php?h=54&w=173&src=user_assets/images/google_play.png"/></a>
                        </div>

                    </div>
                </div>
                <div class="border-btn mt-30"><span></span></div>
            </div>
        </div>
    </section>
    <section class="pdng_all_container ">
        <div class="container pb-30">
            <div class="">
                <h1 class="subtitle text-center " style="margin-bottom: 25px;"><span style="color:#bf2d37;">AIRMED EXPERIENCE</span></h1>
                <div class="row">
                    <div class="container" id="" style="style="background: #f1f2f3"">    
                         <div class="row">
                            <div class="col-md-12 column">    
                                <div class="carousel slide" id="testimonials-rotate">
                                    <ol class="carousel-indicators tstml_indictr">
                                        <li class="active" data-slide-to="0" data-target="#testimonials-rotate">
                                        </li>
                                        <li data-slide-to="1" data-target="#testimonials-rotate">
                                        </li>
                                        <li data-slide-to="2" data-target="#testimonials-rotate">
                                        </li>
                                    </ol>
                                    <div class="carousel-inner">
                                        <?php
                                        $cnt = 0;
                                        foreach ($testimonial as $key) {
                                            ?>
                                            <div class="item<?php
                                            if ($cnt == 0) {
                                                echo " active";
                                            }
                                            ?>">    
                                                <div class="col-md-3 col-sm-3 txt-center">
                                                    <img alt="" src="<?php echo base_url(); ?>thumb_helper.php?h=200&w=200&src=upload/<?php echo $key['image']; ?>" class="img-circle img-responsive"/>
                                                    <h3><?php echo $key['name']; ?>, <?php echo $key['address']; ?></h3>

                                                </div>
                                                <div class="testimonials col-md-9 col-sm-9">
                                                    <img src="<?php echo base_url(); ?>user_assets/images/new/qoute-start.png" style="width:40px">
                                                    <h1 style="--text-align:center"> <?php echo $key['title']; ?> </h1>
                                                    <h3>
                                                        <div class="col-sm-11 pdng_0">
                                                            <?php echo $key['description']; ?> <!--<small><a href="#" class="bg-primary">More</a> </small>--></div>
                                                        <div class="col-md-1 col-sm-9 tstmnl_rgt_qut"><img src="<?php echo base_url(); ?>user_assets/images/new/qoute-fisnish.png" class="pull-right"></div>
                                                    </h3>

                                                </div>

                                                <div class="clearfix"></div>
                                            </div>
                                            <?php
                                            $cnt++;
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="testi-nav">
                                    <a class="left" href="#testimonials-rotate" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>
                                    <a class="right" href="#testimonials-rotate" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a><div class="clearfix"></div>  
                                </div>
                            </div>
                        </div>
                    </div><!--end of container-->
                    <style>
                        #testimonials-row{background:url(<?php echo base_url(); ?>user_assets/images/new/testi-back.png); background-position:center; background-size:contain; padding:80px 0;background-repeat:no-repeat}
                        #testimonials-rotate .txt-center{text-align:center}
                        #testimonials-rotate .img-circle{border:10px solid #d01130;padding:5px; float:none;margin:0 auto;width:165px}
                        .bg-primary {
                            background-color: #737373;

                            color: #fff;
                            padding: 3px 10px;
                        }
                        margin:0 auto
                        .testi-nav{position:absolute;z-index:999; width:100%; top:50%;}
                        .testi-nav .left{position:absolute;top:50%;left:-20px; font-size:30px; color:#737373}
                        .testi-nav .right{position:absolute;top:50%;right:-20px;font-size:30px; color:#737373}
                        .item .col-md-2{text-align:center}
                        .item .col-md-2 img{margin:0 auto;}

                        .testimonials h3{
                            margin-top:0px;
                        }
                        @media(max-width:768px)
                        {   #testimonials-row{background:none;}
                            #testimonials-rotate{padding:10px 40px; }
                            .testi-nav .left{left:20px;}
                            .testi-nav .right{right:20px;}

                        }

                    </style>

                    <div class="col-md-12 col-sm-12">
                        <?php foreach ($testimonial as $key) { ?>
                            <!--<div class="col-md-6 col-sm-6 dr_cmnt">
                                <div class="col-md-2 col-sm-4 dr_cmnt">
                                                                        <div class="testi_back_img">
                                                                                <img class="hlt_feed_img" src="<?php echo base_url(); ?>thumb_helper.php?h=50&w=50&src=upload/<?php echo $key['image']; ?>">
                                                                        </div>
                                </div>
                                <div class="col-md-10 col-sm-8 dr_cmnt">
                                    <a href="javascript:void(0)" ><span><b><?php echo $key['name']; ?></b></span></a>
                                    <p><?php echo $key['address']; ?></p>
                                </div>
                                <div class="col-md-12 col-sm-12 dr_cmnt">
                                    <p class=""><?php echo $key['description']; ?></p>

                                </div>
                            </div>-->
                            <!--<div class="col-md-6 col-sm-6 dr_cmnt">
                                <div class="btm_testi_quote_main">
                                    <div class="testi_back_img">
                                        <img class="hlt_feed_img" src="<?php echo base_url(); ?>thumb_helper.php?h=50&w=50&src=upload/<?php echo $key['image']; ?>">
                                    </div>
                                    <div class="btm_testi_name_div">
                                        <a href="javascript:void(0)" ><span><b><?php echo $key['name']; ?></b></span></a>
                                        <p><?php echo $key['address']; ?></p>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 dr_cmnt">
                                    <p class=""><?php echo $key['description']; ?></p>

                                </div>
                            </div>
                            -->



                        <?php } ?>
                        <div class="flexslider">
                            <ul class="slides">
                                <li>
                                    <div class="col-sm-3">
                                        <img src="<?php echo base_url(); ?>thumb_helper.php?h=455&w=229&src=user_assets/images/indx_mobile_1.png">
                                    </div>
                                    <div class="col-sm-9">
                                        "Your text here"
                                    </div>
                                </li>
                                <li>
                                    <img src="images/kitchen_adventurer_lemon.jpg" />
                                </li>
                                <li>
                                    <img src="images/kitchen_adventurer_donut.jpg" />
                                </li>
                                <li>
                                    <img src="images/kitchen_adventurer_caramel.jpg" />
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Payment_success start-->
        <div role="dialog" id="myModal_payment" class="modal fade">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">

                    <div class="modal-body">
                        <div class="col-sm-12">
                            <h4 style="width:-moz-max-content; float: left; text-align: center; width: 100%;" class="modal-title"><?= $payment_success[0]; ?></h4>
                        </div>
                        <div id="model_body">
                            <div style="width:100%;text-align:center;"><img src="<?php echo base_url(); ?>user_assets/right-images.jpg"></div>
                        </div>
                    </div>
                    <button style="display:none;" id="close_model" data-dismiss="modal" class="btn btn-primary" type="button"></button>
                    <div style="display:none;" id="save_btn" class="modal-footer">
                        <button onclick="frm_sbmt.click();" data-dismiss="modal" class="btn btn-primary" type="button">Save</button>
                        <button id="close_model" data-dismiss="modal" class="btn btn-danger" type="button">Cancel</button>

                    </div>
                </div>

            </div>
        </div>

        <div role="dialog" id="myModal_payment1" class="modal fade">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">

                    <div class="modal-body">
                        <div style="" class="col-sm-12">
                            <h4 style="width:-moz-max-content; float: left; text-align: center; width: 100%;" class="modal-title"><?= $payment_unsuccess[0]; ?></h4>
                        </div>
                        <div id="model_body">
                            <div style="width:100%;text-align:center;"><img src="<?php echo base_url(); ?>user_assets/warning.jpg" style="height:70px;"></div>
                        </div>
                    </div>
                    <button style="display:none;" id="close_model" data-dismiss="modal" class="btn btn-primary" type="button"></button>
                    <div style="display:none;" id="save_btn" class="modal-footer">
                        <button onclick="frm_sbmt.click();" data-dismiss="modal" class="btn btn-primary" type="button">Save</button>
                        <button id="close_model" data-dismiss="modal" class="btn btn-danger" type="button">Cancel</button>

                    </div>
                </div>

            </div>
        </div>
        <!--Payment success end-->
    </section>
</div>
<!-- end main-content -->
<!--<script src="<?php echo base_url(); ?>user_assets/js/jquery-2.2.0.min.js"></script>--> 
<script src="<?php echo base_url(); ?>user_assets/js/jquery-ui.min.js"></script>
<script src="<?php echo base_url(); ?>user_assets/js/bootstrap.min.js"></script>
<script type="text/javascript">
                            var CaptchaCallback = function () {
                                grecaptcha.render('RecaptchaField_2', {
                                    'sitekey': '6Ld5_x8UAAAAAPoCzraL5sfQ8nzvvk3e5EIC1Ljr'
                                });
                                //$('#send_btn').removeAttr('disabled');
                            };
</script>
<script>
    $tst = 0;
    function recaptchaCallback() {
        $('#send_btn').removeAttr('disabled');
        $tst = 1;
    }
    $('.decimal').keyup(function () {
        var val = $(this).val();
        if (isNaN(val)) {
            val = val.replace(/[^0-9\.]/g, '');
            if (val.split('.').length > 2)
                val = val.replace(/\.+$/, "");
        }
        $(this).val(val);
    })
    document.getElementById('id_browse').onchange = function () {
        $('#file_name').val(this.value);
    };
    $('#uploadform').submit(function () {
        $('#arrowLoader').show();
        return true;
    });

    function open_browse() {
        var city_ids = $("#tst_city_list").val();
        $("#tst_city").val(city_ids);
        document.getElementById('id_browse').click();
    }
<?php if ($payment_success[0] != '') { ?>
        $("#myModal_payment").modal('show');
        //setTimeout(function () {
        //   $("#myModal_payment").modal('show');
        //}, 3000);
        //setTimeout(function () {
        //    $("#myModal_payment").modal('hide');
        //}, 8000);
<?php } ?>
<?php if ($payment_unsuccess[0] != '') { ?>
        $("#myModal_payment1").modal('show');
        //setTimeout(function () {
        //    $("#myModal_payment1").modal('show');
        //}, 3000);
        //setTimeout(function () {
        //    $("#myModal_payment1").modal('hide');
        //}, 8000);
<?php } ?>
    emial_type = 0;
    function uploads() {
        var email = document.getElementById("email").value;
        if (checkemail(email) == true) {
            $('#error_email').html(" ");
            $.post("<?php echo base_url(); ?>user_master/check_email", {
                email: email
            }, function (data) {
                if (data == 'login') {
                    $("#login_password").show();
                    $("#register").hide();
                    emial_type = 'login';
                } else if (data == 'register') {
                    $("#register").show();
                    $("#login_password").hide();
                    emial_type = 'register';
                }
            });
        } else {
            emial_type = 0;
            $('#error_email').html("Invalid Email.");
        }
    }
    function validmobile() {
        var mobile = document.getElementById("mobile").value;
        if (mobile == '') {
            $('#error_mobile').html("Please Enter Mobile Number!");
        }
        if (checkmobile(mobile) == true) {
            $('#error_mobile').html(" ");
        } else if (mobile.length != 10) {
            emial_type = 0;
            $('#error_mobile').html('Enter Valid Number.');
        } else {
            emial_type = 0;
            $('#error_mobile').html("Invalid Mobile Number.");
        }
    }
    function vlidation_btn() {
        var all = [];
        $("#captch_error").html("");

        if (emial_type == 0) {
            $('#mobile').each(function () {
                var mobile = $(this).val();
                $('#error_mobile').html("");
                if (mobile == '') {
                    $('#error_mobile').html('Please Enter Mobile Number!');
                } else if (checkmobile(mobile) == false) {
                    $('#error_mobile').html('Invalid Number');
                } else if (mobile != '') {
                    $('#error_mobile').html(" ");
                } else if (mobile.length != 10) {
                    all = 1;
                    $('#error_mobile').html('Enter Valid Number.');
                } else {
                    all = 1;
                    $('#error_mobile').html("Mobile Number is required.");
                }
            });
            $('#desc').each(function () {
                var desc = $(this).val();
                $('#error_desc').html("");
                if (desc != '') {
                    $('#error_desc').html(" ");
                } else {
                    all = 1;
                    $('#error_desc').html("Description is required.");
                }
            });
            $('#id_browse').each(function () {
                var id_browse = $(this).val();
                $('#error_file').html("");
                if (id_browse != '') {
                    $('#error_file').html(" ");
                } else {
                    all = 1;
                    $('#error_file').html("Please Select File To Upload");
                }
            });
        }
        if (emial_type == 'login') {
            $('#mobile').each(function () {
                var mobile = $(this).val();
                $('#error_mobile').html("");
                if (mobile != '') {
                    $('#error_mobile').html(" ");
                } else {
                    all = 1;
                    $('#error_mobile').html("Mobile Number is required.");
                }
            });
            $('#desc').each(function () {
                var desc = $(this).val();
                $('#error_desc').html("");
                if (desc != '') {
                    $('#error_desc').html(" ");
                } else {
                    all = 1;
                    $('#error_desc').html("Description is required.");
                }
            });
            $('#id_browse').each(function () {
                var id_browse = $(this).val();
                $('#error_file').html("");
                if (id_browse != '') {
                    $('#error_file').html(" ");
                } else {
                    all = 1;
                    $('#error_file').html("Please Select File To Upload");
                }
            });
            $('#logpass').each(function () {
                var logpass = $(this).val();
                $('#error_logpass').html("");
                if (logpass != '') {
                    $('#error_logpass').html(" ");
                } else {
                    all = 1;
                    $('#error_logpass').html("Login Password is required.");
                }
            });
        }
        if (emial_type == 'register') {
            $('#mobile').each(function () {
                var mobile = $(this).val();
                $('#error_mobile').html("");
                if (mobile == '') {
                    $('#error_mobile').html('Please Enter Mobile Number!');
                } else if (checkmobile(mobile) == false) {
                    $('#error_mobile').html('Invalid Number');
                } else if (mobile != '') {
                    $('#error_mobile').html(" ");
                } else if (mobile.length != 10) {
                    all = 1;
                    $('#error_mobile').html('Enter Valid Number.');
                } else {
                    all = 1;
                    $('#error_mobile').html("Mobile Number is required.");
                }
            });
            $('#desc').each(function () {
                var desc = $(this).val();
                $('#error_desc').html("");
                if (desc != '') {
                    $('#error_desc').html(" ");
                } else {
                    all = 1;
                    $('#error_desc').html("Description is required.");
                }
            });
            $('#id_browse').each(function () {
                var id_browse = $(this).val();
                $('#error_file').html("");
                if (id_browse != '') {
                    $('#error_file').html(" ");
                } else {
                    all = 1;
                    $('#error_file').html("Please Select File To Upload");
                }
            });
            $('#regpass').each(function () {
                var regpass = $(this).val();
                $('#error_regpass').html("");
                if (regpass != '') {
                    $('#error_regpass').html(" ");
                } else {
                    all = 1;
                    $('#error_regpass').html("Password is required.");
                }
            });
            $('#regname').each(function () {
                var regname = $(this).val();
                $('#error_regname').html("");
                if (regname != '') {
                    $('#error_regname').html(" ");
                } else {
                    all = 1;
                    $('#error_regname').html("Name is required.");
                }
            });
            $('#regconpass').each(function () {
                var regconpass = $(this).val();
                var regpass = $("#regpass").val();
                $('#error_regconpass').html("");
                if (regconpass != '') {
                    $('#error_regconpass').html(" ");
                } else if (regconpass != regpass) {
                    all = 1;
                    $('#error_regconpass').html("Password and Conform Password is Dont Match.");
                } else {
                    all = 1;
                    $('#error_regconpass').html("Conform Password is required.");
                }
            });
        }
        var mb_no = $("#mobile").val();
        if (checkmobile(mb_no) == false) {
            all = 1;
            $('#error_mobile').html('Enter Valid Number.');
        }
        if ($tst == 0) {
            $("#captch_error").html("Required");
            return false;
        }
        if (all != '1') {
            $("#uploadform").submit();
        } else {
            return false;
        }
    }
    function checkemail(mail) {
        var filter = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
        if (filter.test(mail)) {
            return true;
        } else {
            return false;
        }
    }
    function checkmobile(mobile) {
        var filter = /^[0-9-+]+$/;
        var pattern = /^\d{10}$/;
        if (filter.test(mobile)) {
            if (pattern.test(mobile)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function add_class() {
        /*setTimeout(function () {
         var old_style = document.getElementById("navMenuSecWrapper1");
         var css_property = old_style.style.cssText;
         css_property = css_property.trim();
         if (css_property == 'display: none;') {
         document.getElementById("searchbar").setAttribute("class", " menuBtn");
         } else {
         document.getElementById("searchbar").setAttribute("class", " menuBtn home_inpt_radus");
         }
         }, 400);
         setTimeout(function () {
         var old_style = document.getElementById("navMenuSecWrapper1");
         var css_property = old_style.style.cssText;
         css_property = css_property.trim();
         if (css_property == 'display: none;') {
         document.getElementById("searchbar").setAttribute("class", " menuBtn");
         } else {
         document.getElementById("searchbar").setAttribute("class", " menuBtn home_inpt_radus");
         }
         }, 500);
         setTimeout(function () {
         var css_property = old_style.style.cssText;
         css_property = css_property.trim();
         if (css_property == 'display: none;') {
         document.getElementById("searchbar").setAttribute("class", " menuBtn");
         } else {
         document.getElementById("searchbar").setAttribute("class", " menuBtn home_inpt_radus");
         }
         }, 600);
         //Nsihit chnage 26-8-16
         // if (jQuery("#searchbar").hasClass('home_inpt_radus')) {
         //jQuery("#searchbar").removeClass("home_inpt_radus");
         //} else {
         // 
         //jQuery("#searchbar").addClass("home_inpt_radus");
         //}*/

    }


    /* $(window).click(function () {
     //console.log("Nishit");
     setTimeout(function () {
     add_class();
     }, 400);
     setTimeout(function () {
     add_class();
     }, 500);
     setTimeout(function () {
     add_class();
     }, 600);
     });
     add_class();*/


    /*Nishit code start*/
    function get_inquiry() {
        var get_phone_no = $("#get_inquiry_id").val();
        $("#get_inquiry_id_error").html("");
        if (checkmobile(get_phone_no) == true) {
            $.ajax({
                url: "<?php echo base_url(); ?>user_master/save_inquiry",
                data: {phone: get_phone_no},
                type: "POST",
                beforeSend: function () {
                    $("#loader_div").attr("style", "");
                    $("#get_inquiry_id_error_btn").attr("disabled", "disabled");
                },
                success: function (data) {
                    $("#get_inquiry_id").val("");
                    $("#get_inquiry_id_error").html("<span style='color:green;'>Your inquiry successfully send.</span>");
                },
                complete: function () {
                    $("#loader_div").attr("style", "display:none;");
                    $("#get_inquiry_id_error_btn").removeAttr("disabled");
                },
            });
        } else {
            $("#get_inquiry_id_error").html("<span style='color:red;'>Invalid</span>");
        }
    }
    /*END*/
</script>
<script type="text/javascript">jssor_1_slider_init();</script>
<link href="<?php echo base_url(); ?>user_assets/js/bootstrap-touch-slider-min.js">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.touchswipe/1.6.18/jquery.touchSwipe.min.js"></script>
<script src="bootstrap-touch-slider.js"></script>

<script type="text/javascript">
    $('#bootstrap-touch-slider').bsTouchSlider();
</script>
<!-- Vishal Code Start -->
<?php if (isset($payment_success) != NULL) { ?>

    <script type="text/javascript">

        $('#exampleModal').modal('show');
    </script>
<?php } ?>
<!-- Vishal COde End -->
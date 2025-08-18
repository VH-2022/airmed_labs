<style>
    .border-search{ background-color: #fff;
                    border: 1px solid #000;
                    border-radius: 0;
                    -- border-top: 1px solid #d01130;
                    box-shadow: 1px 1px rgba(0, 0, 0, 0.4);
                    clear: left;}
    .indx_big_img {
        width: 100%;
        background-image: url(../images/new/home_1.png);
        background-attachment: fixed;
        background-size: cover;
        height: 480px !important;
        top: 10% !important;
        background-position: 0 80px;
    }
    #team{
        background:url(<?php echo base_url(); ?>user_assets/images/new/back-package.png); background-size:cover; padding:30px 0;background-repeat:no-repeat;
    }
    <!------.box-package{width:100%;float:left;position:relative; min-height:300px;	}----->


    .txt-box{width:100%;float:left;text-align:center; margin-bottom:15px;min-height:100px;max-height:150px;overflow:hidden;}
    .txt-box h3{margin: 10px 0 0 0;font-size: 17px;text-transform: uppercase;font-weight: 600;}
    .txt-box p{margin: 0; text-transform:uppercase;font-size: 18px;}
    .pdng_lft_0{padding-left:0}
    .pdng_rgt_0{padding-right:0}
    .red-label{position:absolute; right:3px;top:-5px;   z-index: 1;}
    .red-label p{background:#d01130;padding:2px;color:#fff;width:100px; text-align:center; font-size:18px;font-weight:600}
    .hover-over:hover{opacity:0.8;}
    section, footer{width:100%;float:left;}
</style>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<!-- Start main-content -->
<div class="main-content">
    <section id="about" class="package-div">


        <div class="gray-overlay" style="">

        </div>
        <div class="container" style="padding:0">
            <div class="row">
                <div class="col-md-4 col-sm-4">
                    <center>
                        <h1 class="font-40 font-w" style="line-height:38px; color:#BF2D37;">PACKAGES </h1>
                        <div class="border-btn">
                            <span></span>
                        </div>
                    </center>

                </div>
                <div class="col-sm-8">
                    <img src="<?= base_url(); ?>user_assets/images/new/inner-package.jpg">
                </div>

            </div>
        </div>


    </section>
    <!-- <section id="team_back_none" class="sctn_full">
        <div class="diff_box_containr">
            <div class="section-content">
                <div class="container">
                    <div class="col-sm-12 pdng_0">
                        <div class="col-md-9 col-sm-7">
                            <h3 class="text-3xl font-semibold mb-8 text-center">ALL PACKAGES</h3>
                            <div id="packages">

                            </div>

                            <div class="col-md-12 pdng_0">

                                <div class="row">
                                    <?php foreach ($package_array as $key1) { ?>
                                        <div class="col-md-4 pdng_0 ">
                                            <a href="<?= base_url(); ?>user_master/package_details/<?= $key1[0]["id"] ?>" class="hover-over">
                                                <div class="box-package ">
                                                    <div class="red-label"><p>Rs.<?= $key1[1][0]["d_price"] ?></p></div>
                                                    <div class="img-box">
                                                        <img src="<?php echo base_url(); ?>upload/package/<?= $key1[0]["image"] ?>">
                                                    </div>
                                                    <div class="txt-box">
                                                        <h3><?= $key1[0]["title"] ?></h3>
           

                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    <?php } ?>
                                </div>

                            </div>

                        </div>
                        <div class="col-md-3 col-sm-5">
                            <?php foreach($suggest_package as $key1){ ?>
                            <h2 class="subtitle text-center txt_blue_clr" style="margin-bottom: 25px;COLOR:#000"><?= ucfirst($key1["name"])?></h2>
                           
                            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                <?php foreach($key1["package"] as $p_key1){ ?>
                                <div class="panel panel-default">
                                    <div class="panel-heading pt_pb_0 all_pckgs_test_prz" role="tab" id="headingOne">
                                        <h4 class="panel-title plus_sign_none">
                                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne<?=$p_key1["id"]?>" aria-expanded="true" aria-controls="collapseOne">
                                               <?= ucfirst($p_key1["title"]);?> - Rs.<?=$p_key1["d_price"]?>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseOne<?=$p_key1["id"]?>" class="panel-collapse collapse all_pckgs_test_prz" role="tabpanel" aria-labelledby="headingOne">
                                        <div class="panel-body">
                                            <ul class="inner-line">
                                                <?php foreach($p_key1["test_list"] as $t_key1){ ?>
                                                <li>- <?=ucfirst($t_key1["test_name"])?>  </li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                         <center>
                                                <a href="<?=base_url();?>user_master/package_details/<?=$p_key1["id"]?>" class="btn btn-dark btn-theme-colored btn-flat mb-30 ">Book</a>
                                        </center>
                                    </div>
                                </div>
                                <?php } ?>
                              
                            </div>
                            
                            <?php } ?>
                         
                        </div>
                    </div>
                    <div class="border-btn mt-30"><span style="background-color:black"></span></div>
                </div>
            </div>
        </div>
    </section> -->
    <section id="popular-tests" class="py-12 bg-white px-4">

        <div class="max-w-6xl mx-auto">

        <h3 class="text-3xl font-semibold mb-8 text-center">All Packages</h3>

        <div class="grid md:grid-cols-3 gap-6">

            <!-- Test Card Example -->
            <?php foreach ($suggest_package as $key1) {     foreach($key1["package"] as $p_key1){  ?>
            <div class="bg-gray-100 p-4 rounded shadow">

            <img src="<?php echo base_url(); ?>upload/package/<?= $p_key1["image"] ?>" alt="<?= $p_key1["title"] ?>" class="w-full h-auto mb-4" style="width:325px; height:245px; object-fit:cover;" />

            <h4 class="text-xl font-bold mb-2"><?= $p_key1["title"] ?></h4>

            <p class="mb-4"><ul>
            <li>Test Include:</li>
                                             <?php foreach($p_key1["test_list"] as $t_key1){ ?>
                                                <li><?=ucfirst($t_key1["test_name"])?></li>

                                                <?php } ?>
                                                <font color="#9900FF"><li>

                                                    <b>Price: <?=$p_key1["d_price"]?>/- <s><?=$p_key1["a_price"]?>/-</s></b>

                                                </li></font>

                                            </ul></p>

            <!-- <button onclick="bookNow(`<?=ucfirst($p_key1['title'])?>`)" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">WhatsApp Inquiry</button> -->

            <a href="<?= base_url(); ?>user_master/package_details/<?= $p_key1["id"] ?>" class="hover-over"><button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Book Now</button></a>

            </div>
            <?php } } ?>
            <!-- Repeat above block for more tests -->

        </div>

        </div>

    </section>

      <!-- All Test Packages Page -->
<!--
<section id="all-tests" class="py-12 px-4 bg-gray-50">

<div class="max-w-6xl mx-auto">

  <h3 class="text-3xl font-semibold mb-8 text-center">Body Checkup Packages</h3>

  <div class="grid md:grid-cols-2 gap-6">

    -- Individual Test Package --
    <?php  $bcount=0; foreach ($body_suggest_package as $bkey1) {   foreach($bkey1["body_package"] as $bp_key1){  $bcount++ ?>
    <div class="bg-white p-4 rounded shadow">

      <img src="<?php echo base_url(); ?>upload/package/<?= $bp_key1["image"] ?>" alt="<?= $bp_key1["title"] ?>" class="w-full h-auto mb-4" style="width:525px; height:245px; object-fit:cover;" />

      <h4 class="text-xl font-bold mb-2"><?= $bp_key1["title"] ?></h4>

      <p class="mb-4"><ul>

                                        <li>Test Include:</li>
                                        <?php foreach($bp_key1["test_list"] as $bt_key1){ ?>
                                        <li>&#x2022; <?=ucfirst($bt_key1["test_name"])?></li>

                                       <?php } ?>                                         

                                    </ul></p>

      -- <button onclick="bookNow(`<?=ucfirst($bt_key1['test_name'])?>`)" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">WhatsApp Inquiry</button> --

      <a href="<?= base_url(); ?>user_master/package_details/<?= $bp_key1["id"] ?>" class="hover-over"><button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Book Now</button></a>

    </div>
<?php } }  if(count($bcount) == "0") { echo "bhargav";?>
    <div class="col-span-2 text-center">
      <p class="text-gray-500">No body checkup packages available at the moment.</p>
</div>
<?php } ?>
    -- More packages... --

  </div>

</div>

</section>
-->
   <!-- <section class="indx_mbl_ovrlay " style="padding-bottom:0;margin-bottom:0;">
        <div class="container mbl_containr" style="padding-bottom:0">
            <div class="row">
                <div class="col-sm-12 pdng_0">
                    <div class="indx_mbl_mdl">
                   

                        <div class="col-sm-4  col-xs-4">
                            <img src="<?php echo base_url(); ?>user_assets/images/new/book-test.png"/> 
                        </div>
                        <div class="col-sm-4 col-xs-4">
                            <img src="<?php echo base_url(); ?>user_assets/images/new/manage-report.png"/> 
                        </div>
                        <div class="col-sm-4 col-xs-4">
                            <img src="<?php echo base_url(); ?>user_assets/images/new/share-report.png"/> 
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </section> -->

   <!-- <section class="indx_mbl_ovrlay" style="margin-bottom:0; background:#d7d7d7; background-repeat:no-repeat; ">
        <div class="container mbl_containr">
            <div class="row">
                <div class="col-sm-12" style="text-align:center;">
                    <div class="col-sm-1 col-xs-3 pdng_0 col-sm-offset-2 ">
                        <img src="<?php echo base_url(); ?>user_assets/images/new/icon-a.png"/> 
                    </div>
                    <div class="col-sm-7  col-xs-9 pdng_0 ">    <h1 class="mbl_title center" style="margin-top:0px; margin-bottom:0px;">DOWNLOAD AIRMED MOBILE APP
                        -- <br/> & GET <b style="font-family: 'Montserrat', sans-serif;"><?php echo $this->cash_back[0]["caseback_per"]; ?>% CASH BACK</B>  --
                    </h1>

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

            </div>
        </div>
    </section> -->


</div>
<!-- end main-content -->
<!--<script src="<?php echo base_url(); ?>user_assets/js/jquery-2.2.0.min.js"></script>--> 
<script src="<?php echo base_url(); ?>user_assets/js/jquery-ui.min.js"></script>
<script src="<?php echo base_url(); ?>user_assets/js/bootstrap.min.js"></script>
<script>

    function toggleIcon(e) {
        $(e.target)
                .prev('.panel-heading')
                .find(".short-full")
                .toggleClass('glyphicon-plus glyphicon-minus');
    }
    $('.panel-group').on('hidden.bs.collapse', toggleIcon);
    $('.panel-group').on('shown.bs.collapse', toggleIcon);

    

        function bookNow(testName) {

        const phoneNumber = +919227184434;

        const message = `Hi, I'm interested in booking the test package: ${testName}`;

        const url = `https://wa.me/${phoneNumber}?text=${encodeURIComponent(message)}`;

        window.open(url, '_blank');

        }

</script>

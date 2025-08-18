<script src="<?php echo base_url(); ?>user_assets/js/jssor.slider-21.1.6.min.js" type="text/javascript"></script>
 <script type="text/javascript">
        jssor_1_slider_init = function() {

            var jssor_1_SlideshowTransitions = [
              {$Duration:1200,x:-0.3,$During:{$Left:[0.3,0.7]},$Easing:{$Left:$Jease$.$InCubic,$Opacity:$Jease$.$Linear},$Opacity:2},
              {$Duration:1200,x:0.3,$SlideOut:true,$Easing:{$Left:$Jease$.$InCubic,$Opacity:$Jease$.$Linear},$Opacity:2}
            ];

            var jssor_1_options = {
              $AutoPlay: true,
              $SlideshowOptions: {
                $Class: $JssorSlideshowRunner$,
                $Transitions: jssor_1_SlideshowTransitions,
                $TransitionsOrder: 1
              },
              $ArrowNavigatorOptions: {
                $Class: $JssorArrowNavigator$
              },
              $BulletNavigatorOptions: {
                $Class: $JssorBulletNavigator$
              },
              $ThumbnailNavigatorOptions: {
                $Class: $JssorThumbnailNavigator$,
                $Cols: 1,
                $Align: 0,
                $NoDrag: true
              }
            };

            var jssor_1_slider = new $JssorSlider$("jssor_1", jssor_1_options);

            /*responsive code begin*/
            /*you can remove responsive code if you don't want the slider scales while window resizing*/
            function ScaleSlider() {
                var refSize = jssor_1_slider.$Elmt.parentNode.clientWidth;
                if (refSize) {
                    refSize = Math.min(refSize, 1000);
                    jssor_1_slider.$ScaleWidth(refSize);
                }
                else {
                    window.setTimeout(ScaleSlider, 30);
                }
            }
            ScaleSlider();
            $Jssor$.$AddEvent(window, "load", ScaleSlider);
            $Jssor$.$AddEvent(window, "resize", ScaleSlider);
            $Jssor$.$AddEvent(window, "orientationchange", ScaleSlider);
            /*responsive code end*/
        };
    </script>
    <style>
        /* jssor slider bullet navigator skin 01 css */
        /*
        .jssorb01 div           (normal)
        .jssorb01 div:hover     (normal mouseover)
        .jssorb01 .av           (active)
        .jssorb01 .av:hover     (active mouseover)
        .jssorb01 .dn           (mousedown)
        */
        .jssorb01 {
            position: absolute;
        }
        .jssorb01 div, .jssorb01 div:hover, .jssorb01 .av {
            position: absolute;
            /* size of bullet elment */
            width: 12px;
            height: 12px;
            filter: alpha(opacity=70);
            opacity: .7;
            overflow: hidden;
            cursor: pointer;
            border: #000 1px solid;
        }
        .jssorb01 div { background-color: gray; }
        .jssorb01 div:hover, .jssorb01 .av:hover { background-color: #d3d3d3; }
        .jssorb01 .av { background-color: #fff; }
        .jssorb01 .dn, .jssorb01 .dn:hover { background-color: #555555; }

        /* jssor slider arrow navigator skin 05 css */
        /*
        .jssora05l                  (normal)
        .jssora05r                  (normal)
        .jssora05l:hover            (normal mouseover)
        .jssora05r:hover            (normal mouseover)
        .jssora05l.jssora05ldn      (mousedown)
        .jssora05r.jssora05rdn      (mousedown)
        .jssora05l.jssora05lds      (disabled)
        .jssora05r.jssora05rds      (disabled)
        */
        .jssora05l, .jssora05r {
            display: block;
            position: absolute;
            /* size of arrow element */
            width: 40px;
            height: 40px;
            cursor: pointer;
            background: url('../images/a17.png') no-repeat;
            overflow: hidden;
        }
        .jssora05l { background-position: -10px -40px; }
        .jssora05r { background-position: -70px -40px; }
        .jssora05l:hover { background-position: -130px -40px; }
        .jssora05r:hover { background-position: -190px -40px; }
        .jssora05l.jssora05ldn { background-position: -250px -40px; }
        .jssora05r.jssora05rdn { background-position: -310px -40px; }
        .jssora05l.jssora05lds { background-position: -10px -40px; opacity: .3; pointer-events: none; }
        .jssora05r.jssora05rds { background-position: -70px -40px; opacity: .3; pointer-events: none; }
        /* jssor slider thumbnail navigator skin 09 css */.jssort09-600-45 .p {    position: absolute;    top: 0;    left: 0;    width: 600px;    height: 45px;}.jssort09-600-45 .t {    font-family: verdana;    font-weight: normal;    position: absolute;    width: 100%;    height: 100%;    top: 0;    left: 0;    color:#fff;    line-height: 45px;    font-size: 20px;    padding-left: 10px;}
		
		.h3{    margin: 0;
    padding: 5px;
    background: #dc1d3c;
    color: #fff;
    border-bottom: 2px solid #000;}
	
	.btn-dark.btn-theme-colored.one{padding:3px 15px;}
    </style>
<!-- Start main-content -->
<div class="main-content">
   <section id="about" class="package-div our_team">
        <div class="gray-overlay desc_full_width_img">
            <img src="<?php echo base_url(); ?>user_assets/images/new/career.jpg">
        </div>
        <div class="container" style="padding:0">
            <div class="row">
                <div class="col-md-4 col-sm-4">
                    <center>
                        <h1 class="font-40 font-w" style="line-height:38px; color:#BF2D37;text-shadow:1px -1px #fff;">Careers</h1>
                        <div class="border-btn mt-30">
                            <span></span>
                        </div>
                    </center>
                </div>
            </div>
        </div>


    </section> 
  
  <section>
		<div class="container">
			<div class="section-content book_test">
                <div class="row multi-row-clearfix">
                    <div class="col-md-12 col-sm-12">
						<div id="jssor_1" style="position: relative; margin: 0 auto; top: 0px; left: 0px; width: 1000px; height: 300px; overflow: hidden; visibility: hidden;">
							<div data-u="slides" style="cursor: default; position: relative; top: 0px; left: 0px; width: 1000px; height: 300px; overflow: hidden;">
								
							 <div data-p="112.50" style="display: none;">
									 <img data-u="image" src="<?php echo base_url(); ?>user_assets/images/new/c1.jpg" />	
								</div>
								<div data-p="112.50" style="display: none;">
									 <img data-u="image" src="<?php echo base_url(); ?>user_assets/images/new/c3.jpg" />
								</div> 
								<div data-p="112.50" style="display: none;">
									 <img data-u="image" src="<?php echo base_url(); ?>user_assets/images/new/c4.jpg" />
								</div> 
							</div>
							<div data-u="navigator" class="jssorb01" style="bottom:16px;right:16px;">
								<div data-u="prototype" style="width:12px;height:12px;"></div>
							</div>
							<span data-u="arrowleft" class="jssora05l" style="top:0px;left:8px;width:40px;height:40px;" data-autocenter="2"></span>
							<span data-u="arrowright" class="jssora05r" style="top:0px;right:8px;width:40px;height:40px;" data-autocenter="2"></span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
    <section>
	<div class="container" style="padding:0">
		<div class="row">
			<div class="col-md-12">
			<div class="our_careers">
			<div class="col-md-12"><center><h2 style="margin-bottom:50px;">Our people are our biggest assets! Join us in our mission and make a difference through your work. </h2></center></div>
			
				<div class="col-md-3 col-sm-6">
					<div class="careers_optn">
						<h3 class="h3">Search all available jobs</h3>
						<img src="<?php echo base_url(); ?>user_assets/images/careers1.jpg">
						<p>Airmed provides quality care and wellness packages to patients and best-in- class diagnosis support to doctors. We cater to direct consumers and patients referred by doctors and hospitals. Lab at your door step.</p>
						<div class="career_lrnmore_btn">
							<a href="<?php echo base_url(); ?>user_master/careers_details" class="btn btn-dark btn-theme-colored one">Learn More</a>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-6">
					<div class="careers_optn">
						<h3 class="h3">Get to Know Us</h3>
						<img src="<?php echo base_url(); ?>user_assets/images/careers1.jpg">
						<p>Airmed provides quality care and wellness packages to patients and best-in- class diagnosis support to doctors. We cater to direct consumers and patients referred by doctors and hospitals. Lab at your door step.</p>
						<div class="career_lrnmore_btn">
							<a href="<?php echo base_url(); ?>user_master/careers_details" class="btn btn-dark btn-theme-colored one">Learn More</a>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-6">
					<div class="careers_optn">
						<h3 class="h3">Why Airmed Pathology?</h3>
						<img src="<?php echo base_url(); ?>user_assets/images/careers1.jpg">
						<p> Airmed provides quality care and wellness packages to patients and best-in- class diagnosis support to doctors. We cater to direct consumers and patients referred by doctors and hospitals. Lab at your door step.</p>
						<div class="career_lrnmore_btn">
							<a href="<?php echo base_url(); ?>user_master/careers_details" class="btn btn-dark btn-theme-colored one">Learn More</a>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-6">
					<div class="careers_optn">
						<h3 class="h3">Information Technology</h3>
						<img src="<?php echo base_url(); ?>user_assets/images/careers1.jpg">
						<p>Airmed provides quality care and wellness packages to patients and best-in- class diagnosis support to doctors. We cater to direct consumers and patients referred by doctors and hospitals. Lab at your door step.</p>
						<div class="career_lrnmore_btn">
							<a href="<?php echo base_url(); ?>user_master/careers_details" class="btn btn-dark btn-theme-colored one">Learn More</a>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-6">
					<div class="careers_optn">
						<h3 class="h3">Phlebotomy</h3>
						<img src="<?php echo base_url(); ?>user_assets/images/careers1.jpg">
						<p>Airmed provides quality care and wellness packages to patients and best-in- class diagnosis support to doctors. We cater to direct consumers and patients referred by doctors and hospitals. Lab at your door step.</p>
						<div class="career_lrnmore_btn">
							<a href="<?php echo base_url(); ?>user_master/careers_details" class="btn btn-dark btn-theme-colored one">Learn More</a>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-6">
					<div class="careers_optn">
						<h3 class="h3">Client Services</h3>
						<img src="<?php echo base_url(); ?>user_assets/images/careers1.jpg">
						<p>Airmed provides quality care and wellness packages to patients and best-in- class diagnosis support to doctors. We cater to direct consumers and patients referred by doctors and hospitals. Lab at your door step.</p>
						<div class="career_lrnmore_btn">
							<a href="<?php echo base_url(); ?>user_master/careers_details" class="btn btn-dark btn-theme-colored one">Learn More</a>
						</div>
					</div>
				</div>
				
			</div>
			</div>
		</div>
	</div>
  </section>
    <section class="indx_mbl_ovrlay " style="padding-bottom:0;margin-bottom:0;">
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
	
	<section class="indx_mbl_ovrlay" style="margin-bottom:0; background:#d7d7d7; background-repeat:no-repeat; ">
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
								
			</div>
        </div>
    </section>
				
                
</div>
<!-- end main-content -->
<!--<script src="<?php echo base_url(); ?>user_assets/js/jquery-2.2.0.min.js"></script>--> 
<script src="<?php echo base_url(); ?>user_assets/js/jquery-ui.min.js"></script>
<script src="<?php echo base_url(); ?>user_assets/js/bootstrap.min.js"></script>
<script type="text/javascript">jssor_1_slider_init();</script>

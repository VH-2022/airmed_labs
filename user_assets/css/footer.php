<!-- Footer -->
<footer id="footer" class="footer bg-black-222 set_footer_res">
    <div class="footer-bottom bg-black-333">
        <div class="container pt-20">
		<div class="row">
					<div class="col-md-3 col-sm-3 footer_frst_cl6">
						 <a class="header_logo pull-left flip xs-pull-center col-sm-12 col-md-12 pdng_0" href="<?php echo base_url();?>user_master"><img src="<?php echo base_url();?>user_assets/images/logo.png" alt=""></a>
					</div>
					<div class="col-md-9 col-sm-9">
						<ul class="list-inline footer_menu">
							<li><a href="<?php echo base_url(); ?>user_master/about_us">About us</a></li>
							<li><a href="#">Partner with us</a></li>
							<li><a href="#">Investors</a></li>
							<li><a href="<?php echo base_url();?>user_master/support_system">Support/Help</a></li>
							<li><a href="<?php echo base_url(); ?>user_master/privacy_policy">Privacy policy</a></li>
							<li><a href="<?php echo base_url(); ?>user_master/contact_us">Contact Us</a></li>
						</ul>
					</div>
				</div>
			
        </div>
		<div class="ftr_brdr_full" style="border:none">
			<div class="container pt-20 pb-20">
				<div class="row">
				<div class="col-sm-12">
					<p class="full_div text-center foot_copy">AirmedPathlabs.com is India’s largest health test @ home service, creating a new benchmark for very high quality and honest prices. Airmeds employs state of art 46 touchpoints technology for assuring quality collection and testing across its tightly controlled network of labs and hundreds of full-time phlebotomists.  Coolsure ™ and Blackcode technology ensure sample transportation in an environment proof way- safe from heat, sunlight, contamination and human error. Smartprick ™ technology ensures minimum pain by precision laser guided blood collection. It specialises in Diagnostic, Preventive and Chronic Disease Management. Its main tests include blood tests, scans, MRI, ultrasound, ECG, PFT, free employee checkup, annual body check up, health test subscriptions. </p>
					
					<p class="font-13 text-black-777 m-0 text-center foot_copy">Copyright @ 2016 Airmed Path labs. All rights reserved</p>
				</div>
			</div>
			</div>
        </div>
    </div>
</footer>
<a class="scrollToTop" href="#"><i style="line-height: 1;" class="fa fa-angle-up"></i></a>
</div>
<!-- end wrapper -->

<!-- Footer Scripts -->


<!-- JS | jquery plugin collection for this theme -->
<script src="<?php echo base_url(); ?>user_assets/js/jquery-plugin-collection.js"></script>
<!-- JS | Custom script for all pages -->
<script src="<?php echo base_url(); ?>user_assets/js/custom.js"></script>

<!-- Revolution Slider 5.x SCRIPTS -->
<script src="<?php echo base_url(); ?>user_assets/js/revolution-slider/js/jquery.themepunch.tools.min.js"></script>
<script src="<?php echo base_url(); ?>user_assets/js/revolution-slider/js/jquery.themepunch.revolution.min.js"></script>



<!-- SLIDER REVOLUTION 5.0 EXTENSIONS  --> 
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/js/revolution-slider/js/extensions/revolution.extension.actions.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/js/revolution-slider/js/extensions/revolution.extension.carousel.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/js/revolution-slider/js/extensions/revolution.extension.kenburn.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/js/revolution-slider/js/extensions/revolution.extension.layeranimation.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/js/revolution-slider/js/extensions/revolution.extension.migration.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/js/revolution-slider/js/extensions/revolution.extension.navigation.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/js/revolution-slider/js/extensions/revolution.extension.parallax.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/js/revolution-slider/js/extensions/revolution.extension.slideanims.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/js/revolution-slider/js/extensions/revolution.extension.video.min.js"></script>


<!-- Footer Custom Scripts  -->
<script type="text/javascript">
    window.fbAsyncInit = function() {
    FB.init({
    appId      : '156557021428302', // replace your app id here
            channelUrl : '<?= base_url(); ?>',
            status     : true,
            cookie     : true,
            xfbml      : true
    });
    };
    (function(d){
    var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
    if (d.getElementById(id)) {return; }
    js = d.createElement('script'); js.id = id; js.async = true;
    js.src = "//connect.facebook.net/en_US/all.js";
    ref.parentNode.insertBefore(js, ref);
    }(document));
    function FBLogin(){
    FB.login(function(response){
    if (response.authResponse){
//            window.location.href = "actions.php?action=fblogin";
//var link = document.getElementById("fblink").value;

    window.location.href = "<?php echo base_url(); ?>user_login/fb_login/fblogin";
    }
    }, {scope: 'email,user_likes'});
    }
</script>
<script>
    $(document).on('change', '.btn-file :file', function() {
    var input = $(this),
            numFiles = input.get(0).files ? input.get(0).files.length : 1,
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
    input.trigger('fileselect', [numFiles, label]);
    });
    $(document).ready(function() {
    $('.btn-file :file').on('fileselect', function(event, numFiles, label) {

    var input = $(this).parents('.input-group').find(':text'),
            log = numFiles > 1 ? numFiles + ' files selected' : label;
    if (input.length) {
    input.val(log);
    } else {
    if (log) alert(log);
    }  });
    });</script>
<script>
    $(document).ready(function(e) {
    //Revolution Slider
    $(".rev_slider").revolution({
    sliderType:"standard",
            sliderLayout: "auto",
            dottedOverlay: "none",
            delay: 5000,
            navigation: {
            keyboardNavigation: "off",
                    keyboard_direction: "horizontal",
                    mouseScrollNavigation: "off",
                    onHoverStop: "off",
                    touch: {
                    touchenabled: "on",
                            swipe_threshold: 75,
                            swipe_min_touches: 1,
                            swipe_direction: "horizontal",
                            drag_block_vertical: false
                    },
                    arrows: {
                    style:"zeus",
                            enable:true,
                            hide_onmobile:true,
                            hide_under:600,
                            hide_onleave:true,
                            hide_delay:200,
                            hide_delay_mobile:1200,
                            tmp:'<div class="tp-title-wrap">    <div class="tp-arr-imgholder"></div> </div>',
                            left: {
                            h_align:"left",
                                    v_align:"center",
                                    h_offset:30,
                                    v_offset:0
                            },
                            right: {
                            h_align:"right",
                                    v_align:"center",
                                    h_offset:30,
                                    v_offset:0
                            }
                    },
                    bullets: {
                    enable:true,
                            hide_onmobile:true,
                            hide_under:600,
                            style:"metis",
                            hide_onleave:true,
                            hide_delay:200,
                            hide_delay_mobile:1200,
                            direction:"horizontal",
                            h_align:"center",
                            v_align:"bottom",
                            h_offset:0,
                            v_offset:30,
                            space:5,
                            tmp:'<span class="tp-bullet-img-wrap">  <span class="tp-bullet-image"></span></span><span class="tp-bullet-title">{{title}}</span>'
                    }
            },
            responsiveLevels: [1240, 1024, 778],
            visibilityLevels: [1240, 1024, 778],
            gridwidth: [1170, 1024, 778, 480],
            gridheight: [700, 768, 960, 720],
            lazyType: "none",
            parallax: {
            origo: "slidercenter",
                    speed: 1000,
                    levels: [5, 10, 15, 20, 25, 30, 35, 40, 45, 46, 47, 48, 49, 50, 100, 55],
                    type: "scroll"
            },
            shadow: 0,
            spinner: "off",
            stopLoop: "on",
            stopAfterLoops: 0,
            stopAtSlide: - 1,
            shuffle: "off",
            autoHeight: "off",
            fullScreenAutoWidth: "off",
            fullScreenAlignForce: "off",
            fullScreenOffsetContainer: "",
            fullScreenOffset: "0",
            hideThumbsOnMobile: "off",
            hideSliderAtLimit: 0,
            hideCaptionAtLimit: 0,
            hideAllCaptionAtLilmit: 0,
            debugMode: false,
            fallbacks: {
            simplifyAll: "off",
                    nextSlideOnWindowFocus: "off",
                    disableFocusListener: false,
            }
    });
    //Appointment Form Validation
    $("#appointment_form_at_home").validate({
    submitHandler: function(form) {
    var form_btn = $(form).find('button[type="submit"]');
    var form_result_div = '#form-result';
    $(form_result_div).remove();
    form_btn.before('<div id="form-result" class="alert alert-success" role="alert" style="display: none;"></div>');
    var form_btn_old_msg = form_btn.html();
    form_btn.html(form_btn.prop('disabled', true).data("loading-text"));
    $(form).ajaxSubmit({
    dataType:  'json',
            success: function(data) {
            if (data.status == 'true') {
            $(form).find('.form-control').val('');
            }
            form_btn.prop('disabled', false).html(form_btn_old_msg);
            $(form_result_div).html(data.message).fadeIn('slow');
            setTimeout(function(){ $(form_result_div).fadeOut('slow') }, 6000);
            }
    });
    }
    });
    //Mailchimp Subscription Form Validation
    $('#mailchimp-subscription-form-footer').ajaxChimp({
    callback: mailChimpCallBack,
            url: '//thememascot.us9.list-manage.com/subscribe/post?u=a01f440178e35febc8cf4e51f&amp;id=49d6d30e1e'
    });
    function mailChimpCallBack(resp) {
    // Hide any previous response text
    var $mailchimpform = $('#mailchimp-subscription-form-footer'),
            $response = '';
    $mailchimpform.children(".alert").remove();
    console.log(resp);
    if (resp.result === 'success') {
    $response = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' + resp.msg + '</div>';
    } else if (resp.result === 'error') {
    $response = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' + resp.msg + '</div>';
    }
    $mailchimpform.prepend($response);
    }


    });</script>
<!--AUTO INPUT-->
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/js/auto_input/jquery.tokeninput.js"></script>
</body>
<script type="text/javascript">
    $(function(){
    $('#vidyagames').tokenInput([
<?php foreach ($test as $ts) { ?>
        {id: 't-<?php echo $ts['id']; ?>', name: "<?php echo $ts['test_name']; ?>"},
<?php } ?>

    ], {
    theme: "facebook",
            noResultsText: "Nothing' found.",
            preventDuplicates: true,
            onAdd: function (item) {
            console.log("add ok");
            //return false;
            var id = item.id;
            console.log(id + " add id1");
            setTimeout(function(){
            document.getElementById('li_id_' + id).setAttribute("onClick", "remove_token('" + id + "', '" + item.name + "');");
            },500);
            $('#' + id + '_add').css("display", "none");
            $('#' + id + '_close').css("display", "block");
            $('#btn_search').html('Book');
            return false;
            },
            onDelete: function (item) {
            var delete_id = item.id;
            //remove_token(item.id, item.name);
            console.log(delete_id + " delete id1");
            setTimeout(function(){
            document.getElementById('li_id_' + item.id).setAttribute("onClick", "add_token('" + item.id + "', '" + item.name + "');");
            },500);
            document.getElementById(delete_id + "_close").setAttribute("style", "display:none;");
            document.getElementById(delete_id + "_add").setAttribute("style", "");
            return false;
            }, });
    });
    $(function(){
    $('#vidyagames1').tokenInput([
<?php foreach ($test as $ts) { ?>
        {id: 't-' +<?php echo $ts['id']; ?>, name: "<?php echo $ts['test_name']; ?>"},
<?php } ?>

    ], {
    theme: "facebook",
            noResultsText: "Nothing' found.",
            preventDuplicates: true,
            onAdd: function (item) {
            console.log(item + " add");
            //return false;
            // alert('hi');
            },
            onDelete: function (item) {
            var delete_id = item.id;
            var len = item.lenght;
            if (len == 0){
            $('#btn_search').html('Search');
            }
            //document.getElementById(delete_id+"_close").setAttribute("style","display:none;");
            //document.getElementById(delete_id+"_add").setAttribute("style","");
            },
    });
    });
    function get_select_value(){

    var selectedValues = $('#vidyagames').tokenInput("get");
    //console.log(selectedValues);
    //alert(selectedValues);
    var ids = [];
    var pid = [];
    var names = [];
    for (var key in selectedValues) {
    var value12 = selectedValues[key];
    console.log(value12);
    //var value1 = Object.values(value12);
    var value1 = $.map(value12, function(value, index) {
    return [value];
    });
//console.log(array);

    var id = value1[0];
    var name = value1[1];
    var type = value1[2];
    //alert(id);

    ids.push(id);
    names.push(name);
    }
    console.log(value1);
//console.log(ids);
//console.log(pid);
    if (ids != ""){

    $.ajax({
    url:"<?php echo base_url(); ?>user_master/searching_test",
            type:'post',
            data:{id:ids, name:names, all:selectedValues},
            success: function(data) {
            //     console.log("data"+data);
            window.location = "<?php echo base_url(); ?>user_master/order_search";
            }
    });
    } else{

    }

//console.log(ids);
//console.log(names);
//console.log(Object.values(value));



    }

    function add_new_test(){

    var selectedValues = $('#vidyagames1').tokenInput("get");
    //console.log(selectedValues);
    //alert(selectedValues);
    var ids = [];
    var pid = [];
    var names = [];
    for (var key in selectedValues) {
    var value12 = selectedValues[key];
    console.log(value12);
    //var value1 = Object.values(value12);
    var value1 = $.map(value12, function(value, index) {
    return [value];
    });
//console.log(array);

    var id = value1[0];
    var name = value1[1];
    var type = value1[2];
    //alert(id);
    console.log(id + " ids");
    ids.push(id);
    names.push(name);
    var oldids = $('#selectedid').val();
    var oldname = $('#selectedname').val();
    var x = oldids.concat(',', id);
    var y = oldname.concat('#', name);
    $('#selectedid').val(x);
    $('#test_package_id').val(x);
    $('#selectedname').val(y);
    }
    var x1 = x.split(',');
    var y1 = y.split('#');
    console.log(y1);
    console.log(x1);
    var x11 = [];
    $.each(x1, function(i, el){
    if ($.inArray(el, x11) === - 1) x11.push(el);
    });
    var y11 = [];
    $.each(y1, function(i, el){
    if ($.inArray(el, y11) === - 1) y11.push(el);
    });
	var newArray1 = x11.filter(function(v){return v!==''});
	var newArray2 = y11.filter(function(v){return v!==''});
    $.ajax({
    url:"<?php echo base_url(); ?>user_master/searching_test",
            type:'post',
            data:{id:newArray1, name:newArray2},
            success: function(data) {
            //     console.log("data"+data);
            window.location = "<?php echo base_url(); ?>user_master/order_search";
            }
    });
    }
</script>
<script>
    function remove_test(val){
	if(confirm('Are you sure want to remove?')){
			
		
    $("#li_id_" + val).remove();
    //alert(val
	
    setTimeout(function(){
    var hidden_sting = '';
    var hidden_string_name = '';
    var elems = document.getElementsByClassName('myAvailableTest');
    for (var i = 0; i < elems.length; i++) {
    var new_id = elems[i].id;
    new_id = new_id.split("_");
    var tag_name = elems[i].title;
    if (i == 0){
    hidden_sting = hidden_sting + new_id[1];
    hidden_string_name = hidden_string_name + tag_name;
    } else{
    hidden_sting = hidden_sting + "," + new_id[1];
    hidden_string_name = hidden_string_name + "#" + tag_name;
    }
    }
    //console.log(hidden_sting+" final string");
    //console.log(hidden_string_name+" final string name");
    $('#selectedid').val(hidden_sting);
    $('#selectedname').val(hidden_string_name);
    //console.log(y1);
    //console.log(x1);
    var x1 = hidden_sting.split(',');
    var y1 = hidden_string_name.split('#');
    $.ajax({
    url:"<?php echo base_url(); ?>user_master/searching_test",
            type:'post',
            data:{id:x1, name:y1},
            success: function(data) {
            //     console.log("data"+data);
            window.location = "<?php echo base_url(); ?>user_master/order_search";
            }
    });
    }, 1000);
};   
   }
</script>
<script>
    function add_token(id, name){

    $('#vidyagames').tokenInput("add", {id: id, name: name});
    $('#' + id + '_add').css("display", "none");
    $('#' + id + '_close').css("display", "block");
    $('#btn_search').html('Book');
    return false;
    }
    function remove_token(id, name){
    $('#vidyagames').tokenInput("remove", {id: id, name: name});
    $('#' + id + '_close').css("display", "none");
    $('#' + id + '_add').css("display", "block");
    return false;
    }

</script>
<script>
    $('.menuBtn').click(function(e) {
    e.stopPropagation();
    $('.navMenuSecWrapper').fadeToggle();
    return false;
    });
    $(document).click(function() {
    $('.navMenuSecWrapper').fadeOut();
    });</script>
<script>
    function signOut() {

    var auth2 = gapi.auth2.getAuthInstance();
    auth2.signOut().then(function () {
    console.log('User signed out.');
    });
    }
</script>


<script>
    function onSignIn(googleUser) {
    var profile = googleUser.getBasicProfile();
//  console.log('ID: ' + profile.getId()); // Do not send to your backend! Use an ID token instead.
    // console.log('Name: ' + profile.getName());
    //console.log('Image URL: ' + profile.getImageUrl());
    //console.log('Email: ' + profile.getEmail());
    var id = profile.getId();
    var name = profile.getName();
    var image = profile.getImageUrl();
    var email = profile.getEmail();
<?php if (!isset($login_data['id'])) { ?>
        $.ajax({
        url:"<?php echo base_url(); ?>user_login/google_login",
                type:'post',
                data:{id:id, name:name, email:email, image:image},
                success: function(data) {
                //     console.log("data"+data);
                window.location = "<?php echo base_url(); ?>user_master";
                }
        });
<?php } ?>

    }

</script>
<div class="g-signin2" style="display:none" data-onsuccess="onSignIn"></div>
<style>
    .token-input-input-token-facebook > input {
        min-width: 70px;
    }
    .token-input-dropdown-facebook p{
        display:none;
    }
</style>
<!--AUTO INPUT-->
</body>
</html>

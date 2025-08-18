<style>
    /*******************************
* MODAL AS LEFT/RIGHT SIDEBAR
* Add "left" or "right" in modal parent div, after class="modal".
* Get free snippets on bootpen.com
*******************************/
    .modal.left .modal-dialog,
    .modal.right .modal-dialog {
        position: fixed;
        margin: auto;
        width: 320px;
        height: 100%;
        -webkit-transform: translate3d(0%, 0, 0);
        -ms-transform: translate3d(0%, 0, 0);
        -o-transform: translate3d(0%, 0, 0);
        transform: translate3d(0%, 0, 0);
    }

    .modal.left .modal-content,
    .modal.right .modal-content {
        height: 100%;
        overflow-y: auto;
    }

    .modal.left .modal-body,
    .modal.right .modal-body {
        padding: 15px 15px 80px;
    }

    /*Left*/
    .modal.left.fade .modal-dialog{
        left: -320px;
        -webkit-transition: opacity 0.3s linear, left 0.3s ease-out;
        -moz-transition: opacity 0.3s linear, left 0.3s ease-out;
        -o-transition: opacity 0.3s linear, left 0.3s ease-out;
        transition: opacity 0.3s linear, left 0.3s ease-out;
    }

    .modal.left.fade.in .modal-dialog{
        left: 0;
    }

    /*Right*/
    .modal.right.fade .modal-dialog {
        right: -320px;
        -webkit-transition: opacity 0.3s linear, right 0.3s ease-out;
        -moz-transition: opacity 0.3s linear, right 0.3s ease-out;
        -o-transition: opacity 0.3s linear, right 0.3s ease-out;
        transition: opacity 0.3s linear, right 0.3s ease-out;
    }

    .modal.right.fade.in .modal-dialog {
        right: 0;
    }

    /* ----- MODAL STYLE ----- */
    .modal-content {
        border-radius: 0;
        border: none;
    }

    .modal-header {
        border-bottom-color: #EEEEEE;
        background-color: #FAFAFA;
    }

    /* ----- v CAN BE DELETED v ----- */
    body {
        background-color: #78909C;
    }

    .demo {
        padding-top: 60px;
        padding-bottom: 110px;
    }

    .btn-demo {
        margin: 15px;
        padding: 10px 15px;
        border-radius: 0;
        font-size: 16px;
        background-color: #FFFFFF;
    }

    .btn-demo:focus {
        outline: 0;
    }

    .demo-footer {
        position: fixed;
        bottom: 0;
        width: 100%;
        padding: 15px;
        background-color: #212121;
        text-align: center;
    }

    .demo-footer > a {
        text-decoration: none;
        font-weight: bold;
        font-size: 16px;
        color: #fff;
    }
    .content_details_users label input {
        color: #000000;
    }
    .rgt_sidebar.modal {background: transparent;}
    body .modal-backdrop.in {opacity: 0;}
    .mrgn_btm_25px {margin-bottom: 25px;}
    .login_light_back .input-group {float: left; width: 100%;}
    .login_light_back .input-group .input-group-addon {float: left; width: 8%;}
    .input-group-addon {background-color: #eeeeee !important; border: 1px solid #cccccc !important; border-radius: 4px; color: #555555; font-size: 14px; font-weight: 400; line-height: 1; padding: 9px 12px; text-align: center;}
    .login_light_back .input-group input.form-control {float: left; width: 92%;}
    .regstr_gendr_full {float: left; width: 100%;}
    .spn_red {color: #ff0000;}
    .regstr_male {float: left; margin-right: 6%; width: auto;}
    .regstr_male input[type="radio"] {margin-right: 8px;}
    .fillInfoClass{display:none;}
    .token-input-dropdown-facebook{  bottom: 19% !important; height:180px !important; width: 260px !important; z-index: 300000 !important;}
    #telicaller_test_list ul li.token-input-input-token-facebook{border:1px solid #ddd; border-radius:3px;}
    div.token-input-dropdown-facebook ul{height:100% !important;}
    #telicaller_test_list{height:200px}
    .token-input-token-facebook.token-input-selected-token-facebook > p {
        color: #737374;
    }
</style>
<!-- Modal -->

<!--Register Model-->
<footer class="main-footer">
    <div class="container">
        <div class="pull-right hidden-xs">

        </div>
        <strong>Copyright &copy; 2016 <a href="">AirmedLabs</a>.</strong> All rights reserved.
    </div><!-- /.container -->
</footer>


<?php /* Notification start */ ?>
<style>
    .discount-code {
        position: fixed;
        z-index: 999!important;
        left: 0;
        bottom:0;
        width:auto;
    }
    .box-content {
        border-bottom: none;
        width: 200px;
    }
    .box-toggle.active {
        color: #fff;
    }
    .box-toggle {
        display: block;
        overflow: hidden;
        color: #fff;
        position: relative;
        top: 0;
        height: 35px;
        margin-top: 0 ;
        margin-left:0px;
    }
    .fl {
        float: left;
    }
    .coupon-handle {
        background: #bf2d37;
        font-size: 14px;
        padding: 10px 8px;
        width: 200px;
    }
    .coupon-handle strong{color:#fff}
    .red-box{background: #bf2d37; color:#fff;}
    .red-box h2{color:#bf2d37;    margin: 0; background:#fff;padding:10px;font-size:18px; font-weight:bold;}
    .red-box ul{padding:0 10px;margin-bottom:0;}
    .red-box ul li{    color: #fff;
                       border-bottom: 1px dotted;
                       list-style: none;
                       padding: 5px 0;}
    .close{    position: absolute;
               right: 0;
               width: 22px;
               color: white;
               background: #bf2d37;
               padding: 0px 7px;
               font-weight: bold;
               border-radius: 0px;
               margin: 11px 6px;
               font-size: 19px;
               line-height: 25px;opacity:1}
    .red-box ul li span{font-weight:bold;}
    .close a{font-weight:bold; color: white;}
</style>
<script>
    /* $( "#get-coupon" ).click(function() {
     $(".box-content").toogle();
     $(".coupon-handle span").toggleClass("glyphicon-chevron-down glyphicon-chevron-up");
     }); */



    $(function(){
    $('.box-toggle').click(function() {
    $('.box-content').slideToggle(1000).delay(1000);
    });
    });</script>

<section class="discount-code" id="notification_bar_id" style="display:none;">
    <div class="box-content red-box">
        <div  class="close"><a href="javascript:void(0);" onclick="notification_bar_id.remove();">X</a> </div>
        <!-- <a href="/deals"><img src="<?php echo base_url(); ?>user_assets/images/new/coupon1.jpg" alt="" copy-to-clipboard="" text-to-copy="'20 discount'"></a> -->
        <span id="message_id"></span>
    </div>
    <div class="clear"></div>     
    <a class="box-toggle fl " href="javascript:void(0);" id="get-coupon">
        <div class="coupon-handle">
            <strong><span id="title_id"></span></strong>
            <span class="glyphicon glyphicon-chevron-up pull-right" style="font-size:11px;"></span>
        </div>
    </a>  
    <div class="clear"></div>
</section>
<?php /*  <a class="scrollToTop" href="javascript:void(0);"><i style="line-height: 1;" class="fa fa-angle-up"></i></a>   */ ?>
<?php /* END */ ?>

</div><!-- ./wrapper -->
<!-- ChartJS 1.0.1 -->
<script src="<?= base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<script src="<?= base_url(); ?>plugins/chartjs/Chart.min.js" type="text/javascript"></script>
<!-- FastClick -->

<!-- AdminLTE App -->

<!-- AdminLTE for demo purposes -->


<!-- Bootstrap 3.3.2 JS -->
<script src="<?php echo base_url(); ?>bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>user_assets/bootstrap-datepicker.js"></script>
<!-- SlimScroll -->
<script src="<?php echo base_url(); ?>plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<!-- FastClick -->
<script src='<?php echo base_url(); ?>plugins/fastclick/fastclick.min.js'></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url(); ?>dist/js/app.min.js" type="text/javascript"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url(); ?>dist/js/demo.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>js/admin.js" type="text/javascript"></script>
<script src="<?= base_url(); ?>assets/summernote/summernote.js"></script>
<script src="<?= base_url(); ?>assets/js/pages/forms.js"></script>

<!-- Bootstrap 3.3.2 JS -->

<!-- InputMask -->

<script src="<?php echo base_url(); ?>plugins/input-mask/jquery.inputmask.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>plugins/input-mask/jquery.inputmask.extensions.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>plugins/input-mask/jquery.inputmask.date.extensions.js" type="text/javascript"></script>

<?php /* <link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url(); ?>user_assets/css/auto_input/styles.css">
  <link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url(); ?>user_assets/css/auto_input/token-input.css">
  <link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url(); ?>user_assets/css/auto_input/token-input-facebook.css"> */ ?>
<!-- Bootstrap WYSIHTML5 -->
<script src="<?php echo base_url(); ?>plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js" type="text/javascript"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>user_assets/bootstrap-datepicker3.css"/>
<!-- Include the plugin's CSS and JS: -->
<script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap-multiselect.css" type="text/css"/>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-ui-1.10.3.js"></script>

<script>
            $(document).ready(function () {
            var date_input = $('input[name="date"]'); //our date input has the name "date"

            var container = $('.bootstrap-iso form').length > 0 ? $('.bootstrap-iso form').parent() : "body";
            date_input.datepicker({
            format: 'dd/mm/yyyy',
                    container: container,
                    todayHighlight: true,
                    autoclose: true,
            })
            })

                    $(document).ready(function () {
            var date_input = $('.datepicker-input'); //our date input has the name "date"

            var container = $('.bootstrap-iso form').length > 0 ? $('.bootstrap-iso form').parent() : "body";
            date_input.datepicker({
            format: 'dd/mm/yyyy',
                    container: container,
                    todayHighlight: true,
                    autoclose: true,
            })
            })
</script>

<!-- FastClick -->

<!-- AdminLTE App -->

<!-- AdminLTE for demo purposes -->

<!-- Page script -->
<script type="text/javascript">
            $(function () {
            //Datemask dd/mm/yyyy
            $("#datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
            $("[data-mask]").inputmask();
            });</script>
<!-- DATA TABES SCRIPT -->
<script src="<?php echo base_url(); ?>plugins/datatables/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $(function () {
    $("#example1").dataTable();
    $('#example2').dataTable({
    "bPaginate": true,
            "bLengthChange": false,
            "bFilter": true,
            "bSort": false,
            "bInfo": false,
            "bAutoWidth": false,
            "iDisplayLength": 10
    });
    });
    function get_pending_count() {

    $.ajax({
    url: "<?php echo base_url(); ?>tech/job_master/pending_count/",
            error: function (jqXHR, error, code) {
            // alert("not show");
            },
            success: function (data) {
            //     console.log("data"+data);
            //var jsonparse = JSON.Parse(data);
            var obj = $.parseJSON(data);
            //console.log(obj.job_count);
            if (obj.inquiry_total != '0') {
            document.getElementById('test_package_count').innerHTML = obj.all_inquiry;
            } else {
            document.getElementById('test_package_count').innerHTML = '0';
            }
            if (obj.inquiry_total != '0') {
            document.getElementById('inquiry_total').innerHTML = obj.inquiry_total;
            } else {
            document.getElementById('inquiry_total').innerHTML = '0';
            }
            if (obj.tickepanding != '0') {
            document.getElementById('supportpanding').innerHTML = obj.tickepanding;
            } else {
            document.getElementById('supportpanding').innerHTML = '0';
            }
            if (obj.job_count != '0') {
            document.getElementById('pending_count').innerHTML = obj.job_count;
            document.getElementById('total_pending_count').innerHTML = obj.job_count;
            } else {
            document.getElementById('pending_count').innerHTML = '0';
            document.getElementById('total_pending_count').innerHTML = '0';
            }
            if (obj.prescription_upload != '0') {
            document.getElementById('prescription_count').innerHTML = obj.prescription_upload;
            } else {
            document.getElementById('prescription_count').innerHTML = '0';
            }
            if (obj.contact_us_count != '0') {
            document.getElementById('contact_us').innerHTML = obj.contact_us_count;
            } else {
            document.getElementById('contact_us').innerHTML = '0';
            }
            if (obj.package_inquiry != '0') {
            document.getElementById('package_inquiry').innerHTML = obj.package_inquiry;
            } else {
            document.getElementById('package_inquiry').innerHTML = '0';
            }
            }
    });
    }
</script>
<script src="https://cdn.ckeditor.com/4.4.3/standard/ckeditor.js"></script>
<script type="text/javascript">
    $(function () {
    /* Extoel call full details popup screen code
     ShowMenu is used to disable popup come again. 
     
     
     
     */

    // Replace the <textarea id="editor1"> with a CKEditor
// instance, using default configuration.
    CKEDITOR.replace('editor1');
    CKEDITOR.replace('editor2');
//bootstrap WYSIHTML5 - text editor
    $(".textarea").wysihtml5();
    });</script>
<script type="text/javascript">




    get_pending_count();
    showMenu = true;
    callrunning = false;
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
    var pattern = /^\d{10,11}$/;
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


    function fillinfo() {

    }
    /*Nishit code start*/
    function get_packages123(city_fk) {
    $.ajax({
    url: '<?= base_url(); ?>User_call_master/package_list1',
            type: 'post',
            tryCount: 0,
            retryLimit: 3,
            data: {city: city_fk},
            beforeSend: function () {
            $("#loader_div2").attr("style", "");
            $("#send_quotte_btn").attr("disabled", "disabled");
            $("#send_quote_test_city").attr("disabled", "disabled");
            },
            success: function (data) {
            $('#telicaller_test_list').html(data);
            },
            complete: function () {
            $("#loader_div2").attr("style", "display:none;");
            $("#send_quotte_btn").removeAttr("disabled");
            $("#send_quote_test_city").removeAttr("disabled");
            },
            error: function (xhr, textStatus, errorThrown) {
            }
    });
    }
    $.ajax({
    url: "<?php echo base_url(); ?>Collection/todays_collection/",
            error: function (jqXHR, error, code) {
            },
            success: function (data) {
            $("#todays_collection").html(data);
            }
    });
    /*Nishit code end*/
    function getinventnoty(){

    var atype = "<?= $login_data["type"]; ?>";
    var checkstatus = "<?= strtolower($this->router->fetch_class() . "/" . $this->router->fetch_method()); ?>";
    $.ajax({
    url:'<?php echo base_url(); ?>inventory/notification_master',
            type:"get",
            data:{},
             dataType:"json",
            success:function(data){

            if (atype == 8){

            if (data.totalrequest != 0){  $("#indexnrequi").addClass("label label-warning"); $("#indexnrequi").html(data.totalrequest); }

            if (data.totalpo != 0){ $("#poindexnrequi").addClass("label label-warning"); $("#poindexnrequi").html(data.totalpo); }

            if (data.totalpoinward != 0){  $("#poindeeard").addClass("label label-warning"); $("#poindeeard").html(data.totalpoinward); }

            } else if (atype == 1 || atype == 2){


            if (data.totalrequest != 0){

            $("#maininventry").addClass("label label-warning");
            $("#indexnrequi").addClass("label label-warning");
            $("#indexnrequi").html(data.totalrequest); }

            if (data.totalpo != 0){

            $("#maininventry").addClass("label label-warning");
            $("#indentpore").addClass("label label-warning");
            $("#indentpore").html(data.totalpo); }

            if (data.totalpoinward != 0){

            $("#maininventry").addClass("label label-warning");
            $("#poindeeard").addClass("label label-warning");
            $("#poindeeard").html(data.totalpoinward);
            }
            var final_notification_total = parseInt(data.totalrequest) + parseInt(data.totalpo) + parseInt(data.totalpoinward);
            $("#maininventry").html(final_notification_total);
            } else{

            if (data.totalrequest != 0){ $("#maininventry").addClass("label label-warning"); $("#indexnrequi").addClass("label label-warning"); $("#indexnrequi").html(data.totalrequest); }

            if (data.totalpo != 0){

            $("#maininventry").addClass("label label-warning");
            $("#indentpore").addClass("label label-warning");
            $("#indentpore").html(data.totalpo);
            }
             var final_notification_total = parseInt(data.totalrequest) + parseInt(data.totalpo) + parseInt(data.totalpoinward);
            $("#maininventry").html(final_notification_total);
            }
            if (checkstatus == "intent_request/sub_index"){ notiinteentrequiest();
            } else if (checkstatus == "intent_genrate/poigeneratedetils"){
            ponotuupdatenoti();
            } else if (checkstatus == "intent_inward/inward_list"){

            notiinwrdupdate();
            }

            }
    });
    }
    function notiinteentrequiest(){

    $.ajax({
    url:'<?php echo base_url(); ?>inventory/notification_master/notuupdate',
            type:"get",
            data:{},
             dataType:"json",
            success:function(data){	}
    });
    }
    function ponotuupdatenoti(id){

    var id = "<?= $this->uri->segment(4); ?>";
    $.ajax({
    url:'<?php echo base_url(); ?>inventory/notification_master/ponotuupdatenoti',
            type:"get",
            data:{cid:id},
             dataType:"json",
            success:function(data){	}
    });
    }
    function notiinwrdupdate(){

    $.ajax({
    url:'<?php echo base_url(); ?>inventory/notification_master/notinwarduupdate',
            type:"get",
            data:{},
             dataType:"json",
            success:function(data){	}
    });
    }
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
<script src="http://websitedemo.co.in/phpdemoz/patholab/js/typeahead.min.js"></script>
<script type="text/javascript">
    $.ajax({
    url:'<?php echo base_url(); ?>Notification_Master/view',
            type:"post",
            data:{},
             dataType:"json",
            success:function(data){
            var title = data.title;
            var message = data.message;
            $('#title_id').html(title);
            $('#message_id').html(message);
            if (title.trim() != ''){
            $("#notification_bar_id").removeAttr('style');
            }
            }
    });
</script>
</body>
</html>

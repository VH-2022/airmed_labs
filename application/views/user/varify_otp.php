<!-- Start main-content -->
<div class="main-content">
<!-- Section: home -->
<section>
    <div class="container pdng_top_20px pdng_btm_30px">
        <div class="row">
            <div class="col-sm-12 pdng_0">
                <div class="col-sm-6">
                    <div class="login_main">
                        <div class="login_dark_back">
                            <h1 class="txt_green_clr res_txt_grn">Mobile Number Verification</h1>
                            <?php if (!empty($msg)) { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <?= $msg; ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="login_light_back">
                            <div class="col-sm-12 pdng_0 mrgn_btm_25px">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-key"></i></span>
                                    <input type="text" placeholder="Enter OTP" id="otp_val" class="form-control" name="otp">
                                </div>
                                <span style="color:red;" id="otp_error"></span>
                                <span style="color:green;" id="otp_success"></span>
                            </div>

                            <div class="col-sm-12 pdng_0 mrgn_btm_25px">
                                <div class="input-group">
                                    <a href="javascript:void(0);" id="resend_opt" style="display:none;" onclick="resend_otp();">Resend OTP</a>
                                    <a href="javascript:void(0);" id="mycounter"></a>
                                    <button type="button" id="verify_btn" onclick="check_otp();" class="btn btn-dark btn-theme-colored btn-flat pull-right">Verify</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 pull-left">
                    <img class="regi_rgt_img" src="<?php echo base_url(); ?>user_assets/images/shutterstock_113144242.jpg"/>
                </div>
            </div>
        </div>
     
       

        <script>
            // Countdown function
            function countdown(elementName, minutes, seconds) {
                var element = document.getElementById(elementName);
                var endTime = (+new Date) + 1000 * (60 * minutes + seconds) + 500;

                function twoDigits(n) {
                    return (n <= 9 ? "0" + n : n);
                }

                function updateTimer() {
                    var msLeft = endTime - (+new Date);
                    if (msLeft < 1000) {
                        $("#mycounter").hide();
                        $("#resend_opt").show();
                        element.innerHTML = '';
                    } else {
                        $("#resend_opt").hide();
                        var time = new Date(msLeft);
                        var hours = time.getUTCHours();
                        var mins = time.getUTCMinutes();
                        element.innerHTML = 'Resend OTP after <b>' + (hours ? hours + ':' + twoDigits(mins) : mins) + ':' + twoDigits(time.getUTCSeconds()) + '</b>';
                        setTimeout(updateTimer, 1000);
                    }
                }
                updateTimer();
            }

            // Start timer on page load
            countdown("mycounter", 2, 0);

            // Verify OTP
            function check_otp() {
                $("#otp_success").html("");
                $("#otp_error").html("");
                $("#verify_btn").prop("disabled", true);

                var otp = $("#otp_val").val().trim();
                var phone_no = "<?= $phone_no; ?>";

                if (otp !== '') {
                    $.ajax({
                        url: "<?= base_url(); ?>user_forget/verify_otp_ajax",
                        type: "post",
                        data: {otp: otp, phone_no: phone_no},
                        success: function (response) {
                            var jsondata = JSON.parse(response);
                            if (jsondata.status == 0) {
                                $("#verify_btn").prop("disabled", false);
                                $("#otp_error").html(jsondata.error_msg);
                            } else {
                                $("#otp_success").html(jsondata.success_msg);
                                setTimeout(function () {
                                    window.location = jsondata.redirect_url;
                                }, 2000);
                            }
                        },
                        error: function () {
                            $("#verify_btn").prop("disabled", false);
                            alert("Oops! Something went wrong. Please try again.");
                        }
                    });
                } else {
                    $("#verify_btn").prop("disabled", false);
                    $("#otp_error").html("OTP field is required.");
                }
            }
            // Resend OTP
            function resend_otp() {
                $("#otp_success").html("");
                $("#otp_error").html("");
                $("#resend_opt").hide();

                $.ajax({
                    url: "<?= base_url(); ?>user_forget/resend_otp_forgatpassword",
                    type: "post",
                    data: { phone_no: <?= $phone_no; ?> },
                    success: function (data) {
                        var jsondata = JSON.parse(data);
                        if (jsondata.status == 0) {
                            $("#otp_error").html(jsondata.error_msg);
                            $("#resend_opt").show();
                        } else {
                            $("#mycounter").show();
                            countdown("mycounter", 2, 0);
                            $("#otp_success").html(jsondata.success_msg);
                        }
                    },
                    error: function () {
                        $("#resend_opt").show();
                        alert("Oops! Something went wrong. Please try again.");
                    }
                });
            }
        </script>
    </div>
</section>

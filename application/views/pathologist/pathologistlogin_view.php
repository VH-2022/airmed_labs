<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>AirmedLabs</title>
	<link rel="stylesheet" href="<?php echo base_url(); ?>user_assets/jquery.mobile/jquery.mobile.icons.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>user_assets/jquery.mobile/theme-classic.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>user_assets/jquery.mobile/jquery.mobile.structure-1.4.5.min.css">
	
	<script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
	<script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
	<style>
.full_bg{background: rgba(0,0,0,0.3); width:100%; height:100%;padding-bottom:100%;padding:250px 0; position:relative; z-index:9; top:0; bottom:0;}

     .loader {width: 70px;height: 70px;margin: 0 auto;}
    .full_bg .loader img{width:70px; height:70px;}
    .text_highlight:hover{
        text-decoration: underline;
        /*font-weight: bold;
        font-size: 12px;*/
    }	
	
</style>
</head>
<body>
<div class="full_bg" style="display:none;" id="loader_div">
    <div class="loader">
        <img src="http://static.heart.org/riskcalc/app/assets/img/loading.gif">
    </div>
</div>
<div data-role="page" class="ui-page-theme-b" id="login">

	<div data-role="header">
		<h1>AirmedLabs Login</h1>
		
		
	</div><!-- /header -->

	<div class="ui-content" role="main">

		<h1><img src="<?php echo base_url(); ?>user_assets/images/logo.png" alt="" style=" margin-left: 57px;" width="200px"></h1>
 
	<div data-role="content">	
<span id="success_msg"></span>
		<form id="check-user"  data-ajax="false">

			<fieldset data-role="fieldcontain"> 
				<label for="email">Email:</label>
				<input type="email" name="email" id="email">
				<span style="color:red" id="error_email"></span>
			</fieldset>

			<fieldset data-role="fieldcontain"> 
				<label for="password">Password:</label>
				<input type="password" name="password" id="password">
				 <span style="color:red" id="error_password"></span>
			</fieldset>

			
			<input  id="submit" type="submit" value="LOG IN">
			

		</form>
		


	</div>

	</div><!-- /content -->

	

</div><!-- /page -->
<script>
$(document).on('pageinit', '#login',  function() {
    $("#submit").on("click",function(){
		
		ecnt = 0;
                                    $("#error_email").html("");
                                    $("#error_password").html("");
                                    $("#success_msg").html("");
                                    var email = $("#email").val();
                                    var password = $("#password").val();
                                    if (email == "") {
                                        $("#error_email").html("The email field required");
                                        ecnt = 1;
                                    } else {
                                        if (!(checkemail(email))) {
                                            $("#error_email").html("Please Enter Valid Email");
                                            ecnt = 1;
                                        }
                                    }
                                    if (password == "") {
                                        $("#error_password").html("The password field required");
                                        ecnt = 1;
                                    }
                                    if (ecnt == 1) {
                                        return false;
                                    }
                                    $.ajax({
                                        url: "<?php echo base_url(); ?>pathologist/Login/verify_login",
                                        data: {
                                            email: email, password: password
                                        },
                                        type: "POST",
                                        beforeSend: function () {
											$("#loader_div").show();
                                        },
                                        success: function (data) {
                                            var json_data = JSON.parse(data);
                                            if (json_data.status == '1') {
												
												window.location = '<?= base_url()."pathologist/Dashboard/" ?>patientlist?id=<?php echo time(); ?>';
                                                
                                            }
                                            if (json_data.status == '0') {
                                                $("#error_password").html(json_data.msg);
                                                $("#password").focus();
                                            }
                                        },
                                        complete: function () {
                                            $("#loader_div").hide();
                                        },
                                    });
									
									return false;
    });
	function checkemail(mail) {
                                                                var filter = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
                                                                if (filter.test(mail)) {
                                                                    return true;
                                                                } else {
                                                                    return false;
                                                                }
                                                            }

});

</script>

</body>
</html>
<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<section class="content-header">
    <h1>
        Customer
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>location-master/city-list"><i class="fa fa-users"></i>City List</a></li>
        <li class="active">Add Customer</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">

            <div class="box box-primary">
                <p class="help-block" style="color:red;"><?php
                    if (isset($error)) {
                        echo $error;
                    }
                    ?></p>

                <!-- form start -->
                <form role="form" id="customer_form" action="<?php echo base_url(); ?>customer-master/customer-add" method="post" enctype="multipart/form-data">

                    <div class="box-body">
                        <div class="col-md-6">
                            <?= validation_errors('<div class="alert alert-danger alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>', '</div>'); ?>

                            <div class="form-group">
                                <label for="exampleInputFile">Full Name</label><span style="color:red">*</span>
                                <input type="text"  name="fname" class="form-control"  value="<?php echo set_value('fname'); ?>" >
                            </div>
                            <?php /*<div class="form-group">
								<label for="exampleInputFile">Last Name</label><span style="color:red">*</span>
								<input type="text"  name="lname" class="form-control"  value="<?php echo set_value('lname'); ?>" >
							</div>*/?>
                            <div class="form-group">
                                <label for="exampleInputFile">Gender</label><span style="color:red">*</span>
                                <select class="form-control" name="gender" >
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Email</label><span style="color:red">*</span>
                                <input type="text"  name="email" class="form-control"  value="<?php echo set_value('email'); ?>" >

                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Mobile</label><span style="color:red">*</span>
                                <input type="text"  name="mobile" class="form-control"  value="<?php echo set_value('mobile'); ?>" >

                            </div>
							<div class="form-group">
                                <label for="exampleInputFile">Date Of Birth</label><span style="color:red">*</span>
                                <input type="text" id="dob1" name="dob" placeholder='Birth date' class="datepicker form-control" value="<?php echo $query[0]["dob"]; ?>" style="width:70%"/>OR<input type="text" class="form-control" style="width:20%" onkeyup="calculate_age1(this.value);"/>
                            </div>
                            <script>
                                function calculate_age1(val)
                                {
                                    var today_date = '<?= date("Y-m-d"); ?>';
                                    var get_date_data = today_date.split("-");

                                    var new_date = get_date_data[0] - val;
                                    new_date = new_date + "-" + get_date_data[1] + "-" + get_date_data[2];
                                    $("#dob1").val(new_date);
                                }
                            </script>

                        </div>
                        <div class="col-md-6">
                            <?php /*<div class="form-group">
								<label for="exampleInputFile">Country</label><span style="color:red">*</span>
                                    <select class="form-control" name="country" onchange="get_state(this.value);">
										<option value="">Select Country</option>
										<?php foreach ($country as $cat) { ?>
											<option value="<?php echo $cat['id']; ?>" <?php echo set_select('country', $cat['id']); ?>><?php echo ucwords($cat['country_name']); ?></option>
										<?php } ?>
									</select>
							</div>
                            <div class="form-group">
                                <label for="exampleInputFile">State</label><span style="color:red">*</span>
                                <select class="form-control" name="state" id="state" onchange="get_city(this.value);">
                                    <option value="">Select State</option>
                                    <?php foreach ($country as $cat) { ?>
                                        <option value="<?php echo $cat['id']; ?>" <?php echo set_select('country', $cat['id']); ?>><?php echo ucwords($cat['country_name']); ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputFile">City</label><span style="color:red">*</span>
                                <select class="form-control" name="city" id="city">
                                    <option value="">Select City</option>
                                </select>
                            </div>*/?>
							
							<div class="form-group">
                                <label for="exampleInputFile">State</label><span style="color:red">*</span>
                                <select class="form-control" name="state" id="state">
                                    <option value="">Select State</option>
                                    <?php foreach ($state as $cat) { ?>
                                        <option value="<?php echo $cat['id']; ?>" <?php
                                        if ($cat['id'] == $query[0]["state"]) {
                                            echo "selected";
                                        }
                                        ?>><?php echo ucwords($cat['state_name']); ?></option>
                                            <?php } ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputFile">City</label><span style="color:red">*</span>
                                <select class="form-control" name="city" id="city">
                                    <option value="">Select City</option>
                                    <?php foreach ($city as $cat) { ?>
                                        <option value="<?php echo $cat['id']; ?>" <?php
                                        if ($cat['id'] == $query[0]["city"]) {
                                            echo "selected";
                                        }
                                    ?>><?php echo ucwords($cat['city_name']); ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputFile">Address</label><span style="color:red">*</span>
                                <textarea name="address" class="form-control"> <?php echo set_value('address'); ?> </textarea>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputFile">Discount</label><span style="color:red">*</span>
                                <input type="text"  name="discount" id= "discount" class="form-control"  value="<?php echo set_value('discount'); ?>">
								<span><i>Note: Set '0' if no discount provided.</i></span>
                            </div>
                        </div>
                    </div><!-- /.box-body -->

                    <div class="box-footer">
                        <div class="col-md-6">
                            <button class="btn btn-primary" type="submit">Add</button>
                        </div>
                    </div>

                </form>
            </div><!-- /.box -->
            <script  type="text/javascript">
                $(document).ready(function () {
                    $("#showHide").click(function () {
                        if ($("#password").attr("type") == "password") {
                            $("#password").attr("type", "text");
                        }
                        else {
                            $("#password").attr("type", "password");
                        }
                    });
                });

                function get_state(val) {
                    $.ajax({
                        url: "<?php echo base_url(); ?>customer_master/get_state/" + val,
                        error: function (jqXHR, error, code) {
                            // alert("not show");
                        },
                        success: function (data) {
                            //     console.log("data"+data);
                            document.getElementById('state').innerHTML = "";
                            document.getElementById('state').innerHTML = data;

                        }
                    });

                }
                function get_city(val) {

                    $.ajax({
                        url: "<?php echo base_url(); ?>customer_master/get_city/" + val,
                        error: function (jqXHR, error, code) {
                            // alert("not show");
                        },
                        success: function (data) {
                            //     console.log("data"+data);
                            document.getElementById('city').innerHTML = "";
                            document.getElementById('city').innerHTML = data;

                        }
                    });
                }

//                $("#customer_form").submit(function (e) {
//                    var error = 1;
//                    var name = $("#name").val().trim();
//                    var city_data = $("#city_data").val()
//
//                    var email = $("#email").val().trim();
//
//                    if (name == "") {
//                        error = 0;
//                        $("#nameerror").html("The Name field is required.");
//                    }
//                    if (joinnig_salary != "") {
//                        if (isNaN(joinnig_salary)) {
//                            error = 0;
//                            $("#join_salary_error").html("Enter joining salary in number.");
//                        }
//                    }
//                    if (error == 1) {
//                        return true;
//                    } else {
//                        return false;
//                    }
//                }
            </script>
			<script src="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>

            <script>
                $(document).ready(function () {
                    var date_input = $('input[name="phlebo_date"]'); //our date input has the name "date"

                    var container = $('.bootstrap-iso form').length > 0 ? $('.bootstrap-iso form').parent() : "body";
                    date_input.datepicker({
                        format: 'yyyy-mm-dd',
                        container: container,
                        todayHighlight: true,
                        autoclose: true
                    });
                });
                $('.datepicker').datepicker({
                    format: 'yyyy-mm-dd'
                });
            </script>
        </div>
    </div>
</section>
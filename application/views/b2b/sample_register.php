<style>
    .pdng_0 {padding: 0;}
    .round {
        display: inline-block;
        height: 30px;
        width: 30px;
        line-height: 30px;
        -moz-border-radius: 15px;
        border-radius: 15px;
        background-color: #222;    
        color: #FFF;
        text-align: center;  
    }
    .round.round-sm {
        height: 10px;
        width: 10px;
        line-height: 10px;
        -moz-border-radius: 10px;
        border-radius: 10px;
        font-size: 0.7em;
    }
    .round.blue {
        background-color: #3EA6CE;
    }
    .fillInfoClass{display:block !important; }
    .chosen-container .chosen-results li.active-result {width: 100% !important;}
</style>
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<link href="<?php echo base_url(); ?>plugins/timepick/css/timepicki.css" rel="stylesheet">
<style>
    .chosen-container {
        display: inline-block;
        font-size: 14px;
        position: relative;
        vertical-align: middle;
        width: 100%;
    }
</style>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <!-- Custom Tabs -->
            <?php if (isset($success) != NULL) { ?>
                <div class="alert alert-success alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">X</button>
                    <?php echo $success['0']; ?>
                </div>
            <?php } ?>
            <?php if (isset($error) != NULL) { ?>
                <div class="alert alert-danger alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">X</button>
                    <?php echo $error['0']; ?>
                </div>
            <?php } ?>

            <?php if ($this->session->flashdata("error") != NULL) { ?>
                <div class="alert alert-danger alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">X</button>
                    <?php echo $this->session->flashdata("error") ?>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
            <section class="content">
                <div class="row">
                    <div class="row">
                        <div class="col-md-6">
                            <?php if ($login_data["type"] != 4) { ?>
                                <?php echo form_open("b2b/Logistic/details/" . $id, array("role" => "form", "method" => "POST", "id" => "user_form")); ?>
                            <?php } ?>
                            <div class="box box-primary">

                                <div class="box-header">
                                    <!-- form start -->
                                    <h3 class="box-title">Sample Details</h3>
                                </div>
                                <div class="box-body">
                                    <div class="col-md-12">
                                        <div class="form-group col-sm-12  pdng_0 mrgn_0">
                                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Barcode : </label><?php echo $barcode_detail[0]["barcode"]; ?>

                                        </div>
                                        <div class="form-group col-sm-12  pdng_0 mrgn_0">
                                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Scan date : </label><?php echo $barcode_detail[0]["scan_date"]; ?> 

                                        </div>
                                        <div class="form-group col-sm-12  pdng_0 mrgn_0">
                                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Patient Name: </label><?php echo $job_details[0]["customer_name"]; ?> 

                                        </div>
                                        <div class="form-group col-sm-12  pdng_0 mrgn_0">
                                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Age : </label><?php echo $job_details[0]["age"] . "/" . $job_details[0]["age_type"]; ?> 

                                        </div>
                                        <div class="form-group col-sm-12  pdng_0 mrgn_0">
                                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Gender : </label><?php echo ucfirst($job_details[0]["customer_gender"]); ?> 

                                        </div>
                                        <?php if ($login_data["type"] != 4) { ?>	
                                            <div class="form-group col-sm-12  pdng_0 mrgn_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Collect From : </label><?php echo ucfirst($barcode_detail[0]["c_name"]); ?>
                                            </div>
                                            <?php /* Nishit code start */ ?>
                                            <div class="form-group col-sm-12  pdng_0 mrgn_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Total Amount:</label> <?php
                                                echo "Rs." . $job_details[0]['price'];
                                                ?>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0 mrgn_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Due/Payable Amount:</label> <?php
                                                $j_payable_price = 0;
                                                if ($job_details[0]['payable_amount'] == "") {
                                                    echo "Rs." . "0";
                                                } else {
                                                    $j_payable_price = $job_details[0]['payable_amount'];
                                                    echo "Rs." . $job_details[0]['payable_amount'];
                                                }
                                                ?>  <a href="javascript:void(0)" onclick="$('#payment_model').modal('show');"> <i class="fa fa-edit"></i></a>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0 mrgn_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Creditors :</label>
                                                <?php
                                                if (!empty($selected_creditor)) {
                                                    echo ucfirst($selected_creditor[0]["name"]) . " - " . $selected_creditor[0]["mobile"];
                                                } else {
                                                    echo "NA";
                                                }
                                                ?>
                                                <?php if (empty($selected_creditor)) { ?>
                                                    <a href="javascript:void(0);" onclick="$('#creditor_model').modal('show');"><i class="fa fa-edit"></i></a>
                                                <?php } ?>
                                            </div>
                                            <?php /* END */ ?>
                                        <?php } ?>	
                                        <!--                                        <div class="form-group col-sm-12  pdng_0 mrgn_0">
                                                                                    <label for="exampleInputFile" class="col-sm-3 pdng_0">Logistic Name : </label><?php echo ucfirst($barcode_detail[0]["name"]); ?> 
                                        
                                                                                </div>-->
                                    </div>

                                </div>
                            </div>
                            <?php if ($login_data["type"] != 4) { ?>
                                <div class="box box-primary">

                                    <div class="box-header">
                                        <!-- form start -->
                                        <h3 class="box-title">Customer Details</h3>
                                    </div>
                                    <div class="box-body">
                                        <div id="hidden_test"><?php
                                            $cnt = 0;
                                            foreach ($job_details[0]["test_list"] as $ts1) {
                                                if ($ts1['testtype'] == '2') {
                                                    $testvalue = "p-" . $ts1["test_fk"];
                                                } else {
                                                    $testvalue = "t-" . $ts1["test_fk"];
                                                }
                                                ?>
                                                <input id="tr1_<?= $cnt ?>" type="hidden" name="test[]" value="<?= $testvalue; ?>" />
                                                <?php
                                                $cnt++;
                                            }
                                            ?></div>
                                        <div class="col-md-12">

                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Customer Name <span style="color:red">*</span></label> 
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" id="customer_name" name="customer_name" value="<?= $job_details[0]["customer_name"]; ?>" class="form-control"/>
                                                    <span style="color:red;" id="name_error"></span>
                                                </div>
                                            </div>
                                            <?php /* Nishit change payment status end */ ?>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Mobile :</label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" id="customer_mobile" name="customer_mobile" placeholder="Ex.9879879870" value="<?= $job_details[0]["customer_mobile"]; ?>" class="form-control"/>
                                                    <span style="color:red;" id="phone_error"></span>
                                                    <span style="color:red;" id="phone_error1"></span>
                                                    <input type="hidden" id="phone_check" value="0"/>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Email :</label>
                                                <div class="col-sm-9 pdng_0">
                                                    <?php /* <input type="text" onblur="check_email(this.value);" id="email" class="form-control" name="email" value="<?php echo ucwords($user_info[0]['email']); ?>"/> */ ?>
                                                    <input type="text" id="customer_email" class="form-control" name="customer_email" value="<?= $job_details[0]["customer_email"]; ?>"/> 
                                                    <span style="color:red;" id="email_error"></span>
                                                    <span style="color:red;" id="email_error1"></span>
                                                    <input type="hidden" id="email_check" value="0"/>
                                                </div>
                                            </div>

                                            <script>
                                                cust_id = "";
                                                function check_phn(val) {
                                                    $("#phone_error1").html("");
                                                    $("#phone_error").html("");
                                                    if (checkmobile(val) == true) {
                                                        $.ajax({
                                                            url: "<?php echo base_url(); ?>Admin/check_phone",
                                                            type: 'post',
                                                            data: {phone: val, cust_id: cust_id},
                                                            success: function (data) {
                                                                if (data.trim() > 0) {
                                                                    $("#phone_error1").html("This phone number already used, Try different.");
                                                                }
                                                                $("#phone_check").val(data.trim());
                                                            }
                                                        });
                                                    } else if (val == '') {
                                                        $("#phone_error1").html("Please enter phone number.");
                                                    } else {
                                                        $("#phone_error1").html("Invalid phone number.");
                                                    }
                                                }
                                                function check_email(val) {
                                                    $("#email_error1").html("");
                                                    if (checkemail(val) == true) {
                                                        $.ajax({
                                                            url: "<?php echo base_url(); ?>Admin/check_email",
                                                            type: 'post',
                                                            data: {email: val, cust_id: cust_id},
                                                            success: function (data) {
                                                                if (data.trim() > 0) {
                                                                    $("#email_error1").html("This email address already used, Try different.");
                                                                }
                                                                $("#email_check").val(data.trim());
                                                            }
                                                        });
                                                    } else {
                                                        $("#email_error1").html("Invalid email.");
                                                    }
                                                }
                                            </script>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Gender <span style="color:red">*</span>:</label>
                                                <div class="col-sm-9 pdng_0">
                                                    <select name="customer_gender" id="gender" class="form-control">
                                                        <option value="">--Select--</option>
                                                        <option value="male" <?php
                                                        if ($job_details[0]["customer_gender"] == "male") {
                                                            echo "selected";
                                                        }
                                                        ?>>Male</option>
                                                        <option value="female" <?php
                                                        if ($job_details[0]["customer_gender"] == "female") {
                                                            echo "selected";
                                                        }
                                                        ?>>Female</option>
                                                    </select> 
                                                    <span style="color:red;" id="gender_error"></span>
                                                </div>
                                            </div>
                                            <br>
                                            <input type="hidden" name="abc" value="12"/>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Age <span style="color:red">*</span>:</label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" id="dob" placeholder='Birth date' name="dob" class="datepicker form-control" value="<?= $job_details[0]["customer_dob"]; ?>" style="width:70%"/>OR
													<?php /* <input type="text" class="form-control" style="width:20%" id="age_1" onkeyup="calculate_age(this.value);"/> */ ?>
													<div class="col-sm-9 input-group pdng_0">

                                                    <span><input type="text" class="form-control number" minlength="0" maxlength="3" style="width:20%;" placeholder="Year" id="age_1" onkeyup="calculate_age(this.value);"/></span>
                                                    <span><input type="text" class="form-control number" minlength="0" maxlength="2" style="width:20%;" placeholder="Month" id="age_2" onkeyup="calculate_age(this.value);"/></span>&nbsp;&nbsp;&nbsp;
                                                    <span><input type="text" class="form-control number" minlength="0" maxlength="2" style="width:20%" placeholder="Day" id="age_3" onkeyup="calculate_age(this.value);"/></span>
                                                   
                                                </div>
                                                    <span style="color:red;" id="dob_error"></span>
                                                </div>
                                            </div>
                                            <script>
                                                function calculate_age(val)
                                                {
                                                    // var today_date = '<?= date("Y-m-d"); ?>';
                                                    // var get_date_data = today_date.split("-");

                                                    // var new_date = get_date_data[0] - val;
                                                    // new_date = new_date + "-" + get_date_data[1] + "-" + get_date_data[2];
                                                    var year = $('#age_1').val();
                                                var month = $('#age_2').val();
                                                var day = $('#age_3').val();

                                                var d = new Date();
                                                var days = d.setDate(d.getDate() - day);
                                                var months = d.setMonth(d.getMonth() - month);
                                                var years = d.setFullYear(d.getFullYear() - year);

                                                // document.write('Today is: ' + d.toLocaleString());
                                                console.log(d);
                                                var news = d.toLocaleString(days + months + years);

                                                var today = news.split(",");

                                                new_date = today[0];
                                                var nw_date = new_date.split("/");
                                                nw_date = nw_date[2] + "-" + nw_date[0] + "-" + nw_date[1];

                                                nw_date = d.toISOString().split('T')[0];
                                                $("#dob").val(nw_date);
                                                //    $("#dob").val(new_date);
                                                }
                                            </script>
                                            <br>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Referred By :</label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" id="" name="referby" value="<?= $job_details[0]["doctor"]; ?>" class="form-control"/>
                                                    <span style="color:red;" id=""></span>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Address :</label> 
                                                <div class="col-sm-9 pdng_0">
                                                    <textarea id="address" name="address" class="form-control"><?= $job_details[0]["customer_address"]; ?></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Note :</label> 
                                                <div class="col-sm-9 pdng_0">
                                                    <textarea id="address" name="note" class="form-control"><?= $job_details[0]["note"]; ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                </div> <?php } ?>
                            <div class="box box-primary">
                                <div class="box-header">
                                    <h3 class="box-title">Test/Package</h3>
                                </div>
                                <div class="box-body">
                                    <div class="col-md-12" id="all_packages">
    <!--                                        <button class="btn btn-primary pull-right" id="show_test_btn" onclick="show_test_model1();" type="button" style="margin-right:20px"><i class="fa fa-plus-square" style="font-size:20px;"></i> Add Test/Package</button> 
                                        <span id="loader_div" style="display:none;" class="pull-right"><img src="<?= base_url(); ?>upload/opc-ajax-loader.gif" style="height:28px;margin-right:10px"> </span> -->
                                        <?php if ($login_data["type"] != 4) { ?>
                                            <div class="col-md-12">
                                                <div class="col-md-12">
                                                    <div id="search_test">
                                                        <select class="chosen chosen-select" data-live-search="true" id="test" data-placeholder="Select Test" onchange="get_test_price();">

                                                        </select>
                                                    </div>
                                                    <span style="color:red;" id="test_error"></span>
                                                </div>
                                                <!--                                                <a href="javascript:void(0);" onclick="get_test_price();" style="margin-left:5px;" class="btn btn-primary"> Add</a>-->
                                                <span id="loader_div" style="display:none;"><img src="<?= base_url(); ?>upload/opc-ajax-loader.gif" style="height:28px;margin-right:10px"> </span> 
                                                <button class="btn btn-primary" id="show_test_btn" onclick="show_test_model();" type="button" style="display:none;">Add</button> 

                                            </div>
                                            <br><br>
                                        <?php } ?>
                                        <table class="table table-striped" id="city_wiae_price">
                                            <thead>
                                                <tr>
                                                    <th>Test Name</th>
                                                    <?php if ($login_data["type"] != 4) { ?>
                                                        <th>MRP</th>
                                                        <th>B2B Price</th>
                                                        <th>Action</th>
                                                    <?php } ?>
                                                </tr>
                                            </thead>
                                            <tbody id="t_body">
                                                <?php
                                                $cnt = 0;
                                                $total_price = 0;
                                                foreach ($job_details[0]["test_list"] as $ts1) {

                                                    //array_push($pids, $ts1['test_id']);
                                                    /* if ($ts1["info"][0]["special_price"] > 0) {
                                                      $prc1 = $ts1["info"][0]["special_price"];
                                                      } else if ($ts1["info"][0]["b2b_price"] > 0) {
                                                      $prc1 = $ts1["info"][0]["b2b_price"];
                                                      } else {
                                                      $prc1 = $ts1["info"][0]["price"];
                                                      } */
                                                    $mrp = round($ts1["info"][0]['price']);
                                                    if ($ts1["info"][0]['mrpprice'] != "") {
                                                        $mrp = $ts1["info"][0]['mrpprice'];
                                                    }

                                                    if ($ts1["info"][0]['specialprice'] != "") {
                                                        $discount = "";
                                                        $spicelprice = $ts1["info"][0]['specialprice'];
                                                    } else {
                                                        $discount = $labdetils->test_discount;
                                                        $discountprice = $mrp * $discount / 100;
                                                        $spicelprice = $mrp - $discountprice;
                                                    }
                                                    $spicelprice = $spicelprice;
                                                    if ($ts1['testtype'] == '2') {
                                                        $testvalue1 = "p-" . $ts1["test_fk"];
                                                    } else {
                                                        $testvalue1 = "t-" . $ts1["test_fk"];
                                                    }
                                                    ?>
                                                    <tr id="tr_<?= $cnt ?>">
                                                        <td><?= $ts1["info"][0]["test_name"]; ?></td> 
                                                        <?php if ($login_data["type"] != 4) { ?>
                                                            <td>Rs.<?= $mrp; ?></td>
                                                            <td>Rs.<?= round($ts1["price"]); /* $spicelprice; */ ?></td>
                                                            <td><a href="javascript:void(0);" onclick="delete_city_price('<?= $cnt ?>', '<?= $ts1["info"][0]['price']; ?>', '<?= $ts1['price']; ?>', '<?= $ts1["info"][0]["test_name"]; ?>', '<?= $testvalue1 ?>')"> Delete</a></td>
                                                        <?php } ?>
                                                    </tr>
                                                    <?php
                                                    $total_price = round($total_price + $ts1["price"]);
                                                    ;
                                                    $cnt++;
                                                }
                                                ?>
                                            </tbody>
                                        </table>

                                        <br>
                                        <?php if ($login_data["type"] != 4) { ?>
                                            <div class="col-md-12">
                                                <div class="col-md-12" style="padding:0">
                                                    <p class="lead">Amount</p>
                                                    <div class="table-responsive">
                                                        <table class="table">
                                                            <?php /* <tr>
                                                              <th>Discount(%):</th>
                                                              <td><input type="text" onkeyup="get_discount_price(this.value);" value="<?= $job_details[0]["discount"] ?>" name="discount" id="discount" class="form-control"/></td>
                                                              </tr> */ ?>
                                                            <input type="hidden" onkeyup="get_discount_price(this.value);" value="0" name="discount" id="discount" class="form-control"/>
                                                            <tr style="">
                                                                <th>Collection Charge:</th>
                                                                <td><input type="text" onkeypress="return isNumberKey(event)" onkeyup="get_collection_price(this.value);" value="<?php
                                                                    if ($job_details[0]["collection_charge"] > 0) {
                                                                        echo $job_details[0]["collection_charge"];
                                                                    } else {
                                                                        echo 0;
                                                                    }
                                                                    ?>" name="collection_charge" id="collection_charge" class="form-control"/></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Payable Amount: Rs.</th>
                                                                <td><div id="payable_div"><input type="text" name="payable" id="payable_val" value="<?=$j_payable_price;?>" readonly="" class="form-control"/></div></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Total Amount: Rs. </th>
                                                                <th><div id="total_id_div"><input type="text" name="total_amount" id="total_id" value="<?= $job_details[0]["price"]; ?>" readonly="" class="form-control"/></div></th>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div><!-- /.col -->
                                            </div><!-- /.row -->
                                        <?php } ?>
                                    </div><!-- /.box -->
                                </div>
                                <?php if ($login_data["type"] != 4) { ?>
                                    <input type="hidden" name="submit_type" id="submit_type" value="0"/>
                                    <script>
                                        $(function () {
                                            $('.chosen').chosen();
                                            getActiveCall();
                                        });
                                        setInterval(function () {
                                            getActiveCall();
                                        }, 3000);
                                        ajaxCall = null;
                                        function getActiveCall() {

                                            if (ajaxCall != null) {
                                                ajaxCall.abort();
                                            }
                                            $ajaxCall = $.ajax({
                                                url: "<?php echo base_url(); ?>Admin/get_call_user_data/",
                                                error: function (jqXHR, error, code) {
                                                },
                                                success: function (data) {

                                                    d = JSON.parse(data);
                                                    if (d.number) {
                                                        if (d.customer) {
                                                            $("#show_number_print").show();

                                                            $('.chosen').val(d.customer['id']);

                                                            get_user_info({value: d.customer['id'] + ""});
                                                            $('.chosen').trigger("chosen:updated");
                                                            $("#number_print").html("New Incoming Call Number <b>" + d.number +
                                                                    ".</b>" + " Registered user name " + d.customer['full_name']);
                                                            //                                                        $("#show_number_print").show();
                                                            $("#show_number_print1").hide();

                                                        } else {
                                                            $("#name").val('');
                                                            $("#phone").val('');
                                                            $("#email").val('');
                                                            var elements = document.getElementById("gender").options;
                                                            for (var i = 0; i < elements.length; i++) {
                                                                elements[i].removeAttribute("selected");
                                                                elements[i].selected = false;
                                                            }
                                                            $("#address").val('');
                                                            $("#existingCustomer12").val('').trigger('chosen:updated');
                                                            $("#number_print1").html("New Incoming Call Number <b>" + d.number +
                                                                    ".</b>");
                                                            $('#phone').val(d.number);
                                                            $("#show_number_print1").show();
                                                            $("#show_number_print").hide();
                                                            $("#order_details").hide();
                                                        }

                                                    } else {
                                                        //                                                    $("#name").val('');
                                                        //                                            $("#phone").val('');
                                                        //                                            $("#email").val('');
                                                        //                                            var elements = document.getElementById("gender").options;
                                                        //                                            for (var i = 0; i < elements.length; i++) {
                                                        //                                                elements[i].removeAttribute("selected");
                                                        //                                                elements[i].selected = false;
                                                        //                                            }
                                                        //                                            $("#address").val('');
                                                        $("#show_number_print").hide();
                                                        $("#show_number_print1").hide();
                                                    }


                                                }
                                            });
                                        }
                                        function get_test(val) {

                                            if (val.value.trim()) {
                                                $.ajax({
                                                    url: "<?php echo base_url(); ?>Admin/get_city_test",
                                                    type: 'post',
                                                    data: {city: val.value},
                                                    success: function (data) {
                                                        $("#t_body").html("");
                                                        $("#hidden_test").html("");
                                                        $("#discount").html("0");
                                                        $("#payable_val").val("0");
                                                        $("#total_id").val("0");
                                                        $("#search_test").html("");
                                                        $("#search_test").html(data);
                                                    }
                                                });
                                            }
                                        }

                                        function get_user_info(val) {
                                            if (val.value.trim() != '') {
                                                $.ajax({
                                                    url: "<?php echo base_url(); ?>Admin/get_user_info",
                                                    type: 'post',
                                                    data: {user_id: val.value},
                                                    success: function (data) {
                                                        var json_data = JSON.parse(data);
                                                        cust_id = json_data.id.trim();
                                                        if (json_data.full_name.trim()) {
                                                            $("#name").val(json_data.full_name);
                                                        } else {
                                                            $("#name").val("");
                                                        }
                                                        if (json_data.mobile.trim()) {
                                                            $("#phone").val(json_data.mobile);
                                                        } else {
                                                            $("#phone").val("");
                                                        }
                                                        if (json_data.email.trim()) {
                                                            $("#email").val(json_data.email);
                                                        } else {
                                                            $("#email").val("");
                                                        }
                                                        if (json_data.dob.trim()) {
                                                            $("#dob").val(json_data.dob);
                                                            bid_datepicker();
                                                        } else {
                                                            $("#dob").val("");
                                                        }
                                                        if (json_data.gender.trim()) {
                                                            //$("#gender").val(json_data.gender);
                                                            $("#gender:selected").removeAttr("selected");
                                                            var elements = document.getElementById("gender").options;
                                                            for (var i = 0; i < elements.length; i++) {
                                                                elements[i].removeAttribute("selected");
                                                                elements[i].selected = false;
                                                            }
                                                            for (var i = 0; i < elements.length; i++) {
                                                                /*elements[i].selected = false;*/
                                                                if (elements[i].value.toUpperCase() == json_data.gender.toUpperCase()) {
                                                                    elements[i].setAttribute("selected", "selected");
                                                                }
                                                            }
                                                        }
                                                        $("#test_for").html(json_data.family);
                                                        if (json_data.address.trim()) {
                                                            $("#address").val(json_data.address);
                                                        } else {
                                                            $("#address").val("");
                                                        }
                                                    }
                                                });
                                                $.ajax({
                                                    url: "<?php echo base_url(); ?>Admin/get_user_orders",
                                                    type: 'post',
                                                    data: {user_id: val.value},
                                                    error: function (jqXHR, error, code) {
                                                    },
                                                    success: function (data) {
                                                        if (data != '0') {
                                                            $("#order_table").empty();
                                                            $("#order_table").append(data);
                                                            $("#order_details").show();
                                                        } else {
                                                            $("#order_details").hide();
                                                        }
                                                    }
                                                });
                                            } else {
                                                $("#name").val('');
                                                $("#phone").val('');
                                                $("#email").val('');
                                                var elements = document.getElementById("gender").options;
                                                for (var i = 0; i < elements.length; i++) {
                                                    elements[i].removeAttribute("selected");
                                                    elements[i].selected = false;
                                                }
                                                $("#address").val('');
                                                $("#order_details").attr("style", "display:none;");
                                            }
                                        }
                                        function submit_type1(val) {
                                            var cnt = 0;
                                            var name = $("#name").val();
                                            var email = $("#email").val();
                                            var phone = $("#phone").val();
                                            var gender = $("#gender").val();
                                            var customer_name = $("#customer_name").val();
                                            var customer_name = customer_name.trim();

                                            var dob = $("#dob").val();
                                            $("#phone_error1").html("");
                                            var test_city = $("#test_city").val();
                                            $("#name_error").html("");
                                            $("#email_error").html("");
                                            $("#phone_error").html("");
                                            $("#test_city_error").html("");
                                            $("#test_error").html("");
                                            $("#gender_error").html("");
                                            $("#dob_error").html("");

                                            if (customer_name == '') {
                                                cnt = cnt + 1;
                                                $("#name_error").html("The customer name field is required.");
                                            }

                                            if (gender == '') {
                                                cnt = cnt + 1;
                                                $("#gender_error").html("The gender field is required");
                                            }
                                            if (dob == '') {
                                                cnt = cnt + 1;
                                                $("#dob_error").html("Required");
                                            }

                                            if ($("#hidden_test").html().trim() == '') {
                                                cnt = cnt + 1;
                                                if (cnt == 1) {
                                                    $("#test_error").html("Please select test.");
                                                }
                                            }
                                            if (cnt > 0) {
                                                return false;
                                            }

                                            $("#submit_type").val(val);
                                            setTimeout(function () {

                                                var val = $("#phone_check").val();
                                                if (val > 0) {
                                                    return false;
                                                }
                                                $("#user_form").submit();
                                                //alert("ok");
                                            }, 500);
                                        }
                                        function checkmobile(mobile) {
                                            var filter = /^[789]\d{9}$/;
                                            if (filter.test(mobile)) {
                                                if (mobile.length == 10) {
                                                    return true;
                                                } else {
                                                    return false;
                                                }
                                            } else {
                                                return false;
                                            }
                                        }
                                        function checkemail(mail) {
                                            //var str=document.validation.emailcheck.value
                                            var filter = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i
                                            if (filter.test(mail)) {
                                                return true;
                                            } else {
                                                return false;
                                            }
                                        }
										 setTimeout(function () {
											
        var job_id = "<?= $id ?>";
        $.ajax({
            url: "<?php echo base_url()."b2b/Logistic/get_job_log/"; ?>" + job_id,
            success: function (data) {
                $("#job_tracking").html(data);
            }
        });
    }, 1000);
                                    </script>
                                    <div class="box-footer">
                                        <input type="button" onclick="submit_type1('1');" class="btn btn-primary" value="Update information"/>
                                    </div>
                                <?php } ?>
                            </div>
                            <?php if ($login_data["type"] != 4) { ?>
                                <?php echo form_close(); ?>
                            <?php } ?>
                            <div class="box box-primary">

                                <div class="box-header">
                                    <!-- form start -->
                                    <h3 class="box-title">Upload Report</h3>
                                </div>
                                <?php if ($login_data["type"] != 4) { ?>
                                    <a href="javascript:void(0)" id="reportgenrate" class="btn btn-primary pull-right">Generate report</a>
                                <?php } ?>

                                <form role="form" action="<?php echo base_url(); ?>b2b/Logistic/upload_report/<?= $id ?>" method="post" enctype="multipart/form-data" id="submit_report">
                                    <?php echo form_open_multipart("b2b/Logistic/upload_report/$id", array("role" => 'form', "id" => 'submit_report')); ?>
                                    <div class="box-body">
                                        <?php if ($sample_client_print_report_permission[0]["print_report"] == 1 || $job_details[0]['payable_amount'] <= 0) { ?>
                                            <div class="form-group">
                                                <input type="file" multiple id="common_report" name="common_report[]" required="">
                                                <small></small><br>

                                                <?php
                                                if ($login_data["type"] != 4) {
                                                    if ($jobspdf != null) {
                                                        ?>	<a href="<?= base_url() . "b2b/Logistic/pdfapprove/" . $id; ?>" data-toggle="tooltip" data-original-title="Approve" onclick="return confirm('Are you sure you want to Approve this report?');" class="btn btn-primary pull-right">Approve report</a><?php
                                                    }
                                                }
                                                ?>

                                                <table class="table table-striped" id="city_wiae_price123">
                                                    <tbody id="t_body123">
                                                        <?php foreach ($jobspdf as $pdf) { ?>
                                                            <tr id="pdf_<?= $pdf['id']; ?>">
                                                                <td><a href="<?php echo base_url(); ?>upload/B2breport/<?php echo $pdf['report'] . '?' . time(); ?>" target="_blank"> <?php echo $pdf['original']; ?> </a></td>
                                                                <td><?php
                                                                    if ($pdf['approve'] == 1) {
                                                                        echo "Approve";
                                                                    } else {
                                                                        echo "Pending";
                                                                    }
                                                                    ?></td>
                                                                <td><a href="javascript:void(0);" id="dpdf_<?= $pdf['id']; ?>" class="pdfdelete" >Delete</a></td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>

                                                <?php /* foreach($jobspdf as $pdf) { ?>
                                                  <a href="<?php echo base_url(); ?>upload/B2breport/<?php echo $pdf['report']; ?>" target="_blank"> <?php echo $pdf['original']; ?> </a> &nbsp; <br>
                                                  <?php } */ ?>
                                                <input type="hidden" name="type_common_report" value="c"/>
                                            </div>
                                        <?php
                                        } else {
                                            if (count($jobspdf) > 0) {
                                                echo "<span style='color:red;'>Please collect due amount for print report.</span>";
                                            }
                                        }
                                        ?>
                                        <div class="form-group">
                                            <textarea class="form-control" name="desc_common_report"><?php echo $job_details[0]['report_description']; ?></textarea>
                                        </div>
                                        <div class="box-footer">
                                            <input class="btn btn-primary" value="Upload" type="submit">
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal fade" id="payment_model" role="dialog">
                                <div class="modal-dialog">

                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Payment Status</h4>
                                        </div>
                                            <?php echo form_open("b2b/Logistic/payment_received/" . $job_details[0]["id"] . "/" . $id, array("onsubmit" => "return confirm('Are you sure?');", "method" => "POST", "role" => "form", "id" => "payment_receiv_form_id")); ?>
                                        <div class="modal-body">
<?php if (!empty($amount_history_success)) { ?>
                                                <div class="widget">
                                                    <div class="alert alert-success alert-dismissable">
                                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>
                                                <?php echo $amount_history_success['0']; ?>
                                                    </div>
                                                </div>
<?php } ?>
                                            <div class="form-group">
                                                <label for="recipient-name" class="control-label">Receive Amount<span style="color:red;">*</span>:</label>
                                                <input type="text" class="form-control" id="j_amount" name="amount" onkeypress="return isNumberKey(event)" required="">
                                                <input type="hidden" id="ttl_amount" name="ttl_amount" value="<?= $job_details[0]['payable_amount']; ?>"/>
                                                <span style="color:red;" id="amount_error"></span>
                                            </div>
                                            <div class="form-group">
                                                <textarea class="form-control" placeholder="Remark" name="remark"></textarea>
                                            </div>
                                            <script>
                                                function delete_received_payment(val, d_amount) {
                                                    var cnfg = confirm('Are you sure?');
                                                    if (cnfg == true) {
                                                        var ttl_amount = $("#ttl_amount").val();
                                                        $.ajax({
                                                            url: "<?php echo base_url(); ?>b2b/Logistic/delete_assign_payment",
                                                            type: 'post',
                                                            data: {id: val, ttl_amount: ttl_amount, jid:<?= $job_details[0]["id"]; ?>, did:<?= $id; ?>, d_amount: d_amount},
                                                            beforeSend: function () {
                                                                $("#amount_error").html('<img src="<?= base_url(); ?>upload/opc-ajax-loader.gif" style="height:28px;margin-left:10px">');
                                                            },
                                                            success: function (data) {
                                                                if (data == 1) {
                                                                    window.location.reload();
                                                                }
                                                                $("#amount_error").html('');
                                                            }, complete: function () {
                                                                $("#amount_error").html('');
                                                            }
                                                        });
                                                    }
                                                }
                                                /*$("#j_amount").keyup(function () {
                                                 $("#amount_error").html("");
                                                 var val = parseInt(this.value);
                                                 var ttl_amt = '<?= $query[0]['payable_amount']; ?>';
                                                 ttl_amt = parseInt(ttl_amt);
                                                 if (val != null) {
                                                 console.log("ttl" + ttl_amt + " input" + val);
                                                 if (ttl_amt >= val) {
                                                 $("#amount_submit_btn").removeAttr("disabled");
                                                 } else {
                                                 $("#amount_submit_btn").attr("disabled", "");
                                                 $("#amount_error").html("Given amount is more than payable amount.");
                                                 }
                                                 } else {
                                                 $("#amount_submit_btn").attr("disabled", "");
                                                 $("#amount_error").html("Invalid amount.");
                                                 }
                                                 });*/
                                            </script>
                                            <h3>Received Amount History</h3>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Amount</th>
                                                        <th>Remark</th>
                                                        <th>Pay Via</th>
                                                        <th>Added By</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <?php $ttl = 0; ?>
                                                    <?php
                                                    if ($query[0]["discount"] > 0) {
                                                        ?>
                                                        <tr>
                                                            <td><?= $job_details[0]["date"] ?></td>
                                                            <td>Rs.<?php
                                                                echo round(($query[0]["price"] * $query[0]["discount"]) / 100);
                                                                $ttl = $ttl + round(($query[0]["price"] * $query[0]["discount"]) / 100);
                                                                ?></td>
                                                            <td>Discount</td>
                                                            <td></td>
                                                            <td><?= $discount_added_by[0]["name"] ?></td>
                                                            <td></td>
                                                        </tr>
                                                    <?php } ?>
                                                    <?php
                                                    foreach ($job_master_receiv_amount as $rakey): $ttl = $ttl + $rakey["amount"];
                                                        if ($rakey["amount"] != '') {
                                                            ?>
                                                            <tr>
                                                                <td><?= $rakey["createddate"] ?></td>
                                                                <td>Rs.<?= $rakey["amount"] ?></td>
                                                                <td><?= ucfirst($rakey["remark"]) ?></td>
                                                                <td>CASH</td>
                                                                <td><?php if ($rakey["type"] != "User Pay") { ?><?= ucfirst($rakey["name"]); ?><?php } ?></td>
                                                                <td>
                                                                    <?php if ($rakey["type"] != "User Pay" && $login_data['type'] == 1 || $login_data['type'] == 2) { ?>
                                                                        <a href="javascript:void(0);" onclick="delete_received_payment('<?= $rakey["id"] ?>', '<?= $rakey["amount"] ?>');"><i class="fa fa-trash"></i></a>
                                                            <?php } ?>
                                                                </td>
                                                            </tr>
    <?php } endforeach; ?>
                                                    <tr style="border-top:2px solid black">
                                                        <td><b>Total Received Amount</b></td>
                                                        <td colspan="4"><b>Rs.<?= $ttl; ?></b></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="modal-footer">

                                            <!--                            <button type="button" class="btn btn-primary" id="amount_submit_btn" disabled="">Add</button>-->
                                            <input type="submit" value="Add" class="btn btn-primary" id="amount_submit_btn"/>
                                            <script>
                                                function send_receiv_amount() {
                                                    $("#amount_submit_btn").attr('disabled', 'disabled');
                                                    //$("#payment_receiv_form_id").submit();
                                                    document.getElementById("frm_sb").click();
                                                    return true;
                                                    //$("#frm_sb").click();
                                                }
                                            </script>
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
<?= form_close(); ?>
                                    </div>

                                </div>
                            </div>
                            <?php /* <div class="box box-primary">

                              <div class="box-header">
                              <!-- form start -->
                              <h3 class="box-title">Upload documents</h3>
                              </div>
                              <a href="javascript:void(0)" id="reportgenrate" data-toggle="modal" data-target="#documentuplode" class="btn btn-primary pull-right">Upload documents</a>

                              <div class="box-body">


                              <table class="table table-striped" id="city_wiae_price123">
                              <tbody id="t_body123">
                              <?php if($jobsdocks != null){ foreach ($jobsdocks as $docks) { ?>
                              <tr id="docksr_<?= $docks['id']; ?>">
                              <td><a href="<?php echo base_url(); ?>upload/labducuments/<?php echo $docks['document'].'?' . time(); ?>" target="_blank"> <?php echo $docks['name']; ?> </a></td>

                              <td><a href="javascript:void(0);" id="docks_<?= $docks['id']; ?>" class="docksdelete" >Delete</a></td>
                              </tr>
                              <?php } }else{ echo "<tr><td colspan='2'>No documentation available</td></tr>"; } ?>
                              </tbody>
                              </table>
                              </div>
                              <script>
                              $(document).on("click", ".docksdelete", function () {

                              var id = this.id;
                              var splitid = id.split("_")
                              var pdfid = splitid[1];

                              $("#" + id).prop('disabled', true);

                              $.ajax({url: "<?php echo base_url() . "b2b/logistic/documentdelete"; ?>",
                              type: "POST",
                              data: {pid: pdfid},
                              error: function (jqXHR, error, code) {
                              },
                              success: function (data) {

                              if (data == 1) {
                              $("#docksr_" + pdfid).remove();
                              }
                              }
                              });

                              });
                              </script>
                              </div> */ ?>

                        </div>
<?php if ($login_data["type"] != 4) { ?>
                            <div class="col-md-6">
                                <a href="<?= base_url(); ?>upload/barcode/<?php echo $barcode_detail[0]["pic"]; ?>" target="_blank"><img src="<?= base_url(); ?>upload/barcode/<?php echo $barcode_detail[0]["pic"]; ?>" class="col-sm-12" alt="Image not available."/></a>
                            </div>
							<div class="col-md-6">
                                <div class="col-sm-12 res_pdng_0"><div id="job_tracking"></div></div>
                            </div>
                        <?php } ?>
                        <?php /*      <div class="col-sm-12">
                          <div class="col-sm-6">
                          <div class="box box-primary">
                          <div class="box-header">
                          <!-- form start -->
                          <h3 class="box-title">Assign Phlebo</h3>
                          </div>
                          <div class="box-body">
                          <div class="col-sm-12">
                          <div class="form-group col-sm-12  pdng_0">
                          <label class="col-sm-3 pdng_0" for="exampleInputFile">Select Phlebo :</label>
                          <div class="col-sm-9 pdng_0">
                          <select id="phlebo"  name="phlebo" class="chosen">
                          <option value="">--Select--</option>
                          <?php foreach ($phlebo_list as $phlebo) { ?>
                          <option value="<?php echo $phlebo['id']; ?>"><?= ucfirst($phlebo["name"]) . "-" . $phlebo["mobile"]; ?></option>
                          <?php } ?>
                          </select>
                          </div>
                          </div>
                          <div class="form-group col-sm-12  pdng_0">
                          <label for="exampleInputFile" class="col-sm-3 pdng_0">Date : </label>
                          <div class="col-sm-9 pdng_0">
                          <input type="text" id="date" name="phlebo_date" class="form-control datepicker-input" onchange="get_time(this.value);"/>
                          </div>
                          </div>
                          <div class="form-group col-sm-12  pdng_0">
                          <label for="exampleInputFile" class="col-sm-3 pdng_0">Time :</label>
                          <div class="col-sm-9 pdng_0">
                          <select id="time_slot"  name="phlebo_time" class="chosen">
                          <option value="">--Select--</option>
                          </select>
                          </div>
                          </div>
                          <div class="form-group col-sm-12  pdng_0" style="display:none;">
                          <div class="col-sm-9 pdng_0" style="float:right;">
                          <input type="checkbox" id="notify" name="notify" value="1" checked> Notify Customer
                          </div>
                          </div>
                          </div>
                          </div>
                          </div>
                          </div>
                          <div class="col-sm-6" id="order_details" style="display: none;">
                          <div class="box box-primary">
                          <div class="box-header">
                          <!-- form start -->
                          <h3 class="box-title">Order Details</h3>
                          </div>
                          <div class="box-body" id="order_table">
                          </div>
                          </div>
                          </div>
                          </div> */ ?>
                        </section>
                        <script  type="text/javascript">
                            $(document).on("click", ".pdfdelete", function () {


                                var id = this.id;
                                var splitid = id.split("_")
                                var pdfid = splitid[1];
                                $("#" + id).prop('disabled', true);

                                $.ajax({url: "<?php echo base_url() . "b2b/logistic/pdfdelete"; ?>",
                                    type: "POST",
                                    data: {pid: pdfid},
                                    error: function (jqXHR, error, code) {
                                    },
                                    success: function (data) {
                                        if (data == 1) {
                                            $("#pdf_" + pdfid).remove();
                                        }
                                    }
                                });

                            });
                        </script>														
<?php if ($login_data["type"] != 4) { ?>
                            <script>
                                $city_cnt = <?= $cnt ?>;
                                function Validation() {
                                    var cnt = 0;
                                    var pm = 1;
                                    var count = $("#count").val();
                                    for (cnt = 0; cnt <= count; cnt++) {
                                        $("#descerror_" + cnt).html('');
                                        $("#reporterror_" + cnt).html('');
                                        var desc = $("#desc_" + cnt).val();
                                        var report = $("#report_" + cnt).val();
                                        if (desc == '') {
                                            pm = 1;
                                            $("#descerror_" + cnt).html('Description is required.');
                                        } else {
                                            pm = 0;
                                            $("#descerror_" + cnt).html('');
                                        }
                                        if (report == '') {
                                            pm = 1;
                                            $("#reporterror_" + cnt).html('Report is required.');
                                        } else {
                                            pm = 0;
                                            $("#reporterror_" + cnt).html('');
                                        }
                                    }
                                    if (pm == 0) {
                                        $("#submit_report").submit();
                                    }
                                }
                                function change_status() {
                                    var status = $("#status").val();
                                    var job_id = $("#job_id").val();
                                    if (status == "") {
                                        alert("Please Select Status!");
                                    } else {
                                        $.ajax({
                                            url: "<?php echo base_url(); ?>job_master/changing_status_job",
                                            type: 'post',
                                            data: {status: status, jobid: job_id},
                                            success: function (data) {
                                                if (data == 1) {
                                                    //     console.log("data"+data);
                                                    window.location = "<?php echo base_url(); ?>job-master/job-details/<?php echo $cid; ?>";
                                                                        }
                                                                    }
                                                                });
                                                            }
                                                        }
                            </script>
                            <script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
                            <script  type="text/javascript">
                                                        function get_test_price() {
                                                            var test_val = $("#test").val();
                                                            $("#test_error").html("");
                                                            //$("#desc_error").html("");
                                                            var cnt = 0;
                                                            if (test_val.trim() == '') {
                                                                $("#test_error").html("Test is required.");
                                                                cnt = cnt + 1;
                                                            }
                                                            if (cnt > 0) {
                                                                return false;
                                                            }
                                                            var skillsSelect = document.getElementById("test");
                                                            var selectedText = skillsSelect.options[skillsSelect.selectedIndex].text;
                                                            var prc = selectedText.split('(Rs.');
                                                            var prc1 = prc[1].split(')');
                                                            var prc2 = prc[1].split('( Rs.');
                                                            var prc3 = prc2[1].split(')');
                                                            var pm = skillsSelect.value;
                                                            var explode = pm.split('-');

                                                            var pm = skillsSelect.value;
                                                            var explode = pm.split('-');
                                                            $("#city_wiae_price").append('<tr id="tr_' + $city_cnt + '"><td>' + prc[0] + '</td><td>Rs.' + prc1[0] + '</td><td>Rs.' + prc3[0] + '</td><td><a href="javascript:void(0);" onclick="delete_city_price(\'' + $city_cnt + '\',\'' + prc1[0] + '\',\'' + prc3[0] + '\',\'' + prc[0] + '\',\'' + skillsSelect.value + '\')">Delete</a></td></tr>');

                                                            $("#test option[value='1']").remove();
                                                            var old_dv_txt = $("#hidden_test").html();
                                                            /*Total price calculate start*/
                                                            var old_price = $("#total_id").val();
                                                            $("#total_id").val(+old_price + +prc3[0]);
                                                            var dscnt = $("#discount").val();
                                                            get_discount_price(dscnt);
                                                            /*Total price calculate end*/
                                                            $("#hidden_test").html(old_dv_txt + '<input id="tr1_' + $city_cnt + '" type="hidden" name="test[]" value="' + skillsSelect.value + '"/>');
                                                            $city_cnt = $city_cnt + 1;
                                                            //$("#test").val("");
                                                            $("#desc").val("");
                                                            $("#test option[value='" + skillsSelect.value + "']").remove();
                                                            $("#test").val('').trigger('chosen:updated');
                                                            $('#exampleModal').modal('hide');


                                                        }
                                                        function show_package_details(val) {
                                                        }
                                                        function delete_city_price(id, tprice, prc, name, value) {
                                                            var tst = confirm('Are you sure?');
                                                            if (tst == true) {
                                                                /*Total price calculate start*/
                                                                $('#test').append('<option value="' + value + '">' + name + '(Rs.' + tprice + ') ( Rs.' + prc + ')</option>').trigger("chosen:updated");
                                                                var old_price = $("#total_id").val();
                                                                $("#total_id").val(old_price - prc);
                                                                var dscnt = $("#discount").val();
                                                                get_discount_price(dscnt);
                                                                $("#tr_" + id).remove();
                                                                $("#tr1_" + id).remove();

                                                            }
                                                            setTimeout(function () {
                                                                get_price();
                                                            }, 1000);
                                                            $('#test').trigger("chosen:updated");
                                                        }
                                                        $old_cc = <?php
    if ($job_details[0]["collection_charge"] > 0) {
        echo $job_details[0]["collection_charge"];
    } else {
        echo 0;
    }
    ?>;
                                                        function get_collection_price(val) {

                                                            if (val == '') {
                                                                val = 0;
                                                            }
                                                            var total = $("#total_id").val();
                                                            if (total == "") {
                                                                total = 0;
                                                            }
                                                            var payable_val = $("#payable_val").val();
                                                            if (payable_val == "") {
                                                                payable_val = 0;
                                                            }
                                                            payable_val = parseInt(payable_val);
                                                            total = parseInt(total);
                                                            val = parseInt(val);
                                                            var final_total = total + val - $old_cc;
                                                            var final_payable = payable_val + val - $old_cc;
                                                            if (final_payable == '') {
                                                                final_payable = 0;
                                                            }
                                                            if (final_total == '') {
                                                                final_total = 0;
                                                            }
                                                            $("#total_id").val(final_total);
                                                            $("#payable_val").val(final_payable);
                                                            $old_cc = val;
                                                            //alert(total); 
                                                            return false;
                                                        }
                                                        setTimeout(function () {
                                                            get_collection_price(<?php
    if ($job_details[0]["collection_charge"] > 0) {
        echo $job_details[0]["collection_charge"];
    } else {
        echo 0;
    }
    ?>);
                                                        }, 1500);
                                                        function get_discount_price(val) {
                                                            setTimeout(function () {
                                                                if (val != '' || val != '0') {

                                                                    var total = $("#total_id").val();
                                                                    var dis = val;

                                                                    var discountpercent = val / 100;
                                                                    var discountprice = (total * discountpercent);
                                                                    var payableamount = total - discountprice;
                                                                    $("#payable_val").val(payableamount);
                                                                } else {
                                                                    var ttl = $("#total_id").val();
                                                                    $("#payable_val").val(ttl);
                                                                }
                                                            }, 1000);
                                                        }
                                                        function show_details(val) {
                                                            $.ajax({
                                                                url: "<?php echo base_url(); ?>service/service_v2/package_details",
                                                                type: "POST",
                                                                data: {pid: val},
                                                                error: function (jqXHR, error, code) {
                                                                },
                                                                success: function (data) {
                                                                    var json_data = JSON.parse(data);
                                                                    $("#description").empty();
                                                                    $("#description").append('<p>' + json_data.data + '</p>');
                                                                    // console.log(data);
                                                                }
                                                            });
                                                        }
                            </script>
                            <div class="modal fade" id="myModal_view" role="dialog">
                                <div class="modal-dialog">

                                    <!-- Modal content-->
                                    <div class="modal-content srch_popup_full">
                                        <div class="modal-header srch_popup_full srch_head_clr">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title clr_fff">Package Detail</h4>
                                        </div>
                                        <div class="modal-body srch_popup_full">
                                            <div class="srch_popup_full srch_popup_acco">
                                                <div id="accordion1" class="panel-group accordion transparent">
                                                    <div class="panel">
                                                        <div class="panel-collapse collapse in" role="tablist" aria-expanded="true">
                                                            <div class="panel-content" id="description">

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
<?php } ?>
                    </div><!-- /.tab-pane -->
                </div><!-- /.tab-content -->
        </div><!-- nav-tabs-custom -->
    </div>
    <!-- /.row -->
</section>


<div class="modal fade" id="documentuplode" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content srch_popup_full">
            <div class="modal-header srch_popup_full srch_head_clr">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title clr_fff">Upload document</h4>
            </div>
            <div class="modal-body srch_popup_full">

<?php echo form_open_multipart("b2b/Logistic/uplode_document/$id", array("role" => 'form', "id" => 'uplodedocks', "method" => 'post')); ?>
                <div class="box-body">
                    <div class="form-group">
                        <label class="control-label">Name:</label>
                        <input type="test"  id="docksname" class="form-control" name="docksname" required="">
                        <small></small><br>
                    </div>

                    <div class="form-group">
                        <input type="file"  id="docks_report" name="docks_uplode" required="">
                        <small></small><br>

                    </div>
                    <div class="box-footer">
                        <input class="btn btn-primary" value="Upload" type="submit">
                    </div>
                </div>
                </form>

            </div>
        </div>
    </div>
</div>	


<?php if ($login_data["type"] != 4) { ?>
    <div class="modal fade" id="creditor_model" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Change Creditors</h4>
                </div>
    <?php echo form_open("job_master/change_credential/" . $cid, array("method" => "POST", "role" => "form")); ?>
                <div class="modal-body">
                    <div id="creditor_div">
                        <div class="form-group">
                            <label for="recipient-name" class="control-label">Select Creditors:</label>
                            <select id="creditor_id" name="creditor" class="chosen" style="width: 100%" required="">
                                <option value="">--Select--</option>
                                <?php foreach ($creditors as $branch) {
                                    ?>
                                    <option value="<?php echo $branch['id']; ?>" <?php
                                    if ($selected_creditor[0]["creditors_fk"] == $branch["id"]) {
                                        echo "selected";
                                    }
                                    ?>><?php echo ucwords($branch['name']) . " - " . $branch['mobile']; ?></option>

                                    <?php
                                }
                                ?>
                            </select>
                            <span style="color:red;" id="creditor_error"></span>
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="control-label">Amount:</label>
                            <input type="number" name="creditor_amount" id="creditor_amount" class="form-control" value="<?php echo $selected_creditor[0]["amount"]; ?>">
                            <span style="color:red;" id="creditor_amount_error"></span>
                        </div>
                    </div>
                    <div id="otp_creditor_div" style="display:none;">
                        <div class="form-group has-feedback">
                            <div class="input-group">
                                <span class="input-group-addon" style=""><i class="fa fa-key"></i></span>
                                <input type="text"  placeholder="OTP" id="otp_val" class="form-control" name="otp">
                            </div>
                            <span style="color:red;" id="otp_error"></span>
                            <span style="color:green;" id="otp_success"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-xs-8">    
                        <span id="loader_creditor_div" style="display:none;"><img src="<?= base_url(); ?>upload/opc-ajax-loader.gif" style="height:28px;margin-left:10px"></span>
                        <a href="javascript:void(0);" id="resend_opt" style="display:none;" onclick="credit_resend();">Resend OTP</a>
                        <a href="javascript:void(0);" id="mycounter" style=""></a>
                    </div><!-- /.col -->
                    <div class="col-xs-4">
                        <button type="button" class="btn btn-primary" id="creditor_amount_submit_btn" onclick="creditor_otp();" <?php
                        if ($selected_creditor[0]["creditors_fk"] != '') {
                            echo "disabled";
                        }
                        ?>>Update</button>
                        <button type="button" class="btn btn-primary" id="creditor_amount_submit_btn_otp" style="display:none;" onclick="creditor_otp_check();">Update</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
    <?= form_close(); ?>
            </div>

            <script>
                function countdown(elementName, minutes, seconds)
                {
                    var element, endTime, hours, mins, msLeft, time;

                    function twoDigits(n)
                    {
                        return (n <= 9 ? "0" + n : n);
                    }

                    function updateTimer()
                    {
                        msLeft = endTime - (+new Date);
                        if (msLeft < 1000) {
                            //element.innerHTML = "countdown's over!";
                            $("#mycounter").attr("style", "display:none;");
                            $("#resend_opt").attr("style", "");
                            $("#resend_opt").attr("style", "margin-bottom:10px");
                            element.innerHTML = '';
                        } else {
                            $("#resend_opt").attr("style", "display:none;");
                            time = new Date(msLeft);
                            hours = time.getUTCHours();
                            mins = time.getUTCMinutes();
                            element.innerHTML = 'Resend Code after <b>' + (hours ? hours + ':' + twoDigits(mins) : mins) + ':' + twoDigits(time.getUTCSeconds()) + ' </b> second';
                            setTimeout(updateTimer, time.getUTCMilliseconds() + 500);
                        }
                    }
                    element = document.getElementById(elementName);
                    endTime = (+new Date) + 1000 * (60 * minutes + seconds) + 500;
                    updateTimer();
                }
                $("#creditor_amount").keyup(function () {
                    $("#creditor_amount_error").html("");
                    var val = parseInt(this.value);
                    var ttl_amt = '<?= $job_details[0]['payable_amount']; ?>';
                    ttl_amt = parseInt(ttl_amt);
                    if (val != null) {
                        console.log("ttl" + ttl_amt + " input" + val);
                        if (ttl_amt >= val) {
                            $("#creditor_amount_submit_btn").removeAttr("disabled");
                        } else {
                            $("#creditor_amount_submit_btn").attr("disabled", "");
                            $("#creditor_amount_error").html("Given amount is more than payable amount.");
                        }
                    } else {
                        $("#creditor_amount_submit_btn").attr("disabled", "");
                        $("#creditor_amount_error").html("Invalid amount.");
                    }
                });
                function creditor_otp() {
                    var creditor_fk = $("#creditor_id").val();
                    var amount = $("#creditor_amount").val();
                    var job_fk = "<?php echo $job_details[0]["id"]; ?>";
                    $("#creditor_error").html("");
                    $("#creditor_amount_error").html("");
                    var temp = 1;
                    if (creditor_fk == "") {
                        $("#creditor_error").html("Please select Creditors.");
                        temp = 0;
                    }
                    if (amount == "") {
                        $("#creditor_amount_error").html("Add Amount.");
                        temp = 0;
                    }
                    if (temp == 1) {
                        $.ajax({
                            url: "<?php echo base_url(); ?>b2b/logistic/creditors_add",
                            type: 'post',
                            data: {creditor_id: creditor_fk, amount: amount, job_id: job_fk},
                            beforeSend: function () {
                                $("#loader_creditor_div").attr("style", "");
                                $("#creditor_amount_submit_btn").attr("disabled", "disabled");
                            },
                            success: function (data) {
                                countdown("mycounter", 1, 0);
                                $("#creditor_div").attr("style", "display:none;");
                                $("#creditor_amount_submit_btn").attr("style", "display:none;");
                                $("#otp_creditor_div").attr("style", "");
                                $("#mycounter").attr("style", "");
                                $("#resend_opt").attr("style", "");
                                $("#creditor_amount_submit_btn_otp").attr("style", "");
                            }, complete: function () {
                                $("#loader_creditor_div").attr("style", "display:none;");
                            }
                        });
                    }
                }
                function credit_resend() {
                    var creditor_fk = $("#creditor_id").val();
                    var amount = $("#creditor_amount").val();
                    var job_fk = "<?php echo $job_details[0]["id"]; ?>";
                    $.ajax({
                        url: "<?php echo base_url(); ?>b2b/logistic/creditors_resend",
                        type: 'post',
                        data: {creditor_id: creditor_fk, amount: amount, job_id: job_fk},
                        beforeSend: function () {
                            $("#loader_creditor_div").attr("style", "");
                            $("#creditor_amount_submit_btn").attr("disabled", "disabled");
                        },
                        success: function (data) {
                            countdown("mycounter", 1, 0);
                            $("#creditor_div").attr("style", "display:none;");
                            $("#creditor_amount_submit_btn").attr("style", "display:none;");
                            $("#otp_creditor_div").attr("style", "");
                            $("#mycounter").attr("style", "");
                            $("#resend_opt").attr("style", "");
                            $("#creditor_amount_submit_btn_otp").attr("style", "");
                        }, complete: function () {
                            $("#loader_creditor_div").attr("style", "display:none;");
                        }
                    });
                }
                function creditor_otp_check() {
                    $("#otp_success").html("");
                    $("#otp_error").html("");
                    $("#creditor_amount_submit_btn_otp").prop("disabled", true);
                    var otp = $("#otp_val").val();
                    var job = "<?php echo $job_details[0]["id"]; ?>";
                    var creditor_fk = $("#creditor_id").val();
                    var amount = $("#creditor_amount").val();
                    if (otp.trim() != '') {
                        $.ajax({
                            url: '<?= base_url(); ?>b2b/logistic/creditor_check_otp',
                            type: 'post',
                            data: {otp: otp, job: job, creditor_fk: creditor_fk, amount: amount},
                            success: function (data) {
                                var jsondata = JSON.parse(data);
                                if (jsondata.status == 0) {
                                    $("#creditor_amount_submit_btn_otp").prop("disabled", false);
                                    $("#otp_error").html(jsondata.msg);
                                }
                                if (jsondata.status == 1) {
                                    $("#otp_success").html(jsondata.msg);
                                    setTimeout(function () {
                                        window.location.reload();
                                    }, 1000);
                                }
                            },
                            error: function (jqXhr) {
                                $("#creditor_amount_submit_btn_otp").prop("disabled", false);
                                alert('Oops somthing wrong Tryagain!.');
                            }
                        });
                    } else {
                        $("#creditor_amount_submit_btn_otp").prop("disabled", false);
                        $("#otp_error").html("OTP field is required.");
                    }
                }
            </script>

        </div>
    </div>
    <div class="modal fade" id="genreatereport" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content srch_popup_full">
                <div class="modal-header srch_popup_full srch_head_clr">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title clr_fff">Generate report</h4>
                </div>
                <div class="modal-body srch_popup_full"> 

    <?php echo form_open_multipart("b2b/Logistic/genrate_report/$id", array("role" => 'form', "id" => '', "method" => 'post')); ?>
                    <div class="box-body">
                        <div class="form-group">
                            <input type="file" multiple id="common_report" name="common_report[]" >
                            <small></small><br>
                            <input type="hidden" name="type_common_report" value="c"/>
                        </div>
                        <?php if(!empty($old_report_images)){ echo "Old Report Images<br>"; } foreach($old_report_images as $rkey){ ?>
                        <img src="<?=base_url();?>upload/B2breport/<?=$rkey["image"]?>" style="width:100px;height: auto;margin: 3px 3px 3px 3px;" title="Report Image">&nbsp;&nbsp;<a href="<?=base_url();?>/b2b/Logistic/remove_report_image?jid=<?php echo $job_details[0]["barcode_fk"]; ?>&id=<?=$rkey["id"]?>" onclick="return confirm('Are you sure?');"><i class="fa fa-trash"></i></a><br>
                        <?php } ?>
                        <?php /*  <div class="form-group">
                          <textarea class="form-control" name="desc_common_report"><?php echo $job_details[0]['report_description']; ?></textarea>
                          </div>
                          <div class="form-group">
                          <label class="radio-inline">
                          <input type="radio" checked value="1" name="latterpad">with letterhead
                          </label>
                          <label class="radio-inline">
                          <input type="radio" value="2" name="latterpad"> without letterhead
                          </label>
                          </div> */ ?>

                        <div class="box-footer">
                            <input class="btn btn-primary" value="Generate report" type="submit">
                        </div>
                    </div>
                    </form>

                </div>
            </div>
        </div>
    </div>


    <script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
    <script  type="text/javascript">
                    $(function () {

                        $('.chosen-select').chosen();

                    });

    </script> 
    <script src="<?php echo base_url(); ?>plugins/timepick/js/timepicki.js"></script>
    <script>
                    $('#time').timepicki();
    </script>
    <script type="text/javascript">

        $("#reportgenrate").click(function () {
            $("#genreatereport").modal("show");

        });
        function get_pending_count2() {

            $.ajax({
                url: "<?php echo base_url(); ?>job_master/pending_count/",
                error: function (jqXHR, error, code) {
                    // alert("not show");
                },
                success: function (data) {
                    //     console.log("data"+data);
                    //var jsonparse = JSON.Parse(data);
                    var obj = $.parseJSON(data);
                    console.log(obj.job_count);
                    //document.getElementById('pending_count').innerHTML = "";
                    //document.getElementById('pending_count').innerHTML = obj.job_count;
                    document.getElementById('pending_count_1').innerHTML = obj.job_count;
                    document.getElementById('pending_count_2').innerHTML = obj.package_count;
                    document.getElementById('test_package_count').innerHTML = obj.all_inquiry;
                    if (obj.tickepanding != '0') {
                        document.getElementById('supportpanding').innerHTML = obj.tickepanding;
                    }
                    if (obj.job_count != '0') {
                        document.getElementById('pending_count').innerHTML = obj.job_count;
                    }
                    if (obj.contact_us_count != '0') {
                        document.getElementById('contact_us').innerHTML = obj.contact_us_count;
                    }

                }
            });

        }

        get_pending_count2();

        /*window.setInterval(function() {
         $('.chosen-select').trigger('chosen:updated');
         }, 1000); */
        function setCustomerValue(cid) {
            getDetailsByid(cid);

            $("#existingCustomer").val(cid);
            $('#existingCustomer').trigger('chosen:updated');
        }
        function setFormValue(name, email, mobile) {
            $("#name").val(name);
            $("#email").val(email);
            $("#phone").val(mobile);
        }
        function getDetailsByid(val) {
            $.ajax({
                url: "<?php echo base_url(); ?>Admin/get_user_info",
                type: 'post',
                data: {user_id: val},
                success: function (data) {
                    var json_data = JSON.parse(data);
                    cust_id = json_data.id.trim();
                    if (json_data.full_name.trim()) {
                        $("#name").val(json_data.full_name);
                    } else {
                        $("#name").val("");
                    }
                    if (json_data.mobile.trim()) {
                        $("#phone").val(json_data.mobile);
                    } else {
                        $("#phone").val("");
                    }
                    if (json_data.email.trim()) {
                        $("#email").val(json_data.email);
                    } else {
                        $("#email").val("");
                    }
                    if (json_data.gender.trim()) {
                        //$("#gender").val(json_data.gender);
                        $("#gender:selected").removeAttr("selected");
                        var elements = document.getElementById("gender").options;
                        for (var i = 0; i < elements.length; i++) {
                            elements[i].removeAttribute("selected");
                            elements[i].selected = false;
                        }
                        for (var i = 0; i < elements.length; i++) {
                            /*elements[i].selected = false;*/
                            if (elements[i].value.toUpperCase() == json_data.gender.toUpperCase()) {
                                elements[i].setAttribute("selected", "selected");
                            }
                        }
                    }
                    if (json_data.address.trim()) {
                        $("#address").val(json_data.address);
                    } else {
                        $("#address").val("");
                    }
                }
            });
        }
        function show_test_model1() {
            //$("#show_test_btn").attr('disabled',true);
            var values = $("input[name='test[]']").map(function () {
                return $(this).val();
            }).get();
            var ctid = $('#test_city').val();
            $.ajax({
                url: "<?php echo base_url(); ?>job_master/get_test_list1/",
                type: "POST",
                data: {ids: values, ctid: ctid},
                error: function (jqXHR, error, code) {
                },
                beforeSend: function () {
                    $("#loader_div").attr("style", "");
                    $("#show_test_btn").attr("disabled", "disabled");
                },
                success: function (data) {
                    $("#city_wise_test").empty();
                    $("#city_wise_test").append(data);
                    // console.log(data);
                },
                complete: function () {
                    $("#loader_div").attr("style", "display:none;");
                    $("#show_test_btn").removeAttr("disabled");
                },
            });
            //$('#exampleModal').modal('show');
            console.log(values);
        }
        function save_user_data() {
            var customer_test = $("input[name='test[]']").map(function () {
                return $(this).val();
            }).get();
            var customer_fk = $('#existingCustomer12').val();
            var customer_name = $('#name').val();
            var customer_mobile = $('#phone').val();
            var customer_email = $('#email').val();
            var customer_gender = $('#gender').val();
            var customer_city = $('#test_city').val();
            var customer_address = $('#address').val();
            var pay_discount = $('#discount').val();
            var payable_amount = $('#payable_val').val();
            var referral_by = $('#referral_by').val();
            var phlebo = $('#phlebo').val();
            var phlebo_date = $('#date').val();
            var phlebo_time = $('#time_slot').val();
            var source = $('#source').val();
            if ($('#notify').prop('checked')) {
                var notify = 1;
            } else {
                var notify = 0;
            }
            var total_amount = $('#total_id').val();
            var note = $('#note').val();
            $.ajax({
                url: "<?php echo base_url(); ?>Admin/get_telecaller_remain/",
                type: "POST",
                data: {cid: customer_fk, cname: customer_name, cmobile: customer_mobile, cemail: customer_email, cgender: customer_gender, ctestcity: customer_city, caddress: customer_address, cbooktest: customer_test, cdis: pay_discount, cpayableamo: payable_amount, ctotalamo: total_amount, cnote: note, referral_by: referral_by, source: source, phlebo: phlebo, phlebo_date: phlebo_date, phlebo_time: phlebo_time, notify: notify},
                error: function (jqXHR, error, code) {
                },
                success: function (data) {
                    if (data != 0) {
                        $("#t_body").empty();
                        $('#existingCustomer12').val('').trigger('chosen:updated');
                        $('#referral_by').val('').trigger('chosen:updated');
                        $('#source').val('').trigger('chosen:updated');
                        $('#phlebo').val('').trigger('chosen:updated');
                        $('#date').val('');
                        $('#time_slot').val('').trigger('chosen:updated');
                        $('#name').val('');
                        $('#phone').val('');
                        $('#email').val('');
                        $('#gender').val('');
                        $('#test_city').val('');
                        $('#address').val('');
                        $('#test').val('').trigger('chosen:updated');
                        $('#discount').val('0');
                        $('#payable_val').val('0');
                        $('#total_id').val('0');
                        $('#note').val('');
                    }
                },
                //            complete: function () {
                //                $("#loader_div").attr("style", "display:none;");
                //                $("#show_test_btn").removeAttr("disabled");
                //            },
            });
        }
        function get_time(val) {
            $.ajax({
                url: '<?php echo base_url(); ?>phlebo-api_v2/get_phlebo_schedule',
                type: 'post',
                data: {bdate: val},
                success: function (data) {
                    var json_data = JSON.parse(data);
                    if (json_data.status == 1) {
                        for (var i = 0; i < json_data.data.length; i++) {
                            if (json_data.data[i].booking_status == 'Available') {
                                //$("#phlebo_shedule").append("<a href='javascript:void(0);' id='time_slot_" + json_data.data[i].id + "' onclick='get_select_time(this," + json_data.data[i].time_slot_fk + ");'><p>" + json_data.data[i].start_time + " TO " + json_data.data[i].end_time + "<br>(" + json_data.data[i].booking_status + ")</p></a>");
                                $('#time_slot').append('<option value="' + json_data.data[i].time_slot_fk + '">' + json_data.data[i].start_time + ' TO ' + json_data.data[i].end_time + ' (Available)</option>').trigger("chosen:updated");
                            } else {
                                //$("#phlebo_shedule").append("<a href='javascript:void(0);'><p>" + json_data.data[i].start_time + " TO " + json_data.data[i].end_time + "<br>(" + json_data.data[i].booking_status + ")</p></a>");
                                $('#time_slot').append('<option disabled>' + json_data.data[i].start_time + ' TO ' + json_data.data[i].end_time + ' (Unavailable)</option>').trigger("chosen:updated");
                            }
                        }
                    } else {
                        if (json_data.error_msg == 'Time slot unavailable.') {
                            //$("#phlebo_shedule").empty();
                            //$("#phlebo_shedule").append('<div class="form-group"><label for="message-text" class="form-control-label">Request to consider as emergency:-</label><input type="checkbox" value="emergency" onclick="check_emergency(this);" value="emergency" id="as_emergency"></div>'+"<span style='color:red;'>" + json_data.error_msg + "</span>");
                        } else {
                            //$("#phlebo_shedule").html("<span style='color:red;'>" + json_data.error_msg + "</span>");
                        }
                    }
                },
                error: function (jqXhr) {
                    $("#phlebo_shedule").html("");
                },
                complete: function () {
                    //$("#shedule_loader_div").attr("style", "display:none;");
                    //$("#send_opt_1").removeAttr("disabled");
                },
            });
        }
    </script> 
    <!--Nishit code end-->
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
    </script>
    <script type="text/javascript">
        window.setTimeout(function () {
            $(".alert").fadeTo(500, 0).slideUp(500, function () {
                $(this).remove();
            });
        }, 4000);</script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>

    <script>
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd'
        });
        function bid_datepicker() {
            //        $('.datepicker').datepicker({
            //            format: 'yyyy-mm-dd'
            //        });
        }
        setTimeout(function () {
            $.ajax({
                url: '<?php echo base_url(); ?>Admin/get_refered_by',
                type: 'post',
                data: {val: 1},
                success: function (data) {
                    var json_data = JSON.parse(data);
                    $("#referral_by").html(json_data.refer);
                    //$("#test").html(json_data.test_list);
                    //$("#existingCustomer12").html(json_data.customer);
                    $('.chosen').trigger("chosen:updated");
                },
                error: function (jqXhr) {
                    $("#referral_by").html("");
                    //$("#test").html("");
                    //$("#existingCustomer12").html("");
                },
                complete: function () {
                    //$("#shedule_loader_div").attr("style", "display:none;");
                    //$("#send_opt_1").removeAttr("disabled");
                },
            });
            var test_city = $("#test_city").val();
            //        $.ajax({
            //            url: '<?php echo base_url(); ?>Admin/get_test_list',
            //            type: 'post',
            //            data: {val: test_city},
            //            success: function (data) {
            //                var json_data = JSON.parse(data);
            //                //$("#referral_by").html(json_data.refer);
            //                $("#test").html(json_data.test_list);
            //                //$("#existingCustomer12").html(json_data.customer);
            //                $('.chosen').trigger("chosen:updated");
            //            },
            //            error: function (jqXhr) {
            //                //$("#referral_by").html("");
            //                $("#test").html("");
            //                //$("#existingCustomer12").html("");
            //            },
            //            complete: function () {
            //                //$("#shedule_loader_div").attr("style", "display:none;");
            //                //$("#send_opt_1").removeAttr("disabled");
            //            },
            //        });
            $.ajax({
                url: '<?php echo base_url(); ?>Admin/get_customer_list',
                type: 'post',
                data: {val: 1},
                success: function (data) {
                    var json_data = JSON.parse(data);
                    //$("#referral_by").html(json_data.refer);
                    //$("#test").html(json_data.test_list);
                    $("#existingCustomer12").html(json_data.customer);
                    $('.chosen').trigger("chosen:updated");
                },
                error: function (jqXhr) {
                    //$("#referral_by").html("");
                    //$("#test").html("");
                    $("#existingCustomer12").html("");
                },
                complete: function () {
                    //$("#shedule_loader_div").attr("style", "display:none;");
                    //$("#send_opt_1").removeAttr("disabled");
                },
            });
            var lid = "<?php echo $barcode_detail[0]['collect_from'] ?>";
            var test = "<?= $addedtest ?>";
            var pack = "<?= $addedpack ?>";
            $.ajax({
                url: "<?php echo base_url(); ?>b2b/logistic/get_lab_testsedit",
                type: 'post',
                data: {lab: lid, pack: pack, test: test},
                success: function (data) {
                    $("#search_test").html(data);
                    //$("#existingCustomer12").html(json_data.customer);
                    $('.chosen').trigger("chosen:updated");
                }
            });
            /*get_discount_price(<?php
                        if (empty($job_details[0]["discount"])) {
                            echo 0;
                        } else {
                            echo $job_details[0]["discount"];
                        }
                        ?>);*/
        }, 500);
        function isNumberKey(evt)
        {
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode != 46 && charCode > 31
                    && (charCode < 48 || charCode > 57)) {
                if (charCode == 45) {
                    return false;
                } else {
                    return false;
                }
            } else {
                return true;
            }
        }
    </script>
<?php } ?>
<script>
<?php if (!empty($amount_history_success)) { ?>
        setTimeout(function () {
            $('#payment_model').modal('show');
        }, 1000);
<?php } ?>
</script>
<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="http://localhost/patholab/user_assets/bootstrap-datepicker3.css"/>

<section class="content-header">
    <h1>
        Branch Package Discount
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>Branch_Package_Discount/index"><i class="fa fa-users"></i>Branch Package Discount List</a></li>
        <li class="active">Add Branch Package Discount</li>
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
                <form role="form" action="<?php echo base_url(); ?>Branch_Package_Discount/add" method="post" enctype="multipart/form-data" id="test_submit">

                    <div class="box-body">
                        <div class="col-md-6">
                            <?= validation_errors('<div class="alert alert-danger alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>', '</div>'); ?>

                            <div class="form-group">
                                <label for="exampleInputFile">City</label><span style="color:red">*</span>
                                <select name="city_data" id="city_data" class="form-control">
                                    <option value="">--Select--</option>
                                    <?php foreach ($citys as $key) {
                                        ?>
                                        <option value="<?php echo $key['id'] ?>"> <?php echo $key['name'] ?> </option>
                                    <?php } ?>
                                </select>
                                <span id="city_dataerror" class="errorall" style="color:red;"><?php echo form_error('city'); ?></span>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputFile">Branch</label><span style="color:red">*</span>
                                <select name="branch_data" id="branch_data" class="form-control">
                                    <option value="">--Select--</option>
                                </select>
                                <span id="branch_dataerror" class="errorall" style="color:red;"><?php echo form_error('branch_data'); ?></span>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputFile">Package</label><span style="color:red">*</span>
                                <select name="package_data" id="package_data" class="form-control">
                                    <option value="">--Select--</option>
                                </select>
                                <span id="package_dataerror" class="errorall" style="color:red;"><?php echo form_error('package_data'); ?></span>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputFile">How many days active package available from user booking?</label><span style="color:red">*</span>
                                <input type="text" name="package_active_user_book" class="form-control"  value="<?php echo set_value('package_active_user_book'); ?>" id="package_active_user_book" onkeyup="calculate_age1(this.value)">
                                <span id="package_active_user_bookerror" class="errorall" style="color:red;"><?php echo form_error('package_active_user_book'); ?></span>
                            </div>
                            OR 
                            <div class="form-group">
                                <br/>
                                <label for="exampleInputFile">Active till date</label>
<!--                                <input type="text" name="active_till_date" class="form-control datepicker-input"  value="<?php echo set_value('active_till_date'); ?>" id="active_till_date">-->
                                <input type="text" name="active_till_date" class="form-control" value="<?php echo set_value('active_till_date'); ?>" id="active_till_date" onchange="changeDate(this.value)">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputFile">Other Test Discount For Self (%)</label><span style="color:red">*</span>
                                <input type="text" name="other_test_discount_self" id="other_test_discount_self" class="form-control"  value="<?php echo set_value('other_test_discount_self'); ?>" id="other_test_discount_self">
                                <span id="other_test_discount_selferror" class="errorall" style="color:red;"><?php echo form_error('other_test_discount_self'); ?></span>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputFile">Other Test Discount For Family (%)</label><span style="color:red">*</span>
                                <input type="text" name="other_test_discount_family" id="other_test_discount_family" class="form-control"  value="<?php echo set_value('other_test_discount_family'); ?>" id="other_test_discount_family">
                                <span id="other_test_discount_familyerror" class="errorall" style="color:red;"><?php echo form_error('other_test_discount_family'); ?></span>
                            </div>

                           <?php /* <div class="form-group">
                                <label for="exampleInputFile">How many times user booked this package?</label>
                                <input type="text" name="time_booked_package" id="time_booked_package" class="form-control"  value="<?php echo $query[0]['time_booked_package']; ?>" id="time_booked_package">
                                <span id="time_booked_packageerror" class="errorall" style="color:red;"><?php echo form_error('time_booked_package'); ?></span>
                            </div> */ ?>


                        </div>
                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="exampleInputFile">Select Test</label>
                                <select name="test_data" id="test_data" class="form-control" onchange="get_test_price()">
                                    <option value="">--Select--</option>
                                </select>
                            </div>

                            <table class="table table-striped" id="city_wiae_price">
                                <thead>
                                    <tr>
                                        <th>Test Name</th>
                                        <th>Discount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="t_body">
                                    <?php
                                    $cnt = 0;
                                    ?>
                                </tbody>
                            </table>

                        </div>
                    </div><!-- /.box-body -->

                    <div class="box-footer">
                        <div class="col-md-6">
                            <button class="btn btn-primary" type="submit">Add</button>
                        </div>
                    </div>
                </form>
            </div><!-- /.box -->
        </div>
    </div>
</section>
<script>
    $('#city_data').change(function () {
        var url = "<?php echo base_url(); ?>Branch_Package_Discount/get_branch";
        var value = $("#city_data").val();
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {"id": value},
            success: function (response)
            {
                var $el = $("#branch_data");
                $el.empty();
                $el.append($("<option></option>")
                        .attr("value", '').text('Select Branch'));

                $.each(response, function (index, data) {
                    $('#branch_data').append('<option value="' + data['id'] + '">' + data['branch_name'] + '</option>');
                });
            }
        });
    });

    $('#branch_data').change(function () {
        var url = "<?php echo base_url(); ?>Branch_Package_Discount/get_package";
        var value = $("#branch_data").val();
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {"id": value},
            success: function (response)
            {
                var $el = $("#package_data");
                $el.empty();
                $el.append($("<option></option>")
                        .attr("value", '').text('Select Package'));

                $.each(response, function (index, data) {
                    $('#package_data').append('<option value="' + data['id'] + '">' + data['title'] + '</option>');
                });
            }
        });
    });


    $('#branch_data').change(function () {
        var url = "<?php echo base_url(); ?>Branch_Package_Discount/get_test";
        var value = $("#branch_data").val();
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {"id": value},
            success: function (response)
            {
                var $el = $("#test_data");
                $el.empty();
                $el.append($("<option></option>")
                        .attr("value", '').text('Select Test'));

                $.each(response, function (index, data) {
                    $('#test_data').append('<option value="' + data['id'] + '">' + data['test_name'] + '</option>');
                });
            }
        });
    });

    function get_test_price() {
        var test_val = $("#test_data").val();
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
        var skillsSelect = document.getElementById("test_data");
        var selectedText = skillsSelect.options[skillsSelect.selectedIndex].text;


        $("#city_wiae_price").append('<tr id="' + test_val + '"><td>' + selectedText + '</td><td><input type="text" name="price[]" class="price" id="price_' + test_val + '"> %<br/><span id="price_error_' + test_val + '" class="errorall" style="color:red;"></span><input type="hidden" name="branch_fk[]" value="' + test_val + '"><span id="' + test_val + '" class="errorall" style="color:red;"></span></td><td><a href="javascript:void(0);" onclick="delete_city_price(\'' + test_val + '\',\'' + selectedText + '\')"><span class="glyphicon glyphicon-trash"></span></a></td></tr>');
//        $("#test option[value='1']").remove();
        $("#test_data option[value='" + test_val + "']").remove();
    }
    function delete_city_price(id, selectedText) {
        $("#" + id).remove();
        $("#test_data").append('<option value="' + id + '">' + selectedText + '</option>');
    }

    $("#test_submit").submit(function (e) {
        var error = 1;
        var city_data = $("#city_data").val().trim();
        var branch_data = $("#branch_data").val().trim();
        var package_data = $("#package_data").val().trim();
        var other_test_discount_self = $("#other_test_discount_self").val().trim();
        var other_test_discount_family = $("#other_test_discount_family").val().trim();
        var package_active_user_book = $("#package_active_user_book").val().trim();
        var active_till_date = $("#active_till_date").val();

        $(".errorall").html("");

        if (city_data == "") {
            error = 0;
            $("#city_dataerror").html("The City field is required.");
        }
        if (branch_data == "") {
            error = 0;
            $("#branch_dataerror").html("The Branch field is required.");
        }
        if (package_data == "") {
            error = 0;
            $("#package_dataerror").html("The Package field is required.");
        }
        if (other_test_discount_self == "") {
            error = 0;
            $("#other_test_discount_selferror").html("The Other Test Discount for Self field is required.");
        } else {
            if (isNaN(other_test_discount_self) || other_test_discount_self >= 100 || other_test_discount_self <= 0) {
                error = 0;
                $("#other_test_discount_selferror").html("Please enter valid Other Test Discount for Self.");
            }
        }

        if (other_test_discount_family == "") {
            error = 0;
            $("#other_test_discount_familyerror").html("The Other Test Discount for Family field is required.");
        } else {
            if (isNaN(other_test_discount_family) || other_test_discount_family >= 100 || other_test_discount_family <= 0) {
                error = 0;
                $("#other_test_discount_familyerror").html("Please enter valid Other Test Discount for Family.");
            }
        }


        if (package_active_user_book != "") {
            if (isNaN(package_active_user_book) || package_active_user_book.length > 3) {
                error = 0;
                $("#package_active_user_bookerror").html("Please enter valid active package available info.");
            }
        }

        if (active_till_date == "" && package_active_user_book == "") {
            error = 0;
            $("#package_active_user_bookerror").html("Please enter number of days active package OR Active till date.");
        }


        $(".price").each(function (index, element) {
            if ($(this).is(':visible')) {

                var id = this.id;
                var price_value = $("#" + id).val();
                var idsplit = id.split("_");
                var branch_id = idsplit[1];

                if (price_value == "") {
                    error = 0;
                    $("#price_error_" + branch_id).html("Please Enter Discount.");
                }
                if (isNaN(price_value) || price_value < 0 || price_value > 100) {
                    error = 0;
                    $("#price_error_" + branch_id).html("Please Enter Valid Discount.");
                }
            }
        });

        if (error == 1) {
            return true;
        } else {
            return false;
        }
    });

    var date = new Date();
    date.setDate(date.getDate());
    $('#active_till_date').datepicker({
//        format: 'dd-mm-yyyy',
        format: 'yyyy-mm-dd',
        startDate: date,
        autoclose: true
    });

</script>
<script>
    function calculate_age1(val)
    {
        var day = parseInt(val);

        var d = new Date();
        //alert(d.getDate() + day);
        var days = d.setDate(d.getDate() + day);
        var news = d.toLocaleString(days);

        var today = news.split(",");

        var new_date = today[0];
        var nw_date = new_date.split("/");
        //alert(nw_date);
        nw_date = nw_date[2] + "-" + nw_date[0] + "-" + nw_date[1];

        nw_date = d.toISOString().split('T')[0];
        $("#active_till_date").val(nw_date);
    }

    function changeDate(val) {

        var date = val;
        var nw_date = date.split("-");
        var day = parseInt(nw_date[2]);

        var d = new Date();
        var newday = day - (d.getDate());
        $("#package_active_user_book").val(newday);
    }
</script>
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<style>
    /* pending_job_detail responsive table */
    .pending_job_list_tbl {width: 100%; float: left;}
    .pending_job_list_tbl table {width: 100%; border-collapse: collapse; float: left;}
    .pending_job_list_tbl th {background-color: #e5e5e5; color: #3e3e3e; font-size: 16px; font-weight: 600; text-align: center; vertical-align: middle; border: 1px solid #b1b1b1;}
    .pending_job_list_tbl td, th {padding:2px 6px; border: 1px solid #ccc; text-align: left;}
    .pending_job_list_tbl td {padding: 4px 4px; font-size: 13px; color: #505050;} 
    @media (max-width: 980px) {
        .pending_job_list_tbl table, .pending_job_list_tbl thead, .pending_job_list_tbl tbody, .pending_job_list_tbl th, .pending_job_list_tbl td, .pending_job_list_tbl tr {display: block;}
        .pending_job_list_tbl thead tr {position: absolute; top: -9999px; left: -9999px;}
        .pending_job_list_tbl tr {border: 1px solid #ccc !important;}
        .pending_job_list_tbl td {border: none; border-bottom: 1px solid #eee; position: relative; padding-left: 60%; text-align: left;}
        .pending_job_list_tbl td:before {position: absolute; top: 6px; left: 6px; width: 45%; padding-right: 10px; white-space: nowrap;}
        .pending_job_list_tbl tr{margin-bottom:15px;}
        .table-responsive.pending_job_list_tbl{border:none !important;}

        .pending_job_list_tbl td:nth-of-type(1):before {content: "No";}
        .pending_job_list_tbl td:nth-of-type(2):before {content: "Reg No";}
        .pending_job_list_tbl td:nth-of-type(3):before {content: "Order Id";}
        .pending_job_list_tbl td:nth-of-type(4):before {content: "Customer Name";}
        .pending_job_list_tbl td:nth-of-type(5):before {content: "Doctor";}
        .pending_job_list_tbl td:nth-of-type(6):before {content: "Test/Package Name";}
        .pending_job_list_tbl td:nth-of-type(7):before {content: "Date";}
        .pending_job_list_tbl td:nth-of-type(8):before {content: "Payable Amount / Price";}
        .pending_job_list_tbl td:nth-of-type(9):before {content: "Job Status";}
        .pending_job_list_tbl td:nth-of-type(10):before {content: "Action";}
    }
    /* End pending_job_detail responsive table */

    .full_bg{background: rgba(0,0,0,0.3); width:100%; height:100%; float:left; padding:250px; position:fixed; z-index:9; top:0; bottom:0;}
    .full_bg .loader img{width:70px; height:70px;}
    .text_highlight:hover{
        text-decoration: underline;
        /*font-weight: bold;
        font-size: 12px;*/
    }	
</style>
<div class="full_bg" style="display:none;" id="loader_div">
    <div class="loader">
        <center><img src="http://static.heart.org/riskcalc/app/assets/img/loading.gif"></center>
    </div>
</div>
<section class="content-header">
    <h1>
        Po Generate
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>inventory/intent_genrate/index"><i class="fa fa-users"></i>List</a></li>
        <li class="active">Po Generate</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">

            <div class="box box-primary">

                <div class="box-body">

                    <div class="col-md-6">

                        <?php echo form_open("inventory/intent_genrate/poigenerateedit", array("method" => "POST", "role" => "form", "id" => 'poform')); ?>

                        <div class="form-group">
                            <label for="message-text" class="form-control-label">Branch: <span style="color:red">*</span></label>
                            <select class="chosen chosen-select" id ="branch_id" disabled="" name="branch_fk" onchange="getReagent();">
                                <option value="">--Select Branch---</option>
                                <?php
                                foreach ($branch as $val) {
                                    ?>
                                    <option value="<?= $val["BranchId"]; ?>" <?php
                                    if ($query[0]["branchfk"] == $val["BranchId"]) {
                                        echo "selected";
                                    }
                                    ?> ><?= $val["BranchName"] ?></option>
                                        <?php }
                                        ?>
                            </select>
                            <span id="errorbranch" style="color: red;"><?= form_error('branch'); ?></span>
                        </div>

                        <div class="form-group">
                            <label for="message-text" class="form-control-label">Vendor: <span style="color:red">*</span></label>
                            <select class="chosen chosen-select"  id ="vendor" name="vendorname"  >
                                <option value="">--Select Vendor---</option>
                                <?php
                                foreach ($vendor_list as $venal) {
                                    ?>
                                    <option value="<?= $venal["id"]; ?>" <?php
                                    if ($query[0]["vendorid"] == $venal["id"]) {
                                        echo "selected";
                                    }
                                    ?> ><?= ucwords($venal["vendor_name"]); ?></option>
                                        <?php }
                                        ?>
                            </select>
                            <span id="errorvendor" style="color: red;"></span>
                        </div>

                        <div class="form-group">
                            <label for="message-text" class="form-control-label">Indent Code: <span style="color:red">*</span></label>
                            <input type="text" name="indentcode" id="indentcode" disabled="" value="<?= $query[0]["indentcode"]; ?>" class="form-control" />
                            <span id="errorindedcode" style="color: red;"></span>
                        </div>

                        <div class="form-group">
                            <label for="message-text" class="form-control-label">Add Reagent:</label>
                            <select class="chosen chosen-select new_update" name="category_fk" id="selected_item" onchange="select_item('Reagent', this)">
                                <option value="">--Select--</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="form-control-label">Add Lab Consumables:</label>
                            <select class="chosen chosen-select" name="category_fk" id="consumer_id" onchange="select_item('Consumables', this);">
                                <option value="">--Select--</option>
                                <?php
                                foreach ($lab_consum as $mkey) {
                                    $lab_consum = $lab_consum . '<option value="' . $mkey["id"] . '">' . $mkey["reagent_name"] . '</option>';
                                    ?>
                                    <option value="<?= $mkey["id"] ?>"><?= $mkey["reagent_name"] ?></option>
                                <?php } ?>
                            </select>
                            <input type="hidden" id="item_list" value="<?= htmlspecialchars($lab_consum, ENT_QUOTES); ?>"/>
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="form-control-label">Add Stationary:</label>
                            <select class="chosen chosen-select" name="category_fk" id="yahoo_id" onchange="select_item('Stationary', this);">
                                <option value="">--Select--</option>
                                <?php
                                foreach ($stationary_list as $mkey) {
                                    $stationary_list = $stationary_list . '<option value="' . $mkey["id"] . '">' . $mkey["reagent_name"] . '</option>';
                                    ?>
                                    <option value="<?= $mkey["id"] ?>"><?= $mkey["reagent_name"] ?></option>
                                <?php } ?>
                            </select>
                            <span id="erroriteam" style="color: red;"></span>

                        </div>

                        <div class="form-group">
                            <label for="message-text" class="form-control-label">Remark:</label>
                            <textarea name="remark" id="remark" class="form-control"><?= $query[0]["remark"]; ?></textarea>

                        </div>






                        <div id="itemtext" style="display:none">
                            <select class="form-control calution1" name="itemtext[]" >
                                <option value="0">--Select Tax--</option>
                                <?php
                                foreach ($itemtext as $txt) {
                                    ?>
                                    <option rel="<?= $txt["tax"]; ?>" value="<?= $txt["id"] ?>"><?= $txt["title"] . "(" . $txt["tax"] . "%)"; ?></option>
                                <?php } ?>
                            </select>

                        </div>

                    </div><!-- /.box -->

                    <div class="col-md-6">

                    </div>

                    <div class="table-responsive pending_job_list_tbl">
                        <table class="table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Category</th> 
                                    <th>Item</th>
                                    <th>NOS</th>
                                    <th>Qty.</th>
                                    <th>Rate per Test</th>
                                    <th>Amount Rs.</th>
                                    <th>Discount(%)</th>
                                    <th>TAX</th>
                                    <th>TOTAL PAYABLE</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="selected_items">

                                <?php
                                $cnt = 0;
                                $totalprice = 0;
                                $selected_item=array();
                                foreach ($poitenm as $row) {
                                    $cnt++;
                                    $itetype = $row["category_fk"];
                                    $whotype = "";
                                    if ($itetype == '1') {
                                        $whotype = "Stationary";
                                        $cat = "Stationary";
                                    }
                                    if ($itetype == '2') {
                                        $whotype = "Consumables";
                                        $cat = "Lab Consumables";
                                    }
                                    if ($itetype == '3') {
                                        $whotype = "Reagent";
                                        $cat = "Reagent";
                                    }
                                    ?>

                                    <tr id="tr_<?= $cnt; ?>">
                                        <td><?= $cnt; ?></td>
                                        <td><?= $cat; ?></td>
                                        <td><?= $row["reagent_name"]; ?><input type="hidden" name="item[]" value="<?= $row["itemid"]; ?>"><?php $selected_item[]=$row["itemid"]; ?></td>
                                        <td><input type="text" name="nos[]" id="nos_<?= $cnt; ?>" value="<?= $row["itemnos"]; ?>" class="form-control calution"/></td>
                                        <td><p id="totalqty_<?= $cnt; ?>"><?= $row["itenqty"]; ?></p></td>
                                        <td><input type="text" id="rateqty_<?= $cnt; ?>" value="<?= $row["peritemprice"]; ?>" name="rateqty[]" class="form-control calution" /></td>
                                        <td><p id="testamount_<?= $cnt; ?>"><?= $row["peritemprice"] * $row["itemnos"] * $row["itenqty"]; ?></p></td>
                                        <td><input type="text" id="itemdis_<?= $cnt; ?>" name="itemdis[]" value="<?= $row["itemdis"]; ?>" class="form-control calution"/><span id="errorperdis_<?= $cnt; ?>" style="color: red;"></span></td>
                                        <td><p id="txtid_<?= $cnt; ?>"><select class="form-control calution1" name="itemtext[]" >
                                                    <option value="0">--Select Tax--</option>
                                                    <?php
                                                    foreach ($itemtext as $txt) {
                                                        ?>
                                                        <option <?php
                                                        if ($txt["id"] == $row["txtid"]) {
                                                            echo "selected";
                                                        }
                                                        ?> rel="<?php
                                                            if ($txt["id"] == $row["txtid"]) {
                                                                echo $row["itemtxt"];
                                                            } else {
                                                                echo $txt["tax"];
                                                            }
                                                            ?>" value="<?= $txt["id"] ?>"><?= $txt["title"] . "(" . $txt["tax"] . "%)"; ?></option>
                                                        <?php } ?>
                                                </select></p></td>
                                        <td><input type="text" id="totalamount_<?= $cnt; ?>" disabled name="totalamount[]" value="<?= $row["itemprice"]; ?>" class="form-control"/></td>
                                        <td><a href="javascript:void(0);" onclick="delete_city_price('<?= $cnt; ?>', '<?= $row["reagent_name"]; ?>', '<?= $row["itemid"]; ?>', '<?= $whotype; ?>')"><i class="fa fa-trash"></i></a></td>
                                    </tr>
                                    <?php
                                    $totalprice += $row["itemprice"];
                                }
                                ?>
                            </tbody>

                            <tr>
                                <td colspan='8'></td>
                                <td>Discount(%)</td>
                                <td colspan='2'><input id="pricediscount" type="text"  name="pricediscount" value="<?= $query[0]["discount"]; ?>" /><br><span id="errordisccount" style="color: red;"></span></td>
                            </tr>

                            <tr>
                                <td colspan='8'></td>
                                <td>Total Amount Rs.</td>
                                <td colspan='2'><input id="maintotalprice" type="text" readonly name="maintotal" value="<?= round($totalprice - ($totalprice * $query[0]["discount"] / 100)); ?>" /></td>
                            </tr>
                        </table>


                    </div> 


                </div>	
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Update</button>

            </div>

<?php echo form_close(); ?>
        </div>
    </div>
</section>
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
<script  type="text/javascript">
                                        $(function () {

                                            $('.chosen-select').chosen();



                                        });
                                        var pogid = parseInt("<?= $query[0]["id"]; ?>");
                                        $(document).on('keydown', '.calution', function (e) {
                                            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                                                    (e.keyCode == 65 && e.ctrlKey === true) ||
                                                    // Allow: Ctrl+C
                                                            (e.keyCode == 67 && e.ctrlKey === true) ||
                                                            // Allow: Ctrl+X
                                                                    (e.keyCode == 88 && e.ctrlKey === true) ||
                                                                    // Allow: home, end, left, right
                                                                            (e.keyCode >= 35 && e.keyCode <= 39)) {
                                                                // let it happen, don't do anything
                                                                return;
                                                            }
                                                            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                                                                e.preventDefault();
                                                            }
                                                        });

                                                function getReagent() {

                                                    var id = $('#branch_id').val();
                                                    $.ajax({
                                                        url: "<?php echo base_url(); ?>inventory/Intent_Request/getsub",
                                                        type: "POST",
                                                        data: {branch_fk: id, selected: '<?php echo implode(",",$selected_item); ?>'},
                                                        success: function (data) {
                                                            console.log(data);
                                                            $('.new_update').html(data);
                                                            $('.chosen').trigger("chosen:updated");

                                                        }
                                                    });
                                                }
                                                setTimeout(function () {
                                                    getReagent();
                                                },
                                                        2000);

                                                function select_item(val, id) {

                                                    var skillsSelect = id;
                                                    var selectedText = skillsSelect.options[skillsSelect.selectedIndex].text;
                                                    var prc = selectedText;
                                                    var pm = skillsSelect.value;
                                                    var explode = pm.split('-');

                                                    $("#loader_div").show();

                                                    var $city_cnt = $('#selected_items').children('tr').length;

                                                    var old_dv_txt = $("#hidden_test").html();
                                                    $city_cnt = $city_cnt + 1;
                                                    if (val == "Reagent") {
                                                        $("#selected_item option[value='" + skillsSelect.value + "']").remove();
                                                        var catgory = "Reagent";
                                                    }
                                                    if (val == "Consumables") {
                                                        $("#consumer_id option[value='" + skillsSelect.value + "']").remove();
                                                        var catgory = "Lab Consumables";
                                                    }

                                                    if (val == "Stationary") {
                                                        $("#yahoo_id option[value='" + skillsSelect.value + "']").remove();
                                                        var catgory = "Stationary";
                                                    }
                                                    $('.chosen').trigger("chosen:updated");

                                                    $.ajax({
                                                        url: "<?php echo base_url(); ?>inventory/intent_genrate/getitenqty",
                                                        type: "GET",
                                                        dataType: "json",
                                                        data: {itenid: pm},
                                                        success: function (data) {

                                                            var itemtext = $('#itemtext').html();
                                                            if (data.price != null && data.price != "") {
                                                                var iprice = data.price;
                                                            } else {
                                                                var iprice = "0"
                                                            }

                                                            $("#selected_items").append('<tr id="tr_' + $city_cnt + '"><td>' + $city_cnt + '</td><td>' + catgory + '</td><td>' + prc + '<input type="hidden" name="item[]" value="' + pm + '"></td><td><input type="text" name="nos[]" id="nos_' + $city_cnt + '" value="1" class="form-control calution"/></td><td><p id="totalqty_' + $city_cnt + '">' + data.qty + '</p></td><td><input type="text" id="rateqty_' + $city_cnt + '" value="' + iprice + '" name="rateqty[]" class="form-control calution"/></td><td><p id="testamount_' + $city_cnt + '">0</p></td><td><input type="text" id="itemdis_' + $city_cnt + '" name="itemdis[]" value="0" class="form-control calution"/><span id="errorperdis_' + $city_cnt + '" style="color: red;"></span></td><td><p id="txtid_' + $city_cnt + '">' + itemtext + '</p></td><td><input type="text" id="totalamount_' + $city_cnt + '" disabled name="totalamount[]" class="form-control"/></td><td><a href="javascript:void(0);" onclick="delete_city_price(\'' + $city_cnt + '\',\'' + prc + '\',\'' + pm + '\',\'' + val + '\')"><i class="fa fa-trash"></i></a></td></tr>');

                                                            tablecal("rateqty_" + $city_cnt);

                                                            $("#loader_div").hide();

                                                        }
                                                    });




                                                }
                                                function delete_city_price(id, name, value, selec) {
                                                    var tst = confirm('Are you sure?');
                                                    if (tst == true) {

                                                        if (selec == "Reagent") {
                                                            $('#selected_item').append('<option value="' + value + '">' + name + '</option>').trigger("chosen:updated");
                                                        }
                                                        if (selec == "Consumables") {
                                                            $('#consumer_id').append('<option value="' + value + '">' + name + '</option>').trigger("chosen:updated");
                                                        }
                                                        if (selec == "Stationary") {
                                                            $('#yahoo_id').append('<option value="' + value + '">' + name + '</option>').trigger("chosen:updated");
                                                        }
                                                        $("#tr_" + id).remove();


                                                    }

                                                    maintotalcchange();

                                                }

                                                $(document).on('keyup', '.calution', function () {

                                                    tablecal(this.id);
                                                });
                                                function tablecal(tid) {

                                                    var curow = tid;
                                                    var explode = curow.split('_');
                                                    var textid = explode[0];
                                                    var trid = explode[1];
                                                    var nos = parseFloat($("#nos_" + trid).val());
                                                    if (nos > 0) {
                                                        var nos = nos;
                                                    } else {
                                                        var nos = 1;
                                                    }
                                                    var qty = parseFloat($("#totalqty_" + trid).html());
                                                    var ratepertest = parseFloat($("#rateqty_" + trid).val());
                                                    var itemdis = parseFloat($("#itemdis_" + trid).val());
                                                    $("#errorperdis_" + trid).html("");
                                                    if (itemdis >= 0 && itemdis <= 100) {
                                                        var perdiscount = parseFloat(itemdis);
                                                    } else {
                                                        $("#errorperdis_" + trid).html("Please enter valid discount");
                                                        var perdiscount = 0;
                                                    }

                                                    if (ratepertest != "" && ratepertest > 0) {
                                                        var disamount = (qty * ratepertest) * nos;

                                                        var multiqty = (disamount - disamount * perdiscount / 100);

                                                        var disamount1 = disamount.toFixed(2);

                                                        $("#testamount_" + trid).html(disamount1);
                                                        var itemtxt = parseFloat($("#txtid_" + trid + " select option:selected").attr('rel'));
                                                        if (itemtxt > 0) {

                                                            var totalamount = (multiqty + (multiqty * itemtxt / 100));
                                                            var totalamount1 = totalamount.toFixed(2);
                                                            $("#totalamount_" + trid).val(totalamount1);
                                                        } else {
                                                            var multiqty1 = multiqty.toFixed(2);
                                                            $("#totalamount_" + trid).val(multiqty1);
                                                        }

                                                    } else {
                                                        $("#testamount_" + trid).html('0');
                                                        $("#totalamount_" + trid).val('0');
                                                    }

                                                    maintotalcchange();
                                                }
                                                $(document).on('change', '.calution1', function () {
                                                    var itemtxt = parseFloat($('option:selected', this).attr('rel'));
                                                    var curow = $(this).parent().attr("id");
                                                    var explode = curow.split('_');
                                                    var textid = explode[0];
                                                    var trid = explode[1];
                                                    var tamount1 = parseFloat($("#testamount_" + trid).html());
                                                    var itemdis = parseFloat($("#itemdis_" + trid).val());

                                                    if (itemdis >= 0 && itemdis <= 100) {
                                                        var perdiscount = parseFloat(itemdis);
                                                    } else {
                                                        var perdiscount = 0;
                                                    }

                                                    var tamount = (tamount1 - tamount1 * perdiscount / 100);

                                                    if (tamount > 0 && itemtxt > 0) {

                                                        var totalamount = (tamount + (tamount * itemtxt / 100));
                                                        $("#totalamount_" + trid).val(totalamount.toFixed(2));

                                                    } else {
                                                        $("#totalamount_" + trid).val(tamount.toFixed(2));
                                                    }

                                                    maintotalcchange();

                                                });

                                                function maintotalcchange() {
                                                    var dis1 = parseFloat($("#pricediscount").val());
                                                    if (dis1 > 0) {
                                                        if (dis1 <= 100) {
                                                            var dis = dis1;
                                                        } else {
                                                            $("#errordisccount").html("Please enter valid discount");
                                                            dis = 0;
                                                        }
                                                    } else {
                                                        var dis = 0;
                                                    }

                                                    var total = 0;

                                                    $("input[name='totalamount[]']").each(function (index, element) {

                                                        if (parseFloat($(element).val()) > 0) {
                                                            total = total + parseFloat($(element).val());
                                                        }

                                                    });


                                                    var discountamount = (total - (total * dis / 100));

                                                    $("#maintotalprice").val(discountamount);

                                                }
                                                $(document).on('keyup', '#pricediscount', function () {
                                                    var dis1 = this.value;
                                                    $("#errordisccount").html("");
                                                    if (dis1 > 0) {
                                                        if (dis1 <= 100) {
                                                            var dis = dis1;
                                                        } else {
                                                            $("#errordisccount").html("Please enter valid discount");
                                                            dis = 0;
                                                        }
                                                    } else {
                                                        var dis = 0;
                                                    }
                                                    var total = 0;
                                                    $("input[name='totalamount[]']").each(function (index, element) {
                                                        if (parseFloat($(element).val()) > 0) {
                                                            total = total + parseFloat($(element).val());
                                                        }
                                                    });
                                                    var discountamount = (total - (total * dis / 100));

                                                    $("#maintotalprice").val(discountamount);




                                                });

                                                $("#poform").submit(function () {

                                                    var baranch = $("#branch_id").val();
                                                    var vendor = $("#vendor").val();
                                                    var indentcode = $("#indentcode").val();
                                                    var pricetotal = $("#maintotalprice").val();
                                                    var pricediscount = $("#pricediscount").val();
                                                    var remark = $("#remark").val();


                                                    var check = 0;
                                                    if (baranch == "") {
                                                        $("#errorbranch").html("Branch field is required");
                                                        var check = 1;
                                                    } else {
                                                        $("#errorbranch").html("");
                                                    }
                                                    if (vendor == "") {
                                                        $("#errorvendor").html("Vendor field is required");
                                                        var check = 1;
                                                    } else {
                                                        $("#errorvendor").html("");
                                                    }
                                                    if (indentcode == "") {
                                                        $("#errorindedcode").html("Vendor field is required");
                                                        var check = 1;
                                                    } else {
                                                        $("#errorindedcode").html("");
                                                    }
//                                                    if (pricetotal >= 0) {
//                                                        $("#erroriteam").html("");
//                                                    } else {
//                                                        $("#erroriteam").html("Please add items");
//                                                        var check = 1;
//                                                    }
                                                    if (check == 0) {

                                                        $("#loader_div").show();
                                                        var nosval = new Array();
                                                        $("input[name='nos[]']").each(function () {
                                                            nosval.push($(this).val());
                                                        });

                                                        var item = new Array();
                                                        $("input[name='item[]']").each(function () {
                                                            item.push($(this).val());
                                                        });

                                                        var rateqty = new Array();
                                                        $("input[name='rateqty[]']").each(function () {
                                                            rateqty.push($(this).val());
                                                        });

                                                        var itemdis = new Array();
                                                        $("input[name='itemdis[]']").each(function () {
                                                            itemdis.push($(this).val());
                                                        });


                                                        var itemtext = new Array();
                                                        var it = 0;
                                                        $("select[name='itemtext[]']").each(function () {
                                                            if (it != 0) {
                                                                itemtext.push($(this).val());
                                                            }
                                                            it++;
                                                        });

                                                        $.ajax({
                                                            url: "<?php echo base_url(); ?>inventory/intent_genrate/poigenerateedit/" + pogid,
                                                            type: "POST",
                                                            data: {pogid: pogid, branch_fk: baranch, vendorname: vendor, indentcode: indentcode, maintotal: pricetotal, pricediscount: pricediscount, item: item, itemtext: itemtext, nosval: nosval, rateqty: rateqty, remark: remark, itemdis: itemdis},
                                                            success: function (data) {

                                                                $("#loader_div").hide();

                                                                if (data == 1) {
                                                                    window.location.href = "<?= base_url() . "inventory/intent_genrate/index" ?>";
                                                                }

                                                            }
                                                        });

                                                        return false;

                                                    } else {

                                                        return false;
                                                    }

                                                });

</script> 
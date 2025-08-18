<style>
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


</style>
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />


<style>
    .box.box-solid{border: 1px solid #ccc;}
    .box-header.with-border{border-bottom: 1px solid #ccc;}
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Manage Indent Request
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Indent Request</a></li>
            <li class="active">Indent Request List</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="invoice">
        <div class="widget">
            <?php if (isset($success) != NULL) { ?>
                <div class="alert alert-success alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    <?php echo $success['0']; ?>
                </div>
            <?php } ?>

        </div>
        <div class="row">
            <span style="float: right;"><a href="javascript:void(0);" onclick="$('#myModal').modal('show');" class="btn btn-primary btn-sm">Add</a></span>  
        </div>


        <div class="row">
            <div class="col-md-12">
                <form role="form" action="<?php echo base_url(); ?>inventory/Intent_Request/intent_list" method="get" enctype="multipart/form-data">
                    <table id="example4" class="table table-bordered table-striped">
                        <thead>
                        <th>No</th>
                        <th>Indent No</th>
                        <th>Branch Name</th>
                        <th>Item</th>
                        <th>Created Date</th>
                        <th>Status</th>
                        <th>Action</th>
                        </thead>
                        <tbody>
                            <tr>

                                <td><span style="color:red;">*</span></td>

                                <td><input type="text" placeholder="Indent No" class="form-control" name="intent_name" value="<?php
                                    if (isset($intenet_id)) {
                                        echo $intenet_id;
                                    }
                                    ?>"/></td>
                                <td>
                                    <select name="branch_fk" class="chosen chosen-select" data-placeholder="choose a language...">
                                        <option value="">--Select--</option>
                                        <?php foreach ($branch_list as $val) { ?>
                                            <option value="<?php echo $val['id']; ?>" <?php
                                            if ($branch_fk == $val['id']) {
                                                echo " selected='selected'";
                                            }
                                            ?> ><?php echo $val['branch_name']; ?></option>

                                        <?php } ?>
                                    </select>

                                </td>
                                <td></td>
                                <td><input type="text" name="start_date" class="form-control datepicker" placeholder="Enter Date" value="<?php echo $new_date; ?>"></td>
                                <td>
                                    <select name="status" class="chosen chosen-select" data-placeholder="choose a language...">
                                        <option value="">--Select Status--</option>
                                        <option value="1" <?php
                                        if ($status == '1') {
                                            echo "selected='selected'";
                                        }
                                        ?>>Approved</option>
                                        <option value="2" <?php
                                        if ($status == '2') {
                                            echo "selected='selected'";
                                        }
                                        ?>>Waiting For Approval</option>


                                    </select>
                                <td><input type="submit" name="search" class="btn btn-success" value="Search" /></td>
                            </tr>
                            <?php
                            $cnt = 1;
                            foreach ($final_array as $valTest) {
                                $date = explode(" ", $valTest['created_date']);
                                $explode_date = explode("-", $date[0]);
                                $ddate = $explode_date[2] . '-' . $explode_date[1] . '-' . $explode_date[0];
                                $new_date = $ddate . ' ' . $date[1];
                                ?>
                                <tr>
                                    <td>
                                        <?php echo $cnt; ?>
                                    </td>

                                    <td>
                                        <?php echo "<br><small><b>#-</b>" . $valTest['intent_id'] . '<br><small><b>Added By-</b>' . $valTest["admin_name"] . '</small>'; ?>
                                    </td>
                                    <td><?php
                                        if ($valTest['branch_name'] != '') {
                                            $branch_name = $valTest['branch_name'];
                                        } else {
                                            $branch_name = "N/A";
                                        }
                                        echo $branch_name;
                                        ?></td>
                                    <td>
                                        <?php foreach ($valTest['details'] as $key => $new_array) {
                                            ?>

                                            <?php foreach ($new_array['reagent'] as $key => $all_merge) { ?>
                                                <b><?= ucfirst($all_merge["Category_Name"] . '<br>') ?></b>
                                                <?php
                                                $old = explode(",", $all_merge['Reagent_name']);
                                                $old_new = explode(",", $all_merge["Quantity"]);
                                                $crq = 0;
                                                foreach ($old_new as $d1) {
                                                    ?>

                                                    <?php echo $old[$crq]; ?> &nbsp;: <?php echo $d1 . '<br>'; ?>

                                                    <?php
                                                    $crq++;
                                                }
                                                ?>

                                            <?php } ?>



                                        <?php } ?>
                                    </td>
                                    <td><?php echo $new_date; ?></td> 
                                    <td>
                                        <?php
                                        if ($valTest['iiSTATUS'] == 1) {
                                            echo "<span class='label label-warning '>Approved</span>";
                                        }
                                        ?>

                                        <?php
                                        if ($valTest['iiSTATUS'] == 2) {
                                            echo "<span class='label label-danger '>Waiting For Approval</span>";
                                        }
                                        ?></td>                
                                    <td>   
                                        <?php if ($valTest['iiSTATUS'] == 2) { ?>
                                            <a href="<?php echo base_url(); ?>inventory/Intent_Request/get_proved/<?php echo $valTest['intent_id']; ?>/<?php echo $login_data['type']; ?>" data-toggle="tooltip" data-original-title="Approved"  onclick="return confirm('Are you sure?');"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i></a>  

                                            <a href="javascript:void(0);" onclick="edit('<?php echo $valTest['intent_id']; ?>', '<?php echo $valTest['BranchId']; ?>'), getReagent_test('<?php echo $valTest['BranchId']; ?>')" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-edit"></i></a>

                                            <a href="<?php echo base_url(); ?>inventory/Intent_Request/delete/<?php echo $valTest['intent_id']; ?>/<?php echo $login_data['type']; ?>" onclick="return confirm('Are you sure?');" data-toggle="tooltip" data-original-title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></a>

                                        <?php } else { ?>
                                            <a href='<?php echo base_url(); ?>inventory/Intent_Request/get_print_form/<?php echo $valTest['intent_id']; ?>/<?php echo $valTest['iiSTATUS']; ?>/<?php echo $login_data['type']; ?>' data-toggle="tooltip" data-original-title="Print Report"><span class=""><i class="fa fa-print"></i></span></a> 
                                            <a href="<?php echo base_url(); ?>inventory/Intent_Request/delete/<?php echo $valTest['intent_id']; ?>/<?php echo $login_data['type']; ?>" onclick="return confirm('Are you sure?');" data-toggle="tooltip" data-original-title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                        <?php } ?>



                                    </td>
                                </tr>
                                <?php
                                $cnt++;
                            }
                            ?>
                        </tbody>
                </form>
                </table>
            </div>
        </div>
    </section><!-- /.content -->
    <div class="clearfix"></div>
</div><!-- /.content-wrapper -->
<!-- Modal -->
<?php ?>
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add</h4>
            </div>
            <?php echo form_open("inventory/Intent_Request/intent_add", array("method" => "POST", "role" => "form", "id" => 'submit_id')); ?>
            <div class="modal-body">

                <input type="hidden" name="intent_id" value="<?php echo $generate[0]['Intent']; ?>">
                <div class="form-group">
                    <select class="chosen chosen-select" id ="branch_id" name="branch_fk" onchange="getReagent();">
                        <option value="">--Select Branch---</option>
                        <?php
                        $item_list_array = array();
                        foreach ($branch_list as $val) {

                            $item_list_array = $item_list_array . '<option value="' . $val["id"] . '">' . $val["branch_name"] . '</option>';
                            ?>

                            <option value="<?= $val["id"] ?>"><?= $val["branch_name"] ?></option>
                        <?php }
                        ?>
                    </select>
                    <span style="color:red" id="branch_error_id"></span>
                </div>
                <div style="display: none;" id="show_hide">
                    <div id="items_list">
                        <div class="form-group">
                            <label for="message-text" class="form-control-label">Add Reagent:</label>
                            <select class="chosen chosen-select new_update" name="category_fk" id="selected_item" onchange="select_item('Reagent', this)">
                                <option value="">--Select--</option>

                            </select>
                            <input type="hidden" id="item_list" value="<?= htmlspecialchars($item_list_array, ENT_QUOTES); ?>"/>
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="form-control-label">Add Lab Consumables:</label>
                            <select class="chosen chosen-select" name="category_fk" id="consumer_id" onchange="select_item('Consumables', this);">
                                <option value="">--Select--</option>
                                <?php
                                foreach ($lab_consum as $mkey) {
                                    if ($mkey['unit_fk'] != '') {
                                        $unit = '(' . $mkey['uname'] . ')';
                                    }
                                    $lab_consum = $lab_consum . '<option value="' . $mkey["id"] . '">' . $mkey["reagent_name"] . $unit . '</option>';
                                    ?>
                                    <option value="<?= $mkey["id"] ?>"><?= $mkey["reagent_name"] . $unit ?></option>
                                <?php } ?>
                            </select>
                            <input type="hidden" id="item_list" value="<?= htmlspecialchars($lab_consum, ENT_QUOTES); ?>"/>
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="form-control-label">Add Stationary:</label>
                            <select class="chosen chosen-select" name="category_fk" id="yahoo_id" onchange="select_item('Stationary', this);">
                                <option value="">--Select--</option>
                                <?php
                                foreach ($stationary_list as $key => $val) {
                                    if ($val['ufk'] != '') {
                                        $new_unt_station = (!empty($val["test_new_val"])) ? "(" . $val["test_new_val"] . ")" : "";
                                        /* $unit_test = '('.$val["test_new_val"].')'; */
                                    }

                                    $stationary_list = $stationary_list . '<option value="' . $val["id"] . '">' . $val["reagent_name"] . $new_unt_station . '</option>';
                                    ?>
                                    <option value="<?= $val["id"] ?>"><?= $val["reagent_name"] . $new_unt_station ?></option>
                                <?php } ?>
                            </select>
                            <input type="hidden" id="item_list" value="<?= htmlspecialchars($stationary_list, ENT_QUOTES); ?>"/>
                        </div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Category</th>
                                    <th>Quantity</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="selected_items">
                            <span id="error_id" style="color:red;"></span>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="submitData();">Add</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            <?php echo form_close(); ?>
        </div>

    </div>
</div>
<div id="myModalEdit" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Update</h4>
            </div>
            <?php echo form_open("inventory/Intent_Request/intent_update/", array("method" => "POST", "role" => "form", "id" => 'edit_form')); ?>
            <div class="modal-body">
                <div class="form-group">
                    <select class="chosen chosen-select branch_select" id ="branch_id" name="branch_fk">
                        <option value="">--Select Branch---</option>
                        <?php
                        $item_list_array = array();
                        foreach ($branch_list as $val) {

                            $item_list_array = $item_list_array . '<option value="' . $val["id"] . '">' . $val["branch_name"] . '</option>';
                            ?>

                            <option value="<?= $val["id"] ?>"><?= $val["branch_name"] ?></option>
                        <?php }
                        ?>
                    </select>
                    <input type="hidden" name="branch_fk" class="branch_sub_val" value="">
                </div>

                <div id="items_list_edit">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="edit_data()">Update</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            <?php echo form_close(); ?>
        </div>

    </div>
</div>
<?php /* END */ ?>
<?php /* END */ ?>
<script src="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
<script>
                    $('.datepicker').datepicker({
                        format: 'dd/mm/yyyy'
                    });
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
<script  type="text/javascript">
                    $nc = $.noConflict();
                    $nc(function () {

                        $nc('.chosen-select').chosen();

                    });

</script> 
<script>
    jQuery(document).ready(function () {
        jQuery("#machine_id_edit").chosen();
    });
</script>
<script>
    $city_cnt = 0;
    function machine_select() {
        var selected_ids = $("#machine_id").val();
        if (selected_ids != '') {
            $("#items_list").attr("style", "");
        } else {
            $("#items_list").attr("style", "display:none;");
            //$(".chosen").val('').trigger("liszt:updated");
        }
        $("#selected_items").html("");
        $("#selected_item").html("");
        var option_data = $("#item_list").val();
        $("#selected_item").html(option_data);
        $nc('.chosen').trigger("chosen:updated");

    }
    function select_item(val, id) {

        var skillsSelect = id;
        var selectedText = skillsSelect.options[skillsSelect.selectedIndex].text;
        var prc = selectedText;
        var pm = skillsSelect.value;

        var explode = pm.split('-');
        $("#selected_items").append('<tr id="tr_' + $city_cnt + '"><td>' + prc + '<input type="hidden" name="item[]" value="' + pm + '"></td><td>' + val + '</td><td><input type="text" name="quantity[]" required="" class="form-control dummyTekst" value=""/><span class="quantity_error_id" style="color:red;"></span></td><td><a href="javascript:void(0);" onclick="delete_city_price(\'' + $city_cnt + '\',\'' + prc + '\',\'' + pm + '\',\'' + val + '\')"><i class="fa fa-trash"></i></a></td></tr>');
        //$("#test option[value='1']").remove();
        var old_dv_txt = $("#hidden_test").html();
        $city_cnt = $city_cnt + 1;
        // $("#selected_item option[value='" + skillsSelect.value + "']").remove();
        //  $("#consumer_id option[value='" + skillsSelect.value + "']").remove();

        // $("#statinary_id option[value='" + skillsSelect.value + "']").remove();
        if (val == "Reagent") {
            $("#selected_item option[value='" + skillsSelect.value + "']").remove();
        }
        if (val == "Consumables") {
            $("#consumer_id option[value='" + skillsSelect.value + "']").remove();
        }

        if (val == "Stationary") {
            $("#yahoo_id option[value='" + skillsSelect.value + "']").remove();
        }
        $nc('.chosen').trigger("chosen:updated");
        //$nc('.chosen').trigger("chosen:updated");
    }

    function delete_city_price(id, name, value, val) {
        var tst = confirm('Are you sure?');

        if (tst == true) {
            /*Total price calculate start*/
            if (val == "Reagent") {
                $('#selected_item').append('<option value="' + value + '">' + name + '</option>');

            }
            if (val == "Consumables") {
                $('#consumer_id').append('<option value="' + value + '">' + name + '</option>');

            }
            if (val == "Stationary") {
                $('#yahoo_id').append('<option value="' + value + '">' + name + '</option>');

            }

            $("#tr_" + id).remove();
            $nc('.chosen').trigger("chosen:updated");
        }
    }

    /*EDIT start*/

    function edit(tid, cid) {

        $(".branch_select").val(cid);
        $(".branch_select").attr("disabled", "disabled");
        /*AJAX start*/
        $.ajax({
            url: '<?php echo base_url(); ?>inventory/Intent_Request/edit/' + tid,
            type: 'GET',
            success: function (data) {

                //console.log(data);
                $("#items_list_edit").html(data);
                $nc('.chosen').trigger("chosen:updated");
            },
            complete: function () {
                jQuery("#machine_id_edit").trigger("chosen:updated");
            },
        });
        /*AJAX end*/
        $nc('.chosen').trigger("chosen:updated");
        $("#myModalEdit").modal("show");
        getReagent_test(cid);
        $('.branch_sub_val').val(cid);
    }

    $city_cnt_edit = 0;
    function machine_select_edit() {
        var selected_ids = $("#machine_id_edit").val();
        if (selected_ids != '') {
            $("#items_list_edit").attr("style", "");
        } else {
            $nc("#items_list_edit").attr("style", "display:none;");
            //$(".chosen").val('').trigger("liszt:updated");
        }
        $("#selected_items_edit").html("");
        $("#selected_item_edit").html("");
        var option_data = $("#item_list").val();
        $("#selected_item_edit").html(option_data);
        $nc('.chosen').trigger("chosen:updated");

    }
    function select_item_edit(val, id) {

        var skillsSelect = id;
        var selectedText = skillsSelect.options[skillsSelect.selectedIndex].text;
        var prc = selectedText;
        var pm = skillsSelect.value;
        var explode = pm.split('-');
        $("#selected_items_edit").append('<tr id="tr_edit_' + $city_cnt + '"><td>' + prc + '<input type="hidden" name="item[]" value="' + pm + '"></td><td>' + val + '</td><td><input type="text" name="quantity[]" required="" class="form-control"/></td><td><a href="javascript:void(0);" onclick="delete_city_price_edit(\'' + $city_cnt + '\',\'' + prc + '\',\'' + pm + '\')"><i class="fa fa-trash"></i></a></td></tr>');
        //$("#test option[value='1']").remove();
        var old_dv_txt = $("#hidden_test_edit").html();
        $city_cnt = $city_cnt + 1;
        // $("#selected_item_edit option[value='" + skillsSelect.value + "']").remove();
        // $('')
        if (val == "Reagent") {
            $("#selected_item_edit option[value='" + skillsSelect.value + "']").remove();
        }
        if (val == "Consumables") {
            $("#consume_id option[value='" + skillsSelect.value + "']").remove();
        }

        if (val == "Stationary") {
            $("#stationary_id option[value='" + skillsSelect.value + "']").remove();
        }
        $nc('.chosen').trigger("chosen:updated");
        //$("#selected_item").val('').trigger('chosen:updated');
    }

    function delete_city_price_edit(id, name, value, val) {
        var tst = confirm('Are you sure?');
        if (tst == true) {
            if (val == "Reagent") {
                /*Total price calculate start*/
                $('#selected_item_edit').append('<option value="' + value + '">' + name + '</option>');
            }
            if (val == "Consumables") {
                $('#consumables_id').append('<option value="' + value + '">' + name + '</option>');
            }
            if (val == "Stationary") {
                $('#stationary_id').append('<option value="' + value + '">' + name + '</option>');
            }
            $("#tr_edit_" + id).remove();
            $nc('.chosen').trigger("chosen:updated");
        }
    }

    function getReagent() {
        var id = $('#branch_id').val();
        if (id.trim() != '') {
            $.ajax({
                url: "<?php echo base_url(); ?>inventory/Intent_Request/getsub",
                type: "POST",
                data: {branch_fk: id},
                success: function (data) {
                    console.log(data);
                    $('.new_update').html(data);
                    $nc('.chosen').trigger("chosen:updated");

                }
            });
            $('#show_hide').show();
        }
    }

    function getReagent_test(val) {
        setTimeout(function () {
            $.ajax({
                url: "<?php echo base_url(); ?>inventory/Intent_Request/getsub",
                type: "POST",
                data: {branch_fk: val},
                success: function (data) {
                    $('#selected_item_edit').html(data);
                    $nc('.chosen').trigger("chosen:updated");

                }
            });
            $('#show_hide').show();
        }, 1000);
    }
    /*END*/
    $nc('.chosen').trigger("chosen:updated");
    function OnBlurInput(input) {
        this.value == "";
    }
</script>
<script type="text/javascript">
    $(function () {

        $('#example3').dataTable({
            //"bPaginate": false, 
            "bLengthChange": false,
            "bFilter": false,
            "bSort": false,
            "bInfo": false,
            "bAutoWidth": false
        });
    });
</script>

<script type="text/javascript">
    function submitData() {
        var id = $('#branch_id').val();
        $('#branch_error_id').html("");
        var frm_data = $('#submit_id');
        var path = frm_data.attr("action");
        var count = $('#selected_items tr').length;

        var cnt = 0;
        if (id == '') {
            $('#branch_error_id').html("Please Select Branch");
            cnt = cnt + 1;
        } else {

        }
        if (count == '0') {
            $('#error_id').html("Please choose Item");
            cnt = cnt + 1;
        }


        if (cnt > 0) {

            return false;
        }

        $.ajax({
            url: path,
            type: "POST",
            data: frm_data.serialize(),
            success: function (data) {
                location.reload();
            }
        })
    }
    function edit_data() {
        var frm_data = $('#edit_form');
        var path = frm_data.attr("action");
        var count = $('#selected_items_edit tr').length;
        var cnt = 0;
        if (count == 0) {
            $('#error_id_new').html("Please choose Item");
            cnt = cnt + 1;
        }

        if (cnt > 0) {

            return false;
        }

        $.ajax({
            url: path,
            type: "POST",
            data: frm_data.serialize(),
            success: function (data) {
                location.reload();
            }
        });
    }
</script>
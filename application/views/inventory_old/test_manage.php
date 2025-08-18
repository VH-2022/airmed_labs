<!-- Page Heading -->
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
            Manage Test
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Lab List</a></li>
            <li class="active">Profile</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="invoice">
        <!-- title row -->
        <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header">
                    <i class="fa fa-eyedropper"></i> <?= ucfirst($test_details[0]["test_name"]) ?>  
                    <small class="pull-right"><a href="javascript:void(0);" onclick="$('#myModal').modal('show');" class="btn btn-primary btn-sm">Add</a></small>
                </h2>
            </div><!-- /.col -->
        </div>
        <!-- info row -->
        <!-- Table row -->
        <br/>
        <div class="row">
            <div class="col-md-12">
                <?php
                $added_machine = array();
                foreach ($final_array as $key) {
                    $added_machine[] = $key["machine_fk"];
                    ?>
                    <div class="col-md-4">
                        <div class="box box-solid" style="background:#EEE;">
                            <div class="box-header with-border">
                                <i class="fa fa-list"></i>
                                <h3 class="box-title"><?= $key["name"] ?></h3>
                                <a href="javascript:void(0);" onclick="edit('<?= $id ?>', '<?= $key["machine_fk"] ?>')" class="pull-right"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;&nbsp;
                                <a href="<?= base_url(); ?>inventory/Test_item/delete/<?= $id ?>/<?= $key["machine_fk"] ?>" onclick="return confirm('Are you sure?');" class="pull-right" style="margin-right:10px;"><i class="fa fa-trash"></i></a>
                            </div><!-- /.box-header -->
                            <div class="box-body">
                                <ul>
                                    <?php foreach ($key["details"] as $dkey) { ?>
                                        <li><b><?= $dkey["item_name"] ?></b> : <?= $dkey["quantity"] ?></li>
                                    <?php } ?>
                                </ul>
                            </div><!-- /.box-body -->
                        </div><!-- /.box -->
                    </div><!-- /.box-header -->
                <?php } ?>
                <div class="box-body no-padding" id="machine_list">

                </div><!-- /.box-body -->
            </div>
        </div>
    </section><!-- /.content -->
    <div class="clearfix"></div>
</div><!-- /.content-wrapper -->
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add</h4>
            </div>
            <?php echo form_open("inventory/Test_item/add_test_machine/" . $id, array("method" => "POST", "role" => "form")); ?>
            <div class="modal-body">
                <input type="hidden" name="test_fk" value="<?= $id; ?>"/>
                <div class="form-group">
                    <label for="recipient-name" class="form-control-label">Select Machine:</label>
                    <select class="chosen chosen-select" name="machine" required="" id="machine_id" onchange="machine_select();">
                        <option value="">--Select--</option>
                        <?php
                        foreach ($machine_details as $mkey) {
                            if (!in_array($mkey["id"], $added_machine)) {
                                ?>
                                <option value="<?= $mkey["id"] ?>"><?= $mkey["name"] ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>
                <div id="items_list" style="display:none;">
                    <div class="form-group">
                        <label for="message-text" class="form-control-label">Add Item:</label>
                        <select class="chosen chosen-select" name="item" id="selected_item" onchange="select_item();">
                            <option value="">--Select--</option>
                            <?php
                            $item_list = '<option value="">--Select--</option>';
                            foreach ($item_details as $mkey) {
                                $item_list = $item_list . '<option value="' . $mkey["id"] . '">' . $mkey["name"] . '</option>';
                                ?>
                                <option value="<?= $mkey["id"] ?>"><?= $mkey["name"] ?></option>
                            <?php } ?>
                        </select>
                        <input type="hidden" id="item_list" value="<?= htmlspecialchars($item_list, ENT_QUOTES); ?>"/>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Quantity</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="selected_items">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Add</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            <?php echo form_close(); ?>
        </div>

    </div>
</div>


<?php /* Nishit Edit changes are start */ ?>
<div id="myModalEdit" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Update</h4>
            </div>
            <?php echo form_open("inventory/Test_item/edit_test_machine/" . $id, array("method" => "POST", "role" => "form")); ?>
            <div class="modal-body">
                <input type="hidden" name="test_fk" value="<?= $id; ?>"/>
                <div class="form-group">
                    <label for="recipient-name" class="form-control-label">Select Machine:</label>
                    <select class="form-control" name="machine" id="machine_id_edit" onchange="machine_select_edit();">
                        <option value="">--Select--</option>
                        <?php
                        foreach ($machine_details as $mkey) {
                            ?>
                            <option value="<?= $mkey["id"] ?>"><?= $mkey["name"] ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <div id="items_list_edit">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Update</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            <?php echo form_close(); ?>
        </div>

    </div>
</div>
<?php /* END */ ?>

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
    function select_item() {
        var test_val = $("#selected_item").val();
        $("#test_error").html("");
        var cnt = 0;
        if (test_val.trim() == '') {
            $("#test_error").html("Item is required.");
            cnt = cnt + 1;
        }
        if (cnt > 0) {
            return false;
        }
        var skillsSelect = document.getElementById("selected_item");
        var selectedText = skillsSelect.options[skillsSelect.selectedIndex].text;
        var prc = selectedText;
        var pm = skillsSelect.value;
        var explode = pm.split('-');
        $("#selected_items").append('<tr id="tr_' + $city_cnt + '"><td>' + prc + '</td><td><input type="hidden" name="item[]" value="' + pm + '"><input type="text" name="quantity[]" required="" class="form-control"/></td><td><a href="javascript:void(0);" onclick="delete_city_price(\'' + $city_cnt + '\',\'' + prc + '\',\'' + pm + '\')"><i class="fa fa-trash"></i></a></td></tr>');
        //$("#test option[value='1']").remove();
        var old_dv_txt = $("#hidden_test").html();
        $city_cnt = $city_cnt + 1;
        $("#selected_item option[value='" + skillsSelect.value + "']").remove();
        $nc('.chosen').trigger("chosen:updated");
        //$("#selected_item").val('').trigger('chosen:updated');
    }

    function delete_city_price(id, name, value) {
        var tst = confirm('Are you sure?');
        if (tst == true) {
            /*Total price calculate start*/
            $('#selected_item').append('<option value="' + value + '">' + name + '</option>').trigger("chosen:updated");
            $("#tr_" + id).remove();
        }
    }

    /*EDIT start*/

    function edit(tid, mid) {
        $("#machine_id_edit").val(mid);
        $("#machine_id_edit").attr("disabled", "disabled");
        /*AJAX start*/
        $.ajax({
            url: '<?php echo base_url(); ?>inventory/Test_item/edit_machine/' + tid + '/' + mid,
            type: 'GET',
            success: function (data) {
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
    function select_item_edit() {
        var test_val = $("#selected_item_edit").val();
        $("#test_error_edit").html("");
        var cnt = 0;
        if (test_val.trim() == '') {
            $("#test_error_edit").html("Item is required.");
            cnt = cnt + 1;
        }
        if (cnt > 0) {
            return false;
        }
        var skillsSelect = document.getElementById("selected_item_edit");
        var selectedText = skillsSelect.options[skillsSelect.selectedIndex].text;
        var prc = selectedText;
        var pm = skillsSelect.value;
        var explode = pm.split('-');
        $("#selected_items_edit").append('<tr id="tr_edit_' + $city_cnt + '"><td>' + prc + '</td><td><input type="hidden" name="item[]" value="' + pm + '"><input type="text" name="quantity[]" required="" class="form-control"/></td><td><a href="javascript:void(0);" onclick="delete_city_price_edit(\'' + $city_cnt + '\',\'' + prc + '\',\'' + pm + '\')"><i class="fa fa-trash"></i></a></td></tr>');
        //$("#test option[value='1']").remove();
        var old_dv_txt = $("#hidden_test_edit").html();
        $city_cnt = $city_cnt + 1;
        $("#selected_item_edit option[value='" + skillsSelect.value + "']").remove();
        $nc('.chosen').trigger("chosen:updated");
        //$("#selected_item").val('').trigger('chosen:updated');
    }

    function delete_city_price_edit(id, name, value) {
        var tst = confirm('Are you sure?');
        if (tst == true) {
            /*Total price calculate start*/
            $('#selected_item_edit').append('<option value="' + value + '">' + name + '</option>').trigger("chosen:updated");
            $("#tr_edit_" + id).remove();
        }
    }
    /*END*/
    $nc('.chosen').trigger("chosen:updated");
</script>
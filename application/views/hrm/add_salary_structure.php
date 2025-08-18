<!-- Page Heading -->
<style>
    #table_body td:nth-child(2n+1) {
        width: 100px;
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1>Department<small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>hrm/department/"><i class="fa fa-briefcase"></i> Department List</a></li>
            <li class="active"> Add Salary Structure</li>
        </ol>
    </section>
    <form role="form" id="department_form" action="<?php echo base_url(); ?>hrm/department/add_salary_structure/<?php echo $query->id ?>" method="post" enctype="multipart/form-data">

        <section class="content">
            <div class="widget">
                <?php if ($this->session->flashdata('unsuccess')) { ?>
                    <div class="alert alert-danger alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&#10006;</button>
                        <?php echo $this->session->flashdata('unsuccess'); ?>
                    </div>
                <?php } ?>
                <?php if ($this->session->flashdata('success')) { ?>
                    <div class="alert alert-success alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&#10006;</button>
                        <?php echo $this->session->flashdata('success'); ?>
                    </div>
                <?php } ?>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-primary" style="border-color:#bf2d37;">
                        <div class="panel-heading" style="background-color:#bf2d37;">
                            <!-- form start -->
                            <h3 class="panel-title">Salary Structure</h3>
                        </div>
                        <div class="panel-body">
                            <div class="col-md-12">
                                <table id="example4" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Department Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <input type="text" name="department_name" id="department_name" class="form-control" readonly value="<?php echo $query->name ?>">
                                                <input type="hidden" name="department_id" id="department_id" class="form-control" value="<?php echo $query->id ?>">
                                                <input type="hidden" name="designation_id" id="designation_id" class="form-control">
<!--                                                <span id="department_name_error" style="color:red;"></span>-->
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>



                                <!-- Add Modal-->
                                <div class="modal fade" id="family_model" role="dialog">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title">Add Salary Structure</h4>
                                            </div>
                                            <div class="modal-body">
                                                <table id="example4" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Add/Remove</th>
                                                            <th>Salary Name</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="table_body">
                                                        <tr>
                                                            <td>
                                                                <a class="srch_view_a" href="javascript:void(0)" onclick="add_field();"><i class="fa fa-plus-square"></i></a>
                                                            </td>
                                                            <td>
                                                                <input type="hidden" name="count_designation" id="count_designation" value="1">
                                                                <input type="text" name="designation_1" id="designation_1" class="form-control">
                                                                <span id="designation_error_1" style="color:red;"></span>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-primary" type="button" onclick="submit_button();">Add</button>
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>







                                <div class="col-md-12">
                                    <table id="example4" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Designation</th>
                                                <th>Update Salary Structure</th>
<!--                                                <th>Edit</th>-->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($final_array as $designation) { ?>
                                                <tr>
                                                    <td><b><?= $designation->name ?></b><?php
                                                        foreach ($designation->designation as $sskey) {
                                                            if (!empty($sskey->salary_name)) {
                                                                echo "<br>-";
                                                                echo ucfirst($sskey->salary_name);
                                                            }
                                                        }
                                                        ?></td>
    <!--                                                    <td><input type="button" class="btn btn-success btn-sm" id="add_new_btn" onclick="check_new_user('<?php //echo $designation->id         ?>');" value="Add" ></td>-->
                                                    <td><input type="button" class="btn btn-success btn-sm" id="edit_new_btn" onclick="check_new_user_edit('<?php echo $designation->id ?>');" value="Update" ></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>

                                <?php if (isset($added_data) && count($added_data) > 0) { ?>

                                    <div class="col-md-4">
                                        <table id="example5" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Designation - <?= $designation_name[0]->name ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach ($added_data as $ad_data) {
                                                    ?>
                                                    <tr>
                                                        <td><?= $ad_data->salary_name ?></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php } ?>


                            </div>
                            <div class="panel-footer">
                                <!--                            <button class="btn btn-primary" type="button" onclick="submit_button();">Add</button>-->
                            </div>
                            </form>
                        </div><!-- /.box -->
                    </div>
                </div>
        </section>
</div>




<!-- Edit Modal-->
<form role="form" id="department_form1" action="<?php echo base_url(); ?>hrm/department/edit_salary" method="post" enctype="multipart/form-data">
    <div class="modal fade" id="family_model_edit" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Update Salary Structure</h4>
                </div>
                <div class="modal-body">
                    <table id="example4_edit" class="table table-bordered table-striped">
                    </table>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="button" onclick="submit_button1();">Save</button>
                    <input type="hidden" name="department_id" id="department_id" class="form-control" value="<?php echo $query->id ?>">
                    <input type="hidden" name="designation_id_edit" id="designation_id_edit" class="form-control">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    function check_new_user(value) {
        $("#designation_id").val(value);
        if (value != "") {
            $("#family_model").modal("show");
        }
    }

    function check_new_user_edit(value) {
        $("#designation_id_edit").val(value);
        var designation_id = value;

        if (value != "") {
            //$("#family_model_edit").modal("show");
            $.ajax({
                type: 'post',
                url: '<?php echo base_url(); ?>/hrm/Department/edit_salary_popup',
                dataType: 'html',
                data: {designation_id: designation_id},
                success: function (result) {
                    $("#example4_edit").html(result);
                    $("#family_model_edit").modal('show');
                    $(".edit").val('edit');
                }
            });
        }
    }

    var cnt = 1;
    function add_field() {
        cnt++;
        var coun = "'" + cnt + "'";
        $('#table_body').append('<tr id="tr_' + cnt + '"><td><a href="javascript:void(0)" onclick="row_remove(' + coun + ');"><i style="color:red;" class="fa fa-minus-square"></i></a></td><td><input type="text" name="designation_' + cnt + '" id="designation_' + cnt + '" class="form-control"><span id="designation_error_' + cnt + '" style="color:red;"></span></td></tr>');
        $('#count_designation').val(cnt);
    }
    function add_field1() {
        var cnt = $('#example4_edit tbody tr').length;
        var lastprchild = cnt - 1;

        var lastscond = $('#table_body1 tr:nth-child(' + lastprchild + ')').attr('id');
        var tresplit = lastscond.split("_");
        var cnt = parseInt(tresplit[1]) + 1;
        var coun = "'" + cnt + "'";

        $('#table_body1 tr:nth-child(' + lastprchild + ')').after('<tr id="tr_' + cnt + '"><td><a href="javascript:void(0)" onclick="row_remove(' + coun + ');"><i style="color:red;" class="fa fa-minus-square"></i></a></td><td><input type="text" name="designation1[]" id="designation1_' + cnt + '" class="form-control"><span id="designation_error_' + cnt + '" style="color:red;"></span></td><td><select name="cuttype[]" class="form-control"><option value="1">Fix</option><option value="2">Percentages</option></select></td><td><input type="text" placeholder="value" name="pricevalue[]" id="pricevalue_' + cnt + '" class="form-control pricevalue"></td></tr>');

        /* $('#count_designation1').val(cnt); */
    }
    function row_remove(val) {
        $('#tr_' + val).remove();
        cnt--;
        $('#count_designation').val(cnt);
    }
    function submit_button() {
        var count = $('#count_designation').val();
        var temp = 1;

        var j = 1;
        for (j = 1; j <= count; j++) {
            var name = $('#designation_' + j).val();
            $("#designation_error_" + j).html("");
            if (name == "") {
                $("#designation_error_" + j).html("Field Required.");
                temp = 0;
            }
        }

        if (temp == 1) {
            $("#department_form").submit();
        }
    }
    function submit_button1() {
        var count = $('#count_designation1').val();
        var temp = 1;
        /* var j = 1;
         for (j = 1; j <= count; j++) {
         var name = $('#designation_1' + j).val();
         $("#designation_error_" + j).html("");
         if (name == "") {
         $("#designation_error_" + j).html("Field Required.");
         temp = 0;
         }
         } */
        $("input[name='designation1[]']").each(function (index, element) {
            var getval = $(element).val().trim();
            var spliid = this.id.split("_");
            var id = spliid[1];
            $("#designation_error_" + id).html("");
            if (id != '0') {
                if (getval.trim() == '') {
                    $("#designation_error_" + id).html("Field is Required.");
                    temp = 0;
                }
            }

        });

        if (temp == 1) {
            $("#department_form1").submit();
        }
    }
</script>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Manage Salary<small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><i class="fa fa-rocket"></i> Application</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-primary" style="border-color:#bf2d37;">
                    <div class="panel-heading" style="background-color:#bf2d37;">
                        <h3 class="panel-title">Manage Salary</h3>

                    </div><!-- /.box-header -->
                    <div class="panel-body">
                        <?php echo form_open('hrm/leave/leave_list?employee=' . $_GET["employee"] . '&month=' . $_GET["month"] . '&current_year=' . $_GET["current_year"] . '&city=' . $_GET["city"], array('class' => 'form-horizontal', 'method' => 'POST', 'role' => 'form')); ?>

                        <button type="button" class="btn btn-info btn-sm pull-right" data-toggle="modal" data-target="#myModal">Upload Sheet</button>


                        <div class="col-md-2">
                            <select class="form-control" tabindex="-1" name="current_year" id="current_year">
                                <option value="" >Select Year</option>
                                <?php for ($j = 2018; $j <= date('Y'); $j++) { ?>
                                    <option value="<?php echo $j ?>" <?php
                                    if ($j == $current_year) {
                                        echo "selected";
                                    }
                                    ?>><?php echo $j ?></option>
                                        <?php }
                                        ?>
                            </select>
                        </div>


                        <div class="col-md-2">
                            <select class="form-control" tabindex="-1" name="month" id="month">
                                <option value="" >Select Month</option>
                                <?php
                                $current_date = date("Y-m-d");
                                $current_month = date('m');
                                $month = $_GET["month"];
                                //for ($i = 1; $i <= $current_month; $i++) {
                                for ($i = 1; $i <= 12; $i++) {
                                    ?>
                                    <?php if ($i == 1) { ?>
                                        <option value=<?= $i ?> <?php
                                        if ($month == $i) {
                                            echo " selected";
                                        }
                                        ?>>January </option>
                                            <?php } ?>
                                            <?php if ($i == 2) { ?>
                                        <option value=<?= $i ?> <?php
                                        if ($month == $i) {
                                            echo " selected";
                                        }
                                        ?>>February </option>
                                            <?php } ?>
                                            <?php if ($i == 3) { ?>
                                        <option value=<?= $i ?> <?php
                                        if ($month == $i) {
                                            echo " selected";
                                        }
                                        ?>>March</option>
                                            <?php } ?>
                                            <?php if ($i == 4) { ?>
                                        <option value=<?= $i ?> <?php
                                        if ($month == $i) {
                                            echo " selected";
                                        }
                                        ?>>April </option>
                                            <?php } ?>
                                            <?php if ($i == 5) { ?>
                                        <option value=<?= $i ?> <?php
                                        if ($month == $i) {
                                            echo " selected";
                                        }
                                        ?>>May </option>
                                            <?php } ?>
                                            <?php if ($i == 6) { ?>
                                        <option value=<?= $i ?> <?php
                                        if ($month == $i) {
                                            echo " selected";
                                        }
                                        ?>>June </option>
                                            <?php } ?>
                                            <?php if ($i == 7) { ?>
                                        <option value=<?= $i ?> <?php
                                        if ($month == $i) {
                                            echo " selected";
                                        }
                                        ?>>July </option>
                                            <?php } ?>
                                            <?php if ($i == 8) { ?>
                                        <option value=<?= $i ?> <?php
                                        if ($month == $i) {
                                            echo " selected";
                                        }
                                        ?>>August </option>
                                            <?php } ?>
                                            <?php if ($i == 9) { ?>
                                        <option value=<?= $i ?> <?php
                                        if ($month == $i) {
                                            echo " selected";
                                        }
                                        ?>>September </option>
                                            <?php } ?>
                                            <?php if ($i == 10) { ?>
                                        <option value=<?= $i ?><?php
                                        if ($month == $i) {
                                            echo " selected";
                                        }
                                        ?>>October </option>
                                            <?php } ?>
                                            <?php if ($i == 11) { ?>
                                        <option value=<?= $i ?> <?php
                                        if ($month == $i) {
                                            echo " selected";
                                        }
                                        ?>>November </option>
                                            <?php } ?>
                                            <?php if ($i == 12) { ?>
                                        <option value=<?= $i ?> <?php
                                        if ($month == $i) {
                                            echo " selected";
                                        }
                                        ?>>December </option>
                                                <?php
                                            }
                                        }
                                        ?>
                            </select>
                        </div>
<!--                        <input type="hidden" value="<?= date("Y") ?>" name="current_year">-->
                        <div class="col-md-2">
                            <select class="form-control" tabindex="-1" name="city" id="city">
                                <option value="">All City</option>
                                <?php
                                foreach ($test_cityes as $emp) {
                                    ?>
                                    <option value = "<?php echo $emp['id']; ?>" <?php
                                    if (isset($_GET['city']) && $_GET['city'] == $emp['id']) {
                                        echo "selected";
                                    }
                                    ?>> <?php echo $emp['name'] ?></option>
                                            <?php
                                        }
                                        ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" tabindex="-1" name="employee" id="employee">
                                <option value="" >All Employee</option>
                                <?php
                                foreach ($employee as $emp) {
                                    ?>
                                    <option value = "<?php echo $emp['id']; ?>" <?php
                                    if (isset($_GET['employee']) && $_GET['employee'] == $emp['id']) {
                                        echo "selected";
                                    }
                                    ?>> <?php echo $emp['name'] ?></option>
                                            <?php
                                        }
                                        ?>
                            </select>
                        </div>
                        <input type="button" id="search_btn" class="btn btn-sm btn-primary" value="Search"/>
                        <?php //if (!empty($final_array)) { ?>
                        <!--                            <a href="javascript:void(0);" class="btn btn-sm btn-primary">Export CSV</a>-->
                        <?php //} ?>
                        <?php if (!empty($final_array)) { ?>
                            <a href='<?= base_url(); ?>hrm/leave/export_salary_sheet?<?php echo 'employee=' . $_GET["employee"] . '&month=' . $_GET["month"] . '&current_year=' . $_GET["current_year"] . '&city=' . $_GET["city"]; ?>' class="btn btn-sm btn-primary">Export Salary Sheet</a>
                        <?php } ?>
                        <br/>
                        <br/>
                        <div class="widget">
                            <?php if (!empty($unsuccess)) { ?>
                                <div class="alert alert-danger alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $unsuccess; ?>
                                </div>
                            <?php } ?>
                            <?php if (!empty($success)) { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $success; ?>
                                </div>
                            <?php } ?>
                        </div>
                        <br> 
                        <?php
                        $common_salary_structure[] = array("salary_name" => "Leaves");
                        $common_salary_structure[] = array("salary_name" => "OT");
                        $common_salary_structure[] = array("salary_name" => "Petrol");
                        $common_salary_structure[] = array("salary_name" => "Insentive");
                        $common_salary_structure[] = array("salary_name" => "Sunday Wages");
                        $common_salary_structure[] = array("salary_name" => "Festival Allowance");
                        $common_salary_structure[] = array("salary_name" => "Others Earning");

                        //echo "<pre>"; print_r($common_salary_structure);die();
                        $new_common_salary_structure = array();
                        $new_common_salary_structure[] = array("salary_name" => "Basic");
                        $new_common_salary_structure[] = array("salary_name" => "DA");
//                        $new_common_salary_structure[] = array("salary_name" => "HRA");
//                        $new_common_salary_structure[]=array("salary_name" => "Conveyance Allowance");
//                        $new_common_salary_structure[]=array("salary_name" => "Special Allowance");

                        $new_common_salary_structure[] = array("salary_name" => "Other Allowance");
//                        $new_common_salary_structure[] = array("salary_name" => "Special Allowance");
//                        $new_common_salary_structure[] = array("salary_name" => "Medical Reimbursement");
                        $new_common_salary_structure[] = array("salary_name" => "MAINTENACE COST");
                        $new_common_salary_structure[] = array("salary_name" => "OT");
                        $new_common_salary_structure[] = array("salary_name" => "Petrol");
                        $new_common_salary_structure[] = array("salary_name" => "Insentive");
                        $new_common_salary_structure[] = array("salary_name" => "Sunday Wages");
                        $new_common_salary_structure[] = array("salary_name" => "Festival Allowance");
                        $new_common_salary_structure[] = array("salary_name" => "Others Earning");
                        //$new_common_salary_structure[] = array("salary_name" => "Other Reimbursement");
                        $new_common_salary_structure[] = array("salary_name" => "PF-Employee's Contribution");
                        $new_common_salary_structure[] = array("salary_name" => "ESIC-Employee's Contribution");

                        $new_common_salary_structure[] = array("salary_name" => "PF-Employer's Contribution");
                        $new_common_salary_structure[] = array("salary_name" => "ESIC-Employer's Contribution");
                        $new_common_salary_structure[] = array("salary_name" => "TDS");
                        $new_common_salary_structure[] = array("salary_name" => "Leaves");
                        $new_common_salary_structure[] = array("salary_name" => "PT");
                        $new_common_salary_structure[] = array("salary_name" => "Others Deduction");
                        $new_common_salary_structure[] = array("salary_name" => "Present Day Basic Salary");


                        $new_common_salary_structure[] = array("salary_name" => "Present Day DA");
                        $new_common_salary_structure[] = array("salary_name" => "Present Day Allowance");
                        $new_common_salary_structure[] = array("salary_name" => "Present Day ESIC");

                        $new_common_salary_structure[] = array("salary_name" => "SALARY (CTC) / PM");
                        $new_common_salary_structure[] = array("salary_name" => "Net Take Home");
                        ?>
                        <div class="col-sm-12">
                            <?php
                            if ($_GET['month'] != "") {
                                echo "<h3>" . date("F", mktime(0, 0, 0, $_GET['month'], 10)) . " " . $current_year . "</h3>";
                            }
                            ?>
                            <div class="tableclass table-responsive">
                                <table id="example4" class="table table-bordered table-striped" style="font-size: 12px;">
                                    <thead>
                                        <tr>
                                            <th style="width:10%">Name</th>
                                            <?php foreach ($new_common_salary_structure as $csskey) { ?>
                                                <th><?= $csskey["salary_name"] ?></th>
                                            <?php } ?>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <?php foreach ($final_array as $fkey) { ?>

                                        <tbody>
                                            <tr>
                                                <td><?= "<b>" . ucfirst($fkey->name) . " </b>&nbsp;<small>(" . ucfirst($fkey->employee_id) . ")</small>" ?></td>
                                                <?php
                                                if (!empty($fkey->salary_data)) {
                                                    foreach ($new_common_salary_structure as $csskey) {
                                                        $phtml = '<td>NA</td>';
                                                        foreach ($fkey->salary_data[0]->details as $eskey) {
                                                            $type = "Earning";
                                                            if ($eskey->earning_type == 2) {
                                                                $type = "Deduction";
                                                            }
                                                            if (strtoupper($csskey["salary_name"]) == strtoupper($eskey->salary_field)) {
                                                                /* $sval = ($eskey->value>0)?$eskey->value:0; */
                                                                $sval = $eskey->value;
                                                                $phtml = "<td>" . $sval . "</td>";
                                                            }
                                                        }
                                                        echo $phtml;
                                                    }
                                                } else {
                                                    
                                                }
                                                ?>
                                                <th>
                                                    <!--<a href='javascript:void(0);' data-toggle="tooltip" data-original-title="Download Salary Sleep"><i class="fa fa-print"></i></a>-->
                                                    <a href='javascript:void(0);' data-toggle="tooltip" data-original-title="Download Salary Sleep" onclick="fun_wtlh('<?php echo $fkey->id; ?>', '<?php echo $_GET['month'] ?>', '<?php echo $_GET['current_year'] ?>')"><i class="fa fa-print"></i></a>
                                                </th>
                                            </tr>
                                        </tbody>

                                    <?php } ?>
                                </table>    
                            </div>
                        </div>
                        </form>

                        <div style="text-align:right;" class="box-tools">
                            <ul class="pagination pagination-sm no-margin pull-right">
                                <?php echo $links; ?>
                            </ul>
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Upload Attendance Sheet</h4>
            </div>
            <?php echo form_open_multipart('hrm/leave/leave_list?employee=' . $_GET["employee"] . '&month=' . $_GET["month"] . '&current_year=' . $_GET["current_year"] . '&city=' . $_GET["city"], array('class' => '', 'id' => 'uploadData', 'method' => 'POST', 'role' => 'form')); ?>
            <div class="modal-body">
                <div class="form-group">

                    <label for="recipient-name1" class="col-form-label">Year:</label>
                    <select class="form-control" tabindex="-1" name="uyear" id="uyear" required="">
                        <option value="" >Select Year</option>
                        <?php for ($j = 2018; $j <= date('Y'); $j++) { ?>
                            <option value="<?php echo $j ?>" <?php
                        if ($j == $current_year) {
                            echo "selected";
                        }
                            ?>><?php echo $j ?></option>
                                <?php }
                                ?>
                    </select>


                    <label for="recipient-name" class="col-form-label">Month:</label>
                    <select name="umonth" class="form-control" required="">
                        <option value="">--Select--</option>
                        <?php
                        $setValue = set_value("umonth");
                        $current_date = date("Y-m-d");
                        $current_month = date('m');
                        $month = $_GET["month"];
                        for ($i = 1; $i <= 12; $i++) {
                            ?>
                            <?php if ($i == 1) { ?>
                                <option value="<?= $i ?>" <?php
                        if ($setValue == $i) {
                            echo " selected";
                        }
                                ?>>January </option>
                                    <?php } ?>
                                    <?php if ($i == 2) { ?>
                                <option value="<?= $i ?>" <?php
                                if ($setValue == $i) {
                                    echo " selected";
                                }
                                        ?>>February </option>
                                    <?php } ?>
                                    <?php if ($i == 3) { ?>
                                <option value="<?= $i ?>" <?php
                                if ($setValue == $i) {
                                    echo " selected";
                                }
                                        ?>>March </option>
                                    <?php } ?>
                                    <?php if ($i == 4) { ?>
                                <option value="<?= $i ?>" <?php
                                if ($setValue == $i) {
                                    echo " selected";
                                }
                                        ?>>April </option>
                                    <?php } ?>
                                    <?php if ($i == 5) { ?>
                                <option value="<?= $i ?>" <?php
                                if ($setValue == $i) {
                                    echo " selected";
                                }
                                        ?>>May </option>
                                    <?php } ?>
                                    <?php if ($i == 6) { ?>
                                <option value="<?= $i ?>" <?php
                                if ($setValue == $i) {
                                    echo " selected";
                                }
                                        ?>>June </option>
                                    <?php } ?>
                                    <?php if ($i == 7) { ?>
                                <option value="<?= $i ?>" <?php
                                if ($setValue == $i) {
                                    echo " selected";
                                }
                                        ?>>July </option>
                                    <?php } ?>
                                    <?php if ($i == 8) { ?>
                                <option value="<?= $i ?>" <?php
                                if ($setValue == $i) {
                                    echo " selected";
                                }
                                        ?>>August </option>
                                    <?php } ?>
                                    <?php if ($i == 9) { ?>
                                <option value="<?= $i ?>" <?php
                                if ($setValue == $i) {
                                    echo " selected";
                                }
                                        ?>>September</option>
                                    <?php } ?>
                                    <?php if ($i == 10) { ?>
                                <option value="<?= $i ?>" <?php
                                if ($setValue == $i) {
                                    echo " selected";
                                }
                                        ?>>October </option>
                                    <?php } ?>
                                    <?php if ($i == 11) { ?>
                                <option value="<?= $i ?>" <?php
                                if ($setValue == $i) {
                                    echo " selected";
                                }
                                        ?>>November </option>
                                    <?php } ?>
                                    <?php if ($i == 12) { ?>
                                <option value="<?= $i ?>" <?php
                                if ($setValue == $i) {
                                    echo " selected";
                                }
                                        ?>>December </option>
                                        <?php
                                    }
                                }
                                ?>
                    </select>
                    <span style="color:red"><?= form_error("umonth"); ?></span>
                </div>

                <div class="form-group">
                    <label for="message-text" class="col-form-label">File:</label>
                    <input type="file" name="userfile" class="form-control" accept=".csv" required="">
                    <span style="color:red"><?= form_error("userfile"); ?></span>
<!--                    <small><b>Sample Formate</b>&nbsp; (Sr. No., Employee Code, Name of Employee, No. of Leaves, Over Time, Petrol, Incentive, Sunday Wages, Festival Allowance, Others Earning, TDS, MAINTENACE COST, Working Hours, Other Deduction)</small>-->
                    <small><b>Sample Formate</b>&nbsp; (Sr. No., Employee Code, Name of Employee, No. of Leaves, Over Time, Other, TDS, MAINTENACE COST, Working Hours, Other Deduction)</small>
                </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Upload</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            </form>
        </div>

    </div>
</div>

<form role="form" id="offer_wtlh" action="<?php echo base_url(); ?>hrm/leave/pdf_salary_slip/?employee=<?php echo $_GET['employee'] ?>&month=<?php echo $_GET['month'] ?>&current_year=<?php echo $_GET['current_year'] ?>" method="post" enctype="multipart/form-data">
    <div class="modal fade" id="wtlh_model" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Salary Slip</h4>
                </div>
                <div class="modal-body">
                    <table id="example4_edit" class="table table-bordered table-striped">
                        Download : &nbsp;&nbsp;
                        <input type="radio" name="letter_head" value="1" checked> With Letter Head &nbsp;
                        <input type="radio" name="letter_head" value="0"> Without Letter Head<br>
                    </table>
                </div>
                <div class="modal-footer">
                    <input class="btn btn-primary" type="submit" value="Download" id="submit_off_wtlh">
                    <input type="hidden" name="emp_id" id="emp_offer_id">
                    <input type="hidden" name="month_id" id="month_id">
                    <input type="hidden" name="year_id" id="year_id">

                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    $("#search_btn").click(function () {
        var emp_val = $("#employee").val();
        var month_val = $("#month").val();
        var city = $("#city").val();
        var current_year = $("#current_year").val();
        window.location = "leave_list?employee=" + emp_val + "&month=" + month_val + "&current_year=" + current_year + "&city=" + city;
    });
</script>
<script>
    $(function () {
        setTimeout(function () {
            $(".alert").hide('blind', {}, 500)
        }, 5000);
    });
<?php if (!empty(form_error("umonth")) || !empty(form_error("ucity")) || !empty(form_error("userfile"))) { ?>
        setTimeout(function () {
            $("#myModal").modal("show");
        }, 1000);
<?php } ?>

    function fun_wtlh(id, month, year) {

        $("#wtlh_model").modal("show");
        $("#emp_offer_id").val(id);
        $("#month_id").val(month);
        $("#year_id").val(year);
    }

    $("#submit_off_wtlh").click(function () {
        setTimeout(function () {
            $('#wtlh_model').modal('hide')
        }, 2000);
    });

</script>
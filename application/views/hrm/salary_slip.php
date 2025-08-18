<style>
    body{
        font-size:12px;
    }
    table {
        border-collapse: collapse;
    }
    table, td, th {
        border: 1px solid black;
    }
    .left{text-align:left;}
    .bg{background:silver;}
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Salary Slip<small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>hrm/admin/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><i class="fa fa-rocket"></i> Salary Slip</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-primary" style="border-color:#bf2d37;">
                    <div class="panel-heading" style="background-color:#bf2d37;">
                        <h3 class="panel-title">Employee Salary</h3>

                    </div><!-- /.box-header -->
                    <div class="panel-body">
                        <?php echo form_open('hrm/employee/salary_slip', array('class' => 'form-horizontal', 'method' => 'get', 'role' => 'form')); ?>
                        <div class="col-md-3">
                            <select class="form-control" tabindex="-1" name="employee" id="employee">
                                <option value="" >Select Employee</option>
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
                        <div class="col-md-3">
                            <select class="form-control" tabindex="-1" name="month" id="month">
                                <option value="" >Select Month</option>
                                <?php
                                $current_date = date("Y-m-d");
                                $current_month = date('m');

                                for ($i = 1; $i < $current_month; $i++) {
                                    ?>
                                    <?php if ($i == 1) { ?>
                                        <option value=<?= $i ?> <?php
                                        if ($month == $i) {
                                            echo "selected";
                                        }
                                        ?>>Jan-<?= date("Y") ?> </option>
                                            <?php } ?>
                                            <?php if ($i == 2) { ?>
                                        <option value=<?= $i ?> <?php
                                        if ($month == $i) {
                                            echo "selected";
                                        }
                                        ?>>Feb-<?= date("Y") ?> </option>
                                            <?php } ?>
                                            <?php if ($i == 3) { ?>
                                        <option value=<?= $i ?> <?php
                                        if ($month == $i) {
                                            echo "selected";
                                        }
                                        ?>>Mar-<?= date("Y") ?> </option>
                                            <?php } ?>
                                            <?php if ($i == 4) { ?>
                                        <option value=<?= $i ?> <?php
                                        if ($month == $i) {
                                            echo "selected";
                                        }
                                        ?>>Apr-<?= date("Y") ?> </option>
                                            <?php } ?>
                                            <?php if ($i == 5) { ?>
                                        <option value=<?= $i ?><?php
                                        if ($month == $i) {
                                            echo "selected";
                                        }
                                        ?>>May-<?= date("Y") ?> </option>
                                            <?php } ?>
                                            <?php if ($i == 6) { ?>
                                        <option value=<?= $i ?><?php
                                        if ($month == $i) {
                                            echo "selected";
                                        }
                                        ?>>June-<?= date("Y") ?> </option>
                                            <?php } ?>
                                            <?php if ($i == 7) { ?>
                                        <option value=<?= $i ?><?php
                                        if ($month == $i) {
                                            echo "selected";
                                        }
                                        ?>>July-<?= date("Y") ?> </option>
                                            <?php } ?>
                                            <?php if ($i == 8) { ?>
                                        <option value=<?= $i ?><?php
                                        if ($month == $i) {
                                            echo "selected";
                                        }
                                        ?>>Aug-<?= date("Y") ?> </option>
                                            <?php } ?>
                                            <?php if ($i == 9) { ?>
                                        <option value=<?= $i ?><?php
                                        if ($month == $i) {
                                            echo "selected";
                                        }
                                        ?>>Sep-<?= date("Y") ?> </option>
                                            <?php } ?>
                                            <?php if ($i == 10) { ?>
                                        <option value=<?= $i ?><?php
                                        if ($month == $i) {
                                            echo "selected";
                                        }
                                        ?>>Oct-<?= date("Y") ?> </option>
                                            <?php } ?>
                                            <?php if ($i == 11) { ?>
                                        <option value=<?= $i ?><?php
                                        if ($month == $i) {
                                            echo "selected";
                                        }
                                        ?>>Nov-<?= date("Y") ?> </option>
                                            <?php } ?>
                                            <?php if ($i == 12) { ?>
                                        <option value=<?= $i ?><?php
                                        if ($month == $i) {
                                            echo "selected";
                                        }
                                        ?>>Dec-<?= date("Y") ?> </option>
                                                <?php
                                            }
                                        }
                                        ?>
                            </select>
                        </div>
                        <input type="hidden" value="<?= date("Y") ?>" name="current_year">
                        <input type="submit" value="Generate Salary Slip" class="btn btn-primary btn-md" style="height:33px">
                        <?php if (isset($all_record) && count($all_record) > 0) { ?>
                            <a style="float:right;" href='javascript:void(0);' data-toggle="tooltip" onclick="fun_wtlh('<?php echo $row->id; ?>')" class="btn btn-primary btn-sm"><i class="fa fa-print"></i> Download SALARY SLIP</a>
                        <?php }
                        ?>  
                        </form>
                        <br>
                        <?php
                        if (isset($_GET['employee']) && $_GET['month'] != "") {
                            $employee_name = (isset($query->name)) ? $query->name : $all_record[0]->name;
                            echo "<h3>" . $employee_name . " - " . date("F", mktime(0, 0, 0, $_GET['month'], 10)) . " " . date('Y') . "</h3>";
                        }
                        ?>
                        <div class="widget">
                            <?php if ($this->session->flashdata('unsuccess')) { ?>
                                <div class="alert alert-danger alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $this->session->flashdata('unsuccess'); ?>
                                </div>
                            <?php } ?>
                            <?php if ($this->session->flashdata('success')) { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $this->session->flashdata('success'); ?>
                                </div>
                            <?php } ?>
                        </div>
                        <br> 
                        <?php
                        if ($_GET['employee'] != "" && $_GET['month'] != "") {

                            //echo "<pre>"; print_r($all_record); exit;                            
                            ?>

                            <?php echo form_open('hrm/employee/salary_slip_data_save', array('class' => 'form-horizontal', 'method' => 'post', 'role' => 'form', 'id' => 'salary_slip_form')); ?>

                            <table>
                                <tr>
                                    <th colspan="6" style="padding:10px;font-size:14px;"><center><u>ANNEXURE-I</u></center></th>
                                </tr>
                                <tr>
                                    <th colspan="4"><center><u>SALARY BREAK-UP</u></center></th>
                                <td>
                                    <input type="text" id="present_day" name="present_day" value="<?php echo isset($total_present_day) ? $total_present_day : $all_record[0]->present_day; ?>"/>
                                </td>
                                <td style="width:100px">Present days</td>
                                </tr>
                                <tr>
                                    <th class="left">Name</th>
                                    <td colspan="3">
                                        <?php echo $name = isset($query->name) ? $query->name : $all_record[0]->name ?>
                                        <input type="hidden" name="name" value="<?php echo $name; ?>" />
                                    </td>
                                    <td>
                                        <input type="text" id="joinnig_salary" name="monthly_ctc" value="<?php echo isset($query->joining_salary) ? $query->joining_salary : $all_record[0]->monthly_ctc; ?>"/>
                                    </td>
                                    <td>Monthly CTC</td>
                                </tr>
                                <tr>
                                    <th class="left">DESIGNATION</th>
                                    <td colspan="3">
                                        <input type="hidden" name="designation" value="<?php echo isset($designation_data->name) ? $designation_data->name : $all_record[0]->designation_fk; ?>"/>
                                        <?php echo isset($designation_data->name) ? $designation_data->name : $all_record[0]->designation_fk; ?>
                                    </td>
                                    <td><input type="text"></td>
                                    <td>Sunday Wages </td>
                                </tr>
                                <tr>
                                    <th class="left">DEPARTMENT</th>
                                    <td colspan="3">
                                        <input type="hidden" id="department" name="department" placeholder="Department" class="form-control" value="<?php echo isset($department_data->name) ? $department_data->name : $all_record[0]->department_fk; ?>"/>
                                        <?php echo isset($department_data->name) ? $department_data->name : $all_record[0]->department_fk; ?>
                                    </td>
                                    <td>
                                        <input type="hidden" name="days_in_month" value="<?php echo $today ?>"/>
                                        <?php echo $today ?></td>
                                    <td>days in a month</td>
                                </tr>
                                <tr>
                                    <th class="left">DATE OF JOINING</th>
                                    <td colspan="3">
                                        <input type="hidden" id="joining_date" name="joining_date" placeholder="" class="form-control" value="<?php echo isset($query->date_of_joining) ? $query->date_of_joining : $all_record[0]->joining_date; ?>"/>
                                        <?php echo isset($query->date_of_joining) ? $query->date_of_joining : $all_record[0]->joining_date ?>
                                    </td>
                                    <td>
                                        <input type="text" id="working_hr" name="working_hr" value="<?php echo $all_record[0]->working_hr; ?>"/>
                                    </td>
                                    <td>Working hours</td>
                                </tr>
                                <tr>
                                    <th></th>
                                    <td colspan="3"></td>
                                    <td><input type="text" id="ot" name="ot" value="<?php echo $all_record[0]->ot ?>"/></td>
                                    <td>OT hours</td>
                                </tr>
                                <tr>
    <!--                                    <th colspan="2"><center><u>EARNINGS</u></center></th>
                                <th colspan="2"><center><u>DEDUCTIONS</u></center></th>-->
                                    <td></td>
                                    <td></td>
                                    <th><center><u></u></center></th>
                                <th><center><u></u></center></th>
                                <td>
                                    <input type="text" id="festivals" name="festivals" value="<?php echo isset($festivals) ? $festivals : $all_record[0]->festivals; ?>"/>
                                </td>
                                <td>Festival Days</td>
                                </tr>


                                <tr>
    <!--                                    <th class="left bg"><u>SALARY HEAD</u></th>
                                <th class="bg"><u>AMOUNT (Rs.)</u></th>
                                <th class="left bg"><u>SALARY HEAD</u></th>
                                <th class="bg"><u>AMOUNT (Rs.)</u></th>-->
                                    <th class="left bg"><u>SALARY HEAD</u></th>
                                <th class="bg"><u>AMOUNT (Rs.)</u></th>
                                <th class="left bg" style="width:100px"><u>TYPE</u></th>
                                <td></td>
                                <td></td>
                                <td></td>
                                </tr>

                                <?php
                                if (isset($salary_structure_exist)) {
                                    foreach ($salary_structure_exist as $sal1) {
                                        //echo "<pre>"; print_r($sal1); exit;
                                        // if ($sal1['sal_type'] == 1) {
                                        ?>
                                        <tr>
                                            <td>
                                                <?php echo $salary_value1 = ($sal1['salary_name'] != "") ? $sal1['salary_name'] : "Other" ?>
                                                <input type="hidden" id="" name="salary_name[]" value="<?= $salary_value1 ?>"/>
                                                <input type="hidden" id="" name="salary_type[]" value="<?= $sal1['sal_type'] ?>"/>
                                            </td>
                                            <td>
                                                <input type="text" id="" name="salary_value[]" value="<?php echo $sal1['salary_value']; ?>" class="<?php
                                                if ($sal1['sal_type'] == 1) {
                                                    echo "plus1 calution";
                                                } else {
                                                    echo "minus1 calution";
                                                }
                                                ?>"/>
                                            </td>
                                            <td>
                                                <?php
                                                if ($sal1['sal_type'] == 1) {
                                                    echo "EARNING";
                                                } else {
                                                    echo "DEDUCTION";
                                                }
                                                ?>
                                            </td>
                                            <td></td><td></td> <td></td>
                                        </tr>
                                        <?php //} else {    ?>
            <!--                                            <tr>
                                            <td></td><td></td>
                                            <td>
                                        <?php //echo $salary_value1 = ($sal1['salary_name'] != "") ? $sal1['salary_name'] : "Other"    ?>
                                                <input type="hidden" id="" name="salary_name[]" value="<?php //echo $salary_value1                             ?>"/>
                                                <input type="hidden" id="" name="salary_type[]" value="<?php //echo $sal1['sal_type']                             ?>"/>
                                            </td>
                                            <td>
                                                <input type="text" id="" name="salary_value[]" value="<?php //echo $sal1['salary_value'];                             ?>"/>
                                            </td>
                                            <td></td>
                                            <td></td>
                                        </tr>                          -->
                                        <?php //}
                                        ?>
                                        <?php
                                    }
                                } else {
                                    if (isset($salary_structure_emp)) {
                                        foreach ($salary_structure_emp as $sal) {
                                            // if ($sal['plus_minus'] == 1) {
                                            ?>
                                            <tr>
                                                <td>
                                                    <?php echo $sal['salary_name'] ?>
                                                    <input type="hidden" id="" name="salary_name[]" value="<?= $sal['salary_name'] ?>"/>
                                                    <input type="hidden" id="" name="salary_type[]" value="<?= $sal['plus_minus'] ?>"/>
                                                </td>
                                                <td>
                                                    <input type="text" id="" name="salary_value[]" value="<?php echo $sal['salary_value']; ?>" class="<?php
                                                    if ($sal['plus_minus'] == 1) {
                                                        echo "plus1 calution";
                                                    } else {
                                                        echo "minus1 calution";
                                                    }
                                                    ?>"/>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($sal['plus_minus'] == 1) {
                                                        echo "EARNING";
                                                    } else {
                                                        echo "DEDUCTION";
                                                    }
                                                    ?>
                                                </td><td></td> <td></td><td></td>
                                            </tr>
                                            <?php
//                                            } else {
//                                                if ($sal['salary_name'] != "") {
//                                                    
                                            ?>
                <!--                                                    <tr>
                                                    <td></td><td></td>
                                                    <td>
                                            <?php //echo $sal['salary_name'];  ?>
                                                        <input type="hidden" id="" name="salary_name[]" value="//<?php //echo $sal['salary_name']                         ?>"/>
                                                        <input type="hidden" id="" name="salary_type[]" value="//<?php //echo $sal['plus_minus']                         ?>"/>
                                                    </td>
                                                    <td>
                                                        <input type="text" id="" name="salary_value[]" value="//<?php //echo $sal['salary_value'];                         ?>" class="minus1"/>
                                                    </td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>-->
                                            <?php
//                                                }
//                                            }
                                        }
                                    }
                                }
                                ?>

                                <tr>
                                    <th class="left">TOTAL EARNING</th>
                                    <td><input type="text" readonly id="plus_sum" name="salary_ctc_pm" value ="<?php
                                        if (isset($all_record[0]->salary_ctc_pm)) {
                                            echo $all_record[0]->salary_ctc_pm;
                                        }
                                        ?>"></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th class="left">TOTAL DEDUCTION</th>
                                    <td><input type="text" readonly id="minus_sum" name="total_deduction" value ="<?php
                                        if (isset($all_record[0]->total_deduction)) {
                                            echo $all_record[0]->total_deduction;
                                        }
                                        ?>"></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th class="left">SALARY (CTC) / PM</th>
                                    <td><input type="text" readonly id="salary_other" name="salary_other" value ="<?php
                                        if (isset($all_record[0]->salary_other)) {
                                            echo $all_record[0]->salary_other;
                                        }
                                        ?>"></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td style="padding:10px"></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th class="left">Net Take Home</th>
                                    <td><input type="text" readonly id="net_take_home" name="net_take_home" value ="<?php
                                        if (isset($all_record[0]->net_take_home)) {
                                            echo $all_record[0]->net_take_home;
                                        }
                                        ?>"></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </table>

                            <input type="hidden" name="month_data" value="<?= $_GET['month'] ?>"/>
                            <input type="hidden" name="employee_data" value="<?= $_GET['employee'] ?>"/>
                            <input type="hidden" name="year_data" value="<?= date('Y') ?>"/>
                            <br/><br/>
                            <input type="submit" value="Save" class="btn btn-primary btn-md" style="width:70px">

                            </form>
                        <?php } ?>
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


<form role="form" id="offer_wtlh" action="<?php echo base_url(); ?>hrm/employee/pdf_salary_slip/?employee=<?php echo $_GET['employee'] ?>&month=<?php echo $_GET['month'] ?>&current_year=<?php echo $_GET['current_year'] ?>" method="post" enctype="multipart/form-data">
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
                    <input type="hidden" name="emp_offer_id" id="emp_offer_id">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</form>


<script>

function checkcal(){
        var total_plus = 0;
        var total_minus = 0;

        $(".plus1").each(function (index, element) {
            total_plus = parseFloat(total_plus) + parseFloat(element.value);
        });

        $(".minus1").each(function (index, element) {
            total_minus = parseFloat(total_minus) + parseFloat(element.value);
        });

        $("#plus_sum").val(total_plus);
        $("#minus_sum").val(total_minus);
        $("#net_take_home").val(total_plus);
        $("#salary_other").val(total_plus + total_minus);
	}
<?php if (!isset($all_record[0])) { ?>  checkcal();	<?php } ?>
    $(function () {
        setTimeout(function () {
            $(".alert").hide('blind', {}, 500)
        }, 5000);
    });

    function fun_wtlh() {
        $("#wtlh_model").modal("show");
    }

    $("#submit_off_wtlh").click(function () {
        setTimeout(function () {
            $('#wtlh_model').modal('hide')
        }, 2000);
    });
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
		 $(document).on('keyup', '.calution', function () {

                                                    checkcal();

                                                });	
</script>
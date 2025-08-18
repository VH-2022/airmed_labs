<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Employee<small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>hrm/admin/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><i class="fa fa-users"></i> Employee</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-primary" style="border-color:#bf2d37;">
                    <div class="panel-heading" style="background-color:#bf2d37;">
                        <h3 class="panel-title">Employee List</h3>

                    </div><!-- /.box-header -->
                    <div class="panel-body">
                        <a style="float:right;" href='<?php echo base_url(); ?>hrm/employee/add' class="btn btn-primary btn-sm" ><i class="fa fa-plus-circle" ></i> <strong> Add</strong></a>
                        <?php $attributes = array('class' => 'form-horizontal', 'method' => 'get', 'role' => 'form'); ?>
                        <?php echo form_open('hrm/employee/index', $attributes); ?>
                        <div class="col-md-3">
<!--                            <input type="text" class="form-control" name="search" placeholder="search" value="<?php
//                            if (isset($search) != NULL) {
//                                echo $search;
//                            }
                            ?>" />-->
                        </div>
<!--                        <input type="submit" value="Search" class="btn btn-primary btn-md">-->
                        <br>
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
                        <br> 
                        <div class="tableclass">
                            <table id="example4" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Sr. No.</th>
                                        <th>Employee-ID</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Dept/Designation</th>
                                        <th>City</th>
<!--                                        <th>At Work</th>-->
                                        <th>Branch</th>
                                        <th>Phone</th>
                                        <th>Date Of Joining</th>
										<th>Date Of Leaving</th>
                                        <th>Status</th>
                                        <th>Documents</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>

                                        </td>
                                        <td>
                                            <?php echo form_input(['name' => 'employee_id', 'class' => 'form-control', 'placeholder' => 'Enter Employee ID', 'value' => $employee_id]); ?>
                                        </td>
                                        <td>

                                        </td>
                                        <td>
                                            <?php echo form_input(['name' => 'e_name', 'class' => 'form-control', 'placeholder' => 'Enter Name', 'value' => $e_name]); ?>
                                        </td>
                                        <td>

                                        </td>
                                        <td>
                                            <select class="form-control" name="city" id="city">
                                                <option value="">--All city--</option>
                                                <?php foreach ($test_city as $key) { ?>
                                                    <option value="<?= $key->id ?>" <?php
                                                    if ($_GET["city"] == $key->id) {
                                                        echo "selected";
                                                    }
                                                    ?>><?= $key->name ?></option>
                                                        <?php } ?>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control" name="branch" id="branch">
                                                <option value="">--Select Branch--</option>
                                                <?php
                                                if ($city != "") {
                                                    if (!empty($branch_data)) {
                                                        foreach ($branch_data as $b) {
                                                            ?>
                                                            <option value="<?= $b->id ?>" <?php
                                                            if ($branch == $b->id) {
                                                                echo "selected";
                                                            }
                                                            ?>><?= $b->branch_name ?></option>
                                                                    <?php
                                                                }
                                                            }
                                                        }
                                                        ?>
                                            </select>
                                        </td>

                                        <td>
                                            <?php echo form_input(['name' => 'phone', 'class' => 'form-control', 'placeholder' => 'Enter Phone', 'value' => $phone]); ?>
                                        </td>
                                        <td>

                                        </td>
										<td>

                                        </td>
                                        <td>
                                            <select class="form-control chosen-select" tabindex="-1"  data-placeholder="Select status" name="status">
                                                <option value="">Select status</option>
                                                <option value="1" <?php
                                                if ($_GET['status'] == '1') {
                                                    echo "selected";
                                                }
                                                ?>>Active</option>
                                                <option value="2" <?php
                                                if ($_GET['status'] == '2') {
                                                    echo "selected";
                                                }
                                                ?>>Deactive</option>
                                                <option value="0" <?php
                                                if ($_GET['status'] == '0') {
                                                    echo "selected";
                                                }
                                                ?>>Pending</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="submit" name="search" class="btn btn-sm btn-success" value="Search" />
                                        </td>
                                        <td>
                                            <a href="<?= base_url() ?>hrm/Employee/export_csv?employee_id=<?= $_GET["employee_id"] ?>&e_name=<?= $_GET["e_name"] ?>&city=<?= $_GET["city"] ?>&phone=<?= $_GET["phone"] ?>&status=<?= $_GET["status"] ?>&branch=<?= $_GET["branch"] ?>" class="btn btn-primary btn-sm">Export CSV</a>
                                        </td>
                                    </tr>
                                    </form>

                                    <?php
                                    $cnt = $counts;
                                    $cnt1 = 0;
                                    $d_diff = 0;
                                    foreach ($query as $row) {
                                        //echo "<pre>"; print_r($row); exit;
                                        $cnt++;
                                        $cnt1++;
                                        $row_color = '';
                                        $date_p = explode("/", $row->date_of_joining);
                                        $newDate = $date_p[2] . "-" . $date_p[1] . "-" . $date_p[0];
                                        $date1 = date_create($newDate);
                                        $date2 = date_create(date("Y-m-d"));
                                        if (!empty($date1) && !empty($date2)) {
                                            $diff = date_diff($date1, $date2);
                                            $d_diff = $diff->format("%a");

                                            if ($d_diff > 150 && $row->is_profassion == 1) {
                                                $row_color = ' style="background-color: #FFF1BC;"';
                                            }
                                            if ($d_diff > 180 && $row->is_profassion == 1) {
                                                $row_color = ' style="background-color: #FFBDBC;"';
                                            }
                                        }
                                        ?>
                                        <tr <?= $row_color; ?>>
                                            <td><?= $cnt1 ?></td>
                                            <td><?php echo $row->employee_id; ?></td>
                                            <td><?php if ($row->photo != '' && file_exists(base_url() . "upload/employee/" . $row->photo)) { ?><img src="<?php echo base_url(); ?>upload/employee/<?php echo $row->photo; ?>" alt="ProfileImage" height="70px"> <?php } else { ?><img src="<?php echo base_url(); ?>upload/employee/default_avatar.jpg" height="70px"><?php } ?></td>
                                            <td><?php echo ucwords($row->name); ?></td>
                                            <td><span>Department: <b><?php echo ucwords($row->department); ?></b></span><br><span>Designation: <b><?php echo ucwords($row->designation); ?></b></span></td>
                                            <td><?= $row->city_name; ?></td>
                                            <td><?php
//                                                $start = $row->date_of_joining;
//                                                if ($start != "") {
////                                                    $expirationDate = strtotime($start);
////                                                    $toDay = strtotime(date('d-m-Y'));
////                                                    $difference = abs($toDay - $expirationDate);
////                                                    //echo $difference; exit;
////                                                    $years = floor($difference / 31207680);
////                                                    $months = floor(($difference - $years * 31207680) / 2600640);
////                                                    $days = floor(($difference - $years * 31207680 - $months * 2600640) / 86400);
////                                                    echo $years . " year " . $months . " month " . $days . " day";
//                                                    //echo date('Y-m-d', strtotime($start));
//
//                                                    $datetime2 = new DateTime(date('d-m-Y', strtotime($start)));
//                                                    //print_r($datetime2);
//                                                    $datetime1 = new DateTime(date('d-m-Y'));
//                                                    //print_r($datetime1);
//                                                    $interval = $datetime1->diff($datetime2);
//                                                    //echo $interval->format('%y years %m months and %d days');
//                                                } else {
//                                                    echo "-";
//                                                };
                                                echo $row->Branch_name;
                                                ?></td>
                                            <td><?php
                                                if ($row->phone != "") {
                                                    echo $row->phone;
                                                } else {
                                                    echo "-";
                                                };
                                                ?></td>
                                            <td><?= $row->date_of_joining ?></td>
											<td><?= $row->date_of_leaving ?></td>
                                            <td>
                                                <?php if ($row->status == '0') { ?> <span class="label label-danger">Pending</span><?php } else if ($row->status == '1') { ?><span class="label label-success">Active</span><?php } else if ($row->status == '2') { ?><span class="label label-warning">Deactive</span><?php } ?>
                                                <?php if ($row->is_profassion == 1) { ?><span class="label label-primary">Under probation period.</span><?php } ?>
                                            </td>
                                            <td>
                                                <?php if ($row->status != '2') { ?>
                                                                                                                                                                                                                <!--<a href='<?php //echo base_url();                                                                                     ?>hrm/employee/pdf_offer_letter/<?php //echo $row->id;                                                                                                    ?>' data-toggle="tooltip" data-original-title="Download Offer Letter" onclick="fun_wtlh()"><i class="fa fa-print"></i></a>-->
                                                    <a href='javascript:void(0);' data-toggle="tooltip" data-original-title="Download Offer Letter" onclick="fun_wtlh('<?php echo $row->id; ?>')"><i class="fa fa-print"></i></a>
                                                    <a href='<?php echo base_url(); ?>hrm/employee/send_offer_letter/<?php echo $row->id; ?>' name="send_offer_letter" class="btn-xs" data-toggle="tooltip" data-original-title="Send offer Letter"><i class="fa fa-send-o"></i></a>
                                                    <br/>
                                                    <?php if ($row->status == '1') { ?>
                                                                                                                                                                                                                    <!--<a href='<?php //echo base_url();                                                                                      ?>hrm/employee/pdf_joining_letter/<?php //echo $row->id;                                                                                              ?>' data-toggle="tooltip" data-original-title="Download Joining Letter"><i class="fa fa-print"></i></a>-->
                                                        <a href='javascript:void(0);' data-toggle="tooltip" data-original-title="Download Join Letter" onclick="fun_wtlh1('<?php echo $row->id; ?>')"><i class="fa fa-print"></i></a>
                                                        <a href='<?php echo base_url(); ?>hrm/employee/send_join_letter/<?php echo $row->id; ?>' name="send_join_letter" class="btn-xs" data-toggle="tooltip" data-original-title="Send joining Letter"><i class="fa fa-send-o"></i></a>
                                                        <?php
                                                    }
                                                } else {
                                                    if ($row->email != "") {
                                                        ?>
            <!--                                                        <a href='<?php //echo base_url();                                                                               ?>hrm/employee/pdf_relevent_letter/<?php echo $row->id; ?>' data-toggle="tooltip" data-original-title="Download Relevant Letter"><i class="fa fa-print"></i></a>-->
                                                        <a href='javascript:void(0);' data-toggle="tooltip" data-original-title="Download Relevant Letter" onclick="fun_wtlh2('<?php echo $row->id; ?>')"><i class="fa fa-print"></i></a>
                                                        <a href='<?php echo base_url(); ?>hrm/employee/send_relevent_letter/<?php echo $row->id; ?>' name="send_offer_letter" class="btn-xs" data-toggle="tooltip" data-original-title="Send Relevant Letter"><i class="fa fa-send-o"></i></a>
                                                        <?php
                                                    } else {
                                                        echo "Email is required to send relevant letter.";
                                                    }
                                                }
                                                ?>       
                                            </td>
                                            <td><a href='<?php echo base_url(); ?>hrm/employee/edit/<?php echo $row->id; ?>' data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-edit"></i></a>
                                                <a href='<?php echo base_url(); ?>hrm/employee/delete/<?php echo $row->id; ?>' data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure you want to remove this data?');"><i class="fa fa-trash-o"></i></a>
                                                <?php if ($row->is_profassion == 1) { ?><a href='<?php echo base_url(); ?>hrm/employee/delete1/<?php echo $row->id; ?>' data-toggle="tooltip" data-original-title="Remove probation period" onclick="return confirm('Are you sure?');"><i class="fa fa-check"></i></a><?php } ?>
                                            </td>
                                        </tr>
                                    <?php }if (empty($query)) {
                                        ?>
                                        <tr>
                                            <td colspan="8">No records found</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
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


<form role="form" id="offer_wtlh" action="<?php echo base_url(); ?>hrm/employee/pdf_offer_letter/" method="post" enctype="multipart/form-data">
    <div class="modal fade" id="wtlh_model" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Offer Letter</h4>
                </div>
                <div class="modal-body">
                    <table id="example4_edit" class="table table-bordered table-striped">

                        <div class="form-group col-sm-12  pdng_0">
                            <div class="col-sm-9 pdng_0">
                                <select class="form-control" id="download_type" name="download_type">
                                    <option value="1">PDF</option>
                                    <option value="2">Word Document</option>
                                </select>
                            </div>
                        </div>
                        <div id="with_wiot_lt">
                            Download : &nbsp;&nbsp;
                            <input type="radio" name="letter_head" value="1" checked> With Letter Head &nbsp;
                            <input type="radio" name="letter_head" value="0"> Without Letter Head<br>
                        </div>
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


<form role="form" id="offer_wtlh1" action="<?php echo base_url(); ?>hrm/employee/pdf_joining_letter/" method="post" enctype="multipart/form-data">
    <div class="modal fade" id="wtlh_model1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Join Letter</h4>
                </div>
                <div class="modal-body">
                    <table id="example4_edit" class="table table-bordered table-striped">
                        <div class="form-group col-sm-12  pdng_0">
                            <div class="col-sm-9 pdng_0">
                                <select class="form-control" id="download_type1" name="download_type1">
                                    <option value="1">PDF</option>
                                    <option value="2">Word Document</option>
                                </select>
                            </div>
                        </div>
                        <div id="with_wiot_lt1">
                            Download : &nbsp;&nbsp;
                            <input type="radio" name="letter_head1" value="1" checked> With Letter Head &nbsp;
                            <input type="radio" name="letter_head1" value="0"> Without Letter Head<br>
                        </div>
                    </table>
                </div>
                <div class="modal-footer">
                    <input class="btn btn-primary" type="submit" value="Download" id="submit_off_wtlh1">
                    <input type="hidden" name="emp_offer_id1" id="emp_offer_id1">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</form>


<form role="form" id="offer_wtlh1" action="<?php echo base_url(); ?>hrm/employee/pdf_relevent_letter/" method="post" enctype="multipart/form-data">
    <div class="modal fade" id="wtlh_model2" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Relevant Letter</h4>
                </div>
                <div class="modal-body">
                    <table id="example4_edit" class="table table-bordered table-striped">
                        Download : &nbsp;&nbsp;
                        <input type="radio" name="letter_head2" value="1" checked> With Letter Head &nbsp;
                        <input type="radio" name="letter_head2" value="0"> Without Letter Head<br>
                    </table>
                </div>
                <div class="modal-footer">
                    <input class="btn btn-primary" type="submit" value="Download" id="submit_off_wtlh2">
                    <input type="hidden" name="emp_offer_id2" id="emp_offer_id2">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</form>


<script>
    $(function () {
        setTimeout(function () {
            $(".alert").hide('blind', {}, 500)
        }, 5000);
    });

    function fun_wtlh(id) {
        $("#emp_offer_id").val(id);
        $("#wtlh_model").modal("show");
    }

    $("#submit_off_wtlh").click(function () {
        setTimeout(function () {
            $('#wtlh_model').modal('hide')
        }, 2000);
    });

    function fun_wtlh1(id) {
        $("#emp_offer_id1").val(id);
        $("#wtlh_model1").modal("show");
    }

    $("#submit_off_wtlh1").click(function () {
        setTimeout(function () {
            $('#wtlh_model1').modal('hide')
        }, 2000);
    });


    function fun_wtlh2(id) {
        $("#emp_offer_id2").val(id);
        $("#wtlh_model2").modal("show");
    }

    $("#submit_off_wtlh2").click(function () {
        setTimeout(function () {
            $('#wtlh_model2').modal('hide')
        }, 2000);
    });

    $('#city').change(function () {
        var url = "<?php echo base_url(); ?>hrm/employee/get_branch";
        var value = $("#city").val();
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {"id": value},
            success: function (response)
            {
                var $el = $("#branch");
                $el.empty();
                $el.append($("<option></option>")
                        .attr("value", '').text('Select Branch'));

                $.each(response, function (index, data) {
                    $('#branch').append('<option value="' + data['id'] + '">' + data['branch_name'] + '</option>');
                });
                $('#branch').trigger("chosen:updated");
                $('#branch').trigger("listz:updated");
            }
        });
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
        jQuery("#branch").chosen();
    });
    $('#download_type').change(function () {
        var type = $('#download_type').val();
        if (type == '2') {
            $('#with_wiot_lt').hide();
        }else{
            $('#with_wiot_lt').show();
        }
    });

    $('#download_type1').change(function () {
        var type = $('#download_type1').val();
        if (type == '2') {
            $('#with_wiot_lt1').hide();
        }else{
            $('#with_wiot_lt1').show();
        }
    });

</script>
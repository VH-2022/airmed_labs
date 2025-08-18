<style>
    .custom-chart {
        max-width: calc(100vw - 200px);
        overflow: auto;
        max-height: 450px
    }

    .custom-table-striped {
        min-width: 1500px;
    }
</style>

<section class="content-header">
    <h1>
        Dashboard
    </h1>

    <ol class="breadcrumb">
        <li><a href=""><i class="fa fa-dashboard"></i>Home</a></li>
        <li class="active">Dashboard</li>
    </ol>
</section>
<?php if ($login_data["type"] == 1 || $login_data["type"] == 5 || $login_data["type"] == 6 || $login_data["type"] == 7 || $login_data["type"] == 2) { ?>
    <section class="content">
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-olive">
                    <span class="info-box-icon"><i class="fa fa-money"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Today's Total Revenue</span>
                        <span class="info-box-number">Rs. <?php
                                                            if ($total_revenue[0]['revenue'] == "") {
                                                                echo "0";
                                                            } else {
                                                                echo number_format((float) $total_revenue[0]['revenue'], 2, '.', '');
                                                            }
                                                            ?></span>
                        <div class="progress">
                            <div style="width: 100%" class="progress-bar"></div>
                        </div>
                        <a href="#" style="color:white;"><span class="progress-description">
                                View More
                            </span></a>
                    </div><!-- /.info-box-content -->
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-yellow">
                    <span class="info-box-icon"><i class="fa fa-money"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Today's Total Due Amount</span>
                        <span class="info-box-number">Rs. <?php
                                                            if ($total_due_amount[0]['due_amount'] == "") {
                                                                echo "0";
                                                            } else {
                                                                echo number_format((float) $total_due_amount[0]['due_amount'], 2, '.', '');
                                                            }
                                                            ?></span>
                        <div class="progress">
                            <div style="width: 100%" class="progress-bar"></div>
                        </div>
                        <a href="#" style="color:white;"><span class="progress-description">
                                View More
                            </span></a>
                    </div><!-- /.info-box-content -->
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-teal">
                    <span class="info-box-icon"><i class="fa fa-eyedropper"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Today's Total Samples</span>
                        <span class="info-box-number"><?php echo $total_sample[0]['test']; ?></span>
                        <div class="progress">
                            <div style="width: 100%" class="progress-bar"></div>
                        </div>
                        <a href="#" style="color:white;"><span class="progress-description">
                                View More
                            </span></a>
                    </div><!-- /.info-box-content -->
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-olive">
                    <span class="info-box-icon"><i class="fa fa-flask"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Today's Total Test</span>
                        <span class="info-box-number"><?php echo $total_test[0]['test']; ?></span>
                        <div class="progress">
                            <div style="width: 100%" class="progress-bar"></div>
                        </div>
                        <a href="#" style="color:white;"><span class="progress-description">
                                View More
                            </span></a>
                    </div><!-- /.info-box-content -->
                </div>
            </div>
        </div>
        <!-- /.row -->
    </section>
    <?php if ($login_data["type"] == 5) { ?>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-6">
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title">Collected Amount (<?php if (!empty($date4)) {
                                                                            echo $date4;
                                                                        } else {
                                                                            echo date("Y-m-d");
                                                                        } ?>)</h3><br>

                                <div class="box-tools pull-right">
                                    <input type="text" name="date4" value="<?php if (!empty($date4)) {
                                                                                echo $date4;
                                                                            } else {
                                                                                echo date("d/m/Y");
                                                                            } ?>" onchange="get_date_collect(this.value);" id="collection_date" class="from-control datepicker-input" />
                                </div>
                            </div>
                            <div class="box-body">
                                <div class="chart">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Branch</th>
                                                <th>Date</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($collecting_amount_branch as $am_br) { ?>
                                                <tr>
                                                    <td><?= ucfirst($am_br["name"]) ?></td>
                                                    <td><?php echo $am_br['branch_name']; ?></td>
                                                    <td><?php if (!empty($date4)) {
                                                            echo $date4;
                                                        } else {
                                                            echo date("d/m/Y");
                                                        } ?></td>
                                                    <td>Rs.<?= ucfirst($am_br["SUM"]) ?></td>
                                                    <td><?php if ($am_br["admin_fk"] == '') { ?><span class="label label-warning ">Pending</span><?php } else { ?><span class="label label-success">Collected</span><?php } ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if (empty($collecting_amount_branch)) { ?>
                                                <tr>
                                                    <td colspan="5">
                                                        <center>Data not available.</center>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div><!-- /.box-body -->
                        </div><!-- /.box -->
                    </div>
                    <script>
                        function get_date_collect(val) {
                            if (val != '') {
                                window.location.href = "<?php echo base_url(); ?>Dashboard?date4=" + val;
                            }
                        }
                    </script>
                    <div class="col-md-6">
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title">Total Amount (<?php if (!empty($date5)) {
                                                                        echo $date5;
                                                                    } else {
                                                                        echo date("Y-m-d");
                                                                    } ?>)</h3><br>

                                <div class="box-tools pull-right">
                                    <input type="text" name="date5" value="<?php if (!empty($date5)) {
                                                                                echo $date5;
                                                                            } else {
                                                                                echo date("d/m/Y");
                                                                            } ?>" onchange="get_date_total(this.value);" id="collection_date" class="from-control datepicker-input" />
                                </div>
                            </div>
                            <div class="box-body">
                                <div class="chart">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Branch</th>
                                                <th>Date</th>
                                                <th>Type</th>
                                                <th>Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($total_amount_branch as $total) { ?>
                                                <tr>
                                                    <td><?= ucfirst($total["name"]) ?></td>
                                                    <td><?php echo $total['branch_name']; ?></td>
                                                    <td><?php if (!empty($date5)) {
                                                            echo $date5;
                                                        } else {
                                                            echo date("d/m/Y");
                                                        } ?></td>
                                                    <td><?php echo $total['type']; ?></td>
                                                    <td>Rs.<?= ucfirst($total["SUM"]) ?></td>

                                                </tr>
                                            <?php } ?>
                                            <?php if (empty($total_amount_branch)) { ?>
                                                <tr>
                                                    <td colspan="5">
                                                        <center>Data not available.</center>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div><!-- /.box-body -->
                        </div><!-- /.box -->
                    </div>
                    <script>
                        function get_date_total(val) {
                            if (val != '') {
                                window.location.href = "<?php echo base_url(); ?>Dashboard?date5=" + val;
                            }
                        }
                    </script>
                </div>
            </div>
        </section>
    <?php } ?>


    <script type="text/javascript">
        <?php
        $flag = 0;
        $status = 0;

        if ($login_data['type'] == "6" && $login_data['branch_fk'][0]['branch_type_fk'] == '6') {
            $curdate1 = date("Y-m");
            $current_month = date('m');
            $current_year = date('Y');
            $current_date = date('d');
            $prev_month = $current_month - 1;
            $prev_month = '0' . $prev_month;

            $days = array("01", "02", "03", "04", "05", "06", "07");
            $payamount1 = 0;

            $payment_status = 0;
            foreach ($data2["get_branch_report"] as $key) {
                $month = $key['month'];
                $temp = explode('-', $key['month']);
                if ($prev_month == $temp[1] && $current_year == $temp[0]) {
                    if ($key['paidamount'] == "") {
                        $payment_status = 1;
                        $paidtoairmeddetails = $this->Airmed_tech_report_model->get_val("Select * from tech_revenue_collection_master where branch_fk='" . $key["branch_fk"] . "' AND status=1");
                        $paidtoairmedlogdetails = $this->Airmed_tech_report_model->get_val("Select * from tech_revenue_collection_master_log where branch_fk='" . $key["branch_fk"] . "' AND techrevenue_id='" . $paidtoairmeddetails[0]['id'] . "' AND DATE_FORMAT(updated_date, '%Y-%m')<='" . $month . "' ORDER BY updated_date DESC Limit 1");
                        if (!empty($paidtoairmedlogdetails)) {
                            $paidtoairmeddetails = $paidtoairmedlogdetails;
                        } else {
                            $paidtoairmeddetails[0]["collection_type"] = 2;
                            $paidtoairmeddetails[0]["collection_value"] = 7.00;
                        }
                        if ($paidtoairmeddetails[0]["collection_type"] == 1) {
                            $key["paidtoairmed"] = $paidtoairmeddetails[0]["collection_value"];
                        } else {
                            $key["paidtoairmed"] = ($key["total_revenue"] * $paidtoairmeddetails[0]["collection_value"]) / 100;
                        }
                        $payamount1 = round($key["paidtoairmed"] - $key['paidamount']);
                        $due_month = $key['date'];
                    }
                }
            }

            if ($payment_status == '1' && in_array($current_date, $days)) {
        ?>
                $(window).on('load', function() {
                    $('#due_payment_model').modal('show');
                });
        <?php
            }
        }
        ?>
    </script>


    <div class="modal fade" id="due_payment_model" role="dialog">
        <div class="modal-dialog">
            Modal content
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Monthly Fee Payment</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        Dear Airmed Customer, <br /><br />
                        <p><span>This is a payment reminder. We request you to pay the amount Rs. <b><?= $payamount1 ?></b> /- for the month of <b><?= $due_month ?></b>.</span></p>
                        <br />
                        Thanks
                    </div>

                </div>
                <div class="modal-footer">
                    <?php /* if (0 < $payamount1 && strtotime($curdate1) > strtotime($month)) { ?>
                  <a href="<?= base_url() . "Airmed_tech_report/payumoney?bid=" . $data2['bid'] . "&month=" . $month; ?>" target="_blank" class="btn btn-sm btn-success">Pay</a>
                  <?php } */ ?>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>



    <?php if ($login_data["type"] == 1 || $login_data["type"] == 2) { ?>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <?php /*  <div class="col-md-6">
              <!-- AREA CHART -->
              <div class="box box-primary">
              <div class="box-header with-border">
              <h3 class="box-title">Sample and Test Chart (Last week)</h3><br><br>
              <div style="width:20%;float:left;"><div style="height: 13px;width: 13px;background-color: #D2D6DE;float: left;margin:5px 5px 5px 5px;"></div>Total Test</div>
              <div style="width:20%;float:left;"><div style="height: 13px;width: 13px;background-color: #4B94BF;float: left;margin:5px 5px 5px 5px;"></div>Total Sample</div>
              <div class="box-tools pull-right">
              <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
              <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
              </div>
              <div class="box-body">
              <div class="chart">
              <canvas id="areaChart" height="250"></canvas>
              </div>
              </div><!-- /.box-body -->
              </div><!-- /.box -->
              </div> */ ?>
                    <!-- DONUT CHART -->

                    <!-- LINE CHART -->
                    <?php /* <div class="col-md-6">
              <div class="box box-info">
              <div class="box-header with-border">
              <h3 class="box-title">Revenue Chart (Last week)</h3><br><br>
              <div style="width:20%;float:left;"><div style="height: 13px;width: 13px;background-color: #D2D6DE;float: left;margin:5px 5px 5px 5px;"></div>Total Revenue</div>
              <div class="box-tools pull-right">
              <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
              <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
              </div>
              <div class="box-body">
              <div class="chart">
              <canvas id="lineChart" height="250"></canvas>
              </div>
              </div><!-- /.box-body -->
              </div><!-- /.box -->
              </div> */ ?>
                    <div class="col-md-6">
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title">Revenue Chart (<?php
                                                                        if ($date == 0) {
                                                                            echo "Today";
                                                                        }
                                                                        if ($date == '-1') {
                                                                            echo "YesterDay";
                                                                        }
                                                                        if ($date == -2) {
                                                                            echo "Last Two Day";
                                                                        }
                                                                        if ($date == -7) {
                                                                            echo "Last Week";
                                                                        }
                                                                        if ($date == -30) {
                                                                            echo "Last Month";
                                                                        }
                                                                        ?>)</h3><br>

                                <div class="box-tools pull-right">
                                    <div class="btn-group">
                                        <a class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
                                            Action
                                            <span class="caret"></span>
                                        </a>
                                        <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
                                            <li><a tabindex="-1" href="<?= base_url(); ?>/Dashboard?date=0">Today</a></li>
                                            <li><a tabindex="-1" href="<?= base_url(); ?>/Dashboard?date=-1">Last Two Days</a></li>
                                            <li><a tabindex="-1" href="<?= base_url(); ?>/Dashboard?date=-7">Last Seven Days</a></li>
                                            <li><a tabindex="-1" href="<?= base_url(); ?>/Dashboard?date=-30">Last Month</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="box-body">
                                <div class="chart">
                                    <div id="columnchart_material" style="width: 610px; height: 270px;"></div>
                                </div>
                            </div><!-- /.box-body -->
                        </div><!-- /.box -->
                    </div>

                    <div class="col-md-6">
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title">Job Status Chart (<?php
                                                                        if ($date1 == 0) {
                                                                            echo "Today";
                                                                        }
                                                                        if ($date1 == '-1') {
                                                                            echo "YesterDay";
                                                                        }
                                                                        if ($date1 == -2) {
                                                                            echo "Last Two Day";
                                                                        }
                                                                        if ($date1 == -7) {
                                                                            echo "Last Week";
                                                                        }
                                                                        if ($date1 == -30) {
                                                                            echo "Last Month";
                                                                        }
                                                                        ?>)</h3><br>

                                <div class="box-tools pull-right">
                                    <div class="btn-group">
                                        <a class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
                                            Action
                                            <span class="caret"></span>
                                        </a>
                                        <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
                                            <li><a tabindex="-1" href="<?= base_url(); ?>/Dashboard?j_date=0">Today</a></li>
                                            <li><a tabindex="-1" href="<?= base_url(); ?>/Dashboard?j_date=-1">Last Two Days</a></li>
                                            <li><a tabindex="-1" href="<?= base_url(); ?>/Dashboard?j_date=-7">Last Seven Days</a></li>
                                            <li><a tabindex="-1" href="<?= base_url(); ?>/Dashboard?j_date=-30">Last Month</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="box-body">
                                <div class="chart">
                                    <div id="chart_div" style="width: 610px; height: 270px;"></div>
                                </div>
                            </div><!-- /.box-body -->
                        </div><!-- /.box -->
                    </div>

                    <div class="col-md-6">
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title">Collected Amount (<?php if (!empty($date2)) {
                                                                            echo $date2;
                                                                        } else {
                                                                            echo date("Y-m-d");
                                                                        } ?>)</h3><br>

                                <div class="box-tools pull-right">
                                    <input type="text" name="collect_date" readonly="" value="<?php if (!empty($date2)) {
                                                                                                    echo $date2;
                                                                                                } else {
                                                                                                    echo date("d/m/Y");
                                                                                                } ?>" onchange="get_date(this.value);" id="collection_date" class="from-control datepicker-input" />
                                </div>
                            </div>
                            <div class="box-body">
                                <div class="chart">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Date</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($collecting_amount as $key) { ?>
                                                <tr>
                                                    <td><?= ucfirst($key["name"]) ?></td>
                                                    <td><?php if (!empty($date2)) {
                                                            echo $date2;
                                                        } else {
                                                            echo date("d/m/Y");
                                                        } ?></td>
                                                    <td>Rs.<?= ucfirst($key["SUM"]) ?></td>
                                                    <td><?php if ($key["admin_fk"] == '') { ?><span class="label label-warning ">Pending</span><?php } else { ?><span class="label label-success">Collected</span><?php } ?></td>
                                                    <td><?php if ($key["admin_fk"] == '') { ?><a href="<?= base_url(); ?>Admin/collect_amount?date=<?= $date2 ?>&amount=<?= ucfirst($key["sum"]) ?>&user=<?= $key["user_fk"] ?>" onclick="return confirm('Are you sure?');"><i class="fa fa-check"></i></a><?php } ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if (empty($collecting_amount)) { ?>
                                                <tr>
                                                    <td colspan="5">
                                                        <center>Data not available.</center>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div><!-- /.box-body -->
                        </div><!-- /.box -->
                    </div>

                  
                    <div class="col-md-6">
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title">Outsource </h3><br>
                                <a style="float:right;margin-left:3px" href='<?= base_url(); ?>Admin/export_csv_outsource' id="job_search_btn" class="btn btn-sm btn-primary" ><i class="fa fa-download" ></i><strong > Export To CSV</strong></a>
                            </div>
                            <div class="box-body">
                                <div class="chart custom-chart">
                                    <table class="table table-striped custom-table-striped" id="city_wiae_price">
                                        <thead>
                                            <tr>
                                                <th>Sr. No.</th>
                                                <th>Reg. No</th>
                                                <th>Order Id</th>
                                                <th>Customer Name</th>
                                                <th>Doctor</th>
                                                <th>Test/Package Name</th>
                                                <th>Date</th>
                                                <th>Payable Amount / Price</th>
                                                <th>Job Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="t_body">
                                            <?php
                                            $i = 0;
                                            $temp = [];
                                            $new_array = array();
                                            foreach ($outsourceData as $row) {
                                                $i++;
                                            ?>
                                                <tr>
                                                    <td><?= $i; ?></td>
                                                    <td> <a href="<?php echo base_url(); ?>job-master/job-details/<?php echo $row['id']; ?>"> <?= $row["id"]; ?>
                                                    </a><br>Barcode - <?= $row["barcode"] ?></td>
                                                    <td style="color:#d73925;">
                                                        <?php
                                                        echo $row["order_id"];
                                                        ?>
                                                    </td>
                                                    <?php
                                                    if ($row['relation'] == '') {
                                                        $row['relation'] = 'Self';
                                                    }
                                                    ?>
                                                    <td>
                                                        <?php if ($row['relation'] == 'Self') { ?>
                                                            <span style="color:#d73925;"><?php echo ucwords($row['full_name']); ?></span> <?php echo "(" . $row['age'] . " " . $row['age_type'] . "/" . $row['gender'] . ")"; ?>
                                                            &nbsp;<?= $row['mobile'] ?>
                                                        <?php } else { ?>
                                                            <span style="color:#d73925;"><?= ucfirst($row['relation']); ?></span> <?php echo "(" . $row['age'] . " " . $row['age_type'] . "/" . $row['gender'] . ")"; ?>
                                                            &nbsp;<?= $row['rphone'] ?>
                                                            <br>
                                                            <span title="Account holder">AC-</span><a style="margin-left:0;" href="<?php echo base_url(); ?>customer-master/customer-all-details/<?php echo $row['cid']; ?>"><?php echo ucwords($row['full_name']); ?></a>&nbsp;<?= $row['mobile'] ?>
                                                        <?php } ?>

                                                        <br>
                                                        <?php
                                                        if ($row["test_city"] == 1) {
                                                            echo "Ahmedabad";
                                                        } else if ($row["test_city"] == 2) {
                                                            echo "Surat";
                                                        } else if ($row["test_city"] == 3) {
                                                            echo "Vadodara";
                                                        } else if ($row["test_city"] == 4) {
                                                            echo "Gurgaon";
                                                        } else if ($row["test_city"] == 5) {
                                                            echo "Delhi";
                                                        } else if ($row["test_city"] == 6) {
                                                            echo "Gandhinagar";
                                                        }
                                                        /* if($branch['portal'] == 'web'){ */
                                                        $branch_id = $row['branch_fk'];
                                                        $smsalert = 0;
                                                        $emailalert = 0;
                                                        $allow_whatsapp = 0;
                                                        $allowwhatsappwithoutpayment = false;
                                                        foreach ($branchlist as $branch) {
                                                            if ($row['branch_fk'] == $branch['id']) {
                                                                echo '[' . $branch['branch_name'] . ']';

                                                                $smsalert = $branch['smsalert'];
                                                                $emailalert = $branch['emailalert'];

                                                                if ($branch["allow_whatsapp"] == "1")
                                                                    $allow_whatsapp = 1;

                                                                if ($branch["whatsapp_report_send"] == "1")
                                                                    $allowwhatsappwithoutpayment = true;
                                                            }
                                                        }

                                                        ?>
                                                        <br><small><b>Added By- </b>
                                                            <?php
                                                            if (!empty($row["phlebo_added_by"])) {
                                                                echo ucfirst($row["phlebo_added_by"]) . " (Phlebo)";
                                                            } else if (!empty($row["added_by"])) {
                                                                echo ucfirst($row["added_by"]);
                                                            } else {
                                                                echo "Online Booking";
                                                            }
                                                            ?>
                                                        </small>
                                                    </td>
                                                    <td><?php if (!empty($row["doctor"])) { ?><?= ucfirst($row["doctor_name"]) . " - " . $row["doctor_mobile"]; ?> <?php
                                                                                                                                                                } else {
                                                                                                                                                                    echo "-";
                                                                                                                                                                }
                                                                                                                                                                    ?>
                                                    <br>
                                                    PRO - <?php if (!empty($row['pro_name'])) {
                                                                echo $row['pro_name'];
                                                            } else {
                                                                echo '-';
                                                            }  ?>
                                                    </td>
                                                    <td><?php
                                                        $test_id_1 = "";

                                                        foreach ($row["job_test_list"] as $test) {
                                                            $test_id_1 .= $test['test_fk'] . ',';
                                                            $is_panel = '';
                                                            if ($test['is_panel'] == 1) {
                                                                $is_panel = '(Panel)';
                                                            }
                                                            echo "<span id='test_" . $row["id"] . "_" . $test['test_fk'] . "' class='test_" . $row["id"] . "'>" . ucwords($test['test_name']) . " <b>" . $is_panel . "</b></span>" . "<br>";
                                                            foreach ($test["sub_test"] as $kt_key) {

                                                                $kt_key["test_name"];
                                                                if (!in_array($kt_key["sub_test"], $test_list)) {
                                                        ?>
                                                                    <i style="margin-left:20px">-</i><span id='test_<?= $row['id']; ?>_<?= $kt_key["sub_test"] ?>' class="test_<?= $row['id']; ?>"><?= $kt_key["test_name"] ?></span><br>
                                                            <?php
                                                                    $test_list[] = $kt_key["test_fk"];
                                                                }
                                                            }
                                                        }
                                                        $test_list = array();
                                                        $package_id_1 = "";
                                                        foreach ($row["package"] as $key3) {

                                                            ?>
                                                            <?php echo ucfirst($key3["name"]); ?><br><?php
                                                                                                        foreach ($key3["test"] as $kt_key) {
                                                                                                            $package_id_1 .= $kt_key['test_fk'] . ',';
                                                                                                            $kt_key["test_name"];
                                                                                                            if (!in_array($kt_key["test_fk"], $test_list)) {
                                                                                                        ?>
                                                                    <i style="margin-left:20px">-</i><span id='test_<?= $row['id']; ?>_<?= $kt_key["test_fk"] ?>' class="test_<?= $row['id']; ?>"><?= $kt_key["test_name"] ?></span><br>
                                                        <?php
                                                                                                                $test_list[] = $kt_key["test_fk"];
                                                                                                            }
                                                                                                        }
                                                                                                    }
                                                        ?>
                                                    </td>
                                                    <td><?php echo ucwords($row['date']); ?></td>
                                                    <td><?php
                                                        $payable_amount = 0;
                                                        $color_code = '#00A65A';
                                                        if ($row['payable_amount'] > 0) {
                                                            $color_code = '#D33724';
                                                        }
                                                        if ($row['payable_amount'] == "") {
                                                            echo "<spam style='color:white;background:" . $color_code . ";padding:2px'>Rs. 0";
                                                        } else {
                                                            $payable_amount = $row['payable_amount'];
                                                            echo "<spam style='color:white;background:" . $color_code . ";padding:2px'>Rs. " . number_format((float) $row['payable_amount'], 2, '.', '');
                                                        }
                                                        ?> /<?= " Rs." . number_format((float) $row["price"], 2, '.', '') . "</spam>"; ?>
                                                        <?php
                                                        if ($row["cut_from_wallet"] != 0) {
                                                            echo "<br><small>(Rs." . number_format((float) $row["cut_from_wallet"], 2, '.', '') . " Debited from wallet)</small>";
                                                        }
                                                        if ($row["discount"] != '' && $row["discount"] != '0') {
                                                            $dprice1 = $row["discount"] * $row["price"] / 100;
                                                            $discount_amount = $discount_amount + $dprice1;
                                                            echo "<br><small>( Rs." . number_format((float) $dprice1, 2, '.', '') . " Discount)</small>";
                                                        }
                                                        if ($row["collection_charge"] == 1) {
                                                            echo "<br><small>( Rs." . number_format((float) $row["collectioncharge_amount"], 2, '.', '') . " Collection charge)</small>";
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $status = $row['status'];

                                                        if ($row['status'] == 1) {
                                                            echo "<span class='label label-danger '>Waiting For Approval</span>";
                                                        }
                                                        ?>
                                                        <?php
                                                        if ($row['status'] == 6) {
                                                            echo "<span class='label label-warning '>Approved</span>";
                                                        }
                                                        ?>
                                                        <?php
                                                        if ($row['status'] == 7) {
                                                            echo "<span class='label label-warning '>Sample Collected</span>";
                                                        }
                                                        ?>
                                                        <?php
                                                        if ($row['status'] == 8) {
                                                            echo "<span class='label label-warning '>Sample received & processing</span>";
                                                        }
                                                        ?>
                                                        <?php
                                                        if ($row['status'] == 2) {
                                                            echo "<span class='label label-success '>Completed</span>";
                                                        }
                                                        ?>
                                                        <?php
                                                        if ($row['status'] == 0) {
                                                            echo "<span class='label label-danger '>User Deleted</span>";
                                                        }
                                                        ?>
                                                        <br><?php
                                                            if ($row['emergency'] == '1') {
                                                                echo "<span class='label label-danger '>Emergency</span><br>";
                                                            }
                                                            if ($row["dispatch"] == 1) {
                                                                echo "<span class='label label-success '>Dispatched</span>";
                                                            }
                                                            ?>
                                                    </td>
                                                    <td>
                                                        <a href='<?php echo base_url(); ?>job-master/job-details/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="View Job Details" target="_blank"> <span class=""><i class="fa fa-eye"> </i></span> </a>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div><!-- /.box-body -->
                        </div><!-- /.box -->
                    </div>
                    

                </div><!-- /.col (RIGHT) -->
            </div><!-- /.row -->

        </section><!-- /.content -->









        <div class="modal fade" id="use_reagent_model" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Reagent Less than 15% Left</h4>
                    </div>
                    <?php echo form_open("job_master/add_sample_collect_time/" . $cid, array("method" => "POST", "role" => "form")); ?>

                    <div class="tableclass">
                        <div class="table-responsive pending_job_list_tbl">
                            <table class="table table-striped" id="city_wiae_price">
                                <thead>
                                    <tr>
                                        <th>Sr. No.</th>
                                        <th>Branch Name</th>
                                        <th>Reagent Name</th>
                                        <th>Total Test Quantity / Used</th>
                                    </tr>
                                </thead>
                                <tbody id="t_body">
                                    <?php
                                    $i = 0;
                                    $temp = [];
                                    $new_array = array();
                                    foreach ($new_data as $row) {
                                        if (!in_array(strtoupper(trim($row["branch_name"])) . strtoupper(trim($row["reagent_name"])), $new_array)) {


                                            $used_item = 0;
                                            foreach ($row["old_record"] as $oldkey) {
                                                $used_item += $oldkey["useditem"] * $oldkey["timeperfomace"];
                                            }
                                            $ata = $row["packet_quantity"] * $row["quantity"];
                                            $ata1 = round($ata * 10 / 100);
                                            $ata2 = round($ata - $ata1);
                                            $ata3 = round($ata - $used_item);
                                            if (($row["packet_quantity"] * $row["quantity"] - (($row["packet_quantity"] * $row["quantity"]) * 15 / 100)) < $used_item) {
                                                $i++;
                                    ?>
                                                <tr>
                                                    <td><?= $i; ?></td>
                                                    <td><?php echo $row["branch_name"]; ?></td>
                                                    <td><?php echo $row["reagent_name"]; ?></td>
                                                    <td><?php echo ($row["packet_quantity"] * $row["quantity"]) . ' ' . $row["unit_name"]; ?>&nbsp;/&nbsp;<?php echo $used_item . ' ' . $row["unit_name"]; ?></td>
                                                </tr>

                                    <?php
                                            }
                                            $new_array[] = strtoupper(trim($row["branch_name"])) . strtoupper(trim($row["reagent_name"]));
                                        }
                                    }
                                    ?>

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <!--                <button type="submit" class="btn btn-primary" id="amount_submit_btn">Submit</button>-->
                        <button type="button" class="btn btn-default closeBtn" data-dismiss="modal">Close</button>
                        <a class="btn btn-primary" href="<?php echo base_url() ?>inventory/indent_usereagent" target="_blank">View</a>
                    </div>
                    <?= form_close(); ?>
                </div>
            </div>
        </div>



        <!-- jQuery 2.1.4 -->
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script>
            let urlParams = new URLSearchParams(window.location.search);
            // let debug = urlParams.get('debug');

            // $('.closeBtn').click(function() {
            //     if (debug == "1") {
            //         $('#pending_data_modal').modal('show');
            //     }
            // });
        </script>
        <script type="text/javascript">
            <?php if ($login_data['type'] == "1" || $login_data['type'] == "2" || $login_data['type'] == "8" || $login_data['type'] == "5") { ?>
                $(window).on('load', function() {
                    $('#use_reagent_model').modal('show');
                });
            <?php } ?>

            function get_date(val) {
                if (val != '') {
                    window.location.href = "<?= base_url() ?>/Dashboard?c_date=" + val;
                }
            }
            google.charts.load('current', {
                packages: ['corechart', 'bar']
            });
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                    ['Date', 'Revenue', 'Due', 'Paid'],
                    ['<?php
                        if ($date == 0) {
                            echo "Today";
                        }
                        if ($date == '-1') {
                            echo "Last Two Days";
                        }
                        if ($date == -7) {
                            echo "Last Seven Days";
                        }
                        if ($date == -30) {
                            echo "Last Month";
                        }
                        ?>', <?php echo (!empty($total_revenue[0]["revenue"])) ? $total_revenue[0]["revenue"] : 0; ?>, <?php echo (!empty($total_due_amount[0]["due_amount"])) ? $total_due_amount[0]["due_amount"] : 0; ?>, <?php echo ((!empty($total_revenue[0]["revenue"])) ? $total_revenue[0]["revenue"] : 0) - ((!empty($total_due_amount[0]["due_amount"])) ? $total_due_amount[0]["due_amount"] : 0); ?>]
                ]);

                var options = {
                    chart: {
                        //   title: 'Company Performance',
                        //            subtitle: 'Sales, Expenses, and Profit: 2014-2017',
                    }
                };

                var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

                chart.draw(data, google.charts.Bar.convertOptions(options));
            }

            google.charts.setOnLoadCallback(drawAnnotations);

            function drawAnnotations() {
                //var data = new google.visualization.DataTable();
                var data = google.visualization.arrayToDataTable([
                    ['Number', 'Job Status', {
                        role: 'style'
                    }],
                    <?php
                    $status1 = 0;
                    foreach ($job_status as $key) {
                        $status1 = $status1 + $key["count"];
                    }
                    echo "['Total Jobs', " . $status1 . ", 'color: #4285f4'],";
                    ?>
                    <?php
                    $status1 = 0;
                    foreach ($job_status as $key) {
                        if ($key["status"] == 1) {
                            echo "['Waiting For Approval', " . $key["count"] . ", 'color: #db4437'],";
                            $status1 = 1;
                        }
                    }
                    if ($status1 == 0) {
                        echo "['Waiting For Approval', 0, 'color: #db4437'],";
                    }
                    ?>
                    <?php
                    $status1 = 0;
                    foreach ($job_status as $key) {
                        if ($key["status"] == 6) {
                            echo "['Approved', " . $key["count"] . ", 'color: #f4b400'],";
                            $status1 = 1;
                        }
                    }
                    if ($status1 == 0) {
                        echo "['Approved', 0, 'color: #f4b400'],";
                    }
                    ?>
                    <?php
                    $status1 = 0;
                    foreach ($job_status as $key) {
                        if ($key["status"] == 7) {
                            echo "['Sample Collected', " . $key["count"] . ", 'color: #f4b400'],";
                            $status1 = 1;
                        }
                    }
                    if ($status1 == 0) {
                        echo "['Sample Collected', 0, 'color: #f4b400'],";
                    }
                    ?>
                    <?php
                    $status1 = 0;
                    foreach ($job_status as $key) {
                        if ($key["status"] == 8) {
                            echo "['Processing', " . $key["count"] . ", 'color: #f4b400'],";
                            $status1 = 1;
                        }
                    }
                    if ($status1 == 0) {
                        echo "['Processing', 0, 'color: #f4b400'],";
                    }
                    ?>
                    <?php
                    $status1 = 0;
                    foreach ($job_status as $key) {
                        if ($key["status"] == 2) {
                            echo "['Completed', " . $key["count"] . ", 'color: green']";
                            $status1 = 1;
                        }
                    }
                    if ($status1 == 0) {
                        echo "['Completed', 0, 'color: green']";
                    }
                    ?>
                ]);

                var options = {
                    //  title: 'Motivation and Energy Level Throughout the Day'
                };

                var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
                chart.draw(data, options);
            }
        </script>


        <!-- page script -->
        <script>
            $(function() {
                /* ChartJS
                 * -------
                 * Here we will create a few charts using ChartJS
                 */

                //--------------
                //- AREA CHART -
                //--------------

                // Get context with jQuery - using jQuery's .get() method.
                var areaChartCanvas = $("#areaChart").get(0).getContext("2d");
                // This will get the first returned node in the jQuery collection.
                var areaChart = new Chart(areaChartCanvas);
                var areaChartData = {
                    labels: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
                    datasets: [{
                            label: "Total Tests",
                            fillColor: "rgba(210, 214, 222, 1)",
                            strokeColor: "rgba(210, 214, 222, 1)",
                            pointColor: "rgba(210, 214, 222, 1)",
                            pointStrokeColor: "#c1c7d1",
                            pointHighlightFill: "#fff",
                            pointHighlightStroke: "rgba(220,220,220,1)",
                            data: [<?php
                                    if ($test_sunday[0]['total'] == "") {
                                        echo "0";
                                    } else {
                                        echo $test_sunday[0]['total'];
                                    }
                                    ?>, <?php
                                        if ($test_monday[0]['total'] == "") {
                                            echo "0";
                                        } else {
                                            echo $test_monday[0]['total'];
                                        }
                                        ?>, <?php
                                            if ($test_tuesday[0]['total'] == "") {
                                                echo "0";
                                            } else {
                                                echo $test_tuesday[0]['total'];
                                            }
                                            ?>, <?php
                                                if ($test_wednesday[0]['total'] == "") {
                                                    echo "0";
                                                } else {
                                                    echo $test_wednesday[0]['total'];
                                                }
                                                ?>, <?php
                if ($test_thrusday[0]['total'] == "") {
                    echo "0";
                } else {
                    echo $test_thrusday[0]['total'];
                }
                ?>, <?php
                if ($test_friday[0]['total'] == "") {
                    echo "0";
                } else {
                    echo $test_friday[0]['total'];
                }
                ?>, <?php
                if ($test_saturday[0]['total'] == "") {
                    echo "0";
                } else {
                    echo $test_saturday[0]['total'];
                }
                ?>]
                        },
                        {
                            label: "Total Sample Collection",
                            fillColor: "rgba(60,141,188,0.9)",
                            strokeColor: "rgba(60,141,188,0.8)",
                            pointColor: "#3b8bba",
                            pointStrokeColor: "rgba(60,141,188,1)",
                            pointHighlightFill: "#fff",
                            pointHighlightStroke: "rgba(60,141,188,1)",
                            data: [<?php
                                    if ($sample_sunday[0]['total'] == "") {
                                        echo "0";
                                    } else {
                                        echo $sample_sunday[0]['total'];
                                    }
                                    ?>, <?php
                                        if ($sample_monday[0]['total'] == "") {
                                            echo "0";
                                        } else {
                                            echo $sample_monday[0]['total'];
                                        }
                                        ?>, <?php
                                            if ($sample_tuesday[0]['total'] == "") {
                                                echo "0";
                                            } else {
                                                echo $sample_tuesday[0]['total'];
                                            }
                                            ?>, <?php
                                                if ($sample_wednesday[0]['total'] == "") {
                                                    echo "0";
                                                } else {
                                                    echo $sample_wednesday[0]['total'];
                                                }
                                                ?>, <?php
                if ($sample_thrusday[0]['total'] == "") {
                    echo "0";
                } else {
                    echo $sample_thrusday[0]['total'];
                }
                ?>, <?php
                if ($sample_friday[0]['total'] == "") {
                    echo "0";
                } else {
                    echo $sample_friday[0]['total'];
                }
                ?>, <?php
                if ($sample_saturday[0]['total'] == "") {
                    echo "0";
                } else {
                    echo $sample_saturday[0]['total'];
                }
                ?>]
                        }
                    ],
                    options: {
                        scales: {
                            yAxes: [{
                                scaleLabel: {
                                    display: true,
                                    labelString: 'probability'
                                }
                            }]

                        }

                    }
                };
                var areaChartData1 = {
                    labels: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
                    datasets: [{
                            label: "Total Amount",
                            fillColor: "rgba(210, 214, 222, 1)",
                            strokeColor: "rgba(210, 214, 222, 1)",
                            pointColor: "rgba(210, 214, 222, 1)",
                            pointStrokeColor: "#c1c7d1",
                            pointHighlightFill: "#fff",
                            pointHighlightStroke: "rgba(220,220,220,1)",
                            data: [<?php
                                    if ($amount_sunday[0]['total'] == "") {
                                        echo "0";
                                    } else {
                                        echo $amount_sunday[0]['total'];
                                    }
                                    ?>, <?php
                                        if ($amount_monday[0]['total'] == "") {
                                            echo "0";
                                        } else {
                                            echo $amount_monday[0]['total'];
                                        }
                                        ?>, <?php
                                            if ($amount_tuesday[0]['total'] == "") {
                                                echo "0";
                                            } else {
                                                echo $amount_tuesday[0]['total'];
                                            }
                                            ?>, <?php
                                                if ($amount_wednesday[0]['total'] == "") {
                                                    echo "0";
                                                } else {
                                                    echo $amount_wednesday[0]['total'];
                                                }
                                                ?>, <?php
                if ($amount_thrusday[0]['total'] == "") {
                    echo "0";
                } else {
                    echo $amount_thrusday[0]['total'];
                }
                ?>, <?php
                if ($amount_friday[0]['total'] == "") {
                    echo "0";
                } else {
                    echo $amount_friday[0]['total'];
                }
                ?>, <?php
                if ($amount_saturday[0]['total'] == "") {
                    echo "0";
                } else {
                    echo $amount_saturday[0]['total'];
                }
                ?>]
                        },
                        /*{
                         label: "Cashback",
                         fillColor: "rgba(60,141,188,0.9)",
                         strokeColor: "rgba(60,141,188,0.8)",
                         pointColor: "#3b8bba",
                         pointStrokeColor: "rgba(60,141,188,1)",
                         pointHighlightFill: "#fff",
                         pointHighlightStroke: "rgba(60,141,188,1)",
                         data: [<?php
                                if ($cashback_sunday[0]['total'] == "") {
                                    echo "0";
                                } else {
                                    echo $cashback_sunday[0]['total'];
                                }
                                ?>, <?php
                                    if ($acashback_monday[0]['total'] == "") {
                                        echo "0";
                                    } else {
                                        echo $cashback_monday[0]['total'];
                                    }
                                    ?>, <?php
                                        if ($cashback_tuesday[0]['total'] == "") {
                                            echo "0";
                                        } else {
                                            echo $cashback_tuesday[0]['total'];
                                        }
                                        ?>, <?php
                                            if ($cashback_wednesday[0]['total'] == "") {
                                                echo "0";
                                            } else {
                                                echo $cashback_wednesday[0]['total'];
                                            }
                                            ?>, <?php
                if ($cashback_thrusday[0]['total'] == "") {
                    echo "0";
                } else {
                    echo $cashback_thrusday[0]['total'];
                }
                ?>, <?php
                if ($cashback_friday[0]['total'] == "") {
                    echo "0";
                } else {
                    echo $cashback_friday[0]['total'];
                }
                ?>, <?php
                if ($cashback_saturday[0]['total'] == "") {
                    echo "0";
                } else {
                    echo $cashback_saturday[0]['total'];
                }
                ?>]
                         }, {
                         label: "Digital Goods",
                         fillColor: "rgba(60,141,188,0.9)",
                         strokeColor: "rgba(60,141,188,0.8)",
                         pointColor: "#3b8a",
                         pointStrokeColor: "rgba(60,141,188,1)",
                         pointHighlightFill: "#fff",
                         pointHighlightStroke: "rgba(60,141,188,1)",
                         data: [40, 60, 52, 31, 98, 39, 102]
                         }*/
                    ]
                };
                var areaChartOptions = {
                    //Boolean - If we should show the scale at all
                    showScale: true,
                    //Boolean - Whether grid lines are shown across the chart
                    scaleShowGridLines: false,
                    //String - Colour of the grid lines
                    scaleGridLineColor: "rgba(0,0,0,.05)",
                    //Number - Width of the grid lines
                    scaleGridLineWidth: 1,
                    //Boolean - Whether to show horizontal lines (except X axis)
                    scaleShowHorizontalLines: true,
                    //Boolean - Whether to show vertical lines (except Y axis)
                    scaleShowVerticalLines: true,
                    //Boolean - Whether the line is curved between points
                    bezierCurve: true,
                    //Number - Tension of the bezier curve between points
                    bezierCurveTension: 0.3,
                    //Boolean - Whether to show a dot for each point
                    pointDot: true,
                    //Number - Radius of each point dot in pixels
                    pointDotRadius: 4,
                    //Number - Pixel width of point dot stroke
                    pointDotStrokeWidth: 1,
                    //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
                    pointHitDetectionRadius: 20,
                    //Boolean - Whether to show a stroke for datasets
                    datasetStroke: true,
                    //Number - Pixel width of dataset stroke
                    datasetStrokeWidth: 2,
                    //Boolean - Whether to fill the dataset with a color
                    datasetFill: true,
                    //String - A legend template
                    //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
                    maintainAspectRatio: false,
                    //Boolean - whether to make the chart responsive to window resizing
                    responsive: true
                };
                //Create the line chart
                areaChart.Line(areaChartData, areaChartOptions);
                /*     var chartInstance = new Chart(ctx, {
                 type: 'line',
                 data: lineChartCanvas,
                 options: {
                 title: {
                 display: true,
                 text: 'Custom Chart Title'
                 }
                 }
                 }); */


                //-------------
                //- LINE CHART -
                //--------------
                var lineChartCanvas = $("#lineChart").get(0).getContext("2d");
                var lineChart = new Chart(lineChartCanvas);
                var lineChartOptions = areaChartOptions;
                lineChartOptions.datasetFill = false;
                lineChart.Line(areaChartData1, lineChartOptions);
            });
        </script>
    <?php } else { ?>

        <section class="content">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12 text-center" style="background-color:#dd4b39">
                    <img src="<?php echo base_url(); ?>user_assets/images/logo1.png" class="" style="  " alt="User Image" />
                </div>
            </div>
        </section>
    <?php } ?>
    <!-- jQuery 2.1.4 -->
<?php } ?>
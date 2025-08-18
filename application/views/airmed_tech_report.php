<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Airmed Tech Report
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>Airmed_tech_report/index"><i class="fa fa-users"></i>Airmed Tech Report</a></li>

        </ol>
    </section>
    <?php
    $cumdate = date("Y-m");
    ?>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Airmed Tech Report</h3>

                    </div>
                    <div class="box-body">
                        <div class="widget">
                            <?php if (isset($success) != NULL) { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $success['0']; ?>
                                </div>
                            <?php }if ($this->session->flashdata("success")) { ?>

                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $this->session->flashdata("success"); ?>
                                </div>

                            <?php }if ($this->session->flashdata("error")) { ?>

                                <div class="alert alert-danger alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $this->session->flashdata("error"); ?>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="tableclass">
                            <form role="form" action="<?php echo base_url(); ?>Airmed_tech_report/index" method="get" >
                                <div class="col-md-4">
                                    <select class="form-control" name="bid">
                                        <option value="">--Select--</option>
                                        <?php foreach ($user_assign_branch as $key) { ?>
                                            <option value="<?= $key["id"] ?>" <?php
                                            if ($bid == $key["id"]) {
                                                echo "selected";
                                            }
                                            ?>><?= $key["branch_name"] ?></option>
                                                <?php } ?>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <select class="form-control" name="year">
                                        <option value="">--Select Year--</option>
                                        <?php for ($i = 2018; $i <= date('Y'); $i++) { ?>
                                            <option value="<?= $i; ?>" <?php
                                            if ($year == $i) {
                                                echo "selected";
                                            }
                                            ?>><?= $i; ?></option>	
                                                <?php } ?>
                                    </select>
                                </div>

                                <!--                                bhavik 19 June 2018-->
                                <div class="col-md-2">
                                    <select class="form-control" name="month" id="month">
                                        <option value="">--Select Month--</option>
                                        <option value="1" <?php
                                        if ($month == "1") {
                                            echo "selected";
                                        }
                                        ?>>January</option>
                                        <option value="2" <?php
                                        if ($month == "2") {
                                            echo "selected";
                                        }
                                        ?>>February</option>
                                        <option value="3" <?php
                                        if ($month == "3") {
                                            echo "selected";
                                        }
                                        ?>>March</option>
                                        <option value="4" <?php
                                        if ($month == "4") {
                                            echo "selected";
                                        }
                                        ?>>April</option>
                                        <option value="5" <?php
                                        if ($month == "5") {
                                            echo "selected";
                                        }
                                        ?>>May</option>
                                        <option value="6" <?php
                                        if ($month == "6") {
                                            echo "selected";
                                        }
                                        ?>>June</option>
                                        <option value="7" <?php
                                        if ($month == "7") {
                                            echo "selected";
                                        }
                                        ?>>July</option>
                                        <option value="8" <?php
                                        if ($month == "8") {
                                            echo "selected";
                                        }
                                        ?>>August</option>
                                        <option value="9" <?php
                                        if ($month == "9") {
                                            echo "selected";
                                        }
                                        ?>>September</option>
                                        <option value="10" <?php
                                        if ($month == "10") {
                                            echo "selected";
                                        }
                                        ?>>October</option>
                                        <option value="11" <?php
                                        if ($month == "11") {
                                            echo "selected";
                                        }
                                        ?>>November</option>
                                        <option value="12" <?php
                                        if ($month == "12") {
                                            echo "selected";
                                        }
                                        ?>>December</option>
                                    </select>
                                </div>
                                <!--                                bhavik 19 June 2018-->


                                <a href="<?php echo base_url(); ?>Airmed_tech_report/index" class="btn btn-primary btn-md">Reset</a>
                                <input type="submit" name="search" class="btn btn-success pull-left" value="Search" style="margin-right: 5px;"/>

                                <!--                                bhavik 19 june 2018-->
                                <?php //if (!empty($bid)) {   ?>
        <!--                                    <a style="float:right;" href='<?php echo base_url(); ?>Airmed_tech_report/export_csv?bid=<?= $bid ?>' class="btn btn-primary pull-left" ><i class="fa fa-download" ></i><strong > Export To Data</strong></a>-->                                
                                <a href='<?php echo base_url(); ?>Airmed_tech_report/export_csv?bid=<?= $bid ?>&month=<?= $month ?>' class="btn btn-primary" ><i class="fa fa-download" ></i><strong > Export To Data</strong></a>
                                <?php //}   ?>


                                <!--                                bhavik 19 june 2018-->
                                <br><br>
                                <div id="prnt_rpt">
                                    <table id="example3" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Center</th>
                                                <th>Month</th>
                                                <th>Total Revenue</th>
                                                <!--                                                    Bhavik 19 June 2018-->
                                                <?php if ($login_data['type'] == "1" || $login_data['type'] == "2") { ?>
                                                    <th>Discount</th>
                                                    <th>Net Price</th>
                                                <?php } ?>
                                                <!--                                                    Bhavik 19 June 2018-->
                                                <th>Paid to Airmed</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $cnt = 1;
                                            foreach ($get_branch_report as $row) {
                                                $bid = $row['branch_fk'];
                                                //$payamount = round($row['net_price'] * 7 / 100) - $row['paidamount'];
                                                //$payamount = round($row['total_revenue'] * 7 / 100) - $row['paidamount'];
                                                $payamount = round($row['paidtoairmed']) - $row['paidamount'];

//                                                if ($row['paidamount'] == '') {
//                                                    $paid_money = 0;
//                                                } else {
//                                                    $paid_money = $row['paidamount'];
//                                                }
                                                ?>
                                                <tr style="<?php
                                                if ($payamount == 0) {
                                                    echo "background-color:#84ce87;color:white;";
                                                }
                                                ?>">
                                                    <td><?php echo $cnt; ?></td>
                                                    <td ><?php echo $row['branch_name']; ?></td>
                                                    <td ><a href="<?= base_url(); ?>Airmed_tech_report/month_report?bid=<?= $bid ?>&month=<?= $row["month"] ?>" target="_blank"><?php echo $row['date']; ?></a></td>
                                                    <td >Rs.<?php echo round($row['total_revenue'], 2); ?></td>

                                                    <!--                                                    Bhavik 19 June 2018-->
                                                    <?php if ($login_data['type'] == "1" || $login_data['type'] == "2") { ?>
                                                        <td>Rs.<?php echo round($row['discount']); ?></td>
                                                        <td>Rs.<?php echo round($row['net_price']); ?></td>
                                                        <!--                                                    Bhavik 19 June 2018-->                                                    

                                                    <?php } ?>
                                                    <td>Rs.<?php echo $payamount; ?></td>
<!--                                                        <td>Rs.<?php //echo $paid_money; ?></td>-->
                                                    <td>
                                                        <?php if (0 < $payamount && strtotime($cumdate) > strtotime($row['month'])) { ?>
                                                            <a href="<?= base_url() . "Airmed_tech_report/payumoney?bid=" . $bid . "&month=" . $row['month']; ?>" target="_blank" class="btn btn-sm btn-success">Pay</a>

                                                            <?php if (in_array($login_data["type"], array("1", "2"))) { ?>
                                                                <a id="<?= $bid . "_" . $row['month'] . "_" . $payamount ?>" class="btn btn-sm btn-success recevepayment" href="javascript:void(0)" ><i class="fa fa-credit-card"></i> Receive Payment</a>
                                                                <?php
                                                            }
                                                        }
                                                        if ($payamount == 0) {
                                                            echo "Payment Received";
                                                        }
                                                        ?>

                                                        <a href="<?= base_url() . "Airmed_tech_report/printinvoice?bid=$bid&month=" . $row['month']; ?>" id="" class="btn btn-sm btn-primary"><i class="fa fa-print"></i><strong> Print Invoice</strong></a>

                                                    </td>
                                                </tr>
                                                <?php
                                                $cnt++;
                                            }if (empty($get_branch_report)) {
                                                ?>
                                                <tr>
                                                    <td colspan="8">No records found</td>
                                                </tr>
                                            <?php } ?>

                                        </tbody>
                                    </table>
                                </div>
                            </form>
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
        <?php if (in_array($login_data["type"], array("1", "2"))) { ?>
            <div class="modal fade" id="add_model_payment" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Receive Payment</h4>
                        </div>
                        <?php echo form_open("Airmed_tech_report/recivepayment", array("method" => "POST", "role" => "form", "onsubmit" => "return confirm('Are you sure receive payment?')")); ?>
                        <div class="modal-body">

                            <div class="form-group">
                                <label for="recipient-name" class="control-label">Amount:</label>
                                <input type="text" readonly name="amount" id="payamount" value="" required="" class="form-control number"/>
                            </div>
                            <div class="form-group">
                                <input type="hidden" id="paybranchid" name="bid" />
                                <input type="hidden" id="paybranchmonth" name="paymonth" />
                                <label for="recipient-name" class="control-label">Payment Mode:</label>
                                <select class="form-control" name="type">
                                    <option value="cash">Cash</option>
                                    <option value="bank-deposit">Bank Deposit</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="control-label">Remark:</label>
                                <textarea class="form-control" name="note"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="amount_submit_btn">Submit</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                        <?= form_close(); ?>
                    </div>

                </div>
            </div>
        <?php } ?>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<script>
    $(document).ready(function () {

        var date_input = $('input[name="expense_date"]'); //our date input has the name "date"

        var container = $('.bootstrap-iso form').length > 0 ? $('.bootstrap-iso form').parent() : "body";
        date_input.datepicker({
            format: 'dd-mm-yyyy',
            container: container,
            todayHighlight: true,
            autoclose: true
        });
        $(".recevepayment").click(function () {

            var id = this.id.split("_");
            var bid = id[0];
            var bidmonth = id[1];
            var bipayamount = id[2];
            $("#paybranchid").val(bid);
            $("#paybranchmonth").val(bidmonth);
            $("#payamount").val(bipayamount);
            $('#add_model_payment').modal('show');

        });
    });
</script>
<script type="text/javascript">
    function printData()
    {
        var divToPrint = document.getElementById("prnt_rpt");
        newWin = window.open("");
        newWin.document.write(divToPrint.outerHTML);
        newWin.print();
        newWin.close();
    }
</script>
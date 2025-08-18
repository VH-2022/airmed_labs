<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?= ucfirst($lab_details[0]["name"]) ?> 
            <small>#<?= $lab_details[0]["id"] ?></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Lab List</a></li>
            <li class="active">Profile</li>
        </ol>
    </section>

    <!--        <div class="pad margin no-print">
              <div class="callout callout-info" style="margin-bottom: 0!important;">												
                <h4><i class="fa fa-info"></i> Note:</h4>
                This page has been enhanced for printing. Click the print button at the bottom of the invoice to test.
              </div>
            </div>-->

    <!-- Main content -->
    <section class="invoice">
        <!-- title row -->
        <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header">
                    <i class="fa fa-eyedropper"></i> <?= ucfirst($lab_details[0]["name"]) ?> 
                    <small class="pull-right">Date: <?= date("d/m/Y"); ?></small>
                </h2>
            </div><!-- /.col -->
        </div>
        <!-- info row -->
        <div class="row invoice-info">
            <?php /* <div class="col-sm-4 invoice-col">
              From
              <address>
              <strong>Admin, Inc.</strong><br>
              795 Folsom Ave, Suite 600<br>
              San Francisco, CA 94107<br>
              Phone: (804) 123-5432<br/>
              Email: info@almasaeedstudio.com
              </address>
              </div><!-- /.col -->
              <div class="col-sm-4 invoice-col">
              To
              <address>
              <strong>John Doe</strong><br>
              795 Folsom Ave, Suite 600<br>
              San Francisco, CA 94107<br>
              Phone: (555) 539-1037<br/>
              Email: john.doe@example.com
              </address>
              </div><!-- /.col --> */ ?>
            <div class="col-sm-4 invoice-col">
                <b>Lab Id:</b> #<?= $lab_details[0]["id"] ?><br/>
                <b>Email:</b> <?php echo $lab_details[0]["email"]; ?><br/>
                <b>Contact Person:</b> <?php echo ucwords($lab_details[0]["contact_person_name"]); ?><br/>
                <b>Mobile Number:</b> <?php echo $lab_details[0]["mobile_number"]; ?><br>
                <b>Alternate Number:</b> <?php echo $lab_details[0]["alternate_number"]; ?><br>
                <b>Address:</b> <?php echo $lab_details[0]["address"]; ?><br>
                <b>Client description and Business Type:</b> <?php echo $lab_details[0]["bus_desc"]; ?><br>
                <b>Space allocated for primary simple collection:</b> <?php echo $lab_details[0]["space_allocate"]; ?><br>
                <b>Business Expected:</b> <?php echo $lab_details[0]["bus_expeted"]; ?><br>
                <b>Test discount :</b> <?php echo $lab_details[0]["test_discount"]; ?>%
            </div><!-- /.col -->
            <div class="col-sm-5 invoice-col">
            </div><!-- /.col -->
            <div class="col-sm-3 invoice-col">
                <?php /* if (isset($credit_list[0]["total"]) > 0) { ?>
                  <h3 style="margin-top:0px"><b style="color:green;">Credit:</b> Rs.<?php
                  $credited = 0;
                  $due = 0;
                  if ($credit_list[0]["total"] > 0) {
                  $credited = $credit_list[0]["total"];
                  } else {
                  $due = $credit_list[0]["total"];
                  } echo $credited;
                  ?><br/></h3>
                  <h3 style="margin-top:0px"><b style="color:red;">Due:</b> Rs.<?= 0-$due; ?><br/></h3>
                  <?php } */ ?>
            </div><!-- /.col -->
        </div><!-- /.row -->

        <!-- Table row -->
        <br/>
        <div class="row">
            <div class="col-md-6">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Test <a href="<?= base_url(); ?>b2b/Logistic_master/sub_list/<?= $lab_fk ?>">Manage</a></h3>
                    </div><!-- /.box-header -->
                    <div class="box-body no-padding">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Test Name</th>
                                    <th>Special Price</th>
                                </tr>
                            </thead>

                            <?php
                            $cnt = 1;
                            foreach ($test_list as $test) {
                                ?>
                                <tr>
                                    <td><?php echo $cnt; ?></td>
                                    <td><?php echo ucwords($test['test_name']); ?></td>
                                    <td><?php echo "Rs." . $test['price']; ?></td>
                                </tr>
                                <?php
                                $cnt++;
                            }
                            foreach ($pack_list as $test) {
                                ?>
                                <tr>
                                    <td><?php echo $cnt; ?></td>
                                    <td><?php echo ucwords($test['test_name']); ?></td>
                                    <td><?php echo "Rs." . $test['price']; ?></td>
                                </tr>
                                <?php
                                $cnt++;
                            }


                            if (empty($test_list) && empty($pack_list)) {
                                ?>
                                <tr>
                                    <td colspan="3">No records found</td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>

                    </div><!-- /.box-body -->
                </div>
            </div>
            <div class="col-md-6">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Total Business <a href="<?php echo base_url(); ?>b2b/Amount_manage/details/<?= $lab_fk ?>">Manage</a></h3>
                    </div><!-- /.box-header -->
                    <div class="box-body no-padding">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Month</th>
                                    <th>Booking amount</th>
                                    <th>Received amount</th>
                                    <th>Due amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $cnt = 1;
                                $due_amiunt = 0;
                                foreach ($dueamountmonth as $credit) {
                                    $startdatein = date("01/m/Y", strtotime($credit['createddate']));
                                    $enddatein = date("t/m/Y", strtotime($credit['createddate']));

                                    $inmonth = date("m", strtotime($credit['createddate']));
                                    $cumonth = date("m");
                                    ?>
                                    <tr>
                                        <td><?php echo $credit['months']; ?></td>

                                        <td><?php echo "Rs." . round($credit['price']); ?></td>
                                        <td><?= "Rs." . round($credit['reciveamount']); ?></td>
                                        <td><?= "Rs." . round($credit['amount'] - $credit['reciveamount']); ?><?php $due_amiunt = $due_amiunt + round($credit['amount'] - $credit['reciveamount']); ?></td>
                                        <td><?php if ($inmonth != $cumonth) { ?> <a style="float:right;margin-left:3px" href="<?= base_url() . "b2b/invoice_master/labinvoice_pdf?date=$startdatein&todate=$enddatein&from=$lab_fk&intype=1" ?>" id="" class="btn btn-sm btn-primary"><i class="fa fa-print"></i><strong> Print Invoice</strong></a> <?php } ?></td>
                                    </tr>
                                    <?php
                                    $cnt++;
                                } if (empty($dueamountmonth)) {
                                    ?>
                                    <tr>
                                        <td colspan="3">No records found</td>
                                    </tr>
                                <?php } ?>
                                <?php if ($due_amiunt > 0) { ?>
                                    <tr>
                                        <td colspan="3"><b>Total Due Amount</b></td>
                                        <td colspan="2"><?= "Rs." . $due_amiunt ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>

    </section><!-- /.content -->
    <div class="clearfix"></div>
</div><!-- /.content-wrapper -->
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
                    <i class="fa fa-eyedropper"></i> <?php echo "Basic information"; ?> 
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
                <b>Client Id:</b> #<?= $lab_details[0]["id"] ?><br/>
                <b>Email:</b> <?php echo $lab_details[0]["email"]; ?><br/>
                <b>Contact Person:</b> <?php echo ucwords($lab_details[0]["contact_person_name"]); ?><br/>
                <b>Mobile Number:</b> <?php echo $lab_details[0]["mobile_number"]; ?><br>
                <b>Alternate Number:</b> <?php echo $lab_details[0]["alternate_number"]; ?><br>
                <b>Address:</b> <?php echo $lab_details[0]["address"]; ?>
				<br>
                <b>Business description:</b> <?php echo $lab_details[0]["bus_desc"]; ?>
				
				<br>
                <b>Pincode:</b> <?php echo $lab_details[0]["pincode"]; ?>
				<br>
                <b>Pancard no:</b> <?php echo $lab_details[0]["pancard"]; ?>
				<br>
                <b>Space allocate:</b> <?php echo $lab_details[0]["space_allocate"]; ?>
				<br>
                <b>Business expected:</b> <?php echo $lab_details[0]["bus_expeted"]; ?>
				<br>
                <b>City:</b> <?php echo $lab_details[0]["cityname"];  /* ?>
				<br>
                <b>createddate:</b> <?php echo date("d/m/Y",strtotime($lab_details[0]["createddate"]));; */ ?>
				
            </div><!-- /.col -->
            <div class="col-sm-5 invoice-col">
			
            </div><!-- /.col -->
            <div class="col-sm-3 invoice-col">
                <?php
                    if (isset($credit_list[0]["total"]) > 0) { ?>
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
                    <?php } ?>
            </div><!-- /.col -->
			
        </div><!-- /.row -->

        <!-- Table row -->
        <br/>
		 <div class="row">
		 <div class="col-sm-12">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Uploaded identity documents</h3>
          <span class="label label-primary pull-right"><i class="fa fa-picture-o"></i></span>
        </div><!-- /.box-header -->
        <div class="box-body">
		<?php if($labdocks != null){ foreach($labdocks as $docimg){ ?>
          <div class="col-md-4">
    <div class="thumbnail">
	<?php $baseurlinfo=base_url(); ?>
	<a href="<?= $baseurlinfo."upload/labducuments/".$docimg['dock_name'].'?'.time(); ?>" target="_blank">
     <img src="<?= $baseurlinfo."upload/labducuments/".$docimg['dock_name'] ?>" style="width:100%">
	 </a>
    </div>
  </div>
		<?php } }else{ echo "<p>No identity documentation available</p>"; } ?>
          
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div>
		 </diV>
		 
		 <div class="row">
		 <div class="col-sm-12">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Uploaded address documents</h3>
          <span class="label label-primary pull-right"><i class="fa fa-picture-o"></i></span>
        </div><!-- /.box-header -->
        <div class="box-body">
		<?php if($labdocksadd != null){ foreach($labdocksadd as $docimg){ ?>
          <div class="col-md-4">
    <div class="thumbnail">
	<a href="<?= $baseurlinfo."upload/labducuments/".$docimg['dock_name'].'?'.time(); ?>" target="_blank">
     <img src="<?= $baseurlinfo."upload/labducuments/".$docimg['dock_name'] ?>" style="width:100%">
	 </a>
    </div>
  </div>
		<?php } }else{ echo "<p>No address documentation available</p>"; } ?>
          
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div>
		 </diV>
		 
        <div class="row">

            <div class="col-xs-6 table-responsive">
                <p class="lead">Test <small>(B2C test discount in <?= $lab_details[0]["test_discount"]."%"; ?>)</small></p>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Price</th>
							<th>Special price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $cnt = 1;
                        foreach ($test_list as $test) {
                            ?>
                            <tr>
                                <td><?php echo $cnt; ?></td>
                                <td><?php echo ucwords($test['test_name']); ?></td>
                                <td><?php echo "Rs.".$test['price']; ?></td>
								<td><?php echo "Rs.".$test['special_price']; ?></td>
                            </tr>
                            <?php
                            $cnt++;
                        } if (empty($test_list)) {
                            ?>
                            <tr>
                                <td colspan="3">No records found</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div><!-- /.col -->

            <!-- accepted payments column -->
            <?php /*    <div class="col-xs-6">
              <p class="lead">Payment Methods:</p>
              <img src="../../dist/img/credit/visa.png" alt="Visa"/>
              <img src="../../dist/img/credit/mastercard.png" alt="Mastercard"/>
              <img src="../../dist/img/credit/american-express.png" alt="American Express"/>
              <img src="../../dist/img/credit/paypal2.png" alt="Paypal"/>
              <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
              Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles, weebly ning heekya handango imeem plugg dopplr jibjab, movity jajah plickers sifteo edmodo ifttt zimbra.
              </p>
              </div><!-- /.col --> */ ?>

            <div class="col-xs-6 table-responsive">
                <p class="lead">Sample From Lab <small></small></p>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Credit</th>
                            <th>Debit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $cnt = 1;
                        foreach ($credit_list as $credit) {
                            ?>
                            <tr>
                                <td><?php echo $cnt; ?></td>
                                <td><?php if($credit['credit'] != ""){ echo "Rs.".$credit['credit']; }else{ echo "Rs.0"; } ?></td>
                                <td><?php if($credit['debit'] != ""){ echo "Rs.".$credit['debit']; }else{ echo "Rs.0"; } ?></td>
                            </tr>
                            <?php
                            $cnt++;
                        } if (empty($test_list)) {
                            ?>
                            <tr>
                                <td colspan="3">No records found</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div><!-- /.col -->
        </div><!-- /.row -->

        <!-- this row will not appear when printing -->
        <div class="row no-print">
<!--            <div class="col-xs-12">
                <a href="invoice-print.html" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
                <button class="btn btn-success pull-right"><i class="fa fa-credit-card"></i> Submit Payment</button>
                <button class="btn btn-primary pull-right" style="margin-right: 5px;"><i class="fa fa-download"></i> Generate PDF</button>
            </div>-->
        </div>
    </section><!-- /.content -->
    <div class="clearfix"></div>
</div><!-- /.content-wrapper -->

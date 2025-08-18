<style type="text/css">
      .bs-example{
        position: relative;
    }
    .typeahead, .tt-query, .tt-hint {
        font-size: 14px;
        height: 34px;
        line-height: 30px;
        outline: medium none;
        padding: 8px 12px;
        width: 270px;
    }
    .typeahead {
        background-color: #FFFFFF;
    }
    .typeahead:focus {
        border: 1px solid #3c8dbc;
    }
    .tt-query {
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset;
    }
    .tt-hint {
        color: #999999;
    }
    .tt-dropdown-menu {
        background-color: #FFFFFF;
        border: 1px solid rgba(0, 0, 0, 0.2);
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
        padding: 8px 0;
        width: 270px;
    }
    .tt-suggestion {
        line-height: 24px;
        padding: 3px 20px;
    }
    .tt-suggestion.tt-is-under-cursor {
        background-color: #337ab7;
        color: #FFFFFF;
    }
    .tt-suggestion p {
        margin: 0;
    }
</style>
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
    $('input.typeahead').typeahead({
        name: 'typeahead',
        autoselect: true,
        remote:'<?php echo base_url();?>wallet_master/get_sub_customer?key=%QUERY',
        limit : 10
    });
});
    </script>
    <script type="text/javascript">
      $(document).ready(function(){
        $(".bs-example").on('keyup', function(e){
       
    if(e.which == 13) { 
       
        $(".tt-suggestion:first-child", this).trigger('click');
    }
});
      })
      
    </script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Wallet History
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="javascript:void(0);"><i class="fa fa-dashboard"></i>Report</a></li>
            <li><a href="javascript:void(0);"><i class="fa fa-users"></i> Wallet History</a></li>
        </ol>
    </section>
	<?php 
    $typeahead = $_GET['typeahead'];

    $credit = $_GET['credit'];
    $debit = $_GET['debit'];
    $total = $_GET['total'];
    $date = $_GET['date'];

    ?>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <a style="float:right;" href='<?php echo base_url(); ?>wallet_master/export_csv?typeahead=<?php echo $typeahead; ?>&credit=<?php echo $credit; ?>&debit=<?php echo $debit; ?>&total=<?php echo $total; ?>&date=<?php echo $date; ?>' class="btn btn-primary btn-sm" ><i class="fa fa-download" ></i> <strong > Export To CSV</strong></a>
                        <a style="float:right;margin-right: 10px;" href='<?php echo base_url(); ?>wallet_master/wallet_update' class="btn btn-primary btn-sm" ><i class="fa fa-plus-circle" ></i>  <strong> Update Wallet</strong></a>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="widget">
                            <?php if (isset($success) != NULL) { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $success['0']; ?>
                                </div>
                            <?php } ?>
                        </div>
                        <?php /*
                          <?php $attributes = array('class' => 'form-horizontal', 'method' => 'get', 'role' => 'form'); ?>

                          <?php echo form_open('admin_master/admin_list', $attributes); ?>

                          <div class="col-md-3">
                          <input type="text" class="form-control" name="user" placeholder="Username" value="<?php if(isset($username) != NULL){ echo $username; } ?>" />
                          </div>
                          <div class="col-md-3">
                          <input type="text" class="form-control" name="email" placeholder="Email" value="<?php if(isset($email) != NULL){ echo $email; } ?>"/>
                          </div>
                          <input type="submit" value="Search" class="btn btn-primary btn-md">


                          </form>

                          <br>
                         */ ?>
						  <?php 
                        $name = $_GET['typeahead'];
                        ?>
						<div style="text-align:right;margin-bottom:50px;" class="box-tools">
                            <ul class="pagination pagination-sm no-margin pull-right">
                                <?php echo $links; ?>
                            </ul>
                        </div>
                        <div class="tableclass">
                            <form role="form" action="<?php echo base_url(); ?>wallet_master/account_history" method="get" enctype="multipart/form-data">
                                <table id="example3" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Customer Name</th>
                                            <th>Credit</th>
                                            <th>Credit For</th>
                                            <th>Debit</th>
                                            <th>Total</th>
                                            <th>Date</th>
                                            <th style="width:150px;">Action</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><span style="color:red;">*</span></td>
                                          <?php  /* <td>
                                                <select class="form-control chosen-select" tabindex="-1" data-placeholder="Select Customer" name="user">
                                                    <option value="">Select Customer</option>
                                                    <?php foreach ($customer as $cat) { ?>
                                                        <option value="<?php echo $cat['id']; ?>" <?php
                                                        if ($customerfk == $cat['id']) {
                                                            echo "selected";
                                                        }
                                                        ?> ><?php echo ucwords($cat['full_name']); ?>(<?php echo $cat['mobile']; ?>)</option>
                                                            <?php } ?>
                                                </select>
                                            </td> */ ?>
											
											 <td class="bs-example">
<!--                                                <select class="form-control chosen-select" tabindex="-1" data-placeholder="Select Customer" name="user">
                                                    <option value="">Select Customer</option>
                                                    <?php /*foreach ($customer as $cat) { ?>
                                                        <option value="<?php echo $cat['id']; ?>" <?php
                                                        if ($customerfk == $cat['id']) {
                                                            echo "selected";
                                                        }
                                                        ?> ><?php echo ucwords($cat['full_name']); ?>(<?php echo $cat['mobile']; ?>)</option>
                                                            <?php } */ ?>
                                                </select>-->
                                              <input type="text" name="typeahead" class="typeahead tt-query form-control" autocomplete="off" id="search_id"  spellcheck="false" placeholder="Enter Customer Name" value="<?php echo $name;?>"
                                                >
                                            </td>
											
                                            <td><input class="form-control" type="text" name="credit" placeholder="Credit" value="<?php
                                                if (isset($credit)) {
                                                    echo $credit;
                                                }
                                                ?>" /></td>
                                            <td></td>
                                            <td><input class="form-control" type="text" name="debit" placeholder="Debit" value="<?php
                                                if (isset($debit)) {
                                                    echo $debit;
                                                }
                                                ?>" /></td>
                                            <td><input class="form-control" type="text" name="total" placeholder="Total" value="<?php
                                                if (isset($total)) {
                                                    echo $total;
                                                }
                                                ?>" /></td>
                                            <td>
                                                <input type="text" name="date" placeholder="select date" class="form-control datepicker-input" id="date"  value="<?php
                                                if (isset($date)) {
                                                    echo $date;
                                                }
                                                ?>" />
                                            </td>
                                            <td>
                                                <input type="submit" name="search" class="btn btn-success" value="Search" />
												 <a href="<?php echo base_url(); ?>wallet_master/account_history?typeahead=&credit=&debit=&total=&date=" class="btn btn-success" id="reset_id">Reset</a>
                                            </td>
                                        </tr>
                                        <?php
                                        $cnt = $counts;
                                        foreach ($query as $row) {
                                            $cnt++;
                                            ?>

                                            <tr>
                                                <td><?php echo $cnt; ?></td>
                                                <td><?php echo ucwords($row['full_name']); 
                                                if(!empty($row["added_by_name"])){
                                                        echo "<br>(<small>Added By <b>".$row["added_by_name"]."</b></small>)";
                                                    } ?></td>
                                                <td><?php echo ucwords($row['credit']); ?></td>
                                                <td><?php
                                                    if ($row['type'] == 'Case Back') {
                                                        echo 'Cash Back';
                                                    } else if ($row['type'] == 'referral code') {
                                                        echo 'Refer';
                                                    } else {
                                                        echo '-';
                                                    }
                                                    ?></td>
                                                <td><?php echo ucwords($row['debit']); ?></td>
                                                <td><?php echo ucwords($row['total']); ?></td>
                                                <td><?php echo ucwords($row['created_time']); ?></td>
                                                <td></td>
                                            </tr>
                                        <?php } if ($cnt == '0') {
                                            ?>
                                            <tr>
                                                <td colspan="6">No records found</td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
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
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
<script  type="text/javascript">

    jQuery(".chosen-select").chosen({
        search_contains: true
    });
    //  $(".chosen-select-deselect").chosen({ allow_single_deselect: true });
    // $("#cid").chosen('refresh');

</script>
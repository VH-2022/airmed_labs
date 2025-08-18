
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" rel="stylesheet" />
<style>
    /* pending_job_detail responsive table */
    .pending_job_list_tbl {width: 100%; float: left;}
    .pending_job_list_tbl table {width: 100%; border-collapse: collapse; float: left;}
    .pending_job_list_tbl th {background-color: #e5e5e5; color: #3e3e3e; font-size: 16px; font-weight: 600; text-align: center; vertical-align: middle; border: 1px solid #b1b1b1;}
    .pending_job_list_tbl td, th {padding:2px 6px; border: 1px solid #ccc; text-align: left;}
    .pending_job_list_tbl td {padding: 4px 4px; font-size: 13px; color: #505050;} 
    @media (max-width: 980px) {
        .pending_job_list_tbl table, .pending_job_list_tbl thead, .pending_job_list_tbl tbody, .pending_job_list_tbl th, .pending_job_list_tbl td, .pending_job_list_tbl tr {display: block;}
        .pending_job_list_tbl thead tr {position: absolute; top: -9999px; left: -9999px;}
        .pending_job_list_tbl tr {border: 1px solid #ccc !important;}
        .pending_job_list_tbl td {border: none; border-bottom: 1px solid #eee; position: relative; padding-left: 60%; text-align: left;}
        .pending_job_list_tbl td:before {position: absolute; top: 6px; left: 6px; width: 45%; padding-right: 10px; white-space: nowrap;}
        .pending_job_list_tbl tr{margin-bottom:15px;}
        .table-responsive.pending_job_list_tbl{border:none !important;}
    }
    /* End pending_job_detail responsive table */
    .full_bg{background: rgba(0,0,0,0.3); width:100%; height:100%; float:left; padding:250px; position:fixed; z-index:9; top:0; bottom:0;}
    .full_bg .loader img{width:70px; height:70px;}
    .text_highlight:hover{
        text-decoration: underline;
        /*font-weight: bold;
        font-size: 12px;*/
    }	
</style>
<div class="full_bg" style="display:none;" id="loader_div">
    <div class="loader">
        <center><img src="http://static.heart.org/riskcalc/app/assets/img/loading.gif"></center>
    </div>
</div>
<section class="content-header">
    <h1>
        View Po Generate details
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li class="active">Po Generate details</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <?php if ($this->session->flashdata("success") != "") { ?>
                    <div class="alert alert-success alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <?php echo $this->session->flashdata("success"); ?>
                    </div>
                <?php } ?>
                <div class="box-body">
                    <div class="col-md-6">
                        <div class="form-group col-sm-12  pdng_0 mrgn_0">
                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Po Number : </label><?= $query[0]["ponumber"]; ?>
                        </div>
                        <div class="form-group col-sm-12  pdng_0 mrgn_0">
                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Branch Name : </label><?= $query[0]["branch_name"]; ?>
                        </div>
                        <div class="form-group col-sm-12  pdng_0 mrgn_0">
                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Vendor Name : </label><?= $query[0]["vendor_name"]; ?>
                        </div>
                        <div class="form-group col-sm-12  pdng_0 mrgn_0">
                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Remark : </label><?= $query[0]["remark"]; ?>
                        </div>
                        <div class="form-group col-sm-12  pdng_0 mrgn_0">
                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Created Date : </label><?php
                            $odate_time = explode(' ', $query[0]['creteddate']);
                            $odate = explode("-", $odate_time[0]);

                            $adddate = $odate[2] . '-' . $odate[1] . '-' . $odate[0] . ' ' . $odate_time[1];
                            echo $adddate;
                            ?>
                        </div>
                        <div class="form-group col-sm-12  pdng_0 mrgn_0">
                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Added By : </label><?= $query[0]["cretdby"]; ?>
                        </div>
                        <div class="form-group col-sm-12  pdng_0 mrgn_0">
                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Status : </label><?php
                            if ($query[0]["status"] == 1) {
                                echo "Completed";
                            } else if ($query[0]["status"] == 2) {
                                echo "Draft";
                            } else {
                                echo "Saved";
                            }
                            ?>
                        </div>
                    </div><!-- /.box -->
                    <div class="table-responsive pending_job_list_tbl"> <br><br>
                        <table class="table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Category</th>
                                    <th>Item</th>
                                    <th>NOS</th>
                                    <th>Qty.</th>
                                    <th>Rate</th>
                                    <th>Amount Rs.</th>
                                    <th>Discount(%)</th>
                                    <th>TAX(%)</th>
                                    <th>TOTAL PAYABLE</th>
                                </tr>
                            </thead>
                            <tbody id="selected_items">
                                <?php
                                $cnt = 0;
                                $totalprice = 0;
                                foreach ($poitenm as $row) {
                                    $cnt++;
                                    $itetype = $row["category_fk"];
                                    $whotype = "";
                                    if ($itetype == '1') {
                                        $whotype = "Stationary";
                                        $cat = "Stationary";
                                    }
                                    if ($itetype == '2') {
                                        $whotype = "Consumables";
                                        $cat = "Lab Consumables";
                                    }
                                    if ($itetype == '3') {
                                        $whotype = "Reagent";
                                        $cat = "Reagent";
                                    }
                                    ?>
                                    <tr>
                                        <td><?= $cnt ?></td>
                                        <td><?= $whotype ?></td>
                                        <td><?= $row["reagent_name"]; ?></td>
                                        <td><?= $row["itemnos"]; ?></td>
                                        <td><?= $row["itenqty"]; ?></td>
                                        <td><?= $row["peritemprice"]; ?></td>
                                        <td><?= $row["peritemprice"] * $row["itemnos"] * $row["itenqty"]; ?></td>
                                        <td><?= $row["itemdis"]; ?></td>
                                        <td><?= $row["itemtxt"]; ?></td>
                                        <td><?= $row["itemprice"]; ?></td>
                                    </tr>
                                    <?php
                                    $totalprice += $row["itemprice"];
                                }
                                ?>
                                <tr>
                                    <td colspan="8"></td>
                                    <td>Discount(%)</td>
                                    <td><?= $query[0]["discount"]; ?></td>
                                </tr>
                                <tr>
                                    <td colspan="8"></td>
                                    <td>Total Amount Rs.</td>
                                    <td><?= round($totalprice - ($totalprice * $query[0]["discount"] / 100)); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div> 
                    <?php if (!empty($sub_data)) { ?>
                        <div class="col col-sm-6">
                            <h2>Bill Details</h2>
                            <div class="form-group col-sm-12  pdng_0 mrgn_0">
                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Invoice No : </label><?php echo $sub_data[0]["invoice_no"]; ?>
                            </div>
                            <div class="form-group col-sm-12  pdng_0 mrgn_0">
                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Invoice Date : </label><?= date("d-m-Y", strtotime($sub_data[0]["invoice_date"])); ?>
                            </div>
                            <div class="form-group col-sm-12  pdng_0 mrgn_0">
                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Bill Amount : </label><?= $sub_data[0]["bill_amount"]; ?>
                            </div>
                            <?php if ($sub_data[0]["bill_copy"] != "") { ?>
                                <div class="form-group col-sm-12  pdng_0 mrgn_0">
                                    <label for="exampleInputFile" class="col-sm-3 pdng_0">Bill File : </label><?php
                                    $old_image = explode(" | ", $sub_data[0]["bill_copy"]);

                                    foreach ($old_image as $key => $val) {
                                        $ext = pathinfo($val, PATHINFO_EXTENSION);
                                        if ($ext == "pdf") {
                                            ?>
                                            <a href="<?php echo base_url(); ?>upload/bill/<?php echo $val; ?>" onclick="window.open(this.src)" style="width: 100px;cursor: pointer;" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>

                                        <?php } else { ?>
                                            <img src="<?php echo base_url(); ?>upload/bill/<?php echo $val; ?>" onclick="window.open(this.src)" style="width: 100px;cursor: pointer;border: 1px solid black;margin: 2px 2px 2px 2px">
                                        <?php
                                        }
                                    }
                                    ?>
                                </div>
                        <?php } ?>
                        </div>  
                    <?php } ?>
<?php if ($banmpay != null) { ?>
                        <div class="col col-sm-6">
                            <h2>Payment Details</h2>
                            <div class="form-group col-sm-12  pdng_0 mrgn_0">
                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Bank : </label><?php echo $banmpay[0]["bank_name"]; ?>
                            </div>
                            <div class="form-group col-sm-12  pdng_0 mrgn_0">
                                <label for="exampleInputFile" class="col-sm-3 pdng_0">NEFT : </label><?php echo $banmpay[0]["neft"]; ?>
                            </div>
                            <div class="form-group col-sm-12  pdng_0 mrgn_0">
                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Date : </label><?= date("d-m-Y", strtotime($banmpay[0]["paydate"])); ?>
                            </div>
                            <div class="form-group col-sm-12  pdng_0 mrgn_0">
                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Remark : </label><?php echo $banmpay[0]["remark"]; ?>
                            </div>
                        </div> 
<?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>

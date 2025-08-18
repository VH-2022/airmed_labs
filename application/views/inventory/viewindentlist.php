
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
        View Details Reagent
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li class="active">View Details Reagent</li>
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
                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Branch Name : </label><?php echo $query[0]['branch_name'];?>
                        </div>


                        <div class="form-group col-sm-12  pdng_0 mrgn_0">
                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Item: </label> 
                                <div class="col-sm-9 pdng_0">
                                            <?php 
                                            $Category = explode(",",$query[0]['Category']);
                                          $Reagent = explode(",",$query[0]['Reagent']);
                                            $quantity = explode(",",$query[0]['Quantity']);
                                        $cnt =0;
                                            foreach ($Category as $key => $all_merge) {
                                              ?>

                                                <b><?= ucfirst($all_merge . '<br>') ?></b>
                                                   
                                                <?php echo $Reagent[$cnt];?>&nbsp;&nbsp;&nbsp;<?php echo $quantity[$cnt];?><br>

                                            
                                            <?php   $cnt++; } ?>
                                </div>
                        </div>


                </div>
                   
                </div>
            </div>
        </div>
    </div>
</section>

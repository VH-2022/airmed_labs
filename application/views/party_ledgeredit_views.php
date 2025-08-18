<!-- Page Heading -->
<?php /* <script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script> */ ?>
<style>
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
        Party ledger edit
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
      <?php  /* <li><a href="<?php echo base_url(); ?>Branch_Master/Branch_list"><i class="fa fa-users"></i>Branch List</a></li> */ ?>
        <li class="active">Party ledger edit</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">

            <div class="box box-primary">

                <div class="box-body">
				
                        <div class="widget">
                           

                        </div>
                    <div class="col-md-6">
                        <!-- form start -->
                        <form role="form" action="<?php echo base_url()."branch_receive_payment/party_edit/".$paryinfo->id; ?>" method="post" >

                            <div class="form-group">
                                <label for="type">Party name</label><span style="color:red">*</span>
								<input type="text" name="partyname" class="form-control" value="<?= $paryinfo->party_name; ?>" />
                                <?php echo form_error('partyname'); ?>
                            </div>
							
                    </div>
                </div>

                <div class="box-footer">
                    <div class="col-md-6">
                        <button class="btn btn-primary" type="submit">Update</button>
                    </div>
                </div>
                </form>
            </div><!-- /.box -->

        </div>
    </div>
</section>
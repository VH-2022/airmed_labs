<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<section class="content-header">
    <h1>
      Client Print Permission Edit
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>b2b/Sample_print_master/sub_sample_list"><i class="fa fa-users"></i>Client Print Permission List</a></li>
        <li class="active">Edit Client Print Permission</li>
    </ol>
   
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">

            <div class="box box-primary">
                <p class="help-block" style="color:red;"><?php
                    if (isset($error)) {
                        echo $error;
                    }
                    ?></p>
 <?php if ($this->session->flashdata('duplicate')) { ?>
                                <div class="alert alert-danger alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $this->session->flashdata('duplicate'); ?>
                                </div>
                            <?php } ?>
                <!-- form start -->
                <form role="form" action="<?php echo base_url(); ?>b2b/Sample_print_master/sub_sample_edit/<?php echo $id;?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value='<?php echo $query[0]['id'];?>'>
                    <div class="box-body">
                        <div class="col-md-6">
                          <div class="form-group">
                                <label for="exampleInputFile">Select Client</label><span style="color:red">*</span>
                               <select class="form-control" name="client_fk"  tabindex="5"  >
							   <option value="">--Select--</option>
                                    <?php foreach($client as $val){ ?>
                                        
                              <option value="<?php echo $val['id']; ?>" <?php if($val['id'] == $query[0]["client_fk"]){ echo "selected"; } ?>><?php echo ucwords($val['name']); ?></option>
                                        <?php   } ?>
                              
							   </select>
                                <span style="color: red;"><?= form_error('client_fk'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Print Report Without Collect Payment</label>
                                <input type="checkbox" name="print_report" value="1" <?php if($query[0]['print_report'] == 1) { echo "checked"; } ?>>

                            
                            </div>
                          
                        </div>
                        
                    </div><!-- /.box-body -->

                    <div class="box-footer">
                        <div class="col-md-6">
                            <button class="btn btn-primary" type="submit">Add</button>
                        </div>
                    </div>

                </form>
            </div><!-- /.box -->
            <script  type="text/javascript">
                $(document).ready(function () {
                    $("#showHide").click(function () {
                        if ($("#password").attr("type") == "password") {
                            $("#password").attr("type", "text");
                        } else {
                            $("#password").attr("type", "password");
                        }

                    });
                });
            </script>
        </div>
    </div>
</section>
</div>
</div>
\
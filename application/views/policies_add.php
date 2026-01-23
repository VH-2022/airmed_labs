<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>

<section class="content-header">
    <h1>Policies & Programs</h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>Investor_master/policies_list">Policies List</a></li>
        <li class="active">Add</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">

                <?php if(isset($error)){ ?>
                    <p style="color:red;"><?php echo $error; ?></p>
                <?php } ?>

                <form role="form"
                      action="<?php echo base_url(); ?>Investor_master/policies_add"
                      method="post"
                      enctype="multipart/form-data">

                    <div class="box-body">

                        <div class="form-group">
                            <label>Title <span style="color:red">*</span></label>
                            <input type="text" name="title" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>PDF File <span style="color:red">*</span></label>
                            <input type="file" name="pdf" class="form-control" required>
                        </div>

                    </div>

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" style="float:right;">
                            Upload PDF
                        </button>
                        <a href="<?php echo base_url(); ?>Investor_master/policies_list" class="btn btn-default" style="float:right; margin-right:10px;" > Cancel </a>
                    </div>

                </form>

            </div>
        </div>
    </div>
</section>

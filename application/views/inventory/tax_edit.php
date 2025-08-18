<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<section class="content-header">
    <h1>
       Tax Master Edit
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>inventory/Tax_master/tax_list"><i class="fa fa-users"></i>Tax List</a></li>
        <li class="active">Edit Tax</li>
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
                <div class="box-body">

                    <div class="col-md-6">
                        <!-- form start -->
                        <form role="form" action="<?php echo base_url(); ?>inventory/Tax_master/edit/<?php echo $query[0]['id'];?>" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?php echo $query[0]['id'];?>">

                            <div class="form-group">
                                <label for="name">Title</label><span style="color:red">*</span>
                                <input type="text"  name="title" class="form-control" value="<?php echo $query[0]['title'];?>">
                                <span style="color:red;"> <?php echo form_error('title'); ?></span>
                            </div>
                              <div class="form-group">
                                <label for="name">Tax</label><span style="color:red">*</span>
                                <input type="text"  name="tax" class="form-control" value="<?php echo $query[0]['tax'];?>">
                                <span style="color:red;"><?php echo form_error('tax'); ?></span>
                            </div>
                                <div class="form-group">
                                <label>City</label>
                                <select name="city_fk" class="form-control">
                                    <option value="">Select City</option>
                                    <?php foreach($city_list as $key=>$val){ ?>
                                    <option value="<?php echo $val['city_fk'];?>" <?php if($query[0]['city_fk'] == $val['city_fk']){echo "selected ='selected'";}?>><?php echo $val['name'];?></option>

                                    <?php } ?>
                                </select>
                                  <span style="color:red;"><?php echo form_error('city_fk'); ?></span>
                            <div class="form-group">
                                <label for="name">Remark</label>
                                <textarea name="remark" class="form-control">
                                    <?php echo $query[0]['remark'];?>
                                </textarea>
                            </div>
                              
                            </div>
                            <button class="btn btn-primary" type="submit">Edit</button>

                        </form>
                    </div><!-- /.box -->
                </div>	
            </div>
        </div>
    </div>
</section>
</div>
</div>
<script type="text/javascript">
    window.setTimeout(function () {
        $(".alert").fadeTo(500, 0).slideUp(500, function () {
            $(this).remove();
        });
    }, 4000);
</script>
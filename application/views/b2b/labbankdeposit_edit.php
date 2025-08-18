<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<section class="content-header">
    <h1>
        Lab
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>b2b/Logistic/lab_list"><i class="fa fa-users"></i>Lab List</a></li>
        <li class="active">Add Edit</li>
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

                <!-- form start -->
                <form role="form" action="<?php echo base_url(); ?>b2b/Lab_bankdeposit/lab_edit/<?= $id; ?>" method="post" enctype="multipart/form-data">

                    <div class="box-body">
                        <div class="col-md-6">
                            <!--<?= validation_errors('<div class="alert alert-danger alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>', '</div>'); ?>-->


                            <div class="form-group">
                                <label for="exampleInputFile">Lab</label><span style="color:red">*</span>
                                <select class="form-control" name="lab_fk" tabindex="5" disabled="">
                                    <option value="">--Select--</option>
                                    <?php foreach ($branch as $city) { ?>

                                        <option value="<?= $city['id']; ?>" <?php
                                        if ($city['id'] == $query[0]["lab_fk"]) {
                                            echo "selected";
                                        }
                                        ?>  ><?= $city['name']; ?></option>
                                            <?php } ?>
                                </select>
                                <span style="color: red;"><?= form_error('lab_fk'); ?></span>
                            </div>


                            <div class="form-group">
                                <label for="exampleInputFile">Bank Name</label><span style="color:red">*</span>
                                <input type="text"  name="bank_name" tabindex="11"  class="form-control"  value="<?php echo $query[0]["bank_name"]; ?>" >
                                <span style="color: red;"><?= form_error('bank_name'); ?></span>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputFile">A/c No</label><span style="color:red">*</span>
                                <input type="text"  name="ac_no" tabindex="11"  class="form-control"  value="<?php echo $query[0]["ac_no"]; ?>" >
                                <span style="color: red;"><?= form_error('ac_no'); ?></span>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputFile">IFSC Code</label><span style="color:red">*</span>
                                <input type="text"  name="ifsc_no" tabindex="11"  class="form-control"  value="<?php echo $query[0]["ifsc_no"]; ?>" >
                                <span style="color: red;"><?= form_error('ifsc_no'); ?></span>
                            </div>



                            <div class="form-group">
                                <label for="exampleInputFile">Message</label><span style="color:red">*</span>
                                <textarea id="editor1" name="message"><?php echo $query[0]["message"]; ?> </textarea>
                                <span style="color: red;"><?= form_error('editor1'); ?></span>
                            </div>



                        </div>

                        <div class="col-md-6">
                            <!--<?= validation_errors('<div class="alert alert-danger alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>', '</div>'); ?>-->
                            <div class="form-group">
                                <label for="exampleInputFile">PAN</label><span style="color:red">*</span>
                                <input type="text"  name="pan" tabindex="2" class="form-control"  value="<?php echo $query[0]["pan"]; ?>" >
                                <span style="color: red;"><?= form_error('pan'); ?></span>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputFile">Branch</label>
                                <input type="text"  name="branch" tabindex="4" class="form-control"  value="<?php echo $query[0]["branch"]; ?>" >
                                <span style="color: red;"><?= form_error('Branch'); ?></span>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputFile">MICR No</label><span style="color:red">*</span>
                                <input type="text"  name="micr_no" tabindex="6" class="form-control"  value="<?php echo $query[0]["micr_no"]; ?>" >
                                <span style="color: red;"><?= form_error('micr_no'); ?></span>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputFile">GSTIN</label><span style="color:red">*</span>
                                <input type="text"  name="gstin" tabindex="6" class="form-control"  value="<?php echo $query[0]["gstin"]; ?>" >
                                <span style="color: red;"><?= form_error('gstin'); ?></span>
                            </div>





                        </div>
                    </div><!-- /.box-body -->

                    <div class="box-footer">
                        <div class="col-md-6">
                            <button class="btn btn-primary" type="submit">Update</button>
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

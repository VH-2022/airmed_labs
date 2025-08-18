<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<section class="content-header">
    <h1>
        Machine Edit
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>inventory/Machine/machine_list"><i class="fa fa-users"></i>Machine List</a></li>
        <li class="active">Machine Edit</li>
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
                        <form role="form" action="<?php echo base_url(); ?>inventory/machine/machine_edit/<?= $eid; ?>" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="name">Name</label><span style="color:red">*</span>
                                <input type="text"  name="name" value="<?php echo $query[0]['name']; ?>" class="form-control">
                                <?php echo form_error('name'); ?>
                            </div>
                            <input type="hidden" name="id" value="<?= $eid; ?>"/>
                            <div class="form-group">
                                <label for="name">Branch</label>
                                <select class="multiselect-ui form-control" id="branch" title="Select Branch" multiple="multiple" name="branch[]">
                                    <?php foreach ($branchlist as $bkey) { ?>
                                        <option value="<?= $bkey["id"] ?>" <?php
                                        if (in_array($bkey["id"], explode(',', $assign_branch[0]["branch"]))) {
                                            echo "selected";
                                        }
                                        ?>><?= $bkey["branch_name"] ?></option>
                                            <?php } ?>
                                </select>
                                <?php echo form_error('branch[]'); ?>
                            </div>
                            <button class="btn btn-primary" type="submit">Update</button>

                        </form>
                    </div><!-- /.box -->
                </div>	
            </div>
        </div>
</section>
<script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap-multiselect.js"></script>
<Script>
    $(function () {
        $('.multiselect-ui').multiselect({
            includeSelectAllOption: true,
            nonSelectedText: 'Select Branch'
        });
    });
    $(function () {
        $('.chosen').chosen();
    });
    jQuery(".chosen-select").chosen({
        search_contains: true
    });
</script>
<script type="text/javascript">
    window.setTimeout(function () {
        $(".alert").fadeTo(500, 0).slideUp(500, function () {
            $(this).remove();
        });
    }, 4000);
</script>
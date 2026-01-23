<section class="content-header">
    <h1>Add Others PDF</h1>
    <ol class="breadcrumb">
        <li>
            <a href="<?php echo base_url(); ?>Dashboard">
                <i class="fa fa-dashboard"></i> Dashboard
            </a>
        </li>
        <li class="active">Add Others</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">

                <div class="box-header">
                    <h3 class="box-title">Add Others PDF</h3>
                </div>

                <div class="box-body">
                    <form method="post" enctype="multipart/form-data">

                        <div class="form-group">
                            <label>PDF Title</label>
                            <input type="text"
                                   name="pdf_title"
                                   class="form-control"
                                   placeholder="Enter PDF title"
                                   required>
                        </div>

                        <div class="form-group">
                            <label>Upload PDF</label>
                            <input type="file"
                                   name="pdf"
                                   class="form-control"
                                   accept="application/pdf"
                                   required>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Save
                        </button>

                        <a href="<?= base_url('Investor_master/others_list'); ?>"
                           class="btn btn-default">
                            Cancel
                        </a>

                    </form>
                </div>

            </div>
        </div>
    </div>
</section>

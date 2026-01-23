<section class="content-header">
    <h1>Edit Others PDF</h1>
    <ol class="breadcrumb">
        <li>
            <a href="<?php echo base_url(); ?>Dashboard">
                <i class="fa fa-dashboard"></i> Dashboard
            </a>
        </li>
        <li class="active">Edit Others</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">

                <div class="box-header">
                    <h3 class="box-title">Edit Others PDF</h3>
                </div>

                <div class="box-body">
                    <form method="post" enctype="multipart/form-data">

                        <div class="form-group">
                            <label>PDF Title</label>
                            <input type="text"
                                   name="pdf_title"
                                   value="<?= $row['pdf_title']; ?>"
                                   class="form-control"
                                   required>
                        </div>

                        <div class="form-group">
                            <label>Current PDF</label><br>
                            <a href="<?= base_url('upload/others/'.$row['pdf_path']); ?>"
                               target="_blank">
                                View Current PDF
                            </a>
                        </div>

                        <div class="form-group">
                            <label>Change PDF (Optional)</label>
                            <input type="file"
                                   name="pdf"
                                   class="form-control"
                                   accept="application/pdf">
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Update
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

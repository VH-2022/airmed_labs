<section class="content-header">
    <h1>Edit Stock Exchange Disclosures</h1>
</section>

<section class="content">
    <div class="box box-primary">

        <form method="post" enctype="multipart/form-data">

            <div class="box-body">

                <div class="form-group">
                    <label>Title <span style="color:red">*</span></label>
                    <input type="text" name="title" class="form-control"
                        value="<?= $category[0]['title']; ?>">
                    <span style="color:red"><?= form_error('title'); ?></span>
                </div>

                <hr>

                <h4>Existing PDFs</h4>

                <table class="table table-bordered">
                    <tr>
                        <th>No</th>
                        <th>PDF Title</th>
                        <th>View</th>
                    </tr>

                    <?php $i = 1;
                    foreach ($files as $f) { ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= $f['pdf_title']; ?></td>
                            <td>
                                <a target="_blank"
                                    href="<?= base_url('upload/stock_exchange/' . $f['pdf_path']); ?>">
                                    View PDF
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </table>

                <hr>

                <h4>Add More PDFs</h4>

                <div id="pdfWrapper">

                    <div class="row pdfRow">
                        <div class="col-md-6">
                            <label>PDF Title</label>
                            <input type="text" name="pdf_title[]" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label>PDF File</label>
                            <input type="file" name="pdf[]" class="form-control">
                        </div>
                    </div>

                </div>

            </div>

            <div class="box-footer">
                <button type="button" class="btn btn-info" onclick="addMore()">+ Add More</button>
                <button class="btn btn-primary" style="float:right;">Update</button>
                <a href="<?php echo base_url(); ?>Investor_master/stock_exchange_list" class="btn btn-default" style="float:right; margin-right:10px;" > Cancel </a>
            </div>

        </form>

    </div>
</section>

<script>
    function addMore() {
        var html = document.querySelector('.pdfRow').outerHTML;
        document.getElementById('pdfWrapper').insertAdjacentHTML('beforeend', html);
    }
</script>
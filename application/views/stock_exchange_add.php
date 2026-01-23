<section class="content-header">
    <h1>Add Stock Exchange Disclosures</h1>
</section>

<section class="content">
    <div class="box box-primary">

        <form method="post" enctype="multipart/form-data">

            <div class="box-body">

                <div class="form-group">
                    <label>Title <span style="color:red">*</span></label>
                    <input type="text" name="title" class="form-control" value="<?= set_value('title'); ?>">
                    <span style="color:red"><?= form_error('title'); ?></span>
                </div>

                <hr>

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
                <button class="btn btn-primary" style="float:right;">Save</button>
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
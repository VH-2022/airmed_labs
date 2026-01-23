<div class="content-wrapper">
    <section class="content-header">
        <h1>RHP</h1>
    </section>

    <section class="content">
        <div class="box box-primary">


            <form method="post" enctype="multipart/form-data">

                <div class="box-body">
                    <?php if (isset($success[0])) { ?>
                    <div class="alert alert-success alert-dismissable">
                        <button class="close" data-dismiss="alert">×</button>
                        <?= $success[0]; ?>
                    </div>
                <?php } ?>
                    <div class="form-group">
                        <label>RHP Content</label>
                        <textarea name="content" id="editor" class="form-control" rows="10">
                        <?= isset($rhp['content']) ? $rhp['content'] : ''; ?>
                        </textarea>
                    </div>

                    <div class="form-group">
                        <label>Upload PDF</label>
                        <input type="file" name="pdf_file" class="form-control">
                    </div>

                    <?php if (!empty($rhp['pdf_file'])) { ?>
                        <p>
                            Current PDF:
                            <a target="_blank" href="<?= base_url('upload/rhp/' . $rhp['pdf_file']); ?>">
                                View PDF
                            </a>
                        </p>
                    <?php } ?>

                </div>

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Save</button>

                </div>

            </form>

        </div>
    </section>
</div>

<!-- CKEditor -->
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('editor');
</script>
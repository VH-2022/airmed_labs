<section class="content-header">
    <h1>Add Stock Exchange</h1>
</section>

<section class="content">
    <div class="box box-primary">

        <div class="box-body">
            <form method="post">

                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Address</label>
                    <textarea name="address" class="form-control" required></textarea>
                </div>

                <div class="form-group">
                    <label>Symbol</label>
                    <input type="text" name="symbol" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save"></i> Save
                </button>

                <a href="<?= base_url('Investor_master/stock_list'); ?>" class="btn btn-default">Cancel</a>

            </form>
        </div>
    </div>
</section>
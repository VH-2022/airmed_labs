<section class="content-header">
    <h1>Edit Stock Exchange</h1>
</section>

<section class="content">
    <div class="box box-primary">

        <div class="box-body">
            <form method="post">

                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" value="<?= $row['name']; ?>" class="form-control">
                </div>

                <div class="form-group">
                    <label>Address</label>
                    <textarea name="address" class="form-control"><?= $row['address']; ?></textarea>
                </div>

                <div class="form-group">
                    <label>Symbol</label>
                    <input type="text" name="symbol" value="<?= $row['symbol']; ?>" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save"></i> Save
                </button>

                <a href="<?= base_url('Investor_master/stock_list'); ?>" class="btn btn-default">Cancel</a>

            </form>
        </div>
    </div>
</section>
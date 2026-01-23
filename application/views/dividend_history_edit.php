<section class="content-header">
    <h1>Edit Dividend History</h1>
</section>

<section class="content">
<div class="row">
<div class="col-md-12">
<div class="box box-primary">

<form method="post">

<div class="box-body">
<div class="row">

<div class="col-md-4">
    <div class="form-group">
        <label>Financial Year</label>
        <input type="text" name="year"
               value="<?= $row['year']; ?>"
               class="form-control" required>
    </div>
</div>

<div class="col-md-4">
    <div class="form-group">
        <label>Dividend Type</label>
        <select name="dividend_type" class="form-control" required>
            <option value="Interim" <?= ($row['type']=="Interim")?"selected":""; ?>>Interim</option>
            <option value="2nd Interim" <?= ($row['type']=="2nd Interim")?"selected":""; ?>>2nd Interim</option>
            <option value="Final" <?= ($row['type']=="Final")?"selected":""; ?>>Final</option>
        </select>
    </div>
</div>

<div class="col-md-4">
    <div class="form-group">
        <label>Amount (₹)</label>
        <input type="number" step="0.01" name="amount"
               value="<?= $row['amount']; ?>"
               class="form-control" required>
    </div>
</div>

</div>
</div>

<div class="box-footer">
    <button class="btn btn-primary" style="float:right;" type="submit">Update</button>
    <a href="<?php echo base_url(); ?>Investor_master/dividend_history_list" class="btn btn-default" style="float:right; margin-right:10px;" > Cancel </a>

</div>

</form>

</div>
</div>
</div>
</section>

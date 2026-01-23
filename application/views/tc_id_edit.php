<section class="content-header">
    <h1>Edit T & C of Appointment of ID</h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>Investor_master/tc_id_list">T & C List</a></li>
        <li class="active">Add</li>
    </ol>
</section>

<section class="content">
    <div class="box box-primary">
        <form action="<?= base_url('Investor_master/tc_id_edit/'.$row['id']); ?>"
              method="post" enctype="multipart/form-data">

            <div class="box-body">

                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control"
                           value="<?= $row['title']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Current PDF</label><br>
                    <a href="<?= base_url('upload/tc_id/'.$row['pdf_file']); ?>" target="_blank">
                        View PDF
                    </a>
                </div>

                <div class="form-group">
                    <label>Upload New PDF (Optional)</label>
                    <input type="file" name="pdf" class="form-control">
                </div>

            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="<?= base_url('Investor_master/tc_id_list'); ?>" class="btn btn-default">
                    Cancel
                </a>
            </div>

        </form>
    </div>
</section>

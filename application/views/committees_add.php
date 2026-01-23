<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>

<section class="content-header">
    <h1>Committees</h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>Investor_master/committees_list">Committees List</a></li>
        <li class="active">Add Committee</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">

                <form role="form"
                      action="<?php echo base_url(); ?>Investor_master/committees_add"
                      method="post">

                    <div class="box-body">
                        <div class="row">

                            <!-- Committee Name -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Committee Name <span style="color:red">*</span></label>
                                    <input type="text" name="committee_name" class="form-control"
                                           value="<?php echo set_value('committee_name'); ?>" required>
                                    <span style="color:red;"><?= form_error('committee_name'); ?></span>
                                </div>
                            </div>

                            <!-- Members -->
                            <div class="col-md-12">
                                <h4>Committee Members</h4>

                                <div id="member-wrapper">

                                    <div class="member-row">
                                        <div class="row">

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Member Name</label>
                                                    <input type="text" name="member_name[]" class="form-control" required>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Designation</label>
                                                    <input type="text" name="designation[]" class="form-control" required>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Role</label>
                                                    <input type="text" name="role[]" class="form-control" required>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>&nbsp;</label><br>
                                                    <button type="button" class="btn btn-danger remove-member">
                                                        Remove
                                                    </button>
                                                </div>
                                            </div>

                                        </div>
                                        <hr>
                                    </div>

                                </div>

                                <button type="button" class="btn btn-success" id="add-more">
                                    + Add More
                                </button>
                            </div>

                        </div>
                    </div>

                    <div class="box-footer">
                        <div class="col-md-12">
                            <button class="btn btn-primary" style="float:right;" type="submit">
                                Add Committee
                            </button>
                            <a href="<?php echo base_url(); ?>Investor_master/committees_list" class="btn btn-default"  style="float:right; margin-right:10px;" >
                                Cancel
                            </a>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
</section>

<script>
$("#add-more").click(function(){
  $("#member-wrapper").append(`
    <div class="member-row">
      <div class="row">

        <div class="col-md-3">
          <div class="form-group">
            <label>Member Name</label>
            <input type="text" name="member_name[]" class="form-control" required>
          </div>
        </div>

        <div class="col-md-3">
          <div class="form-group">
            <label>Designation</label>
            <input type="text" name="designation[]" class="form-control" required>
          </div>
        </div>

        <div class="col-md-3">
          <div class="form-group">
            <label>Role</label>
            <input type="text" name="role[]" class="form-control" required>
          </div>
        </div>

        <div class="col-md-3">
          <div class="form-group">
            <label>&nbsp;</label><br>
            <button type="button" class="btn btn-danger remove-member">Remove</button>
          </div>
        </div>

      </div>
      <hr>
    </div>
  `);
});

$(document).on("click",".remove-member",function(){
  $(this).closest(".member-row").remove();
});
</script>

<div class="content-wrapper">
  <style>
.desc-limit {
  max-width: 400px;
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  white-space: normal;
}
</style>
  <section class="content-header">
      <h1>
        Team List
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>home"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li class="active">Team List</li>
      </ol>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box box-primary">
          <div class="box-header">
            <h3 class="box-title">Team List</h3>
            <a style="float:right;" href='<?php echo base_url(); ?>TeamMaster/add' class="btn btn-primary btn-sm" ><i class="fa fa-plus-circle" ></i><strong > Add</strong></a>
          </div>
          <div class="box-body">
            <div class="widget">
              <?php if (isset($success) != NULL) { ?>
                  <div class="alert alert-success alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                      <?php echo $success['0']; ?>
                  </div>
              <?php } ?>
					  </div>
        	  <br>
            <div class="tableclass">
              <table id="example4" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Designation</th>
                    <th>Description</th>
						        <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($query)) { ?>
                      <?php $cnt = 1; foreach ($query as $row) { ?>
                          <tr>
                              <td><?php echo $cnt; ?></td>

                              <td>
                                  <?php if (!empty($row['image'])) { ?>
                                      <img src="<?php echo base_url(); ?>upload/team/<?php echo $row['image']; ?>"
                                          alt="Profile Pic" style="width:50px; height:40px;"/>
                                  <?php } else { ?>
                                      <span>No Image</span>
                                  <?php } ?>
                              </td>

                              <td><?php echo $row['title']; ?></td>
                              <td><?php echo $row['designation']; ?></td>
                              <td>
                              <div class="desc-limit"
                                  data-toggle="tooltip"
                                  data-placement="top"
                                  title="<?php echo strip_tags($row['desc']); ?>">
                                <?php echo $row['desc']; ?>
                              </div>
                            </td>

                              <td>
                                  <a href='<?php echo base_url(); ?>TeamMaster/edit/<?php echo $row['id']; ?>'
                                    data-toggle="tooltip" data-original-title="Edit">
                                    <i class="fa fa-edit"></i>
                                  </a>

                                  <a href='<?php echo base_url(); ?>TeamMaster/delete/<?php echo $row['id']; ?>'
                                    data-toggle="tooltip" data-original-title="Remove"
                                    onclick="return confirm('Are you sure you want to remove this data?');">
                                    <i class="fa fa-trash-o"></i>
                                  </a>
                              </td>
                          </tr>
                      <?php $cnt++; } ?>
                  <?php } else { ?>
                      <tr>
                          <td colspan="6" style="text-align:center; color:red; font-weight:bold;">
                              No Team Data Found
                          </td>
                      </tr>
                  <?php } ?>
                  </tbody>
              </table>
            </div>
            <div style="text-align:right;" class="box-tools">
					    <ul class="pagination pagination-sm no-margin pull-right">
					      <?php echo $links;?>
						  </ul>
				    </div>
				  </div>
        </div>
      </div>
    </div>
  </section>
</div>
<script>
$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();
});
</script>
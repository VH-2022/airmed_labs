
          <section class="content-header">
            <h1>
              Edit Profile
              <small></small>
            </h1>
            <ol class="breadcrumb">
          <li><a href="<?php echo base_url()."reception/dashboard"; ?>"><i class="fa fa-dashboard"></i>Home</a></li>
              <li class="active">Edit Profile</li>
            </ol>
          </section>
          <section class="content">
          	<div class="row">
          		<div class="col-md-12">
             
              <div class="box box-primary">
               
                	<div class="box-body">
                            <?php if ($this->session->flashdata('success')) { ?>
                                <div class="alert alert-success alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $this->session->flashdata('success'); ?>
                                </div>
                            <?php } ?>
                        
					</div>
              
                <form role="form" action="<?php echo base_url()."reception/updateprofile"; ?>" method="post" >
                  <div class="box-body">
                    <div class="col-md-6">
						
                    <div class="form-group">
                      <label for="exampleInputFile">Name</label><span style="color:red">*</span>
                      <input type="text"  name="username" class="form-control" value="<?php echo $user->name; ?>">
                       <span style="color: red;"><?= form_error('username'); ?></span>
                    </div>
                    
					 <div class="form-group">
                      <label for="exampleInputFile">Mobile</label><span style="color:red">*</span>
                      <input type="text"  name="mobile" class="form-control" value="<?php echo $user->mobile; ?>">
                       <span style="color: red;"><?= form_error('mobile'); ?></span>
                    </div>
					
				
                    </div>
                  </div><!-- /.box-body -->

                  <div class="box-footer">
                    <button class="btn btn-primary" type="submit">Submit</button>
                  </div>
                </form>
              </div><!-- /.box -->
  

            </div>
          	</div>
			 </section>
			 
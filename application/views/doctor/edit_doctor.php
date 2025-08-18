
          <section class="content-header">
            <h1>
              Edit Profile
              <small></small>
            </h1>
            <ol class="breadcrumb">
           <?php /*  <li><a href=""><i class="fa fa-user"></i>Doctor Profile</a></li> */ ?>
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
              
                <form role="form" action="<?php echo base_url()."doctor/updateprofile"; ?>" method="post" >
                  <div class="box-body">
                    <div class="col-md-6">
						
                    <div class="form-group">
                      <label for="exampleInputFile">Name</label><span style="color:red">*</span>
                      <input type="text"  name="username" class="form-control" value="<?php echo $user->full_name; ?>">
                       <span style="color: red;"><?= form_error('username'); ?></span>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputFile">Email</label><span style="color:red">*</span>
                      <input type="email"  name="email" class="form-control" value="<?php echo $user->email; ?>">
                       <span style="color: red;"><?= form_error('email'); ?></span>
                    </div>
					 <div class="form-group">
                      <label for="exampleInputFile">Mobile</label><span style="color:red">*</span>
                      <input type="text"  name="mobile" class="form-control" value="<?php echo $user->mobile; ?>">
                       <span style="color: red;"><?= form_error('mobile'); ?></span>
                    </div>
					
				<?php /*  <div class="form-group">
                      <label for="exampleInputFile">State</label><span style="color:red">*</span>
                      <select class="form-control" name="state" onchange="state_list(this.value)">
                          <option value="">--Select--</option>
                          <?php foreach ($state_list as $state) { ?>
                          <option value="<?php echo $state['id']; ?>" <?php if($state['id'] == $query[0]['state']) { echo 'selected'; } ?>><?php echo $state['state_name'] ?></option>
                          <?php } ?>
                      </select>
                      <span style="color: red;"><?=form_error('state');?></span>
                    </div>
                                                <div class="form-group">
                      <label for="exampleInputFile">City</label><span style="color:red">*</span>
                      <select class="form-control" name="city" id="city_list">
                          <option value="">--Select--</option>
                          <?php foreach ($city_list as $city) { ?>
                          <option value="<?php echo $city['id']; ?>" <?php if($city['id'] == $query[0]['city']) { echo 'selected'; } ?>><?php echo $city['city_name'] ?></option>
                          <?php } ?>
                      </select>
                      <span style="color: red;"><?=form_error('city');?></span>
                    </div> */ ?>
                            <div class="form-group">
                                <label for="exampleInputFile">Address</label>
                                <textarea name="address" class="form-control"> <?php echo  $user->address; ?></textarea>
                                <span style="color: red;"><?= form_error('address'); ?></span>
                            </div>
                    
					<?php /* <div class="form-group">
								<input type="checkbox" name="chng_pwd" value="1" class="minimal" id="chngPwd"/> Change Password  
								
                    </div>
                    <div class="form-group" style="display:none;" id="change_div">
							<label for="exampleInputFile">Password</label>
                      <input type="password"  name="password" id="password" class="form-control" value="<?php echo $user->password; ?>">
                                 			<input type="checkbox" id="showHide"> Show Password
                    </div> */ ?>
                    </div>
                  </div><!-- /.box-body -->

                  <div class="box-footer">
                    <button class="btn btn-primary" type="submit">Submit</button>
                  </div>
                </form>
              </div><!-- /.box -->
<script  type="text/javascript">
 $(document).ready(function () {
 $("#showHide").click(function () {
 if ($("#password").attr("type")=="password") {
 $("#password").attr("type", "text");
 }
 else{
 $("#password").attr("type", "password");
 }
 
 });
 
 $("#chngPwd").change(function(){
	if($(this).is(":checked")) {
		$("#change_div").css("display","block");
	}else {
		$("#change_div").css("display","none");
	}
	
});

 });
</script>
              
               

            </div>
          	</div>
			 </section>
			 
<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<section class="content-header">
    <h1>
        Lab
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>b2b/Logistic/lab_list"><i class="fa fa-users"></i>Lab List</a></li>
        <li class="active">Add Edit</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">

            <div class="box box-primary">
                <p class="help-block" style="color:red;"><?php
                    if (isset($error)) {
                        echo $error;
                    }
                    ?></p>

                <!-- form start -->
                <form role="form" action="<?php echo base_url(); ?>b2b/Logistic/lab_edit/<?= $id; ?>" method="post" enctype="multipart/form-data">

                    <div class="box-body">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputFile">Select PLM</label><span style="color:red">*</span>
                               <select class="form-control" name="branch"  tabindex="5"  >
							   <option value="">--Select--</option>
							   <?php 
							   $selbra=$labbranch[0]["branch_fk"];
							   foreach($branchall  as $bra){ ?>
							    
							   <option value="<?= $bra['id']; ?>"  <?php if($selbra==$bra['id']){ echo "selected"; } ?> ><?= $bra['branch_name']; ?></option>
							   <?php } ?>
							   </select	>
                                <span style="color: red;"><?= form_error('branch'); ?></span>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputFile">Select Sales</label><span style="color:red">*</span>
                               <select class="form-control" name="sales_fk"  tabindex="6"  >
                               <option value="">--Select--</option>
                               <?php 
                               $selbra=$query[0]["sales_fk"];

                               foreach($sales  as $sal){ ?>
                                
                               <option value="<?= $sal['id']; ?>"  <?php if($selbra==$sal['id']){ echo "selected"; } ?> ><?= ucfirst($sal['first_name'].' '.$sal['last_name']); ?></option>
                               <?php } ?>
                               </select >
                                <span style="color: red;"><?= form_error('branch'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Lab Name</label><span style="color:red">*</span>
                                <input type="text"  name="lab_name" tabindex="1" class="form-control"  value="<?php echo $query[0]["name"]; ?>" >
                                <span style="color: red;"><?= form_error('lab_name'); ?></span>
                            </div>
                     
                            <div class="form-group">
                                <label for="exampleInputFile">Mobile Number</label><span style="color:red">*</span>
                                <input type="text"  name="mobile_number" tabindex="3" class="form-control"  value="<?php echo $query[0]["mobile_number"]; ?>" >
                                <span style="color: red;"><?= form_error('mobile_number'); ?></span>
                            </div>
                      
                            <div class="form-group">
                                <label for="exampleInputFile">City</label><span style="color:red">*</span>
                               <select class="form-control" name="city" tabindex="5"  >
							   <option value="">--Select--</option>
							   <?php foreach($cityall  as $city){ ?>
							    
							   <option value="<?= $city['id']; ?>" <?php if($city['id']==$query[0]["city"]){ echo "selected"; } ?>  ><?= $city['name']; ?></option>
							   <?php } ?>
							   </select>
                                <span style="color: red;"><?= form_error('city'); ?></span>
                            </div>
							
                     
                            <div class="form-group">
                                <label for="exampleInputFile">Email</label><span style="color:red">*</span>
                                <input type="text"  name="email" tabindex="7" class="form-control"  value="<?php echo $query[0]["email"]; ?>" >
                           
                            </div     <span style="color: red;"><?= form_error('email'); ?></span>
                          
                            <div class="form-group">
                                <label for="exampleInputFile">Address</label>
                                <textarea name="address" tabindex="9" class="form-control" ><?php echo $query[0]["address"]; ?></textarea>
                                <span style="color: red;"><?= form_error('address'); ?></span>
                            </div>
                  
                      
                             <div class="form-group">
                                <label for="exampleInputFile">Business Expected</label>
                                <input type="text"  name="bus_expeted" tabindex="11"  class="form-control"  value="<?php echo $query[0]["bus_expeted"]; ?>" >
                                <span style="color: red;"><?= form_error('bus_expeted'); ?></span>
                            </div>
                            
                             <div class="form-group">
                                        <label for="exampleInputFile" class="col-sm-3 pdng_0">Identify Document :</label>
                                        <div class="col-sm-9 pdng_0">
                                            <input type="file" name="file" tabindex="13" >
                                            <span style="color:red;" id="logistic_error"></span>
                                            
                                        </div>
										<?php if($query1[0]["dock_name"] != ""){ ?>
                                         <img src="<?= base_url()."upload/lab/".$query1[0]["dock_name"] ?>" width="100" height="100" ></img>
										<?php } ?>
                                      
                                       
                            </div>
                            
                            <div class="form-group">
                                        <label for="exampleInputFile" class="col-sm-3 pdng_0">Address Proof Document:</label>
                                        <div class="col-sm-9 pdng_0" tabindex="14" >
                                            <input type="file" name="upload_pic" mutiple>
                                            <span style="color:red;" id="logistic_error"></span>
                                        </div>
										<?php if($query1[0]["address_proof"] != ""){ ?>
                                        <img src="<?= base_url()."upload/lab/".$query1[0]["address_proof"] ?>" width="100" height="100" ></img>
										<?php } ?>
                            </div>
                       
                        </div>
                        
                         <div class="col-md-6">
                            <!--<?= validation_errors('<div class="alert alert-danger alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>', '</div>'); ?>-->
                             <div class="form-group">
                                <label for="exampleInputFile">Contact Person Name</label>
                                <input type="text"  name="person_name" tabindex="2" class="form-control"  value="<?php echo $query[0]["contact_person_name"]; ?>" >
                                <span style="color: red;"><?= form_error('person_name'); ?></span>
                            </div>
                            
                            <div class="form-group">
                                <label for="exampleInputFile">Alternate Number</label>
                                <input type="text"  name="alternate_number" tabindex="4" class="form-control"  value="<?php echo $query[0]["alternate_number"]; ?>" >
                                <span style="color: red;"><?= form_error('alternate_number'); ?></span>
                            </div>
                            
                            <div class="form-group">
                                <label for="exampleInputFile">Test discount (percentage)</label><span style="color:red">*</span>
                                <input type="text"  name="test_discount" tabindex="6" class="form-control"  value="<?php echo $query[0]["test_discount"]; ?>" >
                                <span style="color: red;"><?= form_error('test_discount'); ?></span>
                            </div>
                            
                             <div class="form-group">
                                <label for="exampleInputFile">Password</label><span style="color:red">*</span>
                                <input type="password"  name="password" tabindex="8" class="form-control"  value="<?php echo $query[0]["password"]; ?>" >
                                <span style="color: red;"><?= form_error('password'); ?></span>
                            </div>
                            
                            <div class="form-group">
                                <label for="exampleInputFile">Client description and Business Type</label>
                                <textarea name="bus_desc" tabindex="10" class="form-control" ><?php echo $query[0]["bus_desc"]; ?></textarea>
                                <span style="color: red;"><?= form_error('bus_desc'); ?></span>
                            </div>
                   
                            
                   
                             <div class="form-group">
                                <label for="exampleInputFile">Space allocated for primary simple collection </label>
                                <textarea name="space_allocate" tabindex="12" class="form-control" ><?php echo $query[0]["space_allocate"]; ?></textarea>
                                <span style="color: red;"><?= form_error('space_allocate'); ?></span>
                            </div>
                            
                       
                        
                         </div>
                    </div><!-- /.box-body -->

                    <div class="box-footer">
                        <div class="col-md-6">
                            <button class="btn btn-primary" type="submit">Update</button>
                        </div>
                    </div>

                </form>
            </div><!-- /.box -->
            <script  type="text/javascript">
                $(document).ready(function () {
                    $("#showHide").click(function () {
                        if ($("#password").attr("type") == "password") {
                            $("#password").attr("type", "text");
                        } else {
                            $("#password").attr("type", "password");
                        }

                    });
                });
            </script>
        </div>
    </div>
</section>
</div>
</div>

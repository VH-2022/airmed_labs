<style>
    .round {
        display: inline-block;
        height: 30px;
        width: 30px;
        line-height: 30px;
        -moz-border-radius: 15px;
        border-radius: 15px;
        background-color: #222;    
        color: #FFF;
        text-align: center;  
    }
    .round.round-sm {
        height: 10px;
        width: 10px;
        line-height: 10px;
        -moz-border-radius: 10px;
        border-radius: 10px;
        font-size: 0.7em;
    }
    .round.blue {
        background-color: #3EA6CE;
    }


</style>
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />


<style>
    .box.box-solid{border: 1px solid #ccc;}
    .box-header.with-border{border-bottom: 1px solid #ccc;}
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Manage Tax Master
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Tax Master</a></li>
            <li class="active">Tax Master List</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="invoice">
        <div class="widget">
                            <?php if (isset($success) != NULL) { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $success['0']; ?>
                                </div>
                            <?php } ?>

                        </div>
        <div class="row">
                    <span style="float: right;"><a href="<?php echo base_url();?>inventory/tax_master/add"  class="btn btn-primary btn-sm">Add</a></span>    
        </div>


        <div class="row">
            <div class="col-md-12">
                 <form role="form" action="<?php echo base_url(); ?>inventory/tax_master/index" method="get" enctype="multipart/form-data">
             <table id="example3" class="table table-bordered table-striped">
                  <thead>
                      <th>No</th>
                       <th>Title</th>
                        <th>Tax</th>
                      <th>City</th>
                      <th>Remark</th>
                      
                      <th>Action</th>
                  </thead>
                  <tbody>
                     <tr>
                        
                                            <td><span style="color:red;">*</span></td>
                                            
                                            <td><input type="text" placeholder="Enter Title" class="form-control" name="title" value="<?php
                                                if (isset($title)) {
                                                    echo $title;
                                                }
                                                ?>"/></td>
                                            <td><input type="text" placeholder="Enter Tax" class="form-control" name="tax" value="<?php
                                                if (isset($tax)) {
                                                    echo $tax;
                                                }
                                                ?>"/></td>
                                                 <td>
                                                     <select name="city_fk" class="chosen chosen-select" data-placeholder="choose a language...">
                                                         <option value="">--Select--</option>
                                                        <?php foreach($city_list as $val){  ?>
                                                              <option value="<?php echo $val['city_fk']; ?>" <?php
                                                    if ($city_fk == $val['city_fk']) {
                                                        echo " selected='selected'";
                                                    }
                                                    ?> ><?php echo $val['name']; ?></option>
                                                      
                                                        <?php } ?>
                                                     </select>

                                                 </td>
                                                <td></td>
                                                
                                                 
                                            <td><input type="submit" name="search" class="btn btn-success" value="Search" /></td>
                                        </tr>
                    <?php
                $cnt = 1;
                foreach ($query as $valTest) { 
                    $date = explode(" ",$valTest['created_date']);
                    $explode_date = explode("-",$date[0]);
                    $ddate  =$explode_date[2].'-'.$explode_date[1].'-'.$explode_date[0];
                    $new_date = $ddate.' '.$date[1];

                    ?>
                    <tr>
                        <td>
                            <?php echo $cnt;?>
                        </td>
                        <td><?php echo ucfirst($valTest['title']);?></td>
                        <td><?php echo ucfirst($valTest['tax']);?></td>
                        <td><?php echo ucfirst($valTest['cityName']);?></td>
                        <td><?php echo ucfirst($valTest['remark']);?></td>
                        <td>
                         
                            <a href="<?php echo base_url();?>inventory/tax_master/edit/<?php echo $valTest['id'];?>"><i class="fa fa-edit"></i></a>
                            <a href="<?php echo base_url();?>inventory/tax_master/delete/<?php echo $valTest['id'];?>"><i class="fa fa-trash" aria-hidden="true"></i></a></td>
                        
                    <?php  $cnt++;
                } if(empty($query)){?>
                    <tr><td colspan="7">Record Not Found</td></tr>
                <?php } 
               ?>
                  </tbody>
              </form>
              </table>
            </div>
        </div>
    </section><!-- /.content -->
    <div class="clearfix"></div>
</div><!-- /.content-wrapper -->
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
<script  type="text/javascript">
                        $nc = $.noConflict();
                        $nc(function () {

                            $nc('.chosen-select').chosen();

                        });

</script> 
<script type="text/javascript">
     $(function () {
     
     $('#example3').dataTable({
     //"bPaginate": false, 
     "bLengthChange": false,
     "bFilter": false,
     "bSort": false, 
     "bInfo": false,
     "bAutoWidth": false
     });
     }); 
</script>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Business Report
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>location-master/city-list"><i class="fa fa-users"></i>Business Report</a></li>

        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Business Report List</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="widget">
                            <?php if (isset($success) != NULL) { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $success['0']; ?>
                                </div>
                            <?php } ?>

                        </div>

                        <?php $attributes = array('class' => 'form-horizontal', 'method' => 'get', 'role' => 'form'); ?>

                        <?php echo form_open('Business_Report/business_list', $attributes); ?>

                        <div class="col-md-3">
                            <input type="text" class="form-control datepicker" name="start_date" placeholder="Enter Start Date" value="<?php if (isset($start_date)) {
                            echo $start_date;
                        }else{
                           echo date("d/m/Y");
                        } ?>" />
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control datepicker" name="end_date" placeholder="Enter End Date" value="<?php if (isset($end_date)) {
                            echo $end_date;
                        }else{
                            echo date("d/m/Y");
                        }?>"/>
                        </div>
                        <div class="col-md-3">
                            <select name="city" class="form-control">
                                <option value="">Select City</option>
                                <?php foreach($city as $val) { ?>
                                <option value="<?php echo $val['id'];?>"<?php if($city_id == $val['id']){ echo "selected='selected'";} ?>><?php echo $val['name'];?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <input type="submit" value="Search" class="btn btn-primary btn-md">
                       
						<a href="<?= base_url()."Business_Report/business_list"; ?>" class="btn btn-primary btn-md">Reset</a>
                        <?php if($query !='') { ?> 
                        <a href="<?php echo base_url();?>Business_Report/export_to_csv?start_date=<?php echo $start_date;?>&end_date=<?php echo $end_date;?>&city=<?php echo $city_id;?>"class="btn btn-primary btn-md" ><i class="fa fa-download" ></i><strong > Export CSV</strong></a>
                        <?php } ?>
                        </form>

                        
                         
                        <br>
                         
                        <div class="tableclass">
                            <?php if($query !='') { ?>
                            <table id="example4" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name of Cilent</th>
                                        <th>BDE/BDM</th>
                                        <th>Branch Type</th>
                                        <th>City</th>
                                        <th>Revenue</th>
                                        <th>Total Booking</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $cnt=1;
                                     foreach($query as $val){ ?>
                                    <tr>
                                        <td><?php echo $cnt;?></td>
                                        <td><?php echo ucwords($val['ClientName']);?></td>
                                        <td><?php echo ucwords($val['first_name'].' '.$val['last_name']);?></td>
                                        <td>B2B</td>
                                         <td><?php echo ucwords($val['City']);?></td>
                                          <td><?php echo ucwords($val['Revenue']);?></td>
                                          <td><?php echo ucwords($val['Booking']);?></td>
                                          
                                    </tr>
                                    
                                  
                                     <?php $cnt++; } foreach($query_b2c as $key) { ?>
                                    <tr>
                                        <td><?php echo $cnt;?></td>
                                        <td><?php echo ucwords($key['BranchName']);?></td>
                                        <td><?php echo ucwords($key['first_name'].' '.$key['last_name']);?></td>
                                        <td><?php echo ucwords($key['BranchType']);?></td>
                                         <td><?php echo ucwords($key['City']);?></td>
                                          <td><?php echo ucwords($key['Revenue']);?></td>
                                          <td><?php echo ucwords($key['Booking']);?></td>
                                          
                                    </tr>
                                     <?php $cnt++;} ?>
                                </tbody>
                            </table>
                            <div style="text-align:right;" class="box-tools">
                            <ul class="pagination pagination-sm no-margin pull-right">
<?php echo $links; ?>
                            </ul>
                        </div>
                            <?php }?>
                           
                            
                        </div>
                        
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<script type="text/javascript">
    $(function () {
        $("#example1").dataTable();
        $('#example3').dataTable({
            "bPaginate": false,
            "bLengthChange": false,
            "bFilter": true,
            "bSort": false,
            "bInfo": false,
            "bAutoWidth": false
        });
    });
</script>

<script>
  var FromEndDate = new Date();

    $(function(){
   $('.datepicker').datepicker({
      format: 'dd/mm/yyyy',
     endDate: FromEndDate, 
    autoclose: true
    });
});
</script>
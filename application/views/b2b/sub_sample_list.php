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
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
       Client Print List
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li> Client Print List</li>

        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Client Print List</h3>

                        <a style="float:right;" href='<?php echo base_url(); ?>b2b/Sample_print_master/sub_sample_add' class="btn btn-primary btn-sm" ><i class="fa fa-plus-circle" ></i><strong > Add</strong></a>
                                                <!--<a style="float:right;" href='<?php echo base_url(); ?>manage_master/manage_csv' class="btn btn-primary btn-sm" ><strong > Export</strong></a>
                                                <a style="float:right;" data-toggle="modal"  data-target="#import"  class="btn btn-primary btn-sm" ><strong > Import</strong></a>-->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <?php $session = $this->session->userdata('success'); ?>
                        <div class="widget">
                            <?php if (isset($success) != NULL) { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $success; ?>
                                </div>
                            <?php } ?>

                        </div>

                        
                        <div class="tableclass">
                            <?php echo form_open("b2b/Sample_print_master/sub_sample_list", array("method" => 'GET')); ?>
                            <table id="example3" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                     
                                        <th>No</th>
                                        <th>Client Name</th>
                                        
                                        <th>Print Report</th>
                                       
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    <tr>

                                        <td><span style="color:red;">#</span></td>
                                       
                                        <td>
                                            <select class="form-control chosen-select" tabindex="-1" name="client">
                                                <option value="">Select Client</option>
                                                <?php foreach ($client as $br) { ?>
                                                    <option value="<?php echo $br['id']; ?>" <?php
                                                    if ($client_fk == $br['id']) {
                                                        echo " selected";
                                                    }
                                                    ?> ><?php echo $br['name']; ?></option>
                                                        <?php } ?>
                                            </select>
                                        </td>
                                        <td>
                                             <select class="form-control chosen-select" tabindex="-1" name="yes" >
                                                <option value="">Select Print Report</option>
                                               <option value="0"<?php if($yes_id =='0'){echo "selected";} ?>>No</option>
                                               <option value="1" <?php if($yes_id =='1'){echo "selected";}?>>Yes</option>
                                            </select>
                                        </td>
                                        
                                        <td>
                                            <input type="submit" name="search" class="btn btn-success" value="Search" />
											<a class="btn btn-primary" href="<?= base_url()."b2b/Sample_print_master/sub_sample_list"; ?>">Reset</a>
                                        </td>
                                    </tr>

                                    <?php
                                    $cnt = 1;

                                    foreach ($query as $row) {
                                        /*  echo "<pre>";
                                          print_r($row['test_name']);
                                          exit; */
                                        ?>
                                        <tr>
                                            <td><?php echo $cnt; ?> </td>
                                            <td><?php echo ucfirst($row['ClientName']); ?></td>

                                            <td><?php if($row['print_report'] == 0){
                                                $print = "No";
                                            }else{
                                                $print ="Yes";
                                            } 

                                                echo $print;
                                            ?></td>
                                           
                                    <td>
                                            
                                        <a href='<?php echo base_url(); ?>b2b/Sample_print_master/sub_sample_edit/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-edit"></i></a> 
                                        <a  href='<?php echo base_url(); ?>b2b/Sample_print_master/sub_sample_delete/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure you want to remove this data?');"><i class="fa fa-trash-o"></i></a>      
                                       
                                    </td>

                                    </tr>
                                    <?php
                                    $cnt++;
                                }
                                if (empty($query)) {
                                    ?>
                                    <tr><td colspan="4"><center>Data not available.</center></td></tr>
                                <?php } ?>
                                </tbody>

                            </table>
                            <div style="text-align:right;" class="box-tools">
                                <ul class="pagination pagination-lm no-margin pull-right">
                                    <?php echo $links; ?>
                                </ul>
                            </div>

                            <?php echo form_close(); ?>
                        </div>





                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
<script  type="text/javascript">

                                        jQuery(".chosen-select").chosen({
                                            search_contains: true
                                        });
                                        //  $(".chosen-select-deselect").chosen({ allow_single_deselect: true });
                                        // $("#cid").chosen('refresh');

</script> 
<script type="text/javascript">
    /*  $(function () {
     
     $('#example3').dataTable({
     //"bPaginate": false,
     "bLengthChange": false,
     "bFilter": false,
     "bSort": false,
     "bInfo": false,
     "bAutoWidth": false
     });
     }); */
</script>

<script type="text/javascript">
    window.setTimeout(function () {
        $(".alert").fadeTo(500, 0).slideUp(500, function () {
            $(this).remove();
        });
    }, 4000);</script>
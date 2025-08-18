<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Pitch List
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>pitch_master/pitch_list"><i class="fa fa-users"></i>Pitch List</a></li>

        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Pitch List</h3>

                       
                        

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
                        <?php /*
                          <?php $attributes = array('class' => 'form-horizontal', 'method' => 'get', 'role' => 'form'); ?>

                          <?php echo form_open('admin_master/admin_list', $attributes); ?>

                          <div class="col-md-3">
                          <input type="text" class="form-control" name="user" placeholder="Username" value="<?php if(isset($username) != NULL){ echo $username; } ?>" />
                          </div>
                          <div class="col-md-3">
                          <input type="text" class="form-control" name="email" placeholder="Email" value="<?php if(isset($email) != NULL){ echo $email; } ?>"/>
                          </div>
                          <input type="submit" value="Search" class="btn btn-primary btn-md">


                          </form>

                          <br>
                         */ ?>
                        <div class="tableclass">
                            <form role="form" action="<?php echo base_url(); ?>pitch_master/pitch_list" method="get" enctype="multipart/form-data">
                              
                                
                            
                                 <div id="prnt_rpt">
                                <table id="example3" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>City Name</th>
                                            <th>Html</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       
                                 
                                        <?php
                                        $cnt = 1;
                                      $counts =array();
                                        foreach ($new_array as $row) {
                                            ?>
                                            <tr>
                                                <td><?php echo $cnt; ?></td>
                                                 <td><?php echo ucwords($row['name']); ?></td>
                                                 <td>
                                                     <?php   
                                                      echo count($row['details']);
                                                    ?>
                                                 </td>
                                                <td>
                                                    <a href="<?php echo base_url();?>Pitch_master/add/<?php echo $row['id'];?>">Add</a>
                                                </td>
                                            </tr>
                                            
                                            <?php
                                            $cnt++;
                                        }
                                            ?>
                                          
                                    
                                    </tbody>
                                </table>
                                 </div>
                            </form>
                        </div>
                        <div style="text-align:right;" class="box-tools">
                            <ul class="pagination pagination-sm no-margin pull-right">

                            </ul>
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
    $(document).ready(function () {
      
        var date_input = $('input[name="expense_date"]'); //our date input has the name "date"

        var container = $('.bootstrap-iso form').length > 0 ? $('.bootstrap-iso form').parent() : "body";
        date_input.datepicker({
            format: 'dd-mm-yyyy',
            container: container,
            todayHighlight: true,
            autoclose: true
        });
    });
</script>
<script type="text/javascript">
function printData()
                                {
                                    var divToPrint = document.getElementById("prnt_rpt");
                                    newWin = window.open("");
                                    newWin.document.write(divToPrint.outerHTML);
                                    newWin.print();
                                    newWin.close();
                                }
</script>
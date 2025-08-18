<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Airmed Tech Month Report
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>bill_master/bill_list/"><i class="fa fa-users"></i>Airmed Tech Month Report</a></li>

        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Airmed Tech Month Report</h3>

                    </div>
                    <div class="box-body">
<a style="float:right;" href='<?php echo base_url(); ?>Airmed_tech_report/export_csv_branch?bid=<?= $bid ?>&month=<?=$month?>' class="btn btn-sm btn-primary pull-right" ><i class="fa fa-download" ></i><strong > Export To Data</strong></a>
                        <div id="prnt_rpt">
                            <table id="example3" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Ref No.</th>
                                        <th>Order ID</th>
                                        <th>Patient Name</th>
                                        <th>Date</th>
                                        <th>Price</th>
                                        <th>Discount</th>
                                        <th>Due Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $cnt = 1;
                                    foreach ($report_data as $row) {
                                        ?>
                                        <tr>
                                            <td><?php echo $cnt; ?></td>
                                            <td ><?php echo $row['id']; ?></td>
                                            <td ><a href="<?= base_url(); ?>tech/job_master/job_details/<?= $row["id"] ?>" target="_blank"><?php echo $row['order_id']; ?></a></td>
                                            <td ><?php echo $row['patient']; ?></td>
                                            <td ><?php echo $row['date']; ?></td>
                                            <td>Rs.<?php echo $row['price']; ?></td>
                                            <td>Rs.<?php if($row['discount']>0){ echo ($row['price'] - (($row['price']*$row['discount'])/100)); }else{ echo "0"; } ?></td>
                                            <td>Rs.<?php echo $row['payable_amount']; ?></td>
                                        </tr>
                                        <?php
                                        $cnt++;
                                    }if (empty($report_data)) {
                                        ?>
                                        <tr>
                                            <td colspan="7">No records found</td>
                                        </tr>
                                    <?php } ?>

                                </tbody>
                            </table>
                        </div>
                        </form>
                    </div>
                    <div style="text-align:right;" class="box-tools">
                        <ul class="pagination pagination-sm no-margin pull-right">
                            <?php echo $links; ?>
                        </ul>
                    </div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
</div><!-- /.row -->
</section><!-- /.content -->
</div><!-- /.content-wrapper -->
<script type="text/javascript">
    /*  $(function () {
     $("#example1").dataTable();
     $('#example3').dataTable({
     "bPaginate": false,
     "bLengthChange": false,
     "bFilter": true,
     "bSort": false,
     "bInfo": false,
     "bAutoWidth": false
     });
     }); */
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
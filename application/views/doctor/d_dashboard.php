 <link href="<?php echo base_url(); ?>/plugins/morris/morris.css" rel="stylesheet" type="text/css" />
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
           Dashboard
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url()."doctor/dashboard"; ?>"><i class="fa fa-dashboard"></i>Home</a></li>
             <li class="active">Dashboard</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        
        <div class="row">
            <div class="col-xs-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                  <h3 class="box-title">Today</h3>
                  
                </div>
                <div class="box-body">
                   <div class="chart" id="today-chart" style="height: 300px; position: relative;"></div> 
 <table class="table table-bordered">
                    <tbody><tr><th>Total Patients</th><th>Total Collection</th></tr>
					<tr>
					<td valign="middle"><?= $todata["total_payment"]; ?></td>
					<td valign="middle"><?php if($todata["total_payment_collection"] != "₹"){ echo $todata["total_payment_collection"]; }else{ echo "₹0"; } ?></td>
					</tr> 
					</tbody>
					</table>				   

 <div class="box box-primary"></div>
<table class="table table-bordered">
                    <tbody>
					<?php foreach($todaygraph as $trecird){ 
					
					$color = $trecird["color"];

switch ($color) {
    case "#e85629":
        $tcolor= "bg-navy";
		 $type="";
        break;
    case "#f42e48":
      $tcolor= "bg-maroon";
	  $type="1";
        break;
    case "#FFD600":
        $tcolor= "bg-orange";
		$type="2";
        break;
		 case "#33691E":
        $tcolor= "bg-olive";
		$type="3";
		
        break;
		 case "#2e4de8":
        $tcolor= "bg-purple";
		$type="0";
        break;
    default:
         $tcolor= "bg-purple";
		 $type="";
}
					
					
					?>
                    <tr>
                      
                      <td><a href="<?php echo base_url()."doctor/report?from=".date("d-m-Y")."&to=".date("d-m-Y")."&type=".$type ?>"><span class="btn btn-flat <?= $tcolor; ?>"><?= $trecird["name"]." Patients"; ?></span></a></td>
                     
                      <td><a href="<?php echo base_url()."doctor/report?from=".date("d-m-Y")."&to=".date("d-m-Y")."&type=".$type ?>"><span class="badge bg-red"><?= $trecird["value"]; ?></span></a></td>
                    </tr>
					<?php } ?>
                    
                  </tbody></table>				  
                </div>
                </div><!-- /.box -->
            </div><!-- /.col -->
			
			 <div class="col-xs-6">
                <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title">Last Two Days</h3>
                  
                </div>
                <div class="box-body">
                    <div class="chart" id="pieChart1" style="height: 300px; position: relative;"></div>  
<table class="table table-bordered">
                    <tbody><tr><th>Total Patients</th><th>Total Collection</th></tr>
					<tr>
					<td valign="middle"><?= $last2data["total_payment"]; ?></td>
					<td valign="middle"><?php if($last2data["total_payment_collection"] != "₹"){ echo $last2data["total_payment_collection"]; }else{ echo "₹0"; } ?></td>
					</tr> 
					</tbody>
					</table>
	 <div class="box box-danger"></div>				
 	
<table class="table table-bordered">
                    <tbody>
					<?php foreach($last2day as $trecird1){ 
					
					$color1 = $trecird1["color"];

switch ($color1) {
    case "#e85629":
        $tcolor1= "bg-navy";
		 $type="";
        break;
    case "#f42e48":
      $tcolor1= "bg-maroon";
	  $type="1";
        break;
    case "#FFD600":
        $tcolor1= "bg-orange";
		$type="2";
        break;
		 case "#33691E":
        $tcolor1= "bg-olive";
		$type="3";
		
        break;
		 case "#2e4de8":
        $tcolor1= "bg-purple";
		$type="0";
        break;
    default:
         $tcolor1= "bg-purple";
		 $type="";
}
					
					
					?>
                    <tr>
                      
                      <td><a href="<?php echo base_url()."doctor/report?from=".date("d-m-Y",strtotime("-1 day"))."&to=".date("d-m-Y")."&type=".$type ?>"><span class="btn btn-flat <?= $tcolor1; ?>"><?= $trecird1["name"]." Patients"; ?></span></a></td>
                     
                      <td><a href="<?php echo base_url()."doctor/report?from=".date("d-m-Y",strtotime("-1 day"))."&to=".date("d-m-Y")."&type=".$type ?>"><span class="badge bg-red"><?= $trecird1["value"]; ?></span></a></td>
                    </tr>
					<?php } ?>
                    
                  </tbody></table>					
                </div>
              </div>
            </div>
			
		<?php  if($monthreport != null){ ?>	
			<div class="col-xs-6">
                <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">Monthly Report</h3>
                  
                </div>
                <div class="box-body">

<table class="table table-bordered">
                    <tbody>
					<?php foreach($monthreport as $monthr){ 
					$damonth=sprintf("%02d",$monthr['MONTH']);
					
					$dayear=$monthr['YEAR'];
					$dstartdate=date("01-$damonth-$dayear");
					$denddate=date("t-m-Y", strtotime($dstartdate));
					?>
                   <tr>
                      
                       <td><a href="<?php echo base_url()."doctor/report?from=".$dstartdate."&to=".$denddate; ?>"><span class="btn btn-flat bg-purple"><?= $monthr["yearmotnth"]; ?></span></a></td>
                     
                      <td><a href="<?php echo base_url()."doctor/report?from=".$dstartdate."&to=".$denddate; ?>"><span class="badge bg-red"><?= $monthr["total"]; ?></span></a></td>
                    </tr>
					<?php } ?>
                    
                  </tbody></table>					
                </div>
              </div>
            </div>
		<?php } ?>
			 
			
			 
        </div><!-- /.row -->
    </section><!-- /.content -->

 <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
	  <script src="<?php echo base_url(); ?>/plugins/morris/morris.min.js" type="text/javascript"></script>
	 <script>
	 
	 var donut = new Morris.Donut({
          element: 'today-chart',
          resize: true,
          colors: ["#001f3f", "#f42e48", "#ff851b","#33691E","#605ca8"],
          data: [
		  <?php foreach($todaygraph as $trecird){ ?>
            { colors:"#<?= $trecird["color"]; ?>",label: "<?= $trecird["name"]." Patients"; ?>", value: <?= $trecird["value"]; ?>},
		  <?php } ?>
          ],
          hideHover: 'auto'
        });
		
		 var donut1 = new Morris.Donut({
          element: 'pieChart1',
          resize: true,
          colors: ["#001f3f", "#f42e48", "#ff851b","#33691E","#605ca8"],
          data: [
		  <?php foreach($last2day as $trecird1){ ?>
            { colors:"#<?= $trecird1["color"]; ?>",label: "<?= $trecird1["name"]." Patients"; ?>", value: <?= $trecird1["value"]; ?>},
		  <?php } ?>
          ],
          hideHover: 'auto'
        });
		
		
	 
	 </script>
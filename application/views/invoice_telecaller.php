<style>
.success_msg_div{border:1px solid #D01130; float:left; width:100%; margin:20px 0; padding:20px;box-shadow: 1px 1px 10px #ccc;}
.ack_invc_btn{width:100%; float:left;margin-top:20px;}
.ack_invc_btn ul{width:100%; float:left;text-align:center; }
.ack_invc_btn ul li{display:inline-block;}
.ack_invc_btn ul li a{background-color: #D01130; border-color: #D01130;border-radius:5px; padding:7px 20px; color:#fff; text-transform:capitalize; font-weight:bold; font-size:18px;    float: left;}
.ack_invc_btn ul li a:hover{background-color: #dc1d3c; border-color: #dc1d3c; text-decoration:none;}
.ack_invc_btn ul li span{font-size:25px; margin:0 20px;}
.back_rgstr_btn{width:100%; float:left; text-align:center;}
.back_rgstr_btn a{background-color: #D01130; border-color: #D01130;border-radius:5px; padding:7px 20px; color:#fff; text-transform:capitalize; font-weight:bold; font-size:18px;    display: inline-block;}
.back_rgstr_btn a:hover{background-color: #dc1d3c; border-color: #dc1d3c; text-decoration:none;}
.print_msg{width:100%; float:left; text-align:center;}
.alert{padding: 10px 15px;}
.alert-success{font-size: 21px;}
.ack_invc_btn ul li span {font-size: 20px; margin: 0 20px; border-radius: 50%;border: 6px double #ccc; padding: 5px;float: left; width: 50px; height: 50px;line-height: 29px;}

</style>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="success_msg_div">
				<div class="alert alert-success">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					<strong>Success!</strong> Your Booking is Successfully Done.
				</div>
                            
                                            <?php if ($login_data['type'] == "6") { ?>
                    <div class="print_msg">
                        <h4 style="margin:0px"><b>Patient Name </b> - <?= $name[0]['full_name'] ?></h4><br/>
                        <h4 style="margin:0px"><b>Registration No </b> - <?= $job_id ?></h4><br/>
                        <h4 style="margin:0px"><b>Barcode No </b> - <?= $barcode[0]['barcode'] ?></h4><br/>
                    </div>
                <?php } ?>
                            
				<div class="print_msg">
					<h2>Do you Want to Print?</h2>
				</div>
				<div class="ack_invc_btn">
					<ul class="inline-block">
						<li>
                                                    <a href="<?php echo base_url(); ?>job_master/ack/<?php echo $job_id; ?>" target="_blank">Acknowledgment</a>
						</li>
						<li><span>OR</span></li>
						<li>
                                                    <a href="<?php echo base_url(); ?>job_master/pdf_invoice/<?php echo $job_id; ?>" target="_blank">Invoice</a>
						</li>
				</div>
				<div class="back_rgstr_btn">
		
				<?php 
				$pagetype=$this->input->get("pagetype");
				if($pagetype==2){ ?>
				<a href="<?php echo base_url(); ?>sample_booking/TelecallerCallBooking"><i class="fa fa-pencil-square-o"></i>Back To Register</a>
				<?php }else{ ?>
					<a href="<?php echo base_url(); ?>Admin/TelecallerCallBooking"><i class="fa fa-pencil-square-o"></i>Back To Register</a>
				<?php } ?>
				</div>
			</div>	
		</div>
	</div>
</div>
</div>
</div>
</div>
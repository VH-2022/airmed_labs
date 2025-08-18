<html><head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8"></head><body>
		<div class="pdf_container">
            <meta charset="utf-8">
            <style>
                body {padding:0;margin:0;}
                .pdf_container {width:970px; margin: 0 auto;padding:0;}
                .main_set_pdng_div {width: 100%; float: left; padding: 0 0;}
                .brdr_full_div { float: left; width: 100%;margin-top:20px}
                .full_div {width:98%; float: left;padding:0 30px;}
                .header_full_div {float: left; width: 98%; height:auto;}
                .set_logo {width: 100%; float: right;}
                .testreport_full {width: 100%; float: left;}
                .tst_rprt {border-bottom: 1px solid #000000; border-top: 1px solid #000000; margin: 0; padding: 5px 0; text-transform: uppercase;width: 100%; float:left;}
                .tst_rprt > img {}
                .tst_rprt h3 {margin: 0; text-align: center;}
                .tbl_full {width: 100%; font-size: 12px;}
                .mdl_tbl_full_div {width: 100%; float: left; min-height: 500px;margin: 0px 5px 0px 5px;}
                .btm_tbl_full_div {width: 100%; float: left;}

                .brdr_btm {border-bottom: 1px solid #000;}
                .header_full_div .hdr_lft{ width: 75%; float: left; }
                .header_full_div .hdr_rgt{ width: 25%; float: left; }
                .header_full_div h2 {background: #BF2D37; color: #fff; font-size: 16px;font-weight: 800; text-transform: uppercase; padding: 10px 0 10px 30px;margin-bottom:0;}
                .tbl_half{width:35%; float:left;font-size:12px;font-family:'Roboto', sans-serif;}
                tr {line-height: 1.3;}
                .tbl_full.with_brdr{margin-bottom:20px;}
                .tbl_full.with_brdr thead th{border-bottom:1px solid #000; text-align:left;    padding: 10px 5px;}
                .tbl_full.with_brdr tbody td{border-bottom:1px solid #000;padding: 10px 5px;}
                .rslt_p_brdr {border-bottom: 1px solid #000000; float: left; margin: 0; padding-bottom: 5px; text-align: center; width: 100%;}
                .last{border-bottom:none !important;}
			 padding: 10px 0 10px 30px;margin-bottom:0;}
			.tbl_half{width:48%; float:left;font-size: 12px;}
			 tr {line-height: 1.3;}
			 .tbl_full.with_brdr{margin-bottom:40px;}
			 .tbl_full.with_brdr thead th{border-bottom:1px solid #000; text-align-last: start; }
			 .tbl_full.with_brdr tbody td{border-bottom:1px solid #000;}
			.rslt_p_brdr {border-bottom: 1px solid #000000; float: left; margin: 0; padding-bottom: 5px; text-align: center; width: 100%;}
			.half_div{width:50%; float:left;}
			
			.header_full_div .hdr_top h2 {font-size: 16px;font-weight: 600; text-transform: uppercase; margin-bottom:5px; text-align:center; background:none; color:#000; padding:0;}
			.lst_airmed_mdl {float: left; margin-bottom: 0; margin-top: 0px; text-align: center; width: 100%;}
            .lst_31_addrs_mdl {float: left; margin: 0; text-align: center; width: 100%;margin-bottom: 5px;}
            .tbl_btm_mdl_txt {width: 80%; float: left; padding: 0 98px; font-weight: bold;}
			.txt_align_lft{text-align:left;}
			.brdr_rgt{border-right:1px solid #000;}
            </style>


			<table style="border-left:6px solid #E30026;width:100%;">
			   <tr>
			      <td colspan="2">
				       <table style="width:100%;float:right;text-align:right;">
                            <tr>
                                
                                <td colspan="2" style="text-align:right;">
                                    <div style="float:right;background:#E20025;">
                                        <img class="set_logo" 
                                             src="http://www.airmedlabs.com/user_assets/images/logo1.png" style="width:170px;padding:10px;float:right;">
                                    </div>
                                </td>
                            </tr>
                        </table>
				  </td>
			   </tr>
			    <tr>
                    <td colspan="2"><center><h1 style="font-weight:bold;font-size:22px;text-align:center;text-transform:uppercase;font-family:'Roboto', sans-serif;">Invoice</h1></center></td>
			 </tr>
				<tr>
					<td colspan="2">
							<table>
                
               
                <tr>
                    <td style="width:65%; margin-bottom:25px;">
                        <table style="margin-left:20px">
                            <tr>
                                <td colspan="2"><b style="font-family: 'Roboto', sans-serif;">TO</b></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="font-family: 'Roboto', sans-serif;"><?= ucwords($query->name); ?></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="font-family: 'Roboto', sans-serif;"><?= ucwords($query->address); ?></td>
                            </tr>
							
                        </table>
                    </td>

                    <td style="width:35%;margin-bottom:15px;float:left;">
                        <table style="border-spacing:0;width:260px;margin-left:0px;">
						<?php if($intype=='1') { ?>
                            <tr>
                                <td class="last" style="border-top:1px solid #000;border-left:1px solid #000;border-right:1px solid #000;padding:5px;font-size:12px;font-weight:700;font-family: 'Roboto', sans-serif;">
                                   <b> BILL NO:</b>
                                </td>
                                <td class="last" style="border-top:1px solid #000;border-right:1px solid #000;padding:5px;font-size:12px;font-weight:700;font-family: 'Roboto', sans-serif;">
                                   <?php if($date2){ ?>  <b><?= "AIR-".$query->id."-".date('m-Y',strtotime($date2)); ?></b> <?php } ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="last" style="border-top:1px solid #000;border-left:1px solid #000;border-right:1px solid #000;padding:5px;font-size:12px;font-weight:700;font-family: 'Roboto', sans-serif;">
                                  <b> BILL DATE:</b>
                                </td>
                                <td class="last" style="border-top:1px solid #000;border-right:1px solid #000;padding:5px;font-size:12px;font-weight:700;font-family: 'Roboto', sans-serif;">
                                    <b><?= date("jS M Y"); ?></b>
                                </td>
                            </tr>
						<?php } ?>
                            <tr>
                                <td colspan="2" style="border-top:1px solid #000;border-right:1px solid #000;border-left:1px solid #000;padding:5px;font-size:12px;font-weight:700;border-bottom:1px solid #000;font-family: 'Roboto', sans-serif;">
                                   <b> FROM <?php if($date2){ echo date("jS M Y",strtotime($date2)); }else{ echo "-"; } ?> TO <?php if($todate2){ echo date("jS M Y",strtotime($todate2)); } ?> </b>
                                </td>
                            </tr>

                        </table>
                    </td>
                </tr>
            </table>
			
					</td>
				</tr>
				<tr>
				  <td colspan="2">
						 <table class="" style="width:94%;border:1px solid #000; border-spacing: 0;margin:0 auto;">
                                <thead style="text-align:left;">
                                    <tr>
                                        <th style="width:80%;border-bottom:1px solid #000;font-size:16px;text-align:left;padding:3px 10px;font-family:'Roboto', sans-serif">Description</th>
                                        <th style="width:20%;border-left:1px solid #000;border-bottom:1px solid #000;font-size:14px;text-align:center;padding:3px 10px;font-family:'Roboto', sans-serif ">Amount</th>
                                    </tr>
                                <thead>
                                <tbody>
                                    <tr>
                                        <td  colspan="2" style=" #000;font-size:14px;text-align:left;padding:3px 10px; font-family:'Roboto', sans-serif;height:50px;vertical-align:top;"><?= ucwords($query->name); ?></td>                   
                                                           
                                    </tr>

                                </tbody>
                            </table>
				  </td>
				</tr>
			</table>
			<table style="border-spacing: 0;margin-left: 4.2%;margin-top: -4px;   width: 96.5%;">
			
			 
                                    <tr>                                        <td style="border-bottom:1px solid #000;border-left:1px solid #000; font-size:14px;text-align:left;padding:3px 10px;font-family:'Roboto', sans-serif;width:61.5%;"><b>Total :</b></td>
										
                                        <td style="width:2%;border-left:1px solid #000;border-bottom:1px solid #000;border-right:1px solid #000;font-size:14px; text-align:center;padding:3px 10px;font-family:'Roboto', sans-serif"><b><?= "Rs.".round($totalamount); ?></b></td>                   
                                    </tr>
                                  <?php  /* <tr>
                                        <td colspan="2" style="font-size:14px;text-align:left;padding:3px 10px;font-family:'Roboto', sans-serif;border-bottom:1px solid #000;
										border-left:1px solid #000;border-right:1px solid #000;">AMOUNT IN WORDS: Seven Hundred Five Only</td>                   
                                                          
                                    </tr> */ ?>
			</table>

            	

            <div class="main_set_pdng_div">
                <div class="brdr_full_div">

                    <div class="testreport_full">	

                        <div class="full_div">
                           
                        </div>

                        <div class="full_div">
						<?php if($bank[0] != "" && $bank[0] != null) { $row=$bank[0]; ?>

                             <table style="width:100%;">
							   <tr>
                                    <td colspan="2" style="font-family:'Roboto', sans-serif;font-size:16px;"><b>Cheque drawn in favour of - Airmed Pathology Pvt.Ltd</b></td>
                                </tr>
							 </table>
                            <table class="tbl_half">
                               
                                <tr>
                                    <td colspan="2" style=""><b>PAN - <?php echo $row['pan']; ?></b></td>
                                </tr>
                                <tr>
                                    <td><b>Bank Details </b></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td><b>Bank Name :</b></td>
                                    <td><?php echo ucwords($row['bank_name']); ?></td>
                                </tr>
                                <tr>
                                    <td><b>Branch :</b></td>
                                    <td><?php echo $row['branch']; ?></td>
                                </tr>
                                <tr>
						<td><b>A/C No. :</b></td>
						<td><?php echo $row['ac_no']; ?></td>
					</tr>
					<tr>
						<td><b>IFSC Code :</b></td>
						<td><?php echo $row['ifsc_no']; ?></td>
					</tr>
					<tr>
						<td><b>MICRNO :</b></td>
						<td><?php echo $row['micr_no']; ?></td>
					</tr>
					<tr>
						<td><b>GSTIN :</b></td>
						<td><?php echo $row['gstin']; ?></td>
					</tr>
								
                            </table>
						<?php } ?>
							<?php if($intype=='1') { ?> <table style="width:100%;margin-bottom:100px;">
							  <tr>
									<td colspan="2">
									<b style="font-family:'Roboto', sans-serif;font-size:12px;">COMPUTER GENERATED INVOICE,REQUIRES NO SIGNATURE</b>
									</td>
								</tr>
							</table><?php } ?>
                           
                            <table class="tbl_full" style="margin-bottom:10px;width:100%;">
                                <tr>
                                    <td>
									  <table style="">
									    <tr>
										 <td colspan="2"><b style="font-size:15px;">LAB AT YOUR DOORSTEP</b></td>
										 
										</tr>
										 <tr>
						 <td colspan="2" style="background:#D81238;text-align:center;">
						 <h1 style="color:#fff;font-size:30px;padding:10px;font-weight:300">8101-161616</h1>
						 </td>
						 
										 
										</tr>
									  </table>
									 </td>
									 <td>
									
                                 <table style="width:98%;float:right; font-family:'Roboto', sans-serif;font-size:12
								 px;margin-left:35px;">
                                            <tr>
                                                <td></td><td style="text-align:left"><b style="font-size:14px;">AIRMED PATHOLOGY PVT.LTD.</b>							</td>
                                            </tr>
                                            <tr><td></td><td><b>HEAD OFFICE:30 Ambika Society, near usmanpura garden, next to NABARD</b>							</td>
                                            </tr>
                                            <tr>
                                                <td></td><td><b>bank, usmanpura Ahmedabad-380013 </b>							</td>
                                            </tr>

                                            <tr><td></td><td><b style="color:#0101FF">www.airmedlabs.com  | info@airmedlabs.com </b><b>| accounts@airmedlabs.com</b>							</td>
                                            </tr>
                                        </table></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    </div></div>
					<pagebreak>
	<div class="header_full_div">
			<div class="hdr_top">
				<h2>Airmed Pathology Private Limited</h2>
				<p class="lst_31_addrs_mdl">31, Ambika Society, Next to Nabard Bank, Opp. Usmanpura, Ahmedabad, Gujarat - 380 013.</p>
				<p class="lst_31_addrs_mdl"><b>info@airmedlabs.com | www.airmedlabs.com</b></p>
			</div>
		
		</div>
		<div class="testreport_full">
		  <?php if($intype=='1') { ?>  <div class="tst_rprt">
			<div class="tst_rprt_title" style="width:100%;float:left;text-align:center;">
			    <h3>Invoice Details</h3>
			</div>
		  </div> <?php } ?>
			<div class="full_div" style="margin-bottom: 10px;">
				<table style="font-size:12px;">
					<tbody>
						<tr>
							<td style="width:25%;"><b>Sales Person</b></td>
							<td><?= ucwords($query->salesname); ?></td>
						</tr>
						<tr>
							<td style="width:25%;"><b>Account Name :</b></td>
							<td><?= ucwords($query->name); ?></td>
						</tr>
					</tbody>
				</table>
				<?php if($intype=='1') { ?><table class="tbl_full">
					<tbody>
						<tr>
							<td><b>Bill No. :</b></td>
							<td><?php if($date2){ ?>  <b><?= "AIR-".$query->id."-".date('m-Y',strtotime($date2)); ?></b> <?php } ?></td>
							<td><b>Bill Date :</b></td>
							<td><?= date("jS M Y",strtotime(date("d-m-Y"))); ?></td>
							<td><b>Bill Period :</b></td>
							<td><?php if($date2){ echo date("jS M Y",strtotime($date2)); } ?> - <?php if($todate2){ echo date("jS M Y",strtotime($todate2)); } ?> </td>
							
						</tr>
					</tbody>
				</table><?php } ?>
			</div>
			
		    <div class="full_div">
				<table class="tbl_full with_brdr" style="border:1px solid #000; border-spacing: 0;">
					<thead>
						<tr>
							<th class="txt_align_lft brdr_btm brdr_rgt" style="width:42px;padding:5px;">Sr No.</th>
							<th class="txt_align_lft brdr_btm brdr_rgt" style="width:90px;padding:5px;">Reg.Date</th>
							 <th class="txt_align_lft brdr_btm brdr_rgt" style="width:100px;padding:5px;">Lab ID</th>  
							<th class="txt_align_lft brdr_btm brdr_rgt" style="width:27%;padding:5px;">Patient's Name</th>
							<?php /* <th class="txt_align_lft brdr_btm brdr_rgt" style="padding:5px;width:90px;">Ref. ID</th> */ ?>
							<th class="txt_align_lft brdr_btm brdr_rgt" style="width:27%;padding:5px;">Investigation</th>
							<th class="txt_align_lft brdr_btm brdr_rgt" style="width:100px;padding:5px;">Collection charge</th>
							<th class="brdr_btm" style="border-right:none; width:60px; text-align:right;padding:5px;">Amount</th>
						</tr>
					<thead>
                     <tbody>
					 <?php $amount_all=0; $i=0; foreach($query1 as $key){ $i++; $amount_all += $key['amt']; ?>
						<tr>
                            <td class="brdr_btm brdr_rgt" style="padding:5px;"><?= $i; ?></td>                
                            <td class="brdr_btm brdr_rgt" style="padding:5px;"><?= $key['regdate']; ?></td>                
                                            
                            <td class="brdr_btm brdr_rgt" style="text-transform:uppercase;padding:5px;"><?=$key['labid']; ?></td>
                            <td class="brdr_btm brdr_rgt" style="padding:5px;"><?= ucwords($key['patientname']); ?> </td>                
                            <td class="brdr_btm brdr_rgt" style="padding:5px;"><?= ucwords($key['testname']); ?></td>
							<td class="brdr_btm brdr_rgt" style="padding:5px;"><?= round ($key['collection_charge']); ?></td>
                            <td class="brdr_btm" style="text-align:right;padding:5px;"><?= $key['amt'];	?></td>                
                        </tr>
						<?php 
						
						
						} ?>
						<tr><td></td><td colspan='5'><b>Total</b></td><td>Rs.<?=round($amount_all);?></td></tr>
                     </tbody>
				</table>
		    </div>
			
			
		</div>
</pagebreak>				
					
					</div></body></html>
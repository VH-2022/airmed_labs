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


		
	<div class="header_full_div">
			<div class="hdr_top">
				<h2>Airmed Pathology Private Limited</h2>
				<p class="lst_31_addrs_mdl">31, Ambika Society, Next to Nabard Bank, Opp. Usmanpura, Ahmedabad, Gujarat - 380 013.</p>
				<p class="lst_31_addrs_mdl"><b>info@airmedlabs.com | www.airmedlabs.com</b></p>
			</div>
		
		</div>
		<div class="testreport_full">
		    <div class="tst_rprt">
			<div class="tst_rprt_title" style="width:100%;float:left;text-align:center;">
			    <h3>Payment Report</h3>
			</div>
		  </div> 
			
			
		    <div class="full_div">
				<table class="tbl_full with_brdr" style="border:1px solid #000; border-spacing: 0;">
					<thead>
						<tr>
							<th class="txt_align_lft brdr_btm brdr_rgt" style="width:42px;padding:5px;">Sr No.</th>
							<th class="txt_align_lft brdr_btm brdr_rgt" style="width:90px;padding:5px;">Date</th>
							 <th class="txt_align_lft brdr_btm brdr_rgt" style="width:100px;padding:5px;">Cash Collection Today</th>  
							<th class="txt_align_lft brdr_btm brdr_rgt" style="width:27%;padding:5px;">Prev. Day collection</th>
							<?php /* <th class="txt_align_lft brdr_btm brdr_rgt" style="padding:5px;width:90px;">Ref. ID</th> */ ?>
							<th class="txt_align_lft brdr_btm brdr_rgt" style="width:27%;padding:5px;">Deposit in bank</th>
							<th class="txt_align_lft brdr_btm brdr_rgt" style="width:27%;padding:5px;">Same Day Difference</th>
							<th class="brdr_btm" style="border-right:none; width:60px; text-align:right;padding:5px;">Cumulative Difference</th>
						</tr>
					<thead>
                     <tbody>
					 
					 <?php
									   /*CashCreditorDataFromStartDay
									   CashFromStartDay */
									  
									   $tptal=0;
									   $count=0;
									 
									   $lastBankEntry=$CashCreditorDataPrevDay+$CashPrevDay;
									   $backCumulativeDiff=0;
									   $backCumulativeDiff=$CashCreditorDataFromStartDay[0]->SameDay+$CashCreditorDataFromStartDay[0]->BackDay+$CashFromStartDay[0]->SameDay+$CashFromStartDay[0]->BackDay-$lastBankEntry;
									   $CumulativeDiff=$backCumulativeDiff-$BankDepositFromStartDay;
										
										/* print_r( $backCumulativeDiff);
										print_r( $CumulativeDiff);
										print_r( $BankDepositFromStartDay); */
										$prevDate="";
									   foreach($query as $row){ $count++; 
									  $data=getjobspayamount($branch,"1",date("Y-m-d",strtotime($row["date"]))); ?>
									   <tr>
									   <td class="brdr_btm brdr_rgt" style="padding:5px;"><?= $count; ?></td>
									   <td class="brdr_btm brdr_rgt" style="padding:5px;"><?= date("d-m-Y",strtotime($row["date"])); ?></td>
									   <td class="brdr_btm brdr_rgt" style="padding:5px;"><?= round($row["SameDay"])+round($row["creditor_sameday"]); ?></td>
									   <td class="brdr_btm brdr_rgt" style="padding:5px;"><?= round($row["BackDay"])+round($row["creditor_backday"]); ?></td>
									   <td class="brdr_btm brdr_rgt" style="padding:5px;"><?= round($data['banckamount']); ?></td>
									   
									   <td class="brdr_btm brdr_rgt" style="padding:5px;"><?php $tptal=$lastBankEntry-round($data['banckamount']);
									   echo $tptal;
									   $CumulativeDiff+=$tptal;

									   ?></td><td class="brdr_btm brdr_rgt" style="padding:5px;"><?= $CumulativeDiff ?></td>
									   </tr>
									   
									  <?php $total=round($row["SameDay"])+round($row["creditor_sameday"])+round($row["BackDay"])+round($row["creditor_backday"]);
									   $lastBankEntry=$total;
									  
									  } 
					 ?>
					 
						
                     </tbody>
				</table>
		    </div>
			
			
		</div>
</pagebreak>				
					
					</div></body></html>
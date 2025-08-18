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
                    <td colspan="2"><center><h1 style="font-weight:bold;font-size:22px;text-align:center;text-transform:uppercase;font-family:'Roboto', sans-serif;"></h1></center></td>
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
                                <td colspan="2" style="font-family: 'Roboto', sans-serif;"><?= ucwords($query[0]["vendor_name"]); ?></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="font-family: 'Roboto', sans-serif;"><?= ucwords($query[0]["address"]);  ?></td>
                            </tr>
							
							 <tr>
                                <td colspan="2"><b style="font-family: 'Roboto', sans-serif;">FROM</b></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="font-family: 'Roboto', sans-serif;"><?= ucwords($query[0]["branch_name"]); ?></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="font-family: 'Roboto', sans-serif;"><?= ucwords($query[0]["baddress"]); ?></td>
                            </tr>
							
                        </table>
                    </td>
					
                     <td style="width:35%;margin-bottom:15px;float:left;">
                        <table style="border-spacing:0;width:260px;margin-left:0px;">
						
                            <tr>
                                <td class="last" style="border-top:1px solid #000;border-left:1px solid #000;border-right:1px solid #000;padding:5px;font-size:12px;font-weight:700;font-family: 'Roboto', sans-serif;">
                                   <b> Order NO:</b>
                                </td>
                                <td class="last" style="border-top:1px solid #000;border-right:1px solid #000;padding:5px;font-size:12px;font-weight:700;font-family: 'Roboto', sans-serif;">
                                    <b><?= $query[0]["ponumber"]; ?></b>
                                </td>
                            </tr>
                            <tr>
                                <td class="last" style="border-top:1px solid #000;border-right:1px solid #000;border-left:1px solid #000;padding:5px;font-size:12px;font-weight:700;border-bottom:1px solid #000;font-family: 'Roboto', sans-serif;">
                                  <b> BILL DATE:</b>
                                </td>
                                <td class="last" style="border-top:1px solid #000;border-right:1px solid #000;border-left:1px solid #000;padding:5px;font-size:12px;font-weight:700;border-bottom:1px solid #000;font-family: 'Roboto', sans-serif;">
                                    <b><?= date("jS M Y",strtotime($query[0]["updateddate"])); ?></b>
                                </td>
                            </tr>
						
                        </table>
                    </td>
					
					 
					
                </tr>
            </table>
			
					</td>
				</tr>
				
			</table>
			
 <div class="full_div">
				<table class="tbl_full with_brdr" style="border:1px solid #000; border-spacing: 0;">
					<thead>
						<tr>
							
							<th class="txt_align_lft brdr_btm brdr_rgt" style="width:10px;padding:5px;">Sr No.</th>
							<th class="txt_align_lft brdr_btm brdr_rgt" style="width:100px;padding:5px;">Category</th>
							 <th class="txt_align_lft brdr_btm brdr_rgt" style="width:100px;padding:5px;">Items</th>  
							<th class="txt_align_lft brdr_btm brdr_rgt" style="width:10%;padding:5px;">NOS</th>
							<th class="txt_align_lft brdr_btm brdr_rgt" style="width:10%;padding:5px;">Qty.</th>
							<th class="txt_align_lft brdr_btm brdr_rgt" style="width:10px;padding:5px;">Rate</th>
							<th class="txt_align_lft brdr_btm brdr_rgt" style="width:10px;padding:5px;">Amount Rs.</th>
							<th class="txt_align_lft brdr_btm brdr_rgt" style="width:10px;padding:5px;">Discount(%)</th>
							<th class="txt_align_lft brdr_btm brdr_rgt" style="width:10px;padding:5px;">TAX(%)</th>
							<th class="txt_align_lft brdr_btm brdr_rgt" style="width:10px;padding:5px;">TOTAL PAYABLE</th>
							
							
						</tr>
					<thead>
                     <tbody>
					 
					<?php 
						
						$cnt=0; $totalprice=0;
						foreach($poitenm as $row){ 
						
						$cnt++;
						$itetype=$row["category_fk"];
						$whotype="";
						if($itetype=='1'){ $whotype="Stationary"; $cat="Stationary"; }
						if($itetype=='2'){ $whotype="Consumables"; $cat="Lab Consumables"; }
						if($itetype=='3'){ $whotype="Reagent"; $cat="Reagent"; }

						?>
						<tr>
                            <td class="brdr_btm brdr_rgt" style="padding:5px;" ><?= $cnt; ?></td>
							<td class="brdr_btm brdr_rgt" style="padding:5px;" ><?= $whotype ?></td>
						<td class="brdr_btm brdr_rgt" style="padding:5px;" ><?= $row["reagent_name"]; ?></td>
						<td class="brdr_btm brdr_rgt" style="padding:5px;" ><?= $row["itemnos"]; ?></td>
						<td class="brdr_btm brdr_rgt" style="padding:5px;" ><?= $row["itenqty"]; ?></td>
						<td class="brdr_btm brdr_rgt" style="padding:5px;" ><?= $row["peritemprice"]; ?></td>
						<td class="brdr_btm brdr_rgt" style="padding:5px;"><?=  $row["peritemprice"]*$row["itemnos"]*$row["itenqty"]; ?></td>
						<td class="brdr_btm brdr_rgt" style="padding:5px;" ><?= $row["itemdis"]; ?></td>
						<td class="brdr_btm brdr_rgt" style="padding:5px;" ><?= $row["itemtxt"]; ?></td>
						<td class="brdr_btm brdr_rgt" style="padding:5px;" ><?= $row["itemprice"]; ?></td>
							           
                        </tr>
						<?php $totalprice +=$row["itemprice"]; } ?>
						
						<tr>
						<td class="brdr_btm brdr_rgt" style="padding:5px;" colspan="8"></td>
						<td class="brdr_btm brdr_rgt" style="padding:5px;" >Discount(%)</td>
						<td class="brdr_btm brdr_rgt" style="padding:5px;"><?= $query[0]["discount"]; ?></td>
						</tr>
						<tr>
						<td class="brdr_btm brdr_rgt" style="padding:5px;" colspan="8"></td>
						<td class="brdr_btm brdr_rgt" style="padding:5px;" >Total Amount Rs.</td>
						<td class="brdr_btm brdr_rgt" style="padding:5px;" ><?= round($totalprice-($totalprice*$query[0]["discount"]/100)); ?></td>
						</tr>
					 
	
                     </tbody>
				</table>
		    </div>
            	

            <div class="main_set_pdng_div">
                <div class="brdr_full_div">

                    <div class="testreport_full">	

                        <div class="full_div">
                           
                        </div>

                        <div class="full_div">
						
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
		    <div class="tst_rprt">
			<div class="tst_rprt_title" style="width:100%;float:left;text-align:center;">
			    <h3>Terms & Conditions of Purchase</h3>
			</div>
		  </div>
			
		    <div class="full_div">
			<ul style="list-style-type: decimal;">
			
			<li><h4>Mode of Payment :</h4></li>
<ul style="list-style-type: lower-alpha;">
  <li>Payment will usually be made in A/c Payee Cheque drawn on Nationalised Bank  after successful compliance of this purchase order.</li>
  <li>Deduction on account of Income Tax, Sales Tax/Vat will be made wherever applicable as per statute.</li>
</ul>


	<li><h4>Advance payment against Proforma Bill: Payment against proforma bills / invoice the same should be released on full compliance of Purchase Order and satisfactory installation of the product wherever necessary. Final Bill / Invoice with Challan / money receipt etc. to be furnished after delivery of goods for which payments are received against Proforma Invoice.</h4></li>
	
	<li><h4>The Price of any item mentioned in this order should not exceed the accepted price. The quantity / no. of item may vary in the order without any change in the accepted price.</h4></li>
	
	<li><h4>In case of Import the following documents are to be furnished.</h4></li>
	
	<li><h4>In case of Import the following documents are to be furnished.</h4></li>
	<ul style="list-style-type: lower-alpha;">
	
	<li>Supplier’s Invoice indicating, inter alia description and specification of the goods, quantity, unit price, total value, date of delivery.</li>
	<li>Packing list (with cost) / Post parcel wrapper (with cost) wherever applicable.</li>
	<li>Certificate of Country Origin.</li>
	<li>Insurance Certificate.</li>
	<li>Railway receipt (in case of domestic suppliers) / Consignment note / Bilty.</li>
	<li>Manufacturer’s certificate</li>
	<li>Bill of ship loading / Airway Bill.</li>
	<li>Any other documents</li>
	
	</ul>
	
	</ul>
	
	
		    </div>
			
		</div>
</pagebreak>	
								
					
					</div></body></html>
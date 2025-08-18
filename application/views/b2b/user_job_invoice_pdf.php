<html>
    <head>
        <meta charset="utf-8">
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
        <style>
            body {font-family: 'Roboto', sans-serif;}
            .pdf_container {width: 970px; margin: 0 auto;}
            .main_set_pdng_div {width: 100%; float: left;}
            .brdr_full_div {border: 0px solid #444444; float: left; padding: 10px; width: 100%;}
            .full_div {width: 100%; float: left;}
            .header_full_div {float: left; padding: 6px 40px; width: 92%; border-bottom: 1px solid #000000;}
            .set_logo {width: 180px; float: right;}

            .rgtsign_img_divfull {width: 100%; float: left; text-align: center;}
            .rgtsign_img_divfull h1 { font-size: 20px; color: #3b3b3b; font-weight: bold; margin-bottom: 5px; text-transform: uppercase; margin-top: 15px;}
            .rgtsign_img_divfull h2 {color: #3b3b3b; font-size: 16px; margin: 0; font-weight: normal;}
            .rgtsign_img_divfull h2 span {color: #046604;}
            .rgtsign_img_divfull h5 {color: #3b3b3b; font-size: 14px; font-weight: normal; margin-bottom: 0; margin-top: 5px;}
            .rgtsign_img_divfull h5 span {color: #046604;}
            .rgtsign_img_divfull p {color: #3b3b3b; font-size: 12px; margin-top: 5px;}
            .invc_brdrdiv_titl_div {background-color: #f6f6f6; border: 1px solid #cccccc; float: left; padding: 10px 15px; width: 96.8%;}
            .invc_brdrdiv_titl_div_span {font-size: 14px; color: #000; font-weight: bold; padding: 10px 15px;}
            .mdl_tbl_full { margin-bottom: 5px; padding: 3px 15px; width: 100%; border-bottom: 1px solid #cccccc; display: table; border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc; border-right: 1px solid #cccccc;}
            .mdl_tbl_full tr td {padding: 3px 0; font-size: 12px;}
            .mdl_tbl_full tr td:nth-of-type(1) {width: 300px; color: #777777; text-transform: uppercase;}
            .mdl_tbl_full tr {margin-bottom: 10px;}
            .bookng_smry_titl {color: #373737; float: left; font-size: 16px; font-weight: bold; margin: 0 0 10px 0;}

            .ordr_smry_full {width: 100%; float: left;}
            .ordr_smry_cl6_lft {border: 1px solid #f4f4f4; display: table; float: left; margin-right: 15px; width: 46.2%;}
            .ordrsmry_cl6_back {background-color: #f4f4f4; float: left; padding: 8px; text-align: center; vertical-align: middle; width: 125px;}
            .ordrsmry_cl6_back p {font-size: 14px;}
            .ordrsmry_cl6_dotted_brdr {float: left; padding: 5px; vertical-align: middle;}
            .ordrsmry_cl6_dotted_brdr p {border: 1px dotted #ededed; margin: 0; min-height: 55px; text-align: center; font-size: 14px; padding-top: 4px;}

            .ordr_smry_cl6_rgt {border: 1px solid #f4f4f4; float: left; margin-left: 15px; width: 97%;}
            .ordrsmry_cl6_tbl {float: left; width: 100%; border-collapse: collapse;}
            .ordrsmry_cl6_tbl tr td {padding: 5px 10px; font-size: 12px;width:75%;}
            .ordrsmry_cl6_tbl tr td {border-right: 1px solid #f4f4f4; border-bottom: 1px solid #f4f4f4;}
            .ordrsmry_cl6_tbl tr td:last-child {border-right: none; text-align:right;}
            .ordrsmry_cl6_tbl tr:last-child td {border-bottom: none;}
            .ordrsmry_cl6_tbl tr td:nth-of-type(1) {text-transform: uppercase;text-align:right;}

            .invc_sctn_3_full_back {background-color: #838383; float: right; margin-top: 20px; padding: 10px 15px; text-align: right; width: 95%; margin-bottom: 5px;}
            .invc_sctn_3_full_back p {color: #ffffff; font-weight: bold; margin: 0;}
            .invc_sctn_3_full_back p span {color: #ffe439; margin-left: 20px;}

            .pymntsmry_tbl {display: table; width: 100%; border-collapse: collapse; margin-bottom: 1px;}
            .pymntsmry_tbl tr {border: 1px solid #f4f4f4;}
            .pymntsmry_tbl tr td {border: 1px solid #f4f4f4;}
            .pymntsmry_tbl tr:nth-of-type(1) {background-color: #f4f4f4;}
            .pymntsmry_tbl tr:nth-of-type(1) td {font-weight: bold;}
            .pymntsmry_tbl tr td {padding: 8px;}
            .pymntsmry_tbl tr:last-child td:last-child {color: #d01130; font-size: 18px; font-weight: bold;}

            .foot_num_div {width: 100%; float: left; padding-bottom: 10px; border-bottom: 2px dotted #E30026;}
            .foot_num_p {text-align: center; margin-bottom: 10px;}
            .foot_num_p span {background-color: #E30026; border-radius: 25px; padding: 3px 15px; color: #fff;}
            .foot_lab_p {margin: 0; text-align: center; text-transform: uppercase;}
            .lst_ison_ul {display: inline-block; padding: 0; text-align: center; width: 100%; amrgin-top: 5px;}
            .lst_ison_ul li {display: inline-block; margin-right: 15px;}
            .lst_icon_spn_back {background-color: #e30026; border-radius: 50%; float: left; height: 16px; margin-right: 9px; padding: 4px; width: 16px;}
            .lst_icon_spn_back .fa {color: #fff;}
            .lst_airmed_mdl {float: left; margin-bottom: 0; margin-top: 9px; text-align: center; width: 100%;}
            .lst_31_addrs_mdl {float: left; margin: 0 0 0 0; text-align: center; width: 100%;}

            .half_div{width:50%; float:left;}
            .ordr_smry_cl12_rgt{width:100%; float:left; margin-top:10px;}
            .ordrsmry_cl6_tbl tr td {font-size: 20px;}
        </style>
    </head>
    <div class="rgtsign_img_divfull">
        <h1>Invoice</h1>
        <h2>thank you for your order</h2>
        <h2>Your Registration No. is <span><?php echo $barcode_detail[0]["id"]; ?></span></h2>
       <?php /* <h5>We have sent you an email confirmation at : <span><?php echo $query[0]['email']; ?></span></h5> */ ?>
        <p>In case of any questions, please call us at: <b>+91 8101 161616</b></p>
    </div>

    <h2 class="bookng_smry_titl">Booking Summary </h2>
    <div class="invc_brdrdiv_titl_div">
        <span class="invc_brdrdiv_titl_div_span">Booking ID : <?php echo $job_details[0]['order_id']; ?></span>
    </div>
    <table class="mdl_tbl_full">
        <tbody>
            <tr>
                <td>Booking Date :</td>
                <td><b><?php echo date("l,F d,Y", strtotime($barcode_detail[0]["createddate"])); ?> </b></td>
            </tr>

                <tr>
                    <td>Billing Name :</td>
                    <td><b><?php echo ucfirst($job_details[0]['customer_name']); ?> </b></td>
                </tr>
          <?php  if($job_details[0]['mobile'] != ""){ ?>  
            <tr>
                <td>Registered mobile no. :</td>
                <td><b><?php echo "(+91) " . $job_details[0]['mobile']; ?> </b></td>
            </tr>
			<?php } ?>
            <tr>
                <td>Referred By :</td>
                <td><b><?php echo ucfirst($job_details[0]['doctor']); ?> </b></td>
            </tr>

            <tr>
                <td>Sample collection address :</td>
                <td><b><?php  echo $job_details[0]['customer_address']; ?></b></td>
            </tr>
            <tr>
                <td>Email ID :</td>
                <td><b><?php
                        if ($job_details[0]['customer_email'] != "noreply@airmedlabs.com") {
                            echo $job_details[0]['customer_email'];
                        }
                        ?></b></td>
            </tr>
        </tbody>
    </table>

    <h2 class="bookng_smry_titl">Order Summary</h2>
    <div class="ordr_smry_full">
        <div class="half_div">
            <div class="ordrsmry_cl6_back">
               
                <?php
                    $p_name = ucfirst($job_details[0]['customer_name']);
                    $p_gender = ucfirst($job_details[0]['customer_gender']);
                
                ?>
                <p><?php echo $p_name; ?> <br/> <?php  echo $job_details[0]['age'];
                   if (strtoupper($p_gender) == 'MALE') {
                        echo "/M";
                    } else if (strtoupper($p_gender) == 'FEMALE') {
                        echo "/F";
                    }
                    ?></p>
            </div>
            <div class="ordrsmry_cl6_dotted_brdr">
                <p> </p>
            </div>
        </div>
        <div class="half_div" style="font-size: 15px;">
            <div class="ordr_smry_cl6_rgt">
                <table class="ordrsmry_cl6_tbl">
                    <tbody>
                        <tr>
                            <td><b>Test/Package</b></td>
                            <td><b>Our price</b></td>
                        </tr>
                        <?php
						 $ttl_price = 0;
                        foreach($test_list as $testname) {
							$getprice=getmrpspricalprice($testname['testtype'],$barcode_detail[0]["collect_from"],$testname['test_fk']);
							if($getprice[0]["price_special"] != ""){ $tprice=round($getprice[0]["price_special"]); $ttl_price += round($getprice[0]["price_special"]);  }else{ $tprice=round($testname['price']); $ttl_price += round($testname['price']); }
                            
                            ?>
                            <tr>
                                <td style="font-size: 17px;"><?php echo ucfirst($testname['testname']); ?></td>
                                <td style="font-size: 17px;">Rs. <?php echo round($tprice); ?></td>
                            </tr>
<?php } ?>

                    </tbody>
                </table>
            </div>

            <div class="ordr_smry_cl6_rgt" style="margin-top:10px; border:1px solid #3b3b3b;"></div>
            <div class="ordr_smry_cl12_rgt">
                <div class="ordr_smry_cl6_rgt">

                    <table class="ordrsmry_cl6_tbl">
                        <tbody>
                            <tr>
                                <td><b>Collection Charge </b></td>
                                <td>Rs.<?php echo round($job_details[0]["collection_charge"]); ?></td>
                            </tr>
							 <tr>
                                <td><b>Total </b></td>
                                <td>Rs.<?php echo (round($ttl_price)+round($job_details[0]["collection_charge"])); ?></td>
                            </tr>

                          
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</html>
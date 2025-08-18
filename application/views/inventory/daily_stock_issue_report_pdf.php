
<html>
    <head>
        <meta charset="utf-8">
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
        <style>
            body {font-family: 'Roboto', sans-serif; font-size: small}
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

    <table border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
        <tr>
            <td colspan="4" style="width:60%"><h3><center>AIRMED PATHOLOGY PRIVATE LIMITED, AHMEDABAD </center></h3></td>
            <td rowspan="3" style="width:20%"><img class="set_logo" src="logo.png" style="margin:0px;"/></td>
        </tr>
        <tr>
            <td colspan="2"><h3><center>STOCK & ISSUE REGISTER<center></h3></td>
                            <td colspan="2"><h3><center>FORMS & FORMATS<center></h3></td>
                                            </tr>
                                            <tr>
                                                <td>Doc. No : Form 08</td>
                                                <td>Rev. No : 00 </td>
                                                <td>Rev. Dt :  <?php echo date('d-m-Y') ?></td>
                                                <td>Page 1 of 1</td>
                                            </tr>
                                            </table>
                                            <br/>
                                            <br/>
                                            <br/>
                                            <br/>
<!--<pagebreak>-->
                                            <div class="ordr_smry_cl12_rgt">
                                                <div class="ordr_smry_cl6_rgt">
                                                    <table border="1" cellpadding="0" cellspacing="0" style="width:110%; border-collapse:collapse;">

                                                        <thead>
                                                            <?php /* <tr>
                                                              <td colspan="2" style="text-align: left;"><h4>Item Name: </h4></td>
                                                              <td colspan="5" style="text-align: left;"><h4><?php
                                                              if ($query[0]['reagent_name'] != "") {
                                                              echo $query[0]['reagent_name'];
                                                              } else {
                                                              "-";
                                                              }
                                                              ?> </h4></td>
                                                              <td colspan="1" style="text-align: left;"><h4>Page No:</h4></td>
                                                              <td colspan="1" style="text-align: left;"><h4></h4></td>
                                                              </tr>

                                                              <tr>
                                                              <td colspan="2" style="text-align: left;"><h4>Location: </h4></td>
                                                              <td colspan="5" style="text-align: left;"><h4>Refrigerator 1/3 </h4></td>
                                                              <td colspan="1" style="text-align: left;"><h4>Cup Board No :</h4></td>
                                                              <td colspan="1" style="text-align: left;"><h4></h4></td>
                                                              </tr>


                                                              <tr>
                                                              <th><h4>No.</h4></th>
                                                              <th><h4>Date</h4></th>
                                                              <th><h4>Company</h4></th>
                                                              <th><h4>Pack Size</h4></th>
                                                              <th><h4>Lot No.</h4></th>
                                                              <th><h4>Expiry</h4></th>
                                                              <th><table style="width:100%;">
                                                              <tr>
                                                              <th colspan="4"> <center><h4>Stock</h4></center></th>
                                                              </tr>
                                                              <tr>
                                                              <th><h4>Opening</h4></th>
                                                              <th><h4>Received</h4></th>
                                                              <th><h4>Issue</h4></th>
                                                              <th><h4>Closing</h4></th>
                                                              </tr>
                                                              </table></th>
                                                              <th><h4>Name</h4></th>
                                                              <th><h4>Sign</h4></th>
                                                              </tr> */ ?>
                                                            <tr>
<!--                                                                <th colspan="2">Item Name:</th>
                                                                <th colspan="8"><?php
//                                                                    if ($query[0]['reagent_name'] != "") {
//                                                                        echo $query[0]['reagent_name'];
//                                                                    } else {
//                                                                        "-";
//                                                                    }
                                                                ?></th>-->

                                                                <th colspan="2">Date :</th>
                                                                <th colspan="9"><?php
                                                                    echo date('d/m/Y', strtotime($start_date));
                                                                    ?></th>

                                                                <th colspan="2">Page No.</th>
                                                            </tr>
                                                            <tr>
                                                                <th colspan="2">Location Name:</th>
                                                                <th colspan="9"><?php
                                                                    echo $branch_name[0]['branch_name'];
                                                                    ?></th>
<!--                                                                <th colspan="8"><center>Refrigerator 1/3</center></th>-->
                                                                <th colspan="2"></th>
                                                            </tr>
                                                            <tr>
                                                                <td rowspan="2">Sr. No.</td>
                                                                <td rowspan="2">Date</td>
                                                                <td rowspan="2">Item</td>
                                                                <td rowspan="2">Company</td>
                                                                <td rowspan="2" style="width: 5%">Pack Size</td>
                                                                <td rowspan="2" style="width: 5%">Lot No.</td>
                                                                <td rowspan="2">Expiry</td>
                                                                <td colspan="4"><center>Stock</center></td>
                                                        <td rowspan="2">Name</td>
                                                        <td rowspan="2">Sign</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Opening</td>
                                                            <td>Received</td>
                                                            <td>Issue</td>
                                                            <td>Closing</td>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
//                                        $temp = explode('-', $start_date);
//                                        $s_date = $temp[2];
//                                        $month = $temp[1];
//                                        $year = $temp[0];
//                                        $temp2 = explode('-', $end_date);
//                                        $e_date = $temp2[2];

                                                            if ($start_date != "" && $end_date != "" && $branch != "") {
                                                                $cnt = 0;
                                                                foreach ($new_array as $row) {
                                                                    $cnt++;
                                                                    ?>
                                                                    <tr>
                                                                        <td><?php echo $cnt ?></td>
                                                                        <td><?php echo date('d-m-Y', strtotime($row['date'])) ?></td>
                                                                        <td><?php echo $row['item'] ?></td>
                                                                        <td><?php echo $row['compony'] ?></td>
                                                                        <td><?php echo $row['pack_size'] ?></td>
                                                                        <td><?php echo $row['lot'] ?></td>
                                                                        <td><?php echo $row['expire_date'] ?></td>
                                                                        <td><?php echo $row['opening'] ?></td>
                                                                        <td><?php echo $row['received'] ?></td>
                                                                        <td><?php echo $row['issue'] ?></td>
                                                                        <td><?php echo $row['closing'] ?></td>
                                                                        <td>-</td>
                                                                        <td>-</td>
                                                                    </tr>                                                
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                            <?php if ($cnt == 0) {
                                                                ?>
                                                                <tr>
                                                                    <td colspan="12"><center>No records found</center></td>
                                                            </tr>
                                                        <?php }
                                                        ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            </html>
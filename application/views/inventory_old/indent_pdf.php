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
            .mdl_tbl_full thead th{display: block;}
     table {
    width:100%;
}
table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
}
th, td {
    padding: 5px;
    text-align: left;
}
table#t01 tr:nth-child(even) {
    background-color: #eee;
}
table#t01 tr:nth-child(odd) {
   background-color:#fff;
}
table#t01 th {
    background-color: black;
    color: white;
}
table.sec_tbl{margin-top:20px;}
        </style>
    </head>
   
    <div>
        <?php $odate =explode(" ",$query[0]['created_date']);
            $time = $odate[1];
            $date = explode("-", $odate[0]);
           $date_formate  =$date[2].'-'.$date[1].'-'.$date[0].' '.$time; 
        ?>
      Indent No:<?php echo $query[0]['indent_fk'];?><br>
      Indent Date : <?php echo $date_formate;?><br>
       Department:<br>
         Name of Indent :<br>

   <table>
  

        <tr>
            <th>SR NO.</th>
            <th>DESCRIPTION OF ITEM</th>
            <th>CATEGORY NAME</th>
            <th>UNIT</th>
            <th>QTY</th>
            <th>SUGGESTED COMPANY</th>
            <th>REMARK</th>
        </tr>
      
            <?php $cnt = 1;foreach($query as $val){ ?>
            <tr>
                <td><?php echo $cnt;?></td>
                <td><?php echo ucfirst($val['ReagentName']);?></td>
                <td><?php echo ucfirst($val['CategoryName']);?></td>
                <td><?php echo ucfirst($val['UnitName']);?></td>
                <td><?php echo $val['quantity'];?></td>
                <td></td>
                <td></td>
            </tr>
            <?php $cnt++;} ?>
        
        
    </table>
   
    <table class="sec_tbl">
        <tr>
            <td>Prepared by:<?php echo ucfirst($query[0]['admin_name_created']);?></td>
            <td>Authorized by:<?php echo ucfirst($query[0]['admin_name_approve']);?></td>
            <td>Received by:</td>
        </tr>
    </table>

        
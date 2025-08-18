<html>
    <head>
        <meta charset="utf-8">
        <style>
            /*            body {font-family: 'Roboto', sans-serif; font-size: small}*/
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
            body{
                font-size:12px;
            }
            table {
                border-collapse: collapse;
                width: 100%;
            }
            table, td, th {
                border: 1px solid black;
            }
            .left{text-align:left;}
            .bg{background:silver;}
        </style>

    </head>
    <body>
        <?php /*
          if (isset($_GET['month']) && $_GET['month'] != "") {
          $employee_name = $new_array1[0]['emp_name'];
          echo "<h3>" . $employee_name . " - " . date("F", mktime(0, 0, 0, $_GET['month'], 10)) . " " . date('Y') . "</h3>";
          } */
        ?>

        <table>
            <tr>
                <th colspan="6" style="padding:10px;font-size:14px;"><center><u>ANNEXURE-I</u></center></th>
    </tr>
    <tr>
        <th colspan="4"><center><u>SALARY BREAK-UP</u></center></th>
<td>
    <?php echo $today - $new_array1[0]['leave']; ?>
</td>
<td>Present days</td>
</tr>
<tr>
    <th class="left">NAME</th>
    <td colspan="3">
        <?php echo ucfirst($new_array1[0]['emp_name']); ?>
    </td>
    <td>
        <?php echo $new_array1[0]['joining_salary']; ?>
    </td>
    <td>Monthly CTC</td>
</tr>
<tr>
    <th class="left">DESIGNATION</th>
    <td colspan="3">
        <?php echo $new_array1[0]['designation_name'] ?>
    </td>
    <td><?php echo $new_array1[0]['sunday_wages']; ?></td>
    <td>Sunday Wages </td>
</tr>
<tr>
    <th class="left">DEPARTMENT</th>
    <td colspan="3">
        <?php echo $new_array1[0]['department_name']; ?>
    </td>
    <td>
        <?php echo $today ?></td>
    <td>Days in a month</td>
</tr>
<tr>
    <th class="left">DATE OF JOINING</th>
    <td colspan="3">
        <?php echo $new_array1[0]['date_of_joining'] ?>
    </td>
    <td>
        <?php echo $new_array1[0]['working_hr']; ?>
    </td>
    <td>Working hours</td>
</tr>
<tr>
    <th class="left">MONTH</th>
    <td colspan="3"><?= date("F", mktime(0, 0, 0, $_GET['month'], 10)) . " " . date('Y') ?></td>
    <td><?php echo $new_array1[0]['ot'] ?></td>
    <td>OT hours</td>
</tr>
<tr>

    <th colspan="2"><center><u></u></center></th>
<th colspan="2"><center><u></u></center></th>
<td>
    <?php echo $new_array1[0]['festival_allowance']; ?>
</td>
<td>Festival Days</td>
</tr>


<tr>
    <th class="left bg" colspan="2"><u>SALARY HEAD</u></th>
    <th class="left bg"><u>AMOUNT (Rs.)</u></th>
    <th class="left bg" colspan="2"><u>TYPE</u></th>
    <td></td>
</tr>

<?php
if (isset($new_array1[0]['attendance_data'])) {
    foreach ($new_common_salary_structure as $csskey) {
        foreach ($new_array1[0]['attendance_data'] as $sal) {


            if (strtoupper($csskey["salary_name"]) == strtoupper($sal["salary_field"])) {
                if ($sal['value'] > 0) {
                    $class = '';
                    if (in_array(strtoupper($sal['salary_field']), array("SALARY (CTC) / PM", "NET TAKE HOME"))) {
                        $class = ' class="left bg"';
                    }
                    ?>
                    <tr>
                        <td colspan="2" <?= $class ?>>
                            <?php if (in_array(strtoupper($sal['salary_field']), array("SALARY (CTC) / PM", "NET TAKE HOME"))) {
                                echo "<b>";
                            } ?>
                            <?php echo $sal['salary_field'] ?>
                    <?php if (in_array(strtoupper($sal['salary_field']), array("SALARY (CTC) / PM", "NET TAKE HOME"))) {
                        echo "</b>";
                    } ?>
                        </td>
                        <td <?= $class ?>>
                            <?php echo $sal['value'] ?>
                        </td>
                        <td colspan="2" <?= $class ?>>
                            <?php
                            if ($class == '') {
                                if ($sal['earning_type'] == 1) {
                                    echo "EARNING";
                                } else {
                                    echo "DEDUCTION";
                                }
                            }
                            ?>
                        </td>
                        <td <?= $class ?>></td>
                    </tr>
                    <?php
                }
            }
        }
    }
}
?>

</table>

</body>
</html>
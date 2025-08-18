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
            }
            table, td, th {
                border: 1px solid black;
            }
            .left{text-align:left;}
            .bg{background:silver;}
        </style>

    </head>
    <body>
        <?php
        if (isset($_GET['month']) && $_GET['month'] != "") {
            $employee_name = (isset($query->name)) ? $query->name : $all_record[0]->name;
            echo "<h3>" . $employee_name . " - " . date("F", mktime(0, 0, 0, $_GET['month'], 10)) . " " . date('Y') . "</h3>";
        }
        ?>

        <table>
            <tr>
                <th colspan="6" style="padding:10px;font-size:14px;"><center><u>ANNEXURE-I</u></center></th>
    </tr>
    <tr>
        <th colspan="4"><center><u>SALARY BREAK-UP</u></center></th>
<td>
    <?php echo isset($total_present_day) ? $total_present_day : $all_record[0]->present_day; ?>
</td>
<td>Present days</td>
</tr>
<tr>
    <th class="left">Name</th>
    <td colspan="3">
        <?php echo $name = isset($query->name) ? $query->name : $all_record[0]->name ?>
        <input type="hidden" name="name" value="<?php echo $name; ?>" />
    </td>
    <td>
        <?php echo isset($query->joining_salary) ? $query->joining_salary : $all_record[0]->monthly_ctc; ?>
    </td>
    <td>Monthly CTC</td>
</tr>
<tr>
    <th class="left">DESIGNATION</th>
    <td colspan="3">
        <input type="hidden" name="designation" value="<?php echo isset($designation_data->name) ? $designation_data->name : $all_record[0]->designation_fk; ?>"/>
        <?php echo isset($designation_data->name) ? $designation_data->name : $all_record[0]->designation_fk; ?>
    </td>
    <td></td>
    <td>Sunday Wages </td>
</tr>
<tr>
    <th class="left">DEPARTMENT</th>
    <td colspan="3">
        <?php echo isset($department_data->name) ? $department_data->name : $all_record[0]->department_fk; ?>
    </td>
    <td>
        <?php echo $today ?></td>
    <td>days in a month</td>
</tr>
<tr>
    <th class="left">DATE OF JOINING</th>
    <td colspan="3">
        <?php echo isset($query->date_of_joining) ? $query->date_of_joining : $all_record[0]->joining_date ?>
    </td>
    <td>
        <?php echo $all_record[0]->working_hr; ?>
    </td>
    <td>Working hours</td>
</tr>
<tr>
    <th></th>
    <td colspan="3"></td>
    <td><?php echo $all_record[0]->ot ?></td>
    <td>OT hours</td>
</tr>
<tr>
<!--    <th colspan="2"><center><u>EARNINGS</u></center></th>
<th colspan="2"><center><u>DEDUCTIONS</u></center></th>-->
    <th colspan="2"><center><u></u></center></th>
<th colspan="2"><center><u></u></center></th>
<td>
    <?php echo isset($festivals) ? $festivals : $all_record[0]->festivals; ?>
</td>
<td>Festival Days</td>
</tr>

<tr>
    <th class="left bg"><u>SALARY HEAD</u></th>
<th class="bg"><u>AMOUNT (Rs.)</u></th>
<th class="bg"><u>TYPE</u></th>
<!--<th class="left bg"><u>SALARY HEAD</u></th>
<th class="bg"><u>AMOUNT (Rs.)</u></th>-->
<td></td>
<td></td>
<td></td>
</tr>

<?php
if (isset($salary_structure_exist)) {
    foreach ($salary_structure_exist as $sal1) {
        //if ($sal1['sal_type'] == 1) {
        ?>
        <tr>
            <td>
                <?php echo $salary_value1 = ($sal1['salary_name'] != "") ? $sal1['salary_name'] : "Other" ?>
            </td>
            <td>
                <?php echo $sal1['salary_value']; ?>
            </td>
            <td>
                <?php
                if ($sal1['sal_type'] == 1) {
                    echo "Earning";
                } else {
                    echo "Deduction";
                }
                ?>
            </td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <?php //} else { ?>
        <!--            <tr>
            <td></td><td></td>
            <td>
        <?php //echo $salary_value1 = ($sal1['salary_name'] != "") ? $sal1['salary_name'] : "Other" ?>
            </td>
            <td>
        <?php //echo $sal1['salary_value']; ?>
            </td>
            <td></td>
            <td></td>
        </tr>                                -->
        <?php //}
        ?>
        <?php
    }
} else {
    if (isset($salary_structure_emp)) {
        foreach ($salary_structure_emp as $sal) {
            // if ($sal['plus_minus'] == 1) {
            ?>
            <tr>
                <td>
                    <?php echo $salary_value = ($sal['salary_name'] != "") ? $sal['salary_name'] : "Other" ?>
                </td>
                <td class="<?php
                if ($sal['plus_minus'] == 1) {
                    echo "plus1";
                } else {
                    echo "minus1";
                }
                ?>">    
                        <?php echo $sal['salary_value']; ?>
                </td>

                <td>
                    <?php
                    if ($sal['plus_minus'] == 1) {
                        echo "Earning";
                    } else {
                        echo "Deduction";
                    }
                    ?>
                </td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <?php //} else {
            ?>
            <!--                <tr>
                <td></td><td></td>
                <td>
            <?php //echo $salary_value = ($sal['salary_name'] != "") ? $sal['salary_name'] : "Other" ?>
                </td>
                <td class="minus1">
            <?php //echo $sal['salary_value']; ?>
                </td>
                <td></td>
                <td></td>
            </tr>-->
            <?php
            //}
        }
    }
}
?>


<tr>
    <th class="left">Total Earning</th>
    <td id="plus_sum"><?php
        if (isset($total_plus) && $total_plus != "") {
            echo $total_plus;
        } else {
            echo $total_plus1;
        }
        ?></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
</tr>            

<tr>
    <th class="left">TOTAL DEDUCTION</th>
    <td id="minus_sum"><?php
        if (isset($total_minus) && $total_minus != "") {
            echo $total_minus;
        } else {
            echo $total_minus1;
        }
        ?></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
</tr>

<tr>
    <th class="left">SALARY (CTC) / PM</th>
    <td id="salary_other"><?php
        if (isset($salary_other) && $salary_other != "") {
            echo $salary_other;
        } else {
            echo $salary_other1;
        }
        ?></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
</tr>

<tr>
    <td style="padding:10px"></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
</tr>
<tr>
    <td>Net Take Home</td>
    <td id="net_take_home"><?php
        if (isset($take_home) && $take_home != "") {
            echo $take_home;
        } else {
            echo $take_home1;
        }
        ?></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
</tr>
</table>

</body>
</html>
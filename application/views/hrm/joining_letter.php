
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

    <p>Date:-<b><?php echo date('d/m/Y') ?></b></p>
    <p><b>To,</b></p>

    <?php
    if ($data->gender == "female") {
        $mr_or_mrs = "Mrs.";
    } else {
        $mr_or_mrs = "Mr.";
    }
    ?>

    Dear <?= $mr_or_mrs ?> <b><?= $data->name ?></b>,<br/>

    <p>We refer to your application for employment and subsequent interview with us. We are pleased to <b>APPOINT</b> you as  
        <b><?php
            if (isset($designation->name) && $designation->name != "") {
                echo $designation->name;
            }
            ?> </b> in our organization at  <b><?php
            if (isset($city->name) && $city->name != "") {
                echo $city->name;
            }
            ?></b></p>

    <p>1.Your posting will be presently at Airmed Pathology Private Ltd., 31 Ambika society ,near Usmanpura garden, Next to Nabard bank,Usmanpura,Ahmedabad. However, during employment of Company, you may be posted / transferred to any of the offices / projects / divisions / departments / units / subsidiaries /  sister concerns of the Company, existing or to be set up at any other location in India or abroad, without any additional remuneration, in the interest of the Company without assigning any reasons.</p>

    <p>2.At the time of joining you will be required to provide the following :</p>

    <p>(a)Date of Birth Proof</p>
    <p>(b)Residence Proof (Permanent & Present)</p>
    <p>(c)Educational certificate</p>
    <p>(d)Two Passport size photograph</p>
    <p>(e)Copy of relieving letter from your present employer, if you are employed</p>
    <p>(f)Latest Pay Slip. </p>

    <p>All documents require attested either by authorized signatory or self.</p>

    <p>3.Your Consulting Fee is fixed at Rupees Rs. <b><?php
            if (isset($data->joining_salary) && $data->joining_salary != "") {
                echo $data->joining_salary;
            }
            ?>/-</b> per Annum (Rs. <b><?php
            if (isset($data->joining_salary) && $data->joining_salary != "") {
                echo $data->joining_salary * 12;
            }
            ?></b>/-). (Inclusive All).</p>

    <p>4.You will be required to comply with the requirements of the different acts.</p>

    <p>5.Company rules and guidelines are mentioned in Annexure – B.</p>

    <p>6.You shall devote your full time and attention to the work assigned to you. You shall at all times obey and abide by the lawful directions and orders given to you by your supervisors and shall work diligently, faithfully and well. The company shall be the sole judge to determine whether the work assigned to you is suitable or not and you shall not cease performing a part of the whole of your duties unilaterally.</p>

    <p>7.You shall not accept any other employment, part-time or otherwise or engage in any commercial business or pursuit on your own account or as an agent for others.</p>

    <p>8.During the course of your employment with the company it is agreed that information parted to you with respect to products, processes and financial data used or developed by the company or its affiliates will be following conditions:</p>

    <p>a.You shall be responsible for the safe-keeping and return in good condition and order of all the company’s property which may be in your use, custody or charge.
        b. You will treat as trade secret all confidential or specialized data or information acquired by you during the course of your employment, and will not use any such trade secret for our own benefit nor disclose them to any other person/firm/association or corporation or one of its affiliates during the course of your employment or thereafter and shall submit yourself for any disciplinary action for breach of this condition. In such an event, the liquidated damages a foresaid will be due and payable by you to the company.</p>

    <p>9.You, if provided with residential quarters, shall vacate such quarters as and when the company requires you to do it in any case at the termination of your services. One month notice shall be given for vacating such quarters.</p>

    <p>10.You shall keep the company informed of any change in your residential address and civil status.</p>

    <p>11.You shall abide by the rules and regulations of the company which are in force and/or which may be framed from time to time.</p>

    <p>12.You shall give the company the benefit of all inventions and discoveries you may make, and shall, when called upon to do so, assign any such inventions, discoveries or patents to the company and shall sign an agreement to carry this into effect.</p>

    <p>13.Your appointment and its continuance is subject to your being and remaining medically (physically and mentally) Fit. The management shall have the right to get you medically examined periodically or any time by any Registered Medical Practitioner of their choice, who’s opinion as your fitness or otherwise shall be final and binding on you.</p>

    <p>14.Your appointment and its continuance is subject to your being and remaining medically (physically and mentally) Fit. The management shall have the right to get you medically examined periodically or any time by any Registered Medical Practitioner of their choice, who’s opinion as your fitness or otherwise shall be final and binding on you.</p>

    <p>15.If there is any dispute between the parties hereto the jurisdiction to entertain and try such disputes shall rest exclusively in a court in Ahmedabad only.</p>

    <p>16.If, at any time, in the opinion of the company which shall be final, you become insolvent or are found guilty of dishonesty, disobedience, misappropriation, theft, fraud, disorderly behavior, negligence, indiscipline, absence from duty without permission or of any other conduct considered by the company as detrimental to its interests or of violation of one or more terms of this appointment, your services any be terminated without notice.</p>

    <p>17.You shall not take any presents, commission, or any kind of gratification in cash or kind from any kind of gratification in cash or kind from any person, party or firm having connection with this
        company and if you are offered any, the same should be handed over to the management of this company.</p>

    <p>18.This appointment is liable to be terminated if in the opinion of the company, you are found guilty of breach of any of the above clauses, insubordination, insolence, gross negligence of duty, dishonesty or embezzlement or accepting any commission or discounts etc. from guest or placing consideration of any nature above the company’s interest may at the time be in your possession.</p>

    <p>19.Your service may be terminated by either side by giving one Month’s notice as applicable to your grade at time of leaving. As per your present grade, this notice period will be of one month. However, no notice shall be required for termination during Probation/training & company shall be entitled to terminate your service without any notice during such period.</p>

    <p>20.The offer is given subject to your information supplied in the application / Biodata from to be absolute true. In the event of any information supplied by you, is found wrong or otherwise, you shall be liable for termination.</p>

    <p>21.Airmed Pathology Pvt. Ltd. reserve the right to forfeit, charge, deduct from the salary, (as applicable, in terms of monetary value of the product) in matters related to losses, theft, damage, etc.</p>

    <p>22.Any dispute arising out of this contract will be subject to the jurisdiction of court of laws at Ahmedabad in the state of Gujarat.</p>

    <p>Please confirm your acceptance of this offer on the above mentioned terms and conditions by returning to us the copy of this letter only duly signed by you.</p>

    <p>We look forward to as long and successful association with you.</p>

    <p>Yours Sincerely</p>

    <b>For Airmed Pathology Pvt. Ltd.</b><br/><br/>


    <p><b>(Authorized Signatory)</b></p>




    <pagebreak />
        <div class ="rgtsign_img_divfull">
            <center><b><u>Acknowledgment and Acceptance</u></b></center><br/><br/>
        </div>

        <p><b>I have read all the terms & conditions contained in this letter. I hereby declare that I have fully understood these terms and agree that they shall remain binding. To confirm my acceptance I have signed the duplicate copy of this letter.</b></p>

        <b><p>Signature: _________________________________ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Date:_________________________</b></p>

    <p><b> Name: <?php echo $data->name ?></b></p>

    <div class="rgtsign_img_divfull">
        <center><b>Annexure – A</b></center><br/>
    </div>

    <p>Name :<b><?= $data->name ?></b></p>
    <p>Designation : <b><?php
            if (isset($designation->name) && $designation->name != "") {
                echo $designation->name;
            }
            ?></b></p>

    <div class="ordr_smry_cl12_rgt">
        <div class="ordr_smry_cl6_rgt">

            <table style="width:80%;">
                <tr>
                    <th style="text-align: left;" class="bg"><u>SALARY HEAD</u></th>
                    <th style="text-align: left;" class="bg"><u>AMOUNT (Rs.) (MONTHLY)</u></th>
                    <th style="text-align: left;" class="bg"><u>AMOUNT (Rs.) (YEARLY)</u></th>
                    <th style="text-align: left;width:100px" class="bg"><u>TYPE</u></th>                    
                </tr>

                <tbody>
                    <?php
                    if (isset($salary_structure_emp1) && count($salary_structure_emp1) > 0) {
                        foreach ($salary_structure_emp1 as $sal) {
                            ?>
                            <?php
                            $b = "";
                            $b1 = "";
                            $class = '';
                            if (in_array($sal['salary_name'], array("SALARY (CTC) / PM", "Net Take Home","Gross Salary"))) {
                                $b = "<b>";
                                $b1 = "</b>";
                                $class = ' class="bg"';
                            }
                            ?>
                            <tr>
                                <td <?= $class ?>>
                                    <?php echo $b . $sal['salary_name'] . $b1 ?>
                                </td>
                                <td <?= $class ?>>
                                    <?php echo $b . $sal['salary_value'] . $b1; ?>
                                </td>
                                <td <?= $class ?>>
                                    <?php
                                    echo $b;
                                    echo $sal['salary_value'] * 12;
                                    echo $b1;
                                    ?>
                                </td>
                                <td <?= $class ?>>
                                    <?php
                                    if (!in_array($sal['salary_name'], array("SALARY (CTC) / PM", "Net Take Home","Gross Salary"))) {
                                        if ($sal['plus_minus'] == 1) {
                                            echo "Earning";
                                        } else {
                                            echo "Deduction";
                                        }
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>

                </tbody>
            </table>




        </div>
    </div>
    <p>
        <b>Terms and Conditions:</b><br/>
    <p>All components  are fixed except Special allowance as PF would be paid under the Special allowance category until liable for deduction.</p>

    <p>TDS deduction will be made as per provision under section 192 of Income tax Act-1961, (If Applicable)</p>

    <p>Call report to be sent on daily basis otherwise it will attract one day leave deduction.</p>

    <b>For Airmed Pathology Pvt. Ltd.</b><br/>

    <p><b>(Authorized Signatory)</b></p>
<pagebreak />
    <div class="rgtsign_img_divfull">
        <center><b>ANNEXURE - B</b></center><br/>
    </div>
    <br/>

    <p><B>Rules and Guidelines</B></p>
    <ul>
    <li>Employees are expected to arrive and to depart on schedule. An employee is considered late when he or she clocks in after his or her starting time. Similarly, an employee leaves early when he or she clocks out prior to the end of the workday without permission from his or her supervisor or in charge.</li>

    <li>Employees leaving work for any authorized personal reason during the day must clock out when leaving campus, and clock in when they return.</li>

    <li>It is not permissible for an employee to clock in for the day and then leave the building to conduct personal business such as eating, smoking or parking one’s vehicle. An employee will be documented as late when such instances occur. </li>

    <li>In the event that an employee fails to clock in or out at any time during the workday, he or she must complete and sign a Missed Punch Form , and submit it to his or her supervisor or in charge for processing. Failure to clock in or out as directed more than two times a week or a pattern of failing to clock in and out on a regular basis may result in disciplinary action. </li>

    <li>An employee’s time record may not be edited for missed punches at any time without a Missed Punch Form which has been signed by both the employee and the supervisor or in charge, Failure to follow designated missed punch procedures can result in disciplinary action. </li>

    <li>Clocking in late on a regular basis, without prior authorization from a supervisor or in charge, is a violation of centre policy. Repeat occurrences may lead to disciplinary action.  </li>

    <li>If an employee is unable to punch in or out due to a time clock malfunction, it is the employee’s responsibility to immediately inform his or her supervisor or in charge. </li>

    <li>Employees who would complete a year in the company on last day of March (in the year of payment) only are eligible for yearly pay hike and is usually paid in the month of April. </li>

    <li>Bonus is not applicable (No Diwali bonus).  </li>

    <li>No paid leaves. Sandwich leave policy will be applicable. On one Uninformed leaves, two days salary will be deducted. </li>

    <li>One month notice to be served before resignation and on last working day one has to submit all the assets of the company given to you at the time of joining and/r during your tenure with the organization, else salary will not be released. </li>

    <li><b>Confidentiality: The letter contains all details with regard to your salary and incentives and supersedes all earlier communications. Please note that the contents of this letter and the compensation details are confidential. Employee should refrain from discussing or sharing the same with colleagues & Non-Airmed Employees. </b></li>

    <li>Deductions per month include PT, PF / ESI (which is credited into the PF/ESI Account) & Income tax as per statutory requirements. </li>

    <li>Organization reserves right to modify the policies and salary structure, without affecting emoluments adversely. </li>

    <li>TDS deduction will be made as per provision under section 192 of Income tax Act-1961, (If Applicable) </li>

    <li>Call report to be sent on daily basis otherwise it will attract one day leave deduction. </li>
    </ul>


    <b>For Airmed Pathology Pvt. Ltd.</b><br/>

    <p><b>(Authorized Signatory)</b></p>

    <div class="rgtsign_img_divfull">
        <center><b><u>Acknowledgment and Acceptance</u></b></center><br/><br/>   
    </div>

    <p><b>I have read and understood the letter of offer and hereby accept your offer for employment.</b></p>

    <p><b>Date: _________________________________&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Signature:_________________________</b></p>

</html>
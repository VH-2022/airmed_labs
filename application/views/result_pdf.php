<html>
    <head>
        <meta charset="utf-8">
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
        <style>

            body {font-family: 'Roboto', sans-serif;}
            .pdf_container {width: 970px; margin: 0 auto;}
            .main_set_pdng_div {width: 100%; float: left; padding: 0 0;}
            .brdr_full_div { float: left; padding: 10px; width: 100%;}
            .full_div {width: 100%; float: left;}
            .header_full_div {float: left; padding: 0px 10px 5px 10px; width: 92%; height:80px;}
            .set_logo {width: 180px; float: right;}
            .testreport_full {width: 100%; float: left;}
            .tst_rprt {border-bottom: 1px solid #000000; border-top: 1px solid #000000; margin: 0; padding: 5px 0; text-transform: uppercase;width: 100%; float:left;}
            .tst_rprt > img {}
            .tst_rprt h3 {margin: 0; text-align: center;}
            .tbl_full {width: 100%; font-size: 12px;}
            .mdl_tbl_full_div {width: 100%; float: left; min-height: 500px;margin: 0px 5px 0px 5px;}
            .btm_tbl_full_div {width: 100%; float: left;}

            .mdl_tbl_full {width: 100%; font-size: 12px; margin-top: 5px; border-top:1px solid #000;border-bottom:1px solid #000;}
            .mdl_tbl_full1 {width: 100%; font-size: 11px; margin-top: 20px; margin-left: 10px;}
            .btm_tbl_full {width: 100%; font-size: 11px; margin-top: 20px;}
            .mdl_tbl_big_titl {border-bottom: 1px solid #000000; font-size: 12px; font-weight: bold; display: inline-block;}
            .mdl_tbl_tr_brdr {border-bottom: 2px solid #000000; border-top: 2px solid #000000; /*display: table; width: 100%;*/}
            .brdr_btm {border-bottom: 1px solid #000;}
            .end_rprt {text-align: center; float: left; width: 100%;}
            .rslt_p_brdr {border-bottom: 1px solid #000000; float: left; margin: 0; padding-bottom: 5px; text-align: center; width: 100%;}
            .this_p {float: left; margin-top: 5px;}
            .lst_sign_div_main {width: 100%; float: left; margin-top: 22px;}
            .lst_sign_pathologist {float: left;margin-right: 19%;padding-left: 10%;width: 44%;}
            .lst_sign_mdl_sign {width: 29%; float: left;}
            .lst_sign_lst_sign {width: 25%; float: left;}
            .foot_num_div {width: 100%; float: left; padding-bottom: 2px; height: 60px;}
            .foot_num_p {text-align: center; margin-bottom: 5px;}
            .foot_num_p span {background-color: #E30026; border-radius: 25px; padding: 3px 15px; color: #fff;}
            .foot_lab_p {margin: 0; text-align: center; text-transform: uppercase;  border-bottom: 3px dotted #9D0902; padding-bottom: 15px;}
            .foot_lab_p1 {margin: 0; text-transform: uppercase;  border-bottom: 3px dotted #9D0902; padding-bottom: 15px;}
            .lst_ison_ul {display: inline-block; padding: 0; text-align: center; width: 100%; amrgin-top: 5px;}
            .lst_ison_ul li {display: inline-block; margin-right: 15px;}
            .lst_icon_spn_back {background-color: #e30026; border-radius: 50%; float: left; height: 16px; margin-right: 9px; padding: 4px; width: 16px;}
            .lst_icon_spn_back .fa {color: #fff;}
            .lst_airmed_mdl {float: left; margin-bottom: 0; margin-top: 0px; text-align: center; width: 100%;}
            .lst_31_addrs_mdl {float: left; margin: 0; text-align: center; width: 100%;}
            .tbl_btm_mdl_txt {width: 80%; float: left; padding: 0 98px; font-weight: bold;}
            .btm_tbl_full b {float: left; width: 100%;}
            .mdl_tbl_td_title{text-align:center; width:100%; border-top:1px solid #000;}
            .tst_rprt_title{width:40%;float:left;}
            .brcd_div{width:30%; float:left;}
            tr {
                line-height: 0.8;

            }
            .nishit-1:before { content: counter(page); }
            .pagenum:before { content: counter(page); }
        </style>
    </head>
    <body> 

        <?php
        $finalpageArray = array();
        $ts = explode('#', $query[0]['testname']);
        $tid = explode(",", $query[0]['testid']);
        $cnt = 0;
        $pagecount = 0;
        $currentpage = 0;
        $page_break = 0;
        $department_name = "";
        $pageStart = true;
        foreach ($new_data_array as $testidp) {
            $parameter_cnt = 0;
			$testdepart=$testidp["department_fk"];
            if (!empty($testidp[0]["parameter"]) || $testidp['report_type'] == 2) {
                $parameter_val_cnt = 0;
                foreach ($testidp[0]["parameter"] as $parameter) {
                    if (!empty($parameter['user_val']) || !empty($parameter['user_culture_val'])) {
                        $parameter_val_cnt++;
                    }
                }
                if ($parameter_val_cnt != 0) {
                    // if ($parameter_list[$cnt][0]['test_fk'] == $testidp) {
                    ?>
                    <?php
                    if ($cnt != 0 && $testidp["on_new_page"] == 1) {
                        $department_name = "";
                        ?>
                        <div style="width:100%;text-align: center;">- - - - - - End Of Report - - - - - -</div>
                        <?php
                        $pageStart = true;
                        echo "</div>";
                        ?>
                        <?php
                    } $parameter_cnt++;


                    if ($pageStart) {
                        $pagecount++;
                        $finalpageArray[] = array("page" => "chapter" . $pagecount, "sample_type" => array());
                        $currentpage = $pagecount - 1;
                        ?> <div class="chapter<?= $pagecount ?>" > 
                        <?php
                        $pageStart = false;
                    }
					
					if ($department_name != $testidp["department_name"])
                        if ($testidp["department_name"] == "BIOCHEMISTRY")
							if($query[0]['branch_fk'] == "2")
								echo '<div style="position:absolute;padding:7px;left:0;right:0;width:100%;text-align:right;"><img style="width:50px;height:50px;" src="nabl_logo.jpg" /></div>';
					
                    ?>

                        <table style="margin:0 auto" width="98%" class="mdl_tbl_full1" repeat_header="1" class="chapter<?= $pagecount ?> ">
                            <thead>
							<tr style="">
								<td colspan="4" class=""><p class="mdl_tbl_big_titl"><center>
								</center></p></td>
							</tr>
							<tr style="">
								<td colspan="4" class=""><p class="mdl_tbl_big_titl"><center>
								</center></p></td>
							</tr>
                            <tr style="">
                                <td colspan="4" class=""><p class="mdl_tbl_big_titl"><center><?php
									if ($department_name != $testidp["department_name"]) {
                                        //echo 'here';exit;
										//echo '<br /><br />' . ucwords(($testidp["department_name"]));
                                        if(strpos($query[0]['dname'], 'ICON HOSPITAL') > 0)
                                            echo '<br />' .  ucwords(($testidp["department_name"]));
                                        else
                                            echo ucwords(($testidp["department_name"]));
									} 
									$department_name = $testidp["department_name"];
                                ?></center></p></td>
                            </tr>
                            <tr style="">
                                <td colspan="4" class=""><p class="mdl_tbl_big_titl">
								<?php
										if(!empty($testidp[0]["nablprint"])){
											if($testidp[0]["nablprint"]["test_scope"]==1){
												echo "";
											}else if($testidp[0]["nablprint"]["test_scope"]==2){
												echo "<span style='font-weight:normal'><small>*</small></span>";
											}else if($testidp[0]["nablprint"]["test_scope"]==3){
												echo "<span style='font-weight:normal'><small>**</small></span>";
											}
										}
                                        if (!empty($testidp["PRINTING_NAME"])) {
                                            //echo 'here1';exit;
                                            //echo '<br /><br />' . ucwords($testidp["PRINTING_NAME"]);
                                            echo ucwords($testidp["PRINTING_NAME"]);
                                        } else {
                                            echo $testidp["test_name"];
                                        }
                                        ?> </p><?php
                                    if (!empty($testidp["test_method"])) {
                                        echo "<span><small><i>" . $testidp["test_method"] . "</i></small></span>";
                                    }
                                    ?></td>
                            </tr>
                            <?php if ($testidp["report_type"] == 2) { ?>
                                <?php if (empty($parameter['user_culture_val'][0]["result"])) { ?>
                                    <tr class="mdl_tbl_tr_brdr">
                                        <td style="width: 30%;"><b style="border-bottom: 1px solid #000;">ANTIBIOTIC NAME</b></td>
                                        <td style="width: 20%;"><b style="border-bottom: 1px solid #000;">SENSITIVITY</b></td>
                                        <td style="width: 20%;"><b style="border-bottom: 1px solid #000;"></b></td>
                                        <td style="width: 30%;"><b style="border-bottom: 1px solid #000;">ZONE OF INHIBITION</b></td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>

                            </thead>
                            <?php
                            $temp = '1';
                            $cn = 0;
                            $is_group_cnt = 0;
                            $main_cnt = 0;
                            $parameter_cnt1 = 0;
                            foreach ($testidp[0]["parameter"] as $parameter){ $parameter_cnt1++;
                                if ($testidp["report_type"] == 1) {
                                    if ($parameter["is_group"] != '1') {
                                        if (!empty($parameter['parameter_name']) && !empty($parameter['user_val'])) {
                                            if (count($parameter['user_val']) > 0) {
                                                $status = "Normal";
                                                if ($parameter["para_ref_rng"][0]['absurd_low'] > $parameter['user_val'][0]["value"]) {
                                                    $status = "Emergency";
                                                }
                                                if ($parameter["para_ref_rng"][0]['ref_range_low'] > $parameter['user_val'][0]["value"]) {
                                                    $status = $parameter["para_ref_rng"][0]['low_remarks'];
                                                }
                                                if ($parameter["para_ref_rng"][0]['critical_low'] > $parameter['user_val'][0]["value"]) {
                                                    $status = $parameter["para_ref_rng"][0]['critical_low_remarks'];
                                                }
                                                if ($parameter["para_ref_rng"][0]['ref_range_high'] < $parameter['user_val'][0]["value"]) {
                                                    $status = $parameter["para_ref_rng"][0]['high_remarks'];
                                                }
                                                if ($parameter["para_ref_rng"][0]['critical_high'] < $parameter['user_val'][0]["value"]) {
                                                    $status = $parameter["para_ref_rng"][0]['critical_high_remarks'];
                                                }
                                            } else {
                                                $status = "";
                                            }
                                            $is_group_cnt++;
                                            ?>
                                            <?php
                                            if (strlen($parameter_group_set) > 10) {
                                                echo $parameter_group_set;
                                                $parameter_group_set = 0;
                                            }
                                            ?>
                                            <tr style="font-size: 11px;">
                                                <td valign="top" style="width: 30%;font-size: 12px;"><?php
                                                    echo $parameter['parameter_name'];
                                                    if (!empty($parameter['method'])) {
                                                        echo "<br><span><small>Method : <i>" . $parameter['method'] . "</i></small></span>";
                                                    }
                                                    $finalpageArray[$pagecount - 1]["sample_type"][] = $testidp["sampletype"];
                                                    ?></td>
                                                <?php
                                                $res = ''; 
                                                $is_text = 0;
                                                if (isset($parameter["para_ref_rng"][0]['id'])) {
                                                    ?>
                                                    <?php 
													if($testdepart=='5'){
														
													    if (is_numeric($parameter['user_val'][0]["value"]))
                                                        {	$res = number_format((float)$parameter['user_val'][0]["value"], 2, '.', ''); }else{
                                                            $res = $parameter['user_val'][0]["value"];
                                                        }														
                                                    }else{ $res = $parameter['user_val'][0]["value"]; } ?><?php $status; ?>
                                                <?php } else { ?>
                                                    <?php
                                                    if (!empty($parameter["para_ref_status"])) {
                                                        foreach ($parameter["para_ref_status"] as $kky) {
                                                            if ($parameter['user_val'][0]["value"] == $kky["id"]) {
                                                                if ($parameter['user_val'][0]["value"] == $kky["id"]) {
                                                                    $is_text = 1;
                                                                    if (strtoupper($kky["critical_status"]) == "C") {
                                                                        $res = "<span style='font-weight:bold;text-decoration: underline'>" . $kky["parameter_name"] . "</span>";
                                                                    } else {
                                                                        $res = $kky["parameter_name"];
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    } else {
														
														if($testdepart=='5'){
                                                            if (is_numeric($parameter['user_val'][0]["value"])){	
                                                                $res = number_format((float)$parameter['user_val'][0]["value"], 2, '.', ''); 
                                                            }else{
	                                                            $res = $parameter['user_val'][0]["value"];
                                                            } 
                                                        }else{															
                                                            $res = $parameter['user_val'][0]["value"];														
														}
                                                    }
                                                    ?>
                                                <?php } ?>
                                                <td valign="top" <?php
                                                if ($is_text == 1 || strlen($res) > 20) {
                                                    echo 'colspan="4"';
                                                }
                                                ?> style="width: 20%;font-size: 12px;" >
                                                    <?php
                                                    if (!empty(trim($parameter["para_ref_rng"][0]["ref_range"]))) {
                                                        echo $res;
                                                    } else {
                                                        if (( $parameter["para_ref_rng"][0]['ref_range_low'] <= $res ) && ( $res <= $parameter["para_ref_rng"][0]['ref_range_high'] ) || ($parameter["para_ref_rng"][0]['ref_range_low'] == "")) {
                                                            //      if (( 100<=4400 ) && ( 4400<=200 )) {
                                                            echo "" . $res;
                                                        } else {
                                                            echo "<div style='font-weight: bold;'>" . $res . "</div>";
                                                        }
                                                    }
                                                    ?>
                                                </td> 
                                                <?php if ($is_text == 0 && strlen($res) <= 20) { ?>
                                                    <td valign="top" style="width: 20%;font-size: 12px;"><?php echo $parameter['parameter_unit']; ?></td>
                                                    <td style="width: 30%;font-size: 12px;" >  <?php
                                                        if (!empty(trim($parameter["para_ref_rng"][0]["ref_range"]))) {
                                                            echo "<div style=''>" . $parameter["para_ref_rng"][0]["ref_range"] . "</div>";
                                                        } else {
                                                            echo "<div style=''>" . $parameter["para_ref_rng"][0]['ref_range_low'] . " - " . $parameter["para_ref_rng"][0]['ref_range_high'] . "</div>";
                                                        }
                                                        ?></td>
                                                <?php } ?>
                                            </tr>
                                            <?php if (!empty(trim($parameter['description']))) { ?>
                                                <tr  style="font-size: 12px;"><td colspan="4"><?= "<div style='font-weight: ;font-size:11px;'>" . $parameter['description'] . "</div>" ?><br/><br></td></tr>
                                            <?php } ?>

                                            <?php
                                            $cnt++;
                                        }
                                        ?>
                                        <?php
                                    } else {
                                        $showGroup = false;
                                        ;

                                        $temp = $parameter_cnt1;
                                        for (; count($testidp[0]["parameter"]) > $temp; $temp++) {
                                            if ($testidp[0]["parameter"][$temp]['user_val'][0]["value"] != "") {
                                                $showGroup = true;
                                            }
                                        }


                                        if ($showGroup) {
                                            $parameter_group_set = '<tr style="">
                                <td colspan="4" valign="top"><div style="border-bottom: 1px solid black;font-weight: bold;">' . $parameter['parameter_name'] . '</div></td>
                            </tr>';

                                            $is_group_cnt = 0;
                                        }
                                        $cnt++;
                                        //}
                                    }
                                    $main_cnt++;
                                } else if ($testidp["report_type"] == 2) {
                                    /* culture report start */
                                    ?>
                                    <?php if (!empty($parameter['user_val'][0]["value"])) { ?>
                                        <?php
                                        if (strlen($parameter_group_set) > 10) {
                                            echo $parameter_group_set;
                                            $parameter_group_set = 0;
                                        }
                                        ?>
                                        <tr>
                                            <td valign="top" style="width: 30%;font-size: 12px;"><?php
                                                echo $parameter['parameter_name'];
                                                if (!empty($parameter['method'])) {
                                                    echo "<br><span><small>Method : <i>" . $parameter['method'] . "</i></small></span>";
                                                }
                                                $finalpageArray[$pagecount - 1]["sample_type"][] = $testidp["sampletype"];
                                                ?></td>
                                            <?php
                                            $res = '';
                                            $is_text = 0;
                                            if (isset($parameter["para_ref_rng"][0]['id'])) {
                                                ?>
                                                <?php if($testdepart=='5'){ 
                                                    if (is_numeric($parameter['user_val'][0]["value"])){	
                                                        $res = number_format((float)$parameter['user_val'][0]["value"], 2, '.', ''); 
                                                    }else{
	                                                    $res = $parameter['user_val'][0]["value"];
                                                    } }else{ $res = $parameter['user_val'][0]["value"]; } ?><?php $status; ?>
                                            <?php } else { ?>
                                                <?php
                                                if (!empty($parameter["para_ref_status"])) {
                                                    foreach ($parameter["para_ref_status"] as $kky) {
                                                        if ($parameter['user_val'][0]["value"] == $kky["id"]) {
                                                            $is_text = 1;
                                                            if (strtoupper($kky["critical_status"]) == "C") {
                                                                $res = "<span style='font-weight:bold;text-decoration: underline'>" . $kky["parameter_name"] . "</span>";
                                                            } else {
                                                                $res = $kky["parameter_name"];
                                                            }
                                                        }
                                                    }
                                                } else {
													if($testdepart=='5'){ 
                                                        if (is_numeric($parameter['user_val'][0]["value"])){
                                                            $res = number_format((float)$parameter['user_val'][0]["value"], 2, '.', ''); 
                                                        }else{
	                                                        $res = $parameter['user_val'][0]["value"];
                                                        } }else{
                                                    $res = $parameter['user_val'][0]["value"]; }
                                                }
                                                ?>
                                            <?php } ?>
                                            <td valign="top" style="width: 20%;font-size: 12px;">
                                                <?php
                                                echo $res;
                                                ?>
                                            </td> 
                                            <td style="width: 20%;"></td>
                                            <td style="width: 30%;font-size: 12px;"><?php echo $parameter['user_val'][0]["value2"]; ?></td>
                                        </tr>
                                        <?php if (!empty(trim($parameter['description'])) && !empty($res)) { ?>
                                            <tr  style="font-size: 11px;"><td colspan="4"><?= "<div style='font-weight: ;font-size:11px;'>" . $parameter['description'] . "</div>" ?><br/><br></td></tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                    <?php
                                    /* END */
                                    $cnt++;
                                }
                            }
                            ?>
                            <?php if (!empty($parameter['user_culture_val'][0]["result"])) { ?>
                                <tr style="font-size: 11px;">
                                    <td colspan="4"><?php echo $parameter['user_culture_val'][0]["result"]; ?></td>
                                </tr>
                            <?php } ?>
                            <?php if (empty($testidp["graph"])) { ?>
                                <tr style="text-align: center; width: 100%;">
                                    <td colspan="4"></td>
                                </tr>
                            <?php } ?>
                        </table>
                        <?php
                    }
                }
                
                ?>
                <?php
                if ($pageStart) {
                    $pagecount++;
                    $finalpageArray[] = array("page" => "chapter" . $pagecount, "sample_type" => array());
                    $currentpage = $pagecount - 1;
                    ?> <div class="chapter<?= $pagecount ?>" > 
                    <?php
                    $pageStart = false;
                }
                if (!empty($testidp["graph"])) {
                    echo '<table class="mdl_tbl_full1">';
                    if ($parameter_cnt == 0) {
                        ?><thead>
                                <tr style="text-align: center; width: 100%;">
                                    <td colspan="4" class=""><p class="mdl_tbl_big_titl"><center><?php
            if (!empty($testidp["PRINTING_NAME"])) {
                //echo 'here2';exit;
                echo '<br />' . ucwords($testidp["PRINTING_NAME"]);
            } else {
                echo $testidp["test_name"];
            }
                        ?></center></p></td>
                            </tr>
                            </thead><?php
                }
                $itest = 0;
                foreach ($testidp['graph'] as $g_key) {
                    if (!empty($g_key["pic"])) {
                        $itest++;
                            ?>
                                <tr style="text-align: center; width: 100%;">
                                    <td>
                                <center>
                                    <img src="<?= FCPATH; ?>upload/report/graph/<?= $g_key["pic"] ?>" height="auto" width="100%"/> 
                                    <?php if (count($testidp['graph']) == $itest) { ?>
                                        <br>
                                    <?php } ?>
                                </center>
                                </td>
                                </tr>
                                <?php
                                $cnt++;
                            }
                        }
                        ?>
                        </table>
                        <?php
                    }
                }
                ?>
                <div style="width:100%;text-align: center;">- - - - - - End Of Report - - - - - -</div>
            </div>
            <?php
            $pageStart = true;
            echo "</div>";
            foreach ($finalpageArray as $page) {
                ?>
                <htmlpageheader name="p<?php echo $page['page'] ?>"><?php
            $hcnt = strlen(implode(',', array_unique($page['sample_type'])));
            if ($hcnt > 2) {
                $new_header = str_replace("FINAL", implode(',', array_unique($page['sample_type'])), $header);
                $new_header = str_replace("Report Status", "Sample Type", $new_header);
                echo $new_header;
            } else {
                echo $header;
            }
//echo json_encode($page); echo implode(',', $page['sample_type']); 
                ?>
                </htmlpageheader>
            <?php }
            ?>
            <htmlpagefooter name="footer"><?php echo $footer; ?></htmlpagefooter>

    </body>
    <style>
<?php
$pdfsize = explode(",", $pdfsize);

foreach ($finalpageArray as $page) {
    ?>
            @page <?php echo $page['page'] ?> {
                odd-header-name: p<?php echo $page['page'] ?>;
                margin-left: 7%;
                margin-right: 2%;
                margin-header: <?= $pdfsize[0] ?>mm;
                margin-footer: <?= $pdfsize[1] ?>mm;
                margin-top: <?= $pdfsize[2] ?>%;
                odd-footer-name:footer;,
                                        		
            }

            .<?php echo $page['page'] ?> {
                page: <?php echo $page['page'] ?>;
                page-break-before: always;
            }
<?php }
?>
    
         
    </style>
</html>
<?php
//die();
?>
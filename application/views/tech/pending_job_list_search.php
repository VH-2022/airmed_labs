<thead>
    <tr>
        <th>No</th>
        <th>Reg. No</th>
        <th>Order Id</th>
        <th>Customer Name</th>
        <th>Doctor</th>
        <th>Test/Package Name</th>
        <th>Date</th>
        <!--<th>Payment Type</th>-->
        <!--<th>Total Amount(Rs.)</th>-->
        <th>Payable Amount / Price</th>
        <!--<th>Blood Sample Collation Status</th>-->
        <th>Job Status</th>
        <th width="112px;">Action</th>
    </tr>
</thead>
<tbody>
    <?php
    $cnt = 1;
    $test_ids = array();
    foreach ($query as $row) {
        if ($row["is_show"] == 1) {
            $test_ids[] = $row['id'];
            ?>
            <?php
            $style = '';
			$trstyle="";
            if ($row['emergency'] == '1') {
                /* $style = "background-color:red;color:white;"; */
				$trstyle='style="background-color: #FFBDBC;"';
            }
            if ($row['status'] == 2) {
                $style = "background-color:#008D4C;color:white;";
            }
            ?>
            <tr <?= $trstyle; ?>>
                <td style="<?= $style; ?>"><?php
                    echo $cnt + $pages . " ";
                    if ($row['views'] == '0') {
                        echo '<span class="round round-sm blue"> </span>';
                    }
                    ?> 
                </td>
                <td><?= $row["id"]; ?><br>Barcode - <?=$row["barcode"]?></td>
                <td style="color:#d73925;">
                    <?php
                    echo $row["order_id"];
                    ?>

                </td>
                <?php
                if ($row['relation'] == '') {
                    $row['relation'] = 'Self';
                }
                ?>
                <td>
                    <?php if ($row['relation'] == 'Self') { ?>
                        <span style="color:#d73925;"><?php echo ucwords($row['full_name']); ?></span> <?php echo "(" . $row['age'] . " " . $row['age_type'] . "/" . $row['gender'] . ")"; ?>
                        &nbsp;<?= $row['mobile'] ?>
                    <?php } else { ?>
                        <span style="color:#d73925;"><?= ucfirst($row['relation']); ?></span> <?php echo "(" . $row['age'] . " " . $row['age_type'] . "/" . $row['gender'] . ")"; ?>
                        &nbsp;<?= $row['rphone'] ?>
                        <br>
                        <span title="Account holder">AC-</span><a style="margin-left:0;" href="<?php echo base_url(); ?>customer-master/customer-all-details/<?php echo $row['cid']; ?>"><?php echo ucwords($row['full_name']); ?></a>&nbsp;<?= $row['mobile'] ?>
                    <?php } ?>

                    <br>
                    <?php
                    if ($row["test_city"] == 1) {
                        echo "Ahmedabad";
                    } else if ($row["test_city"] == 2) {
                        echo "Surat";
                    } else if ($row["test_city"] == 3) {
                        echo "Vadodara";
                    } else if ($row["test_city"] == 4) {
                        echo "Gurgaon";
                    } else if ($row["test_city"] == 5) {
                        echo "Delhi";
                    } else if ($row["test_city"] == 6) {
                        echo "Gandhinagar";
                    }
                    /* if($branch['portal'] == 'web'){ */
					$smsalert=0;
					$emailalert=0;
                    $allow_whatsapp = 0;
                    foreach ($branchlist as $branch) {
                        if ($row['branch_fk'] == $branch['id']) {
                            echo '[' . $branch['branch_name'] . ']';
							
							$smsalert=$branch['smsalert'];
					        $emailalert=$branch['emailalert'];

                            if($branch["allow_whatsapp"] == "1")
                                $allow_whatsapp = 1;
                        }
                    }
                    /* }else{
                      foreach ($branchlist as $branch) {
                      if ($row['branch_fk'] == $branch['branch_code']) {
                      echo '[' . $branch['branch_name'] . ']';
                      }
                      }
                      } */
                    ?>
                    <br><small><b>Added By- </b>
                        <?php
                        if (!empty($row["phlebo_added_by"])) {
                            echo ucfirst($row["phlebo_added_by"]) . " (Phlebo)";
                        } else if (!empty($row["added_by"])) {
                            echo ucfirst($row["added_by"]);
                        } else {
                            echo "Online Booking";
                        }
                        ?>
                    </small>
                </td>
                <td><?php if (!empty($row["doctor"])) { ?><?= ucfirst($row["doctor_name"]) . " - " . $row["doctor_mobile"]; ?> <?php
                    } else {
                        echo "-";
                    }
                    ?></td>
                <td><?php
                    //$testname = explode(",", $row['testname']);
                    //print_R($row["job_test_list"]); die();
                    foreach ($row["job_test_list"] as $test) {
                        $is_panel = '';
                        if ($test['is_panel'] == 1) {
                            $is_panel = '(Panel)';
                        }
                        echo "<span id='test_" . $row["id"] . "_" . $test['test_fk'] . "' class='test_".$row["id"]."'>" . ucwords($test['test_name']) . " <b>" . $is_panel . "</b></span>" . "<br>";
                        foreach ($test["sub_test"] as $kt_key) {
                            $kt_key["test_name"];
                            if (!in_array($kt_key["sub_test"], $test_list)) {
                                ?>
                                <i style="margin-left:20px">-</i><span id='test_<?= $row['id']; ?>_<?= $kt_key["sub_test"] ?>' class="test_<?= $row['id']; ?>"><?= $kt_key["test_name"] ?></span><br>
                                <?php
                                $test_list[] = $kt_key["test_fk"];
                            }
                        }
                    }
                    $test_list = array();
                    foreach ($row["package"] as $key3) {
                        ?>
                        <?php echo ucfirst($key3["name"]); ?><br><?php
                        foreach ($key3["test"] as $kt_key) {
                            $kt_key["test_name"];
                            if (!in_array($kt_key["test_fk"], $test_list)) {
                                ?>
                                <i style="margin-left:20px">-</i><span id='test_<?= $row['id']; ?>_<?= $kt_key["test_fk"] ?>' class="test_<?= $row['id']; ?>"><?= $kt_key["test_name"] ?></span><br>
                                <?php
                                $test_list[] = $kt_key["test_fk"];
                            }
                        }
                    }
                    ?>
                </td>
                <td><?php echo ucwords($row['date']); ?></td>

                <td><?php
                    $payable_amount = 0;
                    /* Nishit code start */
                    $color_code = '#00A65A';
                    if ($row['payable_amount'] > 0) {
                        $color_code = '#D33724';
                    }
                    /* END */
                    if ($row['payable_amount'] == "") {
                        echo "<spam style='color:white;background:" . $color_code . ";padding:2px'>Rs. 0";
                    } else {
                        $payable_amount = $row['payable_amount'];
                        echo "<spam style='color:white;background:" . $color_code . ";padding:2px'>Rs. " . number_format((float) $row['payable_amount'], 2, '.', '');
                    }
                    ?> /<?= " Rs." . number_format((float) $row["price"], 2, '.', '') . "</spam>"; ?>
                    <?php
                    if ($row["cut_from_wallet"] != 0) {
                        echo "<br><small>(Rs." . number_format((float) $row["cut_from_wallet"], 2, '.', '') . " Debited from wallet)</small>";
                    }
                    if ($row["discount"] != '' && $row["discount"] != '0') {
                        $dprice1 = $row["discount"] * $row["price"] / 100;
                        $discount_amount = $discount_amount + $dprice1;
                        echo "<br><small>( Rs." . number_format((float) $dprice1, 2, '.', '') . " Discount)</small>";
                    }
                    if ($row["collection_charge"] == 1) {
                        echo "<br><small>( Rs." . number_format((float) 100, 2, '.', '') . " Collection charge)</small>";
                    }
                    ?>
                </td>

                <td>

                    <?php
                    if ($row['status'] == 1) {
                        echo "<span class='label label-danger '>Waiting For Approval</span>";
                    }
                    ?>
                    <?php
                    if ($row['status'] == 6) {
                        echo "<span class='label label-warning '>Approved</span>";
                    }
                    ?>
                    <?php
                    if ($row['status'] == 7) {
                        echo "<span class='label label-warning '>Sample Collected</span>";
                    }
                    ?>
                    <?php
                    if ($row['status'] == 8) {
                        echo "<span class='label label-warning '>Processing</span>";
                    }
                    ?>
                    <?php
                    if ($row['status'] == 2) {
                        echo "<span class='label label-success '>Completed</span>";
                    }
                    ?>
                    <?php
                    if ($row['status'] == 0) {
                        echo "<span class='label label-danger '>User Deleted</span>";
                    }
                    ?>
                    <br><?php
                    if ($row['emergency'] == '1') {
                        echo "<span class='label label-danger '>Emergency</span><br>";
                    }
                    if ($row["dispatch"] == 1) {
                        echo "<span class='label label-success '>Dispatched</span>";
                    }
                    ?>
                    <?php /* if($row['status']=="1" && $row['sample_collection']=="0" ){ echo "<span class='label label-danger'>Pending</span>"; }else if($row['status']=="2"){ echo "<span class='label label-success'>Completed</span>"; }else if($row['status']=="3"){ echo "<span class='label label-danger'>Spam</span>"; }else if($row['sample_collection']==1){ echo "<span class='label label-warning'>Processing</span>";} 
                     */
                    ?> 

                </td>
                <td>
                    <!--<a  href='<?php echo base_url(); ?>job_master/job_mark_spam/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Mark as Spam" ><span class="label label-danger">Mark as Spam</span></a>                                                                                                                                                                                                                                                                                                                                                                      <a  href='<?php echo base_url(); ?>job_master/job_mark_completed/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Mark as completed" ><span class="label label-success">Mark as completed</span> </a>  --> 
                    <a  href='<?php echo base_url(); ?>tech/job_master/job_details/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="View Job Details" target="_blank"> <span class=""><i class="fa fa-eye"> </i></span> </a>

                    
                    <a id="dispatch_1_<?php echo $row['id']; ?>" style="display:<?php
                    if ($row["dispatch"] == 0) {
                        echo "none";
                    }
                    ?>" href='javascript:void(0)' data-toggle="tooltip" data-original-title="Dispatched" onclick="dispatched_job_test('<?php echo $row['id']; ?>', '0');"> <span class="success"><i class="fa fa-truck"> </i></span> </a>
                    <a id="dispatch_0_<?php echo $row['id']; ?>" style="display:<?php
                    if ($row["dispatch"] == 1) {
                        echo "none";
                    }
                    ?>" href='javascript:void(0)' data-toggle="tooltip" data-original-title="Mark As Dispatched" onclick="dispatched_job_test('<?php echo $row['id']; ?>', '1');"> <span class="warning"><i class="fa fa-truck"> </i></span> </a>
                       <?php if (!empty($row["report"])) { ?>
                           <?php
                           if ($row["is_print"] == 0 && $row["panel_test_count"] == 0) {
                               if ($payable_amount <= 0) {
                                   ?>
                                <a href='javascript:void(0);' data-toggle="tooltip" data-original-title="Print Report" onclick="printreport('<?= $row["id"] ?>')"><span class=""><i id="printicon" <?php
                                        if ($row['prientreport'] == '0') {
                                            echo "style='color:green'";
                                        }
                                        ?> class="fa fa-print"></i></span></a>    
<?php if($smsalert==1 || in_array($login_data["type"], array(1,2))){ ?>								
                                <a href='javascript:void(0);' onclick="<?php if ($payable_amount <= 0 || $login_data['type'] == 5) { ?>sms_popup('<?= $row["id"] ?>');<?php } else { ?>alert('Please collect due amount.');<?php } ?>" data-toggle="tooltip" data-original-title="Send Report Via SMS" <?php if (!empty($row["send_repor_sms"])) { ?> style="background:green;color:white;width:auto;padding:0px 5px 3px 5px;border-radius:3px;" <?php } ?>><span class=""><i class="<?php
                                        if ($row['send_sms'] == 1) {
                                            echo "fa fa-comment";
                                        } else {
                                            echo "fa fa-comment-o";
                                        }
                                        ?>"  style="<?php
                                                                                                                                                                                                                                                                                                                                                                                                                                                                              if ($row['send_sms'] == 1) {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  echo "color:green";
                                                                                                                                                                                                                                                                                                                                                                                                                                                                              }
                                                                                                                                                                                                                                                                                                                                                                                                                                                                              ?>"></i></span></a>
<?php } if($emailalert==1 || in_array($login_data["type"], array(1,2))){ ?>
                                <a href='javascript:void(0);' onclick="<?php if ($payable_amount <= 0 || $login_data['type'] == 5) { ?>mail_popup('<?= $row["id"] ?>', '<?= $row["cid"] ?>');<?php } else { ?>alert('Please collect due amount.');<?php } ?>" data-toggle="tooltip" data-original-title="Send Report Via Mail" <?php if (!empty($row["send_report_mail"])) { ?> style="background:green;color:white;width:auto;padding:0px 5px 3px 5px;border-radius:3px;" <?php } ?>><span class=""><i class="<?php
                                        if ($row['send_email'] == 1) {
                                            echo "fa fa-envelope";
                                        } else {
                                            echo "fa fa-envelope-o";
                                        }
                                        ?>" style="<?php
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        if ($row['send_email'] == 1) {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            echo "color:green";
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        }
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ?>"></i></span></a>
<?php } } ?>
                            <?php } else { ?>
                            <a href='javascript:void(0);' data-toggle="tooltip" data-original-title="Print Report" onclick="printreport('<?= $row["id"] ?>')"><span class=""><i id="printicon" <?php
                                        if ($row['prientreport'] == '0') {
                                            echo "style='color:green'";
                                        }
                                        ?> class="fa fa-print"></i></span></a>
							<?php if($smsalert==1 || in_array($login_data["type"], array(1,2))){ ?>
                            <a href='javascript:void(0);' onclick="<?php if ($payable_amount <= 0 || $login_data['type'] == 5) { ?>sms_popup('<?= $row["id"] ?>');<?php } else { ?>alert('Please collect due amount.');<?php } ?>" data-toggle="tooltip" data-original-title="Send Report Via SMS" <?php if (!empty($row["send_repor_sms"])) { ?> style="background:green;color:white;width:auto;padding:0px 5px 3px 5px;border-radius:3px;" <?php } ?>><span class=""><i class="<?php
                                    if ($row['send_sms'] == 1) {
                                        echo "fa fa-comment";
                                    } else {
                                        echo "fa fa-comment-o";
                                    }
                                    ?>"  style="<?php
                                                                                                                                                                                                                                                                                                                                                                                                                                                                          if ($row['send_sms'] == 1) {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                              echo "color:green";
                                                                                                                                                                                                                                                                                                                                                                                                                                                                          }
							?>"></i></span></a><?php } if($emailalert==1 || in_array($login_data["type"], array(1,2))){ ?>
                            <a href='javascript:void(0);' onclick="<?php if ($payable_amount <= 0) { ?>mail_popup('<?= $row["id"] ?>', '<?= $row["cid"] ?>');<?php } else { ?>alert('Please collect due amount.');<?php } ?>" data-toggle="tooltip" data-original-title="Send Report Via Mail" <?php if (!empty($row["send_report_mail"])) { ?> style="background:green;color:white;width:auto;padding:0px 5px 3px 5px;border-radius:3px;" <?php } ?>><span class=""><i class="<?php
                                    if ($row['send_email'] == 1) {
                                        echo "fa fa-envelope";
                                    } else {
                                        echo "fa fa-envelope-o";
                                    }
                                    ?>" style="<?php
                                                                                                                                                                                                                                                                                                                                                                                                                                                                        if ($row['send_email'] == 1) {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                            echo "color:green";
                                                                                                                                                                                                                                                                                                                                                                                                                                                                        }
                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ?>"></i></span></a>
                            <?php } } ?>
                        <?php } ?>

                    <?php /* if ($payable_amount > 0) { ?>
                      <a onclick="return confirm('Are you sure?');" href='<?= base_url(); ?>/job_master/send_sms_due_payment/<?= $row["id"] ?>' target="_blank" data-toggle="tooltip" data-original-title="Send SMS" ><span class=""><i class="fa fa-inr fa-6"></i></span></a>
                      <?php } */ ?>
                    <?php 
			if($smsalert==1 || in_array($login_data["type"], array(1,2))){		
					if ($payable_amount > 0) { ?>
                        <a href='javascript:void(0);' onclick="sms_popup1('<?= $row["id"] ?>');"  data-toggle="tooltip" data-original-title="Send SMS" ><span class=""><i class="fa fa-inr fa-6"></i></span></a>
			<?php } } ?>

                    <a href='<?php if (!empty($row['invoice'])) { ?><?= base_url(); ?>upload/result/<?php echo $query[0]['invoice']; ?><?php } else { ?><?= base_url(); ?>tech/job_master/pdf_invoice/<?= $row["id"] ?><?php } ?>' target="_blank" data-toggle="tooltip" data-original-title="Download Invoice"><span class=""><i class="fa fa-download fa-6"></i></span></a>
                    <a href='<?= base_url(); ?>tech/job_master/ack/<?= $row["id"] ?>' data-toggle="tooltip" data-original-title="Download ACK" target="_blank"><span class=""><i class="fa fa-download fa-6"></i></span></a>
                    <a href='javascript:voic();' onclick="open_barcode_popup('<?= $row["id"] ?>');" data-toggle="tooltip" data-original-title="Print Barcode"><span class=""><i class="fa fa-barcode"></i></span></a>
                    <?php 
                    if($allow_whatsapp == 1)
                        if ($row['status'] == 8 || $row['status'] == 2) 
                            if ($login_data['type'] != 7) 
                                if ($payable_amount <= 0)
                                { 
                                    if ($row['whatsappsent'] == 1) 
                                        echo "<img src='" . base_url() . "double tick4.png' style='width:16px;height:16px;margin-left:10px;' tooltip='Whatsapp Already Sent' title='Whatsapp Already Sent'  data-toggle='tooltip' data-original-title='Whatsapp Already Sent'>";                                    
                                    else
                                        echo "<a href='javascript:void(0);' onclick=\"approved('" . $row["id"] . "');\" data-toggle='tooltip' data-original-title='Send Whatsapp Report' ><img src='" . base_url() . "whatsapp_icon.png' style='width:15px;height:15px;'></a>";
                                }                       
                                else if($login_data['id'] == 50) 
                                {
                                    if ($row['whatsappsent'] == 1) 
                                        echo "<img src='" . base_url() . "double tick4.png' style='width:16px;height:16px;margin-left:10px;' tooltip='Whatsapp Already Sent' title='Whatsapp Already Sent'  data-toggle='tooltip' data-original-title='Whatsapp Already Sent'>";                                    
                                    else
                                        echo "<a href='javascript:void(0);' onclick=\"approved('" . $row["id"] . "');\" data-toggle='tooltip' data-original-title='Send Whatsapp Report' ><img src='" . base_url() . "whatsapp_icon.png' style='width:15px;height:15px;'></a>";
                                }
                    ?>
                </td>
            </tr>
            <?php
            $cnt++;
        }
    }if (empty($query)) {
        ?>
        <tr>
            <td colspan="10">No records found</td>
        </tr>
    <?php } ?>
    <tr style="display:none;">
        <td colspan="10" id="search_test_ids"><?php echo implode(",", $test_ids); ?></td>
    </tr>
</tbody>
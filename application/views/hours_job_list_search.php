<thead>
    <tr>
        <th>No</th>
        <th>Reg. No</th>
        <th>Order Id</th>
        <th>Branch Name</th>
        <th>Customer Name</th>
        <th>Test/Package Name</th>
        <th>Date</th>
        <th>Job Status</th>
        <th width="112px;">Action</th>
    </tr>
</thead>
<tbody>
    <?php
    $cnt = 1;
    $test_ids = array();
    foreach ($query as $row) {


        if ($sample_not_processed == "1" && $row['late_sample_collection'] == '0') {
            continue;
        }
        if ($sample_not_processed == "2" && $row['late_processing'] == '0') {
            continue;
        }


        if ($row["is_show"] == 1) {
            $test_ids[] = $row['id'];
            ?>
            <?php
            $style = '';
            $trstyle = "";
            if ($row['emergency'] == '1') {
                /* $style = "background-color:red;color:white;"; */
                $trstyle = 'style="background-color: #FFBDBC;"';
            } else {
                if ($row['late_sample_collection'] == '1') {
                    $trstyle = 'style="background-color: #BCBCDD;"';
                } else if ($row['late_processing'] == '1') {
                    $trstyle = 'style="background-color: #B8D5D5;color:black"';
                }
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
                <td><?= $row["id"]; ?><br>Barcode - <?= $row["barcode"] ?></td>
                <td style="color:#d73925;">
                    <?php
                    echo $row["order_id"];
                    ?>

                </td>
                <td>
                    <?php
                          foreach ($branchlist as $branch) {
                            if ($row['branch_fk'] == $branch['id']) {
                                echo  $branch['branch_name'];
    
                                $smsalert = $branch['smsalert'];
                                $emailalert = $branch['emailalert'];
    
                                if($branch["allow_whatsapp"] == "1")
                                    $allow_whatsapp = 1;
                                    
                                if($branch["whatsapp_report_send"] == "1")
                                    $allowwhatsappwithoutpayment = true;
                            }
                        }
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
                    $branch_id = $row['branch_fk'];
                    $smsalert = 0;
                    $emailalert = 0;
                    $allow_whatsapp = 0;
                    $allowwhatsappwithoutpayment = false;
                    foreach ($branchlist as $branch) {
                        if ($row['branch_fk'] == $branch['id']) {
                            

                            $smsalert = $branch['smsalert'];
                            $emailalert = $branch['emailalert'];

                            if($branch["allow_whatsapp"] == "1")
                                $allow_whatsapp = 1;
                                
                            if($branch["whatsapp_report_send"] == "1")
                                $allowwhatsappwithoutpayment = true;
                        }
                    }
                   
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
            
                <td><?php
                 
                    $test_id_1 = "";
                   
                    foreach ($row["job_test_list"] as $test) {
                        $test_id_1 .= $test['test_fk'] . ',';
                        $is_panel = '';
                        if ($test['is_panel'] == 1) {
                            $is_panel = '(Panel)';
                        }
                        echo "<span id='test_" . $row["id"] . "_" . $test['test_fk'] . "' class='test_" . $row["id"] . "'>" . ucwords($test['test_name']) . " <b>" . $is_panel . "</b></span>" . "<br>";
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
                    $package_id_1 = "";
                    foreach ($row["package"] as $key3) {
                        
                        ?>
                        <?php echo ucfirst($key3["name"]); ?><br><?php
                        foreach ($key3["test"] as $kt_key) {
                            $package_id_1 .= $kt_key['test_fk'] . ',';
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

                <td>

                    <?php
                    $status = $row['status'];

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
                        echo "<span class='label label-warning '>Sample received & processing</span>";
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
                  

                </td>
                <td>
                  
                    <a  href='<?php echo base_url(); ?>job-master/job-details/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="View Job Details" target="_blank"> <span class=""><i class="fa fa-eye"> </i></span> </a>

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

    



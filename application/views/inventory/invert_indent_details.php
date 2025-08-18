<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<style>
    /* pending_job_detail responsive table */
    .pending_job_list_tbl {width: 100%; float: left;}
    .pending_job_list_tbl table {width: 100%; border-collapse: collapse; float: left;}
    .pending_job_list_tbl th {background-color: #e5e5e5; color: #3e3e3e; font-size: 16px; font-weight: 600; text-align: center; vertical-align: middle; border: 1px solid #b1b1b1;}
    .pending_job_list_tbl td, th {padding:2px 6px; border: 1px solid #ccc; text-align: left;}
    .pending_job_list_tbl td {padding: 4px 4px; font-size: 13px; color: #505050;} 
    /* End pending_job_detail responsive table */

    .full_bg{background: rgba(0,0,0,0.3); width:100%; height:100%; float:left; padding:250px; position:fixed; z-index:9; top:0; bottom:0;}
    .full_bg .loader img{width:70px; height:70px;}
    .text_highlight:hover{
        text-decoration: underline;
        /*font-weight: bold;
        font-size: 12px;*/
    }	
</style>
<div class="full_bg" style="display:none;" id="loader_div">
    <div class="loader">
        <center><img src="http://static.heart.org/riskcalc/app/assets/img/loading.gif"></center>
    </div>
</div>
<section class="content-header">
    <h1>
        Po Generate
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>inventory/Intent_Request/sub_index"><i class="fa fa-users"></i>Indent Request </a></li>
        <li class="active">Po Generate</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="box-header">
                        <h3 class="box-title">Indent Details</h3>
                    </div>
                    <hr>
                    <div class="col-md-12">

                        <div class="form-group">
                            <label for="message-text" class="form-control-label">Indent Id:</label>
                            <?= $indedrequiest[0]["id"] ?>
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="form-control-label">Branch:</label>
                            <?= $indedrequiest[0]["branch_name"] ?>
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="form-control-label">Remark:</label>
                            <?= $indedrequiest[0]["remark"] ?>
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="form-control-label">Added By:</label>
                            <?= $indedrequiest[0]["added_by"] ?>
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="form-control-label">Item:</label>
                            <?php foreach ($poitenm as $pkey): ?>
                                <br><b><?= $pkey["reagent_name"] ?></b><?= ($pkey["reagent_name"]) ? "(" . $pkey["unit"] . ")" : ""; ?><?= "<small>" . $pkey["category"] . "</small>" ?>
                            <?php endforeach; ?>
                        </div>



                        <div class="table-responsive pending_job_list_tbl">
                            <table class="table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
\                                        <th>Item</th> 
                                        <th>Category</th>
                                        <th>LOT Number</th>
                                        <th>NOS</th>
                                        <th>Qty.</th>
                                    </tr>
                                </thead>
                                <tbody id="selected_items">
                                    <?php
                                    $cnt = 0;
                                    $totalprice = 0;
                                    foreach ($poitenm as $row) {
                                        $cnt++;
                                        $itetype = $row["cattype"];
                                        $whotype = "";
                                        if ($itetype == '1') {
                                            $whotype = "Stationary";
                                            $cat = "Stationary";
                                        }
                                        if ($itetype == '2') {
                                            $whotype = "Consumables";
                                            $cat = "Lab Consumables";
                                        }
                                        if ($itetype == '3') {
                                            $whotype = "Reagent";
                                            $cat = "Reagent";
                                        }
                                        if ($row["box_price"] != "") {
                                            $itemprice = $row["box_price"];
                                        } else {
                                            $itemprice = 0;
                                        }
                                        $itrmtotal = round($itemprice * $row["qty"] * $row["quantity"]);
                                        $totalprice += $itrmtotal;
                                        ?>
                                        <tr id="tr_<?= $cnt; ?>">
                                            <td><?= $cnt; ?></td>
                                            <td><?= $row["reagent_name"]; ?></td>
                                            <td><?= $row["category"]; ?></td>
                                            <td><?= $row["lot_number"]; ?></td>
                                            <td><input type="text" name="nos[]" id="nos_<?= $cnt; ?>" value="<?= $row["quantity"]; ?>" class="form-control calution"/></td>
                                            <td><p id="totalqty_<?= $cnt; ?>"><?= $row["qty"]; ?></p></td>
                                            <td><?= ($row["available_stock"] > 0) ? $row["available_stock"] : 0; ?></td>
                                            <td><input type="text" id="rateqty_<?= $cnt; ?>" value="<?= $itemprice; ?>"  name="rateqty[]" class="form-control calution" /></td>
                                            <td><p id="testamount_<?= $cnt; ?>"><?= $itrmtotal; ?></p></td>
                                            <td><input type="text" id="itemdis_<?= $cnt; ?>" value="0" name="itemdis[]" class="form-control calution"/><span id="errorperdis_<?= $cnt; ?>" style="color: red;"></span></td>
                                            <td><p id="txtid_<?= $cnt; ?>"><select class="form-control calution1" name="itemtext[]" >
                                                        <option value="0">--Select Tax--</option>
                                                        <?php
                                                        foreach ($itemtext as $txt) {
                                                            ?>
                                                            <option  rel="<?php echo $txt["tax"]; ?>" value="<?= $txt["id"] ?>" <?php
                                                            if ($row["taxid"] == $txt["id"]) {
                                                                echo "selected";
                                                            }
                                                            ?>><?= $txt["title"] . "(" . $txt["tax"] . "%)"; ?></option>
                                                                 <?php } ?>
                                                    </select></p></td>
                                            <td>
                                                <input type="text" id="totalamount_<?= $cnt; ?>" disabled name="totalamount[]" value="<?= $itrmtotal ?>" class="form-control"/>
                                                <span id="errortamount_<?= $cnt; ?>" class="" style="color:red;"></span>
                                            </td>
                                            <td><a href="javascript:void(0);" onclick="delete_city_price('<?= $cnt; ?>', '<?= $row["reagent_name"]; ?>', '<?= $row["itemid"]; ?>', '<?= $whotype; ?>')"><i class="fa fa-trash"></i></a></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>

                                <tr>
                                    <td colspan='9'></td>
                                    <td>Total Amount Rs.</td>
                                    <td colspan='2'><input id="maintotalprice" type="text" readonly name="maintotal" value="<?= $totalprice; ?>" /></td>
                                </tr>
                            </table>


                        </div> 


                    </div>
                </div>	
            </div>

        </div>
    </div>
</section>
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
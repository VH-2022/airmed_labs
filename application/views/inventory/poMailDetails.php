<?php
if ($query[0]["status"] == 3) {
    ?>
    <a  onclick=" return confirm('Are you sure ?');" href="<?= base_url() . "inventory/intent_genrate/mail_poconfirm?id=" . $query[0]["id"]; ?>" type="submit" class="btn btn-primary">Click here to approve PO</a> <br><em>This action will email PO to vendor.</em>
    <?php
}else{
    echo "Already approved.";
}
?>
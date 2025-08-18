<div id="printBarcodePage" style="margin-left:30px;font-size:12px;font-family: Arial;">
    <style>
        div.b128{
            border-left: 1px black solid; 
            height:20px;
        }

    </style>
    <span style="font-size:10px"><b>  <?= $patient_name; ?></b></span></br>
    <span style="font-size:9px"><b>  <?= $patient_gender; ?>/<?= $patient_age; ?>&nbsp;<?= $age_type ?> </b>&nbsp;(<?= date("d-M-Y", strtotime($date)); ?>)</span>
    </br> 
 <img style="width:60%" src='https://barcode.tec-it.com/barcode.ashx?data=ABC-1234&code=Code39&multiplebarcodes=false&translate-esc=false&unit=Fit&dpi=96&imagetype=Gif&rotation=0&color=%23000000&bgcolor=%23ffffff&qunit=Mm&quiet=0'></br> 
            <span style="font-size:8px"><?= ucfirst($doctor); ?></span >
            </div>
            <script>
              
            </script>

<body onfocus="window.close()"><div id="printBarcodePage" style="margin-left:30px;font-size:12px;font-family: Arial;">
    <style>
        div.b128{
            border-left: 1px black solid; 
            height:20px;
        }

    </style>
    <span style="font-size:10px"><b>  <?= $patient_name; ?></b></span></br>
    <span style="font-size:9px"><b>  <?= $patient_gender; ?>/<?= $patient_age; ?>&nbsp;<?= $age_type ?> </b>&nbsp;(<?= date("d-M-Y", strtotime($date)); ?>)</span>
    </br> 
    <span style="font-size:8px"><?= $barcode; ?></<span >
            <span style="font-size:8px"><?= ucfirst($doctor); ?></span ></br>
            <span style="font-size:8px"><?= ucfirst($sample); ?></span ></br>
            <span style="font-size:8px"><?= ucfirst($F_PPBS); ?></span >
            </div>
            <script>
        //    window.focus();
               window.print();
         //      window.close();
            </script>
            </body>

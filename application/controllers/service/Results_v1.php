<?php

class Results_v1 extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->library('email');
        $this->load->model('job_model');
        $this->load->model('user_master_model');
        $this->load->library('Firebase_notification');
        $this->load->helper('security');
        $this->load->helper('string');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST');
        header("Access-Control-Allow-Headers: X-Requested-With");
        // $this->app_tarce();
    }


    function json_data($status, $error_msg, $data = NULL, $custom = null)
    {
        if ($data == NULL) {
            $data = array();
        }
        $final = array("statusCode" => $status, "statusMessage" => $error_msg, 'version' => "1.0.03", "data" => $data, "customExceptionObject" => $custom);
        return str_replace("null", '" "', json_encode($final));
    }

    function checkHeader()
    {
        $headers = apache_request_headers();
        if (isset($headers['Username']) && isset($headers['Password'])) {
            if ($headers['Username'] != "airmedlabs" && $headers['password'] != "Newpass123!") {
                echo $this->json_data("0", "unauthorized", "");
                die;
            }
        } else {
            echo $this->json_data("0", "unauthorized", "");
            die;
        }
    }

    function GetDataForMacInterface()
    {
        $this->checkHeader();
        $data1['machine_type'] = $machine_type = $this->input->get_post('machine_type');
        $center = $this->input->get_post('center');
        $data1['center'] = "";
        if ($center == 169) {
            $data1['center'] = [169, 175, 164];
        }
        if ($center == 2) {
            $data1['center'] = [2,22, 6, 7, 145, 183, 184, 172, 161, 149, 131, 142];
        }
        if ($center == 173) {
            $data1['center'] = [173, 159, 148, 160, 144, 140];
        }


        $getbarcode = $this->job_model->get_val("SELECT `acknowledgement_for_macInterface_barcode`.barcode FROM `acknowledgement_for_macInterface_barcode`");
        $barcode = [];
        if ($getbarcode) {
            $barcode = array_column($getbarcode, 'barcode');
        }

        $data1['barcode'] = $barcode;
        $data1['p_ref'] = $this->input->get_post('p_ref');
        // if ($_GET['debug'] == '1') {
        //     $data1['city']=6;
        // }
        // echo "<pre>"; print_r(implode(",", $data1["barcode"])); die;
        $row1 = $this->job_model->sampleCollectedList($data1, '', '');
        if($_GET['debug']==1){
            echo "<pre>"; print_r($row1); die; 
            }
        $row = [];
        
        foreach ($row1 as $key) {
            $branch = $key["branch_fk"];
            $job_fk = $key["jobid"];
            if ($center != "") {
                $branch =  $center;
            }

            $key['testname'] = "";
            $query = "SELECT `job_test_list_master`.`test_fk`,`test_master`.`test_name` FROM `job_test_list_master` INNER JOIN `test_master` ON `job_test_list_master`.`test_fk`=`test_master`.`id` WHERE `job_test_list_master`.`job_fk`='" . $key["jobid"] . "'";

            // if ($machine_type=="H3PRO") {
            //     $query .= " AND job_test_list_master.test_fk='236'";
            // }
            $job_test_list = $this->job_model->get_val($query);
          
           
           
            $package_ids = $this->job_model->get_job_booking_package($key["jobid"]);
           
            $query["package"] = $package_ids;
            if (!empty($package_ids)) {
                $query["packagename"] = "";
            }
           
            /* Check sub test start */
            $job_tst_lst = array();
            // if(count($job_test_list)>0){
            foreach ($job_test_list as $st_key) {
                //echo $st_key['test_fk'];

                if (preg_match('/\b(CBC|Cbc|cbc)\b/', $st_key['test_name'])) {
                    $test_fk = 236;
                }else{
                    $test_fk = $st_key['test_fk'];
                }
             

                $data1 = $this->job_model->get_val("
                select ts.test_fk,ts.barcode,ts.sample_collection,tm.test_name,tm.sample as sample_type
                from test_sample_barcode ts
                INNER JOIN test_master tm on tm.id = ts.test_fk
				left join branch_sample_type bt on bt.test_fk=tm.id and bt.branch_fk='$branch' and bt.status='1'  where ts.job_fk = '$job_fk' and ts.test_fk ='$test_fk' AND ts.status ='1' group by tm.id");

                $job_sub_test_list = $this->job_model->get_val("SELECT `sub_test_master`.test_fk,`sub_test_master`.`sub_test`,test_master.`test_name` FROM `sub_test_master` INNER JOIN `test_master` ON `sub_test_master`.`sub_test`=`test_master`.`id` WHERE `sub_test_master`.`status`='1' AND `test_master`.`status`='1' AND `sub_test_master`.`test_fk`='" . $st_key['test_fk'] . "'");
                $st_key["sub_test"] = $job_sub_test_list;
                $job_tst_lst[] = $st_key;

                // link branch with Gandhinagar
                // AIRMED- GANDHINAGAR,NAMOSTUTE PMJAY GANDHINAGAR
                // AHM-AIRMED NAMOSTUTE
                $gandhinagrBranchs = [169,178, 175, 164];

                if (in_array($branch, $gandhinagrBranchs)) {

                    $branch = 169;
                }
                // link branch with AIRMED- RATAN MULTI-SPECIALITY HOSPITAL LLP
                // AHM BAPUNAGAR,	PIXEL DIAGNOSTICS - BAPUNAGAR ,	AHM SHARNAM HOSPITAL,PIXEL DIAGNOSTICS MANINAGAR,
                //   URJA DIAGNOSTICS NARODA,

                $ratanBranchs = [173, 159, 148, 160, 144, 140];

                if (in_array($branch, $ratanBranchs)) {
                    $branch = 173;
                }

                // link branch with AHM- Usmanpura
                // AHM - Sardarnagar ,AHM - Shahibaug,
                //   	AHM- VEDANT HOSPITAL,	CURA HOSPITAL,CURA HOSPITAL PMJAY,PIXEL DIAGNOSTICS - JUHAPURA,
                // PIXEL DIAGNOSTICS - JUHAPURA,PIXEL DIAGNOSTICS - SABARMATI,	PIXEL DIAGNOSTICS - SATELLITE,	PIXEL DIAGNOSTICS VADAJ

                $usmanpuraBranchs = [2, 6, 7, 145, 183, 184, 172, 161, 149, 131, 142];

                if (in_array($branch, $usmanpuraBranchs)) {
                    $branch = 2;
                }




                $dataprocessing_center['processing_center'] = $this->job_model->master_fun_get_tbl_val("processing_center", array('status' => 1, 'lab_fk' => $branch), array("id", "asc"));
                if (empty($dataprocessing_center['processing_center'])) {
                    $processing_center = '1';
                } else {
                    $processing_center = $dataprocessing_center['processing_center'][0]["branch_fk"];
                }

                $collecttest = $this->job_model->get_val("SELECT GROUP_CONCAT(`test_fk`) AS collectsample FROM test_sample_barcode WHERE STATUS='1' AND `job_fk`='" .  $job_fk . "' AND `sample_collection`='1' GROUP BY job_fk");

                $testcollect = explode(",", $collecttest[0]["collectsample"]);

                $tid = array($test_fk);

                $collecttest = array();
                foreach ($tid as $testidj) {

                    if (in_array($testidj, $testcollect)) {
                        $collecttest[] = $testidj;
                    }
                }
                $sub_test = [];
                if($_GET['debug']==1){
                    echo "<pre>"; print_r($job_sub_test_list); die; 
                    }  
                if (isset($job_sub_test_list) && count($job_sub_test_list) > 0) {
                    foreach ($job_sub_test_list as $stkey) {
                        if (preg_match('/\b(CBC|Cbc|cbc)\b/', $stkey['test_name'])) {
                            $test_fk = 236;
                        }else{
                            $test_fk = $stkey['sub_test'];
                        }
                        // $test_fk =   $stkey['sub_test'];

                        $data1 = $this->job_model->get_val("
                        select ts.test_fk,ts.barcode,ts.sample_collection,tm.test_name,tm.sample as sample_type
                        from test_sample_barcode ts
                        INNER JOIN test_master tm on tm.id = ts.test_fk
                        left join branch_sample_type bt on bt.test_fk=tm.id and bt.branch_fk='$branch' and bt.status='1'  where ts.job_fk = '$job_fk' and ts.test_fk ='$test_fk' AND ts.status ='1' group by tm.id");
                        
                        $get_test_parameter = $this->job_model->get_val("SELECT `test_parameter`.*,`test_master`.`test_name`,`test_master`.PRINTING_NAME,`test_master`.`report_type` FROM `test_parameter` INNER JOIN `test_master` ON `test_parameter`.`test_fk`=`test_master`.`id` WHERE `test_parameter`.`status`='1' AND `test_master`.`status`='1' AND `test_parameter`.`test_fk`='" . $test_fk . "' and `test_parameter`.`center`='" . $processing_center . "' order by `test_parameter`.order asc");
                        // echo "<pre>"; print_r($get_test_parameter); die;
                        $pid = array();
                        foreach ($get_test_parameter as $tp_key) {
                            if (!empty($tp_key["parameter_fk"])) {
                                if (!empty($tp_key["parameter_fk"])) {
                                    $pid[] = $tp_key["parameter_fk"];
                                }
                            }
                        }
                        // echo "<pre>"; print_r($pid); die;
                        if (!empty($pid)) {
                            $para = $this->job_model->get_val("SELECT * FROM `test_parameter_master` WHERE `status`='1' AND id IN (" . implode(",", $pid) . ") ORDER BY FIELD(id," . implode(",", $pid) . ")");



                            //  echo "<pre>"; print_r($para); die;
                            foreach ($para as $paramiter) {
                                $key['testId'] =  $test_fk;
                                $key['testname'] =  $stkey['test_name'];
                                $key["sampletype"] = $data1[0]['sample_type'];
                                $key["ParameterId"] = $paramiter['id'];
                                $key["center"] = $paramiter['center'];
                                $key["Parametername"] = $paramiter['parameter_name'];
                                if ($paramiter['parameter_name'] != "") {
                                    // $key["parameter_name"] =$paramiter['testcode'];
                                    $row[] = $key;
                                }
                            }
                        } else {
                            $key['testId'] = $test_fk;
                            $key['testname'] =  $st_key['test_name'];
                            $key["sampletype"] = $data1[0]['sample_type'];
                            $key["center"] = $st_key['center'];
                            $key["ParameterId"] = '';
                            $key["Parametername"] = '';
                            $key["parameter_name"] = '';
                            //$row[] = $key;
                        }
                    }
                } else {
                    $get_test_parameter = $this->job_model->get_val("SELECT `test_parameter`.*,`test_master`.`test_name`,`test_master`.PRINTING_NAME,`test_master`.`report_type` FROM `test_parameter` INNER JOIN `test_master` ON `test_parameter`.`test_fk`=`test_master`.`id` WHERE `test_parameter`.`status`='1' AND `test_master`.`status`='1' AND `test_parameter`.`test_fk`='" . $test_fk . "' and `test_parameter`.`center`='" . $processing_center . "' order by `test_parameter`.order asc");

                    // echo "<pre>"; print_r($get_test_parameter); die;
                    $pid = array();
                    foreach ($get_test_parameter as $tp_key) {
                        if (!empty($tp_key["parameter_fk"])) {
                            if (!empty($tp_key["parameter_fk"])) {
                                $pid[] = $tp_key["parameter_fk"];
                            }
                        }
                    }
                    // echo "<pre>"; print_r($pid); die;
                    if (!empty($pid)) {
                        $para = $this->job_model->get_val("SELECT * FROM `test_parameter_master` WHERE `status`='1' AND id IN (" . implode(",", $pid) . ") ORDER BY FIELD(id," . implode(",", $pid) . ")");



                        //  echo "<pre>"; print_r($para); die;
                        foreach ($para as $paramiter) {
                            $key['testId'] =  $test_fk;
                            $key['testname'] =  $st_key['test_name'];
                            $key["sampletype"] = $data1[0]['sample_type'];
                            $key["ParameterId"] = $paramiter['id'];
                            $key["center"] = $paramiter['center'];
                            $key["Parametername"] = $paramiter['parameter_name'];
                            if ($paramiter['parameter_name'] != "") {
                                // $key["parameter_name"] =$paramiter['testcode'];
                                $row[] = $key;
                            }
                        }
                    } else {
                        $key['testId'] = $test_fk;
                        $key['testname'] =  $st_key['test_name'];
                        $key["sampletype"] = $data1[0]['sample_type'];
                        $key["center"] = $st_key['center'];
                        $key["ParameterId"] = '';
                        $key["Parametername"] = '';
                        $key["parameter_name"] = '';
                        //$row[] = $key;
                    }
                }

            }
              // }
            // else{
              
                foreach ($package_ids as $st_key) {
                    //echo $st_key['test_fk'];
                  foreach( $st_key['test'] as  $st_key){

                    if (preg_match('/\b(CBC|Cbc|cbc)\b/', $st_key['test_name'])) {
                        $test_fk = 236;
                    }else{
                        $test_fk = $st_key['test_fk'];
                    }
    
                    // $test_fk = $st_key['test_fk'];
    
                    $data1 = $this->job_model->get_val("
                    select ts.test_fk,ts.barcode,ts.sample_collection,tm.test_name,tm.sample as sample_type
                    from test_sample_barcode ts
                    INNER JOIN test_master tm on tm.id = ts.test_fk
                    left join branch_sample_type bt on bt.test_fk=tm.id and bt.branch_fk='$branch' and bt.status='1'  where ts.job_fk = '$job_fk' and ts.test_fk ='$test_fk' AND ts.status ='1' group by tm.id");
    
                    $job_sub_test_list = $this->job_model->get_val("SELECT `sub_test_master`.test_fk,`sub_test_master`.`sub_test`,test_master.`test_name` FROM `sub_test_master` INNER JOIN `test_master` ON `sub_test_master`.`sub_test`=`test_master`.`id` WHERE `sub_test_master`.`status`='1' AND `test_master`.`status`='1' AND `sub_test_master`.`test_fk`='" . $st_key['test_fk'] . "'");
                    $st_key["sub_test"] = $job_sub_test_list;
                    $job_tst_lst[] = $st_key;
                    
                    // link branch with Gandhinagar
                    // AIRMED- GANDHINAGAR,NAMOSTUTE PMJAY GANDHINAGAR
                    // AHM-AIRMED NAMOSTUTE
                    $gandhinagrBranchs = [169, 175, 164];
    
                    if (in_array($branch, $gandhinagrBranchs)) {
    
                        $branch = 169;
                    }
                    // link branch with AIRMED- RATAN MULTI-SPECIALITY HOSPITAL LLP
                    // AHM BAPUNAGAR,	PIXEL DIAGNOSTICS - BAPUNAGAR ,	AHM SHARNAM HOSPITAL,PIXEL DIAGNOSTICS MANINAGAR,
                    //   URJA DIAGNOSTICS NARODA,
    
                    $ratanBranchs = [173, 159, 148, 160, 144, 140];
    
                    if (in_array($branch, $ratanBranchs)) {
                        $branch = 173;
                    }
    
                    // link branch with AHM- Usmanpura
                    // AHM - Sardarnagar ,AHM - Shahibaug,
                    //   	AHM- VEDANT HOSPITAL,	CURA HOSPITAL,CURA HOSPITAL PMJAY,PIXEL DIAGNOSTICS - JUHAPURA,
                    // PIXEL DIAGNOSTICS - JUHAPURA,PIXEL DIAGNOSTICS - SABARMATI,	PIXEL DIAGNOSTICS - SATELLITE,	PIXEL DIAGNOSTICS VADAJ
    
                    $usmanpuraBranchs = [2, 6, 7, 145, 183, 184, 172, 161, 149, 131, 142];
    
                    if (in_array($branch, $usmanpuraBranchs)) {
                        $branch = 2;
                    }
    
    
    
    
                    $dataprocessing_center['processing_center'] = $this->job_model->master_fun_get_tbl_val("processing_center", array('status' => 1, 'lab_fk' => $branch), array("id", "asc"));
                    if (empty($dataprocessing_center['processing_center'])) {
                        $processing_center = '1';
                    } else {
                        $processing_center = $dataprocessing_center['processing_center'][0]["branch_fk"];
                    }
    
                    $collecttest = $this->job_model->get_val("SELECT GROUP_CONCAT(`test_fk`) AS collectsample FROM test_sample_barcode WHERE STATUS='1' AND `job_fk`='" .  $job_fk . "' AND `sample_collection`='1' GROUP BY job_fk");
    
                    $testcollect = explode(",", $collecttest[0]["collectsample"]);
    
                    $tid = array($test_fk);
    
                    $collecttest = array();
                    foreach ($tid as $testidj) {
    
                        if (in_array($testidj, $testcollect)) {
                            $collecttest[] = $testidj;
                        }
                    }
                    $sub_test = [];
                    if (isset($job_sub_test_list) && count($job_sub_test_list) > 0) {
                        foreach ($job_sub_test_list as $stkey) {

                            if (preg_match('/\b(CBC|Cbc|cbc)\b/', $stkey['test_name'])) {
                                $test_fk = 236;
                            }else{
                                $test_fk = $stkey['sub_test'];
                            }
                            // $test_fk =   $stkey['sub_test'];
    
                            $data1 = $this->job_model->get_val("
                            select ts.test_fk,ts.barcode,ts.sample_collection,tm.test_name,tm.sample as sample_type
                            from test_sample_barcode ts
                            INNER JOIN test_master tm on tm.id = ts.test_fk
                            left join branch_sample_type bt on bt.test_fk=tm.id and bt.branch_fk='$branch' and bt.status='1'  where ts.job_fk = '$job_fk' and ts.test_fk ='$test_fk' AND ts.status ='1' group by tm.id");
                            
                            $get_test_parameter = $this->job_model->get_val("SELECT `test_parameter`.*,`test_master`.`test_name`,`test_master`.PRINTING_NAME,`test_master`.`report_type` FROM `test_parameter` INNER JOIN `test_master` ON `test_parameter`.`test_fk`=`test_master`.`id` WHERE `test_parameter`.`status`='1' AND `test_master`.`status`='1' AND `test_parameter`.`test_fk`='" . $test_fk . "' and `test_parameter`.`center`='" . $processing_center . "' order by `test_parameter`.order asc");
                            // echo "<pre>"; print_r($get_test_parameter); die;
                            $pid = array();
                            foreach ($get_test_parameter as $tp_key) {
                                if (!empty($tp_key["parameter_fk"])) {
                                    if (!empty($tp_key["parameter_fk"])) {
                                        $pid[] = $tp_key["parameter_fk"];
                                    }
                                }
                            }
                            // echo "<pre>"; print_r($pid); die;
                            if (!empty($pid)) {
                                $para = $this->job_model->get_val("SELECT * FROM `test_parameter_master` WHERE `status`='1' AND id IN (" . implode(",", $pid) . ") ORDER BY FIELD(id," . implode(",", $pid) . ")");
    
    
    
                                //  echo "<pre>"; print_r($para); die;
                                foreach ($para as $paramiter) {
                                    $key['testId'] =  $test_fk;
                                    $key['testname'] =  $stkey['test_name'];
                                    $key["sampletype"] = $data1[0]['sample_type'];
                                    $key["ParameterId"] = $paramiter['id'];
                                    $key["center"] = $paramiter['center'];
                                    $key["Parametername"] = $paramiter['parameter_name'];
                                    if ($paramiter['parameter_name'] != "") {
                                        // $key["parameter_name"] =$paramiter['testcode'];
                                        $row[] = $key;
                                    }
                                }
                            } else {
                                $key['testId'] = $test_fk;
                                $key['testname'] =  $st_key['test_name'];
                                $key["sampletype"] = $data1[0]['sample_type'];
                                $key["center"] = $st_key['center'];
                                $key["ParameterId"] = '';
                                $key["Parametername"] = '';
                                $key["parameter_name"] = '';
                                //$row[] = $key;
                            }
                        }
                    } else {
                       
                        $get_test_parameter = $this->job_model->get_val("SELECT `test_parameter`.*,`test_master`.`test_name`,`test_master`.PRINTING_NAME,`test_master`.`report_type` FROM `test_parameter` INNER JOIN `test_master` ON `test_parameter`.`test_fk`=`test_master`.`id` WHERE `test_parameter`.`status`='1' AND `test_master`.`status`='1' AND `test_parameter`.`test_fk`='" . $test_fk . "' and `test_parameter`.`center`='" . $processing_center . "' order by `test_parameter`.order asc");
                        
                        // echo "<pre>"; print_r($get_test_parameter); die;
                        $pid = array();
                        foreach ($get_test_parameter as $tp_key) {
                            if (!empty($tp_key["parameter_fk"])) {
                                if (!empty($tp_key["parameter_fk"])) {
                                    $pid[] = $tp_key["parameter_fk"];
                                }
                            }
                        }
                        // echo "<pre>"; print_r($pid); die;
                        if (!empty($pid)) {
                            $para = $this->job_model->get_val("SELECT * FROM `test_parameter_master` WHERE `status`='1' AND id IN (" . implode(",", $pid) . ") ORDER BY FIELD(id," . implode(",", $pid) . ")");
    
    
    
                            //  echo "<pre>"; print_r($para); die;
                            foreach ($para as $paramiter) {
                                $key['testId'] =  $test_fk;
                                $key['testname'] =  $st_key['test_name'];
                                $key["sampletype"] = $data1[0]['sample_type'];
                                $key["ParameterId"] = $paramiter['id'];
                                $key["center"] = $paramiter['center'];
                                $key["Parametername"] = $paramiter['parameter_name'];
                                if ($paramiter['parameter_name'] != "") {
                                    // $key["parameter_name"] =$paramiter['testcode'];
                                    $row[] = $key;
                                }
                            }
                        } else {
                            $key['testId'] = $test_fk;
                            $key['testname'] =  $st_key['test_name'];
                            $key["sampletype"] = $data1[0]['sample_type'];
                            $key["center"] = $st_key['center'];
                            $key["ParameterId"] = '';
                            $key["Parametername"] = '';
                            $key["parameter_name"] = '';
                            //$row[] = $key;
                        }
                    }
    
                }
            // }
            }
        }


        $data['_lstMInterPatientDetails'] = $row;
        $customExceptionObject = ['pExceptionType' => null, 'pException' => null, 'pSource' => null, 'pStackTrace' => null, 'pInnerExceptionType' => null, 'pInnerException' => null, 'pInnerExceptionSource' => null, 'pInnerStackTrace' => null, 'pExceptionDate' =>  date('Y-m-d H:i:s')];
        if ($row) {
            echo $this->json_data("1", "", $data, $customExceptionObject);
        } else {
            echo $this->json_data("0", "Data Not Found", "");
        }
    }

    public function PostAcknowledgementForMacInterface()
    {


        $this->checkHeader();
        $listsample = $this->input->get_post('listsample');
        $listsample = json_decode($listsample);
        if (count($listsample) > 0) {
            foreach ($listsample as $data) {

                $this->job_model->master_fun_insert("acknowledgement_for_macInterface_barcode", array("barcode" => $data->barcode));
            }
            echo $this->json_data("1", "Record Added", "");
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    public function PostResultForMacInterface()
    {

        $this->checkHeader();

        $listsample1 = $listsample = $this->input->get_post('listsampleResult');

        $listsample = json_decode($listsample, true);
        //  echo "<pre>"; print_r($listsample); die;
        if (count($listsample) > 0) {
            $no = 0;
            foreach ($listsample as $data) {

                if (isset($data['PatientId']) && isset($data['barcode']) && isset($data['testId']) && isset($data['parameterId']) && isset($data['result']) && isset($data['machinename'])) {
                    $no = 1;
                    $this->job_model->master_fun_insert("result_for_mac_interface", array("patientId" => $data['PatientId'], 'barcode' => $data['barcode'], 'testId' => $data['testId'], 'ParameterId' => $data['parameterId'], 'Result' => $data['result'], 'machinename' => $data['machinename'], 'machine_test_code' => $data['machine_test_code'], 'created_at' => date('Y-m-d H:i:s')));

                    $parameterId = $data['parameterId'];

                    $testId = $data['testId'];

                    $para = $this->job_model->get_val("SELECT * FROM `test_parameter_master` WHERE `status`='1' AND id = $parameterId");

                    $test_master = $this->job_model->get_val("SELECT * FROM `test_master` WHERE `status`='1' AND id = $testId");

                    $testCode = "";
                    if ($test_master) {
                        $testCode = $test_master[0]['test_name'];
                    }

                    $branch_fk = "";
                    if ($para) {
                        $branch_fk = $para[0]['center'];
                    }
                    $code = "";
                    if ($para) {
                        $code = $para[0]['code'];
                    }

                    $this->job_model->master_fun_insert("instument_data_storage_new", array("lab_id" => $branch_fk, 'barcode' => $data['barcode'], 'test_code' => $testCode, 'para_code' => $data['machine_test_code'], 'para_value' => $data['result'], 'machinename' => $data['machinename'], 'test_date_time' => date('Y-m-d H:i:s')));
                } else {
                    $no = 0;
                    if (!isset($data['PatientId'])) {
                        echo $this->json_data("0", "PatientId parameter is missing", "");
                        die;
                    }
                    if (!isset($data['barcode'])) {
                        echo $this->json_data("0", "Barcode parameter is missing", "");
                        die;
                    }
                    if (!isset($data['testId'])) {
                        echo $this->json_data("0", "TestId parameter is missing", "");
                        die;
                    }
                    if (!isset($data['parameterId'])) {
                        echo $this->json_data("0", "ParameterId parameter is missing", "");
                        die;
                    }
                    if (!isset($data['result'])) {
                        echo $this->json_data("0", "Result parameter is missing", "");
                        die;
                    }
                    if (!isset($data['machinename'])) {
                        echo $this->json_data("0", "Machinename parameter is missing", "");
                        die;
                    }
                    echo $this->json_data("0", "Parameter miss match", "");
                    die;
                }
            }
            if ($no == 1) {
                echo $this->json_data("1", "Record Added", $listsample1, "");
            } else {
                echo $this->json_data("0", "Parameter not passed", "");
            }
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }
}

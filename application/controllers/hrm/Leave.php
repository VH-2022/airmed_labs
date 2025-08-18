<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Leave extends CI_Controller {

    public $CI = NULL;

    function __construct() {
        parent::__construct();
        $this->CI = & get_instance();
        $this->load->model('user_model');
        $this->load->model('hrm/employee_model');
        $this->load->model('hrm/leave_model');
        $this->load->model('hrm/department_model');
        $this->load->library('email');
        $this->load->library('pushserver');
        $this->load->helper('string');
        //ini_set('display_errors', 'On');

        $data["login_data"] = is_hrmlogin();
    }

    function leave_list() {
        if (!is_hrmlogin()) {
            redirect('login');
        }
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 300); //300 seconds = 5 minutes
        $data["login_data"] = is_hrmlogin();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $data['unsuccess'] = $this->session->flashdata("unsuccess");
        /* Nishit code start */
        if ((!empty($_POST["uyear"]) && !empty($_POST["umonth"])) || !empty($_POST["ucity"])) {

            $this->load->library('form_validation');
            $this->form_validation->set_rules('uyear', 'Date', 'trim|required');
            $this->form_validation->set_rules('umonth', 'Date', 'trim|required');
            $this->form_validation->set_rules('userfile', 'CSV', 'callback__check_valid_userfile');
            if ($this->form_validation->run() != FALSE) {
                $uploaddata = $this->uploadData();
                //echo "<pre>"; print_r($uploaddata["data"]); exit;
                
                foreach ($uploaddata["data"] as $udkey) {
                    $uyear = $this->input->get_post("uyear");
                    $umonth = $this->input->get_post("umonth");
                    //$umonth_year = explode("-", $umonth);
                    $udate = explode("-", $udkey["Date"]);
                    $employee_details = $this->leave_model->get_all("hrm_employees", array("employee_id" => $udkey["Employee Code"], "status" => "1"));
                    $all_record = $this->leave_model->get_all("hrm_month_atendance", array("employee_fk" => $employee_details[0]->id, "month" => $umonth, "year" => $uyear, "status" => 1));
                    $present = 0;
                    if (!empty($employee_details)) {
                        $srno = $udkey['Sr. No.'];
                        $employee_code = $udkey['Employee Code'];
                        $name_of_employee = $udkey['Name of Employee'];
                        $leaves = $udkey['No. of Leaves'];
                        $ot = $udkey['Over Time'];
                        $petrol = $udkey['Petrol'];
                        $incentive = $udkey['Incentive'];
                        $sunday_wages = $udkey['Sunday Wages'];
                        $festival_allowance = $udkey['Festival Allowance'];
                        $others = $udkey['Others Earning'];
                        $tds = $udkey['TDS'];
                        $MAINTENACE = $udkey['MAINTENACE COST'];
                        $Working_Hours = $udkey['Working Hours'];
                        $Other_Deduction = $udkey['Other Deduction'];

                        if ($tds > 0) {
                            
                        } else {
                            $tds = 0;
                        }
                        $daynumber = cal_days_in_month(CAL_GREGORIAN, $umonth, $uyear);
                        $data1 = array(
                            "employee_fk" => $employee_details[0]->id,
                            "month" => $umonth,
                            "year" => $uyear,
                            "present_day" => $daynumber - $leaves,
                            "leave" => $leaves,
                            "ot" => $ot,
                            "sunday_wages" => $sunday_wages,
                            "festival_allowance" => $festival_allowance,
                            "petrol" => $petrol,
                            "incentive" => $incentive,
                            "others" => $others,
                            "created_date" => date("Y-m-d H:i:s"),
                            "status" => 1,
                            "tds" => $tds,
                            "maintenace_cost" => $MAINTENACE,
                            "working_hr" => $Working_Hours,
                            "other_deduction" => $Other_Deduction
                        );
                        if (count($all_record) > 0) {
                            $update = $this->leave_model->update("hrm_month_atendance", array("employee_fk" => $employee_details[0]->id, "month" => $umonth, "year" => $uyear, "status" => 1), $data1);
                            $insert = $all_record[0]->id;
                        } else {
                            $insert = $this->leave_model->insert("hrm_month_atendance", $data1);
                        }

                        /* SPECIAL CALCULATION START */
                        $employee_salary = 0;
                        $employee_salary = $employee_details[0]->joining_salary;
                        $sal_option = $employee_details[0]->sal_option;

                        $moth_day = $daynumber;
                        $leave = $leaves;

                        $leave_cut_amount = 0;

                        if ($leave > 0) {
                            $leave_cut_amount = round(($employee_salary / $moth_day) * $leave);
                        }

                        /* END */
                        /* salary structure start */
                        $get_common_salary_structure = $this->leave_model->get_val("SELECT DISTINCT(salary_name) as salary_name FROM `hrm_master_salary_structure_details` WHERE `status`='1' and salary_strucure_id='" . $employee_details[0]->salary_structure_id . "' order by `order` asc");
                        $j_salary = $employee_details[0]->joining_salary;
                        $salary_array = array();
                        $pf = 0;
                        $e_pf = 0;
                        $esic = 0;
                        $e_esic = 0;

                        $pf1 = 0;
                        $e_pf1 = 0;
                        $esic1 = 0;
                        $e_esic1 = 0;

                        $basic = 0;
                        $da = 0;
                        $hra = 0;
                        $convence = 0;
                        $madical = 0;
                        $pt = 0;
                        $fstvl = 0;
                        $other_allowance = 0;
                        $other_reimbursement = 0;
                        $Other__deductions = 0;
                        $special_allowance = 0;

                        foreach ($get_common_salary_structure as $csskey) {

                            $employee_salary_structure = $this->leave_model->get_val('SELECT * FROM `hrm_master_salary_structure_details` WHERE `status`="1" AND `salary_strucure_id`="' . $employee_details[0]->salary_structure_id . '" AND `salary_name`="' . $csskey["salary_name"] . '" order by `order` asc');
                            $s_name = $csskey["salary_name"];
                            if (!empty($employee_salary_structure)) {
                                $salary_calculate = 0;
                                if ($employee_salary_structure[0]["cutofftype"] == 1) {
                                    $salary_calculate = $employee_salary_structure[0]["value"];
                                }
                                if ($employee_salary_structure[0]["cutofftype"] == 2) {
                                    $salary_calculate = round($j_salary * $employee_salary_structure[0]["value"] / 100);
                                }


                                /* SPECIAL CALCULATION START */
                                if (trim(strtoupper($s_name)) == 'BASIC') {
                                    $basic1 = $salary_calculate;
                                    $present_day_basic = $salary_calculate;
                                    if ($leave > 0) {
                                        $present_day_basic = round($salary_calculate - (($salary_calculate / $moth_day) * $leave));
                                    }
                                    //$salary_calculate = $present_day_basic;
                                    $basic = $present_day_basic;
                                    //$basic = $salary_calculate;
                                }

//                                if (trim(strtoupper($s_name)) == "BASIC") {
//                                    //$basic = $salary_calculate;
//                                    $present_day_basic = $salary_calculate;
//                                    if ($leave > 0) {
//                                        $present_day_basic = round($salary_calculate - (($salary_calculate / $moth_day) * $leave));
//                                    }
//                                    $basic = $present_day_basic;
//                                }
                                if (trim(strtoupper($s_name)) == "DA") {
                                    $da1 = $salary_calculate;
                                    $da = $salary_calculate;
                                    if ($leave > 0) {
                                        $da = round($salary_calculate - (($salary_calculate / $moth_day) * $leave));
                                    }
                                    //$salary_calculate = $da;
                                    //$da = $salary_calculate;
                                }
//                                if (trim(strtoupper($s_name)) == "HRA") {
//                                    $hra = $salary_calculate;
//                                }
//                                if (trim(strtoupper($s_name)) == "CONVEYANCE ALLOWANCE") {
//                                    $convence = $salary_calculate;
//                                }

                                if (trim(strtoupper($s_name)) == trim(strtoupper("Other Allowance"))) {
                                    $other_allowance1 = $salary_calculate;
                                    $other_allowance = $salary_calculate;
                                    if ($leave > 0) {
                                        $other_allowance = round($salary_calculate - (($salary_calculate / $moth_day) * $leave));
                                    }
                                    //$other_allowance = $salary_calculate;
                                    //$salary_calculate = $other_allowance;
                                }


//                                if (trim(strtoupper($s_name)) == "MEDICAL REIMBURSEMENT") {
//                                    $madical = $salary_calculate;
//                                }
                                if (trim(strtoupper($s_name)) == "PF-EMPLOYEE'S CONTRIBUTION") {
//                                    if ($employee_details[0]->is_profassion > 0) {
//                                        //$salary_calculate = 0;
//                                        $salary_calculate = round(($present_day_basic * 12) / 100);
//                                    } else {
//                                        $salary_calculate = round(($present_day_basic * 12) / 100);
//                                    }

                                    $pf = $salary_calculate = 0;
                                    if ($employee_salary <= 21000) {
                                        $pf = $salary_calculate = round(($present_day_basic * 12) / 100);
                                    }
                                    if ($employee_salary > 21000) {
                                        if ($sal_option == "1") {
                                            $pf = $salary_calculate = round(($present_day_basic * 12) / 100);
                                        }
                                    }

                                    $pf1 = $salary_calculate = 0;
                                    if ($employee_salary <= 21000) {
                                        $pf1 = $salary_calculate = round(($basic1 * 12) / 100);
                                    }
                                    if ($employee_salary > 21000) {
                                        if ($sal_option == "1") {
                                            $pf1 = $salary_calculate = round(($basic1 * 12) / 100);
                                        }
                                    }
                                    $salary_calculate = $pf;
                                }

//                                if (trim(strtoupper($s_name)) == "ESIC-EMPLOYEE'S CONTRIBUTION") {
//                                    if ($employee_details[0]->is_profassion > 0) {
//                                        $salary_calculate = 0;
//                                    } else {
//                                        $salary_calculate = round(($present_day_basic * 1.75) / 100);
//                                    }
//                                    $esic = $salary_calculate;
//                                }


                                if (trim(strtoupper($s_name)) == "PF-EMPLOYER'S CONTRIBUTION") {
                                    $e_pf = $salary_calculate = 0;
                                    if ($employee_salary <= 21000) {
                                        $e_pf = $salary_calculate = round(($present_day_basic * 12) / 100);
                                    }
                                    if ($employee_salary > 21000) {
                                        if ($sal_option == "1") {
                                            $e_pf = $salary_calculate = round(($present_day_basic * 12) / 100);
                                        }
                                    }


                                    $e_pf1 = $salary_calculate = 0;
                                    if ($employee_salary <= 21000) {
                                        $e_pf1 = $salary_calculate = round(($basic1 * 12) / 100);
                                    }
                                    if ($employee_salary > 21000) {
                                        if ($sal_option == "1") {
                                            $e_pf1 = $salary_calculate = round(($basic1 * 12) / 100);
                                        }
                                    }
                                    $salary_calculate = $e_pf;
                                }

                                if (trim(strtoupper($s_name)) == "TDS") {
                                    $tds = round($tds);
                                }
                                if (trim(strtoupper($s_name)) == "MAINTENACE COST") {
                                    $salary_calculate = round($MAINTENACE);
                                }

                                if (trim(strtoupper($s_name)) == "PT") {
                                    $salary_calculate = 0;
                                    if ($employee_salary >= '0' && $employee_salary <= '5999') {
                                        $salary_calculate = '0';
                                    }
                                    if ($employee_salary >= '6000' && $employee_salary <= '8999') {
                                        $salary_calculate = '80';
                                    }
                                    if ($employee_salary >= '9000' && $employee_salary <= '11999') {
                                        $salary_calculate = '150';
                                    }
                                    if ($employee_salary >= '12000') {
                                        $salary_calculate = '200';
                                    }
                                    $pt = $salary_calculate;
                                }
                                /* END */
                                /* SPECIAL ALLOWNACE START */

                                /* END */
                                if ($salary_calculate < 1) {
                                    $salary_calculate = 0;
                                }
                                $earning_type = 1;
                                if ($employee_salary_structure[0]["type"] == 1) {
                                    $total_earning = $total_earning + $salary_calculate;
                                }
                                if ($employee_salary_structure[0]["type"] == 2) {
                                    $total_deduction = $total_deduction + $salary_calculate;
                                    $earning_type = 2;
                                }
                                $ekey[$s_name] = $salary_calculate;
                            } else {
                                $ekey[$s_name] = 0;
                            }

                            $salary_array[] = array("atendance_fk" => $insert, "salary_field" => $s_name, "value" => $salary_calculate, "type" => 1, "earning_type" => $earning_type);
                        }

                        /* OT CALCULATION START */
                        $ot_amount = 0;
                        if ($data1["ot"] > 0) {
                            $ot_amount = round(((($employee_salary) / $moth_day) / $Working_Hours) * $data1["ot"]);
                        }
                        /* END */
                        /* SUNDAY WEAGS CALCULATION START */
                        $sw_amount = 0;

                        if ($data1["sunday_wages"] > 0) {
                            $sw_amount = round(($employee_salary / $moth_day) * $data1["sunday_wages"]);
                        }
                        /* END */

                        $fstvl = 0;
                        if ($data1["festival_allowance"] > 0) {
                            $fstvl = round(($employee_salary / $moth_day) * $data1["festival_allowance"]);
                        }
                        /* END */
                        /* HRA START */

                        $esic = 0;
                        $e_esic = 0;
                        //$present_day_basic_sal = round($basic * (($moth_day - $leave) / $moth_day));
                        $present_day_basic_sal = $basic;

                        //$present_day_da = round($da * (($moth_day - $leave) / $moth_day));
                        $present_day_da = $da;
                        //$present_day_allowance = round($other_allowance * (($moth_day - $leave) / $moth_day));
                        $present_day_allowance = $other_allowance;
                        if ($employee_details[0]->salary_structure_id == "27") {
                            if ($employee_salary <= 21000) {
                                //echo $present_day_basic.'-'.$other_allowance.'-'.$da;  exit;
                                $esic = round((($present_day_basic + $other_allowance + $da) * 1.75) / 100);
                                $e_esic = round((($present_day_basic + $other_allowance + $da) * 4.75) / 100);

                                $e_esic1 = round((($basic1 + $other_allowance1 + $da1) * 4.75) / 100);
                                $esic1 = round((($basic1 + $other_allowance1 + $da1) * 1.75) / 100);


                                //$esic = round((($present_day_basic_sal + $present_day_da + $present_day_allowance) * 1.75) / 100);
                                //$e_esic = round((($present_day_basic_sal + $present_day_da + $present_day_allowance) * 4.75) / 100);

                                $present_day_esic = round((($present_day_basic_sal + $present_day_da + $present_day_allowance) * 1.75) / 100);
                            } else {
                                $present_day_esic = "N/A";
                            }
//                            if ($employee_salary > 21000) {
//                                if ($sal_option == "1") {
//                                    $esic = round((($present_day_basic + $convence + $da) * 1.75) / 100);
//                                    $e_esic = round((($present_day_basic + $convence + $da) * 4.75) / 100);
//                                }
//                            }
                        }

                        $incentive = ($employee_salary * 40) / 100;
                        $incentive1 = $incentive;


                        if ($leave > 0) {
                            $incentive = round($incentive - (($incentive / $moth_day) * $leave));
                        }
//                        if ($leave > 0) {
//                            $incentive = round($incentive - (($incentive / $moth_day) * $leave));
//                        }
//                        if ($pf == 0) {
//                            $hra = round($basic * 40 / 100);  
//                        }
                        /* END */
                        /* SPECIAL ALLOWANCE START */
                        //$special_allowance = $employee_salary - ($basic + $da + $hra + $convence + $madical);
                        //$special_allowance = $convence;
                        /* END */
                        /* CTC START */
//                        $ctc = $basic + $da + $hra + $incentive + $madical + $special_allowance + $others;

                        /* END */
                        /* PER DAY BASIC SALARY START */

                        //$perday_basic = round(($basic - $leave_cut_amount) / $moth_day);
                        // 24 July 2018
                        //$perday_basic = round($basic * ($moth_day - $leave) / $moth_day);
                        // 24 July 2018


                        /* END */
                        /* NTH START */
                        //$nth = ($basic + $da + $hra + $incentive + $madical + $special_allowance + $MAINTENACE + $ot_amount + $petrol + $fstvl + $sw_amount + $others) - $pf - $e_pf - $esic - $e_esic - $pt - $tds - $leave_cut_amount - $Other_Deduction;
                        //$ctc = ($basic + $da + $hra + $incentive + $madical + $special_allowance + $MAINTENACE + $ot_amount + $petrol + $fstvl + $sw_amount + $others) + $pf + $e_pf + $esic + $e_esic + $pt + $tds + $leave_cut_amount + $Other_Deduction;

                        if ($employee_details[0]->salary_structure_id == "27") {
                            if ($sal_option == "1") {

//                              $ctc = ($basic + $da + $hra + $incentive + $madical + $special_allowance + $MAINTENACE + $ot_amount + $petrol + $fstvl + $sw_amount + $others);
                                $ctc = ($basic1 + $da1 + $hra + $incentive1 + $madical + $special_allowance + $other_allowance1) + ($pf1 + $e_pf1);
                            } else {

//                              $ctc = ($basic + $da + $hra + $incentive + $madical + $special_allowance + $MAINTENACE + $ot_amount + $petrol + $fstvl + $sw_amount + $others) + $pf + $e_pf + $esic + $e_esic + $pt + $tds + $leave_cut_amount + $Other_Deduction;
                                //echo $pf1 .'+'. $e_pf1 .'+'. $esic1 .'+'. $e_esic1; exit;

                                $ctc = ($basic1 + $da1 + $hra + $incentive1 + $madical + $special_allowance + $other_allowance1 + $pt) + ($pf1 + $e_pf1 + $esic1 + $e_esic1);

                                //echo $basic1.'+'.$da1.'+'.$hra.'+'.$incentive1.'+'.$madical.'+'.$special_allowance.'+'.$other_allowance1.'+'.$pt.'+'.$pf1.'+'.$e_pf1 .'+'.$esic1 .'+'.$e_esic1; exit;
                            }
                            if ($employee_details[0]->is_profassion == '1') {

                                $nth = ($basic + $da + $hra + $incentive + $madical + $special_allowance + $other_allowance + $ot_amount + $petrol + $fstvl + $sw_amount + $others) - $pf - $e_pf - $esic - $e_esic - $pt - $tds - $Other_Deduction - $MAINTENACE;
                            } else {

                                //echo $basic1.'+'.$da1.'+'.$hra.'+'.$incentive1.'+'.$madical.'+'.$special_allowance.'+'.$other_allowance1.'+'.$ot_amount.'+'.$petrol.'+'.$fstvl.'+'.$sw_amount.'+'.$others.'-'.$pf1.'-'.$esic1.'-'.$pt.'-'.$tds.'-'.$leave_cut_amount.'-'.$Other_Deduction.'-'.$MAINTENACE; exit;

                                $nth = ($basic + $da + $hra + $incentive + $madical + $special_allowance + $other_allowance + $ot_amount + $petrol + $fstvl + $sw_amount + $others) - $pf - $esic - $pt - $tds - $Other_Deduction - $MAINTENACE;
                            }
                        } else {
                            //echo $basic; exit;
                            //echo $basic.'+'.$da.'+'.$hra.'+'.$incentive.'+'.$madical.'+'.$special_allowance.'+'.$other_allowance.'+'.$ot_amount.'+'.$petrol.'+'.$fstvl.'+'.$sw_amount.'+'.$others.'-'.$pf.'-'.$e_pf.'-'.$esic.'-'.$e_esic.'-'.$pt.'-'.$tds.'-'.$leave_cut_amount.'-'.$Other_Deduction.'-'.$MAINTENACE; exit;
                            // Here PT is not added in CTC
//                            $ctc = ($basic + $da + $hra + $incentive + $madical + $special_allowance + $MAINTENACE + $ot_amount + $petrol + $fstvl + $sw_amount + $others) + $pf + $e_pf + $esic + $e_esic + $tds + $leave_cut_amount + $Other_Deduction;
                            $ctc = ($basic1 + $da1 + $hra + $incentive1 + $madical + $special_allowance + $other_allowance1);
                            $nth = ($basic + $da + $hra + $incentive + $madical + $special_allowance + $other_allowance + $ot_amount + $petrol + $fstvl + $sw_amount + $others) - $pf - $e_pf - $esic - $e_esic - $pt - $tds - $Other_Deduction - $MAINTENACE;
                        }





                        /* END */
                        $new_salary_array = array();
                        $added_salary_fields = array();
                        //echo "<pre>"; print_r($salary_array); exit;
                        foreach ($salary_array as $value) {
                            if (trim(strtoupper($value["salary_field"])) == trim(strtoupper("ESIC-Employee's Contribution"))) {
                                $value = array();
                                $value = array("atendance_fk" => $insert, "salary_field" => "ESIC-Employee's Contribution", "value" => $esic, "type" => 1, "earning_type" => 2);
                            }

                            if (trim(strtoupper($value["salary_field"])) == trim(strtoupper("ESIC-Employer's Contribution"))) {
                                $value = array();
                                $value = array("atendance_fk" => $insert, "salary_field" => "ESIC-Employer's Contribution", "value" => $e_esic, "type" => 1, "earning_type" => 2);
                            }

                            if (trim(strtoupper($value["salary_field"])) == "PT") {
                                $value = array();
                                $value = array("atendance_fk" => $insert, "salary_field" => "PT", "value" => $pt, "type" => 1, "earning_type" => 2);
                            }
                            if (trim(strtoupper($value["salary_field"])) == "TDS") {
                                $value = array();
                                $value = array("atendance_fk" => $insert, "salary_field" => "TDS", "value" => $tds, "type" => 1, "earning_type" => 2);
                            }

                            if (trim(strtoupper($value["salary_field"])) == "HRA") {
                                $value = array();
                                $value = array("atendance_fk" => $insert, "salary_field" => "HRA", "value" => $hra, "type" => 1, "earning_type" => 1);
                            }

                            if (trim(strtoupper($value["salary_field"])) == "OTHER ALLOWANCE") {
                                $value = array();
                                $value = array("atendance_fk" => $insert, "salary_field" => "Other Allowance", "value" => $other_allowance1, "type" => 1, "earning_type" => 1);
                            }

                            if (trim(strtoupper($value["salary_field"])) == "LEAVES") {
                                $value = array();
                                $value = array("atendance_fk" => $insert, "salary_field" => "Leaves", "value" => $leave_cut_amount, "type" => 2, "earning_type" => 1);
                            }

                            if (trim(strtoupper($value["salary_field"])) == "OT") {
                                $value = array();
                                $value = array("atendance_fk" => $insert, "salary_field" => "OT", "value" => $ot_amount, "type" => 3, "earning_type" => 1);
                            }

                            if (trim(strtoupper($value["salary_field"])) == "PETROL") {
                                $value = array();
                                $value = array("atendance_fk" => $insert, "salary_field" => "Petrol", "value" => $data1["petrol"], "type" => 4, "earning_type" => 1);
                            }

//                            if (trim(strtoupper($value["salary_field"])) == "INSENTIVE") {
//                                $value = array();
//                                $value = array("atendance_fk" => $insert, "salary_field" => "Insentive", "value" => $data1["incentive"], "type" => 5, "earning_type" => 1);
//                            }

                            if (trim(strtoupper($value["salary_field"])) == "INSENTIVE") {
                                $value = array();
                                $value = array("atendance_fk" => $insert, "salary_field" => "Insentive", "value" => $incentive1, "type" => 5, "earning_type" => 1);
                            }

                            if (trim(strtoupper($value["salary_field"])) == "SUNDAY WAGES") {
                                $value = array();
                                $value = array("atendance_fk" => $insert, "salary_field" => "Sunday Wages", "value" => $sw_amount, "type" => 6, "earning_type" => 1);
                            }

                            if (trim(strtoupper($value["salary_field"])) == "FESTIVAL ALLOWANCE") {
                                $value = array();
                                $value = array("atendance_fk" => $insert, "salary_field" => "Festival Allowance", "value" => $fstvl, "type" => 7, "earning_type" => 1);
                            }


                            if (trim(strtoupper($value["salary_field"])) == "OTHERS EARNING") {
                                $value = array();
                                $value = array("atendance_fk" => $insert, "salary_field" => "Others Earning", "value" => $others, "type" => 8, "earning_type" => 1);
                            }
                            if (trim(strtoupper($value["salary_field"])) == trim(strtoupper("Other Reimbursement"))) {
                                $value = array();
                                $other_reimbursement = $others + $ot_amount + $petrol + $sw_amount + $fstvl;
                                $value = array("atendance_fk" => $insert, "salary_field" => "Other Reimbursement", "value" => $other_reimbursement, "type" => 8, "earning_type" => 1);
                            }

                            if (trim(strtoupper($value["salary_field"])) == "OTHERS DEDUCTION") {
                                $value = array();
                                $value = array("atendance_fk" => $insert, "salary_field" => "Others Deduction", "value" => $Other_Deduction, "type" => 1, "earning_type" => 2);
                            }

                            if (trim(strtoupper($value["salary_field"])) == "OTHER DEDUCTIONS") {
                                $value = array();
                                $Other__deductions = $Other_Deduction + $MAINTENACE;
                                $value = array("atendance_fk" => $insert, "salary_field" => "Other Deductions", "value" => $Other__deductions, "type" => 1, "earning_type" => 2);
                            }


                            if (trim(strtoupper($value["salary_field"])) == "PRESENT DAY BASIC SALARY") {
                                $value = array();
//                                $value = array("atendance_fk" => $insert, "salary_field" => "Present Day Basic Salary", "value" => $perday_basic, "type" => 1, "earning_type" => 1);
                                $value = array("atendance_fk" => $insert, "salary_field" => "Present Day Basic Salary", "value" => $present_day_basic_sal, "type" => 1, "earning_type" => 1);
                            }
                            if (trim(strtoupper($value["salary_field"])) == "PRESENT DAY DA") {
                                $value = array();
                                $value = array("atendance_fk" => $insert, "salary_field" => "Present Day DA", "value" => $present_day_da, "type" => 1, "earning_type" => 1);
                            }
                            if (trim(strtoupper($value["salary_field"])) == "PRESENT DAY ALLOWANCE") {
                                $value = array();
                                $value = array("atendance_fk" => $insert, "salary_field" => "Present Day Allowance", "value" => $present_day_allowance, "type" => 1, "earning_type" => 1);
                            }
                            if (trim(strtoupper($value["salary_field"])) == "PRESENT DAY ESIC") {
                                $value = array();
                                $value = array("atendance_fk" => $insert, "salary_field" => "Present Day ESIC", "value" => $present_day_esic, "type" => 1, "earning_type" => 1);
                            }




                            if (trim(strtoupper($value["salary_field"])) == "SALARY (CTC) / PM") {
                                $value = array();
                                $value = array("atendance_fk" => $insert, "salary_field" => "SALARY (CTC) / PM", "value" => $ctc, "type" => 1, "earning_type" => 1);
                            }

                            if (trim(strtoupper($value["salary_field"])) == "NET TAKE HOME") {
                                $value = array();
                                $value = array("atendance_fk" => $insert, "salary_field" => "Net Take Home", "value" => $nth, "type" => 1, "earning_type" => 1);
                            }

                            $added_salary_fields[] = trim(strtoupper($value["salary_field"]));
                            $new_salary_array[] = $value;
                        }
                        //echo "<pre>"; print_r($new_salary_array); exit;

                        if (!in_array("PT", $added_salary_fields)) {
                            $new_salary_array[] = array("atendance_fk" => $insert, "salary_field" => "PT", "value" => $pt, "type" => 1, "earning_type" => 2);
                        }
                        if (!in_array("TDS", $added_salary_fields)) {
                            $new_salary_array[] = array("atendance_fk" => $insert, "salary_field" => "TDS", "value" => $tds, "type" => 1, "earning_type" => 2);
                        }
                        if (!in_array("HRA", $added_salary_fields)) {
                            $new_salary_array[] = array("atendance_fk" => $insert, "salary_field" => "HRA", "value" => $hra, "type" => 1, "earning_type" => 1);
                        }
//                        if (!in_array("OTHER ALLOWANCE", $added_salary_fields)) {
//                            $new_salary_array[] = array("atendance_fk" => $insert, "salary_field" => "Special Allowance", "value" => $special_allowance, "type" => 1, "earning_type" => 1);
//                        }
                        if (!in_array("LEAVES", $added_salary_fields)) {
                            $new_salary_array[] = array("atendance_fk" => $insert, "salary_field" => "Leaves", "value" => $leave_cut_amount, "type" => 2, "earning_type" => 2);
                        }
                        if (!in_array("OT", $added_salary_fields)) {
                            $new_salary_array[] = array("atendance_fk" => $insert, "salary_field" => "OT", "value" => $ot_amount, "type" => 3, "earning_type" => 1);
                        }
                        if (!in_array("PETROL", $added_salary_fields)) {
                            $new_salary_array[] = array("atendance_fk" => $insert, "salary_field" => "Petrol", "value" => $data1["petrol"], "type" => 4, "earning_type" => 1);
                        }
                        if (!in_array("INSENTIVE", $added_salary_fields)) {
                            $new_salary_array[] = array("atendance_fk" => $insert, "salary_field" => "Insentive", "value" => $incentive, "type" => 5, "earning_type" => 1);
                        }
                        if (!in_array("SUNDAY WAGES", $added_salary_fields)) {
                            $new_salary_array[] = array("atendance_fk" => $insert, "salary_field" => "Sunday Wages", "value" => $sw_amount, "type" => 6, "earning_type" => 1);
                        }
                        if (!in_array("FESTIVAL ALLOWANCE", $added_salary_fields)) {
                            $new_salary_array[] = array("atendance_fk" => $insert, "salary_field" => "Festival Allowance", "value" => $fstvl, "type" => 7, "earning_type" => 1);
                        }
                        if (!in_array("OTHERS EARNING", $added_salary_fields)) {
                            $new_salary_array[] = array("atendance_fk" => $insert, "salary_field" => "Others Earning", "value" => $data1["others"], "type" => 8, "earning_type" => 1);
                        }
                        if (!in_array("OTHERS DEDUCTION", $added_salary_fields)) {
                            $new_salary_array[] = array("atendance_fk" => $insert, "salary_field" => "Others Deduction", "value" => $Other_Deduction, "type" => 1, "earning_type" => 2);
                        }
                        if (!in_array("SALARY (CTC) / PM", $added_salary_fields)) {
                            $new_salary_array[] = array("atendance_fk" => $insert, "salary_field" => "SALARY (CTC) / PM", "value" => $ctc, "type" => 1, "earning_type" => 1);
                        }
                        if (!in_array("NET TAKE HOME", $added_salary_fields)) {
                            $new_salary_array[] = array("atendance_fk" => $insert, "salary_field" => "Net Take Home", "value" => $nth, "type" => 1, "earning_type" => 1);
                        }

//                        echo "<pre>";print_r($new_salary_array);die();
//                        exit;

                        $this->leave_model->update("hrm_month_atendance_data", array("atendance_fk" => $insert, "status" => 1), array("status" => "0"));
                        $added_salary = array();
                        //$new_salary_array
                        foreach ($new_salary_array as $skey) {
                            if (!in_array(trim(strtoupper($skey["salary_field"])), $added_salary)) {
                                $insert = $this->leave_model->insert("hrm_month_atendance_data", $skey);
                                $added_salary[] = trim(strtoupper($skey["salary_field"]));
                            }
                        }
                        /* END */
                    }
                    $this->session->set_flashdata("success", "Data successfully uploaded.");
                }
            }
        }

        /* Nishit code end */
        $final_array = array();
        if ($_GET['month'] != "") {
            $emp = $_GET['employee'];
            $month = $_GET['month'];
            $year = $_GET['current_year'];
            $city = $_GET['city'];
            $data['emp'] = $emp;
            $data['month'] = $month;
            $data['current_year'] = $year;
//            if (!empty($data['emp'])) {
//                $data['query'] = $this->employee_model->get_all("hrm_employees", array("id" => $emp, 'status' => "1"));
//            } else {
//                $data['query'] = $this->employee_model->get_all("hrm_employees", array("city" => $city, "status" => "1"));
//            }

            if (!empty($data['emp'])) {
                $data['query'] = $this->employee_model->get_all("hrm_employees", array("id" => $emp, 'status' => "1"));
            } else if (!empty($city)) {
                $data['query'] = $this->employee_model->get_all("hrm_employees", array("city" => $city, "status" => "1"));
            } else {
                $data['query'] = $this->employee_model->get_all("hrm_employees", array("status" => "1"));
            }


            $current_month = date("m");
            if ($month == $current_month) {
                $today = date("d");
            } else {
                $today = cal_days_in_month(CAL_GREGORIAN, $month, date("Y"));
            }
            $data['today'] = $today;



            foreach ($data['query'] as $ekey) {
                $all_record = $this->leave_model->get_all("hrm_month_atendance", array("employee_fk" => $ekey->id, "year" => $year, "month" => $month, "status" => "1"));
                $salary_all_record = $this->leave_model->get_all("hrm_month_atendance_data", array("atendance_fk" => $all_record[0]->id, "status" => "1"));
                $all_record[0]->details = $salary_all_record;
                $ekey->salary_data = $all_record;
                $final_array[] = $ekey;
            }
        }
        $data["final_array"] = $final_array;
        //echo "<pre>"; print_r($final_array);die();
        $data['employee'] = $this->user_model->master_fun_get_tbl_val("hrm_employees", array('status' => 1), array("id", "desc"));
        $data['leave_reason'] = $this->user_model->master_fun_get_tbl_val("hrm_leave_reason", array('status' => 1), array("id", "asc"));
        $data['test_cityes'] = $this->user_model->master_fun_get_tbl_val("test_cities", array('status' => 1), array("id", "asc"));
        $data["common_salary_structure"] = $this->leave_model->get_val("SELECT DISTINCT(hrm_master_salary_structure_details.salary_name) AS salary_name FROM `hrm_master_salary_structure_details` INNER JOIN `hrm_master_salary_structure` ON `hrm_master_salary_structure`.`id`=hrm_master_salary_structure_details.`salary_strucure_id` WHERE hrm_master_salary_structure_details.`status`='1' AND `hrm_master_salary_structure`.`status`='1' order by `order` asc");
        $this->load->view('hrm/header');
        $this->load->view('hrm/nav', $data);
        $this->load->view('hrm/leave_list', $data);
        $this->load->view('hrm/footer');
    }

//    function leave_list_old_chekbox() {
//        if (!is_hrmlogin()) {
//            redirect('login');
//        }
//        $data["login_data"] = is_hrmlogin();
//        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
//        $data['success'] = $this->session->flashdata("success");
//
//        if ($_GET['employee'] != "" && $_GET['month'] != "") {
////            echo "<pre>";
////            print_r($_GET); exit;
//            $emp = $_GET['employee'];
//            $month = $_GET['month'];
//            $year = $_GET['current_year'];
//            $data['emp'] = $emp;
//            $data['month'] = $month;
//
//            $data['query'] = $this->employee_model->get_one("hrm_employees", array("id" => $emp));
//            $current_month = date("m");
//            if ($month == $current_month) {
//                $today = date("d");
//            } else {
//                $today = cal_days_in_month(CAL_GREGORIAN, $month, date("Y"));
//            }
//            $data['today'] = $today;
//
//            for ($i = 1; $i <= $today; $i++) {
//                if (isset($_GET["p" . $i]) && $_GET["p" . $i] != "") {
//                    
//                    $all_record = $this->leave_model->get_all("hrm_employee_attendance", array("att_date" => $i, "att_year" => $year, "att_month" => $month, "employee_fk" => $emp));
//
//                    if (count($all_record) > 0) {
//                        $update = $this->leave_model->update("hrm_employee_attendance", array("id" => $all_record[0]->id), array("present_absent" => $_GET["p" . $i], "leave_reason_fk" => $_GET["leave_type" . $i]));
//                    } else {
//                        $data1 = [
//                            'employee_fk' => $emp,
//                            'att_date' => $i,
//                            'att_month' => $month,
//                            'att_year' => $year,
//                            'present_absent' => $_GET["p" . $i],
//                            'leave_reason_fk' => $_GET["leave_type" . $i],
//                            'status' => 1,
//                        ];
//                        $insert = $this->leave_model->insert("hrm_employee_attendance", $data1);
//                    }
//                }
//            }
//            $data['all_record1'] = $this->leave_model->get_all("hrm_employee_attendance", array("att_year" => $year, "att_month" => $month, "employee_fk" => $emp));
//        }
//
//        $data['employee'] = $this->user_model->master_fun_get_tbl_val("hrm_employees", array('status' => 1), array("id", "desc"));
//        $data['leave_reason'] = $this->user_model->master_fun_get_tbl_val("hrm_leave_reason", array('status' => 1), array("id", "asc"));
//
//        $this->load->view('hrm/header');
//        $this->load->view('hrm/nav', $data);
//        $this->load->view('hrm/leave_list', $data);
//        $this->load->view('hrm/footer');
//    }
    function upload_sheet() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('year', 'Date', 'trim|required');
        $this->form_validation->set_rules('month', 'Date', 'trim|required');
        $this->form_validation->set_rules('city', 'City', 'trim|required');
        $this->form_validation->set_rules('userfile', 'CSV', 'callback__check_valid_userfile');
        if ($this->form_validation->run() != FALSE) {
            $uploaddata = $this->uploadData();
            echo "<pre>";
            print_r($uploaddata["data"]);
            die("-----OK");
        } else {
            //redirect("hrm/leave/leave_list?employee=&month=1&current_year=2018&city=2");
            redirect("hrm/leave/leave_list");
        }
    }

    function _check_valid_userfile($str) {
        $uploaddata = $this->uploadData();
        if (!empty($uploaddata["error"])) {
            $this->form_validation->set_message('_check_valid_userfile', 'Invalid uploaded CSV file formate.');
            return false;
        } else {
            return TRUE;
        }
    }

    function uploadData() {
        $error = '';
        $count = 0;
        $fp = fopen($_FILES['userfile']['tmp_name'], 'r') or die("can't open file");
        while ($csv_line = fgetcsv($fp, 1024)) {
            $count++;
            if ($count == 1) {
                if (
                        'Sr. No.' == $csv_line[0] &&
                        'Employee Code' == $csv_line[1] &&
                        'Name of Employee' == $csv_line[2] &&
                        'No. of Leaves' == $csv_line[3] &&
                        'Over Time' == $csv_line[4] &&
                        'Petrol' == $csv_line[5] &&
                        'Incentive' == $csv_line[6] &&
                        'Sunday Wages' == $csv_line[7] &&
                        'Festival Allowance' == $csv_line[8] &&
                        'Others Earning' == $csv_line[9] &&
                        'TDS' == $csv_line[10] &&
                        'MAINTENACE COST' == $csv_line[11] &&
                        'Working Hours' == $csv_line[12] &&
                        'Other Deduction' == $csv_line[13]
                ) {

                    continue;
                } else {
                    $error = "File is not in proper formate.";
                    break;
                }
            }//keep this if condition if you want to remove the first row
            for ($i = 0, $j = count($csv_line) / 10; $i < $j; $i++) {
                $data[] = array(
                    'Sr. No.' => $csv_line[0],
                    'Employee Code' => $csv_line[1],
                    'Name of Employee' => $csv_line[2],
                    'No. of Leaves' => $csv_line[3],
                    'Over Time' => $csv_line[4],
                    'Petrol' => $csv_line[5],
                    'Incentive' => $csv_line[6],
                    'Sunday Wages' => $csv_line[7],
                    'Festival Allowance' => $csv_line[8],
                    'Others Earning' => $csv_line[9],
                    'TDS' => $csv_line[10],
                    'MAINTENACE COST' => $csv_line[11],
                    'Working Hours' => $csv_line[12],
                    'Other Deduction' => $csv_line[13]
                );
            }
            $i++;
            //$data['crane_features']=$this->db->insert('tableName', $data);
        }
        fclose($fp) or die("can't close file");
        return array("error" => $error, "data" => $data);
    }

    /*     function uploadData() {
      $error = '';
      $count = 0;
      $fp = fopen($_FILES['userfile']['tmp_name'], 'r') or die("can't open file");
      while ($csv_line = fgetcsv($fp, 1024)) {
      $count++;
      if ($count == 1) {
      if (
      'Date' == $csv_line[0] &&
      'InTime' == $csv_line[1] &&
      'OutTime' == $csv_line[2] &&
      'Shift' == $csv_line[3] &&
      'S. InTime' == $csv_line[4] &&
      'S. OutTime' == $csv_line[5] &&
      'Work Dur.' == $csv_line[6] &&
      'OT' == $csv_line[7] &&
      'Tot. Dur.' == $csv_line[8] &&
      'P/A' == $csv_line[9] &&
      'Leave Type' == $csv_line[10] &&
      'Employee Code' == $csv_line[11] &&
      'Employee Name' == $csv_line[12]
      ) {
      continue;
      } else {
      $error = "File is not in proper formate.";
      break;
      }
      }//keep this if condition if you want to remove the first row
      for ($i = 0, $j = count($csv_line); $i < $j; $i++) {
      $data[] = array(
      'Date' => $csv_line[0],
      'InTime' => $csv_line[1],
      'OutTime' => $csv_line[2],
      'Shift' => $csv_line[3],
      'S. InTime' => $csv_line[4],
      'S. OutTime' => $csv_line[5],
      'Work Dur.' => $csv_line[6],
      'OT' => $csv_line[7],
      'Tot. Dur.' => $csv_line[8],
      'P/A' => $csv_line[9],
      'Leave Type' => $csv_line[10],
      'Employee Code' => $csv_line[11],
      'Employee Name' => $csv_line[12]
      );
      }
      $i++;
      //$data['crane_features']=$this->db->insert('tableName', $data);
      }
      fclose($fp) or die("can't close file");
      return array("error" => $error, "data" => $data);
      } */

    function export_salary_sheet() {
        $final_array = array();
        if ($_GET['month'] != "" && $_GET['city'] != "") {
            $emp = $_GET['employee'];
            $month = $_GET['month'];
            $year = $_GET['current_year'];
            $city = $_GET['city'];
            $data['emp'] = $emp;
            $data['month'] = $month;
            if (!empty($data['emp'])) {
                $data['query'] = $this->employee_model->get_all("hrm_employees", array("id" => $emp, 'status' => "1"));
            } else {
                $data['query'] = $this->employee_model->get_all("hrm_employees", array("city" => $city, "status" => "1"));
            }
            $current_month = date("m");
            if ($month == $current_month) {
                $today = date("d");
            } else {
                $today = cal_days_in_month(CAL_GREGORIAN, $month, date("Y"));
            }
            $data['today'] = $today;



            foreach ($data['query'] as $ekey) {
                $all_record = $this->leave_model->get_all("hrm_month_atendance", array("employee_fk" => $ekey->id, "year" => $year, "month" => $month, "status" => "1"));
                $salary_all_record = $this->leave_model->get_all("hrm_month_atendance_data", array("atendance_fk" => $all_record[0]->id, "status" => "1"));
                $all_record[0]->details = $salary_all_record;
                $ekey->salary_data = $all_record;
                $final_array[] = $ekey;
            }

            //echo "<pre>"; print_r($final_array);die();
            $data['employee'] = $this->user_model->master_fun_get_tbl_val("hrm_employees", array('status' => 1), array("id", "desc"));
            $data['leave_reason'] = $this->user_model->master_fun_get_tbl_val("hrm_leave_reason", array('status' => 1), array("id", "asc"));
            $data['test_cityes'] = $this->user_model->master_fun_get_tbl_val("test_cities", array('status' => 1), array("id", "asc"));
            $common_salary_structure = $this->leave_model->get_val("SELECT DISTINCT(hrm_master_salary_structure_details.salary_name) AS salary_name FROM `hrm_master_salary_structure_details` INNER JOIN `hrm_master_salary_structure` ON `hrm_master_salary_structure`.`id`=hrm_master_salary_structure_details.`salary_strucure_id` WHERE hrm_master_salary_structure_details.`status`='1' AND `hrm_master_salary_structure`.`status`='1' order by `order` asc");



            $common_salary_structure[] = array("salary_name" => "Leaves");
            $common_salary_structure[] = array("salary_name" => "OT");
            $common_salary_structure[] = array("salary_name" => "Petrol");
            $common_salary_structure[] = array("salary_name" => "Insentive");
            $common_salary_structure[] = array("salary_name" => "Sunday Wages");
            $common_salary_structure[] = array("salary_name" => "Festival Allowance");
            $common_salary_structure[] = array("salary_name" => "Others Earning");

            //echo "<pre>"; print_r($common_salary_structure);die();
            $new_common_salary_structure = array();
            $new_common_salary_structure[] = array("salary_name" => "Basic");
            $new_common_salary_structure[] = array("salary_name" => "DA");
//          $new_common_salary_structure[] = array("salary_name" => "HRA");
//            $new_common_salary_structure[] = array("salary_name" => "Conveyance Allowance");
//            $new_common_salary_structure[] = array("salary_name" => "Special Allowance");

            $new_common_salary_structure[] = array("salary_name" => "Other Allowance");
//          $new_common_salary_structure[] = array("salary_name" => "Special Allowance");
//            $new_common_salary_structure[] = array("salary_name" => "Medical Reimbursement");
            $new_common_salary_structure[] = array("salary_name" => "MAINTENACE COST");
            $new_common_salary_structure[] = array("salary_name" => "OT");
            $new_common_salary_structure[] = array("salary_name" => "Petrol");
            $new_common_salary_structure[] = array("salary_name" => "Insentive");
            $new_common_salary_structure[] = array("salary_name" => "Sunday Wages");
            $new_common_salary_structure[] = array("salary_name" => "Festival Allowance");
            $new_common_salary_structure[] = array("salary_name" => "Others Earning");
//            $new_common_salary_structure[] = array("salary_name" => "Other Reimbursement");
            $new_common_salary_structure[] = array("salary_name" => "PF-Employee's Contribution");
            $new_common_salary_structure[] = array("salary_name" => "ESIC-Employee's Contribution");
            $new_common_salary_structure[] = array("salary_name" => "PF-Employer's Contribution");
            $new_common_salary_structure[] = array("salary_name" => "ESIC-Employer's Contribution");
            $new_common_salary_structure[] = array("salary_name" => "TDS");
            $new_common_salary_structure[] = array("salary_name" => "Leaves");
            $new_common_salary_structure[] = array("salary_name" => "PT");
            $new_common_salary_structure[] = array("salary_name" => "Others Deduction");
            $new_common_salary_structure[] = array("salary_name" => "Present Day Basic Salary");
            $new_common_salary_structure[] = array("salary_name" => "SALARY (CTC) / PM");
            $new_common_salary_structure[] = array("salary_name" => "Net Take Home");


            $new_csv_array = array();
            $cnt = 0;
            foreach ($final_array as $fkey) {
                $kname = array();
                if ($cnt == 0) {
                    $kname = array("Name", "Employee Id");
                    foreach ($new_common_salary_structure as $csskey) {
                        $kname[] = $csskey["salary_name"];
                    }
                    $new_csv_array[] = $kname;
                }
                $kname = array();
                $kname[] = ucfirst($fkey->name);
                $kname[] = ucfirst($fkey->employee_id);

                if (!empty($fkey->salary_data)) {
                    foreach ($new_common_salary_structure as $csskey) {
                        $phtml = 'NA';
                        foreach ($fkey->salary_data[0]->details as $eskey) {
                            $type = "Earning";
                            if ($eskey->earning_type == 2) {
                                $type = "Deduction";
                            }
                            if (strtoupper($csskey["salary_name"]) == strtoupper($eskey->salary_field)) {
                                $phtml = ($eskey->value > 0) ? $eskey->value : 0;
                            }
                        }
                        $kname[] = $phtml;
                    }
                    $new_csv_array[] = $kname;
                }
                $kname[] = ucfirst($phtml);

                $cnt++;
            }
            /* CSV START */
            header("Content-type: application/csv");
            header("Content-Disposition: attachment; filename=\"Salary_sheet-" . date('d-M-Y') . ".csv\"");
            header("Pragma: no-cache");
            header("Expires: 0");
            $handle = fopen('php://output', 'w');
            $cnt = 1;
            foreach ($new_csv_array as $key) {
                fputcsv($handle, $key);
                $cnt++;
            }
            fclose($handle);
            exit;
            /* END */
        } else {
            redirect("hrm/leave/leave_list");
        }
    }

    function pdf_salary_slip() {
        
        $emp = $_POST['emp_id'];
        $month = $_POST['month_id'];
        $current_year = $_POST['year_id'];
        $new_array1 = array();
        $with_without_letter = $_POST['letter_head'];
        if ($emp != "" && $month != "") {
            $today = cal_days_in_month(CAL_GREGORIAN, $month, date("Y"));
            $data['today'] = $today;
            $data['emp'] = $emp;
            $data['month'] = $month;
            $data['current_year'] = $current_year;
            
            $get_employee_list = "SELECT *,he.name AS emp_name,dt.name AS department_name,ds.name AS designation_name from hrm_month_atendance ha 
                LEFT JOIN hrm_employees he ON ha.employee_fk = he.id 
                LEFT JOIN hrm_department dt ON dt.id = he.department 
                LEFT JOIN hrm_designation ds ON ds.id = he.designation 
                WHERE ha.status = 1 AND ha.month = $month AND ha.year = $current_year AND ha.employee_fk='" . $emp . "'";
            //echo $get_employee_list; exit;

            $attendance = $this->leave_model->get_val($get_employee_list);


            $j = 0;

            foreach ($attendance as $at) {

                $emp_id2 = $at['employee_fk'];
                /* $q = "select hd.atendance_fk,hd.salary_field,hd.value,hd.type,hd.earning_type,hd.status from hrm_month_atendance ha 
                  LEFT JOIN  hrm_month_atendance_data hd
                  on hd.atendance_fk = ha.id
                  WHERE hd.status = 1 AND ha.employee_fk = $emp_id2"; */
                $q = "SELECT 
  hd.atendance_fk,
  hd.salary_field,
  hd.value,
  hd.type,
  hd.earning_type,
  hd.status 
FROM
  hrm_month_atendance ha 
  LEFT JOIN hrm_month_atendance_data hd 
    ON hd.atendance_fk = ha.id 
WHERE hd.status = 1 
  AND ha.`month`='" . $month . "'
  AND ha.`year`='" . $current_year . "' AND ha.`employee_fk`='" . $emp . "'";

                $at['attendance_data'] = $this->employee_model->get_val($q);
                $new_array1[] = $at;
                $j++;
            }
            //echo "<pre>"; print_r($at['attendance_data']);die("OK");
        }
        $data["new_array1"] = $new_array1;

//        echo "<pre>"; print_r($new_array1); exit;

        $new_common_salary_structure = array();
        $new_common_salary_structure[] = array("salary_name" => "Basic");
        $new_common_salary_structure[] = array("salary_name" => "DA");
//        $new_common_salary_structure[] = array("salary_name" => "HRA");
//        $new_common_salary_structure[] = array("salary_name" => "Conveyance Allowance");
//        $new_common_salary_structure[] = array("salary_name" => "Special Allowance");
//        $new_common_salary_structure[] = array("salary_name" => "Medical Reimbursement");
//        $new_common_salary_structure[] = array("salary_name" => "MAINTENACE COST");
//        $new_common_salary_structure[] = array("salary_name" => "OT");
//        $new_common_salary_structure[] = array("salary_name" => "Petrol");
        $new_common_salary_structure[] = array("salary_name" => "Insentive");
//        $new_common_salary_structure[] = array("salary_name" => "Sunday Wages");
//        $new_common_salary_structure[] = array("salary_name" => "Festival Allowance");
        $new_common_salary_structure[] = array("salary_name" => "Other Allowance");
        $new_common_salary_structure[] = array("salary_name" => "PF-Employee's Contribution");
        $new_common_salary_structure[] = array("salary_name" => "ESIC-Employee's Contribution");
        $new_common_salary_structure[] = array("salary_name" => "TDS");
        $new_common_salary_structure[] = array("salary_name" => "Leaves");
        $new_common_salary_structure[] = array("salary_name" => "PT");
        $new_common_salary_structure[] = array("salary_name" => "Other Deductions");

        $new_common_salary_structure[] = array("salary_name" => "Other Reimbursement");
        $new_common_salary_structure[] = array("salary_name" => "PF-Employer's Contribution");
        $new_common_salary_structure[] = array("salary_name" => "ESIC-Employer's Contribution");

        $new_common_salary_structure[] = array("salary_name" => "SALARY (CTC) / PM");
        /* 	$new_common_salary_structure[] = array("salary_name"=>"Present Day Basic Salary"); */
        $new_common_salary_structure[] = array("salary_name" => "Net Take Home");
        $data['new_common_salary_structure'] = $new_common_salary_structure;
        $date = date("_Y-m-d_H:i:s");

        $path = FCPATH . 'upload/employee/';
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        $pdfFilePath = $path . "salary_slip_pdf_leave" . $date . ".pdf";

        //$data->page_title = 'AirmedLabs';

        ini_set('memory_limit', '128M');
        $html = $this->load->view('hrm/salary_slip_pdf_leave', $data, true);

        if (file_exists($pdfFilePath)) {
            $this->delete_downloadfile($pdfFilePath);
        }

        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->autoScriptToLang = true;
        $pdf->baseScript = 1; // Use values in classes/ucdn.php  1 = LATIN
        $pdf->autoVietnamese = true;
        $pdf->autoArabic = true;
        $pdf->autoLangToFont = true;

        if ($with_without_letter == 1) {
            $pdf->SetHTMLHeader('<body>
                <div class="pdf_container">
            <div class="main_set_pdng_div">
                <div class="brdr_full_div">
                    <div class="header_full_div">
                        <img class="set_logo" src="logo.png" style="margin-top:15px;"/>
                    </div>');
        }

        $pdf->AddPage('p', // L - landscape, P - portrait
                '', '', '', '', 5, // margin_left
                5, // margin right
                30, // margin top
                30, // margin bottom
                2, // margin header
                2); // margin footer

        if ($with_without_letter == 1) {
            $pdf->SetHTMLFooter('<div class="foot_num_div" style="margin-bottom:0;padding-bottom:0">
		<p class="foot_num_p" style="margin-bottom:2;padding-bottom:0"><img class="set_sign" src="pdf_phn_btn.png" style="width:"/></p>
		<p class="foot_lab_p" style="font-size:13px;margin-bottom:2;padding-bottom:0">LAB AT YOUR DOORSTEP</p>
	</div>
		<p class="lst_airmed_mdl" style="font-size:13px;margin-top:5px">Airmed Pathology Pvt. Ltd.</p>
		<p class="lst_31_addrs_mdl" style="font-size:12px"><span style="color:#9D0902;">Commercial Address : </span>31, Ambika Society, Next to Nabard Bank, Opp. Usmanpura, Ahmedabad, Gujarat - 380 013.</p>
		<p class="lst_31_addrs_mdl"><b><img src="email-icon.png" style="margin-bottom:-3px;width:13px"/> info@airmedlabs.com  <img src="web-icon.png" style="margin-bottom:-3px;width:13px"/> www.airmedlabs.com</b></p><p class="lst_31_addrs_mdl"><!--<img src="lastimg.png" style="margin-top:3px;"/>--> </p></div>
        </body>
</html>');
        }

        $pdf->WriteHTML($html);


//        $pdf->debug = true;
//        $pdf->allow_output_buffering = TRUE;
//        if (file_exists($pdfFilePath) == true) {
//            $this->load->helper('file');
//            unlink($path);
//        }
        $pdf->Output($pdfFilePath, 'F'); // save to file because we can
        $downld = $this->_push_file($pdfFilePath, "salary_slip" . $date . ".pdf");
        $this->session->set_flashdata("success", "Salary Slip has downloaded successfully.");
        redirect($pdfFilePath);
        //redirect("/upload/b2binvoice/" . $data['job_details'][0]['order_id'] . "customerinvoice.pdf?" . time());
    }

    function _push_file($path, $name) {
        // make sure it's a file before doing anything!
        if (is_file($path)) {
            // required for IE
            if (ini_get('zlib.output_compression')) {
                ini_set('zlib.output_compression', 'Off');
            }
            // get the file mime type using the file extension
            $this->load->helper('file');

            $mime = get_mime_by_extension($path);

            // Build the headers to push out the file properly.
            header('Pragma: public');     // required
            header('Expires: 0');         // no cache
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($path)) . ' GMT');
            header('Cache-Control: private', false);
            header('Content-Type: ' . $mime);  // Add the mime type from Code igniter.
            header('Content-Disposition: attachment; filename="' . basename($name) . '"');  // Add the file name
            header('Content-Transfer-Encoding: binary');
            header('Content-Length: ' . filesize($path)); // provide file size
            header('Connection: close');
            readfile($path); // push it out
        }
    }

}

?>
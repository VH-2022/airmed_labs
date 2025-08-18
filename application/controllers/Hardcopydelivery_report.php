<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Hardcopydelivery_report extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('report_model');
        
        $this->load->library('email');
        $this->load->helper('string');
        $this->load->library('pagination');
        //ini_set('display_errors', 'On');
    }

    function index() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();

        $handover_start_date = $this->input->get('handover_start_date');
        $handover_end_date = $this->input->get('handover_end_date');
        $delivery_start_date = $this->input->get('delivery_start_date');
        $delivery_end_date = $this->input->get('delivery_end_date');
        $phlebo = $this->input->get("phlebo");
        $type = $this->input->get("type");

        if ($type == "") {
            $type = "3";
        }

        $data['handover_start_date'] = $handover_start_date;
        $data['handover_end_date'] = $handover_end_date;
        $data['delivery_start_date'] = $delivery_start_date;
        $data['delivery_end_date'] = $delivery_end_date;
        $data['phlebo'] = $phlebo;
        $data['type'] = $type;

        $temp = "";
        if ($handover_start_date != "") {
            if ($type == "1") {
                $temp .=" AND hrt.doctor_date >= '$handover_start_date' AND hrt.doctor_date !='0000-00-00'";
            } else if ($type == "2") {
                $temp .=" AND hrt.patient_date >= '$handover_start_date' AND hrt.patient_date !='0000-00-00'";
            } else {
                $temp .=" AND (hrt.doctor_date >= '$handover_start_date' AND hrt.doctor_date !='0000-00-00' OR hrt.patient_date >= '$handover_start_date 00:00:00' AND hrt.patient_date !='0000-00-00')";
            }
        }
        if ($handover_end_date != "") {
            if ($type == "1") {
                $temp .=" AND hrt.doctor_date <= '$handover_end_date' AND hrt.doctor_date !='0000-00-00'";
            } else if ($type == "2") {
                $temp .=" AND hrt.patient_date <= '$handover_end_date' AND hrt.patient_date !='0000-00-00'";
            } else {
                $temp .=" AND (hrt.doctor_date <= '$handover_end_date' AND hrt.doctor_date !='0000-00-00' OR hrt.patient_date <= '$handover_end_date 23:23:59' AND hrt.patient_date!='0000-00-00')";
            }
        }
        if ($delivery_start_date != "") {
            if ($type == "1") {
                $temp .=" AND hrt.doc_delivery_timestamp >= '$delivery_start_date 00:00:00' AND hrt.doc_delivery_timestamp !='0000-00-00'";
            } else if ($type == "2") {
                $temp .=" AND hrt.patient_delivery_timestamp >= '$delivery_start_date 00:00:00' AND hrt.patient_delivery_timestamp !='0000-00-00'";
            } else {
                $temp .=" AND (hrt.doc_delivery_timestamp >= '$delivery_start_date 00:00:00' OR hrt.patient_delivery_timestamp >= '$delivery_start_date 00:00:00')";
            }
        }
        if ($delivery_end_date != "") {
            if ($type == "1") {
                $temp .=" AND hrt.doc_delivery_timestamp <= '$delivery_end_date 23:23:59' AND hrt.doc_delivery_timestamp !='0000-00-00'";
            } else if ($type == "2") {
                $temp .=" AND hrt.patient_delivery_timestamp <= '$delivery_end_date 23:23:59' AND hrt.patient_delivery_timestamp !='0000-00-00'";
            } else {
                $temp .=" AND (hrt.doc_delivery_timestamp <= '$delivery_end_date 23:23:59'  OR hrt.patient_delivery_timestamp <= '$delivery_end_date 23:23:59')";
            }
        }
        if ($phlebo != "") {
            if ($type == "1") {
                $temp .=" AND hrt.doctor_phlebo= '$phlebo'";
            } else if ($type == "2") {
                $temp .=" AND hrt.patient_phlebo= '$phlebo'";
            } else {
                $temp .=" AND (hrt.doctor_phlebo= '$phlebo' OR hrt.patient_phlebo= '$phlebo')";
            }
        }

        $query = "select hrt.id,jm.id,hrt.doctor_status,hrt.doctor_phlebo,hrt.doctor_date,hrt.doctor_time,hrt.patient_status, 
            hrt.patient_phlebo,hrt.patient_date,hrt.patient_time,cm.full_name,hrt.doc_delivery_timestamp,hrt.patient_delivery_timestamp 
            from hard_report_track hrt 
                INNER JOIN job_master jm on jm.id = hrt.job_fk 
                INNER JOIN customer_master cm on cm.id = jm.cust_fk AND cm.status='1' 
                INNER JOIN phlebo_master pm on pm.id = hrt.doctor_phlebo AND pm.status='1' 
                where jm.status!=0 $temp order by hrt.id desc";
        
        $data['query'] = $this->report_model->get_val($query);
        
        $totalRows = count($data['query']);
        $config = array();
        $config['page_query_string'] = TRUE;
        $config['reuse_query_string'] = true;
        $config["base_url"] = base_url() . "Hardcopydelivery_report/index?" . http_build_query($get);
        $get = $_GET;
        unset($get['offset']);
        $config["total_rows"] = $totalRows;
        $config["per_page"] = 50;
        $config['cur_tag_open'] = '<span>';
        $config['cur_tag_close'] = '</span>';
        $config['next_link'] = 'Next &rsaquo;';
        $config['prev_link'] = '&lsaquo; Previous';
        $this->pagination->initialize($config);
        $sort = $this->input->get("sort");
        $by = $this->input->get("by");
        $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
        
        $query.= " limit $page," . $config['per_page'];
        
        $data['query'] = $this->report_model->get_val($query);
        $data["links"] = $this->pagination->create_links();
        $data["pages"] = $page;
        
        $i = 0;
        foreach ($data['query'] as $key) {

            $data['query'][$i]['doctorPhlebo'] = $this->report_model->get_val("select name from phlebo_master where status='1' AND id='" . $key['doctor_phlebo'] . "'")[0]['name'];
            $data['query'][$i]['patientPhlebo'] = $this->report_model->get_val("select name from phlebo_master where status='1' AND id='" . $key['patient_phlebo'] . "'")[0]['name'];
            $i++;
        }
        
        $data['phlebo_list'] = $this->report_model->get_val("select id,name from phlebo_master where status='1'");
        $this->load->view('header', $data);
        $this->load->view('nav', $data);
        $this->load->view('hardcopydelivery_report_view', $data);
        $this->load->view('footer');
    }

    function export_csv() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $handover_start_date = $this->input->get('handover_start_date');
        $handover_end_date = $this->input->get('handover_end_date');
        $delivery_start_date = $this->input->get('delivery_start_date');
        $delivery_end_date = $this->input->get('delivery_end_date');
        $phlebo = $this->input->get("phlebo");
        $type = $this->input->get("type");

        if ($type == "") {
            $type = "3";
        }

        $data['handover_start_date'] = $handover_start_date;
        $data['handover_end_date'] = $handover_end_date;
        $data['delivery_start_date'] = $delivery_start_date;
        $data['delivery_end_date'] = $delivery_end_date;
        $data['phlebo'] = $phlebo;
        $data['type'] = $type;
        
        $temp = "";
        if ($handover_start_date != "") {
            if ($type == "1") {
                $temp .=" AND hrt.doctor_date >= '$handover_start_date' AND hrt.doctor_date !='0000-00-00'";
            } else if ($type == "2") {
                $temp .=" AND hrt.patient_date >= '$handover_start_date' AND hrt.patient_date !='0000-00-00'";
            } else {
                $temp .=" AND (hrt.doctor_date >= '$handover_start_date' AND hrt.doctor_date !='0000-00-00' OR hrt.patient_date >= '$handover_start_date 00:00:00' AND hrt.patient_date !='0000-00-00')";
            }
        }
        if ($handover_end_date != "") {
            if ($type == "1") {
                $temp .=" AND hrt.doctor_date <= '$handover_end_date' AND hrt.doctor_date !='0000-00-00'";
            } else if ($type == "2") {
                $temp .=" AND hrt.patient_date <= '$handover_end_date' AND hrt.patient_date !='0000-00-00'";
            } else {
                $temp .=" AND (hrt.doctor_date <= '$handover_end_date' AND hrt.doctor_date !='0000-00-00' OR hrt.patient_date <= '$handover_end_date 23:23:59' AND hrt.patient_date!='0000-00-00')";
            }
        }
        if ($delivery_start_date != "") {
            if ($type == "1") {
                $temp .=" AND hrt.doc_delivery_timestamp >= '$delivery_start_date 00:00:00' AND hrt.doc_delivery_timestamp !='0000-00-00'";
            } else if ($type == "2") {
                $temp .=" AND hrt.patient_delivery_timestamp >= '$delivery_start_date 00:00:00' AND hrt.patient_delivery_timestamp !='0000-00-00'";
            } else {
                $temp .=" AND (hrt.doc_delivery_timestamp >= '$delivery_start_date 00:00:00' OR hrt.patient_delivery_timestamp >= '$delivery_start_date 00:00:00')";
            }
        }
        if ($delivery_end_date != "") {
            if ($type == "1") {
                $temp .=" AND hrt.doc_delivery_timestamp <= '$delivery_end_date 23:23:59' AND hrt.doc_delivery_timestamp !='0000-00-00'";
            } else if ($type == "2") {
                $temp .=" AND hrt.patient_delivery_timestamp <= '$delivery_end_date 23:23:59' AND hrt.patient_delivery_timestamp !='0000-00-00'";
            } else {
                $temp .=" AND (hrt.doc_delivery_timestamp <= '$delivery_end_date 23:23:59'  OR hrt.patient_delivery_timestamp <= '$delivery_end_date 23:23:59')";
            }
        }
        if ($phlebo != "") {
            if ($type == "1") {
                $temp .=" AND hrt.doctor_phlebo= '$phlebo'";
            } else if ($type == "2") {
                $temp .=" AND hrt.patient_phlebo= '$phlebo'";
            } else {
                $temp .=" AND (hrt.doctor_phlebo= '$phlebo' OR hrt.patient_phlebo= '$phlebo')";
            }
        }

        $query = "select hrt.id,jm.id,hrt.doctor_status,hrt.doctor_phlebo,hrt.doctor_date,hrt.doctor_time,hrt.patient_status, 
            hrt.patient_phlebo,hrt.patient_date,hrt.patient_time,cm.full_name,hrt.doc_delivery_timestamp,hrt.patient_delivery_timestamp 
            from hard_report_track hrt 
                INNER JOIN job_master jm on jm.id = hrt.job_fk 
                INNER JOIN customer_master cm on cm.id = jm.cust_fk AND cm.status='1' 
                INNER JOIN phlebo_master pm on pm.id = hrt.doctor_phlebo AND pm.status='1' 
                where jm.status!=0 $temp order by hrt.id desc";
        
        $data['query'] = $this->report_model->get_val($query);
        $i = 0;
        foreach ($data['query'] as $key) {
            
            $data['query'][$i]['doctorPhlebo'] = $this->report_model->get_val("select name from phlebo_master where status='1' AND id='" . $key['doctor_phlebo'] . "'")[0]['name'];
            $data['query'][$i]['patientPhlebo'] = $this->report_model->get_val("select name from phlebo_master where status='1' AND id='" . $key['patient_phlebo'] . "'")[0]['name'];
            $i++;
        }
        
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"Hard_copy_delivery_status_report.csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array("Sr.No.", "Type", "Job ID", "Customer Name", "Phlebo(Runner)", "Handover DateTime", "Delivery DateTime", "Status"));
        
        $count = 0;
        foreach ($data['query'] as $row) {
            
            if ($type == "3") {
                if ($row['doctor_status'] == "2") {
                    $doc_status = "Delivered";
                } else if ($row['doctor_status'] == "1") {
                    $doc_status = "Handover";
                } else {
                    $doc_status = "Pending";
                }
                
                fputcsv($handle, array(++$count, "Doctor", $row["id"],
                    ucwords($row["full_name"]), $row['doctorPhlebo'] ? ucwords($row['doctorPhlebo']) : '-',
                    $row['doctor_date'] ? $row['doctor_date'] . ' ' . $row['doctor_time'] : '-',
                    $row['doc_delivery_timestamp'] ? $row['doc_delivery_timestamp'] : '-', $doc_status));
                
                if ($row['patient_status'] == "2") {
                    $pat_status = "Delivered";
                } else if ($row['patient_status'] == "1") {
                    $pat_status = "Handover";
                } else {
                    $pat_status = "Pending";
                }

                fputcsv($handle, array(++$count, "Patient", $row["id"],
                    ucwords($row["full_name"]), $row['patientPhlebo'] ? ucwords($row['patientPhlebo']) : '-',
                    $row['patient_date'] ? $row['patient_date'] . ' ' . $row['patient_time'] : '-',
                    $row['patient_delivery_timestamp'] ? $row['patient_delivery_timestamp'] : '-', $pat_status));
            } else if ($type == "1") {
                if ($row['doctor_status'] == "2") {
                    $doc_status = "Delivered";
                } else if ($row['doctor_status'] == "1") {
                    $doc_status = "Handover";
                } else {
                    $doc_status = "Pending";
                }
                
                fputcsv($handle, array(++$count, "Doctor", $row["id"],
                    ucwords($row["full_name"]), $row['doctorPhlebo'] ? ucwords($row['doctorPhlebo']) : '-',
                    $row['doctor_date'] ? $row['doctor_date'] . ' ' . $row['doctor_time'] : '-',
                    $row['doc_delivery_timestamp'] ? $row['doc_delivery_timestamp'] : '-', $doc_status));
            } else {
                if ($row['patient_status'] == "2") {
                    $pat_status = "Delivered";
                } else if ($row['patient_status'] == "1") {
                    $pat_status = "Handover";
                } else {
                    $pat_status = "Pending";
                }

                fputcsv($handle, array(++$count, "Doctor", $row["id"],
                    ucwords($row["full_name"]), $row['patientPhlebo'] ? ucwords($row['patientPhlebo']) : '-',
                    $row['patient_date'] ? $row['patient_date'] . ' ' . $row['patient_time'] : '-',
                    $row['patient_delivery_timestamp'] ? $row['patient_delivery_timestamp'] : '-', $pat_status));
            }
        }
        fclose($handle);
        exit;
    }

}

?>
<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Doctor_sample_report extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('user_model');
        $this->load->model('Test_tat_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('pushserver');
        $this->load->library('email');
        $this->load->helper('string');
        //ini_set('display_errors', 1);
        $data["login_data"] = logindata();
    }

    function index() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data['user'] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");

        $data['start_date'] = $start_date = $this->input->get('start_date');
        $data['end_date'] = $end_date = $this->input->get('end_date');
        $data['city'] = $this->Test_tat_model->get_val("select * from test_cities where status='1'");

        $data['city_id'] = $city_id = $this->input->get('city_id');
        //echo $city_id; exit;
        $city_query = "";
        if ($city_id != "") {
            $city_id = $this->Test_tat_model->get_val("select city_fk from test_cities where id='$city_id'")[0]->city_fk;
            $city_query = " AND dm.city='$city_id'";
            $data['doctor'] = $this->Test_tat_model->get_val("select id,full_name from doctor_master where id IN(select doctor from job_master) AND status='1' AND city='$city_id'");
        } else {
            $data['doctor'] = $this->Test_tat_model->get_val("select id,full_name from doctor_master where id IN(select doctor from job_master) AND status='1' AND city='333'");
        }

        $data['doc'] = $doc = $this->input->get('doc_id');

        //echo "<pre>"; print_r($doc); exit;

        if ($start_date != '' && $end_date != '') {
            
            if (!empty($doc)) {
                $data['doc_list'] = $this->Test_tat_model->get_val("select dm.id,dm.full_name from doctor_master dm INNER JOIN job_master jm ON jm.doctor=dm.id 
                where dm.id IN(" . implode(',', $doc) . ") AND dm.status='1' $city_query AND jm.status!=0 AND jm.date>='$start_date 00:00:00' AND jm.date<='$end_date 23:23:59' GROUP BY dm.id ORDER BY jm.date ASC ");

                $query = $this->Test_tat_model->get_val("select dm.id,dm.full_name,jm.id AS job_id, 
                    count(jm.sample_collection) AS date_wise_total,DATE_FORMAT(jm.date, '%Y-%m-%d') AS job_date 
                    from doctor_master dm INNER JOIN job_master jm ON jm.doctor=dm.id 
                    where dm.id IN(" . implode(',', $doc) . ") AND dm.status='1' $city_query AND jm.status!=0 AND jm.date>='$start_date 00:00:00' 
                    AND jm.date<='$end_date 23:23:59' GROUP BY job_date ORDER BY jm.date ASC");

                $data['doc_wise_total'] = $this->Test_tat_model->get_val("select dm.id,dm.full_name,jm.id AS job_id, 
                    count(jm.sample_collection) AS total,DATE_FORMAT(jm.date, '%Y-%m-%d') AS job_date 
                    from doctor_master dm INNER JOIN job_master jm ON jm.doctor=dm.id 
                    where dm.id IN(" . implode(',', $doc) . ") AND dm.status='1' $city_query AND jm.status!=0 AND jm.date>='$start_date 00:00:00' 
                    AND jm.date<='$end_date 23:23:59' GROUP BY dm.id ORDER BY jm.date ASC");
            } else {
                $data['doc_list'] = $this->Test_tat_model->get_val("select dm.id,dm.full_name from doctor_master dm INNER JOIN job_master jm ON jm.doctor=dm.id 
                where dm.status='1' $city_query AND jm.status!=0 AND jm.date>='$start_date 00:00:00' AND jm.date<='$end_date 23:23:59' GROUP BY dm.id ORDER BY jm.date ASC");

                $query = $this->Test_tat_model->get_val("select dm.id,dm.full_name,jm.id AS job_id, 
                    count(jm.sample_collection) AS date_wise_total,DATE_FORMAT(jm.date, '%Y-%m-%d') AS job_date 
                    from doctor_master dm INNER JOIN job_master jm ON jm.doctor=dm.id 
                    where dm.status='1' $city_query AND jm.status!=0 AND jm.date>='$start_date 00:00:00' 
                    AND jm.date<='$end_date 23:23:59' GROUP BY job_date ORDER BY jm.date ASC");

                $data['doc_wise_total'] = $this->Test_tat_model->get_val("select dm.id,dm.full_name,jm.id AS job_id, 
                    count(jm.sample_collection) AS total,DATE_FORMAT(jm.date, '%Y-%m-%d') AS job_date 
                    from doctor_master dm INNER JOIN job_master jm ON jm.doctor=dm.id 
                    where dm.status='1' $city_query AND jm.status!=0 AND jm.date>='$start_date 00:00:00' 
                    AND jm.date<='$end_date 23:23:59' GROUP BY dm.id ORDER BY jm.date ASC");
            }
            
            $j = 0;
            $final_array = [];
            foreach ($query as $key) {
                $doc_info = $this->Test_tat_model->get_val("select dm.id,dm.full_name,jm.id AS job_id, 
                count(jm.sample_collection) AS doctor_wise_total,jm.date from doctor_master dm INNER JOIN job_master jm ON jm.doctor=dm.id 
                where dm.status='1' AND jm.status!=0 AND jm.date>='$key->job_date 00:00:00' AND jm.date<='$key->job_date 23:23:59' 
                GROUP BY dm.id ORDER BY jm.date ASC");
                
                $key->doc_info = $doc_info;
                $final_array[] = $key;
            }
            
            $data['final_array'] = $final_array;
            //echo "<pre>"; print_r($final_array); exit;
        }
        
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('doctor_sample_report_view', $data);
        $this->load->view('footer');
    }

    function export_csv() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data['user'] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        
        $data['start_date'] = $start_date = $this->input->get('start_date');
        $data['end_date'] = $end_date = $this->input->get('end_date');
        //$data['doctor'] = $this->Test_tat_model->get_val("select id,full_name from doctor_master where id IN(select doctor from job_master) AND status='1'");
        
        $data['city'] = $this->Test_tat_model->get_val("select * from test_cities where status='1'");
        
        $data['city_id'] = $city_id = $this->input->get('city_id');
        $city_query = '';
        if ($city_id != "") {
            $city_id = $this->Test_tat_model->get_val("select city_fk from test_cities where id='$city_id'")[0]->city_fk;
            $city_query = " AND dm.city='$city_id'";
            $data['doctor'] = $this->Test_tat_model->get_val("select id,full_name from doctor_master where id IN(select doctor from job_master) AND status='1' AND city='$city_id'");
        } else {
            $data['doctor'] = $this->Test_tat_model->get_val("select id,full_name from doctor_master where id IN(select doctor from job_master) AND status='1' AND city='333'");
        }


        $doc1 = $this->input->get('doc_id');
        $data['doc'] = $doc = explode(',', $doc1);
        //echo "<pre>"; print_r($doc); exit;

        if ($start_date != '' && $end_date != '') {

            if (!empty($doc1)) {
                $data['doc_list'] = $this->Test_tat_model->get_val("select dm.id,dm.full_name from doctor_master dm INNER JOIN job_master jm ON jm.doctor=dm.id 
                where dm.id IN(" . implode(',', $doc) . ") AND dm.status='1' $city_query AND jm.status!=0 AND jm.date>='$start_date 00:00:00' AND jm.date<='$end_date 23:23:59' GROUP BY dm.id ORDER BY jm.date ASC");

                $query = $this->Test_tat_model->get_val("select dm.id,dm.full_name,jm.id AS job_id, 
                    count(jm.sample_collection) AS date_wise_total,DATE_FORMAT(jm.date, '%Y-%m-%d') AS job_date 
                    from doctor_master dm INNER JOIN job_master jm ON jm.doctor=dm.id 
                    where dm.id IN(" . implode(',', $doc) . ") AND dm.status='1' $city_query AND jm.status!=0 AND jm.date>='$start_date 00:00:00' 
                    AND jm.date<='$end_date 23:23:59' GROUP BY job_date ORDER BY jm.date ASC");

                $data['doc_wise_total'] = $this->Test_tat_model->get_val("select dm.id,dm.full_name,jm.id AS job_id, 
                    count(jm.sample_collection) AS total,DATE_FORMAT(jm.date, '%Y-%m-%d') AS job_date 
                    from doctor_master dm INNER JOIN job_master jm ON jm.doctor=dm.id 
                    where dm.id IN(" . implode(',', $doc) . ") AND dm.status='1' $city_query AND jm.status!=0 AND jm.date>='$start_date 00:00:00' 
                    AND jm.date<='$end_date 23:23:59' GROUP BY dm.id ORDER BY jm.date ASC");
            } else {
                $data['doc_list'] = $this->Test_tat_model->get_val("select dm.id,dm.full_name from doctor_master dm INNER JOIN job_master jm ON jm.doctor=dm.id 
                where dm.status='1' $city_query AND jm.status!=0 AND jm.date>='$start_date 00:00:00' AND jm.date<='$end_date 23:23:59' GROUP BY dm.id ORDER BY jm.date ASC");

                $query = $this->Test_tat_model->get_val("select dm.id,dm.full_name,jm.id AS job_id, 
                    count(jm.sample_collection) AS date_wise_total,DATE_FORMAT(jm.date, '%Y-%m-%d') AS job_date 
                    from doctor_master dm INNER JOIN job_master jm ON jm.doctor=dm.id 
                    where dm.status='1' $city_query AND jm.status!=0 AND jm.date>='$start_date 00:00:00' 
                    AND jm.date<='$end_date 23:23:59' GROUP BY job_date ORDER BY jm.date ASC");

                $data['doc_wise_total'] = $this->Test_tat_model->get_val("select dm.id,dm.full_name,jm.id AS job_id, 
                    count(jm.sample_collection) AS total,DATE_FORMAT(jm.date, '%Y-%m-%d') AS job_date 
                    from doctor_master dm INNER JOIN job_master jm ON jm.doctor=dm.id 
                    where dm.status='1' $city_query AND jm.status!=0 AND jm.date>='$start_date 00:00:00' 
                    AND jm.date<='$end_date 23:23:59' GROUP BY dm.id ORDER BY jm.date ASC");
            }

            $j = 0;
            $final_array = [];
            foreach ($query as $key) {
                $doc_info = $this->Test_tat_model->get_val("select dm.id,dm.full_name,jm.id AS job_id, 
                count(jm.sample_collection) AS doctor_wise_total,jm.date from doctor_master dm INNER JOIN job_master jm ON jm.doctor=dm.id 
                where dm.status='1' AND jm.status!=0 AND jm.date>='$key->job_date 00:00:00' AND jm.date<='$key->job_date 23:23:59'                    
                GROUP BY dm.id ORDER BY jm.date ASC");

                $key->doc_info = $doc_info;
                $final_array[] = $key;
            }

            $data['final_array'] = $final_array;
            //echo "<pre>"; print_r($final_array); exit;
        }

        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"Doctor Sample Collection Report.csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');

        $cnt = 0;
        $haystack = [];
        $doc_list1 = [];
        $doc_list1[] = "Date\Doctors";

        foreach ($data['doc_list'] as $row) {
            if (!in_array($row->id, $haystack)) {
                $haystack[] = $row->id;
                $doc_list1[] = $row->full_name;
            }
        }
        $doc_list1[] = "Total";
        fputcsv($handle, $doc_list1);

        $cnt = 0;
        foreach ($final_array as $row1) {
            $arr = [];
            $total_vertical = 0;
            $arr[] = date('Y-m-d', strtotime($row1->job_date));
            foreach ($haystack as $h) {
                $count = 0;
                foreach ($row1->doc_info as $r) {
                    if ($h == $r->id) {
                        $count = $r->doctor_wise_total;
                        $total_vertical += $count;
                    }
                }
                $arr[] = $count;
            }
            $arr[] = $total_vertical;
            fputcsv($handle, $arr);
            $cnt++;
        }

        if (!empty($final_array)) {
            $horizontal_arr = array('Total');
            $grand_total = 0;
            foreach ($data['doc_wise_total'] as $key1) {
                $grand_total += $key1->total;
                $horizontal_arr[] = $key1->total;
            }
            $horizontal_arr[] = $grand_total;
            fputcsv($handle, $horizontal_arr);
        }

        fclose($handle);
        exit;
    }

    function fetch_doc() {
        $city = $this->input->get_post('cityID');
        if ($city != "") {
            $city_id = $this->Test_tat_model->get_val("select city_fk from test_cities where id='$city'")[0]->city_fk;
            $doc_info = $this->Test_tat_model->get_val("select id,full_name from doctor_master where id IN(select doctor from job_master) AND status='1' AND city='$city_id'");
            //print_r(json_encode($doc_info));
            ?>
            <?php foreach ($doc_info as $key) {
                ?>
                <option value="<?php echo $key->id ?>"><?php echo $key->full_name ?></option>
            <?php } ?>
            <?php
        }
    }

}
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('user_model');
    }

    function index() {
        $data["login_data"] = logindata();  
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]); 
        // vishal code Start
 $data['total_reagent'] = $this->user_model->get_val('SELECT COUNT(item.id) AS total_reagent FROM inventory_item AS item LEFT JOIN inventory_machine AS im ON im.id = item.machine WHERE item.status ="1" AND im.status ="1" AND item.machine !="" AND item.machine !="0" AND item.category_fk="3"');


        $data['total_stationary'] = $this->user_model->get_val('SELECT COUNT(id) AS stationary FROM inventory_item WHERE status="1" AND category_fk="1"');
        
        $data['total_consumes'] = $this->user_model->get_val('select count(id) as consumes from inventory_item where status="1" and category_fk="2"');

        // VIshal COde End
        $this->load->view('inventory/header');
        $this->load->view('inventory/nav', $data);
        $this->load->view('inventory/dashboard', $data);
        $this->load->view('inventory/footer');
    }

}

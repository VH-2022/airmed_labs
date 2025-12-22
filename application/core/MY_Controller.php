<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    protected $phlebo;
    public function __construct() {

        parent::__construct();
        $this->load->model('service_model'); // Assuming your model is loaded here
    }

    protected function _validate_token() {
        $headers = $this->input->request_headers();
        $token = isset($headers['Authorization']) ? trim(str_replace("Bearer", "", $headers['Authorization'])) : "";

        if (empty($token)) {
            echo $this->json_data("0", "Token missing", ""); // Use your json_data method (assuming it's defined elsewhere)
            return false;
        }

        $this->phlebo = $this->service_model->get_phlebo_by_token($token);
        if (!$this->phlebo) {
            echo $this->json_data("0", "Invalid or expired token", "");
            return false;
        }

        // Optional: Add token expiry check here if you store expiry in DB
        // e.g., if (strtotime($this->phlebo->expiry) < time()) { echo error; return false; }

        return true;
    }

    protected function json_data($status, $message, $data = "") {
        $response = array(
            'status' => $status,
            'message' => $message,
            'data' => $data
        );
        echo json_encode($response);
    }
}
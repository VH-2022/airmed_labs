<?php


/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Employer extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Employer/Employer_model');
       $this->load->helper('url_helper');
		$this->load->library('user_agent');
        $this->load->helper(array('form', 'url'));
        $this->load->library('session');
		$this->load->library('upload');
		 $this->load->library("pagination");
		 $this->load->library("email");
		$session_data = $this->session->userdata('logged_in');
				
                $data['email'] = $session_data['email'];
               //$data['company_name'] = $session_data['company_name'];
                $data['id'] = $session_data['id'];
				
				 $data['query'] = $this->Employer_model->get_language();
            $lang = array();
            $data["lang"] = new stdClass();
            foreach ($data['query'] as $row) {
                $data['lang']->{$row['key_name']} = $row['eng_name'];
            }
        $this->data['lang'] = $data["lang"];
				
        
    }

	   public function index() {
	  
if ($this->session->userdata('logged_in') && $this->session->userdata('logged_in')['type']=="EMPLOYER" || 
	$this->session->userdata('logged_in')['oauth_provider']=="google" || $this->session->userdata('logged_in')['oauth_provider']=="facebook") { 
          $session_data = $this->session->userdata('logged_in');
			$data['id'] = $session_data['id'];
                  $data['email'] = $session_data['email'];
			  $data['company_name'] = $session_data['company_name'];
		}
		
		else
		{
			redirect('Employerr/Employer/login',"refresh");
		}
				
	 $this->load->view('employer/header');
       $this->load->view('employer/login');
		$this->load->view('employer/footer');
    }
	
	public function dashboard1(){
		
		require APPPATH .'third_party/Facebook/autoload.php';
	
		$this->config->load('facebook_employe');
		$appId=$this->config->item('appId');
		$secret=$this->config->item('secret');
		$fb = new Facebook\Facebook([
  'app_id' =>$appId,
  'app_secret' => $secret,
  'default_graph_version' => 'v2.4',
]);
$helper = $fb->getCanvasHelper();

$permissions = ['user_friends']; // optionnal

$helper = $fb->getRedirectLoginHelper();  
  
try {  
  $accessToken = $helper->getAccessToken();  
} catch(Facebook\Exceptions\FacebookResponseException $e) {  
  // When Graph returns an error  
  
  echo 'Graph returned an error: ' . $e->getMessage();  
  exit;  
} catch(Facebook\Exceptions\FacebookSDKException $e) {  
  // When validation fails or other local issues  

  echo 'Facebook SDK returned an error: ' . $e->getMessage();  
  exit;  
}

if (isset($_SESSION['facebook_access_token'])) {
	
		$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
	} else {
		
		$_SESSION['facebook_access_token'] = (string) $accessToken;

	  	// OAuth 2.0 client handler
		$oAuth2Client = $fb->getOAuth2Client();

		// Exchanges a short-lived access token for a long-lived one
		$longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);

		$_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;

		$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
	}

	// validating the access token
	try {
		
		$request = $fb->get('/me?fields=id,email,name,first_name,last_name,gender,picture.width(200).height(200)');
		
		//$user_profile=$request['decodedBody:protected']; 
		$user = $request->getGraphUser();
		$email=$user['email'];
		$name=$user['name'];
		$first_name=$user['first_name'];
		$last_name=$user['last_name'];
		$picture=$user['picture'];
		
		$userData['oauth_provider'] = 'facebook';
            $userData['oauth_uid'] = $user['id'];
            $userData['company_name'] = $first_name;
			$userData['email'] = $email;
            
            $userID = $this->Employer_model->checkUser($userData);
            
			    $userData['id'] = $userID;
                $data['userData'] = $userData;
				$this->session->set_userdata('logged_in',$userData);
				redirect('Employerr/Employer/dashboard');
        
		
	} catch(Facebook\Exceptions\FacebookResponseException $e) {
		// When Graph returns an error
		if ($e->getCode() == 190) {
			unset($_SESSION['facebook_access_token']);
			$helper = $fb->getRedirectLoginHelper();
			
			
			$loginUrl = $helper->getLoginUrl('http://website-demo.co.in/phpdemoz/facebooklogin/demo2/index.php', $permissions);
			echo "<script>window.top.location.href='".$loginUrl."'</script>";
		}
		exit;
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
		// When validation fails or other local issues
		echo 'Facebook SDK returned an error: ' . $e->getMessage();
		exit;
	}
	
	
}
	
public function dashboard(){
if ($this->session->userdata('logged_in') && $this->session->userdata('logged_in')['type']=="EMPLOYER" || 
	$this->session->userdata('logged_in')['oauth_provider']=="google" || $this->session->userdata('logged_in')['oauth_provider']=="facebook") {
            $session_data = $this->session->userdata('logged_in');
			$data['id'] = $session_data['id'];
                  $data['email'] = $session_data['email'];
			  $data['company_name'] = $session_data['company_name'];
		}
		
		else
		{
			redirect('Employerr/Employer/logout',"refresh");
		}
		
		
		
	    $this->load->view('employer/header',$data);
        $this->load->view('employer/dashboard',$data);
		$this->load->view('employer/footer',$data);
   
	}
	
	function check_employer_email($email)
	{
		$result = $this->Employer_model->check_employer_email($email);
		$email = $this->input->post('email');
		if($result != $email)
		{
			return TRUE;
			
		}
		else
		{
			
			$this->form_validation->set_message('check_employer_email', 'The Email field must contain a unique value.');
            return false;
		}
}
	
	
	public function check_passoword($str)
{
$str = $this->input->post('password');
$match =preg_match('#[0-9]#', $str) && preg_match('#[a-zA-Z]#', $str);
    if ($match == FALSE)
   {
       $this->form_validation->set_message('check_passoword', 'Password Field Must Contain alphanumeric Value.');
return FALSE;
  
   }
   else
   {
return TRUE;
       
   }
}
 	public function check_captcha($str)
{
$recaptcharesponse=$this->input->post('g-recaptcha-response');
$secret='6LcA0B8UAAAAALH-hhykTmEcqVJLETRjQarKFxF2';
			 $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$recaptcharesponse);
        $responseData = json_decode($verifyResponse);
        if($responseData->success){ return TRUE;
       
      }
   else
   {

       $this->form_validation->set_message('check_captcha', 'invalid captcha please enter the valid captcha.');
return FALSE;
   }
}
	public function employer_reg()
	{
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('company_name','Company Name','required');
		$this->form_validation->set_rules('owner','Owner Name','required');
		$this->form_validation->set_rules('description','Description','required');
		$this->form_validation->set_rules('contact','Contact','required');
		$this->form_validation->set_rules('address','Permanent Address','required');
		
		
	if (!empty($this->input->post('email'))){

		$this->form_validation->set_rules('email', 'email', 'required|valid_email|callback_check_employer_email');
	}
	else
	{
		$this->form_validation->set_rules('email', 'email', 'required');
		}
		
		
		//$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		//$this->form_validation->set_rules('website','Website','required');
		
	if (!empty($this->input->post('password'))){

	$this->form_validation->set_rules('password', 'Password', 'required|min_length[8]|callback_check_passoword');
	}
	else
	{
		$this->form_validation->set_rules('password', 'Password', 'required');
		}
	$this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'required|callback_check_captcha');	
	$this->form_validation->set_rules('retype','ReType','required|matches[password]');
		$this->form_validation->set_rules('terms','Terms & Condition agreement','required');
		if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            $data['email'] = $session_data['email'];
			//  $data['name'] = $session_data['name'];
			    $data['id'] = $session_data['id'];
				}
				
				
				if($this->form_validation->run() == FALSE)
				{
					
					$this->load->view('employer/header');
			
					$data["success"]=$this->session->flashdata("success");
					
					$this->load->view('employer/employer_reg',$data);
					
					$this->load->view('employer/footer',$data);
					
				}
				else
				{
						$this->session->unset_userdata('termdata');
						$query = $this->Employer_model->insertemployer();
						
						$email = $this->input->post('email');
						$company_name = $this->input->post('company_name');
						$password = $this->input->post('password');
						$getadata1 = $this->db->get_where('email_setting',array('email_id' =>3));
						$getadata=$getadata1->row();
						$body=$getadata->message;
						$subject=$getadata->subject;
						$link=base_url()."index.php/Employerr/Employer/login/".$query;
						$body=str_replace('{{company_name}}',$name,$body);
						$body=str_replace('{{password}}',$password,$body);
						$body=str_replace('{{email}}',$email, $body);
						$body=str_replace('{{link}}',$link, $body);
			
						$message = $body;
						
						$this->load->helper(array('swift'));
			     send_mail($email,$subject,$message); 
						
						$this->session->set_flashdata("email_sent","Email Sent Successfully.");
						
						$this->session->set_flashdata("success",$this->data['lang']->reg_success);
						 
						redirect("Employerr/Employer/login","refresh");
				}
		
	}
	
	public function login() {
		/*Google Login Coding Starts*/
		// Include the google api php libraries
        include_once APPPATH."libraries/google-api-php-client/Google_Client.php";
        include_once APPPATH."libraries/google-api-php-client/contrib/Google_Oauth2Service.php";
		
		 // Google Project API Credentials
        $clientId = '652706727262-7pnvj83agd1va9gi943gsuerdbi80kjt.apps.googleusercontent.com';
        $clientSecret = 'Z3clf-qrhL9OZdijUhxPnpBp';
       $redirectUrl1 = base_url().'Employerr/Employer/login'; 
		 // Google Client Configuration
        $gClient = new Google_Client();
        $gClient->setApplicationName('Login to Powercontracts Job Portal');
        $gClient->setClientId($clientId);
        $gClient->setClientSecret($clientSecret);
        $gClient->setRedirectUri($redirectUrl1);
        $google_oauthV2 = new Google_Oauth2Service($gClient);
		if (isset($_REQUEST['code'])) {
		
            $gClient->authenticate();
            $this->session->set_userdata('token', $gClient->getAccessToken());
			//echo $gClient->getAccessToken();die();
            //redirect('Employerr/Employer/dashboard');
        }

        $token = $this->session->userdata('token');
        if (!empty($token)) {
            $gClient->setAccessToken($token);
        }

        if ($gClient->getAccessToken()) {
            $userProfile = $google_oauthV2->userinfo->get();
            // Preparing data for database insertion
            $userData['oauth_provider'] = 'google';
            $userData['oauth_uid'] = $userProfile['id'];
            $userData['company_name'] = $userProfile['given_name'];
            //$userData['last_name'] = $userProfile['family_name'];
            $userData['email'] = $userProfile['email'];
            //$userData['gender'] = $userProfile['gender'];
            //$userData['locale'] = $userProfile['locale'];
            //$userData['profile_url'] = $userProfile['link'];
            //$userData['picture_url'] = $userProfile['picture'];
            // Insert or update user data
            $userID = $this->Employer_model->checkUser($userData);
			
            if(!empty($userID)){
			$userData['id'] = $userID;
                $data['userData'] = $userData;
                $this->session->set_userdata('logged_in',$userData);
				
				redirect('Employerr/Employer/dashboard');
            } else {
               $data['userData'] = array();
            }
        } else {
            $data['authUrl1'] = $gClient->createAuthUrl();
        }
		
		/*Google Login Coding Ends*/
		/*Fb Login Code Starts*/
		$this->load->helper('social_helper');
		$data['authUrl']=get_facebookurl1();
		/*Fb Login Coding Ends*/
        $this->load->helper('form');
        $this->load->library('form_validation');
      $user_id = $this->uri->segment(4);
//print_r($user_id);
$data['employer'] = $this->Employer_model->get_employer_data($user_id);
//print_r($data['employee'][0]['id']);
if(!empty($user_id)){
if($data['employer'][0]['id'] == $user_id){
$data1 = array('status'=>1);
$this->db->where('id',$user_id);
$this->db->update('employer_master',$data1);
} }

        $this->form_validation->set_rules('email', 'email', 'trim|required');

       $password = $this->input->post('password');
		if(!empty($password))
		{
			$this->form_validation->set_rules('password', 'password', 'callback_check_database');
		}
		else
		{
			$this->form_validation->set_rules('password', 'password', 'required');
		}
		$this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'required|callback_check_captcha');
        if ($this->form_validation->run() == FALSE) {
			  $this->load->view('employer/header');
            $this->load->view('employer/login',$data);
			  $this->load->view('employer/footer');
            
        } else {
            if ($this->session->userdata('logged_in') && $this->session->userdata('logged_in')['type']=="EMPLOYER" || 
			$this->session->userdata('logged_in')['oauth_provider']=="google" || $this->session->userdata('logged_in')['oauth_provider']=="facebook") {
                $session_data = $this->session->userdata('logged_in');
				
                $data['email'] = $session_data['email'];
                $data['id'] = $session_data['id'];
				$data['company_name'] = $session_data['company_name'];
				
				
				
			
            }
			else
				{
					redirect('Employerr/Employer/login',"refresh");
				}

	$this->session->set_flashdata("success",$this->data['lang']->logged_in_successfully_msg);
    redirect('Employerr/Employer/dashboard/',$data);
        }
    }

    function check_database($password) {
        //Field validation succeeded.  Validate against database
        $email = $this->input->post('email');
		//query the database
        $result = $this->Employer_model->logon($email, $password);

        if ($result) {
            $sess_array = array();
            foreach ($result as $row) {
                $sess_array = array(
					'id'=>$row->id,
					'company_name'=>$row->company_name,
                    'email' => $row->email,
					'type' => "EMPLOYER",
					'oauth_provider' => $row->oauth_provider,
                    'password' => $row->password
                );
				
                $this->session->set_userdata('logged_in', $sess_array);
                $email = $this->session->userdata('email');
				 $company_name = $this->session->userdata('company_name');
            }
            return TRUE;
        } else {
            $this->form_validation->set_message('check_database', 'Invalid username or password');
            return false;
        }
    }
	
	public function ucp_change_pass() {
	
			$this->load->helper('form');
			$this->load->library('form_validation');
            if ($this->session->userdata('logged_in') /*&& $this->session->userdata('logged_in')['type']=="EMPLOYER"*/) {
            $session_data = $this->session->userdata('logged_in');
            $data['email'] = $session_data['email'];
			$data['company_name'] = $session_data['company_name'];
			$data['id'] = $session_data['id'];
            
        }
		else
		{
			redirect('Employerr/Employer/logout',"refresh");
		}
            $this->form_validation->set_rules('password','Old password','trim|required|callback_oldpass_check');            
            $this->form_validation->set_rules('new_password','New password', 'trim|required|min_length[4]');
            $this->form_validation->set_rules('retype', 'Confirm password', 'trim|required|min_length[4]|matches[new_password]');

            if($this->form_validation->run() == TRUE) 
            {       
                $data =array('password' => $this->input->post('retype'));                   
                $this->Employer_model->update('employer_master',$session_data['id'],$data);
				
				redirect('Employerr/Employer/dashboard');
                                   
            }
			
            else{
				
				$this->load->view('employer/header',$data);
                $this->load->view('employer/changepwd');
				$this->load->view('employer/footer',$data);
				
                }       
          
    } 
	
	function oldpass_check($oldpass)
 { 
	$session_data = $this->session->userdata('logged_in');
    $result = $this->Employer_model->check_oldpassword($oldpass,$session_data['id']);  
	
        if($result == 0)
            {
                $this->form_validation->set_message('oldpass_check', "%s is required or %s doesn't match.");
                return FALSE ;  

            }
         else
            {
                return TRUE ;

            }             
}

	public function view_employer()
	{
		if ($this->session->userdata('logged_in') && $this->session->userdata('logged_in')['type']=="EMPLOYER" || 
	$this->session->userdata('logged_in')['oauth_provider']=="google" || $this->session->userdata('logged_in')['oauth_provider']=="facebook") {

            $session_data = $this->session->userdata('logged_in');
			
            $data['email'] = $session_data['email'];
			  $data['company_name'] = $session_data['company_name'];
			  
			    $data['id'] = $session_data['id'];
				
				}
				
				else
		{
		
			redirect('Employerr/Employer/logout',"refresh");
		}
		
				$id = $this->uri->segment(4);
				$data['user'] = $this->Employer_model->viewprofile($id);
				$data['title'] = "Company Details";
		$this->load->view('employer/header',$data);
		$this->load->view('employer/view_employer',$data);
		$this->load->view('employer/footer',$data);
		
	}
   public function password_check($str)
{
   if (preg_match('#[0-9]#', $str) && preg_match('#[a-zA-Z]#', $str)) {
     return TRUE;
   }
   $this->form_validation->set_message('password_check', "Password Field Must Contain min 8 alpha numerical Value");
   return FALSE;
}
	public function edit_employer()
	{
			if ($this->session->userdata('logged_in') && $this->session->userdata('logged_in')['type']=="EMPLOYER" || 
	$this->session->userdata('logged_in')['oauth_provider']=="google" || $this->session->userdata('logged_in')['oauth_provider']=="facebook") {
            $session_data = $this->session->userdata('logged_in');
            //$data['email'] = $session_data['email'];
			$data['company_name'] = $session_data['company_name'];
			$data['id'] = $session_data['id'];
        }
		
		else
		{
			redirect('Employerr/Employer/logout',"refresh");
		}
			
		$id = $this->uri->segment(4);
	
		
		
		$this->load->helper('form');
                $this->load->library('form_validation');

		$data['user'] = $this->Employer_model->fetch_employer($id);
		$this->form_validation->set_rules('company_name','Company Name','required');
		$this->form_validation->set_rules('owner','Owner Name','required');
		$this->form_validation->set_rules('description','Description','required');
		$this->form_validation->set_rules('contact','Contact','required');
		$this->form_validation->set_rules('address','Permanent Address','required');	
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		//$this->form_validation->set_rules('website','Website','required');
		if(empty($data['user']['oauth_provider'])) {
		$this->form_validation->set_rules('password','Password','required|min_length[8]|alpha_numeric');
		$this->form_validation->set_rules('retype','ReType','required|min_length[8]|alpha_numeric|matches[password]|callback_password_check');
		}
		$this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'required|callback_check_captcha');
		if ($this->form_validation->run() === FALSE)
                {
				
				
		
			$this->load->view('employer/header',$data);
			$this->load->view('employer/edit_employer',$data); 
            $this->load->view('employer/footer',$data);            
			
		}
		else
		{
			$this->Employer_model->update_employer($id);
			$this->load->view('employer/header',$data);
		$this->session->set_flashdata("success",$this->data['lang']->profile_updated_msg);
		$this->load->view('employer/dashboard',$data);
		
			
			$this->load->view('employer/footer',$data);
			
                       
		}
		
		
	}
	   function logout() {
        $this->session->sess_destroy();
		
			$this->load->view('employer/header');
       redirect('Employerr/Employer/login',"refresh");
		  $this->load->view('employer/footer');            
			
    }
	
	function view_package()
	{
		if ($this->session->userdata('logged_in') && $this->session->userdata('logged_in')['type']=="EMPLOYER" || 
	$this->session->userdata('logged_in')['oauth_provider']=="google" || $this->session->userdata('logged_in')['oauth_provider']=="facebook") {
				   $session_data = $this->session->userdata('logged_in');
				   $data['email'] = $session_data['email'];
		$data['company_name'] = $session_data['company_name'];
		$data['id'] = $session_data['id'];
			   }

		else
		{
		redirect('Employerr/Employer/logout',"refresh");
		}

			$data['results'] = $this->Employer_model->view_package();
			$this->load->helper('packgesactive_helper');
			$this->load->view('employer/header',$data);
			$this->load->view('employer/view_package',$data);
			$this->load->view('employer/footer',$data); 
	 }
	
	public function post_job()
	{

		if ($this->session->userdata('logged_in') && $this->session->userdata('logged_in')['type']=="EMPLOYER" || 
	$this->session->userdata('logged_in')['oauth_provider']=="google" || $this->session->userdata('logged_in')['oauth_provider']=="facebook") {
				   $session_data = $this->session->userdata('logged_in');
				   $data['email'] = $session_data['email'];
		$data['company_name'] = $session_data['company_name'];
		$data['id'] = $session_data['id'];
			   }

		else
		{
		redirect('Employerr/Employer/logout',"refresh");
		}
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title','Job Title','required');
		$this->form_validation->set_rules('job_description','Job Description','required');
		$this->form_validation->set_rules('category_name','Category Name','required');
		$this->form_validation->set_rules('display','Display Type','required');
		$this->form_validation->set_rules('position','Position Type','required');
		$data['results'] = $this->Employer_model->get_category11();
	
			if($this->form_validation->run() == false)
			{
			$this->load->view('employer/header',$data);
			$data["success"]=$this->session->flashdata("success");
			$this->load->view('employer/post_job',$data);
			$this->load->view('employer/footer',$data); 
			}
			else
			{
			$attachment = $_FILES["attachment"]["name"];
			if ($_FILES["attachment"]["name"]) {
						   $config['upload_path'] = './test/';
						   $config['allowed_types'] = 'jpg|jpeg|png|gif';
						   $_FILES["attachment"]["name"] = str_replace(' ', '_', $_FILES["attachment"]["name"]);
						   $config['file_name'] =$_FILES["attachment"]["name"];
						   $img = $this->load->library('upload', $config);
			$this->upload->initialize($config);
						   if (!$this->upload->do_upload('attachment')) {
							   $error = array('error' => $this->upload->display_errors());
			
							   $this->session->set_flashdata("unsucess", array($this->upload->display_errors()));
							   redirect("Employerr/Employer/post_job", "refresh");
						   } else {
							   $data = array('upload_data' => $this->upload->data());
							   $file_name = $data["upload_data"]["file_name"];
						   }
					   }
					   $data['query'] = $this->Employer_model->add_job();
						$title = $this->input->post('title');
						$subject="Post Job";
						$message="<html><body><h2>Hello,".$session_data['company_name']."</h2>Your Job Post Request Is In Process.<br/>Thank you for Posting Job Request with Job Portal.<br/> <br/><br/><b>Job Title : ".$title."</b></body></html";

						$this->load->helper(array('swift'));
						send_mail($session_data['email'],$subject,$message); 
				 
						$this->session->set_flashdata("email_sent","Email Sent Successfully.");
						
						$id = $this->uri->segment(4);
						//$data['query'] = $this->Employer_model->jobinsert_approve($id,$attachment);
						$this->session->set_flashdata("success",array("Record successfully added."));
						redirect("Employerr/Employer/dashboard","refresh");

			}
	}
	
public function add_listing()
 { 
	
		if ($this->session->userdata('logged_in') && $this->session->userdata('logged_in')['type']=="EMPLOYER" || 
	  $this->session->userdata('logged_in')['oauth_provider']=="google" || $this->session->userdata('logged_in')['oauth_provider']=="facebook") {
				   $session_data = $this->session->userdata('logged_in');
				   $data['email'] = $session_data['email'];
		$data['company_name'] = $session_data['company_name'];
		$data['id'] = $session_data['id'];
			   }

		else
		{
		redirect('Employerr/Employer/logout',"refresh");
		}
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title','Job Title','required');
		$this->form_validation->set_rules('job_description','Job Description','required');
		$this->form_validation->set_rules('category_name','Category Name','required');
		$this->form_validation->set_rules('display','Display Type','required');
		$this->form_validation->set_rules('position','Position Type','required');
		$this->form_validation->set_rules('length','Length','required');
		$package_id = $this->uri->segment(4);
		$data['results'] = $this->Employer_model->get_category11();
		$data['package'] = $this->Employer_model->get_package1($package_id);
		
		if($this->form_validation->run() == false)
			{
			$this->load->view('employer/header',$data);
			$this->load->view('employer/add_listing',$data);
			$this->load->view('employer/footer',$data); 
			}
			else
			{ 
			 $attachment =$_FILES["attachment"]["name"];
			if ($_FILES["attachment"]["name"]) {
						   $config['upload_path'] = './test/';
						   $config['allowed_types'] = 'jpg|jpeg|png|gif';
						   $_FILES["attachment"]["name"] = str_replace(' ', '_', $_FILES["attachment"]["name"]);
						   $config['file_name'] = $_FILES["attachment"]["name"];
						   $img = $this->load->library('upload', $config);
							$this->upload->initialize($config);
						   if (!$this->upload->do_upload('attachment')) {
							   $error = array('error' => $this->upload->display_errors());
			
							   $this->session->set_flashdata("unsucess", array($this->upload->display_errors()));
							   redirect("Employerr/Employer/post_job", "refresh");
						   } else {
							   $data = array('upload_data' => $this->upload->data());
							   $file_name = $data["upload_data"]["file_name"];
						   }
					   }
		$package_id=$this->input->post('pack_fk');
		$useactive=$this->Employer_model->get_activepackge($data['id'],$package_id);
		if($useactive != ""){ 	$activepack1=$useactive->id; 
								$pachejobs=$useactive->listing_no;
		
		 $totaladdjobs=$this->Employer_model->forgotpass('post_job',array("pack_fk"=>$activepack1));
		if($totaladdjobs < $pachejobs){ $activepack=$useactive->id;  }else{ $product=$this->Employer_model->fetchdatarow('pack_id,pack_description,pack_name,days,listing_no,front_page,hightlighted,html_listing,visitor_count,top_of_category,google_map,price','package_master',array("pack_id"=>$package_id));
		
		$pack_id=$product->pack_id;
		$pack_name=$product->pack_name;
		$pack_description=$product->pack_description;
		$listing_no=$product->listing_no;
		$front_page=$product->front_page;
		$hightlighted=$product->hightlighted;
		$html_listing=$product->html_listing;
		$visitor_count=$product->visitor_count;
		$top_of_category=$product->top_of_category;
		$google_map=$product->google_map;
		$price=$product->price;
		$days=$product->days;
		$employer_fk=$session_data['id'];
		
		$expiry=date('Y-m-d h:i:sa', mktime(date("H"), date("i"), date("s"), date("m"), date("d") + $product->days, date("Y"))); 
		
		$data_array=array('employer_fk'=>$session_data['id'],'package_fk'=>$package_id,'start_date'=>date('Y-m-d h:i:sa'),'expiry_date'=>$expiry,'payment_status'=>"pending",'price'=>$price,'mrp_price'=>$price,'created_date' =>date('Y-m-d h:i:sa'),'status' =>2,"pack_name"=>$pack_name,"pack_description"=>$pack_description,"listing_no"=>$listing_no,"front_page"=>$front_page,"hightlighted"=>$hightlighted,"html_listing"=>$html_listing,"visitor_count"=>$visitor_count,"top_of_category"=>$top_of_category,"google_map"=>$google_map,"days"=>$days);
		
		 $activepack=$this->Employer_model->master_fun_insert("user_active_package",$data_array); }
		}else{ $product=$this->Employer_model->fetchdatarow('pack_id,pack_description,pack_name,days,listing_no,front_page,hightlighted,html_listing,visitor_count,top_of_category,google_map,price','package_master',array("pack_id"=>$package_id));
		
		$pack_id=$product->pack_id;
		$pack_name=$product->pack_name;
		$pack_description=$product->pack_description;
		$listing_no=$product->listing_no;
		$front_page=$product->front_page;
		$hightlighted=$product->hightlighted;
		$html_listing=$product->html_listing;
		$visitor_count=$product->visitor_count;
		$top_of_category=$product->top_of_category;
		$google_map=$product->google_map;
		$price=$product->price;
		$days=$product->days;
		$employer_fk=$session_data['id'];
		
		$expiry=date('Y-m-d h:i:sa', mktime(date("H"), date("i"), date("s"), date("m"), date("d") + $product->days, date("Y"))); 
		
		$data_array=array('employer_fk'=>$session_data['id'],'package_fk'=>$package_id,'start_date'=>date('Y-m-d h:i:sa'),'expiry_date'=>$expiry,'payment_status'=>"pending",'price'=>$price,'mrp_price'=>$price,'created_date' =>date('Y-m-d h:i:sa'),'status' =>2,"pack_name"=>$pack_name,"pack_description"=>$pack_description,"listing_no"=>$listing_no,"front_page"=>$front_page,"hightlighted"=>$hightlighted,"html_listing"=>$html_listing,"visitor_count"=>$visitor_count,"top_of_category"=>$top_of_category,"google_map"=>$google_map,"days"=>$days);
		
		 $activepack=$this->Employer_model->master_fun_insert("user_active_package",$data_array); }
		 
		 $data['query'] = $this->Employer_model->add_listing($session_data['id'],$activepack);
		 
						$title = $this->input->post('title');
						$getadata1 = $this->db->get_where('email_setting',array('email_id' =>14));
						$getadata=$getadata1->row();
						$body=$getadata->message;
						$subject=$getadata->subject;
						$body=str_replace('{{company_name}}',$session_data['company_name'],$body);
						$body=str_replace('{{title}}',$title,$body);
						
						$this->load->helper(array('swift'));
						//send_mail($session_data['email'],$subject,$body);
						
						$this->session->set_flashdata("email_sent","Email Sent Successfully.");
						
						$this->session->set_flashdata("success",$this->data['lang']->listing_addeded_succ_msg);
						redirect('Employerr/Employer/show_listing');

			}
	}
public function jobs_add($pacgeud=null,$activepack=null)
{ 
  if($activepack != null && $pacgeud != null){
		
		if ($this->session->userdata('logged_in') && $this->session->userdata('logged_in')['type']=="EMPLOYER" || 
	  $this->session->userdata('logged_in')['oauth_provider']=="google" || $this->session->userdata('logged_in')['oauth_provider']=="facebook") {
				   $session_data = $this->session->userdata('logged_in');
				   $data['email'] = $session_data['email'];
		$data['company_name'] = $session_data['company_name'];
		$data['id'] = $session_data['id'];
			   }

		else
		{
		redirect('Employerr/Employer/logout',"refresh");
		}
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title','Job Title','required');
		$this->form_validation->set_rules('job_description','Job Description','required');
		$this->form_validation->set_rules('category_name','Category Name','required');
		$this->form_validation->set_rules('display','Display Type','required');
		$this->form_validation->set_rules('position','Position Type','required');
		$this->form_validation->set_rules('length','Length','required');
		$package_id = $this->uri->segment(4);
		$data['results'] = $this->Employer_model->get_category11();
		$data['package'] = $this->Employer_model->get_package1($package_id);
		
		if($this->form_validation->run() == false)
		{
			
			/* $data['activepack']=$activepack; */
			
			$this->load->view('employer/header',$data);
			$this->load->view('employer/add_jobs_views',$data);
			$this->load->view('employer/footer',$data); 
		
		}
			else
			{ 
			 $attachment =$_FILES["attachment"]["name"];
			if($_FILES["attachment"]["name"]) {
						   $config['upload_path'] = './test/';
						   $config['allowed_types'] = 'jpg|jpeg|png|gif';
						   $_FILES["attachment"]["name"] = str_replace(' ', '_', $_FILES["attachment"]["name"]);
						   $config['file_name'] = $_FILES["attachment"]["name"];
						   $img = $this->load->library('upload', $config);
							$this->upload->initialize($config);
						   if (!$this->upload->do_upload('attachment')) {
							   $error = array('error' => $this->upload->display_errors());
			
							   $this->session->set_flashdata("unsucess", array($this->upload->display_errors()));
							   redirect("Employerr/Employer/post_job", "refresh");
						   } else {
							   $data = array('upload_data' => $this->upload->data());
							   $file_name = $data["upload_data"]["file_name"];
						   }
					   }
		$package_id=$this->input->post('pack_fk');
		$product=$this->Employer_model->fetchdatarow('days','package_master',array("pack_id"=>$package_id));
		$expiry=date('Y-m-d h:i:sa', mktime(date("H"), date("i"), date("s"), date("m"), date("d") + $product->days, date("Y")));
		
		  $activepack=$activepack;
		 
					   $data['query'] = $this->Employer_model->add_listing($session_data['id'],$activepack);
						
						$title = $this->input->post('title');
						
						
					
						$getadata1 = $this->db->get_where('email_setting',array('email_id' =>14));
						$getadata=$getadata1->row();
						$body=$getadata->message;
						$subject=$getadata->subject;
						$body=str_replace('{{company_name}}',$session_data['company_name'],$body);
						$body=str_replace('{{title}}',$title,$body);
						

						$this->load->helper(array('swift'));
						send_mail($session_data['email'],$subject,$body);
						
						$this->session->set_flashdata("email_sent","Email Sent Successfully.");
					
						$this->session->set_flashdata("success",$this->data['lang']->listing_addeded_succ_msg);
						
						redirect('Employerr/Employer/show_listing');

			}
	}else{ show_404(); }
	}	
	
	function show_listing()
	{
		if ($this->session->userdata('logged_in') && $this->session->userdata('logged_in')['type']=="EMPLOYER" || 
	$this->session->userdata('logged_in')['oauth_provider']=="google" || $this->session->userdata('logged_in')['oauth_provider']=="facebook") {
				   $session_data = $this->session->userdata('logged_in');
				   $data['email'] = $session_data['email'];
		$data['company_name'] = $session_data['company_name'];
		$data['id'] = $session_data['id'];
			   }

		else
		{
		redirect('Employerr/Employer/logout',"refresh");
		}
		$data['statuspackge']='1';
			$data['package_list'] = $this->Employer_model->get_package();
			$data['results1']=$this->Employer_model->fetch_package();
	
$search=$this->input->get('search');
		 $status=$this->input->get('status'); 
		if($search != "" || $status != ""){
		
		$config["base_url"] = base_url()."index.php/Employerr/Employer/show_listing?search=$search&status=$status";
		$config["total_rows"] = $this->Employer_model->get_listing_count($data['id'],$search,$status);
		$config["page_query_string"] = TRUE;
		$config["per_page"] = 20;
		$config["uri_segment"] = 4;
		$config['full_tag_open'] = "<ul class='pagination pagination-sm pro-page-list'>";
		$config['full_tag_close'] ="</ul>";
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
		$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
		$config['next_tag_open'] = "<li>";
		$config['next_tagl_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";
		$config['prev_tagl_close'] = "</li>";
		$config['first_tag_open'] = "<li>";
		$config['first_tagl_close'] = "</li>";
		$config['last_tag_open'] = "<li>";
		$config['last_tagl_close'] = "</li>"; 
		$this->pagination->initialize($config);
		$page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
		$data['results'] = $this->Employer_model->get_listingsearch($config["per_page"],$page,$data['id'],$search,$status);
		
		}else{
			
	$config["base_url"] = base_url() . "index.php/Employerr/Employer/show_listing";
	$config["total_rows"] = $this->Employer_model->get_listing_count($session_data['id']);
	$config["per_page"] = 10;
	$config["uri_segment"] = 4;
	$config['full_tag_open'] = "<ul class='pagination pagination-sm pro-page-list'>";
	$config['full_tag_close'] ="</ul>";
	$config['num_tag_open'] = '<li>';
	$config['num_tag_close'] = '</li>';
	$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
	$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
	$config['next_tag_open'] = "<li>";
	$config['next_tagl_close'] = "</li>";
	$config['prev_tag_open'] = "<li>";
	$config['prev_tagl_close'] = "</li>";
	$config['first_tag_open'] = "<li>";
	$config['first_tagl_close'] = "</li>";
	$config['last_tag_open'] = "<li>";
	$config['last_tagl_close'] = "</li>";
	$this->pagination->initialize($config);
	$page = ($this->uri->segment(4))? $this->uri->segment(4) : 0;
	$data['results']=$this->Employer_model->get_listing($config["per_page"],$page,$session_data['id']);
	
		}
			
			$data["links"] = $this->pagination->create_links();	
			$this->load->view('employer/header',$data);
			$this->load->view('employer/showlistingall',$data);
			$this->load->view('employer/footer',$data);

 }	
function pack_detail($id=null,$activepack=null)
{
	
if($id != null && $activepack != null){
	
if ($this->session->userdata('logged_in') && $this->session->userdata('logged_in')['type']=="EMPLOYER" || 
	$this->session->userdata('logged_in')['oauth_provider']=="google" || $this->session->userdata('logged_in')['oauth_provider']=="facebook") {
				   $session_data = $this->session->userdata('logged_in');
				   $data['email'] = $session_data['email'];
		$data['company_name'] = $session_data['company_name'];
		$data['id'] = $session_data['id'];
	 }

		else
		{ redirect('Employerr/Employer/logout',"refresh");	}
	
		$id = $this->uri->segment(4);
		$data['activepack']=$activepack;
		$data['package_list'] = $this->Employer_model->fetch_activepackage($activepack);
		$this->load->view('employer/header',$data);
		$this->load->view('employer/pack_detail',$data);
		$this->load->view('employer/footer',$data);
		
	}else{ show_404(); }
		
}
function buy_packages($id=null)
{
	if ($this->session->userdata('logged_in') && $this->session->userdata('logged_in')['type']=="EMPLOYER" || 
	$this->session->userdata('logged_in')['oauth_provider']=="google" || $this->session->userdata('logged_in')['oauth_provider']=="facebook") {
				   $session_data = $this->session->userdata('logged_in');
				   $data['email'] = $session_data['email'];
		$data['company_name'] = $session_data['company_name'];
		$data['id'] = $session_data['id'];
	 }

		else
		{ redirect('Employerr/Employer/logout',"refresh");	}
	if($id != null){
		
		$num=$this->Employer_model->forgotpass('package_master',array('pack_id'=>$id,"status"=>1));
		if($num != 0){
			$package_id=$id;
			$product=$this->Employer_model->fetchdatarow('pack_id,pack_description,pack_name,days,listing_no,front_page,hightlighted,html_listing,visitor_count,top_of_category,google_map,price','package_master',array("pack_id"=>$package_id));
		
		$pack_id=$product->pack_id;
		$pack_name=$product->pack_name;
		$pack_description=$product->pack_description;
		$listing_no=$product->listing_no;
		$front_page=$product->front_page;
		$hightlighted=$product->hightlighted;
		$html_listing=$product->html_listing;
		$visitor_count=$product->visitor_count;
		$top_of_category=$product->top_of_category;
		$google_map=$product->google_map;
		$price=$product->price;
		$days=$product->days;
		$employer_fk=$session_data['id'];
		
		$expiry=date('Y-m-d h:i:sa', mktime(date("H"), date("i"), date("s"), date("m"), date("d") + $product->days, date("Y"))); 
		
		$data_array=array('employer_fk'=>$session_data['id'],'package_fk'=>$package_id,'start_date'=>date('Y-m-d h:i:sa'),'expiry_date'=>$expiry,'payment_status'=>"pending",'price'=>$price,'mrp_price'=>$price,'created_date' =>date('Y-m-d h:i:sa'),'status' =>2,"pack_name"=>$pack_name,"pack_description"=>$pack_description,"listing_no"=>$listing_no,"front_page"=>$front_page,"hightlighted"=>$hightlighted,"html_listing"=>$html_listing,"visitor_count"=>$visitor_count,"top_of_category"=>$top_of_category,"google_map"=>$google_map,"days"=>$days);
		
		 $activepack=$this->Employer_model->master_fun_insert("user_active_package",$data_array);
		 $this->package_detail($activepack);
			
		}else{
			
			show_404();
		}
	
	}
	
}
function package_detail($id=null)
{
	
if($id != null){
	
if ($this->session->userdata('logged_in') && $this->session->userdata('logged_in')['type']=="EMPLOYER" || 
	$this->session->userdata('logged_in')['oauth_provider']=="google" || $this->session->userdata('logged_in')['oauth_provider']=="facebook") {
				   $session_data = $this->session->userdata('logged_in');
				   $data['email'] = $session_data['email'];
		$data['company_name'] = $session_data['company_name'];
		$data['id'] = $session_data['id'];
	 }

		else
		{ redirect('Employerr/Employer/logout',"refresh");	}
	
		$data['activepack']=$id;
		$data['package_list'] = $this->Employer_model->fetch_activepackage($id);
		$this->load->view('employer/header',$data);
		$this->load->view('employer/pack_detail',$data);
		$this->load->view('employer/footer',$data);
		
	}else{ show_404(); }
		
}
function live_description()
	{
		if ($this->session->userdata('logged_in') && $this->session->userdata('logged_in')['type']=="EMPLOYER" || 
	$this->session->userdata('logged_in')['oauth_provider']=="google" || $this->session->userdata('logged_in')['oauth_provider']=="facebook") {
		 $session_data = $this->session->userdata('logged_in');
		 //$data['email'] = $session_data['email'];
		$data['company_name'] = $session_data['company_name'];
		$data['id'] = $session_data['id'];
		}
		else
		{
		redirect('Employerr/Employer/logout',"refresh");
		}
		$this->load->helper('form');
		$job_id = $this->uri->segment(4);
		
		$data["job_id"]=$job_id;
		$data['user'] = $this->Employer_model->get_description1($data["job_id"]);
	
		$this->load->view('employer/header',$data);
		$this->load->view('employer/live_description',$data);
		$this->load->view('employer/footer',$data);  
	}
	function close_description()
	{
		if ($this->session->userdata('logged_in') && $this->session->userdata('logged_in')['type']=="EMPLOYER" || 
	$this->session->userdata('logged_in')['oauth_provider']=="google" || $this->session->userdata('logged_in')['oauth_provider']=="facebook") {
		 $session_data = $this->session->userdata('logged_in');
		 //$data['email'] = $session_data['email'];
		$data['company_name'] = $session_data['company_name'];
		$data['id'] = $session_data['id'];
		}
		else
		{
		redirect('Employerr/Employer/logout',"refresh");
		}
		$this->load->helper('form');
		$job_id = $this->uri->segment(4);
		
		$data["job_id"]=$job_id;
		$data['user'] = $this->Employer_model->get_description1($data["job_id"]);
		//print_r($data['user']);die();
		$this->load->view('employer/header',$data);
		$this->load->view('employer/close_description',$data);
		$this->load->view('employer/footer',$data);  
	}
	function description()
	{
		if ($this->session->userdata('logged_in') && $this->session->userdata('logged_in')['type']=="EMPLOYER" || 
	$this->session->userdata('logged_in')['oauth_provider']=="google" || $this->session->userdata('logged_in')['oauth_provider']=="facebook") {
		 $session_data = $this->session->userdata('logged_in');
		 //$data['email'] = $session_data['email'];
		$data['company_name'] = $session_data['company_name'];
		$data['id'] = $session_data['id'];
		}
		else
		{
		redirect('Employerr/Employer/logout',"refresh");
		}
		$this->load->helper('form');
		$job_id = $this->uri->segment(4);
		
		$data["job_id"]=$job_id;
		$data['user'] = $this->Employer_model->get_description1($data["job_id"]);
		//print_r($data['user']);die();
		$this->load->view('employer/header',$data);
		$this->load->view('employer/shortlist_description',$data);
		$this->load->view('employer/footer',$data);  
  }
  
  function website_description()
	{
		if ($this->session->userdata('logged_in') && $this->session->userdata('logged_in')['type']=="EMPLOYER" || 
	$this->session->userdata('logged_in')['oauth_provider']=="google" || $this->session->userdata('logged_in')['oauth_provider']=="facebook") {
		 $session_data = $this->session->userdata('logged_in');
		 //$data['email'] = $session_data['email'];
		$data['company_name'] = $session_data['company_name'];
		$data['id'] = $session_data['id'];
		}
		else
		{
		redirect('Employerr/Employer/logout',"refresh");
		}
		$this->load->helper('form');
		$job_id = $this->uri->segment(4);
		
		$data["job_id"]=$job_id;
		$data['user'] = $this->Employer_model->get_description1($data["job_id"]);
		//echo "<pre>"; print_r($data['user']); die();
		$data['jobsviews']=$this->Employer_model->get_jobsviews($job_id);
		//echo "<pre>"; print_r($data['jobsviews']); die(); 
		
		$this->load->view('employer/header',$data);
		$this->load->view('employer/website_description',$data);
		$this->load->view('employer/footer',$data);  
  }
  
  function job_description()
	{
		if ($this->session->userdata('logged_in') && $this->session->userdata('logged_in')['type']=="EMPLOYER" || 
	$this->session->userdata('logged_in')['oauth_provider']=="google" || $this->session->userdata('logged_in')['oauth_provider']=="facebook") {
		 $session_data = $this->session->userdata('logged_in');
		 //$data['email'] = $session_data['email'];
		$data['company_name'] = $session_data['company_name'];
		$data['id'] = $session_data['id'];
		}
		else
		{
		redirect('Employerr/Employer/logout',"refresh");
		}
		$this->load->helper('form');
		$job_id = $this->uri->segment(4);
		
		$data["job_id"]=$job_id;
		$data['user'] = $this->Employer_model->get_description($data["job_id"]);
		//print_r($data['user']);die();
		$this->load->view('employer/header',$data);
		$this->load->view('employer/description',$data);
		$this->load->view('employer/footer',$data);  
  }
  
  function candidate_description()
	{
		if ($this->session->userdata('logged_in') && $this->session->userdata('logged_in')['type']=="EMPLOYER" || 
	$this->session->userdata('logged_in')['oauth_provider']=="google" || $this->session->userdata('logged_in')['oauth_provider']=="facebook") {
		 $session_data = $this->session->userdata('logged_in');
		 //$data['email'] = $session_data['email'];
		$data['company_name'] = $session_data['company_name'];
		$data['id'] = $session_data['id'];
		}
		else
		{
		redirect('Employerr/Employer/logout',"refresh");
		}
		$this->load->helper('form');
		$id = $this->uri->segment(4);
		$data['user'] = $this->Employer_model->get_candidate_desc($id);
		//var_dump($data['user']);
		$this->load->view('employer/header',$data);
		$this->load->view('employer/candidate_description',$data);
		$this->load->view('employer/footer',$data);  
  }
  
	public function showjobs()
	{
		if ($this->session->userdata('logged_in') && $this->session->userdata('logged_in')['type']=="EMPLOYER" || 
	$this->session->userdata('logged_in')['oauth_provider']=="google" || $this->session->userdata('logged_in')['oauth_provider']=="facebook") {
            $session_data = $this->session->userdata('logged_in');
            $data['email'] = $session_data['email'];
			  $data['company_name'] = $session_data['company_name'];
			    $data['id'] = $session_data['id'];
				}
				else
		{
			redirect('Employerr/Employer/logout',"refresh");
		}
		
		$search=$this->input->get('search');
		 $status=$this->input->get('status'); 
		if($search != "" || $status != ""){
		
		$config["base_url"] = base_url() . "index.php/Employerr/Employer/showjobs?search=$search&status=$status";
		$config["total_rows"] = $this->Employer_model->jobs_countsearch($data['id'],$search,$status);
		$config["page_query_string"] = TRUE;
		$config["per_page"] = 20;
		$config["uri_segment"] = 4;
		$config['full_tag_open'] = "<ul class='pagination pagination-sm pro-page-list'>";
		$config['full_tag_close'] ="</ul>";
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
		$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
		$config['next_tag_open'] = "<li>";
		$config['next_tagl_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";
		$config['prev_tagl_close'] = "</li>";
		$config['first_tag_open'] = "<li>";
		$config['first_tagl_close'] = "</li>";
		$config['last_tag_open'] = "<li>";
		$config['last_tagl_close'] = "</li>"; 
		$this->pagination->initialize($config);
		$page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
		$data['results'] = $this->Employer_model->get_jobssearch($config["per_page"],$page,$data['id'],$search,$status);
		
		}else{
		
		$config["base_url"] = base_url() . "index.php/Employerr/Employer/showjobs";
		$config["total_rows"] = $this->Employer_model->jobs_count($data['id']);
		$config["per_page"] = 20;
		$config["uri_segment"] = 4;
		$config['full_tag_open'] = "<ul class='pagination pagination-sm pro-page-list'>";
		$config['full_tag_close'] ="</ul>";
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
		$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
		$config['next_tag_open'] = "<li>";
		$config['next_tagl_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";
		$config['prev_tagl_close'] = "</li>";
		$config['first_tag_open'] = "<li>";
		$config['first_tagl_close'] = "</li>";
		$config['last_tag_open'] = "<li>";
		$config['last_tagl_close'] = "</li>"; 
		$this->pagination->initialize($config);
		$page = ($this->uri->segment(4))? $this->uri->segment(4) : 0;
		$data['results'] = $this->Employer_model->get_jobs($config["per_page"],$page,$data['id']);
		
		}
				$data['results1'] = $this->Employer_model->jobedit_request();
				
			/* echo "<pre>"; print_r($data['results']); die();	 */
				
				$cnt=0;
				foreach($data['results'] as $key){
				$results2 = $this->Employer_model->app_received($key["job_id"]);	
				$data['results'][$cnt]["new_count"]=$results2[0]["count1"];
				$cnt++;
				}
				
		$data["links"] = $this->pagination->create_links();	
		$this->load->view('employer/header',$data);
		$this->load->view('employer/showjobs',$data);
		$this->load->view('employer/footer',$data);
	}
	
	public function close_job() {
	if ($this->session->userdata('logged_in') && $this->session->userdata('logged_in')['type']=="EMPLOYER" || 
	$this->session->userdata('logged_in')['oauth_provider']=="google" || $this->session->userdata('logged_in')['oauth_provider']=="facebook") {
            $session_data = $this->session->userdata('logged_in');
            $data['email'] = $session_data['email'];
			  $data['company_name'] = $session_data['company_name'];
			    $data['id'] = $session_data['id'];
				}
				else
		{
			redirect('Employerr/Employer/logout',"refresh");
		}
       $id = $this->uri->segment(4);
       if (empty($id)) {
           show_404();
       }
		$this->Employer_model->close_job($id);
	$data['results1'] = $this->Employer_model->fetch_job_details1($id);
	 $data['results2'] = $this->Employer_model->pack_detail($id);
	 

						
						$subject="Close Job";
						$body="<html><body><h3>Dear,".$data['results1'][0]['company_name']." <br/>Your Job Is Successfully Closed</h3><u><h4>Job Details</h4></u><table border='2'><tr><th>Job Title</th><th>Category</th><th>Company</th><th>Job Type</th><th>Job Position</th><th>Payment Type</th><th>Amount</th><th>Location</th><th>Package Name</th><th>Price</th></tr><tr><td>".$data['results1'][0]["title"]."</td><td>".$data['results1'][0]["category_name"]."</td><td>".$data['results1'][0]["company_name"]."</td><td>".$data['results1'][0]["display"]."</td><td>".$data['results1'][0]["position"]."</td><td>".$data['results1'][0]["payment"]."</td><td>".$data['results1'][0]["amount"]."</td><td>".$data['results1'][0]["location"]."</td><td>".$data['results2'][0]["pack_name"]."</td><td>".$data['results2'][0]["price"]."</td></tr></table></body></html>";
						$this->load->helper(array('swift'));
						send_mail($data['results1'][0]['email'],$subject,$body);
						
							$this->session->set_flashdata("email_sent","Email Sent Successfully.");
						
						
       redirect("Employerr/Employer/showjobs","refresh");
   }
	
	
	public function delete_job() {
	if ($this->session->userdata('logged_in') && $this->session->userdata('logged_in')['type']=="EMPLOYER" || 
	$this->session->userdata('logged_in')['oauth_provider']=="google" || $this->session->userdata('logged_in')['oauth_provider']=="facebook") {
            $session_data = $this->session->userdata('logged_in');
            $data['email'] = $session_data['email'];
			  $data['company_name'] = $session_data['company_name'];
			    $data['id'] = $session_data['id'];
				}
				else
		{
			redirect('Employerr/Employer/logout',"refresh");
		}
        $id = $this->uri->segment(4);
        if (empty($id)) {
            show_404();
        }
		$this->Employer_model->delete_job($id);
        redirect("Employerr/Employer/showjobs","refresh");
    }
	
	public function delete_listed_job() {
	if ($this->session->userdata('logged_in') && $this->session->userdata('logged_in')['type']=="EMPLOYER" || 
	$this->session->userdata('logged_in')['oauth_provider']=="google" || $this->session->userdata('logged_in')['oauth_provider']=="facebook") {
            $session_data = $this->session->userdata('logged_in');
            $data['email'] = $session_data['email'];
			  $data['company_name'] = $session_data['company_name'];
			    $data['id'] = $session_data['id'];
				}
				else
		{
			redirect('Employerr/Employer/logout',"refresh");
		}
        $id = $this->uri->segment(4);
        if (empty($id)) {
            show_404();
        }
		$this->Employer_model->delete_listed_job($id);
        redirect("Employerr/Employer/show_listing","refresh");
    }

	
	

	public function editjobs()
	{
		if ($this->session->userdata('logged_in') && $this->session->userdata('logged_in')['type']=="EMPLOYER" || 
	$this->session->userdata('logged_in')['oauth_provider']=="google" || $this->session->userdata('logged_in')['oauth_provider']=="facebook") {
            $session_data = $this->session->userdata('logged_in');
            $data['email'] = $session_data['email'];
			$data['company_name'] = $session_data['company_name'];
			$data['id'] = $session_data['id'];
       }
		else
				{
					redirect('Employerr/Employer/logout',"refresh");
				}
		$id = $this->uri->segment(4);
		$userpack=$this->Employer_model->getfetch_jobs($id);
		
		$pack_fk=$userpack['pack_fk'];
		$num=$this->Employer_model->forgotpass('user_active_package',array("id"=>$pack_fk,"employer_fk"=>$data['id'],"expiry_date >="=>date('Y-m-d')));
		if($num != 0){ 
		$this->load->helper('form');
        $this->load->library('form_validation');
		$data['user'] =$userpack;
		//$data['query'] = $this->Employer_model->jobedit_approve($id);
		$data['category_list'] = $this->Employer_model->get_category11();
		$data['package_list'] = $this->Employer_model->get_package();
		//$data['company_list'] = $this->Employer_model->get_company();
		$this->form_validation->set_rules('title','Job Title','required');
		$this->form_validation->set_rules('job_description','Job Description','required');
		/*$this->form_validation->set_rules('category_name','Category Name','required');
		$this->form_validation->set_rules('display','Display Type','required');
		$this->form_validation->set_rules('position','Position Type','required');
		$this->form_validation->set_rules('payment','Payment Type','required');*/
		
		if ($this->form_validation->run() == FALSE)
        { 
			$this->load->view('employer/header',$data);
			$this->load->view('employer/editjobs',$data); 
            $this->load->view('employer/footer',$data);
		}
		else
		{ 
			$attachment = $_FILES["attachment"]["name"];
			$file_name="";
			if ($_FILES["attachment"]["name"]) {
                $config['upload_path'] = './test/';
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
				$_FILES["attachment"]["name"] = str_replace(' ', '_', $_FILES["attachment"]["name"]);
                $config['file_name'] =$_FILES["attachment"]["name"];
                $img = $this->load->library('upload', $config);
				$this->upload->initialize($config);
                if (!$this->upload->do_upload('attachment')) {
                    $error = array('error' => $this->upload->display_errors());
                    $this->session->set_flashdata("unsucess", array($this->upload->display_errors()));
                    redirect("Employerr/Employer/post_job", "refresh");
                } else {
                    $data = array('upload_data' => $this->upload->data());
                    $file_name = $data["upload_data"]["file_name"];
                }
            }
			if(!empty($file_name)){ $attachment = $_FILES["attachment"]["name"];}else{ $attachment =$this->input->post('attachment');}
			
			$data['query'] = $this->Employer_model->jobinsertedit_approve($id,$attachment);
		
			  $this->session->set_flashdata("success",$this->data['lang']->listing_updated_succ_msg);
			redirect("Employerr/Employer/show_listing","refresh");
			
		}
		 }else{ show_404(); } 
	}
	
	
	public function fetch_package()
	{
		
		$id = $this->uri->segment(4);
		$data['package_list'] = $this->Employer_model->pack_detail1($id);
		//print_r($data['package_list']);
	echo json_encode($data['package_list']);
	}
	
	public function inserteditjobs()
	{
		if ($this->session->userdata('logged_in') && $this->session->userdata('logged_in')['type']=="EMPLOYER" || 
	  $this->session->userdata('logged_in')['oauth_provider']=="google" || $this->session->userdata('logged_in')['oauth_provider']=="facebook") {
            $session_data = $this->session->userdata('logged_in');
            $data['email'] = $session_data['email'];
			$data['company_name'] = $session_data['company_name'];
			$data['id'] = $session_data['id'];
       }
		else
				{
					redirect('Employerr/Employer/logout',"refresh");
				}
		$id = $this->uri->segment(4);
		$this->load->helper('form');
        $this->load->library('form_validation');
		$data['user'] = $this->Employer_model->fetch_jobs($id);
		//$data['query'] = $this->Employer_model->jobedit_approve($id);
		$data['category_list'] = $this->Employer_model->get_category11();
		$data['package_list'] = $this->Employer_model->get_package();
		//$data['company_list'] = $this->Employer_model->get_company();
		$this->form_validation->set_rules('title','Job Title','required');
		$this->form_validation->set_rules('job_description','Job Description','required');
		/*$this->form_validation->set_rules('category_name','Category Name','required');
		$this->form_validation->set_rules('display','Display Type','required');
		$this->form_validation->set_rules('position','Position Type','required');
		$this->form_validation->set_rules('payment','Payment Type','required');*/
		
		if ($this->form_validation->run() == FALSE)
        {
			$this->load->view('employer/header',$data);
			$this->load->view('employer/inserteditjobs',$data); 
            $this->load->view('employer/footer',$data);
		}
		else
		{
			$attachment = $_FILES["attachment"]["name"];
			$file_name="";
			if ($_FILES["attachment"]["name"]) {
                $config['upload_path'] = './test/';
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
				$_FILES["attachment"]["name"] = str_replace(' ', '_', $_FILES["attachment"]["name"]);
                $config['file_name'] =$_FILES["attachment"]["name"];
                $img = $this->load->library('upload', $config);
				$this->upload->initialize($config);
                if (!$this->upload->do_upload('attachment')) {
                    $error = array('error' => $this->upload->display_errors());
                    $this->session->set_flashdata("unsucess", array($this->upload->display_errors()));
                    redirect("Employerr/Employer/post_job", "refresh");
                } else {
                    $data = array('upload_data' => $this->upload->data());
                    $file_name = $data["upload_data"]["file_name"];
                }
            }
			if(!empty($file_name)){
			 $attachment = $_FILES["attachment"]["name"];
			}else{
				
				$attachment =$this->input->post('attachment');
			}
			$data['query'] = $this->Employer_model->jobinsertedit_approve($id,$attachment);
			 $this->session->set_flashdata("success",$this->data['lang']->jobedit_req_send_msg);
			redirect("Employerr/Employer/showjobs","refresh");
			
		}
	}
	

	
	function shortlist()
	{
		
	if ($this->session->userdata('logged_in') && $this->session->userdata('logged_in')['type']=="EMPLOYER" || 
	$this->session->userdata('logged_in')['oauth_provider']=="google" || $this->session->userdata('logged_in')['oauth_provider']=="facebook") {
            $session_data = $this->session->userdata('logged_in');
            $data['email'] = $session_data['email'];
			  $data['company_name'] = $session_data['company_name'];
			    $data['id'] = $session_data['id'];			
				}
				else
		{
			redirect('Employerr/Employer/logout',"refresh");
		}
		
		
		$search=$this->input->get('search');
		 $status=$this->input->get('status'); 
		if($search != "" || $status != ""){
		
		$config["base_url"] = base_url() . "index.php/Employerr/Employer/shortlist?search=$search&status=$status";
		$config["total_rows"] = $this->Employer_model->shortlist_count($data['id'],$search,$status);
		$config["page_query_string"] = TRUE;
		$config["per_page"] = 20;
		$config["uri_segment"] = 4;
		$config['full_tag_open'] = "<ul class='pagination pagination-sm pro-page-list'>";
		$config['full_tag_close'] ="</ul>";
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
		$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
		$config['next_tag_open'] = "<li>";
		$config['next_tagl_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";
		$config['prev_tagl_close'] = "</li>";
		$config['first_tag_open'] = "<li>";
		$config['first_tagl_close'] = "</li>";
		$config['last_tag_open'] = "<li>";
		$config['last_tagl_close'] = "</li>"; 
		$this->pagination->initialize($config);
		$page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
		$data['results'] = $this->Employer_model->shortlist($config["per_page"],$page,$data['id'],$search,$status);
		
		}else{
		$config["base_url"] = base_url() . "index.php/Employerr/Employer/shortlist";
		$config["total_rows"] = $this->Employer_model->shortlist_count($session_data['id']);
			
		$config["per_page"] = 20;
		$config["uri_segment"] = 4;
		$config['full_tag_open'] = "<ul class='pagination pagination-sm pro-page-list'>";
		$config['full_tag_close'] ="</ul>";
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
		$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
		$config['next_tag_open'] = "<li>";
		$config['next_tagl_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";
		$config['prev_tagl_close'] = "</li>";
		$config['first_tag_open'] = "<li>";
		$config['first_tagl_close'] = "</li>";
		$config['last_tag_open'] = "<li>";
		$config['last_tagl_close'] = "</li>"; 
		$this->pagination->initialize($config);
		$page = ($this->uri->segment(4))? $this->uri->segment(4) : 0;
	    $data['results'] = $this->Employer_model->shortlist($config["per_page"],$page,$session_data['id']);
		
		}
		//echo "<pre>";	
		//print_r($data['results']); die();
		$data["links"] = $this->pagination->create_links();	
				$this->load->view('employer/header',$data);
				$this->load->view('employer/shortlist',$data);
				$this->load->view('employer/footer',$data);
	
	}
	
	public function hire() 
{
	if ($this->session->userdata('logged_in') && $this->session->userdata('logged_in')['type']=="EMPLOYER" || 
	$this->session->userdata('logged_in')['oauth_provider']=="google" || $this->session->userdata('logged_in')['oauth_provider']=="facebook") {
            $session_data = $this->session->userdata('logged_in');
            $data['email'] = $session_data['email'];
			  $data['company_name'] = $session_data['company_name'];
			    $data['id'] = $session_data['id'];			
				}
				else
		{
			redirect('Employerr/Employer/logout',"refresh");
		}
        $id = $this->uri->segment(4);
		
        if (empty($id)) {
            show_404();
    
	}
			$this->Employer_model->hire($id);
			$data['results1'] = $this->Employer_model->get_email($id);
			$data['results2'] = $this->Employer_model->get_approve_job($id);

						$getadata1 = $this->db->get_where('email_setting',array('email_id' =>20));
						$getadata=$getadata1->row();
						$body=$getadata->message;
						$subject=$getadata->subject;
						$body=str_replace('{{name}}',$data['results1'][0]['name'],$body);
						$body=str_replace('{{company_name}}',$data['results2'][0]['company_name'],$body);
						 $body=str_replace('{{title}}',$data['results2'][0]["title"],$body);
						$body=str_replace('{{category_name}}',$data['results2'][0]["category_name"], $body);
						$body=str_replace('{{display}}',$data['results2'][0]["display"], $body);
						$body=str_replace('{{position}}',$data['results2'][0]["position"], $body);
						$body=str_replace('{{payment}}',$data['results2'][0]["payment"], $body);
						$body=str_replace('{{location}}',$data['results2'][0]["location"], $body);
						
						$this->load->helper(array('swift'));
						send_mail($data['results1'][0]['email'],$subject,$body);
						
						
						$this->session->set_flashdata("email_sent","Email Sent Successfully.");
						$this->session->set_flashdata("success",$this->data['lang']->hire_success_msg);
		redirect("Employerr/Employer/shortlist","refresh");
    }
	
	
	public function undohire() 
{
	if ($this->session->userdata('logged_in') && $this->session->userdata('logged_in')['type']=="EMPLOYER" || 
	$this->session->userdata('logged_in')['oauth_provider']=="google" || $this->session->userdata('logged_in')['oauth_provider']=="facebook") {
            $session_data = $this->session->userdata('logged_in');
            $data['email'] = $session_data['email'];
			  $data['company_name'] = $session_data['company_name'];
			    $data['id'] = $session_data['id'];			
				}
				else
		{
			redirect('Employerr/Employer/logout',"refresh");
		}
        $id = $this->uri->segment(4);
		//print_r($id);
        if (empty($id)) {
            show_404();
    
	}
			$this->Employer_model->undohire($id);
			$data['results1'] = $this->Employer_model->get_email($id);
			$data['results2'] = $this->Employer_model->get_approve_job($id);
		
						$getadata1 = $this->db->get_where('email_setting',array('email_id' =>21));
						$getadata=$getadata1->row();
						$body=$getadata->message;
						$subject=$getadata->subject;
						$body=str_replace('{{name}}',$data['results1'][0]['name'],$body);
						$body=str_replace('{{company_name}}',$data['results2'][0]['company_name'],$body);
						 $body=str_replace('{{title}}',$data['results2'][0]["title"],$body);
						$body=str_replace('{{category_name}}',$data['results2'][0]["category_name"], $body);
						$body=str_replace('{{display}}',$data['results2'][0]["display"], $body);
						$body=str_replace('{{position}}',$data['results2'][0]["position"], $body);
						$body=str_replace('{{payment}}',$data['results2'][0]["payment"], $body);
						$body=str_replace('{{location}}',$data['results2'][0]["location"], $body);
                                       
			
						
						$this->load->helper(array('swift'));
						send_mail($data['results1'][0]['email'],$subject,$body);
						
							$this->session->set_flashdata("email_sent","Email Sent Successfully.");
						

						$this->session->set_flashdata("success",$this->data['lang']->unhire_success_msg);
						redirect("Employerr/Employer/shortlist","refresh");
    }
	
	function employer_email1($email)
	{  
		 $result = $this->Employer_model->check_employer_email1($email);
		$email = $this->input->post('email');
		if($result !="0")
		{
			return TRUE;
			
		}
		else
		{
			
			$this->form_validation->set_message('employer_email1', 'Email is Not Exist OR Not Valid.');
            return false;
		}
		
		
	}
	
	
	function forgot()
	{
		$this->load->library('form_validation');
        if(empty($this->input->post('email'))){
        $this->form_validation->set_rules('email', 'Email','required');
		}
		elseif(!empty($this->input->post('email'))){
			$this->form_validation->set_rules('email', 'Email','valid_email|callback_employer_email1');
			}
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('employer/header'); 
			$this->load->view('employer/forgot_pass_view');
			$this->load->view('employer/footer'); 
		}
		else
		{
			 $email = $this->input->post('email');
			 $check_email = $this->Employer_model->forgotpass("employer_master", array("email" => $email, 'status' => '1'));
			 if ($check_email == '0') {
                $this->session->set_flashdata('fail',$this->data['lang']->forgot_msg);
                redirect('Employerr/Employer/forgot');
				}
				$this->load->helper('string');
            $rs = random_string('alnum', 5);
            $data1 = array(
                'rs' => $rs
            );
            $this->db->where('email', $email);
            $this->db->where('status', '1');
            $this->db->update('employer_master', $data1);
			
			$getadata1 = $this->db->get_where('email_setting',array('email_id' =>25));
			$getadata=$getadata1->row();
			$body=$getadata->message;
			$subject=$getadata->subject;
			$link=base_url()."index.php/Employerr/Employer/ucp_change_pass1/".$rs;
			$body=str_replace('{{link}}',$link, $body);
			
			$this->load->helper(array('swift'));
			send_mail($email,$subject,$body);
						
            $this->session->set_flashdata('success',$this->data['lang']->change_pwd_msg);
            redirect('Employerr/Employer/login');
            
			
			
		}
	}
	
	
		public function ucp_change_pass1() {
	
			 $data['code'] = $this->uri->segment(4); 
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $this->form_validation->set_rules('retype', 'Password Confirmation', 'trim|required|matches[password]');
        if ($this->form_validation->run() == FALSE) {
           $this->load->view('employer/header',$data);
            $this->load->view('employer/forgotchangepwd',$data);
           $this->load->view('employer/footer',$data);
        } else {
            $code = $this->uri->segment(4); 
            $query = $this->db->get_where('employer_master', array('rs' => $code));
            if ($query->num_rows() == 0) {
                show_error('Sorry!!! Invalid Request!');
            } else {
                $pass = $this->input->post('password');
                $retype = $this->input->post('retype');
                if ($pass == $retype) {
                    $data = array(
                        'password' => $this->input->post('password'),
                        'rs' => ''
                    ); 
                   $where = $this->db->where('status', '1');	
                    $this->db->where('rs', $code);
				
                    $this->db->update('employer_master', $data);

                    $this->session->set_flashdata('success',$this->data['lang']->your_pass_is_change);
                    redirect('Employerr/Employer', 'refresh');
                } else {

                    echo "<script>alert('Password Do Not Match!')</script>";
                }
            }
        }
    } 
	
	
	 function terms()
	{
	
		if ($this->session->userdata('logged_in') && $this->session->userdata('logged_in')['type']=="EMPLOYER" || 
	$this->session->userdata('logged_in')['oauth_provider']=="google" || $this->session->userdata('logged_in')['oauth_provider']=="facebook") {
            $session_data = $this->session->userdata('logged_in');          
			$data['company_name'] = $session_data['company_name'];
			$data['id'] = $session_data['id'];
            $data['email'] = $session_data['email']; 
				
        }
		/*else
		{
			redirect('Employerr/Employer/logout',"refresh");
		}*/
		$data['results'] = $this->Employer_model->get_terms();
		$this->load->view('employer/header',$data);
		$this->load->view('employer/terms');
		$this->load->view('employer/footer');
	}
	
	
	function aboutus()
	{
		if ($this->session->userdata('logged_in') && $this->session->userdata('logged_in')['type']=="EMPLOYER" || 
	$this->session->userdata('logged_in')['oauth_provider']=="google" || $this->session->userdata('logged_in')['oauth_provider']=="facebook") {
            $session_data = $this->session->userdata('logged_in');          
			$data['company_name'] = $session_data['company_name'];
			$data['id'] = $session_data['id'];
            $data['email'] = $session_data['email']; 
				
        }
		/*else
		{
			redirect('Employerr/Employer/logout',"refresh");
		}*/
		if ($this->session->userdata('logged_in')) {
		$this->load->view('employer/header',$data);
		
		}
		else
		{
			$this->load->view('employer/header');
		}
		$this->load->view('employer/aboutus');
		$this->load->view('employer/footer');
	}
	
	function contact_us()
	{
		if ($this->session->userdata('logged_in') && $this->session->userdata('logged_in')['type']=="EMPLOYER" || 
	$this->session->userdata('logged_in')['oauth_provider']=="google" || $this->session->userdata('logged_in')['oauth_provider']=="facebook") {
            $session_data = $this->session->userdata('logged_in');          
			$data['company_name'] = $session_data['company_name'];
			$data['id'] = $session_data['id'];
            $data['email'] = $session_data['email']; 
			
        }
		/*else
		{
			redirect('Employerr/Employer/logout',"refresh");
		}*/
			$data['random1'] = rand('0','4');
			$data['random2'] = rand('5','9');
			$data['random3'] = $data['random1'] + $data['random2'];
		 $this->load->helper('form');
        $this->load->library('form_validation');
		$this->form_validation->set_rules('name','Name','required');
       
        $this->form_validation->set_rules('message','Message','required');
       
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
      
       $this->form_validation->set_rules('sum','Sum','required|callback_checksum');
	   $this->form_validation->set_rules('phone','Phone No','required|numeric');
	   
		if($this->form_validation->run() == FALSE)
		{
		if ($this->session->userdata('logged_in')) {
		$this->load->view('employer/header',$data);
		}
		else
		{
			$this->load->view('employer/header');
		}
		$this->load->view('employer/contactus',$data);
		$this->load->view('employer/footer');
		}
		else
		{
			$this->Employer_model->contact_us();
			
			$name = $this->input->post('name');
			$email = $this->input->post('email');
			$phone = $this->input->post('phone');
			$message = $this->input->post('message');
			$created_date =$this->input->post('created_date');
			$getadata1 = $this->db->get_where('email_setting',array('email_id' =>23));
			$getadata=$getadata1->row();
			$body=$getadata->message;
			$subject=$getadata->subject;
			$body=str_replace('{{name}}',$name,$body);
            $body=str_replace('{{email}}',$email,$body);
			 $body=str_replace('{{phone}}',$phone,$body);
            $body=str_replace('{{message}}',$message, $body);
			$body=str_replace('{{created_date}}',$created_date, $body);
			
			
			$this->load->helper(array('swift'));
			send_mail('info@powercontracts.org',$subject,$body);
			 
        $this->session->set_flashdata("success",$this->data['lang']->your_req_is_recorder_msg);
			redirect('Employerr/Employer/contact_us');
		}
	}
	
	function checksum(){
	$val1=$this->input->post("sum");
	$val2=$this->input->post("sum1");
	if($val1==$val2){
	return true;
	}else{
	$this->form_validation->set_message('checksum', 'Invalid total.');
	return false;
	}
	}
	
		public function view_openjobs()
	{
	if ($this->session->userdata('logged_in') && $this->session->userdata('logged_in')['type']=="EMPLOYER" || 
	$this->session->userdata('logged_in')['oauth_provider']=="google" || $this->session->userdata('logged_in')['oauth_provider']=="facebook") {
			   $session_data = $this->session->userdata('logged_in');
			   $data['email'] = $session_data['email'];
	 $data['company_name'] = $session_data['company_name'];
	   $data['id'] = $session_data['id'];
	}
	else
	{
	redirect('Employerr/Employer/logout',"refresh");
	}
	
	$search=$this->input->get('search');
	
		if($search != ""){
		
		$config["base_url"] = base_url()."index.php/Employerr/Employer/view_openjobs?search=$search";
		$config["total_rows"] = $this->Employer_model->view_openjobscount($data['id'],$search);
		$config["page_query_string"] = TRUE;
		$config["per_page"] = 20;
		$config["uri_segment"] = 4;
		$config['full_tag_open'] = "<ul class='pagination pagination-sm pro-page-list'>";
		$config['full_tag_close'] ="</ul>";
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
		$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
		$config['next_tag_open'] = "<li>";
		$config['next_tagl_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";
		$config['prev_tagl_close'] = "</li>";
		$config['first_tag_open'] = "<li>";
		$config['first_tagl_close'] = "</li>";
		$config['last_tag_open'] = "<li>";
		$config['last_tagl_close'] = "</li>"; 
		$this->pagination->initialize($config);
		$page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
		$data['results'] = $this->Employer_model->view_openjobs($config["per_page"],$page,$data['id'],$search);
		
		}else{
	$config["base_url"] = base_url() . "index.php/Employerr/Employer/view_openjobs";
	$config["total_rows"] = $this->Employer_model->view_openjobscount($data['id']);
	$config["per_page"] = 10;
	$config["uri_segment"] = 4;
	$config['full_tag_open'] = "<ul class='pagination pagination-sm pro-page-list'>";
	$config['full_tag_close'] ="</ul>";
	$config['num_tag_open'] = '<li>';
	$config['num_tag_close'] = '</li>';
	$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
	$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
	$config['next_tag_open'] = "<li>";
	$config['next_tagl_close'] = "</li>";
	$config['prev_tag_open'] = "<li>";
	$config['prev_tagl_close'] = "</li>";
	$config['first_tag_open'] = "<li>";
	$config['first_tagl_close'] = "</li>";
	$config['last_tag_open'] = "<li>";
	$config['last_tagl_close'] = "</li>";
	$this->pagination->initialize($config);
	$page = ($this->uri->segment(4))? $this->uri->segment(4) : 0;
	$data['results'] = $this->Employer_model->view_openjobs($config["per_page"],$page,$data['id'],'');
	
		}
		$data["links"] = $this->pagination->create_links();
	$data['results1'] = $this->Employer_model->jobedit_request();
	$this->load->view('employer/header',$data);
	$this->load->view('employer/view_openjobs',$data);
	$this->load->view('employer/footer',$data);
	
	 
	}
	
		public function view_closejobs()
	{
	if ($this->session->userdata('logged_in') && $this->session->userdata('logged_in')['type']=="EMPLOYER" || 
	$this->session->userdata('logged_in')['oauth_provider']=="google" || $this->session->userdata('logged_in')['oauth_provider']=="facebook") {
			   $session_data = $this->session->userdata('logged_in');
			   $data['email'] = $session_data['email'];
	 $data['company_name'] = $session_data['company_name'];
	   $data['id'] = $session_data['id'];
	}
	else
	{
	redirect('Employerr/Employer/logout',"refresh");
	}
	
	$search=$this->input->get('search');
	
		if($search != ""){
		
		$config["base_url"] = base_url()."index.php/Employerr/Employer/view_openjobs?search=$search";
		$config["total_rows"] = $this->Employer_model->view_closejobscount($data['id'],$search);
		$config["page_query_string"] = TRUE;
		$config["per_page"] = 20;
		$config["uri_segment"] = 4;
		$config['full_tag_open'] = "<ul class='pagination pagination-sm pro-page-list'>";
		$config['full_tag_close'] ="</ul>";
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
		$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
		$config['next_tag_open'] = "<li>";
		$config['next_tagl_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";
		$config['prev_tagl_close'] = "</li>";
		$config['first_tag_open'] = "<li>";
		$config['first_tagl_close'] = "</li>";
		$config['last_tag_open'] = "<li>";
		$config['last_tagl_close'] = "</li>"; 
		$this->pagination->initialize($config);
		$page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
		$data['results'] = $this->Employer_model->view_closejobs($config["per_page"],$page,$data['id'],$search);
		
		}else{
			
	$config["base_url"] = base_url() . "index.php/Employerr/Employer/view_closejobs";
	$config["total_rows"] = $this->Employer_model->view_closejobscount($data['id']);
	$config["per_page"] = 20;
	$config["uri_segment"] = 4;
	$config['full_tag_open'] = "<ul class='pagination pagination-sm pro-page-list'>";
	$config['full_tag_close'] ="</ul>";
	$config['num_tag_open'] = '<li>';
	$config['num_tag_close'] = '</li>';
	$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
	$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
	$config['next_tag_open'] = "<li>";
	$config['next_tagl_close'] = "</li>";
	$config['prev_tag_open'] = "<li>";
	$config['prev_tagl_close'] = "</li>";
	$config['first_tag_open'] = "<li>";
	$config['first_tagl_close'] = "</li>";
	$config['last_tag_open'] = "<li>";
	$config['last_tagl_close'] = "</li>";
	$this->pagination->initialize($config);
	$page = ($this->uri->segment(4))? $this->uri->segment(4) : 0;
	$data['results'] = $this->Employer_model->view_closejobs($config["per_page"],$page,$data['id']);
	
		}
	$data["links"] = $this->pagination->create_links();
	$this->load->view('employer/header',$data);
	$this->load->view('employer/view_closejobs',$data);
	$this->load->view('employer/footer',$data);
	}
	
	  function addtermdata(){
			$data = array(
			'company_name' => $this->input->post('company_name'),
            'owner' => $this->input->post('owner'),
            'description' => $this->input->post('description'),
            'contact' => $this->input->post('contact'),
			'address' => $this->input->post('address'),
            'email' => $this->input->post('email'),
            'website' => $this->input->post('website')
  );
   
 $this->session->set_userdata("termdata",$data); 
	
 }
  function package_all()
	{
		if ($this->session->userdata('logged_in') && $this->session->userdata('logged_in')['type']=="EMPLOYER" || 
	$this->session->userdata('logged_in')['oauth_provider']=="google" || $this->session->userdata('logged_in')['oauth_provider']=="facebook") {
				   $session_data = $this->session->userdata('logged_in');
				   $data['email'] = $session_data['email'];
		$data['company_name'] = $session_data['company_name'];
		$data['id'] = $session_data['id'];
			   }
		else
		{
		redirect('Employerr/Employer/logout',"refresh");
		}
	$jobstype=$this->input->get("jobs");	
	$config["base_url"] = base_url()."index.php/Employerr/Employer/package_all";
	$config["total_rows"] = $this->Employer_model->get_userpackgescount($session_data['id'],$jobstype);
	$config["per_page"] = 10;
	$config["uri_segment"] = 4;
	$config['full_tag_open'] = "<ul class='pagination pagination-sm pro-page-list'>";
	$config['full_tag_close'] ="</ul>";
	$config['num_tag_open'] = '<li>';
	$config['num_tag_close'] = '</li>';
	$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
	$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
	$config['next_tag_open'] = "<li>";
	$config['next_tagl_close'] = "</li>";
	$config['prev_tag_open']="<li>";
	$config['prev_tagl_close']= "</li>";
	$config['first_tag_open']="<li>";
	$config['first_tagl_close']="</li>";
	$config['last_tag_open'] = "<li>";
	$config['last_tagl_close'] = "</li>";
	$this->pagination->initialize($config);
	$page = ($this->uri->segment(4))? $this->uri->segment(4) : 0;
	$data['results']=$this->Employer_model->get_userpackges($config["per_page"],$page,$session_data['id'],$jobstype);
	$data["links"] = $this->pagination->create_links();	
	$data['totaldue']=$this->Employer_model->duepackageprice($data['id']);
			
			$this->load->view('employer/header',$data);
			$this->load->view('employer/userpackage_payments',$data);
			$this->load->view('employer/footer',$data);
	}
function jobs_packge($id=null)
	{
if($id != null){

		if ($this->session->userdata('logged_in') && $this->session->userdata('logged_in')['type']=="EMPLOYER" || 
	$this->session->userdata('logged_in')['oauth_provider']=="google" || $this->session->userdata('logged_in')['oauth_provider']=="facebook") {
				   $session_data = $this->session->userdata('logged_in');
				   $data['email'] = $session_data['email'];
		$data['company_name'] = $session_data['company_name'];
		$data['id'] = $session_data['id'];
			   }

		else
		{
		redirect('Employerr/Employer/logout',"refresh");
		}
			
	$num=$this->Employer_model->forgotpass('user_active_package',array("id"=>$id,"employer_fk"=>$data['id'],"expiry_date >="=>date('Y-m-d')));
if($num != 0){ $data['statuspackge']='1'; }else{ $data['statuspackge']='0'; }	
	$config["base_url"] = base_url() . "index.php/Employerr/Employer/show_listing";
	$config["total_rows"] = $this->Employer_model->get_listing_packgecount($session_data['id'],$id);
	$config["per_page"] = 10;
	$config["uri_segment"] = 4;
	$config['full_tag_open'] = "<ul class='pagination pagination-sm pro-page-list'>";
	$config['full_tag_close'] ="</ul>";
	$config['num_tag_open'] = '<li>';
	$config['num_tag_close'] = '</li>';
	$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
	$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
	$config['next_tag_open'] = "<li>";
	$config['next_tagl_close'] = "</li>";
	$config['prev_tag_open'] = "<li>";
	$config['prev_tagl_close'] = "</li>";
	$config['first_tag_open'] = "<li>";
	$config['first_tagl_close'] = "</li>";
	$config['last_tag_open'] = "<li>";
	$config['last_tagl_close'] = "</li>";
	$this->pagination->initialize($config);
	$page = ($this->uri->segment(5))? $this->uri->segment(5) : 0;
	$data['results']=$this->Employer_model->get_listingpackges($config["per_page"],$page,$session_data['id'],$id);
			
			$data["links"] = $this->pagination->create_links();	
			$this->load->view('employer/header',$data);
			$this->load->view('employer/showlisting',$data);
			$this->load->view('employer/footer',$data);
}else{ show_404(); }
 }
 function payments_history()
	{
		if ($this->session->userdata('logged_in') && $this->session->userdata('logged_in')['type']=="EMPLOYER" || 
	$this->session->userdata('logged_in')['oauth_provider']=="google" || $this->session->userdata('logged_in')['oauth_provider']=="facebook") {
				   $session_data = $this->session->userdata('logged_in');
				   $data['email'] = $session_data['email'];
		$data['company_name'] = $session_data['company_name'];
		$data['id'] = $session_data['id'];
			   }
		else
		{
		redirect('Employerr/Employer/logout',"refresh");
		}
	    $data['results']=$this->Employer_model->userpayment_history($session_data['id']);
		$data['totaldue']=$this->Employer_model->duepackageprice($data['id']);
		
		
			$this->load->view('employer/header',$data);
			$this->load->view('employer/users_payments',$data);
			$this->load->view('employer/footer',$data);
	}
	function packges($id=null)
	{
		
	if($id != null){
		if ($this->session->userdata('logged_in') && $this->session->userdata('logged_in')['type']=="EMPLOYER" || 
	$this->session->userdata('logged_in')['oauth_provider']=="google" || $this->session->userdata('logged_in')['oauth_provider']=="facebook") {
				   $session_data = $this->session->userdata('logged_in');
				   $data['email'] = $session_data['email'];
		$data['company_name'] = $session_data['company_name'];
		$data['id'] = $session_data['id'];
			   }

		else
		{
		redirect('Employerr/Employer/logout',"refresh");
		}

			$data['results'] = $this->Employer_model->view_package();
			$data['jobsid']=$id;
			$this->load->helper('packgesactive_helper');
			$this->load->view('employer/header',$data);
			$this->load->view('employer/view_packagerelist',$data);
			$this->load->view('employer/footer',$data); 
	  
	  }else{ show_404(); }
	 
	 }
public function add_jobsrelist($packid=null,$jobid=null)
 {

if($packid != null && $jobid != null){
 
 

 
 
	if ($this->session->userdata('logged_in') && $this->session->userdata('logged_in')['type']=="EMPLOYER" || 
	  $this->session->userdata('logged_in')['oauth_provider']=="google" || $this->session->userdata('logged_in')['oauth_provider']=="facebook") {
				   $session_data = $this->session->userdata('logged_in');
				   $data['email'] = $session_data['email'];
		$data['company_name'] = $session_data['company_name'];
		$data['id'] = $session_data['id'];
			   }else{redirect('Employerr/Employer/logout',"refresh");}
			   
	 $jonsdetils=$this->Employer_model->fetchdatarow('*','post_job',array("job_id"=>$jobid,"company_fk"=>$data['id']));
	 
	
	 
	 
	 if($jonsdetils != ""){
		/* $num=$this->Employer_model->forgotpass('user_active_package',array("id"=>$jonsdetils->pack_fk,"employer_fk"=>$data['id'],"expiry_date <= "=>date('Y-m-d'))); 
		
		
if($num != 0 || $jonsdetils->status==2){ */
		
		$package_id=$packid;
		$useactive=$this->Employer_model->get_activepackge($data['id'],$package_id);
		
		if($useactive != ""){  
					$activepack1=$useactive->id; 
					$pachejobs=$useactive->listing_no;
		
		 $totaladdjobs=$this->Employer_model->forgotpass('post_job',array("pack_fk"=>$activepack1));
		if($totaladdjobs < $pachejobs){ $activepack=$useactive->id;  }else{  $product=$this->Employer_model->fetchdatarow('pack_id,pack_description,pack_name,days,listing_no,front_page,hightlighted,html_listing,visitor_count,top_of_category,google_map,price','package_master',array("pack_id"=>$package_id));
		
		$pack_id=$product->pack_id;
		$pack_name=$product->pack_name;
		$pack_description=$product->pack_description;
		$listing_no=$product->listing_no;
		$front_page=$product->front_page;
		$hightlighted=$product->hightlighted;
		$html_listing=$product->html_listing;
		$visitor_count=$product->visitor_count;
		$top_of_category=$product->top_of_category;
		$google_map=$product->google_map;
		$price=$product->price;
		$days=$product->days;
		$employer_fk=$session_data['id'];
		
		$expiry=date('Y-m-d h:i:sa', mktime(date("H"), date("i"), date("s"), date("m"), date("d") + $product->days, date("Y"))); 
		
		$data_array=array('employer_fk'=>$session_data['id'],'package_fk'=>$package_id,'start_date'=>date('Y-m-d h:i:sa'),'expiry_date'=>$expiry,'payment_status'=>"pending",'price'=>$price,'mrp_price'=>$price,'created_date' =>date('Y-m-d h:i:sa'),'status' =>2,"pack_name"=>$pack_name,"pack_description"=>$pack_description,"listing_no"=>$listing_no,"front_page"=>$front_page,"hightlighted"=>$hightlighted,"html_listing"=>$html_listing,"visitor_count"=>$visitor_count,"top_of_category"=>$top_of_category,"google_map"=>$google_map,"days"=>$days);
		
		 $activepack=$this->Employer_model->master_fun_insert("user_active_package",$data_array); }
		}else{ $product=$this->Employer_model->fetchdatarow('pack_id,pack_description,pack_name,days,listing_no,front_page,hightlighted,html_listing,visitor_count,top_of_category,google_map,price','package_master',array("pack_id"=>$package_id));
		
		$pack_id=$product->pack_id;
		$pack_name=$product->pack_name;
		$pack_description=$product->pack_description;
		$listing_no=$product->listing_no;
		$front_page=$product->front_page;
		$hightlighted=$product->hightlighted;
		$html_listing=$product->html_listing;
		$visitor_count=$product->visitor_count;
		$top_of_category=$product->top_of_category;
		$google_map=$product->google_map;
		$price=$product->price;
		$days=$product->days;
		$employer_fk=$session_data['id'];
		
		$expiry=date('Y-m-d h:i:sa', mktime(date("H"), date("i"), date("s"), date("m"), date("d") + $product->days, date("Y"))); 
		
		$data_array=array('employer_fk'=>$session_data['id'],'package_fk'=>$package_id,'start_date'=>date('Y-m-d h:i:sa'),'expiry_date'=>$expiry,'payment_status'=>"pending",'price'=>$price,'mrp_price'=>$price,'created_date' =>date('Y-m-d h:i:sa'),'status' =>2,"pack_name"=>$pack_name,"pack_description"=>$pack_description,"listing_no"=>$listing_no,"front_page"=>$front_page,"hightlighted"=>$hightlighted,"html_listing"=>$html_listing,"visitor_count"=>$visitor_count,"top_of_category"=>$top_of_category,"google_map"=>$google_map,"days"=>$days);
		
		 $activepack=$this->Employer_model->master_fun_insert("user_active_package",$data_array); }
		 
		 
		 $datainsert = array(
			'pack_fk' => $activepack,
            'title' =>$jonsdetils->title,
            'job_description' => $jonsdetils->job_description,
            'keyword' => $jonsdetils->keyword,
            'category_name' => $jonsdetils->category_name,
            'attachment' => $jonsdetils->attachment, //$this->input->post('attachment'),
            'display' => $jonsdetils->display,
            'position' => $jonsdetils->position,
            'payment' => $jonsdetils->payment,
            'amount' => $jonsdetils->amount,
			'length' => $jonsdetils->length,
			'length1' =>$jonsdetils->length1,
			'rating' => $jonsdetils->rating,
            'location' => $jonsdetils->location,
            'created_date' => date('Y-m-d h:i:sa'),
			'company_fk' => $data['id'],
			'lat' =>$jonsdetils->lat,
			'log' =>$jonsdetils->log,
            'status' => 3
        );
		
		 $data['query'] = $this->Employer_model->master_fun_insert('post_job',$datainsert);
		 
						$title = $this->input->post('title');
						$getadata1 = $this->db->get_where('email_setting',array('email_id' =>14));
						$getadata=$getadata1->row();
						$body=$getadata->message;
						$subject=$getadata->subject;
						$body=str_replace('{{company_name}}',$session_data['company_name'],$body);
						$body=str_replace('{{title}}',$title,$body);
						
						$this->load->helper(array('swift'));
						$this->session->set_flashdata("email_sent","Email Sent Successfully.");
						
						$this->session->set_flashdata("success",$this->data['lang']->listing_addeded_succ_msg);
						redirect('Employerr/Employer/show_listing');
						
		/* }else{ show_404(); } */		

	 }else{ show_404(); }
			
}else{ show_404(); }
	}	 
	 
	
}
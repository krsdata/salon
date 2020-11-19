<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/third_party/spout-2.4.3/src/Spout/Autoloader/autoload.php';
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type; 

class SuperAdmin extends CI_Controller {
		
		//constructor of the Alumni Controller
		public function __construct(){
			parent::__construct();
			date_default_timezone_set('Asia/Kolkata');
			$this->load->model('SuperAdminModel');
	 }
	//function for login
	public function Login(){
		if(isset($_POST) && !empty($_POST)){
			$this->form_validation->set_rules('super_admin_email', 'Email', 'trim|required|max_length[100]');
			$this->form_validation->set_rules('super_admin_password', 'Password', 'trim|required');
			
			if ($this->form_validation->run() == FALSE) 
			{
				$data = array(
								'success' => 'false',
								'error'   => 'true',
								'message' =>  validation_errors()
							 );
				header("Content-type: application/json");
				print(json_encode($data, JSON_PRETTY_PRINT));
				die;
			}  
			else 
			{
				$data = array(
					'super_admin_email' 	  => $this->input->post('super_admin_email'),
					'super_admin_password' => $this->input->post('super_admin_password')
				);
			
				$result = $this->SuperAdminModel->SuperAdminLogin($data);
				
				if ($result['success'] == 'true') 
				{
					$result = $this->SuperAdminModel->SuperAdminByEmail($data['super_admin_email']);
				
						if($result['success'] == 'true'){
							if($data['super_admin_email'] == $result['res_arr']['super_admin_email'] && password_verify($data['super_admin_password'],$result['res_arr']['super_admin_password'])){ 
								$session_data = array(
									'super_admin_id'      => $result['res_arr']['super_admin_id'],
									'super_admin_name'    => $result['res_arr']['super_admin_name'],
									'super_admin_email'   => $result['res_arr']['super_admin_email'],
									'user_type'        => 'super_admin'
								);
							
								$this->session->set_userdata('logged_in', $session_data);
								$this->ReturnJsonArray(true,false,'Valid login!');
								die;
							}else{
								$this->ReturnJsonArray(false,true,'No such super admin exist. Try Again with valid credentials.');
								die;
							}
						}elseif($result['error'] == 'true'){
							$this->ReturnJsonArray(false,true,$result['message']);
							die;
						}
				}
				elseif($result['error'] == 'true'){
					$this->ReturnJsonArray(false,true,$result['message']);
					die;
				}
			}
		}
		else{
			$data['title'] = "Login";
			$this->load->view('superAdmin/sa_login_view',$data);
		}
	}
	private function IsLoggedIn($type){
		if (!isset($this->session->userdata['logged_in'])) {
    	return false;
    }
    else{
    	if($this->session->userdata['logged_in']['user_type'] == $type){
    		return true;
    	}
    	else{
    		return false;
    	}
    }
	}

	private function GetDataForSuperAdmin($title){
		$data = array();
		$data['title'] = $title;
		return $data;
	}



  public function ReturnJsonArray($success,$error,$message){
  	if($success == true && $error == false){
  		$data = array(
						'success' => 'true',
						'error'   => 'false',
						'message' =>  $message
					 );
			header("Content-type: application/json");
			print(json_encode($data, JSON_PRETTY_PRINT));
		}
		elseif ($success == false && $error == true) {
			$data = array(
						'success' => 'false',
						'error'   => 'true',
						'message' =>  $message
					 );
			header("Content-type: application/json");
			print(json_encode($data, JSON_PRETTY_PRINT));
		}
  }

  //Testing Function
  private function PrettyPrintArray($data){
  	print("<pre>".print_r($data,true)."</pre>");
  	die;
  }

	//function for logging out the user
	public function Logout(){
		if(isset($this->session->userdata['logged_in']) && !empty($this->session->userdata['logged_in'])){
 			$this->session->unset_userdata('logged_in');
 			$this->session->unset_userdata('outlets');
 			$this->session->sess_destroy();
 		}
		redirect(base_url().'SuperAdmin/Login/','refresh');
	}

	//function for logging out the user
	private function LogoutUrl($url){
		if(isset($this->session->userdata['logged_in']) && !empty($this->session->userdata['logged_in'])){
 			$this->session->unset_userdata('logged_in');
 			$this->session->unset_userdata('outlets');
 			$this->session->sess_destroy();
 		}
	    
	    redirect($url,'refresh');
	}

	

	public function ResetAdminPassword(){
		if($this->IsLoggedIn('super_admin')){
			if(isset($_POST) && !empty($_POST)){
				$this->form_validation->set_rules('business_admin_id', 'Business Admin', 'trim|required');
				$this->form_validation->set_rules('new_password', 'New Password', 'trim|required');
				$this->form_validation->set_rules('confirm_new_password', 'Confirm Password', 'trim|required');

				if ($this->form_validation->run() == FALSE) 
				{
					$data = array(
									'success' => 'false',
									'error'   => 'true',
									'message' =>  validation_errors()
								 );
					header("Content-type: application/json");
					print(json_encode($data, JSON_PRETTY_PRINT));
					die;
				}
				else{
					  
					$new_password 		  =  $this->input->post('new_password');
					$confirm_new_password =  $this->input->post('confirm_new_password');
					
					if($new_password === $confirm_new_password){
						
						$data = array(
							"business_admin_password" => password_hash($new_password,PASSWORD_DEFAULT),
							"business_admin_id"       => $this->input->post('business_admin_id')
						);
						
						$result = $this->SuperAdminModel->Update($data,'mss_business_admin','business_admin_id');
						// $this->PrettyPrintArray($result);
						// exit;
						if($result['success'] == 'true'){
							$this->ReturnJsonArray(true,false,"Password Reset Successfully!");
							die;
            }
            elseif($result['error'] == 'true'){
            	$this->ReturnJsonArray(false,true,$result['message']);
							die;
            }
					}
					else{
						$this->ReturnJsonArray(false,true,"Passwords Do Not Match!");
						die;
					}
				}	
		  }
		 	else{
				
				 $data = $this->GetDataForSuperAdmin("Reset Password");
				 $data['admin_list']=$this->SuperAdminModel->FullTable('mss_business_admin');
				$data['admin_list']=$data['admin_list']['res_arr'];
				$this->load->view('superAdmin/sa_resetAdminPassword',$data);
			}
		}
		else{
			$this->LogoutUrl(base_url()."SuperAdmin/Login");
		}					
	}
	
	//Dashboard for the Business Admin
	public function Dashboard(){
		if($this->IsLoggedIn('super_admin')){
			$data = $this->GetDataForSuperAdmin("Dashboard");
			$data['sidebar_collapsed'] = "true";
			$data['admin_list']=$this->SuperAdminModel->FullTable('mss_business_admin');
			$data['admin_list']=$data['admin_list']['res_arr'];
			$data['active_admin']=$this->SuperAdminModel->ActiveAdminDetails();
			$data['active_admin']=$data['active_admin']['res_arr'];
			$data['master_admin']=$this->SuperAdminModel->FullTable('mss_master_admin');
			$data['master_admin']=$data['master_admin']['res_arr'];
			/*$this->PrettyPrintArray($data);
			die;*/
			$this->load->view('superAdmin/sa_dashboard_view',$data);
		}
		else{
			$this->LogoutUrl(base_url()."SuperAdmin/Login");
		}
	}

	//Default Page for Business Admin
	public function index(){
		if($this->IsLoggedIn('super_admin')){
			$data = $this->GetDataForSuperAdmin("Dashboard");
			$data['sidebar_collapsed'] = "true";
			$data['admin_list']=$this->SuperAdminModel->FullTable('mss_business_admin');
			$data['admin_list']=$data['admin_list']['res_arr'];
			$data['active_admin']=$this->SuperAdminModel->ActiveAdminDetails();
			$data['active_admin']=$data['active_admin']['res_arr'];
			$data['master_admin']=$this->SuperAdminModel->FullTable('mss_master_admin');
			$data['master_admin']=$data['master_admin']['res_arr'];
			// $this->PrettyPrintArray($data['admin_list']);
			// exit;
			$this->load->view('superAdmin/sa_dashboard_view',$data);
		}
		else{
			$data['title'] = "Login";
			$this->load->view('superAdmin/sa_login_view',$data);
		}
	}
	public function AddAdmin(){	
		if($this->IsLoggedIn('super_admin')){
			if(isset($_POST) && !empty($_POST)){
				$this->form_validation->set_rules('business_admin_first_name', 'Business Admin First Name', 'trim|required|max_length[100]');
				$this->form_validation->set_rules('business_admin_last_name', 'Business Admin Last Name', 'trim|max_length[50]');
				$this->form_validation->set_rules('business_admin_mobile', 'Business Admin Mobile', 'trim|max_length[10]');
				$this->form_validation->set_rules('business_admin_address', 'Business Admin Address', 'trim|required');
				$this->form_validation->set_rules('business_admin_firm_name', 'Business Admin Firm Name', 'trim|required|max_length[100]');
				$this->form_validation->set_rules('business_admin_account_expiry_date', 'Business Admin Expiry Date', 'trim|required|max_length[10]');				
				$this->form_validation->set_rules('business_admin_city', 'Business Admin City', 'trim|max_length[255]');
				$this->form_validation->set_rules('business_admin_state', 'Business Admin State', 'trim|required|max_length[255]');
				$this->form_validation->set_rules('master_admin', 'Master Admin', 'trim|required');
				$this->form_validation->set_rules('business_admin_email', 'Business Admin Email', 'trim|max_length[100]');
				$this->form_validation->set_rules('business_admin_password', 'Business Admin Password', 'trim|required|max_length[50]');
				
				if ($this->form_validation->run() == FALSE) 
				{
					$data = array(
									'success' => 'false',
									'error'   => 'true',
									'message' =>  validation_errors()
								 );
					header("Content-type: application/json");
					print(json_encode($data, JSON_PRETTY_PRINT));
					die;
				}
				else{
					$data = array(
						'business_admin_first_name' 	=> $this->input->post('business_admin_first_name'),
						'business_master_admin_id' 	=> $this->input->post('master_admin'),
						'business_admin_last_name' 		=> $this->input->post('business_admin_last_name'),
						'business_admin_mobile' 			=> $this->input->post('business_admin_mobile'),
						'business_admin_address' 			=> $this->input->post('business_admin_address'),
						'business_admin_firm_name' 		=> $this->input->post('business_admin_firm_name'),
						'business_admin_account_expiry_date' 	=> $this->input->post('business_admin_account_expiry_date'),
						'business_admin_email' 				=> $this->input->post('business_admin_email'),
						'business_admin_city' 	=> $this->input->post('business_admin_city'),
						'business_admin_state' 				=> $this->input->post('business_admin_state'),
						'business_admin_password' 		=> PASSWORD_HASH($_POST['business_admin_password'], PASSWORD_DEFAULT),
						'business_admin_creation_date'=>date("Y-m-d")
						
					);
					
					$result = $this->SuperAdminModel->Insert($data,'mss_business_admin');
					
					$data2=array(
						'business_admin_id'	=> $result['res_arr']['insert_id']
					);
					$result2=  $this->SuperAdminModel->Insert($data2,'mss_user_permission');
					if($result['success'] == 'true'){
						//send user id and password tp Admin mobile
						$this->AccountDetailsSMS($data['business_admin_mobile'],$data['business_admin_first_name'],$data['business_admin_email'],$_POST['business_admin_password']);
						$this->ReturnJsonArray(true,false,"Admin Created successfully!<br>Please Remember Your Password");
						die;
          }
          elseif($result['error'] == 'true'){
          	$this->ReturnJsonArray(false,true,$result['message']);
						die;
          }
				}
			}
			else{
				$data = $this->GetDataForSuperAdmin("Dashboard");
				$this->load->view('superAdmin/sa_dashboard_view',$data);
			}
		}
		else{
			$this->LogoutUrl(base_url()."SuperAdmin/Login");
		}	
	}


	//Add Master Admin
	public function AddMasterAdmin(){	
		if($this->IsLoggedIn('super_admin')){
			if(isset($_POST) && !empty($_POST)){
				$this->form_validation->set_rules('master_admin_name', 'Master Admin Name', 'trim|required|max_length[100]');
				$this->form_validation->set_rules('master_admin_firm_name', 'master Admin Firm Name', 'trim|max_length[50]');
				$this->form_validation->set_rules('master_admin_mobile', 'master Admin Mobile', 'trim|max_length[10]');
				$this->form_validation->set_rules('master_admin_account_expiry_date', 'master Admin Expiry Date', 'trim|required|max_length[10]');				
				$this->form_validation->set_rules('master_admin_city', 'master Admin City', 'trim|max_length[255]');
				$this->form_validation->set_rules('master_admin_state', 'master Admin State', 'trim|required|max_length[255]');
				$this->form_validation->set_rules('master_admin_email', 'master Admin Email', 'trim|max_length[100]');
				$this->form_validation->set_rules('master_admin_password', 'master Admin Password', 'trim|required|max_length[50]');
				
				if ($this->form_validation->run() == FALSE){
					$data = array(
									'success' => 'false',
									'error'   => 'true',
									'message' =>  validation_errors()
								 );
					header("Content-type: application/json");
					print(json_encode($data, JSON_PRETTY_PRINT));
					die;
				}
				else{
					$data = array(
						'master_admin_name' 	=> $this->input->post('master_admin_name'),
						'master_admin_firm_name' 		=> $this->input->post('master_admin_firm_name'),
						'master_admin_mobile' 			=> $this->input->post('master_admin_mobile'),
						'master_admin_account_expiry_date' 	=> $this->input->post('master_admin_account_expiry_date'),
						'master_admin_email' 				=> $this->input->post('master_admin_email'),
						'master_admin_city' 	=> $this->input->post('master_admin_city'),
						'master_admin_state' 				=> $this->input->post('master_admin_state'),
						'master_admin_password' 		=> PASSWORD_HASH($_POST['master_admin_password'], PASSWORD_DEFAULT),
						'master_admin_creation_date'=>date("Y-m-d")
						
					);
					
					$result = $this->SuperAdminModel->Insert($data,'mss_master_admin');
						
					if($result['success'] == 'true'){
						$this->ReturnJsonArray(true,false,"Master Admin Created successfully!<br>Please Remember Your Password");
						//send user id and password tp Admin mobile
						$this->AccountDetailsSMS($data['master_admin_mobile'],$data['master_admin_name'],$data['master_admin_email'],$_POST['master_admin_password']);
						
						die;
          }
          elseif($result['error'] == 'true'){
          	$this->ReturnJsonArray(false,true,$result['message']);
						die;
          }
				}
			}
			else{
				$data = $this->GetDataForSuperAdmin("Dashboard");
				$this->load->view('superAdmin/sa_dashboard_view',$data);
			}
		}
		else{
			$this->LogoutUrl(base_url()."SuperAdmin/Login");
		}	
	}
	//send Account Details
	public function  AccountDetailsSMS($admin_mobile,$admin_name,$admin_email,$admin_password){
		//API key & sender ID
		$apikey = "ll2C18W9s0qtY7jIac5UUQ";
		$apisender = "BILLIT";
		$msg = "Dear ".$admin_name.", your account has been created at Salon First. Your user email is ".$admin_email." and password is ".$admin_password.". It's confidential to you. Don't share with anyone. Thanks for using Salon First. ";
		 $msg = rawurlencode($msg);   //This for encode your message content                 		
		 
		 // API 
		$url = 'https://www.smsgatewayhub.com/api/mt/SendSMS?APIKey='.$apikey.'&senderid='.$apisender.'&channel=2&DCS=0&flashsms=0&number='.$admin_mobile.'&text='.$msg.'&route=1';
									
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch,CURLOPT_POST,1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,"");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,2);
		
		$data = curl_exec($ch);
		return json_encode($data);
	}
	//Edit Admin
	public function EditAdmin(){	
		if($this->IsLoggedIn('super_admin')){
			// $this->PrettyPrintArray($_POST);
			// exit;
			if(isset($_POST) && !empty($_POST)){
				$this->form_validation->set_rules('business_admin_id', 'Business Admin Id', 'trim|required');
				$this->form_validation->set_rules('business_admin_first_name', 'Business Admin First Name', 'trim|required|max_length[100]');
				$this->form_validation->set_rules('business_admin_last_name', 'Business Admin Last Name', 'trim|max_length[50]');
				$this->form_validation->set_rules('business_admin_mobile', 'Business Admin Mobile', 'trim|max_length[10]');
				$this->form_validation->set_rules('business_admin_address', 'Business Admin Address', 'trim|required');
				$this->form_validation->set_rules('business_admin_firm_name', 'Business Admin Firm Name', 'trim|required|max_length[100]');
				$this->form_validation->set_rules('business_admin_account_expiry_date', 'Business Admin Expiry Date', 'trim|required|max_length[10]');				
				$this->form_validation->set_rules('business_admin_email', 'Business Admin Email', 'trim|max_length[100]');
				// $this->form_validation->set_rules('business_admin_password', 'Business Admin Password', 'trim|required|max_length[50]');
				
				if ($this->form_validation->run() == FALSE) 
				{
					$data = array(
									'success' => 'false',
									'error'   => 'true',
									'message' =>  validation_errors()
								 );
					header("Content-type: application/json");
					print(json_encode($data, JSON_PRETTY_PRINT));
					die;
				}
				else{
					$data = array(
						'business_admin_first_name' 	=> $this->input->post('business_admin_first_name'),
						'business_admin_id' 	=> $this->input->post('business_admin_id'),
						'business_master_admin_id' 	=> $this->input->post('master_admin'),
						'business_admin_last_name' 		=> $this->input->post('business_admin_last_name'),
						'business_admin_mobile' 			=> $this->input->post('business_admin_mobile'),
						'business_admin_address' 			=> $this->input->post('business_admin_address'),
						'business_admin_firm_name' 		=> $this->input->post('business_admin_firm_name'),
						'business_admin_account_expiry_date' 	=> $this->input->post('business_admin_account_expiry_date'),
						'business_admin_email' 				=> $this->input->post('business_admin_email'),
						// 'business_admin_password' 		=> PASSWORD_HASH($_POST['business_admin_password'], PASSWORD_DEFAULT),
						// 'business_admin_creation_date'=>date("Y-m-d")
						
					);
					
					$result = $this->SuperAdminModel->Update($data,'mss_business_admin','business_admin_id');
						
					if($result['success'] == 'true'){
						$this->ReturnJsonArray(true,false,"Admin updated successfully!");
						die;
          }
          elseif($result['error'] == 'true'){
          	$this->ReturnJsonArray(false,true,$result['message']);
						die;
          }
				}
			}
			else{
				$data = $this->GetDataForSuperAdmin("Dashboard");
				$this->load->view('superAdmin/sa_dashboard_view',$data);
			}
		}
		else{
			$this->LogoutUrl(base_url()."SuperAdmin/Login");
		}	
	}


	//Assign Module to Business Admin
	public function AddModule(){
		if($this->IsLoggedIn('super_admin')){
			if(isset($_POST) && !empty($_POST)){
				$this->form_validation->set_rules('business_outlet_business_admin', 'Business Outlet Business Admin', 'trim|required');
				
				if ($this->form_validation->run() == FALSE) 
				{
					$data = array(
									'success' => 'false',
									'error'   => 'true',
									'message' =>  validation_errors()
								 );
					header("Content-type: application/json");
					print(json_encode($data, JSON_PRETTY_PRINT));
					die;
				}
				else{
					foreach($_POST['module'] as $k=>$v){
						$data = array(
							'business_admin_id' 	=> $this->input->post('business_outlet_business_admin'),
							'package_id'					=> $v,
							'package_expiry_date'	=>"2030-12-31"
						);
						$result = $this->SuperAdminModel->Insert($data,'mss_business_admin_packages');
					}
					
					
						
					if($result['success'] == 'true'){
						$this->ReturnJsonArray(true,false,"Module added successfully!");
						die;
          }
          elseif($result['error'] == 'true'){
          	$this->ReturnJsonArray(false,true,$result['message']);
						die;
          }
				}
			}
			else{
				$data = $this->GetDataForSuperAdmin("Add Outlet");
				$this->load->view('superAdmin/sa_dashboard_view',$data);
			}
		}
		else{
			$this->LogoutUrl(base_url()."SuperAdmin");
		}	
	}

	//Admin Outlet Details
	public function AdminOuletDetails(){
		if($this->IsLoggedIn('super_admin')){
			$data = $this->GetDataForSuperAdmin("Admin Outlet Details");
			$data['sidebar_collapsed'] = "true";
			$data['admin_id']=$_GET['var'];
			$where=array(
				'business_outlet_business_admin'=>$data['admin_id']
			);
			$data['outlet_list']=$this->SuperAdminModel->MultiWhereSelect('mss_business_outlets',$where);
			$data['business_outlet_details']=$data['outlet_list']['res_arr'];
			
			//for last active date uncomment below code
			// $data['business_outlet_details']=$this->SuperAdminModel->OutletDetails($where);
			// $data['business_outlet_details']=$data['business_outlet_details']['res_arr'];
			// $this->PrettyPrintArray($data['business_outlet_details']);
			// exit;
			$where2=array(
				'business_admin_id'=>$data['admin_id']
			);
			$data['module']=$this->SuperAdminModel->GetModule();
			$data['module']=$data['module']['res_arr'];

			$data['admin_module']=$this->SuperAdminModel->GetAdminModule($where2);
			$data['admin_module']=$data['admin_module']['res_arr'];

			// $this->PrettyPrintArray($data['admin_module']);
			// exit;
			$this->load->view('superAdmin/sa_admin_outlet_details_view',$data);
		}
		else{
			$this->LogoutUrl(base_url()."SuperAdmin/Login");
		}
	}

	//Add Outlet
	public function AddOutlet(){
		if($this->IsLoggedIn('super_admin')){
			if(isset($_POST) && !empty($_POST)){
				$this->form_validation->set_rules('business_outlet_name', 'Business Outlet Name', 'trim|required|max_length[100]');
				$this->form_validation->set_rules('business_outlet_gst_in', 'Business Outlet GST IN', 'trim|max_length[15]|min_length[15]');
				$this->form_validation->set_rules('business_outlet_address', 'Business Outlet Address', 'trim|required');
				$this->form_validation->set_rules('business_outlet_city', 'Business Outlet City', 'trim|required|max_length[100]');
				$this->form_validation->set_rules('business_outlet_state', 'Business Outlet State', 'trim|required|max_length[100]');
				$this->form_validation->set_rules('business_outlet_pincode', 'Business Outlet Pincode', 'trim|required|max_length[10]');
				$this->form_validation->set_rules('business_outlet_mobile', 'Business Outlet Mobile', 'trim|max_length[15]');
				$this->form_validation->set_rules('business_outlet_landline', 'Business Outlet Landline', 'trim|max_length[15]');
				$this->form_validation->set_rules('business_outlet_email', 'Business Outlet Email', 'trim|max_length[100]');
				$this->form_validation->set_rules('business_outlet_bill_header_msg', 'Business Outlet Bill Header message', 'trim');
				$this->form_validation->set_rules('business_outlet_bill_footer_msg', 'Business Outlet Bill Footer message', 'trim');
				$this->form_validation->set_rules('business_outlet_facebook_url', 'Business Outlet FB URL', 'trim');
				$this->form_validation->set_rules('business_outlet_instagram_url', 'Business Outlet Instagram URL', 'trim');
				$this->form_validation->set_rules('business_outlet_latitude', 'Business Outlet Latitude', 'trim');
				$this->form_validation->set_rules('business_outlet_longitude', 'Business Outlet Longitude', 'trim');
				$this->form_validation->set_rules('business_outlet_location', 'Business Outlet Location', 'trim');
				$this->form_validation->set_rules('business_outlet_expiry_date', 'Business Outlet Expiry Date', 'trim');

				if ($this->form_validation->run() == FALSE) 
				{
					$data = array(
									'success' => 'false',
									'error'   => 'true',
									'message' =>  validation_errors()
								 );
					header("Content-type: application/json");
					print(json_encode($data, JSON_PRETTY_PRINT));
					die;
				}
				else{
					$data = array(
						'business_outlet_name' 	=> $this->input->post('business_outlet_name'),
						'business_outlet_gst_in' 	=> $this->input->post('business_outlet_gst_in'),
						'business_outlet_mobile' 	=> $this->input->post('business_outlet_mobile'),
						'business_outlet_landline' 	=> $this->input->post('business_outlet_landline'),
						'business_outlet_address' 	=> $this->input->post('business_outlet_address'),
						'business_outlet_location' 	=> $this->input->post('business_outlet_location'),
						'business_outlet_pincode' 	=> $this->input->post('business_outlet_pincode'),
						'business_outlet_state' 	=> $this->input->post('business_outlet_state'),
						'business_outlet_city' 	=> $this->input->post('business_outlet_city'),
						'business_outlet_country' 	=> "India",
						'business_outlet_email' 	=> $this->input->post('business_outlet_email'),
						'business_outlet_bill_header_msg' 	=> $this->input->post('business_outlet_bill_header_msg'),
						'business_outlet_bill_footer_msg' 	=> $this->input->post('business_outlet_bill_footer_msg'),
						'business_outlet_facebook_url' 	=> $this->input->post('business_outlet_facebook_url'),
						'business_outlet_instagram_url' 	=> $this->input->post('business_outlet_instagram_url'),
						'business_outlet_sender_id' 	=> $this->input->post('business_outlet_sender_id'),
						'business_outlet_google_my_business_url' 	=> $this->input->post('business_outlet_google_my_business_url'),
						'api_key' 									=> $this->input->post('api_key'),
						'business_whatsapp_number'	=> $this->input->post('business_whatsapp_number'),
						'whatsapp_userid'						=> $this->input->post('whatsapp_userid'),
						'whatsapp_key'							=> $this->input->post('whatsapp_key'),
						'business_outlet_latitude' 	=> $this->input->post('business_outlet_latitude'),
						'business_outlet_longitude' 	=> $this->input->post('business_outlet_longitude'),
						'business_outlet_business_admin' 	=> $this->input->post('business_outlet_business_admin'),
						'business_outlet_expiry_date' 	=> $this->input->post('business_outlet_expiry_date'),
						'business_outlet_creation_date' 	=> date('Y-m-d')
					);

					$result = $this->SuperAdminModel->Insert($data,'mss_business_outlets');
						
					if($result['success'] == 'true'){
						$this->ReturnJsonArray(true,false,"Outlet added successfully!");
						die;
          }
          elseif($result['error'] == 'true'){
          	$this->ReturnJsonArray(false,true,$result['message']);
						die;
          }
				}
			}
			else{
				$this->load->view('superAdmin/sa_admin_outlet_details_view');
			}
		}
		else{
			$this->LogoutUrl(base_url()."SuperAdmin/Login");
		}	
	}
	//
	public function GetBusinessOutlet(){
		if($this->IsLoggedIn('super_admin')){
			if(isset($_GET) && !empty($_GET)){
				$data = $this->SuperAdminModel->DetailsById($_GET['business_outlet_id'],'mss_business_outlets','business_outlet_id');
				header("Content-type: application/json");
				print(json_encode($data['res_arr'], JSON_PRETTY_PRINT));
				die;
			}
		}
		else{
			$this->LogoutUrl(base_url()."SuperAdmin/Login");
		}
	}
	public function GetBusinessAdmin(){
		if($this->IsLoggedIn('super_admin')){
			$where = array(
				'business_admin_id' => $_GET['business_admin_id']
			);
			$data = $this->SuperAdminModel->GetBusinessAdminData($where);
			if($data['success'] == 'true'){	
				header("Content-type: application/json");
				print(json_encode($data['res_arr'][0], JSON_PRETTY_PRINT));
				die;
			}
		}
		else{
			$this->LogoutUrl(base_url()."SuperAdmin/Login");
		}	
	}

	//Edit OUtet
	public function EditOutlet(){
		if($this->IsLoggedIn('super_admin')){
			if(isset($_POST) && !empty($_POST)){
				$this->form_validation->set_rules('business_outlet_name', 'Business Outlet Name', 'trim|required|max_length[100]');
				$this->form_validation->set_rules('business_outlet_gst_in', 'Business Outlet GST IN', 'trim|max_length[15]|min_length[15]');
				$this->form_validation->set_rules('business_outlet_address', 'Business Outlet Address', 'trim|required');
				$this->form_validation->set_rules('business_outlet_city', 'Business Outlet City', 'trim|required|max_length[100]');
				$this->form_validation->set_rules('business_outlet_state', 'Business Outlet State', 'trim|required|max_length[100]');
				$this->form_validation->set_rules('business_outlet_pincode', 'Business Outlet Pincode', 'trim|required|max_length[10]');
				$this->form_validation->set_rules('business_outlet_mobile', 'Business Outlet Mobile', 'trim|max_length[15]');
				$this->form_validation->set_rules('business_outlet_landline', 'Business Outlet Landline', 'trim|max_length[15]');
				$this->form_validation->set_rules('business_outlet_email', 'Business Outlet Email', 'trim|max_length[100]');
				$this->form_validation->set_rules('business_outlet_bill_header_msg', 'Business Outlet Bill Header message', 'trim');
				$this->form_validation->set_rules('business_outlet_bill_footer_msg', 'Business Outlet Bill Footer message', 'trim');
				$this->form_validation->set_rules('business_outlet_facebook_url', 'Business Outlet FB URL', 'trim');
				$this->form_validation->set_rules('business_outlet_instagram_url', 'Business Outlet Instagram URL', 'trim');
				
				$this->form_validation->set_rules('business_outlet_latitude', 'Business Outlet Latitude', 'trim');
				$this->form_validation->set_rules('business_outlet_longitude', 'Business Outlet Longitude', 'trim');

				if ($this->form_validation->run() == FALSE) 
				{
					$data = array(
									'success' => 'false',
									'error'   => 'true',
									'message' =>  validation_errors()
								 );
					header("Content-type: application/json");
					print(json_encode($data, JSON_PRETTY_PRINT));
					die;
				}
				else{
					$data = array(
						'business_outlet_id'    => $this->input->post('business_outlet_id'),
						'business_outlet_name' 	=> $this->input->post('business_outlet_name'),
						'business_outlet_gst_in' 	=> $this->input->post('business_outlet_gst_in'),
						'business_outlet_mobile' 	=> $this->input->post('business_outlet_mobile'),
						'business_outlet_landline' 	=> $this->input->post('business_outlet_landline'),
						'business_outlet_address' 	=> $this->input->post('business_outlet_address'),
						'business_outlet_location' 	=> $this->input->post('business_outlet_location'),
						'business_outlet_pincode' 	=> $this->input->post('business_outlet_pincode'),
						'business_outlet_state' 	=> $this->input->post('business_outlet_state'),
						'business_outlet_city' 	=> $this->input->post('business_outlet_city'),
						'business_outlet_country' 	=> "India",
						'business_outlet_email' 	=> $this->input->post('business_outlet_email'),
						'business_outlet_bill_header_msg' 	=> $this->input->post('business_outlet_bill_header_msg'),
						'business_outlet_bill_footer_msg' 	=> $this->input->post('business_outlet_bill_footer_msg'),
						'business_outlet_facebook_url' 	=> $this->input->post('business_outlet_facebook_url'),
						'business_outlet_instagram_url' 	=> $this->input->post('business_outlet_instagram_url'),
						'business_outlet_sender_id' 	=> $this->input->post('business_outlet_sender_id'),
						'business_outlet_google_my_business_url' 	=> $this->input->post('business_outlet_google_my_business_url'),
						'api_key' 	=> $this->input->post('api_key'),
						'business_whatsapp_number'	=> $this->input->post('business_whatsapp_number'),
						'whatsapp_userid'						=> $this->input->post('whatsapp_userid'),
						'whatsapp_key'							=> $this->input->post('whatsapp_key'),
						'business_outlet_latitude' 	=> $this->input->post('business_outlet_latitude'),
						'business_outlet_longitude' 	=> $this->input->post('business_outlet_longitude'),
						'business_outlet_business_admin' 	=> $this->input->post('business_outlet_business_admin'),
						'business_outlet_expiry_date' 	=> $this->input->post('business_outlet_expiry_date'),
						'business_outlet_creation_date' 	=> $this->input->post('business_outlet_creation_date')
					);
					
					$result = $this->SuperAdminModel->Update($data,'mss_business_outlets','business_outlet_id');
						
					if($result['success'] == 'true'){
						$this->ReturnJsonArray(true,false,"Outlet details updated successfully!");
						die;
          }
          elseif($result['error'] == 'true'){
          	$this->ReturnJsonArray(false,true,$result['message']);
						die;
          }
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."SuperAdmin/Login");
		}	
	}
	
	//DeleteOutlet
	public function DeleteOutlet(){
		if($this->IsLoggedIn('super_admin')){
			if(isset($_POST) && !empty($_POST)){
				$this->form_validation->set_rules('business_outlet_id', 'Business Outlet Id', 'trim|required|max_length[10]');
				$this->form_validation->set_rules('business_outlet_status', 'Business Outlet Status', 'trim|required');
				if ($this->form_validation->run() == FALSE) 
				{
					$data = array(
									'success' => 'false',
									'error'   => 'true',
									'message' =>  validation_errors()
								 );
					header("Content-type: application/json");
					print(json_encode($data, JSON_PRETTY_PRINT));
					die;
				}
				else{
					$data = array(
						'business_outlet_id'    => $this->input->post('business_outlet_id'),
						'business_outlet_status'=>$this->input->post('business_outlet_status')
					);
					
					$result = $this->SuperAdminModel->Update($data,'mss_business_outlets','business_outlet_id');
						
					if($result['success'] == 'true'){
						$this->ReturnJsonArray(true,false,"Outlet Updated successfully!");
						die;
          }
          elseif($result['error'] == 'true'){
          	$this->ReturnJsonArray(false,true,$result['message']);
						die;
          }
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."SuperAdmin/Login");
		}	
	}
	//
	//
	public function UpdateModule(){
		if($this->IsLoggedIn('super_admin')){
			if(isset($_POST) && !empty($_POST)){
				$this->form_validation->set_rules('business_admin_id', 'Business Admin Id', 'trim|required');
				$this->form_validation->set_rules('package_id', 'Package Id', 'trim|required');				
				if ($this->form_validation->run() == FALSE) 
				{
					$data = array(
									'success' => 'false',
									'error'   => 'true',
									'message' =>  validation_errors()
								 );
					header("Content-type: application/json");
					print(json_encode($data, JSON_PRETTY_PRINT));
					die;
				}
				else{
					$data = array(
						'business_admin_id' 	=> $this->input->post('business_admin_id'),
						'package_id'					=>$this->input->post('package_id'),
						'package_expiry_date'	=>$this->input->post('package_expiry_date')
					);
					$result = $this->SuperAdminModel->UpdateAdminModule($data);
					if($result['success'] == 'true'){
						$this->ReturnJsonArray(true,false,"Admin Module updated successfully!");
						die;
          }
          elseif($result['error'] == 'true'){
          	$this->ReturnJsonArray(false,true,$result['message']);
						die;
          }
				}
			}
			else{
				$this->load->view('superAdmin/sa_admin_outlet_details_view');
			}
		}
		else{
			$this->LogoutUrl(base_url()."SuperAdmin/Login");
		}		

	}
	//Send Message from SMTP
	public function SendMail(){
	
    $to =  $this->input->post('from');  // User email pass here
    $subject = 'Welcome To MarkS-Retech';

    $from = 'marksretech@gmail.com';              // Pass here your mail id
    $emailContent = '<!DOCTYPE><html><head></head><body><table width="600px" style="border:1px solid #cccccc;margin: auto;border-spacing:0;"><tr><td style="background:#000000;padding-left:3%"><img src="http://codingmantra.co.in/assets/logo/logo.png" width="300px" vspace=10 /></td></tr>';
    $emailContent .='<tr><td style="height:20px"></td></tr>';


    $emailContent .= $this->input->post('message');  //   Post message available here


    $emailContent .='<tr><td style="height:20px"></td></tr>';
    $emailContent .= "<tr><td style='background:#000000;color: #999999;padding: 2%;text-align: center;font-size: 13px;'><p style='margin-top:1px;'><a href='http://codingmantra.co.in/' target='_blank' style='text-decoration:none;color: #60d2ff;'>www.codingmantra.co.in</a></p></td></tr></table></body></html>";
                


    $config['protocol']    = 'smtp';
    $config['smtp_host']    = 'ssl://smtp.gmail.com';
    $config['smtp_port']    = '465';
    $config['smtp_timeout'] = '60';

    $config['smtp_user']    = 'marksretech@gmail.com';    //Important
    $config['smtp_pass']    = 'Delhi@123';  //Important

    $config['charset']    = 'utf-8';
    $config['newline']    = "\r\n";
    $config['mailtype'] = 'html'; // or html
    $config['validation'] = TRUE; // bool whether to validate email or not 

    $this->email->initialize($config);
    $this->email->set_mailtype("html");
    $this->email->from($from);
    $this->email->to($to);
    $this->email->subject($subject);
    $this->email->message($emailContent);
		print_r($this->email->send());
		$this->ReturnJsonArray(true,false,"success");
		die;
	}

	//
	public function GetBusinessOutlets(){
		if($this->IsLoggedIn('super_admin')){
			$where = array(
				'business_outlet_business_admin' => $_GET['business_admin_id']
			);
			$data = $this->SuperAdminModel->MultiWhereSelect('mss_business_outlets',$where);
			if($data['success'] == 'true'){	
				header("Content-type: application/json");
				print(json_encode($data['res_arr'], JSON_PRETTY_PRINT));
				die;
			}
		}
		else{
			$this->LogoutUrl(base_url()."SuperAdmin/Login");
		}	
	}

	
	//
	public function CustomerData(){
		if($this->IsLoggedIn('super_admin')){
		if(isset($_GET) && !empty($_GET)){
					$data = array(
						'business_outlet_business_admin' => $_GET['business_outlet_business_admin'],
						'business_outlet_id'						=>$_GET['business_outlet']
					);

					$result = $this->SuperAdminModel->GetCustomerData($data);
					if($result['success'] == 'true'){
						/*$this->PrettyPrintArray($result['res_arr']);
						die;*/
						$data = array(
									'success' => 'true',
									'error'   => 'false',
									'message' => '',
									'result' =>  $result['res_arr']
						);
						header("Content-type: application/json");
						print(json_encode($data, JSON_PRETTY_PRINT));
						die;	
          }
          elseif($result['error'] == 'true'){
          	$data = array(
									'success' => 'false',
									'error'   => 'true',
									'message' =>  $result['message']
						);
						header("Content-type: application/json");
						print(json_encode($data, JSON_PRETTY_PRINT));
						die;	
          }
	      }
	      else{
	      	$this->ReturnJsonArray(false,true,"Select outlet first!");
	      }
			}		
		else{
			$this->LogoutUrl(base_url()."SuperAdmin");
		}
	}

    //jitesh's code
	public function AddLoyaltyRule()
    {
        // $this->PrettyPrintArray($_POST);
        // exit;
        if($this->IsLoggedIn('super_admin'))
        {
            if(isset($_POST) && !empty($_POST))
            {
                // $data['admin_id']=$_POST['var'];
                $this->form_validation->set_rules('business_outlet_rule', 'Business Outlet Rule', 'required');
                $this->form_validation->set_rules('business_outlet_rule_validity', 'Business Outlet Rule Validity', 'required');
                if ($this->form_validation->run() == FALSE) 
                {
                    $data = array(
                                    'success' => 'false',
                                    'error'   => 'true',
                                    'message' =>  validation_errors()
                                 );
                    header("Content-type: application/json");
                    print(json_encode($data, JSON_PRETTY_PRINT));
                    die;
                }
                else
                {
                        // $this->PrettyPrintArray($_POST);
                        // exit;
                        $where = array('business_outlet_id' => $this->input->post('loyalty_business_outlet_id'));
                        $CheckOutletRuleExists = $this->SuperAdminModel->CheckOutletRuleExists($where);
                        // $this->PrettyPrintArray($CheckOutletRuleExists);
                        // exit;
                        // if($CheckOutletRuleExists['success'] == 'false' && $CheckOutletRuleExists['error'] == 'true')
                        // {
                            // $this->PrettyPrintArray($_POST);
                            //   exit;
                            $amount1  = $this->input->post('amount1[]');
                            $amount2  = $this->input->post('amount2[]');
                            $points   = $this->input->post('points[]');
                            $cashback = $this->input->post('cashback[]');
                            $counter  = 0;//To keep track of no of successful submisions
                            // $this->PrettyPrintArray($_POST);
                            // exit;
                            if($_POST['business_outlet_rule'] == 'Cashback Visits')
                            {
								// $this->PrettyPrintArray($_POST);
                                // exit;
                                $data = array(
                                    'business_outlet_id'  => $this->input->post('loyalty_business_outlet_id'),
                                    'business_admin_id'   => $this->input->post('business_outlet_business_admin'),
                                    'rule_type'           => $this->input->post('business_outlet_rule'),
                                    'rule_validity'       => $this->input->post('business_outlet_rule_validity')
                                );
                                $result = $this->SuperAdminModel->InsertRule($data,'mss_loyalty_rules');
                                if($result['success'] == 'true')
                                {
                                    $this->ReturnJsonArray(true,false,"Rule Defined Successfully");
                                    die;
                                }
                                else
                                {
                                    $this->ReturnJsonArray(false,true,"Please Check Again");
                                    die;    
                                }
                            }
                            else
                            {
							// 	$this->PrettyPrintArray($_POST);
                            //   exit;
                                foreach($amount1 as $key=>$value)
                                {
                                  if(isset($amount2[$key]) && !empty($amount2[$key]))
                                    {
                                      $amount2[$key] = $amount2[$key];
                                    }
                                    else
                                    {
                                      $amount2[$key] = 999999999;
                                    }
                                    $data = array(
                                    'business_outlet_id'  => $this->input->post('loyalty_business_outlet_id'),
                                    'business_admin_id'   => $this->input->post('business_outlet_business_admin'),
                                    'rule_type'           => $this->input->post('business_outlet_rule'),
                                    'rule_validity'       => $this->input->post('business_outlet_rule_validity'),
                                    'amount1'             => $amount1[$key],
                                    'amount2'             => $amount2[$key],
                                    'points'              => $points[$key],
                                    'cashback'            => $cashback[$key]
                                    );
                                    $result = $this->SuperAdminModel->InsertRule($data,'mss_loyalty_rules');
                                    // $this->PrettyPrintArray($result);
                                    // exit;
                                    if($result['success'] == 'true')
                                    {
                                    $counter = $counter + 1;
                                    }
                                }
                                if($counter == count($amount1))
                                {
                                    $this->ReturnJsonArray(true,false,"Rule Defined Successfully");
                                    die;
                                }
                                else{
                                    $this->ReturnJsonArray(false,true,"Please Check Again");
                                    die;
                                }
                            }       
                }
            }
            // }
            // else if($CheckOutletRuleExists['success'] == 'true' && $CheckOutletRuleExists['error'] == 'false')
            // {
                //   $this->PrettyPrintArray($CheckOutletRuleExists);
                //   exit;
            //  
        }
        else
        {
            $this->LogoutUrl(base_url()."SuperAdmin/Login");
        }
	}
	public function GetBusinessOutletLoyaltyRule()
	{
		// $this->PrettyPrintArray($_GET);
		// exit;
		$where = $_GET['business_outlet_id'];
		$data['outlet_rule']=$this->SuperAdminModel->GetExistingRule($where);
		// $this->PrettyPrintArray($data);
		// exit;
		if($data['outlet_rule']['success'] == 'true')
		{
			//$data['outlet_rule']=$data['outlet_rule']['res_arr'];
			// $this->PrettyPrintArray($data['outlet_rule']);
			// exit;
			header("Content-type: application/json");
			print(json_encode($data['outlet_rule']));
			die;
		}
		else
		{
			// $data['outlet_rule'] +=['res_arr'];
			// $data['outlet_rule']=$data['outlet_rule']['res_arr'];
			header("Content-type: application/json");
			return $this->ReturnJsonArray(false,true,'No rules Configured');
			die;
		}
	}
	public function UpdateLoyaltyRule()
    {
        // $this->PrettyPrintArray($_POST);
        // exit;
        if($this->IsLoggedIn('super_admin'))
        {
            if(isset($_POST) && !empty($_POST))
            {
                // $data['admin_id']=$_POST['var'];
                $this->form_validation->set_rules('edit_business_outlet_rule', 'Business Outlet Rule', 'required');
                $this->form_validation->set_rules('edit_business_outlet_rule_validity', 'Business Outlet Rule Validity', 'required');
                if ($this->form_validation->run() == FALSE) 
                {
                    $data = array(
                                    'success' => 'false',
                                    'error'   => 'true',
                                    'message' =>  validation_errors()
                                 );
                    header("Content-type: application/json");
                    print(json_encode($data, JSON_PRETTY_PRINT));
                    die;
                }
                else
                {
                        // $this->PrettyPrintArray($_POST);
                        // exit;
                        $where = array('business_outlet_id' => $this->input->post('business_outlet_id'));
                        $CheckOutletRuleExists = $this->SuperAdminModel->CheckOutletRuleExists($where);
                        // $this->PrettyPrintArray($CheckOutletRuleExists);
						// exit;
						if($CheckOutletRuleExists['success']=='true' && $CheckOutletRuleExists['error']=='false')
						{
								$data=array(
									'business_outlet_id' =>$this->input->post('business_outlet_id'),
									'rule_status'=>0
								  );
								  $update_res = $this->SuperAdminModel->Update($data,'mss_loyalty_rules','business_outlet_id');	
								  if($update_res['success'] == 'true')
								  {
									$amount1  = $this->input->post('amount1[]');
									$amount2  = $this->input->post('amount2[]');
									$points   = $this->input->post('points[]');
									$cashback = $this->input->post('cashback[]');
									$counter  = 0;
									if(isset($_POST['business_outlet_rule']) == 'Cashback Visits')
                            		{
							            // $this->PrettyPrintArray($_POST);
                             			// exit;
										$data=array(
										'business_outlet_id' =>$this->input->post('business_outlet_id'),
										'rule_status'=>0
										);
										$update_res = $this->SuperAdminModel->Update($data,'mss_loyalty_rules','business_outlet_id');
										$data = array(
											'business_outlet_id'  => $this->input->post('business_outlet_id'),
											'business_admin_id'   => $this->input->post('business_outlet_business_admin'),
											'rule_type'           => $this->input->post('edit_business_outlet_rule'),
											'rule_validity'       => $this->input->post('edit_business_outlet_rule_validity')
										);
										
										$result = $this->SuperAdminModel->InsertRule($data,'mss_loyalty_rules');
										
										if($result['success'] == 'true')
                                		{
											$data_offer=array(
												'business_outlet_id' => $this->input->post('business_outlet_id'),
												'offers_status' => 0
											  );
											  $update_offer = $this->SuperAdminModel->Update($data_offer,'mss_loyalty_offer_integrated','business_outlet_id');
											$this->ReturnJsonArray(true,false,"Rule Updated Successfully");
											die;
										}
										else
										{
											$this->ReturnJsonArray(false,true,"Rule Update Fails!");
											die; 
										}
									}
									else{
										foreach($amount1 as $key=>$value)
										{
											if(isset($amount2[$key]) && !empty($amount2[$key]))
												{
												$amount2[$key] = $amount2[$key];
												}
												else
												{
												$amount2[$key] = 999999999;
												}
												$data = array(
												'business_outlet_id'  => $this->input->post('business_outlet_id'),
												'business_admin_id'   => $this->input->post('business_outlet_business_admin'),
												'rule_type'           => $this->input->post('edit_business_outlet_rule'),
												'rule_validity'       => $this->input->post('edit_business_outlet_rule_validity'),
												'amount1'             => $amount1[$key],
												
												'amount2'             => $amount2[$key],
												'points'              => $points[$key],
												'cashback'            => $cashback[$key]
												);
												$result = $this->SuperAdminModel->InsertRule($data,'mss_loyalty_rules');
												// $this->PrettyPrintArray($result);
												// exit;
												if($result['success'] == 'true')
												{
												$counter = $counter + 1;
												}
										}
										if($counter == count($amount1))
										{
												$data_offer=array(
													'business_outlet_id' => $this->input->post('business_outlet_id'),
													'offers_status' => 0
												);
												$update_offer = $this->SuperAdminModel->Update($data_offer,'mss_loyalty_offer_integrated','business_outlet_id');
											$this->ReturnJsonArray(true,false,"Rule Updated Successfully");
											die;
										}
										else{
											$this->ReturnJsonArray(false,true,"Rule Update Fails/Offer is Not defined");
											die;
										}
									}
								  }
								  else
								  {
									$this->ReturnJsonArray(false,true,"Rule Update Fails!");
									die; 
								  }
						}
						else if($CheckOutletRuleExists['success'] == 'false' && $CheckOutletRuleExists['error'] == 'true')
						{
							// $this->PrettyPrintArray($_POST);
							// exit;
									$amount1  = $this->input->post('amount1[]');
									$amount2  = $this->input->post('amount2[]');
									$points   = $this->input->post('points[]');
									$cashback = $this->input->post('cashback[]');
									$counter  = 0;
									if(isset($_POST['edit_business_outlet_rule']) == 'Cashback Visits')
                            		{
							            // $this->PrettyPrintArray($_POST);
                             			// exit;
										$data=array(
										'business_outlet_id' =>$this->input->post('business_outlet_id'),
										'rule_status'=>0
										);
										$update_res = $this->SuperAdminModel->Update($data,'mss_loyalty_rules','business_outlet_id');
										$data = array(
											'business_outlet_id'  => $this->input->post('business_outlet_id'),
											'business_admin_id'   => $this->input->post('business_outlet_business_admin'),
											'rule_type'           => $this->input->post('edit_business_outlet_rule'),
											'rule_validity'       => $this->input->post('edit_business_outlet_rule_validity')
										);
										
										$result = $this->SuperAdminModel->InsertRule($data,'mss_loyalty_rules');
										
										if($result['success'] == 'true')
                                		{
											$this->ReturnJsonArray(true,false,"Rule Updated Successfully");
											die;
										}
										else
										{
											$this->ReturnJsonArray(false,true,"Rule Update Fails!");
											die; 
										}
									}
									else{
										foreach($amount1 as $key=>$value)
										{
											if(isset($amount2[$key]) && !empty($amount2[$key]))
												{
												$amount2[$key] = $amount2[$key];
												}
												else
												{
												$amount2[$key] = 999999999;
												}
												$data = array(
												'business_outlet_id'  => $this->input->post('business_outlet_id'),
												'business_admin_id'   => $this->input->post('business_outlet_business_admin'),
												'rule_type'           => $this->input->post('edit_business_outlet_rule'),
												'rule_validity'       => $this->input->post('edit_business_outlet_rule_validity'),
												'amount1'             => $amount1[$key],
												
												'amount2'             => $amount2[$key],
												'points'              => $points[$key],
												'cashback'            => $cashback[$key]
												);
												$result = $this->SuperAdminModel->InsertRule($data,'mss_loyalty_rules');
												// $this->PrettyPrintArray($result);
												// exit;
												if($result['success'] == 'true')
												{
												$counter = $counter + 1;
												}
										}
										if($counter == count($amount1))
										{
												$data_offer=array(
													'business_outlet_id' => $this->input->post('business_outlet_id'),
													'offers_status' => 0
												);
												$update_offer = $this->SuperAdminModel->Update($data_offer,'mss_loyalty_offer_integrated','business_outlet_id');
											$this->ReturnJsonArray(true,false,"Rule Updated Successfully");
											die;
										}
										else{
											$this->ReturnJsonArray(false,true,"Rule Update Fails/Offer is Not defined");
											die;
										}
									}
						}

                }
            }
        }
        else
        {
            $this->LogoutUrl(base_url()."SuperAdmin/Login");
        }
 	}
	public function DeleteOuletRule()
	{
		
		if($this->IsLoggedIn('super_admin'))
		{
		if(isset($_POST) && !empty($_POST))
		{
			$data = array(
			'business_outlet_id'=>$this->input->post('business_outlet_id'),
			'rule_id'=>$this->input->post('rule_id'),
			'rule_status' => 0
			);
			$rule_type = $this->SuperAdminModel->DetailsById($this->input->post('rule_id'),'mss_loyalty_rules','rule_id');
			if($rule_type['res_arr']['rule_type'] == 'Offers Single Rule' || $rule_type['res_arr']['rule_type'] == 'Cashback Single Rule')
			{
			$delete_res = $this->SuperAdminModel->Update($data,'mss_loyalty_rules','rule_id');
			if($delete_res['success'] == 'true')
			{
				$this->ReturnJsonArray(true,false,"Rule deleted Successfully");
				die;
			}
			else
			{
				$this->ReturnJsonArray(false,true,'Rule Deletion Error!');
				die;
			}
			}
			else
			{
			$delete_res = $this->SuperAdminModel->Update($data,'mss_loyalty_rules','rule_id');
			if($delete_res['success'] == 'true')
			{
				$this->ReturnJsonArray(true,false,"Rule deleted Successfully");
				die;
			}
			else
			{
				$this->ReturnJsonArray(false,true,'Rule Deletion Error!');
				die;
			}
			}
			
		}
		}
	}
  public function AddMoreLoyaltyRule()
    {
        // $this->PrettyPrintArray($_POST);
        // exit;
        if($this->IsLoggedIn('super_admin'))
        {
            if(isset($_POST) && !empty($_POST))
            {
                // $data['admin_id']=$_POST['var'];
                $this->form_validation->set_rules('business_outlet_rule', 'Business Outlet Rule', 'required');
                $this->form_validation->set_rules('business_outlet_rule_validity', 'Business Outlet Rule Validity', 'required');
                if ($this->form_validation->run() == FALSE) 
                {
                    $data = array(
                                    'success' => 'false',
                                    'error'   => 'true',
                                    'message' =>  validation_errors()
                                 );
                    header("Content-type: application/json");
                    print(json_encode($data, JSON_PRETTY_PRINT));
                    die;
                }
                else
                {
                        // $this->PrettyPrintArray($_POST);
                        // exit;
                        $where = array('business_outlet_id' => $this->input->post('loyalty_business_outlet_id'));
                        $CheckOutletRuleExists = $this->SuperAdminModel->CheckOutletRuleExists($where);
                        // $this->PrettyPrintArray($CheckOutletRuleExists);
                        // exit;
                        // if($CheckOutletRuleExists['success'] == 'true' && $CheckOutletRuleExists['error'] == 'false')
                        // {
                            // $this->PrettyPrintArray($_POST);
                            //   exit;
                            $amount1  = $this->input->post('amount1[]');
                            $amount2  = $this->input->post('amount2[]');
                            $points   = $this->input->post('points[]');
                            $cashback = $this->input->post('cashback[]');
                            $counter  = 0;//To keep track of no of successful submisions
                            // $this->PrettyPrintArray($amount1);
                            // exit;
                            if($_POST['business_outlet_rule'] == 'Cashback Visits')
                            {
							                // 	$this->PrettyPrintArray($_POST);
                             //   exit;
                                $data=array(
                                  'business_outlet_id' =>$this->input->post('loyalty_business_outlet_id'),
                                  'rule_status'=>0
                                );
                                $update_res = $this->SuperAdminModel->Update($data,'mss_loyalty_rules','business_outlet_id');
                                $data = array(
                                    'business_outlet_id'  => $this->input->post('loyalty_business_outlet_id'),
                                    'business_admin_id'   => $this->input->post('business_outlet_business_admin'),
                                    'rule_type'           => $this->input->post('edit_business_outlet_rule'),
                                    'rule_validity'       => $this->input->post('edit_business_outlet_rule_validity')
                                );
                                
                                $result = $this->SuperAdminModel->InsertRule($data,'mss_loyalty_rules');
                                
                                if($result['success'] == 'true')
                                {
                                      
                                    $data_offer=array(
                                      'business_outlet_id' => $this->input->post('loyalty_business_outlet_id'),
                                      'offers_status' => 0
                                    );
                                    $update_offer = $this->SuperAdminModel->Update($data_offer,'mss_loyalty_offer_integrated','business_outlet_id');
                                    if($update_res['success'] == 'true' & $update_offer['success'] == 'true')
                                    {
                                      $this->ReturnJsonArray(true,false,"Rule Updated Successfully");
                                      die;
                                    }
                                    else
                                    {
                                      $this->ReturnJsonArray(false,true,"Rule Update Fails!");
                                      die; 
                                    }
                                }
                                else
                                {
                                    $this->ReturnJsonArray(false,true,"Rule Update Error!");
                                    die;    
                                }
                            }
                            else
                            {
							                // 	$this->PrettyPrintArray($_POST);
                              //   exit;
                              $data=array(
                                'business_outlet_id' =>$this->input->post('business_outlet_id'),
                                'rule_status'=>0
                              );
                              $update_res = $this->SuperAdminModel->Update($data,'mss_loyalty_rules','business_outlet_id');
                                foreach($amount1 as $key=>$value)
                                {
                                  if(isset($amount2[$key]) && !empty($amount2[$key]))
                                    {
                                      $amount2[$key] = $amount2[$key];
                                    }
                                    else
                                    {
                                      $amount2[$key] = 999999999;
                                    }
                                    $data = array(
                                    'business_outlet_id'  => $this->input->post('loyalty_business_outlet_id'),
                                    'business_admin_id'   => $this->input->post('business_outlet_business_admin'),
                                    'rule_type'           => $this->input->post('business_outlet_rule'),
                                    'rule_validity'       => $this->input->post('business_outlet_rule_validity'),
                                    'amount1'             => $amount1[$key],
                                    
                                    'amount2'             => $amount2[$key],
                                    'points'              => $points[$key],
                                    'cashback'            => $cashback[$key]
                                    );
                                    $result = $this->SuperAdminModel->InsertRule($data,'mss_loyalty_rules');
                                    // $this->PrettyPrintArray($result);
                                    // exit;
                                    if($result['success'] == 'true')
                                    {
                                     $counter = $counter + 1;
                                    }
                                }
                                if($counter == count($amount1))
                                {
                                  $data_offer=array(
                                    'business_outlet_id' => $this->input->post('loyalty_business_outlet_id'),
                                    'offers_status' => 0
                                  );
                                  $update_offer = $this->SuperAdminModel->Update($data_offer,'mss_loyalty_offer_integrated','business_outlet_id');
                                  if($update_res['success'] == 'true' & $update_offer['success'] == 'true')
                                  {
                                    $this->ReturnJsonArray(true,false,"Rule Updated Successfully");
                                    die;
                                  }
                                  else{
                                    $this->ReturnJsonArray(false,true,"Rule Update Fails");
                                    die;
                                  }
                                }
                                else{
                                    $this->ReturnJsonArray(false,true,"Rule Update Fails");
                                    die;
                                }
                            }       
                        }
                }
            // }
            // else if($CheckOutletRuleExists['success'] == 'false' && $CheckOutletRuleExists['error'] == 'true')
            // {
            //     //   $this->PrettyPrintArray($CheckOutletRuleExists);
            //     //   exit;
            //     $this->ReturnJsonArray(false,true,"Rule Does Not  Exists");
            //     die;
            // }
        }
        else
        {
            $this->LogoutUrl(base_url()."SuperAdmin/Login");
        }
 	}
    //04-04-2020
	public function UploadCustomerDetails(){
        if($this->IsLoggedIn('super_admin')){
            if(isset($_FILES["file"]["type"])){
                // check file name is not empty
              if (!empty($_FILES['file']['name'])) {        
                // Get File extension eg. 'xlsx' to check file is excel sheet
                $pathinfo = pathinfo($_FILES["file"]["name"]);        
                // check file has extension xlsx, xls and also check file is not empty
                if (($pathinfo['extension'] == 'xlsx' || $pathinfo['extension'] == 'xls') && $_FILES['file']['size'] > 0 ) {            
                // Temporary file name
                $inputFileName = $_FILES['file']['tmp_name'];        
                // Read excel file by using ReadFactory object.
                $reader = ReaderFactory::create(Type::XLSX);    
                //Open file
                $reader->open($inputFileName);
                $count = 1;    
                $successInserts = 0;
                $errorInserts = 0;
                // Number of sheet in excel file
                foreach ($reader->getSheetIterator() as $sheet) {                
                  // Number of Rows in Excel sheet
                  foreach ($sheet->getRowIterator() as $row) {    
                    // It reads data after header.
                    if ($count > 1) {     
                        $data = array(
                            'customer_master_admin_id'=>$row[0],
                            'customer_business_admin_id' => $row[1],
                            'customer_business_outlet_id'      => $row[2],
														'customer_title'      => $row[3],
														'customer_name'   => $row[4],
														'customer_mobile'   => $row[5],
														'customer_dob'   => $row[6]->format('Y-m-d'),
														'customer_doa'     => $row[7]->format('Y-m-d'),
														'customer_virtual_wallet' => $row[8],
														'customer_next_appointment_date'     => $row[9]->format('Y-m-d'),
														'customer_next_appointment_time'  => $row[10],
														'customer_preferred_day'     => $row[11],
														'customer_preferred_service'   => $row[12],
														'customer_recommended_service'   => $row[13],
														'customer_avg_feedback_rating'   => $row[14],
														'customer_last_updated'  => $row[15]->format('Y-m-d'),
														'customer_media_path'  => $row[16],
														'customer_rewards'     => $row[17],
														'customer_cashback'     => $row[18],
														'customer_segment'     => $row[19],
														'customer_email'     => $row[20],
														'customer_pending_amount'     => $row[21],
														'customer_wallet_expiry_date'     => $row[22]->format('Y-m-d'),
														'customer_added_on'     => $row[23]->format('Y-m-d'),
														'home_branch' =>$row[24],
														'last_visit_branch'=>$row[25]
													);
													
											// $this->PrettyPrintArray($data);

														$result = $this->SuperAdminModel->Insert($data,'mss_customers');
														if($result['success'] == 'true'){
														$successInserts++;
                          }
                          elseif($result['error'] == 'true'){
                            $errorInserts++;
                          }
                    }
                    $count++;
                  }
                }  
                $reader->close(); 
                $this->ReturnJsonArray(true,false,"File uploaded with successful entries : ".$successInserts.", errors : ".$errorInserts."");
                    die;   
                } 
                else {    
                    $this->ReturnJsonArray(false,true,"Please Select Valid Excel File!");
                    die;
                }    
              } 
            else {  
                $this->ReturnJsonArray(false,true,"Please Select Excel File!");  
              die;
            }
            }
        }
        else{
            $this->LogoutUrl(base_url()."SuperAdmin");
        }
    }
	public function BulkUploadPackageTransaction(){
        if($this->IsLoggedIn('super_admin')){
            if(isset($_FILES["file"]["type"])){
                // check file name is not empty
              if (!empty($_FILES['file']['name'])) {        
                // Get File extension eg. 'xlsx' to check file is excel sheet
                $pathinfo = pathinfo($_FILES["file"]["name"]);        
                // check file has extension xlsx, xls and also check file is not empty
                if (($pathinfo['extension'] == 'xlsx' || $pathinfo['extension'] == 'xls') && $_FILES['file']['size'] > 0 ) {            
                // Temporary file name
                $inputFileName = $_FILES['file']['tmp_name'];        
                // Read excel file by using ReadFactory object.
                $reader = ReaderFactory::create(Type::XLSX);    
                //Open file
                $reader->open($inputFileName);
                $count = 1;    
                $successInserts = 0;
                $errorInserts = 0;
                // Number of sheet in excel file
                foreach ($reader->getSheetIterator() as $sheet) {                
                  // Number of Rows in Excel sheet
                  foreach ($sheet->getRowIterator() as $row) {    
                    // It reads data after header.
                    if ($count > 1) {     
                        $data = array(
                            'package_txn_id' => $row[0],
                            'package_txn_unique_serial_id'      => $row[1],
                             'datetime'      => $row[2]->format('Y-m-d'),
                             'outlet_name'   => $row[3],
                             'package_txn_customer_id'   => $row[4],
                             'customer_number'   => $row[5],
                             'customer_name'     => $row[6],
                             'category_type'     => $row[7],
                             'package_txn_discount'  => $row[8],
                             'package_txn_value'     => $row[9],
                             'package_txn_cashier'   => $row[10],
                             'package_txn_expert'   => $row[11],
                             'expert_name'   => $row[12],
                             'CGST'  => $row[13],
                             'SGST'  => $row[14],
                             'total_tax'     => $row[15],
                             'outlet_id' => $row[16],
                             'business_admin_id'=>$row[17],
                             'master_admin_id'=>$row[18]
                                    );
                                    $result = $this->SuperAdminModel->Insert($data,'mss_package_transactions_replica');
                                    if($result['success'] == 'true'){
                                        $successInserts++;
                          }
                          elseif($result['error'] == 'true'){
                            $errorInserts++;
                          }
                    }
                    $count++;
                  }
                }  
                $reader->close(); 
                $this->ReturnJsonArray(true,false,"File uploaded with successful entries : ".$successInserts.", errors : ".$errorInserts."");
                    die;   
                } 
                else {    
                    $this->ReturnJsonArray(false,true,"Please Select Valid Excel File!");
                    die;
                }    
              } 
            else {  
                $this->ReturnJsonArray(false,true,"Please Select Excel File!");  
              die;
            }
            }
        }
        else{
            $this->LogoutUrl(base_url()."SuperAdmin");
        }
	}
	public function BulkUploadServiceTransaction(){
        if($this->IsLoggedIn('super_admin')){
            if(isset($_FILES["file"]["type"])){
                // check file name is not empty
              if (!empty($_FILES['file']['name'])) {        
                // Get File extension eg. 'xlsx' to check file is excel sheet
                $pathinfo = pathinfo($_FILES["file"]["name"]);        
                // check file has extension xlsx, xls and also check file is not empty
                if (($pathinfo['extension'] == 'xlsx' || $pathinfo['extension'] == 'xls') && $_FILES['file']['size'] > 0 ) {            
                // Temporary file name
                $inputFileName = $_FILES['file']['tmp_name'];        
                // Read excel file by using ReadFactory object.
                $reader = ReaderFactory::create(Type::XLSX);    
                //Open file
                $reader->open($inputFileName);
                $count = 1;    
                $successInserts = 0;
                $errorInserts = 0;
                // Number of sheet in excel file
                foreach ($reader->getSheetIterator() as $sheet) {                
                  // Number of Rows in Excel sheet
                  foreach ($sheet->getRowIterator() as $row) {    
                    // It reads data after header.
                    if ($count > 1) {     
                        $data = array(
                                        'txn_service_id'  =>$row[0],
                                        'txn_service_service_id' => $row[1],
                                        'txn_service_expert_id'      => $row[2],
                                        'expert_name' => $row[3],
                                        'txn_service_txn_id'      => $row[4],
                                        'txn_datetime'      => $row[5]->format('Y-m-d'),
                                        'business_outlet_name'   => $row[6],
                                        'cust_id'   => $row[7],
                                        'cust_mobile'   => $row[8],
                                        'cust_name'     => $row[9],
                                        'service_name' => $row[10],
                                        'service_type' => $row[11],
                                        'txn_service_quantity'      => $row[12],
                                        'txn_service_discount_percentage'     => $row[13],
                                        'txn_service_discount_absolute'     => $row[14],
                                        'txn_service_discounted_price'     => $row[15],
                                        'CGST'     => $row[16],
                                        'SGST'     => $row[17],
                                        'other_tax'  => $row[18],
                                        'txn_service_status'  => $row[19],
                                        'business_oulet_id' => $row[20],
                                        'business_admin_id' => $row[21],
                                        'master_admin_id'   =>$row[22]
                                    );
                                    $result = $this->SuperAdminModel->Insert($data,'mss_transaction_services_replica');
                                    if($result['success'] == 'true'){
                                        $successInserts++;
                          }
                          elseif($result['error'] == 'true'){
                            $errorInserts++;
                          }
                    }
                    $count++;
                  }
                }  
                $reader->close(); 
                $this->ReturnJsonArray(true,false,"File uploaded with successful entries : ".$successInserts.", errors : ".$errorInserts."");
                    die;   
                } 
                else {    
                    $this->ReturnJsonArray(false,true,"Please Select Valid Excel File!");
                    die;
                }    
              } 
            else {  
                $this->ReturnJsonArray(false,true,"Please Select Excel File!");  
              die;
            }
            }
        }
        else{
            $this->LogoutUrl(base_url()."SuperAdmin");
        }
    }



	public function CustomerHistory(){
        if($this->IsLoggedIn('super_admin')){
            $data = $this->GetDataForSuperAdmin("HISTORY");
            $data['sidebar_collapsed'] = "true";
            $this->load->view('superAdmin/sa_customer_history_view',$data);
        }   
        else{   
            $this->LogoutUrl(base_url()."SuperAdmin/Login");
        }
    }
    public function BulkUploadTransaction(){
        if($this->IsLoggedIn('super_admin')){
            if(isset($_FILES["file"]["type"])){
                // check file name is not empty
              if (!empty($_FILES['file']['name'])) {        
                // Get File extension eg. 'xlsx' to check file is excel sheet
                $pathinfo = pathinfo($_FILES["file"]["name"]);        
                // check file has extension xlsx, xls and also check file is not empty
                if (($pathinfo['extension'] == 'xlsx' || $pathinfo['extension'] == 'xls') && $_FILES['file']['size'] > 0 ) {            
                // Temporary file name
                $inputFileName = $_FILES['file']['tmp_name'];        
                // Read excel file by using ReadFactory object.
                $reader = ReaderFactory::create(Type::XLSX);    
                //Open file
                $reader->open($inputFileName);
                $count = 1;    
                $successInserts = 0;
                $errorInserts = 0;
                // Number of sheet in excel file
                foreach ($reader->getSheetIterator() as $sheet) {                
                  // Number of Rows in Excel sheet
                  foreach ($sheet->getRowIterator() as $row) {    
                    // It reads data after header.
                    if ($count > 1) {     
                        $data = array(
                                        'txn_id' => $row[0],
                                        'txn_unique_serial_id'      => $row[1],
                                        'txn_datetime'      => $row[2]->format('Y-m-d'),
                                        'outlet_name'   => $row[3],
                                        'txn_customer_id'   => $row[4],
                                        'txn_customer_number'   => $row[5],
                                        'txn_customer_name'     => $row[6],
                                        'category_type'     => $row[7],
                                        'Quantity'  => $row[8],
                                        'txn_discount'  => $row[9],
                                        'txn_value'     => $row[10],
                                        'txn_cashier'   => $row[11],
                                        'CGST'  => $row[12],
                                        'SGST'  => $row[13],
                                        'txn_total_tax'     => $row[14],
                                        'txn_pending_amount'    => $row[15],
                                        'txn_loyalty_points_debited'    => $row[16],
                                        'txn_cashback_debited'=>$row[17],
                                        'txn_loyalty_points_balance'=>$row[18],
                                        'txn_status'    => $row[19],
                                        'txn_remarks'   => $row[20],
                                        'txn_outlet_id' => $row[21],
                                        'txn_business_admin_id'     =>$row[22] ,
                                        'master_admin_id'   => $row[23]
                                    );
                                    // $this->PrettyPrintArray($data);
                                    $result = $this->SuperAdminModel->Insert($data,'mss_transactions_replica');
                                    if($result['success'] == 'true'){
                                        $successInserts++;
                          }
                          elseif($result['error'] == 'true'){
                            $errorInserts++;
                          }
                    }
                    $count++;
                  }
                }  
                $reader->close(); 
                $this->ReturnJsonArray(true,false,"File uploaded with successful entries : ".$successInserts.", errors : ".$errorInserts."");
                    die;   
                } 
                else {    
                    $this->ReturnJsonArray(false,true,"Please Select Valid Excel File!");
                    die;
                }    
              } 
            else {  
                $this->ReturnJsonArray(false,true,"Please Select Excel File!");  
              die;
            }
            }
        }
        else{
            $this->LogoutUrl(base_url()."SuperAdmin");
        }
    }

}
	    
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cashier extends CI_Controller {

	/*
		Definition and usage of temporary session variables for POS billings
		

		--> $this->session->userdata['POS']['.$customer_id.']

		Now to access any particular value
		
		--> $this->session->userdata['POS']['.$customer_id.']['customer_name']		
		

		In the above session we will have multiple session varibles for corresponding
		to each customer who are currently availing the services in the Salon

		They can now be deleted from the session variables very easily 
	*/

	/*
		Definition and usage of temporary session variables for the Cart for customers

		--> $this->session->userdata['cart']['.$customer_id.'][Array of Added Services]
	
		This session variable will be completely distroyed upon logout and partially unset upon settle order for particular customer...

	*/
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

    private function GetDataForCashier($title){
        $data = array();
        $data['title'] = $title;
        $data['business_admin_packages'] = $this->GetBusinessAdminPackages();
        $data['cashier_details']  = $this->GetCashierDetails();
        $where = array(
            'business_admin_id' => $this->session->userdata['logged_in']['business_admin_id'],
            'business_outlet_id'=> $this->session->userdata['logged_in']['business_outlet_id']
        );
        $data['loyalty_payment']=$this->BusinessAdminModel->GetLoyaltyAmountReceived($where);
        $data['loyalty_payment']=$data['loyalty_payment']['res_arr'][0]['loyalty_wallet'];
        $data['cards_data']['payment_wise'] = $this->BusinessAdminModel->GetSalesPaymentWiseData($where);
        $data['cards_data']['payment_wise']=$data['cards_data']['payment_wise']['res_arr'];
        $data['nav_details']['revenue']=0;
        $data['nav_details']['visit']=0;
        $data['nav_details']['appointment']=0;
        $data['svisits']=$this->CashierModel->ServiceVisitSales();
        if($data['svisits']['success'] == 'true'){
            $data['nav_details']['visit']=$data['svisits']['res_arr'][0]['visit'];
            $data['nav_details']['revenue']=$data['svisits']['res_arr'][0]['service'];
            // $this->PrettyPrintArray($data['nav_details']['revenue']);
        }
        $data['appointment']=$this->CashierModel->GetAllAppointmentsCount();
        if($data['appointment']['success']=='true'){
            $data['nav_details']['appointment']=$data['appointment']['res_arr'][0]['count'];
        }
        $data['revenue']=$this->CashierModel->PackageVisitSales();
        if($data['revenue']['success'] == 'true'){
            $data['nav_details']['revenue']=$data['nav_details']['revenue']+$data['revenue']['res_arr'][0]['packages'];
            $data['nav_details']['visit']=$data['nav_details']['visit']+$data['revenue']['res_arr'][0]['visit'];
        }
        // $this->PrettyPrintArray($data);
        return $data;
    }
	
	//Default Page for cashier
	public function index(){
		if($this->IsLoggedIn('cashier')){
			$data = $this->GetDataForCashier("Dashboard");
			if(isset($this->session->userdata['payment'])){
 				$this->session->unset_userdata('payment');
 			}

 			//Unset any session so that no one interfere in billing logic
			if(isset($this->session->userdata['Package_Customer'])){
 				$this->session->unset_userdata('Package_Customer');
 			}

 			if(isset($this->session->userdata['package_cart'])){
				$this->session->unset_userdata('package_cart');
			}

			if(isset($this->session->userdata['package_payment'])){
		 		$this->session->unset_userdata('package_payment');
		 	}
			//

			if(isset($this->session->userdata['POS'])){
				$data['customers'] = $this->session->userdata['POS'];
			}
			
			if(isset($this->session->userdata['cart'])){
				$data['cart'] = $this->session->userdata['cart'];
			}
		    			if(array_search("Marks360",$data["business_admin_packages"]))
              {
                $rules = $this->BusinessAdminModel->RuleDetailsById($data['cashier_details']['business_outlet_id'],'mss_loyalty_rules','business_outlet_id');
                if($rules['success'] == 'true')
                    $data['rules'] = $rules['res_arr'];
              }
              else
              {
                $data['rules'] = ['res_arr'=>''];
                $data['rules'] = $data['rules']['res_arr'];
              }
        			$data['sidebar_collapsed'] = "true";
        			$this->load->view('cashier/cashier_dashboard_view',$data);
        		}
        		else{
        			$data['title'] = "Login";
        			$this->load->view('cashier/cashier_login_view',$data);
        		}
	}

	//constructor of the Alumni Controller
	public function __construct(){
		parent::__construct();
		date_default_timezone_set('Asia/Kolkata');
		$this->load->model('AnalyticsModel');
		$this->load->model('CashierModel');
		$this->load->model('AppointmentsModel');
		$this->load->model('POSModel');
		$this->load->model('BusinessAdminModel');
		$this->load->helper('ssl_helper');
  }		
	
  private function ReturnJsonArray($success,$error,$message){
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
 			
 			if(isset($this->session->userdata['POS'])){
 				$this->session->unset_userdata('POS');
 			}
 			if(isset($this->session->userdata['cart'])){
 				$this->session->unset_userdata('cart');
 			}

 			if(isset($this->session->userdata['payment'])){
 				$this->session->unset_userdata('payment');
 			}

 			if(isset($this->session->userdata['Package_Customer'])){
 				$this->session->unset_userdata('Package_Customer');
 			}

 			if(isset($this->session->userdata['package_cart'])){
				$this->session->unset_userdata('package_cart');
			}

			if(isset($this->session->userdata['package_payment'])){
		 		$this->session->unset_userdata('package_payment');
		 	}
 			
 			$this->session->sess_destroy();
 		}
		redirect(base_url().'Cashier/Login/','refresh');
	}

	//function for logging out the user
	private function LogoutUrl($url){
		if(isset($this->session->userdata['logged_in']) && !empty($this->session->userdata['logged_in'])){
 			$this->session->unset_userdata('logged_in');
 			
 			if(isset($this->session->userdata['POS'])){
 				$this->session->unset_userdata('POS');
 			}
 			if(isset($this->session->userdata['cart'])){
 				$this->session->unset_userdata('cart');
 			}

 			if(isset($this->session->userdata['payment'])){
 				$this->session->unset_userdata('payment');
 			}

 			if(isset($this->session->userdata['Package_Customer'])){
 				$this->session->unset_userdata('Package_Customer');
 			}

 			if(isset($this->session->userdata['package_cart'])){
				$this->session->unset_userdata('package_cart');
			}

			if(isset($this->session->userdata['package_payment'])){
		 		$this->session->unset_userdata('package_payment');
		 	}
			 
			 
 			$this->session->sess_destroy();
 		}  
	  redirect($url,'refresh');
	}

	//function for login
	//function for login
    public function Login(){
        if(isset($_POST) && !empty($_POST)){
            $this->form_validation->set_rules('employee_email', 'Email', 'trim|required|max_length[100]');
            $this->form_validation->set_rules('employee_password', 'Password', 'trim|required');
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
                    'employee_email'    => $this->input->post('employee_email'),
                    'employee_password' => $this->input->post('employee_password')
                );
                $result = $this->CashierModel->CashierLogin($data);
                if ($result['success'] == 'true') 
                {
                    $result = $this->CashierModel->CashierByEmail($data['employee_email']);
                    //$password =  password_hash('password',PASSWORD_DEFAULT);
                    // $this->PrettyPrintArray($result);
                    if($result['success'] == 'true'){
                        if($data['employee_email'] == $result['res_arr']['employee_email'] && password_verify($data['employee_password'],$result['res_arr']['employee_password']) && (int)$result['res_arr']['employee_is_active'] && $result['res_arr']['business_admin_account_expiry_date'] >= date('Y-m-d'))
                        { 
                            $session_data = array(
                                'employee_id'      => $result['res_arr']['employee_id'],
                                'employee_email'   => $result['res_arr']['employee_email'],
                                'employee_name'    => $result['res_arr']['employee_first_name'].' '.$result['res_arr']['employee_last_name'],
                                'user_type'        => 'cashier',
                                'master_admin_id'   =>$result['res_arr']['business_master_admin_id'],
                                'business_admin_id'=> $result['res_arr']['employee_business_admin'],
                                'business_outlet_id'=> $result['res_arr']['employee_business_outlet']
                            );
                            $this->session->set_userdata('logged_in', $session_data);
                            $this->ReturnJsonArray(true,false,'Valid login!');
                            die;
                        }
                        else
                        {
                            if (!(int)$result['res_arr']['employee_is_active']) {
                                $this->ReturnJsonArray(false,true,'Your account is suspended. Please contact Admin');
                                die;    
                            }
                            elseif($result['res_arr']['business_admin_account_expiry_date'] < date('Y-m-d')) {
                                $this->ReturnJsonArray(false,true,'Your account is expired. Please renew your subscription!');
                                die;
                            }
                            else{
                                $this->ReturnJsonArray(false,true,'Wrong email or password !');
                                die;
                            }
                        }
                    }
                    elseif($result['error'] == 'true'){
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
            $this->load->view('cashier/cashier_login_view',$data);
        }
    }

	public function ResetCashierPassword(){
		if($this->IsLoggedIn('cashier')){
			if(isset($_POST) && !empty($_POST)){
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
											"employee_password" => password_hash($new_password,PASSWORD_DEFAULT),
									  	"employee_id"       => $this->session->userdata['logged_in']['employee_id']
										);
						
						$result = $this->CashierModel->Update($data,'mss_employees','employee_id');
						
						if($result['success'] == 'true'){
							$this->ReturnJsonArray(true,false,"Password changed successfully!");
							die;
            }
            elseif($result['error'] == 'true'){
            	$this->ReturnJsonArray(false,true,"DB Error!");
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
		  	//Unset any session so that no one interfere in billing logic
				if(isset($this->session->userdata['Package_Customer'])){
	 				$this->session->unset_userdata('Package_Customer');
	 			}

	 			if(isset($this->session->userdata['package_cart'])){
					$this->session->unset_userdata('package_cart');
				}

				if(isset($this->session->userdata['payment'])){
 					$this->session->unset_userdata('payment');
 				}

 				if(isset($this->session->userdata['package_payment'])){
		 			$this->session->unset_userdata('package_payment');
		 		}
				//

		    $data = $this->GetDataForCashier("Reset Password");
		    $this->load->view('cashier/cashier_reset_password_view',$data);
			}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}					
	}

	//Dashboard
	public function Dashboard(){
		if($this->IsLoggedIn('cashier')){
			$data = $this->GetDataForCashier("Dashboard");			
			if(isset($this->session->userdata['payment'])){
 				$this->session->unset_userdata('payment');
 			}
 			//Unset any session so that no one interfere in billing logic
			if(isset($this->session->userdata['Package_Customer'])){
 				$this->session->unset_userdata('Package_Customer');
 			}

 			if(isset($this->session->userdata['package_cart'])){
				$this->session->unset_userdata('package_cart');
			}

			if(isset($this->session->userdata['package_payment'])){
 				$this->session->unset_userdata('package_payment');
 			}
			//

			if(isset($this->session->userdata['POS'])){
				$data['customers'] = $this->session->userdata['POS'];
				
			}

			if(isset($this->session->userdata['cart'])){
				$data['cart'] = $this->session->userdata['cart'];
			}
			if(array_search("Marks360",$data["business_admin_packages"]))
            {
                $rules = $this->BusinessAdminModel->RuleDetailsById($data['cashier_details']['business_outlet_id'],'mss_loyalty_rules','business_outlet_id');
                if(array_search('res_arr',$rules)==false)
                {
                    $rules+=['res_arr'=>''];
                }
                $data['rules'] = $rules['res_arr'];
            }
            else
            {
                $data['rules'] = ['res_arr'=>''];
                // $data['rules'] = $rules['res_arr'];
						}
						// $this->PrettyPrintArray($this->session->all_userdata());
			$data['sidebar_collapsed'] = "true";
			$this->load->view('cashier/cashier_dashboard_view',$data);
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/Login");
		}
	}

	private function GetBusinessAdminPackages(){
		if($this->IsLoggedIn('cashier')){
			$data = $this->BusinessAdminModel->BusinessAdminPackages($this->session->userdata['logged_in']['business_admin_id']);
			if($data['success'] == 'true'){
				$temp = array();
				
				foreach ($data['res_arr'] as $d) {
					if($d['package_expiry_date'] >= date('Y-m-d')){
						array_push($temp,$d['package_name']);
					}
				}
				return $temp;
			}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}	
	}

	private function GetCashierDetails(){
		if($this->IsLoggedIn('cashier')){

			$data = $this->CashierModel->GetCashierPersonal($this->session->userdata['logged_in']['employee_id']);
			if($data['success'] == 'true'){	
				return $data['res_arr'];
			}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}	
	}

	public function GetCustomerData(){
		if($this->IsLoggedIn('cashier')){
			if(isset($_GET) && !empty($_GET)){
				$data = $this->CashierModel->SearchCustomers($_GET['query'],$this->session->userdata['logged_in']['business_admin_id'],$this->session->userdata['logged_in']['business_outlet_id'],$this->session->userdata['logged_in']['master_admin_id']);
				if($data['success'] == 'true'){	
					$this->ReturnJsonArray(true,false,$data['res_arr']);
					die;
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}
	}
	
	public function GetSearchServiceData(){
		if($this->IsLoggedIn('cashier')){
			if(isset($_GET) && !empty($_GET)){
				$data = $this->CashierModel->SearchServices($_GET['query'],$this->session->userdata['logged_in']['business_admin_id'],$this->session->userdata['logged_in']['business_outlet_id']);

				if($data['success'] == 'true'){	
					$this->ReturnJsonArray(true,false,$data['res_arr']);
					die;
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}
	}
	
	public function GetCustomer(){
		if($this->IsLoggedIn('cashier')){
			if(isset($_GET) && !empty($_GET)){
				$data = $this->CashierModel->GetCompleteCustomer($_GET['customer_id']);
				
				if($data['success'] == 'true'){		
					header("Content-type: application/json");
					print(json_encode($data['res_arr'], JSON_PRETTY_PRINT));
					die;	
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}	
	}

	private function GetCustomerBilling($customer_id){
		if($this->IsLoggedIn('cashier')){
			$data = $this->CashierModel->DetailsById($customer_id,'mss_customers','customer_id');
			if($data['success'] == 'true'){	
				return $data['res_arr'];
			}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}		
	}

	private function IsPackageCustomer($customer_id){
		if($this->IsLoggedIn('cashier')){
			$data = $this->CashierModel->BoolPackageCustomer($customer_id);
			if($data['success'] == 'true'){	
				return $data['res_arr'];

			}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}		
	}

	private function GetRawMaterials(){
		if($this->IsLoggedIn('cashier')){
			$where = array(
				'raw_material_business_outlet_id' => $this->session->userdata['logged_in']['business_admin_id'],
				'raw_material_business_outlet_id'=> $this->session->userdata['logged_in']['business_outlet_id'],
				'raw_material_is_active' => TRUE
			);
			$data = $this->CashierModel->MultiWhereSelect('mss_raw_material_categories',$where);
			if($data['success'] == 'true'){	
				return $data['res_arr'];
			}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}		
	}

	public function GetRawMaterial(){
		if($this->IsLoggedIn('cashier')){
			if(isset($_GET) && !empty($_GET)){
				$data = $this->CashierModel->DetailsById($_GET['raw_material_category_id'],'mss_raw_material_categories','raw_material_category_id');
				if($data['success'] == 'true'){	
					header("Content-type: application/json");
					print(json_encode($data['res_arr'], JSON_PRETTY_PRINT));
					die;	
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}		
	}

	public function GetCustomerByMobile($mobile){
		if($this->IsLoggedIn('cashier')){
			if(isset($_GET) && !empty($_GET)){
				$data = $this->CashierModel->DetailsById($mobile,'mss_customers','customer_mobile');
				if($data['success'] == 'true'){	
					return $data['res_arr'];
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}		
	}

	public function GetOTCItem(){
		if($this->IsLoggedIn('cashier')){
			if(isset($_GET) && !empty($_GET)){
				$data = $this->CashierModel->DetailsById($_GET['service_id'],'mss_services','service_id');
				if($data['success'] == 'true'){	
					header("Content-type: application/json");
					print(json_encode($data['res_arr'], JSON_PRETTY_PRINT));
					die;	
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}		
	}

	public function AddNewCustomer(){
		if($this->IsLoggedIn('cashier')){
			if(isset($_POST) && !empty($_POST)){
				$this->form_validation->set_rules('customer_title', 'Title', 'trim|required');
				$this->form_validation->set_rules('customer_name', 'Customer Name', 'trim|required|max_length[100]');
				$this->form_validation->set_rules('customer_mobile', 'Customer Mobile', 'trim|required|max_length[10]|min_length[10]');
				$this->form_validation->set_rules('customer_dob', 'Customer date of birth', 'trim');
				$this->form_validation->set_rules('customer_doa', 'Customer date of anniversary', 'trim');
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
					$where = array(
						'customer_business_admin_id' => $this->session->userdata['logged_in']['business_admin_id'],
						'customer_business_outlet_id' => $this->session->userdata['logged_in']['business_outlet_id'],
						'customer_master_admin_id' => $this->session->userdata['logged_in']['master_admin_id'],
						'customer_mobile'  => $this->input->post('customer_mobile')
					);
					
					$customerExists = $this->CashierModel->CheckCustomerExists($where);
					
					if($customerExists['success'] == 'false' && $customerExists['error'] == 'true' ){
						$this->ReturnJsonArray(false,true,$customerExists['message']);
						die;
					}	
					else{
						$data = array(
							'customer_title' 	=> $this->input->post('customer_title'),
							'customer_name' 	=> $this->input->post('customer_name'),
							'customer_dob'    => $this->input->post('customer_dob'),
							'customer_mobile' => $this->input->post('customer_mobile'),
							'customer_business_admin_id' => $this->session->userdata['logged_in']['business_admin_id'],
							'customer_business_outlet_id' => $this->session->userdata['logged_in']['business_outlet_id'],
							'customer_master_admin_id' => $this->session->userdata['logged_in']['master_admin_id'],
							'customer_doa' => $this->input->post('customer_doa')
						);

						$result = $this->CashierModel->Insert($data,'mss_customers');
							
						if($result['success'] == 'true'){
							
							//After Adding customer we have to add it to session varible
							$sess_data = $this->GetCustomerBilling($result['res_arr']['insert_id']);
							$curr_sess_cust_data = array();
							if(!isset($this->session->userdata['POS'])){
								array_push($curr_sess_cust_data, $sess_data);
								$this->session->set_userdata('POS', $curr_sess_cust_data);
							}
							else{
								$curr_sess_cust_data = $this->session->userdata['POS'];
								array_push($curr_sess_cust_data, $sess_data);
								$this->session->set_userdata('POS', $curr_sess_cust_data);
							}
							////////////////////////////////////////////////////////////

							$this->ReturnJsonArray(true,false,"Customer added successfully!");
							die;
	          }
	          elseif($result['error'] == 'true'){
	          	$this->ReturnJsonArray(false,true,$result['message']);
							die;
	          }
	        }
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}


	public function EditCustomerDetails(){
		if($this->IsLoggedIn('cashier')){
			if(isset($_POST) && !empty($_POST)){
				$this->form_validation->set_rules('customer_title', 'Title', 'trim|required');
				$this->form_validation->set_rules('customer_name', 'Customer Name', 'trim|required|max_length[100]');
				$this->form_validation->set_rules('customer_mobile', 'Customer Mobile', 'trim|required|max_length[10]|min_length[10]');
				$this->form_validation->set_rules('customer_dob', 'Customer date of birth', 'trim');
				$this->form_validation->set_rules('customer_doa', 'Customer date of anniversary', 'trim');
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

					//Get the details to Compare
					$details = $this->GetCustomerBilling($this->input->post('customer_id'));

					//If mobile number is changed!
					if($details['customer_mobile'] != $this->input->post('customer_mobile')){
						$where = array(
							'customer_business_admin_id' => $this->session->userdata['logged_in']['business_admin_id'],
							'customer_business_outlet_id' => $this->session->userdata['logged_in']['business_outlet_id'],
							'customer_mobile'  => $this->input->post('customer_mobile')
						);
						
						$customerExists = $this->CashierModel->CheckCustomerExists($where);
						
						if($customerExists['success'] == 'false' && $customerExists['error'] == 'true' ){
							$this->ReturnJsonArray(false,true,$customerExists['message']);
							die;
						}
						else{
							$data = array(
								'customer_id'     => $this->input->post('customer_id'),
								'customer_title' 	=> $this->input->post('customer_title'),
								'customer_name' 	=> $this->input->post('customer_name'),
								'customer_dob'    => $this->input->post('customer_dob'),
								'customer_doa' 		=> $this->input->post('customer_doa'),
								'customer_mobile' => $this->input->post('customer_mobile')
							);

							$result = $this->CashierModel->Update($data,'mss_customers','customer_id');
							if($result['success'] == 'true'){

							/************************************************************************/
								$customer_id = $this->input->post('customer_id');
								
								$curr_sess_cust_data = $this->session->userdata['POS'];
							
								$key = array_search($customer_id, array_column($curr_sess_cust_data,'customer_id'));
								unset($curr_sess_cust_data[$key]);	
								array_splice($curr_sess_cust_data, $key, $key); 
								$sess_data = $this->GetCustomerBilling($customer_id);
								$sess_data['is_package_customer'] = $this->IsPackageCustomer($customer_id);
								array_push($curr_sess_cust_data, $sess_data);
								$this->session->set_userdata('POS', $curr_sess_cust_data);
								
							/***********************************************************************/
								$this->ReturnJsonArray(true,false,"Customer details updated successfully!");
								die;
		          }
		          elseif($result['error'] == 'true'){
		          	$this->ReturnJsonArray(false,true,$result['message']);
								die;
		          }
						}
					}
					else{					
						
						$data = array(
							'customer_id'     => $this->input->post('customer_id'),
							'customer_title' 	=> $this->input->post('customer_title'),
							'customer_name' 	=> $this->input->post('customer_name'),
							'customer_dob'    => $this->input->post('customer_dob'),
							'customer_doa' 		=> $this->input->post('customer_doa')
						);

						$result = $this->CashierModel->Update($data,'mss_customers','customer_id');
						if($result['success'] == 'true'){
							/************************************************************************/
								$customer_id = $this->input->post('customer_id');
								
								$curr_sess_cust_data = $this->session->userdata['POS'];
							
								$key = array_search($customer_id, array_column($curr_sess_cust_data,'customer_id'));
								unset($curr_sess_cust_data[$key]);	
								array_splice($curr_sess_cust_data, $key, $key); 
								$sess_data = $this->GetCustomerBilling($customer_id);
								$sess_data['is_package_customer'] = $this->IsPackageCustomer($customer_id);
								array_push($curr_sess_cust_data, $sess_data);
								$this->session->set_userdata('POS', $curr_sess_cust_data);
							
							/***********************************************************************/
								$this->ReturnJsonArray(true,false,"Customer details updated successfully!");
								die;
	          }
	          elseif($result['error'] == 'true'){
	          	$this->ReturnJsonArray(false,true,$result['message']);
							die;
	          }
	        }
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}

	public function AddCustomerCardToDashboard(){
		if($this->IsLoggedIn('cashier')){
			if(isset($_POST) && !empty($_POST)){
				$sess_data = $this->GetCustomerBilling($this->input->post('customer_id'));
				
				$sess_data['is_package_customer'] = $this->IsPackageCustomer($this->input->post('customer_id'));
				// $this->PrettyPrintArray($sess_data['is_package_customer']);
				$curr_sess_cust_data = array();
				if(!isset($this->session->userdata['POS'])){
					array_push($curr_sess_cust_data, $sess_data);
					$this->session->set_userdata('POS', $curr_sess_cust_data);
					
				}
				else{
					$curr_sess_cust_data = $this->session->userdata['POS'];
					array_push($curr_sess_cust_data, $sess_data);
					$this->session->set_userdata('POS', $curr_sess_cust_data);
				}
				$this->ReturnJsonArray(true,false,"Customer added!");
			}else{
				$this->ReturnJsonArray(false,true,"Wrong Method!");
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}

	//For Packages
	public function AddNewCustomerFromPackage(){
		if($this->IsLoggedIn('cashier')){
			if(isset($_POST) && !empty($_POST)){
				$this->form_validation->set_rules('customer_title', 'Title', 'trim|required');
				$this->form_validation->set_rules('customer_name', 'Customer Name', 'trim|required|max_length[100]');
				$this->form_validation->set_rules('customer_mobile', 'Customer Mobile', 'trim|required|max_length[10]|min_length[10]');
				$this->form_validation->set_rules('customer_dob', 'Customer date of birth', 'trim');
				$this->form_validation->set_rules('customer_doa', 'Customer date of anniversary', 'trim');
				
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
					$where = array(
						'customer_business_admin_id' => $this->session->userdata['logged_in']['business_admin_id'],
						'customer_business_outlet_id' => $this->session->userdata['logged_in']['business_outlet_id'],
						'customer_mobile'  => $this->input->post('customer_mobile')
					);
					
					$customerExists = $this->CashierModel->CheckCustomerExists($where);
					
					if($customerExists['success'] == 'false' && $customerExists['error'] == 'true' ){
						$this->ReturnJsonArray(false,true,$customerExists['message']);
						die;
					}	
					else{
						$data = array(
							'customer_title' 	=> $this->input->post('customer_title'),
							'customer_name' 	=> $this->input->post('customer_name'),
							'customer_dob'    => $this->input->post('customer_dob'),
							'customer_mobile' => $this->input->post('customer_mobile'),
							'customer_business_admin_id' => $this->session->userdata['logged_in']['business_admin_id'],
							'customer_business_outlet_id' => $this->session->userdata['logged_in']['business_outlet_id'],
							'customer_doa' => $this->input->post('customer_doa')
						);

						$result = $this->CashierModel->Insert($data,'mss_customers');
							
						if($result['success'] == 'true'){
							
							if(isset($this->session->userdata['package_cart'])){
								$this->session->unset_userdata('package_cart');
							}

							if(isset($this->session->userdata['package_payment'])){
								$this->session->unset_userdata('package_payment');
							}
							//After Adding customer we have to add it to session varible
							$sess_data = $this->GetCustomerBilling($result['res_arr']['insert_id']);
							
							if(!isset($this->session->userdata['Package_Customer'])){
								$this->session->set_userdata('Package_Customer', $sess_data);
							}
							else{
								$this->session->unset_userdata('Package_Customer');
								$this->session->set_userdata('Package_Customer', $sess_data);
							}
				
							$this->ReturnJsonArray(true,false,"Customer added successfully!");
							die;
	          }
	          elseif($result['error'] == 'true'){
	          	$this->ReturnJsonArray(false,true,$result['message']);
							die;
	          }
	        }
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}


	public function AddCustomerCartToPackage(){
		if($this->IsLoggedIn('cashier')){
			if(isset($_POST) && !empty($_POST)){
				$sess_data = $this->GetCustomerBilling($this->input->post('customer_id'));
				
				if(isset($this->session->userdata['package_cart'])){
					$this->session->unset_userdata('package_cart');
				}

				if(isset($this->session->userdata['package_payment'])){
					$this->session->unset_userdata('package_payment');
				}

				if(!isset($this->session->userdata['Package_Customer'])){
					$this->session->set_userdata('Package_Customer', $sess_data);
				}
				else{
					$this->session->unset_userdata('Package_Customer');
					$this->session->set_userdata('Package_Customer', $sess_data);
				}
				$this->ReturnJsonArray(true,false,"Customer added!");
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}

	//Very Important function
	public function PerformBilling(){
		if($this->IsLoggedIn('cashier')){	
			$customer_id = $this->uri->segment(3);
			
			if(isset($customer_id)){
				//Check whether customer belongs to the logged cashier's shop and business admin
				$where = array(
					'business_admin_id' => $this->session->userdata['logged_in']['business_admin_id'],
					'business_outlet_id'=> $this->session->userdata['logged_in']['business_outlet_id'],
					'master_admin_id'=> $this->session->userdata['logged_in']['master_admin_id'],
					'customer_id'       => $customer_id
				);

				$check = $this->CashierModel->VerifyCustomer($where);
				if($check['success'] == 'true'){	

					//Unset any session so that no one interfere in billing logic
					if(isset($this->session->userdata['Package_Customer'])){
		 				$this->session->unset_userdata('Package_Customer');
		 			}

		 			if(isset($this->session->userdata['package_cart'])){
						$this->session->unset_userdata('package_cart');
					}

					if(isset($this->session->userdata['package_payment'])){
		 				$this->session->unset_userdata('package_payment');
		 			}
					//

					$data = $this->GetDataForCashier("Final Billing");
					
					$data['individual_customer'] = $this->GetCustomerBilling($customer_id);
					
					$data['categories']  = $this->GetCategoriesByType($this->session->userdata['logged_in']['business_outlet_id'],'Service');
					// $data['categories_products'] = $this->GetCategoriesByType($this->session->userdata['logged_in']['business_outlet_id'],'Products');

					
					$data['experts'] = $this->GetExperts();

					$data['active_packages_categories'] = $this->PurchasedPackages($customer_id);
					$data['categories_products']=$this->CashierModel->OtcCategory($this->session->userdata['logged_in']['business_outlet_id']);
					$data['categories_products']=$data['categories_products']['res_arr'];

					// $this->PrettyPrintArray($data['categories_products']);

					//Coupon
					$data['coupon'] = $this->CashierModel->GetCustomerCoupon($customer_id);
				
					if($data['coupon']['success'] == 'false')
					{
						$data['coupon']=['res_arr'=>''];
						$data['coupon']=$data['coupon']['res_arr'];
					}
					else
					{
						$data['coupon']=$data['coupon']['res_arr'];
					}
					
					if(isset($this->session->userdata['recommended_ser'][$customer_id])){
						// $data['recommended_ser'] = $this->session->userdata['recommended_ser'][$customer_id];
					}else{
						$recommended=$this->CashierModel->GetTopServicesCustomer($customer_id);
						// $this->PrettyPrintArray(count($recommended['res_arr']));
						if(isset($recommended['res_arr'])){
						    if(count($recommended['res_arr']) == 5){
							    $recommended=$recommended['res_arr'];
						    }else{
						       	$data['recommended']=$this->CashierModel->GetTopServices();
                                if(isset($data['recommended']['res_arr'])){
                                    $recommended=$data['recommended']['res_arr'];
                                }else{
                                    $recommended=0; 
                                }
						    }
						    
						}else{
							$recommended=0;
						}
						$_SESSION['recommended_ser'][$customer_id]=$recommended;
					}
						$data['recommended']=$this->CashierModel->GetTopServices();
                                if(isset($data['recommended']['res_arr'])){
                                    $data['recommended']=$data['recommended']['res_arr'];
                                }else{
                                    $data['recommended']=0; 
                                }
					if(isset($this->session->userdata['cart'][$customer_id])){
						$data['cart'] = $this->session->userdata['cart'][$customer_id];
					}
					
					if(isset($this->session->userdata['payment'])){
						$data['payment'] = $this->session->userdata['payment'][$customer_id];
					}
					$data['txn_remarks']= $this->session->userdata['txn_remarks'];
					//cashback conversion ratio
					$data['loyalty']=$this->CashierModel->GetConversionRatio($this->session->userdata['logged_in']['business_admin_id']);
					$data['loyalty']=$data['loyalty']['res_arr'];
					$data['sidebar_collapsed'] = "true";
					// $this->PrettyPrintArray($_SESSION);
					// $this->PrettyPrintArray($data);
					
	                foreach($data['business_admin_packages'] as $key =>$value)
					{
						if(!($value == 'Marks360'))
						{
							$cashback = 0;
						}
						else
						{
							$rules = $this->BusinessAdminModel->RuleDetailsById($data['cashier_details']['business_outlet_id'],'mss_loyalty_rules','business_outlet_id');
							if($rules['success'] == 'true')
							{
							    $data['rules'] = $rules['res_arr'];
							    if($data['rules']['rule_type'] == 'Cashback Single Rule' || $data['rules']['rule_type'] == 'Cashback Multiple Rule' || $data['rules']['rule_type'] == 'Cashback LTV Rule')
                                {
                                    $data['loyalty_offer'] = ['res_arr'=>'']; 
                                }
                                else
                                {
                                    $data['loyalty_offer'] = $this->CashierModel->GetOffers($this->session->userdata['logged_in']['business_outlet_id'],'mss_loyalty_offer_integrated');
                                    if($data['loyalty_offer']['success'] == 'true')
                                    {
                                        $data['loyalty_offer'] = $data['loyalty_offer']['res_arr'];
                                    }
                                    else
                                    {
                                        $data['loyalty_offer'] += ['res_arr'=>''];
                                        $data['loyalty_offer'] = $data['loyalty_offer']['res_arr'];
                                    }
                                }
							}
						}
					}
				
          // $this->PrettyPrintArray($data);
					$this->load->view('cashier/cashier_do_billing_view',$data);
				}
				elseif ($check['error'] == 'true'){
					$data = array(
						'heading' => "Illegal Access!",
						'message' => $check['message']
					);
					$this->load->view('errors/html/error_general',$data);	
				}
			}
			else{
				$data = array(
					'heading' => "Illegal Access!",
					'message' => "Customer details/ID missing. Please do not change url!"
				);
				$this->load->view('errors/html/error_general',$data);
			}	
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}	
	}
	
	
	// Update Cart Data
	public function UpdateCartData(){
		if($this->IsLoggedIn('cashier')){
			// $this->PrettyPrintArray($_POST);
			if(isset($_POST) && !empty($_POST)){
				$this->form_validation->set_rules('coupon_code', 'Coupon Code', 'trim|required|max_length[8]');
				$this->form_validation->set_rules('customer_id', 'Customer Id', 'trim|required');
				$this->form_validation->set_rules('total_bill', 'Total Bill', 'trim|required');
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
					$customer_id = $this->input->post('customer_id');
					$total_bill= $this->input->post('total_bill'); 
					$coupon_code= $this->input->post('coupon_code');
					$curr_sess_cart_data = $this->session->userdata['cart'][''.$customer_id.''];
					$where=array(
						'customer_id'=>$this->input->post('customer_id'),
						'coupon_code'=>$this->input->post('coupon_code'),
						'business_outlet_id'=>$this->session->userdata['logged_in']['business_outlet_id'],
						'business_admin_id'	=>$this->session->userdata['logged_in']['business_admin_id']
					);
					$code_info=$this->CashierModel->GetDealInfo($where);
				// 	$this->PrettyPrintArray($code_info);
					if($code_info['success'] == 'false')
					{
						$this->ReturnJsonArray(false,true,"No coupon found!");
						die;
						// $code_info=['res_arr'=>''];
						// $code_info=$code_info['res_arr'];
					}else{
						$code_info=$code_info['res_arr'];
						$service_arr=array();
						foreach($code_info as $info){
							array_push($service_arr,$info['service_id']);
						}

						// $this->PrettyPrintArray($service_arr);
						if($code_info[0]['deal_code']==$coupon_code && $total_bill > $code_info[0]['minimum_amt'] && $total_bill < $code_info[0]['maximum_amt'] && substr($code_info[0]['start_time'],0,5) < substr(date("H:i:s"),0,5) && substr($code_info[0]['end_time'],0,5) > substr(date("H:i:s"),0,5)){
							$session_data=array();
							if(isset($curr_sess_cart_data) || empty($curr_sess_cart_data)){
								foreach($curr_sess_cart_data as $data){
									if(in_array($data['service_id'],$service_arr)){
										$data['service_discount_percentage']=$code_info[0]['discount'];
										array_push($session_data,$data);
									}else{
										// $data['service_discount_percentage']=$code_info[0]['discount'];
									  array_push($session_data,$data);
									}
								}
							}
							$curr_sess_cart_data  = ["".$this->input->post('customer_id')."" => $session_data];
							$this->session->set_userdata('cart', $curr_sess_cart_data);
							// $this->session->userdata['cart'][''.$customer_id.'']=$session_data;
							// $this->session->set_userdata('cart', $session_data);
							// $this->PrettyPrintArray($this->session->userdata['cart'][''.$customer_id.'']);
							$this->ReturnJsonArray(true,false,"Coupon applied successfully!");
							die;
						}else{
							$this->ReturnJsonArray(false,true,"Invalid Coupon!");
							die;
						}
					}					
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/Login");
		}	
	}

	private function PurchasedPackages($customer_id){
		if($this->IsLoggedIn('cashier')){
			$where = array(
				'customer_id' => $customer_id
			);

			$data = $this->CashierModel->GetPurchasedPackagesCategories($where);
			
			if($data['success'] == 'true'){	
				return $data['res_arr'];
			}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}		
	}

	private function GetCategories($outlet_id){
		if($this->IsLoggedIn('cashier')){
			$where = array(
				'category_business_admin_id' => $this->session->userdata['logged_in']['business_admin_id'],
				'category_is_active'         => TRUE,
				'category_business_outlet_id'=> $outlet_id
			);

			$data = $this->BusinessAdminModel->MultiWhereSelect('mss_categories',$where);
			if($data['success'] == 'true'){	
				return $data['res_arr'];
			}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}		
	}

	private function GetSubCategories($outlet_id){
		if($this->IsLoggedIn('cashier')){
			$where = array(
				'category_business_admin_id' => $this->session->userdata['logged_in']['business_admin_id'],
				'sub_category_is_active'     => TRUE,
				'category_business_outlet_id'=>$outlet_id
			);

			$data = $this->BusinessAdminModel->SubCategories($where);
			if($data['success'] == 'true'){	
				return $data['res_arr'];
			}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}		
	}


	private function GetOTCItems(){
		if($this->IsLoggedIn('cashier')){
			$where = array(
				'category_business_admin_id' => $this->session->userdata['logged_in']['business_admin_id'],
				'service_is_active'     => TRUE,
				'category_business_outlet_id'=> $this->session->userdata['logged_in']['business_outlet_id'],
				'service_type' => 'otc'
			);

			$data = $this->BusinessAdminModel->Services($where);
			if($data['success'] == 'true'){	
				return $data['res_arr'];
			}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}		
	}
    
    //get category by  category type
	private function GetCategoriesByType($outlet_id,$type){
		if($this->IsLoggedIn('cashier')){
			$where = array(
				'category_business_admin_id' => $this->session->userdata['logged_in']['business_admin_id'],
				'category_type' => $type,
				'category_is_active'         => TRUE,
				'category_business_outlet_id'=> $outlet_id
			);
	
			$data = $this->BusinessAdminModel->MultiWhereSelect('mss_categories',$where);
			if($data['success'] == 'true'){	
				return $data['res_arr'];
			}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}		
	}
	
    
	public function GetSubCategoriesByCatId(){
		if($this->IsLoggedIn('cashier')){
			if(isset($_GET) && !empty($_GET)){
				$where = array(
					'sub_category_category_id' => $_GET['category_id'],
					'sub_category_is_active'   => TRUE
				);
				
				$data = $this->BusinessAdminModel->MultiWhereSelect('mss_sub_categories',$where);
				header("Content-type: application/json");
				print(json_encode($data['res_arr'], JSON_PRETTY_PRINT));
				die;
			}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}
	}

	public function GetServicesBySubCatId(){
		if($this->IsLoggedIn('cashier')){
			if(isset($_GET) && !empty($_GET)){
				$where = array(
					'service_sub_category_id'  => $_GET['sub_category_id'],
					'service_is_active'   => TRUE
				);
				
				$data = $this->BusinessAdminModel->MultiWhereSelect('mss_services',$where);
		
				header("Content-type: application/json");
				print(json_encode($data['res_arr'], JSON_PRETTY_PRINT));
				die;
			}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}
	}
	
	public function GetServicesBySubCatIdOtc(){
		if($this->IsLoggedIn('cashier')){
			if(isset($_GET) && !empty($_GET)){
				$where = array(
					'service_sub_category_id'  => $_GET['sub_category_id'],
					'service_is_active'   => TRUE,
					'inventory_type'=>'Retail Product',
				);
				
				$data = $this->BusinessAdminModel->MultiWhereSelect('mss_services',$where);
		
				header("Content-type: application/json");
				print(json_encode($data['res_arr'], JSON_PRETTY_PRINT));
				die;
			}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}
	}
	
		public function GetServiceByServiceId(){
		if($this->IsLoggedIn('cashier')){
			if(isset($_GET) && !empty($_GET)){
				$where = array(
					'service_id'  => $_GET['service_id'],
					'service_is_active'   => TRUE
				);
				
				$data = $this->CashierModel->MultiWhereSelect('mss_services',$where);
		
				header("Content-type: application/json");
				print(json_encode($data['res_arr'], JSON_PRETTY_PRINT));
				die;
			}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}
	}
	

	public function GetPackageSubCategories(){
		if($this->IsLoggedIn('cashier')){
			if(isset($_GET) && !empty($_GET)){
				$where = array(
					'customer_id' => $_GET['customer_id'],
					'category_id' => $_GET['category_id']
				);
				
				$data = $this->CashierModel->GetPurchasedPackagesSubCategories($where);
				header("Content-type: application/json");
				print(json_encode($data['res_arr'], JSON_PRETTY_PRINT));
				die;
			}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}
	}

	public function GetPackageServices(){
		if($this->IsLoggedIn('cashier')){
			if(isset($_GET) && !empty($_GET)){
				
				$where = array(
					'customer_id' => $_GET['customer_id'],
					'sub_category_id' => $_GET['sub_category_id']
				);
				
				$data = $this->CashierModel->GetPurchasedPackagesServices($where);
				// print_r($data);
				// exit;
				header("Content-type: application/json");
				print(json_encode($data['res_arr'], JSON_PRETTY_PRINT));
				die;
			}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}
	}

	private function GetExperts(){
		if($this->IsLoggedIn('cashier')){
			$where = array(
				'employee_business_admin' => $this->session->userdata['logged_in']['business_admin_id'],
				'employee_business_outlet'=> $this->session->userdata['logged_in']['business_outlet_id'],
				'employee_is_active' => TRUE
			);

			$data = $this->CashierModel->MultiWhereSelect('mss_employees',$where);
			if($data['success'] == 'true'){	
				return $data['res_arr'];
			}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}		
	}

	private function ShopDetails(){
		if($this->IsLoggedIn('cashier')){
			$where = array(
				'business_outlet_business_admin' => $this->session->userdata['logged_in']['business_admin_id'],
				'business_outlet_id'=> $this->session->userdata['logged_in']['business_outlet_id'],
			);

			$data = $this->CashierModel->DetailsById($where['business_outlet_id'],'mss_business_outlets','business_outlet_id');
			if($data['success'] == 'true'){	
				return $data['res_arr'];
			}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}		
	}

	public function AddToCart(){
		// $this->PrettyPrintArray($_POST);
		// exit;
		if($this->IsLoggedIn('cashier')){
			if(isset($_POST) && !empty($_POST)){
				$this->form_validation->set_rules('service_name', 'Service Name', 'trim|required|max_length[100]');
				$this->form_validation->set_rules('service_total_value', 'Service Total Value', 'trim|required');
				$this->form_validation->set_rules('service_quantity', 'Service Quantity', 'trim|required');
				$this->form_validation->set_rules('service_discount_percentage', 'Service Discount Percentage', 'trim|required');
				$this->form_validation->set_rules('service_discount_absolute', 'Service Discount Absolute', 'trim|required');
				$this->form_validation->set_rules('service_expert_id', 'Service Expert Name', 'trim|required');
				$this->form_validation->set_rules('service_price_inr', 'Service Price Inr', 'trim|required');
				$this->form_validation->set_rules('customer_id', 'Customer Name', 'trim|required');
				$this->form_validation->set_rules('service_id', 'Service Name', 'trim|required');

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

					if(isset($this->session->userdata['payment'])){
		 				$this->session->unset_userdata('payment');
		 			}
					
					$data = array(
										'customer_id'								 => $this->input->post('customer_id'),
										'service_id'  							 => $this->input->post('service_id'),
										'service_name'							 => $this->input->post('service_name'),
										'service_total_value'				 => $this->input->post('service_total_value'),
										'service_quantity'					 => $this->input->post('service_quantity'),
										'service_discount_percentage'=> $this->input->post('service_discount_percentage'),
										'service_discount_absolute'  => $this->input->post('service_discount_absolute'),
										'service_expert_id'          => $this->input->post('service_expert_id'),
										'service_price_inr'          => $this->input->post('service_price_inr'),
										'service_add_on_price'			=> 0,
										'service_gst_percentage'     => $this->input->post('service_gst_percentage'),
										'service_est_time'  => $this->input->post('service_est_time'),
										'customer_package_profile_id' => -999, //Short code for no package redemption
										'coupon_id'									=>$this->input->post('coupon_id')
									);

								
					//Adding the cart product to particular customer's cart
					$curr_sess_cart_data = array();
					$sess_data = array($data);
					if(!isset($this->session->userdata['cart'])){
						$curr_sess_cart_data  += ["".$this->input->post('customer_id')."" => $sess_data];
						$this->session->set_userdata('cart', $curr_sess_cart_data);
						
					}
					else{
						$curr_sess_cart_data = $this->session->userdata['cart'];
					
						if(isset($curr_sess_cart_data["".$this->input->post('customer_id').""]) || !empty($curr_sess_cart_data["".$this->input->post('customer_id').""])){
							
							array_push($curr_sess_cart_data["".$this->input->post('customer_id').""], $data);
							$this->session->set_userdata('cart', $curr_sess_cart_data);
							// print_r($curr_sess_cart_data);
							// exit;
						}
						else{
							$curr_sess_cart_data  += ["".$this->input->post('customer_id')."" => $sess_data];
							$this->session->set_userdata('cart', $curr_sess_cart_data);
						}
					}
					/********************************************************************/
					$this->ReturnJsonArray(true,false,"Your Cart updated successfully!");
					die;
				}
			}
			else{
				$this->ReturnJsonArray(false,true,"Not a valid request!");
				die;
			}		
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}	
	}
	
	public function EditCart(){
		if($this->IsLoggedIn('cashier')){
			if(isset($_POST) && !empty($_POST)){
				$this->form_validation->set_rules('service_name', 'Service Name', 'trim|required|max_length[100]');
				$this->form_validation->set_rules('service_total_value', 'Service Total Value', 'trim|required');
				$this->form_validation->set_rules('service_quantity', 'Service Quantity', 'trim|required');
				$this->form_validation->set_rules('service_discount_percentage', 'Service Discount Percentage', 'trim|required');
				$this->form_validation->set_rules('service_discount_absolute', 'Service Discount Absolute', 'trim|required');
				$this->form_validation->set_rules('service_expert_id', 'Service Expert Name', 'trim|required');
				$this->form_validation->set_rules('service_price_inr', 'Service Price Inr', 'trim|required');
				$this->form_validation->set_rules('service_add_on_price', 'Service Add On Price', 'trim|required');
				$this->form_validation->set_rules('customer_id', 'Customer Name', 'trim|required');
				$this->form_validation->set_rules('service_id', 'Service Name', 'trim|required');
				$this->form_validation->set_rules('customer_package_profile_id', 'Package ID', 'trim|required');
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

					if(isset($this->session->userdata['payment'])){
		 				$this->session->unset_userdata('payment');
		 			}

		 			//Delete the cart item then add the similar upadated one!
		 			$res = $this->DeleteCartItemUtility($this->input->post('service_id'),$this->input->post('customer_id'));
		 			if($res){
						$service_total_value=($_POST['service_add_on_price'])-($_POST['service_add_on_price']*$_POST['service_gst_percentage']/100);
			 			$data = array(
							'customer_id'								 => $this->input->post('customer_id'),
							'service_id'  							 => $this->input->post('service_id'),
							'service_name'							 => $this->input->post('service_name'),
							'service_total_value'				 => ($_POST['service_total_value']+$_POST['service_add_on_price']),
							'service_quantity'					 => $this->input->post('service_quantity'),
							'service_discount_percentage'=> $this->input->post('service_discount_percentage'),
							'service_discount_absolute'  => $this->input->post('service_discount_absolute'),
							'service_expert_id'          => $this->input->post('service_expert_id'),
							'service_price_inr'          => $this->input->post('service_price_inr'),
							'service_add_on_price'       => $this->input->post('service_add_on_price'),
							'service_gst_percentage'     => $this->input->post('service_gst_percentage'),
							'service_est_time'  => $this->input->post('service_est_time'),
							'customer_package_profile_id' => $this->input->post('customer_package_profile_id'),
							'service_remarks'							=> $this->input->post('service_remarks')
						);

				
						//Adding the cart product to particular customer's cart
						$curr_sess_cart_data = array();
						$sess_data = array($data);
						if(!isset($this->session->userdata['cart'])){
							$curr_sess_cart_data  += ["".$this->input->post('customer_id')."" => $sess_data];
							$this->session->set_userdata('cart', $curr_sess_cart_data);
						}
						else{
							$curr_sess_cart_data = $this->session->userdata['cart'];

							if(isset($curr_sess_cart_data["".$this->input->post('customer_id').""]) || !empty($curr_sess_cart_data["".$this->input->post('customer_id').""])){
								
								array_push($curr_sess_cart_data["".$this->input->post('customer_id').""], $data);
								$this->session->set_userdata('cart', $curr_sess_cart_data);
							}
							else{
								$curr_sess_cart_data  += ["".$this->input->post('customer_id')."" => $sess_data];
								$this->session->set_userdata('cart', $curr_sess_cart_data);
							}
						}
						/********************************************************************/
						$this->ReturnJsonArray(true,false,"Your Cart updated successfully!");
						die;
		 			}
		 			else{
		 				$this->ReturnJsonArray(false,true,"Cart is already empty!");
		 			}
				}
			}
			else{
				$this->ReturnJsonArray(false,true,"Not a valid request!");
				die;
			}		
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}		
	}

	public function AddToCartPackageService(){
		if($this->IsLoggedIn('cashier')){
			if(isset($_POST) && !empty($_POST)){
				$this->form_validation->set_rules('service_name', 'Service Name', 'trim|required|max_length[100]');
				$this->form_validation->set_rules('salon_package_name', 'Package Name', 'trim|required|max_length[100]');
				$this->form_validation->set_rules('service_quantity', 'Total Service Quantity', 'trim|required');
				$this->form_validation->set_rules('service_total_value', 'Service Total Value', 'trim|required');
				$this->form_validation->set_rules('service_gst_percentage', 'Service GST Percentage', 'trim|required');
				$this->form_validation->set_rules('service_quantity_redeemable', 'Service Quantity', 'trim|required|is_natural_no_zero');
				$this->form_validation->set_rules('service_discount_percentage', 'Service Discount Percentage', 'trim|required');
				$this->form_validation->set_rules('service_expert_id', 'Service Expert Name', 'trim|required');
				$this->form_validation->set_rules('service_price_inr', 'Service Price Inr', 'trim|required');
				$this->form_validation->set_rules('customer_id', 'Customer Name', 'trim|required');
				$this->form_validation->set_rules('service_id', 'Service Name', 'trim|required');

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

					if(isset($this->session->userdata['payment'])){
		 				$this->session->unset_userdata('payment');
		 			}

					$data = array(
										'customer_id'								 => $this->input->post('customer_id'),
										'service_id'  							 => $this->input->post('service_id'),
										'service_name'							 => $this->input->post('service_name'),
										'salon_package_name'				 => $this->input->post('salon_package_name'),
										'service_count'							 => $this->input->post('service_quantity'),
										'service_total_value'				 => $this->input->post('service_total_value'),
										'service_quantity'					 => $this->input->post('service_quantity_redeemable'),
										'service_discount_percentage'=> $this->input->post('service_discount_percentage'),
										'service_discount_absolute'  => 0,
										'service_add_on_price'  => 0,
										'service_expert_id'          => $this->input->post('service_expert_id'),
										'service_price_inr'          => $this->input->post('service_price_inr'),
										'service_gst_percentage'     => $this->input->post('service_gst_percentage'),
										'service_est_time'  => $this->input->post('service_est_time'),
										'customer_package_profile_id' => $this->input->post('customer_package_profile_id')
									);

					//Adding the cart product to particular customer's cart
					$curr_sess_cart_data = array();
					$sess_data = array($data);
					if(!isset($this->session->userdata['cart'])){
						$curr_sess_cart_data  += ["".$this->input->post('customer_id')."" => $sess_data];
						$this->session->set_userdata('cart', $curr_sess_cart_data);
					}
					else{
						$curr_sess_cart_data = $this->session->userdata['cart'];

						if(isset($curr_sess_cart_data["".$this->input->post('customer_id').""]) || !empty($curr_sess_cart_data["".$this->input->post('customer_id').""])){
							
							array_push($curr_sess_cart_data["".$this->input->post('customer_id').""], $data);
							$this->session->set_userdata('cart', $curr_sess_cart_data);
						}
						else{
							$curr_sess_cart_data  += ["".$this->input->post('customer_id')."" => $sess_data];
							$this->session->set_userdata('cart', $curr_sess_cart_data);
						}
					}
					/********************************************************************/
					$this->ReturnJsonArray(true,false,"Your Cart updated successfully!");
					die;
				}
			}
			else{
				$this->ReturnJsonArray(false,true,"Not a valid request!");
				die;
			}		
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}	
	}

		public function AddPackageToCart(){
		if($this->IsLoggedIn('cashier')){           
				if(isset($_POST) && !empty($_POST)){                
						$this->form_validation->set_rules('package_name', 'Package Name', 'trim|required');
						$this->form_validation->set_rules('customer_name', 'Customer Name', 'trim|required');
						$this->form_validation->set_rules('package_discount_absolute', 'Discount Absolute', 'trim|required');
						$this->form_validation->set_rules('package_price_inr', 'Package Price', 'trim|required');
						$this->form_validation->set_rules('package_gst', 'Package GST', 'trim|required');
						$this->form_validation->set_rules('package_validity', 'Package validity', 'trim|required');
						$this->form_validation->set_rules('package_final_value', 'Price After Discount', 'trim|required');
						$this->form_validation->set_rules('package_type', 'Package Type', 'trim|required');
						
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
								$empid=explode(',',$_POST['emp_id']);
								// $this->PrettyPrintArray($empid);
								// exit;
								$emp_name=$empid[1];
								$emp_id=$empid[0];
								//Unset the payment info when cart package is added to the cart
								if(isset($this->session->userdata['package_payment'])){
										$this->session->unset_userdata('package_payment');
								}
								
								if(isset($this->session->userdata['Package_Customer']) && !empty($this->session->userdata['Package_Customer'])){
												$discount=$_POST['package_discount_absolute'];
												$base_price=$_POST['package_price_inr'];
												$gst_percentage=$_POST['package_gst'];
												$gst=(($_POST['package_price_inr']*$gst_percentage)/100);
												$x=$discount/(1+($_POST['package_gst']/100));
												$y=$discount-$x;
												$new_package_price=$base_price-$x;
												$new_gst=$gst-$y;
										if(isset($_POST['package_discount_absolute']) && $_POST['package_discount_absolute'] > 0 ){
												
												$data = array(
														'package_name'                               => $this->input->post('package_name'),
														'customer_name'                              => $this->input->post('customer_name'),
														'package_discount_absolute'      => $this->input->post('package_discount_absolute'),
														'package_price_inr'                  => $new_package_price,
														'package_old_price'                         => $this->input->post('package_price_inr'),
														'package_validity'             => $this->input->post('package_validity'),
														'package_final_value'                    => round($new_package_price+$new_gst),
														'package_gst'                                       => round($new_gst),
														'package_old_gst'                               => round($gst_percentage),
														'package_type'                 => $this->input->post('package_type'),
														'employee_id'                  =>$emp_id,
														'employee_name'                =>$emp_name,
														'customer_id'                  => $this->session->userdata['Package_Customer']['customer_id'],
														'salon_package_id'             => $this->input->post('salon_package_id')
												);
										}else{
												$data = array(
														'package_name'                               => $this->input->post('package_name'),
														'customer_name'                              => $this->input->post('customer_name'),
														'package_discount_absolute'      => $this->input->post('package_discount_absolute'),
														'package_price_inr'                  => $this->input->post('package_price_inr'),
														'package_old_price'                      => $this->input->post('package_price_inr'),
														'package_validity'             => $this->input->post('package_validity'),
														'package_final_value'                    => $this->input->post('package_final_value'),
														'package_gst'                                       => round($new_gst),
														'package_old_gst'                               => round($gst_percentage),
														'package_type'                 => $this->input->post('package_type'),
														'employee_id'                  =>$emp_id,
														'employee_name'                =>$emp_name,
														'customer_id'                  => $this->session->userdata['Package_Customer']['customer_id'],
														'salon_package_id'             => $this->input->post('salon_package_id')
												);
										}
										
												
										$sess_data = $data;
										
										if(!isset($this->session->userdata['package_cart'])){
												$this->session->set_userdata('package_cart', $sess_data);
										}
										else{
												$this->session->unset_userdata('package_cart');
												$this->session->set_userdata('package_cart', $sess_data);
										}
										
										$this->ReturnJsonArray(true,false,"Your Cart updated successfully!");
										die;
								}
								else{
										$this->ReturnJsonArray(false,true,"Please select to sell package!");
								}
						}
				}
				else{
						$this->ReturnJsonArray(false,true,"Not a valid request!");
						die;
				}       
		}
		else{
				$this->LogoutUrl(base_url()."Cashier/");
		}   
	}

	//Donar Details for Gift
	public function GiftDonarDetails(){
			if($this->IsLoggedIn('cashier')){
					if(isset($_POST) && !empty($_POST)){
							$_SESSION['package_cart']+=['donar_name'=>$_POST['donar_name']];
							$_SESSION['package_cart']+=['donar_mob'=>$_POST['donar_mob']];
							// $this->PrettyPrintArray($_SESSION['package_cart']);
							// exit;
							return $this->ReturnJsonArray(true,false,'Donar Added Successfully');
							die;    
							
					}
			}
			else{
					$this->LogoutUrl(base_url()."Cashier/");
			}       
	}


	public function DeleteCartItem(){
		if($this->IsLoggedIn('cashier')){
			if(isset($_GET) && !empty($_GET)){
				$service_id = $_GET['service_id'];
				$customer_id = $_GET['customer_id'];
				
				if(isset($this->session->userdata['payment'])){
	 				$this->session->unset_userdata('payment');
	 			}

				//Delete the cart product to particular customer's cart
				if(!isset($this->session->userdata['cart']) || empty($this->session->userdata['cart'])){
					$this->ReturnJsonArray(false,true,"Cart is empty!");
					die;
				}
				else{
					$overall_cart_data = $this->session->userdata['cart'];
					$key = array_search($service_id, array_column($overall_cart_data[strval($customer_id)], 'service_id'));
					unset($overall_cart_data[strval($customer_id)][$key]);	
					$overall_cart_data[strval($customer_id)] = array_values($overall_cart_data[strval($customer_id)]);
					$this->session->set_userdata('cart', $overall_cart_data);

					$this->ReturnJsonArray(true,false,"Item deleted successfully!");
					die;
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}
	}
	
	//Both are same
	public function DeleteCartItemUtility($service_id,$customer_id){
		if($this->IsLoggedIn('cashier')){
				
			if(isset($this->session->userdata['payment'])){
 				$this->session->unset_userdata('payment');
 			}

			//Delete the cart product to particular customer's cart
			if(!isset($this->session->userdata['cart']) || empty($this->session->userdata['cart'])){
				return false;
			}
			else{
				$overall_cart_data = $this->session->userdata['cart'];
				$key = array_search($service_id, array_column($overall_cart_data[strval($customer_id)], 'service_id'));
				unset($overall_cart_data[strval($customer_id)][$key]);	
				$overall_cart_data[strval($customer_id)] = array_values($overall_cart_data[strval($customer_id)]);
				$this->session->set_userdata('cart', $overall_cart_data);

				return true;
			}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}
	}		

	public function DeleteCartPackageItem(){
		if($this->IsLoggedIn('cashier')){
			if(isset($_GET) && !empty($_GET)){
				$customer_id = $_GET['customer_id'];
				
				if(isset($this->session->userdata['package_payment'])){
	 				$this->session->unset_userdata('package_payment');
	 			}

				//Delete the cart product to particular customer's cart
				if(!isset($this->session->userdata['package_cart']) || empty($this->session->userdata['package_cart'])){
					$this->ReturnJsonArray(false,true,"Cart is empty!");
					die;
				}
				else{
					$overall_cart_data = $this->session->userdata['package_cart'];
					
					$this->session->unset_userdata('package_cart');

					$this->ReturnJsonArray(true,false,"Item deleted successfully!");
					die;
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}
	}	

	public function ApplyExtraDiscount(){
		if($this->IsLoggedIn('cashier')){
			if(isset($_POST) && !empty($_POST)){
				$this->form_validation->set_rules('cart_discount_absolute', 'Discount Absolute', 'trim|required');
				$this->form_validation->set_rules('cart_discount_percentage', 'Discount Percentage', 'trim|required');
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
					$customer_id = $this->input->post('customer_id');
					$curr_sess_payment_data = array();
					if(!isset($this->session->userdata['payment'][''.$customer_id.''])){
						$payment_data = array(
														'full_payment_info' => array(),
														'split_payment_info' => array(),
														'discount_info' => array(
															'cart_discount_absolute' => $this->input->post('cart_discount_absolute'),
															'cart_discount_percentage' => $this->input->post('cart_discount_percentage'),
															'customer_id' => $customer_id
														)
												 	);

						$curr_sess_payment_data  = array(
							"".$this->input->post('customer_id')."" => $payment_data
						);
						$this->session->set_userdata('payment', $curr_sess_payment_data);
						$this->ReturnJsonArray(true,false,"Discount applied successfully!");
						die;
					}
					else{
						
						$payment_data = $this->session->userdata['payment'];
						$payment_data[''.$customer_id.'']['discount_info']['cart_discount_absolute'] = $this->input->post('cart_discount_absolute');
						$payment_data[''.$customer_id.'']['discount_info']['cart_discount_percentage'] = $this->input->post('cart_discount_percentage');
						$payment_data[''.$customer_id.'']['full_payment_info'] = array();
						$payment_data[''.$customer_id.'']['split_payment_info'] = array();
						
						$this->session->set_userdata('payment', $payment_data);
						$this->ReturnJsonArray(true,false,"Discount applied successfully!");
						die;
					}
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}	
	}

	public function FullPaymentInfo(){
		if($this->IsLoggedIn('cashier')){
			if(isset($_POST) && !empty($_POST)){
				$this->form_validation->set_rules('payment_type', 'Payment Type', 'trim|required');
				$this->form_validation->set_rules('total_final_bill', 'Final Bill', 'trim|required');
				$this->form_validation->set_rules('total_amount_received', 'Amount Received', 'trim|required');
				$this->form_validation->set_rules('balance_to_be_paid_back', 'Balance', 'trim|required');
				$this->form_validation->set_rules('pending_amount', 'Pending Amount', 'trim|required');

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
					$customer_id = $this->input->post('customer_id');
					$wallet_info = $this->CashierModel->GetWalletInfo($customer_id);
					
					if($this->input->post('payment_type') == 'Virtual_Wallet'){
						if($wallet_info['res_arr']['customer_virtual_wallet'] < $this->input->post('total_amount_received') || $wallet_info['res_arr']['customer_wallet_expiry_date'] <= date('Y-m-d')){
								$this->ReturnJsonArray(false,true,"Either Wallet balance is low or it might expired, Choose another payment method!");
								die;
						}
					}
					//
					if($this->input->post('payment_type') == 'loyalty_wallet'){
						if($wallet_info['res_arr']['customer_rewards'] < $this->input->post('total_amount_received')){
								$this->ReturnJsonArray(false,true,"Low Loyalty Balance, Choose another payment method!");
								die;
						}
					}					
					//
					$curr_sess_payment_data = array();
					if(!isset($this->session->userdata['payment'][''.$customer_id.''])){
						$payment_data = array(
							'full_payment_info' => array(
								'payment_type' => $this->input->post('payment_type'),
								'total_final_bill' => $this->input->post('total_final_bill'),
								'total_amount_received' => $this->input->post('total_amount_received'),
								'balance_to_be_paid_back' => $this->input->post('balance_to_be_paid_back'),
								'pending_amount' => $this->input->post('pending_amount')
							),
							'split_payment_info' => array(),
							'discount_info' => array()
					 	);

						$curr_sess_payment_data  = array(
							"".$this->input->post('customer_id')."" => $payment_data
						);
						$this->session->set_userdata('payment', $curr_sess_payment_data);
						$this->ReturnJsonArray(true,false,"success!");
						die;
					}
					else{
						$payment_data = $this->session->userdata['payment'];
						$payment_data[''.$customer_id.'']['full_payment_info']['payment_type'] = $this->input->post('payment_type');
						$payment_data[''.$customer_id.'']['full_payment_info']['total_final_bill'] = $this->input->post('total_final_bill');
						$payment_data[''.$customer_id.'']['full_payment_info']['total_amount_received'] = $this->input->post('total_amount_received');
						$payment_data[''.$customer_id.'']['full_payment_info']['balance_to_be_paid_back'] = $this->input->post('balance_to_be_paid_back');
						$payment_data[''.$customer_id.'']['full_payment_info']['pending_amount'] = $this->input->post('pending_amount');
						$payment_data[''.$customer_id.'']['split_payment_info'] = array();
						
						
						$this->session->set_userdata('payment', $payment_data);
						$this->ReturnJsonArray(true,false,"success!");
						die;
					}
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}	
	}

	public function SplitPaymentInfo(){
		if($this->IsLoggedIn('cashier')){
			if(isset($_POST) && !empty($_POST)){				
				$this->form_validation->set_rules('total_final_bill', 'Final Bill', 'trim|required');
				$this->form_validation->set_rules('total_amount_received', 'Amount Received', 'trim|required');
				$this->form_validation->set_rules('balance_to_be_paid_back', 'Balance', 'trim|required');
				$this->form_validation->set_rules('total_pending_amount', 'Pending Amount', 'trim|required');

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
					$customer_id = $this->input->post('customer_id');
					if(!empty($_POST['payment_type']) && !empty($_POST['amount_received'])){
						$payment_type = $this->input->post('payment_type');
						$amount_received = $this->input->post('amount_received');

						$counter = 0;
						$split_payment_data = array();
						for($i=0;$i<count($payment_type);$i++){
							$temp = array('payment_type' => $payment_type[$i],'amount_received' => $amount_received[$i]);
							array_push($split_payment_data, $temp);
						}

						if(!isset($this->session->userdata['payment'][''.$customer_id.''])){
							$curr_sess_payment_data = array();
							$payment_data = array(
																'full_payment_info' => array(),
																'split_payment_info' => array(
																'total_final_bill' => $this->input->post('total_final_bill'),
																'total_amount_received' => $this->input->post('total_amount_received'),
																'balance_to_be_paid_back' => $this->input->post('balance_to_be_paid_back'),
																'total_pending_amount' => $this->input->post('total_pending_amount'),
																'multiple_payments' => $split_payment_data
																),
																'discount_info' => array()
													 		);

							$curr_sess_payment_data  = array(
								"".$this->input->post('customer_id')."" => $payment_data
							);
							$this->session->set_userdata('payment', $curr_sess_payment_data);
							$this->ReturnJsonArray(true,false,"success!");
							die;
						}
						else{
							$payment_data = $this->session->userdata['payment'];
							$payment_data[''.$customer_id.'']['split_payment_info']['total_final_bill'] = $this->input->post('total_final_bill');
							$payment_data[''.$customer_id.'']['split_payment_info']['total_amount_received'] = $this->input->post('total_amount_received');
							$payment_data[''.$customer_id.'']['split_payment_info']['balance_to_be_paid_back'] = $this->input->post('balance_to_be_paid_back');
							$payment_data[''.$customer_id.'']['split_payment_info']['total_pending_amount'] = $this->input->post('total_pending_amount');
							$payment_data[''.$customer_id.'']['split_payment_info']['multiple_payments'] = $split_payment_data;
							$payment_data[''.$customer_id.'']['full_payment_info'] = array();
							
							$this->session->set_userdata('payment', $payment_data);
							$this->ReturnJsonArray(true,false,"success!");
							die;
						}
					}
					else{
						$this->ReturnJsonArray(false,true,"Please the all values must be filled!");
						die;
					}
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}	
	}

	public function FullPaymentPackageInfo(){
		if($this->IsLoggedIn('cashier')){
			if(isset($_POST) && !empty($_POST)){
				$this->form_validation->set_rules('payment_type', 'Payment Type', 'trim|required');
				$this->form_validation->set_rules('total_final_bill', 'Final Bill', 'trim|required');
				$this->form_validation->set_rules('total_amount_received', 'Amount Received', 'trim|required');
				$this->form_validation->set_rules('balance_to_be_paid_back', 'Balance', 'trim|required');
				$this->form_validation->set_rules('pending_amount', 'Pending Amount', 'trim|required');

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
					$customer_id = $this->input->post('customer_id');
					$package_payment_data = array();
					if(!isset($this->session->userdata['package_payment'][''.$customer_id.''])){
						$payment_data = array(
														'full_payment_info' => array(
															'payment_type' => $this->input->post('payment_type'),
															'total_final_bill' => $this->input->post('total_final_bill'),
															'total_amount_received' => $this->input->post('total_amount_received'),
															'balance_to_be_paid_back' => $this->input->post('balance_to_be_paid_back'),
															'pending_amount' => $this->input->post('pending_amount')
														),
														'split_payment_info' => array()
												 	);

						$package_payment_data  = array(
							"".$this->input->post('customer_id')."" => $payment_data
						);
						$this->session->set_userdata('package_payment', $package_payment_data);
						$this->ReturnJsonArray(true,false,"success!");
						die;
					}
					else{
						$package_data = $this->session->userdata['package_payment'];
						$payment_data[''.$customer_id.'']['full_payment_info']['payment_type'] = $this->input->post('payment_type');
						$payment_data[''.$customer_id.'']['full_payment_info']['total_final_bill'] = $this->input->post('total_final_bill');
						$payment_data[''.$customer_id.'']['full_payment_info']['total_amount_received'] = $this->input->post('total_amount_received');
						$payment_data[''.$customer_id.'']['full_payment_info']['balance_to_be_paid_back'] = $this->input->post('balance_to_be_paid_back');
						$payment_data[''.$customer_id.'']['full_payment_info']['pending_amount'] = $this->input->post('pending_amount');
						$payment_data[''.$customer_id.'']['split_payment_info'] = array();
						
						
						$this->session->set_userdata('package_payment', $payment_data);
						$this->ReturnJsonArray(true,false,"success!");
						die;
					}
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}	
	}

	public function SplitPaymentPackageInfo(){
		if($this->IsLoggedIn('cashier')){
			if(isset($_POST) && !empty($_POST)){
				
				$this->form_validation->set_rules('total_final_bill', 'Final Bill', 'trim|required');
				$this->form_validation->set_rules('total_amount_received', 'Amount Received', 'trim|required');
				$this->form_validation->set_rules('balance_to_be_paid_back', 'Balance', 'trim|required');
				$this->form_validation->set_rules('pending_amount', 'Pending Amount', 'trim|required');

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
					$customer_id = $this->input->post('customer_id');
					if(!empty($_POST['payment_type']) && !empty($_POST['amount_received'])){
						$payment_type = $this->input->post('payment_type');
						$amount_received = $this->input->post('amount_received');

						$counter = 0;
						$split_payment_data = array();
						for($i=0;$i<count($payment_type);$i++){
							$temp = array('payment_type' => $payment_type[$i],'amount_received' => $amount_received[$i]);
							array_push($split_payment_data, $temp);
						}

						if(!isset($this->session->userdata['package_payment'][''.$customer_id.''])){
							$package_payment_data = array();
							$payment_data = array(
																'full_payment_info' => array(),
																'split_payment_info' => array(
																	'total_final_bill' => $this->input->post('total_final_bill'),
																	'total_amount_received' => $this->input->post('total_amount_received'),
																	'balance_to_be_paid_back' => $this->input->post('balance_to_be_paid_back'),
																	'pending_amount' => $this->input->post('pending_amount'),
																	'multiple_payments' => $split_payment_data
																)
													 		);

							$package_payment_data  = array(
								"".$this->input->post('customer_id')."" => $payment_data
							);
							$this->session->set_userdata('package_payment', $package_payment_data);
							$this->ReturnJsonArray(true,false,"success!");
							die;
						}
						else{
							$payment_data = $this->session->userdata['package_payment'];
							$payment_data[''.$customer_id.'']['split_payment_info']['total_final_bill'] = $this->input->post('total_final_bill');
							$payment_data[''.$customer_id.'']['split_payment_info']['total_amount_received'] = $this->input->post('total_amount_received');
							$payment_data[''.$customer_id.'']['split_payment_info']['balance_to_be_paid_back'] = $this->input->post('balance_to_be_paid_back');
							$payment_data[''.$customer_id.'']['split_payment_info']['pending_amount'] = $this->input->post('pending_amount');
							$payment_data[''.$customer_id.'']['split_payment_info']['multiple_payments'] = $split_payment_data;
							$payment_data[''.$customer_id.'']['full_payment_info'] = array();
							
							$this->session->set_userdata('package_payment', $payment_data);
							$this->ReturnJsonArray(true,false,"success!");
							die;
						}
					}
					else{
						$this->ReturnJsonArray(false,true,"Please the all values must be filled!");
						die;
					}
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}	
	}

		private function TopCardsData($type){
		if($this->IsLoggedIn('cashier')){
				$data = array(
										'type'               => $type,
										'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id'],
										'business_outlet_id' => $this->session->userdata['logged_in']['business_outlet_id']
				);
				
				$result = $this->BusinessAdminModel->GetTopCardsData($data);
				if($result['success'] == 'true'){   
						return $result['res_arr'];
				}
		}
		else{
				$this->LogoutUrl(base_url()."BusinessAdmin/");
		}   
    }
    
    public function ReportsManagement(){
    if($this->IsLoggedIn('cashier')){
            $data = $this->GetDataForCashier("Reports Management");
            $where = array(
                    'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id'],
                    'business_outlet_id' => $this->session->userdata['logged_in']['business_outlet_id']
            );
            
            
            $data['sidebar_collapsed'] = "true";
            $data['bill']= $this->BusinessAdminModel->GetTodaysBill($where);
            $data['bill']= $data['bill']['res_arr'];
            // $this->PrettyPrintArray($data['bill']);
            // exit;
            $data['package_transaction']=$this->CashierModel->GetTodaysPackageTransaction($where);
            $data['package_transaction']=$data['package_transaction']['res_arr'];
          
            $data['last_txn']= $this->CashierModel->LastFiftyTransaction();
						$data['last_txn']=$data['last_txn']['res_arr'];
						// $this->PrettyPrintArray($data['last_txn']);
            $data['service']= $this->CashierModel->ServiceWiseSale($where);
            $data['service']= $data['service']['res_arr'];
            $data['expert']= $this->CashierModel->ExpertWiseSale($where);
            $data['expert']= $data['expert']['res_arr'];
            $data['package_expert']=$this->CashierModel->ExpertWisePackageSale($where);
            $data['package_expert']= $data['package_expert']['res_arr'];
            $data['total_expert_wise']=array();
            // if(count($data['expert']) >= count($data['package_expert'])){    
                foreach($data['expert'] as $key => $value ){
                    foreach($data['package_expert'] as $k=>$v)
                    {
                        if($value['emp_id'] == $v['emp_id']){
                            array_push($data['total_expert_wise'],array($value['emp_id'],$value['net_amt']+$v['package_sales']));
                        }
                        else{
                            array_push($data['total_expert_wise'],array($value['emp_id'],$value['net_amt']));
                        }
                    }
                }
            
            $data['cash_report']= $this->CashierModel->CashReport($where);
            $data['cash_report']= $data['cash_report']['res_arr'];
            
            /////
            $data['cards_data']['sales'] = $this->TopCardsData('sales');
            $data['cards_data']['customer_count'] = $this->TopCardsData('customer_count');
            $data['cards_data']['new_customer'] = $this->TopCardsData('new_customer');
            $data['cards_data']['total_visits'] = $this->TopCardsData('total_visits');
            $data['cards_data']['expenses'] = $this->TopCardsData('expenses');
            $data['cards_data']['yest_expenses'] = $this->TopCardsData('yest_expenses');
            $data['cards_data']['existing_customer'] = $this->TopCardsData('existing_customer');
            $data['categories']  = $this->GetCategories($this->session->userdata['logged_in']['business_outlet_id']);
            $data['cards_data']['payment_wise'] = $this->BusinessAdminModel->GetSalesPaymentWiseData($where);
            $data['cards_data']['payment_wise']=$data['cards_data']['payment_wise']['res_arr'];
            $data['package_payment_wise'] = $this->BusinessAdminModel->GetPackageSalesPaymentWiseData($where);
            $data['package_payment_wise']=$data['package_payment_wise']['res_arr'];
						$data['paid_back']=$this->BusinessAdminModel->GetDailyAmountPaidBack($where);
						$data['paid_back']=$data['paid_back']['res_arr'][0]['paid_back'];
            $data['loyalty_payment']=$this->BusinessAdminModel->GetLoyaltyAmountReceived($where);
            $data['loyalty_payment']=$data['loyalty_payment']['res_arr'][0]['loyalty_wallet'];
            $data['loyalty_points_given']=$this->BusinessAdminModel->GetLoyaltyPointsGiven($where);
            $data['loyalty_points_given']=$data['loyalty_points_given']['res_arr'][0]['loyalty_points'];
            $due_amount=$this->BusinessAdminModel->GetTodaysDueAmount($where);
            $data['due_amount']=$due_amount['res_arr'][0]['due_amount'];
            $data['pending_amount_received']=$this->BusinessAdminModel->GetPendingAmountReceived($where);
            $data['pending_amount_received']=$data['pending_amount_received']['res_arr'][0]['pending_amount_received'];
            $data['card_data']= $this->BusinessAdminModel->TodayPackageSales($where);
            $data['card_data']=$data['card_data']['res_arr'];
            $data['package_payment_wise'] = $this->BusinessAdminModel->GetPackageSalesPaymentWiseData($where);
						$data['package_payment_wise']=$data['package_payment_wise']['res_arr'];
            $data['product_sale_today']=$this->CashierModel->ProductsSalesToday();
            if(isset($data['product_sale_today']['res_arr'])){
                $data['product_sale_today']=$data['product_sale_today']['res_arr'];
            }else{
                $data['product_sale_today'] = ['res_arr'=>''];
                $data['product_sale_today'] = $data['product_sale_today']['res_arr'];
            }
            $data['productsales'] = $this->TopCardsData('productsales');
            // $this->PrettyPrintArray($data['product_sale_today']);
            // exit;
            /////
            $data['collapsed']="true";
            if(!isset($_GET) || empty($_GET)){
                    //Load the default view
                    $this->load->view('cashier/cashier_reports_view',$data);
            }
            else if(isset($_GET) && !empty($_GET)){
                    
                    $data = array(
                            'report_name'            => $_GET['report_name'],
                            'from_date'                  => $_GET['from_date'],
                            'to_date'                    => $_GET['to_date'],
                            'business_outlet_id' => $this->session->userdata['logged_in']['business_outlet_id'],
                            'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id']
                    );
                    $result = $this->BusinessAdminModel->GenerateReports($data);
                    if($result['success'] == 'true'){
                    
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
    }
    else{
            $this->LogoutUrl(base_url()."Cashier/");
    }
}
  //MOST Important Function of POS Billing. Be Careful While Changing things!
  /* SAMPLE-DATA coming from the POST Request
	Array
	(
    [txn_data] => Array
        (
            [txn_customer_id] => 11
            [txn_discount] => 95
            [txn_value] => 481
        )

    [txn_settlement] => Array
        (
            [txn_settlement_way] => Split Payment
            [txn_settlement_payment_mode] => Split
            [txn_settlement_amount_received] => 400
            [txn_settlement_balance_paid] => 0
        )

    [customer_pending_data] => Array
        (
            [customer_id] => 11
            [pending_amount] => 81
            [customer_mobile] => 9990906406
        )

    [cart_data] => Array
        (
            [0] => Array
                (
                    [service_id] => 1
                    [service_total_value] => 220
                    [service_quantity] => 2
                    [service_expert_id] => 4
                    [service_discount_percentage] => 0
                    [service_discount_absolute] => 10
                )

            [1] => Array
                (
                    [service_id] => 1
                    [service_total_value] => 220
                    [service_quantity] => 2
                    [service_expert_id] => 4
                    [service_discount_percentage] => 0
                    [service_discount_absolute] => 10
                )

        )

	)
  */
	
	public function DoTransaction(){		
		if($this->IsLoggedIn('cashier')){			
			if(isset($_POST) && !empty($_POST)){
				$customer_id = $_POST['txn_data']['txn_customer_id'];							
				$count=1;
				foreach($_POST['cart_data'] as $key => $value)
				{
					if($key){
						$count++;
					}
				}
				// end
				$business_admin_id =  $this->session->userdata['logged_in']['business_outlet_id'];
				$result = $this->CashierModel->BillingTransaction($_POST,$this->session->userdata['logged_in']['business_outlet_id'],$this->session->userdata['logged_in']['business_admin_id']);

				if($result['success'] == 'true'){
					$transcation_detail = $this->CashierModel->GetBilledServicesByTxnId($result['res_arr']['res_arr']['insert_id']);
					
					$cart_data['transaction_id'] = $result['res_arr']['res_arr']['insert_id'];
					$cart_data['outlet_admin_id'] = $business_admin_id;					
					$cart_data['transaction_time'] = $transcation_detail['res_arr'][0]['txn_datetime'];
					$cart_data['cart_data'] = json_encode($_POST['cart_data']);
					$cart_detail = $this->CashierModel->Insert($cart_data,'mss_transaction_cart');
					if($cart_detail['success'] == 'true'){
						$this->session->set_userdata('loyalty_point',$transcation_detail['res_arr'][0]['txn_loyalty_points']);
						$detail_id = $cart_detail['res_arr']['insert_id'];
						$bill_url = base_url()."Cashier/generateBill/$customer_id/".base64_encode($detail_id);
						$bill_url = shortUrl($bill_url);
					}

					//1.Unset the payment session
		 			if(isset($this->session->userdata['payment'])){
		 				$this->session->unset_userdata('payment');
					 }
					 //j
					 //Memebership Package Loyalty Calculation
          if(isset($this->session->userdata['cart'][$customer_id]))
          { 
            $data = $this->CashierModel->GetCustomerPackages($customer_id);
            if($data['success'] == 'false')
            {
              // $this->PrettyPrintArray("Customer Does not have special membership");
            }
            else{
              if(!empty($data['res_arr']))
              {
              if(isset($this->session->userdata['cart'][$customer_id]))
              {
                $curr_sess_cart = $this->session->userdata['cart'][$customer_id];
                $cart_data = array();
                $i = 0;
                $j = 0;
                $total_product = 0;
                $total_points = 0;
                for($j;$j<count($curr_sess_cart);$j++)
                {
                  $service_details = $this->CashierModel->DetailsById($curr_sess_cart[$j]['service_id'],'mss_services','service_id');
                  if(isset($service_details['res_arr']))
                  {
                    // print_r($service_details['res_arr']);
                    if($service_details['res_arr']['service_type'] == 'otc')
                    {
                      $total_product += $curr_sess_cart[$j]['service_total_value'];
                      $total_points+= ($curr_sess_cart[$j]['service_total_value']*$data['res_arr'][$i]['service_discount'])/100;
                      $curr_sess_cart[$j] += ['service_discount'=>$data['res_arr'][$i]['service_discount']];
                      
                      array_push($cart_data,$curr_sess_cart[$j]);
                      // array_push($cart_data,$data['res_arr'][$i]['service_discount']);
                    }
                  }
                }
                
                if(!empty($cart_data))
                {
                  foreach($cart_data as $key=>$value)
                  {
                  }
                }
                $update = array(
                  'total_points' => $total_points,
                  'txn_id' => $result['res_arr']['res_arr']['insert_id'],
                  'customer_id' => $customer_id
                );
                $result_2 = $this->CashierModel->SpecialLoyaltyPoints($update,$this->session->userdata['logged_in']['business_outlet_id'],$this->session->userdata['logged_in']['business_admin_id']);
                // $this->PrettyPrintArray($cart_data);
                // exit;
              }
              }
              
            }
        
          }
					 //end
          
          // 2.Then unset the cart session
		 			if(isset($this->session->userdata['cart'][$customer_id])){
		 				$curr_sess_cart_data  = $this->session->userdata['cart'];
		 				unset($curr_sess_cart_data[''.$customer_id.'']);
		 				$this->session->set_userdata('cart',$curr_sess_cart_data);
		 			}
                    if(isset($this->session->userdata['recommended_ser'][$customer_id])){
						$curr_sess_cart_data  = $this->session->userdata['recommended_ser'];
						unset($curr_sess_cart_data[''.$customer_id.'']);
						$this->session->set_userdata('recommended_ser',$curr_sess_cart_data);
					}
					//3.Then unset the customer session from POS
		 			if(isset($this->session->userdata['POS'])){
		 				$curr_sess_cust_data  = $this->session->userdata['POS'];
		 				
		 				$key = array_search($customer_id, array_column($curr_sess_cust_data, 'customer_id'));
		 				
		 				unset($curr_sess_cust_data[$key]);
		 				$curr_sess_cust_data = array_values($curr_sess_cust_data);
		 				
		 				$this->session->set_userdata('POS',$curr_sess_cust_data);
		 			}

		 			//These 2 call will be used for the further enhancement of message sending architecture.
		 			$outlet_details = $this->GetCashierDetails();
					$customer_details = $this->GetCustomerBilling($_POST['customer_pending_data']['customer_id']);
					//4.Send a msg
					$this->session->set_userdata('bill_url',$bill_url);
					if($_POST['send_sms'] === 'true' && $_POST['cashback']>0){
						if($_POST['txn_data']['txn_value']==0){
						$this->SendPackageTransactionSms($_POST['txn_data']['sender_id'],$_POST['txn_data']['api_key'],$_POST['customer_pending_data']['customer_mobile'],$_POST['cart_data'][0]['salon_package_name'],$count,$customer_details['customer_name'],$_POST['cart_data'][0]['service_count'],$outlet_details['business_outlet_google_my_business_url'],$customer_details['customer_rewards']);
						}else{		
						$this->SendCashbackSms($_POST['txn_data']['sender_id'],$_POST['txn_data']['api_key'],$_POST['customer_pending_data']['customer_mobile'],$_POST['txn_data']['txn_value'],$_POST['cashback'],$outlet_details['business_outlet_name'],$customer_details['customer_name'],$outlet_details['business_outlet_google_my_business_url'],$customer_details['customer_rewards']);
						}
					}
					//
					if($_POST['send_sms'] === 'true' && $_POST['cashback']==0){
						if($_POST['txn_data']['txn_value']==0){
						$this->SendPackageTransactionSms($_POST['txn_data']['sender_id'],$_POST['txn_data']['api_key'],$_POST['customer_pending_data']['customer_mobile'],$_POST['cart_data'][0]['salon_package_name'],$count,$customer_details['customer_name'],$_POST['cart_data'][0]['service_count'],$outlet_details['business_outlet_google_my_business_url'],$customer_details['customer_rewards']);
						}else{		
						$this->SendSms($_POST['txn_data']['sender_id'],$_POST['txn_data']['api_key'],$_POST['customer_pending_data']['customer_mobile'],$_POST['txn_data']['txn_value'],$outlet_details['business_outlet_name'],$customer_details['customer_name'],$outlet_details['business_outlet_google_my_business_url'],$customer_details['customer_rewards']);
						}
					}
					//
        
					$this->ReturnJsonArray(true,false,"Transaction is successful!");
					die;
				}
				elseif ($result['error'] == 'true') {
					$this->ReturnJsonArray(false,true,$result['message']);
					die;
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}
	}
	public function SendSms($sender_id,$api_key,$mobile,$bill_amt,$outlet_name,$customer_name,$google_url,$loyalty=""){
		if($this->IsLoggedIn('cashier')){
			$bill_url = $this->session->userdata('bill_url');
			$loyalty = $this->session->userdata('loyalty_point');
		error_log("URL ============1 ".$bill_url);
  		//API key & sender ID
  		// $apikey = "ll2C18W9s0qtY7jIac5UUQ";
			// $apisender = "BILLIT";
			// $apikey="32kO6tWy5UuN16e3fOQpPg";
			// $apisender="DSASSH";
			
  		//$msg = "Dear ".$customer_name.", Thanks for Visiting ".$outlet_name."! You have been billed for Rs.".$bill_amt.". Look forward to serving you again!Review us on ".$google_url." to serve you better and Please find the invoice on ".$bill_url;
  		if(!empty($loyalty) && $loyalty > 0){
  			if(!empty($google_url)){
  				$msg = "Dear ".$customer_name.", Thanks for visiting ".$outlet_name."! You have earned $loyalty rewards,on your bill of Rs.$bill_amt. View $bill_url Review us on Google: $google_url";
  			}else{
  				$msg = "Dear ".$customer_name.", Thanks for visiting ".$outlet_name."! You have earned $loyalty rewards,on your bill of Rs.$bill_amt. View $bill_url ";
  			}  			
  		}else if(!empty($google_url)){
  			$msg = "Dear $customer_name, Thanks for visiting $outlet_name. View your bill of $bill_amt, $bill_url Review us on Google: $google_url";
  		}else{
  			$msg = "Dear $customer_name, Thanks for visiting $outlet_name. View your bill of $bill_amt, $bill_url Look forward to serve you again.";
  		}  		
   		$msg = rawurlencode($msg);   //This for encode your message content                 		
 			
 			// API 
			$url = 'https://www.smsgatewayhub.com/api/mt/SendSMS?APIKey='.$api_key.'&senderid='.$sender_id.'&channel=2&DCS=0&flashsms=0&number='.$mobile.'&text='.$msg.'&route=1';
            log_message('info', $url);
        
  		$ch = curl_init($url);
  		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  		curl_setopt($ch,CURLOPT_POST,1);
  		curl_setopt($ch,CURLOPT_POSTFIELDS,"");
  		curl_setopt($ch, CURLOPT_RETURNTRANSFER,2);
  		
  		$data = curl_exec($ch);
  		return json_encode($data);
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}				
	}
	//Send acshback SMS
	public function SendCashbackSms($sender_id,$api_key,$mobile,$bill_amt,$cashback,$outlet_name,$customer_name,$google_url,$loyalty = ''){
		if($this->IsLoggedIn('cashier')){
			$bill_url = $this->session->userdata('bill_url');
			$loyalty = $this->session->userdata('loyalty_point');
			
		error_log("URL ============2 ".$bill_url);
  		//API key & sender ID
  		// $apikey = "ll2C18W9s0qtY7jIac5UUQ";
			// $apisender = "BILLIT";
			// $apikey="32kO6tWy5UuN16e3fOQpPg";
			// $apisender="DSASSH";
			
  		//$msg = "Dear ".$customer_name.", Thanks for Visiting ".$outlet_name."! You have been billed for Rs.".$bill_amt."/-. Look forward to serving you again! and Please find the invoice on $bill_url . Review us on Google: https://bit.ly/396f8ok";
  		if(!empty($loyalty) && $loyalty > 0){
  			if($google_url){
  				$msg = "Dear ".$customer_name.", Thanks for visiting ".$outlet_name."! You have earned $loyalty rewards,on your bill of Rs.$bill_amt. View $bill_url Review us on Google: $google_url";
  			}else{
  				$msg = "Dear ".$customer_name.", Thanks for visiting ".$outlet_name."! You have earned $loyalty rewards,on your bill of Rs.$bill_amt. View $bill_url";
  			}
  		}else if(!empty($google_url)){
  			$msg = "Dear $customer_name, Thanks for visiting $outlet_name. View your bill of $bill_amt, $bill_url Review us on Google: $google_url";
  		}else{
  			$msg = "Dear $customer_name, Thanks for visiting $outlet_name. View your bill of $bill_amt, $bill_url Look forward to serve you again.";
  		}
   		$msg = rawurlencode($msg);   //This for encode your message content                 		
 			
 			// API 
			$url = 'https://www.smsgatewayhub.com/api/mt/SendSMS?APIKey='.$api_key.'&senderid='.$sender_id.'&channel=2&DCS=0&flashsms=0&number='.$mobile.'&text='.$msg.'&route=1';
                    error_log("msgurl ============ ".$url);
  		$ch = curl_init($url);
  		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  		curl_setopt($ch,CURLOPT_POST,1);
  		curl_setopt($ch,CURLOPT_POSTFIELDS,"");
  		curl_setopt($ch, CURLOPT_RETURNTRANSFER,2);
  		
  		$data = curl_exec($ch);
  		return json_encode($data);
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}				
	}


	public function PrintBill(){
		$this->load->helper('pdfhelper');//loading pdf helper
		if($this->IsLoggedIn('cashier')){	
			$customer_id = $this->uri->segment(3);
			if(isset($customer_id)){
				//Check whether customer belongs to the logged cashier's shop and business admin
				$where = array(
					'business_admin_id' => $this->session->userdata['logged_in']['business_admin_id'],
					'business_outlet_id'=> $this->session->userdata['logged_in']['business_outlet_id'],
					'master_admin_id'=> $this->session->userdata['logged_in']['master_admin_id'],
					'customer_id'       => $customer_id
				);

				$check = $this->CashierModel->VerifyCustomer($where);				
				if($check['success'] == 'true'){	
					$data['title'] = "Invoice";
					$data['experts'] = $this->GetExperts();
					$data['shop_details'] = $this->ShopDetails();
					$data['individual_customer'] = $this->GetCustomerBilling($customer_id);
				
					//for Bill No
					$outlet_counter = $this->db->select('*')->from('mss_business_outlets')->where('business_outlet_id',$this->session->userdata['logged_in']['business_outlet_id'])->get()->row_array();

					$data['bill_no']=strval("A".strval(100+$this->session->userdata['logged_in']['business_admin_id']) . "O" . strval( $this->session->userdata['logged_in']['business_outlet_id']) . "-" . strval($outlet_counter['business_outlet_bill_counter']));
										
					if(isset($this->session->userdata['cart'][$customer_id])){
						$data['cart'] = $this->session->userdata['cart'][$customer_id];
					}

					if(isset($this->session->userdata['payment'])){
						$data['payment'] = $this->session->userdata['payment'][$customer_id];
					}

					$outlet_admin_id = $this->session->userdata['logged_in']['business_outlet_id'];
					//print_r($result[0]['outlet_admin_id']);die;
					// print_r($data['cart']);
					$sql ="SELECT config_value from mss_config where config_key='salon_logo' and outlet_admin_id = $outlet_admin_id";

					$query = $this->db->query($sql);
					$result = $query->result_array();
					if(empty($result)){
						$sql ="SELECT config_value from mss_config where config_key='salon_logo' and outlet_admin_id = 1";

						$query = $this->db->query($sql);
						$result = $query->result_array();
					}
					$data['logo'] = $result[0]['config_value'];		
					// $this->PrettyPrintArray($data['payment']);				
					$this->load->view('cashier/cashier_print_bill',$data);
				}
				elseif ($check['error'] == 'true'){
					$data = array(
						'heading' => "Illegal Access!",
						'message' => $check['message']
					);
					$this->load->view('errors/html/error_general',$data);	
				}
			}
			else{
				$data = array(
					'heading' => "Illegal Access!",
					'message' => "Customer details/ID missing. Please do not change url!"
				);
				$this->load->view('errors/html/error_general',$data);
			}	
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}	
	}

	public function PrintBillPackage(){
		$this->load->helper('pdfhelper');
		if($this->IsLoggedIn('cashier')){   
				$customer_id = $this->uri->segment(3);
				if(isset($customer_id)){
						//Check whether customer belongs to the logged cashier's shop and business admin
						$where = array(
								'business_admin_id' => $this->session->userdata['logged_in']['business_admin_id'],
								'business_outlet_id'=> $this->session->userdata['logged_in']['business_outlet_id'],
								'master_admin_id'=> $this->session->userdata['logged_in']['master_admin_id'],
								'customer_id'       => $customer_id
						);
						$check = $this->CashierModel->VerifyCustomer($where);

						if($check['success'] == 'true'){    
								$data['title'] = "Invoice";
								$data['shop_details'] = $this->ShopDetails();
								$data['individual_customer'] = $this->GetCustomerBilling($customer_id);
								if(isset($this->session->userdata['package_cart'])){
										$data['package_cart'] = $this->session->userdata['package_cart'];
								}
								if(isset($this->session->userdata['package_payment'])){
										$data['package_payment'] = $this->session->userdata['package_payment'][$customer_id];
								}

								$outlet_admin_id = $this->session->userdata['logged_in']['business_outlet_id'];
								//print_r($result[0]['outlet_admin_id']);die;
								// print_r($data['cart']);
								$sql ="SELECT config_value from mss_config where config_key='salon_logo' and outlet_admin_id = $outlet_admin_id";

								$query = $this->db->query($sql);
								$result = $query->result_array();
								if(empty($result)){
									$sql ="SELECT config_value from mss_config where config_key='salon_logo' and outlet_admin_id = 1";

									$query = $this->db->query($sql);
									$result = $query->result_array();
								}
								$data['logo'] = $result[0]['config_value'];
								// $this->PrettyPrintArray($data['shop_details']);
								// die;

								$this->load->view('cashier/cashier_package_print_bill',$data);
						}
						elseif ($check['error'] == 'true'){
								$data = array(
										'heading' => "Illegal Access!",
										'message' => $check['message']
								);
								$this->load->view('errors/html/error_general',$data);   
						}
				}
				else{
						$data = array(
								'heading' => "Illegal Access!",
								'message' => "Customer details/ID missing. Please do not change url!"
						);
						$this->load->view('errors/html/error_general',$data);
				}   
		}
		else{
				$this->LogoutUrl(base_url()."Cashier/");
		}   
}
	public function JobOrder(){
		if($this->IsLoggedIn('cashier')){	
			$customer_id = $this->uri->segment(3);
			if(isset($customer_id)){
				//Check whether customer belongs to the logged cashier's shop and business admin
				$where = array(
					'business_admin_id' => $this->session->userdata['logged_in']['business_admin_id'],
					'business_outlet_id'=> $this->session->userdata['logged_in']['business_outlet_id'],
					'master_admin_id'=> $this->session->userdata['logged_in']['master_admin_id'],
					'customer_id'       => $customer_id
				);

				$check = $this->CashierModel->VerifyCustomer($where);
				if($check['success'] == 'true'){	
					$data['title'] = "Job Order";
					$data['experts'] = $this->GetExperts();
					$data['shop_details'] = $this->ShopDetails();
					$data['individual_customer'] = $this->GetCustomerBilling($customer_id);
				
					
					if(isset($this->session->userdata['cart'][$customer_id])){
						$data['cart'] = $this->session->userdata['cart'][$customer_id];
					}

					$this->load->view('cashier/cashier_job_order',$data);
				}
				elseif ($check['error'] == 'true'){
					$data = array(
						'heading' => "Illegal Access!",
						'message' => $check['message']
					);
					$this->load->view('errors/html/error_general',$data);	
				}
			}
			else{
				$data = array(
					'heading' => "Illegal Access!",
					'message' => "Customer details/ID missing. Please do not change url!"
				);
				$this->load->view('errors/html/error_general',$data);
			}	
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}	
	}

	public function Inventory(){
        if($this->IsLoggedIn('cashier')){
            if(isset($_POST) && !empty($_POST)){
            }
            else{
                //Unset any session so that no one interfere in billing logic
                if(isset($this->session->userdata['Package_Customer'])){
                    $this->session->unset_userdata('Package_Customer');
                }
                if(isset($this->session->userdata['package_cart'])){
                    $this->session->unset_userdata('package_cart');
                }
                if(isset($this->session->userdata['payment'])){
                    $this->session->unset_userdata('payment');
                }
                if(isset($this->session->userdata['package_payment'])){
                    $this->session->unset_userdata('package_payment');
                }
                //
                $data = $this->GetDataForCashier('Add Inventory');
                $data['raw_materials'] = $this->GetRawMaterials();
                $data['otc_items'] = $this->GetOTCItems();
                // $data['otc_stock'] = $this->GetOTCStock();
                $data['otc_stock']=$this->CashierModel->InventoryStock();
                if($data['otc_stock']['success'] == 'true'){
                    $data['otc_stock']=$data['otc_stock']['res_arr'];
                }
                $where=array(
									'business_outlet_id'=>$this->session->userdata['logged_in']['business_outlet_id']
								);
								$data['vendors']=$this->BusinessAdminModel->MultiWhereSelect('mss_vendors',$where);
								if($data['vendors']['success'] == 'true'){
										$data['vendors']=$data['vendors']['res_arr'];
								}
                $data['categories']  = $this->GetCategoriesOtc($this->session->userdata['logged_in']['business_outlet_id']);
                $data['sub_categories']  = $this->GetSubCategories($this->session->userdata['logged_in']['business_outlet_id']);
                $data['services']  = $this->GetServices($this->session->userdata['logged_in']['business_outlet_id']);
                $data['raw_material_stock'] = $this->GetRawMaterialStock();
                $m = $this->uri->segment(3);
                if(isset($m))
                {
                    $data['modal']=1;
                }
                else{
                    $data['modal']=0;
                }
                $this->load->view('cashier/cashier_add_inventory_view',$data);
            }
        }
        else{
            $this->LogoutUrl(base_url()."Cashier/Login");
        }   
    }

	private function GetOTCStock(){
		if($this->IsLoggedIn('cashier')){
			$where = array(
				'category_business_admin_id' => $this->session->userdata['logged_in']['business_admin_id'],
				'category_business_outlet_id'=> $this->session->userdata['logged_in']['business_outlet_id']
			);
		
			$data = $this->CashierModel->GetCurrentOTCStock($where);
			if($data['success'] == 'true'){	
				return $data['res_arr'];
			}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}
	}

	private function GetRawMaterialStock(){	
		if($this->IsLoggedIn('cashier')){
			$where = array(
				'raw_material_business_admin_id' => $this->session->userdata['logged_in']['business_admin_id'],
				'raw_material_business_outlet_id'=> $this->session->userdata['logged_in']['business_outlet_id']
			);
		
			$data = $this->CashierModel->GetCurrentRMStock($where);
			if($data['success'] == 'true'){	
				return $data['res_arr'];
				
			}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}		
	}

	public function AddRawMaterialInventory(){
		if($this->IsLoggedIn('cashier')){
			if(isset($_POST) && !empty($_POST)){
				$this->form_validation->set_rules('rmc_id', 'Raw material category', 'trim|required');
				// $this->form_validation->set_rules('rm_entry_date', 'Entry Date', 'trim|required');
				// $this->form_validation->set_rules('rm_expiry_date', 'Expiry Date', 'trim|required');
				$this->form_validation->set_rules('rm_sku', 'SKU', 'trim|required|is_natural_no_zero');
				$this->form_validation->set_rules('rm_quantity', 'Quantity/SKU', 'trim|required|is_natural_no_zero');
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
					$where = array(
						'rmc_id'  => $this->input->post('rmc_id')
					);
					
					$RmStockExists = $this->CashierModel->CheckRmStockExists($where);
					
					if($RmStockExists['success'] == 'false' && $RmStockExists['error'] == 'true' ){
						//Insert the Raw Material Stock for the first time
						$rm_stock = (int)$this->input->post('rm_sku')*(int)$this->input->post('rm_quantity');
						
						$data = array(
							'rmc_id' 					=> $this->input->post('rmc_id'),
							'rm_entry_date' 	=> $this->input->post('rm_entry_date'),
							'rm_expiry_date'  => $this->input->post('rm_expiry_date'),
							'rm_stock' 				=> $rm_stock,
						);

						$result = $this->CashierModel->Insert($data,'mss_raw_material_stock');
						if($result['success'] == 'true'){
							$this->ReturnJsonArray(true,false,"Stock added successfully!");
							die;
						}
						else if($result['error'] == 'true'){
							$this->ReturnJsonArray(false,true,$result['message']);
							die;
						}
					}	
					else{
						//Update the Stock of Raw Material
						$rm_stock = (int)$this->input->post('rm_sku')*(int)$this->input->post('rm_quantity');
						
						$data = array(
							'rmc_id' 					=> $this->input->post('rmc_id'),
							'rm_entry_date' 	=> $this->input->post('rm_entry_date'),
							'rm_expiry_date'  => $this->input->post('rm_expiry_date'),
							'rm_stock' 				=> $rm_stock,
						);
						
						$result = $this->CashierModel->UpdateRawMaterialStock($data);
							
						if($result['success'] == 'true'){
							$this->ReturnJsonArray(true,false,"Stock added successfully!");
							die;
						}
						else if($result['error'] == 'true'){
							$this->ReturnJsonArray(false,true,$result['message']);
							die;
						}
	        }
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}
	}

	public function AddOTCInventory(){
        if($this->IsLoggedIn('cashier')){
            if(isset($_POST) && !empty($_POST)){
				// $this->PrettyPrintArray($_POST);
					$this->form_validation->set_rules('otc_item','OTC Name', 'trim|required');
          $this->form_validation->set_rules('sku_size', 'SKU', 'trim|required|is_natural_no_zero');
                $a=explode(',',$_POST['sku_size']);
                $_POST['service_id']=$a[0];
                $_POST['sku_size']=$a[1];
                $year=$_POST['year'];
                $month=$_POST['month'];
                $_POST['month']=$year.'-'.$month;
                $master_admin = $this->CashierModel->DetailsById($this->session->userdata['logged_in']['business_admin_id'],'mss_business_admin','business_admin_id');
                $master_admin_id = $master_admin['res_arr']['business_master_admin_id'];
                $service_details = $this->CashierModel->DetailsById($_POST['service_id'],'mss_services','service_id');
								$service_details = $service_details['res_arr'];
				
                if ($this->form_validation->run() == FALSE){
                    $data = array(
                                    'success' => 'false',
                                    'error'   => 'true',
                                    'message' =>  validation_errors()
                                 );
                    header("Content-type: application/json");
                    print(json_encode($data, JSON_PRETTY_PRINT));
                    die;
                }else{
									$product_type = $this->CashierModel->DetailsById($service_details['service_sub_category_id'],'mss_sub_categories','sub_category_id');
									$product_type=$product_type['res_arr']['sub_category_name'];
									$outlet_counter = $this->CashierModel->DetailsById($this->session->userdata['logged_in']['business_outlet_id'],'mss_inventory_transaction','outlet_id');
									$outlet_counter=count($outlet_counter)-2;
									$where = array(
											'service_name'  => $this->input->post('otc_item'),
											'sku_size' => $this->input->post('sku_size'),
											'service_id'=>$this->input->post('service_id')
									);
									$data2=array(
											'txn_id'    =>  strval("I".strval(100+((int)$this->session->userdata['logged_in']['business_admin_id'])) . "O" . strval($this->session->userdata['logged_in']['business_outlet_id']) . "-" . strval($outlet_counter)),
											'master_admin_id'   =>$master_admin_id,
											'business_admin_id' => $this->session->userdata['logged_in']['business_admin_id'],
											'outlet_id' => $this->session->userdata['logged_in']['business_outlet_id'],
											'invoice_no'    => ' ',
											'source_type'   =>' ',
											'source_name'   =>' ',
											'datetime'  =>date('Y-m-d h:i:s'),
											'invoice_amt'   => $service_details['service_price_inr']*$_POST['sku_count'],
											'barcode'   => $service_details['barcode'],
											'sku_count' => $_POST['sku_count'],
											'mss_service_id'=>$this->input->post('service_id'),
											'stock_level'   => ' ',
											'usg_category'=>$_POST['otc_inventory_type'],
											'expiry'    => $_POST['month'],
											'brand_name'    =>$service_details['service_brand'],
											'product_type'  => $product_type,
											'sku_size'  => $_POST['sku_size'],
											'unit'  => $_POST['unit'],
											'mrp'   => ($service_details['service_price_inr']*$_POST['sku_count'])+($service_details['service_price_inr']*$_POST['sku_count'])*$service_details['service_gst_percentage']/100,
											'gst'   => $service_details['service_gst_percentage']
									);
						$this->CashierModel->Insert($data2,'mss_inventory_transaction');
						$data3=array(
							'business_outlet_id'=>$this->session->userdata['logged_in']['business_outlet_id'],
							'business_admin_id'=>$this->session->userdata['logged_in']['business_admin_id'],
							'master_admin_id'=>$this->session->userdata['logged_in']['master_admin_id'],
							'date'=>$this->input->post('ipurchase_date'),
							'vendor_id'=>$this->input->post('ivendors'),
							'invoice_no'=>$this->input->post('iinvoice_no'),
							'HSN_No'=>$this->input->post('ihsn_no'),
							'item_id'=>$this->input->post('ipurchase_date'),
							'item_name'=>$this->input->post('iproduct_name'),
							'base_amt'=>$this->input->post('ibase_cost'),
							'gst'=>$this->input->post('igst'),
							'total_mrp_amt'=>$this->input->post('itotal_cost'),
							'mrp'=>$this->input->post('imrp'),
							'type_of_invoice'=>$this->input->post('itype_of_invoice'),
							'freight_charges'=>$this->input->post('ifreight_charges')
						);
						$this->CashierModel->Insert($data3,'mss_inventory_transaction_source');
						// Expense
						$datam=array(
							'outlet_id'=>$this->session->userdata['logged_in']['business_outlet_id']
						);
						$da=$this->BusinessAdminModel->SelectMaxIdExpense($datam);
						$da=$da['res_arr'][0]['id'];
						$vendor_name=$this->BusinessAdminModel->DetailsById($this->input->post('ivendors'),'mss_vendors','vendor_id');
						$vendor_name=$vendor_name['res_arr'];
						// $this->PrettyPrintArray($_SESSION);
						// die;
						$expense_id= "E1010Y".$this->session->userdata['logged_in']['business_outlet_id'].($da+1);
							$data5 = array(
								'expense_unique_serial_id'=>$expense_id, 
								'expense_date'      => $this->input->post('ipurchase_date'),
								'expense_type_id'   => 0,
								'item_name'         => $this->input->post('iproduct_name'),
								'amount'            => $this->input->post('itotal_cost'),
								'remarks'           => '',
								'payment_type'      => 'vendor',
								'payment_to'        => $vendor_name['vendor_id'],
								'total_amount'      =>$this->input->post('itotal_cost'),
								'payment_to_name'   => $vendor_name['vendor_name'],
								'payment_mode'      => 'Cash',
								'pending_amount'    => 0,
								'expense_status'    => 'Paid',
								'employee_name'     => $this->session->userdata['logged_in']['employee_name'],
								'invoice_number'    => $this->input->post('iinvoice_no'),
								'bussiness_outlet_id' =>$this->session->userdata['logged_in']['business_outlet_id']
							);
						//$this->CashierModel->Insert($data5,'mss_expenses');
                        $OTCstockexistsnew = $this->CashierModel->CheckOTCStockExist($where);
                        $OTCstockexists = $this->CashierModel->CheckOTCStockExists($where);
                        if($OTCstockexistsnew['success'] == 'false' && $OTCstockexistsnew['error'] == 'true' ){
                            $data = array(
                                'service_id'             => $this->input->post('service_id'),
                                'master_admin_id'   => $master_admin_id,
                                'business_admin_id' => $this->session->userdata['logged_in']['business_admin_id'],
                                'outlet_id' => $this->session->userdata['logged_in']['business_outlet_id'],
                                'barcode_id'    => $service_details['barcode_id'],
                                'barcode'   => $this->input->post('barcode'),
                                'brand_name'    => $this->input->post('otc_item'),
                                'product_type'  => $this->input->post('otc_sub_category'),
                                'usg_category'  =>  $this->input->post('otc_inventory_type'),
                                'expiry'  => $_POST['month'],
                                'mrp'   => $service_details['service_price_inr']+($service_details['service_price_inr']*($service_details['service_gst_percentage']/100)),
                                'sku_count'          => $this->input->post('sku_count'),
                                'sku_size'  => $this->input->post('sku_size'),
                                'unit'  => $this->input->post('unit')
                            );
                            // $this->PrettyPrintArray($data);
                            $result=$this->CashierModel->Insert($data,'mss_inventory');
                            // $result = $this->CashierModel->Insert($data,'mss_otc_stock');
                            if($result['success'] == 'true'){
                                $this->ReturnJsonArray(true,false,"OTC Stock added successfully!");
                                die;
                            }
                            else if($result['error'] == 'true'){
                                $this->ReturnJsonArray(false,true,$result['message']);
                                die;
                            }
                        }   
                        else{
													$data = array(
															'service_id'             => $this->input->post('service_id'),
															'master_admin_id'   => $master_admin_id,
															'business_admin_id' => $this->session->userdata['logged_in']['business_admin_id'],
															'outlet_id' => $this->session->userdata['logged_in']['business_outlet_id'],
															'barcode_id'    => $service_details['barcode_id'],
															'barcode'   => $this->input->post('barcode'),
															'brand_name'    => $this->input->post('otc_item'),
															'product_type'  => $this->input->post('otc_sub_category'),
															'usg_category'  =>  $this->input->post('otc_inventory_type'),
															'expiry'  => $_POST['month'],
															'mrp'   => $service_details['service_price_inr']+($service_details['service_price_inr']*($service_details['service_gst_percentage']/100)),
															'sku_count'          => $this->input->post('sku_count'),
															'sku_size'  => $this->input->post('sku_size'),
															'unit'  => $this->input->post('unit')
													);
													$result = $this->CashierModel->UpdateOTCStocknew($data);
													if($result['success'] == 'true'){
															$this->ReturnJsonArray(true,false,"Stock added successfully!");
															die;
													}
													else if($result['error'] == 'true'){
															$this->ReturnJsonArray(false,true,$result['message']);
															die;
													}
                        }
                        if($OTCstockexists['success'] == 'false' && $OTCstockexists['error'] == 'true' ){
                            //Insert the OTC Stock for the first time
                            $expiry=$_POST['month'].'-'.$_POST['year'];
                            $data = array(
                                'otc_service_id'             => $this->input->post('service_id'),
                                // 'otc_expiry_date'  => $expiry,
                                'otc_sku'          => $this->input->post('sku_count'),
                                'qty_per_sku'           =>  $this->input->post('sku_size')
                            );
                            $result = $this->CashierModel->Insert($data,'mss_otc_stock');
                            if($result['success'] == 'true'){
                                $this->ReturnJsonArray(true,false,"OTC Stock added successfully!");
                                die;
                            }
                            else if($result['error'] == 'true'){
                                $this->ReturnJsonArray(false,true,$result['message']);
                                die;
                            }
                        }   
                        else{
                        //Update the Stock of OTC
                            $data = array(
                                'otc_service_id'             => $this->input->post('service_id'),
                                // 'otc_entry_date'      => $this->input->post('otc_entry_date'),
                                'otc_expiry_date'  => $this->input->post('expiry_date'),
                                'otc_sku'          => $this->input->post('sku_count')
														);
														
                            $result = $this->CashierModel->UpdateOTCStock($data);
                            if($result['success'] == 'true'){
                                $this->ReturnJsonArray(true,false,"Stock added successfully!");
                                die;
                            }
                            else if($result['error'] == 'true'){
                                $this->ReturnJsonArray(false,true,$result['message']);
                                die;
                            }
                        }
                }
            }
        }
        else{
            $this->LogoutUrl(base_url()."Cashier/");
        }
    }

	//Expense Tracker function
	public function Expenses(){
        if($this->IsLoggedIn('cashier')){       
            if(isset($_POST) && !empty($_POST)){
                $this->form_validation->set_rules('expense_type_id', 'Expense Type', 'trim|required');
                $this->form_validation->set_rules('item_name', 'Item Name', 'trim|required|max_length[50]');
                $this->form_validation->set_rules('total_amt', 'Total Amount', 'trim|required|is_natural_no_zero');
                $this->form_validation->set_rules('payment_mode', 'Payment Mode', 'trim|required|max_length[50]');
                $this->form_validation->set_rules('expense_status', 'Expense Status', 'trim|required|max_length[50]');
                $this->form_validation->set_rules('employee_name', 'Employee Name', 'trim|required|max_length[100]');
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
                    if($_POST['expense_status']=='Unpaid')
                {
                    $_POST['pending_amount']=$_POST['total_amt'];
                    $_POST['amount']=0;
                }
                if($_POST['payment_type'] == 'others'){
                    $payment_to=0;
                    $payment_to_name=$_POST['payment_to_others'];
                }elseif($_POST['payment_type'] == 'employee'){
                    $payment_to=$_POST['payment_to'];
                    $payment_to_name=$this->BusinessAdminModel->DetailsById($payment_to,'mss_employees','employee_id');
                    $payment_to_name=$payment_to_name['res_arr'];
                    $fname=$payment_to_name['employee_first_name'];
                    $lname=$payment_to_name['employee_last_name'];
                    $payment_to_name=$fname." ".$lname;
                }
                else{
                    $payment_to=$_POST['payment_to'];
                    $payment_to_name=$this->BusinessAdminModel->DetailsById($payment_to,'mss_vendors','vendor_id');
                    $payment_to_name=$payment_to_name['res_arr']['vendor_name'];
                }
                $datam=array(
                    'outlet_id'=>$this->session->userdata['logged_in']['business_outlet_id']
                );
                $da=$this->BusinessAdminModel->SelectMaxIdExpense($datam);
                // $this->PrettyPrintArray($da);
                $da=$da['res_arr'][0]['id'];
                $expense_id= "E1010Y".$this->session->userdata['logged_in']['business_outlet_id'].($da+1);
                    $data = array(
                        'expense_unique_serial_id'=>$expense_id, 
                        'expense_date'      => $this->input->post('entry_date'),
                        'expense_type_id'   => $this->input->post('expense_type_id'),
                        'item_name'         => $this->input->post('item_name'),
                        'amount'            => $this->input->post('amount'),
                        'remarks'           => $this->input->post('remarks'),
                        'payment_type'      => $this->input->post('payment_type'),
                        'payment_to'        => $payment_to,
                        'total_amount'      =>$this->input->post('total_amt'),
                        'payment_to_name'   => $payment_to_name,
                        'payment_mode'      => $this->input->post('payment_mode'),
                        'pending_amount'    => $this->input->post('pending_amount'),
                        'expense_status'    => $this->input->post('expense_status'),
                        'employee_name'     => $this->input->post('employee_name'),
                        'invoice_number'    => $this->input->post('invoice_number'),
                        'bussiness_outlet_id'=>$this->session->userdata['logged_in']['business_outlet_id']
                    );
                    $data1 = array(
                        'expense_date'      => $this->input->post('entry_date'),
                        'expense_type_id'   => $this->input->post('expense_type_id'),
                        'item_name'         => $this->input->post('item_name'),
                        'amount'            => $this->input->post('amount'),
                        'remarks'           => $this->input->post('remarks'),
                        'payment_type'      => $this->input->post('payment_type'),
                        'payment_to'        => $payment_to,
                        'total_amount'      =>$this->input->post('total_amt'),
                        'payment_to_name'   => $payment_to_name,
                        'payment_mode'      => $this->input->post('payment_mode'),
                        'pending_amount'    => $this->input->post('pending_amount'),
                        'expense_status'    => $this->input->post('expense_status'),
                        'employee_name'     => $this->input->post('employee_name'),
                        'invoice_number'    => $this->input->post('invoice_number'),
                        'bussiness_outlet_id'=>$this->session->userdata['logged_in']['business_outlet_id']
                    );
                    // $this->PrettyPrintArray($_POST);
                    // exit;
                    // if($_POST['expense_status'] != 'Unpaid' && $_POST['expense_status'] != 'Partialy_paid'){
                    //  $result = $this->BusinessAdminModel->Insert($data,'mss_expenses');
                    //  }
                    //  else{
                    //      $result = $this->BusinessAdminModel->Insert($data,'mss_expenses_unpaid');
                    //  }
                    if($_POST['expense_status'] != 'Unpaid'){
                        $result = $this->BusinessAdminModel->Insert($data,'mss_expenses');
                        }
                        if($_POST['expense_status'] != 'paid'){
                            $result = $this->BusinessAdminModel->Insert($data1,'mss_expenses_unpaid');
                        }
                        if($result['success'] == 'true'){
                            $this->ReturnJsonArray(true,false,"Expense added successfully!");
                            die;
                          }
                          elseif($result['error'] == 'true'){
                            $this->ReturnJsonArray(false,true,$result['message']);
                            die;
                          }
                }
            }
            else{
                //Unset any session so that no one interfere in billing logic
                if(isset($this->session->userdata['Package_Customer'])){
                    $this->session->unset_userdata('Package_Customer');
                }
                if(isset($this->session->userdata['package_cart'])){
                    $this->session->unset_userdata('package_cart');
                }
                if(isset($this->session->userdata['payment'])){
                    $this->session->unset_userdata('payment');
                }
                if(isset($this->session->userdata['package_payment'])){
                    $this->session->unset_userdata('package_payment');
                }
                //
                $data = $this->GetDataForCashier("expenses");
                $data['expense_types'] = $this->GetExpensesTypes($this->session->userdata['logged_in']['business_outlet_id']);
                $data['expenses_summary'] = $this->GetExpensesSummary($this->session->userdata['logged_in']['business_outlet_id']); 
                $data['all_expenses'] = $this->AllExpenses($this->session->userdata['logged_in']['business_outlet_id']);    
                $data['cashier'] = array('employee_name' => $this->session->userdata['logged_in']['employee_name']);
                $datam=array(
                    'outlet_id'=>$this->session->userdata['logged_in']['business_outlet_id']
                );
                $data['employees']=$this->BusinessAdminModel->GetBusinessAdminEmployeesC($datam);
                $data['employees']=$data['employees']['res_arr'];
                $data['vendors']=$this->BusinessAdminModel->GetVendorDetails($datam);
                $data['vendors']=$data['vendors']['res_arr'];
                // $this->PrettyPrintArray($data['all_expenses']);
                // exit;
                $m = $this->uri->segment(3);
                if(isset($m))
                {
                    $data['modal']=1;
                }
                else{
                    $data['modal']=0;
                }
                $this->load->view('cashier/cashier_expenses_view',$data);
            }
        }
        else{
            $this->LogoutUrl(base_url()."Cashier/");
        }   
    }


	private function GetExpensesTypes($outlet_id){
		if($this->IsLoggedIn('cashier')){
			$where = array(
				'expense_type_business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id'],
				'expense_type_business_outlet_id' => $outlet_id
			);

			$data = $this->BusinessAdminModel->MultiWhereSelect('mss_expense_types',$where);
			if($data['success'] == 'true'){	
				return $data['res_arr'];
			}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}		
	}

	private function GetExpensesSummary($outlet_id){
		if($this->IsLoggedIn('cashier')){
			$where = array(
				'expense_type_business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id'],
				'expense_type_business_outlet_id' => $outlet_id
			);

			$data = $this->CashierModel->GetTopExpensesSummary($where);
			if($data['success'] == 'true'){	
				return $data['res_arr'];
			}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}		
	}

	private function AllExpenses($outlet_id){
		if($this->IsLoggedIn('cashier')){
			$where = array(
				'expense_type_business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id'],
				'expense_type_business_outlet_id' => $outlet_id
			);

			$data = $this->CashierModel->GetAllExpenses($where);
			
			if($data['success'] == 'true'){	
				return $data['res_arr'];
			}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}		
	}

	public function ExpensesSummaryRange(){
		if($this->IsLoggedIn('cashier')){
			if(isset($_GET) && !empty($_GET)){
				$where = array(
					'expense_type_business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id'],
					'expense_type_business_outlet_id' => $this->session->userdata['logged_in']['business_outlet_id'],
					'from_date' => $_GET['from_date'],
					'to_date'   => $_GET['to_date']
				);

				$data = $this->CashierModel->GetExpensesSummaryRange($where);
				if($data['success'] == 'true'){	
					header("Content-type: application/json");
					print(json_encode($data['res_arr'], JSON_PRETTY_PRINT));
					die;	
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}		
	}

	public function TopExpensesSummaryRange(){
		if($this->IsLoggedIn('cashier')){
			if(isset($_GET) && !empty($_GET)){
				$where = array(
					'expense_type_business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id'],
					'expense_type_business_outlet_id' => $this->session->userdata['logged_in']['business_outlet_id'],
					'date_from' => $_GET['from_date'],
					'date_to'   => $_GET['to_date']
				);

				$data = $this->CashierModel->GetTopExpensesSummaryRange($where);
				if($data['success'] == 'true'){	
					header("Content-type: application/json");
					print(json_encode($data['res_arr'], JSON_PRETTY_PRINT));
					die;	
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}		
	}
	
	//Add packages function
	public function BuyPackages (){
        if($this->IsLoggedIn('cashier')){       
                $data = $this->GetDataForCashier("Buy Packages");           
                $data['salon_packages'] = $this->AdminActivePackages();
                $data['sidebar_collapsed'] = "true";      
                $customer_id = $this->uri->segment(3);
                if($customer_id != ''){
                    $sess_data = $this->GetCustomerBilling($customer_id);
                    if(!isset($this->session->userdata['Package_Customer'])){
                        $this->session->set_userdata('Package_Customer', $sess_data);
                    }
                    else{
                        $this->session->unset_userdata('Package_Customer');
                        $this->session->set_userdata('Package_Customer', $sess_data);
                    }
                }
                // $this->PrettyPrintArray($customer_id);      
                if(isset($this->session->userdata['Package_Customer']) || isset($customer_id)){
                        $data['Package_Customer'] = $this->session->userdata['Package_Customer'];
                        if(isset($this->session->userdata['package_payment'])){
                                $data['package_payment'] = $this->session->userdata['package_payment'][''.$this->session->userdata['Package_Customer']['customer_id'].''];
                        }
                }
                if(isset($this->session->userdata['package_cart'])){
                        $data['package_cart'] = $this->session->userdata['package_cart'];
                }
                $data['expert']=$this->GetExperts();
                $data['loyalty']=$this->CashierModel->GetConversionRatio($this->session->userdata['logged_in']['business_admin_id']);
                $data['loyalty']=$data['loyalty']['res_arr'];
                $data['sidebar_collapsed'] = "true";
                //
                // $data['loyalty_offer'] = $this->CashierModel->GetOffers($this->session->userdata['logged_in']['business_outlet_id'],'mss_loyalty_offer_integrated');
                // $data['loyalty_offer'] = $data['loyalty_offer']['res_arr'];
                // $this->PrettyPrintArray($data);
                // exit;
                $this->load->view('cashier/cashier_packages_view',$data);
        }
        else{
                $this->LogoutUrl(base_url()."Cashier/");
        }   
    }

	//Add packages function
// 	public function BuyPackages (){
// 		if($this->IsLoggedIn('cashier')){		
// 			$data = $this->GetDataForCashier("Buy Packages");			
// 			$data['salon_packages'] = $this->AdminActivePackages();
// 			$data['sidebar_collapsed'] = "true";			
// 			if(isset($this->session->userdata['Package_Customer'])){
// 				$data['Package_Customer'] = $this->session->userdata['Package_Customer'];
// 				if(isset($this->session->userdata['package_payment'])){
// 					$data['package_payment'] = $this->session->userdata['package_payment'][''.$this->session->userdata['Package_Customer']['customer_id'].''];
// 				}
// 			}
// 			if(isset($this->session->userdata['package_cart'])){
// 				$data['package_cart'] = $this->session->userdata['package_cart'];
// 			}
// 			// $this->PrettyPrintArray($data);
// 			// exit;
// 			$this->load->view('cashier/cashier_packages_view',$data);
// 		}
// 		else{
// 			$this->LogoutUrl(base_url()."Cashier/");
// 		}	
// 	}

	public function BuyPackage(){
		if($this->IsLoggedIn('cashier')){	
			if(isset($this->session->userdata['Package_Customer'])){
 				$customer_id = $this->session->userdata['Package_Customer']['customer_id'];
 				//Now Add the Respective Item to the cart

 			}
 			else{
 				$this->ReturnJsonArray(false,true,"Please add customer for further billing!");
 			}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}		
	}

	private function AdminActivePackages(){
		if($this->IsLoggedIn('cashier')){
			
			$where = array(
				'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id'],
				'business_outlet_id' => $this->session->userdata['logged_in']['business_outlet_id'],
				'is_active' => TRUE
			);

			$data = $this->CashierModel->MultiWhereSelect('mss_salon_packages',$where);
			
			if($data['success'] == 'true'){	
				return $data['res_arr'];
			}else{
				return "No Active Packages Found.";
			}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}		
	}

	public function ActivePackages (){
		if($this->IsLoggedIn('cashier')){	
			//Unset any session so that no one interfere in billing logic
			if(isset($this->session->userdata['Package_Customer'])){
				$this->session->unset_userdata('Package_Customer');
			}

			if(isset($this->session->userdata['package_cart'])){
			 $this->session->unset_userdata('package_cart');
		 }

		 if(isset($this->session->userdata['payment'])){
				$this->session->unset_userdata('payment');
			}

			if(isset($this->session->userdata['package_payment'])){
				$this->session->unset_userdata('package_payment');
			}
		 //
		 $where = array(
			'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id'],
			'business_outlet_id' => $this->session->userdata['logged_in']['business_outlet_id'],
			'is_active' => TRUE
		);
			$data = $this->GetDataForCashier("Active Package");	
			$data['activePackages']=$this->CashierModel->ActivePackage($where['business_admin_id'],$where['business_outlet_id']);
			$data['activePackages']=$data['activePackages']['res_arr'];
		
			$data['sidebar_collapsed'] = "true";
      $this->load->view('cashier/cashier_active_packages_view', $data);
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}	
	}

	//Package History
	public function PackagesHistory (){
		if($this->IsLoggedIn('cashier')){		
			$data = $this->GetDataForCashier("Packages History");
			$d = strtotime("-1 Months");
			$backdate=date("Y-m-d", $d);
			$where = array(
				'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id'],
				'business_outlet_id' => $this->session->userdata['logged_in']['business_outlet_id'],
				'is_active' => TRUE,
				'redemption_date'=>$backdate			
			);
			$data['redeemedPackages'] = $this->CashierModel->GetPackageRedemptionDetails($where);
			$data['redeemedPackages']=$data['redeemedPackages']['res_arr'];
			$data['sidebar_collapsed'] = "true";
			$this->load->view('cashier/cashier_packages_history_view',$data);	
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}	
	}

	public function GetPackage(){
		if($this->IsLoggedIn('cashier')){
			if(isset($_GET) && !empty($_GET)){
				$data = $this->CashierModel->GetPackageDetails($_GET['salon_package_id']);
				if($data['success'] == 'true'){	
					header("Content-type: application/json");
					print(json_encode($data['res_arr'], JSON_PRETTY_PRINT));
					die;	
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}		
	}

	public function GetPackageWallet(){
		if($this->IsLoggedIn('cashier')){
			if(isset($_GET) && !empty($_GET)){
				$data = $this->CashierModel->DetailsById($_GET['salon_package_id'],'mss_salon_packages','salon_package_id');
				if($data['success'] == 'true'){	
					header("Content-type: application/json");
					print(json_encode($data['res_arr'], JSON_PRETTY_PRINT));
					die;	
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}		
	}
	public function GetBilledServices(){
		if($this->IsLoggedIn('cashier')){
			if(isset($_GET) && !empty($_GET)){
				$data = $this->CashierModel->GetBilledServicesByTxnId($_GET['txn_id']);
				if($data['success'] == 'true'){	
					header("Content-type: application/json");
					print(json_encode($data['res_arr'], JSON_PRETTY_PRINT));
					die;	
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}		
	}

	public function GetBilledPackages(){
		if($this->IsLoggedIn('cashier')){
			if(isset($_GET) && !empty($_GET)){
				$data = $this->CashierModel->GetBilledPackagesByTxnId($_GET['txn_id']);
				if($data['success'] == 'true'){	
					header("Content-type: application/json");
					print(json_encode($data['res_arr'], JSON_PRETTY_PRINT));
					die;	
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}		
	}

	public function ClearPendingAmount(){
		if($this->IsLoggedIn('cashier')){
			if(isset($_POST) && !empty($_POST)){
				$this->form_validation->set_rules('balance_left', 'Balance Left', 'trim|required|is_natural');
				$this->form_validation->set_rules('due_amount', 'Due Amount', 'trim|required|is_natural');
				$this->form_validation->set_rules('remove_from_dashboard', 'Want to Remove', 'trim|required');
				$this->form_validation->set_rules('amount_paid_now', 'Amount Paid', 'trim|required|is_natural');

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
						"customer_pending_amount" => (int)$this->input->post('due_amount') - (int)$this->input->post('amount_paid_now') ,
						"customer_id" => $this->input->post('customer_id')
					);

					$data_track = array(
						"customer_id" => $this->input->post('customer_id'),
						"pending_amount_submitted" => (int)$this->input->post('amount_paid_now'),
						"pending_amount_outstanding" => (int)$this->input->post('due_amount') - (int)$this->input->post('amount_paid_now'),
						'payment_type'=>$this->input->post('payment_type')
					);
					
					$result = $this->CashierModel->Update($data,'mss_customers','customer_id');

					$result_track = $this->CashierModel->Insert($data_track,'mss_pending_amount_tracker');

					$remove = $this->input->post('remove_from_dashboard');
					if($remove == "Yes"){
						if($result['success'] == 'true'){
							$customer_id = $data["customer_id"];
							$curr_sess_cust_data = $this->session->userdata['POS'];
							
							$key = array_search($customer_id, array_column($curr_sess_cust_data,'customer_id'));
							unset($curr_sess_cust_data[$key]);
							if($key==0){
								array_splice($curr_sess_cust_data, 0, $key);
							}								 
							$this->session->set_userdata('POS', $curr_sess_cust_data);
							
							$this->ReturnJsonArray(true,false,"Pending Amount changed successfully!");
							die;
	          }
	          elseif($result['error'] == 'true'){
	          	$this->ReturnJsonArray(false,true,"DB Error!");
							die;
	          }
					}
					else{
						if($result['success'] == 'true'){
							$customer_id = $data["customer_id"];
							$curr_sess_cust_data = $this->session->userdata['POS'];
							
							$key = array_search($customer_id, array_column($curr_sess_cust_data,'customer_id'));
							unset($curr_sess_cust_data[$key]);	
							array_splice($curr_sess_cust_data, $key, $key); 
							$sess_data = $this->GetCustomerBilling($customer_id);
							$sess_data['is_package_customer'] = $this->IsPackageCustomer($customer_id);
							array_push($curr_sess_cust_data, $sess_data);
							$this->session->set_userdata('POS', $curr_sess_cust_data);
							
							$this->ReturnJsonArray(true,false,"Pending Amount changed successfully!");
							die;
	          }
	          elseif($result['error'] == 'true'){
	          	$this->ReturnJsonArray(false,true,"DB Error!");
							die;
	          }
					}
				}
		  }
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}
	}

    	public function DoPackageTransaction(){
		if($this->IsLoggedIn('cashier')){		
				if(isset($_POST) && !empty($_POST)){
						$_POST['txn_data']+=['package_txn_expert'=>$_POST['cart_data']['employee_id']];
						$data = $this->CashierModel->DetailsById($this->session->userdata['logged_in']['business_admin_id'],'mss_business_outlets','business_outlet_id');
						$data=$data['res_arr'];
						// $this->PrettyPrintArray($_POST);
						// exit;
						$result = $this->CashierModel->BillingPackageTransaction($_POST,$this->session->userdata['logged_in']['business_outlet_id'],$this->session->userdata['logged_in']['business_admin_id']);
						if($result['success'] == 'true'){
								
								//send sms
								// $this->SendPackageSms($_POST['txn_data']['sender_id'],$_POST['txn_data']['api_key'],$_POST['customer_pending_data']['customer_mobile'],$_POST['cart_data']['package_name'],$_POST['cart_data']['customer_name'],$_POST['cart_data']['package_validity']);

								if(isset($_POST['cart_data']['donar_name']) && isset($_POST['cart_data']['donar_mob'])){
										$this->SendPackageDonarSms($_POST['txn_data']['sender_id'],$_POST['txn_data']['api_key'],$_POST['customer_pending_data']['customer_mobile'],$_POST['cart_data']['package_name'],$_POST['cart_data']['customer_name'],$_POST['cart_data']['package_validity'],$_POST['cart_data']['donar_name'],$_POST['cart_data']['package_final_value'],$data['business_outlet_name'],$data['business_outlet_address'],$_POST['cart_data']['donar_mob'],$data['business_outlet_google_my_business_url']);
										$this->SendPackageRecipentSms($_POST['txn_data']['sender_id'],$_POST['txn_data']['api_key'],$_POST['customer_pending_data']['customer_mobile'],$_POST['cart_data']['package_name'],$_POST['cart_data']['customer_name'],$_POST['cart_data']['package_validity'],$_POST['cart_data']['donar_name'],$_POST['cart_data']['package_final_value'],$data['business_outlet_name'],$data['business_outlet_address'],$data['business_outlet_mobile'],$data['business_outlet_google_my_business_url']);
								}
								else{
										$this->SendPackageSms($_POST['txn_data']['sender_id'],$_POST['txn_data']['api_key'],$_POST['customer_pending_data']['customer_mobile'],$_POST['cart_data']['package_name'],$_POST['cart_data']['customer_name'],$_POST['cart_data']['package_validity'],$data['business_outlet_google_my_business_url']);
								}   
								
								//1.Unset the payment session
								if(isset($this->session->userdata['package_payment'])){
										$this->session->unset_userdata('payment');
								}
			
								// 2.Then unset the cart session
								if(isset($this->session->userdata['package_cart'])){
										$this->session->unset_userdata('package_cart');
								}
								//3.Then unset the customer session
								if(isset($this->session->userdata['Package_Customer'])){
										$this->session->unset_userdata('Package_Customer');
								}
			/*CODE*/
								$this->ReturnJsonArray(true,false,"Transaction is successful!");
								die;
						}
						elseif ($result['error'] == 'true') {
								$this->ReturnJsonArray(false,true,$result['message']);
								die;
						}
				}
		}
		else{
				$this->LogoutUrl(base_url()."Cashier/");
		}
}
	// BuyPackage sms
	public function SendPackageSms($sender_id,$api_key,$mobile,$package_name,$customer_name,$package_validity,$google_url){
		if($this->IsLoggedIn('cashier')){
		
			//API key & sender ID
			// $apikey = "ll2C18W9s0qtY7jIac5UUQ";
			// $apisender = "BILLIT";
			$msg = "Dear ".$customer_name.", Thanks for Visiting. You've bought ".$package_name." Package Valid for  ".$package_validity." months. Look forward to serve you again! Review us on ".$google_url." to serve you better.";
			 $msg = rawurlencode($msg);   //This for encode your message content                 		
			 
			 // API 
			$url = 'https://www.smsgatewayhub.com/api/mt/SendSMS?APIKey='.$api_key.'&senderid='.$sender_id.'&channel=2&DCS=0&flashsms=0&number='.$mobile.'&text='.$msg.'&route=1';
										
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch,CURLOPT_POST,1);
			curl_setopt($ch,CURLOPT_POSTFIELDS,"");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,2);
			
			$data = curl_exec($ch);
			return json_encode($data);
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}		
	}	

	//
	public function SendPackageRecipentSms($sender_id,$api_key,$mobile,$package_name,$customer_name,$package_validity,$donar_name,$package_final_value,$business_outlet_name,$business_outlet_address,$business_outlet_mobile,$google_url){
		if($this->IsLoggedIn('cashier')){
		
				//API key & sender ID
				// $apikey = "ll2C18W9s0qtY7jIac5UUQ";
				// $apisender = "BILLIT";
				$msg = "Dear ".$customer_name.", You heve been gifted a ".$package_name." Package at ".$business_outlet_name." by ".$donar_name." The Package is Valid for  ".$package_validity." months. Call or visit ".$business_outlet_name." ".$business_outlet_address." today & enjoy your wellness journey. Team ".$business_outlet_name." ".$business_outlet_mobile." Review us on ".$google_url." to serve you better.";
				 $msg = rawurlencode($msg);   //This for encode your message content                        
				 
				 // API 
				$url = 'https://www.smsgatewayhub.com/api/mt/SendSMS?APIKey='.$api_key.'&senderid='.$sender_id.'&channel=2&DCS=0&flashsms=0&number='.$mobile.'&text='.$msg.'&route=1';
																		
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch,CURLOPT_POST,1);
				curl_setopt($ch,CURLOPT_POSTFIELDS,"");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER,2);
				
				$data = curl_exec($ch);
				return json_encode($data);
		}
		else{
				$this->LogoutUrl(base_url()."Cashier/");
		}       
	}

	public function SendPackageDonarSms($sender_id,$api_key,$mobile,$package_name,$customer_name,$package_validity,$donar_name,$package_final_value,$business_outlet_name,$business_outlet_address,$donar_mobile,$google_url){
			if($this->IsLoggedIn('cashier')){
			
					//API key & sender ID
					// $apikey = "ll2C18W9s0qtY7jIac5UUQ";
					// $apisender = "BILLIT";
					$msg = "Dear ".$donar_name.", Your gift ".$package_name." Worth Rs ".$package_final_value." at ".$business_outlet_name." , ".$business_outlet_address." has been activated for ".$customer_name." The Package is valid for ".$package_validity." months. Look forward to your continued patronage.Review us on ".$google_url." to serve you better.";
						$msg = rawurlencode($msg);   //This for encode your message content                        
						
						// API 
					$url = 'https://www.smsgatewayhub.com/api/mt/SendSMS?APIKey='.$api_key.'&senderid='.$sender_id.'&channel=2&DCS=0&flashsms=0&number='.$donar_mobile.'&text='.$msg.'&route=1';
																			
					$ch = curl_init($url);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					curl_setopt($ch,CURLOPT_POST,1);
					curl_setopt($ch,CURLOPT_POSTFIELDS,"");
					curl_setopt($ch, CURLOPT_RETURNTRANSFER,2);
					
					$data = curl_exec($ch);
					return json_encode($data);
			}
			else{
					$this->LogoutUrl(base_url()."Cashier/");
			}       
	}
	

	public function SwapWithExistingCustomer(){
		if($this->IsLoggedIn('cashier')){
			if(isset($_POST) && !empty($_POST)){
				$customer_mobile =  $this->input->post('customer_mobile');
				$customer_id     =  $this->input->post('customer_id');			

				$where = array(
					'customer_business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id'],
					'customer_business_outlet_id' => $this->session->userdata['logged_in']['business_outlet_id'],
					'customer_mobile'             => $customer_mobile
				);
				
				$customerExists = $this->CashierModel->CheckCustomerExists($where);
				
				//If a customer is there
				if($customerExists['success'] == 'false' && $customerExists['error'] == 'true' ){
					//Update the POS and Cart data

					/************************************************************************/
					
						$sess_data = $this->CashierModel->MultiWhereSingleSelect('mss_customers',$where);
						if($sess_data['success'] == 'true'){
							$customer_profile = $sess_data['res_arr'];
							$customer_profile['is_package_customer'] = $this->IsPackageCustomer($customer_profile['customer_id']);

							$curr_sess_cust_data = $this->session->userdata['POS'];
					
							$key = array_search($customer_id, array_column($curr_sess_cust_data,'customer_id'));
							unset($curr_sess_cust_data[$key]);	
							array_splice($curr_sess_cust_data, $key, $key);
							
							array_push($curr_sess_cust_data, $customer_profile);
							$this->session->set_userdata('POS', $curr_sess_cust_data);

							if(isset($this->session->userdata['cart'])){	
								$curr_sess_cart_data = $this->session->userdata['cart'];

								if(isset($curr_sess_cart_data[strval($customer_id)]) || !empty($curr_sess_cart_data[strval($customer_id)])){								
									
									$old_cust_cart_data = $curr_sess_cart_data[strval($customer_id)];
									
									unset($curr_sess_cart_data[strval($customer_id)]);	
								
									$curr_sess_cart_data[strval($customer_profile['customer_id'])] = $old_cust_cart_data;
									$this->session->set_userdata('cart', $curr_sess_cart_data);
								}
							}
							
							$this->ReturnJsonArray(true,false,"Changed back to existing customer!");
							die;
						}	
						else{
							//Some duplication found in the tables
							$this->ReturnJsonArray(false,true,$sess_data['message']);
							die;
						}
					/***********************************************************************/
				}
				else{
					$this->ReturnJsonArray(false,true,"You need to create customer first!");
					die;	
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}
	}

	//package transaction sms
	public function SendPackageTransactionSms($sender_id,$api_key,$mobile,$package_name,$count,$customer_name,$service_count){
		if($this->IsLoggedIn('cashier')){
		$bill_url = $this->session->userdata('bill_url');
			//API key & sender ID
			// $apikey = "ll2C18W9s0qtY7jIac5UUQ";
			// $apisender = "BILLIT";
			
			$msg = "Dear ".$customer_name." You've redeemed ".$count." Services from ".$package_name.". Remaining count: ".$service_count.". And Please find the invoice on ".$bill_url;
			 $msg = rawurlencode($msg);   //This for encode your message content                 		
			 
			 // API 
			$url = 'https://www.smsgatewayhub.com/api/mt/SendSMS?APIKey='.$api_key.'&senderid='.$sender_id.'&channel=2&DCS=0&flashsms=0&number='.$mobile.'&text='.$msg.'&route=1';
										
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch,CURLOPT_POST,1);
			curl_setopt($ch,CURLOPT_POSTFIELDS,"");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,2);
			
			$data = curl_exec($ch);
			return json_encode($data);
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}				
	}

	//Appointment Module start

	private function GetAllAppointments(){
		if($this->IsLoggedIn('cashier')){
			$where = array(
				'business_admin_id' => $this->session->userdata['logged_in']['business_admin_id'],
				'business_outlet_id'=> $this->session->userdata['logged_in']['business_outlet_id'],
				'appointment_date'=>date('Y-m-01')
			);
			$data = $this->CashierModel->GetAllAppointments($where);
			if($data['success'] == 'true'){	
				return $data['res_arr'];
			}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}
	}

	public function Appointment(){
        if($this->IsLoggedIn('cashier')){
            $data = $this->GetDataForCashier("Appointment");            
            $data['experts'] = $this->GetExperts(); 
            $data['categories'] = $this->GetCategories($this->session->userdata['logged_in']['business_outlet_id']);
            $data['appointments'] = $this->GetAllAppointments();
            $data['appointments_today'] = array();
            $data['clndr_aptmnt_data'] = array();
            foreach ($data['appointments'] as $appointment) {
                if($appointment['appointment_date'] >= date('Y-m-01')){
                    if($appointment['appointment_date'] > date('Y-m-d')){
						$color='#009900';
					}elseif($appointment['appointment_date'] < date('Y-m-d')){
						$color='#ff0000';
					}else{
						$color='#ff8000';
					}
                    $temp = array(
                        'appointment_id' => $appointment['appointment_id'],
                        'appointment_status' => $appointment['appointment_status'],
                        'expert_id'      => $appointment['employee_id'],
                        'expert_name'    => $appointment['employee_first_name'],
                        'customer_mobile'=> $appointment['customer_mobile'],
                        'customer_id'        => $appointment['customer_id'],
                        'start_time'     => $appointment['appointment_start_time'],
                        'end_time'       => $appointment['appointment_end_time'],
                        'date'           => $appointment['appointment_date'],
                        'allDay'         => false,
                        'title'          => $appointment['customer_name'],
                        'start'          => $appointment['appointment_date']."T".$appointment['appointment_start_time'],
                        'end'            => $appointment['appointment_date']."T".$appointment['appointment_end_time'],
                        'description'    => $appointment['service_name'],
                        'category_name'     =>$appointment['category_name'],
                        'sub_category_name'=>$appointment['sub_category_name'],
                        'service_id'     => $appointment['service_id'],
                        'remarks'        => $appointment['remarks'],
                        'eventColor'	 =>	$color
                    );
                    array_push($data['clndr_aptmnt_data'],$temp);
                    $temp = array();
                    if($appointment['appointment_date'] == date('Y-m-d')){
                        array_push($data['appointments_today'], $appointment);
                    }
                }
            }
            $m = $this->uri->segment(3);
            if(isset($m))
            {
                $data['modal']=1;
            }
            else{
                $data['modal']=0;
            }
            $where = array(
				'business_outlet_id'=>$this->session->userdata['logged_in']['business_outlet_id']
			);
			$data['facebook'] = $this->CashierModel->MultiWhereSelect('mss_appointment_integration',$where);
			if(array_search('res_arr',$data['facebook'])==false)
			    $data['facebook'] += ['res_arr'=>''];
            // $this->PrettyPrintArray($data['modal']);
            $data['facebook'] = $data['facebook']['res_arr'];
            $data['sidebar_collapsed'] = "true";
      $this->load->view('cashier/cashier_appointments_view', $data);
        // $this->PrettyPrintArray($data);
        }
        else{
            $data['title'] = "Login";
            $this->load->view('cashier/cashier_login_view',$data);
        }
    }
	
	// Add Appointment
	public function AddAppointment(){
		if($this->IsLoggedIn('cashier')){
			if(isset($_POST) && !empty($_POST)){
				$this->form_validation->set_rules('customer_mobile', 'Customer Mobile', 'trim|required|max_length[10]|min_length[10]');
				$this->form_validation->set_rules('customer_appointment_date', 'Appointment Date', 'trim');
				$this->form_validation->set_rules('appointment_start_time', 'Appointment Start Time', 'required|trim');
				$this->form_validation->set_rules('appointment_end_time', 'Appointment End Time', 'required|trim');
				$this->form_validation->set_rules('expert_id', 'Expert Name', 'required');
				
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

					$services = $this->input->post('service_id');
					if(!empty($services)){						
						//1. check whether customer exists by mobile
						$where = array(
							'customer_business_admin_id' => $this->session->userdata['logged_in']['business_admin_id'],
							'customer_business_outlet_id' => $this->session->userdata['logged_in']['business_outlet_id'],
							'customer_master_admin_id' => $this->session->userdata['logged_in']['master_admin_id'],
							'customer_mobile'  => $this->input->post('customer_mobile')
						);
					
						$customerExists = $this->CashierModel->CheckCustomerExists($where);
						if($customerExists['success'] == 'true' && $customerExists['error'] == 'false' ){
							//
							$data = array(
								'customer_name' 							=> $this->input->post('customer_name'),
								'customer_mobile' 						=> $this->input->post('customer_mobile'),
								'customer_title' 							=> $this->input->post('customer_title'),
								'customer_master_admin_id' 	=> $this->session->userdata['logged_in']['master_admin_id'],
								'customer_business_admin_id' 	=> $this->session->userdata['logged_in']['business_admin_id'],
								'customer_business_outlet_id' => $this->session->userdata['logged_in']['business_outlet_id'],
								'customer_segment'						=>"new",
								'customer_virtual_wallet'			=>"0",
								'customer_pending_amount'			=>"0"
							);
							$result=$this->CashierModel->insert($data,'mss_customers');
							$customer_details = $this->CashierModel->MultiWhereSingleSelect('mss_customers',$where);
							$customer_details = $customer_details['res_arr'];
							if($result['success']=='true'){
								$data = array(
									'customer_id' 		=> $result['res_arr']['insert_id'],
									'appointment_date' => $this->input->post('appointment_date'),
									'appointment_start_time' => $this->input->post('appointment_start_time'),
									'appointment_end_time' => $this->input->post('appointment_end_time'),
									'appointment_mode' => $this->input->post('appointment_mode'),
									'remarks'					=>$this->input->post('remarks')
								);
								$services=$this->input->post('service_id');
								$result = $this->CashierModel->AddAppointmentModel($data,$services,$this->input->post('expert_id'));
						
								if($result['success'] == 'true'){
									
								    $this->SendAppointmentSms($_POST['sender_id'],$_POST['api_key'],$customer_details['customer_mobile'],$customer_details['customer_name'],$_POST['business_outlet_name'],$data['appointment_date'],$data['appointment_start_time']);
									
										$this->ReturnJsonArray(true,false,"Appointment added successfully!");
										die;
									}
									elseif($result['error'] == 'true'){
										$this->ReturnJsonArray(false,true,$result['message']);
										die;
									}
									}else{
										$this->ReturnJsonArray(false,true,"Error in Adding new Customer !");
										die;
									}
							
							//
							// $this->ReturnJsonArray(false,true,'Customer do not exists!');
							// die;
						}	
						else{
							$customer_details = $this->CashierModel->MultiWhereSingleSelect('mss_customers',$where);
							$customer_details = $customer_details['res_arr'];


							//2. Check appointment already exists
							$where = array(
								'customer_id' 					 			=> $customer_details['customer_id'],
								'appointment_date'  					=> $this->input->post('appointment_date'),
								'appointment_start_time'  		=> $this->input->post('appointment_start_time'),
								'appointment_status'  				=> 1
							);

				
							$appointmentExists = $this->CashierModel->CheckAppointmentExists($where);


							if($appointmentExists['success'] == 'true'){
								
								//3. Check Expert Available or not
								$where = array(
									'expert_id'                   => $this->input->post('expert_id'),
									'appointment_date'  					=> $this->input->post('appointment_date'),
									'appointment_start_time'  		=> $this->input->post('appointment_start_time'),
									'appointment_status'  				=> 1
								);

								$expertAvailable = $this->CashierModel->CheckExpertAvailable($where);

								if($expertAvailable['success'] == 'false'){

									$data = array(
										'customer_id' 		=> $customer_details['customer_id'],
										'appointment_date' => $this->input->post('appointment_date'),
										'appointment_start_time' => $this->input->post('appointment_start_time'),
										'appointment_end_time' => $this->input->post('appointment_end_time'),
										'appointment_mode' => $this->input->post('appointment_mode'),
										'remarks'					=>$this->input->post('remarks')
									);

									$result = $this->CashierModel->AddAppointmentModel($data,$services,$this->input->post('expert_id'));
							
									if($result['success'] == 'true'){
									
									$this->SendAppointmentSms($_POST['sender_id'],$_POST['api_key'],$customer_details['customer_mobile'],$customer_details['customer_name'],$_POST['business_outlet_name'],$data['appointment_date'],$data['appointment_start_time']);
								    
										
										$this->ReturnJsonArray(true,false,"Appointment added successfully!");
										die;
									}
									elseif($result['error'] == 'true'){
										$this->ReturnJsonArray(false,true,$result['message']);
										die;
									}
								}
								else{
									$this->ReturnJsonArray(false,true,$expertAvailable['message']);
									die;
								}
							}
							else{
								//Appointment exists
								$this->ReturnJsonArray(false,true,$appointmentExists['message']);
								die;
							}
						}
					}
					else{
						$this->ReturnJsonArray(false,true,"Please fill atleast one service!");
						die;
					}
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}
	
	public function GetAppointments(){
		if($this->IsLoggedIn('cashier')){
			$data = $this->CashierModel->GetAllAppointments();
				if($data['success'] == 'true'){	
					$this->ReturnJsonArray(true,false,$data['res_arr']);
					die;
				}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}
	}
	//Update Appointment
	public function UpdateAppointment(){
		if($this->IsLoggedIn('cashier')){
			if(isset($_POST) && !empty($_POST)){
				$this->form_validation->set_rules('appointment_start_time', 'Appointment Time', 'trim|required');
				$this->form_validation->set_rules('appointment_end_time', 'Appointment End Time', 'trim|required');
				$this->form_validation->set_rules('appointment_date', 'Appointment Date', 'trim|required|max_length[10]');
				$this->form_validation->set_rules('service_id', 'Service Id', 'trim|required|max_length[100]');
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
				}else{
							$data = array(
								'appointment_id'     => $this->input->post('appointment_id'),
								'customer_id'     => $this->input->post('customer_id'),
								'appointment_date' 	=> $this->input->post('appointment_date'),
								'appointment_start_time' 	=> $this->input->post('appointment_start_time'),
								'appointment_end_time' 	=> $this->input->post('appointment_end_time'),
								'expert_id'    => $this->input->post('expert_id'),
								'service_id' 		=> $this->input->post('service_id'),
								'appointment_status' 		=> 1,
								'remarks'					=>$this->input->post('remarks')
							);
						
							$result = $this->CashierModel->UpdateAppointmentModel($data);
							if($result['success'] == 'true'){

								//Send Upadate Appointment SMS
								$this->SendUpdateAppointmentSms($_POST['customer_mobile'],$_POST['business_outlet_name'],$_POST['customer_name'],$_POST['appointment_date'],$_POST['appointment_start_time']);

								$this->ReturnJsonArray(true,false,"Appointment updated successfully!");
								die;
							}else{
								$this->ReturnJsonArray(false,true,$result['message']);
								die;
							}
				}
			}else{
				$this->ReturnJsonArray(false,true,"Invalid Method");
				die;
			}
		}else{
			$this->LogoutUrl(base_url()."Cashier/Login/");
		}
		
	}
	//Cancel Appointment
	public function CancelAppointment(){
		if($this->IsLoggedIn('cashier')){		
			if(isset($_POST) && !empty($_POST)){
				$this->form_validation->set_rules('appointment_id', 'Appointment Id', 'trim|required');
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
				}else{
						$data = array(
							'appointment_id'     => $this->input->post('appointment_id'),
							'appointment_status' 		=> 0
						);
						$result = $this->CashierModel->Update($data,'mss_appointments','appointment_id');
						
						if($result['success'] == 'true'){
							//Send Upadate Appointment SMS
							$this->SendCancelAppointmentSms($_POST['sender_id'],$_POST['api_key'],$_POST['customer_mobile'],$_POST['business_outlet_name'],$_POST['customer_name'],$_POST['appointment_date'],$_POST['appointment_time']);

							$this->ReturnJsonArray(true,false,"Appointment Cancelled.");
							die;
						}elseif($result['error'] == 'true'){
							$this->ReturnJsonArray(false,true,$result['message']);
							die;
						}else{
						}
					}
				}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/Login/");
		}
	}

	//
	public function GetAppointmentServices(){
		if($this->IsLoggedIn('cashier')){
			$data = $this->CashierModel->DetailsById($_GET['service_id'],'mss_services','service_id');
				if($data['success'] == 'true'){	
					$this->ReturnJsonArray(true,false,$data['res_arr']);
					die;
				}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}
	}
	//Send Appointment SMS
	public function SendAppointmentSms($sender_id,$api_key,$mobile,$customer_name,$outlet,$date,$time){
		if($this->IsLoggedIn('cashier')){
			//API key & sender ID
			// $apikey = "ll2C18W9s0qtY7jIac5UUQ";
			// $apisender = "BILLIT";
			$msg = "Dear ".$customer_name.", you have made an appointment @".$outlet." on ".$date." at ".$time." hrs. Looking forward to offer our best services to you.";
			 $msg = rawurlencode($msg);   //This for encode your message content                 		
			 
			 // API 
			$url = 'https://www.smsgatewayhub.com/api/mt/SendSMS?APIKey='.$api_key.'&senderid='.$sender_id.'&channel=2&DCS=0&flashsms=0&number='.$mobile.'&text='.$msg.'&route=1';
										
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch,CURLOPT_POST,1);
			curl_setopt($ch,CURLOPT_POSTFIELDS,"");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,2);
			
			$data = curl_exec($ch);
			return json_encode($data);
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}		
	}

	//Send Update Appointment SMS
	public function SendUpdateAppointmentSms($mobile,$outlet_name,$customer_name,$date,$time){
		if($this->IsLoggedIn('cashier')){
		
			//API key & sender ID
			$apikey = "ll2C18W9s0qtY7jIac5UUQ";
			$apisender = "BILLIT";
			$msg = "Dear ".$customer_name.", your updated appointment is at ".$outlet_name.". We look forward to see you on ".$date." at".$time." hrs.";
			 $msg = rawurlencode($msg);   //This for encode your message content                 		
			 
			 // API 
			$url = 'https://www.smsgatewayhub.com/api/mt/SendSMS?APIKey='.$apikey.'&senderid='.$apisender.'&channel=2&DCS=0&flashsms=0&number='.$mobile.'&text='.$msg.'&route=1';
										
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch,CURLOPT_POST,1);
			curl_setopt($ch,CURLOPT_POSTFIELDS,"");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,2);
			
			$data = curl_exec($ch);
			return json_encode($data);
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}		
	}
	//Send Update Appointment SMS
	public function SendCancelAppointmentSms($sender_id,$api_key,$mobile,$outlet_name,$customer_name,$date,$time){
		if($this->IsLoggedIn('cashier')){
		
			//API key & sender ID
			// $apikey = "ll2C18W9s0qtY7jIac5UUQ";
			// $apisender = "BILLIT";
			$msg = "Dear ".$customer_name.", your have cancelled your appointment @ ".$outlet_name." on ".$date." at ".$time." hrs. We will miss you, Let us know when we can serve you again.";
				$msg = rawurlencode($msg);   //This for encode your message content                 		
				
				// API 
			$url = 'https://www.smsgatewayhub.com/api/mt/SendSMS?APIKey='.$api_key.'&senderid='.$sender_id.'&channel=2&DCS=0&flashsms=0&number='.$mobile.'&text='.$msg.'&route=1';
										
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch,CURLOPT_POST,1);
			curl_setopt($ch,CURLOPT_POSTFIELDS,"");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,2);
			
			$data = curl_exec($ch);
			return json_encode($data);
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}		
	}
	public function SendReminderSms(){
		//Send Reminder SMS
		
		$this->SendReminder($_POST['sender_id'],$_POST['api_key'],$_POST['customer_mobile'],$_POST['business_outlet_name'],$_POST['customer_name'],$_POST['appointment_date'],$_POST['appointment_start_time']);
		$this->ReturnJsonArray(true,false,"Message sent successfully");
		die;

	}
	//Send Update Appointment SMS
	public function SendReminder($sender_id,$api_key,$mobile,$outlet_name,$customer_name,$date,$time){
		if($this->IsLoggedIn('cashier')){
		
			//API key & sender ID
			// $apikey = "ll2C18W9s0qtY7jIac5UUQ";
			// $apisender = "BILLIT";
			$msg = "Dear ".$customer_name.", your have an upcoming appointment at ".$outlet_name." on ".$date." at ".$time." hrs.";
				$msg = rawurlencode($msg);   //This for encode your message content                 		
				
				// API 
			$url = 'https://www.smsgatewayhub.com/api/mt/SendSMS?APIKey='.$api_key.'&senderid='.$sender_id.'&channel=2&DCS=0&flashsms=0&number='.$mobile.'&text='.$msg.'&route=1';
										
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch,CURLOPT_POST,1);
			curl_setopt($ch,CURLOPT_POSTFIELDS,"");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,2);
			
			$data = curl_exec($ch);
			return json_encode($data);
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}		
	}


	public function AddToCartFromAppointment(){
		if($this->IsLoggedIn('cashier')){
			if(isset($_POST) && !empty($_POST)){
				
				$this->form_validation->set_rules('appointment_id','Appointment Id','trim|required');
				
				if ($this->form_validation->run() == FALSE) {
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
						'appointment_id' => $this->input->post('appointment_id')
					);
					
					$result = $this->CashierModel->GetAppointment($data['appointment_id']);
				
					if($result['success'] == 'true'){

						//Right now I am asserting that there is only one service present for the 
						//appointment. If the number of services increase more than one then it might
						//misbehave

						$sess_data = $this->GetCustomerBilling($result['res_arr']['customer_id']);
						$sess_data['is_package_customer'] = $this->IsPackageCustomer($result['res_arr']['customer_id']);


						//1. Create a customer card
						$curr_sess_cust_data = array();
						if(!isset($this->session->userdata['POS'])){
							array_push($curr_sess_cust_data, $sess_data);
							$this->session->set_userdata('POS', $curr_sess_cust_data);
						}
						else{
							$curr_sess_cust_data = $this->session->userdata['POS'];
							array_push($curr_sess_cust_data, $sess_data);
							$this->session->set_userdata('POS', $curr_sess_cust_data);
						}

						//2.After Creating customer card, Add services in to his cart
						$data = array(
							'customer_id'								 =>$result['res_arr']['customer_id'],
							'service_id'  							 =>$result['res_arr']['service_id'],
							'service_name'							 =>$result['res_arr']['service_name'],
							'service_total_value'				 =>ceil($result['res_arr']['service_price_inr'] + ($result['res_arr']['service_price_inr'] * $result['res_arr']['service_gst_percentage'])/100),
							'service_quantity'					 => 1,
							'service_discount_percentage'=> 0,
							'service_discount_absolute'  => 0,
							'service_expert_id'          =>$result['res_arr']['expert_id'],
							'service_price_inr'          =>ceil($result['res_arr']['service_price_inr'] + ($result['res_arr']['service_price_inr'] * $result['res_arr']['service_gst_percentage'])/100),
							'service_gst_percentage'     =>$result['res_arr']['service_gst_percentage'],
							'service_add_on_price'			=> 0,
							'service_est_time'  				 =>$result['res_arr']['service_est_time'],
							'customer_package_profile_id' => -999 //Short code for no package redemption
						);

			
						//Adding the cart product to particular customer's cart
						$curr_sess_cart_data = array();
						$sess_data = array($data);
						if(!isset($this->session->userdata['cart'])){
							$curr_sess_cart_data  += ["".$result['res_arr']['customer_id']."" => $sess_data];
							$this->session->set_userdata('cart', $curr_sess_cart_data);
						}
						else{
							$curr_sess_cart_data = $this->session->userdata['cart'];

							if(isset($curr_sess_cart_data["".$result['res_arr']['customer_id'].""]) || !empty($curr_sess_cart_data["".$result['res_arr']['customer_id'].""])){
								
								array_push($curr_sess_cart_data["".$result['res_arr']['customer_id'].""], $data);
								$this->session->set_userdata('cart', $curr_sess_cart_data);
							}
							else{
								$curr_sess_cart_data  += ["".$result['res_arr']['customer_id']."" => $sess_data];
								$this->session->set_userdata('cart', $curr_sess_cart_data);
							}
						}
				
						//
						$this->ReturnJsonArray(true,false,"");
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
			$this->LogoutUrl(base_url()."Cashier/Login/");
		}
	}

	//Appointment Module end
//Get Filtered Data for Redeemed Packages
	public function FilterRedemption (){
		if($this->IsLoggedIn('cashier')){	
			if(isset($_POST) && !empty($_POST)){
				$this->form_validation->set_rules('from_date', 'From Date', 'trim|required');
				$this->form_validation->set_rules('to_date', 'To Date', 'trim|required');
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
				}else{
					$data = array(
						'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id'],
						'business_outlet_id' => $this->session->userdata['logged_in']['business_outlet_id'],
						'is_active' => TRUE,
						'from_date' 	=> $this->input->post('from_date'),
						'to_date' 	=> $this->input->post('to_date')
					);
				
					$result = $this->CashierModel->GetRedemptionFilter($data);
				
					if($result['success'] == 'true'){
						return $this->ReturnJsonArray(true,false,$result['res_arr']);						
						die;
					}else{
						$this->ReturnJsonArray(false,true,$result['message']);
						die;
					}
				}
			}else{
				$this->ReturnJsonArray(false,true,"Invalid Method");
				die;
			}
			
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}	
	}
	//Loyalty
	public function Loyalty(){
		if($this->IsLoggedIn('cashier')){
			
			$data = $this->GetDataForCashier("Loyalty");			
			$data['experts'] = $this->GetExperts();	
			$where=array('business_admin_id'=>$this->session->userdata['logged_in']['business_admin_id']);
			$data['cashback']= $this->CashierModel->Cashback_Customer($where);
			$data['cashback']=$data['cashback']['res_arr'];
			if(array_search("Marks360",$data["business_admin_packages"]))
            {
                $rules = $this->BusinessAdminModel->RuleDetailsById($data['cashier_details']['business_outlet_id'],'mss_loyalty_rules','business_outlet_id');
                $data['rules'] = $rules['res_arr'];
            }
            else
            {
                $data['rules'] = ['res_arr'=>''];
                $data['rules'] = $data['rules']['res_arr'];
            }
			$data['sidebar_collapsed'] = "true";
      $this->load->view('cashier/cashier_loyalty_view', $data);
		}
		else{
			$data['title'] = "Login";
			$this->load->view('cashier/cashier_login_view',$data);
		}
	}
	//
	public function AddCustomerDataInTable(){	
		if($this->IsLoggedIn('cashier')){
			if(isset($_GET) && !empty($_GET)){
				$where=array('customer_id'=>$_GET['customer_id'],
											'business_admin_id'=>$this->session->userdata['logged_in']['business_admin_id']);				
										
				$data = $this->CashierModel->Cashback_Customer_Data_By_Id($where);
			
				if($data['success'] == 'true'){	
					header("Content-type: application/json");
					print(json_encode($data['res_arr'], JSON_PRETTY_PRINT));
					die;	
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/Login/");
		}
	}


	//Video
	public function Video(){
		if($this->IsLoggedIn('cashier')){
			
			$data = $this->GetDataForCashier("Video");			
			$data['experts'] = $this->GetExperts();	
			$data['sidebar_collapsed'] = "true";
      $this->load->view('cashier/cashier_demo_video_view', $data);
		}
		else{
			$data['title'] = "Login";
			$this->load->view('cashier/cashier_login_view',$data);
		}
	}

	
	//Attendance
	// public function Attendance(){
	// 	if($this->IsLoggedIn('cashier')){
			
	// 		$data = $this->GetDataForCashier("Attendance");			
	// 		$data['experts'] = $this->GetExperts();	
	// 		$data['sidebar_collapsed'] = "true";
		
  //     $this->load->view('cashier/cashier_attendance_view', $data);
	// 	}
	// 	else{
	// 		$data['title'] = "Login";
	// 		$this->load->view('cashier/cashier_login_view',$data);
	// 	}
	// }
	//Attendance
	// 	public function AddInTime(){
		
	// 		if($this->IsLoggedIn('cashier')){
	// 			if(isset($_POST) && !empty($_POST)){
	// 				$this->form_validation->set_rules('attendance_date', 'Attendance Date', 'trim|required');
	// 				$this->form_validation->set_rules('in_time', 'In Time', 'trim');
	// 				if ($this->form_validation->run() == FALSE) 
	// 				{
	// 					$data = array(
	// 									'success' => 'false',
	// 									'error'   => 'true',
	// 									'message' =>  validation_errors()
	// 									);
	// 					header("Content-type: application/json");
	// 					print(json_encode($data, JSON_PRETTY_PRINT));
	// 					die;
	// 				}else{
	// 							$data = array(
	// 								'attendance_date' 		=> $this->input->post('attendance_date'),
	// 								'in_time' 						=> $this->input->post('in_time'),
	// 								'employee_id'					=> $this->input->post('employee_id'),
	// 								'employee_outlet_id'	=>$this->session->userdata['logged_in']['business_outlet_id'],
	// 								'employee_business_admin_id'=>$this->session->userdata['logged_in']['business_admin_id']
	// 							);
	// 							$result=$this->CashierModel->insert($data,'mss_emss_attendance');
	// 							if($result['success']=='true'){
	// 								return $this->ReturnJsonArray(true,false,'Attendnce added successfully');						
	// 								die;
	// 							}else{
	// 								return $this->ReturnJsonArray(true,false,'Databas Error');						
	// 								die;
	// 							}
	// 					}
	// 			}
	// 		else{
	// 			$this->LogoutUrl(base_url()."BusinessAdmin/");
	// 		}
	// 	}
	// }

	//Add Out Time
	// public function AddOutTime(){
	// 	if($this->IsLoggedIn('cashier')){
	// 		if(isset($_POST) && !empty($_POST)){
	// 			$this->form_validation->set_rules('attendance_date', 'Attendance Date', 'trim|required');
	// 			$this->form_validation->set_rules('out_time', 'Out Time', 'trim');
	// 			if ($this->form_validation->run() == FALSE) 
	// 			{
	// 				$data = array(
	// 								'success' => 'false',
	// 								'error'   => 'true',
	// 								'message' =>  validation_errors()
	// 								);
	// 				header("Content-type: application/json");
	// 				print(json_encode($data, JSON_PRETTY_PRINT));
	// 				die;
	// 			}else{
	// 						$data = array(
	// 							'attendance_date' 		=> $this->input->post('attendance_date'),
	// 							'out_time' 						=> $this->input->post('out_time'),
	// 							'employee_id'					=> $this->input->post('employee_id'),
	// 							'employee_outlet_id'	=>$this->session->userdata['logged_in']['business_outlet_id'],
	// 							'employee_business_admin_id'=>$this->session->userdata['logged_in']['business_admin_id']
	// 						);
	// 						$result=$this->CashierModel->UpdateAttendance($data);
	// 						if($result['success']=='true'){
	// 							return $this->ReturnJsonArray(true,false,'Attendnce Recorded successfully');						
	// 							die;
	// 						}else{
	// 							return $this->ReturnJsonArray(true,false,'Databas Error');						
	// 							die;
	// 						}
	// 				}
	// 		}
	// 	else{
	// 		$this->LogoutUrl(base_url()."BusinessAdmin/");
	// 	}
	// }
// }
    public function EditExpertInCart(){
	if($this->IsLoggedIn('cashier')){
					// $this->PrettyPrintArray($this->session->userdata['cart']);
					if(isset($_POST) && !empty($_POST)){
							$this->form_validation->set_rules('service_expert_id', 'Service Expert Name', 'trim|required');
							$this->form_validation->set_rules('service_id', 'Service Name', 'trim|required');
							$this->form_validation->set_rules('customer_package_profile_id', 'Package ID', 'trim|required');
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
									if(isset($this->session->userdata['payment'])){
											$this->session->unset_userdata('payment');
									}
									if(!isset($this->session->userdata['cart']) || empty($this->session->userdata['cart'])){
											$this->ReturnJsonArray(false,true,"Cart is empty!");
											die;
									}
									else{
											$overall_cart_data = $this->session->userdata['cart'];
											$key = array_search($this->input->post('service_id'), array_column($overall_cart_data[strval($this->input->post('customer_id'))], 'service_id'));
											$this->session->userdata['cart'][$this->input->post('customer_id')][$key]['service_expert_id'] = $this->input->post('service_expert_id');
											$this->ReturnJsonArray(true,false,"Your Cart updated successfully!");
											die;
									}   
							}
							
			}
	}
	else{
			$this->LogoutUrl(base_url()."Cashier/");
	}
}
//jitesh
	//Loyalty Redeem
public function AddToCartRedeemPoints(){
    if($this->IsLoggedIn('cashier')){
      // print_r($_POST);
      // exit;
      if(isset($_POST) && !empty($_POST)){
        $this->form_validation->set_rules('customer_id', 'Customer Name', 'trim|required');
        $this->form_validation->set_rules('offer_id', 'Offer', 'trim|required');
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
      //    print_r($_POST);
      // exit;
          if(isset($this->session->userdata['payment'])){
            $this->session->unset_userdata('payment');
           }
          $offer_id  = $this->input->post('offer_id');
          //  echo $offer_id;
          //  exit;
          $fetch_offers = $this->CashierModel->DetailsById($offer_id,'mss_loyalty_offer_integrated','offer_id');
          // print_r($fetch_offers);
          // exit;
          if($fetch_offers['success'] == 'true')
          {
            $fetch_offer  = $fetch_offers['res_arr'];
            $customer_id = $this->input->post('customer_id');
            // echo $customer_id;
            // exit;
            $fetch_customer = $this->CashierModel->DetailsById($customer_id,'mss_customers','customer_id');
            // print_r($fetch_offer['points']);
            // exit;
            $points_redeemed = $fetch_customer['res_arr']['customer_rewards'] - $fetch_offer['points'];
            // print_r($points_redeemed);
            // exit;
            if($fetch_customer['success'] == 'true')
            {
              $fetch_customer = $fetch_customer['res_arr'];
              $cust_mobile = $fetch_customer;
              $data = array(
                'customer_id' => $customer_id,
                'customer_rewards' => $points_redeemed
              );
              $redeem_points = $this->CashierModel->Update($data,'mss_customers','customer_id');
              // print_r($fetch_offer);
              // exit;
            //   $otp_genrate=array();
            //   $otp_genrate = $this->GenerateOtp();
            //   print_r($otp_genrate);
            //   exit;
        //      if($otp_genrate['success'] == 'true')
        //      {
                // print_r($otp_genrate);
                // exit;
                // $this->session->set_userdata('customer_details',$customer_id);
                // $this->session->set_userdata('offer_details',$fetch_offer);
                // $this->ReturnJsonArray(true,false,"OTP SENT SUCCESSFULLY!",$otp_genrate);
                // die;
              // }
              
            }
            // else
            // {
            //  $this->ReturnJsonArray(false,true,"Invalid Mobile No!");
            //  die;
            // }
            $service_details = $this->CashierModel->DetailsById($fetch_offer['service_id'],'mss_services','service_id');
      $service_details = $service_details['res_arr'];
      $loyaltydata = array(
        'customer_id'                => $this->input->post('customer_id'),
        'service_id'                 => $fetch_offer['service_id'],
        'customer_rewards'           => $fetch_customer['customer_rewards'],
        'offer_id'                   =>$this->input->post('offer_id'),
        'employee_id'                =>$this->input->post('cashier_id'),
      );
      $insert_transcation = $this->CashierModel->Insert($loyaltydata,'loyalty_transcation_integrated');
      
      // print_r($service_details);
      // exit;
            $data = array(
              'customer_id'                => $this->input->post('customer_id'),
              'service_id'                 => $fetch_offer['service_id'],
              'service_name'               => $fetch_offer['offers'],
              'service_count'              => 1,
              'service_total_value'        => 0,
              'service_quantity'           => 1,
              'service_discount_percentage'=> 100,
              'service_discount_absolute'  => 0,
              'service_expert_id'          => $this->input->post('cashier_id'),
							'service_price_inr'          => $service_details['service_price_inr'],
							'service_add_on_price'				=>0,
              'service_gst_percentage'     => $service_details['service_gst_percentage'],
              'service_est_time'           => $service_details['service_est_time'],
              'customer_package_profile_id' => -9999
            );
    //Adding the cart product to particular customer's cart
    $curr_sess_cart_data = array();
    $sess_data = array($data);
    if(!isset($this->session->userdata['cart'])){
      $curr_sess_cart_data  += ["".$this->input->post('customer_id')."" => $sess_data];
      $this->session->set_userdata('cart', $curr_sess_cart_data);
    }
    else{
      $curr_sess_cart_data = $this->session->userdata['cart'];
      if(isset($curr_sess_cart_data["".$this->input->post('customer_id').""]) || !empty($curr_sess_cart_data["".$this->input->post('customer_id').""])){
        
        array_push($curr_sess_cart_data["".$this->input->post('customer_id').""], $data);
        $this->session->set_userdata('cart', $curr_sess_cart_data);
      }
      else{
        $curr_sess_cart_data  += ["".$this->input->post('customer_id')."" => $sess_data];
        $this->session->set_userdata('cart', $curr_sess_cart_data);
      }
    }
    /********************************************************************/
    $this->ReturnJsonArray(true,false,"Your Cart updated successfully!");
    die;
          }
          else
          {
            $this->ReturnJsonArray(false,true,"Invalid Offer Selected!");
            die;
          }
        }
      }
      else{
        $this->ReturnJsonArray(false,true,"Not a valid request!");
        die;
      }   
    }
    else{
      $this->LogoutUrl(base_url()."Cashier/");
    }   
  }
  public function DeleteCartLoyaltyItem(){
    if($this->IsLoggedIn('cashier')){
      if(isset($_GET) && !empty($_GET)){
        $customer_id = $_GET['customer_id'];
        
        if(isset($this->session->userdata['package_payment'])){
          $this->session->unset_userdata('package_payment');
        }
        //Delete the cart product to particular customer's cart
        if(!isset($this->session->userdata['package_cart']) || empty($this->session->userdata['package_cart'])){
          $this->ReturnJsonArray(false,true,"Cart is empty!");
          die;
        }
        else{
          $overall_cart_data = $this->session->userdata['package_cart'];
          
          $this->session->unset_userdata('package_cart');
          $this->ReturnJsonArray(true,false,"Item deleted successfully!");
          die;
        }
      }
    }
    else{
      $this->LogoutUrl(base_url()."Cashier/");
    }
	}
	
	public function ResendOtp()
  {
    if($this->IsLoggedIn('cashier')){
      // $this->PrettyPrintArray($_POST);
      // exit;
      foreach($_POST as $key=>$value)
      {
        $mobileno = $key;
      }
      $otp = $this->session->userdata('otp');
      // $this->PrettyPrintArray($mobileno);
      // exit;
    }
    else{
      $data['title'] = "Login";
      $this->load->view('cashier/cashier_login_view',$data);
    }
  }
  public function ValidateIntegratedOtp()
  {
    if($this->IsLoggedIn('cashier'))
    {
      // $this->PrettyPrintArray($_POST);
      // exit;
      if(isset($_POST) && !empty($_POST))
      {
        $verify_otp = $_POST['confirm_otp'];
        // $this->PrettyPrintArray($_POST);
        // exit;
        $otp = $this->session->userdata('otp');
        // $this->PrettyPrintArray($otp);
        // exit;
        print_r($this->session->userdata);
          $service_details = $this->CashierModel->DetailsById($this->session->userdata('offer_details'),'mss_services','service_id');
        
          exit;
        if($verify_otp == $otp)
        {
          $this->session->unset_userdata('otp');
          $data = array(
            'success' => 'true',
            'error'   => 'false',
            'message' =>  'OTP Verified Successfully',
          );
          
         $data = array(
                   'customer_id'                 => $this->session->userdata('customer_details'),
                   'service_id'                => $service_details,
                   'service_name'              => $service_details,
                   'service_count'               => 1,
                   'service_total_value'         => 0,
                   'service_quantity'          => 1,
                   'service_discount_percentage'=> 0,
                   'service_discount_absolute'  => 0,
                  //  'service_expert_id'          => $this->input->post('service_expert_id'),
                   'service_price_inr'          => $this->input->post('service_price_inr'),
                   'service_gst_percentage'     => $this->input->post('service_gst_percentage'),
                   'service_est_time'  => $this->input->post('service_est_time'),
                 );
         //Adding the cart product to particular customer's cart
         $curr_sess_cart_data = array();
         $sess_data = array($data);
         if(!isset($this->session->userdata['cart'])){
           $curr_sess_cart_data  += ["".$this->input->post('customer_id')."" => $sess_data];
           $this->session->set_userdata('cart', $curr_sess_cart_data);
         }
         else{
           $curr_sess_cart_data = $this->session->userdata['cart'];
           if(isset($curr_sess_cart_data["".$this->input->post('customer_id').""]) || !empty($curr_sess_cart_data["".$this->input->post('customer_id').""])){
             
             array_push($curr_sess_cart_data["".$this->input->post('customer_id').""], $data);
             $this->session->set_userdata('cart', $curr_sess_cart_data);
           }
           else{
             $curr_sess_cart_data  += ["".$this->input->post('customer_id')."" => $sess_data];
             $this->session->set_userdata('cart', $curr_sess_cart_data);
           }
         }
         /********************************************************************/
         $this->ReturnJsonArray(true,false,"Your Cart updated successfully!");
         die;
          header("Content-type: application/json");
          print(json_encode($data, JSON_PRETTY_PRINT));
          die;
        }   
        else
        { 
          return $this->ReturnJsonArray(false,true,'Invalid OTP');            
          die;
        }     
      }
    }
    else
    {
      $data["title"]  = "Login";
      $this->load->view('cashier/cashier_login_view',$data);
    }
  }
  public function ValidateOtp()
  {
    if($this->IsLoggedIn('cashier'))
    {
      // $this->PrettyPrintArray($_POST);
      // exit;
      if(isset($_POST) && !empty($_POST))
      {
        $verify_otp = "" ;
        $otp_json = json_decode($_POST['otp_data'],true);
        foreach($otp_json as $key=>$value)
        {
          foreach($value as $k=>$v)
          {
            $verify_otp = $v;
          }
        }
        // $this->PrettyPrintArray($_POST);
        // exit;
        $otp = $this->session->userdata('otp');
        // $this->PrettyPrintArray($otp);
        // exit;
        if($otp == $verify_otp)
        {
          $this->session->unset_userdata('otp');
          $data = array(
            'success' => 'true',
            'error'   => 'false',
            'message' =>  'OTP Verified Successfully',
          );
          header("Content-type: application/json");
          print(json_encode($data, JSON_PRETTY_PRINT));
          die;
        }   
        else
        { 
          return $this->ReturnJsonArray(false,true,'Invalid OTP');            
          die;
        }     
      }
    }
    else
    {
      $data["title"]  = "Login";
      $this->load->view('cashier/cashier_login_view',$data);
    }
  }

	public function GenerateOtp(){
			if($this->IsLoggedIn('cashier')){
				$data['sidebar_collapsed'] = "true";
				$numbers = "0123456789";
				$otp_generation = substr(str_shuffle($numbers),0,6);
				$data['otp'] = $otp_generation;
				if(isset($this->session->userdata['otp']))
				{
					$this->session->unset_userdata('otp');
				}
				$this->session->set_userdata('otp',$otp_generation);
				// print(json_encode($data,JSON_PRETTY_PRINT));   
				return $this->ReturnJsonArray(true,false,$data);
				die;
			}
			else{
				$data['title'] = "Login";
				$this->load->view('cashier/cashier_login_view',$data);
			}
		}
		public function LoyaltyBillingStandAlone(){
			if($this->IsLoggedIn('cashier'))
			{
				// $this->PrettyPrintArray($_GET);
				// exit;
				
				// FORMDATA FOR REFERENCE
				//  [title] => Mr.
				// [customer_mobile] => 8545801188
				// [cust_dob] => 0000-00-00
				// [customer_name] => Ashok kumar
				// [customer_email] => 
				// [cust_doa] => 
				// [business_outlet_id] => 1
				// [employee_id] => 1
				// [cust_id] => 5045
				// [billing_amount] => 1000
				// [cashback] => 900
				// [final_amount] => 1000
				if(isset($_GET) && !empty($_GET) )
				{
					// if(empty($_GET['cust_id']))
					// {
						$this->form_validation->set_rules('customer_title', 'Title', 'trim|required');
						$this->form_validation->set_rules('customer_name', 'Customer Name', 'trim|required|max_length[100]');
						$this->form_validation->set_rules('customer_mobile', 'Customer Mobile', 'trim|required|max_length[10]|min_length[10]');
						$this->form_validation->set_rules('cashback', 'Customer date of birth', 'trim|required');
						// $this->form_validation->set_rules('customer_doa', 'Customer date of anniversary', 'trim');
						// if ($this->form_validation->run() == FALSE) 
						// {
						//  $this->PrettyPrintArray($_GET);
						//  $data = array(
						//          'success' => 'false',
						//          'error'   => 'true',
						//          'message' =>  validation_errors()
						//        );
						//  header("Content-type: application/json");
						//  print(json_encode($data, JSON_PRETTY_PRINT));
						//  die;
						// }  
					// }
					// else
					// {  
						// $this->PrettyPrintArray($_GET);
						if(empty($_GET['cust_id']))
						{
							$customer_id = 1;
						} 
						else
						{
							$customer_id = $_GET['cust_id'];
						}
							$data = array(
							
							'customer_id'     => $customer_id,
							'gross_amount'    => $this->input->get('billing_amount'),
							'cashback_amount'   => $this->input->get('cashback'),
							'net_amount'    => $this->input->get('final_amount'),
							'business_outlet_id'=> $this->input->get('business_outlet_id'),
							'employee_id'   => $this->input->get('employee_id')
							);
							$points_generated = $this->CashierModel->CheckRule($data,'mss_loyalty_rules','business_outlet_id');
							//  $this->PrettyPrintArray($points_generated);
							if($points_generated['success'] == true)
							{
								$rules = $this->BusinessAdminModel->RuleDetailsById($_GET['business_outlet_id'],'mss_loyalty_rules','business_outlet_id');
								if($rules['res_arr']['rule_type'] == 'Cashback Single Rule' || $rules['res_arr']['rule_type'] == 'Cashback Multiple Rule' || $rules['res_arr']['rule_type'] == 'Cashback LTV Rule')
								{
									$cashback_valid = $points_generated['res_arr']['cashback_validity'];
									$date = date("Y-m-d");
									$date = strtotime(date("Y-m-d",strtotime($date))."+".$cashback_valid);
									// $date->add(new DateInterval("P.$points_valid.m"));
									$data = array_merge($data,array('cashback_generated'=>$points_generated['res_arr']['cashback_generated'],'cashback_validity'=>date("Y-m-d",$date)));
									$transcation = $this->CashierModel->Insert($data,'mss_loyalty_transcation');
									if($transcation['success'] == true)
									{
										// print_r($data);
										// exit;
										$customer_details = $this->CashierModel->DetailsById($data['customer_id'],'mss_customers','customer_id');
										// print_r($customer_details);
										// exit;
													
										if($customer_details['success'] == true)
										{
											$points = $customer_details['res_arr']['customer_cashback'] +  $points_generated['res_arr']['cashback_generated'];
											// print_r($points);
											// exit;
											$data = array(
												'customer_id' => $data['customer_id'],
												'customer_cashback' => $points
											);
											$points_update = $this->CashierModel->Update($data,'mss_customers','customer_id');
											// $this->PrettyPrintArray($points_update);
											// exit;
											if($points_update['success'] == true)
											{
												// $this->session->unset_userdata('otp');
												$data = array(
													'success' => 'true',
													'error'   => 'false',
													'message' =>  'Transcation Successfull',
												);
												header("Content-type: application/json");
												print(json_encode($data, JSON_PRETTY_PRINT));
												die;
											}
										}
										else
										{
											return $this->ReturnJsonArray(false,true,'DB error');           
											die;
										}
								}
								else if($rules['res_arr']['rule_type'] != 'Cashback Visits')
								{
									$points_valid = $points_generated['res_arr']['points_validity'];
									$date = date("Y-m-d");
									$date = strtotime(date("Y-m-d",strtotime($date))."+".$points_valid);
									// $date->add(new DateInterval("P.$points_valid.m"));
									$data = array_merge($data,array('points_generated'=>$points_generated['res_arr']['points_generated'],'points_validity'=>date("Y-m-d",$date)));
									//  print_r($data);
									//  exit;
									$transcation = $this->CashierModel->Insert($data,'mss_loyalty_transcation');
									if($transcation['success'] == true)
									{
										// print_r($data);
										// exit;
										$customer_details = $this->CashierModel->DetailsById($data['customer_id'],'mss_customers','customer_id');
										// print_r($customer_details);
										// exit;
													
										if($customer_details['success'] == true)
										{
											$points = $customer_details['res_arr']['customer_rewards'] +  $points_generated['res_arr']['points_generated'];
											// print_r($points);
											// exit;
											$data = array(
												'customer_id' => $data['customer_id'],
												'customer_rewards' => $points
											);
											$points_update = $this->CashierModel->Update($data,'mss_customers','customer_id');
											// $this->PrettyPrintArray($points_update);
											// exit;
											if($points_update['success'] == true)
											{
												// $this->session->unset_userdata('otp');
												$data = array(
													'success' => 'true',
													'error'   => 'false',
													'message' =>  'Transcation Successfull',
												);
												header("Content-type: application/json");
												print(json_encode($data, JSON_PRETTY_PRINT));
												die;
											}
										}
										else
										{
											return $this->ReturnJsonArray(false,true,'DB error');           
											die;
										}
									}
								}
								else
								{
									$data = array(
										'success' => 'true',
										'error'   => 'false',
										'message' =>  'Transcation Successfull',
									);
									header("Content-type: application/json");
									print(json_encode($data, JSON_PRETTY_PRINT));
									die;
								}
											
							}
							else if ($points_generated['success'] == false)
							{
							return $this->ReturnJsonArray(false,true,'DB error');
							die;
							}
						// }
					// }
						}
				}
				
			}
			else
			{
					$data["title"]  = "Login";
					$this->load->view('cashier/cashier_login_view',$data);
			}
	}
  public function Loyalty_Redemption(){
    if($this->IsLoggedIn('cashier')){
      $data = $this->GetDataForCashier("Loyalty");        
      $where=array('business_admin_id'=>$this->session->userdata['logged_in']['business_admin_id']);
      $data['cashback']= $this->CashierModel->Cashback_Customer($where);
      $data['cashback']=$data['cashback']['res_arr'];
      $data['sidebar_collapsed'] = "true";
      $rules = $this->BusinessAdminModel->RuleDetailsById($data['cashier_details']['business_outlet_id'],'mss_loyalty_rules','business_outlet_id');
      if($rules['res_arr']['rule_type'] == 'Cashback Single Rule' || $rules['res_arr']['rule_type'] == 'Cashback Multiple Rule' || $rules['res_arr']['rule_type'] == 'Cashback LTV Rule')
      {
        $data['loyalty_offer'] = ['res_arr'=>'']; 
      }
      else
      {
        $data['loyalty_offer']= $this->CashierModel->GetOffers($data['cashier_details']['business_outlet_id'],'mss_loyalty_offer');
      }
      
      // $this->PrettyPrintArray($data);
      // exit;
      $this->load->view('cashier/cashier_loyalty_redemption_view',$data);
    }
    else{
      $data['title'] = "Login";
      $this->load->view('cashier/cashier_login_view',$data);
    }
  }
	//Pritam Code for Emss Attendance	
	//Attendance
	public function Attendance(){		
		if($this->IsLoggedIn('cashier')){			
			$data = $this->GetDataForCashier("Attendance");			
			$data['experts'] = $this->GetExperts();	
			$data['attendance_today']=$this->CashierModel->CalculateAttendance();			
		
			$data['sidebar_collapsed'] = "true";
			// $this->PrettyPrintArray($data);
			// exit;
			$this->load->view('cashier/cashier_attendance_view', $data);	
		}
		else{
			$data['title'] = "Login";
			$this->load->view('cashier/cashier_login_view',$data);
		}
		

	}
	//Attendance
		public function AddInTime(){	
			// $this->PrettyPrintArray($_POST);
			// exit;	
			if($this->IsLoggedIn('cashier')){
				if(isset($_POST) && !empty($_POST)){
					$this->form_validation->set_rules('emp_id', 'Id not found', 'trim|required');
					// $this->form_validation->set_rules('in_time', 'In Time', 'trim');
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
					}else{
						
								$data = array(
									'attendance_date' 				=> date("Y/m/d"),
									'in_time' 						=> date('Y/m/d H:i:s'),
									'employee_id' 					=> $this->input->post('emp_id'),
									'employee_outlet_id'			=>$this->session->userdata['logged_in']['business_outlet_id'],
									'employee_business_admin_id'	=>$this->session->userdata['logged_in']['business_admin_id'],
									// 'status'=>1
								);
							
								$check['attendance']=$this->CashierModel->CheckAttendance($data);
								// $this->PrettyPrintArray($check);
								// exit;
								if($check['attendance']['success']=='true')
								{
									return $this->ReturnJsonArray(true,false,'Attendance Already exist.');						
									die;
								}else{
									$result=$this->CashierModel->insert($data,'mss_emss_attendance');
									if($result['success']=='true'){
										return $this->ReturnJsonArray(true,false,'Attendance added successfully');						
										die;
									}
									else{
										return $this->ReturnJsonArray(true,false,'Database Error');						
										die;
									}
								}

								// }
						}
				}
			else{
				$this->LogoutUrl(base_url()."BusinessAdmin/");
			}
		}
	}

	//Add Out Time
	public function AddOutTime(){
		if($this->IsLoggedIn('cashier')){
			if(isset($_POST) && !empty($_POST)){
				$this->form_validation->set_rules('emp_id', 'Id not found', 'trim|required');
				// $this->form_validation->set_rules('out_time', 'Out Time', 'trim');
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
				}else{
					// $check['in_time']=$this->CashierModel->CheckAttendanceOutTime($data);
					// $working_hours=$check['in_time']-date("h:i:s");
				
					
							$data = array(
								'attendance_date' 				=> date("Y/m/d"),
								'out_time' 						=> date('Y/m/d H:i:s'),
								'employee_id'					=> $this->input->post('emp_id'),
								'employee_outlet_id'			=>$this->session->userdata['logged_in']['business_outlet_id'],
								'employee_business_admin_id'	=>$this->session->userdata['logged_in']['business_admin_id']
							);
							$result=$this->CashierModel->UpdateAttendance($data);
							$check['in_time']=$this->CashierModel->CheckAttendanceOutTime($data);
							$work_hr=$this->CashierModel->GetWorkingHoursOutlet($data);
							// $this->PrettyPrintArray($work_hr);
							// exit;
							$working_hours=(strtotime($check['in_time'][0]['out_time']))-(strtotime($check['in_time'][0]['in_time']));
							$working_hours=(int)($working_hours/60);
							$hour=$working_hours/60;
							// echo $hour.'         '.$working_hours;
							// exit;
							// $work_hr=strtotime($working_hours);
							// (int)$hour=date('H',$working_hours);

							
							
							// (int)$min=date('i',$working_hours);
							// (int)$sec=date('s',$working_hours);
							// $temp_time = (strtotime("09:00:00"));
							 
							// echo (int)$work_hr;
							$work_hr=(int)($work_hr[0]['working_hours']);
							// $work_hr=$work_hr*60;
							// $this->PrettyPrintArray($work_hr);
							// exit;
							
							// $over_time=$hour-$work_hr;
							// $this->PrettyPrintArray($hour);
							// $this->PrettyPrintArray($working_hours);
							// exit;
							if($hour > $work_hr or $hour == $work_hr)
							{
								$over_time=($working_hours)-(int)($work_hr*60);
								
							
							$data = array(
								
								'working_hours'					=>$working_hours,
								'over_time'						=>$over_time,
								'employee_id'					=> $this->input->post('emp_id'),
								'employee_outlet_id'			=>$this->session->userdata['logged_in']['business_outlet_id'],
								'employee_business_admin_id'	=>$this->session->userdata['logged_in']['business_admin_id']
								
							);
							// $this->PrettyPrintArray($data);
							// exit;

							$result=$this->CashierModel->UpdateWorkingHours($data);
						}
						else{
							$data = array(
								
								'working_hours'					=>$working_hours,
								'over_time'						=>'0',
								'employee_id'					=> $this->input->post('emp_id'),
								'employee_outlet_id'			=>$this->session->userdata['logged_in']['business_outlet_id'],
								'employee_business_admin_id'	=>$this->session->userdata['logged_in']['business_admin_id']
								
							);
							$result=$this->CashierModel->UpdateWorkingHours($data);
						}
							

							
							
							if($result['success']=='true'){
								return $this->ReturnJsonArray(true,false,'Attendance Recorded successfully');						
								die;
							}else{
								return $this->ReturnJsonArray(true,false,'Database Error');						
								die;
							}
					}
			}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}
	}
}

	public function AttendanceReport(){
		if($this->IsLoggedIn('cashier')){
			$data = $this->GetDataForCashier("AttendanceReport");	
			
			
			
			$where = array(
				'employee_outlet_id'			=>$this->session->userdata['logged_in']['business_outlet_id'],
				'employee_business_admin_id'	=>$this->session->userdata['logged_in']['business_admin_id']
			);
			$data['collapsed']="true";
			if(!isset($_GET) || empty($_GET)){
				//Load the default view
				$this->load->view('cashier/cashier_attendance_report_view',$data);
			}
			else if(isset($_GET) && !empty($_GET)){
				
				$data = array(
					'report_name' 			 => $_GET['report_name'],
					'from_date' 				 => $_GET['from_date'],
					'to_date' 					 => $_GET['to_date'],
					'business_outlet_id' => $this->session->userdata['logged_in']['business_outlet_id'],
					'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id']
				);
			// 	$this->PrettyPrintArray($data);
			// exit;
				$result = $this->BusinessAdminModel->GenerateReports($data);
				if($result['success'] == 'true'){
				
					$data = array(
								'success' => 'true',
								'error'   => 'false',
								'message' => '',
								'result' =>  $result['res_arr']
					);
					// $this->PrettyPrintArray($data);
					// exit;
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
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}
	}
	//26/03/2020
	public function TxnHistory(){
        if($this->IsLoggedIn('cashier')){
            $data = $this->GetDataForCashier("Transaction History");
            $where = array(
                'outlet_id'         =>$this->session->userdata['logged_in']['business_outlet_id'],
                'business_admin_id' =>$this->session->userdata['logged_in']['business_admin_id']
            );
            $data['txnhistory']=$this->CashierModel->TxnHistory($where);
            if($data['txnhistory']['success'] == 'true')
            {
                $data['txnhistory']=$data['txnhistory']['res_arr'];
            }
            else
            {
                $data['txnhistory']+=['res_arr'=>''];
                $data['txnhistory']=$data['txnhistory']['res_arr'];
            }
            
            $data['txnhistorypackages']=$this->CashierModel->TxnHistoryPackages();
            if($data['txnhistorypackages']['success'] == 'true')
            {
                $data['txnhistorypackages']=$data['txnhistorypackages']['res_arr'];
            }
            else
            {
                $data['txnhistorypackages']+=['res_arr'=>''];
                $data['txnhistorypackages']=$data['txnhistorypackages']['res_arr'];
            }
            $data['services']=$this->CashierModel->TxnHistoryServices();
            if($data['services']['success'] == 'true')
            {
                $data['services']=$data['services']['res_arr'];
            }
            else
            {
                $data['services']+=['res_arr'=>''];
                $data['services']=$data['services']['res_arr'];
            }
            $data['products']=$this->CashierModel->TxnHistoryProduct();
            if($data['products']['success'] == 'true')
            {
                $data['products']=$data['products']['res_arr'];
            }
            else
            {
                $data['products']+=['res_arr'=>''];
                $data['products']=$data['products']['res_arr'];
            }
            // $this->PrettyPrintArray($data);
            // exit;    
            $this->load->view('cashier/cashier_transaction_history',$data);
        }   
        else{
            $this->LogoutUrl(base_url()."Cashier/");
        }
    }
	public function CustTransHistory(){
        if($this->IsLoggedIn('cashier')){
            $data = $this->GetDataForCashier("Transaction History");
            $where = array(
                'outlet_id'         =>$this->session->userdata['logged_in']['business_outlet_id'],
                'business_admin_id' =>$this->session->userdata['logged_in']['business_admin_id']
            );
            // $this->PrettyPrintArray($where);
            // exit;
            // if($result['success'] == 'true'){
            //  $data = array(
            //              'success' => 'true',
            //              'error'   => 'false',
            //              'message' => '',
            //              'result' =>  $result['res_arr']
            //  );
            //  header("Content-type: application/json");
            //  print(json_encode($data, JSON_PRETTY_PRINT));
            //  die;    
            // }
            // elseif($result['error'] == 'true'){
            // $data = array(
            //          'success' => 'false',
            //          'error'   => 'true',
            //          'message' =>  $result['message']
            //          );
            //          header("Content-type: application/json");
            //          print(json_encode($data, JSON_PRETTY_PRINT));
            //          die;    
            // }
        }
        else{
            $this->LogoutUrl(base_url()."Cashier/");
        }       
	}
	public function CustomerBirthDayAnniver(){
        if($this->IsLoggedIn('cashier')){
            $data = $this->GetDataForCashier("Transaction History");
            $where = array(
                'outlet_id'         =>$this->session->userdata['logged_in']['business_outlet_id'],
                'business_admin_id' =>$this->session->userdata['logged_in']['business_admin_id']
            );
            $data['txnhistory']=$this->CashierModel->TxnHistory($where);
            $data['txnhistory']=$data['txnhistory']['res_arr'];
            $data['txnhistorypackages']=$this->CashierModel->TxnHistoryPackages();
            $data['txnhistorypackages']=$data['txnhistorypackages']['res_arr'];
            $data['services']=$this->CashierModel->TxnHistoryServices();
            $data['services']=$data['services']['res_arr'];
            $data['products']=$this->CashierModel->TxnHistoryProduct();
            $data['products']=$data['products']['res_arr'];
            // $this->PrettyPrintArray($data['services']);
            // exit;    
            $this->load->view('cashier/cashier_bday_anniversary_view',$data);
        }   
        else{
            $this->LogoutUrl(base_url()."Cashier/");
        }
	}
	public function GetCustomerHistory(){
        if($this->IsLoggedIn('cashier')){
            // $this->PrettyPrintArray($_GET);
            // exit;
            $data = $this->GetDataForCashier("BirthDay & Anniversary");
            $month="2020-".$_GET['month']."-01";
            $where = array(
                'month' =>$month,
                'outlet_id'         =>$this->session->userdata['logged_in']['business_outlet_id'],
                'business_admin_id' =>$this->session->userdata['logged_in']['business_admin_id']
            );
            $result=$this->CashierModel->TxnBirthDay($where);
            
            if($result['success'] == 'true'){
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
            $this->LogoutUrl(base_url()."Cashier/");
        }
	}
	public function GetCustomerHistoryAnniversary(){
        if($this->IsLoggedIn('cashier')){
            
            $data = $this->GetDataForCashier("BirthDay & Anniversary");
            $month="2020-".$_GET['month']."-01";
            $where = array(
                'month' =>$month,
                'outlet_id'         =>$this->session->userdata['logged_in']['business_outlet_id'],
                'business_admin_id' =>$this->session->userdata['logged_in']['business_admin_id']
            );
            $result=$this->CashierModel->TxnAnniverDay($where);
            // $this->PrettyPrintArray($result);
            // exit;
            if($result['success'] == 'true'){
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
            $this->LogoutUrl(base_url()."Cashier/");
        }
	}
	public function SendSmsMessage($option,$sender_id,$api_key,$mobile,$name,$outlet_name,$oulet_location,$address){
        if($this->IsLoggedIn('cashier')){
            // $this->PrettyPrintArray($outlet_name);
            // exit;
            //API key & sender ID
            // $apikey = "ll2C18W9s0qtY7jIac5UUQ";
            // $apisender = "BILLIT";
            if($option == 'bday'){
                $msg = "Dear ".$name.", Wish you a very Happy B'day. Come over to ".$outlet_name.", ".$oulet_location.", & enjoy special B'day Offer exclusively for you. Team".$outlet_name." Address : ".$address;
                $msg = rawurlencode($msg);   //This for encode your message content                         
            }else{
                $msg = "Dear ".$name.", Wish you a very Happy Anniversary. Come over to ".$outlet_name.", ".$oulet_location.", & enjoy special Anniversary Offer exclusively for you. Team".$outlet_name." Address : ".$address;
                $msg = rawurlencode($msg);
            } 
             // API 
            $url = 'https://www.smsgatewayhub.com/api/mt/SendSMS?APIKey='.$api_key.'&senderid='.$sender_id.'&channel=2&DCS=0&flashsms=0&number='.$mobile.'&text='.$msg.'&route=1';
                                        
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch,CURLOPT_POST,1);
            curl_setopt($ch,CURLOPT_POSTFIELDS,"");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,2);
            
            $data = curl_exec($ch);
            return json_encode($data);
        }
        else{
            $this->LogoutUrl(base_url()."Cashier/");
        }               
    }
    public function SendMessage(){
        if($this->IsLoggedIn('cashier')){
            if(isset($_GET) && !empty($_GET)){
                        $result=$this->CashierModel->DetailsById($this->session->userdata['logged_in']['business_outlet_id'],'mss_business_outlets','business_outlet_id');
                        // $this->PrettyPrintArray($result);
                        $sender_id=$result['res_arr']['business_outlet_sender_id'];
                        $api_key=$result['res_arr']['api_key'];
                        $mobile=$_GET['customer_number'];
                        $name=$_GET['customer_name'];
                        $option=$_GET['option'];
                        
                        $this->SendSmsMessage($option,$sender_id,$api_key,$mobile,$name,$result['res_arr']['business_outlet_name'],$result['res_arr']['business_outlet_location'],$result['res_arr']['business_outlet_address']);
                    }
                    if($result['success'] == 'true'){
                        $this->ReturnJsonArray(true,false,"Message Sent Successfully");
                        die;
                    }
                    elseif ($result['error'] == 'true') {
                    $this->ReturnJsonArray(false,true,$result['message']);
                    die;
                }
            }
        else{
            $this->LogoutUrl(base_url()."Cashier/");
        }
    }
    public function ExpenseReport(){
        if($this->IsLoggedIn('cashier')){
                        $data = array(
                        'business_outlet_id' => $this->session->userdata['logged_in']['business_outlet_id'],
                        'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id']
                    );
                    if($_GET['group'] == 'range'){
                        $date=$_GET['from_date'];
                        $date=explode("-",$date);
                        // $this->PrettyPrintArray($date);
                        $data1=array(
                            'from_date' => $date[0],
                            'to_date' => $date[1],
                            'business_outlet_id' => $this->session->userdata['logged_in']['business_outlet_id'],
                            'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id']
                        );  
                        $result = $this->BusinessAdminModel->ExpenseReportRange($data1);
                    }
                    else if($_GET['group'] == '7days'){
                        $result = $this->BusinessAdminModel->ExpenseReport7Days($data);
                    }
                    else if($_GET['group'] == '30days'){
                        $result = $this->BusinessAdminModel->ExpenseReport30Days($data);
                    }
                    else if($_GET['group'] == 'mtd'){
                        $result = $this->BusinessAdminModel->ExpenseReportMTD($data);
                    }
                    else{
                        $result = $this->BusinessAdminModel->ExpenseReports($data);
                    }
                    // $this->PrettyPrintArray($result);
                    // exit;
                    if($result['success'] == 'true'){
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
            $this->LogoutUrl(base_url()."Cashier/");
        }
    }
    //11-04-2020
	public function GetProductData(){
        if($this->IsLoggedIn('cashier')){
            if(isset($_GET) && !empty($_GET)){
                // $this->PrettyPrintArray($_GET);
                $data = $this->CashierModel->SearchProduct($_GET['query'],$_GET['inventory_type'],$this->session->userdata['logged_in']['business_admin_id'],$this->session->userdata['logged_in']['business_outlet_id']);
                if($data['success'] == 'true'){ 
                    $this->ReturnJsonArray(true,false,$data['res_arr']);
                    die;
                }
            }
        }
        else{
            $this->LogoutUrl(base_url()."Cashier/");
        }
    }
    public function GetProductDetails(){
        if($this->IsLoggedIn('cashier')){
            if(isset($_GET) && !empty($_GET)){
                // $this->PrettyPrintArray($_GET);
                $data = $this->CashierModel->ProductDetails($_GET['product'],$this->session->userdata['logged_in']['business_admin_id'],$this->session->userdata['logged_in']['business_outlet_id']);
                // $this->PrettyPrintArray($data);
                if($data['success'] == 'true'){
                    $data1 = array(
                                'success' => 'true',
                                'error'   => 'false',
                                'message' => '',
                                'result' =>  $data['res_arr']
                    );
                    header("Content-type: application/json");
                    print(json_encode($data1, JSON_PRETTY_PRINT));
                    die;    
                }
            }
        }
        else{
            $this->LogoutUrl(base_url()."Cashier/");
        }
    }
    public function GetProductDetail(){
        if($this->IsLoggedIn('cashier')){
            if(isset($_GET) && !empty($_GET)){
                $data = $this->CashierModel->ProductDetails($_GET['product'],$this->session->userdata['logged_in']['business_admin_id'],$this->session->userdata['logged_in']['business_outlet_id']);
                // $this->PrettyPrintArray($data);
                if($data['success'] == 'true'){
                    $data1 = array(
                                'success' => 'true',
                                'error'   => 'false',
                                'message' => '',
                                'result' =>  $data['res_arr']
                    );
                    header("Content-type: application/json");
                    print(json_encode($data1, JSON_PRETTY_PRINT));
                    die;    
                }
            }
        }
        else{
            $this->LogoutUrl(base_url()."Cashier/");
        }
    }
    private function GetServices($outlet_id){
        if($this->IsLoggedIn('cashier')){
            $where = array(
                'category_business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id'],
                'service_is_active'           => TRUE,
                'category_business_outlet_id' => $outlet_id,
                'service_type'                => 'service'
            );
            $data = $this->BusinessAdminModel->Services($where);
            if($data['success'] == 'true'){ 
                return $data['res_arr'];
            }
        }
        else{
            $this->LogoutUrl(base_url()."Cashier/");
        }       
    }
    private function GetCategoriesOtc($outlet_id){
        if($this->IsLoggedIn('cashier')){
            $where = array(
                'category_business_admin_id' => $this->session->userdata['logged_in']['business_admin_id'],
                'category_is_active'         => TRUE,
                'category_business_outlet_id'=> $outlet_id,
                'category_type' => 'Products'
            );
            $data = $this->BusinessAdminModel->MultiWhereSelect('mss_categories',$where);
            if($data['success'] == 'true'){ 
                return $data['res_arr'];
            }
        }
        else{
            $this->LogoutUrl(base_url()."Cashier/");
        }       
    }
    public function GetCategoriesByInventory(){
        if($this->IsLoggedIn('cashier')){
            $type="";
            if($_GET['inventory_type'] == "Retail Product"){
                $type="Retail Products";
            }else{
                $type="Raw Material";
            }
            $where = array(
                'category_business_admin_id' => $this->session->userdata['logged_in']['business_admin_id'],
                'category_is_active'         => TRUE,
                'category_business_outlet_id'=> $this->session->userdata['logged_in']['business_outlet_id'],
                'category_type' => 'Products'
            );
            // $this->PrettyPrintArray($where);
            $data = $this->BusinessAdminModel->MultiWhereSelect('mss_categories',$where);
            // $this->PrettyPrintArray($data);
            if($data['success'] == 'true'){
                header("Content-type: application/json");
                print(json_encode($data, JSON_PRETTY_PRINT));
                die;    
            }
        }
        else{
            $this->LogoutUrl(base_url()."Cashier/");
        }       
    }
    //10 april
    public function GetServicesByName(){
        if($this->IsLoggedIn('cashier')){
            if(isset($_GET) && !empty($_GET)){
                $where = array(
                    'service_name'  => $_GET['service_name'],
                    'service_is_active'   => TRUE,
                    ''
                );
                $data = $this->CashierModel->ProductByName($where);
                // $this->PrettyPrintArray($data);
                header("Content-type: application/json");
                print(json_encode($data['res_arr'], JSON_PRETTY_PRINT));
                die;
            }
        }
        else{
            $this->LogoutUrl(base_url()."Cashier/");
        }
    }
    //15-04
    public function InventoryDownload(){
        if($this->IsLoggedIn('cashier')){
                $result = $this->CashierModel->InventoryReports();
                    if($result['success'] == 'true'){
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
            $this->LogoutUrl(base_url()."Cashier/");
        }
    }
    //17-04
    public function TxnHistoryByCustomerS(){    
        // $this->PrettyPrintArray($_GET);
        if($this->IsLoggedIn('cashier')){
            if(isset($_GET) && !empty($_GET)){
                $where=array(
                            'outlet_id' => $this->session->userdata['logged_in']['business_outlet_id'],
                            'business_admin_id'=>$this->session->userdata['logged_in']['business_admin_id'],
                            'master_admin_id'=>$this->session->userdata['logged_in']['master_admin_id']);
                $data = $this->CashierModel->TxnDetailsCustService($where,$_GET['query']);
                // $this->PrettyPrintArray($data);
                if($data['success'] == 'true'){ 
                    $this->ReturnJsonArray(true,false,$data['res_arr']);
                    die;
                }
            }
        }
        else{
            $this->LogoutUrl(base_url()."Cashier/Login/");
        }
    }
    public function AddDataInServiceTable(){    
        if($this->IsLoggedIn('cashier')){
            if(isset($_GET) && !empty($_GET)){
                $where=array('customer_no'=>$_GET['customer_no'],
                'outlet_id' => $this->session->userdata['logged_in']['business_outlet_id'],     
                'business_admin_id'=>$this->session->userdata['logged_in']['business_admin_id'],
                'master_admin_id'=>$this->session->userdata['logged_in']['master_admin_id']);
                
                $data['service'] = $this->CashierModel->GetCustServiceData($where);
                // $data['service']=$data['service']['res_arr'];
                $data['package'] = $this->CashierModel->GetCustPackagesData($where);
                // $data['package']=$data['package']['res_arr'];
                $data['pproduct'] = $this->CashierModel->GetCustServicePP($where);
                // $data['pproduct']=$data['pproduct']['res_arr'];
                $data['sservice'] = $this->CashierModel->GetCustServicePS($where);
                // if($data['success'] == 'true'){ 
                //     $data = array(
                //         'success' => 'true',
                //         'error'   => 'false',
                //         'message' => '',
                //         'result' =>  $data['res_arr']
                //         );
                        header("Content-type: application/json");
                        print(json_encode($data, JSON_PRETTY_PRINT));
                        die;        
                // }
            }
        }
        else{
            $this->LogoutUrl(base_url()."Cashier/Login/");
        }
    }
    // 18-04
    public function TxnHistoryByCustomerP(){    
        // $this->PrettyPrintArray($_GET);
        if($this->IsLoggedIn('cashier')){
            if(isset($_GET) && !empty($_GET)){
                $where=array(
                            'outlet_id' => $this->session->userdata['logged_in']['business_outlet_id'],
                            'business_admin_id'=>$this->session->userdata['logged_in']['business_admin_id'],
                            'master_admin_id'=>$this->session->userdata['logged_in']['master_admin_id']);
                $data = $this->CashierModel->TxnDetailsCustPackages($where,$_GET['query']);
                // $this->PrettyPrintArray($data);
                if($data['success'] == 'true'){ 
                    $this->ReturnJsonArray(true,false,$data['res_arr']);
                    die;
                }
            }
        }
        else{
            $this->LogoutUrl(base_url()."Cashier/Login/");
        }
    }
    public function AddDataInPackageTable(){    
        if($this->IsLoggedIn('cashier')){
            if(isset($_GET) && !empty($_GET)){
                $where=array('customer_no'=>$_GET['customer_no'],
                'outlet_id' => $this->session->userdata['logged_in']['business_outlet_id'],     
                'business_admin_id'=>$this->session->userdata['logged_in']['business_admin_id'],
                'master_admin_id'=>$this->session->userdata['logged_in']['master_admin_id']);
                $data = $this->CashierModel->GetCustPackagesData($where);
                // $this->PrettyPrintArray($data);
                if($data['success'] == 'true'){ 
                    $data = array(
                        'success' => 'true',
                        'error'   => 'false',
                        'message' => '',
                        'result' =>  $data['res_arr']
                        );
                        header("Content-type: application/json");
                        print(json_encode($data, JSON_PRETTY_PRINT));
                        die;        
                }
            }
        }
        else{
            $this->LogoutUrl(base_url()."Cashier/Login/");
        }
    }
    // preffered Services
    public function TxnHistoryByCustomerPS(){   
        // $this->PrettyPrintArray($_GET);
        if($this->IsLoggedIn('cashier')){
            if(isset($_GET) && !empty($_GET)){
                $where=array(
                            'outlet_id' => $this->session->userdata['logged_in']['business_outlet_id'],
                            'business_admin_id'=>$this->session->userdata['logged_in']['business_admin_id'],
                            'master_admin_id'=>$this->session->userdata['logged_in']['master_admin_id']);
                $data = $this->CashierModel->TxnDetailsCustPS($where,$_GET['query']);
                // $this->PrettyPrintArray($data);
                if($data['success'] == 'true'){ 
                    $this->ReturnJsonArray(true,false,$data['res_arr']);
                    die;
                }
            }
        }
        else{
            $this->LogoutUrl(base_url()."Cashier/Login/");
        }
    }
    public function AddDataINPrefferedServicesTable(){  
        if($this->IsLoggedIn('cashier')){
            if(isset($_GET) && !empty($_GET)){
                $where=array('customer_no'=>$_GET['customer_no'],
                'outlet_id' => $this->session->userdata['logged_in']['business_outlet_id'],     
                'business_admin_id'=>$this->session->userdata['logged_in']['business_admin_id'],
                'master_admin_id'=>$this->session->userdata['logged_in']['master_admin_id']);
                $data = $this->CashierModel->GetCustServicePS($where);
                // $this->PrettyPrintArray($data);
                if($data['success'] == 'true'){ 
                    $data = array(
                        'success' => 'true',
                        'error'   => 'false',
                        'message' => '',
                        'result' =>  $data['res_arr']
                        );
                        header("Content-type: application/json");
                        print(json_encode($data, JSON_PRETTY_PRINT));
                        die;        
                }
            }
        }
        else{
            $this->LogoutUrl(base_url()."Cashier/Login/");
        }
    }
    // preffered product
    public function TxnHistoryByCustomerPP(){   
        // $this->PrettyPrintArray($_GET);
        if($this->IsLoggedIn('cashier')){
            if(isset($_GET) && !empty($_GET)){
                $where=array(
                            'outlet_id' => $this->session->userdata['logged_in']['business_outlet_id'],
                            'business_admin_id'=>$this->session->userdata['logged_in']['business_admin_id'],
                            'master_admin_id'=>$this->session->userdata['logged_in']['master_admin_id']);
                $data = $this->CashierModel->TxnDetailsCustPP($where,$_GET['query']);
                // $this->PrettyPrintArray($data);
                if($data['success'] == 'true'){ 
                    $this->ReturnJsonArray(true,false,$data['res_arr']);
                    die;
                }
            }
        }
        else{
            $this->LogoutUrl(base_url()."Cashier/Login/");
        }
    }
    public function AddDataINPrefferedProductTable(){   
        if($this->IsLoggedIn('cashier')){
            if(isset($_GET) && !empty($_GET)){
                $where=array('customer_no'=>$_GET['customer_no'],
                'outlet_id' => $this->session->userdata['logged_in']['business_outlet_id'],     
                'business_admin_id'=>$this->session->userdata['logged_in']['business_admin_id'],
                'master_admin_id'=>$this->session->userdata['logged_in']['master_admin_id']);
                $data = $this->CashierModel->GetCustServicePP($where);
                // $this->PrettyPrintArray($data);
                if($data['success'] == 'true'){ 
                    $data = array(
                        'success' => 'true',
                        'error'   => 'false',
                        'message' => '',
                        'result' =>  $data['res_arr']
                        );
                        header("Content-type: application/json");
                        print(json_encode($data, JSON_PRETTY_PRINT));
                        die;        
                }
            }
        }
        else{
            $this->LogoutUrl(base_url()."Cashier/Login/");
        }
    }
    //26-05-2020
    public function GetProduct(){
        if($this->IsLoggedIn('cashier')){
            if(isset($_GET) && !empty($_GET)){
                $id=explode(',',$_GET['item_id']);
                // $this->PrettyPrintArray($id);
                $data = $this->CashierModel->ServiceDetail($id[0]);
                // $this->PrettyPrintArray($data);
                // die;
                if($data['success'] == 'true'){
                    $data1 = array(
                                'success' => 'true',
                                'error'   => 'false',
                                'message' => '',
                                'result' =>  $data['res_arr']
                    );
                    header("Content-type: application/json");
                    print(json_encode($data1, JSON_PRETTY_PRINT));
                    die;    
                }
            }
        }
        else{
            $this->LogoutUrl(base_url()."Cashier/");
        }
    }

    public function generateBill(){    	 
    	// $arr = array('service_id'=>'71,116','time'=>'2020-07-13 02:11:34');
    	// $arr = json_encode($arr);
    	// $arr = base64_encode($arr);

    	// echo "<pre>";
    	// print_r($arr);
    	// die;
		$this->load->helper('pdfhelper');//loading pdf helper
		if(1){	
			$customer_id = $this->uri->segment(3);
			$card_id = $this->uri->segment(4);
			$card_id = base64_decode($card_id);						
			// print_r($service_id_arr);
			// die;
			if(isset($customer_id)){
				//Check whether customer belongs to the logged cashier's shop and business admin
				$where = array(					
					'customer_id'       => $customer_id
				);

				$check = $this->CashierModel->VerifyOfflineCustomer($where);				

				if($check['success'] == 'true'){	
					$data['title'] = "Invoice";

					$data['individual_customer'] = [];
					$data1 = $this->CashierModel->DetailsById($customer_id,'mss_customers','customer_id');
						if($data1['success'] == 'true'){	
							 $data['individual_customer'] = $data1['res_arr'];
						}


//					$data['experts'] = $this->GetExperts();
					$where = array(
						'employee_business_admin' => $data['individual_customer']['customer_business_admin_id'],
						'employee_business_outlet'=> $data['individual_customer']['customer_business_outlet_id'],
						'employee_is_active' => TRUE
					);

					$data1 = $this->CashierModel->MultiWhereSelect('mss_employees',$where);
					if($data1['success'] == 'true'){	
						$data['experts'] = $data1['res_arr'];
					}

					$where = array(
						'business_outlet_business_admin' => $data['individual_customer']['customer_business_admin_id'],
						'business_outlet_id'=> $data['individual_customer']['customer_business_outlet_id'],
					);

					$data1 = $this->CashierModel->DetailsById($where['business_outlet_id'],'mss_business_outlets','business_outlet_id');
					if($data1['success'] == 'true'){	
						$data['shop_details'] =  $data1['res_arr'];
					}



//					$data['shop_details'] = $this->ShopDetails();
					//$data['individual_customer'] = $this->GetCustomerBilling($customer_id);
					
					// echo "<pre>";
					// print_r($data);
					// die;
					//for Bill No
					$outlet_counter = $this->db->select('*')->from('mss_business_outlets')->where('business_outlet_id',$data['individual_customer']['customer_business_outlet_id'])->get()->row_array();

					$data['bill_no']=strval("A".strval(100+$data['individual_customer']['customer_business_admin_id']) . "O" . strval( $data['individual_customer']['customer_business_outlet_id']) . "-" . strval($outlet_counter['business_outlet_bill_counter']));
										
					$sql ="SELECT * from mss_transaction_cart where id=".$card_id;

					$query = $this->db->query($sql);
					$result = $query->result_array();
					$cart = json_decode($result[0]['cart_data'],true);
					 
					
					$data['cart'] = $cart;
					// echo "<pre>";
					$outlet_admin_id = $result[0]['outlet_admin_id'];
					//print_r($result[0]['outlet_admin_id']);die;
					// print_r($data['cart']);
					$sql ="SELECT config_value from mss_config where config_key='salon_logo' and outlet_admin_id = $outlet_admin_id";

					$query = $this->db->query($sql);
					$result = $query->result_array();
					if(empty($result)){
						$sql ="SELECT config_value from mss_config where config_key='salon_logo' and outlet_admin_id = 1";

						$query = $this->db->query($sql);
						$result = $query->result_array();
					}
					$data['logo'] = $result[0]['config_value'];						// if(isset($this->session->userdata['payment'])){
					// 	$data['payment'] = $this->session->userdata['payment'][$customer_id];
					// }
					// print_r($data['payment']);
					// die;
					$this->load->view('cashier/cashier_print_bill',$data);
				}
				elseif ($check['error'] == 'true'){
					$data = array(
						'heading' => "Illegal Access!",
						'message' => $check['message']
					);
					$this->load->view('errors/html/error_general',$data);	
				}
			}
			else{
				$data = array(
					'heading' => "Illegal Access!",
					'message' => "Customer details/ID missing. Please do not change url!"
				);
				$this->load->view('errors/html/error_general',$data);
			}	
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/");
		}    	
		}
		
		//Due Amount SMS
	public function ReSendBill(){
		if($this->IsLoggedIn('cashier')){	
			$this->load->helper('ssl');//loading pdf helper
			if(isset($_POST) && !empty($_POST)){
				$this->form_validation->set_rules('txn_id', 'Transaction Id', 'trim|required');
				if ($this->form_validation->run() == FALSE){
					$data = array(
													'success' => 'false',
													'error'   => 'true',
													'message' =>  validation_errors()
												);
						header("Content-type: application/json");
						print(json_encode($data, JSON_PRETTY_PRINT));
						die;
				}else{
						$data=array(
							'txn_id' => $this->input->post('txn_id'),
							'business_outlet_id' => $this->session->userdata['logged_in']['business_outlet_id']
						);
						$result = $this->BusinessAdminModel->GetCustomerBill($data);			
						// $this->PrettyPrintArray($result);
						if($result['success'] == 'true'){
							//ReSend Bill SMS
							$res =$result['res_arr'][0];
							
							$customer_id=$res['customer_id'];
							$detail_id=$res['id'];
							$bill_url = base_url()."Cashier/generateBill/$customer_id/".base64_encode($detail_id);
							$bill_url = shortUrl($bill_url);
							$this->ReSendBillSms($res['customer_name'],$res['customer_mobile'],$res['business_outlet_name'],$res['txn_value'], $res['sender_id'],$res['api_key'], $bill_url);
							$this->ReturnJsonArray(true,false,"Message Send.");
							die;
						}else{
							$this->ReturnJsonArray(false,true,$result['message']);
							die;
						}
					}
				}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/Login/");
		}
	}

	public function ReSendBillSms($customer_name, $mobile, $outlet_name, $bill_amt, $sender_id, $api_key, $bill_url){
		if($this->IsLoggedIn('cashier')){
			// $bill_url = $this->session->userdata('bill_url');
			// error_log("URL ============1 ".$bill_url);
			
			$msg = "Dear ".$customer_name.", Thanks for Visiting ".$outlet_name."! You have been billed for Rs.".$bill_amt.". Look forward to serving you again! Please find the invoice on ".$bill_url;
   		$msg = rawurlencode($msg);   //This for encode your message content                 		
 			
 			// API 
			$url = 'https://www.smsgatewayhub.com/api/mt/SendSMS?APIKey='.$api_key.'&senderid='.$sender_id.'&channel=2&DCS=0&flashsms=0&number='.$mobile.'&text='.$msg.'&route=1';
            log_message('info', $url);
        
  		$ch = curl_init($url);
  		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  		curl_setopt($ch,CURLOPT_POST,1);
  		curl_setopt($ch,CURLOPT_POSTFIELDS,"");
  		curl_setopt($ch, CURLOPT_RETURNTRANSFER,2);
  		
  		$data = curl_exec($ch);
  		return json_encode($data);
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/Login");
		}				
	}

	public function RePrintBill(){
		$this->load->helper('pdfhelper');//loading pdf helper
		if($this->IsLoggedIn('cashier')){	
			$txn_id = $this->uri->segment(3);
			$outlet_admin_id = $this->session->userdata['logged_in']['business_outlet_id'];
			$data['cart']=$this->BusinessAdminModel->GetTransactionDetailByTxnId($txn_id);
			$data['cart']=$data['cart']['res_arr'];
			$data['shop_details'] = $this->ShopDetails();
			$sql ="SELECT config_value from mss_config where config_key='salon_logo' and outlet_admin_id = $outlet_admin_id";

			$query = $this->db->query($sql);

			$result = $query->result_array();
			if(empty($result)){
				$sql ="SELECT config_value from mss_config where config_key='salon_logo' and outlet_admin_id = 1";

				$query = $this->db->query($sql);
				$result = $query->result_array();
			}
			$data['logo'] = $result[0]['config_value'];						
			$this->load->view('cashier/cashier_reprint_bill',$data);			
		}else{
			$this->LogoutUrl(base_url()."Cashier/Login");
		}	
	}

	public function AddTxnRemarks(){
		if($this->IsLoggedIn('cashier')){
			if(isset($_POST) && !empty($_POST)){
				$this->form_validation->set_rules('txn_remarks', 'Transaction Remarks', 'trim|required');
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
					if(!isset($this->session->userdata['txn_remarks'])){
						$txn_remarks = array(
														'txn_remarks'=> $this->input->post('txn_remarks')
												 	);
						$this->session->set_userdata('txn_remarks', $txn_remarks);
						$this->ReturnJsonArray(true,false,"Remarks applied successfully!");
						die;
					}
					else{
						
						$txn_remarks = $this->session->userdata['txn_remarks'];						
						$this->session->set_userdata('txn_remarks', $txn_remarks);
						$this->ReturnJsonArray(true,false,"Remarks applied successfully!");
						die;
					}
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."Cashier/Login");
		}	
	}

	public function daybook(){        
        $this->load->model('BusinessAdminModel');
        if(!isset($_GET) || empty($_GET)){
            if(!empty($_REQUEST['to_date'])){
                $date = $_REQUEST['to_date'];
                $one_day_before = date('Y-m-d', strtotime($date. ' - 1 days'));                    
            }else{
                $date = date('Y-m-d');    
                $one_day_before = date('Y-m-d',strtotime("-1 days"));
            }
           
            $result = $this->BusinessAdminModel->GetExpenseRecord($date);
        }            
        if($result['success']){
            $transaction = $result['res_arr']['transaction'];
            $expenses = $result['res_arr']['expenses'];
            $pending_amount = $result['res_arr']['pending_amount'];
            $temp = [];
            $transaction_data = [];
            $json_data = [];                
            //transaction data
            $key_info = [];
            
            foreach ($transaction as $key => $value) {
                if(!empty($value['txn_settlement_payment_mode'])){                        
                    if(in_array(strtolower($value['txn_settlement_payment_mode']), $temp)){
                        $transaction_data[strtolower($value['txn_settlement_payment_mode'])] += $value['total_price'];
                    }else{
                        $result = json_decode($value['txn_settlement_payment_mode']);
                        if (json_last_error() === JSON_ERROR_NONE) {
                            $json_data[] = json_decode($value['txn_settlement_payment_mode'],true);
                        }else{
                            $transaction_data[strtolower($value['txn_settlement_payment_mode'])] = $value['total_price'];
                            $temp[] = strtolower($value['txn_settlement_payment_mode']);
                        }                            
                    }
                }                        
            }                
            if(!empty($json_data)){                    
                foreach ($json_data as $key => $j) {
                    foreach ($j as $key => $t) {
                      if(in_array(strtolower($t['payment_type']), $temp)){
                        $transaction_data[strtolower($t['payment_type'])] += $t['amount_received'];
                        }else{
                            $transaction_data[strtolower($t['payment_type'])] = $t['amount_received'];
                            $temp[] = strtolower($t['payment_type']);
                        }  
                    }                       
                }
            }
            $key_info['keys'][0] = $temp;                
            // expenses

            $temp = [];
            $expenses_data = [];          
            //transaction data                
            foreach ($expenses as $key => $value) {
                if(!empty($value['payment_mode'])){                        
                    if(in_array(strtolower($value['payment_mode']), $temp)){
                        $expenses_data[strtolower($value['payment_mode'])] += $value['total_amount'];
                    }else{                            
                            $expenses_data[strtolower($value['payment_mode'])] = $value['total_amount'];
                            $temp[] = strtolower($value['payment_mode']);
                    }
                }                        
            }
            $key_info['keys'][1] = $temp;
            // pending tracker
            $temp = [];
            $pending_amount_data = [];          
            //transaction data                        
            foreach ($pending_amount as $key => $value) {
                if(!empty($value['payment_type'])){                        
                    if(in_array(strtolower($value['payment_type']), $temp)){
                        $pending_amount_data[strtolower($value['payment_type'])] += $value['total_amount'];
                    }else{                            
                            $pending_amount_data[strtolower($value['payment_type'])] = $value['total_amount'];
                            $temp[] = strtolower($value['payment_type']);
                    }
                }                        
            }
            $key_info['keys'][2] = $temp;

            //get opening record        
            $result = $this->BusinessAdminModel->getOpeningRecord($one_day_before);
            $opening_balance = $result['res_arr']['opening_balance'];       
            $temp = [];
                $opening_balance_data = [];          
                //transaction data                        
                foreach ($opening_balance as $key => $value) {
                    if(!empty($value['payment_mode'])){                        
                        if(in_array(strtolower($value['payment_mode']), $temp)){
                            $opening_balance_data[strtolower($value['payment_mode'])] += $value['amount'];
                        }else{                            
                                $opening_balance_data[strtolower($value['payment_mode'])] = $value['amount'];
                                $temp[] = strtolower($value['payment_mode']);
                        }
                    }                        
                }
                $key_info['keys'][3] = $temp;


                $p_mode = [];
                foreach ($key_info as $key => $k) {
                    foreach ($k as $key => $keys) {
                        if(!in_array($keys, $p_mode)){
                            $p_mode[] = $keys;
                        }
                    }                                    
                }

        }
        
        $p_mode = array_filter($p_mode);        
        $p_mode = call_user_func_array('array_merge', $p_mode);
        $p_mode = array_unique($p_mode);
        $p_mode = array_values($p_mode);        
        $data['p_mode'] = $p_mode;
        $data['opening_balance_data'] = $opening_balance_data;
        $data['pending_amount_data'] = $pending_amount_data;
        $data['expenses_data'] = $expenses_data;
        $data['transaction_data'] = $transaction_data;
        $data['date'] = $date;
        $this->load->view('cashier/cashier_book_view',$data);
    }


    public function cashbook(){        
        $this->load->model('BusinessAdminModel');
        if(!isset($_GET) || empty($_GET)){
            if(!empty($_REQUEST['to_date'])){
                $from = $_REQUEST['from_date'];
                $to = $_REQUEST['to_date'];
                //$one_day_before = date('Y-m-d', strtotime($date. ' - 1 days'));                    
            }else{
                $from = $to = date('Y-m-d');    
                //$one_day_before = date('Y-m-d',strtotime("-1 days"));
            }
           
            $result = $this->BusinessAdminModel->GetCashRecord($from,$to);
        }            
        if($result['success']){
            $transaction = $result['res_arr']['transaction'];
            $expenses = $result['res_arr']['expenses'];
            $pending_amount = $result['res_arr']['pending_amount'];
            $temp = [];
            $transaction_data = [];
            $json_data = [];                
            //transaction data
            $key_info = [];
            
            foreach ($transaction as $key => $value) {
                if(!empty($value['txn_settlement_payment_mode'])){                        
                    if(in_array(strtolower($value['txn_settlement_payment_mode']), $temp)){
                        $transaction_data[strtolower($value['txn_settlement_payment_mode'])] += $value['total_price'];
                    }else{
                        $result = json_decode($value['txn_settlement_payment_mode']);
                        if (json_last_error() === JSON_ERROR_NONE) {
                            $json_data[] = json_decode($value['txn_settlement_payment_mode'],true);
                        }else{
                            $transaction_data[strtolower($value['txn_settlement_payment_mode'])] = $value['total_price'];
                            $temp[] = strtolower($value['txn_settlement_payment_mode']);
                        }                            
                    }
                }                        
            }                
            if(!empty($json_data)){                    
                foreach ($json_data as $key => $j) {
                    foreach ($j as $key => $t) {
                      if(in_array(strtolower($t['payment_type']), $temp)){
                        $transaction_data[strtolower($t['payment_type'])] += $t['amount_received'];
                        }else{
                            $transaction_data[strtolower($t['payment_type'])] = $t['amount_received'];
                            $temp[] = strtolower($t['payment_type']);
                        }  
                    }                       
                }
            }
            $key_info['keys'][0] = $temp;                
            // expenses

            $temp = [];
            $expenses_data = [];          
            //transaction data                
            foreach ($expenses as $key => $value) {
                if(!empty($value['payment_mode'])){                        
                    if(in_array(strtolower($value['payment_mode']), $temp)){
                        $expenses_data[strtolower($value['payment_mode'])] += $value['total_amount'];
                    }else{                            
                            $expenses_data[strtolower($value['payment_mode'])] = $value['total_amount'];
                            $temp[] = strtolower($value['payment_mode']);
                    }
                }                        
            }
            $key_info['keys'][1] = $temp;
            // pending tracker
            $temp = [];
            $pending_amount_data = [];          
            //transaction data                        
            foreach ($pending_amount as $key => $value) {
                if(!empty($value['payment_type'])){                        
                    if(in_array(strtolower($value['payment_type']), $temp)){
                        $pending_amount_data[strtolower($value['payment_type'])] += $value['total_amount'];
                    }else{                            
                            $pending_amount_data[strtolower($value['payment_type'])] = $value['total_amount'];
                            $temp[] = strtolower($value['payment_type']);
                    }
                }                        
            }
            $key_info['keys'][2] = $temp;

            
                $p_mode = [];
                foreach ($key_info as $key => $k) {
                    foreach ($k as $key => $keys) {
                        if(!in_array($keys, $p_mode)){
                            $p_mode[] = $keys;
                        }
                    }                                    
                }

        }
        
        $p_mode = array_filter($p_mode);        
        $p_mode = call_user_func_array('array_merge', $p_mode);
        $p_mode = array_unique($p_mode);
        $p_mode = array_values($p_mode);        
        $data['p_mode'] = $p_mode;
        $data['opening_balance_data'] = $opening_balance_data;
        $data['pending_amount_data'] = $pending_amount_data;
        $data['expenses_data'] = $expenses_data;
        $data['transaction_data'] = $transaction_data;
        $data['from'] = $from;
        $data['to'] = $to;
        $this->load->view('cashier/cashier_cash_book_view',$data);
    }

}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/third_party/spout-2.4.3/src/Spout/Autoloader/autoload.php';
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type; 

class BusinessAdmin extends CI_Controller {
		
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

	private function GetDataForAdmin($title){
		$data = array();
		$data['title'] = $title;
		$data['business_admin_packages'] = $this->GetBusinessAdminPackages();
		$data['business_admin_details']  = $this->GetBusinessAdminDetails();
		$data['business_outlet_details'] = $this->GetBusinessOutlets();
		if(isset($this->session->userdata['outlets'])){
			$data['selected_outlet']        = $this->GetCurrentOutlet($this->session->userdata['outlets']['current_outlet']);
		}
		if(isset($this->session->userdata['outlets'])){
			$where = array(
				'business_admin_id' => $this->session->userdata['logged_in']['business_admin_id'],
				'business_outlet_id'=> $this->session->userdata['outlets']['current_outlet'],
			);
			
			$data['loyalty_payment']=$this->BusinessAdminModel->GetLoyaltyAmountReceived($where);
			$data['loyalty_payment']=$data['loyalty_payment']['res_arr'][0]['loyalty_wallet'];
			$data['cards_data']['payment_wise'] = $this->BusinessAdminModel->GetSalesPaymentWiseData($where);
			$data['cards_data']['payment_wise']=$data['cards_data']['payment_wise']['res_arr'];
			$data['nav_details']['revenue']=0;
			$data['nav_details']['visit']=0;
			$data['nav_details']['appointment']=0;
			$data['svisits']=$this->BusinessAdminModel->ServiceVisitSales($where);
			if($data['svisits']['success'] == 'true'){
				$data['nav_details']['visit']=$data['svisits']['res_arr'][0]['visit'];
				$data['nav_details']['revenue']=$data['svisits']['res_arr'][0]['service'];
			}
			$data['appointment']=$this->BusinessAdminModel->GetAllAppointmentsCount($where);
			if($data['appointment']['success']=='true'){
				$data['nav_details']['appointment']=$data['appointment']['res_arr'][0]['count'];
			}
			$data['revenue']=$this->BusinessAdminModel->PackageVisitSales($where);
			if($data['revenue']['success'] == 'true'){
				$data['nav_details']['revenue']=$data['nav_details']['revenue']+$data['revenue']['res_arr'][0]['packages'];
				$data['nav_details']['visit']=$data['nav_details']['visit']+$data['revenue']['res_arr'][0]['visit'];
			}
		}
		return $data;
	}

	//constructor of the Alumni Controller
	public function __construct(){
	   parent::__construct();
	   date_default_timezone_set('Asia/Kolkata');
	   $this->load->model('AnalyticsModel');
	   $this->load->model('BusinessAdminModel');
	   $this->load->model('AppointmentsModel');
	   $this->load->model('CashierModel');
	   $this->load->model('POSModel');
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
		redirect(base_url().'BusinessAdmin/Login','refresh');
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

	//function for login
	public function Login(){
        if(isset($_POST) && !empty($_POST)){
            $this->form_validation->set_rules('business_admin_email', 'Email', 'trim|required|max_length[100]');
            $this->form_validation->set_rules('business_admin_password', 'Password', 'trim|required');
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
                    'business_admin_email'    => $this->input->post('business_admin_email'),
                    'business_admin_password' => $this->input->post('business_admin_password')
                );
                $result = $this->BusinessAdminModel->BusinessAdminLogin($data);
                if ($result['success'] == 'true') 
                {
                    $result = $this->BusinessAdminModel->BusinessAdminByEmail($data['business_admin_email']);
                    // $this->PrettyPrintArray($result);
                    // exit;
                    if($result['success'] == 'true'){
                        if($data['business_admin_email'] == $result['res_arr']['business_admin_email'] && password_verify($data['business_admin_password'],$result['res_arr']['business_admin_password']) && $result['res_arr']['business_admin_account_expiry_date'] >= date('Y-m-d'))
                        { 
                            $session_data = array(
                                'business_admin_id'      => $result['res_arr']['business_admin_id'],
                                'business_admin_name'    => $result['res_arr']['business_admin_first_name'].' '.$result['res_arr']['business_admin_last_name'],
                                'business_admin_email'   => $result['res_arr']['business_admin_email'],
                                'master_admin_id'   => $result['res_arr']['business_master_admin_id'],
                                'user_type'        => 'business_admin'
                            );
                            $this->session->set_userdata('logged_in', $session_data);
                            $this->ReturnJsonArray(true,false,'Valid login!');
                            die;
                        }
                        else
                        {
                            if($result['res_arr']['business_admin_account_expiry_date'] < date('Y-m-d')){
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
            $this->load->view('business_admin/ba_login_view',$data);
        }
    }

	public function ResetBusinessAdminPassword(){
		if($this->IsLoggedIn('business_admin')){
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
							"business_admin_password" => password_hash($new_password,PASSWORD_DEFAULT),
							"business_admin_id"       => $this->session->userdata['logged_in']['business_admin_id']
						);
						
						$result = $this->BusinessAdminModel->Update($data,'mss_business_admin','business_admin_id');
						
						if($result['success'] == 'true'){
							$this->ReturnJsonArray(true,false,"Password changed successfully!");
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
		 		$data = $this->GetDataForAdmin("Reset Password");
				$this->load->view('business_admin/ba_reset_password_view',$data);
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin");
		}					
	}

	//Dashboard for the Business Admin
    public function Dashboard(){
        if($this->IsLoggedIn('business_admin')){
            $data = $this->GetDataForAdmin("Dashboard");
            $data['sidebar_collapsed'] = "true";
            
            if(isset($data['selected_outlet']) || !empty($data['selected_outlet'])){
                $data['cards_data']['sales'] = $this->TopCardsData('sales');
                $data['cards_data']['productsales'] = $this->TopCardsData('productsales');
                $data['cards_data']['customer_count'] = $this->TopCardsData('customer_count');
                $data['cards_data']['new_customer'] = $this->TopCardsData('new_customer');
                $data['cards_data']['total_visits'] = $this->TopCardsData('total_visits');
                $data['cards_data']['expenses'] = $this->TopCardsData('expenses');
                $data['cards_data']['yest_expenses'] = $this->TopCardsData('yest_expenses');
                $data['cards_data']['existing_customer'] = $this->TopCardsData('existing_customer');
                $data['categories']  = $this->GetCategories($this->session->userdata['outlets']['current_outlet']);
                $data['cards_data']['payment_wise'] = $this->SalesPaymentWiseData();
                // $this->PrettyPrintArray($data['cards_data']['payment_wise']);    
                $where = array(
                    'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id'],
                    'business_outlet_id' => $this->session->userdata['outlets']['current_outlet']
                );
                $data['total_virtual_wallet']= $this->BusinessAdminModel->GetTotalVirtualWalletBalance($where);
                $data['total_virtual_wallet'] = $data['total_virtual_wallet']['res_arr'][0]['total_virtual_wallet_balance'];
                
                $data['yesterday_sales']=$this->TopCardsData('yesterday_sales');
                $data['bill']= $this->BusinessAdminModel->GetTodaysBill($where);
								$data['bill']= $data['bill']['res_arr'];
								
								$data['paid_back']=$this->BusinessAdminModel->GetDailyAmountPaidBack($where);
								$data['paid_back']=$data['paid_back']['res_arr'][0]['paid_back'];
                // $this->PrettyPrintArray($data['paid_back']);
                $data['card_data']= $this->BusinessAdminModel->TodayPackageSales($where);
                $data['card_data']=$data['card_data']['res_arr'];
                $data['virtual_wallet_sales']= $this->BusinessAdminModel->TodayVirtualWalletSales($where);
                $data['today_virtual_wallet_sales']=$data['virtual_wallet_sales']['res_arr'][0]['wallet_sales'];
                
                //pending Amount card data
                $data['total_due_amount']= $this->BusinessAdminModel->GetTotalDueAmount($where);
                $data['total_due_amount']=$data['total_due_amount']['res_arr'][0];
                
                $due_amount=$this->BusinessAdminModel->GetTodaysDueAmount($where);
								$data['due_amount']=$due_amount['res_arr'][0]['due_amount'];
								$package_due_amount=$this->BusinessAdminModel->GetTodaysPackageDueAmount($where);
								$data['package_due_amount']=$package_due_amount['res_arr'][0]['package_due_amount'];
								                // $this->PrettyPrintArray($data['package_due_amount']);
                $data['pending_amount_received']=$this->BusinessAdminModel->GetPendingAmountReceived($where);
                $data['pending_amount_received']=$data['pending_amount_received']['res_arr'][0]['pending_amount_received'];
                $data['monthly_due_amount']=$this->BusinessAdminModel->GetMonthlyDueAmount($where);
                $data['monthly_due_amount']=$data['monthly_due_amount']['res_arr'][0]['monthly_due_amount'];
                $data['monthly_pending_amount_received']=$this->BusinessAdminModel->GetMonthlyPendingAmountReceived($where);
                $data['monthly_pending_amount_received']=$data['monthly_pending_amount_received']['res_arr'][0]['monthly_pending_amount_received'];
                $data['last_month_due_amount']=$this->BusinessAdminModel->GetLastMonthDueAmount($where);
                $data['last_month_due_amount']=$data['last_month_due_amount']['res_arr'][0]['last_month_due_amount'];
                $data['last_month_pending_amount_received']=$this->BusinessAdminModel->GetLastMonthPendingAmountReceived($where);
                $data['last_month_pending_amount_received']=$data['last_month_pending_amount_received']['res_arr'][0]['last_month_pending_amount_received'];
                
                //loyalty top card data
                $data['loyalty_payment']=$this->BusinessAdminModel->GetLoyaltyAmountReceived($where);
                $data['loyalty_payment']=$data['loyalty_payment']['res_arr'][0]['loyalty_wallet'];
                $data['loyalty_points_given']=$this->BusinessAdminModel->GetLoyaltyPointsGiven($where);
                $data['loyalty_points_given']=$data['loyalty_points_given']['res_arr'][0]['loyalty_points'];
                $data['monthly_loyalty_payment']=$this->BusinessAdminModel->GetMonthlyLoyaltyAmountReceived($where);
                $data['monthly_loyalty_payment']=$data['monthly_loyalty_payment']['res_arr'][0]['monthly_loyalty_wallet'];
                $data['monthly_loyalty_points_given']=$this->BusinessAdminModel->GetMonthlyLoyaltyPointsGiven($where);
                $data['monthly_loyalty_points_given']=$data['monthly_loyalty_points_given']['res_arr'][0]['monthly_loyalty_points'];
                $data['last_month_loyalty_payment']=$this->BusinessAdminModel->GetLastMonthLoyaltyAmountReceived($where);
                $data['last_month_loyalty_payment']=$data['last_month_loyalty_payment']['res_arr'][0]['last_month_loyalty_wallet'];
                $data['last_month_loyalty_points_given']=$this->BusinessAdminModel->GetLastMonthLoyaltyPointsGiven($where);
                $data['last_month_loyalty_points_given']=$data['last_month_loyalty_points_given']['res_arr'][0]['last_month_loyalty_points'];
                $data['sales_till_date']=$this->BusinessAdminModel->GetMonthlySalesTillDate($where);
								$data['sales_till_date']=$data['sales_till_date']['res_arr'][0]['sales_till_date'];
								// $this->PrettyPrintArray($data['sales_till_date']);
                $data['product_sales_till_date']=$this->BusinessAdminModel->GetMonthlyProductSalesTillDate($where);
								$data['product_sales_till_date']=$data['product_sales_till_date']['res_arr'][0]['product_sales_till_date'];
                $data['package_sales_till_date']=$this->BusinessAdminModel->PackageSalesTillDate($where);
                $data['package_sales_till_date']=$data['package_sales_till_date']['res_arr'][0]['package_sales'];
                
                //monthly top card data 
                $data['cards_data']['monthly_sales']=$this->MonthlyTopCardsData('monthly_sales');
                $data['cards_data']['monthly_expenses']=$this->MonthlyTopCardsData('monthly_expenses');
                
                $data['monthly_sales_payment_wise']=$this->BusinessAdminModel->GetMonthlySalesPaymentWiseData($where);
                $data['monthly_sales_payment_wise']=$data['monthly_sales_payment_wise']['res_arr'];
                $data['monthly_package_sales_payment_wise']=$this->BusinessAdminModel->GetMonthlyPackageSalesPaymentWiseData($where);
                $data['monthly_package_sales_payment_wise']=$data['monthly_package_sales_payment_wise']['res_arr'];
                // $this->PrettyPrintArray($data['monthly_sales_payment_wise']);
                // exit;
                //Last months card data
                $data['cards_data']['last_month_sales'] = $this->LastMonthTopCardsData('last_month_sales');
                $data['cards_data']['last_month_product_sales'] = $this->LastMonthTopCardsData('last_month_product_sales');
                $data['cards_data']['last_month_expense'] = $this->LastMonthTopCardsData('last_month_expense');
                
                $data['last_month_package_sales']=$this->BusinessAdminModel->PackageSalesForLastMonth($where);
                $data['last_month_package_sales']=$data['last_month_package_sales']['res_arr'][0]['package_sales'];
								$data['last_month_sales_payment_wise']=$this->BusinessAdminModel->GetLastMonthSalesPaymentWiseData($where);
								
                $data['last_month_sales_payment_wise']=$data['last_month_sales_payment_wise']['res_arr'];
                
                $data['package_payment_wise'] = $this->BusinessAdminModel->GetPackageSalesPaymentWiseData($where);
                $data['package_payment_wise']=$data['package_payment_wise']['res_arr'];
                // $this->PrettyPrintArray($data['cards_data']['last_month_sales']);
                // exit;
                $data['last_month_package_sales_payment_wise'] = $this->BusinessAdminModel->GetLastMonthPackageSalesPaymentWiseData($where);
                $data['last_month_package_sales_payment_wise']=$data['last_month_package_sales_payment_wise']['res_arr'];
                // $this->PrettyPrintArray($data['last_month_package_sales_payment_wise']);
                // exit;
                
                //balance_to be paid back
                $data['balance_paidback']=$this->BusinessAdminModel->GetBalancePaidBack($where);
                if(isset($data['balance_paidback'])){
                    $data['balance_paidback']=(int)$data['balance_paidback']['res_arr'][0]['balance_paid'];
                }else{
                    $data['balance_paidback']=(int)0;
				}
				

				// Analytics 
					//Revenue Trends 
						// revenue client
						$revenueclient=$this->BusinessAdminModel->LastSevenDaysSalesClient();
						if($revenueclient['success'] == 'false'){
							$revenueclient=array();
						}else{
							$revenueclient = $revenueclient['res_arr'];
						}
						// $this->PrettyPrintArray(($revenue_client));
						$data['client_revenue_labels']= array();
						$data['client_revenue_sales']=array();
						if(count($revenueclient) == 0){
							for($i=6;$i>=0;$i--){
								$tt = date("Y-m-d",strtotime("-$i day"));
								array_push($data['client_revenue_labels'],$tt);
								array_push($data['client_revenue_sales'],0);
							}
						
						}else if(count($revenueclient) == 7){
							foreach($revenueclient as $k=>$v){
								array_push($data['client_revenue_labels'],$v['bill_date']);
								array_push($data['client_revenue_sales'],$v['total_sales']);
							}
						}else{
							for($i=6;$i>=0;$i--){
								// $j=$i+1;
								$tt = date("Y-m-d",strtotime("-$i day"));
								// echo " ".$tt;
								$s0=0;
								foreach($revenueclient as $k=>$v){
									if($tt == $v['bill_date']){
										array_push($data['client_revenue_labels'],$v['bill_date']);
										array_push($data['client_revenue_sales'],$v['total_sales']);
										$s0=1;
									}
								}
								if($s0 == 0){		
									array_push($data['client_revenue_labels'],$tt);
									array_push($data['client_revenue_sales'],0);
								}
							}
						}
						// revenue visit
						$revenue_client=$this->BusinessAdminModel->LastSevenDaysSales();
						if($revenue_client['success'] == 'false'){
							$revenue_client=array();
						}else{
							$revenue_client = $revenue_client['res_arr'];
						}
						
						// $this->PrettyPrintArray(($revenue_client));
						$data['revenue_labels']= array();
						$data['revenue_sales']=array();
						if(count($revenue_client) == 0){
							for($i=6;$i>=0;$i--){
								$tt = date("Y-m-d",strtotime("-$i day"));
								array_push($data['revenue_labels'],$tt);
								array_push($data['revenue_sales'],0);
							}
						
						}else if(count($revenue_client) == 7){
							foreach($revenue_client as $k=>$v){
								array_push($data['revenue_labels'],$v['bill_date']);
								array_push($data['revenue_sales'],$v['total_sales']);
							}
						}else{
							for($i=6;$i>=0;$i--){
								// $j=$i+1;
								$tt = date("Y-m-d",strtotime("-$i day"));
								// echo " ".$tt;
								$s1=0;
								foreach($revenue_client as $k=>$v){
									if($tt == $v['bill_date']){
										array_push($data['revenue_labels'],$v['bill_date']);
										array_push($data['revenue_sales'],$v['total_sales']);
									$s1=1;
									}
								}
								if($s1==0){
									array_push($data['revenue_labels'],$tt);
									array_push($data['revenue_sales'],0);
								}
							}
						}
						
						// // revenue per day
						$revenue_perday=$this->BusinessAdminModel->LastSevenDaysSalesPerDay();
						if($revenue_perday['success'] == 'false'){
							$revenue_perday=array();
						}else{
							$revenue_perday = $revenue_perday['res_arr'];
						}
						// $this->PrettyPrintArray(($revenue_perday));
						$data['perday_labels']= array();
						$data['perday_sales']=array();
						if(count($revenue_perday) == 0){
							for($i=6;$i>=0;$i--){
								$tt = date("Y-m-d",strtotime("-$i day"));
								array_push($data['perday_labels'],$tt);
								array_push($data['perday_sales'],0);
							}
						
						}else if(count($revenue_perday) == 7){
							foreach($revenue_perday as $k=>$v){
								array_push($data['perday_labels'],$v['bill_date']);
								array_push($data['perday_sales'],$v['total_sales']);
							}
						}else{
							for($i=6;$i>=0;$i--){
								// $j=$i+1;
								$tt = date("Y-m-d",strtotime("-$i day"));
								// echo " ".$tt;
								$s2=0;
								foreach($revenue_perday as $k=>$v){
									if($tt == $v['bill_date']){
										array_push($data['perday_labels'],$v['bill_date']);
										array_push($data['perday_sales'],$v['total_sales']);
										$s2=1;
									}
								}
								if($s2==0){
									array_push($data['perday_labels'],$tt);
									array_push($data['perday_sales'],0);
								}
							
							}
						}
						$Srevenue_perday=$this->BusinessAdminModel->LastSevenDaysServiceSalesPerDay();
						if($Srevenue_perday['success'] == 'false'){
							$Srevenue_perday=array();
						}else{
							$Srevenue_perday = $Srevenue_perday['res_arr'];
						}
						// $this->PrettyPrintArray(($Srevenue_perday));
						$data['service_perday_labels']= array();
						$data['service_perday_sales']=array();
						if(count($Srevenue_perday) == 0){
							for($i=6;$i>=0;$i--){
								$tt = date("Y-m-d",strtotime("-$i day"));
								array_push($data['service_perday_labels'],$tt);
								array_push($data['service_perday_sales'],0);
							}
						
						}else if(count($Srevenue_perday) == 7){
							foreach($Srevenue_perday as $k=>$v){
								array_push($data['service_perday_labels'],$v['bill_date']);
								array_push($data['service_perday_sales'],$v['total_sales']);
							}
						}else{
							for($i=6;$i>=0;$i--){
								// $j=$i+1;
								$tt = date("Y-m-d",strtotime("-$i day"));
								// echo " ".$tt;
								$s3=0;
								foreach($Srevenue_perday as $k=>$v){
									if($tt == $v['bill_date']){
										array_push($data['service_perday_labels'],$v['bill_date']);
										array_push($data['service_perday_sales'],$v['total_sales']);
										$s3=1;
									}
								}
								if($s3 == 0){
									array_push($data['service_perday_labels'],$tt);
									array_push($data['service_perday_sales'],0);
								}
							
							}
						}
						$Prevenue_perday=$this->BusinessAdminModel->LastSevenDaysProductSalesPerDay();
						if($Prevenue_perday['success'] == 'false'){
							$Prevenue_perday=array();
						}else{
							$Prevenue_perday = $Prevenue_perday['res_arr'];
						}
						// $this->PrettyPrintArray(($Prevenue_perday));
						$data['otc_perday_labels']= array();
						$data['otc_perday_sales']=array();
						if(count($Prevenue_perday) == 0){
							for($i=6;$i>=0;$i--){
								$tt = date("Y-m-d",strtotime("-$i day"));
								array_push($data['otc_perday_labels'],$tt);
								array_push($data['otc_perday_sales'],0);
							}
						
						}else if(count($Prevenue_perday) == 7){
							foreach($Prevenue_perday as $k=>$v){
								array_push($data['otc_perday_labels'],$v['bill_date']);
								array_push($data['otc_perday_sales'],$v['total_sales']);
							}
						}else{
							for($i=6;$i>=0;$i--){
								// $j=$i+1;
								$tt = date("Y-m-d",strtotime("-$i day"));
								$s4=0;
								foreach($Prevenue_perday as $k=>$v){
									if($tt == $v['bill_date']){
										array_push($data['otc_perday_labels'],$v['bill_date']);
										array_push($data['otc_perday_sales'],$v['total_sales']);
										$s4=1;
									}
								}
								if($s4 == 0){
									array_push($data['otc_perday_labels'],$tt);
									array_push($data['otc_perday_sales'],0);
								}
							
							}
						}


						// revenue client
						$data['client_avg']=$this->BusinessAdminModel->Client7DaysAvg();
						// $this->PrettyPrintArray($data['client_avg']);
						// die;
						if($data['client_avg']['res_arr'][0]['total_sales'] != null){
							$data['client_avg']=$data['client_avg']['res_arr'][0]['total_sales'];
						}else{
							$data['client_avg']=0;
						}
						
						$data['lmtd_client_avg']=$this->BusinessAdminModel->Client7DaysAvgLMTD();
						if($data['lmtd_client_avg']['res_arr'][0]['total_sales'] != null){
							$data['lmtd_client_avg']=$data['lmtd_client_avg']['res_arr'][0]['total_sales'];
						}else{
							$data['lmtd_client_avg']=0;
						}
						$data['mtd_client_avg']=$this->BusinessAdminModel->Client7DaysAvgMTD();
						if($data['mtd_client_avg']['res_arr'][0]['total_sales'] != null){
							$data['mtd_client_avg']=$data['mtd_client_avg']['res_arr'][0]['total_sales'];
						}else{
							$data['mtd_client_avg']=0;
						}
						// revenue visit
						$data['visit_avg']=$this->BusinessAdminModel->Vist7DaysAvg();
						if($data['visit_avg']['res_arr'][0]['total_sales'] != null){
							$data['visit_avg']=$data['visit_avg']['res_arr'][0]['total_sales'];
						}else{
							$data['visit_avg']=0;
						}
						$data['lmtd_visit_avg']=$this->BusinessAdminModel->Vist7DaysAvgLMTD();
						if($data['lmtd_visit_avg']['res_arr'][0]['total_sales'] != null){
							$data['lmtd_visit_avg']=$data['lmtd_visit_avg']['res_arr'][0]['total_sales'];
						}else{
							$data['lmtd_visit_avg']=0;
						}
						$data['mtd_visit_avg']=$this->BusinessAdminModel->Vist7DaysAvgMTD();
						if($data['mtd_visit_avg']['res_arr'][0]['total_sales'] != null){
							$data['mtd_visit_avg']=$data['mtd_visit_avg']['res_arr'][0]['total_sales'];
						}else{
							$data['mtd_visit_avg']=0;
						}

						$data['revenue_avg']=$this->BusinessAdminModel->Revenue7DaysAvg();
						if($data['revenue_avg']['res_arr'][0]['total_sales'] != null){
							$data['revenue_avg']=$data['revenue_avg']['res_arr'][0]['total_sales'];
						}else{
							$data['revenue_avg']=0;
						}
						$data['mtd_revenue_avg']=$this->BusinessAdminModel->RevenueMTDAvg();
						if($data['mtd_revenue_avg']['res_arr'][0]['total_sales'] != null){
							$data['mtd_revenue_avg']=$data['mtd_revenue_avg']['res_arr'][0]['total_sales'];
						}else{
							$data['mtd_revenue_avg']=0;
						}
						$data['lmtd_revenue_avg']=$this->BusinessAdminModel->RevenueLMTDAvg();
						if($data['lmtd_revenue_avg']['res_arr'][0]['total_sales'] != null){
							$data['lmtd_revenue_avg']=$data['lmtd_revenue_avg']['res_arr'][0]['total_sales'];
						}else{
							$data['lmtd_revenue_avg']=0;
						}

						$data['service_avg']=$this->BusinessAdminModel->Service7DaysAvg();
						if($data['service_avg']['res_arr'][0]['total_sales'] != null){
							$data['service_avg']=$data['service_avg']['res_arr'][0]['total_sales'];
						}else{
							$data['service_avg']=0;
						}
						$data['mtd_service_avg']=$this->BusinessAdminModel->ServiceMTDAvg();
						if($data['mtd_service_avg']['res_arr'][0]['total_sales'] != null){
							$data['mtd_service_avg']=$data['mtd_service_avg']['res_arr'][0]['total_sales'];
						}else{
							$data['mtd_service_avg']=0;
						}
						$data['lmtd_service_avg']=$this->BusinessAdminModel->ServiceLMTDAvg();
						if($data['lmtd_service_avg']['res_arr'][0]['total_sales'] != null){
							$data['lmtd_service_avg']=$data['lmtd_service_avg']['res_arr'][0]['total_sales'];
						}else{
							$data['lmtd_service_avg']=0;
						}


						$data['otc_avg']=$this->BusinessAdminModel->Product7DaysAvg();
						if($data['otc_avg']['res_arr'][0]['total_sales'] != null){
							$data['otc_avg']=$data['otc_avg']['res_arr'][0]['total_sales'];
						}else{
							$data['otc_avg']=0;
						}
						$data['mtd_otc_avg']=$this->BusinessAdminModel->ProductMTDAvg();
						if($data['mtd_otc_avg']['res_arr'][0]['total_sales'] != null){
							$data['mtd_otc_avg']=$data['mtd_otc_avg']['res_arr'][0]['total_sales'];
						}else{
							$data['mtd_otc_avg']=0;
						}
						$data['lmtd_otc_avg']=$this->BusinessAdminModel->ProductLMTDAvg();
						if($data['lmtd_otc_avg']['res_arr'][0]['total_sales'] != null){
							$data['lmtd_otc_avg']=$data['lmtd_otc_avg']['res_arr'][0]['total_sales'];
						}else{
							$data['lmtd_otc_avg']=0;
						}
						// $this->PrettyPrintArray($data['visit_avg']);
			//Product Pemetrations Trends
			$retail_product_buyers=$this->BusinessAdminModel->RetailProduct3Months();
			if($retail_product_buyers['success'] == 'true'){
				$retail_product_buyers=$retail_product_buyers['res_arr'];
			}else{
				$retail_product_buyers=array();
			}
			// $this->PrettyPrintArray($retail_product_buyers);	
			$data['retail_product_labels']= array();
			$data['retail_product_sales']=array();   
			if(count($retail_product_buyers) == 0){
				for($i=3;$i>=1;$i--){
					$tt =date("m-Y",strtotime("-$i Months"));
					// echo " ".$tt;
					array_push($data['retail_product_labels'],$tt);
					array_push($data['retail_product_sales'],0);
				}
			
			}else if(count($retail_product_buyers) == 3){
				foreach($retail_product_buyers as $k=>$v){
					array_push($data['retail_product_labels'],$v['month']);
					array_push($data['retail_product_sales'],$v['Buyers']);
				}
			}else{
				for($j=3;$j>=1;$j--){
					// $j=$j+1;
					$tt = date("m-Y",strtotime("-$j Months"));
					// echo " ".$tt;
					$e=0;
					foreach($retail_product_buyers as $k=>$v){
						if($tt == $v['month']){
							array_push($data['retail_product_labels'],$v['month']);
							array_push($data['retail_product_sales'],$v['Buyers']);
						$e=1;
						}
					}
					if($e == 0){
						array_push($data['retail_product_labels'],$tt);
						array_push($data['retail_product_sales'],0);
					}
				
				}
			}
			$data['mtd_retail_prod']=$this->BusinessAdminModel->RetailProductMTD();
			$data['mtd_retail_prod']=$data['mtd_retail_prod']['res_arr'][0]['Buyers'];
			$data['mtd_retail_prod_perc']=$this->BusinessAdminModel->RetailProductMTDP();
			if($data['mtd_retail_prod_perc']['res_arr'][0]['Buyers'] == 0){
				$data['mtd_retail_prod_perc']=0;
			}else{
				$data['mtd_retail_prod_perc']=($data['mtd_retail_prod']/$data['mtd_retail_prod_perc']['res_arr'][0]['Buyers'])*100;
			}
				// $this->PrettyPrintArray($data['mtd_retail_prod_perc']);
			
			//Packages Pemetrations Trends
			$package_buyers=$this->BusinessAdminModel->Package3Months();
			if($package_buyers['success'] == 'true'){
				$package_buyers=$package_buyers['res_arr'];
			}else{
				$package_buyers=array();
			}
			// $this->PrettyPrintArray($package_buyers);	
			$data['package_labels']= array();
			$data['package_sales']=array();   
			if(count($package_buyers) == 0){
				for($i=3;$i>=1;$i--){
					$tt =date("m-Y",strtotime("-$i Months"));
					array_push($data['package_labels'],$tt);
					array_push($data['package_sales'],0);
				}
			}else if(count($package_buyers) == 3){
				foreach($package_buyers as $k=>$v){
					array_push($data['package_labels'],$v['month']);
					array_push($data['package_sales'],$v['Buyers']);
				}
			}else{
				for($j=3;$j>=1;$j--){
					$tt = date("m-Y",strtotime("-$j Months"));
					$e1=0;
					foreach($package_buyers as $k=>$v){
						if($tt == $v['month']){
							array_push($data['package_labels'],$v['month']);
							array_push($data['package_sales'],$v['Buyers']);
							$e1=1;
						}
					}
					if($e1 == 0){
						array_push($data['package_labels'],$tt);
						array_push($data['package_sales'],0);
					}
				
				}
			}
			
			$data['mtd_package']=$this->BusinessAdminModel->PackageMTD();
			$data['mtd_package']=$data['mtd_package']['res_arr'][0]['Buyers'];
			// $this->PrettyPrintArray($data['mtd_package']);	
			$data['mtd_package_perc']=$this->BusinessAdminModel->PackageMTDP();
			if($data['mtd_package_perc']['res_arr'][0]['Buyers'] == 0){
				$data['mtd_package_perc']=0;
			}else{
				$data['mtd_package_perc']=($data['mtd_package']/$data['mtd_package_perc']['res_arr'][0]['Buyers'])*100;
			}
			

			// Product Sales Ratio
			$product_buyers=$this->BusinessAdminModel->Product3Months();
			if($product_buyers['success'] == 'true'){
				$product_buyers=$product_buyers['res_arr'];
			}else{
				$product_buyers=array();
			}
			$trans_product_buyers=$this->BusinessAdminModel->TransactionProduct3Months();
			if($trans_product_buyers['success'] == 'true'){
				$trans_product_buyers=$trans_product_buyers['res_arr'];
			}else{
				$trans_product_buyers=array();
			}
			// $this->PrettyPrintArray($trans_product_buyers);
			for($i=0;$i<count($product_buyers);$i++){
				// $this->PrettyPrintArray($product_buyers[$i]['month']);
				if($product_buyers[$i]['month']==$trans_product_buyers[$i]['month']){
					// echo "hii";
					$product_buyers[$i]['Buyers']=Round(($product_buyers[$i]['Buyers']/$trans_product_buyers[$i]['Buyers'])*100,2);
				}
			}
			
			// $this->PrettyPrintArray($product_buyers);	
			$data['product3_labels']= array();
			$data['product3_sales']=array();   
			if(count($product_buyers) == 0){
				for($i=3;$i>=1;$i--){
					$tt =date("m-Y",strtotime("-$i Months"));
					// echo " ".$tt;
					array_push($data['product3_labels'],$tt);
					array_push($data['product3_sales'],0);
				}
			
			}else if(count($product_buyers) == 3){
				foreach($product_buyers as $k=>$v){
					array_push($data['product3_labels'],$v['month']);
					array_push($data['product3_sales'],$v['Buyers']);
				}
			}else{
				for($j=3;$j>=1;$j--){
					// $j=$j+1;
					$tt = date("m-Y",strtotime("-$j Months"));
					// echo " ".$tt;
					$e2=0;
					foreach($product_buyers as $k=>$v){
						if($tt == $v['month']){
							array_push($data['product3_labels'],$v['month']);
							array_push($data['product3_sales'],$v['Buyers']);
							$e2 = 1;
						}
					}
					if($e2 == 0){
						array_push($data['product3_labels'],$tt);
						array_push($data['product3_sales'],0);
					}
				}
			}
			$data['mtd_product_ratio']=$this->BusinessAdminModel->RetailProductMTD();
			$data['mtd_product_ratio']=$data['mtd_product_ratio']['res_arr'][0]['Buyers'];
			$data['mtd_product_ratio_perc']=$this->BusinessAdminModel->RetailProductMTDP();
			if($data['mtd_product_ratio_perc']['res_arr'][0]['Buyers'] == 0){
				$data['mtd_product_ratio_perc']=0;
			}else{
				$data['mtd_product_ratio_perc']=($data['mtd_product_ratio']/$data['mtd_product_ratio_perc']['res_arr'][0]['Buyers'])*100;
			}

			$all_cust=$this->BusinessAdminModel->TransactionAllCust();
			if(isset($all_cust['res_arr'])){
			$data['UU']=count($all_cust['res_arr']);
			}else{
			    $data['UU']=0;
			}
			$cust_service=$this->BusinessAdminModel->TransactionAllCustService();
			if(isset($cust_service['res_arr'])){
			    $cust_service=$cust_service['res_arr'];
			}else{
			    $cust_service=array();
			}
			$pcust_serv=array();
			$pcust_otc=array();
				foreach($cust_service as $key=>$value){
					// $this->PrettyPrintArray($value);
					array_push($pcust_serv,$value['Buyers']);
				}
			// $this->PrettyPrintArray($cust_service);
			$cust_otc=$this->BusinessAdminModel->TransactionAllCustOtc();
			if(isset($cust_otc['res_arr'])){
			$cust_otc=$cust_otc['res_arr'];
			}else{
			    $cust_otc=array();
			}
			foreach($cust_otc as $key=>$value){
				// $this->PrettyPrintArray($value);
				array_push($pcust_otc,$value['Buyers']);
			}
			$data['pnon_buyers']=$this->BusinessAdminModel->ProductNonBuyers3Months();
			if($data['pnon_buyers']['success']=='false'){
				$data['pnon_buyers']=array();
			}else{
				$data['pnon_buyers']=$data['pnon_buyers']['res_arr'];
			}
			$data['label_non_buyers']=array();
			$data['sale_non_buyers']=array();
			foreach($data['pnon_buyers'] as $key=>$value){
				array_push($data['label_non_buyers'],$value['month']);
				array_push($data['sale_non_buyers'],$value['Buyers']);
			}
			// $this->PrettyPrintArray($data['label_non_buyers']);
			$count = count(array_intersect($pcust_serv, $pcust_otc));
			$serv1=count($pcust_serv)-$count; 
			
			$data['non_user']=$serv1;
			$data['otcdays']=$this->BusinessAdminModel->Transaction90CustOtc();
			if(isset($data['otcdays']['res_arr'])){
			$data['otcdays']=count($data['otcdays']['res_arr']);
			}else{
			    $data['otcdays']=0;
			}
			// Appointment dashboard
			$appoinmentperday=$this->BusinessAdminModel->AppointmentPerDay();
			if($appoinmentperday['success'] == 'false'){
				$appoinmentperday=array();
			}else{
				$appoinmentperday = $appoinmentperday['res_arr'];
			}
			
			$data['appointment_per_day_labels']= array();
			$data['appointmnet_per_day_count']=array();
			if(count($appoinmentperday) == 0){
				for($i=6;$i>=0;$i--){
					$tt = date("Y-m-d",strtotime("-$i day"));
					array_push($data['appointment_per_day_labels'],$tt);
					array_push($data['appointmnet_per_day_count'],0);
				}
			
			}else if(count($appoinmentperday) == 7){
				foreach($appoinmentperday as $k=>$v){
					array_push($data['appointment_per_day_labels'],$v['Date']);
					array_push($data['appointmnet_per_day_count'],$v['Cust']);
				}
			}else{
				for($i=6;$i>=0;$i--){
					// $j=$i+1;
					$tt = date("Y-m-d",strtotime("-$i day"));
					// echo " ".$tt;
					$s=0;
					foreach($appoinmentperday as $k=>$v){

						if($tt == $v['Date']){
							array_push($data['appointment_per_day_labels'],$v['Date']);
							array_push($data['appointmnet_per_day_count'],$v['Cust']);
						$s=1;
						}
					}
					if($s == 0){
							array_push($data['appointment_per_day_labels'],$tt);
							array_push($data['appointmnet_per_day_count'],0);
					}
				}
			}
						// echo "<pre>";
						// $this->PrettyPrintArray($data['appointment_per_day_labels']);
						// $this->PrettyPrintArray($data['appointmnet_per_day_count']);
				// Appointment MTD
				$data['appointment_mtd']=$this->BusinessAdminModel->AppointmentMTD();
				$data['appointment_mtd']=$data['appointment_mtd']['res_arr'][0]['Cust'];
				$data['appointment_lmtd']=$this->BusinessAdminModel->AppointmentLMTD();
				$data['appointment_lmtd']=$data['appointment_lmtd']['res_arr'][0]['Cust'];
			// Appointment Cancellation
			$appointment_cancellation=$this->BusinessAdminModel->AppointmentCancellation();
			if($appointment_cancellation['success'] == 'false'){
				$appointment_cancellation=array();
			}else{
				$appointment_cancellation = $appointment_cancellation['res_arr'];
			}
			$data['appointment_cancellation_labels']= array();
			$data['appointmnet_cancellation_count']=array();
			if(count($appointment_cancellation) == 0){
				for($i=6;$i>=0;$i--){
					$tt = date("Y-m-d",strtotime("-$i day"));
					array_push($data['appointment_cancellation_labels'],$tt);
					array_push($data['appointmnet_cancellation_count'],0);
				}
			
			}else if(count($appointment_cancellation) == 7){
				foreach($appointment_cancellation as $k=>$v){
					array_push($data['appointment_cancellation_labels'],$v['Date']);
					array_push($data['appointmnet_cancellation_count'],$v['Cust']);
				}
			}else{
				for($i=6;$i>=0;$i--){
					// $j=$i+1;
					$tt = date("Y-m-d",strtotime("-$i day"));
					$s5=0;
					foreach($appointment_cancellation as $k=>$v){
						if($tt == $v['Date']){
							array_push($data['appointment_cancellation_labels'],$v['Date']);
							array_push($data['appointmnet_cancellation_count'],$v['Cust']);
							$s5=1;
						}
					}
					if($s5 == 0){
						array_push($data['appointment_cancellation_labels'],$tt);
						array_push($data['appointmnet_cancellation_count'],0);
					}
				
				}
			}

				$data['appointment_cancellation_mtd']=$this->BusinessAdminModel->AppointmentCancellationMTD();
				$data['appointment_cancellation_mtd']=$data['appointment_cancellation_mtd']['res_arr'][0]['Cust'];
				$data['appointment_cancellation_lmtd']=$this->BusinessAdminModel->AppointmentCancellationLMTD();
				$data['appointment_cancellation_lmtd']=$data['appointment_cancellation_lmtd']['res_arr'][0]['Cust'];

			// Appointment Visit
			$appointment_visit=$this->BusinessAdminModel->AppointmentVisit();
			if($appointment_visit['success'] == 'false'){
				$appointment_visit=array();
			}else{
				$appointment_visit = $appointment_visit['res_arr'];
			}
			$data['appointment_visit_labels']= array();
			$data['appointment_visit_count']=array();
			if(count($appointment_visit) == 0){
				for($i=6;$i>=0;$i--){
					$tt = date("Y-m-d",strtotime("-$i day"));
					array_push($data['appointment_visit_labels'],$tt);
					array_push($data['appointment_visit_count'],0);
				}
			
			}else if(count($appointment_visit) == 7){
				foreach($appointment_visit as $k=>$v){
					array_push($data['appointment_visit_labels'],$v['Date']);
					array_push($data['appointment_visit_count'],$v['Cust']);
				}
			}else{
				for($i=6;$i>=0;$i--){
					// $j=$i+1;
					$tt = date("Y-m-d",strtotime("-$i day"));
					$s6=0;
					foreach($appointment_visit as $k=>$v){
						if($tt == $v['Date']){
							array_push($data['appointment_visit_labels'],$v['Date']);
							array_push($data['appointment_visit_count'],$v['Cust']);
							$s6=1;
						}
					}
					if($s6 == 0){
						array_push($data['appointment_visit_labels'],$tt);
						array_push($data['appointment_visit_count'],0);
					}
				
				}
			}

				$data['appointment_visit_mtd']=$this->BusinessAdminModel->AppointmentVisitMTD();
				$data['appointment_visit_mtd']=$data['appointment_visit_mtd']['res_arr'][0]['Cust'];
				$data['appointment_visit_lmtd']=$this->BusinessAdminModel->AppointmentVisitLMTD();
				$data['appointment_visit_lmtd']=$data['appointment_visit_lmtd']['res_arr'][0]['Cust'];
				// $this->PrettyPrintArray($data['appointment_cancellation']);

			// Appointment No Show
			$appointment_noshow=$this->BusinessAdminModel->AppointmentNoShow();
			if($appointment_noshow['success'] == 'false'){
				$appointment_noshow=array();
			}else{
				$appointment_noshow = $appointment_noshow['res_arr'];
			}
			$data['appointment_noshow_labels']= array();
			$data['appointment_noshow_count']=array();
			if(count($appointment_noshow) == 0){
				for($i=6;$i>=0;$i--){
					$tt = date("Y-m-d",strtotime("-$i day"));
					array_push($data['appointment_noshow_labels'],$tt);
					array_push($data['appointment_noshow_count'],0);
				}
			
			}else if(count($appointment_noshow) == 7){
				foreach($appointment_noshow as $k=>$v){
					array_push($data['appointment_noshow_labels'],$v['Date']);
					array_push($data['appointment_noshow_count'],$v['Cust']);
				}
			}else{
				for($i=6;$i>=0;$i--){
					// $j=$i+1;
					$tt = date("Y-m-d",strtotime("-$i day"));
					$s7=0;
					foreach($appointment_noshow as $k=>$v){
						if($tt == $v['Date']){
							array_push($data['appointment_noshow_labels'],$v['Date']);
							array_push($data['appointment_noshow_count'],$v['Cust']);
							$s7=1;
						}
					}
					if($s7==0){
						array_push($data['appointment_noshow_labels'],$tt);
						array_push($data['appointment_noshow_count'],0);
					}
				
				}
			}

				$data['appointment_noshow_mtd']=$this->BusinessAdminModel->AppointmentShowMTD();
				$data['appointment_noshow_mtd']=$data['appointment_noshow_mtd']['res_arr'][0]['Cust'];
				$data['appointment_noshow_lmtd']=$this->BusinessAdminModel->AppointmentShowLMTD();
				$data['appointment_noshow_lmtd']=$data['appointment_noshow_lmtd']['res_arr'][0]['Cust'];

			$data['collapsed']="true";
            }
            
            $this->load->view('business_admin/ba_dashboard_view',$data);
        }
        else{
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }
}

	//Default Page for Business Admin
	public function index(){
		if($this->IsLoggedIn('business_admin')){
			$data = $this->GetDataForAdmin("Dashboard");
			$data['sidebar_collapsed'] = "true";
			$where = array(
					'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id'],
					'business_outlet_id' => $this->session->userdata['outlets']['current_outlet']
				);
			if(isset($data['selected_outlet']) || !empty($data['selected_outlet'])){
				$data['cards_data']['sales'] = $this->TopCardsData('sales');
                $data['cards_data']['productsales'] = $this->TopCardsData('productsales');
                $data['cards_data']['customer_count'] = $this->TopCardsData('customer_count');
                $data['cards_data']['new_customer'] = $this->TopCardsData('new_customer');
                $data['cards_data']['total_visits'] = $this->TopCardsData('total_visits');
                $data['cards_data']['expenses'] = $this->TopCardsData('expenses');
                $data['cards_data']['yest_expenses'] = $this->TopCardsData('yest_expenses');
                $data['cards_data']['existing_customer'] = $this->TopCardsData('existing_customer');
                $data['categories']  = $this->GetCategories($this->session->userdata['outlets']['current_outlet']);
                $data['cards_data']['payment_wise'] = $this->SalesPaymentWiseData();
                // $this->PrettyPrintArray($data['cards_data']['payment_wise']);    
                $where = array(
                    'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id'],
                    'business_outlet_id' => $this->session->userdata['outlets']['current_outlet']
                );
                $data['total_virtual_wallet']= $this->BusinessAdminModel->GetTotalVirtualWalletBalance($where);
                $data['total_virtual_wallet'] = $data['total_virtual_wallet']['res_arr'][0]['total_virtual_wallet_balance'];
                
                $data['yesterday_sales']=$this->TopCardsData('yesterday_sales');
                $data['bill']= $this->BusinessAdminModel->GetTodaysBill($where);
								$data['bill']= $data['bill']['res_arr'];
								
								$data['paid_back']=$this->BusinessAdminModel->GetDailyAmountPaidBack($where);
								$data['paid_back']=$data['paid_back']['res_arr'][0]['paid_back'];
                // $this->PrettyPrintArray($data['bill']);
                $data['card_data']= $this->BusinessAdminModel->TodayPackageSales($where);
                $data['card_data']=$data['card_data']['res_arr'];
                $data['virtual_wallet_sales']= $this->BusinessAdminModel->TodayVirtualWalletSales($where);
                $data['today_virtual_wallet_sales']=$data['virtual_wallet_sales']['res_arr'][0]['wallet_sales'];
                
                //pending Amount card data
                $data['total_due_amount']= $this->BusinessAdminModel->GetTotalDueAmount($where);
                $data['total_due_amount']=$data['total_due_amount']['res_arr'][0];
                
                $due_amount=$this->BusinessAdminModel->GetTodaysDueAmount($where);
								$data['due_amount']=$due_amount['res_arr'][0]['due_amount'];
								$package_due_amount=$this->BusinessAdminModel->GetTodaysPackageDueAmount($where);
								$data['package_due_amount']=$package_due_amount['res_arr'][0]['package_due_amount'];

                $data['pending_amount_received']=$this->BusinessAdminModel->GetPendingAmountReceived($where);
                $data['pending_amount_received']=$data['pending_amount_received']['res_arr'][0]['pending_amount_received'];
                $data['monthly_due_amount']=$this->BusinessAdminModel->GetMonthlyDueAmount($where);
                $data['monthly_due_amount']=$data['monthly_due_amount']['res_arr'][0]['monthly_due_amount'];
                $data['monthly_pending_amount_received']=$this->BusinessAdminModel->GetMonthlyPendingAmountReceived($where);
                $data['monthly_pending_amount_received']=$data['monthly_pending_amount_received']['res_arr'][0]['monthly_pending_amount_received'];
                $data['last_month_due_amount']=$this->BusinessAdminModel->GetLastMonthDueAmount($where);
                $data['last_month_due_amount']=$data['last_month_due_amount']['res_arr'][0]['last_month_due_amount'];
                $data['last_month_pending_amount_received']=$this->BusinessAdminModel->GetLastMonthPendingAmountReceived($where);
                $data['last_month_pending_amount_received']=$data['last_month_pending_amount_received']['res_arr'][0]['last_month_pending_amount_received'];
                
                //loyalty top card data
                $data['loyalty_payment']=$this->BusinessAdminModel->GetLoyaltyAmountReceived($where);
                $data['loyalty_payment']=$data['loyalty_payment']['res_arr'][0]['loyalty_wallet'];
                $data['loyalty_points_given']=$this->BusinessAdminModel->GetLoyaltyPointsGiven($where);
                $data['loyalty_points_given']=$data['loyalty_points_given']['res_arr'][0]['loyalty_points'];
                $data['monthly_loyalty_payment']=$this->BusinessAdminModel->GetMonthlyLoyaltyAmountReceived($where);
                $data['monthly_loyalty_payment']=$data['monthly_loyalty_payment']['res_arr'][0]['monthly_loyalty_wallet'];
                $data['monthly_loyalty_points_given']=$this->BusinessAdminModel->GetMonthlyLoyaltyPointsGiven($where);
                $data['monthly_loyalty_points_given']=$data['monthly_loyalty_points_given']['res_arr'][0]['monthly_loyalty_points'];
                $data['last_month_loyalty_payment']=$this->BusinessAdminModel->GetLastMonthLoyaltyAmountReceived($where);
                $data['last_month_loyalty_payment']=$data['last_month_loyalty_payment']['res_arr'][0]['last_month_loyalty_wallet'];
                $data['last_month_loyalty_points_given']=$this->BusinessAdminModel->GetLastMonthLoyaltyPointsGiven($where);
                $data['last_month_loyalty_points_given']=$data['last_month_loyalty_points_given']['res_arr'][0]['last_month_loyalty_points'];
                $data['sales_till_date']=$this->BusinessAdminModel->GetMonthlySalesTillDate($where);
								$data['sales_till_date']=$data['sales_till_date']['res_arr'][0]['sales_till_date'];
                $data['product_sales_till_date']=$this->BusinessAdminModel->GetMonthlyProductSalesTillDate($where);
								$data['product_sales_till_date']=$data['product_sales_till_date']['res_arr'][0]['product_sales_till_date'];
                $data['package_sales_till_date']=$this->BusinessAdminModel->PackageSalesTillDate($where);
                $data['package_sales_till_date']=$data['package_sales_till_date']['res_arr'][0]['package_sales'];
                
                //monthly top card data 
                $data['cards_data']['monthly_sales']=$this->MonthlyTopCardsData('monthly_sales');
                $data['cards_data']['monthly_expenses']=$this->MonthlyTopCardsData('monthly_expenses');
                
                $data['monthly_sales_payment_wise']=$this->BusinessAdminModel->GetMonthlySalesPaymentWiseData($where);
                $data['monthly_sales_payment_wise']=$data['monthly_sales_payment_wise']['res_arr'];
                $data['monthly_package_sales_payment_wise']=$this->BusinessAdminModel->GetMonthlyPackageSalesPaymentWiseData($where);
                $data['monthly_package_sales_payment_wise']=$data['monthly_package_sales_payment_wise']['res_arr'];
                // $this->PrettyPrintArray($data['monthly_sales_payment_wise']);
                // exit;
                //Last months card data
                $data['cards_data']['last_month_sales'] = $this->LastMonthTopCardsData('last_month_sales');
                $data['cards_data']['last_month_product_sales'] = $this->LastMonthTopCardsData('last_month_product_sales');
                $data['cards_data']['last_month_expense'] = $this->LastMonthTopCardsData('last_month_expense');
                
                $data['last_month_package_sales']=$this->BusinessAdminModel->PackageSalesForLastMonth($where);
                $data['last_month_package_sales']=$data['last_month_package_sales']['res_arr'][0]['package_sales'];
                $data['last_month_sales_payment_wise']=$this->BusinessAdminModel->GetLastMonthSalesPaymentWiseData($where);
                $data['last_month_sales_payment_wise']=$data['last_month_sales_payment_wise']['res_arr'];
                
                $data['package_payment_wise'] = $this->BusinessAdminModel->GetPackageSalesPaymentWiseData($where);
                $data['package_payment_wise']=$data['package_payment_wise']['res_arr'];
                // $this->PrettyPrintArray($data['last_month_sales_payment_wise']);
                // exit;
                $data['last_month_package_sales_payment_wise'] = $this->BusinessAdminModel->GetLastMonthPackageSalesPaymentWiseData($where);
                $data['last_month_package_sales_payment_wise']=$data['last_month_package_sales_payment_wise']['res_arr'];
                // $this->PrettyPrintArray($data['last_month_package_sales_payment_wise']);
								// exit;
								// $this->PrettyPrintArray($data['last_month_sales_payment_wise']);
                
                //balance_to be paid back
                $data['balance_paidback']=$this->BusinessAdminModel->GetBalancePaidBack($where);
                if(isset($data['balance_paidback'])){
                    $data['balance_paidback']=(int)$data['balance_paidback']['res_arr'][0]['balance_paid'];
                }else{
                    $data['balance_paidback']=(int)0;
				}
				

				// Analytics 
					//Revenue Trends 
						// revenue client
						$revenueclient=$this->BusinessAdminModel->LastSevenDaysSalesClient();
						if($revenueclient['success'] == 'false'){
							$revenueclient=array();
						}else{
							$revenueclient = $revenueclient['res_arr'];
						}
						// $this->PrettyPrintArray(($revenue_client));
						$data['client_revenue_labels']= array();
						$data['client_revenue_sales']=array();
						if(count($revenueclient) == 0){
							for($i=6;$i>=0;$i--){
								$tt = date("Y-m-d",strtotime("-$i day"));
								array_push($data['client_revenue_labels'],$tt);
								array_push($data['client_revenue_sales'],0);
							}
						
						}else if(count($revenueclient) == 7){
							foreach($revenueclient as $k=>$v){
								array_push($data['client_revenue_labels'],$v['bill_date']);
								array_push($data['client_revenue_sales'],$v['total_sales']);
							}
						}else{
							for($i=6;$i>=0;$i--){
								// $j=$i+1;
								$tt = date("Y-m-d",strtotime("-$i day"));
								// echo " ".$tt;
								$s0=0;
								foreach($revenueclient as $k=>$v){
									if($tt == $v['bill_date']){
										array_push($data['client_revenue_labels'],$v['bill_date']);
										array_push($data['client_revenue_sales'],$v['total_sales']);
										$s0=1;
									}
								}
								if($s0 == 0){		
									array_push($data['client_revenue_labels'],$tt);
									array_push($data['client_revenue_sales'],0);
								}
							}
						}
						// revenue visit
						$revenue_client=$this->BusinessAdminModel->LastSevenDaysSales();
						if($revenue_client['success'] == 'false'){
							$revenue_client=array();
						}else{
							$revenue_client = $revenue_client['res_arr'];
						}
						
						// $this->PrettyPrintArray(($revenue_client));
						$data['revenue_labels']= array();
						$data['revenue_sales']=array();
						if(count($revenue_client) == 0){
							for($i=6;$i>=0;$i--){
								$tt = date("Y-m-d",strtotime("-$i day"));
								array_push($data['revenue_labels'],$tt);
								array_push($data['revenue_sales'],0);
							}
						
						}else if(count($revenue_client) == 7){
							foreach($revenue_client as $k=>$v){
								array_push($data['revenue_labels'],$v['bill_date']);
								array_push($data['revenue_sales'],$v['total_sales']);
							}
						}else{
							for($i=6;$i>=0;$i--){
								// $j=$i+1;
								$tt = date("Y-m-d",strtotime("-$i day"));
								// echo " ".$tt;
								$s1=0;
								foreach($revenue_client as $k=>$v){
									if($tt == $v['bill_date']){
										array_push($data['revenue_labels'],$v['bill_date']);
										array_push($data['revenue_sales'],$v['total_sales']);
									$s1=1;
									}
								}
								if($s1==0){
									array_push($data['revenue_labels'],$tt);
									array_push($data['revenue_sales'],0);
								}
							}
						}
						
						// // revenue per day
						$revenue_perday=$this->BusinessAdminModel->LastSevenDaysSalesPerDay();
						if($revenue_perday['success'] == 'false'){
							$revenue_perday=array();
						}else{
							$revenue_perday = $revenue_perday['res_arr'];
						}
						// $this->PrettyPrintArray(($revenue_perday));
						$data['perday_labels']= array();
						$data['perday_sales']=array();
						if(count($revenue_perday) == 0){
							for($i=6;$i>=0;$i--){
								$tt = date("Y-m-d",strtotime("-$i day"));
								array_push($data['perday_labels'],$tt);
								array_push($data['perday_sales'],0);
							}
						
						}else if(count($revenue_perday) == 7){
							foreach($revenue_perday as $k=>$v){
								array_push($data['perday_labels'],$v['bill_date']);
								array_push($data['perday_sales'],$v['total_sales']);
							}
						}else{
							for($i=6;$i>=0;$i--){
								// $j=$i+1;
								$tt = date("Y-m-d",strtotime("-$i day"));
								// echo " ".$tt;
								$s2=0;
								foreach($revenue_perday as $k=>$v){
									if($tt == $v['bill_date']){
										array_push($data['perday_labels'],$v['bill_date']);
										array_push($data['perday_sales'],$v['total_sales']);
										$s2=1;
									}
								}
								if($s2==0){
									array_push($data['perday_labels'],$tt);
									array_push($data['perday_sales'],0);
								}
							
							}
						}
						$Srevenue_perday=$this->BusinessAdminModel->LastSevenDaysServiceSalesPerDay();
						if($Srevenue_perday['success'] == 'false'){
							$Srevenue_perday=array();
						}else{
							$Srevenue_perday = $Srevenue_perday['res_arr'];
						}
						// $this->PrettyPrintArray(($Srevenue_perday));
						$data['service_perday_labels']= array();
						$data['service_perday_sales']=array();
						if(count($Srevenue_perday) == 0){
							for($i=6;$i>=0;$i--){
								$tt = date("Y-m-d",strtotime("-$i day"));
								array_push($data['service_perday_labels'],$tt);
								array_push($data['service_perday_sales'],0);
							}
						
						}else if(count($Srevenue_perday) == 7){
							foreach($Srevenue_perday as $k=>$v){
								array_push($data['service_perday_labels'],$v['bill_date']);
								array_push($data['service_perday_sales'],$v['total_sales']);
							}
						}else{
							for($i=6;$i>=0;$i--){
								// $j=$i+1;
								$tt = date("Y-m-d",strtotime("-$i day"));
								// echo " ".$tt;
								$s3=0;
								foreach($Srevenue_perday as $k=>$v){
									if($tt == $v['bill_date']){
										array_push($data['service_perday_labels'],$v['bill_date']);
										array_push($data['service_perday_sales'],$v['total_sales']);
										$s3=1;
									}
								}
								if($s3 == 0){
									array_push($data['service_perday_labels'],$tt);
									array_push($data['service_perday_sales'],0);
								}
							
							}
						}
						$Prevenue_perday=$this->BusinessAdminModel->LastSevenDaysProductSalesPerDay();
						if($Prevenue_perday['success'] == 'false'){
							$Prevenue_perday=array();
						}else{
							$Prevenue_perday = $Prevenue_perday['res_arr'];
						}
						// $this->PrettyPrintArray(($Prevenue_perday));
						$data['otc_perday_labels']= array();
						$data['otc_perday_sales']=array();
						if(count($Prevenue_perday) == 0){
							for($i=6;$i>=0;$i--){
								$tt = date("Y-m-d",strtotime("-$i day"));
								array_push($data['otc_perday_labels'],$tt);
								array_push($data['otc_perday_sales'],0);
							}
						
						}else if(count($Prevenue_perday) == 7){
							foreach($Prevenue_perday as $k=>$v){
								array_push($data['otc_perday_labels'],$v['bill_date']);
								array_push($data['otc_perday_sales'],$v['total_sales']);
							}
						}else{
							for($i=6;$i>=0;$i--){
								// $j=$i+1;
								$tt = date("Y-m-d",strtotime("-$i day"));
								$s4=0;
								foreach($Prevenue_perday as $k=>$v){
									if($tt == $v['bill_date']){
										array_push($data['otc_perday_labels'],$v['bill_date']);
										array_push($data['otc_perday_sales'],$v['total_sales']);
										$s4=1;
									}
								}
								if($s4 == 0){
									array_push($data['otc_perday_labels'],$tt);
									array_push($data['otc_perday_sales'],0);
								}
							
							}
						}


						// revenue client
						$data['client_avg']=$this->BusinessAdminModel->Client7DaysAvg();
						// $this->PrettyPrintArray($data['client_avg']);
						// die;
						if($data['client_avg']['res_arr'][0]['total_sales'] != null){
							$data['client_avg']=$data['client_avg']['res_arr'][0]['total_sales'];
						}else{
							$data['client_avg']=0;
						}
						
						$data['lmtd_client_avg']=$this->BusinessAdminModel->Client7DaysAvgLMTD();
						if($data['lmtd_client_avg']['res_arr'][0]['total_sales'] != null){
							$data['lmtd_client_avg']=$data['lmtd_client_avg']['res_arr'][0]['total_sales'];
						}else{
							$data['lmtd_client_avg']=0;
						}
						$data['mtd_client_avg']=$this->BusinessAdminModel->Client7DaysAvgMTD();
						if($data['mtd_client_avg']['res_arr'][0]['total_sales'] != null){
							$data['mtd_client_avg']=$data['mtd_client_avg']['res_arr'][0]['total_sales'];
						}else{
							$data['mtd_client_avg']=0;
						}
						// revenue visit
						$data['visit_avg']=$this->BusinessAdminModel->Vist7DaysAvg();
						if($data['visit_avg']['res_arr'][0]['total_sales'] != null){
							$data['visit_avg']=$data['visit_avg']['res_arr'][0]['total_sales'];
						}else{
							$data['visit_avg']=0;
						}
						$data['lmtd_visit_avg']=$this->BusinessAdminModel->Vist7DaysAvgLMTD();
						if($data['lmtd_visit_avg']['res_arr'][0]['total_sales'] != null){
							$data['lmtd_visit_avg']=$data['lmtd_visit_avg']['res_arr'][0]['total_sales'];
						}else{
							$data['lmtd_visit_avg']=0;
						}
						$data['mtd_visit_avg']=$this->BusinessAdminModel->Vist7DaysAvgMTD();
						if($data['mtd_visit_avg']['res_arr'][0]['total_sales'] != null){
							$data['mtd_visit_avg']=$data['mtd_visit_avg']['res_arr'][0]['total_sales'];
						}else{
							$data['mtd_visit_avg']=0;
						}

						$data['revenue_avg']=$this->BusinessAdminModel->Revenue7DaysAvg();
						if($data['revenue_avg']['res_arr'][0]['total_sales'] != null){
							$data['revenue_avg']=$data['revenue_avg']['res_arr'][0]['total_sales'];
						}else{
							$data['revenue_avg']=0;
						}
						$data['mtd_revenue_avg']=$this->BusinessAdminModel->RevenueMTDAvg();
						if($data['mtd_revenue_avg']['res_arr'][0]['total_sales'] != null){
							$data['mtd_revenue_avg']=$data['mtd_revenue_avg']['res_arr'][0]['total_sales'];
						}else{
							$data['mtd_revenue_avg']=0;
						}
						$data['lmtd_revenue_avg']=$this->BusinessAdminModel->RevenueLMTDAvg();
						if($data['lmtd_revenue_avg']['res_arr'][0]['total_sales'] != null){
							$data['lmtd_revenue_avg']=$data['lmtd_revenue_avg']['res_arr'][0]['total_sales'];
						}else{
							$data['lmtd_revenue_avg']=0;
						}

						$data['service_avg']=$this->BusinessAdminModel->Service7DaysAvg();
						if($data['service_avg']['res_arr'][0]['total_sales'] != null){
							$data['service_avg']=$data['service_avg']['res_arr'][0]['total_sales'];
						}else{
							$data['service_avg']=0;
						}
						$data['mtd_service_avg']=$this->BusinessAdminModel->ServiceMTDAvg();
						if($data['mtd_service_avg']['res_arr'][0]['total_sales'] != null){
							$data['mtd_service_avg']=$data['mtd_service_avg']['res_arr'][0]['total_sales'];
						}else{
							$data['mtd_service_avg']=0;
						}
						$data['lmtd_service_avg']=$this->BusinessAdminModel->ServiceLMTDAvg();
						if($data['lmtd_service_avg']['res_arr'][0]['total_sales'] != null){
							$data['lmtd_service_avg']=$data['lmtd_service_avg']['res_arr'][0]['total_sales'];
						}else{
							$data['lmtd_service_avg']=0;
						}


						$data['otc_avg']=$this->BusinessAdminModel->Product7DaysAvg();
						if($data['otc_avg']['res_arr'][0]['total_sales'] != null){
							$data['otc_avg']=$data['otc_avg']['res_arr'][0]['total_sales'];
						}else{
							$data['otc_avg']=0;
						}
						$data['mtd_otc_avg']=$this->BusinessAdminModel->ProductMTDAvg();
						if($data['mtd_otc_avg']['res_arr'][0]['total_sales'] != null){
							$data['mtd_otc_avg']=$data['mtd_otc_avg']['res_arr'][0]['total_sales'];
						}else{
							$data['mtd_otc_avg']=0;
						}
						$data['lmtd_otc_avg']=$this->BusinessAdminModel->ProductLMTDAvg();
						if($data['lmtd_otc_avg']['res_arr'][0]['total_sales'] != null){
							$data['lmtd_otc_avg']=$data['lmtd_otc_avg']['res_arr'][0]['total_sales'];
						}else{
							$data['lmtd_otc_avg']=0;
						}
						// $this->PrettyPrintArray($data['visit_avg']);
			//Product Pemetrations Trends
			$retail_product_buyers=$this->BusinessAdminModel->RetailProduct3Months();
			if($retail_product_buyers['success'] == 'true'){
				$retail_product_buyers=$retail_product_buyers['res_arr'];
			}else{
				$retail_product_buyers=array();
			}
			// $this->PrettyPrintArray($retail_product_buyers);	
			$data['retail_product_labels']= array();
			$data['retail_product_sales']=array();   
			if(count($retail_product_buyers) == 0){
				for($i=3;$i>=1;$i--){
					$tt =date("m-Y",strtotime("-$i Months"));
					// echo " ".$tt;
					array_push($data['retail_product_labels'],$tt);
					array_push($data['retail_product_sales'],0);
				}
			
			}else if(count($retail_product_buyers) == 3){
				foreach($retail_product_buyers as $k=>$v){
					array_push($data['retail_product_labels'],$v['month']);
					array_push($data['retail_product_sales'],$v['Buyers']);
				}
			}else{
				for($j=3;$j>=1;$j--){
					// $j=$j+1;
					$tt = date("m-Y",strtotime("-$j Months"));
					// echo " ".$tt;
					$e=0;
					foreach($retail_product_buyers as $k=>$v){
						if($tt == $v['month']){
							array_push($data['retail_product_labels'],$v['month']);
							array_push($data['retail_product_sales'],$v['Buyers']);
						$e=1;
						}
					}
					if($e == 0){
						array_push($data['retail_product_labels'],$tt);
						array_push($data['retail_product_sales'],0);
					}
				
				}
			}
			$data['mtd_retail_prod']=$this->BusinessAdminModel->RetailProductMTD();
			$data['mtd_retail_prod']=$data['mtd_retail_prod']['res_arr'][0]['Buyers'];
			$data['mtd_retail_prod_perc']=$this->BusinessAdminModel->RetailProductMTDP();
			if($data['mtd_retail_prod_perc']['res_arr'][0]['Buyers'] == 0){
				$data['mtd_retail_prod_perc']=0;
			}else{
				$data['mtd_retail_prod_perc']=($data['mtd_retail_prod']/$data['mtd_retail_prod_perc']['res_arr'][0]['Buyers'])*100;
			}
				// $this->PrettyPrintArray($data['mtd_retail_prod_perc']);
			
			//Packages Pemetrations Trends
			$package_buyers=$this->BusinessAdminModel->Package3Months();
			if($package_buyers['success'] == 'true'){
				$package_buyers=$package_buyers['res_arr'];
			}else{
				$package_buyers=array();
			}
			// $this->PrettyPrintArray($package_buyers);	
			$data['package_labels']= array();
			$data['package_sales']=array();   
			if(count($package_buyers) == 0){
				for($i=3;$i>=1;$i--){
					$tt =date("m-Y",strtotime("-$i Months"));
					array_push($data['package_labels'],$tt);
					array_push($data['package_sales'],0);
				}
			}else if(count($package_buyers) == 3){
				foreach($package_buyers as $k=>$v){
					array_push($data['package_labels'],$v['month']);
					array_push($data['package_sales'],$v['Buyers']);
				}
			}else{
				for($j=3;$j>=1;$j--){
					$tt = date("m-Y",strtotime("-$j Months"));
					$e1=0;
					foreach($package_buyers as $k=>$v){
						if($tt == $v['month']){
							array_push($data['package_labels'],$v['month']);
							array_push($data['package_sales'],$v['Buyers']);
							$e1=1;
						}
					}
					if($e1 == 0){
						array_push($data['package_labels'],$tt);
						array_push($data['package_sales'],0);
					}
				
				}
			}
			
			$data['mtd_package']=$this->BusinessAdminModel->PackageMTD();
			$data['mtd_package']=$data['mtd_package']['res_arr'][0]['Buyers'];
			// $this->PrettyPrintArray($data['mtd_package']);	
			$data['mtd_package_perc']=$this->BusinessAdminModel->PackageMTDP();
			if($data['mtd_package_perc']['res_arr'][0]['Buyers'] == 0){
				$data['mtd_package_perc']=0;
			}else{
				$data['mtd_package_perc']=($data['mtd_package']/$data['mtd_package_perc']['res_arr'][0]['Buyers'])*100;
			}
			

			// Product Sales Ratio
			$product_buyers=$this->BusinessAdminModel->Product3Months();
			if($product_buyers['success'] == 'true'){
				$product_buyers=$product_buyers['res_arr'];
			}else{
				$product_buyers=array();
			}
			$trans_product_buyers=$this->BusinessAdminModel->TransactionProduct3Months();
			if($trans_product_buyers['success'] == 'true'){
				$trans_product_buyers=$trans_product_buyers['res_arr'];
			}else{
				$trans_product_buyers=array();
			}
			// $this->PrettyPrintArray($trans_product_buyers);
			for($i=0;$i<count($product_buyers);$i++){
				// $this->PrettyPrintArray($product_buyers[$i]['month']);
				if($product_buyers[$i]['month']==$trans_product_buyers[$i]['month']){
					// echo "hii";
					$product_buyers[$i]['Buyers']=Round(($product_buyers[$i]['Buyers']/$trans_product_buyers[$i]['Buyers'])*100,2);
				}
			}
			
			// $this->PrettyPrintArray($product_buyers);	
			$data['product3_labels']= array();
			$data['product3_sales']=array();   
			if(count($product_buyers) == 0){
				for($i=3;$i>=1;$i--){
					$tt =date("m-Y",strtotime("-$i Months"));
					// echo " ".$tt;
					array_push($data['product3_labels'],$tt);
					array_push($data['product3_sales'],0);
				}
			
			}else if(count($product_buyers) == 3){
				foreach($product_buyers as $k=>$v){
					array_push($data['product3_labels'],$v['month']);
					array_push($data['product3_sales'],$v['Buyers']);
				}
			}else{
				for($j=3;$j>=1;$j--){
					// $j=$j+1;
					$tt = date("m-Y",strtotime("-$j Months"));
					// echo " ".$tt;
					$e2=0;
					foreach($product_buyers as $k=>$v){
						if($tt == $v['month']){
							array_push($data['product3_labels'],$v['month']);
							array_push($data['product3_sales'],$v['Buyers']);
							$e2 = 1;
						}
					}
					if($e2 == 0){
						array_push($data['product3_labels'],$tt);
						array_push($data['product3_sales'],0);
					}
				}
			}
			$data['mtd_product_ratio']=$this->BusinessAdminModel->RetailProductMTD();
			$data['mtd_product_ratio']=$data['mtd_product_ratio']['res_arr'][0]['Buyers'];
			$data['mtd_product_ratio_perc']=$this->BusinessAdminModel->RetailProductMTDP();
			if($data['mtd_product_ratio_perc']['res_arr'][0]['Buyers'] == 0){
				$data['mtd_product_ratio_perc']=0;
			}else{
				$data['mtd_product_ratio_perc']=($data['mtd_product_ratio']/$data['mtd_product_ratio_perc']['res_arr'][0]['Buyers'])*100;
			}

			$all_cust=$this->BusinessAdminModel->TransactionAllCust();
			if(isset($all_cust['res_arr'])){
			$data['UU']=count($all_cust['res_arr']);
			}else{
			    $data['UU']=0;
			}
			$cust_service=$this->BusinessAdminModel->TransactionAllCustService();
			if(isset($cust_service['res_arr'])){
			    $cust_service=$cust_service['res_arr'];
			}else{
			    $cust_service=array();
			}
			$pcust_serv=array();
			$pcust_otc=array();
				foreach($cust_service as $key=>$value){
					// $this->PrettyPrintArray($value);
					array_push($pcust_serv,$value['Buyers']);
				}
			// $this->PrettyPrintArray($cust_service);
			$cust_otc=$this->BusinessAdminModel->TransactionAllCustOtc();
			if(isset($cust_otc['res_arr'])){
			$cust_otc=$cust_otc['res_arr'];
			}else{
			    $cust_otc=array();
			}
			foreach($cust_otc as $key=>$value){
				// $this->PrettyPrintArray($value);
				array_push($pcust_otc,$value['Buyers']);
			}
			$data['pnon_buyers']=$this->BusinessAdminModel->ProductNonBuyers3Months();
			if($data['pnon_buyers']['success']=='false'){
				$data['pnon_buyers']=array();
			}else{
				$data['pnon_buyers']=$data['pnon_buyers']['res_arr'];
			}
			$data['label_non_buyers']=array();
			$data['sale_non_buyers']=array();
			foreach($data['pnon_buyers'] as $key=>$value){
				array_push($data['label_non_buyers'],$value['month']);
				array_push($data['sale_non_buyers'],$value['Buyers']);
			}
			// $this->PrettyPrintArray($data['label_non_buyers']);
			$count = count(array_intersect($pcust_serv, $pcust_otc));
			$serv1=count($pcust_serv)-$count; 
			
			$data['non_user']=$serv1;
			$data['otcdays']=$this->BusinessAdminModel->Transaction90CustOtc();
			if(isset($data['otcdays']['res_arr'])){
			$data['otcdays']=count($data['otcdays']['res_arr']);
			}else{
			    $data['otcdays']=0;
			}
			// Appointment dashboard
			$appoinmentperday=$this->BusinessAdminModel->AppointmentPerDay();
			if($appoinmentperday['success'] == 'false'){
				$appoinmentperday=array();
			}else{
				$appoinmentperday = $appoinmentperday['res_arr'];
			}
			
			$data['appointment_per_day_labels']= array();
			$data['appointmnet_per_day_count']=array();
			if(count($appoinmentperday) == 0){
				for($i=6;$i>=0;$i--){
					$tt = date("Y-m-d",strtotime("-$i day"));
					array_push($data['appointment_per_day_labels'],$tt);
					array_push($data['appointmnet_per_day_count'],0);
				}
			
			}else if(count($appoinmentperday) == 7){
				foreach($appoinmentperday as $k=>$v){
					array_push($data['appointment_per_day_labels'],$v['Date']);
					array_push($data['appointmnet_per_day_count'],$v['Cust']);
				}
			}else{
				for($i=6;$i>=0;$i--){
					// $j=$i+1;
					$tt = date("Y-m-d",strtotime("-$i day"));
					// echo " ".$tt;
					$s=0;
					foreach($appoinmentperday as $k=>$v){

						if($tt == $v['Date']){
							array_push($data['appointment_per_day_labels'],$v['Date']);
							array_push($data['appointmnet_per_day_count'],$v['Cust']);
						$s=1;
						}
					}
					if($s == 0){
							array_push($data['appointment_per_day_labels'],$tt);
							array_push($data['appointmnet_per_day_count'],0);
					}
				}
			}
						// echo "<pre>";
						// $this->PrettyPrintArray($data['appointment_per_day_labels']);
						// $this->PrettyPrintArray($data['appointmnet_per_day_count']);
				// Appointment MTD
				$data['appointment_mtd']=$this->BusinessAdminModel->AppointmentMTD();
				$data['appointment_mtd']=$data['appointment_mtd']['res_arr'][0]['Cust'];
				$data['appointment_lmtd']=$this->BusinessAdminModel->AppointmentLMTD();
				$data['appointment_lmtd']=$data['appointment_lmtd']['res_arr'][0]['Cust'];
			// Appointment Cancellation
			$appointment_cancellation=$this->BusinessAdminModel->AppointmentCancellation();
			if($appointment_cancellation['success'] == 'false'){
				$appointment_cancellation=array();
			}else{
				$appointment_cancellation = $appointment_cancellation['res_arr'];
			}
			$data['appointment_cancellation_labels']= array();
			$data['appointmnet_cancellation_count']=array();
			if(count($appointment_cancellation) == 0){
				for($i=6;$i>=0;$i--){
					$tt = date("Y-m-d",strtotime("-$i day"));
					array_push($data['appointment_cancellation_labels'],$tt);
					array_push($data['appointmnet_cancellation_count'],0);
				}
			
			}else if(count($appointment_cancellation) == 7){
				foreach($appointment_cancellation as $k=>$v){
					array_push($data['appointment_cancellation_labels'],$v['Date']);
					array_push($data['appointmnet_cancellation_count'],$v['Cust']);
				}
			}else{
				for($i=6;$i>=0;$i--){
					// $j=$i+1;
					$tt = date("Y-m-d",strtotime("-$i day"));
					$s5=0;
					foreach($appointment_cancellation as $k=>$v){
						if($tt == $v['Date']){
							array_push($data['appointment_cancellation_labels'],$v['Date']);
							array_push($data['appointmnet_cancellation_count'],$v['Cust']);
							$s5=1;
						}
					}
					if($s5 == 0){
						array_push($data['appointment_cancellation_labels'],$tt);
						array_push($data['appointmnet_cancellation_count'],0);
					}
				
				}
			}

				$data['appointment_cancellation_mtd']=$this->BusinessAdminModel->AppointmentCancellationMTD();
				$data['appointment_cancellation_mtd']=$data['appointment_cancellation_mtd']['res_arr'][0]['Cust'];
				$data['appointment_cancellation_lmtd']=$this->BusinessAdminModel->AppointmentCancellationLMTD();
				$data['appointment_cancellation_lmtd']=$data['appointment_cancellation_lmtd']['res_arr'][0]['Cust'];

			// Appointment Visit
			$appointment_visit=$this->BusinessAdminModel->AppointmentVisit();
			if($appointment_visit['success'] == 'false'){
				$appointment_visit=array();
			}else{
				$appointment_visit = $appointment_visit['res_arr'];
			}
			$data['appointment_visit_labels']= array();
			$data['appointment_visit_count']=array();
			if(count($appointment_visit) == 0){
				for($i=6;$i>=0;$i--){
					$tt = date("Y-m-d",strtotime("-$i day"));
					array_push($data['appointment_visit_labels'],$tt);
					array_push($data['appointment_visit_count'],0);
				}
			
			}else if(count($appointment_visit) == 7){
				foreach($appointment_visit as $k=>$v){
					array_push($data['appointment_visit_labels'],$v['Date']);
					array_push($data['appointment_visit_count'],$v['Cust']);
				}
			}else{
				for($i=6;$i>=0;$i--){
					// $j=$i+1;
					$tt = date("Y-m-d",strtotime("-$i day"));
					$s6=0;
					foreach($appointment_visit as $k=>$v){
						if($tt == $v['Date']){
							array_push($data['appointment_visit_labels'],$v['Date']);
							array_push($data['appointment_visit_count'],$v['Cust']);
							$s6=1;
						}
					}
					if($s6 == 0){
						array_push($data['appointment_visit_labels'],$tt);
						array_push($data['appointment_visit_count'],0);
					}
				
				}
			}

				$data['appointment_visit_mtd']=$this->BusinessAdminModel->AppointmentVisitMTD();
				$data['appointment_visit_mtd']=$data['appointment_visit_mtd']['res_arr'][0]['Cust'];
				$data['appointment_visit_lmtd']=$this->BusinessAdminModel->AppointmentVisitLMTD();
				$data['appointment_visit_lmtd']=$data['appointment_visit_lmtd']['res_arr'][0]['Cust'];
				// $this->PrettyPrintArray($data['appointment_cancellation']);

			// Appointment No Show
			$appointment_noshow=$this->BusinessAdminModel->AppointmentNoShow();
			if($appointment_noshow['success'] == 'false'){
				$appointment_noshow=array();
			}else{
				$appointment_noshow = $appointment_noshow['res_arr'];
			}
			$data['appointment_noshow_labels']= array();
			$data['appointment_noshow_count']=array();
			if(count($appointment_noshow) == 0){
				for($i=6;$i>=0;$i--){
					$tt = date("Y-m-d",strtotime("-$i day"));
					array_push($data['appointment_noshow_labels'],$tt);
					array_push($data['appointment_noshow_count'],0);
				}
			
			}else if(count($appointment_noshow) == 7){
				foreach($appointment_noshow as $k=>$v){
					array_push($data['appointment_noshow_labels'],$v['Date']);
					array_push($data['appointment_noshow_count'],$v['Cust']);
				}
			}else{
				for($i=6;$i>=0;$i--){
					// $j=$i+1;
					$tt = date("Y-m-d",strtotime("-$i day"));
					$s7=0;
					foreach($appointment_noshow as $k=>$v){
						if($tt == $v['Date']){
							array_push($data['appointment_noshow_labels'],$v['Date']);
							array_push($data['appointment_noshow_count'],$v['Cust']);
							$s7=1;
						}
					}
					if($s7==0){
						array_push($data['appointment_noshow_labels'],$tt);
						array_push($data['appointment_noshow_count'],0);
					}
				
				}
			}

				$data['appointment_noshow_mtd']=$this->BusinessAdminModel->AppointmentShowMTD();
				$data['appointment_noshow_mtd']=$data['appointment_noshow_mtd']['res_arr'][0]['Cust'];
				$data['appointment_noshow_lmtd']=$this->BusinessAdminModel->AppointmentShowLMTD();
				$data['appointment_noshow_lmtd']=$data['appointment_noshow_lmtd']['res_arr'][0]['Cust'];
			}
		
			$this->load->view('business_admin/ba_dashboard_view',$data);
		}
		else{
			$data['title'] = "Login";
			$this->load->view('business_admin/ba_login_view',$data);
		}
	}

	public function LowStockItems(){
		if($this->IsLoggedIn('business_admin')){
			if(isset($_GET) && !empty($_GET)){
				$where = array(
					'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id'],
					'business_outlet_id' => $this->session->userdata['outlets']['current_outlet']
				);
				

				$data = $this->BusinessAdminModel->GetLowStockItems($where);
				if($data['success'] == 'true'){	
					header("Content-type: application/json");
					print(json_encode($data['res_arr'], JSON_PRETTY_PRINT));
					die;	
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}		
	}

	public function GenderDistribution(){
		if($this->IsLoggedIn('business_admin')){
			if(isset($_GET) && !empty($_GET)){
				$where = array(
					'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id'],
					'business_outlet_id' => $this->session->userdata['outlets']['current_outlet']
				);
				$data = $this->BusinessAdminModel->GetGenderDistribution($where);
				if($data['success'] == 'true'){	
					header("Content-type: application/json");
					print(json_encode($data['res_arr'], JSON_PRETTY_PRINT));
					die;	
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin");
		}			
	}

	public function AgeDistribution(){
		if($this->IsLoggedIn('business_admin')){
			if(isset($_GET) && !empty($_GET)){
				
				$where = array(
					'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id'],
					'business_outlet_id' => $this->session->userdata['outlets']['current_outlet']
				);

				$data = $this->BusinessAdminModel->GetAgeDistribution($where);
				
				if($data['success'] == 'true'){	
					header("Content-type: application/json");
					print(json_encode($data['res_arr'], JSON_PRETTY_PRINT));
					die;	
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin");
		}			
	}

	public function BarChartYearly(){
		if($this->IsLoggedIn('business_admin')){
			if(isset($_GET) && !empty($_GET)){
				$where = array(
					'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id'],
					'business_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
					'yearly_kpi'  => $_GET['yearly_kpi']
				);

				if(isset($_GET['category_id']) && !empty($_GET['category_id'])){
					$where['category_id'] = $_GET['category_id'];
				}

				if(isset($_GET['sub_category_id']) && !empty($_GET['sub_category_id'])){
					$where['sub_category_id'] = $_GET['sub_category_id'];
				}

				if(isset($_GET['service_id']) && !empty($_GET['service_id'])){
					$where['service_id'] = $_GET['service_id'];
				}				

				$data = $this->BusinessAdminModel->BarChartYearly($where);
				
				header("Content-type: application/json");
				print(json_encode($data['res_arr'], JSON_PRETTY_PRINT));
				die;	
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin");
		}	
	}

	public function GetRCVData(){
		if($this->IsLoggedIn('business_admin')){
			$where = array(
				'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id'],
				'business_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
				'from_date' => date('Y-m-d', strtotime('-15 days')),
				'to_date'   => date('Y-m-d')
			);

			if(isset($_GET['fromdate']) && isset($_GET['todate'])){
				$where = array(
					'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id'],
					'business_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
					'from_date' => $_GET['fromdate'],
					'to_date'   => $_GET['todate']
				);				

				$data['revenue'] = $this->RevenueBarChart($where);
				$data['customer'] = $this->CustomerBarChart($where);
				$data['visits'] = $this->VisitsBarChart($where);
				
				header("Content-type: application/json");
				print(json_encode($data, JSON_PRETTY_PRINT));
				die;	
			}
			else{
				$data['revenue'] = $this->RevenueBarChart($where);
				$data['customer'] = $this->CustomerBarChart($where);
				$data['visits'] = $this->VisitsBarChart($where);
				$data['package_revenue']= $this->BusinessAdminModel->GetPackageRevenueBarChart($where);
				$data['package_revenue']=$data['package_revenue']['res_arr'];
				// print_r($data['package_revenue']);
				// exit;
				header("Content-type: application/json");
				print(json_encode($data, JSON_PRETTY_PRINT));
				die;
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin");
		}	
	}

	private function RevenueBarChart($where){
		if($this->IsLoggedIn('business_admin')){
			$result = $this->BusinessAdminModel->GetRevenueBarChart($where);
			if($result['success'] == 'true'){	
				return $result['res_arr'];
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin");
		}	
	}

	private function CustomerBarChart($where){
		if($this->IsLoggedIn('business_admin')){
			$result = $this->BusinessAdminModel->GetCustomerBarChart($where);
			if($result['success'] == 'true'){	
				return $result['res_arr'];
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin");
		}	
	}

	private function VisitsBarChart($where){
		if($this->IsLoggedIn('business_admin')){
			$result = $this->BusinessAdminModel->GetVisitsBarChart($where);
			if($result['success'] == 'true'){	
				return $result['res_arr'];
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin");
		}	
	}

	private function TopCardsData($type){
		if($this->IsLoggedIn('business_admin')){
			$data = array(
						'type'               => $type,
						'business_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
						'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id']
			);
			
			$result = $this->BusinessAdminModel->GetTopCardsData($data);
			if($result['success'] == 'true'){	
				return $result['res_arr'];
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin");
		}	
	}
	//monthly card data
	private function MonthlyTopCardsData($type){
		if($this->IsLoggedIn('business_admin')){
			$data = array(
						'type'               => $type,
						'business_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
						'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id']
			);
			
			$result = $this->BusinessAdminModel->GetTopCardsDataMonthly($data);
			if($result['success'] == 'true'){	
				return $result['res_arr'];
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin");
		}	
	}
	//last month data
	private function LastMonthTopCardsData($type){
		if($this->IsLoggedIn('business_admin')){
			$data = array(
						'type'               => $type,
						'business_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
						'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id']
			);
			
			$result = $this->BusinessAdminModel->GetTopCardsDataForLastMonth($data);
			if($result['success'] == 'true'){	
				return $result['res_arr'];
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin");
		}	
	}
	//
	private function SalesPaymentWiseData(){
		if($this->IsLoggedIn('business_admin')){
			$data = array(
						'business_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
						'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id']
			);
			
			$result = $this->BusinessAdminModel->GetSalesPaymentWiseData($data);
			if($result['success'] == 'true'){	
				return $result['res_arr'];
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}	
	}


	private function GetBusinessAdminPackages(){
		if($this->IsLoggedIn('business_admin')){
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
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}	
	}

	private function GetBusinessAdminDetails(){
		if($this->IsLoggedIn('business_admin')){
			$data = $this->BusinessAdminModel->DetailsById($this->session->userdata['logged_in']['business_admin_id'],'mss_business_admin','business_admin_id');
			if($data['success'] == 'true'){	
				return $data['res_arr'];
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}	
	}

	private function GetCurrentOutlet($outlet_id){
		if($this->IsLoggedIn('business_admin')){
			$data = $this->BusinessAdminModel->DetailsById($outlet_id,'mss_business_outlets','business_outlet_id');
			if($data['success'] == 'true'){	
				return $data['res_arr'];
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}	
	}

	private function GetBusinessOutlets(){
		if($this->IsLoggedIn('business_admin')){
			$where = array(
				'business_outlet_business_admin' => $this->session->userdata['logged_in']['business_admin_id'],
				'business_outlet_status'					=>1
			);
			$data = $this->BusinessAdminModel->MultiWhereSelect('mss_business_outlets',$where);
			if($data['success'] == 'true' ){	
				return $data['res_arr'];
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}	
	}

	/*	
		Basic Template

		if($this->IsLoggedIn('business_admin')){
			if(isset($_POST) && !empty($_POST)){

			}
			else{
				$this->load->view();
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	*/

	public function MenuManagement(){
        if($this->IsLoggedIn('business_admin')){
            $data = $this->GetDataForAdmin("Menu Management");
            if(isset($data['selected_outlet']) || !empty($data['selected_outlet'])){
                $data['categories']  = $this->GetCategories($this->session->userdata['outlets']['current_outlet']);
                $data['sub_categories']  = $this->GetSubCategories($this->session->userdata['outlets']['current_outlet']);
                $data['services']  = $this->GetServices($this->session->userdata['outlets']['current_outlet']);
                $data['all_otc'] = $this->GetOTCServices($this->session->userdata['outlets']['current_outlet']);
                // $this->PrettyPrintArray($data['all_otc']);
                $data['raw_materials'] = $this->GetRawMaterials($this->session->userdata['outlets']['current_outlet']);
                $where=array('business_admin_id'=>$this->session->userdata['logged_in']['business_admin_id']);
                $data['permission']= $this->BusinessAdminModel->MultiWhereSelect('mss_user_permission',$where);
                $data['permission']=$data['permission']['res_arr'][0];
                //
                $data['recommend_status']=$this->BusinessAdminModel->DetailsById($this->session->userdata['outlets']['current_outlet'],'mss_business_outlets',$this->session->userdata['outlets']['current_outlet']);
                $data['recommend_status']=$data['recommend_status']['res_arr'];
                $data['service_details_outlet']=$this->BusinessAdminModel->ServiceDetailsByOutlet();
                $data['recommend_services']=$this->BusinessAdminModel->GetTopServices();
                // $this->PrettyPrintArray($data['service_details_outlet']);
                if($data['recommend_services']['success'] == 'true'){
                    $data['recommend_services']=$data['recommend_services']['res_arr'];
                    $data['recommend_services_status']=1;
                }else{
                    $data['recommend_services_status']=0;
                }
                $data['recommended_services']=$this->BusinessAdminModel->RecommendeServiceProduct();
                // $this->PrettyPrintArray($data['recommended_services']);
                if($data['recommended_services']['success']=='true')
                {
                    $data['recommended_services']=$data['recommended_services']['res_arr'];
                    $data['recommended_services_status']=1;
                }else{
                    $data['recommended_services_status']=0;
                }
            }
            $where = array(
				'category_business_admin_id' => $this->session->userdata['logged_in']['business_admin_id'],
				'category_is_active'         => TRUE,
				'category_business_outlet_id'=> $this->session->userdata['outlets']['current_outlet'],
				'category_type'=>'Products'
			);

			$data['otccategories'] = $this->BusinessAdminModel->MultiWhereSelect('mss_categories',$where);
			if($data['otccategories']['success'] == 'true'){	
				 $data['otccategories']=$data['otccategories']['res_arr'];
			}
			
			$data['otc_sub_categories']  = $this->BusinessAdminModel->SubCategoriesOtc();
			if($data['otc_sub_categories']['success'] == 'true'){	
				$data['otc_sub_categories']=$data['otc_sub_categories']['res_arr'];
			}
            $data['sidebar_collapsed'] = "true";
            $this->load->view('business_admin/ba_menu_management_view',$data);
        }
        else{
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }
	}

	public function ExpensesSummaryRange(){
		if($this->IsLoggedIn('business_admin')){
			if(isset($_GET) && !empty($_GET)){
				$where = array(
					'expense_type_business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id'],
					'expense_type_business_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
					'date_from' => $_GET['from_date'],
					'date_to'   => $_GET['to_date']
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
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}		
	}

	public function TopExpensesSummaryRange(){
		if($this->IsLoggedIn('business_admin')){
			if(isset($_GET) && !empty($_GET)){
				$where = array(
					'expense_type_business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id'],
					'expense_type_business_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
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
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}		
	}

    public function ConfigExpense(){  
        if($this->IsLoggedIn('business_admin')){
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
                // $this->PrettyPrintArray($_POST);
                // exit;
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
                    'outlet_id'=>$this->session->userdata['outlets']['current_outlet']
                );
                $da=$this->BusinessAdminModel->SelectMaxIdExpense($datam);
                $da=$da['res_arr'][0]['id'];
								$expense_id= "E1010Y".$this->session->userdata['outlets']['current_outlet'].($da+1);
								
									$outlet_id = $this->session->userdata['outlets']['current_outlet'];
									$admin_id= $this->session->userdata['logged_in']['business_admin_id'];
									$expense_counter = $this->db->select('*')->from('mss_business_outlets')->where('business_outlet_id',$outlet_id)->get()->row_array();			
          				$expense_unique_serial_id = strval("EA".strval(100+$admin_id) . "O" . strval($outlet_id) . "-" . strval($expense_counter['business_outlet_expense_counter']));

                $data = array(
                'expense_unique_serial_id' =>$expense_unique_serial_id, 
                'expense_date'      => date('Y-m-d'),
                'expense_type_id'   => $this->input->post('expense_type_id'),
                'item_name'         => $this->input->post('item_name'),
                'total_amount'      =>$this->input->post('total_amt'),
                'amount'            => $this->input->post('amount'),
                'remarks'           => $this->input->post('remarks'),
                'payment_type'      => $this->input->post('payment_type'),
                'payment_to'        => $payment_to,
                'payment_to_name'   => $payment_to_name,
                'payment_mode'      => $this->input->post('payment_mode'),
                'pending_amount'    => $this->input->post('pending_amount'),
                'expense_status'    => $this->input->post('expense_status'),
                'employee_name'     => $this->input->post('employee_name'),
                'invoice_number'    => $this->input->post('invoice_number'),
                'bussiness_outlet_id'=>$this->session->userdata['outlets']['current_outlet']
              );
              $data1 = array(
                'expense_date'      => date('Y-m-d'),
                'expense_type_id'   => $this->input->post('expense_type_id'),
                'item_name'         => $this->input->post('item_name'),
                'total_amount'      =>$this->input->post('total_amt'),
                'amount'            => $this->input->post('amount'),
                'remarks'           => $this->input->post('remarks'),
                'payment_type'      => $this->input->post('payment_type'),
                'payment_to'        => $payment_to,
                'payment_to_name'   => $payment_to_name,
                'payment_mode'      => $this->input->post('payment_mode'),
                'pending_amount'    => $this->input->post('pending_amount'),
                'expense_status'    => $this->input->post('expense_status'),
                'employee_name'     => $this->input->post('employee_name'),
                'invoice_number'    => $this->input->post('invoice_number'),
                'bussiness_outlet_id'=>$this->session->userdata['outlets']['current_outlet']
              );
    
             
              if($_POST['expense_status'] != 'Unpaid'){
                $result = $this->BusinessAdminModel->Insert($data,'mss_expenses');
                }
                if($_POST['expense_status'] != 'Paid'){
                    $result = $this->BusinessAdminModel->Insert($data1,'mss_expenses_unpaid');
								}
								
								//update expense counter
								$query = "UPDATE mss_business_outlets SET business_outlet_expense_counter = business_outlet_expense_counter + 1 WHERE business_outlet_id = ".$outlet_id."";
								$this->db->query($query);

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
            $data = $this->GetDataForAdmin("Expense");
            if(isset($data['selected_outlet']) || !empty($data['selected_outlet'])){
                $datam=array(
                    'outlet_id'=>$this->session->userdata['outlets']['current_outlet']
                );
              $data['expense_types'] = $this->GetExpensesTypes($this->session->userdata['outlets']['current_outlet']);
              $data['expenses_summary'] = $this->GetExpensesSummary($this->session->userdata['outlets']['current_outlet']); 
              $data['all_expenses'] = $this->AllExpenses($this->session->userdata['outlets']['current_outlet']);  
              $data['business_admin'] = array('employee_name' => $this->session->userdata['logged_in']['business_admin_name']);
              $data['employees']=$this->BusinessAdminModel->GetBusinessAdminEmployees($datam);
              $data['employees']=$data['employees']['res_arr'];
              $data['vendors']=$this->BusinessAdminModel->GetVendorDetails($datam);
              $data['vendors']=$data['vendors']['res_arr'];
              $data['pending_payment']=$this->BusinessAdminModel->GetPendingPayment();
              $data['pending_payment']=$data['pending_payment']['res_arr'];
            }
            // $this->PrettyPrintArray($_SESSION);
						// exit;
						$data['sidebar_collapsed']="true";
            $this->load->view('business_admin/ba_config_expense_category_view',$data);
          }
        }
        else{
          $this->LogoutUrl(base_url()."BusinessAdmin/");
        }
    }

	private function GetExpensesSummary($outlet_id){
		if($this->IsLoggedIn('business_admin')){
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
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}		
	}

	private function AllExpenses($outlet_id){
		if($this->IsLoggedIn('business_admin')){
			$where = array(
				'expense_type_business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id'],
				'expense_type_business_outlet_id' => $outlet_id
			);

			$data = $this->BusinessAdminModel->GetAllExpenses($where);
			
			if($data['success'] == 'true'){	
				return $data['res_arr'];
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}		
	}

	public function Inventory(){
		if($this->IsLoggedIn('business_admin')){
				$data = $this->GetDataForAdmin("Add Composition");
				if(isset($data['selected_outlet']) || !empty($data['selected_outlet'])){
						$data['categories']  = $this->GetCategories($this->session->userdata['outlets']['current_outlet']);
						$data['sub_categories']  = $this->GetSubCategories($this->session->userdata['outlets']['current_outlet']);
						$data['services']  = $this->GetServices($this->session->userdata['outlets']['current_outlet']);
						$data['raw_materials'] = $this->GetRawMaterialsIn($this->session->userdata['outlets']['current_outlet']);
						// $this->PrettyPrintArray($data['raw_materials']); 
						$data['compositions'] = $this->GetCompositions($this->session->userdata['outlets']['current_outlet']);
				}
				$this->load->view('business_admin/ba_inventory_management_view',$data);
		}
		else{
				$this->LogoutUrl(base_url()."index.php/BusinessAdmin/");
		}
}


	public function AddCategory(){
		if($this->IsLoggedIn('business_admin')){
			if(isset($_POST) && !empty($_POST)){
				$this->form_validation->set_rules('category_name', 'Category Name', 'trim|required|max_length[100]');
				$this->form_validation->set_rules('category_description', 'Category Description', 'trim');
                $this->form_validation->set_rules('category_type','Category Type','required');
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
						'category_name' 				=> $this->input->post('category_name'),
						'category_description' 	=> $this->input->post('category_description'),
						'category_business_admin_id' => $this->session->userdata['logged_in']['business_admin_id'],
						'category_business_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
						'category_type'         => $this->input->post('category_type')
					);

					$result = $this->BusinessAdminModel->Insert($data,'mss_categories');
						
					if($result['success'] == 'true'){
						$this->ReturnJsonArray(true,false,"Category added successfully!");
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
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}

	public function AddSubCategory(){
		if($this->IsLoggedIn('business_admin')){
			if(isset($_POST) && !empty($_POST)){
				$this->form_validation->set_rules('sub_category_name', 'Sub-Category Name', 'trim|required|max_length[100]');
				$this->form_validation->set_rules('sub_category_description', 'Sub-Category Description', 'trim');
				$this->form_validation->set_rules('sub_category_category_id', 'Category Name', 'trim|required');
				
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
						'sub_category_name' 				=> $this->input->post('sub_category_name'),
						'sub_category_description' 	=> $this->input->post('sub_category_description'),
						'sub_category_category_id' => $this->input->post('sub_category_category_id')
					);

					$result = $this->BusinessAdminModel->Insert($data,'mss_sub_categories');
						
					if($result['success'] == 'true'){
						$this->ReturnJsonArray(true,false,"Sub-Category added successfully!");
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
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}

	public function EditCategory(){
		if($this->IsLoggedIn('business_admin')){
			if(isset($_POST) && !empty($_POST)){
				$this->form_validation->set_rules('category_name', 'Category Name', 'trim|required|max_length[100]');
				$this->form_validation->set_rules('category_description', 'Category Description', 'trim');
				
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
						'category_id'           => $this->input->post('category_id'),
						'category_name' 				=> $this->input->post('category_name'),
						'category_description' 	=> $this->input->post('category_description'),
						'category_type' 	=> $this->input->post('category_type')
					);

					$result = $this->BusinessAdminModel->Update($data,'mss_categories','category_id');
						
					if($result['success'] == 'true'){
						$this->ReturnJsonArray(true,false,"Category updated successfully!");
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
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}

	public function EditSubCategory(){
		if($this->IsLoggedIn('business_admin')){
			if(isset($_POST) && !empty($_POST)){
				$this->form_validation->set_rules('sub_category_name', 'Sub-Category Name', 'trim|required|max_length[100]');
				$this->form_validation->set_rules('sub_category_description', 'Sub-Category Description', 'trim');
				$this->form_validation->set_rules('sub_category_category_id', 'Category Name', 'trim');
				
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
						'sub_category_id'           => $this->input->post('sub_category_id'),
						'sub_category_name' 				=> $this->input->post('sub_category_name'),
						'sub_category_description' 	=> $this->input->post('sub_category_description'),
						'sub_category_category_id'  => $this->input->post('sub_category_category_id')
					);

					$result = $this->BusinessAdminModel->Update($data,'mss_sub_categories','sub_category_id');
						
					if($result['success'] == 'true'){
						$this->ReturnJsonArray(true,false,"Sub-Category updated successfully!");
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
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}

	public function AddEmployee(){
		if($this->IsLoggedIn('business_admin')){
			// $this->PrettyPrintArray($_POST);
			if(isset($_POST) && !empty($_POST)){
				$this->form_validation->set_rules('employee_first_name', 'Employee FirstName', 'trim|required|max_length[50]');
				$this->form_validation->set_rules('employee_last_name', 'Employee Last Name', 'trim|required|max_length[50]');
				$this->form_validation->set_rules('employee_address', 'Employee Address', 'trim|required');
				$this->form_validation->set_rules('employee_mobile', 'Employee Mobile', 'trim|max_length[15]|required');
				$this->form_validation->set_rules('employee_email', 'Employee Email', 'trim|max_length[100]|required');
				$this->form_validation->set_rules('employee_business_outlet', 'Employee Outlet Name', 'trim|required');
				$this->form_validation->set_rules('employee_password', 'Employee Password', 'trim');
				$this->form_validation->set_rules('employee_expertise', 'Employee Expertise', 'trim');
				$this->form_validation->set_rules('employee_role', 'Employee Role', 'trim|required|max_length[50]');
				$this->form_validation->set_rules('employee_date_of_joining', 'Employee Date of Joining', 'trim|required');
				

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
					
          if($_POST['nick_name'] == '' || empty($_POST['nick_name'])){
						$_POST['nick_name']=$_POST['employee_first_name'].' '.$_POST['employee_last_name'];
					}
					$data = array(
						'employee_first_name' 	=> $this->input->post('employee_first_name'),
						'employee_last_name' 	=> $this->input->post('employee_last_name'),
						'employee_nick_name'=>$this->input->post('nick_name'),
						'employee_mobile' 	=> $this->input->post('employee_mobile'),
						'employee_address' 	=> $this->input->post('employee_address'),
						'employee_email' 	=> $this->input->post('employee_email'),
						'employee_business_outlet' 	=> $this->input->post('employee_business_outlet'),
						'employee_password' 	=> password_hash($this->input->post('employee_password'), PASSWORD_DEFAULT),
						'employee_expertise' 	=> $this->input->post('employee_expertise'),
						'employee_role' 	=> $this->input->post('employee_role'),
						'employee_date_of_joining' 	=> $this->input->post('employee_date_of_joining'),
						'employee_business_admin' => $this->session->userdata['logged_in']['business_admin_id']
					);
					// $this->PrettyPrintArray($data);
					$result = $this->BusinessAdminModel->Insert($data,'mss_employees');
						
					if($result['success'] == 'true'){
						$this->ReturnJsonArray(true,false,"Employee added successfully!");
						die;
         			 }
          elseif($result['error'] == 'true'){
          	$this->ReturnJsonArray(false,true,$result['message']);
						die;
          }
				}
			}
			else{
				$data = $this->GetDataForAdmin("Employee Management");
				$data['business_admin_employees']  = $this->GetBusinessAdminEmployees();
				$this->load->view('business_admin/ba_employee_management_view',$data);
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}	
	}


	public function AddService(){
		if($this->IsLoggedIn('business_admin')){
			if(isset($_POST) && !empty($_POST)){
				$this->form_validation->set_rules('service_name', 'Service Name', 'trim|required|max_length[100]');
				$this->form_validation->set_rules('service_price_inr', 'Service Gross Price', 'trim|required');
				$this->form_validation->set_rules('service_est_time', 'Service Estimated Time', 'trim|required|max_length[50]');
				$this->form_validation->set_rules('service_description', 'Service Description', 'trim');
				$this->form_validation->set_rules('service_gst_percentage', 'Service GST Percentage', 'trim|is_natural|required');
				$this->form_validation->set_rules('service_sub_category_id', 'Service Sub-Category Name', 'trim|required');

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
						'service_name' 	=> $this->input->post('service_name'),
						'service_price_inr' 	=> $this->input->post('service_price_inr'),
						'service_est_time' 	=> $this->input->post('service_est_time'),
						'service_description' 	=> $this->input->post('service_description'),
						'service_gst_percentage' 	=> $this->input->post('service_gst_percentage'),
						'service_sub_category_id' 	=> $this->input->post('service_sub_category_id')
					);

					$result = $this->BusinessAdminModel->Insert($data,'mss_services');
						
					if($result['success'] == 'true'){
						$this->ReturnJsonArray(true,false,"Service added successfully!");
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
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}	
	}

	public function AddOTCService(){
        if($this->IsLoggedIn('business_admin')){
            if(isset($_POST) && !empty($_POST)){
                $this->form_validation->set_rules('item_name', 'otc Item Name', 'trim|required|max_length[100]');
                $this->form_validation->set_rules('otc_brand', 'Otc brand', 'trim|required|max_length[100]');
                $this->form_validation->set_rules('otc_unit[]', 'Otc unit', 'trim|required|max_length[50]');
                $this->form_validation->set_rules('otc_sub_category_id', 'Otc Sub-Category Name', 'trim|required');
                $this->form_validation->set_rules('otc_gst_percentage[]', 'OTC GST Percentage', 'trim|required');
                $this->form_validation->set_rules('otc_price_inr[]', 'OTC Gross Price', 'trim|required');
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
                        // $barcode_id = $this->BusinessAdminModel->DetailsById($_POST['otc_barcode'],'mss_services','barcode');
                        // $barcode_id = $barcode_id['res_arr']; 
												// $barcode_id=count($barcode_id)-3;
												
												$inv=0;
												if($_POST['otc_inventory_type'] == 'Raw Material'){
													$inv=1;
												}else{
													$inv=2;
												}
                        foreach($_POST['qty_per_item'] as $key=>$value){
                            $data = array(
                                'inventory_type'                => $this->input->post('otc_inventory_type'),
                                'service_name'                          => $this->input->post('item_name'),
                                'service_sub_category_id'   => $this->input->post('otc_sub_category_id'),
                                'service_brand'                         => $this->input->post('otc_brand'),
                                'barcode'       => $this->input->post('otc_barcode'),
                                'barcode_id'    =>$this->input->post('otc_barcode'),
                                'qty_per_item'                          => $_POST['qty_per_item'][$key],
                                'service_price_inr'                 => $_POST['otc_price_inr'][$key],
                                'service_gst_percentage'        => $_POST['otc_gst_percentage'][$key],
                                'service_unit'                          => $_POST['otc_unit'][$key],
																'service_type'              => "otc",
																'inventory_type_id'					=>$inv
                            );
                            // $this->PrettyPrintArray($data);
                            $result = $this->BusinessAdminModel->Insert($data,'mss_services');
                        }   
                    if($result['success'] == 'true'){
                        $this->ReturnJsonArray(true,false,"OTC item added successfully!");
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
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }   
    }

  public function AddRawMaterial(){
		if($this->IsLoggedIn('business_admin')){
			if(isset($_POST) && !empty($_POST)){
				$this->form_validation->set_rules('raw_material_name', 'Raw Material Item Name', 'trim|required|max_length[100]');
				$this->form_validation->set_rules('raw_material_brand', 'Raw Material brand', 'trim|required|max_length[100]');
				$this->form_validation->set_rules('raw_material_unit', 'Raw Material unit', 'trim|required|max_length[50]');


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
						'raw_material_name' 	=> $this->input->post('raw_material_name'),
						'raw_material_brand' 	=> $this->input->post('raw_material_brand'),
						'raw_material_unit' 	=> $this->input->post('raw_material_unit'),
						'raw_material_business_admin_id' 	=> $this->session->userdata['logged_in']['business_admin_id'],
						'raw_material_business_outlet_id' => $this->session->userdata['outlets']['current_outlet']
					);

					$result = $this->BusinessAdminModel->Insert($data,'mss_raw_material_categories');
						
					if($result['success'] == 'true'){
						$this->ReturnJsonArray(true,false,"Raw Material added successfully!");
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
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}	
	}

	public function AddExpenseCategory(){
		if($this->IsLoggedIn('business_admin')){
			if(isset($_POST) && !empty($_POST)){
				$this->form_validation->set_rules('expense_type', 'Category Name', 'trim|required|max_length[100]');
				$this->form_validation->set_rules('expense_type_description', 'Category Description', 'trim');
				
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
						'expense_type' 				=> $this->input->post('expense_type'),
						'expense_type_description' 	=> $this->input->post('expense_type_description'),
						'expense_type_business_admin_id' => $this->session->userdata['logged_in']['business_admin_id'],
						'expense_type_business_outlet_id' => $this->session->userdata['outlets']['current_outlet']
					);

					$result = $this->BusinessAdminModel->Insert($data,'mss_expense_types');
						
					if($result['success'] == 'true'){
						$this->ReturnJsonArray(true,false,"Expense Category added successfully!");
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
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}	
	}

	public function AddComposition(){
		if($this->IsLoggedIn('business_admin')){
			if(isset($_POST) && !empty($_POST)){
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
					$where = array('service_id' => $this->input->post('service_id'));
					$CheckCompositionExists = $this->CashierModel->CheckCompositionExists($where);
					if($CheckCompositionExists['success'] == 'false' && $CheckCompositionExists['error'] == 'true'){
						if(!empty($_POST['raw_material_id']) && !empty($_POST['consumption_quantity'])){
							$rmc_id   = $this->input->post('raw_material_id');
							$consumption_quantity = $this->input->post('consumption_quantity');
							$counter = 0; //To keep track of no of succcessful submissions
						
							for($i=0;$i<count($rmc_id);$i++){	  	
						  	$data = array(
						  		'service_id' => $this->input->post('service_id'),
					  			'rmc_id'     => $rmc_id[$i],
					  			'consumption_quantity' => $consumption_quantity[$i]
					  		);		
						   	$result = $this->BusinessAdminModel->Insert($data,'mss_raw_composition');
						   	if($result['success'] == 'true'){
						   		$counter = $counter + 1;
						   	}	 
							}

							if($counter == count($rmc_id)){
								$this->ReturnJsonArray(true,false,"Composition added successfully!");
								die;
							}
							else{
								$this->ReturnJsonArray(false,true,"Please Check!");
								die;
							}	
						}
					}
					else if($CheckCompositionExists['success'] == 'true' && $CheckCompositionExists['error'] == 'false'){
						$this->ReturnJsonArray(false,true,"Composition already exists!");
						die;
					}
				} 
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}

	public function EditExpenseCategory(){
		if($this->IsLoggedIn('business_admin')){
			if(isset($_POST) && !empty($_POST)){
				$this->form_validation->set_rules('expense_type', 'Category Name', 'trim|required|max_length[100]');
				$this->form_validation->set_rules('expense_type_description', 'Category Description', 'trim');
				
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
						'expense_type' 				=> $this->input->post('expense_type'),
						'expense_type_description' 	=> $this->input->post('expense_type_description'),
						'expense_type_id'     => $this->input->post('expense_type_id')
					);

					$result = $this->BusinessAdminModel->Update($data,'mss_expense_types','expense_type_id');
						
					if($result['success'] == 'true'){
						$this->ReturnJsonArray(true,false,"Expense Category updated successfully!");
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
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}

	public function EditRawMaterial(){
		if($this->IsLoggedIn('business_admin')){
			if(isset($_POST) && !empty($_POST)){
				$this->form_validation->set_rules('raw_material_name', 'Raw Material Item Name', 'trim|required|max_length[100]');
				$this->form_validation->set_rules('raw_material_brand', 'Raw Material brand', 'trim|required|max_length[100]');
				$this->form_validation->set_rules('raw_material_unit', 'Raw Material unit', 'trim|required|max_length[50]');


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
						'raw_material_name' 	=> $this->input->post('raw_material_name'),
						'raw_material_brand' 	=> $this->input->post('raw_material_brand'),
						'raw_material_unit' 	=> $this->input->post('raw_material_unit'),
						'raw_material_category_id' 	=> $this->input->post('raw_material_category_id')
					);

					$result = $this->BusinessAdminModel->Update($data,'mss_raw_material_categories','raw_material_category_id');
						
					if($result['success'] == 'true'){
						$this->ReturnJsonArray(true,false,"Raw Material updated successfully!");
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
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}	
	}


	public function EditService(){
		if($this->IsLoggedIn('business_admin')){
			if(isset($_POST) && !empty($_POST)){
				$this->form_validation->set_rules('service_name', 'Service Name', 'trim|required|max_length[100]');
				$this->form_validation->set_rules('service_price_inr', 'Service Gross Price', 'trim|required');
				$this->form_validation->set_rules('service_est_time', 'Service Estimated Time', 'trim|required|max_length[50]');
				$this->form_validation->set_rules('service_description', 'Service Description', 'trim');
				$this->form_validation->set_rules('service_gst_percentage', 'Service GST Percentage', 'trim|is_natural|required');
				/*$this->form_validation->set_rules('service_sub_category_id', 'Service Sub-Category Name', 'trim|required');*/
				

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
						'service_id'    => $this->input->post('service_id'),
						'service_name' 	=> $this->input->post('service_name'),
						'service_price_inr' 	=> $this->input->post('service_price_inr'),
						'service_est_time' 	=> $this->input->post('service_est_time'),
						'service_description' 	=> $this->input->post('service_description'),
						'service_gst_percentage' 	=> $this->input->post('service_gst_percentage')
						/*'service_sub_category_id' 	=> $this->input->post('service_sub_category_id')*/
					);

					$result = $this->BusinessAdminModel->Update($data,'mss_services','service_id');
						
					if($result['success'] == 'true'){
						$this->ReturnJsonArray(true,false,"Service updated successfully!");
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
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}	
	}

	public function EditEmployee(){
        if($this->IsLoggedIn('business_admin')){
            if(isset($_POST) && !empty($_POST)){
                $this->form_validation->set_rules('employee_first_name', 'Employee FirstName', 'trim|required|max_length[50]');
                $this->form_validation->set_rules('employee_last_name', 'Employee Last Name', 'trim|required|max_length[50]');
                $this->form_validation->set_rules('employee_address', 'Employee Address', 'trim|required');
                $this->form_validation->set_rules('employee_mobile', 'Employee Mobile', 'trim|max_length[15]|required');
                $this->form_validation->set_rules('employee_email', 'Employee Email', 'trim|max_length[100]|required');
                $this->form_validation->set_rules('employee_business_outlet', 'Employee Outlet Name', 'trim|required');
                $this->form_validation->set_rules('employee_expertise', 'Employee Expertise', 'trim');
                $this->form_validation->set_rules('employee_date_of_joining', 'Employee Date of Joining', 'trim|required');
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
                    if(isset($_POST['employee_weekoff'])){   
											$count=0;
                        $temp=array_chunk($_POST['employee_weekoff'],5);
                      
                        foreach($_POST['year'] as $key => $value){
                            $month=$_POST['month_name'][$key]."-".$value;
                            $data=array(
                                'employee_id'=>$_POST['employee_id'],
                                'month'=>$month
                            );
                            $check=$this->BusinessAdminModel->CheckweekOffDeclared($data);
                            if($check['success'] == 'true'){
                                $data=array(
                                    'employee_id'=>$_POST['employee_id'],
                                    'month'=>$month,
                                    'weekoff'=>json_encode($temp[$key]),
                                    'id'=>$check['res_arr'][0]['id']
                                );
                                $this->BusinessAdminModel->Update($data,'mss_emss_weekoff','id');
                                $weeklyoff=json_encode($temp[$key]);
                            }else{
                                $data=array(
                                    'employee_id'=>$_POST['employee_id'],
                                    'month'=>$month,
                                    'weekoff'=>json_encode($temp[$key]),
                                    'business_outlet_id'=>$this->session->userdata['outlets']['current_outlet'],
                                    'business_admin_id'=>$this->session->userdata['logged_in']['business_admin_id']
                                );
                                $this->BusinessAdminModel->Insert($data,'mss_emss_weekoff');
                                $weeklyoff=json_encode($temp[$key]);
                            }
                        }   
                    }else{
                        $employee_weekoff='null';
                    }
										 // for file upload
										//  $this->PrettyPrintArray($_FILES);
										$config['allowed_types']='jpg|png|pdf|docx|doc';
										$config['upload_path']='./upload/';
										$this->load->library('upload',$config);
										$this->upload->do_upload('cv');
										$path="upload/".$_FILES['cv']['name'];
										 //
										if($_POST['nick_name'] == '' || empty($_POST['nick_name'])){
											$_POST['nick_name']=$_POST['employee_first_name'].' '.$_POST['employee_last_name'];
										}
                    $data = array(
                        'employee_first_name'   => $this->input->post('employee_first_name'),
                        'employee_last_name'    => $this->input->post('employee_last_name'),
                        'employee_nick_name'    => $_POST['employee_nick_name'],
                        'employee_mobile'   => $this->input->post('employee_mobile'),
                        'employee_address'  => $this->input->post('employee_address'),
                        'employee_email'    => $this->input->post('employee_email'),
                        'employee_business_outlet'  => $this->input->post('employee_business_outlet'),
                        'employee_expertise'    => $this->input->post('employee_expertise'),
                        'employee_role'     => $this->input->post('employee_role'),
                        'employee_date_of_joining'  => $this->input->post('employee_date_of_joining'),
                        'employee_gross_salary'     => $this->input->post('employee_gross_salary'),
                        'employee_basic_salary'     => $this->input->post('employee_basic_salary'),
                        'employee_pf'   => $this->input->post('employee_pf'),
                        'employee_gratuity'     => $this->input->post('employee_gratuity'),
                        'employee_others'   => $this->input->post('employee_others'),
                        'employee_pt'=> $this->input->post('employee_pt'),
                        'employee_income_tax'=> $this->input->post('employee_it'),
                        'working_hours'     => $this->input->post('employee_work_hour'),
                        'employee_over_time_rate'   => $this->input->post('employee_over_time_rate'),
                        'employee_aadhar_number'    => $this->input->post('employee_aadhar_number'),
                        'employee_experience'   => $this->input->post('employee_experience'),
                        'employee_weekoff'  => $weeklyoff,
                        'employer'  => json_encode($this->input->post('employer')),
                        'from_date'     => json_encode($this->input->post('from_date')),
                        'to_date'   => json_encode($this->input->post('to_date')),
                        'no_of_certification'   => $this->input->post('no_of_certification'),
                        'certification_name'    => json_encode($this->input->post('certification_name')),
                        'start_date'    => json_encode($this->input->post('start_date')),
                        'end_date'  => json_encode($this->input->post('end_date')),
                        'employee_account_number'   => $this->input->post('employee_account_number'),
                        'employee_account_holder_name'  => $this->input->post('employee_account_holder_name'),
                        'employee_bank_name'    => $this->input->post('employee_bank_name'),
                        'employee_ifsc'     => $this->input->post('employee_ifsc'),
                        'employee_id'   => $this->input->post('employee_id'),
                        'employee_business_admin' => $this->session->userdata['logged_in']['business_admin_id'],
                        'file_path' => $path
                    );
                    $result = $this->BusinessAdminModel->Update($data,'mss_employees','employee_id');
                    if($result['success'] == 'true'){
                        $this->ReturnJsonArray(true,false,"Employee updated successfully!");
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
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }   
    }
	public function AddOutlet(){
		if($this->IsLoggedIn('business_admin')){
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
						'business_outlet_name' 	=> $this->input->post('business_outlet_name'),
						'business_outlet_gst_in' 	=> $this->input->post('business_outlet_gst_in'),
						'business_outlet_mobile' 	=> $this->input->post('business_outlet_mobile'),
						'business_outlet_landline' 	=> $this->input->post('business_outlet_landline'),
						'business_outlet_address' 	=> $this->input->post('business_outlet_address'),
						'business_outlet_pincode' 	=> $this->input->post('business_outlet_pincode'),
						'business_outlet_state' 	=> $this->input->post('business_outlet_state'),
						'business_outlet_city' 	=> $this->input->post('business_outlet_city'),
						'business_outlet_country' 	=> "India",
						'business_outlet_email' 	=> $this->input->post('business_outlet_email'),
						'business_outlet_bill_header_msg' 	=> $this->input->post('business_outlet_bill_header_msg'),
						'business_outlet_bill_footer_msg' 	=> $this->input->post('business_outlet_bill_footer_msg'),
						'business_outlet_facebook_url' 	=> $this->input->post('business_outlet_facebook_url'),
						'business_outlet_instagram_url' 	=> $this->input->post('business_outlet_instagram_url'),
						'business_outlet_latitude' 	=> $this->input->post('business_outlet_latitude'),
						'business_outlet_longitude' 	=> $this->input->post('business_outlet_longitude'),
						'business_outlet_sender_id'		=>$this->input->post('business_outlet_sender_id'),
						'business_outlet_google_my_business_url'=>strtolower($this->input->post('business_outlet_google_my_business_url')),
						'api_key'=>$this->input->post('api_key'),
						'business_outlet_business_admin' 	=> $this->session->userdata['logged_in']['business_admin_id']
					);
					$result = $this->BusinessAdminModel->Insert($data,'mss_business_outlets');
						
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
				$data = $this->GetDataForAdmin("Add Outlet");
				$outlet_admin_id= $this->session->userdata['outlets']['current_outlet'];
				$sql ="SELECT config_value from mss_config where config_key='salon_logo' and outlet_admin_id = $outlet_admin_id";

					$query = $this->db->query($sql);
					$result = $query->result_array();
					if(empty($result)){
						$sql ="SELECT config_value from mss_config where config_key='salon_logo' and outlet_admin_id = 1";

						$query = $this->db->query($sql);
						$result = $query->result_array();
					}
					$data['logo'] = $result[0]['config_value'];
				$this->load->view('business_admin/ba_add_outlet_view',$data);
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}	
	}
//upload outletLogo
// public function UploadImage(){
// 	if($this->IsLoggedIn('business_admin')){
// 		/* Getting file name */
// 		$filename = $_FILES['file']['name']; 
			
// 		/* Location */
// 		$location = "http://localhost/public/images/".$filename; 
// 		$uploadOk = 1; 
			
// 		if($uploadOk == 0){ 
// 			return 0; 
// 		}else{ 
//    /* Upload file */
// 			if(move_uploaded_file($_FILES['file']['tmp_name'], $location)){ 
// 					return $location; 
// 			}else{ 
// 					echo 0; 
// 			} 
// 		} 
// 	}
// 	else{
// 		$this->LogoutUrl(base_url()."BusinessAdmin/");
// 	}
// }
//
	public function GetBusinessOutlet(){
		if($this->IsLoggedIn('business_admin')){
			if(isset($_GET) && !empty($_GET)){
				$data = $this->BusinessAdminModel->DetailsById($_GET['business_outlet_id'],'mss_business_outlets','business_outlet_id');
				header("Content-type: application/json");
				print(json_encode($data['res_arr'], JSON_PRETTY_PRINT));
				die;
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}

public function GetEmployee(){
		if($this->IsLoggedIn('business_admin')){
			if(isset($_GET) && !empty($_GET)){
				$data = $this->BusinessAdminModel->DetailsById($_GET['employee_id'],'mss_employees','employee_id');
				header("Content-type: application/json");
				print(json_encode($data['res_arr'], JSON_PRETTY_PRINT));
				die;
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}

	public function GetCategory(){
		if($this->IsLoggedIn('business_admin')){
			if(isset($_GET) && !empty($_GET)){
				$data = $this->BusinessAdminModel->DetailsById($_GET['category_id'],'mss_categories','category_id');
				header("Content-type: application/json");
				print(json_encode($data['res_arr'], JSON_PRETTY_PRINT));
				die;
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}
    
    // get service by id
	public function GetServicePriceById(){
		if($this->IsLoggedIn('business_admin')){
			if(isset($_GET) && !empty($_GET)){
				$where = array(
					'service_id'  => $_GET['service_id'],
					'service_is_active'   => TRUE
				);
				
				$data = $this->BusinessAdminModel->MultiWhereSelect('mss_services',$where);
				header("Content-type: application/json");
				print(json_encode($data['res_arr'], JSON_PRETTY_PRINT));
				die;
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}

	public function GetSubCategory(){
		if($this->IsLoggedIn('business_admin')){
			if(isset($_GET) && !empty($_GET)){
				$data = $this->BusinessAdminModel->DetailsById($_GET['sub_category_id'],'mss_sub_categories','sub_category_id');
				header("Content-type: application/json");
				print(json_encode($data['res_arr'], JSON_PRETTY_PRINT));
				die;
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}

	public function GetService(){
		if($this->IsLoggedIn('business_admin')){
			if(isset($_GET) && !empty($_GET)){
				$data = $this->BusinessAdminModel->DetailsById($_GET['service_id'],'mss_services','service_id');
				// $this->PrettyPrintArray($data);
				header("Content-type: application/json");
				print(json_encode($data['res_arr'], JSON_PRETTY_PRINT));
				die;
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}

	public function GetRawMaterial(){
		if($this->IsLoggedIn('business_admin')){
			if(isset($_GET) && !empty($_GET)){
				$data = $this->BusinessAdminModel->DetailsById($_GET['raw_material_category_id'],'mss_raw_material_categories','raw_material_category_id');
				header("Content-type: application/json");
				print(json_encode($data['res_arr'], JSON_PRETTY_PRINT));
				die;
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}

	public function GetExpenseType(){
		if($this->IsLoggedIn('business_admin')){
			if(isset($_GET) && !empty($_GET)){
				$data = $this->BusinessAdminModel->DetailsById($_GET['expense_type_id'],'mss_expense_types','expense_type_id');
				header("Content-type: application/json");
				print(json_encode($data['res_arr'], JSON_PRETTY_PRINT));
				die;
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}

	public function GetParticularComposition(){
		if($this->IsLoggedIn('business_admin')){
			if(isset($_GET) && !empty($_GET)){
				$where = array(
					'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id'],
					'business_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
					'service_id'         => $_GET['service_id']
				);
				$data = $this->BusinessAdminModel->ViewComposition($where);
				header("Content-type: application/json");
				print(json_encode($data['res_arr'], JSON_PRETTY_PRINT));
				die;
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}	
	}


	public function DeleteComposition(){
		if($this->IsLoggedIn('business_admin')){
			if(isset($_GET) && !empty($_GET)){
				$where = array(
					'service_id' => $_GET['service_id']
				);
				
				$data = $this->BusinessAdminModel->DeleteMultiple('mss_raw_composition',$where['service_id'],'service_id');
				
				if($data['success'] == 'true'){
					$this->ReturnJsonArray(true,false,'Composition deleted successfully!');
					die;
				}
				elseif ($data['error'] == 'true') {
					$this->ReturnJsonArray(false,true,$data['message']);
					die;	
				}			
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}	
	}

	public function EditOutlet(){
		if($this->IsLoggedIn('business_admin')){
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

					// for file upload
					if(!empty($_FILES['business_outlet_logo']['name'])){
						$config['allowed_types']='jpg|png|jpeg';
						$config['upload_path']='./public/images/';
						$this->load->library('upload',$config);
						$this->upload->do_upload('business_outlet_logo');
						$path=array(
							'outlet_admin_id' => $this->session->userdata['outlets']['current_outlet'],
							'config_key'			=>'salon_logo',
							'config_value' =>$_FILES['business_outlet_logo']['name']
						);
						$where=array(
							'outlet_admin_id'=> $this->session->userdata['outlets']['current_outlet']
						);
						$logo_exist=$this->BusinessAdminModel->MultiWhereSelect('mss_config',$where);
							if(empty($logo_exist['res_arr'])){
							$update_logo=$this->BusinessAdminModel->Insert($path,'mss_config');
							}else{
								$update_logo=$this->BusinessAdminModel->Update($path,'mss_config','outlet_admin_id');
							}
						}
						//

					$data = array(
						'business_outlet_id'    => $this->input->post('business_outlet_id'),
						'business_outlet_name' 	=> $this->input->post('business_outlet_name'),
						'business_outlet_gst_in' 	=> $this->input->post('business_outlet_gst_in'),
						'business_outlet_mobile' 	=> $this->input->post('business_outlet_mobile'),
						'business_outlet_landline' 	=> $this->input->post('business_outlet_landline'),
						'business_outlet_address' 	=> $this->input->post('business_outlet_address'),
						'business_outlet_pincode' 	=> $this->input->post('business_outlet_pincode'),
						'business_outlet_state' 	=> $this->input->post('business_outlet_state'),
						'business_outlet_city' 	=> $this->input->post('business_outlet_city'),
						'business_outlet_country' 	=> "India",
						'business_outlet_email' 	=> $this->input->post('business_outlet_email'),
						'business_outlet_bill_header_msg' 	=> $this->input->post('business_outlet_bill_header_msg'),
						'business_outlet_bill_footer_msg' 	=> $this->input->post('business_outlet_bill_footer_msg'),
						'business_outlet_facebook_url' 	=> $this->input->post('business_outlet_facebook_url'),
						'business_outlet_instagram_url' 	=> $this->input->post('business_outlet_instagram_url'),
						'business_outlet_latitude' 	=> $this->input->post('business_outlet_latitude'),
						'business_outlet_longitude' 	=> $this->input->post('business_outlet_longitude'),
						'business_outlet_business_admin' 	=> $this->session->userdata['logged_in']['business_admin_id']
					);
					
					$result = $this->BusinessAdminModel->Update($data,'mss_business_outlets','business_outlet_id');
						
					if($result['success'] == 'true' || $update_logo){
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
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}	
	}

	public function GetCategoriesPublic(){
		if($this->IsLoggedIn('business_admin')){
			$this->ReturnJsonArray(true,false,$this->GetCategories($this->session->userdata['outlets']['current_outlet']));
			die;
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}		
	}

	public function GetSubCategoriesPublic(){
		if($this->IsLoggedIn('business_admin')){
			$this->ReturnJsonArray(true,false,$this->GetSubCategories($this->session->userdata['outlets']['current_outlet']));
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}		
	}

	private function GetCategories($outlet_id){
		if($this->IsLoggedIn('business_admin')){
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
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}		
	}

	private function GetRawMaterials($outlet_id){
		if($this->IsLoggedIn('business_admin')){
			$where = array(
				'raw_material_business_admin_id' => $this->session->userdata['logged_in']['business_admin_id'],
				'raw_material_is_active'         => TRUE,
				'raw_material_business_outlet_id'=> $outlet_id
			);

			$data = $this->BusinessAdminModel->MultiWhereSelect('mss_raw_material_categories',$where);
			if($data['success'] == 'true'){	
				return $data['res_arr'];
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}		
	}
	
		public function GetCategoriesByCategoryType(){
		if($this->IsLoggedIn('business_admin')){
			if(isset($_GET) && !empty($_GET)){
				$where = array(
					'category_type' => $_GET['category_type'],
					'category_is_active'   => TRUE,
					'category_business_admin_id'=>$this->session->userdata['logged_in']['business_admin_id']
				);
				$data = $this->BusinessAdminModel->MultiWhereSelect('mss_categories',$where);
				header("Content-type: application/json");
				print(json_encode($data['res_arr'], JSON_PRETTY_PRINT));
				die;
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}

	public function GetSubCategoriesByCatId(){
		if($this->IsLoggedIn('business_admin')){
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
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}

	public function GetServicesBySubCatId(){
		if($this->IsLoggedIn('business_admin')){
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
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}

	private function GetSubCategories($outlet_id){
		if($this->IsLoggedIn('business_admin')){
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
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}		
	}

	private function GetServices($outlet_id){
		if($this->IsLoggedIn('business_admin')){
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
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}		
	}

	private function GetOTCServices($outlet_id){
		if($this->IsLoggedIn('business_admin')){
			$where = array(
				'category_business_admin_id'   => $this->session->userdata['logged_in']['business_admin_id'],
				'service_is_active'            => TRUE,
				'category_business_outlet_id'  => $outlet_id,
				'service_type'    						 => 'otc'
			);

			$data = $this->BusinessAdminModel->Services($where);
			if($data['success'] == 'true'){	
				return $data['res_arr'];
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}		
	}

	private function GetExpensesTypes($outlet_id){
		if($this->IsLoggedIn('business_admin')){
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
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}		
	}

	private function GetCompositions($outlet_id){
		if($this->IsLoggedIn('business_admin')){
			$where = array(
				'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id'],
				'business_outlet_id' => $outlet_id
			);

			$data = $this->BusinessAdminModel->ViewCompositionBasic($where);
			if($data['success'] == 'true'){	
				return $data['res_arr'];
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}		
	}

	private function GetBusinessAdminEmployees(){
		if($this->IsLoggedIn('business_admin')){
			$where = array(
				'employee_business_admin' => $this->session->userdata['logged_in']['business_admin_id']
			);

			$data = $this->BusinessAdminModel->MultiWhereSelect('mss_employees',$where);
			// $data = $this->BusinessAdminModel->GetOutletEmployee($where);
			if($data['success'] == 'true'){	
				return $data['res_arr'];
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}		
	}


	//To Change Employee Status
	public function ChangeEmployeeStatus(){
		if($this->IsLoggedIn('business_admin')){
			if(isset($_POST) && !empty($_POST)){
				if($this->input->post('activate') == 'true' && $this->input->post('deactivate') == 'false'){
					//Activate the Employee
					$data = array(
					  "employee_id"          => $this->input->post('employee_id'),
						"employee_is_active"   => TRUE
					);

					$status = $this->BusinessAdminModel->Update($data,'mss_employees','employee_id');
					if($status['success'] == 'true'){
						$this->ReturnJsonArray(true,false,"Activated successfully!");
						die;
					}
					elseif($status['error'] == 'true'){
						$this->ReturnJsonArray(false,true,$status['message']);
						die;
					}
				}	
				elseif($this->input->post('activate') == 'false' && $this->input->post('deactivate') == 'true'){
					//Deactivate the Employee
					$data = array(
						"employee_id" => $this->input->post('employee_id'),	
						"employee_is_active"   => FALSE
					);

					$status = $this->BusinessAdminModel->Update($data,'mss_employees','employee_id');
					
					if($status['success'] == 'true'){
						$this->ReturnJsonArray(true,false,"Deactivated successfully!");
						die;
					}
					elseif($status['error'] == 'true'){
						$this->ReturnJsonArray(false,true,$status['message']);
						die;
					}
				}		       
	    }
	    else{
				$this->ReturnJsonArray(false,true,'Not a POST Request !');
				die;
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}

	public function DeactivateCategory(){
		if($this->IsLoggedIn('business_admin')){
			if(isset($_POST) && !empty($_POST)){
				if($this->input->post('activate') == 'false' && $this->input->post('deactivate') == 'true'){
					//Activate the Employee
					$data = array(
					  "category_id"          => $this->input->post('category_id'),
						"category_is_active"   => 0
					);
					
					$status = $this->BusinessAdminModel->DeactiveCategory($data['category_id']);
					if($status['success'] == 'true'){
						$this->ReturnJsonArray(true,false,"Deleted successfully!");
						die;
					}
					elseif($status['error'] == 'true'){
						$this->ReturnJsonArray(false,true,$status['message']);
						die;
					}
				}	    
	    }
	    else{
				$this->ReturnJsonArray(false,true,'Not a POST Request !');
				die;
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}

	public function DeactivateSubCategory(){
		if($this->IsLoggedIn('business_admin')){
			if(isset($_POST) && !empty($_POST)){
				if($this->input->post('activate') == 'false' && $this->input->post('deactivate') == 'true'){
					//Activate the Employee
					$data = array(
					  "sub_category_id"          => $this->input->post('sub_category_id'),
						"sub_category_is_active"   => FALSE
					);

					$status = $this->BusinessAdminModel->DeactiveSubCategory($data['sub_category_id']);
					if($status['success'] == 'true'){
						$this->ReturnJsonArray(true,false,"Deleted successfully!");
						die;
					}
					elseif($status['error'] == 'true'){
						$this->ReturnJsonArray(false,true,$status['message']);
						die;
					}
				}	    
	    }
	    else{
				$this->ReturnJsonArray(false,true,'Not a POST Request !');
				die;
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}

	public function DeactivateService(){
		if($this->IsLoggedIn('business_admin')){
			if(isset($_POST) && !empty($_POST)){
				if($this->input->post('activate') == 'false' && $this->input->post('deactivate') == 'true'){
					$data = array(
					  "service_id"          => $this->input->post('service_id'),
						"service_is_active"   => FALSE
					);

					$status = $this->BusinessAdminModel->Update($data,'mss_services','service_id');
					if($status['success'] == 'true'){
						$this->ReturnJsonArray(true,false,"Deleted successfully!");
						die;
					}
					elseif($status['error'] == 'true'){
						$this->ReturnJsonArray(false,true,$status['message']);
						die;
					}
				}	    
	    }
	    else{
				$this->ReturnJsonArray(false,true,'Not a POST Request !');
				die;
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}

	public function DeactivateRawMaterial(){
		if($this->IsLoggedIn('business_admin')){
			if(isset($_POST) && !empty($_POST)){
				if($this->input->post('activate') == 'false' && $this->input->post('deactivate') == 'true'){
					//Activate the Employee
					$data = array(
					  "raw_material_category_id"          => $this->input->post('raw_material_category_id'),
						"raw_material_is_active"   					=> FALSE
					);
					
					$check = $this->BusinessAdminModel->IsBeingUsed('mss_raw_composition',$data['raw_material_category_id'],'rmc_id');
					if($check['success'] == 'true'){
						$this->ReturnJsonArray(false,true,'Raw material is currently in use for some composition. Cannot Delete!');
						die;
					}
					elseif ($check['success'] == 'false') {
					 	$status = $this->BusinessAdminModel->DeleteRawMaterialCategory($data);
						if($status['success'] == 'true'){
							$this->ReturnJsonArray(true,false,"Deleted successfully!");
							die;
						}
						elseif($status['error'] == 'true'){
							$this->ReturnJsonArray(false,true,$status['message']);
							die;
						}
					} 
				}	    
	    }
	    else{
				$this->ReturnJsonArray(false,true,'Not a POST Request !');
				die;
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}

	public function SelectOutlet(){
		if($this->IsLoggedIn('business_admin')){
			$outlet_id = $this->uri->segment(3);
			if(isset($this->session->userdata['outlets'])){
				$this->session->unset_userdata('outlets');
				$session_data = array(
					'current_outlet' => $outlet_id
				);
				$this->session->set_userdata('outlets', $session_data);
			}
			else{
				$session_data = array(
					'current_outlet' => $outlet_id
				);
				$this->session->set_userdata('outlets', $session_data);	
			}

			redirect(base_url()."BusinessAdmin/Dashboard/",'refresh');
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}

	public function BusinessAdminAddPackage(){
		if($this->IsLoggedIn('business_admin')){
			// $this->PrettyPrintArray($_POST);
			if(isset($_POST) && !empty($_POST)){
				$this->form_validation->set_rules('salon_package_name', 'Package Name', 'trim|required|max_length[50]');
				$this->form_validation->set_rules('salon_package_price', 'Package Price', 'trim|required');
				$this->form_validation->set_rules('salon_package_gst', 'Package GST', 'trim|required');
				$this->form_validation->set_rules('salon_package_upfront_amt', 'Upfront Amount', 'trim|required');
				$this->form_validation->set_rules('salon_package_validity', 'Validity', 'trim|required|is_natural_no_zero');
				$this->form_validation->set_rules('salon_package_type', 'Package Type', 'trim|required|max_length[50]');
				$this->form_validation->set_rules('virtual_wallet_money_absolute', 'Wallet money loaded', 'trim');
				$this->form_validation->set_rules('virtual_wallet_money_percentage', 'Wallet money loaded', 'trim');
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
						'salon_package_name' => $this->input->post('salon_package_name'),
						'salon_package_price' => $this->input->post('salon_package_price'),
						'service_gst_percentage' => $this->input->post('salon_package_gst'),
						'salon_package_upfront_amt' => $this->input->post('salon_package_upfront_amt'),
						'salon_package_validity'=> $this->input->post('salon_package_validity'),
						'salon_package_type' 	=> $this->input->post('salon_package_type'),
						'business_admin_id' => $this->session->userdata['logged_in']['business_admin_id'],
						'business_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
						'salon_package_date' => date('Y-m-d')
					);
					if($data['salon_package_type'] == "Wallet"){
							//Only one insert required
						if(empty($this->input->post("virtual_wallet_money_absolute")) && empty($this->input->post("virtual_wallet_money_percentage"))){
							$this->ReturnJsonArray(false,true,"Please fill virtual money value!");
							die;
						}
						else{
							
							if(!empty($this->input->post("virtual_wallet_money_absolute"))){
								$data['virtual_wallet_money'] = $this->input->post("virtual_wallet_money_absolute");
								
								$result = $this->BusinessAdminModel->Insert($data,'mss_salon_packages');
								
								if($result['success'] == 'true'){
									$this->ReturnJsonArray(true,false,"Package added successfully!");
									die;
								}
								elseif($result['error'] == 'true'){
									$this->ReturnJsonArray(false,true,$result['message']);
									die;
								}
							}

							if(!empty($this->input->post("virtual_wallet_money_percentage"))){
								$data['virtual_wallet_money'] = (($this->input->post("virtual_wallet_money_percentage")/100) * $data['salon_package_upfront_amt']) ;
								
								$result = $this->BusinessAdminModel->Insert($data,'mss_salon_packages');
								
								if($result['success'] == 'true'){
									$this->ReturnJsonArray(true,false,"Package added successfully!");
									die;
								}
								elseif($result['error'] == 'true'){
									$this->ReturnJsonArray(false,true,$result['message']);
									die;
								}
							}
						}
					}
					elseif($data['salon_package_type'] == "Services"){
						$services = $this->input->post('service_id');
						$counts = $this->input->post('count_service');
						if(!empty($services) && !empty($counts) && (count($services) == count($counts))){
							$result = $this->BusinessAdminModel->AddServicePackageForSalon($data,$services,$counts);
							if($result['success'] == 'true'){
								$this->ReturnJsonArray(true,false,"Package added successfully!");
								die;
							}
							elseif($result['error'] == 'true'){
								$this->ReturnJsonArray(false,true,$result['message']);
								die;
							}
						}
						else{
							$this->ReturnJsonArray(false,true,"Wrong way of data filling!");
							die;
						}
					}
					elseif($data['salon_package_type'] == "Discount"){

						$services = $this->input->post('service_id');
						$discounts =  $this->input->post('discount');
						$counts = $this->input->post('count_discount');
						// $this->PrettyPrintArray($services);
						if(!empty($services) && !empty($discounts) && !empty($counts) && (count($services) == count($counts)) && (count($counts) == count($discounts))){
							$result = $this->BusinessAdminModel->AddDiscountPackageForSalon($data,$services,$discounts,$counts);
							if($result['success'] == 'true'){
								$this->ReturnJsonArray(true,false,"Package added successfully!");
								die;
							}
							elseif($result['error'] == 'true'){
								$this->ReturnJsonArray(false,true,$result['message']);
								die;
							}
						}
						else{
							$this->ReturnJsonArray(false,true,"Wrong way of data filling!");
							die;
						}
					}
					elseif ($data['salon_package_type'] == "Service_SubCategory_Bulk") {
						$data['salon_package_type'] = 'Services';
						$sub_categories = $this->input->post('service_sub_category_bulk'); 
						$counts = $this->input->post('count_service_subcategory_bulk');
						if(!empty($sub_categories) && !empty($counts) && (count($sub_categories) == count($counts))){
							
							$result = $this->BusinessAdminModel->AddServiceSubCategoryBulkPackage($data,$sub_categories,$counts);
							
							if($result['success'] == 'true'){
								$this->ReturnJsonArray(true,false,"Package added successfully!");
								die;
							}
							elseif($result['error'] == 'true'){
								$this->ReturnJsonArray(false,true,$result['message']);
								die;
							}
						}
						else{
							$this->ReturnJsonArray(false,true,"Wrong way of data filling!");
							die;
						}	
					}
					elseif($data['salon_package_type'] == "Discount_SubCategory_Bulk"){
						$data['salon_package_type'] = 'Discount';
						$sub_categories = $this->input->post('service_sub_category_bulk');
						$discounts =  $this->input->post('discount_subcategory_bulk');
						$counts = $this->input->post('count_discount_subcategory_bulk');
						
						if(!empty($sub_categories) && !empty($discounts) && !empty($counts) && (count($sub_categories) == count($counts)) && (count($counts) == count($discounts))){
							$result = $this->BusinessAdminModel->AddDiscountSubCategoryBulkPackage($data,$sub_categories,$discounts,$counts);
							if($result['success'] == 'true'){
								$this->ReturnJsonArray(true,false,"Package added successfully!");
								die;
							}
							elseif($result['error'] == 'true'){
								$this->ReturnJsonArray(false,true,$result['message']);
								die;
							}
						}
						else{
							$this->ReturnJsonArray(false,true,"Wrong way of data filling!");
							die;
						}
					}
					elseif ($data['salon_package_type'] == "Service_Category_Bulk") {
						$data['salon_package_type'] = 'Services';
						$categories = $this->input->post('service_category_bulk'); 
						$counts = $this->input->post('count_service_category_bulk');
						if(!empty($categories) && !empty($counts) && (count($categories) == count($counts))){
							// $this->PrettyPrintArray($categories);
							$result = $this->BusinessAdminModel->AddServiceCategoryBulkPackage($data,$categories,$counts);
							
							if($result['success'] == 'true'){
								$this->ReturnJsonArray(true,false,"Package added successfully!");
								die;
							}
							elseif($result['error'] == 'true'){
								$this->ReturnJsonArray(false,true,$result['message']);
								die;
							}
						}
						else{
							$this->ReturnJsonArray(false,true,"Wrong way of data filling!");
							die;
						}	
					}
					elseif($data['salon_package_type'] == "Discount_Category_Bulk"){
						$data['salon_package_type'] = 'Discount';
						$categories = $this->input->post('discount_service_category_bulk');
						$cat_price = $this->input->post('service_price_greater_than');
						$discounts =  $this->input->post('discount_category_bulk');
						$counts = $this->input->post('count_discount_category_bulk');
					
						if(!empty($categories) && !empty($discounts) && !empty($counts) && (count($categories) == count($counts)) && (count($counts) == count($discounts))){
							$result = $this->BusinessAdminModel->AddDiscountCategoryBulkPackage($data,$categories, $cat_price, $discounts,$counts);
							if($result['success'] == 'true'){
								$this->ReturnJsonArray(true,false,"Package added successfully!");
								die;
							}
							elseif($result['error'] == 'true'){
								$this->ReturnJsonArray(false,true,$result['message']);
								die;
							}
						}
						else{
							$this->ReturnJsonArray(false,true,"Wrong way of data filling!");
							die;
						}
					}
					elseif($data['salon_package_type'] == "special_membership"){
						// $this->PrettyPrintArray($_POST);
						$where=array(
							// 'category_type'=> $_POST['category_type'],
							// 'service_price_inr' 	=> $_POST['price_greater_than'],
							'business_admin_id' => $this->session->userdata['logged_in']['business_admin_id'],
							'business_outlet_id' => $this->session->userdata['outlets']['current_outlet']
						);
						// $discounts =  $this->input->post('special_discount');
						$counts = 100;
					
						if(!empty($counts)){
							$result = $this->BusinessAdminModel->AddDiscountServicePackage($_POST,$data,$counts,$where);
							if($result['success'] == 'true'){
								$this->ReturnJsonArray(true,false,"Package added successfully!");
								die;
							}
							elseif($result['error'] == 'true'){
								$this->ReturnJsonArray(false,true,$result['message']);
								die;
							}
						}
						else{
							$this->ReturnJsonArray(false,true,"Wrong way of data filling!");
							die;
						}
					}
				}
			}
			else{
				$data = $this->GetDataForAdmin("Create Packages");
				if(isset($data['selected_outlet']) || !empty($data['selected_outlet'])){
					$data['services'] = $this->GetServices($this->session->userdata['outlets']['current_outlet']);
					$data['sub_categories'] = $this->GetSubCategories($this->session->userdata['outlets']['current_outlet']); 
					$data['categories'] = $this->GetCategories($this->session->userdata['outlets']['current_outlet']);	
					$data['packages'] = $this->ActivePackages($this->session->userdata['outlets']['current_outlet']);
					$data['deactive_packages'] = $this->BusinessAdminModel->DeActivePackages($this->session->userdata['outlets']['current_outlet']);
					$data['deactive_packages'] = $data['deactive_packages']['res_arr'];
				}
				$this->load->view('business_admin/ba_add_packages_view',$data);
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}

	private function ActivePackages($outlet_id){
		if($this->IsLoggedIn('business_admin')){
			$where = array(
				'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id'],
				'business_outlet_id' => $outlet_id
			);

			$data = $this->BusinessAdminModel->GetAllPackages($where);
			
			if($data['success'] == 'true'){	
				return $data['res_arr'];
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}		
	}


	public function ChangePackageStatus(){
		if($this->IsLoggedIn('business_admin')){
			if(isset($_POST) && !empty($_POST)){
				if($this->input->post('activate') == 'true' && $this->input->post('deactivate') == 'false'){
					//Activate
					$data = array(
					  "salon_package_id"          => $this->input->post('salon_package_id'),
						"is_active"   => TRUE
					);

					$status = $this->BusinessAdminModel->Update($data,'mss_salon_packages','salon_package_id');
					if($status['success'] == 'true'){
						$this->ReturnJsonArray(true,false,"Activated successfully!");
						die;
					}
					elseif($status['error'] == 'true'){
						$this->ReturnJsonArray(false,true,$status['message']);
						die;
					}
				}	
				elseif($this->input->post('activate') == 'false' && $this->input->post('deactivate') == 'true'){
					//Deactivate the Employee
					$data = array(
						"salon_package_id" => $this->input->post('salon_package_id'),	
						"is_active"   => FALSE
					);

					$status = $this->BusinessAdminModel->Update($data,'mss_salon_packages','salon_package_id');
					
					if($status['success'] == 'true'){
						$this->ReturnJsonArray(true,false,"Deactivated successfully!");
						die;
					}
					elseif($status['error'] == 'true'){
						$this->ReturnJsonArray(false,true,$status['message']);
						die;
					}
				}		       
	    }
	    else{
				$this->ReturnJsonArray(false,true,'Not a POST Request !');
				die;
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}

	public function ReportsManagement(){
		if($this->IsLoggedIn('business_admin')){
			$data = $this->GetDataForAdmin("Reports Management");
			if(!isset($_GET) || empty($_GET)){
				//Load the default view
				$where=array(
					'employee_is_active' => 1,
					'employee_business_admin' => $this->session->userdata['logged_in']['business_admin_id'],
					'employee_business_outlet' => $this->session->userdata['outlets']['current_outlet']
				);
				$data['expert1']=$this->BusinessAdminModel->MultiWhereSelect('mss_employees',$where);
				$data['expert']=$data['expert1']['res_arr'];
				$data['emp']=$data['expert1']['res_arr'];
				// $this->PrettyPrintArray($data);
				$this->load->view('business_admin/ba_reports_view',$data);
			}
			else if(isset($_GET) && !empty($_GET)){
				//Return the report view
				if(isset($data['selected_outlet']) || !empty($data['selected_outlet'])){
					$data = array(
						'report_name' 			 => $_GET['report_name'],
						'from_date' 				 => $_GET['from_date'],
						'to_date' 					 => $_GET['to_date'],
						'business_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
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
	      else{
	      	$this->ReturnJsonArray(false,true,"Select outlet first!");
	      }
			}		
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}

	public function BulkUploadServices(){
		if($this->IsLoggedIn('business_admin')){
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
										'service_sub_category_id' => $row[0],
										'service_name' 						=> $row[1],
										'service_price_inr' 			=> $row[2],
										'service_est_time' 				=> $row[3],
										'service_description' 		=> $row[4],
										'service_gst_percentage' 	=> $row[5]
									);

									$result = $this->BusinessAdminModel->Insert($data,'mss_services');
										
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
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}

	public function BulkUploadOTC(){
        if($this->IsLoggedIn('business_admin')){
            // $this->PrettyPrintArray($_FILES['file']);
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
                                        'service_sub_category_id' => $row[0],
                                        'inventory_type'                        => $row[1],
                                        'service_name'          => $row[2],
                                        'service_price_inr'     => $row[3],
                                        'service_is_active'            => $row[4],
                                        'service_est_time'           => $row[5],
                                        'service_description'            => $row[6],
                                        'service_gst_percentage' => $row[7],
                                        'service_type'=> 'otc',
                                        'barcode' => $row[8],
                                        'barcode_id'=> $row[9],
                                        'service_unit'=> $row[10],
                                        'service_brand'=> $row[11],
                                        'qty_per_item'=> $row[12]
                                       
                                    );
                                    // $this->PrettyPrintArray($data);
                                    $result = $this->BusinessAdminModel->Insert($data,'mss_services');
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
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }
    }

	public function BulkUploadCategory(){
		if($this->IsLoggedIn('business_admin')){
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
									    'category_name'               => $row[0],
										'category_type'               => $row[1],
										'category_description'         => $row[2],
										'category_business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id'],
										'category_business_outlet_id' => $this->session->userdata['outlets']['current_outlet']
									);

									$result = $this->BusinessAdminModel->Insert($data,'mss_categories');
										
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
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}


	public function BulkUploadSubCategory(){
		if($this->IsLoggedIn('business_admin')){
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
		             		'sub_category_category_id' => $row[0],
										'sub_category_name'        => $row[1],
										'sub_category_description' => $row[2]
									);

									$result = $this->BusinessAdminModel->Insert($data,'mss_sub_categories');
										
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
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}

	public function BulkUploadRawMaterial(){
		if($this->IsLoggedIn('business_admin')){
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
		             		'raw_material_name' 	=> $row[0],
										'raw_material_unit'  	=> $row[1],
										'raw_material_brand' 	=> $row[2],
										'raw_material_business_admin_id' 	=> $this->session->userdata['logged_in']['business_admin_id'],
										'raw_material_business_outlet_id' => $this->session->userdata['outlets']['current_outlet']
									);

									$result = $this->BusinessAdminModel->Insert($data,'mss_raw_material_categories');
										
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
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}

	//Loyalty
	public function Loyalty(){
		if($this->IsLoggedIn('business_admin')){
			$where=array(
				'business_admin_id'=> $this->session->userdata['logged_in']['business_admin_id']
			);
				$data = $this->GetDataForAdmin("Loyalty");
				$data['business_outlet_details'] = $this->GetBusinessOutlets();
				$data['loyalty']= $this->BusinessAdminModel->ExistingLoyalty($where);
				$data['loyalty']= $data['loyalty']['res_arr'];
				$data['cust_data']= $this->BusinessAdminModel->Cashback_Customer_Data($where);
				$data['cust_data']=	$data['cust_data']['res_arr'];
				// $this->PrettyPrintArray($data);
				// exit;
				$data['sidebar_collapsed']="true";
				$this->load->view('business_admin/ba_loyalty_view',$data);
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}	
	}

	public function AddConversionRatio(){
		if($this->IsLoggedIn('business_admin')){
			// $this->PrettyPrintArray($_POST);
			// exit;
			if(isset($_POST) && !empty($_POST)){ 
				$this->form_validation->set_rules('rupees', 'Amount', 'trim|required');
				$this->form_validation->set_rules('rate', 'Rate', 'trim|required');
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
							'loyalty_conversion' 	=> $this->input->post('rupees'),
							'loyalty_rate' 	=> $this->input->post('rate'),
							'validity' 	=>	$this->input->post('validity'),
							'loyalty_business_admin_id' 	=> $this->session->userdata['logged_in']['business_admin_id']
						);
					

					$where=array('business_admin_id'=>$this->session->userdata['logged_in']['business_admin_id']);
					$check= $this->BusinessAdminModel->ExistingLoyalty($where);

					// $this->PrettyPrintArray($check);
					// exit;
					if($check['success']=="true"){
						$this->ReturnJsonArray(false,true,"Conversion ratio already exist. Please update that");
						die;
					}
					$result = $this->BusinessAdminModel->Insert($data,'mss_loyalty');
					
						
					if($result['success'] == 'true'){
						$this->ReturnJsonArray(true,false,"Ratio added successfully!");
						die;
					}
					elseif($result['error'] == 'true'){
						$this->ReturnJsonArray(false,true,$result['message']);
						die;
					}
				}
			}
			else{
				$this->ReturnJsonArray(false,true,"Invalid Method");
				die;
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}	
	}


	//get conversion ratio
	public function GetConversionRatio(){
		if($this->IsLoggedIn('business_admin')){
			if(isset($_GET) && !empty($_GET)){
				$data = $this->BusinessAdminModel->DetailsById($_GET['loyalty_id'],'mss_loyalty','loyalty_id');
				// $this->PrettyPrintArray($data);
				// exit;
				header("Content-type: application/json");
				print(json_encode($data['res_arr'], JSON_PRETTY_PRINT));
				die;
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}
//Update Coversion ratio
	public function UpdateConversionRatio(){
		if($this->IsLoggedIn('business_admin')){
			// $this->PrettyPrintArray($_POST);
			// exit;
			if(isset($_POST) && !empty($_POST)){ 
				$this->form_validation->set_rules('conversion_ratio', 'Amount', 'trim|required');
				$this->form_validation->set_rules('conversion_rate', 'Rate', 'trim|required');
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
					if(!empty($_POST['validity'])){
						$data = array(
							'loyalty_conversion' 	=> $this->input->post('conversion_ratio'),
							'loyalty_rate' 	=> $this->input->post('conversion_rate'),
							'loyalty_id'		=> $this->input->post('loyalty_id'),
							'validity' 	=>$this->input->post('validity') ,
							'loyalty_business_admin_id' 	=> $this->session->userdata['logged_in']['business_admin_id']
						);
						
					}else{
						$data = array(
							'loyalty_conversion' 	=> $this->input->post('conversion_ratio'),
							'loyalty_rate' 	=> $this->input->post('conversion_rate'),
							'loyalty_id'		=> $this->input->post('loyalty_id'),
							'validity' 	=>"9999-12-31" ,
							'loyalty_business_admin_id' 	=> $this->session->userdata['logged_in']['business_admin_id']
						);
					
					}
					$result = $this->BusinessAdminModel->Update($data,'mss_loyalty','loyalty_id');
						
					if($result['success'] == 'true'){
						$this->ReturnJsonArray(true,false,"Ratio Updated successfully!");
						die;
					}
					elseif($result['error'] == 'true'){
						$this->ReturnJsonArray(false,true,$result['message']);
						die;
					}
				}
			}
			else{
				$this->ReturnJsonArray(false,true,"Invalid Method");
				die;
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}	
	}

	//
	public function GetCustomerData(){
		if($this->IsLoggedIn('business_admin')){
			
			if(isset($_GET) && !empty($_GET)){
				$data = $this->BusinessAdminModel->SearchCustomers($_GET['query'],$this->session->userdata['logged_in']['business_admin_id']);
			
				if($data['success'] == 'true'){	
					$this->ReturnJsonArray(true,false,$data['res_arr']);
					die;
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}
	//
	public function AddCustomerDataInTable(){	
		if($this->IsLoggedIn('business_admin')){
			if(isset($_GET) && !empty($_GET)){
				$where=array('customer_id'=>$_GET['customer_id'],
											'business_admin_id'=>$this->session->userdata['logged_in']['business_admin_id']);
											
				$data = $this->BusinessAdminModel->Cashback_Customer_Data_By_Id($where);
			
				if($data['success'] == 'true'){	
					header("Content-type: application/json");
					print(json_encode($data['res_arr'], JSON_PRETTY_PRINT));
					die;	
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}
	
	//Bill generate for Cancel purpose
	public function GenerateCustomerBill(){	        
		if($this->IsLoggedIn('business_admin')){
			if(isset($_GET) && !empty($_GET)){
				$where=array(	'from_date'	=> $_GET['from_date'],
											'to_date'		=> $_GET['to_date'],
											'business_admin_id'=>$this->session->userdata['logged_in']['business_admin_id'],
											'business_outlet_id'=>	$this->session->userdata['outlets']['current_outlet']);
											
				$data = $this->BusinessAdminModel->GenerateBills($where);
				// $this->PrettyPrintArray($data);
				if($data['success'] == 'true'){	
					header("Content-type: application/json");
					print(json_encode($data['res_arr'], JSON_PRETTY_PRINT));
					die;	
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}
	//delete bills
	public function DeleteBills(){	
		// $this->PrettyPrintArray($_POST);
		if($this->IsLoggedIn('business_admin')){
			if(isset($_POST) && !empty($_POST)){
				if($_POST['txn_type']=='Service'){
					$data=array(	
						'txn_id'				=>		$_POST['txn_id'],
						'txn_status'		=>0,
						'txn_remarks'		=>$_POST['remarks']
					);
				}else{
					$data=array(	
						'package_txn_id'				=>		$_POST['txn_id'],
						'package_txn_status'		=>0,
						'package_txn_remarks'		=>$_POST['remarks']
					);
				}
				
					
					//get IP
					if (!empty($_SERVER['HTTP_CLIENT_IP'])) {   //check ip from share internet
						$ip = $_SERVER['HTTP_CLIENT_IP'];
					} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   //to check ip is pass from proxy
							$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
					} else {
							$ip = $_SERVER['REMOTE_ADDR'];
					}						
				$data2=array(
					'txn_id'=>$_POST['txn_id'],
					'txn_remarks'=>$_POST['remarks'],
					'business_admin_id'=>$this->session->userdata['logged_in']['business_admin_id'],
					'business_outlet_id'=> $this->session->userdata['outlets']['current_outlet'],
					'ip_address'	=> $ip
				);
				// $data = $this->BusinessAdminModel->CancelBills($data);	
				
					$result = $this->BusinessAdminModel->BusinessAdminByEmail($this->session->userdata['logged_in']['business_admin_email']);	
					if($_POST['txn_type']=='Service'){
						$txn_details=$this->BusinessAdminModel->DetailsById($_POST['txn_id'],'mss_transactions','txn_id');
						$cust_id=$txn_details['res_arr']['txn_customer_id'];
						$pending_amount= $txn_details['res_arr']['txn_pending_amount'];
						$result2= $this->BusinessAdminModel->UpdateCustomerPendingAmount($cust_id,$pending_amount);
					}else{
						$txn_details=$this->BusinessAdminModel->DetailsById($_POST['txn_id'],'mss_package_transactions','package_txn_id');
						$cust_id=$txn_details['res_arr']['package_txn_customer_id'];
						$pending_amount= $txn_details['res_arr']['package_txn_pending_amount'];
						$result2= $this->BusinessAdminModel->UpdateCustomerPendingAmount($cust_id,$pending_amount);
					}

				if(password_verify($_POST['password'],$result['res_arr']['business_admin_password']))	{
					if($_POST['txn_type']=='Package'){
						$result = $this->BusinessAdminModel->Update($data,'mss_package_transactions','package_txn_id');
					}else{
						$result = $this->BusinessAdminModel->Update($data,'mss_transactions','txn_id');
					}						
					// log_message('info', 'Bill Edited By Admin id=' . $result['res_arr']['business_admin_id']);
					log_message('Error','Bill Edited By Admin id='.$this->session->userdata['logged_in']['business_admin_id'].'IP'.$_SERVER['REMOTE_ADDR']);	
					$this->BusinessAdminModel->Insert($data2,'mss_edit_bill_info');	
					if($result['success'] == 'true'){	
						$this->ReturnJsonArray(true,false,"Bill Deleted successfully!");
						die;
					}else{
						$this->ReturnJsonArray(false,true,"Error in Bill Cancellation!");
						die;
					}
				}else{
					$this->ReturnJsonArray(false,true,"Incorrect Password!");
					die;
				}
				
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}

		//Update bills
		public function UpdateTransaction(){	
			if($this->IsLoggedIn('business_admin')){
				if(isset($_POST) && !empty($_POST)){
					// $this->PrettyPrintArray($_POST);					
					if(!empty($_POST['txn_date']) && !empty($_POST['txn_expert']) && !empty($_POST['txn_abs_disc'])){
						$data=array(	
							'txn_id'												=> $_POST['txn_id'],
							'txn_datetime'									=> $_POST['txn_date'],
							'txn_service_id'								=> $_POST['txn_service_id'],
							'txn_service_discount_absolute' => $_POST['txn_abs_disc'],
							'txn_discounted_result'					=> ($_POST['old_txn_disc']-$_POST['txn_abs_disc']),
							'txn_expert'										=> $_POST['txn_expert']
						);
						
						$result=$this->BusinessAdminModel->UpdateTransactionOne($data);
					}else if(!empty($_POST['txn_date']) && !empty($_POST['txn_expert']) && (!isset($_POST['txn_abs_disc']))){
						$data=array(	
							'txn_id'												=> $_POST['txn_id'],
							'txn_datetime'									=> $_POST['txn_date'],
							'txn_service_id'								=> $_POST['txn_service_id'],
							'txn_expert'										=> $_POST['txn_expert']
						);
						
						$result=$this->BusinessAdminModel->UpdateTransactionTwo($data);
					}else if(!empty($_POST['txn_expert']) && !empty($_POST['txn_abs_disc']) && (!isset($_POST['txn_date']))){
						$data=array(	
							'txn_id'												=> $_POST['txn_id'],
							'txn_service_id'								=> $_POST['txn_service_id'],
							'txn_service_discount_absolute' => $_POST['txn_abs_disc'],
							'txn_discounted_result'					=> ($_POST['old_txn_disc']-$_POST['txn_abs_disc']),
							'txn_expert'										=> $_POST['txn_expert']
						);
						
						$result=$this->BusinessAdminModel->UpdateTransactionThree($data);
					}else if(!empty($_POST['txn_date']) && !empty($_POST['txn_abs_disc']) && (!isset($_POST['txn_expert']))){
						$data=array(	
							'txn_id'												=> $_POST['txn_id'],
							'txn_datetime'									=> $_POST['txn_date'],
							'txn_service_id'								=> $_POST['txn_service_id'],
							'txn_service_discount_absolute' => $_POST['txn_abs_disc'],
							'txn_discounted_result'					=> ($_POST['old_txn_disc']-$_POST['txn_abs_disc'])
						);
						
						$result=$this->BusinessAdminModel->UpdateTransactionFour($data);
					}else if(!empty($_POST['txn_date']) && (!isset($_POST['txn_abs_disc'])) && (!isset($_POST['txn_expert']))){
						$data=array(	
							'txn_id'												=> $_POST['txn_id'],
							'txn_datetime'									=> $_POST['txn_date']
						);
						
						$result=$this->BusinessAdminModel->Update($data,'mss_transactions','txn_id');
					}else if(!empty($_POST['txn_expert']) && (!isset($_POST['txn_date']))  && (!isset($_POST['txn_abs_disc']))){
						$data=array(	
							'txn_id'												=> $_POST['txn_id'],
							'txn_service_id'								=> $_POST['txn_service_id'],
							'txn_expert'										=> $_POST['txn_expert']
						);
						
						$result=$this->BusinessAdminModel->UpdateTransactionFive($data);
					}else if(!empty($_POST['txn_abs_disc']) && (!isset($_POST['txn_date'])) && (!isset($_POST['txn_expert']))){
						$data=array(	
							'txn_id'												=> $_POST['txn_id'],
							'txn_service_id'								=> $_POST['txn_service_id'],
							'txn_service_discount_absolute' => $_POST['txn_abs_disc'],
							'txn_discounted_result'					=> ($_POST['old_txn_disc']-$_POST['txn_abs_disc'])
						);
						
						$result=$this->BusinessAdminModel->UpdateTransactionSix($data);
					}else{
						$this->ReturnJsonArray(false,true,"Bill Not Updated.");
						die;
					}
						
						// $result3 = $this->BusinessAdminModel->UpdateAbsDiscount($data3);			
						if($result['success'] == 'true'){	
							$this->ReturnJsonArray(true,false,"Bill Updated successfully!");
							die;
						}else{
							$this->ReturnJsonArray(false,true,"No Bill Updated!");
							die;
						}					
				}
			}
			else{
				$this->LogoutUrl(base_url()."BusinessAdmin/");
			}
		}

		//Update Package bills
		public function UpdatePackageTransaction(){	
			if($this->IsLoggedIn('business_admin')){
				if(isset($_POST) && !empty($_POST)){
					// $this->PrettyPrintArray($_POST);
					if(isset($_POST['txn_expert']) && isset($_POST['txn_discount']) && isset($_POST['txn_date'])){
						$data2=array(	
							'package_txn_id'				=>	$_POST['package_txn_id'],
							'package_txn_expert'		=>	$_POST['txn_expert'],
							'package_txn_discount'	=>	$_POST['txn_discount'],
							'datetime'							=>	$_POST['txn_date'],
							'txn_discounted_result'	=>	($_POST['old_discount']-$_POST['txn_discount'])
						);
						$result2 = $this->BusinessAdminModel->UpdatePackageTransactionOne($data2);	
					}else if(isset($_POST['txn_expert']) && isset($_POST['txn_discount']) && !isset($_POST['txn_date'])){
						
						$data2=array(	
							'package_txn_id'				=>	$_POST['package_txn_id'],
							'package_txn_expert'		=>	$_POST['txn_expert'],
							'package_txn_discount'	=>	$_POST['txn_discount'],
							'txn_discounted_result'	=>	($_POST['old_discount']-$_POST['txn_discount'])
						);
						$result2 = $this->BusinessAdminModel->UpdatePackageTransactionTwo($data2);	
					}else if(isset($_POST['txn_expert']) && isset($_POST['txn_date']) && !isset($_POST['txn_discount'])){
						
						$data2=array(	
							'package_txn_id'				=>	$_POST['package_txn_id'],
							'package_txn_expert'		=>	$_POST['txn_expert'],
							'datetime'							=>	$_POST['txn_date']
						);
						
						$result2 = $this->BusinessAdminModel->Update($data2,'mss_package_transactions','package_txn_id');	
					}else if(!isset($_POST['txn_expert']) && isset($_POST['txn_date']) && isset($_POST['txn_discount'])){
						$data2=array(	
							'package_txn_id'				=>	$_POST['package_txn_id'],
							'package_txn_discount'	=>	$_POST['txn_discount'],
							'datetime'							=>	$_POST['txn_date'],
							'txn_discounted_result'	=>	($_POST['old_discount']-$_POST['txn_discount'])
						);
						$result2 = $this->BusinessAdminModel->UpdatePackageTransactionThree($data2);
					}else if(isset($_POST['txn_expert']) && !isset($_POST['txn_date']) && !isset($_POST['txn_discount'])){
						$data2=array(	
							'package_txn_id'				=>	$_POST['package_txn_id'],
							'package_txn_expert'		=>	$_POST['txn_expert']
						);
						$result2 = $this->BusinessAdminModel->Update($data2,'mss_package_transactions','package_txn_id');	
					}else if(!isset($_POST['txn_expert']) && !isset($_POST['txn_date']) && isset($_POST['txn_discount'])){
						$data2=array(	
							'package_txn_id'				=>	$_POST['package_txn_id'],
							'package_txn_discount'	=>	$_POST['txn_discount'],
							'txn_discounted_result'	=>	($_POST['old_discount']-$_POST['txn_discount'])
						);
						$result2 = $this->BusinessAdminModel->UpdatePackageTransactionFour($data2);	
					}else if(!isset($_POST['txn_expert']) && isset($_POST['txn_date']) && !isset($_POST['txn_discount'])){
						$data2=array(	
							'package_txn_id'				=>	$_POST['package_txn_id'],
							'datetime'							=>	$_POST['txn_date']
						);
						$result2 = $this->BusinessAdminModel->Update($data2,'mss_package_transactions','package_txn_id');	
					}else{
						$this->ReturnJsonArray(false,true,"Package Transaction Not Updated!");
						die;
					}								
						if($result2['success'] == 'true'){	
							$this->ReturnJsonArray(true,false,"Bill Updated successfully!");
							die;
						}else{
							$this->ReturnJsonArray(false,true,"No Bill Updated!");
							die;
						}					
				}
				else{
					$this->LogoutUrl(base_url()."BusinessAdmin/");
				}
			}
		}

	//delete bills
	public function VerifyPassword(){	
		if($this->IsLoggedIn('business_admin')){
			if(isset($_GET) && !empty($_GET)){
				$data=array('business_admin_password'=>$_GET['admin_password']);		
				$result = $this->BusinessAdminModel->BusinessAdminByEmail($this->session->userdata['logged_in']['business_admin_email']);	
				$where=array(
					'txn_id' 		=> $_GET['txn_id'],
					'txn_type' 	=> $_GET['txn_type']
				);
				$result1=$this->BusinessAdminModel->GetFullTransactionDetail($where);
				// $this->PrettyPrintArray($result1);
					
				if(password_verify($data['business_admin_password'],$result['res_arr']['business_admin_password']))	{
						header("Content-type: application/json" && $result1['res_arr']!=null);
						print(json_encode($result1['res_arr'], JSON_PRETTY_PRINT));
						die;									
				}else{
					$this->ReturnJsonArray(false,true,"Incorrect Password");
					die;
				}				
			}else{
				$this->ReturnJsonArray(false,true,"Incorrect Method!");
				die;
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}
	//edit bills
	public function EditBills(){
		if($this->IsLoggedIn('business_admin')){
			if(isset($_POST) && !empty($_POST)){
				$data=array(
					'txn_id'=>$_POST['txn_id'],
					'txn_service_service_id'=>$_POST['txn_service_service_id'],
					'txn_service_discounted_price'=>$_POST['txn_service_discounted_price'],
					'txn_service_status'=>0
				);
				//Get IP ADDRESS
					if (!empty($_SERVER['HTTP_CLIENT_IP'])) {   //check ip from share internet
						$ip = $_SERVER['HTTP_CLIENT_IP'];
					} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   //to check ip is pass from proxy
							$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
					} else {
							$ip = $_SERVER['REMOTE_ADDR'];
					}
				$data2=array(
					'txn_id'=>$_POST['txn_id'],
					'txn_service_id'			=>$_POST['txn_service_service_id'],
					'business_admin_id'		=> $this->session->userdata['logged_in']['business_admin_id'],
					'business_outlet_id'	=> $this->session->userdata['outlets']['current_outlet'],
					'ip_address'					=>	$ip,
					'txn_remarks'					=> 'Edited Individual Service'
					
				);		
				$update_txn_services=$this->BusinessAdminModel->UpdateTransactionService($data);

				if($update_txn_services['success']=='true'){
					//genereate log for edit service
						log_message('Error','Bill Edited By Admin id='.$this->session->userdata['logged_in']['business_admin_id'].' MAC'.$_SERVER['REMOTE_ADDR']);	
					//capture data for edited bill
						$this->BusinessAdminModel->Insert($data2,'mss_edit_bill_info');
						$this->ReturnJsonArray(true,false,"Bill Deleted Successfully!");
						die;				
				}else{
					$this->ReturnJsonArray(false,true,"Bill Not Deleted");
					die;
				}				
			}else{
				$this->ReturnJsonArray(false,true,"Incorrect Method!");
				die;
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}

	//EMSS start
	public function Employee_details(){
		if($this->IsLoggedIn('business_admin')){
			$where=array(
				'business_admin_id'=> $this->session->userdata['logged_in']['business_admin_id']
			);
				$data = $this->GetDataForAdmin("Employee_details");
				$data['business_outlet_details'] = $this->GetBusinessOutlets();
				$data['business_admin_employees']  = $this->GetBusinessAdminEmployees();
				// $data['loyalty']= $this->BusinessAdminModel->ExistingLoyalty($where);
				// $data['loyalty']= $data['loyalty']['res_arr'];
				// $data['cust_data']= $this->BusinessAdminModel->Cashback_Customer_Data($where);
				// $data['cust_data']=	$data['cust_data']['res_arr'];
				// $this->PrettyPrintArray($data);
				// exit;
				$data['sidebar_collapsed']="true";
				$this->load->view('business_admin/ba_emss_employee_details_view',$data);
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}	
	}

	// public function Attendance(){
	// 	if($this->IsLoggedIn('business_admin')){
	// 		$where=array(
	// 			'business_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
	// 			'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id']
	// 		);
	// 			$data = $this->GetDataForAdmin("Employee_details");
	// 			$data['business_outlet_details'] = $this->GetBusinessOutlets();
	// 			$data['business_admin_employees']  = $this->GetBusinessAdminEmployees();
	// 			$data['attendance']= $this->BusinessAdminModel->GetAttendance($where);
	// 			$data['attendance']= $data['attendance']['res_arr'];
	// 			// $data['cust_data']= $this->BusinessAdminModel->Cashback_Customer_Data($where);
	// 			// $data['cust_data']=	$data['cust_data']['res_arr'];
	// 			// $this->PrettyPrintArray($data['attendance']);
	// 			// exit;
	// 			$data['sidebar_collapsed']="true";
	// 			$this->load->view('business_admin/ba_emss_attendance_view',$data);
	// 	}
	// 	else{
	// 		$this->LogoutUrl(base_url()."BusinessAdmin/");
	// 	}	
	// }

	// public function Salary(){
	// 	if($this->IsLoggedIn('business_admin')){
	// 		$where=array(
	// 			'business_admin_id'=> $this->session->userdata['logged_in']['business_admin_id']
	// 		);
	// 			$data = $this->GetDataForAdmin("Employee_details");
	// 			$data['business_outlet_details'] = $this->GetBusinessOutlets();
	// 			$data['business_admin_employees']  = $this->GetBusinessAdminEmployees();
	// 			// $data['loyalty']= $this->BusinessAdminModel->ExistingLoyalty($where);
	// 			// $data['loyalty']= $data['loyalty']['res_arr'];
	// 			// $data['cust_data']= $this->BusinessAdminModel->Cashback_Customer_Data($where);
	// 			// $data['cust_data']=	$data['cust_data']['res_arr'];
	// 			// $this->PrettyPrintArray($data);
	// 			// exit;
	// 			$data['sidebar_collapsed']="true";
	// 			$this->load->view('business_admin/ba_emss_salary_view',$data);
	// 	}
	// 	else{
	// 		$this->LogoutUrl(base_url()."BusinessAdmin/");
	// 	}	
	// }
	// public function Commission(){
	// 	if($this->IsLoggedIn('business_admin')){
	// 		$where=array(
	// 			'business_admin_id'=> $this->session->userdata['logged_in']['business_admin_id']
	// 		);
	// 			$data = $this->GetDataForAdmin("Employee_details");
	// 			$data['business_outlet_details'] = $this->GetBusinessOutlets();
	// 			$data['business_admin_employees']  = $this->GetBusinessAdminEmployees();
	// 			// $data['loyalty']= $this->BusinessAdminModel->ExistingLoyalty($where);
	// 			// $data['loyalty']= $data['loyalty']['res_arr'];
	// 			// $data['cust_data']= $this->BusinessAdminModel->Cashback_Customer_Data($where);
	// 			// $data['cust_data']=	$data['cust_data']['res_arr'];
	// 			// $this->PrettyPrintArray($data['business_admin_employees']);
	// 			// exit;
	// 			$data['sidebar_collapsed']="true";
	// 			$this->load->view('business_admin/ba_emss_commission_view',$data);
	// 	}
	// 	else{
	// 		$this->LogoutUrl(base_url()."BusinessAdmin/");
	// 	}	
	// }

	public function Holidays(){
		if($this->IsLoggedIn('business_admin')){
			if(isset($_POST) && !empty($_POST)){ 
				$this->form_validation->set_rules('holiday_date', 'Holiday Date', 'trim|required');
				$this->form_validation->set_rules('holiday_name', 'Holiday Name', 'trim|required');
				

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
						'holiday_name' 	=> $this->input->post('holiday_name'),
						'holiday_date' 	=> $this->input->post('holiday_date'),
						'holiday_business_admin_id' 	=> $this->session->userdata['logged_in']['business_admin_id']
					);
					$result = $this->BusinessAdminModel->Insert($data,'mss_emss_holidays');
						
					if($result['success'] == 'true'){
						$this->ReturnJsonArray(true,false,"Holiday added successfully!");
						die;
          }
          elseif($result['error'] == 'true'){
          	$this->ReturnJsonArray(false,true,$result['message']);
						die;
          }
				}
			}
			else{
				$data = $this->GetDataForAdmin("Holidays");
				$data['business_outlet_details'] = $this->GetBusinessOutlets();
				$data['business_admin_employees']  = $this->GetBusinessAdminEmployees();
				$where=array(
					'business_admin_id'=> $this->session->userdata['logged_in']['business_admin_id']
				);
				$data['holiday']= $this->BusinessAdminModel->Holidays($where);
				$data['holiday']= $data['holiday']['res_arr'];
				// $this->PrettyPrintArray($data['holiday']);
				// exit;
				$data['sidebar_collapsed']="true";
				$this->load->view('business_admin/ba_emss_holidays_view',$data);
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}	
	}
	public function EditHolidays(){
		if($this->IsLoggedIn('business_admin')){
			if(isset($_POST) && !empty($_POST)){
				$data=array(	'holiday_id'=>$_POST['holiday_id'],
											'holiday_date'	=>$_POST['holiday_date'],
											'holiday_name'=>$_POST['holiday_name']);				
				$result = $this->BusinessAdminModel->Update($data,'mss_emss_holidays','holiday_id');			
				if($result['success'] == 'true'){	
					$this->ReturnJsonArray(true,false,"Holiday Edited successfully!");
					die;
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}
	public function CancelHolidays(){
		if($this->IsLoggedIn('business_admin')){
			if(isset($_POST) && !empty($_POST)){
				$data=array(	'holiday_id'=>$_POST['holiday_id'],
										'holiday_status' =>0);				
				$result = $this->BusinessAdminModel->Update($data,'mss_emss_holidays','holiday_id');			
				if($result['success'] == 'true'){	
					$this->ReturnJsonArray(true,false,"Holiday Canceled successfully!");
					die;
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}
	//Add EMSS employee
    public function AddEmssEmployee(){
        // $this->PrettyPrintArray($_POST);
        if($this->IsLoggedIn('business_admin')){
            if(isset($_POST) && !empty($_POST)){
                $this->form_validation->set_rules('employee_first_name', 'Employee FirstName', 'trim|required|max_length[50]');
                $this->form_validation->set_rules('employee_last_name', 'Employee Last Name', 'trim|required|max_length[50]');
                $this->form_validation->set_rules('employee_address', 'Employee Address', 'trim|required');
                $this->form_validation->set_rules('employee_mobile', 'Employee Mobile', 'trim|max_length[15]|required');
                $this->form_validation->set_rules('employee_email', 'Employee Email', 'trim|max_length[100]|required');
                $this->form_validation->set_rules('employee_business_outlet', 'Employee Outlet Name', 'trim|required');
                $this->form_validation->set_rules('employee_password', 'Employee Password', 'trim');
                $this->form_validation->set_rules('employee_expertise', 'Employee Expertise', 'trim');
                $this->form_validation->set_rules('employee_role', 'Employee Role', 'trim|required|max_length[50]');
                $this->form_validation->set_rules('employee_date_of_joining', 'Employee Date of Joining', 'trim|required');
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
                    if(isset($_POST['employee_weekoff']))
                    {
                        $employee_weekoff   = implode(",",$this->input->post('employee_weekoff'));
                    }
                    else{
                        $employee_weekoff='null';
                    }
                    //upload    
                        $path='';
                        // $this->PrettyPrintArray($_FILES);
                        if($_FILES['cv']['name'] != null){
                            $errors= array();
                            $file_name = $_FILES['cv']['name'];
                            $file_size =$_FILES['cv']['size'];
                            $file_tmp =$_FILES['cv']['tmp_name'];
                            $file_type=$_FILES['cv']['type'];
                            // $file_ext=strtolower(end(explode('.',$_FILES['cv']['name'])));
                            $tmp = explode('.', $file_name);
                            $file_ext = end($tmp);
                            $extensions= array("jpeg","jpg","png","pdf","doc","docx");
                            if(in_array($file_ext,$extensions)=== false){
                            $errors[]="extension not allowed.";
                            }
                            if(empty($errors)==true){
                                $file_name=$_POST['employee_mobile'].'.'.$file_ext;
                                $filename=$_POST['employee_mobile'];
                                $dir = 'upload';
                                $files = glob("{$dir}/{$filename}.*");
                                // if($files==null){
                                // }
                                $path="upload/".$file_name;
														$status=move_uploaded_file($file_tmp,$path);
														$this->PrettyPrintArray($status);
                            //    echo "Success";
                            }else{
                            print_r($errors);
                            }
                        }
                    //
                    if($_POST['employee_nick_name'] == ''){
                        $_POST['employee_nick_name']=$_POST['employee_first_name'].' '.$_POST['employee_last_name'];
                    }
                    // die;
                    $data = array(
                        'employee_first_name'   => $this->input->post('employee_first_name'),
                        'employee_last_name'    => $this->input->post('employee_last_name'),
                        'employee_nick_name'    => $this->input->post('employee_nick_name'),
                        'employee_mobile'   => $this->input->post('employee_mobile'),
                        'employee_address'  => $this->input->post('employee_address'),
                        'employee_email'    => $this->input->post('employee_email'),
                        'employee_business_outlet'  => $this->input->post('employee_business_outlet'),
                        'employee_password'     => password_hash($this->input->post('employee_password'), PASSWORD_DEFAULT),
                        'employee_expertise'    => $this->input->post('employee_expertise'),
                        'employee_role'     => $this->input->post('employee_role'),
                        'employee_date_of_joining'  => $this->input->post('employee_date_of_joining'),
                        'employee_gross_salary'     => $this->input->post('employee_gross_salary'),
                        'employee_basic_salary'     => $this->input->post('employee_basic_salary'),
                        'employee_pf'   => $this->input->post('employee_pf'),
                        'employee_gratuity'     => $this->input->post('employee_gratuity'),
                        'employee_others'   => $this->input->post('employee_others'),
                        'employee_pt'=> $this->input->post('employee_pt'),
                        'employee_income_tax'=> $this->input->post('employee_it'),
                        'employee_over_time_rate'   => 0,
                        'employee_aadhar_number'    => $this->input->post('employee_aadhar_number'),
                        'employee_experience'   => $this->input->post('employee_experience'),
                        'employee_weekoff'  => $employee_weekoff,
                        'employer'  => json_encode($this->input->post('employer')),
                        'from_date'     => json_encode($this->input->post('from_date')),
                        'to_date'   => json_encode($this->input->post('to_date')),
                        'no_of_certification'   => $this->input->post('no_of_certification'),
                        'certification_name'    => json_encode($this->input->post('certification_name')),
                        'start_date'    => json_encode($this->input->post('start_date')),
                        'end_date'  => json_encode($this->input->post('end_date')),
                        'employee_account_number'   => $this->input->post('employee_account_number'),
                        'employee_account_holder_name'  => $this->input->post('employee_account_holder_name'),
                        'employee_bank_name'    => $this->input->post('employee_bank_name'),
                        'employee_ifsc'     => $this->input->post('employee_ifsc'),
                        'employee_business_admin' => $this->session->userdata['logged_in']['business_admin_id'],
                        'file_path'=>$path
                    );
                    $result = $this->BusinessAdminModel->Insert($data,'mss_employees');
                    $res=$this->BusinessAdminModel->GetLastDataInserted($data);
                    if(isset($_POST['employee_weekoff']))
                    {   $count=0;
                        $temp=array_chunk($_POST['employee_weekoff'],5);
                        // foreach($temp as $w=>$day){
                        //  // $this->PrettyPrintArray($day);
                        //  array_push($week,$day);
                        // }
                        foreach($_POST['year'] as $key => $value){
                            $month=$_POST['month_name'][$key]."-".$value;
                                $data=array(
                                    'employee_id'=>$res['res_arr'][0]['employee_id'],
                                    'month'=>$month,
                                    'weekoff'=>json_encode($temp[$key]),
                                    'business_outlet_id'=>$this->session->userdata['outlets']['current_outlet'],
                                    'business_admin_id'=>$this->session->userdata['logged_in']['business_admin_id']
                                );
                                $this->BusinessAdminModel->Insert($data,'mss_emss_weekoff');
                                $weeklyoff=json_encode($temp[$key]);
                        }   
                    }
                    // $this->PrettyPrintArray($res);
                }
                    //
                    if($result['success'] == 'true'){
                        $this->ReturnJsonArray(true,false,"Employee added successfully!");
                        die;
          }
          elseif($result['error'] == 'true'){
            $this->ReturnJsonArray(false,true,$result['message']);
                        die;
                    }               
            }
        }
        else{
            $this->LogoutUrl(base_url()."BusinessAdmin/Login/");
        }   
    }


	//Auo Engage
		//Auto Engage
		public function AutoEngage(){
			if($this->IsLoggedIn('business_admin')){
				$where=array(
					'business_admin_id'=> $this->session->userdata['logged_in']['business_admin_id']
				);
					$data = $this->GetDataForAdmin("Auto Engage");
					$data['business_outlet_details'] = $this->GetBusinessOutlets();
					$data['triggers']=$this->BusinessAdminModel->ActiveTriggers($where);
					$data['triggers']=$data['triggers']['res_arr'];
					$data['upcomingDate']=$this->BusinessAdminModel->UpcomingDate($where);
					$data['upcomingDate']=$data['upcomingDate']['res_arr'];

					$data['tags']=$this->BusinessAdminModel->GetTags();
					$data['tags']=$data['tags']['res_arr'];
					// $this->PrettyPrintArray($data['tags']);
					// exit;
                    $trigger_detail =$this->BusinessAdminModel->GetTrigger();
                    $data['trigger_detail'] = [];
                    if($trigger_detail['success']){
                        $data['trigger_detail'] = $trigger_detail['res_arr'];
                    }                    
                    $outlet = [];
                    foreach ($data['business_outlet_details'] as $key => $ol) {
                        $outlet[] = $ol['business_outlet_id'];
                    }
                    
                    $activity =$this->BusinessAdminModel->GetOutLetSMSActivity($outlet);
                    $ac = [];
                    if($activity['success']){
                        $activity = $activity['res_arr'];                        
                        foreach ($activity as $key => $a) {
                            $ac[] = $a['outlet_id']."_".$a['services_number'];
                        }
                    }  
										$data['activity'] = $ac;       
										$data['sidebar_collapsed']="true";
										// $this->PrettyPrintArray($data);
					$this->load->view('business_admin/ba_autoEngage_view',$data);
			}
			else{
				$this->LogoutUrl(base_url()."BusinessAdmin/");
			}	
		}
		public function AddTrigger(){
			if($this->IsLoggedIn('business_admin')){
				if(isset($_POST) && !empty($_POST)){ 
					$this->form_validation->set_rules('trigger', 'Trigger Type', 'trim|required');
				// 	$this->form_validation->set_rules('day_to_trigger', 'Days', 'trim|required');
				// 	$this->form_validation->set_rules('offer', 'Offer', 'trim|required');
					$this->form_validation->set_rules('business_outlet_id', 'Business Outlet Id', 'trim|required');
	
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
							'trigger_type' 	=> $this->input->post('trigger'),
							'trigger_days' 	=> 1,
							'offer_type'						=>"Default",
							'is_active'						=>1,
							'business_outlet_id' 	=> $this->input->post('business_outlet_id'),
							'business_admin_id' 	=> $this->session->userdata['logged_in']['business_admin_id']
						);
							$where=array(
							'business_outlet_id' 	=> $this->input->post('business_outlet_id'),
							'business_admin_id' 	=> $this->session->userdata['logged_in']['business_admin_id'],
							'trigger_type' 	=> $this->input->post('trigger')
						);
						$trigger_exist=$this->BusinessAdminModel->CheckTriggerExist($where);
				
						if($trigger_exist['success']=='true'){
							$result = $this->BusinessAdminModel->Insert($data,'mss_auto_engage');
							if($result['success'] == 'true'){
								$this->ReturnJsonArray(true,false,"Trigger added successfully!");
								die;
							}
							elseif($result['error'] == 'true'){
								$this->ReturnJsonArray(false,true,$result['message']);
								die;
							}
						}elseif($trigger_exist['success']=='false'){
							$this->ReturnJsonArray(false,true,'Trigger Already exist.');
							die;
						}else{
							$this->ReturnJsonArray(false,true,'Error.');
							die;
						}						
					}
				}
				else{
					$this->ReturnJsonArray(false,true,"Invalid Method");
					die;
				}
			}
			else{
				$this->LogoutUrl(base_url()."BusinessAdmin/");
			}	
		}
		
		
	//Due Amount SMS
	public function SendPendingAmountSms(){
		if($this->IsLoggedIn('business_admin')){	
			if(isset($_POST) && !empty($_POST)){
				$this->form_validation->set_rules('business_outlet_id', 'Outlet Id', 'trim|required');
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
						$data = array(
							'business_outlet_id'     => $this->input->post('business_outlet_id'),
							'business_admin_id' 		=> $this->session->userdata['logged_in']['business_admin_id']
						);
						$result = $this->BusinessAdminModel->GetDueCustomer($data);

						// $this->PrettyPrintArray($result);
						
						if($result['success'] == 'true'){
							//Send Upadate Appointment SMS
							$result =$result['res_arr'];
							foreach($result as $res){
								$this->SendDueAmountSms($res['customer_name'],$res['customer_mobile'],$res['customer_pending_amount'],$res['business_outlet_name'],$res['business_outlet_location'],$res['business_outlet_sender_id'],$res['api_key']);
							}
							$this->ReturnJsonArray(true,false,"Message Send.");
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
	
	//Due Amount SMS
	public function ReSendBill(){
		if($this->IsLoggedIn('business_admin')){	
			$this->load->helper('ssl');//loading pdf helper
			// $this->PrettyPrintArray($_POST);
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
					if($_POST['txn_type']=='Service'){
						$data=array(
							'txn_id' => $this->input->post('txn_id'),
							'business_outlet_id' => $this->session->userdata['outlets']['current_outlet']
						);
						$result = $this->BusinessAdminModel->GetCustomerBill($data);			
            //             echo $this->db->last_query();
						// die('Test');
						if($result['success'] == 'true'){
							//ReSend Bill SMS
							$res =$result['res_arr'][0];
							
							$customer_id=$res['customer_id'];
							$detail_id=$res['id'];
							$bill_url = base_url()."Cashier/generateBill/$customer_id/".base64_encode($detail_id);
            	$bill_url = str_replace("https", "http", $bill_url);
							$bill_url = shortUrl($bill_url);
							$this->ReSendBillSms($res['customer_name'],$res['customer_mobile'],$res['business_outlet_name'],$res['txn_value'], $res['sender_id'],$res['api_key'], $bill_url);
							$this->ReturnJsonArray(true,false,"Message Send.");
							die;
						}

					}else{
						$data=array(
							'package_txn_id' => $this->input->post('txn_id'),
							'business_outlet_id' => $this->session->userdata['outlets']['current_outlet']
						);
						$result = $this->BusinessAdminModel->GetCustomerPackageBillDetails($data);	
            // $this->PrettyPrintArray($result);

							if($result['success'] == 'true'){
								//ReSend Bill SMS
								$res =$result['res_arr'][0];							
								// $customer_id=$res['customer_id'];
								// $detail_id=$res['id'];
								// $bill_url = base_url()."Cashier/generateBill/$customer_id/".base64_encode($detail_id);
								// $bill_url = str_replace("https", "http", $bill_url);
								// $bill_url = shortUrl($bill_url);
								$this->ReSendPackageBillSms($res['customer_name'],$res['customer_mobile'],$res['salon_package_name'],$res['salon_package_validity'], $res['sender_id'],$res['api_key']);
								$this->ReturnJsonArray(true,false,"Message Send.");
								die;
							}else{
							$this->ReturnJsonArray(false,true,$result['message']);
							die;
							}
					}
				}
			}
		}else{
			$this->LogoutUrl(base_url()."BusinessAdmin/Login/");
		}
	}

	//Send Update Appointment SMS
	public function SendDueAmountSms($customer_name,$mobile,$pending_amount,$outlet_name,$location,$sender_id,$api_key){
		if($this->IsLoggedIn('businss_admin')){
		
			//API key & sender ID
			// $apikey = "ll2C18W9s0qtY7jIac5UUQ";
			// $apisender = "BILLIT";
			$msg = "Dear ".$customer_name.", kindly clear your dues of ".$pending_amount." at ".$outlet_name.",".$location.". Looking forward serving you again.Team ".$outlet_name.".";
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
		
		//get trigger
		public function GetTrigger(){
			if($this->IsLoggedIn('business_admin')){
				if(isset($_GET) && !empty($_GET)){
					$data = $this->BusinessAdminModel->DetailsById($_GET['auto_engage_id'],'mss_auto_engage','auto_engage_id');
					header("Content-type: application/json");
					print(json_encode($data['res_arr'], JSON_PRETTY_PRINT));
					die;
				}
			}else{
				$this->LogoutUrl(base_url()."BusinessAdmin/");
			}
		}
		//edit trigger
		public function EditTrigger(){
			if($this->IsLoggedIn('business_admin')){
				if(isset($_POST) && !empty($_POST)){
					$this->form_validation->set_rules('trigger', 'Trigger', 'trim|required');
					$this->form_validation->set_rules('day_to_trigger', 'Day to Trigger', 'trim|required');
					$this->form_validation->set_rules('offer', 'Offer', 'trim|required');
					$this->form_validation->set_rules('auto_engage_id', 'Auto Engage', 'trim|required');			
	
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
							'auto_engage_id'    => $this->input->post('auto_engage_id'),
							'trigger_type' 	=> $this->input->post('trigger'),
							'trigger_days' 	=> $this->input->post('day_to_trigger'),
							'offer_type' 	=> $this->input->post('offer'),
						);
	
						$result = $this->BusinessAdminModel->Update($data,'mss_auto_engage','auto_engage_id');
							
						if($result['success'] == 'true'){
							$this->ReturnJsonArray(true,false,"Trigger Updated Successfully!");
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
				$this->LogoutUrl(base_url()."BusinessAdmin/");
			}	
		}
	
		//delete trigger
		public function CancelTrigger(){
			if($this->IsLoggedIn('business_admin')){
				// $this->PrettyPrintArray($_POST);
				// exit;
				if(isset($_POST) && !empty($_POST)){
					$this->form_validation->set_rules('auto_engage_id', 'Auto Engage', 'trim|required');			
	
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
							'auto_engage_id'    => $this->input->post('auto_engage_id'),
							'is_active'=>$this->input->post('is_active')
						);
	
						$result = $this->BusinessAdminModel->Update($data,'mss_auto_engage','auto_engage_id');
							
						if($result['success'] == 'true'){
							$this->ReturnJsonArray(true,false,"Trigger Updated Successfully!");
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
				$this->LogoutUrl(base_url()."BusinessAdmin/");
			}	
		}
		//suspend trigger
	
		//data for selected trigger
		public function SelectedTrigger(){
			if($this->IsLoggedIn('business_admin')){
				// $this->PrettyPrintArray($_POST);
				// exit;
				if(isset($_POST) && !empty($_POST)){
					$this->form_validation->set_rules('auto_engage_id', 'Auto Engage', 'trim|required');			
	
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
							'auto_engage_id'    => $this->input->post('auto_engage_id'),
							'is_active'=>$this->input->post('is_active')
						);
	
						$result = $this->BusinessAdminModel->Update($data,'mss_auto_engage','auto_engage_id');
							
						if($result['success'] == 'true'){
							$this->ReturnJsonArray(true,false,"Trigger Updated Successfully!");
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
				$this->LogoutUrl(base_url()."BusinessAdmin/");
			}	
		}
		
		//Deals & Discount
	public function DealsDiscount(){
		if($this->IsLoggedIn('business_admin')){
			$where=array(
				'business_admin_id'=> $this->session->userdata['logged_in']['business_admin_id'],
				'business_outlet_id'=> $this->session->userdata['outlets']['current_outlet']
			);
				$data = $this->GetDataForAdmin("Deals & Discount");
				$data['business_outlet_details'] = $this->GetBusinessOutlets();
				$data['deals']=$this->BusinessAdminModel->ActiveDeals($where);
				$data['deals']=$data['deals']['res_arr'];
				$data['tag']=$this->BusinessAdminModel->GetTag($where);
				$data['tag']=$data['tag']['res_arr'];
				// $this->PrettyPrintArray($where);

				$data['deal_redeemed']=$this->BusinessAdminModel->GetDealRedemption($where);

				$data['deal_redeemed']=$data['deal_redeemed']['res_arr'];
				// exit;
				$this->load->view('business_admin/ba_deals&discount_view',$data);
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}	
	}

	public function AddDeals(){	    
		if($this->IsLoggedIn('business_admin')){
// 			$this->PrettyPrintArray($_POST);
			if(isset($_POST) && !empty($_POST)){ 
				$this->form_validation->set_rules('tag_name', 'Tag Name', 'trim|required');
				$this->form_validation->set_rules('deal_name', 'Deal NAme', 'trim|required');
				$this->form_validation->set_rules('deal_code' ,'Deal Code', 'trim|required');
				$this->form_validation->set_rules('daterange', 'Date Range', 'trim|required');
				$this->form_validation->set_rules('start_time', 'Satrt Time', 'trim|required');
				$this->form_validation->set_rules('end_time', 'End Time', 'trim|required');
				$this->form_validation->set_rules('weekend', 'Weekends', 'trim|required');
				$this->form_validation->set_rules('national_holiday', 'National Holidays', 'trim|required');
				$this->form_validation->set_rules('bday_anni', 'Birthday Anniversary', 'trim|required');
				$this->form_validation->set_rules('weekday', 'Weekday', 'trim|required');
				$this->form_validation->set_rules('benifit_type', 'Benifit Type', 'trim|required');
				$this->form_validation->set_rules('minimum_amt', 'Minimum Amount', 'trim|required');
				$this->form_validation->set_rules('maximum_amt', 'Maximum Amount', 'trim|required');
				$this->form_validation->set_rules('discount', 'Discount', 'trim|required');

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
						'tag_id' 						=> $this->input->post('tag_name'),
						'deal_name' 					=> $this->input->post('deal_name'),
						'deal_code'						=>strtoupper($this->input->post('deal_code')),
						'deal_price'					=>0,
						'start_date' 					=> substr($this->input->post('daterange'),0,10),
						'end_date' 						=> substr($this->input->post('daterange'),13,23),
						'start_time' 					=> $this->input->post('start_time'),
						'end_time'						=>$this->input->post('end_time'),
						'weekend'							=>$this->input->post('weekend'),
						'national_holiday' 		=> $this->input->post('national_holiday'),
						'bday_anni' 					=> $this->input->post('bday_anni'),
						'weekday'							=>$this->input->post('weekday'),
						'benifit_type'				=>$this->input->post('benifit_type'),
						'minimum_amt'					=>$this->input->post('minimum_amt'),
						'maximum_amt' 				=> $this->input->post('maximum_amt'),
						'total_services'			=>$this->input->post('total_service'),
						'discount'						=>$this->input->post('discount'),
						'deal_for'						=>$this->input->post('tag_name'),
						'deal_description'		=>$this->input->post('deal_description'),
						'deal_business_outlet_id' 	=> $this->session->userdata['outlets']['current_outlet'],
						'deal_business_admin_id' 	=> $this->session->userdata['logged_in']['business_admin_id']
					);
					$where=array(
						'business_outlet_id' 	=>	$this->session->userdata['outlets']['current_outlet'],
						'business_admin_id'		=> 	$this->session->userdata['logged_in']['business_admin_id']
					);
					$result = $this->BusinessAdminModel->AddSchemeServices($data,$where);

					if($result['success'] == 'true'){
						$this->ReturnJsonArray(true,false,"Scheme configured successfully!");
						die;
					}elseif($result['error'] == 'true'){
						$this->ReturnJsonArray(false,true,$result['message']);
						die;
					}			
				}
			}
			else{
				$this->ReturnJsonArray(false,true,"Invalid Method");
				die;
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}	
	}
		
		//emss Loyalty
		//Pritams Code for EMSS*********************


    // opening page for commission details
    public function Commission_opening()
{
	if($this->IsLoggedIn('business_admin')){
		$data = $this->GetDataForAdmin("Employee_details");
		$data['business_outlet_details'] = $this->GetBusinessOutlets();
		$data['business_admin_employees']  = $this->BusinessAdminModel->GetBusinessAdminEmployees();
		$data['business_admin_employees']=$data['business_admin_employees']['res_arr'];
		$data['commission_details']=$this->BusinessAdminModel->commission_details();
		// $data['commission_details_All']=$this->BusinessAdminModel->commission_details_All();
		$data['sidebar_collapsed']="true";
		// $this->PrettyPrintArray($data['commission_details']);
		// exit;
		$this->load->view('business_admin/ba_emss_commission_opening',$data);
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}	
}
    
    public function UpdateCommission(){
	if($this->IsLoggedIn('business_admin')){
		if(isset($_POST) && !empty($_POST)){
			// $this->PrettyPrintArray($_POST);
			// exit;
				$data = array(
					'comm_id' =>$this->input->post('comm_id'),
					'names' 	=> $this->input->post('name'),
					'months' 	=> $this->input->post('month'),
					'targets' 	=> $this->input->post('target'),
					'base_value' 	=> $this->input->post('basevalue'),
					'set_target1' 	=> $this->input->post('range1'),
					'set_target2' 	=> $this->input->post('range2'),
					'commision' 	=> $this->input->post('comm')
				);
				
				// $this->PrettyPrintArray($data);
				// exit;
				$result = $this->BusinessAdminModel->Update($data,'mss_emss_commision','comm_id');
					
				if($result['success'] == 'true'){
					$this->ReturnJsonArray(true,false,"Commission updated successfully!");
					die;
	 			 }
	  			elseif($result['error'] == 'true'){
		 		 $this->ReturnJsonArray(false,true,$result['message']);
				die;
	  			}
		}
	}
	
	else{
		$this->LogoutUrl(base_url()."BusinessAdmin/");
	}	
}
    
    public function Commission_edit(){
	if($this->IsLoggedIn('business_admin')){
		// $this->PrettyPrintArray($_GET);
		// exit;
		$data=array('comm_id' => $_GET['comm_id'],
		'comm_name'=>$_GET['comm_name'],
				'month'=>$_GET['month']	
	);
		$result=$this->BusinessAdminModel->commission_edit($data);
		// $this->PrettyPrintArray($result);
		// exit;
		if($result['success'] == 'true'){		
			header("Content-type: application/json");
			print(json_encode($result['res_arr'][0], JSON_PRETTY_PRINT));
			die;	
		}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}	
    }
    
    public function DeleteCommission(){
	if($this->IsLoggedIn('business_admin')){
		if(isset($_GET) && !empty($_GET)){
			// $this->PrettyPrintArray($_GET);
			// exit;
				$data=array(
					'comm_id'=>$_GET['comm_id'],
					'emp_id'=>$_GET['emp_id'],
					'names'=>$_GET['names'],
					'months'=>$_GET['month']
				);
				
				// $this->PrettyPrintArray($data);
				// exit;
				$result = $this->BusinessAdminModel->DeleteCommission($data);
					
				if($result['success'] == 'true'){
					$this->ReturnJsonArray(true,false,"Commission Deleted successfully!");
					die;
	 			 }
	  			elseif($result['error'] == 'true'){
		 		 $this->ReturnJsonArray(false,true,$result['message']);
				die;
	  			}
		}
	}
	
	else{
		$this->LogoutUrl(base_url()."BusinessAdmin/");
	}	
}

// attendance view

public function ba_emss_attendance(){
	if($this->IsLoggedIn('business_admin')){
		$attendance_data['attendance']=$this->BusinessAdminModel->ba_emss_attendance();
		$this->PrettyPrintArray($attendance_data['attendance']);
		exit;
		$this->load->view('business_admin/ba_emss_attendance_view',$attendance_data);
	}
	else{
		$this->LogoutUrl(base_url()."BusinessAdmin/");
	}	
}

// set commission  
public function SetCommission()
{
	if($this->IsLoggedIn('business_admin')){
		$where=array(
			'business_admin_id'=> $this->session->userdata['logged_in']['business_admin_id']
		);
			$data = $this->GetDataForAdmin("Employee_details");
			$data['business_outlet_details'] = $this->GetBusinessOutlets();
			$data['business_admin_employees']  = $this->GetBusinessAdminEmployees();
				// $this->PrettyPrintArray($data);
				// exit;
			$data['emp']=$this->BusinessAdminModel->NewCommission($data);
			
			$data['sidebar_collapsed']="true";
			// $this->PrettyPrintArray($data['emp']);
			// exit;
			$this->load->view('business_admin/ba_emss_commission_view',$data);
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}	


	}

//set commission for all employees
	public function SetCommissionAll()
{
	if($this->IsLoggedIn('business_admin')){
		$where=array(
			'business_admin_id'=> $this->session->userdata['logged_in']['business_admin_id']
		);
			$data = $this->GetDataForAdmin("Employee_details");
			$data['business_outlet_details'] = $this->GetBusinessOutlets();
			$data['business_admin_employees']  = $this->GetBusinessAdminEmployees();
			
			// $data['emp']=$this->BusinessAdminModel->NewCommissionAll($data);
			$data['sidebar_collapsed']="true";
			// $this->PrettyPrintArray($data);
			// exit;
		
			$this->load->view('business_admin/ba_emss_commission_view_all',$data);
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}	


	}
//raw function
		public function Commission(){
		
		if($this->IsLoggedIn('business_admin')){
			$where=array(
				'business_admin_id'=> $this->session->userdata['logged_in']['business_admin_id']
			);
				$data = $this->GetDataForAdmin("Employee_details");
				$data['business_outlet_details'] = $this->GetBusinessOutlets();
				$data['business_admin_employees']  = $this->GetBusinessAdminEmployees();
		$data['sidebar_collapsed']="true";
				// $this->PrettyPrintArray($_GET);
				// exit;
				$commission = array(
					'month_name' 			 	=> $_GET['month'],
					'expert_id' 				 => $_GET['expert_id'],
					'base_kpi'					=>$_GET['base_kpi'],
					'business_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
					'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id']
				);
				
				$result=$this->BusinessAdminModel->CommissionGetSalary($commission);
			
				if($result['success'] == 'true'){		
					header("Content-type: application/json");
					print(json_encode($result['res_arr'][0], JSON_PRETTY_PRINT));
					die;	
				}
				
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}	
	}
//Insert values in table for setting commission
	public function InsertCommission(){
		$year=$_POST['year'];
		$month=$_POST['month_name'];
		$_POST['month_name']=$year.'-'.$month;
		// $this->PrettyPrintArray($_POST);
		// exit;
		
		if($this->IsLoggedIn('business_admin')){
			$data = $this->GetDataForAdmin("Employee_details");
			$data['business_outlet_details'] = $this->GetBusinessOutlets();
			$where=array(
			'employee_business_admin' =>$this->session->userdata['logged_in']['business_admin_id'],
			'employee_is_active'      =>1
			);
			// $data['business_admin_employees']  = $this->BusinessAdminModel->MultiWhereSelect('mss_employees', $where);
			$data['business_admin_employees']  = $this->BusinessAdminModel->GetBusinessAdminEmployees('mss_employees', $where);
			$data['business_admin_employees'] = $data['business_admin_employees']['res_arr'];
			$counter  = $this->BusinessAdminModel->Countercommission();
			$counter=$counter['res_arr'][0]['max'];
			// $this->PrettyPrintArray($counter);
				$data['sidebar_collapsed']="true";
				if(isset($_POST) && $_POST!=''){
				if($_POST['expert_id']==0 && $_POST['commission_name']=='gross_salary'){        
					foreach($_POST['start_range'] as $key=>$value){
					for($i=0;$i< count($data['business_admin_employees']);$i++){
						$data_arr=array(
						'names' => $this->input->post('name'),
						'counter'=>$counter+1,
						'months'=> $this->input->post('month_name'),  
						'emp_id'=>$data['business_admin_employees'][$i]['employee_id'], 
						'commission_type'=>$this->input->post('commission_type'),
						'base_kpi'=>$this->input->post('commission_name'),
						'target_multiplier'=>$this->input->post('t1'), 
						'target_base_kpi'=>$this->input->post('t2'), 
						'targets'=>($data['business_admin_employees'][$i]['employee_gross_salary']*$this->input->post('t1')),
						'set_target1' => $_POST['start_range'][$key],
						'set_target2' => $_POST['end_range'][$key], 
						'commision' => $_POST['comm'][$key],
						'base_value'=>$_POST['basevalue'][$key],
						'base_kpi_amt'=>$data['business_admin_employees'][$i]['employee_gross_salary'],
						'comm'=>(($data['business_admin_employees'][$i]['employee_gross_salary']*$_POST['comm'][$key])/100),
						'new_base_value'=>$_POST['newbasevalue'][$key],
						'bussiness_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
						'business_admin_id'=> $this->session->userdata['logged_in']['business_admin_id'],
						'group_by'=>'All'
						);
						//Insert data into Commission Table
						$result = $this->BusinessAdminModel->Insert($data_arr,'mss_emss_commision');
					}
					}
					$this->ReturnJsonArray(true , false,'Commission Inserted Successfully.' );
					exit;
				}else if($_POST['expert_id']==0 && $_POST['commission_name']=='Avg_sales_mrp'){
					foreach($_POST['start_range'] as $key=>$value){
					for($i=0;$i< count($data['business_admin_employees']);$i++){
						$emp=array(
							'emp_id'=>$data['business_admin_employees'][$i]['employee_id']
						);
						$data['employees_avg_service_sales']  = $this->BusinessAdminModel->GetEmployeesServiceSales($emp);
						$data['employees_avg_service_sales']= $data['employees_avg_service_sales']['res_arr'];
						//   $this->PrettyPrintArray($data['employees_avg_service_sales']);
						// exit;
						if(isset($data['employees_avg_service_sales']) && ($data['employees_avg_service_sales'])==!null)
						{
							$data['employees_avg_service_sales']=$data['employees_avg_service_sales'][0]['service_sales'];
						}
						else{
							$data['employees_avg_service_sales']=0;
						}
						
						$data_arr=array(
						'names' => $this->input->post('name'),
						'counter'=>$counter+1,
						'months'=> $this->input->post('month_name'),  
						'emp_id'=>$data['business_admin_employees'][$i]['employee_id'], 
						'commission_type'=>$this->input->post('commission_type'),
						'base_kpi'=>$this->input->post('commission_name'),
						'target_multiplier'=>$this->input->post('t1'), 
						'target_base_kpi'=>$this->input->post('t2'), 
						'targets'=>($data['employees_avg_service_sales']*$this->input->post('t1')),
						'set_target1' => $_POST['start_range'][$key],
						'set_target2' => $_POST['end_range'][$key], 
						'commision' => $_POST['comm'][$key],
						'base_value'=>$_POST['basevalue'][$key],
						'base_kpi_amt'=>$data['employees_avg_service_sales'],
						'comm'=>(($data['employees_avg_service_sales']*$_POST['comm'][$key])/100),
						'new_base_value'=>$_POST['newbasevalue'][$key],
						'bussiness_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
						'business_admin_id'=> $this->session->userdata['logged_in']['business_admin_id'],
						'group_by'=>'All'
						);
						//Insert data into Commission Table
						$result = $this->BusinessAdminModel->Insert($data_arr,'mss_emss_commision');
					}
					}
					$this->ReturnJsonArray(true , false,'Commission Inserted Successfully.' );
					exit;
				}else if($_POST['expert_id']==0 && $_POST['commission_name']=='Avg_total_disc_price'){
					foreach($_POST['start_range'] as $key=>$value){
					for($i=0;$i< count($data['business_admin_employees']);$i++){
						$commission=array(
							'expert_id'=>$data['business_admin_employees'][$i]['employee_id']
						);
						$comm['net_total']=$this->BusinessAdminModel->CommissionTotalSales($commission);
						if(isset($comm['net_total']))
						{
						$comm['net_total']=$comm['net_total']['res_arr'][0]['total'];
						}
						else{
							$comm['net_total']=0;
						} 
						$data_arr=array(
						'names' => $this->input->post('name'),
						'counter'=>$counter+1,
						'months'=> $this->input->post('month_name'),  
						'emp_id'=>$data['business_admin_employees'][$i]['employee_id'], 
						'commission_type'=>$this->input->post('commission_type'),
						'base_kpi'=>$this->input->post('commission_name'),
						'target_multiplier'=>$this->input->post('t1'), 
						'target_base_kpi'=>$this->input->post('t2'), 
						'targets'=>$comm['net_total']*$this->input->post('t1'),
						'set_target1' => $_POST['start_range'][$key],
						'set_target2' => $_POST['end_range'][$key], 
						'commision' => $_POST['comm'][$key],
						'base_value'=>$_POST['basevalue'][$key],
						'base_kpi_amt'=>$comm['net_total'],
						'comm'=>(($comm['net_total']*$_POST['comm'][$key])/100),
						'new_base_value'=>$_POST['newbasevalue'][$key],
						'bussiness_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
						'business_admin_id'=> $this->session->userdata['logged_in']['business_admin_id'],
						'group_by'=>'All'
						);
						//Insert data into Commission Table
						$result = $this->BusinessAdminModel->Insert($data_arr,'mss_emss_commision');
					}
					}
					$this->ReturnJsonArray(true , false,'Commission Inserted Successfully.' );
					exit;
				}
				else if($_POST['expert_id']==0 && $_POST['commission_name']=='none'){
					foreach($_POST['start_range'] as $key=>$value){
					for($i=0;$i< count($data['business_admin_employees']);$i++){
						$data_arr=array(
						'names' => $this->input->post('name'),
						'counter'=>$counter+1,
						'months'=> $this->input->post('month_name'),  
						'emp_id'=>$data['business_admin_employees'][$i]['employee_id'], 
						'commission_type'=>$this->input->post('commission_type'),
						'base_kpi'=>$this->input->post('commission_name'),
						'target_multiplier'=>0, 
						'target_base_kpi'=>0, 
						'targets'=>$this->input->post('t3'),
						'set_target1' => $_POST['start_range'][$key],
						'set_target2' => $_POST['end_range'][$key], 
						'commision' => $_POST['comm'][$key],
						'base_value'=>$_POST['basevalue'][$key],
						'base_kpi_amt'=>$this->input->post('t3'),
						'comm'=>0,
						'new_base_value'=>$_POST['newbasevalue'][$key],
						'bussiness_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
						'business_admin_id'=> $this->session->userdata['logged_in']['business_admin_id'],
						'group_by'=>'All'
						);
						//Insert data into Commission Table
						$result = $this->BusinessAdminModel->Insert($data_arr,'mss_emss_commision');
					}
					}
					$this->ReturnJsonArray(true , false,'Commission Inserted Successfully.' );
					exit;
				}else{
					//   $this->PrettyPrintArray($_POST);
					//   exit;
					$start_range=$this->input->post('start_range[]');
					$end_range=$this->input->post('end_range[]');
					$comm=$this->input->post('comm[]');
					$BaseValue=$this->input->post('basevalue[]');           
					$base=array('basevalue'=>$_POST['basevalue'],'expert'=>$_POST['expert_id']);
					$tmp=$_POST['expert_id'];
					$temp=array();
					$comm_calc=array();
					// calling model for insertion
						foreach($base['basevalue'] as $key=>$value)
						{
							// array to send expertid and basevalue to get basekpi amt
							$abb=array($value,$tmp);                    
							$result=$this->BusinessAdminModel->CommissionBaseValue($abb);
							$result=$result['res_arr'][0];
							foreach($result as $key=>$value)
							{   
								array_push($temp,$value);   
							}
						}
						// traversal for insertion of the data
						foreach($start_range as $key=>$value)
						{
						$res=array(
							'names' => $this->input->post('name'),
							'counter'=>$counter+1,
							'months'=> $this->input->post('month_name'),  
							'emp_id'=>$this->input->post('expert_id'), 
							'commission_type'=>$this->input->post('commission_type'),
							'base_kpi'=>$this->input->post('commission_name'),
							'target_multiplier'=>$this->input->post('t1'), 
							'target_base_kpi'=>$this->input->post('t2'), 
							'targets'=>$this->input->post('t3'),
							'set_target1' => $start_range[$key],
							'set_target2' => $end_range[$key], 
							'commision' => $comm[$key],
							'base_value'=>$BaseValue[$key],
							'base_kpi_amt'=>$temp[$key],
							'comm'=>($temp[$key]*$comm[$key])/100,
							'new_base_value'=>$_POST['newbasevalue'][$key],
							'bussiness_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
							'business_admin_id'=> $this->session->userdata['logged_in']['business_admin_id']                    
							);
							// calling model to insett data
							$result = $this->BusinessAdminModel->Insert($res,'mss_emss_commision');                   
						}                   
						if($result['success'] == 'true'){
							//redirecting to main Controller SetCommission 
							$this->ReturnJsonArray(true , false,'Commission Inserted Successfully.' );
							exit;
						}else{
							$this->ReturnJsonArray(false , true,'Commission failed.' );
							exit;
						}               
				}
				}else{
				$this->ReturnJsonArray(false,true,'Wrong Method!');
				}             
			}
			else{
				$this->LogoutUrl(base_url()."BusinessAdmin/");
			}   
  	}

	//Insert Commission value for All empployees 

	public function InsertCommissionAll()
	{
		if($this->IsLoggedIn('business_admin')){
			$where=array(
				'business_admin_id'=> $this->session->userdata['logged_in']['business_admin_id']
			);
				$data = $this->GetDataForAdmin("Employee_details");
				$data['business_outlet_details'] = $this->GetBusinessOutlets();
				$data['business_admin_employees']  = $this->GetBusinessAdminEmployees();
				
				
				
				$data['sidebar_collapsed']="true";
				
					
					$start_range=$this->input->post('start_range[]');
					$end_range=$this->input->post('end_range[]');
					$comm=$this->input->post('comm[]');
					$BaseValue=$this->input->post('BaseValue[]');
					

					// traversal for insertion of the data
					foreach($start_range as $key=>$value)
					{
						$res=array(
							'name' => $this->input->post('name'),
							'month'=> $this->input->post('month_name'),  
							// 'emp_id'=>$this->input->post('expert_id'), 
							'base_kpi'=>$this->input->post('commission_name'),
							'target_multiplier'=>$this->input->post('t1'), 
							// 'target_base_kpi'=>$this->input->post('t2'), 
							// 'targets'=>$this->input->post('t3'),
							'set_target1' => $start_range[$key],
							'set_target2' => $end_range[$key], 
							'comm_perc' => $comm[$key],
							'base_value'=>$BaseValue[$key],
							// 'base_kpi_amt'=>$temp[$key],
							// 'bussiness_admin_id	'=>$this->input->post('outlet_id'),
							'bussiness_outlet_id	'=>$this->input->post('outlet_id'),
							
						);
						// caalling model to insett data
						$result = $this->BusinessAdminModel->Insert($res,'mss_emss_commision_all');
						
					} 
					
						
						if($result['success'] == 'true'){
							//redirecting to main Controller SetCommission 
						?>
							<script>alert("Data Inserted");window.location.href="<?=base_url()?>/BusinessAdmin/SetCommission";</script>
						<?php
						}
						elseif($result['error'] == 'true'){
							
						}
						
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}	
	}


//EMSS Attendance
public function Attendance(){
    if($this->IsLoggedIn('business_admin')){
        $data = $this->GetDataForAdmin("Employee_details");
        
        if(isset($data['selected_outlet']) || !empty($data['selected_outlet'])){
            $where=array(
                'business_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
                'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id']
            );
                $data = $this->GetDataForAdmin("Employee_details");
                $data['business_outlet_details'] = $this->GetBusinessOutlets();
                $data['business_admin_employees']  = $this->GetBusinessAdminEmployees();
                $data['attendance']= $this->BusinessAdminModel->GetAttendance($where);
                $data['attendance']= $data['attendance']['res_arr'];
                    if(!isset($_GET) || empty($_GET)){
                        //Load the default view
                        $data['holidays']=$this->BusinessAdminModel->EmssGetHolidaysLoad($data);
                        $data['holidays']=$data['holidays']['res_arr'][0]['holiday'];
                        $data['halfday']=$this->BusinessAdminModel->GetHalfDayAttendanceLoad($data);
                        $data['halfday']=$data['halfday']['res_arr'];
    						//Code for WeekOff Count
    						// $data['weekoff']=$this->BusinessAdminModel->CalWeekOff($data);
    						// $data['weekoff']=$data['weekoff']['res_arr'];
    						// echo count($data['weekoff']);
    						$data['weekoff']=$this->BusinessAdminModel->GetCalWeekOff($data);
    						$data['weekoff']=$data['weekoff']['res_arr'];
    						for($i=0;$i<count($data['weekoff']);$i++){
    							$data['weekoff'][$i]+=['count'=>0];
    						}
    						// $this->PrettyPrintArray($data['weekoff']);
    						for($i=0;$i<count($data['weekoff']);$i++){
    							
    							if($data['weekoff'][$i]['weekoff'] == 'null' || $data['weekoff'][$i]['weekoff'] == null || $data['weekoff'][$i]['weekoff'] == '[]'){
    								$data['weekoff'][$i]['count']=0;
    							}
    							else{
    								$temp=json_decode($data['weekoff'][$i]['weekoff']);
    								$count=0;
    								for($j=0;$j < count($temp);$j++){
    									if(date('Y-m-d') > $temp[$j]){
    										$count=$count+1;
    									}
    									$data['weekoff'][$i]['count']=$count;
    								}
    								
    								// $this->PrettyPrintArray($temp);
    							}
    						}
    						// $this->PrettyPrintArray($data['weekoff']);
    						// exit;
    					$res = array(
    						// 'weekoff'=>$data['weekoff'][],
    						'holiday'=>$data['holidays'],
                            'business_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
                            'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id']
                  	  );
    					$data['attendance_details']=$this->BusinessAdminModel->GetAttendanceDetails($res);
    					$data['attendance_details']=$data['attendance_details']['res_arr'];
    					
                        for($i=0;$i<count($data['attendance_details']);$i++)
                        {
                            $data['attendance_details'][$i]+=['halfday'=>'0'];
                            $data['attendance_details'][$i]+=['weekoff'=>'0'];
    					}
    					
                        foreach($data['attendance_details'] as $key=>$value)
                        {
                            foreach($data['halfday'] as $k=>$v)
                            {
                                if($value['employee_id'] == $v['employee_id'])
                                {
                                    $data['attendance_details'][$key]['halfday']=$v['Half_Day'];
                                }
                            }
    					}
    					// $this->PrettyPrintArray($data['weekoff']);
    					foreach($data['attendance_details'] as $key=>$value)
                        {
                            foreach($data['weekoff'] as $k=>$v)
                            {
                                if($value['employee_id'] == $v['employee_id'])
                                {
                                    $data['attendance_details'][$key]['weekoff']=$v['count'];
                                }
                            }
    					}
    					// $this->PrettyPrintArray($data['attendance_details']);
    					for($i=0;$i<count($data['attendance_details']);$i++)
                        {
                            $data['attendance_details'][$i]['Working-Days']=$data['attendance_details'][$i]['Calender_Days']-($data['attendance_details'][$i]['weekoff']+$data['holidays']);
    						$data['attendance_details'][$i]['Absent']=$data['attendance_details'][$i]['Absent']-$data['attendance_details'][$i]['weekoff'];
    					}
    					// $this->PrettyPrintArray($data['attendance_details']);
    					
                    }
                    else if(isset($_GET) && !empty($_GET)){
    					$year=$_GET['year'];
    					$month=$_GET['month'];
    					$_GET['month']=$year.'-'.$month;
    					$data = array(
                            'group_name'             => 'attendance',
                            'month'                  => $_GET['month'],
                            'business_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
                            'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id']
                    	);
    					// if($_GET['month']==date('m-Y'))
                        $res['holiday'] = $this->BusinessAdminModel->EmssGetHolidays($data);
                    	$res['holiday']=$res['holiday']['res_arr'][0]['holiday'];
                        $data = array(
                            'group_name'             => 'attendance',
                            'month'                  => $_GET['month'],
                            // 'to_date'                     => $_GET['to_date'],
                            'holiday'=>$res['holiday'],
                            'business_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
                            'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id']
                    	);
    					$result = $this->BusinessAdminModel->EmssAttendanceReport($data);
    						//For HAlf Day ANd Net Days Present 
    						// $data['holidays']=$result['res_arr'];        
    						for($i=0;$i<count($result['res_arr']);$i++)
    						{
    							$result['res_arr'][$i]+=['Net_Days_Present'=>'0'];
    							// $result['res_arr'][$i]+=['HalfDay'=>'0'];
    						}
    						$data['halfday']=$this->BusinessAdminModel->GetHalfDayAttendanceKpi($data);
    						$data['halfday']=$data['halfday']['res_arr'];
    							foreach($result['res_arr'] as $key=>$value)
    							{
    								foreach($data['halfday'] as $k=>$v)
    								{
    									if($value['employee_id'] == $v['employee_id'])
    									{
    										$result['res_arr'][$key]['Halfday']=(int)$v['Half_Day'];
    									}
    								}
    							}
    							for($i=0;$i<count($result['res_arr']);$i++)
    							{
    								$result['res_arr'][$i]['Net_Days_Present']=$result['res_arr'][$i]['Present']-($result['res_arr'][$i]['Halfday']*0.5);
    							}
    					// End of Code
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
        $data['sidebar_collapsed']="true";
        $this->load->view('business_admin/ba_emss_attendance_view',$data);
    }
    else{
        $this->LogoutUrl(base_url()."BusinessAdmin/");
    }   
}

//Attendance Empoyee wise monthly
public function AttendanceEMP()
{
	$data = array(
		'group_name' 			 => $_GET['group_name'],
		'month_name' 				 => $_GET['month_name'],
		'expert_id' 					 => $_GET['expert'],
		'business_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
		'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id']
);


	$result = $this->BusinessAdminModel->EmssAttendanceReport($data);
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
//Data for attendance page
	public function GetDataAttendance()
{	
	// $this->PrettyPrintArray($_GET);
	if($this->IsLoggedIn('business_admin')){
		$where=array(
			'business_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
			'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id']
		);
			$data = $this->GetDataForAdmin("Employee_details");
			$data['business_outlet_details'] = $this->GetBusinessOutlets();
			$data['business_admin_employees']  = $this->GetBusinessAdminEmployees();
			// $this->PrettyPrintArray($data['business_admin_employees']);
			// exit;
			$data['attendance']= $this->BusinessAdminModel->GetAttendance($where);
			$data['attendance']= $data['attendance']['res_arr'];
			$data['sidebar_collapsed']="true";
				if(!isset($_GET) || empty($_GET)){
					//Load the default view
					$this->load->view('business_admin/ba_emss_attendance_view',$data);
				}
				else if(isset($_GET) && !empty($_GET)){
					$data = array(
						'group_name' 			 => $_GET['group_name'],
						'month' 				 => $_GET['month'],
						// 'to_date' 					 => $_GET['to_date'],
						'business_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
						'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id']
					);
					// $m=$_GET['month'].'-00';
					// $this->PrettyPrintArray($data);
					$m=strtotime($_GET['month']);
					// echo date('m-Y',$m);
					if(date('m-Y',$m) == date('m-Y'))
					{	
						$data['holidays']=$this->BusinessAdminModel->EmssGetHolidaysLoad($data);
						$data['holidays']=$data['holidays']['res_arr'][0]['holiday'];
						$data['halfday']=$this->BusinessAdminModel->GetHalfDayAttendanceLoad($data);
						$data['halfday']=$data['halfday']['res_arr'];
							$data['weekoff']=$this->BusinessAdminModel->GetCalWeekOff($data);
							$data['weekoff']=$data['weekoff']['res_arr'];
							// $this->PrettyPrintArray($data['weekoff']);
							for($i=0;$i<count($data['weekoff']);$i++)
							{
								$data['weekoff'][$i]+=['count'=>0];
								if($data['weekoff'][$i]['weekoff'] == 'null' || $data['weekoff'][$i]['weekoff'] == null || $data['weekoff'][$i]['weekoff'] == '[]')
								{
									// $this->PrettyPrintArray("hii");
									$data['weekoff'][$i]['count']=0;
								}
								else{
									// $count=0;
									$temp=json_decode($data['weekoff'][0]['weekoff']);
									// $this->PrettyPrintArray($temp);
									// $data['weekoff'][$i]['count']=count($data['weekoff'][$i]['employee_weekoff']);
									$data['weekoff'][$i]['count']=count($temp);
									// $data['weekoff'][$i]['count']=count($data['weekoff'][$i]['employee_weekoff']);
									// $data['weekoff'][$i]['count']=substr_count($data['weekoff'][$i]['employee_weekoff'],',')+1;
								}
							}
							// $this->PrettyPrintArray($data['weekoff']);
						$res = array(
							// 'weekoff'=>$data['weekoff'][],
							'holiday'=>$data['holidays'],
							'business_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
							'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id']
						);
						$data['attendance_details']=$this->BusinessAdminModel->GetAttendanceDetails($res);
						$data['attendance_details']=$data['attendance_details']['res_arr'];
						// appending key as half day
						for($i=0;$i<count($data['attendance_details']);$i++)
						{
							$data['attendance_details'][$i]+=['halfday'=>'0'];
							$data['attendance_details'][$i]+=['month'=>date('M-Y')];
							$data['attendance_details'][$i]+=['weekoff'=>'0'];
							$data['attendance_details'][$i]+=['Net_Days_Present'=>'0'];
						}
						foreach($data['attendance_details'] as $key=>$value)
						{
							foreach($data['halfday'] as $k=>$v)
							{
								if($value['employee_id'] == $v['employee_id'])
								{
									$data['attendance_details'][$key]['halfday']=(int)$v['Half_Day'];
								}
							}
						}
						foreach($data['attendance_details'] as $key=>$value)
						{
							foreach($data['weekoff'] as $k=>$v)
							{
								if($value['employee_id'] == $v['employee_id'])
								{
									$data['attendance_details'][$key]['weekoff']=$v['count'];
								}
							}
						}
						for($i=0;$i<count($data['attendance_details']);$i++)
						{
							$data['attendance_details'][$i]['Working_Days']=$data['attendance_details'][$i]['Calender_Days']-($data['attendance_details'][$i]['weekoff']+$data['holidays']);
							$data['attendance_details'][$i]['Absent']=$data['attendance_details'][$i]['Absent']-$data['attendance_details'][$i]['weekoff'];
							$data['attendance_details'][$i]['Net_Days_Present']=$data['attendance_details'][$i]['Present']-($data['attendance_details'][$i]['halfday']*0.5);
						}	
						// $this->PrettyPrintArray($data['attendance_details']);
							$data = array(
										'success' => 'true',
										'error'   => 'false',
										'message' => 'current_month',
										'result' =>  $data['attendance_details']
										);
										header("Content-type: application/json");
										print(json_encode($data, JSON_PRETTY_PRINT));
										die;	
					}
					else{
							$res['holiday'] = $this->BusinessAdminModel->EmssGetHolidays($data);
							$res['holiday']=$res['holiday']['res_arr'][0]['holiday'];
							$data = array(
								'group_name' 			 => $_GET['group_name'],
								'month' 				 => $_GET['month'],
								// 'to_date' 					 => $_GET['to_date'],
								'holiday'=>$res['holiday'],
								'business_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
								'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id']
							);
							$result = $this->BusinessAdminModel->DataAttendanceShow($data);

							for($i=0;$i<count($result['res_arr']);$i++)
							{
								$result['res_arr'][$i]+=['Halfday'=>'0'];
								$result['res_arr'][$i]+=['weekoff'=>'0'];
								// $result['res_arr'][$i]+=['OverTime'=>'0'];
								$result['res_arr'][$i]+=['Net_Days_Present'=>'0'];
							}
							// $this->PrettyPrintArray($result);
							// exit;
							foreach($result['res_arr'] as $key=>$value)
							{
								
								$data = array(
									'group_name' 			 => $_GET['group_name'],
									'month' 				 => $_GET['month'],
									'emp_id' 					 => $value['employee_id'],
									'holiday'=>$res['holiday'],
									'business_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
									'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id']
								);
								// $this->PrettyPrintArray($data);
								$data['halfday']=$this->BusinessAdminModel->GetHalfDayAttendanceKpi($data);
								$data['halfday']=$data['halfday']['res_arr'];
								if(isset($data['halfday']) && !empty($data['halfday'])){
								$result['res_arr'][$key]['Halfday']=$data['halfday'][0]['Half_Day'];	
								}else{
									$value['Halfday']=0;
								}
								// $this->PrettyPrintArray($result['res_arr'][$key]);
								// exit;
							}
							// $this->PrettyPrintArray($result['res_arr']);
							// exit;
							$monthweek=explode('-',$_GET['month']);
							$dummy=array(
								'month'=>$monthweek[1]."-".$monthweek[0]
							);
							// $this->PrettyPrintArray($dummy);
							$data['weekoff']=$this->BusinessAdminModel->GetCalWeekOffMonth($dummy);
							$data['weekoff']=$data['weekoff']['res_arr'];
							// $this->PrettyPrintArray($data['weekoff']);
							for($i=0;$i<count($data['weekoff']);$i++)
							{
								$data['weekoff'][$i]+=['count'=>0];
								if($data['weekoff'][$i]['weekoff'] == 'null' || $data['weekoff'][$i]['weekoff'] == null || $data['weekoff'][$i]['weekoff'] == '[]')
								{
									$data['weekoff'][$i]['count']=0;
								}
								else{
									
									$count=0;
									$temp=json_decode($data['weekoff'][$i]['weekoff']);
									$temp=array_filter($temp);
									
									$getdate=$_GET['month']."-01";
									$d1=strtotime($getdate);
									
									for($j=0;$j < count($temp);$j++){
										// if(date('Y-m-d') > $temp[$j]){
										$d2=strtotime($temp[$j]);
										if(date("m",$d1) == date("m",$d2)){
											$count=$count+1;
										}
										$data['weekoff'][$i]['count']=$count;
									}
									
								
								}
							}
								// $this->PrettyPrintArray($data['weekoff']);
							foreach($result['res_arr'] as $key=>$value)
							{
								foreach($data['weekoff'] as $k=>$v)
								{
									if($value['employee_id'] == $v['employee_id'])
									{
										$result['res_arr'][$key]['weekoff']=$v['count'];
									}
								}
							}
							
							for($i=0;$i<count($result['res_arr']);$i++)
							{	
								$result['res_arr'][$i]['Leave']=$result['res_arr'][$i]['Leave']-(int)$result['res_arr'][$i]['weekoff'];
								$result['res_arr'][$i]['Working_Days']=$result['res_arr'][$i]['Calender_Days']-(int)$result['res_arr'][$i]['weekoff']-$res['holiday'];
								$result['res_arr'][$i]['Net_Days_Present']=$result['res_arr'][$i]['Present']-($result['res_arr'][$i]['Halfday']*0.5);
							}
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
	}
	
	else{
		$this->LogoutUrl(base_url()."BusinessAdmin/");
	}	
}



	// Check Salary for employee
	public function CheckEmployeeSalary(){
		if($this->IsLoggedIn('business_admin')){
			// $where=array(
			// 	'business_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
			// 	'business_admin_id'=> $this->session->userdata['logged_in']['business_admin_id']
			// );
				$data = $this->GetDataForAdmin("Employee_details");
				$data['business_outlet_details'] = $this->GetBusinessOutlets();
				$data['business_admin_employees']  = $this->BusinessAdminModel->GetBusinessAdminEmployees();
				$data['business_admin_employees']=$data['business_admin_employees']['res_arr'];
				
				$data['emp']=$this->BusinessAdminModel->NewCommission($data);	
				$data['sidebar_collapsed']="true";
				if(!isset($_GET) || empty($_GET))
				{
					//Load the default view
					
					$data['salary']=$this->BusinessAdminModel->GetSalaryTillDate($data);
					$data['salary']=$data['salary']['res_arr'];
					// $this->PrettyPrintArray($data['salary']);
					// die;
					if(date('m')==02 && date('d')==28 || date('d')==29){
						for($i=0;$i<count($data['salary']);$i++){
							$data['salary'][$i]['Salary']=$data['salary'][$i]['Salary']*30;
						}
					}
					elseif(date('d')==31){
						for($i=0;$i<count($data['salary']);$i++){
							$data['salary'][$i]['Salary']=$data['salary'][$i]['Salary']*30;
						}
					}
					else{
						for($i=0;$i<count($data['salary']);$i++){
							$data['salary'][$i]['Salary']=$data['salary'][$i]['Salary']*(date('d')-1);
						}
					}
					//advance amoutn taken cal and appending into salary aarray 
					for($i=0;$i<count($data['salary']);$i++){
						$data['salary'][$i]+=['amt'=>'0'];
						$data['salary'][$i]+=['comm'=>'0'];
						$data['salary'][$i]+=['HalfDay'=>'0'];
						$data['salary'][$i]+=['Leaves'=>'0'];
						$data['salary'][$i]+=['OverTime'=>'0'];
						$data['salary'][$i]+=['Net_Payout'=>'0'];
					}
					$data['advance'] = $this->BusinessAdminModel->AdvanceSalaryDetails($data);
					$data['advance'] = $data['advance']['res_arr'];
					foreach($data['salary'] as $key=>$value){
						foreach($data['advance'] as $k=>$v){
							if($v['employee_id']==$value['employee_id']){
								$data['salary'][$key]['amt']=$data['salary'][$key]['amt']+$v['amount'];
							}
						}
					}
					foreach ($data['salary'] as $item=>$value) {
						# code...
						$comm=array();
						array_push($comm,0);
						$data['comm']=$this->BusinessAdminModel->CommissionDetails($value['employee_id']);
						$data['comm']=$data['comm']['res_arr'];
						
						if(isset($data['comm']))
						{
							foreach($data['comm'] as $com_key=>$com_value)
							{
								$target=$data['comm'][$com_key]['targets'];	
									$commission=array(
										'base_value'=>$com_value['base_value'],
										'emp_id'=>$com_value['emp_id'],
										'commission_type'=>$com_value['commission_type']
									);
								$comm_perc=0;
								$target1=$com_value['set_target1'];
								$target2=$com_value['set_target2'];	
								
								$result['sales']=$this->BusinessAdminModel->CommissionBaseValueForSalary($commission);
								if(isset($result['sales']) && $target != 0){
									// $result['sales']['total']=0;

									$comm_perc=round(($result['sales']/$target)*100);
								}
								else{
									$comm_perc=0;
								}
								//calculate commission
								if($comm_perc >= $target1 && $comm_perc < $target2){
									if($com_value['new_base_value'] == 'Calculation on Base Target'){
										$c=($com_value['commision']*$com_value['targets'])/100;
										array_push($comm,$c);	
									}else{
									$c=($com_value['commision']*$result['sales'])/100;
									array_push($comm,$c);
									}
								}
								else if($comm_perc > $target1 && $target2 == 0){
									if($com_value['new_base_value'] == 'Calculation on Base Target'){
										$c=($com_value['commision']*$com_value['targets'])/100;
										array_push($comm,$c);	
									}else{
									$c=($com_value['commision']*$result['sales'])/100;
									array_push($comm,$c);
									}
								}
							}
							$commission=array_sum($comm);
							$data['salary'][$item]['comm']=$commission;
						}
					}
					//start
					//end of loop				
					// get half day for salary
					$data['halfday']=$this->BusinessAdminModel->GetHalfDayAttendanceLoad($data);
					$data['halfday']=$data['halfday']['res_arr'];
					
					$data['holidays']=$this->BusinessAdminModel->EmssGetHolidaysLoad($data);
					$data['holidays']=$data['holidays']['res_arr'][0]['holiday'];
					$res = array(
						'holiday'=>$data['holidays'],
						'business_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
						'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id']
					);
					$data['weekoff']=$this->BusinessAdminModel->GetCalWeekOff($data);
					$data['weekoff']=$data['weekoff']['res_arr'];
					// $this->PrettyPrintArray($data['weekoff']);
					// die;
					$data['attendance_details']=$this->BusinessAdminModel->GetAttendanceDetails($res);
					$data['attendance_details']=$data['attendance_details']['res_arr'];
						for($i=0;$i<count($data['weekoff']);$i++){
								$data['weekoff'][$i]+=['count'=>0];
								if($data['weekoff'][$i]['weekoff'] == 'null' || $data['weekoff'][$i]['weekoff'] == null || $data['weekoff'][$i]['weekoff'] == '[]'){
									$data['weekoff'][$i]['count']=0;
								}
								else{
									$temp=json_decode($data['weekoff'][$i]['weekoff']);
									$count=0;
									for($j=0;$j < count($temp);$j++){
										if(date('Y-m-d') > $temp[$j]){
											$count=$count+1;
										}
										$data['weekoff'][$i]['count']=$count;
									}
											
								}
							}
							
							for($i=0;$i<count($data['attendance_details']);$i++){
								$data['attendance_details'][$i]+=['weekoff'=>'0'];
							}
							foreach($data['attendance_details'] as $key=>$value){
								foreach($data['weekoff'] as $k=>$v){
									if($value['employee_id'] == $v['employee_id']){
										$data['attendance_details'][$key]['weekoff']=$v['count'];
									}
								}
							}
							// $this->PrettyPrintArray($data['attendance_details']);
							// die;
						for($i=0;$i<count($data['attendance_details']);$i++){
							$data['attendance_details'][$i]['Working-Days']=$data['attendance_details'][$i]['Calender_Days']-$data['attendance_details'][$i]['weekoff'];
							$data['attendance_details'][$i]['Absent']=$data['attendance_details'][$i]['Absent']-$data['attendance_details'][$i]['weekoff'];
						}
						foreach($data['salary'] as $key=>$value){
							if(isset($data['halfday'])){
								foreach($data['halfday'] as $k=>$v){
									if($value['employee_id'] == $v['employee_id']){
										$data['salary'][$key]['HalfDay']=($data['salary'][$key]['employee_gross_salary'])/30*($v['Half_Day']/2);
									}
								}
							}
							else{
								$data['salary'][$key]['HalfDay']=0;	
							}	
						}
					
						foreach($data['salary'] as $key=>$value){
							if(isset($data['attendance_details'])){
								foreach($data['attendance_details'] as $k=>$v){
									if($value['employee_id'] == $v['employee_id']){
										$data['salary'][$key]['Leaves']=($data['salary'][$key]['employee_gross_salary']/30)*$v['Absent'];
									}
								}
							}else{
								$data['salary'][$key]['Leaves']=0;	
							}
						}
						foreach($data['attendance_details'] as $key=>$value){
							foreach($data['salary'] as $k=>$v){
								if($value['employee_id'] == $v['employee_id']){
									$data['salary'][$k]['OverTime']=$data['attendance_details'][$key]['overtime'];
								}
							}
						}
						foreach($data['salary'] as $key=>$value){
							(int)$value['Net_Payout']=(int)$value['Net_Payout']+((int)$value['Salary']+(int)$value['comm'])-((int)$value['HalfDay']+$value['Leaves'])+$value['OverTime']-($value['employee_pt']+$value['employee_income_tax']);		
						}
					//end 
					// $this->PrettyPrintArray($data['salary']);
					// die;
					$this->load->view('business_admin/ba_emss_cal_salary_view',$data);
				}
				else if(isset($_GET) && !empty($_GET))
				{ 	
					$year=$_GET['year'];
					$month=$_GET['month'];
					
					if((int)$month<10){
						$_GET['month']=$year.'-0'.$month;
					}
					else{
						$_GET['month']=$year.'-'.$month;
					}
					//Calculating Salary For ALL Employees  
						if($_GET['expert_id']=='All'){
							
							$res=$this->BusinessAdminModel->EmpDetails();
							$data=$res['res_arr'];
							for($i=0;$i<count($data);$i++){
								$data[$i]+=['advance'=>0];
								$data[$i]+=['commission'=>0];
								$data[$i]+=['HalfDay'=>0];
								$data[$i]+=['Leaves'=>0];
								$data[$i]+=['OverTime'=>0];
								$data[$i]+=['Net_Payout'=>0];
							}
							//main for for itrating employee 
								foreach($data as $key=>$value)
								{
											$comm=array();
											array_push($comm,0);
											$data1 = array(
												'month'=>$_GET['month'],
												'expert_id'=>$value['employee_id'],
												'business_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
												'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id']
												);
												$adv=0;
												$check['salary']=$this->BusinessAdminModel->CheckAdvanceSalaryDefined($data1);
												$check['salary']=$check['salary']['res_arr'];
												for($i=0;$i<count($check['salary']);$i++){
													$adv=$adv+$check['salary'][$i]['amount'];
												}
												$data[$key]['advance']=$adv;
												//get commssion details
												$check['commission']=$this->BusinessAdminModel->CheckCommissionDefined($data1);
												$check['commission']=$check['commission']['res_arr'];
												// print_r($check['commission']);
												//if commission is declared for that month then calculate it 	
												if(isset($check['commission']))
												{	
													foreach($check['commission'] as $com_key=>$com_value)
													{
														$target=$check['commission'][$com_key]['targets'];	
														$commission=array(
															'base_value'=>$com_value['base_value'],
															'expert_id'=>$com_value['emp_id'],
															'month'=>$com_value['months']
														);
														$data['commission']=$this->BusinessAdminModel->CommissionBaseValueForSalaryMonth($commission);
														if(isset($data['commission'])){
															$data['commission']=$data['commission']['res_arr'][0]['total'];
														
														}
														// $this->PrettyPrintArray($data['commission']);
														//declare value for checking 
														$comm_perc=0;
														$target1=$com_value['set_target1'];
														$target2=$com_value['set_target2'];
														//if commission is set 
															if(isset($data['commission'])  && $target!=0){
																$comm_perc=round(($data['commission']/$target)*100);
																// $this->PrettyPrintArray($comm_perc);
															}
															else{
																$comm_perc=0;
															}
															//calculate commission
															if($comm_perc > $target1 && $comm_perc <= $target2){
																$c=($com_value['commision']*$data['commission'])/100;
																// $com_value['commission']=$comm;
																array_push($comm,$c);
															// break;
															}
															else if($comm_perc > $target1 && $target2 == 0){
																$c=($com_value['commision']*$data['commission'])/100;
																// $com_value['commission']=$comm;
																array_push($comm,$c);
															// break;
															}
															
													}
													//commission foreach loop end;
													// $commission=max($comm);
													$commission=array_sum($comm);
													$data[$key]['commission']=$commission;
												}//if commission declared  		
												$data1 = array(
													'month_name'=>$_GET['month'],
													'expert_id'=>$value['employee_id'],
													'business_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
													'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id']
													);
												$halfday['halfday']=$this->BusinessAdminModel->GetEmpHalfDayAttendance($data1);
												$halfday['halfday']=$halfday['halfday']['res_arr'];
												$overtime['overtime']=$this->BusinessAdminModel->GetOverTimeDetails($data1);
												$overtime['overtime']=$overtime['overtime']['res_arr'];
												if(isset($overtime['overtime'])){
													$data[$key]['OverTime']=$overtime['overtime'][0]['overtime'];
												}
												elseif(!isset($overtime['overtime']) || empty($overtime['overtime'])){
													$data[$key]['OverTime']=0;
												}
												foreach($halfday['halfday'] as $k=>$v){
													if($value['employee_id'] == $v['employee_id']){
														$data[$key]['HalfDay']=(int)(($value['employee_gross_salary']/30)*$v['HalfDay'])*0.5;
													}
												}
												
												$month_sal=explode("-",$_GET['month']);
												
												$monthq=$month_sal[1]."-".$month_sal[0];
												$data2 = array(
													'month_name'=>$monthq,
													'expert_id'=>$value['employee_id'],
													'business_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
													'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id']
													);
												$weekoff=0;
												$data['weekoff']=$this->BusinessAdminModel->GetCalWeekOffMonthSal($data2);
												// $this->PrettyPrintArray($_GET);	
												if($data['weekoff']['success'] == 'false'){
													$weekoff=0;
												}elseif($data['weekoff']['success'] == 'true'){
													$count=0;
														$data['weekoff']=$data['weekoff']['res_arr'];
														// $this->PrettyPrintArray($data['weekoff']);
														$temp=json_decode($data['weekoff'][0]['weekoff']);
														$temp=array_filter($temp);
														$getdate=$_GET['month']."-01";
														$d1=strtotime($getdate);
														
														for($j=0;$j < count($temp);$j++){
															// if(date('Y-m-d') > $temp[$j]){
															$d2=strtotime($temp[$j]);
															if(date("m",$d1) == date("m",$d2)){
																$count=$count+1;
															}
															$weekoff=$count;
														}
															
												}
												// }
												// $this->PrettyPrintArray($data['weekoff'][0]['count']);
												// die;	
												$data['holidays']=$this->BusinessAdminModel->EmssGetHolidaysMonth($data1);
												$data['holidays']=$data['holidays']['res_arr'][0]['holiday'];
												$data1 = array(
													'month_name'=>$_GET['month'],
													'expert_id'=>$value['employee_id'],
													'holiday'=>$data['holidays'],
													'weekoff'=>$weekoff,
													'business_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
													'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id']
													);
												// print_r($data1);	
												$data['absent']=$this->BusinessAdminModel->CalAbsentMonthSal($data1);
												$data['absent']=$data['absent']['res_arr'];
												if(!empty($data['absent'])){
													$data['absent']=$data['absent'];
													$leave=$data['absent'][0]['Leave'];
													$data[$key]['Leaves']=(int)($value['employee_gross_salary']/30)*$leave;
												}
												else{
													$data['absent']=0;
												}
												$data[$key]['Net_Payout']=($value['employee_gross_salary']+$data[$key]['commission']+$data[$key]['OverTime'])-($data[$key]['Leaves']+$data[$key]['HalfDay']+$data[$key]['advance'])-($data[$key]['employee_pt']+$data[$key]['employee_income_tax']);		
									}
								//employee foreach
								unset($data['weekoff'],$data['holidays'],$data['absent'],$data['commission']);		
									// $this->PrettyPrintArray($data);
									// exit;
								if($res['success']=='true'){	
									header("Content-type: application/json");
									print(json_encode($data, JSON_PRETTY_PRINT));
									die;
								}
								else{
									return $this->ReturnJsonArray(true,false,'Database Error');						
									die;
								}
					}	//ALL EMPLOYEE COMMISSION CALCULATION ENDS HERE
						//if end of all expert			
				}
			}
			else{
				$this->LogoutUrl(base_url()."BusinessAdmin/");
		}	
	}

public function Salary(){
	if($this->IsLoggedIn('business_admin')){
			$where=array(
					'business_admin_id'=> $this->session->userdata['logged_in']['business_admin_id']
			);
					$data = $this->GetDataForAdmin("Employee_details");
					$data['business_outlet_details'] = $this->GetBusinessOutlets();
					$data['business_admin_employees']  = $this->GetBusinessAdminEmployees();
					
					$data['sidebar_collapsed']="true";
					$this->load->view('business_admin/ba_emss_salary_view',$data);
	}
	else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
	}   
}
// show Salary of employeee

public function ShowSalary(){
	if($this->IsLoggedIn('business_admin')){
		$where=array(
			'business_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
			'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id']
		);
			$data = $this->GetDataForAdmin("Employee_details");
			$data['business_outlet_details'] = $this->GetBusinessOutlets();
			$data['business_admin_employees']  = $this->GetBusinessAdminEmployees();
			
			$data['sidebar_collapsed']="true";
				if(!isset($_GET) || empty($_GET)){
					//Load the default view
					$this->load->view('business_admin/ba_emss_cal_salary_view',$data);
				}
				else if(isset($_GET) && !empty($_GET)){
					
					$data = array(
						'expert_id' 			 => $_GET['emp_id'],
						'month' 				 => $_GET['month'],
						
						'business_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
						'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id']
				);


					$result = $this->BusinessAdminModel->GetSalaryDetails($data);
					if($result['success'] == 'true'){

						header("Content-type: application/json");
							print(json_encode($result, JSON_PRETTY_PRINT));
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
		$this->LogoutUrl(base_url()."BusinessAdmin/");
	}	
}

//Advance PAyment  
public function AdvancePayment()
{
	if($this->IsLoggedIn('business_admin')){
		$where=array(
			'business_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
			'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id']
		);
			$data = $this->GetDataForAdmin("Employee_details");
			$data['business_outlet_details'] = $this->GetBusinessOutlets();
			$data['business_admin_employees']  = $this->BusinessAdminModel->GetBusinessAdminEmployees();
			$data['business_admin_employees']=$data['business_admin_employees']['res_arr'];
			$data['emp']=$this->BusinessAdminModel->NewCommission($data);
			if(!isset($_POST) || empty($_POST)){
				$data['sidebar_collapsed']="true";
				$data['salary_details']=$this->BusinessAdminModel->GetAdvanceSalaryDetails($data);
				$data['salary_details']=$data['salary_details']['res_arr'];
				//Load the default view
				$this->load->view('business_admin/ba_emss_advance_payment_view',$data);
			}
			else{
				$data['sidebar_collapsed']="true";
				// $this->PrettyPrintArray($_POST);
				// exit;
				$data1=array(
					'employee_id'=>$_POST['emp_id'],
					'month'=>$_POST['date'],
					'amount'=>$_POST['amount'],	
					'payment_mode'=>$_POST['payment_mode'],
					'Reason'=>$_POST['reason'],
					'business_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
					'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id']
				);
				$result = $this->BusinessAdminModel->Insert($data1,'mss_emss_advance_salary');
				$emp_details=$this->BusinessAdminModel->DetailsById($_POST['emp_id'],'mss_employees','employee_id');
				$emp_details=$emp_details['res_arr'];
				// $this->PrettyPrintArray($this->session->userdata['logged_in']);
				// exit;
				$data2=array(
					'expense_date'=>$_POST['date'],
					'expense_type_id'=>7,
					'item_name'=>'Advance Salary',
					'employee_name'=>$this->session->userdata['logged_in']['business_admin_name'],
					'amount'=>$_POST['amount'],
					'payment_type'=>'Employee Salary',
					'payment_to'=>$emp_details['employee_first_name'].' '.$emp_details['employee_last_name'],
					'invoice_number'=>'',
					'remarks'=>$_POST['reason'],
					'payment_mode'=>$_POST['payment_mode'],
					'expense_status'=>'Paid'
				);
				$result = $this->BusinessAdminModel->Insert($data2,'mss_expenses');
				$this->load->view('business_admin/ba_emss_advance_payment_view',$data);

				if($result['success'] == 'true'){
					$this->ReturnJsonArray(true,false,"Data added successfully!");
					die;
				  }
				elseif($result['error'] == 'true'){
					$this->ReturnJsonArray(false,true,$result['message']);
								die;
	  			}

			}
	}
	else{
		$this->LogoutUrl(base_url()."BusinessAdmin/");
	}	
}

// insert salary into table
public function InsertSalary(){
	if($this->IsLoggedIn('business_admin')){
		if(isset($_POST) && !empty($_POST)){
			// $this->PrettyPrintArray($_POST);
			// 	exit;
				$data = array(
				"employee_id"          => $this->input->post('employee_id'),
				"salary" =>$this->input->post('salary'),
				"advance"=>$this->input->post('advance'),
				"commission"=>$this->input->post('commission'),
				"payout"=>$this->input->post('payout'),
				'business_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
				'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id']
					
				);

				$status = $this->BusinessAdminModel->Insert($data,'mss_emss_salary_details');
				if($status['success'] == 'true'){
					$this->ReturnJsonArray(true,false,"Inserted successfully!");
					die;
				}
				elseif($status['error'] == 'true'){
					$this->ReturnJsonArray(false,true,$status['message']);
					die;
				}
			}	
					       
	}
	
	else{
		$this->LogoutUrl(base_url()."BusinessAdmin/");
	}
}
    public function GetCommisionDetails(){
    	if($this->IsLoggedIn('business_admin')){
    		$where=array(
    			'business_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
    			'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id']
    		);
    			$data=array(
    				'comm_name'=>$_GET['comm_name'],
    				'month'=>$_GET['month'],
    				'expert_id'=>$_GET['expert_id']
    			);
    			$result=$this->BusinessAdminModel->GetCommissionDetails($data);
    			// $this->PrettyPrintArray($result);
    			// exit;
    			if($result['success']=='true'){
    				header("Content-type: application/json");
    				print(json_encode($result['res_arr'], JSON_PRETTY_PRINT));
    			}
    			else{
    				return $this->ReturnJsonArray(true,false,'Database Error');						
    				die;
    			}
    				
    	}
    	
    	else{
    		$this->LogoutUrl(base_url()."BusinessAdmin/");
    	}	
    }
	//
	//Loyalty Dashboard
	public function LoyaltyDashBoard(){
		if($this->IsLoggedIn('business_admin')){
			$where=array(
				'business_admin_id'=> $this->session->userdata['logged_in']['business_admin_id']
			);
				$data = $this->GetDataForAdmin("Loyalty");
				$data['business_outlet_details'] = $this->GetBusinessOutlets();
				$data['loyalty']= $this->BusinessAdminModel->ExistingLoyalty($where);
				$data['loyalty']= $data['loyalty']['res_arr'];
				$data['cust_data']= $this->BusinessAdminModel->Cashback_Customer_Data($where);
				$data['cust_data']=	$data['cust_data']['res_arr'];
				// $this->PrettyPrintArray($data);
				// exit;
				$data['sidebar_collapsed']="true";
				$data['CheckOutletRule'] = $this->BusinessAdminModel->GetOutletLoyalty($data['selected_outlet']['business_outlet_id']);
				if($data['CheckOutletRule']['success'] == 'true')
				{
					$data['CheckOutletRule']=$data['CheckOutletRule']['res_arr'];
				}
				else
				{
					$data['CheckOutletRule']+=['res_arr'=>''];
					$data['CheckOutletRule'] = $data['CheckOutletRule']['res_arr'];
				}
				$data['loyalty_offer'] = $this->BusinessAdminModel->ExistingLoyaltyOffer($data['selected_outlet']['business_outlet_id'],'mss_loyalty_offer');
				// $this->PrettyPrintArray($data);
				// exit;
				if(isset($data['loyalty_offer']['res_arr']))
				{
					$data['loyalty_offer'] = $data['loyalty_offer']['res_arr'];
				}
				else
				{
					$data['loyalty_offer'] +=['res_arr'=>0];
					$data['loyalty_offer'] = $data['loyalty_offer']['res_arr'];
				}
				// $this->PrettyPrintArray($data);
				// exit;
				$this->load->view('business_admin/ba_loyalty_dashboard_view',$data);
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}
	//Loyalty Dashboard Integrated 
	public function LoyaltyDashBoardIntegrated()
	{
		if($this->IsLoggedIn('business_admin')){
			$where=array(
				'business_admin_id'=> $this->session->userdata['logged_in']['business_admin_id']
			);
				$data = $this->GetDataForAdmin("Loyalty");
				$data['business_outlet_details'] = $this->GetBusinessOutlets();
				$data['cust_data']= $this->BusinessAdminModel->Cashback_Customer_Data($where);
				$data['cust_data']=	$data['cust_data']['res_arr'];
				$data['categories']  = $this->GetCategories($this->session->userdata['outlets']['current_outlet']);
				$data['sub_categories']  = $this->GetSubCategories($this->session->userdata['outlets']['current_outlet']);
				$data['services']  = $this->GetServices($this->session->userdata['outlets']['current_outlet']);
				// $this->PrettyPrintArray($data);
				// exit;
				$data['sidebar_collapsed']="true";
				$data['CheckOutletRule'] = $this->BusinessAdminModel->GetOutletLoyalty($data['selected_outlet']['business_outlet_id']);
				if($data['CheckOutletRule']['success'] == 'true')
				{
					$data['CheckOutletRule']=$data['CheckOutletRule']['res_arr'];
				}
				else
				{
					$data['CheckOutletRule']+=['res_arr'=>''];
					$data['CheckOutletRule'] = $data['CheckOutletRule']['res_arr'];
				}
				$data['loyalty_offer'] = $this->BusinessAdminModel->ExistingLoyaltyOffer($data['selected_outlet']['business_outlet_id'],'mss_loyalty_offer_integrated');
				if($data['loyalty_offer']['success'] == 'true')
				{
					$data['loyalty_offer'] = $data['loyalty_offer']['res_arr'];
				}
				else
				{
					$data['loyalty_offer'] +=['res_arr'=>''];
					$data['loyalty_offer'] = $data['loyalty_offer']['res_arr'];
				}
				if(array_search("Marks360",$data["business_admin_packages"]))
				{
					$rules = $this->BusinessAdminModel->RuleDetailsById($data['selected_outlet']['business_outlet_id'],'mss_loyalty_rules','business_outlet_id');
					$data['rules'] = $rules['res_arr'];
				}
				else
				{
					$data['rules'] = ['res_arr'=>''];
					$data['rules'] = $data['rules']['res_arr'];
				}
				// $this->PrettyPrintArray($data);
				// exit;
				$this->load->view('business_admin/ba_loyalty_dashboard_integrated',$data);
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}
	//Loyalty  Delete Offer
	public function LoyaltyDeleteOfferIntegrated()
	{
		// print_r($_GET);
		// exit;
		if($this->IsLoggedIn('business_admin'))
		{
			$where = array(
				'business_admin_id' => $this->session->userdata['logged_in']['business_admin_id']
			);
			$data = array(
				'offers_status' => '0',
				'offer_id' => $this->input->get('offer_id')
			);
			// $delete_offer = $this->BusinessAdminModel->Update($data,'mss_loyalty_offer_integrated','offer_id');
			$delete_offer = $this->BusinessAdminModel->DeleteOfferIntegrated($data,'mss_loyalty_offer_integrated','offer_id');
			if($delete_offer['success'] == 'true')
			{
				return $this->ReturnJsonArray(true,false,"Offer Deleted Successfully");
			}
			else
			{
				return $this->ReturnJsonArray(false,true,"Something Went Wrong");
			}
		}
	}
	//Loyalty Delete Offer
	public function LoyaltyDeleteOffer()
	{
		// print_r($_GET);
		// exit;
		if($this->IsLoggedIn('business_admin'))
		{
			$where = array(
				'business_admin_id' => $this->session->userdata['logged_in']['business_admin_id']
			);
			$data = array(
				// 'offers_status' => '0',
				'offer_id' => $this->input->get('offer_id')
			);
			// $delete_offer = $this->BusinessAdminModel->Update($data,'mss_loyalty_offer_integrated','offer_id');
			$delete_offer = $this->BusinessAdminModel->Delete($data,'mss_loyalty_offer','offer_id');
			if($delete_offer['success'] == 'true')
			{
				return $this->ReturnJsonArray(true,false,"Offer Deleted Successfully");
			}
			else
			{
				return $this->ReturnJsonArray(false,true,"Something Went Wrong");
			}
		}
	}
	//Loyalty Integrated Visit Offer
	public function InsertIntegratedVisitOffer(){
		// $this->PrettyPrintArray($_POST);
		// exit;
		if($this->IsLoggedIn('business_admin')){
			$where=array(
				'business_admin_id'=> $this->session->userdata['logged_in']['business_admin_id']
			);
			$this->form_validation->set_rules('visit[]', 'Visit', 'trim|required');
			$this->form_validation->set_rules('category_id[]', 'Category', 'trim|required');
			$this->form_validation->set_rules('sub_category_id[]','Sub Category' ,'trim|required');
			$this->form_validation->set_rules('service_id[]','Service' ,'trim|required');
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
						$visits 	= $this->input->post('visit[]');
						$category_id 	= $this->input->post('category_id[]');
						$sub_category_id 	= $this->input->post('sub_category_id[]');
						$service_id = $this->input->post('service_id[]');
						$rule_type = $this->input->post('rule_type[]');
						$counter 	= 0;//To keep the record of no of insert
						// $this->PrettyPrintArray($_POST);
						// exit;
						foreach($visits as $key=>$value)
						{
							$service_name = $this->BusinessAdminModel->DetailsById($service_id[$key],'mss_services','service_id');
							// print_r($service_name);
							// exit;
							if($service_name['success'] == 'true')
							{
								$data = array(
									'visits' => $visits[$key],
									'category_id' => $category_id[$key],
									'sub_category_id' => $sub_category_id[$key],
									'service_id' => $service_id[$key],
									'rule_type' =>$rule_type[$key],
									'offers' => $service_name['res_arr']['service_name'],
									'business_outlet_id' => $this->input->post('business_outlet_id'),
									'business_admin_id' => $this->input->post('business_outlet_business_admin')
								);
								$result = $this->BusinessAdminModel->InsertVisitOffer($data,'mss_loyalty_offer_integrated');
								// $this->PrettyPrintArray($data);
								// exit;
								if($result['success'] == 'true')
								{
									$counter = $counter + 1;
								}
							}
								
						}
						if($counter == count($visits))
						{
							$this->ReturnJsonArray(true,false,"Offer Defined Successfully");
							die;
						}
						else
						{
							$this->ReturnJsonArray(false,true,"Please Check Again");
							die;
						}
				}
					// $this->PrettyPrintArray($data);
					// exit;
			
				
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
			

	}
	//Loyalty Visit Offer Insert
	public function InsertVisitOffer(){
		// $this->PrettyPrintArray($_POST);
		// exit;
		if($this->IsLoggedIn('business_admin')){
			$where=array(
				'business_admin_id'=> $this->session->userdata['logged_in']['business_admin_id']
			);
			$this->form_validation->set_rules('visits[]', 'Visits', 'trim|required');
			$this->form_validation->set_rules('offers[]', 'Offers', 'trim|required');
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
						$offers_id 	= $this->input->post('offers_id[]');
						$visits 	= $this->input->post('visits[]');
						$offers 	= $this->input->post('offers[]');
						$counter 	= 0;//To keep the record of no of insert
						// $this->PrettyPrintArray($_POST);
						// exit;
						foreach($visits as $key=>$value)
						{
								$data = array(
									'visits' => $visits[$key],
									'offers' => $offers[$key],
									'business_outlet_id' => $this->input->post('business_outlet_id'),
									'business_admin_id' => $this->input->post('business_outlet_business_admin')
								);
								$result = $this->BusinessAdminModel->InsertVisitOffer($data,'mss_loyalty_offer');
								// $this->PrettyPrintArray($data);
								// exit;
								if($result['success'] == 'true')
								{
									$counter = $counter + 1;
								}
						}
						if($counter == count($visits))
						{
							$this->ReturnJsonArray(true,false,"Offer Defined Successfully");
							die;
						}
						else
						{
							$this->ReturnJsonArray(false,true,"Please Check Again");
							die;
						}
				}
					// $this->PrettyPrintArray($data);
					// exit;
			
				
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
			

	}
	//Loyalty Offer Insert
	public function InsertOffer(){
		// $this->PrettyPrintArray($_POST);
		// exit;
		if($this->IsLoggedIn('business_admin')){
			$where=array(
				'business_admin_id'=> $this->session->userdata['logged_in']['business_admin_id']
			);
			$this->form_validation->set_rules('points[]', 'Points', 'trim|required');
			$this->form_validation->set_rules('category_id[]', 'Category', 'trim|required');
			$this->form_validation->set_rules('sub_category_id[]','Sub Category','trim|required');
			$this->form_validation->set_rules('service_id[]','Service','trim|required');
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
						$offers_id = $this->input->post('offers_id[]');
						$points = $this->input->post('points[]');
						$category_id = $this->input->post('category_id[]');
						$sub_category_id = $this->input->post('sub_category_id[]');
						$service_id	=$this->input->post('service_id[]');
						$rule_type=$this->input->post('rule_type[]');
						$counter = 0;//To keep the record of no of insert
						$existCounter = 0;//To keep the record of no of exist offer
						foreach($points as $key=>$value)
						{
							$service_data = array(
								'service_id' => $service_id[$key]

							);
							$service = $this->BusinessAdminModel->DetailsById($service_data['service_id'],'mss_services','service_id');
							// print_r($service);
								$data = array(
									'category_id' => $category_id[$key],
									'sub_category_id' => $sub_category_id[$key],
									'service_id' => $service_id[$key],
									'points' => $points[$key],
									'rule_type'=>$rule_type[$key],
									'offers' => $service['res_arr']['service_name'],
									'business_outlet_id' => $this->input->post('business_outlet_id'),
									'business_admin_id' => $this->input->post('business_outlet_business_admin')
								);
								$check_offer=$this->BusinessAdminModel->CheckExistingLoyaltyOffer($data,'mss_loyalty_offer_integrated');
								// $this->PrettyPrintArray($check_offer);
								// exit;
								// if(count($check_offer)>0)
								// {
								// 	$existCounter = $existCounter + 1;
								// }
								// else{
									$result = $this->BusinessAdminModel->InsertOffer($data,'mss_loyalty_offer_integrated');
									if($result['success'] == 'true')
									{
										$counter = $counter + 1;
									}
								// }
								
						}
						
						if($counter == count($points))
						{
							$this->ReturnJsonArray(true,false,"Offer Defined Successfully");
							die;
			}
            else 
            {
              $this->ReturnJsonArray(true,false," offers are defined already");
							die;
            }
						// else if($existCounter)
						// {
						// 	$this->ReturnJsonArray(false,true,"Some Offer Already Exists");
						// 	die;	
						// }
				}
				
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
			
	}
	public function GetOffers(){
		if($this->IsLoggedIn('business_admin')){
			// $this->PrettyPrintArray($_GET);
			// exit;
			if(isset($_GET) && !empty($_GET)){
			
				$data = $this->BusinessAdminModel->DetailsById($_GET['offer_id'],'mss_loyalty_offer_integrated','offer_id');
				$this->PrettyPrintArray($data);
				exit;
				header("Content-type: application/json");
				print(json_encode($data['res_arr'], JSON_PRETTY_PRINT));
				die;
			}
		}
		else
		{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}
	//Loyalty Update Offer
	public function UpdateOffer()
	{
		if($this->IsLoggedIn('business_admin'))
		{
			if(isset($_POST) && !empty($_POST))
			{
				$where=array(
					'business_admin_id'=> $this->session->userdata['logged_in']['business_admin_id']
				);
				$this->form_validation->set_rules('points', 'Points', 'trim|required');
				$this->form_validation->set_rules('category_id', 'Category', 'trim|required');
				$this->form_validation->set_rules('sub_category_id','Sub Category','trim|required');
				$this->form_validation->set_rules('service_id','Service','trim|required');
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
					$service_data = array(
						'service_id' => $this->input->post('service_id')
					);
					$offers = $this->BusinessAdminModel->DetailsById($service_data,'mss_services','service_id');
					$data = array(
						'offer_id' => $this->input->post('offer_id'),
						'points' => $this->input->post('points'),
						'category_id' => $this->input->post('category_id'),
						'sub_category_id' => $this->input->post('sub_category_id'),
						'servcie_id' => $this->input->post('service_id'),
						'offers' =>$this->input->post($offers['res_arr']['service_name']),
					);
				}	
			}
		}
		else
		{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}
	//Update Integrated Offer
	public function UpdateVisitOfferIntegrated()
	{
		if($this->IsLoggedIn('business_admin'))
		{
			if(isset($_POST) && !empty($_POST))
			{
				$where=array(
					'business_admin_id'=> $this->session->userdata['logged_in']['business_admin_id']
				);
				$this->form_validation->set_rules('offer_id', ' ', 'trim|required');
				$this->form_validation->set_rules('visits', 'Points', 'trim|required');
				$this->form_validation->set_rules('category_id', 'Category', 'trim|required');
				$this->form_validation->set_rules('sub_category_id','Sub Category','trim|required');
				$this->form_validation->set_rules('service_id','Service','trim|required');
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
					$service_data = array(
						'service_id' => $this->input->post('service_id')
					);
					$offers = $this->BusinessAdminModel->DetailsById($service_data,'mss_services','service_id');
					$data = array(
						'offer_id' => $this->input->post('offer_id'),
						'visits' => $this->input->post('visit'),
						'category_id' => $this->input->post('category_id'),
						'sub_category_id' => $this->input->post('sub_category_id'),
						'servcie_id' => $this->input->post('service_id'),
						'offers' =>$this->input->post($offers['res_arr']['service_name']),
					);
					// $result = $this->BusinessAdminModel->Update($data,'mss_loyalty')
				}	
			}
		}
		else
		{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}
  public function GetSubCategoriesByCategoryType()
  {
    
    if($this->IsLoggedIn('business_admin')){
			if(isset($_GET) && !empty($_GET)){
				$where = array(
          'category_type' => $_GET['category_type'],
          'category_business_admin_id' =>$this->session->userdata['logged_in']['business_admin_id'],
					'category_is_active'   => TRUE
				);
				
				$data = $this->BusinessAdminModel->MultiWhereSelect('mss_categories',$where);
				header("Content-type: application/json");
				print(json_encode($data['res_arr'], JSON_PRETTY_PRINT));
				die;
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
  }
  public function GetCategoryByCategoryType()
  {
    if($this->IsLoggedIn('business_admin')){
			if(isset($_GET) && !empty($_GET)){
				$where = array(
          'category_type' => $_GET['category_type'],
          'category_business_admin_id' =>$this->session->userdata['logged_in']['business_admin_id'],
					'category_is_active'   => TRUE
				);
				
				$data = $this->BusinessAdminModel->MultiWhereSelect('mss_categories',$where);
				header("Content-type: application/json");
				print(json_encode($data['res_arr'], JSON_PRETTY_PRINT));
				die;
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
  }
  //StandAlone
  public function InsertOfferStandAlone(){
		// $this->PrettyPrintArray($_POST);
		// exit;
		if($this->IsLoggedIn('business_admin')){
			$where=array(
				'business_admin_id'=> $this->session->userdata['logged_in']['business_admin_id']
			);
			$this->form_validation->set_rules('points[]', 'Points', 'trim|required');
			$this->form_validation->set_rules('offers[]','Offers','trim|required');
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
						$rules = $this->BusinessAdminModel->RuleDetailsById($_POST['business_outlet_id'],'mss_loyalty_rules','business_outlet_id');
						$rules = $rules['res_arr']['rule_type'];
						// $this->PrettyPrintArray($rules);
						
						$points = $this->input->post('points[]');
						
						$offers = $this->input->post('offers[]');
						$counter = 0;//To keep the record of no of insert
						$existCounter = 0;//To keep the record of no of exist offer
						foreach($points as $key=>$value)
						{
							
							
							// print_r($service);
								$data = array(
									'points' => $points[$key],
									'rule_type'=>$rules,
									'offers' => $offers[$key],
									'business_outlet_id' => $this->input->post('business_outlet_id'),
									'business_admin_id' => $this->input->post('business_outlet_business_admin')
								);
								// $this->PrettyPrintArray($data);
								$check_offer=$this->BusinessAdminModel->CheckExistingLoyaltyOffer($data,'mss_loyalty_offer');
								// if(count($check_offer)>0)
								// {
								// 	$existCounter = $existCounter + 1;
								// }
								// else{
									$result = $this->BusinessAdminModel->InsertOffer($data,'mss_loyalty_offer');
									if($result['success'] == 'true')
									{
										$counter = $counter + 1;
									}
								// }
								
						}
						
						if($counter == count($points))
						{
							$this->ReturnJsonArray(true,false,"Offer Defined Successfully");
							die;
			}
			else 
			{
			$this->ReturnJsonArray(true,false," offers are defined already");
							die;
			}
						// else if($existCounter)
						// {
						// 	$this->ReturnJsonArray(false,true,"Some Offer Already Exists");
						// 	die;	
						// }
				}
				
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
			
	}
	public function LoyaltyDeleteOfferStandAlone()
	{
		// print_r($_GET);
		// exit;
		if($this->IsLoggedIn('business_admin'))
		{
			$where = array(
				'business_admin_id' => $this->session->userdata['logged_in']['business_admin_id']
			);
			$data = array(
				// 'offers_status' => '0',
				'offer_id' => $this->input->get('offer_id')
			);
			// $delete_offer = $this->BusinessAdminModel->Update($data,'mss_loyalty_offer_integrated','offer_id');
			$delete_offer = $this->BusinessAdminModel->DeleteMultiple('mss_loyalty_offer',$data['offer_id'],'offer_id');
			if($delete_offer['success'] == 'true')
			{
				return $this->ReturnJsonArray(true,false,"Offer Deleted Successfully");
			}
			else
			{
				return $this->ReturnJsonArray(false,true,"Something Went Wrong");
			}
		}
	}
   public function AddVendor(){  
		if($this->IsLoggedIn('business_admin')){
		  if(isset($_POST) && !empty($_POST)){
				$da=$this->BusinessAdminModel->SelectMaxIdVendor();
				$da=$da['res_arr'][0]['id'];
				// $this->PrettyPrintArray($da);
				// exit;
				$code= "VE101".''.$this->session->userdata['outlets']['current_outlet'].''.($da+1);
				$data = array(
				'vendor_name'      => $this->input->post('vendor_name'),
				'vendor_contact_person'   => $this->input->post('vendor_contact_person'),
				'vendor_deals_in'         => $this->input->post('vendor_products'),
				'vendor_code'            => $code,
				'vendor_contact_no'           => $this->input->post('vendor_contact_no'),
				'vendor_landline_no'      => $this->input->post('vendor_landline_no'),
				'vendor_state'        => $this->input->post('vendor_state'),
				'vendor_city'      => $this->input->post('vendor_city'),
				'vendor_address'    => $this->input->post('vendor_address'),
				'vendor_gst_no'     => $this->input->post('gst_no'),
				'vandor_bank_acc_no'    => $this->input->post('bank_no'),
				'vandor_bank_ifsc_bank'    => $this->input->post('ifsc_code'),
				'business_outlet_id'	=>$this->session->userdata['outlets']['current_outlet']
			  );
			  $result = $this->BusinessAdminModel->Insert($data,'mss_vendors');
			  if($result['success'] == 'true'){
				$this->ReturnJsonArray(true,false,"Vendor added successfully!");
				die;
			  }
			  elseif($result['error'] == 'true'){
				$this->ReturnJsonArray(false,true,$result['message']);
				die;
			  }
			}
		  
		  
		}
		else{
		  $this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	  }
	  

	// 26/03/2020
	public function UpdateExpense(){  
        if($this->IsLoggedIn('business_admin')){
            if(isset($_POST) && !empty($_POST)){
                // $this->PrettyPrintArray($_POST);
                // exit;
                $datam=array(
                    'outlet_id'=>$this->session->userdata['outlets']['current_outlet']
                );
                $da=$this->BusinessAdminModel->SelectMaxIdExpense($datam);
                $da=$da['res_arr'][0]['id'];
								// $expense_id= "E1010Y".$this->session->userdata['outlets']['current_outlet'].($da+1);
								
								//
								$outlet_id = $this->session->userdata['outlets']['current_outlet'];
									$admin_id= $this->session->userdata['logged_in']['business_admin_id'];
									$expense_counter = $this->db->select('*')->from('mss_business_outlets')->where('business_outlet_id',$outlet_id)->get()->row_array();			
          				$expense_unique_serial_id = strval("EA".strval(100+$admin_id) . "O" . strval($outlet_id) . "-" . strval($expense_counter['business_outlet_expense_counter']));
								//

                $data = array(
                'expense_id'=>$_POST['expense_id'],
                'expense_date'=>$_POST['entry_date'],
                'expense_type_id'=>$_POST['expense_type_id'],
                'item_name'=>$_POST['item_name'],
                'employee_name'=>$_POST['employee_name'],
                'amount'=>($_POST['amount']+$_POST['amount1']),
                'pending_amount'=>$_POST['remaining_amt'],
                'payment_type'=>$_POST['payment_type'],
                'payment_to'=>$_POST['payment_to'],
                'invoice_number'=>$_POST['invoice_number'],
                'remarks'=>$_POST['remarks'],
                'payment_mode'=>$_POST['payment_mode'],
                'expense_status'=>$_POST['expense_status'],
                'bussiness_outlet_id'   =>$this->session->userdata['outlets']['current_outlet']
                );
                $data1 = array(
                'expense_unique_serial_id'=>$expense_unique_serial_id,
                'expense_date'=>$_POST['entry_date'],
                'expense_type_id'=>$_POST['expense_type_id'],
                'item_name'=>$_POST['item_name'],
                'total_amount'=>$_POST['total_amount'],
                'amount'=>($_POST['amount1']),
                'employee_name'=>$_POST['employee_name'],
                'payment_to_name'=>$_POST['payment_to_name'],
                'pending_amount'=>$_POST['remaining_amt'],
                'payment_type'=>$_POST['payment_type'],
                'payment_to'=>$_POST['payment_to'],
                'invoice_number'=>$_POST['invoice_number'],
                'remarks'=>$_POST['remarks'],
                'payment_mode'=>$_POST['payment_mode'],
                'expense_status'=>$_POST['expense_status'],
                'bussiness_outlet_id'   =>$this->session->userdata['outlets']['current_outlet']
                );
            
            //   exit;
                $result = $this->BusinessAdminModel->Insert($data1,'mss_expenses');
                $result1 = $this->BusinessAdminModel->UpdateExpense($data);
								//Update expense Counter
								$query = "UPDATE mss_business_outlets SET business_outlet_expense_counter = business_outlet_expense_counter + 1 WHERE business_outlet_id = ".$outlet_id."";
					
								$this->db->query($query);

                if($result1['success'] == 'true'){
                $this->ReturnJsonArray(true,false,"Expense Updated successfully!");
                die;
                }
                elseif($result['error'] == 'true'){
                $this->ReturnJsonArray(false,true,$result['message']);
                die;
                }
                else{
                $this->ReturnJsonArray(false,true,"Expense Not Update");
                die;
                }
            }
            
            
        }
        else{
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }
    }

	public function BulkUploadTransactionHistory(){
        if($this->IsLoggedIn('business_admin')){
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
                                        'txn_id'    => $row[0],
                                        'txn_unique_serial_id'      => $row[1],
                                        'outlet_name'   => $row[2],
                                        'txn_customer_id'   => $row[3],
                                        'txn_customer_number'   => $row[4],
                                        'txn_customer_name'     => $row[5],
                                        'category_type'     => $row[6],
                                        'Quantity'  => $row[7],
                                        'txn_discount'  => $row[8],
                                        'txn_value'     => $row[9],
                                        'txn_cashier'   => $row[0],
                                        'CGST'  => $row[11],
                                        'SGST'  => $row[12],
                                        'txn_total_tax'     => $row[13],
                                        'txn_pending_amount'    => $row[14],
                                        'txn_loyalty_points'    => $row[15],
                                        'txn_status'    => $row[16],
                                        'txn_remarks'   => $row[17],
                                        'txn_business_admin_id'     => $this->session->userdata['logged_in']['business_admin_id'],
                                        'txn_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
                                        'master_admin_id'   => $row[18]
                                    );
                                    $result = $this->BusinessAdminModel->Insert($data,'mss_transactions_replica');
                                        
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
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }
	}
	public function ExpenseReport(){
        if($this->IsLoggedIn('business_admin')){
            $data = $this->GetDataForAdmin("Expense");
            // $this->PrettyPrintArray($_GET);
            // exit;
            $data = array(
                'business_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
                'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id']
            );
                //Return the report view
                    if($_GET['group'] == 'range'){
                        $date=$_GET['from_date'];
                        $date=explode("-",$date);
                        $data1=array(
                            'from_date' => $date[0],
                            'to_date' => $date[1],
                            'business_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
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
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }
    }
	public function CustomerBirthDayAnniver(){
        if($this->IsLoggedIn('business_admin')){
            $data = $this->GetDataForAdmin("Transaction History");
            $where = array(
                'outlet_id'         => $this->session->userdata['outlets']['current_outlet'],
                'business_admin_id' =>$this->session->userdata['logged_in']['business_admin_id']
            );
            
            $this->load->view('business_admin/ba_bday_anniversary_view',$data);
        }   
        else{   
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }
	}
	public function GetCustomerHistory(){
        if($this->IsLoggedIn('business_admin')){
            // $this->PrettyPrintArray($_GET);
            // exit;
            $data = $this->GetDataForAdmin("BirthDay & Anniversary");
            $month="2020-".$_GET['month']."-01";
            $where = array(
                'month' =>$month,
                'outlet_id'         =>$this->session->userdata['outlets']['current_outlet'],
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
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }
    }
    public function GetCustomerHistoryAnniversary(){
        if($this->IsLoggedIn('business_admin')){
            
            $data = $this->GetDataForAdmin("BirthDay & Anniversary");
            $month="2020-".$_GET['month']."-01";
            $where = array(
                'month' =>$month,
                'outlet_id'         =>  $this->session->userdata['outlets']['current_outlet'],
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
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }
	}
	public function GetCustomer(){
        if($this->IsLoggedIn('business_admin')){
            // $this->PrettyPrintArray($_GET);
            //  exit;
            if(isset($_GET) && !empty($_GET)){
                
                $data = $this->BusinessAdminModel->GetCompleteCustomer($_GET['customer_id']);
                // $this->PrettyPrintArray($data);
                // exit;
                if($data['success'] == 'true'){     
                    header("Content-type: application/json");
                    print(json_encode($data['res_arr'], JSON_PRETTY_PRINT));
                    die;    
                }
            }
        }
        else{
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }   
    }
    public function SendSms(){
        if($this->IsLoggedIn('business_admin')){
            if(isset($_GET) && !empty($_GET)){
                        $result=$this->CashierModel->DetailsById($this->session->userdata['outlets']['current_outlet'],'mss_business_outlets','business_outlet_id');
                        // $this->PrettyPrintArray($_GET);
                        // exit;
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
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }
    }
    public function SendSmsMessage($option,$sender_id,$api_key,$mobile,$name,$outlet_name,$oulet_location,$address){
        if($this->IsLoggedIn('business_admin')){
            // $this->PrettyPrintArray("hii");
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
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }               
	}
	//01-04-2020
	public function GetVendorDetails(){
        if($this->IsLoggedIn('business_admin')){
            // $this->PrettyPrintArray($_GET);
            //  exit;
            if(isset($_GET) && !empty($_GET)){
                $data = $this->BusinessAdminModel->GetVendorDetail($_GET['vendor_id']);
                // $this->PrettyPrintArray($data);
                // exit;
                if($data['success'] == 'true'){     
                    header("Content-type: application/json");
                    print(json_encode($data['res_arr'][0], JSON_PRETTY_PRINT));
                    die;    
                }
            }
        }
        else{
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }   
    }
    //Update vendors Details
    public function UpdateVendor(){  
        if($this->IsLoggedIn('business_admin')){
          if(isset($_POST) && !empty($_POST)){
                $data = array(
                'vendor_id'     =>  $this->input->post('vendor_id'),
                'vendor_name'      => $this->input->post('vendor_name'),
                'vendor_contact_person'   => $this->input->post('vendor_contact_person'),
                'vendor_deals_in'         => $this->input->post('vendor_products'),
                'vendor_contact_no'           => $this->input->post('vendor_contact_no'),
                'vendor_landline_no'      => $this->input->post('vendor_landline_no'),
                'vendor_state'        => $this->input->post('vendor_state'),
                'vendor_city'      => $this->input->post('vendor_city'),
                'vendor_address'    => $this->input->post('vendor_address'),
                'vendor_gst_no'     => $this->input->post('gst_no'),
                'vandor_bank_acc_no'    => $this->input->post('bank_no'),
                'vandor_bank_ifsc_bank'    => $this->input->post('ifsc_code'),
                'business_outlet_id'    =>$this->session->userdata['outlets']['current_outlet']
              );
              $result = $this->BusinessAdminModel->Update($data,'mss_vendors','vendor_id');
              if($result['success'] == 'true'){
                $this->ReturnJsonArray(true,false,"Vendor Updated successfully!");
                die;
              }
              elseif($result['error'] == 'true'){
                $this->ReturnJsonArray(false,true,$result['message']);
                die;
              }
            }
        }
        else{
          $this->LogoutUrl(base_url()."BusinessAdmin/");
        }
    }
    public function TxnHistory(){
        if($this->IsLoggedIn('business_admin')){
			$data = $this->GetDataForAdmin("Transaction History");
            // $this->PrettyPrintArray($this->session->userdata);
			if(isset($this->session->userdata['outlets'])){
				$where = array(
					'business_outlet_id'         =>$this->session->userdata['outlets']['current_outlet'],
					'business_admin_id' =>$this->session->userdata['logged_in']['business_admin_id']
				);
				$data['txnhistory']=$this->BusinessAdminModel->TxnHistory($where);
				// $this->PrettyPrintArray($data['txnhistory']);
				
				if($data['txnhistory']['success'] == 'true')
				{
					$data['txnhistory']=$data['txnhistory']['res_arr'];
				}
				else
				{
					$data['txnhistory']+=['res_arr'=>''];
					$data['txnhistory']=$data['txnhistory']['res_arr'];
				}
				
				$data['txnhistorypackages']=$this->BusinessAdminModel->TxnHistoryPackages($where);
				if($data['txnhistorypackages']['success'] == 'true')
				{
					$data['txnhistorypackages']=$data['txnhistorypackages']['res_arr'];
				}
				else
				{
					$data['txnhistorypackages']+=['res_arr'=>''];
					$data['txnhistorypackages']=$data['txnhistorypackages']['res_arr'];
				}
				$data['services']=$this->BusinessAdminModel->TxnHistoryServices($where);
				if($data['services']['success'] == 'true')
				{
					$data['services']=$data['services']['res_arr'];
				}
				else
				{
					$data['services']+=['res_arr'=>''];
					$data['services']=$data['services']['res_arr'];
				}
				$data['products']=$this->BusinessAdminModel->TxnHistoryProduct($where);
				if($data['products']['success'] == 'true')
				{
					$data['products']=$data['products']['res_arr'];
				}
				else
				{
					$data['products']+=['res_arr'=>''];
					$data['products']=$data['products']['res_arr'];
				}
				// $this->PrettyPrintArray($data['services']);
				// exit;    
				
 			}   
			$this->load->view('business_admin/ba_transaction_history',$data);
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}
    public function CustTransHistory(){
        if($this->IsLoggedIn('cashier')){
            $data = $this->GetDataForAdmin("Transaction History");
            $where = array(
                'outlet_id'         =>$this->session->userdata['outlets']['current_outlet'],
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
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }       
	}
	public function GetProductDataDetails(){
        if($this->IsLoggedIn('business_admin')){
            if(isset($_GET) && !empty($_GET)){
                // $this->PrettyPrintArray($_GET);
                $data = $this->BusinessAdminModel->SearchProductDetails($_GET['query'],$_GET['inventory_type'],$this->session->userdata['logged_in']['business_admin_id'],$this->session->userdata['outlets']['current_outlet']);
                if($data['success'] == 'true'){ 
                    $this->ReturnJsonArray(true,false,$data['res_arr']);
                    die;
                }
            }
        }
        else{
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }
		}
		
    public function EditOTCService(){
        if($this->IsLoggedIn('business_admin')){
            if(isset($_POST) && !empty($_POST)){
                $this->form_validation->set_rules('otc_item_name', 'otc Item Name', 'trim|required|max_length[100]');
                $this->form_validation->set_rules('otc_brand', 'Otc brand', 'trim|required|max_length[100]');
                $this->form_validation->set_rules('otc_unit', 'Otc unit', 'trim|required|max_length[50]');
                $this->form_validation->set_rules('otc_gst_percentage', 'OTC GST Percentage', 'trim|required');
                $this->form_validation->set_rules('otc_price_inr', 'OTC Gross Price', 'trim|required');
                $this->form_validation->set_rules('otc_inventory_type', 'Inventory Type', 'trim|required');
                // $this->form_validation->set_rules('otc_barcode', 'Barcode', 'trim|required');
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
									if($_POST['otc_inventory_type']=='Retail Product'){
										$inventory_type_id = 2;
									}else{
										$inventory_type_id = 1;
									}
                    $data = array(
                        'service_name'          => $this->input->post('otc_item_name'),
                        'service_brand'         => $this->input->post('otc_brand'),
                        'service_sub_category_id'=>$this->input->post('otc_sub_category_id'),
                        'service_unit'          => $this->input->post('otc_unit'),
												'service_id'          => $this->input->post('otc_service_id'),
												'service_is_active'	=>1,
                        'barcode'   =>  $this->input->post('otc_barcode'),
                        'inventory_type'    =>  $this->input->post('otc_inventory_type'),
                        'service_price_inr'                 => $this->input->post('otc_price_inr'),
                        'service_gst_percentage'        => $this->input->post('otc_gst_percentage'),
												'qty_per_item'      => $this->input->post('sku_size'),
												'inventory_type_id'	=>	$inventory_type_id
										);
										// $this->PrettyPrintArray($_POST);
                    $result = $this->BusinessAdminModel->Update($data,'mss_services','service_id');
                    if($result['success'] == 'true'){
                        $this->ReturnJsonArray(true,false,"OTC item updated successfully!");
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
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }   
    }
    public function ServiceReportDownload(){
        if($this->IsLoggedIn('business_admin')){
            $data = array(
                'type'  => $_GET['type'],
                'business_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
                'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id']
            );
                if($_GET['type'] == 'otc'){
                        $result = $this->BusinessAdminModel->OTCReports($data);
                    }
                    else if($_GET['type'] == 'service'){
                        $result = $this->BusinessAdminModel->ServiceReports($data);
                    }
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
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }
    }
    // function for cust transaction history search function
    //17-04
    public function TxnHistoryByCustomerS(){    
        // $this->PrettyPrintArray($_GET);
        if($this->IsLoggedIn('business_admin')){
            if(isset($_GET) && !empty($_GET)){
                $where=array(
                            'outlet_id' => $this->session->userdata['outlets']['current_outlet'],
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
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }
    }
    public function AddDataInServiceTable(){    
        if($this->IsLoggedIn('business_admin')){
            if(isset($_GET) && !empty($_GET)){
               $where=array('customer_no'=>$_GET['customer_no'],
                			'outlet_id' => $this->session->userdata['outlets']['current_outlet'],
							'business_admin_id'=>$this->session->userdata['logged_in']['business_admin_id'],
							'master_admin_id' => $this->session->userdata['logged_in']['master_admin_id']
						);             
                $data['service'] = $this->CashierModel->GetCustServiceData($where);
                // $data['service']=$data['service']['res_arr'];
                $data['package'] = $this->CashierModel->GetCustPackagesData($where);
                // $data['package']=$data['package']['res_arr'];
                $data['pproduct'] = $this->CashierModel->GetCustServicePP($where);
                // $data['pproduct']=$data['pproduct']['res_arr'];
                $data['sservice'] = $this->CashierModel->GetCustServicePS($where);
                // $data['sservice']=$data['sservice']['res_arr'];
                // $this->PrettyPrintArray($data);
                // die;
                // if($data['service']['success'] == 'true'){ 
                    // $data1 = array(
                    //     'success' => 'true',
                    //     'error'   => 'false',
                    //     'message' => '',
                    //     'result' =>  $data
                    //  );
                        // $this->PrettyPrintArray($data1);
                        header("Content-type: application/json");
                        print(json_encode($data, JSON_PRETTY_PRINT));
                        die;        
                }
            // }
        }
        else{
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }
    }
    // 18-04
    public function TxnHistoryByCustomerP(){    
        // $this->PrettyPrintArray($_GET);
        if($this->IsLoggedIn('business_admin')){
            if(isset($_GET) && !empty($_GET)){
                $where=array(
                    'outlet_id' => $this->session->userdata['outlets']['current_outlet'],
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
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }
    }
    public function AddDataInPackageTable(){    
        if($this->IsLoggedIn('business_admin')){
            if(isset($_GET) && !empty($_GET)){
                $where=array('customer_no'=>$_GET['customer_no'],
                'outlet_id' => $this->session->userdata['outlets']['current_outlet'],
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
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }
    }
    // preffered Services
    public function TxnHistoryByCustomerPS(){   
        // $this->PrettyPrintArray($_GET);
        if($this->IsLoggedIn('business_admin')){
            if(isset($_GET) && !empty($_GET)){
                $where=array(
                    'outlet_id' => $this->session->userdata['outlets']['current_outlet'],
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
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }
    }
    public function AddDataINPrefferedServicesTable(){  
        if($this->IsLoggedIn('business_admin')){
            if(isset($_GET) && !empty($_GET)){
                $where=array('customer_no'=>$_GET['customer_no'],
                'outlet_id' => $this->session->userdata['outlets']['current_outlet'],
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
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }
    }
    // preffered product
    public function TxnHistoryByCustomerPP(){   
        // $this->PrettyPrintArray($_GET);
        if($this->IsLoggedIn('business_admin')){
            if(isset($_GET) && !empty($_GET)){
                $where=array(
                    'outlet_id' => $this->session->userdata['outlets']['current_outlet'],
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
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }
    }
    public function AddDataINPrefferedProductTable(){   
        if($this->IsLoggedIn('business_admin')){
            if(isset($_GET) && !empty($_GET)){
                $where=array('customer_no'=>$_GET['customer_no'],
                            'outlet_id' => $this->session->userdata['outlets']['current_outlet'],
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
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }
    }
    //27-04-2020
	// 24-04
    public function Engagement(){
        if($this->IsLoggedIn('business_admin')){
            $data = $this->GetDataForAdmin("Customer");
            if(isset($data['selected_outlet']) || !empty($data['selected_outlet'])){
                $data['sidebar_collapsed'] = "true";
            }
            
            $data['timeline']=$this->BusinessAdminModel->DetailsById($this->session->userdata['outlets']['current_outlet'],'mss_customer_timeline_setup','business_outlet_id');
            if($data['timeline']['success'] == 'true'){
                $res=array(
                     'id' => $data['timeline']['res_arr']['id'],
                     'r1' => $data['timeline']['res_arr']['r1'],
										 'r2' => $data['timeline']['res_arr']['r2'],
										 'regular_cust' => $data['timeline']['res_arr']['regular_cust'],
										 'no_risk' => $data['timeline']['res_arr']['no_risk'],
										 'dormant_r1' => $data['timeline']['res_arr']['dormant_r1'],
										 'dormant_r2' => $data['timeline']['res_arr']['dormant_r2'],
										 'at_risk_cust' => $data['timeline']['res_arr']['at_risk_cust'],
										 'at_risk_cust2' => $data['timeline']['res_arr']['at_risk_cust2'],
                     'lost_customer' => $data['timeline']['res_arr']['lost_customer']
                );
            }
            else{
                $res=array(
                    'id' => 0,
                    'r1' => 2,
                    'r2' => 5,
										'regular_cust' => 5,
										'no_risk'	=>30,
										'dormant_r1'	=> 31,
										'dormant_r2'	=>60,
										'at_risk_cust' => 61,
										'at_risk_cust2' => 90,
                    'lost_customer' => 90
               );
            }
            $data['custwithouttrans']=$this->BusinessAdminModel->CustomerWithoutTransaction();
            // $this->PrettyPrintArray($data);
            if($data['custwithouttrans']['success'] == 'true'){
                $data['custwithouttrans']=($data['custwithouttrans']['res_arr'][0]['count']);
            }
            else{
                $data['custwithouttrans']=0;
            }
            $data['allcust']=$this->BusinessAdminModel->AllCustomerCount();
            if($data['allcust']['success'] == 'true'){
                $data['allcust']=($data['allcust']['res_arr'][0]['count']);
            }
            else{
                $data['allcust']=0;
            }
            $data['new']=$this->BusinessAdminModel->NewCustomer($res);
            if($data['new']['success'] == 'true'){
                $data['new']=count($data['new']['res_arr']);
            }
            else{
                $data['new']=0;
            }
            $data['repeat']=$this->BusinessAdminModel->RepeatingCustomerService($res);
            if($data['repeat']['success'] == 'true'){
                $data['repeat']=count($data['repeat']['res_arr']);
            }
            else{
                $data['repeat']=0;
            }
            $data['regular']=$this->BusinessAdminModel->RegularCustomer($res);
            if($data['regular']['success'] == 'true'){
                $data['regular']=count($data['regular']['res_arr']);
            }
            else{
                $data['regular']=0;
						}
						$data['no_risk']=$this->BusinessAdminModel->NoRiskCustomer($res);
            if($data['no_risk']['success'] == 'true'){
                $data['no_risk']=count($data['no_risk']['res_arr']);
            }
            else{
                $data['no_risk']=0;
						}
						$data['dormant']=$this->BusinessAdminModel->DormantCustomer($res);
            if($data['dormant']['success'] == 'true'){
                $data['dormant']=count($data['dormant']['res_arr']);
            }
            else{
                $data['dormant']=0;
            }
            $data['risk']=$this->BusinessAdminModel->RiskCustomerService($res);
            if($data['risk']['success']='true'){
                $data['risk']=count($data['risk']['res_arr']);
            }else{
                $data['risk']=0;
            }
            $data['lost']=$this->BusinessAdminModel->LostCustomerService($res);
            if($data['lost']['success']='true'){
                $data['lost']=count($data['lost']['res_arr']);
            }else{
                $data['lost']=0;
            }
            $this->load->view('business_admin/ba_engagement_view',$data);
        }
        else{
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }
    }
    public function Customertimeline(){
        if($this->IsLoggedIn('business_admin')){
            $data = $this->GetDataForAdmin("Customer");
            if(isset($data['selected_outlet']) || !empty($data['selected_outlet'])){
                $data['sidebar_collapsed'] = "true";
            }
            // $data['timeline']=$this->BusinessAdminModel->DetailsById($this->session->userdata['outlets']['current_outlet'],'mss_customer_timeline_setup','business_outlet_id');
            // $this->PrettyPrintArray($data['timeline']);
            $data['timeline']=$this->BusinessAdminModel->DetailsById($this->session->userdata['outlets']['current_outlet'],'mss_customer_timeline_setup','business_outlet_id');
            // $this->PrettyPrintArray($data['timeline']);
            if($data['timeline']['success'] == 'true'){
                $res=array(
										'id' => $data['timeline']['res_arr']['id'],
										'r1' => $data['timeline']['res_arr']['r1'],
										'r2' => $data['timeline']['res_arr']['r2'],
										'no_risk' => $data['timeline']['res_arr']['no_risk'],
										'dormant_r1' => $data['timeline']['res_arr']['dormant_r1'],
										'dormant_r2' => $data['timeline']['res_arr']['dormant_r2'],
										'regular_cust' => $data['timeline']['res_arr']['regular_cust'],
										'at_risk_cust' => $data['timeline']['res_arr']['at_risk_cust'],
										'at_risk_cust2' => $data['timeline']['res_arr']['at_risk_cust2'],
										'lost_customer' => $data['timeline']['res_arr']['lost_customer']
                );
            }
            else{
                $res=array(
                    'id' => 0,
                    'r1' => 2,
                    'r2' => 5,
										'regular_cust' => 5,
										'no_risk'	=> 30,
										'dormant_r1'=>31,
										'dormant_r2'=>60,
										'at_risk_cust' => 61,
										'at_risk_cust2'	=>90,
                    'lost_customer' => 91
               );
						}

						$data['allcust']=$this->BusinessAdminModel->AllCustomerCount();
            if($data['allcust']['success'] == 'true'){
                $data['allcust']=($data['allcust']['res_arr'][0]['count']);
            }
            else{
                $data['allcust']=0;
            }

						$data['custwithouttrans']=$this->BusinessAdminModel->CustomerWithoutTransaction();
            // $this->PrettyPrintArray($data);
            if($data['custwithouttrans']['success'] == 'true'){
                $data['custwithouttrans']=($data['custwithouttrans']['res_arr'][0]['count']);
            }
            else{
                $data['custwithouttrans']=0;
            }
            $data['new']=$this->BusinessAdminModel->NewCustomer($res);
            if($data['new']['success'] == 'true'){
								$data['new']=count($data['new']['res_arr']);
            }
            else{
                $data['new']=0;
            }
            $data['repeat']=$this->BusinessAdminModel->RepeatingCustomerService($res);
            if($data['repeat']['success'] == 'true'){
								$data['repeat']=count($data['repeat']['res_arr']);
								// $this->PrettyPrintArray($data['repeat']);
								
            }
            else{
                $data['repeat']=0;
            }
            $data['regular']=$this->BusinessAdminModel->RegularCustomer($res);
            if($data['regular']['success'] == 'true'){
                $data['regular']=count($data['regular']['res_arr']);
            }
            else{
                $data['regular']=0;
						}
						
						$data['no_risk']=$this->BusinessAdminModel->NoRiskCustomer($res);
            if($data['no_risk']['success'] == 'true'){
                $data['no_risk']=count($data['no_risk']['res_arr']);
            }
            else{
                $data['no_risk']=0;
						}
						$data['dormant']=$this->BusinessAdminModel->DormantCustomer($res);
            if($data['dormant']['success'] == 'true'){
                $data['dormant']=count($data['dormant']['res_arr']);
            }
            else{
                $data['dormant']=0;
            }

            $data['risk']=$this->BusinessAdminModel->RiskCustomerService($res);
            if($data['risk']['success']='true'){
                $data['risk']=count($data['risk']['res_arr']);
            }else{
                $data['risk']=0;
            }
            $data['lost']=$this->BusinessAdminModel->LostCustomerService($res);
            if($data['lost']['success']='true'){
                $data['lost']=count($data['lost']['res_arr']);
            }else{
                $data['lost']=0;
            }
            // $this->PrettyPrintArray($data['timeline']);
            $this->load->view('business_admin/ba_customer_timeline_view',$data);
        }
        else{
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }
    }
	public function InsertTimeline(){  
        if($this->IsLoggedIn('business_admin')){
          if(isset($_POST) && !empty($_POST)){
              if($_POST['id'] == 0){
                    $data=array(
                        'r1'=>$_POST['r1'],
                        'r2'=>$_POST['r2'],
												'regular_cust'=>$_POST['reg_cust'],
												'no_risk'			=>$_POST['no_risk'],
												'dormant_r1'			=>$_POST['dormant_r1'],
												'dormant_r2'			=>$_POST['dormant_r2'],
												'at_risk_cust'=>$_POST['at_risk_r1'],
												'at_risk_cust2'=>$_POST['at_risk_r2'],
                        'lost_customer'=>$_POST['lost_cust'],
                        'business_outlet_id'=>$this->session->userdata['outlets']['current_outlet'],
                        'business_admin_id'=>$this->session->userdata['logged_in']['business_admin_id'],
                        'master_admin_id'=>$this->session->userdata['logged_in']['master_admin_id']
                    );
                    $result = $this->BusinessAdminModel->Insert($data,'mss_customer_timeline_setup');
                }else{
                // $this->PrettyPrintArray($_POST);
                        $data=array(
                            'id'=>$_POST['id'],
                            'r1'=>$_POST['r1'],
                            'r2'=>$_POST['r2'],
														'regular_cust'=>$_POST['reg_cust'],
														'no_risk'			=>$_POST['no_risk'],
														'dormant_r1'			=>$_POST['dormant_r1'],
														'dormant_r2'			=>$_POST['dormant_r2'],
														'at_risk_cust'=>$_POST['at_risk_r1'],
														'at_risk_cust2'=>$_POST['at_risk_r2'],
                            'lost_customer'=>$_POST['lost_cust'],
                            'business_outlet_id'=>$this->session->userdata['outlets']['current_outlet'],
                            'business_admin_id'=>$this->session->userdata['logged_in']['business_admin_id'],
                            'master_admin_id'=>$this->session->userdata['logged_in']['master_admin_id']
                        );
                        $result = $this->BusinessAdminModel->Update($data,'mss_customer_timeline_setup','id');
                    }
              if($result['success'] == 'true'){
                $this->ReturnJsonArray(true,false,"Data Added successfully!");
                die;
              }
              elseif($result['error'] == 'true'){
                $this->ReturnJsonArray(false,true,$result['message']);
                die;
              }
            }
        }
        else{
          $this->LogoutUrl(base_url()."BusinessAdmin/");
        }
    }
    public function Campaigns(){
        if($this->IsLoggedIn('business_admin')){
            $data = $this->GetDataForAdmin("Customer");
            if(isset($data['selected_outlet']) || !empty($data['selected_outlet'])){
                $data['sidebar_collapsed'] = "true";
            }
            $where = array(
                'business_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
                'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id'],
            );
            $data['campaigns']= $this->BusinessAdminModel->MultiWhereSelect('mss_campaign',$where);
            $data['campaigns']=$data['campaigns']['res_arr'];
            $data['tags']=$this->BusinessAdminModel->GetTags();
            if($data['tags']['success']=='true'){
                $data['tags']=$data['tags']['res_arr'];
            }else{
                $data['tags']=0;
            }
             $data['timeline']=$this->BusinessAdminModel->DetailsById($this->session->userdata['outlets']['current_outlet'],'mss_customer_timeline_setup','business_outlet_id');
            if($data['timeline']['success'] == 'true'){
                $res=array(
                     'id' => $data['timeline']['res_arr']['id'],
                     'r1' => $data['timeline']['res_arr']['r1'],
                     'r2' => $data['timeline']['res_arr']['r2'],
										 'regular_cust' => $data['timeline']['res_arr']['regular_cust'],
										 'no_risk'		=> $data['timeline']['res_arr']['no_risk'],
										 'dormant_r1'		=> $data['timeline']['res_arr']['dormant_r1'],
										 'dormant_r2'		=> $data['timeline']['res_arr']['dormant_r2'],
										 'at_risk_cust' => $data['timeline']['res_arr']['at_risk_cust'],
										 'at_risk_cust2' => $data['timeline']['res_arr']['at_risk_cust2'],
                     'lost_customer' => $data['timeline']['res_arr']['lost_customer']
                );
            }
            else{
                $res=array(
                    'id' => 0,
                    'r1' => 2,
                    'r2' => 5,
										'regular_cust' => 5,
										'no_risk'	=> 30,
										'dormant_r1'	=>31,
										'dormant_r2'	=>60,
										'at_risk_cust' => 61,
										'at_risk_cust2' => 90,
                    'lost_customer' => 91
               );
            }
            $data['new']=$this->BusinessAdminModel->NewCustomer($res);
            if($data['new']['success'] == 'true'){
                $data['new']=count($data['new']['res_arr']);
            }
            else{
                $data['new']=0;
            }
            $data['repeat']=$this->BusinessAdminModel->RepeatingCustomerService($res);
            if($data['repeat']['success'] == 'true'){
                $data['repeat']=count($data['repeat']['res_arr']);
            }
            else{
                $data['repeat']=0;
            }
            $data['regular']=$this->BusinessAdminModel->RegularCustomer($res);
            if($data['regular']['success'] == 'true'){
                $data['regular']=count($data['regular']['res_arr']);
            }
            else{
                $data['regular']=0;
            }
            $data['risk']=$this->BusinessAdminModel->RiskCustomerService($res);
            if($data['risk']['success']='true'){
                $data['risk']=count($data['risk']['res_arr']);
            }else{
                $data['risk']=0;
            }
            $data['lost']=$this->BusinessAdminModel->LostCustomerService($res);
            if($data['lost']['success']='true'){
                $data['lost']=count($data['lost']['res_arr']);
            }else{
                $data['lost']=0;
						}
						//Birthday Customer$b
						$birthday=$this->BusinessAdminModel->GetBirthday();

            $this->load->view('business_admin/ba_campaign_manager',$data);
        }
        else{
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }
    }
    //Insert Camapign Details 
    public function InsertCampaignData(){
        if($this->IsLoggedIn('business_admin')){
								// $data = explode('&',$_POST);
                $res1 = $this->BusinessAdminModel->DetailsById($this->session->userdata['outlets']['current_outlet'],'mss_business_outlets','business_outlet_id');
                $data=array(
                    'campaign_mode' => $_POST['ftype'],
                    'rundate'=>date("Y-m-d"),
                    'campaign_name' => $_POST['camapign_name'],
                    'message'   =>$_POST['message'],
                    'name'=>$_POST['name'],
                    'image'=>' ',
                    'recipient'=>$_POST['recipent'],
                    'send_info'=>$_POST['wsend'],
                    'business_outlet_id'=>$this->session->userdata['outlets']['current_outlet'],
                    'business_admin_id'=>$this->session->userdata['logged_in']['business_admin_id'],
                    'master_admin_id'=>$this->session->userdata['logged_in']['master_admin_id']
                );    
                $result = $this->BusinessAdminModel->Insert($data,'mss_campaign');
                $data=array(
                    'campaign_mode' => $_POST['ftype'],
                    'rundate'=>date("Y-m-d"),
                    'campaign_name' => $_POST['camapign_name'],
                    'message'   =>$_POST['message'],
                    'name'=>$_POST['name'],
                    'image'=>' ',
                    'recipient'=>$_POST['recipent'],
                    'myfile'=>$_FILES['file'],
                    'send_info'=>$_POST['wsend'],
                    'business_outlet_id'=>$this->session->userdata['outlets']['current_outlet'],
                    'business_admin_id'=>$this->session->userdata['logged_in']['business_admin_id'],
                    'master_admin_id'=>$this->session->userdata['logged_in']['master_admin_id']
                );
                // $this->PrettyPrintArray($res1);
                if($_POST['wsend'] =='now'){
                    $this->SendCampaignData($data);
                }
                if($result['success'] == 'true'){
                    $this->ReturnJsonArray(true , false,'Success.' );
                    exit;
                }else{
                    $this->ReturnJsonArray(false , true,'Commission failed.' );
                    exit;
                }               
            }
            else{
                $this->LogoutUrl(base_url()."BusinessAdmin/");
            }   
    }
    public function SendSmsCampaign($sender_id,$api_key,$mobile,$camapign_name,$name,$cust_name,$message,$outlet_name){
        if($this->IsLoggedIn('business_admin')){
        
        $msg = str_replace('#Name#', $cust_name, $message); 
        $msg = $msg." ".$outlet_name;
        // $this->PrettyPrintArray($msg);
        // exit;
        $msg = rawurlencode($msg);                          
            // API 
            $url = 'https://www.smsgatewayhub.com/api/mt/SendSMS?APIKey='.$api_key.'&senderid='.$sender_id.'&channel=1&DCS=0&flashsms=0&number='.$mobile.'&text='.$msg.'&route=1';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,"");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,2);
        $data = curl_exec($ch);
        return json_encode($data);
        }
        else{
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }               
    }
      public function SendCampaignData($datasend){
        if($this->IsLoggedIn('business_admin')){
            // $this->PrettyPrintArray($datasend);
            $data['timeline']=$this->BusinessAdminModel->DetailsById($this->session->userdata['outlets']['current_outlet'],'mss_customer_timeline_setup','business_outlet_id');
            $res1 = $this->BusinessAdminModel->DetailsById($this->session->userdata['outlets']['current_outlet'],'mss_business_outlets','business_outlet_id');
            $res1=$res1['res_arr'];
            if($data['timeline']['success'] == 'true'){
                $res=array(
					
                     'id' 					=> $data['timeline']['res_arr']['id'],
                     'r1' 					=> $data['timeline']['res_arr']['r1'],
                     'r2' 					=> $data['timeline']['res_arr']['r2'],
										 'regular_cust' => $data['timeline']['res_arr']['regular_cust'],
										 'no_risk'			=> $data['timeline']['res_arr']['no_risk'],
										 'dormant_r1'		=> $data['timeline']['res_arr']['dormant_r1'],
										 'dormant_r2'		=> $data['timeline']['res_arr']['dormant_r2'],
										 'at_risk_cust' => $data['timeline']['res_arr']['at_risk_cust'],
										 'at_risk_cust2' => $data['timeline']['res_arr']['at_risk_cust2'],
                     'lost_customer' => $data['timeline']['res_arr']['lost_customer']
                );
            }
            else{
                $res=array(
                    'id' => 0,
                    'r1' => 2,
                    'r2' => 5,
										'regular_cust' => 5,
										'no_risk'			=>30 ,
										'dormant_r1'	=>	31,
										'dormant_r2'	=>	60,
										'at_risk_cust' => 61,
										'at_risk_cust2'	=>90,
                    'lost_customer' => 91
               );
            }
            if($datasend['recipient'] == 'Manual')
            {
				// file data
					// $this->PrettyPrintArray($_FILES);
						$pathinfo = pathinfo($_FILES["file"]["name"]);        
						// check file has extension xlsx, xls and also check file is not empty
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
								if ($count >= 1) {     
									$data = array(
										'cust_name' => $row[0],
										'mobile'    => $row[1],
										
									);
									        $this->SendSmsCampaign($res1['business_outlet_sender_id'],$res1['api_key'],$data['mobile'],$datasend['campaign_name'],$datasend['name'],$data['cust_name'],$datasend['message'],$res1['business_outlet_name']);
								}
							}
						}
						
                // }   
            }elseif($datasend['recipient'] == 'All' ){
                    $where = array(
                        'customer_business_outlet_id' => $this->session->userdata['outlets']['current_outlet']
                    );
                    $res = $this->BusinessAdminModel->MultiWhereSelect('mss_customers',$where);
                    $res=$res['res_arr'];
                    foreach($res as $key=>$value){
                        // $this->PrettyPrintArray($value);
                        $this->SendSmsCampaign($res1['business_outlet_sender_id'],$res1['api_key'],$value['customer_mobile'],$datasend['campaign_name'],$datasend['name'],$value['customer_name'],$datasend['message'],$res1['business_outlet_name']);
                    }
            }elseif($datasend['recipient'] == 'New' ){
                $data['new']=$this->BusinessAdminModel->NewCustomer($res);
                if($data['new']['success'] == 'true'){
                    $data['new']=($data['new']['res_arr']);
                }
                foreach($data['new'] as $key=>$value){
                    // $this->PrettyPrintArray($value);
                    $res=$this->BusinessAdminModel->DetailsById($value['txn_customer_id'],'mss_customers','customer_id');
                    $res=$res['res_arr'];
                    $this->SendSmsCampaign($res1['business_outlet_sender_id'],$res1['api_key'],$res['customer_mobile'],$datasend['campaign_name'],$datasend['name'],$value['customer_name'],$datasend['message'],$res1['business_outlet_name']);
                }   
            }elseif($datasend['recipient'] == 'Repeat'){
                $data['repeat']=$this->BusinessAdminModel->RepeatingCustomerService($res);
                if($data['repeat']['success'] == 'true'){
                    $data['repeat']=($data['repeat']['res_arr']);
                }
                foreach($data['repeat'] as $key=>$value){
                    $res=$this->BusinessAdminModel->DetailsById($value['txn_customer_id'],'mss_customers','customer_id');
                    $res=$res['res_arr'];
                    $this->SendSmsCampaign($res1['business_outlet_sender_id'],$res1['api_key'],$res['customer_mobile'],$datasend['campaign_name'],$datasend['name'],$value['customer_name'],$datasend['message'],$res1['business_outlet_name']);
                }   
            }elseif($datasend['recipient'] == 'Regular'){
                $data['regular']=$this->BusinessAdminModel->RegularCustomer($res);
                if($data['regular']['success'] == 'true'){
                    $data['regular']=($data['regular']['res_arr']);
                }
                // $this->PrettyPrintArray($data['regular']);
                foreach($data['regular'] as $key=>$value){
                    $res=$this->BusinessAdminModel->DetailsById($value['txn_customer_id'],'mss_customers','customer_id');
                    $res=$res['res_arr'];
                    $this->SendSmsCampaign($res1['business_outlet_sender_id'],$res1['api_key'],$res['customer_mobile'],$datasend['campaign_name'],$datasend['name'],$value['customer_name'],$datasend['message'],$res1['business_outlet_name']);
                }
						}elseif($datasend['recipient'] == 'NoRisk'){
							$data['no_risk']=$this->BusinessAdminModel->NoRiskCustomer($res);
							if($data['no_risk']['success'] == 'true'){
									$data['no_risk']=($data['no_risk']['res_arr']);
							}
							// $this->PrettyPrintArray($data['no_risk']);
							foreach($data['no_risk'] as $key=>$value){
									$res=$this->BusinessAdminModel->DetailsById($value['txn_customer_id'],'mss_customers','customer_id');
									$res=$res['res_arr'];
									$this->SendSmsCampaign($res1['business_outlet_sender_id'],$res1['api_key'],$res['customer_mobile'],$datasend['campaign_name'],$datasend['name'],$value['customer_name'],$datasend['message'],$res1['business_outlet_name']);
							}
					}elseif($datasend['recipient'] == 'Dormant'){
						$data['dormant']=$this->BusinessAdminModel->DormantCustomer($res);
						if($data['dormant']['success'] == 'true'){
								$data['dormant']=($data['dormant']['res_arr']);
						}
						
						foreach($data['dormant'] as $key=>$value){
								$res=$this->BusinessAdminModel->DetailsById($value['txn_customer_id'],'mss_customers','customer_id');
								$res=$res['res_arr'];
								$this->SendSmsCampaign($res1['business_outlet_sender_id'],$res1['api_key'],$res['customer_mobile'],$datasend['campaign_name'],$datasend['name'],$value['customer_name'],$datasend['message'],$res1['business_outlet_name']);
						}
				}elseif($datasend['recipient'] == 'Risk'){
                $data['risk']=$this->BusinessAdminModel->RiskCustomerService($res);
								if($data['risk']['success']='true'){
									$data['risk']=($data['risk']['res_arr']);
								}

                foreach($data['risk'] as $key=>$value){
                    // $this->PrettyPrintArray($value); 
                    $res=$this->BusinessAdminModel->DetailsById($value,'mss_customers','customer_id');
                    $res=$res['res_arr'];
                    $this->SendSmsCampaign($res1['business_outlet_sender_id'],$res1['api_key'],$res['customer_mobile'],$datasend['campaign_name'],$datasend['name'],$value['customer_name'],$datasend['message'],$res1['business_outlet_name']);
                }
            }elseif($datasend['recipient'] == 'Lost'){
                $data['lost']=$this->BusinessAdminModel->LostCustomerService($res);
					if($data['lost']['success']='true'){
						$data['lost']=($data['lost']['res_arr']);
						
					}
                foreach($data['lost'] as $key=>$value){
                    // $this->PrettyPrintArray($value); 
                    $res=$this->BusinessAdminModel->DetailsById($value,'mss_customers','customer_id');
                    $res=$res['res_arr'];
                    $this->SendSmsCampaign($res1['business_outlet_sender_id'],$res1['api_key'],$res['customer_mobile'],$datasend['campaign_name'],$datasend['name'],$value['customer_name'],$datasend['message'],$res1['business_outlet_name']);
                }
			}
			// Tags
			elseif($datasend['recipient'] != 'All' && $datasend['recipient'] != 'New' && $datasend['recipient'] != 'Repeat' && $datasend['recipient'] != 'Regular' && $datasend['recipient'] != 'risk' && $datasend['recipient'] != 'Lost' && $datasend['recipient'] != 'Manual'){
				$res['tag']=$this->BusinessAdminModel->GetTagsIDDetails($datasend['recipient']);
				
				if($res['tag']['success']=='true'){
					$res['tag']=$res['tag']['res_arr'][0];
				}
				
				$data['send']=array();
				$res['tag']+=['no_rules'=>'0'];
				$res['tag']+=['customers'=>'0'];
				
					$cust=0;
					$where = array(
						'tag_id' => $datasend['recipient'],
						'business_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
						'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id'],
					);
					$data['rules']=$this->BusinessAdminModel->MultiWhereSelect('mss_tags_rule',$where);
					if($data['rules']['success'] == 'true'){
						$data['rules']=$data['rules']['res_arr'];
					}
					// $this->PrettyPrintArray($data['rules']);die;
					$res['tag']['no_rules']=count($data['rules']);
					$cust=array();
					$cust_no=0;
					$succ=0;
					foreach($data['rules'] as $k=>$v){
						// $this->PrettyPrintArray($v);die;
						$res=array(
							'kpi'=>$v['kpi'],
							'start_range'=>$v['start_range'],
							'end_range'=>$v['end_range'],
							'start_range_date'=>$v['start_range_date'],
							'date'=>$v['date'],
							'end_range_date'=>$v['end_range_date'],
							'value'=>$v['value'],
							'select_option'=>$v['select_option']
						);
						$result=$this->BusinessAdminModel->CalRuleCust($res);
						// $this->PrettyPrintArray($result);
						// exit;
						
						if($result['success']=='true'){
							// $cust = $cust + count($result['res_arr']);
							if($v['and_or'] == 'OR'){
								foreach($result['res_arr'] as $key=>$value){
									// $this->PrettyPrintArray($value);
									// die;
									// foreach($value as $kk=>$vv){
										array_push($data['send'],$value['cust_id']);
									// }
								}
							}
							else{
								
								foreach($result['res_arr'] as $key=>$value){
									
									if(in_array($value['cust_id'],$cust)){
										// foreach($value as $kk=>$vv){
											// $this->PrettyPrintArray($vv);
											// die;
											array_push($data['send'],$value['cust_id']);
										// }
									}else{
										array_push($cust,$value['cust_id']);
										
									}
									
								}
								// $this->PrettyPrintArray($cust);echo "h";
								if(count($data['rules']) == 1){
									foreach($result['res_arr'] as $key=>$value){
										// $this->PrettyPrintArray($value);
										// die;
										// foreach($value as $kk=>$vv){
											array_push($data['send'],$value['cust_id']);
										// }
									}
								}

							}
						}
					}
					$data['send']=array_unique($data['send']);
					$this->PrettyPrintArray($data['send']);
					die;
					foreach($data['send'] as $key=>$vlue){
						$res=$this->BusinessAdminModel->DetailsById($vlue,'mss_customers','customer_id');
						$res=$res['res_arr'];
						$this->SendSmsCampaign($res1['business_outlet_sender_id'],$res1['api_key'],$res['customer_mobile'],$datasend['campaign_name'],$datasend['name'],$res['customer_name'],$datasend['message'],$res1['business_outlet_name']);
						
					}	
			}
                $result=array('success' => 'true');
                if($result['success'] == 'true'){
                    $this->ReturnJsonArray(true , false,'Success.' );
                    exit;
                }else{
                    $this->ReturnJsonArray(false , true,'Commission failed.' );
                    exit;
                }               
            }
            else{
                $this->LogoutUrl(base_url()."BusinessAdmin/");
            }   
    }
	// 27-04
    public function EngagementReport(){
        if($this->IsLoggedIn('business_admin')){
            $data = $this->GetDataForAdmin("Expense");
            $data['timeline']=$this->BusinessAdminModel->DetailsById($this->session->userdata['outlets']['current_outlet'],'mss_customer_timeline_setup','business_outlet_id');
            $res1 = $this->BusinessAdminModel->DetailsById($this->session->userdata['outlets']['current_outlet'],'mss_business_outlets','business_outlet_id');
            $res1=$res1['res_arr'];
            if($data['timeline']['success'] == 'true'){
                $res=array(
										'id' => $data['timeline']['res_arr']['id'],
										'r1' => $data['timeline']['res_arr']['r1'],
										'r2' => $data['timeline']['res_arr']['r2'],
										'regular_cust' => $data['timeline']['res_arr']['regular_cust'],
										'no_risk' => $data['timeline']['res_arr']['no_risk'],
										'dormant_r1' => $data['timeline']['res_arr']['dormant_r1'],
										'dormant_r2' => $data['timeline']['res_arr']['dormant_r2'],
										'at_risk_cust' => $data['timeline']['res_arr']['at_risk_cust'],
										'at_risk_cust2' => $data['timeline']['res_arr']['at_risk_cust2'],
										'lost_customer' => $data['timeline']['res_arr']['lost_customer']
                );
            }
            else{
                $res=array(
                    'id' => 0,
                    'r1' => 2,
                    'r2' => 5,
										'regular_cust' => 5,
										'no_risk' 			=>30,
										'dormant_r1'		=>31,
										'dormant_r2'		=>60,
										'at_risk_cust' 	=> 61,
										'at_risk_cust2' => 90,
                    'lost_customer' => 91
               );
            }
                //Return the report view
                    if($_GET['category'] == 'All'){
                        $result = $this->BusinessAdminModel->ReportAllCustomer($res);
                    }
                    else if($_GET['category'] == 'New'){
                        $result = $this->BusinessAdminModel->ReportNewCustomer($res);
                    }
                    else if($_GET['category'] == 'Repeat'){
                        $result = $this->BusinessAdminModel->ReportRepeatCustomer($res);
                    }
                    else if($_GET['category'] == 'Regular'){
                        $result = $this->BusinessAdminModel->ReportRegularCustomer($res);
                    }
                    else if($_GET['category'] == 'Risk'){
                        $riskS=array(); 
                        $data['riskS']=$this->BusinessAdminModel->RiskCustomerService($res);
                        if($data['riskS']['success']='true'){
                            $data['riskS']=$data['riskS']['res_arr'];
                            foreach($data['riskS'] as $key => $value){
                                array_push($riskS,$value['customer_id']);
                            }
                        }else{
                            $data['riskS']=0;
                        }
                        $ReportRiskS=array();   
                        for($i=0;$i<count($riskS);$i++){
                            $result = $this->BusinessAdminModel->ReportRiskSCustomer($riskS[$i]);
                            array_push($ReportRiskS,$result['res_arr'][0]);
                            // $this->PrettyPrintArray($result);
                        }
                        $result = array(
                            'success' => 'true',
                            'res_arr'   => $ReportRiskS
                        );
                        // $this->PrettyPrintArray($result);
                    }elseif($_GET['category'] == 'Lost'){
                        $lostS=array(); 
                        $data['lostS']=$this->BusinessAdminModel->LostCustomerService($res);
                        if($data['lostS']['success']='true'){
                            $data['lostS']=$data['lostS']['res_arr'];
                            foreach($data['lostS'] as $key => $value){
                                array_push($lostS,$value['customer_id']);
                            }
                        }else{
                            $data['lostS']=0;
                        }
                        $ReportRiskS=array();   
                        for($i=0;$i<count($lostS);$i++){
                            $result = $this->BusinessAdminModel->ReportRiskSCustomer($lostS[$i]);
                            array_push($ReportRiskS,$result['res_arr'][0]);
                            // $this->PrettyPrintArray($result);
                        }
                        $result = array(
                            'success' => 'true',
                            'res_arr'   => $ReportRiskS
                        );
                    }
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
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }
    }
    // 28-04
    public function GetCustomerTxn(){
        if($this->IsLoggedIn('business_admin')){
            // $this->PrettyPrintArray($_GET);
            //  exit;
            if(isset($_GET) && !empty($_GET)){
                $data = $this->BusinessAdminModel->GetCompleteCustomerTxn($_GET['customer_no']);
                // $this->PrettyPrintArray($data);
                // exit;
                if($data['success'] == 'true'){     
                    header("Content-type: application/json");
                    print(json_encode($data['res_arr'], JSON_PRETTY_PRINT));
                    die;    
                }
            }
        }
        else{
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }   
	}
	public function EditCustomerDetails(){
        if($this->IsLoggedIn('business_admin')){
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
                    $details = $this->CashierAdmin->GetCustomerBilling($this->input->post('customer_id'));
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
                                'customer_title'    => $this->input->post('customer_title'),
                                'customer_name'     => $this->input->post('customer_name'),
                                'customer_dob'    => $this->input->post('customer_dob'),
                                'customer_doa'      => $this->input->post('customer_doa'),
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
                                $sess_data = $this->CashierAdmin->GetCustomerBilling($customer_id);
                                $sess_data['is_package_customer'] = $this->CashierAdmin->IsPackageCustomer($customer_id);
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
                            'customer_title'    => $this->input->post('customer_title'),
                            'customer_name'     => $this->input->post('customer_name'),
                            'customer_dob'    => $this->input->post('customer_dob'),
                            'customer_doa'      => $this->input->post('customer_doa')
                        );
                        $result = $this->CashierModel->Update($data,'mss_customers','customer_id');
                        if($result['success'] == 'true'){
                            /************************************************************************/
                                $customer_id = $this->input->post('customer_id');
                                $curr_sess_cust_data = $this->session->userdata['POS'];
                                $key = array_search($customer_id, array_column($curr_sess_cust_data,'customer_id'));
                                unset($curr_sess_cust_data[$key]);  
                                array_splice($curr_sess_cust_data, $key, $key); 
                                $sess_data = $this->CashierAdmin->GetCustomerBilling($customer_id);
                                $sess_data['is_package_customer'] = $this->CashierAdmin->IsPackageCustomer($customer_id);
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
	public function GetCustDataForTimeline(){
        if($this->IsLoggedIn('business_admin')){
            $data = $this->GetDataForAdmin("Expense");
            $data['timeline']=$this->BusinessAdminModel->DetailsById($this->session->userdata['outlets']['current_outlet'],'mss_customer_timeline_setup','business_outlet_id');
            $res1 = $this->BusinessAdminModel->DetailsById($this->session->userdata['outlets']['current_outlet'],'mss_business_outlets','business_outlet_id');
            $res1=$res1['res_arr'];
            if($data['timeline']['success'] == 'true'){
                $res=array(
										'id' => $data['timeline']['res_arr']['id'],
										'r1' => $data['timeline']['res_arr']['r1'],
										'r2' => $data['timeline']['res_arr']['r2'],
										'regular_cust' => $data['timeline']['res_arr']['regular_cust'],
										'no_risk'			=> $data['timeline']['res_arr']['no_risk'],
										'dormant_r1'			=> $data['timeline']['res_arr']['dormant_r1'],
										'dormant_r2'			=> $data['timeline']['res_arr']['dormant_r2'],
										'at_risk_cust' => $data['timeline']['res_arr']['at_risk_cust'],
										'at_risk_cust2' => $data['timeline']['res_arr']['at_risk_cust2'],
										'lost_customer' => $data['timeline']['res_arr']['lost_customer']
                );
            }
            else{
                $res=array(
                    'id' => 0,
                    'r1' => 2,
                    'r2' => 5,
										'regular_cust' => 5,
										'no_risk'			=> 30,
										'dormant_r1'			=> 31,
										'dormant_r2'			=> 60,
										'at_risk_cust' => 61,
										'at_risk_cust2' => 90,
                    'lost_customer' => 91
               );
            }
                //Return the report view
                    if($_GET['category'] == 'All'){
                        $result = $this->BusinessAdminModel->ReportAllCustomer($res);
                    }
                    else if($_GET['category'] == 'New'){
                        $result = $this->BusinessAdminModel->ReportNewCustomer($res);
                    }
                    else if($_GET['category'] == 'Repeat'){
                        $result = $this->BusinessAdminModel->ReportRepeatCustomer($res);
                    }
                    else if($_GET['category'] == 'Regular'){
                        $result = $this->BusinessAdminModel->ReportRegularCustomer($res);
										}
										else if($_GET['category'] == 'no_risk'){
											$result = $this->BusinessAdminModel->ReportNoRiskCustomer($res);
										}
										else if($_GET['category'] == 'dormant'){
											$result = $this->BusinessAdminModel->ReportDormantCustomer($res);
										}
                    else if($_GET['category'] == 'Risk'){
                        // $riskS=array();
												$result=$this->BusinessAdminModel->RiskCustomerService($res);
                        
                    }elseif($_GET['category'] == 'Lost'){
                            // $lostS=array(); 
                            $result=$this->BusinessAdminModel->LostCustomerService($res);
                            // if($data['lostS']['success']='true'){
                            //     $data['lostS']=$data['lostS']['res_arr'];
                            //     foreach($data['lostS'] as $key => $value){
                            //         array_push($lostS,$value['customer_id']);
                            //     }
                            // }else{
                            //     $data['lostS']=0;
                            // }
                            // $ReportRiskS=array();   
                            // for($i=0;$i<count($lostS);$i++){
                            //     $result = $this->BusinessAdminModel->ReportLostSCustomer($lostS[$i]);
                            //     array_push($ReportRiskS,$result['res_arr'][0]);
                            // }
                            // $result = array(
                            //     'success' => 'true',
                            //     'res_arr'   => $ReportRiskS
                            // );
                    }
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
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }
    }
	public function GetCustDataForTimelineSearch(){
        if($this->IsLoggedIn('business_admin')){
            $result = $this->BusinessAdminModel->SearchCustomerTimeline($_POST['customer_id']);
            // $result=0;
            // $this->PrettyPrintArray($result);
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
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }
    }
    public function IntegrateGoogle(){
		if($this->IsLoggedIn('business_admin')){
			if(isset($_POST) && !empty($_POST)){
				$where  = array(
					'business_admin_id' =>$this->session->userdata['logged_in']['business_admin_id'],
					'business_outlet_id'=> $this->session->userdata['outlets']['current_outlet'],
					'api_key' => $this->input->post('api_key'),
					'calendar_id' =>$this->input->post('calendarId')
				);
				$result = $this->BusinessAdminModel->Insert($where,'mss_appointment_integration');
				if($result['success'] == 'true'){
					$this->ReturnJsonArray(true,false,"Calendar Integrated successfully!");
					die;
				}
				elseif($result['error'] == 'true'){
					$this->ReturnJsonArray(false,true,$result['message']);
								die;
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}
	public function IntegrateFacebook(){
		if($this->IsLoggedIn('business_admin')){
			$data = $this->GetDataForAdmin("Appointment");
			$data['sidebar_collapsed'] = "true";
			$this->load->view('business_admin/ba_integrate_view', $data);
	   }
	   else{
		   $data['title'] = "Login";
		   $this->load->view('business_admin/ba_login_view',$data);
		}
	}
	//01-05-2020
	public function Tags(){
        if($this->IsLoggedIn('business_admin')){
            $data = $this->GetDataForAdmin("Customer");
            if(isset($data['selected_outlet']) || !empty($data['selected_outlet'])){
                $data['sidebar_collapsed'] = "true";
            }
            $where = array(
                'business_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
                'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id'],
            );
            $data['tags']=$this->BusinessAdminModel->GetTags();
            // $this->PrettyPrintArray($data['tags']);
            // exit;
            if($data['tags']['success'] == 'true'){
                $data['tags']=$data['tags']['res_arr'];
            }else{
                $data['tags']=0;
            }
            for($i=0;$i<count($data['tags']);$i++){
                $data['tags'][$i]+=['no_rules'=>'0'];
                $data['tags'][$i]+=['customers'=>'0'];
            }
            // $this->PrettyPrintArray($data['tags']);
            // die;
            $cust1=array();
            foreach($data['tags'] as $key => $value){
                // $this->PrettyPrintArray($value);
                if($value['and_or'] == 'OR'){
                    // echo "1";
                    $cust=array();
                    $where = array(
                        'tag_id' => $value['tag_id'],
                        'business_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
                        'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id'],
                    );
                    $data['rules']=$this->BusinessAdminModel->MultiWhereSelect('mss_tags_rule',$where);
                    if($data['rules']['success'] == 'true'){
                        $data['rules']=$data['rules']['res_arr'];
                    }
                    $data['tags'][$key]['no_rules']=count($data['rules']);
                    // $this->PrettyPrintArray($data['tags']);  
                    // exit;
                    foreach($data['rules'] as $k=>$v){
                        // $this->PrettyPrintArray($v);
                        $res=array(
                            'kpi'=>$v['kpi'],
                            'start_range'=>$v['start_range'],
                            'end_range'=>$v['end_range'],
                            'start_range_date'=>$v['start_range_date'],
                            'date'=>$v['date'],
                            'end_range_date'=>$v['end_range_date'],
                            'value'=>$v['value'],
                            'select_option'=>$v['select_option']
                        );
                        $result=$this->BusinessAdminModel->CalRuleCust($res);
                        if($result['success']=='true'){
                            for($i=0;$i<count($result['res_arr']);$i++){
                                array_push($cust,$result['res_arr'][$i]['cust_id']);
                            }
                        }
                    }
                    // $this->PrettyPrintArray($cust);
                    $data['tags'][$key]['customers']=count(array_unique($cust));
                }
                else{
                    $cust=array();
                    $cust_no=0;
                    // echo "0";
                    $succ=0;
                    $where = array(
                        'tag_id' => $value['tag_id'],
                        'business_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
                        'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id'],
                    );
                    $data['rules']=$this->BusinessAdminModel->MultiWhereSelect('mss_tags_rule',$where);
                    if($data['rules']['success'] == 'true'){
                        $data['rules']=$data['rules']['res_arr'];
                    }
                    $data['tags'][$key]['no_rules']=count($data['rules']);
                    // $this->PrettyPrintArray($data['tags']);  
                    // exit;
                    foreach($data['rules'] as $k=>$v){
                        // $this->PrettyPrintArray($v);die;
                        $res=array(
                            'kpi'=>$v['kpi'],
                            'start_range'=>$v['start_range'],
                            'end_range'=>$v['end_range'],
                            'start_range_date'=>$v['start_range_date'],
                            'date'=>$v['date'],
                            'end_range_date'=>$v['end_range_date'],
                            'value'=>$v['value'],
                            'select_option'=>$v['select_option']
                        );
                        $result=$this->BusinessAdminModel->CalRuleCust($res);
                        if($result['success']=='true'){
                            $cust_no = $cust_no + count($result['res_arr']);
                            for($i=0;$i<count($result['res_arr']);$i++){
                                array_push($cust,$result['res_arr'][$i]['cust_id']);
                            }
                            // $this->PrettyPrintArray($cust);  
                            // die;
                        }
                        if(count($cust1) == 0){
                            // array_push($cust1,$cust);
                            $cust1=$cust;
                            // $this->PrettyPrintArray($cust1);
                        }else{
                            $cust1=array_unique($cust);
                            // $this->PrettyPrintArray($cust);
                            // $cust=array(); 
                        }
                    }
                    if($data['tags'][$key]['no_rules'] == 1){
                        $data['tags'][$key]['customers']=count(array_unique($cust1));
                    }else{
                        $data['tags'][$key]['customers']=$cust_no-count(array_unique($cust1));
                    }
                }
            }
                // $this->PrettyPrintArray($data['tags']);
                // exit;
            $this->load->view('business_admin/ba_customer_tags_view',$data);
        }
        else{
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }
    }
    public function SetTags(){
        if($this->IsLoggedIn('business_admin')){
            $data = $this->GetDataForAdmin("Customer");
            if(isset($data['selected_outlet']) || !empty($data['selected_outlet'])){
                $data['sidebar_collapsed'] = "true";
            }
            $where = array(
                'business_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
                'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id'],
            );
            $this->load->view('business_admin/ba_set_rule_tag_view',$data);
        }
        else{
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }
    }
    public function CalculateRuleTag(){
        if($this->IsLoggedIn('business_admin')){
            // $this->PrettyPrintArray($_POST);
            // die;
            $cust=0;
            $kpi=$this->input->post('kpi[]');
            $start_range=$this->input->post('start_range[]');
            $end_range=$this->input->post('end_range[]');
            $date=$this->input->post('date[]');
            $start_range_date=$this->input->post('start_range_date[]');
            $end_range_date=$this->input->post('end_range_date[]');
            $pvalue=$this->input->post('value[]');
            foreach($kpi as $k=>$v){
                $res=array(
                    'kpi'=>$v['kpi'],
                    'start_range'=>$v['start_range'],
                    'end_range'=>$v['end_range'],
                    'start_range_date'=>$v['start_range_date'],
                    'date'=>$v['date'],
                    'end_range_date'=>$v['end_range_date'],
                    'value'=>$v['value'],
                    // 'select_option'=>$v['select_option']
                );
                // $this->PrettyPrintArray($res);
                // die;
                $result=$this->BusinessAdminModel->CalRuleCust($res);
                if($result['success']=='true'){
                    $cust = $cust + count($result['res_arr']);
                }
            }
                    $result=0;
                    if($result['success'] == 'true'){
                        $data = array(
                                    'success' => 'true',
                                    'error'   => 'false',
                                    'message' => 'Success',
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
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }
    }
    public function InsertRuleTag(){
        if($this->IsLoggedIn('business_admin')){
                        // $this->PrettyPrintArray($_POST);
                    $counter=$this->BusinessAdminModel->SelectCounterForTags();
                    if($counter['res_arr'][0]['counter']==''){
                        $counter=0;
                    }else{
                        $counter=$counter['res_arr'][0]['counter'];
                    }
                    // $this->PrettyPrintArray($counter);
                    $kpi=$this->input->post('kpi[]');
                    $start_range=$this->input->post('start_range[]');
                    $end_range=$this->input->post('end_range[]');
                    $date=$this->input->post('date[]');
                    $option=$this->input->post('option');
                    $start_range_date=$this->input->post('start_range_date[]');
                    $end_range_date=$this->input->post('end_range_date[]');
                    $pvalue=$this->input->post('value[]');
                    $select_option=$this->input->post('select_option[]');
            foreach($kpi as $key=>$value){
                $res=array(
                    'rule_name' =>$this->input->post('rule_name'),
                    'datetime'=>date('Y-m-d'),
                    'kpi'=>$kpi[$key],
                    'start_range'=>$start_range[$key],
                    'end_range'=>$end_range[$key],
                    'date'=>$date[$key],
                    'and_or'=>$option,
                    'start_range_date'=>$start_range_date[$key],
                    'end_range_date'=>$end_range_date[$key],
                    'select_option'=>$select_option[$key],
                    'value'=>$pvalue[$key],
                    'tag_id'=>$counter+1,
                    'business_outlet_id'=>$this->session->userdata['outlets']['current_outlet'],
                    'business_admin_id'=>$this->session->userdata['logged_in']['business_admin_id'],
										'master_admin_id'=>$this->session->userdata['logged_in']['master_admin_id'],
										'service_id'	=> $this->input->post('service_name')
                );
                $result = $this->BusinessAdminModel->Insert($res,'mss_tags_rule');
            }
            // $this->PrettyPrintArray($res);
                if($result['success'] == 'true'){
                        $data = array(
                                    'success' => 'true',
                                    'error'   => 'false',
                                    'message' => 'success',
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
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }
    }
    public function DeleteTag(){
        if($this->IsLoggedIn('business_admin')){
            $data=array(
                'tag_id' => $_GET['tag_id']
            );
            $result=$this->BusinessAdminModel->DeleteTagrules($data);
                // $result=0;
            // $this->PrettyPrintArray($result);
            if($result['success'] == 'true'){
                        $data = array(
                                    'success' => 'true',
                                    'error'   => 'false',
                                    'message' => 'Success',
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
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }
    }
    public function DownloadTag(){
        if($this->IsLoggedIn('business_admin')){
            $where = array(
                'tag_id' => $_GET['tag_id'],
                'business_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
                'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id'],
            );
            $data['rules']=$this->BusinessAdminModel->MultiWhereSelect('mss_tags_rule',$where);
            $data['rules']=$data['rules']['res_arr'];
            // $this->PrettyPrintArray(count($data['rules']));
            // die;
            $result['download']=array();
            $temp=array();
            $info=array();
            $temp1=array();
            foreach($data['rules'] as $key => $value){
                    $res=array(
                        'kpi'=>$value['kpi'],
                        'start_range'=>$value['start_range'],
                        'end_range'=>$value['end_range'],
                        'start_range_date'=>$value['start_range_date'],
                        'date'=>$value['date'],
                        'end_range_date'=>$value['end_range_date'],
                        'value'=>$value['value'],
                        'select_option'=>$value['select_option']
                    );
                    $res1=$this->BusinessAdminModel->DownloadTagRules($res);
                    $res1=$res1['res_arr'];
                    if($value['and_or'] == "AND"){
                        if(count($data['rules']) == 1){
                            // array_push($result['download'], $res1);
                            $result['download'] = array_merge($result['download'], $res1);
                        }else{
                            for($i=0;$i<count($res1);$i++){
                                if(in_array($res1[$i]['customer_mobile'],$temp1)){
                                    array_push($result['download'], $res1[$i]);
                                }else{
                                    array_push($temp1,$res1[$i]['customer_mobile']);
                                }
                            }
                        }
                        // $this->PrettyPrintArray($result);
                        // die;
                    }
                    else{
                        for($i=0;$i<count($res1);$i++){
                            if(in_array($res1[$i]['customer_mobile'],$info)){
                            }else{
                                array_push($temp,$res1[$i]);
                            }
                            array_push($info,$res1[$i]['customer_mobile']);
                            // array_push($temp,$info);
                        }
                        // $temp=array_flip($temp);
                        // $this->PrettyPrintArray($temp);
                        $result['download']=$temp;
                    }   
            }
            // die;
                        if(isset($result['download'])){
                                    $data = array(
                                    'success' => 'true',
                                    'error'   => 'false',
                                    'message' => 'Success',
                                    'result' =>  $result['download']
                        );
                        header("Content-type: application/json");
                        print(json_encode($data, JSON_PRETTY_PRINT));
                        die;    
                    }
                    elseif(!isset($result['download'])){
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
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }
    }
    public function EditTag(){
        if($this->IsLoggedIn('business_admin')){
            $data=array(
                'tag_id' => $_GET['tag_id']
            );
            $result=$this->BusinessAdminModel->EditTagRules($data);
                // $result=0;
            // $this->PrettyPrintArray($result);
            if($result['success'] == 'true'){
                        $data = array(
                                    'success' => 'true',
                                    'error'   => 'false',
                                    'message' => 'Success',
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
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }
    }
    //4-05-2020
	
	public function GetAllServices(){
        if($this->IsLoggedIn('business_admin')){
            // $this->PrettyPrintArray($_GET);
            $query=$_GET['query'];
            $data = $this->BusinessAdminModel->ServicesAll($query);
            if($data['success'] == 'true'){ 
                $this->ReturnJsonArray(true,false,$data['res_arr']);
            }
        }
        else{
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }       
    }
    public function AddRecommendedService(){
        if($this->IsLoggedIn('business_admin')){
            // $this->PrettyPrintArray($_POST);
            $data=array(
                'service_id' => $_POST['ServiceProductid'],
                'recommended_service_id'=> $_POST['RecommendedID'],
                'recommended_service' => $_POST['searchrecommended'], 
                'business_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
                'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id']
            );
            // $query=$_GET['query'];
            $data = $this->BusinessAdminModel->Insert($data,'mss_recommended_services');
            if($data['success'] == 'true'){ 
                $this->ReturnJsonArray(true,false,'Success',$data['res_arr']);
            }
        }
        else{
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }       
    }
    public function EditRecommendedService(){
        if($this->IsLoggedIn('business_admin')){
            $result = $this->BusinessAdminModel->EditRecommendeServiceProduct($_GET['rservice_id']);
            if($result['success'] == 'true'){
                $data = array(
                            'success' => 'true',
                            'error'   => 'false',
                            'message' => 'Success',
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
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }       
    }
    public function UpdateRecommendedService(){
        if($this->IsLoggedIn('business_admin')){
            // $this->PrettyPrintArray($_POST);
            $data=array(
                'id'    => $_POST['rid'],
                'service_id' => $_POST['editServiceProductid'],
                'recommended_service_id'=> $_POST['editRecommendedID'],
                'recommended_service' => $_POST['editsearchrecommended'], 
                'business_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
                'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id']
            );
            $result = $this->BusinessAdminModel->Update($data,'mss_recommended_services','id');
            // $this->PrettyPrintArray($data);
            if($result['success'] == 'true'){
                $data = array(
                            'success' => 'true',
                            'error'   => 'false',
                            'message' => 'Success',
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
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }       
    }
    public function DeleteRecomService(){
        if($this->IsLoggedIn('business_admin')){
            // $this->PrettyPrintArray($_POST);
            $data=array(
                'id'=>$_POST['rservice_id']
            );
            $result = $this->BusinessAdminModel->DeleteMultiple('mss_recommended_services',$data['id'],'id');
            if($result['success'] == 'true'){
                $data = array(
                            'success' => 'true',
                            'error'   => 'false',
                            'message' => 'Deleted SuccessFully',
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
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }       
    }
    //15-05-2020  Ranjeet Yadav
	public function GoogleReviews(){
		if($this->IsLoggedIn('business_admin')){
			$data = $this->GetDataForAdmin("Google Reviews");
			$this->load->view('business_admin/ba_google_reviews_view',$data);
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}
	//21-05-2020 Pritam
	public function InventoryHealth(){
        if($this->IsLoggedIn('business_admin')){
            $data = $this->GetDataForAdmin("Inventory Health");
            $where = array(
                'outlet_id'         => $this->session->userdata['outlets']['current_outlet'],
                'business_admin_id' =>$this->session->userdata['logged_in']['business_admin_id']
            );
            $data['stockvalue']=0;
            $data['stockdetails']=$this->BusinessAdminModel->StockDetails();
            if($data['stockdetails']['success'] == 'false'){
                $data['stockdetails']=array();
            }else{ 
                $data['stockdetails']=$data['stockdetails']['res_arr'];
                for($i=0;$i<count($data['stockdetails']);$i++){
                    $data['stockdetails'][$i]+=['deadstock'=>'0'];
                    $data['stockdetails'][$i]+=['entrydate'=>' '];
                    $data['stockdetails'][$i]+=['days'=>'0'];
                    $data['stockdetails'][$i]+=['Total'=>'0'];
                }
            }
            foreach($data['stockdetails'] as $key=>$value){
                $temp=$value['service_id'];
                $res=$this->BusinessAdminModel->StockDetailRegular($temp);
                // $this->PrettyPrintArray($res);
                if($res['success'] == 'true'){
                    // echo "hii";
                    $data['stockdetails'][$key]['deadstock']=$res['res_arr'][0]['dead_stock'];
                    $data['stockdetails'][$key]['entrydate']=($res['res_arr'][0]['entry_date']);
                    $data['stockdetails'][$key]['days']=$res['res_arr'][0]['days'];
                    $data['stockdetails'][$key]['Total']=$res['res_arr'][0]['Total Revenue'];
                }else{
                }
            }
            foreach($data['stockdetails'] as $k=>$v){
                $data['stockvalue']=$data['stockvalue']+$data['stockdetails'][$k]['Total'];
            }
            // $this->PrettyPrintArray($data['stockdetails']);die;
            $data['collapsed']="true";
            $this->load->view('business_admin/ba_inventory_health_view',$data);
        }   
        else{   
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }
    }
    public function InventoryStatus(){
        if($this->IsLoggedIn('business_admin')){
            if(isset($_GET) && !empty($_GET)){
                $data['total']=0;
                if($_GET['status'] == 'slow'){
                    $data['stock']='Slow';
                    $data['stockdetails']=$this->BusinessAdminModel->StockDetails();
                    if($data['stockdetails']['success'] == 'false'){
                        $data['stockdetails']=array();
                    }else{ 
                        $data['stockdetails']=$data['stockdetails']['res_arr'];
                        for($i=0;$i<count($data['stockdetails']);$i++){
                            $data['stockdetails'][$i]+=['deadstock'=>'0'];
                            $data['stockdetails'][$i]+=['entrydate'=>' '];
                            $data['stockdetails'][$i]+=['days'=>'0'];
                            $data['stockdetails'][$i]+=['Total'=>'0'];
                        }
                    }
                    foreach($data['stockdetails'] as $key=>$value){
                        $temp=$value['service_id'];
                        $res=$this->BusinessAdminModel->StockDetailSlow($temp);
                        // $this->PrettyPrintArray($res);
                        if($res['success'] == 'true'){
                            // echo "hii";
                            $data['stockdetails'][$key]['deadstock']=$res['res_arr'][0]['dead_stock'];
                            $data['stockdetails'][$key]['entrydate']=($res['res_arr'][0]['entry_date']);
                            $data['stockdetails'][$key]['days']=$res['res_arr'][0]['days'];
                            $data['stockdetails'][$key]['Total']=$res['res_arr'][0]['Total Revenue'];
                        }
                    }
                    foreach($data['stockdetails'] as $k=>$v){
                        $data['total']=$data['total']+$data['stockdetails'][$k]['Total'];
                    }
                }elseif($_GET['status'] == 'Dead'){
                    $data['stockdetails']=$this->BusinessAdminModel->StockDetails();
                    $data['stock']='Dead';
                    if($data['stockdetails']['success'] == 'false'){
                        $data['stockdetails']=array();
                    }else{ 
                        $data['stockdetails']=$data['stockdetails']['res_arr'];
                        for($i=0;$i<count($data['stockdetails']);$i++){
                            $data['stockdetails'][$i]+=['deadstock'=>'0'];
                            $data['stockdetails'][$i]+=['entrydate'=>' '];
                            $data['stockdetails'][$i]+=['days'=>'0'];
                            $data['stockdetails'][$i]+=['Total'=>'0'];
                        }
                    }
                    foreach($data['stockdetails'] as $key=>$value){
                        $temp=$value['service_id'];
                        $res=$this->BusinessAdminModel->StockDetailDead($temp);
                        // $this->PrettyPrintArray($res);
                        if($res['success'] == 'true'){
                            // echo "hii";
                            $data['stockdetails'][$key]['deadstock']=$res['res_arr'][0]['dead_stock'];
                            $data['stockdetails'][$key]['entrydate']=($res['res_arr'][0]['entry_date']);
                            $data['stockdetails'][$key]['days']=$res['res_arr'][0]['days'];
                            $data['stockdetails'][$key]['Total']=$res['res_arr'][0]['Total Revenue'];
                        }
                    }
                    foreach($data['stockdetails'] as $k=>$v){
                        $data['total']=$data['total']+$data['stockdetails'][$k]['Total'];
                    }
                }
                    // $this->PrettyPrintArray($data);die;
                    header("Content-type: application/json");
                    print(json_encode($data, JSON_PRETTY_PRINT));
                    die;
            }
        }
        else{
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }
    }
    
    public function GetServiceOtc(){
		if($this->IsLoggedIn('business_admin')){
			if(isset($_GET) && !empty($_GET)){
				// $this->PrettyPrintArray($_GET);die;
				$where = array(
					'category_business_admin_id'   => $this->session->userdata['logged_in']['business_admin_id'],
					'service_is_active'            => TRUE,
					'category_business_outlet_id'  => $this->session->userdata['outlets']['current_outlet'],
					'service_type'    			   => 'otc',
					'service_id'	=> $_GET['service_id']
				);
	
				$data = $this->BusinessAdminModel->ServicesOtc($where);
				// $this->PrettyPrintArray($data);
				header("Content-type: application/json");
				print(json_encode($data['res_arr'][0], JSON_PRETTY_PRINT));
				die;
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}
	}
    //26-05-2020
    	public function AddInventory(){
        if($this->IsLoggedIn('business_admin')){
          if(isset($_POST) && !empty($_POST)){
						$this->form_validation->set_rules('invoice_number','Invoice Number', 'trim|required');
						$this->form_validation->set_rules('invoice_date', 'Invoice Date', 'trim|required');			
						if($this->form_validation->run() == FALSE){
							$data = array(
															'success' => 'false',
															'error'   => 'true',
															'message' =>  validation_errors()
													);
							header("Content-type: application/json");
							print(json_encode($data, JSON_PRETTY_PRINT));
							die;
						}else{
								$this->db->trans_start();
								$data2=array(
									'invoice_number'    =>  $this->input->post('invoice_number'),
									'invoice_date'   		=>  $this->input->post('invoice_date'),
									'invoice_amount' 		=>  $this->input->post('invoice_amount'),
									'invoice_tax' 			=>  $this->input->post('invoice_tax'),
									'source'   					=>  $this->input->post('source_type'),
									'source_name'  			=>  $this->input->post('source_name'),
									'invoice_type'  		=>  $this->input->post('invoice_type'),
									'amount_paid'   		=>  $this->input->post('amount_paid'),
									'payment_type'   		=>  $this->input->post('payment_mode'),
									'payment_status' 		=>  $this->input->post('payment_status'),
									'notes' 							=>  $this->input->post('note'),
									'business_outlet_id'=>  $this->session->userdata['outlets']['current_outlet']
								);
								$result=$this->BusinessAdminModel->Insert($data2,'inventory');
								foreach($_POST['product_name'] as $key=>$val){
									$data3=array(
										'inventory_id'				=>$result['res_arr']['insert_id'],
										'service_id'					=>$_POST['product_id'][$key],
										'product_name'				=>$_POST['product_name'][$key],
										'product_type'				=>$_POST['product_type'][$key],
										'product_barcode'			=>$_POST['product_barcode'][$key],
										'sku_size'						=>$_POST['sku_size'][$key],
										'product_qty'					=>$_POST['product_qty'][$key],
										'product_price'				=>$_POST['product_price'][$key],
										'product_gst'					=>$_POST['product_gst'][$key],
										'product_mrp'					=>$_POST['product_mrp'][$key],
										'expiry_date'					=>$_POST['product_exp_date'][$key]
									);
									$this->BusinessAdminModel->Insert($data3,'inventory_data');
									$where=array(
										'stock_service_id' => $_POST['product_id'][$key],
										'stock_outlet_id'	=> $this->session->userdata['outlets']['current_outlet']
									);
									$data4=array(
										'stock_service_id' => $_POST['product_id'][$key],
										'total_stock'=> $_POST['product_qty'][$key],
										'stock_in_unit'	=>($_POST['product_qty'][$key]*$_POST['sku_size'][$key]),
										'stock_outlet_id'	=> $this->session->userdata['outlets']['current_outlet'],
										'updated_on'	=>date('Y-m-d')
									);
									$stock_exist= $this->CashierModel->CheckStockExist($where);
									if($stock_exist['success']=='true'){
										$update_stock=$this->CashierModel->UpdateInventoryStock($data4);
									}else{
										$insert_stock=$this->CashierModel->Insert($data4,'inventory_stock');
									}

									//making entry i expense table
									$outlet_id = $this->session->userdata['outlets']['current_outlet'];
									$admin_id= $this->session->userdata['logged_in']['business_admin_id'];
									$expense_counter = $this->db->select('*')->from('mss_business_outlets')->where('business_outlet_id',$outlet_id)->get()->row_array();			
          				$expense_unique_serial_id = strval("EA".strval(100+$admin_id) . "O" . strval($outlet_id) . "-" . strval($expense_counter['business_outlet_expense_counter']));
									$emp=$this->BusinessAdminModel->DetailsById($_POST['source_name'],'mss_vendors','vendor_id');

									$data5=array(
										'expense_unique_serial_id'	=>  $expense_unique_serial_id,
										'expense_date'							=>	date('Y-m-d'),
										'expense_type_id'						=>	'1',
										'item_name'									=>	'Inventory',
										'employee_name'							=>	$this->session->userdata['logged_in']['business_admin_name'],
										'total_amount'							=>	$this->input->post('invoice_amount'),
										'amount'										=>	$this->input->post('amount_paid'),
										'payment_type'							=>	$this->input->post('source_type'),
										'payment_to'								=>	$this->input->post('source_name'),
										'payment_to_name'						=>	$emp['res_arr']['vendor_name'],
										'invoice_number'						=>	$this->input->post('invoice_number'),
										'remarks'										=>	$this->input->post('note'),
										'payment_mode'							=>	$this->input->post('payment_mode'),
										'expense_status'						=>	$this->input->post('payment_status'),
										'pending_amount'						=>	($this->input->post('invoice_amount')- $this->input->post('amount_paid')),
										'bussiness_outlet_id'				=>	$this->session->userdata['outlets']['current_outlet']
									);
									$insert_expense=$this->CashierModel->Insert($data5,'mss_expenses');
									//Add Expense Unpaid E
									if($_POST['invoice_amount'] > $_POST['amount_paid']){
											$data1 = array(
												'expense_date'      => date('Y-m-d'),
												'expense_type_id'   => 1,
												'item_name'         => 'Inventory',
												'total_amount'      =>$this->input->post('invoice_amount'),
												'amount'            => $this->input->post('amount_paid'),
												'remarks'           => $this->input->post('note'),
												'payment_type'      => $this->input->post('source_type'),
												'payment_to'        => $this->input->post('source_name'),
												'payment_to_name'   => $emp['res_arr']['vendor_name'],
												'payment_mode'      => $this->input->post('payment_mode'),
												'pending_amount'    => ($this->input->post('invoice_amount')- $this->input->post('amount_paid')),
												'expense_status'    => $this->input->post('payment_status'),
												'employee_name'     => $this->session->userdata['logged_in']['business_admin_name'],
												'invoice_number'    => $this->input->post('invoice_number'),
												'bussiness_outlet_id'=>$this->session->userdata['outlets']['current_outlet']
											);				
										    $result = $this->BusinessAdminModel->Insert($data1,'mss_expenses_unpaid');
										}

									//
									$query = "UPDATE mss_business_outlets SET business_outlet_expense_counter = business_outlet_expense_counter + 1 WHERE business_outlet_id = ".$outlet_id."";
					
									$this->db->query($query);

								}
								$this->db->trans_complete();
								if ($this->db->trans_status() === FALSE){
									$this->ReturnJsonArray(false,true,"Error in Stock Addition!");
									die;
								}
								$this->ReturnJsonArray(true,false,"Inventory added successfully!");
								die;						
							}
            }
            else{                
							$data = $this->GetDataForAdmin('Add Inventory');
							$data['raw_materials'] = $this->GetRawMaterials($this->session->userdata['outlets']['current_outlet']);
							$data['otc_items'] = $this->GetOTCItems();
							$data['otc_stock']=$this->BusinessAdminModel->InventoryStock();
							if($data['otc_stock']['success'] == 'true'){
								$data['otc_stock']=$data['otc_stock']['res_arr'];
							}
							$where=array(
								'business_outlet_id'=>$this->session->userdata['outlets']['current_outlet']
							);
							$data['vendors']=$this->BusinessAdminModel->MultiWhereSelect('mss_vendors',$where);
											if($data['vendors']['success'] == 'true'){
													$data['vendors']=$data['vendors']['res_arr'];
							}

							$data['stock']=$this->CashierModel->AvailableStock($where);
							$data['stock']=	$data['stock']['res_arr'];
							// $this->PrettyPrintArray($data['stock']);
							$data['stock_incoming']=$this->CashierModel->IncomingStock($where);
							$data['stock_incoming']=	$data['stock_incoming']['res_arr'];
							$data['inventory_details']= $this->CashierModel->StockInventoryDetails($where);
							$data['inventory_details']=$data['inventory_details']['res_arr'];

							$data['stock_outgoing']=$this->CashierModel->OutgoingStock($where);
							$data['stock_outgoing']=	$data['stock_outgoing']['res_arr'];

							$data['all_expenses'] = $this->AllExpenses($this->session->userdata['outlets']['current_outlet']);
							$data['pending_payment']=$this->BusinessAdminModel->GetVendorPendingPayment();
              $data['pending_payment']=$data['pending_payment']['res_arr'];

                // $this->PrettyPrintArray($data['pending_payment']);
                // exit;
							$data['categories']  = $this->GetCategoriesOtc($this->session->userdata['outlets']['current_outlet']);
							$data['sub_categories']  = $this->GetSubCategories($this->session->userdata['outlets']['current_outlet']);
							$data['services']  = $this->GetServices($this->session->userdata['outlets']['current_outlet']);
							$data['raw_material_stock'] = $this->GetRawMaterialStock();
							$m = $this->uri->segment(3);
							if(isset($m))
							{
									$data['modal']=1;
							}
							else{
									$data['modal']=0;
							}

							//Inventory Health
							$data['stockvalue']=0;
							$data['stockdetails']=$this->BusinessAdminModel->StockDetails();
							if($data['stockdetails']['success'] == 'false'){
									$data['stockdetails']=array();
							}else{ 
									$data['stockdetails']=$data['stockdetails']['res_arr'];
									for($i=0;$i<count($data['stockdetails']);$i++){
											$data['stockdetails'][$i]+=['deadstock'=>'0'];
											$data['stockdetails'][$i]+=['entrydate'=>' '];
											$data['stockdetails'][$i]+=['days'=>'0'];
											$data['stockdetails'][$i]+=['Total'=>'0'];
									}
							}
							foreach($data['stockdetails'] as $key=>$value){
									$temp=$value['service_id'];
									$res=$this->BusinessAdminModel->StockDetailRegular($temp);
									if($res['success'] == 'true'){
											$data['stockdetails'][$key]['deadstock']=$res['res_arr'][0]['dead_stock'];
											$data['stockdetails'][$key]['entrydate']=($res['res_arr'][0]['entry_date']);
											$data['stockdetails'][$key]['days']=$res['res_arr'][0]['days'];
											$data['stockdetails'][$key]['Total']=$res['res_arr'][0]['Total Revenue'];
									}
							}
							foreach($data['stockdetails'] as $k=>$v){
									$data['stockvalue']=$data['stockvalue']+$data['stockdetails'][$k]['Total'];
							}

							//health end
							$data['sidebar_collapsed'] = "true";
							$this->load->view('business_admin/add_inventory_view',$data);
            }
        }
        else{
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }   
			}
	private function GetOTCItems(){
		if($this->IsLoggedIn('business_admin')){
			$where = array(
				'category_business_admin_id' => $this->session->userdata['logged_in']['business_admin_id'],
				'service_is_active'     => TRUE,
				'category_business_outlet_id'=> $this->session->userdata['outlets']['current_outlet'],
				'service_type' => 'otc'
			);

			$data = $this->BusinessAdminModel->Services($where);
			if($data['success'] == 'true'){	
				return $data['res_arr'];
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}		
	}
	private function GetCategoriesOtc($outlet_id){
        if($this->IsLoggedIn('business_admin')){
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
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }       
	}
	private function GetRawMaterialStock(){	
		if($this->IsLoggedIn('business_admin')){
			$where = array(
				'raw_material_business_admin_id' => $this->session->userdata['logged_in']['business_admin_id'],
				'raw_material_business_outlet_id'=> $this->session->userdata['outlets']['current_outlet']
			);
		
			$data = $this->CashierModel->GetCurrentRMStock($where);
			if($data['success'] == 'true'){	
				return $data['res_arr'];
				
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/");
		}		
	}

	public function AddOTCInventory(){
        if($this->IsLoggedIn('business_admin')){
            if(isset($_POST) && !empty($_POST)){
				// $this->PrettyPrintArray($_POST);die;
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
                        $product_type = $this->CashierModel->DetailsById($service_details['service_sub_category_id'],'mss_sub_categories','sub_category_id');
                        $product_type=$product_type['res_arr']['sub_category_name'];
                        $outlet_counter = $this->CashierModel->DetailsById($this->session->userdata['outlets']['current_outlet'],'mss_inventory_transaction','outlet_id');
                        $outlet_counter=count($outlet_counter)-2;
                        $where = array(
                            'service_name'  => $this->input->post('otc_item'),
                            'sku_size' => $this->input->post('sku_size'),
                            'service_id'=>$this->input->post('service_id')
                        );
                        $data2=array(
                            'txn_id'    =>  strval("I".strval(100+((int)$this->session->userdata['logged_in']['business_admin_id'])) . "O" . strval($this->session->userdata['outlets']['current_outlet']) . "-" . strval($outlet_counter)),
                            'master_admin_id'   =>$master_admin_id,
                            'business_admin_id' => $this->session->userdata['logged_in']['business_admin_id'],
                            'outlet_id' => $this->session->userdata['outlets']['current_outlet'],
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
							'business_outlet_id'=>$this->session->userdata['outlets']['current_outlet'],
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
							'outlet_id'=>$this->session->userdata['outlets']['current_outlet']
						);
						$da=$this->BusinessAdminModel->SelectMaxIdExpense($datam);
						$da=$da['res_arr'][0]['id'];
						$vendor_name=$this->BusinessAdminModel->DetailsById($this->input->post('ivendors'),'mss_vendors','vendor_id');
						$vendor_name=$vendor_name['res_arr'];
						// $this->PrettyPrintArray($_SESSION);
						// die;
						$expense_id= "E1010Y".$this->session->userdata['outlets']['current_outlet'].($da+1);
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
								'employee_name'     => $this->session->userdata['logged_in']['business_admin_name'],
								'invoice_number'    => $this->input->post('iinvoice_no'),
								'bussiness_outlet_id' =>$this->session->userdata['outlets']['current_outlet']
							);
				// 			$this->CashierModel->Insert($data5,'mss_expenses');
                        $OTCstockexistsnew = $this->CashierModel->CheckOTCStockExist($where);
                        $OTCstockexists = $this->CashierModel->CheckOTCStockExists($where);
                        if($OTCstockexistsnew['success'] == 'false' && $OTCstockexistsnew['error'] == 'true' ){
                            $data = array(
                                'service_id'             => $this->input->post('service_id'),
                                'master_admin_id'   => $master_admin_id,
                                'business_admin_id' => $this->session->userdata['logged_in']['business_admin_id'],
                                'outlet_id' => $this->session->userdata['outlets']['current_outlet'],
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
                            //Update the Stock of OTC
                            // $expiry=$_POST['month']."-".$_POST['year'];
                            // $this->PrettyPrintArray($expiry);
                            // $abc=strval($year.'-'.$month);
                            // $this->PrettyPrintArray($_POST['expiry']);
                            $data = array(
                                'service_id'             => $this->input->post('service_id'),
                                'master_admin_id'   => $master_admin_id,
                                'business_admin_id' => $this->session->userdata['logged_in']['business_admin_id'],
                                'outlet_id' => $this->session->userdata['outlets']['current_outlet'],
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
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }
	}
	public function GetProductData(){
        if($this->IsLoggedIn('business_admin')){
            if(isset($_GET) && !empty($_GET)){
                // $this->PrettyPrintArray($_GET);die;
                $data = $this->CashierModel->SearchProduct($_GET['query'],$_GET['inventory_type'],$this->session->userdata['logged_in']['business_admin_id'],$this->session->userdata['outlets']['current_outlet']);
                if($data['success'] == 'true'){ 
                    $this->ReturnJsonArray(true,false,$data['res_arr']);
                    die;
                }
            }
        }
        else{
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }
	}
	
	public function GetCategoriesByInventory(){
        if($this->IsLoggedIn('business_admin')){
            $type="";
            if($_GET['inventory_type'] == "Retail Product"){
                $type="Retail Products";
            }else{
                $type="Raw Material";
            }
            $where = array(
                'category_business_admin_id' => $this->session->userdata['logged_in']['business_admin_id'],
                'category_is_active'         => TRUE,
                'category_business_outlet_id'=> $this->session->userdata['outlets']['current_outlet'],
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
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }       
	}
	public function GetProductDetails(){
        if($this->IsLoggedIn('business_admin')){
            if(isset($_GET) && !empty($_GET)){
                // $this->PrettyPrintArray($_GET);
                $data = $this->CashierModel->ProductDetails($_GET['product'],$this->session->userdata['logged_in']['business_admin_id'],$this->session->userdata['outlets']['current_outlet']);
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
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }
	}
	public function GetProductDetail(){
        if($this->IsLoggedIn('business_admin')){
            if(isset($_GET) && !empty($_GET)){
                $data = $this->CashierModel->ProductDetails($_GET['product'],$this->session->userdata['logged_in']['business_admin_id'],$this->session->userdata['outlets']['current_outlet']);
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
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }
	}
	public function GetProduct(){
        if($this->IsLoggedIn('business_admin')){
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
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }
	}

	// 23-05
	public function BulkUploadInventory(){
        if($this->IsLoggedIn('business_admin')){
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
                            'service_id' => $row[0],
                            'master_admin_id'      => $row[1],
                             'business_admin_id'      => $row[2],
                             'outlet_id'   => $row[3],
                             'barcode_id'   => $row[4],
                             'barcode'   => $row[5],
                             'brand_name'     => $row[6],
                             'product_type' => $row[7],
                             'sku_size'     => $row[8],
                             'unit'  => $row[9],
                             'mrp'     => $row[10],
                             'sku_count'   => $row[11],
                             'stock_level'   => $row[12],
                             'usg_category'   => $row[13],
                             'expiry'  => $row[14],
                             'low_stock_level'  => $row[15],
                             
                        );
											$result = $this->BusinessAdminModel->Insert($data,'mss_inventory');
											if($result['success'] == 'true'){
												$successInserts++;
											}elseif($result['error'] == 'true'){
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
            $this->LogoutUrl(base_url()."BusinessAdmin/Login");
        }
	}
	//29-05-2020
    public function GetSalaryEmployee(){
        if($this->IsLoggedIn('business_admin')){
            if(isset($_GET) && !empty($_GET)){
                if($_GET['month'] < 10){
                    $month="0".$_GET['month']."-".$_GET['year'];
                }else{
                    $month=$_GET['month']."-".$_GET['year'];
                }   
                    $data=$this->BusinessAdminModel->GetEmployeeSalary($month);
                    if($data['success'] == 'true'){
                        $data=$data['res_arr'];
                    }else{
                        $data=array();
                    }
                    // $this->PrettyPrintArray($data);
                    // die;
                    $data1 = array(
                                'success' => 'true',
                                'error'   => 'false',
                                'message' => '',
                                'result' =>  $data
                    );
                    header("Content-type: application/json");
                    print(json_encode($data1, JSON_PRETTY_PRINT));
                    die;    
                }
        }
        else{
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }
    }
    // Print Salary
    public function PrintSalary(){
        $this->load->helper('pdfhelper');//loading pdf helper
        if($this->IsLoggedIn('business_admin')){    
            $expert_id = $this->uri->segment(3);
            $month=$this->uri->segment(4);
            // $this->PrettyPrintArray($month);
            // die;
            if(isset($expert_id)){
                $where=array(
                    'expert_id'=>$expert_id,
                    'month'=>$month
                );
                $data['business_outlet_details'] = $this->BusinessAdminModel->DetailsById($this->session->userdata['outlets']['current_outlet'],'mss_business_outlets','business_outlet_id');
                if($data['business_outlet_details']['success'] == 'true'){
                    $data['business_outlet_details']=$data['business_outlet_details']['res_arr'];
                }else{
                    $data['business_outlet_details']=array();
                }
                $data['salary'] = $this->BusinessAdminModel->VerifyEmployee($where);
                if($data['salary']['success'] == 'true'){
                    $data['salary']=$data['salary']['res_arr'];
                }else{
                    $data['salary']=array();
                }
                $data['employee'] = $this->BusinessAdminModel->DetailsById($expert_id,'mss_employees','employee_id');
                $data['employee']=$data['employee']['res_arr'];
                // $this->PrettyPrintArray($data);die;
                $this->load->view('business_admin/ba_employee_print_bill',$data);
            }
            else{
                $data = array(
                    'heading' => "Illegal Access!",
                    'message' => "Employee details/ID missing. Please do not change url!"
                );
                $this->load->view('errors/html/error_general',$data);
            }   
        }
        else{
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }   
		}
		

		private function GetRawMaterialsIn($outlet_id){
			if($this->IsLoggedIn('business_admin')){
					$where = array(
							'business_admin_id' => $this->session->userdata['logged_in']['business_admin_id'],
							'is_active'         => TRUE,
							'business_outlet_id'=> $outlet_id
					);
					$data = $this->BusinessAdminModel->GetRawMaterialsIn($where);
					if($data['success'] == 'true'){ 
							return $data['res_arr'];
					}
			}
			else{
					$this->LogoutUrl(base_url()."BusinessAdmin/");
			}       
	}

	public function ReSendBillSms($customer_name, $mobile, $outlet_name, $bill_amt, $sender_id, $api_key, $bill_url){
		if($this->IsLoggedIn('business_admin')){
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

	public function ReSendPackageBillSms($customer_name, $mobile, $package_name, $package_validity, $sender_id, $api_key){
		if($this->IsLoggedIn('business_admin')){
			// $bill_url = $this->session->userdata('bill_url');
			// error_log("URL ============1 ".$bill_url);
			
			$msg = "Dear ".$customer_name.", Thanks for Visiting. You've bought ".$package_name." Package Valid for  ".$package_validity." months. Look forward to serve you again!.";
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
			$this->LogoutUrl(base_url()."BusinessAdmin/Login");
		}				
	}


	public function RePrintBill(){
		$this->load->helper('pdfhelper');//loading pdf helper
		if($this->IsLoggedIn('business_admin')){	
			$txn_id = $this->uri->segment(3);
			$outlet_admin_id = $this->session->userdata['outlets']['current_outlet'];
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
			$this->load->view('business_admin/ba_print_bill',$data);			
		}else{
			$this->LogoutUrl(base_url()."BusinessAdmin/Login");
		}	
	}

	private function ShopDetails(){
		if($this->IsLoggedIn('business_admin')){
			$where = array(
				'business_outlet_business_admin' => $this->session->userdata['logged_in']['business_admin_id'],
				'business_outlet_id'=> $this->session->userdata['outlets']['current_outlet'],
			);

			$data = $this->CashierModel->DetailsById($where['business_outlet_id'],'mss_business_outlets','business_outlet_id');
			if($data['success'] == 'true'){	
				return $data['res_arr'];
			}
		}
		else{
			$this->LogoutUrl(base_url()."BusinessAdmin/Login");
		}		
	}

	public function AddNewInventory(){
		if($this->IsLoggedIn('business_admin')){
				if(isset($_POST) && !empty($_POST)){
						$this->form_validation->set_rules('otc_item_name', 'otc Item Name', 'trim|required|max_length[100]');
						$this->form_validation->set_rules('otc_brand', 'Otc brand', 'trim|required|max_length[100]');
						$this->form_validation->set_rules('otc_unit', 'Otc unit', 'trim|required|max_length[50]');
						$this->form_validation->set_rules('otc_gst_percentage', 'OTC GST Percentage', 'trim|required');
						$this->form_validation->set_rules('otc_price_inr', 'OTC Gross Price', 'trim|required');
						$this->form_validation->set_rules('otc_inventory_type', 'Inventory Type', 'trim|required');
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
							$service_id= $this->input->post('otc_service_id');
							$inventory_detail=$this->BusinessAdminModel->GetInventoryDetail($service_id);
							$inventory_detail=$inventory_detail['res_arr'][0];
							unset($inventory_detail['id']);
							$inventory_detail['sku_count']= ($this->input->post('total_stock')-$this->input->post('current_stock'));
							$inventory_detail['entry_date']= date('Y-m-d');
							$inventory_detail['stock_level']= $this->input->post('total_stock');
							// $this->PrettyPrintArray($inventory_detail);

								$result = $this->BusinessAdminModel->Insert($inventory_detail,'mss_inventory');
								
								if($result['success'] == 'true'){
										$this->ReturnJsonArray(true,false,"Inventory updated successfully!");
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
				$this->LogoutUrl(base_url()."BusinessAdmin/");
		}   
}

public function daybook(){        
    if(!$this->IsLoggedIn('business_admin')){
        $this->LogoutUrl(base_url()."BusinessAdmin/");
    }
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


                // $p_mode = [];
                // foreach ($key_info as $key => $k) {
                //     foreach ($k as $key => $keys) {
                //         if(!in_array($keys, $p_mode)){
                //             $p_mode[] = $keys;
                //         }
                //     }                                    
                // }

        }
        
        // $p_mode = array_filter($p_mode);        
        // $p_mode = call_user_func_array('array_merge', $p_mode);
        // $p_mode = array_unique($p_mode);
        // $p_mode = array_values($p_mode);        
        // $data['p_mode'] = $p_mode;
        // $data['opening_balance_data'] = $opening_balance_data;
        // $data['pending_amount_data'] = $pending_amount_data;
        // $data['expenses_data'] = $expenses_data;
        // $data['transaction_data'] = $transaction_data;
        // $data['date'] = $date;
        $result = $this->BusinessAdminModel->GetPaymentMode();
        if($result['success']){
            $mode = $result['res_arr'];
            
            foreach ($mode as $key => $value) {
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
        }
        $data['payment_type_arr'] = $temp;       
        $result = $this->BusinessAdminModel->getOpeningRecord($date,1);        

        $cashin = [];
        $cashout = [];
        $temp1 = [];
        $temp2 = [];
        if($result['success']){
            $cashinout = $result['res_arr']['opening_balance'];

            foreach ($cashinout as $key => $value) {
                if($value['amount_data'] == 'CashIn'){
                    if(in_array(strtolower($value['payment_mode']), $temp1)){
                        $cashin[strtolower($value['payment_mode'])] += $value['amount'];
                        }else{
                            $cashin[strtolower($value['payment_mode'])] = $value['amount'];
                            $temp1[] = strtolower($value['payment_mode']);
                        }  
                    }elseif($value['amount_data'] == 'CashOut'){
                        if(in_array(strtolower($value['payment_mode']), $temp2)){
                        $cashout[strtolower($value['payment_mode'])] += $value['amount'];
                        }else{
                            $cashout[strtolower($value['payment_mode'])] = $value['amount'];
                            $temp2[] = strtolower($value['payment_mode']);
                        }
                    }               
            }           
        }        
        $key_info['keys'][4] = $temp1;
        $key_info['keys'][5] = $temp2;
        $p_mode = [];
        foreach ($key_info as $key => $k) {
            foreach ($k as $key => $keys) {
                if(!in_array($keys, $p_mode)){
                    $p_mode[] = $keys;
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
        $data['cashin'] = $cashin;
        $data['cashout'] = $cashout;
        $this->load->view('business_admin/ba_expense_view',$data);
    }

    public function cashbook(){  
        if(!$this->IsLoggedIn('business_admin')){
            $this->LogoutUrl(base_url()."BusinessAdmin/");
        }      
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
        $this->load->view('business_admin/ba_cash_book_view',$data);
		}
		
		public function GetPackage(){
			if($this->IsLoggedIn('business_admin')){
				if(isset($_GET) && !empty($_GET)){
					$where = array(
						'salon_package_id'   => $_GET['salon_package_id']
					);	
					$data = $this->BusinessAdminModel->GetPackageDetails($where);
					header("Content-type: application/json");
					print(json_encode($data['res_arr'][0], JSON_PRETTY_PRINT));
					die;
				}
			}
			else{
				$this->LogoutUrl(base_url()."BusinessAdmin/");
			}
		}

		public function EditPackage(){
			if($this->IsLoggedIn('business_admin')){
				if(isset($_POST) && !empty($_POST)){
					$this->form_validation->set_rules('salon_package_name', 'Package Name', 'trim|required|max_length[50]');
					$this->form_validation->set_rules('salon_package_price', 'Package Price', 'trim|required');
					$this->form_validation->set_rules('salon_package_gst', 'Package GST', 'trim|required');
					$this->form_validation->set_rules('salon_package_upfront_amt', 'Upfront Amount', 'trim|required');
					$this->form_validation->set_rules('salon_package_validity', 'Validity', 'trim|required|is_natural_no_zero');
					$this->form_validation->set_rules('salon_package_type', 'Package Type', 'trim|required|max_length[50]');
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
							'salon_package_name' => $this->input->post('salon_package_name'),
							'salon_package_price' => $this->input->post('salon_package_price'),
							'service_gst_percentage' => $this->input->post('salon_package_gst'),
							'salon_package_upfront_amt' => $this->input->post('salon_package_upfront_amt'),
							'salon_package_validity'=> $this->input->post('salon_package_validity'),
							'salon_package_type' 	=> $this->input->post('salon_package_type'),
							'business_admin_id' => $this->session->userdata['logged_in']['business_admin_id'],
							'business_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
							'salon_package_id'	=> $this->input->post('salon_package_id')
						);
						if($data['salon_package_type'] == "Discount"){	
							$services = $this->input->post('service_id');
							$discounts =  $this->input->post('discount');
							$counts = $this->input->post('count_discount');
							$salon_package_id = $this->input->post('salon_package_id');
							// $this->PrettyPrintArray($services);
							if(!empty($services) && !empty($discounts) && !empty($counts) && (count($services) == count($counts)) && (count($counts) == count($discounts))){
								$result = $this->BusinessAdminModel->UpdateDiscountPackageForSalon($data,$services,$discounts,$counts,$salon_package_id);
								if($result['success'] == 'true'){
									$this->ReturnJsonArray(true,false,"Package updated successfully!");
									die;
								}
								elseif($result['error'] == 'true'){
									$this->ReturnJsonArray(false,true,$result['message']);
									die;
								}
							}
							else{
								$this->ReturnJsonArray(false,true,"Wrong way of data filling!");
								die;
							}
						}
					}
				}
				else{
					$this->ReturnJsonArray(false,true,"Error in Package Update");
					die;
				}
			}
			else{
				$this->LogoutUrl(base_url()."BusinessAdmin/");
			}
		}

		public function EditServicePackage(){
			if($this->IsLoggedIn('business_admin')){
				if(isset($_POST) && !empty($_POST)){
					$this->form_validation->set_rules('salon_package_name', 'Package Name', 'trim|required|max_length[50]');
					$this->form_validation->set_rules('salon_package_price', 'Package Price', 'trim|required');
					$this->form_validation->set_rules('salon_package_gst', 'Package GST', 'trim|required');
					$this->form_validation->set_rules('salon_package_upfront_amt', 'Upfront Amount', 'trim|required');
					$this->form_validation->set_rules('salon_package_validity', 'Validity', 'trim|required|is_natural_no_zero');
					$this->form_validation->set_rules('salon_package_type', 'Package Type', 'trim|required|max_length[50]');
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
							'salon_package_name' => $this->input->post('salon_package_name'),
							'salon_package_price' => $this->input->post('salon_package_price'),
							'service_gst_percentage' => $this->input->post('salon_package_gst'),
							'salon_package_upfront_amt' => $this->input->post('salon_package_upfront_amt'),
							'salon_package_validity'=> $this->input->post('salon_package_validity'),
							'salon_package_type' 	=> $this->input->post('salon_package_type'),
							'business_admin_id' => $this->session->userdata['logged_in']['business_admin_id'],
							'business_outlet_id' => $this->session->userdata['outlets']['current_outlet'],
							'salon_package_id'	=> $this->input->post('salon_package_id')
						);
						if($data['salon_package_type'] == "Services"){
							$services = $this->input->post('service_id');
							$counts = $this->input->post('count_service');
							if(!empty($services) && !empty($counts) && (count($services) == count($counts))){
								$result = $this->BusinessAdminModel->EditServicePackageForSalon($data,$services,$counts);
								if($result['success'] == 'true'){
									$this->ReturnJsonArray(true,false,"Package updated successfully!");
									die;
								}
								elseif($result['error'] == 'true'){
									$this->ReturnJsonArray(false,true,$result['message']);
									die;
								}
							}
							else{
								$this->ReturnJsonArray(false,true,"Wrong way of data filling!");
								die;
							}
						}
					}
				}
				else{
					$this->ReturnJsonArray(false,true,"Error in Package Update");
					die;
				}
			}
			else{
				$this->LogoutUrl(base_url()."BusinessAdmin/");
			}
		}
	

		public function TransferInventory(){
			if($this->IsLoggedIn('business_admin')){
				if(isset($_POST) && !empty($_POST)){
					$this->form_validation->set_rules('invoice_number','Invoice Number', 'trim|required');
					$this->form_validation->set_rules('invoice_date', 'Date', 'trim|required');
			
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
						$this->db->trans_start();
						$data2=array(
								'invoice_number'    =>  $this->input->post('invoice_number'),
								'invoice_date'   		=>  $this->input->post('invoice_date'),
								'invoice_amount' 		=>  $this->input->post('invoice_amount'),
								'invoice_tax' 			=>  $this->input->post('invoice_tax'),
								'destination'   		=>  $this->input->post('destination_type'),
								'destination_name'  =>  $this->input->post('destination_name'),
								'invoice_type'  		=>  $this->input->post('invoice_type'),
								'amount_paid'   		=>  $this->input->post('amount_paid'),
								'payment_type'   		=>  $this->input->post('payment_mode'),
								'payment_status' 		=>  $this->input->post('payment_status'),
								'notes' 							=>  $this->input->post('note'),
								'business_outlet_id'=>  $this->session->userdata['outlets']['current_outlet']
						);
						$result=$this->CashierModel->Insert($data2,'inventory_transfer');
						foreach($_POST['product_name'] as $key=>$val){
							$data3=array(
								'inventory_transfer_id'				=>$result['res_arr']['insert_id'],
								'service_id'					=>$_POST['product_id'][$key],
								'product_name'				=>$_POST['product_name'][$key],
								'product_type'				=>$_POST['product_type'][$key],
								'product_barcode'			=>$_POST['product_barcode'][$key],
								'sku_size'						=>$_POST['sku_size'][$key],
								'product_qty'					=>$_POST['product_qty'][$key],
								'product_price'				=>$_POST['product_price'][$key],
								'product_gst'					=>$_POST['product_gst'][$key],
								'product_mrp'					=>$_POST['product_mrp'][$key],
								'expiry_date'					=>$_POST['product_exp_date'][$key],
								'transfer_status'			=>0
							);
							
							$data4=array(
								'stock_service_id' => $_POST['product_id'][$key],
								'total_stock'=> $_POST['product_qty'][$key],
								'stock_in_unit'  =>	($_POST['product_qty'][$key]*$_POST['sku_size'][$key]),
								'stock_outlet_id'	=> $this->session->userdata['outlets']['current_outlet'],
								'updated_on'	=>date('Y-m-d')
							);
	
							$stock_exist_for_transfer= $this->CashierModel->CheckStockExistForTransfer($data4);
							if($stock_exist_for_transfer['success']=='true'){
								$this->CashierModel->Insert($data3,'inventory_transfer_data');							
							}else{
								$this->ReturnJsonArray(false,true,"Stock not available for transfer!");
								die;							
							}
						}
						$this->db->trans_complete();
							if ($this->db->trans_status() === FALSE){
								$this->ReturnJsonArray(false,true,"Stock not available for transfer!");
								die;
							}
						$this->ReturnJsonArray(true,false,"Inventory Transfered successfully!");
						die;						
					}
				}else{
					$this->ReturnJsonArray(false,true,"Wrong Method!");
					die;
				}
			}
			else{
					$this->LogoutUrl(base_url()."BusinessAdmin/");
			}
		}
	
		public function TransferFinalInventory(){
			if($this->IsLoggedIn('business_admin')){
				if(isset($_POST) && !empty($_POST)){
					$where=array(
						'stock_service_id' => $_POST['service_id'],
						'stock_outlet_id'	=> $this->session->userdata['outlets']['current_outlet']
					);
					$data=array(
						'stock_service_id' => $_POST['service_id'],
						'total_stock'=> $_POST['total_stock'],
						'stock_in_unit'=>($_POST['stock_in_unit']*$_POST['total_stock']),
						'stock_outlet_id'	=> $this->session->userdata['outlets']['current_outlet'],
						'updated_on'	=>date('Y-m-d')
					);

					$data2=array(
						'stock_service_id' => $_POST['service_id'],
						'total_stock'=> $_POST['total_stock'],
						'stock_in_unit'=>($_POST['stock_in_unit']*$_POST['total_stock']),
						'stock_outlet_id'	=> $_POST['sender_outlet_id'],
						'updated_on'	=>date('Y-m-d')
					);
					$status=array(
						'transfer_status'=>1,
						'inventory_transfer_data_id'=>$_POST['transfer_data_id']
					);
					$stock_exist= $this->CashierModel->CheckStockExist($where);
					if($stock_exist['success']=='true'){
						$update_stock=$this->CashierModel->UpdateInventoryStock($data);
					}else{
						$insert_stock=$this->CashierModel->Insert($data,'inventory_stock');
					}
					$res=$this->CashierModel->Update($status,'inventory_transfer_data','inventory_transfer_data_id');
					$update_sender_stock=$this->CashierModel->UpdateSenderInventoryStock($data2);
				}
				$this->ReturnJsonArray(true,false,"Inventory added successfully!");
				die;						
			}
			else{
					$this->LogoutUrl(base_url()."BusinessAdmin/");
			}
		}
	
		public function RejectTransferInventory(){
			if($this->IsLoggedIn('business_admin')){
				if(isset($_POST) && !empty($_POST)){
					$status=array(
						'transfer_status'=>2,
						'inventory_transfer_data_id'=>$_POST['transfer_data_id']
					);
					$res=$this->CashierModel->Update($status,'inventory_transfer_data','inventory_transfer_data_id');
				}
				$this->ReturnJsonArray(true,false,"Inventory Rejected successfully!");
				die;						
			}
			else{
					$this->LogoutUrl(base_url()."BusinessAdmin/");
			}
		}
	
		public function GetBranchAndVendor(){
			if($this->IsLoggedIn('business_admin')){
				if(isset($_GET) && !empty($_GET)){
					$source_type=$_GET['source_type'];
					if($source_type=='warehouse'){
						$data['res_arr'][0]=array('source_id'=>1,'source_name'=>'warehouse');
						$this->ReturnJsonArray(true,false,$data['res_arr']);
						die;
					}else if($source_type=='branch'){
						$where=array(
							'business_outlet_business_admin' => $this->session->userdata['logged_in']['business_admin_id'],
							'business_outlet_status'=>1
						);
						$data['outlets']=$this->BusinessAdminModel->MultiWhereSelect('mss_business_outlets',$where);
						// $this->PrettyPrintArray($data);
						$data['res_arr1']=array();
						$temp=array();
						for($i=0;$i<count($data['outlets']['res_arr']);$i++){
							$temp+=['source_id'=>$data['outlets']['res_arr'][$i]['business_outlet_id'],'source_name'=>$data['outlets']['res_arr'][$i]['business_outlet_name']];							
							array_push($data['res_arr1'],$temp);
							$temp=[];
						}
						$this->ReturnJsonArray(true,false,$data['res_arr1']);
						die;
					}else if($source_type=='vendor'){
						$where=array(
							'business_outlet_id'=>$this->session->userdata['outlets']['current_outlet']
						);
						$data['vendors']=$this->BusinessAdminModel->MultiWhereSelect('mss_vendors',$where);
						$data['res_arr']=array();	
						$temp=array();				
						if($data['vendors']['success'] == 'true'){
							for($i=0;$i<count($data['vendors']['res_arr']);$i++){
								$temp+=['source_id'=>$data['vendors']['res_arr'][$i]['vendor_id'],'source_name'=>$data['vendors']['res_arr'][$i]['vendor_name']];							
								array_push($data['res_arr'],$temp);
								$temp=[];
							}
	
							$this->ReturnJsonArray(true,false,$data['res_arr']);
							die;
						}else{
							$data['res_arr']='';
						}
						$this->ReturnJsonArray(true,false,$data['res_arr']);
						die;
					}else{
						$data['res_arr'][0]=array('source_id'=>1,'source_name'=>'sales_return');
						$this->ReturnJsonArray(true,false,$data['res_arr']);
						die;
					}
				}else{
					$this->ReturnJsonArray(false,true,"Wrong Method!");
					die;
				}						
			}
			else{
					$this->LogoutUrl(base_url()."BusinessAdmin/");
			}
		}

		public function EditInventory(){
			if($this->IsLoggedIn('business_admin')){
				if(isset($_GET) && !empty($_GET)){
					$service_details=$this->CashierModel->DetailsById($_GET['service_id'],'mss_services','service_id');
					$service_details=$service_details['res_arr'];
					$this->ReturnJsonArray(true,false,$service_details);
					die;
				}else if(isset($_POST) && !empty($_POST)){
					$service_details=$this->CashierModel->DetailsById($_POST['service_id'],'mss_services','service_id');
					$service_details=$service_details['res_arr'];
					$data=array(
						'invoice_number'	=>	$_POST['invoice_number'],
						'invoice_date'		=>	$_POST['invoice_date'],
						'invoice_amount'	=>	$_POST['product_mrp'],
						'invoice_tax'			=>	0,
						'source'					=>	'branch',
						'source_name'			=>	$this->session->userdata['outlets']['current_outlet'],
						'invoice_type'		=>	'challan',
						'amount_paid'			=>	0,
						'payment_type'		=>	'',
						'payment_status'	=>	'Unpaid',
						'notes'						=>	$_POST['remarks'],
						'business_outlet_id'	=> $this->session->userdata['outlets']['current_outlet']
					);

					$result=$this->CashierModel->Insert($data,'inventory');
					
					$data2=array(
						'inventory_id'	=>	$result['res_arr']['insert_id'],
						'service_id'	=>	$_POST['service_id'],
						'product_name'	=>	$_POST['product_name'],
						'product_type'	=> $service_details['inventory_type'],
						'product_barcode'	=> $service_details['barcode'],
						'sku_size'	=>	$_POST['sku_size'],
						'product_qty'	=> $_POST['product_qty'],
						'product_price'	=> $_POST['product_price'],
						'product_gst'	=> $_POST['product_gst'],
						'product_mrp'	=> $_POST['product_mrp'],
						'expiry_date'	=> date('Y-m-d',strtotime('+ 1 year', strtotime(date('Y-m-d'))))
					);
					$result=$this->CashierModel->Insert($data2,'inventory_data');
					$where=array(
						'stock_service_id' => $_POST['service_id'],
						'stock_outlet_id'	=> $this->session->userdata['outlets']['current_outlet']
					);
					$data3=array(
						'stock_service_id'=>$_POST['service_id'],
						'total_stock'	=>$_POST['product_qty'],
						'stock_in_unit'	=>($_POST['product_qty']*$_POST['sku_size']),
						'stock_outlet_id'	=> $this->session->userdata['outlets']['current_outlet'],
						'updated_on'	=>date('Y-m-d')
					);

						$stock_exist= $this->CashierModel->CheckStockExist($where);
						if($stock_exist['success']=='true'){
							$update_stock=$this->CashierModel->UpdateInventoryStockForAdmin($data3);
						}else{
							$insert_stock=$this->CashierModel->Insert($data3,'inventory_stock');
						}					
					// $res=$this->CashierModel->UpdateInventoryStock($data3);
					// $res=$this->CashierModel->Update($data3,'inventory_stock','stock_service_id');

					$this->ReturnJsonArray(true,false,"Stock Updated!");
					die;
				}else{
					$this->ReturnJsonArray(false,true,"Wrong Method!");
					die;
				}
			}
		}
		
		public function ChangeSmsStatus(){
			if($this->IsLoggedIn('business_admin')){
				if($_POST['sms_type']=="wapp"){
					$where = array(
						'business_outlet_id'=> $_POST['business_outlet_id'],
						'whats_app_sms_status' => $_POST['sms_status']
					);
				}else{
					$where = array(
						'business_outlet_id'=> $_POST['business_outlet_id'],
						'business_outlet_sms_status' => $_POST['sms_status']
					);
				}
				
				
				$data = $this->BusinessAdminModel->Update($where,'mss_business_outlets','business_outlet_id');
				if($data['success'] == 'true'){	
					$this->ReturnJsonArray(true,false,"SMS status Updated");
					die;
				}else{
					$this->ReturnJsonArray(false,true,"Error in Updating status!");
					die;
				}
			}
			else{
				$this->LogoutUrl(base_url()."BusinessAdmin/");
			}		
		}

        public function AddCashIn(){
        if($this->IsLoggedIn('business_admin')){
            $post['amount'] = $_POST['cash_in'];
            $post['opening_date'] = date('Y-m-d');
            $post['amount_data'] = 'CashIn';
            $post['payment_mode'] = 'Cash';
            $post['business_outlet_id'] = $this->session->userdata['outlets']['current_outlet'];
            $data=$this->CashierModel->Insert($post,'mss_opening_balance');            
            $this->ReturnJsonArray(true,false,$data['res_arr']);
            die;
        }
    }
    
    public function AddCashOut(){
        if($this->IsLoggedIn('business_admin')){
            $post['amount'] = $_POST['cash_out'];
            $post['opening_date'] = date('Y-m-d');
            $post['amount_data'] = 'CashOut';
            $post['payment_mode'] = $_POST['paymod_mode'];
            $post['business_outlet_id'] = $this->session->userdata['outlets']['current_outlet'];
            $data=$this->CashierModel->Insert($post,'mss_opening_balance');
            $this->ReturnJsonArray(true,false,$data['res_arr']);
            die;
        }
    }

    public function AddMessageTrigger(){
        if($this->IsLoggedIn('business_admin')){
            $data['trigger_name'] = $_POST['trigger_name'];
            $data['trigger_description'] = $_POST['trigger_discription'];
            $data['mode'] = $_POST['mode'];
            $data['outlet_id'] = $_POST['business_outlet_id'];
            $data['recipient'] = $_POST['reciptents'];
            $date = explode("-", $_POST['dates']);
            $data['start_date'] = date("Y-m-d", strtotime($date[0]) );
            $data['expiry_date'] = date("Y-m-d", strtotime($date[1]) );
            $data['set_frequency'] = $_POST['ftype'];
            //$data['trigger_name'] = $_POST['frequency_detail'];
            $data['sms'] = $_POST['message'];
            $data['created_by'] = $this->session->userdata['logged_in']['business_admin_id'];
            $result = $this->BusinessAdminModel->Insert($data,'sms_trigger');
            if($result['success']){
                $id = $result['res_arr']['insert_id'];
                foreach ($_POST['frequency_detail'] as $key => $frequency_detail) {
                    $this->BusinessAdminModel->Insert(array('trigger_id'=>$id,'frequency_detail'=>$frequency_detail),'sms_frequency_detail');
                }
            }
            $this->ReturnJsonArray(true,false,"Trigger Saved SuccessFully");
            die;
        }
    }

    //delete trigger
        public function CancelSMSTrigger(){
            if($this->IsLoggedIn('business_admin')){
                // $this->PrettyPrintArray($_POST);
                // exit;
                if(isset($_POST) && !empty($_POST)){
                    $this->form_validation->set_rules('auto_engage_id', 'Auto Engage', 'trim|required');            
    
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
                            'id'    => $this->input->post('auto_engage_id'),
                            'is_active'=>$this->input->post('is_active')
                        );
    
                        $result = $this->BusinessAdminModel->Update($data,'sms_trigger','id');
                            
                        if($result['success'] == 'true'){
                            $this->ReturnJsonArray(true,false,"Trigger Updated Successfully!");
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
                $this->LogoutUrl(base_url()."BusinessAdmin/");
            }   
        }

    
    public function ActivateSMSTrigger(){
        if($this->IsLoggedIn('business_admin')){
                // $this->PrettyPrintArray($_POST);
                // exit;
                if(isset($_POST) && !empty($_POST)){
                    $this->form_validation->set_rules('auto_engage_id', 'Auto Engage', 'trim|required');            
    
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
                            'outlet_id'    => $this->input->post('auto_engage_id'),
                            'is_active'=>$this->input->post('is_active'),
                            'services_number    '=>$this->input->post('service_id'),
                        );                    
                        if($this->input->post('is_active') == 0){
                            unset($data['is_active']);
                            $result = $this->BusinessAdminModel->DeleteSMSActivity('sms_activity',$data);                           
                        }else{
                            $result = $this->BusinessAdminModel->Insert($data,'sms_activity');
                        }                        
                        if($result['success'] == 'true'){
                            $this->ReturnJsonArray(true,false,"Trigger Saved Successfully!");
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
                $this->LogoutUrl(base_url()."BusinessAdmin/");
            }
		}
		


		public function GetInventoryDetails(){
			if($this->IsLoggedIn('business_admin')){
				if(isset($_GET) && !empty($_GET)){
					$where = array(
						'inventory_id'				=> $_GET['inventory_id']
					);
					$data = $this->CashierModel->StockInventoryDetailsById($where);
					// $this->PrettyPrintArray($data);
					if($data['success'] == 'true'){	
						$this->ReturnJsonArray(false,true,$data['res_arr']);
            die;	
					}
				}
			}
			else{
				$this->LogoutUrl(base_url()."BusinessAdmin");
			}			
		}

		public function GetInventoryTransactions(){
			if($this->IsLoggedIn('business_admin')){
				if(isset($_GET) && !empty($_GET)){
					$where=array(
						'from_date'	=> $_GET['from_date'],
						'to_date'		=> $_GET['to_date'],
						'business_outlet_id'=> $this->session->userdata['outlets']['current_outlet']
					);
					// $this->PrettyPrintArray($where);
						$data=$this->BusinessAdminModel->GetInventoryDetailsBetween($where);						
						$this->ReturnJsonArray(true,false,$data['res_arr']);
						die;
				}else{
					$this->ReturnJsonArray(false,true,"Wrong Method!");
					die;
				}						
			}
			else{
					$this->LogoutUrl(base_url()."BusinessAdmin/");
			}
		}

}


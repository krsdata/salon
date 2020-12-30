<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . '/third_party/spout-2.4.3/src/Spout/Autoloader/autoload.php';
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type; 
class MasterReport extends CI_Controller {
	
	//constructor of the Alumni Controller
    public function __construct(){
       parent::__construct();
       date_default_timezone_set('Asia/Kolkata');
       $this->load->model('AnalyticsModel');
       $this->load->model('BusinessAdminModel');
       $this->load->model('AppointmentsModel');
       $this->load->model('CashierModel');
       $this->load->model('POSModel');
       $this->load->model('MasterAdminModel');
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
	private function LogoutUrl($url){
		if(isset($this->session->userdata['logged_in']) && !empty($this->session->userdata['logged_in'])){
			$this->session->unset_userdata('logged_in');
			$this->session->unset_userdata('outlets');
			$this->session->sess_destroy();
		}
		
		redirect($url,'refresh');
	}
	
  private function GetDataForMasterAdmin($title){
        $data = array();
        $data['title'] = $title;
        // $data['business_admin_packages'] = $this->GetMasterAdminPackages();
        // $data['master_admin_details']  = $this->GetMAsterAdminDetails();
        // $data['business_outlet_details'] = $this->GetMasterOutlets();
        // if(isset($this->session->userdata['outlets'])){
        //  $data['selected_outlet']      = $this->GetCurrentOutlet($this->session->userdata['outlets']['current_outlet']);
        // }
        return $data;
    }
   
  private function GetFifteenSalesByType($type){
		if($this->IsLoggedIn('master_admin')){
			$where = array(
				'type'=>$type,
				'master_admin_id' => $this->session->userdata['logged_in']['master_admin_id']
			);
			$result = $this->MasterAdminModel->LastFifteenDaySalesByType($where);
			if(array_search('res_arr',$result) == false)
			    $result += ['res_arr'=>''];
			return $result['res_arr'];
		}else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}
	}
	public function GetMonthSalesByType($data)
	{
		if($this->IsLoggedIn('master_admin')){
			$ma_admin_id = $this->session->userdata['logged_in']['master_admin_id'];
			$res['sales'] = $this->MasterAdminModel->GetCurrentMonthSalesByType($data,$ma_admin_id);
			return $res['sales'] = $res['sales']['res_arr'];
		}else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}
	
	}
	public function GetSalesByType($data)
	{
		if($this->IsLoggedIn('master_admin')){
			$ma_admin_id = $this->session->userdata['logged_in']['master_admin_id'];
			$res['sales'] = $this->MasterAdminModel->GetTodaySalesByType($data,$ma_admin_id);
		
			return $res['sales'] = $res['sales']['res_arr'];
		}else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}
	
	}
	
	public function GetPrevMonthSalesByType($data)
	{
		if($this->IsLoggedIn('master_admin')){
			$ma_admin_id = $this->session->userdata['logged_in']['master_admin_id'];
			$res['sales'] = $this->MasterAdminModel->PreviousMonthTillSalesByType($data,$ma_admin_id);
			return $res['sales'] = $res['sales']['res_arr'];
		}else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}
		
	}
    
	public function GetPrevMonthTotalByType($data)
	{
		$ma_admin_id = $this->session->userdata['logged_in']['master_admin_id'];
		$res['sales'] = $this->MasterAdminModel->PreviousMonthSalesByType($data,$ma_admin_id);
		return $res['sales'] = $res['sales']['res_arr'];
	}
	
	public function GetSixMonthSalesByType($data)
	{
		if($this->IsLoggedIn('master_admin')){
			$ma_admin_id = $this->session->userdata['logged_in']['master_admin_id'];
			$res['sales'] = $this->MasterAdminModel->LastSixMonthServiceByType($data,$ma_admin_id);
			if($res['sales']['success'] == 'true')
			{
				return $res['sales'] = $res['sales']['res_arr'];
			}
			else
			{
				$res['sales'] = ['res_arr'=>''];
				return $res['sales'] = $res['sales']['res_arr'];
			}		
			
		}else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}
	}
	
	public function GetOutletState()
	{
		if($this->IsLoggedIn('master_admin')){
			$ma_admin_id = $this->session->userdata['logged_in']['master_admin_id'];
			$res['state'] = $this->MasterAdminModel->GetStateByMasterAdmin($ma_admin_id);
			if(array_search('res_arr',$res['state'])== 'true')
			    return $res['state'] = $res['state']['res_arr'];
			else{
			    $res['state'] += ['res_arr'=>''];
			    return $res['state'] = $res['state']['res_arr'];
			}
		}else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}
	}
	
	private function GetPrevTillTrendsByOutlet($data,$outlet){
		if($this->IsLoggedIn('master_admin')){
			$where = array(
				'type' => $data,
				'master_admin_id' => $this->session->userdata['logged_in']['master_admin_id'],
				'outlet_id' => $outlet
			);
			$res = $this->MasterAdminModel->OutletPreviousMonthTillSalesByType($where);
			return $res['res_arr']; 
		}else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}
	}
	private function GetPrevTrendsByOutlet($data,$outlet){
		if($this->IsLoggedIn('master_admin')){
			$where = array(
				'type' => $data,
				'master_admin_id' => $this->session->userdata['logged_in']['master_admin_id'],
				'outlet_id' => $outlet
			);
			$res = $this->MasterAdminModel->OutletPreviousMonthSalesByType($where);
			return $res['res_arr']; 
		}else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}
	}
	private function GetCurrentTrendsByOutlet($data,$outlet)
	{
		if($this->IsLoggedIn('master_admin')){
			$where = array(
				'type' => $data,
				'master_admin_id' => $this->session->userdata['logged_in']['master_admin_id'],
				'outlet_id' => $outlet
			);
			$res = $this->MasterAdminModel->GetOutletCurrentMonthSalesByType($where);
			return $res['res_arr']; 
		}else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		
		}
	}
	
	private function LastFifteenDayTypeSalesByOutlet($type,$outlet_id){
		if($this->IsLoggedIn('master_admin')){
			
			$where = array(
				'type'=>$type,
				'master_admin_id' => $this->session->userdata['logged_in']['master_admin_id'],
				'outlet_id'=>$outlet_id
			);
			
			$result = $this->MasterAdminModel->OutletLastFifteenDaySalesByType($where);
			
			if(array_search('res_arr',$result)==false)
				$result += ['res_arr'=>''];
			return $result['res_arr'];
		}else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}
	}
	
	private function LastFifteenDayTypeSalesByCity($type,$city){
		if($this->IsLoggedIn('master_admin')){
			$where = array(
				'type'=>$type,
				'master_admin_id' => $this->session->userdata['logged_in']['master_admin_id'],
				'city'=>$city
			);
			$result = $this->MasterAdminModel->CityLastFifteenDaySalesByType($where);
			if(array_search('res_arr',$result)==false)
				$result += ['res_arr'=>''];
			return $result['res_arr'];
		}else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}
	}
	
	private function LastFifteenDayTypeSalesByState($type,$state){
		if($this->IsLoggedIn('master_admin')){
			$where = array(
				'type'=>$type,
				'master_admin_id' => $this->session->userdata['logged_in']['master_admin_id'],
				'state'=>$state
			);
			$result = $this->MasterAdminModel->StateLastFifteenDaySalesByType($where);
			if(array_search('res_arr',$result)==false)
				$result += ['res_arr'=>''];
			return $result['res_arr'];
		}else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}
	}
	
	private function GetSalesByTypeToday($data,$outlet){
		if($this->IsLoggedIn('master_admin')){
			$where = array(
				'type' => $data,
				'master_admin_id' => $this->session->userdata['logged_in']['master_admin_id'],
				'outlet_id' => $outlet
			);
			
			$res = $this->MasterAdminModel->GetOutletTodaySalesByType($where);
			return $res['res_arr'];
		}else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		 
		}
	}
	
	private function GetCitySalesByTypeToday($type,$city){
		if($this->IsLoggedIn('master_admin')){
			$where = array(
				'type' => $type,
				'master_admin_id' => $this->session->userdata['logged_in']['master_admin_id'],
				'city' => $city
			);
			$res = $this->MasterAdminModel->GetCityTodaySalesByType($where);
			return $res['res_arr'];
		}else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		 
		}
	}
	
	private function GetCurrentTrendsByCity($type,$city){
		if($this->IsLoggedIn('master_admin')){
			$where = array(
				'type' => $type,
				'master_admin_id' => $this->session->userdata['logged_in']['master_admin_id'],
				'city' => $city
			);
			$res = $this->MasterAdminModel->GetCityCurrentMonthSalesByType($where);
			return $res['res_arr'];
		}else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		 
		}
	}
	
	private function GetPrevTillTrendsByCity($type,$city){
		if($this->IsLoggedIn('master_admin')){
			$where = array(
				'type' => $type,
				'master_admin_id' => $this->session->userdata['logged_in']['master_admin_id'],
				'city' => $city
			);
			$res = $this->MasterAdminModel->CityPreviousMonthTillSalesByType($where);
			return $res['res_arr'];
		}else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		 
		}
	}
	
	private function GetPrevTrendsByCity($type,$city){
		if($this->IsLoggedIn('master_admin')){
			$where = array(
				'type' => $type,
				'master_admin_id' => $this->session->userdata['logged_in']['master_admin_id'],
				'city' => $city
			);
			$res = $this->MasterAdminModel->CityPreviousMonthSalesByType($where);
			return $res['res_arr'];
		}else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		 
		}
	}
	
	private function GetStateSalesByTypeToday($type,$state){
		if($this->IsLoggedIn('master_admin')){
			$where = array(
				'type' => $type,
				'master_admin_id' => $this->session->userdata['logged_in']['master_admin_id'],
				'state' => $state
			);
			$res = $this->MasterAdminModel->GetStateTodaySalesByType($where);
			return $res['res_arr'];
		}else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		 
		}
	}
	
	private function GetCurrentTrendsByState($type,$state){
		if($this->IsLoggedIn('master_admin')){
			$where = array(
				'type' => $type,
				'master_admin_id' => $this->session->userdata['logged_in']['master_admin_id'],
				'state' => $state
			);
			$res = $this->MasterAdminModel->GetStateCurrentMonthSalesByType($where);
			return $res['res_arr'];
		}else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		 
		}
	}
	
	private function GetPrevTillTrendsByState($type,$state){
		if($this->IsLoggedIn('master_admin')){
			$where = array(
				'type' => $type,
				'master_admin_id' => $this->session->userdata['logged_in']['master_admin_id'],
				'state' => $state
			);
			$res = $this->MasterAdminModel->StatePreviousMonthTillSalesByType($where);
			return $res['res_arr'];
		}else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		 
		}
	}
	
	private function GetPrevTrendsByState($type,$state){
		if($this->IsLoggedIn('master_admin')){
			$where = array(
				'type' => $type,
				'master_admin_id' => $this->session->userdata['logged_in']['master_admin_id'],
				'state' => $state
			);
			$res = $this->MasterAdminModel->StatePreviousMonthSalesByType($where);
			return $res['res_arr'];
		}else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		 
		}
	}
	
    //Dashboard for the Business Admin
   public function Dashboard(){
      if($this->IsLoggedIn('master_admin')){
		$data = $this->GetDataForMasterAdmin("Dashboard");
		// $data['current_month_sales'] = $this->MasterAdminModel->CurrentMonthSales(1);
		// $data['current_month_sales'] = $data['current_month_sales']['res_arr'];
		// $data['previous_month_sales'] = $this->MasterAdminModel->PreviousMonthSales(1);
		// $data['previous_month_sales'] = $data['previous_month_sales']['res_arr'];
		// if(empty($data['previous_month_sales'][0]['total_sales']))
		// {
		// 	$data['previous_month_sales'][0] = ['total_sales'=>0];
		// }
		// $data['previous_month_total_sales'] = $this->MasterAdminModel->PreviousMonthTotalSales(1);
		// $data['previous_month_total_sales'] = $data['previous_month_total_sales']['res_arr'];
		$master_admin = $this->session->userdata['logged_in']['master_admin_id'];
		$data['avg_current_month_sales'] = $this->MasterAdminModel->AverageCurrentMonthSales($master_admin);
		$data['avg_current_month_sales'] = $data['avg_current_month_sales']['res_arr'];
		$data['avg_previous_month_sales'] = $this->MasterAdminModel->AveragePreviousMonthSales($master_admin);
		$data['avg_previous_month_sales'] = $data['avg_previous_month_sales']['res_arr'];
		$data['last_fifteen_days_sales']  = $this->GetFifteenSalesByType('service');
		$data['last_fifteen_pack_sales'] = $this->MasterAdminModel->LastFifteenDayPackageSales($master_admin);
		if(array_search('res_arr',$data['last_fifteen_pack_sales'])==false)
			$data['last_fifteen_pack_sales'] +=['res_arr'=>''];
		$data['last_fifteen_prod_sales'] = $this->GetFifteenSalesByType('otc');
		// $data['last_fifteen_days_sales'] = $data['last_fifteen_days_sales']['res_arr'];
		$data['last_fifteen_pack_sales'] = $data['last_fifteen_pack_sales']['res_arr'];
		// $data['last_fifteen_prod_sales'] = $data['last_fifteen_prod_sales']['res_arr'];
		$time = strtotime(date("Y-m-d"));
		$p=0; // product count
		$pr = 0;//package count
		$s = 0;//service count
		$fsales = array();
		$fpack = array();
		$fprod = array();		
		for($count = 15;$count>=1;$count--)
		{
			$tt = date("Y-m-d",strtotime("-".$count."day",$time));
			if(!empty($data['last_fifteen_days_sales'])  && $s<count($data['last_fifteen_days_sales']) ){
				if($tt ==$data['last_fifteen_days_sales'][$s]['bill_date'] ){
					array_push($fsales,$data['last_fifteen_days_sales'][$s]);
					$s++;
				}
				else{
					$insert_service = array(
						'total_sales'=>0,
						'bill_date'=>$tt,
						'bill_count'=>0
					);
					array_push($fsales,$insert_service);
				}
			}else{
				$insert_service = array(
					'total_sales'=>0,
					'bill_date'=>$tt,
					'bill_count'=>0
				);
				array_push($fsales,$insert_service);
			}
			if(!empty($data['last_fifteen_pack_sales']) && $pr<count($data['last_fifteen_pack_sales']) ){
				if($tt == $data['last_fifteen_pack_sales'][$pr]['pack_date']){
					array_push($fpack,$data['last_fifteen_pack_sales'][$pr]);
					$pr++;
				}else{
					$insert_pack =array(
						'packages'=>0,
						'pack_date'=>$tt,
						'package_count'=>0
					);
					array_push($fpack,$insert_pack);
				}
			}else{
				$insert_pack =array(
					'packages'=>0,
					'pack_date'=>$tt,
					'package_count'=>0
				);
				array_push($fpack,$insert_pack);
			}
			if(!empty($data['last_fifteen_prod_sales']) && $p<count($data['last_fifteen_prod_sales'])){
				if($tt == $data['last_fifteen_prod_sales'][$p]['bill_date']){
					array_push($fprod,$data['last_fifteen_prod_sales'][$p]);
					$p++;
				}else{
					$insert_prod=array(
						'total_sales'=>0,
						'bill_date'=>$tt
					);
					array_push($fprod,$insert_prod);
				}
			}else{
				$insert_prod=array(
					'total_sales'=>0,
					'bill_date'=>$tt
				);
				array_push($fprod,$insert_prod);
			}
			
		}
		if(empty($data['last_fifteen_days_sales']) || count($data['last_fifteen_days_sales']) != 15)
			$data['last_fifteen_days_sales'] = $fsales;
		if(empty($data['last_fifteen_pack_sales']) || count($data['last_fifteen_pack_sales'])!=15)
			$data['last_fifteen_pack_sales'] = $fpack;
		if(empty($data['last_fifteen_prod_sales']) || count($data['last_fifteen_prod_sales'])!=15)
			$data['last_fifteen_prod_sales'] = $fprod;
		

		$data['labels'] = array();
		$data['data_sales'] = array();
		$data['data_service'] = array();
		$data['data_prod'] =array();
		$data['data_pack'] =array();
		$data['bill_count'] = array();
		if(!empty($data['last_fifteen_days_sales']))
		{
			foreach($data['last_fifteen_days_sales'] as $sales=>$value)
			{
				array_push($data['labels'],$value['bill_date']);
				array_push($data['data_sales'],($value['total_sales']+$data['last_fifteen_pack_sales'][$sales]['packages']+$data['last_fifteen_prod_sales'][$sales]['total_sales']));
				array_push($data['data_service'],$value['total_sales']);
				array_push($data['data_prod'],$data['last_fifteen_prod_sales'][$sales]['total_sales']);
				array_push($data['data_pack'],$data['last_fifteen_pack_sales'][$sales]['packages']);
				array_push($data['bill_count'],($value['bill_count']+$data['last_fifteen_pack_sales'][$sales]['package_count']));
			}
		}
		$data['cust_visit_cur_date'] = $this->MasterAdminModel->CustomerVisitsTillDate($master_admin);
		$data['cust_visit_cur_date'] = $data['cust_visit_cur_date']['res_arr'];
		$data['cust_visit_prev_till_date'] = $this->MasterAdminModel->CustomerVisitPreviousMonthTillDate($master_admin);
		$data['cust_visit_prev_till_date'] = $data['cust_visit_prev_till_date']['res_arr'];
		$data['cust_visit_prev_month'] = $this->MasterAdminModel->CustomerVisitPreviousMonth($master_admin);
		$data['cust_visit_prev_month'] = $data['cust_visit_prev_month']['res_arr'];
		$data['today_sales_service'] = $this->GetSalesByType('service');
		$data['today_sales_products'] = $this->GetSalesByType('otc');
		$data['today_sales_package']  = $this->MasterAdminModel->GetTodayPackSales($master_admin);
		$data['today_sales_package']  = $data['today_sales_package']['res_arr'];
		$data['today_bill_count']	= $this->MasterAdminModel->GetTodayCountBySerAndProd($master_admin);
		$data['today_bill_count'] = $data['today_bill_count']['res_arr'][0]['total_bill'];
		$data['today_pack_bill_count'] = $this->MasterAdminModel->GetTodayCountByPack($master_admin);
		$data['today_bill_count'] += $data['today_pack_bill_count']['res_arr'][0]['package_count'];
		$data['previous_pack_till_date'] = $this->MasterAdminModel->GetPreviousPackTillDate($master_admin);
		$data['previous_pack_till_date'] = $data['previous_pack_till_date']['res_arr'];
		if(empty($data['previous_pack_till_date'][0]['packages']))
		{
			$data['previous_pack_till_date'][0] = ['packages'=>0];
		}
		$data['previous_month_pack_sales'] = $this->MasterAdminModel->GetPreviousPackSales($master_admin);
		$data['previous_month_pack_sales'] = $data['previous_month_pack_sales']['res_arr'];
		$data['current_pack_sales'] = $this->MasterAdminModel->CurrentMonthPackSales($master_admin);
		$data['current_pack_sales'] = $data['current_pack_sales']['res_arr'];
		$data['current_service_sales'] = $this->MasterAdminModel->CurrentMonthServiceSales($master_admin);
		$data['current_service_sales'] = $data['current_service_sales']['res_arr'];
		$data['current_month_service'] = $this->GetMonthSalesByType('service');
		$data['current_month_products'] = $this->GetMonthSalesByType('otc');
		
		
		$data['previous_month_till_service'] = $this->GetPrevMonthSalesByType('service');
		$data['previous_month_till_products'] = $this->GetPrevMonthSalesByType('otc');
		
		if(empty($data['previous_month_till_products'][0]['total_sales']))
		{
			$data['previous_month_till_products'][0] = ['total_sales'=>0];
		}
		$data['previous_month_total_service'] = $this->GetPrevMonthTotalByType('service');
		$data['previous_month_total_products'] = $this->GetPrevMonthTotalByType('otc');
		$data['current_till_bill_count'] = $this->MasterAdminModel->GetCurrentBillCount($master_admin);
		$data['current_till_bill_count'] = $data['current_till_bill_count']['res_arr'];
		$data['prev_till_bill_count'] = $this->MasterAdminModel->GetPrevTillBillCount($master_admin);
		$data['prev_till_bill_count'] = $data['prev_till_bill_count']['res_arr'];
		if(empty($data['prev_till_bill_count'][0]['total_bill']))
		{
			$data['prev_till_bill_count'][0] = ['total_bill'=>0];
		}
		$data['prev_bill_count'] = $this->MasterAdminModel->GetPrevBillCount($master_admin);
		$data['prev_bill_count'] = $data['prev_bill_count']['res_arr'];
		if(empty($data['prev_bill_count'][0]['total_bill']))
		{
			$data['prev_bill_count'][0] = ['total_bill'=>0];
		}
		$data['six_months_service'] = $this->GetSixMonthSalesByType('service');
		$data['six_months_product'] = $this->GetSixMonthSalesByType('otc');
		$data['six_months_package'] = $this->MasterAdminModel->LastSixMonthPackage($master_admin);
		
		if(array_search('res_arr',$data['six_months_package'])==false)
			$data['six_months_package'] += ['res_arr'=>''];
			
		$data['six_months_package'] = $data['six_months_package']['res_arr'];
		$six_months_pack=array();
		$six_months_prod=array();
		$six_months_serv=array();
		$p=0; // package count
		$pr = 0;//product count
		$s = 0;//service count
			
			//unset($data['six_months_service'][5]);
			// print_r($pr);
			 //$this->PrettyPrintArray($data['six_months_service']);
			for($i = 1;$i<=6;$i++)
			{
				$six_month = date('F-y',strtotime(-$i.'month'));
				if(!empty($data['six_months_service'])  && $s<count($data['six_months_service']) ){
					if($six_month ==$data['six_months_service'][$s]['date'] ){
						array_push($six_months_serv,$data['six_months_service'][$s]);
						$s++;
					}
					else{
						$six_service = array(
							'total_service'=>0,
							'service_count'=>0,
							'date'=>$six_month
						);
						array_push($six_months_serv,$six_service);
					}
				}else{
					$six_service = array(
						'total_service'=>0,
						'service_count'=>0,
						'date'=>$six_month
					);
					array_push($six_months_serv,$six_service);
				}
				if(!empty($data['six_months_product']) && $pr<count($data['six_months_product']) ){
					if($six_month == $data['six_months_product'][$pr]['date']){
						array_push($six_months_prod,$data['six_months_product'][$pr]);
						$pr++;
					}else{
						$six_prod = array(
							'total_service'=>0,
							'service_count'=>0,
							'date'=>$six_month
						);
						array_push($six_months_prod,$six_prod);
					}
				}else{
					$six_prod = array(
						'total_service'=>0,
						'service_count'=>0,
						'date'=>$six_month
					);
					array_push($six_months_prod,$six_prod);
				}
				if(!empty($data['six_months_package']) && $p<count($data['six_months_package'])){
					if($six_month == $data['six_months_package'][$p]['date']){
						array_push($six_months_pack,$data['six_months_package'][$p]);
						$p++;
					}else{
						$six_pack = array(
						'package_sales'=>0,
						'package_count'=>0,
						'date'=>$six_month
						);
						array_push($six_months_pack,$six_pack);
				   }
				}else{
					$six_pack = array(
						'package_sales'=>0,
						'package_count'=>0,
						'date'=>$six_month
					);
					array_push($six_months_pack,$six_pack);
				}
			}
	  if(empty($data['six_months_service']) || count($data['six_months_service']) != 15)
			$data['six_months_service'] = $six_months_serv;
		if(empty($data['six_months_product']) || count($data['six_months_product'])!=15)
			$data['six_months_product'] = $six_months_prod;
		if(empty($data['six_months_package']) || count($data['six_months_package'])!=15)
			$data['six_months_package'] = $six_months_pack;
		

		$data['six_month_labels'] = array();
		$data['six_month_data_sales'] = array();
		$data['six_month_data_service'] = array();
		$data['six_month_data_prod'] =array();
		$data['six_month_data_pack'] =array();
		$data['six_month_bill_count'] = array();
		if(!empty($data['six_months_service']))
		{
			foreach($data['six_months_service'] as $sales=>$value)
			{
				array_push($data['six_month_labels'],$value['date']);
				array_push($data['six_month_data_sales'],($value['total_service']+$data['six_months_package'][$sales]['package_sales']+$data['six_months_product'][$sales]['total_service']));
				array_push($data['six_month_data_service'],$value['total_service']);
				array_push($data['six_month_data_prod'],$data['six_months_product'][$sales]['total_service']);
				array_push($data['six_month_data_pack'],$data['six_months_package'][$sales]['package_sales']);
				array_push($data['six_month_bill_count'],($value['service_count']+$data['six_months_package'][$sales]['package_count']));
			}
		} 
		
		$data['outlet_state'] = $this->GetOutletState();
		$data['business_admin_details'] = $this->MasterAdminModel->GetBusinessDetails($this->session->userdata['logged_in']['master_admin_id']);
		$business_det = $data['business_admin_details']['res_arr'];
		$data['lmtd_total_sales'] =array();
		$data['mtd_total_sales'] = array();
		$data['outlet_name'] = array();
		$i = 0;
		foreach($business_det as $key=>$value){
			$where = array(
				'outlet_id' => $value['business_outlet_id']
			);
			// print_r($value);
			$lmtdPack = $this->MasterAdminModel->GetOutletPreviousPackTillDate($where);
			$lmtdPack = $lmtdPack['res_arr'];
			$lmtdService = $this->GetPrevTillTrendsByOutlet('service',$value['business_outlet_id']);
			$lmtdProduct = $this->GetPrevTillTrendsByOutlet('otc',$value['business_outlet_id']);
			array_push($data['lmtd_total_sales'],$lmtdPack[0]['packages']+$lmtdService[0]['total_sales']+$lmtdProduct[0]['total_sales']);
			array_push($data['outlet_name'],$value['business_outlet_name']);
			$mtdPack = $this->MasterAdminModel->CurrentOutletMonthPackSales($where);
			$mtdPack = $mtdPack['res_arr'];
			$mtdService = $this->GetCurrentTrendsByOutlet('service',$value['business_outlet_id']);
			$mtdProduct = $this->GetCurrentTrendsByOutlet('otc',$value['business_outlet_id']);
			array_push($data['mtd_total_sales'],$mtdPack[0]['packages']+$mtdService[0]['total_sales']+$mtdProduct[0]['total_sales']);
			// $data['mtd_all_outlet'][$i] += ['outlet_id'=>$value['business_outlet_id']]; 
			$i = $i + 1;
		}
					// $this->PrettyPrintArray($data);
					// exit;
		$data['sidebar_collapsed'] = "true";			
		$this->load->view('master_admin/ma_dashboard_view',$data);
        }else{
         $this->LogoutUrl(base_url()."MasterAdmin");
        }
    }
  
  public function ReportsManagement(){
		if($this->IsLoggedIn('master_admin')){
			
			$data = $this->GetDataForMasterAdmin("Reports Management");
			if(!isset($_GET) || empty($_GET)){
				// $this->PrettyPrintArray($_GET);
				//Load the default view
				$data = $this->GetDataForMasterAdmin("Menu Management");
				$data['business_admin_details'] = $this->MasterAdminModel->GetBusinessDetails($this->session->userdata['logged_in']['master_admin_id']);
				$data['business_admin_details'] = $data['business_admin_details']['res_arr'];
				$this->load->view('master_admin/ma_reports_view',$data);
				// $this->PrettyPrintArray($data['business_admin_details']);
			}
			
			else if(isset($_GET) && !empty($_GET)){
				// $this->PrettyPrintArray($_GET);
				//Return the report view
				$res_arr = array();
				$report = array();
				if($_GET['file_contents'][0]!=''){
					$outlet_id =explode(",",$_GET['file_contents'][0]);
					foreach($outlet_id as $outlet=>$value){
						$business_admin = $this->MasterAdminModel->DetailsById($value,'mss_business_outlets','business_outlet_id');
						// $this->PrettyPrintArray($business_admin);
						// if(isset($data['selected_outlet']) || !empty($data['selected_outlet'])){
							$data = array(
								'report_name' 			 => $_GET['report_name'],
								'from_date' 				 => $_GET['from_date'],
								'to_date' 					 => $_GET['to_date'],
								'business_outlet_id' => $business_admin['res_arr']['business_outlet_id'],
								'business_admin_id'  => $business_admin['res_arr']['business_outlet_business_admin']
							);
							$result = $this->MasterAdminModel->GenerateReports($data);
				            // $this->PrettyPrintArray($result['res_arr']);
							array_push($res_arr,$result['res_arr']);
					}
				}
				else{
					$data['business_admin_details'] = $this->MasterAdminModel->GetBusinessDetails($this->session->userdata['logged_in']['master_admin_id']);
					$data['business_admin_details'] = $data['business_admin_details']['res_arr'];
					foreach($data['business_admin_details'] as $business=>$value)
					{
						$data = array(
							'report_name' 			 => $_GET['report_name'],
							'from_date' 				 => $_GET['from_date'],
							'to_date' 					 => $_GET['to_date'],
							'business_outlet_id' => $value['business_outlet_id'],
							'business_admin_id'  =>$value['business_admin_id']
						);
						// echo "<pre>";
						// print_r($data);
						$result = $this->MasterAdminModel->GenerateReports($data);
						array_push($res_arr,$result['res_arr']);
						// print_r($value);
					}
				}
				// $this->PrettyPrintArray($res_arr);
				for($j=0;$j<count($res_arr);$j++)
				{
					$report = array_merge($res_arr[$j],$report);
				}
				// $this->PrettyPrintArray($report);
					if(!empty($report)){
						$data = array(
									'success' => 'true',
									'error'   => 'false',
									'message' => '',
									'result' =>  $report
						);
						header("Content-type: application/json");
						print(json_encode($data, JSON_PRETTY_PRINT));
						die;	
          }
          elseif(empty($report)){
          	$data = array(
									'success' => 'false',
									'error'   => 'true',
									'message' =>  "No Data Found"
						);
						header("Content-type: application/json");
						print(json_encode($data, JSON_PRETTY_PRINT));
						die;	
					}

	    //   }
			}		
		}
		else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}
	}
  
  public function GetCityStateWise()
	{
		if($this->IsLoggedIn('master_admin')){
			if(isset($_GET) && !empty($_GET))
			{
				$data = array(
					'business_master_admin_id' => $this->session->userdata['logged_in']['master_admin_id'],
					'state' => $this->input->get('state')
				);
								
				$state = $this->MasterAdminModel->GetCityByState($data);
				header("Content-type: application/json");
				print(json_encode($state['res_arr'], JSON_PRETTY_PRINT));
				die;
			}
		}else{
			$this->LogoutUrl(base_url()."MasterAdmin");	
		}
	}  
	
	public function GetOutlet()
	{
		if($this->IsLoggedIn('master_admin')){
			if(isset($_GET) && !empty($_GET))
			{
				$data = array(
					'business_master_admin_id' => $this->session->userdata['logged_in']['master_admin_id'],
					'city' => $this->input->get('city')
				);
				$state = $this->MasterAdminModel->GetOutletByCity($data);
				header("Content-type: application/json");
				print(json_encode($state['res_arr'], JSON_PRETTY_PRINT));
				die;
			}
		}else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}
	}
	
	public function GetSixMonthTypeSalesByOutlet($type,$outlet_id){
		if($this->IsLoggedIn('master_admin')){
			$ma_admin_id = $this->session->userdata['logged_in']['master_admin_id'];
			$data = array(
						'master_admin_id' => $this->session->userdata['logged_in']['master_admin_id'],
						'type' => $type,
						'outlet_id'=>$outlet_id
					);
			$res['sales'] = $this->MasterAdminModel->LastSixMonthTypeSalesByOutlet($data,$ma_admin_id );		
		    if($res['sales']['success'] == 'true')
			{
				return $res['sales'] = $res['sales']['res_arr'];
			}
			else
			{
				$res['sales'] = ['res_arr'=>''];
				return $res['sales'] = $res['sales']['res_arr'];
			}		
			
		}else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}			
	}
	
	public function GetSixMonthTypeSalesByCity($type,$city){
		if($this->IsLoggedIn('master_admin')){
			$ma_admin_id = $this->session->userdata['logged_in']['master_admin_id'];
			$data = array(
						'master_admin_id' => $this->session->userdata['logged_in']['master_admin_id'],
						'type' => $type,
						'city'=>$city
					);
			$res['sales'] = $this->MasterAdminModel->LastSixMonthTypeSalesByCity($data,$ma_admin_id );		
		    if($res['sales']['success'] == 'true')
			{
				return $res['sales'] = $res['sales']['res_arr'];
			}
			else
			{
				$res['sales'] = ['res_arr'=>''];
				return $res['sales'] = $res['sales']['res_arr'];
			}		
			
		}else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}			
	}
	
	public function GetSixMonthTypeSalesByState($type,$state){
		if($this->IsLoggedIn('master_admin')){
			$ma_admin_id = $this->session->userdata['logged_in']['master_admin_id'];
			$data = array(
						'master_admin_id' => $this->session->userdata['logged_in']['master_admin_id'],
						'type' => $type,
						'state'=>$state
					);
			$res['sales'] = $this->MasterAdminModel->LastSixMonthTypeSalesByState($data,$ma_admin_id );		
		    if($res['sales']['success'] == 'true')
			{
				return $res['sales'] = $res['sales']['res_arr'];
			}
			else
			{
				$res['sales'] = ['res_arr'=>''];
				return $res['sales'] = $res['sales']['res_arr'];
			}		
			
		}else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}			
	}
	
	public function GetSixMonthSalesBy(){
		$data = array();
		if($this->IsLoggedIn('master_admin')){
			if(isset($_POST) && !empty($_POST))
			{
				
				if(isset($_POST) && !empty($_POST['state'] && !empty($_POST['city']) &&!empty($_POST['outlet'])))
				{
					$where = array(
						'outlet_id' => $this->input->post('outlet'),
						'master_admin_id' => $this->session->userdata['logged_in']['master_admin_id']
					);
					if(is_array($where['outlet_id'])){
						$where['outlet_id'] = implode(',',$where['outlet_id']);
					}
					
					
					$last_six_months_sales = $this->GetSixMonthTypeSalesByOutlet('service',$where['outlet_id']);
					$last_six_months_prod_sales = $this->GetSixMonthTypeSalesByOutlet('otc',$where['outlet_id']);
										
					$last_six_months_pack_sales = $this->MasterAdminModel->LastSixMonthPackSalesByOutlet($where);
					if(array_search('res_arr',$last_six_months_pack_sales)==false)
					$last_six_months_pack_sales += ['res_arr'=>''];
				
					$last_six_months_pack_sales = $last_six_months_pack_sales['res_arr'];
				}elseif (isset($_POST) && !empty($_POST['state'] && !empty($_POST['city']))){
					$where = array(
						'city' => $this->input->post('city'),
						'master_admin_id' => $this->session->userdata['logged_in']['master_admin_id']
					);
					
					$last_six_months_sales = $this->GetSixMonthTypeSalesByCity('service',$where['city']);
					$last_six_months_prod_sales = $this->GetSixMonthTypeSalesByCity('otc',$where['city']);
					$last_six_months_pack_sales = $this->MasterAdminModel->GetSixMonthPackSalesByCity($where);
					if(array_search('res_arr',$last_six_months_pack_sales)==false)
					$last_six_months_pack_sales += ['res_arr'=>''];
				
					$last_six_months_pack_sales = $last_six_months_pack_sales['res_arr'];
				}elseif (isset($_POST) && !empty($_POST['state'])){
					$where = array(
						'state' => $this->input->post('state'),
						'master_admin_id' => $this->session->userdata['logged_in']['master_admin_id']
					);
					$last_six_months_sales = $this->GetSixMonthTypeSalesByState('service',$where['state']);
					$last_six_months_prod_sales = $this->GetSixMonthTypeSalesByState('otc',$where['state']);
					$last_six_months_pack_sales = $this->MasterAdminModel->GetSixMonthPackSalesByState($where);
					if(array_search('res_arr',$last_six_months_pack_sales)==false)
					$last_six_months_pack_sales += ['res_arr'=>''];
				
					$last_six_months_pack_sales = $last_six_months_pack_sales['res_arr'];
				}else{
					$last_six_months_sales = $this->GetSixMonthSalesByType('service');
					$last_six_months_prod_sales = $this->GetSixMonthSalesByType('otc');
					$last_six_months_pack_sales  = $this->MasterAdminModel->LastSixMonthPackage($this->session->userdata['logged_in']['master_admin_id']);
					
					if(array_search('res_arr',$last_six_months_pack_sales)==false)
					$last_six_months_pack_sales += ['res_arr'=>''];
					
					$last_six_months_pack_sales = $last_six_months_pack_sales['res_arr'];
				}
				
				$data['six_months_service'] = $last_six_months_sales;
				$data['six_months_product'] = $last_six_months_prod_sales;
				$data['six_months_package'] = $last_six_months_pack_sales;
					
				
					$six_months_pack=array();
					$six_months_prod=array();
					$six_months_serv=array();
					$p=0; // package count
					$pr = 0;//product count
					$s = 0;//service count
						
						//unset($data['six_months_service'][5]);
						// print_r($pr);
						 //$this->PrettyPrintArray($data['six_months_service']);
				 		for($i = 1;$i<=6;$i++)
				 		{
				 			$six_month = date('F-y',strtotime(-$i.'month'));
				 			if(!empty($data['six_months_service'])  && $s<count($data['six_months_service']) ){
				 				if($six_month ==$data['six_months_service'][$s]['date'] ){
				 					array_push($six_months_serv,$data['six_months_service'][$s]);
				 					$s++;
				 				}
				 				else{
				 					$six_service = array(
				 						'total_service'=>0,
				 						'service_count'=>0,
				 						'date'=>$six_month
				 					);
				 					array_push($six_months_serv,$six_service);
				 				}
				 			}else{
				 				$six_service = array(
				 					'total_service'=>0,
				 					'service_count'=>0,
				 					'date'=>$six_month
				 				);
				 				array_push($six_months_serv,$six_service);
				 			}
				 			if(!empty($data['six_months_product']) && $pr<count($data['six_months_product']) ){
				 				if($six_month == $data['six_months_product'][$pr]['date']){
				 					array_push($six_months_prod,$data['six_months_product'][$pr]);
				 					$pr++;
				 				}else{
				 					$six_prod = array(
				 						'total_service'=>0,
				 						'service_count'=>0,
				 						'date'=>$six_month
				 					);
				 					array_push($six_months_prod,$six_prod);
				 				}
				 			}else{
				 				$six_prod = array(
				 					'total_service'=>0,
				 					'service_count'=>0,
				 					'date'=>$six_month
				 				);
				 				array_push($six_months_prod,$six_prod);
				 			}
				 			if(!empty($data['six_months_package']) && $p<count($data['six_months_package'])){
				 				if($six_month == $data['six_months_package'][$p]['date']){
				 					array_push($six_months_pack,$data['six_months_package'][$p]);
				 					$p++;
				 				}else{
									$six_pack = array(
									'package_sales'=>0,
									'package_count'=>0,
									'date'=>$six_month
									);
									array_push($six_months_pack,$six_pack);
			 				   }
			 			    }else{
			 				    $six_pack = array(
				 					'package_sales'=>0,
				 					'package_count'=>0,
				 					'date'=>$six_month
				 				);
				 				array_push($six_months_pack,$six_pack);
				 			}
				 		}
				  if(empty($data['six_months_service']) || count($data['six_months_service']) != 15)
						$data['six_months_service'] = $six_months_serv;
					if(empty($data['six_months_product']) || count($data['six_months_product'])!=15)
						$data['six_months_product'] = $six_months_prod;
					if(empty($data['six_months_package']) || count($data['six_months_package'])!=15)
						$data['six_months_package'] = $six_months_pack;
					
		
					$data['six_month_labels'] = array();
					$data['six_month_data_sales'] = array();
					$data['six_month_data_service'] = array();
					$data['six_month_data_prod'] =array();
					$data['six_month_data_pack'] =array();
					$data['six_month_bill_count'] = array();
					if(!empty($data['six_months_service']))
					{
						foreach($data['six_months_service'] as $sales=>$value)
						{
							array_push($data['six_month_labels'],$value['date']);
							array_push($data['six_month_data_sales'],($value['total_service']+$data['six_months_package'][$sales]['package_sales']+$data['six_months_product'][$sales]['total_service']));
							array_push($data['six_month_data_service'],$value['total_service']);
							array_push($data['six_month_data_prod'],$data['six_months_product'][$sales]['total_service']);
							array_push($data['six_month_data_pack'],$data['six_months_package'][$sales]['package_sales']);
							array_push($data['six_month_bill_count'],($value['service_count']+$data['six_months_package'][$sales]['package_count']));
						}
					}

				$data['success'] = "true";
				// $this->PrettyPrintArray($data); 
				header("Content-type: application/json");
				print(json_encode($data, JSON_PRETTY_PRINT));
				die;		
			}
		}else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}
	}
    
    public function GetFifteenSalesBy(){
		if($this->IsLoggedIn('master_admin')){
			if(isset($_POST) && !empty($_POST))
			{	
				if(isset($_POST) && !empty($_POST['state'] && !empty($_POST['city']) &&!empty($_POST['outlet'])))
				{
					$where = array(
						'outlet_id' => $this->input->post('outlet'),
						'master_admin_id' => $this->session->userdata['logged_in']['master_admin_id']
					);
					if(is_array($where['outlet_id'])){
						$where['outlet_id'] = implode(',',$where['outlet_id']);
					}
					$last_fifteen_days_sales = $this->LastFifteenDayTypeSalesByOutlet('service',$where['outlet_id']);
					$last_fifteen_prod_sales = $this->LastFifteenDayTypeSalesByOutlet('otc',$where['outlet_id']);
										
					$last_fifteen_pack_sales = $this->MasterAdminModel->LastFifteenDayPackSalesByOutlet($where);
					if(array_search('res_arr',$last_fifteen_pack_sales)==false)
					$last_fifteen_pack_sales += ['res_arr'=>''];
					$last_fifteen_pack_sales = $last_fifteen_pack_sales['res_arr'];
				}
				elseif (isset($_POST) && !empty($_POST['state'] && !empty($_POST['city'])))
				{
					$where = array(
						'city' => $this->input->post('city'),
						'master_admin_id' => $this->session->userdata['logged_in']['master_admin_id']
					);
					
					$last_fifteen_days_sales = $this->LastFifteenDayTypeSalesByCity('service',$where['city']);
					$last_fifteen_prod_sales = $this->LastFifteenDayTypeSalesByCity('otc',$where['city']);
					$last_fifteen_pack_sales = $this->MasterAdminModel->LastFifteenDayPackSalesByCity($where);
					if(array_search('res_arr',$last_fifteen_pack_sales)==false)
					$last_fifteen_pack_sales += ['res_arr'=>''];
					$last_fifteen_pack_sales = $last_fifteen_pack_sales['res_arr'];
				}
				else
				{
					$where = array(
						'state' => $this->input->post('state'),
						'master_admin_id' => $this->session->userdata['logged_in']['master_admin_id']
					);
					$last_fifteen_days_sales = $this->LastFifteenDayTypeSalesByState('service',$where['state']);
					$last_fifteen_prod_sales = $this->LastFifteenDayTypeSalesByState('otc',$where['state']);
					$last_fifteen_pack_sales = $this->MasterAdminModel->LastFifteenDayPackSalesByState($where);
					if(array_search('res_arr',$last_fifteen_pack_sales)==false)
					$last_fifteen_pack_sales += ['res_arr'=>''];
					$last_fifteen_pack_sales = $last_fifteen_pack_sales['res_arr'];
				}
				$time = strtotime(date("Y-m-d"));
				$p=0; // product count
				$pr = 0;//package count
				$s = 0;//service count
				$fsales = array();
				$fpack = array();
				$fprod = array();		
				for($count = 15;$count>=1;$count--)
				{
					$tt = date("Y-m-d",strtotime("-".$count."day",$time));
					if(!empty($last_fifteen_days_sales)  && $s<count($last_fifteen_days_sales) ){
						if($tt ==$last_fifteen_days_sales[$s]['bill_date'] ){
							array_push($fsales,$last_fifteen_days_sales[$s]);
							$s++;
						}
						else{
							$insert_service = array(
								'total_sales'=>0,
								'bill_date'=>$tt,
								'bill_count'=>0
							);
							array_push($fsales,$insert_service);
						}
					}else{
						$insert_service = array(
							'total_sales'=>0,
							'bill_date'=>$tt,
							'bill_count'=>0
						);
						array_push($fsales,$insert_service);
					}
					
					if(!empty($last_fifteen_pack_sales) && $pr<count($last_fifteen_pack_sales) ){
						if($tt == $last_fifteen_pack_sales[$pr]['pack_date']){
							array_push($fpack,$last_fifteen_pack_sales[$pr]);
							$pr++;
						}else{
							$insert_pack =array(
								'packages'=>0,
								'pack_date'=>$tt,
								'package_count'=>0
							);
							array_push($fpack,$insert_pack);
						}
					}else{
						$insert_pack =array(
							'packages'=>0,
							'pack_date'=>$tt,
							'package_count'=>0
						);
						array_push($fpack,$insert_pack);
					}
					
					if(!empty($last_fifteen_prod_sales) && $p<count($last_fifteen_prod_sales)){
						if($tt == $last_fifteen_prod_sales[$p]['bill_date']){
							array_push($fprod,$last_fifteen_prod_sales[$p]);
							$p++;
						}else{
							$insert_prod=array(
								'total_sales'=>0,
								'bill_date'=>$tt
							);
							array_push($fprod,$insert_prod);
						}
					}else{
						$insert_prod=array(
							'total_sales'=>0,
							'bill_date'=>$tt
						);
						array_push($fprod,$insert_prod);
					}
					
				}
				if(empty($last_fifteen_days_sales) || count($last_fifteen_days_sales) != 15)
					$last_fifteen_days_sales = $fsales;
				if(empty($last_fifteen_pack_sales) || count($last_fifteen_pack_sales)!=15)
					$last_fifteen_pack_sales = $fpack;
				if(empty($last_fifteen_prod_sales) || count($last_fifteen_prod_sales)!=15)
					$last_fifteen_prod_sales = $fprod;
				$data['labels'] = array();
				$data['data_sales'] = array();
				$data['data_service'] = array();
				$data['data_prod'] =array();
				$data['data_pack'] =array();
				$data['bill_count'] = array();
				
				
				if(!empty($last_fifteen_days_sales))
				{
					foreach($last_fifteen_days_sales as $sales=>$value)
					{
						array_push($data['labels'],$value['bill_date']);
						array_push($data['data_sales'],($value['total_sales']+$last_fifteen_pack_sales[$sales]['packages']+$last_fifteen_prod_sales[$sales]['total_sales']));
						array_push($data['data_service'],$value['total_sales']);
						array_push($data['data_prod'],$last_fifteen_prod_sales[$sales]['total_sales']);
						array_push($data['data_pack'],$last_fifteen_pack_sales[$sales]['packages']);
						array_push($data['bill_count'],($value['bill_count']+$last_fifteen_pack_sales[$sales]['package_count']));
					}
				}
				$data['success'] = "true";
				// $this->PrettyPrintArray($data); 
				header("Content-type: application/json");
				print(json_encode($data, JSON_PRETTY_PRINT));
				die;
			}
		}else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}
	}

    public function GetDailyTrendsByOutlet(){
		if($this->IsLoggedIn('master_admin')){
			if(isset($_POST) && !empty($_POST))
			{
				if(isset($_POST) && !empty($_POST['state']) && !empty($_POST['city']) && !empty($_POST['outlet']))
				{ 	// $this->PrettyPrintArray($_POST);
					// exit;
					$data = array(
						'master_admin_id' => $this->session->userdata['logged_in']['master_admin_id'],
						'outlet_id' =>$this->input->post('outlet')
					);
					if(is_array($data['outlet_id'])){
					  $data['outlet_id'] = implode(',',$data['outlet_id']);
					}
					
					$result = array(); 
					$res_service_today = $this->GetSalesByTypeToday('service',$data['outlet_id']);
					$res_prod_today = $this->GetSalesByTypeToday('otc',$data['outlet_id']);
					
					$res_service_current = $this->GetCurrentTrendsByOutlet('Service',$data['outlet_id']);
					$res_prod_current = $this->GetCurrentTrendsByOutlet('otc',$data['outlet_id']);
					$res_service_prev_till = $this->GetPrevTillTrendsByOutlet('Service',$data['outlet_id']);
					$res_prod_prev_till = $this->GetPrevTillTrendsByOutlet('otc',$data['outlet_id']);
					$res_service_prev = $this->GetPrevTrendsByOutlet('Service',$data['outlet_id']);
					$res_prod_prev = $this->GetPrevTrendsByOutlet('otc',$data['outlet_id']);
					
					$res_pack_today = $this->MasterAdminModel->GetOutletTodayPackSales($data);
					$res_pack_today = $res_pack_today['res_arr'];
					
					$res_pack_current = $this->MasterAdminModel->CurrentOutletMonthPackSales($data);
					$res_pack_current =$res_pack_current['res_arr'];
					$res_pack_prev_till = $this->MasterAdminModel->GetOutletPreviousPackTillDate($data);
					$res_pack_prev_till = $res_pack_prev_till['res_arr'];
					$res_pack_prev = $this->MasterAdminModel->GetOutletPreviousPackSales($data);
					$res_pack_prev = $res_pack_prev['res_arr'];
				}
				else if(isset($_POST) && !empty($_POST['state']) && !empty($_POST['city']))
				{
					$data = array(
						'master_admin_id' => $this->session->userdata['logged_in']['master_admin_id'],
						'city' =>$this->input->post('city')
					);
					$result = array(); 
					$res_service_today = $this->GetCitySalesByTypeToday('service',$_POST['city']);
					$res_prod_today = $this->GetCitySalesByTypeToday('otc',$_POST['city']);
					$res_service_current = $this->GetCurrentTrendsByCity('Service',$_POST['city']);
					$res_prod_current = $this->GetCurrentTrendsByCity('Products',$_POST['city']);
					$res_service_prev_till = $this->GetPrevTillTrendsByCity('Service',$_POST['city']);
					$res_prod_prev_till = $this->GetPrevTillTrendsByCity('Products',$_POST['city']);
					$res_service_prev = $this->GetPrevTrendsByCity('Service',$_POST['city']);
					$res_prod_prev = $this->GetPrevTrendsByCity('Products',$_POST['city']);
					$res_pack_today = $this->MasterAdminModel->GetCityTodayPackSales($data);
					$res_pack_today = $res_pack_today['res_arr'];
					$res_pack_current = $this->MasterAdminModel->CurrentCityMonthPackSales($data);
					$res_pack_current =$res_pack_current['res_arr'];
					$res_pack_prev_till = $this->MasterAdminModel->GetCityPreviousPackTillDate($data);
					$res_pack_prev_till = $res_pack_prev_till['res_arr'];
					$res_pack_prev = $this->MasterAdminModel->GetCityPreviousPackSales($data);
					$res_pack_prev = $res_pack_prev['res_arr'];
				}
				else{
					$data = array(
						'master_admin_id' => $this->session->userdata['logged_in']['master_admin_id'],
						'state' =>$this->input->post('state')
					);
					$result = array(); 
					$res_service_today = $this->GetStateSalesByTypeToday('service',$_POST['state']);
					$res_prod_today = $this->GetStateSalesByTypeToday('otc',$_POST['state']);
					$res_service_current = $this->GetCurrentTrendsByState('Service',$_POST['state']);
					$res_prod_current = $this->GetCurrentTrendsByState('Products',$_POST['state']);
					$res_service_prev_till = $this->GetPrevTillTrendsByState('Service',$_POST['state']);
					$res_prod_prev_till = $this->GetPrevTillTrendsByState('Products',$_POST['state']);
					$res_service_prev = $this->GetPrevTrendsByState('Service',$_POST['state']);
					$res_prod_prev = $this->GetPrevTrendsByState('Products',$_POST['state']);
					$res_pack_today = $this->MasterAdminModel->GetStateTodayPackSales($data);
					$res_pack_today = $res_pack_today['res_arr'];
					$res_pack_current = $this->MasterAdminModel->CurrentStateMonthPackSales($data);
					$res_pack_current =$res_pack_current['res_arr'];
					$res_pack_prev_till = $this->MasterAdminModel->GetStatePreviousPackTillDate($data);
					$res_pack_prev_till = $res_pack_prev_till['res_arr'];
					$res_pack_prev = $this->MasterAdminModel->GetStatePreviousPackSales($data);
					$res_pack_prev = $res_pack_prev['res_arr'];
				}
				// $res_bill_serprod_today = $this->MasterAdminModel->GetOutletTodayCountBySerAndProd($data);
				// $res_bill_serprod_today = $res_bill_serprod_today['res_arr'];
				// $res_bill_pack_today = $this->MasterAdminModel->GetOutletTodayCountByPack($data);
				// $res_bill_pack_today = $res_bill_pack_today['res_arr'];
				// $res_bill_current = $this->MasterAdminModel->GetCurrentBillCountByOutlet($data);
				// $res_bill_current =$res_bill_current['res_arr'];
				// $res_bill_prev_till = $this->MasterAdminModel->GetOutletPrevTillBillCount($data);
				// $res_bill_prev_till = $res_bill_prev_till['res_arr'];
				// $res_bill_prev = $this->MasterAdminModel->GetOutletPrevBillCount($data);
				// $res_bill_prev = $res_bill_prev['res_arr'];
				if(empty($res_service_today[0]['total_sales']))
				{
					$res_service_today[0] = ['total_sales'=>0];
					$res_service_today[0] += ['bill_today_count'=>0];
				}
				if(empty($res_prod_today[0]['total_sales']))
				{
					$res_prod_today[0] = ['total_sales'=>0];
					$res_prod_today[0] += ['bill_today_count'=>0];
				}
				if(empty($res_service_current[0]['total_sales']))
				{
					$res_service_current[0] = ['total_sales'=>0];
					$res_service_current[0] += ['bill_current_count'=>0];	
				}
				if(empty($res_prod_current[0]['total_sales']))
				{
					$res_prod_current[0] = ['total_sales'=>0];
					$res_prod_current[0] = ['bill_current_count'=>0];						
				}
				if(empty($res_service_prev_till[0]['total_sales']))
				{
					$res_service_prev_till[0] = ['total_sales'=>0];
					$res_service_prev_till[0] += ['bill_prev_count_till'=>0];
				}
				if(empty($res_prod_prev_till[0]['total_sales']))
				{
					$res_prod_prev_till[0] = ['total_sales'=>0];
					$res_prod_prev_till[0] = ['bill_prev_count_till'=>0];		
				}
				if(empty($res_service_prev[0]['total_sales']))
				{
					$res_service_prev[0] = ['total_sales'=>0];
					$res_service_prev[0] += ['bill_prev_count'=>0];	
				}
				if(empty($res_prod_prev[0]['total_sales']))
				{
					$res_prod_prev[0] += ['total_sales'=>0];	
					$res_prod_prev[0] += ['bill_prev_count'=>0];
				}
				if(empty($res_pack_today[0]['package_sales']))
				{
					$res_pack_today[0] = ['package_sales'=>0];
					$res_pack_today[0] += ['pack_today_count'=>0];	
				}
				if(empty($res_pack_current[0]['packages']))
				{
					$res_pack_current[0] = ['packages'=>0];
					$res_pack_current[0] += ['pack_current_count'=>0];	
				}
				if(empty($res_pack_prev_till[0]['packages']))
				{
					$res_pack_prev_till[0] = ['packages'=>0];
					$res_pack_prev_till[0] += ['pack_prev_count_till'=>0];	
				}
				if(empty($res_pack_prev[0]['packages']))
				{
					$res_pack_prev[0] = ['packages'=>0];
					$res_pack_prev[0] += ['pack_prev_count'=>0];	
				}
				$total_ftd = $res_pack_today[0]['package_sales']+$res_service_today[0]['total_sales']+$res_prod_today[0]['total_sales'];
				$bill_ftd = $res_pack_today[0]['pack_today_count']+$res_service_today[0]['bill_today_count'];
				
				$total_mtd = $res_pack_current[0]['packages']+$res_service_current[0]['total_sales']+$res_prod_current[0]['total_sales'];
				$bill_mtd = $res_pack_current[0]['pack_current_count']+$res_service_current[0]['bill_current_count'];
				
				$total_lmtd = $res_pack_prev_till[0]['packages']+$res_service_prev_till[0]['total_sales']+$res_prod_prev_till[0]['total_sales'];
				$bill_lmtd = $res_pack_prev_till[0]['pack_prev_count_till']+$res_service_prev_till[0]['bill_prev_count_till'];
				
				$total_lmd= $res_pack_prev[0]['packages']+$res_service_prev[0]['total_sales']+$res_prod_prev[0]['total_sales'];
				$bill_lmd = $res_pack_prev[0]['pack_prev_count']+$res_service_prev[0]['bill_prev_count'];
				array_push($result,$res_service_today[0]);
				array_push($result,$res_prod_today[0]);
				array_push($result,$res_service_current[0]);
				array_push($result,$res_prod_current[0]);
				array_push($result,$res_service_prev_till[0]);
				array_push($result,$res_prod_prev_till[0]);
				array_push($result,$res_service_prev[0]);
				array_push($result,$res_prod_prev[0]);
				array_push($result,$res_pack_today[0]);
				array_push($result,$res_pack_current[0]);
				array_push($result,$res_pack_prev_till[0]);
				array_push($result,$res_pack_prev[0]);
				array_push($result,$total_ftd);
				array_push($result,$bill_ftd);
				array_push($result,$total_mtd);
				array_push($result,$bill_mtd);
				array_push($result,$total_lmtd);
				array_push($result,$bill_lmtd);
				array_push($result,$total_lmd);
				array_push($result,$bill_lmd);
				// array_push($result,$res_bill_serprod_today[0]);
				// array_push($result,$res_bill_pack_today[0]);
				// array_push($result,$res_bill_current[0]);
				// array_push($result,$res_bill_prev_till[0]);
				// array_push($result,$res_bill_prev[0]);
				
				// $this->PrettyPrintArray($result);
				// exit;
				header("Content-type: application/json");
				print(json_encode($result, JSON_PRETTY_PRINT));
				die;
			}	
		}else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		
		}
	} 	

  
}
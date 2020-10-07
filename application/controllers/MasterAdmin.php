<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . '/third_party/spout-2.4.3/src/Spout/Autoloader/autoload.php';
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type; 
class MasterAdmin extends CI_Controller {
        
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
    private function GetDataForMasterAdmin($title){
		 $data = array();
         $data['title'] = $title;
		 $data['business_master_admin_packages'] = $this->GetMasterAdminPackages();
		 $data['master_admin_details'] 			 = $this->GetMasterAdminDetails();
		 $data['business_outlet_details'] 		 = $this->GetMasterOutlets();
		// if(isset($this->session->userdata['outlets'])){
        //  $data['selected_outlet']      = $this->GetCurrentOutlet($this->session->userdata['outlets']['current_outlet']);
        // }
        return $data;
    }
	
	private function assignService($outlets,$services){
		$data = array();
		/* Get Service Records */
					$serviceDetails = $this->MasterAdminModel->getSalonPackageDataByIds(implode(',',$services));
					
					$serviceData = array();
					if(!empty($serviceDetails['res_arr'])){
						foreach($serviceDetails['res_arr'] as  $serviceRecords){
							$serviceData[$serviceRecords['service_id']] = array('discount_percentage'=>$serviceRecords['discount_percentage'],
																				'birthday_discount' =>$serviceRecords['birthday_discount'],				
																				'anni_discount' =>$serviceRecords['anni_discount'],				
																				'service_count' =>$serviceRecords['service_count']				
																				);
						}
					}
					
					foreach($outlets as $outletId){
					  foreach($services as $serviceId){	

						/* Check this id exist or not, if already exist then skip else assign */
						$where = array(
							'service_id '       => $serviceId,
						);
						$record = $this->MasterAdminModel->MultiWhereSelect('master_services',$where);
						
						if(isset($record['res_arr']) && !empty($record['res_arr'])){

						/* Check record exist or not if not then insert else skip that for same outlet and service */

				        $checkExistance = $this->MasterAdminModel->MultiWhereSelect('mss_services',array('is_assign_to_outlet'=>'1','outlet_id'=>$outletId,'master_service_id'=>$record['res_arr'][0]['service_id']));	
				       if(isset($checkExistance['res_arr']) && empty($checkExistance['res_arr'])){

							$data[] = array(
								'master_service_id'  		=> $record['res_arr'][0]['service_id'],
								'outlet_id'  			    => $outletId,
								'service_sub_category_id'   => $record['res_arr'][0]['service_sub_category_id'],
								'inventory_type'		 =>$record['res_arr'][0]['inventory_type'],
								'service_name'	 		 =>$record['res_arr'][0]['service_name'],
								'service_price_inr'		 =>$record['res_arr'][0]['service_price_inr'],
								'service_est_time'		 =>$record['res_arr'][0]['service_est_time'],
								'service_description'	 =>$record['res_arr'][0]['service_description'],
								'service_gst_percentage' =>$record['res_arr'][0]['service_gst_percentage'],
								'service_type'		  	=>$record['res_arr'][0]['service_type'],
								'barcode'		 	  	=>$record['res_arr'][0]['barcode'],
								'barcode_id'		  	=>$record['res_arr'][0]['barcode_id'],
								'service_unit'		  	=>$record['res_arr'][0]['service_unit'],
								'service_brand'		  	=>$record['res_arr'][0]['service_brand'],
								'qty_per_item'		  	=>$record['res_arr'][0]['qty_per_item'],
								'inventory_type_id'	 	 =>$record['res_arr'][0]['inventory_type_id'],
								'created_by'		  	=>$this->session->userdata['logged_in']['master_admin_id']
							);
						  }
						} 
						
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
       $this->load->model('MasterAdminModel');
	   $this->load->helper('common_helper');
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
 
  private function GetMasterAdminPackages(){
		if($this->IsLoggedIn('master_admin')){
			$data = $this->MasterAdminModel->MasterAdminPackages($this->session->userdata['logged_in']['master_admin_id']);
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
			$this->LogoutUrl(base_url()."MasterAdmin");
		}	
	}

  private function GetMasterAdminDetails(){
		if($this->IsLoggedIn('master_admin')){
			$data = $this->MasterAdminModel->DetailsById($this->session->userdata['logged_in']['master_admin_id'],'mss_master_admin','master_admin_id');
			if($data['success'] == 'true'){	
				return $data['res_arr'];
			}
		}
		else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}	
	}

	private function GetCurrentOutlet($outlet_id){
		if($this->IsLoggedIn('master_admin')){
			$data = $this->MasterAdminModel->DetailsById($outlet_id,'mss_business_outlets','business_outlet_id');
			if($data['success'] == 'true'){	
				return $data['res_arr'];
			}
		}
		else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}	
	}
	
	private function GetMasterOutlets(){
		if($this->IsLoggedIn('master_admin')){
			$where = array(
				'master_id' 						=> $this->session->userdata['logged_in']['master_admin_id'],
				'business_outlet_status'			=>1
			);
			$data = $this->MasterAdminModel->MultiWhereSelect('mss_business_outlets',$where);
			if($data['success'] == 'true' ){	
				return $data['res_arr'];
			}
		}
		else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}	
	}
	
	private function ActivePackages($outlet_id=0){
		if($this->IsLoggedIn('master_admin')){
			$where = array('master_id'  => $this->session->userdata['logged_in']['master_admin_id']);
			if($outlet_id>0){
				$where['business_outlet_id'] =  $outlet_id;
			}

			$data = $this->MasterAdminModel->GetAllPackages($where);
			
			if($data['success'] == 'true'){	
				return $data['res_arr'];
			}
		}
		else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}		
	}
	
	/*
	private function getServiceDetailsByPackageId($packageId,$outletId){
	
		$returnServiceRecords =$serviceRecords_2 = $serviceIds = $subCategoryIds = array();
		if($this->IsLoggedIn('master_admin')){
			$where = array(
				'master_id' 	   => $this->session->userdata['logged_in']['master_admin_id'],
				'salon_package_id' => $packageId,
				//'outlet_id' => $outletId,
				'is_active' =>'1'
			);
           
			$data = $this->MasterAdminModel->MultiWhereSelect('mss_salon_package_data',$where);
			
			if($data['success'] == 'true' && !empty($data['res_arr'])){	
				foreach($data['res_arr'] as $key=>$serviceRecords_1){
					$serviceIds[] = $serviceRecords_1['master_service_id'];
				}
				
				/* Get Sub Category based on service Id *
				$masterServiceDetails =$this->MasterAdminModel->getMasterServicesByIds(implode(',',$serviceIds));
				
				if($masterServiceDetails['success'] == 'true' && !empty($masterServiceDetails['res_arr'])){	
				  foreach($masterServiceDetails['res_arr'] as $key=>$masterService){
					     $subCategoryIds[] = $masterService['service_sub_category_id'];
						 $serviceRecords_2[$masterService['service_id']] = array('service_sub_category_id'=>$masterService['service_sub_category_id'],
																			'service_price_inr'=>$masterService['service_price_inr'],
																			'service_est_time'=>$masterService['service_est_time'],
																			'service_gst_percentage'=>$masterService['service_gst_percentage'],
																			'qty_per_item'=>$masterService['qty_per_item']
																		  );
					}
					
					/* Merge the service record 1,2 *
					
					foreach($data['res_arr'] as $key=>$serviceDetails){
						$serviceDetails['service_id'] = (int) $serviceDetails['master_service_id'];
						$returnServiceRecords[] = array_merge($serviceDetails,$serviceRecords_2[$serviceDetails['service_id']]);
					}
					
				}
			}
			
			/* Get Category Ids based on SubCategory *
			$subCategoryRecords = array();
			if(!empty($subCategoryIds)){
				$categoryData = $this->MasterAdminModel->getMasterCategoriesBySubCatIds(array('sub_category_id'=>implode(',',$subCategoryIds),'sub_category_is_active'=>TRUE));
				if(!empty($categoryData['res_arr'])){
					foreach($categoryData['res_arr']  as $category){
						$subCategoryRecords[$category['sub_category_id']] = $category['sub_category_category_id'];
					}
				}
			}
			
			$returnServiceRecordsByGroupCount = array();
			if(!empty($returnServiceRecords)){
				/* Group by Count *
				foreach($returnServiceRecords as $key =>$value){
					$value['service_category_id'] = isset($subCategoryRecords[$value['service_sub_category_id']]) ? $subCategoryRecords[$value['service_sub_category_id']] : 0;
					$returnServiceRecordsByGroupCount[$value['service_count']][] = $value;
				}
				
			}
			
			return $returnServiceRecordsByGroupCount;
		}
		else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}		
	} */
	
	private function getServiceDetailsByPackageId($packageId,$outletId,$packageType=''){
	
		$returnServiceRecords =$serviceRecords_2 = $serviceIds = $subCategoryIds = array();
		if($this->IsLoggedIn('master_admin')){
			$where = array(
				'master_id' 	   => $this->session->userdata['logged_in']['master_admin_id'],
				'salon_package_id' => $packageId,
				'is_active' =>'1'
			);
           
			$data = $this->MasterAdminModel->MultiWhereSelect('mss_salon_package_data',$where);
			
			if($data['success'] == 'true' && !empty($data['res_arr'])){	
				foreach($data['res_arr'] as $key=>$serviceRecords_1){
					$serviceIds[] = $serviceRecords_1['master_service_id'];
				}
				
				/* Get Sub Category based on service Id */
				$masterServiceDetails =$this->MasterAdminModel->getMasterServicesByIds(implode(',',$serviceIds));
				
				if($masterServiceDetails['success'] == 'true' && !empty($masterServiceDetails['res_arr'])){	
				  foreach($masterServiceDetails['res_arr'] as $key=>$masterService){
					     $subCategoryIds[] = $masterService['service_sub_category_id'];
						 $serviceRecords_2[$masterService['service_id']] = array('service_sub_category_id'=>$masterService['service_sub_category_id'],
																			'service_price_inr'=>$masterService['service_price_inr'],
																			'service_est_time'=>$masterService['service_est_time'],
																			'service_gst_percentage'=>$masterService['service_gst_percentage'],
																			'qty_per_item'=>$masterService['qty_per_item']
																		  );
					}
					
					/* Merge the service record 1,2 */
					
					foreach($data['res_arr'] as $key=>$serviceDetails){
						$serviceDetails['service_id'] = (int) $serviceDetails['master_service_id'];
						$returnServiceRecords[] = array_merge($serviceDetails,$serviceRecords_2[$serviceDetails['service_id']]);
					}
					
				}
			}
		
			/* Get Category Ids based on SubCategory */
			$subCategoryRecords = array();
			if(!empty($subCategoryIds)){
				$categoryData = $this->MasterAdminModel->getMasterCategoriesBySubCatIds(array('sub_category_id'=>implode(',',$subCategoryIds),'sub_category_is_active'=>TRUE));
				if(!empty($categoryData['res_arr'])){
					foreach($categoryData['res_arr']  as $category){
						$subCategoryRecords[$category['sub_category_id']] = array('service_category_id'=>$category['sub_category_category_id'],'service_category_type'=>$category['category_type']);
					}
				}
			}
			
			$returnServiceRecordsByGroupCount = array();
			
			if(!empty($returnServiceRecords)){
				/* Group by Count */
				foreach($returnServiceRecords as $key =>$value){
					$value['service_category_id']   = isset($subCategoryRecords[$value['service_sub_category_id']]) ? $subCategoryRecords[$value['service_sub_category_id']]['service_category_id'] : 0;
					$value['service_category_type'] = isset($subCategoryRecords[$value['service_sub_category_id']]) ? $subCategoryRecords[$value['service_sub_category_id']]['service_category_type'] : 0;
					if($packageType=='special_membership'){
				    	$returnServiceRecordsByGroupCount[$value['table_id']][$value['group_index']][$value['service_category_type']][] = $value;
					}else{
						$returnServiceRecordsByGroupCount[$value['service_count']][] = $value;
					}
				}
				
			}
			
		    return $returnServiceRecordsByGroupCount;
		}
		else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}		
	}
  
  //Default Page for Masster Admin
   public function index(){
		if($this->IsLoggedIn('master_admin')){
			$data = $this->GetDataForMasterAdmin("Dashboard");
			$master_admin = $this->session->userdata['logged_in']['master_admin_id'];
			$data['current_month_sales'] = $this->MasterAdminModel->CurrentMonthSales($master_admin);
			$data['current_month_sales'] = $data['current_month_sales']['res_arr'];
			$data['previous_month_sales'] = $this->MasterAdminModel->PreviousMonthSales($master_admin);
			$data['previous_month_sales'] = $data['previous_month_sales']['res_arr'];
			if(empty($data['previous_month_sales'][0]['total_sales']))
			{
				$data['previous_month_sales'][0] = ['total_sales'=>0];
			}
			$data['previous_month_total_sales'] = $this->MasterAdminModel->PreviousMonthTotalSales($master_admin);
			$data['previous_month_total_sales'] = $data['previous_month_total_sales']['res_arr'];
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
				// 		$this->PrettyPrintArray($data);
				// 		exit;
			$data['sidebar_collapsed'] = "true";			
			$this->load->view('master_admin/ma_dashboard_view',$data);
		}
		else{
				$data['title'] = "Login";
				$this->load->view('master_admin/ma_login_view');
		}
	}
    //function for logging out the user
    public function Logout(){
        if(isset($this->session->userdata['logged_in']) && !empty($this->session->userdata['logged_in'])){
            $this->session->unset_userdata('logged_in');
            $this->session->unset_userdata('outlets');
            $this->session->sess_destroy();
        }
        redirect(base_url().'MasterAdmin/Login','refresh');
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
            $this->form_validation->set_rules('master_admin_email', 'Email', 'trim|required|max_length[100]');
            $this->form_validation->set_rules('master_admin_password', 'Password', 'trim|required');
            
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
            else 
            {
                $data = array(
                    'master_admin_email'    => $this->input->post('master_admin_email'),
                    'master_admin_password' => $this->input->post('master_admin_password')
                );                
								$result = $this->MasterAdminModel->MasterAdminLogin($data);  
							         
                if ($result['success'] == 'true'){
                    $result = $this->MasterAdminModel->MasterAdminByEmail($data['master_admin_email']);
                    // $this->PrettyPrintArray($result);
                    // exit;
                    if($result['success'] == 'true'){
                        if($data['master_admin_email'] == $result['res_arr']['master_admin_email'] && password_verify($data['master_admin_password'],$result['res_arr']['master_admin_password'])){ 
                            $session_data = array(
                                'master_admin_id'      => $result['res_arr']['master_admin_id'],
                                'master_admin_name'    => $result['res_arr']['master_admin_name'],
                                'master_admin_email'   => $result['res_arr']['master_admin_email'],
                                'user_type'        => 'master_admin'
                            );
                            
                            $this->session->set_userdata('logged_in', $session_data);
                            $this->ReturnJsonArray(true,false,'Valid login!');
                            die;
                        }else{
													$this->ReturnJsonArray(false,true,'Invalid Login Details!');
                            die;
												}
                    }elseif($result['error'] == 'true'){
                        $this->ReturnJsonArray(false,true,'Invalid Credentials');
                        die;
                    }else{
											$this->ReturnJsonArray(false,true,'Invalid Credentials');
                      die;
										}
                }
                elseif($result['error'] == 'true'){
                    $this->ReturnJsonArray(false,true,'No such Master Admin Exist.');
                    die;
                }
            }
        }
        else{
            $data['title'] = "Login";
            $this->load->view('master_admin/ma_login_view',$data);
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
                      
                    $new_password         =  $this->input->post('new_password');
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
                $data = $this->GetDataForMasterAdmin("Reset Password");
                $this->load->view('business_admin/ba_reset_password_view',$data);
            }
        }
        else{
            $this->LogoutUrl(base_url()."BusinessAdmin");
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
						
						
						// print_r($pr);
						// $this->PrettyPrintArray($data['six_months_product']);
				// 		for($i = 1;$i<=6;$i--)
				// 		{
				// 			$six_month = date('F-y',strtotime(-$i.'month'));
				// 			if(!empty($data['six_months_service'])  && $s<count($data['six_months_service']) ){
				// 				if($six_month ==$data['six_months_service'][$s]['date'] ){
				// 					array_push($six_months_serv,$data['six_months_service'][$s]);
				// 					$s++;
				// 				}
				// 				else{
				// 					$six_service = array(
				// 						'total_service'=>0,
				// 						'service_count'=>0,
				// 						'date'=>$six_month
				// 					);
				// 					array_push($six_months_serv,$six_service);
				// 				}
				// 			}else{
				// 				$six_service = array(
				// 					'total_service'=>0,
				// 					'service_count'=>0,
				// 					'date'=>$six_month
				// 				);
				// 				array_push($six_months_serv,$six_service);
				// 			}
				// 			if(!empty($data['six_months_product']) && $pr<count($data['six_months_product']) ){
				// 				if($six_month == $data['six_months_product'][$pr]['date']){
				// 					array_push($six_months_prod,$data['six_months_product'][$pr]);
				// 					$pr++;
				// 				}else{
				// 					$six_prod = array(
				// 						'total_service'=>0,
				// 						'service_count'=>0,
				// 						'date'=>$six_month
				// 					);
				// 					array_push($six_months_prod,$six_prod);
				// 				}
				// 			}else{
				// 				$six_prod = array(
				// 					'total_service'=>0,
				// 					'service_count'=>0,
				// 					'date'=>$six_month
				// 				);
				// 				array_push($six_months_prod,$six_prod);
				// 			}
				// 			if(!empty($data['six_months_package']) && $p<count($data['six_months_package'])){
				// 				if($six_month == $data['last_fifteen_prod_sales'][$p]['product_date']){
				// 					array_push($six_months_pack,$data['last_fifteen_prod_sales'][$p]);
				// 					$p++;
				// 				}else{
				// 					$six_pack = array(
				// 						'package_sales'=>0,
				// 						'package_count'=>0,
				// 						'date'=>$six_month
				// 					);
				// 					array_push($six_months_pack,$six_pack);
				// 				}
				// 			}else{
				// 				$six_pack = array(
				// 					'package_sales'=>0,
				// 					'package_count'=>0,
				// 					'date'=>$six_month
				// 				);
				// 				array_push($six_months_pack,$six_pack);
				// 			}
				// 		}
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
    
    //user permissions
    public function Permissions(){
      if($this->IsLoggedIn('master_admin')){
        $where=array('business_master_admin_id'=>$this->session->userdata['logged_in']['master_admin_id']);
        $data = $this->GetDataForMasterAdmin("User & Permissions"); 
        $data['business_admin']=$this->MasterAdminModel->BusinessAdminPermission($where);
				$data['business_admin']=	$data['business_admin']['res_arr'];
				$data['sidebar_collapsed']="true";
				// $this->PrettyPrintArray($data['business_admin']);    
        $this->load->view('master_admin/ma_permissions_view',$data);
      }else{
       $this->LogoutUrl(base_url()."MasterAdmin");
      }
    }
	//Category permission
	public function EditCategoryPermission(){
		if($this->IsLoggedIn('master_admin')){
			if(isset($_GET) && !empty($_GET)){
				$where = array(
					'business_admin_id'  => $_GET['business_admin_id'],
					'category_edit' => $_GET['category_edit']
				);

				$data = $this->MasterAdminModel->Update($where,'mss_user_permission','business_admin_id');
				if($data['success'] == 'true'){	
					$this->ReturnJsonArray(true,false,"Permission Updated successfully");
					die;	
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}

	}
	public function CreateCategoryPermission(){
		if($this->IsLoggedIn('master_admin')){
			if(isset($_GET) && !empty($_GET)){
				$where = array(
					'business_admin_id'  => $_GET['business_admin_id'],
					'category_create' => $_GET['category_create']
				);

				$data = $this->MasterAdminModel->Update($where,'mss_user_permission','business_admin_id');
				if($data['success'] == 'true'){	
					$this->ReturnJsonArray(true,false,"Permission Updated successfully");
					die;	
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}

	}
	public function DeleteCategoryPermission(){
		if($this->IsLoggedIn('master_admin')){
			if(isset($_GET) && !empty($_GET)){
				$where = array(
					'business_admin_id'  => $_GET['business_admin_id'],
					'category_delete' => $_GET['category_delete']
				);

				$data = $this->MasterAdminModel->Update($where,'mss_user_permission','business_admin_id');
				if($data['success'] == 'true'){	
					$this->ReturnJsonArray(true,false,"Permission Updated successfully");
					die;	
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}

	}
	//Sub Category Permisssion
	public function EditSubCategoryPermission(){
		if($this->IsLoggedIn('master_admin')){
			if(isset($_GET) && !empty($_GET)){
				$where = array(
					'business_admin_id'  => $_GET['business_admin_id'],
					'sub_category_edit' => $_GET['sub_category_edit']
				);

				$data = $this->MasterAdminModel->Update($where,'mss_user_permission','business_admin_id');
				if($data['success'] == 'true'){	
					$this->ReturnJsonArray(true,false,"Permission Updated successfully");
					die;	
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}

	}
	public function CreateSubCategoryPermission(){
		// $this->PrettyPrintArray($_GET);
		if($this->IsLoggedIn('master_admin')){
			if(isset($_GET) && !empty($_GET)){
				$where = array(
					'business_admin_id'  => $_GET['business_admin_id'],
					'sub_category_create' => $_GET['sub_category_create']
				);

				$data = $this->MasterAdminModel->Update($where,'mss_user_permission','business_admin_id');
				if($data['success'] == 'true'){	
					$this->ReturnJsonArray(true,false,"Permission Updated successfully");
					die;	
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}

	}
	public function DeleteSubCategoryPermission(){
		if($this->IsLoggedIn('master_admin')){
			if(isset($_GET) && !empty($_GET)){
				$where = array(
					'business_admin_id'  => $_GET['business_admin_id'],
					'sub_category_delete' => $_GET['sub_category_delete']
				);

				$data = $this->MasterAdminModel->Update($where,'mss_user_permission','business_admin_id');
				if($data['success'] == 'true'){	
					$this->ReturnJsonArray(true,false,"Permission Updated successfully");
					die;	
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}

	}
	//Service Permission
	public function EditServicePermission(){
		if($this->IsLoggedIn('master_admin')){
			if(isset($_GET) && !empty($_GET)){
				$where = array(
					'business_admin_id'  => $_GET['business_admin_id'],
					'service_edit' => $_GET['service_edit']
				);

				$data = $this->MasterAdminModel->Update($where,'mss_user_permission','business_admin_id');
				if($data['success'] == 'true'){	
					$this->ReturnJsonArray(true,false,"Permission Updated successfully");
					die;	
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}

	}
	public function CreateServicePermission(){
		// $this->PrettyPrintArray($_GET);
		if($this->IsLoggedIn('master_admin')){
			if(isset($_GET) && !empty($_GET)){
				$where = array(
					'business_admin_id'  => $_GET['business_admin_id'],
					'service_create' => $_GET['service_create']
				);

				$data = $this->MasterAdminModel->Update($where,'mss_user_permission','business_admin_id');
				if($data['success'] == 'true'){	
					$this->ReturnJsonArray(true,false,"Permission Updated successfully");
					die;	
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}

	}
	public function DeleteServicePermission(){
		if($this->IsLoggedIn('master_admin')){
			if(isset($_GET) && !empty($_GET)){
				$where = array(
					'business_admin_id'  => $_GET['business_admin_id'],
					'service_delete' => $_GET['service_delete']
				);

				$data = $this->MasterAdminModel->Update($where,'mss_user_permission','business_admin_id');
				if($data['success'] == 'true'){	
					$this->ReturnJsonArray(true,false,"Permission Updated successfully");
					die;	
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}

	}

	//Product Permission
	public function EditProductPermission(){
		if($this->IsLoggedIn('master_admin')){
			if(isset($_GET) && !empty($_GET)){
				$where = array(
					'business_admin_id'  => $_GET['business_admin_id'],
					'product_edit' => $_GET['product_edit']
				);

				$data = $this->MasterAdminModel->Update($where,'mss_user_permission','business_admin_id');
				if($data['success'] == 'true'){	
					$this->ReturnJsonArray(true,false,"Permission Updated successfully");
					die;	
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}

	}
	public function CreateProductPermission(){
		// $this->PrettyPrintArray($_GET);
		if($this->IsLoggedIn('master_admin')){
			if(isset($_GET) && !empty($_GET)){
				$where = array(
					'business_admin_id'  => $_GET['business_admin_id'],
					'product_create' => $_GET['product_create']
				);

				$data = $this->MasterAdminModel->Update($where,'mss_user_permission','business_admin_id');
				if($data['success'] == 'true'){	
					$this->ReturnJsonArray(true,false,"Permission Updated successfully");
					die;	
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}

	}
	public function DeleteProductPermission(){
		if($this->IsLoggedIn('master_admin')){
			if(isset($_GET) && !empty($_GET)){
				$where = array(
					'business_admin_id'  => $_GET['business_admin_id'],
					'product_delete' => $_GET['product_delete']
				);

				$data = $this->MasterAdminModel->Update($where,'mss_user_permission','business_admin_id');
				if($data['success'] == 'true'){	
					$this->ReturnJsonArray(true,false,"Permission Updated successfully");
					die;	
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}

	}

	//RawMaterial Permission
	public function EditRawMaterialPermission(){
		if($this->IsLoggedIn('master_admin')){
			if(isset($_GET) && !empty($_GET)){
				$where = array(
					'business_admin_id'  => $_GET['business_admin_id'],
					'raw_material_edit' => $_GET['raw_material_edit']
				);

				$data = $this->MasterAdminModel->Update($where,'mss_user_permission','business_admin_id');
				if($data['success'] == 'true'){	
					$this->ReturnJsonArray(true,false,"Permission Updated successfully");
					die;	
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}

	}
	public function CreateRawMaterialPermission(){
		// $this->PrettyPrintArray($_GET);
		if($this->IsLoggedIn('master_admin')){
			if(isset($_GET) && !empty($_GET)){
				$where = array(
					'business_admin_id'  => $_GET['business_admin_id'],
					'raw_material_create' => $_GET['raw_material_create']
				);

				$data = $this->MasterAdminModel->Update($where,'mss_user_permission','business_admin_id');
				if($data['success'] == 'true'){	
					$this->ReturnJsonArray(true,false,"Permission Updated successfully");
					die;	
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}

	}
	public function DeleteRawMaterialPermission(){
		if($this->IsLoggedIn('master_admin')){
			if(isset($_GET) && !empty($_GET)){
				$where = array(
					'business_admin_id'  => $_GET['business_admin_id'],
					'raw_material_delete' => $_GET['raw_material_delete']
				);

				$data = $this->MasterAdminModel->Update($where,'mss_user_permission','business_admin_id');
				if($data['success'] == 'true'){	
					$this->ReturnJsonArray(true,false,"Permission Updated successfully");
					die;	
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}

	}
	//Master Admin menu management
	public function MenuManagement(){
		if ($this->IsLoggedIn('master_admin')) {
		$data = $this->GetDataForMasterAdmin("Menu Management");
		$data['business_admin_details'] = $this->MasterAdminModel->GetBusinessDetails($this->session->userdata['logged_in']['master_admin_id']);
		$data['business_admin_details'] = $data['business_admin_details']['res_arr'];
		// $this->PrettyPrintArray($data);
		$this->load->view('master_admin/ma_menu_management_view', $data);
		} else {
		$this->LogoutUrl(base_url() . "MasterAdmin");
		}
	}
    
	//Inventory & Stock
	public function Inventory(){
		if($this->IsLoggedIn('master_admin')){
				$data = $this->GetDataForMasterAdmin("Inventory MAnagement");
					
				$this->load->view('master_admin/ma_inventory_view',$data);
		}else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}
	}

	//Employee MAnagement
	public function Employee(){
		if($this->IsLoggedIn('master_admin')){
				$data = $this->GetDataForMasterAdmin("Employee Management");				   
				$this->load->view('master_admin/ma_employee_view',$data);
		}else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}
	}

	//jg code
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
// 	private function GetSalesByTypeToday($data,$outlet)
// 	{
// 		if($this->IsLoggedIn('master_admin')){
// 			$where = array(
// 				'type' => $data,
// 				'master_admin_id' => $this->session->userdata['logged_in']['master_admin_id'],
// 				'outlet_id' => $outlet
// 			);
// 			$res = $this->MasterAdminModel->GetOutletTodaySalesByType($where);
// 			return $res['res_arr'];
// 		}else{
// 			$this->LogoutUrl(base_url()."MasterAdmin");
		 
// 		}
// 	}
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
// 	private function GetPrevTillTrendsByOutlet($data,$outlet)
// 	{
// 		if($this->IsLoggedIn('master_admin')){
// 			$where = array(
// 				'type' => $data,
// 				'master_admin_id' => $this->session->userdata['logged_in']['master_admin_id'],
// 				'outlet_id' => $outlet
// 			);
// 			$res = $this->MasterAdminModel->OutletPreviousMonthTillSalesByType($where);
// 			return $res['res_arr']; 
// 		}else{
// 			$this->LogoutUrl(base_url()."MasterAdmin");
// 		}
// 	}
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
					$result = array(); 
					$res_service_today = $this->GetSalesByTypeToday('service',$_POST['outlet']);
					$res_prod_today = $this->GetSalesByTypeToday('otc',$_POST['outlet']);
					$res_service_current = $this->GetCurrentTrendsByOutlet('Service',$_POST['outlet']);
					$res_prod_current = $this->GetCurrentTrendsByOutlet('Products',$_POST['outlet']);
					$res_service_prev_till = $this->GetPrevTillTrendsByOutlet('Service',$_POST['outlet']);
					$res_prod_prev_till = $this->GetPrevTillTrendsByOutlet('Products',$_POST['outlet']);
					$res_service_prev = $this->GetPrevTrendsByOutlet('Service',$_POST['outlet']);
					$res_prod_prev = $this->GetPrevTrendsByOutlet('Products',$_POST['outlet']);
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
					$res_service_today[0] += ['bill_today_count'=>0];
				}
				if(empty($res_service_current[0]['total_sales']))
				{
					$res_service_current[0] = ['total_sales'=>0];
					$res_service_current[0] += ['bill_current_count'=>0];	
				}
				if(empty($res_prod_current[0]['total_sales']))
				{
					$res_prod_current[0] = ['total_sales'=>0];	
				}
				if(empty($res_service_prev_till[0]['total_sales']))
				{
					$res_service_prev_till[0] = ['total_sales'=>0];
					$res_service_prev_till[0] += ['bill_prev_count_till'=>0];
				}
				if(empty($res_prod_prev_till[0]['total_sales']))
				{
					$res_prod_prev_till[0] = ['total_sales'=>0];	
				}
				if(empty($res_service_prev[0]['total_sales']))
				{
					$res_service_prev[0] = ['total_sales'=>0];
					$res_service_prev[0] += ['bill_prev_count'=>0];	
				}
				if(empty($res_prod_prev[0]['total_sales']))
				{
					$res_prod_prev[0] += ['total_sales'=>0];	
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
	public function BulkUploadServices()
  {
    if ($this->IsLoggedIn('master_admin')) {
      // $this->PrettyPrintArray($_POST);
      if (isset($_FILES["file"]["type"])) {

        // check file name is not empty
        if (!empty($_FILES['file']['name'])) {

          // Get File extension eg. 'xlsx' to check file is excel sheet
          $pathinfo = pathinfo($_FILES["file"]["name"]);
        //   $this->PrettyPrintArray($pathinfo);
          // check file has extension xlsx, xls and also check file is not empty
          if (($pathinfo['extension'] == 'xlsx' || $pathinfo['extension'] == 'xls') && $_FILES['file']['size'] > 0) {


            // $this->PrettyPrintArray($business);
            // Temporary file name
            $inputFileName = $_FILES['file']['tmp_name'];

            // Read excel file by using ReadFactory object.
            $reader = ReaderFactory::create(Type::XLSX);

            //Open file
            $reader->open($inputFileName);
            $count = 1;
            $successInserts = 0;
            $errorInserts = 0;
            $file_content = json_decode(json_encode($_POST['file_contents']));
            // $this->PrettyPrintArray($file_content);
            // Number of sheet in excel file
            foreach ($reader->getSheetIterator() as $sheet) {
              // Number of Rows in Excel sheet
              foreach ($sheet->getRowIterator() as $row) {
                // It reads data after header.
                if ($count > 1) {
					
                  $data = array(
                    'service_sub_category_id' => $row[0],
                    'service_name'             => $row[1],
                    'service_price_inr'       => $row[2],
                    'service_est_time'         => $row[3],
                    'service_description'     => $row[4],
                    'service_gst_percentage'   => $row[5]
                  );

                  $result = $this->BusinessAdminModel->Insert($data, 'mss_services');

                  if ($result['success'] == 'true') {
                    $successInserts++;
                  } elseif ($result['error'] == 'true') {
                    $errorInserts++;
                  }
                }
                $count++;
              }
            }
            $reader->close();

            $this->ReturnJsonArray(true, false, "File uploaded with successful entries : " . $successInserts . ", errors : " . $errorInserts . "");
            die;
          } else {
            $this->ReturnJsonArray(false, true, "Please Select Valid Excel File!");
            die;
          }
        } 
        else {
          $this->ReturnJsonArray(false, true, "Please Select Excel File!");
          die;
        }
      }
    } else {
      $this->LogoutUrl(base_url() . "SuperAdmin");
    }
  }
  public function BulkUploadOTC()
  {
    if ($this->IsLoggedIn('master_admin')) {
      if (isset($_FILES["file"]["type"])) {

        // check file name is not empty
        if (!empty($_FILES['file']['name'])) {

          // Get File extension eg. 'xlsx' to check file is excel sheet
          $pathinfo = pathinfo($_FILES["file"]["name"]);

          // check file has extension xlsx, xls and also check file is not empty
          if (($pathinfo['extension'] == 'xlsx' || $pathinfo['extension'] == 'xls') && $_FILES['file']['size'] > 0) {

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
                    'inventory_type'          => $row[1],
                    'service_name'            => $row[2],
                    'service_price_inr'       => $row[3],
                    'service_is_active'       => $row[4],
                    'service_est_time'        => $row[5],
                    'service_description'     => $row[6],
                    'service_gst_percentage'  => $row[7],
                    'service_type'            => 'otc',
                    'barcode'                 => $row[8],
                    'barcode_id'              => $row[9],
                    'service_unit'            => $row[10],
                    'service_brand'           => $row[11],
                    'qty_per_item'            => $row[12]
                  );

                  $result = $this->BusinessAdminModel->Insert($data, 'mss_services');

                  if ($result['success'] == 'true') {
                    $successInserts++;
                  } elseif ($result['error'] == 'true') {
                    $errorInserts++;
                  }
                }
                $count++;
              }
            }
            $reader->close();

            $this->ReturnJsonArray(true, false, "File uploaded with successful entries : " . $successInserts . ", errors : " . $errorInserts . "");
            die;
          } else {
            $this->ReturnJsonArray(false, true, "Please Select Valid Excel File!");
            die;
          }
        } else {
          $this->ReturnJsonArray(false, true, "Please Select Excel File!");
          die;
        }
      }
    } else {
      $this->LogoutUrl(base_url() . "SuperAdmin");
    }
  }

  public function BulkUploadCategory()
  {
    if ($this->IsLoggedIn('master_admin')) {
      // $this->PrettyPrintArray($_POST);
      // $this->PrettyPrintArray($_FILES);
      if (isset($_FILES["file"]["type"])) {
        // check file name is not empty
        
        if (!empty($_FILES['file']['name'])) {

          // Get File extension eg. 'xlsx' to check file is excel sheet
          $pathinfo = pathinfo($_FILES["file"]["name"]);
          
          // check file has extension xlsx, xls and also check file is not empty
          if (($pathinfo['extension'] == 'xlsx' || $pathinfo['extension'] == 'xls' ) && $_FILES['file']['size'] > 0) {
            $file_content = json_decode(json_encode($_POST['file_contents']));
            $business_outlet = explode(",", $file_content[0]);
            $business = array();
            foreach ($business_outlet as $outlet_id => $key) {
              $business_details = $this->MasterAdminModel->DetailsById($key, 'mss_business_outlets', 'business_outlet_id');
              array_push($business, $business_details['res_arr']);
              // array_push($business,$business_details['res_arr']['business_outlet_business_admin']);
            }
            // Temporary file name
            $inputFileName = $_FILES['file']['tmp_name'];

            // Read excel file by using ReadFactory object.
            $reader = ReaderFactory::create(Type::XLSX);

            //Open file
            $reader->open($inputFileName);
            $count = 1;
            $successInserts = 0;
            $errorInserts = 0;
            //Number of outlet
            foreach ($business as $business) {
              // Number of sheet in excel file
              foreach ($reader->getSheetIterator() as $sheet) {
                // Number of Rows in Excel sheet
                foreach ($sheet->getRowIterator() as $row) {
                  // It reads data after header.
                  if ($count > 1) {

                    $data = array(
                      'category_name'               => $row[0],
                      'category_type'               => $row[1],
                      'category_description'        => $row[2],
                      'category_business_admin_id'  => 0,
					  'master_id'					=> $this->session->userdata['logged_in']['master_admin_id'],
                      'category_business_outlet_id' => $business['business_outlet_id']
                    );

                    $result = $this->BusinessAdminModel->Insert($data, 'master_categories');

                    if ($result['success'] == 'true') {
                      $successInserts++;
                    } elseif ($result['error'] == 'true') {
                      $errorInserts++;
                    }
                  }
                  $count++;
                }
              }
            }
            $reader->close();

            $this->ReturnJsonArray(true, false, "File uploaded with successful entries : " . $successInserts . ", errors : " . $errorInserts . "");
            die;
          } else {
            $this->ReturnJsonArray(false, true, "Please Select Valid Excel File!");
            die;
          }
        } else {
          $this->ReturnJsonArray(false, true, "Please Select Excel File!");
          die;
        }
      }
    } else {
      $this->LogoutUrl(base_url() . "SuperAdmin");
    }
  }


  public function BulkUploadSubCategory()
  {
    if ($this->IsLoggedIn('master_admin')) {
      if (isset($_FILES["file"]["type"])) {
        // $this->PrettyPrintArray($_FILES);
        // check file name is not empty
        if (!empty($_FILES['file']['name'])) {

          // Get File extension eg. 'xlsx' to check file is excel sheet
          $pathinfo = pathinfo($_FILES["file"]["name"]);

          // check file has extension xlsx, xls and also check file is not empty
          if (($pathinfo['extension'] == 'xlsx' || $pathinfo['extension'] == 'xls') && $_FILES['file']['size'] > 0) {

            // Temporary file name
            $inputFileName = $_FILES['file']['tmp_name'];

            // Read excel file by using ReadFactory object.
            $reader = ReaderFactory::create(Type::XLSX);

            //Open file
            $reader->open($inputFileName);
            $count = 1;
            $successInserts = 0;
            $errorInserts = 0;
			// $this->PrettyPrintArray($_POST['file_contents']);

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

                  $result = $this->BusinessAdminModel->Insert($data, 'mss_sub_categories');

                  if ($result['success'] == 'true') {
                    $successInserts++;
                  } elseif ($result['error'] == 'true') {
                    $errorInserts++;
                  }
                }
                $count++;
              }
            }
            $reader->close();

            $this->ReturnJsonArray(true, false, "File uploaded with successful entries : " . $successInserts . ", errors : " . $errorInserts . "");
            die;
          } else {
            $this->ReturnJsonArray(false, true, "Please Select Valid Excel File!");
            die;
          }
        } else {
          $this->ReturnJsonArray(false, true, "Please Select Excel File!");
          die;
        }
      }
    } else {
      $this->LogoutUrl(base_url() . "BusinessAdmn");
    }
  }

  public function BulkUploadRawMaterial()
  {
    if ($this->IsLoggedIn('master_admin')) {
      if (isset($_FILES["file"]["type"])) {

        // check file name is not empty
        if (!empty($_FILES['file']['name'])) {

          // Get File extension eg. 'xlsx' to check file is excel sheet
          $pathinfo = pathinfo($_FILES["file"]["name"]);
          // check file has extension xlsx, xls and also check file is not empty
          if (($pathinfo['extension'] == 'xlsx' || $pathinfo['extension'] == 'xls') && $_FILES['file']['size'] > 0) {
            $file_content = json_decode(json_encode($_POST['file_contents']));
            $business_outlet = explode(",", $file_content[0]);
            $business = array();
            foreach ($business_outlet as $outlet_id => $key) {
              $business_details = $this->MasterAdminModel->DetailsById($key, 'mss_business_outlets', 'business_outlet_id');
              array_push($business, $business_details['res_arr']);
              // array_push($business,$business_details['res_arr']['business_outlet_business_admin']);
            }
            // Temporary file name
            $inputFileName = $_FILES['file']['tmp_name'];

            // Read excel file by using ReadFactory object.
            $reader = ReaderFactory::create(Type::XLSX);

            //Open file
            $reader->open($inputFileName);
            $count = 1;
            $successInserts = 0;
            $errorInserts = 0;
            // Number of business_outlet
            foreach ($business as $business) {
              // Number of sheet in excel file
              foreach ($reader->getSheetIterator() as $sheet) {
                // Number of Rows in Excel sheet
                foreach ($sheet->getRowIterator() as $row) {
                  // It reads data after header.
                  if ($count > 1) {

                    $data = array(
                      'raw_material_name'   => $row[0],
                      'raw_material_unit'    => $row[1],
                      'raw_material_brand'   => $row[2],
                      'raw_material_business_admin_id'   => $business['business_outlet_business_admin'],
                      'raw_material_business_outlet_id' => $business['business_outlet_id']
                    );

                    $result = $this->BusinessAdminModel->Insert($data, 'mss_raw_material_categories');

                    if ($result['success'] == 'true') {	
                      $successInserts++;
                    } elseif ($result['error'] == 'true') {
                      $errorInserts++;
                    }
                  }
                  $count++;
                }
              }
            }

            $reader->close();

            $this->ReturnJsonArray(true, false, "File uploaded with successful entries : " . $successInserts . ", errors : " . $errorInserts . "");
            die;
          } else {
            $this->ReturnJsonArray(false, true, "Please Select Valid Excel File!");
            die;
          }
        } else {
          $this->ReturnJsonArray(false, true, "Please Select Excel File!");
          die;
        }
      }
    } else {
      $this->LogoutUrl(base_url() . "BusinessAdmn");
    }
  }
  public function GetCategoriesPublic(){
		if($this->IsLoggedIn('master_admin')){
			// $this->PrettyPrintArray($_POST['outlet_id']);
			if(isset($_POST['outlet_id']))
			$data['business_admin_details'] = $this->MasterAdminModel->GetBusinessDetails($this->session->userdata['logged_in']['master_admin_id']);
			$data['business_admin_details'] = $data['business_admin_details']['res_arr'];
			$outlet_id = $_POST['outlet_id'];
			$j =0;
			$categories = array();
			$business_details = array();
			foreach($outlet_id as $outlet=>$value)
			{
				$business_det = $this->MasterAdminModel->DetailsById($value,'mss_business_outlets','business_outlet_id');
				$categories_det = $this->GetCategories($business_det['res_arr']);
				// array_push($business_details,$business_det['res_arr']);
				array_push($business_details,$categories_det);
			}
			// $this->PrettyPrintArray($business_details);
			
				for($j;$j<count($business_details);$j++)
				{
					$categories = array_merge($business_details[$j],$categories);
				}
			// $this->PrettyPrintArray($s);
			$this->ReturnJsonArray(true,false,$categories);
			die;
		}
		else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}		
  }
	private function GetCategories($data){
		if($this->IsLoggedIn('master_admin')){
			$where = array(
				'category_business_admin_id' => $data['business_outlet_business_admin'],
				'category_is_active'         => TRUE,
				'category_business_outlet_id'=> $data['business_outlet_id']
			);	
			$data = $this->MasterAdminModel->FetchCategories($where);
			if($data['success'] == 'true'){	
				return $data['res_arr'];
			}
		}
		else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}		
	}
	
	/* Get Master categories */
	private function GetMasterCategories($masterId){
		if($this->IsLoggedIn('master_admin')){	
			$where = array(
				'master_id' => $masterId,
				'category_is_active'         => TRUE
			);	
			$data = $this->MasterAdminModel->MultiWhereSelect('master_categories',$where);
			if($data['success'] == 'true'){	
				return $data['res_arr'];
			}
		}else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}	
		
	}
	/* Get Master Sub categories */
	private function GetMasterSubCategories($masterId){
		if($this->IsLoggedIn('master_admin')){	
			$data = array(
				'master_id' 		      => $masterId,
				'sub_category_is_active'  => TRUE
			);	
			$data = $this->MasterAdminModel->MasterSubCategories($data);
			if($data['success'] == 'true'){	
				return $data['res_arr'];
			}
		}else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}	
	}
	/* Get Master Services */
	private function GetMasterServices($masterId){
		if($this->IsLoggedIn('master_admin')){	
			$data = array(
				'master_id' 		      => $masterId,
				'service_is_active'       => TRUE,
				'service_type' 			  => 'service'	
			);	
			$data = $this->MasterAdminModel->MasterServices($data);
			if($data['success'] == 'true'){	
				return $data['res_arr'];
			}
		}else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}	
	}
    
	/* Get Master Products/OTC */
	private function GetMasterProducts($masterId){
		if($this->IsLoggedIn('master_admin')){	
			$data = array(
				'master_id' 		      => $masterId,
				'service_is_active'       => TRUE,
				'service_type' 			  => 'otc'	
			);	
			$data = $this->MasterAdminModel->MasterServices($data);
			if($data['success'] == 'true'){	
				return $data['res_arr'];
			}
		}else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}	
	}
	
	public function GetSubCategoriesPublic(){
		if($this->IsLoggedIn('master_admin')){
			// $this->PrettyPrintArray($_POST['outlet_id']);
			if(isset($_POST['outlet_id'])){
			$data['business_admin_details'] = $this->MasterAdminModel->GetBusinessDetails($this->session->userdata['logged_in']['master_admin_id']);
			$data['business_admin_details'] = $data['business_admin_details']['res_arr'];
			
			$outlet_id = $_POST['outlet_id'];
			$j =0;
			$sub_categories = array();
			$business_details = array();
			foreach($outlet_id as $outlet=>$value)
			{
				$business_det = $this->MasterAdminModel->DetailsById($value,'mss_business_outlets','business_outlet_id');
				$sub_categories_det = $this->GetSubCategories($business_det['res_arr']);
				// array_push($business_details,$business_det['res_arr']);
				array_push($business_details,$sub_categories_det);
			}
			// $this->PrettyPrintArray($business_details);
			
				for($j;$j<count($business_details);$j++)
				{
					$sub_categories = array_merge($business_details[$j],$sub_categories);
				}
			// $this->PrettyPrintArray($s);
			$this->ReturnJsonArray(true,false,$sub_categories);
			die;
		}
		}
		else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}		
  }
	private function GetSubCategories($data){
		if($this->IsLoggedIn('master_admin')){
			$where = array(
				'category_business_admin_id' => $data['business_outlet_business_admin'],
				'category_is_active'         => TRUE,
				'category_business_outlet_id'=> $data['business_outlet_id']
			);	
			$data = $this->MasterAdminModel->FetchSubCategories($where);
			if($data['success'] == 'true'){	
				return $data['res_arr'];
			}
		}
		else{
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
	//19-05-2020
	private function GetServicesExport($data){
		if($this->IsLoggedIn('master_admin')){
			$where = array(
				'category_business_admin_id'  => $data['business_outlet_business_admin'],
				'service_is_active'           => TRUE,
				'category_business_outlet_id' => $data['business_outlet_id'],
				'service_type'                => 'service'
			);

			$data = $this->MasterAdminModel->ServicesExport($where);
			if($data['success'] == 'true'){	
				return $data['res_arr'];
			}
		}
		else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}		
	}
	private function GetProductsExport($data){
		if($this->IsLoggedIn('master_admin')){
			$where = array(
				'category_business_admin_id'  => $data['business_outlet_business_admin'],
				'service_is_active'           => TRUE,
				'category_business_outlet_id' => $data['business_outlet_id'],
				'service_type'                => 'otc'
			);

			$data = $this->MasterAdminModel->ServicesExport($where);
			if($data['success'] == 'true'){	
				return $data['res_arr'];
			}
		}
		else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}		
	}
	public function GetServicePublic(){
		if($this->IsLoggedIn('master_admin')){
			// $this->PrettyPrintArray($_POST);
			$service = array();
			$product = array();
			$i = 0;
			if(isset($_POST) && !empty($_POST)){
				foreach($_POST['outlet_id'] as $k=>$v ){
					$business_admin = $this->MasterAdminModel->DetailsById($v,'mss_business_outlets','business_outlet_id');
					
					$value = $business_admin['res_arr'];
					
					$where = array(
						'business_outlet_business_admin'=>$value['business_outlet_business_admin'],
						'business_outlet_id'=>$value['business_outlet_id']
					);
					$services = $this->GetServicesExport($where);
					if($services !=NULL)
						$service = array_merge($service,$services);
				}
				
			}
			else{
				$data = $this->GetDataForMasterAdmin("Menu Management");
				$data['business_admin_details'] = $this->MasterAdminModel->GetBusinessDetails($this->session->userdata['logged_in']['master_admin_id']);
				$data['business_admin_details'] = $data['business_admin_details']['res_arr'];
			
				foreach($data['business_admin_details'] as $key=>$value)
				{
					
					$where = array(
						'business_outlet_business_admin'=>$value['business_admin_id'],
						'business_outlet_id'=>$value['business_outlet_id']
					);
					$services = $this->GetServicesExport($where);
					if($services !=NULL)
						$service = array_merge($service,$services);

				}
			}
			$data['services'] = $service;
			$this->ReturnJsonArray(true,false,$data['services']);
			die;
		}
		else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}	
	}
	public function DownloadService(){
		if($this->IsLoggedIn('master_admin')){
		   if(isset($_POST) && !empty($_POST)){
			  $returnArray = $downloadReturnArray = array();
			  $postServiceId = $this->input->post('master_service_id');
			  if(!empty($postServiceId)){
				  $serviceIds =  implode(',',$postServiceId);
				  
			  }else{
				  $serviceIds = ""; /* Get for All */
			  }
			  $outletIds = $this->input->post('outlet_ids');
			  
			  $where['master_id']  =  $this->session->userdata['logged_in']['master_admin_id'];
			  $where['service_id'] = $serviceIds;
			  $where['service_is_active'] = TRUE;
			  $where['service_type'] = $this->input->post('type');
            if(!empty($outletIds)){
			  $where['outlet_ids'] = implode(',',$outletIds);
			}
			 $result = $this->MasterAdminModel->DownloadMasterServices($where);
			  if(!empty($result['res_arr'])){
				   $outletIds = array();
				   foreach($result['res_arr'] as $key=>$serviceDetails){
					   if(!in_array($serviceDetails['outlet_id'],$outletIds)) {
						   $outletIds[] = $serviceDetails['outlet_id'];
					   }
				   }
				   $outletIds = implode(',',$outletIds);
				   $outletData = array();
				  /* Get Outlet Details */
				   $outletResult = $this->MasterAdminModel->GetOutletDetailsByIds($outletIds,$where['master_id']);
				   if(!empty($outletResult['res_arr'])){
					  foreach($outletResult['res_arr'] as $outlet){
						  $outletData[$outlet['business_outlet_id']] = $outlet['business_outlet_name'];
					  }
				   }
				  foreach($result['res_arr'] as $key=>$serviceDetails){
					  $returnArray['S.No.'] =  $key+1;
					  $returnArray['Outlet'] =  isset($outletData[$serviceDetails['outlet_id']]) ? $outletData[$serviceDetails['outlet_id']] : "";
					  $returnArray['Name'] =  $serviceDetails['service_name'];
					  $returnArray['Price(B/tax)'] =  $serviceDetails['service_price_inr']+($serviceDetails['service_gst_percentage']*$serviceDetails['service_price_inr']/100) ;
					  $returnArray['GST%'] =  $serviceDetails['service_gst_percentage'];
					  $returnArray['MRP(Rs)'] =  $serviceDetails['service_price_inr'];
					  $returnArray['Description'] =  $serviceDetails['service_description'];
					  
					  $returnArray['Est. Time'] =  $serviceDetails['service_est_time'];
					  $returnArray['Type'] =  $serviceDetails['service_type'];
					  $returnArray['Category'] =  $serviceDetails['category_name'];
					  $returnArray['Sub-Category'] =  $serviceDetails['sub_category_name'];
					  $downloadReturnArray[] =  $returnArray;
				  }
				  $this->ReturnJsonArray(true,false,$downloadReturnArray);
				  die;	
			  }else{
				  $this->ReturnJsonArray(false,true,"Records not found!");
				  die;
			  }
		   }
		}else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}	
	}
	
	public function GetProductPublic(){
		if($this->IsLoggedIn('master_admin')){
			// $this->PrettyPrintArray($_POST);
			$product = array();
			$i = 0;
			if(isset($_POST) && !empty($_POST)){
				
				foreach( $_POST['outlet_id'] as $k=>$v ){
					$business_admin = $this->MasterAdminModel->DetailsById($v,'mss_business_outlets','business_outlet_id');
					$value = $business_admin['res_arr'];
					$where = array(
						'business_outlet_business_admin'=>$value['business_outlet_business_admin'],
						'business_outlet_id'=>$value['business_outlet_id']
					);
					$products = $this->GetProductsExport($where);
			     //   $this->PrettyPrintArray($products);
					$product = array_merge($product,$products);
				}
				
			}
			else{
				$data = $this->GetDataForMasterAdmin("Menu Management");
				$data['business_admin_details'] = $this->MasterAdminModel->GetBusinessDetails($this->session->userdata['logged_in']['master_admin_id']);
				$data['business_admin_details'] = $data['business_admin_details']['res_arr'];
			
				foreach($data['business_admin_details'] as $key=>$value)
				{
					
					$where = array(
						'business_outlet_business_admin'=>$value['business_admin_id'],
						'business_outlet_id'=>$value['business_outlet_id']
					);
					$products = $this->GetProductsExport($where);
					 if(isset($products) && !empty($products))
						$product = array_merge($product,$products);

				}
			}
			$data['products'] = $product;
			$this->ReturnJsonArray(true,false,$data['products']);
			die;
		}
		else{
			$this->LogoutUrl(base_url()."");
		}	
	}

    /**
	 *
	 * @author	: Pinky Sahukar
	 * @Function : Master Admin menu management
	*/
	public function MenuManagementNew(){
		if ($this->IsLoggedIn('master_admin')) {
			
		$data = $this->GetDataForMasterAdmin("Menu Management");
		
		$data['categories']    = $this->GetMasterCategories($this->session->userdata['logged_in']['master_admin_id']);
		$data['subCategories'] = $this->GetMasterSubCategories($this->session->userdata['logged_in']['master_admin_id']);
		$data['services']      = $this->GetMasterServices($this->session->userdata['logged_in']['master_admin_id']);
		$data['products']      = $this->GetMasterProducts($this->session->userdata['logged_in']['master_admin_id']);
		
		
		$data['business_admin_details'] = $this->MasterAdminModel->GetBusinessDetails($this->session->userdata['logged_in']['master_admin_id']);
		$data['business_admin_details'] = $data['business_admin_details']['res_arr'];
		
		/* get  */
		 $where['master_id']  = $this->session->userdata['logged_in']['master_admin_id'];
		 $where['service_id'] = "";
		 $where['service_is_active'] = TRUE;
		 $where['service_type'] = 'service';
		 $result = $this->MasterAdminModel->DownloadMasterServices($where);
		 $outletIds = array();
		 if(!empty($result['res_arr'])){
			  foreach($result['res_arr'] as $key=>$serviceDetails){
				   if(!in_array($serviceDetails['outlet_id'],$outletIds)) {
					   $outletIds[$serviceDetails['master_service_id']][] = $serviceDetails['outlet_id'];
				   }
			   }
		  }
		$data['assignOutlets'] = $outletIds;
		//$this->PrettyPrintArray($data);
		
		$this->load->view('master_admin/ma_menu_management_view_new', $data);
		} else {
		$this->LogoutUrl(base_url() . "MasterAdmin");
		}
	}
	
	
	/**
	 *
	 * @author	: Pinky Sahukar
	 * @Function : Add category in master category table 
	*/

	public function AddCategory(){
			if($this->IsLoggedIn('master_admin')){
				if(isset($_POST) && !empty($_POST)){
					//$categoryBusinessDetails = json_decode($this->input->post('categoryBusinessDetails'));
					
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
					
					$data= array(
						'master_id'						=> $this->session->userdata['logged_in']['master_admin_id'],
						'category_name' 				=> $this->input->post('category_name'),
						'category_description' 			=> $this->input->post('category_description'),
						'category_business_admin_id' 	=> 0,
						'category_business_outlet_id' 	=> 0,
						'category_type'         		=> $this->input->post('category_type')
					);
						
					$result = $this->MasterAdminModel->Insert($data,'master_categories');
						
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
				$this->LogoutUrl(base_url()."MasterAdmin/");
			}
		}
	
	/**
	 *
	 * @author	: Pinky Sahukar
	 * @Function : Delete Category as Temporary 
	*/	
	public function DeactivateCategory(){
		if($this->IsLoggedIn('master_admin')){
			if(isset($_POST) && !empty($_POST)){
				if($this->input->post('activate') == 'false' && $this->input->post('deactivate') == 'true'){
					//Activate the Employee
					$data = array(
					  "category_id"          => $this->input->post('category_id'),
					"category_is_active"   => 0
					);
					
					$status = $this->MasterAdminModel->DeactiveMasterCategory($data['category_id']);
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
			$this->LogoutUrl(base_url()."MasterAdmin");
		}
	}
	
	/**
	 *
	 * @author	: Pinky Sahukar
	 * @Function : Edit Category 
	*/	
	public function EditCategory(){
		if($this->IsLoggedIn('master_admin')){
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
						'category_name' 		=> $this->input->post('category_name'),
						'category_description' 	=> $this->input->post('category_description'),
						'category_type' 	=> $this->input->post('category_type')
					);

					$result = $this->MasterAdminModel->Update($data,'master_categories','category_id');
						
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
			$this->LogoutUrl(base_url()."MasterAdmin");
		}
	}
	
	/**
	 *
	 * @author	: Pinky Sahukar
	 * @Function : Get Category details based on cat Id
	*/	
	public function GetCategory(){
		if($this->IsLoggedIn('master_admin')){
			if(isset($_GET) && !empty($_GET)){
				$data = $this->MasterAdminModel->DetailsById($_GET['category_id'],'master_categories','category_id');
				header("Content-type: application/json");
				print(json_encode($data['res_arr'], JSON_PRETTY_PRINT));
				die;
			}
		}
		else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}
	}	
	
	/**
	 *
	 * @author	: Pinky Sahukar
	 * @Function : Add Sub category in master Sub category table 
	*/	
	public function AddSubCategory(){
		if($this->IsLoggedIn('master_admin')){
			if(isset($_POST) && !empty($_POST)){
				$this->form_validation->set_rules('sub_category_name', 'Sub-Category Name', 'trim|required|max_length[100]');
				$this->form_validation->set_rules('sub_category_description', 'Sub-Category Description', 'trim');
				$this->form_validation->set_rules('category_id', 'Category Name', 'trim|required');
				
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
					    'master_id'					=> $this->session->userdata['logged_in']['master_admin_id'],
						'sub_category_name' 		=> $this->input->post('sub_category_name'),
						'sub_category_description' 	=> $this->input->post('sub_category_description'),
						'sub_category_category_id'  => $this->input->post('category_id')
					);

					$result = $this->MasterAdminModel->Insert($data,'mss_sub_categories');
						
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
			$this->LogoutUrl(base_url()."MasterAdmin");
		}
	}
	
	/**
	 *
	 * @author	: Pinky Sahukar
	 * @Function : Add Sub category in master Sub category table 
	*/	
	public function GetSubCategoriesByCategoryType(){
    
		if($this->IsLoggedIn('master_admin')){
			if(isset($_GET) && !empty($_GET)){
				$where = array(
					  'category_type'        => $_GET['category_type'],
					  'master_id' 			 => $this->session->userdata['logged_in']['master_admin_id'],
					  'category_is_active'   => TRUE
				);
				
				$data = $this->MasterAdminModel->MultiWhereSelect('master_categories',$where);
				header("Content-type: application/json");
				print(json_encode($data['res_arr'], JSON_PRETTY_PRINT));
				die;
			}
		}
		else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}
	}
	
	/**
	 *
	 * @author	: Pinky Sahukar
	 * @Function : Get Sub category from master Sub category table 
	*/	
	public function GetSubCategory(){
		if($this->IsLoggedIn('master_admin')){
			if(isset($_GET) && !empty($_GET)){
				$data = array(
					'master_id' 		      => $this->session->userdata['logged_in']['master_admin_id'],
					'sub_category_is_active'  => TRUE
				);	
				
				$data = $this->MasterAdminModel->MasterSubCategories($data,$_GET['sub_category_id']);
				
				header("Content-type: application/json");
				print(json_encode($data['res_arr'][0], JSON_PRETTY_PRINT));
				die;
			}
		}
		else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}
	}
    
	/**
	 *
	 * @author	: Pinky Sahukar
	 * @Function : Update Sub Category
	*/	
	public function EditSubCategory(){
		if($this->IsLoggedIn('master_admin')){
			if(isset($_POST) && !empty($_POST)){
				$this->form_validation->set_rules('sub_category_name', 'Sub-Category Name', 'trim|required|max_length[100]');
				$this->form_validation->set_rules('sub_category_description', 'Sub-Category Description', 'trim');
				$this->form_validation->set_rules('category_id', 'Category Name', 'trim');
				
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
						'sub_category_name' 		=> $this->input->post('sub_category_name'),
						'sub_category_description' 	=> $this->input->post('sub_category_description'),
						'sub_category_category_id'  => $this->input->post('category_id')
					);

					$result = $this->MasterAdminModel->Update($data,'mss_sub_categories','sub_category_id');
						
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
			$this->LogoutUrl(base_url()."MasterAdmin");
		}
	}
	
    /**
	 *
	 * @author	: Pinky Sahukar
	 * @Function : Delete Sub Category as Temporary 
	*/	
	public function DeactivateSubCategory(){
		if($this->IsLoggedIn('master_admin')){
			if(isset($_POST) && !empty($_POST)){
				if($this->input->post('activate') == 'false' && $this->input->post('deactivate') == 'true'){
					//Activate the Employee
					$data = array(
					  "sub_category_id"          => $this->input->post('sub_category_id'),
					  "sub_category_is_active"   => FALSE
					);

					$status = $this->MasterAdminModel->MasterDeactiveSubCategory($data['sub_category_id']);
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
			$this->LogoutUrl(base_url()."MasterAdmin");
		}
	}
 
    /**
	 *
	 * @author	: Pinky Sahukar
	 * @Function : Get Sub categories based on category Id
	*/	
    public function GetSubCategoriesByCatId(){
		if($this->IsLoggedIn('master_admin')){
			if(isset($_GET) && !empty($_GET)){
				$where = array(
					'sub_category_category_id' => $_GET['category_id'],
					'sub_category_is_active'   => TRUE,
					'master_id' => $this->session->userdata['logged_in']['master_admin_id']
				);
				
				$data = $this->MasterAdminModel->MultiWhereSelect('mss_sub_categories',$where);
				header("Content-type: application/json");
				print(json_encode($data['res_arr'], JSON_PRETTY_PRINT));
				die;
			}
		}
		else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}
	}
	
	/**
	 *
	 * @author	: Pinky Sahukar
	 * @Function : Get Sub categories based on category Ids (multiple ids)
	*/	
    public function GetSubCategoriesByCatIds(){
		if($this->IsLoggedIn('master_admin')){
			if(isset($_GET) && !empty($_GET)){
				$where = array(
					'sub_category_category_id' => implode(',',$_GET['category_id']),
					'sub_category_is_active'   => TRUE
				);
				$returnData =  array();
				$data = $this->MasterAdminModel->getMasterSubCategoriesByIds($where);
				if(!empty($data['res_arr'])){
				  foreach($data['res_arr'] as $key=>$value){	
					$returnData[$value['category_id']][] = $value;
				  }
				}else{
					$returnData = array('status'=>true,'message'=>'Records not found!');
				}
				
			}else{
					$returnData = array('status'=>true,'message'=>'Records not found!');
			}
			echo json_encode($returnData);
		}
		else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}
	}
	
	
	/**
	 *
	 * @author	: Pinky Sahukar
	 * @Function : Get Services Based on Sub category Id
	*/	
	public function GetServicesBySubCatId(){
		if($this->IsLoggedIn('master_admin')){
			if(isset($_GET) && !empty($_GET)){
				$where = array(
					'service_sub_category_id'  => $_GET['sub_category_id'],
					'service_is_active'   => TRUE
				);
				
				$data = $this->MasterAdminModel->MultiWhereSelect('master_services',$where);
				header("Content-type: application/json");
				print(json_encode($data['res_arr'], JSON_PRETTY_PRINT));
				die;
			}
		}
		else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}
	}
	
	/**
	 *
	 * @author	: Pinky Sahukar
	 * @Function : Get Services Based on Sub category Ids (Multiple ids)
	*/	
	public function GetServicesBySubCatIds(){
		if($this->IsLoggedIn('master_admin')){
			if(isset($_GET) && !empty($_GET)){
				$categoryType = array();
				foreach($_GET['category_type'] as $type){
					if($type=='Service'){
						$categoryType[] = "'".'service'."'";
					}elseif($type=='Products'){
						$categoryType[] = "'".'otc'."'";
					}
				}
				$returnServices = array();
				$categoryType    = (!empty($categoryType)) ? implode(',',$categoryType) : "";
				$subCategoryIds  = implode(',',$_GET['sub_category_id']);
				$isSubCategoryDetails = TRUE; /* if you want to get Sub Category Details with service then SET it as TRUE else FALSE */
				if(!empty($subCategoryIds)){
					$data = $this->MasterAdminModel->getMasterServicesBySubCatIds($subCategoryIds,$categoryType,$isSubCategoryDetails);
					if(!empty($data['res_arr'])){
						foreach($data['res_arr'] as $services){
							$returnServices[$services['service_sub_category_id']][] = $services;
						}
					}
				}else{
					$returnServices = array('status'=>true,'message'=>'Records not found!');
				}
				echo json_encode($returnServices);
			}
		}
		else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}
	}
	
	/**
	 *
	 * @author	: Pinky Sahukar
	 * @Function : Get Services Based on Sub category Ids for Multiple
	*/	
	public function GetServicesBySubCatMultipleIds(){
		if($this->IsLoggedIn('master_admin')){
			if(isset($_GET) && !empty($_GET)){
				$servicesIds =  implode(',',$_GET['sub_category_id']);
			
				$data = $this->MasterAdminModel->getMasterServicesBySubCatIds($servicesIds);

				header("Content-type: application/json");
				print(json_encode($data['res_arr'], JSON_PRETTY_PRINT));
				die;
			}
		}
		else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}
	}
		
	/**
	 *
	 * @author	: Pinky Sahukar
	 * @Function : Service Tab : Add New Service in master service table
	*/
	public function AddService(){
		if($this->IsLoggedIn('master_admin')){
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
						'service_name' 			=> $this->input->post('service_name'),
						'service_price_inr' 	=> getActualPrice($this->input->post('service_price_inr')),
						'service_est_time' 		=> $this->input->post('service_est_time'),
						'service_description' 	=> $this->input->post('service_description'),
						'service_gst_percentage' 	=> getActualPrice($this->input->post('service_gst_percentage')),
						'service_sub_category_id' 	=> $this->input->post('service_sub_category_id'),
						'service_type'				=> 'service',	
						'created_by'				=> $this->session->userdata['logged_in']['master_admin_id'],	
					);
				
					$result = $this->MasterAdminModel->Insert($data,'master_services');
						
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
			$this->LogoutUrl(base_url()."MasterAdmin");
		}	
	}
	
	/**
	 *
	 * @author	: Pinky Sahukar
	 * @Function : Service Tab : Edit Service
	*/
	public function EditService(){
		if($this->IsLoggedIn('master_admin')){
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
						'service_id'    => $this->input->post('service_id'),
						'service_name' 	=> $this->input->post('service_name'),
						'service_price_inr' 	=> getActualPrice($this->input->post('service_price_inr')),
						'service_est_time' 	=> $this->input->post('service_est_time'),
						'service_description' 	=> $this->input->post('service_description'),
						'service_gst_percentage' 	=> getActualPrice($this->input->post('service_gst_percentage')),
						'service_sub_category_id' 	=> $this->input->post('service_sub_category_id'),
						'service_type'				=> 'service',	
						'created_by'				=> $this->session->userdata['logged_in']['master_admin_id'],
					);

					$result = $this->MasterAdminModel->Update($data,'master_services','service_id');
						
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
			$this->LogoutUrl(base_url()."MasterAdmin");
		}	
	}
    
	/**
	 *
	 * @author	: Pinky Sahukar
	 * @Function : Service Tab : Delete Service as Temporary
	*/
	public function DeactivateService(){
		if($this->IsLoggedIn('master_admin')){
			if(isset($_POST) && !empty($_POST)){
				if($this->input->post('activate') == 'false' && $this->input->post('deactivate') == 'true'){
					$data = array(
					  "service_id"          => $this->input->post('service_id'),
					  "service_is_active"   => FALSE
					);

					$status = $this->MasterAdminModel->Update($data,'master_services','service_id');
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
			$this->LogoutUrl(base_url()."MasterAdmin");
		}
	}
	
	/**
	 *
	 * @author	: Pinky Sahukar
	 * @Function : Service Tab : get Service data from master service table
	*/
	public function GetService(){
		if($this->IsLoggedIn('master_admin')){
			if(isset($_GET) && !empty($_GET)){
				$data = array(
					'master_id' 		      => $this->session->userdata['logged_in']['master_admin_id'],
					'service_is_active'       => TRUE,
					'service_type' 			  => $_GET['service_type']	
				);	
				$data = $this->MasterAdminModel->MasterServices($data,$_GET['service_id']);
				 //$this->PrettyPrintArray($data);
				header("Content-type: application/json");
				print(json_encode($data['res_arr'][0], JSON_PRETTY_PRINT));
				die;
			}
		}
		else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}
	}
	
	/**
	 *
	 * @author	: Pinky Sahukar
	 * @Function : Product Tab : Add New product data into master service table
	*/
	public function AddOTCService(){
        if($this->IsLoggedIn('master_admin')){
            if(isset($_POST) && !empty($_POST)){
                $this->form_validation->set_rules('otc_item_name', 'otc Item Name', 'trim|required|max_length[100]');
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

                	   $barcode_id = $this->MasterAdminModel->DetailsById($_POST['otc_barcode'],'master_services','barcode');
                        // $barcode_id = $barcode_id['res_arr']; 
						$barcode_id=count($barcode_id)-3;
						
						$inv=0;
						if($_POST['otc_inventory_type'] == 'Raw Material'){
							$inv=1;
						}else{
							$inv=2;
						}
					
                        //$barcode_id = $this->MasterAdminModel->DetailsById($_POST['otc_barcode'],'master_services','barcode');
                        //$barcode_id = $barcode_id['res_arr']; 
						//$barcode_id=count($barcode_id)-3;
												
						$inv=0;
						if($_POST['otc_inventory_type'] == 'Raw Material'){
							$inv=1;
						}else{
							$inv=2;
						}
						foreach($_POST['qty_per_item'] as $key=>$value){
                            $data = array(
                                'inventory_type'                => $this->input->post('otc_inventory_type'),
                                'service_name'                          => $this->input->post('otc_item_name'),
                                'service_sub_category_id'   => $this->input->post('otc_sub_category_id'),
                                'service_brand'                         => $this->input->post('otc_brand'),
                                'barcode'       => $this->input->post('otc_barcode'),
                                'barcode_id'    =>$this->input->post('otc_barcode')."-".$barcode_id,
                                'qty_per_item'                          => $_POST['qty_per_item'][$key],
                                'service_price_inr'                 => $_POST['otc_price_inr'][$key],
                                'service_gst_percentage'        => $_POST['otc_gst_percentage'][$key],
                                'service_unit'                          => $_POST['otc_unit'][$key],
                                'service_type'              => "otc"
                            );
                            // $this->PrettyPrintArray($data);
                            $result = $this->MasterAdminModel->Insert($data,'mss_services');
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
            $this->LogoutUrl(base_url()."MasterAdmin");
        }   
    }
	
	/**
	 *
	 * @author	: Pinky Sahukar
	 * @Function : Product Tab : Edit product data 
	*/
	 public function EditOTCService(){
        if($this->IsLoggedIn('master_admin')){
            if(isset($_POST) && !empty($_POST)){
                $this->form_validation->set_rules('otc_item_name', 'otc Item Name', 'trim|required|max_length[100]');
                $this->form_validation->set_rules('otc_brand', 'Otc brand', 'trim|required|max_length[100]');
                $this->form_validation->set_rules('otc_unit', 'Otc unit', 'trim|required|max_length[50]');
                $this->form_validation->set_rules('otc_gst_percentage', 'OTC GST Percentage', 'trim|required');
				$this->form_validation->set_rules('otc_sub_category_id', 'Otc Sub-Category Name', 'trim|required');
                $this->form_validation->set_rules('otc_price_inr', 'OTC Gross Price', 'trim|required');
                //$this->form_validation->set_rules('otc_inventory_type', 'Inventory Type', 'trim|required');
                //$this->form_validation->set_rules('otc_barcode', 'Barcode', 'trim|required');
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
                        'service_name'          => $this->input->post('otc_item_name'),
                        'service_brand'         => $this->input->post('otc_brand'),
                        'service_sub_category_id'=>$this->input->post('otc_sub_category_id'),
                        'service_unit'          => $this->input->post('otc_unit'),
                        'service_id'          	=> $this->input->post('otc_service_id'),
                        'barcode'   			=>  $this->input->post('otc_barcode'),
                        'inventory_type'    	=>  $this->input->post('otc_inventory_type'),
                        'service_price_inr'     => getActualPrice($this->input->post('otc_price_inr')),
                        'service_gst_percentage'=> getActualPrice($this->input->post('otc_gst_percentage')),
                        'qty_per_item'      => $this->input->post('sku_size')
                    );
                    $result = $this->MasterAdminModel->Update($data,'master_services','service_id');
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
            $this->LogoutUrl(base_url()."MasterAdmin");
        }   
    }
	
	/**
	 *
	 * @author	: Pinky Sahukar
	 * @Function : Outlet Management & Add new outlet
	*/
	public function AddOutlet(){
		if($this->IsLoggedIn('master_admin')){
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
						'business_outlet_city' 		=> $this->input->post('business_outlet_city'),
						'business_outlet_country' 	=> "India",
						'business_outlet_email' 	=> $this->input->post('business_outlet_email'),
						'business_outlet_bill_header_msg' 	=> $this->input->post('business_outlet_bill_header_msg'),
						'business_outlet_bill_footer_msg' 	=> $this->input->post('business_outlet_bill_footer_msg'),
						'business_outlet_facebook_url' 		=> $this->input->post('business_outlet_facebook_url'),
						'business_outlet_instagram_url' 	=> $this->input->post('business_outlet_instagram_url'),
						'business_outlet_latitude' 		=> $this->input->post('business_outlet_latitude'),
						'business_outlet_longitude' 	=> $this->input->post('business_outlet_longitude'),
						'business_outlet_sender_id'		=>$this->input->post('business_outlet_sender_id'),
						'business_outlet_google_my_business_url'=>strtolower($this->input->post('business_outlet_google_my_business_url')),
						'api_key'=>$this->input->post('api_key'),
						'master_id' 						=> $this->session->userdata['logged_in']['master_admin_id'],
						'business_outlet_business_admin' 	=> 0
					);
					$result = $this->MasterAdminModel->Insert($data,'mss_business_outlets');
						
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
				$data = $this->GetDataForMasterAdmin("Add Outlet");
				$this->load->view('master_admin/ma_add_outlet_view',$data);
			}
		}
		else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}	
	}
	
	/**
	 *
	 * @author	: Pinky Sahukar
	 * @Function : Outlet Management : Get outlet detail for the given Id
	*/
	public function GetMasterBusinessOutlet(){
		if($this->IsLoggedIn('master_admin')){
			if(isset($_GET) && !empty($_GET)){
				$data = $this->MasterAdminModel->DetailsById($_GET['business_outlet_id'],'mss_business_outlets','business_outlet_id');
				header("Content-type: application/json");
				print(json_encode($data['res_arr'], JSON_PRETTY_PRINT));
				die;
			}
		}
		else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}
	}
	/**
	 *
	 * @author	: Pinky Sahukar
	 * @Function : Outlet Management : Update outlet
	*/
	public function EditOutlet(){
		if($this->IsLoggedIn('master_admin')){
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
					
					$result = $this->MasterAdminModel->Update($data,'mss_business_outlets','business_outlet_id');
						
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
			$this->LogoutUrl(base_url()."MasterAdmin");
		}	
	}

    public function GetCategoriesByCategoryType(){
		if($this->IsLoggedIn('master_admin')){
			if(isset($_GET) && !empty($_GET)){
				$where = array(
					'category_type' 		=> $_GET['category_type'],
					'category_is_active'   => TRUE,
					'master_id' 			=>  $this->session->userdata['logged_in']['master_admin_id']
				);
				$data = $this->MasterAdminModel->MultiWhereSelect('master_categories',$where);
				
				header("Content-type: application/json");
				print(json_encode($data['res_arr'], JSON_PRETTY_PRINT));
				die;
			}
		}
		else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}
	}
	
	 public function GetCategoriesByCategoryTypes(){
		if($this->IsLoggedIn('master_admin')){
			if(isset($_GET) && !empty($_GET)){
				$where = array(
					//'category_type' 		=> $_GET['category_type'],
					'category_is_active'   => TRUE,
					'master_id' 			=>  $this->session->userdata['logged_in']['master_admin_id']
				);
				$data = $this->MasterAdminModel->MultiWhereSelect('master_categories',$where);
				if(!empty($data['res_arr'])){
					$returnData = array('Service'=>array(),'Products'=>array());
					foreach($data['res_arr'] as $category){
						if(in_array('Service',$_GET['category_type']) && $category['category_type']=='Service'){
							$returnData['Service'][] = array('category_id'=>$category['category_id'],'category_name'=>$category['category_name']);
						}elseif(in_array('Products',$_GET['category_type']) && $category['category_type']=='Products'){
							$returnData['Products'][] = array('category_id'=>$category['category_id'],'category_name'=>$category['category_name']);
						}
					}
				}
				
				header("Content-type: application/json");
				print(json_encode($returnData, JSON_PRETTY_PRINT));
				die;
			}
		}
		else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}
	}
	
	
	
	
	/**
	 *
	 * @author	: Pinky Sahukar
	 * @Function : Package Management & Add new Package into Master Table 
	*/
	public function MasterAdminAddPackage(){

		if($this->IsLoggedIn('master_admin')){

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
				$this->form_validation->set_rules('salon_package_outlet[]', 'Outlet', 'trim|required');
				$this->form_validation->set_rules('total_no_of_services', 'Total No. of Services', 'trim|required');
				$this->form_validation->set_rules('package_end_date', 'Package End Date', 'trim|required');
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
						'salon_package_name'        => $this->input->post('salon_package_name'),
						'salon_package_price'       => round($this->input->post('salon_package_price'),2),
						'service_gst_percentage'    => round($this->input->post('salon_package_gst'),2),
						'salon_package_upfront_amt' => round($this->input->post('salon_package_upfront_amt'),2),
						'salon_package_validity'    => $this->input->post('salon_package_validity'),
						'salon_package_type' 	    => $this->input->post('salon_package_type'),
						'business_admin_id'         => 0,
						'master_id'				    => $this->session->userdata['logged_in']['master_admin_id'],
						'business_outlet_id' 	    => 0,
						'salon_package_date' 		=> date('Y-m-d'),
						'salon_package_end_date' 	=> $this->input->post('package_end_date'),
						'total_count_of_services' 	=> $this->input->post('total_no_of_services')
					);
					
					$outletIds 		 = $this->input->post('salon_package_outlet');
					$masterId 		 = $this->session->userdata['logged_in']['master_admin_id'];
					$packageId 		 = $this->input->post('packageId');
					$packageOutletId = $this->input->post('packageOutletId');
			
					if($data['salon_package_type'] == "Wallet"){
						//Only one insert required
						if(empty($this->input->post("virtual_wallet_money_absolute")) && empty($this->input->post("virtual_wallet_money_percentage"))){
							$this->ReturnJsonArray(false,true,"Please fill virtual money value!");
							die;
						}else{
							if(!empty($this->input->post("virtual_wallet_money_absolute")) && $this->input->post("virtual_wallet_money_absolute")>0){
								$data['virtual_wallet_money'] = $this->input->post("virtual_wallet_money_absolute");
							}elseif(!empty($this->input->post("virtual_wallet_money_percentage")) && $this->input->post("virtual_wallet_money_percentage")>0){
								$data['virtual_wallet_money'] = (($this->input->post("virtual_wallet_money_percentage")/100) * $data['salon_package_upfront_amt']) ;
							}
						}
							
							if(!empty($data['virtual_wallet_money'])){
																
								/* if package Id has 0 value then Insert new package else update */
								
								if($packageId>0 && $packageId!=""){
									
									$result = $this->MasterAdminModel->UpdateWalletPackageForSalon($data,$outletIds,$masterId,$packageId);
									$message = $result['res_arr']['message']!="" ? $result['res_arr']['message'] : "Package Updated successfully!";

								}else{
									$result = $this->MasterAdminModel->AddWalletPackageForSalon($data,$outletIds,$masterId);
									
									/* Associate Package with multiple outlet*/ 
									$packageId = $result['res_arr']['insert_id'];
									$message = ($result['res_arr']['message']!="") ? $result['res_arr']['message'] : "Package added successfully!";
								}
								
								//$this->MasterAdminModel->AssignPackageToMultipleOutlet(array('master_id'=>$data['master_id'],'package_id'=>$packageId),$outletIds);
								
								if($result['success'] == 'true'){
									$this->ReturnJsonArray(true,false,$message);
									die;
								}elseif($result['error'] == 'true'){
									$this->ReturnJsonArray(false,true,$result['message']);
									die;
								}
							}

					}
					elseif($data['salon_package_type'] == "Services"){
						$data['salon_package_type_selected'] = $data['salon_package_type'];
						$data['service_id_index'] =  $this->input->post('service_id_index');
						
						$services = $this->input->post('service_id');
						$counts   = $this->input->post('count_service');
						if(!empty($services) && !empty($counts)){
							    if($packageId>0 && $packageId!=""){
									
									$result = $this->MasterAdminModel->UpdateServicePackageForSalon($data,$services,$counts,$outletIds,$masterId,$packageId);
									$message = $result['res_arr']['message']!="" ? $result['res_arr']['message'] : "Package Updated successfully!";

								}else{
									$result = $this->MasterAdminModel->AddServicePackageForSalon($data,$services,$counts,$outletIds,$masterId);
									/* Associate Package with multiple outlet*/ 
									$packageId = $result['res_arr']['insert_id'];
									$message = "Package added successfully!";
									//$this->MasterAdminModel->AssignPackageToMultipleOutlet(array('master_id'=>$data['master_id'],'package_id'=>$packageId),$outletIds);
							   }	 
								
							if($result['success'] == 'true'){
								$this->ReturnJsonArray(true,false,$message);
								die;
							}elseif($result['error'] == 'true'){
								$this->ReturnJsonArray(false,true,$result['message']);
								die;
							}
						}else{
							$this->ReturnJsonArray(false,true,"Wrong way of data filling!");
							die;
						}
					}
					elseif($data['salon_package_type'] == "Discount"){
						$data['salon_package_type_selected'] = $data['salon_package_type'];
						$data['service_id_index'] =  $this->input->post('discount_id_index');
						
						$services = $this->input->post('service_id');
						$discounts =  $this->input->post('discount');

						$counts = $this->input->post('count_discount');
						
						if(!empty($services) && !empty($discounts) && !empty($counts)){
							
						  if($packageId>0 && $packageId!=""){
								
								$result = $this->MasterAdminModel->UpdateDiscountPackageForSalon($data,$services,$discounts,$counts,$outletIds,$masterId,$packageId);
								$message = $result['res_arr']['message']!="" ? $result['res_arr']['message'] : "Package Updated successfully!";

							}else{	

								$result = $this->MasterAdminModel->AddDiscountPackageForSalon($data,$services,$discounts,$counts,$outletIds,$masterId);
								/* Associate Package with multiple outlet*/ 
								$packageId = $result['res_arr']['insert_id'];
								$message = "Package added successfully!";
							}

							//$this->MasterAdminModel->AssignPackageToMultipleOutlet(array('master_id'=>$data['master_id'],'package_id'=>$packageId),$outletIds);
							
							if($result['success'] == 'true'){
								$this->ReturnJsonArray(true,false,$message);
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
						$data['salon_package_type_selected'] = $data['salon_package_type'];
						$data['salon_package_type'] 		 = 'Services';
						$data['service_sub_category_bulk_index'] =  $this->input->post('service_sub_category_bulk_index');
						
						$sub_categories = $this->input->post('service_sub_category_bulk'); 
						$counts = $this->input->post('count_service_subcategory_bulk');
						
						
							if($packageId>0 && $packageId!=""){
										
								$result = $this->MasterAdminModel->UpdateServiceSubCategoryBulkPackage($data,$sub_categories,$counts,$outletIds,$masterId,$packageId);
								$message = $result['res_arr']['message']!="" ? $result['res_arr']['message'] : "Package Updated successfully!";

							}else{
								if(!empty($sub_categories) && !empty($counts)){
									
									$result = $this->MasterAdminModel->AddServiceSubCategoryBulkPackage($data,$sub_categories,$counts,$outletIds,$masterId);
									/* Associate Package with multiple outlet*/ 
									$packageId = $result['res_arr']['insert_id'];
									
									//$this->MasterAdminModel->AssignPackageToMultipleOutlet(array('master_id'=>$data['master_id'],'package_id'=>$packageId),$outletIds);
									
								    $message = "Package added successfully!";
								}else{
									$message = 'Please Select at least one category with count!';
								}
							}						

						    if($result['success'] == 'true'){
								$this->ReturnJsonArray(true,false,$message);
								die;
							}
							elseif($result['error'] == 'true'){
								$this->ReturnJsonArray(false,true,);
								die;
							}
						
						else{
							$this->ReturnJsonArray(false,true,"Wrong way of data filling!");
							die;
						}	
					}
					elseif($data['salon_package_type'] == "Discount_SubCategory_Bulk"){
						$data['salon_package_type_selected'] = $data['salon_package_type'];
						$data['salon_package_type'] = 'Discount';
						$data['service_sub_category_bulk_index'] =  $this->input->post('service_sub_category_discount_bulk_index');
						
						$sub_categories = $this->input->post('service_sub_category_bulk');
						$discounts =  $this->input->post('discount_subcategory_bulk');
						$counts = $this->input->post('count_discount_subcategory_bulk');
					
						if(!empty($sub_categories) && !empty($discounts) && !empty($counts)){
							
						if($packageId>0 && $packageId!=""){
									
							$result = $this->MasterAdminModel->UpdateDiscountSubCategoryBulkPackage($data,$sub_categories,$discounts,$counts,$outletIds,$masterId,$packageId);
							$message = $result['res_arr']['message']!="" ? $result['res_arr']['message'] : "Package Updated successfully!";

						}else{		

							$result = $this->MasterAdminModel->AddDiscountSubCategoryBulkPackage($data,$sub_categories,$discounts,$counts,$outletIds,$masterId);
							
							/* Associate Package with multiple outlet*/ 
							$packageId = $result['res_arr']['insert_id'];
							$message = "Package added successfully!";
						}	
							//$this->MasterAdminModel->AssignPackageToMultipleOutlet(array('master_id'=>$data['master_id'],'package_id'=>$packageId),$outletIds);
							
							
							if($result['success'] == 'true'){
								$this->ReturnJsonArray(true,false,$message);
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
					
						$data['salon_package_type_selected'] = $data['salon_package_type'];
						$data['salon_package_type'] = 'Services';
						$data['service_category_bulk_index'] =  $this->input->post('service_category_bulk_index');
					  
						$categories = $this->input->post('service_category_bulk'); 
						$counts = $this->input->post('count_service_category_bulk');
						if(!empty($categories) && !empty($counts)){

							if($packageId>0 && $packageId!=""){
								
								$result = $this->MasterAdminModel->UpdateServiceCategoryBulkPackage($data,$categories,$counts,$outletIds,$masterId,$packageId);
								$message = $result['res_arr']['message']!="" ? $result['res_arr']['message'] : "Package Updated successfully!";

							}else{	

								// $this->PrettyPrintArray($categories);
								$result = $this->MasterAdminModel->AddServiceCategoryBulkPackage($data,$categories,$counts,$outletIds,$masterId);
								/* Associate Package with multiple outlet*/ 
								$packageId = $result['res_arr']['insert_id'];
								$message = "Package added successfully!";
							}

							//$this->MasterAdminModel->AssignPackageToMultipleOutlet(array('master_id'=>$data['master_id'],'package_id'=>$packageId),$outletIds);
							
							if($result['success'] == 'true'){
								$this->ReturnJsonArray(true,false,$message);
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
						$data['salon_package_type_selected'] = $data['salon_package_type'];
						$data['salon_package_type'] = 'Discount';
						$data['service_category_bulk_index'] =  $this->input->post('service_discount_category_bulk_index');
						
						$categories = $this->input->post('discount_service_category_bulk');
						$cat_price = $this->input->post('service_price_greater_than');
						$discounts =  $this->input->post('discount_category_bulk');
						$counts    = $this->input->post('count_discount_category_bulk');
					
						if(!empty($categories) && !empty($discounts) && !empty($counts)){
							
						  if($packageId>0 && $packageId!=""){
									
								$result = $this->MasterAdminModel->UpdateDiscountCategoryBulkPackage($data,$categories, $cat_price, $discounts,$counts,$outletIds,$masterId,$packageId);
								$message = $result['res_arr']['message']!="" ? $result['res_arr']['message'] : "Package Updated successfully!";

							}else{	
	
								$result = $this->MasterAdminModel->AddDiscountCategoryBulkPackage($data,$categories, $cat_price, $discounts,$counts,$outletIds,$masterId);
								/* Associate Package with multiple outlet*/ 
								$packageId = $result['res_arr']['insert_id'];

								$message = "Package added successfully!";
								
							}
							//$this->MasterAdminModel->AssignPackageToMultipleOutlet(array('master_id'=>$data['master_id'],'package_id'=>$packageId),$outletIds);
							
							if($result['success'] == 'true'){
								$this->ReturnJsonArray(true,false,$message);
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
						$data['total_count_of_services'] = $this->input->post('total_no_of_services');
						$data['salon_package_type_selected'] = $data['salon_package_type'];
						
						// $this->PrettyPrintArray($_POST);
						$where=array(
							// 'category_type'=> $_POST['category_type'],
							// 'service_price_inr' 	=> $_POST['price_greater_than'],
							'master_id'			 => $this->session->userdata['logged_in']['master_admin_id'],
							'business_admin_id'  => $this->session->userdata['logged_in']['business_admin_id'],
							'business_outlet_id' => $this->session->userdata['outlets']['current_outlet']
						);
						// $discounts =  $this->input->post('special_discount');
						$counts = 100;
					   
						if(!empty($counts)){
							
							if($packageId>0 && $packageId!=""){
									
								$result = $this->MasterAdminModel->AddDiscountServicePackage($_POST,$data,$counts,$where,$outletIds,$masterId,$packageId);
								$message = $result['res_arr']['message']!="" ? $result['res_arr']['message'] : "Package Updated successfully!";

							}else{
								
								$result = $this->MasterAdminModel->AddDiscountServicePackage($_POST,$data,$counts,$where,$outletIds,$masterId);
								/* Associate Package with multiple outlet*/ 
								$packageId = $result['res_arr']['insert_id'];
								$message = "Package added successfully!";
							}
							//$this->MasterAdminModel->AssignPackageToMultipleOutlet(array('master_id'=>$data['master_id'],'package_id'=>$packageId),$outletIds);
							
							if($result['success'] == 'true'){
								$this->ReturnJsonArray(true,false,$message);
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
				$data = $this->GetDataForMasterAdmin("Create Packages");
					
				/* Get All Outlet */
				$business_outlet_details  = $this->GetMasterOutlets();
					
				$data['categories']     = $this->GetMasterCategories($this->session->userdata['logged_in']['master_admin_id']);
				$data['sub_categories'] = $this->GetMasterSubCategories($this->session->userdata['logged_in']['master_admin_id']);
				$data['services']       = $this->GetMasterServices($this->session->userdata['logged_in']['master_admin_id']);
				$data['packages']       = $this->ActivePackages();
				$data['business_outlet_details'] = $business_outlet_details;
				/* GET Data from package Data Table for already assign */
				
				$assignOutlets = array();
				$packageDataRecords = $this->MasterAdminModel->MultiWhereSelect('mss_salon_package_data',array('master_id'=>$this->session->userdata['logged_in']['master_admin_id'],'is_active'=>'1'));
				
				if(!empty($packageDataRecords['res_arr'])){
					foreach($packageDataRecords['res_arr'] as $key=>$value){
					  if(!in_array($value['outlet_id'],$assignOutlets[$value['salon_package_id']])){	
						$assignOutlets[$value['salon_package_id']][] = $value['outlet_id'];
					  }
					}
				}	
				
                $data['assignOutlets'] = 	$assignOutlets;			
				/*if(isset($data['selected_outlet']) || !empty($data['selected_outlet'])){
					$data['services']        = $this->GetServices($this->session->userdata['outlets']['current_outlet']);
					$data['sub_categories']  = $this->GetSubCategories($this->session->userdata['outlets']['current_outlet']); 
					$data['categories']      = $this->GetCategories($this->session->userdata['outlets']['current_outlet']);	
					$data['packages']        = $this->ActivePackages($this->session->userdata['outlets']['current_outlet']);
				} */
				$this->load->view('master_admin/ma_add_packages_view',$data);
			}
		}
		else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}
	}
	
	// get service by id
	public function GetPackageDetailsById(){
		$data = array();
		if($this->IsLoggedIn('master_admin')){
			if(isset($_GET) && !empty($_GET)){
				$where = array(
					'salon_package_id'=> $_GET['packageId'],
					'outlet_id' => $_GET['outletId'],
					'master_id' => $this->session->userdata['logged_in']['master_admin_id']
				);
				
				$packageDetails = $this->MasterAdminModel->GetPackageDetailsById($where);
				$packageType = $packageDetails['res_arr'][0]['salon_package_type_selected'];
				
				$outletIds = array();
				if(!empty($packageDetails)){
					$packageData = $packageDetails['res_arr'];
					if(!empty($packageData)){
						$data = $packageData[0];
					}
					/* Get all Service Details which is assign to this package */
					
				   if($packageType=='special_membership'){
					   //array('table_id'=>array('group_index' => 'service_details_with_category'))
					   
					   $data['servicesDetails'] = $this->getServiceDetailsByPackageId($_GET['packageId'],$_GET['outletId'],$packageType);
					  
				   }else{
					   $data['servicesDetails'] = $this->getServiceDetailsByPackageId($_GET['packageId'],$_GET['outletId']);
				   }
				  
					$data['outletIds'] = $this->MasterAdminModel->getOutletsByPackageId($_GET['packageId']);
					if(!empty($data['outletIds']['res_arr'])){
						foreach($data['outletIds']['res_arr'] as $outletId){
							 if(!in_array($outletId['outlet_id'],$outletIds)) { $outletIds[] = $outletId['outlet_id']; }
						}
					}
					$data['outletIds'] = $outletIds;
					header("Content-type: application/json");
					print(json_encode($data, JSON_PRETTY_PRINT));
					die;
				}else{
					$this->ReturnJsonArray(false,true,"Something went wrong way! Try again later.");
					die;
				}
			}
		}
		else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}
	}
	
	// get service by id
	public function GetServicePriceById(){
		if($this->IsLoggedIn('master_admin')){
			if(isset($_GET) && !empty($_GET)){
				$where = array(
					'service_id'  => $_GET['service_id'],
					'service_is_active'   => TRUE
				);
				
				$data = $this->MasterAdminModel->MultiWhereSelect('master_services',$where);
				header("Content-type: application/json");
				print(json_encode($data['res_arr'], JSON_PRETTY_PRINT));
				die;
			}
		}
		else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}
	}
	
	/**
	 *
	 * @author	: Pinky Sahukar
	 * @Function : Package Management : Delete Package as temprory
	*/
	public function ChangePackageStatus(){
		if($this->IsLoggedIn('master_admin')){
			if(isset($_POST) && !empty($_POST)){
				if($this->input->post('activate') == 'true' && $this->input->post('deactivate') == 'false'){
					//Activate
					$data = array(
					  "salon_package_id"          => $this->input->post('salon_package_id'),
					  "is_active"   => FALSE
					);

					$status = $this->MasterAdminModel->Update($data,'mss_salon_packages','salon_package_id');
					if($status['success'] == 'true'){
						$this->ReturnJsonArray(true,false,"Activated successfully!");
						die;
					}
					elseif($status['error'] == 'true'){
						$this->ReturnJsonArray(false,true,$status['message']);
						die;
					}
				}elseif($this->input->post('activate') == 'false' && $this->input->post('deactivate') == 'true'){
					//Deactivate the Employee
					$data = array(
						"salon_package_id" => $this->input->post('salon_package_id'),	
						"is_active"   => TRUE
					);

					$status = $this->MasterAdminModel->Update($data,'mss_salon_packages','salon_package_id');
					
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
			$this->LogoutUrl(base_url()."MasterAdmin");
		}
	}
	
	
	
	// get all active packages
	public function GetALLMasterPackages(){
		if($this->IsLoggedIn('master_admin')){
			
				$where = array(
					'master_id'  => $this->session->userdata['logged_in']['master_admin_id'],
					'is_active'   => TRUE
				);
				
				$data = $this->MasterAdminModel->MultiWhereSelect('mss_salon_packages',$where);
				header("Content-type: application/json");
				print(json_encode($data['res_arr'], JSON_PRETTY_PRINT));
				die;
			
		}else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}
	}
	
	// get all outlet
	public function GetALLOutlets(){
		if($this->IsLoggedIn('master_admin')){
			
				$where = array(
					'master_id'  => $this->session->userdata['logged_in']['master_admin_id']
				);
				
				$data = $this->MasterAdminModel->MultiWhereSelect('mss_business_outlets',$where);
				header("Content-type: application/json");
				print(json_encode($data['res_arr'], JSON_PRETTY_PRINT));
				die;
			
		}else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}
	}
	
	public function MasterAdminAssignPackage(){
		if($this->IsLoggedIn('master_admin')){
			if(isset($_POST) && !empty($_POST)){
				$this->form_validation->set_rules('assign_package_select[]', 'Packages', 'trim|required');
				$this->form_validation->set_rules('assign_Outlets_select[]', 'Outlet', 'trim|required');
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
					$data = array();
					$packages = $this->input->post('assign_package_select');
					$outlets  = $this->input->post('assign_Outlets_select');
					foreach($packages as $packageId){
					  foreach($outlets as $outletId){	
						
						/* Check this id exist or not, if already exist then skip else assign */
						$where = array(
							'master_id'  => $this->session->userdata['logged_in']['master_admin_id'],
							'is_active'   => TRUE,
							'package_id'  => $packageId,
							'outlet_id'   => $outletId,
						);
						$record = $this->MasterAdminModel->MultiWhereSelect('mss_package_outlet_association',$where);
						
						if(isset($record['res_arr']) && empty($record['res_arr'])){
							$data[] = array(
								'master_id'  =>  $this->session->userdata['logged_in']['master_admin_id'],
								'package_id'  => $packageId,
								'outlet_id'   => $outletId
							);
						} 
						
					  }
					}
				  if(!empty($data)){	
					$result = $this->MasterAdminModel->InsertBatch($data,'mss_package_outlet_association');
					if($result['success'] == 'true'){
						$this->ReturnJsonArray(true,false,"Package has assigned successfully!");
						die;
					}
					elseif($result['error'] == 'true'){
						$this->ReturnJsonArray(false,true,$result['message']);
						die;
					}
				  }elseif(empty($data) && !empty($packages) && !empty($outlets)){
					  $this->ReturnJsonArray(true,false,"Packages has already assigned!");
					  die;
				  }
				}
			}		
	    }else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}
	}
	
	public function MasterAdminAssignProducts(){
		if($this->IsLoggedIn('master_admin')){
			if(isset($_POST) && !empty($_POST)){
				$this->form_validation->set_rules('assign_outlet_select[]', 'Outlets', 'trim|required');
				$this->form_validation->set_rules('assign-products-select[]', 'Product', 'trim|required');
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
					$outlets  = $this->input->post('assign_outlet_select');
					$services  = $this->input->post('assign-products-select');
					$data = $this->assignService($outlets,$services);
						if(!empty($data)){	
						$result = $this->MasterAdminModel->InsertBatch($data,'mss_services');
						if($result['success'] == 'true'){
							$this->ReturnJsonArray(true,false,"Product has assigned successfully!");
							die;
						}
						elseif($result['error'] == 'true'){
							$this->ReturnJsonArray(false,true,$result['message']);
							die;
						}
					  }elseif(empty($data) && !empty($outlets) && !empty($serviceId)){
						  $this->ReturnJsonArray(false,true,"Products has already assigned!");
						  die;
					  }
				}
			}
		}else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}
	}
	
	public function MasterAdminAssignServices(){
		if($this->IsLoggedIn('master_admin')){
			if(isset($_POST) && !empty($_POST)){
				$this->form_validation->set_rules('assign_outlet_select[]', 'Outlets', 'trim|required');
				$this->form_validation->set_rules('assign_Services_select[]', 'Services', 'trim|required');
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
					
					$outlets  = $this->input->post('assign_outlet_select');
					$services  = $this->input->post('assign_Services_select');
					$data = $this->assignService($outlets,$services);
				  if(!empty($data)){	
					$result = $this->MasterAdminModel->InsertBatch($data,'mss_services');
					if($result['success'] == 'true'){
						$this->ReturnJsonArray(true,false,"Services has assigned successfully!");
						die;
					}
					elseif($result['error'] == 'true'){
						$this->ReturnJsonArray(false,true,$result['message']);
						die;
					}
				  }elseif(empty($data) && !empty($outlets) && !empty($serviceId)){
					  $this->ReturnJsonArray(false,true,"Services has already assigned!");
					  die;
				  }
				}
			}		
	    }else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}
	}
	
    public function AssignOutletToService(){
		if($this->IsLoggedIn('master_admin')){
			 $recordNotExist = $insertRecords = $updateRecord = FALSE; 
			 $masterServiceId = $this->input->post('master_service_id');
			 $outletId    = $assignOutletIds   = $this->input->post('outlet_id');
			 
			 $notExist = array();
			 
			 $serviceDetails = $this->MasterAdminModel->DownloadMasterServices(array('master_id'=>$this->session->userdata['logged_in']['master_admin_id'],'service_is_active'=>TRUE,'service_type'=>'service'));
			
			 
			 $serviceDetails = $serviceDetails['res_arr'];
			 if(!empty($serviceDetails)){
				 foreach($serviceDetails as $serviceData){
					if(!empty($outletId)){	
						foreach($outletId as $outlet){  
							 if($serviceData['master_service_id']==$masterServiceId && in_array($serviceData['outlet_id'],$outletId)){
								
								while (($key = array_search($serviceData['outlet_id'], $assignOutletIds)) !== false){
									unset($assignOutletIds[$key]);
								}								

							 }elseif($serviceData['master_service_id']==$masterServiceId && !in_array($serviceData['outlet_id'],$outletId)){
								 /* Temp delete */
								 $notExist[] = $serviceData['outlet_id'];
							 }
						}
					 }else{
							    $notExist[] = $serviceData['outlet_id'];
					 }	
				 }		
				 
			    if(!empty($notExist)){
					$updateRecord = TRUE;
					foreach($notExist as $outletId){
						$this->MasterAdminModel->Update(array('is_assign_to_outlet'=>'0'),'mss_services',array('master_service_id'=>$masterServiceId,'outlet_id'=>$outletId));
					}
				} 		 
				$insertRecords = (!empty($assignOutletIds)) ? TRUE : FALSE;
			 }else{
				$recordNotExist = TRUE; 
			 }
			
			 if($recordNotExist || $insertRecords){
				 
				 /* assign service */
				 $serviceId[] = $masterServiceId;
				 if($insertRecords==TRUE){
					 $outletId = $assignOutletIds;
				 }
				 
				 $data = $this->assignService($outletId,$serviceId);
				 
				 if(!empty($data)){	
					$result = $this->MasterAdminModel->InsertBatch($data,'mss_services');
					if($result['success'] == 'true'){
						$this->ReturnJsonArray(true,false,"Service has been assigned successfully!");
						die;
					}
					elseif($result['error'] == 'true'){
						$this->ReturnJsonArray(false,true,$result['message']);
						die;
					}
				  }elseif(empty($data) && !empty($outletId) && !empty($serviceId)){
					  $this->ReturnJsonArray(false,true,"Service has already assigned!");
				  }
			 }
				 
			 if($updateRecord){
				 $this->ReturnJsonArray(true,false,"Service has been Updated successfully!");
				 die;
			 }
		 
		
		}else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}
	}
	
	public function AssignOutletToPackage(){
		if($this->IsLoggedIn('master_admin')){
			 $recordNotExist = $insertRecords = $updateRecord = FALSE; 
			 $salonPackageId = $this->input->post('salon_package_id');
			 $outletId    = $assignOutletIds   = $this->input->post('outlet_id');
			 
			 $notExist = array();
			 
			 $packageDetails = $this->MasterAdminModel->MultiWhereSelect('mss_salon_package_data',array('master_id'=>$this->session->userdata['logged_in']['master_admin_id'],'is_active'=>TRUE,'salon_package_id'=>$salonPackageId));
			
			 $serviceDetails = $masterServiceDetails = array();
			 $storeOutletId = "";
			
			 if(!empty($packageDetails['res_arr'])){
				foreach($packageDetails['res_arr'] as $packageData){
				
				  $storeOutletId = $packageData['outlet_id'];	
				  
				  $serviceDetails[$packageData['outlet_id']][] = $packageData['master_service_id'];
				  
				  $masterServiceDetails[$packageData['outlet_id']][] =  array('discount_percentage'=>$packageData['discount_percentage'],
																					'service_monthly_discount'=>$packageData['service_monthly_discount'],
																					'birthday_discount'=>$packageData['birthday_discount'],
																					'anni_discount'=>$packageData['anni_discount'],
																					'service_count'=>$packageData['service_count'],
																					'master_service_id' =>$packageData['master_service_id']
																					);
				  
				} 
			 }
			
			 $packageDetails = $packageDetails['res_arr'];
			
			 if(!empty($packageDetails)){
				 foreach($packageDetails as $packageData){
					if(!empty($outletId)){	
						foreach($outletId as $outlet){  
							 if($packageData['salon_package_id']==$salonPackageId && in_array($packageData['outlet_id'],$outletId)){
								
								while (($key = array_search($packageData['outlet_id'], $assignOutletIds)) !== false){
									unset($assignOutletIds[$key]);
								}								

							 }elseif($packageData['salon_package_id']==$salonPackageId && !in_array($packageData['outlet_id'],$outletId)){
								 /* Temp delete */
								 $notExist[] = $packageData['outlet_id'];
							 }
						}
					 }else{
							    $notExist[] = $packageData['outlet_id'];
					 }	
				 }		
				
			    if(!empty($notExist)){
					$updateRecord = TRUE;
					foreach($notExist as $outletId){
						$this->MasterAdminModel->Update(array('is_active'=>'0'),'mss_salon_package_data',array('salon_package_id'=>$salonPackageId,'outlet_id'=>$outletId));
					}
				} 		 
				$insertRecords = (!empty($assignOutletIds)) ? TRUE : FALSE;
			 }else{
				$recordNotExist = TRUE; 
			 }
			
			 if($recordNotExist || $insertRecords){
				 
				 /* assign Product */
				
				 if($insertRecords==TRUE){
					 $outletId = $assignOutletIds;
				 }
				
				 $data = array();
				 
			  foreach($outletId as $outlet){
                    $copyRecordsFrom = current($masterServiceDetails);
					if(!empty($copyRecordsFrom)){
						foreach($copyRecordsFrom as $packageDataValue){
							
						$data[] = array('master_id'					=>$this->session->userdata['logged_in']['master_admin_id'],
										'salon_package_id'			=>$salonPackageId,
										'master_service_id'			=>$packageDataValue['master_service_id'],
										'outlet_id'					=>$outlet,
										'discount_percentage'		=>$packageDataValue['discount_percentage'],
										'service_monthly_discount'  =>$packageDataValue['service_monthly_discount'],
										'birthday_discount'		    =>$packageDataValue['birthday_discount'],
										'anni_discount'			    =>$packageDataValue['anni_discount'],
										'service_count'			    =>$packageDataValue['service_count']);
						}
					}	 
			  }
			
				
				 if(!empty($data)){	
					$result = $this->MasterAdminModel->InsertBatch($data,'mss_salon_package_data');
					if($result['success'] == 'true'){
						$this->ReturnJsonArray(true,false,"Package has been assigned successfully!");
						die;
					}
					elseif($result['error'] == 'true'){
						$this->ReturnJsonArray(false,true,$result['message']);
						die;
					}
				  }elseif(empty($data) && !empty($outletId) && !empty($salonPackageId)){
					  $updateRecord = false;
					  $this->ReturnJsonArray(false,true,"Something Went Wrong!Please try again later!");
				  }
			 }
				 
			 if($updateRecord){
				 $this->ReturnJsonArray(true,false,"Package has been Updated successfully!");
				 die;
			 }
		 
		
		}else{
			$this->LogoutUrl(base_url()."MasterAdmin");
		}
	}
	/**
	 *
	 * @author	: Pinky Sahukar
	 * @Function : Package Management & Add new Package into Master Table 
	*/
	public function GetMasterPackages(){
	
		if($this->IsLoggedIn('master_admin')){
					
			$records = array();
			
			/* Get All Outlet */
			$business_outlet_details  = $this->GetMasterOutlets();
			
			## Read value
			$draw = $_POST['draw'];
			$row = $_POST['start'];
			
			$rowperpage = $_POST['length']; // Rows display per page
			$columnIndex = $_POST['order'][0]['column']; // Column index
			$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
			$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
			$searchValue = $_POST['search']['value']; // Search value
			
			$where = array(
				'master_id'  => $this->session->userdata['logged_in']['master_admin_id'],
				'business_outlet_id' => $_POST['outletId']
			);
			
            $filterData = array('columnName'=>$columnName,
								'columnSortOrder'=>$columnSortOrder,
								'searchValue' => $searchValue,
								'rowperpage'=>$rowperpage,
								//'offset'=>(($row - 1) * $rowperpage + 1)
								'row' => (!empty($row) && $row!="") ? $row : 0
								);
			
			/* Get total records count */
			$totalRecords     = $this->MasterAdminModel->GetAllPackagesCount($where,$filterData);
			
			$totalRecords     = (!empty($totalRecords['res_arr'])) ? $totalRecords['res_arr'] : 0;
			
			$data['packages'] = $this->MasterAdminModel->GetAllPackages($where,$filterData);
			
			/* GET Data from package Data Table for already assign */
			$assignOutlets = array();
			$packageDataRecords = $this->MasterAdminModel->MultiWhereSelect( 'mss_salon_package_data',array('master_id'=>$this->session->userdata['logged_in']['master_admin_id'],'is_active'=>'1'));
			if(!empty($packageDataRecords['res_arr'])){
				foreach($packageDataRecords['res_arr'] as $key=>$value){
				  if(!in_array($value['outlet_id'],$assignOutlets[$value['salon_package_id']])){	
					$assignOutlets[$value['salon_package_id']][] = $value['outlet_id'];
				  }
				}
			}
			
			if(!empty($data['packages']['res_arr'])){
				
			    foreach($data['packages']['res_arr'] as $key=>$package){
					
					$options = '';
					 if(!empty($business_outlet_details)){
						 foreach($business_outlet_details as $outlet){
							 $assignOutletIds = $assignOutlets[$package['salon_package_id']];
							
							 if(in_array($outlet['business_outlet_id'],$assignOutletIds)){
								 $selected = 'selected="selected"';
							 }else{
								 $selected = "";
							 }
							 $options .= '<option value="'.$outlet['business_outlet_id'].'" '.$selected.' >'.$outlet['business_outlet_name'].'</option>';
						 }
					 }
					
					$action ='<span  class="btn package-edit-btn" salon_package_id="'.$package['salon_package_id'].'">';
								$action .='<i class="fas fa-edit"></i>';
								$action .='</span>';
					if($package['package_active_status'] == 1){
						
							$action .='<button type="button" class="btn btn-success package-deactivate-btn" salmoneyFormaton_package_id="'.$package['salon_package_id'].'">';
							$action .='<i class="align-middle" data-feather="package"></i>';
							$action .='</button>';
					}
					else{
								
								$action .='<button type="button" class="btn btn-danger package-activate-btn" salon_package_id="'.$package['salon_package_id'].'">';
								$action .='<i class="fas fa-ban align-middle" ></i>';
								$action .='</button>';
					}
				 				 
				  $records[] = array( 
							'salon_package_id'	    => $key+1,
							'salon_package_name'    => $package['salon_package_name'],    
							'salon_package_type'    => $package['salon_package_type'],    
							'salon_package_price'   => moneyFormat($package['salon_package_price']),    
							'service_gst_percentage'=> moneyFormat($package['service_gst_percentage']),
							'total'  			    => moneyFormat($package['salon_package_upfront_amt']),
							'salon_package_validity'=> $package['salon_package_validity'],
							'salon_package_date'    => $package['salon_package_date'],  
							'salon_package_end_date'=> $package['salon_package_end_date'],  
							'outlet_valid_for'		=> ($package['type']==1) ? '<select class="outlet_valid_for" multiple name="outlet_valid_for[]" salon_package_id="'.$package['salon_package_id'].'">'.$options.'</select>' : '--',
							'outlet_created_by'     => ($package['type']==1) ? 'Master Admin' : ($package['type']==2 ? 'Business Admin' : ''), 
							'package_active_status' => $action
							
				   );
				}
			}
			## Response
			$response = array(
			  "draw" => intval($draw),
			  "iTotalRecords" => $totalRecords,
			  "iTotalDisplayRecords" => ($totalRecords>count($records)) ? $totalRecords : count($records),
			  "aaData" => $records
			);

			echo json_encode($response);
			
		}else{
			$this->LogoutUrl(base_url()."MasterAdmin");
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

}

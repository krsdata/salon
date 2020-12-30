

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Script extends CI_Controller {
    
	
//constructor of the Alumni Controller
   public function __construct(){
	   parent::__construct();
	   date_default_timezone_set('Asia/Kolkata');
	   $this->load->model('MasterAdminModel');
	   $this->load->helper('common_helper');
	}
   
	public function index(){
	  // $this->load->view('welcome_message');
	  redirect(base_url('home'), 'refresh');
	}

	
    public function store_master_id_into_outlet_table(){
      $dataRecords = $this->MasterAdminModel->FullTable('mss_business_admin');
	 
	  if(isset($dataRecords['res_arr']) && !empty($dataRecords['res_arr'])){
		  
		  foreach($dataRecords['res_arr'] as $value){
			  $data['master_id']=$value['business_master_admin_id'];
			  $data['business_outlet_business_admin']=$value['business_admin_id'];
			  $this->MasterAdminModel->Update($data,'mss_business_outlets','business_outlet_business_admin');
			  echo $this->db->last_query();
	          echo '<br/>';
			  echo '<br/>';
		  }
		  
	  }
	  
	  //UPDATE `mss_business_outlets` SET `master_id`=1 WHERE `business_outlet_business_admin`=1
	  
	}
	
	public function store_master_id_into_master_category_table(){
		$dataRecords = $this->MasterAdminModel->FullTable('mss_business_admin');
	 
	  if(isset($dataRecords['res_arr']) && !empty($dataRecords['res_arr'])){
		  
		  foreach($dataRecords['res_arr'] as $value){
			  $data['master_id']=$value['business_master_admin_id'];
			  $data['category_business_admin_id']=$value['business_admin_id'];
			  $this->MasterAdminModel->Update($data,'master_categories','category_business_admin_id');
			  echo $this->db->last_query();
	          echo '<br/>';
			  echo '<br/>';
		  }
		  
	  }
		
	}
	
	public function store_master_id_into_master_sub_category_table(){
		$dataRecords = $this->MasterAdminModel->FullTable('master_categories');
	 
	  if(isset($dataRecords['res_arr']) && !empty($dataRecords['res_arr'])){
		  
		  foreach($dataRecords['res_arr'] as $value){
			  $data['master_id']=$value['master_id'];
			  $data['sub_category_business_admin_id	']=$value['category_business_admin_id'];
			  $data['sub_category_category_id']=$value['category_id'];
			  $this->MasterAdminModel->Update($data,'mss_sub_categories','sub_category_category_id');
			  echo $this->db->last_query();
	          echo '<br/>';
			  echo '<br/>';
		  }
		  
	  }
	}
	
	public function store_outlet_id_into_service_table(){
		$dataRecords = $this->MasterAdminModel->GetOutletIdFromCategoryForService();
	
	  if(isset($dataRecords['res_arr']) && !empty($dataRecords['res_arr'])){
		  
		  foreach($dataRecords['res_arr'] as $value){
			 
			  $data['outlet_id']=$value['category_business_outlet_id'];
			  $data['service_id']=$value['service_id'];
			  $this->MasterAdminModel->Update($data,'mss_services','service_id');
			  echo $this->db->last_query();
	          echo '<br/>';
			  echo '<br/>';
		  }
		  
	  }
	}
	
	public function store_master_id_into_package_table(){
		$dataRecords = $this->MasterAdminModel->FullTable('mss_business_admin');
	 
	  if(isset($dataRecords['res_arr']) && !empty($dataRecords['res_arr'])){
		  
		  foreach($dataRecords['res_arr'] as $value){
			  $data['master_id']=$value['business_master_admin_id'];
			  $data['business_admin_id']=$value['business_admin_id'];
			  $this->MasterAdminModel->Update($data,'mss_salon_packages','business_admin_id');
			  echo $this->db->last_query();
	          echo '<br/>';
			  echo '<br/>';
		  }
		  
	  }
		
	}
	
	public function store_outlet_id_and_admin_id_into_package_data_table(){
	  $dataRecords = $this->MasterAdminModel->FullTable('mss_salon_packages'); 
	
	  if(isset($dataRecords['res_arr']) && !empty($dataRecords['res_arr'])){
		  
		  foreach($dataRecords['res_arr'] as $value){
			 
			  $data['master_id']=$value['master_id'];
			  $data['business_admin_id']=$value['business_admin_id'];
			  $data['outlet_id']=$value['business_outlet_id'];
			  $data['salon_package_id']=$value['salon_package_id'];
			  $this->MasterAdminModel->Update($data,'mss_salon_package_data','salon_package_id');
			  echo $this->db->last_query();
	          echo '<br/>';
			  echo '<br/>';
		  }
		  
	  }
	}
	
	
	
}
